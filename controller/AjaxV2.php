<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AjaxV2 extends Controller
{

    public $Logindb;
    public $Admindb;
    public $user;
    public $Analyst;
    public $Ajax;
    public $Assigndb;

    function __construct()
    {
        //$this->connection = parent::loader()->database();
        $this->Logindb = $this->model('logindb');
        $this->Admindb = $this->model('admindb');
        $this->Ajax = $this->model('ajaxdb');
        $this->Analyst = $this->model('analystratemodel');
        $this->Assigndb = $this->model('adminassignmodel');

        $this->Report = $this->model('reportdb');

        if (isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 1 || $_SESSION['user']->user_type_ids == 3) {
            $this->user = $this->Admindb->user_obj($_SESSION['user']->email);
        } else {
            //die('Access forbidden');
            $this->add_alert('danger', 'Access forbidden');
            $this->redirect('');
        }
    }

    public function isValidDate($date, $format = 'm-d-Y')
    {
        $date = trim($date);
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function billing_summary_analyst_ajax()
    {
        $data = [];
        $data['user'] = $this->user;
        $form_data = $_POST;
        $ids = !empty($form_data['analysts']) ? $form_data['analysts'] : [];
        $start_date = !empty($form_data['start_date']) ? $form_data['start_date'] : '';
        $end_date = !empty($form_data['end_date']) ? $form_data['end_date'] : '';
        $data['analysts'] = $ids;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $data['wsheet'] = $this->Report->billing_summary_analyst($start_date, $end_date, $ids);

        $analysts = $this->Report->get_analyst_names();
        $data['analysts'] = $analysts;
        $this->view('v2/admin/billing/billing_summary_analyst_ajax', $data);
    }

    public function ajax_analysis_polpulate()
    {
        $id = $_GET["id"];
        $data = $this->Admindb->analyses_by_id($id);
        echo json_encode($data);
        die();
        //echo $id;
    }

    // public function save_subscription_amount()
    // {
    // 	if(isset($_REQUEST["id"]) && isset($_REQUEST["amount"]) && $_REQUEST["id"]!="" && $_REQUEST["amount"]!=""){
    // 		$id = $_REQUEST["id"];
    // 		$amount = $_REQUEST["amount"];
    // 		$user_data = $this->Admindb->user_by_id($id)['user_meta'];
    // 		$user_data = json_decode( $user_data );
    // 		$user_data->subscription_amount = $_REQUEST["amount"];
    // 		$status = $this->Admindb->user_update_meta($id,json_encode($user_data));
    // 		if($status['status'] = 'success') echo "1";
    // 	}
    // 	die();
    // }

    public function save_subscription_amount()
    {
        if (isset($_REQUEST["id"]) && isset($_REQUEST["amount"]) && $_REQUEST["id"] != "" && $_REQUEST["amount"] != "") {

            $id = $_REQUEST["id"];
            $amount = $_REQUEST["amount"];

            // $is_exists = $this->Admindb->is_exist_subscription_fees($id);
            // if(empty($is_exists)){
            // 		$status = $this->Admindb->subscription_fees_add($id,$amount);
            // 		if($status['status'] = 'success') echo "1";
            // 		die;
            // }else{		
            $customer_id = $id;
            // get old timeline id
            $time_line = $this->Admindb->get_user_timeline($customer_id);

            $time_id = $time_line[0]['time_id'];

            // get all analysis details from that id

            $all_analysis = $this->Admindb->fetch_all_analysis($time_id);

            $all_subscrptions = $this->Admindb->fetch_all_subscrptions($time_id);

            $all_discount_range = $this->Admindb->fetch_all_discount_range($time_id);

            $all_maintenance = $this->Admindb->fetch_all_maintenance($time_id);

            // creating new time id

            $this->Admindb->update_user_timeline($time_id);

            //getting newly created id

            $stat = $this->Admindb->add_usertime_line($customer_id);

            $time_id = $stat['time_id'];

            // insert all details with new time id


            foreach ($all_analysis as $key => $all_analys) {


                $status = $this->Admindb->analyses_rate_insert_with_new_time_id(
                    $all_analys['analysis'],
                    $all_analys['customer'],
                    $all_analys['rate'],
                    $all_analys['code'],
                    $all_analys['analysis_description'],
                    $all_analys['custom_description'],
                    $time_id,
                    $all_analys['min_time']
                );
            }

            foreach ($all_subscrptions as $key => $all_subscri) {

                $status = $this->Admindb->subscription_rate_insert_with_new_time_id(
                    $all_subscri['month'],
                    $all_subscri['analysis'],
                    $all_subscri['customer'],
                    $all_subscri['count'],
                    $time_id
                );
            }

            /* foreach ($all_discount_range as $key => $all_discount) {



              $status = $this->Admindb->discount_range_insert_with_new_time_id(
              $all_discount['customer'],
              $all_discount['minimum_value'],
              $all_discount['maximum_value'],
              $all_discount['percentage'],
              $time_id);
              }

              foreach ($all_maintenance as $key => $all_mainten) {



              $status = $this->Admindb->maintenance_insert_with_new_time_id(
              $all_mainten['customer'],
              $all_mainten['maintenance_fee_type'],
              $all_mainten['maintenance_fee_amount'],
              $time_id);
              } */

            $status = $this->Admindb->subscription_fees_add($id, $amount);

            if ($status['status'] = 'success')
                echo "1";

            die;

            /*  } */
        }

        die;
    }

    public function user_group_name($user_group_id)
    {
        $con = $this->getConnection();
        $sql = "SELECT user_type FROM `user_type` WHERE user_type_id = '$user_group_id'";
        $query = mysqli_query($con, $sql);
        $row = mysqli_fetch_row($query);
        return !empty($row[0]) ? $row[0] : '';
    }

    public function get_user_info()
    {
        $con = $this->getConnection();
        $request = $_REQUEST;
        $col = array(
            0 => 't1.user_name',
            1 => 't1.email',
            2 => 't2.user_type',
            3 => 't1.created_at',
            4 => 't1.is_active',
            5 => 't1.user_id'
        );  //create column like table in database
        //Search

        $search_str = trim($request['search']['value']);

        $sql = "SELECT t1.user_id, t1.user_name, t1.email, t1.created_at, t1.is_active, t1.user_type_ids, t2.user_type FROM users t1 JOIN user_type t2 ON (t1.user_type_ids = t2.user_type_id) WHERE t1.is_deleted != '1' AND t2.is_deleted != '1'";

        if (!empty($search_str)) {
            $sql .= " AND (t1.user_id Like '%" . $search_str . "%' ";
            $sql .= " OR t1.user_name Like '%" . $search_str . "%' ";
            $sql .= " OR t2.user_type Like '%" . $search_str . "%' ";
            $sql .= " OR t1.email Like '%" . $search_str . "%' ";

            if (strtolower($search_str) == 'active') {
                $sql .= " OR t1.is_active = '1' ";
            } else if (strtolower($search_str) == 'inactive') {
                $sql .= " OR t1.is_active = '0' ";
            } else if (strtolower($search_str) == 'dormant') {
                $sql .= " OR t1.is_active = '2' ";
            }

            //  $sql .= " OR t1.created_at Like '%" . $search_str . "%' )";
            if ($this->isValidDate($search_str)) {
                $date = DateTime::createFromFormat('m-d-Y', $search_str);
                $c_date = $date->format('Y-m-d');
                $sql .= " OR date(t1.created_at) = '$c_date' )";
            } else {
                $sql .= " )";
            }
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);
        $totalFilter = $totalData;

        //Order
        // $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
        //         $request['start'] . "  ," . $request['length'] . "  ";

        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];

        if ($request['length'] != -1) {
            $sql .= " LIMIT " . $request['start'] . " ," . $request['length'];
        }

        $query = mysqli_query($con, $sql);

        $data = array();

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            // $usergroup = $this->user_group_name($row[5]);
            $usergroup = $row['user_type'];
            $subdata[] = $row['user_name']; //name
            $subdata[] = $row['email']; //email
            $subdata[] = $usergroup; //user
            $originalDate = $row['created_at'];
            // $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $newDate = date("m-d-Y", strtotime($originalDate));
            $subdata[] = $newDate; //created

            if ($row['is_active'] == 1) {
                $status_a = '<span class="spanstatus badge badge-secondary">Active</span>';
                $s_btn = 'btn-warning';
                $s_icn = '<i class="fas fa-plane-slash"></i> Inactivate';
            } else if ($row['is_active'] == 2) {
                $status_a = '<span class="spanstatus badge badge-light">Dormant</span>';
                $s_btn = 'btn-primary';
                $s_icn = '<i class="fas fa-plane"></i> Activate';
            } else {
                $status_a = '<span class="spanstatus badge badge-danger">Inactive</span>';
                $s_btn = 'btn-primary';
                $s_icn = '<i class="fas fa-plane"></i> Activate';
            }

            $subdata[] = $status_a; // status     

            $block_icon = ' <a href="javascript:void(0)" class="btn btn-xs ' . $s_btn . ' status_link change_status" data-id="' . $row[0] . '" data-status="' . $row['is_active'] . '" item-ref="' . $row[5] . '">' . $s_icn . '</a>';

            //   $subdata[] = '<a href="' . SITE_URL . '/admin/user?edit=' . $row[0] . '" class="btn btn-xs btn-success edit_link"><i class="fas fa-edit"></i> Edit</a> <a href="' . SITE_URL . '/admin/user?delete=' . $row[0] . '" class="btn btn-xs btn-danger delete_link"><i class="fas fa-trash-alt"></i> Delete</a>' . $block_icon;
            $subdata[] = '<a href="' . SITE_URL . '/admin/user?edit=' . $row[0] . '" class="btn btn-xs btn-success edit_link"><i class="fas fa-edit"></i> Edit</a> <a href="javascript:void(0);" class="btn btn-xs btn-danger delete_link" rel="' . $row[0] . '" item-ref="' . $row[5] . '"><i class="fas fa-trash-alt"></i> Delete</a>' . $block_icon;

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

    public function get_miscellaneous_billing_info()
    {
        $con = $this->getConnection();
        $request = $_REQUEST;
        $col = array(
            0 => 'name',
            1 => 'analysis_invoicing_description',
            2 => 'analysis_client_price',
            3 => 'cus',
            4 => 'created_at'
        );  //create column like table in database
        //Search

        $sql = "SELECT t1.name,t1.analysis_invoicing_description,t1.analysis_client_price, t2.user_name as cus,t1.created_at,t1.miscellaneous_billing_id FROM miscellaneous_billing t1 INNER JOIN users t2 ON t1.client_account_ids=t2.user_id";

        if (!empty($request['search']['value'])) {

            $sql .= " AND cus Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR analysis_invoicing_description Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR analysis_client_price Like '%" . $request['search']['value'] . "%' ";

            $sql .= " OR created_at Like '%" . $request['search']['value'] . "%' )";
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);
        $totalFilter = $totalData;

        //Order
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);

        $data = array();

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            $subdata[] = $row[0]; //name
            $subdata[] = $row[1]; //email
            $subdata[] = $row[2];
            $subdata[] = $row[3];
            $originalDate = $row[4];
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $subdata[] = $newDate; //created
            $subdata[] = '<a href="' . SITE_URL . '/admin/miscellaneous_billing?edit=' . $row[5] . '" class="btn btn-xs btn-success edit_link"><i class="fas fa-edit"></i> Edit</a> <a href="' . SITE_URL . '/admin/miscellaneous_billing?delete=' . $row[5] . '" class="btn btn-xs btn-danger delete_link"><i class="fas fa-trash-alt"></i> Delete</a>';
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

    public function get_analyses_info()
    {
        $con = $this->getConnection();
        $request = $_REQUEST;
        $col = array(
            0 => 't1.analysis_name',
            1 => 't2.category_name',
            2 => 't1.analysis_number',
            3 => 't1.analysis_price',
            4 => 'time_in_minutes',
            //            5 => 't1.created_at',
            //            6 => 't1.is_active',
            5 => 't1.analysis_id'
        );  //create column like table in database
        //Search
        $search_str = trim($request['search']['value']);
        $sql = "SELECT t1.analysis_id, t1.analysis_name, t2.category_name, t1.analysis_number, t1.analysis_price, t1.time_to_analyze, t1.time_unit, t1.time_in_minutes, t1.created_at, t1.is_active FROM analyses t1 INNER JOIN analyses_category t2 ON (t1.category_ids = t2.category_id) WHERE t1.is_deleted != '1' AND t2.is_deleted != '1'";
        if (!empty($search_str)) {
            // $sql .= " AND (t1.analysis_id Like '%" . $search_str . "%' ";
            $sql .= " AND (t1.analysis_name Like '%" . $search_str . "%' ";
            $sql .= " OR t1.analysis_number Like '%" . $search_str . "%' ";
            if (is_numeric($search_str)) {
                $sql .= " OR t1.time_to_analyze = '" . $search_str . "' ";
                $sql .= " OR t1.analysis_price = '" . $search_str . "' ";
            }
            $sql .= " OR t2.category_name Like '%" . $search_str . "%' )";
        }

        // echo $sql;

        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);
        $totalFilter = $totalData;

        //Order
        // $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
        //         $request['start'] . "  ," . $request['length'] . "  ";
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];
        if ($request['length'] != -1) {
            $sql .= " LIMIT " . $request['start'] . " ," . $request['length'];
        }

        $query = mysqli_query($con, $sql);

        $data = array();

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            $subdata[] = $row['analysis_name'];
            $subdata[] = $row['category_name'];
            $subdata[] = $row['analysis_number'];
            $subdata[] = "$" . $row['analysis_price'];
            // $subdata[] = $row['time_to_analyze'];
            $subdata[] = $row['time_to_analyze'] . ' ' . $row['time_unit'];
            // $originalDate = $row[6];
            // $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            // $subdata[] = $newDate;
            $block_icon = '';
            /*   if ($row['is_active'] == 1) {
              $status_a = '<span class="spanstatus badge badge-primary">Active</span>';
              $s_btn = 'btn-warning';
              $s_icn = '<i class="fas fa-plane-slash"></i> Inactivate';
              } else {
              $status_a = '<span class="spanstatus badge badge-danger">Inactive</span>';
              $s_btn = 'btn-primary';
              $s_icn = '<i class="fas fa-plane"></i> Activate';
              }

              $subdata[] = $status_a; */

            // $block_icon = ' <a href="javascript:void(0)" class="btn btn-xs ' . $s_btn . ' status_link change_status" data-id="' . $row['analysis_id'] . '" data-status="' . $row['is_active'] . '">' . $s_icn . '</a>';
            //  $subdata[] = '<a href="' . SITE_URL . '/admin/analyses?edit=' . $row['analysis_id'] . '" class="btn btn-xs btn-success edit_link"><i class="fas fa-edit"></i> Edit</a> <a href="' . SITE_URL . '/admin/analyses?delete=' . $row[0] . '" class="btn btn-xs btn-danger delete_link"><i class="fas fa-trash-alt"></i> Delete</a>' . $block_icon;
            $subdata[] = '<a href="' . SITE_URL . '/admin/analyses?edit=' . $row['analysis_id'] . '" class="btn btn-xs btn-success edit_link"><i class="fas fa-edit"></i> Edit</a> <a href="javascript:void(0);" class="btn btn-xs btn-danger delete_link" rel="' . $row[0] . '"><i class="fas fa-trash-alt"></i> Delete</a>' . $block_icon;

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

    public function get_analyses_category_info()
    {
        $con = $this->getConnection();

        $request = $_REQUEST;
        $col = array(
            0 => 'category_name',
            1 => 'is_active',
            2 => 'category_id'
        );  //create column like table in database
        //Search
        $search_str = $request['search']['value'];
        $sql = "SELECT category_id, category_name, is_active FROM analyses_category WHERE is_deleted != '1'";
        if (!empty($search_str)) {
            $sql .= " AND (category_id Like '%" . $search_str . "%' ";
            if (strtolower($search_str) == 'active') {
                $sql .= " OR is_active = '1' ";
            } else if (strtolower($search_str) == 'inactive') {
                $sql .= " OR is_active = '0' ";
            }
            $sql .= " OR category_name Like '%" . $search_str . "%' )";
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);
        $totalFilter = $totalData;
        //Order
        // $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
        //         $request['start'] . "  ," . $request['length'] . "  ";

        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];
        if ($request['length'] != -1) {
            $sql .= " LIMIT " . $request['start'] . " ," . $request['length'];
        }

        $query = mysqli_query($con, $sql);

        $data = array();

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            $subdata[] = $row[1]; //category
            if ($row['is_active'] == 1) {
                $status_a = '<span class="spanstatus badge badge-secondary">Active</span>';
                $s_btn = 'btn-warning';
                $s_icn = '<i class="fas fa-plane-slash"></i> Inactivate';
            } else {
                $status_a = '<span class="spanstatus badge badge-danger">Inactive</span>';
                $s_btn = 'btn-primary';
                $s_icn = '<i class="fas fa-plane"></i> Activate';
            }

            $subdata[] = $status_a; // status 

            $block_icon = ' <a href="javascript:void(0)" class="btn btn-xs ' . $s_btn . ' status_link change_status" data-id="' . $row[0] . '" data-status="' . $row['is_active'] . '">' . $s_icn . '</a>';

            // $subdata[] = '<a href="' . SITE_URL . '/admin/analyses_category?edit=' . $row[0] . '" class="btn btn-xs btn-success edit_link"><i class="fas fa-edit"></i> Edit</a> <a href="' . SITE_URL . '/admin/analyses_category?delete=' . $row[0] . '" class="btn btn-xs btn-danger delete_link"><i class="fas fa-trash-alt"></i> Delete</a>' . $block_icon;
            $subdata[] = '<a href="' . SITE_URL . '/admin/analyses_category?edit=' . $row[0] . '" class="btn btn-xs btn-success edit_link"><i class="fas fa-edit"></i> Edit</a> <a href="javascript:void(0);" class="btn btn-xs btn-danger delete_link" rel="' . $row[0] . '"><i class="fas fa-trash-alt"></i> Delete</a>' . $block_icon;

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


    public function get_customer_info()
    {

        $con = $this->getConnection();

        $request = $_REQUEST;
        $col = array(
            0 => 'user_name',
            1 => 'email',
            2 => 'created_at',
            3 => 'is_active',
            4 => 'user_id'
        );  //create column like table in database
        //Search

        $search_str = trim($request['search']['value']);

        $sql = "SELECT user_id, user_name, email, created_at, is_active, user_type_ids FROM `users` WHERE user_type_ids = 5 AND is_deleted!='1'";

        if (!empty($search_str)) {
            $sql .= " AND (user_id Like '%" . $search_str . "%' ";
            $sql .= " OR user_name Like '%" . $search_str . "%' ";
            $sql .= " OR email Like '%" . $search_str . "%' ";

            if (strtolower($search_str) == 'active') {
                $sql .= " OR is_active = '1' ";
            } else if (strtolower($search_str) == 'inactive') {
                $sql .= " OR is_active = '0' ";
            } else if (strtolower($search_str) == 'dormant') {
                $sql .= " OR is_active = '2' ";
            }
            //  $sql .= " OR created_at Like '%" . $search_str . "%' )";            
            if ($this->isValidDate($search_str)) {
                $date = DateTime::createFromFormat('m-d-Y', $search_str);
                $c_date = $date->format('Y-m-d');
                $sql .= " OR date(created_at) = '$c_date' )";
            } else {
                $sql .= " )";
            }
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);
        $totalFilter = $totalData;

        //Order
        // $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
        //         $request['start'] . "  ," . $request['length'] . "  ";

        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];
        if ($request['length'] != -1) {
            $sql .= " LIMIT " . $request['start'] . " ," . $request['length'];
        }

        $query = mysqli_query($con, $sql);

        $data = array();

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            $subdata[] = $row[1]; //name
            $subdata[] = $row[2]; //email

            $originalDate = $row[3];
            // $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $newDate = date("m-d-Y", strtotime($originalDate));

            $subdata[] = $newDate; //created


            if ($row['is_active'] == 1) {
                $status_a = '<span class="spanstatus badge badge-secondary">Active</span>';
                $s_btn = 'btn-warning';
                $s_icn = '<i class="fas fa-plane-slash"></i> Inactivate';
            } else if ($row['is_active'] == 2) {
                $status_a = '<span class="spanstatus badge badge-light">Dormant</span>';
                $s_btn = 'btn-primary';
                $s_icn = '<i class="fas fa-plane"></i> Activate';
            } else {
                $status_a = '<span class="spanstatus badge badge-danger">Inactive</span>';
                $s_btn = 'btn-primary';
                $s_icn = '<i class="fas fa-plane"></i> Activate';
            }

            $subdata[] = $status_a; // status     

            $block_icon = ' <a href="javascript:void(0)" class="btn btn-xs ' . $s_btn . ' status_link change_status" data-id="' . $row[0] . '" data-status="' . $row['is_active'] . '">' . $s_icn . '</a>';

            $subdata[] = '<a href="' . SITE_URL . '/admin/customer?edit=' . $row[0] . '" class="btn btn-xs btn-success edit_link"><i class="fas fa-edit"></i> Edit</a> <a href="javascript:void(0);" class="btn btn-xs btn-danger delete_link" rel="' . $row[0] . '" item-ref="' . $row['user_type_ids'] . '"><i class="fas fa-trash-alt"></i> Delete</a> <a href="' . SITE_URL . '/analyses_rates?edit=' . $row[0] . '" class="btn btn-xs btn-info edit_link"><i class="fas fa-search-dollar"></i> Analyses Rates</a> ' . $block_icon;

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

    /*

      public function get_all_worksheet(){
      $con=$this->getConnection();
      $request=$_REQUEST;
      $col =array(
      0   =>  'recieveddate',
      1   =>  'instituion',
      2   =>  'accession',
      3   =>  'patient_name',
      4   =>  'mrn',
      5   =>  'customer',
      6   =>  'description',
      7   =>  'assignee',
      8   =>  'status',
      9   =>  'action',
      );  //create column like table in database

      $sql ="SELECT * FROM Clario";
      $query=mysqli_query($con,$sql);

      $totalData=mysqli_num_rows($query);

      $totalFilter=$totalData;

      //Search
      $sql ="SELECT * FROM Clario WHERE 1=1";
      if(!empty($request['search']['value'])){
      $sql.=" AND (id Like '".$request['search']['value']."%' ";
      $sql.=" OR patient_name Like '".$request['search']['value']."%' ";
      $sql.=" OR site Like '".$request['search']['value']."%' ";
      }
      $query=mysqli_query($con,$sql);
      $totalData=mysqli_num_rows($query);

      //Order
      $sql.=" ORDER BY ".$col[$request['order'][0]['column']]."   ".$request['order'][0]['dir']."  LIMIT ".
      $request['start']."  ,".$request['length']."  ";

      $query=mysqli_query($con,$sql);

      $data=array();
      print_r($sql);die;
      while($row=mysqli_fetch_array($query)){
      $subdata=array();
      $subdata[]=$row[1]; //name
      $subdata[]=$row[2]; //email
      $subdata[]=$row[3]; //created
      $subdata[]='<a href="'.SITE_URL.'/admin/user?edit='.$row[0].'" class="edit_link"><i class="fa fa-pencil-square" aria-hidden="true"></i></a><a href="'.SITE_URL.'/admin/user?delete='.$row[0].'" class="delete_link"><i class="fa fa-trash" aria-hidden="true"></i></a>';

      $data[]=$subdata;
      }

      $json_data=array(
      "draw"				=>  intval($request['draw']),
      "recordsTotal"		=>  intval($totalData),
      "recordsFiltered"	=>  intval($totalFilter),
      "data"				=>  $data
      );

      echo json_encode($json_data);die;
      }
     */

    // worksheets

    public function get_open_worksheets_info()
    {

        $con = $this->getConnection();
        $request = $_REQUEST;
        $col = array(
            0 => 'created',
            1 => 'site',
            2 => 'accession',
            3 => 'patient_name',
            4 => 'mrn',
            5 => 'webhook_customer',
            6 => 'webhook_description'
        );  //create column like table in database

        $sql = "SELECT id, created, accession, patient_name, mrn, tat,  webhook_customer, webhook_description FROM `Clario` WHERE assignee = 0";
        $query = mysqli_query($con, $sql);

        $totalData = mysqli_num_rows($query);

        ///  $totalFilter = $totalData;
        //Search
        $sql = "SELECT id, created, accession, patient_name, mrn, tat, webhook_customer, webhook_description FROM `Clario` WHERE assignee = 0";
        if (!empty($request['search']['value'])) {
            $sql .= " AND (id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR created Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR accession Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR webhook_customer Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR webhook_description Like '%" . $request['search']['value'] . "%' )";
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);

        $totalFilter = $totalData;

        //Order
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);

        $data = array();

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();

            $originalDate = $row[1];
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $subdata[] = $newDate;
            $subdata[] = $row[2]; //created
            $subdata[] = $row[3]; //created
            $subdata[] = $row[4]; //created
            $subdata[] = $row[5]; //created
            $subdata[] = $row[6]; //created
            $subdata[] = $row[7]; //created
            //create event on click in button edit in cell datatable for display modal dialog $row[0] is id in table on database
            $subdata[] = '<a href="' . SITE_URL . '/adminV2/dicom_details?edit=' . $row[0] . '" class="btn btn-xs btn-success edit_link"><i class="fas fa-edit"></i> Edit</a>
          <a href="' . SITE_URL . '/adminV2/dicom_details?delete=' . $row[0] . '" class="btn btn-xs btn-danger delete_link"><i class="fas fa-trash-alt"></i> Delete</a>';

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

    public function get_all_worksheet_current_month_info()
    {

        $con = $this->getConnection();

        $request = $_REQUEST;
        $col = array(
            0 => 'created',
            1 => 'site',
            2 => 'accession',
            3 => 'patient_name',
            4 => 'mrn',
            5 => 'webhook_customer',
            6 => 'webhook_description',
            7 => 'name',
            8 => 'status',
            9 => 'review_user_id',
        );  //create column like table in database

        $sql = "SELECT Clario.id, Clario.created, Clario.accession ,Clario.patient_name ,Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer FROM Clario LEFT JOIN users ON Clario.assignee = users.id WHERE MONTH(Clario.created) = MONTH(CURRENT_DATE()) AND YEAR(Clario.created) = YEAR(CURRENT_DATE()) ORDER BY Clario.id DESC";
        $query = mysqli_query($con, $sql);

        $totalData = mysqli_num_rows($query);

        $totalFilter = $totalData;

        //Search
        $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer, cust.tat  as dtat FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id WHERE MONTH(Clario.created) = MONTH(CURRENT_DATE()) AND YEAR(Clario.created) = YEAR(CURRENT_DATE())";

        if (isset($_POST["is_assignee"]) && !empty($_POST['is_assignee'])) {
            $sql .= " AND assignee = '" . $_POST["is_assignee"] . "' ";
        }

        if (isset($_POST["is_day"]) && !empty($_POST['is_day'])) {
            $sql .= " AND TIMESTAMPDIFF(DAY,Clario.created,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        /* if (isset($_POST["status_select"]) && !empty($_POST['status_select'])) { 
          $sql .= " AND Clario.status = '" . $_POST["status_select"] . "' ";
          } */

        if (isset($_POST["status_select"]) && !empty($_POST['status_select'])) {
            if ($_POST['status_select'] != 'Not Assigned') {
                $sql .= " AND Clario.status = '" . $_POST["status_select"] . "' ";
            }
            if ($_POST['status_select'] == 'Not Assigned') {
                $sql .= " AND Clario.status = '' ";
            }
        }

        if (isset($_POST['is_second']) && !empty($_POST['is_second'])) {
            if ($_POST['is_second'] == 1) {
                $sql .= " AND review_user_id !='' ";
                if (isset($_POST['asignee_second']) && !empty($_POST['asignee_second'])) {
                    $reviewer_id = $_POST['asignee_second'];
                    $sql .= " AND review_user_id ='$reviewer_id'";
                }
            } else {
                $sql .= " AND review_user_id =''";
            }
        }

        if (!empty($request['search']['value'])) {
            $sql .= " AND (Clario.id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.created Like'%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.accession Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR cust.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_customer Like '%" . $request['search']['value'] . "%' ";

            $sql .= " OR users.name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_description Like '%" . $request['search']['value'] . "%' ";
            if ($request['search']['value'] != 'Not Assigned') {
                $sql .= " OR Clario.status Like '%" . $request['search']['value'] . "%' )";
            }
            if ($request['search']['value'] == 'Not Assigned') {
                $sql .= " OR Clario.status = '')";
            }
        }

        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);

        //Order
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);

        $data = array();

        $i = 0;

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();

            $originalDate = $row[1];
            $review_name = $this->Admindb->get_name_by_id($row[10]);
            $assign_customer = $this->Admindb->get_name_by_id($row[11]);
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $subdata[] = $newDate;
            $subdata[] = $row[2]; //created
            $subdata[] = $row[3]; //created
            $subdata[] = $row[4]; //created
            if (!empty($row[5])) {
                $row[5] = $row[5] . " hrs";
            } else {
                $row[5] = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
            }
            $subdata[] = $row[5];
            $subdata[] = $row[6]; //created
            $subdata[] = $row[7] ?: "Not Assigned";
            $subdata[] = (empty($assign_customer)) ? "" : $assign_customer;
            $subdata[] = (empty($review_name)) ? "Not Reviewed" : $review_name;
            $subdata[] = $row[8];

            $statusCheck = $row[9];
            if ($row[9] == 'Completed') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-success status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Completed</span>';
                $bgcolor = 'bg-success';
            }
            if ($row[9] == '') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-danger status_chk" rel="' . str_replace(' ', '_', strtolower('Not 
