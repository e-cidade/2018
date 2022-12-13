<?php
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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('r44_selec');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');

db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php 
    db_app::load("estilos.css");
    db_app::load("grid.style.css");
    db_app::load("prototype.js");
    db_app::load("scripts.js");
    db_app::load("strings.js");
    db_app::load("datagrid.widget.js");
    db_app::load("dbtextField.widget.js");
    db_app::load("dbcomboBox.widget.js");
    db_app::load("DBLancador.widget.js");
    db_app::load("DBAncora.widget.js");
  ?>
  <style>
    #intervalo input[type="text"]{ 
      width: 90px;
    }
    select { 
      width: 100%;
    }
  </style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<br /><br />
<form name="form1" method="post" action="">
<table align="center" border="0" width="650">
  <tr>
    <td>
      <fieldset>
        <legend>
          <b>Escala de Férias</b>
        </legend>
       <table>
          <tr>
            <td align="left" nowrap title="Digite o Ano / Mes de competência" >
               <label for="periodovencido"><strong>Períodos Vencidos até:</strong></label>
            </td>
            <td>
              <?php db_inputdata('periodovencido',null, null, null, true, 'text', 1); ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="r44_selec">
              <?php db_ancora("Seleção", "js_pesquisaSelecao(true)", 1); ?>
              </label>
            </td>
            <td> 
              <?php
                db_input('r44_selec', 10,  1, true, 'text', "", "onchange='js_pesquisaSelecao(false)'");
                db_input('r44_des',   51, "", true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="regime">
                <strong>Regime: </strong>
              </label>
            </td>
            <td align="left">
              <?php
                $regime = array( 
                  "0" => "Todos",
                  "1" => "Estatutário",
                  "2" => "CLT", 
                  "3" => "Extra Quadro"
                );
                db_select('regime', $regime, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="tiporelatorio">
                <strong>Tipo de Relatório: </strong>
              </label>
            </td>
            <td align="left">
              <?
                $tipo_relatorio = array(
                                        "geral"          => "Geral",
                                        "lotacao"        => "Lotação",
                                        "orgao"          => "Órgão", 
                                        "matricula"      => "Matrícula", 
                                        "locaistrabalho" => "Locais de Trabalho"
                                       );
                db_select('tiporelatorio',$tipo_relatorio ,true,1, "onChange='js_tiporelatorio()'");                
              ?>
            </td>
          </tr>
          <tr style="display: none;" id="filtros">
            <td>
              <label for="tipofiltro">
                <strong>Tipo de Filtro: </strong>
              </label>
            </td>
            <td align="left">
              <?
                $tipo_filtro = array(
                                     "intervalo"=>"Intervalo",
                                     "selecionado"=>"Selecionado"
                                    );
                db_select('tipofiltro',$tipo_filtro ,true,1, "onChange='js_filtros()'");                
              ?>
            </td>
          </tr>
          
          <tr id="intervalo" style="display: none;">
            <td>
              <label for="intervaloinicial">
                <strong>De<strong>
              </strong>
            </td>
            <td align="left" nowrap="">
              <input type="text" id="intervaloinicial" value="" />
              <strong>&nbsp;&nbsp;&nbsp;a&nbsp;&nbsp;&nbsp;</strong>
              <input type="text" id="intervalofinal" value="" />
            </td>
          </tr>

          <tr id="ctnSelecionados" style="display:none;">
            <td colspan="2" width="640">
              <div style="display: none;" class="ctnLancador" id='ctnLancadorLotacao'></div>
              <div style="display: none;" class="ctnLancador" id='ctnLancadorOrgao'></div>
              <div style="display: none;" class="ctnLancador" id='ctnLancadorMatricula'></div>
              <div style="display: none;" class="ctnLancador" id='ctnLancadorLocaisTrabalho'></div>
            </td>
          </tr>

          <tr>
            <td>
              <label for="tipoordem">
                <strong>Tipo de Ordem: </strong>
              </label>
            </td>
            <td align="left">
              <?
                $tipo_ordem = array("numerica"=>"Numérica",
                                    "alfabetica"=>"Alfabética");
                db_select('tipoordem',$tipo_ordem ,true,1);               
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="imprimeafastados">
                <strong>Imprime Afastados: </strong>
              </label>
            </td>
            <td align="left">
              <?
                $imprime_afastados = array("false" => "Não", "true" => "Sim");
                db_select('imprimeafastados',$imprime_afastados ,true,1);               
              ?>
            </td>
          </tr>
      </table>
     </fieldset>
     </td>
  </tr>    
  <tr>
    <td colspan="2" align="center"> 
      <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();">
    </td>
  </tr>
</form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

/**
 * Instancias dos lancadores
 * usados para pegar os registros lancados por cada tipo de relatorio 
 *
 * @see js_getSelecionados
 */
var aInstanciaLancador = ['lotacao', 'orgao', 'matricula', 'locaistrabalho'];

/**
 * Monta uma instancia do componente DBLancador para cada filtro de pesquisa
 */
js_lotacao();
js_orgao();
js_matricula();
js_locaisTrabalho();

/**
 * Verifica o tipo de relatorio em caso de F5 
 */
js_tiporelatorio();

/**
 * Busca os registros selecionados/lancados na grid do DBLancador 
 * 
 * @access public
 * @return string com os codigos separados por virgula
 */
function js_getSelecionados() {

  /**
   * Tipo de relatorio escolhido 
   * lotacao, orgao, matricula e locais de trabalho
   */
  var sTipoRelatorio = $F('tiporelatorio');
  var aRegistros     = aInstanciaLancador[sTipoRelatorio].getRegistros();
  var iRegistros     = aRegistros.length;
  var aSelecionados  = [];

  if ( iRegistros <= 0 ) {
    return null;
  }

  for ( iRegistro = 0; iRegistro < iRegistros; iRegistro++ ) {

    var sCodigo = aRegistros[iRegistro].sCodigo;
    aSelecionados.push(sCodigo);
  }

  return aSelecionados.join(','); 
}

/**
 * Mostra selecionado pelo tipo do relatorio
 * 
 * @access public
 * @return void
 */
function js_selecionados() {

  /**
   * Esconde outros tipos de relatorio 
   */
  js_escondeSelecionados();

  /**
   * Tipo de relatorio escolhido 
   */
  var sTipoRelatorio = $F('tiporelatorio');

  /**
   * Objeto com tipos de relatorio para ter o container 
   * usado para mostrar Lancador de pesquisa
   */
  var oTipoRelatorio = {
    'lotacao'        : 'ctnLancadorLotacao',
    'orgao'          : 'ctnLancadorOrgao',
    'matricula'      : 'ctnLancadorMatricula',
    'locaistrabalho' : 'ctnLancadorLocaisTrabalho'
  };

  $(oTipoRelatorio[sTipoRelatorio]).show();
}

/**
 * Esconde todas as pesquisas pelo tipo de relatorio 
 * 
 * @access public
 * @return void
 */
function js_escondeSelecionados() {

  var aLancadores = ['ctnLancadorLotacao', 'ctnLancadorOrgao', 'ctnLancadorMatricula', 'ctnLancadorLocaisTrabalho'];

  aLancadores.each(function(sLancador) {
    $(sLancador).hide();
  });
}

/**
 * Monta lancador para pesquisar lotacao 
 * 
 * @access public
 * @return void
 */
function js_lotacao() {

  oLancadorLotacao = new DBLancador('LancadorLotacao');
  oLancadorLotacao.setNomeInstancia('oLancadorLotacao');
  oLancadorLotacao.setLabelAncora('Lotação:');
  oLancadorLotacao.setParametrosPesquisa('func_rhlota.php', ['r70_codigo' , 'r70_descr'], 'instit=' + <?php echo db_getsession('DB_instit'); ?>);
  oLancadorLotacao.show($('ctnLancadorLotacao'));

  /**
   * Adiciona instancia do lancador de lotacoes para depois buscar registros lancados 
   */
  aInstanciaLancador.lotacao = oLancadorLotacao;
}

/**
 * Monta lancador para pesquisar orgao 
 * 
 * @access public
 * @return void
 */
function js_orgao() {

  oLancadorOrgao = new DBLancador('LancadorOrgao');
  oLancadorOrgao.setNomeInstancia('oLancadorOrgao');
  oLancadorOrgao.setLabelAncora('Órgão:');
  oLancadorOrgao.setParametrosPesquisa('func_orcorgao.php', ['o40_orgao' , 'o40_descr'], 'instit=' + <?php echo db_getsession('DB_instit'); ?>);
  oLancadorOrgao.show($('ctnLancadorOrgao'));
  
  /**
   * Adiciona instancia do lancador de orgaos para depois buscar registros lancados 
   */
  aInstanciaLancador.orgao = oLancadorOrgao;
}

/**
 * Monta lancador para pesquisar matricula 
 * 
 * @access public
 * @return void
 */
function js_matricula() {

  oLancadorMatricula = new DBLancador('LancadorMatricula');
  oLancadorMatricula.setNomeInstancia('oLancadorMatricula');
  oLancadorMatricula.setLabelAncora('Matrícula:');
  oLancadorMatricula.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist' , 'z01_nome'], 'instit=' + <?php echo db_getsession('DB_instit'); ?>);
  oLancadorMatricula.show($('ctnLancadorMatricula'));

  /**
   * Adiciona instancia do lancador de matriculas para depois buscar registros lancados 
   */
  aInstanciaLancador.matricula = oLancadorMatricula;
}

/**
 * Monta lancador para pesquisar locais de trabalho 
 * 
 * @access public
 * @return void
 */
function js_locaisTrabalho() {

  oLancadorLocaisTrabalho = new DBLancador('LancadorLocaisTrabalho');
  oLancadorLocaisTrabalho.setNomeInstancia('oLancadorLocaisTrabalho');
  oLancadorLocaisTrabalho.setLabelAncora('Locais de trabalho:');
  oLancadorLocaisTrabalho.setParametrosPesquisa('func_rhlocaltrab.php', ['rh55_codigo' , 'rh55_descr'], 'instit=' + <?php echo db_getsession('DB_instit'); ?>);
  oLancadorLocaisTrabalho.show($('ctnLancadorLocaisTrabalho'));

  /**
   * Adiciona instancia do lancador de locais de trabalho para depois buscar registros lancados 
   */
  aInstanciaLancador.locaistrabalho = oLancadorLocaisTrabalho;
}

/**
 * Tipo de relatorio, mostra/esconde filtros
 * 
 * @access public
 * @return void
 */
function js_tiporelatorio() {

  /**
   * Diferente de geral, mostra filtros 
   */
  if ( $F('tiporelatorio') != 'geral') {

    $('filtros').show();    
    js_filtros();
    return;
  } 

  /**
   * tipo relatorio = geral
   * Esconde filtros 
   */
  $('filtros').hide();
  $('intervalo').hide();
  js_escondeSelecionados();
}

/**
 * Filtros de pequisa 
 * Mostra campos para pesquisa pelo tipo de filtro, intervalo ou selecionado
 * 
 * @access public
 * @return void
 */
function js_filtros() {

  /**
   * Intervalo, mostra dois campos com intervalo dos sequencias a ser pesquisado 
   */
  if ($F('tipofiltro') == 'intervalo') {

    $('intervalo').show();    
    $('ctnSelecionados').hide();    
    return;
  }  

  /**
   * Selecionado, mostra grid com lockup de pesquisa 
   */
  if ( $F('tipofiltro') == 'selecionado') {

    $('intervalo').hide();
    $('ctnSelecionados').show();    
    js_selecionados();
  }
}

function js_pesquisaSelecao(mostra) {
  
  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_geraform_mostraselecao1|r44_selec|r44_descr&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true);
  }else{
    if (document.form1.r44_selec.value != "") {
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_selecao','func_selecao.php?pesquisa_chave='+document.form1.r44_selec.value+'&funcao_js=parent.js_geraform_mostraselecao&instit=<?=db_getsession("DB_instit")?>','Pesquisa',false);
    }else{
      document.form1.r44_des.value = ""; 
    }
  }
}

