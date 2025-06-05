document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // Adjust for navbar height
                    behavior: 'smooth'
                });
                
                // Close navbar collapse on mobile
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                    document.querySelector('.navbar-toggler').click();
                }
            }
        });
    });
    
    // Navbar animation on scroll
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.style.padding = '12px 0';
                navbar.style.boxShadow = '0 4px 10px rgba(0,0,0,0.05)';
            } else {
                navbar.style.padding = '16px 0';
                navbar.style.boxShadow = 'none';
            }
        });
    }
    
    // Initialize carousel with controls
    const modelCarousel = document.getElementById('avatarCarousel');
    if (modelCarousel) {
        // Pause auto rotation when interacting with model viewer
        const modelViewers = document.querySelectorAll('model-viewer');
        modelViewers.forEach(viewer => {
            viewer.addEventListener('camera-change', () => {
                // Add a class to track user interaction
                viewer.classList.add('user-interacting');
            });
        });
        
        // Add keyboard navigation for carousel
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                const prevButton = document.querySelector('[data-bs-slide="prev"]');
                if (prevButton) prevButton.click();
            } else if (e.key === 'ArrowRight') {
                const nextButton = document.querySelector('[data-bs-slide="next"]');
                if (nextButton) nextButton.click();
            }
        });
    }
    
    // Animation on scroll
    const animateElements = document.querySelectorAll('.animate-on-scroll');
    
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-active');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    animateElements.forEach(el => {
        observer.observe(el);
    });
    
    // Handle form submissions
    
    // Generic contact form
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
    
    // Business inquiry form
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
            languageSelect.innerHTML = '<option value="" selected disabled>Select language...</option>';
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

                if(capstoneTypeSelect.value) {
                    populateLanguages(capstoneTypeSelect.value);
                    languageSelect.required = true;
                }
            } else if (selectedBusinessType === 'Project' || selectedBusinessType === 'Activity') {
                populateLanguages('Website');
                languageSelect.required = true;
            } else if (selectedBusinessType === 'Buy a system') {
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
                devFocusGroup.style.display = 'none';
                devFocusSelect.required = false;
                dbTypeGroup.style.display = 'none';
                dbTypeSelect.required = false;
            }
        });

        languageSelect.addEventListener('change', function() {
            const selectedLanguage = this.value;
            dbTypeGroup.style.display = 'none';
            dbTypeSelect.required = false;

            if (selectedLanguage) {
                devFocusGroup.style.display = 'block';
                devFocusSelect.required = true;

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

        businessForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const successMsg = businessForm.querySelector('#business-success-message');
            const errorMsg = businessForm.querySelector('#business-error-message');

            async function submitFormData() {
                if(successMsg) {
                    successMsg.textContent = 'Submitting inquiry...';
                    successMsg.style.display = 'block';
                }
                if(errorMsg) errorMsg.style.display = 'none';

                const userAgent = navigator.userAgent;
                const formData = new FormData(businessForm);

                formData.append('user_agent', userAgent);

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
            if(successMsg) successMsg.style.display = 'none';
            if(errorMsg) errorMsg.style.display = 'none';

            await submitFormData();
        });
    }
    
    // Project filtering functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectItems = document.querySelectorAll('.project-item');
    
    if (filterButtons.length > 0 && projectItems.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filterValue = this.getAttribute('data-filter');
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter projects
                projectItems.forEach(item => {
                    if (filterValue === 'all') {
                        item.style.display = 'block';
                    } else if (item.classList.contains(filterValue)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    }
});
