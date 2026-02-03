<?php
// Make sure CSRF function is available
if (!function_exists('generateCSRFToken')) {
    require_once __DIR__ . '/../../includes/functions.php';
}
?>

<!-- MEMBER MODAL -->
<div id="memberModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="memberModalTitle">Add Member</h2>

        <form id="memberForm">
            <input type="hidden" name="id" id="memberId" value="">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
            <small class="error-message" style="color: red; display: block; margin-bottom: 10px;"></small>

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" id="memberName" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="memberEmail" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" id="memberPhone">
            </div>

            <div class="form-group">
                <label>Membership</label>
                <select name="membership_id" id="memberMembership" required>
                    <option value="">Select membership</option>
                    <?php foreach ($memberships as $ms): ?>
                        <option value="<?= $ms['id'] ?>" data-duration="<?= $ms['duration_months'] ?>">
                            <?= htmlspecialchars($ms['name']) ?> (<?= $ms['duration_months'] ?> months)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Join Date</label>
                <input type="date" name="join_date" id="memberJoinDate" required>
            </div>

            <div class="form-group">
                <label>Expiry Date (Calculated)</label>
                <input type="date" name="expiry_date" id="memberExpiryDate" readonly>
            </div>

            <button type="submit" id="memberModalSubmit">Save</button>
        </form>
    </div>
</div>

<!-- MEMBERSHIP MODAL -->
<div id="membershipModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="membershipModalTitle">Add Membership</h2>

        <form id="membershipForm">
            <input type="hidden" name="id" id="membershipId" value="">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
            <small class="error-message" style="color: red; display: block; margin-bottom: 10px;"></small>

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="membershipName" required>
            </div>

            <div class="form-group">
                <label>Duration (months)</label>
                <input type="number" name="duration_months" id="membershipDuration" required>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" id="membershipPrice" required>
            </div>

            <button type="submit" id="membershipModalSubmit">Save</button>
        </form>
    </div>
</div>

<!-- WORKOUT PLAN MODAL -->
<div id="workoutModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="workoutModalTitle">Add Workout Plan</h2>

        <form id="workoutForm">
            <input type="hidden" name="id" id="workoutId" value="">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

            <div class="form-group">
                <label>Member</label>
                <select name="member_id" id="workoutMember" required>
                    <option value="">Select member</option>
                    <?php foreach ($members as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Plan Details</label>
                <textarea name="plan_details" id="workoutDetails" required></textarea>
            </div>

            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" id="workoutStartDate" required>
            </div>

            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" id="workoutEndDate" required>
            </div>

            <button type="submit" id="workoutModalSubmit">Save</button>
        </form>
    </div>
</div>

<!-- ATTENDANCE MODAL -->
<div id="attendanceModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="attendanceModalTitle">Add Attendance</h2>

        <form id="attendanceForm">
            <input type="hidden" name="id" id="attendanceId" value="">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

            <div class="form-group">
                <label>Member</label>
                <select name="member_id" id="attendanceMember" required>
                    <option value="">Select member</option>
                    <?php foreach ($members as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" id="attendanceDate" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" id="attendanceStatus">
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>

            <button type="submit" id="attendanceModalSubmit">Save</button>
        </form>
    </div>
</div>
