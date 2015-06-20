<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class AjaxController extends CI_Controller {

        public function actionHandle() {
            $post   = $this->input->post();
            $action = $this->input->post('action');

            if(method_exists($this, $action)) {
                $this->{$action}($post);
            }
        }

        private function startIt() {
            echo $this->load->view('formSteps/1_start.php', null, true);
        }

        private function decodeIt($post) {
            $save = $post['save'];
            $this->load->library('ClickerHeroes/ClickerHeroesApi');

            echo json_encode($this->clickerheroesapi->decrypt($save));
        }

        private function encodeIt($post) {
            $json = $post['json'];
            $this->load->library('ClickerHeroes/ClickerHeroesApi');

            var_dump($this->clickerheroesapi->encryptIt($json));
        }

        public function dataSubmit() {
            $data = $this->input->post('test');

            echo json_encode($data);
        }
    }
