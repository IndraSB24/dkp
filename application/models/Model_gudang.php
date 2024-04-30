<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_gudang extends CI_Model
{
    function tabel(){
        $tabel = "gudang";
        return $tabel;
    }
    
    // Ambil Data Semua Gudang
    public function get_all()
    {
        $this->db->select('
            g.*,
            u.nama as dibuat_oleh,
            lk.nama as nama_kota
        ')
        ->from('gudang g')
        ->join('user u', 'u.id_username=g.created_by', 'left')
        ->join('list_kota lk', 'lk.id=g.id_kota', 'left');
        return $this->db->get()->result_array();
    }
    
    // Ambil Data Semua Gudang
    public function get_all_no_array()
    {
        if(activeKota()){
            $this->db->where("gudang.id_kota", activeKota());
        }
        return $this->db->get('gudang')->result();
    }
    
    // Ambil List Semua Gudang
    public function get_list()
    {
        if(activeKota()){
            $this->db->where("gudang.id_kota", activeKota());
        }
        return $this->db->get('gudang')->result_array();
    }
    
    // get data by where
    public function get_where($column, $value)
    {
        $column = $this->db->escape_str($column);
        $value = $this->db->escape_str($value);
    
        $this->db->where($column, $value);
        return $this->db->get('gudang')->result_array();
    }
    
    // get for import
    public function get_for_import($column, $value)
    {
        $column = $this->db->escape_str($column);
        $value = $this->db->escape_str($value);
    
        $this->db->where($column, $value);
        return $this->db->get('gudang')->result_array();
    }

    
    // Ambil Data Semua Gudang
    public function get_for_export()
    {
        $this->db->select('
            nama
        ')
        ->from('gudang')
        ->order_by('nama', 'ASC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();

            // Add a header row for the 'Produk' sheet
            $header = array('Nama');
            array_unshift($result, $header);

            return $result;
        }

        return array();
    }
    
    //ambil by id gudang
    public function get_by_id($id)
    {
        $this->db->select('
            g.*
        ')
        ->from('gudang g')
        ->where('g.id', $id);
        return $this->db->get()->result();
    }

    // Tambah
    public function tambah($user_id)
    {
        $nama   = $this->input->post('nama');
        $alamat = $this->input->post('alamat');
        
        $data = [
            'nama'      => $nama,
            'kategori'  => $this->input->post('kategori'),
            'id_kota'   => $this->input->post('kota'),
            'alamat'    => $alamat,
            'created_by'=> activeId()
        ];

        $this->db->insert($this->tabel(), $data);
    }
    
    // Hapus
    public function hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->tabel());
    }
    
    // Update
    public function update($user_id)
    {
        $id_edit= $this->input->post('id_edit');
        $nama   = $this->input->post('nama_edit');
        $alamat = $this->input->post('alamat_edit');
        
        $data = [
            'nama'      => $nama,
            'alamat'    => $alamat,
            'kategori'  => $this->input->post('kategori_edit'),
            'id_kota'   => $this->input->post('kota_edit'),
        ];
        
        $this->db->where('id', $id_edit);
        $this->db->update($this->tabel(), $data);
    }
    
    
//transaksi gudang--------------------------------------------------------------------------------------------------------------
    // ambil all transaksi gudang
    public function get_all_transaksi(){
        $this->db
            ->select('
                gt.*,
                g.nama as nama_gudang,
                p.nama as nama_produk
            ')
            ->from('gudang_transaksi gt')
            ->join('gudang g', 'g.id=gt.id_gudang')
            ->join('produk p', 'p.id=gt.id_produk')
            ->order_by('gt.id_gudang ASC');
        return $this->db->get()->result();
    }
    
    // ambil transaksi gudang by produk (not mutasi)
    public function get_transaksi_in_by_produk($id_produk){
        $this->db
            ->select('gt.*')
            ->from('gudang_transaksi gt')
            ->where('gt.jenis_transaksi="masuk" AND deskripsi_transaksi!="mutasi" AND gt.id_produk='.$id_produk);
        return $this->db->get()->result();
    }
    
    // ambil transaksi gudang by produk (not mutasi)
    public function get_transaksi_out_by_produk($id_produk){
        $this->db->select('
                            gt.*
                        ')
            ->from('gudang_transaksi gt')
            ->where('gt.jenis_transaksi="keluar" AND deskripsi_transaksi!="mutasi" AND gt.id_produk='.$id_produk);
        return $this->db->get()->result();
    }
    
    //tambah transaksi gudang
    public function add_transaksi_gudang($data){
        $this->Model_all->reset_increment('gudang_transaksi');
        $data = [
            'id_gudang'             => $data['id_gudang'],
            'id_faktur'             => $data['id_faktur'] ? $data['id_faktur'] : "not set",
            'jenis_transaksi'       => $data['jenis_transaksi'],
            'deskripsi_transaksi'   => $data['deskripsi_transaksi'],
            'kode_faktur'           => $data['kode_faktur'],
            'kode_detail'           => $data['kode_detail'],
            'id_produk'             => $data['id_produk'],
            'jumlah'                => $data['jumlah'],
            'tgl_faktur'            => $data['tgl_faktur']
        ];
        $this->db->insert('gudang_transaksi', $data);
    }
    
    //hapus transaksi by faktur
    public function delete_by_faktur($kode_faktur){
        $this->db->where('kode_faktur', $kode_faktur);
        $this->db->delete('gudang_transaksi');
    }
    
    //hapus transaksi by kode transaksi
    public function delete_by_kode_transaksi($kode){
        $this->db->where('deskripsi_transaksi', $kode);
        $this->db->delete('gudang_transaksi');
    }


// Stok Gudang--------------------------------------------------------------------------------------------------------------------
    // get all by gudang
    public function get_produk_by_gudang($id_gudang){
        $this->db->select('
                gs.*,
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
    
    // get all by gudang
    public function get_stok_by_gudang($id_gudang){
        $this->db->select('
                            *
                        ')
            ->from('gudang_stok gs')
            ->where('gs.id_gudang ', $id_gudang);
        return $this->db->get()->result();
    }
    
    // ambil stok per produk per gudang
    public function get_produk_stok_gudang($id_gudang, $id_produk){
        $this->db->select('
                *
            ')
            ->from('gudang_stok gs')
            ->where('gs.id_gudang='.$id_gudang.' AND gs.id_produk='.$id_produk);
        return $this->db->get()->result();
    }
    
    // add stok by gudang
    public function tambah_stok_gudang($data){
        $this->Model_all->reset_increment('gudang_stok');
        $data_update = [
            'id_gudang'     => $data['id_gudang'],
            'id_produk'     => $data['id_produk'],
            'stok_in'       => $data['stok_in'],
            'stok_out'      => $data['stok_out'],
        ];
        $this->db->insert('gudang_stok', $data);
    }
    
    // update stok by gudang and produk
    public function update_stok_gudang($kode, $data){
        if($kode == "in"){
            $data_update = [
                'stok_in'   => $data['stok_in']
            ];
        }else if($kode == "out"){
            $data_update = [
               'stok_out'  => $data['stok_out']
            ];
        }
        $this->db->where('id_gudang='.$data['id_gudang'].' AND id_produk='.$data['id_produk']);
        $this->db->update('gudang_stok', $data_update);
    }
    
    
    // filter ------------------------------------------------------------------------------------------------------------------------------
    var $table = 'gudang_transaksi';
    var $column_order = array(null, 'id_gudang','id_produk','jenis_transaksi','deskripsi_transaksi','kode_faktur','jumlah','tgl_faktur'); //set column field database for datatable orderable
    var $column_search = array('nama_gudang','nama_produk','jenis_transaksi','adddeskripsi_transaksiress','kode_faktur','jumlah','tgl_faktur'); //set column field database for datatable searchable 
    var $order = array('id' => 'asc'); // default order
 
    private function _get_datatables_query()
    {
        //add custom filter here
        if($this->input->post('id_gudang') && $this->input->post('id_gudang')!="nothing")
        {
            $this->db->where('id_gudang', $this->input->post('id_gudang'));
        }
        if($this->input->post('id_produk') && $this->input->post('id_produk')!="nothing")
        {
            $this->db->where('id_produk', $this->input->post('id_produk'));
        }
        if($this->input->post('jenis_transaksi') && $this->input->post('jenis_transaksi')!="nothing")
        {
            $this->db->where('jenis_transaksi', $this->input->post('jenis_transaksi'));
        }
        if($this->input->post('deskripsi_transaksi') && $this->input->post('deskripsi_transaksi')!="nothing")
        {
            $this->db->where('deskripsi_transaksi', $this->input->post('deskripsi_transaksi'));
        }
        if($this->input->post('tgl_faktur') && $this->input->post('tgl_faktur')!="nothing")
        {
            $this->db->where('tgl_faktur >=', date_db_format($this->input->post('tgl_faktur')));
        }
        if($this->input->post('ke_tgl') && $this->input->post('ke_tgl')!="nothing")
        {
            $this->db->where('tgl_faktur <=', date_db_format($this->input->post('ke_tgl')));
        }
        
        $this->db->select('
                gt.*,
                g.nama as nama_gudang,
                p.nama as nama_produk,
                p.kode as kode_produk,
                su.nama_satuan as satuan_dasar
            ')
        ->from('gudang_transaksi gt')
        ->join('gudang g', 'g.id=gt.id_gudang')
        ->join('produk p', 'p.id=gt.id_produk')
        ->join('satuan_ukuran su', 'su.id=p.id_satuan_dasar');
        
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




















