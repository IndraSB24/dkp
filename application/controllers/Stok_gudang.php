<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_gudang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_transaksi_mutasi');
        $this->load->model('Model_Produk');
        $this->load->model('Model_suplier');
        $this->load->model('Model_gudang');
        $this->load->model('Model_karyawan');
        $this->load->helper('kode_helper');
    }
    
    public function model(){
        $model = "Model_transaksi_mutasi";
        return $model;
    }
    
    public function page_home(){
        $home = "mutasi/mutasi";
        return $home;
    }
    
    public function Stok_gudang()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Produk Per Gudang';
        $data['list_gudang']= $this->Model_gudang->get_all();
        $data['list_produk']= $this->Model_Produk->get_all_sorted();
        $data['dari_tgl'] = false;
        $data['ke_tgl'] = false;
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('L_stok_gudang/index', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode)
    {
        $model              = $this->model();
        
        if($kode == "tambah"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Tambah Faktur Mutasi';
            
            $bulan = ambil_bulan();
			$tahun = ambil_tahun();
            $last_faktur_id = $this->$model->get_last_id();
            if($last_faktur_id){
                $kode_urut = sprintf("%05d", (int)$last_faktur_id->id + 1);
            }else{
                $kode_urut = sprintf("%05d", 1);
            }
            $kode = $kode_urut.'/MTS/SP/'.$bulan.'/'.$tahun;
            $data['kode_faktur']    = $kode;
            $data['list_produk']    = $this->Model_Produk->get_with_kel_produk();
            $data['list_gudang']    = $this->Model_gudang->get_all();
            $data['list_karyawan']  = $this->Model_karyawan->get_all();
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_mutasi_barang/tambah', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "filtered"){
            $model              = $this->model();
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Produk Per Gudang';
            $data['list_gudang']= $this->Model_gudang->get_all();
            $data['list_produk']= $this->Model_Produk->get_all_sorted();
            $dari_tgl = false;
            $ke_tgl = false;
            if($this->input->post('dari_tgl_faktur')){
                $dari_tgl = $this->input->post('dari_tgl_faktur');   
            }
            if($this->input->post('ke_tgl_faktur')){
                $ke_tgl = $this->input->post('ke_tgl_faktur');   
            }
            $data['dari_tgl'] = $dari_tgl;
            $data['ke_tgl'] = $ke_tgl;
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('L_stok_gudang/index', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah()
    {   
        $model              = $this->model();
        
        $this->Model_all->reset_increment('faktur_mutasi');
        $this->$model->tambah($user_id);
        $this->session->set_flashdata('pesan', 'Tambah Data Berhasil');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id)
    {
        $model = $this->model();
        $this->$model->hapus($id);
        $this->session->set_flashdata('pesan', 'Data Berhasil Dihapus');
        redirect($this->page_home());
    }
    
    public function update()
    {
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        
        $this->$model->update($user_id);
        $this->session->set_flashdata('pesan', 'Update Data Departemen');
        redirect($this->page_home());
    }
    
    // add stock in
    public function add_stock_in($id_entitas, $id_produk, $jumlah){
        
    }

    // add stock out
    public function add_stock_out($id_entitas, $id_produk, $jumlah){
        
    }
}
