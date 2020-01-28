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

/**
 * Model utilizado para descobrir o conjunto de regras e as regras do documento
 *
 * @author  matheus felini
 * @package contabilidade
 * @version $Revision: 1.9 $
 */
class DocumentoContabil {

	/**
	 * Codigo do tipo do documento contabil (conhistdoctipo)
	 * @var integer
	 */
	protected $iDocumentoContabil;

	/**
   * Objeto do tipo DocumentoContabilConjuntoRegra
   * @var DocumentoContabilConjuntoRegra
	 */
	protected $oConjuntoRegra;

	/**
   * Variaveis cadastradas para o documento contabil
   * @var array
	 */
	protected $aVariaveisDocumento = array();

	/**
	 * Array contendo as variáveis e suas informações
	 * @var array
	 */
	protected $aInformacoesVariaveis = array();

	/**
	 * Carrega o objeto e suas propriedades
	 * @param  integer $iDocumentoContabil
	 * @throws Exception
	 */
	public function __construct($iDocumentoContabil) {

		$oConjuntoRegra           = new DocumentoContabilConjuntoRegra($iDocumentoContabil);
		$this->iDocumentoContabil = $iDocumentoContabil;
		$this->oConjuntoRegra     = $oConjuntoRegra;
		$oDaoOperacaoVariaveis    = db_utils::getDao('conhistdocdocumentovariavel');
		$sWhereVariaveis          = "c93_conhistdoctipo = {$iDocumentoContabil}";
		$sSqlBuscaVariaveis       = $oDaoOperacaoVariaveis->sql_query_file(null, '*', null, $sWhereVariaveis);
		$rsBuscaVariaveis         = $oDaoOperacaoVariaveis->sql_record($sSqlBuscaVariaveis);
		$iLinhasVariaveis         = $oDaoOperacaoVariaveis->numrows;

		if ($iLinhasVariaveis > 0) {

			/*
			 * Percorremos as variáveis cadastradas para o documento adicionando elas ao array de variáveis setando
			 * elas como indice do array. Isso facilitara o parse que devemos fazer para montar o SQL
			 */
			for ($iRowVariavel = 0; $iRowVariavel < $iLinhasVariaveis; $iRowVariavel++) {

				$oDadoVariavel = db_utils::fieldsMemory($rsBuscaVariaveis, $iRowVariavel);
				$this->aVariaveisDocumento[$oDadoVariavel->c93_variavel] = "";
				$this->aInformacoesVariaveis[] = $oDadoVariavel;
			}
		}
		return true;
	}

	/**
	 * Retorna o código do documento que deve ser executado no lancamento contabil
	 * @return integer Codigo do Documento (conhistdoc)
	 */
	public function getCodigoDocumento() {
		return $this->getConjuntoRegra()->getCodigoDocumento($this->getVariaveis());
	}

	/**
	 * Seta valor para uma variável que será utilizada no SQL que busca a regra que deve
	 * ser executada pelo lancamento contabil
	 *
	 * @param string $sVariavelChave
	 * @param string $sVariavelValor
	 * @throws Exception
	 */
	public function setValorVariavel($sVariavelChave, $sVariavelValor) {

		if (!isset($this->aVariaveisDocumento[$sVariavelChave])) {
			throw new Exception("A variável informada não encontra-se cadastrada no sistema. Contate o suporte.");
		}
		$this->aVariaveisDocumento[$sVariavelChave] = $sVariavelValor;
	}

	/**
	 * Retorna um objeto do tipo DocumentoConjuntoRegra
	 * @return DocumentoContabilConjuntoRegra $oConjuntoRegra
	 */
	public function getConjuntoRegra() {
		return $this->oConjuntoRegra;
	}

	/**
	 * Retorna o array de variaveis do documento
	 * @return array $aVariaveisDocumento
	 */
	public function getVariaveis() {
		return $this->aVariaveisDocumento;
	}

	/**
	 * Retorna o array de variaveis e suas informações
	 * @return array $aVariavelInformacao
	 */
	public function getInformacoesVariaveis() {
		return $this->aInformacoesVariaveis;
	}

	/**
	 * Busca os documentos de acordo com o tipo do grupo
	 * @param integer $iCodigoGrupo
	 * @return array - array com os documentos de cada grupo para cada evento
	 */
	static public function getDocumentosPorGrupo ($iCodigoGrupo) {

	  $aDocumentos = array();
	  switch ($iCodigoGrupo) {

	    case 7: //servicos

	      $aDocumentos["empenho"]            = 1;
	      $aDocumentos["liquidacao"]         = 202;
	      $aDocumentos["estorno_liquidacao"] = 203;
	      break;

	    case 8: // despesa com material de almoxarifado

	      $aDocumentos["empenho"]            = 1;
	      $aDocumentos["liquidacao"]         = 204;
	      $aDocumentos["em_liquidacao"]      = 210;
	      $aDocumentos["estorno_liquidacao"] = 205;
	      break;

      case 9: //despesa com material permante

        $aDocumentos["empenho"]            = 1;
        $aDocumentos["em_liquidacao"]      = 208;
        $aDocumentos["liquidacao"]         = 206;
        $aDocumentos["estorno_liquidacao"] = 207;
        break;

      case 10: //divida passiva

        $aDocumentos["empenho"]            = 504;
        $aDocumentos["liquidacao"]         = 506;
        $aDocumentos["estorno_liquidacao"] = 507;
        break;

      case 12: //ferias

        $aDocumentos["empenho"]            = 304;
        $aDocumentos["liquidacao"]         = 306;
        $aDocumentos["estorno_liquidacao"] = 307;
        break;

      case 13: //'13 salario'

        $aDocumentos["empenho"]            = 308;
        $aDocumentos["liquidacao"]         = 310;
        $aDocumentos["estorno_liquidacao"] = 311;
        break;

      case 15: //Precatorios

        $aDocumentos["empenho"]            = 500;
        $aDocumentos["liquidacao"]         = 502;
        $aDocumentos["estorno_liquidacao"] = 503;
        break;
	  }
	  return $aDocumentos;
	}
}