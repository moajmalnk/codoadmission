// Function to get the last application ID from the database
async function getLastApplicationId() {
    try {
        const response = await fetch('get_last_id.php');
        const data = await response.json();
        return data.lastId || null;
    } catch (error) {
        console.error('Error fetching last ID:', error);
        return null;
    }
}

// Function to generate next application ID
async function generateApplicationId() {
    const today = new Date();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const year = String(today.getFullYear()).slice(-2);
    
    // Get last application ID from database
    const lastId = await getLastApplicationId();
    let nextNumber = 1;
    
    if (lastId) {
        // Extract the number from last ID (e.g., "CODO/0325/001" -> 1)
        const lastNumber = parseInt(lastId.split('/')[2]);
        nextNumber = lastNumber + 1;
    }
    
    // Format number with leading zeros
    const formattedNumber = String(nextNumber).padStart(3, '0');
    return `CODO/${month}${year}/${formattedNumber}`;
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', async function() {
    // Set current date
    const today = new Date();
    const day = String(today.getDate()).padStart(2, '0');
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const year = today.getFullYear();
    
    const currentDate = document.getElementById('currentDate');
    if (currentDate) {
        currentDate.textContent = `${day}/${month}/${year}`;
    }

    // Generate and set application ID
    const appId = await generateApplicationId();
    console.log('Generated App ID:', appId);

    // Set application ID in both display and hidden input
    const applicationIdDisplay = document.getElementById('applicationId');
    const applicationIdInput = document.getElementById('applicationIdInput');
    
    if (applicationIdDisplay) {
        applicationIdDisplay.textContent = appId;
    }
    
    if (applicationIdInput) {
        applicationIdInput.value = appId;
    }

    // Form submission handler
    const form = document.getElementById('admissionForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Create FormData and explicitly add application ID
            const formData = new FormData(this);
            formData.set('applicationId', appId);
            
            // Debug log
            console.log('Submitting form with App ID:', appId);
            
            try {
                const response = await fetch('process.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                console.log('Server response:', result);

                if (result.success) {
                    // Show success message or modal
                    alert('Application submitted successfully!');
                    // Or show your modal:
                    // const modal = new bootstrap.Modal(document.getElementById('thankYouModal'));
                    // modal.show();
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Submission error:', error);
                alert('Error submitting form: ' + error.message);
            }
        });
    }
}); 