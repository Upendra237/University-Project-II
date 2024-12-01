// Global variable to hold projects data
let projectsData = [];

// Initialize project functionality
function initializeProjects(data) {
    projectsData = data;
    setupModalHandlers();
    setupImageViewer();
}

function setupModalHandlers() {
    const projectModal = document.getElementById('projectViewModal');
    if (projectModal) {
        projectModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const projectId = parseInt(button.getAttribute('data-project-id'));
            
            const project = projectsData.find(p => p.id === projectId);
            if (project) {
                updateModalContent(project);
            } else {
                projectModal.querySelector('.modal-body').innerHTML = `
                    <div class="alert alert-danger">
                        Project details not found.
                    </div>`;
            }
        });
    }
}

function setupImageViewer() {
    const imageViewerModal = document.getElementById('imageViewerModal');
    if (imageViewerModal) {
        imageViewerModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const imageSrc = button.getAttribute('data-image-src');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
        });
    }
}

function updateModalContent(project) {
    // Update header
    updateHeader(project);

    // Update overview tab
    updateOverviewTab(project);

    // Update technical details tab
    updateTechnicalTab(project);

    // Update team tab
    updateTeamTab(project);

    // Update media tab
    updateMediaTab(project);

    // Update timeline tab
    updateTimelineTab(project);

    // Update footer stats
    updateFooterStats(project);
}

function updateHeader(project) {
    // Header background and title
    const headerBg = document.querySelector('.project-header-bg');
    headerBg.style.backgroundImage = `url(assets/images/projects/${project.preview_image})`;
    
    document.getElementById('projectViewModalLabel').textContent = project.title;
    document.querySelector('.project-type').textContent = project.project_type;
    
    // Project tags
    const tagsContainer = document.querySelector('.project-tags');
    if (project.technologies_used) {
        tagsContainer.innerHTML = project.technologies_used
            .map(tech => `<span class="badge bg-light text-dark">${tech}</span>`)
            .join('');
    }
}

