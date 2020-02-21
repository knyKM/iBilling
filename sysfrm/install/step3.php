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
require ('sysfrm_installer_config.php'); ?>
<!DOCTYPE html>
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
        if (isset($_GET['_error']) && ($_GET['_error']) == '1') {
            echo '<hr><h4 style="color: red;"> Unable to Connect Database, Please make sure database info is correct and try again ! </h4><hr>';
        }
        elseif (isset($_GET['_error']) && ($_GET['_error']) == '2') {
            echo '<hr><h4 style="color: red;"> Config File Already Exist, Application is already installed. If Not delete config.php in sysfrm folder. And try installing again. </h4><hr>';
        }

        elseif (isset($_GET['_error']) && ($_GET['_error']) == '3') {
            echo '<hr><h4 style="color: red;"> Please provide database info correctly and try again. </h4><hr>';
        }

        else{
           // echo '<h4 style="color: red;"> An Error Occuered </h4>';
        }
        ?>
        <?php
        $http = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        $cururl = $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $appurl = str_replace('/install/step3.php', '', $cururl);
        $appurl = str_replace('?_error=1', '', $appurl);
        $appurl = str_replace('?_error=2', '', $appurl);
        $appurl = str_replace('?_error=3', '', $appurl);
        $appurl = str_replace('/sysfrm', '', $appurl);


        ?>

        <form action="step4.php" method="post">
            <fieldset>
                <legend>Database Connection &amp Site config</legend>

                <div class="form-group">
                    <label for="appurl">Application URL</label>
                    <input type="text" class="form-control" id="appurl" name="appurl" value="<?php echo $appurl; ?>">
                    <span class='help-block'>Application url without trailing slash at the end of url (e.g. http://example.com/app). Please keep default, if you are unsure.</span>
                </div>
                <div class="form-group">
                    <label for="dbhost">Database Host</label>
                    <input type="text" class="form-control" id="dbhost" name="dbhost">
                </div>
                <div class="form-group">
                    <label for="dbuser">Database Username</label>
                    <input type="text" class="form-control" id="dbuser" name="dbuser">
                </div>
                <div class="form-group">
                    <label for="dbpass">Database Password</label>
                    <input type="text" class="form-control" id="dbpass" name="dbpass">
                </div>

                <div class="form-group">
                    <label for="dbname">Database Name</label>
                    <input type="text" class="form-control" id="dbname" name="dbname">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </fieldset>
        </form>
    </div>
</div>
<!--  contents area end  -->
</div>
<div class="footer">Copyright &copy; <?php echo date('Y'); ?> All Rights Reserved<br/>
    <br/>
</div>
</body>
</html>

