<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class model extends CI_Controller {
    public function getData($tabel){
        $dataku = $this->db->get($tabel);
        return $dataku->result_array();
    }
    public function masukan($tabel, $data){
        $dataku = $this->db->insert($tabel, $data);
        return $dataku;
    }
    public function perbarui($tabel, $data, $where){
        $dataku = $this->db->update($tabel, $data, $where);
        return $dataku;
    }
    public function hapus($tabel, $where){
        $dataku = $this->db->delete($tabel, $where);
        return $dataku;
    }
}
?>
