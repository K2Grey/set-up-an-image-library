<?php

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES) && isset($_POST['btn-send'])) {
    $userFiles = $_FILES['userfiles'];
    $allowed = ['jpeg', 'gif', 'png'];
    foreach ($userFiles['name'] as $key => $userFile_name) {
        $userFile_size = $userFiles['size'][$key];
        $userFile_error = $userFiles['error'][$key];
        if ($userFile_size >= 1048576 || $userFile_error === 1 || $userFile_error === 2)
            $errors[$key] = "[$userFile_name] is too large.";
        elseif ($userFile_error !== 0 && $userFile_error !== 1 && $userFile_error !== 2)
            $errors[$key] = "[$userFile_name] errored with code $userFile_error.";
        else {
            $userFile_tmp = $userFiles['tmp_name'][$key];
            $type = mime_content_type($userFile_tmp);
            $userFile_ext = explode('/', $type)[1];
            $userFile_name_new = uniqid('', true) . '.' . $userFile_ext;
            $userFile_destination = 'uploads/' . $userFile_name_new;
            if (!in_array($userFile_ext, $allowed))
                $errors[$key] = "[$userFile_name] file extension '$userFile_ext' is not allowed.";
            if (empty($errors[$key]))
                move_uploaded_file($userFile_tmp, $userFile_destination);
        }
    }
}

$it = new \FilesystemIterator(dirname(__DIR__ . '/uploads/*'));

?>

<form enctype="multipart/form-data" action="/upload.php" method="post">
    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
    Sélectionnez vos fichers:
    <input name="userfiles[]" type="file" multiple/>
    <input type="submit" name="btn-send" value="Envoyer" />
</form>
<div>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li>
                <?= $error ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<div>
    <a href="/uploadedFiles.php">Pictures in "uploads" directory</a>
</div>
