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
include("classes/db_vac_vacina_classe.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_stdlibwebseller.php");
require("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clvac_vacina = new cl_vac_vacina;
$db_opcao     = 22;
$db_botao     = false;

if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $aLista=explode(",",$sLista);
  for ($iX=0;$iX<count($aLista);$iX++) {

    $clvac_vacina->vc06_i_codigo = $aLista[$iX];
    $clvac_vacina->vc06_i_orden  = $iX+1;
    $clvac_vacina->alterar($aLista[$iX]);
  
  }
  db_fim_transacao();

}

//MODULO: Vacinas
$clvac_vacina->rotulo->label();
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
<?
$sSql=$clvac_vacina->sql_query("","vc06_i_codigo,vc06_c_descr","vc06_i_orden");
$rsResult=$clvac_vacina->sql_record($sSql);
?>
<table width="450" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="200" align="center" valign="top" bgcolor="#CCCCCC">
    <center>
    <fieldset style='width: 75%;'> <legend><b> Ordenar Vacinas </b></legend>
    <form name="form1" method="post" action="">
      <center>
      <table border="0">
        <tr>
          <td nowrap title="<?=@$Tvc06_i_orden?>" colspan="2">
            <table border="0">
              <tr>
                <td rowspan="3">
                  <select name="vc06_i_orden" size="5" style="width: 200px">
                    <?
                    for ($iX=0; $iX < $clvac_vacina->numrows; $iX++) {

                      $oVacina = db_utils::fieldsmemory($rsResult,$iX);
                      echo"<option value=\"$oVacina->vc06_i_codigo\">$oVacina->vc06_c_descr</option>";

                    }
                    ?>
                  </select>
                </td>
                <td>
                  <input type="button" value="Cima" onclick="js_troca('up');">
                </td>
              </tr>
              <tr>
                <td>
                  <input type="button" Value="Baixo" onclick="js_troca('down');">
                </td>
              </tr>
            </table>
            <?db_input('sLista',10,"",true,'hidden',$db_opcao,"")?>
          </td>
        </tr>
      </table>
      </center>
      <input name="alterar" type="submit" id="db_opcao" value="Ordenar" onclick="return js_listar();" >
    </form>
<script>

function js_troca(direcao) {

  iTam      = document.form1.vc06_i_orden.length;
  iSelected = document.form1.vc06_i_orden.selectedIndex;
  iFator    = 0;
  if ((iSelected > 0) && (iSelected != -1) && (direcao == 'up')) {
    iFator = -1;
  }
  if ((iSelected < (iTam - 1)) && (iSelected != -1) && (direcao == 'down')) {
    iFator = 1;
  }
  if (iFator != 0) {

    iAlvo                                                = iSelected+iFator;
    iOptionTemp                                          = new Option('0', '0');
    iOptionTemp.text                                     = document.form1.vc06_i_orden.options[iSelected].text;
    iOptionTemp.value                                    = document.form1.vc06_i_orden.options[iSelected].value;
    iOptionTemp2                                         = new Option('0', '0');
    iOptionTemp2.text                                    = document.form1.vc06_i_orden.options[iAlvo].text;
    iOptionTemp2.value                                   = document.form1.vc06_i_orden.options[iAlvo].value;
    document.form1.vc06_i_orden.options[iSelected].text  = iOptionTemp2.text;
    document.form1.vc06_i_orden.options[iSelected].value = iOptionTemp2.value;
    document.form1.vc06_i_orden.options[iAlvo].text      = iOptionTemp.text;
    document.form1.vc06_i_orden.options[iAlvo].value     = iOptionTemp.value;
    document.form1.vc06_i_orden.selectedIndex            = iAlvo;

  }

}

function js_listar() {

  iTam=document.form1.vc06_i_orden.length;
  if (iTam == 0) {
    alert('Isso não pode estar acontecendo!');
  }
  document.form1.sLista.value='';
  sSep = '';
  for (iX = 0; iX < iTam; iX++) {

    document.form1.sLista.value += sSep+document.form1.vc06_i_orden.options[iX].value;
    sSep                         = ',';

  }
  return true;

}
</script>
 </center>
  </td>
  </tr>
</table>
</center>
</body>
</html>
<?
if (isset($alterar)) {

  if ($clvac_vacina->erro_status=="0") {

    $clvac_vacina->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clvac_vacina->erro_campo!="") {

      echo "<script> document.form1.".$clvac_vacina->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clvac_vacina->erro_campo.".focus();</script>";

    }
  } else {

    $clvac_vacina->erro(true,false);
    echo"<script>parent.js_fechaOrdenar()</script>";

  }
}
?>