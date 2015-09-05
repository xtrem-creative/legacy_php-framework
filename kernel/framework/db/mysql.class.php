<?php

load( 'db/constants', INC_LOAD );

class Bdd
{
    static $pdoS = false;
    public $pdo = '';
    static $nbRequetes = 0;
    
    public function __construct( $infos = false )
    {
        $displayErrors = false;
        if( !self::$pdoS ) {
            if( empty( $infos ) ) {
                require( ROOT . 'config/bdd.php' );
                $infos = $BDD['mysql'];
            }
            $displayErrors = @$infos['displayErrors'];
            try {
           	    self::$pdoS = new PDO('mysql:host=' . $infos['host'] . ';dbname=' . $infos['database'] . '', $infos['login'], $infos['password'], array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'' ) );   
           	    self::$pdoS->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
           	    $this->wake_up();
           	    return true;
       	    }
       	    catch( PDOException $erreur ) {
       	        $error = new error();
       	        if( $displayErrors )
                    $error->addError( 'Error : '.$erreur->getMessage(), ERROR_PAGE );
                else 
                    $error->addError( 'We can\'t display the page you asked for.', ERROR_PAGE );
                return false;
       	    }
   	    }
       	$this->wake_up();
    }
    
    public function wake_up()
    {
        $this->pdo = self::$pdoS;
    }
    
    public function query( $requete, $params = false, $error = true )
    {
        $bdd = $this->pdo;
        try {
            $req = $bdd->prepare( $requete );
            if( $params && !is_array( $params ) )
                $params = array( $params );
            if( $params )
                $req->execute( $params );
            else
                $req->execute();
            self::$nbRequetes += 1;
        } catch (PDOException $e) {
            if( $error )
                exit( $e );
            else
                $e->getMessage();
        }
        return $req;
    }

    public function fetch( $req = false, $mode = PDO::FETCH_ASSOC )
    {
        $bdd = $this->pdo;
		return $req->fetch( $mode );
    }

    public function execute( $req )
    {
        $bdd = $this->pdo;
        return $bdd->exec( $req );
    }

    public function count_sql( $table, $clauses = '', $params = false )
    {
        $bdd = $this->pdo;
        $req = $this->query( 'SELECT COUNT(*) AS count FROM '.$table.' '.$clauses, $params );
        $numCol = $this->fetch( $req );
        $req->closeCursor();
        return $numCol['count'];
    }

    public function quote( $var )
    {
        $bdd = $this->pdo;
        return $bdd->quote( $var );
    }

    public function requete( $requete, $params = false )
    {
        $req = $this->query( $requete, $params );
        return $this->fetch( $req );
    }

    public function last_insert_id()
    {
        $bdd = $this->pdo;
        return $bdd->lastInsertId();
    }
    
    public function extract_file( $file )
    {
        if( is_file( ROOT . $file ) )
        {
            $contentSQL = file_get_contents( ROOT . $file );
            $this->query( $contentSQL );
            return true;
        }
        else
            return false;
    }
    
    
    public function select( $table, $champs, $conditions, $order, $limit )
    {
	}
	
	public function update( $table, $champs, $conditions, $order, $limit )
    {
	}
	
	public function delete( $table, $champs, $conditions, $order, $limit )
    {
	}
	
	public function insert( $table, $champs, $valeurs )
    {
	}
}
?>
