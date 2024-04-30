<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_transaksi_fat extends CI_Model
{
    function tabel(){
        $tabel = "fat";
        return $tabel;
    }
    
    // Ambil Data
    public function get_all()
    {
        $this->db->select('
            f.*,
            g.nama as nama_outlet
        ')
        ->from('fat f')
        ->join('gudang g', 'g.id=f.id_entitas')
        ->order_by('id', "DESC");
        return $this->db->get()->result();
    }
    
    // ambil by id
    public function get_by_id($id)
    {
        $this->db->select('
            f.*,
            g.nama as nama_outlet
        ')
        ->from('fat f')
        ->join('gudang g', 'g.id=f.id_entitas')
        ->where('f.id', $id);
        return $this->db->get()->result();
    }
    
    // ambil othe by fat id
    public function get_other_by_fat_id($fat_id)
    {
        $this->db->select('
            fo.*,
        ')
        ->from('fat_other fo')
        ->where('fo.id_fat', $fat_id);
        return $this->db->get()->result();
    }
    
    //get all FAT
    public function get_fat($tanggal, $id_entitas)
    {
        $this->db->select('
            f.*,
            g.nama as nama_outlet,
            SUM(f.omzet_outlet_debet_pc) AS total_omzet_outlet_debet_pc,
            SUM(f.dp_diterima_debet_pc) AS total_dp_diterima_debet_pc,
            SUM(f.dp_diambil_kredit_pc) AS total_dp_diambil_kredit_pc,
            SUM(f.gojek_kredit_pc) AS total_gojek_kredit_pc,
            SUM(f.gojek_debet_o) AS total_gojek_debet_o,
            SUM(f.grab_kredit_pc) AS total_grab_kredit_pc,
            SUM(f.grab_debet_o) AS total_grab_debet_o,
            SUM(f.setor_kredit_pc) AS total_setor_kredit_pc,
            SUM(f.setor_debet_bo) AS total_setor_debet_bo,
            SUM(f.shopeepay_kredit_pc) AS total_shopeepay_kredit_pc,
            SUM(f.shopeepay_debet_bo) AS total_shopeepay_debet_bo,
            SUM(f.shopeefood_kredit_pc) AS total_shopeefood_kredit_pc,
            SUM(f.shopeefood_debet_bo) AS total_shopeefood_debet_bo,
            SUM(f.tf_bsi_kredit_pc) AS total_tf_bsi_kredit_pc,
            SUM(f.tf_bsi_debet_bo) AS total_tf_bsi_debet_bo,
            SUM(f.voucher_karyawan_produksi_kredit_pc) AS total_voucher_karyawan_produksi_kredit_pc,
            SUM(f.diskon_karyawan_produksi_kredit_pc) AS total_diskon_karyawan_produksi_kredit_pc,
            SUM(f.voucher_karyawan_gumik_kredit_pc) AS total_voucher_karyawan_gumik_kredit_pc,
            SUM(f.diskon_karyawan_gumik_kredit_pc) AS total_diskon_karyawan_gumik_kredit_pc,
            SUM(f.voucher_karyawan_kredit_pc) AS total_voucher_karyawan_kredit_pc,
            SUM(f.diskon_karyawan_kredit_pc) AS total_diskon_karyawan_kredit_pc,
            SUM(f.pie_give_away_kredit_pc) AS total_pie_give_away_kredit_pc,
            SUM(f.kurir_bahan_baku_kredit_pc) AS total_kurir_bahan_baku_kredit_pc,
            SUM(f.tebus_point_kredit_pc) AS total_tebus_point_kredit_pc,
            SUM(f.pisang_kredit_pc) AS total_pisang_kredit_pc,
            SUM(f.kopi_kredit_pc) AS total_kopi_kredit_pc,
            SUM(f.air_galon_kredit_pc) AS total_air_galon_kredit_pc
        ')
        ->from('fat f')
        ->join('gudang g', 'g.id=f.id_entitas')
        ->where('f.tanggal', $tanggal)
        ->where('f.id_entitas', $id_entitas);
        return $this->db->get()->result();
    }
    
    // get fat other
    public function get_fat_other($tanggal, $id_entitas)
    {
        // Get all IDs from fat table that match tanggal and id_entitas
        $this->db->select('id')
            ->from('fat')
            ->where('tanggal', $tanggal)
            ->where('id_entitas', $id_entitas);
            
        $query = $this->db->get();
        $ids = $query->result_array();
    
        // Extract IDs from the result array
        $idArray = array_column($ids, 'id');
    
        // If no matching IDs found, return an empty array
        if (empty($idArray)) {
            return array();
        }
    
        // Get all fat_other rows by the extracted IDs
        $this->db->select('*')
            ->from('fat_other')
            ->where_in('id_fat', $idArray);
    
        return $this->db->get()->result();
    }
    
    // get ids
    public function get_fat_ids($tanggal, $id_entitas)
    {
        $this->db->select('id')
            ->from('fat')
            ->where('tanggal', $tanggal)
            ->where('id_entitas', $id_entitas);
        return $this->db->get()->result();
    }

    // Tambah
    public function tambah()
    {
        $data = [
            'tanggal'       => date_db_format($this->input->post('tanggal')),
            'id_karyawan'   => activeId(),
            'nama_karyawan' => activeNama(),
            'id_entitas'    => $this->input->post('id_entitas'),
            'shift'         => $this->input->post('shift'),
        ];
        $columns = [
            'omzet_outlet_debet_pc',
            'dp_diterima_debet_pc',
            'dp_diambil_kredit_pc',
            'gojek_kredit_pc',
            'grab_kredit_pc',
            'setor_kredit_pc',
            'shopeepay_kredit_pc',
            'shopeefood_kredit_pc',
            'tf_bsi_kredit_pc',
            'voucher_karyawan_produksi_kredit_pc',
            'diskon_karyawan_produksi_kredit_pc',
            'voucher_karyawan_gumik_kredit_pc',
            'diskon_karyawan_gumik_kredit_pc',
            'voucher_karyawan_kredit_pc',
            'diskon_karyawan_kredit_pc',
            'pie_give_away_kredit_pc',
            'kurir_bahan_baku_kredit_pc',
            'tebus_point_kredit_pc',
            'pisang_kredit_pc',
            'kopi_kredit_pc',
            'air_galon_kredit_pc',
        ];
        foreach ($columns as $column) {
            $data[$column] = no_thousand_separator($this->input->post($column) ?: '0');
        }
        $data['gojek_debet_o'] = no_thousand_separator($this->input->post('gojek_kredit_pc') ?: '0');
        $data['grab_debet_o'] = no_thousand_separator($this->input->post('grab_kredit_pc') ?: '0');
        $data['setor_debet_bo'] = no_thousand_separator($this->input->post('setor_kredit_pc') ?: '0');
        $data['shopeepay_debet_bo'] = no_thousand_separator($this->input->post('shopeepay_kredit_pc') ?: '0');
        $data['shopeefood_debet_bo'] = no_thousand_separator($this->input->post('shopeefood_kredit_pc') ?: '0');
        $data['tf_bsi_debet_bo'] = no_thousand_separator($this->input->post('tf_bsi_kredit_pc') ?: '0');
        $add_to_db = $this->db->insert($this->tabel(), $data);
        
        if($add_to_db){
            $lastID = $this->db->insert_id();
            
            $is_other = $this->input->post('other');
            $other_nominal = $this->input->post('other_debet_pc');
            
            for ($i = 0; $i < count($is_other); $i++) {
                $other_data = [
                    'id_fat'    => $lastID,
                    'keterangan'=> $is_other[$i],
                    'nominal'   => no_thousand_separator($other_nominal[$i] ?: '0')
                ];
                $this->db->insert('fat_other', $other_data);
            }
        }
    }
    
    // Hapus faktur
    public function hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->tabel());
        
        $this->db->where('id_fat', $id);
        $this->db->delete('fat_other');
    }
    
    // Update 
    public function update_where($param, $value, $data)
    {
        $this->db->where('id', $id_faktur);
        $this->db->update($this->tabel(), $data);
    }
    
    // filter ------------------------------------------------------------------------------------------------------------------------------
    var $table = 'fat';
    var $column_order = array(null, 'tanggal', 'nama_outlet', 'shift'); //set column field database for datatable orderable
    var $column_search = array('nama_outlet'); //set column field database for datatable searchable 
    var $order = array('f.id'=>'DESC'); // default order
 
    private function _get_datatables_query()
    {
        // Custom filters
        $filter_dari_tanggal = $this->input->post('filter_dari_tgl');
        $filter_ke_tanggal = $this->input->post('filter_ke_tgl');
        $filter_entitas = $this->input->post('filter_entitas');
        $filter_karyawan = $this->input->post('filter_karyawan');
    
        if ($filter_dari_tanggal && $filter_dari_tanggal != "nothing") {
            $this->db->where('f.tanggal >= ', $filter_dari_tanggal);
        }
        if ($filter_ke_tanggal && $filter_ke_tanggal != "nothing") {
            $this->db->where('f.tanggal <= ', $filter_ke_tanggal);
        }
        if ($filter_entitas && $filter_entitas != "nothing") {
            $this->db->where('f.id_entitas', $filter_entitas);
        }
        if ($filter_karyawan && $filter_karyawan != "nothing") {
            $this->db->where('f.id_karyawan', $filter_karyawan);
        }
    
        $this->db->select('
            f.*,
            g.nama as nama_outlet
        ')
        ->from('fat f')
        ->join('gudang g', 'g.id=f.id_entitas');
    
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
