<?php

class TravelGuideController extends Controller
{
    private $travelGuideModel;

    public function __construct()
    {
        $this->travelGuideModel = $this->model('TravelGuide');
    }

    public function index()
    {
        $q = $_GET['q'] ?? '';

        try {

            if (!empty(trim($q))) {

                if (method_exists($this->travelGuideModel, 'search')) {

                    $guides = $this->travelGuideModel->search($q);

                } else {

                    $guides = [];

                }

            } else {

                if (method_exists($this->travelGuideModel, 'published')) {

                    $guides = $this->travelGuideModel->published();

                } else {

                    $guides = $this->travelGuideModel->all();

                }

            }

        } catch (Exception $e) {

            $guides = [];

        }

        $this->view('travelguide/index', [
            'guides' => $guides,
            'q' => $q
        ]);
    }

    public function details($id = null)
    {
        if (!$id) {

            header('Location: ' . BASE_URL . '/travelguide/index');
            exit;

        }

        try {

            $guide = $this->travelGuideModel->find($id);

        } catch (Exception $e) {

            $guide = null;

        }

        if (!$guide) {

            $this->view('errors/404');
            return;

        }

        $this->view('travelguide/view', [
            'guide' => $guide
        ]);
    }
}