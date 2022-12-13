<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification('libs/db_utils.php'));
require_once(modification('fpdf151/FpdfMultiCellBorder.php'));

$oGet       = db_utils::postMemory($_GET);
$requisicao = $oGet->requisicao;
$requiitem  = $oGet->requiitem;
$iLabSetor  = $oGet->iLabSetor;

$aAtributosDoExame = array();
$lExisteAtributos  = false;

/**
 * Objeto com informações a serem utilizadas no relatório
 */
$oDadosEstrutura                 = new stdClass();
$oDadosEstrutura->iLarguraPadrao = 192;
$oDadosEstrutura->iAlturaPadrao  = 5;
$oDadosEstrutura->aSetor         = array();
$oDadosEstrutura->aExames        = array();
$oDadosEstrutura->iRequisicao    = $requisicao;
$oDadosEstrutura->sData          = ''  ;

$oDadosEstrutura->oSolicitante          = new stdClass();
$oDadosEstrutura->oSolicitante->iCodigo = '';
$oDadosEstrutura->oSolicitante->sNome   = '';
$oDadosEstrutura->oSolicitante->sSexo   = '';
$oDadosEstrutura->oSolicitante->iIdade  = '';

try {

  $oRequisicaoLaboratorial                = new RequisicaoLaboratorial( $requisicao );
  $oDadosEstrutura->oSolicitante->sMedico = $oRequisicaoLaboratorial->getMedico();

  /**
   * Array com atributos que possuem valor para impressão.
   * Ao buscar os dados, caso encontre o registro, incrementa o array
   */
  $aAtributosSelecionaveis  = array();
  $oDaoAtributoSelecionavel = new cl_lab_valorreferenciasel();
  $sSqlAtributos            = $oDaoAtributoSelecionavel->sql_query_file();
  $rsAtributosSelecionaveis = db_query( $sSqlAtributos );

  if ( !$rsAtributosSelecionaveis ) {
    throw new DBException('Falha ao buscar os atributos do exame.');
  }

  for ($iAtributo = 0; $iAtributo < pg_num_rows($rsAtributosSelecionaveis); $iAtributo++) {

    $oDadosAtributoSelecionavel = db_utils::fieldsMemory($rsAtributosSelecionaveis, $iAtributo);
    $aAtributosSelecionaveis[$oDadosAtributoSelecionavel->la28_i_codigo] = $oDadosAtributoSelecionavel->la28_c_descr;
  }

  /**
   * Percorre os exames da requisição, para montar a estrutura do relatório
   */
  foreach( $oRequisicaoLaboratorial->getRequisicoesDeExames() as $oRequisicao ) {

    $oDadosEstrutura->sData = $oRequisicao->getData()->convertTo(DBDate::DATA_PTBR);

    /**
     * Caso não seja do tipo CONFERIDO '7 - Conferido', segue percorrendo o próximo registro
     */
    if ( !in_array($oRequisicao->getSituacao(), array(RequisicaoExame::CONFERIDO, RequisicaoExame::ENTREGUE)) ) {
      continue;
    }

    /**
     * Valida se foi selecionado algum exame específico
     */
    if ( isset( $requiitem ) && !empty( $requiitem ) && $requiitem != $oRequisicao->getCodigo() ) {
      continue;
    }

    /**
     * Valida se foi selecionado algum laboratório específico
     */
    if ( isset( $iLabSetor ) && !empty( $iLabSetor ) && $iLabSetor != $oRequisicao->getLaboratorioSetor()->getCodigo() ) {
      continue;
    }

    $oExame          = $oRequisicao->getExame();
    $oResultadoExame = $oRequisicao->getResultado();
    $aAtributos      = $oExame->getAtributos();

    // Se o exame não tiver atributos, pula para o próximo exame
    if ( empty( $aAtributos ) ) {
      continue;
    }

    $iCodigoSetor = $oRequisicao->getLaboratorioSetor()->getCodigo();
    if ( !array_key_exists($iCodigoSetor, $oDadosEstrutura->aSetor) ) {

      $oDadosSetor             = new stdClass();
      $oDadosSetor->iCodigo    = $oRequisicao->getLaboratorioSetor()->getCodigo();
      $oDadosSetor->sDescricao = $oRequisicao->getLaboratorioSetor()->getDescricao();
      $oDadosSetor->aExames    = array();

      $oDadosEstrutura->aSetor[$iCodigoSetor] = $oDadosSetor;
    }

    $oDadosEstrutura->aExames[] = $oRequisicao->getCodigo();

    /**
     * Preenche os dados do solicitante para impressão do cabeçalho
     */
    $dtNascimento = $oRequisicao->getSolicitante()->getDataNascimento()->getDate();

    $oDadosEstrutura->lSalvarArquivo        = $oRequisicao->getSituacao() == RequisicaoExame::CONFERIDO;
    $oDadosEstrutura->oSolicitante->iCodigo = $oRequisicao->getSolicitante()->getCodigo();
    $oDadosEstrutura->oSolicitante->sNome   = $oRequisicao->getSolicitante()->getNome();
    $oDadosEstrutura->oSolicitante->sSexo   = $oRequisicao->getSolicitante()->getSexo();
    $oDadosEstrutura->oSolicitante->iIdade  = getIdadeSolicitante( $dtNascimento )->anos;

    $aDadosMaterialColeta            = $oExame->getMaterialColeta();

    $iCodigoExame = $oExame->getCodigo();
    if ( !array_key_exists($iCodigoExame, $oDadosEstrutura->aSetor[$iCodigoSetor]->aExames) )  {

      $oStdExame                       = new stdClass();
      $oStdExame->sNomeExame           = $oExame->getNome();
      $oStdExame->sObservacaoExame     = $oExame->getObservacao();
      $oStdExame->aMedicamentosExame   = $oRequisicao->getMedicamentos();
      $oStdExame->aDadosMaterialColeta = $aDadosMaterialColeta;
      $oStdExame->sObservacao          = $oRequisicao->getObservacao();
      $oStdExame->aAtributos           = array();

      $oDadosEstrutura->aSetor[$iCodigoSetor]->aExames[$iCodigoExame] = $oStdExame;
    }

    /**
     * Percorre cada atributo, e monta o objeto com as informações necessários do mesmo
     */
    foreach ($aAtributos as $oAtributo) {

      $oResultadoAtributo = $oResultadoExame->getValorDoAtributo($oAtributo);
      /**
       * Apenas no modelo 2 evitamos de mostrar atributos do exame que não têm valores no resultado
       */
      if (    $oGet->iModelo == 2 && $oAtributo->getTipo() != 1
           && (is_null($oResultadoAtributo) || !digitouResultado($oResultadoAtributo))) {
        continue;
      }

      $oAtributoDoExame                          = new stdClass();
      $oAtributoDoExame->nome                    = $oAtributo->getNome();
      $oAtributoDoExame->nivel                   = $oAtributo->getNivel();
      $oAtributoDoExame->valorabsoluto           = '';
      $oAtributoDoExame->valorpercentual         = '';
      $oAtributoDoExame->unidade                 = '';
      $oAtributoDoExame->referencia              = '';
      $oAtributoDoExame->tipo                    = $oAtributo->getTipo();
      $oAtributoDoExame->tiporeferencia          = $oAtributo->getTipoReferencia();
      $oAtributoDoExame->iSetor                  = $oRequisicao->getLaboratorioSetor()->getCodigo();
      $oAtributoDoExame->valorabsolutoanterior   = '';
      $oAtributoDoExame->valorpercentualanterior = '';
      $oAtributoDoExame->referenciaanterior      = '';
      $oAtributoDoExame->titulacaoanterior       = '';
      $oAtributoDoExame->dataResultadoAnterior   = $oResultadoExame->getDataResultadoAnterior();

      if ($oAtributo->getUnidadeMedida() != "") {
        $oAtributoDoExame->unidade = $oAtributo->getUnidadeMedida()->getNome();
      }

      if (!empty($oResultadoAtributo)) {

        $oRetorno = organizaValoresAtributo( $oResultadoAtributo,
                                             $oAtributoDoExame->unidade,
                                             $oAtributo->getTipoReferencia(),
                                             $aAtributosSelecionaveis);

        $oAtributoDoExame->valorabsoluto   = $oRetorno->valorabsoluto;
        $oAtributoDoExame->valorpercentual = $oRetorno->valorpercentual;
        $oAtributoDoExame->referencia      = $oRetorno->referencia;
        $oAtributoDoExame->titulacao       = $oRetorno->titulacao;
      }

      $oResultadoAnterior = $oResultadoExame->getValorDoAtributoResultadoAnterior($oAtributo);

      if (!empty($oResultadoAnterior)) {

        $oRetornoAnterior = organizaValoresAtributo( $oResultadoAnterior,
                                                     $oAtributoDoExame->unidade,
                                                     $oAtributo->getTipoReferencia(),
                                                     $aAtributosSelecionaveis);

        $oAtributoDoExame->valorabsolutoanterior   = $oRetornoAnterior->valorabsoluto;
        $oAtributoDoExame->valorpercentualanterior = $oRetornoAnterior->valorpercentual;
        $oAtributoDoExame->referenciaanterior      = $oRetornoAnterior->referencia;
        $oAtributoDoExame->titulacaoanterior       = $oRetornoAnterior->titulacao;
      }

      /**
       * Cria um objeto com os dados do setor e um array vazio de atributos a ser incrementado após incrementar o array
       * dos atributos
       */
      $oDadosEstrutura->aSetor[$iCodigoSetor]->aExames[$iCodigoExame]->aAtributos[] = $oAtributoDoExame;

      $lExisteAtributos = true;
    }
  }

  if ( !$lExisteAtributos ) {
    throw new Exception("Nenhum registro encontrado.");
  }

} catch (Exception $oErro) {

  $sMessage = urlencode($oErro->getMessage());
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMessage}");
}

