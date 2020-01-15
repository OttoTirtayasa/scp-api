<? 
  // model users
  class Anggaran_model extends CI_Model {
    
    public function get_dinas(){
       return $this->db->get('sc_anggaran')->result_array();
    }
    public function get_anggaran_pptkuser($pptk_id){
      return $this->db->get_where('sc_anggaran', ['pptk_id' => $pptk_id] )->result_array();
    }
  }

?>