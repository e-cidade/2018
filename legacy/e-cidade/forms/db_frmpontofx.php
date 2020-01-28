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

//MODULO: pessoal
require_once(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpontofx->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh27_limdat");
$clrotulo->label("rh27_descr");
$clrotulo->label("rh27_form");
$clrotulo->label("r29_tpp");
$clrotulo->label("r70_descr");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
$iPonto = "";

/**
 * Variável responsável por exibir os botões do formulário
 */
$input_botoes = true;

/*
 * Alteração do valor da label. 
 * OBS.: Se este valor for aplicado em demais formulário deverá substituir no BD
 */
$Sr90_regist  = "Campo matrícula";
$lLimiteDatas = false;
switch ($ponto) {

  case 'fx':

    $dponto       = " Ponto fixo";
    $iPonto       = 10;

    // if ($rh27_periodolancamento == 't') {
      $lLimiteDatas = true;
    // }
    break;

  case 'fa':
    $dponto = " Ponto de Adiantamento";
    $iPonto = 2;

    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

      try {
        
        /**
         * Verifica se o ponto já foi inicializado.
         */
        if (FolhaPagamentoSalario::hasFolha()) {

          $oCompetencia = new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());

          /**
           * Verifica se NÃO existe folha adiantamento.
           */
          if (!FolhaPagamentoAdiantamento::hasFolha()) {

            /**
             * Cria folha adiantamento.
             */
            $oFolhaAdiantamento = new FolhaPagamentoAdiantamento();

            $oFolhaAdiantamento->setNumero(0);
            $oFolhaAdiantamento->setCompetenciaFolha($oCompetencia);
            $oFolhaAdiantamento->setCompetenciaReferencia($oCompetencia);
            $oFolhaAdiantamento->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit')));
            $oFolhaAdiantamento->setDescricao("Folha adiantamento - {$oCompetencia->getAno()}/{$oCompetencia->getMes()}");
            $oFolhaAdiantamento->salvar();

          /**
           * Verifica se existe folha adiantamento aberta.
           */
          } elseif (!FolhaPagamentoAdiantamento::hasFolhaAberta($oCompetencia)) {

            throw new Exception(_M(FolhaPagamentoAdiantamento::MENSAGENS . "fechamento_folha_fechada"));
          }
        } else {

          throw new DBException(_M(FolhaPagamento::MENSAGENS . "ponto_nao_inicializado"));
        }
      } catch (Exception $e) {
        
        $db_opcao     = 3;
        $input_botoes = false;
        db_msgbox($e->getMessage());
      }
    }
    break;

  case 'com':
    $dponto = " Ponto Complementar por Registro";
    $iPonto = 8;

    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

      try {

        $lComplementarAberta = FolhaPagamentoComplementar::hasFolhaAberta(
          new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha())
        );

        if ($lComplementarAberta) {

          $oFolhaPagamentoComplementar = FolhaPagamentoComplementar::getFolhaAberta();

          $rh141_codigo    = $oFolhaPagamentoComplementar->getNumero();
          $rh141_descricao = $oFolhaPagamentoComplementar->getDescricao();
          $rh141_anoref    = $oFolhaPagamentoComplementar->getCompetenciaReferencia()->getAno();
          $rh141_mesref    = $oFolhaPagamentoComplementar->getCompetenciaReferencia()->getMes();
        } else {

          $db_opcao     = 3;
          $input_botoes = false;
          db_msgbox(_M(FolhaPagamentoComplementar::MENSAGENS . "fechamento_folha_fechada"));
        }
      } catch(DBException $e) {

          $db_opcao     = 3;
          $input_botoes = false;
          db_msgbox($e->getMessage());
      }
    }
    break;

  case 'fs':

    $dponto       = " Ponto de Salário / Suplementar";
    $iPonto       = 1;

    // if ($rh27_periodolancamento == 't') {
      $lLimiteDatas = true;
    // }

    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

      try {

        /**
         * Verifica se o ponto já foi inicializado.
         */
        if (FolhaPagamentoSalario::hasFolha()) {

          /**
           * Verifica se existe folha salario aberta.
           */
          $lSalarioAberta = FolhaPagamentoSalario::hasFolhaAberta(
            new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha())
          );

          /**
           * Verifica se o salário já encontra-se fechado.
           */
          if (!$lSalarioAberta) {

            /**
             * Verifica se existe folha suplementar aberta.
             */
            $lSuplementarAberta = FolhaPagamentoSuplementar::hasFolhaAberta(
              new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha())
            );

            if ($lSuplementarAberta) {

              /**
               * Valida rescisão
               */
              if (isset($r90_regist)) {
                
                $oDaoPessoalMov = new cl_rhpessoalmov();
                if ($oDaoPessoalMov->isRescindido($r90_regist)) {
                  $db_opcao = 3;
                }
              }

              /**
               * Pega a folha suplementar aberta.
               */
              $oFolhaPagamentoSuplementar = FolhaPagamentoSuplementar::getFolhaAberta();

              /**
               * Atribui dados as variáveis que serão
               * utilizadas para popular o formulário.
               */
              $rh141_codigo    = $oFolhaPagamentoSuplementar->getNumero();
              $rh141_descricao = $oFolhaPagamentoSuplementar->getDescricao();
              $rh141_anoref    = $oFolhaPagamentoSuplementar->getCompetenciaReferencia()->getAno();
              $rh141_mesref    = $oFolhaPagamentoSuplementar->getCompetenciaReferencia()->getMes();
            } else {

              throw new DBException(_M(FolhaPagamentoSuplementar::MENSAGENS . "fechamento_folha_fechada"));              
            }
          }
        } else {

          throw new DBException(_M(FolhaPagamento::MENSAGENS . "ponto_nao_inicializado"));
        }
      } catch(DBException $e) {

        $db_opcao     = 3;
        $input_botoes = false;
        db_msgbox($e->getMessage());
      }
    }
    break;

  case 'f13':
    $dponto = " Ponto de Décimo Terceiro";
    $iPonto = 5;

    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

      try {
        
        /**
         * Verifica se o ponto já foi inicializado.
         */
        if (FolhaPagamentoSalario::hasFolha()) {

          $oCompetencia = new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());

          /**
           * Verifica se NÃO existe folha 13º salário.
           */
          if (!FolhaPagamento13o::hasFolha()) {

            /**
             * Cria folha 13º salário.
             */
            $oFolha13o = new FolhaPagamento13o();

            $oFolha13o->setNumero(0);
            $oFolha13o->setCompetenciaFolha($oCompetencia);
            $oFolha13o->setCompetenciaReferencia($oCompetencia);
            $oFolha13o->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit')));
            $oFolha13o->setDescricao("Folha 13º Salário - {$oCompetencia->getAno()}/{$oCompetencia->getMes()}");
            $oFolha13o->salvar();

          /**
           * Verifica se NÃO existe folha 13º salário aberta.
           */
          } elseif (!FolhaPagamento13o::hasFolhaAberta($oCompetencia)) {

            throw new Exception('A folha 13º salário encontra-se fechada.');
          }
        } else {

          throw new DBException(_M(FolhaPagamento::MENSAGENS . "ponto_nao_inicializado"));
        }
      } catch (Exception $e) {
        
        $db_opcao     = 3;
        $input_botoes = false;
        db_msgbox($e->getMessage());
      }
    }
    break;

  case 'fe':
    $dponto = " Ponto de Férias";
    $iPonto = 3;

    // Verifica se a folha de salário e complementar estão fechadas bloqueia o ponto de féria
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()  && ( !FolhaPagamentoSalario::hasFolhaAberta() && !FolhaPagamentoComplementar::hasFolhaAberta())) {

      $db_opcao     = 3;
      $input_botoes = false;
      db_msgbox('Não é possível cadastrar férias, pois todas as folhas disponíveis estão fechadas.');
    }
    break;

  case 'fr':
    $dponto = " Ponto de Rescisão";
    $iPonto = 4;

    // Bloqueio da liberação do contracheque no DBPref
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

      try {
        FolhaPagamentoRescisao::verificaLiberacaoDBPref();
      } catch ( Exception $e ) {

        $db_opcao     = 3;
        $input_botoes = false;
        db_msgbox($e->getMessage());
      }
    }
    break;
}

