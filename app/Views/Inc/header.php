<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?=\App\Libraries\TokenCSRF::creaToken()?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=URLROOT?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=URLROOT?>/css/style.css">
    <title>Blog Lemon</title>
</head>
<body>
    <?php require_once APPROOT . '/Views/Inc/navbar.php'; ?>