<div class="media">
	<a class="pull-left">
		<i class="icon-folder-close"></i>
	</a>
	<div class="media-body">
		<h5 class="media-heading"><?php print_r($fileName); ?>
		<?php echo CHtml::radioButton($name, $selected, array(
				'value'=>$value)); ?>
		</h5>
		{{{children}}}
	</div>
</div>