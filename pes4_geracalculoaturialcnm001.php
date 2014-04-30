<?php
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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
<script src="scripts/classes/DBViewTipoFiltrosFolha.js" type="text/javascript"></script>
<script src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js" type="text/javascript"></script>
<script src="scripts/widgets/dbtextField.widget.js" type="text/javascript"></script>
<script src="scripts/widgets/DBAncora.widget.js" type="text/javascript"></script>
<script src="scripts/widgets/DBLancador.widget.js" type="text/javascript"></script>
<script src="scripts/widgets/DBAbas.widget.js" type="text/javascript"></script>
<script src="scripts/widgets/DBDownload.widget.js" type="text/javascript"></script>
<script src="scripts/datagrid.widget.js" type="text/javascript"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">

</head>
<body bgcolor="#CCCCCC">
  <BR>
  <div class="container">
    <fieldset id="containerAbas" style="width: 700px">
      <legend>CNM</legend>
    </fieldset>
    <table class="form-container" id="DadosBasicos">
      <tr>
        <td>
          <fieldset>
            <legend>Calculo cnn</legend>

            <table>
              <tr>
                <td id="labelCompetencia"></td>
                <td id="formularioCompetencia"></td>
              </tr>
            </table>
            <table class="form-container">
              <tr>
                <td id="lancadorAssentamentos"></td>
              </tr>
            </table>
            <table class="form-container">
              <tr>
                <td id="lancadorCargo"></td>
              </tr>
            </table>

            <fieldset>
              <legend>Arquivos que serão gerados</legend>

              <table class="form-container" id="arquivosGerados">
                <tr>
                  <td width="5"><input type="checkbox" name="arquivos_gerados[]" value="0" checked="checked">
                  </td>
                  <td><label>Ativos</label>
                  </td>
                  <td><input type="checkbox" name="arquivos_gerados[]" value="1" checked="checked">
                  </td>
                  <td><label>Pensionistas</label>
                  </td>
                </tr>
                <tr>
                  <td><input type="checkbox" name="arquivos_gerados[]" value="2" checked="checked">
                  </td>
                  <td><label>Aposentado por Tempo de Contribuição</label>
                  </td>
                  <td><input type="checkbox" name="arquivos_gerados[]" value="3" checked="checked">
                  </td>
                  <td><label>Aposentado por Idade</label>
                  
                </tr>
                <tr>
                  <td><input type="checkbox" name="arquivos_gerados[]" value="4" checked="checked">
                  </td>
                  <td><label>Aposentado por Invalidez</label>
                  </td>
                  </td>
                  <td><input type="checkbox" name="arquivos_gerados[]" value="5" checked="checked">
                  </td>
                  <td><label>Aposentadoria Compulsória</label>
                  </td>
                </tr>
                <tr>
                  
                </tr>
              </table>

            </fieldset>
          </fieldset>
        </td>
      </tr>
    </table>
  </div>
  <div id="FiltroInstituicao" style="height: 600px;">
    <table class="form-container">
      <tr>
        <td>
          <fieldset>
            <legend>Ativos</legend>
            <div id="gridAtivos"></div>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>
          <fieldset>
            <legend>Inativos</legend>
            <div id="gridInativos"></div>
          </fieldset>
        </td>
      </tr>
    </table>
  </div>
  <div>
    <table style="margin: 0 auto">
      <tr>
        <td align="center">
        	<input name="processar" type="button" onClick="js_processaArquivo()" value="Processar">
        </td>
      </tr>
    </table>
  </div>

  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
