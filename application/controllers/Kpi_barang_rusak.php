<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kpi_barang_rusak extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_kpi_barang_rusak');
    }
    
    public function model(){
        $model = "Model_kpi_barang_rusak";
        return $model;
    }
    
    public function page_home(){
        $home = "kpi_barang_rusak/kpi_barang_rusak";
        return $home;
    }
    
    public function kpi_barang_rusak()
    {  
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        $data['nama']   = $data['user']['namaUsaha'];
        $data['title']  = 'Data KPI Barang Rusak';
        
        //$data['list_data'] = $this->$model->get_by_bulan(date('m'));
        $data['list_produk']= $this->Model_Produk->get_all_sorted();
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('kpi_barang_rusak/index', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode, $id_produk=""){
        $model          = $this->model();
        
        if($kode == "detail"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Harga Jual';
            
            $data['data_kpi']   = $this->$model->get_by_produk($id_produk);
            $data['list_tahun'] = $this->$model->get_tahun();
            $data['id_produk']  = $id_produk;
            $data_produk        = $this->Model_Produk->getById($id_produk);
            $data['nama_produk']= $data_produk[0]->nama;
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('kpi_barang_rusak/detail', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah()
    {   
        $model              = $this->model();
        
        $this->Model_all->reset_increment('kpi_barang_rusak');
        $this->$model->tambah();
        $this->session->set_flashdata('pesan', 'Tambah Data KPI Barang Rusak');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id)
    {
        $model = $this->model();
        $this->$model->hapus($id);
        $this->session->set_flashdata('pesan', 'Hapus Data KPI Barang Rusak');
        redirect($this->page_home());
    }
    
    // Update
    public function update()
    {
        $model          = $this->model();
        
        $this->$model->update();
        $this->session->set_flashdata('pesan', 'Update Data KPI Barang Rusak');
        redirect($this->page_home());
    }
}
