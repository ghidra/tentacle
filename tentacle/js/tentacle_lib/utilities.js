/*utilities "class"
utilities 
    .get_position(element)
    .move_to_center(element)
    .relative_mouse_position(event)
    .attach_drag_event(move function,release function)
    .remove_drag_event(move function,release function)
    .disable_selection(id)
----------------*/
tentacle.utilities = {
    'get_position' : function(obj){
        Element.cumulativeScrollOffset
        var curleft= 0;
        var curtop= 0;
        if(obj.offsetParent){//if the browser suppert this object call, offsetParent
            do {
                curleft += obj.offsetLeft;
                curtop += obj.offsetTop;
            }while (obj = obj.offsetParent);
                return [curleft,curtop];
        }
    },
    'move_to_center' : function(id){
		//alert('move_to_center');
        var w_offset = id.offsetWidth/2;
        var h_offset = id.offsetHeight/2;
        var w_width = (document.viewport.getWidth()/2) + document.viewport.getScrollOffsets().left;
        var w_height = (document.viewport.getHeight()/4) + document.viewport.getScrollOffsets().top;
    
        id.setStyle({top:w_height+'px',left:(w_width-w_offset)+'px',zIndex:'1'});
    },
    'relative_mouse_position' : function(event){
        return [ event.pointerX(), event.pointerY()-5 ];//return the mouse pos relative to page scroll
        //return [ event.pointerX() - document.viewport.getScrollOffsets().left , event.pointerY() - document.viewport.getScrollOffsets().top ];//return the mouse pos relative to page scroll
    },
    'attach_drag_event' : function(move, release){
        //tentacle.utilities.remove_drag_event(move,release);
        document.observe( 'mousemove',move);
        document.observe( 'mouseup',release);
    },
    'remove_drag_event' : function(move,release){
        document.stopObserving('mousemove',move);
        document.stopObserving('mouseup',release);
    },
    'disable_selection' : function(id){
        if (typeof id.onselectstart!="undefined") //IE route
            id.onselectstart=function(){return false}
        else if (typeof id.style.MozUserSelect!="undefined") //Firefox route
            id.style.MozUserSelect="none"
        else //All other route (ie: Opera)
            id.onmousedown=function(){return false}
            id.style.cursor = "default"
    },
	'show_hide' : function (id){
		var obj = document.getElementById(id);
		if(obj.style.display=='block'){
			obj.style.display='none';
		}else{
			obj.style.display='block';
		}		
	},
    'element_size':function(id){
        o = document.getElementById(id);
        var w = o.offsetWidth; 
        var h = o.offsetHeight;
        return [w,h];
    },
    'remove_element' :function(id){
		var element = document.getElementById(id);
		element.parentNode.removeChild(element);
    },
    //-------------------elements to attach functions to the element
    'bind': function(elem, e, func, bool) {//dat.gui
        bool = bool || false;
        if (elem.addEventListener){
            elem.addEventListener(e, func, bool);
        }else if (elem.attachEvent){
            elem.attachEvent('on' + e, func);
        }
    },
    'unbind': function(elem, e, func, bool) {
        bool = bool || false;
        if (elem.removeEventListener){
            elem.removeEventListener(e, func, bool);
        }else if (elem.detachEvent){
            elem.detachEvent('on' + e, func);
        }
    },
    'closure':function(scope,fn,arg){//bind my function with the proper this statment
        return function(e){
            fn.call(scope,e,arg);
        };
    },
//this is a function to find out if an object is empty. other wise, the object just returns its origin object array or object
    'object_is_empty' :function(obj) {
        for(var prop in obj) {
            if (Object.prototype.hasOwnProperty.call(obj, prop)) {
                return false;
            }
        }
        return true;
    },

    //-----
   'obj_to_string':function (obj) {
        var str = '';
        for (var p in obj) {
            if (obj.hasOwnProperty(p)) {
                str += p + '::' + obj[p] + '\n';
            }
        }
        return str;
    }
	/*
	//www.richardcastera.com/2008/08/09/get-the-filename-from-upload-form-using-javascript/
	function get_name_from_path(path){//cuts all that fluff off the filepath
		var objre=new RegExp(/([^\/\\]+)$/);
		var strname=objre.exec(path);
		if(strname==null){
			return null;
		}else{
			return strname[0];
		}
	}
	*/
}