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
Event::trigger('dashboard/');
$ui->assign('_title', $_L['Dashboard'].'- '. $config['CompanyName']);
$ui->assign('_st', $_L['Dashboard']);
$user = User::_info();
$ui->assign('user',$user);
$d = ORM::for_table('sys_accounts')->order_by_desc('balance')->limit(5)->find_many();
$tbal = ORM::for_table('sys_accounts')->sum('balance');
$tbal = number_format($tbal,'2','.','');
$ui->assign('d',$d);
$ui->assign('tbal',$tbal);
$fdate = date('Y-m-01');
$tdate = date('Y-m-t');
//first day of month
$first_day_month = date('Y-m-01');
$mdate = date('Y-m-d');
$month_n = date('n');
$mi = ORM::for_table('sys_transactions')->where('type','Income')->where_gte('date',$first_day_month)->where_lte('date',$mdate)->sum('cr');
if($mi == ''){
    $mi = '0.00';
}
$ui->assign('mi',$mi);
$me = ORM::for_table('sys_transactions')->where('type','Expense')->where_gte('date',$first_day_month)->where_lte('date',$mdate)->sum('dr');
if($me == ''){
    $me = '0.00';
}
$ui->assign('me',$me);
$m = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$mdate)->sum('cr');
if($m == ''){
    $m = '0.00';
}
$ui->assign('ti',$m);
$m = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$mdate)->sum('dr');
if($m == ''){
    $m = '0.00';
}
$ui->assign('te',$m);
$out = array();
$d = ORM::for_table('sys_repeating');
$d->where_gte('date', $fdate);
$d->where_lte('date', $tdate);
$d->where('type','Expense');
$d->where('status','Uncleared');
$d->order_by_asc('date');
$d->limit(5);
$rx =  $d->find_many();
$ui->assign('rx',$rx);
$d = ORM::for_table('sys_transactions')->where('type','Expense')->limit(5)->order_by_desc('id')->find_many();
$ui->assign('exp',$d);
$d = ORM::for_table('sys_transactions')->where('type','Income')->limit(5)->order_by_desc('id')->find_many();
$ui->assign('inc',$d);

$net_worth = ORM::for_table('sys_accounts')->sum('balance');
$ui->assign('net_worth',$net_worth);
$goal = $config['networth_goal'];
$v_goal = number_format($goal,2,$config['dec_point'],$config['thousands_sep']);
if($goal != '' AND $goal != '0'){
    $division = $net_worth / $goal;
    $pg = $division * 100;
    $pg = number_format($pg, 2,'.','');
}
else{
    $pg = '0';
}
$ui->assign('pg',$pg);
if($pg <= 100){
    $pgb = $pg;
}
else{
    $pgb = '100';
}
$ui->assign('pgb',$pgb);
if($pgb < 49){
    $ui->assign('pgc','danger');
}
elseif($pgb < 69){
    $ui->assign('pgc','warning');
}
else{
    $ui->assign('pgc','success');
}
//$month_n = date('n');
//$array = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
//$till = $month_n - 1;
//$gstring = '';
//$egstring = '';
//for ($m=0; $m<=$till; $m++) {
//    $mnth = $array[$m];
//    $cal = ORM::for_table('sys_transactions')->where_gte('date',date('Y-m-d',strtotime("first day of $mnth")))->where_lte('date',date('Y-m-d',strtotime("last day of $mnth")))->sum('dr');
//    $egstring .= '["'.$m.'",'.$cal.'], ';
//    $cal = ORM::for_table('sys_transactions')->where_gte('date',date('Y-m-d',strtotime("first day of $mnth")))->where_lte('date',date('Y-m-d',strtotime("last day of $mnth")))->sum('cr');
//    $gstring .= '["'.$m.'",'.$cal.'], ';
//
//}
//$gstring = rtrim($gstring,',');

$d1 = date('Y-m-01');
$d1i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d1)->sum('cr');
if($d1i == ''){
    $d1i = '0.00';
}
$d1e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d1)->sum('dr');
if($d1e == ''){
    $d1e = '0.00';
}


$d2 = date('Y-m-02');
$d2i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d2)->sum('cr');
if($d2i == ''){
    $d2i = '0.00';
}
$d2e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d2)->sum('dr');
if($d2e == ''){
    $d2e = '0.00';
}

$d3 = date('Y-m-03');
$d3i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d3)->sum('cr');
if($d3i == ''){
    $d3i = '0.00';
}
$d3e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d3)->sum('dr');
if($d3e == ''){
    $d3e = '0.00';
}

$d4 = date('Y-m-04');
$d4i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d4)->sum('cr');
if($d4i == ''){
    $d4i = '0.00';
}
$d4e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d4)->sum('dr');
if($d4e == ''){
    $d4e = '0.00';
}


$d5 = date('Y-m-05');
$d5i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d5)->sum('cr');
if($d5i == ''){
    $d5i = '0.00';
}
$d5e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d5)->sum('dr');
if($d5e == ''){
    $d5e = '0.00';
}

