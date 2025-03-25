// Date formatting utility
const DateUtil = {
    formatDate(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }
};

// Signature Pad Handler
class SignaturePadHandler {
    constructor() {
        this.canvas = document.getElementById('signaturePad');
        if (!this.canvas) {
            throw new Error('Signature pad canvas not found');
        }

        this.statusElement = document.getElementById('signatureStatus');
        this.drawingStarted = false;
        this.resizeTimeout = null;

        this.initSignaturePad();
        this.bindEvents();
    }

    initSignaturePad() {
        this.signaturePad = new SignaturePad(this.canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(26, 60, 110)', // CODO blue
            velocityFilterWeight: 0.7,
            minWidth: 0.5,
            maxWidth: 2.5,
            throttle: 16,
        });
    }

    bindEvents() {
        // Resize handling
        window.addEventListener("resize", () => {
            clearTimeout(this.resizeTimeout);
            this.resizeTimeout = setTimeout(() => this.resizeCanvas(), 250);
        });

        // Clear signature
        const clearBtn = document.getElementById('clearSignature');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this.clearSignature());
        }

        // Undo signature
        const undoBtn = document.getElementById('undoSignature');
        if (undoBtn) {
            undoBtn.addEventListener('click', () => this.undoSignature());
        }

        // Signature events
        if (this.signaturePad) {
            this.signaturePad.addEventListener("beginStroke", () => {
                if (!this.drawingStarted) {
                    this.drawingStarted = true;
                    this.updateStatus('Signing...');
                }
            });

            this.signaturePad.addEventListener("endStroke", () => {
                this.updateSignatureInput();
                this.updateStatus('Signature added');
            });
        }

        // Form validation
        document.getElementById('admissionForm')?.addEventListener('submit', (e) => {
            if (this.signaturePad.isEmpty()) {
                e.preventDefault();
                this.updateStatus('Please add your signature before submitting');
                this.canvas.classList.add('shake');
                setTimeout(() => this.canvas.classList.remove('shake'), 650);
                return false;
            }
        });
    }

    resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const context = this.canvas.getContext("2d");
        
        // Store current signature data
        const data = this.signaturePad.toData();
        
        // Resize canvas
        this.canvas.width = this.canvas.offsetWidth * ratio;
        this.canvas.height = this.canvas.offsetHeight * ratio;
        context.scale(ratio, ratio);
        
        // Restore signature if it existed
        if (data) {
            this.signaturePad.fromData(data);
        }
    }

    clearSignature() {
        const clearBtn = document.getElementById('clearSignature');
        clearBtn.classList.add('rotating');
        this.signaturePad.clear();
        document.getElementById('signatureData').value = '';
        this.updateStatus('Signature cleared');
        setTimeout(() => clearBtn.classList.remove('rotating'), 500);
    }

    undoSignature() {
        const data = this.signaturePad.toData();
        if (data) {
            data.pop();
            this.signaturePad.fromData(data);
            this.updateSignatureInput();
            this.updateStatus('Last stroke removed');
        }
    }

    updateStatus(message) {
        if (this.statusElement) {
            this.statusElement.textContent = message;
            this.statusElement.style.animation = 'none';
            this.statusElement.offsetHeight; // Trigger reflow
            this.statusElement.style.animation = null;
        }
    }

    updateSignatureInput() {
        const signatureInput = document.getElementById('signatureData');
        if (!this.signaturePad.isEmpty()) {
            const signatureData = this.signaturePad.toDataURL('image/png');
            signatureInput.value = signatureData;
        } else {
            signatureInput.value = '';
            this.updateStatus('No signature added');
        }
    }
}

// Application ID Generator Class
class ApplicationIdGenerator {
    static async generateId() {
        const today = new Date();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = String(today.getFullYear()).slice(-2);
        
        // Get current count from localStorage
        let currentCount = parseInt(localStorage.getItem('applicationCount') || '0');
        currentCount++;
        
        // Reset count if it's a new day
        const currentDate = `${today.getFullYear()}-${month}-${today.getDate()}`;
        const lastDate = localStorage.getItem('lastApplicationDate');
        
        if (currentDate !== lastDate) {
            currentCount = 1;
            localStorage.setItem('lastApplicationDate', currentDate);
        }
        
        // Save the updated count
        localStorage.setItem('applicationCount', currentCount.toString());
        
        // Format counter with leading zeros
        const counterString = String(currentCount).padStart(3, '0');
        
        return `CODO/${month}${year}/${counterString}`;
    }
}

