<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="https://codoacademy.com/uploads/system/e7c3fb5390c74909db1bb3559b24007a.png"
        type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180"
        href="https://codoacademy.com/uploads/system/e7c3fb5390c74909db1bb3559b24007a.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Form - CODO AI Innovations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Authentication Check Overlay -->
    <div class="auth-check">
        <div class="text-center">
            <div class="spinner-border auth-spinner text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Checking authentication...</p>
        </div>
    </div>

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

        <!-- Form Container -->
        <div class="container">
            <div class="card">
                <div class="form-header">
                    <img src="https://codoacademy.com/uploads/system/0623b9b92a325936b0a00502d95c22e6.png"
                        alt="CODO AI Innovations" class="logo">
                    <div class="date-fields">
                        Date: <span id="currentDate"></span><br>
                        App id: <span id="applicationId"></span>
                        <input type="hidden" name="applicationId" id="applicationIdInput">
                    </div>
                </div>

                <h1 class="form-title">Web Design & Development<br>Admission Form</h1>

                <form id="admissionForm" method="POST" action="process.php">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="applicantName" class="form-label">Applicant Name</label>
                            <input type="text" class="form-control" id="applicantName" name="applicantName" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="batchNo" class="form-label">
                                Batch no.
                            </label>
                            <input type="text" class="form-control" id="batchNo" name="batchNo" required>
                        </div>
                        <div class="col-12">
                            <label for="dateOfJoining" class="form-label">
                                Date of Joining
                                <i class="fas fa-info-circle text-primary" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   title="Batches start every Wednesday only">
                                </i>
                            </label>
                            <input type="date" class="form-control" id="dateOfJoining" name="dateOfJoining">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="dob" class="form-label">
                                Date of Birth
                                <i class="fas fa-info-circle text-primary" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   title="Applicant must be at least 15 years old to apply">
                                </i>
                            </label>
                            <input type="date" class="form-control" id="dob" name="dob" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Gender</label>
                            <div class="gender-group">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="male" name="gender" value="male" required>
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="female" name="gender" value="female">
                                    <label class="form-check-label" for="female">Female</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="phone" class="form-label">Phone No.</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="education" class="form-label">Education</label>
                            <select class="form-select" id="educationSelect" name="education" onchange="handleEducationChange()">
                                <option value="SSLC">SSLC</option>
                                <option selected value="Plus 2">Plus 2</option>
                                <option value="Degree">Degree</option>
                                <option value="custom">Other (Please Specify)</option>
                            </select>
                            <input type="text" class="form-control mt-2" id="customEducation" name="customEducation" 
                                   placeholder="Please specify your education" style="display: none;">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="fatherName" class="form-label">Father's Name</label>
                            <input type="text" class="form-control" id="fatherName" name="fatherName">
                        </div>
                        <div class="col-12">
                            <label for="fatherOccupation" class="form-label">Father's Occupation</label>
                            <input type="text" class="form-control" id="fatherOccupation" name="fatherOccupation">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="motherName" class="form-label">Mother's Name</label>
                            <input type="text" class="form-control" id="motherName" name="motherName">
                        </div>
                        <div class="col-12">
                            <label for="guardianPhone" class="form-label">Guardian's Phone No.</label>
                            <input type="tel" class="form-control" id="guardianPhone" name="guardianPhone">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <label class="form-label">Technical Background</label>
                            <div class="d-flex align-items-center">
                                <label class="me-4">Do you have any prior experience with Web Development?</label>
                                <div class="d-flex gap-4" style="margin-left: 10px !important;">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="techYes" name="technicalBackground"
                                            value="yes">
                                        <label class="form-check-label" for="techYes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="techNo" name="technicalBackground"
                                            value="no">
                                        <label class="form-check-label" for="techNo">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Declaration text -->
                    <div class="declaration-text">
                        I, <span class="declaration-name">_______________________</span>, confirm that the information in
                        this form is
                        accurate to my knowledge. Providing false information may lead to rejection. I acknowledge that
                        joining this mentorship program is a privilege and commit to following its rules. I understand that
                        my behavior is subject to review, and any violation may result in termination. I give permission to
                        verify the information for admission purposes.
                    </div>

                    <div class="submit-section">
                        <button type="submit" class="btn btn-primary submit-btn" id="submitApplication">
                            <i class="fas fa-paper-plane"></i> Submit Application
                        </button>
                    </div>
                </form>

                <!-- Thank you popup modal -->
                <div class="modal fade" id="thankYouModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-check-circle text-success"></i> Application Submitted Successfully
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="success-message text-center">
                                    <div class="success-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <h4>Thank You!</h4>
                                    <p>Your application has been submitted successfully.</p>
                                    <p class="app-id">Application ID: <strong id="submittedAppId"></strong></p>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="window.location.href='index.php'">
                                    <i class="fas fa-plus-circle"></i> New Admission
                                </button>
                                <button type="button" class="btn btn-primary" onclick="printApplication()">
                                    <i class="fas fa-print"></i> Print Application
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <p>CODO AI Innovations</p>
                    <p>Paravath Arcade, 2nd Floor, opp. Budget</p>
                    <p>Hypermarket, Varangode, Malappuram, Kerala 676519</p>
                    <p>info@codoacademy.com | www.codoacademy.com</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="validation.js"></script>
    <script src="app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const authCheck = document.querySelector('.auth-check');
            const mainContent = document.getElementById('mainContent');

            // Check authentication
            const token = localStorage.getItem('authToken');
            const userData = localStorage.getItem('userData');

            if (!token || !userData) {
                // No auth data - redirect to login
                window.location.replace('login.php');
                return;
            }

            try {
                // Verify user data format
                const user = JSON.parse(userData);
                if (!user || !user.username || !user.role) {
                    throw new Error('Invalid user data');
                }

                // Valid auth - show main content
                authCheck.style.display = 'none';
                mainContent.style.display = 'block';
                
                // Set user info in navbar
                document.querySelector('#userInfo .username').textContent = user.username;
                
                // Initialize form if needed
                if (typeof FormHandler !== 'undefined') {
                    new FormHandler();
                }

                // Set current date
                const today = new Date();
                const currentDateElement = document.getElementById('currentDate');
                if (currentDateElement) {
                    currentDateElement.textContent = formatDate(today);
                }

            } catch (error) {
                console.error('Authentication error:', error);
                // Clear invalid data and redirect to login
                localStorage.removeItem('authToken');
                localStorage.removeItem('userData');
                window.location.replace('login.php');
            }
        });

        function logout() {
            localStorage.removeItem('authToken');
            localStorage.removeItem('userData');
            window.location.replace('login.php');
        }

        function formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        // Handle modal close events
        const modalElement = document.getElementById('thankYouModal');
        if (modalElement) {
            modalElement.addEventListener('hidden.bs.modal', function () {
                window.location.href = 'dashboard.php';
            });
        }
    </script>
</body>

</html>