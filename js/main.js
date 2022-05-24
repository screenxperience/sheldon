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

function chk_inputlength(inputid,inputlength) {

	var input = document.getElementById(inputid);

	if(input.value.length >= inputlength)
	{
		newvalue = input.value.slice(0,inputlength);

		input.value = newvalue; 
	}
}

function loadimportfile(importcategory) {

	if(importcategory == 'asset')
	{
		var type = document.getElementById('assettype');

		var vendor = document.getElementById('assetvendor');

		var model = document.getElementById('assetmodel');

		if(type.value == "" || vendor.value == "" || model.value == "")
		{
			alert('Waehlen Sie zuerst Typ,Hersteller und Modell.');
		}
		else
		{
			var filecontent = ['TypID;HerstellerID;ModellID;Seriennummer'+'\n'+type.value+';'+vendor.value+';'+model.value];

			var filename = 'import_'+type.innerHTML+'_'+vendor.innerHTML+'_'+model.innerHTML+'.csv';

			var file = new File(filecontent,filename,{type: "text/plain"});

			var url = URL.createObjectURL(file);

			window.location = url;
		}
	}
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
		