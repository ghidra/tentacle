tentacle.node=function(){
	this.init();
}
tentacle.node.prototype.init=function(){
	this.attributes = {};
	this.mode = 0;//standard not collapsed mode
	this.new_port='';//this is a empty waiting variable that is filled in when a new port is being made
	this.min_width=80;
	this.max_width=200;
	this.property_window = {};
	//this.non_ports = ['type','mode','index','left','top','number_ports'];
	return this;
}
tentacle.node.prototype.create=function(i,t,d){//this makes a new node. creation like god
	var _this = this;	
	new Ajax.Request(
        'editor_node_handler.php',{
            method:'get',
            parameters:{
                act:'node',
                node: t,
                index: i,
                data: d
            },
            onSuccess:function(transport){
				_this.replicate(transport.responseText.evalJSON(true));
			},
            onFailure:function(){
            	tentacle.console.log('node.create(index,type,data) failed!');
            	alert('"'+t+'" : node does not exist');
            }
        }
    );
}
tentacle.node.prototype.replicate=function(d){//this makes a node from existing data. read from xml or copied. replicate
	//tentacle.console.log(d['type']);
	this.attributes =  d;
	var new_node = this.build()
	document.getElementById('composition').appendChild(new_node);
	
	if(d.type!='tentacle_compound_open'){
		this.set_width();
		if( d.top==0 && d.left==0 || !d.top && !d.left){
			tentacle.utilities.move_to_center( new_node );
		}
	}

	tentacle.utilities.disable_selection( new_node );
}
//------------------------------------------
tentacle.node.prototype.build = function(){//now we need to build out the node
	//here we build both the standard node which includes the basic terminal or root node
	//also we build the tentacle compound open node which is for when opening compounds to edit them

	var isnt_execute_node = (this.attributes.type != 'execute')?true:false;
	var is_compound_open = (this.attributes.type == 'tentacle_compound_open')?true:false;
	var is_compound = ( this.attributes.read_type=='embedded' || this.attributes.read_type=='compound' )?true:false;

	//-----
	if (is_compound_open){
		var container = new tentacle.element('div',{'id':'node_'+this.attributes.index,'style':{'position':'fixed','top':'32px','left':'0px','width':'100%','z-index':'9997'}});

		var compound_label = new tentacle.element('div',{'id':'compound_id_label','style':{'background-color':'#333333','width':'100%','text-align':'center','margin-bottom':'2px'}});//this needs a mouse down. compound.options()
		var compound_out_ports = new tentacle.element('div',{'id':'compound_out_ports','style':{'width':'100px','float':'right'}});
		var compound_in_ports = new tentacle.element('div',{'id':'compound_in_ports','style':{'width':'100px','float':'left'}});

		compound_label.innerHTML = (this.attributes.label!='')? this.attributes.label : this.attributes.type;

		/*debugger
		for (var n in this.attributes){
			tentacle.console.log(n+':'+this.attributes[n]);
		}*/
		//commands
		var compound_commands = new tentacle.element('div',{'class':'in_string','style':{'width':'100%','background-color':'#D1670E','margin-bottom':'2px'} },'&nbsp;' );
		//var compound_commands_close_capsule = new tentacle.element('div',{'class':'close_capsule','style':{'margin-left':'2px','float':'left'} } );//onmousedown="compound.save_and_close()
		var compound_commands_close_out = new tentacle.element('div',{'class':'close_out','style':{'margin-left':'2px','float':'left'},'onmousedown':'tentacle.compounds.close()'});// onmousedown="compound.close()
		
		//compound_commands.appendChild( compound_commands_close_capsule );
		compound_commands.appendChild( compound_commands_close_out );

		//--------------------

		compound_out_ports.appendChild(compound_commands);
		
		//----set the offset value that I need when editing compounds in the editor
		//this.attributes.compound_node_offset = tentacle.nodes.counter;


		//out ports
		//the in and out classes a re revered here, because of logic that is why
		for(var i=0;i<this.attributes.out_ports.length;i++){
			var d = this.attributes.out_ports[i];
			var out_port_container = new tentacle.element('div',{'class':'in_string','style':{'margin-bottom':'2px'}});
			var out_port = new tentacle.element('div',{'id':d+'_'+this.attributes.index,'class':'in_port'});
			var out_port_label = new tentacle.element('div',{'class':'port_string'},d);
		
			//out_port_label.innerHTML = d;//+ value?
			out_port_container.appendChild(out_port);
			out_port_container.appendChild(out_port_label);

			compound_out_ports.appendChild(out_port_container);
		}
		//out in wait port


		//in ports
		if( !tentacle.utilities.object_is_empty(this.attributes.in_ports) ){//this makes sure the the object has its own properites. its not just empty basically
		//the if here allows for a pass through node, with no attributs in the button
			for(var group in this.attributes.in_ports){
				for(var port_in in this.attributes.in_ports[group]){
					var in_port_container = new tentacle.element('div',{'class':'out_string','style':{'margin-bottom':'2px'}});
					var in_port = new tentacle.element('div',{'id':port_in+'_'+this.attributes.index,'class':'out_port'});
					var in_port_label = new tentacle.element('div',{'class':'port_string'},port_in);

					in_port_container.appendChild(in_port);
					in_port_container.appendChild(in_port_label);

					compound_in_ports.appendChild(in_port_container);
					//compound_in_ports.appendChild( this.build_in_port( port_in, this.attributes.in_ports[group][port_in]) );
				}
			}
		}
		//////
		container.appendChild(compound_label);
		container.appendChild(compound_in_ports);
		container.appendChild(compound_out_ports);

		//container.appendChild(compound_in_ports);

		return container;
	}else{
		//the container
		var container = new tentacle.element('div',{'id': 'node_'+this.attributes.index, 'class': 'aBox','style':{'left':this.attributes.left,'top':this.attributes.top} } );
		
		//the header
		var header = new tentacle.element('div',{'id':'node_bar_color_'+this.attributes.index, 'class':'aBar','style':{'background-color':this.attributes.color_main},'onmousedown':'tentacle.selection.add(event,\''+this.attributes.index+'\',\''+this.attributes.color_main+'\',\''+this.attributes.color_alt+'\')','onDblclick':'tentacle.nodes.inspect(\''+this.attributes.index+'\')'} );
		var label = new tentacle.element('div',{'id':'node_'+this.attributes.index+'_type'});
		
		//set the node label	
		label.innerHTML = (this.attributes.label!='')? this.attributes.label : this.attributes.type;

		if( isnt_execute_node && this.attributes.index){ //i'm not sure why i am checking index, i just have been, i many not need to
			var close_out = new tentacle.element('div',{'class':'close_out','onmousedown':'tentacle.nodes.remove(\''+this.attributes.index+'\')'});
			var close_capsule = new tentacle.element('div',{'class':'close_capsule','onmousedown':'tentacle.nodes.toggle_capsule(\''+this.attributes.index+'\')'});
			var close_capsule_shape = new tentacle.element('div',{'class':'close_capsule_shape'});
			var node_read_type = new tentacle.element('div',{'id':'node_read_type_'+this.attributes.index,'style':{'display':'none'}});

			close_capsule.appendChild(close_capsule_shape);
			header.appendChild(close_out);
			header.appendChild(close_capsule);

			if (is_compound) {
				var node_open_compound = new tentacle.element('div',{'class':'open_compound','onmousedown':'tentacle.nodes.open_compound(\''+this.attributes.index+'\')'});
				header.appendChild(node_open_compound);
			}

			header.appendChild(node_read_type);
		}
			
		header.appendChild(label);
		container.appendChild(header);
		
		//the ports
		if( isnt_execute_node ){
			//only apply to non execute nodes
			//out ports
			for(var i=0;i<this.attributes.out_ports.length;i++){
				var d = this.attributes.out_ports[i]
				var out_port_container = new tentacle.element('div',{'class':'out_string'});
				var out_port = new tentacle.element('div',{'id':d+'_'+this.attributes.index,'class':'out_port','onmousedown':'tentacle.connections.create(event,\''+this.attributes.index+'\',\''+d+'\')'});
				var out_port_label = new tentacle.element('div');
			
				out_port_label.innerHTML = '&nbsp;&nbsp;'+this.attributes.out_ports[i];
				out_port_container.appendChild(out_port);
				out_port_container.appendChild(out_port_label);
				container.appendChild(out_port_container);
			}
			//in ports
			//check if there any properties at all. this is a ecma 5 solutino, whatever that means
			if( !tentacle.utilities.object_is_empty(this.attributes.in_ports) ){//this makes sure the the object has its own properites. its not just empty basically
			//the if here allows for a pass through node, with no attributs in the button
				for(var group in this.attributes.in_ports){
					for(var port_in in this.attributes.in_ports[group]){
						container.appendChild( this.build_in_port( port_in, this.attributes.in_ports[group][port_in]) );
					}
				}
			}
			//the content ports
			if(this.attributes.number_ports>0){//if there are ports to even make
				for(var i=0;i<this.attributes.number_ports;i++){
					container.appendChild( this.build_in_port(this.attributes.ports+i) );
				}
				container.appendChild( this.build_wait_port(this.attributes.ports) );
			}
		}else{
			//only apply to execute node
			//only needs the one content port
			//container.appendChild( this.build_in_port(this.attributes.ports) );
			for(var i=0;i<this.attributes.number_ports;i++){
				container.appendChild( this.build_in_port(this.attributes.ports+i) );
			}
			//container.appendChild( this.build_wait_port(this.attributes.ports) );
		}
		//wrap it up
		return container;
	}
}

