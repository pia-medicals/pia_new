<?php

class reportdb extends Model {

    public $mysqli;

    function __construct($con) {
        $this->mysqli = $con;
    }

    public function isValidDate($date, $format = 'm-d-Y') {
        $date = trim($date);
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function user_obj($email) {
        $sql_query = "SELECT * FROM users WHERE email='$email'";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows == 1) {
            while ($obj = $result->fetch_object()) {

                $return = $obj;
            }
        } else {
            $return = false;
        }
        return $return;
    }

    public function get_max_discount_by_customer($id) {
        $data = array();
        $sql_query = "SELECT MAX(maximum_volume) as max_value 
                      FROM `monthly_volume_discount` 
                      WHERE `client_account_ids` = $id 
                      AND `is_active` = '1'";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function billing_summary_analyst($sdate = '', $edate = '', $cid = []) {
        $data = [];

        $start_date = !empty($sdate) ? $sdate . '-01' : '';
        $end_date = !empty($edate) ? $edate . '-01' : '';

        if (empty($cid)) {
            $analysts = $this->get_analyst_names();
            foreach ($analysts as $key => $value) {
                $cid[] = $value['user_id'];
            }
        }

        $sql_query = "SELECT t1.analysis_client_price, t1.created_at, t2.analysis_code, t2.analysis_name, t2.analysis_invoicing_description, t3.accession, t3.mrn, t3.completed_time, t3.analyst_id, t4.user_name as analyst_name, t5.site_code, t6.client_name, t6.client_number "
                . "FROM analyses_performed t1 "
                . "INNER JOIN analyses_client_price_details t2 ON (t1.analysis_client_price_ids = t2.analysis_client_price_id) "
                . "INNER JOIN studies t3 ON (t3.studies_id = t1.studies_ids) "
                . "INNER JOIN users t4 ON (t4.user_id=t3.analyst_id) "
                . "INNER JOIN client_details t5 ON (t5.client_account_id = t3.client_account_ids) "
                . "INNER JOIN clients t6 ON (t6.client_id = t5.client_ids) "
                . "WHERE t3.status_ids = 1";
        if (!empty($start_date)) {
            $sql_query .= " AND t1.created_at >= '$start_date'";
        }
        if (!empty($end_date)) {
            $sql_query .= " AND t1.created_at <= LAST_DAY('$end_date')";
        }
        if (!empty($cid)) {
            $sql_query .= " AND t3.analyst_id IN (" . implode(',', $cid) . ")";
        }
        //  $sql_query .= " ORDER BY t3.studies_id ASC";
        $sql_query .= " ORDER BY t4.user_name ASC";

        $result = $this->mysqli->query($sql_query);
        if (!empty($result->num_rows)) {
            while ($row = $result->fetch_assoc()) {
                $analyst_id = $row['analyst_id'];
                $data[$analyst_id][] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function get_analyst_names() {
        $data = [];
        $sql_query = "SELECT user_id, user_name FROM users WHERE is_deleted = '0' AND user_type_ids = '3' ORDER BY user_name ASC";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function get_customer_names() {
        $data = [];
        $sql_query = "SELECT user_id, user_name, email, created_at, is_active FROM `users` WHERE user_type_ids = 5 AND is_deleted!='1' ORDER BY user_name ASC";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function get_status_lists() {
        $data = [];
        $sql_query = "SELECT status_id, status, status_description FROM analysis_status WHERE is_active = '1' AND is_deleted = '0'";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function billing_summary_customer($sdate = '', $edate = '', $cid = []) {
        $data = [];

        $start_date = !empty($sdate) ? $sdate . '-01' : '';
        $end_date = !empty($edate) ? $edate . '-01' : '';

        if (empty($cid)) {
            $customers = $this->get_customer_names();
            foreach ($customers as $key => $value) {
                $cid[] = $value['user_id'];
            }
        }

        $cid_string = implode(',', $cid);

        $sql_query = "SELECT
    COUNT(DISTINCT ap.analysis_performed_id) AS qty, 
    u.user_name, u.user_id, 
    ac.analysis_name,
    ac.analysis_code, 
    ac.analysis_client_price, 
    ac.analysis_invoicing_description, 
    (COUNT(DISTINCT ap.analysis_performed_id) * ac.analysis_client_price) AS amount  
FROM
    analyses_performed ap
JOIN
    analyses_client_price_details ac ON (ap.analysis_client_price_ids = ac.analysis_client_price_id)  
JOIN
    studies s ON (ap.studies_ids = s.studies_id)  
JOIN 
    client_details cd ON (s.client_account_ids = cd.client_account_id)  
JOIN 
    clients c ON (cd.client_ids = c.client_id)  
JOIN 
    users u ON (cd.user_ids = u.user_id)  
WHERE
    ap.is_deleted = '0' 
    AND ac.is_deleted = '0' 
    AND s.is_deleted = '0'
    AND cd.is_deleted = '0'
    AND cd.is_active = '1'
    AND c.is_deleted = '0'
    AND c.is_active = '1'
    AND u.is_deleted = '0'
    AND u.is_active = '1'
    AND s.status_ids = 1
    AND u.user_id IN ($cid_string)";
        if (!empty($start_date)) {
            $sql_query .= " AND ap.created_at >= '$start_date'";
        }
        if (!empty($end_date)) {
            $sql_query .= " AND ap.created_at <= LAST_DAY('$end_date')";
        }
        $sql_query .= " GROUP BY u.user_name, u.user_id, ac.analysis_name, ac.analysis_code, ac.analysis_client_price, ac.analysis_invoicing_description";

        $result = $this->mysqli->query($sql_query);
        if (!empty($result->num_rows)) {
            while ($row = $result->fetch_assoc()) {
                $user_id = $row['user_id'];
                $data[$user_id][] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function billing_summary_detailed($sdate = '', $edate = '', $cid = []) {
        $data = [];

        $start_date = !empty($sdate) ? $sdate . '-01' : '';
        $end_date = !empty($edate) ? $edate . '-01' : '';

        if (empty($cid)) {
            $customers = $this->get_customer_names();
            foreach ($customers as $key => $value) {
                $cid[] = $value['user_id'];
            }
        }

        $cid_string = implode(',', $cid);

        /*
          $sql_query = "SELECT
          u.user_name, u.user_id,
          ac.analysis_name,
          ac.analysis_code,
          ac.analysis_client_price,
          c.client_number,
          c.client_name,
          cd.site_code,
          s.patient_name,
          s.mrn,
          s.accession,
          ap.created_at,
          ap.created_by as assignee_id,
          s.analyst_id,
          s.second_analyst_id
          FROM
          analyses_performed ap
          JOIN
          analyses_client_price_details ac ON (ap.analysis_client_price_ids = ac.analysis_client_price_id)
          JOIN
          studies s ON (ap.studies_ids = s.studies_id)
          JOIN
          client_details cd ON (s.client_account_ids = cd.client_account_id)
          JOIN
          clients c ON (cd.client_ids = c.client_id)
          JOIN
          users u ON (cd.user_ids = u.user_id)
          WHERE
          ap.is_deleted = '0'
          AND ac.is_deleted = '0'
          AND s.is_deleted = '0'
          AND cd.is_deleted = '0'
          AND cd.is_active = '1'
          AND c.is_deleted = '0'
          AND c.is_active = '1'
          AND u.is_deleted = '0'
          AND u.is_active = '1'
          AND s.status_ids = 1
          AND u.user_id IN ($cid_string)";
         */

        $sql_query = "SELECT
    u.user_name AS client_user_name, 
    u.user_id, 
    ac.analysis_name,
    ac.analysis_code, 
    ac.analysis_client_price, 
    c.client_number,
    c.client_name,
    cd.site_code,
    s.patient_name,
    s.mrn,
    s.accession,
    ap.created_at,
    s.second_analyst_id,
    second_analyst.user_name AS second_analyst_name,    
    s.analyst_id AS assignee_id,
    analyst.user_name AS assignee_name
FROM
    analyses_performed ap
JOIN
    analyses_client_price_details ac 
    ON ap.analysis_client_price_ids = ac.analysis_client_price_id  
JOIN
    studies s 
    ON ap.studies_ids = s.studies_id  
JOIN 
    client_details cd 
    ON s.client_account_ids = cd.client_account_id  
JOIN 
    clients c 
    ON cd.client_ids = c.client_id  
JOIN 
    users u 
    ON cd.user_ids = u.user_id 
LEFT JOIN 
    users second_analyst 
    ON s.second_analyst_id = second_analyst.user_id
LEFT JOIN 
    users analyst 
    ON s.analyst_id = analyst.user_id    
WHERE
    ap.is_deleted = '0' 
    AND ac.is_deleted = '0' 
    AND s.is_deleted = '0'
    AND cd.is_deleted = '0'
    AND cd.is_active = '1'
    AND c.is_deleted = '0'
    AND c.is_active = '1'
    AND u.is_deleted = '0'
    AND u.is_active = '1'
    AND s.status_ids = 1
    AND u.user_id IN ($cid_string)";

        if (!empty($start_date)) {
            $sql_query .= " AND ap.created_at >= '$start_date'";
        }
        if (!empty($end_date)) {
            $sql_query .= " AND ap.created_at <= LAST_DAY('$end_date')";
        }
        $sql_query .= " ORDER BY u.user_name ASC";

        $result = $this->mysqli->query($sql_query);
        if (!empty($result->num_rows)) {
            while ($row = $result->fetch_assoc()) {
                $user_id = $row['user_id'];
                $data[$user_id][] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function study_time_report($col = '', $request = '') {
        $data = [];
        $sql = "SELECT t1.studies_id, t1.patient_name, t1.expected_time, t1.analyst_hours, t1.actual_tat, t1.time_difference, t1.created_at, t4.user_name, t5.user_name AS assignee_name, t6.status FROM studies t1";
        $sql .= " JOIN client_details t2 ON (t1.client_account_ids = t2.client_account_id)"
                . " JOIN clients t3 ON (t2.client_ids = t3.client_id)"
                . " JOIN users t4 ON (t2.user_ids = t4.user_id)"
                . " JOIN users t5 ON (t1.analyst_id = t5.user_id)"
                . " JOIN analysis_status t6 ON (t1.status_ids = t6.status_id)";

        if (!empty($request["is_day"])) {
            $sql .= " AND TIMESTAMPDIFF(DAY,t1.created_at,NOW()) < '" . $request["is_day"] . "'";
        }

        if (!empty($request['is_assignee'])) {
            $sql .= " AND t1.analyst_id = '" . $request['is_assignee'] . "'";
        }

        if (!empty($request['is_customer'])) {
            $sql .= " AND t4.user_id = '" . $request['is_customer'] . "'";
        }

        if (!empty($request['is_status'])) {
            $sql .= " AND t1.status_ids = '" . $request['is_status'] . "'";
        }

        if (!empty($request["is_time_mgmt"]) && $request["is_time_mgmt"] == "TimeNotAdded") {
            $sql .= " AND t1.analyst_hours <= 0 ";
        }

        $search = trim($request['search']['value']);
        if (!empty($search)) {
            $sql .= " AND (t4.user_name Like '%" . $search . "%' ";
            $sql .= " OR t1.patient_name Like '%" . $search . "%' ";
            $sql .= " OR t1.analyst_hours Like '%" . $search . "%' ";
            $sql .= " OR t1.expected_time Like '%" . $search . "%' ";
            $sql .= " OR t1.time_difference Like '%" . $search . "%' ";
            $sql .= " OR t5.user_name Like '%" . $search . "%' ";
            $sql .= " OR t6.status Like '%" . $search . "%' ";

            if ($this->isValidDate($search)) {
                $date = DateTime::createFromFormat('m-d-Y', trim($search));
                $c_date = $date->format('Y-m-d');
                $sql .= " OR date(t1.created_at) = '$c_date' )";
            } else {
                $sql .= " )";
            }
        }

        $query = $this->mysqli->query($sql);
        $totalData = $query->num_rows;

        if (!empty($request['length'])) {
            if (!empty($request['is_sorting'])) {
                $order = ($request['is_sorting'] == 1) ? 'ASC' : 'DESC';
                $sql .= " ORDER BY t1.time_difference " . $order;
            } else {
                $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];
            }
            $sql .= "  LIMIT " . $request['start'] . "  ," . $request['length'];

            $query = $this->mysqli->query($sql);
        }

        if (!empty($query->num_rows)) {
            while ($row = $query->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return [$totalData, $data];
    }

    public function users_excel_report() {
        $data = [];
        $sql = "SELECT t1.user_id, t1.user_name, t1.email, t1.created_at, t1.is_active, t1.user_type_ids, t2.user_type FROM users t1 JOIN user_type t2 ON (t1.user_type_ids = t2.user_type_id) WHERE t1.is_deleted != '1' AND t2.is_deleted != '1'  order by t1.user_id desc";
        $query = $this->mysqli->query($sql);
        $totalData = $query->num_rows;
        if (!empty($totalData)) {
            while ($row = $query->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return [$totalData, $data];
    }

    public function get_customer_excel_data() {
        $data = array();
        $sql_query = "SELECT user_name, email, created_at, is_active FROM users WHERE is_deleted != '1' AND user_type_ids = '5' order by user_id desc";
        $query = $this->mysqli->query($sql_query);
        $totalData = $query->num_rows;
        if (!empty($totalData)) {
            while ($row = $query->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return [$totalData, $data];
    }

    public function get_excel_analysis_data() {
        $data = array();
        $sql_query = "SELECT  
    analyses.analysis_name, 
    analyses.analysis_invoicing_description, 
    analyses_category.category_name, 
    analyses.analysis_number,
    analyses.analysis_price, 
    analyses.time_to_analyze, 
    CASE 
        WHEN analyses.is_active = '1' THEN 'ACTIVE' 
        ELSE 'INACTIVE' 
    END AS is_active, 
    analyses.created_at 
FROM analyses 
INNER JOIN analyses_category ON (analyses.category_ids = analyses_category.category_id) WHERE analyses.is_deleted = '0' AND analyses_category.is_deleted = '0' ORDER BY analyses.analysis_name ASC";
        $query = $this->mysqli->query($sql_query);
        $totalData = $query->num_rows;
        if (!empty($totalData)) {
            while ($row = $query->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return [$totalData, $data];
    }
}
