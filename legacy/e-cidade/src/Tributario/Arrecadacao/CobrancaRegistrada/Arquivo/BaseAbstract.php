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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo;

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\Header;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\RegistroCollection;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Registro;

/**
 * Classe abstrata para implementacao do Arquivo de Remessa de Cobranca
 *  Registrada CNAB 240 - v0.87
 */
abstract class BaseAbstract
{
    const CODIGO_LAYOUT_TXT = 263;
    const VERSAO_LAYOUT_ARQUIVO = '087';

    /**
     * @var RegistroCollection
     */
    private $oRegistrosCollection;

    /**
     * @var db_layouttxt
     */
    private $oLayoutTXT;

    /**
     * @var Header
     */
    protected $oHeader;

    /**
     * Recibos válidos gerados no arquivo
     * @var array
     */
    protected $aRecibosGerados = array();

    /**
     * Recibos inválidos que não foram no arquivo
     * @var array
     */
    protected $aRecibosInvalidos = array();

    /**
     * Controle de linhas de registros
     * @var integer
     */
    protected $iSequencialRegistro;

    /**
     * Callback a ser chamado a cada linha adicionada
     */
    protected $callBackFunc;

    public function setCallback($callback)
    {
        $this->callBackFunc = $callback;
    }

    /**
     * @return array
     */
    public function getRecibosGerados()
    {
        return $this->aRecibosGerados;
    }

    /**
     * @return array
     */
    public function getRecibosInvalidos()
    {
        return $this->aRecibosInvalidos;
    }

    /**
     * Função que retorna o objeto header
     * @param Header $oHeader
     */
    public function setHeader(Header $oHeader)
    {
        $this->oHeader = $oHeader;
    }

    /**
     * Define os registros a serem utilizados na remessa
     * @param RegistroCollection $oRegistrosCollection Registros Collection
     */
    public function setRegistros(RegistroCollection $oRegistrosCollection)
    {
        $this->oRegistrosCollection = $oRegistrosCollection;
    }

    public function gerarArquivo($lQuebraLinha = false)
    {
        if (empty($this->oHeader)) {
            throw new \Exception("Header do arquivo não informado para geração.");
        }

        /**
         * Zera os arrays de controle dos recibos
         */
        $this->aRecibosInvalidos = array();
        $this->aRecibosGerados = array();

        $sNomeArquivo = "tmp/cobranca_registrada_remessa_" . time() . ".txt";

        if (file_exists($sNomeArquivo)) {
            unlink($sNomeArquivo);
        }

        $oLayoutTXT = new \db_layouttxt(self::CODIGO_LAYOUT_TXT, $sNomeArquivo);

        $oLayoutTXT->enableOutputMemory(50000);

        $oHeader = $this->gerarHeader();
        $this->oHeader->setLote(1);
        $oHeaderLote = $this->gerarHeaderLote();

        $oLayoutTXT->addLine($oHeader, 1);
        $oLayoutTXT->addLine($oHeaderLote, 2);

        $this->iSequencialRegistro = 0;
        $oConvenio = $this->oHeader->getConvenio();

        $iRegistro = 0;

        foreach ($this->oRegistrosCollection as $oRegistro) {
            $iRegistro++;

            if ($this->callBackFunc) {
                call_user_func($this->callBackFunc, $iRegistro);
            }

            // Se o banco for o responsavel pela geracao do nosso numero
            if ($oConvenio->responsavel_nosso_numero == 'f') {
                $oRegistro->setNossoNumero('0');
            }

            if ($this->iSequencialRegistro >= 99990) {
                $oLayoutTXT->addLine($this->gerarTrailerLote(), 4);

                $this->iSequencialRegistro = 0;
                $this->oHeader->setLote($this->oHeader->getLote() + 1);

                $oLayoutTXT->addLine($this->gerarHeaderLote(), 2);
            }

            $oRegistro->setSequencialRegistro($this->iSequencialRegistro);

            $aSegmentos = $this->getSegmentosRecibo($oRegistro);

            if (empty($aSegmentos)) {
                $this->aRecibosInvalidos[] = $oRegistro->getNumeroDocumento();
            }

            foreach ($aSegmentos as &$oSegmento) {
                $oLayoutTXT->addLine($oSegmento, 3, $oSegmento->segmento);
                $this->iSequencialRegistro++;
            }

            $this->aRecibosGerados[] = $oRegistro->getNumeroDocumento();
        }

        $oTrailerLote = $this->gerarTrailerLote();
        $oTrailer     = $this->gerarTrailer();

        $oLayoutTXT->addLine($oTrailerLote, 4);

        if (!$lQuebraLinha) {
            $oLayoutTXT->desabilitarQuebraAutomatica();
        }
        $oLayoutTXT->addLine($oTrailer, 5);

        $oLayoutTXT->fechaArquivo();

        return new \File($sNomeArquivo);
    }