$d6 = date('Y-m-06');
$d6i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d6)->sum('cr');
if($d6i == ''){
    $d6i = '0.00';
}
$d6e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d6)->sum('dr');
if($d6e == ''){
    $d6e = '0.00';
}

$d7 = date('Y-m-07');
$d7i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d7)->sum('cr');
if($d7i == ''){
    $d7i = '0.00';
}
$d7e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d7)->sum('dr');
if($d7e == ''){
    $d7e = '0.00';
}

$d8 = date('Y-m-08');
$d8i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d8)->sum('cr');
if($d8i == ''){
    $d8i = '0.00';
}
$d8e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d8)->sum('dr');
if($d8e == ''){
    $d8e = '0.00';
}


$d9 = date('Y-m-09');
$d9i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d9)->sum('cr');
if($d9i == ''){
    $d9i = '0.00';
}
$d9e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d9)->sum('dr');
if($d9e == ''){
    $d9e = '0.00';
}

$d10 = date('Y-m-10');
$d10i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d10)->sum('cr');
if($d10i == ''){
    $d10i = '0.00';
}
$d10e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d10)->sum('dr');
if($d10e == ''){
    $d10e = '0.00';
}


$d11 = date('Y-m-11');
$d11i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d11)->sum('cr');
if($d11i == ''){
    $d11i = '0.00';
}
$d11e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d11)->sum('dr');
if($d11e == ''){
    $d11e = '0.00';
}

$d12 = date('Y-m-12');
$d12i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d12)->sum('cr');
if($d12i == ''){
    $d12i = '0.00';
}
$d12e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d12)->sum('dr');
if($d12e == ''){
    $d12e = '0.00';
}


$d13 = date('Y-m-13');
$d13i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d13)->sum('cr');
if($d13i == ''){
    $d13i = '0.00';
}
$d13e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d13)->sum('dr');
if($d13e == ''){
    $d13e = '0.00';
}


$d14 = date('Y-m-14');
$d14i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d14)->sum('cr');
if($d14i == ''){
    $d14i = '0.00';
}
$d14e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d14)->sum('dr');
if($d14e == ''){
    $d14e = '0.00';
}


$d15 = date('Y-m-15');
$d15i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d15)->sum('cr');
if($d15i == ''){
    $d15i = '0.00';
}
$d15e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d15)->sum('dr');
if($d15e == ''){
    $d15e = '0.00';
}


$d16 = date('Y-m-16');
$d16i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d16)->sum('cr');
if($d16i == ''){
    $d16i = '0.00';
}
$d16e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d16)->sum('dr');
if($d16e == ''){
    $d16e = '0.00';
}

$d17 = date('Y-m-17');
$d17i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d17)->sum('cr');
if($d17i == ''){
    $d17i = '0.00';
}
$d17e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d17)->sum('dr');
if($d17e == ''){
    $d17e = '0.00';
}

$d18 = date('Y-m-18');
$d18i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d18)->sum('cr');
if($d18i == ''){
    $d18i = '0.00';
}
$d18e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d18)->sum('dr');
if($d18e == ''){
    $d18e = '0.00';
}

$d19 = date('Y-m-19');
$d19i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d19)->sum('cr');
if($d19i == ''){
    $d19i = '0.00';
}
$d19e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d19)->sum('dr');
if($d19e == ''){
    $d19e = '0.00';
}


$d20 = date('Y-m-20');
$d20i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d20)->sum('cr');
if($d20i == ''){
    $d20i = '0.00';
}
$d20e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d20)->sum('dr');
if($d20e == ''){
    $d20e = '0.00';
}



$d21 = date('Y-m-21');
$d21i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d21)->sum('cr');
if($d21i == ''){
    $d21i = '0.00';
}
$d21e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d21)->sum('dr');
if($d21e == ''){
    $d21e = '0.00';
}

$d22 = date('Y-m-22');
$d22i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d22)->sum('cr');
if($d22i == ''){
    $d22i = '0.00';
}
$d22e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d22)->sum('dr');
if($d22e == ''){
    $d22e = '0.00';
}

$d23 = date('Y-m-23');
$d23i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d23)->sum('cr');
if($d23i == ''){
    $d23i = '0.00';
}
$d23e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d23)->sum('dr');
if($d23e == ''){
    $d23e = '0.00';
}

$d24 = date('Y-m-24');
$d24i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d24)->sum('cr');
if($d24i == ''){
    $d24i = '0.00';
}
$d24e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d24)->sum('dr');
if($d24e == ''){
    $d24e = '0.00';
}

$d25 = date('Y-m-25');
$d25i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d25)->sum('cr');
if($d25i == ''){
    $d25i = '0.00';
}
$d25e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d25)->sum('dr');
if($d25e == ''){
    $d25e = '0.00';
}

$d26 = date('Y-m-26');
$d26i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d26)->sum('cr');
if($d26i == ''){
    $d26i = '0.00';
}
$d26e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d26)->sum('dr');
if($d26e == ''){
    $d26e = '0.00';
}

