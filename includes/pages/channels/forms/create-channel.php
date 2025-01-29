// File: includes/pages/channels/forms/create-channel.php

<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?php echo isset($channel) ? 'Edit Channel' : 'Create New Channel'; ?></h4>
    </div>
    <div class="card-body">
        <form id="channelForm" action="/api/channels/<?php echo isset($channel) ? 'update' : 'create'; ?>" 
              method="POST" enctype="multipart/form-data">
            
            <?php if (isset($channel)): ?>
                <input type="hidden" name="id" value="<?php echo $channel['id']; ?>">
            <?php endif; ?>

            <!-- Basic Information -->
            <div class="mb-4">
                <h5>Basic Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Channel Name</label>
                        <input type="text" class="form-control" name="name" required
                               value="<?php echo isset($channel) ? htmlspecialchars($channel['name']) : ''; ?>"
                               placeholder="Enter channel name">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Template Type</label>
                        <select class="form-select" name="template_type" required>
                            <option value="">Select template type</option>
                            <?php foreach ($templateTypes as $type): ?>
                                <option value="<?php echo $type; ?>" 
                                    <?php echo isset($channel) && $channel['template_type'] === $type ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" 
                                placeholder="Enter channel description"><?php echo isset($channel) ? htmlspecialchars($channel['description']) : ''; ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <select class="form-select" name="department">
                            <option value="">Select department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo htmlspecialchars($dept); ?>"
                                    <?php echo isset($channel) && $channel['department'] === $dept ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dept); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Access Type</label>
                        <select class="form-select" name="access_type" required>
                            <option value="public" <?php echo isset($channel) && $channel['access_type'] === 'public' ? 'selected' : ''; ?>>
                                Public
                            </option>
                            <option value="restricted" <?php echo isset($channel) && $channel['access_type'] === 'restricted' ? 'selected' : ''; ?>>
                                Restricted
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Preview Image -->
            <div class="mb-4">
                <h5>Preview Image</h5>
                <div class="preview-upload">
                    <div class="preview-image">
                        <?php if (isset($channel) && $channel['preview_image']): ?>
                            <img src="<?php echo ROOT_URL; ?>assets/images/channels/<?php echo htmlspecialchars($channel['preview_image']); ?>" 
                                 alt="Preview" class="img-fluid">
                        <?php endif; ?>
                    </div>
                    <div class="upload-controls">
                        <input type="file" class="form-control" name="preview_image" 
                               accept="image/*" <?php echo isset($channel) ? '' : 'required'; ?>>
                        <small class="text-muted">Recommended size: 1200x630px, Max size: 2MB</small>
                    </div>
                </div>
            </div>

            <!-- Display Options -->
            <div class="mb-4 template-options">
                <h5>Display Options</h5>
                <div class="display-options-container">
                    <!-- Will be populated by JavaScript based on template type -->
                </div>
            </div>

            <!-- Metadata Schema -->
            <div class="mb-4">
                <h5>Content Metadata</h5>
                <div class="metadata-fields" data-fields-container>
                    <?php 
                    if (isset($channel) && $channel['metadata_schema']) {
                        $schema = json_decode($channel['metadata_schema'], true);
                        foreach ($schema['fields'] as $index => $field): 
                    ?>
                        <div class="metadata-field-row">
                            <div class="row g-3 mb-2">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" 
                                           name="metadata_fields[<?php echo $index; ?>][name]"
                                           value="<?php echo htmlspecialchars($field['name']); ?>"
                                           placeholder="Field name">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="metadata_fields[<?php echo $index; ?>][type]">
                                        <option value="text" <?php echo $field['type'] === 'text' ? 'selected' : ''; ?>>Text</option>
                                        <option value="number" <?php echo $field['type'] === 'number' ? 'selected' : ''; ?>>Number</option>
                                        <option value="date" <?php echo $field['type'] === 'date' ? 'selected' : ''; ?>>Date</option>
                                        <option value="select" <?php echo $field['type'] === 'select' ? 'selected' : ''; ?>>Select</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" 
                                           name="metadata_fields[<?php echo $index; ?>][options]"
                                           value="<?php echo isset($field['options']) ? implode(',', $field['options']) : ''; ?>"
                                           placeholder="Options (comma-separated)">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-field">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    } 
                    ?>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addMetadataField">
                    <i class="bi bi-plus"></i> Add Field
                </button>
            </div>

            <!-- Submit Buttons -->
            <div class="text-end">
                <button type="button" class="btn btn-light" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <?php echo isset($channel) ? 'Update Channel' : 'Create Channel'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Template Options Templates -->
<template id="galleryOptionsTemplate">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Layout Style</label>
            <select class="form-select" name="display_options[layout]">
                <option value="grid">Grid</option>
                <option value="masonry">Masonry</option>
                <option value="carousel">Carousel</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Thumbnail Size</label>
            <select class="form-select" name="display_options[thumbnail_size]">
                <option value="small">Small</option>
                <option value="medium">Medium</option>
                <option value="large">Large</option>
            </select>
        </div>
    </div>
</template>

<template id="documentOptionsTemplate">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Preview Mode</label>
            <select class="form-select" name="display_options[preview_mode]">
                <option value="embedded">Embedded</option>
                <option value="download">Download Only</option>
            </select>
        </div>
        <div class="col-md-6">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" 
                       name="display_options[show_download]" checked>
                <label class="form-check-label">Enable Downloads</label>
            </div>
        </div>
    </div>
</template>

<?php
// Include form handling JavaScript
include_once ROOT_PATH . '/assets/js/pages/channels/forms/channel-form.js';
?>
