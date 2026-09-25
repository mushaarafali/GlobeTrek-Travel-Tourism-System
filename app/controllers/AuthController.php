<?php
require_once __DIR__ . '/../helpers/mail_helper.php';

class AuthController extends Controller
{
public function login()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            $this->view('auth/login', [
                'error' => 'Please enter a valid email and password.'
            ]);
            return;
        }

        $userModel = $this->model('User');
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'] ?? '',
                'role'  => $user['role']
            ];

            if ($user['role'] === 'admin') {
                $this->redirect('admin/dashboard');
            }

            if ($user['role'] === 'staff') {
                $this->redirect('staff/dashboard');
            }

            $this->redirect('package/index');
        }

        if ($user) {
            sendNotificationEmail(
                $user['email'],
                'Login Security Alert - GlobeTrek Adventures',
                "Dear {$user['name']},\n\nA failed login attempt was detected on your account.\n\nIf this was not you, please reset your password immediately.\n\nGlobeTrek Adventures"
            );
        }

        sendNotificationEmail(
            ADMIN_EMAIL,
            'Security Alert - Failed Login Attempt',
            "Failed login attempt detected.\n\nEmail: {$email}\nTime: " . date('Y-m-d H:i:s') . "\nSystem: GlobeTrek Adventures"
        );

        $this->view('auth/login', [
            'error' => 'Invalid email or password.'
        ]);
        return;
    }

    $this->view('auth/login');
}

public function register()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = preg_replace('/\s+/', '', trim($_POST['phone'] ?? ''));
        $password = $_POST['password'] ?? '';

        $passwordPattern = '/^(?=.*[A-Z])(?=.*[^A-Za-z0-9]).{8,}$/';

        $sriLankaPhonePattern = '/^0[0-9]{9}$/';
        $internationalPhonePattern = '/^\+[1-9][0-9]{7,14}$/';

        $validPhone =
            preg_match($sriLankaPhonePattern, $phone) ||
            preg_match($internationalPhonePattern, $phone);

        if ($name === '') {
            flash('error', 'Full name is required.');
            $this->redirect('auth/register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please enter a valid email address.');
            $this->redirect('auth/register');
        }

        if (!$validPhone) {
            flash('error', 'Enter valid Sri Lankan or international phone number.');
            $this->redirect('auth/register');
        }

        if (!preg_match($passwordPattern, $password)) {
            flash('error', 'Password must be at least 8 characters, include 1 uppercase letter and 1 symbol.');
            $this->redirect('auth/register');
        }

        $userModel = $this->model('User');

        if ($userModel->findByEmail($email)) {
            flash('error', 'Email already exists.');
            $this->redirect('auth/register');
        }

        $created = $userModel->create($name,$email,$phone,$password,'customer');

        if (!$created) {
            flash('error', 'Registration failed.');
            $this->redirect('auth/register');
        }

        sendNotificationEmail(
            $email,
            'Welcome to GlobeTrek Adventures',
            "Dear {$name},\n\nWelcome to GlobeTrek Adventures.\n\nYour account has been created successfully.\n\nYou can now explore packages, book trips, and customize your travel plans.\n\nThank you!"
        );

        flash('success', 'Registration completed. Please login.');
        $this->redirect('auth/login');
    }

    $this->view('auth/register');
}

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = trim($_POST['email'] ?? '');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->view('auth/forgot_password', ['error' => 'Invalid email']);
                return;
            }

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user) {
                $otp = (string)random_int(100000, 999999);
                $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

                $userModel->saveResetOtp($email, $otp, $expiresAt);

                
                sendNotificationEmail(
                    $email,
                    'Password Reset OTP',
                    "Dear {$user['name']},\n\nYour OTP is: {$otp}\n\nValid for 10 minutes.\n\nGlobeTrek Adventures"
                );
            }

            $_SESSION['reset_email'] = $email;

            flash('success', 'OTP sent to your email.');
            $this->redirect('auth/resetPassword');
        }

        $this->view('auth/forgot_password');
    }

    public function resetPassword()
    {
        $email = $_SESSION['reset_email'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = trim($_POST['email'] ?? $email);
            $otp = trim($_POST['otp'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^\d{6}$/', $otp)) {
                $this->view('auth/reset_password', ['error' => 'Invalid OTP', 'email' => $email]);
                return;
            }

            if (strlen($password) < 6 || $password !== $confirmPassword) {
                $this->view('auth/reset_password', ['error' => 'Password mismatch', 'email' => $email]);
                return;
            }

            $userModel = $this->model('User');

            if (!$userModel->verifyResetOtp($email, $otp)) {
                $this->view('auth/reset_password', ['error' => 'OTP expired', 'email' => $email]);
                return;
            }

            $userModel->updatePasswordByEmail($email, $password);

            unset($_SESSION['reset_email']);

            
            sendNotificationEmail(
                $email,
                'Password Changed Successfully',
                "Your password was changed on " . date('Y-m-d H:i:s') . ".\n\nIf this was not you, contact support immediately."
            );

            flash('success', 'Password reset successful.');
            $this->redirect('auth/login');
        }

        $this->view('auth/reset_password', ['email' => $email]);
    }

    public function logout()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}
?>