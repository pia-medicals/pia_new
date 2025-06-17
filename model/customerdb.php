<?php

class customerdb extends Model
{
    private $connection;

    public function __construct($con)
    {
        $this->connection = $con;
    }

    public function getClientaccId($user_id)
    {
        $client_accid = '';
        $sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($client_accid);
        $stmt->fetch();
        $stmt->close();

        return !empty($client_accid) ? $client_accid : null;  // Return null if no result
    }

    //////////////////////With pagination//////////////////////
    //     public function getStudyData($client_accid, $start, $length)
    //     {
    //         $sql = "SELECT 
    //     s.accession, 
    //     s.patient_name, 
    //     s.actual_tat AS default_tat, 
    //     s.analyst_id AS assignee, 
    //     s.second_analyst_id AS second_check, 
    //     s.comment AS description, 
    //     s.status_ids AS status, 
    //     COALESCE(s.created_at, '') AS original_date,
    //     COALESCE(d.webhook_customer, '') AS webhook_customer
    // FROM studies s
    // LEFT JOIN dicom_webhook_details d ON s.dicom_webhook_ids = d.dicom_webhook_id
    // WHERE s.client_account_ids = ? LIMIT ?, ?;  
    // ";

    //         $stmt = $this->connection->prepare($sql);

    //         if (!$stmt) {
    //             die("Prepare failed: " . $this->connection->error); // Show SQL error
    //         }

    //         $stmt->bind_param("iii", $client_accid, $start, $length);
    //         $stmt->execute();
    //         $result = $stmt->get_result();

    //         $data = [];
    //         while ($row = $result->fetch_assoc()) {
    //             $data[] = $row; //First success
    //         }

    //         return $data;
    //     }
    //////////////////////With pagination//////////////////////

    //With select filters
    public function getStudyData($client_accid, $start, $length, $days, $assignee, $second_assignee, $status, $search_value)
    {
        $query = "SELECT 
        s.accession,
        s.mrn, 
        s.patient_name, 
        s.actual_tat AS default_tat, 
        s.analyst_id AS assignee, 
        s.second_analyst_id AS second_check, 
        s.comment AS description, 
        s.status_ids AS status, 
        COALESCE(s.created_at, '') AS original_date,
        COALESCE(d.webhook_customer, '') AS webhook_customer
    FROM studies s
    LEFT JOIN dicom_webhook_details d ON s.dicom_webhook_ids = d.dicom_webhook_id
    WHERE s.client_account_ids = ?";

        // Apply Filters
        $params = [$client_accid];

        if (!empty($days)) {
            $query .= " AND s.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
            $params[] = $days;
        }
        if (!empty($assignee)) {
            $query .= " AND s.analyst_id = ?";
            $params[] = $assignee;
        }
        if (!empty($second_assignee)) {
            $query .= " AND s.second_analyst_id = ?";
            $params[] = $second_assignee;
        }
        if (!empty($status)) {
            $query .= " AND s.status_ids = ?";
            $params[] = $status;
        }

        // Apply Global Search
        if (!empty($search_value)) {
            $query .= " AND (s.accession LIKE ? 
            OR s.mrn = ?
                     OR s.patient_name LIKE ? 
                     OR s.comment LIKE ?)";
            $search_param = "%{$search_value}%";
            array_push($params, $search_param, $search_value, $search_param, $search_param);
        }

        // Debugging: Print Query & Parameters
        // echo "Final Query: " . $query . "<br>";
        // echo "Params: " . json_encode($params);
        // exit;

