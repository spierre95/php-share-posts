<!-- move update profile to popup with form update image, location , name and description  -->
<?php require APP_ROOT . '/views/layout/header.php';?>
<a href="<?php echo URL_ROOT?>/users/profile/<?php echo $data['user']->id; ?>" class="btn btn-light"><i class="fa fa-chevron-left"></i> Back</a>
    <div class="card card-body bg-light mt-5">
        <h2>Edit Profile</h2>
        <form action="<?php echo URL_ROOT?>/users/edit/<?php echo $data['user']->id; ?>" method="post" enctype="multipart/form-data container">
            <div class="image-thumbnail">
                <?php if(!empty($data['user']->profile_image)) : ?>
                    <img src="<?php echo $data['user']->location ?>" alt="picture of <?php echo $data['user']->name;?> ">
                <?php else :?>
                    <img src="https://cdn1.iconfinder.com/data/icons/user-pictures/100/unknown-256.png" alt="picture of <?php echo $data['user']->name;?> ">
                <?php endif ?>
                <div class="form-group">
                    <input type="file" name="image" class="mb-3 mt-3">
                    <input type="submit" value="Upload Image" class="btn btn-success"/>
                </div>        
            </div>
            <div class="form-group">
                <label for="name"> Name: <sup>*</sup></label>
                <input type="text" name="name" class="form-control form-control-lg <?php echo (!empty($data["name_error"])) ? "is-invalid" : "";?>" value="<?php echo $data['user']->name;?>"/>
                <span class="invalid-feedback"><?php echo $data["name_error"];?></span> 
            </div>
            <div class="form-group">
                <label for="location"> Location:</label>
                <input type="text" name="location" class="form-control form-control-lg <?php echo (!empty($data["location_error"])) ? "is-invalid" : "";?>" value="<?php echo $data['user']->location;?>"/>
                <span class="invalid-feedback"><?php echo $data["location_error"];?></span> 
            </div>
            <div class="form-group">
                <label for="description"> Bio:</label>
                <textarea name="description" class="form-control form-control-lg <?php echo (!empty($data["description_error"])) ? "is-invalid" : "";?>" ><?php echo $data['user']->description;?></textarea>
                <span class="invalid-feedback"><?php echo $data["description_error"];?></span> 
            </div>
        </form>
    </div>
<?php require APP_ROOT . '/views/layout/footer.php';?>