$d27 = date('Y-m-27');
$d27i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d27)->sum('cr');
if($d27i == ''){
    $d27i = '0.00';
}
$d27e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d27)->sum('dr');
if($d27e == ''){
    $d27e = '0.00';
}


$d28 = date('Y-m-28');
$d28i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d28)->sum('cr');
if($d28i == ''){
    $d28i = '0.00';
}
$d28e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d28)->sum('dr');
if($d28e == ''){
    $d28e = '0.00';
}

$d29 = date('Y-m-29');
$d29i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d29)->sum('cr');
if($d29i == ''){
    $d29i = '0.00';
}
$d29e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d29)->sum('dr');
if($d29e == ''){
    $d29e = '0.00';
}

$d30 = date('Y-m-30');
$d30i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d30)->sum('cr');
if($d30i == ''){
    $d30i = '0.00';
}
$d30e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d30)->sum('dr');
if($d30e == ''){
    $d30e = '0.00';
}

$d31 = date('Y-m-31');
$d31i = ORM::for_table('sys_transactions')->where('type','Income')->where('date',$d31)->sum('cr');
if($d31i == ''){
    $d31i = '0.00';
}
$d31e = ORM::for_table('sys_transactions')->where('type','Expense')->where('date',$d31)->sum('dr');
if($d31e == ''){
    $d31e = '0.00';
}




$invoices = ORM::for_table('sys_invoices')->limit(5)->order_by_desc('id')->find_many();
$ui->assign('invoices',$invoices);


$ui->assign('xheader', '
<link href="'.APP_URL.'/ui/lib/c3/c3.min.css" rel="stylesheet" type="text/css">
');

$ui->assign('xfooter', '
<script type="text/javascript" src="'.APP_URL.'/ui/lib/jslib/dashboard-alt.js"></script>
<script type="text/javascript" src="'.APP_URL.'/ui/lib/c3/d3.min.js"></script>
<script type="text/javascript" src="'.APP_URL.'/ui/lib/c3/c3.min.js"></script>

');

$ui->assign('xjq', '

var chart = c3.generate({
    bindto: \'#chart\',
    data: {
	columns: [

		[\''.$_L['Income'].'\', \'0\','.$d1i.','.$d2i.', '.$d3i.', '.$d4i.', '.$d5i.', '.$d6i.', '.$d7i.', '.$d8i.', '.$d9i.', '.$d10i.', '.$d11i.', '.$d12i.', '.$d13i.', '.$d14i.', '.$d15i.', '.$d16i.', '.$d17i.', '.$d18i.', '.$d19i.', '.$d20i.', '.$d21i.', '.$d22i.', '.$d23i.', '.$d24i.', '.$d25i.', '.$d26i.', '.$d27i.', '.$d28i.', '.$d29i.', '.$d30i.', '.$d31i.'],
		[\''.$_L['Expense'].'\', \'0\','.$d1e.','.$d2e.', '.$d3e.', '.$d4e.', '.$d5e.', '.$d6e.', '.$d7e.', '.$d8e.', '.$d9e.', '.$d10e.', '.$d11e.', '.$d12e.', '.$d13e.', '.$d14e.', '.$d15e.', '.$d16e.', '.$d17e.', '.$d18e.', '.$d19e.', '.$d20e.', '.$d21e.', '.$d22e.', '.$d23e.', '.$d24e.', '.$d25e.', '.$d26e.', '.$d27e.', '.$d28e.', '.$d29e.', '.$d30e.', '.$d31e.']
	],
        type: \'area-spline\',
         colors: {
            '.$_L['Income'].': \'#23c6c8\',
            '.$_L['Expense'].': \'#ed5565\'
        }
    }

});

var dchart = c3.generate({
    bindto: \'#dchart\',
    data: {
        columns: [
            [\''.$_L['Income'].'\', '.$mi.'],
            [\''.$_L['Expense'].'\', '.$me.'],
        ],
        type : \'donut\',
        colors: {
            '.$_L['Income'].': \'#23c6c8\',
            '.$_L['Expense'].': \'#ed5565\'
        }
    },
    donut: {
        title: "'.$_L['Income_Vs_Expense'].'"
    }
});

    $("#set_goal").click(function (e) {
        e.preventDefault();

        bootbox.prompt({
            title: "'.$_L['Set New Goal for Net Worth'].'",
            value: "'.$v_goal.'",
            buttons: {
        \'cancel\': {
            label: \''.$_L['Cancel'].'\'
        },
        \'confirm\': {
            label: \''.$_L['OK'].'\'
        }
    },
            callback: function(result) {
                if (result === null) {

                } else {
                   // alert(result);
                     $.post( "'.U.'settings/networth_goal/", { goal: result })
        .done(function( data ) {
            location.reload();
        });
                }
            }
        });

    });

 ');

Event::trigger('dashboard/_on_display');

$ui->display('dashboard-alt.tpl');