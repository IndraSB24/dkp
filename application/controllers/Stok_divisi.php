<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_divisi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_divisi');
        $this->load->model('Model_Produk');
        $this->load->model('Model_suplier');
        $this->load->model('Model_gudang');
        $this->load->model('Model_karyawan');
        $this->load->helper('kode_helper');
    }
    
    public function model(){
        $model = "Model_divisi";
        return $model;
    }
    
    public function page_home(){
        $home = "stok_divisi/stok_divisi";
        return $home;
    }
    
    public function Stok_divisi()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Stok Produk Pada Divisi';
        
        $data['list_gudang']= $this->Model_gudang->get_all_no_array();
        $data['data_stok']  = FALSE;
        $data['list_divisi']= FALSE;
        $data['nama_gudang']= 'nothing';
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('L_divisi_stok/index', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode="")
    {
        $model = $this->model();
        
        if($kode == "stok"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Stok Produk Pada Divisi';
            
            $data['data_stok']  = $this->Model_divisi->get_all_divisi_stok($this->input->post('id_gudang'));
            $data['list_divisi']= $this->Model_divisi->get_all($this->input->post('id_gudang'));
            
            $data['list_gudang']= $this->Model_gudang->get_all_no_array();
            $data['id_gudang']  = $this->input->post('id_gudang');
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('L_divisi_stok/index', $data);
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

}
