<?php
// Directory where files will be saved
$uploadDir = "uploads/";

// Create upload directory if not exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Allowed MIME types and max/min sizes
$allowedTypes = [
    'application/pdf',
    'image/png',
    'image/jpeg'
];
$minSize = 20 * 1024;    // 20KB
$maxSize = 500 * 1024;   // 500KB

// Function to validate & move upload file
function handleFileUpload($fieldName, $required = true) {
    global $uploadDir, $allowedTypes, $minSize, $maxSize;

    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        if ($required) {
            die("Error: Required file '$fieldName' was not uploaded.");
        }
        return null;
    }

    $file = $_FILES[$fieldName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Error uploading $fieldName: " . $file['error']);
    }

    if (!in_array($file['type'], $allowedTypes)) {
        die("Error: File type of $fieldName not allowed.");
    }

    if ($file['size'] < $minSize || $file['size'] > $maxSize) {
        die("Error: File size of $fieldName must be between 20KB and 500KB.");
    }

    $safeName = basename($file['name']);
    $targetPath = $uploadDir . uniqid() . "-" . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        die("Error: Could not save uploaded file for $fieldName.");
    }

    return $targetPath;
}

// Process all files
$uploadedFiles = [];
$documents = [
    'aadhar' => true,
    'pan' => true,
    'voter' => false,
    'marksheet_hs' => true,
    'admit_hs' => true,
    'marksheet_hslc' => true,
    'admit_hslc' => true,
    'photo' => true,
    'caste' => true,
    'dob' => false,
    'income' => true,
    'ration' => true,
    'bank' => true
];

foreach ($documents as $doc => $required) {
    $uploadedFiles[$doc] = handleFileUpload($doc, $required);
}

// You can now save form data + $uploadedFiles paths to database or email admin
// For demo, just show success message

echo "<h2>Upload Successful!</h2>";
echo "<p>Thank you, " . htmlspecialchars($_POST['name']) . ". Your files have been uploaded.</p>";
echo "<p><a href='index.html'>Upload more files</a></p>";
?>
