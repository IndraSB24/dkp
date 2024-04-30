<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_kelompok_pelanggan extends CI_Model
{
    function tabel(){
        $tabel = "kelompok_pelanggan";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        $this->db->select('
            kp.*,
            u.nama as dibuat_oleh
        ')
        ->from('kelompok_pelanggan kp')
        ->join('user u', 'u.id_username=kp.created_by');
        return $this->db->get()->result_array();
    }

    // Tambah
    public function tambah($user_id)
    {
        $nama           = $this->input->post('nama');
        $jenis_pelanggan= $this->input->post('jenis_pelanggan');
        $alamat         = $this->input->post('alamat');
        $email          = $this->input->post('email');
        $hp             = $this->input->post('no_hp');
        
        $data = [
            'nama'              => $nama,
            'email'             => $email,
            'jenis_pelanggan'   => $jenis_pelanggan,
            'hp'                => $hp,
            'alamat'            => $alamat,
            'created_by'        => activeId()
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
        $id_edit        = $this->input->post('id_edit');
        $nama           = $this->input->post('nama_edit');
        $jenis_pelanggan= $this->input->post('jenis_pelanggan_edit');
        $alamat         = $this->input->post('alamat_edit');
        $email          = $this->input->post('email_edit');
        $hp             = $this->input->post('no_hp_edit');
        
        $data = [
            'nama'              => $nama,
            'email'             => $email,
            'jenis_pelanggan'   => $jenis_pelanggan,
            'hp'                => $hp,
            'alamat'            => $alamat,
        ];
        
        $this->db->where('id', $id_edit);
        $this->db->update($this->tabel(), $data);
    }
    
}
