<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_supplier extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_suplier');
    }
    
    public function model(){
        $model = "Model_suplier";
        return $model;
    }
    
    public function page_home(){
        $home = "data_supplier/data_supplier";
        return $home;
    }
    
    public function data_supplier()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Data Supplier';
        $data['suplier']    = $this->$model->get_all();
        foreach($data['suplier'] as $row){
            $data['detail_nope'][$row['id']] = $this->$model->get_detail("nope", $row['id']);
            $data['detail_norek'][$row['id']] = $this->$model->get_detail("norek", $row['id']);
        }
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('supplier/v_data_supplier', $data);
        $this->load->view('template/footer');
    }

    public function tambah($kode="")
    {   
        $model              = $this->model();
        
        if($kode=="nope"){
            $this->Model_all->reset_increment('suplier_detail');
            $this->$model->tambah_nope($user_id);
            $this->session->set_flashdata('pesan', 'Tambah Data Kontak');   
        }else if($kode=="norek"){
            $this->Model_all->reset_increment('suplier_detail');
            $this->$model->tambah_norek();
            $this->session->set_flashdata('pesan', 'Tambah Data Rekening');
        }else{
            $this->Model_all->reset_increment('suplier');
            $this->$model->tambah();
            $this->session->set_flashdata('pesan', 'Tambah Data Supplier');
        }
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id)
    {
        $model              = $this->model();
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
        $this->session->set_flashdata('pesan', 'Update Data Supplier');
        redirect($this->page_home());
    }

}
