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
    $mail_sent = mail($to, $subject, $email_body, $headers);

    // Send data to webhook
    $webhook_url = "https://leados-n8n.abmgroups.org/webhook/contact-form";
    $webhook_data = json_encode([
        'name' => $name,
        'email' => $email,
        'number' => $number,
        'company' => $company,
        'message' => $message
    ]);

    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $webhook_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($webhook_data)
    ]);
    curl_exec($ch);
    curl_close($ch);

    if ($mail_sent) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit;
}
?>
