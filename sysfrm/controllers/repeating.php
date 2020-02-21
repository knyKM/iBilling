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
// it will handle all settings
_auth();
$ui->assign('_title', 'Repeating Payments- '. $config['CompanyName']);
$ui->assign('_sysfrm_menu', 'repeating');
$ui->assign('_st', 'Repeating');
$action = $routes['1'];
$user = User::_info();
$mdate = date('Y-m-d');
$ui->assign('user', $user);
switch ($action) {
    case 'income':
        $d = ORM::for_table('sys_accounts')->find_many();
        $ui->assign('d', $d);
        //        $prs = ORM::for_table('sys_payers')->find_many();
        $ui->assign('prs', $d);
        $cats = ORM::for_table('sys_cats')->where('type','Income')->find_many();
        $ui->assign('cats', $cats);
        $pms = ORM::for_table('sys_pmethods')->find_many();
        $ui->assign('pms', $pms);

        $ui->assign('mdate', $mdate);
        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/dp/dist/datepicker.min.css"/>
');
        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/select2/select2.min.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/dp/dist/datepicker.min.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/numeric.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/repeating-income.js"></script>
');
        $ui->assign('xjq', '
 $("#account").select2();
 $("#cats").select2();
  $("#pmethod").select2();
  $("#payer").select2();

 ');
        $ui->display('repeating-income.tpl');

        break;



    case 'income-post':
        $account = _post('account');
        $date = _post('date');
        $amount = _post('amount');
        $amount = str_replace($config['currency_code'],'',$amount);
        $amount = str_replace(',','',$amount);
        $payer = _post('payer');
        $cat = _post('cats');
        $pmethod = _post('pmethod');
        $ref = _post('ref');
        $description = _post('description');
        $msg = '';
        if ($description == '') {
            $msg .= $_L['description_error'] . '<br>';
        }



       if($account == ''){
           $msg .= 'Please choose an Account <br>';
       }


        if (is_numeric($amount) == false) {
            $msg .= $_L['amount_error'] . '<br>';
        }

        $f = _post('frequency');
        $np = _post('np');

if(!Repeating::_validate($f,$np)){
    $msg .= $_L['frequency_error'] . '<br>';
}

if($f == 'Once Only'){
$n = '1';
    $cdate[1] = $date;
}
elseif($f == 'Monthly'){
    $n = $np;
    $t = 0;
    for ($i = 1; $i <= $n; $i++) {
       if($i>1){

           $cdate[$i] = date('Y-m-d', strtotime("+$t month",strtotime($date)));
          // Dev::_log($cdate[$i]);
       }
        else{
            $cdate[$i] = $date;
        }
        $t++;
    }
}
elseif($f == 'Every 30 Days'){
    $n = $np;
    $t = 0;
    for ($i = 1; $i <= $n; $i++) {
        if($i>1){
            $nd = $t*30;

            $cdate[$i] = date('Y-m-d', strtotime("+$nd days",strtotime($date)));
        }
        else{
            $cdate[$i] = $date;
        }
        $t++;
    }
}
elseif($f == 'Weekly'){
    $n = $np;
    $t = 0;
    for ($i = 1; $i <= $n; $i++) {
        if($i>1){

            $cdate[$i] = date('Y-m-d', strtotime("+$t week",strtotime($date)));
        }
        else{
            $cdate[$i] = $date;
        }
        $t++;
    }
}

elseif($f == 'Bi Weekly'){
    $n = $np;
    $t = 0;
    for ($i = 1; $i <= $n; $i++) {
        if($i>1){

            $cdate[$i] = date('Y-m-d', strtotime("+$t week",strtotime($date)));
        }
        else{
            $cdate[$i] = $date;
        }
       $t = $t + 2;
    }
}

elseif($f == 'Everyday'){
    $n = $np;
    $t = 0;
    for ($i = 1; $i <= $n; $i++) {
        if($i>1){

            $cdate[$i] = date('Y-m-d', strtotime("+$t days",strtotime($date)));
        }
        else{
            $cdate[$i] = $date;
        }
        $t++;
    }
}
elseif($f == 'Every 2 Month'){
    $n = $np;
    $t = 0;
    for ($i = 1; $i <= $n; $i++) {
        if($i>1){
            $nm = $t*2;

            $cdate[$i] = date('Y-m-d', strtotime("+$nm month",strtotime($date)));
        }
        else{
            $cdate[$i] = $date;
        }
        $t++;
    }
}
elseif($f == 'Quarterly'){
    $n = $np;
    $t = 0;
    for ($i = 1; $i <= $n; $i++) {
        if($i>1){
            $nm = $t*3;

            $cdate[$i] = date('Y-m-d', strtotime("+$nm month",strtotime($date)));
        }
        else{
            $cdate[$i] = $date;
        }
        $t++;
    }
}
elseif($f == 'Every 6 Month'){
    $n = $np;
    $t = 0;
    for ($i = 1; $i <= $n; $i++) {
        if($i>1){
            $nm = $t*6;
            $cdate[$i] = date('Y-m-d', strtotime("+$nm month",strtotime($date)));
        }
        else{
            $cdate[$i] = $date;
        }
        $t++;
    }
}
elseif($f == 'Yearly'){
    $n = $np;
    $t = 0;
    for ($i = 1; $i <= $n; $i++) {
        if($i>1){
            $nd = $t*365;

            $cdate[$i] = date('Y-m-d', strtotime("+$nd days",strtotime($date)));
        }
        else{
            $cdate[$i] = $date;
        }
        $t++;
    }
}
        else{
            $n = 0;
            $msg .= $_L['frequency_error'] . '<br>';
        }
$tags = _post('tags');
        if ($msg == '') {
            if($tags != ''){
                $pieces = explode(',', $tags);
                foreach($pieces as $element)
                {
                    $tg = ORM::for_table('sys_tags')->where('text',$element)->find_one();
                    if(!$tg){
                        $tc = ORM::for_table('sys_tags')->create();
                        $tc->text = $element;
                        $tc->type = 'Income';
                        $tc->save();
                    }
                }
            }
            for ($i = 1; $i <= $n; $i++) {
                $d = ORM::for_table('sys_repeating')->create();
                $d->account = $account;
                $d->description = $description;
                $d->type = 'Income';
                $d->payer =  $payer;
                $d->tags =  $tags;
                $d->amount = $amount;
                $d->category = $cat;
                $d->method = $pmethod;
                $d->ref = $ref;
                $d->date = $cdate[$i];

                $d->save();
            }
          //  r2(U . 'repeating/income-calendar', 's', $_L['transaction_added_successfully']);
            _msglog('s','Added Successfully');
            echo $d->id();
        } else {
          //  r2(U . 'repeating/income', 'e', $msg);
            echo $msg;
        }
        break;

    case 'expense':
        $d = ORM::for_table('sys_accounts')->find_many();
        $ui->assign('d', $d);
        $ui->assign('prs', $d);
        $cats = ORM::for_table('sys_cats')->where('type','Expense')->find_many();
        $ui->assign('cats', $cats);
        $pms = ORM::for_table('sys_pmethods')->find_many();
        $ui->assign('pms', $pms);
//        $prs = ORM::for_table('sys_payee')->find_many();
//        $ui->assign('prs', $prs);
        $ui->assign('mdate', $mdate);
        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/dp/dist/datepicker.min.css"/>
');
        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/select2/select2.min.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/dp/dist/datepicker.min.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/numeric.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/repeating-expense.js"></script>
');
        $ui->assign('xjq', '
 $("#account").select2();
 $("#cats").select2();
  $("#pmethod").select2();
  $("#payee").select2();

 ');
        $ui->display('repeating-expense.tpl');

        break;



    case 'expense-post':
        $account = _post('account');
        $date = _post('date');
        $amount = _post('amount');
        $amount = str_replace($config['currency_code'],'',$amount);
        $amount = str_replace(',','',$amount);
        $payer = _post('payer');
        $cat = _post('cats');
        $pmethod = _post('pmethod');
        $ref = _post('ref');
        $description = _post('description');
        $msg = '';
        if ($description == '') {
            $msg .= $_L['description_error'] . '<br>';
        }



       if($account == ''){
           $msg .= 'Please choose an Account <br>';
       }


        if (is_numeric($amount) == false) {
            $msg .= $_L['amount_error'] . '<br>';
        }

        $f = _post('frequency');
        $np = _post('np');
        if(!Repeating::_validate($f,$np)){
            $msg .= $_L['frequency_error'] . '<br>';
        }

        if($f == 'Once Only'){
            $n = '1';
            $cdate[1] = $date;
        }
        elseif($f == 'Monthly'){
            $n = $np;
            $t = 0;
            for ($i = 1; $i <= $n; $i++) {
                if($i>1){

                    $cdate[$i] = date('Y-m-d', strtotime("+$t month",strtotime($date)));
                    Dev::_log($cdate[$i]);
                }
                else{
                    $cdate[$i] = $date;
                }
                $t++;
            }
        }
        elseif($f == 'Every 30 Days'){
            $n = $np;
            $t = 0;
            for ($i = 1; $i <= $n; $i++) {
                if($i>1){
                    $nd = $t*30;

                    $cdate[$i] = date('Y-m-d', strtotime("+$nd days",strtotime($date)));
                }
                else{
                    $cdate[$i] = $date;
                }
                $t++;
            }
        }
        elseif($f == 'Weekly'){
            $n = $np;
            $t = 0;
            for ($i = 1; $i <= $n; $i++) {
                if($i>1){

                    $cdate[$i] = date('Y-m-d', strtotime("+$t week",strtotime($date)));
                }
                else{
                    $cdate[$i] = $date;
                }
                $t++;
            }
        }

        elseif($f == 'Bi Weekly'){
            $n = $np;
            $t = 0;
            for ($i = 1; $i <= $n; $i++) {
                if($i>1){

                    $cdate[$i] = date('Y-m-d', strtotime("+$t week",strtotime($date)));
                }
                else{
                    $cdate[$i] = $date;
                }
                $t = $t + 2;
            }
        }

        elseif($f == 'Everyday'){
            $n = $np;
            $t = 0;
            for ($i = 1; $i <= $n; $i++) {
                if($i>1){

                    $cdate[$i] = date('Y-m-d', strtotime("+$t days",strtotime($date)));
                }
                else{
                    $cdate[$i] = $date;
                }
                $t++;
            }
        }
        elseif($f == 'Every 2 Month'){
            $n = $np;
            $t = 0;
            for ($i = 1; $i <= $n; $i++) {
                if($i>1){
                    $nm = $t*2;

                    $cdate[$i] = date('Y-m-d', strtotime("+$nm month",strtotime($date)));
                }
                else{
                    $cdate[$i] = $date;
                }
                $t++;
            }
        }
        elseif($f == 'Quarterly'){
            $n = $np;
            $t = 0;
            for ($i = 1; $i <= $n; $i++) {
                if($i>1){
                    $nm = $t*3;

                    $cdate[$i] = date('Y-m-d', strtotime("+$nm month",strtotime($date)));
                }
                else{
                    $cdate[$i] = $date;
                }
                $t++;
            }
        }
        elseif($f == 'Every 6 Month'){
            $n = $np;
            $t = 0;
            for ($i = 1; $i <= $n; $i++) {
                if($i>1){
                    $nm = $t*6;
                    $cdate[$i] = date('Y-m-d', strtotime("+$nm month",strtotime($date)));
                }
                else{
                    $cdate[$i] = $date;
                }
                $t++;
            }
        }
        elseif($f == 'Yearly'){
            $n = $np;
            $t = 0;
            for ($i = 1; $i <= $n; $i++) {
                if($i>1){
                    $nd = $t*365;

                    $cdate[$i] = date('Y-m-d', strtotime("+$nd days",strtotime($date)));
                }
                else{
                    $cdate[$i] = $date;
                }
                $t++;
            }
        }
        else{
            $n = 0;

            $msg .= $_L['frequency_error'] . '<br>';
        }
$tags = _post('tags');
        if ($msg == '') {
            if($tags != ''){
                $pieces = explode(',', $tags);
                foreach($pieces as $element)
                {
                    $tg = ORM::for_table('sys_tags')->where('text',$element)->find_one();
                    if(!$tg){
                        $tc = ORM::for_table('sys_tags')->create();
                        $tc->text = $element;
                        $tc->type = 'Expense';
                        $tc->save();
                    }
                }
            }
            for ($i = 1; $i <= $n; $i++) {
                $d = ORM::for_table('sys_repeating')->create();
                $d->account = $account;
                $d->description = $description;
                $d->type = 'Expense';
                $d->payer =  $payer;
                $d->amount = $amount;
                $d->tags = $tags;
                $d->category = $cat;
                $d->method = $pmethod;
                $d->ref = $ref;
                $d->date = $cdate[$i];

                $d->save();
            }
          //  r2(U . 'repeating/expense-calendar', 's', $_L['transaction_added_successfully']);
            _msglog('s','Added Successfully');
            echo $d->id();

        } else {
//            r2(U . 'repeating/expense', 'e', $msg);
            echo $msg;
        }
        break;







    case 'list':

        $paginator = Paginator::bootstrap('sys_transactions');
        $d = ORM::for_table('sys_transactions')->offset($paginator['startpoint'])->limit($paginator['limit'])->find_many();
        $ui->assign('d',$d);
        $ui->assign('paginator',$paginator);
        $ui->display('transactions.tpl');
        break;

    case 'view':
        $id = $routes['2'];
        $t = ORM::for_table('sys_repeating')->find_one($id);
        if ($t) {
            $ui->assign('t', $t);
            $d = ORM::for_table('sys_accounts')->find_many();
            $ui->assign('d', $d);
            $icat = '1';
            if(($t['type']) == 'Income'){
                $cats = ORM::for_table('sys_cats')->where('type','Income')->find_many();
            }
            elseif(($t['type']) == 'Expense'){
                $cats = ORM::for_table('sys_cats')->where('type','Expense')->find_many();
            }
            else{
                $cats = '0';
                $icat = '0';
            }
            $ui->assign('icat', $icat);
            $ui->assign('cats', $cats);
            $pms = ORM::for_table('sys_pmethods')->find_many();
            $ui->assign('pms', $pms);

            $ui->assign('mdate', $mdate);
            $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/dp/dist/datepicker.min.css"/>
');
            $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/select2/select2.min.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/dp/dist/datepicker.min.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/numeric.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/repeating-view.js"></script>
');
            $ui->assign('xjq', '
 $("#account").select2();
 $("#cats").select2();
  $("#pmethod").select2();

 ');
            $ui->display('repeating-view.tpl');
        } else {
            r2(U . 'transactions/list', 'e', $_L['Transaction_Not_Found']);
        }

        break;
    case 'edit-post':
        $id = _post('id');
        $d = ORM::for_table('sys_repeating')->find_one($id);

        if($d){
            $cd = $d['description'];
            $cat = _post('cats');
            $pmethod = _post('pmethod');
            $ref = _post('ref');
            $date = _post('date');
            $description = _post('description');
            $msg = '';
            if ($description == '') {
                $msg .= $_L['description_error'] . '<br>';
            }

            if ($msg == '') {

$eo = _post('eo');
                if($eo == 'all'){
        // $d = ORM::for_table('sys_repeating')->where('description',$cd)->find_many();

                }
                else{
                    $d->category = $cat;
                    $d->method = $pmethod;
                    $d->ref = $ref;

                    $d->description = $description;
                    $d->date = $date;

                    $d->save();
                }

                r2(U . 'repeating/view/'.$id, 's', $_L['transaction_update_successful']);
            } else {
                r2(U . 'repeating/view/'.$id, 'e', $msg);
            }
        }
        else{
            r2(U . 'transactions/list', 'e', $_L['Transaction_Not_Found']);
        }



        if ($msg == '') {
            $d = ORM::for_table('sys_accounts')->find_one($id);
            if ($d) {

                $d->account = $account;
                $d->description = $description;

                $d->save();

                // now update all transactions with the new name

                r2(U . 'accounts/list', 's', $_L['account_updated_successfully']);
            } else {
                r2(U . 'accounts/list', 'e', $_L['Account_Not_Found']);
            }
        } else {
            r2(U . 'accounts/add', 'e', $msg);
        }

        break;

    case 'income-calendar':
        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/calendar/css/calendar.min.css"/>
');
        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/calendar/components/underscore/underscore-min.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/calendar/js/calendar.js"></script>
');
        $ui->assign('xjq', "
var calendar = $('#calendar').calendar({
tmpl_path: 'ui/theme/softhash/lib/calendar/tmpls/',
events_source: '".U."ajax.income-calendar',
onAfterViewLoad: function(view) {
".'$'."('#month').text(this.getTitle());
".'$'."('.btn-group button').removeClass('active');
}
});".'

$(\'.btn-group button[data-calendar-nav]\').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.navigate($this.data(\'calendar-nav\'));
		});
	});'
        );
        $ui->display('income-calendar.tpl');
        break;

    case 'expense-calendar':
        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/calendar/css/calendar.min.css"/>
');
        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/calendar/components/underscore/underscore-min.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/calendar/js/calendar.js"></script>
');
        $ui->assign('xjq', "
var calendar = $('#calendar').calendar({
tmpl_path: 'ui/theme/softhash/lib/calendar/tmpls/',
events_source: '".U."ajax.expense-calendar',
onAfterViewLoad: function(view) {
".'$'."('#month').text(this.getTitle());
".'$'."('.btn-group button').removeClass('active');
}
});".'

$(\'.btn-group button[data-calendar-nav]\').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.navigate($this.data(\'calendar-nav\'));
		});
	});'
        );
        $ui->display('expense-calendar.tpl');
        break;

    case 'confirm':
        $id = $routes[2];
        if($_app_stage == 'Demo'){
            r2(U . 'repeating/income', 'e', 'Sorry! Recurring option is disabled in the Demo Mode');
        }
