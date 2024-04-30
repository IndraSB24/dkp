<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_divisi extends CI_Model
{
    function tabel(){
        $tabel = "gudang_divisi";
        return $tabel;
    }
    
    // Ambil Data Semua Divisi
    public function get_all_divisi()
    {
        $this->db->select('
            gd.*,
            g.nama as nama_gudang,
            pjt.nama as nama_pjt,
            creator.nama as pembuat
        ')
        ->from('gudang_divisi gd')
        ->join('gudang g', 'g.id=gd.id_gudang')
        ->join('karyawan pjt', 'pjt.id=gd.pjt')
        ->join('user creator', 'creator.id_username=gd.created_by');
        return $this->db->get()->result();
    }
    
    //ambil by id gudang
    public function get_all($id_gudang="")
    {
        $this->db->select('
            g.*,
            pjt.nama as nama_pjt,
            creator.nama as pembuat
        ')
        ->from('gudang_divisi g')
        ->join('karyawan pjt', 'pjt.id=g.pjt')
        ->join('user creator', 'creator.id_username=g.created_by')
        ->where('g.id_gudang', $id_gudang);
        return $this->db->get()->result();
    }
    
    public function get_by_id($id)
    {
        $this->db->select('
            g.*
        ')
        ->from('gudang_divisi g')
        ->where('g.id', $id);
        return $this->db->get()->result();
    }

    // Tambah
    public function tambah()
    {
        $data = [
            'nama'      => $this->input->post('nama'),
            'pjt'       => $this->input->post('pjt'),
            'id_gudang' => $this->input->post('id_gudang'),
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
        ];
        
        $this->db->where('id', $id_edit);
        $this->db->update($this->tabel(), $data);
    }
    
    
//transaksi divisi--------------------------------------------------------------------------------------------------------------
    // ambil all transaksi divisi
    public function get_all_transaksi(){
        $this->db
            ->select('
                gdt.*,
                g.nama as nama_gudang,
                gd.nama as nama_divisi,
                p.nama as nama_produk
            ')
            ->from('gudang_divisi_transaksi gdt')
            ->join('gudang_divisi gd', 'gd.id=gdt.id_divisi')
            ->join('produk p', 'p.id=gdt.id_produk')
            ->join('gudang g', 'g.id=gd.id_gudang');
        return $this->db->get()->result();
    }
    
    public function get_transaksi_by_produk_by_divisi($id_divisi, $id_produk){
        $this->db
        ->select('
            gdt.*
        ')
        ->from('gudang_divisi_transaksi gdt')
        ->where('gdt.id_produk', $id_produk)
        ->where('gdt.id_divisi', $id_divisi);
        return $this->db->get()->result();
    }
    
    //tambah transaksi divisi
    public function add_transaksi_divisi($data){
        $this->Model_all->reset_increment('gudang_divisi_transaksi');
        $data = [
            'id_divisi'             => $data['id_divisi'],
            'jenis_transaksi'       => $data['jenis_transaksi'],
            'deskripsi_transaksi'   => $data['deskripsi_transaksi'],
            'kode_faktur'           => $data['kode_faktur'],
            'id_produk'             => $data['id_produk'],
            'jumlah'                => $data['jumlah']
        ];
        $this->db->insert('gudang_divisi_transaksi', $data);
    }
    
    //hapus transaksi by faktur
    public function delete_by_faktur($kode_faktur){
        $this->db->where('kode_faktur', $kode_faktur);
        $this->db->delete('gudang_divisi_transaksi');
    }

// Stok Divisi------------------------------------------------------------------------------------------------------------------------------
    //get all
    public function get_all_divisi_stok($id_gudang){
        $this->db->select('
            gds.*,
            p.nama as nama_produk,
            d.nama as nama_divisi
        ')
        ->from('gudang_divisi_stok gds')
        ->join('produk p', 'p.id=gds.id_produk')
        ->join('gudang_divisi d', 'd.id=gds.id_divisi')
        ->where('d.id_gudang', $id_gudang)
        ->group_by('gds.id_produk');
        return $this->db->get()->result();
    }
    
    // get all by divisi
    public function get_produk_by_divisi($id_divisi){
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
    
    // get all by divisi
    public function get_stok_by_gudang($id_gudang){
        $this->db->select('
            *
        ')
        ->from('gudang_stok gs')
        ->where('gs.id_gudang ', $id_gudang);
        return $this->db->get()->result();
    }
    
    // ambil stok per produk per divisi
    public function get_stok_produk_by_divisi($id_divisi, $id_produk){
        $this->db->select('
            *
        ')
        ->from('gudang_divisi_stok gds')
        ->where('gds.id_divisi='.$id_divisi.' AND gds.id_produk='.$id_produk);
        return $this->db->get()->result();
    }
    
    // add stok produk to divisi
    public function tambah_stok_divisi($data){
        $this->Model_all->reset_increment('gudang_divisi_stok');
        $data_update = [
            'id_divisi'     => $data['id_divisi'],
            'id_produk'     => $data['id_produk']
        ];
        $this->db->insert('gudang_divisi_stok', $data);
    }
    
    public function auto_add_stok($id_divisi, $id_produk){
        $cek_stok_produk = $this->get_stok_produk_by_divisi($id_divisi, $id_produk);
        if($cek_stok_produk == FALSE){
            $dt_add_stok = [
                'id_divisi' => $id_divisi,
                'id_produk' => $id_produk
            ];
            $this->db->insert('gudang_divisi_stok', $dt_add_stok);
        }
    }
    
    public function auto_update_stock_per_divisi($id_divisi, $id_produk){
        $data['stok_out']   = 0;
        $data['stok_in']    = 0;
        $ambil_transaksi  = $this->get_transaksi_by_produk_by_divisi($id_divisi, $id_produk);
        foreach($ambil_transaksi as $row){
            if($row->jenis_transaksi == "masuk"){
                $data['stok_in'] += $row->jumlah;    
            }else if($row->jenis_transaksi == "keluar"){
                $data['stok_out'] += $row->jumlah; 
            }
        }
        $this->db->where('id_divisi='.$id_divisi.' AND id_produk='.$id_produk);
        $this->db->update('gudang_divisi_stok', $data);
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
    var $table = 'gudang_divisi_transaksi';
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
            $this->db->where('created_at >=', date_db_format($this->input->post('tgl_faktur')));
        }
        if($this->input->post('ke_tgl') && $this->input->post('ke_tgl')!="nothing")
        {
            $this->db->where('created_at <=', date_db_format($this->input->post('ke_tgl')));
        }
        
        $this->db->select('
            gdt.*,
            g.nama as nama_gudang,
            gd.nama as nama_divisi,
            p.nama as nama_produk
        ')
        ->from('gudang_divisi_transaksi gdt')
        ->join('gudang_divisi gd', 'gd.id=gdt.id_divisi')
        ->join('produk p', 'p.id=gdt.id_produk')
        ->join('gudang g', 'g.id=gd.id_gudang');
        
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




















