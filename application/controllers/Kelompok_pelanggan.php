<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelompok_pelanggan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_kelompok_pelanggan');
    }
    
    public function model(){
        $model = "Model_kelompok_pelanggan";
        return $model;
    }
    
    public function page_home(){
        $home = "kelompok_pelanggan/kelompok_pelanggan";
        return $home;
    }
    
    public function kelompok_pelanggan()
    {
        $model                      = $this->model();
        $data['user']               = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id                    = $data['user']['id_username'];
        $data['nama']               = $data['user']['namaUsaha'];
        $data['title']              = 'Data Kelompok Pelanggan';
        $data['kelompok_pelanggan'] = $this->$model->get_all();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('kelompok_pelanggan/v_kelompok_pelanggan', $data);
        $this->load->view('template/footer');
    }

    public function tambah()
    {   
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Tambah Data Kelompok Pelanggan';
        
        $this->Model_all->reset_increment('kelompok_pelanggan');
        $this->$model->tambah($user_id);
        $this->session->set_flashdata('pesan', 'Tambah Data Kelompok Pelangganl');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id)
    {
        $model = $this->model();
        $this->$model->hapus($id);
        $this->session->set_flashdata('pesan', 'Hapus Data Kelompok Pelanggan');
        redirect($this->page_home());
    }
    
    // Update
    public function update()
    {
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        
        $this->$model->update($user_id);
        $this->session->set_flashdata('pesan', 'Update Data Kelompok Pelanggan');
        redirect($this->page_home());
    }
}