?>

<script>

var MENSAGENS_VALIDA_LIMITE_RUBRICA = "recursoshumanos.pessoal.pes4_valida_limite_rubrica.";

function js_getDadosPadroes() {
  
  var sUrl         = 'pes1_rhrubricas.RPC.php';
  var oParametros  = new Object();
  var msgDiv       = "Pesquisando dados padrão da rubrica. Aguarde...";
  
  oParametros.sExecucao      = 'BuscaPadroesRubrica';  
  oParametros.sCodigoRubrica = $F('r90_rubric');
  
  js_divCarregando(msgDiv,'msgBox');
   
  var oAjax = new Ajax.Request(
    sUrl, 
    {
      method     : 'post',
      parameters : 'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoDadosPadroes
    }
  );   
}

function js_retornoDadosPadroes(oAjax) {

  js_removeObj('msgBox');

  var oRetorno         = eval("("+oAjax.responseText+")");
  <?php if($db_opcao == 1): ?>
  $('r90_quant').value = oRetorno.nQuantidadePadrao;
  $('r90_valor').value = oRetorno.nValorPadrao;
  <?php endif; ?>
  $('r90_valor').setAttribute("limite", oRetorno.nValorLimite);
  $('r90_valor').setAttribute("tipo-bloqueio", oRetorno.sTipoBloqueio);
  
  $('r90_quant').setAttribute("limite", oRetorno.nQuantidadeLimite);
  $('r90_quant').setAttribute("tipo-bloqueio", oRetorno.sTipoBloqueio);
}

function validarValor(event) {

  var tipoBloqueio = this.getAttribute('tipo-bloqueio');
  var valorLimite  = this.getAttribute('limite');

  $('db_opcao').disabled = false;

  /**
   * Se o tipo de bloqueio for nenhum, ou  se o valor digitado for 0 ou vazio ou se a rubrica não foi selecionada
   * Não faz nada 
   */
  if (tipoBloqueio === 'N' || !+this.value || !$F('r90_rubric')  || !+valorLimite ) {
    return;
  }

  if (+valorLimite > 0 && +valorLimite < +this.value) {

    alert(_M(MENSAGENS_VALIDA_LIMITE_RUBRICA + 'limite_valor_excedido', { 'valor' : valorLimite }));

    if (tipoBloqueio === 'B') {
      $('db_opcao').disabled = true;
    }

    return false;  
  }


}

function validarQuantidade(event) {
  var tipoBloqueio = this.getAttribute('tipo-bloqueio');
  var valorLimite  = this.getAttribute('limite');

  $('db_opcao').disabled = false;

  /**
   * Se o tipo de bloqueio for nenhum, ou  se o valor digitado for 0 ou vazio ou se a rubrica não foi selecionada
   * Não faz nada 
   */
  if (tipoBloqueio === 'N' || !+this.value || !$F('r90_rubric')  ) {
    return;
  }

  if (+valorLimite > 0 && +valorLimite < +this.value) {

    alert(_M(MENSAGENS_VALIDA_LIMITE_RUBRICA + 'limite_quantidade_excedido', { 'quantidade' : valorLimite }));

    if (tipoBloqueio === 'B') {
      $('db_opcao').disabled = true;
    }

    return false;  
  }

}
 

function js_calcular(iMatricula, iPonto){

  /**
   *  REQUISITOS PARA CALCULO de FOLHA:
   *  - tipo de folha  = iPonto
   *  - tipo de resumo = m (matricula)
   *  - tipo de filtro = s (selecionados)
   *  - db_debug       = 'false'
   */
  
  /**
   * @todo comentar
   */
  if ( document.getElementById('r90_regist').value == null || document.getElementById('r90_regist').value == '') {
  
    alert('Selecione uma matrícula.');
    return false;
  } else {

    if (iPonto == 3 && !js_validaComparativoFerias(iMatricula)) {    
      return false;
    }

    var sQuery  = "?campo_auxilio_carg=";
        sQuery += "&campo_auxilio_loca=";
        sQuery += "&campo_auxilio_orga=";
        sQuery += "&campo_auxilio_recu=";
        sQuery += "&campo_auxilio_rubr=";
        sQuery += "&faixa_lotac=";
        sQuery += "&opcao_gml=m";
        sQuery += "&opcao_filtro=s";
        sQuery += "&faixa_regis=<?php echo $sMatriculas?>";
        sQuery += "&opcao_geral="+iPonto;
        sQuery += "&sCallBack=js_callbackcalculo()";

    js_OpenJanelaIframe("",'db_iframe_ponto','pes4_gerafolha002.php'+sQuery,'Cálculo Financeiro',true);
  } 
}

