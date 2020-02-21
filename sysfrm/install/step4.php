<?php
// *************************************************************************
// *                                                                       *
// * iBilling -  Accounting, Billing Software                              *
// * Copyright (c) Sadia Sharmin. All Rights Reserved                      *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * Email: sadiasharmin3139@gmail.com                                                *
// * Website: http://www.sadiasharmin.com                                  *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * This software is furnished under a license and may be used and copied *
// * only  in  accordance  with  the  terms  of such  license and with the *
// * inclusion of the above copyright notice.                              *
// * If you Purchased from Codecanyon, Please read the full License from   *
// * here- http://codecanyon.net/licenses/standard                         *
// *                                                                       *
// *************************************************************************
//error_reporting (0);
require ('sysfrm_installer_config.php');
$appurl = $_POST['appurl'];
$db_host = $_POST['dbhost'];
$db_user = $_POST['dbuser'];
$db_password = $_POST['dbpass'];
$db_name = $_POST['dbname'];
    if($appurl == '' OR $db_host == '' OR $db_user == '' OR $db_name == ''){
        header("location: step3.php?_error=3");
        exit;
    }
$cn = '0';
$wConfig = "../config.php";
if(file_exists($wConfig)){
    header("location: step3.php?_error=2");
    exit;
}
try{
    $dbh = new pdo( "mysql:host=$db_host;dbname=$db_name",
        "$db_user",
        "$db_password",
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
   $cn = '1';
}
catch(PDOException $ex){
    header("location: step3.php?_error=1");
    exit;
}



if ($cn == '1') {
    $input = '<?php
$db_host	    = \'' . $db_host . '\';
$db_user        = \'' . $db_user . '\';
$db_password	= \'' . $db_password . '\';
$db_name	    = \'' . $db_name . '\';
define(\'APP_URL\', \'' . $appurl . '\');
$_app_stage = \'Live\'; // You can set this variable Live to Dev to enable ibilling Debug
';

    $fh = fopen($wConfig, 'w') or die("Can't create config file, your server does not support 'fopen' function, or file does not have write permission. Please check the documentation for Manual Installation.
  <br/>
 $input
 ");
    fwrite($fh, $input);
    fclose($fh);


    $sql = file_get_contents('primary.sql');

    $qr = $dbh->exec($sql);
    //


} else {
    header("location: step3.php?_error=$cn");
    exit;
}


?><!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $app_name; ?> Installer</title>
    <link rel="shortcut icon" type="image/x-icon" href="../uploads/icon/favicon.ico">
    <style type="text/css">


    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="../../ui/theme/softhash/css/bootstrap.min.css" rel="stylesheet">
    <link type='text/css' href='style.css' rel='stylesheet'/>



</head>
<body style='background-color: #FBFBFB;'>
<div id='main-container'>
    <div class='header'>
        <div class="header-box wrapper">
            <div class="hd-logo"><a href="#"><img src="../uploads/system/logo.png" alt="Logo"/></a></div>
        </div>

    </div>
    <!--  contents area start  -->
    <div class="span12">
        <h4> <?php echo $app_name; ?> Installer </h4>
        <?php
        if ($cn == '1') {

            ?>
            <p>
                <strong>Config File Created and Database Imported.</strong><br>
            </p>
            <form action="step5.php" method="post">
                <fieldset>
                    <legend>Click Continue</legend>




                    <button type='submit' class='btn btn-primary'>Continue</button>
                </fieldset>
            </form>
        <?php
        } elseif ($cn == '2') {
            ?>
            <p> MySQL Connection was successfull. An error occured while adding data on MySQL. Unsuccessfull
                Installation. Please refer manual installation in the Documentation or Contact support@bdinfosys.com for
                helping on installation</p>
        <?php
        } else {
            ?>

            <p> MySQL Connection Failed. </p>
        <?php

        }
        ?>
    </div>
</div>
<!--  contents area end  -->
</div>
<div class="footer">Copyright &copy; <?php echo date('Y'); ?> All Rights Reserved<br/>
    <br/>
</div>
</body>
</html>

