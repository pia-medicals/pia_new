<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Monthlymodel extends CI_Model{
    private $customerId        =   "";/************** Login ID   **************/
    private $customerName      =   "";/************** Login Name **************/
    private $customerRole      =   "";/************** Login Role **************/
/******************************** RC ******************************************/
/*----------------------- MAIN CONSTRUCT ---------------------------------------
	@PASSING ATTRIBUTES ARE      :  CONSTRUCT FUNCTION 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/
    public function __construct() {
        parent::__construct();
        $this->customerId    =   $this->session->userdata('customerId');
        $this->customerName  =   $this->session->userdata('customerName');
        $this->customerRole  =   $this->session->userdata('customerRole');
    }
    public function userdata(){
        $this->db->select('name,id');
        $this->db->from('users');
        $this->db->where('group_id',5);
        $sql    =   $this->db->get();
        return $sql->result_array();
    }
    public function getreport(){
        $data       =   array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE  worksheets.`status`='Completed'
                        AND worksheets.date between '2018-07-01 00:00:00' and '2018-07-31 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2018-07']        =   $this->db->query($query)->result_array();
        
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE  worksheets.`status`='Completed'
                        AND worksheets.date between '2018-08-01 00:00:00' and '2018-08-31 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2018-08']        =   $this->db->query($query)->result_array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE  worksheets.`status`='Completed'
                        AND worksheets.date between '2018-09-01 00:00:00' and '2018-09-30 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2018-09']        =   $this->db->query($query)->result_array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE  worksheets.`status`='Completed'
                        AND worksheets.date between '2018-10-01 00:00:00' and '2018-10-31 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2018-10']        =   $this->db->query($query)->result_array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE  worksheets.`status`='Completed'
                        AND worksheets.date between '2018-11-01 00:00:00' and '2018-11-30 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2018-11']        =   $this->db->query($query)->result_array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE  worksheets.`status`='Completed'
                        AND worksheets.date between '2018-12-01 00:00:00' and '2018-12-31 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2018-12']        =   $this->db->query($query)->result_array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE  worksheets.`status`='Completed'
                        AND worksheets.date between '2019-01-01 00:00:00' and '2019-01-31 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2019-01']        =   $this->db->query($query)->result_array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE  worksheets.`status`='Completed'
                        AND worksheets.date between '2019-02-01 00:00:00' and '2019-02-28 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2019-02']        =   $this->db->query($query)->result_array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE  worksheets.`status`='Completed'
                        AND worksheets.date between '2019-03-01 00:00:00' and '2019-03-31 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2019-03']        =   $this->db->query($query)->result_array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE  worksheets.`status`='Completed'
                        AND worksheets.date between '2019-04-01 00:00:00' and '2019-04-30 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2019-04']        =   $this->db->query($query)->result_array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE  worksheets.`status`='Completed'
                        AND worksheets.date between '2019-05-01 00:00:00' and '2019-05-31 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2019-05']        =   $this->db->query($query)->result_array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE worksheets.`status`='Completed'
                        AND worksheets.date between '2019-06-01 00:00:00' and '2019-06-30 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2019-06']        =   $this->db->query($query)->result_array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE  worksheets.`status`='Completed'
                        AND worksheets.date between '2018-07-01 00:00:00' and '2019-07-31 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2019-07']        =   $this->db->query($query)->result_array();
        $query    =   "SELECT count(worksheets.analyst) AS Numcount,worksheets.analyses_performed,clario.customer,users.`name`
                        FROM clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE worksheets.`status`='Completed'
                        AND worksheets.date between '2019-08-01 00:00:00' and '2019-08-31 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`";
        $data['2019-08']        =   $this->db->query($query)->result_array();
        return $data;
    }      
    
    public function newfunction(){
        //$customer   =   $this->userdata();
        //foreach ($customer as $value){
        $query    =   "SELECT
                        Count(worksheets.analyst) AS Numcount,
                        worksheets.analyses_performed,
                        clario.customer,
                        users.`name`,
                        worksheets.date
                        FROM
                        clario
                        INNER JOIN users ON clario.customer = users.id
                        INNER JOIN worksheets ON worksheets.clario_id = clario.id
                        WHERE worksheets.`status`='Completed' 
                        AND worksheets.date between '2019-01-01 00:00:00' and '2019-08-31 23:59:00'
                        GROUP BY worksheets.analyses_performed,clario.customer,users.`name`
                        ORDER BY clario.customer";
        //$data[$value['name']]       =   $this->db->query($query)->result_array();
        //}
        return $this->db->query($query)->result_array();;
    }
}
