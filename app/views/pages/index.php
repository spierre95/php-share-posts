<?php require APP_ROOT . '/views/layout/header.php';?>
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <h1 class="display-3"><?php echo $data['title'];?></h1>
        </div>
        <p class="lead">
        <?php echo $data['description'];?>
        </p>
    </div>
<?php require APP_ROOT . '/views/layout/footer.php';?>