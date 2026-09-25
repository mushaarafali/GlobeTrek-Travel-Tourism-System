<?php
require_once __DIR__ . '/../core/Model.php';

class User extends Model
{
    public function create($name, $email, $phone, $password, $role = 'customer')
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, phone, password, role)
             VALUES (?, ?, ?, ?, ?)"
        );

        return $stmt->execute([$name, $email, $phone, $hash, $role]);
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function staffOnly()
    {
        $stmt = $this->db->prepare(
            "SELECT id, name, email, phone, role, created_at 
             FROM users 
             WHERE role = 'staff' 
             ORDER BY id DESC"
        );

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createStaff($name, $email, $password)
    {
        return $this->create($name, $email, '', $password, 'staff');
    }

    public function deleteStaff($id)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM users 
             WHERE id = ? AND role = 'staff'"
        );

        return $stmt->execute([(int)$id]);
    }
    public function updateStaff($id, $name, $email)
{
    $stmt = $this->db->prepare("
        UPDATE users 
        SET name = ?, email = ? 
        WHERE id = ? AND role = 'staff'
    ");

    return $stmt->execute([$name, $email, $id]);
}
    public function saveResetOtp($email, $otp, $expiresAt)
    {
        $hash = password_hash($otp, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            'UPDATE users 
             SET reset_otp = ?, reset_expires = ? 
             WHERE email = ?'
        );

        return $stmt->execute([$hash, $expiresAt, $email]);
    }

    public function verifyResetOtp($email, $otp)
    {
        $user = $this->findByEmail($email);

        if (!$user || empty($user['reset_otp']) || empty($user['reset_expires'])) {
            return false;
        }

        if (strtotime($user['reset_expires']) < time()) {
            return false;
        }

        return password_verify($otp, $user['reset_otp']);
    }

    public function updatePasswordByEmail($email, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            'UPDATE users 
             SET password = ?, reset_otp = NULL, reset_expires = NULL 
             WHERE email = ?'
        );

        return $stmt->execute([$hash, $email]);
    }

    public function clearResetOtp($email)
    {
        $stmt = $this->db->prepare(
            'UPDATE users 
             SET reset_otp = NULL, reset_expires = NULL 
             WHERE email = ?'
        );

        return $stmt->execute([$email]);
    }

    public function all()
    {
        return $this->db
            ->query('SELECT id, name, email, phone, role, created_at FROM users ORDER BY id DESC')
            ->fetchAll();
    }

    public function stats()
    {
        return $this->db
            ->query("SELECT role, COUNT(*) count FROM users GROUP BY role")
            ->fetchAll();
    }
    public function customersOnly()
{
    $stmt = $this->db->prepare(
        "SELECT id, name, email, phone, role, created_at 
         FROM users 
         WHERE role = 'customer' 
         ORDER BY id DESC"
    );

    $stmt->execute();
    return $stmt->fetchAll();
}

public function findById($id)
{
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([(int)$id]);
    return $stmt->fetch();
}

public function updateCustomer($id, $name, $email, $phone)
{
    $stmt = $this->db->prepare(
        "UPDATE users 
         SET name = ?, email = ?, phone = ? 
         WHERE id = ? AND role = 'customer'"
    );

    return $stmt->execute([$name, $email, $phone, (int)$id]);
}

public function deleteCustomer($id)
{
    $stmt = $this->db->prepare(
        "DELETE FROM users 
         WHERE id = ? AND role = 'customer'"
    );

    return $stmt->execute([(int)$id]);
}
public function updateProfile($id, $name, $email, $phone)
{
    $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
    return $stmt->execute([$name, $email, $phone, (int)$id]);
}

}
?>