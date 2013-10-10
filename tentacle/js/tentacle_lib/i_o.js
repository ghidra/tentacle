tentacle.i_o = {
    
    'file_name' : '',
    'save_path' : 'saves',
    'property_window' : {},
    'property_window_id' : 'i_o_window',

    'new_comp' : function(reset){
        this.init_values();
        this.file_name='';
        if(reset)this.save_path='saves';
		$('composition').innerHTML='';
		tentacle.nodes.terminal();//make a terminal node
		tentacle.i_o.log_file_name("untitled");
    },
    'save_comp' : function(){
        //this makes the save dialouge window
        if(document.getElementById(this.property_window_id)){
            this.property_window.remove();
            this.property_window={};//clear that shit out now
        }

        this.property_window = new tentacle.properties_window();

        this.property_window.build(
            this.property_window_id,
            'save page',
            {
                'save settings':{
                    'name':this.file_name,
                    '&nbsp':'save'
                }
            }
        );

        tentacle.console.log('tentacle.i_o.save_comp()');

        this.property_window.entries['&nbsp'].onclick=function(){tentacle.i_o.write_comp();};//add the button function

    },
    'write_comp' : function(){
        //this is called from the save dialouge window when the button is hit
        var form=document[this.property_window_id+"_form"];//name of the form
        this.file_name = form[this.property_window_id+"_name_input"].value;
        var str_json = this.package_comp(this.save_path,this.file_name);

        new Ajax.Request(
            'xml_writer.php',{
         		method:'post',
                parameters:{
                    data:str_json
                },
                onSuccess:function(transport){
                    tentacle.i_o.property_window.remove();
                    //tentacle.console.log(transport.responseText);
                },
                onFailure : function(){alert('broken')}
            }
        );

        tentacle.console.log('tentacle.i_o.write_comp()');
    },
    'preview_comp':function(index){
        //THIS FUNCTION HAS NOT BEEN UPDATED
        new Ajax.Request(
            'xml_writer.php',{
                method:'post',
                parameters:{
                    data: tentacle.i_o.package_comp('media/saves','render_tmp')
                },
                onSuccess:function(t){
                    //alert(t.responseText);
                    new Ajax.Request(
                        'render.php',{
                            method:'get',
                            parameters:{
                                header:0,
                                page:'media/saves/render_tmp'
                            },
                            onSuccess:function(transport){
                                $('render_viewer_'+index).innerHTML=transport.responseText;
                            },
                            onFailure:function(){}
                        }
                    );
                },
                onFailure:function(){}
            }
        );
        tentacle.console.log('tentacle.i_o.preview_comp('+index+')');
    },
//-----------#( tentacle.html.node_read_type(k).innerHTML )//now we can get the read type and act accordingly
    'package_comp' : function (path,file){
        var json = {};//set up the jsoon object
        //var node_recount = {}; //and object to easily grab the changed renumbering due to deleting

        var packaged_nodes = this.package_nodes();//this is coming back with 2 values, the packaged nods, and the node recount array
        var packaged_connections = this.package_connections(packaged_nodes[1]);//this also comes with 2 parts of data, the packaged connections and the parts of the exposed_ports that are the rot node

        json.nodes = packaged_nodes[0];//new Array();
        json.connections = packaged_connections[0];//new Array();//set up the connections part of the object
        json.exposed_ports = packaged_connections[1];//new Array();//set up the connections part of the object

        json.save_as = path+"/"+file;

        //-----------------
        //--finish off exposed ports with the terminal node if it is there
        var r_position = tentacle.utilities.get_position(document.getElementById(tentacle.html.node('root')));
        var e = {};
        e.left = r_position[0];
        e.top = r_position[1];
        json.exposed_ports.push(e);        

        //compound stuff
        if (tentacle.compounds.embedded.length > 0){
            json.embedded = tentacle.compounds.embedded;
        }
        //-------------------JSON
        tentacle.console.log('tentacle.i_o.package_comp(\''+path+'\',\''+file+'\')');
        //return final_json_object;
        return Object.toJSON(json);
    },
    'package_nodes':function(offset){
        offset = typeof offset !== 'undefined' ? offset : 0;//if there isn't an offset value sent, then we can just set ut to 0

        var packaged_nodes = new Array();//hold the nodes
        var node_counter=0;
        var node_iterations=0;
        var node_recount=new Array();

        for( var node in tentacle.nodes.list ){//loop the nodes
            if (tentacle.nodes.list.hasOwnProperty(node)) {//keep it from looking at built in object types
                if( node_iterations >= offset ){//deal with only the nodes past the offset

                    var n = tentacle.nodes.list[node].attributes;//shortcut
                    var a = {};//temp object to hold the attributes

                    node_recount[n.index]=node_counter;//set the recount object numbers

                    a.type = (n.type == 'tentacle_compound')? n.label : n.type;//node_div.firstChild.lastChild.innerHTML;;// node_div.firstChild.innerHTML;
                    a.mode = n.mode;//
                    a.index = node_counter;

                    a.read_type = (n.read_type)?n.read_type:'node';//'node';//this seems like it is missing, but also might need to be read from something and not just set as it is now

                    //now if read type is "embedded" or "compound" I need to do some different stuff here to get the right data saved 
                    
                    //now do the node ports
                    //make a new array to hold the parameters specifically, only if we are not dealing with embedded data
                   // if(offset>0){ 
                   //     a.param = {};
                    //}

                    //var property_counter = 0;
                    for (var group in n.in_ports) {//iterate the port groups first
                        for (var prop in n.in_ports[group]){//iterate the properties
                            //if(offset>0){//for embedded data I just copy it straight over
                                a[prop]=n[prop];
                            //}else{
                             //   a.param = new Array();
                                //a.param[property_counter] = {};
                                //a.param[property_counter][prop] = n[prop];
                                //a.param[prop] = n[prop];
                               // property_counter++;
                            //}
                        }
                    }
                    
                    //alert('there are issues here:'+n.type+'_____'+offset);
                    //it looks like that the compounds nodes are coming in with the right index, but not in the attributes part
                    var n_position=tentacle.utilities.get_position(document.getElementById(tentacle.html.node(n.index)));
                    
                    //if(offset>0){
                        a.left=n_position[0];
                        a.top=n_position[1];
                        a.number_ports=n.number_ports;
                    //}else{
                        //a.guidef={};
                        //a.guidef.left=n_position[0];
                       // a.guidef.top=n_position[1];
                      //  a.guidef.number_ports=n.number_ports;//i need to check that this value on the node is updated when ports are added
                    //}

                    packaged_nodes.push(a);

                    node_counter+=1;
                }
                node_iterations+=1;   
            }
        }
        return new Array(packaged_nodes,node_recount);
    },
    'package_connections':function(recount,offset){
        offset = typeof offset !== 'undefined' ? offset : 0;//if there isn't an offset value sent, then we can just set ut to 0

        var packaged_connections = new Array();//hold the nodes
        var packaged_exposed_ports = new Array();

        if (offset>0){
            packaged_exposed_ports[0]={};
            packaged_exposed_ports[1]={};            
        }

        var connection_iterations=0;//this is needs incase offset comes in, I need to know where in the loop i am

        for( var conn in tentacle.connections.list ){//loop the nodes
            if (tentacle.connections.list.hasOwnProperty(conn)) {//keep it from looking at built in object types
                if( connection_iterations >= offset ){//
                    var c = tentacle.connections.list[conn];//shortcut
                    var b = {};//hold object
                    
                    //tentacle.compounds.level[tentacle.compounds.level.length-1]

                    var new_from_node = (c.from_node=='root')?'root':recount[c.from_node];//get the update from node
                    var new_to_node = (c.to_node=='root')?'root':recount[c.to_node];//get the update from node

                    if(c.to_node!="root" || offset>0){//dont do the one to the root, unless we are in a compound, in which case we need
                        b.from_node = new_from_node;
                        b.to_node = new_to_node;
                        b.from_port = c.from_port;
                        //b.to_node = new_to_node;
                        b.to_port = c.to_port;
                        packaged_connections.push(b);
                        
                        if(offset>0){//now I need to add in the packaged_exposed ports part
                            if(c.to_node=='root'){
                                //alert(c.to_port);
                                packaged_exposed_ports[0][c.to_port]=new Array();
                                packaged_exposed_ports[0][c.to_port][0]='out';
                            }
                            if(c.from_node=='root'){
                                packaged_exposed_ports[1][c.from_port]=new Array();
                                packaged_exposed_ports[1][c.from_port][0]='in';
                            }
                        }

                    }else{//this is part of the exposed ports
                        b.index = new_from_node;
                        b.name = c.from_port;
                        b.label = c.from_port;
                        b.type="out";
                        packaged_exposed_ports.push(b);
                    }
                    
                }
                connection_iterations+=1;
            }
        }
        //alert(packaged_connections);
        return new Array(packaged_connections,packaged_exposed_ports);
    },
    //---done with the packaging part of this code
    //--------------------------------------------
    //--------------------------------------------
    'open_comp' : function(file,location){
        this.file_name=file;
        this.save_path=location;
		var _this = this;
        new Ajax.Request(
            'xml_file_handler.php',{
                method:'get',
                parameters:{
                    act:'open',
                    file: file,
                    location: location
                },
                 onSuccess:function(transport){
                    var d = transport.responseText.evalJSON(true);
                    if(d[0]=="no_file"){
                        tentacle.i_o.new_comp();
                        tentacle.i_o.file_name=d[1];
                    }else{	
                        tentacle.i_o.init_values();//init them values
			
                        tentacle.nodes.from_xml_terminal(d[2]);//this is just the execute node
                        tentacle.nodes.from_xml(d[0]);//put the property at the fromnt of the document
                        tentacle.connections.from_xml(d[1]); 

                        //for a second time do this loop just to set the capsule
                        //for(var i=0;i<d[2];i++){//because we set the connections then set the collapsed nodes as collapsed
							//I NEED TO UPDATE THIS. THIS FUNCTION IS OBSOLETE,
							//NOW I JUST NEED TO TOGGLE THE CAPSULE WHEN READY
                      		///node.set_capsule(i);
                        //}
                  		tentacle.selection.set_composition_dimensions();
                        tentacle.i_o.log_file_name(tentacle.i_o.file_name);
						
						tentacle.compounds.embedded=d[3];//give the embedded data to the compounds class
                    }
                    document.getElementById('node_menu').style.display='inline';//setStyle({display:'inline'});
                    document.getElementById('console_file').style.display='inline';//setStyle({display:'inline'});

                 },
                 onFailure:function(){alert('something went wrong openiing file')}
            }
        );
        tentacle.console.log('tentacle.i_o.open_comp(\''+file+'\',\''+location+'\')');
    },
    'browse_comps' : function(){
        if(!($('browse_xml_window'))){
            new Ajax.Request(
                'xml_file_handler.php',{
                    method:'get',
                    parameters:{
                        act:'browse'
                    },
                    onSuccess:function(transport){
                  		floating_window.create("browse_xml_window","window_box",transport.responseText);		
                    },
                    onFailure:function(){}
                }
            );
        }
        tentacle.console.log('tentacle.i_o.browse_comps()');
    },
    'init_values' : function(){
   		tentacle.nodes.counter =0;
		tentacle.nodes.list = new Array();
   		tentacle.connections.counter = 0;
   		tentacle.connections.list = new Array();
		tentacle.compounds.embedded = new Array();
		tentacle.compounds.level = new Array();
		tentacle.compounds.seeking = false;
    },
    'log_file_name' : function(name){
        document.getElementById('console_file').innerHTML = name;//tentacle.i_o.save_path + '/' + name + '.xml';
    }
}