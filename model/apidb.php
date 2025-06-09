<?php

class apidb extends Model {

    public $mysqli;

    function __construct($con) {
        $this->mysqli = $con;
    }

  /* public function insertclario($data = array()) {
        if (!empty($data)) {
            extract($data);

            //$name = preg_replace('/[^^]/', ' ', $name);
            $name = $this->mysqli->real_escape_string($name);
            $institution = $this->mysqli->real_escape_string($institution);
            $customer = $this->mysqli->real_escape_string($customer);
            $description = $this->mysqli->real_escape_string($description);
            $mrn = $this->mysqli->real_escape_string($mrn);
            $accession = $this->mysqli->real_escape_string($accession);

            $name = str_replace("^", " ", $name);
            $alldata = $this->mysqli->real_escape_string($alldata);
            $allsqlquery = "INSERT INTO `webhook_temp` (`postdata`) VALUES ('$alldata')";
            $allresult = $this->mysqli->query($allsqlquery);
            if ($allresult === TRUE) {
                // Get the last inserted ID from webhook_temp
                $webhook_temp_ids = $this->mysqli->insert_id;
                $status['connected'] = TRUE;
                $status['data'] = " Data From Webhook Added to Webhook temp Table";
            }

            $sql_query = "INSERT INTO `dicom_webhook_details` (`webhook_temp_ids`, `accession`, `mrn`,`patient_name`, `exam_date_time`, `institution_name`,`webhook_customer`,`webhook_description`) VALUES ('$webhook_temp_ids','$accession','$mrn','$name','$exam_date','$institution','$customer','$description')";
            $result = $this->mysqli->query($sql_query);

            $empty = "";
            $date_update = null;
            $status['data'] = " Data From Webhook Added to Webhook Table";
            
            $date = date('Y-m-d H:i:s');
            // Check Duplication

            $check_query = "SELECT  * from `studies` where  accession ='$accession' and mrn='$mrn'";
            $check = $this->mysqli->query($check_query);
            if ($check->num_rows > 0) {

                $status = array();
                $status['query'] = $check_query;
                $status['status'] = false;
                $status['data'] = "Customer details already exists in studies Table";
                return $status;
            } else {
                $check_cus = "SELECT s.client_account_ids AS customer FROM studies s JOIN dicom_webhook_details dwd ON s.dicom_webhook_ids = dwd.dicom_webhook_id WHERE dwd.webhook_customer = ? ORDER BY s.studies_id DESC LIMIT 1";
                $stmt = $this->mysqli->prepare($check_cus);
                $stmt->bind_param('s', $customer);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    // Fetch matching record
                    $rowone = $result->fetch_assoc();
                    $acccustomer = $rowone['customer'];
                } else {
                    // Default value if no match
                    $acccustomer = null;
                }
                $stmt->close();

                // Insert into studies table
                $sql_query2 = "INSERT INTO studies 
                    (accession, mrn, patient_name, client_site_name, analyst_id, second_analyst_id, comment, 
                     second_comment, Error_Finding, second_check_date, dicom_webhook_ids, expected_time, 
                     completed_time, analyst_hours, status_ids, actual_tat, total_price) 
                    VALUES (?, ?, ?, ?, NULL, NULL, '', '', '', '', ?, '', '', '', 3, '', '')";

                $stmt2 = $this->mysqli->prepare($sql_query2);

                // Bind parameters (handling $acccustomer null case)
                $stmt2->bind_param('sssss', $accession, $mrn, $name, $institution, $acccustomer);

                if ($stmt2->execute()) {
                    echo "Record inserted successfully into studies table.";
                } else {
                    echo "Error inserting into studies table: " . $stmt2->error;
                }

                $stmt2->close();
            }
        }
    }  

*/

