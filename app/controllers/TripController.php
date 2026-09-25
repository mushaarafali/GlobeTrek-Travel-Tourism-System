<?php

class TripController extends Controller
{
    public function customize()
    {
        requireLogin();
        $this->view('trip/customize');
    }

    public function save()
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('trip/customize');
        }

        $travelDate = $_POST['travel_date'] ?? '';

        if (empty($travelDate) || $travelDate < date('Y-m-d')) {
            $_SESSION['flash']['error'] = 'Past dates are not allowed.';
            $this->redirect('trip/customize');
        }

        $model = $this->model('TripModel');

        $saved = $model->create([
            'user_id'      => $_SESSION['user']['id'],
            'destination'  => trim($_POST['destination'] ?? ''),
            'travel_date'  => $travelDate,
            'days'         => (int)($_POST['days'] ?? 1),
            'budget'       => (float)($_POST['budget'] ?? 0),
            'persons'      => (int)($_POST['persons'] ?? 1),

            // DB column is requirements, not notes
            'requirements' => trim($_POST['requirements'] ?? $_POST['notes'] ?? '')
        ]);

        if ($saved) {
            $_SESSION['flash']['success'] = 'Your customized trip request has been submitted successfully. Staff will review it and send an email notification.';
        } else {
            $_SESSION['flash']['error'] = 'Something went wrong. Please try again.';
        }

        $this->redirect('trip/customize');
    }
}