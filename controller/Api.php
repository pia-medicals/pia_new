<?php

/**
 * Controller
 */
class Api extends Controller {

    private $Apidb; // Declare the property
    private $Admindb; // Declare the property

    function __construct() {
        $this->Apidb = $this->model('apidb');   // Initialize the declared property
        $this->Admindb = $this->model('admindb'); // Initialize the declared property
    }

    public function amberincoming($data) {
        if (!empty($data)) {

            $requestdata = json_decode($data->body());
            //$return['status'] = false;
            if (!empty($requestdata)) {

                $datatosave['name'] = !empty($requestdata->name) ? $requestdata->name : '';
                $datatosave['mrn'] = !empty($requestdata->MRN) ? $requestdata->MRN : '';
                $datatosave['institution'] = !empty($requestdata->institution) ? $requestdata->institution : '';
                $datatosave['exam_time'] = !empty($requestdata->exam_time) ? $requestdata->exam_time : '';
                $datatosave['accession'] = !empty($requestdata->accession) ? $requestdata->accession : '';
                $datatosave['exam_date'] = !empty($requestdata->exam_date) ? $requestdata->exam_date : '';
                $datatosave['description'] = !empty($requestdata->description) ? $requestdata->description : '';
                $datatosave['customer'] = !empty($requestdata->customer) ? $requestdata->customer : '';
                $datatosave['alldata'] = serialize($requestdata);

                $return = $this->Apidb->insertclario($datatosave);
            }
            echo json_encode($return);
            if ($return == null) {
                $err_val = "NO DATA FOUND";
                $error_message = PHP_EOL . date("d-m-Y h:i A") . " :: " . $err_val . PHP_EOL . PHP_EOL;
                $r1 = $this->log_errors($error_message);
            }

            die;
        } else {
            echo "fail";
        }
        echo json_encode(array('status' => false, 'message' => 'No data Found'));
        die;
    }

    public function log_errors($error_message) {
// path of the log file where errors need to be logged
        $log_file = $_SERVER['DOCUMENT_ROOT'] . "/log_ambra_errors.log";

// logging error message to given log file
        error_log($error_message, 3, $log_file);
    }

    public function carry_calculation() {

        // run this function on 1st day of every month
        $data = array();
        $date = date("Y-m");
        //$date = "2018-11";

        $effectiveDate = strtotime("-1 months", strtotime($date));
        $datePrev = date("Y-m", $effectiveDate);
        $datePrevs = strtotime("-1 months", strtotime($datePrev));
        $before_prev_date = date("Y-m", $datePrevs);
        $allworksheets = $this->Admindb->get_all_worksheets(); //Users Fetch



        if (!empty($allworksheets)) {
            foreach ($allworksheets as $key => $allworksht) {

                $wsheet = $this->get_calc_worksheet($allworksht['customer'], $datePrev);

                $new_time_id = $this->Admindb->get_time_id_by_date($datePrev, $allworksht['customer']);


                $time_id = $new_time_id[0]['time_id'];

                //print_r($time_id);die;

                if (isset($wsheet) && !empty($wsheet['worksheets_details'])) {



                    foreach ($wsheet['worksheets_details'] as $key => $worksheets_det) {


                        $sub_count = $this->Admindb->only_count_subscription($allworksht['customer'], $worksheets_det['ans_id'], $time_id);

                        $pre_carry = $this->Admindb->count_previous_carry($allworksht['customer'], $worksheets_det['ans_id'], $before_prev_date);
                        if ($sub_count) {

                            $carry = $sub_count['count'] + $pre_carry['count'] - $worksheets_det['qty'];
                            if ($carry <= 0) {

                                $count[] = 0;
                            } else {

                                $count[] = $carry;
                            }
                            $analysis[] = $sub_count['analysis'];
                            $customer[] = $allworksht['customer'];

                            //$this->debug($data);
                            // if($data['count'] >= 0) $insert_data[] = $data;	
                        }
                    }
                }
            }
        }

        $this->debug($analysis);
        $this->debug($customer);
        $this->debug($count);

        if (!empty($analysis)) {
            foreach ($analysis as $key => $ans_id) {
                $this->Admindb->carry_add($ans_id, $customer[$key], $count[$key], $datePrev);
            }
        }

        //print_r($datePrev); die("datePrev");
        $all_carry = $this->Admindb->carry_select($datePrev, $time_id);
        $this->debug($all_carry);
        $this->debug("from carry forward");
        $this->Admindb->trunct_table();

        if (!empty($all_carry)) {
            foreach ($all_carry as $key => $all_cry) {
                echo $all_cry;
                $this->Admindb->carry_add_backup($all_cry['analysis'], $all_cry['customer'], $all_cry['count'], $datePrev);
            }
        }

        //selecting unused items from subscriptions

        $all_carry_backup = $this->Admindb->carry_select_from($datePrev, $time_id);


        $this->debug("all_carry_backup");
        $this->debug($all_carry_backup);

        if (!empty($all_carry_backup)) {
            foreach ($all_carry_backup as $key => $all_carrys) {
                $prev_carry = $this->Admindb->count_previous_carry($all_carrys['customer'], $all_carrys['analysis'], $before_prev_date);
                $this->debug($prev_carry['count']);
                $this->Admindb->carry_add_2($all_carrys['analysis'], $all_carrys['customer'], $all_carrys['count'], $datePrev, $prev_carry['count']);
            }
        }

        $this->debug("success");
    }

}
