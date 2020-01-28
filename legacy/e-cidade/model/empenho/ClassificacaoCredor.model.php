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

require_once(modification('model/empenho/AutorizacaoEmpenho.model.php'));

/**
 * Class ClassificacaoCredor
 */
class ClassificacaoCredor {

  /**
   * @type int
   */
  const RECURSO_VINCULADO = 1;

  /**
   * @type int
   */
  const PEQUENO_VALOR = 2;

  /**
   * @type int
   */
  const MATERIAL_SERVICO = 3;

  /**
   * @type int
   */
  const DISPENSA = 4;

  /**
   * Caminho do arquivo de mensagens
   *
   * @type string
   */
  const MENSAGENS = 'financeiro.empenho.ClassificacaoCredor.';

  /**
   * Sexto e Sétimo níveis das contas que são dispensadas de classificação
   * 333903016000000
   * @type array
   */
  public static $aSextoSetimoNivelDispensados = array(47, 16, 93);

  /**
   * Primeiros três niveis desconsiderados
   * 331
   * @type array
   */
  public static $aInicialElementos = array(331);

  /**
   * @type EmpenhoFinanceiro
   */
  private $oEmpenho;


  /**
   * @deprecated
   * Define o tipo de classificação de credor para o empenho
   * @param EmpenhoFinanceiro $oEmpenho
   * @param EmpenhoFinanceiro $oEmpenho
   * @return int
   */
  public static function getClassificacaoCredorPorEmpenho(EmpenhoFinanceiro $oEmpenho) {

    if ($oEmpenho->isPrestacaoContas()) {
      return self::DISPENSA;
    }
    return self::getClassificacaoPorAutorizacao($oEmpenho->getAutorizacaoEmpenho());
  }

  /**
   * @deprecated
   * @param AutorizacaoEmpenho $oAutorizacao
   *
   * @return int
   * @throws BusinessException
   * @throws Exception
   */
  public static function getClassificacaoPorAutorizacao(AutorizacaoEmpenho $oAutorizacao) {

    $oDotacao = $oAutorizacao->getDotacaoOrcamentaria();
    if (empty($oDotacao)) {
      throw new BusinessException("Objeto Dotação Orçamentária não carregado.");
    }

    $oContaOrcamentaria = $oDotacao->getContaOrcamentaria();
    if(empty($oContaOrcamentaria)) {
      throw new Exception("Objeto Conta Orçamentária não carregada.");
    }
    $sEstrutural = $oContaOrcamentaria->getEstrutural();
    $iSextoSetimoNivel    = substr($sEstrutural, 5, 2);
    $iPrimeirosTresNiveis = substr($sEstrutural, 0, 3);
    if (in_array($iSextoSetimoNivel, self::$aSextoSetimoNivelDispensados) || in_array($iPrimeirosTresNiveis, self::$aInicialElementos)) {
      return self::DISPENSA;
    }

    $oRecurso = $oDotacao->getDadosRecurso();
    if ($oRecurso->getTipoRecurso() == Recurso::VINCULADO) {
      return self::RECURSO_VINCULADO;
    }

    if ($oAutorizacao->getValor() <= 8000.00) {
      return self::PEQUENO_VALOR;
    }
    return self::MATERIAL_SERVICO;
  }

  /**
   * @deprecated
   * @param $iClassificacao
   * @return string
   */
  public static function getDescricaoDaClassificacao($iClassificacao) {

    $sDescricao = '';
    switch ($iClassificacao) {

      case self::DISPENSA:
        $sDescricao = 'Dispensa';
        break;

      case self::MATERIAL_SERVICO:
        $sDescricao = 'Material e Serviço';
        break;

      case self::PEQUENO_VALOR:
        $sDescricao = 'Pequeno Valor';
        break;

      case self::RECURSO_VINCULADO:
        $sDescricao = 'Recurso Vinculado';
        break;
    }

    return $sDescricao;
  }

  /**
   * @deprecated
   * @param $iClassificacao
   *
   * @return int
   */
  public static function getQuantidadeDiasPorClassificacao($iClassificacao) {

    switch ($iClassificacao) {

      case self::RECURSO_VINCULADO:
      case self::MATERIAL_SERVICO:
        return 30;

      case self::PEQUENO_VALOR:
        return 5;
    }
  }

  /**
   * @deprecated
   * @param NotaLiquidacao $oNota
   * @return DBDate
   */
  public static function getDataDeVencimentoPorNota(NotaLiquidacao $oNota) {

    $iClassificacao  = $oNota->getEmpenho()->getClassificacaoCredor();
    $oDataVencimento = $oNota->getDataRecebimento();
    return self::verificaDataVencimento($oDataVencimento, $iClassificacao);
  }

  /**
   * @deprecated
   * @param DBDate $oData
   * @param        $iClassificacao
   *
   * @return DBDate
   */
  public static function getDataDeVencimentoPorData(DBDate $oData, $iClassificacao) {
    return self::verificaDataVencimento($oData, $iClassificacao);
  }

  /**
   * @deprecated
   * @param DBDate $oData
   * @param        $iClassificacao
   *
   * @return DBDate
   */
  private static function verificaDataVencimento(DBDate $oData, $iClassificacao) {

    $iDias = 0;
    switch ($iClassificacao) {

      case self::RECURSO_VINCULADO:
      case self::MATERIAL_SERVICO:
        $iDias = 30;
        break;

      case self::PEQUENO_VALOR:
        $iDias = 5;
        break;
    }

    if ($iDias == 0) {
      return $oData;
    }

    return new DBDate(date('Y-m-d', db_stdClass::getIntervaloDiasUteis($oData->getTimeStamp(), ++$iDias)));
  }


