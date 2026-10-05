<?php
// app/controllers/DosenController.php
// Controller: menghubungkan Model Dosen dengan View.

require_once APP_PATH . '/models/Dosen.php';

class DosenController
{
    private Dosen $model;

    public function __construct()
    {
        $this->model = new Dosen();
    }

    // URL: ?url=dosen/index
    public function index(): void
    {
        $dosen = $this->model->getAll();
        require APP_PATH . '/views/dosen/index.php';
    }
}
