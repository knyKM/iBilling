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
$mdate = $routes['1'];
$mdate = $mdate/1000;
$mdate = date('Y-m-d',$mdate);
$d = ORM::for_table('sys_transactions')->where('date',$mdate)->order_by_desc('id')->find_many();
$dr = ORM::for_table('sys_transactions')->where('date',$mdate)->sum('dr');
if($dr == ''){
    $dr = '0.00';
}
$cr = ORM::for_table('sys_transactions')->where('date',$mdate)->sum('cr');
if($cr == ''){
    $cr = '0.00';
}
?>
<div class="panel-body" style="background: #ffffff; margin-top: 10px;">
<h4><?php echo $_L['Total Income']; ?>: <?php echo $config['currency_code'] .' '. number_format($cr,2,$config['dec_point'],$config['thousands_sep']); ?></h4>
<h4><?php echo $_L['Total Expense']; ?>:  <?php echo $config['currency_code'] .' '. number_format($dr,2,$config['dec_point'],$config['thousands_sep']); ?></h4>

<hr>
<h4><?php echo $_L['All Transactions at Date']; ?>:  <?php echo date( $config['df'], strtotime($mdate)); ?></h4>
<hr>
<table class="table table-striped table-bordered">

    <th><?php echo $_L['Account']; ?></th>
    <th><?php echo $_L['Type']; ?></th>
    <th><?php echo $_L['Category']; ?></th>
    <th class="text-right"><?php echo $_L['Amount']; ?></th>
    <th><?php echo $_L['Payer']; ?></th>
    <th><?php echo $_L['Payee']; ?></th>
    <th><?php echo $_L['Method']; ?></th>
    <th><?php echo $_L['Ref']; ?></th>
    <th><?php echo $_L['Description']; ?></th>
    <th class="text-right"><?php echo $_L['Dr']; ?></th>
    <th class="text-right"><?php echo $_L['Cr']; ?></th>
    <th class="text-right"><?php echo $_L['Balance']; ?></th>
<?php

   foreach($d as $ds){
       $cls = '';
       if(($ds['bal']) < 0){
           $cls = 'class="text-red"';
       }

       if($ds['category'] == 'Uncategorized'){
           $cat = $_L['Uncategorized'];
       }
       else{
           $cat = $ds['category'];
       }

       echo ' <tr>

        <td>'.$ds['account'].'</td>
        <td>'.ib_lan_get_line($ds['type']).'</td>
        <td>'.$cat.'</td>
        <td class="text-right">'.$config['currency_code'].' '.number_format($ds['amount'],2,$config['dec_point'],$config['thousands_sep']).'</td>
        <td>'.$ds['payer'].'</td>
        <td>'.$ds['payee'].'</td>
        <td>'.$ds['method'].'</td>
        <td>'.$ds['ref'].'</td>
        <td>'.$ds['description'].'</td>
        <td class="text-right">'.$config['currency_code'].' '.number_format($ds['dr'],2,$config['dec_point'],$config['thousands_sep']).'</td>
        <td class="text-right">'.$config['currency_code'].' '.number_format($ds['cr'],2,$config['dec_point'],$config['thousands_sep']).'</td>
        <td class="text-right"><span '.$cls.'>'.$config['currency_code'].' '.number_format($ds['bal'],2,$config['dec_point'],$config['thousands_sep']).'</span></td>

    </tr>';
   }



?>
</table>
    </div>