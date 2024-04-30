<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_transaksi_hutang extends CI_Model
{
    function tabel(){
        $tabel = "transaksi_hutang";
        return $tabel;
    }
    
    //ambil last id
    public function get_last_id()
    {
        return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get($this->tabel())->row();
    }
    
    //ambil total bayar by faktur
    public function get_totalBayar_by_faktur($jenis_transaksi, $no_faktur)
    {
        $this->db->select('
            sum(th.jumlah_bayar) as total_bayar
        ')
        ->from('transaksi_hutang th')
        ->where('th.jenis_transaksi', $jenis_transaksi)
        ->where('th.no_faktur', $no_faktur);
        return $this->db->get()->result();
    }
    
    //get total hutang
    public function get_total_hutang()
    {
        $this->db->select('
            sum(fp.total_pembelian) as total_hutang,
            sum(fp.dibayar) as dibayar
        ')
        ->from('faktur_pembelian fp')
        ->where('fp.status_hutang', 'TERHUTANG');
        return $this->db->get()->result();
    }
    
    //ambil data hutang
    public function get_terhutang()
    {
        $this->db->select('
            count(fp.id) as jumlah_faktur,
            sum(fp.total_pembelian) as total_hutang,
            sum(fp.dibayar) as dibayar,
            s.id as id_supplier,
            s.nama as nama_supplier
        ')
        ->from('faktur_pembelian fp')
        ->join('suplier s', 's.id=fp.id_supplier')
        ->where('fp.status_hutang', 'TERHUTANG')
        ->group_by('fp.id_supplier');
        return $this->db->get()->result();
    }
    
    //ambil data piutang
    public function get_piutang()
    {
        $this->db->select('
            count(fm.id) as jumlah_faktur,
            sum(fm.nilai_mutasi) as total_piutang,
            sum(fm.dibayar) as dibayar,
            g.id as id_ke_gudang,
            g.nama as ke_gudang
        ')
        ->from('faktur_mutasi fm')
        ->join('gudang g', 'g.id=fm.ke_gudang')
        ->where('fm.status_piutang', 'PIUTANG')
        ->group_by('fm.ke_gudang');
        return $this->db->get()->result();
    }
    
    // Tambah pembayaran
    public function tambah($kode="", $deskripsi="")
    {
        $data = [
            'id_vendor'             => $this->input->post('id_vendor'),
            'jenis_transaksi'       => $kode,
            'deskripsi_transaksi'   => $deskripsi,
            'no_faktur'             => $this->input->post('no_faktur'),
            'jumlah_bayar'          => $this->input->post('nominal'),
            'bukti_bayar'           => $this->input->post('bukti'),
            'tgl_bayar'             => date_db_format($this->input->post('tanggal_bayar')),
            'created_by'            => activeId()
        ];
        $this->db->insert($this->tabel(), $data);
    }
    
    // filter ------------------------------------------------------------------------------------------------------------------------------
    var $table = 'faktur_pembelian';
    var $column_order = array(null, 'no_faktur', 'no_invoice', 'tgl_faktur', 'id_supplier'); //set column field database for datatable orderable
    var $column_search = array('tgl_faktur'); //set column field database for datatable searchable 
    var $order = array('fp.id' => 'asc'); // default order
 
    private function _get_datatables_query()
    {
        //add custom filter here
        if($this->input->post('filter_dari_tgl')){
            $this->db->where('fp.tanggal_faktur >=', $this->input->post('filter_dari_tgl'));
        }
        if($this->input->post('filter_ke_tgl')){
            $this->db->where('fp.tanggal_faktur <=', $this->input->post('filter_ke_tgl'));
        }
        if($this->input->post('filter_supplier') && $this->input->post('filter_supplier')!="nothing"){
            $this->db->where('s.id', $this->input->post('filter_supplier'));
        }
        
        $this->db->select('
            count(fp.id) as jumlah_faktur,
            sum(fp.total_pembelian) as total_hutang,
            sum(fp.dibayar) as dibayar,
            s.id as id_supplier,
            s.nama as nama_supplier
        ')
        ->from('faktur_pembelian fp')
        ->join('suplier s', 's.id=fp.id_supplier')
        ->where('fp.status_hutang', 'TERHUTANG')
        ->group_by('fp.id_supplier');

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
    
    // filter ------------------------------------------------------------------------------------------------------------------------------
    var $table_piutang = 'faktur_mutasi';
    var $column_order_piutang = array(null, 'no_faktur', 'no_invoice', 'tgl_faktur', 'id_supplier'); //set column field database for datatable orderable
    var $column_search_piutang = array('tgl_faktur'); //set column field database for datatable searchable 
    var $order_piutang = array('fm.id' => 'DESC'); // default order
 
    private function _get_datatables_query_piutang()
    {
        //add custom filter here
        if($this->input->post('filter_dari_tgl')){
            $this->db->where('fm.tgl_faktur >=', $this->input->post('filter_dari_tgl'));
        }
        if($this->input->post('filter_ke_tgl')){
            $this->db->where('fm.tgl_faktur <=', $this->input->post('filter_ke_tgl'));
        }
        if($this->input->post('filter_outlet') && $this->input->post('filter_outlet')!="nothing"){
            $this->db->where('g.id', $this->input->post('filter_outlet'));
        }
        
        $this->db->select('
            count(fm.id) as jumlah_faktur,
            sum(fm.nilai_mutasi) as total_piutang,
            sum(fm.dibayar) as dibayar,
            g.id as id_ke_gudang,
            g.nama as ke_gudang
        ')
        ->from('faktur_mutasi fm')
        ->join('gudang g', 'g.id=fm.ke_gudang')
        ->where('fm.status_piutang', 'PIUTANG')
        ->group_by('fm.ke_gudang');

        $i = 0;
        foreach ($this->column_search_piutang as $item) // loop column 
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
 
                if(count($this->column_search_piutang) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order_piutang[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order_piutang))
        {
            $order = $this->order_piutang;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    public function get_datatables_piutang()
    {
        $this->_get_datatables_query_piutang();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    
}
