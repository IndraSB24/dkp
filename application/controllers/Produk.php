<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_kelompok_produk');
        $this->load->model('Model_Produk');
    }
    
    public function model(){
        $model = "Model_Produk";
        return $model;
    }
    
    public function page_home(){
        $home = "produk/data_produk";
        return $home;
    }
    
    public function data_produk()
    {
        $model                  = $this->model();
        $data['user']           = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id                = $data['user']['id_username'];
        $data['nama']           = $data['user']['namaUsaha'];
        $data['title']          = 'Data Produk';
        $data['produk']         = $this->$model->get_with_kel_produk();
        $data['kelompok_produk']= $this->Model_kelompok_produk->get_all();
        $data['satuan_dasar']   = $this->Model_satuan_ukuran->get_all();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('produk/index', $data);
        $this->load->view('template/footer');
    }
    
    public function tambah()
    {   
        $model              = $this->model();
        
        $this->Model_all->reset_increment('produk');
        $this->$model->tambah($user_id);
        $this->Model_kelompok_produk->tambahTerpakai();
        $this->session->set_flashdata('pesan', 'Tambah Data Produk');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id, $id_kel_produk)
    {
        $model = $this->model();
        $this->$model->hapus($id);
        
        $detail_kel_produk  = $this->Model_kelompok_produk->getById($id_kel_produk);
        $terpakai           = $detail_kel_produk[0]->terpakai;
        $terpakai           = $terpakai - 1;
        $this->Model_kelompok_produk->kurangiTerpakai($id_kel_produk, $terpakai);
        
        $this->session->set_flashdata('pesan', 'Hapus Data Produk');
        redirect($this->page_home());
    }
    
    public function update()
    {
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        
        $this->$model->update($user_id);
        
        $id_kel_awal    = $this->input->post('kelompok_edit_awal');
        $id_kel_baru    = $this->input->post('kelompok_edit');
        if($id_kel_baru != $id_kel_awal){
            $detail_kel_produk  = $this->Model_kelompok_produk->getById($id_kel_awal);
            $terpakai           = $detail_kel_produk[0]->terpakai;
            $terpakai           = $terpakai - 1;
            $this->Model_kelompok_produk->kurangiTerpakai($id_kel_awal, $terpakai);
            
            $detail_kel_baru    = $this->Model_kelompok_produk->getById($id_kel_baru);
            $terpakaiBaru       = $detail_kel_baru[0]->terpakai;
            $terpakaiBaru       = $terpakaiBaru + 1;
            $this->Model_kelompok_produk->tambahTerpakai('edit', $terpakaiBaru);
        }
        
        $this->session->set_flashdata('pesan', 'Update Data Produk');
        redirect($this->page_home());
    }
    
    // get data
    public function get_satuan()
    {
        $id = $this->input->post('id_produk');

        // Validate input data (you can add more validation if needed)
        if (empty($id)) {
            echo json_encode(['error' => 'Invalid input data']);
            return;
        }
    
        // Fetch data from the model
        $data_produk = $this->Model_produk->get_where('id', $id);
    
        // Check if data is available
        if (!empty($data_produk)) {
            echo json_encode(['satuan' => $data_produk[0]->satuan_dasar]);
        } else {
            echo json_encode(['satuan' => 'Not Set']);
        }
    }

}
