<?php


function get_client_ip(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}



function _log($description,$type='',$userid='0'){
    $d = ORM::for_table('sys_logs')->create();
    $d->date = date('Y-m-d H:i:s');
    $d->type = $type;
    $d->description = $description;
    $d->userid = $userid;
    $d->ip = $_SERVER["REMOTE_ADDR"];
    $d->save();

}

$nav0 = '';
$nav1 = '';
$nav2 = '';
$nav3 = '';
$nav4 = '';
$nav5 = '';
$nav6 = '';
$nav7 = '';
$nav8 = '';
$nav9 = '';
$nav10 = '';
function add_menu_admin($name,$link='#',$c='',$icon='leaf',$position='5',$submenu=array()){

    if($position == '0'){
        global $nav0;
        global $routes;
        $active = '';
        if((isset($routes['1'])) AND ($routes['1']) == $c){
            $active = 'active';
        }
        if(!empty($submenu)){
            $s = '';
            foreach($submenu as $menu){
                $s .= ' <li><a href="http://localhost/ibilling/ibilling/?ng=util/activity/">Activity Log</a></li>';
            }

            $nav0 .= '<li class="'.$active.'">
                    <a href="'.$link.'"><i class="fa fa-'.$icon.'"></i> <span class="nav-label">'.$name.' </span><span class="fa arrow"></span></a>

<ul class="nav nav-second-level">
'.$s.'
</ul>
</li>';

        }
        else{
            $nav0 .= '<li class="'.$active.'"><a href="'.U.'plugins/'.'"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>';
        }

    }

    if($position == '2'){
        global $nav2;
        global $routes;
        $active = '';
        if((isset($routes['0'])) AND ($routes['0']) == $c){
            $active = 'active';
        }
        if(!empty($submenu)){
            $s = '';
            foreach($submenu as $menu){
                $s .= ' <li><a href="'.$menu[1].'">'.$menu[0].'</a></li>';
            }

            $nav2 .= '<li class="'.$active.'">
                    <a href="'.$link.'"><i class="fa fa-'.$icon.'"></i> <span class="nav-label">'.$name.' </span><span class="fa arrow"></span></a>

<ul class="nav nav-second-level">
'.$s.'
</ul>
</li>';

        }
        else{
            $nav2 .= '<li class="'.$active.'"><a href="'.$link.'"><i class="fa fa-'.$icon.'"></i> <span class="nav-label">'.$name.'</span></a></li>';
        }

    }

    if($position == '5'){
        global $nav5;
        global $routes;
        $active = '';
        if((isset($routes['0'])) AND ($routes['0']) == $c){
            $active = 'active';
        }
        if(!empty($submenu)){
            $s = '';
            foreach($submenu as $menu){
                $s .= ' <li><a href="http://localhost/ibilling/ibilling/?ng=util/activity/">Activity Log</a></li>';
            }

            $nav5 .= '<li class="'.$active.'">
                    <a href="'.$link.'"><i class="fa fa-'.$icon.'"></i> <span class="nav-label">'.$name.' </span><span class="fa arrow"></span></a>

<ul class="nav nav-second-level">
'.$s.'
</ul>
</li>';

        }
        else{
            $nav5 .= '<li class="'.$active.'"><a href="'.$link.'"><i class="fa fa-'.$icon.'"></i> <span class="nav-label">'.$name.'</span></a></li>';
        }

    }




}

$sub_menu = array();
$sub_menu['settings'] = array();
$sub_menu['crm'] = array();

function add_sub_menu_admin($parent,$name,$link){
global $sub_menu;
    $sub_menu[$parent][] = '<li><a href="'.$link.'">'.$name.'</a></li>
';

}


function add_option($option, $value){

    $d = ORM::for_table('sys_appconfig')->where('setting',$option)->find_one();
    if($d){
        return false;
    }
    else{
        //add option
        $c = ORM::for_table('sys_appconfig')->create();
        $c->setting = $option;
        $c->value = $value;
        $c->save();
        return true;
    }

}


function get_option($option){
    $d = ORM::for_table('sys_appconfig')->where('setting',$option)->find_one();
    if($d){
        return $d['value'];
    }
    else{
        return false;
    }
}

function update_option($option,$value){

    $d = ORM::for_table('sys_appconfig')->where('setting',$option)->find_one();
    if($d){
        $d->value = $value;
        $d->save();
        return true;
    }
    else{
        return false;
    }

}

function delete_option($option){
    $d = ORM::for_table('sys_appconfig')->where('setting',$option)->find_one();
    if($d){
       $d->delete();
        return true;
    }
    else{
        return false;
    }
}


function ib_die($msg=''){
    echo $msg;
    exit;
}

function ib_close(){
    exit;
}


function get_custom_field_value($fid,$rid){

    $d = ORM::for_table('crm_customfieldsvalues')->where('fieldid',$fid)->where('relid',$rid)->find_one();

    return $d['fvalue'];

}


function ib_pg_count(){
    $d = ORM::for_table('sys_pg')->where('status','Active')->count();
    return $d;
}


function ib_add_field_value($fieldid,$relid,$fvalue){
    $d = ORM::for_table('crm_customfieldsvalues')->create();
    $d->fieldid = $fieldid;
    $d->relid = $relid;
    $d->fvalue = $fvalue;
    $d->save();
    return true;
}


// Date Related

function ib_today(){
    return true;
}

function ib_after_1_month($from = '', $format = 'mysql'){

    if($from == ''){
        $from = strtotime(date('Y-m-d'));
    }

    if($format == 'mysql'){
        $format = 'Y-m-d';
    }

    return date($format,strtotime('+1 month',$from));

}

function ib_lan_get_line($line){
    global $_L;
    return str_replace($line,$_L[$line],$line);
}

function d2($msg = 'I am here!'){

    if(is_array($msg)){
        foreach ($msg as $m){
            echo $m. ' |
';
        }
    }
    else{
        echo $msg;
    }

    exit;

}

function d2c( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}

function lan(){
    global $config;
    return $config['language'];
}

function add_js($f=array(),$v=''){

    global $ui;
    global $pl_path;

    if($v == ''){
        $ver = '';
    }
    else{
        $ver = '?ver='.$v;
    }
    $gen = '';
    if(is_array($f)){
        foreach($f as $p){
            $gen .= '<script type="text/javascript" src="'.$pl_path.'js/'.$p.'.js'.$ver.'"></script>
        ';
        }

        $ui->assign('xfooter', $gen);

        return true;

    }

    return false;

}


function add_js_external($url=array()){



    $gen = '';
    if(is_array($url)){
        foreach($url as $u){
            $gen .= '<script type="text/javascript" src="'.$u.'.js"></script>
        ';
        }



        return $gen;

    }

    return false;

}

function set_tpl($path){
    global $ui;
    $ui->assign('tplheader', $path.'header');
    $ui->assign('tplfooter', $path.'footer');
}


function language_append($path){
    global $_L;
    $file = 'sysfrm/plugins/'.$path;
    include ($file);
}

function lan_register($path){

    $x = include $path;


    var_dump($x);
    exit;


}


function add_plugin_ui_header($header=''){
    global $plugin_ui_header;
    // $plugin_ui_header .= $header;
    array_push($plugin_ui_header,$header);
}




function i_close($msg = ''){
    echo $msg;
    exit;
}




