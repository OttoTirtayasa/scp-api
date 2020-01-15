<? 
  // model users
  class Info_model extends CI_Model {
    
    public function get_info(){
       return $this->db->order_by('info_id', 'DESC')->get_where('sc_info', ['target' => 'Mobile'])->result_array();
    }
  }

?>