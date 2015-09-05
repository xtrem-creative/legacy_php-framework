<?php
class Zip
{
	private $zip = '';
	private $nameZip = '';
	
	public function __construct( $zipDir )
	{
		$this->zip = $zipDir;
		$this->nameZip = basename( $zipDir, '.zip' );
	}
	
	public function extract( $directory )
	{
		$filesZip = $this->browseZip();
		foreach( $filesZip AS $file )
		{
			$this->extractFiles( $file[0], $file[1], $directory );
		}
		return true;
	}
	
	public function extractFile( $fileName )
	{
		$zip = new ZipArchive;
		$zip->open( $this->zip );
		$contentFile = $zip->getFromName( $fileName );
		$zip->close();
		return $contentFile;
	}
	
	
	public function extractFiles( $elementZip, $elementName, $directory )
	{
		$fileZip = zip_entry_read( $elementZip );
		$newDir = $directory . $elementName;
		$isDirInfos = strrpos( $elementName, '/' );
		$lastPos = strlen( $elementName ) - 1;
		if( $lastPos == $isDirInfos )
		{
			if( !is_dir( $newDir ) )
			{
				mkdir( $newDir );
				chmod( $newDir, 0777 );
			}
		}
		else
		{
			$newFile = fopen( $directory . $elementName, 'w+' );
			fwrite( $newFile, $fileZip );
			fclose( $newFile );
			@chmod( $newDir, 0777 );
		}
		return true;
	}
	
	private function browseZip()
	{
		$zip = zip_open( $this->zip );
		$files = array();
		$this->nameZip = NULL;
		while( $elementZip = zip_read( $zip ) )
	    {
			$elementName = zip_entry_name( $elementZip );
			$files[] = array( $elementZip, $elementName );
			zip_entry_close( $elementZip );
		}
		zip_close( $zip );
		return $files;
	}
	
	public function listFiles()
	{
		$filesZip = $this->browseZip();
		$filesToReturn = array();
		foreach( $filesZip AS $file )
			$filesToReturn[] = $file[1];
		return $filesToReturn;
	}
	
	public function getNameZip()
	{
		return $this->nameZip;
	}
}
?>
