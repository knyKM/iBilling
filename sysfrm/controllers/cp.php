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
if (isset($routes['1'])) {
    $do = $routes['1'];
} else {
    $do = 'login-display';
}
switch($do){
    case 'post':
        $username = _post('username');
        $password = _post('password');
        if($username != '' AND $password != ''){
            $d = ORM::for_table('sys_users')->where('username',$username)->find_one();
            if($d){
                $d_pass = $d['password'];
                if(Password::_verify($password,$d_pass) == true){
                    //Now check if OTP is enabled
                    if($d['otp'] == 'Yes'){
                        Otp::make($d['id']);
                        $_SESSION['tuid'] = $d['id'];

                        r2(U.'otp');
                    }
                    else{
                        $_SESSION['uid'] = $d['id'];
                        $d->last_login = date('Y-m-d H:i:s');
                        $d->save();
                        r2(U.$config['redirect_url']);
                    }

                }
                else{
                    _msglog('e','Invalid Username or Password');

                    r2(U.'login');
                }
            }
            else{

                _msglog('e','Invalid Username or Password');

                r2(U.'login');
            }
        }

        else{
            _msglog('e','Invalid Username or Password');

            r2(U.'login');
        }


        break;

    case 'login-display':
        $ui->display('login.tpl');
        break;

    default:
        $ui->display('login.tpl');
        break;
}

