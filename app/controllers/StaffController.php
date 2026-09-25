<?php
require_once __DIR__ . '/../helpers/mail_helper.php';

class StaffController extends Controller
{
   public function dashboard()
{
    requireRole('staff');

    $bookingModel = $this->model('Booking');
    $tripModel    = $this->model('TripModel');
    $inquiryModel = $this->model('Inquiry');
    $packageModel = $this->model('Package');

    $bookings    = $bookingModel->all();
    $customTrips = $tripModel->all();
    $inquiries   = $inquiryModel->all();
    $packages    = $packageModel->all();

    $revenue = 0;

    foreach ($bookings as $booking) {
        $status = strtolower($booking['status'] ?? '');

        if ($status === 'confirmed' || $status === 'completed') {
            $revenue += (float)($booking['total_amount'] ?? 0);
        }
    }

    $this->view('staff/dashboard', [
        'bookings'    => $bookings,
        'customTrips' => $customTrips,
        'inquiries'   => $inquiries,
        'packages'    => $packages,
        'revenue'     => $revenue
    ]);
}

    public function customTrips()
    {
        requireRole('staff');

        $model = $this->model('TripModel');

        $this->view('staff/custom_trips', [
            'trips' => $model->all()
        ]);
    }

    public function packages()
    {
        requireRole('staff');
        $this->view('staff/packages', ['packages' => $this->model('Package')->all()]);
    }

    public function savePackage()
    {
        requireRole('staff');

        $data = $this->packageDataFromRequest();

        if (!$data['title'] || !$data['destination'] || $data['price'] <= 0) {
            flash('error', 'Please enter valid package details.');
            $this->redirect('staff/packages');
        }

        $this->model('Package')->create($data);
        flash('success', 'Package saved successfully.');
        $this->redirect('staff/packages');
    }



    public function bookings()
    {
        requireRole('staff');
        $this->view('admin/bookings', ['bookings' => $this->model('Booking')->all()]);
    }

