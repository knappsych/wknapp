function changeCSS (id, property, setting){
	document.getElementById(id).style[property]=setting;
}

function validate(type, stringtocheck){
	var patt=/\w+/;
	if(type=='email')patt= /^\w+@[a-zA-Z_]+?(\.[a-zA-Z]+)+/;
	if(type=='mcans')patt=/^[a-mA-M]$/;
	return stringtocheck.match(patt); //returns null if the pattern doesn't match
}

function clean(type, stringtoclean){
	if(type=='whiteborder'){
		var patt=/^\s+|\s+$/g;
		return stringtoclean.replace(patt, '');
	}
	return '';
}

function certifywork(){
	if(document.getElementById('honesty').checked){
		changeCSS("work", "display", "inline");
		document.getElementById("certified").value=1;
	}
	else{
		changeCSS("work", "display", "none");
		document.getElementById("certified").value=0;
	}
}