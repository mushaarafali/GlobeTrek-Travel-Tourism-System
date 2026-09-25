<?php
class HomeController extends Controller {
    public function index() {
        $packageModel = $this->model('Package');
        $this->view('home/index', ['packages' => array_slice($packageModel->all(), 0, 3)]);
    }

    public function about() {
        $this->view('home/about');
    }

    public function notFound() {
        http_response_code(404);
        $this->view('errors/404');
    }
}
?>
