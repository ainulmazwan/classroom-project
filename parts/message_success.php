<?php if(isset( $_SESSION["success"])) : ?>
    <div class="alert alert-success" role="alert">
        <?php echo $_SESSION["success"];?>
        <?php 
            // clear error message
            unset($_SESSION["success"]);
        ?>
    </div>
<?php endif; ?>