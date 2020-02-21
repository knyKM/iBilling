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
    <div class="row">
        <div class="col-md-12">
            <h4> <?php echo $app_name; ?> Installer </h4>

            <div class="box box-two">


                <h5>

                    Please Read Before Continue</h5>

                <p><strong>About</strong><br>
                    Application Name: <?php echo $app_name; ?> <br>
                    Release Date: <?php echo $release_date; ?><br>
                    By: <?php echo $author_name; ?> [ <a href="http://<?php echo $app_url; ?>" target="_blank"><?php echo $app_url; ?></a> ]<br>
                    <br>
                    <strong>Getting Support</strong><br>
                    If you find any bugs or you have any idea for improvement, Please don't hesitate to contact with me using my profile page-<br>
                    <a href="<?php echo $support_url; ?>" target="_blank"><?php echo $support_url; ?></a>

                    <br>
                    <br>
                    <strong>License Summary</strong><br>
                    The Regular License grants you, the purchaser, an ongoing, non-exclusive, worldwide license to make use
                    of the digital work (Item) you have selected. <br>
                    <br>
                    You are licensed to use the Item to create one single End Product for yourself or for one client (a
                    "single application"), and the End Product can be distributed for Free.<br>
                    You can create one End Product for a client, and you can transfer that single End Product to your client
                    for any fee. This license is then transferred to your client.<br><br>

                    <strong>You can't Sell the End Product, except to one client. </strong><br><br>


                    <strong>You can't re-distribute the Item as stock, in a tool or template, or with source files. You
                        can't do this with an Item either on its own or bundled with other items, and even if you modify the
                        Item. You can't re-distribute or make available the Item as-is or with superficial modifications.
                        These things are not allowed even if the re-distribution is for Free.</strong><br><br>

                    <strong>Although you can modify the Item and therefore delete unwanted components before creating your
                        single End Product, you can't extract and use a single component of an Item on a stand-alone
                        basis.</strong>

                    <br><br>
                    This license can be terminated if you breach it. If that happens, you must stop making copies of or
                    distributing the End Product until you remove the Item from it.<br>
                    <br>
                    The author of the Item retains ownership of the Item but grants you the license on these terms. This
                    license is between the author of the Item and you. Envato Pty Ltd is not a party to this license or the
                    one giving you the license.<br>
                    <br>
                    Read The Full License Here-<br>
                    <a href="http://codecanyon.net/licenses/regular"
                       target="_blank">http://codecanyon.net/licenses/regular</a></p>

            </div>

        </div>

        <div class="col-md-12"><br>

            <a href="step2.php" class="btn btn-primary">Accept &amp; Continue</a> <a href="#" class="btn">Cancel</a>
        </div>
    </div>



    <!--  contents area end  -->
</div>
<div class="footer">Copyright &copy; <?php echo date('Y'); ?> All Rights Reserved<br/>
    <br/>
</div>
</body>
</html>