<script>
  /**
   * Instância o Input Competencia Folha 
   */
  var oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(true);
  oCompetenciaFolha.renderizaLabel($('labelCompetencia'));
  oCompetenciaFolha.renderizaFormulario($('formularioCompetencia'));
  oCompetenciaFolha.setCallBack(function(){ 
                                      buscaInstituicoes()
                                    });
 
  /**
   * Instância dbAbas
   */
  var oAbas = new DBAbas( $(containerAbas) );
  oAbas.adicionarAba('Dados Básicos', $('DadosBasicos'), true);
  oAbas.adicionarAba('Filtro Instituição', $('FiltroInstituicao'), false);

  /**
   * Instância Lançador de Assentamentos
   */
   var oLancadorAssentamento = new DBLancador('LancadorAssentamentos');
   oLancadorAssentamento.setNomeInstancia('oLancadorAssentamento');
   oLancadorAssentamento.setGridHeight(100);
   oLancadorAssentamento.setLabelAncora('Assentamentos:');
   oLancadorAssentamento.setTextoFieldset('Assentamentos');
   oLancadorAssentamento.setParametrosPesquisa('func_tipoasse.php', 
                                          ['h12_codigo' , 'h12_descr'], 'lConsultaAssentamento=true');
   oLancadorAssentamento.show($('lancadorAssentamentos'));
  
  /**
   * Instância Lançador de Cargos
   */
  var oLancadorCargo = new DBLancador('LancadorCargo');
  oLancadorCargo.setNomeInstancia('oLancadorCargo');
  oLancadorCargo.setGridHeight(100);
  oLancadorCargo.setLabelAncora('Cargo:');
  oLancadorCargo.setTextoFieldset('Cargos de Professores');
  oLancadorCargo.setParametrosPesquisa('func_rhfuncao.php', 
                                         ['rh37_funcao' , 'rh37_descr'], 
                                         'instit=<?=db_getsession("DB_instit")?>');
  oLancadorCargo.show($('lancadorCargo'));

  /**
   * Instância as Grid contendo as instituições Ativas e Inativas
   */
  
  var oGridInativos          = new DBGrid('gridInativos');
  oGridInativos.nameInstance = 'oGridInativos';
  oGridInativos.setCheckbox(0);
  oGridInativos.setCellAlign(new Array('center', 'left'));
  oGridInativos.setCellWidth(new Array('0px', '600px'));
  oGridInativos.setHeader(new Array('Codigo', 'Instituição'));
  oGridInativos.setHeight(100);
  oGridInativos.show($('gridInativos'));
  oGridInativos.clearAll(true);
  
  var oGridAtivos          = new DBGrid('gridAtivos');
  oGridAtivos.nameInstance = 'oGridAtivos';           
  oGridAtivos.setCheckbox(0);      
  oGridAtivos.setCellAlign(new Array('center', 'left'));    
  oGridAtivos.setCellWidth(new Array('0px', '600px'));      
  oGridAtivos.setHeader(new Array('Codigo', 'Instituição'));
  oGridAtivos.setHeight(100);                               
  oGridAtivos.show($('gridAtivos'));                        
  oGridAtivos.clearAll(true);                               
  /**
   * Toda vez que um tipo de arquivo é alterado, é executada a função trocaTipoArquivo().
   */
  var oTiposAqrquivos = $$('#arquivosGerados input[type="checkbox"]');
  oTiposAqrquivos.each (function(oTipoArquivo) {
    
    oTipoArquivo.observe ('change', function() {
      trocaTipoArquivo();       
    });
  });
   
   /**
    * Verifica os tipos de arquivos selecionados para 
    * exibir as Grids de instituições corretamente.
    */
  function trocaTipoArquivo() {

    $('gridAtivos').hide();
    $('gridInativos').hide();
    
    var aTiposSelecionados = $$('#arquivosGerados input[type="checkbox"]:checked');

    aTiposSelecionados.each (function(oInputSelecionado) {

      if (oInputSelecionado.value == 0) {
        $('gridAtivos').show();
      } else {
        $('gridInativos').show();
      };
    });
    
    return;
  }
  
  /**
   * Função responsável por realizar a busca das instituições, 
   * separando elas em Ativas e Inativas em suas respectivas Grids
   */
  function buscaInstituicoes(){

    /**
     * Limpas as linhas atualmente listadas nas 2 grids de instituições.
     */
    oGridAtivos.clearAll(true);
    oGridInativos.clearAll(true);
    
    var oParam = {
      sAcao:           'BuscaInstituicoes',
      iMesCompetencia: $F('mes'),
      iAnoCompetencia: $F('ano')
    }

    var oDadosRequisicao = {
      method : 'post',
      parameters: 'json='+Object.toJSON(oParam),
      asynchronous : false,
      onComplete: function( oRespostaAjax ) {
        
        var oRetorno = eval("(" + oRespostaAjax.responseText + ")");
  
        if (oRetorno.iStatus == 2) {
          throw oRetorno.sMensagem;
        }   
  
        var aInstituicoesAtivos   = oRetorno.aInstituicaoesAtivas;
        var aInstituicoesInativos = oRetorno.aInstituicaoesInativas;
        var iIntituicaoAtual      = <?=db_getsession("DB_instit")?>;
        
        oGridAtivos.clearAll(true);
        /**
         * Percorre todas as instituições ativas adicionando elas na Grid .
         */      
        for (var iInstituicoes in aInstituicoesAtivos) {
  
          var oDados   = aInstituicoesAtivos[iInstituicoes];
          var aCelulas   = [oDados.codigo, oDados.nomeinst.urlDecode()];
          var lChecked = false;

          /**
           * Verifica se a instituição e a intituição do usuario, 
           * se for lChecked recebe true para marcar o checkbox na grid
           */
          if (iIntituicaoAtual == oDados.codigo) {
            lChecked = true;
          }

          oGridAtivos.addRow(aCelulas,null, false, lChecked);
        }
        
        oGridAtivos.renderRows();

        oGridInativos.clearAll(true);
        
        /**
         * Percorre todas as instituições inativas adicionando elas na Grid.
         */
        for (var iInstituicoes in aInstituicoesInativos) {
  
          var oDados = aInstituicoesInativos[iInstituicoes];
          var aCelulas = [oDados.codigo, oDados.nomeinst.urlDecode()];
          var lChecked = false;

          /**
           * Verifica se a instituição e a intituição do usuario, 
           * se for lChecked recebe true para marcar o checkbox na grid
           */
          if (iIntituicaoAtual == oDados.codigo) {
            lChecked = true;
          }
          
          oGridInativos.addRow(aCelulas,null, false, lChecked);
        }
        
        oGridInativos.renderRows();
      }
    }

    var oBusca = new Ajax.Request('pes4_geracalculoaturialcnm.RPC.php', oDadosRequisicao);
    
    return;
  }

  /**
   * Valida a consistência dos dados a serem enviados
   * - Deve ser selecionado pelo menos um tipo de arquivo.
   * - Deve ser selecionado pelo menos uma instituição, para cada tipo de arquivo selecionado(Ativo/Inativo).
   * - Deve ser selecionado pelo menos um assentamento.
   * - Deve ser selecionado pelo menos um cargo. 
   */
  function js_validaDados() {

    /**
     * Deve ser selecionado pelo menos um tipo de arquivo
     */
    if ($$('#arquivosGerados input[type="checkbox"]:checked').length == 0) {

      alert('Por favor, selecione pelo menos um tipo de arquivo');
      return false;
    }

    /**
     * Deve ser selecionado pelo menos uma instituição, 
     * para cada tipo de arquivo selecionado(Ativo/Inativo).
     */
    var oTiposSelecionados = $$('#arquivosGerados input[type="checkbox"]:checked');

    for (var iTipoSelecionado = 0; iTipoSelecionado < oTiposSelecionados.length; iTipoSelecionado++) {
      
      oInputSelecionado = oTiposSelecionados[iTipoSelecionado];

      if (oInputSelecionado.value == 0) {

        if (oGridAtivos.getSelection().length == 0) {
          
          alert('Por favor, selecione pelo menos uma instituição Ativa');
          return false;    
        }
      } else {

        if (oGridInativos.getSelection().length == 0) {

          alert('Por favor, selecione pelo menos uma instituição Inativa');
          return false;
        }        
      };
    }

    return true;
  }
  
  /**
   * Realiza o processamento dos Arquivos a partir dos filtros selecionados.
   */
  function js_processaArquivo() {

    /**
     * FUnção responsável por validas a consistência dos dados
     */
    if (!js_validaDados()) {
     return false;
    }

    /**
     * Monta um array com os Arquivos Selecionados 
     */
    var oArquivosSelecionados = $$('#arquivosGerados input[type="checkbox"]:checked');
    var aArquivosSelecionados = new Array();
    for (var iTipoSelecionado = 0; iTipoSelecionado < oArquivosSelecionados.length; iTipoSelecionado++) {

      oInputSelecionado = oArquivosSelecionados[iTipoSelecionado];
      aArquivosSelecionados[iTipoSelecionado] = oInputSelecionado.value;
    };

    /**
     * Monta um array com os Assentamentos
     */
     var oAssentamentos = oLancadorAssentamento.getRegistros();
     var aAssentamentos = new Array();
     for (var iAssentamentos = 0; iAssentamentos < oAssentamentos.length; iAssentamentos++) {
       aAssentamentos[iAssentamentos] = oAssentamentos[iAssentamentos]['sCodigo'];
     }
    
    /**
     * Monta um array com os Cargos
     */
     var oCargos = oLancadorCargo.getRegistros();
     var aCargos = new Array();
     for (var iCargos = 0; iCargos < oCargos.length; iCargos++) {
       aCargos[iCargos] = oCargos[iCargos]['sCodigo'];
     }
     
    /**
     * Monta um array com as intituições Ativas
     */
     var oInstituicoesAtivas = oGridAtivos.getSelection();
     var aInstituicoesAtivos = new Array();
     for (var iIntituicoesAtivas = 0; iIntituicoesAtivas < oInstituicoesAtivas.length; iIntituicoesAtivas++) {
       aInstituicoesAtivos[iIntituicoesAtivas] = oInstituicoesAtivas[iIntituicoesAtivas][1];
     }

    /**
     * Monta um array com as intituições Inativas
     */
     var oInstituicoesInativas = oGridInativos.getSelection();
     var aInstituicoesInativos = new Array();
     for (var iInstituicoesInativas = 0; iInstituicoesInativas < oInstituicoesInativas.length; iInstituicoesInativas++) {
       aInstituicoesInativos[iInstituicoesInativas] = oInstituicoesInativas[iInstituicoesInativas][1];
     }
    
    var oParam = {
      sAcao:                 'processar',
      iAno:                  $F('ano'),
      iMes:                  $F('mes'),
      aAssentamentos:        aAssentamentos,
      aCargoProfessores:     aCargos,
      aArquivos:             aArquivosSelecionados,
      aInstituicoesAtivos:   aInstituicoesAtivos,
      aInstituicoesInativos: aInstituicoesInativos,
    }

    var oDadosRequisicao = {
        
      method : 'post',
      parameters: 'json='+Object.toJSON(oParam),
      asynchronous : false,
      onComplete: function( oRespostaAjax ) {

        js_removeObj('msgBox');
        var oRetorno = eval("(" + oRespostaAjax.responseText + ")");
    
        if (oRetorno.iStatus == 2) {
          alert(oRetorno.sMensagem.urlDecode());
        }   

        var oDBDownload = new DBDownload();
        var aArquivos   = oRetorno.aArquivos;

        for (var iArquivo = 0; iArquivo < aArquivos.length; iArquivo++) {
          
          var sArquivo = aArquivos[iArquivo];

          aLabel = sArquivo.urlDecode().split("CNM_");
          sLabel = aLabel[aLabel.length - 1];
          oDBDownload.addFile(sArquivo.urlDecode(), sLabel);
				}
        
        oDBDownload.show();    
      }
    }

    js_divCarregando("Gerando arquivos de servidores.\nAguarde", 'msgBox');
    var oBusca = new Ajax.Request('pes4_geracalculoaturialcnm.RPC.php', oDadosRequisicao);
  }

  /**
   * Função executado no onLoad da pagina.
   */
  (function() {
    
    buscaInstituicoes(); 
  })();
</script>
</html>