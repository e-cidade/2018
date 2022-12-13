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
include("classes/db_vac_campanhavacina_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
db_postmemory($HTTP_POST_VARS);
$clvac_campanhavacina = new cl_vac_campanhavacina;
$db_opcao             = 1;
$db_botao             = true;

//altera exclui inicio
$db_botao1 = false;
if (isset($opcao)) {

 /////comeca classe alterar excluir
  $campos  = "";
  $result1 = $clvac_campanhavacina->sql_record($clvac_campanhavacina->sql_query("","*",""," vc12_i_codigo = $vc12_i_codigo "));
  if ($clvac_campanhavacina->numrows>0) {
    db_fieldsmemory($result1,0);
  }
  if ( $opcao == "alterar") {

    $db_opcao  = 2;
    $db_botao1 = true;

  } else {

    if ( $opcao=="excluir" || isset($db_opcao) && $db_opcao==3) {

      $db_opcao  = 3;
      $db_botao1 = true;

    } else {

      if (isset($alterar)) {

        $db_opcao  = 2;
        $db_botao1 = true;

      }
    }
  }
}

if (isset($incluir)) {

  db_inicio_transacao();
  $clvac_campanhavacina->incluir($vc12_i_codigo);
  db_fim_transacao();

} else if(isset($alterar)) {
  
  db_inicio_transacao();
  $db_opcao = 2 ;
  $clvac_campanhavacina->alterar($vc12_i_codigo);
  db_fim_transacao();

} else if(isset($excluir)) {

  db_inicio_transacao();
  $db_opcao = 3;
  $clvac_campanhavacina->excluir($vc12_i_codigo);
  db_fim_transacao();

} else if(isset($chavepesquisa)) {

  $db_opcao = 2;
  $result   = $clvac_campanhavacina->sql_record($clvac_campanhavacina->sql_query($chavepesquisa)); 
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
<br><br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmvac_campanhavacina.php");
	?>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1", "vc12_i_campanha", true, 1, "vc12_i_campanha", true);
</script>
<?
if ((isset($incluir))||(isset($alterar))||(isset($excluir))) {

  if ($clvac_campanhavacina->erro_status == "0") {

    $clvac_campanhavacina->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clvac_campanhavacina->erro_campo != "") {

      echo "<script> document.form1.".$clvac_campanhavacina->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clvac_campanhavacina->erro_campo.".focus();</script>";

    }
  } else {

    $clvac_campanhavacina->erro(true,false);
    db_redireciona("vac1_vac_campanhavacina004.php?vc12_i_campanha=$vc12_i_campanha&vc11_c_nome=$vc11_c_nome");

  }
}
?>