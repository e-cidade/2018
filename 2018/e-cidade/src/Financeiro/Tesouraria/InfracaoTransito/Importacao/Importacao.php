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

namespace ECidade\Financeiro\Tesouraria\InfracaoTransito\Importacao;

use DateTime;
use ECidade\Financeiro\Tesouraria\InfracaoTransito\ArquivoInfracao;
use ECidade\Financeiro\Tesouraria\InfracaoTransito\Multa;
use ECidade\Financeiro\Tesouraria\InfracaoTransito\Repository\ReceitaInfracao as ReceitaInfracaoRepository;
use ECidade\Financeiro\Tesouraria\InfracaoTransito\Repository\InfracaoTransito as InfracaoTransitoRepository;
use ECidade\Financeiro\Tesouraria\InfracaoTransito\Repository\ArquivoInfracao as ArquivoInfracaoRepository;
use \DBLayoutReader;
use \cl_taborc;
use \cl_tabrec;
use \PlanilhaArrecadacao;
use \ReceitaPlanilha;
use \InstituicaoRepository;
use \CaracteristicaPeculiar;
use \contaTesouraria;
use \DBDate;
use \ReceitaContabil;

class Importacao
{
    const CODIGO_LAYOUT = 282;

    /**
     * @var string
     */
    private $sCaminhoArquivo;

    /**
     * @var PlanilhaArrecadacao
     */
    private $oPlanilhaArrecadacao;

    /**
     * @var ArquivoInfracao
     */
    private $oArquivoInfracao;

    /**
     * @var array
     */
    private $aReceitasInfracoes;


    /**
     * @var DateTime
     */
    private $data_arquivo;

    /**
     * @var string
     */
    private $convenio_arquivo;

    /**
     * @var string;
     */
    private $remessa_arquivo;

    /**
     * @var float
     */
    private $valor_prefeitura = 0;

    /**
     * @var float
     */
    private $valor_duplicado = 0;

    /**
     * @var float
     */
    private $valor_funset = 0;

    /**
     * @var float
     */
    private $valor_detran = 0;

    /**
     * Multas não cadastradas no sistema
     * @var Multa[]
     */
    private $multasNaoCadastradas = array();

    /**
     * Existe registro de Trailler do arquivo. caso a o paremetro for false,arquivo nao foi importado.
     * @var bool
     */
    private $importacaoRegistro9 = false;

    /**
     * @param $sCaminhoArquivo
     */
    public function setCaminhoArquivo($sCaminhoArquivo)
    {
        $this->sCaminhoArquivo = $sCaminhoArquivo;
    }

    /**
     * Processa a importacao do arquivo
     * @return bool
     * @throws \BusinessException
     * @throws \DBException
     */
    public function processar()
    {

        if (!\db_utils::inTransaction()) {
            throw new \DBException('Sem transação iniciada.');
        }

        $iAno = date("Y", db_getsession("DB_datausu"));

        /* Obtem os parametros do ano */
        $oReceitaInfracaoRepository = ReceitaInfracaoRepository::getInstance();
        $this->aReceitasInfracoes = $oReceitaInfracaoRepository->getByAno($iAno);

        if (empty($this->aReceitasInfracoes)) {
            throw new \BusinessException('Não há parâmetros de receitas de infrações para o exercicio de ' . $iAno);
        }

        $oLayoutReader = new DBLayoutReader(self::CODIGO_LAYOUT, $this->sCaminhoArquivo);

        $this->registraPlanilha();

        foreach ($oLayoutReader->getLines() as $oLinha) {
            if (!$oLinha instanceof \DBLayoutLinha) {
                continue;
            }
            $this->processaLinha($oLinha);
        }

        if (!$this->importacaoRegistro9) {
            throw new \BusinessException("Arquivo não possui trailler válido.\nImportação não realizada.");
        }
        $this->oPlanilhaArrecadacao->salvar();
        return true;
    }

    /**
     * Verifica o tipo da linha e executa o processamento especifico
     * @param \DBLayoutLinha $oLinha
     */
    private function processaLinha($oLinha)
    {
        $iIdentificador = $oLinha->identificadorregistro;

        switch ($iIdentificador) {
            case '0':
                $this->obtemInformacoesArquivo($oLinha);
                break;

            case '1':
                $this->registraLancamentoReceita($oLinha);
                break;

            case '9':
                $this->registraImportacaoArquivoInfracoes($oLinha);
                break;
        }
    }

