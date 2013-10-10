/*
everything here in is used specifically in the editing of tentacle nodes in the editor.
written by jimmy gass, and by those noted next to code.
*/
//-------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
tentacle = {};//this is our main javascript object object
tentacle.includes = {
    'ext_lib': ["prototype-1.6.0.3",
        "scriptaculous_1.8.2/scriptaculous",
        "wz_jsgraphics",
        "ajaxupload.3.5",
        "cropper/cropper",
        "shortcut",
        "rgbcolor"
    ],
    'tentacle_lib' : ["node",
		"nodes",
		"compounds",
        "connection",
		"connections",
        "html",
        "selection",
        "floating_window",
        "properties_window",
        "node_properties",
		"file_handler",
		"database",
        "drop_down",
        "utilities",
		"element",
        "i_o",
		"console"
    ],
    'include' : function ( src ) { 
        document.write('<script type="text/javascript" language="Javascript" src="'+src+'"><\/script>'); 
    },
    'complete_link' : function ( path,links ) {
        for(var i=0;i<links.length;i++){
            links[i]="js/"+path+links[i]+".js";
        }
    },
    'load_libraries' : function () {
        this.complete_link("ext_lib/",this.ext_lib);
        this.complete_link("tentacle_lib/",this.tentacle_lib);
        var all_scripts = this.ext_lib.concat(this.tentacle_lib);
        for(var i in all_scripts){
            this.include(all_scripts[i]);
        }
    }
}
tentacle.includes.load_libraries();
//------------------------------------------------------------------------------------------
window.onload=function(){
    shortcut.add("Ctrl+O",function(){tentacle.i_o.browse_comps()});// http://www.openjs.com/scripts/events/keyboard_shortcuts/# 
    shortcut.add('Shift',function(){tentacle.selection.shift_pressed();},{'disable_in_input':true});
    shortcut.add("Ctrl+C",function(){tentacle.selection.copy()},{'disable_in_input':true});
    shortcut.add("Ctrl+V",function(){tentacle.selection.paste()},{'disable_in_input':true});
    shortcut.add("Ctrl+S",function(){tentacle.i_o.save_comp()},{'disable_in_input':true});
	shortcut.add("Ctrl+M",function(){tentacle.selection.compound()},{'disable_in_input':true});
    shortcut.add("C",function(){tentacle.console.toggle('')},{'disable_in_input':true});
    shortcut.add("V",function(){tentacle.console.toggle('variables')},{'disable_in_input':true});
    
    Event.observe(document.body, 'mousedown', function(event) {
        if ( Event.element(event).identify() == 'composition' ) tentacle.selection.marquee(event);
    });

}