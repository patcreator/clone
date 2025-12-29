// Global variables
let currentFile = null;
let currentFilter = 'none';
let currentRotation = 0;
let addedText = '';
let cropper = null;

// DOM elements
const fileInput = document.getElementById('fileInput');
const dropArea = document.getElementById('dropArea');
const browseBtn = document.getElementById('browseBtn');
const fileList = document.getElementById('fileList');
const imagePreview = document.getElementById('imagePreview');
const videoPreview = document.getElementById('videoPreview');
const previewPlaceholder = document.getElementById('previewPlaceholder');
const previewContainer = document.getElementById('previewContainer');
const cropBtn = document.getElementById('cropBtn');
const rotateBtn = document.getElementById('rotateBtn');
const filterBtn = document.getElementById('filterBtn');
const filterOptions = document.getElementById('filterOptions');
const resetBtn = document.getElementById('resetBtn');
const sendBtn = document.getElementById('sendBtn');
const cropModal = document.getElementById('cropModal');
const cropImage = document.getElementById('cropImage');
const cancelCrop = document.getElementById('cancelCrop');
const applyCrop = document.getElementById('applyCrop');
const imageCanvas = document.getElementById('imageCanvas');
const ctx = imageCanvas.getContext('2d');

// Event Listeners
browseBtn.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', handleFileSelect);

// Drag and drop events
dropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropArea.style.backgroundColor = '#f0f7ff';
    dropArea.style.borderColor = '#6a11cb';
});

dropArea.addEventListener('dragleave', () => {
    dropArea.style.backgroundColor = 'white';
    dropArea.style.borderColor = '#a78bfa';
});

dropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dropArea.style.backgroundColor = 'white';
    dropArea.style.borderColor = '#a78bfa';
    
    if (e.dataTransfer.files.length) {
        handleFiles(e.dataTransfer.files);
    }
});

// Edit control buttons
cropBtn.addEventListener('click', openCropModal);
rotateBtn.addEventListener('click', rotateImage);
filterBtn.addEventListener('click', toggleFilterOptions);

// Filter option clicks
document.querySelectorAll('.filter-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.filter-option').forEach(opt => {
            opt.classList.remove('active');
        });
        this.classList.add('active');
        currentFilter = this.dataset.filter;
        applyFilterToImage();
    });
});



// Action buttons
resetBtn.addEventListener('click', resetEdits);
sendBtn.addEventListener('click', sendToServer);

// Crop modal buttons
cancelCrop.addEventListener('click', () => {
    cropModal.style.display = 'none';
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});

applyCrop.addEventListener('click', applyCropToImage);

// Handle file selection
function handleFileSelect(e) {
    const files = e.target.files;
    handleFiles(files);
}

function handleFiles(files) {
    // Clear previous file list
    fileList.innerHTML = '';
    
    // Process each file
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Check file type
        if (!file.type.match('image.*') && !file.type.match('video.*')) {
            alert(`File ${file.name} is not an image or video. Please select valid files.`);
            continue;
        }
        
        // Check file size (max 50MB)
        if (file.size > 50 * 1024 * 1024) {
            alert(`File ${file.name} is too large. Maximum size is 50MB.`);
            continue;
        }
        
        // Add to file list
        addFileToList(file);
        
        // If it's the first file, preview it
        if (i === 0) {
            previewFile(file);
            currentFile = file;
            sendBtn.disabled = false;
            enableEditButtons(file.type.match('image.*'));
        }
    }
}

function addFileToList(file) {
    const li = document.createElement('li');
    li.className = 'file-item';
    
    // Determine icon based on file type
    const iconClass = file.type.match('image.*') ? 'fa-file-image' : 'fa-file-video';
    
    // Format file size
    const fileSize = formatFileSize(file.size);
    
    li.innerHTML = `
        <i class="fas ${iconClass} file-icon"></i>
        <span class="file-name">${file.name}</span>
        <span class="file-size">${fileSize}</span>
    `;
    
    // Add click event to preview this file
    li.addEventListener('click', () => {
        previewFile(file);
        currentFile = file;
        enableEditButtons(file.type.match('image.*'));
    });
    
    fileList.appendChild(li);
}

