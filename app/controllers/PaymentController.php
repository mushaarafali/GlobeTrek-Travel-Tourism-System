<?php

class PaymentController extends Controller
{
    public function method()
    {
        requireRole('customer');

        $pending = $_SESSION['pending_booking'] ?? null;

        if (!$pending) {
            flash('error', 'No pending booking found.');
            $this->redirect('package/index');
            return;
        }

        $booking = $this->preparePendingBooking($pending);

        $this->view('payment/method', $booking);
    }

    public function card()
    {
        requireRole('customer');

        $pending = $_SESSION['pending_booking'] ?? null;

        if (!$pending) {
            flash('error', 'No pending booking found.');
            $this->redirect('package/index');
            return;
        }

        $booking = $this->preparePendingBooking($pending);

        $this->view('payment/card', $booking);
    }

    public function cash()
    {
        requireRole('customer');

        $pending = $_SESSION['pending_booking'] ?? null;

        if (!$pending) {
            flash('error', 'No pending booking found.');
            $this->redirect('package/index');
            return;
        }

        $pending['payment_method'] = 'Cash on Arrival';

        $bookingId = $this->model('Booking')->create(
            $_SESSION['user']['id'],
            $pending
        );

        unset($_SESSION['pending_booking']);

        $this->model('Booking')->updatePaymentMethod($bookingId, 'Cash on Arrival');

        header('Location: ' . BASE_URL . '/payment/success/' . $bookingId);
        exit;
    }

    public function store()
    {
        requireRole('customer');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('package/index');
            return;
        }

        $pending = $_SESSION['pending_booking'] ?? null;

        if (!$pending) {
            flash('error', 'No pending booking found.');
            $this->redirect('package/index');
            return;
        }

        $cardHolder = trim($_POST['card_holder'] ?? '');
        $cardNumber = preg_replace('/\D/', '', $_POST['card_number'] ?? '');
        $expiryDate = trim($_POST['expiry_date'] ?? '');
        $cvv = trim($_POST['cvv'] ?? '');

        $bookingData = $this->preparePendingBooking($pending);

        if ($cardHolder === '') {
            $this->paymentError($bookingData, 'Please enter card holder name.');
            return;
        }

        if (!preg_match('/^\d{16}$/', $cardNumber)) {
            $this->paymentError($bookingData, 'Card number must contain exactly 16 digits.');
            return;
        }

        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiryDate)) {
            $this->paymentError($bookingData, 'Expiry date format must be MM/YY.');
            return;
        }

        [$month, $year] = explode('/', $expiryDate);

        $expiryYear = (int)('20' . $year);
        $expiryMonth = (int)$month;

        if (
            $expiryYear < (int)date('Y') ||
            ($expiryYear === (int)date('Y') && $expiryMonth < (int)date('m'))
        ) {
            $this->paymentError($bookingData, 'Card expired. Please use valid expiry date.');
            return;
        }

        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            $this->paymentError($bookingData, 'CVV must contain 3 or 4 digits.');
            return;
        }

        $pending['payment_method'] = 'Card Payment';

        $bookingId = $this->model('Booking')->create(
            $_SESSION['user']['id'],
            $pending
        );

        $this->model('Payment')->create([
            'booking_id' => $bookingId,
            'user_id' => $_SESSION['user']['id'],
            'card_holder' => $cardHolder,
            'card_last4' => substr($cardNumber, -4),
            'expiry_month' => sprintf('%02d', $expiryMonth),
            'expiry_year' => $expiryYear,
            'amount' => $pending['total_amount']
        ]);

        $this->model('Booking')->updatePaymentMethod($bookingId, 'Card Payment');
        $this->model('Booking')->updateStatus($bookingId, 'Paid');

        unset($_SESSION['pending_booking']);

        header('Location: ' . BASE_URL . '/payment/success/' . $bookingId);
        exit;
    }

    public function success($bookingId)
    {
        requireRole('customer');

        $booking = $this->model('Booking')->findWithDetails($bookingId);

        if (!$booking || $booking['user_id'] != $_SESSION['user']['id']) {
            flash('error', 'Invalid booking.');
            $this->redirect('customer/dashboard');
            return;
        }

        $this->view('payment/success', [
            'booking' => $booking
        ]);
    }

    public function invoice($bookingId)
    {
        requireRole('customer');

        $booking = $this->model('Booking')->findWithDetails($bookingId);

        if (!$booking || $booking['user_id'] != $_SESSION['user']['id']) {
            flash('error', 'Invalid booking.');
            $this->redirect('customer/dashboard');
            return;
        }

        $payment = $this->model('Payment')->findByBooking($bookingId);

        $this->view('payment/invoice', [
            'booking' => $booking,
            'payment' => $payment
        ]);
    }

    private function preparePendingBooking($pending)
    {
        $package = $this->model('Package')->find($pending['package_id']);

        $accommodation = !empty($pending['accommodation_id'])
            ? $this->model('Accommodation')->find($pending['accommodation_id'])
            : null;

        $transport = !empty($pending['transport_id'])
            ? $this->model('Transport')->find($pending['transport_id'])
            : null;

        $persons = (int)($pending['persons'] ?? 1);
        $days = (int)($package['days'] ?? 1);
        $days = max($days, 1);
        $nights = max($days - 1, 1);

        $packagePrice = (float)($package['price'] ?? 0);
        $roomPrice = (float)($accommodation['room_price'] ?? 0);
        $transportPrice = (float)($transport['price_per_day'] ?? 0);

        $packageTotal = $packagePrice * $persons;
        $accommodationTotal = $roomPrice * $persons * $nights;
        $transportTotal = $transportPrice * $days;
        $grandTotal = $packageTotal + $accommodationTotal + $transportTotal;

        return [
            'booking' => array_merge($pending, [
                'id' => 0,
                'package_title' => $package['title'] ?? '',
                'destination' => $package['destination'] ?? '',
                'days' => $days,
                'price' => $packagePrice,
                'hotel_name' => $accommodation['hotel_name'] ?? null,
                'room_type' => $accommodation['room_type'] ?? null,
                'room_price' => $roomPrice,
                'vehicle_no' => $transport['vehicle_no'] ?? null,
                'vehicle_type' => $transport['vehicle_type'] ?? null,
                'price_per_day' => $transportPrice
            ]),
            'persons' => $persons,
            'days' => $days,
            'nights' => $nights,
            'packagePrice' => $packagePrice,
            'packageTotal' => $packageTotal,
            'roomPrice' => $roomPrice,
            'accommodationTotal' => $accommodationTotal,
            'transportPrice' => $transportPrice,
            'transportTotal' => $transportTotal,
            'grandTotal' => $grandTotal
        ];
    }

    private function paymentError($bookingData, $message)
    {
        $bookingData['error'] = $message;
        $this->view('payment/card', $bookingData);
    }
}
?>