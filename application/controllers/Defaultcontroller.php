<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class DefaultController extends CI_Controller {

        public function index() {
            $this->load->library('ClickerHeroes/clickerheroesapi');

            var_dump($this->clickerheroesapi->getStaticData('salt'));
        }
    }
