<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
if (!empty($_FILES['file']['name'])) {
    echo "File Name: " . $_FILES['file']['name'];
    echo "<br>";
    echo "File Size: " . $_FILES['file']['size'];
    echo "<br>";
    echo "Temporary File: " . $_FILES['file']['tmp_name'];
    echo "<br>";
    echo "File Type: " . $_FILES['file']['type'];
}
?>

</body>
</html>