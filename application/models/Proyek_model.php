<?php

class Proyek_model extends CI_Model{

    public function post_proyek($data){
        if ($this->db->insert('scp_proyek', $data)) {
            return true;
        }
    }

    public function get_proyek_by_id($id){
        return $this->db->get_where('scp_proyek', ['id' => $id])->result();
    }

    public function get_proyek_by_owner($id_owner){
        return $this->db->get_where('scp_proyek', ['id_owner' => $id_owner])->result();
    }

    public function get_proyek_by_pengawas($id_pengawas){
        return $this->db->get_where('scp_proyek', ['id_pengawas' => $id_pengawas])->result();
    }

    public function get_proyek_by_kontraktor($id_kontraktor){
        return $this->db->get_where('scp_proyek', ['id_kontraktor' => $id_kontraktor])->result();
    }
}