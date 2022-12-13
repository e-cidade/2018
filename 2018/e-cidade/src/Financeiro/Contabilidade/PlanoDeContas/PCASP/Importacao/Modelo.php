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

namespace ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao;

use ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Importacao;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Conta;

/**
 * Class Modelo
 * Classe que representa um modelo para importação do plano de contas do PCASP.
 * @package ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP
 */
class Modelo {

	/**
	 * @var int
	 */
	private $iId;

	/**
	 * @var string
	 */
	private $sNome;

	/**
	 * @var int
	 */
	private $iExercicio;

	/**
	 * @var Conta[]
	 */
	private $aContas;

	/**
	 * Modelo constructor.
	 *
	 * @param int $iCodigo
	 *
	 * @throws \DBException
	 */
	public function __construct($iCodigo = null) {

		if (!empty($iCodigo)) {

			$oDao = new \cl_modeloplanoconta();
			$sSql = $oDao->sql_query_file($iCodigo);
			$rsResult = db_query($sSql);

			if (!$rsResult) {
				throw new \DBException("Houve uma falha ao buscar o modelo do PCASP com código {$iCodigo}.");
			}

			if (pg_num_rows($rsResult) != 1) {
				throw new \DBException("Modelo do PCASP com código {$iCodigo} não encontrado.");
			}

			$oStd = \db_utils::fieldsMemory($rsResult, 0);

			$this->setId($oStd->c94_sequencial);
			$this->setNome($oStd->c94_nome);
			$this->setExercicio($oStd->c94_exercicio);
		}
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->iId;
	}

	/**
	 * @param int $iId
	 */
	public function setId($iId) {
		$this->iId = $iId;
	}

	/**
	 * @return string
	 */
	public function getNome() {
		return $this->sNome;
	}

	/**
	 * @param string $sNome
	 */
	public function setNome($sNome) {
		$this->sNome = $sNome;
	}

	/**
	 * @return int
	 */
	public function getExercicio() {
		return $this->iExercicio;
	}

	/**
	 * @param int $iExercicio
	 */
	public function setExercicio($iExercicio) {
		$this->iExercicio = $iExercicio;
	}

	/**
	 * Verifica se o modelo já foi importado.
	 *
	 * @return bool
	 * @throws \DBException
	 * @throws \ParameterException
	 */
	public function isImportado() {

		$iModelo = $this->getId();
		if (empty($iModelo)) {
			throw new \ParameterException("Código do modelo não informado.");
		}

		$oDaoImportacao = new \cl_importacaoplanoconta();
		$sSql = $oDaoImportacao->sql_query_file(null, 1, null, "c96_modeloplanoconta = {$iModelo}");
		$rsResult = db_query($sSql);

		if (!$rsResult) {
			throw new \DBException("Houve um erro ao verificar a importação do modelo.");
		}

		return pg_num_rows($rsResult) >= 1;
	}

	/**
	 * Busca e retorna as contas para importação.
	 *
	 * @return Conta[]
	 * @throws \BusinessException
	 * @throws \DBException
	 * @throws \ParameterException
	 */
	public function getContas() {

		if (empty($this->aContas)) {

			$iModelo = $this->getId();
			if (empty($iModelo)) {
				throw new \ParameterException("Código do Modelo não foi informado.");
			}

			$this->aContas = array();

			$oDao = new \cl_planocontadetalhe();
			$sSql = $oDao->sql_query_file(null, "*", null, "c95_modeloplanoconta = {$iModelo}.");
			$rsResult = db_query($sSql);

			if (!$rsResult) {
				throw new \DBException("Houve um erro ao buscar as contas do modelo {$iModelo}.");
			}

			$iContas = pg_num_rows($rsResult);
			if ($iContas == 0) {
				throw new \BusinessException("Não foram encontradas contas para o Modelo {$iModelo}.");
			}

			for ($i = 0; $i < $iContas; $i++) {

				$oStdConta = \db_utils::fieldsMemory($rsResult, $i);

				$oConta = new Conta();
				$oConta->setExclusao($oStdConta->c95_excluir == 't');
				$oConta->setCodigo($oStdConta->c95_sequencial);
				$oConta->setCodigoModelo($oStdConta->c95_modeloplanoconta);
				$oConta->setEstrutural($oStdConta->c95_estrutural);
				$oConta->setTitulo($oStdConta->c95_titulo);
				$oConta->setFuncao($oStdConta->c95_funcao);
				$oConta->setNaturezaSaldo($oStdConta->c95_naturezasaldo);
				$oConta->setAnalitica($oStdConta->c95_analitica == 't');
				$oConta->setSistema($oStdConta->c95_sistema);
				$oConta->setIndicadorSuperavit($oStdConta->c95_indicadorsuperavit);

				$this->aContas[] = $oConta;
			}
		}
		return $this->aContas;
	}

	public static function getModelosByExercicio($iExercicio) {

		$aModelos = array();

		if (!$iExercicio) {
			throw new \DBException("Exercício não informado.");
		}
	
		$oDao = new \cl_modeloplanoconta();

		$sSqlModelo = $oDao->sql_query(null, "distinct c94_sequencial as id, c94_nome as nome", "c94_nome", "c94_exercicio = {$iExercicio}");
		$rsModelo   = db_query($sSqlModelo);
		
		if (!$rsModelo) {
			throw new \DBException("Houve uma falha ao buscar os modelos para o exercício {$iExercicio}.");
		}

		$iModelos = pg_num_rows($rsModelo);
		for ($i = 0; $i < $iModelos; $i++) {

			$oStd = \db_utils::fieldsMemory($rsModelo, $i);

			$oModelo = new Modelo();
			$oModelo->setId($oStd->id);
			$oModelo->setNome($oStd->nome);
			$oModelo->setExercicio($iExercicio);

			$aModelos[] = $oModelo;
		}

		return $aModelos;
	}
}