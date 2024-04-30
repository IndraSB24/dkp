<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_satuan_ukuran extends CI_Model
{
    
    public function tabel(){
        $tabel = "satuan_ukuran";
        return $tabel;
    }

    // Ambil Data Semua Satuan Ukuran
    public function get_all()
    {
        return $this->db->get($this->tabel())->result_array();
    }
    
    // ambil data dari id
    public function getById($id)
    {
        return $this->db->get_where('satuan_ukuran su', array('su.id' => $id))->result();
    }

    // Tambah
    public function tambah($user_id)
    {
        $kode       = $this->input->post('kode');
        $nama       = $this->input->post('nama_satuan');
        $deskripsi  = $this->input->post('deskripsi_satuan');
        
        $data = [
            'kode'              => $kode,
            'nama_satuan'       => $nama,
            'deskripsi_satuan'  => $deskripsi,
            'created_by'        => activeId()
        ];

        $this->db->insert($this->tabel(), $data);
    }
    
    // Update
    public function update($user_id)
    {
        $id_satuan  = $this->input->post('id_satuan_edit');
        $kode       = $this->input->post('kode_edit');
        $nama       = $this->input->post('nama_edit');
        $deskripsi  = $this->input->post('deskripsi_edit');
        
        $data = [
            'kode'              => $kode,
            'nama_satuan'       => $nama,
            'deskripsi_satuan'  => $deskripsi,
            'created_by'        => activeId()
        ];
        
        $this->db->where('id', $id_satuan);
        $this->db->update($this->tabel(), $data);
    }
    
    // Hapus
    public function hapus($id_satuan)
    {
        $this->db->where('id', $id_satuan);
        $this->db->delete($this->tabel());
    }
    
}