    /**
     * Registra a planilha de receitas
     */
    private function registraPlanilha()
    {
        $oPlanilhaArrecadacao = new PlanilhaArrecadacao();
        $dtArrecadacao = date('Y-m-d', db_getsession('DB_datausu'));
        $oPlanilhaArrecadacao->setDataCriacao($dtArrecadacao);
        $oPlanilhaArrecadacao->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit')));

        $this->oPlanilhaArrecadacao = $oPlanilhaArrecadacao;
    }

    /**
     * @param \DBLayoutLinha $oLinha
     * @throws \BusinessException
     */
    private function obtemInformacoesArquivo(\DBLayoutLinha $oLinha)
    {
        if (!$oLinha instanceof \DBLayoutLinha) {
            throw new \BusinessException("O Header do arquivo não é uma linha valida.");
        }

        $this->data_arquivo = new DateTime($oLinha->datamovimento);
        $this->convenio_arquivo = $oLinha->identificacao;
        $this->remessa_arquivo = $oLinha->sequencialremessa;

        $oArquivoInfracaoRepository = ArquivoInfracaoRepository::getInstance();
        $oArquivoInfracaoModel = $oArquivoInfracaoRepository->getByDataConvenioRemessa($this->data_arquivo, $this->convenio_arquivo, $this->remessa_arquivo);

        if (!empty($oArquivoInfracaoModel)) {
            throw new \BusinessException("Este arquivo já foi importado e processado!");
        }

        $oArquivoInfracao = new ArquivoInfracao();
        $this->oArquivoInfracao = $oArquivoInfracao;
    }

