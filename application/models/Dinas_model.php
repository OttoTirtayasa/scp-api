<? 
  // model users
  class Dinas_model extends CI_Model {
    
    public function get_dinas(){
       return $this->db->get('dinas')->result_array();
    }
  }

?>