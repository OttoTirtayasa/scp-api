<? 
  // model users
  class Paket_model extends CI_Model {
    
    public function get_paket($paket_id = null){
       if($paket_id == null){
         return $this->db->get('sc_paket')->result_array();
        }else{
          return $this->db->get_where('sc_paket', ['pa_id' => $paket_id] )->result_array();
        }
    }

    public function delete_paket($paket_id){

    }

    public function get_paket_kegiatan($kegiatan_id){
      return $this->db->get_where('sc_paket', ['ke_id' => $kegiatan_id] )->result_array();
    }
    
    public function get_paket_status($status){
      return $this->db->get_where('sc_paket', ['status' => $status] )->result_array();
    }

    public function get_paket_jenis($jenis){
      return $this->db->get_where('sc_paket', ['jenis' => $jenis] )->result_array();
    }

    public function get_paket_tahun($tahun){
      return $this->db->get_where('sc_paket', ['tahun' => $jenis] )->result_array();
    }

    public function get_paket_kontraktor($pekerja_id){
      return $this->db->get_where('sc_paket', ['pekerja_id' => $pekerja_id] )->result_array();
    }

    public function get_paket_pptkuser($pptk_id){
      return $this->db->order_by("pa_judul", "asc")->get_where('sc_paket', ['pptk_id' => $pptk_id] )->result_array();
    }

  }  

?>