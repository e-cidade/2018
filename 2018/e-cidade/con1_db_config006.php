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

require("libs/db_stdlib.php");
require ("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_db_config_classe.php");
include("dbforms/db_funcoes.php");
include ("libs/db_app.utils.php");
require("classes/db_db_configarquivos_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cldb_config         = new cl_db_config;
$cldb_configarquivos = new cl_db_configarquivos;
$db_botao            = false;
$db_opcao            = 33;

if (isset($excluir)) {
	
  $lErro = false;
  
  db_inicio_transacao();
  
  $db_opcao = 3;
  
  $sWhere = "db38_instit = $codigo";
  
  $rsDbConfigArquivos = $cldb_configarquivos->sql_record($cldb_configarquivos->sql_query_file(null,"*",null,$sWhere));
  $iNumRows           = $cldb_configarquivos->numrows;

  for($iInd=0;$iInd < $iNumRows; $iInd++){

    $oConfigArquivos = db_utils::fieldsMemory($rsDbConfigArquivos,$iInd);
    
    if (!empty($oConfigArquivos->db38_arquivo)) {
      db_geraArquivoOid(null, $oConfigArquivos->db38_arquivo, 3, $conn);
    }

    $cldb_configarquivos->excluir($oConfigArquivos->db38_sequencial);   
    if ($cldb_configarquivos->erro_status == "0") {
      $lErro= true;
      $cldb_config->erro_msg = $cldb_configarquivos->erro_msg;
       
    }     
  
  }
  
  $rscldb_config = $cldb_config->sql_record($cldb_config->sql_query_file($codigo, "db21_imgmarcadagua"));
  
  $oDbConfig     = db_utils::fieldsMemory($rscldb_config, 0); 
  if (!empty($oDbConfig->db21_imgmarcadagua)) {
    db_geraArquivoOid(null, $oDbConfig->db21_imgmarcadagua, 3, $conn);
  }
    
  if (!$lErro) {
     
  	$cldb_config->excluir($codigo);
    if ($cldb_config->erro_status == "0") {
      $lErro = true;
      $strMsg = strstr($cldb_config->erro_msg,"viola restrição de chave estrangeira"); 
      if($strMsg != false){
        $cldb_config->erro_msg  = "\\n\\nusuário:\\n\\nInstituição não excluída !!!\\n\\n ";
        $cldb_config->erro_msg .= "Verifique o código da instituição ainda é referenciado em documentos\\n ou "; 
        $cldb_config->erro_msg .= "configurações de outros parâmetros\\n\\n";
      }
    }
  }
  
  db_fim_transacao($lErro);
} else if(isset($chavepesquisa)) {
	
   $db_opcao = 3;
   $db_botao = true;
   $result   = $cldb_config->sql_record($cldb_config->sql_query($chavepesquisa)); 
   
   db_fieldsmemory($result,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
  db_app::load('strings.js,scripts.js,datagrid.widget.js,prototype.js');
  db_app::load('estilos.css,grid.style.css');
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 15px;">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
  <?
  include("forms/db_frmdb_config.php");
  ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($cldb_config->erro_status=="0"){
    $cldb_config->erro(true,false);
  }else{
    $cldb_config->erro(true,true);
  }
}

if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>