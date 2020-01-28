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

namespace ECidade\Tributario\Agua\Coletor\Exportacao;

use ECidade\Tributario\Agua\Coletor\Exportacao\Arquivo\Arquivo;

class Processamento {

  const NAO_IMPRIME_CONTA        = 1;
  const IMPRIME_CONTA            = 2;
  const IMPRIME_CONTA_SEM_CODIGO = 3;

  /**
   * @var Arquivo[]
   */
  private $aArquivos;

  /**
   * @var int
   */
  private $iCodigoExportacao;

  /**
   * @param $iCodigoExportacao
   */
  public function setCodigoExportacao($iCodigoExportacao) {
    $this->iCodigoExportacao = $iCodigoExportacao;
  }

  /**
   * @return int
   */
  public function getCodigoExportacao() {
    return $this->iCodigoExportacao;
  }

  /**
   * @param Arquivo $oArquivo
   */
  public function adicionarArquivo(Arquivo $oArquivo) {
    $this->aArquivos[] = $oArquivo;
  }

  /**
   * Gera os arquivos e retorna uma coleção de File
   * @return \File[]
   * @throws \BusinessException
   */
  public function processar() {

    $aArquivosProcessados = array();
    $oDataExportacao      = new \DateTime();
    foreach ($this->aArquivos as $oArquivo) {
      try {

        $sNomeArquivo = sprintf('%s_%s_%s',
          $this->getCodigoExportacao(), $oDataExportacao->getTimestamp(), strtoupper($oArquivo->getNomeArquivo())
        );

        $oArquivo->setNomeArquivo($sNomeArquivo);
        $aArquivosProcessados[] = $oArquivo->gerar();

      } catch (\Exception $oErro) {
        throw new \BusinessException("Ocorreu um erro ao processar o arquivo de {$oArquivo->getNomeArquivo()}");
      }
    }

    return $aArquivosProcessados;
  }

  /**
   * @param $iCodigoContrato
   * @return int
   * @throws \DBException
   * @throws \ParameterException
   * @deprecated
   */
  public function getQuantidadeEconomias($iCodigoContrato) {

    $oContrato = new \AguaContrato();
    $oContrato->setCodigo($iCodigoContrato);

    return $oContrato->getQuantidadeEconomias();
  }

  /**
   * @param $iCodigoContrato
   * @return \AguaHidrometro
   * @throws \BusinessException
   */
  public function getHidrometro($iCodigoContrato) {

    $oAguaContrato = new \AguaContrato();
    $oAguaContrato->setCodigo($iCodigoContrato);
    $aHidrometros = $oAguaContrato->getHidrometros();

    if (!$aHidrometros) {
      throw new \BusinessException('Nenhum Hidrômetro encontrado para o Contrato informado.');
    }

    return current($aHidrometros);
  }

  /**
   * @param $iCodigoMatricula
   * @return int
   * @throws \DBException
   * @throws \ParameterException
   */
  public function getImprimeConta($iCodigoMatricula) {

    if (!$iCodigoMatricula) {
      throw new \ParameterException('Código da Matrícula é inválido.');
    }

    $oDaoDebitoConta = new \cl_debcontapedidomatric();
    $sSqlDebitoConta = $oDaoDebitoConta->sql_query_file(
      null, 'count(d68_codigo) as possui_debito_conta', null, "d68_matric = {$iCodigoMatricula}"
    );
    $rsDebitoConta = db_query($sSqlDebitoConta);

    if (!$rsDebitoConta) {
      throw new \DBException("Não foi possível obter as informações de Débito em Conta.");
    }

    $oDebitoConta = pg_fetch_object($rsDebitoConta);

    return ($oDebitoConta->possui_debito_conta ? self::IMPRIME_CONTA_SEM_CODIGO : self::IMPRIME_CONTA);
  }

  /**
   * @param $iCodigoResponsavel
   * @return int
   * @throws \DBException
   * @throws \ParameterException
   */
  public function getCodigoIsencao($iCodigoResponsavel) {

    if (!$iCodigoResponsavel) {
      throw new \ParameterException('Código do Responsável é inválido.');
    }

    $oDaoAguaIsencaoCgm = new \cl_aguaisencaocgm();
    $sWhere = implode(' and ', array(
      'x56_datainicial < now()',
      '(x56_datafinal > now() or x56_datafinal is null)',
      "x56_cgm = {$iCodigoResponsavel}"
    ));

    $sSqlAguaIsencaoCgm = $oDaoAguaIsencaoCgm->sql_query_file(null, 'x56_sequencial', null, $sWhere . ' limit 1');
    $rsAguaIsencaoCgm = db_query($sSqlAguaIsencaoCgm);

    if (!$rsAguaIsencaoCgm) {
      throw new \DBException("Não foi possível obter as informações de Isenção.");
    }

    if (!pg_num_rows($rsAguaIsencaoCgm)) {
      return false;
    }

    $oIsencao = pg_fetch_object($rsAguaIsencaoCgm);

    return $oIsencao->x56_sequencial;
  }

  /**
   * @param $iCodigoMatricula
   * @param $iAno
   * @param $iMes
   * @return bool
   * @throws \DBException
   * @throws \ParameterException
   */
  public function getDataVencimento($iCodigoMatricula, $iAno, $iMes) {

    if (!$iCodigoMatricula) {
      throw new \ParameterException('Código da Matrícula é inválido.');
    }

    if (!$iAno) {
      throw new \ParameterException('Ano não informado.');
    }

    if (!$iMes) {
      throw new \ParameterException('Més não informado.');
    }

    $sSqlDataVencimento = "select fc_agua_datavencimento({$iAno}, {$iMes}, {$iCodigoMatricula}) as data_vencimento";
    $rsDataVencimento = db_query($sSqlDataVencimento);

    if (!$rsDataVencimento) {
      throw new \DBException("Não foi possível obter as informações de Data de Vencimento.");
    }

    if (!pg_num_rows($rsDataVencimento)) {
      return false;
    }

    $oDataVencimento = pg_fetch_object($rsDataVencimento);

    return $oDataVencimento->data_vencimento;
  }

  /**
   * @param $iCodigoMatricula
   * @param $iAno
   * @param $iMes
   * @return bool
   * @throws \DBException
   */
  public function isContratoExportado($iCodigoContrato, $iAno, $iMes) {

    $sSqlContratoExportado = "
      select x51_sequencial
      from aguacoletorexportadadosleitura
        inner join agualeitura                     on x21_codleitura = x51_agualeitura
        inner join aguacoletorexportadados         on x50_sequencial = x51_aguacoletorexportadados
        inner join aguacoletorexporta              on x49_sequencial = x50_aguacoletorexporta
        inner join aguacoletorexportadadoscontrato on x57_aguacoletorexportadados = x50_sequencial
     where x49_anousu     = {$iAno}
       and x49_mesusu     = {$iMes}
       and x57_aguacontrato = {$iCodigoContrato}
       and x21_tipo       = 3
       and x21_status     = 1
    limit 1
    ";

    $rsContratoExportado = db_query($sSqlContratoExportado);
    if (!$rsContratoExportado) {
      throw new \DBException("Não foi possível obter informações da Matrícula Exportada.");
    }

    return (boolean) pg_num_rows($rsContratoExportado);
  }
}
