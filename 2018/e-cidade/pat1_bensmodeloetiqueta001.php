<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_bensmodeloetiqueta_classe.php");
require_once("classes/db_bensmodeloetiquetapadrao_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);

$clbensmodeloetiqueta       = new cl_bensmodeloetiqueta;
$clbensmodeloetiquetapadrao = new cl_bensmodeloetiquetapadrao;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
	$sXml   = null;
	$sDescr = null;
	$sql_erro = false;
	
	if(isset($_FILES["fileXml"]["name"]) && $_FILES["fileXml"]["name"] != "" ){
		
			$path = "tmp/tmpXML.xml";
			$lArquivo = move_uploaded_file($_FILES["fileXml"]["tmp_name"],$path);
			
			if($lArquivo){
				
			 $sXml = file_get_contents($path);
			 			 
			 $sDescr = $t71_descr;
			 if($sXml==false){
			 	$sql_erro = true;
        $clbensmodeloetiqueta->erro_msg  = _M('patrimonial.patrimonio.db_frmbensmodeloetiqueta.falha_ao_importar');
        $clbensmodeloetiqueta->erro_status = "0";
			 }
			}else{
				$sql_erro = true;
        $clbensmodeloetiqueta->erro_msg  = _M('patrimonial.patrimonio.db_frmbensmodeloetiqueta.falha_no_upload');
        $clbensmodeloetiqueta->erro_status = "0";
			}
			unlink($path);
		//}
	}else if($t72_sequencial != ""){

		$rsModeloPadrao = $clbensmodeloetiquetapadrao->sql_record($clbensmodeloetiquetapadrao->sql_query_file($t72_sequencial));
		if($clbensmodeloetiquetapadrao->erro_status == "0"){
			$sql_erro = true;
      $clbensmodeloetiqueta->erro_msg    = $clbensmodeloetiquetapadrao->erro_msg;
      $clbensmodeloetiqueta->erro_status = "0";
		}else {
			   
      $oModeloPadrao = db_utils::fieldsMemory($rsModeloPadrao,0);
      $sDescr  = $oModeloPadrao->t72_descr;
      $sXml    = $oModeloPadrao->t72_strxml;
      
		}
	}
	
  db_inicio_transacao();

  if(!$sql_erro){
    $clbensmodeloetiqueta->t71_descr  = $sDescr;
    $clbensmodeloetiqueta->t71_strxml = addslashes($sXml);
    $clbensmodeloetiqueta->incluir($t71_sequencial);
    if($clbensmodeloetiqueta->erro_status == "0"){
    	$sql_erro = true;
    }
  }
  
  db_fim_transacao($sql_erro);

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,classes/dbXmlEditor.js,
                classes/dbXmlFactory.js,widgets/dbtextField.widget.js");
  db_app::load("estilos.css,grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC>
	<?
	include("forms/db_frmbensmodeloetiqueta.php");
	?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","t71_descr",true,1,"t71_descr",true);
</script>
<?
if(isset($incluir)){
  if($clbensmodeloetiqueta->erro_status=="0"){
    $clbensmodeloetiqueta->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clbensmodeloetiqueta->erro_campo!=""){
      echo "<script> document.form1.".$clbensmodeloetiqueta->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbensmodeloetiqueta->erro_campo.".focus();</script>";
    }
  }else{
  	$chavepesquisa = $clbensmodeloetiqueta->t71_sequencial;
    db_msgbox($clbensmodeloetiqueta->erro_msg);
    echo "<script> document.location.href = 'pat1_bensmodeloetiqueta002.php?chavepesquisa=$chavepesquisa'</script>  ";
    
  }
}
?>