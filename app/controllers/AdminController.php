<?php
require_once __DIR__ . '/../helpers/mail_helper.php';

class AdminController extends Controller
{
    public function dashboard()
    {
        requireRole('admin');

        $booking = $this->model('Booking');
        $user = $this->model('User');
        $packageModel = $this->model('Package');
        $inquiryModel = $this->model('Inquiry');
        $tripModel = $this->model('TripModel');

        $prediction = $booking->futurePrediction();

        $this->view('admin/dashboard', [
            'packageCount' => count($packageModel->all()),
            'bookingCount' => $booking->count(),
            'revenue' => $booking->revenue(),
            'inquiryCount' => count($inquiryModel->all()),
            'customTripCount' => count($tripModel->all()),
            'users' => $user->all(),
            'prediction' => $prediction
        ]);
    }

    public function customTrips()
    {
        requireRole('admin');

        $model = $this->model('TripModel');

        $this->view('admin/custom_trips', [
            'trips' => $model->all()
        ]);
    }

    public function packages()
    {
        requireRole('admin');
        $model = $this->model('Package');
        $this->view('admin/packages', ['packages' => $model->all()]);
    }

    public function savePackage()
    {
        requireRole('admin');
        $model = $this->model('Package');
        $data = $this->packageDataFromRequest();

        if (!$data['title'] || !$data['destination'] || $data['price'] <= 0) {
            flash('error', 'Please enter valid package details.');
            $this->redirect('admin/packages');
        }

        $model->create($data);
        flash('success', 'Package saved successfully.');
        $this->redirect('admin/packages');
    }

    public function deletePackage(int $id)
    {
        requireRole('admin');
        $this->model('Package')->delete($id);
        flash('success', 'Package deleted successfully.');
        $this->redirect('admin/packages');
    }

    public function bookings()
    {
        requireRole('admin');
        $this->view('admin/bookings', ['bookings' => $this->model('Booking')->all()]);
    }

    public function status($id, $status)
    {
        requireRole('admin');

        $status = ucfirst(strtolower(trim($status)));
        $allowed = ['Confirmed', 'Cancelled', 'Pending'];

        if (!in_array($status, $allowed, true)) {
            flash('error', 'Invalid booking status.');
            $this->redirect('admin/bookings');
        }

        $bookingModel = $this->model('Booking');
        $bookingModel->updateStatus($id, $status);
        $booking = $bookingModel->findWithDetails($id);

        if ($booking && in_array($status, ['Confirmed', 'Cancelled'], true)) {
            $subject = $status === 'Confirmed'
                ? 'Booking Confirmed - GlobeTrek Adventures'
                : 'Booking Cancelled - GlobeTrek Adventures';

            $message = "Dear " . ($booking['user_name'] ?? 'Customer') . ",\n\n";
            $message .= $status === 'Confirmed'
                ? "Your booking has been confirmed.\n\n"
                : "Your booking has been cancelled.\n\n";
            $message .= "Package: " . ($booking['package_title'] ?? '') . "\n";
            $message .= "Destination: " . ($booking['destination'] ?? '') . "\n";
            $message .= "Travel Date: " . ($booking['travel_date'] ?? '') . "\n";
            $message .= "Persons: " . ($booking['persons'] ?? '') . "\n";
            $message .= "Total Amount: LKR " . number_format((float)($booking['total_amount'] ?? 0), 2) . "\n\n";
            $message .= "Thank you for choosing GlobeTrek Adventures.";

            if (!empty($booking['user_email'])) {
                sendNotificationEmail($booking['user_email'], $subject, $message);
            }
        }

        flash('success', 'Booking status updated successfully.');
        $this->redirect('admin/bookings');
    }

    public function prediction()
    {
        requireRole('admin');

        $model = $this->model('Prediction');

        $this->view('admin/prediction', [
            'summary' => $model->summary(),
            'monthlySales' => $model->monthlySales(),
            'packages' => $model->packagePerformance(),
            'prediction' => $model->nextMonthPrediction()
        ]);
    }

    public function inquiries()
    {
        requireRole('admin');
        $this->view('admin/inquiries', ['inquiries' => $this->model('Inquiry')->all()]);
    }

    public function replyInquiry($id)
    {
        requireRole('admin');
        $this->model('Inquiry')->reply($id, $_POST['reply'] ?? '');
        flash('success', 'Inquiry reply saved.');
        $this->redirect('admin/inquiries');
    }

    public function staff()
    {
        requireRole('admin');
        $this->view('admin/staff', ['staff' => $this->model('User')->staffOnly()]);
    }

    public function saveStaff()
    {
        requireRole('admin');

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            flash('error', 'Please enter valid staff details. Password must be 6+ characters.');
            $this->redirect('admin/staff');
        }

        $userModel = $this->model('User');

        if ($userModel->findByEmail($email)) {
            flash('error', 'This email already exists.');
            $this->redirect('admin/staff');
        }

