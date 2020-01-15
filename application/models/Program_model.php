<? 
  // model users
  class Program_model extends CI_Model {
    
    public function get_program(){
       return $this->db->get('program')->result_array();
    }
  }

?>