    /**
     * Faz a chamada dos segmentos a serem gerados
     *
     * @param Registro $oRegistro
     * @return array
     */
    protected function getSegmentosRecibo(Registro $oRegistro)
    {
        $aRegistros = array();

        $oSegmentoP = $this->gerarSegmentoP($oRegistro);

        if (!empty($oSegmentoP)) {
            $aRegistros[] = $oSegmentoP;
        }

        $oSegmentoQ = $this->gerarSegmentoQ($oRegistro);

        if (!empty($oSegmentoQ)) {
            $aRegistros[] = $oSegmentoQ;
        }

        return $aRegistros;
    }

    /**
     * Função que cria o Header para a geração do arquivo
     * @return \stdClass
     */
    protected function gerarHeader()
    {
        $oHeader   = new \stdClass;
        $oDateTime = new \DateTime();

        $oHeader->codigo_banco         = null;
        $oHeader->lote                 = str_pad($this->oHeader->getLote(), 4, '0', STR_PAD_LEFT);
        $oHeader->tipo_registro        = '0';
        $oHeader->exclusivo_febraban_1 = str_pad('', 9, ' ');

        /**
         * campo do CNAB. Verificar se a classe de layout colocará os espaços em braco
         * Senão, este campo deve ser inserido aqui
         */
        $oHeader->tipo_inscricao        = 2;
        $oHeader->numero_inscricao      = $this->oHeader->getInstituicao()->getCNPJ();
        $oHeader->codigo_convenio_banco = str_pad('', 20, ' ');
        $oHeader->codigo_agencia        = str_pad($this->oHeader->getContaBancaria()->getNumeroAgencia(), 5, 0, STR_PAD_LEFT);
        $oHeader->dv_agencia            = $this->oHeader->getContaBancaria()->getDVAgencia();
        $oHeader->exclusivo_banco_1     = str_pad('', 14, ' ');
        $oHeader->nome_empresa          = \DBString::removerAcentuacao(str_pad($this->oHeader->getInstituicao()->getDescricao(), 30, ' ', STR_PAD_RIGHT));
        $oHeader->nome_banco            = \DBString::removerAcentuacao(str_pad($this->oHeader->getContaBancaria()->getDescricaoBanco(), 30, ' ', STR_PAD_RIGHT));
        $oHeader->exclusivo_febraban_2  = str_pad('', 10, ' ');

        /**
         * campo do CNAB. Verificar se a classe de layout colocará os espaços em braco
         * Senão, este campo deve ser inserido aqui
         */
        $oHeader->codigo_remessa        = '1';
        $oHeader->data_geracao          = $oDateTime->format('dmY');
        $oHeader->hora_geracao          = $oDateTime->format('His');
        $oHeader->numero_sequencial     = str_pad($this->oHeader->getSequencial(), 6, '0', STR_PAD_LEFT);
        $oHeader->versao_layout         = self::VERSAO_LAYOUT_ARQUIVO;
        $oHeader->densidade_arquivo     = '00000';
        $oHeader->uso_reservado_banco   = str_pad('', 20, ' ');
        $oHeader->uso_reservado_empresa = str_pad('', 20, ' ');
        $oHeader->exclusivo_febraban_3  = str_pad('', 29, ' ');

        /**
         * Sistema deve listar no arquivo de remessa o nome do cendente que constar como nome no cadastro de convênio,
         * quando for convênio BDL
         */
        if ($this->oHeader->getConvenio()->tipo_convenio == 1) {
            $oHeader->nome_empresa = $this->oHeader->getConvenio()->nome;
        }

        /**
         * campo do CNAB. Verificar se a classe de layout colocará os espaços em braco
         * Senão, este campo deve ser inserido aqui
         */

        return $oHeader;
    }

