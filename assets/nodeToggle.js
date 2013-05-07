var niezgoda=niezgoda || {};
niezgoda.filePicker=niezgoda.filePicker || {};

niezgoda.filePicker.nodeToggle=function(id, collapseSelector, childrenSelector, 
		nodeSelector, selectedPath){
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
		initNodes=function(){
			var selectedElement, childrenElement;

			element.on('mousedown',toggleNode);
			element.find(childrenSelector).slideToggle(0);

			// go upstream, expanding every parent node
			selectedElement=element.find('input[type=radio]:checked');
			childrenElement=selectedElement.parent().closest(childrenSelector);
			while(childrenElement.length){
				childrenElement.slideToggle(0);
				childrenElement=childrenElement.parent().closest(childrenSelector);
			}
		};

	if(!element.find(collapseSelector).length)
		console.log(id+" doesn't have "+collapseSelector);
	if(!element.find(childrenSelector).length)
		console.log(id+" doesn't have "+childrenSelector);

	initNodes();
};