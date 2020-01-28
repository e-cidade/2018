<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada;

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Arquivo\Repository;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Arquivo\IncluiBoleto;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Manutencao;

class CobrancaRegistrada
{
  /**
   * Verifica se o conv�nio passado por par�metro � um convenio de cobran�a valido
   * @param integer $iCodigoConvenio C�digo do convenio
   * @throws \Exception
   * @return boolean
   */
  public static function validaConvenioCobranca($iCodigoConvenio)
  {
    require_once "classes/db_cadconvenio_classe.php";

    $oDaoConvenio = new \cl_cadconvenio();
    $sSqConvenio  = $oDaoConvenio->sql_query_convenio_cobranca($iCodigoConvenio, "ar12_sequencial, ar12_cadconveniomodalidade, ar13_carteira");
    $rsConvenio   = \db_query($sSqConvenio);

    if (empty($rsConvenio) || !pg_num_rows($rsConvenio)) {
      throw new \Exception("Erro ao buscar dados do convenio.");
    }

    $oDadosConvenio = \db_utils::fieldsMemory($rsConvenio, 0);

    if ($oDadosConvenio->ar12_cadconveniomodalidade != \convenio::MODALIDADE_COBRANCA) {
      return false;
    }

    /**
     * Quando o convenio for do Banco do Brasil, a acarteira deve ser a 17
     */
    if ($oDadosConvenio->ar12_sequencial == \convenio::TIPO_CONVENIO_COMPENSACAO_BDL && $oDadosConvenio->ar13_carteira == "17") {
      return true;
    }

    /**
     * Quando o convenio for da Caixa, a carteira deve ser a 14
     */
    if ($oDadosConvenio->ar12_sequencial == \convenio::TIPO_CONVENIO_COMPENSACAO_SIGCB && $oDadosConvenio->ar13_carteira == "14") {
      return true;
    }

    /**
     * Quando o convenio for da Banrisul
     */
    if ($oDadosConvenio->ar12_sequencial == \convenio::TIPO_CONVENIO_COMPENSACAO_BDL && $oDadosConvenio->ar13_carteira == "1") {
      return true;
    }

    return false;
  }

  /**
   * Adiciona um recibo na fila para gera��o dos arquivos de cobran�a registrada
   *
   * @param \Recibo $oRecibo
   * @param integer $iCodigoConvenio
   * @throws \Exception
   * @return boolean
   */
  public static function adicionarRecibo($sNumpre, $iCodigoConvenio)
  {
    require_once "classes/db_reciboregistra_classe.php";

    if (empty($iCodigoConvenio)) {
      throw new \Exception("C�digo do conv�nio n�o informado.");
    }

    if (empty($sNumpre)) {
      throw new \Exception("Recibo inv�lido.");
    }

    $oDaoReciboRegistra = new \cl_reciboregistra();
    $oDaoReciboRegistra->k146_numpre = $sNumpre;
    $oDaoReciboRegistra->k146_convenio = $iCodigoConvenio;

    $oDaoReciboRegistra->incluir(null);

    if ($oDaoReciboRegistra->erro_status == '0') {
      throw new \Exception("Erro ao incluir recibo para cobran�a registrada.");
    }

    return true;
  }

  /**
   * Fun��o que verifica se h� usu�rio do webservice configurado
   *
   * @throws DBException
   * @return boolean
   */
  public static function utilizaIntegracaoWebService($iConvenio)
  {
    require_once "classes/db_cadconvenio_classe.php";
    require_once "classes/db_bancoagencia_classe.php";
    require_once "classes/db_parametroscobrancaregistrada_classe.php";

    $oDaoCadConvenio = new \cl_cadconvenio();
    $sSqlCadConvenio = $oDaoCadConvenio->sql_queryConvenioCobranca($iConvenio);
    $rsCadConvenio   = db_query($sSqlCadConvenio);

    if (!$rsCadConvenio) {
      throw new \DBException("Erro ao buscar dados do conv�nio de cobran�a.");
    }

    /**
     * Se n�o vier regitro na consulta, o conv�nio n�o � de cobran�a,
     * portanto n�o deve utilizar a integra��o com o webservice
     */
    if ( pg_num_rows($rsCadConvenio) == 0 ) {
      return false;
    }

    $oConvenioCobranca = \db_utils::fieldsMemory($rsCadConvenio, 0);

    $oDaoBancoAgencia = new \cl_bancoagencia();
    $sSqlBancoAgencia = $oDaoBancoAgencia->sql_query_file($oConvenioCobranca->ar13_bancoagencia, "db89_db_bancos");
    $rsBancoAgencia   = $oDaoBancoAgencia->sql_record($sSqlBancoAgencia);

    if ( !$rsBancoAgencia ) {
      throw new \DBException("Erro ao buscar dados da ag�ncia do conv�nio de cobran�a.");
    }

    $oBancoAgencia = \db_utils::fieldsMemory($rsBancoAgencia, 0);

    if ($oBancoAgencia->db89_db_bancos != Manutencao::CODIGO_BANCO) {
      return false;
    }

    $oDaoParametrosCobrancaRegistrada = new \cl_parametroscobrancaregistrada();
    $sSqlParametrosCobrancaRegistrada = $oDaoParametrosCobrancaRegistrada->sql_query_file(null, 'ar28_usuario', null, "ar28_usuario is not null and ar28_usuario != ''");
    $rsParametrosCobrancaRegistrada   = \db_query($sSqlParametrosCobrancaRegistrada);

    if (empty($rsParametrosCobrancaRegistrada)) {
      throw new \DBException("Erro ao buscar dados de configur�o.");
    }

    return pg_num_rows($rsParametrosCobrancaRegistrada) > 0;
  }

  /**
   * Fun��o que realiza o processo de cobran�a registrada no webservice
   *
   * @param  integer  $iNumpreRecibo
   * @param  integer  $iConvenio
   * @param  numeric  $nValor
   * @param  boolean  $lUsuarioExterno
   *
   * @throws Exception
   */
  public static function registrarReciboWebservice($iNumpreRecibo, $iConvenio, $nValor, $lUsuarioExterno = false)
  {
    $oRepository = new Repository();
    $oRegistro   = $oRepository->getDadosIncluiBoleto($iNumpreRecibo, $iConvenio, (string)$nValor);

    $oIncluiBoleto = new IncluiBoleto($oRegistro);
    $oManutencao   = new Manutencao($oIncluiBoleto);

    $oManutencao->processarRequisicao();
    $oResposta = $oManutencao->getResposta();

    $iCodigoRetorno = $oResposta->getCodigoRetorno();

    if ( !empty($iCodigoRetorno) ) {
      throw new \Exception($oResposta->getMensagemRetorno($lUsuarioExterno));
    }
  }
}
