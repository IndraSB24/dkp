<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_kpi_barang_rusak extends CI_Model
{
    function tabel(){
        $tabel = "kpi_barang_rusak";
        return $tabel;
    }
    
    // Ambil Data Semua
    public function get_list_all()
    {
        return $this->db->get('kpi_barang_rusak')->result();
    }
    
    //ambil by bulan
    public function get_by_bulan($bulan)
    {
        $this->db->select('
            kbr.*,
            p.nama as nama_produk
        ')
        ->from('kpi_barang_rusak kbr')
        ->join('produk p', 'p.id=kbr.id_produk')
        ->where('year(kbr.tgl_periode)', date("Y"))
        ->where('month(kbr.tgl_periode)', $bulan);
        return $this->db->get()->result();
    }
    
    //ambil by id_produk
    public function get_by_produk($id_produk)
    {
        $this->db->select('
            kbr.*
        ')
        ->from('kpi_barang_rusak kbr')
        ->where('kbr.id_produk', $id_produk);
        return $this->db->get()->result();
    }
    
    //ambil by id_produk and bulan
    public function get_by_idProduk_bulan($id_produk, $bulan)
    {
        $this->db->select('
            kbr.*,
            p.nama as nama_produk
        ')
        ->from('kpi_barang_rusak kbr')
        ->join('produk p', 'p.id=kbr.id_produk')
        ->where('kbr.id_produk', $id_produk)
        ->where('year(kbr.tgl_periode)', date("Y"))
        ->where('month(kbr.tgl_periode)', $bulan);
        return $this->db->get()->result();
    }
    
    //ambil by id_produk and bulan
    public function get_by_idProduk_bulan_tahun($id_produk, $bulan, $tahun)
    {
        $this->db->select('
            kbr.*,
            p.nama as nama_produk
        ')
        ->from('kpi_barang_rusak kbr')
        ->join('produk p', 'p.id=kbr.id_produk')
        ->where('kbr.id_produk', $id_produk)
        ->where('year(kbr.tgl_periode)', $tahun)
        ->where('month(kbr.tgl_periode)', $bulan);
        return $this->db->get()->result();
    }
    
    //ambil tahun
    public function get_tahun()
    {
        $this->db->select('
            year(kbr.tgl_periode) as tahun
        ')
        ->from('kpi_barang_rusak kbr')
        ->group_by('year(kbr.tgl_periode)');
        return $this->db->get()->result();
    }

    // Tambah
    public function tambah()
    {
        $id_produk = $this->input->post('id_produk');
        if(!$id_produk){
            $id_produk = $this->input->post('id_produk_row');
        }
        
        $data = [
            'id_produk'     => $id_produk,
            'nilai_maksimum'=> $this->input->post('nilai_maksimum'),
            'tgl_periode'   => date_db_format($this->input->post('tanggal')),
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
    public function update()
    {
        $id_edit= $this->input->post('id_edit');
        
        $data = [
            'nilai_maksimum' => $this->input->post('nilai_maksimum')
        ];
        
        $this->db->where('id', $id_edit);
        $this->db->update($this->tabel(), $data);
    }
    
}




















