<?php
/**
 * 
 */
class Reset extends Controller
{	
	private $logindb;
	function __construct()
	{
		$this->logindb = $this->model('logindb');
		//print_r($_SESSION['user']);

	}
	public function index(){
		$data['db'] = $this->getConnection();
		$data['logindb'] = $this->logindb;

		if(isset($_POST['submit'])){
			$password = mysqli_real_escape_string($data['db'],$_POST['password']);
			$cpassword = mysqli_real_escape_string($data['db'],$_POST['cpassword']);
			$user = $_SESSION['user'];
			if($password != $cpassword){
				$this->add_alert('danger','Password mismatch!');
				$this->redirect('reset');
			} else {
				$password = password_hash($password, PASSWORD_DEFAULT);
				$resetstatus = $this->logindb->resetPassword($password,$user->id);
				//print_r($resetstatus);
				if($resetstatus == 1){
					//print_r($user);exit;
					if($user->user_type_ids == 1)
						$this->redirect('admin');
					else if($user->user_type_ids == 2)
						$this->redirect('manager');
					else
						$this->redirect('dashboard');
					die();
				} else {
					//echo "fff";
					$this->add_alert('danger','Reset Password Failed!!');
					$this->redirect('reset');
				}
			}
		}
		$this->view('user/reset-password',$data);
	}
}