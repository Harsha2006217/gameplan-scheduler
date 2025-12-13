/**
 * script.js - Client-Side Logic
 * Author: Harsha Kanaparthi
 * Date: 30-09-2025
 * Description: 
 * This file adds interactivity to the web pages. It handles two main things:
 * 1. Form Validation: Checking if user input is correct BEFORE sending it to the server.
 *    This gives instant feedback (e.g. "Email is wrong") without reloading the page.
 * 2. Reminders: Checking for upcoming events and showing alerts.
 */

// --- Login Form Validation ---
function validateLoginForm() {
    // Get values and remove outer spaces (trim)
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    // Check Empty
    if (!email || !password) {
        alert('Please fill in both email and password.');
        return false; // Stop form submission
    }

    // Check Email Format using a Regex (Regular Expression) pattern
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Please enter a valid email address (e.g. user@example.com).');
        return false;
    }

    return true; // Allow submission
}

// --- Register Form Validation ---
function validateRegisterForm() {
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    if (!username || !email || !password) {
        alert('All fields are required.');
        return false;
    }

    if (username.length > 50) {
        alert('Username is too long (max 50 characters).');
        return false;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Invalid email format.');
        return false;
    }

    if (password.length < 8) {
        alert('Password must be at least 8 characters for security.');
        return false;
    }

    return true;
}

// --- Schedule Form Validation ---
function validateScheduleForm() {
    const gameTitle = document.getElementById('game_title').value.trim();
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const friendsStr = document.getElementById('friends_str').value.trim();
    const sharedWithStr = document.getElementById('shared_with_str').value.trim();

    // Bug Fix Reference #1001: Explicitly check for empty strings after trim
    if (!gameTitle || /^\s*$/.test(gameTitle)) {
        alert('Game title is required and cannot be empty.');
        return false;
    }

    // Bug Fix Reference #1004: Ensure date is properly selected and in future
    if (!date) {
        alert('Please select a date.');
        return false;
    }

    const selectedDate = new Date(date);
    const today = new Date();
    // Reset time part to compare just dates
    today.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
        alert('Date must be in the future.');
        return false;
    }

    if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(time)) {
        alert('Invalid time format.');
        return false;
    }

    // Validation for comma separated lists (regex: alphanumeric, space, comma)
    if (friendsStr && !/^[a-zA-Z0-9,\s]*$/.test(friendsStr)) {
        alert('Friends list contains invalid characters. Use letters, numbers, and commas.');
        return false;
    }

    if (sharedWithStr && !/^[a-zA-Z0-9,\s]*$/.test(sharedWithStr)) {
        alert('Shared With list contains invalid characters.');
        return false;
    }

    return true;
}

// --- Event Form Validation ---
function validateEventForm() {
    const title = document.getElementById('title').value.trim();
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const description = document.getElementById('description').value;
    const externalLink = document.getElementById('external_link').value;

    if (!title || /^\s*$/.test(title)) {
        alert('Title is required.');
        return false;
    }

    if (title.length > 100) {
        alert('Title too long (max 100 characters).');
        return false;
    }

    if (!date) {
        alert('Please select a date.');
        return false;
    }

    // Check future date
    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
        alert('Date must be in the future.');
        return false;
    }

    if (description.length > 500) {
        alert('Description too long (max 500 characters).');
        return false;
    }

    // URL Validation
    if (externalLink && !/^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/.test(externalLink)) {
        alert('Invalid external link URL.');
        return false;
    }

    return true;
}

// --- Reminder System ---
document.addEventListener('DOMContentLoaded', function () {
    // This runs when the page loads
    console.log('GamePlan Scheduler: Ready');

    // Note: The actual reminder data array is injected via PHP in the footer/index page
    // Look for: const reminders = [...]; 
});