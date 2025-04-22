// Date formatting utility
const DateUtil = {
    formatDate(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    },

    // Get next Wednesday from a given date
    getNextWednesday(fromDate = new Date()) {
        const date = new Date(fromDate);
        const day = date.getDay();
        // If it's after Wednesday, get next Wednesday
        // If it's before Wednesday, get this Wednesday
        const daysUntilWednesday = day > 3 ? 10 - day : 3 - day;
        date.setDate(date.getDate() + daysUntilWednesday);
        return date;
    },

    // Check if a date is a Wednesday
    isWednesday(date) {
        return date.getDay() === 3; // 3 represents Wednesday (0 = Sunday, 1 = Monday, etc.)
    }
};

// Batch number utility
const BatchUtil = {
    async generateBatchNumber() {
        const today = new Date();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = String(today.getFullYear()).slice(-2);
        
        try {
            // Get last application ID to determine batch number
            const lastId = await getLastApplicationId();
            let applicantCount = 0;
            let batchNumber = 1;
            
            if (lastId) {
                // Extract the month and year from last ID
                const parts = lastId.split('/');
                if (parts.length === 3) {
                    const lastMonth = parts[1].substring(0, 2);
                    const lastYear = parts[1].substring(2, 4);
                    
                    // If same month and year, get the applicant count
                    if (lastMonth === month && lastYear === year) {
                        applicantCount = parseInt(localStorage.getItem('currentBatchCount') || '0');
                        batchNumber = Math.floor(applicantCount / 30) + 1;
                        
                        // Increment applicant count
                        applicantCount = (applicantCount % 30) + 1;
                    } else {
                        // New month/year - reset counts
                        applicantCount = 1;
                        batchNumber = 1;
                    }
                }
            } else {
                // First application
                applicantCount = 1;
                batchNumber = 1;
            }
            
            // Save the current batch count
            localStorage.setItem('currentBatchCount', applicantCount.toString());
            
            // Format batch number
            const batchString = String(batchNumber).padStart(3, '0');
            return `CODO/${month}${year}/B${batchString}`;
            
        } catch (error) {
            console.error('Error generating batch number:', error);
            return `CODO/${month}${year}/B001`;
        }
    },

    validateBatchNumber(batchNo) {
        const batchPattern = /^CODO\/\d{4}\/B\d{3}$/;
        return batchPattern.test(batchNo);
    }
};

// Function to get the last application ID from the database
async function getLastApplicationId() {
    try {
        const response = await fetch('http://localhost/codoadmission/admissionbackend/get_last_id.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': localStorage.getItem('token') || ''
            },
            mode: 'cors'
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        if (!data.success) {
            throw new Error(data.message || 'Failed to fetch last ID');
        }
        return data.lastId || null;
    } catch (error) {
        console.error('Error fetching last ID:', error);
        return null;
    }
}

// Function to show error message in a user-friendly way
function showError(message) {
    // You can customize this to show errors in a nicer way (e.g., using Bootstrap alerts)
    alert(message);
}

// Function to generate next application ID
async function generateApplicationId() {
    try {
        const today = new Date();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = String(today.getFullYear()).slice(-2);
        
        // Get last application ID from database
        const lastId = await getLastApplicationId();
        let nextNumber = 1;
        
        if (lastId) {
            try {
                // Extract the number from last ID (e.g., "CODO/0425/001" -> 1)
                const parts = lastId.split('/');
                if (parts.length === 3) {
                    const lastNumber = parseInt(parts[2]);
                    if (!isNaN(lastNumber)) {
                        nextNumber = lastNumber + 1;
                    }
                }
            } catch (parseError) {
                console.error('Error parsing last ID:', parseError);
                // Generate a timestamp-based fallback
                nextNumber = Math.floor(Date.now() / 1000) % 1000;
            }
        }
        
        // Format number with leading zeros
        const formattedNumber = String(nextNumber).padStart(3, '0');
        const generatedId = `CODO/${month}${year}/${formattedNumber}`;

        // Verify if ID exists
        const verifyResponse = await fetch(`http://localhost/codoadmission/admissionbackend/verify_id.php?id=${generatedId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('authToken')}` || ''
            },
            mode: 'cors'
        });

        const verifyResult = await verifyResponse.json();
        if (verifyResult.exists) {
            // If ID exists, add timestamp to make it unique
            const timestamp = Date.now().toString().slice(-3);
            return `CODO/${month}${year}/${timestamp}`;
        }

        return generatedId;
    } catch (error) {
        console.error('Error generating application ID:', error);
        // Fallback to a timestamp-based ID if everything fails
        const timestamp = Date.now().toString().slice(-3);
        return `CODO/${month}${year}/${timestamp}`;
    }
}

// Add age validation utility function at the top level
function validateAge(dobValue) {
    const dob = new Date(dobValue);
    const today = new Date();
    
    // Check for future dates
    if (dob > today) {
        return -1; // Invalid - future date
    }
    
    const age = today.getFullYear() - dob.getFullYear();
    const monthDiff = today.getMonth() - dob.getMonth();
    
    // Adjust age if birthday hasn't occurred this year
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
        return age - 1;
    }
    return age;
}

