<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");
require_once("libs/db_utils.php");

$oRelatorio = new relatorioContabil($codrel);
$iAnousu    = db_getsession("DB_anousu");
$clrotulo   = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

db_postmemory($_GET);
db_postmemory($_POST);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<?
	$sNovoFonte = "con2_anexoXIII_002_2011.php";
  $iAnoUsu    = db_getsession("DB_anousu");
?>
<script>

variavel = 1;
function js_emite(){

  if (document.db_selinstit_iframe) {
    db_selinstit_iframe.js_marca()
  }
  
  var sInstituicao = document.form1.db_selinstit.value;
  var sel_periodo  = document.form1.o116_periodo.value;

  <?
    if(!file_exists($sNovoFonte)) {
      echo "alert('Relatório não disponível para o exercício $iAnoUsu');";
      echo "return false;";
    }
  ?>
  
  if (sel_periodo == "0"){
    alert("Selecione um periodo");
    return false;
  }

  if ( document.db_selinstit_iframe ) {
    var aCheckbox = db_selinstit_iframe.$('form1').getInputs('checkbox');
    var sTraco = "";
    sInstituicao = "";
    aCheckbox.each(function (oCheckBox, id) {

        if (oCheckBox.checked) {
        sInstituicao += sTraco+oCheckBox.value
        sTraco = "-";
        }
        });
  }
  if(sInstituicao == ''){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
  }else{
    var query = "";
    var obj   = document.form1;

    query  = "db_selinstit="+sInstituicao;
    query += "&periodo="+obj.o116_periodo.value;
    query += "&codrel="+<?=$codrel ?>;
    query += "&consolidado="+obj.emiteconsolidado.value;
  
 
    jan = window.open('<?=$sNovoFonte?>?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);   
    
  }
}

function js_marcainstituicoes() {
 
  var iConsolidado = $F('emiteconsolidado');
    
  if (iConsolidado == 1) {
    
    var aCheckbox = db_selinstit_iframe.$('form1').getInputs('checkbox');
    aCheckbox.each(function (oCheckBox, id) {
       oCheckBox.checked = true;
    });
  }
}

function js_naoconsolidado() {

  var aCheckbox = db_selinstit_iframe.$('form1').getInputs('checkbox');
  aCheckbox.each(function (oCheckBox, id) {
    if (!oCheckBox.checked) {
       $('emiteconsolidado').value = 2;
    } 
  });
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

  <table  align="center">
    <form name="form1" method="post" action="">
    <tr>
    <td >&nbsp;</td>
   </tr>
   <tr>
    <td colspan=3  class='table_header'>
      Balanço Financeiro(Anexo 13)
    </td>
   </tr>  
   <tr>
    <td>
      <fieldset>
        <legend>
          <b>Filtros</b>
        </legend>
         <table border='0' align="center">
            <tr>
              <td align="center" colspan="3">
                <? db_selinstit('parent.js_naoconsolidado', 300, 100); ?>
              </td>
            </tr>
            <tr>
              <td width="10" colspan=2 >
                <b>Período:&nbsp;</b>
              </td>
              <td width="200">
                <?
                  $aPeriodos         = $oRelatorio->getPeriodos();                  
                  $aListaPeriodos    = array();
                  $aListaPeriodos[0] = "Selecione";
                  
                  foreach ($aPeriodos as $oPeriodo) {
                    $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                  }
                  
                  db_select("o116_periodo", $aListaPeriodos, true, 1);
                ?>
              </td> 
            </tr>
            <tr>
              <td colspan="2" nowrap>
                <b>Emite Consolidado:</b>
              </td>
              <td>
                <?
                  $aConsolidado = array (2 => 'Não', 1 => 'Sim');
                  db_select('emiteconsolidado', $aConsolidado, true, 1, 'onchange=js_marcainstituicoes()' );
                ?>
              </td>
            </tr>       
         </table>
      </fieldset>
     <table align="center">
       <tr>
        <td>&nbsp;</td>
       </tr> 
      <tr>
        <td colspan="2" align="center">
          <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
        </td>
      </tr>
  </form>
    </table>
</body>
</html>