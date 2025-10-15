    <?php
    // Event registration handling
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        require_once __DIR__ . '/../includes/db.php';

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $event_name = trim($_POST['event_name'] ?? '');
        $ticket_type = trim($_POST['ticket_type'] ?? 'General');
        $tickets = (int) ($_POST['tickets'] ?? 1);
        $notes = trim($_POST['notes'] ?? '');

        $errors = [];
        if ($name === '') $errors[] = 'Name is required.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        if ($event_name === '') $errors[] = 'Event name is required.';
        if ($tickets < 1) $errors[] = 'Please select at least 1 ticket.';

        if (empty($errors)) {
            try {
                $ins = $pdo->prepare('INSERT INTO event_registrations (name, email, phone, event_name, ticket_type, tickets, notes) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $ins->execute([$name, $email, $phone, $event_name, $ticket_type, $tickets, $notes]);
                $success = 'Your registration has been received. Thank you!';
                $_POST = [];
            } catch (Exception $e) {
                $errors[] = 'Database error: ' . htmlspecialchars($e->getMessage());
            }
        }
    }
    ?>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Event Registration</h5>

        <p>Register for the event using the form below.</p>

        <!-- Event Registration Form -->
        <form class="row g-3" method="post" action="">
          <div class="col-md-6">
            <div class="form-floating">
              <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
              <label for="name">Full name</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
              <label for="email">Email</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
              <label for="phone">Phone</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input type="text" name="event_name" class="form-control" id="event_name" placeholder="Event Name" value="<?php echo isset($_POST['event_name']) ? htmlspecialchars($_POST['event_name']) : 'Sample Event'; ?>" required>
              <label for="event_name">Event</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-floating mb-3">
              <select class="form-select" name="ticket_type" id="ticket_type" aria-label="Ticket Type">
                <option value="General" <?php echo (isset($_POST['ticket_type']) && $_POST['ticket_type']==='General') ? 'selected' : ''; ?>>General</option>
                <option value="VIP" <?php echo (isset($_POST['ticket_type']) && $_POST['ticket_type']==='VIP') ? 'selected' : ''; ?>>VIP</option>
              </select>
              <label for="ticket_type">Ticket type</label>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating">
              <input type="number" min="1" max="10" name="tickets" class="form-control" id="tickets" placeholder="Tickets" value="<?php echo isset($_POST['tickets']) ? (int)$_POST['tickets'] : 1; ?>">
              <label for="tickets">Tickets</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-floating">
              <textarea name="notes" class="form-control" placeholder="Notes" id="notes" style="height: 100px;"><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
              <label for="notes">Notes</label>
            </div>
          </div>
          <div class="text-center">
            <button type="submit" name="submit" class="btn btn-primary">Register</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
          </div>
        </form><!-- End Event Registration Form -->

        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger mt-3" role="alert">
            <ul class="mb-0">
              <?php foreach ($errors as $err): ?>
                <li><?php echo htmlspecialchars($err); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php elseif (!empty($success)): ?>
          <div class="alert alert-success mt-3" role="alert"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

      </div>
    </div>