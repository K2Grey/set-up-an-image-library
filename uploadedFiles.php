<?php

$it = new \FilesystemIterator(dirname(__DIR__ . '/uploads/*'));

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && isset($_POST['btn-delete'])) {
    $picture = $_POST['picture'];
    $pictureDir = './uploads/' . $picture;

    if (!file_exists($pictureDir))
        $error = "This file doesn't exist.";
    if (empty($error))
        unlink($pictureDir);
}

?>

<div>
    <a href="/upload.php">Upload form</a>
</div>
<div>
    <?php foreach ($it as $fileInfo): ?>
        <figure>
            <img src="./uploads/<?= $it->getFilename() ?>" style="max-width: 300px" alt="uploaded picture" />
            <figcaption><?= $it->getFilename() ?></figcaption>
        </figure>
        <form method="post" action="/uploadedFiles.php">
            <input type="hidden" name="picture" value="<?= $it->getFilename() ?>" />
            <button type="submit" name="btn-delete">Delete</button>
        </form>
    <?php endforeach; ?>
</div>
