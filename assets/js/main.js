document.addEventListener('DOMContentLoaded', function() {
    // Original contact form submission (if any other form uses this ID)
    const genericContactForm = document.getElementById('contact-form');
    if (genericContactForm) {
        genericContactForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(genericContactForm);
            const response = await fetch('contact.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            const successMsg = genericContactForm.querySelector('#contact-success');
            const errorMsg = genericContactForm.querySelector('#contact-error');

            if (result.trim() === 'success') {
                genericContactForm.reset();
                if(successMsg) successMsg.style.display = 'block';
                if(errorMsg) errorMsg.style.display = 'none';
            } else {
                if(errorMsg) errorMsg.style.display = 'block';
                if(successMsg) successMsg.style.display = 'none';
            }
        });
    }

    // Business Inquiry Form Logic
    const businessForm = document.getElementById('business-inquiry-form');
    if (businessForm) {
        const businessTypeSelect = document.getElementById('business_type');
        const capstoneTypeGroup = document.getElementById('capstone_type_group');
        const capstoneTypeSelect = document.getElementById('capstone_type');
        const languageGroup = document.getElementById('language_group');
        const languageSelect = document.getElementById('language');
        const devFocusGroup = document.getElementById('dev_focus_group');
        const devFocusSelect = document.getElementById('dev_focus');
        const dbTypeGroup = document.getElementById('db_type_group');
        const dbTypeSelect = document.getElementById('db_type');

        const languages = {
            Website: ['HTML, CSS, JS, PHP', 'Laravel', 'ReactJS', 'Python FastAPI', 'CodeIgniter'],
            Gui: ['Python'],
            App: ['Kotlin', 'ReactNative']
        };

        function populateLanguages(type) {
            languageSelect.innerHTML = '<option value="" selected disabled>Select language...</option>'; // Reset
            if (languages[type]) {
                languages[type].forEach(lang => {
                    const option = document.createElement('option');
                    option.value = lang;
                    option.textContent = lang;
                    languageSelect.appendChild(option);
                });
                languageGroup.style.display = 'block';
            } else {
                languageGroup.style.display = 'none';
            }
        }

        businessTypeSelect.addEventListener('change', function() {
            const selectedBusinessType = this.value;
            capstoneTypeGroup.style.display = 'none';
            languageGroup.style.display = 'none';
            languageSelect.required = false;
            capstoneTypeSelect.required = false;

            if (selectedBusinessType === 'Capstone') {
                capstoneTypeGroup.style.display = 'block';
                capstoneTypeSelect.required = true;
                // If a capstone type is already selected, populate languages
                if(capstoneTypeSelect.value) {
                    populateLanguages(capstoneTypeSelect.value);
                    languageSelect.required = true;
                }
            } else if (selectedBusinessType === 'Project' || selectedBusinessType === 'Activity') {
                // For Project/Activity, we can directly show general web languages or keep it simple
                // Or, you can define specific languages for these too.
                // For now, let's assume they might need web languages as a common case.
                populateLanguages('Website'); // Default to web languages or create a 'General' category
                languageSelect.required = true;
            } else if (selectedBusinessType === 'Buy a system') {
                // No language selection needed for 'Buy a system' or specific logic
                languageGroup.style.display = 'none';
            }
        });

        capstoneTypeSelect.addEventListener('change', function() {
            const selectedCapstoneType = this.value;
            if (selectedCapstoneType) {
                populateLanguages(selectedCapstoneType);
                languageSelect.required = true;
            } else {
                languageGroup.style.display = 'none';
                languageSelect.required = false;
                devFocusGroup.style.display = 'none'; // Hide subsequent
                devFocusSelect.required = false;
                dbTypeGroup.style.display = 'none';   // Hide subsequent
                dbTypeSelect.required = false;
            }
        });

        languageSelect.addEventListener('change', function() {
            const selectedLanguage = this.value;
            dbTypeGroup.style.display = 'none'; // Reset DB type when language changes
            dbTypeSelect.required = false;

            if (selectedLanguage) {
                devFocusGroup.style.display = 'block';
                devFocusSelect.required = true;
                // If a dev focus is already selected, trigger its change event to show/hide DB
                if(devFocusSelect.value) {
                    devFocusSelect.dispatchEvent(new Event('change'));
                }
            } else {
                devFocusGroup.style.display = 'none';
                devFocusSelect.required = false;
                dbTypeGroup.style.display = 'none'; 
                dbTypeSelect.required = false;
            }
        });

        devFocusSelect.addEventListener('change', function() {
            const selectedFocus = this.value;
            if (selectedFocus === 'Backend' || selectedFocus === 'Both') {
                dbTypeGroup.style.display = 'block';
                dbTypeSelect.required = true;
            } else {
                dbTypeGroup.style.display = 'none';
                dbTypeSelect.required = false;
            }
        });

        // Handle business inquiry form submission
        businessForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const successMsg = businessForm.querySelector('#business-success-message');
            const errorMsg = businessForm.querySelector('#business-error-message');

            // Helper function to submit form data
            async function submitFormData() { // Removed latitude, longitude parameters
                if(successMsg) { // Simplified message logic
                    successMsg.textContent = 'Submitting inquiry...';
                    successMsg.style.display = 'block';
                }
                if(errorMsg) errorMsg.style.display = 'none';

                const userAgent = navigator.userAgent;
                const formData = new FormData(businessForm);

                // Latitude and Longitude are no longer sent
                formData.append('user_agent', userAgent); // user_agent is still useful

                // Add conditional fields if they are visible and have values
                if (capstoneTypeGroup.style.display !== 'none' && capstoneTypeSelect.value) {
                    formData.set('capstone_type', capstoneTypeSelect.value);
                }
                if (languageGroup.style.display !== 'none' && languageSelect.value) {
                    formData.set('language', languageSelect.value);
                }
                if (devFocusGroup.style.display !== 'none' && devFocusSelect.value) {
                    formData.set('dev_focus', devFocusSelect.value);
                }
                if (dbTypeGroup.style.display !== 'none' && dbTypeSelect.value) {
                    formData.set('db_type', dbTypeSelect.value);
                }

                try {
                    const response = await fetch('contact.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if (result.status === 'success') {
                        businessForm.reset();
                        // Reset dynamic fields visibility
                        capstoneTypeGroup.style.display = 'none';
                        languageGroup.style.display = 'none';
                        devFocusGroup.style.display = 'none';
                        dbTypeGroup.style.display = 'none';
                        if(successMsg) successMsg.textContent = 'Inquiry sent successfully! Thank you.';
                        if(successMsg) successMsg.style.display = 'block';
                        if(errorMsg) errorMsg.style.display = 'none';
                    } else {
                        if(errorMsg) errorMsg.textContent = result.message || 'An error occurred while submitting the inquiry.';
                        if(errorMsg) errorMsg.style.display = 'block';
                        if(successMsg) successMsg.style.display = 'none';
                        console.error('Form submission error:', result.message);
                    }
                } catch (err) {
                    console.error('Error submitting form or parsing JSON response:', err);
                    if(errorMsg) errorMsg.textContent = 'An unexpected error occurred. Please try again. Details: ' + err.message;
                    if(errorMsg) errorMsg.style.display = 'block';
                    if(successMsg) successMsg.style.display = 'none';
                }
            }
            if(successMsg) successMsg.style.display = 'none'; // Clear previous success message
            if(errorMsg) errorMsg.style.display = 'none';   // Clear previous error message

            // Directly call submitFormData without any geolocation logic
            await submitFormData();
        });
    }
});
