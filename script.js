// ============================================================================
// SCRIPT.JS - GamePlan Scheduler Client-Side JavaScript
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This file contains all client-side JavaScript for form validation,
// user interface interactions, and DOM manipulation.
//
// WHAT IS CLIENT-SIDE VALIDATION?
// Client-side validation runs in the browser BEFORE form submission.
// - Provides instant feedback to users
// - Reduces server load (invalid forms not submitted)
// - BUT: Must still validate on server (client can be bypassed)
//
// FUNCTIONS IN THIS FILE:
// 1. Form Validation Functions
//    - validateLoginForm()
//    - validateRegisterForm()
//    - validateScheduleForm()
//    - validateEventForm()
//
// 2. Helper Functions
//    - showError() - Display error message
//    - clearErrors() - Remove all error messages
//    - isEmptyOrWhitespace() - Check for empty/spaces only
//    - isValidEmail() - Check email format
//    - isValidDate() - Check date format and range
//    - isValidTime() - Check time format
//
// 3. UI Enhancement Functions
//    - initializeTooltips() - Bootstrap tooltips
//    - autoCloseAlerts() - Auto-dismiss alerts
//
// BUG FIXES APPLIED:
// - #1001: Empty/whitespace validation
// - #1004: Date format validation
// ============================================================================


// ============================================================================
// SECTION: HELPER FUNCTIONS
// ============================================================================
// These small utility functions are used by the validation functions.


/**
 * isEmptyOrWhitespace - Check if value is empty or only whitespace
 * 
 * BUG FIX #1001: Validates that input is not just spaces
 * 
 * HOW IT WORKS:
 * 1. trim() removes leading/trailing whitespace
 * 2. Check if result is empty string
 * 
 * EXAMPLES:
 * isEmptyOrWhitespace("") → true
 * isEmptyOrWhitespace("   ") → true
 * isEmptyOrWhitespace("Hello") → false
 * isEmptyOrWhitespace("  Hello  ") → false (has actual content)
 * 
 * @param {string} value - The value to check
 * @returns {boolean} - True if empty or whitespace only
 */
function isEmptyOrWhitespace(value) {
    // Handle null/undefined cases
    if (value === null || value === undefined) {
        return true;
    }

    // Convert to string (in case of numbers) and trim
    // Trim removes spaces, tabs, newlines from both ends
    return String(value).trim() === '';
}


/**
 * isValidEmail - Check if email format is valid
 * 
 * Uses a regular expression (regex) to validate email format.
 * 
 * REGEX EXPLANATION:
 * ^[^\s@]+  = Start with one or more chars that aren't space or @
 * @         = Must have @ symbol
 * [^\s@]+   = One or more chars that aren't space or @
 * \.        = Must have a dot
 * [^\s@]+$  = End with one or more chars that aren't space or @
 * 
 * VALID: user@example.com, name.surname@domain.co.uk
 * INVALID: user@, @domain.com, user @test.com
 * 
 * @param {string} email - The email to validate
 * @returns {boolean} - True if valid email format
 */
function isValidEmail(email) {
    // Regex pattern for basic email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // .test() returns true if pattern matches
    return emailPattern.test(email);
}


/**
 * isValidDate - Check if date is valid and in the future
 * 
 * BUG FIX #1004: Comprehensive date validation
 * 
 * VALIDATION STEPS:
 * 1. Check format matches YYYY-MM-DD
 * 2. Parse the date and check if it's a real date
 * 3. Check if date is today or in the future
 * 
 * @param {string} dateStr - Date string in YYYY-MM-DD format
 * @returns {object} - {valid: boolean, message: string}
 */
function isValidDate(dateStr) {
    // STEP 1: Check format with regex
    // \d{4} = 4 digits (year)
    // - = literal dash
    // \d{2} = 2 digits (month and day)
    const formatPattern = /^\d{4}-\d{2}-\d{2}$/;

    if (!formatPattern.test(dateStr)) {
        return {
            valid: false,
            message: 'Invalid date format. Use YYYY-MM-DD.'
        };
    }

    // STEP 2: Parse and validate actual date
    // Parse the date string
    const parts = dateStr.split('-');
    const year = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10);
    const day = parseInt(parts[2], 10);

    // Create a Date object (month is 0-indexed in JavaScript)
    const dateObj = new Date(year, month - 1, day);

    // Check if the date is valid by comparing components
    // Invalid dates like 2025-02-30 would give different values
    if (dateObj.getFullYear() !== year ||
        dateObj.getMonth() !== month - 1 ||
        dateObj.getDate() !== day) {
        return {
            valid: false,
            message: 'Invalid date. Please enter a real calendar date.'
        };
    }

    // STEP 3: Check if date is in the future
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Reset to start of day

    if (dateObj < today) {
        return {
            valid: false,
            message: 'Date must be today or in the future.'
        };
    }

    // All checks passed
    return { valid: true, message: '' };
}


/**
 * isValidTime - Check if time format is valid
 * 
 * Valid format: HH:MM (24-hour format)
 * 
 * @param {string} timeStr - Time string to validate
 * @returns {boolean} - True if valid format
 */