function organizaValoresAtributo( ResultadoExameAtributo $oResultadoAtributo, $unidade, $iTipoReferencia, $aAtributosSelecionaveis) {

  $oAtributoDoExame = new stdClass();
  $oAtributoDoExame->valorabsoluto   = $oResultadoAtributo->getValorAbsoluto();
  $oAtributoDoExame->valorpercentual = $oResultadoAtributo->getValorPercentual();
  $oAtributoDoExame->titulacao       = $oResultadoAtributo->getTitulacao();
  $oAtributoDoExame->referencia      = '';
  $oAtributoDoExame->valorreferencia = '';

  switch ( $iTipoReferencia ) {

    case AtributoExame::REFERENCIA_NUMERICA:

      $oReferenciaAtributo  = $oResultadoAtributo->getFaixaUtilizada();

      if( !empty($oReferenciaAtributo) && $oReferenciaAtributo->getCodigo() == '' ) {
        $oReferenciaAtributo = $oAtributo->getValoresDeReferenciaParaExame($oRequisicao);
      }

      $iCasasDecimaisApresentacao = null;

      if( $oReferenciaAtributo instanceof AtributoValorReferenciaNumerico ) {
        $iCasasDecimaisApresentacao = $oReferenciaAtributo->getCasasDecimaisApresentacao();
      }

      $oAtributoDoExame->valorabsoluto = MascaraValorAtributoExame::mascarar($iCasasDecimaisApresentacao, $oAtributoDoExame->valorabsoluto);

      if ($oReferenciaAtributo != '') {

        $iValorMinimo = MascaraValorAtributoExame::mascarar($iCasasDecimaisApresentacao, $oReferenciaAtributo->getValorMinimo());
        $iValorMaximo = MascaraValorAtributoExame::mascarar($iCasasDecimaisApresentacao, $oReferenciaAtributo->getValorMaximo());

        $sStringReferencia                 = "({$iValorMinimo} - {$iValorMaximo}) {$unidade}";
        $oAtributoDoExame->referencia      = $sStringReferencia;
        $oAtributoDoExame->valorreferencia = "({$iValorMinimo} - {$iValorMaximo})";
      }
      break;

    case AtributoExame::REFERENCIA_SELECIONAVEL:

      $oAtributoDoExame->referencia      = $unidade;
      $oAtributoDoExame->valorreferencia = null;
      if (isset($aAtributosSelecionaveis[$oResultadoAtributo->getValorAbsoluto()])) {
        $oAtributoDoExame->valorabsoluto = $aAtributosSelecionaveis[$oResultadoAtributo->getValorAbsoluto()];
      }
      break;

    case AtributoExame::REFERENCIA_FIXA:

      $oAtributoDoExame->referencia      = $unidade;
      $oAtributoDoExame->valorreferencia = null;
      $oAtributoDoExame->valorabsoluto   = $oResultadoAtributo->getValorAbsoluto();
      break;
  }

  return $oAtributoDoExame;
}



