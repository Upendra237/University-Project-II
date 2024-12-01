<!-- Enhanced Project View Modal -->
<div class="modal fade" id="projectViewModal" tabindex="-1" aria-labelledby="projectViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header with Project Title and Tags -->
            <div class="modal-header project-modal-header position-relative p-0">
                <!-- Background Image Overlay -->
                <div class="project-header-bg w-100 h-100 position-absolute top-0 start-0"></div>
                
                <div class="container position-relative p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <span class="badge bg-primary mb-2 project-type"></span>
                            <h4 class="modal-title text-white mb-2" id="projectViewModalLabel"></h4>
                            <div class="project-tags"></div>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="modal-navigation bg-white sticky-top shadow-sm">
                <ul class="nav nav-pills nav-fill" id="projectViewTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                            <i class="bi bi-info-circle"></i> Overview
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="technical-tab" data-bs-toggle="tab" data-bs-target="#technical" type="button" role="tab">
                            <i class="bi bi-code-square"></i> Technical Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="team-tab" data-bs-toggle="tab" data-bs-target="#team" type="button" role="tab">
                            <i class="bi bi-people"></i> Team
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab">
                            <i class="bi bi-images"></i> Media
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline" type="button" role="tab">
                            <i class="bi bi-calendar-event"></i> Timeline
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Modal Body with Tab Content -->
            <div class="modal-body p-0">
                <div class="tab-content" id="projectViewTabContent">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active p-4" id="overview" role="tabpanel">
                        <div class="row">
                            <!-- Main Content -->
                            <div class="col-lg-8">
                                <!-- Project Description -->
                                <div class="project-description mb-4"></div>

                                <!-- Key Features -->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">
                                            <i class="bi bi-stars"></i> Key Features
                                        </h5>
                                        <div class="key-features mt-3"></div>
                                    </div>
                                </div>

                                <!-- Project Outcomes -->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">
                                            <i class="bi bi-trophy"></i> Project Outcomes
                                        </h5>
                                        <div class="project-outcomes mt-3"></div>
                                    </div>
                                </div>

                                <!-- Challenges & Solutions -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">
                                            <i class="bi bi-puzzle"></i> Challenges & Solutions
                                        </h5>
                                        <div class="challenges-solutions mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="col-lg-4">
                                <!-- Project Stats -->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">Project Stats</h5>
                                        <div class="project-stats"></div>
                                    </div>
                                </div>

                                <!-- Project Info -->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">Project Details</h5>
                                        <ul class="list-unstyled project-meta mb-0"></ul>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="project-actions"></div>

                                <!-- Awards & Recognition -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">
                                            <i class="bi bi-award"></i> Awards
                                        </h5>
                                        <div class="awards-list"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Details Tab -->
                    <div class="tab-pane fade p-4" id="technical" role="tabpanel">
                        <div class="row">
                            <!-- Tech Stack -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">
                                            <i class="bi bi-layers"></i> Tech Stack
                                        </h5>
                                        <div class="tech-stack mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Architecture -->
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">
                                            <i class="bi bi-diagram-3"></i> Architecture
                                        </h5>
                                        <?php if (!empty($project['architecture_diagram'])): ?>
                                            <img src="assets/images/projects/<?php echo $project['architecture_diagram']; ?>" 
                                                class="img-fluid" alt="Architecture Diagram">
                                        <?php else: ?>
                                            <div class="alert alert-info">
                                                No architecture diagram available for this project.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Implementation Details -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">
                                            <i class="bi bi-code-slash"></i> Implementation Details
                                        </h5>
                                        <div class="implementation-details mt-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Tab -->
                    <div class="tab-pane fade p-4" id="team" role="tabpanel">
                        <div class="team-members"></div>
                    </div>

                    <!-- Media Tab -->
                    <div class="tab-pane fade p-4" id="media" role="tabpanel">
                        <!-- Project Gallery -->
                        <h5 class="text-primary mb-4">
                            <i class="bi bi-images"></i> Project Gallery
                        </h5>
                        <div class="project-gallery mb-5"></div>

                        <!-- Project Videos -->
                        <h5 class="text-primary mb-4">
                            <i class="bi bi-camera-video"></i> Project Videos
                        </h5>
                        <div class="project-videos"></div>
                    </div>

                    <!-- Timeline Tab -->
                    <div class="tab-pane fade p-4" id="timeline" role="tabpanel">
                        <div class="project-timeline"></div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer with Action Buttons -->
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <div class="rating me-3">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <span class="project-rating"></span>
                                </div>
                                <div class="views me-3">
                                    <i class="bi bi-eye"></i>
                                    <span class="project-views"></span>
                                </div>
                                <div class="downloads">
                                    <i class="bi bi-download"></i>
                                    <span class="project-downloads"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Viewer Modal -->
<div class="modal fade" id="imageViewerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <img src="" class="img-fluid" id="modalImage">
            </div>
        </div>
    </div>
</div>
<!-- Video Player Modal -->
<div class="modal fade" id="videoPlayerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Video Player</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="videoContainer" class="bg-dark">
                    <!-- Video will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>