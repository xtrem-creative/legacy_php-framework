<?php
function get_ini( $dir, $parse = true )
{
    if( file_exists( ROOT . $dir ) )
        if( !$parse )
            require_once( ROOT . $dir );
        else
            return parse_ini_file( ROOT . $dir, true );
    else
        return false;
}

function est_en_ligne()
{
	//TODO !
	return false;
}

function parse_membre( $p )
{
	//TODO !
	return $p;
}

function get_ip() 
{
	return isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
}

function echoa( $array )
{
    echo '<pre>';
    print_r( $array );
    echo '</pre>';
}

function _hash( $string, $prefixe = HASH_PREFIXE, $suffixe = HASH_SUFFIXE )
{
    return md5( $prefixe . trim( $string ) . $suffixe );
}

function check_email( $string )
{
    $len = strlen( $string );
    if( $len > EMAIL_MAX_LENGTH ) return false;
    return preg_match( '`([a-zA-Z0-9_-]*)@([a-zA-Z0-9_-]*)\.[a-z]{2,4}`isU', $string );
}

function check_pseudo( $string )
{
    $len = strlen( $string );
    if( $len > EMAIL_MAX_LENGTH ) return false;
    return true;
}


function parcourt_arborescence( $chemin, $toDisplay = true, $returnArray = false, $recursif = true )
{
	$arrayToReturn = array();
	$display = '<ul>'."\n";
	$dossier = opendir( $chemin );
	while( $element = @readdir( $dossier ) ) 
	{
		if( strpos( $element, '.' ) === 0 ) continue;
		if( is_dir( $chemin . '/' . $element ) )
		{
			$display .= '<li>' . $chemin . "\n";
			$arrayToReturn[$chemin . '/' . $element] = ( $recursif === true ? parcourt_arborescence( $chemin . '/' . $element, false, true ) : $element );
			$display .= ( $recursif === true ? parcourt_arborescence( $chemin . '/' . $element, false ) : $element );
			$display .= '</li>'."\n";
		}
		else 
		{
			$arrayToReturn[] = $element;
			$display .= '<li>'.$element.'</li>'."\n";
		}
	}
	$display .= '</ul>'."\n";
	closedir( $dossier );
	if( $returnArray === true ) return $arrayToReturn;
	if( $toDisplay === true ) echo $display;
	return $display;
}


function rm($fichier_ou_dossier, $delDir = true )
{ // si le paramètre est une chaîne de caractère...
    if (is_string($fichier_ou_dossier))
    { // si le paramètre est un fichier...
        if (is_file($fichier_ou_dossier))
        { // on efface le fichier et renvoit le resultat
            return unlink($fichier_ou_dossier);
        }
        else
        {
            // si c'est un dossier
            if (is_dir($fichier_ou_dossier) )
            {
                $suppr_fichier = rm("$fichier_ou_dossier/*", $delDir);
                // si les fichiers n'ont pas été supprimés
                if (!$suppr_fichier)
					return false;

                // supprime le dossier après être vidé ^^
                if( $delDir == true )
					return rmdir($fichier_ou_dossier);
            }

            else
            { // on recherche les fichiers vérifiant un masque (*.html)
                $fichiers_masque = glob($fichier_ou_dossier);
                // si aucun fichier...
                if ($fichiers_masque === false)
                {
                    // déclanche une erreur utilisateur
                    trigger_error(sprintf('Aucun fichier correspondant au masque suivant: %s', $fichier_ou_dossier), E_USER_WARNING);
                    return false;
                }
                // on rappel la fonction rm() pour chaque fichier afin de //les supprimer un par un
                $rslt = array();
                foreach( $fichiers_masque AS $f )
                {
					$v = rm( $f, $delDir );
					$rslt[] = $v;
				}

                // si un false est trouvé il y a eu une erreur lors de la //suppression
                if (in_array(false, $rslt))
                {
                    return false;
                }
            }
        }
    }
    else
    {
        // s'il s'agit un tableau contenant les noms des fichiers...
        if (is_array($fichier_ou_dossier))
        {
            // on rappel la fonction rm() pour chaque fichier afin de //les supprimer un par un
			$rslt = array();
			foreach( $fichier_ou_dossier AS $f )
			{
				$v = rm( $f, $delDir );
				$rslt[] = $v;
			}
            // si un false est trouvé il y a eu une erreur lors de la //suppression
            if (in_array(false, $rslt))
            {
                return false;
            }
        }
        else
        {
            // déclanche une erreur utilisateur
            trigger_error('Le paramètre passé en argument n\'est pas valide !', E_USER_ERROR);
            return false;
        }
    }
    return true;
}


