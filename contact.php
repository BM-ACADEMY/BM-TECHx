<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['mail']);
    $number = htmlspecialchars($_POST['number']);
    $company = htmlspecialchars($_POST['service']);
    $message = htmlspecialchars($_POST['message']);
    
    // Define email parameters
    $to = "admin@bmtechx.in"; // Replace with your email
    $subject = "New Contact Form Submission";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Compose email body
    $email_body = "Name: $name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Skype/Phone: $number\n";
    $email_body .= "Company: $company\n";
    $email_body .= "Message:\n$message\n";

    // Send email and return success response
    if (mail($to, $subject, $email_body, $headers)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit;
}
?>