Assigned')) . '">Not Assigned</span>';
                $bgcolor = 'bg-danger';
            }
            if ($row[9] == 'In progress') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-info status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">In progress</span>';
                $bgcolor = 'bg-info';
            }
            if ($row[9] == 'Under review') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Under review</span>';
                $bgcolor = 'bg-warning';
            }
            if ($row[9] == 'Cancelled' || $row[9] == 'CancelledAcc' || $row[9] == 'CancelledCust') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-default status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Cancelled</span>';
                $bgcolor = 'bg-default';
            }
            if ($row[9] == 'On hold') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">On hold</span>';
                $bgcolor = 'bg-warning';
            }


            $subdata[] = $row[9];

            $subdata[] = '<a href="' . SITE_URL . '/admin/dicom_details?edit=' . $row[0] . '" class="btn btn-primary btn-xs">View</a>';

            $subdata[] = '<script>$("#status_val_' . $i . '").closest("tr").addClass("' . $bgcolor . '"); $("#status_val_' . $i . '").closest("td").attr("display","none");</script>';

            $data[] = $subdata;

            $i++;
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

    public function get_all_worksheet_info()
    {

        $con = $this->getConnection();

        $request = $_REQUEST;
        $col = array(
            0 => 'created',
            1 => 'site',
            2 => 'accession',
            3 => 'patient_name',
            4 => 'mrn',
            5 => 'webhook_customer',
            6 => 'webhook_description',
            7 => 'name',
            8 => 'status',
            9 => 'review_user_id',
        );  //create column like table in database
        //echo $col[$request['order'][0]['column']];exit;
        $sql = "SELECT Clario.id, Clario.created, Clario.accession ,Clario.patient_name ,Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer FROM Clario LEFT JOIN users on Clario.assignee = users.id order by Clario.id DESC";

        $query = mysqli_query($con, $sql);

        $totalData = mysqli_num_rows($query);

        $totalFilter = $totalData;

        //Search
        $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer,cust.tat as dtat, Clario.last_modified FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id  WHERE 1=1 ";

        if (isset($_POST["is_assignee"]) && !empty($_POST['is_assignee'])) {
            $sql .= " AND assignee = '" . $_POST["is_assignee"] . "' ";
        }

        if (isset($_POST["is_day"]) && !empty($_POST['is_day'])) {
            $sql .= " AND TIMESTAMPDIFF(DAY,Clario.created,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        /*  if (isset($_POST["status_select"]) && !empty($_POST['status_select'])) { 
          $sql .= " AND Clario.status = '" . $_POST["status_select"] . "' ";
          } */

        if (isset($_POST["status_select"]) && !empty($_POST['status_select'])) {
            if ($_POST['status_select'] != 'Not Assigned') {
                $sql .= " AND Clario.status = '" . $_POST["status_select"] . "' ";
            }
            if ($_POST['status_select'] == 'Not Assigned') {
                $sql .= " AND Clario.status = '' ";
            }
        }


        if (isset($_POST['is_second']) && !empty($_POST['is_second'])) {
            if ($_POST['is_second'] == 1) {
                $sql .= " AND review_user_id !='' ";
                if (isset($_POST['asignee_second']) && !empty($_POST['asignee_second'])) {
                    $reviewer_id = $_POST['asignee_second'];
                    $sql .= " AND review_user_id ='$reviewer_id'";
                }
            } else {
                $sql .= " AND review_user_id =''";
            }
        }


        if (!empty($request['search']['value'])) {

            /* $sec_review = "SELECT id,name FROM users WHERE user_type_ids = '5' AND name Like '%" . $request['search']['value'] . "%'";

              $secquery = mysqli_query($con, $sec_review);
              $secid = [];
              while ($secrow = mysqli_fetch_array($secquery)) {
              $secid[] = $secrow['id'];
              } */


            $sql .= " AND (Clario.id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.created Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.accession Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR cust.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_customer Like '%" . $request['search']['value'] . "%' ";
            /* if(!empty($secid)){
              $secondids = implode(",",$secid);
              $sql .= " OR Clario.review_user_id IN '(" . $secondids . ")' ";
              } */

            $sql .= " OR users.name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_description Like '%" . $request['search']['value'] . "%' ";
            if ($request['search']['value'] != 'Not Assigned') {
                $sql .= " OR Clario.status Like '%" . $request['search']['value'] . "%' )";
            }
            if ($request['search']['value'] == 'Not Assigned') {
                $sql .= " OR Clario.status = '')";
            }
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);

        //Order
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);

        $data = array();

        $i = 0;

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();

            $originalDate = $row[1];
            $review_name = $this->Admindb->get_name_by_id($row[10]);
            $assign_customer = $this->Admindb->get_name_by_id($row[11]);
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $subdata[] = $newDate;
            $subdata[] = $row[2]; //created
            $subdata[] = $row[3]; //created
            $subdata[] = $row[4]; //created
            if (!empty($row[5])) {
                $row[5] = $row[5] . " hrs";
            } else {
                $row[5] = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
            }
            $subdata[] = $row[5];
            $subdata[] = $row[6]; //created
            $subdata[] = $row[7] ?: "Not Assigned";
            $subdata[] = (empty($assign_customer)) ? "" : $assign_customer;
            $subdata[] = (empty($review_name)) ? "Not Reviewed" : $review_name;
            $subdata[] = $row[8];

            $statusCheck = $row[9];
            if ($row[9] == 'Completed') {
                $manilDate = $row[13];
                $newcomDate = date("m-d-Y h:i:s A", strtotime($manilDate));
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-success status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Completed<br><span class="btn btn-light">' . $newcomDate . '</span></span>';
                $bgcolor = 'bg-success';
            }
            if ($row[9] == '') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-danger status_chk" rel="' . str_replace(' ', '_', strtolower('Not 
Assigned')) . '">Not Assigned</span>';
                $bgcolor = 'bg-danger';
            }
            if ($row[9] == 'In progress') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-info status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">In progress</span>';
                $bgcolor = 'bg-info';
            }
            if ($row[9] == 'Under review') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Under review</span>';
                $bgcolor = 'bg-warning';
            }
            //            if($row[9] == 'Cancelled'){ $row[9] = '<span class="btn btn-xs btn-danger">Cancelled</span>';}
            if ($row[9] == 'Cancelled' || $row[9] == 'CancelledAcc' || $row[9] == 'CancelledCust') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-default status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Cancelled</span>';
                $bgcolor = 'bg-default';
            }
            if ($row[9] == 'On hold') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">On hold</span>';
                $bgcolor = 'bg-warning';
            }


            $subdata[] = $row[9];

            $subdata[] = '<a href="' . SITE_URL . '/admin/dicom_details?edit=' . $row[0] . '" class="btn btn-primary btn-xs">View</a>';

            $subdata[] = '<script>$("#status_val_' . $i . '").closest("tr").addClass("' . $bgcolor . '"); $("#status_val_' . $i . '").closest("td").attr("display","none");</script>';

            //$subdata[]	=	$row[10]
            //print_r($row[9]);
            $data[] = $subdata;

            $i++;
        }
        // print_r($totalData);
        //  die();
        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalData),
            //"recordsFiltered" => intval($totalFilter),
            "data" => $data
        );

        echo json_encode($json_data);

        die;
    }

    public function get_all_worksheet_info_tatmissing()
    {

        $con = $this->getConnection();

        $request = $_REQUEST;
        $col = array(
            0 => 'created',
            1 => 'site',
            2 => 'accession',
            3 => 'patient_name',
            4 => 'mrn',
            5 => 'webhook_customer',
            6 => 'webhook_description',
            7 => 'name',
            8 => 'status',
            9 => 'review_user_id',
        );
        $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer,cust.tat as dtat, Clario.last_modified FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id  WHERE 1=1 AND Clario.tat IS NULL AND cust.tat IS NULL   ";

        if (!empty($request['search']['value'])) {

            /* $sec_review = "SELECT id,name FROM users WHERE user_type_ids = '5' AND name Like '%" . $request['search']['value'] . "%'";

              $secquery = mysqli_query($con, $sec_review);
              $secid = [];
              while ($secrow = mysqli_fetch_array($secquery)) {
              $secid[] = $secrow['id'];
              } */


            $sql .= " AND (Clario.id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.created Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.accession Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR cust.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_customer Like '%" . $request['search']['value'] . "%' ";
            /* if(!empty($secid)){
              $secondids = implode(",",$secid);
              $sql .= " OR Clario.review_user_id IN '(" . $secondids . ")' ";
              } */

            $sql .= " OR users.name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_description Like '%" . $request['search']['value'] . "%' ";
            if ($request['search']['value'] != 'Not Assigned') {
                $sql .= " OR Clario.status Like '%" . $request['search']['value'] . "%' )";
            }
            if ($request['search']['value'] == 'Not Assigned') {
                $sql .= " OR Clario.status = '')";
            }
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);

        //Order
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);

        $data = array();

        $i = 0;

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            if (empty($row[5]) and empty($row[12])) {
                // $row[5] = $row[5] . " hrs";
                //} else {
                // $row[5] = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
                //}
                $originalDate = $row[1];
                $review_name = $this->Admindb->get_name_by_id($row[10]);
                $assign_customer = $this->Admindb->get_name_by_id($row[11]);
                $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
                $subdata[] = $newDate;
                $subdata[] = $row[2]; //created
                $subdata[] = $row[3]; //created
                $subdata[] = $row[4]; //created
                if (!empty($row[5])) {
                    $row[5] = $row[5] . " hrs";
                } else {
                    $row[5] = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
                }
                $subdata[] = $row[5];
                $subdata[] = $row[6]; //created
                $subdata[] = $row[7] ?: "Not Assigned";
                $subdata[] = (empty($assign_customer)) ? "" : $assign_customer;
                $subdata[] = (empty($review_name)) ? "Not Reviewed" : $review_name;
                $subdata[] = $row[8];

                $statusCheck = $row[9];
                if ($row[9] == 'Completed') {
                    $manilDate = $row[13];
                    $newcomDate = date("m-d-Y h:i:s A", strtotime($manilDate));
                    $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-success status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Completed<br><span class="btn btn-light">' . $newcomDate . '</span></span>';
                    $bgcolor = 'bg-success';
                }
                if ($row[9] == '') {
                    $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-danger status_chk" rel="' . str_replace(' ', '_', strtolower('Not 
Assigned')) . '">Not Assigned</span>';
                    $bgcolor = 'bg-danger';
                }
                if ($row[9] == 'In progress') {
                    $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-info status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">In progress</span>';
                    $bgcolor = 'bg-info';
                }
                if ($row[9] == 'Under review') {
                    $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Under review</span>';
                    $bgcolor = 'bg-warning';
                }
                //            if($row[9] == 'Cancelled'){ $row[9] = '<span class="btn btn-xs btn-danger">Cancelled</span>';}
                if ($row[9] == 'Cancelled' || $row[9] == 'CancelledAcc' || $row[9] == 'CancelledCust') {
                    $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-default status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Cancelled</span>';
                    $bgcolor = 'bg-default';
                }
                if ($row[9] == 'On hold') {
                    $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">On hold</span>';
                    $bgcolor = 'bg-warning';
                }


                $subdata[] = $row[9];

                $subdata[] = '<a href="' . SITE_URL . '/admin/dicom_details?edit=' . $row[0] . '" class="btn btn-primary btn-xs">View</a>';

                $subdata[] = '<script>$("#status_val_' . $i . '").closest("tr").addClass("' . $bgcolor . '"); $("#status_val_' . $i . '").closest("td").attr("display","none");</script>';

                //$subdata[]    =   $row[10]
                //print_r($row[9]);
                $data[] = $subdata;

                $i++;
            }
        }
        // print_r($totalData);
        //  die();
        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalData),
            //"recordsFiltered" => intval($totalFilter),
            "data" => $data
        );

        echo json_encode($json_data);

        die;
    }

    public function get_all_worksheet_info_new()
    {

        $con = $this->getConnection();

        $request = $_REQUEST;
        $col = array(
            0 => 'created',
            1 => 'site',
            2 => 'accession',
            3 => 'patient_name',
            4 => 'mrn',
            5 => 'webhook_customer',
            6 => 'webhook_description',
            7 => 'name',
            8 => 'status',
            9 => 'review_user_id',
        );  //create column like table in database
        //echo $col[$request['order'][0]['column']];exit;
        $sql = "SELECT Clario.id, Clario.created, Clario.accession ,Clario.patient_name ,Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer FROM Clario LEFT JOIN users on Clario.assignee = users.id order by Clario.id DESC";

        $query = mysqli_query($con, $sql);

        $totalData = mysqli_num_rows($query);

        $totalFilter = $totalData;

        //Search
        $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer,cust.tat as dtat, Clario.last_modified FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id  WHERE 1=1 ";

        if (isset($_POST["is_assignee"]) && !empty($_POST['is_assignee'])) {
            $sql .= " AND assignee = '" . $_POST["is_assignee"] . "' ";
        }

        if (isset($_POST["is_day"]) && !empty($_POST['is_day'])) {
            $sql .= " AND TIMESTAMPDIFF(DAY,Clario.created,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        /*  if (isset($_POST["status_select"]) && !empty($_POST['status_select'])) { 
          $sql .= " AND Clario.status = '" . $_POST["status_select"] . "' ";
          } */

        if (isset($_POST["status_select"]) && !empty($_POST['status_select'])) {
            if ($_POST['status_select'] != 'Not Assigned') {
                $sql .= " AND Clario.status = '" . $_POST["status_select"] . "' ";
            }
            if ($_POST['status_select'] == 'Not Assigned') {
                $sql .= " AND Clario.status = '' ";
            }
        }


        if (isset($_POST['is_second']) && !empty($_POST['is_second'])) {
            if ($_POST['is_second'] == 1) {
                $sql .= " AND review_user_id !='' ";
                if (isset($_POST['asignee_second']) && !empty($_POST['asignee_second'])) {
                    $reviewer_id = $_POST['asignee_second'];
                    $sql .= " AND review_user_id ='$reviewer_id'";
                }
            } else {
                $sql .= " AND review_user_id =''";
            }
        }


        if (!empty($request['search']['value'])) {

            /* $sec_review = "SELECT id,name FROM users WHERE user_type_ids = '5' AND name Like '%" . $request['search']['value'] . "%'";

              $secquery = mysqli_query($con, $sec_review);
              $secid = [];
              while ($secrow = mysqli_fetch_array($secquery)) {
              $secid[] = $secrow['id'];
              } */


            $sql .= " AND (Clario.id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.created Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.accession Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR cust.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_customer Like '%" . $request['search']['value'] . "%' ";
            /* if(!empty($secid)){
              $secondids = implode(",",$secid);
              $sql .= " OR Clario.review_user_id IN '(" . $secondids . ")' ";
              } */

            $sql .= " OR users.name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_description Like '%" . $request['search']['value'] . "%' ";
            if ($request['search']['value'] != 'Not Assigned') {
                $sql .= " OR Clario.status Like '%" . $request['search']['value'] . "%' )";
            }
            if ($request['search']['value'] == 'Not Assigned') {
                $sql .= " OR Clario.status = '')";
            }
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);

        //Order
        //    $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
        //        $request['start'] . "  ," . $request['length'] . "  ";

        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);
        $queryone = mysqli_query($con, $sql);
        $querytwo = mysqli_query($con, $sql);
        $queryfour = mysqli_query($con, $sql);
        $queryfive = mysqli_query($con, $sql);
        $querythree = mysqli_query($con, $sql);

        $data = array();

        $i = 0;

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            if ($row[9] == '') {
                $originalDate = $row[1];
                $review_name = $this->Admindb->get_name_by_id($row[10]);
                $assign_customer = $this->Admindb->get_name_by_id($row[11]);
                $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
                $subdata[] = $newDate;
                $subdata[] = $row[2]; //created
                $subdata[] = $row[3]; //created
                $subdata[] = $row[4]; //created
                $subdata[] = $row[8];
                $subdata[] = (empty($assign_customer)) ? "" : $assign_customer;
                /* TAT */
                if (!empty($row[5])) {
                    $row[5] = $row[5] . " hrs";
                    $tat = $row[5];
                } else {
                    $row[5] = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
                    $tat = (!empty($row[12])) ? $row[12] : 0;
                }
                /* TAT COMPLETE */
                $subdata[] = $tat;
                //  $subdata[] = $row[6]; //created

                $newDatetest = date("d/m/Y h:i:s A", strtotime($originalDate));
                $receivedtimestamp = strtotime($originalDate);
                // date_default_timezone_set('Asia/Calcutta');
                date_default_timezone_set('America/New_York');
                $current_timestamp = time();
                $current_date = date("Y-m-d H:i:s", $current_timestamp);

                $now_tat = "+" . $tat . " hours";
                $nowDate = date("m-d-Y h:i:s A", $current_timestamp);
                $received_latest = date("Y-m-d H:i:s", strtotime($now_tat, strtotime($originalDate)));
                $hourdiff = (strtotime($received_latest) - strtotime($current_date)) / 3600;
                $currentnewtimestamp = strtotime($nowDate);
                $tattimestamp = $tat * 60 * 60;
                $finaltime = $receivedtimestamp + $tattimestamp;
                $final = $finaltime - $current_timestamp;
                //$final = $finaltime - $currentnewtimestamp;
                $last = $final / 3600;
                $last = round($last, 2);
                $whole = floor($hourdiff);      // 1
                $fraction = $hourdiff - $whole;
                // $fraction = strval($fraction);
                //$fraction = substr($fraction, 0, 2);
                $left_time = $whole . " HOUR " . $fraction . " MINUTES";
                /*     if (strtotime($received_latest) >= strtotime($current_date)) {
                  $subdata[] = $left_time;
                  // $subdata[] = $nowDate;
                  } else {
                  $subdata[] = "DUE";
                  }

                 */
                if ($last > 0) {
                    $subdata[] = $last;
                } else {
                    $subdata[] = "DUE";
                }
                $subdata[] = $row[7] ?: "Not Assigned";
                $subdata[] = (empty($review_name)) ? "Not Reviewed" : $review_name;

                $statusCheck = $row[9];

                /*    if ($row[9] == 'Under review') {
                  $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower
                  ($row[9])) . '">Under review</span>';
                  $bgcolor = 'bg-warning';
                  }

                  if ($row[9] == 'Cancelled' || $row[9] == 'CancelledAcc' || $row[9] == 'CancelledCust') {
                  $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-default status_chk" rel="' . str_replace(' ', '_', strtolower($row
                  [9])) . '">Cancelled</span>';
                  $bgcolor = 'bg-default';
                  }
                 */
                if ($row[9] == '') {
                    $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-danger status_chk" rel="' . str_replace(' ', '_', strtolower('Not 
Assigned')) . '">Not Assigned</span>';
                    $bgcolor = 'bg-danger';
                }

                $subdata[] = $row[9];

                $subdata[] = '<a href="' . SITE_URL . '/admin/dicom_details?edit=' . $row[0] . '" class="btn btn-primary btn-xs">View</a>';

                $subdata[] = '<script>$("#status_val_' . $i . '").closest("tr").addClass("' . $bgcolor . '"); $("#status_val_' . $i . '").closest("td").attr("display","none");</script>';

                //$subdata[]    =   $row[10]
                //print_r($row[9]);
                $data[] = $subdata;
                //  $dataone[] = $subdata;

                $i++;
            }
        }



        // $i = 0;

        while ($row = mysqli_fetch_array($queryone)) {
            $subdata = array();
            if ($row[9] == 'On hold') {
                $originalDate = $row[1];
                $review_name = $this->Admindb->get_name_by_id($row[10]);
                $assign_customer = $this->Admindb->get_name_by_id($row[11]);
                $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
                $subdata[] = $newDate;
                $subdata[] = $row[2]; //created
                $subdata[] = $row[3]; //created
                $subdata[] = $row[4]; //created
                $subdata[] = $row[8];
                $subdata[] = (empty($assign_customer)) ? "" : $assign_customer;
                /* TAT */
                if (!empty($row[5])) {
                    $row[5] = $row[5] . " hrs";
                    $tat = $row[5];
                } else {
                    $row[5] = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
                    $tat = (!empty($row[12])) ? $row[12] : 0;
                }
                /* TAT COMPLETE */
                $subdata[] = $tat;
                //  $subdata[] = $row[6]; //created


                $receivedtimestamp = strtotime($originalDate);
                $received_latest = date("Y-m-d H:i:s", strtotime('+3 hours', strtotime($originalDate)));
                $current_timestamp = time();

                $nowDate = date("m-d-Y h:i:s A", $current_timestamp);
                $tattimestamp = $tat * 60 * 60;
                $finaltime = $receivedtimestamp + $tattimestamp;
                $final = $finaltime - $current_timestamp;
                $last = $final / 3600;
                $last = round($last, 2);
                if ($last > 0) {
                    $subdata[] = $last;
                } else {
                    $subdata[] = "DUE";
                }
                $subdata[] = $row[7] ?: "Not Assigned";
                $subdata[] = (empty($review_name)) ? "Not Reviewed" : $review_name;

                $statusCheck = $row[9];

                if ($row[9] == 'On hold') {
                    $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">On hold</span>';
                    $bgcolor = 'bg-warning';
                }


                $subdata[] = $row[9];

                $subdata[] = '<a href="' . SITE_URL . '/admin/dicom_details?edit=' . $row[0] . '" class="btn btn-primary btn-xs">View</a>';

                $subdata[] = '<script>$("#status_val_' . $i . '").closest("tr").addClass("' . $bgcolor . '"); $("#status_val_' . $i . '").closest("td").attr("display","none");</script>';

                //$subdata[]    =   $row[10]
                //print_r($row[9]);
                $data[] = $subdata;
                //  $dataone[] = $subdata;

                $i++;
            }
        }



        while ($row = mysqli_fetch_array($querytwo)) {
            $subdata = array();
            if ($row[9] == 'In progress') {
                $originalDate = $row[1];
                $review_name = $this->Admindb->get_name_by_id($row[10]);
                $assign_customer = $this->Admindb->get_name_by_id($row[11]);
                $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
                $subdata[] = $newDate;
                $subdata[] = $row[2]; //created
                $subdata[] = $row[3]; //created
                $subdata[] = $row[4]; //created
                $subdata[] = $row[8];
                $subdata[] = (empty($assign_customer)) ? "" : $assign_customer;
                /* TAT */
                if (!empty($row[5])) {
                    $row[5] = $row[5] . " hrs";
                    $tat = $row[5];
                } else {
                    $row[5] = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
                    $tat = (!empty($row[12])) ? $row[12] : 0;
                }
                /* TAT COMPLETE */
                $subdata[] = $tat;
                //  $subdata[] = $row[6]; //created


                $receivedtimestamp = strtotime($originalDate);
                $current_timestamp = time();

                $nowDate = date("m-d-Y h:i:s A", $current_timestamp);
                $tattimestamp = $tat * 60 * 60;
                $finaltime = $receivedtimestamp + $tattimestamp;
                $final = $finaltime - $current_timestamp;
                $last = $final / 3600;
                $last = round($last, 2);
                if ($last > 0) {
                    $subdata[] = $last;
                } else {
                    $subdata[] = "DUE";
                }
                $subdata[] = $row[7] ?: "Not Assigned";
                $subdata[] = (empty($review_name)) ? "Not Reviewed" : $review_name;

                $statusCheck = $row[9];

                if ($row[9] == 'In progress') {
                    $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-info status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">In progress</span>';
                    $bgcolor = 'bg-info';
                }


                $subdata[] = $row[9];

                $subdata[] = '<a href="' . SITE_URL . '/admin/dicom_details?edit=' . $row[0] . '" class="btn btn-primary btn-xs">View</a>';

                $subdata[] = '<script>$("#status_val_' . $i . '").closest("tr").addClass("' . $bgcolor . '"); $("#status_val_' . $i . '").closest("td").attr("display","none");</script>';

                //$subdata[]    =   $row[10]
                //print_r($row[9]);
                $data[] = $subdata;
                //  $dataone[] = $subdata;

                $i++;
            }
        }



        while ($row = mysqli_fetch_array($querythree)) {
            $subdata = array();
            if ($row[9] == 'Completed') {
                $originalDate = $row[1];
                $review_name = $this->Admindb->get_name_by_id($row[10]);
                $assign_customer = $this->Admindb->get_name_by_id($row[11]);
                $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
                $subdata[] = $newDate;
                $subdata[] = $row[2]; //created
                $subdata[] = $row[3]; //created
                $subdata[] = $row[4]; //created
                $subdata[] = $row[8];
                $subdata[] = (empty($assign_customer)) ? "" : $assign_customer;
                /* TAT */
                if (!empty($row[5])) {
                    $row[5] = $row[5] . " hrs";
                    $tat = $row[5];
                } else {
                    $row[5] = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
                    $tat = (!empty($row[12])) ? $row[12] : 0;
                }
                /* TAT COMPLETE */
                $subdata[] = $row[5];
                //  $subdata[] = $row[6]; //created


                $receivedtimestamp = strtotime($originalDate);
                $current_timestamp = time();

                $nowDate = date("m-d-Y h:i:s A", $current_timestamp);
                $tattimestamp = $tat * 60 * 60;
                $finaltime = $receivedtimestamp + $tattimestamp;
                $final = $finaltime - $current_timestamp;
                $last = $final / 3600;
                $last = round($last, 2);
                if ($last > 0) {
                    $subdata[] = $last;
                } else {
                    $subdata[] = "DUE";
                }
                $subdata[] = $row[7] ?: "Not Assigned";
                $subdata[] = (empty($review_name)) ? "Not Reviewed" : $review_name;

                $statusCheck = $row[9];

                if ($row[9] == 'Completed') {
                    $manilDate = $row[13];
                    $newcomDate = date("m-d-Y h:i:s A", strtotime($manilDate));
                    $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-success status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Completed<br><span class="btn btn-light">' . $newcomDate . '</span></span>';
                    $bgcolor = 'bg-success';
                }


                $subdata[] = $row[9];

                $subdata[] = '<a href="' . SITE_URL . '/admin/dicom_details?edit=' . $row[0] . '" class="btn btn-primary btn-xs">View</a>';

                $subdata[] = '<script>$("#status_val_' . $i . '").closest("tr").addClass("' . $bgcolor . '"); $("#status_val_' . $i . '").closest("td").attr("display","none");</script>';

                //$subdata[]    =   $row[10]
                //print_r($row[9]);
                $data[] = $subdata;
                //  $dataone[] = $subdata;

                $i++;
            }
        }








        /*    while ($row = mysqli_fetch_array($querythree)) {
          $subdata = array();
          if ($row[9] == '') {
          $originalDate = $row[1];
          $review_name = $this->Admindb->get_name_by_id($row[10]);
          $assign_customer = $this->Admindb->get_name_by_id($row[11]);
          $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
          $subdata[] = $newDate;
          $subdata[] = $row[2]; //created
          $subdata[] = $row[3]; //created
          $subdata[] = $row[4]; //created
          $subdata[] = $row[8];
          $subdata[] = (empty($assign_customer)) ? "" : $assign_customer;
          /* TAT */
        /*        if (!empty($row[5])) {
          $row[5] = $row[5] . " hrs";
          $tat = $row[5];
          } else {
          $row[5] = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
          $tat = (!empty($row[12])) ? $row[12] : 0;
          }
          /* TAT COMPLETE */
        /*         $subdata[] = $row[5];
          //  $subdata[] = $row[6]; //created


          $receivedtimestamp = strtotime($originalDate);
          $current_timestamp = time();

          $nowDate = date("m-d-Y h:i:s A", $current_timestamp);
          $tattimestamp = $tat * 60 * 60;
          $finaltime = $receivedtimestamp + $tattimestamp;
          $final = $finaltime - $current_timestamp;
          $last = $final / 3600;
          $last = round($last, 2);
          if ($last > 0) {
          $subdata[] = $last;
          } else {
          $subdata[] = "DUE";
          }
          $subdata[] = $row[7] ?: "Not Assigned";
          $subdata[] = (empty($review_name)) ? "Not Reviewed" : $review_name;

          $statusCheck = $row[9];

          if ($row[9] == 'Completed') {
          $manilDate = $row[13];
          $newcomDate = date("m-d-Y h:i:s A", strtotime($manilDate));
          //  $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-success status_chk" rel="' . str_replace(' ', '_', strtolower($row
          [9])) . '">Completed<br><span class="btn btn-light">' . $newcomDate . '</span></span>';
          //$bgcolor = 'bg-success';


          $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-danger status_chk" rel="' . str_replace(' ', '_', strtolower('Not
          Assigned')) . '">Not Assigned</span>';
          $bgcolor = 'bg-danger';

          }


          $subdata[] = $row[9];

          $subdata[] = '<a href="' . SITE_URL . '/admin/dicom_details?edit=' . $row[0] . '" class="btn btn-primary btn-xs">View</a>';

          $subdata[] = '<script>$("#status_val_' . $i . '").closest("tr").addClass("' . $bgcolor . '"); $("#status_val_' . $i . '").closest("td").attr("display","none");</script>';

          //$subdata[]    =   $row[10]
          //print_r($row[9]);
          $data[] = $subdata;
          //  $dataone[] = $subdata;

          $i++;
          }
          }
         */

        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalData),
            //"recordsFiltered" => intval($totalFilter),
            "data" => $data
        );

        echo json_encode($json_data);

        die;
    }

    public function get_all_stat_info()
    {

        $con = $this->getConnection();

        $request = $_REQUEST;
        $col = array(
            0 => 'created',
            1 => 'site',
            2 => 'accession',
            3 => 'patient_name',
            4 => 'mrn',
            5 => 'webhook_customer',
            6 => 'webhook_description',
            7 => 'name',
            8 => 'status',
            9 => 'review_user_id',
        );  //create column like table in database
        //echo $col[$request['order'][0]['column']];exit;
        /*    $sql = "SELECT Clario.id, Clario.created, Clario.accession ,Clario.patient_name ,Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer FROM Clario LEFT JOIN users on Clario.assignee = users.id order by Clario.id DESC";

          $query = mysqli_query($con, $sql);

          $totalData = mysqli_num_rows($query);

          $totalFilter = $totalData; */

        //Search
        /*   $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer,cust.tat as dtat, Clario.last_modified,worksheets.clario_id,worksheets.analyses_performed,worksheets.analyses_ids FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id LEFT JOIN worksheets on Clario.id = worksheets.clario_id WHERE 1=1 ";  */

        /* $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer, users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer,cust.tat as dtat, Clario.last_modified, worksheets.clario_id,worksheets.analyses_performed,worksheets.analyses_ids FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id LEFT JOIN worksheets on Clario.id = worksheets.clario_id WHERE worksheets.analyses_ids IN (61)";  */

        $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer, users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer,cust.tat as dtat, Clario.last_modified, worksheets.clario_id,worksheets.analyses_performed,worksheets.analyses_ids,Clario.site,users.site_code,users.client_code FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id LEFT JOIN worksheets on Clario.id = worksheets.clario_id WHERE 1=1";

        if (isset($_POST["is_assignee"]) && !empty($_POST['is_assignee'])) {
            $sql .= " AND assignee = '" . $_POST["is_assignee"] . "' ";
        }

        if (isset($_POST["is_day"]) && !empty($_POST['is_day'])) {
            $sql .= " AND TIMESTAMPDIFF(DAY,Clario.created,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        /*  if (isset($_POST["status_select"]) && !empty($_POST['status_select'])) { 
          $sql .= " AND Clario.status = '" . $_POST["status_select"] . "' ";
          } */

        if (isset($_POST["status_select"]) && !empty($_POST['status_select'])) {
            if ($_POST['status_select'] != 'Not Assigned') {
                $sql .= " AND Clario.status = '" . $_POST["status_select"] . "' ";
            }
            if ($_POST['status_select'] == 'Not Assigned') {
                $sql .= " AND Clario.status = '' ";
            }
        }


        if (isset($_POST['is_second']) && !empty($_POST['is_second'])) {
            if ($_POST['is_second'] == 1) {
                $sql .= " AND Clario.review_user_id !='' ";
                if (isset($_POST['asignee_second']) && !empty($_POST['asignee_second'])) {
                    $reviewer_id = $_POST['asignee_second'];
                    $sql .= " AND Clario.review_user_id ='$reviewer_id'";
                }
            } else {
                $sql .= " AND Clario.review_user_id =''";
            }
        }

        /*  if (isset($_POST['analysis_perfomed']) && !empty($_POST['analysis_perfomed'])) {

          $ans_id = $_POST['analysis_perfomed'];
          $sql .= " AND worksheets.analyses_ids IN (".$ans_id.") ";

          }
          else{
          $sql .= " AND worksheets.analyses_ids IN (60,61,62) ";
          }

         */

        if (isset($_POST['analysis_perfomed']) && !empty($_POST['analysis_perfomed'])) {

            $ans_id = $_POST['analysis_perfomed'];
            $sql .= " AND worksheets.analyses_ids LIKE '%" . $ans_id . "%' ";
        } else {
            $sql .= " AND (worksheets.analyses_ids LIKE '%60%' OR worksheets.analyses_ids LIKE '%61%' OR worksheets.analyses_ids LIKE '%62%') ";
        }

        if (isset($_POST["status_select"]) && !empty($_POST['status_select']) && $_POST["status_select"] != 'Not Assigned') {
            $sql .= " AND Clario.status = '" . $_POST["status_select"] . "' ";
        }

        if (isset($_POST["status_select"]) && !empty($_POST['status_select']) && $_POST["status_select"] == 'Not Assigned') {
            $sql .= " AND Clario.status = '' ";
        }



        if (!empty($request['search']['value'])) {

            /* $sec_review = "SELECT id,name FROM users WHERE user_type_ids = '5' AND name Like '%" . $request['search']['value'] . "%'";

              $secquery = mysqli_query($con, $sec_review);
              $secid = [];
              while ($secrow = mysqli_fetch_array($secquery)) {
              $secid[] = $secrow['id'];
              } */


            $sql .= " AND (Clario.id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.created Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.accession Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_customer Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR cust.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_customer Like '%" . $request['search']['value'] . "%' ";
            /* if(!empty($secid)){
              $secondids = implode(",",$secid);
              $sql .= " OR Clario.review_user_id IN '(" . $secondids . ")' ";
              } */

            $sql .= " OR users.name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_description Like '%" . $request['search']['value'] . "%' ";
            if ($request['search']['value'] != 'Not Assigned') {
                $sql .= " OR Clario.status Like '%" . $request['search']['value'] . "%' )";
            }
            if ($request['search']['value'] == 'Not Assigned') {
                $sql .= " OR Clario.status = '')";
            }
        }




        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);

        //Order
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);

        $data = array();

        $i = 0;

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            //$assessment_ids = $this->Admindb->get_name_by_id($row[0]);
            $originalDate = $row[1];
            $review_name = $this->Admindb->get_name_by_id($row[10]);
            $assign_customer = $this->Admindb->get_name_by_id($row[11]);
            // $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $newDate = date("m-d-Y", strtotime($originalDate));
            $subdata[] = $newDate;
            // $subdata[] = $row[0]; //created
            $subdata[] = $row[2]; //created
            $subdata[] = $row[3]; //created
            $subdata[] = $row[4]; //created
            if (!empty($row[5])) {
                $row[5] = $row[5] . " hrs";
            } else {
                $row[5] = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
            }
            $subdata[] = $row[5];
            //  $subdata[] = $row[6]; //created
            $subdata[] = $row[7] ?: "Not Assigned";
            $subdata[] = (empty($assign_customer)) ? "" : $assign_customer;

            $subdata[] = $row[19];
            $subdata[] = $row[18];
            $subdata[] = (empty($review_name)) ? "Not Reviewed" : $review_name;

            $arrayids = explode(" ", $row[8]);

            // $subdata[] = $row[8];
            $subdata[] = $row[15];
            $subdata[] = $row[16];
            if ($row[9] == 'Completed') {
                $manilDate = $row[13];
                $newcomDate = date("m-d-Y h:i:s A", strtotime($manilDate));
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-success status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Completed<br><span class="btn btn-light">' . $newcomDate . '</span></span>';
                $bgcolor = 'bg-success';
            }
            if ($row[9] == '') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-danger status_chk" rel="' . str_replace(' ', '_', strtolower('Not 
