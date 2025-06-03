<?php
/**
 * 
 */
class Loding extends Controller
{	
	  public $Logindb;
    public $Admindb;
    public $user;
	function __construct()
	{
		 $this->Logindb = $this->model('logindb');
        $this->Admindb = $this->model('admindb');
        $this->Report = $this->model('report');
        $this->dbmodel = $this->model('dashboardmodel'); //RC 

		if(isset($_SESSION['user'])){
			$user = $_SESSION['user'];
			$this->check_force_pasword_reset($user);
			if(isset($user) &&  !empty($user)){

				if($user->group_id == 1)
					//echo "jjj";
					$this->redirect('loder');
				else if($user->group_id == 2)
					$this->redirect('manager');
				else
					$this->redirect('dashboard');	
				die();
			}
		}
	}

	public function index(){
		/*$data['db'] = $this->getConnection();
		$data['logindb'] = $this->logindb;
		//echo "jjj";
		
	    $this->view('user/loder',$data); */
	}
}