function js_callbackcalculo() {
  db_iframe_ponto.hide();

  /**
   * Verificamos se esta sendo execurtado a partir da consulta financeira
   */

  if (typeof parent.formatu !== 'undefined') {
    parent.location.href = 'pes3_gerfinanc001.php?matricula='+document.getElementById('r90_regist').value;
  }
}

/**
 * Realiza a validação para o comparativo d férias, verificando se existe 
 * o cálculo de salário quando estiver sendo cálculado o ponto de férias
 * @param  {Int} iMatricula 
 * @return {boolean}           
 */
function js_validaComparativoFerias(iMatricula) {

  var sUrlRPC = "pes4_rhgeracaofolha.RPC.php";

  var oParam            = new Object();
      oParam.exec       = 'validaComparativoFerias';
      oParam.iMatricula = iMatricula;
  var lRetorno = null;

  var oAjax = new Ajax.Request(sUrlRPC, {
      method      : 'post',
      parameters  : 'json='+Object.toJSON(oParam),
      asynchronous: false,
      onComplete: function(oAjax) {

        var oRetorno = eval("("+oAjax.responseText.urlDecode()+")");

        if (oRetorno.status == 2) {

          alert(oRetorno.message);
          lRetorno =  false;
        } else {
          lRetorno =   true;
        }
      }
  });
  
  return lRetorno;
}

function js_consultar(iMatricula){
   
   //pes3_gerfinanc001.php
   if ( document.getElementById('r90_regist').value == null || document.getElementById('r90_regist').value == '') {
   
     alert('Selecione uma matrícula.');
     return false;
   } else {
     document.location.href='pes3_gerfinanc001.php?lConsulta=1&iMatricula='+iMatricula;
   }  
}

</script> 

<form name="form1" method="post" class="container" action="">

<fieldset>

  <legend>
    <?php echo $dponto; ?>
  </legend>

  <?php

  if ($ponto == "com" && DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

    echo '<fieldset id="toogle">';
    echo "  <legend>Dados da Folha Complementar</legend>";      
    $sDBOpcaoAnterior            = $db_opcao;
    $db_opcao                    = 3;
    include(modification("forms/db_frmrhfolhapagamento.php"));
    $db_opcao                    = $sDBOpcaoAnterior;
    echo "</fieldset>";
    
  } elseif(isset($oFolhaPagamentoSuplementar)) {
    echo '<fieldset id="toogle">';
    echo "  <legend>Dados da Folha Suplementar</legend>";      
    $sDBOpcaoAnterior            = $db_opcao;
    $db_opcao                    = 3;
    include(modification("forms/db_frmrhfolhapagamento.php"));
    $db_opcao                    = $sDBOpcaoAnterior;
    echo "</fieldset>";
  }
  ?>

