<?php
  class Post_Model {
    private $db;

    public function __construct() {
      $this->db = new Database;
    }

    public function getPosts() {
      $this->db->query('SELECT *,
                        posts.id as postId,
                        users.id as userId,
                        posts.created_at as postCreated,
                        users.created_at as userCreated
                        FROM posts
                        INNER JOIN users
                        ON posts.user_id = users.id
                        ORDER BY posts.created_at DESC
                        ');

      $results = $this->db->resultset_all_asoc();

      return $results;

    }

    public function addPost($data) {
      $this->db->query('INSERT INTO posts (title, user_id, body) VALUES(:title, :user_id, :body)');

      $this->db->bind(':title', $data['title']);
      $this->db->bind(':user_id', $data['user_id']);
      $this->db->bind(':body', $data['body']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function updatePost($data) {
      $this->db->query('UPDATE posts SET title = :title, body = :body WHERE id = :id ');

      $this->db->bind(':id', $data['id']);
      $this->db->bind(':title', $data['title']);
      $this->db->bind(':body', $data['body']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getPostById($id) {
      $this->db->query('SELECT * FROM posts WHERE id = :id');

      $this->db->bind(':id', $id);
      $row = $this->db->resultset_single_obj();

      return $row;
    }

    public function deletePostById($id) {
      $this->db->query('DELETE FROM posts WHERE id = :id ');

      $this->db->bind(':id',$id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

  }