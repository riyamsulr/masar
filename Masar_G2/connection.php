<?php

$connection = mysqli_connect("sql100.infinityfree.com","if0_40560213_it329","masar12345678","it329",3306);

$error = mysqli_connect_error();

if($error != null){
    $output = "<p>Could not connect to the database</p>".$error;
    exit($output);
}