<fieldset>
<legend>Dados do Servidor</legend>
<table>
<!-- @TODO REMOVER LINHA -->
  <tr>
    <td title="Digite o Ano / Mes de competência" >
      <strong>Competência:</strong> 
      
    </td>
    <td>
      <?php
      db_input('DBtxt23', 4, $IDBtxt23, true, 'text', 3, "onchange='js_submita();'", 'r90_anousu');
      echo "/";
      db_input('DBtxt25', 2, $IDBtxt25, true, 'text', 3, "onchange='js_submita();'", 'r90_mesusu');

      db_input('ponto', 15, 0, true, 'hidden', 3, "");
      db_input('data_de_admissao', 15, 0, true, 'hidden', 3, "");
      db_input('rh27_form', 15, $Irh27_form, true, 'hidden', 3, "");
      ?>
    </td>
  </tr>
  <tr>
    <td title="<?=@$Tr90_regist?>">
      <?
      db_ancora(@ $Lr90_regist, "js_pesquisar90_regist(true);", $db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('r90_regist', 8, $Ir90_regist, true, 'text', $db_opcao, " onchange='js_pesquisar90_regist(false);' tabIndex=1 ")
      ?>
      <?
      db_input('z01_nome', 60, $Iz01_nome, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td title="<?=@$Tr90_lotac?>">
      <?
      db_ancora(@ $Lr90_lotac, "js_pesquisar90_lotac(true);", 3);
      ?>
    </td>
    <td> 
      <?
      db_input('r90_lotac', 8, $Ir90_lotac, true, 'text', 3, " onchange='js_pesquisar90_lotac(false);'")
      ?>
      <?
      db_input('r70_descr', 60, $Ir70_descr, true, 'text', 3, '');
      ?>
    </td>
  </tr>  
</table>
</fieldset>
       <fieldset>
        <legend>Rubrica</legend>
        <table border="0">
          <tr>
            <td align="left" nowrap title="<?=@$Tr90_rubric?>">
              <?
              db_ancora(@ $Lr90_rubric, "js_pesquisar90_rubric(true);", (($db_opcao==1)?"1":"3"));
              ?>
            </td>
              <?php
              if ($lLimiteDatas) {
                echo "<td align='left' class='periodo' nowrap title='$Tr90_datlim'>
                        <b>Data de Início:</b> 
                      </td>";
                echo "<td align='left' nowrap class='periodo' title='$Tr90_datlim'>
                        <b>Data de Término:</b> 
                      </td>";
              }
              // Se for ponto fixo ou for ponto de salário, colocará o LABEL do ano/mês limite...
              if($ponto == "fx" || $ponto == "fs"){
                echo "<td align='left' nowrap class='data_limite' title='$Tr90_datlim'>
                        $Lr90_datlim
                      </td>";
              // Caso contrário, se for ponto de férias ou de rescisão, colocará o LABEL da TPP
              }else if($ponto == "fe" || $ponto == "fr"){
                echo "<td align='left' nowrap title='$Tr29_tpp'>
                        $Lr29_tpp
                      </td>";
              }

              ?>
            <td align="left" nowrap title="<?=@$Tr90_quant?>">
              <?=@$Lr90_quant?>
            </td>
            <td align="left" nowrap title="<?=@$Tr90_valor?>">
              <?=@$Lr90_valor?>
            </td>
          </tr>
          <tr>
            <td> 
              <?
              db_input('r90_rubric', 8, $Ir90_rubric, true, 'text', (($db_opcao==1)?"1":"3"), " onchange='js_pesquisar90_rubric(false);' tabIndex=2 ");
              db_input('rh27_descr', 30, $Irh27_descr, true, 'text', 3, '');
              ?>
            </td>
              <?
              // Se for ponto fixo ou for ponto de salário, colocará o CAMPO do ano/mês limite...
              $tabulacao = 4;
              if ($lLimiteDatas) {

                echo "<td align='left' nowrap  class='periodo' title='$Tr90_datlim'>";
                db_inputdata('rh183_datainicio', $rh183_datainicio_dia, $rh183_datainicio_mes, $rh183_datainicio_ano, true, "text", 1);
                echo "</td>";
                echo "<td align='left' nowrap class='periodo' title='$Tr90_datlim'>";
                db_inputdata('rh183_datafim', $rh183_datafim_dia, $rh183_datafim_mes, $rh183_datafim_ano, true, "text", 1);
                echo "</td>";
                $tabulacao = 6;
              }
              if($ponto == "fx" || $ponto == "fs"){
                $tabulacao++;
                echo "<td class='data_limite'>";
                db_input('r90_datlim', 15, $Ir90_datlim, true, 'text', 3, "onChange='js_calculaQuant(this.value);' onKeyUp='js_mascaradata(this.value);' tabIndex=3 ");
                db_input('rh27_limdat', 15, $Irh27_limdat, true, 'hidden', 3, "");
                echo "</td>";
              // Caso contrário, se for ponto de férias ou de rescisão, colocará o CAMPO da TPP
              }else if($ponto == "fe" || $ponto == "fr"){
                $tabulacao++;
                echo "<td>";
                db_input('r29_tpp', 5, $Ir29_tpp, true, 'text', $db_opcao, " tabIndex=3 ");
                echo "</td>";
              }
              ?>
            </td>
            <td>
              <?
              if(!isset($r90_quant) || (isset($r90_quant) && trim($r90_quant)=="")){
                $r90_quant = '0';
              }

              /**
               * Tarefa: 96909
               * Força o alert a ficar coerente.
               */
              $Sr90_quant = 'O campo quantidade';

              db_input('r90_quant', 15, $Ir90_quant, true, 'text', $db_opcao, "onchange='js_calculaDataLimit();' tabIndex=$tabulacao ");
              $tabulacao++;
              
              db_input('rh27_presta',10,'',true,'hidden',3);
              
              ?>
            </td>
            <td>
              <?
              if(!isset($r90_valor) || (isset($r90_valor) && trim($r90_valor)=="")){
                $r90_valor = '0';
              }
              db_input('r90_valor', 15, $Ir90_valor, true, 'text', $db_opcao, " tabIndex=$tabulacao ");
              $tabulacao++;
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      
      <?php
      if ($input_botoes){
        if($db_opcao != 1){
      ?>
          <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_submita();"  tabIndex=<?=$tabulacao?> onblur='document.form1.r90_regist.select();document.form1.r90_regist.focus();'>
      <?php
          $tabulacao++;
        }
      ?>
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  onclick="return js_vercampos();" tabIndex=<?=$tabulacao?> <?=(($db_opcao==1)?"onblur='document.form1.r90_regist.select();document.form1.r90_regist.focus();'":"onblur='document.form1.novo.focus();'")?>>
        
        <input type='button' id='calcular' value='Calcular' name='calcular' onclick="js_calcular(r90_regist.value ,<?=$iPonto ?>);" />
        
        <? if (!isset($_GET['lConsulta'])){ ?>
          <input type='button' id='consultar' value='Consultar' name='consultar' onclick="js_consultar(<?=$r90_regist ?>);" />
        <? } ?>
      <?php
      }
      // $sigla - É a sigla a ser utilizada no select.
      // $campoextra - É para quando as tabelas tiverem campos como o DATLIM ou o TPP
      // $mostracamp - É para quando o DATLIM ou o TPP forem apresentados no Select, serem mostrados no IFRAME_SELECIONA

      // $whereextra - Como o campo TPP é PK juntamente com o REGISTRO, ANOUSU e MESUSU em algumas tabelas, essas tabelas
      // poderão retornar o mesmo registro, mesmo anousu e mesmo mesusu com diferentes TPP, assim, quando o usuário clicar
      // em A ('alteração') ou E ('exclusão'), a linha q foi clicada, não deverá mais aparecer e as outras com diferentes
      // TPP devem continuar aparecendo... $whereextra controla isso.
      if($ponto == "fx"){
        $sigla = "r90_";
        $campoextra = ", r90_datlim as r90_datlim ";
        $mostracamp = ", r90_datlim";
      }else if($ponto == "fs"){
        $sigla = "r10_";
        $campoextra = ", r10_datlim as r90_datlim ";
        $mostracamp = ", r90_datlim";
      }else if($ponto == "fa"){
        $sigla = "r21_";
        $campoextra = "";
        $mostracamp = "";
      }else if($ponto == "fe"){
        $sigla = "r29_";
        $campoextra = ", r29_tpp";
        $mostracamp = ",r29_tpp";
        $whereextra222 = true;
      }else if($ponto == "fr"){
        $sigla = "r19_";
        $campoextra = ", r19_tpp as r29_tpp";
        $mostracamp = ",r29_tpp";
        $whereextra222 = true;
      }else if($ponto == "f13"){
        $sigla = "r34_";
        $campoextra = "";
        $mostracamp = "";
      }else if($ponto == "com"){
        $sigla = "r47_";
        $campoextra = "";
        $mostracamp = "";
      }
      $dbwhere = "      ".$sigla."regist = ".@$r90_regist ;
      $dbwhere .= " and ".$sigla."anousu = $r90_anousu ";
      $dbwhere .= " and ".$sigla."mesusu = $r90_mesusu ";
      // Para controlar a INSTITUIÇÃO
      $dbwhere .= " and ".$sigla."instit = ".db_getsession("DB_instit");

      if(isset ($r90_rubric) && trim($r90_rubric) != "" && !isset($incluir) && !isset($alterar)){
      if(!isset($whereextra222)){
          $dbwhere .= " and ".$sigla."rubric <> '$r90_rubric' ";
      }else if(isset($r29_tpp) && trim($r29_tpp)!=""){
          $dbwhere .= " and ".$sigla."tpp||".$sigla."rubric <> '".$r29_tpp.$r90_rubric."' ";
      }
      }
      
      $campos = $sigla."anousu as r90_anousu,".  $sigla."mesusu  as r90_mesusu,". $sigla."regist  as r90_regist,". $sigla."rubric as r90_rubric,
                z01_numcgm, z01_nome".$campoextra.", rh27_descr ,".  $sigla."lotac as r90_lotac,
                r70_descr,". $sigla."quant as r90_quant,".  $sigla."valor as r90_valor";
      $orderby= $sigla."regist,". $sigla."rubric";

      $chavepri = array ("r90_anousu" => @ $r90_anousu, "r90_mesusu" => @ $r90_mesusu, "r90_regist" => @ $r90_regist, "r90_rubric" => @ $r90_rubric);
      
      // Seta TPP como chave primária.
      if($ponto == "fe" || $ponto == "fr"){
        $chavepri["r29_tpp"] = @$r29_tpp;
      }
      
      $cliframe_alterar_excluir->chavepri = $chavepri;
      if($ponto == "fx"){
      $cliframe_alterar_excluir->sql = $clpontofx->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($ponto == "fs"){
      $cliframe_alterar_excluir->sql = $clpontofs->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($ponto == "fa"){
      $cliframe_alterar_excluir->sql = $clpontofa->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($ponto == "fe"){
      $cliframe_alterar_excluir->sql = $clpontofe->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($ponto == "fr"){
      $cliframe_alterar_excluir->sql = $clpontofr->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($ponto == "f13"){
      $cliframe_alterar_excluir->sql = $clpontof13->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }else if($ponto == "com"){
      $cliframe_alterar_excluir->sql = $clpontocom->sql_query_seleciona(
                                                   null,
                                                   null,
                                                   null,
                                                   null,
                                                   " distinct ".$campos,
                                                   $orderby,
                                                   $dbwhere);
      }
      $cliframe_alterar_excluir->campos   = "r90_rubric,rh27_descr".$mostracamp.",r90_quant,r90_valor";
      $cliframe_alterar_excluir->opcoes   = 3;
      $cliframe_alterar_excluir->legenda  = "";
      $cliframe_alterar_excluir->iframe_height = "70%";
      $cliframe_alterar_excluir->iframe_width  = "95%";
      $cliframe_alterar_excluir->opcoes   = 1;
      $cliframe_alterar_excluir->fieldset = false;


      //Aqui colocar fieldset
     
      echo "<fieldset>                           ";
      echo "  <legend>Rubricas Lançadas</legend>";
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);
      echo "</fieldset>";
      ?>
    <div id="caixa_de_texto"></div>
  </fieldset> 
</form> 

<script src="scripts/widgets/DBToogle.widget.js"></script>
<script>
  $('r90_valor').observe('change', validarValor);
  $('r90_quant').observe('change', validarQuantidade);
  js_getDadosPadroes();
</script>
<script>
<?php if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) { ?>
  var oToogle = new DBToogle('toogle', true);
<?php } ?>

function js_calculaDataLimit(){

  var doc       = document.form1;
  var iQuant    = new Number(doc.r90_quant.value);
  
  if ( doc.rh27_presta.value == 't' && doc.rh27_limdat.value == 't' ) {
  
    var iMesAtu   = new Number(doc.r90_mesusu.value);
    var iAnoLimit = new Number(doc.r90_anousu.value);
    var iMesLimit = iMesAtu + (iQuant-1);
    
    while ( iMesLimit > 12  ) {
      iMesLimit -= 12;
      iAnoLimit++;
    }
    
    if ( iMesLimit.toString().length < 2 ) {
      iMesLimit = "0"+iMesLimit;
    }
    
    doc.r90_datlim.value = iAnoLimit+'/'+iMesLimit;
     
  }
   
  doc.r90_valor.select();
  doc.r90_valor.focus();
}

function js_calculaQuant(sDataLimit){
  
  var doc        = document.form1;
  var aDataLimit = sDataLimit.split('/');
  var iAnoLimit  = new Number(aDataLimit[0]);
  var iMesLimit  = new Number(aDataLimit[1]);
  var iAnoAtu    = new Number(doc.r90_anousu.value);
  var iMesAtu    = new Number(doc.r90_mesusu.value);
    
  if ( doc.rh27_presta.value == 't' && doc.rh27_limdat.value == 't' ) {
    
    var iQuant     = new Number(0);
    
    if ( iAnoLimit > iAnoAtu ) {
    
      while ( iAnoLimit > (iAnoAtu+1)  ) {
        iQuant += 12;
        --iAnoLimit;
      }
      
      var iMesRest  = new Number(12 - iMesAtu);
      
      iQuant += iMesRest + iMesLimit;
      
    } else {
      iQuant += iMesLimit - iMesAtu;
    }
    
    doc.r90_quant.value = iQuant+1;
    
  }  
}


function js_vercampos(){
  // Verificar se algum campo ficou em branco
<?
  if($db_opcao != 3 && $db_opcao != 33){
    echo '
          erro = 0;
          if(document.form1.r90_regist.value == ""){
            alert("Matrícula do servidor não informada.");
            document.form1.r90_regist.focus();
            erro++;
          }else if(document.form1.r90_lotac.value == ""){
            alert("Lotação do servidor não informada.");
            document.form1.r90_lotac.focus();
            erro++;
          }else if(document.form1.r90_rubric.value == ""){
            alert("Rubrica não informada.");
            document.form1.r90_rubric.focus();
            erro++;  
          }else if((document.form1.r90_quant.value == "" || document.form1.r90_quant.value == "0") && (document.form1.rh27_form.value == "T" || document.form1.rh27_form.value == "t")){
            alert("Quantidade não informada.");
            document.form1.r90_quant.select();
            document.form1.r90_quant.focus();
            erro++;
          }else if(document.form1.r29_tpp && document.form1.r29_tpp.value == ""){
            alert("Tipo não informado.");
            document.form1.r29_tpp.focus();
            erro++
          }else if((document.form1.r90_valor.value == "" || document.form1.r90_valor.value == "0") && (document.form1.rh27_form.value == "F" || document.form1.rh27_form.value == "f")){
            alert("Valor não informado.");
            document.form1.r90_valor.select();
            document.form1.r90_valor.focus();
            erro++;
          }

          if(erro > 0){
            return false;
          }else{

            var oInputDataInicio  = $("rh183_datainicio");        
            var oInputDataTermino = $("rh183_datafim");
            
            if (oInputDataInicio) {
            
              if (oInputDataInicio.value != "" && oInputDataTermino.value == "") {
                 
                 alert("A Data de Término do lançamento da rubrica deve ser informado!");
                 return false;
              }
               
              if (oInputDataInicio.value == "" && oInputDataTermino.value != "") {
                 
               alert("A Data de Início do lançamento da rubrica deve ser informado!");
               return false;
              }
              
              if (js_comparadata(oInputDataInicio.value, oInputDataTermino.value, ">")) {              
              
                alert("A Data de Início do lançamento da rubrica deve ser  menor que a Data de Término!");
                return false;
               }
            }
            
            /**
             * Verificar a competencia inicial do periodo. Caso o periodo for maior, devemos pedir confirmação do usuário 
             */
            var sRubrica                  = document.form1.r90_rubric.value; 
            var aParteDataInicio          = oInputDataInicio.value.split("/");
            var aParteDatasFim            = oInputDataTermino.value.split("/");
            var sCompetenciaAtual         = document.form1.r90_anousu.value+""+document.form1.r90_mesusu.value;
            var sCompetenciaInicioRubrica = false;
            var sCompetenciaFimRubrica    = false;
            var sCompetenciaMensagem      = false;

            if (aParteDataInicio.length > 1) {
              sCompetenciaInicioRubrica = aParteDataInicio[2]+""+aParteDataInicio[1];
              sCompetenciaMensagem      = aParteDataInicio[1]+"/"+aParteDataInicio[2];
            }

            if (aParteDatasFim.length > 1) {
              sCompetenciaFimRubrica = aParteDatasFim[2]+""+aParteDatasFim[1];
            } 

            /**
             * Verificamos se esta sendo cadastrado a rubrica no ponto de salário.
             * Se estiver, e a data inicio ou fim for fora da competência atual, não é permitido no salário
             * Apenas no fixo.
             */
            if (document.form1.ponto.value == "fs" && (sCompetenciaInicioRubrica > sCompetenciaAtual || sCompetenciaFimRubrica > sCompetenciaAtual)) {

              alert("Não é possível lançar uma rubrica com período fora da competência atual. Para isso utilize o lançamento do ponto fixo. </br> \"Procedimentos > Manutenção do Ponto > Por Matrícula > Fixo\".");
              return false;
            }

            if (sCompetenciaInicioRubrica && sCompetenciaInicioRubrica > sCompetenciaAtual) {
              
              var sMensagemRubricaCompetencia  = "A rubrica "+sRubrica+" foi agendada para pagamento em outra competência.\n";
                  sMensagemRubricaCompetencia += "Até a competência "+sCompetenciaMensagem+", a rubrica não será calculada, nem poderá ser alterada.\n";
                  sMensagemRubricaCompetencia += "Deseja Continuar?";
                  
              if (!confirm(sMensagemRubricaCompetencia)) {
                return false;    
              }
            }

            if ( document.form1.ponto.value == "fx" || document.form1.ponto.value == "fs" ) {
              return js_verificaposicoes(document.form1.r90_datlim.value,"true");
            }

            if(document.form1.r90_quant.value == ""){
              document.form1.r90_quant.value = 0;
            }
            if(document.form1.r90_valor.value == ""){
              document.form1.r90_valor.value = 0;
            }
            
              
              
            return js_testarRegraPonto();
          }
         ';
  } 
  ?>

}

function js_submita() {

  location.href = "pes1_pontofx001.php?r90_anousu="+document.form1.r90_anousu.value+"&r90_mesusu="+document.form1.r90_mesusu.value+"&r90_regist="+document.form1.r90_regist.value+"&ponto="+document.form1.ponto.value;
}
// Função para tornar ou não o campo datlim READONLY.
function js_desabilita(TrueORFalse){
  opcaoextra = "<?=($db_opcao)?>";
  <?
  // Se ponto for salário ou fixo, a função irá executar caso contrário, a função
  // não fará nada
  if($ponto == "fx" || $ponto == "fs"){
    echo '
    if(document.form1.r90_regist.value != ""){
      if(TrueORFalse==true || opcaoextra=="3"){
        if(opcaoextra!="3"){
          document.form1.r90_datlim.value                 = "";
        }
        document.form1.r90_datlim.readOnly              = true;
        document.form1.r90_datlim.style.backgroundColor = "#DEB887";
        if(document.form1.r90_rubric.value != ""){
          document.form1.r90_quant.select();
          document.form1.r90_quant.focus();
        }else{
          document.form1.r90_rubric.select();
          document.form1.r90_rubric.focus();
        }
      }else{
        if(document.form1.r90_rubric.value != ""){
          document.form1.r90_datlim.readOnly              = false;
          document.form1.r90_datlim.style.backgroundColor ="";
          document.form1.r90_datlim.select();
          document.form1.r90_datlim.focus();
        }
      }
    }else{
      document.form1.r90_regist.select();
      document.form1.r90_regist.focus();
    }
    ';
  }else if($ponto == "fe" || $ponto == "fr"){
    echo '
    if(document.form1.r90_regist.value != ""){
      if(document.form1.r90_rubric.value != ""){
        document.form1.r29_tpp.select();
        document.form1.r29_tpp.focus();
      }else{
        document.form1.r90_rubric.select();
        document.form1.r90_rubric.focus();
      }
    }else{
      document.form1.r90_regist.select();
      document.form1.r90_regist.focus();
    }
    ';
  }
  ?>
}

/**
 * Variavel global para armazenar o status da rubrica
 * - true: pode ser inserida no ponto.
 * - false: não pode ser inserida no ponto.
 *
 * @var boolean lTestarRegraPonto
 * @access public
 */
var lTestarRegraPonto;

/**
 * Realiza uma consulta no RPC para cada vez que uma Rubrica é adicionada, 
 * para verificar se a mesma possui alguma regra de lançamento.
 * - lTestarRegraPonto recebe true quando a rubrica pode ser adicionada e false quando não pode ser adicionada
 *
 * @access public
 * @return boolean lTestarRegraPonto.
 */
function js_testarRegraPonto() {

  var aRubricas  = [$F('r90_rubric')];
  var sTabela    = "<?=$ponto?>";
  var iMatricula = $F('r90_regist');

  var sUrl   = 'pes1_rhrubricas.RPC.php';

  var oParametros = Object();
      oParametros.sExecucao  = 'testarRegistroPonto';
      oParametros.aRubricas  = aRubricas;
      oParametros.sTipoPonto = sTabela;
      oParametros.iMatricula = iMatricula;   

  var oAjax  = new Ajax.Request( sUrl, {
                                         method: 'post', 
                                         asynchronous: false,
                                         parameters : 'json=' + Object.toJSON(oParametros),
                                         onComplete: js_retornoTestarRegraPonto
                                        }
                                );

  return lTestarRegraPonto;
}

/**
 * Trata o retorno da função js_testarRegraPonto
 * - se for somente aviso, exibe um alert com a mensagem solicitando se deseja adicionar a rubrica ou não
 * - se for bloqueio não permite adicionar a rubrica
 * - lTestarRegraPonto recebe true quando a rubrica pode ser adicionada e false quando não pode ser adicionada ao ponto
 *
 * @param object oRetorno.
 * @access public
 */
function js_retornoTestarRegraPonto(oRetorno) {

  lTestarRegraPonto = true;

  var oRetorno = eval("("+oRetorno.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode().replace(/\\n/g, "\n");

  /**
   * Erro no RPC
   */
  if ( oRetorno.iStatus > 1 ) {

    alert(sMensagem);
    lTestarRegraPonto = false;
    return false;
  }

  /**
   * Se haver uma mensagem de bloqueio, exibe a mensagem para o usuario a mensgem e lTestarRegraPonto 
   * recebe o valor false, para a rubrica não ser adicionada ao ponto
   */
  if ( oRetorno.sMensagensBloqueio != '' ) {

    lTestarRegraPonto = false;
    alert( oRetorno.sMensagensBloqueio.urlDecode().replace(/\n/g, "\n") );
    return false;
  }

  /**
   * Se haver uma mensagem de aviso, exibe a mensagem para o usuario perguntando 
   * se a rubrica deve ser adicionada ao ponto ou não.
   * - lTestarRegraPonto recebe false se o usuario clicar em cancelar
   */
  if ( oRetorno.sMensagensAviso != '' ) {

    lConfirmarAviso = confirm( oRetorno.sMensagensAviso.urlDecode().replace(/\n/g, "\n") );

    /**
     * Clicou em cancelar
     * - Nao permite adicionar ao ponto
     */
    if ( !lConfirmarAviso ) {
      lTestarRegraPonto = false;
    }
  }
}

function js_verificaposicoes(valor,TorF){

  var expr = new RegExp("[^0-9]+");
  localbarra = valor.search("/");
  erro = 0;
  errm = "";
  if(localbarra == -1){
    if(valor.match(expr)){
      erro ++;
    }else if(TorF == "true" && document.form1.r90_datlim.readOnly == false){
      erro ++;
    }
  }else{
    ano = valor.substr(0,4);
    mes = valor.substr(5,2);
    anoi = new Number(ano);
    mesi = new Number(mes);
    anot = new Number(document.form1.r90_anousu.value);
    mest = new Number(document.form1.r90_mesusu.value);
    
    if(ano.match(expr)){
      erro ++;
    }else if(mes.match(expr)){
      erro ++;
    }else if(anoi < anot || (anoi <= anot && mesi < mest)){
      if(mesi > 1 || anoi < anot || TorF == 'true'){
        errm = "\nAno e mês devem ser maior ou igual ao corrente da folha.";
        erro ++;
      }
    }else if(mesi > 12){
      errm = "\nMês inexistente.";
      erro ++;
    }else if(TorF == 'true' && mes == 0){
      errm = "\nMês não informado.";
      erro ++;
    }
  }

  if( erro > 0 || (document.form1.rh27_limdat.value == 't' && document.form1.r90_datlim.value == "")){
  alert("Campo Ano/mês deve ser preenchido com números e uma '/' no seguinte formato (aaaa/mm)! " + errm);
    document.form1.r90_datlim.select();
    document.form1.r90_datlim.focus();
    return false;
  }

//   return false;
  return js_testarRegraPonto();

}
function js_mascaradata(valor){

  total = valor.length;
  if(total > 0){
    digit = valor.substr(total-1,1);
    if(digit != "/"){
      if(total == 4){
        valor += "/";
      }
    }
  }
  
  document.form1.r90_datlim.value = valor;
  return js_verificaposicoes(valor,'false');

}
function js_pesquisar90_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=<?=($ponto == "fs" ? "raf" : ($ponto == "fr" ? "fa" : "ra"))?>&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>&chave_r01_mesusu='+document.form1.r90_mesusu.value+'&chave_r01_anousu'+document.form1.r90_anousu.value,'Pesquisa Matricula',true);
  }else{
     if(document.form1.r90_regist.value != ''){
      <?php if (!FolhaPagamentoSalario::hasFolhaAberta()) { ?>
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?rotina_suplementar=true&testarescisao=<?=($ponto == "fs" ? "raf" : ($ponto == "fr" ? "fa" : "ra"))?>&pesquisa_chave='+document.form1.r90_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa Matricula',false);
      <?php } else { ?>
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=<?=($ponto == "fs" ? "raf" : ($ponto == "fr" ? "fa" : "ra"))?>&pesquisa_chave='+document.form1.r90_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa Matricula',false);
      <?php } ?>
     }else{
       alert("Matrícula do servidor não informada.");
       document.form1.z01_nome.value = '';
       location.href = "pes1_pontofx001.php?ponto="+document.form1.ponto.value; 
     }
  }
}
function js_mostrapessoal(chave,erro){

  document.form1.z01_nome.value = chave; 

  if(erro==true){ 
    document.form1.r90_regist.focus(); 
    document.form1.r90_regist.value = ''; 
  }else{
    js_submita();
  }
}
function js_mostrapessoal1(chave1,chave2){

  document.form1.r90_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
  js_submita();
}

function js_pesquisar90_rubric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_rhrubricas','func_rhrubricasponto.php?funcao_js=parent.js_mostrarubricas1|rh27_rubric|rh27_descr|rh27_limdat|formula|rh27_obs|rh27_presta|rh27_periodolancamento|&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa Rubrica',true);
  }else{
     if(document.form1.r90_rubric.value != ''){       
       quantcaracteres = document.form1.r90_rubric.value.length;
       for(i=quantcaracteres;i<4;i++){
         document.form1.r90_rubric.value = "0"+document.form1.r90_rubric.value;        
       }
       js_OpenJanelaIframe('','db_iframe_rhrubricas','func_rhrubricasponto.php?pesquisa_chave='+document.form1.r90_rubric.value+'&funcao_js=parent.js_mostrarubricas&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa Rubrica',false);
     }else{
       document.form1.rh27_descr.value = '';
       document.form1.rh27_form.value  = '';
       document.form1.r90_rubric.value  = '';
       document.form1.r90_valor.value = '0';
       document.form1.r90_quant.value = '0';
       document.getElementById('caixa_de_texto').innerHTML = "";
       js_desabilita(true); 
     }
  }
}
function js_mostrarubricas(chave,chave2,chave3,chave4,chave5,erro, periodoLancamento ) {

  document.form1.rh27_descr.value  = chave;
  <?php
  if($ponto == "fx" || $ponto == "fs"){
    echo "document.form1.rh27_limdat.value = chave2;\n";
  }
  ?>
  if (erro==true) {

    document.getElementById('caixa_de_texto').innerHTML = "";
    document.form1.rh27_form.value = '';
    document.form1.r90_rubric.value = '';
    document.form1.r90_rubric.focus();

  }else{
    document.form1.rh27_presta.value = chave5;
    document.getElementById('caixa_de_texto').innerHTML = "<font color='red'><b>"+chave4+"</b></font>";
    document.form1.rh27_form.value  = chave3;
  }

  js_getDadosPadroes();

  desabilitarPeriodoDatas(periodoLancamento == 'f' ? true : false);
  if(chave2 == 'f' || chave2 == ''){
    js_desabilita(true);
  }else{
    js_desabilita(false);
  }
}

