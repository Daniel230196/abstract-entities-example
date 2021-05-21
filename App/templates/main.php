<form method="get" enctype="multipart/form-data" action="<?php echo DEV_HOST ?>">
    <input type="text">
    <input type="submit">
</form>

<?php for($i = 1; $i <= $this->pages; ++$i): ?>
    <a href="<?php echo DEV_HOST . '/entities/?page=' . $i . '&limit=' . $this->limit ?>"> <?php echo $i ; ?></a>
<?php endfor;?>

<?php foreach ($this->data as $datum): ?>
<div>
    <hr>
    <p><?php echo $datum->name ?></p>
    <p><?php echo $datum->description?></p>
    <a href="<?php echo DEV_HOST . '/entities/delete/'. $datum->id;?>">Удалить</a>
</div>
<?php endforeach  ?>

<script>
    const pages = <?php echo (float)$this->pages; ?>;
</script>