        $query .= " LIMIT ?, ?";
        $params[] = $start;
        $params[] = $length;

        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $this->connection->error);
        }

        $types = "i"; // client_accid
        if (!empty($days)) $types .= "i";
        if (!empty($assignee)) $types .= "i";
        if (!empty($second_assignee)) $types .= "i";
        if (!empty($status)) $types .= "i";
        if (!empty($search_value)) $types .= "ssss"; // 3 strings for search
        $types .= "ii"; // start and length

        $stmt->bind_param($types, ...$params);

        // $stmt->bind_param(str_repeat("i", count($params)), ...$params);
        // $stmt->bind_param(str_repeat("i", count($params) - 2) . "ii", ...$params);

        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }



    public function getFilteredStudyCount($client_accid, $days, $assignee, $second_assignee, $status, $search_value)
    {
        $query = "SELECT COUNT(*) as total FROM studies WHERE client_account_ids = ?";
        $params = [$client_accid];

        if (!empty($days)) {
            $query .= " AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
            $params[] = $days;
        }
        if (!empty($assignee)) {
            $query .= " AND analyst_id = ?";
            $params[] = $assignee;
        }
        if (!empty($second_assignee)) {
            $query .= " AND second_analyst_id = ?";
            $params[] = $second_assignee;
        }
        if (!empty($status)) {
            $query .= " AND status_ids = ?";
            $params[] = $status;
        }
        if (!empty($search_value)) {
            $query .= " AND (accession LIKE ? OR patient_name LIKE ? OR comment LIKE ?)";
            $search_param = "%{$search_value}%";
            array_push($params, $search_param, $search_param, $search_param);
        }

        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->connection->error);
        }

        $types = "i"; // client_accid
        if (!empty($days)) $types .= "i";
        if (!empty($assignee)) $types .= "i";
        if (!empty($second_assignee)) $types .= "i";
        if (!empty($status)) $types .= "i";
        if (!empty($search_value)) $types .= "sss"; // 3 strings for search

        $stmt->bind_param($types, ...$params);

        // $stmt->bind_param(str_repeat("i", count($params)), ...$params);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total;
    }



    // Function to get total record count
    public function getTotalStudyCount($client_accid)
    {
        $sql = "SELECT COUNT(*) as total FROM studies WHERE client_account_ids = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $client_accid);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total;
    }

    //////////////////////Set limits//////////////////////
    // public function fetchAssignees($search, $page)
    // {
    //     $limit = 15;
    //     $offset = ($page - 1) * $limit;

    //     // $con = $this->getConnection(); // Ensure this function returns a MySQLi connection

    //     $sql = "SELECT user_id, user_name FROM users WHERE user_type_ids = 3";
    //     $params = [];

    //     if (!empty($search)) {
    //         $sql .= " AND user_name LIKE ?";
    //         $params[] = "%$search%";
    //     }
    //     $sql .= " LIMIT ?, ?";

    //     $stmt = $this->connection->prepare($sql);

    //     if (!empty($search)) {
    //         $stmt->bind_param("sii", $params[0], $offset, $limit);
    //     } else {
    //         $stmt->bind_param("ii", $offset, $limit);
    //     }

    //     $stmt->execute();
    //     $stmt->bind_result($user_id, $user_name);

    //     $items = [];
    //     while ($stmt->fetch()) {
    //         $items[] = [
    //             "id" => $user_id,
    //             "text" => $user_name
    //         ];
    //     }

    //     $stmt->close();
    //     // $connection->close();

    //     return [
    //         "items" => $items,
    //         "more" => count($items) === $limit
    //     ];
    // }




    // public function fetchStatuses($search, $page)
    // {
    //     $limit = 15;
    //     $offset = ($page - 1) * $limit;

    //     // $con = $this->getConnection(); // Ensure this function returns a MySQLi connection

    //     $sql = "SELECT status_id, status FROM analysis_status";
    //     $params = [];

    //     if (!empty($search)) {
    //         $sql .= " WHERE status LIKE ?";
    //         $params[] = "%$search%";
    //     }
    //     $sql .= " LIMIT ?, ?";

    //     $stmt = $this->connection->prepare($sql);

    //     if (!empty($search)) {
    //         $stmt->bind_param("sii", $params[0], $offset, $limit);
    //     } else {
    //         $stmt->bind_param("ii", $offset, $limit);
    //     }

    //     $stmt->execute();
    //     $stmt->bind_result($status_id, $status);

    //     $items = [];
    //     while ($stmt->fetch()) {
    //         $items[] = [
    //             "id" => $status_id,
    //             "text" => $status
    //         ];
    //     }

    //     $stmt->close();
    //     // $con->close();

    //     return [
    //         "items" => $items,
    //         "more" => count($items) === $limit
    //     ];
    // }
    //////////////////////Set limits//////////////////////


    //////////////////////Without limits//////////////////////
    public function fetchAssignees($search)
    {
        $sql = "SELECT user_id, user_name FROM users WHERE user_type_ids = 3";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND user_name LIKE ?";
            $params[] = "%$search%";
        }

        $stmt = $this->connection->prepare($sql);

        if (!empty($search)) {
            $stmt->bind_param("s", $params[0]);
        }

        $stmt->execute();
        $stmt->bind_result($user_id, $user_name);

        $items = [];
        while ($stmt->fetch()) {
            $items[] = [
                "id" => $user_id,
                "text" => $user_name
            ];
        }

        $stmt->close();

        return [
            "items" => $items
        ];
    }

    public function fetchStatuses($search)
    {
        $sql = "SELECT status_id, status FROM analysis_status";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE status LIKE ?";
            $params[] = "%$search%";
        }

        $stmt = $this->connection->prepare($sql);

        if (!empty($search)) {
            $stmt->bind_param("s", $params[0]);
        }

        $stmt->execute();
        $stmt->bind_result($status_id, $status);

        $items = [];
        while ($stmt->fetch()) {
            $items[] = [
                "id" => $status_id,
                "text" => $status
            ];
        }

        $stmt->close();

        return [
            "items" => $items
        ];
    }
    //////////////////////Without limits//////////////////////



    public function getClientaccId_siteCode($user_id)
    {
        $client_accid = '';
        $client_id = '';
        $site_code = '';
        $sql = "SELECT client_account_id, client_ids, site_code FROM client_details WHERE user_ids = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($client_accid, $client_id, $site_code);
        $stmt->fetch();
        $stmt->close();

        // return !empty($client_accid) ? $client_accid : null;  // Return null if no result
        return (!empty($client_accid)) ?
            ['client_accid' => $client_accid, 'client_id' => $client_id, 'site_code' => $site_code] : null;
    }

    public function getClientName($client_id)
    {
        $client_number = '';
        $client_name = '';
        $sql = "SELECT client_number, client_name from clients WHERE client_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $client_id);
        $stmt->execute();
        $stmt->bind_result($client_number, $client_name);
        $stmt->fetch();
        $stmt->close();

        return (!empty($client_number)) ?
            ['client_number' => $client_number, 'client_name' => $client_name] : null;
    }

    public function getStatData($client_accid, $start, $length, $days, $assignee, $second_assignee, $status, $search_value)
    {
        $query = "SELECT 
        s.studies_id, 
        s.client_account_ids, 
        s.accession,  
        s.mrn, 
        s.patient_name, 
        s.client_site_name, 
        s.analyst_id AS assignee, 
        s.second_analyst_id AS second_check,  
        s.dicom_webhook_ids, 
        s.status_ids AS status,
        COALESCE(s.completed_time, '') AS completed_time,
        COALESCE(s.created_at, '') AS original_date,  
        s.actual_tat AS default_tat,  
        a.analysis_performed_id, 
        a.studies_ids 
    FROM studies s 
    JOIN analyses_performed a ON s.studies_id = a.studies_ids 
    WHERE  s.client_account_ids = ?";


        // Apply Filters
        $params = [$client_accid];

        if (!empty($days)) {
            $query .= " AND s.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
            $params[] = $days;
        }
        if (!empty($assignee)) {
            $query .= " AND s.analyst_id = ?";
            $params[] = $assignee;
        }
        if (!empty($second_assignee)) {
            $query .= " AND s.second_analyst_id = ?";
            $params[] = $second_assignee;
        }
        if (!empty($status)) {
            $query .= " AND s.status_ids = ?";
            $params[] = $status;
        }

        // Apply Global Search
        if (!empty($search_value)) {
            $query .= " AND (s.accession LIKE ? 
                     OR s.patient_name LIKE ? 
                     OR s.comment LIKE ?)";
            $search_param = "%{$search_value}%";
            array_push($params, $search_param, $search_param, $search_param);
        }

        $query .= " LIMIT ?, ?";
        $params[] = $start;
        $params[] = $length;

        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $this->connection->error);
        }

        $types = "i"; // client_accid
        if (!empty($days)) $types .= "i";
        if (!empty($assignee)) $types .= "i";
        if (!empty($second_assignee)) $types .= "i";
        if (!empty($status)) $types .= "i";
        if (!empty($search_value)) $types .= "sss"; // 3 strings for search
        $types .= "ii"; // start and length

        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function getFilteredStatCount($client_accid, $days, $assignee, $second_assignee, $status, $search_value)
    {
        $query = "SELECT COUNT(*) as total FROM studies s 
              JOIN analyses_performed a ON s.studies_id = a.studies_ids 
              WHERE s.client_account_ids = ?";
        $params = [$client_accid];

        if (!empty($days)) {
            $query .= " AND s.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
            $params[] = $days;
        }
        if (!empty($assignee)) {
            $query .= " AND s.analyst_id = ?";
            $params[] = $assignee;
        }
        if (!empty($second_assignee)) {
            $query .= " AND s.second_analyst_id = ?";
            $params[] = $second_assignee;
        }
        if (!empty($status)) {
            $query .= " AND s.status_ids = ?";
            $params[] = $status;
        }
        if (!empty($search_value)) {
            $query .= " AND (s.accession LIKE ? OR s.patient_name LIKE ? OR s.comment LIKE ?)";
            $search_param = "%{$search_value}%";
            array_push($params, $search_param, $search_param, $search_param);
        }

        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->connection->error);
        }

        $types = "i"; // client_accid
        if (!empty($days)) $types .= "i";
        if (!empty($assignee)) $types .= "i";
        if (!empty($second_assignee)) $types .= "i";
        if (!empty($status)) $types .= "i";
        if (!empty($search_value)) $types .= "sss"; // 3 strings for search

        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total;
    }

    // public function getTotalStatCount($client_accid, $days, $assignee, $second_assignee, $status, $search_value)
    // {
    //     $query = "SELECT COUNT(*) as total FROM studies s 
    //           JOIN analyses_performed a ON s.studies_id = a.studies_ids 
    //           WHERE s.client_account_ids = ?";

    //     $params = [$client_accid];

    //     if (!empty($days)) {
    //         $query .= " AND s.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
    //         $params[] = $days;
    //     }
    //     if (!empty($assignee)) {
    //         $query .= " AND s.analyst_id = ?";
    //         $params[] = $assignee;
    //     }
    //     if (!empty($second_assignee)) {
    //         $query .= " AND s.second_analyst_id = ?";
    //         $params[] = $second_assignee;
    //     }
    //     if (!empty($status)) {
    //         $query .= " AND s.status_ids = ?";
    //         $params[] = $status;
    //     }

    //     if (!empty($search_value)) {
    //         $query .= " AND (s.accession LIKE ? 
    //                  OR s.patient_name LIKE ? 
    //                  OR s.comment LIKE ?)";
    //         $search_param = "%{$search_value}%";
    //         array_push($params, $search_param, $search_param, $search_param);
    //     }

    //     $stmt = $this->connection->prepare($query);

    //     if (!$stmt) {
    //         die("Prepare failed: " . $this->connection->error);
    //     }

    //     $types = "i"; // client_accid
    //     if (!empty($days)) $types .= "i";
    //     if (!empty($assignee)) $types .= "i";
    //     if (!empty($second_assignee)) $types .= "i";
    //     if (!empty($status)) $types .= "i";
    //     if (!empty($search_value)) $types .= "sss"; // 3 strings for search

    //     $stmt->bind_param($types, ...$params);
    //     $stmt->execute();
    //     $stmt->bind_result($total);
    //     $stmt->fetch();
    //     $stmt->close();

    //     return $total;
    // }

    public function getTotalStatCount($client_accid)
    {
        $query = "SELECT COUNT(*) as total FROM studies s 
              JOIN analyses_performed a ON s.studies_id = a.studies_ids 
              WHERE s.client_account_ids = ?";

        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $this->connection->error);
        }

        $stmt->bind_param("i", $client_accid);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total;
    }

    // Add this function to your model (Admindb)
    public function get_status_ids_by_name($statusName)
    {

        $statusName = $this->connection->escape($statusName);
        $query = $this->connection->query("SELECT status_id FROM status WHERE status_name LIKE '%" . $statusName . "%'");

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $status_ids = array_column($result, 'status_id');
            return $status_ids;
        }
        return [];
    }
}
