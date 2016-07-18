<!DOCTYPE html>
<html lang="<?=Gundi()->config->getParam('core.default_lang_code'); ?>">
<head>

    <!-- Site meta -->
    <meta charset="utf-8">
</head>
<body>
<div class="container">
    <?php
    echo $this->getContent();
    ?>

</div>
</body>
</html>