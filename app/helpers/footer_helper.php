<?php
require_once __DIR__ . '/../core/Database.php';

function footer_setting_value($key, $default = '') {
    try {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ? LIMIT 1");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    } catch (Throwable $e) {
        return $default;
    }
}

function footer_settings() {
    return [
        'company_name' => footer_setting_value('company_name', 'GlobeTrek Adventures'),
        'tagline' => footer_setting_value('tagline', 'Your trusted partner for premium travel planning, bookings, and unforgettable tour experiences.'),
        'phone' => footer_setting_value('phone', '075 4444 789'),
        'email' => footer_setting_value('email', 'globetrekproject@gmail.com'),
        'address' => footer_setting_value('address', 'Negombo, Sri Lanka'),
        'facebook' => footer_setting_value('facebook', '#'),
        'instagram' => footer_setting_value('instagram', '#'),
        'whatsapp' => footer_setting_value('whatsapp', '#'),
    ];
}
?>
