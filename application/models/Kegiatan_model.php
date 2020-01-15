<? 
  // model users
  class Kegiatan_model extends CI_Model {
    
    public function get_kegiatan($kegiatan_id = null){
      if($kegiatan_id == null){
        return $this->db->get_where('sc_kegiatan', ['status' => '0'])->result_array();
      }else{
        return $this->db->get_where('sc_kegiatan', ['ke_id' => $kegiatan_id])->result_array();
      }
    }

    public function get_kegiatan_dinas($dinas_id){
      return $this->db->get_where('sc_kegiatan', ['dinas_id' => $dinas_id] )->result_array();
    }

    public function get_kegiatan_bidang($bi_id){
      return $this->db->get_where('sc_kegiatan', ['bi_id' => $bi_id] )->result_array();
    }

    public function get_kegiatan_status($status){
      return $this->db->get_where('sc_kegiatan', ['status' => $status] )->result_array();
    }

    public function get_kegiatan_tahun($tahun){
      return $this->db->get_where('sc_kegiatan', ['tahun' => $tahun] )->result_array();
    }

    public function get_kegiatan_user($user_id){
      return $this->db->get_where('sc_kegiatan', ['user_id' => $user_id] )->result_array();
    }

    public function get_kegiatan_userdinas($user_id, $dinas_id){
      return $this->db->get_where('sc_kegiatan', ['user_id' => $user_id, 'dinas_id' => $dinas_id] )->result_array();
    }

    public function get_kegiatan_bidangdinas($bi_id, $dinas_id){
      return $this->db->get_where('sc_kegiatan', ['bi_id' => $bi_id, 'dinas_id' => $dinas_id] )->result_array();
    }

  }

?>