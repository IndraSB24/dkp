<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_divisi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_divisi');
        $this->load->model('Model_karyawan');
    }
    
    public function model(){
        $model = "Model_divisi";
        return $model;
    }
    
    public function page_home(){
        $home = "data_divisi/data_divisi";
        return $home;
    }
    
    public function data_divisi($id_gudang="", $nama_gudang="")
    {  
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        $data['nama']   = $data['user']['namaUsaha'];
        $data['title']  = strtoupper('Data Divisi '.urldecode($nama_gudang));
        $data['divisi'] = $this->$model->get_all($id_gudang);
        $data['karyawan'] = $this->Model_karyawan->get_all_no_array();
        $data['id_gudang'] = $id_gudang;
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('gudang/v_data_divisi', $data);
        $this->load->view('template/footer');
    }
    
    public function tambah()
    {   
        $model              = $this->model();
        
        $this->Model_all->reset_increment('gudang_divisi');
        $this->$model->tambah();
        $this->session->set_flashdata('pesan', 'Tambah Data Divisi');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id)
    {
        $model = $this->model();
        $this->$model->hapus($id);
        $this->session->set_flashdata('pesan', 'Hapus Data Divisi');
        redirect($this->page_home());
    }
    
    // Update
    public function update()
    {
        $model          = $this->model();
        
        $this->$model->update($user_id);
        $this->session->set_flashdata('pesan', 'Update Data Divisi');
        redirect($this->page_home());
    }
}
