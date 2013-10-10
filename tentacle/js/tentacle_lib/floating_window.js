/*floating_window "class"
//this class hold any windows that are added to it for draggin pirpose. Nodes are handled by them selves in thoer own thing.
//this is for property windows, or I guess even database entry windows.
----------------*/

tentacle.floating_window = {
    'list' : [],
    'drag_data' : {},
    'z_index' : 10,
    'drag' : function(event,id){//called from the html layer being clicked on. I need the whole name of the elements id already before acting
   		if(!tentacle.selection.shift_pressed_modifier)this.clear_list();
        var add=true;
		for (var i=0;i<this.list.length;i++){
			if(this.list[i]==id){
				add=false;
				break;
			}
		}
        if(add) this.list.push( id );
        
   		var mo_po = tentacle.utilities.relative_mouse_position(event);

        this.drag_data._x = mo_po[0];
        this.drag_data._y = mo_po[1];
        this.drag_data.windows = [];

		for (var i=0;i<this.list.length;i++){
			var win = document.getElementById(this.list[i]);
            this.drag_data.windows[i]=[];
            this.drag_data.windows[i][0] = (isNaN(parseInt(win.style.left, 10))) ? 0 : parseInt(win.style.left, 10);
            this.drag_data.windows[i][1] = (isNaN(parseInt(win.style.top, 10))) ? 0 : parseInt(win.style.top, 10);
            win.style.zIndex = this.z_index;
		}
        
   		tentacle.utilities.attach_drag_event(this.drag_go,this.drag_stop);
    },
    'drag_go': function(event){
		var _this = tentacle.floating_window;
   		var mo_po = tentacle.utilities.relative_mouse_position(event);
        var xd = (mo_po[0] - _this.drag_data._x);
        var yd = (mo_po[1] - _this.drag_data._y);
        
		for(var i = 0; i < _this.list.length; i++){
			
            var xn = _this.drag_data.windows[i][0]+xd;
            var yn = _this.drag_data.windows[i][1]+yd;
            xn=(xn>4)?xn:4;
            yn=(yn>30)?yn:30;
            
			var win = document.getElementById(_this.list[i]);
			win.style.left = xn + "px";
            win.style.top = yn + "px";
        }
    },
    'drag_stop' : function(event){
		var _this = tentacle.floating_window;
		for (var i=0;i<_this.list.length;i++){
			document.getElementById(_this.list[i]).style.zIndex = _this.z_index-1;
		}
   		tentacle.utilities.remove_drag_event(_this.drag_go,_this.drag_stop);
    
    },
    'clear_list':function(){
        this.list=new Array();
    }
}