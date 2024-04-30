<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Formula_produk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_produk_formula');
        $this->load->model('Model_Produk');
        $this->load->model('Model_kelompok_produk');
    }
    
    public function model(){
        $model = "Model_produk_formula";
        return $model;
    }
    
    public function page_home(){
        $home = "formula_produk/formula_produk";
        return $home;
    }
    
    public function Formula_produk()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Formula Produk';
        $data['formula_produk']= $this->$model->get_all_produk();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('formula_produk/index', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode, $id_formula="", $id_produk="")
    {
        $model              = $this->model();
        
        if($kode == "tambah"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Formula Produk';
            $data['list_produk']= $this->Model_Produk->getNotBB();
            $data['list_bahan'] = $this->Model_Produk->get_with_kel_produk();
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('formula_produk/tambah', $data);
            $this->load->view('template/footer');
        }else if($kode == "detail"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Formula Produk';
            $data['detail_formula'] = $this->$model->get_detail_formula($id_formula);
            $data['detail_produk']  = $this->Model_Produk->get_all_id($id_produk);
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('formula_produk/detail_resep', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah()
    {   
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Tambah Data Formula Produk';
        
        $this->Model_all->reset_increment('produk_formula_list');
        $this->$model->tambah_formula_list($user_id);
        
        $this->Model_all->reset_increment('produk_formula_detail');
        $lastFormulaId  = $this->$model->get_last_id();
	    $lastFormulaId  = $lastFormulaId->id;
        $this->$model->tambah_formula_detail($lastFormulaId);
        
        $ambil_kelompok_produk      = $this->Model_kelompok_produk->count_kelompok_produk_notBB();
        for($i=0; $i<$ambil_kelompok_produk[0]->jumlah_kelompok_produk; $i++){
            $ambil_produk_with_formula  = $this->Model_produk_formula->get_all_produk();
            foreach($ambil_produk_with_formula as $row){
                $this->Model_produk_formula->update_harga_satuan_formula($row['id_produk']);
            }
        }
        
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
