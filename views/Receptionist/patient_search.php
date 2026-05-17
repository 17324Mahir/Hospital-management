<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Search Patients</h1>
    <p>
        Search registered patients by patient ID, name, email, or phone number.
    </p>
</div>

<div class="page-card">

    <form method="GET" action="Dashboard.php">
        <input type="hidden" name="action" value="patient_search">

        <div class="grid-2">
            <div>
                <label for="keyword">Search Keyword</label>
                <input 
                    type="text" 
                    name="keyword" 
                    id="keyword"
                    placeholder="Example: Mahir, 017, gmail, 1"
                    value="<?= htmlspecialchars($keyword); ?>"
                    required
                >
            </div>

            <div style="display:flex; align-items:end;">
                <button type="submit" class="btn" style="width:100%;">
                     Search Patient
                </button>
            </div>
        </div>
    </form>

</div>

<div class="page-card">

    <h2>Search Results</h2>

    <?php if (empty($keyword)): ?>

        <div class="empty">
            Enter a keyword above to search patient records.
        </div>

    <?php elseif ($patients && $patients->num_rows > 0): ?>

        <table>
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Patient Info</th>
                    <th>Contact</th>
                    <th>Gender</th>
                    <th>Blood Group</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($patient = $patients->fetch_assoc()): ?>
                    <tr>
                        <td>
                            #<?= htmlspecialchars($patient['patient_id']); ?>
                        </td>

                        <td>
                            <strong><?= htmlspecialchars($patient['name']); ?></strong>
                            <br>
                            <span class="small">
                                <?= htmlspecialchars($patient['email']); ?>
                            </span>
                        </td>

                        <td>
                            <?= htmlspecialchars($patient['mobile']); ?>
                            <?php if (!empty($patient['phone']) && $patient['phone'] !== $patient['mobile']): ?>
                                <br>
                                <span class="small">
                                    Alt: <?= htmlspecialchars($patient['phone']); ?>
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?= !empty($patient['gender']) ? htmlspecialchars($patient['gender']) : '-'; ?>
                        </td>

                        <td>
                            <?= !empty($patient['blood_group']) ? htmlspecialchars($patient['blood_group']) : '-'; ?>
                        </td>

                        <td>
                            <?php if ($patient['is_active'] == 1): ?>
                                <span class="status active">Active</span>
                            <?php else: ?>
                                <span class="status inactive">Inactive</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a 
                                href="Dashboard.php?action=patient_details&id=<?= htmlspecialchars($patient['patient_id']); ?>"
                                class="btn"
                            >
                                View Details
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>

        <div class="empty">
            No patient found for:
            <strong><?= htmlspecialchars($keyword); ?></strong>
            <br><br>

            <a href="Dashboard.php?action=register_patient" class="btn btn-purple">
                Register New Patient
            </a>
        </div>

    <?php endif; ?>

</div>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>