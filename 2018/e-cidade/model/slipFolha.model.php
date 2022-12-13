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


class slipFolha {

  /**
   *
   */
  function __construct() {

  }


  public function geraSlipFolha( $iRecurso='',$iContaDebito='',$iContaCredito='',$nValor='',$iCodFolhaSlip='',$iNumCgm='',$iRetencao='',$sObservacao='',$iInstit=''){

    $sMsgErro = 'Geração de SLIP abortada';

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }

    if ( trim($iRecurso) == '' ) {
      throw new Exception("{$sMsgErro}, \n Recurso não informado!");
    }
    if ( trim($iContaDebito) == '' ) {
      throw new Exception("{$sMsgErro}, \n Conta Débito não informado! ");
    }
    if ( trim($iContaCredito) == '' ) {
      throw new Exception("{$sMsgErro}, \n Conta Crédito não informado!");
    }
    if ( trim($nValor) == '' ) {
      throw new Exception("{$sMsgErro}, \n Valor não informado!");
    }
    if ( trim($iCodFolhaSlip) == '' ) {
      throw new Exception("{$sMsgErro}, \n Código do SLIP da folha não informado!");
    }
    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }
    if ( trim($iRetencao) == '' ) {
      throw new Exception("{$sMsgErro}, \n Código da retenção não informada!");
    }

    $oDaoDBConfig                = db_utils::getDao('db_config');
  	$oDaoSlip                    = db_utils::getDao('slip');
  	$oDaoRetencaoTipoRec         = db_utils::getDao('retencaotiporec');
  	$oDaoSlipNum                 = db_utils::getDao('slipnum');
  	$oDaoSlipRecurso             = db_utils::getDao('sliprecurso');
    $oDaorhSlipFolhaSlip         = db_utils::getDao('rhslipfolhaslip');
  	$oDaoCfPess                  = db_utils::getDao('cfpess');
  	$oDaoConHist                 = db_utils::getDao('conhist');

    if ( trim($iNumCgm) == '' ) {
    	$rsCgmInstit   = $oDaoDBConfig->sql_record($oDaoDBConfig->sql_query_file($iInstit,"numcgm"));
    	$oDaoCgmInstit = db_utils::fieldsMemory($rsCgmInstit,0);
    	$iNumCgm       = $oDaoCgmInstit->numcgm;
    }

  	$rsHistPadrao = $oDaoCfPess->sql_record($oDaoCfPess->sql_query_file(db_anofolha(),
  	                                                                    db_mesfolha(),
  	                                                                    db_getsession('DB_instit')));
  	if ( $oDaoCfPess->numrows > 0 ) {
      $oHistPadrao = db_utils::fieldsMemory($rsHistPadrao,0);

      // Consistência o histórico do Slip
      $oDaoConHist->sql_record($oDaoConHist->sql_query_file($oHistPadrao->r11_histslip));
      if ($oDaoConHist->numrows == 0) {
      	throw new Exception("Histórico configurado não existe no cadastro.\nVERIFIQUE PARAMETROS GERAIS.");
      }

  	} else {
  		throw new Exception("{$sMsgErro}, \n Histórico padrão não configurado!");
  	}

  	/**
  	 * Buscamos se a conta credito possui uma conta extra-orçamentaria vinculada
  	 * caso possua, devemos usar essa conta como conta pagadora
  	 */
  	$oDaoSaltesExtra = $oDaoSaltesExtra  = db_utils::getDao("saltesextra");
  	$sSqlContaextra  = $oDaoSaltesExtra->sql_query_extra(null,
                                                          "k109_contaextra",
                                                          null,
                                                          "k109_saltes = {$iContaCredito}");

    $rsContaExtra = $oDaoSaltesExtra->sql_record($sSqlContaextra);
    if ($oDaoSaltesExtra->numrows > 0) {
      $iContaCredito = db_utils::fieldsmemory($rsContaExtra, 0)->k109_contaextra;
    }

		$oDaoSlip->k17_data     = date('Y-m-d',db_getsession("DB_datausu"));
		$oDaoSlip->k17_hist     = $oHistPadrao->r11_histslip;
		$oDaoSlip->k17_texto    = $sObservacao;
		$oDaoSlip->k17_instit   = $iInstit;
		$oDaoSlip->k17_situacao = 1;
		$oDaoSlip->k17_debito   = $iContaDebito;
		$oDaoSlip->k17_credito  = $iContaCredito;
		$oDaoSlip->k17_valor    = $nValor;

		$oDaoSlip->incluir(null);

		if ( $oDaoSlip->erro_status == '0' ) {
 		  throw new Exception($oDaoSlip->erro_msg);
		}

		$iCodSlip = $oDaoSlip->k17_codigo;

    /**
     * Agendamos o slip caso o parametro emparametro.e30_agendaautomatico = true.
     */
    require_once(modification(Modification::getFile('model/agendaPagamento.model.php')));
    $oAgendaPagamento = new agendaPagamento();
    $oSlipAgenda      = new stdClass();
    $oSlipAgenda->iCodigoSlip = $iCodSlip;
    $oSlipAgenda->nValor      = ""+$nValor+"";

    /**
      * Procuramos se a conta credito do slip é uma conta pagadora no caixa.
      * caso for. setamos essa conta como conta pagadora na agenda.
      */
    $oParametroAgenda = (db_stdClass::getParametro("empparametro",array(db_getsession('DB_anousu')),"e30_agendaautomatico"));
    if ($oParametroAgenda[0]->e30_agendaautomatico == "t" ) {

       $oDaoEmpAgeTipo = db_utils::getDao("empagetipo");
       $sSqlConta      = $oDaoEmpAgeTipo->sql_query_file(null,
                                                         "e83_codtipo",
                                                          null,
                                                         "e83_conta = {$iContaCredito}");
       $rsConta        = $oDaoEmpAgeTipo->sql_record($sSqlConta);
       if ($oDaoEmpAgeTipo->numrows > 0 ) {
         $oSlipAgenda->iCodTipo = db_utils::fieldsMemory($rsConta,0)->e83_codtipo;
       }

       $this->iMovimento =  $oAgendaPagamento->addMovimentoAgenda(2, $oSlipAgenda);
    }


		$oDaoSlipNum->k17_numcgm = $iNumCgm;
		$oDaoSlipNum->k17_codigo = $iCodSlip;
		$oDaoSlipNum->incluir($iCodSlip);

    if ( $oDaoSlipNum->erro_status == '0' ) {
      throw new Exception($oDaoSlipNum->erro_msg);
    }

    $oDaoSlipRecurso->k29_slip    = $iCodSlip;
    $oDaoSlipRecurso->k29_recurso = $iRecurso;
    $oDaoSlipRecurso->k29_valor   = $nValor;
    $oDaoSlipRecurso->incluir(null);

    if ( $oDaoSlipRecurso->erro_status == '0' ) {
      throw new Exception($oDaoSlipRecurso->erro_msg);
    }

    $oDaorhSlipFolhaSlip->rh82_rhslipfolha = $iCodFolhaSlip;
    $oDaorhSlipFolhaSlip->rh82_slip        = $iCodSlip;
    $oDaorhSlipFolhaSlip->incluir(null);

    if ( $oDaorhSlipFolhaSlip->erro_status == '0' ) {
      throw new Exception($oDaorhSlipFolhaSlip->erro_msg);
    }

    /**
     * Inclui vinculo com o tipo de operação, vinculando
     */
    if (USE_PCASP) {

      $aTipo = array(1, 2);

      foreach ($aTipo as $iTipo) {

        $oDAOSlipconcarpeculiar = db_utils::getDao("slipconcarpeculiar");
        $oDAOSlipconcarpeculiar->k131_slip           = $iCodSlip;
        $oDAOSlipconcarpeculiar->k131_tipo           = $iTipo;
        $oDAOSlipconcarpeculiar->k131_concarpeculiar = "000";
        $oDAOSlipconcarpeculiar->incluir(null);

        if ($oDAOSlipconcarpeculiar->erro_status == '0' ) {
          throw new Exception($oDAOSlipconcarpeculiar->erro_msg);
        }

      }

      $oDAOVinculo = db_utils::getDao("sliptipooperacaovinculo");
      $oDAOVinculo->k153_slip             = $iCodSlip;
      $oDAOVinculo->k153_slipoperacaotipo = 13;
      $oDAOVinculo->incluir($iCodSlip);

      if ($oDAOVinculo->erro_status == '0') {
        throw new Exception($oDAOVinculo->erro_msg);
      }
    }
    return $iCodSlip;
  }

  public function geraSlipFolhaLote( $aDadosSlip = array(),$iInstit=''){

    $sMsgErro    = 'Geração de SLIP em lote abortada';
    $aListaSlip = array();

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }

    if ( empty($aDadosSlip) ) {
    	throw new Exception("{$sMsgErro}, dados não informados!");
    }

    if ( trim($iInstit) == '' ) {
      $iInstit = db_getsession('DB_instit');
    }
    foreach ( $aDadosSlip as $oSlip ){

    	$aListaSlip[] = $this->geraSlipFolha($oSlip->iRecurso,
							                             $oSlip->iContaDebito,
							                             $oSlip->iContaCredito,
							                             $oSlip->nValor,
							                             $oSlip->iCodFolhaSlip,
							                             $oSlip->iNumCgm,
							                             $oSlip->iRetencao,
							                             $oSlip->sObservacao,
							                             $iInstit);
    }


    return $aListaSlip;

  }

  public function geraPlanilhaSlip( $sListaSlips='', $iCgm='', $iInstit='' ) {

    $sMsgErro     = 'Geração das Planilhas de SLIP abortada';
    $sCodPlanilha = '';

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }

  	if ( trim($sListaSlips) == '' ) {
  		throw new Exception("{$sMsgErro}, nenhum slip informado!");
  	}

  	if ( trim($iInstit) == '' ) {
  		$iInstit = db_getsession('DB_instit');
  	}

    $oDaoSlip            = db_utils::getDao('slip');
    $oDaoPlaCaixa        = db_utils::getDao('placaixa');
    $oDaoPlaCaixaRec     = db_utils::getDao('placaixarec');
    $oDaoPlaCaixaRecSlip = db_utils::getDao('placaixarecslip');

    $sCampoRubricas  = " distinct on (rubric,recurso,sequencial)        ";
    $sCampoRubricas .= "          rh73_sequencial      as sequencial,   ";
    $sCampoRubricas .= "          rh73_rubric          as rubric,       ";
    $sCampoRubricas .= "          rh82_slip            as slip,         ";
    $sCampoRubricas .= "          rh79_recurso         as recurso,      ";
    $sCampoRubricas .= "          rh79_concarpeculiar   as caracteristicapeculiar, ";
    $sCampoRubricas .= "          rh78_retencaotiporec as retencao,     ";
    $sCampoRubricas .= "          e21_receita          as receita,      ";
    $sCampoRubricas .= "          rh41_conta           as contacredito, ";
    $sCampoRubricas .= "          e48_cgm              as numcgm,       ";
    $sCampoRubricas .= "          rh73_valor           as valor         ";

    $sWhereRubricas  = "     rh73_tiporubrica = 2                       ";
    $sWhereRubricas .= " and rh82_slip in ( {$sListaSlips} )            ";
    $sWhereRubricas .= " and k110_slip is null                          ";

    //Verifica se conta do recurso está configurada para o ano
    $sWhereRubricas .= " and exists (select 1
                                       from saltes
                                            inner join conplanoreduz on conplanoreduz.c61_reduz  = saltes.k13_reduz
                                                                    and conplanoreduz.c61_anousu = ".db_getsession("DB_anousu")."
                                      where saltes.k13_reduz = rhcontasrec.rh41_conta)";

    $sSqlSubRubricas = $oDaoSlip->sql_query_rhemprubricas(null,$sCampoRubricas,null,$sWhereRubricas);


    $sSqlRubricas  = " select rubric,                                 ";
		$sSqlRubricas .= "        slip,                                   ";
		$sSqlRubricas .= "        recurso,                                ";
		$sSqlRubricas .= "        retencao,                               ";
		$sSqlRubricas .= "        receita,                                ";
		$sSqlRubricas .= "        contacredito,                           ";
		$sSqlRubricas .= "        caracteristicapeculiar,                 ";
		$sSqlRubricas .= "        case when numcgm is null then (select numcgm from db_config where codigo = ".db_getsession('DB_instit').") else numcgm end as numcgm, ";
		$sSqlRubricas .= "        sum(valor) as valor                     ";
		$sSqlRubricas .= "   from ( {$sSqlSubRubricas} ) as x             ";
    $sSqlRubricas .= " group by rubric,                               ";
    $sSqlRubricas .= "          slip,                                 ";
    $sSqlRubricas .= "          retencao,                             ";
    $sSqlRubricas .= "          receita,                              ";
    $sSqlRubricas .= "          contacredito,                         ";
		$sSqlRubricas .= "          caracteristicapeculiar,                 ";
    $sSqlRubricas .= "          numcgm,                               ";
    $sSqlRubricas .= "          recurso                               ";
    $sSqlRubricas .= " order by rubric,                               ";
    $sSqlRubricas .= "          recurso                               ";
    $rsRubricas      = $oDaoSlip->sql_record($sSqlRubricas);
    $iNroRubricas    = $oDaoSlip->numrows;

    if ( $iNroRubricas > 0 ) {

      $oDaoPlaCaixa->k80_data   = date('Y-m-d',db_getsession("DB_datausu"));
      $oDaoPlaCaixa->k80_instit = $iInstit;
      $oDaoPlaCaixa->incluir(null);

      if ( $oDaoPlaCaixa->erro_status == '0' ) {
        throw new Exception($oDaoPlaCaixa->erro_msg);
      }

      $sCodPlanilha = $oDaoPlaCaixa->k80_codpla;
      $oDaoTabRec   = db_utils::getDao("tabplan");

      for ( $iInd=0; $iInd < $iNroRubricas; $iInd++ ) {

      	$oDadosRubricas      = db_utils::fieldsMemory($rsRubricas,$iInd);

        /**
         * Buscamos se a conta credito possui uma conta extra-orçamentaria vinculada
         * caso possua, devemos usar essa conta como conta pagadora apenas se a receita for extra-orçamentaria
         */
      	$sSqlExtra        = $oDaoTabRec->sql_query($oDadosRubricas->receita);
      	$rsReceitaExtra   = $oDaoTabRec->sql_record($sSqlExtra);

      	if ($oDaoTabRec->numrows > 0) {

      	  $oDaoSaltesExtra  = db_utils::getDao("saltesextra");
          $sSqlContaextra   = $oDaoSaltesExtra->sql_query_extra(null,
                                                              "k109_contaextra",
                                                               null,
                                                              "k109_saltes = {$oDadosRubricas->contacredito}");

          $rsContaExtra = $oDaoSaltesExtra->sql_record($sSqlContaextra);

          if ($oDaoSaltesExtra->numrows > 0) {
            $oDadosRubricas->contacredito = db_utils::fieldsmemory($rsContaExtra, 0)->k109_contaextra;
          }
      	}

	      $oDaoPlaCaixaRec->k81_codpla     = $sCodPlanilha;
	      $oDaoPlaCaixaRec->k81_conta      = $oDadosRubricas->contacredito;
	      $oDaoPlaCaixaRec->k81_receita    = $oDadosRubricas->receita;
	      $oDaoPlaCaixaRec->k81_valor      = $oDadosRubricas->valor;
	      $oDaoPlaCaixaRec->k81_codigo     = $oDadosRubricas->recurso;
        if ($iCgm != '') {
          $oDaoPlaCaixaRec->k81_numcgm     = $iCgm;
        } else {
        $oDaoPlaCaixaRec->k81_numcgm     = $oDadosRubricas->numcgm;
        }

	      $oDaoPlaCaixaRec->k81_datareceb  = date('Y-m-d',db_getsession("DB_datausu"));
	      $oDaoPlaCaixaRec->k81_origem     = 1;
	      $oDaoPlaCaixaRec->k81_obs        = '';
        $oDaoPlaCaixaRec->k81_concarpeculiar = $oDadosRubricas->caracteristicapeculiar;
        $oDaoPlaCaixaRec->incluir(null);

        if ($oDaoPlaCaixaRec->erro_status == '0') {
          throw new Exception($oDaoPlaCaixaRec->erro_msg);
        }


	      /**
	       * Inclui vinculo com o tipo de operação, vinculando
	       */
	      if (USE_PCASP) {

	        /**
	         * Verifica existência de registro na tabela slipconcarpeculiar
	         * Caso já exista, o slip já foi gerado
	         */
	        $oDAOSlipconcarpeculiar = db_utils::getDao("slipconcarpeculiar");
	        $sWhere                 = "k131_slip = {$oDadosRubricas->slip}";
	        $sSqlSlipconcarpeculiar = $oDAOSlipconcarpeculiar->sql_query_file(null,"*",null, $sWhere);
	        $rsSlipconcarpeculiar   = $oDAOSlipconcarpeculiar->sql_record($sSqlSlipconcarpeculiar);
	        $lSlipGerado            = false;

	        if ($oDAOSlipconcarpeculiar->numrows > 0) {
	          $lSlipGerado = true;
	        }

	        if (!$lSlipGerado ) {

  	        $aTipo = array(1, 2);

  	        foreach ($aTipo as $iTipo) {

  	          $oDAOSlipconcarpeculiar = db_utils::getDao("slipconcarpeculiar");
  	          $oDAOSlipconcarpeculiar->k131_slip           = $oDadosRubricas->slip;
  	          $oDAOSlipconcarpeculiar->k131_tipo           = $iTipo;
  	          $oDAOSlipconcarpeculiar->k131_concarpeculiar = "000";
  	          $oDAOSlipconcarpeculiar->incluir(null);

  	          if ($oDAOSlipconcarpeculiar->erro_status == '0' ) {
  	            throw new Exception($oDAOSlipconcarpeculiar->erro_msg);
  	          }

  	        }
	        }
	        /**
	         * Adicionado vinculo com tipo de operação
	         * Quando o slip já existe, é feita uma alteração no vinculo
	         */
	        $oDAOVinculo = db_utils::getDao("sliptipooperacaovinculo");
	        $oDAOVinculo->k153_slip             = $oDadosRubricas->slip;
	        $oDAOVinculo->k153_slipoperacaotipo = 13;

	        if ($lSlipGerado) {
	          $oDAOVinculo->alterar($oDadosRubricas->slip);
	        } else {
	          $oDAOVinculo->incluir($oDadosRubricas->slip);
	        }

	        if ($oDAOVinculo->erro_status == '0') {
	          throw new Exception($oDAOVinculo->erro_msg);
	        }

	      }

	      if ( $oDaoPlaCaixaRec->erro_status == '0' ) {
	        throw new Exception($oDaoPlaCaixaRec->erro_msg);
	      }

        $oDaoPlaCaixaRecSlip->k110_slip        = $oDadosRubricas->slip;
        $oDaoPlaCaixaRecSlip->k110_placaixarec = $oDaoPlaCaixaRec->k81_seqpla;
        $oDaoPlaCaixaRecSlip->incluir(null);

        if ( $oDaoPlaCaixaRecSlip->erro_status == '0' ) {
          throw new Exception($oDaoPlaCaixaRecSlip->erro_msg);
        }

      }
    } else {

      $sMensagem  = "Não foi possível buscar os dados. Possíveis causas: \n";
      $sMensagem .= "- Slips já gerados \n";
      $sMensagem .= "- Conta do Recurso não configurada corretamente para o ano\n";
      $sMensagem .= "  Neste caso, configure acessando o menu Cadastros>Contas por Recurso> Inclusão\n";

      throw new Exception($sMensagem);


    }
  	return $sCodPlanilha;
  }

  /**
   * Verifica se a folha de pagamento possui slip liberado.
   *
   * @param FolhaPagamento $oFolha
   * @return Boolean
   */
  public static function isFolhaPagamentoSlipLiberado(FolhaPagamento $oFolha) {

    $sCampo  = "rh83_sequencial";

    $sWhere  = "    rh83_anousu       =  {$oFolha->getCompetencia()->getAno()}    ";
    $sWhere .= "and rh83_mesusu       =  {$oFolha->getCompetencia()->getMes()}    ";
    $sWhere .= "and rh83_siglaarq     = '{$oFolha->getSigla()}'                   ";
    $sWhere .= "and rh83_complementar =  {$oFolha->getNumero()}                   ";
    $sWhere .= "and rh83_instit       =  {$oFolha->getInstituicao()->getCodigo()} ";

    $oDaoSlipLiberado = new cl_rhempenhofolhaconfirma();
    $sSqlSlipLiberado = $oDaoSlipLiberado->sql_slip_liberada($sCampo, $sWhere);
    $rsSlipLiberado   = db_query($sSqlSlipLiberado);

    if (!$rsSlipLiberado) {
      throw new DBException($oDaoSlipLiberado->erro_msg);
    }

    return (boolean) pg_num_rows($rsSlipLiberado);
  }

}
