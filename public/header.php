<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Notification CSS -->
<style>
.notify {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 18px;
    color: white;
    border-radius: 8px;
    z-index: 99999;
    opacity: 0.95;
    font-size: 14px;
}
.notify.success { background: #28a745; }
.notify.error { background: #dc3545; }
.notify.info { background: #0dcaf0; }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">

    <a class="navbar-brand" href="/taskhub/public/dashboard.php">TaskHub</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">

      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="/taskhub/public/tasks.php">Tasks</a>
        </li>
      </ul>

      <div class="d-flex align-items-center">

        <!-- Notifications -->
        <div class="dropdown me-3">
          <a class="nav-link dropdown-toggle text-white" href="#" id="notifDropdown" data-bs-toggle="dropdown">
            Notifications <span id="notifCount" class="badge bg-danger">0</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width:300px;">
            <div id="notifList">Loading...</div>
            <div class="text-center mt-2">
              <a href="/taskhub/public/notifications.php">View all</a>
            </div>
          </ul>
        </div>

        <span class="text-white me-3"><?= htmlspecialchars($_SESSION['name']) ?></span>
        <a href="/taskhub/actions/logout.php" class="btn btn-sm btn-danger">Logout</a>

      </div>
    </div>

  </div>
</nav>


<script>
/* LOAD NOTIFICATIONS */
function loadNotifs(){
  $.getJSON("/taskhub/actions/fetch_notifications.php", function(data){
    let html = '';
    let unread = 0;

    if (data.length === 0) {
      html = '<div class="text-muted p-2">No notifications</div>';
    } else {
      data.forEach(n=>{
        if (n.is_read == 0) unread++;
        html += `<div class="dropdown-item">
          <div class="small text-muted">${n.created_at}</div>
          <div>${n.message}</div>
          <div class="text-end">
            <button class="btn btn-sm btn-link mark-read" data-id="${n.id}">Mark read</button>
          </div>
        </div>`;
      });
    }

    $("#notifList").html(html);
    $("#notifCount").text(unread);
  });
}

/* MARK READ */
$(document).on("click", ".mark-read", function(e){
  e.preventDefault();
  const id = $(this).data('id');

  $.post("/taskhub/actions/mark_notification_read.php",
    { id: id },
    function(){ loadNotifs(); },
    'json'
  );
});

loadNotifs();
setInterval(loadNotifs, 20000);
</script>