//----------
//these functions build the content ports, and wait ports
//----------
tentacle.node.prototype.build_in_port = function(port,attr){//now we need to build out the node
	//i'm not using the attributes object yet, but it has the port type, and the port exposed or not boolean
	var in_port_container = new tentacle.element('div',{'id':port+'_'+this.attributes.index+'_port','class':'in_string'});
	var in_port = new tentacle.element('div',{'id':port+'_'+this.attributes.index,'class':'in_port','onmousedown':'tentacle.connections.pop(event,\''+this.attributes.index+'\',\''+port+'\')'});
	var in_port_label = new tentacle.element('div',{'id':port+'_'+this.attributes.index+'_type','style':{'float':'left'}});
	var in_port_value = new tentacle.element('div',{'id':port+'_'+this.attributes.index+'_data'});
	
	in_port_label.innerHTML = port+':';
	in_port_value.innerHTML = (this.attributes[port])?this.attributes[port]:'';
	in_port_container.appendChild(in_port);
	in_port_container.appendChild(in_port_label);
	in_port_container.appendChild(in_port_value);
	
	return in_port_container;
}
tentacle.node.prototype.build_wait_port = function(port){//now we need to build out the node
	var wait_port_container = new tentacle.element('div',{'id':port+'_'+this.attributes.index+'_port','class':'in_string'});
	var wait_port = new tentacle.element('div',{'id':port+'_'+this.attributes.index,'class':'inwait_port'});
	var wait_port_label = new tentacle.element('div',{'style':{'float':'left'}});
	
	wait_port_label.innerHTML = 'new '+port;
	wait_port_container.appendChild(wait_port);
	wait_port_container.appendChild(wait_port_label);
	
	return wait_port_container;
}
//----------
//----------

