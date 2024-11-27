<!-- viewer.php -->
<?php
if (!isset($_GET['pdf']) || !file_exists($_GET['pdf'])) {
    die('PDF file not found');
}
$pdfPath = $_GET['pdf'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Viewer</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        #loading {
            display: none;
            text-align: center;
            margin: 20px 0;
        }

        #book-container {
            margin: 20px auto;
            display: none;
            width: auto;
        }

        #book {
            background: white;
        }

        .canvas-page {
            width: 100%;
            display: block;
            margin: 0 auto;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="turn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
</head>
<body>

    <div id="loading">
        <h3>Loading PDF... Please wait...</h3>
    </div>

    <div id="book-container">
        <div id="book"></div>
    </div>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

        const loading = document.getElementById('loading');
        const bookContainer = document.getElementById('book-container');
        let currentBook = null;

        async function initPDF() {
            try {
                const pdf = await pdfjsLib.getDocument('<?php echo $pdfPath; ?>').promise;
                console.log('PDF loaded successfully');
                const bookDiv = document.getElementById('book');
                bookDiv.innerHTML = '';

                for (let num = 1; num <= pdf.numPages; num++) {
                    const page = await pdf.getPage(num);
                    const viewport = page.getViewport({ scale: 1.5 });
                    const canvas = document.createElement('canvas');
                    canvas.className = 'canvas-page';
                    
                    const context = canvas.getContext('2d');
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    
                    await page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise;

                    const div = document.createElement('div');
                    div.appendChild(canvas);
                    bookDiv.appendChild(div);
                }
                                const page = await pdf.getPage(1);
                const viewport = page.getViewport({ scale: 1.0 });
                console.log('Page Width:', viewport.width);
                console.log('Page Height:', viewport.height);

                currentBook = $('#book').turn({
                    width: viewport.width*1.5,
                    height: 600,
                    autoCenter: true,
                    gradients: true,
                    acceleration: true
                });

                bookContainer.style.display = 'block';
                loading.style.display = 'none';
            } catch (error) {
                console.error('Error processing PDF:', error);
                alert('Error loading PDF. Please try again.');
                loading.style.display = 'none';
            }
        }

        function prevPage() {
            if (currentBook) {
                currentBook.turn('previous');
            }
        }

        function nextPage() {
            if (currentBook) {
                currentBook.turn('next');
            }
        }

        // Keyboard controls
        document.addEventListener('keydown', (event) => {
            if (currentBook) {
                switch (event.key) {
                    case 'ArrowRight':
                        nextPage();
                        break;
                    case 'ArrowLeft':
                        prevPage();
                        break;
                    case 'Home':
                        currentBook.turn('page', 1);
                        break;
                    case 'End':
                        currentBook.turn('page', currentBook.turn('pages'));
                        break;
                    case 'Escape':
                        window.close();
                        break;
                }
            }
        });

        // Initialize the PDF viewer
        initPDF();
    </script>
</body>
</html>