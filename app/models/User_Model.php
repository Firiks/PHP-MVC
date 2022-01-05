<?php
  class User_Model {
    private $db;

    public function __construct() {
      $this->db = new Database;
    }

    public function registerUser($data) {
      $this->db->query('INSERT INTO users (name, email, password) VALUES(:name, :email, :password)');

      $this->db->bind(':name', $data['name']);
      $this->db->bind(':email', $data['email']);
      $this->db->bind(':password', $data['password']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function loginUser($email, $password) {
      $this->db->query('SELECT * FROM users WHERE email = :email');

      $this->db->bind(':email', $email);

      $row = $this->db->resultset_single_obj();

      $hashed_password = $row->password;
      if(password_verify($password, $hashed_password)){
        return $row;
      } else {
        return false;
      }

    }

    public function findUserByEmail($email) {
      $this->db->query('SELECT * FROM users WHERE email = :email');
      $this->db->bind(':email',$email);

      $row = $this->db->resultset_single_assoc();

      if($this->db->row_count() > 0) {
        return true;
      } else {
        return false;
      }
    }

    public function getUserById($id) {
      $this->db->query('SELECT * FROM users WHERE id = :id');
      $this->db->bind(':id',$id);

      $row = $this->db->resultset_single_obj();

      return $row;
    }

  }