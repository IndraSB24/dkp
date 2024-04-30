<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembayaran_hutang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_departmen');
    }
    
    public function model(){
        $model = "Model_departmen";
        return $model;
    }
    
    public function page_home(){
        $home = "data_departemen/data_departemen";
        return $home;
    }
    
    public function Pembayaran_hutang()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Penjualan';
        $data['departmen']  = $this->$model->get_all($user_id);
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('trans_pembayaran_hutang/faktur_penjualan', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode)
    {
        $model              = $this->model();
        
        if($kode == "tambah"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Penjualan';
            $data['departmen']  = $this->$model->get_all($user_id);
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_penjualan/tambah', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah()
    {   
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Tambah Data Suplier';
        
        $this->Model_all->reset_increment('departmen');
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
