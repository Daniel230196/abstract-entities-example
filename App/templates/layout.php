<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Layout</title>
</head>
<body class="container">
<form method="get" enctype="multipart/form-data" action="<?php DEV_HOST ?>">
    <input type="text">
    <input type="submit">
</form>
<?php \App\Views\View::content($this); ?>
</body>

</html>