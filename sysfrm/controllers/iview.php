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
$ui->assign('_sysfrm_menu', 'invoices');
$ui->assign('_st', $_L['Invoice']);
$ui->assign('_title', $_L['Accounts'].'- '. $config['CompanyName']);
$action = $routes['1'];
$id  = $routes['2'];
$d = ORM::for_table('sys_invoices')->find_one($id);

if($d){
    $token = $routes['3'];
    $token = str_replace('token_','',$token);
    $vtoken = $d['vtoken'];
    if($token != $vtoken){
        echo 'Sorry Token does not match!';
        exit;
    }


    //find all activity for this user
    $items = ORM::for_table('sys_invoiceitems')->where('invoiceid',$id)->order_by_asc('id')->find_many();

    $trs_c = ORM::for_table('sys_transactions')->where('iid', $id)->count();

    $trs = ORM::for_table('sys_transactions')->where('iid', $id)->order_by_desc('id')->find_many();

//find the user
    $a = ORM::for_table('crm_accounts')->find_one($d['userid']);

    $i_credit = $d['credit'];
    $i_due = '0.00';
    $i_total = $d['total'];
    if($d['credit'] != '0.00'){
        $i_due = $i_total - $i_credit;
    }
    else{
        $i_due =  $d['total'];
    }
$i_due = number_format($i_due,2,$config['dec_point'],$config['thousands_sep']);
    $cf = ORM::for_table('crm_customfields')->where('showinvoice','Yes')->order_by_asc('id')->find_many();


    require 'sysfrm/lib/invoices/render.php';

}
else{
    r2(U . 'customers/list', 'e', $_L['Account_Not_Found']);
}