/**
 * Calcula a idade que o solicitante tem com base na data do sistema
 * @param  date    $dtNascimento
 * @return stdClass
 */
function getIdadeSolicitante( $dtNascimento ) {

  $oIdade        = new stdClass();
  $oIdade->anos  = 0;
  $oIdade->meses = 0;
  $oIdade->dias  = 0;

  if ($dtNascimento == "") {
    return '';
  }

  $dtSistema       = date("Y-m-d", db_getsession("DB_datausu"));
  $sSqlAnoMesDia   = "SELECT fc_idade_anomesdia('{$dtNascimento}', '{$dtSistema}', false) as dias;";
  $rsAnoMesDia     = db_query($sSqlAnoMesDia);
  if ($rsAnoMesDia && pg_num_rows($rsAnoMesDia) > 0) {

    $aDadosIdade   = explode(',', db_utils::fieldsMemory($rsAnoMesDia, 0)->dias);
    $oIdade->anos  = trim($aDadosIdade[0]);
    $oIdade->meses = trim($aDadosIdade[1]);
    $oIdade->dias  = trim($aDadosIdade[2]);

  }

  return $oIdade;
}


/**
 * Salva o relatório gerado
 * @param $oDadosEstrutura
 */
function salvaRelatorio( $oDadosEstrutura ) {

  $oDaoLabEmissao = new cl_lab_emissao();

  db_inicio_transacao();

  foreach( $oDadosEstrutura->aExames as $iExame ) {

    $iOid     = DBLargeObject::criaOID(true);
    $mEscrita = DBLargeObject::escrita( "tmp/{$oDadosEstrutura->sNome}", $iOid );

    $oDaoLabEmissao->la34_o_laudo     = $mEscrita;
    $oDaoLabEmissao->la34_c_nomearq   = "tmp/$oDadosEstrutura->sNome";
    $oDaoLabEmissao->la34_d_data      = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoLabEmissao->la34_c_hora      = db_hora();
    $oDaoLabEmissao->la34_i_requiitem = $iExame;
    $oDaoLabEmissao->la34_i_usuario   = $oDadosEstrutura->iUsuario;
    $oDaoLabEmissao->la34_i_forma     = 1;
    $oDaoLabEmissao->incluir(null);
  }
  db_fim_transacao();
}


