<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

ini_set('display_errors', 0);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

$dotenv = Dotenv::createImmutable(__DIR__); 
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /contact-us/');
    exit;
}
if (!empty($_POST['website'])) {
    echo json_encode([
        "status"  => "error",
        "message" => "Spam detected."
    ]);
    exit;
}

$secretKey = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';
 if (empty($_POST['g-recaptcha-response'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Please complete the Google reCAPTCHA."
    ]);
    exit;
}

$captchaResponse = $_POST['g-recaptcha-response'];
$ch = curl_init('https://www.google.com/recaptcha/api/siteverify');

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
        'secret' => $secretKey,
        'response' => $captchaResponse
    ],
    CURLOPT_TIMEOUT => 30
]);

$verify = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode([
        "status" => "error",
        "message" => curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

curl_close($ch);
 if ($verify === false) {
    echo json_encode([
        "status" => "error",
        "message" => "Unable to verify Google reCAPTCHA."
    ]);
    exit;
}
$result = json_decode($verify, true);
 if (
    !isset($result['success']) ||
    $result['success'] !== true
) {

    echo json_encode([
        "status" => "error",
        "message" => "Google reCAPTCHA verification failed."
    ]);

    exit;
}

$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$phone      = trim($_POST['telephone'] ?? '');
$message    = trim($_POST['comments'] ?? '');

if (
    $first_name === '' ||
    $last_name === '' ||
    $email === '' ||
    $phone === '' ||
    $message === ''
) {

    echo json_encode([
        "status" => "error",
        "message" => "Please fill in all required fields."
    ]);
    exit;
}

 if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "message" => "Please enter a valid email address."
    ]);
    exit;
}


$first_name = htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8');
$last_name  = htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8');
$email      = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$phone      = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$message    = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

$mail = new PHPMailer(true);
try {

    $mail->isSMTP();
    $mail->Host       = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['SMTP_USERNAME'];
    $mail->Password   = $_ENV['SMTP_PASSWORD'];
    $mail->Port       = (int) $_ENV['SMTP_PORT'];

    // Automatically use SSL (465) or TLS (587)
    if ((int)$_ENV['SMTP_PORT'] === 465) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } else {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }
    $mail->CharSet = 'UTF-8';
    $mail->setFrom(
        $_ENV['SMTP_FROM'],
        $_ENV['SMTP_FROM_NAME']
    );
    /*
    |--------------------------------------------------------------------------
    | Admin Email
    |--------------------------------------------------------------------------
    */

    $mail->addAddress($_ENV['ADMIN_EMAIL']);
    $mail->addReplyTo(
        $email,
        $first_name . ' ' . $last_name
    );
    $mail->isHTML(true);
    $mail->Subject = '🚖 New Contact Enquiry - Easy Cab India';
    $mail->Body = '
    <!DOCTYPE html>
        <html>
        <head>
              <meta charset="UTF-8">
              <title>New Contact Enquiry</title>
        </head>
      <body style="margin:0;padding:0;background:#f4f8f9;font-family:Arial,Helvetica,sans-serif;">
             <table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f4f8f9">
                 <tr>
                     <td align="center">
                       <table width="650" cellpadding="0" cellspacing="0"
                       style="background:#ffffff;border-radius:10px;overflow:hidden;">

                 <tr>
                     <td align="center" style="background:#1C4A5A;padding:30px;"> 
                        <img src="https://www.easycabindia.com/images/easycab-logo.png" width="70"alt="Easy Cab India">
                        <h2 style="margin:15px 0 5px;color:#ffffff;">
                          New Website Enquiry
                        </h2> 
                        <p style="margin:0;color:#d9f4f3;"> Easy Cab India </p>

                     </td>
                 </tr>

                <tr>
        <td style="padding:35px;">

        <p style="font-size:16px;color:#444;margin-top:0;">
        A new enquiry has been submitted through the Easy Cab India website.
        </p>

        <table width="100%"
        cellpadding="12"
        cellspacing="0"
        style="border-collapse:collapse;border:1px solid #eeeeee;">

        <tr style="background:#f7fbfb;">
        <td width="35%"><strong>First Name</strong></td>
        <td>'.$first_name.'</td>
        </tr>

        <tr>
        <td><strong>Last Name</strong></td>
        <td>'.$last_name.'</td>
        </tr>

        <tr style="background:#f7fbfb;">
        <td><strong>Email</strong></td>
        <td>'.$email.'</td>
        </tr>

        <tr>
        <td><strong>Phone</strong></td>
        <td>'.$phone.'</td>
        </tr>

        <tr style="background:#f7fbfb;">
        <td><strong>Message</strong></td>
        <td>'.nl2br($message).'</td>
        </tr>

        <tr>
        <td><strong>Submitted On</strong></td>
        <td>'.date('d M Y, h:i A').'</td>
        </tr>

        </table>

        </td>
        </tr>

        <!-- Footer -->

        <tr>

        <td align="center"
        style="background:#20A397;padding:20px;color:#ffffff;">

        <p style="margin:0;font-size:16px;font-weight:bold;">
        Easy Cab India
        </p>

        <p style="margin:10px 0 0;font-size:13px;">
        Fast • Reliable • Secure
        </p>

        <p style="margin-top:15px;font-size:12px;">
        © '.date('Y').' Easy Cab India. All Rights Reserved.
        </p>

        </td>

        </tr>

        </table>

        </td>
        </tr>

        </table>

        </body>
        </html>
