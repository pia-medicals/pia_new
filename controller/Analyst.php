<?php

class Analyst extends Controller {

    public $Logindb;
    public $Admindb;
    public $user;
    public $Report;
    public $dbmodel;

    function __construct() {
        $this->Logindb = $this->model('logindb');
        $this->Admindb = $this->model('admindb');
        $this->Report = $this->model('report');
        $this->dbmodel = $this->model('dashboardmodel'); //RC 

        if (isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 3) {
            $userdata = $_SESSION['user'];
            // $this->check_force_pasword_reset($userdata);
            $this->user = $this->Admindb->user_obj($_SESSION['user']->email);
        } else {
            $this->add_alert('danger', 'Access forbidden');
            $this->redirect('');
        }
    }

    public function index() {
        $this->redirect('analyst_dashboard');
    }

public function analyst_dicom_details_all() {
        $data['user'] = $this->user;
        $this->view('v2/layout/side_menu/analyst_new_menu', $data);
        $data['asignee'] = $this->Admindb->get_all_analyst();
        $data['analysis_statuses'] = $this->Admindb->get_all_analysis_statuses();
        $this->view('v2/analyst/dicom/all/all', $data);
    }


public function analyst_dicom_details_my() {
    $data['user'] = $this->user;

    // Handle update (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studies_id'])) {
        $studies_id = intval($_POST['studies_id']);
        $second_analyst_id = isset($_POST['second_analyst_id']) && is_numeric($_POST['second_analyst_id']) 
            ? intval($_POST['second_analyst_id']) : null;
        $status_ids = isset($_POST['status_ids']) ? intval($_POST['status_ids']) : null;

        // Convert CSV string to array
        $analysis_performed_list = $_POST['analysis_performed_list'] ?? '';
        $analysis_array = array_filter(array_map('trim', explode(',', $analysis_performed_list)));

        // Call model update
        $update_success = $this->Admindb->update_study_details($studies_id, $second_analyst_id, $status_ids, $analysis_array);

        if ($update_success) {
            $this->add_alert('success', 'Study updated successfully.');
        } else {
            $this->add_alert('danger', 'Failed to update study.');
        }

        $this->redirect('analyst/analyst_dicom_details_my');
        return;
    }

    // Handle view (GET)
    if (isset($_GET['view']) && is_numeric($_GET['view'])) {
        $id = intval($_GET['view']);
        $study_entry = $this->Admindb->get_studies_by_id($id);

        if (!$study_entry) {
            $this->add_alert('danger', 'Study not found.');
            $this->redirect('analyst/analyst_dicom_details_my');
        }

        $data['edit_studies'] = $study_entry;

        // ✅ Pass full user array (with IDs and names)
        $data['other_users'] = $this->Admindb->get_all_analyst();
        $data['statuses'] = $this->Admindb->get_all_analysis_statuses();
        $data['categories'] = array_map(function ($analysis) use ($study_entry) {
            return $analysis['analysis_name'];
        }, $this->Admindb->getAnalysesDDWN($study_entry['client_account_ids'] ?? 0));

        $this->view('v2/layout/side_menu/analyst_new_menu', $data);
        $this->view('v2/analyst/dicom/my/edit', $data);
        return;
    }

    // Default list view
    $data['my_studies'] = $this->Admindb->get_studies_by_analyst($this->user->user_id);
    $data['asignee'] = $this->Admindb->get_all_analyst();
    $data['analysis_statuses'] = $this->Admindb->get_all_analysis_statuses();

    $this->view('v2/layout/side_menu/analyst_new_menu', $data);
    $this->view('v2/analyst/dicom/my/my', $data);
}






public function profile()
{
    $data = [];
    $data['user'] = $data['edit'] = $this->user;
    $data['page_title'] = 'Edit Analyst Profile';

    if (isset($_POST['btnSubmit'])) {
        $form_data = $_POST;
        $form_data['group_id'] = $data['edit']->user_type_ids;
        $form_data['is_active'] = $data['edit']->is_active;
        if ($form_data['password'] == "") {
            $form_data['password'] = $data['edit']->password;
        } else {
            $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
        }
        unset($form_data['btnSubmit']);
        if (!empty($this->empty_key_value($form_data))) {
            $error = $this->empty_key_value($form_data);
            $error_label = '';
            foreach ($error as $key => $value) {
                $error_label .= ucfirst($this->underscore_remove($value)) . ',';
            }
            $this->add_alert('danger', 'Validation Error in ' . $error_label);
            $this->redirect('analyst_profile');
        } else {
            $status = $this->Admindb->analyst_update_profile($form_data);
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('analyst_profile');
        }
    }
    $this->view('v2/layout/analyst_header', $data); 
    $this->view('v2/layout/side_menu/analyst_new_menu', $data); 
    $this->view('v2/analyst/analyst_profile', $data);
    $this->view('v2/layout/analyst_footer', $data); 
}


