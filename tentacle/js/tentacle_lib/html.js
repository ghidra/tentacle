/*html "class"
----------------*/

tentacle.html = {
    'composition':function(){return 'composition';},
    'node' : function(i){return 'node_'+i;},
    'node_bar' : function(i){return 'node_bar_color_'+i;},
    'node_type' : function(i){return 'node_'+i+'_type';},
	'node_read_type' : function(i){return 'node_read_type_'+i;},//this one was recently added, so if anything is broke, it might be this one
    //'node_capsule' : function(i){return 'node_capsule_'+i;},
    'node_port' : function (i,p){return p+'_'+i+'_port';},
    'node_in_port' : function (i,p){return p+'_'+i;},
    'node_port_type' : function (i,p){return p+'_'+i+'_type';},
    'node_port_data' : function (i,p){return p+'_'+i+'_data';},
    'node_property' : function(i){return 'property_'+i;},
    'node_property_form' : function(i){return 'property_'+i+"_form";},
	'node_property_input' : function(i,p){return 'property_'+i+"_"+p+'_input';},
    'connection' : function(i){return 'connection_'+i;},
	'embedded_id' : function(i){return 'embedded_id_'+i;}
}