function validateDateOfBirth(dobInput) {
    if (!dobInput || !dobInput.value) {
        alert('Please enter your date of birth.');
        dobInput?.focus();
        return false;
    }

    const age = validateAge(dobInput.value);
    if (age < 0) {
        alert('Date of birth cannot be in the future.');
        dobInput.value = ''; // Clear invalid date
        dobInput.focus();
        return false;
    }
    if (age < 15) {
        alert('You must be at least 15 years old to apply.');
        dobInput.value = ''; // Clear invalid date
        dobInput.focus();
        return false;
    }
    return true;
}

// Function to validate joining date
function validateJoiningDate(dateInput) {
    if (!dateInput || !dateInput.value) {
        alert('Please select a joining date.');
        dateInput?.focus();
        return false;
    }

    const selectedDate = new Date(dateInput.value);
    if (!DateUtil.isWednesday(selectedDate)) {
        alert('Batch starts only on Wednesdays. Please select a Wednesday.');
        dateInput.value = DateUtil.getNextWednesday().toISOString().split('T')[0];
        dateInput.focus();
        return false;
    }
    return true;
}

// Function to handle education dropdown changes
function handleEducationChange() {
    const educationSelect = document.getElementById('educationSelect');
    const customEducation = document.getElementById('customEducation');
    
    if (educationSelect.value === 'custom') {
        customEducation.style.display = 'block';
        customEducation.required = true;
    } else {
        customEducation.style.display = 'none';
        customEducation.required = false;
        customEducation.value = ''; // Clear custom input when not in use
    }
}

// Form Handler Class
class FormHandler {
    constructor() {
        this.form = document.getElementById('admissionForm');
        this.modal = new bootstrap.Modal(document.getElementById('thankYouModal'));
        this.setupEventListeners();
    }

    setupEventListeners() {
        this.form?.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (this.validateForm()) {
                await this.handleSubmission();
            }
        });

        document.getElementById('printApplication')?.addEventListener('click', () => {
            this.printApplication();
        });

        // Initialize education dropdown handler
        const educationSelect = document.getElementById('educationSelect');
        if (educationSelect) {
            educationSelect.addEventListener('change', handleEducationChange);
        }
    }

    validateForm() {
        const dobInput = document.getElementById('dob');
        const joiningDateInput = document.getElementById('dateOfJoining');
        
        return validateDateOfBirth(dobInput) && validateJoiningDate(joiningDateInput);
    }

    async handleSubmission() {
        try {
            const dobInput = document.getElementById('dob');
            if (!validateDateOfBirth(dobInput)) {
                return;
            }

            const formData = new FormData(this.form);
            
            // Handle education field
            const educationSelect = document.getElementById('educationSelect');
            const customEducation = document.getElementById('customEducation');
            if (educationSelect.value === 'custom' && customEducation.value) {
                formData.set('education', customEducation.value);
            }

            const appId = document.getElementById('applicationId').textContent;
            formData.append('applicationId', appId);
            
            // Debug: Log form data
            console.log('Submitting form data:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }
            
            document.getElementById('submitApplication').disabled = true;
            
            const response = await fetch('http://localhost/codoadmission/admissionbackend/process.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('authToken')}` || ''
                },
                mode: 'cors'
            });

            const result = await response.json();
            console.log('Server response:', result);
            
            if (result.success) {
                const modal = new bootstrap.Modal(document.getElementById('thankYouModal'));
                document.getElementById('submittedAppId').textContent = appId;
                modal.show();
                this.form.reset();
            } else {
                throw new Error(result.message || 'Submission failed');
            }
        } catch (error) {
            console.error('Submission error:', error);
            // Show detailed error message
            const errorMessage = error.message || 'Unknown error occurred';
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger mt-3';
            errorDiv.innerHTML = `
                <strong>Error submitting form:</strong><br>
                ${errorMessage}<br>
                Please try again or contact support if the problem persists.
            `;
            this.form.insertBefore(errorDiv, this.form.firstChild);
            
            // Auto-remove error message after 5 seconds
            setTimeout(() => errorDiv.remove(), 5000);
        } finally {
            document.getElementById('submitApplication').disabled = false;
        }
    }

    printApplication() {
        const printContent = document.createElement('div');
        printContent.classList.add('print-section');
        
        printContent.innerHTML = `
            <div class="print-header">
                <img src="${document.querySelector('.logo').src}" alt="CODO AI Innovations" style="max-height: 60px;">
                <h2>Web Design & Development Admission Form</h2>
            </div>
            <div class="print-info">
                <p>Application ID: ${document.getElementById('applicationId').textContent}</p>
                <p>Date: ${document.getElementById('currentDate').textContent}</p>
            </div>
            <div class="print-content">
                ${this.getFormDataForPrint()}
            </div>
        `;
        
        document.body.appendChild(printContent);
        window.print();
        document.body.removeChild(printContent);
    }

    getFormDataForPrint() {
        const formData = new FormData(this.form);
        let printHTML = '<div class="form-data">';
        
        formData.forEach((value, key) => {
            printHTML += `
                <div class="form-field">
                    <label>${key}:</label>
                    <span>${value}</span>
                </div>
            `;
        });
        
        printHTML += '</div>';
        return printHTML;
    }
}

