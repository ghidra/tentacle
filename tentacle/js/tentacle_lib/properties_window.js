tentacle.properties_window=function(){
	//return this.build(id,title,data); //to return or not to return, probably

	//if this is here only, it fucks up where the ojbect goes for an odd reason i don't understand
	this.entry_type_list = {};//i need this inited here, bcause build doesnt happen soon enough
	
	return this;
}
//this class should be reuseable to hold windows for node properties and database query windows, as long as anything else  might need related

/*DATA SHOULD LOOK LIKE THIS
this.property_window = new tentacle.properties_window(
	this.property_window_id,
	'add new page',
	{
		'page settings':{
			'name':'',
			'type':{
				'ind':'page',
				'album_images':{'selected':'image album'},
				'album_movies':'movie album'
			},
			'typeb':{
				'ind',
				{'selected':'image album'},
				'movie album'
			},
			'number value':0,
			'hidden':false,
			'&nbsp':'add new page'
		}
	}
);
*/
tentacle.properties_window.prototype.build=function(id,title,data){
	this.id=id;
	this.label_width=80;
	this.input_width=140;
	this.style_margin=2;

	//this.active_label='';//used so far primarily for the slider control
	this.slider_width =(this.input_width*0.6)-(this.style_margin*4);
	this.slider_input_width = (this.input_width*0.4)-(this.style_margin*4);//get the actual width in pixels, instead of percentage, so it is more presice
	//this.slider_value=0;//we need to save the slider value when we start to slide. other wise we get weird slider results

	this.entries = {};//to hold the things that possibly needs a call back function;
	//this.entry_type_list = {};//this object is used to hold the type to that I can reference it when I am changin input values

	
	if(!(document.getElementById(id))){

		var comp = document.getElementById('composition');

		//containers
		var container = new tentacle.element('div',{'id':id,'class':'propertyBox'});
		var form_container = new tentacle.element('form',{'name':id+'_form', 'id':id+'_form','action':'','method':'GET','enctype':'multipart/form-data','style':{'margin-bottom':'0px'}});

		//header
		var header = new tentacle.element('div',{'class':'aBar','onmousedown':'tentacle.floating_window.drag(event,\''+id+'\')' });
		var label = new tentacle.element('div');
		var close_out = new tentacle.element('div',{'class':'close_out','onmousedown': 'tentacle.utilities.remove_element(\''+id+'\')' });
		
		//start appending
		label.innerHTML = title;//node.attributes.type+':'+node.attributes.index;
		header.appendChild(close_out);
		header.appendChild(label);

		container.appendChild(header);

		//------------now add in the form stuff, procedurally created elements with the other functions here
		//first iterate the groups of properties
		var group_count = 0;//use this, so that the first group doesn't have a way to hide, there is no reason for it.
		for (var group in data) {
			if (group_count>0) var group_label = new tentacle.element('div',{'class':'bBar','style':{'margin-top':'1px','clear':'both'},'onmousedown':'tentacle.utilities.show_hide(\''+id+'_'+group+'\')'},group);
			var group_container = new tentacle.element('div',{'id':id+'_'+group});
			//now iterate the properties in each group
			for (key in data[group]){//for each gui element, add it to the containe
				group_container.appendChild( this.add( key,data[group][key] ) );
			}
			if (group_count>0) form_container.appendChild(group_label);
			form_container.appendChild(group_container);
			group_count++;//up the counter
		}
		container.appendChild(form_container);
		
		//------------
		comp.appendChild(container);

		tentacle.utilities.move_to_center( container );
		tentacle.utilities.disable_selection( container );

	}

	return this;
}
tentacle.properties_window.prototype.add=function(title,value){
	var temp = {};

	switch(typeof value){
		case 'number':
			temp = this.number_input(title,value);
			break;
		case 'string':
			if(title=='&nbsp'){
				temp = this.button(title,value);
			}else{
				if(this.entry_type_list[title]=='color'){
					temp = this.color_input(title,value);	
				}else{
					temp = this.string_input(title,value);
				}
			}
			break;
		case 'boolean':
			temp = this.boolean_input(title,value);
			break;
		case 'object':
			temp = this.drop_down_input(title,value);
			break;
		case 'default':
			temp = new tentacle.element('div','','some shit is broke');
			break;
	}

	return temp;
}