function previewFile(file) {
    // Reset preview state
    previewPlaceholder.style.display = 'none';
    imagePreview.style.display = 'none';
    videoPreview.style.display = 'none';
    resetEdits();
    
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const result = e.target.result;
        
        if (file.type.match('image.*')) {
            // It's an image
            imagePreview.src = result;
            imagePreview.style.display = 'block';
            imagePreview.style.filter = 'none';
            imagePreview.style.transform = 'rotate(0deg)';
            
            // Set canvas dimensions to match image
            imagePreview.onload = function() {
                imageCanvas.width = imagePreview.naturalWidth;
                imageCanvas.height = imagePreview.naturalHeight;
                currentRotation = 0;
                currentFilter = 'none';
                addedText = '';
            };
        } else if (file.type.match('video.*')) {
            // It's a video
            videoPreview.src = result;
            videoPreview.style.display = 'block';
        }
    };
    
    reader.readAsDataURL(file);
}

function enableEditButtons(isImage) {
    // Enable or disable edit buttons based on file type
    cropBtn.disabled = !isImage;
    rotateBtn.disabled = !isImage;
    filterBtn.disabled = !isImage;
    
    // Hide filter options if not image
    if (!isImage) {
        filterOptions.classList.add('hidden');
    }
}

function openCropModal() {
    if (!currentFile || !currentFile.type.match('image.*')) return;
    
    // Set the image for cropping
    cropImage.src = imagePreview.src;
    cropModal.style.display = 'flex';
    
    // Initialize cropper
    setTimeout(() => {
        if (cropper) {
            cropper.destroy();
        }
        
        cropper = new Cropper(cropImage, {
            aspectRatio: NaN, // Free aspect ratio
            viewMode: 1,
            autoCropArea: 0.8,
            responsive: true,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
    }, 100);
}

function applyCropToImage() {
    if (!cropper) return;
    
    // Get cropped canvas
    const croppedCanvas = cropper.getCroppedCanvas();
    
    // Update the preview image
    imagePreview.src = croppedCanvas.toDataURL();
    
    // Close modal and destroy cropper
    cropModal.style.display = 'none';
    cropper.destroy();
    cropper = null;
    
    // Update canvas dimensions
    imageCanvas.width = croppedCanvas.width;
    imageCanvas.height = croppedCanvas.height;
}

function rotateImage() {
    if (!currentFile || !currentFile.type.match('image.*')) return;
    
    currentRotation += 90;
    if (currentRotation >= 360) currentRotation = 0;
    
    imagePreview.style.transform = `rotate(${currentRotation}deg)`;
}

function toggleFilterOptions() {
    filterOptions.classList.toggle('hidden');
}

function applyFilterToImage() {
    if (!currentFile || !currentFile.type.match('image.*')) return;
    
    let filterValue = '';
    
    switch(currentFilter) {
        case 'grayscale':
            filterValue = 'grayscale(100%)';
            break;
        case 'sepia':
            filterValue = 'sepia(100%)';
            break;
        case 'brightness':
            filterValue = 'brightness(1.5)';
            break;
        default:
            filterValue = 'none';
    }
    
    imagePreview.style.filter = filterValue;
}

function applyTextToImage() {
    if (!currentFile || !currentFile.type.match('image.*')) return;
    
    // Clear canvas
    ctx.clearRect(0, 0, imageCanvas.width, imageCanvas.height);
    
    // Draw the image with current filter and rotation
    const tempImg = new Image();
    tempImg.src = imagePreview.src;
    
    tempImg.onload = function() {
        // Save the context state
        ctx.save();
        
        // Translate to center for rotation
        ctx.translate(imageCanvas.width / 2, imageCanvas.height / 2);
        ctx.rotate(currentRotation * Math.PI / 180);
        
        // Apply filter by drawing with filter
        ctx.filter = imagePreview.style.filter;
        
        // Draw image centered
        ctx.drawImage(tempImg, -tempImg.width / 2, -tempImg.height / 2);
        
        // Restore context (removes rotation and filter for text)
        ctx.restore();
        
        // Add text if exists
        if (addedText) {
            ctx.font = 'bold 48px Arial';
            ctx.fillStyle = 'white';
            ctx.strokeStyle = 'black';
            ctx.lineWidth = 3;
            
            // Text positioning (center bottom)
            const textWidth = ctx.measureText(addedText).width;
            const x = (imageCanvas.width - textWidth) / 2;
            const y = imageCanvas.height - 50;
            
            // Draw text with stroke and fill
            ctx.strokeText(addedText, x, y);
            ctx.fillText(addedText, x, y);
        }
        
        // Update preview with canvas content
        imagePreview.src = imageCanvas.toDataURL();
    };
}

function resetEdits() {
    if (!currentFile) return;
    
    // Reset all edits
    currentFilter = 'none';
    currentRotation = 0;
    addedText = '';
    
    // Reset preview image
    if (currentFile.type.match('image.*')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.filter = 'none';
            imagePreview.style.transform = 'rotate(0deg)';
            
            // Reset canvas
            imageCanvas.width = imagePreview.naturalWidth;
            imageCanvas.height = imagePreview.naturalHeight;
            
            // Clear any drawn content
            ctx.clearRect(0, 0, imageCanvas.width, imageCanvas.height);
        };
        reader.readAsDataURL(currentFile);
    }
    
    // Hide filter options
    filterOptions.classList.add('hidden');
    
    // Reset filter option UI
    document.querySelectorAll('.filter-option').forEach(opt => {
        opt.classList.remove('active');
    });
    document.querySelector('.filter-option[data-filter="none"]').classList.add('active');
}

// Main upload function
function sendToServer() {
    if (!currentFile) return;

    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    sendBtn.disabled = true;

    if (currentFile.type.match('image.*')) {
        // 🔴 IMPORTANT: draw everything to canvas FIRST
        renderFinalImageToCanvas(() => {
            imageCanvas.toBlob(blob => {
                const fileToSend = new File([blob], currentFile.name, {
                    type: currentFile.type
                });
                uploadToServer(fileToSend);
            }, currentFile.type);
        });
    } else {
        uploadToServer(currentFile);
    }
}
function renderFinalImageToCanvas(callback) {
    const img = new Image();
    img.src = imagePreview.src;

    img.onload = () => {
        const w = img.naturalWidth;
        const h = img.naturalHeight;

        imageCanvas.width = w;
        imageCanvas.height = h;

        ctx.save();
        ctx.clearRect(0, 0, w, h);

        // Move to center for rotation
        ctx.translate(w / 2, h / 2);
        ctx.rotate(currentRotation * Math.PI / 180);

        // Apply filter
        ctx.filter = imagePreview.style.filter || 'none';

        // Draw image centered
        ctx.drawImage(img, -w / 2, -h / 2, w, h);

        ctx.restore();

        // Draw text LAST (no filter)
        if (addedText) {
            ctx.font = 'bold 48px Arial';
            ctx.fillStyle = 'white';
            ctx.strokeStyle = 'black';
            ctx.lineWidth = 3;

            const textWidth = ctx.measureText(addedText).width;
            const x = (w - textWidth) / 2;
            const y = h - 60;

            ctx.strokeText(addedText, x, y);
            ctx.fillText(addedText, x, y);
        }

        callback();
    };
}



// Actual upload function using your API
function uploadToServer(file) {
    // Get form data values (assuming you have these fields in your form)
    const sender = document.getElementById('sender') ? document.getElementById('sender').value : null;
    const receiver = document.getElementById('receiver') ? document.getElementById('receiver').value : null;
    const is_group = document.querySelector('input[name="is_group"]') ? document.querySelector('input[name="is_group"]').value : '0';
    
    // Create FormData object
    const formData = new FormData();
    formData.append('file', file);
    formData.append('sender', sender);
    formData.append('receiver', receiver);
    formData.append('is_group', is_group);
    
    // Determine content type based on file type
    let contentType = 'document';
    if (file.type.match('image.*')) {
        contentType = 'image';
    } else if (file.type.match('video.*')) {
        contentType = 'video';
    }
    
    // Send to your PHP API
    fetch('@api/api?action=upload-file', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showUploadStatus('File uploaded successfully!', 'success');
            
            // If you want to insert into messages table with custom content
            if (sender && receiver) {
                const messageData = {
                    sender: sender,
                    receiver: receiver,
                    content: contentType + ':' + (data.data ? data.data.file_name : file.name),
                    type: contentType,
                    is_group: is_group
                };
                
                // Send message to your send endpoint
                sendMessageToDatabase(messageData);
            }
            
            // Reset after successful upload
            setTimeout(() => {
                resetUploadState();
                
                // Redirect or reload as per your PHP response
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (sender && receiver) {
                    // Redirect to chat as in your PHP code
                    window.location.href = window.location.pathname + `?r=${receiver}&s=${sender}`;
                }
            }, 1500);
        } else {
            showUploadStatus(data.message || 'Upload failed', 'error');
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send to Server';
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        showUploadStatus('Upload error occurred', 'error');
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send to Server';
    });
}

