<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_user');
    }
    
    public function model(){
        $model = "Model_user";
        return $model;
    }
    
    public function page_home(){
        $home = "user_profile/user_profile";
        return $home;
    }
    
    public function user_profile()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        
        $data['title']      = 'User Management';
        $data['data_user']  = $this->$model->get_by_id(activeId());
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('user_profile/index', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode="", $id_faktur="")
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
        $model          = $this->model();
        
        $data = [
            'nama' => $this->input->post('nama_edit'),
            'hp' => $this->input->post('nohp_edit'),
            'login_type' => $this->input->post('role_edit'),
            'status' => $this->input->post('stat_edit')
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
    
    public function upload_foto($id_user=""){
        $model      = $this->model();
        $photo      = "";
        $filename   = str_replace('.','','foto_profil_'.activeId());
        
        $this->load->library('upload');
        $config['upload_path']  = './uploads/foto_profil/';
        $config['allowed_types']= 'gif|jpg|png|jpeg|bmp';
        $config['max_size']     = '20048';
        $config['max_width']    = '1288';
        $config['max_height']   = '768';
        $config['file_name']    = $filename;
        $this->upload->initialize($config);
        
        if(!$this->upload->do_upload('photo')){
            $photo="";
        }else{
            unlink("./uploads/foto_profil/".$this->input->post('old_foto'));
            $photo = $this->upload->data();
        }
        $data = array(
             'foto_profil' => $photo['file_name'],
        );
        
        $this->$model->update(activeId(), $data);
        $this->session->set_flashdata('pesan', 'Update Foto Profil');
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
