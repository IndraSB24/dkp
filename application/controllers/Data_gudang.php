<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_gudang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_gudang');
        $this->load->model('Model_kota');
    }
    
    public function model(){
        $model = "Model_gudang";
        return $model;
    }
    
    public function page_home(){
        $home = "data_gudang/data_gudang";
        return $home;
    }
    
    public function data_gudang()
    {  
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        $data['nama']   = $data['user']['namaUsaha'];
        $data['title']  = 'Data Gudang';
        $data['gudang'] = $this->$model->get_all();
        $data['list_kota']  = $this->Model_kota->get_all();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('gudang/v_data_gudang', $data);
        $this->load->view('template/footer');
    }
    
    public function show()
    {  
        $model          = $this->model();
        $data['title']  = 'Data Divisi';
        $data['gudang'] = $this->$model->get_all();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('gudang/v_data_divisi', $data);
        $this->load->view('template/footer');
    }

    public function tambah()
    {   
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $this->session->userdata('activeUserId');
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Tambah Data Gudang';
        
        $this->Model_all->reset_increment('gudang');
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
    
    // Update
    public function update()
    {
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        
        $this->$model->update($user_id);
        $this->session->set_flashdata('pesan', 'Update Data Gudang');
        redirect($this->page_home());
    }
}