//---------------------------------
//---------------------------------
//everything down here is to make the form elements
//---------------------------------
//---------------------------------
tentacle.properties_window.prototype.entry=function(title){
	//this is the base entry layer, with the label
	var container	= 	new tentacle.element('div',{'style':{'clear':'both'}});//hold the input and the label
	var label 		= 	new tentacle.element('div',{'style':{'float':'left','width':this.label_width}},title);
	container.appendChild(label);

	return container;
}
tentacle.properties_window.prototype.drop_down_input=function(label,value){
	var container = this.entry(label);
	this.entries[label] = new tentacle.element('select',{'id':this.id+'_'+label+'_input','name':this.id+'_'+label+'_input','style':{'width':this.input_width} } );

	if( Object.prototype.toString.call( value ) == '[object Object]' ){//this is an object, or assiative array type object
		for (k in value){
			//if value[k] is an object, that means that this is a selected value
			if(Object.prototype.toString.call( value[k] ) == '[object Object]'){
				var option = new tentacle.element('option',{'value':k,'selected':'selected'},value[k]['selected']);
				//var option = new tentacle.element('option',{'value':value[k],'selected':'selected'},value[k]['selected']);
			}else{
				var option = new tentacle.element('option',{'value':k},value[k]);
				//var option = new tentacle.element('option',{'value':value[k]},k);
			}
			this.entries[label].appendChild( option );
		}
	}else{//this is a basic array
		for(var i=0; i<value.length; i++){
			if(Object.prototype.toString.call( value[i] ) == '[object Object]'){
				var option = new tentacle.element('option',{'value':value[i]['selected'],'selected':'selected'},value[i]['selected']);
			}else{
				var option = new tentacle.element('option',{'value':value[i]},value[i]);
			}
			this.entries[label].appendChild( option );
		}
	}
	container.appendChild(this.entries[label]);

	return container;
}
tentacle.properties_window.prototype.string_input=function(label,value){
	var container = this.entry(label);
	this.entries[label] = new tentacle.element('input',{'type':'text','name':this.id+'_'+label+'_input','id':this.id+'_'+label+'_input','style':{'width':this.input_width},'value':value } );
	container.appendChild(this.entries[label]);

	return container;
}
tentacle.properties_window.prototype.boolean_input=function(label,value){
	var container = this.entry(label);
	if(value){
		this.entries[label] = new tentacle.element('input',{'type':'checkbox','name':this.id+'_'+label+'_input','id':this.id+'_'+label+'_input','checked':'true' } );
	}else{
		this.entries[label] = new tentacle.element('input',{'type':'checkbox','name':this.id+'_'+label+'_input','id':this.id+'_'+label+'_input' } );
	}
	container.appendChild(this.entries[label]);

	return container;
}
tentacle.properties_window.prototype.number_input=function(label,value){
	//----------------slider

	var container = this.entry(label);

	var slider_bg =  new tentacle.element('div',{'id':'gui_slider_bg_'+this.id+'_'+label,'class':'gui_slider_bg','style':{'width':'40%','height':'22px','background-color':'red','float':'left','margin':this.style_margin+'px'}});
	var slider_fg =  new tentacle.element('div',{'id':'gui_slider_fg_'+this.id+'_'+label,'class':'gui_slider_fg','style':{'width':this.slider_width/2+'px','height':'18px','background-color':'black','float':'left','margin':this.style_margin+'px'}});
	this.entries[label] = new tentacle.element('input',{'id':this.id+'_'+label+'_input','type':'text','name':this.id+'_'+label+'_input','value':value,'style':{'width':this.slider_input_width+'px','float':'left'}});

	slider_bg.appendChild(slider_fg);
	container.appendChild(slider_bg);
	container.appendChild(this.entries[label]);
	//this.entries[label] = new tentacle.element('input',{'type':'text','name':this.id+'_'+label+'_input','id':this.id+'_'+label+'_input','style':{'width':this.input_width},'value':value });
	//container.appendChild(this.entries[label]);
	
	tentacle.utilities.bind(slider_bg,'mousedown',tentacle.utilities.closure(this,this.slider_mousedown,label));//add the function to start dragging
	tentacle.utilities.bind(this.entries[label],'change',tentacle.utilities.closure(this,this.slider_input_change,label));

	return container;
}
tentacle.properties_window.prototype.color_input=function(label,value){
	var container = this.entry(label);

	var color = new rgbcolor(value);
	var color_setting = (color.ok)?color.toHex():'#000000';

	var swatch =  new tentacle.element('div',{'id':'swatch_'+this.id+'_'+label,'class':'gui_swatch','style':{'width':this.slider_input_width+'px','height':'18px','background-color':color_setting,'float':'left','margin':this.style_margin+'px'}});
	this.entries[label] = new tentacle.element('input',{'id':this.id+'_'+label+'_input','type':'text','name':this.id+'_'+label+'_input','value':value,'style':{'width':this.slider_width+'px','float':'left'}});

	container.appendChild(this.entries[label]);
	container.appendChild(swatch);
	
	//tentacle.utilities.bind(swatch,'mousedown',tentacle.utilities.closure(this,this.slider_mousedown,label));//add the function to start dragging
	tentacle.utilities.bind(this.entries[label],'change',tentacle.utilities.closure(this,this.color_input_change,label));

	return container;
}
tentacle.properties_window.prototype.button=function(label,value){
	var container = this.entry('&nbsp');
	//var input_button 	= 	new tentacle.element('input',{'type':'button','name':'btnAdd','id':'btnAdd','onclick':'tentacle.database.add_nav()','value':'add new page'});
	this.entries[label] = new tentacle.element('input',{'type':'button','name':this.id+'_'+value+'_button','id':this.id+'_'+value+'_button','style':{'width':this.input_width},'value':value } );
	container.appendChild(this.entries[label]);

	return container;
}
//------------------------
tentacle.properties_window.prototype.remove=function(){
	tentacle.utilities.remove_element(this.id);
}