function js_geraform_mostraselecao(sDescricao, lErro) {

  if (lErro) { 

    document.form1.r44_selec.value = '';
    document.form1.r44_selec.focus(); 
  }

  document.form1.r44_des.value = sDescricao; 
}

function js_geraform_mostraselecao1(chave1,chave2) {
  
  document.form1.r44_selec.value = chave1;
  if(document.form1.r44_des){
    document.form1.r44_des.value = chave2;
  }
  db_iframe_selecao.hide();
}

/**
 * Abre janela com relatorio 
 * 
 * @access public
 * @return bool
 */
function js_emite(){

  /**
   * Valida se foi passado periodo 
   */
  if ( $F('periodovencido') == '' ) {  

    alert('Informe o períodos vencido.');
    return false;
  }

  var sUrl          = 'pes2_escalaferias002.php?periodo=' + $F('periodovencido');
  var sWidth        = document.body.clientWidth;
  var sHeight       = document.body.clientHeight;
  var sFiltros      = '';
  var sSelecionados = null;

  if ( $F('tiporelatorio') != 'geral' ) {

    /**
     * tipo de filtro selecionado
     * Valida se foi passado algum registro 
     */
    if ( $F('tipofiltro') == 'selecionado' ) {

      /**
       * String com os codigos dos registros selecionados 
       */
      sSelecionados = js_getSelecionados();

      if ( sSelecionados == null ) {

        alert("Filtro do tipo 'selecionado'.\n\nSelecione um registro.");
        return false;
      }

      sFiltros += '&sSelecionados=' + sSelecionados;
    }

    if ( $F('tipofiltro') == 'intervalo' ) {

      var iIntervarloInicial = $F('intervaloinicial');
      var iIntervarloFinal   = $F('intervalofinal');

      if ( iIntervarloFinal == '' || iIntervarloInicial == '' ) {

        alert("Filtro do tipo 'intervalo'.\n\nInforme o intervalo inicial e final.");
        return false;
      }

      sFiltros += '&iIntervaloInicial=' + iIntervarloInicial;
      sFiltros += '&iIntervaloFinal=' + iIntervarloFinal;
    }
  }
  
  /**
   * Selecao 
   */
  if ( $F('r44_selec') != '' ) {
    sFiltros += '&iSelecao=' + $F('r44_selec');
  }

  sFiltros += '&iRegime=' + $F('regime');
  sFiltros += '&sTipoRelatorio=' + $F('tiporelatorio');
  sFiltros += '&sTipoFiltro=' + $F('tipofiltro');
  sFiltros += '&sTipoOrdem=' + $F('tipoordem');
  sFiltros += '&lImprimeAfastados=' + $F('imprimeafastados');
  sUrl     += sFiltros;

  oJanela = window.open(sUrl, '', 'width=' + sWidth +',height='+ sHeight +',scrollbars=1,location=0 ');
  oJanela.moveTo(0,0);
}
</script>