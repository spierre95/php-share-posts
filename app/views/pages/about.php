<?php require APP_ROOT . '/views/layout/header.php';?>
    <h1><?php echo $data['title'];?></h1>
    <p><?php echo $data['description'];?></p>
    <p>Version: <strong><?php echo APP_VERSION;?></strong></p>
<?php require APP_ROOT . '/views/layout/footer.php';?>