  /**
   * Vincula um empenho em uma classificação
   *
   * @param EmpenhoFinanceiro $oEmpenho
   * @param int    $iCodigoClassificacao
   * @param string $sJustificativa
   *
   * @return bool
   * @throws DBException
   * @throws ParameterException
   */
  public static function vincularEmpenhoEmClassificacao(EmpenhoFinanceiro $oEmpenho, $iCodigoClassificacao, $sJustificativa = null) {

    $oDaoClassificacaoEmpenho = new cl_classificacaocredoresempenho();
    $oDaoClassificacaoEmpenho->excluir(null, "cc31_empempenho = {$oEmpenho->getNumero()}");
    $oDaoClassificacaoEmpenho->cc31_sequencial            = null;
    $oDaoClassificacaoEmpenho->cc31_justificativa         = pg_escape_string($sJustificativa);
    $oDaoClassificacaoEmpenho->cc31_classificacaocredores = $iCodigoClassificacao;
    $oDaoClassificacaoEmpenho->cc31_empempenho            = $oEmpenho->getNumero();
    $oDaoClassificacaoEmpenho->incluir(null);
    if ($oDaoClassificacaoEmpenho->erro_status == "0") {
      throw new DBException("Impossível incluir o vínculo entre o empenho e a classificação do credor.");
    }
    return true;
  }

  /**
   * Método que valida os parâmetros necessários para a nota do Empenho.
   *
   * @deprecated
   * @param EmpenhoFinanceiro $oEmpenho          Empenho para o qual a nota está sendo criada.
   * @param string            $sDataNota         Data da nota.
   * @param string            $sDataRecebimento  Data de recebimento data nota.
   * @param string            $sDataVencimento   Data de vencimento da nota.
   * @param string            $sLocalRecebimento Local de recebimento da nota.
   *
   * @throws ParameterException caso algum valor seja inválido para o empenho informado.
   */
  public static function validaParametros($oEmpenho, $sDataNota, $sDataRecebimento, $sDataVencimento, $sLocalRecebimento) {

    $iClassificacaoCredor = $oEmpenho->getClassificacaoCredor();
    $lObrigaPreenchimento = $iClassificacaoCredor != null && $iClassificacaoCredor != ClassificacaoCredor::DISPENSA;

    if (empty($sDataNota)) {
      throw new ParameterException('O campo Data da Nota é de preeenchimento obrigatório.');
    }

    if (empty($sDataRecebimento)) {
      throw new ParameterException('O campo Data de Recebimento é de preenchimento obrigatório.');
    }

    if ($lObrigaPreenchimento && empty($sDataVencimento)) {
      throw new ParameterException('O campo Data de Vencimento é de preenchimento obrigatório.');
    }

    if ($lObrigaPreenchimento && empty($sLocalRecebimento)) {
      throw new ParameterException('O campo Local de Recebimento é de preenchimento obrigatório.');
    }
  }

  /**
   * Método que realiza a validação das datas necessárias para a nota do Empenho.
   *
   * @deprecated
   * @param EmpenhoFinanceiro $oEmpenho         Empenho para o qual a nota está sendo criada.
   * @param DBDate            $oDataNota        Data da nota.
   * @param DBDate            $oDataRecebimento Data de recebimento da nota.
   * @param DBDate            $oDataVencimento  Data de vencimento da nota.
   * @param string            $sMensagem        Caminho da mensagem de validação da data de vencimento.
   *
   * @throws BusinessException caso alguma data seja inválida para o empenho informado.
   */
  public static function validaDatas($oEmpenho, $oDataNota, $oDataRecebimento, $oDataVencimento = null, $sMensagem = null) {

    $iClassificacaoCredor = $oEmpenho->getClassificacaoCredor();

    if ($oDataRecebimento->getTimeStamp() < $oDataNota->getTimeStamp()) {
      throw new BusinessException(_M(self::MENSAGENS . 'data_recebimento_menor'));
    }
    if ($oDataVencimento &&  $oDataVencimento->getTimeStamp() < $oDataRecebimento->getTimeStamp()) {
      throw new BusinessException(_M(self::MENSAGENS . 'data_vencimento_menor'));
    }

    $oDataLimite = null;
    if ($iClassificacaoCredor != ClassificacaoCredor::DISPENSA && $iClassificacaoCredor != null) {
      $oDataLimite     = ClassificacaoCredor::getDataDeVencimentoPorData($oDataRecebimento, $iClassificacaoCredor);
    }

    $lTemDatas     = $oDataVencimento && $oDataLimite;
    $lDataInvalida = $lTemDatas ? $oDataVencimento->getTimeStamp() > $oDataLimite->getTimeStamp() : false;
    if ($lTemDatas && $lDataInvalida) {

      $iQuantidadeDias = ClassificacaoCredor::getQuantidadeDiasPorClassificacao($iClassificacaoCredor);
      $sClassificacao  = ClassificacaoCredor::getDescricaoDaClassificacao($iClassificacaoCredor);
      $aParametrosMensagem = array(
        'sClassificacao'  => $sClassificacao,
        'iQuantidadeDias' => $iQuantidadeDias,
      );
      if ($sMensagem === null) {
        $sMensagem = 'data_vencimento_invalida';
      }
      throw new BusinessException(_M(self::MENSAGENS . $sMensagem, (object) $aParametrosMensagem));
    }
  }
}
