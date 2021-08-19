<?php if( isset( $_GET['deleted'] ) ) :   ?>
<div class="notice notice-alt is-dismissible notice-success">
    <p>License Deleted</p>
</div>
<?php endif; ?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e( 'License Manager: Licenses', 'lmfwppt' ); ?></h1>

    <a href="<?php echo admin_url( 'admin.php?page=license-manager-wppt-licenses&action=new' ); ?>" class="page-title-action"><?php _e( 'Add New License', 'lmfwppt' ); ?></a>

    <form action="" method="post">
        <?php 
            $table = new LMFWPPT_LicenseListTable();
            $table->prepare_items();
            $table->display();

        ?>
    </form>

</div>

