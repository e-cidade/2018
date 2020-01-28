<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_liborcamento.php"));
require_once(modification("model/relatorioContabil.model.php"));
$oGet = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$clrotulo->label('o116_periodo');
$oRelatorio = new relatorioContabil($oGet->codrel);
db_postmemory($HTTP_POST_VARS);

$anousu = db_getsession("DB_anousu");

$sLabelMsg = "Anexo XIII - Demonstrativo das Parcerias Público-Privadas";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
var variavel = 1;

function js_buscaEdicaoLrf(iAnousu,sFontePadrao){
  var url       = 'con4_lrfbuscaedicaoRPC.php';
  var parametro = 'ianousu='+iAnousu+'&sfontepadrao='+sFontePadrao ;
  var objAjax   = new Ajax.Request (url, { method:'post',
                                           parameters:parametro,
                                           onComplete:js_setNomeArquivo}
                                    );
}

function js_setNomeArquivo(oResposta){
  sNomeArquivoEdicao = oResposta.responseText;
}

js_buscaEdicaoLrf(<?=db_getsession("DB_anousu")?>,"con2_lrfanexoxvii002");


function js_emite(){

  var sel_periodo = document.form1.o116_periodo.value;

  if (sel_periodo == "0"){
    alert("Selecione um periodo");
    return false;
  }
  var query = "";
  var obj   = document.form1;
  query += sNomeArquivoEdicao+"?&periodo="+obj.o116_periodo.value;
  jan    = window.open(query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
 <form name="form1" method="post" action="">
     <table align="center" cellpadding="0" cellspacing="0">
       <tr>
         <td >&nbsp;</td>
       </tr>
       <tr>
         <td colspan=3  class='table_header'>
           <?=$sLabelMsg?>
         </td>
       </tr>
       <tr>
         <td>
           <fieldset>
             <legend><b>Filtros</b></legend>
					  <table align="center" border=0>
					    <tr>
					     <td>&nbsp;</td>
                  <td nowrap title="<?=@$To116_periodo?>" colspan="2" align="center">
                     <?=@$Lo116_periodo?>
                     <?
                     $aPeriodos = $oRelatorio->getPeriodos();
                     $aListaPeriodos = array();
                     $aListaPeriodos[0] = "Selecione";
                     foreach ($aPeriodos as $oPeriodo) {
                       $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                     }
                     db_select("o116_periodo", $aListaPeriodos, true, 1);
                    ?>
                  </td>
               </tr>
					   </table>
					  </fieldset>
         </td>
       </tr>
       <tr>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td align="center">
           <input type="submit" id="imprimir" value="Imprimir" onClick="return js_emite();">
         </td>
       </tr>
     </table>
 </form>
</body>
</html>