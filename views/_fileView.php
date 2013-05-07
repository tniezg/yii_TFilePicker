<div class="media filePicker node">
	<a class="pull-left">
		<i class="icon-folder-close"></i>
	</a>
	<div class="media-body">
		<h5 class="media-heading filePicker button toggle" style="cursor:pointer">
			<?php echo $fileName; ?>
			<?php echo CHtml::radioButton($name, $selected, array(
				'value'=>$value, 'style'=>'vertical-align:top;margin:3px 0 0 5px')); ?>
		</h5>
		<div class="filePicker children list">
			<?php echo $children ?>
		</div>
	</div>
</div>