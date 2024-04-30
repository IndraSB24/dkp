<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_transaksi_penjualan extends CI_Model
{
    function tabel(){
        $tabel = "faktur_penjualan";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        $this->db->select('
            fp.*,
            g.nama as nama_outlet,
            k.nama as nama_penanggung_jawab
        ')
        ->from('faktur_penjualan fp')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->join('karyawan k', 'k.id=fp.penanggung_jawab')
        ->order_by('id', "DESC");
        return $this->db->get()->result();
    }
    
    //ambil last id faktur
    // public function get_last_id()
    // {
    //     return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get($this->tabel())->row();
    // }
    public function get_last_id()
    {
        $current_year = date('Y'); 
    
        return $this->db
            ->select("*")
            ->where('YEAR(created_at) =', $current_year)
            ->limit(1)
            ->order_by('id', 'DESC')
            ->get($this->tabel())
            ->row();
    }
    
    //ambil last id faktur
    public function get_last_detail_id()
    {
        return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('faktur_mutasi_detail')->row();
    }
    
    //ambil data faktur
    public function get_by_id($id_faktur)
    {
        $this->db->select('
            fp.*,
            g.nama as nama_outlet,
            k.nama as nama_penanggung_jawab
        ')
        ->from('faktur_penjualan fp')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->join('karyawan k', 'k.id=fp.penanggung_jawab')
        ->where('fp.id', $id_faktur);
        return $this->db->get()->result();
    }
    
    // AMbil detail data faktur
    public function get_detail_faktur($id_faktur)
    {
        $this->db->select('
            fmd.*,
            fmd.kode as kode,
            fmd.jumlah as jumlah,
            fmd.id_produk as id_produk,
            fmd.harga as harga,
            fm.nilai_mutasi as nilai_mutasi,
            fm.dibayar as dibayar,
            p.nama as nama_produk,
            su.nama_satuan as satuan_dasar
        ')
        ->from('faktur_mutasi_detail fmd')
        ->join('faktur_mutasi fm', 'fm.id=fmd.id_faktur')
        ->join('produk p', 'p.id=fmd.id_produk')
        ->join('satuan_ukuran su', 'su.id=p.id_satuan_dasar')
        ->where('fmd.id_faktur', $id_faktur);
        return $this->db->get()->result();
    }
    
    //ambil list id faktur
    public function get_id_faktur_list($lokasi){
        $this->db->select('
            fp.id as id_faktur
        ')
        ->from('faktur_penjualan fp')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->where('g.nama', $lokasi);
        return $this->db->get()->result();
    }
    
    //ambil data penjualan
    public function get_data_penjualan($id_faktur)
    {
        $this->db->select('
            fpd.jumlah as jumlah,
            fpd.harga_per_pcs as harga,
            fpd.nama_produk as nama_produk,
            fpd.kategori as kategori,
            kp.nama as kelompok_produk,
            fp.kode_faktur as kode_faktur,
            fp.id_gudang as id_gudang,
            fp.tgl_faktur as tgl_faktur,
            p.id as id_produk,
        ')
        ->from('faktur_penjualan_detail fpd')
        ->join('faktur_penjualan fp', 'fp.id=fpd.id_faktur')
        ->join('produk p', 'p.nama=fpd.nama_produk')
        ->join('kelompok_produk kp', 'kp.id=p.id_kelompok_produk')
        ->where('fp.id', $id_faktur);
        return $this->db->get()->result();
    }
    
    //ambil data diskon
    public function get_data_diskon($id_faktur)
    {
        $this->db->select('
            fpd.*
        ')
        ->from('faktur_penjualan_diskon fpd')
        ->where('fpd.id_faktur', $id_faktur);
        return $this->db->get()->result();
    }
    
    //ambil data diskon
    public function get_total_diskon($id_faktur, $payment)
    {
        $this->db->select('
            sum(fpd.subtotal) as sub_total,
            sum(fpd.diskon) as total_diskon
        ')
        ->from('faktur_penjualan_diskon fpd')
        ->where('fpd.id_faktur', $id_faktur)
        ->where('fpd.payment_mode', $payment);
        return $this->db->get()->result();
    }
    
    public function get_list_total_penjualan()
    {
        $this->db->select('
            fp.*,
            sum(fpd.subtotal) as total_penjualan
        ')
        ->from('faktur_penjualan_diskon fpd')
        ->join('faktur_penjualan fp', 'fp.id=fpd.id_faktur')
        ->group_by('fpd.id_faktur');
        return $this->db->get()->result();
    }
    
    //ambil total penjualan by id
    public function get_total_penjualan($id_faktur)
    {
        $this->db->select('
            sum(fpd.subtotal) as total_penjualan
        ')
        ->from('faktur_penjualan_diskon fpd')
        ->group_by('fpd.id_faktur')
        ->where('fpd.id_faktur', $id_faktur);
        return $this->db->get()->result();
    }
    
    //ambil total penjualan
    public function get_total_penjualan_all()
    {
        if(activeKota() && active_role()=="spv_outlet"){
            $this->db->where("g.id_kota", activeKota());
        }
        
        $this->db->select('
            sum(fpd.subtotal) as total_penjualan,
        ')
        ->from('faktur_penjualan_diskon fpd')
        ->join('faktur_penjualan fp', "fp.id=fpd.id_faktur")
        ->join('gudang g', "g.id=fp.id_gudang");
        return $this->db->get()->result();
    }
    
    //ambil total faktur
    public function get_total_faktur()
    {
        if(activeKota() && active_role()=="spv_outlet"){
            $this->db->where("g.id_kota", activeKota());
        }
        
        $this->db->select('
            count(fp.id) as total_faktur
        ')
        ->from('faktur_penjualan fp')
        ->join('gudang g', "g.id=fp.id_gudang");
        return $this->db->get()->result();
    }
    
    //ambil total faktur
    public function get_total_faktur_where($lokasi)
    {
        $this->db->select('
            count(fp.id) as total_faktur,
            g.nama as nama_outlet
        ')
        ->from('faktur_penjualan fp')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->where('g.nama', $lokasi);
        return $this->db->get()->result();
    }
    
    //ambil data manual
    public function get_data_manual($id_faktur)
    {
        $this->db->select('
            fpm.*
        ')
        ->from('faktur_penjualan_manual fpm')
        ->where('fpm.id_faktur', $id_faktur);
        return $this->db->get()->result();
    }
    
    public function tambah_excel_penjualan($data){
        $this->Model_all->reset_increment('faktur_penjualan_detail');
		$insert = $this->db->insert_batch('faktur_penjualan_detail', $data);
		if($insert){
			return true;
		}
	}
	
	public function tambah_excel_diskon($data){
		$this->Model_all->reset_increment('faktur_penjualan_diskon');
		$insert = $this->db->insert_batch('faktur_penjualan_diskon', $data);
		if($insert){
			return true;
		}
	}

    // Tambah
    public function tambah_faktur()
    {
        $data = [
                'kode_faktur'   => $this->input->post('no_faktur'),
                'urut'          => $this->input->post('kode_urut'),
                'penanggung_jawab'=> $this->input->post('karyawan'),
                'id_gudang'     => $this->input->post('gudang'),
                'tgl_faktur'    => date_db_format($this->input->post('tanggal_faktur')),
                'created_by'    => activeId()
            ];
        $this->db->insert($this->tabel(), $data);
    }
    
    // tambah detail permintaan 
    public function tambah_detail_permintaan($id_faktur)
    {
        $itung      = $this->input->post('itung');

        for($i=1; $i<=$itung; $i++){
            $jumlah     = $this->input->post('jumlah_'.$i);
            
            $data = [
                'id_faktur'     => $id_faktur,
                'id_produk'     => $this->input->post('id_produk_'.$i),
                'jumlah'        => $jumlah
            ];
            
            if($data['id_produk'] != 0){
                $this->db->insert('faktur_mutasi_detail_permintaan', $data);
            }
        }
    }
    
    // tambah detail mutasi
    // public function tambah_faktur_detail($id_faktur, $id_detail)
    // {
    //     //$itung      = $this->count_row($id_faktur);
    //     $dari_gudang= $this->input->post('dari_gudang');
    //     $ke_gudang  = $this->input->post('ke_gudang');
    //     $itung      = $this->input->post('itung');
    //     $urut       = (int)$id_detail;
    //     for($i=1; $i<=$itung; $i++){
    //         $urut       = $urut+1;
    //         $jumlah     = (float)$this->input->post('jumlah_'.$i);
            
    //         $data = [
    //             'id_faktur'     => $id_faktur,
    //             'kode'          => 'T-MTS-'.$urut,
    //             'id_produk'     => $this->input->post('id_produk_'.$i),
    //             'jumlah'        => $jumlah,
    //             'harga'         => $this->input->post('harga_'.$i),
    //             'harga_mutasi'  => (float)$jumlah * $this->input->post('harga_'.$i)
    //         ];
            
    //         if($data['id_produk'] != 0){
    //             $this->db->insert('faktur_mutasi_detail', $data);
                
    //             $this->Model_all->reset_increment('gudang_transaksi');
    //             // add transaksi dari gudang
    //             $dt_dari_gudang = [
    //                 'id_gudang'             => $dari_gudang,
    //                 'jenis_transaksi'       => 'keluar',
    //                 'deskripsi_transaksi'   => 'mutasi',
    //                 'kode_faktur'           => $this->input->post('no_faktur'),
    //                 'kode_detail'           => $data['kode'],
    //                 'id_produk'             => $data['id_produk'],
    //                 'jumlah'                => $data['jumlah'],
    //                 'tgl_faktur'            => date_db_format($this->input->post('tanggal_faktur'))
    //             ];
    //             $this->Model_gudang->add_transaksi_gudang($dt_dari_gudang);
                
    //             // add transaksi ke gudang
    //             $dt_ke_gudang = [
    //                 'id_gudang'             => $ke_gudang,
    //                 'jenis_transaksi'       => 'masuk',
    //                 'deskripsi_transaksi'   => 'mutasi',
    //                 'kode_faktur'           => $this->input->post('no_faktur'),
    //                 'kode_detail'           => $data['kode'],
    //                 'id_produk'             => $data['id_produk'],
    //                 'jumlah'                => $data['jumlah'],
    //                 'tgl_faktur'            => date_db_format($this->input->post('tanggal_faktur'))
    //             ];
    //             $this->Model_gudang->add_transaksi_gudang($dt_ke_gudang);
                
    //             // update stok dari gudang
    //             $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($dari_gudang, $data['id_produk']);
    //             $dt_stok_dari_gudang = [
    //                 'id_gudang' => $dari_gudang,
    //                 'id_produk' => $data['id_produk'],
    //                 'stok_out'  => (float)$ambil_stok_gudang[0]->stok_out + (float)$data['jumlah']
    //             ];
    //             $this->Model_gudang->update_stok_gudang('out', $dt_stok_dari_gudang);
                
    //             // add or update stok ke gudang
    //             $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($ke_gudang, $data['id_produk']);
    //             if($ambil_stok_gudang){
    //                 // update stok
    //                 $dt_stok_ke_gudang = [
    //                     'id_gudang' => $ke_gudang,
    //                     'id_produk' => $data['id_produk'],
    //                     'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in + (float)$data['jumlah']
    //                 ];
    //                 $this->Model_gudang->update_stok_gudang('in', $dt_stok_ke_gudang);
    //             }else{
    //                 // add stok
    //                 $dt_stok_ke_gudang = [
    //                     'id_gudang' => $ke_gudang,
    //                     'id_produk' => $data['id_produk'],
    //                     'stok_in'   => $data['jumlah']
    //                 ];
    //                 $this->Model_gudang->tambah_stok_gudang($dt_stok_ke_gudang);
    //             }
    //         }
    //     }
    // }
    
    // Tambah data manual
    public function tambah_data_manual()
    {
        $data = [
                'id_faktur'     => $this->input->post('id_faktur'),
                'omset'         => $this->input->post('in_omset'),
                'cash'          => $this->input->post('in_cash'),
                'diskon_cash'   => $this->input->post('in_diskon_cash'),
                'sfood'         => $this->input->post('in_shopee'),
                'diskon_sfood'  => $this->input->post('in_diskon_shopee'),
                'gofood'        => $this->input->post('in_gojek'),
                'diskon_gofood' => $this->input->post('in_diskon_gojek'),
                'grfood'        => $this->input->post('in_grab'),
                'diskon_grfood' => $this->input->post('in_diskon_grab'),
                'created_by'    => activeId()
            ];
        $this->db->insert('faktur_penjualan_manual', $data);
    }
    
    // Hapus faktur
    public function hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->tabel());
        
        $this->db->where('id_faktur', $id);
        $this->db->delete('faktur_penjualan_detail');
        
        $this->db->where('id_faktur', $id);
        $this->db->delete('faktur_penjualan_diskon');
    }
    
    public function hapus_data_penjualan($id)
    {
        $this->db->where('id_faktur', $id);
        $this->db->delete('faktur_penjualan_detail');
    }
    
    public function hapus_data_diskon($id)
    {
        $this->db->where('id_faktur', $id);
        $this->db->delete('faktur_penjualan_diskon');
    }
    
    public function hapus_data_manual($id)
    {
        $this->db->where('id_faktur', $id);
        $this->db->delete('faktur_penjualan_manual');
    }
    
    
    // Update 
    public function update_faktur($id_faktur, $data)
    {
        $this->db->where('id', $id_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    // Update total mutasi
    public function update_total_mutasi($id_faktur, $total_mutasi)
    {
        $data = [
            'total_mutasi' => $total_mutasi
        ];
        
        $this->db->where('id', $id_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    // Update total nilai mutasi
    public function update_nilai_mutasi($id_faktur, $nilai_mutasi)
    {
        $data = [
            'nilai_mutasi' => $nilai_mutasi
        ];
        
        $this->db->where('id', $id_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    // Update bayar piutang
    public function update_bayar_piutang($no_faktur, $jumlah, $tgl)
    {
        $data = [
            'dibayar' => $jumlah,
            'tgl_bayar' => date_db_format($tgl)
        ];
        
        $this->db->where('no_faktur', $no_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    public function update_status_piutang($no_faktur, $status)
    {
        $data = [
            'status_piutang' => $status
        ];
        
        $this->db->where('no_faktur', $no_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    
    // filter ------------------------------------------------------------------------------------------------------------------------------
    var $table = 'faktur_penjualan';
    var $column_order = array(null, 'kode_faktur','id_gudang','tgl_faktur','penanggung_jawab'); //set column field database for datatable orderable
    var $column_search = array('kode_faktur','id_gudang','tgl_faktur','penanggung_jawab'); //set column field database for datatable searchable 
    var $order = array('id' => 'DESC'); // default order
 
    private function _get_datatables_query()
    {
        //add custom filter here
        if($this->input->post('filter_dari_tgl') && $this->input->post('filter_dari_tgl')!="nothing")
        {
            $this->db->where('tgl_faktur >=', $this->input->post('filter_dari_tgl'));
        }
        if($this->input->post('filter_ke_tgl') && $this->input->post('filter_ke_tgl')!="nothing")
        {
            $this->db->where('tgl_faktur <=', $this->input->post('filter_ke_tgl'));
        }
        if($this->input->post('filter_outlet') && $this->input->post('filter_outlet')!="nothing")
        {
            $this->db->where('id_gudang', $this->input->post('filter_outlet'));
        }
        if($this->input->post('filter_karyawan') && $this->input->post('filter_karyawan')!="nothing")
        {
            $this->db->where('penanggung_jawab', $this->input->post('filter_karyawan'));
        }
        
        if(activeKota() && active_role()=="spv_outlet"){
            $this->db->where('g.id_kota', activeKota());
        }
        
        $this->db->select('
            fp.*,
            g.nama as nama_outlet,
            k.nama as nama_penanggung_jawab
        ')
        ->from('faktur_penjualan fp')
        ->join('gudang g', 'g.id=fp.id_gudang')
        ->join('karyawan k', 'k.id=fp.penanggung_jawab')
        ->order_by('id', "DESC");

        $i = 0;
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    public function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
 
    public function get_list_countries()
    {
        $this->db->select('country');
        $this->db->from($this->table);
        $this->db->order_by('country','asc');
        $query = $this->db->get();
        $result = $query->result();
 
        $countries = array();
        foreach ($result as $row) 
        {
            $countries[] = $row->country;
        }
        return $countries;
    }
    
}
