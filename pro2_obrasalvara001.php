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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_caracter_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_obrasalvara_classe.php");

$clobrasalvara                    = new cl_obrasalvara;
$oIframeSeleciona                 = new cl_iframe_seleciona;
$clrotulo                         = new rotulocampo;
$oArquivoAuxiliar                 = new cl_arquivo_auxiliar();
 
$oArquivoAuxiliar->cabecalho      = '<strong>Seleção de Obras</strong>';
$oArquivoAuxiliar->codigo         = 'ob04_codobra';
$oArquivoAuxiliar->descr          = 'ob01_nomeobra';
$oArquivoAuxiliar->nomeobjeto     = 'oObrasAlvara';
$oArquivoAuxiliar->funcao_js      = 'js_mostraSituacao';
$oArquivoAuxiliar->funcao_js_hide = 'js_mostraSituacaoHide';
$oArquivoAuxiliar->func_arquivo   = 'func_obrasalvara.php';
$oArquivoAuxiliar->nomeiframe     = 'db_iframe_situacao';
$oArquivoAuxiliar->db_opcao       = 2;
$oArquivoAuxiliar->tipo           = 2;
$oArquivoAuxiliar->linhas         = 6;
$oArquivoAuxiliar->vwidth         = 350;
$oArquivoAuxiliar->Labelancora    = 'Numero Alvara';

$clobrasalvara->rotulo->label();

db_postmemory($HTTP_POST_VARS);

/**
 * Declaração de variáveis utilizadas no script
 */

$ob04_data_dia = "";
$ob04_data_mes = "";
$ob04_data_ano = "";
$aTipoSelecao  = array("S"=>"Somente Selecionados", "N"=>"Menos os Selecionados");


?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires"      content="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" onload="js_tipoSelecao();">
    <form class="container" name="form1" method="post" action="pro2_obrasalvara002.php" target="rel">
      <fieldset>
        <legend>Relatório de Obras com Alvará</legend>
        <table class="form-container">
          <tr>                
            <td nowrap title="<?=@$Tob04_data?>">
              Data de emissão
            </td>
            <td>
              <?  
                db_inputdata('ob04_data', $ob04_data_dia, $ob04_data_mes, $ob04_data_ano, true, 'text', 1, "", "ob04_dataINI");
                echo "<strong>&nbsp;À&nbsp;</strong>";
                db_inputdata('ob04_data', $ob04_data_dia, $ob04_data_mes, $ob04_data_ano, true, 'text', 1, "", "ob04_dataFIM");
              ?>                   
            </td>
          </tr>
          <tr>                
            <td nowrap title="<?=@$Tob04_data?>">
              Tipo de Seleção:
            </td>
            <td>
              <?  
                db_select("tipoSelecao", array("S" => "Seleção","I" => "Intervalo"),true,1, "onChange=\"js_tipoSelecao();\"");
              ?>                   
            </td>
          </tr>
          <tr class="obras_selecao" style="display:none">
            <td colspan="2">
              <table>
               <?
                $oArquivoAuxiliar->funcao_gera_formulario();
               ?>
              </table>
            </td>
          </tr>
          <tr class="obras_selecao">
            <td>Opção de Seleção:</td>
            <td>
              <?
              db_select('param_obrasalvara',$aTipoSelecao, true,2);
              ?>
            </td>
          </tr>
          <tr class="obras_intervalo">
            <td title="<?=@$Tob04_codobra?>">
              Obras:  
            </td>
            <td>
              <? 
                db_input('ob04_codobra', 15, $Iob04_codobra, true, 'text', 1, "","ob04_codobraINI", "");
                echo "<strong> À </strong>";                                                                                             
                db_input('ob04_codobra', 15, $Iob04_codobra, true, 'text', 1, "","ob04_codobraFIM", "");
              ?>
            </td>
          </tr>
          <tr>
            <td>Ordem de seleção:</td>
            <td>
              <select name="ordem" id="ordemRelatorio">
                <option value="ob04_codobra">Código da obra</option>
                <option value="ob04_data">Data da emissão</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Formato do Relatório:</td>
            <td>
              <?php 
                db_select("formatoRelatorio", array("pdf" => "PDF","csv" => "CSV"),true,1);
              ?>
            </td>
          </tr>
        </table>
      <?  db_input('obra',"",0,true,'hidden',3,"");  ?>
      </fieldset>
      <input type="button" name="relatorio1" value=" Processar " onClick="js_validaCampos();">
    </form>
  </body>
  <? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>
