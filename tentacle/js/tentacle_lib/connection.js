tentacle.connection=function(data,event){	
	this.init(data,event);
}
tentacle.connection.prototype.init=function(data,event){	
	var i = data.index;
	
	this.index = i;
    this.from_node = data.from_node;
    this.from_port = data.from_port;
    this.to_node = data.to_node;
    this.to_port = data.to_port;
    this.to_port_minimized = data.to_port_minimized;
    this.color = data.color;
	
	var element = document.createElement("div");
    element.setAttribute("name","connection");
    element.setAttribute("id","connection_"+i);

	document.getElementById('composition').appendChild(element);//append the connection

	/*jsgraphics*/
	this.graphics = new jsGraphics("connection_"+i);	
	this.draw(event)//event is optional, only from create
	
	return this;		
}
tentacle.connection.prototype.draw=function(event){//event is optional, and comes from connections,create, when a new connection is created on mouse down
	var from_port_pos = tentacle.utilities.get_position( document.getElementById( tentacle.html.node_in_port( this.from_node,this.from_port ) ) );
	
	//THIS NODE SHIT IS MAKING SHIT NOT WORK. BECAUSE OF THE FACT THAT THE ROOT NODE ISN'T IN THE NODE LIST, AND THE SHIT DOESN'T KNOW HOW TO ACCOUNT FOR IT 
	//var node_to_mode = (this.to_node!='mouse' && this.to_node!='root')? tentacle.nodes.list[ tentacle.nodes.get_index(this.to_node) ].mode : 0;/*n*/	
	//tentacle.console.log(node_to_mode);
	var node_to_mode=0;
	
	if(node_to_mode == 0){
		if( this.to_node == 'mouse' ){
			var mouse_pos = tentacle.utilities.relative_mouse_position(event);
	        var port_to_pos = [];
	        port_to_pos[0] = mouse_pos[0];
	        port_to_pos[1] = mouse_pos[1];
		}else{ 
			//tentacle.console.log( tentacle.html.node_in_port(this.to_node,this.to_port) );
			var port_to_pos = tentacle.utilities.get_position(document.getElementById( tentacle.html.node_in_port(this.to_node,this.to_port ) ));
	    }
	}else{//if the mode is minimized
		var port_to_pos = tentacle.utilities.get_position(document.getElementById( tentacle.html.node_in_port(this.to_node,this.to_port_minimized) ));
	}
	//tentacle.console.log(from_port_pos[0]+':'+from_port_pos[1]+':'+port_to_pos[0]+':'+port_to_pos[1]);
	this.graphics.clear();//clear the lauers of drawings
	this.graphics.setColor("#ff0000");
	this.graphics.drawLine(from_port_pos[0]+10,from_port_pos[1]+5,port_to_pos[0],port_to_pos[1]+5);
	this.graphics.paint();
}
