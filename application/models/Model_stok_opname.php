<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_stok_opname extends CI_Model
{
    
    function tabel(){
        $tabel = "faktur_stok_opname";
        return $tabel;
    }
    
    //ambil last id faktur
    public function get_last_id()
    {
        return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get($this->tabel())->row();
    }
    
    //ambil last id faktur
    public function get_last_detail_id()
    {
        return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('faktur_stok_opname_detail')->row();
    }
    
    // Ambil Data
    public function get_all()
    {
        $this->db->select('
            fso.*,
            u.nama as pjt
        ')
        ->from('faktur_stok_opname fso')
        ->join('user u', 'u.id_username=fso.penanggung_jawab')
        ->order_by('id',"DESC");
        return $this->db->get()->result();
    }
    
    // Ambil Data
    public function get_by_id($id_faktur)
    {
        $this->db->select('
            fso.*
        ')
        ->from('faktur_stok_opname fso')
        ->where('fso.id', $id_faktur);
        return $this->db->get()->result();
    }
    
    // Ambil Data
    public function get_detail($id_faktur)
    {
        $this->db->select('
            fsod.*
        ')
        ->from('faktur_stok_opname_detail fsod')
        ->where('fsod.id_faktur', $id_faktur);
        return $this->db->get()->result();
    }
    
    // Ambil Data
    public function get_all_stok()
    {
        return $this->db->get($this->tabel())->result_array();
    }
    
    // get produk opname harian
    public function get_produk_opname_harian($id_gudang){
        $this->db->select('
                gs.*,
                gs.status_opname_harian as status_opname,
                p.id as id_produk,
                p.nama as nama_produk,
                su.nama_satuan as satuan_dasar
            ')
            ->from('gudang_stok gs')
            ->join('produk p', 'p.id=gs.id_produk')
            ->join('satuan_ukuran su', 'su.id=p.id_satuan_dasar')
            ->where('gs.id_gudang ', $id_gudang);
        return $this->db->get()->result();
    }

    // Tambah
    public function tambah_faktur()
    {
        $id_gudang = $this->input->post('id_gudang');
        $nama_gudang = $this->input->post('nama_gudang');
        
        // input detail opname
        if(activeEntitasType() == "outlet"){
            if(date('m') != 01){
                $bulan  = (int)date('m') - 1;
                $year   = (int)date('Y');
            }else{
                $bulan  = 12;
                $year   = (int)date('Y') - 1;
            }
            $tgl = date('d').'-'.$bulan.'-'.$year.' '.date('H:i:s');
            $kode = $year.'/'.$bulan.'/'.$nama_gudang;
            $data = [
                'kode_faktur'       => $kode,
                'jenis_opname'      => "bulanan",
                'id_gudang'         => active_role()=="admin_entitas" || active_role()=="spv_gudang" ? activeOutlet() : $this->input->post('id_gudang'),
                'nama_gudang'       => $this->input->post('nama_gudang'),
                'penanggung_jawab'  => activeId(),
                'tgl_opname'        => datetime_db_format($tgl)
            ];
            
        }else{
            $kode = date('Y').'/'.date('m').'/'.$nama_gudang;
            $data = [
                'kode_faktur'       => $kode,
                'jenis_opname'      => "bulanan",
                'id_gudang'         => active_role()=="admin_entitas" || active_role()=="spv_gudang" ? activeOutlet() : $this->input->post('id_gudang'),
                'nama_gudang'       => $this->input->post('nama_gudang'),
                'penanggung_jawab'  => activeId()
            ];
        }
        $this->db->insert($this->tabel(), $data);
    }
    
    // Tambah
    public function tambah_faktur_harian()
    {
        $data = [
            'kode_faktur'       => "",
            'jenis_opname'      => "harian",
            'id_gudang'         => active_role()=="admin_entitas" || active_role()=="spv_gudang" ? activeOutlet() : $this->input->post('id_gudang'),
            'nama_gudang'       => $this->input->post('nama_gudang'),
            'penanggung_jawab'  => activeId()
        ];
        $this->db->insert($this->tabel(), $data);
    }
    
    // tambah detail
    public function tambah_detail($id_faktur="", $id_detail="")
    {
        $id_gudang = $this->input->post('id_gudang');
        $nama_gudang = $this->input->post('nama_gudang');
        $itung      = $this->input->post('itung');
        $selisih_nilai = 0;

        for($i=1; $i<=$itung; $i++){
            $data = [
                'id_faktur'     => $id_faktur,
                'id_produk'     => $this->input->post('id_produk_'.$i),
                'nama_produk'   => $this->input->post('nama_produk_'.$i),
                'stok_sistem'   => $this->input->post('stok_now_'.$i),
                'stok_nyata'    => $this->input->post('stok_real_'.$i)
            ];
            $this->db->insert('faktur_stok_opname_detail', $data);
            
            $data_produk = $this->Model_Produk->getById($data['id_produk']);
            $selisih_nilai = $selisih_nilai + ((float)$data_produk[0]->harga_satuan * ($data['stok_nyata'] - $data['stok_sistem']));
            
            // update stock terakhir untuk awal bulan
            $selisih_stok = $data['stok_nyata'] - $data['stok_sistem'];
            
            // input detail opname
            if($id_gudang != 1 && $id_gudang != 2 && $id_gudang != 14){
                if(date('m') != 01){
                    $bulan  = (int)date('m') - 1;
                    $year   = (int)date('Y');
                }else{
                    $bulan  = 12;
                    $year   = (int)date('Y') - 1;
                }
                $kode = $year.'/'.$bulan.'/'.$nama_gudang;
                $tgl = date('d').'-'.$bulan.'-'.$year;
            }else{
                $kode = date('Y').'/'.date('m').'/'.$nama_gudang;
                $tgl = date('d').'-'.date('m').'-'.date('Y');
            }
            
            $ambil_stok_gudang = $this->Model_gudang->get_produk_stok_gudang($id_gudang, $data['id_produk']);
            if($selisih_stok > 0){
                // add transaksi in
                $dt_trans_gudang_in = [
                    'id_gudang'             => $id_gudang,
                    'jenis_transaksi'       => 'masuk',
                    'deskripsi_transaksi'   => 'opname',
                    'kode_faktur'           => $kode,
                    'kode_detail'           => 'saldo awal',
                    'id_produk'             => $data['id_produk'],
                    'jumlah'                => $selisih_stok,
                    'tgl_faktur'            => date_db_format($tgl)
                ];
                $this->Model_gudang->add_transaksi_gudang($dt_trans_gudang_in);
                // update stok in
                $dt_up_stok_in = [
                    'id_gudang' => $this->input->post('id_gudang'),
                    'id_produk' => $data['id_produk'],
                    'stok_in'   => (float)$ambil_stok_gudang[0]->stok_in + (float)$selisih_stok
                ];
                $this->Model_gudang->update_stok_gudang('in', $dt_up_stok_in);
            
            }else if($selisih_stok < 0){
                // add transaksi out
                $dt_trans_gudang_out = [
                    'id_gudang'             => $id_gudang,
                    'jenis_transaksi'       => 'keluar',
                    'deskripsi_transaksi'   => 'opname',
                    'kode_faktur'           => $kode,
                    'kode_detail'           => 'saldo awal',
                    'id_produk'             => $data['id_produk'],
                    'jumlah'                => $selisih_stok,
                    'tgl_faktur'            => date_db_format($tgl)
                ];
                $this->Model_gudang->add_transaksi_gudang($dt_trans_gudang_out);
                // update stok out 
                $dt_up_stok_out = [
                    'id_gudang' => $this->input->post('id_gudang'),
                    'id_produk' => $data['id_produk'],
                    'stok_out'  => (float)$ambil_stok_gudang[0]->stok_out + (float)$selisih_stok
                ];
                $this->Model_gudang->update_stok_gudang('out', $dt_up_stok_out);
            }
        }
        
        $data_update = [
            'selisih' => $selisih_nilai
        ];
        $this->update($id_faktur, $data_update);
    }
    
    // tambah detail
    public function tambah_detail_harian($id_faktur="", $id_detail="")
    {
        $itung      = $this->input->post('itung');
        $selisih_nilai = 0;

        for($i=1; $i<=$itung; $i++){
            $data = [
                'id_faktur'     => $id_faktur,
                'id_produk'     => $this->input->post('id_produk_harian_'.$i),
                'nama_produk'   => $this->input->post('nama_produk_harian_'.$i),
                'stok_sistem'   => $this->input->post('stok_now_harian_'.$i),
                'stok_nyata'    => $this->input->post('stok_real_'.$i)
            ];
            $this->db->insert('faktur_stok_opname_detail', $data);
            
            $data_produk = $this->Model_Produk->getById($data['id_produk']);
            $selisih_nilai = $selisih_nilai + ((float)$data_produk[0]->harga_satuan * ($data['stok_nyata'] - $data['stok_sistem']));
        }
        
        $data_update = [
            'selisih' => $selisih_nilai
        ];
        $this->update($id_faktur, $data_update);
    }
    
    // Hapus
    public function hapus_faktur($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->tabel());
    }
    
    // Hapus detail
    public function hapus_detail($id_faktur)
    {
        $this->db->where('id_faktur', $id_faktur);
        $this->db->delete('faktur_stok_opname_detail');
    }
    
    // Update
    public function update($id_faktur, $data)
    {
        $this->db->where('id', $id_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    // Update
    public function update_status_opname_harian()
    {
        $itung = 0;
        $id_gudang = $this->input->post('id_gudang');
        
        $data_produk = $this->Model_gudang->get_produk_by_gudang($id_gudang);
        foreach($data_produk as $row){
            $itung += 1;
            $checked = $this->input->post('check_list['.$itung.']');
            if($checked){
                $data = [
                    'status_opname_harian' => '1'
                ];
                
                $this->db->where('id_gudang', $id_gudang);
                $this->db->where('id_produk', $checked);
                $this->db->update('gudang_stok', $data);
            }else{
                $data = [
                    'status_opname_harian' => '0'
                ];
                
                $this->db->where('id_gudang', $id_gudang);
                $this->db->where('id_produk', $row->id_produk);
                $this->db->update('gudang_stok', $data);
            }
        }
    }
    
    // filter ------------------------------------------------------------------------------------------------------------------------------
    var $table = 'faktur_stok_opname';
    var $column_order = array(null, 'no_faktur', 'no_invoice', 'tgl_faktur', 'id_supplier'); //set column field database for datatable orderable
    var $column_search = array('tgl_faktur'); //set column field database for datatable searchable 
    var $order = array('id' => 'DESC'); // default order
 
    private function _get_datatables_query()
    {
        //add custom filter here
        if($this->input->post('filter_dari_tgl')){
            $this->db->where('tgl_faktur >=', $this->input->post('filter_dari_tgl'));
        }
        if($this->input->post('filter_ke_tgl')){
            $this->db->where('tgl_faktur <=', $this->input->post('filter_ke_tgl'));
        }
        if($this->input->post('filter_gudang') && $this->input->post('filter_gudang')!="nothing"){
            $this->db->where('fso.id_gudang', $this->input->post('filter_gudang'));
        }
        if($this->input->post('filter_karyawan') && $this->input->post('filter_karyawan')!="nothing"){
            $this->db->where('id_karyawan', $this->input->post('filter_karyawan'));
        }
        
        $this->db->select('
            fso.*,
            u.nama as pjt
        ')
        ->from('faktur_stok_opname fso')
        ->join('user u', 'u.id_username=fso.penanggung_jawab');

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
