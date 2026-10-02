<?php

$numbers = array(5, -7, 12, 10, -7, 11, -6, 12, 1, -7, 2, 9);


echo "All Elements:<br>";

foreach ($numbers as $number) {
    echo $number . " ";
}

echo "<br><br>";


$total = 0;

foreach ($numbers as $number) {
    $total = $total + $number;
}

echo "Total = " . $total;
echo "<br>";


$evenTotal = 0;

foreach ($numbers as $number) {
    if ($number % 2 == 0) {
        $evenTotal = $evenTotal + $number;
    }
}

echo "Even Total = " . $evenTotal;
echo "<br>";


$oddTotal = 0;

foreach ($numbers as $number) {
    if ($number % 2 != 0) {
        $oddTotal = $oddTotal + $number;
    }
}

echo "Odd Total = " . $oddTotal;
echo "<br>";


$min = min($numbers);

echo "Minimum = " . $min . "<br>";
echo "Minimum Positions: ";

foreach ($numbers as $key => $number) {
    if ($number == $min) {
        echo ($key + 1) . " ";
    }
}

echo "<br>";


$max = max($numbers);

echo "Maximum = " . $max . "<br>";
echo "Maximum Positions: ";

foreach ($numbers as $key => $number) {
    if ($number == $max) {
        echo ($key + 1) . " ";
    }
}

?>