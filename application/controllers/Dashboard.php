<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_dashboard');
        $this->load->model('Model_transaksi_penjualan');
        $this->load->model('Model_transaksi_pembelian');
        $this->load->model('Model_transaksi_mutasi');
        $this->load->helper('wa_helper');
        $this->load->model('Model_zdb_cust');
    }
    
    public function index()
    {
        $bulan = date('n');
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id = $data['user']['id_username'];
        $data['title'] = 'Dashboard';
        $data['nama'] = $data['user']['namaUsaha'];
        
        $data['list_gudang']    = $this->Model_gudang->get_all_no_array();
        $data['data_hutang']    = $this->Model_dashboard->get_total_hutang();
        $data['data_penjualan']    = $this->Model_dashboard->get_total_penjualan();
        $data['data_pembelian']    = $this->Model_dashboard->get_total_pembelian();
        $data['data_mutasi']    = $this->Model_dashboard->get_total_mutasi();
        $data['graph_penjualan']    = $this->Model_transaksi_penjualan->get_all();
        foreach($data['list_gudang'] as $row){
            $data_produk_gudang     = $this->Model_dashboard->get_total_produk_by_gudang($row->id);
            if($data_produk_gudang){
                $data['total_produk'][$row->id] = $data_produk_gudang[0]->jumlah_produk;   
            }else{
                $data['total_produk'][$row->id] = 0;
            }
            
            $data_persediaan_gudang = $this->Model_dashboard->get_total_persediaan_by_gudang($row->id);
            if($data_persediaan_gudang){
                $data['total_persediaan'][$row->id] = 0;
                foreach($data_persediaan_gudang as $dpg){
                    $data['total_persediaan'][$row->id] += ($dpg->stok_in - $dpg->stok_out) * $dpg->harga_satuan;
                }
            }else{
                $data['total_persediaan'][$row->id] = 0;
            }
            $data_outlet_penjualan = $this->Model_dashboard->get_total_penjualan_by_gudang($row->id);
            if($data_outlet_penjualan){
                $data['total_penjualan'][$row->id] = $data_outlet_penjualan[0]->total_penjualan;
            }else{
                $data['total_penjualan'][$row->id] = 0;
            }
            $data_mutasi_piutang_outlet = $this->Model_dashboard->get_total_mutasi_by_outlet($row->id);
            if($data_mutasi_piutang_outlet){
                $data['total_mutasi'][$row->id] = $data_mutasi_piutang_outlet[0]->total_mutasi;
            }else{
                $data['total_mutasi'][$row->id] = 0;
            }
        }
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('dashboard', $data);
        $this->load->view('template/footer');
    }
    
    public function sendWaPersonal(){
        $data['penerima'] = "6281268596440";
        $data['greetings'] = "Mba";
        $data['nama_panggilan'] = "Yulina";
        waPersonalTest($data);
        waPersonalImageTest($data);
    }
    
     public function sendWaGroup(){
         $list_barang = '- teh'.
                        '<br>- garam';
         $data_wa = [
            'no_faktur' => "OK",
            'nama_dari_gudang' => "OK",
            'nama_ke_gudang' => "OK",
            'tgl_diminta' => "OK",
            'waktu_diminta' => "OK",
            'nama_karyawan' => "OK",
            'list_barang_diminta' => $list_barang,
            'keterangan' => "Dikirim Secepat Kilat Ya"
        ];
        waPermintaanMutasi($data_wa);
    }
    
    
    
}




















