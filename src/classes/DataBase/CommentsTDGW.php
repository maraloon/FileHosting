<?php
namespace FileHosting\DataBase;
use \FileHosting\Models\CommentModel;
class CommentsTDGW{
	protected $db;

	public function __construct(\PDO $PDO_connection){
		$this->db=$PDO_connection;
	}

	/**
	 * @var \FileHosting\Models\CommentModel $comment
	 * @return bool status of adding to DB
	 */
	public function addComment( CommentModel $comment){
		$SqlString="INSERT INTO `comments`
				(`text`,`nick`,`file_id`)
				VALUES
				(:text,:nick,:file_id)";

		$rows = $this->db->prepare($SqlString);
		$rows->bindValue(':text', $comment->text, \PDO::PARAM_STR);
		$rows->bindValue(':nick', $comment->nick, \PDO::PARAM_STR);
		$rows->bindValue(':file_id', $comment->file_id, \PDO::PARAM_INT);
		$rows->execute();
	}

	public function getComments(int $file_id){

		$rows = $this->db->prepare("SELECT * FROM `comments` WHERE `file_id`=:file_id");
		$rows->bindValue(':file_id', $file_id, \PDO::PARAM_INT);
		$rows->execute();

		$comments_row=$rows->fetchAll(\PDO::FETCH_ASSOC);

		//Подготавливаем массив
		$comments=array();
		foreach($comments_row as $comment_row){
			$comment=new CommentModel();
			$comment->addInfo($comment_row);
			$comments[]=$comment;
		}
		return $comments;
		/*
		if ($comment_row!=NULL) {
			$comment=new CommentModel();
			$comment->addInfo($comment_row);

			return $comment;
		}
		return false;*/
	}
	
}