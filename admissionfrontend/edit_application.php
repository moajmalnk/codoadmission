<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODO AI Innovations - Edit Application</title>
    <link rel="icon" href="https://codoacademy.com/uploads/system/e7c3fb5390c74909db1bb3559b24007a.png"
        type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180"
        href="https://codoacademy.com/uploads/system/e7c3fb5390c74909db1bb3559b24007a.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .form-section {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-section h3 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .btn-save {
            background-color: #2563eb;
            color: white;
            padding: 10px 30px;
            border-radius: 5px;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-cancel {
            background-color: #e5e7eb;
            color: #374151;
            padding: 10px 30px;
            border-radius: 5px;
            border: none;
            font-weight: 500;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background-color: #d1d5db;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
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
                        <a class="nav-link" href="index.php">
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

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-edit me-2"></i>Edit Application</h2>
            <button class="btn btn-outline-secondary" onclick="window.history.back()">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </button>
        </div>

        <form id="editApplicationForm">
            <div class="form-section">
                <h3>Personal Information</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="applicant_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="applicant_name" name="applicant_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Course Information</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="batch_no" class="form-label">Batch Number</label>
                        <input type="text" class="form-control" id="batch_no" name="batch_no" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_of_joining" class="form-label">Date of Joining</label>
                        <input type="date" class="form-control" id="date_of_joining" name="date_of_joining" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="education" class="form-label">Education</label>
                        <input type="text" class="form-control" id="education" name="education" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="technical_background" class="form-label">Technical Background</label>
                        <select class="form-select" id="technical_background" name="technical_background">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Family Information</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="father_name" class="form-label">Father's Name</label>
                        <input type="text" class="form-control" id="father_name" name="father_name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="father_occupation" class="form-label">Father's Occupation</label>
                        <input type="text" class="form-control" id="father_occupation" name="father_occupation">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="mother_name" class="form-label">Mother's Name</label>
                        <input type="text" class="form-control" id="mother_name" name="mother_name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="guardian_phone" class="form-label">Guardian's Phone</label>
                        <input type="tel" class="form-control" id="guardian_phone" name="guardian_phone">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Address</h3>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="address" class="form-label">Full Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-4">
                <button type="button" class="btn btn-cancel" onclick="window.history.back()">Cancel</button>
                <button type="submit" class="btn btn-save">Save Changes</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Check authentication
        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('authToken');
            if (!token) {
                window.location.href = 'login.php';
                return;
            }

            try {
                const userData = JSON.parse(atob(token.split('.')[0]));
                document.querySelector('#userInfo .username').textContent = userData.username;
                loadApplicationData();
            } catch (error) {
                window.location.href = 'login.php';
            }
        });

        function loadApplicationData() {
            const applicationData = JSON.parse(localStorage.getItem('editApplication'));
            if (!applicationData) {
                window.location.href = 'dashboard.php';
                return;
            }

            // Populate form fields
            Object.keys(applicationData).forEach(key => {
                const element = document.getElementById(key);
                if (element) {
                    if (key === 'dob' || key === 'date_of_joining') {
                        // Format date for input type="date"
                        const date = new Date(applicationData[key]);
                        element.value = date.toISOString().split('T')[0];
                    } else {
                        element.value = applicationData[key];
                    }
                }
            });
        }

        document.getElementById('editApplicationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const applicationData = JSON.parse(localStorage.getItem('editApplication'));
            formData.append('application_id', applicationData.application_id);

            fetch('https://admissionbackend.moajmalnk.in/api/update_application.php', {
                method: 'POST',
                body: formData,
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Application updated successfully!');
                    localStorage.removeItem('editApplication');
                    window.location.href = 'dashboard.php';
                } else {
                    throw new Error(data.message || 'Failed to update application');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update application: ' + error.message);
            });
        });

        function logout() {
            localStorage.removeItem('authToken');
            window.location.href = 'login.php';
        }
    </script>
</body>

</html> 