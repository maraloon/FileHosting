function showCommentForm(fileId,parentId,pathFor){
    var commentDiv = document.getElementById('comment_'+parentId);
    // новый элемент
    var commentForm = document.createElement('div');
    commentForm.innerHTML = '<form method="post" action="'+pathFor+'">\
    <input type="text" name="comment">\
    <input type="text" name="nick" placeholder="Anon">\
    <input type="hidden" name="parentId" value="'+parentId+'"">\
    <input type="hidden" name="fileId" value="'+fileId+'">\
    <button class="btn btn-success btn-sm" type="submit">Ответить на комментарий</button>';
    // добавление в конец
    commentDiv.appendChild(commentForm);
}