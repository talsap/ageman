<?php

namespace App\Model\Entity;

require __DIR__.'/../../../includes/app.php';

use \App\Model\Entity\Locais as EntityLocal;
use \App\Session\Admin\Login;

//VERIFICAR SE O USUÁRIO ESTÁ LOGADO
$loged = Login::isLogged();

//id DO USUÁRIO DA SESSÃO
$id_user = $_SESSION['admin']['usuario']['id'];

//id DO LOCAL
$id = $_POST['id'];

//VAI NO BANCO E PEGA AS ÁREAS REFERENTES AO LOCAL PARA O USUÁRIO LOGADO
$itens = EntityLocal::getAreaItens($id, $id_user);

echo json_encode($itens);

