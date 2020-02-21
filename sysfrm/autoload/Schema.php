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

Class Schema{

    public $build_query;
    public $table;
    public $method;

    public function __construct($table)
    {
        $this->table = $table;
        $this->method = 'create';
        $this->build_query = 'CREATE TABLE IF NOT EXISTS '.$table.' (
id int(11) NOT NULL AUTO_INCREMENT,
';
    }

    public function create(){
        $this->method = 'create';
    }

    public function add_column(){
        $table = $this->table;
        $this->method = 'add_column';
        $this->build_query = 'ALTER TABLE '.$table.' ';
    }

    public function drop_column(){
        $table = $this->table;
        $this->method = 'drop_column';
        $this->build_query = 'ALTER TABLE '.$table.' ';
    }

    public function select($column){
        $this->build_query .= 'DROP '.$column.', ';
        return $this;

    }

    public function add($name,$type='text',$length='',$default='')
    {
//Apply logic to create order
        $l = '';

        if($length != ''){
            $l = '('.$length.')';
        }
        if($default != ''){
            $d = ' NOT NULL DEFAULT \''.$default.'\'';
        }
        else{

        $d = '';

        }


        $method = $this->method;

        if($method == 'create'){
            $this->build_query .= $name.' ' . $type. '' . $l. $d.',
';
        }
        elseif($method == 'add_column'){
            $this->build_query .= 'ADD '.$name.' ' . $type. '' . $l. $d.', ';
        }
        else{

        }

        return $this;
    }

    public function column(){

        // ALTER TABLE sys_invoices ADD h_t VARCHAR(10) NOT NULL DEFAULT '0', ADD h_w VARCHAR(10) NOT NULL DEFAULT '0', ADD h_th VARCHAR(10) NOT NULL DEFAULT '0';
        
        $this->build_query = '';
    }

    public function save(){

        $method = $this->method;

        if($method == 'create'){
            $this->build_query .= 'PRIMARY KEY ( id )
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

            try {
                $d = ORM::execute($this->build_query);
                return $d;

            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        elseif($method == 'add_column'){


            $build_query = $this->build_query;
            $build_query = substr($build_query, 0, -2);



            try {
                $d = ORM::execute($build_query);
                return $d;

            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        else{
            return false;
        }




    }

    public function drop(){

        $method = $this->method;

        if($method == 'create'){
            try {
                $d = ORM::execute('DROP TABLE '.$this->table);
                return $d;

            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        elseif($method == 'drop_column'){

            $build_query = $this->build_query;
            $build_query = substr($build_query, 0, -2);




            try {
                $d = ORM::execute($build_query);
                return $d;

            } catch (Exception $e) {
                return $e->getMessage();
            }

        }
        else{
            return false;
        }

    }

}

