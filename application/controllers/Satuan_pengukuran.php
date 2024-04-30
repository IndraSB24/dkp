<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Satuan_pengukuran extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_satuan_ukuran');
    }
    
    public function model(){
        $model = "Model_satuan_ukuran";
        return $model;
    }
    
    public function page_home(){
        $home = "satuan_pengukuran/satuan_pengukuran";
        return $home;
    }
    
    public function satuan_pengukuran()
    {
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        $data['nama']   = $data['user']['namaUsaha'];
        $data['title']  = 'Satuan Pengukuran';
        $data['satuan'] = $this->Model_satuan_ukuran->get_all();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('satuan_pengukuran/v_satuan_pengukuran', $data);
        $this->load->view('template/footer');
    }

    public function tambah()
    {
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Tambah Data Satuan Pengukuran';
        
        $this->Model_all->reset_increment('satuan_ukuran');
        $this->Model_satuan_ukuran->tambah($user_id);
        $this->session->set_flashdata('pesan', 'Tambah Data Berhasil');
        redirect($this->page_home());
/*
        $this->form_validation->set_rules(
            'nama',
            'Nama Produk',
            'trim|required|regex_match[/^([-a-z0-9_ ])+$/i]',
            [
                'required'      => "Nama Menu Harus Diisi",
                'regex_match'   => "Karakter Inputan Salah"
            ]

        );

        $this->form_validation->set_rules(
            'harga_beli',
            'Harga Beli',
            'trim|required|numeric|min_length[4]',
            [
                'required' => "Harga Beli Harus Diisi",
                'numeric' => "Inputan Hanya Menerima Karakter Angka",
                'min_length' => "Minimal Harga Rp. 1.000.-"
            ]
        );
        $this->form_validation->set_rules(
            'harga_jual',
            'Harga Jual',
            'trim|required|numeric|min_length[4]',
            [
                'required' => "Harga Jual Harus Diisi",
                'numeric' => "Inputan Hanya Menerima Karakter Angka",
                'min_length' => "Minimal Harga Rp. 1.000.-"
            ]
        );
        $this->form_validation->set_rules(
            'kategori',
            'kategori',
            'required',
            [
                'required' => "Kategori Harus Diisi",
            ]
        );

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('produk/tambah_produk', $data);
            $this->load->view('template/footer');
        } else {
            $this->Model_satuan_ukuran->tambah($user_id);
            //$this->session->set_flashdata('pesan', 'Tambah Produk');
            redirect('satuan_pengukuran/satuan_pengukuran');
        }
*/
    }

    public function edit($id_satuan_pengukuran)
    {
        grantAccessFor('all');
        
        $model  = $this->model();
        $id     = decrypt($id_satuan_pengukuran);
        $dt     = $this->$model->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    // Hapus Produk
    public function hapus($id_satuan)
    {
        $this->Model_satuan_ukuran->hapus($id_satuan);
        $this->session->set_flashdata('pesan', 'Hapus Data Berhasil');
        redirect($this->page_home());
    }

    public function update()
    {
        $model  = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        
        $this->$model->update($user_id);
        $this->session->set_flashdata('pesan', 'Update Satuan Pengukuran');
        redirect($this->page_home());
    }

    // Kategori Index
    public function kategori()
    {
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id = $data['user']['id_username'];
        $data['nama'] = $data['user']['namaUsaha'];
        $data['title'] = 'Kategori Produk';
        $data['kategori'] = $this->Model_Produk->get_kategori($user_id);

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('kategori/index', $data);
        $this->load->view('template/footer');
    }

    // Tambah Kategori Produk
    public function tambah_kategori()
    {
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id = $data['user']['id_username'];
        $data['nama'] = $data['user']['namaUsaha'];
        $data['title'] = 'Tambah Kategori Produk';

        $this->form_validation->set_rules(
            'nama_kat',
            'Nama Kategori',
            'trim|required|regex_match[/^([a-z ])+$/i]',
            [
                'required' => "Nama Kategori Harus Diisi",
                'regex_match' => "Inputan Hanya Menerima Karakter Huruf"
            ]

        );

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('kategori/tambah_kategori', $data);
            $this->load->view('template/footer');
        } else {
            $this->Model_Produk->tambah_kategori($user_id);
            $this->session->set_flashdata('pesan', 'Tambah Kategori Produk');
            redirect('produk/kategori');
        }
    }

    // Hapus Kategori 
    public function hapus_kategori($id_kategori)
    {
        $this->Model_Produk->hapus_kategori($id_kategori);
        $this->session->set_flashdata('pesan', 'Hapus Kategori Produk ');
        redirect('produk/kategori');
    }

    // Update Kategori
    public function update_kategori($id_kategori)
    {
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['nama'] = $data['user']['namaUsaha'];
        $data['title'] = 'Update Kategori Produk';
        $data['kategori'] = $this->db->get_where('kategori', ['id_kategori' => $id_kategori])->row_array();

        $this->form_validation->set_rules(
            'nama_kat',
            'Nama Kategori',
            'trim|required|regex_match[/^([a-z ])+$/i]',
            [
                'required' => "Nama Menu Harus Diisi",
                'is_unique' => "Nama Kategori Sudah Ada",
                'regex_match' => "Inputan Hanya Menerima Karakter Huruf"
            ]

        );

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('kategori/update_kategori', $data);
            $this->load->view('template/footer');
        } else {
            $this->Model_Produk->update_kategori($id_kategori);
            $this->session->set_flashdata('pesan', 'Update Kategori Produk');
            redirect('produk/kategori');
        }
    }
}