';

$mail->send();
    // Clear previous recipients
    $mail->clearAddresses();
    $mail->clearReplyTos();
    $mail->clearAttachments();

    // Customer Email
    $mail->addAddress($email, $first_name . ' ' . $last_name);

    $mail->Subject = 'Thank You for Contacting Easy Cab India';

    $mail->Body = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Thank You</title>
</head>

<body style="margin:0;padding:0;background:#f4f8f9;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f4f8f9">

<tr>
<td align="center">

<table width="650"
cellpadding="0"
cellspacing="0"
style="background:#ffffff;border-radius:10px;overflow:hidden;">

<!-- Header -->

<tr>

<td align="center"
style="background:#1C4A5A;padding:30px;">

<img src="https://www.easycabindia.com/images/easycab-logo.png"
width="70"
alt="Easy Cab India">

<h2 style="margin:15px 0 5px;color:#ffffff;">
Thank You!
</h2>

<p style="margin:0;color:#d8f2f1;">
Your enquiry has been received successfully.
</p>

</td>

</tr>

<!-- Body -->

<tr>

<td style="padding:35px;">

<h3 style="margin-top:0;color:#1C4A5A;">
Hello '.$first_name.',
</h3>

<p style="font-size:16px;color:#555;line-height:28px;">

Thank you for contacting
<strong style="color:#20A397;">Easy Cab India</strong>.

<br><br>

We have received your enquiry successfully.

<br><br>

Our team will review your request and get back to you as soon as possible.

</p>

<table width="100%"
cellpadding="12"
cellspacing="0"
style="border-collapse:collapse;border:1px solid #eeeeee;margin-top:25px;">

<tr style="background:#f7fbfb;">
<td width="35%"><strong>Name</strong></td>
<td>'.$first_name.' '.$last_name.'</td>
</tr>

<tr>
<td><strong>Email</strong></td>
<td>'.$email.'</td>
</tr>

<tr style="background:#f7fbfb;">
<td><strong>Phone</strong></td>
<td>'.$phone.'</td>
</tr>

<tr>
<td><strong>Your Message</strong></td>
<td>'.nl2br($message).'</td>
</tr>

</table>

<div style="margin-top:35px;text-align:center;">

<a href="https://www.easycabindia.com"
style="
background:#20A397;
color:#ffffff;
text-decoration:none;
padding:14px 35px;
border-radius:30px;
display:inline-block;
font-weight:bold;
">

Visit Our Website

</a>

</div>

</td>

</tr>

<!-- Footer -->

<tr>

<td align="center"
style="background:#1C4A5A;padding:25px;">

<p style="margin:0;color:#ffffff;font-size:18px;">
Easy Cab India
</p>

<p style="margin:10px 0 0;color:#d2e7ea;font-size:14px;">
Fast • Reliable • Secure
</p>

<p style="margin-top:15px;color:#9fdedb;font-size:13px;">
© '.date('Y').' Easy Cab India. All Rights Reserved.
</p>

</td>
</tr>

</table>

</td>

</tr>

</table>

</body>
</html>';
    $mail->send();
    echo json_encode([
    "status" => "success",
    "message" => "Your enquiry has been submitted successfully."
]);
exit;

} catch (Exception $e) {
    error_log(
        '[' . date('Y-m-d H:i:s') . '] PHPMailer Error: ' .
        $mail->ErrorInfo .
        PHP_EOL,
        3,
        __DIR__ . '/mail-error.log'
    );

 echo json_encode([
    "status" => "error",
    "message" => $mail->ErrorInfo
]);
exit;
}