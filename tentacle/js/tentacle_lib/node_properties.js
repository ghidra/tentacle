tentacle.node_properties=function(index){
	return this.init(index); //to return or not to return, probably
}

tentacle.node_properties.prototype = new tentacle.properties_window();
tentacle.node_properties.prototype.constructor = tentacle.node_properties;

tentacle.node_properties.prototype.init=function(index){
	this.index=index;

	//entry type list has to be also declared here other wise the object assignment doesnt worj right
	this.entry_type_list = {};//this object is used to hold the type to that I can reference it when I am changin input values

	var node = tentacle.nodes.list[ tentacle.nodes.get_index(index) ];//get the node	

	var id = 'property_'+index;//the id of the window
	var title = node.attributes.type+':'+index;//the title of the window
	var blue_print = {};//this is going to be the object that I send to properties_window that will build everything out.
	//this is a cariable we will use to iterate over later to make the function calls
	//now build the blue print
	if( !tentacle.utilities.object_is_empty(node.attributes.in_ports) ){
		//the if is here to filter out pass through type nodes, like css_stack.
		//it just makes a header tab thing to close out
		//maybe i should fix that and make it open nothing, but with out any errors
		for(var group in node.attributes.in_ports){// go through the groups of the paroperties
			blue_print[group] = {};//create object to hold the group
			for(var port_in in node.attributes.in_ports[group]){//now go through the entries of the group			
				switch( node.attributes.in_ports[group][port_in]['type'] ){
					case 'color':
						blue_print[group][port_in]=node.attributes[port_in];
						this.entry_type_list[port_in]='color';
						break;	

					case 'boolean':
						blue_print[group][port_in]=(node.attributes[port_in]=='true')?true:false;
						this.entry_type_list[port_in]='boolean';
						break;

					case 'integer':
						//lets add in the min and max if we have them set
						//var min = ( node.attributes.in_ports[group][port_in].hasOwnProperty('min') )?node.attributes.in_ports[group][port_in]['min']:0;
						//var max = ( node.attributes.in_ports[group][port_in].hasOwnProperty('max') )?node.attributes.in_ports[group][port_in]['max']:0;

						//var slider_settings = [node.attributes[port_in],min,max];

						//blue_print[group][port_in]=slider_settings;
						blue_print[group][port_in]=node.attributes[port_in];
						this.entry_type_list[port_in]='number';
						//this.entry_type_list[port_in]='array';

						break;
					case 'scalar':
						blue_print[group][port_in]=node.attributes[port_in];
						this.entry_type_list[port_in]='number';
						break;
					case 'dropdown':
						//i need to alter this object to know which is the selected value so that it send that along too, and not just the object or variable
						var opt = node.attributes[port_in + '_options'];
						if( Object.prototype.toString.call( opt ) == '[object Object]' ){//this is an object, or assiative array type object
							for(k in opt){
								if(opt[k]==node.attributes[port_in]) opt[k] = {'selected':opt[k]};
							}
						}else{
							for(var i=0; i<opt.length; i++){
								if(opt[i]==node.attributes[port_in]) opt[i] = {'selected':opt[i]};
							}
						}
						//blue_print[group][port_in] = node.attributes[port_in + '_options'];
						blue_print[group][port_in] = opt;
						this.entry_type_list[port_in]='dropdown';
						break;
					default://default is basically string
						blue_print[group][port_in] = node.attributes[port_in];
						this.entry_type_list[port_in]='default';
						break;
				}	 		
			}
		}
	}

	//actually make the window shit
	this.build(id,title,blue_print);

	//now let us add the functions to the window
	var _this =  this;
	for(var key in this.entry_type_list){
		//tentacle.console.log(key);
		//this is some hacky shit, but it works, and keeps the scope
		//tentacle.utilities.bind(this.entries[key],'change',tentacle.utilities.closure(this,this.input_changed,port));
		this.entries[key].onchange = function(port){return function(){ _this.input_changed(port)} }(key);
	}
	//tentacle.console.log('_____________');
	//done here
	return this;
}
tentacle.node_properties.prototype.input_changed=function(port){//port is the port to look in, type is optional value, incase it is a dropdown
	//tentacle.console.log('chaning:'+port);
	var node = tentacle.nodes.list[ tentacle.nodes.get_index(this.index) ];//get the node	
	var value_element = document.getElementById(tentacle.html.node_property_input(this.index,port));//get the input element
	
	var value = '';//set an empty value
	switch(this.entry_type_list[port]){
		case 'dropdown':
			//alert(value_element.options[value_element.selectedIndex].value);
			value = value_element.options[value_element.selectedIndex].value;
			break;
		case 'boolean':
			value = value_element.checked;
			break;
		default:
			value = value_element.value;
			break;
	}
	
	document.getElementById(tentacle.html.node_port_data(this.index,port)).innerHTML = value;//set value on node
	
	node.attributes[port] = (isNaN(value)) ? value: parseInt(value); //make sure we send back a number if it is a number, and not a string
}