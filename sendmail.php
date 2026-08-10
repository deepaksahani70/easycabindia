<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "1";
    // ✅ Form data capture
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $phone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $message = isset($_POST['comments']) ? $_POST['comments'] : '';
    
    // ✅ Email details
    $to = "easycabindiaa@gmail.com";
    $subject = "New Enquiry from Easy Cab India - " . $first_name . " " . $last_name;
    
    // ✅ Email body
    $body = "You have received a new enquiry from your website:\n\n";
    $body .= "Name: " . $first_name . " " . $last_name . "\n";
    $body .= "Phone: " . $phone . "\n";
    $body .= "Email: " . $email . "\n\n";
    $body .= "Message:\n" . $message . "\n";
    
    // ✅ Headers
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    echo "2";
    // ✅ Send email
    if (mail($to, $subject, $body, $headers)) {
        // Success
        echo "mailsend";
    } else {
        // Error
        echo "mailsend error";
    }
} else {
    // Agar koi directly sendmail.php open kare
    //header("Location: index.html");
    //exit();
    echo "error not post";
}
?>