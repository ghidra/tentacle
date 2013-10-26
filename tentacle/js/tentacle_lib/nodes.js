tentacle.nodes = {
    'counter' : 0,
	'list':[],
	'root':{},//hold the terminal node
    'insert' : function(type,data){// data is optional, comes from database nodes
		//this functions craetes a new node object, and the node goes to php to see what to do, via ajax
        if(!(data)) data='';
        this.list.push( new tentacle.node() );
        this.list[ this.list.length-1 ].create(this.counter,type,data);
		this.counter++;//if successful

        //if(tentacle.selection.creating_compound) tentacle.selection.connect_new_compound();
    },
    'from_xml' : function(d){//this function comes from opening an xml file, and adding in the nodes
    	//this.terminal();
        //alert(this.otos(d[0]));
    	for (var i=0;i<d.length;i++){
           // alert(otos(d[i]));
    		this.list.push( new tentacle.node() );
            //d[i].index=this.counter;//force the numbering
    		this.list[this.counter].replicate(d[i]);
    		this.counter++;
    	}
    },
    'from_xml_terminal':function(d){
    	this.root = new tentacle.node();
    	this.root.replicate(d);
    },
	'terminal' : function(){// this just makes a terminal node specially
        this.root = new tentacle.node();
        this.root.create('root','execute','');
    },
	'inspect' : function(id){//we get passed in the index of the node, not nessisarily its index in the list array
		this.list[ this.get_index(id) ].inspect();
	},
    'open_compound':function(id){
        
        var c_id = this.list[ this.get_index(id) ].attributes.compound_id;
        //alert(id+":"+c_id);
        tentacle.compounds.open(id,c_id);
    },
    'toggle_capsule' : function(id){
		this.list[ this.get_index(id) ].toggle_capsule();
    },
	'remove' : function(id){
        var cd = tentacle.connections.connected(id);
		for(var i = 0; i<cd.length; i++){
            tentacle.connections.remove(cd[i]);//remove any connection
        }
   		//tentacle.selection.drag_stop();//looks like I don't need to do this anymore....forse the floating window drag stop to stop. has to be here too, otherwise it doesn't stop it
		tentacle.utilities.remove_element(tentacle.html.node(id));
		if(document.getElementById(tentacle.html.node_property(id))){ tentacle.utilities.remove_element(tentacle.html.node_property(id));}//if there isn't already a property page open
		this.list.splice(this.get_index(id),1);
	},
	'get_index':function(id){//this function finds the 'index' value of the connection in the list aray, and returns the index of the connetion relative to the array, example: list=[index:0,index:5]  index5=index1 in the array;
		var ind=-1;// = we haven't found it
		for (var i=0; i<this.list.length; i++){//loop through the list
			if(this.list[i].attributes.index == id){//if we find it
				ind=i;//set value
				break;//breal cause we found it
			}
		}
		return ind;
	},
    'otos':function(o){//object to string
        s='';
        for(var i in o){
            s+=i+':'+o[i]+"\n";
        }
        return s;
    }

}