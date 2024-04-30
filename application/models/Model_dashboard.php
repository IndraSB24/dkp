<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_dashboard extends CI_Model
{
    //ambil hutang di bulan dan tahun sekarang
    public function get_total_hutang()
    {
        $this->db->select('
            count(fp.id) as jumlah_faktur,
            sum(fp.total_pembelian) as total_hutang,
            sum(fp.dibayar) as dibayar,
            s.id as id_supplier,
            s.nama as nama_supplier
        ')
        ->from('faktur_pembelian fp')
        ->join('suplier s', 's.id=fp.id_supplier')
        ->where('fp.status_hutang', 'TERHUTANG')
        ->where('year(fp.tanggal_faktur)', date("Y"))
        ->where('month(fp.tanggal_faktur)', date("m"));
        return $this->db->get()->result();
    }
    
    // ambil jumlah produk pada gudang
    public function get_total_produk_by_gudang($id_gudang){
        $this->db->select('
                count(gs.id_produk) as jumlah_produk
            ')
            ->from('gudang_stok gs')
            ->where('gs.id_gudang ', $id_gudang)
            ->group_by('gs.id_gudang');
        return $this->db->get()->result();
    }
    
    // ambil jumlah produk pada gudang
    public function get_total_persediaan_by_gudang($id_gudang){
        $this->db->select('
                gs.*,
                p.harga_satuan as harga_satuan
            ')
            ->from('gudang_stok gs')
            ->join('produk p', 'p.id=gs.id_produk')
            ->where('gs.id_gudang ', $id_gudang);
        return $this->db->get()->result();
    }
    
    //ambil hutang di bulan dan tahun sekarang
    public function get_total_penjualan()
    {
        $this->db->select('
            sum(fpd.subtotal) as total_penjualan
        ')
        ->from('faktur_penjualan_diskon fpd')
        ->join('faktur_penjualan fp', 'fp.id = fpd.id_faktur')
        ->where('year(fp.tgl_faktur)', date("Y"))
        ->where('month(fp.tgl_faktur)', date("m"));
        return $this->db->get()->result();
    }
    
    public function get_total_penjualan_by_gudang($id_gudang)
    {
        $this->db->select('
            sum(fpd.subtotal) as total_penjualan
        ')
        ->from('faktur_penjualan_diskon fpd')
        ->join('faktur_penjualan fp', 'fp.id = fpd.id_faktur')
        ->where('year(fp.tgl_faktur)', date("Y"))
        ->where('fp.id_gudang ', $id_gudang)
        ->where('month(fp.tgl_faktur)', date("m"));
        return $this->db->get()->result();
    }
    
    //ambil hutang di bulan dan tahun sekarang
    public function get_total_pembelian()
    {
        $this->db->select('
            sum(fp.total_pembelian) as total_pembelian
        ')
        ->from('faktur_pembelian fp')
        ->where('year(fp.tanggal_faktur)', date("Y"))
        ->where('month(fp.tanggal_faktur)', date("m"));
        return $this->db->get()->result();
    }
    
    //ambil hutang di bulan dan tahun sekarang
    public function get_total_mutasi()
    {
        $this->db->select('
            sum(fp.nilai_mutasi) as total_mutasi
        ')
        ->from('faktur_mutasi fp')
        ->where('year(fp.tgl_mutasi)', date("Y"))
        ->where('month(fp.tgl_mutasi)', date("m"));
        return $this->db->get()->result();
    }
    
    public function get_total_mutasi_by_outlet($id_gudang)
    {
        $this->db->select('
            sum(fm.nilai_mutasi) as total_mutasi
        ')
        ->from('faktur_mutasi fm')
        ->where('year(fm.tgl_mutasi)', date("Y"))
        ->where('fm.ke_gudang ', $id_gudang)
        ->where('month(fm.tgl_mutasi)', date("m"));
        return $this->db->get()->result();
    }
    
    
}
