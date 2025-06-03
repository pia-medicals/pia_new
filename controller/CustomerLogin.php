<?php

class CustomerLogin extends Controller
{

    private $customerlogindb;

    function __construct()
    {
        $this->customerlogindb = $this->model('customerlogindb');
    }

    public function index()
    {
        $data['db'] = $this->getConnection();
        $data['customerlogindb'] = $this->customerlogindb;

        // echo "Hi";
        if (isset($_POST['submit'])) {
            $username = mysqli_real_escape_string($data['db'], $_POST['username']);
            $password = mysqli_real_escape_string($data['db'], $_POST['password']);
            $login_status = $this->customerlogindb->checkLogin($username, $password);
            switch ($login_status) {
                case 1: {
                        $user = $_SESSION['user'];
                        // $this->check_force_pasword_reset($user);
                        if ($user->user_type_ids == 5) {
                            // echo "Hi";
                            // $this->redirect('hme');
                            $this->redirect('customer_dashboard');
                        } else {
                            $this->add_alert('danger', 'This login is for customers only.');
                            $this->redirect('customer_login');
                        }
                        break;
                    }
                case 2: {
                        $this->add_alert('danger', 'Password Incorrect!');
                        $this->redirect('customer_login');
                        break;
                    }
                case 3: {
                        $this->add_alert('danger', 'User Not Found!');
                        $this->redirect('customer_login');
                        break;
                    }
                default: {
                        $this->add_alert('danger', 'Login Failed!' . $login_status);
                        $this->redirect('customer_login');
                        break;
                    }
            }
            //echo "Form submitted";
            // echo "Email ID: ".$username."<br> Password: ".$password;
            die();
        }
        $this->view('/v2/customer/customer_login', $data);
    }
}