    /**
     * Função que cria o Header do Lote com os dados genéricos
     *
     * @return \stdClass
     */
    protected function gerarHeaderLote()
    {
        $oHeaderLote = new \stdClass;
        $oDateTime   = new \DateTime();

        $oHeaderLote->codigo_banco          = null;
        $oHeaderLote->lote                  = str_pad($this->oHeader->getLote(), 4, '0', STR_PAD_LEFT);
        $oHeaderLote->tipo_registro         = '1';
        $oHeaderLote->tipo_operacao         = 'R';
        $oHeaderLote->tipo_servico          = '01';
        $oHeaderLote->exclusivo_febraban_1  = '  ';
        $oHeaderLote->versao_layout         = '000';
        $oHeaderLote->exclusivo_febraban_2  = ' ';
        $oHeaderLote->tipo_inscricao        = '2';
        $oHeaderLote->numero_inscricao      = str_pad($this->oHeader->getInstituicao()->getCNPJ(), 15, '0', STR_PAD_LEFT);
        $oHeaderLote->codigo_convenio_banco = str_pad('', 20, ' ');
        $oHeaderLote->codigo_agencia        = str_pad($this->oHeader->getContaBancaria()->getNumeroAgencia(), 5, '0', STR_PAD_LEFT);
        $oHeaderLote->dv_agencia            = $this->oHeader->getContaBancaria()->getDVAgencia();
        $oHeaderLote->exclusivo_banco_1     = str_pad('', 14, ' ');
        $oHeaderLote->nome_empresa          = \DBString::removerAcentuacao(str_pad($this->oHeader->getInstituicao()->getDescricao(), 30, ' ', STR_PAD_RIGHT));
        $oHeaderLote->mensagem1             = str_pad('', 40, ' ');
        $oHeaderLote->mensagem2             = str_pad('', 40, ' ');
        $oHeaderLote->numero_remessa        = str_pad($this->oHeader->getSequencial(), 8, '0', STR_PAD_LEFT);
        $oHeaderLote->data_geracao          = $oDateTime->format('dmY');
        $oHeaderLote->data_credito          = str_pad('', 8, 0);
        $oHeaderLote->exclusivo_febraban_3  = str_pad('', 33, ' ');

        /**
         * Sistema deve listar no arquivo de remessa o nome do cendente que constar como nome no cadastro de convênio,
         * quando for convênio BDL
         */
        if ($this->oHeader->getConvenio()->tipo_convenio == 1) {
            $oHeaderLote->nome_empresa = $this->oHeader->getConvenio()->nome;
        }

        return $oHeaderLote;
    }

