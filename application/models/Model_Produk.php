<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_Produk extends CI_Model
{
    
    function tabel(){
        $tabel = "produk";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        return $this->db->get($this->tabel())->result_array();
    }
    
    // get data by where
    public function get_where($column, $value)
    {
        $column = $this->db->escape_str($column);
        $value = $this->db->escape_str($value);
        
        $this->db->select('
            p.*,
            kp.nama as kelompok_produk,
            su.nama_satuan as satuan_dasar
        ')
        ->from('produk p')
        ->join('kelompok_produk kp', 'p.id_kelompok_produk=kp.id')
        ->join('satuan_ukuran su', 'su.id=p.id_satuan_dasar')
        ->where($column, $value);
        return $this->db->get()->result_array();
    }
    
    // get for import
    public function get_for_import($column, $value)
    {
        $column = $this->db->escape_str($column);
        $value = $this->db->escape_str($value);
    
        $this->db->where($column, $value);
        return $this->db->get('produk')->result_array();
    }
    
    // Ambil Data untuk export
    public function get_for_export()
    {
        $this->db->select('
            p.nama,
            kp.nama as kelompok_produk,
            su.nama_satuan as satuan_dasar
        ')
        ->from('produk p')
        ->join('kelompok_produk kp', 'p.id_kelompok_produk=kp.id')
        ->join('satuan_ukuran su', 'su.id=p.id_satuan_dasar') 
        ->order_by('p.nama', 'ASC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();

            // Add a header row for the 'Produk' sheet
            $header = array('Nama Produk', 'Kategori Produk', 'Satuan Produk');
            array_unshift($result, $header);

            return $result;
        }

        return array();
    }
    
    // Ambil Bahan Baku
    function get_all_sorted()
    {
        $this->db->select('
            p.*,
            kp.nama as kelompok_produk,
            su.nama_satuan as satuan_dasar
        ')
        ->from('produk p')
        ->join('kelompok_produk kp', 'p.id_kelompok_produk=kp.id')
        ->join('satuan_ukuran su', 'su.id=p.id_satuan_dasar') 
        ->order_by('p.id_kelompok_produk', 'ASC');
        return $this->db->get()->result_array();
    }
    
    // Ambil Bahan Baku
    function get_all_no_array()
    {
        $this->db->select('
            p.*,
            p.nama as nama_produk,
            kp.nama as kelompok_produk,
            su.nama_satuan as satuan_dasar
        ')
        ->from('produk p')
        ->join('kelompok_produk kp', 'p.id_kelompok_produk=kp.id')
        ->join('satuan_ukuran su', 'su.id=p.id_satuan_dasar') 
        ->order_by('p.id_kelompok_produk', 'ASC');
        return $this->db->get()->result();
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
                            su.nama_satuan as satuan_dasar,
                            pfl.id as id_formula
                        ')
            ->from('produk p')
            ->join('satuan_ukuran su', 'p.id_satuan_dasar=su.id')
            ->join('produk_formula_list pfl', 'p.id=pfl.id_produk')
            ->order_by('p.nama', 'ASC');
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
        ->order_by('p.nama', 'ASC');
        return $this->db->get()->result_array();
    }
    
    // Ambil not Bahan Baku
    function getNotBB()
    {
        $this->db->select('
            p.*,
            kp.nama as kelompok_produk,
            su.nama_satuan as satuan_dasar
        ')
        ->from('produk p')
        ->join('kelompok_produk kp', 'p.id_kelompok_produk=kp.id')
        ->join('satuan_ukuran su', 'p.id_satuan_dasar=su.id')
        ->where('p.id_kelompok_produk != 1 and p.id_kelompok_produk != 2')
        ->order_by('p.nama', 'ASC');
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
        
        $data = [
            'id_kelompok_produk'=> $id_kelompok_produk,
            'kode'              => $kode,
            'nama'              => strtoupper($nama),
            'id_satuan_dasar'   => $id_sat_das,
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
        
        $data = [
            'id_kelompok_produk'=> $id_kelompok_produk,
            'kode'              => $kode,
            'nama'              => strtoupper($nama),
            'id_satuan_dasar'   => $id_sat_das,
            'created_by'        => activeId()
        ];
        
        $this->db->where('id', $id_edit);
        $this->db->update($this->tabel(), $data);
    }
    
    // update stock
    public function update_stock($kode="", $id_produk, $jumlah){
        $data = ['stok_in' => $jumlah];
        
        $this->db->where('id', $id_produk);
        $this->db->update($this->tabel(), $data);
    }
    
    public function update_stock_out($id_produk, $jumlah){
        $data = ['stok_out' => $jumlah];
        $this->db->where('id', $id_produk);
        $this->db->update($this->tabel(), $data);
    }
    
    public function update_stock_all($id_produk){
        $ambil_stok_in  = $this->Model_gudang->get_transaksi_in_by_produk($id_produk);
        foreach($ambil_stok_in as $asi){
            $stok_in += $asi->jumlah;
        }
        $ambil_stok_out  = $this->Model_gudang->get_transaksi_out_by_produk($id_produk);
        foreach($ambil_stok_out as $aso){
            $stok_out += $aso->jumlah;
        }
        $data = [
            'stok_in'  => $stok_in,
            'stok_out' => $stok_out
        ];
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
