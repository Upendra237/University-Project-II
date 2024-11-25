<div class="d-flex justify-content-between align-items-center mb-4 p-3 border-bottom border-2 shadow-sm bg-light rounded" 
     style="background: linear-gradient(135deg, #007bff 20%, #0056b3 100%);">
    <div>
        <h1 class="text-white fw-bold display-6">Explore KhEC theses </h1>
        <p class="text-light fst-italic mb-0">All theses of Khwopa Engineering College (KhEC)</p>
    </div>
    <div class="d-flex gap-3 align-items-center">
        <span class="badge bg-light text-primary ms-3 rounded-pill p-2 px-3 shadow-sm" 
            style="font-size: 1rem; background-color: #f8f9fa; border: 1px solid #dee2e6;">
            Found <?php echo count($filteredThesis) == 1 ? '1 thesis' : count($filteredThesis) . ' theses'; ?>
        </span>
        <div class="btn-group shadow-sm" role="group" aria-label="Display Toggle">
            <a href="?display=landscape<?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?>" 
               class="btn <?php echo $displayType == 'landscape' ? 'btn-light text-primary' : 'btn-primary text-white'; ?> fw-bold">
                <i class="bi bi-grid-fill"></i> Landscape View
            </a>
            <a href="?display=portrait<?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?>" 
               class="btn <?php echo $displayType == 'portrait' ? 'btn-light text-primary' : 'btn-primary text-white'; ?> fw-bold">
                <i class="bi bi-list"></i> Portrait View
            </a>
        </div>
    </div>
</div>
