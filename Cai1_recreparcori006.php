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
$oGet  = db_utils::postMemory($_GET);

$clrecreparcori  = new cl_recreparcori();
$clrecreparcdest = new cl_recreparcdest();
$clrecreparcarretipo  = new cl_recreparcarretipo;

$db_opcao = 33;
$db_botao = false;

if ( isset($oPost->excluir) ) {
	
	$lSqlErro = false;

  db_inicio_transacao();
  $db_opcao = 3;
 
  //Veriicar se existe tipo debito cadastrado se sim avisar e mandar excluir antes
  $sql =   $clrecreparcarretipo->sql_query_recreparcori(null,
                                                          "*", 
                                                         null,
                                                         "k70_codigo=".$k70_codigo." and k72_sequencial is null and k70_codigo = k72_codigo");
  
              
  $result = $clrecreparcarretipo->sql_record($sql); 
  if ($clrecreparcarretipo->numrows > 0) {

  	$lSqlErro = true;
  	$clrecreparcori->erro_status = 0;
  	$clrecreparcori->erro_msg = "usuario:\\n\\nExiste um ou mais tipo de debito vinculado! \\nNão pode ser excluído! Exclua os tipos de débitos primeiro!\\n\\nadministrador:\\n\\n";
  	$db_botao = true;
  }
  
  if (!$lSqlErro) {
		$clrecreparcdest->excluir($oPost->k70_codigo);
	  $clrecreparcori->excluir($oPost->k70_codigo);
	  
	  	// tratamento de erros 
			if ( $clrecreparcori->erro_status == "0") {
				$lSqlErro = true;
	      $sMsgErro = $clrecreparcori->erro_msg;
			}
			if( $clrecreparcdest->erro_status == "0" ) {
				$lSqlErro = true;
	      $sMsgErro = $clrecreparcdest->erro_msg;
			}
   }
   
   db_fim_transacao($lSqlErro);
	
	}  else if( isset($oGet->chavepesquisa) ) {
	
   $db_opcao = 3;

   $sCampos  = "recreparcori.*, 					 			  "; 
   $sCampos .= "recreparcdest.*,					 			  "; 
   $sCampos .= "tabrec.k02_descr as k02_descrori, "; 	 
   $sCampos .= "a.k02_descr 		 as k02_descrdest "; 	 	 
		
   $result = $clrecreparcori->sql_record($clrecreparcori->sql_query_dadosrec($oGet->chavepesquisa,$sCampos)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
	 
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
<?
if ( isset($oPost->excluir) ) {
  if ( $clrecreparcori->erro_status=="0" ) {
    $clrecreparcori->erro(true,false);
  } else {
    $clrecreparcori->erro(true,true);
  }
}

if ( $db_opcao==33 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
if(isset($oGet->chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.tipodebito.disabled = false;
         
         top.corpo.iframe_tipodebito.location.href='cai1_reparcoritipodebito001.php?k72_codigo=".@$k70_codigo."';
         
     ";
      if(isset($oGet->liberaaba)){
         echo "  parent.mo_camada('tipodebito');";
      }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>