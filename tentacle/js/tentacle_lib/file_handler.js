/*file_handler "class"
//this code handles files for upload and thumbnanils
----------------*/

file_handler = {
	'desired_thumb_width':80,
	'desired_thumb_height':60,
	'lg_im_width':0,
	'lg_im_height':0,
	'upload_dialogue' : function (albumtype,album){
		if(!($("navigation_upload"))){//if there isn't already a property page open
			new Ajax.Request(
	            'editor_database_handler.php',{
	                method:'get',
	                parameters:{
	                    act:'new_upload_window',
	                    type: albumtype,
						album: album
	                },
	                onSuccess:function(transport){
						floating_window.create("navigation_upload","window_box",transport.responseText);
				        file_handler.set_uploader();//set the uploader to work yo
	                },
	                onFailure:function(){alert('something went wrong inserting a node')}
	            }
			);
		}
	},
	'set_uploader' : function (){//called from upload
	    //---------prototype and upload script writter for prototype
	    var interval;
	    new Ajax_upload('#upload_file', {
	        action: 'uploader.php',
	        onSubmit: function(file,extension) {
	            // allow only 1 upload
	            this.disable();
	            //---------upload_file is the id of the choose file button in the form
	            var album_type=document.getElementById("upload_type").value;//reach right into the form
	            var data_input=document.getElementById("upload_data").value;
	            var upload_folder=document.getElementById("upload_folder").value;
	            var upload_album=document.getElementById("upload_album").value;

	            //var form=document["upload_form"];//name of the form
	            //the set data sends the uploader.php all the data as post data
	            if(album_type=="album_images"){
	                if(data_input=="true"){
	                    var image_desc=document.getElementById("mtxDesc").value;//reach right into the form, this is the title
	                    this.setData({"filename":file,"folder":upload_folder,"album":upload_album,"mtxDesc":image_desc,"album_type":album_type,"data_input":data_input});
	                }else{
	                    this.setData({"filename":file,"folder":upload_folder,"album":upload_album,"album_type":album_type,"data_input":data_input});
	                }
	            }else if(album_type=="album_movies"){
	                if(data_input=="true"){
	                    var movie_w=document.getElementById("moviewidth").value;//reach right into the form, this is the title
	                    var movie_h=document.getElementById("movieheight").value;//reach right into the form, this is the title
	                    var movie_desc=document.getElementById("movieDesc").value;//reach right into the form, this is the title
	                    this.setData({"filename":file,"folder":upload_folder,"album":upload_album,"moviewidth":movie_w,'movieheight':movie_h,"movieDesc":movie_desc,"album_type":album_type,"data_input":data_input});
	                }else{
	                    this.setData({"filename":file,"folder":upload_folder,"album":upload_album,"album_type":album_type,"data_input":data_input});
	                }
	            }
	            //------
	            var status = document.getElementById("uploadstatus");
	            document.getElementById("uploadstatus").innerHTML="uploading";
	            interval = window.setInterval(function(){
					var text = document.getElementById("uploadstatus").innerHTML;
					if (text.length < 13){
						document.getElementById("uploadstatus").innerHTML=text + '.';					
					} else {
						document.getElementById("uploadstatus").innerHTML='uploading';				
					}
				}, 200);
	        },
	        onComplete : function(file,response){
	            document.getElementById("uploadstatus").innerHTML=response;
	            window.clearInterval(interval);
	            this.destroy();
	            file_handler.upload_next(file);
	            //$$('#upload .uploadstatus').update(file);
	        }	
	    });
	    //-------------
	},
	'upload_next' : function (file){//this is called from upload-progess after an upload is finished
		var next_method=document.getElementById("upload_next").value;//reach right in and just grab the function from the hidden field
		var upload_albu=document.getElementById("upload_album").value;//reach right in and just grab the album from the hidden field
		$("navigation_upload").remove();//remove the window
		//-----------------
		if(next_method=="thumb_me"){
			file_handler.create_thumb_window(upload_albu,file);
			return;
		}else if(next_method=="image_me"){
			file_handler.upload("album_images",upload_albu);
			return;
		}
	},
	'create_thumb_window' : function (pa,fi){
		if(!($("navigation_create_thumb"))){//if there isn't already a property page open
			new Ajax.Request(
	            'editor_database_handler.php',{
	                method:'get',
	                parameters:{
	                    act:'create_thumb_window',
	                    path: pa,
						file: fi
	                },
	                onSuccess:function(transport){
						var d = transport.responseText.evalJSON(true);
				        //var d = JSON.parse(xmlHttp.responseText);//parse the incoming text
						//set the "global" large image width and height
						file_handler.lg_im_width=d[3];
						file_handler.lg_im_height=d[4];
						//alert(d[1]);
						floating_window.create("navigation_create_thumb","window_box",d[0]);//make a window to hold it

						//-----------------------------
				        $('thumbnail_display').observe('load',function(){//wait for it to load first before procedding
				        	new Cropper.ImgWithPreview(
				            	'thumbnail_display',
				            	{
				            		previewWrap: 'thumbnail_preview',
				                	minWidth: file_handler.desired_thumb_width,
				                	minHeight: file_handler.desired_thumb_height,
				                	ratioDim: { x: file_handler.desired_thumb_width, y: file_handler.desired_thumb_height },
				                	onEndCrop: file_handler.onEndCrop
				            	}
				        	);
				        });
	                },
	                onFailure:function(){alert('something went wrong making thumbnail')}
	            }
			);
		}
	},
	'onEndCrop' : function(coords,dimensions){
		$( 'x1' ).value = coords.x1;
	    $( 'y1' ).value = coords.y1;
	    $( 'x2' ).value = coords.x2;
	    $( 'y2' ).value = coords.y2;
	    $( 'w' ).value = dimensions.width;
	    $( 'h' ).value = dimensions.height;
	},
	'save_thumbnail' :function (){//called from the same thumbnail button
	//	alert("x1="+$('x1').value+"&y1="+$('y1').value+"&x2="+$('x2').value+"&y2="+$('y2').value+"&w="+$('w').value+"&h="+$('h').value+"&path="+$('p').value+"&file="+$('f').value);
		new Ajax.Request(
            'editor_database_handler.php',{
                method:'get',
                parameters:{
                    act:'upload_thumbnail',
                    x1 : $('x1').value,
				    y1 : $('y1').value,
				    x2 : $('x2').value,
				    y2 : $('y2').value,
				    w : $('w').value,
				    h : $('h').value,
				    path : $('tpath').value,
				    file : $('tfile').value
                },
                onSuccess:function(transport){
					$("navigation_create_thumb").remove();
                },
                onFailure:function(){alert('something went wrong inserting a node')}
            }
		);
	 }
}