<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_produk_formula extends CI_Model
{
    function tabel(){
        $tabel = "produk_formula_list";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        return $this->db->get($this->tabel())->result_array();
    }
    
    //ambil last id formula list
    public function get_last_id()
    {
        return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('produk_formula_list')->row();
    }
    
    // Ambil Semua Produk yang memiliki formula
    public function get_all_produk()
    {
        $this->db->select('
            pfl.*,
            pfl.id_produk as id_produk_formula,
            p.id as id_produk,
            p.nama as nama_produk,
            p.kode as kode_produk,
            kp.nama as kelompok_produk,
            su.nama_satuan as satuan
        ')
        ->from('produk_formula_list pfl')
        ->join('produk p', 'pfl.id_produk=p.id')
        ->join('kelompok_produk kp', 'p.id_kelompok_produk=kp.id')
        ->join('satuan_ukuran su', 'p.id_satuan_dasar=su.id')
        ->order_by('pfl.id', 'ASC');
        return $this->db->get()->result_array();
    }
    
    // AMbil detail data formula
    public function get_detail_formula($id_formula)
    {
        $this->db->select('
            pfd.jumlah_bahan as jumlah_bahan,
            pfl.id_produk as id_produk,
            p.nama as nama_produk,
            p.kode as kode_produk,
            su.nama_satuan as satuan
        ')
        ->from('produk_formula_detail pfd')
        ->join('produk_formula_list pfl', 'pfl.id=pfd.id_formula_list')
        ->join('produk p', 'pfd.id_bahan=p.id')
        ->join('satuan_ukuran su', 'p.id_satuan_dasar=su.id')
        ->where('pfd.id_formula_list', $id_formula);
        return $this->db->get()->result_array();
    }
    
    // AMbil detail data formula
    public function get_detail_formula_by_produk($id_produk)
    {
        $this->db->select('
            pfd.jumlah_bahan as jumlah_bahan,
            pfd.id_bahan as id_bahan,
            pfl.id_produk as id_produk,
            p.nama as nama_produk,
            p.kode as kode_produk,
            p.harga_satuan as harga_satuan,
            p.stok_in as stok_masuk,
            p.stok_out as stok_keluar,
            su.nama_satuan as satuan
        ')
        ->from('produk_formula_detail pfd')
        ->join('produk_formula_list pfl', 'pfl.id=pfd.id_formula_list')
        ->join('produk p', 'pfd.id_bahan=p.id')
        ->join('satuan_ukuran su', 'p.id_satuan_dasar=su.id')
        ->where('pfl.id_produk', $id_produk);
        return $this->db->get()->result_array();
    }
    
    // Ambil detail data formula no array
    public function get_detail_formula_by_produk_no_array($id_produk)
    {
        $this->db->select('
            pfd.jumlah_bahan as jumlah_bahan,
            pfd.id_bahan as id_bahan,
            pfl.id_produk as id_produk,
            p.nama as nama_produk,
            p.kode as kode_produk,
            p.harga_satuan as harga_satuan,
            p.stok_in as stok_masuk,
            p.stok_out as stok_keluar,
            su.nama_satuan as satuan
        ')
        ->from('produk_formula_detail pfd')
        ->join('produk_formula_list pfl', 'pfl.id=pfd.id_formula_list')
        ->join('produk p', 'pfd.id_bahan=p.id')
        ->join('satuan_ukuran su', 'p.id_satuan_dasar=su.id')
        ->where('pfl.id_produk', $id_produk);
        return $this->db->get()->result();
    }

    // Tambah
    public function tambah_formula_list($user_id)
    {
        $id_produk  = $this->input->post('produk');
        $data_formula_list = [
                'id_produk'     => $id_produk,
                'created_by'    => activeId()
            ];
        $this->db->insert($this->tabel(), $data_formula_list);
    }
    
    // tambah detail formula
    public function tambah_formula_detail($id_formula_list)
    {
        $itung      = $this->input->post('itung');
        for($i=1; $i<=$itung; $i++){
            $id_bahan   = $this->input->post('id_bahan_'.$i);
            $jumlah     = $this->input->post('jumlah_'.$i);
            
            $data = [
                'id_formula_list'   => $id_formula_list,
                'id_bahan'          => $id_bahan,
                'jumlah_bahan'      => $jumlah,
                'created_by'        => activeId()
            ];
            
            if($id_bahan != 0){
                $this->db->insert('produk_formula_detail', $data);
            }
        }
    }
    
    // Hapus
    public function hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->tabel());
        
        $this->db->where('id_formula_list', $id);
        $this->db->delete('produk_formula_detail');
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
    
    // Update harga satuan
    public function update_harga_satuan_formula($id_produk){
        $ambil_resep = $this->get_detail_formula_by_produk_no_array($id_produk);
        $harga_satuan= 0;
        
        foreach($ambil_resep as $row){
            $data_bahan = $this->Model_Produk->getById($row->id_bahan);
            $harga_satuan += $data_bahan[0]->harga_satuan * $row->jumlah_bahan;
        }
        
        $this->Model_Produk->update_harga_satuan($id_produk, $harga_satuan);
    }
}
















