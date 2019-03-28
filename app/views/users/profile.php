<?php require APP_ROOT . '/views/layout/header.php';?>
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h2><?php echo $data['user']->name;?></h2>
                    <div class="image-thumbnail">
                        <?php if(!empty($data['user']->profile_image)) : ?>
                            <img src="<?php echo $data['user']->location ?>" alt="picture of <?php echo $data['user']->name;?> ">
                        <?php else :?>
                            <img src="https://cdn1.iconfinder.com/data/icons/user-pictures/100/unknown-256.png" alt="picture of <?php echo $data['user']->name;?> ">
                        <?php endif ?>
                    </div>
                    <?php if(!empty($data['user']->location)) : ?>
                        <i class="fa fa-map-marker-alt"></i> <?php echo $data['user']->location ?>
                    <?php endif ?>
                    <?php if($_SESSION['user_id'] == $data['user']->id) :?>
                        <a href="<?php echo URL_ROOT?>/users/edit/<?php echo $_SESSION['user_id']; ?>" class="btn btn-dark">Edit</a>
                    <?php endif ?> 
                </div>
                <div class="col-6">
                    <h5>Bio</h5>
                    <p  class="lead"><?php echo $data['user']->description?></p>
                </div>

            </div>
        </div>
    </div>
<?php require APP_ROOT . '/views/layout/footer.php';?>



<!-- move update profile to popup with form update image, location , name and description  -->