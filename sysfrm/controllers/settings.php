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
//it will handle all settings
_auth();
$ui->assign('_title', $_L['Settings'].'- '. $config['CompanyName']);
$ui->assign('_pagehead', '<i class="fa fa-cogs lblue"></i> Settings');
$ui->assign('_st', $_L['Settings']);
$ui->assign('_sysfrm_menu', 'settings');
$action = $routes['1'];
$user = User::_info();
$ui->assign('user', $user);
$ui->assign('_user', $user);
switch ($action) {
    case 'expense-categories':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }

        $d = ORM::for_table('sys_cats')->where('type','Expense')->order_by_asc('sorder')->find_many();
        $ui->assign('d',$d);
        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/css/liststyle.css"/>
');
        $ui->assign('xjq', Reorder::js('sys_cats'));
        $ui->display('expense-categories.tpl');


        break;

    case 'expense-categories-post':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        $name = _post('name');
        if($name == ''){
            r2(U."settings/expense-categories",'e',$_L['name_error']);
        }
        //check categories already exist
        $c = ORM::for_table('sys_cats')->where('name',$name)->where('type','Expense')->find_one();
        if($c){
            r2(U."settings/expense-categories",'e',$_L['name_exist_error']);
        }
        if($_app_stage == 'Demo'){
            r2(U . 'settings/expense-categories', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        $d = ORM::for_table('sys_cats')->create();

        $d->name = $name;
        $d->type = 'Expense';
        $d->save();
        r2(U."settings/expense-categories",'s',$_L['added_successful']);
        break;

    case 'income-categories':

        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }

        $d = ORM::for_table('sys_cats')->where('type','Income')->order_by_asc('sorder')->find_many();
        $ui->assign('d',$d);
        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/css/liststyle.css"/>
');

        $ui->assign('xjq', Reorder::js('sys_cats'));
        $ui->display('income-categories.tpl');


        break;

    case 'income-categories-post':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        $name = _post('name');
        if($name == ''){
            r2(U."settings/income-categories",'e',$_L['name_error']);
        }
        $c = ORM::for_table('sys_cats')->where('name',$name)->where('type','Income')->find_one();
        if($c){
            r2(U."settings/income-categories",'e',$_L['name_exist_error']);
        }
        if($_app_stage == 'Demo'){
            r2(U . 'settings/income-categories', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        $d = ORM::for_table('sys_cats')->create();

        $d->name = $name;
        $d->type = 'Income';
        $d->save();
        r2(U."settings/income-categories",'s',$_L['added_successful']);
        break;

    case 'categories-manage':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }

        $id = $routes[2];
        $d = ORM::for_table('sys_cats')->find_one($id);
        if($d){
            $ui->assign('c',$d);
            $ui->display('categories-edit.tpl');
        }






        break;

    case 'categories-edit-post':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        $id = _post('id');
        $d = ORM::for_table('sys_cats')->find_one($id);
        if($_app_stage == 'Demo'){
            r2(U . 'settings/expense-categories', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        if($d){
            $otype = $d['type'];
            $rd = strtolower($otype);
            $name = _post('name');
            $c = ORM::for_table('sys_cats')->where('name',$name)->where('type',$otype)->find_one();
            if($c){
                r2(U."settings/$rd-categories",'e',$_L['name_exist_error']);
            }
            $oname = $d['name'];
            $type = $d['type'];
            if($name == ''){
                r2(U."settings/categories-manage/$id",'e',$_L['name_error']);
            }
            else{
                $d->name = $name;
                $d->save();
                //update payee in transactions
                ORM::for_table('sys_transactions')->raw_execute("update sys_transactions set category='$name' where (category='$oname' AND type='$type')");
                r2(U."settings/categories-manage/$id",'s',$_L['edit_successful']);
            }
        }
        break;


    case 'categories-delete':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        $id = $routes[2];
        $d = ORM::for_table('sys_cats')->find_one($id);
        if($d){
            if($_app_stage == 'Demo'){
                r2(U . 'settings/expense-categories', 'e', 'Sorry! This option is disabled in the demo mode.');
            }
            //find all transaction in this category
            $name = $d['name'];
            $type = $d['type'];

            ORM::for_table('sys_transactions')->raw_query("update sys_transactions set category=:cat where category='$name' AND type='$type'", array('cat' => 'Uncategorized'));
            $d->delete();
            if($type == 'Income'){
                r2(U."settings/income-categories",'s',$_L['delete_successful']);
            }
            else{
                r2(U."settings/expense-categories",'s',$_L['delete_successful']);
            }


        }
        break;

    case 'payee':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }

        $d = ORM::for_table('sys_payee')->order_by_asc('sorder')->find_many();
        $ui->assign('d',$d);
        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/css/liststyle.css"/>
');
        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/js/jquery-ui-1.10.2.custom.min.js"></script>
');
        $ui->assign('xjq', Reorder::js('sys_payee'));
        $ui->display('payee.tpl');


        break;

    case 'payee-manage':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }

        $id = $routes[2];
        $d = ORM::for_table('sys_payee')->find_one($id);
        if($d){
            $ui->assign('c',$d);
            $ui->display('payee-manage.tpl');
        }


        break;

    case 'payee-edit-post':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        if($_app_stage == 'Demo'){
            r2(U . 'settings/payee', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        $id = _post('id');
        $d = ORM::for_table('sys_payee')->find_one($id);
        if($d){
            $name = _post('name');
            $c = ORM::for_table('sys_payee')->where('name',$name)->find_one();
            if($c){
                r2(U."settings/payee",'e',$_L['name_exist_error']);
            }

            $oname = $d['name'];

            if($name == ''){
                r2(U."settings/payee-manage/$id",'e',$_L['name_error']);
            }
            else{
                $d->name = $name;
                $d->save();
                //update payee in transactions
                ORM::for_table('sys_transactions')->raw_query("update sys_transactions set payee=:payee where payee='$oname'", array('payee' => $name));
                r2(U."settings/payee-manage/$id",'s',$_L['edit_successful']);
            }
        }

        break;

    case 'payee-post':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        $name = _post('name');
        if($_app_stage == 'Demo'){
            r2(U . 'settings/payee', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        if($name == ''){
            r2(U."settings/payee",'e',$_L['name_error']);
        }

        $c = ORM::for_table('sys_payee')->where('name',$name)->find_one();
        if($c){
            r2(U."settings/payee",'e',$_L['name_exist_error']);
        }

        $d = ORM::for_table('sys_payee')->create();

        $d->name = $name;

        $d->save();
        r2(U."settings/payee",'s',$_L['added_successful']);
        break;


    case 'payee-delete':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        if($_app_stage == 'Demo'){
            r2(U . 'settings/payee', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        $id = $routes[2];
        $d = ORM::for_table('sys_payee')->find_one($id);
        if($d){


            $d->delete();


            r2(U."settings/payee",'s',$_L['delete_successful']);
        }
        break;


    case 'payer':

        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        $d = ORM::for_table('sys_payers')->order_by_asc('sorder')->find_many();
        $ui->assign('d',$d);
        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/css/liststyle.css"/>
');
        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/js/jquery-ui-1.10.2.custom.min.js"></script>
');
        $ui->assign('xjq', Reorder::js('sys_payers'));
        $ui->display('payer.tpl');


        break;

    case 'payer-manage':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }

        $id = $routes[2];
        $d = ORM::for_table('sys_payers')->find_one($id);
        if($d){
            $ui->assign('c',$d);
            $ui->display('payer-manage.tpl');
        }


        break;

    case 'payer-edit-post':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        if($_app_stage == 'Demo'){
            r2(U . 'settings/payer', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        $id = _post('id');
        $d = ORM::for_table('sys_payers')->find_one($id);
        if($d){
            $name = _post('name');
            $c = ORM::for_table('sys_payers')->where('name',$name)->find_one();
            if($c){
                r2(U."settings/payer",'e',$_L['name_exist_error']);
            }

            $oname = $d['name'];

            if($name == ''){
                r2(U."settings/payer-manage/$id",'e',$_L['name_error']);
            }
            else{
                $d->name = $name;
                $d->save();

                ORM::for_table('sys_transactions')->raw_query("update sys_transactions set payer=:payer where payer='$oname'", array('payer' => $name));
                r2(U."settings/payer-manage/$id",'s',$_L['edit_successful']);
            }
        }

        break;

    case 'payer-post':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        if($_app_stage == 'Demo'){
            r2(U . 'settings/payer', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        $name = _post('name');
        if($name == ''){
            r2(U."settings/payer",'e',$_L['name_error']);
        }

        $c = ORM::for_table('sys_payers')->where('name',$name)->find_one();
        if($c){
            r2(U."settings/payer",'e',$_L['name_exist_error']);
        }

        $d = ORM::for_table('sys_payers')->create();

        $d->name = $name;

        $d->save();
        r2(U."settings/payer",'s',$_L['added_successful']);
        break;

    case 'payer-delete':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        if($_app_stage == 'Demo'){
            r2(U . 'settings/payer', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        $id = $routes[2];
        $d = ORM::for_table('sys_payers')->find_one($id);
        if($d){


            $d->delete();


            r2(U."settings/payer",'s',$_L['delete_successful']);
        }
        break;
    case 'pmethods':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }

        $d = ORM::for_table('sys_pmethods')->order_by_asc('sorder')->find_many();
        $ui->assign('d',$d);
        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/css/liststyle.css"/>
');
        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/js/jquery-ui-1.10.2.custom.min.js"></script>
');
        $ui->assign('xjq', Reorder::js('sys_pmethods'));
        $ui->display('pmethods.tpl');


        break;

    case 'pmethods-manage':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }

        $id = $routes[2];
        $d = ORM::for_table('sys_pmethods')->find_one($id);
        if($d){
            $ui->assign('c',$d);
            $ui->display('pmethods-manage.tpl');
        }


        break;

    case 'pmethods-edit-post':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        if($_app_stage == 'Demo'){
            r2(U . 'settings/pmethods', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        $id = _post('id');
        $d = ORM::for_table('sys_pmethods')->find_one($id);
        if($d){
            $name = _post('name');
            $c = ORM::for_table('sys_pmethods')->where('name',$name)->find_one();
            if($c){
                r2(U."settings/pmethods",'e',$_L['name_exist_error']);
            }

            $oname = $d['name'];

            if($name == ''){
                r2(U."settings/pmethods-manage/$id",'e',$_L['name_error']);
            }
            else{
                $d->name = $name;
                $d->save();

                ORM::for_table('sys_transactions')->raw_query("update sys_transactions set pmethod=:pmethod where pmethod='$oname'", array('pmethod' => $name));
                r2(U."settings/pmethods-manage/$id",'s',$_L['edit_successful']);
            }
        }

        break;

    case 'pmethods-post':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        if($_app_stage == 'Demo'){
            r2(U . 'settings/pmethods', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        $name = _post('name');
        if($name == ''){
            r2(U."settings/pmethods",'e',$_L['name_error']);
        }

        $c = ORM::for_table('sys_pmethods')->where('name',$name)->find_one();
        if($c){
            r2(U."settings/pmethods",'e',$_L['name_exist_error']);
        }

        $d = ORM::for_table('sys_pmethods')->create();

        $d->name = $name;

        $d->save();
        r2(U."settings/pmethods",'s',$_L['added_successful']);
        break;


    case 'pmethods-delete':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        if($_app_stage == 'Demo'){
            r2(U . 'settings/pmethods', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        $id = $routes[2];
        $d = ORM::for_table('sys_pmethods')->find_one($id);
        if($d){


            $d->delete();


            r2(U."settings/pmethods",'s',$_L['delete_successful']);
        }
        break;


    case 'app':

        //find current invoice increment
        $tblsts = ORM::for_table('sys_invoices')->raw_query("show table status like 'sys_invoices'")->find_one();
        $ai = $tblsts['Auto_increment'];
        $ui->assign('ai',$ai);


        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        $timezonelist = Timezone::timezoneList();
        $ui->assign('tlist',$timezonelist);

        //find email settings

        $e = ORM::for_table('sys_emailconfig')->find_one('1');
        $ui->assign('e',$e);

        $ui->assign('xheader', Asset::css(array('s2/css/select2.min','redactor/redactor')));
        $ui->assign('xfooter', Asset::js(array('redactor/redactor.min','s2/js/select2.min','s2/js/i18n/'.lan(),'ui-settings')));


        $ui->assign('xjq', '

$(\'#invoice_terms\').redactor(
{
minHeight: 150 // pixels
}
);


 ');

        $ui->display('app-settings.tpl');

        break;

    case 'features':


        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }

        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/feature-settings.js"></script>
');

        $ui->assign('xjq', '



 ');

        $ui->display('feature-settings.tpl');


        break;

    case 'users':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
//        $ui->assign('xfooter', '
//<script type="text/javascript" src="ui/lib/c/users.js"></script>
//');
        $d = ORM::for_table('sys_users')->find_many();
        $ui->assign('d',$d);
        $ui->display('users.tpl');

        break;

    case 'users-add':
        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }

        $ui->display('users-add.tpl');

        break;

    case 'users-edit':
        $ui->assign('_sysfrm_menu', 'my_account');

        $id  = $routes['2'];
        $d = ORM::for_table('sys_users')->find_one($id);
        if($d){

            $ui->assign('xheader', Asset::css(array('imgcrop/assets/css/croppic')));


            $ui->assign('xfooter', Asset::js(array('imgcrop/croppic','jslib/admin_profile')));

            $ui->assign('d',$d);
            $ui->display('users-edit.tpl');

        }
        else{
            r2(U . 'settings/users', 'e', $_L['Account_Not_Found']);
        }

        break;

    case 'users-delete':


        $id  = $routes['2'];
        //prevent self delete
        if(($user['id']) == $id){
            r2(U . 'settings/users', 'e', 'Sorry You can\'t delete yourself');
        }
        $d = ORM::for_table('sys_users')->find_one($id);
        if($d){

            $d->delete();
            r2(U . 'settings/users', 's', 'User deleted Successfully');
        }
        else{
            r2(U . 'settings/users', 'e', $_L['Account_Not_Found']);
        }

        break;

    case 'users-post':


        $username = _post('username');
        $fullname = _post('fullname');
        $password = _post('password');
        $cpassword = _post('cpassword');
        $user_type = _post('user_type');
        $msg = '';
        if(Validator::Email($username) == false){
            $msg .= $_L['notice_email_as_username']. '<br>';
        }
        if(Validator::Length($fullname,26,2) == false){
            $msg .= 'Full Name should be between 3 to 25 characters'. '<br>';
        }
        if(!Validator::Length($password,15,5)){
            $msg .= 'Password should be between 6 to 15 characters'. '<br>';

        }
        if($password != $cpassword){
            $msg .= 'Passwords does not match'. '<br>';
        }
//check with same name account is exist
        $d = ORM::for_table('sys_users')->where('username',$username)->find_one();
        if($d){
            $msg .= $_L['account_already_exist']. '<br>';
        }




        if($msg == ''){

            $password = Password::_crypt($password);
            // Add Account
            $d = ORM::for_table('sys_users')->create();
            $d->username = $username;
            $d->password = $password;
            $d->fullname = $fullname;
            $d->user_type = $user_type;

            //others
            $d->phonenumber = '';
            $d->last_login = date('Y-m-d H:i:s');
            $d->email = '';
            $d->creationdate = date('Y-m-d H:i:s');
            $d->pin = '';
            $d->img = '';
            $d->otp = 'No';
            $d->pin_enabled = 'No';
            $d->api = 'No';
            $d->pwresetkey = '';
            $d->keyexpire = '';
            $d->status = 'Active';

            //

            $d->save();
            r2(U . 'settings/users', 's', $_L['account_created_successfully']);
        }
        else{
            r2(U . 'settings/users-add', 'e', $msg);
        }

        break;


    case 'users-edit-post':


        $username = _post('username');
        $fullname = _post('fullname');
        $img = _post('picture');
        $password = _post('password');
        $cpassword = _post('cpassword');



        $msg = '';

        if(Validator::Email($username) == false){
            $msg .= 'Please use a valid Email address as Username'. '<br>';
        }
        if(Validator::Length($fullname,26,2) == false){
            $msg .= 'Full Name should be between 3 to 25 characters'. '<br>';
        }
        if($password != ''){
            if(!Validator::Length($password,15,5)){
                $msg .= 'Password should be between 6 to 15 characters'. '<br>';

            }
            if($password != $cpassword){
                $msg .= 'Passwords does not match'. '<br>';
            }
        }
        //find this user
        $id = _post('id');
        $d = ORM::for_table('sys_users')->find_one($id);
        if($d){

        }
        else{
            $msg .= 'Username Not Found'. '<br>';
        }
//check with same name account is exist
        if($d['username'] != $username){
            $c = ORM::for_table('sys_users')->where('username',$username)->find_one();
            if($c){
                $msg .= $_L['account_already_exist']. '<br>';
            }
        }



        if($_app_stage == 'Demo'){
            $msg .= 'Editing User is disabled in the Demo Mode!'. '<br>';
        }


        if($msg == ''){


            // Add Account

            $d->username = $username;
            if($password != ''){
                $password = Password::_crypt($password);
                $d->password = $password;
            }

            $d->fullname = $fullname;
            if(($user['id']) != $id){
                $user_type = _post('user_type');
                $d->user_type = $user_type;
            }

            $d->img = $img;

            $d->save();
            r2(U . 'settings/users', 's', 'User Updated Successfully');
        }
        else{
            r2(U . 'settings/users-edit/'.$id, 'e', $msg);
        }

        break;

    case 'app-post':
        if($_app_stage == 'xDemo'){
            r2(U . 'settings/app', 'e', 'Sorry! This option is disabled in the demo mode.');
        }
        $company = _post('company');
        $theme = _post('theme');

        $nstyle = _post('nstyle');
        $pdf_font = _post('pdf_font');
        if($company == '' OR $theme == '' OR $nstyle == ''){
            r2(U.'settings/app','e',$_L['All Fields are Required']);
        }

        //check if email is posted as smtp

        if($_app_stage == 'Demo'){
            r2(U.'settings/app','e',$_L['disabled_in_demo']);
        }
        else{
            $d = ORM::for_table('sys_appconfig')->where('setting','CompanyName')->find_one();
            $d->value = $company;
            $d->save();

            $d = ORM::for_table('sys_appconfig')->where('setting','theme')->find_one();
            $d->value = $theme;
            $d->save();

            $d = ORM::for_table('sys_appconfig')->where('setting','nstyle')->find_one();
            $d->value = $nstyle;
            $d->save();


            $d = ORM::for_table('sys_appconfig')->where('setting','pdf_font')->find_one();
            $d->value = $pdf_font;
            $d->save();

            $caddress = $_POST['caddress'];
            $d = ORM::for_table('sys_appconfig')->where('setting','caddress')->find_one();
            $d->value = $caddress;
            $d->save();

            $invoice_terms = $_POST['invoice_terms'];
            $d = ORM::for_table('sys_appconfig')->where('setting','invoice_terms')->find_one();
            $d->value = $invoice_terms;
            $d->save();


            $i_driver = $_POST['i_driver'];
            $d = ORM::for_table('sys_appconfig')->where('setting','i_driver')->find_one();
            $d->value = $i_driver;
            $d->save();

            //set invoice numbering

            $iai = _post('iai');

            if(($iai != '') AND (is_numeric($iai))){
                //check it's bigger then current
                $tblsts = ORM::for_table('sys_invoices')->raw_query("show table status like 'sys_invoices'")->find_one();
                $ai = $tblsts['Auto_increment'];
                if($ai < $iai){
                    $set_ai = ORM::for_table('sys_invoices')->raw_execute("ALTER TABLE sys_invoices auto_increment = $iai");
                }
            }

            r2(U.'settings/app','s',$_L['Settings Saved Successfully']);
        }


        break;

    case 'eml-post':
        if($_app_stage == 'Demo'){
            r2(U.'settings/emls/','e',$_L['disabled_in_demo']);
        }



        $sysemail = _post('sysemail');
        if(Validator::Email($sysemail) == false){
            r2(U.'settings/emls/','e',$_L['Invalid System Email']);
        }

        $d = ORM::for_table('sys_appconfig')->where('setting','sysEmail')->find_one();
        $d->value = $sysemail;
        $d->save();
        $email_method = _post('email_method');
        $e = ORM::for_table('sys_emailconfig')->find_one('1');
        if($email_method == 'smtp'){

            $smtp_user = _post('smtp_user');
            $smtp_host = _post('smtp_host');
            $smtp_password = _post('smtp_password');
            $smtp_port = _post('smtp_port');
            $smtp_secure = _post('smtp_secure');
            if($smtp_user == '' OR $smtp_password == '' OR $smtp_port == '' OR $smtp_host == ''){
                r2(U.'settings/emls/','e',$_L['smtp_fields_error']);
            }
            else{
                $e->method = 'smtp';
                $e->host = $smtp_host;
                $e->username = $smtp_user;
                $e->password = $smtp_password;
                $e->apikey = '';
                $e->port = $smtp_port;
                $e->secure = $smtp_secure;
            }
        }
        else{
            $e->method = 'phpmail';
        }
        $e->save();
        r2(U.'settings/emls/','s',$_L['Settings Saved Successfully']);



        break;

    case 'lc-post':
        if($_app_stage == 'Demo'){
            r2(U . 'settings/localisation/', 'e', 'Sorry! This option is disabled in the demo mode!');
        }

//        $rtl = _post('rtl');
//
//        if($rtl != '1'){
//            $rtl = '0';
//        }

        $tzone = _post('tzone');
        $d = ORM::for_table('sys_appconfig')->where('setting','timezone')->find_one();
        $d->value = $tzone;
        $d->save();

        $country = _post('country');
        $d = ORM::for_table('sys_appconfig')->where('setting','country')->find_one();
        $d->value = $country;
        $d->save();

//        $dec_point = $_POST['dec_point'];
//        if(strlen($dec_point) == '1'){
//            $d = ORM::for_table('sys_appconfig')->where('setting','dec_point')->find_one();
//            $d->value = $dec_point;
//            $d->save();
//        }
//
//        $thousands_sep = $_POST['thousands_sep'];
//        if(strlen($thousands_sep) == '1'){
//            $d = ORM::for_table('sys_appconfig')->where('setting','thousands_sep')->find_one();
//            $d->value = $thousands_sep;
//            $d->save();
//        }

        $cformat = _post('cformat');

        if($cformat == '1'){
            $d = ORM::for_table('sys_appconfig')->where('setting','dec_point')->find_one();
            $d->value = '.';
            $d->save();
            $d = ORM::for_table('sys_appconfig')->where('setting','thousands_sep')->find_one();
            $d->value = '';
            $d->save();
        }
        elseif($cformat == '2'){
            $d = ORM::for_table('sys_appconfig')->where('setting','dec_point')->find_one();
            $d->value = '.';
            $d->save();
            $d = ORM::for_table('sys_appconfig')->where('setting','thousands_sep')->find_one();
            $d->value = ',';
            $d->save();
        }
        elseif($cformat == '3'){
            $d = ORM::for_table('sys_appconfig')->where('setting','dec_point')->find_one();
            $d->value = ',';
            $d->save();
            $d = ORM::for_table('sys_appconfig')->where('setting','thousands_sep')->find_one();
            $d->value = '';
            $d->save();
        }
        elseif($cformat == '4'){
            $d = ORM::for_table('sys_appconfig')->where('setting','dec_point')->find_one();
            $d->value = ',';
            $d->save();
            $d = ORM::for_table('sys_appconfig')->where('setting','thousands_sep')->find_one();
            $d->value = '.';
            $d->save();
        }
        else{

            $d = ORM::for_table('sys_appconfig')->where('setting','dec_point')->find_one();
            $d->value = '.';
            $d->save();
            $d = ORM::for_table('sys_appconfig')->where('setting','thousands_sep')->find_one();
            $d->value = ',';
            $d->save();

        }

        $currency_code = $_POST['currency_code'];

        $d = ORM::for_table('sys_appconfig')->where('setting','currency_code')->find_one();
        $d->value = $currency_code;
        $d->save();

//        $d = ORM::for_table('sys_appconfig')->where('setting','rtl')->find_one();
//        $d->value = $rtl;
//        $d->save();

        $df = _post('df');
        $d = ORM::for_table('sys_appconfig')->where('setting','df')->find_one();
        $d->value = $df;
        $d->save();

        $lan = _post('lan');
        $d = ORM::for_table('sys_appconfig')->where('setting','language')->find_one();
        $d->value = $lan;
        $d->save();

        // reload lagnuage file

        r2(U.'settings/localisation/');



        break;

    case 'lc-charset-post':

        $coll = _post('coll');
        $chars = explode('_',$coll);
        $chars_name =  $chars[0];
//echo $chars_name;
//
//exit;

        $mysqli = @new mysqli($db_host, $db_user, $db_password, $db_name);

        if( ! $mysqli->error ) {
            $sql = "SHOW TABLES";
            $show = $mysqli->query($sql);
            while ( $r = $show->fetch_array() ) {
                $tables[] = $r[0];
            }

            if( ! empty( $tables ) ) {

                foreach( $tables as $table )
                {
                    // $result     = $mysqli->query('SELECT * FROM '.$table);
                    $result     = $mysqli->query('ALTER TABLE '.$table." CONVERT TO CHARACTER SET $chars_name COLLATE $coll");
                    //   echo $table;

                }


            } else {

                //     $result = '<p>Error when executing database query to export.</p>'.$mysqli->error;

            }
        }

        r2(U.'settings/localisation/','s',$_L['Charset Saved Successfully']);
        break;

    case 'change-password':
        $ui->assign('_sysfrm_menu', 'my_account');
        $ui->display('change-password.tpl');

        break;

    case 'change-password-post':

        $password = _post('password');
        if($password != ''){
            $d = ORM::for_table('sys_users')->where('username',$user['username'])->find_one();
            if($d){
                $d_pass = $d['password'];
                if(Password::_verify($password,$d_pass) == true){

                    $npass = _post('npass');
                    $cnpass = _post('cnpass');
                    if(!Validator::Length($npass,15,5)){
                        r2(U.'settings/change-password','e',$_L['password_length_error']);

                    }
                    if($npass != $cnpass){
                        r2(U.'settings/change-password','e',$_L['Both Password should be same']);
                    }



                    if($_app_stage == 'Demo'){
                        r2(U.'settings/change-password','e',$_L['disabled_in_demo']);
                    }
                    $npass = Password::_crypt($npass);
                    $d->password = $npass;
                    $d->save();
                    _msglog('s',$_L['Password changed successfully']);

                    r2(U.'login');

                }
                else{
                    r2(U.'settings/change-password','e',$_L['Incorrect Current Password']);
                }
            }
            else{

                r2(U.'settings/change-password','e',$_L['Incorrect Current Password']);
            }
        }
        else{
            r2(U.'settings/change-password','e',$_L['Incorrect Current Password']);
        }


        break;

    case 'networth_goal':

        $goal = _post('goal');

        $goal = Finance::amount_fix($goal);

        if((is_numeric($goal)) AND $goal != ''){
            $d = ORM::for_table('sys_appconfig')->where('setting','networth_goal')->find_one();
            $d->value = $goal;
            $d->save();
            _msglog('s',$_L['New Goal has been set']);
        }
        else{
            _msglog('e',$_L['Invalid Number']);
        }

        break;

    case 'email-templates':
        $d = ORM::for_table('sys_email_templates')->find_many();
        $ui->assign('d',$d);
        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="ui/lib/sn/summernote.css"/>
<link rel="stylesheet" type="text/css" href="ui/lib/sn/summernote-bs3.css"/>
<link rel="stylesheet" type="text/css" href="' . $_theme . '/css/modal.css"/>
<link rel="stylesheet" type="text/css" href="ui/lib/sn/summernote-sysfrm.css"/>
');
        $ui->assign('xfooter', '
 <script type="text/javascript" src="' . $_theme . '/lib/modal.js"></script>
  <script type="text/javascript" src="ui/lib/sn/summernote.min.js"></script>
 <script type="text/javascript" src="ui/lib/jslib/email-templates.js"></script>
');
        $ui->display('email-templates.tpl');
        break;

    case 'email-templates-view':

        $sid = $routes['2'];
        $d = ORM::for_table('sys_email_templates')->find_one($sid);
        if($d){
            $ui->assign('d',$d);

            $s_yes = '';
            $s_no = '';
            if(($d['send']) == 'No'){
                $s_no = 'selected="selected"';
            }

            if(($d['send']) == 'Yes'){
                $s_yes = 'selected="selected"';
            }

            echo '
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3>'.ib_lan_get_line($d['tplname']).'</h3>
</div>
<div class="modal-body">

<form class="form-horizontal" role="form" id="edit_form" method="post">

<div class="form-group">
    <label for="subject" class="col-sm-2 control-label">'.$_L['Subject'].'</label>
    <div class="col-sm-10">
      <input type="text" id="subject" name="subject" class="form-control" value="'.$d['subject'].'">
    </div>
  </div>


   <div class="form-group">
    <label for="message" class="col-sm-2 control-label">'.$_L['Message Body'].'</label>
    <div class="col-sm-10">
      <textarea id="message" name="message" class="form-control sysedit" rows="10">'.$d['message'].'</textarea>
      <input type="hidden" id="sid" name="id" value="'.$d['id'].'">
    </div>
  </div>
  <div class="form-group">
    <label for="name" class="col-sm-2 control-label">'.$_L['Send'].'</label>
    <div class="col-sm-10">
      <select name="send" id="send" class="form-control">
                              <option value="Yes" '.$s_yes.'>'.$_L['Yes'].'</option>
                              <option value="No" '.$s_no.'>'.$_L['No'].'</option>

                          </select>
    </div>
  </div>
</form>

</div>
<div class="modal-footer">
	<button id="update" class="btn btn-primary">'.$_L['Save'].'</button>

		<button type="button" data-dismiss="modal" class="btn">'.$_L['Close'].'</button>
</div>';
        }
        else{
            exit('Template Not Found');
        }





        break;

    case 'update-email-template':
        $id = _post('id');
        $d = ORM::for_table('sys_email_templates')->find_one($id);
        if($_app_stage == 'Demo'){
            echo 'Sorry! This option is disabled in the demo mode!';
            exit;
        }
        if($d){

            $message = $_POST['message'];
            $subject = $_POST['subject'];
            $send = _post('send');
            if($message == '' OR $subject == ''){
                echo 'Invalid Data';
            }
            else{
                $d->subject = $subject;
                $d->send = $send;
                $d->message = $message;

                $d->save();
                echo 'Data Updated';
            }

        }
        else{
            echo 'Sorry Data not Found';
        }

        break;

    case 'tags':

        $d = ORM::for_table('sys_tags')->find_many();
        $ui->assign('d',$d);

        $ui->assign('xjq', '
$(".cdelete").click(function (e) {
        e.preventDefault();
        var id = this.id;
        bootbox.confirm("'.$_L['are_you_sure'].'", function(result) {
           if(result){
               var _url = $("#_url").val();
               window.location.href = _url + "delete/tags/" + id;
           }
        });
    });

 ');


        $ui->display('tags.tpl');


        break;



    case 'logo-post':
        if($_app_stage == 'Demo'){
            r2(U.'settings/app','e',$_L['disabled_in_demo']);
        }
        $validextentions = array("jpeg", "jpg", "png");
        $temporary = explode(".", $_FILES["file"]["name"]);
        $file_extension = end($temporary);
        $file_name = '';
        if(($_FILES["file"]["type"] == "image/png")){
            $file_name = 'logo-tmp.png';
        }
        elseif(($_FILES["file"]["type"] == "image/jpg")){
            $file_name = 'logo-tmp.jpg';
        }
        elseif(($_FILES["file"]["type"] == "image/jpeg")){
            $file_name = 'logo-tmp.jpeg';
        }
        elseif(($_FILES["file"]["type"] == "image/gif")){
            $file_name = 'logo-tmp.gif';
        }
        else{

        }
        if ((($_FILES["file"]["type"] == "image/png")
                || ($_FILES["file"]["type"] == "image/jpg")
                || ($_FILES["file"]["type"] == "image/jpeg"))
            && ($_FILES["file"]["size"] < 1000000)//approx. 100kb files can be uploaded
            && in_array($file_extension, $validextentions)){
            move_uploaded_file($_FILES["file"]["tmp_name"], 'sysfrm/uploads/system/'. $file_name);
            $image = new Image();
            $image->source_path = 'sysfrm/uploads/system/'. $file_name;
            $image->target_path = 'sysfrm/uploads/system/logo.png';
            $image->resize('0','40',ZEBRA_IMAGE_BOXED,'-1');

            //now delete the tmp image

            unlink('sysfrm/uploads/system/'. $file_name);

            r2(U.'settings/app','s',$_L['Settings Saved Successfully']);
        }

        else{

            r2(U.'settings/app','e',$_L['Invalid Logo File']);

        }


        break;


    case 'localisation':


        $tblsts = ORM::for_table('crm_accounts')->raw_query("show table status like 'crm_accounts'")->find_one();
        $col = $tblsts['Collation'];
        $ui->assign('col',$col);


        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }
        $ui->assign('countries',Countries::all($config['country'])); // may add this $config['country_code']
        $timezonelist = Timezone::timezoneList();
        $ui->assign('tlist',$timezonelist);



        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/select2/select2.css"/>
');
        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/select2/select2.min.js"></script>
');

        $ui->assign('xjq', '
 $("#tzone").select2();
 $("#country").select2();

 ');

        $ui->display('localisation.tpl');

        break;


    case 'emls':

        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }


        //find email settings

        $e = ORM::for_table('sys_emailconfig')->find_one('1');
        $ui->assign('e',$e);


        $ui->assign('xjq', '

        function _check_e_method(){
        var emethod = $( "#email_method" ).val();
        if(emethod == "phpmail"){
         $("#a_hide").hide();
        }
        else{
         $("#a_hide").show();
        }
        }
_check_e_method();
$( "#email_method" ).change(function() {
 _check_e_method();
});
 ');

        $ui->display('emls.tpl');

        break;


    case 'automation':

        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }


        $cs = ORM::for_table('sys_schedule')->find_many();
        foreach($cs as $rcs)
        {
            $arcs[$rcs['cname']]=$rcs['val'];
        }
        $ui->assign('arcs',$arcs);

//        $ui->assign('xheader', '
//<link rel="stylesheet" type="text/css" href="ui/lib/bootstrap-switch/bootstrap-switch.css"/>
//');
//        $ui->assign('xfooter', '
//<script type="text/javascript" src="ui/lib/bootstrap-switch/bootstrap-switch.min.js"></script>
//');
//
//        $ui->assign('xjq', '
//            $(".sys_csw").bootstrapSwitch();
// ');


        $ui->display('automation.tpl');

        break;


    case 'pg':

        if($user['user_type'] != 'Admin'){
            r2(U."dashboard",'e',$_L['You do not have permission']);
        }




        $d = ORM::for_table('sys_pg')->order_by_asc('sorder')->find_many();
        $ui->assign('d',$d);

        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/select2/select2.css"/>
');
        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/select2/select2.min.js"></script>
');

        $ui->assign('xjq', '


 ');

        $ui->display('pg.tpl');

        break;


    case 'pg-conf':
        $pg = $routes['2'];
        $d = ORM::for_table('sys_pg')->find_one($pg);
        if($d){
            $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/pg.js"></script>
');
            $ui->assign('d',$d);
            $ui->display('pg-conf.tpl');

        }
        else{
            echo 'PG Not Found';
        }

        break;


    case 'pg-post':
        if($_app_stage == 'Demo'){
            r2(U.'settings/app','e',$_L['disabled_in_demo']);
        }
        $pg = _post('pgid');

        $d = ORM::for_table('sys_pg')->find_one($pg);
        if($d){
            $name = _post('name');
            if($name == ''){

                _msglog('e',$_L['name_error']);
                echo $pg;
                exit;
            }
            $d->name = $name;
            $d->settings = _post('settings');
            $d->value = _post('value');
            $d->status = _post('status');
            $d->c1 = _post('c1');
            $d->c2 = _post('c2');
            $d->c3 = _post('c3');
            $d->c4 = _post('c4');
            $d->c5 = _post('c5');
            $d->save();
            _msglog('s',$_L['Data Updated']);
            echo $pg;
        }
        else{
            echo 'PG Not Found';
        }

        break;

    case 'add-tax':

        $ui->display('add-tax.tpl');
        break;

    case 'add-tax-post':
        if($_app_stage == 'Demo'){
            r2(U.'settings/app','e',$_L['disabled_in_demo']);
        }
        $taxname = _post('taxname');
        $taxrate = _post('taxrate');
        $taxrate = Finance::amount_fix($taxrate);
        if($taxname == '' OR $taxrate ==''){
            r2(U.'settings/add-tax/','e',$_L['All Fields are Required']);
        }
        if(!is_numeric($taxrate)){
            r2(U.'settings/add-tax/','e',$_L['Invalid TAX Rate']);
        }

        $d = ORM::for_table('sys_tax')->create();
        $d->name = $taxname;
        $d->rate = $taxrate;
        $d->save();
        r2(U.'tax/list/','s',$_L['New TAX Added']);
        break;

    case 'edit-tax':
        $tid = $routes['2'];
        $d = ORM::for_table('sys_tax')->find_one($tid);
        if($d){
            $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/numeric.js"></script>
');

            $ui->assign('d',$d);
            $ui->display('edit-tax.tpl');
        }
        else{
            r2(U.'tax/list/','e',$_L['TAX Not Found']);
        }

        break;

    case 'edit-tax-post':
        if($_app_stage == 'Demo'){
            r2(U.'settings/app','e',$_L['disabled_in_demo']);
        }
        $tid = _post('tid');
        $d = ORM::for_table('sys_tax')->find_one($tid);
        if($d){
            $taxname = _post('taxname');
            $taxrate = _post('taxrate');
            $taxrate = Finance::amount_fix($taxrate);
            if($taxname == '' OR $taxrate ==''){
                r2(U.'settings/edit-tax/'.$tid.'/','e','All Fields is Required.');
            }
            if(!is_numeric($taxrate)){
                r2(U.'settings/edit-tax/'.$tid.'/','e','Invalid TAX Rate.');
            }

            $d->name = $taxname;
            $d->rate = $taxrate;
            $d->save();
            r2(U.'settings/edit-tax/'.$tid.'/','s','TAX Saved.');
        }
        else{
            r2(U.'tax/list/','e',$_L['TAX Not Found']);
        }

        break;

    case 'consolekey_regen':

        $nkey = _raid('10');

        $d = ORM::for_table('sys_appconfig')->where('setting','ckey')->find_one();
        $d->value = $nkey;
        $d->save();
        r2(U.'settings/automation/','s',$_L['cron_new_key']);
        break;

    case 'automation-post':
        $accounting_snapshot = _post('accounting_snapshot');
        $d = ORM::for_table('sys_schedule')->where('cname', 'accounting_snapshot')->find_one();
        if ($accounting_snapshot == 'on') {
            $d->val = 'Active';
        } else {
            $d->val = 'Inactive';
        }
        $d->save();

        $recurring_invoice = _post('recurring_invoice');
        $d = ORM::for_table('sys_schedule')->where('cname', 'recurring_invoice')->find_one();
        if ($recurring_invoice == 'on') {
            $d->val = 'Active';
        } else {
            $d->val = 'Inactive';
        }
        $d->save();

        $notify = _post('notify');
        $notifyemail = _post('notifyemail');
        if($notify == 'on'){
            //need valid notify email
            if(Validator::Email($notifyemail) == false){
                r2(U.'settings/automation/','e',$_L['cron_notification']);
            }
        }
        $d = ORM::for_table('sys_schedule')->where('cname', 'notify')->find_one();
        if ($notify == 'on') {
            $d->val = 'Active';
        } else {
            $d->val = 'Inactive';
        }
        $d->save();


        $d = ORM::for_table('sys_schedule')->where('cname', 'notifyemail')->find_one();
        $d->val = $notifyemail;
        $d->save();

        r2(U.'settings/automation/','s',$_L['Settings Saved Successfully']);
        break;

    case 'plugins':

        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="ui/lib/dropzone/dropzone.css"/>
');
        $ui->assign('xfooter', '
<script type="text/javascript" src="ui/lib/dropzone/dropzone.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/plugins.js"></script>
');

        $pls = array_diff(scandir('sysfrm/plugins'), array('..', '.','index.html'));
        $pl_html = '';
        foreach ($pls as $pl){
            $pl_path = 'sysfrm/plugins/'.$pl.'/';
            $i = 0;
            if(file_exists($pl_path.'/manifest.php')){
                $i++;
                //
                $d = ORM::for_table('sys_pl')->where('c',$pl)->find_one();
                $btn = '';
                if($d){
                    //plugin was installed & active
                    $status = $d['status'];
                    if($status == '1'){
                        $btn .= ' <a href="'.U.'settings/plugin_deactivate/'.$pl.'/" class="btn btn-danger btn-sm cdelete"><i class="fa fa-minus-square-o"></i> Deactivate </a>';

                    }
                    else{
                        $btn .= ' <a href="'.U.'settings/plugin_activate/'.$pl.'/" class="btn btn-info btn-sm"><i class="fa fa-check"></i> Activate </a>';
                        $btn .= ' <a href="'.U.'settings/plugin_uninstall/'.$pl.'/" class="btn btn-danger btn-sm c_uninstall"><i class="fa fa-remove"></i> Uninstall </a>';
                    }


                }
                else{
                    //plugin need to be installed
                    $btn .= ' <a href="'.U.'settings/plugin_install/'.$pl.'/" class="btn btn-primary btn-sm cedit"><i class="fa fa-hdd-o"></i> Install </a>';
                    $btn .= ' <a href="'.U.'settings/plugin_delete/'.$pl.'/" class="btn btn-danger btn-sm cdelete"><i class="fa fa-trash"></i> Delete </a>';

                }
                $plugin = null;
                require($pl_path.'/manifest.php');
                $pl_html .= ' <tr>

                <td class="project-title">
                    <a href="'.$plugin['url'].'" class="cedit" target="_blank">'.$plugin['name'].'</a>
                    <br>
                    <small>'.$plugin['version'].'</small>
                </td>
                <td>

                   '.$plugin['description'].'

                </td>

                <td class="project-actions">

                  <span class="pull-right">'.$btn.'</span>

                </td>
            </tr>';

            }
        }

        if($pl_html == ''){
            $pl_html = '<h4 class="text-center">'.$_L['No Plugins Available'].'</h4>';
        }

        $ui->assign('pl_html',$pl_html);
        $ui->display('pl-list.tpl');

        break;

    case 'plugin_upload':

        $uploader   =   new Uploader();
        $uploader->setDir('sysfrm/plugins/');
        $uploader->sameName(true);
        $uploader->setExtensions(array('zip'));  //allowed extensions list//
        if($uploader->uploadFile('file')){   //txtFile is the filebrowse element name //
            $uploaded  =   $uploader->getUploadName(); //get uploaded file name, renames on upload//

        }else{//upload failed
         _msglog('e',$uploader->getMessage()); //get upload error message
        }
        break;

    case 'plugin_unzip':
        /*

        function doIt($callback) { $callback(); }

doIt(function() {
    // this will be done
});


        */
$msg = '';
        $name = _post('name');
        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive;

            $res = $zip->open('sysfrm/plugins/'.$name);
            if ($res === TRUE) {


                if($_app_stage == 'Demo'){
                    $msg .= $name . ' - Plugin Unzipping is Disabled in the Demo Mode! <br>';
                }

                else{
                    $zip->extractTo('sysfrm/plugins/');
                }





if($zip->close()){
    unlink('sysfrm/plugins/'.$name);
}
              //

            } else {
                $msg .= $name . ' - Invalid Plugin Package Or An error occured while unzipping the file! <br>';
            }
        }

else{
    $msg .= 'PHP ZipArchive Class is not Available! <br>';
}

if($msg != ''){
    _msglog('e',$msg);
}
else{
    _msglog('s',$_L['Plugin Added']);
}


        break;

    case 'plugin_activate':

        if(isset($routes['2']) AND $routes['2'] != ''){

            $pl = $routes['2'];
            $pl_path = 'sysfrm/plugins/'.$pl.'/';

            $msg = '';
            $msg .= 'Activating Plugin...
';

            require($pl_path.'/manifest.php');

            if(file_exists($pl_path.'/activate.php')){

                require($pl_path.'/activate.php');

            }

            $d = ORM::for_table('sys_pl')->where('c',$pl)->find_one();
            if($d){
                $d->status = '1';
                $d->save();
                $msg .= 'Plugin Activated...
';

            }

            $ui->assign('plugin',$plugin);
            $ui->assign('plugin_activity',$_L['Activating Plugin']);
            $ui->assign('msg',$msg);
            $ui->display('plugin-activity.tpl');

        }

        else{
            echo 'Plugin not Found';
        }

        break;


    case 'plugin_deactivate':

        if(isset($routes['2']) AND $routes['2'] != ''){

            $pl = $routes['2'];
            $pl_path = 'sysfrm/plugins/'.$pl.'/';

            $msg = '';
            $msg .= 'Deactivating Plugin...
';

            require($pl_path.'/manifest.php');

            if(file_exists($pl_path.'/deactivate.php')){

                require($pl_path.'/deactivate.php');

            }

            $d = ORM::for_table('sys_pl')->where('c',$pl)->find_one();
            if($d){
                $d->status = '0';
                $d->save();
                $msg .= 'Plugin Deactivated...
';

            }

            $ui->assign('plugin',$plugin);
            $ui->assign('plugin_activity',$_L['Deactivating Plugin']);
            $ui->assign('msg',$msg);
            $ui->display('plugin-activity.tpl');

        }

        else{
            echo 'Plugin not Found';
        }

        break;


    case 'plugin_install':

        if(isset($routes['2']) AND $routes['2'] != ''){

            $pl = $routes['2'];

            $pl_path = 'sysfrm/plugins/'.$pl.'/';
            $msg = '';
            $msg .= 'Installing Plugin...
';
            require($pl_path.'/manifest.php');

            if(file_exists($pl_path.'/install.php')){


                require($pl_path.'/install.php');

            }



            $msg .= 'Adding Plugin to the Plugin Database
';

            $c = ORM::for_table('sys_pl')->create();
            $c->c = $pl;
            $c->status = '1';
            $c->save();

            $msg .= 'Plugin Added
';


            $ui->assign('plugin',$plugin);
            $ui->assign('plugin_activity',$_L['Installing Plugin']);
            $ui->assign('msg',$msg);
            $ui->display('plugin-activity.tpl');

        }

        else{
            echo 'Install Script not Found';
        }

        break;


    case 'plugin_uninstall':

        if(isset($routes['2']) AND $routes['2'] != ''){

            $pl = $routes['2'];
            $pl_path = 'sysfrm/plugins/'.$pl.'/';

            $msg = '';
            $msg .= 'Uninstalling Plugin...
';


            require($pl_path.'/manifest.php');

            if(file_exists($pl_path.'/uninstall.php')){

                require($pl_path.'/uninstall.php');

            }

            $msg .= 'Removing Plugin from Plugin Database...
';

            $d = ORM::for_table('sys_pl')->where('c',$pl)->find_one();
            if($d){
                $d->delete();
                $msg .= 'Plugin Uninstalled...
';

            }

            $ui->assign('plugin',$plugin);
            $ui->assign('plugin_activity',$_L['Uninstalling Plugin']);
            $ui->assign('msg',$msg);
            $ui->display('plugin-activity.tpl');

        }

        else{
            echo 'Uninstall script not found';
        }

        break;


    case 'plugin_delete':

        if(isset($routes['2']) AND $routes['2'] != ''){

            $pl = $routes['2'];
            $pl_path = 'sysfrm/plugins/'.$pl.'/';

            $msg = '';
            $msg .= 'Deleting Plugin...
';


            require($pl_path.'/manifest.php');

            if(Sysfile::deleteDir($pl_path)){
                $msg .= 'Plugin Directory Deleted Successfully
';
            }
            else{
                $msg .= 'An Error Occurred while Deleting Plugin Directory. You may Delete this Plugin Manually - '.$pl_path.'
';
            }


            $ui->assign('plugin',$plugin);
            $ui->assign('plugin_activity','Delete Plugin');
            $ui->assign('msg',$msg);
            $ui->display('plugin-activity.tpl');

        }

        else{
            echo 'Plugin not found';
        }

        break;








    case 'customfields':


        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/css/modal.css"/>

');
        $ui->assign('xfooter', '
        <script type="text/javascript" src="' . $_theme . '/lib/modal.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/custom-fields.js"></script>

');
        $cf = ORM::for_table('crm_customfields')->where('ctype','crm')->order_by_asc('id')->find_many();

        $ui->assign('cf',$cf);

        $ui->display('customfields.tpl');

        break;

    case 'customfields-post':

        $fieldname = _post('fieldname');
        $fieldtype = _post('fieldtype');
        $description = _post('description');
        $validation = _post('validation');
        $options = _post('options');
        $showinvoice = _post('showinvoice');
        if($showinvoice != 'Yes'){
            $showinvoice = 'No';
        }
        if($fieldname != ''){

            $d = ORM::for_table('crm_customfields')->create();
            $d->fieldname = $fieldname;
            $d->fieldtype = $fieldtype;
            $d->description = $description;
            $d->regexpr = $validation;
            $d->fieldoptions = $options;
            $d->ctype = 'crm';
            $d->relid = '';
            $d->adminonly = '';
            $d->required = '';
            $d->showorder = '';
            $d->showinvoice = $showinvoice;
            $d->sorder = '0';
            $d->save();

            echo $d->id();
        }
        else{
            echo 'Name is Required';
        }

        break;

    case 'customfields-ajax-add':


        $ui->display('ajax-add-custom-field.tpl');

        break;


    case 'customfields-ajax-edit':

        $id = $routes[2];
        $id = str_replace('f','',$id);

        $d = ORM::for_table('crm_customfields')->find_one($id);
        if($d){
            $ui->assign('d',$d);
            $ui->display('ajax-edit-custom-field.tpl');
        }
        else{
            echo 'Not Found';
        }


        break;


    case 'customfield-edit-post':

        $id = _post('id');

        $fieldname = _post('fieldname');

        if($fieldname == ''){
            ib_die('Name is Required');
        }

        $d = ORM::for_table('crm_customfields')->find_one($id);
        if($d){

            $fieldtype = _post('fieldtype');
            $description = _post('description');
            $validation = _post('validation');
            $options = _post('options');
            $showinvoice = _post('showinvoice');
            if($showinvoice != 'Yes'){
                $showinvoice = 'No';
            }
            $d->fieldname = $fieldname;
            $d->fieldtype = $fieldtype;
            $d->description = $description;
            $d->regexpr = $validation;
            $d->fieldoptions = $options;
            $d->ctype = 'crm';
            $d->relid = '';
            $d->adminonly = '';
            $d->required = '';
            $d->showorder = '';
            $d->showinvoice = $showinvoice;
            $d->sorder = '0';
            $d->save();
            echo $id;
        }
        else{
            echo 'Not Found';
        }


        break;

    case 'update_option':

        $opt = _post('opt');
        $val = _post('val');

        if(update_option($opt,$val)){
            echo 'ok';
        }
        else{
            echo 'failed';
        }

        break;


//    API Support from Version 3

    case 'api':

        $d = ORM::for_table('sys_api')->find_many();

        $ui->assign('d',$d);
        $ui->assign('api_url',APP_URL);

        $ui->display('api.tpl');


        break;

    case 'api_post':

        $label = _post('label');
        if($label == ''){
           r2(U.'settings/api/','e','Label is Required');
        }
        else{

            $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $string = '';
            $random_string_length = '40';
            for ($i = 0; $i < $random_string_length; $i++) {
                $string .= $characters[rand(0, strlen($characters) - 1)];
            }

            $d = ORM::for_table('sys_api')->create();
            $d->label = $label;
            $d->ip = '';
            $d->apikey = $string;
            $d->save();
            r2(U.'settings/api/','s',$_L['API Access Added']);
        }

        break;


    case 'api_delete':

        $id = $routes[2];
        $d = ORM::for_table('sys_api')->find_one($id);
        if($d){


            $d->delete();


            r2(U."settings/api/",'s',$_L['delete_successful']);
        }

        break;

    case 'api_regen':

        $id = $routes[2];
        $d = ORM::for_table('sys_api')->find_one($id);
        if($d){

            $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $string = '';
            $random_string_length = '40';
            for ($i = 0; $i < $random_string_length; $i++) {
                $string .= $characters[rand(0, strlen($characters) - 1)];
            }

            $d->apikey = $string;
            $d->save();

            r2(U."settings/api/",'s','API Key Updated');
        }

        break;


    case 'plugin_force_remove':

        $pl = $routes[2];

        $d = ORM::for_table('sys_pl')->where('c',$pl)->find_one();
        if($d){
            $d->delete();
            r2(U."dashboard/",'s','Plugin Successfully Removed.');
        }

        r2(U."dashboard/",'s','Plugin Not Found.');

        break;




//

    default:
        echo 'action not defined';
}