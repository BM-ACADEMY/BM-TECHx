
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $name = htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['mail'] ?? '', FILTER_SANITIZE_EMAIL);
    $number = htmlspecialchars($_POST['number'] ?? '', ENT_QUOTES, 'UTF-8');
    $company = htmlspecialchars($_POST['service'] ?? '', ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8');

    // Website identification
    $website = "yourdomain.com";

    // Define email parameters
    $to = "admin@bmtechx.in";
    $subject = "New Enquiry - " . $website;

    $headers = "From: admin@bmtechx.in\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Compose email body
    $email_body = "Website Enquiry: $website\n\n";
    $email_body .= "Name: $name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Skype/Phone: $number\n";
    $email_body .= "Company: $company\n";
    $email_body .= "Message:\n$message\n";

    // Send admin email
    $mail_sent = mail($to, $subject, $email_body, $headers);

    // Send data to n8n webhook
    $webhook_url = "https://leados-n8n.abmgroups.org/webhook/contact-form";

    $webhook_data = json_encode([
        'website' => $website,
        'form' => 'Contact Form',
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
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $webhook_response = curl_exec($ch);

    if ($webhook_response === false) {
        error_log('n8n webhook error: ' . curl_error($ch));
    }

    curl_close($ch);

    // Return success response
    header('Content-Type: application/json');

    if ($mail_sent) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }

    exit;
}
?>
