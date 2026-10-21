<?php
// app/Controllers/HomeController.php
class HomeController extends Controller
{
    public function index(): void
    {
        if (!empty($_SESSION['login']) && $_SESSION['login'] === true) {
            $this->redirect('dashboard');
        }
        $this->redirect('login');
    }
}
