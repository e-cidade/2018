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

use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\ResponsavelCon as RegraContrato;

/**
 * Class ResponsavelConLicitaCon
 */
class ResponsavelConLicitaCon extends ArquivoLicitaCon
{
    /**
     * @var string
     */
    const NOME_ARQUIVO = 'RESPONSAVEL_CON';

    /**
     * @var array
     */
    public static $aTiposResponsavel = array(
        AcordoComissaoMembro::TIPO_GESTOR => 'G',
        AcordoComissaoMembro::TIPO_SECUNDARIO => 'G',
        AcordoComissaoMembro::TIPO_SUPLENTE => 'S',
        AcordoComissaoMembro::TIPO_FISCAL => 'F',
    );

    /**
     * ResponsavelConLicitaCon constructor.
     * @param CabecalhoLicitaCon $oCabecalho
     */
    public function __construct(CabecalhoLicitaCon $oCabecalho)
    {
        parent::__construct($oCabecalho, new RegraContrato($oCabecalho->getDataGeracao()));
        $this->sNomeArquivo = self::NOME_ARQUIVO;
        $this->iCodigoLayout = $this->oRegra->getCodigoLayout();
    }

    /**
     * @return bool|resource
     * @throws DBException
     */
    private function getResponsavel()
    {
        $oDaoAcordoComissaoMembros = new cl_acordocomissaomembro;
        $sCampos = ' DISTINCT ' . implode(',', array(
                'ac16_numero',
                'ac16_anousu',
                'ac16_tipoinstrumento',
                'z01_cgccpf',
                'ac07_tipomembro',
                'ac07_datainicio',
                'ac07_datatermino',
                'ac16_sequencial',
                'ac07_numeroatodesignacao',
                'ac07_anoatodesignacao',
                'ac07_nomearquivo'
            ));
        $sDataAtual = $this->oCabecalho->getDataGeracao()->getDate();
        $sWhere = implode(' AND ', array(
            "(ac58_sequencial IS NULL OR ac58_data >= '{$sDataAtual}')",
            "ac16_instit = {$this->oCabecalho->getInstituicao()->getCodigo()}",
            'EXISTS(SELECT 1 FROM acordoposicao INNER JOIN acordoitem ON ac20_acordoposicao = ac26_sequencial AND ac26_acordo = ac16_sequencial)'
        ));
        $sSqlResponsaveis = $oDaoAcordoComissaoMembros->sql_query_acordo($sCampos, $sWhere,
            'ac16_numero, ac07_datatermino ASC');
        $rsResponsaveis = db_query($sSqlResponsaveis);

        if (!$rsResponsaveis) {
            throw new DBException('Não foi possível obter as informações da comissão do acordo');
        }

        return $rsResponsaveis;
    }

    /**
     * @return array
     */
    private function processarResponsaveis()
    {
        $rsResponsaveis = $this->getResponsavel();
        $iTotalResponsaveis = pg_num_rows($rsResponsaveis);
        $aTiposInstrumento = LicitaConTipoInstrumentoAcordo::getSiglas();
        $aResponsaveis = array();

        for ($iIndice = 0; $iIndice < $iTotalResponsaveis; $iIndice++) {
            $oResponsavel = db_utils::fieldsMemory($rsResponsaveis, $iIndice);
            $oRegraContrato = new RegraContrato($this->oCabecalho->getDataGeracao());
            $oLicitacao = $oRegraContrato->getDadosDaLicitacaoDoContrato($oResponsavel->ac16_sequencial);
            $sCaminhoArquivo = "";

            if($oResponsavel->ac07_nomearquivo != null) {
                $sCaminhoArquivo = $this->sNomeArquivo . "\\" . preg_replace("/((?![\w\\.!@#$%*()_+= ,<>?\/^~-]).)/", "", $oResponsavel->ac07_nomearquivo);
            }
            $oStdResponsavel = new stdClass;
            $oStdResponsavel->NR_LICITACAO = $oLicitacao->numero;
            $oStdResponsavel->ANO_LICITACAO = $oLicitacao->ano;
            $oStdResponsavel->CD_TIPO_MODALIDADE = $oLicitacao->tipo;
            $oStdResponsavel->NR_CONTRATO = $oResponsavel->ac16_numero;
            $oStdResponsavel->ANO_CONTRATO = $oResponsavel->ac16_anousu;
            $oStdResponsavel->TP_INSTRUMENTO = $aTiposInstrumento[$oResponsavel->ac16_tipoinstrumento];
            $oStdResponsavel->TP_DOCUMENTO_RESPONSAVEL = 'F';
            $oStdResponsavel->NR_DOCUMENTO_RESPONSAVEL = $oResponsavel->z01_cgccpf;
            $oStdResponsavel->TP_RESPONSAVEL = self::$aTiposResponsavel[$oResponsavel->ac07_tipomembro];
            $oStdResponsavel->DT_INICIO_RESP = null;
            $oStdResponsavel->DT_FINAL_RESP = null;
            $oStdResponsavel->NR_ATO_DESIGNACAO = $oResponsavel->ac07_numeroatodesignacao;
            $oStdResponsavel->ANO_ATO_DESIGNACAO = $oResponsavel->ac07_anoatodesignacao;
            $oStdResponsavel->NOME_ARQUIVO_DOCUMENTO = $sCaminhoArquivo;

            if ($oResponsavel->ac07_datainicio) {
                $oDataInicial = new DBDate($oResponsavel->ac07_datainicio);
                $oStdResponsavel->DT_INICIO_RESP = $oDataInicial->getDate(DBDate::DATA_PTBR);
            }

            if ($oResponsavel->ac07_datatermino) {
                $oDataFim = new DBDate($oResponsavel->ac07_datatermino);
                $oStdResponsavel->DT_FINAL_RESP = $oDataFim->getDate(DBDate::DATA_PTBR);
            }

            $aResponsaveis[] = $oStdResponsavel;
        }

        return $aResponsaveis;
    }

    /**
     * @return array
     */
    public function getDados()
    {
        return $this->processarResponsaveis();
    }
}
