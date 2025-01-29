// File: assets/js/pages/channels/modules/video.js

class VideoHandler {
    constructor() {
        this.player = null;
        this.currentVideo = null;
        this.initializePlayer();
        this.setupEventListeners();
    }

    initializePlayer() {
        // Create video player modal if it doesn't exist
        if (!document.getElementById('videoPlayerModal')) {
            const playerTemplate = `
                <div class="modal fade video-player-modal" id="videoPlayerModal" tabindex="-1">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-0">
                                <div class="video-player-wrapper">
                                    <video id="videoPlayer" class="video-js vjs-big-play-centered">
                                        <p class="vjs-no-js">
                                            To view this video please enable JavaScript, or consider upgrading to a browser that
                                            supports HTML5 video
                                        </p>
                                    </video>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <div class="video-info"></div>
                                <div class="video-actions"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', playerTemplate);
        }

        // Initialize Video.js player
        this.player = videojs('videoPlayer', {
            controls: true,
            fluid: true,
            playbackRates: [0.5, 1, 1.25, 1.5, 2],
            plugins: {
                hotkeys: {
                    volumeStep: 0.1,
                    seekStep: 5,
                    enableModifiersForNumbers: false
                }
            }
        });

        // Add quality selector plugin if available
        if (videojs.getPlugin('qualitySelector')) {
            this.player.qualitySelector();
        }
    }

    setupEventListeners() {
        // Video click handler
        document.addEventListener('click', (e) => {
            const videoItem = e.target.closest('.video-item');
            if (videoItem) {
                const playButton = e.target.closest('.play-video');
                if (playButton) {
                    e.preventDefault();
                    this.playVideo(videoItem.dataset);
                }
            }
        });

        // Player events
        this.player.on('play', () => this.onVideoPlay());
        this.player.on('pause', () => this.onVideoPause());
        this.player.on('ended', () => this.onVideoEnd());
        this.player.on('timeupdate', () => this.onTimeUpdate());

        // Modal events
        const modal = document.getElementById('videoPlayerModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', () => this.onModalClose());
        }
    }

    playVideo(videoData) {
        this.currentVideo = videoData;
        const modal = document.getElementById('videoPlayerModal');
        
        // Update modal content
        modal.querySelector('.modal-title').textContent = videoData.title;
        modal.querySelector('.video-info').innerHTML = this.formatVideoInfo(videoData);
        modal.querySelector('.video-actions').innerHTML = this.getVideoActions(videoData);

        // Set up video source
        this.player.src({
            type: 'video/mp4',
            src: videoData.videoUrl,
            label: 'auto'
        });

        // Add quality sources if available
        if (videoData.qualities) {
            const qualities = JSON.parse(videoData.qualities);
            qualities.forEach(quality => {
                this.player.src().push({
                    type: 'video/mp4',
                    src: quality.url,
                    label: quality.label
                });
            });
        }

        // Show modal and play
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        this.player.play();

        // Track view
        this.trackView(videoData.id);
    }

    formatVideoInfo(videoData) {
        const metadata = JSON.parse(videoData.metadata || '{}');
        return `
            <div class="video-metadata">
                ${metadata.description ? `
                    <p class="video-description">${metadata.description}</p>
                ` : ''}
                <div class="metadata-details">
                    ${metadata.presenter ? `
                        <div class="metadata-item">
                            <i class="bi bi-person"></i> ${metadata.presenter}
                        </div>
                    ` : ''}
                    ${metadata.duration ? `
                        <div class="metadata-item">
                            <i class="bi bi-clock"></i> ${this.formatDuration(metadata.duration)}
                        </div>
                    ` : ''}
                    <div class="metadata-item">
                        <i class="bi bi-eye"></i> ${videoData.views} views
                    </div>
                </div>
            </div>
        `;
    }

    getVideoActions(videoData) {
        return `
            <div class="btn-group">
                ${videoData.downloadable ? `
                    <button class="btn btn-primary" onclick="videoHandler.downloadVideo('${videoData.id}')">
                        <i class="bi bi-download"></i> Download
                    </button>
                ` : ''}
                <button class="btn btn-light" onclick="videoHandler.shareVideo('${videoData.id}')">
                    <i class="bi bi-share"></i> Share
                </button>
                ${videoData.hasTranscript ? `
                    <button class="btn btn-light" onclick="videoHandler.showTranscript('${videoData.id}')">
                        <i class="bi bi-file-text"></i> Transcript
                    </button>
                ` : ''}
            </div>
        `;
    }

    formatDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const remainingSeconds = seconds % 60;

        if (hours > 0) {
            return `${hours}:${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
        }
        return `${minutes}:${String(remainingSeconds).padStart(2, '0')}`;
    }

    async downloadVideo(videoId) {
        try {
            const response = await fetch(`/api/channels/video/download/${videoId}`);
            if (!response.ok) throw new Error('Download failed');

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `video-${videoId}.mp4`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            // Track download
            this.trackDownload(videoId);
        } catch (error) {
            console.error('Download error:', error);
            window.channelsManager.showNotification('Failed to download video', 'error');
        }
    }

    shareVideo(videoId) {
        const shareUrl = `${window.location.origin}/video/${videoId}`;
        const title = this.currentVideo.title;

        if (navigator.share) {
            navigator.share({
                title: title,
                url: shareUrl
            }).catch(console.error);
        } else {
            navigator.clipboard.writeText(shareUrl)
                .then(() => window.channelsManager.showNotification('Link copied to clipboard', 'success'))
                .catch(() => window.channelsManager.showNotification('Failed to copy link', 'error'));
        }
    }

    async showTranscript(videoId) {
        try {
            const response = await fetch(`/api/channels/video/transcript/${videoId}`);
            if (!response.ok) throw new Error('Failed to load transcript');

            const data = await response.json();
            
            // Show transcript in modal
            const transcriptModal = new bootstrap.Modal(document.getElementById('transcriptModal'));
            document.getElementById('transcriptContent').innerHTML = data.transcript;
            transcriptModal.show();
        } catch (error) {
            console.error('Transcript error:', error);
            window.channelsManager.showNotification('Failed to load transcript', 'error');
        }
    }

    // Player event handlers
    onVideoPlay() {
        if (this.currentVideo) {
            this.updatePlaybackState('playing');
        }
    }

    onVideoPause() {
        if (this.currentVideo) {
            this.updatePlaybackState('paused');
        }
    }

    onVideoEnd() {
        if (this.currentVideo) {
            this.updatePlaybackState('ended');
            this.trackCompletion(this.currentVideo.id);
        }
    }

    onTimeUpdate() {
        // Track progress for resume functionality
        if (this.currentVideo) {
            const currentTime = this.player.currentTime();
            const duration = this.player.duration();
            const progress = (currentTime / duration) * 100;
            
            // Save progress every 5 seconds
            if (currentTime % 5 < 1) {
                this.saveProgress(this.currentVideo.id, currentTime, progress);
            }
        }
    }

    onModalClose() {
        this.player.pause();
        // Save final progress
        if (this.currentVideo) {
            const currentTime = this.player.currentTime();
            const duration = this.player.duration();
            const progress = (currentTime / duration) * 100;
            this.saveProgress(this.currentVideo.id, currentTime, progress);
        }
    }

    // API calls
    async trackView(videoId) {
        try {
            await fetch(`/api/channels/video/view/${videoId}`, { method: 'POST' });
        } catch (error) {
            console.error('Failed to track view:', error);
        }
    }

    async trackDownload(videoId) {
        try {
            await fetch(`/api/channels/video/download/${videoId}/track`, { method: 'POST' });
        } catch (error) {
            console.error('Failed to track download:', error);
        }
    }

    async trackCompletion(videoId) {
        try {
            await fetch(`/api/channels/video/complete/${videoId}`, { method: 'POST' });
        } catch (error) {
            console.error('Failed to track completion:', error);
        }
    }

    async saveProgress(videoId, currentTime, progress) {
        try {
            await fetch(`/api/channels/video/progress/${videoId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    currentTime,
                    progress
                })
            });
        } catch (error) {
            console.error('Failed to save progress:', error);
        }
    }

    async updatePlaybackState(state) {
        try {
            await fetch(`/api/channels/video/state/${this.currentVideo.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ state })
            });
        } catch (error) {
            console.error('Failed to update playback state:', error);
        }
    }
}

// Initialize video handler
document.addEventListener('DOMContentLoaded', () => {
    window.videoHandler = new VideoHandler();
});
