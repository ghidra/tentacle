/*node "class"
    .embedded : object//passed from open i_o
    .open : function(index) - send data to server to assemble html for editing
 	.find_compound :function(index) -finds if there are any more compounds in the embedded compound
----------------*/
/*
tentacle.compounds={
	'open':function(i,ii){
		alert('the fuck');
	}
}
*/

tentacle.compounds = {
    'embedded': [],
	'level':[],
	'seeking':false,

	'open':function(index,compound_index){
		//
		if(isNaN(index)){//this is a file compound

		}else{//this is an embedded xompound
			new Ajax.Request(
				'xml_file_handler.php',{
					method:'get',
					parameters:{
						act:'open_embedded_compound',
						data: Object.toJSON( this.embedded[compound_index] ),
						count: tentacle.nodes.counter,
						ccount: tentacle.connections.counter,
						nid : index,
						cid : compound_index
					},//data: Object.toJSON( this.embedded[compound_index] ),
					///////

						//JSON.stringify( this.embedded[compound_index] ),
						//so it apperas that whatever toJSON bullshit is broken and not giving good json results. booooooo

					//////
					onSuccess:function(transport){
						//something is not happeneing right here again. maybe it is the eval, not sure. But it worked fine in
						//windows on chrome, but not here on mac
						var d = transport.responseText.evalJSON(true);
						//var d = JSON.parse(transport);
						//alert(d[0]);

						document.getElementById('composition').style.display='none';//setStyle({display:'none'});
						document.getElementById('composition').id='composition_base';//setAttribute('id','composition_base');
						
						document.getElementById('node_root').id='node_root_base';

						var a = new tentacle.element('div',{'id':'composition'});
						//var a=new Element('div',{'id':'composition'}).update(d[0]);
						document.body.appendChild(a);
						tentacle.compounds.open_terminal(d[2]);//this is just the execute node
                        tentacle.nodes.from_xml(d[0]);//put the property at the fromnt of the document
                        tentacle.connections.from_xml(d[1]);
                        

						//alert('something went right');
						//tentacle.compound.level[ tentacle.compound.level.length ] = [tentacle.nodes.counter,last_connection_count,parseInt(embedded_id),index];//d[2];//giving some data to store for compounts, like how many nodes existed before, and connections, the array id in compounds, and the index of the compound that called it to be opened
						
						///////
						//tentacle.i_o.init_values();//init them values 

                        //for a second time do this loop just to set the capsule
                        //for(var i=0;i<d[2];i++){//because we set the connections then set the collapsed nodes as collapsed
							//I NEED TO UPDATE THIS. THIS FUNCTION IS OBSOLETE,
							//NOW I JUST NEED TO TOGGLE THE CAPSULE WHEN READY
                      		///node.set_capsule(i);
                        //}
                  		//tentacle.selection.set_composition_dimensions();

					},
					onFailure:function(){alert('something went wrong opening compound')}
				}
			);
			//----------------
		}
	},
	'open_terminal':function(d){
		//tentacle.console.log('making trminals');
		//basically a copy of the nodes xml_termianl function. makes a node. in the node it makes the compound html
		this.level[this.level.length] = new tentacle.node();
		//alert(tentacle.utilities.obj_to_string(d));
    	this.level[this.level.length-1].replicate(d);
	},
	//called from the compound node, when open. wraps it up and gives data to tentacle.compounds
	'close':function(){
		//tentacle.console.log('in here');
		var json = {};//set up the jsoon objecy
        ////var node_recount = {}; //and object to easily grab the changed renumbering due to deleting
        var compound_node_offset = this.level[this.level.length-1].attributes.compound_node_offset;
        var compound_connection_offset = this.level[this.level.length-1].attributes.compound_connection_offset;
        var compound_id = this.level[this.level.length-1].attributes.compound_id;
        var compound_node_id = this.level[this.level.length-1].attributes.compound_node_id;

        var packaged_nodes = tentacle.i_o.package_nodes( compound_node_offset );//this is coming back with 2 values, the packaged nods, and the node recount array
        var packaged_connections = tentacle.i_o.package_connections(packaged_nodes[1],compound_connection_offset);//this also comes with 2 parts of data, the packaged connections and the parts of the exposed_ports that are the rot node

        json.nodes = packaged_nodes[0];//new Array();
        json.connections = packaged_connections[0];//new Array();//set up the connections part of the object
        json.result = packaged_connections[1];

        json.results = new Array();
		json.input = new Array();
		
		json.name = document.getElementById('compound_id_label').innerHTML;
		
		//json.result[0]={};
		json.result[0].index='root';
		json.result[0].read_type='node';
		json.result[0].type='tentacle_compound_open';
		json.result[0].left=0;
		json.result[0].top=0;
		
		//---now effect the editor representation
		tentacle.nodes.list.splice(compound_node_offset , tentacle.nodes.list.length - compound_node_offset );//( tentacle.compound.level[tentacle.compound.level.length-1][1], tentacle.connections.list.length-tentacle.compound.level[tentacle.compound.level.length-1][1] )
		tentacle.nodes.counter = compound_node_offset;//tentacle.compound.level[tentacle.compound.level.length-1][0];
		tentacle.connections.list.splice(compound_connection_offset , tentacle.connections.list.length - compound_connection_offset );//( tentacle.compound.level[tentacle.compound.level.length-1][1], tentacle.connections.list.length-tentacle.compound.level[tentacle.compound.level.length-1][1] )
		tentacle.connections.counter = compound_connection_offset;//tentacle.compound.level[tentacle.compound.level.length-1][1];
		
		document.getElementById('composition').remove();		
		//i still need to aaccount for levels here, so that I can have multiple compounds open at once
		document.getElementById('composition_base').style.display='';
		document.getElementById('composition_base').id='composition';
		document.getElementById('node_root_base').id='node_root';
		/**/
		//--------------------Replace the old compound data with the new compound if there are changes to the exterior
		//-this is the id of the node that was/is the compound --- tentacle.compound.level[tentacle.compound.level.length-1][3];
		document.getElementById('node_' + compound_node_id + '_type').innerHTML = json.name;//this updates the name of the compound node
		
		this.embedded[ compound_id ] = json;
		this.level.splice(this.level.length-1,1);
		
        tentacle.console.log('tentacle.compound.close() mo fo');
	}

}

