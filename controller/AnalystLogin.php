<?php

class AnalystLogin extends Controller
{

    private $analystlogindb;

    function __construct()
    {
        $this->analystlogindb = $this->model('analystlogindb');
    }

    public function index()
    {
        $data['db'] = $this->getConnection();
        $data['analystlogindb'] = $this->analystlogindb;

        // echo "Hi";
        if (isset($_POST['submit'])) {
            $username = mysqli_real_escape_string($data['db'], $_POST['username']);
            $password = mysqli_real_escape_string($data['db'], $_POST['password']);
            $login_status = $this->analystlogindb->checkLogin($username, $password);
            switch ($login_status) {
                case 1: {
                        $user = $_SESSION['user'];
                        // $this->check_force_pasword_reset($user);
                        if ($user->user_type_ids == 3) {
                            // echo "Hi";
                            // $this->redirect('hme');
                            $this->redirect('analyst_dashboard');
                        } else {
                            $this->add_alert('danger', 'This login is for analysts only.');
                            $this->redirect('analyst_login');
                        }
                        break;
                    }
                case 2: {
                        $this->add_alert('danger', 'Password Incorrect!');
                        $this->redirect('analyst_login');
                        break;
                    }
                case 3: {
                        $this->add_alert('danger', 'User Not Found!');
                        $this->redirect('analyst_login');
                        break;
                    }
                case 4: {
                        $this->add_alert('danger', 'Your account is deactivated!');
                        $this->redirect('analyst_login');
                        break;
                    }
                default: {
                        $this->add_alert('danger', 'Login Failed!' . $login_status);
                        $this->redirect('analyst_login');
                        break;
                    }
            }
            //echo "Form submitted";
            // echo "Email ID: ".$username."<br> Password: ".$password;
            die();
        }
        $this->view('/v2/analyst/analyst_login', $data);
    }
}
