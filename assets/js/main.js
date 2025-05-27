/**
 * TalentBridge - Main JavaScript File
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Skills selection functionality
    const skillsContainer = document.getElementById('skills-container');
    const skillsInput = document.getElementById('skills-input');
    const skillsHiddenInput = document.getElementById('skills');
    
    if (skillsContainer && skillsInput && skillsHiddenInput) {
        // Add skill when pressing Enter
        skillsInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && this.value.trim() !== '') {
                e.preventDefault();
                addSkill(this.value.trim());
                this.value = '';
                updateSkillsHiddenInput();
            }
        });
        
        // Function to add a skill tag
        function addSkill(skill) {
            const skillTag = document.createElement('span');
            skillTag.classList.add('skill-tag', 'me-2', 'mb-2');
            skillTag.innerHTML = skill + ' <i class="fas fa-times-circle"></i>';
            
            skillTag.querySelector('i').addEventListener('click', function() {
                skillTag.remove();
                updateSkillsHiddenInput();
            });
            
            skillsContainer.appendChild(skillTag);
        }
        
        // Update hidden input with all skills
        function updateSkillsHiddenInput() {
            const skills = [];
            document.querySelectorAll('.skill-tag').forEach(tag => {
                skills.push(tag.textContent.trim().replace(' ×', ''));
            });
            skillsHiddenInput.value = JSON.stringify(skills);
        }
        
        // Initialize skills from hidden input if it has a value
        if (skillsHiddenInput.value) {
            try {
                const skills = JSON.parse(skillsHiddenInput.value);
                skills.forEach(skill => addSkill(skill));
            } catch (e) {
                console.error('Error parsing skills:', e);
            }
        }
    }
    
    // Job application form validation
    const applicationForm = document.getElementById('application-form');
    if (applicationForm) {
        applicationForm.addEventListener('submit', function(e) {
            const messageField = document.getElementById('application-message');
            if (messageField && messageField.value.trim().length < 10) {
                e.preventDefault();
                alert('Please provide a more detailed application message (at least 10 characters).');
            }
        });
    }
    
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const passwordStrength = document.getElementById('password-strength');
    
    if (passwordInput && passwordStrength) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 8) strength += 1;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
            if (password.match(/\d/)) strength += 1;
            if (password.match(/[^a-zA-Z\d]/)) strength += 1;
            
            switch (strength) {
                case 0:
                    passwordStrength.innerHTML = '';
                    break;
                case 1:
                    passwordStrength.innerHTML = '<div class="progress-bar bg-danger" style="width: 25%">Weak</div>';
                    break;
                case 2:
                    passwordStrength.innerHTML = '<div class="progress-bar bg-warning" style="width: 50%">Fair</div>';
                    break;
                case 3:
                    passwordStrength.innerHTML = '<div class="progress-bar bg-info" style="width: 75%">Good</div>';
                    break;
                case 4:
                    passwordStrength.innerHTML = '<div class="progress-bar bg-success" style="width: 100%">Strong</div>';
                    break;
            }
        });
    }
    
    // Confirm password validation
    const confirmPasswordInput = document.getElementById('confirm-password');
    if (passwordInput && confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});