//--------------------------------
//--------color related functions
//--------------------------------

tentacle.properties_window.prototype.color_input_change=function(e,label){
	var swatch = document.getElementById('swatch_'+this.id+'_'+label);
	var color_input = document.getElementById(this.id+'_'+label+'_input');
	var color = new rgbcolor(color_input.value);
	//alert(color.toHex());
	if(color.ok){
		//alert(swatch.style.backgroundColor);
		//color_input.value = color.toHex();
		swatch.style.backgroundColor=color.toHex();
	}else{
		color_input.value='undfined';
		swatch.style.backgroundColor='#000000';
	}
}

//--------------------------------
//--------slider related functions
//--------------------------------

tentacle.properties_window.prototype.slider_mousedown=function(e,label){
	if( ! this['slider_'+label+'_keep'] ){//if we are just doing this, we need a new number. if we haven't reset the slider, then dont get a new number
		this['slider_'+label+'_value'] = parseFloat(document.getElementById(this.id+'_'+label+'_input').value);//save the slider value, cause we are going to be maniulation it
		this['slider_'+label+'_keep'] = true;
	}

	this.slider_update(e,label);

	this.slider_temp_updater=tentacle.utilities.closure(this,this.slider_update,label);//i have to make the objet on THIS object so that I can remove them later
	this.slider_temp_releaser=tentacle.utilities.closure(this,this.slider_release,label);

	tentacle.utilities.attach_drag_event( this.slider_temp_updater, this.slider_temp_releaser);
}
tentacle.properties_window.prototype.slider_update=function(e,label){


	var c = tentacle.utilities.relative_mouse_position(e);//chainsaw.dom.mouse_position(e);//get mouse position
	var p = tentacle.utilities.get_position(document.getElementById('gui_slider_bg_'+this.id+'_'+label));//chainsaw.dom.element_position('gui_slider_bg_'+this.id);
	var s = tentacle.utilities.element_size('gui_slider_bg_'+this.id+'_'+label);
	var fg = document.getElementById('gui_slider_fg_'+this.id+'_'+label);
	var v = document.getElementById(this.id+'_'+label+'_input');

	var mouse_offset = c[0]-p[0];
	
	var new_position = this.clamp(mouse_offset-this.style_margin); 
	var bounds = this.slider_bounds( this['slider_'+label+'_value'] );
	var new_value = this.remap(new_position,1,this.slider_width,bounds[0],bounds[1]);
	fg.style.width = new_position;

	//tentacle.console.log(v);
	//tentacle.console.log(bounds[0]+':'+bounds[1]);

	v.value=new_value.toFixed(2);//set the input value
	//this.value = new_value;
	//this.act.call(this,e);
}
tentacle.properties_window.prototype.slider_input_change=function(e,label){
	this['slider_'+label+'_keep'] = false;
	var fg = document.getElementById('gui_slider_fg_'+this.id+'_'+label);
	var v = document.getElementById(this.id+'_'+label+'_input');
	var new_value = parseFloat(v.value);

	if ( isNaN(new_value) ){
		v.value=this['slider_'+label+'_value'];
	}else{
		this.slider_bounds(parseFloat(new_value));
		this['slider_'+label+'_value'] = new_value;

		fg.style.width = this.slider_width/2;
	}
}
tentacle.properties_window.prototype.slider_release=function(e,label){
	tentacle.utilities.remove_drag_event(this.slider_temp_updater,this.slider_temp_releaser);
	delete this.slider_temp_updater;
	delete this.slider_temp_releaser;
	this.entries[label].onchange.call();
}
tentacle.properties_window.prototype.slider_bounds=function(value){
	var vi = parseInt(value);
	
	var span = (vi==0)? 10 : vi*2;
	var v = Math.abs(span);

	return [vi-v, vi+v];
}
tentacle.properties_window.prototype.clamp = function(v) {
  return Math.min(Math.max(v, 1), this.slider_width);
}
tentacle.properties_window.prototype.remap = function(v,l1,h1,l2,h2){
	return l2 + (v - l1) * (h2 - l2) / (h1 - l1);
}
