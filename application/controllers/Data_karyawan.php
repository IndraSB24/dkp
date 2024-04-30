<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_karyawan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_karyawan');
    }
    
    public function model(){
        $model = "Model_karyawan";
        return $model;
    }
    
    public function page_home(){
        $home = "data_karyawan/data_karyawan";
        return $home;
    }
    
    public function data_karyawan()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Data Karyawan';
        $data['karyawan']   = $this->$model->get_all();
        $data['list_outlet'] = $this->Model_gudang->get_all_no_array();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('karyawan/v_data_karyawan', $data);
        $this->load->view('template/footer');
    }

    public function tambah()
    {   
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Tambah Data Karyawan';
        
        $this->Model_all->reset_increment('karyawan');
        $this->$model->tambah($user_id);
        $this->session->set_flashdata('pesan', 'Tambah Data Karyawan');
        redirect($this->page_home());
        
    }

    // Hapus 
    public function hapus($id)
    {
        $model = $this->model();
        $this->$model->hapus($id);
        $this->session->set_flashdata('pesan', 'Hapus Data Karyawan');
        redirect($this->page_home());
    }
    
    public function update()
    {
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        
        $this->$model->update($user_id);
        $this->session->set_flashdata('pesan', 'Update Data Karyawan');
        redirect($this->page_home());
    }
}
