<?php
  class Pages extends Controller {
    public function __construct() {
    }

    public function index() {
      if(isLoggedIn()) {
        redirect('posts');
      }

      $data = [
        'title' => 'mvc',
        'description' => 'Simple social network built on opensource PHP framework'
      ];

      $this->view('pages/index', $data);
    }

    public function about() {
      $data = [
        'title' => 'about'
      ];

      $this->view('pages/about', $data);
    }

  }