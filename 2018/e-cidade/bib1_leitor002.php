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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_leitor_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clleitor  = new cl_leitor;
$db_opcao  = 22;
$db_opcao1 = 3;
$db_botao  = false;

if (isset($chavepesquisa)) {
  
  $db_opcao = 2;
  $campos   = "leitor.bi10_codigo,
               cidadao.ov02_sequencial as codigo,
               cidadao.ov02_nome as nome,
               'CIDADAO' as tipo
              ";
  $result   = $clleitor->sql_record($clleitor->sql_query_leitorcidadao("",$campos,""," bi10_codigo = $chavepesquisa"));
  
  db_fieldsmemory($result,0);
  $db_botao = true;
  ?>
  <script>
    parent.document.formaba.acervo2.disabled = false;
    top.corpo.iframe_acervo2.location.href='bib1_carteira001.php?bi16_leitor=<?=$bi10_codigo?>&z01_nome=<?=$nome?>';
  </script>
  <?
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Altera��o de Leitor</b></legend>
    <?require_once("forms/db_frmleitor.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if (isset($alterar)) {
  
  if ($clleitor->erro_status == "0") {
    
    $clleitor->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clleitor->erro_campo != "") {

      echo "<script> document.form1.".$clleitor->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clleitor->erro_campo.".focus();</script>";
    };
  } else {
    ?>
    <script>
      parent.document.formaba.acervo2.disabled = false;
      top.corpo.iframe_acervo2.location.href='bib1_carteira001.php?bi16_leitor=<?=$bi10_codigo?>&z01_nome=<?=$nome?>';
    </script>
    <?
    $clleitor->erro(true,false);
  };
};
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>