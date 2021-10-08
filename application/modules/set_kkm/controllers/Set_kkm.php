<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Set_kkm extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->sespre = $this->config->item('session_name_prefix');

        $this->d['admlevel'] = $this->session->userdata($this->sespre . 'level');
        $this->d['url'] = "set_kkm";
        $this->d['idnya'] = "setkkm";
        $this->d['nama_form'] = "f_setkkm";
        $this->d['kkm'] = $this->db->get('t_kkm')->result_array();

        $get_tasm = $this->db->query("SELECT tahun FROM tahun WHERE aktif = 'Y'")->row_array();
        $this->d['tasm'] = $get_tasm['tahun'];
    }

    public function datatable()
    {
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $this->input->post('search');

        $d_total_row = $this->db->query("SELECT
                                        a.id, b.nama nmguru, c.nama nmkelas, d.nama nmmapel
                                        FROM t_guru_mapel a
                                        INNER JOIN m_guru b ON a.id_guru = b.id
                                        INNER JOIN m_kelas c ON a.id_kelas = c.id
                                        INNER JOIN m_mapel d ON a.id_mapel = d.id
                                        WHERE a.tasm = '" . $this->d['tasm'] . "'
                                        ORDER BY nmguru ASC, nmmapel ASC, nmkelas ASC")->num_rows();

        $q_datanya = $this->db->query("SELECT
                                    a.id, b.nama nmguru, c.nama nmkelas, d.nama nmmapel
                                    FROM t_guru_mapel a
                                    INNER JOIN m_guru b ON a.id_guru = b.id
                                    INNER JOIN m_kelas c ON a.id_kelas = c.id
                                    INNER JOIN m_mapel d ON a.id_mapel = d.id
                                    WHERE a.tasm = '" . $this->d['tasm'] . "' AND 
                                    (b.nama LIKE '%" . $search['value'] . "%' 
                                    OR c.nama LIKE '%" . $search['value'] . "%'
                                    OR d.nama LIKE '%" . $search['value'] . "%')
                                    ORDER BY nmguru ASC, nmmapel ASC, nmkelas ASC
                                    LIMIT " . $start . ", " . $length . "")->result_array();
        $data = array();
        $no = ($start + 1);

        foreach ($q_datanya as $d) {
            $data_ok = array();
            $data_ok[0] = $no++;
            $data_ok[1] = $d['nmguru'];
            $data_ok[2] = $d['nmmapel'] . " - " . $d['nmkelas'];

            $data_ok[3] = '<a href="#" onclick="return hapus(\'' . $d['id'] . '\');" class="btn btn-xs btn-danger"><i class="fad fa-trash"></i> Hapus</a> ';

            $data[] = $data_ok;
        }

        $json_data = array(
            "draw" => $draw,
            "iTotalRecords" => $d_total_row,
            "iTotalDisplayRecords" => $d_total_row,
            "data" => $data
        );
        j($json_data);
        exit;
    }

    public function edit()
    {
        $tujuh = $this->input->post('7');
        $delapan = $this->input->post('8');
        $sembilan = $this->input->post('9');

        $this->db->set('kkm', $tujuh);
        $this->db->where('kelas', 7);
        $this->db->update('t_kkm');
        $this->db->set('kkm', $delapan);
        $this->db->where('kelas', 8);
        $this->db->update('t_kkm');
        $this->db->set('kkm', $sembilan);
        $this->db->where('kelas', 9);
        $this->db->update('t_kkm');

        $this->session->set_flashdata('k', '<div class="alert alert-success">KKM berhasil dirubah</div>');

        redirect($this->d['url']);
    }

    public function index()
    {
        $this->d['p'] = "list";
        $this->load->view("template_utama", $this->d);
    }
}
