<?php

class admindb extends Model {

    public $mysqli;

    function __construct($con) {
        $this->mysqli = $con;
    }

    public function debug($array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    public function user_obj($email) {

        $sql_query = "SELECT * FROM users WHERE email='$email'";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows == 1) {
            while ($obj = $result->fetch_object()) {

                $return = $obj;
            }
        } else
            $return = false;
        return $return;
    }

    public function get_max_discount_by_customer($id) {

        $data = array();

        $time_ids = $this->get_user_time_id($id);

        $time_id = $time_ids['time_id'];

        $sql_query = "SELECT MAX(maximum_value) as max_value 
					  FROM `discount_range` 
					  WHERE `customer` = $id 
					  AND `time_id` = $time_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function delete($table, $id) {
        $sql_query = "DELETE FROM $table WHERE id=$id";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'danger';
            $status['msg'] = 'Deleted successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function worksheets_details_delete($id = 0) {
        if (empty($id)) {
            return;
        }
        $sql_query = "DELETE FROM worksheet_details WHERE worksheet_id=$id";
        $result = $this->mysqli->query($sql_query);
        return $result;
    }

    public function add_user($data = array()) {

        extract($data);
        $name = str_replace("'", "''", $name);
        $sql_query = "INSERT INTO `users` (`created`, `updated`, `group_id`, `name`, `email`, `password`, `active`, `id`,`profile_picture`,`user_meta`) VALUES ('$created', '$updated', '$group_id', '$name', '$email', '$password', '1', NULL,'','')";
        $result = $this->mysqli->query($sql_query);

        $customer_id = $this->mysqli->insert_id;
        $sql = "INSERT INTO `adm_admin_customer_login` (`ACL_Fisrt_Name`, `ACL_Email`, `ACL_Password`, `ACL_Status`, `ACL_Master_FK`,`ACL_Add_User_By`,`ACL_User_Add_On`,`ACL_Customer_Type_FK`) VALUES ('$name', '$email', '$password', '1', '$customer_id', '0', '$created', '1')";
        $result = $this->mysqli->query($sql);

        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'New user created successfully';
            $status['customer_id'] = $customer_id;
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function add_usertime_line($customer_id) {



        $created_at = date("Y-m-d H:i:s");

        $date = date('Y-m', strtotime('+1 month'));

        $valid_from = $date . '-01';

        $valid_to = strtotime("36 months", strtotime($valid_from));

        $valid_to = date("Y-m-d", $valid_to);

        $sql_query = "INSERT INTO `Timeline` (`customer_id`, `created_at`, `valid_from`, `valid_to`)  VALUES ('$customer_id', '$created_at', '$valid_from', '$valid_to')";
        $result = $this->mysqli->query($sql_query);

        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'New user created successfully';
            $status['time_id'] = $this->mysqli->insert_id;

            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function get_saved_billing($date, $customer) {
        $data = array();
        $sql_query = "SELECT data FROM `billing_versions` WHERE `customer` = $customer and `date` = '$date'";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function save_billing($val = array()) {

        extract($val);
        $old_data = $data;
        $data = $this->mysqli->real_escape_string($data);
        $date = $date . "-1";

        $prev = $this->get_saved_billing($date, $customer);

        foreach ($prev as $key => $value) {
            if (json_decode($old_data) == json_decode($value['data']))
                return array();
        }



        $sql_query = "INSERT INTO `billing_versions` (`id`, `customer`, `data`, `updated`, `date`) VALUES (NULL, '$customer', '$data', CURRENT_TIMESTAMP, '$date')";
        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Created successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function user_update($data = array()) {
        extract($data);

        $sql_query = "UPDATE `users` SET 
			updated = '$updated' ,
			group_id = '$group_id',
			name = '$name',
			email = '$email',
			password = '$password',
			profile_picture = '$profile_picture',
			active = '$active'
		 WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'User updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function user_tat_update($data = array()) {
        extract($data);

        $sql_query = "UPDATE `users` SET 
            tat = '$tat'
         WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'User updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function user_pic_update($data = array()) {
        extract($data);

        $sql_query = "UPDATE `users` SET 
			profile_picture = '$profile_picture'
		 WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'User updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function user_update_meta($id, $updated = array()) {

        $sql_query = "UPDATE `users` SET 
				user_meta = '$updated' 
			WHERE id = $id";
        print_r($sql_query);
        die();
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'User updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function user_update_metanew($id, $customer_code, $phone, $address, $client_code) {
        $datalist = array("customer_code" => $customer_code, "phone" => $phone, "address" => $address);
        $updated = json_encode($datalist);
        $sql_query = "UPDATE `users` SET 
                user_meta = '$updated',site_code = '$customer_code',client_code = '$client_code' 
            WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'User updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function maintenance_fees_add($updated = array(), $customer) {

        extract($updated);
        $time_ids = $this->get_user_time_id($customer);
        $time_id = $time_ids['time_id'];

        $sql_query = "INSERT INTO `maintenance` (`customer`,`time_id`,`maintenance_fee_type`,`maintenance_fee_amount`) VALUES ('$customer','$time_id','$maintenance_fee_type','$maintenance_fee_amount')";

        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'User updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function subscription_fees_add($customer, $amount) {


        $time_ids = $this->get_user_time_id($customer);
        $time_id = $time_ids['time_id'];

        $sql_query = "INSERT INTO `subscription_fees` (`customer`,`time_id`,`subscription_fees`) 
  												VALUES ('$customer','$time_id','$amount')";

        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['status'] = 'success';
            return $status;
        } else {

            $status['status'] = 'error';
            return $status;
        }
    }

    public function insert_subscription_fees($id, $time_id, $subs_amount) {

        $sql_query = "INSERT INTO `subscription_fees` (`customer`,`time_id`,`subscription_fees`) 
  												VALUES ('$id','$time_id','$subs_amount')";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['status'] = 'success';
            return $status;
        } else {

            $status['status'] = 'error';
            return $status;
        }
    }

    public function subscrptionFees_insert_with_new_time_id($customer, $amount, $time_id) {


        $sql_query = "INSERT INTO `subscription_fees` (`customer`,`time_id`,`subscription_fees`) 
  												VALUES ('$customer','$time_id','$amount')";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {

            $status['status'] = 'success';
            return $status;
        } else {

            $status['status'] = 'error';
            return $status;
        }
    }

    public function get_subscription_by_time_id($cust_id, $time_id) {

        $sql_query = "SELECT * FROM `subscription_fees` WHERE  customer = '$cust_id' AND time_id = '$time_id'";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {

            $data = false;
        }
        return $data;
    }

    public function salesforce_code_add($data = array()) {
        extract($data);
        $sql_query = "INSERT INTO `Salesforce` (`code`, `description`) VALUES ('$code', '$description')";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Salesforce code created successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function analyses_add($data = array()) {
        extract($data);
        $name = str_replace("'", "''", $name);
        $description = str_replace("'", "''", $description);
        $sql_query = "INSERT INTO `analyses` (`name`,`description`,`category`,`part_number`,`price`,`minimum_time`) VALUES ('$name','$description','$category','$part_number','$price','$minimum_time')";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'analyses created successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function miscellaneous_billing_add_during_cancellation($data = array()) {

        extract($data);
        $date = date("Y-m-d");
        $sql_query = "INSERT INTO `miscellaneous_billing` (`id`,`count`,`name`,`description`,`date`,`price`,`customer`) VALUES (NULL,$count_an,'$name_an','$description_an','$date',$rate_an, $customer)";

        //print_r($sql_query); die();
        $result = $this->mysqli->query($sql_query);

        if ($result === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function miscellaneous_billing_add($data = array()) {
        extract($data);
        $date = date("Y-m-d", strtotime($date));
        $sql_query = "INSERT INTO `miscellaneous_billing` (`id`,`count`,`name`,`description`,`date`,`price`,`customer`) VALUES (NULL,$count_an,'$name_an','$description_an','$date',$rate_an, $customer)";

        //print_r($sql_query); die();
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Miscellaneous billing item created successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function miscellaneous_billing_update($data = array()) {
        extract($data);
        $date = date("Y-m-d", strtotime($date));
        $sql_query = "UPDATE `miscellaneous_billing` SET count = '$count_an' , name = '$name_an' , description = '$description_an' , customer = '$customer' , price = '$rate_an' , date = '$date' WHERE id = $id";

        //print_r($sql_query); die();
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Miscellaneous billing item updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function miscellaneous_billing_by_date($id, $date) {
        $start_date = $date . '-01 00:00:00';
        $end_date = $date . '-31 23:59:59';
        //$sql_query = "SELECT * FROM `worksheets` WHERE ( `date` BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
        $sql_query = "SELECT * FROM miscellaneous_billing  WHERE ( `date` BETWEEN '$start_date' AND '$end_date') AND customer =" . $id;

        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function get_usermeta_by_id($id) {
        $data = array();
        $sql_query = "SELECT user_meta FROM `users` WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return json_decode($data['user_meta'], true);
    }

    public function get_user_time_id($id) {

        $data = array();
        $sql_query = "SELECT time_id FROM `timeline` WHERE customer_id = $id";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }


     public function get_client_by_id($kk) {

        $data = array();
        $sql_query = "SELECT client_code,name FROM  `users` WHERE id = $kk";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }



    public function get_user_timeline($id) {

        $data = array();
        $sql_query = "SELECT * FROM `Timeline` WHERE customer_id = $id ORDER BY time_id DESC LIMIT 1";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function update_user_timeline($time_id) {

        $date = date("Y-m");

        $valid_to = $date . '-31';

        $sql_query = "UPDATE `Timeline` SET  valid_to = '$valid_to' WHERE time_id = $time_id";
        $result = $this->mysqli->query($sql_query);
    }

    public function fetch_all_analysis($time_id) {

        $sql_query = "SELECT * FROM `Analyses_rates` WHERE time_id = $time_id";

        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function fetch_all_subscrptions($time_id) {

        $sql_query = "SELECT * FROM `subscriptions` WHERE time_id = $time_id";

        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function fetch_all_discount_range($time_id) {

        $data = array();
        $sql_query = "SELECT * FROM `discount_range` WHERE time_id = $time_id";

        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function fetch_all_maintenance($time_id) {
        $data = array();
        $sql_query = "SELECT * FROM `maintenance` WHERE time_id = $time_id";

        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function fetch_all_subscrptionfees($time_id) {

        $sql_query = "SELECT * FROM `subscription_fees` WHERE time_id = $time_id";

        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function analyses_name_by_id($id) {
        $sql_query = "SELECT name FROM `analyses` WHERE analysis_id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data['name'];
    }

    // public function analyses_rate_add($data = array()){
    // extract($data);	
    // $usermeta = $this->get_usermeta_by_id($data['customer']);
    // $time_ids = $this->get_user_time_id($customer);
    // $time_id = $time_ids['time_id'];
    // $cc_code = $usermeta['customer_code'];
    // $analysis_name = $this->analyses_name_by_id($data['analysis']);
    // $analysis_description = $cc_code.$data['code']."-".$analysis_name;	
    // $analysis_description = str_replace("'", "''", $analysis_description);
    // $sql_query = "INSERT INTO `Analyses_rates` (`analysis`,`time_id`,`customer`,`rate`,`code`,`analysis_description`) VALUES ('$analysis','$time_id','$customer',$rate,'$code','$analysis_description')";
    // $result = $this->mysqli->query($sql_query);
    // $status = array();
    // if ($result === TRUE) {
    // 	$status['type'] = 'success';
    // 	$status['msg'] = 'analyses rate created successfully';
    //     return  $status;
    // } else {
    // 	$status['type'] = 'danger';
    // 	$status['msg'] = "Error:".$this->mysqli->error;
    //     return  $status;
    // }
    // }

    public function delete_analyses_rate($customer) {

        $time_ids = $this->get_user_time_id($customer);

        $time_id = $time_ids['time_id'];

        $sql_query = "DELETE  FROM `Analyses_rates` WHERE `customer` = $customer AND `time_id` = $time_id";

        $this->mysqli->query($sql_query);
    }

    public function delete_user_subscription($customer) {

        $time_ids = $this->get_user_time_id($customer);

        $time_id = $time_ids['time_id'];

        $sql_query = "DELETE  FROM `subscriptions` WHERE `customer` = $customer AND `time_id` = $time_id";

        $this->mysqli->query($sql_query);
    }

    public function delete_user_discounts($customer) {

        $time_ids = $this->get_user_time_id($customer);

        $time_id = $time_ids['time_id'];

        $sql_query = "DELETE  FROM `discount_range` WHERE `customer` = $customer AND `time_id` = $time_id";

        $this->mysqli->query($sql_query);
    }

    // public function analyses_rate_add($analysis_ids, $customer, $rate, $code, $custom_description, $min_time) {

    public function analyses_rate_add($analysis_ids, $customer, $rate, $code, $min_time) {

        $usermeta = $this->get_usermeta_by_id($customer);

        $time_ids = $this->get_user_time_id($customer);

        $time_id = $time_ids['time_id'];

        $cc_code = $usermeta['customer_code'];

        $analysis_name = $this->analyses_name_by_id($analysis_ids);

        $analysis_description = $cc_code . $code . "-" . $analysis_name;

        $analysis_description = str_replace("'", "''", $analysis_description);

        //  $analysis_custom_description = $custom_description;
        //$analysis_custom_description = str_replace("'", "''", $analysis_custom_description);
        // $sql_query = "INSERT INTO `Analyses_rates` (`analysis`,`time_id`,`customer`,`rate`,`code`,`analysis_description`,`custom_description`,`min_time`) 
        //  VALUES ('$analysis_ids','$time_id','$customer',$rate,'$code','$analysis_description','$analysis_custom_description','$min_time')";

        $sql_query = "INSERT INTO `Analyses_rates` (`analysis`,`time_id`,`customer`,`rate`,`code`,`analysis_description`,`min_time`) 
                    VALUES ('$analysis_ids','$time_id','$customer',$rate,'$code','$analysis_description','$min_time')";
        $result = $this->mysqli->query($sql_query);

        $status = array();

        if ($result === TRUE) {

            $status['type'] = 'success';
            $status['msg'] = 'analyses rate created successfully';
            return $status;
        } else {

            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    // Function added on 22-1-2019

    public function analyses_category_add($data = array()) {
        extract($data);
        $sql_query = "INSERT INTO `analyses_category` (`category`) 
					  VALUES ('$category')";

        $result = $this->mysqli->query($sql_query);

        $status = array();

        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'analyses category created successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function subscription_add($analysis, $customer, $count) {

        $datem = date("Y-m");
        $time_ids = $this->get_user_time_id($customer);
        $time_id = $time_ids['time_id'];

        /* if(){ */
        $sql_query2 = "INSERT INTO `carry_forward` (`analysis`,`customer`,`count`,`month`) 
					VALUES ('$analysis','$customer','0','$datem')";
        $result = $this->mysqli->query($sql_query2);

        /* }	else{ */
        $sql_query = "INSERT INTO `subscriptions` (`time_id`,`analysis`,`customer`,`count`) 
				  VALUES ('$time_id','$analysis','$customer',$count)";
        $result = $this->mysqli->query($sql_query);

        /* 	} */

        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Subscription analysis created successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function carry_add($ans_id, $customer, $count, $date) {


        $sql_query = "INSERT INTO `carry_forward` (`analysis`,`customer`,`count`,`month`) 
				  VALUES ('$ans_id','$customer','$count','$date')";

        $result = $this->mysqli->query($sql_query);
    }

    public function carry_add_2($analysis, $customer, $count, $datem, $prev_carry) {



        $new_count = $count + $prev_carry;

        $id = $this->carry_exist($analysis, $customer, $datem);
        if (!$id)
            $sql_query = "INSERT INTO `carry_forward` (`analysis`,`customer`,`count`,`month`) VALUES ('$analysis','$customer','$new_count','$datem')";
        else
            $sql_query = "UPDATE `carry_forward` SET count = '$new_count' WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
    }

    public function carry_add_backup($analysis, $customer, $count, $datem) {



        $sql_query = "INSERT INTO `carry_backup` (`analysis`,`customer`,`count`,`month`) VALUES ('$analysis','$customer','$count','$datem')";
        $result = $this->mysqli->query($sql_query);
    }

    public function trunct_table() {

        $sql_delete = "TRUNCATE TABLE carry_backup";

        $this->mysqli->query($sql_delete);
    }

    public function carry_select($date, $time_id) {



        $data = array();

        $sql_query = "SELECT * FROM `carry_forward` A 
				  RIGHT JOIN `subscriptions` B 
				  ON A.customer = B.customer 
				  AND A.analysis = B.analysis
				  WHERE A.month = '$date'
				  AND B.time_id = '$time_id'";

        //WHERE A.customer IS NULL AND A.analysis IS NULL

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }

        return $data;
    }

    public function carry_select_from($date, $time_id) {
        $data = array();

        $sql_query = "SELECT * FROM `carry_backup` A 
				  RIGHT JOIN `subscriptions` B 
				  ON A.customer = B.customer 
				  AND A.analysis = B.analysis
				  WHERE A.customer IS NULL AND A.analysis IS NULL AND B.time_id = '$time_id'";

        //print_r($sql_query ); die;
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function save_invoice($data = array()) {
        extract($data);

        $created_at = time();

        $sql_query = "INSERT INTO `invoice` (`customer_id`,`created_at`,`date`,`total_before_dicount`,`discount_percnt`,`discount`,`total_after_dicount`,`subs_amount`,`maint_fees`,`maint_fees_type`,`grand_total`) VALUES ('$customer_id','$created_at','$date','$total_before_dicount','$discount_percnt','$discount','$total_after_dicount','$subs_amount','$maint_fees','$maint_fees_type','$grand_total')";

        $result = $this->mysqli->query($sql_query);

        $invoice_id = $this->mysqli->insert_id;

        if ($ans_id == 0) {
            
        } else {
            foreach ($ans_id as $key => $ans_ids) {

                $sql_query = "INSERT INTO `invoice_details` (`invoice_id`,`ans_id`,`ans_name`,`total_subscribed`,`used`,`balance_carry`,`extra_used`,`rate`,`total`) VALUES ('$invoice_id','$ans_ids','$ans_name[$key]','$total_subscribed[$key]','$used[$key]','$balance_carry[$key]','$extra_used[$key]','$rate[$key]','$total[$key]')";

                $result = $this->mysqli->query($sql_query);
            }
        }

        if ($ad_ans_id == 0) {
            
        } else {

            foreach ($ad_ans_id as $key => $ad_ans_ids) {

                $sql_query = "INSERT INTO `additiional_invoice_details` (`invoice_id`,`ans_id`,`ans_name`,`rate`,`qty`,`total`) VALUES ('$invoice_id','$ad_ans_ids','$ad_ans_name[$key]','$ad_ans_rate[$key]','$ad_ans_qty[$key]','$ad_ans_total[$key]')";

                $result = $this->mysqli->query($sql_query);
            }
        }
        print_r($this->mysqli->error);

        if ($result) {

            print_r("successs");
        }
    }

    public function analyses_update($data = array()) {
        extract($data);
        $sql_query = "UPDATE `analyses` SET description = '$description' , name = '$name' , part_number = '$part_number' , price = '$price', minimum_time='$minimum_time' WHERE id = $id";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Analyse updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    // Function added on 22-1-19

    public function analyses_category_update($data = array()) {
        extract($data);
        $sql_query = "UPDATE `analyses_category` SET category='$category' WHERE id = $id";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'analyses Category updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function weekly_case_count() {
        $query = "SELECT DATE_FORMAT(created,'%d') day, COUNT(created) count FROM `Clario` WHERE MONTH(created) = MONTH(CURRENT_DATE()) AND YEAR(created) = YEAR(CURRENT_DATE()) GROUP by DATE_FORMAT(created,'%d')";

        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

//  public function analyses_rate_insert_with_new_time_id($analysis,$customer,$rate,$code,$analysis_description,$custom_description,$time_id){
//		
//		
//
//		echo $sql_query = "INSERT INTO `Analyses_rates` (`analysis`,`time_id`,`customer`,`rate`,`code`,`analysis_description`,`custom_description`) VALUES ('$analysis','$time_id','$customer',$rate,'$code','$analysis_description','$custom_description')";
//
//                
////                $sql_query = "INSERT INTO `Analyses_rates` (`analysis`,`time_id`,`customer`,`rate`,`code`,`analysis_description`,`custom_description`,`min_time`) 
////					  VALUES ('$analysis_ids','$time_id','$customer',$rate,'$code','$analysis_description','$analysis_custom_description','$min_time')";
//
//
//		$result = $this->mysqli->query($sql_query);
//
//		$status = array();
//		if ($result === TRUE) {
//			$status['type'] = 'success';
//			$status['msg'] = 'analyses rate created successfully';
//		    return  $status;
//		} else {
//			$status['type'] = 'danger';
//			$status['msg'] = "Error:".$this->mysqli->error;
//		    return  $status;
//		}
//  }

    public function analyses_rate_insert_with_new_time_id($analysis, $customer, $rate, $code, $analysis_description, $custom_description, $time_id, $min_time) {



        //echo $sql_query = "INSERT INTO `Analyses_rates` (`analysis`,`time_id`,`customer`,`rate`,`code`,`analysis_description`,`custom_description`) VALUES ('$analysis','$time_id','$customer',$rate,'$code','$analysis_description','$custom_description')";


        $sql_query = "INSERT INTO `Analyses_rates` (`analysis`,`time_id`,`customer`,`rate`,`code`,`analysis_description`,`custom_description`,`min_time`) 
					  VALUES ('$analysis','$time_id','$customer',$rate,'$code','$analysis_description','$custom_description','$min_time')";

        $result = $this->mysqli->query($sql_query);

        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'analyses rate created successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function subscription_rate_insert_with_new_time_id($month, $analysis, $customer, $count, $time_id) {



        $sql_query = "INSERT INTO `subscriptions` (`month`,`analysis`,`customer`,`count`,`time_id`) 
						VALUES ('$month','$analysis','$customer',$count,'$time_id')";

        $result = $this->mysqli->query($sql_query);

        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'analyses rate created successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function discount_range_insert_with_new_time_id($customer, $minimum_value, $maximum_value, $percentage, $time_id) {



        $sql_query = "INSERT INTO `discount_range` (`customer`,`minimum_value`,`maximum_value`,`percentage`,`time_id`)  		VALUES ('$customer','$minimum_value','$maximum_value',$percentage,'$time_id')";

        $result = $this->mysqli->query($sql_query);

        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'analyses rate created successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function maintenance_insert_with_new_time_id($customer, $maintenance_fee_type, $maintenance_fee_amount, $time_id) {




        $sql_query = "INSERT INTO `maintenance` (`customer`,`maintenance_fee_type`,`maintenance_fee_amount`,`time_id`) VALUES ('$customer','$maintenance_fee_type','$maintenance_fee_amount','$time_id')";

        $result = $this->mysqli->query($sql_query);

        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'analyses rate created successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function analyses_rate_update($data = array(), $time_id) {

        extract($data);
        $usermeta = $this->get_usermeta_by_id($data['customer']);
        $cc_code = $usermeta['customer_code'];
        $analysis_name = $this->analyses_name_by_id($data['analysis']);

        $analysis_description = $cc_code . $data['code'] . "-" . $analysis_name;

        $analysis_description = str_replace("'", "''", $analysis_description);

        // $sql_query = "UPDATE `Analyses_rates` SET rate = '$rate' , analysis = '$analysis', code = '$code' , customer = $customer , analysis_description = '$analysis_description'  WHERE time_id = $time_id AND customer = $customer AND analysis = $analysis";

        $sql_query = "UPDATE `Analyses_rates` SET rate = '$rate' , analysis = '$analysis', code = '$code' , customer = $customer , analysis_description = '$analysis_description', min_time='$min_time', custom_description='$custom_description'  WHERE time_id = $time_id AND customer = $customer AND analysis = $analysis";

        /* print_r($sql_query); die; */
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'analyses rate updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function subscription_update($data = array(), $time_id) {
        extract($data);
        $sql_query = "UPDATE `subscriptions` SET count = '$count' , analysis = '$analysis', customer = $customer WHERE time_id = $time_id AND customer = $customer AND analysis = '$analysis'";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Subscriptions analyses updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function update_maintenance_fees_add($data = array(), $time_id) {
        extract($data);
        $sql_query = "UPDATE `maintenance` 
						SET maintenance_fee_type = '$maintenance_fee_type' ,
							 maintenance_fee_amount = '$maintenance_fee_amount',
							  customer = $customer 
							  WHERE time_id = $time_id 
							  AND customer = $customer";

        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Subscriptions analyses updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function is_exist_maintenance_fees($customer_id) {

        $sql_query = "SELECT * FROM `maintenance` WHERE customer = $customer_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function is_exist_analysis_of_user($customer_id) {

        $sql_query = "SELECT * FROM `Analyses_rates` WHERE customer = $customer_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {

            return false;
        }
    }

    public function is_exist_discount_of_user($customer_id) {

        $sql_query = "SELECT * FROM `discount_range` WHERE customer = $customer_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {

            return false;
        }
    }

    public function is_exist_subcri_of_user($customer_id) {

        $sql_query = "SELECT * FROM `subscriptions` WHERE customer = $customer_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {

            return false;
        }
    }

    public function is_exist_subscription_fees($customer_id) {



        $sql_query = "SELECT * FROM `subscription_fees` WHERE customer = $customer_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {

            return true;
        } else {

            return false;
        }
    }

    public function salesforce_code_update($data = array()) {
        extract($data);
        $sql_query = "UPDATE `Salesforce` SET code = '$code' , description = '$description' WHERE id = $id";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Salesforce code updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function salesforce_code($page_now = "", $page_url) {
        $data = array();
        $sql_query = "SELECT * FROM `Salesforce`";

        $data['pagination'] = $this->pagination($page_now, $sql_query, $page_url);
        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " ORDER BY id DESC LIMIT $start_from, 10";
        } else {
            $sql_query .= " ORDER BY id DESC LIMIT 0, 10";
        }


        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function analyses($key = "", $page_now = "", $page_url) {
        $data = array();
        //$sql_query = "SELECT * FROM `analyses`";	

        $sql_query = "SELECT analyses.id, analyses.name, analyses.description, analyses.price, analyses.minimum_time, analyses.part_number, analyses_category.category FROM analyses  JOIN analyses_category ON analyses.category = analyses_category.id";

        //

        if ($key != '') {
            $sql_query .= " WHERE analyses.name LIKE '%$key%' ";
        }

        $data['pagination'] = $this->pagination($page_now, $sql_query, $page_url, $key);
        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " ORDER BY analyses.name ASC LIMIT $start_from, 10";
        } else {
            $sql_query .= " ORDER BY analyses.name ASC LIMIT 0, 10";
        }

        //echo $sql_query; die;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }

        //print_r($data);die;
        return $data;
    }

    public function miscellaneous_billing($key = "", $page_now = "", $page_url) {
        $data = array();
        //$sql_query = "SELECT * FROM `analyses`";	

        $sql_query = "SELECT * FROM miscellaneous_billing ";

        //

        if ($key != '') {
            $sql_query .= " WHERE name LIKE '%$key%' ";
        }

        $data['pagination'] = $this->pagination($page_now, $sql_query, $page_url, $key);
        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " ORDER BY id LIMIT $start_from, 10";
        } else {
            $sql_query .= " ORDER BY id LIMIT 0, 10";
        }

        //echo $sql_query; die;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }

        //print_r($data);die;
        return $data;
    }

    public function analyses_rate($page_now = "", $page_url) {
        $data = array();
        $sql_query = "SELECT * FROM `Analyses_rates`";

        $data['pagination'] = $this->pagination($page_now, $sql_query, $page_url);
        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " ORDER BY id DESC LIMIT $start_from, 10";
        } else {
            $sql_query .= " ORDER BY id DESC LIMIT 0, 10";
        }


        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    /*
     * Analyses Category
     * Functions added on 22-1-2019
     */

    public function analyses_category($page_now = "", $page_url) {
        $data = array();
        $sql_query = "SELECT * FROM `analyses_category`";

        $data['pagination'] = $this->pagination($page_now, $sql_query, $page_url);
        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " ORDER BY id DESC LIMIT $start_from, 10";
        } else {
            $sql_query .= " ORDER BY id DESC LIMIT 0, 10";
        }


        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function subscriptions_user($user) {
        $data = array();

        $time_ids = $this->get_user_time_id($user);
        $time_id = $time_ids['time_id'];

        $sql_query = "SELECT * FROM `subscriptions` WHERE `customer` = $user AND `time_id` = $time_id";

        //$sql_query = "SELECT * FROM subscriptions JOIN analyses_rates ON  subscriptions.customer = Analyses_rates.customer   WHERE subscriptions.customer = $user";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function carry_forward($cid, $date, $dateend) {

        $data = array();

        // $cid = implode("','", $cid);
        foreach ($cid as $kk) {
            $sql_query = "SELECT * FROM carry_forward JOIN analyses ON analyses.id = carry_forward.analysis  WHERE `customer` = $kk AND (`month` >= '$date' AND `month` <= '$dateend')";
            // print_r($sql_query);

            $result = $this->mysqli->query($sql_query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[$kk][] = $row;
                }
            } else {
                $data = false;
            }
        }
        return $data;
    }

    public function carry_forward_with_cust_ans_id($cid, $pre_date, $ans_id) {



        $data = array();

        $sql_query = "SELECT * FROM carry_forward  WHERE `customer` = $cid AND `month` = '$pre_date' AND `analysis` = $ans_id ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function carry_forward_data($cid, $ans, $date) {

        $data = array();
        $sql_query = "SELECT * FROM carry_forward JOIN analyses ON analyses.id = carry_forward.analysis  WHERE `customer` = $cid AND `month` = '$date' AND `analysis` = $ans ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function analyses_rate_user($user) {
        $data = array();

        $time_ids = $this->get_user_time_id($user);

        $time_id = $time_ids['time_id'];

        $sql_query = "SELECT * FROM `Analyses_rates` WHERE `customer` = $user AND `time_id` = $time_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function subscibed_user($user) {
        $data = array();

        $time_ids = $this->get_user_time_id($user);
        $time_id = $time_ids['time_id'];

        $sql_query = "SELECT * FROM `subscriptions` WHERE `customer` = $user AND `time_id` = $time_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function discounted_user($user) {

        $data = array();

        $time_ids = $this->get_user_time_id($user);

        $time_id = $time_ids['time_id'];

        $sql_query = "SELECT * FROM `discount_range` WHERE `customer` = $user AND `time_id` = $time_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function get_maintenance_by_customer($user) {
        $data = array();

        $sql_query = "SELECT * FROM `maintenance` WHERE `customer` = $user ORDER BY time_id ASC";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function get_subscription_by_customer($user) {
        $data = array();

        $sql_query = "SELECT * FROM `subscription_fees` WHERE `customer` = $user ORDER BY time_id ASC";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function get_maintenance_by_customer_and_time_Id($user, $time_id) {


        $data = array();
        //$user = implode("','", $user);
        $monthly = 'monthly';

        $sql_query = "SELECT * FROM `maintenance` 
						WHERE `customer` = '$user' AND time_id = '$time_id' AND maintenance_fee_type = '$monthly'";
        // print_r($sql_query);

        $result = $this->mysqli->query($sql_query);

        $this->debug($this->mysqli->error);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function salesforce_code_full() {
        $data = array();
        $sql_query = "SELECT * FROM `Salesforce` ORDER BY id DESC";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function table_full($table, $where = '') {
        $data = array();
        // $sql_query = "SELECT t1.*,t2.id as uid,t2.name as cus FROM miscellaneous_billing t1 INNER JOIN users t2 ON t1.customer=t2.id ORDER BY t1.id DESC";
        $sql_query = "SELECT * FROM $table $where ORDER BY id DESC";
        //  $sql_query = "SELECT * FROM $table $where AND  active = 1 ORDER BY name";
        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function table_full_co($table, $where = '') {
        $data = array();
        // $sql_query = "SELECT t1.*,t2.id as uid,t2.name as cus FROM miscellaneous_billing t1 INNER JOIN users t2 ON t1.customer=t2.id ORDER BY t1.id DESC";
        //  $sql_query = "SELECT * FROM $table $where ORDER BY id DESC";
        $sql_query = "SELECT * FROM $table $where AND  active = 1 ORDER BY name";
        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function basic_users_by_group($group_id) {
        $data = array();
        $sql_query = "SELECT user_id, user_name FROM users WHERE user_type_ids = '$group_id' AND is_active = 1 ORDER BY user_name";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function table_full_miscellaneous_billing($table, $where = '') {
        $data = array();
        $sql_query = "SELECT t1.*,t2.id as uid,t2.name as cus FROM miscellaneous_billing t1 INNER JOIN users t2 ON t1.customer=t2.id ORDER BY t1.id DESC";
        // $sql_query = "SELECT * FROM $table $where ORDER BY id DESC";
        //  $sql_query = "SELECT * FROM $table $where AND  active = 1 ORDER BY name";
        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function table_full_name_desc($table, $where = '') {
        $data = array();
        $sql_query = "SELECT * FROM $table $where ORDER BY name DESC";
        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function table_full_name_asc($table, $where = '') {
        $data = array();
        $sql_query = "SELECT * FROM $table $where ORDER BY name ASC";
        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function salesforce_code_by_id($id) {
        $sql_query = "SELECT * FROM `Salesforce` WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function analyses_by_id($id) {
        $sql_query = "SELECT * FROM `analyses` WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function analyses_rate_by_id($id) {
        $sql_query = "SELECT * FROM `Analyses_rates` WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    // Function added on 22-1-2019

    public function analyses_category_by_id($id) {
        $sql_query = "SELECT * FROM `analyses_category` WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function salesforce_code_by_code($code) {
        $sql_query = "SELECT * FROM `Salesforce` WHERE code = $code";

        $data = false;
        $result = $this->mysqli->query($sql_query);
        if (isset($result) && is_object($result)) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data = $row;
                }
            }
        }
        return $data;
    }

    public function analyses_by_code($id) {
        $sql_query = "SELECT * FROM `analyses` WHERE id = $id";

        $data = false;
        $result = $this->mysqli->query($sql_query);
        if (isset($result) && is_object($result)) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data = $row;
                }
            }
        }
        return $data;
    }

    public function get_by_id($table, $id) {
        $sql_query = "SELECT * FROM `$table` WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else
            $data = false;
        return $data;
    }

    public function get_by_field($table, $field, $val) {
        $sql_query = "SELECT * FROM `$table` WHERE $field = $val";
        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else
            $data = false;
        return $data;
    }

    public function get_by_clario_id($id) {
        $sql_query = "SELECT * FROM `worksheets` WHERE clario_id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    /*  public function get_wsheet_id_customer($cid, $date, $edate) {
      $start_date = $date . '-01 00:00:00';
      $end_date = $edate . '-31 23:59:59';
      // foreach($cid as $kk){
      //  echo $kk;
      //}
      //print_r($cid);
      // $cid = implode("','", $cid);
      //$sql_query = "SELECT * FROM `worksheets` WHERE ( `date` BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
      foreach ($cid as $kk) {

      $sql_query = "SELECT * FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id  WHERE ( worksheets.date BETWEEN '$start_date' AND '$end_date') AND worksheets.customer_id IN ('" . $cid . "') AND worksheets.status = 'Completed' ";

      // print_r($sql_query);

      /*  $result = $this->mysqli->query($sql_query);
      if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
      if($row)
      $data[] = $row;
      }
      } else {
      $data = false;
      } */

    // print_r($data);
    //return $data;
    //} */

    public function get_wsheet_id_customer($cid, $date, $edate) {
        $start_date = $date . '-01 00:00:00';
        $end_date = $edate . '-31 23:59:59';
        // foreach($cid as $kk){
        //  echo $kk;
        //}
        //print_r($cid);
        // $cid = implode("','", $cid);
        //$sql_query = "SELECT * FROM `worksheets` WHERE ( `date` BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
        foreach ($cid as $kk) {

            $sql_query = "SELECT * FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id  WHERE ( worksheets.date BETWEEN '$start_date' AND '$end_date') AND worksheets.customer_id = $kk AND worksheets.status = 'Completed' ";

            $result = $this->mysqli->query($sql_query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row)
                        $data[$kk][] = $row;
                }
            } else {
                $data = false;
            }
        }
        return $data;
    }

    /*  public function get_wsheet_id_customer($cid, $date) {
      $start_date = $date . '-01 00:00:00';
      $end_date = $date . '-31 23:59:59';
      //$sql_query = "SELECT * FROM `worksheets` WHERE ( `date` BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
      $sql_query = "SELECT * FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id  WHERE ( worksheets.date BETWEEN '$start_date' AND '$end_date') AND worksheets.customer_id  = $cid AND worksheets.status = 'Completed' ";

      //print_r($sql_query);

      $result = $this->mysqli->query($sql_query);
      if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
      $data[] = $row;
      }
      } else {
      $data = false;
      }
      return $data;
      } */

    public function billing_summary_detailed($cid, $date) {

        $start_date = $date . '-01 00:00:00';
        $end_date = $date . '-31 23:59:59';
        //$sql_query = "SELECT * FROM `worksheets` WHERE ( `date` BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
        $sql_query = "SELECT * FROM Clario INNER JOIN worksheets ON Clario.id = worksheets.clario_id  WHERE ( worksheets.date BETWEEN '$start_date' AND '$end_date') AND worksheets.customer_id  = $cid AND worksheets.status = 'Completed'"; // GROUP BY Clario.id";
        //print_r($sql_query); 

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function billing_summary_detailed_new($cid, $date, $edate) {

        $start_date = $date . '-01 00:00:00';
        $end_date = $edate . '-31 23:59:59';
        //    echo $start_date;
        //   echo $end_date;
        //  $cid = implode("','", $cid);
        //$sql_query = "SELECT * FROM `worksheets` WHERE ( `date` BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
        foreach ($cid as $kk) {
            $sql_query = "SELECT * FROM Clario INNER JOIN worksheets ON Clario.id = worksheets.clario_id  WHERE ( worksheets.date BETWEEN '$start_date' AND '$end_date') AND worksheets.customer_id  = $kk AND worksheets.status = 'Completed'"; // GROUP BY Clario.id";
            //print_r($sql_query); 
            //  $data = array();
            $result = $this->mysqli->query($sql_query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[$kk][] = $row;
                    // $data[] = $row;
                    // $data = true;
                    // print_r($row);
                }
            } //else {
            /// $data = false;
            //}  
        }

        return $data;
    }

    public function billing_summary_detailed_basic($cid, $date, $edate) {

        $start_date = $date . '-01 00:00:00';
        $end_date = $edate . '-31 23:59:59';
        foreach ($cid as $kk) {
            $sql_query = "SELECT worksheets.id, clario.patient_name, clario.mrn, clario.review_user_id,clario.assignee, worksheets.date, clario.webhook_customer,clario.accession, worksheets.custom_analysis_description, worksheets.analyst_hours FROM Clario INNER JOIN worksheets ON Clario.id = worksheets.clario_id  WHERE ( worksheets.date BETWEEN '$start_date' AND '$end_date') AND worksheets.customer_id  = $kk AND worksheets.status = 'Completed'"; // GROUP BY Clario.id";

            $result = $this->mysqli->query($sql_query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[$kk][] = $row;
                }
            }
        }

        return $data;
    }

    public function billing_summary_analyst($cid, $date, $edate) {
        //echo $date;exit;
        $datearr = explode('-', $date);
        //print_r($datearr);exit;
        $year = $datearr[0];
        $month = $datearr[1];

        $start_date = $date . '-01 00:00:00';
        $end_date = $date . '-31 23:59:59';
        //$sql_query = "SELECT * FROM `worksheets` WHERE ( `date` BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
        $sql_query = "SELECT * FROM Clario INNER JOIN worksheets ON Clario.id = worksheets.clario_id WHERE MONTH(Clario.created) = '$month' AND YEAR(Clario.created) = '$year' AND worksheets.analyst  = $cid AND Clario.assignee  = $cid AND Clario.status = 'Completed' ORDER BY Clario.created ASC";
        // $sql_query = "SELECT * FROM Clario INNER JOIN worksheets ON Clario.id = worksheets.clario_id WHERE ( worksheets.date BETWEEN '$start_date' AND '$end_date') AND worksheets.analyst  = $cid AND worksheets.status = 'Completed'"; // GROUP BY Clario.id";
        //print_r($sql_query); 
        // print_r($sql_query); 
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function billing_summary_analyst_new($cid, $date, $edate) {
        //echo $date;exit;

        $datearr = explode('-', $date);
        //print_r($datearr);exit;
        $year = $datearr[0];
        $month = $datearr[1];

        $start_date = $date . '-01 00:00:00';
        $end_date = $edate . '-31 23:59:59';
        //$sql_query = "SELECT * FROM `worksheets` WHERE ( `date` BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
        /*  $sql_query = "SELECT * FROM Clario INNER JOIN worksheets ON Clario.id = worksheets.clario_id WHERE MONTH(Clario.created) = '$month' AND YEAR(Clario.created) = '$year' AND worksheets.analyst  = $cid AND Clario.assignee  = $cid AND Clario.status = 'Completed' ORDER BY Clario.created ASC";  */
        // $sql_query = "SELECT * FROM Clario INNER JOIN worksheets ON Clario.id = worksheets.clario_id WHERE ( worksheets.date BETWEEN '$start_date' AND '$end_date') AND worksheets.analyst  = $cid AND worksheets.status = 'Completed'"; // GROUP BY Clario.id";
        //print_r($sql_query); 
        foreach ($cid as $kk) {
            $sql_query = "SELECT * FROM Clario INNER JOIN worksheets ON Clario.id = worksheets.clario_id WHERE ( Clario.created BETWEEN '$start_date' AND '$end_date') AND worksheets.analyst  = $kk AND Clario.assignee  = $kk AND Clario.status = 'Completed' ORDER BY Clario.created ASC";

            $result = $this->mysqli->query($sql_query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[$kk][] = $row;
                }
            } else {
                $data = false;
            }
        }
        return $data;
    }

    public function billing_summary_analyst_all($date = null) {
        if (empty($date)) {
            $date = date('Y-m');
        }
        $datearr = explode('-', $date);
        //print_r($datearr);exit;
        $year = $datearr[0];
        $month = $datearr[1];

        $start_date = $date . '-01 00:00:00';
        $end_date = $date . '-31 23:59:59';
        //$sql_query = "SELECT * FROM `worksheets` WHERE ( `date` BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
        echo $sql_query = "SELECT * FROM Clario INNER JOIN worksheets ON Clario.id = worksheets.clario_id WHERE MONTH(Clario.created) = '$month' AND YEAR(Clario.created) = '$year' AND Clario.status = 'Completed' ORDER BY Clario.created ASC"; // GROUP BY Clario.id";
        //print_r($sql_query); 

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    /* public function get_time_id_by_date($date,$edate,$ids) {

      $start_date = $date . '-01';
      $end_date = $edate. '-31';

      $ids = implode("','", $ids);

      $sql_query = "SELECT * FROM Timeline  WHERE '$start_date' BETWEEN Timeline.valid_from AND Timeline.valid_to AND '$end_date' BETWEEN Timeline.valid_from AND Timeline.valid_to  AND Timeline.customer_id = $ids";

      print_r($sql_query);
      $result = $this->mysqli->query($sql_query);
      if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
      $data[] = $row;
      }
      } else {
      $data = false;
      }
      return $data;
      } */

    public function get_time_id_by_date($date, $edate, $ids) {

        $start_date = $date . '-01';
        $end_date = $edate . '-31';

        // $ids = implode("','", $ids);
        foreach ($ids as $kk) {
            $sql_query = "SELECT * FROM Timeline  WHERE '$start_date' BETWEEN Timeline.valid_from AND Timeline.valid_to AND '$end_date' BETWEEN Timeline.valid_from AND Timeline.valid_to  AND Timeline.customer_id = $kk";

            $result = $this->mysqli->query($sql_query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[$kk][] = $row;
                }
            } else {
                $data = false;
            }
        }
        return $data;
    }

    public function get_customers_with_time_id() {

        $date = date("Y-m-d");
        //$date = "2018-11-02";


        $sql_query = "SELECT * FROM Timeline  WHERE '$date' BETWEEN Timeline.valid_from AND Timeline.valid_to";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function find_customers_with_id($cid) {

        $sql_query = "SELECT * FROM users  WHERE id ='$cid'";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function get_all_customers() {


        $sql_query = "SELECT * FROM users  WHERE user_type_ids ='5' and active = '1' ORDER BY name ASC";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function get_all_analysts() {


        $sql_query = "SELECT * FROM users  WHERE user_type_ids ='3' and active = '1' ORDER BY name ASC";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function worksheet_detials($wsheet_ids) {


        $sql_query = "SELECT worksheet_id, ans_id,rate, qty 
					  FROM worksheet_details  
					  where worksheet_id = '$wsheet_ids'";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    /* public function get_wsheet_details_wsheet_id($wsheet_ids, $size) {


      if ($size == 1) {



      $str_wsheet = implode(", ", $wsheet_ids);



      $sql_query = "SELECT worksheet_id, ans_id,rate, qty
      FROM worksheet_details
      where worksheet_id = '$str_wsheet'";
      } else {

      $str_wsheet = implode(", ", $wsheet_ids);

      $sql_query = "SELECT worksheet_id, ans_id,rate, SUM(qty) AS qty
      FROM worksheet_details
      where worksheet_id IN ($str_wsheet)
      GROUP BY ans_id";
      }

      //print_r($sql_query);






      // select worksheet_id, ans_id, SUM(qty) as qty from worksheet_details  where worksheet_id = 85 OR worksheet_id = 86 GROUP BY ans_id
      //select worksheet_id, ans_id, SUM(qty) as qty from worksheet_details  where worksheet_id IN (85,86) GROUP BY ans_id





      $result = $this->mysqli->query($sql_query);
      if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
      $data[] = $row;
      }
      } else {
      $data = false;
      }
      return $data;
      } */

    public function get_wsheet_details_wsheet_id($wsheet_ids, $size) {


        if ($size == 1) {



            $str_wsheet = implode(", ", $wsheet_ids);

            $sql_query = "SELECT worksheet_id, ans_id,rate, qty 
                      FROM worksheet_details  
                      where worksheet_id = '$str_wsheet'";
        } else {

            $str_wsheet = implode(", ", $wsheet_ids);

            $sql_query = "SELECT worksheet_id, ans_id,rate, SUM(qty) AS qty 
                    FROM worksheet_details  
                    where worksheet_id IN ($str_wsheet)
                    GROUP BY ans_id";
        }






        // select worksheet_id, ans_id, SUM(qty) as qty from worksheet_details  where worksheet_id = 85 OR worksheet_id = 86 GROUP BY ans_id
        //select worksheet_id, ans_id, SUM(qty) as qty from worksheet_details  where worksheet_id IN (85,86) GROUP BY ans_id





        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function get_rate_by_anid_cid($anid, $cid) {
        $data = array();
        $sql_query = "SELECT * FROM `Analyses_rates` WHERE analysis = $anid && customer =  $cid";

        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function clario_import($data = array()) {
        $sql_query = "INSERT INTO `Clario` VALUES (NULL,'$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','','$data[6]','$data[7]','$data[8]','$data[9]',0,0,'','')";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Dicom Details imported successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function hospital_add($name) {
        $sql_query = "INSERT INTO `hospital` VALUES (NULL,'$name')";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Hospital added successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function clario_hospitals() {
        $sql_query = "SELECT DISTINCT hospital FROM Clario";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else
            $data = false;
        return $data;
    }

    public function clario_sites() {
        $sql_query = "SELECT DISTINCT site FROM Clario";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else
            $data = false;
        return $data;
    }

    public function analyses_code_by_code_individ($code) {
        $sql_query = "SELECT * FROM `Analyses_rates` WHERE code = '$code'";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else
            $data = false;
        return $data;
    }

    public function users($key = "", $page_now = "") {
        $data = array();
        $sql_query = "SELECT * FROM `users`";

        if ($key != '') {
            $sql_query .= " WHERE name LIKE '%$key%' OR email LIKE '%$key%' ";
        }

        $data['pagination'] = $this->pagination($page_now, $sql_query, SITE_URL . '/admin/user', $key);

        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " ORDER BY id DESC LIMIT $start_from, 10";
        } else {
            $sql_query .= " ORDER BY id DESC LIMIT 0, 10";
        }


        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function customer($key = "", $page_now = "") {



        $data = array();
        $sql_query = "SELECT * FROM `users`";

        if ($key != '') {
            $sql_query .= " WHERE user_type_ids = 5 AND (name LIKE '%$key%' OR email LIKE '%$key%') ";
        } else {
            $sql_query .= " WHERE user_type_ids = 5";
        }


        $data['pagination'] = $this->pagination($page_now, $sql_query, SITE_URL . '/admin/customer', $key);

        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " ORDER BY name ASC LIMIT $start_from, 10";
        } else {
            $sql_query .= " ORDER BY name ASC LIMIT 0, 10";
        }

        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function user_by_id($id) {
        $data = array();
        $sql_query = "SELECT * FROM `users` WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function dicom_details($page_now = "", $page_url) {
        $data = array();
        $sql_query = "SELECT * FROM `Clario` WHERE assignee = 0";

        $data['pagination'] = $this->pagination($page_now, $sql_query, $page_url);
        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " LIMIT $start_from, 10";
        } else {
            $sql_query .= " LIMIT 0, 10";
        }


        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function dicom_details_assigned($page_now = "", $page_url) {
        $data = array();
        $sql_query = "SELECT * FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id";

        $data['pagination'] = $this->pagination($page_now, $sql_query, $page_url);
        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " LIMIT $start_from, 10";
        } else {
            $sql_query .= " LIMIT 0, 10";
        }


        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function dicom_details_assigned_by_id($id) {
        $data = array();
        $sql_query = "SELECT * FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id WHERE Clario.id = $id";

        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else
            $data = false;
        return $data;
    }

    public function analysis_data_by_ids($analysis_id, $customer_id) {
        $data = array();
        $sql_query = "SELECT analyses.id,analyses.name,Analyses_rates.code,Analyses_rates.rate FROM analyses JOIN analyses_rates ON analyses.id = Analyses_rates.analysis WHERE analyses.id = $analysis_id and Analyses_rates.customer = $customer_id";
        /* $sql_query = "SELECT analyses.id,analyses.name,Analyses_rates.code FROM analyses JOIN analyses_rates ON analyses.id = Analyses_rates.analysis WHERE analyses.id = $analysis_id"; */

        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else
            $data = false;
        return $data;
    }

    public function dicom_detail_by_id($page_now = "", $page_url, $id) {
        $data = array();
        $sql_query = "SELECT * FROM `Clario` WHERE assignee = $id";

        $data['pagination'] = $this->pagination($page_now, $sql_query, $page_url);
        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " LIMIT $start_from, 10";
        } else {
            $sql_query .= " LIMIT 0, 10";
        }


        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function worksheet_assign($assignee, $work_id, $customer, $tat) {

        // extract($data);
        $sql_query = "UPDATE `Clario` SET assignee = '$assignee' , customer = '$customer', tat= '$tat', status = 'In progress'  WHERE id = $work_id";
        $result = $this->mysqli->query($sql_query);
        // print_r($sql_query); die();
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Worksheet assigned successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function insert_wsheet($data = array()) {
        extract($data);

        $time_ids = $this->get_user_time_id($customer_id);
        $time_id = $time_ids['time_id'];

        $analyst_hours = round($analyst_hours, 2);
        //    $expected_time = $expected_time;
        $medical_director_hours = round($medical_director_hours, 2);
        $image_specialist_hours = round($image_specialist_hours, 2);
        // $other_notes = mysqli_escape_string($other_notes);
        // $other_notes = "test";

        if (empty($txtReviewAnalyst)) {
            $txtReviewAnalyst = 0;
        }

        $sql_query = "INSERT INTO `worksheets` (`id`,`time_id`, `analyst`, `clario_id`, `date`, `other`, `analyses_performed`, `custom_analysis_description`, `other_notes`, `addon_flows`, `analyst_hours`, `expected_time`, `image_specialist_hours`, `medical_director_hours`, `pia_analysis_codes`, `status`, `customer_id`, `analyses_ids`, `existing_rate`,`any_mint`,`review_user_id`) VALUES (NULL,'$time_id','$analyst', '$clario_id', '$date', '$other', '$analyses_performed', '$custom_analysis_description', '$other_notes', '$addon_flows', '$analyst_hours', '$expected_time', '$image_specialist_hours', '$medical_director_hours', '$pia_analysis_codes', '$status', '$customer_id', '$analyses_ids', '$existing_rate','$ans_hr','$txtReviewAnalyst')";

        $sql_query_clario = "UPDATE `Clario` SET status = '$status',assignee = $analyst,review_user_id='$txtReviewAnalyst' WHERE  id = $clario_id";

        $res_Clario = $this->mysqli->query($sql_query_clario);

        $result = $this->mysqli->query($sql_query);

        $worksheet_id = $this->mysqli->insert_id;

        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Worksheet Added successfully';
            $status['worksheet_id'] = $worksheet_id;

            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function update_tat($data = array()) {
        extract($data);
        if (!empty($tat)) {
            $sql_query = "UPDATE `Clario` SET tat= '$tat'  WHERE id = $clario_id";
            $result = $this->mysqli->query($sql_query);

            if ($result === TRUE) {
                return true;
            }
        }
        return false;
    }

    public function insert_wsheet_details($data = array(), $worksheet_id, $ans_id, $rate, $qty, $customer) {
        extract($data);

        $sql_query = "INSERT INTO `worksheet_details` (`worksheet_id`,`customer_id`, `date`, `ans_id`, `rate`, `qty`) 
													   VALUES ('$worksheet_id','$customer','$date', '$ans_id', '$rate', '$qty')";
        $result = $this->mysqli->query($sql_query);

        //$status['sql']	=	$sql_query;
        $status = array();
        $status['sql'] = $sql_query;
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Worksheet Added successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function empty_wsheet_details($worksheet_id) {

        $sql_query = "DELETE FROM worksheet_details WHERE worksheet_id= $worksheet_id";

        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Worksheet Added successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function update_wsheet_completed_time($data) {
        extract($data);

        $cdate = date("Y-m-d H:i:s");

        $sql_query = "UPDATE `worksheets` SET completed_time = '$cdate' WHERE  clario_id = $clario_id";
        $result = $this->mysqli->query($sql_query);
        if ($result === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function update_wsheet($data) {

        extract($data);

        /*
          $time_ids = $this->get_user_time_id($customer_id);

          $time_id = $time_ids['time_id']; */

        $analyst_hours = round($analyst_hours, 2);
        $medical_director_hours = round($medical_director_hours, 2);
        $image_specialist_hours = round($image_specialist_hours, 2);
        //$other_notes = mysqli_real_escape_string($other_notes);
        $other_notes = $this->mysqli->real_escape_string($other_notes);

        if (empty($txtReviewAnalyst)) {
            $txtReviewAnalyst = 0;
        }


        $sql_query = "UPDATE `worksheets` SET other = '$other', analyses_performed = '$analyses_performed', custom_analysis_description = '$custom_analysis_description', other_notes = '$other_notes', addon_flows =  '$addon_flows', analyst_hours = '$analyst_hours', image_specialist_hours = '$image_specialist_hours', medical_director_hours = '$medical_director_hours', pia_analysis_codes = '$pia_analysis_codes', status = '$status', customer_id = '$customer_id', analyses_ids = '$analyses_ids', date = '$date', existing_rate = '$existing_rate', expected_time = '$expected_time',any_mint='$ans_hr',	review_user_id='$txtReviewAnalyst' WHERE  clario_id = $clario_id";
        $sql_query_clario = "UPDATE `Clario` SET status = '$status',assignee = $analyst,review_user_id='$txtReviewAnalyst' WHERE  id = $clario_id";

        $result = $this->mysqli->query($sql_query);

        $res_Clario = $this->mysqli->query($sql_query_clario);

        $status = array();
        //$status['qry']	=	$sql_query;
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Worksheet updated successfully';

            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function update_wsheet_details($data, $ans_id, $rate, $qty, $worksheet_id) {

        extract($data);
        //print_r($addon_flows); die();
        $sql_query = "UPDATE `worksheet_details` SET ans_id = '$ans_id', date = '$date', rate = '$rate', qty = '$qty'  
					WHERE worksheet_id = $worksheet_id AND  ans_id = $ans_id";

        $result = $this->mysqli->query($sql_query);

        $status = array();

        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Worksheet updated successfully';

            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function update_wsheet_customer_id($data) {


        //print_r($addon_flows); die();
        $sql_query = "UPDATE `worksheets` SET customer_id = '$data'  WHERE analyst = $analyst AND  clario_id = $clario_id";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Worksheet updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function select_wsheet_date($data) {

        extract($data);
        $filter = '';
        if ($site != '' && $analyst != '') {
            $filter = " AND worksheets.analyst = '$analyst' AND Clario.site = '$site' ";
        } elseif ($site == '' && $analyst != '') {
            $filter = " AND worksheets.analyst = '$analyst' ";
        } elseif ($site == '' && $analyst == '') {
            $filter = " AND Clario.site = '$site' ";
        }

        $sql_query = "
		SELECT  worksheets.clario_id, 
			worksheets.date, 
			worksheets.customer_id, 
			worksheets.analyses_ids, 
			worksheets.addon_flows, 
			worksheets.status, 
			worksheets.analyses_performed, 
			worksheets.pia_analysis_codes, 
                        worksheets.expected_time,
			users.name, 
			Clario.hospital, 
			Clario.site  
		FROM worksheets 
		JOIN users ON worksheets.analyst=users.id 
		JOIN Clario ON worksheets.clario_id=Clario.id 
		WHERE ( `date` BETWEEN '$start_date' AND '$end_date') 
		AND worksheets.status = 'Completed' 
		$filter";

        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data_ret[] = $row;
            }
        } else
            $data_ret = false;
        return $data_ret;
    }

    public function select_wsheet_billing_date($data) {
//'2018-07-01' AND '2018-07-31'

        extract($data);
        $start_date = $data['start_date'] . '-01';
        $end_date = $data['start_date'] . '-31';
        $hospital = $data['site'];

        /* $sql_query = "SELECT worksheets.clario_id,worksheets.id, worksheets.customer_id, worksheets.date, worksheets.status, worksheets.analyses_performed, worksheets.pia_analysis_codes, users.name, Clario.hospital, Clario.site FROM worksheets JOIN users ON worksheets.analyst=users.id JOIN Clario ON worksheets.clario_id=Clario.id WHERE ( `date` BETWEEN '$start_date' AND '$end_date') AND worksheets.status = 'Completed' AND Clario.site = 'Imaging Hospital'"; */


        $sql_query = "SELECT DISTINCT worksheets.customer_id FROM worksheets JOIN users ON worksheets.analyst=users.id JOIN Clario ON worksheets.clario_id=Clario.id WHERE ( `date` BETWEEN '$start_date' AND '$end_date') AND worksheets.status = 'Completed' AND worksheets.customer_id = $hospital";

        print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data_ret[] = $row;
            }
        } else
            $data_ret = false;
        return $data_ret;
    }

    public function collect_wsheet($userid = "") {
        $data = array();
        $sql_query = "SELECT * FROM `worksheets`";

        if (!empty($userid)) {
            $sql_query .= " WHERE analyst = $userid";
        }
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            $data['total_analyst_hours'] = $data['image_specialist_hours'] = $data['medical_director_hours'] = 0;
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
                $data['total_analyst_hours'] += (int) $row['analyst_hours'];
                $data['image_specialist_hours'] += (int) $row['image_specialist_hours'];
                $data['medical_director_hours'] += (int) $row['medical_director_hours'];
            }
        } else
            $data = false;
        return $data;
    }

    public function wsheet_assign_list() {
        $data = array();

        $sql_query = "SELECT * 
		FROM Clario 
		JOIN worksheets ON worksheets.clario_id=Clario.id ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        } else
            $data = false;
        //print_r($data);
        return $data;
    }

    public function wsheet_assign_list_full() {
        $data = array();
        $data2 = array();

        $sql_query = "select * from Clario WHERE assignee != 0";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
                $data['results_old'][$row['id']] = $row;
            }

            foreach ($data['results'] as $key => $value) {
                $sql_query = "SELECT * 
					FROM Clario 
					JOIN worksheets ON worksheets.clario_id=Clario.id  WHERE Clario.id = " . $value['id'];

                $result2 = $this->mysqli->query($sql_query);
                if ($result2->num_rows == 1)
                    $data2['results'][] = $result2->fetch_assoc();
                else {

                    $data['results_old'][$value['id']]['status'] = "In progress";
                    $data2['results'][] = $data['results_old'][$value['id']];
                }
            }
        } else
            $data = false;

        //print_r($data);
        return $data2;
    }

    public function wsheet_assign_list_analyst_full() {
        $data = array();
        $data2 = array();

        $sql_query = "select * from Clario ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
                $data['results_old'][$row['id']] = $row;
            }

            foreach ($data['results'] as $key => $value) {
                $sql_query = "SELECT * 
					FROM Clario 
					JOIN worksheets ON worksheets.clario_id=Clario.id  WHERE Clario.id = " . $value['id'];

                $result2 = $this->mysqli->query($sql_query);
                if ($result2->num_rows == 1)
                    $data2['results'][] = $result2->fetch_assoc();
                else {

                    $data['results_old'][$value['id']]['status'] = "In progress";
                    $data2['results'][] = $data['results_old'][$value['id']];
                }
            }
        } else
            $data = false;

        //print_r($data);
        return $data2;
    }

    public function wsheet_assign_list_analyst_day($day) {

        $data = array();
        $data2 = array();

        $sql_query = "SELECT * FROM Clario WHERE TIMESTAMPDIFF(DAY,last_modified,NOW()) < $day ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
                $data['results_old'][$row['id']] = $row;
            }

            foreach ($data['results'] as $key => $value) {
                $sql_query = "SELECT * 
					FROM Clario 
					JOIN worksheets ON worksheets.clario_id=Clario.id  WHERE Clario.id = " . $value['id'];

                $result2 = $this->mysqli->query($sql_query);
                if ($result2->num_rows == 1)
                    $data2['results'][] = $result2->fetch_assoc();
                else {

                    $data['results_old'][$value['id']]['status'] = "In progress";
                    $data2['results'][] = $data['results_old'][$value['id']];
                }
            }
        } else
            $data = false;

        //print_r($data);
        return $data2;
    }

    public function wsheet_assign_list_analyst_assignee($day = '', $asignee = '', $status = '') {

        $data = array();
        $data2 = array();

        if (empty($day) && !empty($asignee)) {
            $sql_query = "SELECT * FROM Clario WHERE assignee = $asignee ";
        } elseif (!empty($day) && empty($asignee)) {
            $sql_query = "SELECT * FROM Clario WHERE TIMESTAMPDIFF(DAY,last_modified,NOW()) < $day";
        } elseif (!empty($asignee) && !empty($day)) {
            $sql_query = "SELECT * FROM Clario WHERE assignee = $asignee and TIMESTAMPDIFF(DAY,last_modified,NOW()) < $day";
        } else {
            $sql_query = "select * from Clario";
        }

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
                $data['results_old'][$row['id']] = $row;
            }

            foreach ($data['results'] as $key => $value) {
                $sql_query = "SELECT * 
					FROM Clario 
					JOIN worksheets ON worksheets.clario_id=Clario.id  WHERE Clario.id = " . $value['id'];

                $result2 = $this->mysqli->query($sql_query);
                if ($result2->num_rows == 1)
                    $data2['results'][] = $result2->fetch_assoc();
                else {

                    $data['results_old'][$value['id']]['status'] = "In progress";
                    $data2['results'][] = $data['results_old'][$value['id']];
                }
            }
        } else
            $data = false;

        //print_r($data);
        return $data2;
    }

    public function subscription_analyses($cid) {

        $data = array();

        $time_ids = $this->get_user_time_id($cid);

        $time_id = $time_ids['time_id'];

        $sql_query = "SELECT analyses.id, analyses.name, Analyses_rates.min_time, analyses.description, Analyses_rates.custom_description  FROM analyses JOIN analyses_rates ON analyses.id = Analyses_rates.analysis WHERE Analyses_rates.customer = $cid AND Analyses_rates.time_id = $time_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        } else
            $data = false;
        //print_r($data);
        return $data;
    }

    public function get_my_analyses($cid) {

        $data = array();

        $time_ids = $this->get_user_time_id($cid);

        $time_id = $time_ids['time_id'];

        $sql_query = "SELECT analyses.id, analyses.name FROM analyses JOIN analyses_rates ON analyses.id = Analyses_rates.analysis WHERE Analyses_rates.customer = $cid AND Analyses_rates.time_id = $time_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        } else
            $data = false;
        //print_r($data);
        return $data;
    }

    public function count_subscription($cid, $anid, $time_id) {

        $data = array();

        $sql_query = "SELECT* FROM subscriptions JOIN analyses_rates ON subscriptions.analysis = Analyses_rates.analysis AND subscriptions.customer = Analyses_rates.customer AND subscriptions.time_id = Analyses_rates.time_id WHERE Analyses_rates.customer = $cid AND Analyses_rates.analysis = $anid AND subscriptions.time_id = $time_id AND Analyses_rates.time_id = $time_id";
        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else
            $data = false;
        //print_r($data);
        return $data;
    }

    public function analyses_rate_details($cid, $anid, $time_id) {

        $data = array();

        // $cid = implode("','", $cid);
        // print_r($time_id);

        $sql_query = "SELECT* FROM analyses_rates WHERE customer = $cid  AND analysis = $anid AND time_id = $time_id";
       // print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else
            $data = false;
        //print_r($data);
        return $data;
    }

    public function only_count_subscription($cid, $anid, $time_id) {

        $data = array();
        $cid = implode("','", $cid);
        //$sql_query = "SELECT* FROM subscriptions  WHERE customer = $cid AND analysis = $anid AND time_id = $time_id";

        $sql_query = "SELECT* FROM subscriptions  WHERE customer IN ('" . $cid . "') AND analysis = $anid AND time_id = $time_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else
            $data = false;

        return $data;
    }

    public function count_previous_carry($cid, $anid, $date) {
        $data = array();

        $sql_query = "SELECT* FROM carry_forward WHERE customer = '$cid' AND analysis = '$anid' AND month = '$date'";
        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else
            $data = false;
        //print_r($data);
        return $data;
    }

    public function count_subscription_for_customer($cid) {
        $data = array();

        $sql_query = "SELECT* FROM subscriptions JOIN analyses_rates ON subscriptions.analysis = Analyses_rates.analysis AND subscriptions.customer = Analyses_rates.customer WHERE Analyses_rates.customer = $cid";
        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else
            $data = false;
        //print_r($data);
        return $data;
    }

    public function whseet_by_cid($id) {
        $data = array();
        $sql_query = "SELECT * 
		FROM Clario 
		JOIN worksheets ON worksheets.clario_id=Clario.id  WHERE worksheets.id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function count_table($table, $where = '') {

        $query = " SELECT * FROM `$table` $where";

        $result = $this->mysqli->query($query);
        return mysqli_num_rows($result);
    }

    public function count_tableNew($table, $where = '') {

        $query = "SELECT COUNT(`id`) AS cnt FROM `$table` $where";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return $row['cnt'];
    }

    public function count_table_by_date_new($table, $from, $to) {

        $query = "SELECT COUNT(`id`) AS cnt FROM `$table` WHERE ( `created` BETWEEN '$from' AND '$to') AND assignee <> 0";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return $row['cnt'];
    }

    public function count_table_by_date_clario_new($table, $from, $to) {

        $query = "SELECT COUNT(`id`) As cnt FROM `$table` WHERE ( `created` BETWEEN '$from' AND '$to')";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return $row['cnt'];
    }

    public function getsecondcheckcountdone($from, $to) {
        $query = "SELECT * FROM `Clario` WHERE ( `created` BETWEEN '$from' AND '$to') AND review_user_id!=''";
        $result = $this->mysqli->query($query);
        return mysqli_num_rows($result);
    }

    public function getsecondcheckcountnotdone($from, $to) {
        $query = "SELECT * FROM `Clario` WHERE ( `created` BETWEEN '$from' AND '$to') AND review_user_id=''";
        $result = $this->mysqli->query($query);
        return mysqli_num_rows($result);
    }

    public function anysecondcheckcountdone() {
        $query = "SELECT * FROM `Clario` WHERE  review_user_id!=''";
        $result = $this->mysqli->query($query);
        return mysqli_num_rows($result);
    }

    public function anysecondcheckcountnotdone() {
        $sql = "SELECT * FROM `Clario` WHERE  review_user_id=''";
        $result = $this->mysqli->query($sql);
        return mysqli_num_rows($result);
    }

    //public function count_table_by_date_customers($table, $from, $to, $group) {
    //    $query = "SELECT * FROM `$table` WHERE ( `created` BETWEEN '$from' AND '$to') AND `group_id`= $group ";
    //    $result = $this->mysqli->query($query);
    //    return mysqli_num_rows($result);
    //}

    public function count_analyst_hours() {

        $result = $this->mysqli->query('SELECT SUM(analyst_hours) AS value_sum FROM worksheets');

        $row = mysqli_fetch_assoc($result);

        return $row['value_sum'];
    }

    public function count_analyst_hours_by_date($from, $to) {

        $query = "SELECT SUM(analyst_hours) AS value_sum FROM worksheets WHERE (`date` BETWEEN '$from' AND '$to')";

        $result = $this->mysqli->query($query);

        $row = mysqli_fetch_assoc($result);

        return $row['value_sum'];
    }

    public function count_table_jobUnderReviewNew($table, $from, $to) {
        $query = "SELECT COUNT(`id`) AS cnt FROM `$table` WHERE ( `created` BETWEEN '$from' AND '$to') AND `status` = 'Under review'";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return $row['cnt'];
    }

    public function count_table_jobs_In_progressNew($table, $from, $to) {

        $query = "SELECT COUNT(`id`) AS cnt FROM `$table` WHERE ( `created` BETWEEN '$from' AND '$to') AND `status` = 'In progress'";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return $row['cnt'];
    }

    public function count_table_jobs_on_holdNew($table, $from, $to) {

        $query = "SELECT COUNT(`id`) AS cnt FROM `$table` WHERE ( `created` BETWEEN '$from' AND '$to') AND `status` = 'On hold'";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return $row['cnt'];
    }

    public function count_table_jobs_cancelledNew($table, $from, $to) {

        $query = "SELECT COUNT(`id`) AS cnt FROM `$table` WHERE ( `created` BETWEEN '$from' AND '$to') AND `status` = 'Cancelled'";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return $row['cnt'];
    }

    public function count_table_jobs_CompletedNew($table, $from, $to) {

        $query = "SELECT COUNT(`id`) AS cnt FROM `$table` WHERE ( `created` BETWEEN '$from' AND '$to') AND `status` = 'Completed'";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return $row['cnt'];
    }

    public function clario_exist($accession, $mrn) {

        $sql_query = "SELECT * FROM Clario WHERE accession='$accession' AND mrn='$mrn'";
        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows >= 1) {
            while ($obj = $result->fetch_object()) {

                $return = $obj->id;
            }
        } else
            $return = false;

        return $return;
    }

    public function carry_exist($analysis, $customer, $date) {

        $sql_query = "SELECT * FROM carry_forward WHERE analysis='$analysis' AND customer='$customer' AND month='$date'";
        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows >= 1) {
            while ($obj = $result->fetch_object()) {

                $return = $obj->id;
            }
        } else
            $return = false;

        return $return;
    }

    public function hospital_name_exist($name) {

        $sql_query = "SELECT * FROM hospital WHERE name='$name'";
        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows >= 1) {
            while ($obj = $result->fetch_object()) {

                $return = $obj->id;
            }
        } else
            $return = false;

        return $return;
    }

    public function clario_change_exist($id, $data) {

        $sql_query = "SELECT * FROM Clario WHERE id = $id";
        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows >= 1) {

            $db_data = (array) $result->fetch_object();
            unset($db_data['id']);
            unset($db_data['assignee']);
            $array_diff = array_diff($db_data, $data);

            if (!empty($array_diff))
                return $array_diff;
            else
                false;
        }
    }

    public function get_assigned_id($id) {

        $sql_query = "SELECT * FROM Clario WHERE id = $id";
        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows >= 1) {

            $db_data = (array) $result->fetch_object();
            return $db_data['assignee'];
        }
    }

    public function clario_import_update($id, $data) {


        $sql_query = "UPDATE `Clario` SET 
			accession = '$data[0]' ,
			mrn = '$data[1]',
			patient_name = '$data[2]',
			site_procedure = '$data[3]',
			last_modified = '$data[4]',
			exam_time = '$data[5]',
			status = '$data[6]',
			priority = '$data[7]',
			site = '$data[8]',
			hospital = '$data[9]'
		 WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function sum_hours($field) {
        $query = "SELECT SUM(" . $field . ") as " . $field . "total, DATE_FORMAT(`date`,'%m') monthnum  FROM  worksheets WHERE TIMESTAMPDIFF(MONTH,date,NOW()) < 6 GROUP BY DATE_FORMAT(`date`,'%m') ORDER BY DATE_FORMAT(`date`,'%m')";
        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function wsheet_static() {
        $query = "SELECT DATE_FORMAT(`date`,'%m') monthnum, SUM(`analyst_hours`) as analyst_hours_sum , SUM(`image_specialist_hours`) as image_specialist_hours_sum , SUM(`medical_director_hours`) as medical_director_hours_sum FROM worksheets 
WHERE TIMESTAMPDIFF(MONTH,date,NOW()) < 6 GROUP BY DATE_FORMAT(`date`,'%m') ORDER BY DATE_FORMAT(`date`,'%m')";
        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function wsheet_month_rate() {
        $query = "SELECT DATE_FORMAT(`date`,'%m') monthnum, `addon_flows`,`pia_analysis_codes`,`analyses_ids`,`customer_id` FROM worksheets  WHERE date < Now() and date > DATE_ADD(Now(), INTERVAL- 6 MONTH)";
        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function analyst_amount_per_month() {

        $data = array();
        $query = "SELECT `pia_analysis_codes`,Month(date) monthnum FROM `worksheets` WHERE TIMESTAMPDIFF(MONTH,date,NOW()) < 6 AND `status` ='Completed' ORDER BY Month(date)";
        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        $arr = array();
        $month = array();
        foreach ($data as $analystamount) {
            $monthnum = $analystamount['monthnum'];
            $pia_analysis_codes = $analystamount['pia_analysis_codes'];
            if (!in_array($monthnum, $arr)) {
                $arr[] = $monthnum;
            }
            $month[$monthnum][] = $pia_analysis_codes;
        }

        //print_r($month);die;

        return $month;
    }

    public function get_discount_range() {

        $data = array();
        $sql_query = "SELECT * FROM `discount_range`";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function get_last_discount_range() {

        $sql_query = "SELECT * FROM `discount_range` ORDER BY `id` DESC LIMIT 1";
        $result = $this->mysqli->query($sql_query);
        return $result->fetch_assoc()['maximum_value'];
    }

    public function discount_pricing_add($customer, $minimum_value, $maximum_value, $percentage) {

        $time_ids = $this->get_user_time_id($customer);

        $time_id = $time_ids['time_id'];

        $sql_query = "INSERT INTO `discount_range` (`customer`,`time_id`,`minimum_value`,`maximum_value`,`percentage`) VALUES ($customer,$time_id,$minimum_value,$maximum_value,$percentage)";

        $result = $this->mysqli->query($sql_query);

        $status = array();

        if ($result === TRUE) {

            $status['type'] = 'success';
            $status['msg'] = 'Discount Range created successfully';
            return $status;
        } else {

            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function discount_range_by_id($id) {

        $sql_query = "SELECT * FROM `discount_range` WHERE id = $id";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else
            $data = false;
        return $data;
    }

    public function discount_range_update($data = array(), $time_id) {
        extract($data);
        $sql_query = "UPDATE `discount_range` SET customer = '$customer',minimum_value = '$minimum_value' , maximum_value = '$maximum_value', percentage = '$percentage' WHERE time_id = '$time_id' AND customer = '$customer'";

        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Discount Range updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function get_discount_range_by_customer($id) {

        $data = array();

        $time_ids = $this->get_user_time_id($id);

        $time_id = $time_ids['time_id'];

        $sql_query = "SELECT * FROM `discount_range` WHERE customer = $id AND time_id = $time_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function get_discount($count, $cid, $time_id) {


        $data = array();
        $sql_query = "SELECT percentage FROM `discount_range` WHERE  minimum_value <= $count and maximum_value >= $count AND customer = $cid AND time_id = $time_id LIMIT 1";

        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {
            return false;
        }
        return $data;
    }

    public function get_discount_details($count, $cid, $time_id) {


        $data = array();
        $sql_query = "SELECT * FROM `discount_range` WHERE  minimum_value <= $count and maximum_value >= $count AND customer = $cid AND time_id = $time_id LIMIT 1";

        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {
            return false;
        }
        return $data;
    }

    public function get_user_id_by_accession($acc, $mrn) {

        //echo" $acc  $mrn";die;
        $sql_query = "SELECT cc_code FROM `Clario` where accession = '$acc'  and mrn ='$mrn' ";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function get_user_id_by_cc_code($cc) {

        //echo" $acc  $mrn";die;
        $sql_query = "SELECT id FROM users where user_meta like '%$cc%' ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function get_analyses_by_user($id) {

        $sql_query = "SELECT code FROM `Analyses_rates` where customer = $id";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }


        foreach ($data as $value) {
            $sql_query = "SELECT * FROM `analyses` where part_number = " . $value['code'];

            $result = $this->mysqli->query($sql_query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data['analyses'][] = $row;
                }
            }
        }

        //print_r($data['analyses']); die;

        return $data['analyses'];
    }

    public function ans_details() {
        
    }

    public function total_analyst_hours($table, $where = '') {

        //$data = array();
        //$sql_query = "SELECT * FROM $table $where";
        $sql_query = "SELECT SUM(analyst_hours) totalanalysthours FROM `$table` $where";

        //echo "hfds".$sql_query; die;
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row['totalanalysthours'];
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function cases_by_analyses_types() {
        $query = "SELECT analyses_ids FROM `worksheets` where WEEK(date) = WEEK( current_date ) - 1 AND YEAR( date) = YEAR( current_date ) AND `status` = 'Completed' ";

        $result = $this->mysqli->query($query);

        $string = '';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $string .= $row['analyses_ids'] . ',';
            }
        }

        $string = explode(',', $string);
        //print_r($string);
        $a = array();
        foreach ($string as $key => $value) {
            if ($value != '')
                if (isset($a[$value])) {
                    $a[$value]++;
                } else {
                    $a[$value] = 1;
                }
        }
        $an_ids = array_keys($a);
        $an_id_string = implode(',', $an_ids);

        $query = 'SELECT analyses.id, analyses.name ,analyses_category.category FROM analyses  JOIN analyses_category ON analyses.category = analyses_category.id WHERE analyses.id IN (' . $an_id_string . ')';

        $result = $this->mysqli->query($query);

        $categories = array();
        if (isset($result) && !empty($result))
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $categories[] = $row;
                }
            }

        $categories_array = array();
        foreach ($categories as $key => $value) {
            $c = $value['category'];
            $id = $value['id'];
            if (isset($categories_array[$c])) {
                $categories_array[$c] = $categories_array[$c] + $a[$id];
            } else {
                $categories_array[$c] = $a[$id];
            }
        }
        return $categories_array;
    }

    public function get_analysis_description($analyses_id, $customer_id) {
        $data = array();
        $sql_query = "SELECT analysis_description FROM `Analyses_rates` WHERE analysis = $analyses_id AND customer = $customer_id";

        //print_r($sql_query); die;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data['analysis_description'];
    }

    public function get_analysis_details($analyses_id, $customer_id) {

        $data = array();

        $sql_query = "SELECT * FROM `Analyses_rates` WHERE analysis = $analyses_id AND customer = $customer_id";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {

            return false;
        }

        return $data;
    }
    
    public function get_analysis_details_description($analyses_id, $customer_id) {

        $data = array();

        $sql_query = "SELECT analysis_description FROM `Analyses_rates` WHERE analysis = $analyses_id AND customer = $customer_id";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {

            return false;
        }

        return $data;
    }
    
    public function get_analysis_code($analyses_id, $customer_id) {
        $data = array();
        $sql_query = "SELECT code FROM `Analyses_rates` WHERE analysis = $analyses_id AND customer = $customer_id";     
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {

            return false;
        }
        return $data;
    }




    public function get_analysis_part($analyses_id) {
        $data = array();
        $sql_query = "SELECT part_number FROM `analyses` WHERE id  = $analyses_id";     
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {

            return false;
        }
        return $data;
    }

    public function get_analysis_analyst($analyses_id) {

        $data = array();

        $sql_query = "SELECT * FROM `Analyses_rates` WHERE analysis = $analyses_id";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {

            return false;
        }

        return $data;
    }

    public function get_analysis_name($aid) {
        $data = array();
        $sql_query = "SELECT id, name FROM `users` WHERE user_type_ids = 3 AND id='$aid'";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data['name'];
    }

    public function daily_work_count() {
        $query = "SELECT DATE_FORMAT(created,'%d') day, COUNT(created) count FROM `Clario`  WHERE MONTH(created) = MONTH(CURRENT_DATE()) AND YEAR(created) = YEAR(CURRENT_DATE()) AND TIMESTAMPDIFF(DAY,created,NOW()) < 7 GROUP by DATE_FORMAT(created,'%d')";

        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function daily_completed_work_count() {
        $query = "SELECT DATE_FORMAT(date,'%d') day, COUNT(date) count FROM worksheets WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE()) AND TIMESTAMPDIFF(DAY,date,NOW()) < 7 AND status = 'Completed' GROUP by DATE_FORMAT(date,'%d')";

        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function check_email($id) {
        $sql_query = "SELECT * FROM `users` WHERE email = '$id'";

        //print_r($sql_query);die;
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        if (!empty($data))
            return "exists";
        else
            return false;
    }

    public function get_all_analyst() {

        $sql_query = "SELECT id, name FROM `users` WHERE user_type_ids = 3 ORDER BY name ASC";

        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else
            $data = false;
        return $data;
    }

    public function get_all_worksheets() {

        $sql_query = "SELECT DISTINCT `customer` FROM `subscriptions`";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function find_analysis_details($analyses_id, $cust_id) {

        $sql_query = "SELECT * FROM `Analyses_rates` WHERE analysis = '$analyses_id' AND customer = '$cust_id'";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function re_assign($id) {

        $sql_query = "UPDATE `worksheets` SET status = 'Under review' WHERE clario_id = $id";

        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Case Reopened successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- REVIEW COMPLETED PROCESS FUNCTION -------------------
      @FUNCTION DATE              :  03-01-2019
      ------------------------------------------------------------------------------ */

    public function reviewcompleted($edit_id, $user_id, $analystHours, $comments, $rate) {
        $second_check_time = date("Y-m-d H:i:s");
        $sql_queryF = "UPDATE `worksheets` SET review_user_id = '$user_id',second_analyst_hours='$analystHours',second_comment='$comments',second_check_rate='$rate',`second_check_date`='$second_check_time'  WHERE clario_id = $edit_id";
        $result = $this->mysqli->query($sql_queryF);
        $sql_query = "UPDATE `Clario` SET review_user_id = '$user_id',second_analyst_hours='$analystHours',second_comment='$comments',second_check_rate='$rate' WHERE id = $edit_id";
        $result = $this->mysqli->query($sql_query);
        echo 1; //$sql_query.' '.$sql_queryF;
        //$status = array();
//        if ($result === TRUE) {
//            $status['type'] = 'success';
//            $status['msg'] = 'Successfully completed second check process';
//            return $status;
//        } else {
//            $status['type'] = 'danger';
//            $status['msg'] = "Error:" . $this->mysqli->error;
//            return $status;
//        }
    }

    public function getreviewcompleted($reviewid) {
        $sql = "SELECT `review_user_id`,`second_analyst_hours`,`second_comment`,`second_check_rate` FROM `worksheets`WHERE clario_id = '$reviewid'";
        $result = $this->mysqli->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data['status'] = $result->num_rows;
        }
        return $data;
    }

    public function get_invoice($id, $start_date) {

        $sql_query = "SELECT * FROM `invoice` WHERE customer_id = '$id' AND date = '$start_date' ORDER BY created_at DESC  ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function get_additional_invoice_details($invoice_id) {

        $sql_query = "SELECT * FROM `additiional_invoice_details` WHERE invoice_id = '$invoice_id'";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function get_all_customer() {
        $sql_query = "SELECT DISTINCT worksheets.customer_id as id, users.name FROM `worksheets` JOIN users on worksheets.customer_id = users.id";
        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else
            $data = false;
        return $data;
    }

    public function get_all_customer_new() {
        $sql_query = "SELECT DISTINCT worksheets.customer_id as id, users.name FROM `worksheets` JOIN users on worksheets.customer_id = users.id AND users.active = 1 ORDER BY users.name";
        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else
            $data = false;
        return $data;
    }

    public function get_name_by_id($id) {
        $sql_query = "SELECT name FROM users WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else
            $data = false;
        return $data['name'];
    }

    public function get_study_time_graph($customer, $month, $year) {
        $data = array();
        //$Analyst;
        //$this->Analyst		=	$this->model('analystratemodel');
        //$sql_query = "SELECT sum(analyst_hours) as analysthrs, sum(image_specialist_hours) as imagehrs, sum(medical_director_hours) as medicalhrs FROM `worksheets` WHERE customer_id = $customer AND month(date) = $month AND year(date) = $year";
        $workTimeTotal = 0;
        $excateTotalTime = 0;
        $sql_query = "SELECT any_mint,analyses_ids FROM `worksheets` WHERE customer_id = $customer AND  month(date) = $month AND year(date) = $year";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['any_mint'])) {
                    $workTime = explode(',', $row['any_mint']);
                    for ($i = 0; $i < count($workTime); $i++) {
                        $workTimeTotal = $workTimeTotal + $workTime[$i];
                    }
                }
                if (!empty($row['analyses_ids'])) {
                    $excateAnslyses = explode(',', $row['analyses_ids']);
                    for ($j = 0; $j < count($excateAnslyses); $j++) {
                        $sql = "SELECT minimum_time FROM analyses WHERE id='$excateAnslyses[$j]'";
                        $resultVal = $this->mysqli->query($sql);
                        if ($resultVal->num_rows > 0) {
                            while ($rowVal = $resultVal->fetch_assoc()) {
                                $dataVal = $rowVal['minimum_time'];
                            }
                        }
                        $excateTotalTime = $excateTotalTime + $dataVal;
                    }
                }
                //$data = $row;
                //$val	=	$sql;
            }
            $data['workingTime'] = $workTimeTotal;
            $data['excateTime'] = $excateTotalTime;
        } else {
            $data = false;
        }

        /* 	foreach ($data as $key => $value) {
          if(empty($key[$value])){
          $key[$value] = 0;
          }} */

        if (empty($data['workingTime'])) {
            $data['workingTime'] = 0;
        }
        if (empty($data['excateTime'])) {
            $data['excateTime'] = 0;
        }



        return $data;
    }

    public function analyses_rate_user_excel($user) {
        $data = array();

        $time_ids = $this->get_user_time_id($user);

        $time_id = $time_ids['time_id'];

        $sql_query = "SELECT analysis_description, rate, code FROM `Analyses_rates` WHERE `customer` = $user AND `time_id` = $time_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = ['analysis_description' => 0, 'rate' => 0, 'code' => 0];
        }
        return $data;
    }

    public function get_discount_range_by_customer_excel($id) {

        $data = array();

        $time_ids = $this->get_user_time_id($id);

        $time_id = $time_ids['time_id'];

        $sql_query = "SELECT minimum_value, maximum_value, percentage FROM `discount_range` WHERE customer = $id AND time_id = $time_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = ['minimum_value' => 0, 'maximum_value' => 0, 'percentage' => 0];
        }
        return $data;
    }

    public function subscriptions_user_excel($user) {
        $data = array();

        $time_ids = $this->get_user_time_id($user);
        $time_id = $time_ids['time_id'];

        $sql_query = "SELECT analyses.name, count FROM `subscriptions` JOIN analyses ON analyses.id= subscriptions.analysis WHERE `customer` = $user AND `time_id` = $time_id";

        //$sql_query = "SELECT * FROM subscriptions JOIN analyses_rates ON  subscriptions.customer = Analyses_rates.customer   WHERE subscriptions.customer = $user";
        //print_r($sql_query); die;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = ['analysis' => 0, 'count' => 0];
        }
        return $data;
    }

    public function get_subscription_by_customer_excel($user) {
        $data = array();

        $sql_query = "SELECT subscription_fees FROM `subscription_fees` WHERE `customer` = $user ORDER BY time_id ASC";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {
            $data = ['subscription_fees' => 0];
        }
        return $data;
    }

    public function get_maintenance_by_customer_excel($user) {
        $data = array();

        $sql_query = "SELECT maintenance_fee_amount FROM `maintenance` WHERE `customer` = $user ORDER BY time_id ASC";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {
            $data = ['maintenance_fee_amount' => 0];
        }
        return $data;
    }

    public function get_customer_excel_data() {
        $data = array();

        $sql_query = "SELECT name, email, created FROM `users` WHERE user_type_ids = 5";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function get_excel_analysis_data() {
        $data = array();
        $sql_query = "SELECT analyses.name, analyses_category.category, analyses.part_number, analyses.price, analyses.minimum_time  FROM analyses JOIN analyses_category ON analyses.category = analyses_category.id";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function insert_study($data = array()) {
        extract($data);

        $accession = $this->mysqli->real_escape_string($data["accession"]);
        $mrn = $this->mysqli->real_escape_string($data["mrn"]);
        $patient_name = $this->mysqli->real_escape_string($data["patientname"]);
        $exam_time = $this->mysqli->real_escape_string($data["examtime"]);
        $customer = $this->mysqli->real_escape_string($data["customer"]);
        $description = $this->mysqli->real_escape_string($data["description"]);
        $site = $this->mysqli->real_escape_string($data["site"]);

        $query = "SELECT  * from `Clario` where  accession ='$accession' and mrn='$mrn'";
        $result1 = $this->mysqli->query($query);
        if ($result1->num_rows == 0) {

            $sql_query = "INSERT INTO `Clario` (`id`, `accession`, `mrn`, `patient_name`, `site_procedure`, `last_modified`, `exam_time`, `status`, `priority`, `site`, `hospital`, `assignee`, `customer`, `webhook_customer`, `webhook_description`, `created`) VALUES (NULL, '$accession', '$mrn', '$patient_name', '', CURRENT_TIMESTAMP, '$exam_time', '', '', '$site', '', 0, 0, '$customer', '$description', CURRENT_TIMESTAMP)";

            $result = $this->mysqli->query($sql_query);

            $study_id = $this->mysqli->insert_id;

            $status = array();
            if ($result === TRUE) {
                $status['type'] = 'success';
                $status['msg'] = 'Study Added successfully';
                return $status;
            } else {
                $status['type'] = 'danger';
                $status['msg'] = "Error:" . $this->mysqli->error;
                return $status;
            }
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Duplicate Entry - Another Study with the same MRN and Accession is already present in the table" . $this->mysqli->error;
            return $status;
        }
    }

    public function get_latest_analysis_rates($id, $time_id, $analysis_id) {


        $sql_query = "SELECT * FROM `Analyses_rates` where customer = $id and time_id = $time_id and analysis = $analysis_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }

        //print_r($data['rate']); die("zhsdfgsdfjsgdfsyhdgfyhgsdfshdgf xdfgvdsgsdgggggggggggggggsdfsdf");

        return $data['rate'];
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- UPDATE NOT ASSIGN -----------------------------------
      @FUNCTION DATE              :  01-01-2019
      ------------------------------------------------------------------------------ */

    public function statusupdate($clario_id) {
        $sql_query = "UPDATE Clario SET review_user_id = 0, assignee = 0 , customer = 0, tat= 0, status = ''  WHERE id = $clario_id";
        $this->mysqli->query($sql_query);
    }

    /*     * ***************************** RC ******************************************* */
    /* ----------------------- UPDATE NOT ASSIGN -----------------------------------
      @FUNCTION DATE              :  01-01-2019
      ------------------------------------------------------------------------------ */

    public function getreviewdata($clario_id) {
        $sql = "SELECT Clario.review_user_id,Clario.second_analyst_hours,Clario.second_comment,Clario.second_check_rate,users.name  FROM Clario INNER JOIN users ON users.id=Clario.review_user_id WHERE Clario.id='$clario_id'";
        $result = $this->mysqli->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
        //return $sql;
    }

    /* ----------------------- UPLOAD DOCS CUSTOMER -----------------------------------
      @FUNCTION DATE              :  24-08-2019
      ------------------------------------------------------------------------------ */

    public function uploadCustomerDocuments($data = array()) {
        if (!empty($data)) {
            $customer_id = $this->mysqli->real_escape_string($data['customer_id']);
            $doc_title = $this->mysqli->real_escape_string($data['doc_title']);
            $doc_desc = $this->mysqli->real_escape_string($data['doc_desc']);
            $doc_path = $this->mysqli->real_escape_string($data['doc_path']);
            $add_user_by = $this->mysqli->real_escape_string($data['add_user_by']);
            $user_add_on = $this->mysqli->real_escape_string($data['user_add_on']);
            $updated_user = $this->mysqli->real_escape_string($data['updated_user']);
            $user_upd_on = $this->mysqli->real_escape_string($data['user_upd_on']);
            $status = $this->mysqli->real_escape_string($data['status']);

            $sql_query = "INSERT INTO `adm_admin_customer_docs`(`ACD_Customer_ID_FK`, `ACD_Docs_Path`, `ACD_Docs_Title`, `ACD_Docs_Desc`, `ACD_Add_User_By`, `ACD_User_Add_On`, `ACD_Updated_User_By`, `ACD_User_Updated_On`, `ACD_Status`) VALUES ('$customer_id','$doc_path','$doc_title','$doc_desc','$add_user_by','$user_add_on','$updated_user', '$user_upd_on', '$status')";

            $result = $this->mysqli->query($sql_query);
            if (!$result) {
                return false;
            }

            return $result;
        }
    }

    /* ----------------------- DOCS CUSTOMER LIST BY ID-----------------------------------
      @FUNCTION DATE              :  26-08-2019
      @RETURN                     :  ARRAY
      ------------------------------------------------------------------------------ */

    public function getDocumentsByCustomer($customer_id) {
        $data = array();
        if (!empty($customer_id)) {
            $sql_query = "SELECT ACD_ID_PK, ACD_Customer_ID_FK, ACD_Docs_Path, ACD_Docs_Title, ACD_Docs_Desc, ACD_Add_User_By, ACD_User_Add_On, ACD_Updated_User_By, ACD_User_Updated_On, ACD_Status FROM adm_admin_customer_docs WHERE ACD_Customer_ID_FK = '$customer_id' ORDER BY ACD_ID_PK DESC";
            $result = $this->mysqli->query($sql_query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
        }
        return $data;
    }

    /* ----------------------- BILLS LIST BY ID-----------------------------------
      @FUNCTION DATE              :  26-08-2019
      @RETURN                     :  ARRAY
      ------------------------------------------------------------------------------ */

    public function getBillsByCustomer($customer_id) {
        $data = array();
        if (!empty($customer_id)) {
            $sql_query = "SELECT ACB_ID_PK, ACB_Customer_ID_FK, ACB_Bills_Title, ACB_Bills_Desc, ACB_Bills_Invoice_No, ACB_Bills_Due, ACB_Bills_Month, ACB_Bills_Year, ACB_Bills_Total, ACB_Bills_Discount, ACB_Bills_Invoice_Amount, ACB_Bills_Path, ACB_Add_User_By, ACB_User_Add_On, ACB_Updated_User_By, ACB_User_Updated_On, ACB_Status FROM adm_admin_customer_bills WHERE ACB_Customer_ID_FK = '$customer_id' ORDER BY ACB_ID_PK DESC";
            $result = $this->mysqli->query($sql_query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
        }
        return $data;
    }

    /* ----------------------- UPLOAD BILLS CUSTOMER -----------------------------------
      @FUNCTION DATE              :  26-08-2019
      ------------------------------------------------------------------------------ */

    public function uploadCustomerBills($data = array()) {
        if (!empty($data)) {
            $customer_id = $this->mysqli->real_escape_string($data['customer_id']);
            $bill_title = $this->mysqli->real_escape_string($data['bill_title']);
            $bill_desc = $this->mysqli->real_escape_string($data['bill_desc']);
            $bill_invoice = $this->mysqli->real_escape_string($data['bill_invoice']);
            $bill_due = $this->mysqli->real_escape_string($data['bill_due']);
            $bill_date = $this->mysqli->real_escape_string($data['bill_date']);
            $bill_date_year = $this->mysqli->real_escape_string($data['bill_date_year']);
            $bill_total = $this->mysqli->real_escape_string($data['bill_total']);
            $bill_discount = $this->mysqli->real_escape_string($data['bill_discount']);
            $bill_invoice_amt = $this->mysqli->real_escape_string($data['bill_invoice_amt']);

            $bill_path = $this->mysqli->real_escape_string($data['bill_path']);
            $add_user_by = $this->mysqli->real_escape_string($data['add_user_by']);
            $user_add_on = $this->mysqli->real_escape_string($data['user_add_on']);
            $updated_user = $this->mysqli->real_escape_string($data['updated_user']);
            $user_upd_on = $this->mysqli->real_escape_string($data['user_upd_on']);
            $status = $this->mysqli->real_escape_string($data['status']);

            $sql_query = "INSERT INTO `adm_admin_customer_bills`(`ACB_Customer_ID_FK`, `ACB_Bills_Title`, `ACB_Bills_Desc`, `ACB_Bills_Invoice_No`, `ACB_Bills_Due`, `ACB_Bills_Month`, `ACB_Bills_Year`, `ACB_Bills_Total`, `ACB_Bills_Discount`, `ACB_Bills_Invoice_Amount`, `ACB_Bills_Path`, `ACB_Add_User_By`, `ACB_User_Add_On`, `ACB_Updated_User_By`, `ACB_User_Updated_On`, `ACB_Status`) VALUES ('$customer_id','$bill_title','$bill_desc','$bill_invoice', '$bill_due', '$bill_date', '$bill_date_year', '$bill_total', '$bill_discount', '$bill_invoice_amt', '$bill_path', '$add_user_by','$user_add_on','$updated_user', '$user_upd_on', '$status')";

            $result = $this->mysqli->query($sql_query);
            if (!$result) {
                // break;
                exit;
            }

            return $result;
        }
    }

    /* ----------------------- Update User Status -----------------------------------
      @FUNCTION DATE              :  25-03-2021
      ------------------------------------------------------------------------------ */

    public function userStatusUpdate($id, $status_new) {
        $sql_query = "UPDATE `users` SET `active`='$status_new' WHERE `id` = '$id'";
        $result = $this->mysqli->query($sql_query);
        $status = array();

        if ($result === TRUE && $status_new == '0') {
            $status['type'] = 'danger';
            $status['msg'] = 'Inactivated successfully';
            return $status;
        }
        if ($result === TRUE && $status_new == '1') {
            $status['type'] = 'success';
            $status['msg'] = 'Activated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    /* ----------------------- Update Customer Status -----------------------------------
      @FUNCTION DATE              :  25-03-2021
      ------------------------------------------------------------------------------ */

    public function customerStatusUpdate($id, $status_new) {
        $sql_query = "UPDATE `users` SET `active`='$status_new' WHERE `id` = '$id' AND group_id = 5";
        $result = $this->mysqli->query($sql_query);
        $status = array();

        if ($result === TRUE && $status_new == '0') {
            $status['type'] = 'danger';
            $status['msg'] = 'Inactivated successfully';
            return $status;
        }
        if ($result === TRUE && $status_new == '1') {
            $status['type'] = 'success';
            $status['msg'] = 'Activated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    /* ----------------------- Update Customer Name -----------------------------------
      @FUNCTION DATE              :  21-02-2022
      ------------------------------------------------------------------------------ */

    public function customerNameUpdate($id, $name) {
        $sql_query = "UPDATE `users` SET `name`='$name' WHERE `id` = '$id' AND group_id = 5";
        $result = $this->mysqli->query($sql_query);
        //$status = array();


        if ($result === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function get_custmernames($ids) {

        foreach ($ids as $kk) {

            $sql_query = "SELECT name FROM users where id = $kk";

            $result = $this->mysqli->query($sql_query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row)
                        $data[$kk] = $row;
                }
            } else {
                $data = false;
            }
        }
        return $data;
    }

}