function updateOverviewTab(project) {
    // Project description
    document.querySelector('.project-description').innerHTML = `
        <p class="lead">${project.description}</p>
        ${project.full_description || ''}
    `;
    
    // Key features
    const keyFeatures = document.querySelector('.key-features');
    if (project.key_features) {
        keyFeatures.innerHTML = `
            <ul class="list-unstyled">
                ${project.key_features.map(feature => `
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        ${feature}
                    </li>
                `).join('')}
            </ul>
        `;
    }
    
    // Project outcomes
    const outcomes = document.querySelector('.project-outcomes');
    if (project.project_outcomes) {
        outcomes.innerHTML = `
            <div class="row g-3">
                ${Object.entries(project.project_outcomes).map(([key, value]) => `
                    <div class="col-md-6">
                        <div class="stat-card">
                            <div class="stat-value">${value}</div>
                            <div class="stat-label">${key.replace(/_/g, ' ').toUpperCase()}</div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    // Challenges and solutions
    const challengesSection = document.querySelector('.challenges-solutions');
    if (project.challenges_faced) {
        challengesSection.innerHTML = project.challenges_faced.map(challenge => `
            <div class="challenge-card">
                <h6 class="mb-2"><i class="bi bi-exclamation-triangle me-2"></i>${challenge}</h6>
                <div class="solution">
                    <small class="text-muted">Solution provided...</small>
                </div>
            </div>
        `).join('');
    }
    
    // Project metadata
    const metaList = document.querySelector('.project-meta');
    metaList.innerHTML = `
        <li class="mb-2">
            <i class="bi bi-building me-2"></i>
            <strong>Department:</strong> ${project.department}
        </li>
        <li class="mb-2">
            <i class="bi bi-mortarboard me-2"></i>
            <strong>Semester:</strong> ${project.semester}
        </li>
        <li class="mb-2">
            <i class="bi bi-calendar me-2"></i>
            <strong>Year:</strong> ${project.year}
        </li>
        <li class="mb-2">
            <i class="bi bi-person me-2"></i>
            <strong>Supervisor:</strong> ${project.supervisor}
        </li>
    `;
    
    // Awards and Recognition
    const awardsSection = document.querySelector('.awards-list');
    if (project.awards_recognition) {
        awardsSection.innerHTML = project.awards_recognition.map(award => `
            <div class="award-item">
                <i class="bi bi-trophy-fill text-warning me-2"></i>
                ${award}
            </div>
        `).join('');
    }
    
    // Project actions
    const actionsContainer = document.querySelector('.project-actions');
    actionsContainer.innerHTML = `
        <div class="d-grid gap-2 mb-4">
            ${project.github_url ? `
                <a href="${project.github_url}" class="btn btn-dark" target="_blank">
                    <i class="bi bi-github me-2"></i>View Source Code
                </a>
            ` : ''}
            ${project.demo_url ? `
                <a href="${project.demo_url}" class="btn btn-primary" target="_blank">
                    <i class="bi bi-play-circle me-2"></i>Live Demo
                </a>
            ` : ''}
            ${project.report_url ? `
                <a href="${project.report_url}" class="btn btn-success" target="_blank">
                    <i class="bi bi-file-pdf me-2"></i>Download Report
                </a>
            ` : ''}
        </div>
    `;
}

function updateTechnicalTab(project) {
    // Tech stack
    const techStack = document.querySelector('.tech-stack');
    if (project.implementation_details) {
        const { hardware, software } = project.implementation_details;
        techStack.innerHTML = `
            <div class="mb-4">
                <h6 class="text-muted mb-3">Hardware Components</h6>
                <div>
                    ${hardware.map(item => `
                        <span class="tech-pill">
                            <i class="bi bi-cpu me-1"></i>${item}
                        </span>
                    `).join('')}
                </div>
            </div>
            <div>
                <h6 class="text-muted mb-3">Software Stack</h6>
                <div>
                    ${software.map(item => `
                        <span class="tech-pill">
                            <i class="bi bi-code-slash me-1"></i>${item}
                        </span>
                    `).join('')}
                </div>
            </div>
        `;
    }
    // Architecture section
    const architecture = document.querySelector('.architecture');
    if (project.architecture_details && project.architecture_details.diagram_url) {
        architecture.innerHTML = `
            <img src="assets/images/projects/${project.architecture_details.diagram_url}" 
                 class="img-fluid" alt="Architecture Diagram">
        `;
    } else {
        architecture.innerHTML = `
            <div class="alert alert-info">
                No architecture diagram available for this project.
            </div>
        `;
    }
}

function updateTeamTab(project) {
    const teamSection = document.querySelector('.team-members');
    if (project.team_roles) {
        teamSection.innerHTML = `
            <div class="row g-4">
                ${Object.entries(project.team_roles).map(([name, details]) => `
                    <div class="col-md-4">
                        <div class="card team-member-card">
                            <div class="card-body text-center">
                                <img src="assets/images/users/780339.jpg" 
                                     class="team-member-avatar" alt="${name}">
                                <h5 class="card-title mb-1">${name}</h5>
                                <p class="text-muted mb-3">${details.role}</p>
                                <div class="responsibilities">
                                    ${details.responsibilities.map(resp => `
                                        <div class="mb-1">
                                            <small><i class="bi bi-check2 text-success me-1"></i>${resp}</small>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }
}

function updateMediaTab(project) {
    // Project gallery
    const gallerySection = document.querySelector('.project-gallery');
    if (project.media_gallery && project.media_gallery.images) {
        gallerySection.innerHTML = `
            <div class="row g-4">
                ${project.media_gallery.images.map(image => `
                    <div class="col-md-4">
                        <div class="gallery-item"
                             data-bs-toggle="modal"
                             data-bs-target="#imageViewerModal"
                             data-image-src="assets/images/projects/gallery/${image.url}">
                            <img src="assets/images/projects/gallery/${image.url}" 
                                 class="img-fluid rounded" 
                                 alt="${image.caption}">
                            <div class="image-caption mt-2">
                                <small class="text-muted">${image.caption}</small>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    // Project Videos
    const videosSection = document.querySelector('.project-videos');
    if (project.media_gallery?.videos) {
        videosSection.innerHTML = `
            <div class="row g-4">
                ${project.media_gallery.videos.map(video => `
                    <div class="col-md-6">
                        <div class="card">
                            <div class="position-relative">
                                <img src="assets/images/projects/thumbnails/${video.thumbnail}" 
                                     class="card-img-top" 
                                     alt="${video.title}">
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    ${video.type === 'file' ? `
                                        <button class="btn btn-light rounded-circle play-button"
                                                data-video-url="${video.url}"
                                                data-video-type="file"
                                                data-bs-toggle="modal"
                                                data-bs-target="#videoPlayerModal">
                                            <i class="bi bi-play-fill fs-4"></i>
                                        </button>
                                    ` : `
                                        <button class="btn btn-light rounded-circle play-button"
                                                data-video-url="${video.url}"
                                                data-video-type="embed"
                                                data-bs-toggle="modal"
                                                data-bs-target="#videoPlayerModal">
                                            <i class="bi bi-play-fill fs-4"></i>
                                        </button>
                                    `}
                                </div>
                                <span class="position-absolute bottom-0 end-0 badge bg-dark m-2">
                                    ${video.duration}
                                </span>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">${video.title}</h6>
                                <p class="card-text small text-muted">${video.description}</p>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;

        // Add event listeners for video playback
        setupVideoPlayback();
    }
}

function updateTimelineTab(project) {
    const timelineSection = document.querySelector('.project-timeline');
    if (project.project_timeline) {
        const { start_date, end_date, milestones } = project.project_timeline;
        timelineSection.innerHTML = `
            <div class="mb-4">
                <div class="d-flex justify-content-between text-muted">
                    <span><i class="bi bi-calendar2-check me-2"></i>Start: ${start_date}</span>
                    <span><i class="bi bi-calendar2-x me-2"></i>End: ${end_date}</span>
                </div>
            </div>
            <div class="timeline">
                ${milestones.map(milestone => `
                    <div class="timeline-item">
                        <div class="timeline-date">${milestone.date}</div>
                        <div class="timeline-content">
                            <h6>${milestone.title}</h6>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }
}

function updateProjectStats(project) {
    const statsSection = document.querySelector('.project-stats');
    if (project.project_stats) {
        statsSection.innerHTML = `
            <div class="row g-3">
                ${Object.entries(project.project_stats).map(([key, value]) => `
                    <div class="col-md-4 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <h3 class="stat-value">${value}</h3>
                                <p class="stat-label">${key.replace(/_/g, ' ').toUpperCase()}</p>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }
}

function updateImplementationDetails(project) {
    const implementationSection = document.querySelector('.implementation-details');
    if (project.implementation_details) {
        const { hardware, software, architecture_details } = project.implementation_details;
        
        implementationSection.innerHTML = `
            <div class="row g-4">
                <!-- Hardware Section -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="bi bi-cpu me-2"></i>Hardware Components
                            </h5>
                            <div class="list-group">
                                ${hardware.map(item => `
                                    <div class="list-group-item">
                                        <h6 class="mb-1">${item.name}</h6>
                                        <p class="mb-1 text-muted">${item.purpose}</p>
                                        <small class="text-muted">${item.specs}</small>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Software Section -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="bi bi-code-slash me-2"></i>Software Stack
                            </h5>
                            <div class="list-group">
                                ${software.map(item => `
                                    <div class="list-group-item">
                                        <h6 class="mb-1">${item.name}</h6>
                                        <p class="mb-1 text-muted">${item.purpose}</p>
                                        <small class="text-muted">Version: ${item.version}</small>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Architecture Section -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="bi bi-diagram-3 me-2"></i>System Architecture
                            </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="assets/images/projects/${architecture_details.diagram_url}" 
                                         class="img-fluid rounded mb-3" 
                                         alt="Architecture Diagram">
                                </div>
                                <div class="col-md-8">
                                    <div class="list-group">
                                        ${architecture_details.components.map(comp => `
                                            <div class="list-group-item">
                                                <h6 class="mb-1">${comp.name}</h6>
                                                <p class="mb-0 text-muted">${comp.description}</p>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}

function updateFooterStats(project) {
    // Update rating
    document.querySelector('.project-rating').textContent = 
        `${project.rating} / 5.0`;
    
    // Update views
    document.querySelector('.project-views').textContent = 
        project.views.toLocaleString();
    
    // Update downloads
    document.querySelector('.project-downloads').textContent = 
        project.downloads.toLocaleString();
}

// Export functions if using modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initializeProjects,
        setupModalHandlers,
        updateModalContent
    };
}

// Add this function to handle video playback
function setupVideoPlayback() {
    const videoModal = document.getElementById('videoPlayerModal');
    const videoContainer = document.getElementById('videoContainer');
    
    document.querySelectorAll('.play-button').forEach(button => {
        button.addEventListener('click', () => {
            const videoUrl = button.dataset.videoUrl;
            const videoType = button.dataset.videoType;
            
            if (videoType === 'file') {
                videoContainer.innerHTML = `
                    <video controls class="w-100">
                        <source src="assets/videos/${videoUrl}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                `;
            } else {
                videoContainer.innerHTML = `
                    <div class="ratio ratio-16x9">
                        <iframe src="${videoUrl}" 
                                allowfullscreen 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    </div>
                `;
            }
        });
    });

    // Clear video when modal is closed
    videoModal.addEventListener('hidden.bs.modal', () => {
        videoContainer.innerHTML = '';
    });
}