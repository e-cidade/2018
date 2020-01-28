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

require_once(modification("model/fiscal/Aidof.model.php"));

/**
 * Classe cadastro de AIDOF(Autorização de Impressão de DOcumento Fiscal) por webservice
 *
 * @package Fiscal
 * @author  Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */
class AidofWebService extends Aidof {

  /**
   * Define a empresa pela inscricao
   *
   * @param integer $iInscricao
   * @access public
   * @return void
   */
  public function setInscricao($iInscricao) {
    $this->setEmpresa( new Empresa($iInscricao) );
  }

  /**
   * Define grafica pelo codigo
   *
   * @param integer $iGrafica
   * @access public
   * @return void
   */
  public function setGrafica($iGrafica) {
    $oGrafica =  new Grafica($iGrafica);
    $oGrafica->validarGrafica();

    parent::setGrafica($oGrafica);
  }

  /**
   * Define nota
   *
   * @param mixed $iNota
   * @access public
   * @return void
   */
  public function setNota($iNota) {
    parent::setNota( new NotaFiscalISSQN($iNota) );
  }

  /**
   * Define a data limite solicitada pelo contribuinte
   *
   * @param string $sData
   * @access public
   * @return void
   */
  public function setDataLimiteSolicitada($sData) {
    parent::setDataLimiteSolicitada(new DBDate($sData));
  }

  /**
   * Define a data limite liberada pelo fiscal
   *
   * @param string $sData
   * @access public
   * @return void
   */
  public function setDataLimiteLiberada($sData) {
    parent::setDataLimiteLiberada(new DBDate($sData));
  }

  /**
   * Define data de liberacao da grafica
   *
   * @param string | DBDate $dataLiberacaoGrafica
   * @access public
   * @return void
   */
  public function setDataLiberacaoGrafica($dataLiberacaoGrafica) {
    parent::setDataLiberacaoGrafica( new DBDate($dataLiberacaoGrafica) );
  }

  /**
   * Define a data de recebimento do talao de notas do contribuiente
   *
   * @param string | DBDate $dataLiberacaoContribuinte
   * @access public
   * @return void
   */
  public function setDataRecebimentoContribuinte($dataLiberacaoContribuinte) {
    parent::setDataRecebimentoContribuinte( new DBDate($dataLiberacaoContribuinte) );
  }

  /**
   * Validação realizada para exigir o numero da grafica somente quando a nota
   *  não pertencer ao grupo de notas Eletronicas(q09_gruponotaiss != 2)
   * @throws Exception
   * @access public
   * @return void
   */
  public function validaGrafica () {

  	$iCodigoNota = parent::getNota()->getCodigo();

		$oDaoNotas = db_utils::getDao('notasiss');
		$sSqlNotas = $oDaoNotas->sql_query_file($iCodigoNota, 'q09_gruponotaiss', null, null);
		$rsNotas   = $oDaoNotas->sql_record($sSqlNotas);

		if ( !$rsNotas->erro_status ==  '0' ) {
			throw new Exception('Erro ao buscar nota: ' . $iCodigoNota);
		}

		if ($oDaoNotas->numrows == 0) {
			throw new Exception('Nota informada inválida: ' . $iCodigoNota);
		}

		$oNotas   = db_utils::fieldsMemory($rsNotas, 0);
		$oGrafica = parent::getGrafica();
		$iGrafica = $oGrafica->getCodigo();

		if ($oNotas->q09_gruponotaiss != 2 && empty($iGrafica)) {
			throw new Exception("Campo: Grafica, não informada");
		}
  }

  /**
   * Valida se a numeração inicial e final informada por parametro é valida.
   * - Numeração Inicial deve seguir a sequencia da ultima aidof cadastrada para o tipo de nota e inscrição informado
   * - Numeração Final deve ser a soma da validação inicial com a quantidade liberada
   * @throws Exception
   * @access public
   * @return void
   */
  public function validaNumeracao () {

  	$iInscricao  = parent::getEmpresa()->getInscricao();
  	$iCodigoNota = parent::getNota()->getCodigo();

  	$oDaoNumeracaoAidof = db_utils::getDao('aidof'); ;
  	$sWhere 						= "y08_inscr = $iInscricao and y08_cancel ='f' and y08_nota = $iCodigoNota ";
  	$sSqlNumeracaoAidof = $oDaoNumeracaoAidof->sql_query_file('y08_codigo', 'y08_notain, y08_notafi', 'y08_notafi desc', $sWhere);
  	$rsNumeracaoAidof   = $oDaoNumeracaoAidof->sql_record($sSqlNumeracaoAidof);

  	if ( !$rsNumeracaoAidof->erro_status ==  '0' ) {
  		throw new Exception('Erro ao buscar númeração das notas do AIDOF');
  	}

  	$iNumeracaoInicial = 0;
  	$iNumeracaoFinal   = 0;

  	if ( $oDaoNumeracaoAidof->numrows > 0 ) {

  		$oNumeracaoAidof   = db_utils::fieldsMemory($rsNumeracaoAidof, 0);
  		$iNumeracaoInicial = $oNumeracaoAidof->y08_notafi + 1;
  		$iNumeracaoFinal   = $oNumeracaoAidof->y08_notafi + $this->getQuantidadeLiberada();
  	} else {

  		$iNumeracaoInicial = 1;
  		$iNumeracaoFinal   = $this->getQuantidadeLiberada();
  	}

  	/**
  	 * Verifica se a numeração inicial e final informados são validos
  	 * com as ultimas numerações contidas na tabela aidof
  	 */
  	if (parent::getNumeroInicial() != $iNumeracaoInicial || parent::getNumeroFinal() != $iNumeracaoFinal) {
  		throw new Exception("Numeração inicial e/ou final não são válidos");
  	}
  }

  /**
   * Salvar, inclui ou altera AIDOF
   *
   * @todo - validar informacoes, metodo
   * @access public
   * @return bool
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Sem transação ativa");
    }

    try {
      parent::salvar();
    } catch(Exception $oExeption) {
      throw new Exception($oExeption->getMessage());
    }

    return true;
  }

}