tentacle.element=function(type,data,inner){//inner is option text input, so that I don't have to have a seperate caommand after creation to put some shit in here
	return this.init(type,data,inner);
}
tentacle.element.prototype.init=function(type,data,inner){
	
	var e = document.createElement(type);
	for (var key in data){
		if(key == 'style'){
			for(var style_attribute in data.style){
				e.style[style_attribute] = data.style[style_attribute];
			}
		}else{
			e.setAttribute(key,data[key]);
		}
	}
	//now add the inner if we have it
	if (inner) e.innerHTML = inner;

	return e;
}