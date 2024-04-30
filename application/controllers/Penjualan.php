<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
        $this->load->model('Model_all');
        $this->load->model('Model_transaksi_penjualan');
        $this->load->model('Model_Produk');
        $this->load->model('Model_suplier');
        $this->load->model('Model_gudang');
        $this->load->model('Model_karyawan');
        $this->load->model('Model_stok_gudang');
        $this->load->model('Model_kelompok_produk');
        $this->load->helper('kode_helper');
    }
    
    public function model(){
        $model = "Model_transaksi_penjualan";
        return $model;
    }
    
    public function page_home(){
        $home = "penjualan/penjualan";
        return $home;
    }
    
    public function Penjualan(){
        $model              = $this->model();
        $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id            = $data['user']['id_username'];
        $data['nama']       = $data['user']['namaUsaha'];
        $data['title']      = 'Penjualan';
        
        $last_faktur_id = $this->$model->get_last_id();
        if($last_faktur_id){
            $kode_urut = sprintf("%05d", (int)$last_faktur_id->urut + 1);
        }else{
            $kode_urut = sprintf("%05d", 1);
        }
        $kode = 'PJL-'.ambil_tahun().'-'.$kode_urut;
        $data['kode_faktur']    = $kode;
        $data['urut']           = $kode_urut;
        $data['list_gudang']    = $this->Model_gudang->get_list();
        $data['list_karyawan']  = $this->Model_karyawan->get_all();
        $data['list_data']      = $this->$model->get_all();
        
        if(substr(hak_akses(),0,3)=="ADM" && !isAdmin()){
            $data['total_penjualan'] = 0;
            $lokasi = substr(hak_akses(),6);
            $data['total_faktur']   = $this->$model->get_total_faktur_where($lokasi);
            if($data['total_faktur']){
                $list_id_faktur = $this->$model->get_id_faktur_list($lokasi);
                foreach($list_id_faktur as $row){
                    $penjualan = $this->$model->get_total_penjualan($row->id_faktur);
                    if($penjualan){
                        $data['total_penjualan'] = $data['total_penjualan'] + (float)$penjualan[0]->total_penjualan;
                    }
                }
            }else{
                $data['total_penjualan'] = 0;
            }
        }else{
            $data['total_faktur']   = $this->$model->get_total_faktur();
            $penjualan = $this->$model->get_total_penjualan_all();
            $data['total_penjualan'] = $penjualan[0]->total_penjualan;
        }
            
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('trans_penjualan/faktur_penjualan', $data);
        $this->load->view('template/footer');
    }
    
    public function show($kode="", $id_faktur=""){
        $model              = $this->model();
        
        if($kode == "tambah_data"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Tambah Data';
            
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_penjualan/tambah', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "tambah_data_penjualan"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Tambah Data Penjualan';
            
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['data_penjualan']= $this->$model->get_data_penjualan($id_faktur);
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_penjualan/tambah_data_penjualan', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "tambah_data_diskon"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Tambah Data Diskon';
            
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['data_diskon']= $this->$model->get_data_diskon($id_faktur);
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_penjualan/tambah_data_diskon', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "detail"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Faktur Penjualan';
            
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['data_manual']= $this->$model->get_data_manual($id_faktur);
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_penjualan/detail_trans_penjualan', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "detail_data_penjualan"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Data Penjualan';
            
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['data_penjualan']= $this->$model->get_data_penjualan($id_faktur);
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_penjualan/detail_data_penjualan', $data);
            $this->load->view('template/footer');
            
        }else if($kode == "detail_data_diskon"){
            $data['user']       = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $user_id            = $data['user']['id_username'];
            $data['nama']       = $data['user']['namaUsaha'];
            $data['title']      = 'Detail Data Diskon';
            
            $data['data_faktur']= $this->$model->get_by_id($id_faktur);
            $data['data_diskon']= $this->$model->get_data_diskon($id_faktur);
            
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('trans_penjualan/detail_data_diskon', $data);
            $this->load->view('template/footer');
        }
    }

    public function tambah($kode=""){   
        $model              = $this->model();
        
        if($kode == 'faktur'){
            $this->Model_all->reset_increment('faktur_penjualan');
            $this->$model->tambah_faktur();
            $this->session->set_flashdata('pesan', 'Tambah Faktur');
            redirect($this->page_home());
            
        }else if($kode == 'manual'){
            $this->Model_all->reset_increment('faktur_penjualan_manual');
            $this->$model->tambah_data_manual();
            $this->session->set_flashdata('pesan', 'Tambah Data Manual');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    // Hapus 
    public function hapus($kode="", $id=""){
        $model = $this->model();
        
        if($kode=="faktur"){
            $detail_penjualan = $this->Model_transaksi_penjualan->get_data_penjualan($id);
            if($detail_penjualan){
                //hapus transaksi gudang
                $data_faktur = $this->$model->get_by_id($id);
                $kode_faktur = $data_faktur[0]->kode_faktur;
                $this->Model_gudang->delete_by_faktur($kode_faktur);
                
    	        foreach($detail_penjualan as $row){
                    //update stok produk
                    $this->Model_Produk->update_stock_all($row->id_produk);
    			    
    			    if( strtoupper($row->kategori) == 'KLAPPY' || strtoupper($row->kategori) == 'MINUMAN' || strtoupper($row->kategori) == 'MAKANAN' ||
			            strtoupper($row->kategori) == 'PLASTIK' || strtoupper($row->kategori) == 'KOTAK' || strtoupper($row->nama_produk) == 'APPLE PIE'
    			    ){
                        // update stok out gudang
                        $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($row->id_gudang, $row->id_produk);
                        if($ambil_stok_gudang){
                            // update stok out
                            $dt_up_stok_out = [
                                'id_gudang' => $row->id_gudang,
                                'id_produk' => $row->id_produk,
                                'stok_out'   => (float)$ambil_stok_gudang[0]->stok_out - (float)$row->jumlah
                            ];
                            $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_out);
                        }
                        
                        // special condition for APPLE PIE
                        if(strtoupper($row->nama_produk) == 'APPLE PIE'){
                            // add or update stok in ke gudang
                            $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($row->id_gudang, '152');
                            if($ambil_stok_gudang){
                                // update stok in
                                $dt_up_stok = [
                                    'id_gudang' => $row->id_gudang,
                                    'id_produk' => '152',
                                    'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in - (float)$row->jumlah
                                ];
                                $this->Model_gudang->update_stok_gudang('in', $dt_up_stok);
                            }
                        }
                            
    			    }else{
                        // update stok out gudang
                        $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($row->id_gudang, $row->id_produk);
                        if($ambil_stok_gudang){
                            // update stok dan stok in out
                            $dt_up_stok_in = [
                                'id_gudang' => $row->id_gudang,
                                'id_produk' => $row->id_produk,
                                'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in - (float)$row->jumlah
                            ];
                            $this->Model_gudang->update_stok_gudang('in', $dt_up_stok_in);
                            $dt_up_stok_out = [
                                'id_gudang' => $row->id_gudang,
                                'id_produk' => $row->id_produk,
                                'stok_out'   => (float)$ambil_stok_gudang[0]->stok_out - (float)$row->jumlah
                            ];
                            $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_out);
                        }    
                        // ambil bahan yang digunakan
                        $ambil_bahan = $this->Model_produk_formula->get_detail_formula_by_produk_no_array($row->id_produk);
                        if($ambil_bahan){
                            foreach($ambil_bahan as $ab){
                                $id_bahan   = $ab->id_bahan;
                                $total_bahan= (float)$row->jumlah * (float)$ab->jumlah_bahan;
                                
                                // update stok out gudang
                                $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($row->id_gudang, $id_bahan);
                                if($ambil_stok_gudang){
                                    // update stok out
                                    $dt_up_stok_bahan = [
                                        'id_gudang' => $row->id_gudang,
                                        'id_produk' => $id_bahan,
                                        'stok_out'   => (float)$ambil_stok_gudang[0]->stok_out - (float)$total_bahan
                                    ];
                                    $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_bahan);
                                }
                                //update stok bahan
                                $this->Model_Produk->update_stock_all($id_bahan);
                            }
                        }
    			    }
    	        }
            }
            $this->$model->hapus($id);
            $this->session->set_flashdata('pesan', 'Hapus Data Faktur Penjualan');
            redirect($this->page_home());
            
        }else if($kode=="data_penjualan"){
            //hapus transaksi gudang
            $data_faktur = $this->$model->get_by_id($id);
            $kode_faktur = $data_faktur[0]->kode_faktur;
            $this->Model_gudang->delete_by_faktur($kode_faktur);
            
            $detail_penjualan = $this->Model_transaksi_penjualan->get_data_penjualan($id);
	        foreach($detail_penjualan as $row){
                //update stok produk
                $this->Model_Produk->update_stock_all($row->id_produk);
			    
			    if( strtoupper($row->kategori) == 'KLAPPY' || strtoupper($row->kategori) == 'MINUMAN' || strtoupper($row->kategori) == 'MAKANAN' ||
			        strtoupper($row->kategori) == 'PLASTIK' || strtoupper($row->kategori) == 'KOTAK' || strtoupper($row->nama_produk) == 'APPLE PIE'
			    ){
                    // update stok out gudang
                    $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($row->id_gudang, $row->id_produk);
                    if($ambil_stok_gudang){
                        // update stok out
                        $dt_up_stok_out = [
                            'id_gudang' => $row->id_gudang,
                            'id_produk' => $row->id_produk,
                            'stok_out'   => (float)$ambil_stok_gudang[0]->stok_out - (float)$row->jumlah
                        ];
                        $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_out);
                    }
                    
                    // special condition for APPLE PIE
                    if(strtoupper($row->nama_produk) == 'APPLE PIE'){
                        // add or update stok in ke gudang
                        $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($row->id_gudang, '152');
                        if($ambil_stok_gudang){
                            // update stok in
                            $dt_up_stok = [
                                'id_gudang' => $row->id_gudang,
                                'id_produk' => '152',
                                'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in - (float)$row->jumlah
                            ];
                            $this->Model_gudang->update_stok_gudang('in', $dt_up_stok);
                        }
                    }
                        
			    }else{
                    // update stok out gudang
                    $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($row->id_gudang, $row->id_produk);
                    if($ambil_stok_gudang){
                        // update stok dan stok in out
                        $dt_up_stok_in = [
                            'id_gudang' => $row->id_gudang,
                            'id_produk' => $row->id_produk,
                            'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in - (float)$row->jumlah
                        ];
                        $this->Model_gudang->update_stok_gudang('in', $dt_up_stok_in);
                        $dt_up_stok_out = [
                            'id_gudang' => $row->id_gudang,
                            'id_produk' => $row->id_produk,
                            'stok_out'   => (float)$ambil_stok_gudang[0]->stok_out - (float)$row->jumlah
                        ];
                        $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_out);
                    }    
                    // ambil bahan yang digunakan
                    $ambil_bahan = $this->Model_produk_formula->get_detail_formula_by_produk_no_array($row->id_produk);
                    if($ambil_bahan){
                        foreach($ambil_bahan as $ab){
                            $id_bahan   = $ab->id_bahan;
                            $total_bahan= (float)$row->jumlah * (float)$ab->jumlah_bahan;
                            
                            // update stok out gudang
                            $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($row->id_gudang, $id_bahan);
                            if($ambil_stok_gudang){
                                // update stok out
                                $dt_up_stok_bahan = [
                                    'id_gudang' => $row->id_gudang,
                                    'id_produk' => $id_bahan,
                                    'stok_out'   => (float)$ambil_stok_gudang[0]->stok_out - (float)$total_bahan
                                ];
                                $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_bahan);
                            }
                            //update stok bahan
                            $this->Model_Produk->update_stock_all($id_bahan);
                        }
                    }
			    }
	        }
            $this->$model->hapus_data_penjualan($id);
            $this->session->set_flashdata('pesan', 'Hapus Data Penjualan');
            redirect('penjualan/show/tambah_data_penjualan/'.$id);
            
        }else if($kode=="data_diskon"){
            $this->$model->hapus_data_diskon($id);
            $this->session->set_flashdata('pesan', 'Hapus Data Diskon');
            redirect('penjualan/show/tambah_data_diskon/'.$id);
            
        }else if($kode=="data_manual"){
            $this->$model->hapus_data_manual($id);
            $this->session->set_flashdata('pesan', 'Hapus Data Manual');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    
    public function update(){
        $model          = $this->model();
        $data['user']   = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $user_id        = $data['user']['id_username'];
        
        $this->$model->update($user_id);
        $this->session->set_flashdata('pesan', 'Update Data Departemen');
        redirect($this->page_home());
    }
    
    // datatables
    public function ajax_list(){
        $model          = $this->model();
        $list = $this->$model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $baris) {
            $no++;
            $kode_faktur	= '<a href="'.base_url().'penjualan/show/detail/'.$baris->id.'">'.$baris->kode_faktur.'</a>';
            $this->load->model('Model_transaksi_penjualan');
                $data_penjualan = $this->Model_transaksi_penjualan->get_total_penjualan($baris->id);
                if($data_penjualan){
                    $total_penjualan = "Rp. ". number_format($data_penjualan[0]->total_penjualan, 2, ',', '.');
                }else{
                    $total_penjualan = "-";
                }
            $aksi   = ' <a class="btn btn-sm btn-success text-light" id="btn-add" href="'.base_url().'penjualan/show/tambah_data/'.$baris->id.'">
                            <i class="fas fa-plus"> Tambah Data</i>
                        </a>
                        <a href="'.base_url().'penjualan/hapus/faktur/'.$baris->id.'" class="btn btn-sm btn-danger text-light tombol-hapus">
                            <i class="fas fa-trash-alt"></i>
                        </a>';
            
            $row = array();
            $row[] = $no;
            $row[] = $kode_faktur;
            $row[] = date('d-m-Y', strtotime($baris->tgl_faktur));
            $row[] = $baris->nama_penanggung_jawab;
            $row[] = $baris->nama_outlet;
            $row[] = $total_penjualan;
            $row[] = isAdmin() ? $aksi : "No Access";
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
    
    public function import_excel(){
        $model      = $this->model();
        $id_faktur  = $this->input->post('id_faktur');
		if (isset($_FILES["fileExcel"]["name"])) {
			$path = $_FILES["fileExcel"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				for($row=2; $row<=$highestRow; $row++)
				{
					$nama_produk= $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$kategori   = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$jumlah     = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$harga      = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
					$temp_data[] = array(
					    'id_faktur'     => $id_faktur,
					    'kategori'      => $kategori,
						'nama_produk'	=> $nama_produk,
						'jumlah'	    => $jumlah,
						'harga_per_pcs'	=> $harga
					);
				}
			}
			$insert = $this->$model->tambah_excel_penjualan($temp_data);
			if($insert){
			    $detail_penjualan = $this->Model_transaksi_penjualan->get_data_penjualan($id_faktur);
			    foreach($detail_penjualan as $row){
			        if( strtoupper($row->kategori) == 'KLAPPY' || strtoupper($row->kategori) == 'MINUMAN' || strtoupper($row->kategori) == 'MAKANAN' ||
			            strtoupper($row->kategori) == 'PLASTIK' || strtoupper($row->kategori) == 'KOTAK' || strtoupper($row->nama_produk) == 'APPLE PIE'
			        ){
			            // add transaksi keluar
                        $dt_keluar_penjualan = [
                            'id_gudang'             => $row->id_gudang,
                            'id_faktur'             => $id_faktur,
                            'jenis_transaksi'       => 'keluar',
                            'deskripsi_transaksi'   => 'penjualan',
                            'kode_faktur'           => $row->kode_faktur,
                            'id_produk'             => $row->id_produk,
                            'jumlah'                => $row->jumlah,
                            'tgl_faktur'            => $row->tgl_faktur
                        ];
                        $this->Model_gudang->add_transaksi_gudang($dt_keluar_penjualan);
                            
                        //update stok produk
                        $this->Model_Produk->update_stock_all($row->id_produk);
                        
                        // add or update stok out ke gudang
                        $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($row->id_gudang, $row->id_produk);
                        if($ambil_stok_gudang){
                            // update stok out
                            $dt_up_stok_out = [
                                'id_gudang' => $row->id_gudang,
                                'id_produk' => $row->id_produk,
                                'stok_out'   => (float)$ambil_stok_gudang[0]->stok_out + (float)$row->jumlah
                            ];
                            $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_out);
                        }else{
                            // add stok out
                            $dt_add_stok = [
                                'id_gudang' => $row->id_gudang,
                                'id_produk' => $row->id_produk,
                                'stok_out'  => $row->jumlah
                            ];
                            $this->Model_gudang->tambah_stok_gudang($dt_add_stok);
                        }
                        
                        // special condition for APPLE PIE
                        if(strtoupper($row->nama_produk) == 'APPLE PIE'){
                            // add transaksi masuk kulit small
                            $dt_masuk_penyesuaian = [
                                'id_gudang'             => $row->id_gudang,
                                'id_faktur'             => $id_faktur,
                                'jenis_transaksi'       => 'masuk',
                                'deskripsi_transaksi'   => 'penjualan (penyesuaian apple pie)',
                                'kode_faktur'           => $row->kode_faktur,
                                'id_produk'             => '152',
                                'jumlah'                => $row->jumlah,
                                'tgl_faktur'            => $row->tgl_faktur
                            ];
                            $this->Model_gudang->add_transaksi_gudang($dt_masuk_penyesuaian);
                            
                            // add or update stok in ke gudang
                            $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($row->id_gudang, '152');
                            if($ambil_stok_gudang){
                                // update stok in
                                $dt_up_stok = [
                                    'id_gudang' => $row->id_gudang,
                                    'id_produk' => '152',
                                    'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in + (float)$row->jumlah
                                ];
                                $this->Model_gudang->update_stok_gudang('in', $dt_up_stok);
                            }else{
                                // add stok in
                                $dt_add_stok = [
                                    'id_gudang' => $row->id_gudang,
                                    'id_produk' => '152',
                                    'stok_in'   => $row->jumlah
                                ];
                                $this->Model_gudang->tambah_stok_gudang($dt_add_stok);
                            }
                        }
                        
			        }else{
    			        // add transaksi masuk
                        $dt_masuk_penjualan = [
                            'id_gudang'             => $row->id_gudang,
                            'id_faktur'             => $id_faktur,
                            'jenis_transaksi'       => 'masuk',
                            'deskripsi_transaksi'   => 'penjualan',
                            'kode_faktur'           => $row->kode_faktur,
                            'id_produk'             => $row->id_produk,
                            'jumlah'                => $row->jumlah,
                            'tgl_faktur'            => $row->tgl_faktur
                        ];
                        $this->Model_gudang->add_transaksi_gudang($dt_masuk_penjualan);
                        
                        $dt_keluar_penjualan = [
                            'id_gudang'             => $row->id_gudang,
                            'id_faktur'             => $id_faktur,
                            'jenis_transaksi'       => 'keluar',
                            'deskripsi_transaksi'   => 'penjualan',
                            'kode_faktur'           => $row->kode_faktur,
                            'id_produk'             => $row->id_produk,
                            'jumlah'                => $row->jumlah,
                            'tgl_faktur'            => $row->tgl_faktur
                        ];
                        $this->Model_gudang->add_transaksi_gudang($dt_keluar_penjualan);
                            
                        //update stok produk
                        $this->Model_Produk->update_stock_all($row->id_produk);
                        
                        // add or update stok in ke gudang
                        $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($row->id_gudang, $row->id_produk);
                        if($ambil_stok_gudang){
                            // update stok in
                            $dt_up_stok = [
                                'id_gudang' => $row->id_gudang,
                                'id_produk' => $row->id_produk,
                                'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in + (float)$row->jumlah
                            ];
                            $this->Model_gudang->update_stok_gudang('in', $dt_up_stok);
                            // update stok out
                            $dt_up_stok_out = [
                                'id_gudang' => $row->id_gudang,
                                'id_produk' => $row->id_produk,
                                'stok_out'   => (float)$ambil_stok_gudang[0]->stok_out + (float)$row->jumlah
                            ];
                            $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_out);
                        }else{
                            // add stok in
                            $dt_add_stok = [
                                'id_gudang' => $row->id_gudang,
                                'id_produk' => $row->id_produk,
                                'stok_in'   => $row->jumlah
                            ];
                            $this->Model_gudang->tambah_stok_gudang($dt_add_stok);
                            // update stok out
                            $dt_add_stok_out = [
                                'id_gudang' => $row->id_gudang,
                                'id_produk' => $row->id_produk,
                                'stok_out'  =>(float)$row->jumlah
                            ];
                            $this->Model_gudang->update_stok_gudang('out', $dt_add_stok_out);
                        }
                        
                        // ambil bahan yang digunakan
                        $ambil_bahan = $this->Model_produk_formula->get_detail_formula_by_produk_no_array($row->id_produk);
                        if($ambil_bahan){
                            foreach($ambil_bahan as $ab){
                                $id_bahan   = $ab->id_bahan;
                                $total_bahan= (float)$row->jumlah * (float)$ab->jumlah_bahan;
                                // add transaksi keluar bahan
                                $dt_keluar_bahan = [
                                    'id_gudang'             => $row->id_gudang,
                                    'id_faktur'             => $id_faktur,
                                    'jenis_transaksi'       => 'keluar',
                                    'deskripsi_transaksi'   => 'penjualan',
                                    'kode_faktur'           => $row->kode_faktur,
                                    'id_produk'             => $id_bahan,
                                    'jumlah'                => $total_bahan,
                                    'tgl_faktur'            => $row->tgl_faktur
                                ];
                                $this->Model_gudang->add_transaksi_gudang($dt_keluar_bahan);
                                
                                // update stok out bahan di gudang
                                $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($row->id_gudang, $id_bahan);
                                $dt_up_stok_bahan = [
                                    'id_gudang' => $row->id_gudang,
                                    'id_produk' => $id_bahan,
                                    'stok_out'  => (float)$ambil_stok_gudang[0]->stok_out + (float)$total_bahan
                                ];
                                $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_bahan);
                                    
                                //update stok bahan
                                $this->Model_Produk->update_stock_all($id_bahan);
                            }
                        }
                        
			        }
			    }
			    
				$this->session->set_flashdata('status', '<span class="glyphicon glyphicon-ok"></span> Data Berhasil di Import ke Database');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('status', '<span class="glyphicon glyphicon-remove"></span> Terjadi Kesalahan');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			echo "Tidak ada file yang masuk";
		}
	}
	
	public function import_excel_diskon(){
        $model          = $this->model();
        $id_faktur = $this->input->post('id_faktur');
		if (isset($_FILES["fileExcel"]["name"])) {
			$path = $_FILES["fileExcel"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();	
				for($row=2; $row<=$highestRow; $row++)
				{
					$order_no       = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$customer       = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$subtotal       = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$diskon         = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$payment_mode   = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$temp_data[] = array(
					    'id_faktur'     => $id_faktur,
						'order_no'	    => $order_no,
						'customer'	    => $customer,
						'subtotal'	    => $subtotal,
						'diskon'	    => $diskon,
						'payment_mode'	=> $payment_mode
					); 	
				}
			}
			$insert = $this->$model->tambah_excel_diskon($temp_data);
			if($insert){
				$this->session->set_flashdata('status', '<span class="glyphicon glyphicon-ok"></span> Data Berhasil di Import ke Database');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('status', '<span class="glyphicon glyphicon-remove"></span> Terjadi Kesalahan');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			echo "Tidak ada file yang masuk";
		}
	}

}
