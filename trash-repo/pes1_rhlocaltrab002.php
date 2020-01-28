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

require("libs/db_utils.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
require("std/db_stdClass.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("classes/db_rhlocaltrab_classe.php");
include("classes/db_rhlocaltrabcustoplano_classe.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhlocaltrab = new cl_rhlocaltrab;
$cldb_estrut = new cl_db_estrut;
$db_opcao = 22;
$db_botao = false;
$aParamKeys  = array(
                   "cc09_anousu" => db_getsession("DB_anousu"),
                   "cc09_instit" => db_getsession("DB_instit"),
                   );
$aParametrosCustos   = db_stdClass::getParametro("parcustos",$aParamKeys);
$iTipoControleCustos = 0; 
if (count($aParametrosCustos) > 0) {
  $iTipoControleCustos = $aParametrosCustos[0]->cc09_tipocontrole;
}
if(isset($alterar)) {
  
  $lSqlErro = false;
  db_inicio_transacao();
  $db_opcao = 2;
  $clrhlocaltrab->rh55_instit = db_getsession("DB_instit");
  $clrhlocaltrab->alterar($rh55_codigo,db_getsession("DB_instit"));
  if ($clrhlocaltrab->erro_status == 0) {
    $lSqlErro = true;
  }
  if (!$lSqlErro) {
    
    $oDaoRhlocalTrabCusto = new cl_rhlocaltrabcustoplano();
    $sWhere  = "rh86_rhlocaltrab = {$rh55_codigo} and rh86_instit = {$clrhlocaltrab->rh55_instit}";
    $oDaoRhlocalTrabCusto->excluir(null, $sWhere);
    if ($oDaoRhlocalTrabCusto->erro_status == 0) {
       
      $lSqlErro = true;
      $clrhlocaltrab->erro_msg    = $oDaoRhlocalTrabCusto->erro_msg;
      $clrhlocaltrab->erro_status = 0;
        
    }
  }
  if (!$lSqlErro && $rh86_criteriorateio != "") {
    
    $oDaoRhpesLocalCusto                      = new cl_rhlocaltrabcustoplano;
    $oDaoRhpesLocalCusto->rh86_rhlocaltrab    = $clrhlocaltrab->rh55_codigo;
    $oDaoRhpesLocalCusto->rh86_instit         = $clrhlocaltrab->rh55_instit;
    $oDaoRhpesLocalCusto->rh86_criteriorateio = $rh86_criteriorateio;
    $oDaoRhpesLocalCusto->incluir(null);
    if ($oDaoRhpesLocalCusto->erro_status == 0) {
      
      $lSqlErro = true;
      $clrhlocaltrab->erro_campo  = "rh86_criteriorateio";
      $clrhlocaltrab->erro_status = 0;  
      $clrhlocaltrab->erro_msg    = $oDaoRhpesLocalCusto->erro_msg;
        
    }
  }
  db_fim_transacao($lSqlErro);
  
} else if(isset($chavepesquisa)) {
  
   $db_opcao = 2;
   $result = $clrhlocaltrab->sql_record($clrhlocaltrab->sql_query_centro_custo($chavepesquisa,db_getsession("DB_instit")));
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frmrhlocaltrab.php");
      ?>
      </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clrhlocaltrab->erro_status=="0"){
    $clrhlocaltrab->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clrhlocaltrab->erro_campo!=""){
      echo "<script> document.form1.".$clrhlocaltrab->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhlocaltrab->erro_campo.".focus();</script>";
    }
  }else{
    $clrhlocaltrab->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","rh55_descr",true,1,"rh55_descr",true);
</script>
<?
if(isset($sem_parametro_configurado)){
  db_msgbox($erro_msg);
}
?>