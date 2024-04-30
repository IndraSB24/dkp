<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_suplier extends CI_Model
{
    public function tabel(){
        $tabel = "suplier";
        return $tabel;
    }
    
    // Ambil Data 
    public function get_all()
    {
        return $this->db->get($this->tabel())->result_array();
    }
    
    // Ambil Data Detail
    public function get_detail($jenis="", $id_supplier="")
    {
        $this->db->select('
            *
        ')
        ->from('suplier_detail sd')
        ->where('sd.id_supplier', $id_supplier)
        ->where('sd.jenis', $jenis);
        return $this->db->get()->result();
    }

    // Tambah
    public function tambah()
    {
        $nama   = $this->input->post('nama');
        $email  = $this->input->post('email');
        $hp     = $this->input->post('no_hp');
        $alamat = $this->input->post('alamat');
        
        $data = [
            'nama'      => $nama,
            'email'     => $email,
            'hp'        => $hp,
            'alamat'    => $alamat,
            'created_by'=> activeId()
        ];

        $this->db->insert($this->tabel(), $data);
    }
    
    // Tambah
    public function tambah_nope()
    {
        $data = [
            'id_supplier'   => $this->input->post('id_supplier'),
            'jenis'         => 'nope',
            'deskripsi'     => $this->input->post('nama_kontak'),
            'detail'        => $this->input->post('nope'),
            'created_by'    => activeId()
        ];

        $this->db->insert('suplier_detail', $data);
    }
    
    // Tambah
    public function tambah_norek()
    {
        $data = [
            'id_supplier'   => $this->input->post('id_supplier'),
            'jenis'         => 'norek',
            'deskripsi'     => $this->input->post('bank_an'),
            'detail'        => $this->input->post('norek'),
            'created_by'    => activeId()
        ];

        $this->db->insert('suplier_detail', $data);
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
        $email  = $this->input->post('email_edit');
        $hp     = $this->input->post('no_hp_edit');
        
        $data = [
            'nama'      => $nama,
            'email'     => $email,
            'hp'        => $hp,
            'alamat'    => $alamat,
        ];
        
        $this->db->where('id', $id_edit);
        $this->db->update($this->tabel(), $data);
    }
}
