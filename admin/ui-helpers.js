/**
 * UI/UX Helper Functions
 * Toast notifications, loading states, and other UI improvements
 */

// Toast Notification System
const Toast = {
    show: function (message, type = 'info', duration = 3000) {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        // Add icon based on type
        let icon = '';
        switch (type) {
            case 'success':
                icon = '✓';
                break;
            case 'error':
                icon = '✕';
                break;
            case 'info':
                icon = 'ℹ';
                break;
        }

        toast.innerHTML = `
            <span style="font-size: 20px;">${icon}</span>
            <span>${message}</span>
        `;

        document.body.appendChild(toast);

        // Auto remove after duration
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },

    success: function (message, duration) {
        this.show(message, 'success', duration);
    },

    error: function (message, duration) {
        this.show(message, 'error', duration);
    },

    info: function (message, duration) {
        this.show(message, 'info', duration);
    }
};

// Loading Overlay
const Loading = {
    show: function () {
        // Remove existing overlay
        const existing = document.querySelector('.loading-overlay');
        if (existing) existing.remove();

        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = '<div class="loading-spinner"></div>';
        document.body.appendChild(overlay);
    },

    hide: function () {
        const overlay = document.querySelector('.loading-overlay');
        if (overlay) overlay.remove();
    }
};

// Confirm Dialog Enhancement
function confirmAction(message, callback) {
    if (confirm(message)) {
        Loading.show();
        callback();
    }
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Add fade-in animation to admin sections
    const sections = document.querySelectorAll('.admin-section');
    sections.forEach((section, index) => {
        section.style.opacity = '0';
        setTimeout(() => {
            section.classList.add('fade-in');
            section.style.opacity = '1';
        }, index * 100);
    });
});

// Form validation enhancement
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = '#ef4444';
            isValid = false;
        } else {
            input.style.borderColor = '#10b981';
        }
    });

    if (!isValid) {
        Toast.error('Mohon lengkapi semua field yang wajib diisi!');
    }

    return isValid;
}

// Add slideOutRight animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Export for use in other scripts
window.Toast = Toast;
window.Loading = Loading;
window.confirmAction = confirmAction;
window.validateForm = validateForm;
