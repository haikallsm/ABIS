<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>ABIS - Aplikasi Desa Digital</title>

    <!-- Custom CSS -->
    <link href="<?php echo ASSETS_URL; ?>/css/style.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo generateCSRFToken(); ?>">

    <!-- Additional CSS -->
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link href="<?php echo ASSETS_URL; ?>/css/<?php echo $css; ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <?php include __DIR__ . '/header.php'; ?>

    <!-- Main Content -->
    <main>
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/footer.php'; ?>

    <!-- JavaScript -->
    <script src="<?php echo ASSETS_URL; ?>/js/app.js"></script>

    <!-- Additional JavaScript -->
    <?php if (isset($extra_js)): ?>
        <?php foreach ($extra_js as $js): ?>
            <script src="<?php echo ASSETS_URL; ?>/js/<?php echo $js; ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Page specific JavaScript -->
    <?php if (isset($page_js)): ?>
        <script>
            <?php echo $page_js; ?>
        </script>
    <?php endif; ?>
</body>
</html>
