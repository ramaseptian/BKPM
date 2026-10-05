<?php
// app/Controllers/DosenController.php
class DosenController extends Controller
{
    public function index(): void
    {
        $dosenList = Dosen::all();
        $this->view('dosen/index', compact('dosenList'));
    }
}
