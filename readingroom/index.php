<?php
session_start();

// Configure upload settings
$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdfFile'])) {
    $file = $_FILES['pdfFile'];
    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    if ($file['type'] === 'application/pdf') {
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo json_encode(['success' => true, 'filepath' => $targetPath]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to upload file']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid file type']);
    }
    exit;
}

// Get list of uploaded PDFs
function getUploadedPDFs() {
    global $uploadDir;
    $pdfs = [];
    if ($handle = opendir($uploadDir)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && strtolower(pathinfo($entry, PATHINFO_EXTENSION)) === 'pdf') {
                $pdfs[] = [
                    'name' => $entry,
                    'path' => $uploadDir . $entry,
                    'date' => date("Y-m-d H:i:s", filemtime($uploadDir . $entry))
                ];
            }
        }
        closedir($handle);
    }
    // Sort by date, newest first
    usort($pdfs, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    return $pdfs;
}

$uploadedPDFs = getUploadedPDFs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and View PDFs</title>
    <style>
        /* Style for the upload page */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .dropzone {
            border: 2px dashed #ccc;
            border-radius: 4px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            background: #f8f8f8;
        }

        .dropzone.dragover {
            background: #e1e1e1;
            border-color: #999;
        }

        .pdf-library {
            margin: 20px 0;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 4px;
        }

        .pdf-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .pdf-item:last-child {
            border-bottom: none;
        }

        .pdf-item button {
            padding: 5px 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .pdf-item button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload and View PDFs</h1>

        <div class="dropzone" id="dropzone">
            <h3>Drop your PDF here or click to select</h3>
            <input type="file" id="file-input" accept=".pdf" style="display: none">
        </div>

        <div class="pdf-library">
            <h2>Your PDF Library</h2>
            <?php if (empty($uploadedPDFs)): ?>
                <p>No PDFs uploaded yet.</p>
            <?php else: ?>
                <?php foreach ($uploadedPDFs as $pdf): ?>
                    <div class="pdf-item">
                        <div>
                            <strong><?php echo htmlspecialchars(basename($pdf['name'])); ?></strong>
                            <br>
                            <small>Uploaded: <?php echo $pdf['date']; ?></small>
                        </div>
                        <button onclick="viewPDF('<?php echo htmlspecialchars($pdf['path']); ?>')">View</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file-input');

        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', async (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            const file = e.dataTransfer.files[0];
            if (file && file.type === 'application/pdf') {
                await uploadAndProcessPDF(file);
            } else {
                alert('Please upload a valid PDF file.');
            }
        });

        fileInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (file && file.type === 'application/pdf') {
                await uploadAndProcessPDF(file);
            } else {
                alert('Please upload a valid PDF file.');
            }
        });

        async function uploadAndProcessPDF(file) {
            const formData = new FormData();
            formData.append('pdfFile', file);

            const response = await fetch('index.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                // Reload the page to show the updated PDF library
                location.reload();
            } else {
                alert('Error uploading file: ' + result.error);
            }
        }

        function viewPDF(pdfPath) {
            // Redirect to viewer page with the selected PDF path as a query parameter
            window.location.href = 'viewer.php?pdf=' + encodeURIComponent(pdfPath);
        }
    </script>
</body>
</html>
