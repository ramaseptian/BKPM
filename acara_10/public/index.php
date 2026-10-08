<?php
require __DIR__.'/../app/Core/Controller.php';
require __DIR__.'/../app/Core/Model.php';
require __DIR__.'/../app/Repositories/MahasiswaRepository.php';
require __DIR__.'/../app/Controllers/MahasiswaController.php';
try {
  $c=require __DIR__.'/../config/database.php';
  $pdo=new PDO("mysql:host={$c['host']};dbname={$c['dbname']};charset={$c['charset']}",$c['username'],$c['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
  $controller=new MahasiswaController(new MahasiswaRepository($pdo));
  $action=$_GET['action']??'index'; $id=(int)($_GET['id']??0);
  match($action){'create'=>$controller->create(),'store'=>$controller->store(),'edit'=>$controller->edit($id),'update'=>$controller->update($id),'delete'=>$controller->delete($id),default=>$controller->index()};
} catch(Throwable $e) {
  http_response_code(500); echo '<h2>Terjadi kesalahan</h2><p>Pastikan database acara_10 sudah di-import dan konfigurasi benar.</p><pre>'.htmlspecialchars($e->getMessage()).'</pre>';
}
