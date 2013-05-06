var niezgoda=niezgoda || {};
niezgoda.filePicker=niezgoda.filePicker || {};

niezgoda.filePicker.nodeToggle=function(id, collapseSelector, childrenSelector, 
		nodeSelector){
	var element=$('#'+id),
		toggleNode=function(event){
			var closest, children;
			if($(event.target).is(collapseSelector)){
				// filePicker node
				closest=$(event.target).closest(nodeSelector);
				if(!closest.length)
					console.log(nodeSelector+' could not be found');
				children=closest.find(childrenSelector+':first');
				children.slideToggle();
			}
		},
		collapseAll=function(){
			element.find(childrenSelector).slideToggle(0);
		};

	if(!element.find(collapseSelector).length)
		console.log(id+" doesn't have "+collapseSelector);
	if(!element.find(childrenSelector).length)
		console.log(id+" doesn't have "+childrenSelector);
	
	element.on('mousedown',toggleNode);

	collapseAll();
};