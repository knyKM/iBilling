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
$ui->assign('_sysfrm_menu', 'accounts');
$ui->assign('_title', $_L['Delete'].'- '. $config['CompanyName']);
$action = $routes['1'];
$user = User::_info();
switch ($action) {

    case 'crm-user':
    $id = $routes['2'];
    $id = str_replace('uid','',$id);
    $d = ORM::for_table('crm_accounts')->find_one($id);
    if($d){
//delete all activity
        $x = ORM::for_table('sys_activity')->where('cid',$id)->delete_many();
        $x = ORM::for_table('sys_invoices')->where('userid',$id)->delete_many();
        #todo update payer and payee
        $d->delete();
        _log('Contact Deleted '.$username,'Admin',$user['id']);
        r2(U.'contacts/list','s',$_L['Contact Deleted Successfully']);

    }
    else{
        echo 'contact not found';
    }
    break;

    case 'ps':
    $id = $routes['2'];
    $id = str_replace('pid','',$id);
    $d = ORM::for_table('sys_items')->find_one($id);
    if($d){
        $type = $d['type'];
        $r = 'ps/s-list';
        if($type == 'Product'){
            $r = 'ps/p-list';
        }
        _log($type.' Deleted: '.$d['name'].' [ID: '.$d['id'].']','Admin',$user['id']);

        $d->delete();

        r2(U.$r,'s', $type. ' ' .$_L['Deleted Successfully']);

    }
    else{
        echo 'not found';
    }
    break;

    case 'invoice':
        $id = $routes['2'];
        $id = str_replace('iid','',$id);
        $d = ORM::for_table('sys_invoices')->find_one($id);
        if($d){
//delete all invoice items
            $x = ORM::for_table('sys_invoiceitems')->where('invoiceid',$id)->delete_many();

            $d->delete();
            r2(U.'invoices/list','s',$_L['Invoice Deleted Successfully']);

        }
        else{
            echo 'Invoice not found';
        }
        break;

    case 'quote':
        $id = $routes['2'];
        $id = str_replace('iid','',$id);
        $d = ORM::for_table('sys_quotes')->find_one($id);
        if($d){
//delete all invoice items
            $x = ORM::for_table('sys_quoteitems')->where('qid',$id)->delete_many();

            $d->delete();
            r2(U.'quotes/list/','s',$_L['Quote Deleted Successfully']);

        }
        else{
            echo 'Invoice not found';
        }
        break;

    case 'tags':
        $id = $routes['2'];
        $id = str_replace('iid','',$id);
        $d = ORM::for_table('sys_tags')->find_one($id);
        if($d){
//delete all invoice items


            $d->delete();
            r2(U.'settings/tags','s',$_L['Tag Deleted Successfully']);

        }
        else{
            echo 'Invoice not found';
        }
        break;

    case 'tax':
        $id = $routes['2'];
        $id = str_replace('t','',$id);
        $d = ORM::for_table('sys_tax')->find_one($id);
        if($d){

            $d->delete();
            r2(U.'tax/list/','s',$_L['TAX Deleted Successfully']);

        }
        else{
            echo 'TAX not found';
        }
        break;


    case 'customfield':

        $id = $routes[2];
        $id = str_replace('d','',$id);

        $d = ORM::for_table('crm_customfields')->find_one($id);
        if($d){

            $d->delete();
            r2(U.'settings/customfields/','s',$_L['Custom Field Deleted Successfully']);

        }
        else{
            echo 'Custom Field Not found';
        }

        break;

    default:
        echo 'action not defined';
}