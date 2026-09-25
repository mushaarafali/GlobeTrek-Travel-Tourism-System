<?php
class InquiryController extends Controller {

    public function create() {
        $this->view('inquiries/create');
    }

    public function store() {

        if (!isLoggedIn()) {
            flash('error', 'Please login before sending an inquiry.');
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($subject === '' || $message === '') {
            $this->view('inquiries/create', [
                'error' => 'Please fill all fields.'
            ]);
            return;
        }

        $model = $this->model('Inquiry');
        $model->create($_SESSION['user']['id'], $subject, $message);

        $this->view('inquiries/create', [
            'success' => 'Your inquiry was submitted successfully.'
        ]);
    }
}
?>