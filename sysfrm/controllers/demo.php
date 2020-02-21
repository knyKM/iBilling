<?php
//this script will run only in demo mode

if($_app_stage != 'Demo'){
    exit;
}

//generate random transaction for this month

$td = date('j');


$i = '1';

while($i <= $td){
    echo $i;
    $i++;

}