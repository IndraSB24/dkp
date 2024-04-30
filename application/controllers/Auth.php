<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_all');
        $this->load->model('Model_user');
        $this->load->model('Model_user_role');
    }
    
    public function index()
    {
        if ($this->session->userdata('username')) {
            //redirect('dashboard');
            if(isAdmin() || isManager() || substr(hak_akses(),0,3)=="SPV"){
                redirect('dashboard');    
            }else{
                redirect('transaksi_gudang/transaksi_gudang');
            }
        }
        $this->form_validation->set_rules(
            'username',
            'Username',
            'required|trim',
            [
                'required' => 'Username Tidak Boleh Kosong'
            ]
        );
        $this->form_validation->set_rules('password', 'Password', 'required|trim', [
            'required' => 'Password Tidak Boleh Kosong'
        ]);

        if ($this->form_validation->run() == false) {
            $this->load->view('auth/index');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $username = htmlspecialchars($this->input->post('username'));
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['username' => $username])->row_array();

        if ($user) {
            if($user['status']=='1'){
                if (password_verify($password, $user['password'])) {
                    //$detail_user = $this->db->get_where('user', ['username' => $user['username']])->row_array();
                    $detail_gudang = $this->db->get_where('gudang', ['id' => $user['id_gudang']])->row_array();
                    $data = [
                        'username'      => $user['username'],
                        'act_nama'      => $user['nama'],
                        'activeUserId'  => $user['id_username'],
                        'login_type'    => $user['login_type'],
                        'id_gudang'     => $user['id_gudang'],
                        'id_kota'     => $user['id_kota'] ?: "",
                        'entitas_type'  => $detail_gudang['kategori'],
                        'id_role'       => $user['id_role'],
                        'gudang_divisi' => 'nothing'
                    ];
                    $this->session->set_userdata($data);
                    $this->session->set_flashdata('pesan', 'Login');
                    
                    if($user['login_type']=="ADMINISTRATOR" || $user['login_type']=="MANAGER" || substr($user['login_type'],0,3)=="SPV"){
                        redirect('dashboard');
                    }else{
                        redirect('transaksi_gudang/transaksi_gudang');
                    }
                    
                } else {
                    $this->session->set_flashdata('error', 'Password Salah');
                    redirect('auth');   
                }
            }else{
                $this->session->set_flashdata('error', 'Akun Anda Belum Aktif!!<br>Hubungi Administrator Untuk Aktifasi Akun Anda');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Username Belum Terdaftar');
            redirect('auth');
        }
    }

    public function registrasi()
    {
        if ($this->session->userdata('username')) {
            redirect('dashboard');
        }
        
        $this->form_validation->set_rules(
            'nama',
            'Nama',
            'required|trim|is_unique[user.username]',
            [
                'required' => 'Username Tidak Boleh Kosong',
                'is_unique' => 'Username Telah Digunakan'
            ]
        );
        
        $this->form_validation->set_rules('namaUsaha', 'Nama Usaha', 'required|trim|is_unique[user.namaUsaha]', [
            'required' => 'Nama Usaha Tidak Boleh Kosong',
            'is_unique' => 'Nama Usaha Sudah Digunakan'
        ]);
        
        $this->form_validation->set_rules(
            'password1',
            'Password',
            'required|trim|min_length[6]|matches[password2]',
            [
                'required' => 'Password Tidak Boleh Kosong',
                'matches' => 'Konfirmasi Password Salah',
                'min_length' => 'Minimal Password 6 Karakter'
            ]
        );

        $this->form_validation->set_rules(
            'password2',
            'Password',
            'required|trim'
        );

        if ($this->form_validation->run() == false) {
            $this->load->view('auth/registrasi');
        } else {
            $this->Model_Auth->registrasi();
            $this->session->set_flashdata('pesan', 'Registrasi Akun');
            redirect('auth');
        }
    }
    
    // show
    public function show($kode="")
    {  
        switch($kode){
            case 'registrasi':
                $data['title']  = 'Data Divisi';
                $data['list_role'] = $this->Model_user_role->get_all();
                $this->load->view('auth/registrasi', $data);
            break;
        }
    }
    
    public function logout()
    {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('activeUserId');
        $this->session->unset_userdata('login_type');
        $this->session->unset_userdata('bulanJual');
        $this->session->unset_userdata('tahunJual');
        $this->session->unset_userdata('bulanBeli');
        $this->session->unset_userdata('tahunBeli');
        $this->session->set_flashdata('pesan', 'Logout');
        redirect('auth');
    }
}