        $userModel->createStaff($name, $email, $password);
        flash('success', 'Staff account created successfully.');
        $this->redirect('admin/staff');
    }

    public function deleteStaff($id)
    {
        requireRole('admin');
        $this->model('User')->deleteStaff($id);
        flash('success', 'Staff account deleted successfully.');
        $this->redirect('admin/staff');
    }

    public function editStaff($id)
    {
        requireRole('admin');

        $staff = $this->model('User')->findById($id);

        if (!$staff || $staff['role'] !== 'staff') {
            flash('error', 'Staff not found.');
            $this->redirect('admin/staff');
            return;
        }

        $this->view('admin/edit_staff', [
            'staff' => $staff
        ]);
    }

    public function updateStaff($id)
    {
        requireRole('admin');

        $this->model('User')->updateStaff(
            $id,
            trim($_POST['name']),
            trim($_POST['email'])
        );

        flash('success', 'Staff updated successfully.');
        $this->redirect('admin/staff');
    }

    public function editPackage(int $id)
    {
        requireRole('admin');

        $package = $this->model('Package')->find($id);

        if (!$package) {
            flash('error', 'Package not found');
            $this->redirect('admin/packages');
            return;
        }

        $this->view('admin/edit_package', [
            'package' => $package,
            'role' => 'admin'
        ]);
    }

public function updatePackage($id)
{
    requireRole('admin');

    $current = $this->model('Package')->find($id);

    if (!$current) {
        flash('error', 'Package not found.');
        $this->redirect('admin/packages');
        return;
    }

    $this->model('Package')->update(
        $id,
        $this->packageDataFromRequest($current['image'] ?? 'default-package.jpg')
    );

    flash('success', 'Package updated successfully.');
    $this->redirect('admin/packages');
}

    public function customers()
    {
        requireRole('admin');

        $customers = $this->model('User')->customersOnly();

        $this->view('admin/customers', [
            'customers' => $customers
        ]);
    }

    public function reports()
    {
        requireRole('admin');

        $booking = $this->model('Booking');
        $payment = $this->model('Payment');

        $this->view('admin/reports', [
            'revenue' => $booking->revenueSummary(),
            'statuses' => $booking->statusSummary(),
            'packages' => $booking->packagePerformance(),
            'payments' => $payment->summary(),
            'prediction' => $booking->futurePrediction(),
            'customers' => $this->model('User')->customersOnly()
        ]);
    }


    public function transport()
    {
        requireRole('admin');
        $this->view('admin/transport', ['items' => $this->model('Transport')->all()]);
    }

    public function saveTransport()
    {
        requireRole('admin');
        $this->model('Transport')->create($_POST);
        flash('success', 'Transport saved successfully.');
        $this->redirect('admin/transport');
    }

    public function deleteTransport($id)
    {
        requireRole('admin');
        $this->model('Transport')->delete($id);
        flash('success', 'Transport deleted successfully.');
        $this->redirect('admin/transport');
    }




    public function editTransport($id)
    {
        requireRole('admin');
        $item = $this->model('Transport')->find($id);
        if (!$item) {
            flash('error', 'Transport not found.');
            $this->redirect('admin/transport');
        }
        $this->view('admin/edit_transport', ['item' => $item]);
    }

    public function updateTransport($id)
    {
        requireRole('admin');
        $this->model('Transport')->update($id, $_POST);
        flash('success', 'Transport updated successfully.');
        $this->redirect('admin/transport');
    }

    public function travelGuides()
    {
        requireRole('admin');
        $this->view('admin/travel_guides', ['guides' => $this->model('TravelGuide')->all()]);
    }

    public function saveTravelGuide()
    {
        requireRole('admin');

        $data = $this->travelGuideDataFromRequest();

        if (!$data['title'] || !$data['location'] || !$data['description']) {
            flash('error', 'Please enter valid travel guide details.');
            $this->redirect('admin/travelGuides');
        }

        $this->model('TravelGuide')->create($data);
        flash('success', 'Travel guide saved successfully.');
        $this->redirect('admin/travelGuides');
    }

    public function deleteTravelGuide($id)
    {
        requireRole('admin');
        $this->model('TravelGuide')->delete($id);
        flash('success', 'Travel guide deleted successfully.');
        $this->redirect('admin/travelGuides');
    }

