<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
        require_once 'model.php'; ?>
    <div class="container">
        <div>
            <?php
            $controller = new Controller();
            $controller->handleViewRequest();
            ?>
        </div>
        <div>
            <?php
            $controller = new Controller();
            $controller->handleCreateRequest();
            ?>
        </div>
    </div>
</body>

</html>