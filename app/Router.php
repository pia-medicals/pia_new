<?php



/**
 * 
 */
class Router extends App
{

    private $router;

    function __construct()
    {
        $this->router = new \Klein\Klein();
    }

    public function routes()
    {

        $this->tempDir(); //********  Base Route  ****************

        $this->router->respond('GET', '/?', function ($request) {
            $this->view('layout/header');
            $this->controller('Home');
            $this->view('layout/footer');
        });

        //        $this->router->respond(array('GET', 'POST'), '/login', function () {
        //            $this->view('layout/header');
        //            $this->controller('Login');
        //            $this->view('layout/footer');
        //        });

        $this->router->respond(array('GET', 'POST'), '/login', function () {
            $this->controller('LoginV2');
        });



        $this->router->respond(array('GET', 'POST'), '/redirect', function ($request) {
            // $this->view('layout/header');
            $this->controller('Loding');
            // $this->view('layout/footer');
        });

        $this->router->respond(array('GET', 'POST'), '/reset', function ($request) {
            $this->view('layout/header');
            $this->controller('Reset', $request->page);
            $this->view('layout/footer');
        });

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // Customer Login
        $this->router->respond(array('GET', 'POST'), '/customer_login', function ($request) {
            //$this->controller('CustomerLogin');
            $this->redirect('login');
        });

        $this->router->respond(array('GET', 'POST'), '/customer_dashboard', function () {
            $data = ["page_title" => "Home"];
            $this->view('v2/layout/customer_header', $data);
            $this->view('v2/layout/side_menu/customer_new_menu', $data);
            $this->controller('CustomerDashboard');
            $this->view('v2/layout/customer_footer');
        });

        $this->router->respond(array('GET', 'POST'), '/customer_profile', function ($request) {
            $data = ["page" => 'Edit Profile'];
            $this->view('v2/layout/customer_header', $data);
            $this->view('v2/layout/side_menu/customer_new_menu', $data);
            $this->controller('Customerdetails', 'profile');
            $this->view('v2/layout/customer_footer');
        });

        $this->router->respond(array('GET', 'POST'), '/customer_all_studies', function () {
            $data = ["page_title" => "All Studies"];
            $this->view('v2/layout/customer_header', $data);
            $this->view('v2/layout/side_menu/customer_new_menu', $data);
            $this->controller('CustomerDashboard', 'customerallstudies');
            $this->view('v2/layout/customer_footer');
        });

        $this->router->respond(array('GET', 'POST'), '/customerDetails/[:page]', function ($request) {
            $this->controller('Customerdetails', $request->page);
        });

        $this->router->respond('GET', '/getAssignees', function () {
            $this->controller('Customerdetails', 'getAssignees');
        });

        $this->router->respond('GET', '/getSecondAssignees', function () {

            $this->controller('Customerdetails', 'getSecondAssignees');
        });

        $this->router->respond('GET', '/getStatuses', function () {
            $this->controller('Customerdetails', 'getStatuses');
        });

        $this->router->respond(array('GET', 'POST'), '/customer_stat_report', function () {
            $data = ["page_title" => "Stat Report"];
            $this->view('v2/layout/customer_header', $data);
            $this->view('v2/layout/side_menu/customer_new_menu', $data);
            $this->controller('CustomerDashboard', 'customer_stat_report');
            $this->view('v2/layout/customer_footer');
        });

        $this->router->respond('GET', '/customer_logout', function () {
            session_destroy();
            //$this->redirect('customer_login');
            $this->redirect('login');
        });
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////////////////////////////////////////////
        //Admin TAT
        $this->router->respond(array('GET', 'POST'), '/turnaround_time', function () {
            $data = ["page_title" => "Turnaround Time"];
            $this->view('v2/layout/header', $data);
            $this->controller('Admintat', 'turnaround_time');
            $this->view('v2/layout/footer');
        });
        $this->router->respond(array('GET', 'POST'), '/addnew_turnaround_time', function () {
            $this->controller('Admintat', 'addnew_tat');
        });
        $this->router->respond(array('GET', 'POST'), '/tat/[:page]', function ($request) {
            $this->controller('Admintat', $request->page);
        });
        //////////////////////////////////////////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////////////////////////////////////////////
        //Admin Anlayses Rates
        $this->router->respond(array('GET', 'POST'), '/analyses_rates', function ($request) {
            $this->controller('Admintat', 'analyses_rates');
        });

        $this->router->respond(array('GET', 'POST'), '/analyses_list', function ($request) {
            $this->controller('Admintat', 'list_analyses');
        });

        $this->router->respond(array('GET', 'POST'), '/update_analysis_price', function ($request) {
            $this->controller('Admintat', 'update_analysis_price');
        });

        $this->router->respond(array('GET', 'POST'), '/activate_analysis', function ($request) {
            $this->controller('Admintat', 'activate_analysis');
        });
        
        $this->router->respond(array('GET', 'POST'), '/inactivate_analysis', function ($request) {
            $this->controller('Admintat', 'inactivate_analysis');
        });
        //////////////////////////////////////////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////////////////////////////////////////////
        //Monthly Discount
        $this->router->respond(array('GET', 'POST'), '/get_analyses', function ($request) {
            $this->controller('Admintat', 'get_analyses');
        });

        $this->router->respond(array('GET', 'POST'), '/add_to_disclist', function ($request) {
            $this->controller('Admintat', 'add_to_disclist');
        });

        $this->router->respond(array('GET', 'POST'), '/analyses_discount_list', function ($request) {
            $this->controller('Admintat', 'analyses_discount_list');
        });

        $this->router->respond(array('GET', 'POST'), '/update_analysis_monthly_discount', function ($request) {
            $this->controller('Admintat', 'update_analysis_monthly_discount');
        });

        $this->router->respond(array('GET', 'POST'), '/delete_analysis_monthly_discount', function ($request) {
            $this->controller('Admintat', 'delete_analysis_monthly_discount');
        });

        $this->router->respond(array('GET', 'POST'), '/customer_excel_details', function ($request) {
            $this->controller('Admintat', 'customer_excel_details');
        });
        //////////////////////////////////////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // Analyst Login
        $this->router->respond(array('GET', 'POST'), '/analyst_login', function ($request) {
            //$this->controller('AnalystLogin');
            $this->redirect('login');
        });

        $this->router->respond(array('GET', 'POST'), '/analyst_dashboard', function () {
            $data = ["page_title" => "Home"];
            $this->view('v2/layout/analyst_header', $data);
            $this->view('v2/layout/side_menu/analyst_new_menu', $data);
            $this->controller('AnalystDashboard');
            $this->view('v2/layout/analyst_footer');
        });

        $this->router->respond(array('GET', 'POST'), '/analyst/[:page]', function ($request) {
            $sub = '';
            if (isset($_GET['edit'])) {
                $sub = 'Edit';
            }
            $data = ["page" => $request->page, "sub" => $sub];
            $this->view('v2/layout/analyst_header', $data);
            $this->controller('Analyst', $request->page);
            $this->view('v2/layout/analyst_footer');
        });

        $this->router->respond('GET', '/analyst_logout', function () {
            session_destroy();
            //$this->redirect('analyst_login');
            $this->redirect('login');
        });
        ///////////////////////////////////////////////////////////////////////////////////////////////////


        $this->router->respond(array('GET', 'POST'), '/pdf', function () {
            $style = '<style>table{border-collapse:collapse;border-spacing:0}td,th{padding:0}@media print{*{text-shadow:none!important;color:#000!important;background:transparent!important;box-shadow:none!important}thead{display:table-header-group}tr{page-break-inside:avoid}}*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}:before,:after{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}table{max-width:100%;background-color:transparent}th{text-align:left}table.admin{border:1px solid #ccc;width:100%;margin:0;padding:0;border-collapse:collapse;border-spacing:0;background:#eeefe8;-webkit-box-shadow:7px 22px 40px -1px rgba(0,0,0,0.15);-moz-box-shadow:7px 22px 40px -1px rgba(0,0,0,0.15);box-shadow:7px 22px 40px -1px rgba(0,0,0,0.15)}table.admin thead{background:#025b6c;color:#fff}table.admin tr{border:1px solid #000;padding:5px}table.admin th,table.admin td{padding:10px;text-align:center}table.admin th{text-transform:uppercase;font-size:14px;letter-spacing:1px}@media screen and (max-width:600px){table.admin{border:0}table.admin thead{display:none}table.admin tr{margin-bottom:10px;display:block;border-bottom:2px solid #000}table.admin td{display:block;text-align:right;font-size:13px;border-bottom:1px solid #ccc}table.admin td:last-child{border-bottom:0}table.admin td:before{content:attr(data-label);float:left;text-transform:uppercase;font-weight:bold}}::-moz-selection{background:##777;color:#00a7f5}::selection{background:##777;color:#00a7f5}::-moz-selection{background:##777;color:#00a7f5}*,*:hover{outline:none!important;text-decoration:none!important}</style>';

            $dompdf = new Dompdf\Dompdf();
            $dompdf->loadHtml($style . '<div style="text-align: right;">' . $_POST['date'] . '</div>' . $_POST['pdf']);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
        });

        $this->router->respond(array('GET', 'POST', 'PUT'), '/api/ambra/incoming', function ($request, $response) {
            $this->api('Api', 'amberincoming', $request);
        });



        $this->router->respond(array('GET', 'POST', 'PUT'), '/api/carry', function ($request, $response) {
            $this->api('Api', 'carry_calculation', $request);
        });


        $this->router->respond(array('GET', 'POST'), '/admin', function () {
            $this->view('layout/header');
            $this->controller('Admn');
            $this->view('layout/footer');
        });

        /* $this->router->respond(array('GET', 'POST'), '/Kr', function () {
           /* $this->view('layout/header');
            $this->controller('Kr');
            $this->view('layout/footer');*/
        //  echo "kk";
        // });

        $this->router->respond(array('GET', 'POST'), '/hme', function () {
            $this->view('layout/header');
            $this->controller('Hme');
            $this->view('layout/footer');
        });


        $this->router->respond(array('GET', 'POST'), '/admin/[:page]', function ($request) {
            $sub = '';
            if (isset($_GET['edit'])) {
                $sub = 'Edit';
            }
            $data = ["page" => $request->page, "sub" => $sub];
            $this->view('v2/layout/header', $data);
            $this->controller('Admin', $request->page);
            $this->view('v2/layout/footer');
        });



        $this->router->respond(array('GET', 'POST'), '/mydashboard', function () {
            $data = ["page_title" => "Home"];
            $this->view('v2/layout/header', $data);
            $this->controller('AdminV2', 'mydashboard');
            $this->view('v2/layout/footer');
        });

        $this->router->respond(array('GET', 'POST'), '/adminV2', function () {
            $data = ["page_title" => "Dashboard"];
            $this->view('v2/layout/header', $data);
            $this->controller('AdminV2');
            $this->view('v2/layout/footer');
        });

        $this->router->respond(array('GET', 'POST'), '/adminV2/[:page]', function ($request) {
            $sub = '';
            if (isset($_GET['edit'])) {
                $sub = 'Edit';
            }
            $data = ["page" => $request->page, "sub" => $sub];
            $this->view('v2/layout/header', $data);
            $this->controller('AdminV2', $request->page);
            $this->view('v2/layout/footer');
        });

        $this->router->respond(array('GET', 'POST'), '/report', function () {
            $this->controller('ReportV2');
        });

        $this->router->respond(array('GET', 'POST'), '/report/[:page]', function ($request) {
            $sub = '';
            if (isset($_GET['edit'])) {
                $sub = 'Edit';
            }
            $data = ["page" => $request->page, "sub" => $sub];
            $this->view('v2/layout/header', $data);
            $this->controller('ReportV2', $request->page);
            $this->view('v2/layout/footer');
        });

        $this->router->respond(array('GET', 'POST'), '/mydashboard/profile', function ($request) {
            $data = ["page" => 'Edit Profile'];
            $this->view('v2/layout/header', $data);
            $this->controller('AdminV2', 'profile');
            $this->view('v2/layout/footer');
        });



        $this->router->respond(array('GET', 'POST'), '/adminstat/[:page]', function ($request) {
            $this->view('layout/header');
            $this->controller('Admin', $request->page);
            $this->view('layout/footerstat');
        });

        $this->router->respond(array('GET', 'POST'), '/manager', function () {
            $this->view('layout/header');
            $this->controller('Manager');
            $this->view('layout/footer');
        });

        $this->router->respond(array('GET', 'POST'), '/manager/[:page]', function ($request) {
            $this->view('layout/header');
            $this->controller('Manager', $request->page);
            $this->view('layout/footer');
        });


        $this->router->respond(array('GET', 'POST'), '/ajax/[:page]', function ($request) {
            $this->controller('Ajax', $request->page);
        });

        $this->router->respond(array('GET', 'POST'), '/ajaxV2/[:page]', function ($request) {
            $this->controller('AjaxV2', $request->page);
        });

        $this->router->respond(array('GET', 'POST'), '/ajaxV3/[:page]', function ($request) {
            $this->controller('Ajaxv3', $request->page);
        });

        $this->router->respond(array('GET', 'POST'), '/excel/[:page]', function ($request) {
            $this->controller('Excel', $request->page);
        });


        $this->router->respond(array('GET', 'POST'), '/dashboard', function () {
            $this->view('layout/header');
            $this->controller('Dashboard');
            $this->view('layout/footer');
        });

        $this->router->respond(array('GET', 'POST'), '/dashboard/[:page]', function ($request) {
            $this->view('layout/header');
            $this->controller('Dashboard', $request->page);
            $this->view('layout/footer');
        });
        /*         * ****************************** RC ***************************************** */
        /* ----------------------- ANALYST RATE REPORT ---------------------------------
          @ACCESS MODIFIERS            :  PUBLIC FUNCTION
          @FUNCTION DATE               :  23-04-2019
          ------------------------------------------------------------------------------ */
        $this->router->respond(array('GET', 'POST'), '/analystrate', function () {
            $this->view('layout/header');
            $this->controller('Analystrate');
            $this->view('layout/footer');
        });
        $this->router->respond(array('GET', 'POST'), '/analystrate/[:page]', function ($request) {
            $this->view('layout/header');
            $this->controller('Analystrate', $request->page);
            $this->view('layout/footer');
        });
        #--------------------------------------------------------------------------------

        /*         * ****************************** RC ***************************************** */
        /* ----------------------- Admin Assign ---------------------------------
          @ACCESS MODIFIERS            :  PUBLIC FUNCTION
          @FUNCTION DATE               :  08-08-2019
          ------------------------------------------------------------------------------ */
        $this->router->respond(array('GET', 'POST'), '/adminassign', function () {
            $this->view('layout/header');
            $this->controller('Adminassign');
            $this->view('layout/footer');
        });
        $this->router->respond(array('GET', 'POST'), '/adminassign/[:page]', function ($request) {
            //echo $request->page;
            $this->view('layout/header');
            $this->controller('Adminassign', $request->page);
            $this->view('layout/footer');
        });
        #--------------------------------------------------------------------------------
        $this->router->respond('GET', '/hello-world', function () {
            return 'Hello World!';
        });

        $this->router->respond('GET', '/logout', function () {
            session_destroy();
            $this->redirect('login');
        });


        $this->router->respond('GET', '/abc', function () {
            return 'Hello World abc!';
        });

        $this->router->onHttpError(function ($code, $router) {
            switch ($code) {
                case 404: {
                        $this->view('layout/header');
                        $this->view('pages/404');
                        $this->view('layout/footer');
                        break;
                    }

                case 405:
                    $router->response()->body(
                        'You can\'t do that!'
                    );
                    break;
                default:
                    $router->response()->body(
                        'Oh no, a bad error happened that caused a ' . $code
                    );
            }
        });

        $this->router->dispatch();
    }

    public function tempDir()
    {

        $this->router->respond(array('POST', 'GET'), '/404', function () {
            $this->view('layout/header');
            $this->view('pages/404');
            $this->view('layout/footer');
        });
    }
}
