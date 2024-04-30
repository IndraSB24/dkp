<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_entitas extends CI_Model
{
    // entitas
    public function hitung_entitas()
    {
        return $this->db->get('entitas')->num_rows();
    }

    // Ambil Data Kategori entitas
    public function get_all($user_id)
    {
        return $this->db->get_where('entitas', ['user_id' => $user_id])->result_array();
    }

    public function data_kategori($user_id)
    {
        return $this->db->get_where('kategori', ['user_id' => $user_id])->result_array();
    }

    // Tambah entitas
    public function tambah_entitas($user_id)
    {
        $jual = $this->input->post('harga_jual');
        $harga_jual = str_replace(".", "", $jual);
        $beli = $this->input->post('harga_beli');
        $harga_beli = str_replace(".", "", $beli);
        $data['kategori'] = $this->db->get_where('kategori', ['id_kategori' => $this->input->post('kategori')])->row_array();
        $kategori_name = $data['kategori']['nama_kategori'];
        $data = [
            'nama_entitas' => htmlspecialchars(ucwords($this->input->post('nama'))),
            'hrg_beli' => $harga_beli,
            'hrg_jual' => $harga_jual,
            'kategori_name' => $kategori_name,
            'user_id' => $user_id,
            'stock' => 0
        ];

        $this->db->insert('entitas', $data);
    }

    public function tambah_stock($user_id)
    {
        $id_entitas = $this->input->post('id_entitas');
        $add_stock = $this->input->post('stock');
        // Tambah Stock
        $data_entitas['entitas'] = $this->db->get_where('entitas', ['id_entitas' => $id_entitas])->row_array();
        $stock_now = $data_entitas['entitas']['stock'];
        $jumlah_stock = $stock_now + $add_stock;
        var_dump($jumlah_stock);

        $data = [
            'stock' => $jumlah_stock
        ];

        $this->db->where('id_entitas', $id_entitas);
        $this->db->update('entitas', $data);

        // Beli Barang
        $hrg_beli = $data_entitas['entitas']['hrg_beli'];
        $entitas_name = $data_entitas['entitas']['nama_entitas'];
        $data_beli = [
            'entitas_name' => $entitas_name,
            'user_id' => $user_id,
            'unit' => $add_stock,
            'total_beli' => ($hrg_beli * $add_stock),
            'tanggal_beli' => date('Y-m-d')
        ];

        $this->db->insert('pembelian', $data_beli);
    }

    // Hapus entitas

    public function hapus_entitas($id_entitas)
    {
        $this->db->where('id_entitas', $id_entitas);
        $this->db->delete('entitas');
    }

    // Update entitas
    public function ambil_entitas($id_entitas)
    {
        $query = "SELECT * FROM entitas WHERE id_entitas = $id_entitas";
        return $this->db->query($query)->row_array();
    }

    public function ambil_kat_entitas($id_entitas, $user_id)
    {
        $data['entitas'] = $this->db->get_where('entitas', ['id_entitas' => $id_entitas])->row_array();
        $kategori_name = $data['entitas']['kategori_name'];
        $query = "SELECT * FROM kategori WHERE user_id = $user_id && nama_kategori != '$kategori_name'";
        return $this->db->query($query)->result_array();
    }


    public function update_entitas($id_entitas)
    {
        $jual = $this->input->post('harga_jual');
        $harga_jual = str_replace(".", "", $jual);
        $beli = $this->input->post('harga_beli');
        $harga_beli = str_replace(".", "", $beli);
        $data = [
            'nama_entitas' => htmlspecialchars(ucwords($this->input->post('nama'))),
            'hrg_beli' => $harga_beli,
            'hrg_jual' => $harga_jual,
            'kategori_name' => $this->input->post('kategori')
        ];

        $this->db->where('id_entitas', $id_entitas);
        $this->db->update('entitas', $data);
    }
    // Bagian Kategori

    // Ambil Data Kategori entitas
    public function get_kategori($user_id)
    {
        return $this->db->get_where('kategori', ['user_id' => $user_id])->result_array();
    }

    // Tambah Kategori entitas
    public function tambah_kategori($user_id)
    {
        $data = [
            'user_id' => $user_id,
            'nama_kategori' => htmlspecialchars(ucwords($this->input->post('nama_kat')))
        ];

        $this->db->insert('kategori', $data);
    }

    // Hapus Kategori entitas

    public function hapus_kategori($id_kategori)
    {
        $this->db->where('id_kategori', $id_kategori);
        $this->db->delete('kategori');
    }

    public function update_kategori($id_kategori)
    {
        $data = [
            'nama_kategori' => htmlspecialchars(ucwords($this->input->post('nama_kat')))
        ];

        $this->db->where('id_kategori', $id_kategori);
        $this->db->update('kategori', $data);
    }
}
