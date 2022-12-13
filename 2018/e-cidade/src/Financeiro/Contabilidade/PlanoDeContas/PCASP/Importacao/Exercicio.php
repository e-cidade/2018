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

class Exercicio {

	/**
	 * @var int
	 */
	private $iAno;

	/**
	 * @var boolean
	 */
	private $lImportado;

	/**
	 * @return int
	 */
	public function getAno() {
		return $this->iAno;
	}

	/**
	 * @param int $iAno
	 */
	public function setAno($iAno) {
		$this->iAno = $iAno;
	}

	/**
	 * @return boolean
	 */
	public function isImportado() {
		return $this->lImportado;
	}

	/**
	 * @param boolean $lImportado
	 */
	public function setImportado($lImportado) {
		$this->lImportado = $lImportado;
	}

	public static function exercicioImportado($iExercicio){

		$oDaoImportacao = new \cl_importacaoplanoconta();
		$sSqlImportacao = $oDaoImportacao->sql_query(null, "1", null, "c94_exercicio = {$iExercicio}");
		$rsImportacao   = db_query($sSqlImportacao);

		if (!$rsImportacao) {
			throw new \DBException("Houve uma falha ao verificar a importação do exercício {$oStd->ano}.");
		}

		if(pg_num_rows($rsImportacao) > 0){
			return true;
		} 
		return false;
	}

	public static function getExercicios() {

		$oDaoModelo 		= new \cl_modeloplanoconta();
		$sSqlExercicios = $oDaoModelo->sql_query_file(null, "distinct c94_exercicio as ano", "c94_exercicio");

		$rsExercicio 		= db_query($sSqlExercicios);

		if (!$rsExercicio) {
			throw new \DBException("Houve uma falha ao buscar os exercícios disponíveis.");
		}

		if (pg_num_rows($rsExercicio) == 0) {
			throw new \DBException("Não foram encontrados exercícios para importação.");
		}

		$aExercicios = array();
		
		for ($i=0; $i < pg_numrows($rsExercicio); $i++) { 

			$oStd = \db_utils::fieldsMemory($rsExercicio, $i);

			$oExercicio = new Exercicio();
			$oExercicio->setAno($oStd->ano);
	
			$oDaoImportacao = new \cl_importacaoplanoconta();
			$sSqlImportacao = $oDaoImportacao->sql_query(null, "1", null, "c94_exercicio = {$oStd->ano}");
			$rsImportacao   = db_query($sSqlImportacao);

			if (!$rsImportacao) {
				throw new \DBException("Houve uma falha ao verificar a importação do exercício {$oStd->ano}.");
			}

			$oExercicio->setImportado(pg_numrows($rsExercicio) == 0);
			$aExercicios[$i] = $oExercicio;
		}

		return $aExercicios;
	}
}