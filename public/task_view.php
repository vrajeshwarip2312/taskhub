<div class="card mt-3 p-3">
  <h5>Comments</h5>
  <div id="commentsList">Loading comments...</div>

  <textarea id="newComment" class="form-control mt-2" rows="2" placeholder="Write a comment..."></textarea>
  <button id="postComment" class="btn btn-primary btn-sm mt-2">Post</button>
</div>

<script>
const TASK_ID = <?= (int)$task['id'] ?>;

function refreshComments(){
  $.getJSON('/taskhub/actions/fetch_comments.php', { task_id: TASK_ID }, function(data){
    let html = '';
    data.forEach(c=>{
      html += `<div class="border p-2 mb-2">
        <strong>${c.name}</strong> <small class="text-muted">${c.created_at}</small>
        <p>${c.comment}</p>
        ${c.user_id == <?= $_SESSION['user_id'] ?> ? `<button class="btn btn-sm btn-danger del-comment" data-id="${c.id}">Delete</button>` : ''}
      </div>`;
    });
    $("#commentsList").html(html);
  });
}

$("#postComment").click(function(){
  let txt = $("#newComment").val().trim();
  if (!txt) return alert("Enter comment");
  $.post('/taskhub/actions/add_comment.php', { task_id: TASK_ID, comment: txt }, function(res){
    if (res.success) {
      $("#newComment").val('');
      refreshComments();
    } else {
      alert(res.error || "Error");
    }
  }, 'json');
});

$(document).on('click', '.del-comment', function(){
  if (!confirm('Delete comment?')) return;
  const id = $(this).data('id');
  $.post('/taskhub/actions/delete_comment.php', { comment_id: id }, function(res){
    if (res.success) refreshComments(); else alert(res.error||'Error');
  }, 'json');
});

refreshComments();
</script>
