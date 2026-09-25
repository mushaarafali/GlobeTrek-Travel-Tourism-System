<?php

class PackageController extends Controller {

    public function index() {
        try {
            $filters = [
                'q' => $_GET['q'] ?? '',
                'category' => $_GET['category'] ?? '',
                'max_price' => $_GET['max_price'] ?? ''
            ];

            $packageModel = $this->model('Package');

            $this->view('packages/index', [
                'packages' => $packageModel->all($filters),
                'categories' => $packageModel->categories(),
                'filters' => $filters
            ]);

        } catch (Exception $e) {
            $this->view('packages/index', [
                'packages' => [],
                'categories' => [],
                'filters' => [],
                'error' => 'Unable to load packages. Please check your database connection.'
            ]);
        }
    }

    public function details($id = null) {
        if ($id === null) {
            flash('error', 'Package ID is missing.');
            $this->redirect('package/index');
            return;
        }

        $packageModel = $this->model('Package');
        $package = $packageModel->find($id);

        if (!$package) {
            flash('error', 'Selected package was not found.');
            $this->redirect('package/index');
            return;
        }

        $this->view('packages/details', [
            'package' => $package
        ]);
    }
}