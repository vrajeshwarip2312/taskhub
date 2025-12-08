<?php 
include "../config/auth_check.php"; 
include "../config/db.php"; 
include "header.php"; 

if (!isset($_GET['id'])) {
    die("Invalid Task ID");
}

$task_id = (int)$_GET['id'];

$q = $conn->prepare("SELECT * FROM tasks WHERE id=?");
$q->bind_param("i", $task_id);
$q->execute();
$t = $q->get_result()->fetch_assoc();

if (!$t) {
    die("Task not found");
}
?>

<div class="container mt-4">

<h3>Task Details</h3>

<p><strong>Title:</strong> <?= htmlspecialchars($t['title']) ?></p>
<p><strong>Description:</strong> <?= nl2br(htmlspecialchars($t['description'])) ?></p>
<p><strong>Priority:</strong> <?= $t['priority'] ?></p>
<p><strong>Status:</strong> <?= $t['status'] ?></p>
<p><strong>Due Date:</strong> <?= $t['due_date'] ?></p>

<a href="edit_task.php?id=<?= $t['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="tasks.php" class="btn btn-secondary btn-sm">Back</a>

<hr>

<div class="card mt-4">
  <div class="card-body">

    <h5>Comments</h5>

    <div id="commentsList" class="mb-3">Loading comments...</div>


    <form id="commentForm">
      <div class="mb-2">
        <textarea id="commentText" name="comment" class="form-control" 
                  rows="3" placeholder="Write a comment..."></textarea>
      </div>

      <input type="hidden" id="taskId" value="<?= $task_id ?>">

      <button type="submit" class="btn btn-primary btn-sm">Add Comment</button>
    </form>

  </div>
</div>

</div> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
const TASK_ID = <?= $task_id ?>;


function escapeHtml(text) {
    return $('<div/>').text(text).html();
}

function renderComments(list) {
    if (!list || list.length === 0) {
        $('#commentsList').html('<div class="text-muted">No comments yet.</div>');
        return;
    }

    let html = "";
    list.forEach(c => {
        html += `
        <div class="comment-box mb-3" data-id="${c.id}">
            <div class="small text-muted">${escapeHtml(c.user_name)} • ${c.created_at}</div>
            <div class="comment-text">${escapeHtml(c.comment)}</div>

            <button class='btn btn-sm btn-outline-primary edit-btn'>Edit</button>
            <button class='btn btn-sm btn-outline-danger delete-btn'>Delete</button>
        </div>
        <hr/>`;
    });

    $("#commentsList").html(html);
}


function loadComments() {
    $.getJSON("/taskhub/actions/fetch_comments.php", { task_id: TASK_ID })
        .done(data => renderComments(data))
        .fail(() => $("#commentsList").html("<div class='text-danger'>Failed to load comments</div>"));
}

// Add new comment
$("#commentForm").on("submit", function(e) {
    e.preventDefault();

    const text = $("#commentText").val().trim();
    if (!text) return alert("Comment cannot be empty!");

    $.ajax({
        url: "/taskhub/actions/add_comment.php",
        type: "POST",
        dataType: "json",
        data: { task_id: TASK_ID, comment: text },

        success: function(resp) {
            if (resp.status === "ok") {
                $("#commentText").val("");
                loadComments();
            } else {
                alert("Error: " + resp.msg);
            }
        },

        error: function(xhr) {
            alert("Failed: " + (xhr.responseText || ""));
        }
    });
});

// Delete Comment
$(document).on("click", ".delete-btn", function() {
    let cid = $(this).closest(".comment-box").data("id");

    if (!confirm("Delete this comment?")) return;

    $.post("/taskhub/actions/delete_comment.php", { id: cid }, function(res) {
        if (res.status === "ok") loadComments();
        else alert(res.msg);
    }, "json");
});

// Edit Comment
$(document).on("click", ".edit-btn", function() {
    let box = $(this).closest(".comment-box");
    let cid = box.data("id");
    let oldText = box.find(".comment-text").text();

    let newText = prompt("Edit comment:", oldText);
    if (!newText) return;

    $.post("/taskhub/actions/edit_comment.php",
        { id: cid, comment: newText },
        function(res) {
            if (res.status === "ok") loadComments();
            else alert(res.msg);
        }, 
    "json");
});


loadComments();
</script>


<?php include "footer.php"; ?>
