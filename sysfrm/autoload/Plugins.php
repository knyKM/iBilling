<?php

class Plugins
{


    public function loadPlugins($plugindir)
    {
        $plugindir_path = 'sysfrm/plugins/'.$plugindir;
        if(file_exists($plugindir_path))
        {
            $files = glob($plugindir_path . '/*.{plugin.php}', GLOB_BRACE);
            if(count($files) != 0)
            {
                foreach ($files as $file) {
                    require_once($file);
                }
            } else {
              //  trigger_error("No plugins found in plugin directory '$plugindir'!", E_USER_NOTICE);
_msglog('e',"No plugins found in plugin directory '$plugindir'! <a href='".U."settings/plugin_force_remove/$plugindir/' style='color: white; text-decoration: underline;'>Click Here To</a> Remove This Plugin Entry.");
            }
        } else {
         //   trigger_error("Plugin directory '$plugindir' does not exists!", E_USER_WARNING);
            _msglog('e',"Plugin directory '$plugindir' does not exists! <a href='".U."settings/plugin_force_remove/$plugindir/' style='color: white; text-decoration: underline;'>[ Click Here ]</a> to Remove This Plugin Entry.");
        }
    }

    public function css($path,$v=''){
        if($v == ''){
            $ver = '';
        }
        else{
            $ver = '?ver='.$v;
        }
        $gen = '';
        if(is_array($path)){

            foreach($path as $p){
                $gen .= '<link rel="stylesheet" type="text/css" href="sysfrm/plugins/'.$p.'.css'.$ver.'" />
        ';
            }


        }
        else{
            $gen = '<link rel="stylesheet" type="text/css" href="sysfrm/plugins/'.$path.'.css'.$ver.'" />
        ';
        }
        return $gen;
    }

    public function js($path,$v=''){

        if($v == ''){
            $ver = '';
        }
        else{
            $ver = '?ver='.$v;
        }
        $gen = '';
        if(is_array($path)){
            foreach($path as $p){
                $gen .= '<script type="text/javascript" src="sysfrm/plugins/'.$p.'.js'.$ver.'"></script>
        ';
            }

        }
        else{
            $gen = '<script type="text/javascript" src="sysfrm/plugins/'.$path.'.js'.$ver.'"></script>
        ';
        }
        return $gen;
    }
}