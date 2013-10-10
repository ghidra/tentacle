/*console "class"
----------------*/

tentacle.console = {
    'div': 'console_out',
    'var_div': 'variables_out',
    'mode':true,
    'var_mode':false,
    'toggle':function(type){
        this.mode = (type=='')? this.toggle_display(this.div,this.mode):this.mode;
        this.var_mode = (type=='variables')? this.toggle_display(this.var_div,this.var_mode):this.var_mode;
        if(type=='rollover'){
            if(this.mode) this.mode = this.toggle_display(this.div,true);
            if(this.var_mode) this.var_mode = this.toggle_display(this.var_div,true);
        }
    },
    'log':function(s){
        var entry = new Element('div');
        entry.innerHTML=s;
        $(this.div).insertBefore(entry,$(this.div).firstChild);
        //entry.fade({duration:4});
        //---update relevant variables---------
        if(!($('var_node_count'))) $(this.var_div).insertBefore( new Element('div', {'id':'var_node_count'}),$(this.var_div).firstChild );
        if(!($('var_connection_count'))) $(this.var_div).insertBefore( new Element('div', {'id':'var_connection_count'}),$(this.var_div).firstChild );

        $('var_node_count').innerHTML='n:'+tentacle.nodes.counter;
        $('var_connection_count').innerHTML='c:'+tentacle.connections.counter+'['+tentacle.connections.list.length+']';
    },
    'toggle_display' : function(e,b){
        b=(b)?false:true;
        (b)? $(e).setStyle({display:''}) : $(e).setStyle({display:'none'});
        return b;
    }
}