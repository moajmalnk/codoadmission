<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODO AI Innovations - Dashboard</title>
    <link rel="icon" href="https://codoacademy.com/uploads/system/e7c3fb5390c74909db1bb3559b24007a.png"
        type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180"
        href="https://codoacademy.com/uploads/system/e7c3fb5390c74909db1bb3559b24007a.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <div id="mainContent" style="display: none;">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-light sticky-top">
            <div class="container" style="padding: 0px 10px 0px 50px;">
                <a class="navbar-brand" href="dashboard.php">
                    <img src="https://codoacademy.com/uploads/system/0623b9b92a325936b0a00502d95c22e6.png"
                        alt="CODO AI Innovations" class="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-home me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">
                                <i class="fas fa-user-graduate me-2"></i>Admission Form
                            </a>
                        </li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <span id="userInfo" class="me-3">
                            <i class="fas fa-user-circle me-2"></i>
                            <span class="username"></span>
                        </span>
                        <button class="btn btn-outline-danger" onclick="logout()">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-file-alt me-2"></i>Admission Applications
                </h2>
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search applications...">
                </div>
            </div>

            <div id="applicationsGrid" class="applications-grid">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Modal -->
        <div id="previewModal" class="preview-modal">
            <div class="preview-content">
                <div class="preview-header">
                    <h3 class="mb-0">Application Details</h3>
                    <button class="btn-close-preview" onclick="closePreview()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="preview-body" id="previewBody">
                    <!-- Content will be dynamically inserted here -->
                </div>
                <div class="preview-actions">
                    <button type="button" class="btn btn-secondary" onclick="closePreview()">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                    <button class="btn btn-danger btn-delete-application"
                        onclick="showDeleteConfirmation(this.dataset.applicationId)" data-application-id="">
                        <i class="fas fa-trash-alt me-2"></i>Delete
                    </button>
                    <button class="btn btn-primary btn-download-application"
                        onclick="downloadApplication(this.dataset.applicationId)" data-application-id="">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="modal-overlay">
            <div class="delete-modal">
                <div class="delete-modal-content">
                    <div class="delete-modal-header">
                        <h3>Delete Application</h3>
                        <button class="close-button" onclick="closeDeleteModal()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="delete-modal-body">
                        <div class="warning-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="confirmation-text">
                            <h4>Confirm Deletion</h4>
                            <p>Are you sure you want to delete this application? This action cannot be undone.</p>
                        </div>
                        <div class="application-details">
                            <div class="detail-item">
                                <span class="label">Application ID:</span>
                                <span id="deleteApplicationId" class="value"></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Applicant Name:</span>
                                <span id="deleteApplicantName" class="value"></span>
                            </div>
                        </div>
                    </div>
                    <div class="delete-modal-footer">
                        <button class="btn-cancel" onclick="closeDeleteModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button class="btn-delete" id="confirmDeleteBtn">
                            <i class="fas fa-trash-alt"></i> Delete Application
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Delete Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            animation: fadeIn 0.3s ease-out;
        }

        .delete-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease-out;
        }

        .delete-modal-content {
            display: flex;
            flex-direction: column;
        }

        .delete-modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .delete-modal-header h3 {
            margin: 0;
            color: #2c3e50;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .close-button {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #666;
            cursor: pointer;
            padding: 5px;
            transition: color 0.2s;
        }

        .close-button:hover {
            color: #dc3545;
        }

        .delete-modal-body {
            padding: 25px;
            text-align: center;
        }

        .warning-icon {
            color: #dc3545;
            font-size: 3.5rem;
            margin-bottom: 20px;
        }

        .confirmation-text {
            margin-bottom: 25px;
        }

        .confirmation-text h4 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1.3rem;
        }

        .confirmation-text p {
            color: #666;
            margin-bottom: 0;
        }

        .application-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .detail-item:not(:last-child) {
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-item .label {
            color: #666;
            font-weight: 500;
        }

        .detail-item .value {
            color: #2c3e50;
            font-weight: 600;
        }

        .delete-modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .btn-cancel,
        .btn-delete {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-cancel {
            background: #e9ecef;
            color: #495057;
        }

        .btn-cancel:hover {
            background: #dee2e6;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translate(-50%, -40%);
                opacity: 0;
            }

            to {
                transform: translate(-50%, -50%);
                opacity: 1;
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .delete-modal {
                width: 95%;
                margin: 20px;
            }

            .delete-modal-header h3 {
                font-size: 1.3rem;
            }

            .delete-modal-body {
                padding: 20px;
            }

            .warning-icon {
                font-size: 3rem;
            }

            .confirmation-text h4 {
                font-size: 1.2rem;
            }

            .detail-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .delete-modal-footer {
                flex-direction: column-reverse;
                gap: 10px;
            }

            .btn-cancel,
            .btn-delete {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .delete-modal {
                width: 100%;
                margin: 10px;
                max-height: 90vh;
                overflow-y: auto;
            }

            .delete-modal-header {
                padding: 15px 20px;
            }

            .delete-modal-body {
                padding: 15px;
            }

            .application-details {
                padding: 15px;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="app.js"></script>
    <script>
        let applications = [];
        let currentApplication = null;

        // Check authentication on page load
        function checkAuth() {
            const token = localStorage.getItem('authToken');
            const userData = localStorage.getItem('userData');

            if (!token || !userData) {
                // Clear any potentially corrupted data
                localStorage.clear();
                window.location.replace('login.php');
                return false;
            }

            try {
                // Verify user data format
                const user = JSON.parse(userData);
                if (!user || !user.username || !user.role) {
                    throw new Error('Invalid user data');
                }

                // Set user info in navbar
                document.querySelector('#userInfo .username').textContent = user.username;
                return true;
            } catch (error) {
                // console.error('Authentication error:', error);
                // Clear invalid data
                localStorage.clear();
                window.location.replace('login.php');
                return false;
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function () {
            const mainContent = document.getElementById('mainContent');
            if (checkAuth()) {
                mainContent.style.display = 'block';
                loadApplications();
            }
        });

        function logout() {
            // Clear all authentication data
            localStorage.clear();

            // Show loading indicator
            const loadingDiv = document.createElement('div');
            loadingDiv.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.9);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            `;
            loadingDiv.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Logging out...</span>
                </div>
            `;
            document.body.appendChild(loadingDiv);

            // Force redirect to login page
            window.location.href = 'login.php';
        }

        function loadApplications() {
            const gridElement = document.getElementById('applicationsGrid');
            gridElement.innerHTML = `
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;

            fetch('http://localhost/codoadmission/admissionbackend/api/get_applications.php', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('authToken')}`
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch applications');
                    }
                    return response.json();
                })
                .then(data => {
                    applications = data;
                    displayApplications(applications);
                })
                .catch(error => {
                    // console.error('Error:', error);
                    gridElement.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        Failed to load applications. Please try again later.
                    </div>
                `;
                });
        }

        function displayApplications(apps) {
            const grid = document.getElementById('applicationsGrid');
            if (apps.length === 0) {
                grid.innerHTML = `
                    <div class="text-center text-muted p-5">
                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                        <h4>No applications found</h4>
                        <p>New applications will appear here.</p>
                    </div>
                `;
                return;
            }

            grid.innerHTML = apps.map(app => createApplicationCard(app)).join('');
        }

        function createApplicationCard(application) {
            return `
                <div class="application-card" onclick="showApplicationPreview(${JSON.stringify(application).replace(/"/g, '&quot;')})">
                    <div class="application-header">
                        <span class="application-id">${application.application_id}</span>
                        <span class="application-date">${formatDate(application.created_at)}</span>
                    </div>
                    <h3 class="application-name">${application.applicant_name}</h3>
                    <div class="application-details">
                        <div class="detail-row">
                            <span class="detail-label">Batch No:</span>
                            <span>${application.batch_no}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Phone:</span>
                            <span>${application.phone}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Email:</span>
                            <span>${application.email}</span>
                        </div>
                    </div>
                </div>`;
        }

        function showApplicationPreview(application) {
            currentApplication = application;
            const previewModal = document.getElementById('previewModal');
            const previewBody = document.getElementById('previewBody');

            // Update action buttons
            previewModal.querySelector('.btn-delete-application').dataset.applicationId = application.application_id;
            previewModal.querySelector('.btn-download-application').dataset.applicationId = application.application_id;

            // Update preview content
            previewBody.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h4>Personal Information</h4>
                        <table class="table">
                            <tr>
                                <th>Application ID</th>
                                <td>${application.application_id}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>${application.applicant_name}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth</th>
                                <td>${formatDate(application.dob)}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>${application.gender}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>${application.phone}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>${application.email}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>Course Information</h4>
                        <table class="table">
                            <tr>
                                <th>Batch No</th>
                                <td>${application.batch_no || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Date of Joining</th>
                                <td>${formatDate(application.date_of_joining) || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Education</th>
                                <td>${application.education}</td>
                            </tr>
                            <tr>
                                <th>Technical Background</th>
                                <td>${application.technical_background || 'No'}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Family Information</h4>
                        <table class="table">
                            <tr>
                                <th>Father's Name</th>
                                <td>${application.father_name || 'N/A'}</td>
                                <th>Father's Occupation</th>
                                <td>${application.father_occupation || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Mother's Name</th>
                                <td>${application.mother_name || 'N/A'}</td>
                                <th>Guardian's Phone</th>
                                <td>${application.guardian_phone || 'N/A'}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Address</h4>
                        <p>${application.address}</p>
                    </div>
                </div>
            `;

            previewModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closePreview() {
            const previewModal = document.getElementById('previewModal');
            previewModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            currentApplication = null;
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            const filteredApps = applications.filter(app =>
                app.applicant_name.toLowerCase().includes(searchTerm) ||
                app.application_id.toLowerCase().includes(searchTerm) ||
                app.email.toLowerCase().includes(searchTerm) ||
                app.phone.includes(searchTerm)
            );
            displayApplications(filteredApps);
        });

        // Close modal when clicking outside
        window.onclick = function (event) {
            const previewModal = document.getElementById('previewModal');
            const deleteModal = document.getElementById('deleteModal');
            if (event.target === previewModal) {
                closePreview();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (document.getElementById('deleteModal').style.display === 'block') {
                    closeDeleteModal();
                }
                if (document.getElementById('previewModal').style.display === 'block') {
                    closePreview();
                }
            }
        });

        function showDeleteConfirmation(applicationId) {
            const app = applications.find(a => a.application_id === applicationId);
            if (!app) return;

            const deleteModal = document.getElementById('deleteModal');
            document.getElementById('deleteApplicationId').textContent = app.application_id;
            document.getElementById('deleteApplicantName').textContent = app.applicant_name;

            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.onclick = () => {
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
                performDelete(app.application_id);
            };

            deleteModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const deleteModal = document.getElementById('deleteModal');
            deleteModal.style.display = 'none';
            document.body.style.overflow = 'auto';

            // Reset the delete button
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fas fa-trash-alt"></i> Delete Application';
        }

        function performDelete(applicationId) {
            fetch(`http://localhost/codoadmission/admissionbackend/api/delete_application.php?id=${applicationId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to delete application');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showNotification('Application deleted successfully', 'success');
                        closeDeleteModal();
                        closePreview(); // Close the preview modal if open
                        loadApplications(); // Refresh the applications list
                    } else {
                        showNotification(data.message || 'Failed to delete application', 'error');
                    }
                })
                .catch(error => {
                    // console.error('Error:', error);
                    showNotification('Failed to delete application', 'error');
                })
                .finally(() => {
                    // Reset the delete button
                    const confirmBtn = document.getElementById('confirmDeleteBtn');
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="fas fa-trash-alt"></i> Delete Application';
                });
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} notification`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                padding: 15px 25px;
                border-radius: 5px;
                animation: slideIn 0.5s ease-out;
            `;
            notification.innerHTML = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.5s ease-in';
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }

        // Add this to your existing styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            .application-info {
                background-color: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
                border-left: 4px solid #dc3545;
            }
            #deleteModal .preview-content {
                animation: modalFadeIn 0.3s ease-out;
            }
            @keyframes modalFadeIn {
                from {
                    transform: translateY(-20px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);

        function downloadApplication(applicationId) {
            if (!applicationId) {
                showNotification('No application selected', 'error');
                return;
            }

            const pdfUrl = `http://localhost/codoadmission/admissionbackend/generate_pdf.php?application_id=${applicationId}`;

            // Show loading notification
            showNotification('Generating PDF...', 'info');

            // Create a temporary link element
            const link = document.createElement('a');
            link.href = pdfUrl;
            link.target = '_blank';

            // Append to body, click and remove
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>

</html>