    public function insertclario($data = array()) {
        if (!empty($data)) {
            extract($data);

            //$name = preg_replace('/[^^]/', ' ', $name);
            $name = $this->mysqli->real_escape_string($name);
            $institution = $this->mysqli->real_escape_string($institution);
            $customer = $this->mysqli->real_escape_string($customer);
            $description = $this->mysqli->real_escape_string($description);
            $mrn = $this->mysqli->real_escape_string($mrn);
            $accession = $this->mysqli->real_escape_string($accession);

            $name = str_replace("^", " ", $name);

           
            $alldata = $this->mysqli->real_escape_string($alldata);
            $allsqlquery = "INSERT INTO `webhook_temp` (`postdata`) VALUES ('$alldata')";
            $allresult = $this->mysqli->query($allsqlquery);

            if ($allresult === TRUE) {
                // Get the last inserted ID from webhook_temp
                $webhook_temp_ids = $this->mysqli->insert_id;
                $status['connected'] = TRUE;
                $status['data'] = " Data From Webhook Added to Webhook temp Table";
            }

            $sql_query = "INSERT INTO `dicom_webhook_details` (`webhook_temp_ids`, `accession`, `mrn`,`patient_name`, `exam_date_time`, `institution_name`,`webhook_customer`,`webhook_description`) VALUES ('$webhook_temp_ids','$accession','$mrn','$name','$exam_date','$institution','$customer','$description')";

            
            $result = $this->mysqli->query($sql_query);
            $inserted_id = $this->mysqli->insert_id;


             $empty = "";
            $date_update = null;
            $status['data'] = " Data From Webhook Added to Webhook Table";
            
            $date = date('Y-m-d H:i:s');
            // Check Duplication

            $check_query = "SELECT  * from `studies` where  accession ='$accession' and mrn='$mrn'";
            $check = $this->mysqli->query($check_query);
            if ($check->num_rows > 0) {

                $status = array();
                $status['query'] = $check_query;
                $status['status'] = false;
                $status['data'] = "Customer details already exists in studies Table";
            } else {
    //  date_default_timezone_set('America/Los_Angeles');
        //$lastdate = date('Y-d-m H:i:s');

        //$sql_query2 = "INSERT INTO Clario VALUES (NULL,'$accession','$mrn',0,0,'$empty',0,'$name','$empty','$date','$exam_time','$exam_date','$empty','$empty','$institution','$empty',0,0,'$customer','$description','$date',null)";
        // print_r($sql_query2);
        //$check_cus="SELECT customer from Clario where webhook_customer ='$customer' ORDER BY id DESC LIMIT 1";
        $check_cus="SELECT * from auto_assign_mapping where webhook_customer ='$customer' ORDER BY asid ASC LIMIT 1";
     $check_one = $this->mysqli->query($check_cus);
     
    // echo $check_one[0];
    if ($check_one->num_rows > 0) {
        
         while ($rowone = $check_one->fetch_assoc()) {
                $cus = $rowone;
            }
             //$acccustomer = $cus['customer'];
             $rid = $cus['status'];
               


              




              if($rid == 0){
                 $acccustomer = $cus['cid'];
               }

               if($rid == 1){
               ///  $acccustomer = $cus['cid'];

                 
                      if($customer == 'AR Heart')
                      {

                          if (stripos($institution, "Encore") !== false)
                              {
                               $acccustomer = 149;
                             } 
                          else 
                             {
                               $acccustomer = 148;
                            }
                       }






                             if($customer == 'Maine Medical Center')
                      {

                          if (stripos($institution, "Penobscot") !== false)
                              {
                               $acccustomer = 162;
                             } 
                          else 
                             {
                               $acccustomer = 161;
                            }
                       }




                         if($customer == 'Methodist Health Systems')
                      {

                          if (stripos($institution, "Southlake") !== false)
                              {
                               $acccustomer = 165;
                             } 
                          else 
                             {
                               $acccustomer = 164;
                            }
                       }


                        if($customer == 'Rush University Medical Center')
                      {

                          if (stripos($institution, "Outpatient") !== false)
                              {
                               $acccustomer = 181;
                             } 
                          else 
                             {
                               $acccustomer = 182;
                            }
                       }


                        if($customer == 'University of Louisville')
                      {

                          if (stripos($institution, "East") !== false)
                              {
                               $acccustomer = 200;
                             } 
                          else 
                             {
                               $acccustomer = 201;
                            }
                       }


               }







               if($rid == 2){
                // $acccustomer = $cus['cid'];

                 
                      if($customer == 'Texas Health Resources')
                      {

                          if (strpos($accession, "L") !== false)
                           {
                            $acccustomer = 210;
                           } 
                           
                            else if((strpos($accession, "DC") !== false))
                               {
                             $acccustomer = 190;
                               }

                              else if((strpos($accession, "DM") !== false))
                               {
                             $acccustomer = 191;
                               }

                               else if((strpos($accession, "FM") !== false))
                               {
                             $acccustomer = 192;
                               }

                                else if((strpos($accession, "H") !== false))
                               {
                             $acccustomer = 193;
                               }

                                else if((strpos($accession, "FR") !== false))
                               {
                             $acccustomer = 194;
                               }

                                else if((strpos($accession, "P") !== false))
                               {
                             $acccustomer = 195;
                               }
                               else if((strpos($accession, "RW") !== false))
                               {
                             $acccustomer = 196;
                               }
                               else if((strpos($accession, "M") !== false))
                               {
                             $acccustomer = 211;
                               }
                                else 
                               {
                             $acccustomer = 0;
                               }
                       }


               }






                if($rid == 3){
                // $acccustomer = $cus['cid'];

                 
                      if($customer == 'University of Texas Medical Branch')
                      {

                          if(((stripos($institution, "Galveston") !== false) OR (stripos($institution, "Jennie Sealy") !== false)) AND ((strpos($description, "CT ") !== false) OR (stripos($description, "CTA") !== false)))
                           {
                            $acccustomer = 206;
                           } 

                           else if((stripos($institution, "Angleton") !== false)   AND ((strpos($description, "CT ") !== false) OR (stripos($description, "CTA") !== false)))
                           {
                            $acccustomer = 203;
                           } 
                            else if((stripos($institution, "Angleton") !== false)   AND (strpos($description, "MR ") !== false))
                           {
                            $acccustomer = 204;
                           } 

                            else if(((stripos($institution, "CLC") !== false) OR (stripos($institution, "Clear Lake") !== false) OR (stripos($institution, "Victory Lakes") !== false)) AND ((strpos($description, "CT ") !== false) OR (stripos($description, "CTA") !== false)))
                           {
                            $acccustomer = 227;
                           } 


                             else if(((stripos($institution, "CLC") !== false) OR (stripos($institution, "Clear Lake") !== false) OR (stripos($institution, "Victory Lakes") !== false)) AND (strpos($description, "MR ") !== false))
                           {
                            $acccustomer = 178;
                           } 



                           else if(((stripos($institution, "Galveston") !== false) OR (stripos($institution, "Jennie Sealy") !== false)) AND (strpos($description, "MR ") !== false))
                           {
                            $acccustomer = 207;
                           } 

                           else if(((stripos($institution, "LCC") !== false) OR (stripos($institution, "League City") !== false)) AND ((strpos($description, "CT ") !== false) OR (stripos($description, "CTA") !== false)))
                           {
                            $acccustomer = 208;
                           } 


                           else if(((stripos($institution, "LCC") !== false) OR (stripos($institution, "League City") !== false)) AND (strpos($description, "MR ") !== false))
                           {
                            $acccustomer = 209;
                           } 

                           else if((stripos($institution, "TDCJ") !== false))
                               {
                             $acccustomer = 210;
                               }

                            else{
                               $acccustomer = 0;
                            }
                           
                       }


               }


         /// echo $acccustomer;
          
          $created_at = 
             $sql_query2 = "INSERT INTO studies(`client_account_ids`, `accession`, `mrn`, `patient_name`, `client_site_name`, `analyst_id`, `second_analyst_id`, `comment`, `second_comment`, `Error_Finding`, `second_check_date`, `dicom_webhook_ids`, `expected_time`, `completed_time`, `analyst_hours`, `status_ids`, `actual_tat`, `total_price`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_deleted`) VALUES ($acccustomer,'$accession','$mrn','$name','$institution',NULL,NULL,NULL,NULL,NULL,NULL,$inserted_id
,NULL,NULL,NULL,8,NULL,NULL,NULL,'$date',NULL,NULL,1)";  

           
         
        }
    else{  

      $acccustomer = 0;

       $sql_query2 = "INSERT INTO studies(`client_account_ids`, `accession`, `mrn`, `patient_name`, `client_site_name`, `analyst_id`, `second_analyst_id`, `comment`, `second_comment`, `Error_Finding`, `second_check_date`, `dicom_webhook_ids`, `expected_time`, `completed_time`, `analyst_hours`, `status_ids`, `actual_tat`, `total_price`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_deleted`) VALUES ($acccustomer,'$accession','$mrn','$name','$institution',NULL,NULL,NULL,NULL,NULL,NULL,$inserted_id
,NULL,NULL,NULL,8,NULL,NULL,NULL,'$date' ,NULL,NULL,1)";  
      
    }
        $result2 = $this->mysqli->query($sql_query2);
       //   print_r($sql_query2);
      //$status = array();
      //$status['query']=$sql_query2;
      if ($result2 === TRUE) {
        $status['status'] = true;
        $status['data'] = 'Dicom Details imported successfully into CLario Table';
          return  $status;
      } else {
        $status['status'] = false;
        $status['data'] = "Error:".$this->mysqli->error;
          return  $status;
      }
    }


  }


}
}
?>