<script>
function js_tipoSelecao() {
  
  var sDisplaySelecao   = null;
  var sDisplayIntervalo = null;
  
  switch ( $F('tipoSelecao') ) {
    
    case "I":
      
      sDisplayIntervalo = '';
      sDisplaySelecao   = 'none';
    break;
    case "S":
      
      sDisplayIntervalo = 'none';
      sDisplaySelecao   = '';
    break;
  }

  $$('.obras_intervalo').each(
    
    function(oElemento, iIndice){
      oElemento.style.display = sDisplayIntervalo;
    }
  );
  
  $$('.obras_selecao').each(
      
    function(oElemento, iIndice){
      oElemento.style.display = sDisplaySelecao;
    }
  );
  
}

function js_validaCampos() {

  var sDataInicio       = js_formatar( $F('ob04_dataINI'), 'd' ).replace( /-/g, '' );
  var sDataFim          = js_formatar( $F('ob04_dataFIM'), 'd' ).replace( /-/g, '' );
  
  if ( sDataInicio > sDataFim ) {
    
    alert(_M('tributario.projetos.pro2_obrasalvara001.data_inicial_maior_data_final'));
    $('ob04_dataINI').focus();
    
    return false;
  }
  
  /**
   * caso o tipo de seleção for intervalo valida o mesmo
   */
  if ( $F('tipoSelecao') == "I") {

    var iCodigoObraInicio = new Number( $F('ob04_codobraINI') );
    var iCodigoObraFim    = new Number( $F('ob04_codobraFIM') );

    if ( iCodigoObraInicio > iCodigoObraFim ) {
      
      alert(_M('tributario.projetos.pro2_obrasalvara001.obra_inicial_maior_obra_final'));
      $('ob04_dataINI').focus();
      return false;
    }
  }

  /**
   * Chama função que emite relatório
   */  
  js_emiteRelatorio();
  
}

function js_emiteRelatorio() {

  var oDados = new Object();
  
  oDados.sDataInicio       = js_formatar( $F('ob04_dataINI'), 'd' );
  oDados.sDataFim          = js_formatar( $F('ob04_dataFIM'), 'd' );  
  oDados.sTipoSelecao      = $F('tipoSelecao');
  
  if ( oDados.sTipoSelecao == "I") {

    oDados.iCodigoObraInicio = new Number( $F('ob04_codobraINI') );
    oDados.iCodigoObraFim    = new Number( $F('ob04_codobraFIM') );
  } else {
    
    oDados.aObrasSelecionadas = new Array();

    for ( var iIndiceSelect = 0; iIndiceSelect < $('oObrasAlvara').length; iIndiceSelect++ ) {

      var oOption        = $('oObrasAlvara').options[iIndiceSelect];
      oDados.aObrasSelecionadas.push(oOption.value);  
    }
    oDados.sOpcaoSelecionados = $F('param_obrasalvara');
  }
  oDados.sCampoOrdenacao    = $F('ordemRelatorio');

  oDados.sFormatoRelatorio  = $F("formatoRelatorio");  
  
  var sJson = Object.toJSON(oDados);
  oJanela = window.open("pro2_obrasalvara002.php?sJson=" + sJson,
                        "windowRelatorio","width="+(screen.availWidth-5)+",height="+(screen.availHeight-40)+",scrollbars=1,location=0");
  oJanela.moveTo(0,0);
  
}
</script>

<script>

$("ob04_dataINI").addClassName("field-size2");
$("ob04_dataFIM").addClassName("field-size2");
$("fieldset_oObrasAlvara").addClassName("separator");
$("ob04_codobra").addClassName("field-size2");
$("ob01_nomeobra").addClassName("field-size7");
$("ob04_codobraINI").addClassName("field-size2");
$("ob04_codobraFIM").addClassName("field-size2");
$("oObrasAlvara").style.width = "100%";
</script>