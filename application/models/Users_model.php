<?php

  class Users_model extends CI_Model{

    public function get_users(){
      return $this->db->get('scp_user')->result();
    }

    public function get_owner(){
      return $this->db->get_where('scp_user', ['role' => 'owner'])->result();
    }

    public function get_pengawas(){
      return $this->db->get_where('scp_user', ['role' => 'pengawas'])->result();
    }

    public function get_pengawas($id_owner){
      $query = "SELECT * FROM scp_pengawas INNER JOIN scp_user
      ON scp_pengawas.id_user = scp_user.id
      WHERE scp_pengawas.id_owner = ".$id_owner;
      return $this->db->query($query)->result();
    }

    public function get_kontraktor(){
      return $this->db->get_where('scp_user', ['role' => 'kontraktor'])->result();
    }

    public function get_login($username, $password){
          return $this->db->get_where('scp_user', ['username' => $username, 'password' => md5($password)])->result()[0];
      }

      public function post_signup($nama, $username, $password, $alamat, $telepon, $role, $id_owner){
          $data = array(
              'nama' => $nama,
              'username' => $username,
              'password' => md5($password),
              'alamat' => $alamat,
              'telepon' => $telepon,
              'role'=> $role
          );

          if($this->db->insert('scp_user', $data)){
              $hasil=$this->db->get_where('scp_user', ['username' => $username])->result()[0];
              if ($role == 'owner') {
                  $this->db->insert('scp_owner', array('id_user' => $hasil[0]->id));
              }else if ($role == 'kontraktor') {
                  $this->db->insert('scp_kontraktor', array('id_user' => $hasil[0]->id));
              }else if ($role == 'pengawas') {
                  $id=array(
                      'id_user' => $hasil[0]->id,
                      'id_owner'=> $id_owner);
                  $this->db->insert('scp_pengawas',$id );
              }
              return $hasil;
          }else{
              return false;
          }
      }
  }

?>