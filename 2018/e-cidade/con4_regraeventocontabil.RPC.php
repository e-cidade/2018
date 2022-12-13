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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_app.utils.php");

require_once "model/financeiro/ContaBancaria.model.php";

db_app::import("exceptions.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.planoconta.ContaPlano");
db_app::import("contabilidade.planoconta.*");

$oDaoConplano = db_utils::getDao('conplano');
$oDaoRegraLancamento = db_utils::getDao('contranslr');

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST['json']));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

define("MENSAGEM", "financeiro.contabilidade.con4_regraeventocontabil.");

switch ( $oParam->exec ) {


	case "getTipoAquisicao" :

		$aTipoEvento = array();
		$oDaoBensTipoAquisicao = db_utils::getDao('benstipoaquisicao');
		$sSqlTipoAquisicao     = $oDaoBensTipoAquisicao->sql_query_file(null, "*", 1, null);
		$rsTipoAquisicao       = $oDaoBensTipoAquisicao->sql_record($sSqlTipoAquisicao);
		if ($oDaoBensTipoAquisicao->numrows > 0) {

			$oValores = new stdClass();
			$oValores->t45_sequencial   = ""  ;
			$oValores->t45_descricao  = "Selecione..."  ;
			$aTipoEvento[0] = $oValores;

			if (isset($oParam->iAlteracao) && $oParam->iAlteracao != 'novo') {


				$oDaoAlteracao = db_utils::getDao("benstipoaquisicao");
				$SqlAlteracao  = $oDaoAlteracao->sql_query_file($oParam->iAlteracao);
				$rsAlteracao   = $oDaoAlteracao->sql_record($SqlAlteracao);

				$oAlteracao   = db_utils::fieldsMemory($rsAlteracao, 0);

				$oValores = new stdClass();
				$oValores->t45_sequencial   = $oAlteracao->t45_sequencial;
				$oValores->t45_descricao  = urlencode($oAlteracao->t45_descricao);
				$aTipoEvento[0] = $oValores;

			}

			for ($iIndice = 0; $iIndice < $oDaoBensTipoAquisicao->numrows; $iIndice++) {

				$oDados = db_utils::fieldsMemory($rsTipoAquisicao, $iIndice);
				$oValores = new stdClass();
				$oValores->t45_sequencial   = $oDados->t45_sequencial  ;
				$oValores->t45_descricao  = urlencode($oDados->t45_descricao)  ;
				$aTipoEvento[] = $oValores;
			}
		}
		$oRetorno->aDados = $aTipoEvento;

	break;


	case "getTipoBaixa" :

		$aTipoEvento = array();
		$oDaoBensTipoBaixa = db_utils::getDao('bensmotbaixa');
		$sSqlTipoBaixa     = $oDaoBensTipoBaixa->sql_query_file(null, "*", 1, null);
		$rsTipoBaixa       = $oDaoBensTipoBaixa->sql_record($sSqlTipoBaixa);
		if ($oDaoBensTipoBaixa->numrows > 0) {

			$oValores = new stdClass();
			$oValores->t51_motivo   = ""  ;
			$oValores->t51_descr  = "Selecione..."  ;
			$aTipoEvento[0] = $oValores;

			 if (isset($oParam->iAlteracao) && $oParam->iAlteracao != 'novo') {


			$oDaoAlteracao = db_utils::getDao("bensmotbaixa");
			$SqlAlteracao  = $oDaoAlteracao->sql_query_file($oParam->iAlteracao);
			$rsAlteracao   = $oDaoAlteracao->sql_record($SqlAlteracao);

			$oAlteracao   = db_utils::fieldsMemory($rsAlteracao, 0);

			$oValores = new stdClass();
			$oValores->t51_motivo   = $oAlteracao->t51_motivo;
			$oValores->t51_descr  = urlencode($oAlteracao->t51_descr);
			$aTipoEvento[0] = $oValores;

			}

			for ($iIndice = 0; $iIndice < $oDaoBensTipoBaixa->numrows; $iIndice++) {

				$oDados = db_utils::fieldsMemory($rsTipoBaixa, $iIndice);
				$oValores = new stdClass();
				$oValores->t51_motivo = $oDados->t51_motivo  ;
				$oValores->t51_descr  = urlencode($oDados->t51_descr)  ;
				$aTipoEvento[] = $oValores;
			}
		}
		$oRetorno->aDados = $aTipoEvento;

	break;

	/*
	 * retorna os tipos de evento , quando selecionado a opção "Prestação de Contas "
	 * no combo "Regra de Comparação"
	 * empprestatip
	 * a opção sera gravada em c47_ref
	 */
	case "getTiposEventoEmpenho" :

		$aTipoEvento = array();

		$oDaoEmpPrestaTip = db_utils::getDao("empprestatip");
		$sSqlEmpPrestaTip = $oDaoEmpPrestaTip->sql_query_file(null, "*", 1, null);
		$rsEmpPrestaTip   = $oDaoEmpPrestaTip->sql_record($sSqlEmpPrestaTip);


		if ($oDaoEmpPrestaTip->numrows > 0) {

			$oValores = new stdClass();
			$oValores->e44_tipo   = ""  ;
			$oValores->e44_descr  = "Selecione..."  ;
			$oValores->e44_obriga = "0" ;
			$aTipoEvento[0] = $oValores;


			if (isset($oParam->iAlteracao) && $oParam->iAlteracao != 'novo') {


				$oDaoAlteracao = db_utils::getDao("empprestatip");
				$SqlAlteracao  = $oDaoAlteracao->sql_query_file($oParam->iAlteracao);
				$rsAlteracao   = $oDaoAlteracao->sql_record($SqlAlteracao);

				$oAlteracao   = db_utils::fieldsMemory($rsAlteracao, 0);

				$oValores = new stdClass();
				$oValores->e44_tipo   = $oAlteracao->e44_tipo;
				$oValores->e44_descr  = urlencode($oAlteracao->e44_descr);
				$oValores->e44_obriga = $oAlteracao->e44_obriga;
				$aTipoEvento[0] = $oValores;

			}

			for ($iIndice = 0; $iIndice < $oDaoEmpPrestaTip->numrows; $iIndice++) {

			  $oDados = db_utils::fieldsMemory($rsEmpPrestaTip, $iIndice);
			  $oValores = new stdClass();
			  $oValores->e44_tipo   = $oDados->e44_tipo  ;
			  $oValores->e44_descr  = urlencode($oDados->e44_descr)  ;
			  $oValores->e44_obriga = $oDados->e44_obriga ;
			  $aTipoEvento[] = $oValores;
			}
		}

		$oRetorno->aDados = $aTipoEvento;

	break;

	case "getTipoReconhecimentoContabil" :

    $aTipos = array();
    $sWhere = "";
    $aTiposReconhecimentoContabil = TipoReconhecimentoContabil::buscaDadosTiposDeReconhecimento('c111_sequencial,
    		                                                                                         c111_descricao',
    		                                                                                        $sWhere);

    foreach ($aTiposReconhecimentoContabil as $oTipoReconhecimentoContabil) {

      $oDados = new stdClass();
      $oDados->c111_sequencial = $oTipoReconhecimentoContabil->c111_sequencial;
      $oDados->c111_descricao  = urlencode($oTipoReconhecimentoContabil->c111_descricao);

      $aTipos[] = $oDados;

    }
    $oRetorno->iChaveAlteracao = $oParam->iAlteracao;
		$oRetorno->aDados          = $aTipos;

	break;

	/**
	 * Inclui a transação na contranslr
	 */
	case 'salvarLancamento':

	  db_inicio_transacao();
		try {

			$oRegraLancamentoContabil = new RegraLancamentoContabil();
			$oRegraLancamentoContabil->setSequencialRegra($oParam->c47_seqtranslr);
			$oRegraLancamentoContabil->setSequencialLancamento($oParam->c47_seqtranslan);
			$oRegraLancamentoContabil->setObservacao($oParam->c47_obs);
			$oRegraLancamentoContabil->setAnoUso($oParam->c47_anousu);
			$oRegraLancamentoContabil->setCompara($oParam->c47_compara);
			$oRegraLancamentoContabil->setTipoResto($oParam->c47_tiporesto);
			$oRegraLancamentoContabil->setInstituicao(db_getsession('DB_instit'));
			$oRegraLancamentoContabil->setContaDebito($oParam->c47_debito);
			$oRegraLancamentoContabil->setContaCredito($oParam->c47_credito);
			$oRegraLancamentoContabil->setReferencia($oParam->c47_ref);
			$aCodigosReduzidosCadastrados = array();
			/**
			 * Caso o usuário informe que é necessário fazer a comparação da conta crédito/débito
			 */
			$sTotalContasCadastradas = "";
			$iTotalContasCadastradas = 0;

			/**
       * Elemento informado e tipo de compara 1, 2, 3 ou 4
			 */
			if ( !empty($oParam->sElemento) && in_array($oParam->c47_compara , array(1, 2, 3, 4)) ) {

			  $sCampos = "distinct conplanoreduz.c61_reduz";

			  /**
			   * Quando comparamos com a conta do plano orcamentario,
			   * devemos utilizar o campo conplanoorcamento.c60_codcon como refencia.
			   */
			  if ($oParam->c47_compara == 4) {
			    $sCampos = "distinct conplanoreduz.c61_reduz, conplanoorcamento.c60_codcon as conta_orcamento";
			  }
				$sWhereReduzidos    = "conplanoorcamento.c60_anousu = ".db_getsession("DB_anousu");
				$sWhereReduzidos   .= " and conplanoorcamento.c60_estrut like '{$oParam->sElemento}%'";
				$sWhereReduzidos   .= " and conplanoreduz.c61_instit = ".db_getsession('DB_instit');
				$sSqlBuscaReduzidos = $oDaoConplano->sql_query_pcasp_orcamento_analitico(null,
				                                                                         $sCampos,
				                                                                         null,
				                                                                         $sWhereReduzidos
				                                                                        );

			  $rsBuscaReduzidos   = $oDaoConplano->sql_record($sSqlBuscaReduzidos);

			  if ($oDaoConplano->numrows == 0) {
			  	throw new Exception("Não foi encontrado reduzidos com o estrutural informado.");
			  }

		  	switch ($oParam->c47_compara) {

		  		case RegraLancamentoContabil::COMPARA_DEBITO :

		  			$sNomeCampo  = "c47_debito";
		  			$sNomeMetodo = "setContaDebito";
		  		break;

		  		case RegraLancamentoContabil::COMPARA_CREDITO :

		  			$sNomeCampo  = "c47_credito";
		  			$sNomeMetodo = "setContaCredito";
		  		break;

          /**
           * Elemento
           */
		  		default :

		  			$sNomeCampo  = "c47_ref";
		  			$sNomeMetodo = "setReferencia";
          break;
		  	}

		  	$sNomeCampoOrigem = "c47_debito";
		  	if ($oParam->c47_credito != "0") {
		  	  $sNomeCampoOrigem = "c47_credito";
		  	}

			  for ($iRowReduzido = 0; $iRowReduzido < $oDaoConplano->numrows; $iRowReduzido++) {

			  	$iCodigoReduzido    = db_utils::fieldsMemory($rsBuscaReduzidos, $iRowReduzido)->c61_reduz;
			  	if ($oParam->c47_compara == 4) {
			  	   $iCodigoReduzido  = db_utils::fieldsMemory($rsBuscaReduzidos, $iRowReduzido)->conta_orcamento;
			  	}
			  	$sWhereReduzido     = "    c47_seqtranslan     = {$oParam->c47_seqtranslan} ";
			  	$sWhereReduzido    .= "and {$sNomeCampo}       = {$iCodigoReduzido} ";
			  	$sWhereReduzido    .= "and {$sNomeCampoOrigem} = {$oParam->$sNomeCampoOrigem} ";
			  	$sWhereReduzido    .= "and c47_anousu          = {$oParam->c47_anousu}";
			  	$sSqlBuscaReduzido  = $oDaoRegraLancamento->sql_query(null, "c47_ref", null, $sWhereReduzido);
			  	$rsBuscaReduzido    = $oDaoRegraLancamento->sql_record($sSqlBuscaReduzido);

			  	if ($oDaoRegraLancamento->numrows > 0) {

			  		$aCodigosReduzidosCadastrados[] = $iCodigoReduzido;
			  		continue;
			  	} else {
			  	  $iTotalContasCadastradas++;
			  	}

			  	$oRegraLancamentoContabil->$sNomeMetodo($iCodigoReduzido);
			  	$oRegraLancamentoContabil->salvar();

					$aComparacao = array(
						RegraLancamentoContabil::COMPARA_DEBITO,
						RegraLancamentoContabil::COMPARA_CREDITO
					);
          if ( !empty($oParam->sElemento) && in_array($oParam->c47_compara, $aComparacao) ) {
            $oRegraLancamentoContabil->vincularElemento($oParam->sElemento);
          }

          $oRegraLancamentoContabil->setSequencialRegra('');
			  }

			} else {

				$oRegraLancamentoContabil->salvar();
				if ( !empty($oParam->sElemento) && in_array($oParam->c47_compara, array(RegraLancamentoContabil::COMPARA_CREDITO_ELEMENTO, RegraLancamentoContabil::COMPARA_DEBITO_ELEMENTO))) {

					$oLancamentoOrigem = new EventoContabilLancamento($oParam->c47_seqtranslan);
					$aRegrasCadastradas = $oLancamentoOrigem->getRegrasLancamento();

          foreach ($aRegrasCadastradas as $oRegra) {

						if ($oRegra->getElemento() == str_pad($oParam->sElemento, 15, "0", STR_PAD_RIGHT) && $oParam->c47_anousu == $oRegra->getAnoUso()) {
							throw new Exception("O elemento {$oRegra->getElemento()}, já encontra-se cadastrado para a partida código {$oRegra->getSequencialRegra()}.");
						}
					}
					$oRegraLancamentoContabil->vincularElemento($oParam->sElemento);
				}
			}

      $oLancamentoOrigem = new EventoContabilLancamento($oParam->c47_seqtranslan);
      $oEventoOrigem     = EventoContabil::getInstanciaPorCodigo($oLancamentoOrigem->getSequencialTransacao());
     	$oEventoDestino    = $oEventoOrigem->getEventoInverso();

    	/**
     	 * Varivel para abrotar rotina caso nao encontre lancamentos com mesma ordem entre os eventos de destino e origem
     	 */
     	$lEncontrouLancamentoInverso = false;

     	/**
     	 * Encontrou evento de destino(documento inverso)
     	 * Percorre os lancamentos do evento
     	 * - caso encontre lançamento do evento de destino com mesma ordem do evento de origem, clona as regras
     	 * 	 invertendo as contas
     	 */
     	if ( $oEventoDestino ) {

     		$aLancamentosDestino = $oEventoDestino->getEventoContabilLancamento();

     		foreach( $aLancamentosDestino as $oLancamentoDestino ) {

     			/**
     			 * Busca somente lancamentos com mesma ordem da origem
     			 */
     			if ( $oLancamentoDestino->getOrdem() != $oLancamentoOrigem->getOrdem() ) {
     				continue;
     			}

     			/**
     			 * Encontrou lancamento de destino com mesma ordem do lancamento de origem
     			 */
          $lEncontrouLancamentoInverso = true;

          $aRegraLancamentoDestino = $oLancamentoDestino->getRegrasLancamento();
     			$aRegraLancamentoOrigem  = $oLancamentoOrigem->getRegrasLancamento();

          /**
           * Exclui todas as regras do evento contabil de destino
           */
          foreach ( $aRegraLancamentoDestino as $oRegraLancamentoContabil ) {

            $oRegraLancamentoContabil->excluirVinculoRegra();
            $oRegraLancamentoContabil->excluir();
          }

          /**
           * Percorre os as regras do lancamento de origem e inclui no lancamento de destino
           * - caso encontre vinculo entre as regras origem e destino, exclui regra do evento inverso
           * - cria vinculo entre regra de origem e regra de destino
           */
          foreach ( $aRegraLancamentoOrigem as $oRegraLancamentoOrigem ) {

     			  $oRegraLancamentoDestinoClone = clone $oRegraLancamentoOrigem;

            switch ($oRegraLancamentoOrigem->getCompara()) {

              case RegraLancamentoContabil::COMPARA_DEBITO:
                $oRegraLancamentoDestinoClone->setCompara(RegraLancamentoContabil::COMPARA_CREDITO);
              break;

              case RegraLancamentoContabil::COMPARA_CREDITO:
                $oRegraLancamentoDestinoClone->setCompara(RegraLancamentoContabil::COMPARA_DEBITO);
              break;

							case RegraLancamentoContabil::COMPARA_CREDITO_ELEMENTO:
								$oRegraLancamentoDestinoClone->setCompara(RegraLancamentoContabil::COMPARA_DEBITO_ELEMENTO);
								break;

							case RegraLancamentoContabil::COMPARA_DEBITO_ELEMENTO:
								$oRegraLancamentoDestinoClone->setCompara(RegraLancamentoContabil::COMPARA_CREDITO_ELEMENTO);
								break;
            }

            $oRegraLancamentoDestinoClone->setSequencialLancamento($oLancamentoDestino->getSequencialLancamento());
     				$oRegraLancamentoDestinoClone->setContaCredito($oRegraLancamentoOrigem->getContaDebito());
     				$oRegraLancamentoDestinoClone->setContaDebito($oRegraLancamentoOrigem->getContaCredito());
    				$oRegraLancamentoDestinoClone->salvar();
            $oRegraLancamentoDestinoClone->vincularElemento($oRegraLancamentoOrigem->getElemento());
     				$oRegraLancamentoDestinoClone->salvarVinculoEventoInverso($oRegraLancamentoOrigem->getSequencialRegra(),
     																										              $oEventoDestino->isEventoInclusao());
     			}
     		}
     	}

     	/**
     	 * Encontrou evento inverso mas nao encontrou lancamento com mesma ordem
     	 */
     	if ( !$lEncontrouLancamentoInverso && $oEventoDestino ) {

				$oErroMensagem = new StdClass();
     		$oErroMensagem->iOrdemOrigem = $oLancamentoOrigem->getOrdem();
     		$oErroMensagem->sDocumento   = $oEventoDestino->getCodigoDocumento() . ' - ' . $oEventoDestino->getDescricaoDocumento();
     		throw new BusinessException(_M(MENSAGEM . 'ordem_lancamento_destino_nao_encontrado', $oErroMensagem));
     	}

			if ($iTotalContasCadastradas > 0) {
			  $sTotalContasCadastradas = "\n\nTotal de Contas Cadastradas: {$iTotalContasCadastradas}.";
			}

			$oRetorno->message = urlencode("Regra de lançamento contábil salva com sucesso.{$sTotalContasCadastradas}");
			db_fim_transacao(false);

		} catch (Exception $eException) {

			$oRetorno->status  = 2;
			$oRetorno->message = urlencode($eException->getMessage());
			db_fim_transacao(true);
		}
	break;

	case 'getRegrasLancamentoContabil':

		try {

			$oEventoContabilLancamento = new EventoContabilLancamento($oParam->iCodigoLancamento);
			$aRegraLancamentos         = $oEventoContabilLancamento->getRegrasLancamento();
			$aRetornoRegra             = array();

			if (count($aRegraLancamentos) > 0) {

				foreach ($aRegraLancamentos as $oRegra) {

				  $oContaDebito  = new ContaPlanoPCASP(null, db_getsession('DB_anousu'), $oRegra->getContaDebito());
				  $oContaCredito = new ContaPlanoPCASP(null, db_getsession('DB_anousu'), $oRegra->getContaCredito());

					$oStdClass                     = new stdClass();
					$oStdClass->c47_debito         = $oRegra->getContaDebito();
					$oStdClass->estrutural_debito  = "";
					$oStdClass->descricao_debito   = "";
					if (!empty($oStdClass->c47_debito)) {

					  $oStdClass->estrutural_debito  = $oContaDebito->getEstrutural();
					  $oStdClass->descricao_debito   = urlencode($oContaDebito->getDescricao());
					}
					$oStdClass->c47_credito        = $oRegra->getContaCredito();
					$oStdClass->estrutural_credito = "";
					$oStdClass->descricao_credito  = "";
					if (!empty($oStdClass->c47_credito)) {

					  $oStdClass->estrutural_credito = $oContaCredito->getEstrutural();
					  $oStdClass->descricao_credito  = urlencode($oContaCredito->getDescricao());
					}

				  $oStdClass->c47_anousu         = $oRegra->getAnoUso();
				  $oStdClass->c47_ref            = $oRegra->getReferencia();
				  $oStdClass->c47_compara        = $oRegra->getCompara();
				  $oStdClass->c47_tiporesto      = $oRegra->getTipoResto();
				  $oStdClass->c47_seqtranslr     = $oRegra->getSequencialRegra();
          $oStdClass->sElemento          = $oRegra->getElemento();
				  $aRetornoRegra[]               = $oStdClass;
				}
			}

			$oRetorno->aRegrasLancamento = $aRetornoRegra;

		} catch (Exception $oErro) {

			$oRetorno->message = urlencode($oErro->getMessage());
			$oRetorno->status = 2;
		}

	break;

	case 'excluirRegraLancamentoContabil':

		db_inicio_transacao();

		try {

      /**
        * Para cada regra do array que vem da requisição, setamos o sequencial da regra no objeto e mandamos excluir o mesmo
        */
      foreach($oParam->aRegras as $iRegra) {

        $oRegraLancamentoContabil = new RegraLancamentoContabil($iRegra);
        $oRegraLancamentoContabil->excluirRegraEventoInverso();
        $oRegraLancamentoContabil->excluir();
      }

      $oRetorno->message 				= urlencode("Regras de lançamento excluídas com sucesso");
			db_fim_transacao(false);

		} catch (Exception $eException) {
			$oRetorno->message = urlencode($eException->getMessage());
			$oRetorno->status = 2;
			db_fim_transacao(true);
		}
		break;

	case 'getRegraEventoContabil':

		$oRegraLancamentoContabil = new RegraLancamentoContabil($oParam->iCodigoRegra);
		$oRetorno->c47_anousu     = $oRegraLancamentoContabil->getAnoUso();
		$oRetorno->c47_debito     = $oRegraLancamentoContabil->getContaDebito();
		$oRetorno->c47_credito    = $oRegraLancamentoContabil->getContaCredito();
		$oRetorno->c47_compara    = $oRegraLancamentoContabil->getCompara();
		$oRetorno->c47_tiporesto  = $oRegraLancamentoContabil->getTipoResto();
		$oRetorno->c47_ref        = $oRegraLancamentoContabil->getReferencia();
		$oRetorno->c47_obs        = $oRegraLancamentoContabil->getObservacao();
		$oRetorno->c47_seqtranslr = $oParam->iCodigoRegra;

		break;

	case 'categoriaContrato':

	  $oDaoAcordoCategoria = new cl_acordocategoria();
	  $sSqlAcordoCategoria = $oDaoAcordoCategoria->sql_query_file(null, "*", "1");
	  $rsAcordoCategoria   = $oDaoAcordoCategoria->sql_record($sSqlAcordoCategoria);

	  $oRetorno->aCategoriaContrato = db_utils::getCollectionByRecord($rsAcordoCategoria, false, false, true);
	  break;

}
echo $oJson->encode($oRetorno);