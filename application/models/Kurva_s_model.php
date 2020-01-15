<? 
  // model users
class Kurva_s_model extends CI_Model {

	public function get_data($paket_id = null){
		if($paket_id == null){
			return $this->db->get('sc_rencana')->result_array();
		}else{
			return $this->db->get_where('sc_rencana', ['pa_id' => $paket_id] )->result_array();
		}
	}

	public function delete_data($re_id){
		return $this->db->delete('sc_rencana', ['re_id' => $re_id] )->result_array();
	}

	

}

?>