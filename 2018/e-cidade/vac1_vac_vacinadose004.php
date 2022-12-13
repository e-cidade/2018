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
include("classes/db_vac_vacinadose_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
db_postmemory($HTTP_POST_VARS);
$clvac_vacinadose = new cl_vac_vacinadose;
$db_opcao         = 1;
$db_botao         = true;

?>
<script>
    parent.document.formaba.a2.disabled = true;
    parent.document.formaba.a3.disabled = true;
    parent.document.formaba.a4.disabled = true;
</script>
<?
//altera exclui inicio
$db_botao1 = false;
if (isset($opcao)) {

  /////comeca classe alterar excluir
  $campos  = "";
  $sSql    = $clvac_vacinadose->sql_query2("","*",""," vc07_i_codigo = $vc07_i_codigo ");
  $result1 = $clvac_vacinadose->sql_record($sSql);
  if ($clvac_vacinadose->numrows > 0) {

    db_fieldsmemory($result1,0);
    
    ?>
    <script>

    parent.document.formaba.a2.disabled = false;
    parent.document.formaba.a3.disabled = false;
    parent.document.formaba.a4.disabled = false;
    top.corpo.iframe_a3.iframe_a2.location.href   = 'vac1_vac_vacinadose005.php?'+
                                          'chavepesquisa=<?=$vc07_i_codigo?>';
    top.corpo.iframe_a3.iframe_a3.location.href   = 'vac1_vac_vacinadoserestricao004.php?'+
                                          'vc08_i_vacinadose=<?=$vc07_i_codigo?>&vc07_c_nome=<?=$vc07_c_nome?>'+
                                          '&vc06_i_codigo=<?=$vc07_i_vacina?>&vc06_c_descr=<?=$vc06_c_descr?>';
    top.corpo.iframe_a3.iframe_a4.location.href   = 'vac1_vac_dependencia004.php?'+
                                          'vc09_i_dependente=<?=$vc07_i_codigo?>&dependente=<?=$vc07_c_nome?>';

    </script>

    <?
    
  }
  if ($opcao == "alterar") {

    $db_opcao  = 2;
    $db_botao1 = true;

  } else {

    if ($opcao == "excluir" || isset($db_opcao) && $db_opcao == 3) {

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
  $clvac_vacinadose->vc07_i_sexo        = 3; // Default: AMBOS
  $clvac_vacinadose->vc07_i_tipocalculo = 1; // Default: DATA DE NASCIMENTO
  $clvac_vacinadose->incluir($vc07_i_codigo);
  db_fim_transacao();

} else if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $clvac_vacinadose->alterar($vc07_i_codigo);
  db_fim_transacao();

} else if (isset($excluir)) {

  db_inicio_transacao();
  $db_opcao = 3;
  $clvac_vacinadose->excluir($vc07_i_codigo);
  db_fim_transacao();

} else if (isset($chavepesquisa)) {

  $db_opcao = 2;
  $sSql     = $clvac_vacinadose->sql_query_alt($chavepesquisa);
  $result   = $clvac_vacinadose->sql_record($sSql); 
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
<br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmvac_vacinadose.php");
	?>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1", "vc07_i_diasvalidade", true, 1, "vc07_i_diasvalidade", true);
</script>
<?
if ((isset($incluir)) || (isset($alterar)) || (isset($excluir))) {

  if ($clvac_vacinadose->erro_status == "0") {

    $clvac_vacinadose->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clvac_vacinadose->erro_campo != "") {

      echo "<script> document.form1.".$clvac_vacinadose->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clvac_vacinadose->erro_campo.".focus();</script>";

    }
  } else {

    $clvac_vacinadose->erro(true,false);
    db_redireciona("vac1_vac_vacinadose004.php?vc07_i_vacina=$vc07_i_vacina&vc06_c_descr=$vc06_c_descr");

  }
}
?>