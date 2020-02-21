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
$ui->assign('_st', $_L['Transactions']);
$ui->assign('_sysfrm_menu', 'transactions');
$action = $routes['1'];
$user = User::_info();
$ui->assign('user', $user);
$mdate = date('Y-m-d');

//js var

$ui->assign('jsvar', '
_L[\'Working\'] = \''.$_L['Working'].'\';
_L[\'Submit\'] = \''.$_L['Submit'].'\';
 ');

//
switch ($action) {
    case 'deposit':
        $d = ORM::for_table('sys_accounts')->find_many();
       // $p = ORM::for_table('sys_payers')->find_many();
        $p = ORM::for_table('crm_accounts')->find_many();
        $ui->assign('p', $p);
        $ui->assign('d', $d);
        $cats = ORM::for_table('sys_cats')->where('type','Income')->order_by_asc('sorder')->find_many();
        $ui->assign('cats', $cats);
        $pms = ORM::for_table('sys_pmethods')->find_many();
        $ui->assign('pms', $pms);
        $ui->assign('mdate', $mdate);

        $tags = Tags::get_all('Income');
        $ui->assign('tags',$tags);
//        $ui->assign('xheader', '
//<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/select2/select2.css"/>
//<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/dp/dist/datepicker.min.css"/>
//');

        $ui->assign('xheader', Asset::css(array('s2/css/select2.min','dp/dist/datepicker.min')));


//        $ui->assign('xfooter', '
//<script type="text/javascript" src="' . $_theme . '/lib/select2/select2.min.js"></script>
//<script type="text/javascript" src="' . $_theme . '/lib/dp/dist/datepicker.min.js"></script>
//<script type="text/javascript" src="' . $_theme . '/lib/numeric.js"></script>
//<script type="text/javascript" src="' . $_theme . '/lib/deposit.js"></script>
//');

        $ui->assign('xfooter', Asset::js(array('s2/js/select2.min','s2/js/i18n/'.lan(),'dp/dist/datepicker.min','dp/i18n/'.$config['language'],'numeric','deposit')));

        $ui->assign('xjq', '


 ');
       //find latest income
       $tr = ORM::for_table('sys_transactions')->where('type','Income')->order_by_desc('id')->limit('20')->find_many();
        $ui->assign('tr', $tr);
        $ui->display('deposit.tpl');

        break;



    case 'deposit-post':
        $account = _post('account');
        $date = _post('date');
        $amount = _post('amount');
        /* @since v2. added support for ',' as decimal separator */
        $amount = Finance::amount_fix($amount);
        $payerid = _post('payer');
        $ref = _post('ref');
        $pmethod = _post('pmethod');
        $cat = _post('cats');
        $tags = $_POST['tags'];


if($payerid == ''){
    $payerid = '0';
}
        $description = _post('description');
        $msg = '';
        if ($description == '') {
            $msg .= $_L['description_error'] . '<br>';
        }

        if (Validator::Length($account, 100, 1) == false) {
            $msg .= $_L['Choose an Account'].' ' . '<br>';
        }


        if (is_numeric($amount) == false) {
            $msg .= $_L['amount_error'] . '<br>';
        }

        if ($msg == '') {

            Tags::save($tags,'Income');

            //find the current balance for this account
            $a = ORM::for_table('sys_accounts')->where('account',$account)->find_one();
            $cbal = $a['balance'];
            $nbal = $cbal+$amount;
            $a->balance=$nbal;
            $a->save();
            $d = ORM::for_table('sys_transactions')->create();
            $d->account = $account;
            $d->type = 'Income';
            $d->payerid =  $payerid;
            $d->tags =  Arr::arr_to_str($tags);
            $d->amount = $amount;
            $d->category = $cat;
            $d->method = $pmethod;
            $d->ref = $ref;

            $d->description = $description;
            $d->date = $date;
            $d->dr = '0.00';
            $d->cr = $amount;
            $d->bal = $nbal;

            //others
            $d->payer = '';
            $d->payee = '';
            $d->payeeid = '0';
            $d->status = 'Cleared';
            $d->tax = '0.00';
            $d->iid = 0;
            //

            $d->save();
            $tid = $d->id();
            _log('New Deposit: '.$description.' [TrID: '.$tid.' | Amount: '.$amount.']','Admin',$user['id']);
            _msglog('s',$_L['Transaction Added Successfully']);
           echo $tid;
        } else {
           echo $msg;
        }
        break;

    case 'expense':
        $d = ORM::for_table('sys_accounts')->find_many();
        $p = ORM::for_table('crm_accounts')->find_many();
        $ui->assign('p', $p);
        $ui->assign('d', $d);
        $tags = Tags::get_all('Expense');
        $ui->assign('tags',$tags);
        $cats = ORM::for_table('sys_cats')->where('type','Expense')->order_by_asc('sorder')->find_many();
        $ui->assign('cats', $cats);
        $pms = ORM::for_table('sys_pmethods')->find_many();
        $ui->assign('pms', $pms);
        $ui->assign('mdate', $mdate);
//        $ui->assign('xheader', '
//<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/select2/select2.css"/>
//<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/dp/dist/datepicker.min.css"/>
//');

        $ui->assign('xheader', Asset::css(array('s2/css/select2.min','dp/dist/datepicker.min')));

//        $ui->assign('xfooter', '
//<script type="text/javascript" src="' . $_theme . '/lib/select2/select2.min.js"></script>
//<script type="text/javascript" src="' . $_theme . '/lib/dp/dist/datepicker.min.js"></script>
//<script type="text/javascript" src="' . $_theme . '/lib/numeric.js"></script>
//<script type="text/javascript" src="' . $_theme . '/lib/expense.js"></script>
//');

        $ui->assign('xfooter', Asset::js(array('s2/js/select2.min','s2/js/i18n/'.lan(),'dp/dist/datepicker.min','dp/i18n/'.$config['language'],'numeric','expense')));

        $ui->assign('xjq', '


 ');
        //find latest income
        $tr = ORM::for_table('sys_transactions')->where('type','Expense')->order_by_desc('id')->limit('20')->find_many();
        $ui->assign('tr', $tr);

        $ui->display('expense.tpl');

        break;



    case 'expense-post':
        $account = _post('account');
        $date = _post('date');
        $amount = _post('amount');
        $amount = Finance::amount_fix($amount);
        $payee = _post('payee');
        $ref = _post('ref');
        $pmethod = _post('pmethod');
        $cat = _post('cats');
        $tags = $_POST['tags'];

        if(!is_numeric($payee)){
            $payee = '0';
        }


        $description = _post('description');
        $msg = '';
        if ($description == '') {
            $msg .= $_L['description_error'] . '<br>';
        }

        if (Validator::Length($account, 100, 1) == false) {
            $msg .= $_L['Choose an Account'].' ' . '<br>';
        }


        if (is_numeric($amount) == false) {
            $msg .= $_L['amount_error'] . '<br>';
        }

        if ($msg == '') {

            Tags::save($tags,'Expense');

            //find the current balance for this account
            $a = ORM::for_table('sys_accounts')->where('account',$account)->find_one();
            $cbal = $a['balance'];
            $nbal = $cbal-$amount;
            $a->balance=$nbal;
            $a->save();
            $d = ORM::for_table('sys_transactions')->create();
            $d->account = $account;
            $d->type = 'Expense';
            $d->payeeid =  $payee;
            $d->tags =  Arr::arr_to_str($tags);
            $d->amount = $amount;
            $d->category = $cat;
            $d->method = $pmethod;
            $d->ref = $ref;

            $d->description = $description;
            $d->date = $date;
            $d->dr = $amount;
            $d->cr = '0.00';
            $d->bal = $nbal;
            //others
            $d->payer = '';
            $d->payee = '';
            $d->payerid = '0';
            $d->status = 'Cleared';
            $d->tax = '0.00';
            $d->iid = 0;

            $d->save();
            $tid = $d->id();
            _log('New Expense: '.$description.' [TrID: '.$tid.' | Amount: '.$amount.']','Admin',$user['id']);
            _msglog('s',$_L['Transaction Added Successfully']);
            echo $tid;
        } else {
            echo $msg;
        }
        break;

    case 'transfer':
        $d = ORM::for_table('sys_accounts')->find_many();
        $ui->assign('p', $d);
        $ui->assign('d', $d);

        $pms = ORM::for_table('sys_pmethods')->find_many();
        $ui->assign('pms', $pms);
        $ui->assign('mdate', $mdate);
        $tags = Tags::get_all('Transfer');
        $ui->assign('tags',$tags);
        $ui->assign('xheader', Asset::css(array('s2/css/select2.min','dp/dist/datepicker.min')));

        $ui->assign('xfooter', Asset::js(array('s2/js/select2.min','s2/js/i18n/'.lan(),'dp/dist/datepicker.min','dp/i18n/'.$config['language'],'numeric','transfer')));

        $ui->assign('xjq', '

 ');
        //find latest income
        $tr = ORM::for_table('sys_transactions')->where('type','Transfer')->order_by_desc('id')->limit('20')->find_many();
        $ui->assign('tr', $tr);
        $ui->display('transfer.tpl');

        break;



    case 'transfer-post':
        $faccount = _post('faccount');
        $taccount = _post('taccount');
        $date = _post('date');
        $amount = _post('amount');
        $amount = Finance::amount_fix($amount);
        $pmethod = _post('pmethod');
        $ref = _post('ref');

        $description = _post('description');
        $msg = '';
        if (Validator::Length($faccount, 100, 2) == false) {
            $msg .= $_L['Choose an Account'].' ' . '<br>';
        }

        if (Validator::Length($taccount, 100, 2) == false) {
            $msg .= $_L['Choose the Traget Account'].' ' . '<br>';
        }

        if ($description == '') {
            $msg .= $_L['description_error'] . '<br>';
        }

        if (is_numeric($amount) == false) {
            $msg .= $_L['amount_error'] . '<br>';
        }

        //check if from account & target account is same

        if($faccount == $taccount){
            $msg .= $_L['same_account_error'] . '<br>';
        }

        $tags = $_POST['tags'];

        Tags::save($tags,'Transfer');


        if ($msg == '') {
            $a = ORM::for_table('sys_accounts')->where('account',$faccount)->find_one();
            $cbal = $a['balance'];
            $nbal = $cbal-$amount;
            $a->balance=$nbal;
            $a->save();
            $a = ORM::for_table('sys_accounts')->where('account',$taccount)->find_one();
            $cbal = $a['balance'];
            $tnbal = $cbal+$amount;
            $a->balance=$tnbal;
            $a->save();
            $d = ORM::for_table('sys_transactions')->create();
            $d->account = $faccount;
            $d->type = 'Transfer';

            $d->amount = $amount;

            $d->method = $pmethod;
            $d->ref = $ref;
            $d->tags = Arr::arr_to_str($tags);

            $d->description = $description;
            $d->date = $date;
            $d->dr = $amount;
            $d->cr = '0.00';
            $d->bal = $nbal;

            //others
            $d->payer = '';
            $d->payee = '';
            $d->payerid = '0';
            $d->payeeid = '0';
            $d->category = '';
            $d->status = 'Cleared';
            $d->tax = '0.00';
            $d->iid = 0;
            //

            $d->save();
            //transaction for target account
            $d = ORM::for_table('sys_transactions')->create();
            $d->account = $taccount;
            $d->type = 'Transfer';

            $d->amount = $amount;

            $d->method = $pmethod;
            $d->ref = $ref;
            $d->tags = Arr::arr_to_str($tags);
            $d->description = $description;
            $d->date = $date;
            $d->dr = '0.00';
            $d->cr = $amount;
            $d->bal = $tnbal;

            //others
            $d->payer = '';
            $d->payee = '';
            $d->payerid = '0';
            $d->payeeid = '0';
            $d->category = '';
            $d->status = 'Cleared';
            $d->tax = '0.00';
            $d->iid = 0;
            //

            $d->save();
            _msglog('s',$_L['Transaction Added Successfully']);
           echo '1';
        } else {
            echo $msg;
        }
        break;


    case 'list':

        $paginator = Paginator::bootstrap('sys_transactions');
        $d = ORM::for_table('sys_transactions')->offset($paginator['startpoint'])->limit($paginator['limit'])->order_by_desc('date')->find_many();
        $ui->assign('d',$d);
        $ui->assign('paginator',$paginator);
        $ui->display('transactions.tpl');
        break;

    case 'a':
        $d = ORM::for_table('sys_accounts')->find_many();
        // $p = ORM::for_table('sys_payers')->find_many();
        $p = ORM::for_table('crm_accounts')->find_many();
        $ui->assign('p', $p);
        $ui->assign('d', $d);
        $cats = ORM::for_table('sys_cats')->where('type','Income')->order_by_asc('sorder')->find_many();
        $ui->assign('cats', $cats);
        $pms = ORM::for_table('sys_pmethods')->find_many();
        $ui->assign('pms', $pms);
        $ui->assign('xheader', Asset::css(array('s2/css/select2.min','dp/dist/datepicker.min','dt/media/css/jquery.dataTables.min','modal','css/dta')));

        $ui->assign('xfooter', Asset::js(array('s2/js/select2.min','s2/js/i18n/'.lan(),'dp/dist/datepicker.min','dp/i18n/'.$config['language'],'numeric','modal','dt/media/js/jquery.dataTables.min','js/dta','js/tra')));

        $ui->assign('xjq', '


 ');

        $ui->display('tra.tpl');

        break;

    case 'tr_ajax':

        $filter = '';

        $d = ORM::for_table('sys_transactions');


        if(isset($_POST['order_id']) AND ($_POST['order_id'] != '')){
            // $iTotalRecords = ORM::for_table('flexi_req')->where('id',$_POST['order_id'])->count('id');
            $oid = _post('order_id');
            //  $filter .= "AND id='$oid' ";
            $d->where('id',$oid);
        }

        if(isset($_POST['sender']) AND ($_POST['sender'] != '')){
            $sender = _post('sender');
            // $filter .= "AND sender='$sender'";
            $d->where_like('sender', "%$sender%");
        }

        if(isset($_POST['receiver']) AND ($_POST['receiver'] != '')){
            $receiver = _post('receiver');
            // $filter .= "AND receiver='$receiver' ";
            $d->where_like('receiver', "%$receiver%");
        }

        if(isset($_POST['sdate']) AND ($_POST['sdate'] != '') AND isset($_POST['tdate']) AND ($_POST['tdate'] != '')){
            $sdate = _post('sdate');
            $tdate = _post('tdate');
            // $filter .= "AND reqlogtime >= '$sdate 00:00:00' AND reqlogtime <= '$tdate 23:59:59'";
            $d->where_gte('reqlogtime', "$sdate 00:00:00");
            $d->where_lte('reqlogtime', "$tdate 23:59:59");
        }

        if(isset($_POST['type']) AND ($_POST['type'] != '')){
            $type = _post('type');
            // $filter .= "AND type='$type' ";
            $d->where('type',$type);


        }



        if(isset($_POST['trid']) AND ($_POST['trid'] != '')){
            $trid = _post('trid');
            //  $filter .= "AND transactionid='$trid' ";
            $d->where('transactionid',$trid);

        }

        if(isset($_POST['op']) AND ($_POST['op'] != '')){
            $op = _post('op');
            //  $filter .= "AND op='$op' ";
            $d->where('op',$op);

        }

        $iTotalRecords =  $d->count();


        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;


        if($end > 1000){
            exit;
        }
        $d->order_by_desc('id');
        $d->limit($end);
        $d->offset($iDisplayStart);
        $x = $d->find_many();

        $i = $iDisplayStart;
        foreach ($x as $xs){




            $id = ($i + 1);
            $records["data"][] = array(
                '<input type="checkbox" name="id[]" value="'.$xs['id'].'">',
                $xs['id'],
                $xs['date'],
                $xs['account'],
                $xs['type'],

                $xs['amount'],
                $xs['description'],

                $xs['dr'],
                $xs['cr'],
                $xs['bal'],



                '<a href="#" class="fview btn btn-xs blue btn-editable" id="i'.$xs['id'].'"><i class="icon-list"></i> View</a>',
            );
        }


        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        $resp =  json_encode($records);
        $handler = PhpConsole\Handler::getInstance();
        $handler->start();
        $handler->debug($_REQUEST, 'request');
        echo $resp;


        break;

    case 'list-income':
        $ui->assign('_sysfrm_menu', 'reports');
        $paginator = Paginator::bootstrap('sys_transactions','type','Income');
        $d = ORM::for_table('sys_transactions')->where('type','Income')->offset($paginator['startpoint'])->limit($paginator['limit'])->order_by_desc('date')->find_many();
        $ui->assign('d',$d);
        $ui->assign('paginator',$paginator);
        $ui->display('transactions.tpl');
        break;

    case 'list-expense':

        $ui->assign('_sysfrm_menu', 'reports');
        $paginator = Paginator::bootstrap('sys_transactions','type','Expense');
        $d = ORM::for_table('sys_transactions')->where('type','Expense')->offset($paginator['startpoint'])->limit($paginator['limit'])->order_by_desc('date')->find_many();
        $ui->assign('d',$d);
        $ui->assign('paginator',$paginator);
        $ui->display('transactions.tpl');
        break;



    case 'manage':
        $id = $routes['2'];
        $t = ORM::for_table('sys_transactions')->find_one($id);
        if ($t) {
            $p = ORM::for_table('crm_accounts')->find_many();
            $ui->assign('p', $p);
            $ui->assign('t', $t);
            $d = ORM::for_table('sys_accounts')->find_many();
            $ui->assign('d', $d);
            $icat = '1';
            if(($t['type']) == 'Income'){
                $cats = ORM::for_table('sys_cats')->where('type','Income')->find_many();
                $tags = Tags::get_all('Income');
            }
            elseif(($t['type']) == 'Expense'){
                $cats = ORM::for_table('sys_cats')->where('type','Expense')->find_many();
                $tags = Tags::get_all('Expense');
            }
            else{
                $cats = '0';
                $icat = '0';
                $tags = Tags::get_all('Transfer');
            }

            $ui->assign('tags',$tags);
            $dtags = explode(',',$t['tags']);
            $ui->assign('dtags',$dtags);
            $ui->assign('icat', $icat);
            $ui->assign('cats', $cats);
            $pms = ORM::for_table('sys_pmethods')->find_many();
            $ui->assign('pms', $pms);

            $ui->assign('mdate', $mdate);
            $ui->assign('xheader', Asset::css(array('s2/css/select2.min','dp/dist/datepicker.min')));
            $ui->assign('xfooter', Asset::js(array('s2/js/select2.min','s2/js/i18n/'.lan(),'dp/dist/datepicker.min','dp/i18n/'.$config['language'],'numeric','tr-manage')));
            $ui->display('manage-transaction.tpl');
        } else {
            r2(U . 'transactions/list', 'e', $_L['Transaction_Not_Found']);
        }

        break;
    case 'edit-post':
        $id = _post('id');
        $d = ORM::for_table('sys_transactions')->find_one($id);
        if($d){
            $cat = _post('cats');
            $pmethod = _post('pmethod');
            $ref = _post('ref');
            $date = _post('date');
            $payer = _post('payer');
            $payee = _post('payee');
            $description = _post('description');
            $msg = '';
            if ($description == '') {
                $msg .= $_L['description_error'] . '<br>';
            }



            if(!is_numeric($payer)){
                $payer = '0';
            }

            if(!is_numeric($payee)){
                $payee = '0';
            }

            $tags = $_POST['tags'];


            if ($msg == '') {
                //find the current balance for this account

                Tags::save($tags,$d['type']);

                $d->category = $cat;
                $d->payerid = $payer;
                $d->payeeid = $payee;
                $d->method = $pmethod;
                $d->ref = $ref;
                $d->tags = Arr::arr_to_str($tags);
                $d->description = $description;
                $d->date = $date;

                $d->save();
                _msglog('s',$_L['edit_successful']);
                echo $d->id();
            } else {
                echo $msg;
            }
        }
        else{
            echo 'Transaction Not Found';
        }




        break;
    case 'delete-post':

        $id = _post('id');
        if(Transaction::delete($id)){
            r2(U . 'transactions/list', 's', $_L['transaction_delete_successful']);
        }
        else{
            r2(U . 'transactions/list', 'e', $_L['an_error_occured']);
        }
        break;


    case 'post':

        break;

    case 's':
        $d = ORM::for_table('sys_accounts')->find_many();
        // $p = ORM::for_table('sys_payers')->find_many();
        $c = ORM::for_table('crm_accounts')->find_many();
        $ui->assign('c', $c);
        $ui->assign('d', $d);
        $cats = ORM::for_table('sys_cats')->where('type','Income')->order_by_asc('sorder')->find_many();
        $ui->assign('cats', $cats);
        $pms = ORM::for_table('sys_pmethods')->find_many();
        $ui->assign('pms', $pms);
        $mdate = date('Y-m-d');
        $fdate = date('Y-m-d', strtotime('today - 30 days'));
        $ui->assign('fdate', $fdate);
        $ui->assign('tdate', $mdate);
        $ui->assign('xheader', Asset::css(array('s2/css/select2.min','dp/dist/datepicker.min','modal')));
        $ui->assign('xfooter', Asset::js(array('s2/js/select2.min','s2/js/i18n/'.lan(),'dp/dist/datepicker.min','dp/i18n/'.$config['language'],'numeric','modal','js/tra')));

        $ui->display('trs.tpl');


        break;

    default:
        echo 'action not defined';
}