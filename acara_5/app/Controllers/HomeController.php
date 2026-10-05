<?php
namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        echo "<h1>Selamat datang</h1>";
        echo "<p>Ini adalah halaman utama (Home). Diakses melalui routing bersih tanpa .php.</p>";
    }
}