public function editTravelGuide($id = null)
{
    requireRole('admin');

    if (!$id) {
        $this->redirect('admin/travelGuides');
    }

    $guide = $this->model('TravelGuide')->find($id);

    if (!$guide) {
        $this->view('errors/404');
        return;
    }

    $this->view('admin/edit_travel_guide', [
        'guide' => $guide
    ]);
}

    public function updateTravelGuide($id = null)
    {
        requireRole('admin');

        if (!$id) {
            $this->redirect('admin/travelGuides');
        }

        $roleBase = 'admin';

    $oldGuide = $this->model('TravelGuide')->find($id);

    if (!$oldGuide) {
        $this->view('errors/404');
        return;
    }

    $imageName = $_POST['image'] ?? ($oldGuide['image'] ?? '');

    if (!empty($_FILES['image_file']['name'])) {
        $imageName = time() . '_' . basename($_FILES['image_file']['name']);

        move_uploaded_file(
            $_FILES['image_file']['tmp_name'],
            __DIR__ . '/../../public/assets/images/' . $imageName
        );
    }

    $this->model('TravelGuide')->update($id, [
        'title'       => trim($_POST['title'] ?? ''),
        'location'    => trim($_POST['location'] ?? ''),
        'category'    => trim($_POST['category'] ?? ''),
        'best_time'   => trim($_POST['best_time'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'image'       => $imageName
    ]);

    $this->redirect($roleBase . '/travelGuides');
}
    private function packageDataFromRequest($fallbackImage = 'default-package.jpg')
    {
        $image = trim($_POST['image'] ?? '') ?: $fallbackImage;

        if (!empty($_FILES['image_file']['name']) && is_uploaded_file($_FILES['image_file']['tmp_name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed, true)) {
                $newName = 'package_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $target = __DIR__ . '/../../public/assets/images/' . $newName;

                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target)) {
                    $image = $newName;
                }
            }
        }

        return [
            'title' => trim($_POST['title'] ?? ''),
            'destination' => trim($_POST['destination'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'days' => (int)($_POST['days'] ?? 1),
            'price' => (float)($_POST['price'] ?? 0),
            'image' => $image,
            'description' => trim($_POST['description'] ?? '')
        ];
    }

    private function travelGuideDataFromRequest($fallbackImage = 'default-package.jpg')
    {
        $image = trim($_POST['image'] ?? '') ?: $fallbackImage;

        if (!empty($_FILES['image_file']['name']) && is_uploaded_file($_FILES['image_file']['tmp_name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed, true)) {
                $newName = 'guide_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $target = __DIR__ . '/../../public/assets/images/' . $newName;

                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target)) {
                    $image = $newName;
                }
            }
        }

        return [
            'title' => trim($_POST['title'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'best_time' => trim($_POST['best_time'] ?? ''),
            'estimated_budget' => (float)($_POST['estimated_budget'] ?? 0),
            'description' => trim($_POST['description'] ?? ''),
            'tips' => trim($_POST['tips'] ?? ''),
            'image' => $image,
            'status' => trim($_POST['status'] ?? 'Published')
        ];
    }
    
    public function replyCustomTrip($id)
{
    requireRole('admin');

    $reply = trim($_POST['reply'] ?? '');
    $status = $_POST['status'] ?? 'Pending';

    if ($reply === '') {
        flash('error', 'Please enter a reply.');
        $this->redirect('admin/customTrips');
    }

        $this->model('TripModel')->replyOnlyPending($id, $reply, $status);
    $trip = $this->model('TripModel')->find($id);

if ($trip && in_array($status, ['Approved', 'Rejected'], true)) {

    $subject = $status === 'Approved'
        ? 'Customized Trip Approved - GlobeTrek Adventures'
        : 'Customized Trip Rejected - GlobeTrek Adventures';

    $message = "Dear {$trip['name']},\n\n";

    if ($status === 'Approved') {
        $message .= "Your customized trip request has been approved.\n\n";
    } else {
        $message .= "Your customized trip request has been rejected.\n\n";
    }

    $message .= "Destination: {$trip['destination']}\n";
    $message .= "Travel Date: {$trip['travel_date']}\n";
    $message .= "Days: {$trip['days']}\n";
    $message .= "Persons: {$trip['persons']}\n";
    $message .= "Budget: LKR " . number_format($trip['budget'], 2) . "\n\n";
    $message .= "Staff Reply:\n{$reply}\n\n";
    $message .= "Thank you for choosing GlobeTrek Adventures.";

    sendNotificationEmail(
        $trip['email'],
        $subject,
        $message
    );
}

    flash('success', 'Custom trip reply saved successfully.');
    $this->redirect('admin/customTrips');
}
public function accommodations()
{
    requireRole('admin');

    $this->view('admin/accommodations', [
        'items' => $this->model('Accommodation')->all(),
        'packages' => $this->model('Package')->all()
    ]);
}

public function saveAccommodation()
{
    requireRole('admin');

    $this->model('Accommodation')->create($_POST);

    flash('success', 'Accommodation added successfully.');

    $this->redirect('admin/accommodations');
}

public function editAccommodation($id)
{
    requireRole('admin');

    $item = $this->model('Accommodation')->find($id);

    if (!$item) {
        flash('error', 'Accommodation not found.');
        $this->redirect('admin/accommodations');
        return;
    }

    $this->view('admin/edit_accommodation', [
        'item' => $item,
        'packages' => $this->model('Package')->all()
    ]);
}

public function updateAccommodation($id)
{
    requireRole('admin');

    $this->model('Accommodation')->update($id, $_POST);

    flash('success', 'Accommodation updated successfully.');

    $this->redirect('admin/accommodations');
}

public function deleteAccommodation($id)
{
    requireRole('admin');

    $this->model('Accommodation')->delete($id);

    flash('success', 'Accommodation deleted successfully.');

    $this->redirect('admin/accommodations');
}
}
?>