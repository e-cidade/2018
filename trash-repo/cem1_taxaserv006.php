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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_taxaserv_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cltaxaserv = new cl_taxaserv;
$db_botao   = false;
$db_opcao   = 33;

if ( isset($excluir) ) {
	
  db_inicio_transacao();
  $db_opcao = 3;
  $cltaxaserv->excluir($cm11_i_codigo);
  db_fim_transacao();
} else if ( isset($chavepesquisa) ) {
	
   $db_opcao = 3;
   $result = $cltaxaserv->sql_record($cltaxaserv->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
   
    echo " <script>                                                                                                   ";
    echo "   parent.iframe_valorestaxa.location.href='cem1_taxavalorserv001.php?codigo={$cm11_i_codigo}';             ";
    echo "   parent.document.formaba.dadostaxa.disabled = false;                                                      ";
    echo "   parent.document.formaba.valorestaxa.disabled = false;                                                    ";
    echo "   parent.mo_camada('dadostaxa');                                                                           ";
    echo " </script>                                                                                                  ";   
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top"> 
      <?
        include("forms/db_frmtaxaserv.php");
      ?>
  </td>
  </tr>
</table>
</body>
</html>
<?
if ( isset($excluir) ) {
	
  if ( $cltaxaserv->erro_status == "0" ) {
    $cltaxaserv->erro(true,false);
  } else {
    $cltaxaserv->erro(true,true);
  }
}

if ( $db_opcao == 33 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>