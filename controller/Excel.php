<?php

/**
 * 
 */
class Excel extends Controller {

    public $Logindb;
    public $Admindb;
    public $Ajax;
    public $user;

    function __construct() {
        //$this->connection = parent::loader()->database();
        $this->Logindb = $this->model('logindb');
        $this->Admindb = $this->model('admindb');
        $this->Ajax = $this->model('ajaxdb');

        if (isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 1) {
            $this->user = $this->Admindb->user_obj($_SESSION['user']->email);
        } else {
            //die('Access forbidden');
            $this->add_alert('danger', 'Access forbidden');
            $this->redirect('');
        }
    }

    public function heading_billing($row_count, $title, $spreadsheet) {
        $heading = array(
            'font' => array(
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            )
        );
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A' . $row_count . ':F' . $row_count);
        $spreadsheet->getActiveSheet()->getStyle('A' . $row_count . ':F' . $row_count)->applyFromArray($heading);

        $spreadsheet->getActiveSheet()->getRowDimension($row_count)->setRowHeight(20);

        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row_count, $title);
    }

    public function billing_amount($row_count, $letter, $spreadsheet) {
        $heading = array(
            'font' => array(
                'size' => 10,
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            )
        );
        $spreadsheet->getActiveSheet()->getStyle($letter . $row_count)->applyFromArray($heading);

        $spreadsheet->getActiveSheet()->getRowDimension($row_count)->setRowHeight(20);
    }

    public function table_heading_billing($row_count, $spreadsheet, $height = 20) {

        $table_head = array(
            'font' => array(
                //'bold'  => true,
                'color' => array('rgb' => 'FFFFFF'),
                //'size'  => 15,
                'name' => 'Verdana'
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            )
        );
        $spreadsheet->getActiveSheet()->getStyle('A' . $row_count . ':F' . $row_count)->applyFromArray($table_head);
        $spreadsheet->getActiveSheet()->getRowDimension($row_count)->setRowHeight($height);
        $spreadsheet->getActiveSheet()->getStyle('A' . $row_count . ':F' . $row_count)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('025b6c');
    }

    public function create_xl() {

        if (!$_POST)
            die();
        extract($_POST);

        $carry_array = json_decode($carry);

        $sub_pack_array = json_decode($sub);

        $billing_items_array = json_decode($billing);
        $t_amt_aftr = str_replace('"', '', $t_amt_aftr);
        $pers = str_replace('"', '', $pers);
        $disc = str_replace('"', '', $disc);
        $t_bef_disc = str_replace('"', '', $t_bef_disc);
        $sub_amount = str_replace('"', '', $sub_amount);

        $main_fee_amt = str_replace('"', '', $main_fee_amt);
        $main_fee_type = str_replace('"', '', $main_fee_type);
        $gtotal = str_replace('"', '', $gtotal);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("B2:E2");
        //$spreadsheet->setActiveSheetIndex(0)->mergeCells("E2:F2");

        $this->heading_billing(1, 'Carry Forward From Previous Month', $spreadsheet);
        $this->table_heading_billing(2, $spreadsheet);

        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Carry Forward From Previous Month')
                ->setCellValue('A2', 'ANALYSIS')
                ->setCellValue('B2', 'BALANCE (CARRY FORWARD)');

        $row_count = 2;

        foreach ($carry_array as $key => $carry_array_item) {
            $row_count++;

            //$spreadsheet->setActiveSheetIndex(0)->mergeCells("B".$row_count.":D".$row_count);


            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $row_count, $carry_array_item[0])
                    ->setCellValue('B' . $row_count, $carry_array_item[1])
                    ->setCellValue('E' . $row_count, $carry_array_item[2]);
        }
        $row_count++;
        $this->heading_billing($row_count, 'Subscription Package', $spreadsheet);
        $row_count++;
        $this->table_heading_billing($row_count, $spreadsheet, 30);
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row_count, 'ITEM')
                ->setCellValue('B' . $row_count, 'TOTAL SUBSCRIBED')
                ->setCellValue('C' . $row_count, 'CARRY OVER FROM PREVIOUS MONTH')
                ->setCellValue('D' . $row_count, 'TOTAL BALANCE FOR THIS MONTH')
                ->setCellValue('E' . $row_count, 'USED')
                ->setCellValue('F' . $row_count, 'BALANCE (CARRY FORWARD TO NEXT MONTH)');

        foreach ($sub_pack_array as $key => $sub_pack_item) {
            $row_count++;
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $row_count, $sub_pack_item[0])
                    ->setCellValue('B' . $row_count, $sub_pack_item[1])
                    ->setCellValue('C' . $row_count, $sub_pack_item[2])
                    ->setCellValue('D' . $row_count, $sub_pack_item[3])
                    ->setCellValue('E' . $row_count, $sub_pack_item[4])
                    ->setCellValue('F' . $row_count, $sub_pack_item[5]);
        }
        $row_count++;

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("E" . $row_count . ":F" . $row_count);

        $this->billing_amount($row_count, 'E', $spreadsheet);
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('E' . $row_count, 'Subscription Amount: ' . $sub_amount);
        $row_count++;
        $this->heading_billing($row_count, 'Additional Billing Items', $spreadsheet);
        $row_count++;
        $this->table_heading_billing($row_count, $spreadsheet, 30);
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("B" . $row_count . ":C" . $row_count);
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row_count, 'ANALYSIS')
                ->setCellValue('B' . $row_count, 'RATE')
                ->setCellValue('D' . $row_count, 'COUNT')
                ->setCellValue('F' . $row_count, 'TOTAL');
        foreach ($billing_items_array as $key => $billing_item) {
            $row_count++;
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $row_count, $billing_item[0])
                    ->setCellValue('B' . $row_count, $billing_item[1])
                    ->setCellValue('D' . $row_count, $billing_item[2])
                    //->setCellValue('E'.$row_count,$billing_item[3])
                    ->setCellValue('F' . $row_count, $billing_item[3]);
        }
        $row_count++;

        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A" . $row_count . ":B" . $row_count);
        //$spreadsheet->setActiveSheetIndex(0)->mergeCells("E".$row_count.":F".$row_count);
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("C" . $row_count . ":D" . $row_count);
        $this->billing_amount($row_count, 'A', $spreadsheet);
        $this->billing_amount($row_count, 'C', $spreadsheet);
        $this->billing_amount($row_count, 'E', $spreadsheet);

        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row_count, 'Total Before Discount: ' . $t_bef_disc);
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('C' . $row_count, 'Discount: ' . $disc);
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('E' . $row_count, 'Total Amount after ' . $pers . '% Discount: ' . $t_amt_aftr);

        $row_count++;
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("A" . $row_count . ":B" . $row_count);
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("E" . $row_count . ":F" . $row_count);
        $spreadsheet->setActiveSheetIndex(0)->mergeCells("C" . $row_count . ":D" . $row_count);
        $this->billing_amount($row_count, 'A', $spreadsheet);
        $this->billing_amount($row_count, 'C', $spreadsheet);
        $this->billing_amount($row_count, 'E', $spreadsheet);

        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row_count, 'Maintenance fee amount: ' . $main_fee_amt);
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('C' . $row_count, 'Maintenance fee type: ' . $main_fee_type);
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('E' . $row_count, 'Grand Total: ' . $gtotal);

        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: Application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="downloadExcel.xlsx"');
        $writer->save('php://output');
        die();
    }

    public function generate_excel() {
        $excel_data = $this->Admindb->get_customer_excel_data();
        //$this->debug($test); die;


        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

// Set document properties
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
                ->setLastModifiedBy('Maarten Balliauw')
                ->setTitle('Office 2007 XLSX Test Document')
                ->setSubject('Office 2007 XLSX Test Document')
                ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('Test result file');

        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'NAME')
                ->setCellValue('B1', 'EMAIL')
                ->setCellValue('C1', 'CREATED DATE');

        $spreadsheet->getActiveSheet()
                ->fromArray(
                        $excel_data, // The data to set
                        NULL, // Array values with this value will not be set
                        'A2');     // Top left coordinate of the worksheet range where
        //    we want to set these values (default is A1)
// Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('CUSTOMER DATA');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Customer_Data.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
        exit;
    }

    public function get_excel_analysis() {
        $excel_data = $this->Admindb->get_excel_analysis_data();
        //$this->debug($test); die;


        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

// Set document properties
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
                ->setLastModifiedBy('Maarten Balliauw')
                ->setTitle('Office 2007 XLSX Test Document')
                ->setSubject('Office 2007 XLSX Test Document')
                ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('Test result file');

        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID')
                ->setCellValue('B1', 'NAME')
                ->setCellValue('C1', 'CATEGORY')
                ->setCellValue('D1', 'PRICE')
                ->setCellValue('E1', 'MINIMUM TIME')
                ->setCellValue('F1', 'CREATED DATE');

        $spreadsheet->getActiveSheet()
                ->fromArray(
                        $excel_data, // The data to set
                        NULL, // Array values with this value will not be set
                        'A2');     // Top left coordinate of the worksheet range where
        //    we want to set these values (default is A1)
// Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('ANALYSIS DATA');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Analysis_Data.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
        exit;
    }

    public function get_excel_customer() {

        $cus_id = $_GET['cus'];

        $excel_data_customer = $this->Admindb->analyses_rate_user_excel($cus_id);
        $excel_data_monthly_disc = $this->Admindb->get_discount_range_by_customer_excel($cus_id);

        $excel_data_subscription = $this->Admindb->subscriptions_user_excel($cus_id);

        $excel_data_total_subscription = $this->Admindb->get_subscription_by_customer_excel($cus_id);

        $maintenace_fees = $this->Admindb->get_maintenance_by_customer_excel($cus_id);
//$this->debug($excel_data_total_subscription); die;

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

// Set document properties
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
                ->setLastModifiedBy('Maarten Balliauw')
                ->setTitle('Office 2007 XLSX Test Document')
                ->setSubject('Office 2007 XLSX Test Document')
                ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('Test result file');

        $spreadsheet->getActiveSheet()->mergeCells('A1:C1');

        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ANALYSIS RATE')
                ->setCellValue('A2', 'DESCRIPTION')
                ->setCellValue('B2', 'RATE')
                ->setCellValue('C2', 'CODE');

        $spreadsheet->getActiveSheet()->mergeCells('E1:G1');
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('E1', 'DISCOUNT RANGE')
                ->setCellValue('E2', 'FROM')
                ->setCellValue('F2', 'TO')
                ->setCellValue('G2', 'PERCENTAGE');

        $spreadsheet->getActiveSheet()->mergeCells('I1:J1');
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('I1', 'SUBSCRIPTION')
                ->setCellValue('I2', 'ANALYSIS')
                ->setCellValue('J2', 'COUNT')
                ->setCellValue('K2', 'SUBSCRIPTION AMOUNT');

        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('M2', 'MAINTENANCE FEE..');

        $spreadsheet->getActiveSheet()
                ->fromArray(
                        $excel_data_customer, // The data to set
                        NULL, // Array values with this value will not be set
                        'A3');     // Top left coordinate of the worksheet range where
        //    we want to set these values (default is A1)

        $spreadsheet->getActiveSheet()
                ->fromArray(
                        $excel_data_monthly_disc,
                        NULL,
                        'E3');

        $spreadsheet->getActiveSheet()
                ->fromArray(
                        $excel_data_subscription,
                        NULL,
                        'I3');

        $spreadsheet->getActiveSheet()
                ->fromArray(
                        $excel_data_total_subscription,
                        NULL,
                        'L2');

        $spreadsheet->getActiveSheet()
                ->fromArray(
                        $maintenace_fees,
                        NULL,
                        'N2');
// Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Customer Data');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        $spreadsheet->getActiveSheet()->getStyle('A1:N200')
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

// Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Customer Data.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
        exit;
    }
}
