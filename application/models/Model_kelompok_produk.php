<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_kelompok_produk extends CI_Model
{
    public function tabel(){
        $tabel = "kelompok_produk";
        return $tabel;
    }
    
    // Ambil Data Semua Satuan Ukuran
    public function get_all()
    {
        $this->db->select('
            kp.*,
            u.nama as dibuat_oleh
        ')
        ->from('kelompok_produk kp')
        ->join('user u', 'u.id_username=kp.created_by');
        return $this->db->get()->result_array();
    }
    
    // Ambil data by id
    function getById($id)
    {
        return $this->db->get_where('kelompok_produk tb', array('tb.id' => $id))->result();
    }
    
    // Ambil Data dengan filter
    public function get_by_where($id)
    {
        return $this->db->get_where('kelompok_produk', ['id' => $id])->result_array();
    }
    
    // Ambil data by id
    function count_kelompok_produk_notBB()
    {
        $this->db->select('
            count(kp.id) as jumlah_kelompok_produk
        ')
        ->from('kelompok_produk kp')
        ->where('kp.id != 1 and kp.id != 2');
        return $this->db->get()->result();
    }

    // Tambah
    public function tambah($user_id)
    {
        $kode       = $this->input->post('kode');
        $nama       = $this->input->post('nama');
        $deskripsi  = $this->input->post('deskripsi');
        
        $data = [
            'kode'          => $kode,
            'nama'          => $nama,
            'deskripsi'     => $deskripsi,
            'created_by'    => activeId()
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
        $kode       = $this->input->post('kode_edit');
        $nama       = $this->input->post('nama_edit');
        $deskripsi  = $this->input->post('deskripsi_edit');
        
        $data = [
            'kode'          => $kode,
            'nama'          => $nama,
            'deskripsi'     => $deskripsi
        ];
        
        $this->db->where('id', $id_edit);
        $this->db->update($this->tabel(), $data);
    }
    
    // Tambah terpakai
    public function tambahTerpakai($kode="", $terpakaiBaru="")
    {
        if($kode == 'edit'){
            $id         = $this->input->post('kelompok_edit');
            $terpakai   = $terpakaiBaru;
        }else{
            $id         = $this->input->post('val_kelompok');
            $terpakai   = $this->input->post('terpakai_kelompok');
        }
        
        $data = [
            'terpakai'      => $terpakai
        ];
        
        $this->db->where('id', $id);
        $this->db->update($this->tabel(), $data);
    }
    
    // Kurangi terpakai
    public function kurangiTerpakai($id, $jumlah)
    {
        $data = [
            'terpakai'      => $jumlah
        ];
        
        $this->db->where('id', $id);
        $this->db->update($this->tabel(), $data);
    }
    
}
