<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_departmen extends CI_Model
{
    public function tabel(){
        $tabel = "departmen";
        return $tabel;
    }
    
    // Ambil Data 
    public function get_all()
    {
        return $this->db->get($this->tabel())->result_array();
    }

    // Tambah
    public function tambah()
    {
        $nama   = $this->input->post('nama');
        $alamat = $this->input->post('alamat');
        
        $data = [
            'nama'      => $nama,
            'alamat'    => $alamat,
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
        $alamat = $this->input->post('alamat_edit');
        
        $data = [
            'nama'      => $nama,
            'alamat'    => $alamat,
        ];
        
        $this->db->where('id', $id_edit);
        $this->db->update($this->tabel(), $data);
    }
    
}
