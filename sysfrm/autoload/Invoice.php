<?php
Class Invoice
{

    public static function gen_email($iid, $etpl)
    {

        global $config;

        $d = ORM::for_table('sys_invoices')->find_one($iid);

        if ($etpl == 'created') {
            $e = ORM::for_table('sys_email_templates')->where('tplname', 'Invoice:Invoice Created')->find_one();
        } elseif ($etpl == 'reminder') {
            $e = ORM::for_table('sys_email_templates')->where('tplname', 'Invoice:Invoice Payment Reminder')->find_one();
        } elseif ($etpl == 'overdue') {
            $e = ORM::for_table('sys_email_templates')->where('tplname', 'Invoice:Invoice Overdue Notice')->find_one();
        } elseif ($etpl == 'confirm') {
            $e = ORM::for_table('sys_email_templates')->where('tplname', 'Invoice:Invoice Payment Confirmation')->find_one();
        } elseif ($etpl == 'refund') {
            $e = ORM::for_table('sys_email_templates')->where('tplname', 'Invoice:Invoice Refund Confirmation')->find_one();
        } else {
            $d = false;
            $e = false;
        }

        if ($d) {

            $a = ORM::for_table('crm_accounts')->find_one($d['userid']);
            if ($d['cn'] != '') {
                $dispid = $d['cn'];
            } else {
                $dispid = $d['id'];
            }
            $invoice_num = $d['invoicenum'] . $dispid;
            //parse template
            $total = $d['total'];
            $credit = $d['credit'];
            $due_amount = $total - $credit;
            $tax = $d['tax'];
            $taxrate = $d['taxrate'];
            $subtotal = $d['subtotal'];
            $subject = new Template($e['subject']);
            $subject->set('business_name', $config['CompanyName']);
            $subject->set('invoice_id', $invoice_num);
            $subj = $subject->output();
            $message = new Template($e['message']);
            $message->set('name', $a['account']);
            $message->set('business_name', $config['CompanyName']);
            $message->set('invoice_url', U . 'client/iview/' . $d['id'] . '/token_' . $d['vtoken']);
            $message->set('invoice_id', $invoice_num);
            $message->set('invoice_status', $d['status']);
            $message->set('invoice_amount_paid', number_format($credit, 2, $config['dec_point'], $config['thousands_sep']));
            $message->set('invoice_due_amount', number_format($due_amount, 2, $config['dec_point'], $config['thousands_sep']));
            $message->set('invoice_taxname', $d['taxname']);
            $message->set('invoice_tax_amount', number_format($tax, 2, $config['dec_point'], $config['thousands_sep']));
            $message->set('invoice_tax_rate', number_format($taxrate, 2, $config['dec_point'], $config['thousands_sep']));
            $message->set('invoice_subtotal', number_format($subtotal, 2, $config['dec_point'], $config['thousands_sep']));
            $message->set('invoice_due_date', date($config['df'], strtotime($d['duedate'])));
            $message->set('invoice_date', date($config['df'], strtotime($d['date'])));
            $message->set('invoice_amount', number_format($total, 2, $config['dec_point'], $config['thousands_sep']));
            $message_o = $message->output();

            $gen = array();

            $gen['cid'] = $a['id'];
            $gen['name'] = $a['account'];
            $gen['email'] = $a['email'];
            $gen['subject'] = $subj;
            $gen['body'] = $message_o;

            return $gen;



        }

        else{
            return false;
        }


    }

}