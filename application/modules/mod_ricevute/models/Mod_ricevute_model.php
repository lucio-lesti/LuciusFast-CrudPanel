<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseModel.php';

class Mod_ricevute_model extends BaseModel
{

    public $table = 'mod_ricevute';
    public $id = 'id';
    public $order = 'DESC';


    public function __construct(){
        parent::__construct();
    }
 
 

}