<?php
  class Users extends Controller {
    public function __construct() {
      $this->userModel = $this->model('User_Model');
    }

    public function register() {
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
          'name' => trim($_POST['name']),
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        if(empty($data['email'])) {
          $data['email_err'] = 'Please enter email';
        } else {
          if($this->userModel->findUserByEmail($data['email'])){
            $data['email_err'] ='Email is taken';
          }
        }

        if(empty($data['name'])) { 
          $data['name_err'] = 'Please enter name';
        }

        if(empty($data['password'])) {
          $data['password_err'] = 'Please enter password';
        } else if(strlen($data['password'] < 6)) {
          $data['password_err'] = 'Password lenght is less than 6 characters';
        }

        if(empty($data['confirm_password'])) {
          $data['confirm_password_err'] = 'Please confirm password';
        } else {
          if($data['password'] != $data['confirm_password']) {
            $data['confirm_password_err'] = 'Passwords dont match';
          }
        }

        // No error, register
        if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
          // Hash password
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
          // Register user
          if($this->userModel->registerUser($data)) {
            flash('register_success', 'You are registered and can log in');
            redirect('users/login');
          } else {
            die('Problem with registration');
          }

        } else {
          $this->view('users/register', $data);
        }

      } else {
        // Init data
        $data = [
          'name' => '',
          'email' => '',
          'password' => '',
          'confirm_password' => '',
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        $this->view('users/register', $data);

      }
    }

    public function login(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // Init data
        $data =[
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'email_err' => '',
          'password_err' => '',
        ];

        if(empty($data['email'])){
          $data['email_err'] = 'Pleae enter email';
        }

        if(empty($data['password'])){
          $data['password_err'] = 'Please enter password';
        }

        // Check for user/email
        if($this->userModel->findUserByEmail($data['email'])){
          // User found
        } else {
          // User not found
          $data['email_err'] = 'No user found';
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['password_err'])){
          $loggedInUser = $this->userModel->loginUser($data['email'], $data['password']);

          if($loggedInUser){
            // Create Session
            $this->createUserSession($loggedInUser);
          } else {
            $data['password_err'] = 'Password incorrect';

            $this->view('users/login', $data);
          }
        } else {
          // Load view with errors
          $this->view('users/login', $data);
        }

      } else {
        // Init data
        $data =[
          'email' => '',
          'password' => '',
          'email_err' => '',
          'password_err' => '',
        ];

        // Load view
        $this->view('users/login', $data);
      }
    }

    public function logout() {
      unset($_SESSION['user_id']);
      unset($_SESSION['user_email']);
      unset($_SESSION['user_name']);
      session_destroy();
      redirect('users/login');
    }

    public function createUserSession($loggedInUser) {
      $_SESSION['user_id'] = $loggedInUser->id;
      $_SESSION['user_email'] = $loggedInUser->email;
      $_SESSION['user_name'] = $loggedInUser->name;
      redirect('posts');
    }

  }