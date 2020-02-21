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
_auth();
$ui->assign('_title', $_L['Transactions'].'- '. $config['CompanyName']);
$ui->assign('_st', 'Transactions');
$ui->assign('_sysfrm_menu', 'transactions');
$action = $routes['1'];
$user = User::_info();
$ui->assign('user', $user);
$mdate = date('Y-m-d');
switch ($action) {

    case 'balance-sheet':

      $d = ORM::for_table('sys_accounts')->where_not_equal('balance','0.00')->order_by_desc('balance')->find_many();
      $tbal = ORM::for_table('sys_accounts')->where_not_equal('balance','0.00')->sum('balance');
      $ui->assign('d',$d);
      $ui->assign('tbal',$tbal);
        $ui->display('balance-sheet.tpl');
        break;

    default:
        echo 'action not defined';
}