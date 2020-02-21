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
$ui->assign('_title', $_L['Reports'].'- '. $config['CompanyName']);
$ui->assign('_pagehead', '<i class="fa fa-bar-chart-o lblue"></i> Reports');

$ui->assign('_sysfrm_menu', 'reports');
$action = $routes['1'];
$user = User::_info();
$ui->assign('user', $user);
$mdate = date('Y-m-d');
$tdate = date('Y-m-d', strtotime('today - 30 days'));

//first day of month
$first_day_month = date('Y-m-01');
//
$this_week_start = date('Y-m-d',strtotime( 'previous sunday'));
// 30 days before
$before_30_days = date('Y-m-d', strtotime('today - 30 days'));
//this month
$month_n = date('n');

switch ($action) {


    case 'printable':

        $fdate = _post('fdate');
        $tdate = _post('tdate');
        $account = _post('account');
        $stype = _post('stype');
        $d = ORM::for_table('sys_transactions');
        $d->where('account', $account);
        if($stype == 'credit'){
            $d->where('dr', '0.00');
        }
        elseif($stype == 'debit'){
            $d->where('cr', '0.00');
        }
        else{

        }
        $d->where_gte('date', $fdate);
        $d->where_lte('date', $tdate);
        $d->order_by_desc('id');
        $x =  $d->find_many();

        $ui->assign('d',$x);
        $ui->assign('fdate',$fdate);
        $ui->assign('tdate',$tdate);
        $ui->assign('account',$account);

        $ui->display('printable.tpl');
        break;

    case 'pdf':

        $fdate = _post('fdate');
        $tdate = _post('tdate');
        $account = _post('account');
        $stype = _post('stype');
        $d = ORM::for_table('sys_transactions');
        $d->where('account', $account);
        if($stype == 'credit'){
            $d->where('dr', '0.00');
        }
        elseif($stype == 'debit'){
            $d->where('cr', '0.00');
        }
        else{

        }
        $d->where_gte('date', $fdate);
        $d->where_lte('date', $tdate);
        $d->order_by_desc('id');
        $x =  $d->find_many();




        $aadmin= $user['fullname'];
        // $filename= date('Y-m-d')._raid(4).'.pdf';
        $title = $account. ' Statement ['.$fdate.' - '.$tdate.']';
        $title = str_replace('-',' ',$title);



        if ($x) {
            $html = '<table id="customers">
<tr>
<th>'.$_L['Date'].'</th>
<th>'.$_L['Description'].'</th>
<th>'.$_L['Dr'].'</th>
<th>'.$_L['Cr'].'</th>
<th>'.$_L['Balance'].'</th>
</tr>';
            $c = true;
            foreach ($x as $value) {


             //   $date = $value['date'];
                $date = date( $config['df'], strtotime($value['date']));

                $description = $value['description'];

                $dr = $value['dr'];
                $cr = $value['cr'];
                $bal = $value['bal'];



                $html .= "<tr".(($c = !$c)?' class="alt"':' class=""').">"."
<td>$date</td>
<td>$description</td>
<td>$dr</td>
<td>$cr</td>
<td>$bal</td>
</tr>";
            }
            $html .= '</table>';
            //  exit ("$html");

//            require ('sysfrm/lib/tcpdf/config/lang/eng.php');
//            require ('sysfrm/lib/tcpdf/tcpdf.php');
//            // create new PDF document
//            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//
//// set document information
//            $pdf->SetCreator('SysFrm');
//            $pdf->SetAuthor($aadmin);
//            $pdf->SetTitle($title);
//            $pdf->SetSubject($title);
//
//
//// set default header data
//            $pdf->SetHeaderData('', '', $title, "Generated on ".date('d/m/Y')." \nby ".$aadmin);
//
//// set header and footer fonts
//            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
////$pdf->SetFont('freesans', '', 10);
//// set default monospaced font
//            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//
////set margins
//            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//
////set auto page breaks
//            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//
////set image scale factor
//            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//
////set some language-dependent strings
//            //  $pdf->setLanguageArray();
//
//// ---------------------------------------------------------
//
//// set font
//            $pdf->SetFont('freesans', '', 10);
//
//// add a page
//            $pdf->AddPage();

            define('_MPDF_PATH','sysfrm/lib/mpdf/');

            require('sysfrm/lib/mpdf/mpdf.php');

            $mpdf=new mPDF('c','A4','','',20,15,48,25,10,10);
            $mpdf->SetProtection(array('print'));
            $mpdf->SetTitle($config['CompanyName'].' Invoice');
            $mpdf->SetAuthor($config['CompanyName']);
            $mpdf->SetWatermarkText($d['status']);
            $mpdf->showWatermarkText = true;
            $mpdf->watermark_font = 'Helvetica';
            $mpdf->watermarkTextAlpha = 0.1;
            $mpdf->SetDisplayMode('fullpage');

            $style = '<style>
#customers
{
font-family: Helvetica, sans-serif;
width:100%;
border-collapse:collapse;
}
#customers td, #customers th
{
font-size:1.2em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#customers th
{
font-size:1.4em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#fff;
}
#customers tr.alt td
{
color:#000;
background-color:#EAF2D3;
}
</style>
';

///////////////////////////////////////////////////html

            $nhtml = <<<EOF
$style
$html
EOF;
            //  exit ("$nhtml");

            $mpdf->WriteHTML($nhtml);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// reset pointer to the last page


// ---------------------------------------------------------

//Close and output PDF document
            $mpdf->Output(date('Y-m-d')._raid(4).'.pdf', 'D');

        }
        else{
            echo 'No Data';
        }

        break;


    default:
        echo 'action not defined';
}