<?php

$students = array(

    "CA221" => array(
        "Name" => "Mohamed Ahmed Ali",
        "Phone" => "0648440403",
        "Address" => "Laba Dhagax, Wardhiigley"
    ),

    "CA223" => array(
        "Name" => "Ahmed Abdi Jama",
        "Phone" => "0647223201",
        "Address" => "Taleex, Hodan"
    ),

    "CA221" => array(
        "Name" => "Amina Nur Adan",
        "Phone" => "0646990276",
        "Address" => "Macmaacaanka, Dharkeynley"
    )

);

foreach ($students as $id => $student) {

    echo "ID: " . $id . "<br>";
    echo "Name: " . $student["Name"] . "<br>";
    echo "Phone: " . $student["Phone"] . "<br>";
    echo "Address: " . $student["Address"] . "<br>";

    echo "<br>";
}

?>