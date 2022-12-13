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

class fornecedor{

	private $iCgm;
	private $iStatusBloqueio;
	private $iControleFornecedor;
	private $lBloqueado;

	/**
	 * Objeto da Instância da Classe Cgm
	 *
	 * @var object_type
	 */
	private $oCgm;

	public function __construct($iCgm){

		$this->iCgm                 = (int)$iCgm;
		$this->lBloqueado           = null;
		$this->iStatusBloqueio      = null;
		$this->iControleFornecedor  = null;
		$this->oCgm                 = CgmFactory::getInstanceByCgm($this->iCgm);


	}

	private function getParametroControleFornecedor() {
		$sSql  = " select pc30_fornecdeb ";
		$sSql .= "   from pcparam ";
		$sSql .= "  where pc30_instit = ".db_getsession("DB_instit");

		$rsSql = db_query($sSql);
		if(pg_num_rows($rsSql) == 0 ||  pg_num_rows($rsSql) == false ){
			throw new Exception("Parâmetro de controle de fornecedores em debito não encontrado !");
		}

		$oParametro = db_utils::fieldsMemory($rsSql,0);
		$this->iControleFornecedor = $oParametro->pc30_fornecdeb;
	}

	public function validaSituacaoFiscal(){

		$dData = date('Y-m-d',db_getsession("DB_datausu"));
		$sSql = "select fc_tipocertidao($this->iCgm,'C','$dData','') as situacao ";
		$rsSql = db_query($sSql);
		if (pg_num_rows($rsSql) == 0 || pg_num_rows($rsSql) == false){
			throw new Exception("Situação Fiscal não encontrada !");
		}
		$oSituacaoFiscal  = db_utils::fieldsMemory($rsSql,0);
		switch ($oSituacaoFiscal->situacao){
			case 'negativa' : $this->lBloqueado = false;
				break;
			case 'positiva' : $this->lBloqueado = true;
			 break;
			case 'regular'  : $this->lBloqueado = false;
			 break;
		}
		return $this->lBloqueado;
	}