function date_avancee( $timestamp )
{
	if( preg_match( '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $timestamp ) )
		$timestamp = strtotime( $timestamp );
	if( is_numeric($timestamp))
	{
		$now = time();
		$minuitNow = mktime(0, 0, 0, date('m', $now), date('d', $now), date('Y', $now));
		$limiteSecondes = $timestamp + 60;
		$limiteMinutes = $timestamp + 3600;
		$limiteHeures = $timestamp + 82800;
		$minuitHier = $minuitNow - 82800;
		$minuitAvantHier = $minuitNow - 2*82800;	
		
		if($timestamp == 0)
		{
			return 'jamais';
		}
		/* Futur */
		else if( $timestamp > $now && $timestamp < $now + 60 )//Entre 1 et 59 secondes
		{
			if( $timestamp == $now + 1 )
			{
				return 'dans une seconde';
			}
			else
			{
				$x = $timestamp - $now;
				return 'dans '.$x.' secondes';
			}
		}
		
		else if( $timestamp >= $now + 60 && $timestamp < $now + 3600 )//Entre 60 secondes et 59 minutes
		{
			if( $timestamp >= $now + 60 && $timestamp < $now + 120 )//Entre une minute et une minute 59 secondes
			{
				if( $timestamp == $now + 60 )
				{
					return 'dans une minute';
				}
				else
				{
					$y = $timestamp - $now - 60;
					if( $y == 1 )
					{
						return 'dans une minute et une seconde';
					}
					else
					{
						return 'dans une minute et '.$y.' secondes';
					}
				}
			}
			else//Entre deux minutes et 59 minutes 
			{
				$x = floor( ($timestamp - $now) / 60 );
					
				return 'dans '.$x.' minutes';
			}
		}
		
		//Entre une heure et 23 heures 59 minutes
		else if( $timestamp >= $now + 3600 && $timestamp < $now + 86400 )
		{
			//Entre une heure et une heure 59 minutes
			if( $timestamp >= $now + 3600 && $timestamp < $now + 7200 )
			{
				if( $timestamp == $now + 3600 )
				{
				    return 'dans une heure';
				}
				else
				{
				    $s = $timestamp - $now - 3600;
					if( $s == 1 )
					{
						return 'dans une heure et une seconde';
					}
					elseif( $s < 60 )
					{
					    return 'dans une heure et '.$s.' secondes';
					}
					elseif( $s == 61 )
					{
					    return 'dans une heure, une minute et une seconde';
					}
					elseif( $s == 60 )
					{
					    return 'dans une heure et une minute';
					}
					elseif( $s < 120 && $s > 61 )
					{
					    return 'dans une heure, une minute et ' . ( $s - 60 ) . ' secondes';
					}
					else
					{
					    $m = floor( $s / 60 );
					    $s2 = $s - $m * 60;
					    if( $s2 == 1 )
					    return 'dans une heure, '.$m.' minutes et une seconde';
					    elseif( $s2 == 0 )
					    return 'dans une heure et '.$m.' minutes';
					    else
						return 'dans une heure, '.$m.' minutes et '.$s2.' secondes';
					}
				}
			}
			//Entre 2 heures et 23 heures 59 minutes
			else
			{
			    $s = $timestamp - $now;
			    $h = floor( ( $timestamp - $now ) / 3600 );
			    $m = floor( ( ( $s - $h * 3600 ) / 60 ) );
			    $s = $s % 60;
				if( $m == 0 && $s == 0 )
				    return 'dans '.$h.' heures';
				elseif( $m == 1 && $s == 0 )
				    return 'dans '.$h.' heures et une minute';
				elseif( $m == 1 && $s == 1 )
				    return 'dans '.$h.' heures, une minute et une seconde';
				elseif( $m == 0 && $s == 1 )
				    return 'dans '.$h.' heures et une seconde';
			    elseif( $m == 0 )
			        return 'dans '.$h.' heures et '.$s.' secondes';
		        elseif( $s == 0 )
		            return 'dans '.$h.' heures et '.$m.' minutes';
	            elseif( $s == 1 )
	                return 'dans '.$h.' heures, '.$m.' minutes et une seconde';
		        elseif( $m == 1 )
		            return 'dans '.$h.' heures, une minute et '.$s.' secondes';
		        else
		            return 'dans '.$h.' heures, '.$m.' minutes et '.$s.' secondes';
			}
		}
		elseif( $timestamp > $now )
		{
		    return 'le '.date('d/m/Y', $timestamp).' &agrave; '.date('H \h i', $timestamp);
		}
		
		/* Passé */
		else if( $now <= $limiteSecondes && $timestamp > $now - 1 )
		{
			if($now - 1 == $timestamp)
				return 'il y a 1 seconde';
			if($now == $timestamp)
				return 'maintenant';		
			else
				return 'il y a '. preg_replace('`^[0]*`', '', date('s', $now - $timestamp)) .' secondes';
		}
			
		else if($now < $limiteMinutes)
		{
			if($now - 119 <= $timestamp)
				return 'il y a 1 minute';
							
			else
				return 'il y a '. preg_replace('`^[0]*`', '', date('i', $now - $timestamp)) .' minutes';
		}
					
		else if($timestamp >= $minuitNow)
		{
			if($now - 3599 <= $timestamp)
				return 'il y a '. preg_replace('`^[0]*`', '', date('H \h i', $now - $timestamp));
			else
				return 'aujourd\'hui, &agrave; '.date('H:i:s', $timestamp);
		}
		
		else if( $minuitHier <= $timestamp && $timestamp < $minuitNow)
			return 'hier &agrave; '.date('H \h i', $timestamp);
		
		else if( $minuitAvantHier <= $timestamp && $timestamp < $minuitHier)
			return 'avant-hier &agrave; '.date('H \h i', $timestamp);
		
		else
		{
			return 'le '.date('d/m/Y', $timestamp).' &agrave; '.date('H \h i', $timestamp);
		}

	}
	else
	{
		return 'la valeur d\'entrée n\'est pas un timestamp !';
	}
}


function url_transform($url)
{
	$caracteres_speciaux = array('<','>',';','!','?',':',')','(','[',']','+','\\','...',',','=','^','«','»');
	$url = str_replace($caracteres_speciaux, '', $url);
	$url = rtrim($url);
	$changer_en_tirets = array(' & ',' ', '.', '\'', '_', '/');
	$url = strtolower(str_replace($changer_en_tirets, "-", $url));
	$trouver = explode( '-', "À-Á-Â-Ã-Ä-Å-à-á-â-ã-ä-å-Ò-Ó-Ô-Õ-Ö-Ø-ò-ó-ô-õ-ö-ø-È-É-Ê-Ë-è-é-ê-ë-Ç-ç-Ì-Í-Î-Ï-ì-í-î-ï-Ù-Ú-Û-Ü-ù-ú-û-ü-ÿ-Ñ-ñ" );
	$remplacerpar = explode( '-', "A-A-A-A-A-A-a-a-a-a-a-a-O-O-O-O-O-O-o-o-o-o-o-o-E-E-E-E-e-e-e-e-C-c-I-I-I-I-i-i-i-i-U-U-U-U-u-u-u-u-y-N-n" );
	$url = str_replace($trouver,$remplacerpar,$url);
	$url = rtrim($url, '-');
	return $url;
}

?>
