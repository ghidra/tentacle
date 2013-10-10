/*selection "class"
selection        
    .list : [[id,color_a,colorb],[id,color_a,color_b]]
    .shift_pressed_modifier : boolean
    
    .shift_pressed : function() - toggles shift_pressed_modifier
    
    .add : function(event,id,color_a,color_b) - adds to list
    .clear_selection : function() - clears list

----------------*/

tentacle.selection = {
	'list':[],//All the shortcuts are stored in this array
    'list_connections' : [],//the connetions on the selected nodes
    'drag_data' : {},
    'z_index' : 2,
    'width':10,
    'height':10,
    'marquee_start_x':0,
    'marquee_start_y':0,
    'copy_data':{},
    'pasted_index':[],
    'pasting':false,
	'creating_compound':false,
	'creating_compound_connections':[],
    'direct_contact':true,//a boolean to know weather to bypass code in add() for dragging node or a marquee
    'shift_pressed_modifier':false,
    'shift_pressed': function(){
        this.shift_pressed_modifier = ( this.shift_pressed_modifier ) ? false : true;
        tentacle.console.log('this.shift_pressed()');
    },
	'add': function(event,id,color_a,color_b) {//function adds and begins dragging of nodes and connections
        var add=true;
        //this.list.each(function(item){if(item[0]==id){add=false};});
		for(var i=0; i<this.list.length;i++){
			if(this.list[i][0]==id){
				add=false;
			}
		}
        if(!this.shift_pressed_modifier && add)this.clear_selection();
   		$( tentacle.html.node(id) ).setStyle({borderColor:color_a});
        if(add) this.list.push( new Array(id,color_a,color_b) );
        //------------------
		//-turn on the create compound button
		$('create_compound').setStyle({display:'inline'});
		//------------------
        //drag business
        if(this.direct_contact){// do not run code if being added by marquee
       		var mo_po = tentacle.utilities.relative_mouse_position(event);
            this.drag_data._x = mo_po[0];
            this.drag_data._y = mo_po[1];
            this.drag_data.nodes = [];
            this.drag_data.connections = [];
			for(var i=0;i<this.list.length;i++){
				var item = this.list[i][0];
            //this.list.each( function(item,i) {
                this.drag_data.nodes[i]=[];
           		this.drag_data.nodes[i][0]=parseInt($(tentacle.html.node(item)).style.left, 10);
           		this.drag_data.nodes[i][1]=parseInt($(tentacle.html.node(item)).style.top, 10);
                if (isNaN(this.drag_data.nodes[i][0])) this.drag_data.nodes[i][0] = 0;
                if (isNaN(this.drag_data.nodes[i][1])) this.drag_data.nodes[i][1] = 0;
           		$(tentacle.html.node(item)).style.zIndex = this.z_index;
                //-------------
            
           		this.drag_data.connections = this.drag_data.connections.concat(tentacle.connections.connected(item));
            //});
			}
			//alert('bang');
            this.drag_data.connections.uniq();
       		tentacle.utilities.attach_drag_event(this.drag_go,this.drag_stop);
            //tentacle.console.log('this.add(event,'+id+',\''+color_a+'\',\''+color_b+'\')');
        }
    },
    'drag_go' : function(event){
		var _this = tentacle.selection;
  		var mous_po = tentacle.utilities.relative_mouse_position(event);
        var xd = (mous_po[0] - _this.drag_data._x);
        var yd = (mous_po[1] - _this.drag_data._y);
        for(var i=0;i<_this.list.length;i++){
			var item= _this.list[i][0];
            var xn = _this.drag_data.nodes[i][0]+xd;
            var yn = _this.drag_data.nodes[i][1]+yd;
            xn=(xn>4)?xn:4;
            yn=(yn>30)?yn:30;
       		$(tentacle.html.node(item)).style.left = xn + "px";
       		$(tentacle.html.node(item)).style.top = yn + "px";
		}	
        //drag the connections
		for(var i=0; i<_this.drag_data.connections.length;i++){
			//trash = tentacle.connections.list[_this.drag_data.connections[i]];
			//tentacle.console.log(trash.from_node+':'+trash.from_port+':'+trash.to_node+':'+trash.to_port);
			//this.drag_data.connections.each(function(id){
			//tentacle.console.log(tentacle.connections.list[_this.drag_data.connections[i]]);
			tentacle.connections.list[_this.drag_data.connections[i]].draw(event);
		}
    },
    'drag_stop' : function(event){
		var _this = tentacle.selection;
		for(var i=0; i<_this.list.length;i++){
			document.getElementById(tentacle.html.node(_this.list[i][0])).style.zIndex = _this.z_index-1;
		}
  		tentacle.utilities.remove_drag_event(_this.drag_go,_this.drag_stop);
        _this.set_composition_dimensions();
    },
    'clear_selection':function(){
        if(this.list.length>0){
            for(i=0;i<this.list.length;i++){
                var my_id=this.list[i][0];
                //when the node is removed is still calls this, and the node isn't there and it errors, and keeps th dragging from working
                if( $( tentacle.html.node(my_id) ) ) {
					$( tentacle.html.node(my_id) ).setStyle({borderColor:'#000000'});
				}
            }
        }
		//--turn off the create compound button
		$('create_compound').setStyle({display:'none'});
		//
        this.list=new Array();
        //tentacle.console.log('this.clear_selection()');
    },
    'set_composition_dimensions':function(){//this probably needs to be in tentacle.utilities. also this is being called too much when it doesn't need to be
  		for(var i=0; i<tentacle.nodes.counter; i++){
       		var n = document.getElementById(tentacle.html.node( tentacle.nodes.list[i].attributes.index ));
            if( n ){
                var w = parseFloat(n.getStyle('left'));
                var h = parseFloat(n.getStyle('top'));
                this.width = (w>this.width)?w:this.width;
                this.height = (h>this.height)?h:this.height;
            }
        }
   		var comp = document.getElementById(tentacle.html.composition());
        comp.style.width=this.width;
        comp.style.height=this.height;

        //tentacle.console.log('this.set_composition_dimensions()');
    },
    'marquee':function(event){
	//alert('fuck');
        this.clear_selection();
        //make the marquee div
   		var mo_po = tentacle.utilities.relative_mouse_position(event);
        this.marquee_start_x = mo_po[0];
        this.marquee_start_y = mo_po[1];
   		document.getElementById(tentacle.html.composition()).appendChild( new Element('div',{'id':'marquee'}) );
        var marquee = document.getElementById('marquee');
		marquee.style.position='absolute';
		marquee.style.left=mo_po[0];
		marquee.style.top=mo_po[1];
		marquee.style.width=0;
		marquee.style.height=0;
		marquee.style.zIndex=this.z_index+1;
		marquee.style.border='1px dotted black';
  		tentacle.utilities.attach_drag_event(this.marquee_go,this.marquee_stop);
    },
    'marquee_go':function(event){
		var _this = tentacle.selection;
   		var mo_po = tentacle.utilities.relative_mouse_position(event);
        //---------------x
		var marquee = document.getElementById('marquee');
        if(mo_po[0]<_this.marquee_start_x){
            marquee.style.left=mo_po[0]
			marquee.style.width=_this.marquee_start_x-mo_po[0];
        }else{
            marquee.style.width=mo_po[0]-_this.marquee_start_x;
        }
        //---------------y
        if(mo_po[1]<this.marquee_start_y){
            marquee.style.top=mo_po[1];
			marquee.style.height=_this.marquee_start_y-mo_po[1];
        }else{
            marquee.style.height=mo_po[1]-_this.marquee_start_y;
        }
		tentacle.console.log('x:'+mo_po[0]+' y:'+mo_po[1]);
    },
    'marquee_stop':function(event){
		var _this = tentacle.selection;
   		tentacle.utilities.remove_drag_event(_this.marquee_go,_this.marquee_stop);
        var n = new Array();
        var m = $('marquee');
        var x = parseInt(m.getStyle('left'));
        var y = parseInt(m.getStyle('top'));
        var w = parseInt(m.getStyle('width'));
        var h = parseInt(m.getStyle('height'));
        for(var i=0;i<tentacle.nodes.counter;i++){
            var me = $(tentacle.html.node(i));
            var tagged = false;
            if(me){
                var my_x = parseInt(me.getStyle('left'));
                var my_y = parseInt(me.getStyle('top'));
                var my_w = me.getWidth();
                var my_h = me.getHeight();
                if ( my_x >= x && my_x <= x+w && my_y >= y && my_y <= y+h) tagged = true;//check top left corner
                if ( my_x+my_w >= x && my_x+my_w <= x+w && my_y >= y && my_y <= y+h) tagged = true;//check top right corner
                if ( my_x >= x && my_x <= x+w && my_y+my_h >= y && my_y+my_h <= y+h) tagged = true;//check bottom left corner
                if ( my_x+my_w >= x && my_x+my_w <= x+w && my_y+my_h >= y && my_y+my_h <= y+h) tagged = true;//check bottom right corner
                if (tagged) n.push(i);
            }
        }
        
        if(n[0]!=undefined){//if we have something in our range
            _this.add_multiple(n);
        }
		document.getElementById('composition').removeChild(document.getElementById('marquee'));
        //$('marquee').remove();
    },
    'copy':function(){
        //this.copy_data.node_data = this.list.clone();
        this.copy_data.node_index = [];
        this.copy_data.node_type = [];
        this.copy_data.node_position = [];
		for(var i =0; i<this.list.length;i++){
			var item = this.list[i][0];
        //this.list.each( function(item){//start getting the data of each node
            this.copy_data.node_index.push( item );
            this.copy_data.node_type.push( $(tentacle.html.node_type(item)).innerHTML );
            this.copy_data.node_position.push( {'top': parseInt($(tentacle.html.node(item)).getStyle('top')),'left':parseInt($(tentacle.html.node(item)).getStyle('left')) } ); 
        //});
		}
        this.copy_data.connections=this.list_connections;
        tentacle.console.log('this.copy()');
    },
    'paste':function(){
        if(!this.pasting){
            this.copy_data.nodes_temp=this.copy_data.node_type.clone();//a temp array that I can manipulate        
            this.pasting=true;
        }
        //-----
        if(this.copy_data.nodes_temp.length>0){
            this.pasted_index.push(tentacle.nodes.counter);//collect the index of the new pasted node
            tentacle.nodes.insert( this.copy_data.nodes_temp.pop() );
        }else{//done pasting in nodes, now do what you will with them, like placement and connections at some point 
            this.pasting=false;
            this.pasted_index.reverse();//revese the order to match the nodes list
            for(var i=0;i<this.copy_data.node_index.length;i++){//prototype each not working right, maybe I didn't do it right
                var new_top = this.copy_data.node_position[i].top+10;
                var new_left = this.copy_data.node_position[i].left+10;
                $(tentacle.html.node( this.pasted_index[i] )).setStyle({ top: new_top+'px', left: new_left+'px' });                
            }
            this.clear_selection();
            this.add_multiple(this.pasted_index);
        }
        tentacle.console.log('this.paste()');
    },
    'add_multiple':function(indicies){//i guess for pasting more than one node
        var revert_shift = (this.shift_pressed_modifier)?false:true;
        this.shift_pressed_modifier=true;
        this.direct_contact=false;//direct contact is a boolean that tells if we are in the action of dragging a anode to bypass the code in add()
		for(var i=0; i<indicies.length;i++){
			document.getElementById('node_bar_color_'+indicies[i]).onmousedown();
		}
		//indicies.each( function(item){ $('node_bar_color_'+item).onmousedown(); });//$(tentacle.html.node_bar(n[item])).onmousedown();it wont work right with the html class. I don't know why, maybe it isn't quick enought, and I need to assign it here for uicker access or something?
        if(revert_shift) this.shift_pressed();
        this.direct_contact=true;//turn direct contact back on
        tentacle.console.log('this.add_multiple(['+indicies+'])');
    },



//compound technically works, but with all the refactoring, there is bound to be some isses, there were issues before I did that shit too



	'compound':function(){//turn selected nodes into an embedded compound
		if( this.list.length > 0 ){//only do it if there are compounds slected
			this.creating_compound=true;
			//-----------------------------------
			var id_list = [];//hold the list of node ids only
			for(var i=0;i<this.list.length;i++){
				id_list.push(this.list[i][0]);//store the ids
			}
			//---------------------------------
			//
			//
			//
			//the following was stolen mostly from tentacle.i_o.package_comp and compound.close
			//this is a similar function,
			var obj_jsoon={};//set up the jsoon objecy
	        var obj_recount={};//and object to easily grab the changed renumbering due to deleting
	        //----------------recount
	        var recount=0;//recount the nodes so they go from 0, to remove deleted nodes numbers
	        var e_count=0;
	        var c_count=0;
			//----------------name

			obj_jsoon.name = 'new_compound';

			//----------------array to catch the connections that are going to be encapsulated.

			var connection_id_list = [];
	        
            //----------------nodes
	        obj_jsoon.nodes = new Array();
	   		for(k=0;k<id_list.length;k++){

                var node =  tentacle.nodes.list[id_list[k]];//node object
                if(node != undefined && node != null){//if node hasn't been deleted, since I am only counting up, and not keeping node data in javascript anywhere
                    
                    n = node.attributes;//node attributes

                    obj_recount[id_list[k]]=recount;//set the recount object numbers
                    obj_jsoon.nodes[recount] = {};//make the object to hold node data

                    obj_jsoon.nodes[recount].read_type = (n.read_type) ? n.read_type : 'node';
                    obj_jsoon.nodes[recount].type = (n.type == 'tentacle_compound')? n.label : n.type;//node_div.firstChild.lastChild.innerHTML;;// node_div.firstChild.innerHTML;
                    obj_jsoon.nodes[recount].mode = n.mode;//
                    obj_jsoon.nodes[recount].index = recount+'';//turn it to a string

                    for (var group in n.in_ports) {//iterate the port groups first
                        for (var prop in n.in_ports[group]){//iterate the properties
                                obj_jsoon.nodes[recount][prop]=n[prop];
                        }
                    }
                    //alert(this.object_to_string(obj_jsoon.nodes[0]));
                    var n_position=tentacle.utilities.get_position(document.getElementById(tentacle.html.node(id_list[k])));

                    obj_jsoon.nodes[recount].left=n_position[0];
                    obj_jsoon.nodes[recount].top=n_position[1];
                    obj_jsoon.nodes[recount].number_ports=n.number_ports;
                    recount++;//up the recount
                   
                    //get the connections that are connected and add them to an array
                    var connection_tmp = tentacle.connections.connected(id_list[k]);

                    for(var i=0; i<connection_tmp.length;i++){
                        var item=connection_tmp[i];
                        if(connection_id_list.indexOf(item)<0) connection_id_list.push(item);
                    }
                    //alert(connection_id_list);
                    //-------

                }

	      		/*var node_div = $( tentacle.html.node( id_list[k]) );
	            if(node_div){//if node hasn't been deleted, since I am only counting up, and not keeping node data in javascript anywhere
	                obj_recount[id_list[k]]=recount;//set the recount object numbers
	                obj_jsoon.nodes[recount] = {};//make the object to hold node data
					//
					//
					obj_jsoon.nodes[recount].read_type='node';
	          		obj_jsoon.nodes[recount].type = $(tentacle.html.node_type(id_list[k])).innerHTML;//node_div.firstChild.lastChild.innerHTML;;// node_div.firstChild.innerHTML;
	                obj_jsoon.nodes[recount].mode = $(tentacle.html.node_capsule(id_list[k])).innerHTML;//
	                obj_jsoon.nodes[recount].index =recount+'';//turn it to a string
			
	          		var node_ins = tentacle.nodes.list[0].port_data(id_list[k]);//node.port_data(id_list[k])      collect the input ports
	          		var content_ins = tentacle.nodes.list[0].count_port(id_list[k],'content');//collect the input ports
	                //for use with object instead of array now
	                var j =0;
	                for (var prop in node_ins) {
	              		obj_jsoon.nodes[recount][prop] = node_ins[prop];
	                }

	         		var n_position=tentacle.utilities.get_position($(tentacle.html.node(id_list[k])));

					obj_jsoon.nodes[recount].left=n_position[0];
	                obj_jsoon.nodes[recount].top=n_position[1];
	                obj_jsoon.nodes[recount].number_ports=content_ins;
	                recount++;//up the recount
					
					
					//get the connections that are connected and add them to an array
					var connection_tmp = connection.connected(id_list[k]);

					for(var i=0; i<connection_tmp.length;i++){
						var item=connection_tmp[i];
					//connection_tmp.each(function(item){
						if(connection_id_list.indexOf(item)<0) connection_id_list.push(item);
					}
					//-------

	            }*/

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
			obj_jsoon.result[0].read_type='node';//was node
			obj_jsoon.result[0].type='tentacle_compound_open';
			
			//----an objecet to hold my out ports for creating a new compound
			//var out_ports_object={};
            var out_ports_object=[];
			var in_ports_object={};
            in_ports_object['compound attributes']={};//fake a group
			//-----
			
			//if(false){
	  		for(i=0;i<connection_id_list.length;i++){
				var connection_id = connection_id_list[i];
                var connection = tentacle.connections.list[connection_id];
				//
				//if(i >= compound.level[compound.level.length-1][1]){
					//these iff statments dal with connections that go outside the created compound. in or out
					//obj_jsoon.result[obj_jsoon.result.length] = {};
					
					if (connection.from_node =='root' || id_list.indexOf(connection.from_node)<0  ){
                        //this if statement means that the connection comes from a root node, or to the actual compound ports
                        //so this connection is an input port
						var new_from_node = 'root';
						var new_from_port = connection.to_port;
						
                        //also add the pot to the result array
						//in_ports_object[connection.to_port]=[connection.from_port , connection.from_node];//add input port to the object so I can add it back onto to the object being sent to php
						in_ports_object['compound attributes'][connection.to_port]={};//[connection.from_port , connection.from_node];
                        in_ports_object['compound attributes'][connection.to_port].type='string';//i need to somehow know what I am grabbing here, incase it IS NOT a string
                        in_ports_object['compound attributes'][connection.to_port].exposed=1;//also need to find this info out based on what kind of node we are connecting to

                        obj_jsoon.result[obj_jsoon.result.length] = {};
						obj_jsoon.result[obj_jsoon.result.length-1][connection.to_port] = ['in'];
					}else{
			      		var new_from_node = obj_recount[connection.from_node] + '' ;//turn it to a string get the update from node
						var new_from_port = connection.from_port;
					}
					//
					if(connection.to_node=='root' || id_list.indexOf(connection.to_node)<0 ){
                        //this if statement means that it is an output port of the node
						var new_to_node='root';
						var new_to_port=connection.from_port;

						//out_ports_object[connection.from_port] = [connection.to_port,connection.to_node];//add port data to outport object
                        //just add to the array the name of the port it is from....
                        // i will need to add in error correction incase there is more than one result, which there might be for certain

                        out_ports_object.push(connection.from_port);


						//also add this to result object
						obj_jsoon.result[0][connection.from_port]=['out'];//make it come from inport, cause it neds to be names as the post comeing from node
					}else{
			       		var new_to_node = obj_recount[connection.to_node] + '' ;//get the update from node
						var new_to_port = connection.to_port;
					}
				//
					obj_jsoon.result[0].left="0";
					obj_jsoon.result[0].top="0";
	       		//if(connection.list[i].to_node!="root"){//dot do the one to the root
	                obj_jsoon.connections[c_count] = {};//set the connection as an object
	                obj_jsoon.connections[c_count].from_node = new_from_node;
           			obj_jsoon.connections[c_count].from_port = new_from_port;//connection.list[connection_id].from_port;
	                obj_jsoon.connections[c_count].to_node = new_to_node;
	           		obj_jsoon.connections[c_count].to_port = new_to_port;//connection.list[connection_id].to_port;
	                c_count++;

				//}
	        }
			//now clear the selection
			this.clear_selection();
			//now go and remove the nodes and connections
			///id_list.each(function(item){
			///	node.remove(item);
			///});

			for(var i=0;i<id_list.length;i++){
				tentacle.nodes.remove(id_list[i]);
			}
			
			//now add the object data to the compound embedded list
			tentacle.compounds.embedded.push(obj_jsoon);
			
            //now I need to make a new node that represents the compound 
			var new_compound = {};

            //new_compound.type = '[embedded_'+(tentacle.compounds.embedded.length-1)+']';
            new_compound.type = 'tentacle_compound';
            new_compound.read_type = 'embedded';
            //new_compound.render_type = 'standard';
            new_compound.compound_id = tentacle.compounds.embedded.length-1;
            //new_compound.label = obj_jsoon.name;
            //new_compound.read_data = {};
            //new_compound.read_data.name = 'come the fuck on';
            //new_compound.read_data.connections = [];
            //new_compound.read_data.connections[0]={};//SO THIS IS WHERE WE ARE FUCKING UP... DOES NOT LIKE THIS
            //new_compound.read_data.connections
            new_compound.read_data = obj_jsoon;//I NEED TO GIVE IT ALL THE EMBEDDED DATA, SO THAT IT CAN BUILD THE INFO THAT IT NEEDS

			//new_compound.name = obj_jsoon.name;
			//new_compound.read_type = 'embedded';
			//new_compound.type = '[embedded_'+(tentacle.compounds.embedded.length-1)+']';
			
            /*new_compound.ports = 'content';//out_ports_object;//ports object is used to create out put ports on node
            new_compound.out_ports = out_ports_object;
            new_compound.in_ports = in_ports_object;*/
			
            //for(var inpo in in_ports_object){//add on the in ports
			//	new_compound[inpo]='';
			//}
            /*for (var group in n.in_ports) {//iterate the port groups first
                for (var prop in n.in_ports[group]){//iterate the properties
                    obj_jsoon.nodes[recount][prop]=n[prop];
                }
            }*/


			//make the node
			tentacle.nodes.insert( 'tentacle_compound',Object.toJSON(new_compound) );
			//tentacle.nodes.insert( 'tentacle_compound',new_compound );
            //now make the new connections
			//connection.insert()
			this.creating_compound_connections=[];
			for (var outpo in out_ports_object){//for each out port in the new compound
				var con_data={};
				con_data.from_node=tentacle.nodes.counter;
				con_data.from_port=outpo;
				con_data.to_node=out_ports_object[outpo][1];
				con_data.to_port=out_ports_object[outpo][0];
				con_data.index=tentacle.connections.counter+this.creating_compound_connections.length;
				con_data.color='';
				this.creating_compound_connections.push(con_data);
				//connection.insert(con_data);
			}
			for (var inpob in in_ports_object){
				var con_data_b={};
				con_data_b.from_node=in_ports_object[inpob][1];//node.counter;
				con_data_b.from_port=in_ports_object[inpob][0];//inpo;
				con_data_b.to_node=tentacle.nodes.counter;
				con_data_b.to_port=inpo;
				con_data_b.index=connection.counter+this.creating_compound_connections.length;
				con_data_b.color='';
				this.creating_compound_connections.push(con_data_b);
			}
	        tentacle.console.log('this.compound()');
		}
	},
    object_to_string:function(a){
        var s = '';
        for(var i in a){
           // if( this.is_object(a[i]) ){
            //    s+=i+':\n';
            //    s+=this.object_to_string(a[i]);
            //}else{
                s+=i+':'+a[i]+'\n';
            //}
        }
        return s;
    },
	'connect_new_compound':function(){
		for(var i=0; i<this.creating_compound_connections.length; i++){
			tentacle.connections.insert( new tentacle.connection(this.creating_compound_connections[i]) );
		}
	}
}