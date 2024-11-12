<?php

$host="localhost";
$username="root";
$password="";
$db="resl";

$conn= new mysqli($host,$username,$password,$db);

if(!$conn){
    echo "not working";
}


?>