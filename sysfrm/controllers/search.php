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
$ui->assign('_sysfrm_menu', 'contacts');
$ui->assign('_st', $_L['Search']);
$ui->assign('_title', $_L['Accounts'].'- '. $config['CompanyName']);
$action = $routes['1'];
$user = User::_info();
$ui->assign('user', $user);
switch ($action) {

    case 'ps':
$type = _post('stype');
$name = _post('txtsearch');
        $d = ORM::for_table('sys_items')->where('type',$type)->where_like('name',"%$name%")->order_by_asc('name')->find_many();
if($d){
    echo '<table class="table table-hover">
        <tbody>';


    foreach ($d as $ds){
        $price = number_format($ds['sales_price'],2,$config['dec_point'],$config['thousands_sep']);
        echo ' <tr>

                <td class="project-title">
                    <a href="#" class="cedit"  id="t'.$ds['id'].'">'.$ds['name'].'</a>
                    <br>
                    <small>'.$ds['item_number'].'</small>
                </td>
                <td>

                   '.$price.'

                </td>

                <td class="project-actions">

                    <a href="#" class="btn btn-primary btn-sm cedit" id="e'.$ds['id'].'"><i class="fa fa-pencil"></i> '.$_L['Edit'].' </a>
                    <a href="#" class="btn btn-danger btn-sm cdelete" id="pid'.$ds['id'].'"><i class="fa fa-trash"></i> '.$_L['Delete'].' </a>
                </td>
            </tr>';
    }


    echo '
        </tbody>
    </table>';
}
else{
    echo '<h4>Nothing Found</h4>';
}

        break;


    case 'users':
echo '<table class="table table-bordered table-hover trclickable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Access Level</th>
                    <th>Active</th>
                </tr>
                </thead>
                <tbody>
                <tr id="_tr120">
                    <td>1</td>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td><div class="switch">
                            <div class="onoffswitch">
                                <input type="checkbox" class="onoffswitch-checkbox" data-on-text="Yes">
                                <label class="onoffswitch-label" for="fixednavbar">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div></td>
                </tr>

                </tbody>
            </table>';
        break;

    default:
        echo 'action not defined';
}