if(Repeating::confirm($id)){
    r2(U . 'repeating/view/'.$id, 's', $_L['transaction_update_successful']);
}
        else{
            r2(U . 'repeating/view/'.$id, 'e', $_L['an_error_occured']);
        }

        break;

    case 'mark-paid':
        $id = $routes[2];
        if($_app_stage == 'Demo'){
            r2(U . 'repeating/income', 'e', 'Sorry! Recurring option is disabled in the Demo Mode');
        }
        if(Repeating::mark_paid($id)){
            r2(U . 'repeating/view/'.$id, 's', $_L['transaction_update_successful']);
        }
        else{
            r2(U . 'repeating/view/'.$id, 'e', $_L['an_error_occured']);
        }

        break;

    case 'partial-payment':
        $id = $routes[2];
        if($_app_stage == 'Demo'){
            r2(U . 'repeating/income', 'e', 'Sorry! Recurring option is disabled in the Demo Mode');
        }
        $amount = _post('amount');
        if(Repeating::partial($id,$amount)){
            r2(U . 'repeating/view/'.$id, 's', $_L['transaction_update_successful']);
        }
        else{
            r2(U . 'repeating/view/'.$id, 'e', $_L['an_error_occured']);
        }

        break;

    case 'delete-single':
        $id = $routes[2];
        if($_app_stage == 'Demo'){
            r2(U . 'repeating/income', 'e', 'Sorry! Recurring option is disabled in the Demo Mode');
        }
        $d = ORM::for_table('sys_repeating')->find_one($id);
        if(Repeating::delete_single($id)){
            $type = $d['type'];
            if($type == 'Income'){
                r2(U . 'repeating/income-calendar', 's', $_L['transaction_delete_successful']);
            }
            else{
                r2(U . 'repeating/expense-calendar', 's', $_L['transaction_delete_successful']);
            }

        }
        else{
            r2(U . 'repeating/view/'.$id, 'e', $_L['an_error_occured']);
        }

        break;
    case 'delete-multiple':
        $id = $routes[2];
        $d = ORM::for_table('sys_repeating')->find_one($id);

        if(Repeating::delete_multiple($id)){
            $type = $d['type'];
            if($type == 'Income'){
                r2(U . 'repeating/income-calendar', 's', $_L['transaction_delete_successful']);
            }
            else{
                r2(U . 'repeating/expense-calendar', 's', $_L['transaction_delete_successful']);
            }

        }
        else{
            r2(U . 'repeating/view/'.$id, 'e', $_L['an_error_occured']);
        }

        break;
    default:
        echo 'action not defined';
}