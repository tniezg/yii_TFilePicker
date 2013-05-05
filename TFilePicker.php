<?php class TFilePicker extends CInputWidget{
	private $defaultFileView='_fileView';

	public $directory;
	public $itemView;

	private function getSubdirectoriesTree($directory){
		if(!is_writable($directory))
			throw new Exception('cannot read target directory');

		$subdirectoriesStructure=array();
		if($handle=opendir($directory)){
			while (false !== ($entry = readdir($handle))) {
				$subdirFullPath=$directory.'/'.$entry;
		    if ($entry != "." && $entry != ".." && is_dir($subdirFullPath)) {
					$subdirectoriesStructure[$entry]=
						$this->getSubdirectoriesTree($subdirFullPath);
				}
		  }
			closedir($handle);
		}
		if(count($subdirectoriesStructure)==0){
			return null;
		}else{
			return $subdirectoriesStructure;
		}
	}

	private function toStringTree($directoriesTree, $name, $nodeTemplatePath, 
			$mustache, $selectedBranch=null, $currentBranch="."){

		$result='';
		foreach($directoriesTree as $directoryName=>$contents){
			$subBranch=$currentBranch.'/'.$directoryName;

			$preRender=$this->controller->renderFile($nodeTemplatePath, array(
				'fileName'=>$directoryName,
				'name'=>$name,
				'value'=>$subBranch,
				'selected'=>$selectedBranch==$subBranch,
			), true);
			if($contents){
				$subRender=$this->toStringTree($contents, $name, $nodeTemplatePath, 
					$mustache, $selectedBranch, $subBranch);
			}else{
				$subRender='';
			}
			$result.=$mustache->render($preRender, array('children'=>$subRender));
		}
		return $result;
	}

	public function run(){
		if(!isset($this->directory)){
			throw new Exception('directory needs to be set');
		}

		list($name, $id) = $this->resolveNameId();

		$mustachePathAlias='ext.TFilePicker.mustache.src.Mustache';

		spl_autoload_unregister(array('YiiBase','autoload'));

		require Yii::getPathOfAlias($mustachePathAlias).
			'/Autoloader.php';
		Mustache_Autoloader::register(Yii::getPathOfAlias($mustachePathAlias).
			DIRECTORY_SEPARATOR.'..');

		spl_autoload_register(array('YiiBase','autoload'));

		$mustache = new Mustache_Engine(array(
			'charset' => Yii::app()->charset,
			'cache' => Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'Mustache'.
				DIRECTORY_SEPARATOR.'cache',
			'escape' => function($value) {
      	return CHtml::encode($value);
      },
		));

		if(!isset($this->itemView)){
			// use default view for rendering tree nodes, located inside this 
			// extension dir.
			$fileViewPath=$this->getViewFile($this->defaultFileView);
		}else{
			// use custom view
			$fileViewPath=$this->controller->getViewFile($this->fileView);
			if($fileViewPath===false)
				throw new Exception(
					'Provided fileView('.$this->fileView.') could not be found');
		}

		$structure=$this->getSubdirectoriesTree(getcwd().$this->directory);
		$value=CHtml::resolveValue($this->model, $this->attribute);
		echo $this->toStringTree($structure, $name, $fileViewPath, $mustache, 
			$value);
	}
}