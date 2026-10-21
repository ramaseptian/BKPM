<?php
// app/Core/Controller.php
// BaseController (BKPM Acara 10: Inheritance, Base Controller, Base Model,
// Repository Pattern). Berisi method umum (view, redirect) yang diwariskan
// (inheritance) oleh SELURUH Controller lain -- HomeController,
// AuthController, MahasiswaController, ProdiController, MatakuliahController,
// DosenController -- lewat "extends Controller", sehingga method view() dan
// redirect() hanya ditulis satu kali di sini (DRY - Don't Repeat Yourself).

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }

    protected function redirect(string $path): void
    {
        redirect($path); // helper dari config/app.php
    }
}
