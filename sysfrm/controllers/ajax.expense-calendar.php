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
$fdate = _get('from');
$fdate = $fdate/1000;
$fdate = date('Y-m-d',$fdate);
$tdate = _get('to');
$tdate = $tdate/1000;
$tdate = date('Y-m-d',$tdate);
$out = array();
//find current month
$d = ORM::for_table('sys_repeating');
$d->where_gte('date', $fdate);
$d->where_lte('date', $tdate);
$d->where('type','Expense');
$d->where('status','Uncleared');
$x =  $d->find_many();
foreach($x as $xs){
    $date = $xs['date'];
$id = $xs['id'];
    $description = $xs['description'].' [Amount: '.$config['currency_code'].' '.number_format($xs['amount'],2,$config['dec_point'],$config['thousands_sep']).']';
    $url = U.'repeating/view/'.$xs['id'];
    $out[] = array(
        'id' => $id,
        'title' => $description,
        'url' => $url,
        'class' => 'event-important',
        'start' => strtotime($date).'000'
    );
}

echo json_encode(array('success' => 1, 'result' => $out));
exit;