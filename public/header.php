<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
.dropdown-user { position: relative; cursor:pointer; }
.dropdown-menu-user {
    display: none;
    position: absolute;
    right: 0;
    background: white;
    border: 1px solid #ccc;
    min-width: 150px;
    padding: 5px 0;
    z-index: 99999;
}
.dropdown-user:hover .dropdown-menu-user { display: block; }

.user-avatar {
    width:35px;
    height:35px;
    border-radius:50%;
    object-fit:cover;
    margin-right:5px;
    border:2px solid #fff;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">

    <a class="navbar-brand" href="dashboard.php">TaskHub</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">

      
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="tasks.php">Tasks</a>
        </li>

        <?php if ($_SESSION['role'] === 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link" href="manage_user.php">Manage Users</a>
        </li>
        <?php endif; ?>
      </ul>

      <!-- RIGHT SIDE -->
      <div class="d-flex align-items-center">

        <!-- NOTIFICATIONS -->
        <div class="dropdown me-4">
          <a class="nav-link dropdown-toggle text-white" href="#" id="notifDropdown" data-bs-toggle="dropdown">
            Notifications <span id="notifCount" class="badge bg-danger">0</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width:300px;">
            <div id="notifList">Loading...</div>
            <div class="text-center mt-2">
              <a href="notifications.php">View all</a>
            </div>
          </ul>
        </div>

        <!-- USER MENU -->
        <div class="dropdown-user d-flex align-items-center">

          <!-- Profile Picture -->
          <img src="uploads/avatars/<?= htmlspecialchars($_SESSION['avatar'] ?? 'default.png'); ?>" 
               class="user-avatar" alt="User Avatar">

          <span class="text-white me-2"><?= htmlspecialchars($_SESSION['name']); ?></span>

          <div class="dropdown-menu-user">
            <a class="dropdown-item" href="profile.php">My Profile</a>
            <a class="dropdown-item" href="../actions/logout.php">Logout</a>
          </div>
        </div>

      </div>

    </div>

  </div>
</nav>
<script>
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
          <button class="btn btn-sm btn-link mark-read" data-id="${n.id}">Mark read</button>
        </div>`;
      });
    }

    $("#notifList").html(html);
    $("#notifCount").text(unread);
  });
}

$(document).on("click",".mark-read",function(){
  $.post("/taskhub/actions/mark_notification_read.php",
    { id: $(this).data('id') },
    ()=> loadNotifs()
  );
});

loadNotifs();
setInterval(loadNotifs,20000);
</script>
