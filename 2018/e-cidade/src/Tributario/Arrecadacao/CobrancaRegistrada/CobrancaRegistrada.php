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

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\RegistroCollection;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\Header;

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Arquivo\Repository;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Arquivo\IncluiBoleto;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Manutencao;

class CobrancaRegistrada
{

    /**
     * Verifica se o convênio passado por parâmetro é um convenio de cobrança valido
     * @param integer $iCodigoConvenio Código do convenio
     * @throws \Exception
     * @return boolean
     */
    public static function validaConvenioCobranca($iCodigoConvenio)
    {
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
     * Adiciona um recibo na fila para geração dos arquivos de cobrança registrada
     *
     * @param \Recibo $oRecibo
     * @param integer $iCodigoConvenio
     * @throws \Exception
     * @return boolean
     */
    public static function adicionarRecibo(\Recibo $oRecibo, $iCodigoConvenio)
    {
        if (empty($iCodigoConvenio)) {
            throw new \Exception("Código do convênio não informado.");
        }

        if ($oRecibo->getNumpreRecibo() == '') {
            throw new \Exception("Recibo inválido.");
        }

        $oDaoReciboRegistra = new \cl_reciboregistra();
        $oDaoReciboRegistra->k146_numpre = $oRecibo->getNumpreRecibo();
        $oDaoReciboRegistra->k146_convenio = $iCodigoConvenio;
        $oDaoReciboRegistra->incluir();

        if ($oDaoReciboRegistra->erro_status == '0') {
            throw new \Exception("Erro ao incluir recibo para cobrança registrada.");
        }

        return true;
    }


    /**
     * Remove os recibos passados por parametro
     *
     * @param array $aRecibos
     * @return boolean
     */
    public static function removerRecibos($aRecibos)
    {
        if (empty($aRecibos)) {
            return false;
        }

        $oDaoRecibos = new \cl_reciboregistra();

        /**
         * Remove os recibos da fila
         */
        $oDaoRecibos->excluir(null, "k146_numpre in (" . implode(',', $aRecibos) . ")");

        if ($oDaoRecibos->erro_status = "0") {
            throw new \Exception("Erro ao remover os recibos da fila.");
        }

        return true;
    }

    /**
     * Função que carrega os dados necessários no model Header
     *
     * @param   integer $iCodigoConvenio
     * @return  Header
     */
    public static function carregarHeader($iCodigoConvenio)
    {
        if (empty($iCodigoConvenio)) {
            throw new \Exception("Código do convênio não informado.");
        }

        $oDaoReciboRegistra = new \cl_reciboregistra();
        $oDaoConveio        = new \cl_cadconvenio();

        /**
         * Buscamos os dados do convênnio através do código recebido nos parâmetros
         */
        $sCampos     = "ar11_cadtipoconvenio, ar11_instit, ar11_nome, ar13_carteira, ar13_convenio, ar13_cedente, ar13_variacao, ar13_contabancaria, ar13_responsavelnossonumero";
        $sSqlConveio = $oDaoConveio->sql_queryConvenioCobranca($iCodigoConvenio, $sCampos);
        $rsConveio   = db_query($sSqlConveio);

        if (empty($rsConveio)) {
            throw new \Exception("Erro ao buscar os dados do convênio para o Header");
        }

        $oConvenio      = \db_utils::fieldsMemory($rsConveio, 0);

        $oDadosConvenio = new \stdClass();
        $oDadosConvenio->instituicao              = $oConvenio->ar11_instit;
        $oDadosConvenio->nome                     = $oConvenio->ar11_nome;
        $oDadosConvenio->tipo_convenio            = $oConvenio->ar11_cadtipoconvenio;
        $oDadosConvenio->numero_convenio          = $oConvenio->ar13_convenio;
        $oDadosConvenio->carteira                 = $oConvenio->ar13_carteira;
        $oDadosConvenio->cedente                  = $oConvenio->ar13_cedente;
        $oDadosConvenio->variacao                 = $oConvenio->ar13_variacao;
        $oDadosConvenio->responsavel_nosso_numero = $oConvenio->ar13_responsavelnossonumero;

        $oInstituicao   = new \Instituicao($oConvenio->ar11_instit);
        $oContaBancaria = new \ContaBancaria($oConvenio->ar13_contabancaria);

        /**
         * Buscamos o novo seguencial da remessa
         */
        $sCampo      = "coalesce(max(k147_sequencialremessa), 0) + 1 as sequencial_remessa";
        $oDaoRemessa = new \cl_remessacobrancaregistrada();
        $sSqlRemessa = $oDaoRemessa->sql_query_file(null, $sCampo);
        $rsRemessa   = db_query($sSqlRemessa);

        if (empty($rsRemessa)) {
            throw new \Exception("Erro ao gerar o sequencial da Remessa");
        }

        $oRemessa    = \db_utils::fieldsMemory($rsRemessa, 0);

        /**
         * A partir de agora, colocamos os dados recolhidos acima no Header
         */
        $oHeader = new Header();
        $oHeader->setSequencial($oRemessa->sequencial_remessa);
        $oHeader->setLote(0000);
        $oHeader->setInstituicao($oInstituicao);
        $oHeader->setContaBancaria($oContaBancaria);
        $oHeader->setConvenio($oDadosConvenio);

        return $oHeader;
    }

    /**
     * Função que verifica se há usuário do webservice configurado
     *
     * @throws DBException
     * @return boolean
     */
    public static function utilizaIntegracaoWebService($iConvenio)
    {
        $oDaoCadConvenio = new \cl_cadconvenio();
        $sSqlCadConvenio = $oDaoCadConvenio->sql_queryConvenioCobranca($iConvenio);
        $rsCadConvenio   = db_query($sSqlCadConvenio);

        if (!$rsCadConvenio) {
            throw new \DBException("Erro ao buscar dados do convênio de cobrança.");
        }

        /**
         * Se não vier registro na consulta, o convênio não é de cobrança,
         * portanto não deve utilizar a integração com o webservice
         */
        if (pg_num_rows($rsCadConvenio) == 0) {
            return false;
        }

        $oConvenioCobranca = \db_utils::fieldsMemory($rsCadConvenio, 0);

        $oDaoBancoAgencia = new \cl_bancoagencia();
        $sSqlBancoAgencia = $oDaoBancoAgencia->sql_query_file($oConvenioCobranca->ar13_bancoagencia, "db89_db_bancos");
        $rsBancoAgencia   = $oDaoBancoAgencia->sql_record($sSqlBancoAgencia);

        if (!$rsBancoAgencia) {
            throw new \DBException("Erro ao buscar dados da agência do convênio de cobrança.");
        }

        $oBancoAgencia = \db_utils::fieldsMemory($rsBancoAgencia, 0);

        if ($oBancoAgencia->db89_db_bancos != Manutencao::CODIGO_BANCO) {
            return false;
        }

        $oDaoParametrosCobrancaRegistrada = new \cl_parametroscobrancaregistrada();
        $sSqlParametrosCobrancaRegistrada = $oDaoParametrosCobrancaRegistrada->sql_query_file(null, 'ar28_usuario', null, "ar28_usuario is not null and ar28_usuario != ''");
        $rsParametrosCobrancaRegistrada   = \db_query($sSqlParametrosCobrancaRegistrada);

        if (empty($rsParametrosCobrancaRegistrada)) {
            throw new \DBException("Erro ao buscar dados de configurão.");
        }

        return pg_num_rows($rsParametrosCobrancaRegistrada) > 0;
    }

    /**
     * Função que realiza o processo de cobrança registrada no webservice
     *
     * @param  integer  $iNumpreRecibo
     * @param  integer  $iConvenio
     * @param  float    $nValor
     * @param  boolean  $lUsuarioExterno
     *
     * @throws BusinessException
     */
    public static function registrarReciboWebservice($iNumpreRecibo, $iConvenio, $nValor, $lUsuarioExterno = false, $aEmitirPor = array())
    {

        $oDataSessao = new \DateTime;
        $oDataSessao->setTimestamp(db_getsession('DB_datausu'));
        $oDataServidor = new \DateTime('now');

        /**
         * Não registra a cobrança em função de recibos que precisam ser baixados, devido a já terem
         * sido pagos por fora do sistema (exemplo: inicial do foro, depósito judicial ou penhora).
         */
        if ($oDataSessao->getTimestamp() < $oDataServidor->getTimestamp()) {
            return false;
        }

        $oRepository = new Repository();
        $oRegistro   = $oRepository->getDadosIncluiBoleto($iNumpreRecibo, $iConvenio, $nValor, $aEmitirPor);

        $oIncluiBoleto = new IncluiBoleto($oRegistro);
        $oManutencao   = new Manutencao($oIncluiBoleto);

        $oManutencao->processarRequisicao();
        $oResposta = $oManutencao->getResposta();

        $iCodigoRetorno = $oResposta->getCodigoRetorno();

        if (!empty($iCodigoRetorno)) {
            throw new \BusinessException($oResposta->getMensagemRetorno($lUsuarioExterno));
        }
    }
}
