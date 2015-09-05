function XTCode_lib_cache( idDiv )
{
	var div = document.getElementById( idDiv );
	if( div.style.display == 'none') 
		div.style.display='block'; 
	else 
		div.style.display='none'; 
}

function XTCode_lib_insertTag(startTag, endTag, textareaId, tagType) 
{
	var field = document.getElementById(textareaId);
	var scroll = field.scrollTop;
	field.focus();
	
	
	if (window.ActiveXObject) {
		var textRange = document.selection.createRange();            
		var currentSelection = textRange.text;
	} else {
		var startSelection   = field.value.substring(0, field.selectionStart);
		var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
		var endSelection     = field.value.substring(field.selectionEnd);
	}
	
	if (tagType) {
		switch (tagType) {
			case "lien":
					endTag = "|/lien|";
					if (currentSelection) {
							if (currentSelection.indexOf("http://") == 0 || currentSelection.indexOf("https://") == 0 || currentSelection.indexOf("ftp://") == 0 || currentSelection.indexOf("www.") == 0) {
									var label = prompt("Quel est le libellé du lien ?") || "";
									startTag = "|lien=\"" + currentSelection + "\"|";
									currentSelection = label;
							} else {
									var URL = prompt("Quelle est l'url ?");
									startTag = "|lien=\"" + URL + "\"|";
							}
					} else {
							var URL = prompt("Quelle est l'url ?") || "";
							var label = prompt("Quel est le libellé du lien ?") || "";
							startTag = "|lien=\"" + URL + "\"|";
							currentSelection = label;                     
					}
			break;
			case "citation":
					endTag = "|/citation|";
					if (currentSelection) {
						var auteur = prompt("Quel est l'auteur de la citation ?") || "";
						startTag = "|citation=\"" + auteur + "\"|";
					} else {
						var auteur = prompt("Quel est l'auteur de la citation ?") || "";
						startTag = "|citation=\"" + auteur + "\"|";
						currentSelection = '';    
					}
			break;
			default:
				document.getElementById( tagType + '1' ).selected = true;
			break;
		}
	}
	
	if (window.ActiveXObject) {
		textRange.text = startTag + currentSelection + endTag;
		textRange.moveStart('character', -endTag.length-currentSelection.length);
		textRange.moveEnd('character', -endTag.length);
		textRange.select();  
	} else { // Ce n'est pas IE
		field.value = startSelection + startTag + currentSelection + endTag + endSelection;
		field.focus();
		field.setSelectionRange(startSelection.length + startTag.length, startSelection.length + startTag.length + currentSelection.length);
	}  
	
	field.scrollTop = scroll;   
}

function XTCode_lib_smilies( img, textareaId )
{
	var field = document.getElementById(textareaId);
	var scroll = field.scrollTop;
	field.focus();
	
	
	if (window.ActiveXObject) {
		var textRange = document.selection.createRange();            
	} else {
		var startSelection   = field.value.substring(0, field.selectionStart);
		var endSelection     = field.value.substring(field.selectionEnd);
	}
	
	
	if (window.ActiveXObject) {
		textRange.text = img;
		textRange.moveStart('character', 0);
		textRange.moveEnd('character', 0);
		textRange.select();  
	} else { // Ce n'est pas IE
		field.value = startSelection + img + endSelection;
		field.focus();
		field.setSelectionRange(startSelection.length + img.length, startSelection.length + img.length);
	}  
	
	field.focus();	
	field.scrollTop = scroll;   
}