// Function to send message to database (similar to your 'send' case)
function sendMessageToDatabase(messageData) {
    const formData = new FormData();
    formData.append('sender', messageData.sender);
    formData.append('receiver', messageData.receiver);
    formData.append('content', messageData.content);
    formData.append('type', messageData.type);
    formData.append('is_group', messageData.is_group);
    
    fetch('@api/api?action=send', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Message sent to database:', data);
    })
    .catch(error => {
        console.error('Error sending message to database:', error);
    });
}

// Function to show upload status
function showUploadStatus(message, type) {
    // Create or get status element
    let statusElement = document.getElementById('upload-status-display');
    
    if (!statusElement) {
        statusElement = document.createElement('div');
        statusElement.id = 'upload-status-display';
        statusElement.style.position = 'fixed';
        statusElement.style.top = '20px';
        statusElement.style.right = '20px';
        statusElement.style.padding = '15px 20px';
        statusElement.style.borderRadius = '8px';
        statusElement.style.zIndex = '9999';
        statusElement.style.fontWeight = '500';
        statusElement.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        document.body.appendChild(statusElement);
    }
    
    // Set styles based on type
    switch(type) {
        case 'success':
            statusElement.style.backgroundColor = '#d1fae5';
            statusElement.style.color = '#065f46';
            statusElement.style.borderLeft = '4px solid #10b981';
            break;
        case 'error':
            statusElement.style.backgroundColor = '#fee2e2';
            statusElement.style.color = '#991b1b';
            statusElement.style.borderLeft = '4px solid #ef4444';
            break;
        case 'info':
            statusElement.style.backgroundColor = '#dbeafe';
            statusElement.style.color = '#1e40af';
            statusElement.style.borderLeft = '4px solid #3b82f6';
            break;
    }
    
    statusElement.textContent = message;
    statusElement.style.display = 'block';
    
    // Auto-hide after 5 seconds for success, 7 for error
    const hideTime = type === 'success' ? 5000 : 7000;
    setTimeout(() => {
        statusElement.style.display = 'none';
    }, hideTime);
}

// Reset upload state
function resetUploadState() {
    sendBtn.disabled = false;
    sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send to Server';
    
    // Reset preview
    previewPlaceholder.style.display = 'block';
    imagePreview.style.display = 'none';
    videoPreview.style.display = 'none';
    resetEdits();
    
    // Clear file list
    fileList.innerHTML = '';
    currentFile = null;
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Initialize with example files in the list (for demo)
document.addEventListener('DOMContentLoaded', function() {
    // Create a mock file object for demo
    const mockFile = {
        name: 'example-photo.jpg',
        type: 'image/jpeg',
        size: 2048576
    };
    
    addFileToList(mockFile);
    
    // Set a demo image for preview
    imagePreview.src = 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&q=80';
    imagePreview.style.display = 'block';
    previewPlaceholder.style.display = 'none';
    currentFile = mockFile;
    sendBtn.disabled = false;
    enableEditButtons(true);
    
    // Set canvas dimensions
    imagePreview.onload = function() {
        imageCanvas.width = imagePreview.naturalWidth;
        imageCanvas.height = imagePreview.naturalHeight;
    };
});