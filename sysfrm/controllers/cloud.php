<?php
_auth();
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
$ui->assign('_sysfrm_menu', 'accounts');
$ui->assign('_title', 'Accounts- ' . $config['CompanyName']);
$ui->assign('_st', 'Accounts');
$action = $routes['1'];
$user = User::_info();
$ui->assign('user', $user);
switch ($action) {


    case 'autologin':

        ?>


<!--        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">-->
<!--        <html>-->
<!--        <head>-->
<!--            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">-->
<!--            <title>Please wait while you're redirected</title>-->
<!--            <style type="text/css">-->
<!--                @import url(/css/ui-ob-styles_84534f2584b0c2f1182bde163385ce37.css);-->
<!--            </style>-->
<!--            <script type="text/javascript">-->
<!---->
<!--                function timedText() {-->
<!--                    setTimeout('msg1()', 2000);-->
<!--                    setTimeout('msg2()', 4000);-->
<!--                    setTimeout('document.MetaRefreshForm.submit()', 4000);-->
<!--                }-->
<!---->
<!--                function msg1() {-->
<!--                    document.getElementById('login-redirect-message').firstChild.nodeValue = "Loading customizations...";-->
<!--                }-->
<!---->
<!--                function msg2() {-->
<!--                    document.getElementById('login-redirect-message').firstChild.nodeValue = "Redirecting...";-->
<!--                }-->
<!---->
<!--            </script>-->
<!--        </head>-->
<!---->
<!--        <body id="login-redirect">-->
<!---->
<!--        <div id="login-redirect-container">-->
<!---->
<!--            <h1>Please wait while you&rsquo;re redirected</h1>-->
<!---->
<!--            <p class="login-redirect-message" id="login-redirect-message">Authenticating...</p>-->
<!--            <script type="text/javascript">timedText()</script>-->
<!--            <img src="/images/login-redirection-loader.gif" alt="...">-->
<!--        </div>-->
<!--        <form name="MetaRefreshForm" action="sysfrm/plugins/oc/" method="POST"  name="login"><input-->
<!--                type="hidden" name="user" id="user"-->
<!--                value="admin"><input-->
<!--                type="hidden" name="password" name="password" id="password" value="123456">-->
<!--            -->
<!--        </form>-->
<!--        </body>-->
<!--        </HTML>-->
        <?php

        break;


    default:
        echo 'action not defined';
}