<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_harga_jual extends CI_Model
{
    function tabel(){
        $tabel = "produk_harga_jual";
        return $tabel;
    }
    
    // Ambil Data Semua
    public function get_list_all()
    {
        return $this->db->get('produk_harga_jual')->result_array();
    }
    
    public function get_all()
    {
        $this->db->select('
            phj.*,
            p.kode as kode_produk,
            p.nama as nama_produk,
            kp.nama as kelompok_produk,
            su.nama_satuan as satuan_dasar
        ')
        ->from('produk_harga_jual phj')
        ->join('produk p', 'p.id=phj.id_produk')
        ->join('kelompok_produk kp', 'p.id_kelompok_produk=kp.id')
        ->join('satuan_ukuran su', 'su.id=p.id_satuan_dasar') 
        ->order_by('phj.tahun', 'DESC')
        ->order_by('phj.bulan', 'DESC')
        ->order_by('p.id_kelompok_produk', 'ASC');
        return $this->db->get()->result();
    }
    
    //ambil by bulan
    public function get_by_bulan($bulan)
    {
        $this->db->select('
            phj.*,
            p.nama as nama_produk
        ')
        ->from('produk_harga_jual phj')
        ->join('produk p', 'p.id=phj.id_produk')
        ->where('year(phj.tgl_periode)', date("Y"))
        ->where('month(phj.tgl_periode)', $bulan);
        return $this->db->get()->result();
    }
    
    //ambil by id_produk
    public function get_by_produk($id_produk)
    {
        $this->db->select('
            phj.*
        ')
        ->from('produk_harga_jual phj')
        ->where('phj.id_produk', $id_produk);
        return $this->db->get()->result();
    }
    
    //ambil by id_produk and bulan
    public function get_by_idProduk_bulan($id_produk, $bulan)
    {
        $this->db->select('
            phj.*,
            p.nama as nama_produk
        ')
        ->from('produk_harga_jual phj')
        ->join('produk p', 'p.id=phj.id_produk')
        ->where('phj.id_produk', $id_produk)
        ->where('phj.tahun', date("Y"))
        ->where('phj.bulan', $bulan);
        return $this->db->get()->result();
    }
    
    //ambil untuk penjualan
    public function get_for_mutasi($dari, $ke, $id_produk, $bulan, $tahun)
    {
        $this->db->select('
            phj.*,
            p.nama as nama_produk
        ')
        ->from('produk_harga_jual phj')
        ->join('produk p', 'p.id=phj.id_produk')
        ->where('phj.dari_gudang', $dari)
        ->where('phj.ke_gudang', $ke)
        ->where('phj.id_produk', $id_produk)
        ->where('phj.tahun', $tahun)
        ->where('phj.bulan', $bulan);
        return $this->db->get()->result();
    }
    
    //ambil by id_produk and bulan
    public function get_by_idProduk_bulan_tahun($id_produk, $bulan, $tahun)
    {
        $this->db->select('
            phj.*,
            p.nama as nama_produk
        ')
        ->from('produk_harga_jual phj')
        ->join('produk p', 'p.id=phj.id_produk')
        ->where('phj.id_produk', $id_produk)
        ->where('year(phj.tgl_periode)', $tahun)
        ->where('month(phj.tgl_periode)', $bulan);
        return $this->db->get()->result();
    }
    
    public function get_by_gudang_produk_bulan_tahun($id_gudang, $id_produk, $bulan, $tahun)
    {
        $this->db->select('
            phj.*,
            p.nama as nama_produk
        ')
        ->from('produk_harga_jual phj')
        ->join('produk p', 'p.id=phj.id_produk')
        ->where('phj.id_gudang', $id_gudang)
        ->where('phj.id_produk', $id_produk)
        ->where('phj.tahun', $tahun)
        ->where('phj.bulan', $bulan);
        return $this->db->get()->result();
    }
    
    //ambil tahun
    public function get_tahun()
    {
        $this->db->select('
            year(phj.tgl_periode) as tahun
        ')
        ->from('produk_harga_jual phj')
        ->group_by('year(phj.tgl_periode)');
        return $this->db->get()->result();
    }

    // Tambah
    public function tambah()
    {
        $id_produk  = $this->input->post('id_produk');
        $bulan      = $this->input->post('bulan');
        $tahun      = $this->input->post('tahun');
        $dari       = $this->input->post('dari_entitas');
        $ke         = $this->input->post('ke_entitas');
        $harga_jual = $this->input->post('harga_jual');

        $data = [
            'id_produk'     => $id_produk,
            'dari_gudang'   => $dari,
            'ke_gudang'     => $ke,
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'harga_jual'    => $harga_jual,
            'created_by'    => activeId()
        ];
        $this->db->insert($this->tabel(), $data);
    }
    
    public function add_from_excel($data){
        $this->Model_all->reset_increment('produk_harga_jual');
		$insert = $this->db->insert_batch('produk_harga_jual', $data);
		if($insert){
			return true;
		}
	}
    
    // Hapus
    public function hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->tabel());
    }
    
    // Update
    public function update($user_id="")
    {
        $id_edit= $this->input->post('id_edit');
        
        $data = [
            'harga_jual' => $this->input->post('harga_jual')
        ];
        
        $this->db->where('id', $id_edit);
        $this->db->update($this->tabel(), $data);
    }
    
    // filter ------------------------------------------------------------------------------------------------------------------------------
    var $table = 'produk_harga_jual';
    var $column_order = array(null, 'kode_produk', 'nama_produk', 'satuan_dasar', 'harga_jual', 'bulan'); //set column field database for datatable orderable
    var $column_search = array('nama_produk'); //set column field database for datatable searchable 
    var $order = array('phj.tahun'=>'desc', 'phj.bulan'=>'desc', 'p.nama'=>'asc'); // default order
 
    private function _get_datatables_query()
    {
        // Custom filters
        $filter_tahun = $this->input->post('filter_tahun');
        $filter_bulan = $this->input->post('filter_bulan');
        $filter_dari_entitas = $this->input->post('filter_dari_entitas');
        $filter_ke_entitas = $this->input->post('filter_ke_entitas');
    
        if ($filter_tahun && $filter_tahun != "nothing") {
            $this->db->where('phj.tahun', $filter_tahun);
        }
        if ($filter_bulan && $filter_bulan != "nothing") {
            $this->db->where('phj.bulan', $filter_bulan);
        }
        if ($filter_dari_entitas && $filter_dari_entitas != "nothing") {
            $this->db->where('dari_gudang', $filter_dari_entitas);
        }
        if ($filter_ke_entitas && $filter_ke_entitas != "nothing") {
            $this->db->where('ke_gudang', $filter_ke_entitas);
        }
    
        $this->db->select('
            phj.*, 
            p.kode as kode_produk, 
            p.nama as nama_produk, 
            kp.nama as kelompok_produk, 
            su.nama_satuan as satuan_dasar,
            gd.nama as dari_entitas,
            gk.nama as ke_entitas
        ')
        ->from('produk_harga_jual phj')
        ->join('produk p', 'p.id=phj.id_produk')
        ->join('kelompok_produk kp', 'p.id_kelompok_produk=kp.id')
        ->join('gudang gd', 'phj.dari_gudang=gd.id')
        ->join('gudang gk', 'phj.ke_gudang=gk.id')
        ->join('satuan_ukuran su', 'su.id=p.id_satuan_dasar');
    
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
    
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
    
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    
    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }
    
    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }
    
    public function count_all()
    {
        return $this->db->from($this->table)->count_all_results();
    }

    
}




















