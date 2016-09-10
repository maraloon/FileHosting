<?php
namespace FileHosting\Models;
class CommentsSorter{

	public $tree;

	function __construct(){
	}

	public function sortComments($comments){
		$tree=$this->makeTree($comments);
		$comments=$this->getSortedComments($comments,array(),$tree,0);
		return $comments;
	}

	protected function makeTree($comments){
		$tree=array();

		foreach ($comments as $comment) { //разбираем каждую строку из таблицы
			$id=$comment->getId(); //id текущего комента
			$parentId=$comment->parentId; //id родительского комента (тот, на котроый отвечают)

			if (empty($parentId)) { //если родителя нет, значит это комент без вложеннности, коммент к файлу
				$tree[$id]=array();
			}
			else{
				$findKey=$this->findKey($tree,(int)$parentId,array()); //ищет путь родителя
				$parent=&$tree; //подготавливаем родителя
				foreach ($findKey as $nextComment) { //для каждого элемента вложенности
					$parent=&$parent[$nextComment];
				}
				$parent[$id]=array(); //создаём у родителя текущий комент
			}
		}

		$this->tree=$tree;
		return $tree;
	}

	protected function findKey($tree,$targetKey,$way){
		if (array_key_exists($targetKey,$tree)){
			$way[]=$targetKey;
			return $way;
		}
		else{
			foreach ($tree as $currentKey => $childTree) {
				if (!empty($childTree)) {
					
					$way[]=$currentKey;
					$result=$this->findKey($childTree,$targetKey,$way);
					
					if ($result!=NULL) {
						if (in_array($targetKey, $result)) {
							return $result;
						}
					}

					array_pop($way);
				}
			}
		}
	}

	/**
	 * Сортировка коментариев
	 * 
	 * Возвращает массив с отсортированными комментариями $sortedComments и их глубиной на основе дерева комментариев $tree
	 * 
	 * @param array $comments Неотсортированные комментарии, взятые из БД как есть
	 * @param array $sortedComments Массив, куда заносятся отсортированный комментарии
	 * @param array $tree Модель дерева комментариев, полученная через $this->makeTree
	 * @param int $currentDeep глубина текущего коммента 
	 * 
	 * @return array Массив отсортированных комментов вида (deep='глубина', comment=Object CommentModel)
	 */
	protected function getSortedComments(array $comments,array $sortedComments,array $tree,int $currentDeep){

		if (empty($tree)) { // если нет подкомментов
			return $sortedComments; //вернуть рекурсивно
		}
		else{ // если есть подкоммент(ы)
			foreach ($tree as $id => $childTree) { //для каждого подкоммента
				$last=array('deep'=>$currentDeep,'comment'=>$this->searchComment($id,$comments));
				$sortedComments[]=$last; //добавить подкоммент в список отсортированных

				$sortedComments=$this->getSortedComments($comments,$sortedComments,$childTree,$currentDeep+1); //проверить подкоммент на наличие подкомментов
			}

		}
		return $sortedComments;
	}

	/**
	 * Находит коммент с нужным id
	 */
	protected function searchComment(int $id, array $comments){
		foreach ($comments as $comment) {
			if ($comment->getId()==$id) {
				return $comment;
			}
		}
		return false;
	}
}