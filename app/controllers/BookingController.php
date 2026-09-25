<?php

class BookingController extends Controller
{
public function create($packageId)
{
    requireLogin();

    $package = $this->model('Package')->find($packageId);

    if (!$package) {
        flash('error', 'Package not found.');
        $this->redirect('package/index');
    }

    $transports = $this->model('Transport')->all();

    $accommodations = $this->model('Accommodation')->byPackage($packageId);

    $this->view('booking/create', [
        'package'        => $package,
        'transports'     => $transports,
        'accommodations' => $accommodations
    ]);
}

public function store()
{
    requireRole('customer');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('package/index');
        return;
    }

    $packageId  = $_POST['package_id'] ?? '';
    $travelDate = $_POST['travel_date'] ?? '';
    $persons    = (int)($_POST['persons'] ?? 1);
    $customNote = trim($_POST['custom_note'] ?? '');

    $transportId = !empty($_POST['transport_id'])
        ? (int)$_POST['transport_id']
        : null;

    $accommodationId = !empty($_POST['accommodation_id'])
        ? (int)$_POST['accommodation_id']
        : null;

    $package = $this->model('Package')->find($packageId);

    if (!$package) {
        flash('error', 'Invalid package selected.');
        $this->redirect('package/index');
        return;
    }

    if (
        empty($travelDate) ||
        strtotime($travelDate) <= strtotime(date('Y-m-d'))
    ) {
        flash('error', 'Only future travel dates are allowed.');
        $this->redirect('booking/create/' . $packageId);
        return;
    }

    if ($persons < 1) {
        flash('error', 'Please enter valid number of persons.');
        $this->redirect('booking/create/' . $packageId);
        return;
    }

    $alreadyBooked = $this->model('Booking')->existsForCustomerDate(
        $_SESSION['user']['id'],
        $travelDate
    );

    if ($alreadyBooked) {

        flash(
            'error',
            'You already have a booking on this travel date.'
        );

        $this->redirect('booking/create/' . $packageId);

        return;
    }

    $days = (int)($package['days'] ?? 1);
    $days = max($days, 1);

    $nights = max($days - 1, 1);

    /* PACKAGE */
    $packagePrice = (float)($package['price'] ?? 0);

    $packageTotal = $packagePrice * $persons;

    /* TRANSPORT */
    $transportCharge = 0;

    if (!empty($transportId)) {

        $transport = $this->model('Transport')->find($transportId);

        if (
            !$transport ||
            ($transport['status'] ?? '') !== 'Available'
        ) {

            flash('error', 'Invalid transport selected.');

            $this->redirect('booking/create/' . $packageId);

            return;
        }

        $pricePerDay = (float)($transport['price_per_day'] ?? 0);

        $transportCharge = $pricePerDay * $days;
    }

    /* ACCOMMODATION */
    $accommodationCharge = 0;

    if (!empty($accommodationId)) {

        $accommodation = $this->model('Accommodation')->find($accommodationId);

        if (
            !$accommodation ||
            ($accommodation['status'] ?? '') !== 'Available'
        ) {

            flash('error', 'Invalid accommodation selected.');

            $this->redirect('booking/create/' . $packageId);

            return;
        }

        if (
            (int)$accommodation['package_id']
            !==
            (int)$packageId
        ) {

            flash(
                'error',
                'Selected accommodation does not belong to this package.'
            );

            $this->redirect('booking/create/' . $packageId);

            return;
        }

        $roomPrice = (float)($accommodation['room_price'] ?? 0);

        $accommodationCharge =
            $roomPrice *
            $persons *
            $nights;
    }

    /* GRAND TOTAL */
    $total = $packageTotal + $transportCharge + $accommodationCharge;

   
    $_SESSION['pending_booking'] = [

        'package_id'       => $packageId,

        'travel_date'      => $travelDate,

        'persons'          => $persons,

        'custom_note'      => $customNote,

        'total_amount'     => $total,

        'payment_method'   => 'Pending',

        'transport_id'     => $transportId,

        'accommodation_id' => $accommodationId
    ];

    /* GO TO PAYMENT METHOD PAGE */
    $this->redirect('payment/method');
}
    public function history()
    {
        requireRole('customer');

        $bookingModel = $this->model('Booking');
        $bookings = $bookingModel->getByUserId($_SESSION['user']['id']);

        $this->view('booking/history', [
            'bookings' => $bookings
        ]);
    }

    public function cancel($bookingId)
    {
        requireRole('customer');

        $booking = $this->model('Booking')->findWithDetails($bookingId);

        if (!$booking || (int)$booking['user_id'] !== (int)$_SESSION['user']['id']) {
            flash('error', 'Invalid booking.');
            $this->redirect('booking/history');
            return;
        }

        if (in_array($booking['status'], ['Paid', 'Completed'], true)) {
            flash('error', 'Paid/completed bookings cannot be cancelled online. Please contact support.');
            $this->redirect('booking/history');
            return;
        }

        $this->model('Booking')->cancelByCustomer($bookingId, $_SESSION['user']['id']);

        flash('success', 'Booking cancelled successfully.');
        $this->redirect('booking/history');
    }

    public function notFound()
    {
        http_response_code(404);

        flash('error', 'Booking page not found.');
        $this->redirect('package/index');
    }
    
}