function js_mostrarubricas1(chave1,chave2,chave3,chave4,chave5,chave6, periodoLancamento) {

  document.form1.r90_rubric.value  = chave1;
  document.form1.rh27_descr.value  = chave2;
  document.form1.rh27_form.value   = chave4;
  document.form1.rh27_presta.value = chave6;
  document.getElementById('caixa_de_texto').innerHTML = "<font color='red'><b>"+chave5+"</b></font>";
  <?
  if($ponto == "fx" || $ponto == "fs"){
    echo "document.form1.rh27_limdat.value = chave3;";
  }
  ?>
  if(chave3 == 'f' || chave3 == ""){
    js_desabilita(true);
  }else{
    js_desabilita(false);
  }
  desabilitarPeriodoDatas(periodoLancamento == 'f' ? true : false);
  js_getDadosPadroes();

  db_iframe_rhrubricas.hide();
}

function js_pesquisar90_lotac(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframelotacao','func_lotacao.php?funcao_js=parent.js_mostralotacao1|r70_codigo|r70_descr&instit=<?=(db_getsession("DB_instit"))?>&chave_r70_mesusu='+document.form1.r90_mesusu.value+'&chave_r70_anousu'+document.form1.r90_anousu.value,'Pesquisa',true);
  }else{
     if(document.form1.r90_lotac.value != ''){ 
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframelotacao','func_lotacao.php?pesquisa_chave='+document.form1.r90_lotac.value+'&funcao_js=parent.js_mostralotacao&instit=<?=(db_getsession("DB_instit"))?>&chave_r70_mesusu='+document.form1.r90_mesusu.value+'&chave_r70_anousu'+document.form1.r90_anousu.value,'Pesquisa',false);
     }else{
       document.form1.r70_descr.value = ''; 
     }
  }
}
function js_mostralotacao(chave,erro){
  document.form1.r70_descr.value = chave; 
  if(erro==true){ 
    document.form1.r90_lotac.focus(); 
    document.form1.r90_lotac.value = ''; 
  }
}
function js_mostralotacao1(chave1,chave2){
  document.form1.r90_lotac.value = chave1;
  document.form1.r70_descr.value = chave2;
  db_iframelotacao.hide();
}

function desabilitarPeriodoDatas(lDesabilitar) {

  if (lDesabilitar) {
    $$('.periodo').each(Element.hide);
    $$('.data_limite').each(Element.show);
  } else {
    $$('.periodo').each(Element.show);
    $$('.data_limite').each(Element.hide);
  }
}

<?php
$TrueORFalse = "true";
if(isset($rh27_limdat)){
  if($rh27_limdat=="t"){
  $TrueORFalse = "false";
  }
}
$lLiberaDataesRubrica = $rh27_periodolancamento == 'f' ? 'true' : 'false';
echo "js_desabilita($TrueORFalse);";
echo "desabilitarPeriodoDatas($lLiberaDataesRubrica);";
?>
</script>
