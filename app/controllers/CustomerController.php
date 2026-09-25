<?php

class CustomerController extends Controller
{
    public function dashboard()
    {
        requireRole('customer');

        $bookingModel = $this->model('Booking');

        $this->view('customer/dashboard', [
            'bookings' => $bookingModel->byUser($_SESSION['user']['id'])
        ]);
    }

    public function profile()
    {
        requireRole('customer');

        $userModel = $this->model('User');

        $customer = $userModel->findById($_SESSION['user']['id']);

        $this->view('customer/profile', [
            'customer' => $customer
        ]);
    }

    public function updateProfile()
    {
        requireRole('customer');

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please enter valid profile details.');
            $this->redirect('customer/profile');
            return;
        }

        $userModel = $this->model('User');

        $userModel->updateCustomer(
            $_SESSION['user']['id'],
            $name,
            $email,
            $phone
        );

        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['phone'] = $phone;

        flash('success', 'Profile updated successfully.');
        $this->redirect('customer/profile');
    }

    public function cancelBooking($id = null)
    {
        requireRole('customer');

        if (!$id) {
            $this->redirect('customer/dashboard');
            return;
        }

        $bookingModel = $this->model('Booking');

        $bookingModel->cancelByCustomer($id, $_SESSION['user']['id']);

        flash('success', 'Booking cancelled successfully.');
        $this->redirect('customer/dashboard');
    }

    public function customTripStatus()
    {
        requireRole('customer');

        $customTripModel = $this->model('TripModel');

        $trips = $customTripModel->getByUserId($_SESSION['user']['id']);

        $this->view('customer/custom_trip_status', [
            'trips' => $trips
        ]);
    }

    public function inquiryStatus()
    {
        requireRole('customer');

        $inquiryModel = $this->model('Inquiry');

        $inquiries = $inquiryModel->getByUserId($_SESSION['user']['id']);

        $this->view('customer/inquiry_status', [
            'inquiries' => $inquiries
        ]);
    }

}