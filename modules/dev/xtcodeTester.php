<?php
require_once( '../../kernel/begin.php' );
$xtcode = new xtcode();
?>
<script type="text/javascript" src="http://www.xtrem-creative.com/lib/xtcode.js"></script>
<link href="http://www.xtrem-creative.com/designs/default/css/XTCode.css" rel="stylesheet" type="text/css" />

<form method="post" action="">
	<p>
	<fieldset>
		<legend>Ajout d'un brouillon</legend>
		<input type="text" name="intitule" size="79" />
		<br /><br />
				<p class="xtc_boutons xtcode_select"> 
			<input type="button" value="G" class="button_gras" title="Gras" onFocus="document.getElementById( 'contenu_brouillon' ).focus();" onclick="insertTag('|gras|', '|/gras|', 'contenu_brouillon')" /> 
			<input type="button" value="i" class="button_italique" title="Italique" onclick="insertTag('|italique|', '|/italique|', 'contenu_brouillon')" /> 
			<input type="button" class="button_barre" title="Barré" onclick="insertTag('|barre|', '|/barre|', 'contenu_brouillon')" /> 
			<input type="button" class="button_souligne" title="Souligné" onclick="insertTag('|souligne|', '|/souligne|', 'contenu_brouillon')" /> 
			<input type="button" class="button_lien" title="Lien" onclick="insertTag('', '', 'contenu_brouillon', 'lien')" /> 
			<input type="button" class="button_image" title="Image" onclick="insertTag('|image|', '|/image|', 'contenu_brouillon')" /> 
			<input type="button" class="button_citation" title="Citation" onclick="insertTag('', '', 'contenu_brouillon', 'citation')" />
			<input type="button" class="button_previsualiser" title="Prévisualiser" onclick="previs('contenu_brouillon', 'preview_contenu_brouillon', 'http://www.xtrem-creative.com/lib' );" />

		<br /><br />
		<select onchange="insertTag('|surligne=&quot;' + this.options[this.selectedIndex].value + '&quot;|', '|/surligne|', 'contenu_brouillon', 'surligne');">
				<option value="none" class="selected" id="surligne1" selected="selected">Surligner</option> 
				<option value="bleu">Bleu</option> 
				<option value="jaune">Jaune</option>
				<option value="orange">Orange</option> 
				<option value="rose">Rose</option> 
				<option value="vert">Vert</option> 
			</select>

			
			<select onchange="insertTag('|taille=&quot;' + this.options[this.selectedIndex].value + '&quot;|', '|/taille|', 'contenu_brouillon', 'taille');"> 
				<option value="none" class="selected" id="taille1" selected="selected">Taille</option> 
				<option value="1">Très petit</option> 
				<option value="2">Petit</option> 
				<option value="3">Gros</option> 
				<option value="4">Très Gros</option> 
			</select>
			
			<select onchange="insertTag('|titre' + this.options[this.selectedIndex].value + '|', '|/titre' + this.options[this.selectedIndex].value + '|', 'contenu_brouillon', 'titre');"> 
				<option value="none" class="selected" id="titre1" selected="selected">S&eacute;mantique</option> 
				<option value="1">Titre 1</option> 
				<option value="2">Titre 2</option> 
			</select> 
			<select onchange="insertTag('|flottant=&quot;' + this.options[this.selectedIndex].value + '&quot;|', '|/flottant|', 'contenu_brouillon', 'flottant');"> 
				<option value="none" class="selected" id="flottant1" selected="selected">Flottant</option> 
				<option value="gauche">Gauche</option> 
				<option value="droite">Droite</option> 
			</select> 
		
			<select onchange="insertTag('|couleur=&quot;' + this.options[this.selectedIndex].value + '&quot;|', '|/couleur|', 'contenu_brouillon', 'couleur');">

				<option value="none" class="selected" id="couleur1" selected="selected">Couleur</option> 
				<option value="blanc">Blanc</option> 
				<option value="bleu">Bleu</option>
				<option value="bleuc">Bleu clair</option>
				<option value="bleuf">Bleu foncé</option>
				<option value="gris">Gris</option> 
				<option value="jaune">Jaune</option>

				<option value="orange">Orange</option>
				<option value="or">Or</option> 
				<option value="rouge">Rouge</option>
				<option value="vertc">Vert clair</option>
				<option value="vertf">Vert foncé</option>
				<option value="violet">Violet</option> 
				<option value="#">Autre couleur (hexadécimale)</option>

			</select>
			
		</p>
		<p><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_1.png" title=" :)" alt=" :)" onClick="smilies(' :)', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_2.png" title=" =)" alt=" =)" onClick="smilies(' =)', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_3.png" title=" :D" alt=" :D" onClick="smilies(' :D', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_4.png" title=" ;)" alt=" ;)" onClick="smilies(' ;)', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_5.png" title=" :-o" alt=" :-o" onClick="smilies(' :-o', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_6.png" title=" :P" alt=" :P" onClick="smilies(' :P', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_7.png" title=" (H)" alt=" (H)" onClick="smilies(' (H)', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_8.png" title=" :@" alt=" :@" onClick="smilies(' :@', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_9.png" title=" :S" alt=" :S" onClick="smilies(' :S', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_10.png" title=" :$" alt=" :$" onClick="smilies(' :$', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_11.png" title=" :(" alt=" :(" onClick="smilies(' :(', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_12.png" title=" :'(" alt=" :'(" onClick="smilies(' :\'(', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_13.png" title=" 8-|" alt=" 8-|" onClick="smilies(' 8-|', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_14.png" title=" (K)" alt=" (K)" onClick="smilies(' (K)', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_15.png" title=" +o(" alt=" +o(" onClick="smilies(' +o(', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_16.png" title=" ^o)" alt=" ^o)" onClick="smilies(' ^o)', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_17.png" title=" (6)" alt=" (6)" onClick="smilies(' (6)', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_18.png" title=" :[" alt=" :[" onClick="smilies(' :[', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_19.png" title=" :}" alt=" :}" onClick="smilies(' :}', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_20.png" title=" ^^" alt=" ^^" onClick="smilies(' ^^', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_21.png" title=" xD" alt=" xD" onClick="smilies(' xD', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_22.png" title=" ':(" alt=" ':(" onClick="smilies(' \':(', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_23.png" title=" 0^^" alt=" 0^^" onClick="smilies(' 0^^', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_24.png" title="':p" alt="':p" onClick="smilies('\':p', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_25.png" title="':D" alt="':D" onClick="smilies('\':D', 'contenu_brouillon');return(false)" /><img src="http://www.xtrem-creative.com/designs/default/images/smileys/Tango_Emote_26.png" title=":')" alt=":')" onClick="smilies(':\')', 'contenu_brouillon');return(false)" />		</p>
		<p>
		<input type="button" value="+" onclick="agrandir('contenu_brouillon')">Taille
		<input type="button" value="-" onclick="diminuer('contenu_brouillon')">
		</p>
		<textarea name="contenu_brouillon" id="contenu_brouillon" cols="90" rows="10"></textarea>

		<div id="preview_contenu_brouillon" class="xtcode_div_preview"></div>
		<br /><br />
		<input type="submit" value="Créer mon brouillon" /> <input type="reset" value="Effacer" />
	</fieldset>
	</p>
	</form>

<?php
echo nl2br( ( isset( $_POST['contenu_brouillon'] ) ? htmlspecialchars( $_POST['contenu_brouillon'] ) : NULL ) );
?>
<br /><br /><br /><br /><br /><br />
<?php
echo nl2br( htmlspecialchars($xtcode->_from_xtcode_to_xml( ( isset( $_POST['contenu_brouillon'] ) ? $_POST['contenu_brouillon'] : NULL ) )));
?>
<br /><br /><br /><br /><br /><br />
<?php
echo nl2br( $xtcode->_from_xml_to_html( $xtcode->getXML() ) );
?>
<br /><br /><br /><br /><br />
<?php
echo nl2br( htmlspecialchars( $xtcode->_from_xml_to_xtcode( $xtcode->getXML() ) ) );
?>