public function add_new_study() {
    $data['user'] = $this->user;
    $data['sites'] = $this->Admindb->get_all_sites(); 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $study = [
            'accession'        => $_POST['accession'] ?? '',
            'mrn'              => $_POST['mrn'] ?? '',
            'patient_name'     => $_POST['patient_name'] ?? '',
            'client_site_name' => $_POST['client_site_name'] ?? '',
            'comment'          => $_POST['comment'] ?? '',
            'analyst_id'       => $this->user->user_id,
            'created_at'       => date('Y-m-d H:i:s'),
            'status_ids'       => 1
        ];

        $result = $this->Admindb->insert_study($study);

        if ($result) {
            $this->add_alert('success', 'Study added successfully!');
            $this->redirect('analyst/analyst_dicom_details_my');
        } else {
            $this->add_alert('danger', 'Failed to add study.');
        }
    }

    $this->view('v2/layout/side_menu/analyst_new_menu', $data);
    $this->view('v2/analyst/dicom/my/add_new_study', $data);
}



public function analyst_dicom_details_miscellaneous_billing() {
    $data['user'] = $this->user;

    // Handle edit action
    if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
        $id = intval($_GET['edit']);
        // Fetch the billing entry to edit
        $billing_entry = $this->Admindb->get_miscellaneous_billing_by_id($id);

        if (!$billing_entry) {
            $this->add_alert('danger', 'Billing entry not found.');
            $this->redirect('analyst/analyst_dicom_details_miscellaneous_billing');
        }

        $data['edit_billing'] = $billing_entry;
        $data['clients'] = $this->Admindb->get_all_clients();

        // Handle form submission for updating the billing entry
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $update = [
                'name' => $_POST['name'] ?? '',
                'analysis_invoicing_description' => $_POST['analysis_invoicing_description'] ?? '',
                'analysis_client_price' => floatval($_POST['analysis_client_price'] ?? 0),
                'client_account_ids' => intval($_POST['client_account_ids'] ?? 0),
                'count' => isset($_POST['count']) ? intval($_POST['count']) : null,
            ];

            $result = $this->Admindb->update_miscellaneous_billing($id, $update);

            if ($result) {
                $this->add_alert('success', 'Billing entry updated successfully!');
                $this->redirect('analyst/analyst_dicom_details_miscellaneous_billing');
            } else {
                $this->add_alert('danger', 'Failed to update billing entry.');
            }
        }

        $this->view('v2/layout/side_menu/analyst_new_menu', $data);
        $this->view('v2/analyst/dicom/miscellaneous_billing/edit', $data);
        return;
    }

    // List view
    $data['miscellaneous_billing'] = $this->Admindb->get_all_miscellaneous_billing();
    $this->view('v2/layout/side_menu/analyst_new_menu', $data);
    $this->view('v2/analyst/dicom/miscellaneous_billing/list', $data);
}

public function add_miscellaneous_billing() {
    $data['user'] = $this->user;
    $data['clients'] = $this->Admindb->get_all_clients(); // Always fetch clients

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect and sanitize form data
        $billing = [
            'name' => $_POST['name'] ?? '',
            'analysis_invoicing_description' => $_POST['analysis_invoicing_description'] ?? '',
            'analysis_client_price' => $_POST['analysis_client_price'] ?? '',
            'client_account_ids' => $_POST['client_account_ids'] ?? '',
            'count' => $_POST['count'] ?? '',
            'created_by' => $this->user->user_id,
            'created_at' => date('Y-m-d H:i:s'),
            'is_deleted' => 0
        ];

        // Insert into database
        $result = $this->Admindb->insert_miscellaneous_billing($billing);

        if ($result) {
            $this->add_alert('success', 'Billing entry added successfully!');
            $this->redirect('analyst/add_miscellaneous_billing'); // Redirect to the same "Add" page        } else {
            $this->add_alert('danger', 'Failed to add billing entry.');
        }
    }

    $this->view('v2/layout/side_menu/analyst_new_menu', $data);
    $this->view('v2/analyst/dicom/miscellaneous_billing/add', $data);
}