    /**
     * Efetuar o registro da receita baseado na infracao recebida do arquivo
     * @param \DBLayoutLinha $oLinha
     * @return bool
     * @throws \BusinessException
     */
    private function registraLancamentoReceita($oLinha)
    {
        if (!$oLinha instanceof \DBLayoutLinha) {
            throw new \BusinessException("A linha de registro do arquivo não é uma linha valida.");
        }

        $oMulta = $this->getMulta($oLinha);
        if (empty($oMulta)) {
            return false;
        }
        $this->oArquivoInfracao->adicionaMulta($oMulta);

        /* Obtem os dados de receitas referente ao nivel da infracao de transito */
        $oReceitaInfracaoLancamento = null;
        foreach ($this->aReceitasInfracoes as $oReceitaInfracao) {
            if ($oReceitaInfracao->getNivel() != $oMulta->getNivel()) {
                continue;
            }

            $oReceitaInfracaoLancamento = $oReceitaInfracao;
        }

        if (empty($oReceitaInfracaoLancamento)) {
            throw new \BusinessException('Não há receita cadastrada para infração do nível: ' . $oMulta->getNivel());
        }

        $iReceita = $oReceitaInfracaoLancamento->getReceitaPrincipal();
        /* Se for um pagamento em duplicidade troca a receita */
        if ($oMulta->isDuplicado()) {
            $iReceita = $oReceitaInfracaoLancamento->getReceitaDuplicidade();
        }

        /* Obtem codigo da receita orcamentaria */
        $iCodigoReceitaOrcamentaria = $this->getIdReceitaOrcamentariaByIdReceitaTesouraria($iReceita);

        /* Obtem a model da Receita Contabil */
        $oReceita = new ReceitaContabil($iCodigoReceitaOrcamentaria);

        $oDaoReceita = $this->getReceitaById($iReceita);

        $sObservacao = "Lançamento referente a infração de trânsito importada no arquivo da data: ";
        $sObservacao .= $this->data_arquivo->format('d/m/Y');
        $sObservacao .= " Convênio: {$this->convenio_arquivo} Remessa: {$this->remessa_arquivo}";

        $oInstituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));

        /* Cria a receita da Planilha */
        $oReceitaPlanilha = new ReceitaPlanilha();
        $oReceitaPlanilha->setContaTesouraria(new contaTesouraria($oReceitaInfracaoLancamento->getConta()));
        $oReceitaPlanilha->setTipoReceita($oDaoReceita->k02_codigo);
        $oReceitaPlanilha->setValor($oMulta->getValorPrefeitura());
        $oReceitaPlanilha->setObservacao($sObservacao);
        $oReceitaPlanilha->setCaracteristicaPeculiar(new CaracteristicaPeculiar($oReceita->getCaracteristicaPeculiar())); // 000 - Nao se aplica
        $oReceitaPlanilha->setRecurso($oReceita->getRecurso());

        $oDataRepasse = new DBDate($oMulta->getDataRepasse()->format('Y-m-d'));
        $oReceitaPlanilha->setDataRecebimento($oDataRepasse);

        $oReceitaPlanilha->setOrigem(1); // 1 - CGM
        $oReceitaPlanilha->setCGM($oInstituicao->getCgm());

        $this->oPlanilhaArrecadacao->adicionarReceitaPlanilha($oReceitaPlanilha);
        return true;
    }

    /**
     * @param \DBLayoutLinha $oLinha
     * @throws \BusinessException
     */
    private function registraImportacaoArquivoInfracoes($oLinha)
    {
        if (!$oLinha instanceof \DBLayoutLinha) {
            throw new \BusinessException("O Trailler do arquivo não é uma linha valida.");
        }
        $this->importacaoRegistro9 = true;

        $oArquivoInfracaoRepository = ArquivoInfracaoRepository::getInstance();
        $oArquivoInfracaoModel = $this->oArquivoInfracao;
        $oArquivoInfracaoModel->setDataImportacao(new DateTime(date("Y-m-d", db_getsession("DB_datausu"))));
        $oArquivoInfracaoModel->setDataPagamento(null);
        $oArquivoInfracaoModel->setDataRepasse(null);
        $oArquivoInfracaoModel->setRegistro((int) $oLinha->totalregistro);
        $fValorBruto = substr($oLinha->valorbruto, 0, 13) . '.' . substr($oLinha->valorbruto, 13, 2);
        $oArquivoInfracaoModel->setValorBruto((float) $fValorBruto);
        $oArquivoInfracaoModel->setValorPrefeitura($this->getValorPrefeitura());
        $oArquivoInfracaoModel->setValorDuplicado($this->getValorDuplicado());
        $oArquivoInfracaoModel->setValorFunset($this->getValorFunset());
        $oArquivoInfracaoModel->setValorDetran($this->getValorDetran());
        $oArquivoInfracaoModel->setDataMovimento($this->data_arquivo);
        $oArquivoInfracaoModel->setRemessa($this->remessa_arquivo);
        $oArquivoInfracaoModel->setConvenio($this->convenio_arquivo);
        $oArquivoInfracaoModel->setValorPrestacaoContas(null);
        $oArquivoInfracaoModel->setValorOutros(null);

        $oArquivoInfracaoRepository->salvar($oArquivoInfracaoModel);
    }

    /**
     * @param $iId
     * @return null|\stdClass
     * @throws \BusinessException
     */
    private function getReceitaById($iId)
    {
        $oDaoTabRec = new cl_tabrec();
        $oDados = \db_utils::getRowFromDao($oDaoTabRec, array($iId));

        if (empty($oDados)) {
            throw new \BusinessException('Não existe receita cadastrada com o código: ' . $iId);
        }

        return $oDados;
    }

    /**
     * @return float
     */
    private function getValorPrefeitura()
    {
        return $this->valor_prefeitura;
    }

    /**
     * @param float $valor_prefeitura
     */
    private function addValorPrefeitura($valor_prefeitura)
    {
        $this->valor_prefeitura += $valor_prefeitura;
    }

    /**
     * @return float
     */
    private function getValorDuplicado()
    {
        return $this->valor_duplicado;
    }

    /**
     * @param float $valor_duplicado
     */
    private function addValorDuplicado($valor_duplicado)
    {
        $this->valor_duplicado += $valor_duplicado;
    }

    /**
     * @return float
     */
    private function getValorFunset()
    {
        return $this->valor_funset;
    }

    /**
     * @param float $valor_funset
     */
    private function addValorFunset($valor_funset)
    {
        $this->valor_funset += $valor_funset;
    }

    /**
     * @return float
     */
    private function getValorDetran()
    {
        return $this->valor_detran;
    }

    /**
     * @param float $valor_detran
     */
    private function addValorDetran($valor_detran)
    {
        $this->valor_detran += $valor_detran;
    }

    /**
     * @param $iId
     * @return integer
     * @throws \BusinessException
     * @throws \DBException
     */
    private function getIdReceitaOrcamentariaByIdReceitaTesouraria($iId)
    {
        $iAno = db_getsession('DB_anousu');
        $oDaoTabOrc = new cl_taborc();
        $sSql = $oDaoTabOrc->sql_query_file($iAno, $iId, 'k02_codrec');
        $rsDao = db_query($sSql);

        if (!$rsDao) {
            throw new \DBException("Houve um erro ao buscar a receita orçamentária para a receita {$iId}");
        }

        $oDados = pg_fetch_object($rsDao);

        if (empty($oDados)) {
            throw new \BusinessException("A receita {$iId} não esta vinculada com a receita orçamentária para o ano {$iAno}");
        }

        return $oDados->k02_codrec;
    }

    /**
     * Metodo para criar uma instancia de Multa a partir de um \DBLayoutLinha
     * @param \DBLayoutLinha $oLinha
     * @return Multa $oMultaModel
     * @throws \BusinessException
     */
    private function getMulta($oLinha)
    {
        if (!$oLinha instanceof \DBLayoutLinha) {
            throw new \BusinessException("O Trailler do arquivo não é uma linha valida.");
        }

        $oMultaModel = new Multa;

        $oMultaModel->setCodigoInfracaoTransito((string)$oLinha->codigoinfracao);
        $oMultaModel->setNossoNumero((string)$oLinha->nossonumero);
        $oMultaModel->setAutoInfracao((string)$oLinha->autoinfracao);

        /* Obtem os dados da infracao de transito recebida do arquivo */
        $oInfracaoTransitoRepository = InfracaoTransitoRepository::getInstance();
        try {
            $oInfracaoTransito = $oInfracaoTransitoRepository->getByCodigoInfracao($oLinha->codigoinfracao);
        } catch (\BusinessException $e) {
            if (empty($oInfracaoTransito)) {
                $this->multasNaoCadastradas[] = $oMultaModel;
                return null;
            }
        }

        $oDataPagamento = new DateTime($oLinha->datapagamento);
        $oDataRepasse = new DateTime($oLinha->datarepasse);

        /* Formata o valor */
        $fValorPrefeitura = (float)substr($oLinha->valorprefeitura, 0, 12) . '.' . substr($oLinha->valorprefeitura, 12, 2);
        $fValorBruto = (float)substr($oLinha->valorbruto, 0, 12) . '.' . substr($oLinha->valorbruto, 12, 2);
        $fValorDetran = (float)substr($oLinha->valordetran, 0, 12) . '.' . substr($oLinha->valordetran, 12, 2);
        $fValorFunset = (float)substr($oLinha->valorfunset, 0, 12) . '.' . substr($oLinha->valorfunset, 12, 2);

        /* Incrementa os valores da importacao */
        $this->addValorDetran($fValorDetran);
        $this->addValorFunset($fValorFunset);

        /* Verifica se for pagamento em duplicidade */
        if ($fValorBruto == $fValorPrefeitura) {
            $this->addValorDuplicado($fValorPrefeitura);
            $oMultaModel->setDuplicado(true);
        } else {
            $oMultaModel->setDuplicado(false);
            $this->addValorPrefeitura($fValorPrefeitura);
        }

        $oMultaModel->setDataPagamento($oDataPagamento);
        $oMultaModel->setDataRepasse($oDataRepasse);
        $oMultaModel->setNivel($oInfracaoTransito->getNivel());
        $oMultaModel->setValorBruto($fValorBruto);
        $oMultaModel->setValorPrefeitura($fValorPrefeitura);
        $oMultaModel->setValorDetran($fValorDetran);
        $oMultaModel->setValorFunset($fValorFunset);

        return $oMultaModel;
    }

    /**
     * Verificar se existem multas não processadas
     * @return bool
     */
    public function temMultasNaoProcessadas()
    {
        return count($this->multasNaoCadastradas) > 0;
    }

    /**
     * Retorna todas as multas nao cadastradas.
     * @return Multa[]
     */
    public function getMultasNaoCadastradas()
    {
        return $this->multasNaoCadastradas;
    }

    /**
     * @return PlanilhaArrecadacao
     */
    public function getPlanilhaArrecadacao()
    {
        return $this->oPlanilhaArrecadacao;
    }
}
