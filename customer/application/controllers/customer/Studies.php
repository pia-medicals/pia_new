<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Studies extends MY_Controller{
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
        
        $this->load->model('customer/studiesmodel', 'dbstudies');
    }
    /*----------------------- CUSTOMER ALL STUDY DETAILS -----------------------------
	@CREATE DATE                 :  12-08-2019	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
    ------------------------------------------------------------------------------*/ 
    public function all() {
    	$menu['user']       =   $this->logindata();
    	$data['asignee'] 	= 	$this->dbstudies->getAllUsersByGroupID(3);
    	//$data['dicom_studies_list']	=	$this->dbstudies->workSheetStudiesListAll();
    	$this->load->view('admin/tpl/head-tpl');
        $this->load->view('admin/tpl/menu-tpl',$menu);
        $this->load->view('admin/studies/all',$data);
        $this->load->view('admin/tpl/footer-tpl');
        $this->load->view('admin/studies/scripts/all');
    	
    }
    /*----------------------- CUSTOMER ALL STUDY AJAX -----------------------------
    @CREATE DATE                 :  13-08-2019  
    @ACCESS MODIFIERS            :  PUBLIC FUNCTION 
    ------------------------------------------------------------------------------*/
    public function getAllworkSheetStudies() {
        $request    =   $_REQUEST;
        $assignee   =   $this->input->post('is_assignee');
        $is_day     =   $this->input->post('is_day');
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
        $dicom_total_count      =   $this->dbstudies->workSheetStudiesListAllCount();
        $dicom_studies_list     =   $this->dbstudies->workSheetStudiesListAll($assignee, $is_day, $request, $col);

        if (!empty($request['search']['value'])) {
            $total_data_page    =   count($dicom_studies_list);
        } else {
            $total_data_page    =   $dicom_total_count;
        }
        
        $dicom_studies_dt_list = $this->_dtDicomStudiesList($dicom_studies_list);

        $json_data = array(
            "draw"            =>    intval($request['draw']),
            "recordsTotal"    =>    intval($dicom_total_count),
            "recordsFiltered" =>    intval($total_data_page),
            "data"            =>    $dicom_studies_dt_list
        );

        echo json_encode($json_data);
        exit;
    }
    /*----------------------- CUSTOMER SECOND CHECK AJAX -----------------------------
    @CREATE DATE                 :  14-08-2019  
    @ACCESS MODIFIERS            :  PUBLIC FUNCTION 
    ------------------------------------------------------------------------------*/
    public function getAnalystAllworkSheetStudies() {
        $request = $_REQUEST;
        $assignee   =   $this->input->post('is_assignee');
        $is_day     =   $this->input->post('is_day');
        $is_second  =   $this->input->post('is_second');
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

        $dicom_total_count      =   $this->dbstudies->workSheetStudiesListAllCount();
        $dicom_studies_list     =   $this->dbstudies->workSheetStudiesListAll($assignee, $is_day, $request, $col, $is_second);

        if (!empty($request['search']['value']) || !empty($is_second)) {
            $total_data_page = count($dicom_studies_list);
        } else {
            $total_data_page    =   $dicom_total_count;
        }
        $dicom_studies_dt_list  =   $this->_dtDicomAnalystStudiesList($dicom_studies_list);
        $json_data = array(
            "draw"              =>  intval($request['draw']),
            "recordsTotal"      =>  intval($dicom_total_count),
            "recordsFiltered"   =>  intval($total_data_page),
            "data"              =>  $dicom_studies_dt_list
        );

        echo json_encode($json_data);
        exit;

    }
    /*----------------------- DATA TABLE ARRAY -----------------------------
    @CREATE DATE                 :  14-08-2019  
    @ACCESS MODIFIERS            :  PRIVATE FUNCTION 
    ------------------------------------------------------------------------------*/
    private function _dtDicomStudiesList($studies = array()) {
        $data   =   array();
        foreach ($studies as $key => $row) {
            $subdata        =   array();
            $original_date  =   $row['created'];
            $review_name    =   $this->dbstudies->getNameById($row['review_user_id']);
            $new_date       =   date("m-d-Y h:i:s", strtotime($original_date));
            $subdata[]      =   $new_date;
            $subdata[]      =   $row['accession']; 
            $subdata[]      =   $row['patient_name']; 
            $subdata[]      =   $row['mrn'];
            $subdata[]      =   ($row['tat'] != '') ? $row['tat'] . ' hrs' : '';
            $subdata[]      =   $row['webhook_customer']; 
            $subdata[]      =   $row['name'] ? : 'Not Assigned' ; 
            $subdata[]      =   (empty($review_name)) ? 'Not Reviewed' : $review_name;
            $subdata[]      =   $row['webhook_description'];

            if ($row['status']  ==  'Completed') {
                $row['status']  =   '<span id="status_val_' . $key . '" class="btn btn-xs btn-success status_chk" rel="' . str_replace(' ', '_', strtolower($row['status'])) . '">Completed</span>';
                $bgcolor    =   'bg-success';
            } else if ($row['status']   ==  '') {
                $row['status']  =   '<span id="status_val_' . $key . '" class="btn btn-xs btn-danger status_chk" rel="' . str_replace(' ', '_', strtolower('Not 
                Assigned')) . '">Not Assigned</span>';
                $bgcolor    =   'bg-danger';
            } else if ($row['status']   ==  'In progress') {
                $row['status']  =   '<span id="status_val_' . $key . '" class="btn btn-xs btn-info status_chk" rel="' . str_replace(' ', '_', strtolower($row['status'])) . '">In progress</span>';
                $bgcolor    =   'bg-info';
            } else if ($row['status']   ==  'Under review') {
                $row['status']  =   '<span id="status_val_' . $key . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower
                                        ($row['status'])) . '">Under review</span>';
                $bgcolor    =   'bg-warning';
            } else if ($row['status']   ==   'Cancelled') {
                $row['status']  =   '<span id="status_val_' . $key . '" class="btn btn-xs btn-default status_chk" rel="' . str_replace(' ', '_', strtolower($row['status'])) . '">Cancelled</span>';
                $bgcolor    =   'bg-default';
            } else if ($row['status']   ==   'On hold') {
                $row['status']  =    '<span id="status_val_' . $key . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row['status'])) . '">On hold</span>';
                $bgcolor    =   'bg-warning';
            }
            $subdata[]  =   $row['status'];
            /*$subdata[] = '<a href="'. base_url('customer/customer/studies/edit/'.$row['id']).'" class="btn btn-info btn-circle btn-outline" data-toggle="tooltip" data-placement="top" title="View" data-original-title="View"><i class="fa fa-eye"></i></a>';*/
            $data[]     =   $subdata;
        }

        return $data;
    }
    /*----------------------- DATA TABLE ARRAY -----------------------------
    @CREATE DATE                 :  14-08-2019  
    @ACCESS MODIFIERS            :  PRIVATE FUNCTION 
    ------------------------------------------------------------------------------*/
    private function _dtDicomAnalystStudiesList($studies = array()) {
        $data   =   array();
        foreach ($studies as $key => $row) {
            $subdata        =   array();
            $original_date  =   $row['created'];
            $review_name    =   $this->dbstudies->getNameById($row['review_user_id']);
            $new_date       =   date("m-d-Y h:i:s", strtotime($original_date));
            $subdata[]      =   $new_date;
            $subdata[]      =   $row['accession']; 
            $subdata[]      =   $row['patient_name']; 
            $subdata[]      =   $row['mrn'];
            $subdata[]      =   ($row['tat'] != '') ? $row['tat'] . ' hrs' : '';
            $subdata[]      =   $row['webhook_customer']; 
            $subdata[]      =   $row['name'] ? : 'Not Assigned' ; 
            $subdata[]      =   (empty($review_name)) ? 'Not Reviewed' : $review_name;
            $subdata[]      =   $row['webhook_description'];

            $statusCheck    =   $row['status'];

            if ($row['status']  ==   'Completed') {
                $row['status']  =   '<span id="status_val_' . $key . '" class="btn btn-xs btn-success status_chk" rel="' . str_replace(' ', '_', strtolower($row['status'])) . '">Completed</span>';
                $bgcolor    =   'bg-success';
            } else if ($row['status']   ==   '') {
                $row['status']  =    '<span id="status_val_' . $key . '" class="btn btn-xs btn-danger status_chk" rel="' . str_replace(' ', '_', strtolower('Not 
                    Assigned')) . '">Not Assigned</span>';
                $bgcolor    =    'bg-danger';
            } else if ($row['status']   ==   'In progress') {
                $row['status']  =    '<span id="status_val_' . $key . '" class="btn btn-xs btn-info status_chk" rel="' . str_replace(' ', '_', strtolower($row['status'])) . '">In progress</span>';
                $bgcolor    =    'bg-info';
            } else if ($row['status']   ==   'Under review') {
                $row['status']  =    '<span id="status_val_' . $key . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower
                                        ($row['status'])) . '">Under review</span>';
                $bgcolor    =    'bg-warning';
            } else if ($row['status']   ==   'Cancelled') {
                $row['status']  =    '<span id="status_val_' . $key . '" class="btn btn-xs btn-default status_chk" rel="' . str_replace(' ', '_', strtolower($row['status'])) . '">Cancelled</span>';
                $bgcolor    =    'bg-default';
            } else if ($row['status']   ==   'On hold') {
                $row['status']  =    '<span id="status_val_' . $key . '" class="btn btn-xs btn-warning status_chk" rel="' . str_replace(' ', '_', strtolower($row['status'])) . '">On hold</span>';
                $bgcolor    =    'bg-warning';
            }
            $subdata[]  =    $row['status'];

            /*if ($row['name'] == '') {
                $subdata[] = '<a href="" class="btn btn-success btn-circle btn-outline" data-toggle="tooltip" data-placement="top" title="Assign" data-original-title="Assign"><i class="fa fa-tasks"></i></a>';
            } else if ($statusCheck == 'Completed') {
                $subdata[] = '<a href="" class="btn btn-primary btn-circle btn-outline" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"><i class="fa fa-pencil"></i></a><a  class="btn btn-warning btn-circle btn-outline" onclick="getreview('.$row['id'].')" data-toggle="tooltip" data-placement="top" title="Review" data-original-title="Review" style="display:none;"><i class="fa fa-bullseye"></i></a>';
            } else {
                $subdata[] = '<a href="" class="btn btn-primary btn-circle btn-outline" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"><i class="fa fa-pencil"></i></a><a  class="btn btn-warning btn-circle btn-outline" onclick="getreview('.$row['id'].')" data-toggle="tooltip" data-placement="top" title="Review" data-original-title="Review" style="display:none;"><i class="fa fa-bullseye"></i></a>';
            }*/
            $data[]     =    $subdata;
        }

        return $data;
    }
}