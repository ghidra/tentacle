/*database "class"
//this code interfaces mostly with the database set up created by tentacle. To access nav pages and abums and whatnots
----------------*/

tentacle.database = {
	'property_window' : {},
	'property_window_id' : 'databse_query_window',
	'label_width' : 80,
	'input_width' : 140,
	//inspect_database_page //called on double click of node. for inspection
	'inspect_page' : function (id){
		if(!($("navigation_"+id))){//if there isn't already a property page open
			new Ajax.Request(
	            'editor_database_handler.php',{
	                method:'get',
	                parameters:{
	                    act:'inspect',
	                    id: id
	                },
	                onSuccess:function(transport){
						floating_window.create("navigation_"+id,"window_box",transport.responseText);
	                },
	                onFailure:function(){alert('something went wrong inserting a node')}
	            }
	        );
		}
	},
	'inspect_image' : function(nav_id,album,image){
		new Ajax.Request(
            'editor_database_handler.php',{
                method:'get',
                parameters:{
                    act:'inspect_image',
                    album: album,
					image: image
                },
                onSuccess:function(transport){
					$("element_edit_"+nav_id).innerHTML = transport.responseText;
                },
                onFailure:function(){alert('something went wrong inserting a node')}
            }
        );
	},
	'inspect_movie' : function(nav_id,album,movie){
		new Ajax.Request(
            'editor_database_handler.php',{
                method:'get',
                parameters:{
                    act:'inspect_movie',
                    album: album,
					movie: movie
                },
                onSuccess:function(transport){
					$("element_edit_"+nav_id).innerHTML = transport.responseText;
                },
                onFailure:function(){alert('something went wrong inserting a node')}
            }
        );
	},
	'add_nav_window' :  function(){
		//since we can only have one database window at a time, we need to see if there is already one, and remove that shit.
		if(document.getElementById(this.property_window_id)){
			//tentacle.utilities.remove_element(this.property_window_id);
			this.property_window.remove();
			this.property_window={};//clear that shit out now
		}

		this.property_window = new tentacle.properties_window();

		this.property_window.build(
			this.property_window_id,
			'add new page',
			{
				'page settings':{
					'name':'',
					'type':{
						'ind':'page',
						'album_images':'image album',
						'album_movies':'movie album'
					},
					'hidden':false,
					'&nbsp':'add new page'
				}
			}
		);

		this.property_window.entries['&nbsp'].onclick=function(){tentacle.database.add_nav();};//add the button function
		//this.property_window.entries['name'].onchange=function(){alert('what now');};//add the button function
	},
	'add_nav' : function(){//called from button to make a new navigation page
		var form=document[this.property_window_id+"_form"];//name of the form
		var ti=form[this.property_window_id+"_name_input"].value;
		var ty=form[this.property_window_id+"_type_input"].value;
	    var vi=(form[this.property_window_id+"_hidden_input"].checked)?0:1;

	    //if(form["hidden_boolean"].checked) vi=0;
		new Ajax.Request(
			'editor_database_handler.php',{
                method:'get',
                parameters:{
                    act:'new_nav',
					title: ti,
					type: ty,
					vis: vi
                },
                onSuccess:function(transport){
                	//$("navigation_add_db").remove();
                	tentacle.database.property_window.remove();
                	var page_menu = document.getElementById("database_buttons").innerHTML; 
                	//THIS IS NOT ACTUALLY ADDING TO THE PAGE STACK UNDER TENTACLE BUTTON WHERE IT SHOULD
					page_menu = pagemenu+transport.responseText;//prety bootleg i'm sure, but it works
					tentacle.console.log('successfully added a new page');
				},
                onFailure:function(){alert('something went wrong inserting a node')}
            }
		);
	},
	'remove_nav' : function(id){
		if (confirm('Delete this page and all its contents?')){
			new Ajax.Request(
				'editor_database_handler.php',{
	                method:'get',
	                parameters:{
	                    act:'remove_nav',
						id: id
	                },
	                onSuccess:function(transport){
	                	$("node_menu_db").removeChild($("n_button_"+id));
						$("navigation_"+id).remove();
						tentacle.console.log('successfully removed '+transport.responseText);
					},
	                onFailure:function(){alert('something went wrong inserting a node')}
	            }
			);
		}
	}
	/*
	function inspect_database_page(id){//called on double click of node. for inspection
		if(!(document.getElementById("navigation_"+id))){//if there isn't already a property page open
			ajax(stateChangedDataPage,"editor_database_handler.php?act=inspect&id="+id);
			navigation_count=id;
		}
	}
	function stateChangedDataPage(){
		if(xmlHttp.readyState==4){
			floating_window.create("navigation_"+navigation_count,"window_box",xmlHttp.responseText);		
		}
	}
	function inspect_image(nav_id,album,image){
		ajax(stateChangedDataImage,"editor_database_handler.php?act=inspect_image&album="+album+"&image="+image);
		navigation_count=nav_id;//i need the nav id so it will populate the right window box
	}
	function stateChangedDataImage(){
		if(xmlHttp.readyState==4){
			//alert("how");
			var div = document.getElementById("element_edit_"+navigation_count);
			div.innerHTML=xmlHttp.responseText;
		}
	}
	function view_image(location){
		alert(location);
	}
	function inspect_movie(nav_id,album,movie){
		ajax(stateChangedDataImage,"editor_database_handler.php?act=inspect_movie&album="+album+"&movie="+movie);
		navigation_count=nav_id;//i need the nav id so it will populate the right window box
	}
	//-----create nav
	function add_db_nav_window(){//add a new db page
		if(!(document.getElementById("navigation_add_db"))){//if there isn't already a property page open
			ajax(sc_db_nav_window,"editor_database_handler.php?act=new_nav_window");
		}
	}
	function sc_db_nav_window(){
		if(xmlHttp.readyState==4){
			floating_window.create("navigation_add_db","window_box",xmlHttp.responseText);
		}		
	}
	function add_db_nav(){//called from button to make a new navigation page
		var form=document["new_table_form"];//name of the form

		var ti=form["txtName"].value;
		var ty=form["mtxDesc"].value;
	    var vi=1;
	    if(form["visibility"].checked) vi=0;
	    ajax(sc_db_nav,"editor_database_handler.php?act=new_nav&title="+ti+"&type="+ty+"&vis="+vi);
	}
	function sc_db_nav(){
		if(xmlHttp.readyState==4){
			//need to remove the window with the things in it, the navigation input
			$("navigation_add_db").remove();
			//we need to inser the newbutton somehow
			document.getElementById("database_buttons").innerHTML=document.getElementById("database_buttons").innerHTML+xmlHttp.responseText;//prety bootleg i'm sure, but it works
			//log our success to the concole from the create ajaz early
			//log_to_console("sucessfull created a new page");
		}
	}
	//----remove nav
	function remove_nav_page(id){
		if (confirm('Delete this page and all its contents?')) {
	      	ajax(sc_db_rm_nav,"editor_database_handler.php?act=remove_nav&id="+id);
			naigation_count=id;
		  // window.location.href = 'index.php?fromAlbum=' + albumId + '&imid=' + imid +'&t=m';
	    }
	}
	function sc_db_rm_nav(){
		if(xmlHttp.readyState==4){
			//$("n_button_"+navigation_count).remove();
			document.getElementById("node_menu_db").removeChild(document.getElementById("n_button_"+navigation_count));//wouldn't you know it, it is in a differnt number

			$("navigation_"+navigation_count).remove();
			//log_to_console("id:"+xmlHttp.responseText+":nav item has been terminated");
		}
	}
	*/
}