public function analyst_dicom_details_open()
{
    $data['user'] = $this->user;

    // Handle view action
    if (isset($_GET['view']) && is_numeric($_GET['view'])) {
        $studies_id = intval($_GET['view']);

        // Fetch the study entry
        $study = $this->Admindb->get_study_by_id($studies_id);

        if (!$study) {
            $this->add_alert('danger', "Study with ID $studies_id not found.");
            $this->redirect('analyst/analyst_dicom_details_open');
        }

        $data['edit'] = $study;
        $data['sites'] = $this->Admindb->get_all_customers();
        $data['asignee'] = $this->Admindb->get_all_analyst();
        $data['analysis_statuses'] = $this->Admindb->get_all_analysis_statuses();

        $this->view('v2/layout/side_menu/analyst_new_menu', $data);
        $this->view('v2/analyst/dicom/open/view', $data);
        return;
    }

    // Default list view
    $data['asignee'] = $this->Admindb->get_all_analyst();
    $data['analysis_statuses'] = $this->Admindb->get_all_analysis_statuses();
    $data['open_studies'] = $this->Admindb->get_studies_open_status(1);

    $this->view('v2/layout/side_menu/analyst_new_menu', $data);
    $this->view('v2/analyst/dicom/open/list', $data);
}
public function study() {
    $data['user'] = $this->user;

    // Log request method and data for debugging
    error_log("Study method called. Method: {$_SERVER['REQUEST_METHOD']}, POST: " . print_r($_POST, true));

    // Handle form submission (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $studies_id = isset($_POST['studies_id']) ? intval($_POST['studies_id']) : 0;
        $customer_id = isset($_POST['customer']) ? intval($_POST['customer']) : 0;
        $tat = isset($_POST['tat']) ? $_POST['tat'] : '0'; // Avoid direct mysqli call
        $reasn = isset($_POST['reasn']) ? intval($_POST['reasn']) : 0;

        error_log("Form submitted. studies_id: $studies_id, customer_id: $customer_id, tat: $tat, reasn: $reasn");

        // Validate inputs
        if ($studies_id <= 0) {
            $this->add_alert('danger', 'Invalid study ID.');
            error_log("Invalid study ID: $studies_id");
            $this->redirect('analyst/study?view=' . $studies_id);
            return;
        }

        // Assign study to current analyst
        $status = $this->Admindb->assign_study_to_analyst($studies_id, $this->user->user_id);

        error_log("Assign study result: " . print_r($status, true));

        // Handle response
        if ($status['type'] === 'success') {
            $this->add_alert('success', 'Study assigned to you successfully.');
            // Update customer if provided
            if ($customer_id) {
                $update_result = $this->Admindb->update_study_details($studies_id, null, null, [], ['client_account_ids' => $customer_id]);
                error_log("Customer update result: " . ($update_result ? 'Success' : 'Failed'));
            }
            // Clear session variables
            unset($_SESSION['atat']);
            unset($_SESSION['acustomer']);
        } else {
            $this->add_alert('danger', 'Failed to assign study: ' . $status['msg']);
            error_log("Study assignment failed: " . $status['msg']);
        }

        // Redirect after processing
        $this->redirect('analyst/study?view=' . $studies_id);
        return;
    }

    // Handle view (GET)
    if (isset($_GET['view']) && is_numeric($_GET['view'])) {
        $studies_id = intval($_GET['view']);
        error_log("Fetching study with ID: $studies_id");
        $study = $this->Admindb->get_study_by_id($studies_id);

        if (!$study) {
            $this->add_alert('danger', 'Study not found.');
            error_log("Study not found for ID: $studies_id");
            $this->redirect('analyst/analyst_dicom_details_my');
            return;
        }

        $data['edit'] = $study;
        $data['sites'] = $this->Admindb->get_all_clients();

        // Check if study is already assigned
        if ($study['analyst_id'] && $study['analyst_id'] != $this->user->user_id && !isset($_POST['reasn'])) {
            $_SESSION['atat'] = $study['tat'] ?? '';
            $_SESSION['acustomer'] = $study['client_account_ids'] ?? '';
            error_log("Study already assigned. Redirecting to confirm. Analyst ID: {$study['analyst_id']}");
            $this->redirect('analyst/study?view=' . $studies_id . '&assign=1');
            return;
        }
    } else {
        $this->add_alert('danger', 'No study selected.');
        error_log("No study selected. Missing view parameter.");
        $this->redirect('analyst/analyst_dicom_details_my');
        return;
    }

    $this->view('v2/layout/side_menu/analyst_new_menu', $data);
    $this->view('v2/analyst/dicom/open/view', $data); // Corrected view path
}


public function current_month_studies()
{
    $data['user'] = $this->user;
    $data['asignee'] = $this->Admindb->get_all_analyst();
    $data['analysis_statuses'] = $this->Admindb->get_all_analysis_statuses();

    // View single study if ?view=ID is present
    if (isset($_GET['view']) && is_numeric($_GET['view'])) {
        $studies_id = intval($_GET['view']);
        $study = $this->Admindb->get_study_by_id($studies_id);

        if (!$study) {
            $this->add_alert('danger', "Study with ID $studies_id not found.");
            $this->redirect('analyst/analyst_dicom_details_open');
        }

        $data['edit'] = $study;
        $data['sites'] = $this->Admindb->get_all_customers();
        $data['asignee'] = $this->Admindb->get_all_analyst();
        $data['analysis_statuses'] = $this->Admindb->get_all_analysis_statuses();

        $this->view('v2/layout/side_menu/analyst_new_menu', $data);
        $this->view('v2/analyst/dicom/open/view', $data);
        return;
    }

    // FILTER logic (month/year passed via GET)
    $month = isset($_GET['month']) ? $_GET['month'] : null;
    $year = isset($_GET['year']) ? $_GET['year'] : null;

    if ($month && $year) {
        $data['open_studies'] = $this->Admindb->dicom_details_month($month, $year);
        $data['selected_month'] = $month;
        $data['selected_year'] = $year;
    } else {
        $data['open_studies'] = $this->Admindb->get_studies_open_status(1);
        $data['selected_month'] = date('m');
        $data['selected_year'] = date('Y');
    }

    $this->view('v2/layout/side_menu/analyst_new_menu', $data);
    $this->view('v2/analyst/dicom/current/list', $data);
}


}