function isValidTime(timeStr) {
    // Regex for 24-hour time (00:00 to 23:59)
    // ([01]?[0-9]|2[0-3]) = Hours (0-23)
    // : = literal colon
    // [0-5][0-9] = Minutes (00-59)
    const timePattern = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;

    return timePattern.test(timeStr);
}


/**
 * showError - Display error message for a form field
 * 
 * Creates a red error message below the input field.
 * Also adds red border to the input.
 * 
 * @param {string} fieldId - ID of the input field
 * @param {string} message - Error message to display
 */
function showError(fieldId, message) {
    // Get the input field
    const field = document.getElementById(fieldId);

    if (!field) {
        console.error('Field not found:', fieldId);
        return;
    }

    // Add error styling to field
    field.classList.add('is-invalid');

    // Check if error message already exists
    let errorDiv = field.parentNode.querySelector('.invalid-feedback');

    if (!errorDiv) {
        // Create new error message element
        errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';

        // Insert after the input field
        field.parentNode.appendChild(errorDiv);
    }

    // Set the error message
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}


/**
 * clearErrors - Remove all error messages from form
 * 
 * Clears all red borders and error messages.
 * Called at the start of validation to reset state.
 */
function clearErrors() {
    // Remove is-invalid class from all elements
    const invalidFields = document.querySelectorAll('.is-invalid');
    invalidFields.forEach(field => {
        field.classList.remove('is-invalid');
    });

    // Hide all error messages
    const errorMessages = document.querySelectorAll('.invalid-feedback');
    errorMessages.forEach(msg => {
        msg.style.display = 'none';
    });
}


// ============================================================================
// SECTION: FORM VALIDATION FUNCTIONS
// ============================================================================
// Each form has its own validation function.
// These are called by onsubmit="return validateFormName();"
// Returning false prevents form submission.


/**
 * validateLoginForm - Validate login form
 * 
 * VALIDATES:
 * - Email: Not empty, valid format
 * - Password: Not empty
 * 
 * @returns {boolean} - True if valid, false to prevent submission
 */
function validateLoginForm() {
    // Clear any previous errors
    clearErrors();

    let isValid = true;

    // Get form values
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;


    // VALIDATE EMAIL
    if (isEmptyOrWhitespace(email)) {
        showError('email', 'Email is required.');
        isValid = false;
    } else if (!isValidEmail(email)) {
        showError('email', 'Please enter a valid email address.');
        isValid = false;
    }


    // VALIDATE PASSWORD
    if (isEmptyOrWhitespace(password)) {
        showError('password', 'Password is required.');
        isValid = false;
    }


    // If not valid, prevent form submission
    return isValid;
}


/**
 * validateRegisterForm - Validate registration form
 * 
 * VALIDATES:
 * - Username: Not empty, max 50 characters
 * - Email: Not empty, valid format
 * - Password: Not empty, minimum 8 characters
 * 
 * @returns {boolean} - True if valid, false to prevent submission
 */
function validateRegisterForm() {
    clearErrors();
    let isValid = true;

    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;


    // VALIDATE USERNAME
    if (isEmptyOrWhitespace(username)) {
        showError('username', 'Username is required.');
        isValid = false;
    } else if (username.trim().length > 50) {
        showError('username', 'Username must be 50 characters or less.');
        isValid = false;
    }


    // VALIDATE EMAIL
    if (isEmptyOrWhitespace(email)) {
        showError('email', 'Email is required.');
        isValid = false;
    } else if (!isValidEmail(email)) {
        showError('email', 'Please enter a valid email address.');
        isValid = false;
    }


    // VALIDATE PASSWORD
    if (isEmptyOrWhitespace(password)) {
        showError('password', 'Password is required.');
        isValid = false;
    } else if (password.length < 8) {
        showError('password', 'Password must be at least 8 characters.');
        isValid = false;
    }


    return isValid;
}


/**
 * validateScheduleForm - Validate schedule creation/edit form
 * 
 * VALIDATES:
 * - Game Title: Not empty (Bug Fix #1001)
 * - Date: Valid format and in future (Bug Fix #1004)
 * - Time: Valid format
 * 
 * @returns {boolean} - True if valid, false to prevent submission
 */
function validateScheduleForm() {
    clearErrors();
    let isValid = true;

    const gameTitle = document.getElementById('game_title').value;
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;


    // VALIDATE GAME TITLE (Bug Fix #1001)
    if (isEmptyOrWhitespace(gameTitle)) {
        showError('game_title', 'Game title is required and cannot be just spaces.');
        isValid = false;
    } else if (gameTitle.trim().length > 100) {
        showError('game_title', 'Game title must be 100 characters or less.');
        isValid = false;
    }


    // VALIDATE DATE (Bug Fix #1004)
    if (isEmptyOrWhitespace(date)) {
        showError('date', 'Date is required.');
        isValid = false;
    } else {
        const dateResult = isValidDate(date);
        if (!dateResult.valid) {
            showError('date', dateResult.message);
            isValid = false;
        }
    }


    // VALIDATE TIME
    if (isEmptyOrWhitespace(time)) {
        showError('time', 'Time is required.');
        isValid = false;
    } else if (!isValidTime(time)) {
        showError('time', 'Please enter a valid time (HH:MM).');
        isValid = false;
    }


    return isValid;
}


