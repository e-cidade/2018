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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_diariofinal_classe.php");
require_once("classes/db_diario_classe.php");
require_once("classes/db_diarioavaliacao_classe.php");
require_once("classes/db_regencia_classe.php");
require_once("classes/db_periodoavaliacao_classe.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$resultedu          = eduparametros(db_getsession("DB_coddepto"));
$cldiariofinal      = new cl_diariofinal;
$cldiario           = new cl_diario;
$cldiarioavaliacao  = new cl_diarioavaliacao;
$clregencia         = new cl_regencia;
$clperiodoavaliacao = new cl_periodoavaliacao;
$db_opcao           = 2;
$db_botao           = true;
if (isset($alterar)) {
	
  db_inicio_transacao();
  $sql_reg    = " SELECT ed95_i_regencia as codregatual ";
  $sql_reg   .= " FROM diariofinal ";
  $sql_reg   .= "  inner join diario on ed95_i_codigo = ed74_i_diario ";
  $sql_reg   .= " WHERE ed74_i_codigo = $ed93_i_diarioavaliacao ";
  $result_reg = pg_query($sql_reg);
  db_fieldsmemory($result_reg,0);
  $cldiariofinal->ed74_t_obs           = $ed74_t_obs;
  $cldiariofinal->ed74_i_codigo        = $ed93_i_diarioavaliacao;
  $cldiariofinal->alterar($ed93_i_diarioavaliacao);
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
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 9;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
   <center>
    <?include("forms/db_frmdiariofinalobs.php");?>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
 js_tabulacaoforms("form1","ed74_t_obs",true,1,"ed74_t_obs",true);
</script>
<?
if (isset($alterar)) {
 ?>
  <script>
   parent.location.href = "edu1_diariofinal001.php?regencia=<?=$codregatual?>";
   parent.db_iframe_obs.hide();
   alert("Alteração efetuada com Sucesso");
  </script>
 <?
}
?>