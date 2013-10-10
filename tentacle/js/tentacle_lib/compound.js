//this is currently crap trash shit

tentacle.compound=function(){
	this.init(); //to return or not to return, probably
}

tentacle.node_properties.prototype = new tentacle.node();
tentacle.node_properties.prototype.constructor = tentacle.compound;