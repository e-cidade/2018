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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
include_once(modification("libs/db_sessoes.php"));
include_once(modification("libs/db_usuariosonline.php"));
include_once(modification("dbforms/db_funcoes.php"));
include_once(modification("libs/db_liborcamento.php"));
require_once(modification("model/relatorioContabil.model.php"));

$oGet       = db_utils::postMemory($_GET);
db_postmemory($HTTP_POST_VARS);

$oRelatorio = new relatorioContabil($oGet->c83_codrel);
$clrotulo   = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

$iAnoUsu    = db_getsession("DB_anousu");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script> 
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
<style>
#o116_periodo, #emissao {
  width: 100%;
}
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <form name="form1" method="post" action="" >
    <table align="center" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="table_header">  
          Anexo I - Demonstrativo da Despesa com Pessoal 
        </td>
      </tr>
      <tr>
        <td>
          <fieldset>
            <table align="center" border="0">
              <tr>
                <td id="ctnInstituicao" align="center" colspan="3">
                  <input type="hidden" name="db_selinstit" id="db_selinstit" value="">
                </td>
              </tr>
              <tr colspan="2" nowrap>&nbsp;</tr>
              <tr>
                <td nowrap>
                  <b>Período:&nbsp;</b>
                </td>
                <td>
									<?
                    $aListaPeriodos    = array();
                    $aListaPeriodos[0] = "Selecione";
									  if ($iAnoUsu <= 2007) {

									    $aListaPeriodos  = array("1B" => "Primeiro Bimestre",
									                             "2B" => "Segundo  Bimestre",
									                             "3B" => "Terceiro Bimestre",
									                             "4B" => "Quarto   Bimestre",
									                             "5B" => "Quinto   Bimestre",
									                             "6B" => "Sexto    Bimestre");
									  } else if  ($iAnoUsu <= 2009) {
									  	
									  	$aListaPeriodos  = array("1Q" => "Primeiro Quadrimestre",
									  	                         "2Q" => "Segundo  Quadrimestre",
									  	                         "3Q" => "Terceiro Quadrimestre",
									  	                         "1S" => "Primeiro Semestre",
									  	                         "2S" => "Segundo Semestre");
									  } else {
									  	
									    $aPeriodos = $oRelatorio->getPeriodos();
									    foreach ($aPeriodos as $oPeriodo) {
									      $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
									    }                
									  }
									  
								    db_select("o116_periodo", $aListaPeriodos, true, 1);     
									?>
                </td>
              </tr>
              <?php
                if  ($iAnoUsu >= 2011) {
              ?>
				      <tr>
				        <td nowrap>
				          <b>Emissão:&nbsp;</b>
				        </td>
				        <td>
				          <?
				            $aListaEmissao = array("1" => "Publicação Oficial",
				                                   "2" => "Detalhamento Mensal");
				            db_select("emissao", $aListaEmissao, true, 1);
				          ?>
				        </td>
				      </tr>
				      <?php
                }
				      ?>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>&nbsp;</tr>
	    <tr>
	      <td align="center" colspan="2">
	        <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite(<?=$iAnoUsu?>);">
	      </td>
	    </tr>
    </table>  
  </form>
</body>
<script>
var oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('ctnInstituicao'));
oViewInstituicao.show();

function js_buscaEdicaoLrf(iAnousu,sFontePadrao) {
  
  var url       = 'con4_lrfbuscaedicaoRPC.php';
  var parametro = 'ianousu='+iAnousu+'&sfontepadrao='+sFontePadrao;
  var objAjax   = new Ajax.Request (url, { method:'post',
                                           parameters:parametro, 
                                           onComplete:js_setNomeArquivo
                                          });  
}

function js_setNomeArquivo(oResposta) {
  sNomeArquivoEdicao = oResposta.responseText;
}

js_buscaEdicaoLrf(<?=$iAnoUsu?>,'con2_lrfdesppessoal002');

function js_emite(sFonte) {
  var oDocument     = document.form1;
  var iPeriodo      = $('o116_periodo').value;
  var iSelInstit    = oViewInstituicao.getInstituicoesSelecionadas(true).join('-');
  var sDataIni      = '';
  var sDataFim      = '';
  var iFormaEmissao = 1;
  if ($('emissao')) {
    var iFormaEmissao = $('emissao').value;
  }
  var sNomeArquivo  = sNomeArquivoEdicao;
  var sUrl          = sNomeArquivo+'?db_selinstit='+
                      iSelInstit+
                      '&dtfin='+sDataFim+
                      '&periodo='+iPeriodo+
                      '&emissao='+iFormaEmissao;
  
  if (iSelInstit == 0) {
  
    alert('Você não escolheu nenhuma instituição. Verifique!');
    return false;
  }
  
  if (iPeriodo == 0) {
    
    alert('Você não escolheu nenhum período. Verifique!');
    return false;
  }

  var jan      = window.open(sUrl,
                             '',
                             'width='+(screen.availWidth-5)+
                             ', height='+(screen.availHeight-40)+
                             ', scrollbars=1, location=0');
  jan.moveTo(0,0);
}
</script> 
</html>