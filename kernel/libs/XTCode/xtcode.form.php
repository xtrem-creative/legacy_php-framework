<fieldset>
		<legend>Xtform avancé</legend>
		<div class="xtbox">
			<div class="xtbutton gras" title="Gras" onclick="XTCode_lib_insertTag('|gras|', '|/gras|', '%s')"></div>

			<div class="xtbutton italique" title="Italique" onclick="XTCode_lib_insertTag('|italique|', '|/italique|', '%s')"></div>
			<div class="xtbutton souligne" title="Souligné" onclick="XTCode_lib_insertTag('|souligne|', '|/souligne|', '%s')"></div>
			<div class="xtbutton barre" title="Barré" onclick="XTCode_lib_insertTag('|barre|', '|/barre|', '%s')"></div>
			
			<div class="xtlist">
				<div class="xttitle titre1" onclick="XTCode_lib_cache('titres_form2');" title="Titres"></div>
				<div class="xtvbox" id="titres_form2">
					<div class="titre1" title="Titre de niveau 1" onclick="XTCode_lib_insertTag('|titre1|', '|/titre1|', '%s'); cache('titres_form2');"></div>
					<div class="titre2" title="Titre de niveau 2" onclick="XTCode_lib_insertTag('|titre2|', '|/titre2|', '%s'); cache('titres_form2');"></div>
				</div>

			</div>
			
			<div class="xtlist">
				<div class="xttitle couleurs" onclick="XTCode_lib_cache('couleurs_form2');" title="Couleurs"></div>
				<div class="xtvbox" id="couleurs_form2">
					<div class="blanc" title="Blanc" onclick="XTCode_lib_insertTag('|couleur=&quot;blanc&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>
					<div class="bleu" title="Bleu" onclick="XTCode_lib_insertTag('|couleur=&quot;bleu&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>
					<div class="bleuc" title="Bleu clair" onclick="XTCode_lib_insertTag('|couleur=&quot;bleuc&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>
					<div class="bleuf" title="Bleu foncé" onclick="XTCode_lib_insertTag('|couleur=&quot;bleuf&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>
					<div class="gris" title="Gris" onclick="XTCode_lib_insertTag('|couleur=&quot;gris&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>

					<div class="jaune" title="Jaune" onclick="XTCode_lib_insertTag('|couleur=&quot;jaune&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>
					<div class="or" title="Or" onclick="XTCode_lib_insertTag('|couleur=&quot;or&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>
					<div class="orange" title="Orange" onclick="XTCode_lib_insertTag('|couleur=&quot;orange&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>
					<div class="rouge" title="Rouge" onclick="XTCode_lib_insertTag('|couleur=&quot;rouge&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>
					<div class="vertc" title="Vert clair" onclick="XTCode_lib_insertTag('|couleur=&quot;vertc&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>
					<div class="vertf" title="Vert foncé" onclick="XTCode_lib_insertTag('|couleur=&quot;vertf&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>
					<div class="violet" title="Violet" onclick="XTCode_lib_insertTag('|couleur=&quot;violet&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>
					<div class="choisir" title="Choisir une couleur" onclick="XTCode_lib_insertTag('|couleur=&quot;#&quot;|', '|/couleur|', '%s'); cache('couleurs_form2');"></div>
				</div>

			</div>
			
			<div class="xtlist">
				<div class="xttitle surligneurs" onclick="XTCode_lib_cache('surligne_form2');" title="Surligneurs"></div>
				<div class="xtvbox" id="surligne_form2">
					<div class="surligne_bleu" title="Surligneur bleu" onclick="XTCode_lib_insertTag('|surligne=&quot;bleu&quot;|', '|/surligne|', '%s'); cache('surligne_form2');"></div>
					<div class="surligne_jaune" title="Surligneur jaune" onclick="XTCode_lib_insertTag('|surligne=&quot;jaune&quot;|', '|/surligne|', '%s'); cache('surligne_form2');"></div>
					<div class="surligne_orange" title="Surligneur orange" onclick="XTCode_lib_insertTag('|surligne=&quot;orange&quot;|', '|/surligne|', '%s'); cache('surligne_form2');"></div>
					<div class="surligne_rose" title="Surligneur rose" onclick="XTCode_lib_insertTag('|surligne=&quot;rose&quot;|', '|/surligne|', '%s'); cache('surligne_form2');"></div>
					<div class="surligne_vert" title="Surligneur vert" onclick="XTCode_lib_insertTag('|surligne=&quot;vert&quot;|', '|/surligne|', '%s'); cache('surligne_form2');"></div>

				</div>
			</div>
			
			<div class="xtbutton cite" title="Citer" onclick=""></div>
			<div class="xtbutton lien" title="Lien" onclick=""></div>
			<div class="xtbutton image" title="Image" onclick=""></div>
			
			<div class="xtlist">
				<div class="xttitle tailles" onclick="XTCode_lib_cache('tailles_form2');" title="Taille"></div>
				<div class="xtvbox" id="tailles_form2">
					<div class="tgrand" title="Très grand" onclick="XTCode_lib_insertTag('|taille=&quot;4&quot;|', '|/taille|', '%s'); cache('tailles_form2');"></div>

					<div class="grand" title="Grand" onclick="XTCode_lib_insertTag('|taille=&quot;3&quot;|', '|/taille|', '%s'); cache('tailles_form2');"></div>
					<div class="petit" title="Petit" onclick="XTCode_lib_insertTag('|taille=&quot;2&quot;|', '|/taille|', '%s'); cache('tailles_form2');"></div>
					<div class="tpetit" title="Tout petit" onclick="XTCode_lib_insertTag('|taille=&quot;1&quot;|', '|/taille|', '%s'); cache('tailles_form2');"></div>
				</div>
			</div>
			
			<div class="xtbutton preview" title="Prévisualiser" onclick="XTCode_lib_cache('preview_form2')"></div>
			
		</div>
		
		<br />
		
		<div class="xtsmilebox">

			<img src="images/smileys/Tango_Emote_1.png" onclick="XTCode_lib_smilies(':)', '%s'); return(false);" />
			<img src="images/smileys/Tango_Emote_2.png" onclick="XTCode_lib_smilies('=)', '%s'); return(false);" />
			<img src="images/smileys/Tango_Emote_3.png" onclick="XTCode_lib_smilies(':D', '%s'); return(false);" />
			<img src="images/smileys/Tango_Emote_4.png" onclick="XTCode_lib_smilies(';)', '%s'); return(false);" />
			<img src="images/smileys/Tango_Emote_5.png" onclick="XTCode_lib_smilies(':-o', '%s'); return(false);" />

			<img src="images/smileys/Tango_Emote_6.png" onclick="XTCode_lib_smilies(':p', '%s'); return(false);" />
			<img src="images/smileys/Tango_Emote_7.png" onclick="XTCode_lib_smilies('(H)', '%s'); return(false);" />
			<img src="images/smileys/Tango_Emote_8.png" onclick="XTCode_lib_smilies(':@', '%s'); return(false);" />
			<img src="images/smileys/Tango_Emote_9.png" onclick="XTCode_lib_smilies(':S', '%s'); return(false);" />
			<img src="images/smileys/Tango_Emote_10.png" onclick="XTCode_lib_smilies(':$', '%s'); return(false);" />
			<img src="images/smileys/Tango_Emote_11.png" onclick="XTCode_lib_smilies(':(', '%s'); return(false);" />
			<img src="images/smileys/Tango_Emote_12.png" onclick="XTCode_lib_smilies(':\'(', '%s'); return(false);" />
		</div>		
		<div class="preview" id="preview_form2"></div>
	</fieldset>
