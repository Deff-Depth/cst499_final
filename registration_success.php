<?php
session_start();

if (!isset($_SESSION['verification_link'])) {
    header('Location: register.php');
    exit;
}

$verificationLink = $_SESSION['verification_link'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Registration Success</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Registration Successful!</h2>
        <p>Thank you for registering. Please click the link below to verify your email address:</p>
        <a href="<?php echo htmlspecialchars($verificationLink); ?>" class="btn btn-primary">Verify Email</a>
    </div>
</body>
</html>