tentacle.node.prototype.inspect = function(){
	var i = this.attributes.index;
    if(!( document.getElementById(tentacle.html.node_property(i)) )){                      //if there isn't already a property page open
		this.property_window = new tentacle.node_properties(this.attributes.index);
    }
}
/*tentacle.node.prototype.update = function(){                                    //called from closing out property page - this function is sometimes called from root, and finds no form. May need to make it not try to do the or loop. maybe
  	var ind= this.attributes.index;
	var form_name = tentacle.html.node_property_form( ind,this.attributes.index );          //name of the form
	for(i=0;i<document[form_name].elements.length;i++){
        var port_type=document[form_name].elements[i].name
       	var my_data = document.getElementById( tentacle.html.node_port_data(ind,port_type) );
        if(document[form_name].elements[i].type=="checkbox"){       //if it is a check box
            if(document[form_name].elements[i].checked){
                my_data.innerHTML=document[form_name].elements[i].value;//if there is a port to put the result in
            }else{
                my_data.innerHTML="false";
            }
        }else{
            my_data.innerHTML=document[form_name].elements[i].value;//if there is a port to put the result in
        }
    }
	floating_window.drag_stop();//forse the floating window drag stop to stop. has to be here too, otherwise it doesn't stop it
  	tentacle.utilities.remove_element(tentacle.html.node_property(ind));                          //remove the property
    //tentacle.console.log('node.update('+index+',\''+type+'\')');
},*/
/*tentacle.node.prototype.port_data = function(){
	//this function should soon be obsolete too--after javascript refactoring
	var temp = '';
   	var a = {};
	var i = this.index;
   //	var port_names = this.collect_port_names();
   //	port_names.names.each(function(name){
   	//	var data_div = $(tentacle.html.node_port_data(i,name));
      // 	if(data_div) a[name] = data_div.innerHTML;
   	//});
   	//return a;                                                       // {port : data , port : data }
	for (var i=0; i< this.attributes.in_ports.length; i++){
		//a[key]=this[key];
		temp += this.attributes.in_ports[i]+':'+this.attributes.in_ports_type[i];
	}
	//alert(a);
	alert(temp);
}*/

