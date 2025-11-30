<?php

$connection = mysqli_connect("localhost","root","root","it329",8889);

$error = mysqli_connect_error();

if($error != null){
    $output = "<p>Could not connect to the database</p>".$error;
    exit($output);
}

