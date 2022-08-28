<?php

namespace App\Model\Entity;

require __DIR__.'/../../../includes/app.php';

use \App\Model\Entity\Locais as EntityLocal;

$id = $_POST['id'];
$id_user = $_POST['id_user'];

$itens = EntityLocal::getAreaItens($id, $id_user);

echo json_encode($itens);

