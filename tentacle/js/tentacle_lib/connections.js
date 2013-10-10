tentacle.connections={
	'counter':0,//this is used to iterate the html element created for id purposes
	'list':[],//the array to hold all the connections
	'live':0,
	'create':function(event,index,port){//create function is used when we create a new connection with the mouse, to the moust. thus, event is passed along in the optional argument slot to insert, which adds the connection to the 'stage'
		var data = {
            index : this.counter,
            from_node : index,
            from_port : port,
            to_node : 'mouse',
            to_port : '',
            to_port_minimized : '',
            color : ''
        };
		this.insert( new tentacle.connection(data,event));
		tentacle.utilities.attach_drag_event(this.drag_go, this.drag_stop);
	},
	'from_xml':function(d){
		var offset = 0+this.counter;//adding 0 maes it a local variable, not a reference
		for (var i=0;i<d.length;i++){
			//tentacle.console.log('fn:'+d[i].from_node+' fp:'+d[i].from_port+' tn:'+d[i].to_node+' tp:'+d[i].to_port);
			d[i].index=i+offset;
			d[i].to_port_minimized = '',
			d[i].color = '',
    		this.insert( new tentacle.connection(d[i]) );
    		//tentacle.console.log(d[i].index);
    	}
	},
	'insert':function(connection){
		this.list.push(connection);
		this.counter++;
	},
	'drag_go':function(event){		
		var _this = tentacle.connections
		var _connection = _this.list[_this.list.length-1];
		_connection.draw(event);
	},
	'drag_stop':function(event){
		var _this = tentacle.connections;
		var live = _this.list.length-1;
		var _connection = _this.list[live];//the connection we are stopping dragging
		
		var input = Event.findElement(event).id

		tentacle.utilities.remove_drag_event(_this.drag_go,_this.drag_stop);

		if(input){
			var portdiv = document.getElementById(input);
		    var divclass = portdiv.getAttribute("class");//this gets the class of the layer. I am looking for in_ports.
		    var input_split = input.split("_");//the id is a combo of the node index and the port

			switch(divclass){
				case "in_port":
		    		_this.check(input_split[1],input_split[0]);
		        	_connection.to_node = input_split[1];
		        	_connection.to_port = input_split[0];
					if(tentacle.compounds.seeking) tentacle.compounds.in_port_found( input_split[1] ,input_split[0]);
					break;
		   	
		    	case "inwait_port":
		        	//var port_part=input_split[0].replace(/[.]+/g,"");//split the name of the port, splits the "new: off, and gives the name of the port to make a new one of
					var list_id = tentacle.nodes.get_index(input_split[1]);//the node index in nodes list
					var _node =  tentacle.nodes.list[ list_id ];
		        	_node.insert_port();//,nodename);//insert the new port     
					_connection.to_node = input_split[1];
		        	_connection.to_port = _node.new_port;//i need a propper port name
					if( tentacle.compounds.seeking ) tentacle.compounds.in_port_found(input_split[1],_node.new_port);
					//tentacle.console.log( input_split[1]+':'+input_split[0] );
					break;	
										
		      	case "compound_inwait_port":					
					_connection.to_node = 'root';
				    _connection.to_port = _connection.from_port;//i need a propper port name
					tentacle.compounds.create_output(_connection.from_node,_connection.from_port);					
					break;
					
				default:
		            _this.remove(live);
		            break;
		   	}
		}else{
			_this.remove(live);
		}	
		//this line here would fuck shit up, because I guess the breaks above, would just skip this. 
		//but even putting this line in each statement, shit didnt work.
		//leaving this here for reference.
		//tentacle.utilities.remove_drag_event(_this.drag_go,_this.drag_stop);
	},
	'check' : function(index,port){//checks for a conflicting connection, and removes it if we have one
		var found = {'connected':false};
		for(var i=0; i<this.list.length; i++){
			var connection = this.list[i];
            if(connection.to_node==index && connection.to_port==port){
            	this.remove(i);
				found = {'connected':true,'from_node':connection.from_node,'from_port':connection.from_port};//lets send the variables back, for the pop function
            }
        }
		return found;
    },
	'pop' : function(event,index,port){//this is called from the node html, when i am trying to disconnect it from the baskc side of the node, if there is a connection, remove it, and make a new one
		var con = this.check(index,port);
        if(con.connected==true) this.create(event,con.from_node,con.from_port);
    },
	'connected':function(node){//this function returns an array of ids for the connections.list, that are connected to the node index fed in
        var con_array=new Array();
		for(var i=0; i<this.list.length;i++){
			var connection = this.list[i];
            if( connection.from_node == node || connection.to_node == node ){
                con_array.push(i);
            }
        }
        return con_array.sort(function(a,b){return b - a});//sort the array so large numbers are fisrt, cause when we deleate a lot at once, we need to do it from largest to smallerst number in the array
    },
    'assign_minimize_port' : function(indecies,tmp_port,node){//indecies is the array fro the connected function in this object
		for(var i=0;i<indecies.length;i++){
			var connection = this.list[ i ];
            if( connection.from_node != node  ){ //if there is an exposed port, and connected line isn't an out connection
                connection.to_port_minimized = tmp_port;
            }
        }
    },
	'remove':function(i){
		document.getElementById("composition").removeChild( document.getElementById( tentacle.html.connection( this.list[i].index  )) );
		this.list.splice(i,1);
	}
}