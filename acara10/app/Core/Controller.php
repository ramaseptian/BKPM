<?php

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
