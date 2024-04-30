<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_kota extends CI_Model
{
    public function tabel(){
        $tabel = "list_kota";
        return $tabel;
    }
    
    #Ambil data
    public function get_all()
    {
        return $this->db->get($this->tabel())->result_array();
    }
}
