<? 
  // model users
  class Bidang_model extends CI_Model {
    
    public function get_bidang(){
       return $this->db->get('sc_bidang')->result_array(); 
       $query = "SELECT * FROM users";
       $q_result = $this->db->query($query)->result();
       return q_result;
    }

    public function add_bidang($data){
      $this->db->insert('sc_bidang', $data); // query builder

    }

  }

?>