    /**
     * Função que cria o Segmento P com os dados genéricos
     *
     * @param  Registro $oRegistro [description]
     * @return [type]              [description]
     */
    protected function gerarSegmentoP(Registro $oRegistro)
    {
        $oSegmentoP = new \stdClass;

        $oSegmentoP->codigo_banco             = "000";
        $oSegmentoP->lote                     = str_pad($this->oHeader->getLote(), 4, '0', STR_PAD_LEFT);
        $oSegmentoP->tipo_registro            = "3";
        $oSegmentoP->sequencial_registro      = str_pad($oRegistro->getSequencialRegistro() + 1, 5, '0', STR_PAD_LEFT);
        $oSegmentoP->segmento                 = "P";

        $oSegmentoP->exclusivo_febraban_1     = " ";
        $oSegmentoP->codigo_movimento         = "01";
        $oSegmentoP->codigo_agencia           = str_pad($this->oHeader->getContaBancaria()->getNumeroAgencia(), 5, '0', STR_PAD_LEFT);
        $oSegmentoP->dv_agencia               = $this->oHeader->getContaBancaria()->getDVAgencia();
        $oSegmentoP->exclusivo_banco_1        = str_pad('', 14, ' ');
        $oSegmentoP->exclusivo_banco_2        = str_pad('', 20, ' ');
        $oSegmentoP->codigo_carteira          = "1";
        $oSegmentoP->forma_cadastramento      = "1";
        $oSegmentoP->tipo_documento           = "2";
        $oSegmentoP->emissao_bloqueto         = "2";
        $oSegmentoP->distribuicao_bloqueto    = "0";
        $oSegmentoP->documento_cobranca       = str_pad($oRegistro->getNumeroDocumento(), 15, '0', STR_PAD_LEFT);
        $oSegmentoP->vencimento_titulo        = $oRegistro->getDataVencimento()->getDate("dmY");
        $oSegmentoP->valor_titulo             = str_pad(number_format((string)$oRegistro->getValor(), 2, '', ''), 15, '0', STR_PAD_LEFT);
        $oSegmentoP->codigo_agencia_cobradora = "00000";
        $oSegmentoP->dv_agencia_cobradora     = " ";
        $oSegmentoP->especie_titulo           = "17";
        $oSegmentoP->aceite_titulo            = "N";
        $oSegmentoP->data_emissao_titulo      = $oRegistro->getDataEmissao()->getDate("dmY");
        $oSegmentoP->codigo_juros             = (string) $oRegistro->getCodigoJuros();
        $oSegmentoP->data_juros               = (is_null($oRegistro->getDataJuros()) ? str_pad('', 8, '0') : $oRegistro->getDataJuros()->getDate("dmY"));
        $oSegmentoP->taxa_juros               = str_pad($oRegistro->getTaxaJuros(), 15, '0');
        $oSegmentoP->codigo_desconto          = $oRegistro->getCodigoDesconto();
        $oSegmentoP->data_desconto            = (is_null($oRegistro->getDataDesconto()) ? str_pad('', 8, '0') : $oRegistro->getDataDesconto()->getDate("dmY"));
        $oSegmentoP->valor_desconto           = str_pad($oRegistro->getValorDesconto(), 15, '0', STR_PAD_LEFT);
        $oSegmentoP->valor_iof                = str_pad('', 15, '0');
        $oSegmentoP->valor_abatimento         = str_pad('', 15, '0');
        $oSegmentoP->uso_empresa              = str_pad($oRegistro->getNumeroDocumento(), 25, ' ', STR_PAD_RIGHT); //Numnov
        $oSegmentoP->codigo_protesto          = "3";
        $oSegmentoP->prazo_protesto           = "";
        $oSegmentoP->codigo_baixa_devolucao   = 1;
        $oSegmentoP->prazo_baixa_devolucao    = str_pad('29', 3, '0', STR_PAD_LEFT);
        $oSegmentoP->codigo_moeda             = str_pad($oRegistro->getCodigoMoeda(), 2, '0', STR_PAD_LEFT);
        $oSegmentoP->exclusivo_banco_3        = "";
        $oSegmentoP->exclusivo_febraban_2     = " ";

        return $oSegmentoP;
    }

