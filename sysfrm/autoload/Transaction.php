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
Class Transaction{
    public static function delete($id){

        //find the transaction
        $t = ORM::for_table('sys_transactions')->find_one($id);
        if($t){
            $a = ORM::for_table('sys_accounts')->where('account',$t['account'])->find_one();
            $cr = $t['cr'];
            $dr = $t['dr'];
            if($a){
                $cbal = $a['balance'];
                if($cr != '0.00'){
                    $nbal = $cbal-$cr;
                }
                else{
                    $nbal = $cbal+$dr;
                }
                $a->balance = $nbal;
                $a->save();

            }

            $t->delete();
            return true;
        }

        else{
            return false;
        }


//        if($t){
//            //find affected rows
//            $d = ORM::for_table('sys_transactions')->where_gt('id',$id)->where('account',$t['account'])->find_many();
//
//        }


    }

}