<?php
namespace FileHosting\Models;
class CommentsTreeModel{

	public $tree;

	function __construct(){
	}

	public function makeTree($comments){
		$tree=array();

		foreach ($comments as $comment) { //разбираем каждую строку из таблицы
			$id=$comment->getId(); //id текущего комента
			$parent_id=$comment->parent_id; //id родительского комента (тот, на котроый отвечают)

			if (empty($parent_id)) { //если родителя нет, значит это комент без вложеннности, коммент к файлу
				$tree[$id]=array();
			}
			else{
				$find_key=$this->findKey($tree,(int)$parent_id,array()); //ищет путь родителя
				$parent=&$tree; //подготавливаем родителя
				foreach ($find_key as $next_comment) { //для каждого элемента вложенности
					$parent=&$parent[$next_comment];
				}
				$parent[$id]=array(); //создаём у родителя текущий комент
			}
		}

		$this->tree=$tree;
		return $tree;
	}

	protected function findKey($tree,$target_key,$way){
		if (array_key_exists($target_key,$tree)){
			$way[]=$target_key;
			return $way;
		}
		else{
			foreach ($tree as $current_key => $child_tree) {
				if (!empty($child_tree)) {
					
					$way[]=$current_key;
					$result=$this->findKey($child_tree,$target_key,$way);
					
					if ($result!=NULL) {
						if (in_array($target_key, $result)) {
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
	public function sortComments(array $comments,array $sortedComments,array $tree,int $currentDeep){

		if (empty($tree)) { // если нет подкомментов
			return $sortedComments; //вернуть рекурсивно
		}
		else{ // если есть подкоммент(ы)
			foreach ($tree as $id => $child_tree) { //для каждого подкоммента
				$last=array('deep'=>$currentDeep,'comment'=>$this->searchComment($id,$comments));
				$sortedComments[]=$last; //добавить подкоммент в список отсортированных

				$sortedComments=$this->sortComments($comments,$sortedComments,$child_tree,$currentDeep+1); //проверить подкоммент на наличие подкомментов
			}

		}
		return $sortedComments;
	}

	/**
	 * Находит коммент с нужным id
	 */
	private function searchComment(int $id, array $comments){
		foreach ($comments as $comment) {
			if ($comment->getId()==$id) {
				return $comment;
			}
		}
		return false;
	}
}