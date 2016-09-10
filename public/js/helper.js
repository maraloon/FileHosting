function showCommentForm(fileId,parentId,pathFor){
    var commentDiv = document.getElementById('comment_'+parentId);
    // новый элемент
    var commentForm = document.createElement('div');
    commentForm.innerHTML = '<form method="post" action="'+pathFor+'">\
    <input type="text" name="comment">\
    <input type="text" name="nick" placeholder="Anon">\
    <input type="hidden" name="parentId" value="'+parentId+'"">\
    <input type="hidden" name="fileId" value="'+fileId+'">\
    <button type="submit">Ответить на коммент</button>';  
    // добавление в конец
    commentDiv.appendChild(commentForm);
}