<?php

/**
 * 
 */
require_once ROOT_PATH . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class App {

    function __construct() {
        # code...
    }

    public function require_all() {

        require_once ROOT_PATH . '/config/Defines.php';
        require_once APP_PATH . '/Controller.php';
        require_once APP_PATH . '/Model.php';
        require_once APP_PATH . '/Router.php';
        require_once APP_PATH . '/class_dicom.php';
        require_once VENDOR_PATH . '/autoload.php';
    }

    public function view($view_name, $data = array()) {
        require_once APP_PATH . '/Controller.php';
        $obj = new Controller();
        return $obj->view($view_name, $data);
    }

    public function add_alert($type, $msg) {
        $_SESSION['alert'] = '<div class="alert alert-' . $type . '">' . $msg . '</div>';
        $_SESSION['alert_msg'] = $msg;
        $_SESSION['alert_type'] = $type;
    }

    public function alert($type = "", $msg = "") {

        if (isset($_SESSION['alert'])) {
            echo $_SESSION['alert'];
            $return = array('type' => $_SESSION["alert_type"], 'msg' => $_SESSION["alert_msg"]);
            unset($_SESSION["alert"]);
            unset($_SESSION["alert_msg"]);
            unset($_SESSION["alert_type"]);
            return $return;
        }

        if (!empty($type) && !empty($type)) {

            echo '<div class="alert alert-' . $type . '">' . $msg . '</div>';

            return array('type' => $type, 'msg' => $msg);
        }
        return false;
    }

    public function redirect($route = '') {
        $location = sprintf(
                "%s://%s/%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['HTTP_HOST'],
                //  $_SERVER['REQUEST_URI'],
                $route
        );

        header("Location: " . $location);
        exit;
    }

    public function controller($controller, $function = '') {
        require_once CONTROLLER_PATH . $controller . '.php';
        $obj = new $controller;
        if (!empty($function)) {
            $function = ucfirst($function);
            return $obj->$function();
        }
        return $obj->index();
    }

    public function api($api, $function = '', $request = '') {
        require_once CONTROLLER_PATH . $api . '.php';
        $obj = new $api;
        if (!empty($function)) {
            $function = ucfirst($function);
            return $obj->$function($request);
        }
        return $obj->index($request);
    }

    public function empty_key_value($array) {
        $return = array();
        foreach ($array as $key => $value) {
            if ($value == '' && $key != 'active' && $key != 'phone' && $key != 'profile_picture') {
                $return[$key] = $key;
            }
        }
        return $return;
    }

    public function debug($array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    public function url() {
        return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public function underscore_remove($string) {
        return str_replace('_', ' ', $string);
    }

    public function imageUpload($file, $target_dir = "/assets/uploads/") {
        $uploadOk = array();
        $target_file = $target_dir . basename('IMG' . time() . $file["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $uploadfile = ROOT_PATH . $target_dir . basename('IMG' . time() . $file["name"]);

        if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
            return 'IMG' . time() . $file["name"];
        } else {
            return false;
        }
    }

    public function fileUpload($file, $target_dir = "/assets/uploads/") {
        $uploadOk = array();
        $name = 'file' . time() . $file["name"];
        $uploadfile = $target_dir . basename($name);

        if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
            return $name;
        } else {
            return false;
        }
    }

    public function issetEcho($obj, $value, $index = 0) {
        if (isset($obj) && is_object($obj)) {
            if (isset($obj->$value)) {
                if (is_array($obj->$value)) {
                    $vl = $obj->$value;
                    echo $vl[$index];
                } else
                    echo $obj->$value;
            } else
                echo "";
        }
    }

    public function read_xl($file_name) {



        $inputFileName = XL_PATH . $file_name;

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($inputFileName);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        return $sheetData;
    }

    public function billing_code_total($value = '') {

        $code = explode(',', $value);
        foreach ($code as $key => $billing_code_by_code) {
            $bc[] = $this->Admindb->billing_code_by_code($billing_code_by_code);
        }
        return $bc;
    }

    public function analyst_rates_for($value = '') {

        $code = explode(',', $value);
        foreach ($code as $key => $billing_code_by_code) {
            $rate = $this->Admindb->analyses_code_by_code_individ($billing_code_by_code);

            if ($rate) {
                $bc[] = $rate;
            } else {
                $bc[] = $this->Admindb->analyses_code_by_code_individ($billing_code_by_code);
            }
        }
        return $bc;
    }

    public function analyst_total_amount($array) {
        $month = array();
        $bc = 0;
        foreach ($array as $key => $value) {
            $code = '';
            foreach ($value as $value_inner) {
                $code .= $value_inner . ",";
            }
            $code = explode(',', $code);
            print_r($code);
            foreach ($code as $billing_code_by_code) {
                $bc = $bc + $this->Admindb->billing_code_by_code($billing_code_by_code)['price'];
            }
            $month[$key] = $bc;
        }
        return $month;
    }

    public function wsheet_month_rate() {
        $month_data = $this->Admindb->wsheet_month_rate();
        $month = array();
        $monthnum = array_unique(array_column($month_data, 'monthnum'));
        foreach ($monthnum as $key => $value) {
            $month[$value] = 0;
        }
        foreach ($month_data as $key => $value) {
            $addon_flows = json_decode($value['addon_flows']);
            $analyses_ids = explode(',', $value['analyses_ids']);

            foreach ($analyses_ids as $analyses_id) {
                if ($analyses_id == "" || $value['customer_id'] == "") {
                    $rate = 0;
                    continue;
                }
                // $rate = $this->Admindb->get_rate_by_anid_cid($analyses_id, $value['customer_id'])['rate'] * $addon_flows->$analyses_id;
                $rate = $this->Admindb->get_rate_by_anid_cid_new($analyses_id, $value['customer_id'])['rate'] * $addon_flows->$analyses_id;
            }
            $month[$value['monthnum']] = $month[$value['monthnum']] + $rate;
        }
        asort($month);
        return $month;
    }

    public function get_calc_worksheet($customer_id, $date, $edate) {
        $wsheet_time_id = [];
        $keyarray = [];
        $worksheets_details = [];

        $worksheets = $this->Admindb->get_wsheet_id_customer($customer_id, $date, $edate);

        if (empty($worksheets)) {
            return false;
        }

        if (!empty($customer_id)) {
            foreach ($customer_id as $kk) {
                if (!empty($worksheets[$kk])) {
                    foreach ($worksheets[$kk] as $key => $value) {
                        $wsheet_ids[$kk][] = $value['id'];
                        $wsheet_time_id[$kk][] = $value['time_id'];
                    }
                }
            }
            if (!empty($wsheet_ids)) {
                $keyarray = array_keys($wsheet_ids);
                foreach ($keyarray as $kk) {
                    if (!empty($wsheet_ids[$kk])) {
                        $size = sizeof($wsheet_ids[$kk]);
                        $worksheets_details[$kk] = $this->Admindb->get_wsheet_details_wsheet_id($wsheet_ids[$kk], $size);
                    }
                }
            }
        }

        return array(
            'time_id' => $wsheet_time_id,
            'worksheets_details' => $worksheets_details,
            'keyarray' => $keyarray
        );
    }

    /* public function get_calc_worksheet($customer_id,$date)
      {
      //$wsheet_id id seperate with coma

      $worksheets = $this->Admindb->get_wsheet_id_customer($customer_id,$date);


      if(!$worksheets) return false;


      //print_r($worksheets); die;
      foreach ($worksheets as $key => $value) {

      $wsheet_ids[]	= $value['id'];
      $wsheet_time_id	= $value['time_id'];

      }

      $size  = sizeof($wsheet_ids);










      $worksheets_details = $this->Admindb->get_wsheet_details_wsheet_id($wsheet_ids,$size);








      return  array(

      'time_id' => $wsheet_time_id,

      'worksheets_details' => $worksheets_details


      );

      } */

    public function carry_save($date = '') {

        $customers = $this->Admindb->table_full('users', 'WHERE user_type_ids = 5');
        $sub_and_rate = $this->Admindb->count_subscription(54, 49);
        $date = date("Y-m");
        $insert_data = array();
        foreach ($customers as $key => $customers_value) {
            $wsheet = $this->get_calc_worksheet($customers_value['id'], $date);
            if (isset($wsheet) && !empty($wsheet)) {
                $analysis = array_column($wsheet['data'], 'analyses_id');

                //$this->debug($wsheet);
                foreach ($wsheet['data']as $key => $wsheet_value) {
                    //collecting subscribed analysis
                    $sub_data = $this->Admindb->count_subscription($customers_value['id'], $wsheet_value['analyses_id']);
                    //$this->debug($sub_data);
                    if (isset($sub_data) && !empty($sub_data)) {
                        $used_count = $wsheet_value['count'];
                        $subcrbd_count = $sub_data['count'];
                        $bal = max($subcrbd_count - $used_count, 0);
                        $data['analysis'] = $wsheet_value['analyses_id'];
                        $data['customer'] = $customers_value['id'];
                        $data['count'] = $bal;
                        //$this->debug($data);
                        if ($data['count'] >= 0)
                            $insert_data[] = $data;
                    }
                }

                //$this->debug($analysis);
            }
        }

        foreach ($insert_data as $key => $value) {
            $this->Admindb->carry_add($value);
        }
    }

    public function str_replace_last($search, $replace, $str) {
        if (( $pos = strrpos($str, $search) ) !== false) {
            $search_length = strlen($search);
            $str = substr_replace($str, $replace, $pos, $search_length);
        }
        return $str;
    }

    public function time_convert($time, $type, $ret = false) {
        $return = false;

        if ($type == 'hr') {
            //print_r($time); die();
            if (!is_array($time))
                $time = explode('.', $time);
            if (!isset($time[1]))
                $time[1] = 0;

            $hr = $time[0] * 60;
            $min = $hr + $time[1];
            $min_to_hr = $time[1] / 60;
            $return = $time[0] + $min_to_hr;
            if ($ret)
                $return = explode('.', $return);
        }elseif ($type == 'min') {
            if (!is_array($time))
                $time = explode('.', $time);
            if (!isset($time[1]))
                $time[1] = 0;
            $float = '.' . $time[1];
            $float = (float) $float;
            $time[1] = 60 * $float;
            $return = $time[0] . '.' . $time[1];
            if ($ret)
                $return = $time;
        }

        return $return;
    }

    public function get_customers_with_time_id() {

        $result = $this->Admindb->get_customers_with_time_id();


        if (!empty($result)) {

            foreach ($result as $key => $results) {

                $cust_details[] = $this->Admindb->find_customers_with_id($results['customer_id']);
            }

            return $cust_details;
        }
    }

    public function get_all_customers() {
        $cust_details = $this->Admindb->get_all_customers();
        return $cust_details;
    }

    public function get_all_analysts() {
        $analysts_details = $this->Admindb->get_all_analysts();
        return $analysts_details;
    }

    public function get_analyses_name($id) {
        $analysts_name = $this->Admindb->analyses_by_code($id);
        //print_r($analysts_name);
        return $analysts_name['name'];
    }

    public function get_worksheet_status($status = 'In progress') {

        switch ($status) {
            case 'Cancelled':
                $sta = 'Cancelled - duplicate order';
                break;
            case 'CancelledAcc':
                $sta = 'Cancelled - accidental send';
                break;
            case 'CancelledCust':
                $sta = 'Cancelled - by customer during analysis';
                break;
            case 'Under review':
                $sta = 'Under review';
                break;
            case 'Completed':
                $sta = 'Completed';
                break;
            case 'On hold':
                $sta = 'On hold';
                break;
            case 'In progress':
                $sta = 'In progress';
                break;
            default:
                $sta = 'In progress';
                break;
        }

        return $sta;
    }

}