	private function verificaBloqueioFornecedor($tipoOrigem=null,$origem=null){

		if($this->lBloqueado == null){
			$this->validaSituacaoFiscal();
		}

		$this->getParametroControleFornecedor();

		if ( $this->lBloqueado && $this->iControleFornecedor != 1 ) {
			$lLiberacao = false;

			$sInnerJoin      = "";
			$sWhereInnerJoin = "";

			if ( $tipoOrigem == 'S' ){

			 if ( $origem != null ) {
			   $sInnerJoin      = " inner join liberafornecedorsol on liberafornecedorsol.pc83_liberafornecedor = liberafornecedor.pc82_sequencial ";
         $sWhereInnerJoin = " and liberafornecedorsol.pc83_solicita = $origem ";
        }

				$sSqlLiberacao  = " select pc82_dataini,                                                ";
				$sSqlLiberacao .= "        pc82_datafim                                                 ";
				$sSqlLiberacao .= "   from liberafornecedor                                             ";
				$sSqlLiberacao .= "        {$sInnerJoin}                                                ";
				$sSqlLiberacao .= "  where liberafornecedor.pc82_numcgm = ".$this->getCgmFornecedor()." ";
				$sSqlLiberacao .= "    and liberafornecedor.pc82_liberasol is true                      ";
				$sSqlLiberacao .= "    and liberafornecedor.pc82_ativo     is true                      ";
				$sSqlLiberacao .= "        {$sWhereInnerJoin}                                           ";
				$sSqlLiberacao .= "  order by pc82_data asc                                             ";

			} else if ( $tipoOrigem == 'P' ) {

			  if ( $origem != null ) {
         $sInnerJoin      = " inner join liberafornecedorpcproc on liberafornecedorpcproc.pc84_liberafornecedor = liberafornecedor.pc82_sequencial ";
         $sWhereInnerJoin = " and liberafornecedorpcproc.pc84_pcproc = $origem ";
        }

	      $sSqlLiberacao  = " select pc82_dataini, ";
	      $sSqlLiberacao .= "        pc82_datafim ";
	      $sSqlLiberacao .= "   from liberafornecedor ";
	      $sSqlLiberacao .= "        {$sInnerJoin} ";
	      $sSqlLiberacao .= "  where liberafornecedor.pc82_numcgm                 = ".$this->getCgmFornecedor()."    ";
	      $sSqlLiberacao .= "    and liberafornecedor.pc82_liberaproc is true                                        ";
	      $sSqlLiberacao .= "    and liberafornecedor.pc82_ativo      is true                                        ";
			  $sSqlLiberacao .= "        {$sWhereInnerJoin}                                                              ";
	      $sSqlLiberacao .= "  order by pc82_data asc";

			} else if($tipoOrigem == 'A') {

				$sSqlLiberacao  = " select pc82_dataini, ";
	      $sSqlLiberacao .= "        pc82_datafim ";
	      $sSqlLiberacao .= "   from liberafornecedor ";
	      $sSqlLiberacao .= "  where liberafornecedor.pc82_liberaaut is true ";
	      $sSqlLiberacao .= "    and liberafornecedor.pc82_ativo     is true ";
        $sSqlLiberacao .= "    and liberafornecedor.pc82_numcgm = ".$this->getCgmFornecedor();
	      $sSqlLiberacao .= "  order by pc82_data asc";

			}

	    $rsSqlLiberacao = db_query($sSqlLiberacao);
	    if( !$rsSqlLiberacao ){
	      throw new Exception("Falha na consulta de liberações para fornecedor !");
	    }

	    $iRowsLiberacao = pg_num_rows($rsSqlLiberacao);

	    if ( $iRowsLiberacao > 0  ) {

		    $dtDataAtual = db_getsession('DB_datausu');
	      $dtAtual     = mktime(0,0,0,date('m',$dtDataAtual),date('d',$dtDataAtual),date('Y',$dtDataAtual));

		    for($iInd = 0; $iInd < $iRowsLiberacao; $iInd++){

		      $oLiberacao = db_utils::fieldsMemory($rsSqlLiberacao,$iInd);

		      if( $oLiberacao->pc82_dataini == "" && $oLiberacao->pc82_datafim == "" ){

		      	$lLiberacao = true;
		      	break;

		      } else {

		        $aData = explode('-',$oLiberacao->pc82_dataini);
		        $dtIni = mktime(0,0,0,$aData[1],$aData[2],$aData[0]);
		        $aData = explode('-',$oLiberacao->pc82_datafim);
		        $dtFim = mktime(0,0,0,$aData[1],$aData[2],$aData[0]);

		        if( $dtIni <= $dtAtual && $dtFim >= $dtAtual ){
		          $lLiberacao = true;
		          break;
		        }
		      }
		    }
	    }


	    if ( !$lLiberacao && $tipoOrigem != 'A' ) {

		    $sSqlLiberacaoGeral  = " select pc82_dataini,                                                                ";
		    $sSqlLiberacaoGeral .= "        pc82_datafim                                                                 ";
		    $sSqlLiberacaoGeral .= "   from liberafornecedor                                                             ";
		    $sSqlLiberacaoGeral .= "        left join liberafornecedorsol    on liberafornecedorsol.pc83_liberafornecedor    = liberafornecedor.pc82_sequencial ";
		    $sSqlLiberacaoGeral .= "        left join liberafornecedorpcproc on liberafornecedorpcproc.pc84_liberafornecedor = liberafornecedor.pc82_sequencial ";
		    $sSqlLiberacaoGeral .= "  where liberafornecedor.pc82_ativo     is true                                      ";
        $sSqlLiberacaoGeral .= "    and liberafornecedor.pc82_numcgm = ".$this->getCgmFornecedor()."                 ";
		    $sSqlLiberacaoGeral .= "    and liberafornecedorsol.pc83_liberafornecedor    is null                         ";
		    $sSqlLiberacaoGeral .= "    and liberafornecedorpcproc.pc84_liberafornecedor is null                         ";
		    $sSqlLiberacaoGeral .= "    and ( liberafornecedor.pc82_liberasol  is true                                   ";
		    $sSqlLiberacaoGeral .= "     or liberafornecedor.pc82_liberaproc is true )                                   ";
		    $sSqlLiberacaoGeral .= "  order by pc82_data asc                                                             ";

		    $rsSqlLiberacaoGeral = db_query($sSqlLiberacaoGeral);

		    if( !$rsSqlLiberacaoGeral ){
		      throw new Exception("Falha na consulta de liberações para fornecedor !");
		    }

		    $iRowsLiberacaoGeral = pg_num_rows($rsSqlLiberacaoGeral);

		    if ( $iRowsLiberacaoGeral > 0  ) {

		      $dtDataAtual = db_getsession('DB_datausu');
		      $dtAtual     = mktime(0,0,0,date('m',$dtDataAtual),date('d',$dtDataAtual),date('Y',$dtDataAtual));

		      for($iInd = 0; $iInd < $iRowsLiberacaoGeral; $iInd++){

		        $oLiberacaoGeral = db_utils::fieldsMemory($rsSqlLiberacaoGeral,$iInd);

		        if( $oLiberacaoGeral->pc82_dataini == "" && $oLiberacaoGeral->pc82_datafim == "" ){

		          $lLiberacao = true;
		          break;

		        } else {

		          $aData = explode('-',$oLiberacaoGeral->pc82_dataini);
		          $dtIni = mktime(0,0,0,$aData[1],$aData[2],$aData[0]);
		          $aData = explode('-',$oLiberacaoGeral->pc82_datafim);
		          $dtFim = mktime(0,0,0,$aData[1],$aData[2],$aData[0]);

		          if( $dtIni <= $dtAtual && $dtFim >= $dtAtual ){
		            $lLiberacao = true;
		            break;
		          }
		        }
		      }
		    }
	    }

	    if ( $lLiberacao ) {

	    	$this->iStatusBloqueio = 1;

	    } else {

		    if ( $this->iControleFornecedor == 2 ) {
		      $this->iStatusBloqueio = 2;
		    } else if ( $this->iControleFornecedor == 3 ) {
		      $this->iStatusBloqueio = 3;
		    }

	    }


		} else {
			$this->iStatusBloqueio = 1;
		}
	}

