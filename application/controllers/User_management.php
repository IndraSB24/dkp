<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_management extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_user');
        $this->load->model('Model_user_role');
        $this->load->model('Model_gudang');
    }
    
    public function model(){
        $model = "Model_user";
        return $model;
    }
    
    public function page_home(){
        $home = "user_management/user_management";
        return $home;
    }
    
    public function User_management()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'User Management';
        $data['data_user']  = $this->$model->get_all();
        $data['list_user_role']  = $this->Model_user_role->get_all();
        $data['list_gudang']= $this->Model_gudang->get_all();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('user_management/index', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode, $id_faktur="")
    {
        $model              = $this->model();
        
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Faktur';
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_pembelian/detail_trans_pembelian', $data);
            $this->load->view('template/footer');
        
    }

    public function tambah()
    {   
        $model = $this->model();
        $this->Model_all->reset_increment('user');
        
        $this->$model->tambah();
        $this->session->set_flashdata('pesan', 'Tambah Data User');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id)
    {
        $model = $this->model();
        
        //hapus
        $this->$model->hapus($id);
        
        $this->session->set_flashdata('pesan', 'Hapus User ');
        redirect($this->page_home());
    }
    
    public function update()
    {
        $model      = $this->model();
        $data_gudang= $this->Model_gudang->get_by_id($this->input->post('gudang_edit'));
        
        $id_kota = !empty($data_gudang) ? $data_gudang[0]->id_kota : null;
        
        $data = [
            'nama'      => $this->input->post('nama_edit'),
            'hp'        => $this->input->post('nohp_edit'),
            'status'    => $this->input->post('stat_edit'),
            "id_role"   => $this->input->post('role_edit'),
            "id_gudang" => $this->input->post('gudang_edit'),
            "id_kota"   => $id_kota
        ];
        
        $this->$model->update($this->input->post('id_edit'), $data);
        $this->session->set_flashdata('pesan', 'Update Data User');
        redirect($this->page_home());
    }
    
    public function update_password(){
        $model          = $this->model();
        $data = [
            "password"  => htmlspecialchars(password_hash($this->input->post('new_pass'), PASSWORD_DEFAULT))
        ];
            
        $this->$model->update($this->input->post('id_respass'), $data);
        $this->session->set_flashdata('pesan', 'Update Password User');
        redirect($this->page_home());
    }
    
    public function getdata() {
      $model = $this->model();    
      $id_supplier=$_POST['supplier']; // get parameter pass from ajax call
      $result = $this->$model->get_by_supplier($id_supplier);   //  pass parameter to model
      $data=array();
      $i=1;
     foreach($result as $val){
        $data[]=array(                                  // push data in array
         $i,
         $val->no_faktur,
         $val->no_invoice,
         $val->tanggal_faktur,
         $val->nama_supplier,
         $val->total_pembelian,
         $val->status_hutang,
         aksi
        );
        $i++;
     }
      
      $output=array("data"=>$data);
      echo json_encode($output); // send output as json to ajax call
   }

}
