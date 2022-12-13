<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2016  DBSelller Servicos de Informatica
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

namespace ECidade\Financeiro\Tesouraria\InfracaoTransito\Repository;

use ECidade\Financeiro\Tesouraria\InfracaoTransito\ArquivoInfracao as ArquivoInfracaoModel;
use ECidade\Financeiro\Tesouraria\InfracaoTransito\Repository\Multa as MultaRepository;

/**
 * Class ReceitaInfracao
 * Classe que representa o repository do model ReceitaInfracao
 * @package ECidade\Financeiro\Tesouraria\InfracaoTransito\Repository
 * @author Fabio Egidio <fabio.egidio@dbseller.com.br>
 */
class ArquivoInfracao extends \BaseClassRepository
{

    /**
     * Sobrescreve o atributo da classe pai para
     * manter apenas as referências da classe atual
     * @var ReceitaInfracao
     */
    protected static $oInstance;

    /**
     * Retorna uma instância da classe
     *
     * @param \stdClass $dados stdClass com os dados do registro a ser construido
     * @return ArquivoInfracaoModel
     */
    protected function make($dados)
    {

        if (empty($dados)) {
            return NULL;
        }

        $oArquivoInfracao = new ArquivoInfracaoModel();
        $oArquivoInfracao->setId($dados->i07_sequencial);
        $oArquivoInfracao->setDataImportacao($dados->i07_dtimportacao);
        $oArquivoInfracao->setDataPagamento($dados->i07_dtpagamento);
        $oArquivoInfracao->setDataRepasse($dados->i07_dtrepasse);
        $oArquivoInfracao->setRegistro($dados->i07_registro);
        $oArquivoInfracao->setValorBruto($dados->i07_vlbruto);
        $oArquivoInfracao->setValorPrefeitura($dados->i07_vlprefeitura);
        $oArquivoInfracao->setValorDuplicado($dados->i07_vlduplicado);
        $oArquivoInfracao->setValorFunset($dados->i07_vlfunset);
        $oArquivoInfracao->setValorDetran($dados->i07_vldetran);
        $oArquivoInfracao->setValorPrestacaoContas($dados->i07_vlprestacaocontas);
        $oArquivoInfracao->setValorOutros($dados->i07_vloutros);
        $oArquivoInfracao->setConvenio($dados->i07_convenio);
        $oArquivoInfracao->setRemessa($dados->i07_remessa);
        $oArquivoInfracao->setDataMovimento($dados->i07_dtmovimento);

        return $oArquivoInfracao;
    }

    /**
     * Retorna as informações do arquivo importado
     * @param  int $codigo
     * @return ArquivoInfracaoModel
     * @throws \DBException
     */
    public function getByCodigo($codigo)
    {

        $oDao = new \cl_arquivoinfracao();
        $oDados = $oDao->findBydId($codigo);


        if (empty($oDados)) {
            throw new \DBException("Houve uma falha ao buscar o arquivo com o código {$codigo}.");
        }
        return $this->make($oDados);

    }

    /**
     * Persiste o objeto da classe ArquivoInfracao no banco de dados.
     * @param ArquivoInfracaoModel $dadoArquivoInfracao
     * @return bool
     * @throws \Exception
     */
    public function salvar(ArquivoInfracaoModel $dadoArquivoInfracao)
    {
        if (empty($dadoArquivoInfracao)) {
            throw new \Exception("Ocorreu um erro ao incluir, o objeto está vazio.");
        }

        $oDao = new \cl_arquivoinfracao();

        $oDao->i07_dtimportacao = $dadoArquivoInfracao->getDataImportacao()->format('Y-m-d');
        $oDao->i07_dtpagamento = $dadoArquivoInfracao->getDataPagamento();
        $oDao->i07_dtrepasse = $dadoArquivoInfracao->getDataRepasse();
        $oDao->i07_registro = $dadoArquivoInfracao->getRegistro();
        $oDao->i07_vlbruto = $dadoArquivoInfracao->getValorBruto();
        $oDao->i07_vlprefeitura = $dadoArquivoInfracao->getValorPrefeitura();
        $oDao->i07_vlduplicado = $dadoArquivoInfracao->getValorDuplicado();
        $oDao->i07_vlfunset = $dadoArquivoInfracao->getValorFunset();
        $oDao->i07_vldetran = $dadoArquivoInfracao->getValorDetran();
        $oDao->i07_vlprestacaocontas = $dadoArquivoInfracao->getValorPrestacaoContas();
        $oDao->i07_vloutros = $dadoArquivoInfracao->getValorOutros();
        $oDao->i07_convenio = $dadoArquivoInfracao->getConvenio();
        $oDao->i07_remessa = $dadoArquivoInfracao->getRemessa();
        $oDao->i07_dtmovimento = $dadoArquivoInfracao->getDataMovimento()->format('Y-m-d');

        if (!$oDao->incluir()) {
            throw new \Exception("Ocorreu um erro ao incluir o arquivo." . $oDao->erro_msg);
        }

        if (count($dadoArquivoInfracao->getMultas()) > 0) {

            foreach ($dadoArquivoInfracao->getMultas() as $oMulta) {

                $oMulta->setIdArquivoInfracao($oDao->i07_sequencial);

                $oMultaRepository = MultaRepository::getInstance();
                $oMultaRepository->persist($oMulta);
            }
        }

        return true;
    }

    /**
     * Retorna as informações do arquivo importado
     * @param \DateTime $data
     * @param string $convenio
     * @param string $remessa
     * @return ArquivoInfracaoModel
     * @throws \DBException
     */
    public function getByDataConvenioRemessa($data, $convenio, $remessa)
    {

        $oDao = new \cl_arquivoinfracao();
        $sWhere = "i07_dtmovimento = '{$data->format('Y-m-d')}'";
        $sWhere .= " AND i07_convenio = '{$convenio}'";
        $sWhere .= " AND i07_remessa = '{$remessa}'";

        $sSql = $oDao->sql_query_file(null, '*', null, $sWhere);
        $rsDao = db_query($sSql);

        if (!$rsDao) {
            throw new \DBException("Houve uma falha ao buscar o arquivo do dia {$data->format('d/m/Y')} com remessa: {$remessa}.");
        }

        $oDados = pg_fetch_object($rsDao);

        return $this->make($oDados);

    }

}
