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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rechumano_classe.php");
include("classes/db_db_uf_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpesdoc_classe.php");
include("classes/db_rhraca_classe.php");
include("classes/db_rhinstrucao_classe.php");
include("classes/db_rhestcivil_classe.php");
include("classes/db_rhnacionalidade_classe.php");
include("classes/db_rechumanoescola_classe.php");
include("classes/db_pais_classe.php");
include("classes/db_censouf_classe.php");
include("classes/db_censomunic_classe.php");
include("classes/db_censoorgemissrg_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clrechumano = new cl_rechumano;
$cldb_uf = new cl_db_uf;
$clrhpessoal = new cl_rhpessoal;
$clrhpesdoc = new cl_rhpesdoc;
$clrhraca = new cl_rhraca;
$clrhinstrucao = new cl_rhinstrucao;
$clrhestcivil = new cl_rhestcivil;
$clrhnacionalidade = new cl_rhnacionalidade;
$clpais = new cl_pais;
$clcensouf = new cl_censouf;
$clcensoorgemissrg = new cl_censoorgemissrg;
$clcensomunic = new cl_censomunic;
$db_botao = false;
$db_opcao = 33;
$db_opcao1 = 3;
if(isset($excluir)){
 $db_opcao = 3;
 $db_opcao1 = 3;
 db_inicio_transacao();
 $clrechumano->excluir($ed20_i_codigo);
 db_fim_transacao();
}else if(isset($chavepesquisa)){
 $db_opcao = 3;
 $db_opcao1 = 3;
 $result = $clrechumano->sql_record($clrechumano->sql_query($chavepesquisa));
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset align="left" style="width:95%"><legend><b>Exclusão de Recurso Humano</b></legend>
    <?include("forms/db_frmrechumano.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
 if($clrechumano->erro_status=="0"){
  $clrechumano->erro(true,false);
 }else{
  $clrechumano->erro(true,true);
 }
}
if($db_opcao==33){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>