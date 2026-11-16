<?php
// Front Controller: satu-satunya pintu masuk. Semua request masuk ke sini,
// lalu diteruskan ke Router.
require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/core/autoload.php';

(new Router())->dispatch();
