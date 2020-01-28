<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once modification("libs/db_app.utils.php");

/**
 * inclusão dos models responsáveis pelo processamento das operações
 */
require_once(modification('model/contabilidade/planoconta/ContaPlano.model.php'));

db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$sCaminhoMensagem  = "financeiro.contabilidade.con1_conplanoorcamento.";


	switch ($oParam->exec) {


		/**
		 * Efetuamos a inclusão da conta
		 */
	  case 'incluirConta' :

      try {

				if ($oParam->sTipoConta == "sintetica") {
					$oParam->iContaPcasp = null;
				}

		  	db_inicio_transacao();
		    $oPlano              = new ContaOrcamento($oParam->iCodigoConta, db_getsession("DB_anousu"));
		    $oSistemaConta       = new SistemaConta($oParam->iSistemaContaPlano);
		    $oSubsistemaConta    = new SubSistemaConta($oParam->iSubsistemaConta);
		    $oClassificacaoConta = new ClassificacaoConta($oParam->iClassificacaoContaPlano);
	      $oContaPcasp         = new ContaPlanoPCASP($oParam->iContaPcasp, db_getsession("DB_anousu"));

	      $sDescricao  = db_stdClass::normalizeStringJsonEscapeString($oParam->sDescricaoPlano);
	      $sFinalidade = db_stdClass::normalizeStringJsonEscapeString($oParam->sFinalidadePlano);
		    $oPlano->setEstrutural($oParam->iEstruturalPlano);
		    $oPlano->setDescricao($sDescricao);
		    $oPlano->setFinalidade($sFinalidade);
		    $oPlano->setSistemaConta($oSistemaConta);
		    $oPlano->setSubSistema($oSubsistemaConta);
		    $oPlano->setClassificacaoConta($oClassificacaoConta);
		    $oPlano->setAno(db_getsession('DB_anousu'));
		    $oPlano->setInstituicao(db_getsession('DB_instit'));
		    $oPlano->setIdentificadorFinanceiro($oParam->sIdentificadorFinanceiro);
		    $oPlano->setContraPartida(0);
		    $oPlano->setNaturezaSaldo($oParam->iNaturezaDeSaldo);
		    if ($oContaPcasp != null) {
		      $oPlano->setPlanoContaPCASP($oContaPcasp);
		    }

		    $oPlano->salvar();
		    $oRetorno->iSequencialDaInsercao = $oPlano->getCodigoConta();
		    $oRetorno->sTipoConta            = $oParam->sTipoConta;
		    db_fim_transacao(false);
			} catch (Exception $eException) {

		    db_fim_transacao(true);
			  $oRetorno->status = 2;
			  $oRetorno->message = urlencode(str_replace("\\n", "\n", $eException->getMessage()));
			}
	    break;
	  case "excluirConta":

	    try {

	      db_inicio_transacao();
  	    $oPlano = new ContaOrcamento($oParam->iCodigoConta, db_getsession("DB_anousu"));
  	    $oPlano->excluir();
  	    $oRetorno->message = urlencode("Conta excluída com sucesso.");
  	    db_fim_transacao(false);

	    } catch (Exception $eErro) {

	      $oRetorno->message = urlencode($eErro->getMessage());
	      $oRetorno->status  = 2;
	      db_fim_transacao(true);
	    }



	    break;
	  /**
		 * Buscamos os reduzidos
		 */
		case 'getReduzidos':

			$oPlano                     = new ContaOrcamento($oParam->iCodigoConta, db_getsession("DB_anousu"));
			$aContasReduzidas           = $oPlano->getContasReduzidas();
  		$oRetorno->aContasReduzidas = $aContasReduzidas;
			$oRetorno->aContasReduzidas = utf8_encode_all($oRetorno->aContasReduzidas);
			break;

	  /**
	   * Excluimos reduzido
	   */
		case 'excluirReduzido':

			try {

				db_inicio_transacao();

			  $oPlano = new ContaOrcamento($oParam->iCodigoPlanoConta, db_getsession("DB_anousu"), $oParam->iCodigoReduzido, $oParam->iInstituicao);
			  $oPlano->excluirReduzido();
			  $oRetorno->message = urlencode("Reduzido excluído com sucesso");
			  db_fim_transacao(false);
			} catch (Exception $eException) {

				$oRetorno->status = 2;
				$oRetorno->message = urlencode(str_replace("\\n", "\n", $eException->getMessage()));
			}
			break;

	  /**
	   * Salvamos um reduzido
	   */
		case 'salvarReduzido':

			try {

				db_inicio_transacao();

				$oRetorno->lReduzidoVinculado = false;
				$oPlano = new ContaOrcamento($oParam->iCodigoPlanoConta, db_getsession("DB_anousu"), $oParam->iCodigoReduzido, $oParam->iCodigoInstituicao);

				if (!$oPlano->isContaAnalitica()) {
					throw new Exception("Não é possível incluir um reduzido para contas que possuem filhas.");
				}

				if ($oPlano->getPlanoContaPCASP() == null) {
					throw new Exception("Não é possível incluir reduzido para conta pois não foi informado o Vínculo PCASP na aba Conta.");
				}

				$aContasReduzidas = $oPlano->getContasReduzidas();
				if ($aContasReduzidas && substr($oPlano->getEstrutural(), 0, 1) == 4) {

          $sNomeInstituicao = $aContasReduzidas[0]->nomeinst;
				  $sMensagem  = "Não é possivel cadastrar mais de um reduzido para conta {$oPlano->getEstrutural()} ela ";
          $sMensagem .= "ja possui reduzido na instituição {$sNomeInstituicao}.";
          throw new Exception($sMensagem);
        }

				$oPlano->setInstituicao($oParam->iCodigoInstituicao);
				$oPlano->setRecurso($oParam->iCodigoRecurso);
			  $oPlano->persistirReduzido();

        $aEventoContabil = $oPlano->getEventosContabeisPeloElemento();

			  /**
         * verificarmos se o estrutural do reduzido é filho de algum estrutural cadastrado na tabela contranslrelemento
         * caso seja, perguntamos ao usuario se ele a quer vincular
         */
        if ( !empty($aEventoContabil) ) {

			  	$oRetorno->lReduzidoVinculado = true;
          $oRetorno->iReduzido          = $oPlano->getReduzido();
          $aDocumentosJaAdicionados     = array();

          foreach ($aEventoContabil as $oEventoContabil) {

            if (in_array($oEventoContabil->getCodigoDocumento(), $aDocumentosJaAdicionados)) {
              continue;
            }

            $aDocumentosJaAdicionados[] = $oEventoContabil->getCodigoDocumento();
            $oDadosEventoContabil = new StdClass();
            $oDadosEventoContabil->iDocumento           = $oEventoContabil->getCodigoDocumento();
            $oDadosEventoContabil->sDescricao           = urlencode($oEventoContabil->getDescricaoDocumento());
            $oDadosEventoContabil->iSequencialTransacao = $oEventoContabil->getSequencialTransacao();

            $oRetorno->aEventoContabilVinculado[] = $oDadosEventoContabil;
          }
			  }

				$oRetorno->message = urlencode("Reduzido salvo com sucesso");

				db_fim_transacao(false);

			} catch (Exception $eException) {

				if (db_utils::inTransaction()) {
          db_fim_transacao(false);
        }
        $oRetorno->status = 2;
        $oRetorno->message = urlencode(str_replace("\\n", "\n", $eException->getMessage()));
			}

		break;

		case "vincularReduzido" :

      db_inicio_transacao();

			$iReduzido            = $oParam->iCodigoReduzido;
			$iCodConPcasp         = $oParam->iCodConPcasp;
			$iAnoUso              = db_getsession("DB_anousu");
			$iInstituicao         = db_getsession("DB_instit");
			$oDaoConTransLan      = db_utils::getDao("contranslan");
			$oDaoContrans         = db_utils::getDao("contrans");
			$oPlanoContaPcasp     = new ContaPlanoPCASP($iCodConPcasp, $iAnoUso);
      $iReduzidoPcasp       = $oPlanoContaPcasp->getReduzido();
      $oPlanoContaOrcamento = new ContaOrcamento($oParam->iCodigoContaOrcamento, $iAnoUso);
      $aDocumentos          = array();

      foreach ( $oParam->aDocumentos as $iDocumento ) {

        /*
         * para o documento de origem , devemos ver se ele tem documento inverso.
         * se tiver temos que criar a regra de lançamento
         */
        try {

          $oDocumento        = new EventoContabil($iDocumento, $iAnoUso);
          $oDocumentoInverso = $oDocumento->getEventoInverso();
          $iDocumentoInverso = $oDocumentoInverso->getCodigoDocumento();

          $aDocumentos[$iDocumento]        = $oDocumento;
          $aDocumentos[$iDocumentoInverso] = $oDocumentoInverso;

        /**
         * try criado só para nao disparar a excessao de falta de documento
         * pois se ele nao achar o contrario nesse momento nao fara diferença
         */
        } catch (Exception $eException) { }
      }

      /**
       * Percorre os documentos buscando regra do lancamentos com compara debito ou credito
       * - inclui nova regra definindo conta debito/credito pelo compara
       */
      foreach ($aDocumentos as $iDocumento => $oEventoContabil) {

        foreach ( $oEventoContabil->getEventoContabilLancamento() as $oEventoContabilLancamento ) {

          $aRegrasLancamentos       = $oEventoContabilLancamento->getRegrasLancamento();
          $oRegraLancamentoContabil = clone $aRegrasLancamentos[0];
          $oRegraLancamentoContabil->setAnoUso($iAnoUso);
          $aComparaDebitoCredito = array(RegraLancamentoContabil::COMPARA_DEBITO, RegraLancamentoContabil::COMPARA_CREDITO);

          if ( !in_array($oRegraLancamentoContabil->getCompara(), $aComparaDebitoCredito) ) {
            continue;
          }

          switch ($oRegraLancamentoContabil->getCompara()) {

            case RegraLancamentoContabil::COMPARA_DEBITO :
              $oRegraLancamentoContabil->setContaDebito($iReduzidoPcasp);
            break;

            case RegraLancamentoContabil::COMPARA_CREDITO :
              $oRegraLancamentoContabil->setContaCredito($iReduzidoPcasp);
            break;
          }

          $oRegraLancamentoContabil->salvar();
          $oRegraLancamentoContabil->vincularElemento(db_le_mae_conplano($oPlanoContaOrcamento->getEstrutural()));
        }
      }

      $oRetorno->message = urlencode(_M($sCaminhoMensagem . "reduzidoVinculado"));

  		db_fim_transacao(false);

		break;

		case 'incluirGrupo':

		  try {

		    db_inicio_transacao();
		    $oPlano = new ContaOrcamento($oParam->iCodigoConta, db_getsession("DB_anousu"));
		    // incluímos o grupo
		    $oPlano->addContaGrupo($oParam->iCodigoGrupo);


		    $oRetorno->message   = urlencode("Grupo incluído com sucesso");
		    db_fim_transacao(false);
		  } catch (Exception $eException) {

		    db_fim_transacao(true);
		    $oRetorno->status = 2;
		    $oRetorno->message = urlencode(str_replace("\\n", "\n", $eException->getMessage()));
		  }

		break;

		case "getGrupos":

		  $oPlano                 = new ContaOrcamento($oParam->iCodigoConta, db_getsession("DB_anousu"));
		  $aGrupoContas           = $oPlano->getGruposContas($oParam->iCodigoConta);
		  $oRetorno->aGrupoContas = $aGrupoContas;
		break;

  case "excluirGrupo":

    try {

      db_inicio_transacao();
      $oPlano = new ContaOrcamento($oParam->iCodigoConta, db_getsession("DB_anousu"));
      $oPlano->removeContaGrupo($oParam->iConGrupo);
      $oRetorno->message   = urlencode("Grupo excluído com sucesso");
      db_fim_transacao(false);
    } catch (Exception $eException) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eException->getMessage()));
    }
    break;

  case 'getDadosContaOrcamento':

    $oContaOrcamento                     = new ContaOrcamento($oParam->iCodigoConta, db_getsession("DB_anousu"));
    $oRetorno->dados                     = new stdClass();
    $oRetorno->dados->iCodigoConta       = $oContaOrcamento->getCodigoConta();
    $oRetorno->dados->c90_estrutcontabil = db_formatar($oContaOrcamento->getEstrutural(), "receita");
    $oRetorno->dados->c60_descr          = urldecode($oContaOrcamento->getDescricao());
    if ($oContaOrcamento->getPlanoContaPCASP() != null) {

      $oRetorno->dados->c72_conplano   = $oContaOrcamento->getPlanoContaPCASP()->getCodigoConta();
      $oRetorno->dados->c60_descrPcasp = urldecode($oContaOrcamento->getPlanoContaPCASP()->getDescricao());
    }
    $oRetorno->dados->c60_naturezasaldo = $oContaOrcamento->getNaturezaSaldo();
    $oRetorno->dados->c60_finali        = urlencode($oContaOrcamento->getFinalidade());
    $oRetorno->dados->lReduzido         = false;
    if ($oContaOrcamento->getContasReduzidas()) {
      $oRetorno->dados->lReduzido = true;
    }
    break;
	}


echo $oJson->encode($oRetorno);