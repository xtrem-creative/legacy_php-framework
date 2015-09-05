function insert( idTextarea, aInserer, detailsSupp )
{
	if( typeof detailsSupp == "undefined" )
		detailsSupp = false;
	document.getElementById( idTextarea ).value += aInserer + "\n";
	if( detailsSupp != false )
	{
		if( detailsSupp == "sans" )
			insert_multi( idTextarea );
		else if( detailsSupp == "avec" )
			insert_multi( idTextarea, true );
	}
}

function insert_multi( idTextarea, avecValeurs )
{
	if( typeof avecValeurs == "undefined" )
		avecValeurs = false;
	document.getElementById( idTextarea ).value += "{\n";
	if( avecValeurs == true )
	{
		var i = 0;
		while( nombreOptions = prompt( "Nom, id et valeur séparés par des virgules : " ) || i == 0 )
		{
			if( typeof nombreOptions == "boolean" ) continue;
			document.getElementById( idTextarea ).value += "	option <" + nombreOptions + ">\n";
			i = 1;
		}
	}
	else
	{
		var nombreOptions = parseInt( prompt( "Nombre d'options à proposer : " ) );
		var i = 1;
		do 
		{
			i++;
			document.getElementById( idTextarea ).value += "	Option <nom,id,valeur>\n";
		}
		while( i <= nombreOptions )
	}
	document.getElementById( idTextarea ).value += "}\n";
}
