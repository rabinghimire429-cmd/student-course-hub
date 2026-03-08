<?php
require_once 'config.php';
require_once 'db.php';

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$programme_id = isset($_GET['programme_id']) ? (int)$_GET['programme_id'] : 0;

// Fetch programmes for dropdown
$programmes = $pdo->query("SELECT ProgrammeID, ProgrammeName FROM Programmes WHERE is_published = 1 ORDER BY ProgrammeName")->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 1) {
        // Store in session
        session_start();
        $_SESSION['application'] = [
            'programme_id' => (int)$_POST['programme_id'],
            'start_date' => $_POST['start_date']
        ];
        header('Location: apply.php?step=2');
        exit;
    } elseif ($step === 2) {
        session_start();
        $_SESSION['application']['personal'] = [
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'dob' => $_POST['dob'],
            'nationality' => $_POST['nationality']
        ];
        header('Location: apply.php?step=3');
        exit;
    } elseif ($step === 3) {
        session_start();
        $_SESSION['application']['education'] = [
            'highest_qualification' => $_POST['highest_qualification'],
            'institution' => $_POST['institution'],
            'graduation_year' => $_POST['graduation_year'],
            'grades' => $_POST['grades']
        ];
        header('Location: apply.php?step=4');
        exit;
    } elseif ($step === 4) {
        session_start();
        $_SESSION['application']['review'] = true;
        header('Location: apply.php?step=5');
        exit;
    } elseif ($step === 5) {
        session_start();
        // Save to database
        try {
            $app = $_SESSION['application'];
            $stmt = $pdo->prepare("
                INSERT INTO applications 
                (programme_id, full_name, email, phone, dob, nationality, 
                 highest_qualification, institution, graduation_year, grades, start_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $app['programme_id'],
                $app['personal']['full_name'],
                $app['personal']['email'],
                $app['personal']['phone'],
                $app['personal']['dob'],
                $app['personal']['nationality'],
                $app['education']['highest_qualification'],
                $app['education']['institution'],
                $app['education']['graduation_year'],
                $app['education']['grades'],
                $app['start_date']
            ]);
            
            $application_id = $pdo->lastInsertId();
            
            // Clear session
            unset($_SESSION['application']);
            
            $success = "Application submitted successfully! Your application ID is: " . $application_id;
        } catch (PDOException $e) {
            $error = "Error submitting application: " . $e->getMessage();
        }
    }
}

include 'header.php';
?>

<div class="container my-5 pt-5">
    <h1 class="text-center mb-4">Apply to Bluebird College</h1>
    
    <!-- Progress Steps -->
    <div class="progress mb-5" style="height: 30px;">
        <?php
        $progress = ($step / 5) * 100;
        ?>
        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?= $progress ?>%;">
            Step <?= $step ?> of 5
        </div>
    </div>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
        <div class="text-center">
            <a href="index.php" class="btn btn-primary">Return to Home</a>
        </div>
    <?php else: ?>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <?php
                        $titles = ['Choose Programme', 'Personal Information', 'Education History', 'Review', 'Confirmation'];
                        echo $titles[$step - 1];
                        ?>
                    </h4>
                </div>
                <div class="card-body">
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <?php if ($step === 1): ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Select Programme *</label>
                                <select name="programme_id" class="form-select" required>
                                    <option value="">Choose a programme...</option>
                                    <?php foreach ($programmes as $p): ?>
                                        <option value="<?= $p['ProgrammeID'] ?>" <?= $programme_id == $p['ProgrammeID'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($p['ProgrammeName']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Preferred Start Date *</label>
                                <select name="start_date" class="form-select" required>
                                    <option value="September 2024">September 2024</option>
                                    <option value="January 2025">January 2025</option>
                                    <option value="September 2025">September 2025</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Continue</button>
                        </form>
                    
                    <?php elseif ($step === 2): ?>
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="full_name" class="form-control" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone *</label>
                                    <input type="tel" name="phone" class="form-control" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date of Birth *</label>
                                    <input type="date" name="dob" class="form-control" required>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Nationality *</label>
                                    <input type="text" name="nationality" class="form-control" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Continue</button>
                            <a href="apply.php?step=1" class="btn btn-secondary">Back</a>
                        </form>
                    
                    <?php elseif ($step === 3): ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Highest Qualification *</label>
                                <select name="highest_qualification" class="form-select" required>
                                    <option value="">Select...</option>
                                    <option value="A-Levels">A-Levels</option>
                                    <option value="IB">International Baccalaureate</option>
                                    <option value="Bachelor's Degree">Bachelor's Degree</option>
                                    <option value="Master's Degree">Master's Degree</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Institution Name *</label>
                                <input type="text" name="institution" class="form-control" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Graduation Year *</label>
                                    <input type="number" name="graduation_year" class="form-control" min="1990" max="2030" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Grades/Achievements</label>
                                    <input type="text" name="grades" class="form-control" placeholder="e.g., AAB, 2:1">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Continue</button>
                            <a href="apply.php?step=2" class="btn btn-secondary">Back</a>
                        </form>
                    
                    <?php elseif ($step === 4): ?>
                        <?php
                        session_start();
                        $app = $_SESSION['application'] ?? [];
                        $prog = $pdo->prepare("SELECT ProgrammeName FROM Programmes WHERE ProgrammeID = ?");
                        $prog->execute([$app['programme_id'] ?? 0]);
                        $programme_name = $prog->fetchColumn();
                        ?>
                        
                        <div class="review-section">
                            <h5>Programme</h5>
                            <p><strong>Programme:</strong> <?= htmlspecialchars($programme_name) ?></p>
                            <p><strong>Start Date:</strong> <?= htmlspecialchars($app['start_date'] ?? '') ?></p>
                            
                            <hr>
                            
                            <h5>Personal Information</h5>
                            <p><strong>Name:</strong> <?= htmlspecialchars($app['personal']['full_name'] ?? '') ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($app['personal']['email'] ?? '') ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($app['personal']['phone'] ?? '') ?></p>
                            <p><strong>Date of Birth:</strong> <?= htmlspecialchars($app['personal']['dob'] ?? '') ?></p>
                            <p><strong>Nationality:</strong> <?= htmlspecialchars($app['personal']['nationality'] ?? '') ?></p>
                            
                            <hr>
                            
                            <h5>Education</h5>
                            <p><strong>Qualification:</strong> <?= htmlspecialchars($app['education']['highest_qualification'] ?? '') ?></p>
                            <p><strong>Institution:</strong> <?= htmlspecialchars($app['education']['institution'] ?? '') ?></p>
                            <p><strong>Graduation Year:</strong> <?= htmlspecialchars($app['education']['graduation_year'] ?? '') ?></p>
                            <p><strong>Grades:</strong> <?= htmlspecialchars($app['education']['grades'] ?? '') ?></p>
                        </div>
                        
                        <form method="POST" class="mt-4">
                            <button type="submit" class="btn btn-success">Submit Application</button>
                            <a href="apply.php?step=3" class="btn btn-secondary">Back</a>
                        </form>
                    
                    <?php elseif ($step === 5): ?>
                        <div class="text-center">
                            <i class="bi bi-check-circle-fill text-success fs-1"></i>
                            <h3 class="mt-3">Processing Your Application</h3>
                            <p class="text-muted">Please wait while we submit your application...</p>
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        
                        <script>
                        // Auto-submit form
                        document.querySelector('form').submit();
                        </script>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>