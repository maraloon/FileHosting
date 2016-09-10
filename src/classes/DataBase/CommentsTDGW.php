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
				(`text`,`nick`,`fileId`,`parentId`)
				VALUES
				(:text,:nick,:fileId,:parentId)";

		$rows = $this->db->prepare($SqlString);
		$rows->bindValue(':text', $comment->text, \PDO::PARAM_STR);
		$rows->bindValue(':nick', $comment->nick, \PDO::PARAM_STR);
		$rows->bindValue(':fileId', $comment->fileId, \PDO::PARAM_INT);
		$rows->bindValue(':parentId', $comment->parentId, \PDO::PARAM_INT);
		$rows->execute();
	}

	public function getComments(int $fileId){

		$rows = $this->db->prepare("SELECT * FROM `comments` WHERE `fileId`=:fileId");
		$rows->bindValue(':fileId', $fileId, \PDO::PARAM_INT);
		$rows->execute();

		$commentsRow=$rows->fetchAll(\PDO::FETCH_ASSOC);

		//Подготавливаем массив
		$comments=array();
		foreach($commentsRow as $commentRow){
			$comment=new CommentModel();
			$comment->addInfo($commentRow);
			$comments[]=$comment;
		}
		return $comments;
	}
	
}