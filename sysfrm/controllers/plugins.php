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
$ui->assign('_sysfrm_menu', 'plugins');
$dir = $routes['1'];
$cont = $routes['2'];
$path = 'sysfrm/plugins/'.$dir.'/'.$cont.'.php';
$pl_path = 'sysfrm/plugins/'.$dir.'/';
if(file_exists($path)){
    $_pd = 'sysfrm/plugins/'.$dir.'/';
    $ui->assign('_pd','sysfrm/plugins/'.$dir.'/');
    require($path);

}
else{
//    echo $path;
    r2(U.'dashboard/','e',$_L['Plugin Not Found']);
}
