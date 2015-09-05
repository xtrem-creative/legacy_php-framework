<?php
$balises = array();
$balises['saut'] = array(
			'beginHtml'				=> '<br />',
			'endHtml'				=> NULL,
			'beginXtcode'			=> '',
			'endXtcode'				=> '',
		);
/* Balises simples
 * Sous la forme <nom_x></nom_x>
 * */
$balises['gras'] = array( 
			'beginHtml'				=> '<strong>',
			'endHtml'				=> '</strong>',
			'beginXtcode'			=> '|gras|',
			'endXtcode'				=> '|/gras|',
		);
$balises['paragraphe'] = array(
			'beginHtml'				=> '<p class="xtcode_paragraphe">',
			'endHtml'				=> '</p>',
			'beginXtcode'			=> '',
			'endXtcode'				=> '',
		);
$balises['italique'] = array(
			'beginHtml'				=> '<em>',
			'endHtml'				=> '</em>',
			'beginXtcode'			=> '|italique|',
			'endXtcode'				=> '|/italique|',
		);
$balises['souligne'] = array(
			'beginHtml'				=> '<span class="xtcode_souligne">',
			'endHtml'				=> '</span>',
			'beginXtcode'			=> '|souligne|',
			'endXtcode'				=> '|/souligne|',
		);
$balises['barre'] = array(
			'beginHtml'				=> '<del>',
			'endHtml'				=> '</del>',
			'beginXtcode'			=> '|barre|',
			'endXtcode'				=> '|/barre|',
		);
$balises['titre1'] = array(
			'beginHtml'				=> '<h1 class="xtcode_titre_1">',
			'endHtml'				=> '</h1>',
			'beginXtcode'			=> '|titre1|',
			'endXtcode'				=> '|/titre1|',
		);
$balises['titre2'] = array(
			'beginHtml'				=> '<h2 class="xtcode_titre_2">',
			'endHtml'				=> '</h2>',
			'beginXtcode'			=> '|titre2|',
			'endXtcode'				=> '|/titre2|',
		);
$balises['expose'] = array(
			'beginHtml'				=> '<sup>',
			'endHtml'				=> '</sup>',
			'beginXtcode'			=> '|expose|',
			'endXtcode'				=> '|/expose|',
		);
$balises['indice'] = array(
			'beginHtml'				=> '<sub>',
			'endHtml'				=> '</sub>',
			'beginXtcode'			=> '|indice|',
			'endXtcode'				=> '|/indice|',
		);
$balises['racine'] = array(
			'beginHtml'				=> '<em>RacineCarrée[',
			'endHtml'				=> ']</em>',
			'beginXtcode'			=> '|racine|',
			'endXtcode'				=> '|/racine|',
		);
/* Balises complexes
 * de niveau 1
 * <nom_x attr="y"></nom_x>
 * */
 $balises['surligne'] = array(
			'type'					=> 'normale',
			'beginHtml'				=> '<span$1>',
			'endHtml'				=> '</span>',
			'beginXtcode'			=> '|surligne$1|',
			'endXtcode'				=> '|/surligne|',
			'nbParams'				=> 1,
				'paramXml_1'		=> 'nom',
				'paramXtcode_1'		=> '="$1"',
				'paramHtml_1'		=> ' class="xtcode_fluo-$1"',
		);
$balises['taille'] = array(
			'type'					=> 'normale',
			'beginHtml'				=> '<span$1>',
			'endHtml'				=> '</span>',
			'beginXtcode'			=> '|taille$1|',
			'endXtcode'				=> '|/taille|',
			'nbParams'				=> 1,
				'paramXml_1'		=> 'valeur',
				'paramXtcode_1'		=> '="$1"',
				'paramHtml_1'		=> ' class="xtcode_taille_$1"',
		);
$balises['acronyme'] = array(
			'type'					=> 'normale',
			'beginHtml'				=> '<acronym$1>',
			'endHtml'				=> '</acronym>',
			'beginXtcode'			=> '|acronyme$1|',
			'endXtcode'				=> '|/acronyme|',
			'nbParams'				=> 1,
				'paramXml_1'		=> 'signification',
				'paramXtcode_1'		=> '="$1"',
				'paramHtml_1'		=> ' title="$1"',
		);
$balises['alignement'] = array(
			'type'					=> 'normale',
			'beginHtml'				=> '<span$1>',
			'endHtml'				=> '</span>',
			'beginXtcode'			=> '|alignement$1|',
			'endXtcode'				=> '|/alignement|',
			'nbParams'				=> 1,
				'paramXtcode_1'		=> '="$1"',
				'paramXml_1'		=> 'cote',
				'paramHtml_1'		=> 'class="xtcode_align-$1"',
		);
$balises['flottant'] = array(
			'type'					=> 'normale',
			'beginHtml'				=> '<span$1>',
			'endHtml'				=> '</span>',
			'beginXtcode'			=> '|flottant$1|',
			'endXtcode'				=> '|/flottant|',
			'nbParams'				=> 1,
				'paramXtcode_1'		=> '="$1"',
				'paramXml_1'		=> 'cote',
				'paramHtml_1'		=> 'class="xtcode_flottant-$1"',
		);
$balises['citation']	= array(
			'type'					=> 'normale',
			'name_xml'				=> 'citation',
			'beginXtcode'			=> '|citation$1|',
			'endXtcode'				=> '|/citation|',
			'beginHtml'				=> '<p class="xtcode_citation"$1>',
			'endHtml'				=> '</p>',
			'nbParams'				=> 1,
				'paramXml_1'		=> 'auteur',
				'paramXtcode_1'		=> '="$1"',
				'paramHtml_1'		=> '><strong>Auteur : $1</strong><br /',
				'defaultXml_1'		=> 'Anonyme',
		);
 /* Balises complexes
 * de niveau 2
 * <nom_x attr1="y1" attr2="y2"></nom_x>
 * */
