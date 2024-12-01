<div class="d-flex justify-content-between align-items-center mb-4 p-3 border-bottom border-2 shadow-sm bg-light rounded" 
     style="background: linear-gradient(135deg, #2c3e50 20%, #3498db 100%);">
    <div>
        <h1 class="text-white fw-bold display-6">Student Projects</h1>
        <p class="text-light fst-italic mb-0">
            Browse through innovative projects from our talented students
            <?php if ($selectedDepartment): ?>
                in <?php echo htmlspecialchars($selectedDepartment); ?>
            <?php endif; ?>
        </p>
    </div>
    <div class="d-flex gap-3 align-items-center">
        <div class="bg-white rounded-pill px-4 py-2 shadow-sm">
            <span class="h5 mb-0 text-primary">
                <?php 
                $projectCount = count($filteredProjects);
                echo $projectCount . ' ' . ($projectCount === 1 ? 'Project' : 'Projects');
                ?>
            </span>
            <?php if ($selectedProjectType): ?>
                <span class="text-muted ms-2">in <?php echo htmlspecialchars($selectedProjectType); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!empty($searchQuery) || !empty($selectedDepartment) || !empty($selectedProjectType)): ?>
    <div class="d-flex flex-wrap gap-2 mb-3">
        <?php if (!empty($searchQuery)): ?>
            <div class="badge bg-primary p-2">
                Search: <?php echo htmlspecialchars($searchQuery); ?>
                <a href="<?php echo remove_query_param('search'); ?>" class="text-white ms-2 text-decoration-none">×</a>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($selectedDepartment)): ?>
            <div class="badge bg-info p-2">
                Department: <?php echo htmlspecialchars($selectedDepartment); ?>
                <a href="<?php echo remove_query_param('department'); ?>" class="text-white ms-2 text-decoration-none">×</a>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($selectedProjectType)): ?>
            <div class="badge bg-success p-2">
                Type: <?php echo htmlspecialchars($selectedProjectType); ?>
                <a href="<?php echo remove_query_param('project_type'); ?>" class="text-white ms-2 text-decoration-none">×</a>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($searchQuery) || !empty($selectedDepartment) || !empty($selectedProjectType)): ?>
            <a href="projects.php" class="btn btn-outline-secondary btn-sm">Clear All Filters</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
function remove_query_param($param) {
    $params = $_GET;
    unset($params[$param]);
    return 'projects.php?' . http_build_query($params);
}
?>