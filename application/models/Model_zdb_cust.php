<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_zdb_cust extends CI_Model
{
    public function tabel(){
        $tabel = "db_cust";
        return $tabel;
    }
    
    #Ambil data
    public function get_all()
    {
        return $this->db->get('db_cust')->result();
    }
    
}
