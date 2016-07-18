<!DOCTYPE html>
<html class="no-js before-run" lang="en" data-ng-app="GundiCatalog">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Catalog</title>
    <script type="text/javascript">
        Gundi = {
            Setting: {}
        };
        Gundi.Setting['core.path'] = '<?= Gundi()->config->getParam('core.path'); ?>';
    </script>
    <?php

    $this->addStatic([
        'bootstrap.min.css' => 'app/Template/default/css/',
        'bootstrap-extend.min.css' => 'app/Template/default/css/',
        'style.css' => 'app/Template/default/css/',
        'web-icons.min.css' => 'app/Template/default/fonts/web-icons/',
    ], 0);

    $this->addStatic('http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic');

    $this->addStatic([
        'jquery/jquery.js' => 'static/',
        'angular/angular.min.js' => 'static/',
        'angular-ui-router/angular-ui-router.min.js' => 'static/',
        'angular-infinite/ng-infinite-scroll.min.js' => 'static/',
        'angular-ui-bootstrap/ui-bootstrap.min.js' => 'static/',
        'angular-ui-bootstrap/ui-bootstrap-tpls.min.js' => 'static/',
        'https://npmcdn.com/angular-toastr/dist/angular-toastr.tpls.js',
        'https://npmcdn.com/angular-toastr/dist/angular-toastr.css',
        '//cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.9.0/loading-bar.min.css',
        '//cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.9.0/loading-bar.min.js',
        'bootstrap/bootstrap.js' => 'static/',
    ]);

    //angular controllers
    $this->addStatic(
        [
            'Catalog/Static/app.js' => 'app/Module/',
            'Controller/product/products.js' => 'app/Module/Catalog/Static/',
            'Controller/product/add.js' => 'app/Module/Catalog/Static/',
            'Controller/category/categories.js' => 'app/Module/Catalog/Static/',
            'Controller/category/add.js' => 'app/Module/Catalog/Static/'
        ]
    );
    ?>

    <!-- Stylesheets -->
    <?= $this->css(); ?>
</head>
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

<nav class="navbar navbar-default" role="navigation">
    <a class="navbar-brand" href="#">CATALOG</a>
</nav>

<!-- END nav -->
<div class="container">
    <div class="row">
        <div class="col-sm-2">
            <?= $this->block('admin_left_menu') ?>
        </div>

        <div class="col-sm-10">
            <!-- Content -->
            <div data-ui-view class="page"></div>
            <!-- End Content -->
        </div>
    </div>

</div>

<!-- Scripts -->
<?= $this->js(); ?>
</body>
</html>