Assigned')) . '">Not Assigned</span>';
                $bgcolor = 'bg-danger';
            }
            if ($row[9] == 'In progress') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-info status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">In progress</span>';
                $bgcolor = 'bg-info';
            }
            if ($row[9] == 'Under review') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Under review</span>';
                $bgcolor = 'bg-warning';
            }
            //            if($row[9] == 'Cancelled'){ $row[9] = '<span class="btn btn-xs btn-danger">Cancelled</span>';}
            if ($row[9] == 'Cancelled' || $row[9] == 'CancelledAcc' || $row[9] == 'CancelledCust') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-default status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Cancelled</span>';
                $bgcolor = 'bg-default';
            }
            if ($row[9] == 'On hold') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">On hold</span>';
                $bgcolor = 'bg-warning';
            }


            $subdata[] = $row[9];

            //   $subdata[] = '<a href="' . SITE_URL . '/admin/dicom_details?edit=' . $row[0] . '" class="btn btn-primary btn-xs">View</a>';

            $subdata[] = '<script>$("#status_val_' . $i . '").closest("tr").addClass("' . $bgcolor . '"); $("#status_val_' . $i . '").closest("td").attr("display","none");</script>';

            //$subdata[]    =   $row[10]
            //print_r($row[9]);
            $data[] = $subdata;

            $i++;
        }
        // print_r($totalData);
        //  die();
        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalData),
            //"recordsFiltered" => intval($totalFilter),
            "data" => $data
        );

        echo json_encode($json_data);

        die;
    }

    public function get_assigned_worksheet_info()
    {

        $con = $this->getConnection();

        $request = $_REQUEST;
        $col = array(
            0 => 'created',
            1 => 'mrn',
            2 => 'tat',
            3 => 'patient_name',
            4 => 'name',
            5 => 'webhook_description',
            6 => 'status'
        );  //create column like table in database

        $sql = "SELECT Clario.id, Clario.created, Clario.mrn, Clario.tat, Clario.patient_name, users.name, Clario.webhook_description, Clario.status from Clario JOIN users ON Clario.assignee = users.id WHERE Clario.assignee != 0";
        $query = mysqli_query($con, $sql);

        $totalData = mysqli_num_rows($query);

        // $totalFilter = $totalData;
        //Search
        $sql = "SELECT Clario.id, Clario.created, Clario.mrn, Clario.tat, Clario.patient_name, users.name, Clario.webhook_description, Clario.status from Clario JOIN users ON Clario.assignee = users.id WHERE 1=1 AND Clario.assignee != 0 ";

        if (!empty($request['search']['value'])) {
            $sql .= " AND (Clario.id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.created Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR users.name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_description Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.status Like '%" . $request['search']['value'] . "%' )";
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);
        $totalFilter = $totalData;

        //Order
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);

        $data = array();

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            $originalDate = $row[1];
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $subdata[] = $newDate; //created		   
            $subdata[] = $row[2]; //mrn
            if ($row[3] != '') {
                $row[3] = $row[3] . " hrs";
            }
            $subdata[] = $row[3]; //tat
            $subdata[] = $row[4]; //patient
            $subdata[] = $row[5]; //assignee
            $subdata[] = $row[6]; //descrip

            if ($row[7] == 'Completed') {
                $row[7] = '<span class="btn btn-xs btn-success">Completed</span>';
            }
            if ($row[7] == 'In progress') {
                $row[7] = '<span class="btn btn-xs btn-info">In progress</span>';
            }
            if ($row[7] == 'Under review') {
                $row[7] = '<span class="btn btn-xs btn-warning">Under review</span>';
            }
            if ($row[7] == 'Cancelled') {
                $row[7] = '<span class="btn btn-xs btn-danger">Cancelled</span>';
            }
            if ($row[7] == 'On hold') {
                $row[7] = '<span class="btn btn-xs btn-warning">On hold</span>';
            }

            $subdata[] = $row[7]; //created
            $subdata[] = '<a href="' . SITE_URL . '/admin/dicom_details_assigned?edit=' . $row[0] . '" class="btn btn-primary btn-xs">View</a>';

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

    /* analyst open worksheet */

    public function get_analyst_open_worksheets_info()
    {

        $con = $this->getConnection();
        $request = $_REQUEST;
        $col = array(
            0 => 'created',
            1 => 'site',
            2 => 'accession',
            3 => 'patient_name',
            4 => 'mrn',
            5 => 'webhook_customer',
            6 => 'webhook_description'
        );  //create column like table in database

        $sql = "SELECT id, created, accession, patient_name, mrn, tat, webhook_customer, webhook_description FROM `Clario` WHERE assignee = 0";
        $query = mysqli_query($con, $sql);

        $totalData = mysqli_num_rows($query);

        $totalFilter = $totalData;

        //Search
        $sql = "SELECT id, created, accession, patient_name, mrn, tat, webhook_customer, webhook_description FROM `Clario` WHERE assignee = 0";
        if (!empty($request['search']['value'])) {
            $sql .= " AND (id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR created Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR accession Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR webhook_customer Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR webhook_description Like '%" . $request['search']['value'] . "%' )";
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);

        //Order
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);

        $data = array();

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            $originalDate = $row[1];
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $subdata[] = $newDate; //name
            $subdata[] = $row[2]; //created
            $subdata[] = $row[3]; //created
            $subdata[] = $row[4]; //created
            $subdata[] = $row[5]; //created
            $subdata[] = $row[6]; //created
            $subdata[] = $row[7]; //created
            //create event on click in button edit in cell datatable for display modal dialog $row[0] is id in table on database
            $subdata[] = '<a href="' . SITE_URL . '/dashboard/open_work_sheets?view=' . $row[0] . '" class="btn btn-primary btn-xs">View</a>';

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

    public function get_analyst_assigned_worksheet_info()
    {

        $con = $this->getConnection();

        $request = $_REQUEST;
        $col = array(
            0 => 'created',
            1 => 'mrn',
            2 => 'tat',
            3 => 'patient_name',
            4 => 'name',
            5 => 'webhook_description',
            6 => 'status'
        );  //create column like table in database

        $sql = "SELECT Clario.id, Clario.created, Clario.mrn, Clario.tat, Clario.patient_name, users.name, Clario.webhook_description, Clario.status from Clario JOIN users ON Clario.assignee = users.id  WHERE Clario.assignee = '" . $_SESSION['user']->id . "'";
        $query = mysqli_query($con, $sql);

        $totalData = mysqli_num_rows($query);

        $totalFilter = $totalData;

        //Search
        $sql = "SELECT Clario.id, Clario.created, Clario.mrn, Clario.tat, Clario.patient_name, users.name, Clario.webhook_description, Clario.status from Clario JOIN users ON Clario.assignee = users.id WHERE 1=1 AND Clario.assignee = '" . $_SESSION['user']->id . "'";

        if (!empty($request['search']['value'])) {
            $sql .= " AND (Clario.id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.created Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR users.name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_description Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.status Like '%" . $request['search']['value'] . "%' )";
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);

        //Order
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);

        $data = array();

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            $originalDate = $row[1];
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $subdata[] = $newDate;
            $subdata[] = $row[2]; //created
            if ($row[3] != '') {
                $row[3] = $row[3] . " hrs";
            }
            $subdata[] = $row[3]; //created
            $subdata[] = $row[4]; //created
            $subdata[] = $row[5]; //created	
            $subdata[] = $row[6]; //created	
            if ($row[7] == 'Completed') {
                $row[7] = '<span class="btn btn-xs btn-success">Completed</span>';
            }
            if ($row[7] == 'In progress') {
                $row[7] = '<span class="btn btn-xs btn-info">In progress</span>';
            }
            if ($row[7] == 'Under review') {
                $row[7] = '<span class="btn btn-xs btn-warning">Under review</span>';
            }
            if ($row[7] == 'Cancelled') {
                $row[7] = '<span class="btn btn-xs btn-danger">Cancelled</span>';
            }
            if ($row[7] == 'On hold') {
                $row[7] = '<span class="btn btn-xs btn-warning">On hold</span>';
            }

            $subdata[] = $row[7]; //created
            $subdata[] = '<a href="' . SITE_URL . '/dashboard/my_work_sheets?edit=' . $row[0] . '" class="btn btn-primary btn-xs">Edit</a>';

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

    public function get_analyst_current_month_info()
    {
        $con = $this->getConnection();

        $request = $_REQUEST;
        $col = array(
            0 => 'created',
            1 => 'site',
            2 => 'accession',
            3 => 'patient_name',
            4 => 'mrn',
            5 => 'webhook_customer',
            6 => 'webhook_description',
            7 => 'name',
            8 => 'status',
            9 => 'review_user_id',
        );

        $sql = "SELECT Clario.id, Clario.created, Clario.accession ,Clario.patient_name ,Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer FROM Clario LEFT JOIN users on Clario.assignee = users.id WHERE MONTH(Clario.created) = MONTH(CURRENT_DATE()) AND YEAR(Clario.created) = YEAR(CURRENT_DATE())";

        $query = mysqli_query($con, $sql);

        $totalData = mysqli_num_rows($query);

        $totalFilter = $totalData;

        //Search
        $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer, cust.tat  as dtat FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id WHERE MONTH(Clario.created) = MONTH(CURRENT_DATE()) AND YEAR(Clario.created) = YEAR(CURRENT_DATE())";

        if (isset($_POST["is_assignee"]) && !empty($_POST['is_assignee'])) {
            $sql .= " AND assignee = '" . $_POST["is_assignee"] . "' ";
        }

        if (isset($_POST["is_day"]) && !empty($_POST['is_day'])) {
            $sql .= " AND TIMESTAMPDIFF(DAY,Clario.created,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (isset($_POST["status_select"]) && !empty($_POST['status_select'])) {
            $sql .= " AND Clario.status = '" . $_POST["status_select"] . "' ";
        }

        if (isset($_POST['is_second']) && !empty($_POST['is_second'])) {
            if ($_POST['is_second'] == 1) {
                $sql .= " AND review_user_id !='' ";
                if (isset($_POST['asignee_second']) && !empty($_POST['asignee_second'])) {
                    $reviewer_id = $_POST['asignee_second'];
                    $sql .= " AND review_user_id ='$reviewer_id'";
                }
            } else {
                $sql .= " AND review_user_id =''";
            }
        }


        if (!empty($request['search']['value'])) {
            $sql .= " AND (Clario.id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.created Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.accession Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR cust.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_customer Like '%" . $request['search']['value'] . "%' ";

            $sql .= " OR users.name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_description Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.status Like '%" . $request['search']['value'] . "%' )";
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);

        //Order
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);

        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            $originalDate = $row[1];
            $review_name = $this->Admindb->get_name_by_id($row[10]);
            $assign_customer = $this->Admindb->get_name_by_id($row[11]);
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $subdata[] = $newDate;
            $subdata[] = $row[2]; //created
            $subdata[] = $row[3]; //created
            $subdata[] = $row[4]; //created
            if (!empty($row[5])) {
                $row[5] = $row[5] . " hrs";
            } else {
                $row[5] = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
            }
            $subdata[] = $row[5];
            $subdata[] = $row[6]; //created
            $subdata[] = $row[7] ?: "Not Assigned";
            $subdata[] = (empty($assign_customer)) ? "" : $assign_customer;
            $subdata[] = (empty($review_name)) ? "Not Reviewed" : $review_name;
            $subdata[] = $row[8];
            $statusCheck = $row[9];

            if ($row[9] == 'Completed') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-success status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Completed</span>';
                $bgcolor = 'bg-success';
            }
            if ($row[9] == '') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-danger status_chk" rel="' . str_replace(' ', '_', strtolower('Not 
