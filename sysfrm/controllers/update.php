<?php
$latest_build = '3600';
$ui->assign('latest_build',$latest_build);
// Enable Error Reporting
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
$msg = '';

# Date: May 22, 2015


$d = ORM::for_table('sys_pl')->raw_query("SHOW COLUMNS FROM sys_pl LIKE 'name'")->find_one();

if($d){
    $r = ORM::for_table('sys_pl')->raw_execute("ALTER TABLE sys_pl DROP name, DROP url, DROP icon");
    $msg .= 'Updated sys_pl table
';
}

$d = ORM::for_table('crm_accounts')->raw_query("SHOW TABLES LIKE 'crm_customfields'")->find_one();

if(!$d){
    $r = ORM::for_table('crm_accounts')->raw_execute("CREATE TABLE IF NOT EXISTS crm_customfields (
id int(10) NOT NULL AUTO_INCREMENT,
  ctype text,
  relid int(10) NOT NULL DEFAULT '0',
  fieldname text,
  fieldtype text,
  description text,
  fieldoptions text,
  regexpr text,
  adminonly text,
  required text,
  showorder text,
  showinvoice text,
  sorder int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY ( id )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

    $msg .= 'Created table crm_customfields
';
}


$d = ORM::for_table('crm_accounts')->raw_query("SHOW TABLES LIKE 'crm_customfieldsvalues'")->find_one();

if(!$d){
    $r = ORM::for_table('crm_accounts')->raw_execute("CREATE TABLE IF NOT EXISTS crm_customfieldsvalues (
  id int(10) NOT NULL AUTO_INCREMENT,
  fieldid int(10) NOT NULL,
  relid int(10) NOT NULL,
  fvalue text NOT NULL,
  PRIMARY KEY ( id )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

    $msg .= 'Created table crm_customfieldsvalues
';
}

if(add_option('build',$latest_build)){
    $msg .= 'Build Row Created
';
}

if((get_option('build')) != $latest_build){
    update_option('build',$latest_build);
    $msg .= 'Build Updated to: '.$latest_build.'
';
}


// ALTER TABLE sys_invoices ADD discount DECIMAL(14,2) NOT NULL DEFAULT '0.00' AFTER subtotal;

// ALTER TABLE sys_invoices ADD discount_type VARCHAR(1) NOT NULL DEFAULT 'f' AFTER subtotal;

// SHOW COLUMNS FROM user LIKE 'flexi'"

$d = ORM::for_table('sys_invoices')->raw_query("SHOW COLUMNS FROM sys_invoices LIKE 'discount'")->find_one();


if(!$d){
    $r = ORM::for_table('sys_invoices')->raw_execute("ALTER TABLE sys_invoices ADD discount DECIMAL(14,2) NOT NULL DEFAULT '0.00' AFTER subtotal");
    $r = ORM::for_table('sys_invoices')->raw_execute("ALTER TABLE sys_invoices ADD discount_value DECIMAL(14,2) NOT NULL DEFAULT '0.00' AFTER subtotal");
    $r = ORM::for_table('sys_invoices')->raw_execute("ALTER TABLE sys_invoices ADD discount_type VARCHAR(1) NOT NULL DEFAULT 'f' AFTER subtotal");

    $msg .= 'Discount Column Created in Invoice Table
';
}


if(add_option('animate','1')){
    $msg .= 'Animate Row Created
';
}


if(add_option('pdf_font','dejavusanscondensed')){
    $msg .= 'Font Row Created
';
}


/*
 * @ From v 2.3
*/


$d = ORM::for_table('crm_customfields')->where('ctype','')->find_many();
foreach($d as $ds){
    $x = ORM::for_table('crm_customfields')->find_one($ds['id']);
    $x->ctype = 'crm';
    $x->save();
    $msg .= 'ctype changed for '.$ds['fieldname'].'
';
}





/*
 * @ From v 2.4
*/

// Added for Settings -> Choose Features


if(add_option('accounting','1')){
    $msg .= 'accounting Row Created
';
}

if(add_option('invoicing','1')){
    $msg .= 'invoicing Row Created
';
}

if(add_option('quotes','1')){
    $msg .= 'quotes Row Created
';
}

if(add_option('client_dashboard','1')){
    $msg .= 'client_dashboard Row Created
';
}



//creating table for quote


$d = ORM::for_table('crm_accounts')->raw_query("SHOW TABLES LIKE 'sys_quotes'")->find_one();

if(!$d){
    $r = ORM::execute("
    CREATE TABLE IF NOT EXISTS sys_quotes (
id int(10) NOT NULL AUTO_INCREMENT,
subject text NOT NULL,
stage enum('Draft','Delivered','On Hold','Accepted','Lost','Dead') NOT NULL,
validuntil date NOT NULL,
userid int(10) NOT NULL,
invoicenum text NOT NULL,
cn text NOT NULL,
account text NOT NULL,
firstname text NOT NULL,
lastname text NOT NULL,
companyname text NOT NULL,
email text NOT NULL,
address1 text NOT NULL,
address2 text NOT NULL,
city text NOT NULL,
state text NOT NULL,
postcode text NOT NULL,
country text NOT NULL,
phonenumber text NOT NULL,
currency int(10) NOT NULL,
subtotal decimal(10,2) NOT NULL,
discount_type text NOT NULL,
discount_value decimal(10,2) NOT NULL,
discount decimal(10,2) NOT NULL,
taxname text NOT NULL,
taxrate decimal(10,2) NOT NULL,
tax1 decimal(10,2) NOT NULL,
tax2 decimal(10,2) NOT NULL,
total decimal(10,2) NOT NULL,
proposal text NOT NULL,
customernotes text NOT NULL,
adminnotes text NOT NULL,
datecreated date NOT NULL,
lastmodified date NOT NULL,
datesent date NOT NULL,
dateaccepted date NOT NULL,
vtoken text NOT NULL,
PRIMARY KEY ( id )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000
    ");

    $msg .= 'Created table sys_quotes
';

    $r = ORM::execute("CREATE TABLE IF NOT EXISTS sys_quoteitems (
id int(10) NOT NULL AUTO_INCREMENT,
qid int(10) NOT NULL,
itemcode text NOT NULL,
description text NOT NULL,
qty text NOT NULL,
amount decimal(10,2) NOT NULL,
discount decimal(10,2) NOT NULL,
total decimal(10,2) NOT NULL,
taxable int(1) NOT NULL,
PRIMARY KEY ( id )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1
 ");

    $msg .= 'Created table sys_quoteitems
';

}


$d = ORM::for_table('sys_email_templates')->where('tplname','Quote:Quote Created')->find_one();
if(!$d){
    ORM::execute("INSERT INTO sys_email_templates (tplname, language_id, subject, message, send, core, hidden) VALUES
('Quote:Quote Created', 1, '{{quote_subject}}', '<div style=\"line-height:1.6;color:#222;text-align:left;width:550px;font-size:10pt;margin:0px 10px;font-family:verdana,sans-serif;padding:14px;border:3px solid #d8d8d8;border-top:3px solid #007bc3\"><div style=\"padding:5px;font-size:11pt;font-weight:bold\">   Greetings,</div>	<div style=\"padding:5px\">		Dear {{contact_name}},&nbsp;<br> Here is the quote you requested for.  The quote is valid until {{valid_until}}.	</div><div style=\"padding:10px 5px\">    Quote Unique URL: <a href=\"{{quite_url}}\" target=\"_blank\">{{quote_url}}</a><br></div><div style=\"padding:5px\"><span style=\"font-size: 13.3333330154419px; line-height: 21.3333320617676px;\">You may view the quote at any time and simply reply to this email with any further questions or requirement.</span><br></div><div style=\"padding:0px 5px\">	<div>Best Regards,<br>{{business_name}} Team</div></div></div>', 'Yes', 'Yes', 0)
");

    $msg .= 'Quote Email Template Created
';

}


$d = ORM::for_table('sys_invoices')->raw_query("SHOW COLUMNS FROM sys_invoices LIKE 'cn'")->find_one();


if(!$d){

   ORM::execute("ALTER TABLE sys_invoices ADD cn VARCHAR(100) NOT NULL DEFAULT '' AFTER account");

    $msg .= 'Custom Invoice Number Column Created in Invoice Table
';
}


// Braintree and ccavenue Payment Gateway from v2.4


// INSERT INTO sys_pg (name, settings, value, processor, ins, c1, c2, c3, c4, c5, status, sorder) VALUES('Braintree', 'Merchant ID', 'your merchant id', 'braintree', '', 'your public key', 'your private key', 'bank account', 'sandbox', '', 'Active', 5);
$d = ORM::for_table('sys_pg')->where('processor','braintree')->find_one();

if(!$d){
   ORM::execute("INSERT INTO sys_pg (name, settings, value, processor, ins, c1, c2, c3, c4, c5, status, sorder) VALUES('Braintree', 'Merchant ID', 'your merchant id', 'braintree', '', 'your public key', 'your private key', 'bank account', 'sandbox', '', 'Inactive', 5)");
   ORM::execute("INSERT INTO sys_pg (name, settings, value, processor, ins, c1, c2, c3, c4, c5, status, sorder) VALUES('CCAvenue', 'Merchant ID', 'your merchant id', 'ccavenue', '', 'insert working key here', 'INR', '1', '', '', 'Inactive', 6)");

    $msg .= 'PG 2.4 Rows created
';

}



// =============================================== V 3.0.0 ===============================================

// For API support

$d = ORM::for_table('crm_accounts')->raw_query("SHOW TABLES LIKE 'sys_api'")->find_one();

if(!$d){

    $t = new Schema('sys_api');
    $t->add('label');
    $t->add('ip');
    $t->add('apikey');
    $t->save();

    $msg .= 'API Table is created
';

}

if(add_option('contact_set_view_mode','card')){
    $msg .= 'contact_set_view_mode Row Created
';
}


// End ==================================

// Version 3.2

if(file_exists('sysfrm/controllers/cases.php')){
    unlink('sysfrm/controllers/cases.php');
}

if(file_exists('sysfrm/controllers/notes.php')){
    unlink('sysfrm/controllers/notes.php');
}


// Version 3.3


if(add_option('invoice_terms','')){
    $msg .= 'Invoice Terms Row Created
';
}


if(add_option('console_notify_invoice_created','0')){
    $msg .= 'console_notify_invoice_created Row Created
';
}


// Version 3.4

if(add_option('i_driver','v2')){
    $msg .= 'i_driver Row Created
';
}



// Version 3.6


$d = ORM::for_table('sys_invoices')->raw_query("SHOW COLUMNS FROM sys_invoices LIKE 'eid'")->find_one();


if(!$d){

    ORM::execute("ALTER TABLE sys_invoices ADD eid INT(10) NOT NULL DEFAULT '0' AFTER nd, ADD ename VARCHAR(200) NOT NULL DEFAULT '' AFTER eid");

    $msg .= 'Emp Column Created in Invoice Table
';
}


// End Update


if($msg == ''){
    $msg = 'Done! You are using Latest Version!';
}
else{
    $msg .= 'Update Completed!
';
}

$ui->assign('msg',$msg);

$ui->display('update.tpl');
