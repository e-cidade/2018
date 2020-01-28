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

class MemComissaoLicitaCon extends ArquivoLicitaCon {

  const CODIGO_LAYOUT = 234;
  const NOME_ARQUIVO  = "MEMCOMISSAO";

  public function __construct(CabecalhoLicitaCon $oCabecalho) {

    parent::__construct($oCabecalho);
    $this->sNomeArquivo  = self::NOME_ARQUIVO;
    $this->iCodigoLayout = self::CODIGO_LAYOUT;
  }

  /**
   * @return array
   */
  public function getDados() {

    $aTiposComissao = array(
        1 => 'P',
        2 => 'E',
        3 => 'G',
        4 => 'S',
      );

    $aTipoAtribuicao = array(
        'A' => 'A',
        'D' => 'D',
        2 => 'G',
        'L' => 'L',
        'M' => 'M',
        '3' => 'M',
        'P' => 'P',
        'S' => 'S'
      );

    $this->aAnexos = array();
    $aMembros = array();

    $oDaoLiccomissaoCgm = new cl_liccomissaocgm();
    $oDaoLiclicita      = new cl_liclicita();

    $aCampos = array(
        "l30_data",
        "l30_portaria",
        "l30_tipo",
        "z01_numcgm",
        "l31_tipo",
        "l15_cadattdinamicovalorgrupo"
      );

    $aWhereLicitacao   = LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(), $this->oCabecalho->getDataGeracao());
    $aWhereLicitacao[] = "l20_liccomissao = l30_codigo";
    $aWhereLicitacao[] = "l44_sigla not in ('PRD','PRI')";

    $sSqlLiclicita      = $oDaoLiclicita->sql_query_licitacao_encerramento("1", implode(" and ", $aWhereLicitacao));
    $sSqlLiccomissaoCgm = $oDaoLiccomissaoCgm->sql_query_atributos(implode(", ", $aCampos), null, " exists({$sSqlLiclicita}) ");
    $rsMembros          = $oDaoLiccomissaoCgm->sql_record($sSqlLiccomissaoCgm);

    if ($rsMembros && $oDaoLiccomissaoCgm->numrows > 0) {

      $aMembros = db_utils::makeCollectionFromRecord($rsMembros, function($oRegistro) use($aTiposComissao, $aTipoAtribuicao) {

        $oDataDesignacao = new DBDate($oRegistro->l30_data);

        $oMembro = new stdClass();
        $oMembro->NR_COMISSAO = preg_replace('/[^0-9]/', '', $oRegistro->l30_portaria);
        $oMembro->ANO_COMISSAO = $oDataDesignacao->getAno();
        $oMembro->TP_COMISSAO = isset($aTiposComissao[$oRegistro->l30_tipo]) ? $aTiposComissao[$oRegistro->l30_tipo] : null;
        $oMembro->TP_DOCUMENTO_MEMBRO = LicitanteLicitaCon::getTipoDocumentoPorCGM($oRegistro->z01_numcgm);
        $oMembro->NR_DOCUMENTO_MEMBRO = LicitanteLicitaCon::getDocumentoPorCGM($oRegistro->z01_numcgm);
        $oMembro->TP_ATRIBUICAO = isset($aTipoAtribuicao[$oRegistro->l31_tipo]) ? $aTipoAtribuicao[$oRegistro->l31_tipo] : null;

        $oMembro->DS_CARGO = null;
        $oMembro->TP_CARGO = null;
        $oMembro->DT_DESIGNACAO = null;
        $oMembro->NR_ATO_DESIGNACAO = null;
        $oMembro->ANO_ATO_DESIGNACAO = null;

        $oValoresAtributos = DBAttDinamicoValor::getValores($oRegistro->l15_cadattdinamicovalorgrupo);

        foreach ($oValoresAtributos as $oValor) {

          switch ($oValor->getAtributo()->getNome()) {

            case "cargo":
              $oMembro->DS_CARGO = $oValor->getValor();
              break;

            case "tipocargo":
              $oMembro->TP_CARGO = $oValor->getValor();
              break;

            case "datadesignacao":

              if ($oValor->getValor()) {
                $oData = new DBDate($oValor->getValor());
                $oMembro->DT_DESIGNACAO = $oData->getDate(DBDate::DATA_PTBR);
              }

              break;

            case "numeroatodesignacao":
              $oMembro->NR_ATO_DESIGNACAO = $oValor->getValor();
              break;

            case "anoatodesignacao":
              $oMembro->ANO_ATO_DESIGNACAO = $oValor->getValor();
              break;
          }
        }

        $oMembro->DT_DESTITUICAO = null;
        $oMembro->NR_ATO_DESTITUICAO = null;
        $oMembro->ANO_ATO_DESTITUICAO = null;
        $oMembro->NOME_ARQUIVO_DOCUMENTO = null;

        return $oMembro;
      });
    }

    return $aMembros;
  }
}