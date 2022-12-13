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

use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\EventoLic as Regra;

class EventoLicLicitaCon extends ArquivoLicitaCon
{
    const NOME_ARQUIVO = "EVENTO_LIC";

    /**
     * @type stdClass[]
     */
    private $aDadosLicitacao = array();

    public function __construct(CabecalhoLicitaCon $oCabecalho)
    {
        parent::__construct($oCabecalho, new Regra($oCabecalho->getDataGeracao()));
        $this->sNomeArquivo = self::NOME_ARQUIVO;
        $this->iCodigoLayout = $this->oRegra->getCodigoLayout();
    }


    /**
     * @throws DBException
     */
    private function processarEventos()
    {
        $aTipoResultado = array(
            EventoLicitacao::RESULTADO_INDEFERIDO => 'I',
            EventoLicitacao::RESULTADO_DEFERIDO => 'D',
            EventoLicitacao::RESULTADO_PARCIALMENTE_DEFERIDO => 'P',
        );

        $aCampos = array(
            'l20_codigo as codigo_licitacao',
            'l20_numero as numero_licitacao',
            'l46_fase as fase',
            'l20_anousu as ano',
            'l44_sigla as modalidade',
            'l46_sequencial as sequencia_evento',
            'l46_liclicitatipoevento as codigo_evento',
            'l46_dataevento as data_evento',
            'l46_tipopublicacao as tipo_publicacao',
            'l46_descricaopublicacao as descricao_publicacao',
            'z01_cgccpf as documento_autor',
            'z01_numcgm',
            'l46_datajulgamento as data_julgamento',
            'l46_tiporesultado as tipo_resultado',
        );

        $aWhere = LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(),
            $this->oCabecalho->getDataGeracao());
        $aWhere[] = "l46_liclicitatipoevento <> " . LicitaConTipoEvento::EVENTO_NAO_INFORMADO;

        $sWhere = implode(' and ', $aWhere) . " order by 1, 4";
        $oDaoLicitacao = new cl_liclicitaevento();
        $sSqlBuscaEvento = $oDaoLicitacao->sql_query_eventos(implode(',', $aCampos), $sWhere);
        $rsBuscaEvento = db_query($sSqlBuscaEvento);
        if (!$rsBuscaEvento) {
            throw new DBException("Ocorreu um erro ao buscar as informações do banco de dados.");
        }

        for ($iRow = 0; $iRow < pg_num_rows($rsBuscaEvento); $iRow++) {
            $oStdDados = db_utils::fieldsMemory($rsBuscaEvento, $iRow);

            $sDataEvento = '';
            if (!empty($oStdDados->data_evento)) {
                $oDataEvento = new DBDate($oStdDados->data_evento);
                $sDataEvento = $oDataEvento->getDate(DBDate::DATA_PTBR);
            }

            $sDataJulgamento = '';
            if (!empty($oStdDados->data_julgamento)) {
                $oDataJulgamento = new DBDate($oStdDados->data_julgamento);
                $sDataJulgamento = $oDataJulgamento->getDate(DBDate::DATA_PTBR);
            }

            $sCodigoEvento = '';
            if (!empty($oStdDados->codigo_evento)) {
                $sCodigoEvento = LicitaConTipoEvento::$aSiglaEvento[$oStdDados->codigo_evento];
            }

            $oStdInformacoes = new stdClass();
            $oStdInformacoes->NR_LICITACAO = $oStdDados->numero_licitacao;
            $oStdInformacoes->ANO_LICITACAO = $oStdDados->ano;
            $oStdInformacoes->CD_TIPO_MODALIDADE = $oStdDados->modalidade;
            $oStdInformacoes->SQ_EVENTO = $oStdDados->sequencia_evento;
            $oStdInformacoes->CD_TIPO_FASE = LicitacaoLicitaCon::getSiglaFase($oStdDados->fase);
            $oStdInformacoes->CD_TIPO_EVENTO = $sCodigoEvento;
            $oStdInformacoes->DT_EVENTO = $sDataEvento;
            $oStdInformacoes->TP_VEICULO_PUBLICACAO = LicitaConTipoPublicacao::getSigla($oStdDados->tipo_publicacao);
            $oStdInformacoes->DS_PUBLICACAO = $oStdDados->descricao_publicacao;
            $oStdInformacoes->TP_DOCUMENTO_AUTOR = LicitanteLicitaCon::getTipoDocumentoPorCGM($oStdDados->z01_numcgm);
            $oStdInformacoes->NR_DOCUMENTO_AUTOR = LicitanteLicitaCon::getDocumentoPorCGM($oStdDados->z01_numcgm);
            $oStdInformacoes->DT_JULGAMENTO = $sDataJulgamento;
            $oStdInformacoes->TP_RESULTADO = isset($aTipoResultado[$oStdDados->tipo_resultado]) ? $aTipoResultado[$oStdDados->tipo_resultado] : '';
            $oStdInformacoes->NR_LOTE = '';
            $oStdInformacoes->NR_ITEM = '';
            $this->aDadosLicitacao[] = $oStdInformacoes;
            unset($oStdDados, $oStdInformacoes);
        }
    }

    /**
     * @return array
     */
    public function getDados()
    {
        $this->processarEventos();
        return $this->aDadosLicitacao;
    }
}