Assigned')) . '">Not Assigned</span>';
                $bgcolor = 'bg-danger';
            }
            if ($row[9] == 'In progress') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-info status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">In progress</span>';
                $bgcolor = 'bg-info';
            }
            if ($row[9] == 'Under review') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Under review</span>';
                $bgcolor = 'bg-warning';
            }
            //            if($row[9] == 'Cancelled'){ $row[9] = '<span class="btn btn-xs btn-danger">Cancelled</span>';}
            if ($row[9] == 'Cancelled' || $row[9] == 'CancelledAcc' || $row[9] == 'CancelledCust') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-default status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Cancelled</span>';
                $bgcolor = 'bg-default';
            }
            if ($row[9] == 'On hold') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">On hold</span>';
                $bgcolor = 'bg-warning';
            }

            $subdata[] = $row[9];

            if ($row[7] == '') {
                $subdata[] = '<a href="' . SITE_URL . '/dashboard/open_work_sheets?view=' . $row[0] . '" class="btn btn-success btn-xs" >Assign</a>';
            } else if ($statusCheck == 'Completed') {
                //$subdata[] = '<a href="' . SITE_URL . '/dashboard/my_work_sheets?edit=' . $row[0] . '" class="btn btn-primary btn-xs" style="width: 44px;">Edit</a><a href="' . SITE_URL . '/dashboard/my_work_sheets?edit=' . $row[0] . '&rv=1" class="btn btn-warning btn-xs review-btn" id="'.$row[0].'" style="width: 44px;">Review</a>';
                $subdata[] = '<a href="' . SITE_URL . '/dashboard/my_work_sheets?edit=' . $row[0] . '" class="btn btn-primary btn-xs" style="width: 44px;">Edit</a><a  class="btn btn-warning btn-xs" style="width: 44px;" onclick="getreview(' . $row[0] . ')";>Review</a>';
            } else {
                $subdata[] = '<a href="' . SITE_URL . '/dashboard/my_work_sheets?edit=' . $row[0] . '" class="btn btn-primary btn-xs" style="width: 44px;">Edit</a><a  class="btn btn-warning btn-xs" style="width: 44px;" onclick="getreview(' . $row[0] . ')";>Review</a>';
            }
            $subdata[] = '<script>$("#status_val_' . $i . '").closest("tr").addClass("' . $bgcolor . '"); $("#status_val_' . $i . '").closest("td").attr("display","none");</script>';
            //$subdata[]    =   $row[10]
            //print_r($row[9]);
            $data[] = $subdata;
            $i++;
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

    public function get_analyst_all_worksheet_info()
    {

        $con = $this->getConnection();

        $request = $_REQUEST;
        $col = array(
            0 => 'created',
            1 => 'site',
            2 => 'accession',
            3 => 'patient_name',
            4 => 'mrn',
            5 => 'webhook_customer',
            6 => 'webhook_description',
            7 => 'name',
            8 => 'status',
            9 => 'review_user_id',
        );  //create column like table in database

        $sql = "SELECT Clario.id, Clario.created, Clario.accession ,Clario.patient_name ,Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer FROM Clario LEFT JOIN users on Clario.assignee = users.id ";

        $query = mysqli_query($con, $sql);

        $totalData = mysqli_num_rows($query);

        $totalFilter = $totalData;

        //Search
        $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer,cust.tat as dtat FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id WHERE 1=1";

        if (isset($_POST["is_assignee"]) && !empty($_POST['is_assignee'])) {
            $sql .= " AND assignee = '" . $_POST["is_assignee"] . "' ";
        }

        if (isset($_POST["is_day"]) && !empty($_POST['is_day'])) {
            $sql .= " AND TIMESTAMPDIFF(DAY,Clario.created,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (isset($_POST["status_select"]) && !empty($_POST['status_select'])) {
            $sql .= " AND Clario.status = '" . $_POST["status_select"] . "' ";
        }

        if (isset($_POST['is_second']) && !empty($_POST['is_second'])) {
            if ($_POST['is_second'] == 1) {
                $sql .= " AND review_user_id !='' ";
                if (isset($_POST['asignee_second']) && !empty($_POST['asignee_second'])) {
                    $reviewer_id = $_POST['asignee_second'];
                    $sql .= " AND review_user_id ='$reviewer_id'";
                }
            } else {
                $sql .= " AND review_user_id =''";
            }
        }


        //asignee_second

        if (!empty($request['search']['value'])) {
            $sql .= " AND (Clario.id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.created Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.accession Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR cust.tat Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_customer Like '%" . $request['search']['value'] . "%' ";

            $sql .= " OR users.name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.webhook_description Like '%" . $request['search']['value'] . "%' ";
            //  $sql .= " OR Clario.status Like '%" . $request['search']['value'] . "%' )";
            if ($request['search']['value'] != 'Not Assigned') {
                $sql .= " OR Clario.status Like '%" . $request['search']['value'] . "%' )";
            }
            if ($request['search']['value'] == 'Not Assigned') {
                $sql .= " OR Clario.status = '')";
            }
        }
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);

        //Order
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);

        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            $originalDate = $row[1];
            $review_name = $this->Admindb->get_name_by_id($row[10]);
            $assign_customer = $this->Admindb->get_name_by_id($row[11]);
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $subdata[] = $newDate;
            $subdata[] = $row[2]; //created
            $subdata[] = $row[3]; //created
            $subdata[] = $row[4]; //created
            if (!empty($row[5])) {
                $row[5] = $row[5] . " hrs";
            } else {
                $row[5] = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
            }
            $subdata[] = $row[5];
            $subdata[] = $row[6]; //created
            $subdata[] = $row[7] ?: "Not Assigned";
            $subdata[] = (empty($assign_customer)) ? "" : $assign_customer;
            $subdata[] = (empty($review_name)) ? "Not Reviewed" : $review_name;

            $subdata[] = $row[8];
            $statusCheck = $row[9];
            //            if($row[9] == 'Completed'){ $row[9] = '<span class="btn btn-xs btn-success">Completed</span>';}
            //            if($row[9] == ''){ $row[9] = '<span class="btn btn-xs btn-danger">Not Assigned</span>';}
            //            if($row[9] == 'In progress'){ $row[9] = '<span class="btn btn-xs btn-info">In progress</span>';}
            //            if($row[9] == 'Under review'){ $row[9] = '<span class="btn btn-xs btn-warning">Under review</span>';}
            //            if($row[9] == 'Cancelled'){ $row[9] = '<span class="btn btn-xs btn-danger">Cancelled</span>';}
            //            if($row[9] == 'On hold'){ $row[9] = '<span class="btn btn-xs btn-warning">On hold</span>';}

            if ($row[9] == 'Completed') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-success status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Completed</span>';
                $bgcolor = 'bg-success';
            }
            if ($row[9] == '') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-danger status_chk" rel="' . str_replace(' ', '_', strtolower('Not 
