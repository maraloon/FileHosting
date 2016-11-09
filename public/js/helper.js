function showCommentFormReply(parentId){
    var commentDiv = document.getElementById('comment_'+parentId);
    var commentForm = document.getElementById('comment_form');
    //создаем в форме переменную, содержащую id отвечаемого коммента
    if (document.getElementById('parentIdHidden')==null){    
        var parentIdHidden = document.createElement('input');
        parentIdHidden.id='parentIdHidden';
        parentIdHidden.type='hidden';
        parentIdHidden.name='parentId';
        parentIdHidden.value=parentId;
        commentForm.appendChild(parentIdHidden);
    }
    else {
        document.getElementById('parentIdHidden').value=parentId;
    }
    //Вставляем форму после отвечаемого коммента
    comments.insertBefore(commentForm,commentDiv.nextSibling);

    //Добавляем кнопку "Написать коммент без ответа"
    if (document.getElementById('noAnswerComment')==null) {
        var newCommentButton=document.createElement('button');
        newCommentButton.id='noAnswerComment';
        newCommentButton.className='btn btn-success btn-xs';
        newCommentButton.onclick=showCommentForm;
        newCommentButton.innerHTML='Добавить комментарий к файлу, не отвечая ни на чье сообщение';
        add_comment.appendChild(newCommentButton);
    }

}

function showCommentForm(){
    var commentForm = document.getElementById('comment_form');
    add_comment.appendChild(commentForm);

    var noAnswerComment = document.getElementById('noAnswerComment');
    add_comment.removeChild(noAnswerComment);
}