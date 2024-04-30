<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_karyawan extends CI_Model
{
    function tabel(){
        $tabel = "karyawan";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        return $this->db->get($this->tabel())->result_array();
    }
    
    public function get_all_no_array()
    {
        return $this->db->get($this->tabel())->result();
    }
    
    //ambil berdasar id
    public function get_by_id($id){
        $this->db->select('
            k.*
        ')
        ->from('karyawan k')
        ->where('k.id', $id);
        return $this->db->get()->result_array();
    } 

    // Tambah
    public function tambah($user_id)
    {
        $nama   = $this->input->post('nama');
        $outlet = $this->input->post('outlet');
        $email  = $this->input->post('email');
        $hp     = $this->input->post('no_hp');
        
        $data = [
            'nama'      => $nama,
            'email'     => $email,
            'hp'        => $hp,
            'outlet'    => $outlet,
            'created_by'=> activeId()
        ];

        $this->db->insert($this->tabel(), $data);
    }
    
    // Hapus
    public function hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->tabel());
    }
    
     // Update
    public function update($user_id)
    {
        $id_edit= $this->input->post('id_edit');
        $nama   = $this->input->post('nama_edit');
        $outlet = $this->input->post('outlet_edit');
        $email  = $this->input->post('email_edit');
        $hp     = $this->input->post('no_hp_edit');
        
        $data = [
            'nama'      => $nama,
            'email'     => $email,
            'hp'        => $hp,
            'outlet'    => $outlet,
        ];
        
        $this->db->where('id', $id_edit);
        $this->db->update($this->tabel(), $data);
    }
    
}
