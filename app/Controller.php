<?php

class Controller extends App {

    public $connection;

    public function __construct() {
        $this->connection = $this->getConnection();
    }

    public static function getInstance() {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone() {
        
    }

    public function getConnection() {
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysqli_connect_error(), E_USER_ERROR);
        }
        return $connection;
    }

    public function model($model_name, $data = array()) {
        if (is_array($data)) {
            extract($data);
        } else {
            exit('Please Send An Array');
        }
        require MODEL_PATH . strtolower($model_name) . '.php';
        return new $model_name($this->getConnection());
    }

    public function view($view_name, $data = array()) {
        if (is_array($data)) {
            extract($data);
        } else {
            exit('Please Send An Array');
        }
        require VIEW_PATH . strtolower($view_name) . '.php';
    }

    public function admin_sidebar($data = array()) {

        if (is_array($data)) {
            extract($data);
        } else {
            exit('Please Send An Array');
        }
        require VIEW_PATH . "layout/admin_sidebar.php";
        switch ($data['user']->user_type_ids) {
            case 1:
                require VIEW_PATH . "layout/side_menu/admin_menu.php";
                break;
            case 2:
                require VIEW_PATH . "layout/side_menu/manager_menu.php";
                break;
            case 3:
                require VIEW_PATH . "layout/side_menu/analyst_menu.php";
                break;
            case 5:
                require VIEW_PATH . "layout/side_menu/manager_menu.php";
                break;

            default:
                require VIEW_PATH . "layout/side_menu/default_menu.php";
                break;
        }
    }
    
    public function admin_sidebar_v2($data = array()) {
        if (is_array($data)) {
            extract($data);
        } else {
            exit('Please Send An Array');
        }
      //  require VIEW_PATH . "v2/layout/admin_sidebar.php";
        switch ($data['user']->user_type_ids) {
            case 1:
                require VIEW_PATH . "v2/layout/side_menu/admin_menu.php";
                break;
            case 2:
                require VIEW_PATH . "v2/layout/side_menu/manager_menu.php";
                break;
            case 3:
                require VIEW_PATH . "v2/layout/side_menu/analyst_menu.php";
                break;
            case 5:
                require VIEW_PATH . "v2/layout/side_menu/manager_menu.php";
                break;

            default:
                require VIEW_PATH . "v2/layout/side_menu/default_menu.php";
                break;
        }
    }

    public function check_force_pasword_reset($user = array()) {
        //print_r($user);
        $last_reset = (!empty($user->last_reset)) ? strtotime($user->last_reset) : '';
        //exit;
        if (empty($last_reset) || $last_reset < time() - (86400 * 30 * 3)) {
            echo 'This user\'s password is older than 180 days.';
            $this->redirect('reset');
            die();
        } else {
            //echo 'The password is not 30 days old.';
            return true;
        }
    }

    /* 	public function redirect($route='')
      {
      $location = sprintf(
      "%s://%s/%s",
      isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
      $_SERVER['HTTP_HOST'],
      //  $_SERVER['REQUEST_URI'],
      $route
      );

      header("Location: " . $location);
      exit;
      }
     */
}
