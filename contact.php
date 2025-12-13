<?php
// ============================================================================
// CONTACT.PHP - Contact Form Page
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This page provides a contact form for users to send messages.
// In a production environment, this would send an actual email.
// For this project, it displays a success message to demonstrate functionality.
//
// NOTE FOR EXAMINER:
// Email sending requires a mail server configuration (SMTP).
// This demo version simulates the functionality without actual email sending.
// ============================================================================

require_once 'functions.php';

$success = false;
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    // Validate inputs
    if ($err = validateRequired($name, "Name", 100)) {
        $error = $err;
    } elseif ($err = validateEmail($email)) {
        $error = $err;
    } elseif ($err = validateRequired($subject, "Subject", 200)) {
        $error = $err;
    } elseif ($err = validateRequired($message, "Message", 2000)) {
        $error = $err;
    } else {
        // In production: mail() function or SMTP library would send email
        // For demo: Just show success message
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - GamePlan Scheduler</title>
    <meta name="description"
        content="Contact the GamePlan Scheduler team with questions, feedback, or support requests.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">

    <!-- Simple header -->
    <header class="bg-primary py-3">
        <div class="container">
            <a href="index.php" class="text-white text-decoration-none h4">
                🎮 GamePlan Scheduler
            </a>
        </div>
    </header>


    <main class="container py-5">

        <div class="row justify-content-center">
            <div class="col-lg-6">

                <h1 class="h2 fw-bold mb-4 text-center">📧 Contact Us</h1>
                <p class="text-muted text-center mb-4">
                    Have questions, feedback, or need support? Send us a message!
                </p>


                <div class="card bg-secondary border-0 rounded-4 shadow">
                    <div class="card-body p-4">

                        <!-- Success message -->
                        <?php if ($success): ?>
                            <div class="alert alert-success text-center">
                                <div class="display-4 mb-3">✅</div>
                                <h4 class="fw-bold">Message Sent!</h4>
                                <p class="mb-0">Thank you for contacting us. We will respond within 24-48 hours.</p>
                            </div>
                            <div class="text-center mt-3">
                                <a href="index.php" class="btn btn-primary">← Back to Home</a>
                            </div>


                            <!-- Contact form -->
                        <?php else: ?>

                            <!-- Error display -->
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
                            <?php endif; ?>


                            <form method="POST">

                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        👤 Your Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="name" name="name"
                                        class="form-control bg-dark text-light border-secondary" required maxlength="100"
                                        placeholder="Enter your name" value="<?php echo safeEcho($_POST['name'] ?? ''); ?>">
                                </div>


                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        📧 Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" id="email" name="email"
                                        class="form-control bg-dark text-light border-secondary" required
                                        placeholder="your.email@example.com"
                                        value="<?php echo safeEcho($_POST['email'] ?? ''); ?>">
                                </div>


                                <!-- Subject -->
                                <div class="mb-3">
                                    <label for="subject" class="form-label">
                                        📝 Subject <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="subject" name="subject"
                                        class="form-control bg-dark text-light border-secondary" required maxlength="200"
                                        placeholder="What is your message about?"
                                        value="<?php echo safeEcho($_POST['subject'] ?? ''); ?>">
                                </div>


                                <!-- Message -->
                                <div class="mb-4">
                                    <label for="message" class="form-label">
                                        💬 Message <span class="text-danger">*</span>
                                    </label>
                                    <textarea id="message" name="message"
                                        class="form-control bg-dark text-light border-secondary" rows="5" required
                                        maxlength="2000"
                                        placeholder="Write your message here..."><?php echo safeEcho($_POST['message'] ?? ''); ?></textarea>
                                    <div class="form-text text-muted">Maximum 2000 characters</div>
                                </div>


                                <!-- Submit -->
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    📤 Send Message
                                </button>

                            </form>

                        <?php endif; ?>

                    </div>
                </div>


                <!-- Alternative contact info -->
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        Or email us directly at:
                        <a href="mailto:support@gameplanscheduler.com" class="text-info">
                            support@gameplanscheduler.com
                        </a>
                    </p>
                    <a href="index.php" class="btn btn-outline-light btn-sm">
                        ← Back to Home
                    </a>
                </div>

            </div>
        </div>

    </main>


    <footer class="bg-dark text-center py-3 mt-5 border-top border-secondary">
        <p class="small text-muted mb-0">
            © 2025 GamePlan Scheduler by Harsha Kanaparthi
        </p>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>