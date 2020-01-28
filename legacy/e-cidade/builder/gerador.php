<?php
require_once "../../libs/db_conn.php";
require_once "../../libs/db_utils.php";

$DB_SERVIDOR = 'localhost';
$DB_BASE     = 'riopardo';
$DB_PORTA    = '5432';
$DB_USUARIO  = 'postgres';
$DB_SENHA    = '';

if(!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
  echo "Contate com Administrador do Sistema! (Conexão Inválida.)\n";
  exit;
}

$parameters = array (
                      'b:'  => 'BuildClass:', 
                      'ba:' => 'BuildAllClass:', 
                      'c:'  => 'class-name:', 
                      'i'   => 'ignore', 
                      'r'   => 'replace' 
                    );

$options = getopt ( implode ( '', array_keys ( $parameters ) ), $parameters );

echo "Debug parametros : \n";
var_dump ( $options );

foreach ( $options as $sChave => $sParametro ) {
	
	if ($sParametro) {
	  echo " {$sChave} -- {$sParametro} \n";
  	$o = new $sChave ( $sParametro );
	}

}

class BuildClass {
	
	public $sSource = "";
		
	public function __construct($aParameters) {
		
 	  $this->sSource  = "<?php\n"; 
 	  $this->sSource .= "require_once 'std/DAOBasica.php';\n";
    $this->sSource .= "class cl_%tableName% extends DAOBasica {\n";
    $this->sSource .= "  public function __construct(){\n";
    $this->sSource .= "     parent::__construct(str_replace(\"cl_\",\"\",__CLASS__));\n";
    $this->sSource .= "  }\n";
    $this->sSource .= "  %methods%\n";
	  $this->sSource .= "}\n";
	  		
		$dir = "../../dd/tabelas/";
		// 
		if (is_dir ( $dir )) {
			if ($dh = opendir ( $dir )) {
				while ( ($file = readdir ( $dh )) !== false ) {
					
					$sSource = $this->sSource;
					
					//echo $file."\n";
					$aArquivo       = explode(".",$file);
					$sTableName     = $aArquivo[0]; 
					$sPathClassFile = "../../classes/db_".$sTableName."_classe.php";
					
					if ($sTableName != "arrecad") {
						//continue;	
					}
					
					$sSql  = " select codigoclass      ";
					$sSql .= "   from configuracoes.db_sysclasses me ";
					$sSql .= "        inner join configuracoes.db_sysarquivo a on a.codarq = me.codarq "; 
					$sSql .= "  where nomearq = '{$sTableName}' ";
					
					$rsExternalMethods = pg_query($sSql);
					$iTotalMetodos     = pg_num_rows($rsExternalMethods);
					
					$sExternalMethods  = "";
					
					for ($i=0; $i < $iTotalMetodos; $i++) {
						
						$oExternalMethods  = db_utils::fieldsMemory($rsExternalMethods,$i);
						$sExternalMethods .= $oExternalMethods->codigoclass."\n\n  ";
					}
					
					// echo "$sTableName : $sExternalMethods \n \n";
					echo "Processando tabela : $sTableName \n";
					
					$sSource = str_replace("%tableName%",$sTableName,       $sSource);
					$sSource = str_replace("%methods%",  $sExternalMethods, $sSource);
					
					file_put_contents($sPathClassFile,$sSource);
					// echo file_get_contents($dir.$file)."\n";					
		      //$rsArq = fopen("../dd/tabelas/{$oTabela->nomearq}.dd.xml", "a+");			
					
				}
				closedir ( $dh );
			}
		}
	
	}

}

/*
exit ();

foreach ( $options as $valor ) {
	
	if (substr ( $valor, 0, 1 ) == "-") {
		$sIndex = trim ( $valor );
	}
	$aParametros [$sIndex] [] = $valor;
}

print_r ( $aParametros );
exit ();

echo "\n\n";

$aParametros = array ();


//  For percorrendo os argumentos passados por parametro e /
//    agrupando
 
foreach ( $argv as $valor ) {
	 
	// ignoramos o nome do arquivo
	if ($valor == $argv [0]) {
		continue;
	}
	//
	// se o parametro comecar com -- agrupamos, presumindo
	//   que todos parametros que comecem com -- serao nome de classe
	if (substr ( $valor, 0, 1 ) == "-") {
		$sIndex = trim ( $valor );
	}
	$aParametros [$sIndex] [] = $valor;
}

print_r ( $aParametros );

foreach ( $aParametros as $valores ) {
	
	print_r ( $valores );

}
*/
?>