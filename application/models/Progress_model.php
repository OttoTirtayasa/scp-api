<? 
  // model users
class Progress_model extends CI_Model {

	public function get_progress($paket_id = null){
		if($paket_id == null){
			return $this->db->get('sc_progress')->result_array();
		}else{
			return $this->db->get_where('sc_progress', ['pa_id' => $paket_id] )->result_array();
		}
	}

	public function get_fisik($paket_id = null){
		if($paket_id == null){
			return $this->db->get('sc_progress')->result_array();
		}else{
			return $this->db->get_where('sc_progress', ['pa_id' => $paket_id, 'pr_jenis' => 'Fisik'] )->result_array();
		}
	}

	public function get_keuangan($paket_id = null){
		if($paket_id == null){
			return $this->db->get('sc_progress')->result_array();
		}else{
			return $this->db->get_where('sc_progress', ['pa_id' => $paket_id, 'pr_jenis' => 'Keuangan'] )->result_array();
		}
	}

	public function delete_progress($pr_id){
		return $this->db->delete('sc_progress', ['pr_id' => $pr_id] )->result_array();
	}



}

?>