function downloadApplication(applicationId) {
    if (applicationId) {
        window.location.href = `http://localhost/codoadmission/admissionbackend/generate_pdf.php?application_id=${applicationId}`;
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', async function() {
    try {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Set current date
        const today = new Date();
        const currentDateElement = document.getElementById('currentDate');
        if (currentDateElement) {
            currentDateElement.textContent = DateUtil.formatDate(today);
        }

        // Set up date of birth input
        const dobInput = document.getElementById('dob');
        if (dobInput) {
            const maxDate = new Date();
            maxDate.setFullYear(maxDate.getFullYear() - 15);
            
            // Set max attribute to today to prevent future dates
            dobInput.max = maxDate.toISOString().split('T')[0];

            // Set default value to 15 years ago
            dobInput.value = maxDate.toISOString().split('T')[0];

            // Add real-time validation
            dobInput.addEventListener('change', function() {
                if (this.value) {
                    validateDateOfBirth(this);
                }
            });
        }

        // Set up joining date input
        const joiningDateInput = document.getElementById('dateOfJoining');
        if (joiningDateInput) {
            // Set min date to today
            const today = new Date();
            joiningDateInput.min = today.toISOString().split('T')[0];
            
            // Set default value to next Wednesday
            const nextWednesday = DateUtil.getNextWednesday();
            joiningDateInput.value = nextWednesday.toISOString().split('T')[0];
            
            // Add real-time validation
            joiningDateInput.addEventListener('change', function() {
                if (this.value) {
                    validateJoiningDate(this);
                }
            });
        }

        // Set up batch number
        const batchInput = document.getElementById('batchNo');
        if (batchInput) {
            // Set default batch number
            const defaultBatchNo = await BatchUtil.generateBatchNumber();
            batchInput.value = defaultBatchNo;
            batchInput.placeholder = 'Format: CODO/MMYY/B001';

            // Add validation on input
            batchInput.addEventListener('change', function() {
                if (this.value && !BatchUtil.validateBatchNumber(this.value)) {
                    alert('Please use the format CODO/MMYY/B001 (e.g., CODO/0425/B001)');
                    this.value = defaultBatchNo;
                }
            });

            // Add tooltip
            const batchLabel = document.querySelector('label[for="batchNo"]');
            if (batchLabel) {
                const infoIcon = document.createElement('i');
                infoIcon.className = 'fas fa-info-circle text-primary ms-1';
                infoIcon.setAttribute('data-bs-toggle', 'tooltip');
                infoIcon.setAttribute('data-bs-placement', 'top');
                infoIcon.setAttribute('title', 'Format: CODO/MMYY/B001 (Month/Year/Batch Number)');
                batchLabel.appendChild(infoIcon);
                new bootstrap.Tooltip(infoIcon);
            }
        }

        // Generate and set application ID
        const appId = await generateApplicationId();
        console.log('Generated App ID:', appId);

        const applicationIdDisplay = document.getElementById('applicationId');
        const applicationIdInput = document.getElementById('applicationIdInput');
        
        if (applicationIdDisplay) {
            applicationIdDisplay.textContent = appId;
        }
        
        if (applicationIdInput) {
            applicationIdInput.value = appId;
        }

        // Set up print button
        const printButton = document.querySelector('button[onclick="printApplication()"]');
        if (printButton) {
            printButton.onclick = null;
            printButton.addEventListener('click', function() {
                const appId = document.getElementById('submittedAppId').textContent;
                if (appId) {
                    downloadApplication(appId);
                }
            });
        }

        // Set up applicant name synchronization
        const applicantNameInput = document.getElementById('applicantName');
        const updateDeclarationName = function() {
            const declarationName = document.querySelector('.declaration-name');
            if (declarationName) {
                declarationName.textContent = applicantNameInput.value || '_______________________';
            }
        };
        
        if (applicantNameInput) {
            applicantNameInput.addEventListener('input', updateDeclarationName);
            updateDeclarationName(); // Initial update
        }

        // Initialize form handler
        const admissionForm = document.getElementById('admissionForm');
        if (admissionForm) {
            new FormHandler();
        }

        // For dashboard page
        const downloadButtons = document.querySelectorAll('.btn-download-application');
        downloadButtons.forEach(button => {
            button.addEventListener('click', function() {
                const appId = this.getAttribute('data-application-id');
                if (appId) {
                    downloadApplication(appId);
                }
            });
        });

    } catch (error) {
        console.error('Initialization error:', error);
        showError('Error initializing form. Please refresh the page.');
    }
});