    public function status($id, $status)
    {
        requireRole('staff');

        $status = ucfirst(strtolower(trim($status)));
        $allowed = ['Confirmed', 'Cancelled', 'Pending'];

        if (!in_array($status, $allowed, true)) {
            flash('error', 'Invalid booking status.');
            $this->redirect('staff/bookings');
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
        $this->redirect('staff/bookings');
    }

    public function inquiries()
    {
        requireRole('staff');
        $this->view('admin/inquiries', ['inquiries' => $this->model('Inquiry')->all()]);
    }

    public function replyInquiry($id)
    {
        requireRole('staff');
        $this->model('Inquiry')->reply($id, $_POST['reply'] ?? '');
        flash('success', 'Inquiry reply saved.');
        $this->redirect('staff/inquiries');
    }

    public function prediction()
    {
        requireRole('staff');
        $prediction = $this->model('Booking')->futurePrediction();
        $this->view('admin/prediction', ['prediction' => $prediction]);
    }

    public function editPackage($id)
    {
        requireRole('staff');

        $package = $this->model('Package')->find($id);

        if (!$package) {
            flash('error', 'Package not found');
            $this->redirect('staff/packages');
        }

        $this->view('staff/edit_package', [
            'package' => $package
        ]);
    }

    public function updatePackage($id)
    {
        requireRole('staff');

        $current = $this->model('Package')->find($id);

        $this->model('Package')->update(
            $id,
            $this->packageDataFromRequest($current['image'] ?? 'default-package.jpg')
        );

        flash('success', 'Package updated successfully');
        $this->redirect('staff/packages');
    }

    public function customers()
    {
        requireRole($_SESSION['user']['role']);

        $customers = $this->model('User')->customersOnly();

        $this->view($_SESSION['user']['role'] . '/customers', [
            'customers' => $customers
        ]);
    }

    public function editCustomer($id)
    {
        requireRole($_SESSION['user']['role']);

        $customer = $this->model('User')->findById($id);

        if (!$customer || $customer['role'] !== 'customer') {
            flash('error', 'Customer not found.');
            $this->redirect($_SESSION['user']['role'] . '/customers');
        }

        $this->view($_SESSION['user']['role'] . '/edit_customer', [
            'customer' => $customer
        ]);
    }

    public function updateCustomer($id)
    {
        requireRole($_SESSION['user']['role']);

        $this->model('User')->updateCustomer(
            $id,
            trim($_POST['name']),
            trim($_POST['email']),
            trim($_POST['phone'])
        );

        flash('success', 'Customer updated successfully.');
        $this->redirect($_SESSION['user']['role'] . '/customers');
    }


    public function reports()
    {
        requireRole('staff');

        $booking = $this->model('Booking');

        $this->view('admin/reports', [
            'revenue' => $booking->revenueSummary(),
            'statuses' => $booking->statusSummary(),
            'packages' => $booking->packagePerformance(),
            'payments' => [],
            'prediction' => $booking->futurePrediction(),
            'customers' => []
        ]);
    }


   public function transport()
    {
        requireRole('staff');

        $this->view('staff/transport', [
            'items' => $this->model('Transport')->all()
        ]);
    }

    public function saveTransport()
    {
        requireRole('staff');

        $this->model('Transport')->create($_POST);

        flash('success', 'Transport saved successfully.');
        $this->redirect('staff/transport');
    }

    public function editTransport($id)
    {
        requireRole('staff');

        $item = $this->model('Transport')->find($id);

        if (!$item) {
            flash('error', 'Transport not found.');
            $this->redirect('staff/transport');
        }

        $this->view('staff/edit_transport', [
            'item' => $item
        ]);
    }

    public function updateTransport($id)
    {
        requireRole('staff');

        $this->model('Transport')->update($id, $_POST);

        flash('success', 'Transport updated successfully.');
        $this->redirect('staff/transport');
    }

    public function travelGuides()
    {
        requireRole('staff');
        $this->view('staff/travel_guides', ['guides' => $this->model('TravelGuide')->all()]);
    }

    public function saveTravelGuide()
    {
        requireRole('staff');

        $data = $this->travelGuideDataFromRequest();

        if (!$data['title'] || !$data['location'] || !$data['description']) {
            flash('error', 'Please enter valid travel guide details.');
            $this->redirect('staff/travelGuides');
        }

        $this->model('TravelGuide')->create($data);
        flash('success', 'Travel guide saved successfully.');
        $this->redirect('staff/travelGuides');
    }
    public function editTravelGuide($id = null)
{
    requireRole('staff');

    if (!$id) {
        $this->redirect('staff/travelGuides');
    }

    $guide = $this->model('TravelGuide')->find($id);

    if (!$guide) {
        $this->view('errors/404');
        return;
    }

    $this->view('staff/edit_travel_guide', [
        'guide' => $guide
    ]);
}

    public function updateTravelGuide($id = null)
{
    if (!$id) {
        $this->redirect($_SESSION['user']['role'] . '/travelGuides');
    }

    $roleBase = $_SESSION['user']['role'];

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
    requireRole('staff');

    $reply = trim($_POST['reply'] ?? '');
    $status = $_POST['status'] ?? 'Pending';

    if ($reply === '') {
        flash('error', 'Please enter a reply.');
        $this->redirect('staff/customTrips');
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
    $this->redirect('staff/customTrips');
}
public function accommodations()
{
    requireRole('staff');

    $this->view('staff/accommodations', [
        'items' => $this->model('Accommodation')->all(),
        'packages' => $this->model('Package')->all()
    ]);
}

public function saveAccommodation()
{
    requireRole('staff');

    $this->model('Accommodation')->create($_POST);

    flash('success', 'Accommodation saved successfully.');
    $this->redirect('staff/accommodations');
}

public function editAccommodation($id)
{
    requireRole('staff');

    $item = $this->model('Accommodation')->find($id);

    if (!$item) {
        flash('error', 'Accommodation not found.');
        $this->redirect('staff/accommodations');
    }

    $this->view('staff/edit_accommodation', [
        'item' => $item,
        'packages' => $this->model('Package')->all()
    ]);
}

public function updateAccommodation($id)
{
    requireRole('staff');

    $this->model('Accommodation')->update($id, $_POST);

    flash('success', 'Accommodation updated successfully.');
    $this->redirect('staff/accommodations');
}


}
?>