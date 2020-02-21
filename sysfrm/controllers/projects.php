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
$ui->assign('_sysfrm_menu', 'cases');
$ui->assign('_title', 'Accounts- '. $config['CompanyName']);
$ui->assign('_st', 'Products &amp; Services');
$action = $routes['1'];
$user = User::_info();
$ui->assign('user', $user);
switch ($action) {

    case 'modal-list':

        $d = ORM::for_table('sys_items')->find_many();

        echo '
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3>Modal header</h3>
</div>
<div class="modal-body">

<table class="table table-striped" id="items_table">
      <thead>
        <tr>
          <th width="10%">#</th>
          <th width="20%">Product Code</th>
          <th width="55%">Item Name</th>

          <th width="15%">Price</th>
        </tr>
      </thead>
      <tbody>
       ';

        foreach($d as $ds){
            echo ' <tr>
          <td><input type="checkbox" class="si"></td>
          <td>'.$ds['item_number'].'</td>
          <td>'.$ds['name'].'</td>

          <td class="price">'.$ds['sales_price'].'</td>
        </tr>';
        }

        echo '

      </tbody>
    </table>

</div>
<div class="modal-footer">

	<button type="button" data-dismiss="modal" class="btn">Close</button>
	<button class="btn btn-primary update">Select</button>
</div>';

        break;


    case 'add':

        $d = ORM::for_table('sys_accounts')->find_many();
        $ui->assign('d', $d);
        $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/summernote/summernote.css"/>
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/summernote/summernote-bs3.css"/>
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/dp/dist/datepicker.min.css"/>
');
        $ui->assign('xfooter', '
        <script type="text/javascript" src="' . $_theme . '/lib/select2/select2.min.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/dp/dist/datepicker.min.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/summernote/summernote.min.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/add-project.js"></script>

');

        $ui->assign('xjq', '
 $("#account").select2();

 ');

        $ui->display('project-add.tpl');



        break;


    case 's-new':


        $ui->assign('type','Service');
        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/add-ps.js"></script>

');

        $max = ORM::for_table('sys_items')->max('id');
        $nxt = $max+1;
        $ui->assign('nxt',$nxt);
        $ui->display('add-ps.tpl');



        break;


    case 'add-post':
        $name = _post('name');
        $sales_price = _post('sales_price');
        $item_number = _post('item_number');
        $description = _post('description');
        $type = _post('type');

        $msg = '';

        if($name == ''){
            $msg .= 'Item Name is required <br>';
        }
        if(!is_numeric($sales_price)){
            $sales_price = '0.00';
        }


        if($msg == ''){


            $d = ORM::for_table('sys_items')->create();
            $d->name = $name;
            $d->sales_price = $sales_price;
            $d->item_number = $item_number;
            $d->description = $description;
            $d->type = $type;

            $d->save();
            echo $d->id();
        }
        else{
            echo $msg;
        }
        break;


    case 'view':
        $id  = $routes['2'];
        $d = ORM::for_table('sys_items')->find_one($id);
        if($d){

            //find all activity for this user
            $ac = ORM::for_table('sys_activity')->where('cid',$id)->limit(20)->order_by_desc('id')->find_many();
            $ui->assign('ac',$ac);
            $ui->assign('countries',Countries::all($d['country']));

            $ui->assign('xheader', '
<link rel="stylesheet" type="text/css" href="' . $_theme . '/lib/select2/select2.css"/>

');
            $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/select2/select2.min.js"></script>
<script type="text/javascript" src="' . $_theme . '/lib/profile.js"></script>

');

            $ui->assign('xjq', '
 $("#country").select2();

 ');
            $ui->assign('d',$d);
            $ui->display('ps-view.tpl');

        }
        else{
            //   r2(U . 'customers/list', 'e', $_L['Account_Not_Found']);

        }

        break;




    case 'list':
        $paginator = Paginator::bootstrap('sys_items','type','Product');
        $d = ORM::for_table('sys_items')->where('type','Product')->offset($paginator['startpoint'])->limit($paginator['limit'])->order_by_desc('id')->find_many();
        $ui->assign('d',$d);
        $ui->assign('type','Product');
        $ui->assign('paginator',$paginator);
        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/ps-list.js"></script>
');
        $ui->display('projects-list.tpl');
        break;

    case 's-list':

        $paginator = Paginator::bootstrap('sys_items','type','Service');
        $d = ORM::for_table('sys_items')->where('type','Service')->offset($paginator['startpoint'])->limit($paginator['limit'])->order_by_desc('id')->find_many();
        $ui->assign('d',$d);
        $ui->assign('type','Service');
        $ui->assign('paginator',$paginator);
        $ui->assign('xfooter', '
<script type="text/javascript" src="' . $_theme . '/lib/ps-list.js"></script>
');
        $ui->display('ps-list.tpl');
        break;


    case 'edit-post':
        $msg = '';
        $id = _post('id');
        $price = _post('price');
        $name = _post('name');
        $item_number = _post('item_number');
        $description = _post('description');
        if($name == ''){
            $msg .= 'Name is Required <br>';
        }
        if(!is_numeric($price)){
            $msg .= 'Invalid Sales Price <br>';
        }


        if($msg == ''){
            $d = ORM::for_table('sys_items')->find_one($id);
            if($d){
                $d->name = $name;
                $d->item_number = $item_number;
                $d->sales_price = $price;
                $d->description = $description;
                $d->save();
                echo $d->id();
            }
            else{
                echo 'Not Found';
            }


        }
        else{
            echo $msg;
        }


        break;
    case 'delete':
        $id = $routes['2'];
        if($_app_stage == 'Demo'){
            r2(U . 'accounts/list', 'e', 'Sorry! Deleting Account is disabled in the demo mode.');
        }
        $d = ORM::for_table('sys_accounts')->find_one($id);
        if($d){
            $d->delete();
            r2(U . 'accounts/list', 's', $_L['account_delete_successful']);
        }

        break;

    case 'edit-form':

        $id = $routes['2'];
        $d = ORM::for_table('sys_items')->find_one($id);
        if($d){
            echo '
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3>Edit</h3>
</div>
<div class="modal-body">

<form class="form-horizontal" role="form" id="edit_form" method="post">
  <div class="form-group">
    <label for="name" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" value="'.$d['name'].'" name="name" id="name">
    </div>
  </div>
  <div class="form-group">
    <label for="rate" class="col-sm-2 control-label">Item Number</label>
    <div class="col-sm-2">
      <input type="text" class="form-control" name="item_number" value="'.$d['item_number'].'" id="item_number">
      <input type="hidden" name="id" value="'.$d['id'].'">
    </div>
  </div>
  <div class="form-group">
    <label for="rate" class="col-sm-2 control-label">Price</label>
    <div class="col-sm-2">
      <input type="text" class="form-control" name="price" value="'.$d['sales_price'].'" id="price">
      <input type="hidden" name="id" value="'.$d['id'].'">
    </div>
  </div>
    <div class="form-group">
    <label for="name" class="col-sm-2 control-label">Description</label>
    <div class="col-sm-10">
      <textarea id="description" name="description" class="form-control" rows="3">'.$d['description'].'</textarea>
    </div>
  </div>
</form>

</div>
<div class="modal-footer">

	<button type="button" data-dismiss="modal" class="btn">Close</button>
	<button id="update" class="btn btn-primary">Update</button>
</div>';
        }
        else{
            echo 'not found';
        }



        break;



    case 'post':

        break;

    default:
        echo 'action not defined';
}