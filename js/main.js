function ch_location(url,sek) {

	var time = sek*1000;
	
	window.setTimeout(function() {
	
		window.location = url;
		
	},time);
	
}

function ch_style(elemID,attr,attrValue) {
	
	var elem = document.getElementById(elemID);
	
	if(attr == 'display')
	{
		elem.style.display = attrValue;
	}
	
	if(attr == 'marginLeft')
	{
		elem.style.marginLeft = attrValue;
	}
	
}

function clone(elemID) {
	
	var elem = document.getElementById(elemID);
	
	var clonedElem = elem.cloneNode(true);
	
	return clonedElem;
}
	
function edit(ID) {
	
	ch_style('attr-show-'+ID,'display','none');
	
	ch_style('attr-input-'+ID,'display','block');
	
	ch_style('attr-placeholder-'+ID,'display','none');
	
	ch_style('attr-edit-'+ID,'display','none');
	
	ch_style('attr-cancel-'+ID,'display','block');
	
	ch_style('attr-save-'+ID,'display','block');
	
}
function cedit(ID) {
	
	ch_style('attr-show-'+ID,'display','block');
	
	ch_style('attr-input-'+ID,'display','none');
	
	ch_style('attr-placeholder-'+ID,'display','block');
	
	ch_style('attr-edit-'+ID,'display','block');
	
	ch_style('attr-cancel-'+ID,'display','none');
	
	ch_style('attr-save-'+ID,'display','none');
}

window.onresize = function() {
	
	var windowWidth = window.innerWidth;
	
	if(windowWidth < 993)
	{
		ch_style('category-nav','display','none');
	}
	
	if(windowWidth > 992)
	{	
		ch_style('category-nav','display','block');
	}
}
		