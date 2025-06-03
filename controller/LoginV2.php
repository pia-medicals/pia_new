<?php

class LoginV2 extends Controller {

    private $logindbV2;

    function __construct() {
        $this->logindbV2 = $this->model('logindbV2');
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            //  $this->check_force_pasword_reset($user);
            if (isset($user) && !empty($user)) {
                if ($user->user_type_ids == 1) {
                    // $this->redirect('hme');
                    $this->redirect('mydashboard');
                } else if ($user->user_type_ids == 2) {
                    $this->redirect('manager');
                } else {
                    $this->redirect('dashboard');
                }
                die();
            }
        }
    }

    public function index() {
        $data['db'] = $this->getConnection();
        $data['logindb'] = $this->logindbV2;
        if (isset($_POST['submit'])) {
            $username = mysqli_real_escape_string($data['db'], $_POST['username']);
            $password = mysqli_real_escape_string($data['db'], $_POST['password']);
            $login_status = $this->logindbV2->checkLogin($username, $password);

            switch ($login_status) {
                case 1: {
                        $user = $_SESSION['user'];
                        // $this->check_force_pasword_reset($user);
                        if ($user->user_type_ids == 1) {
                            // $this->redirect('hme');
                            $this->redirect('mydashboard');
                        } 
                        else if ($user->user_type_ids == 5) {
                            $this->redirect('customer_dashboard');
                        } 
                        else if ($user->user_type_ids == 3) {
                            $this->redirect('analyst_dashboard');
                        }
                        else{
                            session_destroy();
                            $this->redirect('login');
                        }
//                        else if ($user->user_type_ids == 2) {
//                            $this->redirect('manager');
//                        } 
//                        else {
//                            $this->redirect('dashboard');
//                        }
                        break;
                    }
                case 2: {
                        $this->add_alert('danger', 'Password Incorrect!');
                        $this->redirect('login');
                        break;
                    }
                case 3: {
                        $this->add_alert('danger', 'User Not Found!');
                        $this->redirect('login');
                        break;
                    }
                default: {
                        $this->add_alert('danger', 'Login Failed!' . $login_status);
                        $this->redirect('login');
                        break;
                    }
            }
        }
        $this->view('v2/user/login', $data);
    }
}
