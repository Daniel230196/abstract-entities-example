<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo DEV_HOST .'/public/css/style.css' ?>">
    <title>Layout</title>
</head>
<body class="container">
<?php \App\Views\View::content($this); ?>
</body>
<script type="module" src="<?php echo DEV_HOST . '/public/scripts/script.js';?>"></script>
</html>