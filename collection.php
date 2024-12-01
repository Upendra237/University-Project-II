<?php
include 'includes/header.php';

// Read collections data from JSON file
$collections_json = file_get_contents('assets/data/collections.json');
$collections = json_decode($collections_json, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collections - Digital Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .collections-container {
            padding: 40px 20px;
            background-color: #f8f9fa;
        }

        .collection-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        .collection-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .card-img-container {
            position: relative;
            overflow: hidden;
            padding-top: 66.67%; /* 3:2 Aspect Ratio */
        }

        .card-img-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .collection-card:hover .card-img-container img {
            transform: scale(1.05);
        }

        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: flex-end;
            padding: 20px;
            color: white;
        }

        .collection-card:hover .card-overlay {
            opacity: 1;
        }

        .filter-bar {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .badge {
            font-size: 0.85em;
            padding: 8px 15px;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .collection-info {
            padding: 20px;
        }

        .collection-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .collection-meta {
            font-size: 0.9rem;
            color: #666;
        }

        .view-btn {
            position: absolute;
            bottom: 20px;
            right: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .collection-card:hover .view-btn {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="collections-container">
        <div class="container">
            <!-- Filter Section -->
            <div class="filter-bar">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <select class="form-select" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="thesis">Thesis</option>
                            <option value="research">Research Papers</option>
                            <option value="projects">Projects</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="departmentFilter">
                            <option value="">All Departments</option>
                            <option value="computer">Computer Engineering</option>
                            <option value="civil">Civil Engineering</option>
                            <option value="electronics">Electronics Engineering</option>
                            <option value="architecture">Architecture</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search collections...">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100" id="resetFilters">Reset Filters</button>
                    </div>
                </div>
            </div>

            <!-- Collections Grid -->
            <div class="row" id="collectionsGrid">
                <!-- Thesis Collection -->
                <div class="col-md-4 collection-item" data-type="thesis">
                    <div class="collection-card">
                        <div class="card-img-container">
                            <img src="assets/images/thesis/blockchain.jpg" alt="Thesis Collection">
                            <div class="card-overlay">
                                <div class="view-btn">
                                    <a href="#" class="btn btn-light">View Collection</a>
                                </div>
                            </div>
                        </div>
                        <div class="collection-info">
                            <h3 class="collection-title">Thesis Collection</h3>
                            <div class="collection-meta">
                                <span class="badge bg-primary">Thesis</span>
                                <span class="text-muted">15 items</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Research Papers -->
                <div class="col-md-4 collection-item" data-type="research">
                    <div class="collection-card">
                        <div class="card-img-container">
                            <img src="assets/images/collections/research.jpg" alt="Research Papers">
                            <div class="card-overlay">
                                <div class="view-btn">
                                    <a href="#" class="btn btn-light">View Collection</a>
                                </div>
                            </div>
                        </div>
                        <div class="collection-info">
                            <h3 class="collection-title">Research Papers</h3>
                            <div class="collection-meta">
                                <span class="badge bg-info">Research</span>
                                <span class="text-muted">23 items</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lab Reports -->
                <div class="col-md-4 collection-item" data-type="projects">
                    <div class="collection-card">
                        <div class="card-img-container">
                            <img src="assets/images/collections/lab-report.jpg" alt="Lab Reports">
                            <div class="card-overlay">
                                <div class="view-btn">
                                    <a href="#" class="btn btn-light">View Collection</a>
                                </div>
                            </div>
                        </div>
                        <div class="collection-info">
                            <h3 class="collection-title">Lab Reports</h3>
                            <div class="collection-meta">
                                <span class="badge bg-success">Projects</span>
                                <span class="text-muted">8 items</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add more collection items as needed -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter functionality
        const typeFilter = document.getElementById('typeFilter');
        const departmentFilter = document.getElementById('departmentFilter');
        const searchInput = document.getElementById('searchInput');
        const resetButton = document.getElementById('resetFilters');
        const collections = document.querySelectorAll('.collection-item');

        function filterCollections() {
            const type = typeFilter.value;
            const search = searchInput.value.toLowerCase();

            collections.forEach(collection => {
                const collectionType = collection.dataset.type;
                const title = collection.querySelector('.collection-title').textContent.toLowerCase();
                const matchesType = !type || collectionType === type;
                const matchesSearch = !search || title.includes(search);

                if (matchesType && matchesSearch) {
                    collection.style.display = 'block';
                } else {
                    collection.style.display = 'none';
                }
            });
        }

        typeFilter.addEventListener('change', filterCollections);
        departmentFilter.addEventListener('change', filterCollections);
        searchInput.addEventListener('input', filterCollections);

        resetButton.addEventListener('click', () => {
            typeFilter.value = '';
            departmentFilter.value = '';
            searchInput.value = '';
            collections.forEach(collection => {
                collection.style.display = 'block';
            });
        });
    </script>
</body>
</html>