/**
 * validateEventForm - Validate event creation/edit form
 * 
 * VALIDATES:
 * - Title: Not empty (Bug Fix #1001)
 * - Date: Valid format and in future (Bug Fix #1004)
 * - Time: Valid format
 * - External Link (optional): Valid URL format
 * 
 * @returns {boolean} - True if valid, false to prevent submission
 */
function validateEventForm() {
    clearErrors();
    let isValid = true;

    const title = document.getElementById('title').value;
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const externalLink = document.getElementById('external_link')?.value || '';


    // VALIDATE TITLE (Bug Fix #1001)
    if (isEmptyOrWhitespace(title)) {
        showError('title', 'Event title is required and cannot be just spaces.');
        isValid = false;
    } else if (title.trim().length > 100) {
        showError('title', 'Title must be 100 characters or less.');
        isValid = false;
    }


    // VALIDATE DATE (Bug Fix #1004)
    if (isEmptyOrWhitespace(date)) {
        showError('date', 'Date is required.');
        isValid = false;
    } else {
        const dateResult = isValidDate(date);
        if (!dateResult.valid) {
            showError('date', dateResult.message);
            isValid = false;
        }
    }


    // VALIDATE TIME
    if (isEmptyOrWhitespace(time)) {
        showError('time', 'Time is required.');
        isValid = false;
    } else if (!isValidTime(time)) {
        showError('time', 'Please enter a valid time (HH:MM).');
        isValid = false;
    }


    // VALIDATE EXTERNAL LINK (Optional, but must be valid if provided)
    if (!isEmptyOrWhitespace(externalLink)) {
        try {
            // URL constructor throws if invalid URL
            new URL(externalLink);
        } catch (e) {
            showError('external_link', 'Please enter a valid URL (include http:// or https://).');
            isValid = false;
        }
    }


    return isValid;
}


// ============================================================================
// SECTION: UI ENHANCEMENT FUNCTIONS
// ============================================================================
// Functions that improve the user interface experience.


/**
 * initializeTooltips - Initialize Bootstrap tooltips
 * 
 * Tooltips show helpful text when hovering over elements.
 * Requires data-bs-toggle="tooltip" attribute on elements.
 */
function initializeTooltips() {
    // Get all elements with data-bs-toggle="tooltip"
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');

    // Create tooltip instances
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
}


/**
 * autoCloseAlerts - Automatically close alert messages after delay
 * 
 * Success messages don't need to stay forever.
 * This automatically dismisses them after 5 seconds.
 */
function autoCloseAlerts() {
    // Get all alert-success elements
    const alerts = document.querySelectorAll('.alert-success');

    alerts.forEach(function (alert) {
        // Set timeout to close after 5 seconds (5000 milliseconds)
        setTimeout(function () {
            // Use Bootstrap's close method
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000);
    });
}


/**
 * setMinDateToToday - Set minimum date on date inputs to today
 * 
 * Prevents users from selecting past dates.
 * Called when page loads.
 */
function setMinDateToToday() {
    // Get today's date in YYYY-MM-DD format
    const today = new Date().toISOString().split('T')[0];

    // Find all date inputs
    const dateInputs = document.querySelectorAll('input[type="date"]');

    // Set minimum date to today
    dateInputs.forEach(function (input) {
        // Only set if not already set (preserve existing min dates)
        if (!input.min) {
            input.min = today;
        }
    });
}


// ============================================================================
// SECTION: PAGE INITIALIZATION
// ============================================================================
// Code that runs when the page loads.


/**
 * DOMContentLoaded Event Handler
 * 
 * This code runs when the HTML document has been completely loaded.
 * This is the RIGHT time to initialize UI components.
 */
document.addEventListener('DOMContentLoaded', function () {

    // Initialize Bootstrap tooltips
    initializeTooltips();

    // Auto-close success alerts after 5 seconds
    autoCloseAlerts();

    // Set minimum date on date inputs
    setMinDateToToday();

    // Log that script loaded successfully (for debugging)
    console.log('🎮 GamePlan Scheduler: JavaScript loaded successfully!');

});


// ============================================================================
// END OF SCRIPT
// ============================================================================
//
// SUMMARY OF FEATURES:
// ✓ Login form validation
// ✓ Registration form validation
// ✓ Schedule form validation with date/time checks
// ✓ Event form validation with URL check
// ✓ Empty/whitespace check (Bug Fix #1001)
// ✓ Date format and range validation (Bug Fix #1004)
// ✓ Bootstrap tooltip initialization
// ✓ Auto-close success alerts
//
// HOW VALIDATION WORKS:
// 1. User fills form and clicks submit
// 2. onsubmit="return validateFormName();" calls validation
// 3. Validation checks all fields
// 4. If any errors: shows messages, returns false (stops submit)
// 5. If all valid: returns true (form submits to server)
// 6. Server-side validation runs as final check
//
// © 2025 GamePlan Scheduler by Harsha Kanaparthi
// ============================================================================