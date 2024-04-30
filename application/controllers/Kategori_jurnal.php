<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_jurnal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_kategori_jurnal');
    }
    
    public function model(){
        $model = "Model_kategori_jurnal";
        return $model;
    }
    
    public function page_home(){
        $home = "kategori_jurnal/kategori_jurnal";
        return $home;
    }
    
    public function kategori_jurnal()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Kategori Jurnal';
        $data['data_kategori_jurnal']   = $this->$model->get_all();
        $data['list_outlet'] = $this->Model_gudang->get_all_no_array();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('kategori_jurnal/index', $data);
        $this->load->view('template/footer');
    }

    public function tambah(){   
        $model              = $this->model();
        $this->Model_all->reset_increment('kategori_jurnal');
        $this->$model->tambah();
        $this->session->set_flashdata('pesan', 'Tambah Data Kategori Jurnal');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id)
    {
        $model = $this->model();
        $this->$model->hapus($id);
        $this->session->set_flashdata('pesan', 'Hapus Data Kategori Jurnal');
        redirect($this->page_home());
    }
    
    public function update()
    {
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        
        $this->$model->update($user_id);
        $this->session->set_flashdata('pesan', 'Update Data Kategori Jurnal');
        redirect($this->page_home());
    }
}
