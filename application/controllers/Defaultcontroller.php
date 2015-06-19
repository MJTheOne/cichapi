<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class DefaultController extends CI_Controller {

        public function index() {
            $this->load->library('ClickerHeroes/ClickerHeroesApi');

            //var_dump($this->clickerheroesapi->getStaticData('salt'));
            $this->load->view('home');
        }
    }
