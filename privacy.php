<?php
// ============================================================================
// PRIVACY.PHP - Privacy Policy Page
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This page displays the Privacy Policy for GamePlan Scheduler.
// Required by law (GDPR/AVG in EU) when collecting user data.
//
// PRIVACY PRINCIPLES:
// - Minimal data collection (only what's needed)
// - Secure storage (hashed passwords, encrypted connections)
// - User control (can delete own data)
// - No sharing with third parties
// - Transparency (this page explains everything)
// ============================================================================

require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - GamePlan Scheduler</title>
    <meta name="description" content="Privacy policy for GamePlan Scheduler - learn how we protect your data.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">

    <!-- Simple header for non-logged-in users -->
    <header class="bg-primary py-3">
        <div class="container">
            <a href="index.php" class="text-white text-decoration-none h4">
                🎮 GamePlan Scheduler
            </a>
        </div>
    </header>


    <!-- Main content -->
    <main class="container py-5">

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Page title -->
                <h1 class="h2 fw-bold mb-4">🔒 Privacy Policy</h1>
                <p class="text-muted mb-4">Last updated: September 30, 2025</p>


                <!-- Privacy content card -->
                <div class="card bg-secondary border-0 rounded-4 shadow">
                    <div class="card-body p-4">

                        <!-- Section 1: Introduction -->
                        <section class="mb-4">
                            <h2 class="h5 fw-bold text-info">1. Introduction</h2>
                            <p>
                                Welcome to GamePlan Scheduler. This privacy policy explains how we collect,
                                use, and protect your personal information when you use our gaming schedule
                                management application.
                            </p>
                            <p>
                                We are committed to protecting your privacy and ensuring that your personal
                                data is handled responsibly and transparently.
                            </p>
                        </section>


                        <!-- Section 2: Data We Collect -->
                        <section class="mb-4">
                            <h2 class="h5 fw-bold text-info">2. Data We Collect</h2>
                            <p>We collect only the minimum data necessary to provide our services:</p>
                            <ul>
                                <li><strong>Account Information:</strong> Username, email address</li>
                                <li><strong>Profile Data:</strong> Favorite games, personal notes</li>
                                <li><strong>Friends List:</strong> Friend usernames and notes</li>
                                <li><strong>Schedules:</strong> Gaming session plans with dates and times</li>
                                <li><strong>Events:</strong> Tournament and event information</li>
                            </ul>
                            <div class="alert alert-success mt-3">
                                <strong>✓ No tracking cookies</strong><br>
                                <strong>✓ No analytics</strong><br>
                                <strong>✓ No advertising</strong>
                            </div>
                        </section>


                        <!-- Section 3: How We Use Your Data -->
                        <section class="mb-4">
                            <h2 class="h5 fw-bold text-info">3. How We Use Your Data</h2>
                            <p>Your data is used only to:</p>
                            <ul>
                                <li>Provide the scheduling and planning features you request</li>
                                <li>Allow you to share schedules with friends</li>
                                <li>Send reminders for your events (when enabled)</li>
                            </ul>
                            <p class="text-warning">
                                <strong>We do NOT:</strong> Sell your data, share it with advertisers,
                                or use it for marketing purposes.
                            </p>
                        </section>


                        <!-- Section 4: Data Security -->
                        <section class="mb-4">
                            <h2 class="h5 fw-bold text-info">4. Data Security</h2>
                            <p>We take security seriously:</p>
                            <ul>
                                <li>
                                    <strong>Password Hashing:</strong> Your password is never stored in plain text.
                                    We use bcrypt hashing which is virtually impossible to reverse.
                                </li>
                                <li>
                                    <strong>Secure Connections:</strong> We recommend using HTTPS for all connections.
                                </li>
                                <li>
                                    <strong>Session Security:</strong> Sessions expire after 30 minutes of inactivity.
                                </li>
                                <li>
                                    <strong>SQL Injection Protection:</strong> All database queries use prepared
                                    statements.
                                </li>
                            </ul>
                        </section>


                        <!-- Section 5: Your Rights -->
                        <section class="mb-4">
                            <h2 class="h5 fw-bold text-info">5. Your Rights</h2>
                            <p>Under GDPR (AVG in Dutch), you have the right to:</p>
                            <ul>
                                <li><strong>Access:</strong> View all your personal data</li>
                                <li><strong>Rectification:</strong> Correct any inaccurate information</li>
                                <li><strong>Erasure:</strong> Delete your account and all associated data</li>
                                <li><strong>Portability:</strong> Request a copy of your data</li>
                                <li><strong>Objection:</strong> Object to how we process your data</li>
                            </ul>
                            <p>
                                To exercise these rights, please contact us using the
                                <a href="contact.php" class="text-info">contact page</a>.
                            </p>
                        </section>


                        <!-- Section 6: Data Retention -->
                        <section class="mb-4">
                            <h2 class="h5 fw-bold text-info">6. Data Retention</h2>
                            <p>
                                We keep your data as long as your account is active. If you delete your
                                account, all your personal data will be removed within 30 days.
                            </p>
                            <p>
                                Deleted schedules, events, and friends are "soft deleted" (marked as deleted)
                                for 30 days before permanent removal, allowing for recovery if needed.
                            </p>
                        </section>


                        <!-- Section 7: Contact -->
                        <section>
                            <h2 class="h5 fw-bold text-info">7. Contact Us</h2>
                            <p>
                                If you have questions about this privacy policy or your data, please
                                contact us:
                            </p>
                            <p>
                                📧 Email: <a href="mailto:privacy@gameplanscheduler.com"
                                    class="text-info">privacy@gameplanscheduler.com</a><br>
                                📝 Contact Form: <a href="contact.php" class="text-info">Contact Page</a>
                            </p>
                        </section>

                    </div>
                </div>


                <!-- Back link -->
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-outline-light">
                        ← Back to Home
                    </a>
                </div>

            </div>
        </div>

    </main>


    <!-- Simple footer -->
    <footer class="bg-dark text-center py-3 mt-5 border-top border-secondary">
        <p class="small text-muted mb-0">
            © 2025 GamePlan Scheduler by Harsha Kanaparthi
        </p>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>