	public function verificaBloqueioProcessoCompra($origem=null){
	   $this->verificaBloqueioFornecedor('P',$origem);
	}

	public function verificaBloqueioSolicitacao($origem=null){
		$this->verificaBloqueioFornecedor('S',$origem);
	}

  public function verificaBloqueioAutorizacaoEmpenho($origem=null){
    $this->verificaBloqueioFornecedor('A',$origem);
  }

	public function getStatusBloqueio(){
		return $this->iStatusBloqueio;
	}

  public function getSituacaoFiscal(){
    return $this->lBloqueado;
  }

  public function getControleFornecedor(){
    return $this->iControleFornecedor;
  }

  public function getCgmFornecedor(){
    return $this->iCgm;
  }

  /**
   * Retorna um objeto da instancia da classe Cgm
   * @return CgmBase
   */
  public function getCgm() {
    return $this->oCgm;
  }

  /**
   * Notificação de Débitos para o Usuário
   *
   * @param boolean_type $lGerarNotificacaoDebito
   * @param integer_type $iOrigem
   * @param integer_type $iNotificacao
   * @param array_type $aDebitosEmAberto
   *
   * return $iCodigoNotificacao
   */
  public function notificar($lGerarNotificacaoDebito, $iOrigem, $iNotificaBloqueio = null, $aDebitosEmAberto) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transação com o banco de dados aberta.\nProcessamento cancelado.");
    }

    if (empty($this->iCgm)) {
    	throw new Exception("Número CGM não informado.\nProcessamento cancelado.");
    }

    if (empty($iOrigem)) {
    	throw new Exception("Origem não informada.\nProcessamento cancelado.");
    }

  	$oDaoNotificaBloqueioFornecedor    = db_utils::getDao("notificabloqueiofornecedor");
  	$oDaoNotificacao                   = db_utils::getDao("notificacao");
  	$oDaoNotiNumCgm                    = db_utils::getDao("notinumcgm");
  	$oDaoNotiDebitos                   = db_utils::getDao("notidebitos");
  	$oDaoNotiDebitosReg                = db_utils::getDao("notidebitosreg");
  	$oDaoNotificacaoNotificaFornecedor = db_utils::getDao("notificacaonotificafornecedor");

  	$dtDataAtual        = db_getsession("DB_datausu");
    $iAnoUso            = db_getsession("DB_anousu");

    /**
     * Verifica mensagem de observação por origem para a notificação de bloqueio do fornecedor
     */
    switch ($iOrigem) {

    	case 1:

    		$sObservacao = "SOLICITAÇÃO";
    		break;

    	case 2:

    		$sObservacao = 'PROCESSO DE COMPRAS';
    		break;

    	case 3:

    		$sObservacao = 'AUTORIZAÇÃO';
    		break;
    }

    if ( !empty($iNotificaBloqueio) ) {
    	$iCodigoNotificaBloqueioFornecedor = $iNotificaBloqueio;
    } else {

      /**
       * Incluir registros de notificação de bloqueio para o fornecedor
       */
      $oDaoNotificaBloqueioFornecedor->pc86_numcgm       = $this->iCgm;
      $oDaoNotificaBloqueioFornecedor->pc86_id_usuario   = db_getsession('DB_id_usuario');
      $oDaoNotificaBloqueioFornecedor->pc86_data         = date('Y-m-d', $dtDataAtual);
      $oDaoNotificaBloqueioFornecedor->pc86_hora         = db_hora();
      $oDaoNotificaBloqueioFornecedor->pc86_origem       = $iOrigem;
      $oDaoNotificaBloqueioFornecedor->pc86_observacao   = $sObservacao;
      $oDaoNotificaBloqueioFornecedor->pc86_departamento = db_getsession('DB_coddepto');
      $oDaoNotificaBloqueioFornecedor->incluir(null);
      if ($oDaoNotificaBloqueioFornecedor->erro_status == 0) {
        throw new Exception($oDaoNotificaBloqueioFornecedor->erro_msg);
      }

      $iCodigoNotificaBloqueioFornecedor = $oDaoNotificaBloqueioFornecedor->pc86_sequencial;
    }

    /**
     * Verifica se parametro de gerar notificação de débito é igual a true
     */
    if ($lGerarNotificacaoDebito) {

    	/**
    	 * Inclui notificação de débitos do fornecedor
    	 */
	    $oDaoNotificacao->k50_procede = 6;
	    $oDaoNotificacao->k50_dtemite =  date('Y-m-d', $dtDataAtual);
	    $oDaoNotificacao->k50_obs     = 'NOTIFICAÇÃO DE DÉBITOS DE FORNECEDOR';
	    $oDaoNotificacao->k50_instit  = db_getsession('DB_instit');
	    $oDaoNotificacao->incluir(null);
	    if ($oDaoNotificacao->erro_status == 0) {
	      throw new Exception($oDaoNotificacao->erro_msg);
	    }

	    $iCodigoNotificacao = $oDaoNotificacao->k50_notifica;

      $oDaoNotiNumCgm->k57_notifica = $iCodigoNotificacao;
      $oDaoNotiNumCgm->k57_numcgm   = $this->iCgm;
      $oDaoNotiNumCgm->incluir($oDaoNotiNumCgm->k57_notifica, $oDaoNotiNumCgm->k57_numcgm);
      if ($oDaoNotiNumCgm->erro_status == 0) {
        throw new Exception($oDaoNotiNumCgm->erro_msg);
      }

      /*
       * Percorre os debitos em aberto informados por parametro
       */
      foreach ($aDebitosEmAberto as $oDebitosEmAberto) {

	      $rsSqlDebitosNumpre = debitos_numpre($oDebitosEmAberto->iNumpre, 0, 0, $dtDataAtual,
	                                           $iAnoUso, $oDebitosEmAberto->iNumpar);

	      /**
	       * Verifica o retorno da função debitos_numpre caso nao exista registros o retorno será falso
	       */
	      if ($rsSqlDebitosNumpre) {

	        /**
	         * Psquisa se notificação já existe para cada numpre e numpar com a mesma notificação
	         */
	        $sSqlNotificaDebitos  = $oDaoNotiDebitos->sql_query_file($iCodigoNotificacao,
	                                                                 $oDebitosEmAberto->iNumpre,
	                                                                 $oDebitosEmAberto->iNumpar, "*", null, "");
	        $rsSqlNotificaDebitos = $oDaoNotiDebitos->sql_record($sSqlNotificaDebitos);
	        if ($oDaoNotiDebitos->numrows == 0) {

	          $oDaoNotiDebitos->k53_notifica = $iCodigoNotificacao;
	          $oDaoNotiDebitos->k53_numpre   = $oDebitosEmAberto->iNumpre;
	          $oDaoNotiDebitos->k53_numpar   = $oDebitosEmAberto->iNumpar;
	          $oDaoNotiDebitos->incluir($oDaoNotiDebitos->k53_notifica, $oDaoNotiDebitos->k53_numpre,
	                                    $oDaoNotiDebitos->k53_numpar);
	          if ($oDaoNotiDebitos->erro_status == 0) {
	            throw new Exception($oDaoNotiDebitos->erro_msg);
	          }
	        }

	        $iNumRowsDebitosNumpre = pg_num_rows($rsSqlDebitosNumpre);
	        for ($iInd = 0; $iInd < $iNumRowsDebitosNumpre; $iInd++) {

	          $oDebitosNumpre = db_utils::fieldsMemory($rsSqlDebitosNumpre, $iInd);

	          /*
	           * Inclui um debito recalculado valores para cada notificação de debito, numpre e numpar
	           */
	          $oDaoNotiDebitosReg->k43_notifica = $iCodigoNotificacao;
	          $oDaoNotiDebitosReg->k43_numpre   = $oDebitosEmAberto->iNumpre;
	          $oDaoNotiDebitosReg->k43_numpar   = $oDebitosEmAberto->iNumpar;
	          $oDaoNotiDebitosReg->k43_receit   = $oDebitosNumpre->k00_receit;
	          $oDaoNotiDebitosReg->k43_vlrcor   = $oDebitosNumpre->vlrcor;
	          $oDaoNotiDebitosReg->k43_vlrdes   = $oDebitosNumpre->vlrdesconto;
	          $oDaoNotiDebitosReg->k43_vlrjur   = $oDebitosNumpre->vlrjuros;
	          $oDaoNotiDebitosReg->k43_vlrmul   = $oDebitosNumpre->vlrmulta;
	          $oDaoNotiDebitosReg->incluir(null);
	          if ($oDaoNotiDebitosReg->erro_status == 0) {
	            throw new Exception($oDaoNotiDebitosReg->erro_msg);
	          }
	        }
	      }
      }

	    /*
	     * Inclui registros de notificação da notificação do fornecedor
	     */
	    $oDaoNotificacaoNotificaFornecedor->pc87_notificacao                = $iCodigoNotificacao;
	    $oDaoNotificacaoNotificaFornecedor->pc87_notificabloqueiofornecedor = $iCodigoNotificaBloqueioFornecedor;
	    $oDaoNotificacaoNotificaFornecedor->incluir(null);
	    if ($oDaoNotificacaoNotificaFornecedor->erro_status == 0) {
	      throw new Exception($oDaoNotificacaoNotificaFornecedor->erro_msg);
	    }
    }

    return $iCodigoNotificaBloqueioFornecedor;
  }

	/**
	 * Retorna o representante legal cadastrado para o fornecedor
	 * @param DBDate|null $oData
	 *
	 * @return CgmFisico|CgmJuridico|null
	 * @throws DBException
	 */
	public function getRepresentanteLegal(DBDate $oData = null) {

		$sCampos    = "b.z01_numcgm";
		$sOrder     = "pcfornereprlegal.pc81_sequencia limit 1";
		$sWhere     = " pc81_cgmforn = {$this->iCgm} ";

		if (!empty($oData)) {

			$sWhere .= " and (pc81_datini <= '{$oData->getDate()}' or pc81_datini is null) ";
			$sWhere .= " and (pc81_datfin >= '{$oData->getDate()}' or pc81_datfin is null) ";
		}
		$oDaoRepresentanteLegal = new cl_pcfornereprlegal();
		$sSqlRepresentanteLegal = $oDaoRepresentanteLegal->sql_query(null, $sCampos, $sOrder, $sWhere);
		$rsRepresentanteLegal   = db_query($sSqlRepresentanteLegal);
		if (!$rsRepresentanteLegal) {
			throw new DBException("Houve um erro ao buscar o representante legal para o Licitante.");
		}

		if (pg_num_rows($rsRepresentanteLegal) == 0) {
			return null;
		}
		return CgmRepository::getByCodigo(db_utils::fieldsMemory($rsRepresentanteLegal, 0)->z01_numcgm);
	}
}
?>