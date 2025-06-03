<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Studiesmodel extends CI_Model{
	private $customerId        =   "";/************** Login ID   **************/
    private $customerName      =   "";/************** Login Name **************/
    private $customerRole      =   "";/************** Login Role **************/
    private $masterCustomerID  =   "";/************** Pia User ID **************/
    /*----------------------- MAIN CONSTRUCT ---------------------------------------
	@PASSING ATTRIBUTES ARE      :  CONSTRUCT FUNCTION 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	------------------------------------------------------------------------------*/
	public function __construct() {
        parent::__construct();
        $this->customerId       =   $this->session->userdata('customerId');
        $this->customerName     =   $this->session->userdata('customerName');
        $this->customerRole     =   $this->session->userdata('customerRole');
        $this->masterCustomerID =   $this->session->userdata('masterCustomerID');
    }
    /*----------------------- GET ALL USERS BY GROUP  --------------------------------
        @CREATE DATE                 :  12-08-2019 
        $RETURN                      :  ARRAY 
    ------------------------------------------------------------------------------*/
    public function getAllUsersByGroupID($group_id = 0) {
        $data = array();
        if (!empty($group_id)) {
            $data   =   $this->db->select('id, name')
                                ->from('users')
                                ->where('group_id', $group_id)
                                ->get()->result_array();

        }

        return $data;       
    }
    /*----------------------- GET ALL STUDIES COUNT  --------------------------------
        @CREATE DATE                 :  13-08-2019 
        $RETURN                      :  INT 
    ------------------------------------------------------------------------------*/
    public function workSheetStudiesListAllCount() {
        $this->db->select('Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id')->from('Clario');
        $this->db->join('users', 'Clario.assignee = users.id', 'left');
        if (!empty($this->masterCustomerID)) {
            $this->db->where('Clario.customer', $this->masterCustomerID);
        }
        $this->db->order_by('Clario.created', 'DESC');
        $data = $this->db->get();
        return $rowcount = $data->num_rows();
    }
    /*----------------------- GET ALL STUDIES  --------------------------------
        @CREATE DATE                 :  13-08-2019
        @RETURN                      :  ARRAY 
    ------------------------------------------------------------------------------*/
    public function workSheetStudiesListAll($assignee = null, $is_day = null, $request = array(), $col = array(), $is_second = null) {

        $this->db->select('Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id')->from('Clario');
        $this->db->join('users', 'Clario.assignee = users.id', 'left');
        if (!empty($assignee)) {
            $this->db->where('Clario.assignee', $assignee);
        }
        if (!empty($is_day)) {
            $this->db->where('TIMESTAMPDIFF(DAY,Clario.created,NOW()) <', $is_day);
        }
        if (!empty($is_second)) {
            if ($is_second == 1) {
                $this->db->where('Clario.review_user_id !=', '');
            } else {
                $this->db->where('Clario.review_user_id', '');
            }
        }
        if (!empty($request['search']['value'])) {
            $this->db->like('Clario.id', $request['search']['value']);
            $this->db->or_like('Clario.created', $request['search']['value']);
            $this->db->or_like('Clario.accession', $request['search']['value']);
            $this->db->or_like('Clario.patient_name', $request['search']['value']);
            $this->db->or_like('Clario.mrn', $request['search']['value']);
            $this->db->or_like('Clario.tat', $request['search']['value']);
            $this->db->or_like('users.name', $request['search']['value']);
            $this->db->or_like('Clario.webhook_customer', $request['search']['value']);
            $this->db->or_like('Clario.status', $request['search']['value']);
        }
        if (!empty($this->masterCustomerID)) {
            $this->db->where('Clario.customer', $this->masterCustomerID);
        }
        
        $this->db->order_by($col[$request['order'][0]['column']], $request['order'][0]['dir']);
        $this->db->limit($request['length'], $request['start']);
        $data = $this->db->get()->result_array();
        return $data;
    }
    /*----------------------- GET USER NAME BY ID  --------------------------------
        @CREATE DATE                 :  14-08-2019
        @PARAM                       :  INT
        @RETURN                      :  STRING 
    ------------------------------------------------------------------------------*/
    public function getNameById($id = '') {
        $name = '';
        if (!empty($id)) {
            $data   =   $this->db->select('name')
                                ->from('users')
                                ->where('id', $id)
                                ->get()->row_array();

            $name = (count($data > 0)) ? $data['name'] : '';
        }

        return $name;
    }

}