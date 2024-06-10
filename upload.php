<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/DEMO/uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$response = ['success' => true, 'message' => '', 'errors' => []];

foreach ($_FILES as $key => $files) {
    for ($i = 0; $i < count($files['name']); $i++) {
        $name = $files['name'][$i];
        $fileTmpName = $files['tmp_name'][$i];
        $fileSize = $files['size'][$i];
        $fileError = $files['error'][$i];
        $fileType = $files['type'][$i];

        $fileExt = explode('.', $name);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg', 'jpeg', 'png', 'pdf');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 10000000) { // Allow files up to 10MB
                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    $fileDestination = $uploadDir . $fileNameNew;

                    if (move_uploaded_file($fileTmpName, $fileDestination)) {
                        $response['message'] .= "File $name uploaded successfully! ";
                    } else {
                        $response['success'] = false;
                        $response['errors'][] = "Failed to move uploaded file $name. Temporary file location: $fileTmpName, Destination: $fileDestination";
                    }
                } else {
                    $response['success'] = false;
                    $response['errors'][] = "File $name is too big! Size: $fileSize bytes.";
                }
            } else {
                $response['success'] = false;
                $response['errors'][] = "Error code $fileError for file $name!";
            }
        } else {
            $response['success'] = false;
            $response['errors'][] = "File type $fileActualExt not allowed for file $name.";
        }
    }
}

if (!$response['success']) {
    $response['message'] = implode(' ', $response['errors']);
}

echo json_encode($response);
?>
