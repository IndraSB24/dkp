<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_gudang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_Produk');
        $this->load->model('Model_suplier');
        $this->load->model('Model_gudang');
        $this->load->model('Model_karyawan');
        $this->load->model('Model_transaksi_pembelian');
        $this->load->model('Model_transaksi_mutasi');
        
        $this->load->helper('kode_helper');
    }
    
    public function model(){
        $model = "Model_gudang";
        return $model;
    }
    
    public function page_home(){
        $home = "transaksi_gudang/transaksi_gudang";
        return $home;
    }
    
    public function transaksi_gudang()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Transaksi Gudang';
        
        $data['list_data']  = $this->$model->get_all_transaksi();
        $data['list_produk']= $this->Model_Produk->get_all_sorted();
        $data['list_gudang']= $this->Model_gudang->get_all_no_array();
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('L_transaksi_gudang/index', $data);
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
    
    public function ajax_list()
    {
        $model          = $this->model();
        $list = $this->$model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $baris) {
            $entitas_lain = "-";
            $harga = 0;
            if($baris->deskripsi_transaksi == "pembelian"){
                $data_pembelian = $this->Model_transaksi_pembelian->get_by_no_faktur($baris->kode_faktur);
                $data_pembelian ? $entitas_lain = $data_pembelian[0]->nama_supplier : $entitas_lain = "-";
                
                if($data_pembelian){
                    $detail_produk = $this->Model_transaksi_pembelian->get_detail_transaksi_produk($data_pembelian[0]->id, $baris->id_produk);
                    $harga = $detail_produk[0]->harga_beli;
                }
            }
            else if($baris->deskripsi_transaksi == "mutasi"){
                $data_mutasi = $this->Model_transaksi_mutasi->get_by_no_faktur($baris->kode_faktur);
                if($baris->jenis_transaksi == "masuk"){
                    $data_mutasi ? $entitas_lain = $data_mutasi[0]->dari_gudang : $entitas_lain="-";   
                }else if($baris->jenis_transaksi == "keluar"){
                    $data_mutasi ? $entitas_lain = $data_mutasi[0]->ke_gudang : $entitas_lain="-";   
                }
            }
            // else if($baris->deskripsi_transaksi == "mutasi_divisi"){
            //     $dari_gudang = "-";
            //     $data_pembelian = $this->Model_transaksi_pembelian->get_by_no_faktur($baris->kode_faktur);
            // }
            // else if($baris->deskripsi_transaksi == "pembelian"){
            //     $dari_gudang = "-";
            //     $data_pembelian = $this->Model_transaksi_pembelian->get_by_no_faktur($baris->kode_faktur);
            // }
            
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $baris->nama_gudang;
            $row[] = $baris->jenis_transaksi;
            $row[] = $entitas_lain;
            $row[] = $baris->kode_produk;
            $row[] = $baris->nama_produk;
            $row[] = $baris->jumlah;
            $row[] = $baris->satuan_dasar;
            $row[] = $harga;
            $row[] = $baris->deskripsi_transaksi;
            $row[] = $baris->kode_faktur;
            $row[] = tgl_indo($baris->tgl_faktur);
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->$model->count_all(),
            "recordsFiltered" => $this->$model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}