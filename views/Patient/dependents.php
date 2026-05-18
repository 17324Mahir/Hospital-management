<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Family Dependents - Patient Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/dependents.css">
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Family Dependents</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Family Dependents</h1>
        <p>Add and manage family members under your patient account.</p>
    </div>

    <div class="card">
        <h2>Add Dependent</h2>

        <?php if (!empty($message)): ?>
            <div class="success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
            <div class="success">Dependent deleted successfully.</div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-grid">

                <div>
                    <label for="name">Dependent Name *</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        placeholder="Enter dependent name"
                        required
                    >
                </div>

                <div>
                    <label for="relationship">Relationship *</label>
                    <input 
                        type="text" 
                        name="relationship" 
                        id="relationship" 
                        placeholder="Example: Father, Mother, Brother, Child"
                        required
                    >
                </div>

                <div>
                    <label for="date_of_birth">Date of Birth *</label>
                    <input 
                        type="date" 
                        name="date_of_birth" 
                        id="date_of_birth" 
                        max="<?= date('Y-m-d'); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="blood_group">Blood Group *</label>
                    <select name="blood_group" id="blood_group" required>
                        <option value="">Select blood group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>

            </div>

            <button type="submit" class="btn submit-btn">Add Dependent</button>
        </form>
    </div>

    <div class="card">
        <h2>My Dependents</h2>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Relationship</th>
                    <th>Date of Birth</th>
                    <th>Blood Group</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($dependents && $dependents->num_rows > 0): ?>
                    <?php while ($dependent = $dependents->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($dependent['name']); ?></td>
                            <td><?= htmlspecialchars($dependent['relationship']); ?></td>
                            <td><?= htmlspecialchars($dependent['date_of_birth']); ?></td>
                            <td><?= htmlspecialchars($dependent['blood_group']); ?></td>
                            <td>
                                <a 
                                    href="Dashboard.php?action=delete_dependent&id=<?= $dependent['id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this dependent?');"
                                    class="delete-btn"
                                >
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty">
                            No dependents added yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>