<?php

class dashboardmodel extends Model {

    public $mysqli;

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- MAIN CONSTRUCT ---------------------------------------
      @FUNCTION DATE              :  28-12-2018
      ------------------------------------------------------------------------------ */

    public function __construct($con) {
        $this->mysqli = $con;
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- ERROR DISPLAY FUNCTION ------------------------------
      @FUNCTION DATE              :  28-12-2018
      ------------------------------------------------------------------------------ */

    public function debug($array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- GET COUNT NOT ASSIGNED FUNCTION ---------------------
      @FUNCTION DATE              :  28-12-2018
      ------------------------------------------------------------------------------ */

    public function getcount($table, $where = '') {
        $sql = "SELECT * FROM `$table` $where";
        $result = $this->mysqli->query($sql);
        return mysqli_num_rows($result);
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- GET COUNT NOT ASSIGNED FUNCTION BY DATE -------------
      @FUNCTION DATE              :  28-12-2018
      ------------------------------------------------------------------------------ */

    public function getcountbydate($table, $from, $to) {
        $sql = "SELECT * FROM `$table` WHERE ( `created` BETWEEN '$from' AND '$to') AND assignee = 0";
        $result = $this->mysqli->query($sql);
        return mysqli_num_rows($result);
    }

    public function getcountbydateNew($table, $from, $to) {
        $sql = "SELECT COUNT(`id`) AS cnt FROM `$table` WHERE ( `created` BETWEEN '$from' AND '$to') AND assignee = 0";
        $result = $this->mysqli->query($sql);
        $row = mysqli_fetch_assoc($result);
        return $row['cnt'];
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- GET LAST STUDY IN DB --------------------------------
      @FUNCTION DATE              :  28-12-2018
      ------------------------------------------------------------------------------ */

    public function getlaststudy() {
        $data = array();
        $sql = "SELECT id,patient_name,webhook_customer,webhook_description FROM Clario ORDER BY id DESC LIMIT 5";
        $result = $this->mysqli->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- GET LAST STUDY IN DB BY DATE ------------------------
      @FUNCTION DATE              :  28-12-2018
      ------------------------------------------------------------------------------ */

    public function getlaststudybydate($from, $to) {
        $data = array();
        $sql = "SELECT id,patient_name,webhook_customer,webhook_description FROM Clario ( `created` BETWEEN '$from' AND '$to') ORDER BY id DESC LIMIT 5";
        $result = $this->mysqli->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- GET LAST CUSTOMERS IN DB ----------------------------
      @FUNCTION DATE              :  28-12-2018
      ------------------------------------------------------------------------------ */

    public function getnewuser() {
        $data = array();

        $sql = "SELECT user_id,email,user_name FROM users WHERE user_type_ids=5 ORDER BY user_id DESC LIMIT 5";

        $result = $this->mysqli->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- GET LAST CUSTOMERS IN DB BY DATE --------------------
      @FUNCTION DATE              :  28-12-2018
      ------------------------------------------------------------------------------ */

    public function getnewuserbydate($from, $to) {
        $data = array();
        $sql = "SELECT id,email,name,user_meta FROM users ( `created` BETWEEN '$from' AND '$to') ORDER BY id DESC LIMIT 5";
        $result = $this->mysqli->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    /*     * ****************************** Actual time vs. Expected time ***************************************** */
    /* ----------------------- Actual time vs. Expected time of Analyst --------------------
      @FUNCTION DATE              :  17-01-2019
      ------------------------------------------------------------------------------ */

    public function getATvsEAT() {
        $data = array();
        // $sql = "SELECT t1.id, t1.date,t1.analyst_hours, t1.expected_time, t2.name as analyst, t3.name as customer FROM worksheets t1 LEFT JOIN users t2 ON t1.analyst = t2.id LEFT JOIN users t3 ON t1.customer_id = t3.id WHERE t1.status='Completed' ORDER BY t1.id DESC LIMIT 5";
        $sql = "SELECT worksheets.id, worksheets.date, Clario.mrn, Clario.accession, worksheets.analyst_hours, worksheets.expected_time FROM Clario JOIN worksheets ON Clario.id = worksheets.clario_id WHERE worksheets.status='Completed' ORDER BY worksheets.id DESC LIMIT 5";
        $result = $this->mysqli->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    /* ----------------------- Actual time vs. Expected time for Different Analyst --------------------
      @FUNCTION DATE              :  18-01-2019
      ------------------------------------------------------------------------------ */

    public function getWorstAnalystChart() {
        $data = array();
        $sql = "SELECT worksheets.id, (worksheets.analyst_hours-worksheets.expected_time) AS difference, worksheets.analyst_hours, worksheets.expected_time, users.name as analyst FROM worksheets LEFT JOIN users ON worksheets.analyst = users.id WHERE worksheets.status='Completed' AND MONTH(worksheets.date) = MONTH(CURRENT_DATE()) AND YEAR(worksheets.date) = YEAR(CURRENT_DATE()) AND worksheets.expected_time > 0 AND worksheets.analyst_hours > worksheets.expected_time GROUP BY worksheets.analyst ORDER BY difference DESC LIMIT 5";
        $result = $this->mysqli->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

}
