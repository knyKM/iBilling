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
$ui->assign('_title', $_L['Settings'].'- '. $config['CompanyName']);
$ui->assign('_pagehead', '<i class="fa fa-cogs lblue"></i> Settings');
$ui->assign('_st', $_L['Settings']);
$ui->assign('_sysfrm_menu', 'settings');
$action = $routes['1'];
$user = User::_info();
$ui->assign('user', $user);
$ui->assign('_user', $user);
if($user['user_type'] != 'Admin'){
    r2(U."dashboard",'e',$_L['You do not have permission']);
}
if (isset($routes['1'])) {
    $do = $routes['1'];
} else {
    $do = 'sys_cats';
}

switch ($do) {




    #################### All Ajax Post ###############################
    case 'reorder-post':
        $action = $_POST['action'];
        $updateRecordsArray = $_POST['recordsArray'];

        $listingCounter = 1;
        foreach ($updateRecordsArray as $recordIDValue) {

            $d = ORM::for_table($action)->find_one($recordIDValue);
            $d->sorder = $listingCounter;
            $d->save();
            $listingCounter = $listingCounter + 1;
        }

        echo '<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <strong>Success!</strong> New Positions are updated in database
</div>';
        break;

    case 'pg':

        $d = ORM::for_table('sys_pg')->order_by_asc('sorder')->find_many();
        $ui->assign('ritem','Payment Gateway');
        $ui->assign('d',$d);
        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/css/liststyle.css"/>
');
        $ui->assign('xjq', Reorder::js('sys_pg'));
        $ui->display('reorder.tpl');

        break;


}