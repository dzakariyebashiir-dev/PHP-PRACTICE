<?php

$number = 15;

if ($number % 3 == 0 && $number % 5 == 0) {
    echo "The number is divisible by both 3 and 5";
} elseif ($number % 3 == 0) {
    echo "The number is divisible by 3";
} elseif ($number % 5 == 0) {
    echo "The number is divisible by 5";
} else {
    echo "The number is not divisible by 3 or 5";
}

?>