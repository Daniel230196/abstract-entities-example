<div class="formContainer">
    <form method="get" enctype="multipart/form-data" action="<?php echo DEV_HOST ?>">
        <input id="find" type="text">
        <input id="find-btn" type="submit" class="b-std" value="Найти">
    </form>
    <div class="addWrapper">
    <button class="addEntity b-std">Добавить позицию</button>
    </div>
</div>
<div class="pagination">
<?php for($i = 1; $i <= $this->pages; ++$i): ?>
    <a href="<?php echo DEV_HOST . '/entities/?page=' . $i . '&limit=' . $this->limit ?>"> <?php echo $i ; ?></a>
<?php endfor;?>
</div>
<div class="card-container">
<div class="cards-wrapper">
<?php  foreach ($this->data as $datum): ?>
    <div class="entityCard<?php echo $datum->id ?> card-main">
    <hr>
    <p class="name"><?php  echo $datum->name ?></p>
    <p class="created"><?php echo date_format($datum->created, 'Y-m-d H:i:s'); ?> </p>
    <p><?php echo $datum->description;?></p>

    <button id="<?php echo $datum->id ?>" class="b-close deleteButton">Удалить</button>
</div>
<?php endforeach;?>
</div>
</div>

