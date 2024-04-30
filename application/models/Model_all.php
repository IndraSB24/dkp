<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_all extends CI_Model
{
    public function reset_increment($tabel){
        $sql = "ALTER TABLE ".$tabel." AUTO_INCREMENT = 1;";
        $this->db->query($sql);
    }
}