tentacle.node.prototype.insert_port=function(){
	var id = this.attributes.index;
	var port_name = this.attributes.ports+this.attributes.number_ports;
	var new_port = this.build_in_port(port_name);
	var node = document.getElementById( tentacle.html.node(id) );
    node.insertBefore(new_port,node.lastChild);
	
	this.new_port=port_name;
	this.attributes.number_ports++;

    //tentacle.console.log('node.insert_port('+index+',\''+port+'\')');
}
/*tentacle.node.prototype.count_port = function (port){
   	var b = 0;
    var port_names = this.collect_port_names();
    //alert('has');
	port_names.names.each(function(name,i){
        name_clean = name.replace(/[0-9_]+/g,"");
        if(name_clean==port) {
            var first_count = port_names.duplicates[i];             //when we make out first duplicate is finds nothing here...
            b = (first_count)?port_names.duplicates[i]+1:1;         //add one cause the number returned is not counting the first one
            throw $break;                                           //stop looking (prototype style break i guess)
        }
    });
    //tentacle.console.log('node.count_port('+index+',\''+port+'\')');
    return b;                                                    // 1
}
*/
tentacle.node.prototype.collect_port_names=function(){
	var port_names = [];
	//get ports from the in_ports object
	for(var group in this.attributes.in_ports){
		for(var port_in in this.attributes.in_ports[group]){
			port_names.push(port_in);
		}
	}
	//get any content ports
	for(var i=0;i<this.attributes.number_ports;i++){
		port_names.push(this.attributes.ports+i);
	}
	//if there are in fact content ports, add in the 'new port' port
	if(this.attributes.number_ports>0) port_names.push(this.attributes.ports);
	return port_names;
}
tentacle.node.prototype.toggle_capsule = function(){
	tentacle.console.log('trying to minimize');	
	this.mode = (this.mode==1)?0:1;
	var ind = this.attributes.index
    var ports = this.collect_port_names();
   	var connected_lines = tentacle.connections.connected(ind);
    var in_exposed = false;
	
	for (var i = 0; i<ports.length; i++){
		var name = ports[i];
        if(this.mode==0){
           	if( $( tentacle.html.node_in_port(ind,name) ).hasClassName('in_port') && !in_exposed){
            	in_exposed=true;
               	$( tentacle.html.node_port_type(ind,name) ).setStyle({display:''});
              	$( tentacle.html.node_port_data(ind,name) ).setStyle({display:''});
            }else{
              	$( tentacle.html.node_port(ind,name) ).setStyle({display:''}); 
            }
        }else{//we are now going to minimize the node
           	if( $( tentacle.html.node_in_port(ind,name) ).hasClassName('in_port') && !in_exposed){
                in_exposed=name;//set the in_exposed var to the name of the port exposed
              	$( tentacle.html.node_port_type(ind,name) ).setStyle({display:'none'});
               	$( tentacle.html.node_port_data(ind,name) ).setStyle({display:'none'});
            }else{
               	$( tentacle.html.node_port(ind,name) ).setStyle({display:'none'}); 
            }
        }
    }
    //now if we have an exposed por to be connected to
	if( in_exposed ) tentacle.connections.assign_minimize_port(connected_lines,in_exposed,ind); //set the
    connected_lines.each(function(id){
		tentacle.connections.list[id].draw();
    });
    //tentacle.console.log('node.toggle_capsule('+index+')');
}
tentacle.node.prototype.set_width = function(){
   	var n = document.getElementById(tentacle.html.node(this.attributes.index));
    var width = n.getWidth();
    width = (width < this.min_width) ? this.min_width : width;
    width = (width > this.max_width) ? this.max_width : width;
    n.setStyle({ width : width+'px'});
}