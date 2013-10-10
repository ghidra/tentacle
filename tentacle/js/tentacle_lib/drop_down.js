/*drop_down "class"
//this code is an adaptation from code found at http://javascript-array.com/scripts/simple_drop_down_menu/

----------------*/

tentacle.drop_down = {
    'time' : 500,
    'timer' : 0,
    'menu_item' : new Array(),
    'open' : function(id,level){
        this.cancel_close();//cancel any outstanding closing goings on
        this.level_close(level);//close the latest level it it needs to be
        this.menu_item[this.menu_item.length] = id;
		
        document.getElementById(id).style.display='inline';
    },
    'level_close' : function(level){
        if(this.menu_item[level]){
            if(level==0){  //if we have something at the top level, we need to make sure to close everything
                this.hard_close();
            }else{
                document.getElementById(this.menu_item[level]).style.display='none';
                this.menu_item.splice(level,1);
            }
        }
    },
    'hard_close' : function (){
		var _this = tentacle.drop_down
        if (_this.menu_item.length>0) {
            for(i=0; i<_this.menu_item.length; i++){
                document.getElementById(_this.menu_item[i]).style.display='none';
            }
            _this.menu_item.clear();//prototype to clear an array
        }
    },
    'close' : function(){
        this.timer = window.setTimeout(this.hard_close, this.time);
    },
    'cancel_close' : function(){
        if(this.timer){
            window.clearTimeout(this.timer);
            this.timer = null;
        }
    }
}