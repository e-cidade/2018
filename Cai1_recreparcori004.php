<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_recreparcori_classe.php");
include("classes/db_recreparcdest_classe.php");
require_once("classes/db_recreparcarretipo_classe.php");
$oPost = db_utils::postMemory($_POST);

$clrecreparcori  = new cl_recreparcori;
$clrecreparcdest = new cl_recreparcdest;
$clrecreparcarretipo  = new cl_recreparcarretipo();

$db_opcao = 1;
$db_botao = true;

if ( isset($oPost->incluir) ) {
	
	$lSqlErro = false;
		
	db_inicio_transacao();
	// select * from recreparcori where k70_recori = 1 and k70_vezesfim >= 11 and k70_vezesini <= 12 ;
	$sWhere  = "     k70_recori    = {$oPost->k70_recori}   ";
  $sWhere .= " and k70_vezesfim >= {$oPost->k70_vezesini} ";
  $sWhere .= " and k70_vezesini <= {$oPost->k70_vezesfim} ";
	
	$clrecreparcori->sql_record($clrecreparcori->sql_query_file(null, "*", null, $sWhere));

  if ( $clrecreparcori->numrows == 0 ) {

  	$lSqlErro = false;
  	/*
    $clrecreparcori->k70_recori   = $oPost->k70_recori;
		$clrecreparcori->k70_vezesini = $oPost->k70_vezesini;
    $clrecreparcori->k70_vezesfim = $oPost->k70_vezesfim;
	  $clrecreparcori->incluir(null);

		if ( $clrecreparcori->erro_status == "0" ) {
			$lSqlErro = true;
		}

    $sMsgErro = $clrecreparcori->erro_msg;
	
		if ( !$lSqlErro ) {
   		
			$clrecreparcdest->k71_codigo  = $clrecreparcori->k70_codigo;
			$clrecreparcdest->k71_recdest = $oPost->k71_recdest;
			$clrecreparcdest->incluir($clrecreparcori->k70_codigo);
		
			if ( $clrecreparcdest->erro_status == "0") {
				$lSqlErro = true;
			}
	
	    $sMsgErro = $clrecreparcdest->erro_msg;			
		
		}
		
		db_fim_transacao($lSqlErro);
		*/
  	  	
  } else {
		
    $rsRecReparcArretipo = $clrecreparcarretipo->sql_record($clrecreparcarretipo->sql_query_recreparcori(null, 
                                                                                                         "*", 
                                                                                                         null, 
                                                                                                         $sWhere)
                                                                                                         );
    $lSqlErro = false;
    if ($clrecreparcarretipo->numrows > 0) {
      
      for ($iInd = 0 ; $iInd < $clrecreparcarretipo->numrows; $iInd++) {
        
        $iK72_sequencial = db_utils::fieldsMemory($rsRecReparcArretipo,$iInd)->k72_sequencial;
        if (trim($iK72_sequencial) == "" && $iK72_sequencial == null) {
          
          $lSqlErro = true;
        }
      }
            
    }

    if ($lSqlErro) {
      $sMsgErro = "Já existe um intervalo númerico fornecido entre a parcela inicial e a final cadastrada para esta receita !";
      $db_botao = true;
    }
  	
		//$lSqlErro = true;
		//$sMsgErro = "Já existe um intervalo númerico fornecido entre a parcela inicial e a final cadastrada para esta receita !";
	
  }
  
  if (!$lSqlErro) {
  	
    $clrecreparcori->k70_recori   = $oPost->k70_recori;
    $clrecreparcori->k70_vezesini = $oPost->k70_vezesini;
    $clrecreparcori->k70_vezesfim = $oPost->k70_vezesfim;
    $clrecreparcori->incluir(null);

    if ( $clrecreparcori->erro_status == "0" ) {
      $lSqlErro = true;
    }

    $sMsgErro = $clrecreparcori->erro_msg;
  
    if ( !$lSqlErro ) {
      
      $clrecreparcdest->k71_codigo  = $clrecreparcori->k70_codigo;
      $clrecreparcdest->k71_recdest = $oPost->k71_recdest;
      $clrecreparcdest->incluir($clrecreparcori->k70_codigo);
    
      if ( $clrecreparcdest->erro_status == "0") {
        $lSqlErro = true;
      }
  
      $sMsgErro = $clrecreparcdest->erro_msg;     
    }
  }
	db_fim_transacao($lSqlErro);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <center>
	<?
  	include("forms/db_frmrecreparcori.php");
	?>
  </center>
</body>
</html>
<script type="text/javascript">
  js_tabulacaoforms("form1","k70_recori",true,1,"k70_recori",true);
</script>
<?
if ( isset($oPost->incluir) ) {
	
  if( $lSqlErro ){
    db_msgbox($sMsgErro);
  }else{
    //$clrecreparcori->erro(true,true);
    db_msgbox($clrecreparcori->erro_msg);
    //db_redireciona("Cai1_recreparcori002.php?liberaaba=true&chavepesquisa={$clrecreparcarretipo->k70_sequencial}");
    echo "
	  <script>
	      function js_db_libera(){
	         parent.document.formaba.tipodebito.disabled = false;
	         
	         //parent.iframe_tipodebito.location.href='cai1_reparcoritipodebito001.php?k72_codigo=".@$k70_codigo."';
	         parent.location.href='Cai1_recreparcori002.php?chave_pesquisa=".$clrecreparcori->k70_codigo."';
	         
	     ";
        /*
	      if(isset($oGet->liberaaba)){
	         echo "  parent.mo_camada('modcarnepadraotipo');";
	      }
        */
 echo"}\n
    js_db_libera();
  </script>\n
 ";
  }
}
?>