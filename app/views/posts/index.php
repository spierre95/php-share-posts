<?php require APP_ROOT . '/views/layout/header.php';?>
<?php flash('post_message'); ?>
    <div class="row">
        <div class="col-md-6">
            <h1>Posts</h1>
        </div>
        <div class="col-md-6">
        <!-- TODO: add sweet alert js add give option to redirect to login or register -->
            <a href="<?php echo URL_ROOT?>/posts/add" class="btn btn-primary pull-right">
                <i class="fa fa-pencil"></i> Add Post
            </a>
        </div>
    </div>
    <?php foreach($data['posts'] as $post) : ?>
    <!-- TODO: redo styles -->
        <div class="card card-body mb-3">
            <h4 class="card-title"><?php echo $post->title;?></h4>
            <div class="bg-light p-2 mb-3">
            <!-- TODO: refactor time created at to use moment.js, example 'created 1 hour ago' -->
                Written By <?php echo $post->name; ?> on <?php echo $post->postCreated; ?>
            </div>
            <p class="card-text"><?php echo $post->body; ?></p>
            <a href="<?php echo URL_ROOT;?>/posts/show/<?php echo $post->postId; ?>" class='btn btn-dark'>
                Read More
            </a>
        </div>
    <?php endforeach; ?>
<?php require APP_ROOT . '/views/layout/footer.php';?>