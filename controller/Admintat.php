<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Admintat extends Controller
{

    public $Logindb;
    public $Admindb;
    public $user;
    // public $Report;
    // public $dbmodel;
    public $Tatdb;

    function __construct()
    {
        $this->Logindb = $this->model('logindb');
        $this->Admindb = $this->model('admindb');
        $this->Tatdb = $this->model('tatdb');
        // $this->Report = $this->model('report');
        // $this->dbmodel = $this->model('dashboardmodel'); //RC 

        if (isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 1) {
            $userdata = $_SESSION['user'];
            // $this->check_force_pasword_reset($userdata);
            $this->user = $this->Admindb->user_obj($_SESSION['user']->email);
        } else {
            $this->add_alert('danger', 'Access forbidden');
            $this->redirect('');
        }
    }

    public function index()
    {
        // $data = [];
        // $data['user'] = $this->user;
        // $data['page_title'] = 'Turnaround Time';
        // $this->admin_sidebar_v2($data);
        // $this->view('v2/admin/turnaround_time/list', $data);
    }

    public function turnaround_time()
    {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);

        if (isset($_GET['page'])) {
            $page_now = $_GET['page'];
        } else {
            $page_now = 1;
        }

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Tatdb->turnaround_time_by_id($id);
            $data['page_title'] = 'Edit Turnaround Time (TAT)';
            $this->view('v2/layout/header', $data);
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/turnaround_time/edit', $data);
            $this->view('v2/layout/footer');
        } else {
            $data['page_title'] = 'Global Turn Around Time (TAT)';
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/turnaround_time/list', $data);
        }
    }

    public function addnew_tat()
    {
        $data = [];
        $data['user'] = $this->user;
        $data['page_title'] = 'Add Turnaround Time (TAT)';
        $this->view('v2/layout/header', $data);
        $this->admin_sidebar_v2($data);
        $this->view('v2/admin/turnaround_time/add', $data);
        $this->view('v2/layout/footer');
    }

    public function save_tat()
    {
        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        // $form_data = $_POST;
        $form_data = array();
        $form_data = array(
            'new_tat' => $_POST['new_tat'],
            'tat_unit' => $_POST['tat_unit']
        );
        $success = 0;
        $msg = '';
        // if (!empty($form_data['new_tat'])) {
        if (!empty($form_data)) {
            $form_data['is_active'] = '1';
            $form_data['created_by'] = $_SESSION['user']->user_id;
            $form_data['created_at'] = date("Y-m-d H:i:s");
            $status = $this->Tatdb->turnaround_time_add($form_data);
            if ($status['type'] == 'success') {
                $success = 1;
            }
            if ($status['type'] == 'warning') {
                $success = 11;
            }
            $msg = !empty($status['msg']) ? $status['msg'] : '';
        } else {
            $msg = 'Please enter a TAT.';
        }
        echo json_encode(array("success" => $success, "msg" => $msg));
    }

    public function edit_tat()
    {
        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        $form_data = $_POST;
        $success = 0;
        $msg = '';
        if (!empty($form_data['new_tat']) && !empty($form_data['id'])) {
            // $status = $this->Admindb->analyses_category_update($form_data);
            $form_data['created_by'] = $_SESSION['user']->user_id;
            $status = $this->Tatdb->turnaround_time_update($form_data);
            if ($status['type'] == 'success') {
                $success = 1;
            }
            if ($status['type'] == 'warning') {
                $success = 11;
            }
            $msg = !empty($status['msg']) ? $status['msg'] : '';
        } else {
            $msg = 'Please enter all the required details.';
        }
        echo json_encode(array("success" => $success, "msg" => $msg));
    }

    public function get_turnaround_time_info()
    {
        $con = $this->getConnection();
        $request = $_REQUEST;

        $col = array(
            0 => 'tat',
            1 => 'is_active',
            2 => 'tat_id'
        ); // Column mapping

        //$search_str = trim($request['search']['value']);
        $search_str = mysqli_real_escape_string($con, trim($request['search']['value']));

        // Search query
        $sql = "SELECT tat_id, tat, tat_unit, is_active FROM tat_master WHERE is_deleted != '1'";
        if (!empty($search_str)) {
            //$sql .= " AND (tat_id LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " AND tat LIKE '%" . $search_str . "%'";
            if (strtolower($search_str) == 'active') {
                $sql .= " OR is_active = '1' ";
            } else if (strtolower($search_str) == 'inactive') {
                $sql .= " OR is_active = '0' ";
            }

            //$sql .= " OR tat LIKE '%" . $search_str . "%')";
        }

        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);
        $totalFilter = $totalData;

        // Order and limit
        // $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] .
        //     " LIMIT " . $request['start'] . " ," . $request['length'];

        // $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . " " . $request['order'][0]['dir'];
        // if ($request['length'] != -1) {
        //     $sql .= " LIMIT " . $request['start'] . " ," . $request['length'];
        // }

        //Determine if the user has actually sorted the table, or it's just the default sort
        $default_order_col_index = 0;
        $default_order_dir = 'desc';

        $order_col_index = $request['order'][0]['column'];
        $order_dir = $request['order'][0]['dir'];

        //If it's the default sort, override with created_at DESC
        if ((int)$order_col_index === $default_order_col_index && strtolower($order_dir) === $default_order_dir) {
            $sql .= " ORDER BY created_at DESC";
        } else {
            $sql .= " ORDER BY " . $col[$order_col_index] . " " . $order_dir;
        }



        $query = mysqli_query($con, $sql);
        $data = array();

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            //$subdata[] = $row['tat']; // TAT value
            $subdata[] = $row['tat'] . ' ' . $row['tat_unit'];

            // Status column
            if ($row['is_active'] == 1) {
                $status_a = '<span class="spanstatus badge badge-secondary">Active</span>';
                $s_btn = 'btn-warning';
                $s_icn = '<i class="fas fa-plane-slash"></i> Inactivate';
            } else {
                $status_a = '<span class="spanstatus badge badge-danger">Inactive</span>';
                $s_btn = 'btn-primary';
                $s_icn = '<i class="fas fa-plane"></i> Activate';
            }

            $subdata[] = $status_a;

            // Action buttons
            $block_icon = '<a href="javascript:void(0)" class="btn btn-xs ' . $s_btn . ' status_link change_status ml-md-1 mt-1 mt-md-0" 
                        data-id="' . $row['tat_id'] . '" data-status="' . $row['is_active'] . '">' . $s_icn . '</a>';

            $subdata[] = '<a href="' . SITE_URL . '/tat/turnaround_time?edit=' . $row['tat_id'] . '" 
                      class="btn btn-xs btn-success edit_link"><i class="fas fa-edit"></i> Edit</a> 
                      <a href="javascript:void(0);" class="btn btn-xs btn-danger delete_link" rel="' . $row['tat_id'] . '">
                      <i class="fas fa-trash-alt"></i> Delete</a>' . $block_icon;

            $data[] = $subdata;
        }

        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFilter),
            "data" => $data
        );

        echo json_encode($json_data);
        die;
    }

    public function ajaxChangeTatStatus()
    {
        $id_status = $_REQUEST['id'];
        $new_status = ($_REQUEST['status'] == 1) ? 0 : 1;
        $status = $this->Tatdb->analysesTatStatusUpdate($id_status, $new_status);
        echo json_encode($status);
    }

    public function delete_turnaround_time()
    {
        $form_data = $_POST;
        $type = '';
        $msg = '';
        if (!empty($form_data['ref'])) {
            $id = $form_data['ref'];
            $status = $this->Tatdb->delete('tat_master', $id, 'tat_id');
            $type = !empty($status['type']) ? $status['type'] : '';
            $msg = !empty($status['msg']) ? $status['msg'] : '';
        }
        echo json_encode(array("type" => $type, "msg" => $msg));
    }

    // public function getTurnAroundTimes()
    // {
    //     $search = $_GET['search'] ?? '';
    //     $page = $_GET['page'] ?? 1;
    //     $response = $this->Tatdb->fetchTurnAroundTimes($search, $page);
    //     header('Content-Type: application/json');
    //     echo json_encode($response);
    // }

    public function getTurnAroundTimes()
    {
        $response = $this->Tatdb->fetchTurnAroundTimes();

        // Debugging: Log response
        error_log("Response: " . json_encode($response));

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // public function getTurnAroundTimes()
    // {
    //     // Get database connection
    //     $con = $this->getConnection();
    //     // Check if connection is successful
    //     if (!$con) {
    //         error_log("Database connection failed.");
    //         echo json_encode(["error" => "Database connection failed"]);
    //         exit;
    //     }
    //     // SQL Query
    //     $sql_query = "SELECT tat_id, tat FROM tat_master WHERE is_active = 1 AND is_deleted = 0";
    //     $result = $con->query($sql_query);
    //     if (!$result) {
    //         error_log("SQL Error: " . $con->error);
    //         echo json_encode(["error" => $con->error, "items" => []]);
    //         exit;
    //     }
    //     $items = [];
    //     while ($row = $result->fetch_assoc()) {
    //         $items[] = [
    //             "id" => $row['tat_id'],
    //             "text" => $row['tat']
    //         ];
    //     }
    //     // Return JSON response
    //     header('Content-Type: application/json');
    //     echo json_encode(["items" => $items]);
    //     exit;
    // }
    // public function get_tat_master_info()
    // {
    //     $con = $this->getConnection();
    //     $request = $_REQUEST;
    //     $col = array(
    //         0 => 'tat',
    //         1 => 'is_active',
    //         2 => 'tat_id'
    //     ); // Column mapping
    //     // Search query
    //     $sql = "SELECT tat_id, tat, is_active FROM tat_master WHERE is_deleted != '1'";
    //     if (!empty($request['search']['value'])) {
    //         $sql .= " AND (tat_id LIKE '%" . $request['search']['value'] . "%' ";
    //         $sql .= " OR tat LIKE '%" . $request['search']['value'] . "%')";
    //     }
    //     $query = mysqli_query($con, $sql);
    //     $totalData = mysqli_num_rows($query);
    //     $totalFilter = $totalData;
    //     // Order and limit
    //     $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] .
    //         " LIMIT " . $request['start'] . " ," . $request['length'];
    //     $query = mysqli_query($con, $sql);
    //     $data = array();
    //     while ($row = mysqli_fetch_array($query)) {
    //         $subdata = array();
    //         $subdata[] = $row['tat']; // TAT value
    //         // Status column
    //         if ($row['is_active'] == 1) {
    //             $status_a = '<span class="spanstatus badge badge-primary">Active</span>';
    //             $s_btn = 'btn-warning';
    //             $s_icn = '<i class="fas fa-plane-slash"></i> Inactivate';
    //         } else {
    //             $status_a = '<span class="spanstatus badge badge-danger">Inactive</span>';
    //             $s_btn = 'btn-primary';
    //             $s_icn = '<i class="fas fa-plane"></i> Activate';
    //         }
    //         $subdata[] = $status_a;
    //         // Action buttons
    //         $block_icon = '<a href="javascript:void(0)" class="btn btn-xs ' . $s_btn . ' status_link change_status" 
    //                         data-id="' . $row['tat_id'] . '" data-status="' . $row['is_active'] . '">' . $s_icn . '</a>';
    //         $subdata[] = '<a href="' . SITE_URL . '/admin/tat_master?edit=' . $row['tat_id'] . '" 
    //                       class="btn btn-xs btn-success edit_link"><i class="fas fa-edit"></i> Edit</a> 
    //                       <a href="javascript:void(0);" class="btn btn-xs btn-danger delete_link" rel="' . $row['tat_id'] . '">
    //                       <i class="fas fa-trash-alt"></i> Delete</a>' . $block_icon;
    //         $data[] = $subdata;
    //     }
    //     $json_data = array(
    //         "draw" => intval($request['draw']),
    //         "recordsTotal" => intval($totalData),
    //         "recordsFiltered" => intval($totalFilter),
    //         "data" => $data
    //     );
    //     echo json_encode($json_data);
    //     die;
    // }

    // public function analyses_rates()
    // {
    //     $data['user'] = $this->user;
    //     $this->admin_sidebar_v2($data);
    //     $this->view('v2/admin/customer/analyses_rates', $data);
    // }

    public function export_turnaround_time_info_excel()
    {
        if (!isset($_SESSION['user']->user_id)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Unauthorized access"]);
            exit;
        }

        $con = $this->getConnection();
        $search_str = $_POST['searchValue'] ?? '';
        $search_str = mysqli_real_escape_string($con, trim($search_str));

        $sql = "SELECT tat_id, tat, tat_unit, is_active FROM tat_master WHERE is_deleted != '1'";

        if (!empty($search_str)) {
            $sql .= " AND tat LIKE '%" . $search_str . "%'";
            if (strtolower($search_str) == 'active') {
                $sql .= " OR is_active = '1' ";
            } elseif (strtolower($search_str) == 'inactive') {
                $sql .= " OR is_active = '0' ";
            }
        }

        $sql .= " ORDER BY tat ASC";

        $query = mysqli_query($con, $sql);
        if (!$query) {
            echo json_encode(["error" => "Database error: " . mysqli_error($con)]);
            exit;
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('Global Turn Around Time Info')
            ->setDescription('Exported TAT data using PhpSpreadsheet.');

        $headers = ["SL No", "Turnaround Time", "Status"];
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $mergeRange = "A2:{$lastColumn}2";

        $sheet->mergeCells($mergeRange);
        $sheet->setCellValue('A2', 'Global Turn Around Time Information');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 17],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF87CEEB'],
            ],
        ]);

        $sheet->fromArray([$headers], NULL, 'A3');
        $sheet->getStyle("A3:{$lastColumn}3")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFADD8E6'],
            ],
        ]);

        $sheet->getColumnDimension('A')->setWidth(30);

        foreach (range('B', $lastColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $rowNumber = 4;
        $sl = 1;

        while ($row = mysqli_fetch_assoc($query)) {
            $status = ($row['is_active'] == 1) ? 'Active' : 'Inactive';
            $dtat = $row['tat'] . ' ' . $row['tat_unit'];
            // $dataRow = [$sl++, $row['tat'], $status];
            //$subdata[] = $row['tat'] . ' ' . $row['tat_unit'];
            $dataRow = [$sl++, $dtat, $status];
            $sheet->fromArray([$dataRow], NULL, 'A' . $rowNumber++);
        }

        $dataStartRow = 4;
        $dataEndRow = $rowNumber - 1;

        // Align columns
        $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("B{$dataStartRow}:C{$dataEndRow}")
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filePath = '/tmp/tat_info.xlsx';
        $writer->save($filePath);

        if (!file_exists($filePath)) {
            die("Error: File not created.");
        }

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="tat_info.xlsx"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        unlink($filePath);
        exit;
    }


    public function analyses_rates()
    {
        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $data = [];
            $data['user'] = $this->user;
            $data['page_title'] = 'Analyses Rates';
            $data['id'] = $_GET['edit'];
            $this->view('v2/layout/header', $data);
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/customer/analyses_rates', $data);
            $this->view('v2/layout/footer');
        }
    }

    // public function list_analyses()
    // {
    //     $con = $this->getConnection();
    //     $request = $_REQUEST ?? [];
    //     $col = [
    //         0 => '',
    //         1 => 'analysis_name',
    //         2 => 'analysis_invoicing_description',
    //         3 => 'analysis_price',
    //         4 => 'analysis_number',
    //         5 => '',
    //     ];
    //     $selopt = $_POST['selection'] ?? '';
    //     // $cid = $_POST['client_id'] ?? '';
    //     $user_id = $_POST['user_id'] ?? '';
    //     $cid = '';

    //     $find_sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
    //     $find_stmt = $con->prepare($find_sql);

    //     if (!$find_stmt) {
    //         die(json_encode(['status' => 'error', 'message' => 'Prepare failed for SELECT', 'error' => $con->error]));
    //     }

    //     $find_stmt->bind_param("s", $user_id);

    //     if ($find_stmt->execute()) {
    //         $find_stmt->bind_result($cid);
    //         if ($find_stmt->fetch()) {
    //             $find_stmt->close();

    //             if ($selopt == "all") {
    //                 $sql = "SELECT
    //         a.analysis_id,
    //         COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
    //         acpd.analysis_client_price_id AS analysis_client_price_id,
    //         COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
    //         COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
    //         COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
    //         COALESCE(acpd.is_active, a.is_active) AS is_active
    //         FROM analyses a
    //         LEFT JOIN analyses_client_price_details acpd 
    //         ON acpd.analysis_id = a.analysis_id 
    //         AND acpd.client_account_ids = '$cid'";
    //                 $query = mysqli_query($con, $sql);
    //                 $data = [];
    //                 $count = 1;
    //                 while ($row = mysqli_fetch_array($query)) {
    //                     $subdata = [];
    //                     $subdata[] = $count++;
    //                     $subdata[] = $row['analysis_id'];
    //                     $subdata[] = $row['analysis_name'];
    //                     $subdata[] = $row['analysis_invoicing_description'];
    //                     $subdata[] = $row['analysis_price'];
    //                     $subdata[] = $row['analysis_number'];
    //                     $subdata[] = $row['analysis_client_price_id'];
    //                     $subdata[] = $row['is_active'];
    //                     //$subdata[] = '<button class="btn btn-info btn-sm edit-btn" data-id="' . $row['analysis_id'] . '">Edit</button> <button class="btn btn-primary btn-sm" data-id="' . $row['analysis_id'] . '"><i class="fas fa-plane"></i>Activate</button> <button class="btn btn-warning btn-sm" data-id="' . $row['analysis_id'] . '"><i class="fas fa-plane-slash"></i>Inactivate</button>';
    //                     $data[] = $subdata;
    //                 }
    //                 echo json_encode(['data' => $data]);
    //                 exit;
    //             }
    //             if ($selopt == "active") {
    //                 $sql = "SELECT 
    //         a.analysis_id,
    //         COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
    //         COALESCE(acpd.analysis_client_price_id) AS analysis_client_price_id,
    //         COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
    //         COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
    //         COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
    //         COALESCE(acpd.is_active, a.is_active) AS is_active
    //         FROM analyses a
    //         LEFT JOIN analyses_client_price_details acpd 
    //         ON acpd.analysis_id = a.analysis_id 
    //         AND acpd.client_account_ids = '$cid'
    //         WHERE 
    //         (
    //             acpd.analysis_id IS NOT NULL AND acpd.is_active = '1'
    //         )
    //         OR
    //         (
    //             acpd.analysis_id IS NULL AND a.is_active = '1'
    //         )
    //         ";


    //                 $query = mysqli_query($con, $sql);
    //                 $data = [];
    //                 $count = 1;
    //                 while ($row = mysqli_fetch_array($query)) {
    //                     $subdata = [];
    //                     $subdata[] = $count++;
    //                     $subdata[] = $row['analysis_id'];
    //                     $subdata[] = $row['analysis_name'];
    //                     $subdata[] = $row['analysis_invoicing_description'];
    //                     $subdata[] = $row['analysis_price'];
    //                     $subdata[] = $row['analysis_number'];
    //                     $subdata[] = $row['analysis_client_price_id'];
    //                     $subdata[] = $row['is_active'];
    //                     //$subdata[] = '<button class="btn btn-info btn-sm edit-btn" data-id="' . $row['analysis_id'] . '">Edit</button>';
    //                     $data[] = $subdata;
    //                 }
    //                 echo json_encode(['data' => $data]);
    //                 exit;
    //             }
    //             if ($selopt == "inactive") {
    //                 $sql = "SELECT 
    //         a.analysis_id,
    //         COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
    //         COALESCE(acpd.analysis_client_price_id) AS analysis_client_price_id,
    //         COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
    //         COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
    //         COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
    //         COALESCE(acpd.is_active, a.is_active) AS is_active
    //         FROM analyses a
    //         LEFT JOIN analyses_client_price_details acpd 
    //         ON acpd.analysis_id = a.analysis_id 
    //         AND acpd.client_account_ids = '$cid'
    //         WHERE 
    //         (
    //             acpd.analysis_id IS NOT NULL AND acpd.is_active = '0'
    //         )
    //         OR
    //         (
    //             acpd.analysis_id IS NULL AND a.is_active = '0'
    //         )
    //         ";

    //                 $query = mysqli_query($con, $sql);
    //                 $data = [];
    //                 $count = 1;
    //                 while ($row = mysqli_fetch_array($query)) {
    //                     $subdata = [];
    //                     $subdata[] = $count++;
    //                     $subdata[] = $row['analysis_id'];
    //                     $subdata[] = $row['analysis_name'];
    //                     $subdata[] = $row['analysis_invoicing_description'];
    //                     $subdata[] = $row['analysis_price'];
    //                     $subdata[] = $row['analysis_number'];
    //                     $subdata[] = $row['analysis_client_price_id'];
    //                     $subdata[] = $row['is_active'];
    //                     //$subdata[] = '<button class="btn btn-info btn-sm edit-btn" data-id="' . $row['analysis_id'] . '">Edit</button>';
    //                     $data[] = $subdata;
    //                 }
    //                 echo json_encode(['data' => $data]);
    //                 exit;
    //             }
    //         } else {
    //             echo json_encode(['status' => 'error', 'message' => 'No client ID found for the user']);
    //         }
    //     } else {
    //         echo json_encode(['status' => 'error', 'message' => 'Execution failed for SELECT', 'error' => $find_stmt->error]);
    //     }

    //     //$find_stmt->close();
    // }

    public function list_analyses()
    {
        $con = $this->getConnection();
        $request = $_REQUEST ?? [];
        $col = [
            0 => '',
            1 => 'analysis_name',
            2 => 'analysis_invoicing_description',
            3 => 'category_name',
            4 => 'analysis_price',
            5 => 'analysis_number',
            6 => '',
        ];
        $selopt = $_POST['selection'] ?? '';
        $selcat = $_POST['selcat'] ?? '';
        // $cid = $_POST['client_id'] ?? '';
        $user_id = $_POST['user_id'] ?? '';
        $cid = '';

        $find_sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
        $find_stmt = $con->prepare($find_sql);

        if (!$find_stmt) {
            die(json_encode(['status' => 'error', 'message' => 'Prepare failed for SELECT', 'error' => $con->error]));
        }

        $find_stmt->bind_param("s", $user_id);

        if ($find_stmt->execute()) {
            $find_stmt->bind_result($cid);
            if ($find_stmt->fetch()) {
                $find_stmt->close();

                if ($selopt == "all") {
                    $sql = "SELECT
        a.analysis_id,
        COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
        acpd.analysis_client_price_id AS analysis_client_price_id,
        COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
        COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
        COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
        COALESCE(acpd.is_active, a.is_active) AS is_active,
        ac.category_name,
        ac.category_id
    FROM analyses a
    LEFT JOIN analyses_client_price_details acpd
        ON acpd.analysis_id = a.analysis_id
        AND acpd.client_account_ids = '$cid'
    LEFT JOIN analyses_category ac
        ON a.category_ids = ac.category_id
    WHERE 1=1
    AND ac.is_active = '1' AND ac.is_deleted = '0'"; // Only select where ac.is_deleted is '0'

                    if (!empty($_POST['selcat'])) {
                        $selcat = $_POST['selcat'];
                        $sql .= " AND ac.category_id = '$selcat'";
                    }

                    $query = mysqli_query($con, $sql);
                    $data = [];
                    $count = 1;
                    while ($row = mysqli_fetch_array($query)) {
                        $subdata = [];
                        $subdata[] = $count++;
                        $subdata[] = $row['analysis_id'];
                        $subdata[] = $row['analysis_name'];
                        $subdata[] = $row['analysis_invoicing_description'];
                        $subdata[] = $row['category_name'];
                        $subdata[] = $row['analysis_price'];
                        $subdata[] = $row['analysis_number'];
                        $subdata[] = $row['analysis_client_price_id'];
                        $subdata[] = $row['is_active'];
                        $subdata[] = $row['category_id'];
                        //$subdata[] = '<button class="btn btn-info btn-sm edit-btn" data-id="' . $row['analysis_id'] . '">Edit</button> <button class="btn btn-primary btn-sm" data-id="' . $row['analysis_id'] . '"><i class="fas fa-plane"></i>Activate</button> <button class="btn btn-warning btn-sm" data-id="' . $row['analysis_id'] . '"><i class="fas fa-plane-slash"></i>Inactivate</button>';
                        $data[] = $subdata;
                    }
                    echo json_encode(['data' => $data]);
                    exit;
                }

                //                 if ($selopt == "all") {
                //                     //         $sql = "SELECT
                //                     // a.analysis_id,
                //                     // COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
                //                     // acpd.analysis_client_price_id AS analysis_client_price_id,
                //                     // COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
                //                     // COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
                //                     // COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
                //                     // COALESCE(acpd.is_active, a.is_active) AS is_active
                //                     // FROM analyses a
                //                     // LEFT JOIN analyses_client_price_details acpd 
                //                     // ON acpd.analysis_id = a.analysis_id 
                //                     // AND acpd.client_account_ids = '$cid'";

                //                     //                     $sql = "SELECT
                //                     //     a.analysis_id,
                //                     //     COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
                //                     //     acpd.analysis_client_price_id AS analysis_client_price_id,
                //                     //     COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
                //                     //     COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
                //                     //     COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
                //                     //     COALESCE(acpd.is_active, a.is_active) AS is_active,
                //                     //     ac.category_name,
                //                     //     ac.category_id
                //                     // FROM analyses a
                //                     // LEFT JOIN analyses_client_price_details acpd 
                //                     //     ON acpd.analysis_id = a.analysis_id 
                //                     //     AND acpd.client_account_ids = '$cid'
                //                     // LEFT JOIN analyses_category ac 
                //                     //     ON a.category_ids = ac.category_id";

                //                     $sql = "SELECT
                //     a.analysis_id,
                //     COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
                //     acpd.analysis_client_price_id AS analysis_client_price_id,
                //     COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
                //     COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
                //     COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
                //     COALESCE(acpd.is_active, a.is_active) AS is_active,
                //     ac.category_name,
                //     ac.category_id
                // FROM analyses a
                // LEFT JOIN analyses_client_price_details acpd 
                //     ON acpd.analysis_id = a.analysis_id 
                //     AND acpd.client_account_ids = '$cid'
                // LEFT JOIN analyses_category ac 
                //     ON a.category_ids = ac.category_id
                // WHERE 1=1";

                //                     if (!empty($_POST['selcat'])) {
                //                         $selcat = $_POST['selcat'];
                //                         $sql .= " AND ac.category_id = '$selcat'";
                //                     }


                //                     $query = mysqli_query($con, $sql);
                //                     $data = [];
                //                     $count = 1;
                //                     while ($row = mysqli_fetch_array($query)) {
                //                         $subdata = [];
                //                         $subdata[] = $count++;
                //                         $subdata[] = $row['analysis_id'];
                //                         $subdata[] = $row['analysis_name'];
                //                         $subdata[] = $row['analysis_invoicing_description'];
                //                         $subdata[] = $row['category_name'];
                //                         $subdata[] = $row['analysis_price'];
                //                         $subdata[] = $row['analysis_number'];
                //                         $subdata[] = $row['analysis_client_price_id'];
                //                         $subdata[] = $row['is_active'];
                //                         $subdata[] = $row['category_id'];
                //                         //$subdata[] = '<button class="btn btn-info btn-sm edit-btn" data-id="' . $row['analysis_id'] . '">Edit</button> <button class="btn btn-primary btn-sm" data-id="' . $row['analysis_id'] . '"><i class="fas fa-plane"></i>Activate</button> <button class="btn btn-warning btn-sm" data-id="' . $row['analysis_id'] . '"><i class="fas fa-plane-slash"></i>Inactivate</button>';
                //                         $data[] = $subdata;
                //                     }
                //                     echo json_encode(['data' => $data]);
                //                     exit;
                //                 }



                if ($selopt == "active") {
                    $sql = "SELECT
        a.analysis_id,
        COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
        acpd.analysis_client_price_id AS analysis_client_price_id,
        COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
        COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
        COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
        COALESCE(acpd.is_active, a.is_active) AS is_active,
        ac.category_name,
        ac.category_id
    FROM analyses a
    LEFT JOIN analyses_client_price_details acpd
        ON acpd.analysis_id = a.analysis_id
        AND acpd.client_account_ids = '$cid'
    LEFT JOIN analyses_category ac
        ON a.category_ids = ac.category_id
    WHERE (
            (acpd.analysis_id IS NOT NULL AND acpd.is_active = '1')
            OR
            (acpd.analysis_id IS NULL AND a.is_active = '1')
        )
    AND ac.is_active = '1' AND ac.is_deleted = '0'"; // Only select where ac.is_deleted is '0'

                    // Add category filter if selected
                    if (!empty($_POST['selcat'])) {
                        $selcat = $_POST['selcat'];
                        $sql .= " AND ac.category_id = '$selcat'";
                    }

                    $query = mysqli_query($con, $sql);
                    $data = [];
                    $count = 1;
                    while ($row = mysqli_fetch_array($query)) {
                        $subdata = [];
                        $subdata[] = $count++;
                        $subdata[] = $row['analysis_id'];
                        $subdata[] = $row['analysis_name'];
                        $subdata[] = $row['analysis_invoicing_description'];
                        $subdata[] = $row['category_name'];
                        $subdata[] = $row['analysis_price'];
                        $subdata[] = $row['analysis_number'];
                        $subdata[] = $row['analysis_client_price_id'];
                        $subdata[] = $row['is_active'];
                        $subdata[] = $row['category_id'];
                        //$subdata[] = '<button class="btn btn-info btn-sm edit-btn" data-id="' . $row['analysis_id'] . '">Edit</button>';
                        $data[] = $subdata;
                    }
                    echo json_encode(['data' => $data]);
                    exit;
                }
                //                 if ($selopt == "active") {
                //                     //                     $sql = "SELECT
                //                     //     a.analysis_id,
                //                     //     COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
                //                     //     acpd.analysis_client_price_id AS analysis_client_price_id,
                //                     //     COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
                //                     //     COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
                //                     //     COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
                //                     //     COALESCE(acpd.is_active, a.is_active) AS is_active,
                //                     //     ac.category_name,
                //                     //     ac.category_id
                //                     // FROM analyses a
                //                     // LEFT JOIN analyses_client_price_details acpd 
                //                     //     ON acpd.analysis_id = a.analysis_id 
                //                     //     AND acpd.client_account_ids = '$cid'
                //                     // LEFT JOIN analyses_category ac 
                //                     //     ON a.category_ids = ac.category_id
                //                     //             WHERE 
                //                     //             (
                //                     //                 acpd.analysis_id IS NOT NULL AND acpd.is_active = '1'
                //                     //             )
                //                     //             OR
                //                     //             (
                //                     //                 acpd.analysis_id IS NULL AND a.is_active = '1'
                //                     //             )
                //                     //             ";

                //                     $sql = "SELECT
                //     a.analysis_id,
                //     COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
                //     acpd.analysis_client_price_id AS analysis_client_price_id,
                //     COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
                //     COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
                //     COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
                //     COALESCE(acpd.is_active, a.is_active) AS is_active,
                //     ac.category_name,
                //     ac.category_id
                // FROM analyses a
                // LEFT JOIN analyses_client_price_details acpd 
                //     ON acpd.analysis_id = a.analysis_id 
                //     AND acpd.client_account_ids = '$cid'
                // LEFT JOIN analyses_category ac 
                //     ON a.category_ids = ac.category_id
                // WHERE (
                //         (acpd.analysis_id IS NOT NULL AND acpd.is_active = '1')
                //         OR
                //         (acpd.analysis_id IS NULL AND a.is_active = '1')
                //     )";

                //                     // Add category filter if selected
                //                     if (!empty($_POST['selcat'])) {
                //                         $selcat = $_POST['selcat'];
                //                         $sql .= " AND ac.category_id = '$selcat'";
                //                     }



                //                     $query = mysqli_query($con, $sql);
                //                     $data = [];
                //                     $count = 1;
                //                     while ($row = mysqli_fetch_array($query)) {
                //                         $subdata = [];
                //                         $subdata[] = $count++;
                //                         $subdata[] = $row['analysis_id'];
                //                         $subdata[] = $row['analysis_name'];
                //                         $subdata[] = $row['analysis_invoicing_description'];
                //                         $subdata[] = $row['category_name'];
                //                         $subdata[] = $row['analysis_price'];
                //                         $subdata[] = $row['analysis_number'];
                //                         $subdata[] = $row['analysis_client_price_id'];
                //                         $subdata[] = $row['is_active'];
                //                         $subdata[] = $row['category_id'];
                //                         //$subdata[] = '<button class="btn btn-info btn-sm edit-btn" data-id="' . $row['analysis_id'] . '">Edit</button>';
                //                         $data[] = $subdata;
                //                     }
                //                     echo json_encode(['data' => $data]);
                //                     exit;
                //                 }




                if ($selopt == "inactive") {
                    $sql = "SELECT
        a.analysis_id,
        COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
        acpd.analysis_client_price_id AS analysis_client_price_id,
        COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
        COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
        COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
        COALESCE(acpd.is_active, a.is_active) AS is_active,
        ac.category_name,
        ac.category_id
    FROM analyses a
    LEFT JOIN analyses_client_price_details acpd
        ON acpd.analysis_id = a.analysis_id
        AND acpd.client_account_ids = '$cid'
    LEFT JOIN analyses_category ac
        ON a.category_ids = ac.category_id
    WHERE (
            (acpd.analysis_id IS NOT NULL AND acpd.is_active = '0')
            OR
            (acpd.analysis_id IS NULL AND a.is_active = '0')
        )
    AND ac.is_active = '1' AND ac.is_deleted = '0'"; // Only select where ac.is_deleted is '0'

                    // Add category filter if selected
                    if (!empty($_POST['selcat'])) {
                        $selcat = $_POST['selcat'];
                        $sql .= " AND ac.category_id = '$selcat'";
                    }

                    $query = mysqli_query($con, $sql);
                    $data = [];
                    $count = 1;
                    while ($row = mysqli_fetch_array($query)) {
                        $subdata = [];
                        $subdata[] = $count++;
                        $subdata[] = $row['analysis_id'];
                        $subdata[] = $row['analysis_name'];
                        $subdata[] = $row['analysis_invoicing_description'];
                        $subdata[] = $row['category_name'];
                        $subdata[] = $row['analysis_price'];
                        $subdata[] = $row['analysis_number'];
                        $subdata[] = $row['analysis_client_price_id'];
                        $subdata[] = $row['is_active'];
                        $subdata[] = $row['category_id'];
                        //$subdata[] = '<button class="btn btn-info btn-sm edit-btn" data-id="' . $row['analysis_id'] . '">Edit</button>';
                        $data[] = $subdata;
                    }
                    echo json_encode(['data' => $data]);
                    exit;
                }
                //                 if ($selopt == "inactive") {
                //                     //                     $sql = "SELECT
                //                     //     a.analysis_id,
                //                     //     COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
                //                     //     acpd.analysis_client_price_id AS analysis_client_price_id,
                //                     //     COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
                //                     //     COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
                //                     //     COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
                //                     //     COALESCE(acpd.is_active, a.is_active) AS is_active,
                //                     //     ac.category_name,
                //                     //     ac.category_id
                //                     // FROM analyses a
                //                     // LEFT JOIN analyses_client_price_details acpd 
                //                     //     ON acpd.analysis_id = a.analysis_id 
                //                     //     AND acpd.client_account_ids = '$cid'
                //                     // LEFT JOIN analyses_category ac 
                //                     //     ON a.category_ids = ac.category_id
                //                     //             WHERE 
                //                     //             (
                //                     //                 acpd.analysis_id IS NOT NULL AND acpd.is_active = '0'
                //                     //             )
                //                     //             OR
                //                     //             (
                //                     //                 acpd.analysis_id IS NULL AND a.is_active = '0'
                //                     //             )
                //                     //             ";

                //                     $sql = "SELECT
                //     a.analysis_id,
                //     COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
                //     acpd.analysis_client_price_id AS analysis_client_price_id,
                //     COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
                //     COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
                //     COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
                //     COALESCE(acpd.is_active, a.is_active) AS is_active,
                //     ac.category_name,
                //     ac.category_id
                // FROM analyses a
                // LEFT JOIN analyses_client_price_details acpd 
                //     ON acpd.analysis_id = a.analysis_id 
                //     AND acpd.client_account_ids = '$cid'
                // LEFT JOIN analyses_category ac 
                //     ON a.category_ids = ac.category_id
                // WHERE (
                //         (acpd.analysis_id IS NOT NULL AND acpd.is_active = '0')
                //         OR
                //         (acpd.analysis_id IS NULL AND a.is_active = '0')
                //     )";

                //                     // Add category filter if selected
                //                     if (!empty($_POST['selcat'])) {
                //                         $selcat = $_POST['selcat'];
                //                         $sql .= " AND ac.category_id = '$selcat'";
                //                     }



                //                     $query = mysqli_query($con, $sql);
                //                     $data = [];
                //                     $count = 1;
                //                     while ($row = mysqli_fetch_array($query)) {
                //                         $subdata = [];
                //                         $subdata[] = $count++;
                //                         $subdata[] = $row['analysis_id'];
                //                         $subdata[] = $row['analysis_name'];
                //                         $subdata[] = $row['analysis_invoicing_description'];
                //                         $subdata[] = $row['category_name'];
                //                         $subdata[] = $row['analysis_price'];
                //                         $subdata[] = $row['analysis_number'];
                //                         $subdata[] = $row['analysis_client_price_id'];
                //                         $subdata[] = $row['is_active'];
                //                         $subdata[] = $row['category_id'];
                //                         //$subdata[] = '<button class="btn btn-info btn-sm edit-btn" data-id="' . $row['analysis_id'] . '">Edit</button>';
                //                         $data[] = $subdata;
                //                     }
                //                     echo json_encode(['data' => $data]);
                //                     exit;
                //                 }

            } else {
                echo json_encode(['status' => 'error', 'message' => 'No client ID found for the user']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Execution failed for SELECT', 'error' => $find_stmt->error]);
        }

        //$find_stmt->close();
    }

    public function update_analysis_price()
    {
        $conn = $this->getConnection();

        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        // Sanitize POST data
        // $analysis_id    = trim($_POST['analysis_id']);
        // $client_id      = trim($_POST['client_id']);
        // $analysis_name  = trim($_POST['analysis_name']);
        // $description    = trim($_POST['description']);
        // $price          = floatval($_POST['price']);

        $analysis_id    = $_POST['analysis_id'] ?? '';
        $client_price_id = $_POST['data_price_id'] ?? '';
        $user_id      = $_POST['user_id'] ?? '';
        $analysis_name  = $_POST['analysis_name'] ?? '';
        $description    = $_POST['description'] ?? '';
        $price          = $_POST['price'] ?? '';
        $anumber          = $_POST['anumber'] ?? '';

        // if (
        //     empty($analysis_id) || empty($client_price_id) || empty($user_id) ||
        //     empty($analysis_name) || empty($description) || empty($price) || empty($anumber)
        // ) {
        //     echo 0; 
        //     return;
        // }

        if (
            !isset($analysis_id, $user_id, $analysis_name, $description, $price, $anumber) ||
            $analysis_id === '' ||
            $user_id === '' ||
            $analysis_name === '' ||
            $description === '' ||
            $price === '' ||
            $anumber === ''
        ) {
            echo 0; // Return 0 to signal "missing required fields"
            exit;
        }

        // if (!preg_match('/^\d{4}$/', $anumber)) {
        //     // Invalid: not a 4-digit whole number
        //     echo json_encode(['status' => 'error', 'message' => 'Analysis Number must be a 4-digit whole number.']);
        //     exit;
        // }


        if (empty($client_price_id)) {
            // $sql = "INSERT INTO analyses_client_price_details(client_account_ids, analysis_id, analysis_name, analysis_invoicing_description, analysis_client_price,) 
            // VALUES('$client_id', '$analysis_id', '$analysis_name', '$description', '$price')";
            // $result = $this->$conn->query($sql);
            // if ($result === TRUE) {
            //     echo json_encode(['status' => 'success', 'message' => 'Inserted successfully']);
            // } else {
            //     echo json_encode(['status' => 'error', 'message' => 'Insertion failed']);
            // }

            // $client_id = '';
            // $find_sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
            // $find_stmt = $conn->prepare($find_sql);
            // $find_stmt->bind_param("s", $user_id);

            // if ($find_stmt->execute()) {
            //     $find_stmt->bind_result($client_id);
            //     $find_stmt->fetch();
            //     // Now $client_id contains the result

            //     $insert_sql = "INSERT INTO analyses_client_price_details 
            //        (client_account_ids, analysis_id, analysis_name, analysis_invoicing_description, analysis_client_price)
            //    VALUES (?, ?, ?, ?, ?)";

            //     $stmt = $conn->prepare($insert_sql);
            //     $stmt->bind_param("ssssd", $client_id, $analysis_id, $analysis_name, $description, $price);

            //     if ($stmt->execute()) {
            //         echo json_encode(['status' => 'success', 'message' => 'Inserted successfully']);
            //     } else {
            //         echo json_encode(['status' => 'error', 'message' => 'Insertion failed', 'error' => $stmt->error]);
            //     }
            // }

            $client_id = '';
            $find_sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
            $find_stmt = $conn->prepare($find_sql);

            if (!$find_stmt) {
                die(json_encode(['status' => 'error', 'message' => 'Prepare failed for SELECT', 'error' => $conn->error]));
            }

            $find_stmt->bind_param("s", $user_id);

            if ($find_stmt->execute()) {
                $find_stmt->bind_result($client_id);
                if ($find_stmt->fetch()) {
                    $find_stmt->close();

                    $insert_sql = "INSERT INTO analyses_client_price_details 
            (client_account_ids, analysis_id, analysis_name, analysis_invoicing_description, analysis_client_price, analysis_code)
            VALUES (?, ?, ?, ?, ?, ?)";

                    $stmt = $conn->prepare($insert_sql);
                    if (!$stmt) {
                        die(json_encode(['status' => 'error', 'message' => 'Prepare failed for INSERT', 'error' => $conn->error]));
                    }

                    $stmt->bind_param("ssssds", $client_id, $analysis_id, $analysis_name, $description, $price, $anumber);

                    if ($stmt->execute()) {
                        //echo json_encode(['status' => 'success', 'message' => 'Inserted successfully']);
                        echo json_encode(['status' => 'success', 'message' => 'Inserted successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Insertion failed', 'error' => $stmt->error]);
                    }

                    $stmt->close();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No client ID found for the user']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Execution failed for SELECT', 'error' => $find_stmt->error]);
            }

            //$find_stmt->close();



            // $insert_sql = "INSERT INTO analyses_client_price_details 
            //        (client_account_ids, analysis_id, analysis_name, analysis_invoicing_description, analysis_client_price)
            //    VALUES (?, ?, ?, ?, ?)";

            // $stmt = $conn->prepare($insert_sql);
            // $stmt->bind_param("ssssd", $client_id, $analysis_id, $analysis_name, $description, $price);

            // if ($stmt->execute()) {
            //     echo json_encode(['status' => 'success', 'message' => 'Inserted successfully']);
            // } else {
            //     echo json_encode(['status' => 'error', 'message' => 'Insertion failed', 'error' => $stmt->error]);
            // }
        } else {

            $client_id = '';
            $find_sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
            $find_stmt = $conn->prepare($find_sql);

            if (!$find_stmt) {
                die(json_encode(['status' => 'error', 'message' => 'Prepare failed for SELECT', 'error' => $conn->error]));
            }

            $find_stmt->bind_param("s", $user_id);

            if ($find_stmt->execute()) {
                $find_stmt->bind_result($client_id);
                if ($find_stmt->fetch()) {
                    $find_stmt->close();

                    // Update query directly using analysis_id
                    $update_sql = "UPDATE analyses_client_price_details 
                   SET analysis_name = ?, 
                       analysis_invoicing_description = ?, 
                       analysis_client_price = ?,
                       analysis_code = ?
                   WHERE analysis_client_price_id = ? AND analysis_id = ? AND client_account_ids = ?";

                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param("ssdssss", $analysis_name, $description, $price, $anumber, $client_price_id, $analysis_id, $client_id);

                    if ($stmt->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Updated successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Update failed']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No client ID found for the user']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Execution failed for SELECT', 'error' => $find_stmt->error]);
            }

            //$find_stmt->close();
        }
    }

    public function activate_analysis()
    {
        $conn = $this->getConnection();

        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        $analysis_id = $_POST['analysis_id'] ?? '';
        $client_price_id = $_POST['data_price_id'] ?? '';
        $user_id = $_POST['user_id'] ?? '';
        $analysis_name = $_POST['analysis_name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $anumber = $_POST['anumber'] ?? '';
        $is_active = '1';
        if (empty($client_price_id)) {
            $client_id = '';
            $find_sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
            $find_stmt = $conn->prepare($find_sql);

            if (!$find_stmt) {
                die(json_encode(['status' => 'error', 'message' => 'Prepare failed for SELECT', 'error' => $conn->error]));
            }

            $find_stmt->bind_param("s", $user_id);

            if ($find_stmt->execute()) {
                $find_stmt->bind_result($client_id);
                if ($find_stmt->fetch()) {
                    $find_stmt->close();

                    $insert_sql = "INSERT INTO analyses_client_price_details 
            (client_account_ids, analysis_id, analysis_name, analysis_invoicing_description, analysis_client_price, analysis_code, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $conn->prepare($insert_sql);
                    if (!$stmt) {
                        die(json_encode(['status' => 'error', 'message' => 'Prepare failed for INSERT', 'error' => $conn->error]));
                    }

                    $stmt->bind_param("ssssdss", $client_id, $analysis_id, $analysis_name, $description, $price, $anumber, $is_active);

                    if ($stmt->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Inserted successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Insertion failed', 'error' => $stmt->error]);
                    }

                    $stmt->close();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No client ID found for the user']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Execution failed for SELECT', 'error' => $find_stmt->error]);
            }
        } else {
            $client_id = '';
            $find_sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
            $find_stmt = $conn->prepare($find_sql);

            if (!$find_stmt) {
                die(json_encode(['status' => 'error', 'message' => 'Prepare failed for SELECT', 'error' => $conn->error]));
            }

            $find_stmt->bind_param("s", $user_id);

            if ($find_stmt->execute()) {
                $find_stmt->bind_result($client_id);
                if ($find_stmt->fetch()) {
                    $find_stmt->close();

                    // Update query directly using analysis_id
                    $update_sql = "UPDATE analyses_client_price_details 
                   SET is_active = ?
                   WHERE analysis_client_price_id = ? AND analysis_id = ? AND client_account_ids = ?";

                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param("ssss", $is_active, $client_price_id, $analysis_id, $client_id);

                    if ($stmt->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Status Updated successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Status Update failed']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No client ID found for the user']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Execution failed for SELECT', 'error' => $find_stmt->error]);
            }
        }
    }

    public function inactivate_analysis()
    {
        $conn = $this->getConnection();

        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        $analysis_id = $_POST['analysis_id'] ?? '';
        $client_price_id = $_POST['data_price_id'] ?? '';
        $user_id = $_POST['user_id'] ?? '';
        $analysis_name = $_POST['analysis_name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $anumber = $_POST['anumber'] ?? '';
        $is_active = '0';
        if (empty($client_price_id)) {
            $client_id = '';
            $find_sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
            $find_stmt = $conn->prepare($find_sql);

            if (!$find_stmt) {
                die(json_encode(['status' => 'error', 'message' => 'Prepare failed for SELECT', 'error' => $conn->error]));
            }

            $find_stmt->bind_param("s", $user_id);

            if ($find_stmt->execute()) {
                $find_stmt->bind_result($client_id);
                if ($find_stmt->fetch()) {
                    $find_stmt->close();

                    $insert_sql = "INSERT INTO analyses_client_price_details 
            (client_account_ids, analysis_id, analysis_name, analysis_invoicing_description, analysis_client_price, analysis_code, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $conn->prepare($insert_sql);
                    if (!$stmt) {
                        die(json_encode(['status' => 'error', 'message' => 'Prepare failed for INSERT', 'error' => $conn->error]));
                    }

                    $stmt->bind_param("ssssdss", $client_id, $analysis_id, $analysis_name, $description, $price, $anumber, $is_active);

                    if ($stmt->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Inserted successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Insertion failed', 'error' => $stmt->error]);
                    }

                    $stmt->close();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No client ID found for the user']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Execution failed for SELECT', 'error' => $find_stmt->error]);
            }
        } else {
            $client_id = '';
            $find_sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
            $find_stmt = $conn->prepare($find_sql);

            if (!$find_stmt) {
                die(json_encode(['status' => 'error', 'message' => 'Prepare failed for SELECT', 'error' => $conn->error]));
            }

            $find_stmt->bind_param("s", $user_id);

            if ($find_stmt->execute()) {
                $find_stmt->bind_result($client_id);
                if ($find_stmt->fetch()) {
                    $find_stmt->close();

                    // Update query directly using analysis_id
                    $update_sql = "UPDATE analyses_client_price_details 
                   SET is_active = ?
                   WHERE analysis_client_price_id = ? AND analysis_id = ? AND client_account_ids = ?";

                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param("ssss", $is_active, $client_price_id, $analysis_id, $client_id);

                    if ($stmt->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Status Updated successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Status Update failed']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No client ID found for the user']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Execution failed for SELECT', 'error' => $find_stmt->error]);
            }
        }
    }

    public function get_analyses()
    {
        $conn = $this->getConnection();

        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        $cid = $_POST['client_account_id'];

        $sql = "SELECT 
            a.analysis_id,
            COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
            COALESCE(acpd.analysis_client_price_id) AS analysis_client_price_id,
            COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
            COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
            COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
            COALESCE(acpd.is_active, a.is_active) AS is_active
        FROM analyses a
        LEFT JOIN analyses_client_price_details acpd 
        ON acpd.analysis_id = a.analysis_id 
        AND acpd.client_account_ids = '$cid'
        WHERE 
        (
            acpd.analysis_id IS NOT NULL AND acpd.is_active = '1'
        )
        OR
        (
            acpd.analysis_id IS NULL AND a.is_active = '1'
        )";

        $result = mysqli_query($conn, $sql);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'value' => !empty($row['analysis_client_price_id']) ? $row['analysis_client_price_id'] : $row['analysis_id'],
                'name'  => $row['analysis_name'],
                'table' => !empty($row['analysis_client_price_id']) ? "client" : "parent"
            ];
        }

        echo json_encode($data);
    }

    public function add_to_disclist()
    {
        $conn = $this->getConnection();

        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        $analysis_value = $_POST['analysvalue'] ?? '';
        $client_account_id = $_POST['client_account_id'] ?? '';
        //$client_price_id = $_POST['data_price_id'] ?? '';
        //$user_id = $_POST['user_id'] ?? '';
        $analysis_name = $_POST['analysname'] ?? '';
        $table_id = $_POST['table_id'] ?? '';
        //$price = $_POST['price'] ?? '';
        //$anumber = $_POST['anumber'] ?? '';
        $frm_value = $_POST['frm_value'] ?? '';
        $to_value = $_POST['to_value'] ?? '';
        $percent = $_POST['percent'] ?? '';
        $is_active = '1';

        if (!empty($table_id)) {
            if ($table_id == "parent") {
                $sql1 = "INSERT INTO analyses_client_price_details 
            (client_account_ids, 
            analysis_id, 
            analysis_name, 
            discount_percentage, 
            is_active)
            VALUES (?, ?, ?, ?, ?)";

                $stmt1 = $conn->prepare($sql1);
                if (!$stmt1) {
                    die(json_encode(['status' => 'error', 'message' => 'Prepare failed for first INSERT', 'error' => $conn->error]));
                }

                $stmt1->bind_param("sssss", $client_account_id, $analysis_value, $analysis_name, $percent, $is_active);
                if ($stmt1->execute()) {
                    $analysis_client_price_id = $conn->insert_id;
                    $sql2 = "INSERT INTO monthly_volume_discount
                        (client_account_ids,
                        analysis_client_price_ids,
                        minimum_volume,
                        maximum_volume,
                        discount_price,
                        is_active)
                        VALUES (?, ?, ?, ?, ?, ?)";

                    $stmt2 = $conn->prepare($sql2);
                    if (!$stmt2) {
                        die(json_encode(['status' => 'error', 'message' => 'Prepare failed for second INSERT', 'error' => $conn->error]));
                    }

                    $stmt2->bind_param("ssssss", $client_account_id, $analysis_client_price_id, $frm_value, $to_value, $percent, $is_active);

                    if ($stmt2->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Inserted in both tables successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Insertion failed in second query in parent', 'error' => $stmt2->error]);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Insertion failed in first query', 'error' => $stmt1->error]);
                }
            }

            if ($table_id == "client") {
                echo json_encode(['status' => 'warning', 'message' => 'Discount Already exists']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something Went Wrong!']);
        }

        // if ($table_id == "client") {
        //     $sql1 = "UPDATE analyses_client_price_details 
        //            SET analysis_name = ?,
        //            discount_percentage = ?
        //            WHERE analysis_client_price_id = ? AND client_account_ids = ?";

        //     $stmt1 = $conn->prepare($sql1);
        //     if (!$stmt1) {
        //         die(json_encode(['status' => 'error', 'message' => 'Prepare failed for first UPDATE', 'error' => $conn->error]));
        //     }

        //     $stmt1->bind_param("ssss", $analysis_name, $percent, $analysis_value, $client_account_id);

        //     if ($stmt1->execute()) {
        //         // echo json_encode(['status' => 'success', 'message' => 'First query Updated successfully']);

        //         $sql = "SELECT discount_id FROM monthly_volume_discount WHERE client_account_ids = ? AND analysis_client_price_ids= ?";
        //         $stmt = $conn->prepare($sql);

        //         if (!$stmt) {
        //             die(json_encode(['status' => 'error', 'message' => 'Prepare failed for SELECT in discount', 'error' => $conn->error]));
        //         }

        //         $stmt->bind_param("ss", $client_account_id, $analysis_value);

        //         $stmt->execute();
        //         $result = $stmt->get_result();

        //         if ($result->num_rows === 0) {

        //             $sql2 = "INSERT INTO monthly_volume_discount
        //                 (client_account_ids,
        //                 analysis_client_price_ids,
        //                 minimum_volume,
        //                 maximum_volume,
        //                 discount_price,
        //                 is_active)
        //                 VALUES (?, ?, ?, ?, ?, ?)";

        //             $stmt2 = $conn->prepare($sql2);
        //             if (!$stmt2) {
        //                 die(json_encode(['status' => 'error', 'message' => 'Prepare failed for second INSERT', 'error' => $conn->error]));
        //             }

        //             $stmt2->bind_param("ssssss", $client_account_id, $analysis_value, $frm_value, $to_value, $percent, $is_active);

        //             if ($stmt2->execute()) {
        //                 echo json_encode(['status' => 'success', 'message' => 'Done in both tables successfully']);
        //             } else {
        //                 echo json_encode(['status' => 'error', 'message' => 'Insertion failed in second query in client', 'error' => $stmt2->error]);
        //             }
        //         } else {
        //             $row = $result->fetch_assoc();
        //             $discount_id = $row['discount_id'];
        //             $is_deleted = '0';
        //             $sql3 = "UPDATE monthly_volume_discount SET minimum_volume = ?, maximum_volume = ?, discount_price = ?, is_deleted = ? WHERE discount_id = ? AND client_account_ids = ? AND analysis_client_price_ids = ?";

        //             $stmt3 = $conn->prepare($sql3);
        //             if (!$stmt3) {
        //                 die(json_encode(['status' => 'error', 'message' => 'Prepare failed for third UPDATE', 'error' => $conn->error]));
        //             }

        //             $stmt3->bind_param("sssssss", $frm_value, $to_value, $percent, $is_deleted, $discount_id, $client_account_id, $analysis_value);

        //             if ($stmt3->execute()) {
        //                 echo json_encode(['status' => 'success', 'message' => 'Updated in both tables successfully']);
        //             } else {
        //                 echo json_encode(['status' => 'error', 'message' => 'Updation in discount table failed']);
        //             }
        //         }
        //     } else {
        //         echo json_encode(['status' => 'error', 'message' => 'Updation failed in first query']);
        //     }
        // }
    }

    public function analyses_discount_list()
    {
        $conn = $this->getConnection();

        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        $cid = $_POST['client_account_id'];

        $sql = "SELECT acpd.analysis_client_price_id,
        acpd.analysis_name,
        mvd.discount_id,
        mvd.minimum_volume,
        mvd.maximum_volume,
        mvd.discount_price
        FROM analyses_client_price_details acpd
        LEFT JOIN monthly_volume_discount mvd
        ON mvd.analysis_client_price_ids = acpd.analysis_client_price_id WHERE acpd.client_account_ids = ? AND mvd.client_account_ids= ? AND acpd.is_active = '1' AND mvd.is_deleted = '0'";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cid, $cid);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            $subdata = [];
            $subdata[] = $count++;
            $subdata[] = $row['analysis_name'];
            $subdata[] = $row['minimum_volume'];
            $subdata[] = $row['maximum_volume'];
            $subdata[] = $row['discount_price'];
            $subdata[] = $row['analysis_client_price_id'];
            $subdata[] = $row['discount_id'];
            $data[] = $subdata;
        }

        echo json_encode(['data' => $data]);
        exit;
    }

    public function update_analysis_monthly_discount()
    {
        $conn = $this->getConnection();

        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        $price_id = $_POST['price_id'] ?? '';
        $client_acc_id = $_POST['client_acc_id'] ?? '';
        $disc_id = $_POST['disc_id'] ?? '';
        $analysis_name = $_POST['analysis_name'] ?? '';
        $analysis_from = $_POST['analysis_from'] ?? '';
        $analysis_to = $_POST['analysis_to'] ?? '';
        $analysis_percent = $_POST['analysis_percent'] ?? '';

        if (
            !isset($price_id, $client_acc_id, $disc_id, $analysis_name, $analysis_from, $analysis_to, $analysis_percent) ||
            $price_id === '' ||
            $client_acc_id === '' ||
            $disc_id === '' ||
            $analysis_name === '' ||
            $analysis_from === '' ||
            $analysis_to === '' ||
            $analysis_percent === ''
        ) {
            echo 0; // Return 0 to signal "missing required fields"
            exit;
        }

        $sql1 = "UPDATE analyses_client_price_details 
                   SET analysis_name = ?,
                   discount_percentage = ?
                   WHERE analysis_client_price_id = ? AND client_account_ids = ?";

        $stmt1 = $conn->prepare($sql1);
        if (!$stmt1) {
            die(json_encode(['status' => 'error', 'message' => 'Prepare failed for first UPDATE', 'error' => $conn->error]));
        }

        $stmt1->bind_param("ssss", $analysis_name, $analysis_percent, $price_id, $client_acc_id);

        if ($stmt1->execute()) {
            $sql2 = "UPDATE monthly_volume_discount SET minimum_volume = ?, maximum_volume = ?, discount_price = ? WHERE discount_id = ? AND client_account_ids = ? AND analysis_client_price_ids = ?";

            $stmt2 = $conn->prepare($sql2);
            if (!$stmt2) {
                die(json_encode(['status' => 'error', 'message' => 'Prepare failed for third UPDATE', 'error' => $conn->error]));
            }

            $stmt2->bind_param("ssssss", $analysis_from, $analysis_to, $analysis_percent, $disc_id, $client_acc_id, $price_id);

            if ($stmt2->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Updated in both tables successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Updation in discount table failed']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Updation failed in first query']);
        }
    }

    public function delete_analysis_monthly_discount()
    {
        $conn = $this->getConnection();

        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        $price_id = $_POST['price_id'] ?? '';
        $client_acc_id = $_POST['client_acc_id'] ?? '';
        $disc_id = $_POST['disc_id'] ?? '';

        $sql1 = "UPDATE analyses_client_price_details
        SET discount_percentage = NULL
        WHERE analysis_client_price_id  = ?
        AND client_account_ids = ?;";

        $stmt1 = $conn->prepare($sql1);
        if (!$stmt1) {
            die(json_encode(['status' => 'error', 'message' => 'Prepare failed for first UPDATE', 'error' => $conn->error]));
        }

        $stmt1->bind_param("ss", $price_id, $client_acc_id);

        if ($stmt1->execute()) {
            $sql2 = "UPDATE monthly_volume_discount
            SET is_deleted = '1'
            WHERE discount_id = ?
            AND analysis_client_price_ids = ?";

            $stmt2 = $conn->prepare($sql2);
            if (!$stmt2) {
                die(json_encode(['status' => 'error', 'message' => 'Prepare failed for third UPDATE', 'error' => $conn->error]));
            }

            $stmt2->bind_param("ss", $disc_id, $price_id);

            if ($stmt2->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Updated in both tables successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Updation in discount table failed']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Updation failed in first query']);
        }
    }

    // public function customer_excel_details()
    // {
    //     $conn = $this->getConnection();

    //     // header('Content-Type: application/json');
    //     // echo json_encode($_POST);
    //     // return;

    //     //$cid = $_POST['client_account_id'];

    //     $cid = $_GET['cus'] ?? '';
    //     $usid = $_GET['ud'] ?? '';

    //     //Customer Details
    //     $csql = "SELECT * FROM users t2 LEFT JOIN client_details t1 ON (t2.user_id = t1.user_ids) LEFT JOIN clients t3 ON (t1.client_ids = t3.client_id) where t2.user_id = ? AND t2.is_deleted != '1'";
    //     $cstmt = $conn->prepare($csql);
    //     $cstmt->bind_param("i", $usid);
    //     $cstmt->execute();
    //     $cresult = $cstmt->get_result();


    //     //Setup
    //     $set_sql = "SELECT analysis_name, analysis_invoicing_description, analysis_client_price, analysis_code FROM analyses_client_price_details WHERE client_account_ids = ? AND is_active = '1'";
    //     $set_stmt = $conn->prepare($set_sql);
    //     $set_stmt->bind_param("i", $cid);
    //     $set_stmt->execute();
    //     $set_result = $set_stmt->get_result();

    //     //Monthly Discount
    //     $disc_sql = "SELECT acpd.analysis_client_price_id,
    //     acpd.analysis_name,
    //     mvd.discount_id,
    //     mvd.minimum_volume,
    //     mvd.maximum_volume,
    //     mvd.discount_price
    //     FROM analyses_client_price_details acpd
    //     LEFT JOIN monthly_volume_discount mvd
    //     ON mvd.analysis_client_price_ids = acpd.analysis_client_price_id WHERE acpd.client_account_ids = ? AND mvd.client_account_ids= ? AND acpd.is_active = '1' AND mvd.is_deleted = '0'";
    //     $disc_stmt = $conn->prepare($disc_sql);
    //     $disc_stmt->bind_param("ii", $cid, $cid);
    //     $disc_stmt->execute();
    //     $disc_result = $disc_stmt->get_result();

    //     //Subscription
    //     $sub_sql = "SELECT analyses_client_price_details.analysis_name, subscription_contents.subscription_volume FROM subscription JOIN subscription_contents ON  subscription.subscription_id = subscription_contents.subscription_ids JOIN analyses_client_price_details ON  subscription_contents.analysis_client_price_ids = analyses_client_price_details.analysis_client_price_id WHERE subscription.client_account_ids = ?";
    //     $sub_stmt = $conn->prepare($sub_sql);
    //     $sub_stmt->bind_param("i", $cid);
    //     $sub_stmt->execute();
    //     $sub_result = $sub_stmt->get_result();

    //     //Subscription Total Amount
    //     $subt_sql = "SELECT subscription_price FROM subscription WHERE client_account_ids = ? ORDER BY subscription_id ASC";
    //     $subt_stmt = $conn->prepare($subt_sql);
    //     $subt_stmt->bind_param("i", $cid);
    //     $subt_stmt->execute();
    //     $subt_result = $subt_stmt->get_result();

    //     //Maintenance Fees
    //     $maint_sql = "SELECT maintenance_fee_type, maintenance_fee_amount FROM maintenance_fees WHERE client_account_ids = ?";
    //     $maint_stmt = $conn->prepare($maint_sql);
    //     $maint_stmt->bind_param("i", $cid);
    //     $maint_stmt->execute();
    //     $maint_result = $maint_stmt->get_result();
    // }

    public function customer_excel_details()
    {
        $conn = $this->getConnection();

        $cid = $_GET['cus'] ?? '';
        $usid = $_GET['ud'] ?? '';

        // Fetch Customer Details
        $csql = "SELECT t3.client_name, t2.email, t1.site_code, t3.client_number, t1.client_site_name, t1.is_headquarters, t1.address_line1, t1.address_line2, t1.city, t1.state, t1.zipcode, t1.phone_number, t1.contract_tat, t1.contract_tat_unit, t1.is_active
            FROM users t2
            LEFT JOIN client_details t1 ON (t2.user_id = t1.user_ids)
            LEFT JOIN clients t3 ON (t1.client_ids = t3.client_id)
            WHERE t2.user_id = ? AND t2.is_deleted != '1'";
        $cstmt = $conn->prepare($csql);
        $cstmt->bind_param("i", $usid);
        $cstmt->execute();
        $cresult = $cstmt->get_result();
        $customerDetails = $cresult->fetch_assoc();

        // Fetch Setup Details
        $set_sql = "SELECT analysis_name, analysis_invoicing_description, analysis_client_price, analysis_code
                FROM analyses_client_price_details
                WHERE client_account_ids = ? AND is_active = '1' AND is_deleted = '0'";
        $set_stmt = $conn->prepare($set_sql);
        $set_stmt->bind_param("i", $cid);
        $set_stmt->execute();
        $set_result = $set_stmt->get_result();

        // Fetch Monthly Discount Details
        $disc_sql = "SELECT acpd.analysis_name, mvd.minimum_volume, mvd.maximum_volume, mvd.discount_price
                 FROM analyses_client_price_details acpd
                 LEFT JOIN monthly_volume_discount mvd ON mvd.analysis_client_price_ids = acpd.analysis_client_price_id
                 WHERE acpd.client_account_ids = ? AND mvd.client_account_ids= ? AND acpd.is_active = '1' AND mvd.is_deleted = '0'";
        $disc_stmt = $conn->prepare($disc_sql);
        $disc_stmt->bind_param("ii", $cid, $cid);
        $disc_stmt->execute();
        $disc_result = $disc_stmt->get_result();

        // Fetch Subscription Details
        $sub_sql = "SELECT analyses_client_price_details.analysis_name AS analysis_name, subscription_contents.subscription_volume AS subscription_volume
                FROM subscription
                JOIN subscription_contents ON  subscription.subscription_id = subscription_contents.subscription_ids
                JOIN analyses_client_price_details ON  subscription_contents.analysis_client_price_ids = analyses_client_price_details.analysis_client_price_id
                WHERE subscription.client_account_ids = ?";
        $sub_stmt = $conn->prepare($sub_sql);
        $sub_stmt->bind_param("i", $cid);
        $sub_stmt->execute();
        $sub_result = $sub_stmt->get_result();

        // Fetch Subscription Total Amount
        $subt_sql = "SELECT subscription_price FROM subscription WHERE client_account_ids = ? ORDER BY subscription_id ASC";
        $subt_stmt = $conn->prepare($subt_sql);
        $subt_stmt->bind_param("i", $cid);
        $subt_stmt->execute();
        $subt_result = $subt_stmt->get_result();

        // Fetch Maintenance Fees
        $maint_sql = "SELECT maintenance_fee_type, maintenance_fee_amount FROM maintenance_fees WHERE client_account_ids = ?";
        $maint_stmt = $conn->prepare($maint_sql);
        $maint_stmt->bind_param("i", $cid);
        $maint_stmt->execute();
        $maint_result = $maint_stmt->get_result();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('Client Details')
            ->setDescription('Generated Excel report for client details.');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Customer Details');

        // Customer Details Section
        $sheet->setCellValue('A2', 'Customer Details');
        $styleArrayTitle = [
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFB0E0E6'], // Powder Blue
            ],
        ];

        $styleMainTitle = [
            'font' => ['bold' => true, 'size' => 17],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF87CEEB'], // Sky Blue
            ],
        ];
        $sheet->getStyle('A2')->applyFromArray($styleMainTitle);

        $customerHeaders = ['Name', 'Email Address', 'Site Code', 'Client Number', 'Client Site Name', 'Headquarters', 'Address Line 1', 'Address Line 2', 'City', 'State', 'Zip Code', 'Phone Number', 'Default turn Around Time', 'Status'];
        $sheet->fromArray([$customerHeaders], NULL, 'A3');

        $customerData = [
            $customerDetails['client_name'] ?? '',
            $customerDetails['email'] ?? '',
            $customerDetails['site_code'] ?? '',
            $customerDetails['client_number'] ?? '',
            $customerDetails['client_site_name'] ?? '',
            ($customerDetails['is_headquarters'] == '1' ? 'Yes' : 'No'),
            $customerDetails['address_line1'] ?? '',
            $customerDetails['address_line2'] ?? '',
            $customerDetails['city'] ?? '',
            $customerDetails['state'] ?? '',
            $customerDetails['zipcode'] ?? '',
            $customerDetails['phone_number'] ?? '',
            //($customerDetails['contract_tat'] ?? '') . ' Hours',
            //(!empty($customerDetails['contract_tat']) ? $customerDetails['contract_tat'] . ' Hours' : ''),
            $customerDefaultTat = $customerDetails['contract_tat'] . ' ' . $customerDetails['contract_tat_unit'],
            ($customerDetails['is_active'] == '1' ? 'Active' : ($customerDetails['is_active'] == '0' ? 'Inactive' : 'Dormant')),
        ];
        $sheet->fromArray([$customerData], NULL, 'A4');

        $lastCustomerColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($customerHeaders));
        $headerCustomerRange = 'A3:' . $lastCustomerColumn . '3';
        $styleArrayHeader = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFADD8E6'],
            ],
        ];
        $sheet->getStyle($headerCustomerRange)->applyFromArray($styleArrayHeader);
        foreach (range('A', $lastCustomerColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Setup Section
        $sheet->setCellValue('A6', 'Setup');
        $sheet->getStyle('A6')->applyFromArray($styleArrayTitle);

        $setupHeaders = ['Sl. No.', 'Analysis', 'Description', 'Price', 'Item'];
        $sheet->fromArray([$setupHeaders], NULL, 'A7');

        $rowNumber = 8;
        $slNo = 1;
        while ($setup = $set_result->fetch_assoc()) {
            $dataRow = [
                $slNo++,
                $setup['analysis_name'],
                $setup['analysis_invoicing_description'],
                $setup['analysis_client_price'],
                $setup['analysis_code'],
            ];
            $sheet->fromArray([$dataRow], NULL, 'A' . $rowNumber++);
        }
        $set_result->free(); // Free the result set

        $lastSetupColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($setupHeaders));
        $headerSetupRange = 'A7:' . $lastSetupColumn . '7';
        $sheet->getStyle($headerSetupRange)->applyFromArray($styleArrayHeader);
        foreach (range('A', $lastSetupColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Monthly Discount Section
        //$sheet->setCellValue('A' . $rowNumber + 1, 'Monthly Discount');
        var_dump('A' . ($rowNumber + 1));
        $sheet->setCellValue('A' . ($rowNumber + 1), 'Monthly Discount');
        //$sheet->getStyle('A' . $rowNumber + 1)->applyFromArray($styleArrayTitle);
        $sheet->getStyle('A' . ($rowNumber + 1))->applyFromArray($styleArrayTitle);
        $rowNumber += 2;

        $discountHeaders = ['Sl. No.', 'Analysis', 'From', 'To', 'Percentage'];
        $sheet->fromArray([$discountHeaders], NULL, 'A' . $rowNumber);
        $rowNumber++;

        $slNo = 1;
        while ($discount = $disc_result->fetch_assoc()) {
            $dataRow = [
                $slNo++,
                $discount['analysis_name'],
                $discount['minimum_volume'],
                $discount['maximum_volume'],
                $discount['discount_price'],
            ];
            $sheet->fromArray([$dataRow], NULL, 'A' . $rowNumber++);
        }
        $disc_result->free(); // Free the result set

        $lastDiscountColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($discountHeaders));
        $headerDiscountRange = 'A' . ($rowNumber - $slNo) . ':' . $lastDiscountColumn . ($rowNumber - $slNo);
        $sheet->getStyle($headerDiscountRange)->applyFromArray($styleArrayHeader);
        foreach (range('A', $lastDiscountColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Subscription Section
        var_dump('A' . ($rowNumber + 1));
        $sheet->setCellValue('A' . ($rowNumber + 1), 'Subscription');
        //$sheet->getStyle('A' . $rowNumber + 1)->applyFromArray($styleArrayTitle);
        $sheet->getStyle('A' . ($rowNumber + 1))->applyFromArray($styleArrayTitle);
        $rowNumber += 2;

        $subscriptionHeaders = ['Sl. No.', 'Analysis', 'Count', 'Subscription Amount'];
        $sheet->fromArray([$subscriptionHeaders], NULL, 'A' . $rowNumber);
        $headerRowNumber = $rowNumber; // Store header row number
        $rowNumber++;

        $slNo = 1;
        $sub_data = [];
        while ($subscription = $sub_result->fetch_assoc()) {
            $sub_data[] = $subscription;
        }
        $sub_result->free();

        $subt_data = [];
        while ($subscriptionTotal = $subt_result->fetch_assoc()) {
            $subt_data[] = $subscriptionTotal;
        }
        $subt_result->free();

        $startRow = $rowNumber; // Store the starting row for merging
        $totalAmount = 0; // Initialize total amount

        for ($i = 0; $i < max(count($sub_data), count($subt_data)); $i++) {
            $sub = $sub_data[$i] ?? null;
            $sub_total = $subt_data[$i] ?? null;

            $amount = $sub_total['subscription_price'] ?? 0;
            $totalAmount += $amount; // Accumulate the amount

            $dataRow = [
                $slNo++,
                $sub['analysis_name'] ?? '',
                $sub['subscription_volume'] ?? '',
                '', // Leave Subscription Amount blank for now
            ];
            $sheet->fromArray([$dataRow], NULL, 'A' . $rowNumber++);
        }

        $endRow = $rowNumber - 1; // Last row with subscription data

        // Merge cells for Subscription Amount
        if ($startRow <= $endRow) {
            $sheet->mergeCells('D' . $startRow . ':D' . $endRow);
            $sheet->setCellValue('D' . $startRow, $totalAmount);
            $sheet->getStyle('D' . $startRow)->getFont()->setBold(true); // Bold the total amount
            $sheet->getStyle('D' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Center align the amount
        }

        $lastSubscriptionColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($subscriptionHeaders));
        $headerSubscriptionRange = 'A' . $headerRowNumber . ':' . $lastSubscriptionColumn . $headerRowNumber;
        $sheet->getStyle($headerSubscriptionRange)->applyFromArray($styleArrayHeader);
        foreach (range('A', $lastSubscriptionColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Maintenance Fees Section
        //$sheet->setCellValue('A' . $rowNumber + 1, 'Maintenance Fees');
        var_dump('A' . ($rowNumber + 1));
        $sheet->setCellValue('A' . ($rowNumber + 1), 'Maintenance Fees');
        //$sheet->getStyle('A' . $rowNumber + 1)->applyFromArray($styleArrayTitle);
        $sheet->getStyle('A' . ($rowNumber + 1))->applyFromArray($styleArrayTitle);
        $rowNumber += 2;

        $maintenanceHeaders = ['Sl. No.', 'Maintenance Type', 'Maintenance Amount'];
        $sheet->fromArray([$maintenanceHeaders], NULL, 'A' . $rowNumber);
        $rowNumber++;

        $slNo = 1;
        while ($maintenance = $maint_result->fetch_assoc()) {
            $dataRow = [
                $slNo++,
                $maintenance['maintenance_fee_type'] ?? '',
                $maintenance['maintenance_fee_amount'] ?? '',
            ];
            $sheet->fromArray([$dataRow], NULL, 'A' . $rowNumber++);
        }
        $maint_result->free(); // Free the result set

        $lastMaintenanceColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($maintenanceHeaders));
        $headerMaintenanceRange = 'A' . ($rowNumber - $slNo) . ':' . $lastMaintenanceColumn . ($rowNumber - $slNo);
        $sheet->getStyle($headerMaintenanceRange)->applyFromArray($styleArrayHeader);
        foreach (range('A', $lastMaintenanceColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Client_Details_' . ($customerDetails['client_name'] ?? 'Unknown') . '.xlsx';
        $filePath = '/tmp/' . $filename;
        $writer->save($filePath);

        if (!file_exists($filePath)) {
            die("Error: File not created.");
        }

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        unlink($filePath);
        exit;
    }

    public function export_analyses_category_info_excel()
    {
        if (!isset($_SESSION['user']->user_id)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Unauthorized access"]);
            exit;
        }

        $con = $this->getConnection();
        //$search_str = $_POST['search_value'] ?? '';
        //$search_str = $_GET['search']['value'];
        $search_str = $_POST['searchValue'] ?? '';

        $sql = "SELECT category_id, category_name, is_active FROM analyses_category WHERE is_deleted != '1'";
        if (!empty($search_str)) {
            $sql .= " AND (category_id LIKE '%" . $search_str . "%'";
            if (strtolower($search_str) == 'active') {
                $sql .= " OR is_active = '1'";
            } elseif (strtolower($search_str) == 'inactive') {
                $sql .= " OR is_active = '0'";
            }
            $sql .= " OR category_name LIKE '%" . $search_str . "%')";
        }

        $sql .= " ORDER BY category_name ASC";

        $query = mysqli_query($con, $sql);
        if (!$query) {
            echo json_encode(["error" => "Database error: " . mysqli_error($con)]);
            exit;
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('Analyses Category Info')
            ->setDescription('Exported analyses category info using PhpSpreadsheet.');

        $headers = ["SL No", "Category Name", "Status"];
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $mergeRange = "A2:{$lastColumn}2";

        $sheet->mergeCells($mergeRange);
        $sheet->setCellValue('A2', 'Analyses Categories');
        $styleMainTitle = [
            'font' => ['bold' => true, 'size' => 17],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,  'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF87CEEB'], // Sky Blue
            ],
        ];
        $sheet->getStyle('A2')->applyFromArray($styleMainTitle);
        //$sheet->getStyle('A2')->getFont()->setBold(true);


        $sheet->fromArray([$headers], NULL, 'A3');

        $styleArray = [
            // 'font' => ['bold' => true],
            // 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            // 'fill' => [
            //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            //     'startColor' => ['argb' => 'FFFFE0B2'],
            // ],
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFADD8E6'],
            ],
        ];
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $sheet->getStyle("A3:$lastColumn" . "3")->applyFromArray($styleArray);

        $sheet->getStyle('A3')
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        foreach (range('A', $lastColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $rowNumber = 4;
        $sl = 1;
        while ($row = mysqli_fetch_assoc($query)) {
            $status = ($row['is_active'] == 1) ? 'Active' : 'Inactive';
            $dataRow = [$sl++, $row['category_name'], $status];
            $sheet->fromArray([$dataRow], NULL, 'A' . $rowNumber++);
        }

        $dataStartRow = 4;
        $dataEndRow = $rowNumber - 1;

        // Right-align only the "SL No" column (Column A)
        $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Left-align all remaining data columns (starting from column B)
        for ($col = 2; $col <= count($headers); $col++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->getStyle("{$colLetter}{$dataStartRow}:{$colLetter}{$dataEndRow}")
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }

        // $dataRange = "A{$dataStartRow}:{$lastColumn}{$dataEndRow}";

        // $sheet->getStyle($dataRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);


        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filePath = '/tmp/analyses_category_info.xlsx';
        $writer->save($filePath);

        if (!file_exists($filePath)) {
            die("Error: File not created.");
        }

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="analyses_category_info.xlsx"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        unlink($filePath);
        exit;
    }

    public function export_analyses_info_excel()
    {
        if (!isset($_SESSION['user']->user_id)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Unauthorized access"]);
            exit;
        }

        $con = $this->getConnection();
        $search_str = $_POST['searchValue'] ?? '';

        $sql = "SELECT t1.analysis_id, t1.analysis_name, t2.category_name, t1.analysis_number, t1.analysis_price, t1.time_to_analyze, t1.time_unit 
            FROM analyses t1 
            INNER JOIN analyses_category t2 ON t1.category_ids = t2.category_id 
            WHERE t1.is_deleted != '1' AND t2.is_deleted != '1'";

        if (!empty($search_str)) {
            $sql .= " AND (t1.analysis_name LIKE '%" . $search_str . "%' ";
            $sql .= " OR t1.analysis_number LIKE '%" . $search_str . "%' ";
            if (is_numeric($search_str)) {
                $sql .= " OR t1.time_to_analyze = '" . $search_str . "' ";
                $sql .= " OR t1.analysis_price = '" . $search_str . "' ";
            }
            $sql .= " OR t2.category_name LIKE '%" . $search_str . "%')";
        }

        $sql .= " ORDER BY t1.analysis_name ASC";

        $query = mysqli_query($con, $sql);
        if (!$query) {
            echo json_encode(["error" => "Database error: " . mysqli_error($con)]);
            exit;
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('Global Analyses Info')
            ->setDescription('Exported global analyses info using PhpSpreadsheet.');

        $headers = ["SL No", "Analysis Name", "Category", "Item No.", "Price", "Default Time"];
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $mergeRange = "A2:{$lastColumn}2";

        $sheet->mergeCells($mergeRange);
        $sheet->setCellValue('A2', 'Global Analyses Information');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 17],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,  'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF87CEEB'],
            ],
        ]);

        $sheet->fromArray([$headers], NULL, 'A3');
        $sheet->getStyle("A3:{$lastColumn}3")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFADD8E6'],
            ],
        ]);

        foreach (range('A', $lastColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $rowNumber = 4;
        $sl = 1;
        while ($row = mysqli_fetch_assoc($query)) {
            $dataRow = [
                $sl++,
                $row['analysis_name'],
                $row['category_name'],
                $row['analysis_number'],
                "$" . $row['analysis_price'],
                // $row['time_to_analyze']
                $row['time_to_analyze'] . ' ' . $row['time_unit']
            ];
            $sheet->fromArray([$dataRow], NULL, 'A' . $rowNumber++);
        }

        $dataStartRow = 4;
        $dataEndRow = $rowNumber - 1;

        // Right-align SL No column
        $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Left-align rest
        for ($col = 2; $col <= count($headers); $col++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->getStyle("{$colLetter}{$dataStartRow}:{$colLetter}{$dataEndRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filePath = '/tmp/analyses_info.xlsx';
        $writer->save($filePath);

        if (!file_exists($filePath)) {
            die("Error: File not created.");
        }

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="analyses_info.xlsx"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        unlink($filePath);
        exit;
    }

    public function export_client_analyses_info_excel()
    {
        if (!isset($_SESSION['user']->user_id)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Unauthorized access"]);
            exit;
        }

        $con = $this->getConnection();
        $user_id = $_POST['cid'] ?? '';
        $selopt = $_POST['sel_analyses'] ?? '';

        // Get client ID
        $find_sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
        $find_stmt = $con->prepare($find_sql);
        if (!$find_stmt) {
            echo json_encode(['error' => 'Prepare failed', 'detail' => $con->error]);
            exit;
        }

        $find_stmt->bind_param("s", $user_id);
        $find_stmt->execute();
        $find_stmt->bind_result($cid);
        $find_stmt->fetch();
        $find_stmt->close();

        // Build the query
        $sql = "SELECT
        a.analysis_id,
        COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
        acpd.analysis_client_price_id AS analysis_client_price_id,
        COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
        COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
        COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
        COALESCE(acpd.is_active, a.is_active) AS is_active,
        ac.category_name,
        ac.category_id
        FROM analyses a
        LEFT JOIN analyses_client_price_details acpd ON acpd.analysis_id = a.analysis_id AND acpd.client_account_ids = '$cid'
        LEFT JOIN analyses_category ac ON a.category_ids = ac.category_id";

        if ($selopt == 'active') {
            $sql .= " WHERE (
                    acpd.analysis_id IS NOT NULL AND acpd.is_active = '1'
                ) OR (
                    acpd.analysis_id IS NULL AND a.is_active = '1'
                )";
        }

        if ($selopt == 'inactive') {
            $sql .= " WHERE (
                    acpd.analysis_id IS NOT NULL AND acpd.is_active = '0'
                ) OR (
                    acpd.analysis_id IS NULL AND a.is_active = '0'
                )";
        }

        $sql .= " ORDER BY analysis_name ASC";

        $query = mysqli_query($con, $sql);
        if (!$query) {
            echo json_encode(["error" => "Database error: " . mysqli_error($con)]);
            exit;
        }

        // Spreadsheet setup
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('Client Analyses Info')
            ->setDescription('Exported client-based analyses info.');

        $headers = ["SL No", "Analysis Name", "Invoicing Description", "Category", "Price", "Item No.", "Active"];
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->setCellValue('A2', 'Client Analyses Information');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 17],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF87CEEB']],
        ]);

        $sheet->fromArray([$headers], NULL, 'A3');
        $sheet->getStyle("A3:{$lastColumn}3")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFADD8E6']],
        ]);

        foreach (range('A', $lastColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $rowNumber = 4;
        $sl = 1;
        while ($row = mysqli_fetch_assoc($query)) {
            $dataRow = [
                $sl++,
                $row['analysis_name'],
                $row['analysis_invoicing_description'],
                $row['category_name'],
                "$" . $row['analysis_price'],
                $row['analysis_number'],
                ($row['is_active'] == '1' ? 'Yes' : 'No')
            ];
            $sheet->fromArray([$dataRow], NULL, 'A' . $rowNumber++);
        }

        // Alignment
        $dataStartRow = 4;
        $dataEndRow = $rowNumber - 1;
        $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        for ($col = 2; $col <= count($headers); $col++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->getStyle("{$colLetter}{$dataStartRow}:{$colLetter}{$dataEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }

        // Output file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filePath = '/tmp/client_analyses_info.xlsx';
        $writer->save($filePath);

        if (!file_exists($filePath)) {
            die("Error: File not created.");
        }

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="client_analyses_info.xlsx"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        unlink($filePath);
        exit;
    }

    public function isValidDate($date, $format = 'm-d-Y')
    {
        $date = trim($date);
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function export_user_info_excel()
    {
        if (!isset($_SESSION['user']->user_id)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Unauthorized access"]);
            exit;
        }

        $con = $this->getConnection();
        $search_str = trim($_POST['searchValue'] ?? '');

        $sql = "SELECT t1.user_id, t1.user_name, t1.email, t1.created_at, t1.is_active, t2.user_type 
            FROM users t1 
            JOIN user_type t2 ON t1.user_type_ids = t2.user_type_id 
            WHERE t1.is_deleted != '1' AND t2.is_deleted != '1'";

        if (!empty($search_str)) {
            $sql .= " AND (t1.user_id LIKE '%$search_str%' 
                    OR t1.user_name LIKE '%$search_str%' 
                    OR t1.email LIKE '%$search_str%' 
                    OR t2.user_type LIKE '%$search_str%'";

            if (strtolower($search_str) == 'active') {
                $sql .= " OR t1.is_active = '1'";
            } elseif (strtolower($search_str) == 'inactive') {
                $sql .= " OR t1.is_active = '0'";
            } elseif (strtolower($search_str) == 'dormant') {
                $sql .= " OR t1.is_active = '2'";
            }

            if ($this->isValidDate($search_str)) {
                $date = DateTime::createFromFormat('m-d-Y', $search_str);
                $c_date = $date->format('Y-m-d');
                $sql .= " OR DATE(t1.created_at) = '$c_date')";
            } else {
                $sql .= ")";
            }
        }

        $sql .= " ORDER BY t1.user_name ASC";
        $query = mysqli_query($con, $sql);

        if (!$query) {
            echo json_encode(["error" => "Database error: " . mysqli_error($con)]);
            exit;
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('User Info')
            ->setDescription('Exported user info using PhpSpreadsheet.');

        $headers = ["SL No", "Name", "Email", "User Type", "Created At", "Status"];
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $mergeRange = "A2:{$lastColumn}2";

        $sheet->mergeCells($mergeRange);
        $sheet->setCellValue('A2', 'User Information');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 17],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFB0E0E6'],
            ],
        ]);

        $sheet->fromArray([$headers], NULL, 'A3');
        $sheet->getStyle("A3:{$lastColumn}3")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFADD8E6'],
            ],
        ]);

        foreach (range('A', $lastColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $rowNumber = 4;
        $sl = 1;
        while ($row = mysqli_fetch_assoc($query)) {
            // $status = match ($row['is_active']) {
            //     '1' => 'Active',
            //     '0' => 'Inactive',
            //     '2' => 'Dormant',
            //     default => 'Unknown'
            // };

            switch ($row['is_active']) {
                case '1':
                    $status = 'Active';
                    break;
                case '0':
                    $status = 'Inactive';
                    break;
                case '2':
                    $status = 'Dormant';
                    break;
                default:
                    $status = 'Unknown';
            }


            $dataRow = [
                $sl++,
                $row['user_name'],
                $row['email'],
                $row['user_type'],
                date("m-d-Y", strtotime($row['created_at'])),
                $status
            ];

            $sheet->fromArray([$dataRow], NULL, 'A' . $rowNumber++);
        }

        $dataStartRow = 4;
        $dataEndRow = $rowNumber - 1;

        $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        for ($col = 2; $col <= count($headers); $col++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->getStyle("{$colLetter}{$dataStartRow}:{$colLetter}{$dataEndRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filePath = '/tmp/user_info.xlsx';
        $writer->save($filePath);

        if (!file_exists($filePath)) {
            die("Error: File not created.");
        }

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="user_info.xlsx"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        unlink($filePath);
        exit;
    }

    public function export_customer_info_excel()
    {
        if (!isset($_SESSION['user']->user_id)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Unauthorized access"]);
            exit;
        }

        $con = $this->getConnection();
        $search_str = trim($_POST['searchValue'] ?? '');

        $sql = "SELECT user_id, user_name, email, created_at, is_active 
            FROM users 
            WHERE user_type_ids = 5 AND is_deleted != '1'";

        if (!empty($search_str)) {
            $sql .= " AND (user_id LIKE '%$search_str%' 
                OR user_name LIKE '%$search_str%' 
                OR email LIKE '%$search_str%'";

            if (strtolower($search_str) === 'active') {
                $sql .= " OR is_active = '1'";
            } elseif (strtolower($search_str) === 'inactive') {
                $sql .= " OR is_active = '0'";
            } elseif (strtolower($search_str) === 'dormant') {
                $sql .= " OR is_active = '2'";
            }

            if ($this->isValidDate($search_str)) {
                $date = DateTime::createFromFormat('m-d-Y', $search_str);
                $c_date = $date->format('Y-m-d');
                $sql .= " OR DATE(created_at) = '$c_date')";
            } else {
                $sql .= ")";
            }
        }

        $sql .= " ORDER BY user_name ASC";
        $query = mysqli_query($con, $sql);

        if (!$query) {
            echo json_encode(["error" => "Database error: " . mysqli_error($con)]);
            exit;
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('Client Info')
            ->setDescription('Exported client info using PhpSpreadsheet.');

        $headers = ["SL No", "Name", "Email", "Created At", "Status"];
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $mergeRange = "A2:{$lastColumn}2";

        $sheet->mergeCells($mergeRange);
        $sheet->setCellValue('A2', 'Client Information');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 17],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFB0E0E6'],
            ],
        ]);

        $sheet->fromArray([$headers], NULL, 'A3');
        $sheet->getStyle("A3:{$lastColumn}3")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFADD8E6'],
            ],
        ]);

        foreach (range('A', $lastColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $rowNumber = 4;
        $sl = 1;
        while ($row = mysqli_fetch_assoc($query)) {
            switch ($row['is_active']) {
                case '1':
                    $status = 'Active';
                    break;
                case '0':
                    $status = 'Inactive';
                    break;
                case '2':
                    $status = 'Dormant';
                    break;
                default:
                    $status = 'Unknown';
            }

            $dataRow = [
                $sl++,
                $row['user_name'],
                $row['email'],
                date("m-d-Y", strtotime($row['created_at'])),
                $status
            ];

            $sheet->fromArray([$dataRow], NULL, 'A' . $rowNumber++);
        }

        $dataStartRow = 4;
        $dataEndRow = $rowNumber - 1;

        $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        for ($col = 2; $col <= count($headers); $col++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->getStyle("{$colLetter}{$dataStartRow}:{$colLetter}{$dataEndRow}")
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filePath = '/tmp/customer_info.xlsx';
        $writer->save($filePath);

        if (!file_exists($filePath)) {
            die("Error: File not created.");
        }

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="client_info.xlsx"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        unlink($filePath);
        exit;
    }

    // public function export_analyses_rates()
    // {
    //     $con = $this->getConnection();
    //     $selopt = $_POST['selection'] ?? '';
    //     $selcat = $_POST['selcat'] ?? '';
    //     $user_id = $_POST['cid'] ?? '';
    //     $cid = '';

    //     // Fetch client_account_id using user_id
    //     $find_stmt = $con->prepare("SELECT client_account_id FROM client_details WHERE user_ids = ?");
    //     if (!$find_stmt) {
    //         die(json_encode(['status' => 'error', 'message' => 'Prepare failed', 'error' => $con->error]));
    //     }

    //     $find_stmt->bind_param("s", $user_id);
    //     if ($find_stmt->execute()) {
    //         $find_stmt->bind_result($cid);
    //         if ($find_stmt->fetch()) {
    //             $find_stmt->close();

    //             $sql = "SELECT
    //                     a.analysis_id,
    //                     COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
    //                     acpd.analysis_client_price_id,
    //                     COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
    //                     COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
    //                     COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
    //                     COALESCE(acpd.is_active, a.is_active) AS is_active,
    //                     ac.category_name,
    //                     ac.category_id
    //                 FROM analyses a
    //                 LEFT JOIN analyses_client_price_details acpd 
    //                     ON acpd.analysis_id = a.analysis_id 
    //                     AND acpd.client_account_ids = '$cid'
    //                 LEFT JOIN analyses_category ac 
    //                     ON a.category_ids = ac.category_id
    //                 WHERE 1 = 1";

    //             // Apply filters
    //             if (!empty($selcat)) {
    //                 $sql .= " AND ac.category_id = '$selcat'";
    //             }

    //             if ($selopt === "active") {
    //                 $sql .= " AND (
    //                         (acpd.analysis_id IS NOT NULL AND acpd.is_active = '1')
    //                         OR
    //                         (acpd.analysis_id IS NULL AND a.is_active = '1')
    //                     )";
    //             } elseif ($selopt === "inactive") {
    //                 $sql .= " AND (
    //                         (acpd.analysis_id IS NOT NULL AND acpd.is_active = '0')
    //                         OR
    //                         (acpd.analysis_id IS NULL AND a.is_active = '0')
    //                     )";
    //             }

    //             // Run query
    //             $query = mysqli_query($con, $sql);
    //             $results = [];

    //             while ($row = mysqli_fetch_assoc($query)) {
    //                 $results[] = [
    //                     'analysis_id' => $row['analysis_id'],
    //                     'analysis_name' => $row['analysis_name'],
    //                     'invoicing_description' => $row['analysis_invoicing_description'],
    //                     'category_name' => $row['category_name'],
    //                     'analysis_price' => $row['analysis_price'],
    //                     'analysis_number' => $row['analysis_number'],
    //                     'client_price_id' => $row['analysis_client_price_id'],
    //                     'is_active' => $row['is_active'],
    //                     'category_id' => $row['category_id']
    //                 ];
    //             }

    //             echo json_encode([
    //                 'status' => 'success',
    //                 'data' => $results
    //             ]);
    //             exit;
    //         }
    //     }

    //     echo json_encode(['status' => 'error', 'message' => 'Client ID not found']);
    // }

    // public function export_analyses_rates()
    // {
    //     if (!isset($_SESSION['user']->user_id)) {
    //         header('Content-Type: application/json');
    //         echo json_encode(["error" => "Unauthorized access"]);
    //         exit;
    //     }

    //     $con = $this->getConnection();
    //     $search_str = trim($_POST['searchValue'] ?? '');
    //     $selopt = $_POST['sel_analyses'] ?? '';
    //     $selcat = $_POST['sel_cat'] ?? '';
    //     $user_id = $_POST['cid'] ?? '';

    //     // Get client ID
    //     $find_sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
    //     $find_stmt = $con->prepare($find_sql);
    //     $find_stmt->bind_param("s", $user_id);
    //     $cid = '';

    //     if (!$find_stmt->execute()) {
    //         echo json_encode(["error" => "Client fetch failed"]);
    //         exit;
    //     }

    //     $find_stmt->bind_result($cid);
    //     if (!$find_stmt->fetch()) {
    //         echo json_encode(["error" => "Client not found"]);
    //         exit;
    //     }
    //     $find_stmt->close();

    //     // Build the SQL query
    //     $sql = "SELECT
    //     a.analysis_id,
    //     COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
    //     acpd.analysis_client_price_id AS analysis_client_price_id,
    //     COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
    //     COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
    //     COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
    //     COALESCE(acpd.is_active, a.is_active) AS is_active,
    //     ac.category_name,
    //     ac.category_id
    // FROM analyses a
    // LEFT JOIN analyses_client_price_details acpd 
    //     ON acpd.analysis_id = a.analysis_id 
    //     AND acpd.client_account_ids = '$cid'
    // LEFT JOIN analyses_category ac 
    //     ON a.category_ids = ac.category_id
    // WHERE 1=1";

    //     if (!empty($selcat)) {
    //         $sql .= " AND ac.category_id = '$selcat'";
    //     }

    //     if (!empty($search_str)) {
    //         $sql .= " AND (
    //         COALESCE(acpd.analysis_name, a.analysis_name) LIKE '%$search_str%' 
    //         OR COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) LIKE '%$search_str%'
    //         OR ac.category_name LIKE '%$search_str%'
    //         OR COALESCE(acpd.analysis_price, a.analysis_price) LIKE '%$search_str%'
    //         OR COALESCE(acpd.analysis_code, a.analysis_number) LIKE '%$search_str%'";

    //         if (strtolower($search_str) === 'active') {
    //             $sql .= " OR COALESCE(acpd.is_active, a.is_active) = '1'";
    //         } elseif (strtolower($search_str) === 'inactive') {
    //             $sql .= " OR COALESCE(acpd.is_active, a.is_active) = '0'";
    //         }

    //         $sql .= ")";
    //     }

    //     $sql .= " ORDER BY analysis_name ASC";

    //     $query = mysqli_query($con, $sql);
    //     if (!$query) {
    //         echo json_encode(["error" => "Query error: " . mysqli_error($con)]);
    //         exit;
    //     }

    //     // PhpSpreadsheet logic
    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $spreadsheet->getProperties()
    //         ->setCreator('Dicon')
    //         ->setLastModifiedBy('Dicon')
    //         ->setTitle('Analyses Info')
    //         ->setDescription('Exported analyses info using PhpSpreadsheet.');

    //     $headers = ["SL No", "Analysis Name", "Invoicing Description", "Category", "Price", "Analysis Number", "Status"];
    //     $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
    //     $mergeRange = "A2:{$lastColumn}2";

    //     $sheet->mergeCells($mergeRange);
    //     $sheet->setCellValue('A2', 'Analyses Information');
    //     $sheet->getStyle('A2')->applyFromArray([
    //         'font' => ['bold' => true, 'size' => 17],
    //         'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    //         'fill' => [
    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //             'startColor' => ['argb' => 'FFB0E0E6'],
    //         ],
    //     ]);

    //     $sheet->fromArray([$headers], NULL, 'A3');
    //     $sheet->getStyle("A3:{$lastColumn}3")->applyFromArray([
    //         'font' => ['bold' => true],
    //         'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT],
    //         'fill' => [
    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //             'startColor' => ['argb' => 'FFADD8E6'],
    //         ],
    //     ]);

    //     foreach (range('A', $lastColumn) as $col) {
    //         $sheet->getColumnDimension($col)->setAutoSize(true);
    //     }

    //     $rowNumber = 4;
    //     $sl = 1;
    //     while ($row = mysqli_fetch_assoc($query)) {
    //         // $status = match ($row['is_active']) {
    //         //     '1' => 'Active',
    //         //     '0' => 'Inactive',
    //         //     default => 'Unknown'
    //         // };

    //         switch ($row['is_active']) {
    //             case '1':
    //                 $status = 'Active';
    //                 break;
    //             case '0':
    //                 $status = 'Inactive';
    //                 break;
    //             default:
    //                 $status = 'Unknown';
    //         }

    //         $dataRow = [
    //             $sl++,
    //             $row['analysis_name'],
    //             $row['analysis_invoicing_description'],
    //             $row['category_name'],
    //             $row['analysis_price'],
    //             $row['analysis_number'],
    //             $status
    //         ];

    //         $sheet->fromArray([$dataRow], NULL, 'A' . $rowNumber++);
    //     }

    //     // Alignment for SL No
    //     $dataStartRow = 4;
    //     $dataEndRow = $rowNumber - 1;

    //     $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")
    //         ->getAlignment()
    //         ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    //     for ($col = 2; $col <= count($headers); $col++) {
    //         $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    //         $sheet->getStyle("{$colLetter}{$dataStartRow}:{$colLetter}{$dataEndRow}")
    //             ->getAlignment()
    //             ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    //     }

    //     $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    //     $filePath = '/tmp/analyses_rates_info.xlsx';
    //     $writer->save($filePath);

    //     if (!file_exists($filePath)) {
    //         die("Error: File not created.");
    //     }

    //     ob_end_clean();
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment; filename="analyses_rates_info.xlsx"');
    //     header('Content-Length: ' . filesize($filePath));
    //     readfile($filePath);
    //     unlink($filePath);
    //     exit;
    // }

    public function export_analyses_rates()
    {
        if (!isset($_SESSION['user']->user_id)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Unauthorized access"]);
            exit;
        }

        $con = $this->getConnection();
        $search_str = trim($_POST['searchValue'] ?? '');
        $selopt = $_POST['sel_analyses'] ?? ''; // all / active / inactive
        $selcat = $_POST['sel_cat'] ?? '';
        $user_id = $_POST['cid'] ?? '';

        // Get client ID
        $find_sql = "SELECT client_account_id FROM client_details WHERE user_ids = ?";
        $find_stmt = $con->prepare($find_sql);
        $find_stmt->bind_param("s", $user_id);
        $cid = '';

        if (!$find_stmt->execute()) {
            echo json_encode(["error" => "Client fetch failed"]);
            exit;
        }

        $find_stmt->bind_result($cid);
        if (!$find_stmt->fetch()) {
            echo json_encode(["error" => "Client not found"]);
            exit;
        }
        $find_stmt->close();

        // Build the SQL query
        $sql = "SELECT
        a.analysis_id,
        COALESCE(acpd.analysis_code, a.analysis_number) AS analysis_number,
        acpd.analysis_client_price_id AS analysis_client_price_id,
        COALESCE(acpd.analysis_name, a.analysis_name) AS analysis_name,
        COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) AS analysis_invoicing_description,
        COALESCE(acpd.analysis_client_price, a.analysis_price) AS analysis_price,
        COALESCE(acpd.is_active, a.is_active) AS is_active,
        ac.category_name,
        ac.category_id
    FROM analyses a
    LEFT JOIN analyses_client_price_details acpd 
        ON acpd.analysis_id = a.analysis_id AND acpd.client_account_ids = ?
    LEFT JOIN analyses_category ac 
        ON a.category_ids = ac.category_id
    WHERE 1=1
    AND ac.is_active = '1' AND ac.is_deleted = '0'";

        $params = [$cid];
        $types = "s";

        if (!empty($selcat)) {
            $sql .= " AND ac.category_id = ?";
            $params[] = $selcat;
            $types .= "s";
        }

        if (!empty($selopt) && $selopt !== 'all') {
            if ($selopt === 'active') {
                $sql .= " AND COALESCE(acpd.is_active, a.is_active) = '1'";
            } elseif ($selopt === 'inactive') {
                $sql .= " AND COALESCE(acpd.is_active, a.is_active) = '0'";
            }
        }

        if (!empty($search_str)) {
            $sql .= " AND (
            COALESCE(acpd.analysis_name, a.analysis_name) LIKE ?
            OR COALESCE(acpd.analysis_invoicing_description, a.analysis_invoicing_description) LIKE ?
            OR ac.category_name LIKE ?
            OR COALESCE(acpd.analysis_price, a.analysis_price) LIKE ?
            OR COALESCE(acpd.analysis_code, a.analysis_number) LIKE ?";

            $likeStr = '%' . $search_str . '%';
            $params = array_merge($params, [$likeStr, $likeStr, $likeStr, $likeStr, $likeStr]);
            $types .= "sssss";

            // Also search by keywords "active"/"inactive" if typed
            if (strtolower($search_str) === 'active') {
                $sql .= " OR COALESCE(acpd.is_active, a.is_active) = '1'";
            } elseif (strtolower($search_str) === 'inactive') {
                $sql .= " OR COALESCE(acpd.is_active, a.is_active) = '0'";
            }

            $sql .= ")";
        }

        $sql .= " ORDER BY analysis_name ASC";

        // Prepare and bind
        $stmt = $con->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            echo json_encode(["error" => "Query error: " . mysqli_error($con)]);
            exit;
        }

        // Excel setup
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setTitle('Analyses Info')
            ->setDescription('Exported analyses info using PhpSpreadsheet.');

        $headers = ["SL No", "Analysis Name", "Invoicing Description", "Category", "Price", "Analysis Number", "Status"];
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $mergeRange = "A2:{$lastColumn}2";

        $sheet->mergeCells($mergeRange);
        $sheet->setCellValue('A2', 'Analyses Information');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 17],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFB0E0E6'],
            ],
        ]);

        $sheet->fromArray([$headers], NULL, 'A3');
        $sheet->getStyle("A3:{$lastColumn}3")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFADD8E6'],
            ],
        ]);

        foreach (range('A', $lastColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $rowNumber = 4;
        $sl = 1;
        while ($row = $result->fetch_assoc()) {
            switch ($row['is_active']) {
                case '1':
                    $status = 'Active';
                    break;
                case '0':
                    $status = 'Inactive';
                    break;
                default:
                    $status = 'Unknown';
            }

            $dataRow = [
                $sl++,
                $row['analysis_name'],
                $row['analysis_invoicing_description'],
                $row['category_name'],
                $row['analysis_price'],
                $row['analysis_number'],
                $status
            ];

            $sheet->fromArray([$dataRow], NULL, 'A' . $rowNumber++);
        }

        $dataStartRow = 4;
        $dataEndRow = $rowNumber - 1;

        $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        for ($col = 2; $col <= count($headers); $col++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->getStyle("{$colLetter}{$dataStartRow}:{$colLetter}{$dataEndRow}")
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filePath = '/tmp/analyses_rates_info.xlsx';
        $writer->save($filePath);

        if (!file_exists($filePath)) {
            die("Error: File not created.");
        }

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="analyses_rates_info.xlsx"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        unlink($filePath);
        exit;
    }
}
