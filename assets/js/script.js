$(document).on("click", ".deleteComment", function() {
    if (!confirm("Delete this comment?")) return;

    const id = $(this).data("id");

    $.post('/taskhub/actions/delete_comment.php', { comment_id: id }, function(res) {
        if (res.status === 'ok') {
            loadComments();
        } else {
            alert("Error: " + res.msg);
        }
    }, 'json');
});

$(document).on("click", ".editComment", function() {
    const id = $(this).data("id");
    const oldText = $(this).data("text");

    const newText = prompt("Edit Comment:", oldText);
    if (newText === null || newText.trim() === "") return;

    $.post('/taskhub/actions/edit_comment.php', { comment_id: id, comment: newText },
        function(res) {
            if (res.status === 'ok') {
                loadComments();
            } else {
                alert("Error: " + res.msg);
            }
        }, 'json'
    );
});