////////////

////////////

////////////

tentacle.compound_OLD = {
    'embedded': [],
	'level':[],
	'seeking':false,//set to true when we are trying to create a new compound input pot
	//'node_count': 0,//the origional node count before opeing a compound up
    /*'open': function(index){
	//I think this only works on embedded compounds at the moment, because I'm not even checking if it is embeddd or not
		var embedded_id = $(tentacle.html.embedded_id(index)).innerHTML;
		//i need to force some other data in here so that php know what to do.
		//this.embedded[embedded_id].node_id = index;
		this.embedded[embedded_id].embedded = this.find_compound(embedded_id);//force embedded data in the passes data, so that the assemble can deal with it
		//alert(this.embedded[embedded_id]);
		new Ajax.Request(
			'xml_file_handler.php',{
				method:'get',
				parameters:{
					act:'open_embedded_compound',
					data: Object.toJSON( this.embedded[embedded_id] ),
					count: tentacle.nodes.counter
				},
				onSuccess:function(transport){
					//this.node_count=node.counter;
					var d = transport.responseText.evalJSON(true);
					//var c = $('composition');
					$('composition').setStyle({display:'none'});
					$('composition').setAttribute('id','composition_base');
					
					var a=new Element('div',{'id':'composition'}).update(d[0]);
					document.body.appendChild(a);
					//c.innerHTML+=d[0];
					//c.innerHTML+='<div id="whatwhat">hahaha</div>';
					var last_connection_count=tentacle.connections.counter; //get the connection count before altering it with insert
					for (var i=0;i<d[1].length;i++){
						d[1][i].index+=last_connection_count;//manually up the connection count so it inserts propperly
                //	connection.insert(d[1][i]);//TURNING THIS OFF TO AVOIV ERRORS
						//alert(d[1][i].from_node+'_'+d[1][i].to_node+'_'+d[1][i].from_port+'_'+d[1][i].to_port);
                     }
					tentacle.compound.level[ tentacle.compound.level.length ] = [tentacle.nodes.counter,last_connection_count,parseInt(embedded_id),index];//d[2];//giving some data to store for compounts, like how many nodes existed before, and connections, the array id in compounds, and the index of the compound that called it to be opened
					tentacle.nodes.counter+=d[2];
					//alert(d[0]);
					//alert(d[0]);
				},
				onFailure:function(){alert('something went wrong opening compound')}
			}
		);
		//tentacle.console.log('tentacle.compound.open(\''+index+'\')');
	},*/
	'close':function(){//well need to be able to close
		//
		//
		//
		//the following was stolen mostly from tentacle.i_o.package_comp
		//this is a similar function, but needs to be aware what compound we are in
		//and ignore other nodes and connection
		//
		//alert(tentacle.compound.level);
		//
		var obj_jsoon={};//set up the jsoon objecy
        var obj_recount={};//and object to easily grab the changed renumbering due to deleting
        //----------------recount
        var recount = 0;//recount the nodes so they go from 0, to remove deleted nodes numbers
        var e_count=0;
        var c_count=0;
		//----------------name
		
		obj_jsoon.name=$('compound_id_label').innerHTML;
		
        //----------------nodes
        obj_jsoon.nodes = new Array();
/*n*/   for(k=0;k<=tentacle.nodes.counter;k++){
			
			//
			//obj_jsoon.nodes[recount]={};
			//obj_jsoon.nodes[recount].k=k;
			//obj_jsoon.nodes[recount].l=tentacle.compound.level[0];
			if(k >= tentacle.compound.level[tentacle.compound.level.length-1][0]){//this is the offset
			//
			
/*h*/       var node_div = $(tentacle.html.node(k));
            if(node_div){//if node hasn't been deleted, since I am only counting up, and not keeping node data in javascript anywhere
                obj_recount[k]=recount;//set the recount object numbers
                obj_jsoon.nodes[recount] = {};//make the object to hold node data
				//
				//
				obj_jsoon.nodes[recount].read_type='node';
/*h*/           obj_jsoon.nodes[recount].type = $(tentacle.html.node_type(k)).innerHTML;//node_div.firstChild.lastChild.innerHTML;;// node_div.firstChild.innerHTML;
                obj_jsoon.nodes[recount].mode = $(tentacle.html.node_capsule(k)).innerHTML;//
                obj_jsoon.nodes[recount].index =recount+'';//turn it to a string
				//                
				//obj_jsoon.nodes[recount].param = new Array();
				//tentacle.nodes.list[0] IS WRONGGGGG
/*n*/           var node_ins = tentacle.nodes.list[0].port_data(k);//collect the input ports
/*n*/           var content_ins = tentacle.nodes.list[0].count_port(k,'content');//collect the input ports
                //for use with object instead of array now
                var j =0;
                for (var prop in node_ins) {
                    //obj_jsoon.nodes[recount].param[j] = {};
/*n*/               //obj_jsoon.nodes[recount].param[j][prop] = node_ins[prop];
                    //j++;
/*n*/               obj_jsoon.nodes[recount][prop] = node_ins[prop];
                }
			
/*u n*/         var n_position=tentacle.utilities.get_position($(tentacle.html.node(k)));
                //obj_jsoon.nodes[recount].guidef={};
                //obj_jsoon.nodes[recount].guidef.left=n_position[0];
                //obj_jsoon.nodes[recount].guidef.top=n_position[1];
                //obj_jsoon.nodes[recount].guidef.number_ports=content_ins;

				obj_jsoon.nodes[recount].left=n_position[0];
                obj_jsoon.nodes[recount].top=n_position[1];
                obj_jsoon.nodes[recount].number_ports=content_ins;
                recount++;//up the recount
            }

			//
			}
			//

        }
        //----------------exposed ports
        //obj_jsoon.exposed_ports = new Array();//set up the connections part of the object
        //----------------connections
        obj_jsoon.connections = new Array();//set up the connections part of the object
		obj_jsoon.results = new Array();
		obj_jsoon.input = new Array();
		obj_jsoon.result = new Array();
		
		obj_jsoon.result[0]={};
		obj_jsoon.result[0].index='root';
		obj_jsoon.result[0].read_type='node';
		obj_jsoon.result[0].type='tentacle_compound_open';
		
/*c*/   for(i=0;i<tentacle.connections.list.length;i++){
			
			//
			if(i >= tentacle.compound.level[tentacle.compound.level.length-1][1]){
				if (tentacle.connections.list[i].from_node=='root'){
					var new_from_node = 'root';
					//also add the pot to the result array
					obj_jsoon.result[obj_jsoon.result.length]={};
					obj_jsoon.result[obj_jsoon.result.length-1][tentacle.connections.list[i].from_port]=['in'];
				}else{
		/*c*/       var new_from_node = obj_recount[tentacle.connections.list[i].from_node] + '' ;//turn it to a string get the update from node
				}
				//
				if(tentacle.connections.list[i].to_node=='root'){
					var new_to_node='root';
					//also add this to result object
					obj_jsoon.result[0][tentacle.connections.list[i].to_port]=['out'];
				}else{
		/*c*/       var new_to_node = obj_recount[tentacle.connections.list[i].to_node] + '' ;//get the update from node
				}
			//
				obj_jsoon.result[0].left="0";
				obj_jsoon.result[0].top="0";
/*c*/       //if(connection.list[i].to_node!="root"){//dot do the one to the root
                obj_jsoon.connections[c_count] = {};//set the connection as an object
                obj_jsoon.connections[c_count].from_node = new_from_node;
/*c*/           obj_jsoon.connections[c_count].from_port = tentacle.connections.list[i].from_port;
                obj_jsoon.connections[c_count].to_node = new_to_node;
/*c*/           obj_jsoon.connections[c_count].to_port = tentacle.connections.list[i].to_port;
                c_count++;
            //}else{//this is part of the exposed ports
              //  obj_jsoon.exposed_ports[e_count] = {};
               // obj_jsoon.exposed_ports[e_count].index = new_from_node;
/*c*/          // obj_jsoon.exposed_ports[e_count].name = connection.list[i].from_port;
/*c*/           //obj_jsoon.exposed_ports[e_count].label = connection.list[i].from_port;
                //obj_jsoon.exposed_ports[e_count].type="out";
                //e_count++;
            //}

			//
			}
			//

        }
        //--finish off ecposed ports with the terminal node if it is there
/*u h*/ /*var r_position = tentacle.utilities.get_position($(tentacle.html.node('root')));
        obj_jsoon.exposed_ports[e_count] = {};
        obj_jsoon.exposed_ports[e_count].left = r_position[0];
        obj_jsoon.exposed_ports[e_count].top = r_position[1];
        //-----------------send the file name.
        obj_jsoon.save_as = "tentacle/compounds/a_new_compound";//path+"/"+file;
		*/
		//-----make the data json
		//----now if we need to add into the mix, some embedded data
		/*if (tentacle.compound.embedded.length > 0){
			obj_jsoon.embedded=tentacle.compound.embedded;
			//final_json_object.embedded=tentacle.compound.embedded;
		} */
		
		//-------------------close compound and restore layered compositions
		tentacle.nodes.counter = tentacle.compound.level[tentacle.compound.level.length-1][0];
		//i am breaking the connection somewhere
		tentacle.connections.list.splice( tentacle.compound.level[tentacle.compound.level.length-1][1], tentacle.connections.list.length-tentacle.compound.level[tentacle.compound.level.length-1][1] )
		tentacle.connections.counter=tentacle.compound.level[tentacle.compound.level.length-1][1];
		//connection.live=connection.list.length-1;
		$('composition').remove();
		//tentacle.compound.level.splice(tentacle.compound.level.length-1,1);
		
		//i still need to aaccount for levels here, so that I can have multople compounds open at once
		
		$('composition_base').setStyle({display:''});
		$('composition_base').setAttribute('id','composition');
		
		//--------------------Replace the old compound data with the new compound if there are changes to the exterior
		//-this is the id of the node that was/is the compound --- tentacle.compound.level[tentacle.compound.level.length-1][3];
		$('node_' + tentacle.compound.level[tentacle.compound.level.length-1][3] + '_type').innerHTML=obj_jsoon.name;//this updates the name of the compound node
		//i could also update the unputs ad out pts with the node.insert_port
        //-------------------JSON set the json data on the embedded array
		
		tentacle.compound.embedded[ tentacle.compound.level[tentacle.compound.level.length-1][2] ] = obj_jsoon;
		tentacle.compound.level.splice(tentacle.compound.level.length-1,1);
		
        tentacle.console.log('tentacle.compound.close()');
		//return final_json_object;
		//alert( Object.toJSON(obj_jsoon));
		//i need to give this json objecy back to embeeded data
		//return Object.toJSON(obj_jsoon);
	},
	'save_and_close':function(){
		//alert('make a move');
		if( !($('save_compound')) ){
            new Ajax.Request(
                'xml_file_handler.php',{
                    method:'get',
                    parameters:{
                        act:'save_compound',
                        file: $('compound_id_label').innerHTML,//this just gets the name of the compound from the bar
                        path: 'media/compounds'
                    },
                    onSuccess:function(transport){
                        floating_window.create('save_window','window_box',transport.responseText);//has to be called save_window cause that is what is being made in the html
                    },
                    onFailure:function(){alert('something went wrong saving compound')}
                }
            );
        }
        tentacle.console.log('tentacle.i_o.save_comp()');
	},
	'save':function(){//very similar to tentacle.i_o.write_comp()
		//alert('make a save');
		this.file_name = document['save_form'].elements[0].value;
        var str_json = '';//this.package_comp(this.save_path,this.file_name);
        new Ajax.Request(
            'xml_writer.php',{
/*-->*/         method:'post',
                parameters:{
                    data:str_json
                },
                onSuccess:function(transport){
                    $('save_window').remove();
                   // alert(transport.responseText);
                    //log_to_console(transport.responseText);
                },
                onFailure : function(){alert('broken')}
            }
        );
        tentacle.console.log('tentacle.i_o.write_comp()');
	},
	'inspect':function(event){
		//I need to somehow find out which node parameters are exposed
		var clicked = Event.element(event);
        var nodeindex = clicked.parentNode.parentNode.id.replace(/node_/g,"");//now I know the node Index
		var embedded_id = $(tentacle.html.embedded_id(nodeindex)).innerHTML;//now I know the embedded compound ID for the javascript object
		var nodetype = $(tentacle.html.node_type(nodeindex)).innerHTML;
		
		var node_info = {'compound_name':nodetype};//just an empty array in wait, with the name of the compound the first value by default
		
		if(!( $(tentacle.html.node_property(nodeindex)) )){ //lets only do all this if there isn't already a property page open fr this compound
		//now I should assmeble and object to send to php, that has the node type, with the port I am trying to inspect, based on all the connectsion from the 'root' node aka the compound node
			var con_array = tentacle.compound.embedded[embedded_id].connections
			var c = 0;//just a counter
			for(i=0;i<con_array.length;i++){
				if(con_array[i].from_node=='root'){
					node_info['input_'+c]={};//an object to hold my data per input
					node_info['input_'+c]['data_node_type'] = tentacle.compound.embedded[embedded_id].nodes[ con_array[i].to_node ].type;//get the type of the connected node
					node_info['input_'+c]['data_node_port'] = con_array[i].to_port;//get the port name
					node_info['input_'+c]['data_comp_port'] = con_array[i].from_port;//the compound port name
					node_info['input_'+c]['data_comp_valu'] = $(tentacle.html.node_port_data(nodeindex,con_array[i].from_port)).innerHTML;//the compound port value
					
					c++;//increment the count
				}	
			}			
			var my_data = Object.toJSON(node_info); 
			
			new Ajax.Request(
                'editor_node_handler.php',{
                    method:'get',
                    parameters:{
                        act:'inspect_compound',
                        node: 'tentacle_compound_open',
                        index: nodeindex,
                        data: my_data
                    },
                    onSuccess: function(transport){
                 		floating_window.create( tentacle.html.node_property(nodeindex),"propertyBox",transport.responseText );
                    },
                    onFailure: function(){ alert('something went wrong inspecting embedded_compound') }
                }
            );
		}
		//alert(my_data);
	},
	'find_compound':function(id){//check the compound for more compounds. I need to send this info to the server to save having to send a lot more data
		var embedded_temp = new Array( tentacle.compound.embedded.length );//a temp arry to hold me data
		tentacle.compound.embedded[id].nodes.each(function(item,i){//check the nodes in the embeeded data
			if(item.read_type == 'node') {
				var temp_id = item.type.split('_')[1];//the embedded id
				embedded_temp[temp_id] = tentacle.compound.embedded[temp_id];
			}
		});
		return embedded_temp;//tentacle.compound.embedded[0].nodes[0].type;//
	},
	'find_in_port':function(event){//we are gonna crate a new connection and see if we can't get a new port for our compound
		tentacle.compound.seeking=true;
		//I NEED THIS< TURNING IT OF TO AVOID ERRORS tentacle.connections.create(event,'root','out');
	},
	'in_port_found':function(id,p){
		tentacle.compound.seeking=false;
		//now I need to check and see if I should rename the label, if "p" exists
		p = tentacle.compound.avoid_label_duplicates(p,'out');
		new Ajax.Request(
            'editor_node_handler.php',{
                method:'get',
                parameters:{
                    act:'compound_in_port',
					node: 'tentacle_compound_open',
                    index: id,
                    number_ports: p//bootleggin. Number of prots here is actually the NAME of the port. bypassing stupid code form earlier, with more stupid code
                },
                onSuccess:function(transport){
					var n_p = new Element('div', { 'class': 'out_string', 'style': 'margin-bottom:2px' });
					n_p.innerHTML=transport.responseText
					var pp = $('compound_out_ports');
					pp.insertBefore(n_p,pp.lastChild);
					tentacle.connections.list[tentacle.connections.live].from_port=p;
                },
                onFailure:function(){alert('something went wrong inserting a compound import')}
            }
        );
	},
	'create_output':function(id,p){
		p = tentacle.compound.avoid_label_duplicates(p,'in');
		new Ajax.Request(
            'editor_node_handler.php',{
                method:'get',
                parameters:{
                    act:'compound_out_port',
					node: 'tentacle_compound_open',
                    index: id,
                    number_ports: p//bootleggin. Number of prots here is actually the NAME of the port. bypassing stupid code form earlier, with more stupid code
                },
                onSuccess:function(transport){
					var n_p = new Element('div', { 'class': 'in_string', 'style': 'margin-bottom:2px' });
					n_p.innerHTML=transport.responseText
					var pp = $('compound_in_ports');
					pp.insertBefore(n_p,pp.lastChild);
					tentacle.connections.list[tentacle.connections.live].from_port=p;
                },
                onFailure:function(){alert('something went wrong inserting a compound import')}
            }
        );
	},
	'avoid_label_duplicates':function( l, d ){//once an import is found we need to collect
		if ( d=='out' ){
			var children = $( 'compound_out_ports' ).childNodes;
		}else{
			var children = $( 'compound_in_ports' ).childNodes;			
		}
		var labels = [];                                                 //object to hold my returning values
		var unique = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
		var unique_counter = 0;
		var new_label = l;
		//collect the labels
		for (var i=0;i<children.length;i++){                            //loop through the divs in the node, to find the ports labels.
			children_b = children[i].childNodes;			
			for (var j=0; j<children_b.length; j++){//now we need to grab the layer that has the text 				
				if( children_b[j].className == "port_string" ){		
					labels[labels.length] = children_b[j].innerHTML.split(':')[0];//put name of labels in an array
				}
			}
		}
		//now check to see if we have a duplicate
		for (var i=0; i<labels.length; i++){
			if(labels[i] == new_label){//we have a duplicate
				new_label = l + unique[unique_counter];//add a letter to the duplicate
				unique_counter++;
			}
		}
		return new_label;
	},
	'options':function(event){//called from the internal compound bar. for renaming the compund from the inside for now
		//alert('options');
		//var clicked = Event.element(event);
        //var nodeindex = clicked.parentNode.parentNode.id.replace(/node_/g,"");
        if(!( $('compound_options') )){                      //if there isn't already a property page open
            //var nodetype = clicked.innerHTML;
            //var portvalues = this.port_data(nodeindex);                 //because we have in ports	
            var my_data = Object.toJSON( {id:$('compound_id_label').innerHTML} );                    //turning this into json just cause
            new Ajax.Request(
                'editor_node_handler.php',{
                    method:'get',
                    parameters:{
                        act:'compound_options',
                        node: 'tentacle_compound_open',
                        index: 'root',
                        data: my_data
                    },
                    onSuccess: function(transport){
						floating_window.create( 'property_root',"propertyBox",transport.responseText );
                    },
                    onFailure: function(){ alert('something went wrong doing the options thing') }
                }
            );
        }
        //tentacle.console.log('node.inspect(event) //-->$(tentacle.html.node_bar('+nodeindex+')).onDblclick()');
	},
	'update_options':function(index,type){//index and type are kind of redundant, since it will allwats be tentacle_compound_open, and root
		//alert('update bitch');
/*h*/   var form_name = tentacle.html.node_property_form( index );          //name of the form
        for(i=0;i<=document[form_name].elements.length-1;i++){
            var port_type=document[form_name].elements[i].name
/*h*/       //var my_data = $( tentacle.html.node_port_data(index,port_type) );
            /*if(document[form_name].elements[i].type=="checkbox"){       //if it is a check box
                if(document[form_name].elements[i].checked){
                    my_data.innerHTML=document[form_name].elements[i].value;//if there is a port to put the result in
                }else{
                    my_data.innerHTML="false";
                }
            }else{*/
               // my_data.innerHTML=document[form_name].elements[i].value;//if there is a port to put the result in
            if(port_type = 'name'){
				$('compound_id_label').innerHTML = document[form_name].elements[i].value;
			}
			/*}*/
        }
        floating_window.drag_stop();//forse the floating window drag stop to stop. has to be here too, otherwise it doesn't stop it
/*h*/   tentacle.utilities.remove_element(tentacle.html.node_property(index));                          //remove the property
        //tentacle.console.log('node.update('+index+',\''+type+'\')');
	},
	'relabel':function(){//we'll need to be able to rename lables.
		alert('relable this bitch');
	}
}