Assigned')) . '">Not Assigned</span>';
                $bgcolor = 'bg-danger';
            }
            if ($row[9] == 'In progress') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-info status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">In progress</span>';
                $bgcolor = 'bg-info';
            }
            if ($row[9] == 'Under review') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Under review</span>';
                $bgcolor = 'bg-warning';
            }
            //            if($row[9] == 'Cancelled'){ $row[9] = '<span class="btn btn-xs btn-danger">Cancelled</span>';}
            if ($row[9] == 'Cancelled' || $row[9] == 'CancelledAcc' || $row[9] == 'CancelledCust') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-default status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">Cancelled</span>';
                $bgcolor = 'bg-default';
            }
            if ($row[9] == 'On hold') {
                $row[9] = '<span id="status_val_' . $i . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row[9])) . '">On hold</span>';
                $bgcolor = 'bg-warning';
            }

            $subdata[] = $row[9];

            if ($row[7] == '') {
                $subdata[] = '<a href="' . SITE_URL . '/dashboard/open_work_sheets?view=' . $row[0] . '" class="btn btn-success btn-xs" >Assign</a>';
            } else if ($statusCheck == 'Completed') {
                //$subdata[] = '<a href="' . SITE_URL . '/dashboard/my_work_sheets?edit=' . $row[0] . '" class="btn btn-primary btn-xs" style="width: 44px;">Edit</a><a href="' . SITE_URL . '/dashboard/my_work_sheets?edit=' . $row[0] . '&rv=1" class="btn btn-warning btn-xs review-btn" id="'.$row[0].'" style="width: 44px;">Review</a>';
                $subdata[] = '<a href="' . SITE_URL . '/dashboard/my_work_sheets?edit=' . $row[0] . '" class="btn btn-primary btn-xs" style="width: 44px;">Edit</a><a  class="btn btn-warning btn-xs" style="width: 44px;display:none;" onclick="getreview(' . $row[0] . ')";>Review</a>';
            } else {
                $subdata[] = '<a href="' . SITE_URL . '/dashboard/my_work_sheets?edit=' . $row[0] . '" class="btn btn-primary btn-xs" style="width: 44px;">Edit</a><a  class="btn btn-warning btn-xs" style="width: 44px;display:none;" onclick="getreview(' . $row[0] . ')";>Review</a>';
            }
            $subdata[] = '<script>$("#status_val_' . $i . '").closest("tr").addClass("' . $bgcolor . '"); $("#status_val_' . $i . '").closest("td").attr("display","none");</script>';
            //$subdata[]	=	$row[10]
            //print_r($row[9]);
            $data[] = $subdata;
            $i++;
        }

        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($totalData),
            //"recordsFiltered" => intval($totalFilter),
            "recordsFiltered" => intval($totalData),
            "data" => $data
        );

        echo json_encode($json_data);

        die;
    }

    //    public function get_study_time_report() {
    //
    //        $con = $this->getConnection();
    //
    //
    //        /*  if(isset($_POST["is_customer"])){
    //          $cus = $_POST["is_customer"]);
    //          } else {
    //          $cus = "";
    //          } */
    //
    //        /*   $day = $_POST["is_day"];
    //          $asn = $_POST["is_assignee"];
    //          $sts = $_POST["is_assignee"]; */
    //
    //        $request = $_REQUEST;
    //        $col = array(
    //            0 => 'date',
    //            1 => 'mrn',
    //            2 => 'customer_id',
    //            3 => 'accession',
    //            4 => 'patient_name',
    //            5 => 'analyst_hours',
    //            6 => 'expected_time',
    //            7 => 'image_specialist_hours',
    //            8 => 'medical_director_hours',
    //            9 => 'name',
    //            10 => 'status'
    //        );  //create column like table in database
    //
    //        $sql = "SELECT worksheets.id, worksheets.date, Clario.mrn, worksheets.customer_id, Clario.accession, Clario.patient_name, worksheets.analyst_hours, worksheets.expected_time, worksheets.image_specialist_hours, worksheets.medical_director_hours, users.name, worksheets.status FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id LEFT JOIN users ON worksheets.analyst = users.id";
    //
    //
    //
    //        $query = mysqli_query($con, $sql);
    //
    //        $totalData = mysqli_num_rows($query);
    //
    //        $totalFilter = $totalData;
    //
    //        //Search
    //        $sql = "SELECT worksheets.id, worksheets.date, Clario.mrn, worksheets.customer_id, Clario.accession, Clario.patient_name,worksheets.analyst_hours,worksheets.expected_time,worksheets.image_specialist_hours,worksheets.medical_director_hours, users.name, worksheets.status FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id LEFT JOIN users ON worksheets.analyst = users.id WHERE 1=1";
    //
    //        if (!empty($_POST["is_day"]) && $_POST["is_assignee"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_assignee"]) && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_customer"]) && $_POST["is_assignee"] == 0 && $_POST["is_day"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_status"]) && $_POST["is_assignee"] == 0 && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_time_mgmt"]) && $_POST["is_assignee"] == 0 && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0) {
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
    //            
    //             $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //
    //// combination of 2 is day
    //
    //
    //        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_day"]) && !empty($_POST["is_customer"]) && $_POST["is_assignee"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_day"]) && !empty($_POST["is_status"]) && $_POST["is_assignee"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_day"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_assignee"] == 0 && $_POST["is_status"] == 0 && $_POST["is_customer"] == 0) {
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
    //            
    //            $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //
    //
    //
    //// combination of 2 assignee
    //
    //        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && $_POST["is_day"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_status"]) && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0) {
    //  
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
    //            
    //            $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //
    //
    //
    //// combination of 2 - customer		
    //        if (!empty($_POST["is_status"]) && !empty($_POST["is_customer"]) && $_POST["is_day"] == 0 && $_POST["is_assignee"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_time_mgmt"]) && !empty($_POST["is_customer"]) && $_POST["is_day"] == 0 && $_POST["is_assignee"] == 0 && $_POST["is_status"] == 0) {
    //
    //            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' ";
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
    //            
    //            $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //
    //// combination of 2 - status
    //
    //        if (!empty($_POST["is_time_mgmt"]) && !empty($_POST["is_status"]) && $_POST["is_day"] == 0 && $_POST["is_assignee"] == 0 && $_POST["is_customer"] == 0) {
    //
    //            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' ";
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
    //            
    //            $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //
    //
    //
    //        if (!empty($_POST["is_customer"]) && !empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_status"]) && !empty($_POST["is_time_mgmt"])) {
    //
    //            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
    //            
    //            $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //
    ////combinations of three 
    //
    //        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_status"]) && $_POST["is_customer"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_day"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_status"]) && $_POST["is_assignee"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
    //        }
    //
    //        
    //         if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0) {
    //
    //            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
    //            
    //            $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //
    //        
    //        
    //        if (!empty($_POST["is_day"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_assignee"] == 0 && $_POST["is_status"] == 0) {
    //
    //            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
    //            
    //            $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //
    //
    //        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_status"]) && $_POST["is_day"] == 0 && $_POST["is_time_mgmt"] == 0) {
    //
    //            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
    //        }
    //
    //        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_day"] == 0 && $_POST["is_status"] == 0) {
    //            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
    //            
    //            $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //
    //
    //        if (!empty($_POST["is_status"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_day"] == 0 && $_POST["is_assignee"] == 0) {
    //            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
    //            
    //            $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //
    //
    //// combinations of 4
    //        
    //        if (!empty($_POST["is_day"]) && !empty($_POST["is_status"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_assignee"] == 0) {
    //            $sql .= " AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
    //            
    //            $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //        
    //        
    //          if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_status"] == 0) {
    //            $sql .= " AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
    //            
    //            $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //        
    //        
    //          if (!empty($_POST["is_status"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_day"] == 0) {
    //            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
    //            if ($_POST["is_time_mgmt"] == "AtVsEat")
    //                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
    //            else if ($_POST["is_time_mgmt"] == "EatVsAt")
    //                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
    //            
    //            $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
    //        }
    //        
    //        
    //        
    //
    //
    //        /* if(isset($_POST["is_customer"]))
    //          {
    //          $sql .= " AND worksheets.customer_id = '".$_POST["is_customer"]."' ";
    //          }
    //
    //          elseif(isset($_POST["is_assignee"]))
    //          {
    //          $sql .= " AND worksheets.analyst = '".$_POST["is_assignee"]."' ";
    //          }
    //
    //          elseif(isset($_POST["is_day"]))
    //          {
    //          $sql .= " AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '".$_POST["is_day"]."' ";
    //          }
    //
    //          elseif(isset($_POST["is_day"]))
    //          {
    //          $sql .= " AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '".$_POST["is_day"]."' ";
    //          } */
    //
    //        /* elseif (isset($_POST["is_customer"]) ) {
    //          # code...
    //          }
    //         */
    //
    //        /* if(isset($_POST["date_search"]))
    //          {
    //          //$start_date = $_POST["start_date"]." 00:00:00";
    //          //$end_date = $_POST["end_date"]." 00:00:00";
    //
    //
    //          $sql .= 'AND worksheets.date BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND ';
    //          } */
    //
    //
    //
    //        if (!empty($request['search']['value'])) {
    //            $sql.=" AND (worksheets.id Like '" . $request['search']['value'] . "%' ";
    //            $sql.=" OR worksheets.date Like'" . $request['search']['value'] . "%' ";
    //            $sql.=" OR Clario.mrn Like '" . $request['search']['value'] . "%' ";
    //            $sql.=" OR worksheets.customer_id Like '" . $request['search']['value'] . "%' ";
    //            $sql.=" OR Clario.accession Like '" . $request['search']['value'] . "%' ";
    //            $sql.=" OR Clario.patient_name Like '" . $request['search']['value'] . "%' ";
    //            $sql.=" OR worksheets.analyst_hours Like '" . $request['search']['value'] . "%' ";
    //            $sql.=" OR worksheets.expected_time Like '" . $request['search']['value'] . "%' ";
    //            $sql.=" OR worksheets.image_specialist_hours Like '" . $request['search']['value'] . "%' ";
    //            $sql.=" OR worksheets.medical_director_hours Like '" . $request['search']['value'] . "%' ";
    //            $sql.=" OR users.name Like '" . $request['search']['value'] . "%' ";
    //            $sql.=" OR worksheets.status Like '" . $request['search']['value'] . "%' )";
    //        }
    //        
    //        
    //        
    //        
    //        $query = mysqli_query($con, $sql);
    //        $totalData = mysqli_num_rows($query);
    //
    //        //Order
    //        $sql.=" ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
    //                $request['start'] . "  ," . $request['length'] . "  ";
    //
    //        $query = mysqli_query($con, $sql);
    //
    //        $data = array();
    //        $current_customers = array();
    //
    //        while ($row = mysqli_fetch_array($query)) {
    //            $subdata = array();
    //
    //            $originalDate = $row[1];
    //            $newDate = date("m-d-Y h:i:s", strtotime($originalDate));
    //
    //            $subdata[] = $newDate; //
    //            $subdata[] = $row[2];
    //            if (!in_array($row[3], $current_customers)) {
    //                $current_customers[] = $row[3];
    //            }
    //            $row[3] = $this->Admindb->get_name_by_id($row[3]);
    //            $subdata[] = $row[3];
    //            $subdata[] = $row[4];
    //            $subdata[] = $row[5];
    //
    //            if (!$row[6] > 0) {
    //                $row[6] = '<span class="btn btn-xs btn-warning">Time Not Added</span>';
    //                // $row[6]='Time Not Added';
    //            }
    //
    //            if ($row[6] > 0 && $row[7] > 0) {
    //                if ($row[6] > $row[7]) {
    ////                    $row[6] = '<span class="btn btn-xs btn-danger">' . $row[6] . '</span>';
    ////                    $row[7] = '<span class="btn btn-xs btn-danger">' . $row[7] . '</span>';
    //                     $row[6] = '<span>' . $row[6] . ' <i class="fa fa-arrow-up" style="font-size:20px;color:red"></i></span>';
    //                    $row[7] = '<span>' . $row[7] . '</span>';
    //                } else if ($row[6] < $row[7]) {
    ////                    $row[6] = '<span class="btn btn-xs btn-success">' . $row[6] . '</span>';
    ////                    $row[7] = '<span class="btn btn-xs btn-success">' . $row[7] . '</span>';
    //                    $row[6] = '<span>' . $row[6] . ' <i class="fa fa-arrow-down" style="font-size:20px;color:green"></i></span>';
    //                    $row[7] = '<span>' . $row[7] . '</span>';
    //                }
    //            }
    //
    //            $subdata[] = $row[6];
    //            $subdata[] = $row[7];
    //
    //            $subdata[] = $row[8];
    //            $subdata[] = $row[9];
    //            $subdata[] = $row[10];
    //
    //            if ($row[11] == 'Completed') {
    //                $row[11] = '<span class="btn btn-xs btn-success">Completed</span>';
    //            }
    //            if ($row[11] == 'In progress') {
    //                $row[11] = '<span class="btn btn-xs btn-info">In progress</span>';
    //            }
    //            if ($row[11] == 'Under review') {
    //                $row[11] = '<span class="btn btn-xs btn-warning">Under review</span>';
    //            }
    //            if ($row[11] == 'Cancelled') {
    //                $row[11] = '<span class="btn btn-xs btn-danger">Cancelled</span>';
    //            }
    //            if ($row[11] == 'On hold') {
    //                $row[11] = '<span class="btn btn-xs btn-warning">On hold</span>';
    //            }
    //
    //            $subdata[] = $row[11];
    //
    //           // $subdata[] = $sql;
    //            //print_r($row[8]);
    //            $data[] = $subdata;
    //        }
    //
    //        $json_data = array(
    //            "draw" => intval($request['draw']),
    //            "recordsTotal" => intval($totalData),
    //            "recordsFiltered" => intval($totalFilter),
    //            "data" => $data
    //        );
    //
    //        echo json_encode($json_data);
    //
    //        die;
    //    }


    public function get_study_time_report_old()
    {

        $con = $this->getConnection();

        $request = $_REQUEST;
        //        $col = array(
        //            0 => 'date',
        //            1 => 'mrn',
        //            2 => 'customer_id',
        //            3 => 'accession',
        //            4 => 'patient_name',
        //            5 => 'analyst_hours',
        //            6 => 'expected_time',
        //            7 => 'image_specialist_hours',
        //            8 => 'medical_director_hours',
        //            9 => 'name',
        //            10 => 'status'
        //        );  //create column like table in database


        $col = array(
            0 => 'date',
            1 => 'customer_id',
            2 => 'patient_name',
            3 => 'any_mint',
            4 => 'analyses_ids',
            5 => 'time_difference',
            6 => 'name',
            7 => 'status'
        );  //create column like table in database
        //$sql = "SELECT worksheets.id, worksheets.date, Clario.mrn, worksheets.customer_id, Clario.accession, Clario.patient_name, worksheets.analyst_hours, worksheets.expected_time, worksheets.image_specialist_hours, worksheets.medical_director_hours, users.name, worksheets.status FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id LEFT JOIN users ON worksheets.analyst = users.id";

        $sql = "SELECT worksheets.id, worksheets.date, worksheets.customer_id, Clario.patient_name, worksheets.analyst_hours, worksheets.expected_time, worksheets.image_specialist_hours, worksheets.medical_director_hours, users.name, worksheets.status,worksheets.any_mint,worksheets.analyses_ids FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id LEFT JOIN users ON worksheets.analyst = users.id";

        $query = mysqli_query($con, $sql);

        $totalData = mysqli_num_rows($query);

        //   $totalFilter = $totalData;
        //Search
        //$sql = "SELECT worksheets.id, worksheets.date, Clario.mrn, worksheets.customer_id, Clario.accession, Clario.patient_name,worksheets.analyst_hours,worksheets.expected_time,worksheets.image_specialist_hours,worksheets.medical_director_hours, users.name, worksheets.status FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id LEFT JOIN users ON worksheets.analyst = users.id WHERE 1=1";
        $sql = "SELECT worksheets.id, worksheets.date, worksheets.customer_id, Clario.patient_name, worksheets.analyst_hours,worksheets.expected_time,worksheets.image_specialist_hours,worksheets.medical_director_hours, users.name, worksheets.status,worksheets.any_mint,worksheets.analyses_ids FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id LEFT JOIN users ON worksheets.analyst = users.id WHERE 1=1";

        if (!empty($_POST["is_day"]) && $_POST["is_assignee"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (!empty($_POST["is_assignee"]) && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
        }

        if (!empty($_POST["is_customer"]) && $_POST["is_assignee"] == 0 && $_POST["is_day"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' ";
        }

        if (!empty($_POST["is_status"]) && $_POST["is_assignee"] == 0 && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' ";
        }

        if (!empty($_POST["is_time_mgmt"]) && $_POST["is_assignee"] == 0 && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0) {
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }

            //  $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
        }

        // combination of 2 is day


        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (!empty($_POST["is_day"]) && !empty($_POST["is_customer"]) && $_POST["is_assignee"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (!empty($_POST["is_day"]) && !empty($_POST["is_status"]) && $_POST["is_assignee"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (!empty($_POST["is_day"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_assignee"] == 0 && $_POST["is_status"] == 0 && $_POST["is_customer"] == 0) {
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
            }
        }



        // combination of 2 assignee

        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && $_POST["is_day"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' ";
        }

        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_status"]) && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
        }

        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0) {

            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
            }
        }



        // combination of 2 - customer		
        if (!empty($_POST["is_status"]) && !empty($_POST["is_customer"]) && $_POST["is_day"] == 0 && $_POST["is_assignee"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' ";
        }

        if (!empty($_POST["is_time_mgmt"]) && !empty($_POST["is_customer"]) && $_POST["is_day"] == 0 && $_POST["is_assignee"] == 0 && $_POST["is_status"] == 0) {

            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }

        // combination of 2 - status

        if (!empty($_POST["is_time_mgmt"]) && !empty($_POST["is_status"]) && $_POST["is_day"] == 0 && $_POST["is_assignee"] == 0 && $_POST["is_customer"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }



        if (!empty($_POST["is_customer"]) && !empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_status"]) && !empty($_POST["is_time_mgmt"])) {

            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }

        //combinations of three 

        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_status"]) && $_POST["is_customer"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (!empty($_POST["is_day"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_status"]) && $_POST["is_assignee"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }


        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0) {

            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }



        if (!empty($_POST["is_day"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_assignee"] == 0 && $_POST["is_status"] == 0) {

            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }


        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_status"]) && $_POST["is_day"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
        }

        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_day"] == 0 && $_POST["is_status"] == 0) {
            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }


        if (!empty($_POST["is_status"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_day"] == 0 && $_POST["is_assignee"] == 0) {
            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }


        // combinations of 4

        if (!empty($_POST["is_day"]) && !empty($_POST["is_status"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_assignee"] == 0) {
            $sql .= " AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }


        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_status"] == 0) {
            $sql .= " AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }


        if (!empty($_POST["is_status"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_day"] == 0) {
            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }





        if (!empty($request['search']['value'])) {
            $sql .= " AND (worksheets.id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.date Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.customer_id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.accession Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.analyst_hours Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.expected_time Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.image_specialist_hours Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.medical_director_hours Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR users.name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.status Like '%" . $request['search']['value'] . "%' )";
        }




        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);
        $totalFilter = $totalData;

        //Order
        $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
            $request['start'] . "  ," . $request['length'] . "  ";

        $query = mysqli_query($con, $sql);

        $data = array();
        $current_customers = array();

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();

            //            $originalDate = $row[1];
            //            $newDate = date("m-d-Y h:i:s", strtotime($originalDate));
            //
            //            $subdata[] = $newDate; //
            //            
            //            $subdata[] = $row[2];
            //            if (!in_array($row[3], $current_customers)) {
            //                $current_customers[] = $row[3];
            //            }
            //            $row[3] = $this->Admindb->get_name_by_id($row[3]);
            //            $subdata[] = $row[3];
            //            $subdata[] = $row[4];
            //            
            //            
            //            $subdata[] = $row[5];
            //
            //            if (!$row[6] > 0) {
            //                $row[6] = '<span class="btn btn-xs btn-warning">Time Not Added</span>';
            //                // $row[6]='Time Not Added';
            //            }
            //
            //            if ($row[6] > 0 && $row[7] > 0) {
            //                if ($row[6] > $row[7]) {
            ////                    $row[6] = '<span class="btn btn-xs btn-danger">' . $row[6] . '</span>';
            ////                    $row[7] = '<span class="btn btn-xs btn-danger">' . $row[7] . '</span>';
            //                     $row[6] = '<span>' . $row[6] . ' <i class="fa fa-arrow-up" style="font-size:20px;color:red"></i></span>';
            //                    $row[7] = '<span>' . $row[7] . '</span>';
            //                } else if ($row[6] < $row[7]) {
            ////                    $row[6] = '<span class="btn btn-xs btn-success">' . $row[6] . '</span>';
            ////                    $row[7] = '<span class="btn btn-xs btn-success">' . $row[7] . '</span>';
            //                    $row[6] = '<span>' . $row[6] . ' <i class="fa fa-arrow-down" style="font-size:20px;color:green"></i></span>';
            //                    $row[7] = '<span>' . $row[7] . '</span>';
            //                }
            //            }
            //
            //            $subdata[] = $row[6];
            //            $subdata[] = $row[7];
            //
            //            $subdata[] = $row[8];
            //            $subdata[] = $row[9];
            //            $subdata[] = $row[10];
            //
            //            if ($row[11] == 'Completed') {
            //                $row[11] = '<span class="btn btn-xs btn-success">Completed</span>';
            //            }
            //            if ($row[11] == 'In progress') {
            //                $row[11] = '<span class="btn btn-xs btn-info">In progress</span>';
            //            }
            //            if ($row[11] == 'Under review') {
            //                $row[11] = '<span class="btn btn-xs btn-warning">Under review</span>';
            //            }
            //            if ($row[11] == 'Cancelled') {
            //                $row[11] = '<span class="btn btn-xs btn-danger">Cancelled</span>';
            //            }
            //            if ($row[11] == 'On hold') {
            //                $row[11] = '<span class="btn btn-xs btn-warning">On hold</span>';
            //            }
            //
            //            $subdata[] = $row[11];
            // Date -0
            $originalDate = $row[1];
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));

            $subdata[] = $newDate; //
            // Customer -1
            if (!in_array($row[2], $current_customers)) {
                $current_customers[] = $row[2];
            }
            $row[2] = $this->Admindb->get_name_by_id($row[2]);
            $subdata[] = $row[2];

            // Patient Name -2
            $subdata[] = $row[3];

            if (!$row[4] > 0) {
                $row[4] = '<span class="btn btn-xs btn-warning">Time Not Added</span>';
            } else {
                $row[4] = number_format($row[4] * 60, 2);
                $row[5] = number_format($row[5] * 60, 2);
            }

            if ($row[4] > 0 && $row[5] > 0) {
                if ($row[4] > $row[5]) {
                    $row[4] = '<span>' . $row[4] . ' <i class="fa fa-arrow-up style-red" ></i></span>';
                    $row[5] = '<span>' . $row[5] . '</span>';
                } else if ($row[4] < $row[5]) {
                    $row[4] = '<span>' . $row[4] . ' <i class="fa fa-arrow-down style-green" ></i></span>';
                    $row[5] = '<span>' . $row[5] . '</span>';
                }
            }
            $workTimeTotal = 0;
            $excateTotalTime = 0;
            if (!empty($row[10])) {

                $workTime = explode(',', $row[10]);
                for (
                    $i = 0;
                    $i < count($workTime);
                    $i++
                ) {
                    $workTimeTotal = $workTimeTotal + $workTime[$i];
                }
            }
            if (!empty($row[11])) {

                $excateAnslyses = explode(',', $row[11]);
                for (
                    $j = 0;
                    $j < count($excateAnslyses);
                    $j++
                ) {
                    $timeValue = $this->Analyst->getexacttime($excateAnslyses[$j]);
                    $excateTotalTime = $excateTotalTime + $timeValue;
                }
            }
            $timeDff = $excateTotalTime - $workTimeTotal;
            $timeDffa = $excateTotalTime - $workTimeTotal;
            if ($timeDff > 0) {
                $timeDff = '<span>' . $timeDff . ' <i class="fa fa-arrow-down style-green" ></i></span>';
            } else if ($timeDff < 0) {
                $timeDff = '<span>' . $timeDff . ' <i class="fa fa-arrow-down style-green" ></i></span>';
            } else {
                $timeDff = '<span>' . $timeDff . '</span>';
            }
            // Analyst Hours -3
            $subdata[] = $workTimeTotal; //$excateTotalTime;//$row[4];
            // Expected Time -4
            $subdata[] = $excateTotalTime; //$row[5];
            // Image Specialist Hours -5
            // $subdata[] = $excateTotalTime;
            //number_format($row[6]*60,2);
            // Medical Director Hours -6
            //$subdata[] = number_format($row[7]*60,2);
            // Assignee -7  

            $subdata[] = $timeDffa;
            $subdata[] = $row[8];

            // Status -8  
            if ($row[9] == 'Completed') {
                $row[9] = '<span class="btn btn-xs btn-success">Completed</span>';
            }
            if ($row[9] == 'In progress') {
                $row[9] = '<span class="btn btn-xs btn-info">In progress</span>';
            }
            if ($row[9] == 'Under review') {
                $row[9] = '<span class="btn btn-xs btn-warning">Under review</span>';
            }
            if ($row[9] == 'Cancelled') {
                $row[9] = '<span class="btn btn-xs btn-danger">Cancelled</span>';
            }
            if ($row[9] == 'On hold') {
                $row[9] = '<span class="btn btn-xs btn-warning">On hold</span>';
            }

            $subdata[] = $row[9];

            $data[] = $subdata;
        }

        if (!empty($_POST["is_sorting"]) && $_POST["is_sorting"] == 1) {
            $key_values = array_column($data, 5);
            array_multisort($key_values, SORT_ASC, $data);
        }

        if (!empty($_POST["is_sorting"]) && $_POST["is_sorting"] == 2) {
            $key_values = array_column($data, 5);
            array_multisort($key_values, SORT_DESC, $data);
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

    public function study_time_graph_data()
    {
        $con = $this->getConnection();
        $request = $_REQUEST;
        $col = array(
            0 => 'name',
            1 => 'analyst_hours',
            2 => 'image_specialist_hours',
            3 => 'medical_director_hours'
        );  //create column like table in database

        $sql = "SELECT users.id, users.name, sum(worksheets.analyst_hours) as analysthrs, sum(worksheets.image_specialist_hours) as imagehrs, sum(worksheets.medical_director_hours) as medicalhrs FROM `worksheets` JOIN users on worksheets.customer_id = users.id GROUP BY worksheets.customer_id";

        $query = mysqli_query($con, $sql);

        $totalData = mysqli_num_rows($query);

        $totalFilter = $totalData;

        //Search
        $sql = "SELECT users.id,users.name, sum(worksheets.analyst_hours) as analysthrs, sum(worksheets.image_specialist_hours) as imagehrs, sum(worksheets.medical_director_hours) as medicalhrs FROM `worksheets` JOIN users on worksheets.customer_id = users.id WHERE 1=1 GROUP BY worksheets.customer_id  ";

        if (isset($_POST["is_date"])) {

            $start_date = $_POST["is_date"] . "-01";
            $end_date = $_POST["is_date"] . "-31";
            $sql = " SELECT users.id, users.name, sum(worksheets.analyst_hours) as analysthrs, sum(worksheets.image_specialist_hours) as imagehrs, sum(worksheets.medical_director_hours) as medicalhrs FROM `worksheets` JOIN users on worksheets.customer_id = users.id WHERE worksheets.date <= '" . $end_date . "' AND worksheets.date >= '" . $start_date . "' GROUP BY worksheets.customer_id";
        }

        /* if(!empty($request['search']['value'])){
          $sql.=" AND (users.id Like '".$request['search']['value']."%' ";
          $sql.=" OR users.name Like'".$request['search']['value']."%' ";
          $sql.=" OR analysthrs Like'".$request['search']['value']."%' ";
          $sql.=" OR imagehrs Like'".$request['search']['value']."%' ";
          $sql.=" OR medicalhrs Like '".$request['search']['value']."%' )";
          } */
        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);

        //Order
        /* $sql.=" ORDER BY ".$col[$request['order'][0]['column']]."   ".$request['order'][0]['dir']."  LIMIT ".
          $request['start']."  ,".$request['length']."  "; */

        $query = mysqli_query($con, $sql);

        $data = array();

        while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            $subdata[] = $row[1]; //name
            $subdata[] = $row[2]; //email
            $subdata[] = $row[3]; //created
            $subdata[] = $row[4];

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

    public function study_time_graph()
    {

        $con = $this->getConnection();

        $customer = $_POST["customer"];
        $month = $_POST["month"];
        $year = $_POST["year"];

        $result = $this->Admindb->get_study_time_graph($customer, $month, $year);

        echo json_encode(array_values($result));
        die;
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- USER REVIEW MODEL ----------------------------------
      @FUNCTION DATE              :  15-10-2018
      ------------------------------------------------------------------------------ */

    public function userreview()
    {
        $userid = $_POST['analyst'];
        $reviewid = isset($_POST['reviewid']) ? $_POST['reviewid'] : '';
        $analystHours = isset($_POST['analystHours']) ? $_POST['analystHours'] : '';
        $comments = isset($_POST['comments']) ? $_POST['comments'] : '';
        $rate = isset($_POST['rate']) ? $_POST['rate'] : '';
        echo $result = $this->Admindb->reviewcompleted($reviewid, $userid, $analystHours, $comments, $rate);
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- USER REVIEW MODEL ----------------------------------
      @FUNCTION DATE              :  13-05-2019
      ------------------------------------------------------------------------------ */

    public function getreview()
    {
        $reviewid = $_POST['reviewid'];
        $result = $this->Admindb->getreviewcompleted($reviewid);
        echo json_encode($result);
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- ANALYST RATE REPORT ---------------------------------
      @ACCESS MODIFIERS            :  PUBLIC FUNCTION
      @FUNCTION DATE               :  23-04-2019
      ------------------------------------------------------------------------------ */

    public function ratedetails()
    {
        //echo 1;

        $reviewid = $_POST['reviewid'];
        $rate = $_POST['rate'];
        $data = $this->Analyst->getratedetails($reviewid, $rate);
        echo json_encode($data);
    }

    /* ----------------------- ASSIGNED CUSTOMERS ---------------------------------
      @ACCESS MODIFIERS            :  PUBLIC FUNCTION
      @FUNCTION DATE               :  09-08-2019
      ------------------------------------------------------------------------------ */

    public function ajaxGetAssignedCustomerInfo()
    {
        $data['data'] = $this->Assigndb->getAllAssignedCustommers();
        echo json_encode($data);
        exit;
    }

    /* ----------------------- ASSIGNED CUSTOMERS DASHBOARD ---------------------------------
      @ACCESS MODIFIERS            :  PUBLIC FUNCTION
      @FUNCTION DATE               :  09-08-2019
      ------------------------------------------------------------------------------ */

    public function ajaxGetAssignedCustomerInfoDashboard()
    {
        $analyst_id = $this->user->id;
        $data['data'] = $this->Assigndb->getAssignedCustommersDashboard($analyst_id);
        echo json_encode($data);
        exit;
    }

    /* ----------------------- ASSIGNED CUSTOMERS STATUS ---------------------------------
      @ACCESS MODIFIERS            :  PUBLIC FUNCTION
      @FUNCTION DATE               :  12-08-2019
      ------------------------------------------------------------------------------ */

    public function ajaxchangeStatus()
    {
        $id_status = $_REQUEST['id'];
        $new_status = ($_REQUEST['status'] == 1) ? 5 : 1;
        $status = $this->Assigndb->statusUpdate($id_status, $new_status);
        echo json_encode($status);
    }

    /* ----------------------- ASSIGNED CUSTOMERS DELETE ---------------------------------
      @ACCESS MODIFIERS            :  PUBLIC FUNCTION
      @FUNCTION DATE               :  12-08-2019
      ------------------------------------------------------------------------------ */

    public function ajaxDeleteAdminAssigned()
    {
        $id_del = $_REQUEST['id'];
        $status = $this->Assigndb->delete('adm_admin_customer_assign', $id_del);
        echo json_encode($status);
    }

    /* ----------------------- AJAX DOCS UPLOAD ---------------------------------
      @ACCESS MODIFIERS            :  PUBLIC FUNCTION
      @FUNCTION DATE               :  24-08-2019
      ------------------------------------------------------------------------------ */

    public function ajaxUploadAgreements()
    {
        //print_r($_REQUEST);
        //print_r($_FILES);exit;
        $doc_count = (isset($_REQUEST['doc_count'])) ? $_REQUEST['doc_count'] : 0;
        $customer = (isset($_REQUEST['customer'])) ? $_REQUEST['customer'] : 0;
        $status = 0;
        $message = '';
        $allowed = array("pdf", "doc", "docx");
        if ($doc_count > 0) {
            for (
                $i = 1;
                $i <= $doc_count;
                $i++
            ) {
                $doc_title = $_REQUEST['document_title_' . $i];
                $doc_desc = $_REQUEST['document_desc_' . $i];

                $doc_name = (isset($_FILES["document_docs_" . $i]['name'])) ? $_FILES["document_docs_" . $i]['name'] : '';
                $doc_tmp_name = (isset($_FILES["document_docs_" . $i]['tmp_name'])) ? $_FILES["document_docs_" . $i]['tmp_name'] : '';
                if ($doc_tmp_name != "") {

                    $doc_type = $_FILES["document_docs_" . $i]['type'];
                    $doc_size = $_FILES["document_docs_" . $i]['size'];
                    $doc_error_msg = $_FILES["document_docs_" . $i]['error'];
                    $doc_ext = pathinfo($doc_name, PATHINFO_EXTENSION);

                    if (!in_array(strtolower($doc_ext), $allowed)) {

                        $message = "Please upload files with pdf, doc, docx extensions only..!!";
                        $status = 0;
                        break;
                    }

                    if ($doc_size > 2097152) {
                        $message = "Please upload files upto 2 MB..!!";
                        $status = 0;
                        break;
                    } else {
                        if (!empty($customer)) {
                            $fileName = strtotime(date('Y-m-d')) . mt_rand(1000, 9999) . '_' . $doc_name;
                            $path = dirname(dirname(__FILE__));

                            $createuploadDirectory = $path . '/assets/uploads/customer/' . $customer . '/';
                            if (!is_dir($createuploadDirectory)) {
                                mkdir($createuploadDirectory, 0777);
                            }
                            $upload_file = $path . '/assets/uploads/customer/' . $customer . '/' . $fileName;
                            if (isset($fileName) && file_exists($upload_file)) {
                                unlink($upload_file);
                            }
                            if (move_uploaded_file($doc_tmp_name, "$upload_file")) {
                                base64_encode($fileName);
                                $fileNamed = $fileName;
                            }

                            $cust_doc_name = (isset($fileNamed)) ? $fileNamed : '';
                            $insarr = array(
                                'customer_id' => $customer,
                                'doc_title' => $doc_title,
                                'doc_desc' => $doc_desc,
                                'doc_path' => $cust_doc_name,
                                'add_user_by' => $this->user->id,
                                'user_add_on' => date("Y-m-d H:i:s"),
                                'updated_user' => $this->user->id,
                                'user_upd_on' => date("Y-m-d H:i:s"),
                                'status' => '1',
                            );

                            //Insert to Db
                            $ins_sta = $this->Admindb->uploadCustomerDocuments($insarr);
                            if ($ins_sta) {
                                $status = $ins_sta;
                                $message = "Uploaded Successfully..!!";
                            }
                        }
                    }
                } else {
                    $status = 0;
                    $message = 'Please choose a valid file..!!';
                    break;
                }
            }
        } else {
            $status = 0;
            $message = 'Please choose a valid file..!!';
        }
        echo json_encode(array('status' => $status, 'msg' => $message));

        /* $id_del = $_REQUEST['id'];
          $status = $this->Assigndb->delete('adm_admin_customer_assign', $id_del);
          echo json_encode($status); */
    }

    /* ----------------------- AJAX BILLS UPLOAD ---------------------------------
      @ACCESS MODIFIERS            :  PUBLIC FUNCTION
      @FUNCTION DATE               :  26-08-2019
      ------------------------------------------------------------------------------ */

    public function ajaxUploadBills()
    {
        $bill_count = (isset($_REQUEST['bill_count'])) ? $_REQUEST['bill_count'] : 0;
        $customer = (isset($_REQUEST['customer'])) ? $_REQUEST['customer'] : 0;
        $status = 0;
        $message = '';
        $allowed = array("pdf", "doc", "docx");

        if ($bill_count > 0) {
            for (
                $i = 1;
                $i <= $bill_count;
                $i++
            ) {
                $bill_title = $_REQUEST['bill_title_' . $i];
                $bill_desc = $_REQUEST['bill_desc_' . $i];
                $bill_invoice = $_REQUEST['bill_invoice_' . $i];
                $bill_due = date('Y-m-d', strtotime($_REQUEST['bill_due_' . $i]));
                $month_year = explode('-', $_REQUEST['bill_date_' . $i]);
                $bill_date = (int) $month_year[0];
                $bill_date_year = (int) $month_year[1];
                $bill_total = $_REQUEST['bill_total_' . $i];
                $bill_discount = $_REQUEST['bill_discount_' . $i];
                $bill_invoice_amt = $_REQUEST['bill_invoice_amt_' . $i];

                $bill_docs = (isset($_FILES["bill_docs_" . $i]['name'])) ? $_FILES["bill_docs_" . $i]['name'] : '';
                $doc_tmp_name = (isset($_FILES["bill_docs_" . $i]['tmp_name'])) ? $_FILES["bill_docs_" . $i]['tmp_name'] : '';

                if ($doc_tmp_name != "") {

                    $bill_type = $_FILES["bill_docs_" . $i]['type'];
                    $bill_size = $_FILES["bill_docs_" . $i]['size'];
                    $bill_error_msg = $_FILES["bill_docs_" . $i]['error'];
                    $bill_ext = pathinfo($bill_docs, PATHINFO_EXTENSION);

                    if (!in_array(strtolower($bill_ext), $allowed)) {
                        $message = "Please upload files with pdf, doc, docx extensions only..!!";
                        $status = 0;
                        break;
                    }
                    if ($bill_size > 2097152) {
                        $message = "Please upload files upto 2 MB..!!";
                        $status = 0;
                        break;
                    } else {
                        if (!empty($customer)) {
                            $fileName = strtotime(date('Y-m-d')) . mt_rand(1000, 9999) . '_' . $bill_docs;
                            $path = dirname(dirname(__FILE__));
                            $createuploadDirectory = $path . '/assets/uploads/customer/bills/' . $customer . '/';
                            if (!is_dir($createuploadDirectory)) {
                                mkdir($createuploadDirectory, 0777);
                            }
                            $upload_file = $path . '/assets/uploads/customer/bills/' . $customer . '/' . $fileName;

                            if (isset($fileName) && file_exists($upload_file)) {
                                unlink($upload_file);
                            }
                            if (move_uploaded_file($doc_tmp_name, "$upload_file")) {
                                base64_encode($fileName);
                                $fileNamed = $fileName;
                            }
                            $cust_bill_name = (isset($fileNamed)) ? $fileNamed : '';

                            $insarr = array(
                                'customer_id' => $customer,
                                'bill_title' => $bill_title,
                                'bill_desc' => $bill_desc,
                                'bill_invoice' => $bill_invoice,
                                'bill_due' => $bill_due,
                                'bill_date' => $bill_date,
                                'bill_date_year' => $bill_date_year,
                                'bill_total' => $bill_total,
                                'bill_discount' => $bill_discount,
                                'bill_invoice_amt' => $bill_invoice_amt,
                                'bill_path' => $cust_bill_name,
                                'add_user_by' => $this->user->id,
                                'user_add_on' => date("Y-m-d H:i:s"),
                                'updated_user' => $this->user->id,
                                'user_upd_on' => date("Y-m-d H:i:s"),
                                'status' => '1',
                            );

                            $ins_sta = $this->Admindb->uploadCustomerBills($insarr);

                            if ($ins_sta) {
                                $status = $ins_sta;
                                $message = "Uploaded Successfully..!!";
                            }
                        } else {
                            $message = "Invalid Customer..!!";
                            $status = 0;
                            break;
                        }
                    }
                } else {
                    $status = 0;
                    $message = 'Please choose a valid file..!!';
                    break;
                }
            }
        } else {
            $status = 0;
            $message = 'Please choose a valid file..!!';
        }
        echo json_encode(array('status' => $status, 'msg' => $message));
    }

    /* ----------------------- Users STATUS ---------------------------------
      @ACCESS MODIFIERS            :  PUBLIC FUNCTION
      @FUNCTION DATE               :  25-03-2021
      ------------------------------------------------------------------------------ */

    public function ajaxChangeUserStatus()
    {
        $id_status = $_REQUEST['id'];
        $new_status = ($_REQUEST['status'] == 1) ? 0 : 1;
        $user_type = !empty($_REQUEST['item_ref']) ? $_REQUEST['item_ref'] : '';
        $status = $this->Admindb->userStatusUpdate($id_status, $new_status);
        if ($user_type == '5') {
            $client_id = $this->Admindb->getClientId($id_status);
            if (!empty($client_id)) {
                $status0 = $this->Admindb->updateStatus('clients', $client_id, 'client_id', $new_status);
            }
            $status1 = $this->Admindb->updateStatus('client_details', $id_status, 'user_ids', $new_status);
        }
        echo json_encode($status);
    }

    public function ajaxChangeAnalysisStatus()
    {
        $id_status = $_REQUEST['id'];
        $new_status = ($_REQUEST['status'] == 1) ? 0 : 1;
        $status = $this->Admindb->analysesStatusUpdate($id_status, $new_status);
        echo json_encode($status);
    }

    public function ajaxChangeAnalysisCatStatus()
    {
        $id_status = $_REQUEST['id'];
        $new_status = ($_REQUEST['status'] == 1) ? 0 : 1;
        $status = $this->Admindb->analysesCatStatusUpdate($id_status, $new_status);
        echo json_encode($status);
    }

    public function ajaxChangeCustomerStatus()
    {
        $id_status = $_REQUEST['id'];
        $new_status = ($_REQUEST['status'] == 1) ? 0 : 1;
        $status = $this->Admindb->customerStatusUpdate($id_status, $new_status);
        $client_id = $this->Admindb->getClientId($id_status);
        if (!empty($client_id)) {
            $status0 = $this->Admindb->updateStatus('clients', $client_id, 'client_id', $new_status);
        }
        $status1 = $this->Admindb->updateStatus('client_details', $id_status, 'user_ids', $new_status);
        echo json_encode($status);
    }

    public function ajaxCustomerName()
    {
        $id = $_REQUEST['id'];
        $name = $_REQUEST['name'];
        $status = 0;
        $message = '';
        if ($id > 0 && !empty($name)) {

            $ins_sta = $this->Admindb->customerNameUpdate($id, $name);
            if ($ins_sta) {
                $status = 1;
                $message = "Updated Successfully..!!";
            }
        } else {
            $message = "Invalid Customer..!!";
            $status = 0;
        }

        echo json_encode(array('status' => $status, 'msg' => $message));
    }

    public function downloaddata_completefilternew()
    {
        $con = $this->getConnection();
        $day_select = $_REQUEST['day_select'];
        $asignee_select = $_REQUEST['asignee_select'];
        $status_select = $_REQUEST['status_select'];
        $secondcheck_select = $_REQUEST['secondcheck_select'];
        $asignee_second = $_REQUEST['asignee_second'];

        $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer,cust.tat as dtat FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id  WHERE 1=1 ";

        if ($asignee_select != '') {
            $sql .= " AND assignee = '" . $asignee_select . "' ";
        }

        if ($day_select != '') {
            $sql .= " AND TIMESTAMPDIFF(DAY,Clario.created,NOW()) < '" . $day_select . "' ";
        }

        if ($status_select != '') {
            $sql .= " AND Clario.status = '" . $status_select . "' ";
        }

        if ($secondcheck_select != '') {
            if ($secondcheck_select == 1) {
                $sql .= " AND review_user_id !='' ";
                if ($asignee_second && !empty($asignee_second)) {
                    $sql .= " AND review_user_id ='" . $asignee_second . "'";
                }
            } else {
                $sql .= " AND review_user_id =''";
            }
        }

        $sql .= " ORDER BY Clario.id Desc";

        $query = mysqli_query($con, $sql);

        $data = array();

        $i = 0;

        $columnHeader = "SL No" . "\t" . "Received Date" . "\t" . "Accession" . "\t" . "Patient Name" . "\t" . "mrn" . "\t" . "Default tat" . "\t" . "webhook_customer" . "\t" . "assignee" . "\t" . "Customer" . "\t" . "Second Check" . "\t" . "Description" . "\t" . "Status" . "\t";

        $setData = '';
        $i = 0;
        // print_r($row);
        while ($row = mysqli_fetch_array($query)) {
            $i++;
            $rowData = '';

            $rowData .= $i . "\t";

            $originalDate = $row[1];
            $review_name = $this->Admindb->get_name_by_id($row[10]);
            $assign_customer = $this->Admindb->get_name_by_id($row[11]);
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $rowData .= '"' . $newDate . '"' . "\t";
            $rowData .= '"' . $row[2] . '"' . "\t";
            // $rowData .= '"' . $row[0] . '"' . "\t";


            $rowData .= '"' . $row[3] . '"' . "\t";
            $rowData .= '"' . $row[4] . '"' . "\t";
            if (!empty($row[5])) {
                $rowfive = $row[5] . " hrs";
            } else {
                $rowfive = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
            }
            $rowData .= '"' . $rowfive . '"' . "\t";
            $rowData .= '"' . $row[6] . '"' . "\t";
            $assign = $row[7] ?: "Not Assigned";
            $customerna = (empty($assign_customer)) ? "" : $assign_customer;
            $secondcheckna = (empty($review_name)) ? "Not Reviewed" : $review_name;
            $rowData .= '"' . $assign . '"' . "\t";
            $rowData .= '"' . $customerna . '"' . "\t";
            $rowData .= '"' . $secondcheckna . '"' . "\t";
            $rowData .= '"' . $row[8] . '"' . "\t";
            if ($row[9] == '') {
                $st = "Not Assigned";
            } else {
                $st = $row[9];
            }

            $rowData .= '"' . $st . '"' . "\t";
            $setData .= trim($rowData) . "\n";

            // exit; 
            /* echo $row[2];
              echo $row[3];
              echo $row[4];
              echo $row[5];
              echo $row[6];
              echo $row[9];
              echo $row[11]; */

            //echo $i;
        }
        ob_end_clean();
        //  header("Content-type: application/vnd.ms-excel");
        //   header("Content-Disposition: attachment; filename=data.xlx");
        //   header("Expires: 0");
        //  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        //  header("Cache-Control: private", false);
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=file.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        echo ucwords(trim($columnHeader) . "\n" . trim($setData) . "\n");
    }

    public function downloaddata_completefilternewcsv_studyrp()
    {

        $con = $this->getConnection();

        $request = $_REQUEST;

        //       

        $sql = "SELECT worksheets.id, worksheets.date, worksheets.customer_id, Clario.patient_name, worksheets.analyst_hours, worksheets.expected_time, worksheets.image_specialist_hours, worksheets.medical_director_hours, users.name, worksheets.status,worksheets.any_mint,worksheets.analyses_ids FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id LEFT JOIN users ON worksheets.analyst = users.id";

        $query = mysqli_query($con, $sql);

        $totalData = mysqli_num_rows($query);

        $sql = "SELECT worksheets.id, worksheets.date, worksheets.customer_id, Clario.patient_name, worksheets.analyst_hours,worksheets.expected_time,worksheets.image_specialist_hours,worksheets.medical_director_hours, users.name, worksheets.status,worksheets.any_mint,worksheets.analyses_ids FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id LEFT JOIN users ON worksheets.analyst = users.id WHERE 1=1";

        if (!empty($_POST["is_day"]) && $_POST["is_assignee"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (!empty($_POST["is_assignee"]) && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
        }

        if (!empty($_POST["is_customer"]) && $_POST["is_assignee"] == 0 && $_POST["is_day"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' ";
        }

        if (!empty($_POST["is_status"]) && $_POST["is_assignee"] == 0 && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' ";
        }

        if (!empty($_POST["is_time_mgmt"]) && $_POST["is_assignee"] == 0 && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0) {
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }




        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (!empty($_POST["is_day"]) && !empty($_POST["is_customer"]) && $_POST["is_assignee"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (!empty($_POST["is_day"]) && !empty($_POST["is_status"]) && $_POST["is_assignee"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (!empty($_POST["is_day"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_assignee"] == 0 && $_POST["is_status"] == 0 && $_POST["is_customer"] == 0) {
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
            }
        }



        // combination of 2 assignee

        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && $_POST["is_day"] == 0 && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' ";
        }

        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_status"]) && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
        }

        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_day"] == 0 && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0) {

            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
            }
        }



        // combination of 2 - customer      
        if (!empty($_POST["is_status"]) && !empty($_POST["is_customer"]) && $_POST["is_day"] == 0 && $_POST["is_assignee"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' ";
        }

        if (!empty($_POST["is_time_mgmt"]) && !empty($_POST["is_customer"]) && $_POST["is_day"] == 0 && $_POST["is_assignee"] == 0 && $_POST["is_status"] == 0) {

            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }

        // combination of 2 - status

        if (!empty($_POST["is_time_mgmt"]) && !empty($_POST["is_status"]) && $_POST["is_day"] == 0 && $_POST["is_assignee"] == 0 && $_POST["is_customer"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }



        if (!empty($_POST["is_customer"]) && !empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_status"]) && !empty($_POST["is_time_mgmt"])) {

            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }

        //combinations of three 

        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && $_POST["is_status"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_status"]) && $_POST["is_customer"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }

        if (!empty($_POST["is_day"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_status"]) && $_POST["is_assignee"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
        }


        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_customer"] == 0 && $_POST["is_status"] == 0) {

            $sql .= " AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }



        if (!empty($_POST["is_day"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_assignee"] == 0 && $_POST["is_status"] == 0) {

            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }


        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_status"]) && $_POST["is_day"] == 0 && $_POST["is_time_mgmt"] == 0) {

            $sql .= " AND worksheets.status = '" . $_POST["is_status"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
        }

        if (!empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_day"] == 0 && $_POST["is_status"] == 0) {
            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }


        if (!empty($_POST["is_status"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_day"] == 0 && $_POST["is_assignee"] == 0) {
            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }


        // combinations of 4

        if (!empty($_POST["is_day"]) && !empty($_POST["is_status"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_assignee"] == 0) {
            $sql .= " AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }


        if (!empty($_POST["is_day"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_status"] == 0) {
            $sql .= " AND TIMESTAMPDIFF(DAY,worksheets.date,NOW()) < '" . $_POST["is_day"] . "' AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }


        if (!empty($_POST["is_status"]) && !empty($_POST["is_assignee"]) && !empty($_POST["is_customer"]) && !empty($_POST["is_time_mgmt"]) && $_POST["is_day"] == 0) {
            $sql .= " AND worksheets.customer_id = '" . $_POST["is_customer"] . "' AND worksheets.analyst = '" . $_POST["is_assignee"] . "' AND worksheets.status = '" . $_POST["is_status"] . "' ";
            if ($_POST["is_time_mgmt"] == "AtVsEat") {
                $sql .= " AND worksheets.analyst_hours > worksheets.expected_time ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "EatVsAt") {
                $sql .= " AND worksheets.expected_time > worksheets.analyst_hours ";
                $sql .= " AND worksheets.analyst_hours > 0 AND worksheets.expected_time > 0";
            } else if ($_POST["is_time_mgmt"] == "TimeNotAdded") {
                $sql .= " AND worksheets.analyst_hours <= 0 ";
            }
        }





        if (!empty($request['search']['value'])) {
            $sql .= " AND (worksheets.id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.date Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.mrn Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.customer_id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.accession Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR Clario.patient_name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.analyst_hours Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.expected_time Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.image_specialist_hours Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.medical_director_hours Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR users.name Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR worksheets.status Like '%" . $request['search']['value'] . "%' )";
        }




        $query = mysqli_query($con, $sql);
        $totalData = mysqli_num_rows($query);
        $totalFilter = $totalData;

        //Order
        //  $sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
        //    $request['start'] . "  ," . $request['length'] . "  ";

        $sql .= " ORDER BY worksheets.date Desc";

        $query = mysqli_query($con, $sql);

        $data = array();
        $current_customers = array();

        $columnHeader = "SL No" . "\t" . "Date" . "\t" . "Customer" . "\t" . "Patient Name" . "\t" . "AT [Minutes]" . "\t" . "EAT [Minutes]" . "\t" . "Assignee" . "\t" . "Time Difference" . "\t" . "Status" . "\t";

        $setData = '';
        //$i = 0;
        $sno = 0;
        $user_arr = array();
        $user_arr[] = array("SL No", "Date", "Customer", "Patient Name", "AT [Minutes]", "EAT [Minutes]", "Time Difference", "Assignee", "Status");

        while ($row = mysqli_fetch_array($query)) {
            // $i++;
            $sno++;
            $originalDate = $row[1];
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));

            $subdata1 = $newDate; //
            if (!in_array($row[2], $current_customers)) {
                $current_customers[] = $row[2];
            }
            $row[2] = $this->Admindb->get_name_by_id($row[2]);
            $subdata2 = $row[2];

            $subdata3 = $row[3];

            if (!$row[4] > 0) {
                $row[4] = 'Time Not Added';
            } else {
                $row[4] = number_format($row[4] * 60, 2);
                $row[5] = number_format($row[5] * 60, 2);
            }


            $workTimeTotal = 0;
            $excateTotalTime = 0;
            if (!empty($row[10])) {

                $workTime = explode(',', $row[10]);
                for (
                    $i = 0;
                    $i < count($workTime);
                    $i++
                ) {
                    $workTimeTotal = $workTimeTotal + $workTime[$i];
                }
            }
            if (!empty($row[11])) {

                $excateAnslyses = explode(',', $row[11]);
                for (
                    $j = 0;
                    $j < count($excateAnslyses);
                    $j++
                ) {
                    $timeValue = $this->Analyst->getexacttime($excateAnslyses[$j]);
                    $excateTotalTime = $excateTotalTime + $timeValue;
                }
            }
            $timeDff = $excateTotalTime - $workTimeTotal;
            $timeDffa = $excateTotalTime - $workTimeTotal;

            // Analyst Hours -3
            $subdata4 = $workTimeTotal; //$excateTotalTime;//$row[4];
            // Expected Time -4
            $subdata5 = $excateTotalTime; //$row[5];
            // Image Specialist Hours -5
            // $subdata[] = $excateTotalTime;
            //number_format($row[6]*60,2);
            // Medical Director Hours -6
            //$subdata[] = number_format($row[7]*60,2);
            // Assignee -7  
            $subdata6 = $timeDffa;
            $subdata7 = $row[8];

            $subdata8 = $row[9];

            $user_arr[] = array($sno, $subdata1, $subdata2, $subdata3, $subdata4, $subdata5, $subdata6, $subdata7, $subdata8);
        }

        $filename = 'Study_Time_Report.csv';
        $file = fopen($filename, "w");

        foreach ($user_arr as $line) {
            fputcsv($file, $line);
        }
        fclose($file);

        // download
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv; ");

        readfile($filename);

        // deleting file
        unlink($filename);
        exit();
    }

    public function downloaddata_completefilternewcsv()
    {
        $con = $this->getConnection();
        $day_select = $_REQUEST['day_select'];
        $asignee_select = $_REQUEST['asignee_select'];
        $status_select = $_REQUEST['status_select'];
        $secondcheck_select = $_REQUEST['secondcheck_select'];
        $asignee_second = $_REQUEST['asignee_second'];

        $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer,cust.tat as dtat FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id  WHERE 1=1 ";

        if ($asignee_select != '') {
            $sql .= " AND assignee = '" . $asignee_select . "' ";
        }

        if ($day_select != '') {
            $sql .= " AND TIMESTAMPDIFF(DAY,Clario.created,NOW()) < '" . $day_select . "' ";
        }

        /* if ($status_select != '') {
          $sql .= " AND Clario.status = '" . $status_select . "' ";
          } */

        if ($status_select != '') {
            if ($status_select != 'Not Assigned') {
                $sql .= " AND Clario.status = '" . $status_select . "' ";
            }
            if ($status_select == 'Not Assigned') {
                $sql .= " AND Clario.status = '' ";
            }
        }

        if ($secondcheck_select != '') {
            if ($secondcheck_select == 1) {
                $sql .= " AND review_user_id !='' ";
                if ($asignee_second && !empty($asignee_second)) {
                    $sql .= " AND review_user_id ='" . $asignee_second . "'";
                }
            } else {
                $sql .= " AND review_user_id =''";
            }
        }

        $sql .= " ORDER BY Clario.id Desc";

        $query = mysqli_query($con, $sql);

        $data = array();

        $i = 0;

        $columnHeader = "SL No" . "\t" . "Received Date" . "\t" . "Accession" . "\t" . "Patient Name" . "\t" . "mrn" . "\t" . "Default tat" . "\t" . "webhook_customer" . "\t" . "assignee" . "\t" . "Customer" . "\t" . "Second Check" . "\t" . "Description" . "\t" . "Status" . "\t";

        $setData = '';
        $i = 0;
        // print_r($row);
        $user_arr = array();
        $user_arr[] = array("SL No", "Received Date", "Accession", "Patient Name", "mrn", "Default tat", "webhook_customer", "assignee", "Customer", "Second Check", "Description", "Status");
        while ($row = mysqli_fetch_array($query)) {
            $i++;

            $no .= $i;

            $originalDate = $row[1];
            $review_name = $this->Admindb->get_name_by_id($row[10]);
            $assign_customer = $this->Admindb->get_name_by_id($row[11]);

            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $rowDataa = $row[2];

            $rowDatab = $row[3];
            $rowDatac = $row[4];
            if (!empty($row[5])) {
                $rowfive = $row[5] . " hrs";
            } else {
                $rowfive = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
            }
            $rowDatad = $rowfive;
            $rowDatae = $row[6];
            $assign = $row[7] ?: "Not Assigned";
            $customerna = (empty($assign_customer)) ? "" : $assign_customer;
            $secondcheckna = (empty($review_name)) ? "Not Reviewed" : $review_name;
            $rowDataf = $assign;
            $rowDatag = $customerna;
            $rowDatah = $secondcheckna;
            $rowDatai = $row[8];
            if ($row[9] == '') {
                $st = "Not Assigned";
            } else {
                $st = $row[9];
            }

            $rowDataj = $st;
            // $setData .= trim($rowData) . "\n";
            $user_arr[] = array($i, $newDate, $rowDataa, $rowDatab, $rowDatac, $rowDatad, $rowDatae, $rowDataf, $rowDatag, $rowDatah, $rowDatai, $rowDataj);

            // exit; 
            /* echo $row[2];
              echo $row[3];
              echo $row[4];
              echo $row[5];
              echo $row[6];
              echo $row[9];
              echo $row[11]; */

            //echo $i;
        }
        $filename = 'all_studies.csv';
        $file = fopen($filename, "w");

        foreach ($user_arr as $line) {
            fputcsv($file, $line);
        }
        fclose($file);

        // download
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv; ");

        readfile($filename);

        // deleting file
        unlink($filename);
        exit();
    }

    /*  ob_end_clean();
      //  header("Content-type: application/vnd.ms-excel");
      //   header("Content-Disposition: attachment; filename=data.xlx");
      //   header("Expires: 0");
      //  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      //  header("Cache-Control: private", false);
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=file.csv");
      header("Pragma: no-cache");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: private", false);
      echo ucwords(trim($columnHeader) . "\n" . trim($setData) . "\n");
      } */

    public function downloaddata_monthlyfilternewcsv()
    {
        $con = $this->getConnection();
        $day_select = $_REQUEST['day_select'];
        $asignee_select = $_REQUEST['asignee_select'];
        $status_select = $_REQUEST['status_select'];
        $secondcheck_select = $_REQUEST['secondcheck_select'];
        $asignee_second = $_REQUEST['asignee_second'];

        $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer, cust.tat  as dtat FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id WHERE MONTH(Clario.created) = MONTH(CURRENT_DATE()) AND YEAR(Clario.created) = YEAR(CURRENT_DATE())";

        if ($asignee_select != '') {
            $sql .= " AND assignee = '" . $asignee_select . "' ";
        }

        if ($day_select != '') {
            $sql .= " AND TIMESTAMPDIFF(DAY,Clario.created,NOW()) < '" . $day_select . "' ";
        }

        /* if ($status_select != '') {
          $sql .= " AND Clario.status = '" . $status_select . "' ";
          } */
        if ($status_select != '') {
            if ($status_select != 'Not Assigned') {
                $sql .= " AND Clario.status = '" . $status_select . "' ";
            }
            if ($status_select == 'Not Assigned') {
                $sql .= " AND Clario.status = '' ";
            }
        }

        if ($secondcheck_select != '') {
            if ($secondcheck_select == 1) {
                $sql .= " AND review_user_id !='' ";
                if ($asignee_second && !empty($asignee_second)) {
                    $sql .= " AND review_user_id ='" . $asignee_second . "'";
                }
            } else {
                $sql .= " AND review_user_id =''";
            }
        }

        $sql .= " ORDER BY Clario.id Desc";

        $query = mysqli_query($con, $sql);

        $data = array();

        $i = 0;

        $columnHeader = "SL No" . "\t" . "Received Date" . "\t" . "Accession" . "\t" . "Patient Name" . "\t" . "mrn" . "\t" . "Default tat" . "\t" . "webhook_customer" . "\t" . "assignee" . "\t" . "Customer" . "\t" . "Second Check" . "\t" . "Description" . "\t" . "Status" . "\t";

        $setData = '';
        $i = 0;
        // print_r($row);
        $user_arr = array();
        $user_arr[] = array("SL No", "Received Date", "Accession", "Patient Name", "mrn", "Default tat", "webhook_customer", "assignee", "Customer", "Second Check", "Description", "Status");
        while ($row = mysqli_fetch_array($query)) {
            $i++;

            $no .= $i;

            $originalDate = $row[1];
            $review_name = $this->Admindb->get_name_by_id($row[10]);
            $assign_customer = $this->Admindb->get_name_by_id($row[11]);

            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $rowDataa = $row[2];

            $rowDatab = $row[3];
            $rowDatac = $row[4];
            if (!empty($row[5])) {
                $rowfive = $row[5] . " hrs";
            } else {
                $rowfive = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
            }
            $rowDatad = $rowfive;
            $rowDatae = $row[6];
            $assign = $row[7] ?: "Not Assigned";
            $customerna = (empty($assign_customer)) ? "" : $assign_customer;
            $secondcheckna = (empty($review_name)) ? "Not Reviewed" : $review_name;
            $rowDataf = $assign;
            $rowDatag = $customerna;
            $rowDatah = $secondcheckna;
            $rowDatai = $row[8];
            if ($row[9] == '') {
                $st = "Not Assigned";
            } else {
                $st = $row[9];
            }

            $rowDataj = $st;
            // $setData .= trim($rowData) . "\n";
            $user_arr[] = array($i, $newDate, $rowDataa, $rowDatab, $rowDatac, $rowDatad, $rowDatae, $rowDataf, $rowDatag, $rowDatah, $rowDatai, $rowDataj);

            // exit; 
            /* echo $row[2];
              echo $row[3];
              echo $row[4];
              echo $row[5];
              echo $row[6];
              echo $row[9];
              echo $row[11]; */

            //echo $i;
        }
        $filename = 'monthly_report.csv';
        $file = fopen($filename, "w");

        foreach ($user_arr as $line) {
            fputcsv($file, $line);
        }
        fclose($file);

        // download
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv; ");

        readfile($filename);

        // deleting file
        unlink($filename);
        exit();
    }

    /*  ob_end_clean();
      //  header("Content-type: application/vnd.ms-excel");
      //   header("Content-Disposition: attachment; filename=data.xlx");
      //   header("Expires: 0");
      //  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      //  header("Cache-Control: private", false);
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=file.csv");
      header("Pragma: no-cache");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: private", false);
      echo ucwords(trim($columnHeader) . "\n" . trim($setData) . "\n");
      } */

    public function users_csv()
    {
        $con = $this->getConnection();

        $sql = "SELECT user_id, user_name, email, created_at, is_active, user_type_ids FROM users ORDER BY user_id Desc";

        $query = mysqli_query($con, $sql);

        $data = array();

        $columnHeader = "SL No" . "\t" . "Name" . "\t" . "Email" . "\t" . "User Type" . "\t" . "Created" . "\t" . "Status" . "\t";

        $setData = '';
        $i = 0;
        $user_arr = array();
        $user_arr[] = array("SL No", "Name", "Email", "User Type", "Created", "Status");
        while ($row = mysqli_fetch_array($query)) {
            $i++;

            //  $no .= $i;

            $usergroup = $this->user_group_name($row[5]);
            $rowDataa = $row[1];

            $rowDatab = $row[2];
            $rowDatac = $usergroup;
            $originalDate = $row[3];
            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $rowDatad = $newDate;
            if ($row['is_active'] == 1) {
                $status_a = 'Active';
            } else {
                $status_a = 'Inactive';
            }
            $rowDatae = $status_a;
            $user_arr[] = array($i, $rowDataa, $rowDatab, $rowDatac, $rowDatad, $rowDatae);
        }

        $filename = '/tmp/users.csv'; // Use a writable directory
        $file = fopen($filename, "w");

        foreach ($user_arr as $line) {
            fputcsv($file, $line);
        }
        fclose($file);

        // download
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . basename($filename));
        header("Content-Type: application/csv; ");
        readfile($filename);

        // deleting file
        unlink($filename);
        exit();
    }

    public function downloadstat_completefilternewcsv()
    {
        $con = $this->getConnection();
        $day_select = $_REQUEST['day_select'];
        $asignee_select = $_REQUEST['asignee_select'];
        $status_select = $_REQUEST['status_select'];
        $secondcheck_select = $_REQUEST['secondcheck_select'];
        $asignee_second = $_REQUEST['asignee_second'];
        $analysis_perfomed = $_REQUEST['analysis_perfomed'];
        $sql = "SELECT Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer, users.name, Clario.webhook_description,Clario.status,Clario.review_user_id, Clario.customer as assign_customer,cust.tat as dtat, Clario.last_modified, worksheets.clario_id,worksheets.analyses_performed,worksheets.analyses_ids FROM Clario LEFT JOIN users on Clario.assignee = users.id LEFT JOIN users as cust on Clario.customer = cust.id LEFT JOIN worksheets on Clario.id = worksheets.clario_id WHERE 1=1 ";

        if ($asignee_select != '') {
            $sql .= " AND assignee = '" . $asignee_select . "' ";
        }

        if ($day_select != '') {
            $sql .= " AND TIMESTAMPDIFF(DAY,Clario.created,NOW()) < '" . $day_select . "' ";
        }

        /* if ($status_select != '') {
          $sql .= " AND Clario.status = '" . $status_select . "' ";
          } */

        if ($status_select != '') {
            if ($status_select != 'Not Assigned') {
                $sql .= " AND Clario.status = '" . $status_select . "' ";
            }
            if ($status_select == 'Not Assigned') {
                $sql .= " AND Clario.status = '' ";
            }
        }

        if ($secondcheck_select != '') {
            if ($secondcheck_select == 1) {
                $sql .= " AND Clario.review_user_id !='' ";
                if ($asignee_second && !empty($asignee_second)) {
                    $sql .= " AND Clario.review_user_id ='" . $asignee_second . "'";
                }
            } else {
                $sql .= " AND Clario.review_user_id =''";
            }
        }

        /*   if ($analysis_perfomed != '') {
          $sql .= " AND worksheets.analyses_ids IN (" . $analysis_perfomed . ") ";
          }
          else{
          $sql .= " AND worksheets.analyses_ids IN (60,61,62) ";
          } */

        if (isset($_POST['analysis_perfomed']) && !empty($_POST['analysis_perfomed'])) {
            $ans_id = $_POST['analysis_perfomed'];
            $sql .= " AND worksheets.analyses_ids LIKE '%" . $ans_id . "%' ";
        } else {
            $sql .= " AND (worksheets.analyses_ids LIKE '%60%' OR worksheets.analyses_ids LIKE '%61%' OR worksheets.analyses_ids LIKE '%62%') ";
        }
        $sql .= " ORDER BY Clario.id Desc";

        $query = mysqli_query($con, $sql);

        $data = array();
        $columnHeader = "SL No" . "\t" . "Received Date" . "\t" . "Accession" . "\t" . "Patient Name" . "\t" . "mrn" . "\t" . "Default tat" . "\t" . "webhook_customer" . "\t" . "assignee" . "\t" . "Customer" . "\t" . "Second Check" . "\t" . "Description" . "\t" . "Analyses Perfomed" . "\t" . "Status" . "\t";

        $setData = '';
        $i = 0;
        // print_r($row);
        $user_arr = array();
        $user_arr[] = array("SL No", "Received Date", "Accession", "Patient Name", "mrn", "Default tat", "webhook_customer", "assignee", "Customer", "Second Check", "Description", "Analyses Perfomed", "Status");
        while ($row = mysqli_fetch_array($query)) {
            $i++;

            //$no .= $i;

            $originalDate = $row[1];
            $review_name = $this->Admindb->get_name_by_id($row[10]);
            $assign_customer = $this->Admindb->get_name_by_id($row[11]);

            $newDate = date("m-d-Y h:i:s A", strtotime($originalDate));
            $rowDataa = $row[2];

            $rowDatab = $row[3];
            $rowDatac = $row[4];
            if (!empty($row[5])) {
                $rowfive = $row[5] . " hrs";
            } else {
                $rowfive = (!empty($row[12])) ? $row[12] . " hrs" : '0 hrs';
            }
            $rowDatad = $rowfive;
            $rowDatae = $row[6];
            $assign = $row[7] ?: "Not Assigned";
            $customerna = (empty($assign_customer)) ? "" : $assign_customer;
            $secondcheckna = (empty($review_name)) ? "Not Reviewed" : $review_name;
            $rowDataf = $assign;
            $rowDatag = $customerna;
            $rowDatah = $secondcheckna;
            $rowDatai = $row[8];
            $rowDataanp = $row[15];
            if ($row[9] == '') {
                $st = "Not Assigned";
            } else {
                $st = $row[9];
            }

            $rowDataj = $st;
            // $setData .= trim($rowData) . "\n";
            $user_arr[] = array($i, $newDate, $rowDataa, $rowDatab, $rowDatac, $rowDatad, $rowDatae, $rowDataf, $rowDatag, $rowDatah, $rowDatai, $rowDataanp, $rowDataj);

            // exit; 
            /* echo $row[2];
              echo $row[3];
              echo $row[4];
              echo $row[5];
              echo $row[6];
              echo $row[9];
              echo $row[11]; */

            //echo $i;
        }
        $filename = 'Stat_Report.csv';
        $file = fopen($filename, "w");

        foreach ($user_arr as $line) {
            fputcsv($file, $line);
        }
        fclose($file);

        // download
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/csv; ");

        readfile($filename);

        // deleting file
        unlink($filename);
        exit();
    }

    public function get_billing_pdf_old()
    {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $custumers = $_POST['custumers'];

        $data['wsheet'] = $this->Admindb->billing_summary_detailed_new($custumers, $start_date, $end_date);

        $custmernames = $this->Admindb->get_custmernames($custumers);

        $data['custmernames'] = $custmernames;

        // print_r($row);
        $user_arr = array();

        foreach ($custumers as $kk) {
            $i = 0;
            $cusfullname = $custmernames[$kk]['name'];
            $user_arr[] = array($cusfullname);
            $user_arr[] = array("SL No", "Name", "MRN", "Exam Date", "Comments", "Analysis Performed", "Analyst Hours", "PIA Analysis Code", "Study Price");
            $customer_code = $this->Admindb->get_usermeta_by_id($kk);
            //echo $kk;
            // echo $customer_code;
            if (isset($data['wsheet'])) {
                // print_r($data['wsheet'][$kk]);
                foreach ($data['wsheet'][$kk] as $key => $wsheets) {
                    $i++;
                    $work_detials = $this->Admindb->worksheet_detials($wsheets['id']);
                    //echo $wsheets['patient_name'];
                    $a = $wsheets['patient_name'];
                    // echo $wsheets['mrn'];
                    $b = $wsheets['mrn'];
                    $date = new DateTime($wsheets['date']);
                    //echo $date->format('m-d-Y');
                    $c = $date->format('m-d-Y');
                    //echo $wsheets['custom_analysis_description'];
                    $d = $wsheets['custom_analysis_description'];

                    $descriptionVal = "";

                    foreach ($work_detials as $key => $work_det) {
                        $ans_name = $this->Admindb->get_analysis_details($work_det['ans_id'], $kk);
                        if (!empty($ans_name['analysis_description'])) {
                            $descriptionVal .= $ans_name['analysis_description'] . ',' . '<br/>';
                        }
                    }
                    $descriptionVal = substr($descriptionVal, 0, -6);
                    $e = (!empty($descriptionVal)) ? $descriptionVal : 'NO ANALYSIS PERFORMED';
                    // echo $wsheets['analyst_hours'];
                    $f = $wsheets['analyst_hours'];

                    $ans_nameVal = "";
                    foreach ($work_detials as $key => $work_det) {
                        $ans_name = $this->Admindb->get_analysis_details($work_det['ans_id'], $kk);
                        if (!empty($ans_name['code'])) {
                            $ans_nameVal .= $customer_code['customer_code'] . '-' . $ans_name['code'] . ',<br/>';
                        }
                    }
                    $ans_nameVal = substr($ans_nameVal, 0, -6);
                    // echo  (!empty($ans_nameVal))?$ans_nameVal:'N/A';
                    $g = (!empty($ans_nameVal)) ? $ans_nameVal : 'N/A';

                    $cost = '';
                    foreach ($work_detials as $key => $work_det) {
                        $cost = $work_det['rate'];
                    }
                    if (!empty($cost)) {
                        // echo '$ '.number_format($cost,2);
                        $h = '$ ' . number_format($cost, 2);
                        //echo $h;
                    }
                    //echo $h;
                    $user_arr[] = array($i, $a, $b, $c, $d, $e, $f, $g, $h);
                }
            }
        }
        $t = time();
        $newDate = date("m-d-Y h:i:s A", strtotime($t));
        $num = rand(100, 10000);
        $fna = "billing_summary" . $num . ".csv";

        $filename = 'email_file/' . $fna;

        if (file_exists($filename)) {
            if (!unlink($filename)) {
                die("Error deleting file $filename");
            }
        }

        $file = fopen($filename, "w");

        foreach ($user_arr as $line) {
            fputcsv($file, $line);
        }
        fclose($file);

        require 'phpmailer/phpmailer/src/Exception.php';
        require 'phpmailer/phpmailer/src/PHPMailer.php';
        require 'phpmailer/phpmailer/src/SMTP.php';

        $mail = new PHPMailer(true);
        //    $emailaddress = "j4jiljokg@gmail.com";
        //  $emailaddress = "jomyak123@gmail.com";
        $emailaddress = "chithrachippy@gmail.com";

        //Server settings
        $mail->isSMTP();                              //Send using SMTP
        $mail->Host = 'smtp.gmail.com';       //Set the SMTP server to send through
        $mail->SMTPAuth = true;             //Enable SMTP authentication
        $mail->Username = 'j4jiljokg@gmail.com';   //SMTP write your email
        $mail->Password = 'hzib fuan kewh ibcm';       //SMTP password
        // $mail->Username   = 'taskmanagement78@gmail.com';   //SMTP write your email
        // $mail->Password   = 'qibq tqgs aqjk mcuc';       //SMTP password

        $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
        $mail->Port = 465;

        //Recipients
        $mail->setFrom($emailaddress, "PIA MEDICALS"); // Sender Email and name
        $mail->addAddress($emailaddress);     //Add a recipient email  
        $mail->addReplyTo($emailaddress, "PIA MEDICALS"); // reply to sender email
        //Content
        $mail->isHTML(true);               //Set email format to HTML
        $mail->Subject = "Detailed Billing Report";   // email subject headings
        $value = SITE_URL . "/" . $filename;

        $mail->Body = "Detailed Billing Report attached here please download  <a href=" . $value . " target=_blank download>download</a>";
        $mail->send();

        exit();
    }

    public function get_billing_pdf()
    {
        // error_reporting(E_ALL);
        // ini_set('display_errors', '1');
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $custumers = $_POST['custumers'];
        $email = trim($_POST['email']);

        //   $data['wsheet'] = $this->Admindb->billing_summary_detailed_new($custumers, $start_date, $end_date);
        $data['wsheet'] = $this->Admindb->billing_summary_detailed_basic($custumers, $start_date, $end_date);

        $custmernames = $this->Admindb->get_custmernames($custumers);

        $data['custmernames'] = $custmernames;

        $user_arr = array();

        $daterange = "Detailed Billing Report Between " . $start_date . " -  " . $end_date . "";
        $user_arr[] = array($daterange);
        if (!empty($custumers)) {
            foreach ($custumers as $kk) {
                $i = 0;
                $cusfullname = $custmernames[$kk]['name'];
                $user_arr[] = array($cusfullname);
                $user_arr[] = array("SL No", "Name", "MRN", "Exam Date", "Comments", "Analysis Performed", "Analyst Hours", "PIA Analysis Code", "Study Price");
                $customer_code = $this->Admindb->get_usermeta_by_id($kk);

                if (isset($data['wsheet'])) {

                    if (!empty($data['wsheet'][$kk])) {
                        foreach ($data['wsheet'][$kk] as $key => $wsheets) {
                            $i++;
                            $work_detials = $this->Admindb->worksheet_detials($wsheets['id']);

                            $a = $wsheets['patient_name'];

                            $b = $wsheets['mrn'];
                            $date = new DateTime($wsheets['date']);

                            $c = $date->format('m-d-Y');

                            $d = $wsheets['custom_analysis_description'];

                            $descriptionVal = "";

                            if (!empty($work_detials)) {
                                foreach ($work_detials as $key => $work_det) {
                                    //  $ans_name = $this->Admindb->get_analysis_details($work_det['ans_id'], $kk);
                                    $ans_name = $this->Admindb->get_analysis_details_description($work_det['ans_id'], $kk);
                                    if (!empty($ans_name['analysis_description'])) {
                                        $descriptionVal .= $ans_name['analysis_description'] . ", ";
                                    }
                                }
                                // $descriptionVal = substr($descriptionVal, 0, -6);
                                $descriptionVal = rtrim($descriptionVal, ", ");
                            }

                            $e = (!empty($descriptionVal)) ? $descriptionVal : 'NO ANALYSIS PERFORMED';

                            $f = $wsheets['analyst_hours'];

                            $ans_nameVal = "";
                            if (!empty($work_detials)) {
                                foreach ($work_detials as $key => $work_det) {
                                    // $ans_name = $this->Admindb->get_analysis_details($work_det['ans_id'], $kk);
                                    $ans_name = $this->Admindb->get_analysis_code($work_det['ans_id'], $kk);
                                    if (!empty($ans_name['code'])) {
                                        $ans_nameVal .= $customer_code['customer_code'] . '-' . $ans_name['code'] . ", ";
                                    }
                                }
                                //   $ans_nameVal = substr($ans_nameVal, 0, -6);
                                $ans_nameVal = rtrim($ans_nameVal, ", ");
                            }

                            $g = (!empty($ans_nameVal)) ? $ans_nameVal : 'N/A';

                            $cost = '';

                            if (!empty($work_detials)) {
                                foreach ($work_detials as $key => $work_det) {
                                    $cost = $work_det['rate'];
                                }
                            }

                            if (!empty($cost)) {
                                $h = '$ ' . number_format($cost, 2);
                            }
                            $user_arr[] = array($i, $a, $b, $c, $d, $e, $f, $g, $h);
                        }
                    }
                }
            }
        }

        $ret = 0;
        if (!empty($user_arr)) {
            //  $t = time();
            // $newDate = date("m-d-Y h:i:s A", strtotime($t));
            //$num = rand(100, 10000);
            //$fna = "billing_summary" . $num . ".csv";
            $fna = "billing_summary_" . date("m-d-Y") . ".csv";

            $filename = 'email_file/' . $fna;

            $file = fopen($filename, "w");

            foreach ($user_arr as $line) {
                fputcsv($file, $line);
            }
            fclose($file);

            $ret = $this->send_smtp_mail($filename, $email);
        }

        echo json_encode(array("success" => $ret));

        exit();
    }

    public function send_smtp_mail($filename, $email)
    {
        require 'phpmailer/phpmailer/src/Exception.php';
        require 'phpmailer/phpmailer/src/PHPMailer.php';
        require 'phpmailer/phpmailer/src/SMTP.php';

        $mail = new PHPMailer(true);
        //    $emailaddress = "j4jiljokg@gmail.com";
        // $emailaddress = "j4jiljokg@gmail.com";
        //Server settings
        $mail->isSMTP();                              //Send using SMTP
        $mail->Host = 'smtp.gmail.com';       //Set the SMTP server to send through
        $mail->SMTPAuth = true;             //Enable SMTP authentication
        $mail->Username = 'j4jiljokg@gmail.com';   //SMTP write your email
        $mail->Password = 'hzib fuan kewh ibcm';       //SMTP password
        // $mail->Username   = 'taskmanagement78@gmail.com';   //SMTP write your email
        // $mail->Password   = 'qibq tqgs aqjk mcuc';       //SMTP password

        $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
        $mail->Port = 465;

        //Recipients
        $mail->setFrom($email, "PIA MEDICALS"); // Sender Email and name
        $mail->addAddress($email);     //Add a recipient email  
        $mail->addReplyTo($email, "PIA MEDICALS"); // reply to sender email
        //Content
        $mail->isHTML(true);               //Set email format to HTML
        $mail->Subject = "Detailed Billing Report";   // email subject headings
        //$value = SITE_URL . "/" . $filename;
        //$mail->Body = "Detailed Billing Report attached here please download  <a href=" . $value . " target='_blank' download =''>download</a>";
        $mail->Body = "Please find the detailed billing report attached here.";
        $mail->addAttachment($filename);

        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_billing_detail_ajax()
    {
        $data['user'] = $this->user;
        $ids = [];
        $form_data = $_POST;
        if (!empty($form_data)) {
            if (isset($_POST['btnMRNView'])) {
                $data['viewMrn'] = 1;
            } else {
                $data['viewMrn'] = 0;
            }
            $data['site'] = $ids = $form_data['site'];
            $date = $form_data['start_date'];
            $edate = $form_data['end_date'];
            $data['wsheet'] = $this->Admindb->billing_summary_detailed_basic($ids, $date, $edate);
        }
        $custmernames = $this->Admindb->get_custmernames($ids);
        $data['custmernames'] = $custmernames;
        $this->view('admin/billing/billing-ajax', $data);
        die;
    }

    public function billing_summary_new()
    {

        $data['user'] = $this->user;
        // $this->admin_sidebar($data);
        //$this->carry_save();
        $form_data = $_POST;
        if (!empty($form_data)) {
            $data['site'] = $ids = !empty($form_data['site']) ? $form_data['site'] : '';

            $effectiveDate = strtotime("-1 months", strtotime($form_data['start_date']));
            $effectiveDateend = strtotime("-1 months", strtotime($form_data['end_date']));

            $date = date("Y-m", $effectiveDate);
            $dateend = date("Y-m", $effectiveDateend);

            $data['pre_date'] = $date;

            $data['carry_frwd']['data'] = $this->Admindb->carry_forward($ids, $date, $dateend);
            // $dat = $this->Admindb->carry_forward($ids, $date, $dateend);
            // print_r($dat);



            if ($data['carry_frwd']['data']) {
                foreach ($ids as $kk) {
                    $ans_key = array_column($data['carry_frwd']['data'][$kk], 'analysis');
                    $ans = array_column($data['carry_frwd']['data'][$kk], 'count');
                    $data['carry_frwd']['ans_ids'][$kk] = $ans_key;
                    $data['carry_frwd']['ans_count'][$kk] = array_combine($ans_key, $ans);
                }
            }
            //  print_r($data['carry_frwd']['ans_count']);

            $wsheet = $this->get_calc_worksheet($ids, $form_data['start_date'], $form_data['end_date']);

            /*    if (empty($wsheet['time_id'])) {

              $new_time_id = $this->Admindb->get_time_id_by_date($form_data['start_date'], $ids);
              $data['new_time_id'] = $new_time_id[0]['time_id'];
              } */

            $data['wsheet'] = !empty($wsheet['worksheets_details']) ? $wsheet['worksheets_details'] : '';
            $data['time_id'] = !empty($wsheet['time_id']) ? $wsheet['time_id'] : '';
            $data['start_date'] = $form_data['start_date'];
            $data['end_date'] = $form_data['end_date'];
            $data['site'] = !empty($form_data['site']) ? $form_data['site'] : '';
            $custmernames = $this->Admindb->get_custmernames($ids);
            $data['custmernames'] = $custmernames;
        }
        $this->view('admin/billing/summary_ajax', $data);
    }

    public function billing_customer_new()
    {


        $data['user'] = $this->user;
        // $this->admin_sidebar($data);
        //$this->carry_save();
        $form_data = $_POST;
        if (!empty($form_data)) {


            $data['site'] = $ids = $form_data['site'];

            $effectiveDate = strtotime("-1 months", strtotime($form_data['start_date']));

            $effectiveDateend = strtotime("-1 months", strtotime($form_data['end_date']));

            $date = date("Y-m", $effectiveDate);
            $dateend = date("Y-m", $effectiveDateend);

            $data['pre_date'] = $date;

            /* $data['carry_frwd']['data'] = $this->Admindb->carry_forward($ids, $date, $dateend);



              /*  if ($data['carry_frwd']['data']) {
              $ans_key = array_column($data['carry_frwd']['data'], 'analysis');
              $ans = array_column($data['carry_frwd']['data'], 'count');
              $data['carry_frwd']['ans_ids'] = $ans_key;
              $data['carry_frwd']['ans_count'] = array_combine($ans_key, $ans);
              } */

            $wsheet = $this->get_calc_worksheet($ids, $form_data['start_date'], $form_data['end_date']);
            // print_r($wsheet);
            // print_r($custmernames);

            if (empty($wsheet['time_id'])) {

                $new_time_id = $this->Admindb->get_time_id_by_date($form_data['start_date'], $form_data['end_date'], $ids);
                //$data['new_time_id'] = $new_time_id[0]['time_id'];
                $data['new_time_id'] = $new_time_id;
            }

            // $data['wsheet'] = $wsheet['136']['worksheets_details'];
            $data['wsheet'] = $wsheet['worksheets_details'];
            $data['time_id'] = $wsheet['time_id'];
            $data['start_date'] = $form_data['start_date'];
            $data['site'] = $form_data['site'];

            $custmernames = $this->Admindb->get_custmernames($ids);
            $data['custmernames'] = $custmernames;
        }

        $this->view('admin/billing/customer_ajax', $data);
    }

    public function save_user_details()
    {
        $form_data = $_POST;
        $success = 0;
        $msg = '';
        if (!empty($form_data['email']) && !empty($form_data['name']) && !empty($form_data['group_id']) && !empty($form_data['password'])) {
            $email_check = $this->Admindb->check_email($form_data['email']);
            if (!empty($email_check)) {
                $msg = 'Email Already Exists!';
            } else {
                $form_data['created'] = $form_data['updated'] = date("Y-m-d H:i:s");
                $form_data['active'] = 1;
                $form_data['created_by'] = $_SESSION['user']->user_id;
                $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
                $status = $this->Admindb->add_user($form_data);
                if ($status['type'] == 'success') {
                    $success = 1;
                }
                $msg = !empty($status['msg']) ? $status['msg'] : '';
            }
        } else {
            $msg = 'Please enter all the required details.';
        }
        echo json_encode(array("success" => $success, "msg" => $msg));
    }

    public function edit_user_details()
    {
        $form_data = $_POST;
        $success = 0;
        $msg = '';
        if (!empty($form_data['email']) && !empty($form_data['name']) && !empty($form_data['group_id']) && !empty($form_data['id'])) {
            $email_check = $this->Admindb->check_email($form_data['email'], $form_data['id']);
            if (!empty($email_check)) {
                $msg = 'Email Already Exists!';
            } else {
                $data['edit'] = $this->Admindb->user_by_id($form_data['id']);
                if ($form_data['password'] == "") {
                    $form_data['password'] = $data['edit']['password'];
                } else {
                    $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
                }
                $status = $this->Admindb->user_update($form_data);
                if ($status['type'] == 'success') {
                    $success = 1;
                }
                $msg = !empty($status['msg']) ? $status['msg'] : '';
            }
        } else {
            $msg = 'Please enter all the required details.';
        }
        echo json_encode(array("success" => $success, "msg" => $msg));
    }

    public function delete_user_details()
    {
        $form_data = $_POST;
        $type = '';
        $msg = '';
        if (!empty($form_data['ref'])) {
            $id = $form_data['ref'];
            $user_type = $form_data['item_ref'];
            $status = $this->Admindb->delete('users', $id, 'user_id');
            if ($user_type == '5') {
                $client_id = $this->Admindb->getClientId($id);
                if (!empty($client_id)) {
                    $status0 = $this->Admindb->delete('clients', $client_id, 'client_id');
                }
                $status1 = $this->Admindb->delete('client_details', $id, 'user_ids');
            }
            $type = !empty($status['type']) ? $status['type'] : '';
            $msg = !empty($status['msg']) ? $status['msg'] : '';
        }
        echo json_encode(array("type" => $type, "msg" => $msg));
    }

    public function delete_analyses_category()
    {
        $form_data = $_POST;
        $type = '';
        $msg = '';
        if (!empty($form_data['ref'])) {
            $id = $form_data['ref'];
            $status = $this->Admindb->delete('analyses_category', $id, 'category_id');
            $type = !empty($status['type']) ? $status['type'] : '';
            $msg = !empty($status['msg']) ? $status['msg'] : '';
        }
        echo json_encode(array("type" => $type, "msg" => $msg));
    }

    public function save_analyses_category()
    {
        $form_data = $_POST;
        $success = 0;
        $msg = '';
        if (!empty($form_data['category'])) {
            $form_data['is_active'] = '1';
            $form_data['created_by'] = $_SESSION['user']->user_id;
            $status = $this->Admindb->analyses_category_add($form_data);
            if ($status['type'] == 'success') {
                $success = 1;
            }
            $msg = !empty($status['msg']) ? $status['msg'] : '';
        } else {
            $msg = 'Please enter analysis category name.';
        }
        echo json_encode(array("success" => $success, "msg" => $msg));
    }

    public function edit_analyses_category_details()
    {
        $form_data = $_POST;
        $success = 0;
        $msg = '';
        if (!empty($form_data['category']) && !empty($form_data['id'])) {
            $status = $this->Admindb->analyses_category_update($form_data);
            if ($status['type'] == 'success') {
                $success = 1;
            }
            $msg = !empty($status['msg']) ? $status['msg'] : '';
        } else {
            $msg = 'Please enter all the required details.';
        }
        echo json_encode(array("success" => $success, "msg" => $msg));
    }

    public function save_analyses_details()
    {
        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        $form_data = $_POST;
        $success = 0;
        $msg = '';
        if (!empty($form_data['name']) && !empty($form_data['category']) && !empty($form_data['part_number']) && !empty($form_data['price']) && !empty($form_data['minimum_time']) && !empty($form_data['description'])) {
            $form_data['is_active'] = 1;
            $form_data['created_by'] = $_SESSION['user']->user_id;

            $tat_id = $form_data['minimum_time'] ?? null;
            if ($tat_id) {
                $tat_det = $this->Admindb->get_newtat_details_by_id($tat_id);
                if (!empty($tat_det)) {
                    $form_data['tat'] = $tat_det['tat'];
                    $form_data['tat_unit'] = $tat_det['tat_unit'];
                }
            }

            $status = $this->Admindb->analyses_add($form_data);

            if ($status['type'] == 'warning') {
                $success = $status['type'];
            }

            if ($status['type'] == 'success') {
                $success = 1;
            }
            $msg = !empty($status['msg']) ? $status['msg'] : '';
        }

        echo json_encode(array("success" => $success, "msg" => $msg));
    }

    public function delete_analyses()
    {
        $form_data = $_POST;
        $type = '';
        $msg = '';
        if (!empty($form_data['ref'])) {
            $id = $form_data['ref'];
            $deleted_at = date("Y-m-d H:i:s");
            $deleted_by = $_SESSION['user']->user_id;
            $status = $this->Admindb->delete_analyses($id, $deleted_at, $deleted_by);
            $type = !empty($status['type']) ? $status['type'] : '';
            $msg = !empty($status['msg']) ? $status['msg'] : '';
        }
        echo json_encode(array("type" => $type, "msg" => $msg));
    }

    public function edit_analyses_details()
    {

        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        $form_data = $_POST;
        $success = 0;
        $msg = '';
        if (!empty($form_data['name']) && !empty($form_data['category']) && !empty($form_data['part_number']) && !empty($form_data['price']) && !empty($form_data['minimum_time']) && !empty($form_data['description']) && !empty($form_data['id'])) {
            $form_data['updated_at'] = date("Y-m-d H:i:s");
            $form_data['updated_by'] = $_SESSION['user']->user_id;

            $tat_id = $form_data['minimum_time'] ?? null;
            if ($tat_id) {
                $tat_det = $this->Admindb->get_newtat_details_by_id($tat_id);
                if (!empty($tat_det)) {
                    $form_data['tat'] = $tat_det['tat'];
                    $form_data['tat_unit'] = $tat_det['tat_unit'];
                }
            }

            $status = $this->Admindb->analyses_update($form_data);

            if ($status['type'] == 'warning') {
                $success = $status['type'];
            }

            if ($status['type'] == 'info') {
                $success = $status['type'];
            }

            if ($status['type'] == 'success') {
                $success = 1;
            }
            $msg = !empty($status['msg']) ? $status['msg'] : '';
        } else {
            $msg = 'Please enter all the required details.';
        }
        echo json_encode(array("success" => $success, "msg" => $msg));
    }

    public function save_customer_details()
    {
        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        $form_data = $_POST;
        $success = 0;
        $msg = '';
        if (!empty($form_data['client_name']) && !empty($form_data['email'])) {
            $form_data['created'] = date("Y-m-d H:i:s");
            $form_data['active'] = 1;
            $form_data['created_by'] = $_SESSION['user']->user_id;
            $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
            $form_data['group_id'] = 5;
            $form_data['name'] = $form_data['client_name'];
            $status = $this->Admindb->add_user($form_data);
            if ($status['type'] == 'success') {
                // $tat_det = array();
                // $tat_id = $form_data['contract_tat'];
                // $tat_det = $this->Admindb->get_tat_details_by_id($tat_id);
                // $form_data['tat'] = $tat_det['tat'];
                // $form_data['tat_unit'] = $tat_det['tat_unit'];

                $tat_id = $form_data['contract_tat'] ?? null;
                if ($tat_id) {
                    $tat_det = $this->Admindb->get_newtat_details_by_id($tat_id);
                    if (!empty($tat_det)) {
                        $form_data['tat'] = $tat_det['tat'];
                        $form_data['tat_unit'] = $tat_det['tat_unit'];
                    }
                }

                $status1 = $this->Admindb->update_customer_details($status['customer_id'], $status['client_id'], $status['client_account_id'], $form_data);
                $success = 1;
            }
            $msg = !empty($status['msg']) ? $status['msg'] : '';
        }
        echo json_encode(array("success" => $success, "msg" => $msg));
    }

    public function edit_customer_details()
    {
        // header('Content-Type: application/json');
        // echo json_encode($_POST);
        // return;

        $form_data = $_POST;
        $success = 0;
        $msg = '';
        if (!empty($form_data['client_name']) && !empty($form_data['email'])) {
            $customer_id = $form_data['id'];
            $client_id = $form_data['client_id'];
            $client_account_id = $form_data['client_account_id'];

            $tat_id = $form_data['contract_tat'] ?? null;
            if ($tat_id) {
                $tat_det = $this->Admindb->get_newtat_details_by_id($tat_id);
                if (!empty($tat_det)) {
                    $form_data['tat'] = $tat_det['tat'];
                    $form_data['tat_unit'] = $tat_det['tat_unit'];
                }
            }

            $counter = $this->Admindb->update_customer_details($customer_id, $client_id, $client_account_id, $form_data);
            if ($counter > 0) {
                $email = $form_data['email'];
                $pword = password_hash($form_data['password'], PASSWORD_DEFAULT);
                $name = $form_data['client_name'];
                $upd = $this->Admindb->update_user_details($customer_id, $email, $pword, $name, $form_data['active']);
                $success = 1;
                $msg = 'Customer Details Saved Successfully.';
            }
        }
        echo json_encode(array("success" => $success, "msg" => $msg));
    }

    public function save_analyses_price_details()
    {
        $request = $_REQUEST;
        $success = 0;
        if (!empty($request['id'])) {
            $client_account_id = trim($request['id']);
            $analysis_id_arr = $request['analysis_id'];
            $created_by = $_SESSION['user']->user_id;
            foreach ($analysis_id_arr as $key => $analysis_ids) {
                if (!empty($analysis_ids)) {
                    $rate = trim($request['rate'][$key]);
                    $code = trim($request['code'][$key]);
                    $min_time = trim($request['min_time'][$key]);
                    $analysis_name = trim($request['analysis_name'][$key]);
                    $analysis_desc = trim($request['analysis_desc'][$key]);
                    $analysis_client_price_id = trim($request['analysis_client_price_id'][$key]);
                    if ($analysis_client_price_id > 0) {
                        $success += $this->Admindb->analyses_rate_update($analysis_client_price_id, $analysis_ids, $client_account_id, $rate, $code, $min_time, $analysis_name, $analysis_desc, $created_by);
                    } else {
                        $success += $this->Admindb->analyses_rate_add($analysis_ids, $client_account_id, $rate, $code, $min_time, $analysis_name, $analysis_desc, $created_by);
                    }
                }
            }
        }
        echo json_encode(array("success" => $success));
    }

    public function delete_analyses_price_details()
    {
        $request = $_REQUEST;
        $success = 0;
        if (!empty($request['client_account_id'])) {
            $client_account_id = trim($request['client_account_id']);
            $analysis_client_price_ids = $request['selectedValues'];
            $deleted_by = $_SESSION['user']->user_id;
            $success = $this->Admindb->analyses_rate_delete($client_account_id, $deleted_by, $analysis_client_price_ids);
        }
        echo json_encode(array("success" => $success));
    }

    public function analysis_price_ajax()
    {
        $data = [];
        $data['edit']['client_account_id'] = $_REQUEST['clientAccountId'];
        $this->view('v2/admin/customer/analysis_price_ajax', $data);
    }

    public function save_monthly_discount_details()
    {
        $request = $_REQUEST;
        $success = 0;
        if (!empty($request['id'])) {
            $client_account_id = trim($request['id']);
            $discount_id_arr = $request['discount_id'];
            $created_by = $_SESSION['user']->user_id;
            //$created_at = date("Y-m-d H:i:s");
            //$date = date('Y-m', strtotime('+1 month'));
            //$valid_from = $date . '-01';
            $valid_from = date('Y-m-01', strtotime('first day of next month'));
            $valid_to = date('Y-m-t', strtotime('first day of next month'));
            foreach ($discount_id_arr as $key => $discount_ids) {
                $minimum_value = trim($request['minimum_value'][$key]);
                $maximum_value = trim($request['maximum_value'][$key]);
                $percentage = trim($request['percentage'][$key]);
                if (!empty($discount_ids)) {
                    $success += $this->Admindb->monthly_discount_update($discount_ids, $client_account_id, $minimum_value, $maximum_value, $percentage);
                } else {
                    $success += $this->Admindb->monthly_discount_add($client_account_id, $minimum_value, $maximum_value, $percentage, $created_by, $valid_from, $valid_to);
                }
            }
        }
        echo json_encode(array("success" => $success));
    }

    public function delete_monthly_discount_details()
    {
        $request = $_REQUEST;
        $success = 0;
        if (!empty($request['client_account_id'])) {
            $client_account_id = trim($request['client_account_id']);
            $discount_ids = $request['selectedValues'];
            $success = $this->Admindb->monthly_discount_delete($client_account_id, $discount_ids);
        }
        echo json_encode(array("success" => $success));
    }

    public function monthly_discount_ajax()
    {
        $data = [];
        $data['edit']['client_account_id'] = $_REQUEST['clientAccountId'];
        $this->view('v2/admin/customer/monthly_discount_ajax', $data);
    }

    public function save_subscription_details()
    {
        $request = $_REQUEST;
        $success = 0;
        if (!empty($request['id'])) {
            $client_account_id = trim($request['id']);
            $subscription_content_ids_arr = !empty($request['subscription_content_id']) ? $request['subscription_content_id'] : '';
            $created_by = $_SESSION['user']->user_id;
            $subscription_total = !empty($request['subscription_total']) ? $request['subscription_total'] : 0;
            foreach ($subscription_content_ids_arr as $key => $subscription_content_ids) {
                $analysis_client_price_id = !empty($request['sub_analysis_id'][$key]) ? $request['sub_analysis_id'][$key] : '';
                $subscription_id = !empty($request['subscription_ids'][$key]) ? $request['subscription_ids'][$key] : '';
                $count = !empty($request['sub_count'][$key]) ? $request['sub_count'][$key] : 0;
                if (!empty($subscription_content_ids)) {
                    $success += $this->Admindb->subscription_update($subscription_content_ids, $subscription_id, $analysis_client_price_id, $client_account_id, $count, $subscription_total);
                } else {
                    $success += $this->Admindb->subscription_add($subscription_id, $analysis_client_price_id, $client_account_id, $count, $subscription_total, $created_by);
                }
            }
        }
        echo json_encode(array("success" => $success));
    }

    public function subscription_ajax()
    {
        $data = [];
        $data['edit']['client_account_id'] = $_REQUEST['clientAccountId'];
        $this->view('v2/admin/customer/subscription_ajax', $data);
    }

    public function delete_subscription_details()
    {
        $request = $_REQUEST;
        $success = 0;
        if (!empty($request['client_account_id'])) {
            $client_account_id = trim($request['client_account_id']);
            $subscription_content_ids = $request['selectedValues'];
            $success = $this->Admindb->delete_subscription_details($client_account_id, $subscription_content_ids);
        }
        echo json_encode(array("success" => $success));
    }

    public function get_excel_analyses_old()
    {
        $excel_data = $this->Admindb->get_excel_analysis_data();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test Result File');

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NAME')
            ->setCellValue('B1', 'DESCRIPTION')
            ->setCellValue('C1', 'CATEGORY')
            ->setCellValue('D1', 'PRICE')
            ->setCellValue('E1', 'DEFAULT TIME')
            ->setCellValue('F1', 'STATUS')
            ->setCellValue('G1', 'CREATED DATE');

        $spreadsheet->getActiveSheet()
            ->fromArray(
                $excel_data, // The data to set
                NULL, // Array values with this value will not be set
                'A2'
            );     // Top left coordinate of the worksheet range where
        //    we want to set these values (default is A1)
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('ANALYSES DATA');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Analyses_Data.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
        exit;
    }

    public function save_maintenance_fee_details()
    {
        $request = $_REQUEST;
        $success = 0;
        if (!empty($request['id'])) {
            $client_account_id = trim($request['id']);
            $created_by = $_SESSION['user']->user_id;
            $maintenance_fee_type = !empty($request['maintenance_fee_type']) ? $request['maintenance_fee_type'] : '';
            $maintenance_fee_amount = !empty($request['maintenance_fee_amount']) ? $request['maintenance_fee_amount'] : 0;
            $success = $this->Admindb->save_maintenance_fee_details($client_account_id, $maintenance_fee_type, $maintenance_fee_amount, $created_by);
        }
        echo json_encode(array("success" => $success));
    }

    public function maintenance_fee_ajax()
    {
        $data = [];
        $data['edit']['client_account_id'] = $_REQUEST['clientAccountId'];
        $this->view('v2/admin/customer/maintenance_fees_ajax', $data);
    }

    public function billing_summary_customer_ajax()
    {
        $data = [];
        $data['user'] = $this->user;
        $form_data = $_POST;
        $ids = !empty($form_data['customers']) ? $form_data['customers'] : [];
        $start_date = !empty($form_data['start_date']) ? $form_data['start_date'] : '';
        $end_date = !empty($form_data['end_date']) ? $form_data['end_date'] : '';
        $data['customers'] = $ids;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['wsheet'] = $this->Report->billing_summary_customer($start_date, $end_date, $ids);
        $customers = $this->Report->get_customer_names();
        $data['customers'] = $customers;
        $this->view('v2/admin/billing/billing_summary_customer_ajax', $data);
    }

    public function billing_summary_detailed_ajax()
    {
        $data = [];
        $data['user'] = $this->user;
        $form_data = $_POST;
        $ids = !empty($form_data['customers']) ? $form_data['customers'] : [];
        $start_date = !empty($form_data['start_date']) ? $form_data['start_date'] : '';
        $end_date = !empty($form_data['end_date']) ? $form_data['end_date'] : '';
        $data['customers'] = $ids;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['wsheet'] = $this->Report->billing_summary_detailed($start_date, $end_date, $ids);
        $customers = $this->Report->get_customer_names();
        $data['customers'] = $customers;
        $this->view('v2/admin/billing/billing_summary_detailed_ajax', $data);
    }

    public function get_study_time_report()
    {
        $request = $_REQUEST;
        $col = array(
            0 => 't1.created_at',
            1 => 't4.user_name',
            2 => 't1.patient_name',
            3 => 't1.analyst_hours',
            4 => 't1.expected_time',
            5 => 't1.time_difference',
            6 => 't5.user_name',
            7 => 't6.status'
        );

        list($totalData, $rows) = $this->Report->study_time_report($col, $request);

        $data = [];

        foreach ($rows as $row) {
            $subdata = [];
            $newDate = '';

            if (!empty($row['created_at'])) {
                $newDate = date("m-d-Y h:i:s A", strtotime($row['created_at']));
            }

            $subdata[] = $newDate;
            $subdata[] = !empty($row['user_name']) ? $row['user_name'] : '';
            $subdata[] = !empty($row['patient_name']) ? $row['patient_name'] : '';
            $analyst_hours = !empty($row['analyst_hours']) ? $row['analyst_hours'] : '';
            $expected_time = !empty($row['expected_time']) ? $row['expected_time'] : '';
            $time_difference = !empty($row['time_difference']) ? $row['time_difference'] : '';

            if ($analyst_hours > 0 && $expected_time > 0) {
                if ($analyst_hours > $expected_time) {
                    $analyst_hours = '<span>' . $analyst_hours . ' <i class="fa fa-arrow-up style-red" ></i></span>';
                    $expected_time = '<span>' . $expected_time . '</span>';
                } else if ($analyst_hours < $expected_time) {
                    $analyst_hours = '<span>' . $analyst_hours . ' <i class="fa fa-arrow-down style-green" ></i></span>';
                    $expected_time = '<span>' . $expected_time . '</span>';
                }
            }

            if ($time_difference > 0) {
                $time_difference = '<span>' . $time_difference . ' <i class="fa fa-arrow-down style-green" ></i></span>';
            } else if ($time_difference < 0) {
                $time_difference = '<span>' . $time_difference . ' <i class="fa fa-arrow-down style-green" ></i></span>';
            } else {
                $time_difference = '<span>' . $time_difference . '</span>';
            }

            $subdata[] = $analyst_hours;
            $subdata[] = $expected_time;
            $subdata[] = $time_difference;

            $subdata[] = !empty($row['assignee_name']) ? $row['assignee_name'] : '';
            $status = !empty($row['status']) ? $row['status'] : '';

            if (strtolower($status) == strtolower('Completed')) {
                $subdata[] = '<span class="btn btn-xs btn-success">' . $status . '</span>';
            } else if (strtolower($status) == strtolower('In progress')) {
                $subdata[] = '<span class="btn btn-xs btn-info">' . $status . '</span>';
            } else if (strtolower($status) == strtolower('Under review')) {
                $subdata[] = '<span class="btn btn-xs btn-warning">' . $status . '</span>';
            } else if (strtolower($status) == strtolower('Cancelled')) {
                $subdata[] = '<span class="btn btn-xs btn-danger">' . $status . '</span>';
            } else if (strtolower($status) == strtolower('On hold')) {
                $subdata[] = '<span class="btn btn-xs btn-warning">' . $status . '</span>';
            } else {
                $subdata[] = $status;
            }

            $data[] = $subdata;
        }

        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalData),
            "data" => $data
        );

        echo json_encode($json_data);
        die;
    }

    public function study_time_report_excel1()
    {
        $request = $_REQUEST;
        $col = [];

        list($totalData, $excel_data) = $this->Report->study_time_report($col, $request);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('Excel Study Time Report')
            ->setSubject('Excel Study Time Report')
            ->setDescription('Generated Excel report using PhpSpreadsheet.')
            ->setKeywords('Excel report PhpSpreadsheet')
            ->setCategory('Report');

        // Define headers
        $headers = ['SL No', 'Date', 'Customer', 'Patient', 'AT [Minutes]', 'EAT [Minutes]', 'Time Difference', 'Assignee', 'Status'];

        $sheet->fromArray([$headers], NULL, 'A1');

        // Determine the last column dynamically
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $headerRange = 'A1:' . $lastColumn . '1';

        // Apply full header styling
        $styleArray = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFE0B2'], // Light Orange Background
            ],
        ];

        // Apply styling
        $sheet->getStyle($headerRange)->applyFromArray($styleArray);

        if ($totalData > 0) {
            // Insert data using a loop
            $rowNumber = 2; // Start from row 2 since row 1 has headers
            foreach ($excel_data as $k => $row) {
                $newDate = '';
                if (!empty($row['created_at'])) {
                    $newDate = date("m-d-Y h:i:s A", strtotime($row['created_at']));
                }
                $customer = !empty($row['user_name']) ? $row['user_name'] : '';
                $patient_name = !empty($row['patient_name']) ? $row['patient_name'] : '';
                $analyst_hours = !empty($row['analyst_hours']) ? $row['analyst_hours'] : '';
                $expected_time = !empty($row['expected_time']) ? $row['expected_time'] : '';
                $time_difference = !empty($row['time_difference']) ? $row['time_difference'] : '';
                $assignee_name = !empty($row['assignee_name']) ? $row['assignee_name'] : '';
                $status = !empty($row['status']) ? $row['status'] : '';
                $sheet->setCellValue('A' . $rowNumber, $k + 1);
                $sheet->setCellValue('B' . $rowNumber, $newDate);
                $sheet->setCellValue('C' . $rowNumber, $customer);
                $sheet->setCellValue('D' . $rowNumber, $patient_name);
                $sheet->setCellValue('E' . $rowNumber, $analyst_hours);
                $sheet->setCellValue('F' . $rowNumber, $expected_time);
                $sheet->setCellValue('G' . $rowNumber, $time_difference);
                $sheet->setCellValue('H' . $rowNumber, $assignee_name);
                $sheet->setCellValue('I' . $rowNumber, $status);
                $rowNumber++;
            }
        }
        // Rename worksheet
        $sheet->setTitle('STUDY TIME REPORT');

        // Set headers for file download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="study_time_report.xls"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        // Create writer and output file
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
        exit;
    }

    public function study_time_report_excel()
    {
        ini_set('memory_limit', '-1');

        $request = $_REQUEST;
        $col = [];

        list($totalData, $excel_data) = $this->Report->study_time_report($col, $request);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('Excel Study Time Report')
            ->setSubject('Excel Study Time Report')
            ->setDescription('Generated Excel report using PhpSpreadsheet.')
            ->setKeywords('Excel report PhpSpreadsheet')
            ->setCategory('Report');

        // Define headers
        $headers = ['SL No', 'Date', 'Customer', 'Patient', 'AT [Minutes]', 'EAT [Minutes]', 'Time Difference', 'Assignee', 'Status'];

        // Insert headers
        $sheet->fromArray([$headers], NULL, 'A1');

        // Determine the last column dynamically
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $headerRange = 'A1:' . $lastColumn . '1';

        // Apply header styling
        $styleArray = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFE0B2'], // Light Orange Background
            ],
        ];
        $sheet->getStyle($headerRange)->applyFromArray($styleArray);

        if ($totalData > 0) {
            // Insert data using a loop
            $rowNumber = 2; // Start from row 2 since row 1 has headers
            foreach ($excel_data as $k => $row) {
                $newDate = (!empty($row['created_at'])) ? date("m-d-Y h:i:s A", strtotime($row['created_at'])) : '';
                $customer = !empty($row['user_name']) ? $row['user_name'] : '';
                $patient_name = !empty($row['patient_name']) ? $row['patient_name'] : '';
                $analyst_hours = !empty($row['analyst_hours']) ? $row['analyst_hours'] : '';
                $expected_time = !empty($row['expected_time']) ? $row['expected_time'] : '';
                $time_difference = !empty($row['time_difference']) ? $row['time_difference'] : '';
                $assignee_name = !empty($row['assignee_name']) ? $row['assignee_name'] : '';
                $status = !empty($row['status']) ? $row['status'] : '';

                $sheet->setCellValueExplicit('A' . $rowNumber, $k + 1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('B' . $rowNumber, $newDate, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('C' . $rowNumber, $customer, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('D' . $rowNumber, $patient_name, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('E' . $rowNumber, $analyst_hours, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $sheet->setCellValueExplicit('F' . $rowNumber, $expected_time, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $sheet->setCellValueExplicit('G' . $rowNumber, $time_difference, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $sheet->setCellValueExplicit('H' . $rowNumber, $assignee_name, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('I' . $rowNumber, $status, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                $rowNumber++;
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fl = 'temp/study_time_report.xlsx';
        $writer->save($fl);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="study_time_report.xlsx"');
        header('Content-Length: ' . filesize($fl));
        readfile($fl);

        unlink($fl);

        exit;
    }

    public function users_excel()
    {
        ini_set('memory_limit', '-1');
        list($totalData, $excel_data) = $this->Report->users_excel_report();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('Excel Users Report')
            ->setSubject('Excel Users Report')
            ->setDescription('Generated Excel report using PhpSpreadsheet.')
            ->setKeywords('Excel report PhpSpreadsheet')
            ->setCategory('Report');
        // Define headers
        $headers = ['SL No', 'Name', 'Email', 'User Type', 'Status', 'Created Date'];
        // Insert headers
        $sheet->fromArray([$headers], NULL, 'A1');
        // Determine the last column dynamically
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $headerRange = 'A1:' . $lastColumn . '1';
        // Apply header styling
        $styleArray = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFE0B2'], // Light Orange Background
            ],
        ];
        $sheet->getStyle($headerRange)->applyFromArray($styleArray);
        if ($totalData > 0) {
            // Insert data using a loop
            $rowNumber = 2; // Start from row 2 since row 1 has headers
            foreach ($excel_data as $k => $row) {
                //  $newDate = (!empty($row['created_at'])) ? date("m-d-Y h:i:s A", strtotime($row['created_at'])) : '';
                $newDate = (!empty($row['created_at'])) ? date("m-d-Y", strtotime($row['created_at'])) : '';
                $name = !empty($row['user_name']) ? $row['user_name'] : '';
                $email = !empty($row['email']) ? $row['email'] : '';
                $user_type = !empty($row['user_type']) ? $row['user_type'] : '';
                if ($row['is_active'] == 1) {
                    $status = 'Active';
                } else if ($row['is_active'] == 2) {
                    $status = 'Dormant';
                } else {
                    $status = 'Inactive';
                }
                $sheet->setCellValueExplicit('A' . $rowNumber, $k + 1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('B' . $rowNumber, $name, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('C' . $rowNumber, $email, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('D' . $rowNumber, $user_type, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('E' . $rowNumber, $status, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('F' . $rowNumber, $newDate, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $rowNumber++;
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fl = 'temp/users_list.xlsx';
        $writer->save($fl);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="users_list.xlsx"');
        header('Content-Length: ' . filesize($fl));
        readfile($fl);

        unlink($fl);

        exit;
    }

    public function client_excel()
    {
        ini_set('memory_limit', '-1');
        list($totalData, $excel_data) = $this->Report->get_customer_excel_data();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('Excel Client Report')
            ->setSubject('Excel Client Report')
            ->setDescription('Generated Excel report using PhpSpreadsheet.')
            ->setKeywords('Excel report PhpSpreadsheet')
            ->setCategory('Report');
        // Define headers
        $headers = ['SL No', 'Name', 'Email', 'Status', 'Created Date'];
        // Insert headers
        $sheet->fromArray([$headers], NULL, 'A1');
        // Determine the last column dynamically
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $headerRange = 'A1:' . $lastColumn . '1';
        // Apply header styling
        $styleArray = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFE0B2'], // Light Orange Background
            ],
        ];
        $sheet->getStyle($headerRange)->applyFromArray($styleArray);
        if ($totalData > 0) {
            // Insert data using a loop
            $rowNumber = 2; // Start from row 2 since row 1 has headers
            foreach ($excel_data as $k => $row) {
                $newDate = (!empty($row['created_at'])) ? date("m-d-Y", strtotime($row['created_at'])) : '';
                $name = !empty($row['user_name']) ? $row['user_name'] : '';
                $email = !empty($row['email']) ? $row['email'] : '';
                if ($row['is_active'] == 1) {
                    $status = 'Active';
                } else if ($row['is_active'] == 2) {
                    $status = 'Dormant';
                } else {
                    $status = 'Inactive';
                }
                $sheet->setCellValueExplicit('A' . $rowNumber, $k + 1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('B' . $rowNumber, $name, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('C' . $rowNumber, $email, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('D' . $rowNumber, $status, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('E' . $rowNumber, $newDate, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $rowNumber++;
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fl = 'temp/clients_list.xlsx';
        $writer->save($fl);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="clients_list.xlsx"');
        header('Content-Length: ' . filesize($fl));
        readfile($fl);

        unlink($fl);

        exit;
    }

    public function get_excel_analyses()
    {
        ini_set('memory_limit', '-1');
        list($totalData, $excel_data) = $this->Report->get_excel_analysis_data();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Dicon')
            ->setLastModifiedBy('Dicon')
            ->setTitle('Excel Analyses Report')
            ->setSubject('Excel Analyses Report')
            ->setDescription('Generated Excel report using PhpSpreadsheet.')
            ->setKeywords('Excel report PhpSpreadsheet')
            ->setCategory('Report');

        $headers = ['SL No', 'Name', 'Description', 'Category', 'Item Number', 'Price', 'Default Time (Min)'];

        $sheet->fromArray([$headers], NULL, 'A1');

        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $headerRange = 'A1:' . $lastColumn . '1';

        $styleArray = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFE0B2'], // Light Orange Background
            ],
        ];
        $sheet->getStyle($headerRange)->applyFromArray($styleArray);
        if ($totalData > 0) {
            $rowNumber = 2;
            foreach ($excel_data as $k => $row) {
                $analysis_name = !empty($row['analysis_name']) ? $row['analysis_name'] : '';
                $analysis_invoicing_description = !empty($row['analysis_invoicing_description']) ? $row['analysis_invoicing_description'] : '';
                $category_name = !empty($row['category_name']) ? $row['category_name'] : '';
                $analysis_number = !empty($row['analysis_number']) ? $row['analysis_number'] : '';
                $analysis_price = !empty($row['analysis_price']) ? $row['analysis_price'] : '';
                $time_to_analyze = !empty($row['analysis_name']) ? $row['time_to_analyze'] : '';

                $sheet->setCellValueExplicit('A' . $rowNumber, $k + 1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('B' . $rowNumber, $analysis_name, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('C' . $rowNumber, $analysis_invoicing_description, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('D' . $rowNumber, $category_name, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('E' . $rowNumber, $analysis_number, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('F' . $rowNumber, $analysis_price, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('G' . $rowNumber, $time_to_analyze, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $rowNumber++;
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fl = 'temp/analyses_list.xlsx';
        $writer->save($fl);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="analyses_list.xlsx"');
        header('Content-Length: ' . filesize($fl));
        readfile($fl);

        unlink($fl);

        exit;
    }
}
