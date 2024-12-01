<?php
require 'App/Libraries/PHPMailer/PHPMailer.php';
require 'App/Libraries/PHPMailer/SMTP.php';
require 'App/Libraries/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPasswordController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once CONFIG . 'dbh.inc.php';
        $this->db = $conn;
    }

    public function index()
    {
        $this->view('forgotPassword');
    }

    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
            $email = trim($_POST['email']);
            $errors = [];


            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['emailError'] = "Invalid email address. Please try again.";
                $this->view('forgotPassword', $errors);
            }

            $stmt = $this->db->prepare("SELECT * FROM users WHERE Email = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $errors['emailError'] = "No account is registered with this email address.";
                $this->view('forgotPassword', $errors);
            }

            $expFormat = mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y"));
            $expDate = date("Y-m-d H:i:s", $expFormat);
            $key = md5((2418 * 2) . $email);
            $addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
            $resetKey = $key . $addKey;

            $stmt = $this->db->prepare("INSERT INTO password_reset_temp (email, resetKey, expDate) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $email, $resetKey, $expDate);
            $stmt->execute();

            $this->sendResetEmail($email, $resetKey);

            
        } else {
            $this->view('forgotPassword');
        }
    }

    private function sendResetEmail($email, $resetKey)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = APP_EMAIL; 
            $mail->Password = APP_PASSWORD;   
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            

            $mail->setFrom(APP_EMAIL, 'Plagiarism Detection'); 
            $mail->addAddress($email); 
            $mail->isHTML(true); 
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "
            <p>Dear User,</p>
            <p>You requested a password reset. Click the link below to reset your password:</p>
            <p><a href='" . redirect("resetPassword/index/") . $resetKey . "'>Reset Password</a></p>
            <p>If you did not request this, you can safely ignore this email. The link will expire in 24 hours.</p>
        ";

            $mail->send();
            $errors['success'] = "Check your email for a reset password link, the link will expire in 24 hours.";
            $this->view('forgotPassword', $errors);
        } catch (Exception $e) {
            $errors['emailError'] = "Email couldn't be sent";
            $this->view('forgotPassword', $errors);
        }
    }
}
