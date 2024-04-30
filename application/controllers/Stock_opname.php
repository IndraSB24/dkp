<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_opname extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->helper('kode_helper');
        $this->load->model('Model_all');
        $this->load->model('Model_stok_opname');
        $this->load->model('Model_suplier');
        $this->load->model('Model_gudang');
        $this->load->model('Model_karyawan');
        $this->load->model('Model_stok_gudang');
        $this->load->model('Model_user');
    }
    
    public function model(){
        $model = "Model_stok_opname";
        return $model;
    }
    
    public function page_home(){
        $home = "stock_opname/stock_opname";
        return $home;
    }
    
    public function Stock_opname()
    {
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Stock Opname';
        $data['gudang']  = $this->Model_gudang->get_all();
        $data['karyawan']  = $this->Model_karyawan->get_all();
        $data['list_gudang']= $this->Model_gudang->get_all();
        $data['list_stock_opname']= $this->$model->get_all();
        $data['list_user']= $this->Model_user->get_all();
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('trans_stock_opname/faktur_stock_opname', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode, $id_faktur="")
    {
        $model              = $this->model();
        
        if($kode == "tambah"){
            $data['title']      = 'Tambah Data Stock Opname';
            
            if(active_role()=="admin_entitas" || active_role()=="entitas_vendor" || active_role()=="spv_gudang"){
                $data_gudang = $this->Model_gudang->get_by_id(activeOutlet());
                $data['list_produk']= $this->Model_gudang->get_produk_by_gudang(activeOutlet());
                $data['id_gudang']  = activeOutlet();
                $data['nama_gudang']= $data_gudang[0]->nama;
                $data['kode']       = $kode;
            }else{
                $data['list_produk']= $this->Model_gudang->get_produk_by_gudang($this->input->post('id_gudang'));
                $data['id_gudang']  = $this->input->post('id_gudang');
                $data['nama_gudang']= $this->input->post('nama_gudang');
                $data['kode']       = $kode;
            }
            
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_stock_opname/tambah', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "tambah_harian"){
            $data['title']      = 'Tambah Data Stock Opname Harian';
            
            if(active_role()=="admin_entitas" || active_role()=="entitas_vendor" || active_role()=="spv_gudang"){
                $data_gudang = $this->Model_gudang->get_by_id(activeOutlet());
                $data['list_produk']= $this->$model->get_produk_opname_harian(activeOutlet());
                $data['id_gudang']  = activeOutlet();
                $data['nama_gudang']= $data_gudang[0]->nama;
                $data['kode']       = $kode;
            }else{
                $data['list_produk']= $this->$model->get_produk_opname_harian($this->input->post('id_gudang_harian'));
                $data['id_gudang']  = $this->input->post('id_gudang_harian');
                $data['nama_gudang']= $this->input->post('nama_gudang_harian');
                $data['kode']       = $kode;
            }
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_stock_opname/tambah', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "detail"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Stock Opname';
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['list_detail']= $this->$model->get_detail($id_faktur);
            $data['kode']       = $kode;
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_stock_opname/detail_trans_stock_opname', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "detail_harian"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Stock Opname';
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['list_detail']= $this->$model->get_detail($id_faktur);
            $data['kode']       = $kode;
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_stock_opname/detail_trans_stock_opname', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "update_produk_opname_harian"){
            $data['title']      = 'Update Produk Opname Harian';
            
            if(active_role()=="admin_entitas" || active_role()=="entitas_vendor" || active_role()=="spv_gudang"){
                $data['list_produk']= $this->Model_gudang->get_produk_by_gudang(activeOutlet());
                $data['id_gudang']  = activeOutlet();
            }else{
                $data['list_produk']= $this->Model_gudang->get_produk_by_gudang($this->input->post('id_gudang_produk'));
                $data['id_gudang']  = $this->input->post('id_gudang_produk');
            }
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_stock_opname/update_produk_stok_harian', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah($kode="")
    {   
        $model              = $this->model();
        
        $this->Model_all->reset_increment('faktur_stok_opname');
        if($kode == "bulanan"){
            $this->$model->tambah_faktur();   
        }else if($kode == "harian"){
            $this->$model->tambah_faktur_harian();
        }
        
        $this->Model_all->reset_increment('faktur_stok_opname_detail');
        $lastId  = $this->$model->get_last_id();
	    $lastId  = $lastId->id;
	    $lastDetailId  = $this->$model->get_last_detail_id();
	    $lastDetailId  = $lastDetailId->id;
	    
        if($kode == "bulanan"){
            $this->$model->tambah_detail($lastId, $lastDetailId);   
        }else if($kode == "harian"){
            $this->$model->tambah_detail_harian($lastId, $lastDetailId);
        }
        
        $this->session->set_flashdata('pesan', 'Tambah Data Stock Opname');
        redirect($this->page_home());
    }

    // Hapus 
    public function hapus($id_faktur="", $jenis_opname="")
    {
        $model = $this->model();
        
        //hapus transaksi gudang
        if($jenis_opname == 'bulanan'){
            //hapus transaksi gudang
            $data_faktur= $this->$model->get_by_id($id_faktur);
            $kode_faktur= $data_faktur[0]->kode_faktur;
            $id_gudang  = $data_faktur[0]->id_gudang;
            $this->Model_gudang->delete_by_faktur($kode_faktur);
            
            $detail_faktur = $this->$model->get_detail($id_faktur);
            foreach($detail_faktur as $detail_faktur){
                $this->Model_Produk->update_stock_all($detail_faktur->id_produk);
            
                // update stock terakhir untuk awal bulan
                $selisih_stok = $detail_faktur->stok_nyata - $detail_faktur->stok_sistem;
                
                $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $detail_faktur->id_produk);
                if($selisih_stok > 0){
                    // update stok in
                    $dt_up_stok_in = [
                        'id_gudang' => $id_gudang,
                        'id_produk' => $detail_faktur->id_produk,
                        'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in - (float)$selisih_stok
                    ];
                    $this->Model_gudang->update_stok_gudang('in', $dt_up_stok_in);
                
                }else if($selisih_stok < 0){
                    // update stok out 
                    $dt_up_stok_out = [
                        'id_gudang' => $id_gudang,
                        'id_produk' => $detail_faktur->id_produk,
                        'stok_out'  => (float)$ambil_stok_gudang[0]->stok_out - (float)$selisih_stok
                    ];
                    $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_out);
                }
            }
        }
        //hapus detail
        $this->$model->hapus_detail($id_faktur);
        //hapus faktur
        $this->$model->hapus_faktur($id_faktur);
        
        $this->session->set_flashdata('pesan', 'Data Berhasil Dihapus');
        redirect($this->page_home());
    }
    
    public function update()
    {
        $model          = $this->model();
        $this->$model->update($user_id);
        $this->session->set_flashdata('pesan', 'Update Data Departemen');
        redirect($this->page_home());
    }
    
    public function update_status_opname_harian()
    {
        $model          = $this->model();
        
        $this->$model->update_status_opname_harian($this->input->post('id_gudang'));
        $this->session->set_flashdata('pesan', 'Update Data Produk Opname Harian');
        redirect($this->page_home());
    }
    
    // datatables
    public function ajax_list()
    {
        $model          = $this->model();
        $list = $this->$model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $baris) {
            $no++;
            if($baris->selisih < 0){
                $selisih = $baris->selisih * -1;
                $show_selisih = '<span class="badge badge-danger">-  Rp. '.number_format($selisih, 2, ',', '.').'</span>';
            }else{
                $show_selisih = '<span class="badge badge-success">Rp. '.number_format($baris->selisih, 2, ',', '.').'</span>';
            }
            if($baris->jenis_opname == "bulanan"){
                $show = "detail";
            }else if($baris->jenis_opname == "harian"){
                $show = "detail_harian";
            }
            $aksi = '
                    <a class="btn btn-sm btn-success text-light font-weight-bold" id="btn-edit" href="'.base_url().'stock_opname/show/'.$show.'/'.$baris->id.'">
                        <i class="fas fa-info"> Detail</i>
                    </a>
                    ..
                    <a href="'.base_url().'stock_opname/hapus/'.$baris->id.'/'.$baris->jenis_opname.'" class="btn btn-sm btn-danger text-light tombol-hapus">
                        <i class="fas fa-trash-alt"></i>
                    </a>
            ';
            
            $row = array();
            $row[] = $no;
            $row[] = $baris->jenis_opname;
            $row[] = tgl_indo($baris->tgl_faktur);
            $row[] = $baris->nama_gudang;
            $row[] = $baris->pjt;
            $row[] = $show_selisih;
            $row[] = $aksi;
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->$model->count_all(),
            "recordsFiltered" => $this->$model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