// Form Handler
class FormHandler {
    constructor() {
        this.form = document.getElementById('admissionForm');
        this.modal = new bootstrap.Modal(document.getElementById('thankYouModal'));
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Form submission
        this.form?.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (this.validateForm()) {
                await this.handleSubmission();
            }
        });

        // Print button
        document.getElementById('printApplication')?.addEventListener('click', () => {
            this.printApplication();
        });
    }

    validateForm() {
        // Add your form validation logic here
        return true;
    }

    async handleSubmission() {
        try {
            // Get form data
            const formData = new FormData(this.form);
            
            // Add application ID explicitly
            const applicationId = document.getElementById('applicationId').textContent;
            formData.append('applicationId', applicationId);
            
            // Add signature data
            const signatureData = document.getElementById('signatureData').value;
            if (signatureData) {
                formData.append('signature', signatureData);
            }
            
            // Show loading state
            document.getElementById('submitApplication').disabled = true;
            
            // Submit form
            const response = await fetch('process.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (result.success) {
                // Create and show custom popup
                const popup = document.createElement('div');
                popup.className = 'custom-popup';
                popup.innerHTML = `
                    <div>Application submitted successfully!</div>
                    <button class="ok-button">OK</button>
                `;
                document.body.appendChild(popup);

                // Add click handler to OK button
                popup.querySelector('.ok-button').addEventListener('click', () => {
                    window.location.reload(); // Reload page when OK is clicked
                });
            } else {
                throw new Error(result.message);
            }
            
        } catch (error) {
            console.error('Submission error:', error);
            alert('Error submitting form: ' + error.message);
        } finally {
            document.getElementById('submitApplication').disabled = false;
        }
    }

    printApplication() {
        // Create print version of the form
        const printContent = document.createElement('div');
        printContent.classList.add('print-section');
        
        // Add logo and header
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
        
        // Add to document and print
        document.body.appendChild(printContent);
        window.print();
        document.body.removeChild(printContent);
    }

    getFormDataForPrint() {
        // Get all form data and format it for printing
        const formData = new FormData(this.form);
        let printHTML = '<div class="form-data">';
        
        formData.forEach((value, key) => {
            if (key !== 'signature') {
                printHTML += `
                    <div class="form-field">
                        <label>${key}:</label>
                        <span>${value}</span>
                    </div>
                `;
            }
        });
        
        // Add signature if exists
        const signatureData = document.getElementById('signatureData').value;
        if (signatureData) {
            printHTML += `
                <div class="signature-field">
                    <label>Signature:</label>
                    <img src="${signatureData}" alt="Signature" style="max-height: 100px;">
                </div>
            `;
        }
        
        printHTML += '</div>';
        return printHTML;
    }
}

// Add this function to generate application ID
function generateApplicationId() {
    const today = new Date();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const year = String(today.getFullYear()).slice(-2);
    
    // Get current count from localStorage
    let currentCount = parseInt(localStorage.getItem('applicationCount') || '0');
    currentCount++;
    
    // Reset count if it's a new day
    const currentDate = `${today.getFullYear()}-${month}-${today.getDate()}`;
    const lastDate = localStorage.getItem('lastApplicationDate');
    
    if (currentDate !== lastDate) {
        currentCount = 1;
        localStorage.setItem('lastApplicationDate', currentDate);
    }
    
    // Save the updated count
    localStorage.setItem('applicationCount', currentCount.toString());
    
    // Format counter with leading zeros
    const counterString = String(currentCount).padStart(3, '0');
    
    return `CODO/${month}${year}/${counterString}`;
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set current date
    const today = new Date();
    const currentDateElement = document.getElementById('currentDate');
    if (currentDateElement) {
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        currentDateElement.textContent = `${day}/${month}/${year}`;
    }

    // Generate and set application ID
    const appId = generateApplicationId();
    
    // Set the display text
    const applicationIdElement = document.getElementById('applicationId');
    if (applicationIdElement) {
        applicationIdElement.textContent = appId;
    }
    
    // Set the hidden input value
    const applicationIdInput = document.getElementById('applicationIdInput');
    if (applicationIdInput) {
        applicationIdInput.value = appId;
    }

    // Initialize signature pad
    const signaturePadElement = document.getElementById('signaturePad');
    if (signaturePadElement) {
        try {
            new SignaturePadHandler();
        } catch (error) {
            console.error('Error initializing SignaturePadHandler:', error);
        }
    }

    // Form handling
    const form = document.getElementById('admissionForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                const appIdValue = document.getElementById('applicationId')?.textContent || '';
                formData.set('applicationId', appIdValue);
                
                console.log('Submitting with Application ID:', appIdValue);
                
                const response = await fetch('process.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                console.log('Server response:', result);
                
                if (result.success) {
                    // Show custom popup
                    alert('Application submitted successfully!');
                    // Reload the page after clicking OK
                    window.location.reload();
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Submission error:', error);
                alert('Error submitting form: ' + error.message);
            }
        });
    }

    // Initialize form handler only if the form exists
    const admissionForm = document.getElementById('admissionForm');
    if (admissionForm) {
        new FormHandler();
    }
});