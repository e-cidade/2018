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

use \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\DotacaoLic as Regra;

class DotacaoLicLicitaCon extends ArquivoLicitaCon {

	/**
	 * Nome do arquivo.
	 */
  const NOME_ARQUIVO  = "DOTACAO_LIC";

	/**
	 * DotacaoLicLicitaCon constructor.
	 *
	 * @param CabecalhoLicitaCon $oCabecalho
	 */
  public function __construct(CabecalhoLicitaCon $oCabecalho) {

    parent::__construct($oCabecalho, new Regra($oCabecalho->getDataGeracao()));
    $this->sNomeArquivo  = self::NOME_ARQUIVO;
    $this->iCodigoLayout = $this->oRegra->getCodigoLayout();
  }

  /**
   * @return array
   * @throws DBException
   */
  public function getDados() {

    $aDotacoes = array();

    $sCampos  = " distinct l20_codigo, o58_projativ, o58_codigo, o56_elemento ";
    $aWhere   = LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(), $this->oCabecalho->getDataGeracao());
    $aWhere[] = "l44_sigla not in ('LEI', 'MAI')";

    $oDaoLicitacao = new cl_liclicita();
    $sSqlDotacoes = $oDaoLicitacao->sql_query_licitacon_dotacao_lic($sCampos, implode(' and ', $aWhere));

    $rsDotacoes = db_query($sSqlDotacoes);
    if ($rsDotacoes === false) {
      throw new DBException("Houve um erro ao buscar as dotações da Licitação.");
    }

    $iDotacoes = pg_num_rows($rsDotacoes);
    for ($i = 0; $i < $iDotacoes; $i++) {

      $oDotacao   = db_utils::fieldsMemory($rsDotacoes, $i);
      $oLicitacao = LicitacaoRepository::getByCodigo($oDotacao->l20_codigo);
			if (!$this->oRegra->mostrarDotacao($oLicitacao)) {
				continue;
			}

			$oDados = new stdClass();
      $oDados->NR_LICITACAO            = $oLicitacao->getEdital();
      $oDados->ANO_LICITACAO           = $oLicitacao->getAno();
      $oDados->CD_TIPO_MODALIDADE      = $oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
      $oDados->CD_PROJETO_ATIVIDADE    = $oDotacao->o58_projativ;
      $oDados->CD_RECURSO_ORCAMENTARIO = $oDotacao->o58_codigo;
      $oDados->CD_NATUREZA_DESPESA     = substr($oDotacao->o56_elemento, 1, 6);

      $aDotacoes[] = $oDados;
    }
    return $aDotacoes;
  }
}
