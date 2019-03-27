<?php require APP_ROOT . '/views/layout/header.php';?>
<a href="<?php echo URL_ROOT?>/posts" class="btn btn-light"><i class="fa fa-chevron-left"></i> Back</a>
<br>
<div class="mt-3"><?php flash('post_message'); ?></div>
<h1><?php echo $data['post']->title ?></h1>
<div class="bg-secondary text-white p-2 mb-3">
    Written by <?php echo $data['user']->name ?> on <?php echo $data['post']->created_at; ?>
</div>
<p><?php echo $data['post']->body;?></p>
<?php if($_SESSION['user_id'] == $data['user']->id) :?>
    <hr>
    <a href="<?php echo URL_ROOT?>/posts/edit/<?php echo $data['post']->id; ?>" class="btn btn-dark">Edit</a>
    <form action="<?php echo URL_ROOT?>/posts/delete/<?php echo $data['post']->id; ?>" method="post" class="pull-right">
        <input type="submit" value="Delete" class="btn btn-danger">
    </form>
<?php endif ?> 
<?php require APP_ROOT . '/views/layout/footer.php';?>