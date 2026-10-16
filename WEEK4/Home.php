<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = htmlspecialchars($_POST['fullname'] ?? '');
    $password = $_POST['password'] ?? '';
    $message  = htmlspecialchars($_POST['message'] ?? '');
    $gender   = htmlspecialchars($_POST['gender'] ?? 'Lama dooran');

    echo "Magacaaga: " . $fullname . "<br>";
    echo "Password-ka waa la helay.<br>";
    echo "Jinsiga: " . $gender . "<br>";
    echo "Fariinta: " . nl2br($message) . "<br><hr>";
}
?>

<form method="post" action="">
    <label>Enter Full Name</label><br>
    <input type="text" name="fullname" required><br>

    <label>Enter Password</label><br>
    <input type="password" name="password" required><br>

    <label>Select Gender</label><br>
    <input type="radio" name="gender" value="Lab" required> Lab
    <input type="radio" name="gender" value="Dumar"> Dumar<br>

    <label>Enter Message</label><br>
    <textarea name="message" rows="5" cols="30"></textarea><br><br>

    <input type="reset" value="reset form">
    <input type="submit" value="Register">
</form>
</body>
</html>