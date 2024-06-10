<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DEMO/uploads/';

if (isset($_GET['delete'])) {
    $fileToDelete = $uploadDir . basename($_GET['delete']);
    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
    }
    header("Location: admin.php");
    exit();
}

$files = array_diff(scandir($uploadDir), array('.', '..'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Uploaded Files</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <img src="images/logo.png" alt="Omega Logo" class="logo">
            <h1>Uploaded Files</h1>
        </div>
    </header>
    <main>
        <section class="file-list">
            <h2>Uploaded Files</h2>
            <ul>
                <?php foreach ($files as $file): ?>
                    <li>
                        <a href="<?php echo 'uploads/' . $file; ?>" download><?php echo $file; ?></a>
                        <a href="?delete=<?php echo urlencode($file); ?>" onclick="return confirm('Are you sure you want to delete this file?');">Delete</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>
</html>
