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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_orcunidade_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clorcunidade = new cl_orcunidade;
$db_opcao = 1;
$db_botao = true;
$sMsg     = ""; 
if(isset($incluir)){
  db_inicio_transacao();
  $iAnoUsu       = db_getsession("DB_anousu");
  $sSqlUltimoAno = "select coalesce(max(o41_anousu), {$iAnoUsu}) as anomaximo from orcunidade";
  $rsUltimoAno   = $clorcunidade->sql_record($sSqlUltimoAno);
  $iUltimoAno    = db_utils::fieldsMemory($rsUltimoAno, 0)->anomaximo;
  
  
 /*
  * Verificamos se a unidade possui cadastro para outros anos.
  * 
  */
  $rsUnidades = $clorcunidade->sql_query("select * from orcunidade where o41_anousu >= {$iAnoUsu} and o41_orgao = {$o41_orgao} and o41_unidade = {$o41_unidade}");
  if (@pg_num_rows($rsUnidades) > 0) {
  	db_msgbox("Inclusão Não Efetuada!\\nJá existe cadastro da Unidade {$o41_unidade} para o Orgão {$o41_orgao} em anos posteriores.");
  	db_redireciona();  
  }
  for ($iAno = $o41_anousu;$iAno <= $iUltimoAno; $iAno++) {
    
    $clorcunidade->incluir($iAno,$o41_orgao,$o41_unidade);
    if ($clorcunidade->erro_status == 0) {
      break;      
    }
  }
  db_fim_transacao();
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
	<?
	include("forms/db_frmorcunidade.php");
	?>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir)){
  if($clorcunidade->erro_status=="0"){
    $clorcunidade->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clorcunidade->erro_campo!=""){
      echo "<script> document.form1.".$clorcunidade->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcunidade->erro_campo.".focus();</script>";
    };
  }else{
    $clorcunidade->erro(true,true);
  };
};
?>