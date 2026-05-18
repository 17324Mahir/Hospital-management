<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Reviews - Patient Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/reviews.css">
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Doctor Reviews</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Doctor Reviews</h1>
        <p>Submit reviews for completed appointments and view your previous reviews.</p>
    </div>

    <div class="card">
        <h2>Submit a Review</h2>

        <?php if (!empty($message)): ?>
            <div class="success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($completedAppointments && $completedAppointments->num_rows > 0): ?>
            <form method="POST" action="">
                <label for="appointment_id">Select Completed Appointment</label>
                <select name="appointment_id" id="appointment_id" required>
                    <option value="">Choose appointment</option>

                    <?php while ($appointment = $completedAppointments->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($appointment['appointment_id']); ?>">
                            Dr. <?= htmlspecialchars($appointment['doctor_name']); ?>
                            |
                            <?= htmlspecialchars($appointment['specialization']); ?>
                            |
                            <?= htmlspecialchars($appointment['appointment_date']); ?>
                            <?= htmlspecialchars($appointment['appointment_time']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="rating">Rating</label>
                <select name="rating" id="rating" required>
                    <option value="">Choose rating</option>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Good</option>
                    <option value="3">3 - Average</option>
                    <option value="2">2 - Poor</option>
                    <option value="1">1 - Very Poor</option>
                </select>

                <label for="review_text">Review</label>
                <textarea 
                    name="review_text" 
                    id="review_text" 
                    placeholder="Write your review about the doctor..."
                    required
                ></textarea>

                <button type="submit" class="btn submit-btn">Submit Review</button>
            </form>
        <?php else: ?>
            <div class="empty">
                No completed appointments available for review.
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>My Previous Reviews</h2>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Submitted At</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($myReviews && $myReviews->num_rows > 0): ?>
                    <?php while ($review = $myReviews->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($review['appointment_date']); ?></td>
                            <td>Dr. <?= htmlspecialchars($review['doctor_name']); ?></td>
                            <td><?= htmlspecialchars($review['specialization']); ?></td>
                            <td class="stars">
                                <?= str_repeat("★", (int)$review['rating']); ?>
                                <?= str_repeat("☆", 5 - (int)$review['rating']); ?>
                            </td>
                            <td><?= htmlspecialchars($review['review_text']); ?></td>
                            <td><?= htmlspecialchars($review['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty">
                            No reviews submitted yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>