/**
 * Busca o usuário que efetuou a conferência dos exames do setor da requisição
 * @param  integer $iRequisicao código da requisição
 * @param  integer $iSetor      código do setor dos exames
 * @return integer|null
 */
function buscaUsuarioConferiuRequisicao($iRequisicao, $iSetor) {

  $oDaoUsuarioLogado     = new cl_lab_labsetor();
  $sCamposUsuarioLogado  = " la47_i_login ";
  $sWhereUsuarioLogado   = " la21_i_requisicao = {$iRequisicao} and la24_i_setor = {$iSetor} ";
  $sSqlUsuarioLogado     = $oDaoUsuarioLogado->sql_query_cgm_lab_setor( null, $sCamposUsuarioLogado, null, $sWhereUsuarioLogado );
  $rsUsuarioLogado       = db_query( $sSqlUsuarioLogado );
  if ( !$rsUsuarioLogado ) {
    throw new Exception("Não foi possível buscar usuário que conferiu requisição.");
  }

  if ( pg_num_rows($rsUsuarioLogado) == 0 ) {
    return null;
  }

  return db_utils::fieldsMemory($rsUsuarioLogado, 0)->la47_i_login;
}


/**
 * Valida os atributos que foram digitados
 * @param  ResultadoExameAtributo $oResultadoAtributo
 * @return boolean   true se digitou
 */
function digitouResultado($oResultadoAtributo) {

  /**
   * Identifiquei que quando a
   * Tipo de Referencia = 2 - representa os atributos numéricos
   */
  if (   $oResultadoAtributo->getAtributo()->getTipoReferencia() == 2 ) {

    /**
     * Tipo de Calculo = 2 é os atributos com cálculo %, nesse caso devemos considerar o valor percentual como valor digitado
     */
    if ( $oResultadoAtributo->getFaixaUtilizada()->getTipoCalculo() == 2 && $oResultadoAtributo->getValorPercentual() == '' ) {
      return false;
    }
  }

  if ( $oResultadoAtributo->getValorAbsoluto() == '' ) {
    return false;
  }

  return true;
}


/**
 * Realiza a chamada do modelo selecionado
 */
if ( $oGet->iModelo == 1 ) {
  require_once( modification('lab4_resultadomodelo1.php') );
} else {
  require_once( modification('lab4_resultadomodelo2.php') );
}


if ($oDadosEstrutura->lSalvarArquivo) {
  salvaRelatorio( $oDadosEstrutura );
}
