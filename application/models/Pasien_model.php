<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien_model extends CI_Model {

    //Fungsi Lihat Data Alternatif
    public function getPasien() {
        return $this->db->get('tb_alternatif')->result_array();
    }

    // get kd & nama klp
    public function getKdNama()
    {
        $query = $this->db->query("SELECT kd_alternatif, nama
                FROM tb_alternatif
                ORDER BY id_alternatif ASC
            ");

        return $query->result_array();
    }

    // get kd & nama klp
    public function getEachKdNama($kd_alternatif)
    {
        return $this->db->select('*')->from('tb_alternatif')->where('kd_alternatif', $kd_alternatif)->get()->result_array();
    }

    // Fungsi tambah kelompok ternak
    public function tambahKelpTernak($data, $kd_subkriteria)   
    {
        //Cek apakah ada kelompok ternak dengan Kode sama
        $filter = $this->db->select('*')->from('tb_alternatif')->where('kd_alternatif', $data['kd_alternatif'])->get()->num_rows();
        if ($filter < 1) {
            $insert = $this->db->insert('tb_alternatif', $data);
            if ($insert) {
                // mengambil data kriteria dalam database
                $kriteria = $this->db->select('id_kriteria')->from('tb_kriteria')->get()->result_array();
                // membuat array data evaluasi
                $data_eval = array();
                // menyusun data kriteria, material dan kelompok ternak dalam 1 array
                for ($j=0; $j < count($kriteria) ; $j++) { 
                    $x = array (
                        'kd_alternatif'    =>  $data['kd_alternatif'],
                        'id_kriteria'   =>  $kriteria[$j]['id_kriteria'],
                        'kd_subkriteria'=>  $kd_subkriteria[$j]
                    );
                    $data_eval[] = $x; 
                }

                // memasukkan semua data dalam array ke dalam tb_evaluasi
                $this->db->insert_batch('tb_evaluasi', $data_eval);
                
            }
        } else {
            // set flashdata
            $this->session->set_flashdata('gagal', 'Data Kelompok Ternak gagal ditambahkan.');
            redirect(base_url('admin/pasien'), 'refresh');
        }
        
        
    }

    // fungsi edit kelompok Ternak
    public function editPasien($data, $kd_subkriteria)
    {
        $this->db->where('kd_alternatif', $data['kd_alternatif']);
        $update = $this->db->update('tb_alternatif', $data);
        if ($update) {
            // mengambil data kriteria dalam database
            $kriteria = $this->db->select('id_kriteria')->from('tb_kriteria')->get()->result_array();
            // membuat array data evaluasi
            $data_eval = array();
            // menyusun data kriteria, material dan kelompok ternak dalam 1 array
            for ($j=0; $j < count($kriteria) ; $j++) { 
                $x = array (
                    'kd_alternatif'    =>  $data['kd_alternatif'],
                    'id_kriteria'   =>  $kriteria[$j]['id_kriteria'],
                    'kd_subkriteria'=>  $kd_subkriteria[$j]
                );
                $data_eval[] = $x; 
            }

            // menghapus data suplier sebelum diinput pada table evaluasi
            $this->db->where('kd_alternatif', $data['kd_alternatif']);
            $delete = $this->db->delete('tb_evaluasi');
            // memasukkan semua data dalam array ke dalam tb_evaluasi
            if ($delete) {
                $this->db->insert_batch('tb_evaluasi', $data_eval);
            }
        }
    }

    // fungsi hapus kelp ternak
    public function hapusKlpTernak($kd_alternatif)
    {
        $this->db->delete('tb_alternatif', ['kd_alternatif' => $kd_alternatif]);
    }

}

/* End of file Pasien_model.php */