    /**
     * Função que cria o Segmento Q com os dados genéricos
     *
     * @param  Registro $oRegistro
     * @return \stdClass
     */
    protected function gerarSegmentoQ(Registro $oRegistro)
    {
        $oCgm                 = $oRegistro->getCgm();
        $sTipoInscricaoSacado = '0';

        if ($oCgm instanceof \CgmFisico) {
            $sTipoInscricaoSacado = '1';
            $sCnpjCpf             = (string) $oCgm->getCpf();
        } elseif ($oCgm instanceof \CgmJuridico) {
            $sTipoInscricaoSacado = '2';
            $sCnpjCpf             = (string) $oCgm->getCnpj();
        }

        $oSegmentoQ = new \stdClass;

        $oSegmentoQ->lote                    = $this->oHeader->getLote();
        $oSegmentoQ->tipo_registro           = '3';
        $oSegmentoQ->sequencial_registro     = $oRegistro->getSequencialRegistro() + 2;
        $oSegmentoQ->segmento                = 'Q';
        $oSegmentoQ->exclusivo_febraban_1    = '';
        $oSegmentoQ->codigo_movimento        = '01';
        $oSegmentoQ->tipo_inscricao_sacado   = $sTipoInscricaoSacado;
        $oSegmentoQ->numero_inscricao_sacado = $sCnpjCpf;
        $oSegmentoQ->nome_sacado             = \DBString::removerAcentuacao($oCgm->getNome());
        $oSegmentoQ->bairro_sacado           = \DBString::removerAcentuacao($oCgm->getBairro());
        $oSegmentoQ->endereco_sacado         = \DBString::removerAcentuacao($oCgm->getLogradouro());
        $oSegmentoQ->cep_sacado              = substr($oCgm->getCep(), 0, 5);
        $oSegmentoQ->sufixo_cep_sacado       = substr($oCgm->getCep(), 5, 3);
        $oSegmentoQ->cidade_sacado           = \DBString::removerAcentuacao($oCgm->getMunicipio());
        $oSegmentoQ->uf_sacado               = $oCgm->getUf();

        /* Instituicao */
        $oSegmentoQ->tipo_inscricao_sacador   = '0';
        $oSegmentoQ->numero_inscricao_sacador = str_pad('', 15, '0');
        $oSegmentoQ->nome_sacador             = '';

        $oSegmentoQ->codigo_banco_correspondente = str_pad('', 3, ' '); // Em branco
        $oSegmentoQ->nosso_numero                = ''; // Em branco
        $oSegmentoQ->exclusivo_febraban_2        = ''; // Em branco

        return $oSegmentoQ;
    }

    /**
     * Função que cria o trailer do lote com os dados genéricos
     * @return \stdClass
     */
    protected function gerarTrailerLote()
    {
        $oTrailerLote                       = new \stdClass;
        $oTrailerLote->codigo_banco         = null;
        $oTrailerLote->lote                 = str_pad($this->oHeader->getLote(), 4, '0', STR_PAD_LEFT);
        $oTrailerLote->tipo_registro        = '5';
        $oTrailerLote->exclusivo_febraban_1 = str_pad('', 9, ' ');
        $oTrailerLote->quantidade_registros = str_pad(($this->iSequencialRegistro + 2), 6, '0', STR_PAD_LEFT);
        $oTrailerLote->exclusivo_febraban_2 = str_pad('', 217, ' ');

        return $oTrailerLote;
    }

    /**
     * Função que retorna um ojbeto com os dados para o trailer do arquivo
     * @return \stdClass
     */
    protected function gerarTrailer()
    {
        $oTrailer                       = new \stdClass;
        $oTrailer->codigo_banco         = null;
        $oTrailer->lote                 = '9999';
        $oTrailer->tipo_registro        = '9';
        $oTrailer->exclusivo_febraban_1 = str_pad('', 9, ' ');
        $oTrailer->quantidade_lotes     = str_pad($this->oHeader->getLote(), 6, '0', STR_PAD_LEFT);
        $oTrailer->quantidade_registros = str_pad(((count($this->aRecibosGerados) * 2) + 2 + ($this->oHeader->getLote() * 2)), 6, '0', STR_PAD_LEFT);
        $oTrailer->exclusivo_febraban_2 = str_pad('', 6, ' ');
        $oTrailer->exclusivo_febraban_3 = str_pad('', 205, ' ');

        return $oTrailer;
    }
}
