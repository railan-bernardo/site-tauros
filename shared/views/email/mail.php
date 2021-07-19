<?php $v->layout("_theme", ["title" => $subject]); ?>


<h1 style="font-size: 1.2em; padding-bottom: 12px; display: block;"><span>Cliente: </span> <?= $first_name; ?></h1>
<h2 style="font-size: 1em; padding-bottom: 8px; display: block;"><span>Assunto: </span> <?= $subject; ?></h2>
<p style="font-size: 1em; padding-bottom: 8px; display: block;"><span>E-mail: </span><?= $email; ?></p>
<p style="font-size: 1em; padding-bottom: 8px; display: block;"><span>Telefone: </span><?= $phone; ?></p>
<p style="font-size: 1em; padding-bottom: 8px; display: block;"><span>Mensagem: </span><?= $msg; ?></p>
