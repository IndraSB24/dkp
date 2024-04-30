<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelompok_produk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_kelompok_produk');
    }
    
    public function model(){
        $model = "Model_kelompok_produk";
        return $model;
    }
    
    public function page_home(){
        $home = "kelompok_produk/kelompok_produk";
        return $home;
    }
    
    public function kelompok_produk()
    {
        $model                  = $this->model();
        $data['user']           = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id                = $data['user']['id_username'];
        $data['nama']           = $data['user']['namaUsaha'];
        $data['title']          = 'Kelompok Produk';
        $data['kelompok_produk']= $this->$model->get_all($user_id);
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('kelompok_produk/v_kelompok_produk', $data);
        $this->load->view('template/footer');
    }

    public function tambah()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Tambah Data Kelompok Produk';
        
        $this->Model_all->reset_increment('kelompok_produk');
        $this->$model->tambah($user_id);
        $this->session->set_flashdata('pesan', 'Tambah Data Kelompok Produk');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id)
    {
        $model = $this->model();
        $this->$model->hapus($id);
        $this->session->set_flashdata('pesan', 'Hapus Data Kelompok Produk');
        redirect($this->page_home());
    }
    
    // Update
    public function update()
    {
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        
        $this->$model->update($user_id);
        $this->session->set_flashdata('pesan', 'Update Data Kelompok Produk');
        redirect($this->page_home());
    }

}
