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

use Dompdf\Exception;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Modelo;

/**
 * Class Importacao
 * Classe que represetação a importação do plano de contas do PCASP.
 * @package ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP
 */
class Importacao {

	/**
	 * @var int
	 */
	private $iId;

	/**
	 * @var int
	 */
	private $iModelo;

	/**
	 * @var \DBDate
	 */
	private $oData;

	/**
	 * @var Modelo
	 */
	private $oModelo;


  /**
   * Último ano cadastrado para o plano de contas
   * @var integer
   */
  private $iAnoFinal;

	public function __construct($iCodigo = null) {

		if (!empty($iCodigo)) {

			$oDao     = new \cl_importacaoplanoconta();
			$sSql     = $oDao->sql_query_file($iCodigo);
			$rsResult = db_query($sSql);

			if (!$rsResult) {
				throw new \DBException("Houve uma falha ao buscar a importação do PCASP com código {$iCodigo}.");
			}

			if (pg_num_rows($rsResult) != 1) {
				throw new \DBException("Importação do PCASP com código {$iCodigo} não encontrado.");
			}

			$oStd = \db_utils::fieldsMemory($rsResult, 0);
			$this->setId($oStd->c96_sequencial);
			$this->setCodigoModelo($oStd->c96_modeloplanoconta);
			$this->setData(new \DBDate($oStd->c96_data));
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
	 * @return int
	 */
	public function getCodigoModelo() {
		return $this->iModelo;
	}

	/**
	 * @param int $iModelo
	 */
	public function setCodigoModelo($iModelo) {
		$this->iModelo = $iModelo;
	}

	/**
	 * @return \DBDate
	 */
	public function getData() {
		return $this->oData;
	}

	/**
	 * @param \DBDate $oData
	 */
	public function setData($oData) {
		$this->oData = $oData;
	}

	/**
	 * @return Modelo
	 * @throws \ParameterException
	 */
	public function getModelo() {

    $iCodigoModelo = $this->getCodigoModelo();
		if (empty($iCodigoModelo)) {
			throw new \ParameterException("Código do modelo do PCASP não informado.");
		}

		if (empty($this->oModelo)) {
			$this->oModelo = new Modelo($this->getCodigoModelo());
		}
		return $this->oModelo;
	}

	public function salvar() {

    $this->processar();

    $oDao                       = new \cl_importacaoplanoconta();
    $oDao->c96_modeloplanoconta = $this->getCodigoModelo();
    $oDao->c96_data             = $this->getData()->getDate(\DBDate::DATA_EN);

    if (!$oDao->incluir()) {
     throw new \Exception("Ocorreu um erro ao atualizar o plano de contas.");
    }

    $this->setId($oDao->c96_sequencial);
		return true;
	}

  public function consultaRecurso(){

    $oDaoOrcTipoRec = new \cl_orctiporec();
    $sSqlOrcTipoRec = $oDaoOrcTipoRec->sql_query_file(1);
    $rsOrcTipoRec = db_query($sSqlOrcTipoRec);

    if(!empty($rsOrcTipoRec)){
      if(pg_num_rows($rsOrcTipoRec) > 0){
        return true;
      }
    }
    return false;
  }

	private function processar() {

    $aContasNovas = $this->getModelo()->getContas();
    $iAnoDestino  = $this->getModelo()->getExercicio();

    $oDaoConplano = new \cl_conplano();
    $sSqlConplano = $oDaoConplano->sql_query_file(null, null, "max(c60_anousu) as max");
    $rsConplano   = db_query($sSqlConplano);

    $lRecurso = $this->consultaRecurso();
    if(empty($lRecurso)){
      throw new \DBException("Não foi localizado o Recurso Orçamentário necessário para a atualização do plano de contas.\nCadastre o recurso para prosseguir com a Atualização do PCASP.");
    }

    if (!$rsConplano | pg_num_rows($rsConplano) != 1) {
      throw new \DBException("Houve um erro ao verificar o plano de contas.");
    }

    $this->iAnoFinal = \db_utils::fieldsMemory($rsConplano, 0)->max;

    $sSqlSelect       = "select c60_codcon from conplano where c60_anousu = {$iAnoDestino} and c60_estrut = $1 order by c60_codcon desc";//exercicio e estrutural
    $sSqlSelectFilhas = "select c60_estrut, c60_codcon from conplano where c60_anousu = $1 and substr(c60_estrut, 1, $2) = $3";//nivel e estrutural
    $sSqlSelectReduz  = "select c61_reduz  
                           from conplano
                                inner join conplanoreduz on c61_anousu = c60_anousu
                                                        and c61_codcon = c60_codcon
                          where c60_anousu between {$iAnoDestino} and {$this->iAnoFinal}
                            and c60_estrut = $1";

    $sSqlSelectReduzLancam = "
      select c69_sequen 
        from conlancamval 
       where c69_anousu between {$iAnoDestino} and {$this->iAnoFinal}
         and (c69_credito = $1 or c69_debito = $1)";

    $sSqlUpdate = "
      update conplano 
         set c60_descr                   = $1, 
             c60_finali                  = $2, 
             c60_consistemaconta         = $3, 
             c60_identificadorfinanceiro = $4,  
             c60_naturezasaldo           = $5, 
             c60_codsis                  = $6 
       where c60_estrut = $7 
         and c60_anousu between $8 and {$this->iAnoFinal} ";

    $sSqlInsert = "insert into conplano 
                   values ($1, 
                           $2, 
                           $3, 
                           $4, 
                           $5, 
                           $6, 
                           $7, 
                           $8, 
                           $9, 
                           $10,
                           $11)";

    $sSqlInsertReduz            = "insert into conplanoreduz values($1, $2, nextval('conplanoreduz_c61_reduz_seq'), $3, 1, 0)";
    $sSqlInsertReduzCodReduz    = "insert into conplanoreduz values($1, $2, $3, $4, 1, 0)";
    $sSqlInsertReduzExe         = "insert into conplanoexe values($1, currval('conplanoreduz_c61_reduz_seq'), 1, 0, 0)";
    $sSqlInsertReduzExeCodReduz = "insert into conplanoexe values($1, $2, 1, 0, 0)";
    $sSqlSelectReduzInst        = "select x.c61_reduz as reduzido, y.c61_reduz as atualizado 
                                    from conplanoreduz as x
                                         left join conplanoreduz as y on y.c61_codcon = x.c61_codcon
                                                                      and y.c61_anousu = {$iAnoDestino}
                                                                      and y.c61_instit = x.c61_instit
                                    where x.c61_codcon = $1 
                                          and x.c61_anousu = {$iAnoDestino}-1 
                                          and x.c61_instit = $2";

    pg_prepare("importacao_pcasp_select"                   , $sSqlSelect);
    pg_prepare("importacao_pcasp_select_reduz_instit"      , $sSqlSelectReduzInst);
    pg_prepare("importacao_pcasp_update"                   , $sSqlUpdate);
    pg_prepare("importacao_pcasp_insert"                   , $sSqlInsert);
    pg_prepare("importacao_pcasp_insert_reduz"             , $sSqlInsertReduz);
    pg_prepare("importacao_pcasp_insert_reduz_codreduz"    , $sSqlInsertReduzCodReduz);
    pg_prepare("importacao_pcasp_insert_reduzexe"          , $sSqlInsertReduzExe);
    pg_prepare("importacao_pcasp_insert_reduzexe_codreduz" , $sSqlInsertReduzExeCodReduz);

    foreach ($aContasNovas as $oConta) {

      if ($oConta->isExclusao()) {

         $this->deletaConta($oConta);

      } else {

        $aParametrosBusca = array($oConta->getEstruturalFormatado());
        $rsConta          = pg_execute("importacao_pcasp_select", $aParametrosBusca);

        if (!$rsConta) {
          throw new \DBException("Houve um erro ao buscar a conta de estrutural {$oConta->getEstruturalFormatado()} no exercício {$iAnoDestino}.");
        }

        if (pg_num_rows($rsConta) > 0) {
          $iCodCon = \db_utils::fieldsMemory($rsConta, 0)->c60_codcon;
          $this->atualizaConta($oConta, $iCodCon);
        } else {
          $this->insereConta($oConta, $this->iAnoFinal);
        }
      }
    }
  }

  public static function consultaExercicioImportado($iExercicio){

    $oExercicio = new Exercicio();
    return $oExercicio->exercicioImportado($iExercicio);
  }

  /**
   * Deleta a conta pelo estrutural e ano do modelo.
   * @param Conta $oConta
   *
   * @throws \DBException
   */
  private function deletaConta(Conta $oConta) {

    $iNivelEstrutura     = \ContaPlano::getNivelEstrutura($oConta->getEstrutural());
    $sEstruturalAteNivel = \ContaPlano::getEstruturalAteNivel($oConta->getEstrutural(), $iNivelEstrutura);

    $aWhere = array(
      "conplano.c60_estrut ilike '".str_replace('.', '', $sEstruturalAteNivel)."%'",
      "conplano.c60_anousu between {$this->oModelo->getExercicio()} and {$this->iAnoFinal}"
    );
    $oDaoReduzidos          = new \cl_conplanoreduz();
    $sSqlVerificaLancamento = $oDaoReduzidos->sql_query_razao(null, null, "conplanoreduz.*", null, implode(' and ', $aWhere). " and c69_anousu >= {$this->oModelo->getExercicio()}");
    $rsVerificaLancamento   = db_query($sSqlVerificaLancamento);

    if(!$rsVerificaLancamento){
      throw new \DBException("Não foi possivel verificar os lançamentos para o estrutural {$oConta->getEstrutural()}");
    }

    if(pg_num_rows($rsVerificaLancamento) > 0){
      throw new \DBException("Estrutural {$oConta->getEstrutural()}, possui lançamentos. Procedimento Cancelado.");
    }

    /*
     * Busca e exclusão dos códigos reduzidos
     */
    $sCamposReduzidos  = "array_to_string(array_accum(distinct c61_reduz), ',') as reduzidos";
    $sSqlBuscaReduzido = $oDaoReduzidos->sql_query_analitica(null, null, $sCamposReduzidos, null, implode(' and ', $aWhere));
    $rsBuscaReduzido   = db_query($sSqlBuscaReduzido);
    if (!$rsBuscaReduzido) {
      throw new \DBException("Não foi possivel verificar os códigos reduzidos para o estrutural {$oConta->getEstrutural()}.");
    }

    $sReduzidos = \db_utils::fieldsMemory($rsBuscaReduzido, 0)->reduzidos;
    if (!empty($sReduzidos)) {

      $rsDeleteReduzidos = db_query("delete from conplanoreduz where c61_reduz in({$sReduzidos}) and c61_anousu between {$this->oModelo->getExercicio()} and {$this->iAnoFinal}");
      if (!$rsDeleteReduzidos) {
        throw new \DBException("Não foi possivel excluir os reduzidos para o estrutural {$oConta->getEstrutural()}.");
      }
    }

    /*
     * Excluir Plano de Contas
     */
    $rsExcluiVinculo = db_query("
      delete from conplanocontacorrente 
       using conplano 
       where conplanocontacorrente.c18_codcon = conplano.c60_codcon 
         and conplanocontacorrente.c18_anousu = conplano.c60_anousu 
         and ".implode(' and ', $aWhere)."
    ");
    if(!$rsExcluiVinculo){
      throw new \DBException("Não foi possivel excluir o vínculo de conta corrente do estrutural {$oConta->getEstrutural()}.", 1);
    }

    $rsExcluiVinculo = db_query("
      delete from conplanoconplanoorcamento 
       using conplano 
       where conplanoconplanoorcamento.c72_conplano = conplano.c60_codcon 
         and conplanoconplanoorcamento.c72_anousu   = conplano.c60_anousu 
         and ".implode(' and ', $aWhere)."
    ");
    if(!$rsExcluiVinculo){
      throw new \DBException("Não foi possivel excluir o vínculo de orçamento do estrutural {$oConta->getEstrutural()}.", 1);
    }

    $rsExcluiVinculo = db_query("
      delete from conplano 
       where ".implode(' and ', $aWhere)."
    ");
    if(!$rsExcluiVinculo){
      throw new \DBException("Não foi possivel excluir o estrutural {$oConta->getEstrutural()}.", 1);
    }
  }

  /**
   * Faz a inserção da conta pcasp.
   * @param Conta $oConta
   * @param int $this->iAnoFinal
   *
   * @throws \DBException
   * @throws \ParameterException
   */
  private function insereConta(Conta $oConta, $iAnoFim) {

    $iCodigoConta = \db_utils::fieldsMemory(db_query("select nextval('conplano_c60_codcon_seq') as codcon"), 0)->codcon;
    for ($iAno = $oConta->getModelo()->getExercicio(); $iAno <= $iAnoFim; $iAno++) {

      $aParametros = array(
        $iCodigoConta,
        $iAno,
        $oConta->getEstruturalFormatado(),
        $oConta->getTitulo(),
        $oConta->getFuncao(),
        $this->getCodigoDetalhamentoSistema($oConta),
        1,
        $oConta->getSistema(),
        $oConta->getIndicadorSuperavit(),
        $oConta->getNaturezaSaldo(),
        ''
      );

      $rsConta = pg_execute("importacao_pcasp_insert", $aParametros);
      if ($oConta->isAnalitica()) {

        $aParametrosBusca = array($oConta->getEstruturalFormatado());
        $rsConta          = pg_execute("importacao_pcasp_select", $aParametrosBusca);
        $iCodConta        = \db_utils::fieldsMemory($rsConta, 0)->c60_codcon;
        $this->geraContasAnalitica($oConta, $iCodConta, $iAno);
      }

      if (!$rsConta) {
        throw new \DBException("Houve um erro ao criar a conta de estrutural {$oConta->getEstruturalFormatado()} no exercício {$this->getModelo()->getExercicio()}.");
      }
    }
  }

  /**
   * Gera as contas filhas para a conta Analitica
   * @param  Conta  $oConta
   * @param  int $iCodConta
   * @param  int $iAno
   * @throws \DBException
   */
  private function geraContasAnalitica(Conta $oConta, $iCodConta, $iAno=null){

    if(empty($iCodConta)){
      throw new \DBException("Código da Conta não informado para o estrutural {$oConta->getEstrutural()}");
    }

    if(empty($iAno)){
      $iAno = $oConta->getModelo()->getExercicio();
    }

    $aInstituicoes = \InstituicaoRepository::getInstituicoes();

    foreach ($aInstituicoes as $oInstituicao) {

      // Verifica se existe reduzido para a instituição atual
      $aParametrosBusca    = array($iCodConta, $oInstituicao->getCodigo());
      $rsSelectReduzInstit = pg_execute('importacao_pcasp_select_reduz_instit', $aParametrosBusca);
      if(!$rsSelectReduzInstit){
        throw new \DBException("Houve um erro ao buscar informações do reduzido da instituição {$oInstituicao->getDescricao()}");
      }

      // Não existe reduzido no ano anterior, será gerado um novo reduzido
      if(pg_num_rows($rsSelectReduzInstit) == 0){      
        
        $aParametrosInsere = array($iCodConta, $iAno, $oInstituicao->getCodigo());
        $rsConplanoReduz   = pg_execute('importacao_pcasp_insert_reduz', $aParametrosInsere);
        if (!$rsConplanoReduz) {
          throw new \DBException("Houve um erro ao salvar o reduzido para o plano de contas.");
        }

        $aParametros = array($iAno);//anousu
        $rsConplanoReduzExe = pg_execute('importacao_pcasp_insert_reduzexe', $aParametros);
        if (!$rsConplanoReduzExe) {
          throw new \DBException("Houve um erro ao salvar o reduzido para o plano de contas.");
        }
      } else {
        
        if(empty(\db_utils::fieldsMemory($rsSelectReduzInstit, 0)->atualizado)){

          $iCodReduzido = \db_utils::fieldsMemory($rsSelectReduzInstit, 0)->reduzido;

          $aParametrosInsere = array($iCodConta, $iAno, $iCodReduzido, $oInstituicao->getCodigo());
          $rsConplanoReduz   = pg_execute('importacao_pcasp_insert_reduz_codreduz', $aParametrosInsere);
          if (!$rsConplanoReduz) {
            throw new \DBException("Houve um erro ao salvar o reduzido para o plano de contas.");
          }
        }
      }
    }
  }

  /**
   * Faz a atualização do plano pcasp.
   * @param Conta $oConta
   *
   * @throws \DBException
   * @throws \ParameterException
   */
  private function atualizaConta(Conta $oConta, $iCodConta) {

    $aParametros = array(
      $oConta->getTitulo(),
      $oConta->getFuncao(),
      $oConta->getSistema(),
      $oConta->getIndicadorSuperavit(),
      $oConta->getNaturezaSaldo(),
      $this->getCodigoDetalhamentoSistema($oConta),
      $oConta->getEstruturalFormatado(),
      $this->getModelo()->getExercicio()
    );

    if ($oConta->isAnalitica()) {

      /*
       * ajustate para nao incluir reduzido para contas com filhas
       * @todo REFATORAR
       */
      $iNivel = \ContaPlano::getNivelEstrutura($oConta->getEstrutural());
      $sEstruturalAteNivel = str_replace('.', '', \ContaPlano::getEstruturalAteNivel($oConta->getEstrutural(), $iNivel));
      $iTotalCaracteres = strlen($sEstruturalAteNivel);

      $sSqlBusca = "select conplano.* as total
                      from conplano 
                           inner join conplanoreduz on c61_codcon = c60_codcon
                                                   and c61_anousu = c60_anousu 
                     where c60_anousu = ".($this->getModelo()->getExercicio() - 1)."
                       and (substring(c60_estrut, 1, {$iTotalCaracteres}) = '{$sEstruturalAteNivel}'
                       and substring(c60_estrut, 1, {$iTotalCaracteres}) >= '{$sEstruturalAteNivel}' ) order by 3";
      $rsBusca = db_query($sSqlBusca);

      if (pg_num_rows($rsBusca) > 0) {
        return;
      }
      $this->geraContasAnalitica($oConta, $iCodConta);
    }

    $rsConta = pg_execute("importacao_pcasp_update", $aParametros);

    if (!$rsConta) {
      throw new \DBException("Houve um erro ao buscar a conta de estrutural {$oConta->getEstruturalFormatado()} no exercício {$this->getModelo()->getExercicio()}.");
    }
  }

  /**
   * Aplica a regra para definir o codsis para a conta, com base na conta.
   * @param Conta $oConta
   *
   * @return int
   */
  private function getCodigoDetalhamentoSistema(Conta $oConta) {

    $sEstrutural = str_replace(".", "", $oConta->getEstrutural());

  	if (!$oConta->isAnalitica()) {
  		return 0;
		}

		if (in_array(substr($sEstrutural, 0, 1), array('7', '8'))) {
      return 4;
    }

    if (in_array(substr($sEstrutural, 0, 1), array('5', '6'))) {
      return 3;
    }

    if (in_array($oConta->getIndicadorSuperavit(), array('P', 'N'))) {
      return 2;
    }

    if ($oConta->getIndicadorSuperavit() == 'F') {

      if (substr($sEstrutural,0,5) == "11111") {
          return 5;
      }

      if (substr($sEstrutural, 0, 5) == "11112" || substr($sEstrutural, 0, 5) == "11113" || substr($sEstrutural, 0, 5) == "11113") {
        return 6;
      }
    }

    if (substr($sEstrutural, 0, 5) == "21881") {
      return 7;
    }

		return 0;
	}
}