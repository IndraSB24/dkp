<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_stok_divisi extends CI_Model
{
    
    function tabel(){
        $tabel = "gudang_stok";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        return $this->db->get($this->tabel())->result_array();
    }
    
    // Ambil Data
    public function get_all_stok()
    {
        return $this->db->get($this->tabel())->result_array();
    }
    
    // Ambil Data by Id
    public function get_all_id($id)
    {
        return $this->db->get_where('produk tb', array('tb.id' => $id))->result();
    }
    
    // Ambil data by id
    function getById($id)
    {
        $this->db->select('
                            *
                        ')
            ->from('produk p')
            ->where('p.id ', $id);
        return $this->db->get()->result();
    }
    
    // Ambil data untuk formula
    function getProdukWithFormula()
    {
        $this->db->select('
                            p.*,
                            su.nama_satuan as satuan_turunan
                        ')
            ->from('produk p')
            ->join('satuan_ukuran su', 'p.id_satuan_turunan=su.id')
            ->join('produk_formula_list pfl', 'p.id=pfl.id_produk')
            //->where('p.id_kelompok_produk != 1 and p.id_kelompok_produk != 2')
            ->order_by('p.nama', 'DESC');
        return $this->db->get()->result_array();
    }
    
    // Ambil Bahan Baku
    function getBB()
    {
        $this->db->select('
            p.*,
            kp.nama as kelompok_produk,
            su.nama_satuan as satuan_dasar
       ')
       ->from('produk p')
       ->join('kelompok_produk kp', 'p.id_kelompok_produk=kp.id')
       ->join('satuan_ukuran su', 'p.id_satuan_dasar=su.id')
        ->where('p.id_kelompok_produk = 1 or p.id_kelompok_produk = 2')
        ->order_by('p.nama', 'DESC');
        return $this->db->get()->result_array();
    }
    
    public function get_with_kel_produk(){
       $this->db->select('
            p.*,
            kp.nama as kelompok_produk,
            su.nama_satuan as satuan_dasar
       ')
       ->from('produk p')
       ->join('kelompok_produk kp', 'p.id_kelompok_produk=kp.id')
       ->join('satuan_ukuran su', 'p.id_satuan_dasar=su.id');
       return $this->db->get()->result_array();
    }

    // Tambah
    public function tambah($user_id)
    {
        $id_kelompok_produk = $this->input->post('kelompok');
        $kode       = $this->input->post('kode');
        $nama       = $this->input->post('nama');
        $id_sat_das = $this->input->post('satuan_dasar');
        $konversi   = $this->input->post('konversi');
        $id_sat_tur = $this->input->post('satuan_turunan');
        
        $data = [
            'id_kelompok_produk'=> $id_kelompok_produk,
            'kode'              => $kode,
            'nama'              => $nama,
            'id_satuan_dasar'   => $id_sat_das,
            'konversi'          => $konversi,
            'id_satuan_turunan' => $id_sat_tur,
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
        $id_edit    = $this->input->post('id_edit');
        $id_kelompok_produk = $this->input->post('kelompok_edit');
        $kode       = $this->input->post('kode_edit');
        $nama       = $this->input->post('nama_edit');
        $id_sat_das = $this->input->post('satuan_dasar_edit');
        $konversi   = $this->input->post('konversi_edit');
        $id_sat_tur = $this->input->post('satuan_turunan_edit');
        
        $data = [
            'id_kelompok_produk'=> $id_kelompok_produk,
            'kode'              => $kode,
            'nama'              => $nama,
            'id_satuan_dasar'   => $id_sat_das,
            'konversi'          => $konversi,
            'id_satuan_turunan' => $id_sat_tur,
            'created_by'        => activeId()
        ];
        
        $this->db->where('id', $id_edit);
        $this->db->update($this->tabel(), $data);
    }
    
    // update stock
    public function update_stock($kode, $id_produk, $jumlah){
        if($kode="stock_in"){
            $data['stok_in'] = $jumlah;
        }else if($kode="stock_out"){
            $data['stok_out'] = $jumlah;
        }
        
        $this->db->where('id', $id_produk);
        $this->db->update($this->tabel(), $data);
    }
    
    // update harga satuan
    public function update_harga_satuan($id_produk, $harga){
        $data['harga_satuan'] = $harga;
        
        $this->db->where('id', $id_produk);
        $this->db->update($this->tabel(), $data);
    }
}
