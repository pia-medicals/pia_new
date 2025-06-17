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
        $sql_query = "SELECT MAX(maximum_volume) as max_value 
                      FROM monthly_volume_discount 
                      WHERE client_account_ids = $id 
                      AND is_active = '1'";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function delete($table, $id, $primary_id) {
        $id = $this->mysqli->real_escape_string($id);
        //$sql_query = "DELETE FROM $table WHERE id=$id";
        $sql_query = "UPDATE $table  SET is_deleted = '1' WHERE $primary_id=$id";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Deleted Successfully';
            return $status;
        } else {
            $status['type'] = 'error';
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
        //$name = str_replace("'", "''", $name);
        $name = $this->mysqli->real_escape_string($name);
        $email = $this->mysqli->real_escape_string($email);

        $sql_query = "INSERT INTO users (created_at, user_type_ids, user_name, email, password, is_active, created_by) VALUES ('$created',  '$group_id', '$name', '$email', '$password', '1', '$created_by')";
        $result = $this->mysqli->query($sql_query);
        $customer_id = $this->mysqli->insert_id;

        if ($group_id == 5) {
            $client = "INSERT INTO clients (client_name, created_by) VALUES ('$name', '$created_by')";
            $result1 = $this->mysqli->query($client);
            $client_id = $this->mysqli->insert_id;

            $sql = "INSERT INTO client_details (client_ids, user_ids, created_by) VALUES ('$client_id', '$customer_id', '$created_by')";
            $result2 = $this->mysqli->query($sql);
            $client_account_id = $this->mysqli->insert_id;
        }

        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            if ($group_id == 5) {
                $status['msg'] = 'New Customer Created Successfully.';
            } else {
                $status['msg'] = 'New User Created Successfully.';
            }
            $status['customer_id'] = !empty($customer_id) ? $customer_id : '';
            $status['client_id'] = !empty($client_id) ? $client_id : '';
            $status['client_account_id'] = !empty($client_account_id) ? $client_account_id : '';
            return $status;
        } else {
            $status['type'] = 'error';
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

        $sql_query = "INSERT INTO Timeline (customer_id, created_at, valid_from, valid_to)  VALUES ('$customer_id', '$created_at', '$valid_from', '$valid_to')";
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
        $sql_query = "SELECT data FROM billing_versions WHERE customer = $customer and date = '$date'";
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



        $sql_query = "INSERT INTO billing_versions (id, customer, data, updated, date) VALUES (NULL, '$customer', '$data', CURRENT_TIMESTAMP, '$date')";
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
        $name = $this->mysqli->real_escape_string($name);
        $email = $this->mysqli->real_escape_string($email);
        $id = $this->mysqli->real_escape_string($id);
        $sql_query = "UPDATE users SET 
			user_type_ids = '$group_id',
			user_name = '$name',
			email = '$email',
			password = '$password',
			is_active = '$is_active'
		 WHERE user_id = '$id'";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            if ($group_id == 5) {
                $client_id = $this->getClientId($id);
                if (!empty($client_id)) {
                    $upd1 = $this->update_client_name($name, $is_active, $client_id);
                    $upd2 = $this->updateStatus('client_details', $client_id, 'client_ids', $is_active);
                }
            }
            $status['type'] = 'success';
            $status['msg'] = 'User Details Updated Successfully.';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function user_tat_update($data = array()) {
        extract($data);

        $sql_query = "UPDATE users SET 
            tat = '$tat'
         WHERE user_id = $id";

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

        $sql_query = "UPDATE users SET 
			profile_picture = '$profile_picture'
		 WHERE user_id = $id";

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

        $sql_query = "UPDATE users SET 
				user_meta = '$updated' 
			WHERE user_id = $id";

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

    public function clientdetails_update($id, $site_code, $client_site_name, $address_line1, $address_line2, $city, $state, $zipcode, $contract_tat) {
        $sql_query = "UPDATE client_details SET 
                site_code = '$site_code',client_site_name = '$client_site_name',address_line1 = '$address_line1',address_line2 = '$address_line2',city = '$city' ,state = '$state',zipcode = '$zipcode',contract_tat='$contract_tat' WHERE user_ids = $id";

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

    public function maintenance_fees_add($updated = array()) {

        extract($updated);

        $sql_query = "INSERT INTO maintenance_fees (client_account_ids,maintenance_fee_type,maintenance_fee_amount,is_active) VALUES ('$client_account_id','$maintenance_fee_type','$maintenance_fee_amount','1')";

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

    public function subscription_fees_add($client_account_ids, $amount) {


        $sql_query = "INSERT INTO subscription (client_account_ids,is_active,subscription_price) 
  												VALUES ('$client_account_id','1','$amount')";

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

    public function insert_subscription_fees($client_account_ids, $amount) {

        $sql_query = "INSERT INTO subscription (client_account_ids,is_active,subscription_price) 
                                                VALUES ('$client_account_id',1,'$amount')";
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

    public function get_subscription_by_time_id($client_account_id, $time_id) {
        $data = [];
        $sql_query = "SELECT * FROM subscription WHERE  client_account_ids = '$client_account_id' AND is_active = 1";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function salesforce_code_add($data = array()) {
        extract($data);
        $sql_query = "INSERT INTO Salesforce (code, description) VALUES ('$code', '$description')";
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
        //$name = str_replace("'", "''", $name);
        //$description = str_replace("'", "''", $description);
        $part_number = $this->mysqli->real_escape_string($part_number);
        $name = $this->mysqli->real_escape_string($name);
        $category = $this->mysqli->real_escape_string($category);
        $price = $this->mysqli->real_escape_string($price);
        $minimum_time = $this->mysqli->real_escape_string($minimum_time);
        $description = $this->mysqli->real_escape_string($description);
        $sql_query = "INSERT INTO analyses (analysis_number,analysis_name,analysis_invoicing_description,category_ids,analysis_price,time_to_analyze,is_active,created_by) VALUES ('$part_number','$name','$description','$category','$price','$minimum_time','$is_active','$created_by')";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Analysis created successfully';
            return $status;
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function miscellaneous_billing_add_during_cancellation($data = array()) {

        extract($data);
        $date = date("Y-m-d");
        $sql_query = "INSERT INTO miscellaneous_billing (count,name,analysis_invoicing_description,created_at,analysis_client_price,client_account_ids) VALUES ($count_an,'$name_an','$description_an','$date',$rate_an, $customer)";

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
        $sql_query = "INSERT INTO miscellaneous_billing (count,name,analysis_invoicing_description,created_at,analysis_client_price,client_account_ids) VALUES ($count_an,'$name_an','$description_an','$date',$rate_an, $customer)";

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
        $sql_query = "UPDATE miscellaneous_billing SET count = '$count_an' , name = '$name_an' , analysis_invoicing_description = '$description_an' , client_account_ids = '$customer' , analysis_client_price = '$rate_an' , created_at = '$date' WHERE miscellaneous_billing_id  = $id";

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
        $data = [];
        $start_date = $date . '-01 00:00:00';
        $end_date = $date . '-31 23:59:59';
        //$sql_query = "SELECT * FROM worksheets WHERE ( date BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
        $sql_query = "SELECT * FROM miscellaneous_billing  WHERE ( created_at BETWEEN '$start_date' AND '$end_date') AND client_account_ids =" . $id;

        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function miscellaneous_billing_by_id($id) {

        $sql_query = "SELECT * FROM miscellaneous_billing  WHERE miscellaneous_billing_id  =" . $id;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function get_usermeta_by_id($id) {
        $data = array();

        $sql_query = "SELECT user_meta FROM users WHERE user_id = $id";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return json_decode($data['user_meta'], true);
    }

    // public function get_user_time_id($id) {
    //     $data = array();
    //     $sql_query = "SELECT time_id FROM timeline WHERE customer_id = $id";
    //     $result = $this->mysqli->query($sql_query);
    //     if ($result->num_rows > 0) {
    //         while ($row = $result->fetch_assoc()) {
    //             $data = $row;
    //         }
    //     }
    //     return $data;
    // }

    public function get_client_by_id($kk) {

        $data = array();

        $sql_query = "SELECT client_code,name FROM  users WHERE user_id = $kk";
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
        $sql_query = "SELECT time_id FROM timeline WHERE customer_id = $id ORDER BY time_id DESC LIMIT 1";

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

        $sql_query = "UPDATE Timeline SET  valid_to = '$valid_to' WHERE time_id = $time_id";
        $result = $this->mysqli->query($sql_query);
    }

    public function fetch_all_analysis($client_account_id) {
        $data = [];
        $sql_query = "SELECT * FROM analyses_client_price_details WHERE client_account_ids = $client_account_id AND is_active = 1";

        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function fetch_all_subscrptions($client_account_id) {
        $data = [];
        $sql_query = "SELECT * FROM subscription WHERE client_account_ids = $client_account_id and is_active=1";

        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function fetch_all_discount_range($client_account_id) {

        $data = array();
        $sql_query = "SELECT * FROM monthly_volume_discount WHERE client_account_ids = $client_account_id and is_active=1";

        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function fetch_all_maintenance($client_account_id) {
        $data = array();
        $sql_query = "SELECT * FROM maintenance_fees WHERE client_account_ids = $client_account_id ORDER BY maintenance_fees_id ASC";

        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }

        return $data;
    }

    public function fetch_all_subscrptionfees($client_account_id) {
        $data = [];
        $sql_query = "SELECT * FROM subscription WHERE client_account_ids = $client_account_id and is_active=1";

        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function analyses_name_by_id($id) {
        $data = [];
        $sql_query = "SELECT analysis_name FROM analyses WHERE analysis_id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return !empty($data['analysis_name']) ? $data['analysis_name'] : '';
    }

    public function analyses_name_by_clientprice_id($id) {
        $data = [];
        $sql_query = "SELECT analysis_name FROM analyses_client_price_details WHERE analysis_client_price_id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return !empty($data['analysis_name']) ? $data['analysis_name'] : '';
    }

    public function analyses_client_priceid_by_id($id, $client_account_id) {
        $sql_query = "SELECT analysis_client_price_id FROM analyses_client_price_details WHERE analysis_id = $id and client_account_ids=$client_account_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data['analysis_client_price_id'];
    }

    // public function analyses_rate_add($data = array()){
    // extract($data);	
    // $usermeta = $this->get_usermeta_by_id($data['customer']);
    // $time_ids = $this->get_user_timeline($customer);
    // $time_id = $time_ids['time_id'];
    // $cc_code = $usermeta['customer_code'];
    // $analysis_name = $this->analyses_name_by_id($data['analysis']);
    // $analysis_description = $cc_code.$data['code']."-".$analysis_name;	
    // $analysis_description = str_replace("'", "''", $analysis_description);
    // $sql_query = "INSERT INTO analyses_client_price_details (analysis,time_id,customer,rate,code,analysis_description) VALUES ('$analysis','$time_id','$customer',$rate,'$code','$analysis_description')";
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

    public function delete_analyses_rate($client_account_id) {



        $sql_query = "DELETE  FROM analyses_client_price_details WHERE client_account_ids = $client_account_id AND is_active = 1";

        $this->mysqli->query($sql_query);
    }

    public function delete_user_subscription($client_account_id) {



        //$sql_query = "DELETE  FROM subscription_contents WHERE client_account_ids = $client_account_id";

        $sql_query = "DELETE subscription_contents
FROM subscription_contents
JOIN subscription 
  ON subscription_contents.subscription_ids = subscription.subscription_id
WHERE subscription.client_account_ids = $client_account_id";

        $this->mysqli->query($sql_query);
    }

    public function delete_user_discounts($client_account_id) {

        $sql_query = "DELETE  FROM monthly_volume_discount WHERE client_account_ids = $client_account_id";

        $this->mysqli->query($sql_query);
    }

    public function analyses_category_add($data = array()) {
        extract($data);
        $category = $this->mysqli->real_escape_string($category);
        $sql_query = "INSERT INTO analyses_category (category_name, is_active, created_by) 
					  VALUES ('$category', '$is_active', '$created_by')";
        $result = $this->mysqli->query($sql_query);
        $status = array();

        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Analysis category created successfully';
            return $status;
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function carry_add($ans_id, $customer, $count, $date) {


        $sql_query = "INSERT INTO carry_forward (analysis,customer,count,month) 
				  VALUES ('$ans_id','$customer','$count','$date')";

        $result = $this->mysqli->query($sql_query);
    }

    public function carry_add_2($analysis, $customer, $count, $datem, $prev_carry) {



        $new_count = $count + $prev_carry;

        $id = $this->carry_exist($analysis, $customer, $datem);
        if (!$id)
            $sql_query = "INSERT INTO carry_forward (analysis,customer,count,month) VALUES ('$analysis','$customer','$new_count','$datem')";
        else
            $sql_query = "UPDATE carry_forward SET count = '$new_count' WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
    }

    public function carry_add_backup($analysis, $customer, $count, $datem) {



        $sql_query = "INSERT INTO carry_backup (analysis,customer,count,month) VALUES ('$analysis','$customer','$count','$datem')";
        $result = $this->mysqli->query($sql_query);
    }

    public function trunct_table() {

        $sql_delete = "TRUNCATE TABLE carry_backup";

        $this->mysqli->query($sql_delete);
    }

    public function carry_select($date, $time_id) {



        $data = array();

        $sql_query = "SELECT * FROM carry_forward A 
				  RIGHT JOIN subscriptions B 
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

        $sql_query = "SELECT * FROM carry_backup A 
				  RIGHT JOIN subscriptions B 
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

        $sql_query = "INSERT INTO invoice (customer_id,created_at,date,total_before_dicount,discount_percnt,discount,total_after_dicount,subs_amount,maint_fees,maint_fees_type,grand_total) VALUES ('$customer_id','$created_at','$date','$total_before_dicount','$discount_percnt','$discount','$total_after_dicount','$subs_amount','$maint_fees','$maint_fees_type','$grand_total')";

        $result = $this->mysqli->query($sql_query);

        $invoice_id = $this->mysqli->insert_id;

        if ($ans_id == 0) {
            
        } else {
            foreach ($ans_id as $key => $ans_ids) {

                $sql_query = "INSERT INTO invoice_details (invoice_id,ans_id,ans_name,total_subscribed,used,balance_carry,extra_used,rate,total) VALUES ('$invoice_id','$ans_ids','$ans_name[$key]','$total_subscribed[$key]','$used[$key]','$balance_carry[$key]','$extra_used[$key]','$rate[$key]','$total[$key]')";

                $result = $this->mysqli->query($sql_query);
            }
        }

        if ($ad_ans_id == 0) {
            
        } else {

            foreach ($ad_ans_id as $key => $ad_ans_ids) {

                $sql_query = "INSERT INTO additiional_invoice_details (invoice_id,ans_id,ans_name,rate,qty,total) VALUES ('$invoice_id','$ad_ans_ids','$ad_ans_name[$key]','$ad_ans_rate[$key]','$ad_ans_qty[$key]','$ad_ans_total[$key]')";

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

        $part_number = $this->mysqli->real_escape_string($part_number);
        $name = $this->mysqli->real_escape_string($name);
        $category = $this->mysqli->real_escape_string($category);
        $price = $this->mysqli->real_escape_string($price);
        $minimum_time = $this->mysqli->real_escape_string($minimum_time);
        $description = $this->mysqli->real_escape_string($description);
        $id = $this->mysqli->real_escape_string($id);

        $sql_query = "UPDATE analyses SET category_ids = '$category', analysis_invoicing_description = '$description', analysis_name = '$name', analysis_number = '$part_number', analysis_price = '$price', time_to_analyze = '$minimum_time', is_active = '$active', updated_at = '$updated_at', updated_by = '$updated_by' WHERE analysis_id = '$id'";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Analysis details updated successfully';
            return $status;
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function analyses_category_update($data = array()) {
        extract($data);
        $category = $this->mysqli->real_escape_string($category);
        $id = $this->mysqli->real_escape_string($id);
        $sql_query = "UPDATE analyses_category SET category_name='$category',is_active = '$active' WHERE category_id = '$id'";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Analysis category updated successfully';
            return $status;
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function weekly_case_count() {
        $data = [];
        $query = "SELECT DATE_FORMAT(created,'%d') day, COUNT(created) count FROM studies WHERE MONTH(created) = MONTH(CURRENT_DATE()) AND YEAR(created) = YEAR(CURRENT_DATE()) GROUP by DATE_FORMAT(created,'%d')";

        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function analyses_rate_update_old($data = array(), $time_id = '') {

        extract($data);
        $usermeta = $this->get_usermeta_by_id($data['customer']);
        $cc_code = $usermeta['customer_code'];
        $analysis_name = $this->analyses_name_by_id($data['analysis']);

        $analysis_description = $cc_code . $data['code'] . "-" . $analysis_name;

        $analysis_description = str_replace("'", "''", $analysis_description);

        // $sql_query = "UPDATE analyses_client_price_details SET rate = '$rate' , analysis = '$analysis', code = '$code' , customer = $customer , analysis_description = '$analysis_description'  WHERE time_id = $time_id AND customer = $customer AND analysis = $analysis";

        $sql_query = "UPDATE analyses_client_price_details SET rate = '$rate' , analysis = '$analysis', code = '$code' , customer = $customer , analysis_description = '$analysis_description', min_time='$min_time', custom_description='$custom_description'  WHERE time_id = $time_id AND customer = $customer AND analysis = $analysis";

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

    public function update_maintenance_fees_add($data = array(), $time_id = '') {
        extract($data);
        $sql_query = "UPDATE maintenance_fees 
						SET maintenance_fee_type = '$maintenance_fee_type' ,
							 maintenance_fee_amount = '$maintenance_fee_amount',
							  client_account_ids = $customer 
							  WHERE client_account_ids = $customer";

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
        $data = [];
        $sql_query = "SELECT * FROM maintenance_fees WHERE client_account_ids = $customer_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function is_exist_analysis_of_user($customer_id) {

        $sql_query = "SELECT * FROM analyses_client_price_details WHERE customer = $customer_id";

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

        $sql_query = "SELECT * FROM discount_range WHERE customer = $customer_id";

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

        $sql_query = "SELECT * FROM subscriptions WHERE customer = $customer_id";

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

    public function is_exist_subscription_fees($client_account_id) {



        $sql_query = "SELECT * FROM subscription WHERE client_account_ids = $client_account_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {

            return true;
        } else {

            return false;
        }
    }

    public function salesforce_code_update($data = array()) {
        extract($data);
        $sql_query = "UPDATE Salesforce SET code = '$code' , description = '$description' WHERE id = $id";
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

    public function salesforce_code($page_now = "", $page_url = "") {
        $data = array();
        $sql_query = "SELECT * FROM Salesforce";

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

    public function analyses($key = "", $page_now = "", $page_url = "") {
        $data = array();
        //$sql_query = "SELECT * FROM analyses";	

        $sql_query = "SELECT analyses.analysis_id, analyses.analysis_name,analyses.analysis_invoicing_description, analyses_category.category_name ,analyses.analysis_price, analyses.time_to_analyze FROM analyses JOIN analyses_category ON analyses.category_ids = analyses_category.category_id";

        //

        if ($key != '') {
            $sql_query .= " WHERE analyses.analysis_name LIKE '%$key%' ";
        }

        $data['pagination'] = $this->pagination($page_now, $sql_query, $page_url, $key);
        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " ORDER BY analyses.analysis_name ASC LIMIT $start_from, 10";
        } else {
            $sql_query .= " ORDER BY analyses.analysis_name ASC LIMIT 0, 10";
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

    public function miscellaneous_billing($key = "", $page_now = "", $page_url = "") {
        $data = array();
        //$sql_query = "SELECT * FROM analyses";	

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

    public function analyses_rate($page_now = "", $page_url = "") {
        $data = array();
        $sql_query = "SELECT * FROM analyses_client_price_details";

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

    public function analyses_category($page_now = "", $page_url = "") {
        $data = array();
        $sql_query = "SELECT * FROM analyses_category";

        $data['pagination'] = $this->pagination($page_now, $sql_query, $page_url);
        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " ORDER BY category_id DESC LIMIT $start_from, 10";
        } else {
            $sql_query .= " ORDER BY category_id DESC LIMIT 0, 10";
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

        //$client_account_id = $this->get_user_clientid($user);
        //$sql_query = "SELECT * FROM subscription_contents WHERE client_account_ids = $user AND is_active = 1";

        $sql_query = "SELECT * FROM subscription JOIN subscription_contents ON  subscription.subscription_id = subscription_contents.subscription_ids  WHERE subscription.client_account_ids = $user ";

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
        if (!empty($cid)) {
            // $cid = implode("','", $cid);
            foreach ($cid as $kk) {
                $sql_query = "SELECT * FROM carry_forward JOIN analyses ON analyses.analysis_id = carry_forward.analysis  WHERE customer = $kk AND (month >= '$date' AND month <= '$dateend')";
                // print_r($sql_query);

                $result = $this->mysqli->query($sql_query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $data[$kk][] = $row;
                    }
                }
//                else {
//                    $data = false;
//                }
            }
        }
        return $data;
    }

    public function carry_forward_with_cust_ans_id($cid, $pre_date, $ans_id) {



        $data = array();

        $sql_query = "SELECT * FROM carry_forward  WHERE customer = $cid AND month = '$pre_date' AND analysis = $ans_id ";

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
        $sql_query = "SELECT * FROM carry_forward JOIN analyses ON analyses.analysis_id = carry_forward.analysis  WHERE customer = $cid AND month = '$date' AND analysis = $ans ";

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

    public function subscibed_user($client_account_id) {
        $data = array();

        $sql_query = "SELECT * FROM subscription WHERE client_account_ids = $client_account_id";

        //$sql_query = "SELECT * FROM subscription_contents WHERE client_account_id = $client_account_id";

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

    public function discounted_user($client_account_id) {

        $data = array();

        $sql_query = "SELECT * FROM monthly_volume_discount WHERE client_account_ids = $client_account_id";

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

        $sql_query = "SELECT * FROM maintenance_fees WHERE client_account_ids = $user ORDER BY maintenance_fees_id ASC";

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

    public function get_subscription_by_customer($client_account_id) {
        $data = array();

        $sql_query = "SELECT * FROM subscription WHERE client_account_ids = $client_account_id and is_active='1' ORDER BY subscription_id ASC";

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

        $sql_query = "SELECT * FROM maintenance_fees 
						WHERE client_account_ids = '$user' AND maintenance_fee_type = '$monthly'";
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
        $sql_query = "SELECT * FROM Salesforce ORDER BY id DESC";

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
        $sql_query = "SELECT * FROM $table $where ORDER BY created_at DESC";
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
        $sql_query = "SELECT * FROM $table $where  ORDER BY user_name";
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
        $sql_query = "SELECT t1.*,t2.user_id as uid,t2.user_name as cus FROM miscellaneous_billing t1 INNER JOIN users t2 ON t1.client_account_ids=t2.user_id ORDER BY t1.miscellaneous_billing_id DESC";
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
        $data = [];
        $sql_query = "SELECT * FROM Salesforce WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function analyses_by_id($id) {
        $data = [];
        $sql_query = "SELECT * FROM analyses WHERE analysis_id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function analyses_rate_by_id($id) {
        $data = [];
        $sql_query = "SELECT * FROM analyses_client_price_details WHERE id = $id";

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
        $data = [];
        $sql_query = "SELECT * FROM analyses_category WHERE category_id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function salesforce_code_by_code($code) {
        $data = [];
        $sql_query = "SELECT * FROM Salesforce WHERE code = $code";

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
        $data = [];
        $sql_query = "SELECT * FROM analyses WHERE analysis_id = $id";

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
        $data = [];
        $sql_query = "SELECT * FROM $table WHERE id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function get_by_analysis_id($id) {
        $data = [];
        $sql_query = "SELECT * FROM analyses WHERE analysis_id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function get_by_field($table, $field, $val) {
        $data = [];
        $sql_query = "SELECT * FROM $table WHERE $field = $val";
        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function get_by_clario_id($id) {
        $data = [];
        $sql_query = "SELECT * FROM analyses_performed WHERE studies_id = $id";

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
      //$sql_query = "SELECT * FROM worksheets WHERE ( date BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
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
        $data = [];
        $start_date = $date . '-01 00:00:00';
        $end_date = $edate . '-31 23:59:59';
        if (!empty($cid)) {
            foreach ($cid as $kk) {
                $sql_query = "SELECT * FROM studies JOIN analyses_performed ON studies.id = analyses_performed.studies_id  WHERE ( analyses_performed.date BETWEEN '$start_date' AND '$end_date') AND analyses_performed.customer_id = $kk AND analyses_performed.status = 'Completed' ";
                $result = $this->mysqli->query($sql_query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if (!empty($row)) {
                            $data[$kk][] = $row;
                        }
                    }
                }
//                else {
//                    $data = false;
//                }
            }
        }
        return $data;
    }

    /*  public function get_wsheet_id_customer($cid, $date) {
      $start_date = $date . '-01 00:00:00';
      $end_date = $date . '-31 23:59:59';
      //$sql_query = "SELECT * FROM worksheets WHERE ( date BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
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
        $data = [];
        $start_date = $date . '-01 00:00:00';
        $end_date = $date . '-31 23:59:59';
        //$sql_query = "SELECT * FROM worksheets WHERE ( date BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
        $sql_query = "SELECT * FROM studies INNER JOIN analyses_performed ON studies.id = analyses_performed.studies_id  WHERE ( analyses_performed.date BETWEEN '$start_date' AND '$end_date') AND analyses_performed.customer_id  = $cid AND analyses_performed.status = 'Completed'"; // GROUP BY studies.id";
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
        $data = [];
        $start_date = $date . '-01 00:00:00';
        $end_date = $edate . '-31 23:59:59';
        //    echo $start_date;
        //   echo $end_date;
        //  $cid = implode("','", $cid);
        //$sql_query = "SELECT * FROM worksheets WHERE ( date BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
        foreach ($cid as $kk) {
            $sql_query = "SELECT * FROM studies INNER JOIN analyses_performed ON studies.id = analyses_performed.studies_id  WHERE ( analyses_performed.date BETWEEN '$start_date' AND '$end_date') AND analyses_performed.customer_id  = $kk AND analyses_performed.status = 'Completed'"; // GROUP BY studies.id";
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
        $data = [];
        $start_date = $date . '-01 00:00:00';
        $end_date = $edate . '-31 23:59:59';
        foreach ($cid as $kk) {
            $sql_query = "SELECT analyses_performed.id, studies.patient_name, studies.mrn, studies.review_user_id,studies.assignee, analyses_performed.date, studies.webhook_customer,studies.accession, analyses_performed.custom_analysis_description, analyses_performed.analyst_hours FROM studies INNER JOIN analyses_performed ON studies.id = analyses_performed.studies_id  WHERE ( analyses_performed.date BETWEEN '$start_date' AND '$end_date') AND analyses_performed.customer_id  = $kk AND analyses_performed.status = 'Completed'"; // GROUP BY studies.id";

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
        $data = [];
        $datearr = explode('-', $date);
        //print_r($datearr);exit;
        $year = $datearr[0];
        $month = $datearr[1];

        $start_date = $date . '-01 00:00:00';
        $end_date = $date . '-31 23:59:59';
        //$sql_query = "SELECT * FROM worksheets WHERE ( date BETWEEN '$start_date' AND '$end_date') AND customer_id  = $cid";
        $sql_query = "SELECT * FROM studies INNER JOIN analyses_performed ON studies.id = analyses_performed.studies_id WHERE MONTH(studies.created) = '$month' AND YEAR(studies.created) = '$year' AND analyses_performed.analyst  = $cid AND studies.assignee  = $cid AND studies.status = 'Completed' ORDER BY studies.created ASC";

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
        $data = [];

        $datearr = explode('-', $date);
        //print_r($datearr);exit;
        $year = $datearr[0];
        $month = $datearr[1];

        $start_date = $date . '-01 00:00:00';
        $end_date = $edate . '-31 23:59:59';

        foreach ($cid as $kk) {
            $sql_query = "SELECT * FROM studies INNER JOIN analyses_performed ON studies.id = analyses_performed.studies_id WHERE ( studies.created BETWEEN '$start_date' AND '$end_date') AND analyses_performed.analyst  = $kk AND studies.assignee  = $kk AND studies.status = 'Completed' ORDER BY studies.created ASC";

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
        $data = [];
        if (empty($date)) {
            $date = date('Y-m');
        }
        $datearr = explode('-', $date);
        //print_r($datearr);exit;
        $year = $datearr[0];
        $month = $datearr[1];

        $start_date = $date . '-01 00:00:00';
        $end_date = $date . '-31 23:59:59';
        $sql_query = "SELECT * FROM studies INNER JOIN analyses_performed ON studies.id = analyses_performed.studies_id WHERE MONTH(studies.created) = '$month' AND YEAR(studies.created) = '$year' AND studies.status = 'Completed' ORDER BY studies.created ASC"; // GROUP BY studies.id";

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
        $data = [];
        $start_date = $date . '-01';
        $end_date = $edate . '-31';

        if (!empty($ids) && is_array($ids)) {
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
        }
        return $data;
    }

    public function get_customers_with_time_id() {
        $data = [];
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

        $sql_query = "SELECT * FROM users  WHERE user_id ='$cid'";

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
        $data = [];

        $sql_query = "SELECT * FROM users  WHERE user_type_ids ='5' and is_active = '1' ORDER BY user_name ASC";

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
        $data = [];

        $sql_query = "SELECT * FROM users  WHERE user_type_ids ='3' and is_active = '1' ORDER BY user_name ASC";

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

        $data = [];
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

        $data = [];
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

        $sql_query = "SELECT * FROM analyses_client_price_details WHERE analysis_id = $anid && client_account_ids =  $cid";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function get_rate_by_anid_cid_new($anid, $cid) {
        $data = array();
        $client_account_id = $this->get_user_clientid($cid);
        $sql_query = "SELECT analysis_client_price FROM  analyses_client_price_details WHERE analysis_id = $anid && client_account_ids =  $cid";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function get_rate_by_an_cl_id($anid, $cid) {
        $client_account_id = $this->get_user_clientid($cid);
        $sql_query = "SELECT analysis_client_price FROM analyses_client_price_details WHERE analysis_client_price_id = $anid && client_account_ids =  $cid";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data['analysis_client_price'];
    }

    public function clario_import($data = array()) {
        $sql_query = "INSERT INTO studies VALUES (NULL,'$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','','$data[6]','$data[7]','$data[8]','$data[9]',0,0,'','')";
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
        $sql_query = "INSERT INTO hospital VALUES (NULL,'$name')";
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
        $data = [];
        $sql_query = "SELECT DISTINCT hospital FROM studies";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function clario_sites() {
        $data = [];
        $sql_query = "SELECT DISTINCT site FROM studies";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function analyses_code_by_code_individ($code) {
        $data = [];
        $sql_query = "SELECT * FROM analyses_client_price_details WHERE code = '$code'";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function users($key = "", $page_now = "") {
        $data = array();
        $sql_query = "SELECT * FROM users";

        if ($key != '') {
            $sql_query .= " WHERE user_name LIKE '%$key%' OR email LIKE '%$key%' ";
        }

        $data['pagination'] = $this->pagination($page_now, $sql_query, SITE_URL . '/admin/user', $key);

        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " ORDER BY user_id DESC LIMIT $start_from, 10";
        } else {
            $sql_query .= " ORDER BY user_id DESC LIMIT 0, 10";
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
        $sql_query = "SELECT * FROM users";

        if ($key != '') {
            $sql_query .= " WHERE user_type_ids = 5 AND (user_name LIKE '%$key%' OR email LIKE '%$key%') ";
        } else {
            $sql_query .= " WHERE user_type_ids = 5";
        }


        $data['pagination'] = $this->pagination($page_now, $sql_query, SITE_URL . '/admin/customer', $key);

        if ($page_now > 1) {
            $start_from = ($page_now - 1) * 10;
            $sql_query .= " ORDER BY user_name ASC LIMIT $start_from, 10";
        } else {
            $sql_query .= " ORDER BY user_name ASC LIMIT 0, 10";
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

        $sql_query = "SELECT * FROM users WHERE user_id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function dicom_details($page_now = "", $page_url = "") {
        $data = array();
        $sql_query = "SELECT * FROM studies WHERE assignee = 0";

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

    public function dicom_details_assigned($page_now = "", $page_url = "") {
        $data = array();
        $sql_query = "SELECT * FROM studies JOIN analyses_performed ON studies.id = analyses_performed.studies_id";

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
        $sql_query = "SELECT * FROM studies JOIN analyses_performed ON studies.id = analyses_performed.studies_id WHERE studies.id = $id";

        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function analysis_data_by_ids($analysis_id, $customer_id) {
        $data = array();
        $sql_query = "SELECT analyses.analysis_id,analyses.analysis_name,analyses_client_price_details.code,analyses_client_price_details.rate FROM analyses JOIN analyses_client_price_details ON analyses.analysis_id = analyses_client_price_details.analysis WHERE analyses.analysis_id = $analysis_id and analyses_client_price_details.client_account_ids = $customer_id";
        /* $sql_query = "SELECT analyses.id,analyses.name,analyses_client_price_details.code FROM analyses JOIN analyses_client_price_details ON analyses.id = analyses_client_price_details.analysis WHERE analyses.id = $analysis_id"; */

        //print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function dicom_detail_by_id($page_now = "", $page_url = "", $id = "") {
        $data = array();
        $sql_query = "SELECT * FROM studies WHERE assignee = $id";

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
        $sql_query = "UPDATE studies SET assignee = '$assignee' , customer = '$customer', tat= '$tat', status = 'In progress'  WHERE id = $work_id";
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

        $time_ids = $this->get_user_timeline($customer_id);
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

        $sql_query = "INSERT INTO analyses_performed (id,time_id, analyst, studies_id, date, other, analyses_performed, custom_analysis_description, other_notes, addon_flows, analyst_hours, expected_time, image_specialist_hours, medical_director_hours, pia_analysis_codes, status, customer_id, analyses_ids, existing_rate,any_mint,review_user_id) VALUES (NULL,'$time_id','$analyst', '$clario_id', '$date', '$other', '$analyses_performed', '$custom_analysis_description', '$other_notes', '$addon_flows', '$analyst_hours', '$expected_time', '$image_specialist_hours', '$medical_director_hours', '$pia_analysis_codes', '$status', '$customer_id', '$analyses_ids', '$existing_rate','$ans_hr','$txtReviewAnalyst')";

        $sql_query_clario = "UPDATE studies SET status = '$status',assignee = $analyst,review_user_id='$txtReviewAnalyst' WHERE  id = $clario_id";

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
            $sql_query = "UPDATE studies SET tat= '$tat'  WHERE id = $clario_id";
            $result = $this->mysqli->query($sql_query);

            if ($result === TRUE) {
                return true;
            }
        }
        return false;
    }

    public function insert_wsheet_details($data = array(), $worksheet_id = '', $ans_id = '', $rate = '', $qty = '', $customer = '') {
        extract($data);

        $sql_query = "INSERT INTO worksheet_details (worksheet_id,customer_id, date, ans_id, rate, qty) 
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

        $sql_query = "UPDATE analyses_performed SET completed_time = '$cdate' WHERE  studies_id = $clario_id";
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
          $time_ids = $this->get_user_timeline($customer_id);

          $time_id = $time_ids['time_id']; */

        $analyst_hours = round($analyst_hours, 2);
        $medical_director_hours = round($medical_director_hours, 2);
        $image_specialist_hours = round($image_specialist_hours, 2);
        //$other_notes = mysqli_real_escape_string($other_notes);
        $other_notes = $this->mysqli->real_escape_string($other_notes);

        if (empty($txtReviewAnalyst)) {
            $txtReviewAnalyst = 0;
        }


        $sql_query = "UPDATE analyses_performed SET other = '$other', analyses_performed = '$analyses_performed', custom_analysis_description = '$custom_analysis_description', other_notes = '$other_notes', addon_flows =  '$addon_flows', analyst_hours = '$analyst_hours', image_specialist_hours = '$image_specialist_hours', medical_director_hours = '$medical_director_hours', pia_analysis_codes = '$pia_analysis_codes', status = '$status', customer_id = '$customer_id', analyses_ids = '$analyses_ids', date = '$date', existing_rate = '$existing_rate', expected_time = '$expected_time',any_mint='$ans_hr',	review_user_id='$txtReviewAnalyst' WHERE  studies_id = $clario_id";
        $sql_query_clario = "UPDATE studies SET status = '$status',assignee = $analyst,review_user_id='$txtReviewAnalyst' WHERE  id = $clario_id";

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
        $sql_query = "UPDATE worksheet_details SET ans_id = '$ans_id', date = '$date', rate = '$rate', qty = '$qty'  
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
        $sql_query = "UPDATE analyses_performed SET customer_id = '$data'  WHERE analyst = $analyst AND  studies_id = $clario_id";
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
            $filter = " AND analyses_performed.analyst = '$analyst' AND studies.site = '$site' ";
        } elseif ($site == '' && $analyst != '') {
            $filter = " AND analyses_performed.analyst = '$analyst' ";
        } elseif ($site == '' && $analyst == '') {
            $filter = " AND studies.site = '$site' ";
        }

        $sql_query = "
		SELECT  analyses_performed.studies_id, 
			analyses_performed.date, 
			analyses_performed.customer_id, 
			analyses_performed.analyses_ids, 
			analyses_performed.addon_flows, 
			analyses_performed.status, 
			analyses_performed.analyses_performed, 
			analyses_performed.pia_analysis_codes, 
                        analyses_performed.expected_time,

			users.user_name, 
			studies.hospital, 
			studies.site  
		FROM analyses_performed 
		JOIN users ON analyses_performed.analyst=users.user_id 
		JOIN studies ON analyses_performed.studies_id=studies.id 
		WHERE ( date BETWEEN '$start_date' AND '$end_date') 
		AND analyses_performed.status = 'Completed' 
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
        $data_ret = [];

        extract($data);
        $start_date = $data['start_date'] . '-01';
        $end_date = $data['start_date'] . '-31';
        $hospital = $data['site'];

        /* $sql_query = "SELECT worksheets.clario_id,worksheets.id, worksheets.customer_id, worksheets.date, worksheets.status, worksheets.analyses_performed, worksheets.pia_analysis_codes, users.name, Clario.hospital, Clario.site FROM worksheets JOIN users ON worksheets.analyst=users.id JOIN Clario ON worksheets.clario_id=Clario.id WHERE ( date BETWEEN '$start_date' AND '$end_date') AND worksheets.status = 'Completed' AND Clario.site = 'Imaging Hospital'"; */


        $sql_query = "SELECT DISTINCT analyses_performed.customer_id FROM analyses_performed JOIN users ON analyses_performed.analyst=users.user_id JOIN studies ON analyses_performed.studies_id=studies.id WHERE ( date BETWEEN '$start_date' AND '$end_date') AND analyses_performed.status = 'Completed' AND analyses_performed.customer_id = $hospital";

        print_r($sql_query);
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data_ret[] = $row;
            }
        }
        return $data_ret;
    }

    public function collect_wsheet($userid = "") {
        $data = array();
        $sql_query = "SELECT * FROM analyses_performed";

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
        }
        return $data;
    }

    public function wsheet_assign_list() {
        $data = array();

        $sql_query = "SELECT * 
		FROM studies 
		JOIN analyses_performed ON analyses_performed.studies_id=studies.id ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function wsheet_assign_list_full() {
        $data = array();
        $data2 = array();

        $sql_query = "select * from studies WHERE assignee != 0";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
                $data['results_old'][$row['id']] = $row;
            }

            foreach ($data['results'] as $key => $value) {
                $sql_query = "SELECT * 
					FROM studies 
					JOIN analyses_performed ON analyses_performed.studies_id=studies.id  WHERE studies.id = " . $value['id'];

                $result2 = $this->mysqli->query($sql_query);
                if ($result2->num_rows == 1)
                    $data2['results'][] = $result2->fetch_assoc();
                else {

                    $data['results_old'][$value['id']]['status'] = "In progress";
                    $data2['results'][] = $data['results_old'][$value['id']];
                }
            }
        }
        return $data2;
    }

    public function wsheet_assign_list_analyst_full() {
        $data = array();
        $data2 = array();

        $sql_query = "select * from studies ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
                $data['results_old'][$row['id']] = $row;
            }

            foreach ($data['results'] as $key => $value) {
                $sql_query = "SELECT * 
					FROM studies 
					JOIN analyses_performed ON analyses_performed.studies_id=studies.id  WHERE studies.id = " . $value['id'];

                $result2 = $this->mysqli->query($sql_query);
                if ($result2->num_rows == 1)
                    $data2['results'][] = $result2->fetch_assoc();
                else {

                    $data['results_old'][$value['id']]['status'] = "In progress";
                    $data2['results'][] = $data['results_old'][$value['id']];
                }
            }
        }
        return $data2;
    }

    public function wsheet_assign_list_analyst_day($day) {

        $data = array();
        $data2 = array();

        $sql_query = "SELECT * FROM studies WHERE TIMESTAMPDIFF(DAY,last_modified,NOW()) < $day ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
                $data['results_old'][$row['id']] = $row;
            }

            foreach ($data['results'] as $key => $value) {
                $sql_query = "SELECT * 
					FROM studies 
					JOIN analyses_performed ON analyses_performed.studies_id=studies.id  WHERE studies.id = " . $value['id'];

                $result2 = $this->mysqli->query($sql_query);
                if ($result2->num_rows == 1)
                    $data2['results'][] = $result2->fetch_assoc();
                else {

                    $data['results_old'][$value['id']]['status'] = "In progress";
                    $data2['results'][] = $data['results_old'][$value['id']];
                }
            }
        }
        return $data2;
    }

    public function wsheet_assign_list_analyst_assignee($day = '', $asignee = '', $status = '') {

        $data = array();
        $data2 = array();

        if (empty($day) && !empty($asignee)) {
            $sql_query = "SELECT * FROM studies WHERE assignee = $asignee ";
        } elseif (!empty($day) && empty($asignee)) {
            $sql_query = "SELECT * FROM studies WHERE TIMESTAMPDIFF(DAY,last_modified,NOW()) < $day";
        } elseif (!empty($asignee) && !empty($day)) {
            $sql_query = "SELECT * FROM studies WHERE assignee = $asignee and TIMESTAMPDIFF(DAY,last_modified,NOW()) < $day";
        } else {
            $sql_query = "select * from studies";
        }

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
                $data['results_old'][$row['studies_id']] = $row;
            }

            foreach ($data['results'] as $key => $value) {
                $sql_query = "SELECT * 
					FROM studies 
					JOIN analyses_performed ON analyses_performed.studies_ids=studies.studies_id  WHERE studies.studies_id = " . $value['studies_id'];

                $result2 = $this->mysqli->query($sql_query);
                if ($result2->num_rows == 1)
                    $data2['results'][] = $result2->fetch_assoc();
                else {

                    $data['results_old'][$value['studies_id']]['status_ids'] = "3";
                    $data2['results'][] = $data['results_old'][$value['studies_id']];
                }
            }
        }
        return $data2;
    }

    public function subscription_analyses($cid) {

        $data = array();

        $time_ids = $this->get_user_timeline($cid);

        $time_id = $time_ids['time_id'];

        $sql_query = "SELECT analyses.id, analyses.name, analyses_client_price_details.min_time, analyses.description, analyses_client_price_details.custom_description  FROM analyses JOIN analyses_client_price_details ON analyses.id = analyses_client_price_details.analysis WHERE analyses_client_price_details.customer = $cid AND analyses_client_price_details.time_id = $time_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function get_my_analyses($cid) {

        $data = array();

        $client_account_id = $this->get_user_clientid($cid);

        //$time_id = $time_ids['time_id'];

        $sql_query = "SELECT analyses.analysis_id, analyses.analysis_name,analyses_client_price_details.analysis_client_price_id FROM analyses JOIN analyses_client_price_details ON analyses.analysis_id = analyses_client_price_details.analysis_id WHERE analyses_client_price_details.client_account_ids = $client_account_id ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }

        return $data;
    }

    public function get_user_clientid($cid) {
        $data = array();
        $sql_query = "SELECT client_account_id FROM client_details WHERE user_ids = 260";

        // /print_r($sql_query); die;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data['client_account_id'];
    }

    public function get_user_sitecode($cid) {
        $data = array();
        $sql_query = "SELECT site_code FROM client_details WHERE client_account_id = $cid";

        //print_r($sql_query); die;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data['site_code'];
    }

    public function count_subscription($cid, $anid, $time_id) {

        $data = array();

        $sql_query = "SELECT * FROM subscriptions JOIN analyses_client_price_details ON subscriptions.analysis = analyses_client_price_details.analysis AND subscriptions.customer = analyses_client_price_details.customer AND subscriptions.time_id = analyses_client_price_details.time_id WHERE analyses_client_price_details.customer = $cid AND analyses_client_price_details.analysis = $anid AND subscriptions.time_id = $time_id AND analyses_client_price_details.time_id = $time_id";
        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function analyses_rate_details($cid, $anid, $time_id) {

        $data = array();

        // $cid = implode("','", $cid);
        // print_r($time_id);

        $sql_query = "SELECT * FROM analyses_client_price_details WHERE customer = $cid  AND analysis = $anid AND time_id = $time_id";
        // print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function only_count_subscription($cid, $anid, $time_id) {

        $data = array();
        $cid = implode("','", $cid);
        //$sql_query = "SELECT* FROM subscriptions  WHERE customer = $cid AND analysis = $anid AND time_id = $time_id";

        $sql_query = "SELECT * FROM subscriptions  WHERE customer IN ('" . $cid . "') AND analysis = $anid AND time_id = $time_id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function count_previous_carry($cid, $anid, $date) {
        $data = array();

        $sql_query = "SELECT * FROM carry_forward WHERE customer = '$cid' AND analysis = '$anid' AND month = '$date'";
        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function count_subscription_for_customer($cid) {
        $data = array();

        $sql_query = "SELECT * FROM subscriptions JOIN analyses_client_price_details ON subscriptions.analysis = analyses_client_price_details.analysis AND subscriptions.customer = analyses_client_price_details.customer WHERE analyses_client_price_details.customer = $cid";
        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }

        return $data;
    }

    public function whseet_by_cid($id) {
        $data = array();
        $sql_query = "SELECT * 
		FROM studies 
		JOIN analyses_performed ON analyses_performed.studies_id=studies.id  WHERE analyses_performed.id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function count_table($table, $where = '') {
        $query = " SELECT * FROM $table $where";
        $result = $this->mysqli->query($query);
        return mysqli_num_rows($result);
    }

    public function count_tableNew($table, $where = '') {
        $query = "SELECT COUNT(id) AS cnt FROM $table $where";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return !empty($row['cnt']) ? $row['cnt'] : 0;
    }

    public function count_usertableNew($table, $where = '') {
        $query = "SELECT COUNT(user_id) AS cnt FROM $table $where";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return !empty($row['cnt']) ? $row['cnt'] : 0;
    }

    public function count_table_by_date_new($table, $from, $to) {
        $query = "SELECT COUNT(id) AS cnt FROM $table WHERE ( created BETWEEN '$from' AND '$to') AND assignee <> 0";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return !empty($row['cnt']) ? $row['cnt'] : 0;
    }

    public function count_table_by_date_clario_new($table, $from, $to) {
        $query = "SELECT COUNT(id) As cnt FROM $table WHERE ( created BETWEEN '$from' AND '$to')";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return !empty($row['cnt']) ? $row['cnt'] : 0;
    }

    public function getsecondcheckcountdone($from, $to) {
        $query = "SELECT * FROM studies WHERE ( created BETWEEN '$from' AND '$to') AND review_user_id!=''";
        $result = $this->mysqli->query($query);
        return mysqli_num_rows($result);
    }

    public function getsecondcheckcountnotdone($from, $to) {
        $query = "SELECT * FROM studies WHERE ( created BETWEEN '$from' AND '$to') AND review_user_id=''";
        $result = $this->mysqli->query($query);
        return mysqli_num_rows($result);
    }

    public function anysecondcheckcountdone() {
        $query = "SELECT * FROM studies WHERE  review_user_id!=''";
        $result = $this->mysqli->query($query);
        return mysqli_num_rows($result);
    }

    public function anysecondcheckcountnotdone() {
        $sql = "SELECT * FROM studies WHERE  review_user_id=''";
        $result = $this->mysqli->query($sql);
        return mysqli_num_rows($result);
    }

    //public function count_table_by_date_customers($table, $from, $to, $group) {
    //    $query = "SELECT * FROM $table WHERE ( created BETWEEN '$from' AND '$to') AND group_id= $group ";
    //    $result = $this->mysqli->query($query);
    //    return mysqli_num_rows($result);
    //}

    public function count_analyst_hours() {
        $result = $this->mysqli->query('SELECT SUM(analyst_hours) AS value_sum FROM analyses_performed');
        $row = mysqli_fetch_assoc($result);
        return !empty($row['value_sum']) ? $row['value_sum'] : 0;
    }

    public function count_analyst_hours_by_date($from, $to) {
        $query = "SELECT SUM(analyst_hours) AS value_sum FROM analyses_performed WHERE (date BETWEEN '$from' AND '$to')";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return !empty($row['value_sum']) ? $row['value_sum'] : 0;
    }

    public function count_table_jobUnderReviewNew($table, $from, $to) {
        $query = "SELECT COUNT(id) AS cnt FROM $table WHERE ( created BETWEEN '$from' AND '$to') AND status = 'Under review'";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return !empty($row['cnt']) ? $row['cnt'] : 0;
    }

    public function count_table_jobs_In_progressNew($table, $from, $to) {
        $query = "SELECT COUNT(id) AS cnt FROM $table WHERE ( created BETWEEN '$from' AND '$to') AND status = 'In progress'";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return !empty($row['cnt']) ? $row['cnt'] : 0;
    }

    public function count_table_jobs_on_holdNew($table, $from, $to) {
        $query = "SELECT COUNT(id) AS cnt FROM $table WHERE ( created BETWEEN '$from' AND '$to') AND status = 'On hold'";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return !empty($row['cnt']) ? $row['cnt'] : 0;
    }

    public function count_table_jobs_cancelledNew($table, $from, $to) {
        $query = "SELECT COUNT(id) AS cnt FROM $table WHERE ( created BETWEEN '$from' AND '$to') AND status = 'Cancelled'";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return !empty($row['cnt']) ? $row['cnt'] : 0;
    }

    public function count_table_jobs_CompletedNew($table, $from, $to) {
        $query = "SELECT COUNT(id) AS cnt FROM $table WHERE ( created BETWEEN '$from' AND '$to') AND status = 'Completed'";
        $result = $this->mysqli->query($query);
        $row = mysqli_fetch_assoc($result);
        return !empty($row['cnt']) ? $row['cnt'] : 0;
    }

    public function clario_exist($accession, $mrn) {
        $sql_query = "SELECT * FROM studies WHERE accession='$accession' AND mrn='$mrn'";
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

        $sql_query = "SELECT * FROM studies WHERE id = $id";
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

        $sql_query = "SELECT * FROM studies WHERE id = $id";
        $result = $this->mysqli->query($sql_query);

        if ($result->num_rows >= 1) {

            $db_data = (array) $result->fetch_object();
            return $db_data['assignee'];
        }
    }

    public function clario_import_update($id, $data) {


        $sql_query = "UPDATE studies SET 
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
        $query = "SELECT SUM(" . $field . ") as " . $field . "total, DATE_FORMAT(date,'%m') monthnum  FROM  analyses_performed WHERE TIMESTAMPDIFF(MONTH,date,NOW()) < 6 GROUP BY DATE_FORMAT(date,'%m') ORDER BY DATE_FORMAT(date,'%m')";
        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function wsheet_static() {
        $query = "SELECT DATE_FORMAT(date,'%m') monthnum, SUM(analyst_hours) as analyst_hours_sum , SUM(image_specialist_hours) as image_specialist_hours_sum , SUM(medical_director_hours) as medical_director_hours_sum FROM analyses_performed 
WHERE TIMESTAMPDIFF(MONTH,date,NOW()) < 6 GROUP BY DATE_FORMAT(date,'%m') ORDER BY DATE_FORMAT(date,'%m')";
        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function wsheet_month_rate() {
        $query = "SELECT DATE_FORMAT(date,'%m') monthnum, addon_flows,pia_analysis_codes,analyses_ids,customer_id FROM analyses_performed WHERE date < Now() and date > DATE_ADD(Now(), INTERVAL- 6 MONTH)";
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
        $query = "SELECT pia_analysis_codes,Month(date) monthnum FROM analyses_performed WHERE TIMESTAMPDIFF(MONTH,date,NOW()) < 6 AND status ='Completed' ORDER BY Month(date)";
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
        $sql_query = "SELECT * FROM discount_range";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function get_last_discount_range() {

        $sql_query = "SELECT * FROM monthly_volume_discount ORDER BY discount_id DESC LIMIT 1";
        $result = $this->mysqli->query($sql_query);
        return $result->fetch_assoc()['discount_volume'];
    }

    public function discount_pricing_add($client_account_id, $minimum_value, $maximum_value, $percentage) {



        $sql_query = "INSERT INTO monthly_volume_discount (client_account_ids,minimum_volume,maximum_volume,discount_price,is_active) VALUES ($client_account_id,$minimum_value,$maximum_value,$percentage,1)";

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
        $data = [];
        $sql_query = "SELECT * FROM monthly_volume_discount WHERE discount_id = $id";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function discount_range_update($data = array()) {
        extract($data);
        $sql_query = "UPDATE monthly_volume_discount SET client_account_ids = '$client_account_id',minimum_value = '$minimum_value' , maximum_value = '$maximum_value', percentage = '$percentage' WHERE is_active = 1 AND client_account_ids = '$client_account_ids'";

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

    public function get_discount_range_by_customer($client_account_id) {

        $data = array();

        $sql_query = "SELECT * FROM monthly_volume_discount WHERE client_account_ids = $client_account_id AND is_active = 1";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function get_discount($count, $cid) {
        $data = array();
        $sql_query = "SELECT percentage FROM monthly_volume_discount WHERE  minimum_value <= '$count' and maximum_value >= '$count' AND client_account_ids = '$cid' AND is_active = 1 LIMIT 1";

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

    public function get_discount_details($count, $cid) {


        $data = array();
        $sql_query = "SELECT * FROM monthly_volume_discount WHERE  minimum_value <= $count and maximum_value >= $count AND client_account_ids = $cid AND is_active = 1 LIMIT 1";

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
        $data = [];
        //echo" $acc  $mrn";die;
        $sql_query = "SELECT cc_code FROM studies where accession = '$acc'  and mrn ='$mrn' ";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function get_user_id_by_cc_code($cc) {
        $data = [];
        //echo" $acc  $mrn";die;

        $sql_query = "SELECT user_id FROM users where user_meta like '%$cc%' ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function get_analyses_by_user($id) {
        $data = [];
        $sql_query = "SELECT code FROM analyses_client_price_details where customer = $id";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }


        foreach ($data as $value) {
            $sql_query = "SELECT * FROM analyses where part_number = " . $value['code'];

            $result = $this->mysqli->query($sql_query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data['analyses'][] = $row;
                }
            }
        }

        return !empty($data['analyses']) ? $data['analyses'] : [];
    }

    public function ans_details() {
        
    }

    public function total_analyst_hours($table, $where = '') {
        $data = [];
        //$sql_query = "SELECT * FROM $table $where";
        $sql_query = "SELECT SUM(analyst_hours) totalanalysthours FROM $table $where";

        //echo "hfds".$sql_query; die;
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row['totalanalysthours'];
            }
        }
        return $data;
    }

    public function cases_by_analyses_types() {
        $query = "SELECT analyses_ids FROM analyses_performed where WEEK(date) = WEEK( current_date ) - 1 AND YEAR( date) = YEAR( current_date ) AND status = 'Completed' ";
        $result = $this->mysqli->query($query);
        $string = '';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $string .= $row['analyses_ids'] . ',';
            }
        }

        $string = !empty($string) ? explode(',', $string) : [];
        $a = array();
        foreach ($string as $key => $value) {
            if ($value != '')
                if (isset($a[$value])) {
                    $a[$value]++;
                } else {
                    $a[$value] = 1;
                }
        }
        $an_ids = !empty($a) ? array_keys($a) : '';
        $an_id_string = !empty($an_ids) ? implode(',', $an_ids) : '';

        $result = '';
        if (!empty($an_id_string)) {
            $query = "SELECT analyses.analysis_id, analyses.analysis_name, analyses_category.category_name FROM analyses JOIN analyses_category ON analyses.category_ids = analyses_category.category_id WHERE analyses.analysis_id IN ($an_id_string)";
            $result = $this->mysqli->query($query);
        }
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
        $sql_query = "SELECT analysis_description FROM analyses_client_price_details WHERE analysis = $analyses_id AND customer = $customer_id";

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

        $sql_query = "SELECT * FROM analyses_client_price_details WHERE analysis = $analyses_id AND customer = $customer_id";
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

        $sql_query = "SELECT analysis_description FROM analyses_client_price_details WHERE analysis = $analyses_id AND customer = $customer_id";
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
        $sql_query = "SELECT code FROM analyses_client_price_details WHERE analysis = $analyses_id AND customer = $customer_id";
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
        $sql_query = "SELECT part_number FROM analyses WHERE id  = $analyses_id";
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

        $sql_query = "SELECT * FROM analyses_client_price_details WHERE analysis = $analyses_id";
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

        $sql_query = "SELECT user_id, user_name FROM users WHERE user_type_ids = 3 AND user_id='$aid'";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data['name'];
    }

    public function daily_work_count() {
        $data = [];
        $query = "SELECT DATE_FORMAT(created,'%d') day, COUNT(created) count FROM studies  WHERE MONTH(created) = MONTH(CURRENT_DATE()) AND YEAR(created) = YEAR(CURRENT_DATE()) AND TIMESTAMPDIFF(DAY,created,NOW()) < 7 GROUP by DATE_FORMAT(created,'%d')";
        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function daily_completed_work_count() {
        $data = [];
        $query = "SELECT DATE_FORMAT(date,'%d') day, COUNT(date) count FROM analyses_performed WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE()) AND TIMESTAMPDIFF(DAY,date,NOW()) < 7 AND status = 'Completed' GROUP by DATE_FORMAT(date,'%d')";
        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function check_email($email, $id = '') {
        $sql_query = "SELECT * FROM users WHERE email = '$email'";
        if (!empty($id)) {
            $sql_query .= " AND user_id != '$id'";
        }
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        if (!empty($data)) {
            return "exists";
        } else {
            return false;
        }
    }

    public function get_all_analyst() {
        $data = [];
        $sql_query = "SELECT user_id, user_name FROM users WHERE user_type_ids = 3 ORDER BY user_name ASC";

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

    public function get_all_analysis_statuses() {
        $data = [];
        $sql_query = "SELECT status_id, status FROM analysis_status ORDER BY status_id ASC";

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
        $data = [];
        $sql_query = "SELECT DISTINCT customer FROM subscriptions";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function find_analysis_details($analyses_id, $cust_id) {
        $data = [];
        $sql_query = "SELECT * FROM analyses_client_price_details WHERE analysis = '$analyses_id' AND customer = '$cust_id'";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function re_assign($id) {

        $sql_query = "UPDATE analyses_performed SET status = 'Under review' WHERE studies_id = $id";

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
        $sql_queryF = "UPDATE analyses_performed SET review_user_id = '$user_id',second_analyst_hours='$analystHours',second_comment='$comments',second_check_rate='$rate',second_check_date='$second_check_time'  WHERE studies_id = $edit_id";
        $result = $this->mysqli->query($sql_queryF);
        $sql_query = "UPDATE studies SET review_user_id = '$user_id',second_analyst_hours='$analystHours',second_comment='$comments',second_check_rate='$rate' WHERE id = $edit_id";
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
        $data = [];
        $sql = "SELECT review_user_id,second_analyst_hours,second_comment,second_check_rate FROM analyses_performedWHERE studies_id = '$reviewid'";
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
        $data = [];
        $sql_query = "SELECT * FROM invoice WHERE customer_id = '$id' AND date = '$start_date' ORDER BY created_at DESC  ";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function get_additional_invoice_details($invoice_id) {
        $data = [];
        $sql_query = "SELECT * FROM additiional_invoice_details WHERE invoice_id = '$invoice_id'";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function get_all_customer() {
        $data = [];

        $sql_query = "SELECT DISTINCT analyses_performed.customer_id as id, users.user_name FROM analyses_performed JOIN users on analyses_performed.customer_id = users.user_id";

        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function get_all_customer_new() {
        $data = [];

        $sql_query = "SELECT DISTINCT analyses_performed.customer_id as id, users.user_name FROM analyses_performed JOIN users on analyses_performed.customer_id = users.user_id AND users.is_active = 1 ORDER BY users.user_name";

        //print_r($sql_query);

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function get_name_by_id($id) {
        $data = [];

        $sql_query = "SELECT user_name FROM users WHERE user_id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {
            $data = [];
        }
        return !empty($data['name']) ? $data['name'] : '';
    }

    public function get_study_time_graph($customer, $month, $year) {
        $data = array();
        //$Analyst;
        //$this->Analyst		=	$this->model('analystratemodel');
        //$sql_query = "SELECT sum(analyst_hours) as analysthrs, sum(image_specialist_hours) as imagehrs, sum(medical_director_hours) as medicalhrs FROM worksheets WHERE customer_id = $customer AND month(date) = $month AND year(date) = $year";
        $workTimeTotal = 0;
        $excateTotalTime = 0;
        $sql_query = "SELECT any_mint,analyses_ids FROM analyses_performed WHERE customer_id = $customer AND  month(date) = $month AND year(date) = $year";
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

        $sql_query = "SELECT analysis_invoicing_description, analysis_client_price,analysis_code FROM analyses_client_price_details WHERE client_account_ids = $user";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = ['analysis_invoicing_description' => 0, 'analysis_client_price' => 0, 'analysis_code' => 0];
        }
        return $data;
    }

    public function get_discount_range_by_customer_excel($id) {

        $data = array();

        $sql_query = "SELECT minimum_volume, maximum_volume, discount_price FROM monthly_volume_discount WHERE client_account_ids = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = ['minimum_volume' => 0, 'maximum_volume' => 0, 'discount_price' => 0];
        }
        return $data;
    }

    public function subscriptions_user_excel($user) {
        $data = array();

        // $sql_query = "SELECT analyses.name, count FROM subscriptions JOIN analyses ON analyses.id= subscriptions.analysis WHERE customer = $user";
        //$sql_query = "SELECT analyses_client_price_details.analysis_name,subscription_contents.subscription_volume FROM subscription_contents JOIN analyses_client_price_details ON  subscription_contents.client_account_ids = analyses_client_price_details.client_account_ids JOIN   WHERE subscription_contents.client_account_ids = $user";
        //print_r($sql_query); die;
        $sql_query = "SELECT analyses_client_price_details.analysis_name,subscription_contents.subscription_volume FROM subscription JOIN subscription_contents ON  subscription.subscription_id = subscription_contents.subscription_ids JOIN analyses_client_price_details ON  subscription_contents.analysis_client_price_ids = analyses_client_price_details.analysis_client_price_id WHERE subscription.client_account_ids = $user ";

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

    public function get_subscription_by_customer_excel($client_account_id) {
        $data = array();

        $sql_query = "SELECT subscription_price FROM subscription WHERE client_account_ids = $client_account_id ORDER BY subscription_id ASC";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        } else {
            $data = ['subscription_price' => 0];
        }
        return $data;
    }

    public function get_maintenance_by_customer_excel($user) {
        $data = array();

        $sql_query = "SELECT maintenance_fee_amount FROM maintenance_fees WHERE client_account_ids = $user ";

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

        $sql_query = "SELECT user_name, email, created_at FROM users WHERE user_type_ids = 5";

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
        $sql_query = "SELECT  
    analyses.analysis_name, 
    analyses.analysis_invoicing_description, 
    analyses_category.category_name, 
    analyses.analysis_price, 
    analyses.time_to_analyze, 
    CASE 
        WHEN analyses.is_active = '1' THEN 'ACTIVE' 
        ELSE 'INACTIVE' 
    END AS is_active, 
    analyses.created_at 
FROM analyses 
INNER JOIN analyses_category ON (analyses.category_ids = analyses_category.category_id) WHERE analyses.is_deleted = '0' AND analyses_category.is_deleted = '0' ORDER BY analyses.analysis_name ASC";
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

        $query = "SELECT  * from studies where  accession ='$accession' and mrn='$mrn'";
        $result1 = $this->mysqli->query($query);
        if ($result1->num_rows == 0) {

            $sql_query = "INSERT INTO studies (id, accession, mrn, patient_name, site_procedure, last_modified, exam_time, status, priority, site, hospital, assignee, customer, webhook_customer, webhook_description, created) VALUES (NULL, '$accession', '$mrn', '$patient_name', '', CURRENT_TIMESTAMP, '$exam_time', '', '', '$site', '', 0, 0, '$customer', '$description', CURRENT_TIMESTAMP)";

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

    public function get_latest_analysis_rates($client_account_id, $analysis_client_price_id) {

        $data = [];
        $sql_query = "SELECT analysis_client_price FROM analyses_client_price_details WHERE client_account_ids = '$client_account_id' AND analysis_client_price_id = '$analysis_client_price_id'";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }


        return !empty($data['analysis_client_price']) ? $data['analysis_client_price'] : '';
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- UPDATE NOT ASSIGN -----------------------------------
      @FUNCTION DATE              :  01-01-2019
      ------------------------------------------------------------------------------ */

    public function statusupdate($clario_id) {
        $sql_query = "UPDATE studies SET review_user_id = 0, assignee = 0 , customer = 0, tat= 0, status = ''  WHERE id = $studies_id";
        $this->mysqli->query($sql_query);
    }

    /*     * ***************************** RC ******************************************* */
    /* ----------------------- UPDATE NOT ASSIGN -----------------------------------
      @FUNCTION DATE              :  01-01-2019
      ------------------------------------------------------------------------------ */

    public function getreviewdata($clario_id) {
        $data = [];

        $sql = "SELECT studies.review_user_id,studies.second_analyst_hours,studies.second_comment,studies.second_check_rate,users.user_name  FROM studies INNER JOIN users ON users.user_id=studies.review_user_id WHERE studies.id='$clario_id'";

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

            $sql_query = "INSERT INTO adm_admin_customer_docs(ACD_Customer_ID_FK, ACD_Docs_Path, ACD_Docs_Title, ACD_Docs_Desc, ACD_Add_User_By, ACD_User_Add_On, ACD_Updated_User_By, ACD_User_Updated_On, ACD_Status) VALUES ('$customer_id','$doc_path','$doc_title','$doc_desc','$add_user_by','$user_add_on','$updated_user', '$user_upd_on', '$status')";

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

            $sql_query = "INSERT INTO adm_admin_customer_bills(ACB_Customer_ID_FK, ACB_Bills_Title, ACB_Bills_Desc, ACB_Bills_Invoice_No, ACB_Bills_Due, ACB_Bills_Month, ACB_Bills_Year, ACB_Bills_Total, ACB_Bills_Discount, ACB_Bills_Invoice_Amount, ACB_Bills_Path, ACB_Add_User_By, ACB_User_Add_On, ACB_Updated_User_By, ACB_User_Updated_On, ACB_Status) VALUES ('$customer_id','$bill_title','$bill_desc','$bill_invoice', '$bill_due', '$bill_date', '$bill_date_year', '$bill_total', '$bill_discount', '$bill_invoice_amt', '$bill_path', '$add_user_by','$user_add_on','$updated_user', '$user_upd_on', '$status')";

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
        $sql_query = "UPDATE users SET is_active='$status_new' WHERE user_id = '$id'";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE && $status_new == '0') {
            $status['type'] = 'success';
            $status['msg'] = 'Inactivated Successfully';
            return $status;
        }
        if ($result === TRUE && $status_new == '1') {
            $status['type'] = 'success';
            $status['msg'] = 'Activated Successfully';
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

        $sql_query = "UPDATE users SET is_active='$status_new' WHERE user_id = '$id' AND user_type_ids = 5";

        $result = $this->mysqli->query($sql_query);
        $status = array();

        if ($result === TRUE && $status_new == '0') {
            $status['type'] = 'success';
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

        $sql_query = "UPDATE users SET user_name='$name' WHERE user_id = '$id' AND user_type_ids = 5";

        $result = $this->mysqli->query($sql_query);
        //$status = array();


        if ($result === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function get_custmernames($ids) {
        $data = [];
        if (!empty($ids)) {
            foreach ($ids as $kk) {
                $sql_query = "SELECT name FROM users where user_id = $kk";
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
        }
        return $data;
    }

    /* ----------------------- Update User Status -----------------------------------
      @FUNCTION DATE              :  25-03-2021
      ------------------------------------------------------------------------------ */

    public function analysesStatusUpdate($id, $status_new) {
        $sql_query = "UPDATE analyses SET is_active ='$status_new' WHERE analysis_id = '$id'";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE && $status_new == '0') {
            $status['type'] = 'success';
            $status['msg'] = 'Inactivated successfully';
            return $status;
        }
        if ($result === TRUE && $status_new == '1') {
            $status['type'] = 'success';
            $status['msg'] = 'Activated successfully';
            return $status;
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function analysesCatStatusUpdate($id, $status_new) {
        $sql_query = "UPDATE analyses_category SET is_active='$status_new' WHERE category_id = '$id'";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE && $status_new == '0') {
            $status['type'] = 'success';
            $status['msg'] = 'Inactivated Successfully';
            return $status;
        }
        if ($result === TRUE && $status_new == '1') {
            $status['type'] = 'success';
            $status['msg'] = 'Activated Successfully';
            return $status;
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function getUserTypes() {
        $data = [];
        $sql_query = "SELECT user_type_id, user_type FROM user_type WHERE is_active = '1' AND is_deleted != '1'";

        if ($result = $this->mysqli->query($sql_query)) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $result->free(); // Free result set memory
        } else {
            error_log("MySQL Query Error: " . $this->mysqli->error); // Log any query errors
        }

        return $data;
    }

    public function updateStatus($table, $id, $primary_id, $is_active) {
        $id = $this->mysqli->real_escape_string($id);
        $sql_query = "UPDATE $table SET is_active = '$is_active' WHERE $primary_id=$id";
        // echo $sql_query;
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Updated Successfully.';
            return $status;
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function getAnalysesCategoryDDWN() {
        $data = array();
        $sql_query = "SELECT category_id, category_name FROM analyses_category WHERE is_active = '1' AND is_deleted != '1' ORDER BY category_name ASC";
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

    public function delete_analyses($id, $deleted_at, $deleted_by) {
        $id = $this->mysqli->real_escape_string($id);
        $sql_query = "UPDATE analyses SET deleted_at='$deleted_at',deleted_by='$deleted_by',is_deleted='1' WHERE analysis_id = '$id'";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Deleted Successfully';
            return $status;
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function update_customer_details($customer_id, $client_id, $client_account_id, $form_data) {
        $count = 0;
        $customer_id = $this->mysqli->real_escape_string($customer_id);
        $client_id = $this->mysqli->real_escape_string($client_id);
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $client_name = !empty($form_data['client_name']) ? $this->mysqli->real_escape_string($form_data['client_name']) : '';
        $site_code = !empty($form_data['site_code']) ? $this->mysqli->real_escape_string($form_data['site_code']) : '';
        $client_code = !empty($form_data['client_code']) ? $this->mysqli->real_escape_string($form_data['client_code']) : '';
        $client_site_name = !empty($form_data['client_site_name']) ? $this->mysqli->real_escape_string($form_data['client_site_name']) : '';
        $is_headquarters = isset($form_data['is_headquarters']) ? $this->mysqli->real_escape_string($form_data['is_headquarters']) : 0;
        $address_line1 = !empty($form_data['address_line1']) ? $this->mysqli->real_escape_string($form_data['address_line1']) : '';
        $address_line2 = !empty($form_data['address_line2']) ? $this->mysqli->real_escape_string($form_data['address_line2']) : '';
        $city = !empty($form_data['city']) ? $this->mysqli->real_escape_string($form_data['city']) : '';
        $state = !empty($form_data['state']) ? $this->mysqli->real_escape_string($form_data['state']) : '';
        $zipcode = !empty($form_data['zipcode']) ? $this->mysqli->real_escape_string($form_data['zipcode']) : '';
        $phone_number = !empty($form_data['phone_number']) ? $this->mysqli->real_escape_string($form_data['phone_number']) : '';
        $contract_tat = !empty($form_data['contract_tat']) ? $this->mysqli->real_escape_string($form_data['contract_tat']) : 0;
        $is_active = isset($form_data['active']) ? $form_data['active'] : 0;
        
        if(empty($client_account_id)){
            $created_by = $_SESSION['user']->user_id;
            $client = "INSERT INTO clients (client_name, created_by) VALUES ('$client_name', '$created_by')";
            $result1 = $this->mysqli->query($client);
            $client_id = $this->mysqli->insert_id;
            $sql = "INSERT INTO client_details (client_ids, user_ids, created_by) VALUES ('$client_id', '$customer_id', '$created_by')";
            $result2 = $this->mysqli->query($sql);
            $client_account_id = $this->mysqli->insert_id;
            if ($result2 === TRUE) {
                $count++;
            }
        }

        if (!empty($client_code) && !empty($client_id)) {
            $sql_query = "UPDATE clients SET client_number = '$client_code', client_name = '$client_name', is_active = '$is_active'";
            $sql_query .= " WHERE client_id = '$client_id'";
            $result = $this->mysqli->query($sql_query);
            if ($result === TRUE) {
                $count++;
            }
        }
        if (!empty($client_account_id)) {
            $sql_query = "UPDATE client_details SET site_code='$site_code', client_site_name='$client_site_name', is_headquarters='$is_headquarters', address_line1='$address_line1', address_line2='$address_line2', city='$city', state='$state', zipcode='$zipcode', phone_number='$phone_number', contract_tat='$contract_tat', is_active='$is_active'";
            $sql_query .= " WHERE client_account_id = '$client_account_id'";
            //  echo $sql_query;
            $result = $this->mysqli->query($sql_query);
            if ($result === TRUE) {
                $count++;
            }
        }              
        return $count;
    }

    public function getClientId($id) {
        $client_id = null; // Default value
        $id = $this->mysqli->real_escape_string($id); // Sanitize input
        $sql_query = "SELECT client_ids FROM client_details WHERE user_ids = '$id'";
        $result = $this->mysqli->query($sql_query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $client_id = $row['client_ids'];
        }
        return $client_id;
    }

    public function update_user_details($id, $email, $name, $is_active) {
        $success = 0;
        $id = $this->mysqli->real_escape_string($id);
        $email = !empty($email) ? $this->mysqli->real_escape_string($email) : '';
        $name = !empty($name) ? $this->mysqli->real_escape_string($name) : '';
        $is_active = isset($is_active) ? $is_active : 0;
        $sql_query = "UPDATE users SET email='$email', user_name='$name', is_active='$is_active'";
        $sql_query .= " WHERE user_id = '$id'";
        $result = $this->mysqli->query($sql_query);
        if ($result === TRUE) {
            $success = 1;
        }
        return $success;
    }

    public function update_client_name($client_name, $is_active, $client_id) {
        $success = 0;
        $sql_query = "UPDATE clients SET client_name = '$client_name', is_active = '$is_active'";
        $sql_query .= " WHERE client_id = '$client_id'";
        $result = $this->mysqli->query($sql_query);
        if ($result === TRUE) {
            $success = 1;
        }
        return $success;
    }

    public function getAnalysesDDWN() {
        $data = array();
        $sql_query = "SELECT analysis_id, analysis_number, analysis_name, analysis_price, time_to_analyze, analysis_invoicing_description FROM analyses WHERE is_active = '1' AND is_deleted != '1' ORDER BY analysis_name ASC";
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

    public function analyses_rate_add($analysis_ids, $client_account_id, $rate, $code, $min_time, $analysis_name, $analysis_desc, $created_by) {
        /*
          $cc_code = $this->get_user_sitecode($client_account_id);
          $analysis_name = $this->analyses_name_by_id($analysis_ids);
          $analysis_description = $cc_code . $code . "-" . $analysis_name;
          $analysis_description = str_replace("'", "''", $analysis_description);
         */
        $success = 0;
        $analysis_ids = $this->mysqli->real_escape_string($analysis_ids);
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $rate = $this->mysqli->real_escape_string($rate);
        $code = $this->mysqli->real_escape_string($code);
        $min_time = $this->mysqli->real_escape_string($min_time);
        $analysis_name = $this->mysqli->real_escape_string($analysis_name);
        $analysis_desc = $this->mysqli->real_escape_string($analysis_desc);

        $sql_query = "INSERT INTO analyses_client_price_details (analysis_id, analysis_name, client_account_ids, analysis_client_price, analysis_code, analysis_invoicing_description, analysis_time, is_active, created_by) 
                    VALUES ('$analysis_ids', '$analysis_name', '$client_account_id', '$rate', '$code', '$analysis_desc', '$min_time', '1', '$created_by')";
        $result = $this->mysqli->query($sql_query);
        if ($result === TRUE) {
            $success++;
        }
        return $success;
    }

    public function client_details_by_id($id) {
        $data = array();
        $id = $this->mysqli->real_escape_string($id);
        // $sql_query = "SELECT * FROM client_details t1 JOIN users t2 ON (t1.user_ids = t2.user_id) JOIN clients t3 ON (t1.client_ids = t3.client_id) where t1.user_ids = '$id' AND t1.is_deleted != '1' AND t3.is_deleted != '1' AND t2.is_deleted != '1'";
        $sql_query = "SELECT * FROM users t2 LEFT JOIN client_details t1 ON (t2.user_id = t1.user_ids) LEFT JOIN clients t3 ON (t1.client_ids = t3.client_id) where t2.user_id = '$id' AND t2.is_deleted != '1'";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function analyses_rate_user($client_account_id) {
        $data = array();
        $sql_query = "SELECT * FROM analyses_client_price_details WHERE client_account_ids = $client_account_id AND is_active = '1' AND is_deleted != '1'";
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

    public function analyses_rate_update($analysis_client_price_id, $analysis_ids, $client_account_id, $rate, $code, $min_time, $analysis_name, $analysis_desc, $updated_by) {
        $success = 0;
        $analysis_client_price_id = $this->mysqli->real_escape_string($analysis_client_price_id);
        $analysis_ids = $this->mysqli->real_escape_string($analysis_ids);
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $rate = $this->mysqli->real_escape_string($rate);
        $code = $this->mysqli->real_escape_string($code);
        $min_time = $this->mysqli->real_escape_string($min_time);
        $analysis_name = $this->mysqli->real_escape_string($analysis_name);
        $analysis_desc = $this->mysqli->real_escape_string($analysis_desc);
        $updated_at = date("Y-m-d H:i:s");

        $sql_query = "UPDATE analyses_client_price_details SET analysis_name='$analysis_name', analysis_invoicing_description='$analysis_desc', analysis_client_price='$rate', analysis_time='$min_time', analysis_code='$code', is_active='1', updated_at='$updated_at', updated_by='$updated_by' WHERE analysis_client_price_id='$analysis_client_price_id' AND client_account_ids='$client_account_id' AND analysis_id='$analysis_ids'";
        $result = $this->mysqli->query($sql_query);
        if ($result === TRUE) {
            $success++;
        }
        return $success;
    }

    public function analyses_rate_delete($client_account_id, $deleted_by, $analysis_client_price_ids) {
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $idList = implode(',', $analysis_client_price_ids);
        $deleted_at = date("Y-m-d H:i:s");
        $sql_query = "UPDATE analyses_client_price_details SET is_deleted = '1', deleted_by = '$deleted_by', deleted_at = '$deleted_at' WHERE client_account_ids = '$client_account_id' AND analysis_client_price_id IN ($idList)";
        $result = $this->mysqli->query($sql_query);
        if ($result === TRUE) {
            $success = 1;
        }
        return $success;
    }

    public function get_max_discount_for_customer($id) {
        $data = array();
        $sql_query = "SELECT MAX(maximum_volume) as max_value 
					  FROM monthly_volume_discount 
					  WHERE client_account_ids = '$id'
					  AND is_active = '1' AND is_deleted = '0' AND valid_from >= DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 MONTH), '%Y-%m-01') AND valid_from < DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 2 MONTH), '%Y-%m-01')";
        $result = $this->mysqli->query($sql_query);
        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
        }
        return $data;
    }

    public function get_monthly_discounts_for_customer($id) {
        $data = array();
        //   $sql_query = "SELECT * FROM monthly_volume_discount WHERE client_account_ids = '$id' AND is_deleted = '0' AND valid_from >= DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 MONTH), '%Y-%m-01') AND valid_from < DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 2 MONTH), '%Y-%m-01')";
        $sql_query = "SELECT * FROM monthly_volume_discount WHERE client_account_ids = '$id' AND is_deleted = '0'";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['results'][] = $row;
            }
        }
        return $data;
    }

    public function monthly_discount_add($client_account_id, $minimum_value, $maximum_value, $percentage, $created_by, $valid_from, $valid_to) {
        $success = 0;
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $minimum_value = $this->mysqli->real_escape_string($minimum_value);
        $maximum_value = $this->mysqli->real_escape_string($maximum_value);
        $percentage = $this->mysqli->real_escape_string($percentage);
        $is_active = 1;
        $sql_query = "INSERT INTO monthly_volume_discount(client_account_ids, minimum_volume, maximum_volume, discount_price, valid_from, valid_to, is_active, created_by) "
                . "VALUES ('$client_account_id','$minimum_value','$maximum_value','$percentage','$valid_from','$valid_to','$is_active','$created_by')";
        $result = $this->mysqli->query($sql_query);
        if ($result === TRUE) {
            $success++;
        }
        return $success;
    }

    public function monthly_discount_update($discount_id, $client_account_id, $minimum_value, $maximum_value, $percentage) {
        $success = 0;
        $discount_id = $this->mysqli->real_escape_string($discount_id);
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $minimum_value = $this->mysqli->real_escape_string($minimum_value);
        $maximum_value = $this->mysqli->real_escape_string($maximum_value);
        $percentage = $this->mysqli->real_escape_string($percentage);
        $is_active = 1;
        $sql_query = "UPDATE monthly_volume_discount SET minimum_volume='$minimum_value',maximum_volume='$maximum_value',discount_price='$percentage',is_active='$is_active' WHERE discount_id='$discount_id' AND client_account_ids='$client_account_id'";
        $result = $this->mysqli->query($sql_query);
        if ($result === TRUE) {
            $success++;
        }
        return $success;
    }

    public function monthly_discount_delete($client_account_id, $discount_ids) {
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $idList = implode(',', $discount_ids);
        $sql_query = "UPDATE monthly_volume_discount SET is_deleted = '1' WHERE client_account_ids = '$client_account_id' AND discount_id IN ($idList)";
        $result = $this->mysqli->query($sql_query);
        if ($result === TRUE) {
            $success = 1;
        }
        return $success;
    }

    public function get_analyses_subscriptions($client_account_id) {
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $data = array();
        $sql_query = "SELECT t2.subscription_ids, t1.subscription_price, t2.subscription_content_id, t2.analysis_client_price_ids, t2.subscription_volume, t3.analysis_name FROM subscription t1 INNER JOIN subscription_contents t2 ON (t1.subscription_id = t2.subscription_ids) INNER JOIN analyses_client_price_details t3 ON (t2.analysis_client_price_ids = t3.analysis_client_price_id) WHERE t1.client_account_ids = '$client_account_id' AND t2.is_deleted = '0' AND t1.is_deleted = '0'";
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

    public function subscription_by_id($client_account_id) {
        $subscription_id = '';
        $sql_query = "SELECT subscription_id FROM subscription WHERE client_account_ids = '$client_account_id' AND is_active = '1' AND is_deleted = '0'";
        $result = $this->mysqli->query($sql_query);
        if ($result && $row = $result->fetch_assoc()) {
            $subscription_id = $row['subscription_id'];
        }
        return $subscription_id;
    }

    public function subscription_add($subscription_id, $analysis_client_price_id, $client_account_id, $count, $subscription_total, $created_by) {
        $success = 0;
        $analysis_client_price_id = $this->mysqli->real_escape_string($analysis_client_price_id);
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $count = $this->mysqli->real_escape_string($count);
        $subscription_total = $this->mysqli->real_escape_string($subscription_total);

        if (empty($subscription_id)) {
            $subscription_id = $this->subscription_by_id($client_account_id);
            if (empty($subscription_id)) {
                $sql = "INSERT INTO subscription (client_account_ids, subscription_price, is_active, created_by) 
				  VALUES ('$client_account_id', '$subscription_total', '1', '$created_by')";
                $res = $this->mysqli->query($sql);
                $subscription_id = $this->mysqli->insert_id;
            }
        }

        if (!empty($subscription_id)) {
            $sql_query = "INSERT INTO subscription_contents(subscription_ids, analysis_client_price_ids, subscription_volume, is_active, created_by) 
				  VALUES ('$subscription_id', '$analysis_client_price_id', '$count', '1', '$created_by')";
            $result = $this->mysqli->query($sql_query);
            if ($result === TRUE) {
                $success++;
            }
        }
        return $success;
    }

    public function subscription_update($subscription_content_id, $subscription_id, $analysis_client_price_id, $client_account_id, $count, $subscription_total) {
        $success = 0;
        $subscription_content_id = $this->mysqli->real_escape_string($subscription_content_id);
        $subscription_id = $this->mysqli->real_escape_string($subscription_id);
        $analysis_client_price_id = $this->mysqli->real_escape_string($analysis_client_price_id);
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $count = $this->mysqli->real_escape_string($count);
        $subscription_total = $this->mysqli->real_escape_string($subscription_total);
        $sql = "UPDATE subscription SET subscription_price='$subscription_total' WHERE subscription_id = '$subscription_id' AND client_account_ids = '$client_account_id'";
        $res = $this->mysqli->query($sql);
        if ($res === TRUE) {
            $success++;
        }
        $sql_query = "UPDATE subscription_contents SET subscription_volume = '$count' WHERE subscription_content_id = '$subscription_content_id' AND subscription_ids = '$subscription_id' AND analysis_client_price_ids = '$analysis_client_price_id'";
        $result = $this->mysqli->query($sql_query);
        if ($result === TRUE) {
            $success++;
        }
        return $success;
    }

    public function delete_subscription_details($client_account_id, $subscription_content_ids) {
        $success = 0;
        $idList = implode(',', $subscription_content_ids);
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $sql_query = "UPDATE subscription_contents SET is_active = '0', is_deleted = '1' WHERE subscription_content_id IN ($idList)";
        $result = $this->mysqli->query($sql_query);
        if ($result === TRUE) {
            $subscriptions = $this->get_analyses_subscriptions($client_account_id);
            if (empty($subscriptions)) {
                $sql = "UPDATE subscription SET is_active = '0', is_deleted = '1' WHERE client_account_ids = '$client_account_id'";
                $res = $this->mysqli->query($sql);
            }
            $success++;
        }
        return $success;
    }

    public function get_maintenance_fees_for_customer($client_account_id) {
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $data = array();
        $sql_query = "SELECT maintenance_fees_id, maintenance_fee_type, maintenance_fee_amount FROM maintenance_fees WHERE client_account_ids = '$client_account_id' AND is_active = '1' AND is_deleted = '0'";
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

    public function save_maintenance_fee_details($client_account_id, $maintenance_fee_type, $maintenance_fee_amount, $created_by) {
        $success = 0;
        $client_account_id = $this->mysqli->real_escape_string($client_account_id);
        $maintenance_fee_type = $this->mysqli->real_escape_string($maintenance_fee_type);
        $maintenance_fee_amount = $this->mysqli->real_escape_string($maintenance_fee_amount);
        $sql = "UPDATE maintenance_fees SET is_active = '0', is_deleted = '1' WHERE client_account_ids = '$client_account_id'";
        $res = $this->mysqli->query($sql);
        $sql_query = "INSERT INTO maintenance_fees(client_account_ids, maintenance_fee_type, maintenance_fee_amount, is_active, created_by) VALUES ('$client_account_id','$maintenance_fee_type','$maintenance_fee_amount','1','$created_by')";
        $result = $this->mysqli->query($sql_query);
        if ($result === TRUE) {
            $success = 1;
        }
        return $success;
    }

     public function get_user_by_id($id) {
        $data = [];

           $sql_query = "SELECT user_name FROM users WHERE user_id = ?";
           $stmt = $this->mysqli->prepare($sql_query);

             if ($stmt) {
                   $stmt->bind_param("i", $id);
                   $stmt->execute();
                   $stmt->bind_result($user_name);
    
            if ($stmt->fetch()) {
               $data['name'] = $user_name;
             }
    
           $stmt->close();
         }

            return $data['name'] ?? '';
    }



   


     public function get_client_details_by_id($id) {
      

          $data = array();
          $dataclient = array();
        $sql_query = "SELECT client_ids FROM client_details WHERE client_account_id = $id";

        //print_r($sql_query); die;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        $client_id = $data['client_ids'];



        $sql_query_client = "SELECT client_number,client_name FROM clients WHERE client_id = $client_id";

        //print_r($sql_query); die;

        $result_client = $this->mysqli->query($sql_query_client);
        if ($result_client->num_rows > 0) {
            while ($row_client = $result_client->fetch_assoc()) {
                $dataclient = $row_client;
            }
        }
         return $dataclient;

    }



     public function get_status_details($status) {
        $data = array();
        $sql_query = "SELECT status FROM  analysis_status  WHERE status_id = $status";

        //print_r($sql_query); die;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data['status'];
    }



     public function get_analysis_details_by_id($id) {
      

          $data = array();
          $dataclient = array();
        $sql_query = "SELECT analysis_client_price_ids FROM analyses_performed  WHERE analysis_performed_id  = $id";

        //print_r($sql_query); die;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        $analysis_client_price_ids = $data['analysis_client_price_ids'];



        $sql_query_client = "SELECT analysis_id,analysis_name,analysis_code FROM analyses_client_price_details  WHERE analysis_client_price_id = $analysis_client_price_ids";

        //print_r($sql_query); die;

        $result_client = $this->mysqli->query($sql_query_client);
        if ($result_client->num_rows > 0) {
            while ($row_client = $result_client->fetch_assoc()) {
                $dataclient = $row_client;
            }
        }
         return $dataclient;

    }


     public function get_analysis_number_by_id($item_number) {
        $data = array();
        $sql_query = "SELECT analysis_number FROM  analyses  WHERE analysis_id = $item_number";

        //print_r($sql_query); die;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data['analysis_number'];
    }

}
