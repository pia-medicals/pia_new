<?php

/**
 * 
 */
class Router extends App {

    private $router;

    function __construct() {
        $this->router = new \Klein\Klein();
    }

    public function routes() {

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


//     $this->router->respond(array('GET', 'POST'), '/admin', function () {
//            $this->view('layout/header');
//            $this->controller('Admn');
//            $this->view('layout/footer');
//        }); 
        
        $this->router->respond(array('GET', 'POST'), '/admin', function () {
            $this->view('v2/layout/header');
            $this->controller('AdminV2');
            $this->view('v2/layout/footer');
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
            $this->view('layout/header');
            $this->controller('Admin', $request->page);
            $this->view('layout/footer');
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

    public function tempDir() {

        $this->router->respond(array('POST', 'GET'), '/404', function () {
            $this->view('layout/header');
            $this->view('pages/404');
            $this->view('layout/footer');
        });
    }

}
