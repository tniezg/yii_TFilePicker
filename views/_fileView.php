<div class="media filePicker node">
	<a class="pull-left">
		<i class="icon-folder-close"></i>
	</a>
	<div class="media-body">
		<h5 class="media-heading filePicker button toggle" style="cursor:pointer"><?php echo $fileName; ?>
		<?php echo CHtml::radioButton($name, $selected, array(
				'value'=>$value)); ?>
		</h5>
		<span class="filePicker children list">
			<?php echo $children ?>
		</span>
	</div>
</div>