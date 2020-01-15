<? 
  // model penyedia jasa
class Penyedia_jasa_model extends CI_Model {

	public function get_penyedia_jasa(){
		return $this->db->get('sc_kontraktor')->result_array();
	}
}

?>