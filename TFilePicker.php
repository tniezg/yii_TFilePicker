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
			$selectedBranch=null, $currentBranch="."){

		$result='';
		foreach($directoriesTree as $directoryName=>$contents){
			$subBranch=$currentBranch.'/'.$directoryName;

			if($contents){
				$subRender=$this->toStringTree($contents, $name, $nodeTemplatePath, 
					$selectedBranch, $subBranch);
			}else{
				$subRender='';
			}
			$result.=$this->controller->renderFile($nodeTemplatePath, array(
				'fileName'=>$directoryName,
				'name'=>$name,
				'value'=>$subBranch,
				'selected'=>$selectedBranch==$subBranch,
				'children'=>$subRender,
			), true);
		}
		return $result;
	}

	public function run(){
		if(!isset($this->directory)){
			throw new Exception('directory needs to be set');
		}

		list($name, $id) = $this->resolveNameId();

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
		echo '<div id="'.$id.'">'.
			$this->toStringTree($structure, $name, $fileViewPath, $value).'</div>';

		// JS
		$cs = Yii::app()->getClientScript();
		$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__).
			'/assets', false, -1, true);
		$cs->registerScriptFile($assets . '/nodeToggle.js');

		$jscode="niezgoda.filePicker.nodeToggle('".$id.
			"', '.filePicker.button.toggle', '.filePicker.children.list', ".
			"'.filePicker.node');";

		Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $id, $jscode);
	}
}