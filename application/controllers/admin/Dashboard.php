<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // proteksi halaman
        $this->auth->cek_login();

        // load model
        $this->load->model('Pasien_model');
        $this->load->model('Kriteria_model');
        
    }

    // Halaman utama Dashboard
    public function index()
    {
        $jumlah_pasien = count($this->Pasien_model->getPasien());
        $jumlah_kriteria = count($this->Kriteria_model->getKriteria());

        $data = array(  'title'             =>      'Dashboard | Administrator',
                        'subtitle'          =>      'Dashboard',
                        'jum_pasien'      =>       $jumlah_pasien,
                        'jum_kriteria'      =>       $jumlah_kriteria,
                        'isi'               =>      'admin/dashboard/list' );
        
        $this->load->view('admin/layout/wrapper', $data, FALSE);
    }

}