$balises['couleur'] = array(
			'type'					=> 'normale',
			'beginHtml'				=> '<span$1>',
			'endHtml'				=> '</span>',
			'beginXtcode'			=> '|couleur$1|',
			'endXtcode'				=> '|/couleur|',
			'nbParams'				=> 2,
				'paramXml_1'		=> 'nom',
				'paramXtcode_1'		=> '="$1"',
				'paramHtml_1'		=> ' class="xtcode_couleur-$1"',
				'optionalParam_1'	=> true,
				'paramXml_2'		=> 'code',
				'paramXtcode_2'		=> '="$1"',
				'paramHtml_2'		=> ' style="color:$1;"',
				'optionalParam_2'	=> true,
		);

$balises['lien']	= array(
			'type'					=> 'normale',
			'name_xml'				=> 'lien',
			'beginHtml'				=> '<a class="xtcode_lien"$1>',
			'endHtml'				=> '</a>',
			'beginXtcode'			=> '|lien$1|',
			'endXtcode'				=> '|/lien|',
			'nbParams'				=> 2,
				'paramXml_1'		=> 'url',
				'paramXtcode_1'		=> '="$1"',
				'paramHtml_1'		=> ' href="$1" ',
				'optionalParam_1'	=> 'url',
				
				'paramXml_2'		=> 'lang',
				'paramXtcode_2'		=> ' lang="$1"',
				'paramHtml_2'		=> '><img src="' . ROOTU . 'lang/$1/flag.png" /',
				'choicesParam_2'	=> 'fr|en',
				'defaultXml_2'		=> 'fr',
				'optionalParam_2'	=> true,
		);
 /* Balises complexes spéciales
 * Image
 * */
$balises['image'] = array(
			'type'					=> 'image',
			'beginHtml'				=> '<img class="xtcode_image" onclick="retrecir( this )" $1 title="',
			'endHtml'				=> '" />',
			'beginXtcode'			=> '|image$1|',
			'endXtcode'				=> '|/image|',
			'nbParams'				=> 3,
				'paramXml_1'		=> 'lien',
				'paramHtml_1'		=> ' src="$1"',
				'paramXtcode_1'		=> '="$1"',
				'optionalParam_1'	=> true,
				
				'paramXml_2'		=> 'text_alternatif',
				'paramHtml_2'		=> ' alt="$1"',
				'paramXtcode_2'		=> ' text_alternatif="$1"',
				'optionalParam_2'	=> true,
				'defaultXml_2'		=> 'Image utilisateur',
				
				'paramXml_3'		=> 'legende',
				'paramXtcode_3'		=> ' legende="$1"',
				'paramHtml_3'		=> '<p>Légende : $1 </p>',
				'paramPlaceHtml_3'	=> 'end',
				'optionalParam_3'	=> true,
		);
/* Tableau, cas particulier pas tant que ça
 * */
 $balises['tableau'] = array(
			'type'					=> 'normale',
			'beginHtml'				=> '<table>',
			'endHtml'				=> '</table>',
			'beginXtcode'			=> '|table|',
			'endXtcode'				=> '|/table|',
			'nbParams'				=> 0,
			'needBalise'			=> 'ligne',
		);
 $balises['ligne'] = array(
			'type'					=> 'normale',
			'beginHtml'				=> '<tr>',
			'endHtml'				=> '</tr>',
			'beginXtcode'			=> '|ligne|',
			'endXtcode'				=> '|/ligne|',
			'nbParams'				=> 0,
			'needBalise'			=> 'colonne',
		);
 $balises['colonne'] = array(
			'type'					=> 'normale',
			'beginHtml'				=> '<td>',
			'endHtml'				=> '</td>',
			'beginXtcode'			=> '|colonne|',
			'endXtcode'				=> '|/colonne|',
			'nbParams'				=> 0,
		);
/* Liste autre cas...
 * */
 $balises['liste'] = array(
			'type'					=> 'normale',
			'beginHtml'				=> '<ul>',
			'endHtml'				=> '</ul>',
			'beginXtcode'			=> '|liste|',
			'endXtcode'				=> '|/liste|',
			'nbParams'				=> 0,
			'needBalise'			=> 'puce',
		);
 $balises['puce'] = array(
			'type'					=> 'normale',
			'beginHtml'				=> '<li>',
			'endHtml'				=> '</li>',
			'beginXtcode'			=> '|puce|',
			'endXtcode'				=> '|/puce|',
			'nbParams'				=> 0,
		);
$smileys = array(
	' :)'  => 1,
	' :-)'  => 1,
	' (:'  => 1,
	' (-:'  => 1,
	' =)'  => 2,
	' :D'  => 3,
	' :-D'  => 3,
	' :d'  => 3,
	' :-d'  => 3,
	' ;)'  => 4,
	' :-o'  => 5,
	' :P'  => 6,
	' :p'  => 6,
	' (H)'  => 7,
	' :@'  => 8,
	' :S'  => 9,
	' :$'  => 10,
	' :('  => 11,
	' :-('  => 11,
	' ):'  => 11,
	' )-:'  => 11,
	' :\'('  => 12,
	' 8-|'  => 13,
	' (K)'  => 14,
	' +o('  => 15,
	' ^o)'  => 16,
	' (6)'  => 17,
	' :['  => 18,
	' :}'  => 19,
	' ^^'  => 20,
	' xD'  => 21,
	' \':('  => 22,
	' 0^^' => 23,
	'\':p' => 24,
	'\':D' => 25,
	':\')' => 26,
);

return array( 'balises' => $balises, 'smileys' => $smileys );
?>
