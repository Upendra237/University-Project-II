<!-- reader/index.php -->
<?php
$pageTitle = 'Reader';
include '../includes/header.php';
?>

<div class="reader-container">
    <!-- Reader Toolbar -->
    <div class="reader-toolbar bg-dark text-white py-2">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h5 class="mb-0">Machine Learning in IoT Applications</h5>
                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-end gap-2">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-light" id="zoomOut">
                                <i class="bi bi-zoom-out"></i>
                            </button>
                            <button type="button" class="btn btn-outline-light" id="zoomIn">
                                <i class="bi bi-zoom-in"></i>
                            </button>
                        </div>
                        <button class="btn btn-outline-light" id="toggleNotes">
                            <i class="bi bi-journal-text"></i> Notes
                        </button>
                        <button class="btn btn-outline-light" id="toggleHighlight">
                            <i class="bi bi-highlighter"></i> Highlight
                        </button>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-light active">
                                <i class="bi bi-sun"></i>
                            </button>
                            <button type="button" class="btn btn-outline-light">
                                <i class="bi bi-moon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reader Content -->
    <div class="reader-content">
        <div class="row g-0">
            <!-- Book Viewer -->
            <div class="col-md-9">
                <div id="flipbook" class="book-container">
                    <!-- Pages will be loaded here -->
                </div>
            </div>

            <!-- Notes Panel -->
            <div class="col-md-3 notes-panel">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Notes</h5>
                    </div>
                    <div class="card-body">
                        <div class="notes-list">
                            <!-- Sample Note -->
                            <div class="note-item">
                                <div class="note-header">
                                    <span class="page-number">Page 12</span>
                                    <div class="note-actions">
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <p class="note-content">Important point about ML algorithms...</p>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100 mt-3">
                            <i class="bi bi-plus-lg"></i> Add Note
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Turn.js and Custom Reader Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/turn.js/4.1.0/turn.min.js"></script>
<script src="/assets/js/reader.js"></script>

<?php include '../includes/footer.php'; ?>
