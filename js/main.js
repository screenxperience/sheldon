function ch_location(url,sek) {

	var millsek = sek*1000;
	
	window.setTimeout(function() {
	
		window.location = url;
		
	},millsek);
	
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

function loadfilter() {

	var filtercategory = document.getElementById('filterinput').value;

	var filterdiv = document.getElementById('filterdiv');

	filterdiv.innerHTML = '';

	var filterframe = document.createElement('iframe');

	filterframe.setAttribute('frameborder','0');

	filterframe.setAttribute('class','block');

	filterframe.style.height = '100vh';

	filterframe.setAttribute('src','/include/filter.inc.php?category='+filtercategory);

	filterdiv.appendChild(filterframe);
}

window.onresize = function() {
	
	var windowWidth = window.innerWidth;
	
	if(windowWidth < 993)
	{
		ch_style('sidebar-category','display','none');
	}
	
	if(windowWidth > 992)
	{	
		ch_style('sidebar-category','display','block');
	}
}
		