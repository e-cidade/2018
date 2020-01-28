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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_libdicionario.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");

$oDaoAcordo = new cl_acordo();
$oDaoAcordo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac17_sequencial");
$clrotulo->label("descrdepto");
$clrotulo->label("ac02_sequencial");
$clrotulo->label("ac08_descricao");
$clrotulo->label("ac50_descricao");
$clrotulo->label("z01_nome");

$iOpcao = 3;
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("estilos.css");
  ?>
</head>
<body bgcolor=#CCCCCC>
  <form action="" method="post" class="container">
    <fieldset>
      <legend class='bold'>Exclusão de Acordo</legend>
      <table class='form-container'>
        <tr>
          <td nowrap ><?=$Lac16_sequencial?></td>
          <td>
            <?
              db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_origem?></td>
          <td>
            <?
             $aValores = array(
                                0 => 'Selecione',
                                1 => 'Processo de Compras',
                                2 => 'Licitação',
                                3 => 'Manual' ,
                                6 => 'Empenho'
                              );
              db_input('ac16_origem', 10, $Iac16_origem, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_acordogrupo;?></td>
          <td>
            <?
              db_input('ac16_acordogrupo', 10, $Iac16_acordogrupo, true, 'text', $iOpcao);
              db_input('ac02_descricao', 30, $Iac02_sequencial, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_numero?></td>
          <td>
            <?
              db_input('ac16_numero', 10, $Iac16_numero, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_contratado;?></td>
          <td>
            <?
              db_input('ac16_contratado', 10, $Iac16_contratado, true, 'text', $iOpcao);
              db_input('nomecontratado', 30, $Iz01_nome, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap>
            <label>Depto Responsável:</label>
          </td>
          <td>
            <?
              db_input('ac16_deptoresponsavel', 10, $Iac16_deptoresponsavel, true, 'text', $iOpcao);
              db_input('descrdepto', 30, $Idescrdepto, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap>
            <label>Comissão:</label>
          </td>
          <td>
            <?
              db_input('ac16_acordocomissao', 10, $Iac16_acordocomissao, true, 'text', $iOpcao);
              db_input('ac08_descricao', 30, $Iac08_descricao, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_lei;?></td>
          <td>
            <?
              db_input('ac16_lei', 50, $Iac16_lei, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_numeroprocesso;?></td>
          <td>
            <?
              db_input('ac16_numeroprocesso', 50, $Iac16_numeroprocesso, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_qtdrenovacao;?></td>
          <td>
            <?
              db_input('ac16_qtdrenovacao', 2, @$Iac16_qtdrenovacao, true, 'text', $iOpcao);
              db_input("ac16_tipounidtempo", 4, $Iac16_tipounidtempo, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td>
             <label>Contrato Emergencial:</label>
          </td>
          <td>
            <?
              $aEmergencial = array("f" => "Não", "t" => "Sim");
              db_select("ac26_emergencial", $aEmergencial, true, $iOpcao);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap><?=$Lac16_dataassinatura?></td>
          <td>
            <?
              db_inputdata('ac16_dataassinatura', @$ac16_dataassinatura_dia, @$ac16_dataassinatura_mes,
                           @$ac16_dataassinatura_ano, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
        	<td>
        		<label>Períodos por Mês Comercial:</label>
        	</td>
        	<td>
        		<?
        		  $aDivisaoPeriodos = array("false" => "NÃO", "true" => "SIM");
        		  db_select("ac16_periodocomercial", $aDivisaoPeriodos, true, $iOpcao);
        		?>
        	</td>
        </tr>
        <tr>
          <td nowrap>
            <label>Categoria:</label>
          </td>
          <td>
            <?
              db_input('ac50_sequencial', 10, $Iac50_descricao, true, 'text', $iOpcao);
              db_input('ac50_descricao', 30, $Iac50_descricao, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset class='separator'>
              <legend>Vigência</legend>
              <table>
                <tr>
                  <td>
                    <label>Inicio:</label>
                  </td>
                  <td>
                    <?
                      db_inputdata('ac16_datainicio', @$ac16_datainicio_dia, @$ac16_datainicio_mes,
                                   @$ac16_datainicio_ano, true, 'text', $iOpcao);
                    ?>
                  </td>
                  <td style="text-indent: 30px;">
                    <label>Fim:</label>
                  </td>
                  <td style="text-align: right;">
                    <?
                      db_inputdata('ac16_datafim', @$ac16_datafim_dia, @$ac16_datafim_mes, @$ac16_datafim_ano, true, 'text', $iOpcao);
                    ?>
                  </td>
                  <td style="text-indent: 50px;">
                    <label>Dias:</label>
                  </td>
                  <td style="text-align: right;">
                    <?
                      db_input('diasvigencia', 10, "", true, 'text', $iOpcao);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>Período:</label>
                  </td>
                  <td>
                    <?
                      db_input('ac16_qtdperiodo', 2, @$Iac16_qtdperiodo, true, 'text', $iOpcao);
                    ?>
                  </td>
                  <td colspan="4">
                    <?
                      db_input("ac16_tipounidtempoperiodo", 4, $Iac16_tipounidtempoperiodo, true, 'text', $iOpcao);
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
	      <tr>
	        <td nowrap colspan="2">
	          <fieldset>
	            <legend><?=@$Lac16_objeto?></legend>
	            <?
	              db_textarea('ac16_objeto', 3, 58, $Iac16_objeto, true, 'text', $iOpcao);
	            ?>
	          </fieldset>
	        </td>
	      </tr>
	      <tr>
	        <td nowrap colspan="2">
	          <fieldset>
	            <legend><?=@$Lac16_resumoobjeto?></legend>
				        <?
				          db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', $iOpcao);
				        ?>
	          </fieldset>
	        </td>
	      </tr>
      </table>
    </fieldset>
    <input id="btnExcluir" type="button" value="Excluir" disabled="disabled" />
    <input id="btnPesquisar" type="button" value="Pesquisar" />
  </form>
</body>
<?
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>

$("ac16_sequencial").addClassName("field-size2");
$("ac16_origem").addClassName("field-size9");
$("ac16_acordogrupo").addClassName("field-size2");
$("ac02_descricao").addClassName("field-size7");
$("ac16_numero").addClassName("field-size2");
$("ac16_contratado").addClassName("field-size2");
$("nomecontratado").addClassName("field-size7");
$("ac16_deptoresponsavel").addClassName("field-size2");
$("descrdepto").addClassName("field-size7");
$("ac16_acordocomissao").addClassName("field-size2");
$("ac08_descricao").addClassName("field-size7");
$("ac16_lei").addClassName("field-size9");
$("ac16_numeroprocesso").addClassName("field-size9");
$("ac16_qtdrenovacao").addClassName("field-size2");
$("ac16_tipounidtempo").addClassName("field-size2");
$("ac26_emergencial_select_descr").addClassName("field-size2");
$("ac16_dataassinatura").addClassName("field-size2");
$("ac16_periodocomercial_select_descr").addClassName("field-size2");
$("ac50_sequencial").addClassName("field-size2");
$("ac50_descricao").addClassName("field-size7");
$("ac16_datainicio").addClassName("field-size2");
$("ac16_datafim").addClassName("field-size2");
$("diasvigencia").addClassName("field-size2");
$("ac16_qtdperiodo").addClassName("field-size2");
$("ac16_tipounidtempoperiodo").addClassName("field-size9");

$("ac16_objeto").style.width       = "100%";
$("ac16_resumoobjeto").style.width = "100%";

var sCaminhoMensagens = 'patrimonial.contratos.aco4_excluiracordo003.';
var sRpc              = 'con4_contratos.RPC.php';

$('btnPesquisar').observe("click", function() {
  pesquisaAcordos();
});

$('btnExcluir').observe("click", function() {
  removerAcordo();
});

/**
 * Pesquisa os arquivos passíveis de exclusão
 */
function pesquisaAcordos() {

  var sUrl = 'func_acordo.php?funcao_js=parent.buscaDadosAcordo|ac16_sequencial&iTipoFiltro=1,4&lAtivo=1&lComExecucao=false';
  js_OpenJanelaIframe(
                       '',
                       'db_iframe_acordo',
                       sUrl,
                       'Pesquisar Acordos',
                       true
                     );
}

/**
 * Busca os dados do acordo selecionado na lookup
 *
 * @param integer iAcordo - Código do acordo
 */

function buscaDadosAcordo( iAcordo ) {

  db_iframe_acordo.hide();
  limpaCampos();
  
  var oParametro           = new Object();
      oParametro.exec      = 'getDadosAcordo';
      oParametro.iContrato = iAcordo;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = retornoBuscaDadosAcordo;

  js_divCarregando( _M( sCaminhoMensagens+'buscando_acordo' ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Retorna os dados do acordo para preenchimento do formulário
 */
function retornoBuscaDadosAcordo( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '('+oResponse.responseText+')' );

  if ( oRetorno.status != 1 ) {

    alert( oRetorno.message.urlDecode() );
    return false;
  }

  var aOrigens    = new Array();
      aOrigens[1] = 'Processo de Compras';
      aOrigens[2] = 'Licitação';
      aOrigens[3] = 'Manual';
      aOrigens[6] = 'Empenho';

  var aPeriodo    = new Array();
      aPeriodo[1] = 'Mês';
      aPeriodo[2] = 'Dia';

  var aRenovacao    = new Array();
      aRenovacao[1] = 'Mês';
      aRenovacao[2] = 'Dias';

  $("ac16_sequencial").value               = oRetorno.contrato.iSequencial;
  $("ac16_origem").value                   = aOrigens[oRetorno.contrato.iOrigem];
  $("ac16_acordogrupo").value              = oRetorno.contrato.iGrupo;
  $("ac16_numero").value                   = oRetorno.contrato.iNumero;
  $("ac16_contratado").value               = oRetorno.contrato.iContratado;
  $("nomecontratado").value                = oRetorno.contrato.sNomeContratado.urlDecode();
  $("ac16_deptoresponsavel").value         = oRetorno.contrato.iDepartamentoResponsavel;
  $("descrdepto").value                    = oRetorno.contrato.sNomeDepartamentoResponsavel.urlDecode();
  $("ac16_acordocomissao").value           = oRetorno.contrato.iComissao;
  $("ac08_descricao").value                = oRetorno.contrato.sNomeComissao.urlDecode();
  $("ac16_lei").value                      = oRetorno.contrato.sLei.urlDecode();
  $("ac16_numeroprocesso").value           = oRetorno.contrato.sNumeroProcesso.urlDecode();
  $("ac16_qtdrenovacao").value             = oRetorno.contrato.iNumeroRenovacao;
  $("ac16_tipounidtempo").value            = aRenovacao[oRetorno.contrato.iTipoRenovacao];
  $("ac26_emergencial_select_descr").value = 'Não';

  if ( oRetorno.contrato.lEmergencial ) {
    $("ac26_emergencial_select_descr").value = 'Sim';
  }
  
  $("ac16_dataassinatura").value                = oRetorno.contrato.dtAssinatura.urlDecode();
  $("ac16_periodocomercial_select_descr").value = 'Não';

  if ( oRetorno.contrato.lPeriodoComercial ) {
    $("ac16_periodocomercial_select_descr").value = 'Sim';
  }
  
  $("ac50_sequencial").value           = oRetorno.contrato.iCategoriaAcordo;
  $("ac16_datainicio").value           = oRetorno.contrato.dtInicio.urlDecode();
  $("ac16_datafim").value              = oRetorno.contrato.dtTermino.urlDecode();
  $("diasvigencia").value              = '';
  $("ac16_qtdperiodo").value           = oRetorno.contrato.iQtdPeriodoVigencia;
  $("ac16_tipounidtempoperiodo").value = aPeriodo[oRetorno.contrato.iTipoUnidadeTempoVigencia];
  $("ac16_objeto").value               = oRetorno.contrato.sObjeto.urlDecode();
  $("ac16_resumoobjeto").value         = oRetorno.contrato.sResumoObjeto.urlDecode();

  setTimeout( function() {
                            pesquisaAcordoGrupo( oRetorno.contrato.iGrupo );
                            pesquisaCategoria( oRetorno.contrato.iCategoriaAcordo );
                            $('diasvigencia').value = js_somarDiasVigencia( $("ac16_datainicio").value, $("ac16_datafim").value );
                         }, 300 );
  
  
  $('btnExcluir').disabled = false;
}

/**
 * Remove o acordo selecionado após confirmação
 */
function removerAcordo() {

  var oVariaveisACordo            = new Object();
      oVariaveisACordo.iAcordo    = $("ac16_sequencial").value;
      oVariaveisACordo.sDescricao = $("ac02_descricao").value;
  
  if ( confirm( _M( sCaminhoMensagens+'confirma_exclusao', oVariaveisACordo ) ) ) {

    var oParametro         = new Object();
        oParametro.exec    = 'excluirAcordo';
        oParametro.iAcordo = $("ac16_sequencial").value;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = retornoRemoverAcordo;

    js_divCarregando( _M( sCaminhoMensagens+'excluindo_acordo' ), "msgBox" );
    new Ajax.Request( sRpc, oDadosRequisicao );
  }
}

/**
 * Retorno da remoção do acordo
 */
function retornoRemoverAcordo( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '('+oResponse.responseText+')' );

  alert( oRetorno.message.urlDecode() );

  if ( oRetorno.status == 1 ) {
    
    limpaCampos();
    $('btnExcluir').disabled = true;
  }
}

/**
 * Limpa os campos do formulário
 */
function limpaCampos() {

  $("ac16_sequencial").value                    = '';
  $("ac16_origem").value                        = '';
  $("ac16_acordogrupo").value                   = '';
  $("ac02_descricao").value                     = '';
  $("ac16_numero").value                        = '';
  $("ac16_contratado").value                    = '';
  $("nomecontratado").value                     = '';
  $("ac16_deptoresponsavel").value              = '';
  $("descrdepto").value                         = '';
  $("ac16_acordocomissao").value                = '';
  $("ac08_descricao").value                     = '';
  $("ac16_lei").value                           = '';
  $("ac16_numeroprocesso").value                = '';
  $("ac16_qtdrenovacao").value                  = '';
  $("ac16_tipounidtempo").value                 = '';
  $("ac26_emergencial_select_descr").value      = '';
  $("ac16_dataassinatura").value                = '';
  $("ac16_periodocomercial_select_descr").value = '';
  $("ac50_sequencial").value                    = '';
  $("ac50_descricao").value                     = '';
  $("ac16_datainicio").value                    = '';
  $("ac16_datafim").value                       = '';
  $("diasvigencia").value                       = '';
  $("ac16_qtdperiodo").value                    = '';
  $("ac16_tipounidtempoperiodo").value          = '';
  $("ac16_objeto").value                        = '';
  $("ac16_resumoobjeto").value                  = '';
}

/**
 * Pesquisa a descrição de um acordogrupo
 *
  @param integer iAcordoGrupo - Código de acordogrupo
 */
function pesquisaAcordoGrupo( iAcordoGrupo ) {

  var sUrl = 'func_acordogrupo.php?funcao_js=parent.retornoPesquisaAcordoGrupo&pesquisa_chave='+iAcordoGrupo;
  js_OpenJanelaIframe(
                       '',
                       'db_iframe_acordogrupo',
                       sUrl,
                       'Pesquisar Grupos de Acordo',
                       false
                     );
}

/**
 * Retorno da descrição de acordogrupo
 */
function retornoPesquisaAcordoGrupo() {

  db_iframe_acordogrupo.hide();
  if ( arguments[1] == false ) {
    $("ac02_descricao").value = arguments[0];
  }
}

/**
 * Pesquisa a descrição da categoria
 *
 * @param integer iCategoria - Código da categoria
 */
function pesquisaCategoria( iCategoria ) {

  var sUrl = 'func_acordocategoria.php?funcao_js=parent.retornoPesquisaCategoria&pesquisa_chave='+iCategoria;
  js_OpenJanelaIframe(
                       '',
                       'db_iframe_acordocategoria',
                       sUrl,
                       'Pesquisar Categorias de Acordo',
                       false
                     );
}

/**
 * Retorno da descrição da categoria
 */
function retornoPesquisaCategoria() {

  db_iframe_acordocategoria.hide();
  if ( arguments[1] == false ) {
    $("ac50_descricao").value = arguments[0];
  }
}

pesquisaAcordos();
</script>