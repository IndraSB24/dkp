<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_kategori_jurnal extends CI_Model
{
    function tabel(){
        $tabel = "kategori_jurnal";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        $this->db->select('
            kj.*,
            u.nama as dibuat_oleh
        ')
        ->from('kategori_jurnal kj')
        ->join('user u', 'u.id_username=kj.created_by');
        return $this->db->get()->result_array();
    }
    
    public function get_all_no_array()
    {
        return $this->db->get($this->tabel())->result();
    }
    
    //ambil berdasar id
    public function get_by_id($id){
        $this->db->select('
            kj.*
        ')
        ->from('kategori_jurnal kj')
        ->where('kj.id', $id);
        return $this->db->get()->result_array();
    } 

    // Tambah
    public function tambah()
    {
        $kategori_jurnal= $this->input->post('nama');
        $deskripsi      = $this->input->post('deskripsi');
        $status         = $this->input->post('status');
        
        $data = [
            'kategori_jurnal'   => $kategori_jurnal,
            'deskripsi'         => $deskripsi,
            'created_by'        => activeId(),
            'status'            => $status,
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
        $id_edit    = $this->input->post('id_edit');
        $nama       = $this->input->post('nama_edit');
        $deskripsi  = $this->input->post('deskripsi_edit');
        
        $data = [
            'kategori_jurnal'   => $nama,
            'deskripsi'         => $deskripsi
        ];
        
        $this->db->where('id', $id_edit);
        $this->db->update($this->tabel(), $data);
    }
    
}
