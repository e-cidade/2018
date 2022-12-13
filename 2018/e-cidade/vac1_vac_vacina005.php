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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oDaoVacVacina         = db_utils::getdao('vac_vacina');
$oDaoVacVacinaMaterial = db_utils::getdao('vac_vacinamaterial');
$db_opcao              = 22;
$db_botao              = false;
if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $oDaoVacVacina->alterar($vc06_i_codigo);
  db_fim_transacao();

} elseif (isset($chavepesquisa)) {

  $db_opcao = 2;
  $sSql     = $oDaoVacVacina->sql_query($chavepesquisa);
  $rsResult = $oDaoVacVacina->sql_record($sSql); 
  db_fieldsmemory($rsResult, 0);
  $db_botao = true;

?>
  <script>

  parent.document.formaba.a2.disabled = false;
  parent.document.formaba.a3.disabled = false;
  parent.document.formaba.a4.disabled = false;
  top.corpo.iframe_a2.location.href   = 'vac1_vac_vacina007.php?'+
                                        'chavepesquisa=<?=$vc06_i_codigo?>&vc06_c_descr=<?=$vc06_c_descr?>';
  top.corpo.iframe_a3.location.href   = 'vac1_vac_vacinadose001.php?'+
                                        'vc07_i_vacina=<?=$vc06_i_codigo?>&vc06_c_descr=<?=$vc06_c_descr?>';
  top.corpo.iframe_a4.location.href   = 'vac1_vac_vacinadoenca004.php?'+
                                        'vc10_i_vacina=<?=$vc06_i_codigo?>&vc06_c_descr=<?=$vc06_c_descr?>';

  </script>
<?
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("datagrid.widget.js");
    db_app::load("strings.js");
    db_app::load("grid.style.css");
    db_app::load("estilos.css");
    db_app::load("/widgets/dbautocomplete.widget.js");
    db_app::load("webseller.js");
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <br><br>
    <center>
      <table width="790" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
            <center>
              <?
              require_once("forms/db_frmvac_vacina.php");
              ?>
            </center>
          </td>
        </tr>
      </table>
    </center>
  </body>
</html>
<?
if (isset($alterar)) {

  if ($oDaoVacVacina->erro_status == "0") {

    $oDaoVacVacina->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false; </script>";

    if ($oDaoVacVacina->erro_campo != "") {

      echo "<script> document.form1.".$oDaoVacVacina->erro_campo.".style.backgroundColor = '#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoVacVacina->erro_campo.".focus();</script>";

    }
    
  } else {

    $oDaoVacVacina->erro(true, false);
    db_redireciona("vac1_vac_vacina005.php?chavepesquisa=$vc06_i_codigo");

  }
  
}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1", "vc06_i_vacina", true, 1, "vc06_i_vacina", true);
</script>