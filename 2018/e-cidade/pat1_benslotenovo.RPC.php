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

require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

db_app::import("patrimonio.*");
db_app::import("CgmFactory");
db_app::import("exceptions.*");
db_app::import("empenho.*");
db_app::import("Dotacao");
db_app::import("patrimonio.*");
db_app::import("configuracao.Instituicao");
db_app::import("CgmFactory");

db_app::import("contabilidade.*");
db_app::import('contabilidade.planoconta.ContaPlano');
db_app::import('contabilidade.planoconta.*');
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.contacorrente.*");

$oPlacaBem          = new PlacaBem();
$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->dados    = new stdClass();
$oRetorno->status   = 1;

if (isset($oParam->dbOpcao)) {

  $oRetorno->dbOpcao  = $oParam->dbOpcao;
}

switch ($oParam->exec) {

  case "salvar":

  	try {

  		db_inicio_transacao();

//      BensParametroPlaca::getPlacaDisponivel();exit;

      PlacaBem::bloqueiaManutencao();

  		$lLoteCadastrado = false;
  	  if (!empty($oParam->cod_lote)) {

  	  	$lLoteCadastrado = true;
        $oBemLote        = new BemLote($oParam->cod_lote);
        $aBens           = $oBemLote->getBens();
      } else {

        $oBemLote = new BemLote();
      }

      $oBemClassificacaoCheck  = new BemClassificacao($oParam->t64_codcla);
      $iTipoBem           		 = $oBemClassificacaoCheck->getTipoBem();
      if($iTipoBem == 2){

      	$oParam->cod_notafiscal    = '';
      	$oParam->t53_empen				 = '';
      	$oParam->cod_ordemdecompra = '';
      	$oParam->garantia					 = '';
      	$oParam->empenhosistema    = 'n';

      }else{

      	$oParam->t54_itbql 				 = '';
      	$oParam->observacoesimovel = '';
      }

      $oBemLote->setData(date('Y-m-d', db_getsession("DB_datausu")));
      $oBemLote->setHora(db_hora());
      $oBemLote->setUsuario(db_getsession("DB_id_usuario"));
      $oBemLote->setDescricao(pg_escape_string($oParam->descr_lote));


      if (!$lLoteCadastrado) {

        $iNovaPlaca = $oParam->t41_placa;
        $iPlacaDisponivel = BensParametroPlaca::getPlacaDisponivel(db_getsession('DB_instit'));
        if (!empty($iPlacaDisponivel)) {
          $iNovaPlaca = $iPlacaDisponivel;
        }
        $oParam->t41_placa = $iNovaPlaca;

      	for ($i = 0; $i < $oParam->quant_lote; $i++) {

	        $oBemLote->adicionarBem(setDadosBem($oParam));
       		$oParam->t41_placa += 1;
	      }
      } else {
      	foreach ($aBens as $oBem) {

      	  setDadosBem($oParam, $oBem);
      	}
      }
  		$oBemLote->salvar();

  		db_fim_transacao(false);
  		} catch (Exception $oException) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->mesage = urlencode($oException->getMessage());
    }
  break;
  case 'getDadosLote':

    $oDataAtual   = new DBDate(date("d/m/Y", db_getsession("DB_datausu")));
    $oInstituicao = new Instituicao(db_getsession("DB_instit"));

    $oRetorno->lPossuiIntegracaoPatrimonial = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataAtual, $oInstituicao);

  	if (isset($oParam->iCodigoLote) && $oParam->iCodigoLote != "") {

  		$oBemLote = new BemLote($oParam->iCodigoLote);
  		$aBens    = $oBemLote->getBens();

  		try {

  			$oDadosLote             = new stdClass();
  			$oDadosLote->cod_lote   = $oBemLote->getCodigoLote();
  			$oDadosLote->descr_lote = $oBemLote->getDescricao();
  			$oDadosLote->quant_lote = count($aBens);
  			$oBemLote->validarBensDoLote();
  			if ($oDadosLote->quant_lote > 0) {

  				$oDadosLote->dadosbem = buscaDadosBem($aBens[0]);
  			}
  			$oRetorno->dados            = $oDadosLote;
  			$oRetorno->dados->parametro = BensParametroPlaca::getCodigoParametro();
  		} catch (Exception $eErro) {

  			$oRetorno->status  = 2;
  			$oRetorno->message = urlencode($eErro->getMessage());
  		}
  	}

  	break;
  case "getDadosNota":

    $oDaoGetDadosNota = db_utils::getDao('empnotaitembenspendente');

    $sCampos          = "  e72_qtd, e72_valor, e72_codnota, pc01_descrmater,  e62_vlrun, e69_dtnota, e60_numcgm, z01_nome";
    $sCampos         .= ", e60_numemp, e69_numero, m52_codordem ";
    $sWhere           = " e72_sequencial = {$oParam->iEmpNotaItem}";
    $sSqlDadosNota    = $oDaoGetDadosNota->sql_query_bens(null, $sCampos, null, $sWhere);
    $rsDadosNota      = $oDaoGetDadosNota->sql_record($sSqlDadosNota);

    $oRetorno->dados = db_utils::fieldsMemory($rsDadosNota, 0, true, false, true);
    break;

}

function setDadosBem($oParam, $oBem=null) {

	/**
	 * setamos as informações da placa do item
	 */
	if (empty($oBem)) {

  	$oBem            = new Bem($oParam->t52_bem);
		$oPlacaBem       = new PlacaBem();
		$iParametroPlaca = BensParametroPlaca::getCodigoParametro();
    if ($iParametroPlaca == 4) {
      $oPlacaBem->setPlacaSeq($oParam->t41_placa);
    } else {
      $oPlacaBem->setPlacaSeq($oPlacaBem->getProximaPlaca($oParam->sPlaca));
    }
		$oPlacaBem->setData(date("d/m/Y", db_getsession("DB_datausu")));
		if (!empty($oParam->sPlaca)) {
			$oPlacaBem->setPlaca($oParam->sPlaca);
		}
		if (!empty($oParam->obser)) {
			$oPlacaBem->setObservacao(db_stdClass::normalizeStringJson($oParam->obser));
		}
  	$oBem->setPlaca($oPlacaBem);
	}

	if ($oBem->getPlaca() == "") {
	  throw new Exception("Placa não Informada");
	}
	/**
	 * setamos as informações do item em sí
	 */
	if (strpos($oParam->t52_dtaqu, '/')) {
		$oParam->t52_dtaqu = implode("-", array_reverse(explode("/", $oParam->t52_dtaqu)));
	}
	$oBem->setDataAquisicao($oParam->t52_dtaqu);
	$oBem->setDescricao(db_stdClass::normalizeStringJson($oParam->t52_descr));
	$oBem->setTipoDepreciacao(new BemTipoDepreciacao($oParam->cod_depreciacao));
  $oBem->setClassificacao(new BemClassificacao($oParam->t64_codcla));
  $oBem->setFornecedor(CgmFactory::getInstanceByCgm($oParam->t52_numcgm));
  $oBem->setTipoAquisicao(new BemTipoAquisicao($oParam->t45_sequencial));
  if (!empty($oParam->t04_sequencial)) {
    $oBem->setCedente(new BemCedente($oParam->t04_sequencial));
  }
  if (!empty($oParam->divisao)) {
    $oBem->setDivisao($oParam->divisao);
  }
  $oBem->setSituacaoBem($oParam->t56_situac);
  $oBem->setValorAquisicao(str_replace(",", ".", $oParam->vlAquisicao));
  $oBem->setValorResidual(str_replace(",", ".", $oParam->vlResidual));
  $oBem->setDepartamento($oParam->t52_depart);
  $oBem->setVidaUtil($oParam->vidaUtil);
  $oBem->setMedida($oParam->t67_sequencial);
  $oBem->setModelo($oParam->t66_sequencial);
  $oBem->setMarca($oParam->t65_sequencial);
  $oBem->setValorAtual($oBem->getValorAquisicao());
  $oBem->setValorDepreciavel($oBem->getValorAquisicao() - $oBem->getValorResidual());

  if(!empty($oParam->obser)) {
    $oBem->setObservacao(addslashes(db_stdClass::normalizeStringJson($oParam->obser)));
  }
  $oBem->setInstituicao(db_getsession("DB_instit"));

  if ($oParam->t54_itbql != '') {

  	$oBemDadosImovel = new BemDadosImovel();
    $oBemDadosImovel->setIdBql($oParam->t54_itbql);
    $oBemDadosImovel->setObservacao($oParam->observacoesimovel);
    $oBem->setDadosImovel($oBemDadosImovel);
  }

  if ($oParam->cod_notafiscal != '') {

  	$oBemDadosMaterial = new BemDadosMaterial();
  	$oBemDadosMaterial->setNotaFiscal($oParam->cod_notafiscal);
  	$oBemDadosMaterial->setEmpenhoSistema($oParam->cod_notafiscal=='s'?true:false);
  	$oBemDadosMaterial->setEmpenho($oParam->t53_empen);
  	$oBemDadosMaterial->setOrdemCompra($oParam->cod_ordemdecompra);
  	if (!empty($oParam->garantia)) {
  	  $oBemDadosMaterial->setDataGarantia(implode("-", array_reverse(explode("/", $oParam->garantia))));
  	}
  	$oBem->setDadosCompra($oBemDadosMaterial);
  }
  if (isset($oParam->iCodigoNotaItem) && !empty($oParam->iCodigoNotaItem)) {
    $oBem->setCodigoItemNota($oParam->iCodigoNotaItem);
  }
  $oBem->salvar();
  return $oBem;
}

/**
 *
 * Busca Descrição da Situação
 * @param integer $iCodigoSituacao
 */
function buscaDescricaoSituacao($iCodigoSituacao) {

  $sSituacao    = null;
  $oDaoSituacao = db_utils::getDao("situabens");
  $rsSituacao   = $oDaoSituacao->sql_record($oDaoSituacao->sql_query_file($iCodigoSituacao));

  if ($oDaoSituacao->numrows > 0) {

    $sSituacao = db_utils::fieldsMemory($rsSituacao, 0)->t70_descr;
  }
  return $sSituacao;
}

/**
 *
 * Busca Descrição do Departamento
 * @param integer $iCodigoDepartamento
 */
function buscaDescricaoDepartamento($iCodigoDepartamento) {

  $sDepart    = null;
  $oDaoDepart = db_utils::getDao("db_depart");

  $rsDepart   = $oDaoDepart->sql_record($oDaoDepart->sql_query_file($iCodigoDepartamento));
  if ($oDaoDepart->numrows > 0) {

    $sDepart = db_utils::fieldsMemory($rsDepart, 0)->descrdepto;
  }
  return $sDepart;
}
function buscaDadosBem($oBem) {

  $oDadosBem       = new stdClass();
  $iParametroPlaca = BensParametroPlaca::getCodigoParametro();

  $oDadosBem->t52_bem           = $oBem->getCodigoBem();

  $oDadosBem->sPlaca            = $oBem->getPlaca()->getPlaca();
  $oDadosBem->t41_placa         = $oBem->getPlaca()->getPlacaSeq();

  if ($iParametroPlaca == 4) {
    $oDadosBem->t41_placa  = $oBem->getPlaca()->getNumeroPlaca();
  }

  $oDadosBem->t52_dtaqu         = db_formatar($oBem->getDataAquisicao(), 'd');
  $oDadosBem->t52_descr         = urlencode($oBem->getDescricao());
  $oDadosBem->t64_class         = urlencode($oBem->getClassificacao()->getClassificacao());
  $oDadosBem->t64_codcla        = $oBem->getClassificacao()->getCodigo();
  $oDadosBem->t64_descr         = urlencode($oBem->getClassificacao()->getDescricao());
  $oDadosBem->t52_numcgm        = $oBem->getFornecedor()->getCodigo();
  $oDadosBem->z01_nome          = urlencode($oBem->getFornecedor()->getNome());

  $oTipoAquisicao = $oBem->getTipoAquisicao();

  $oDadosBem->t45_sequencial    = ($oTipoAquisicao ? $oTipoAquisicao->getCodigo() : '');
  $oDadosBem->t45_descricao     = urlencode(($oTipoAquisicao ? $oTipoAquisicao->getDescricao() : ''));
  $oDadosBem->t52_depart        = $oBem->getDepartamento();
  $oDadosBem->descrdepto        = urlencode(buscaDescricaoDepartamento($oDadosBem->t52_depart));
  $oDadosBem->divisao           = $oBem->getDivisao();
  $oDadosBem->t04_sequencial    = '';
  $oDadosBem->z01_nome_convenio = '';
  if ($oBem->getCedente() != '') {

  	$oDadosBem->t04_sequencial    = $oBem->getCedente()->getCodigo();
    $oDadosBem->z01_nome_convenio = urlencode($oBem->getCedente()->getCedente()->getNome());
  }
  $oDadosBem->t56_situac        = $oBem->getSituacaoBem();

  $oDadosBem->t70_descr          = urlencode(buscaDescricaoSituacao($oDadosBem->t56_situac));
  $oDadosBem->vlAquisicao        = $oBem->getValorAquisicao();
  $oDadosBem->vlResidual         = $oBem->getValorResidual();
  $oDadosBem->vlTotal            = $oBem->getValorAtual();
  $oDadosBem->vlTotalDepreciavel = $oBem->getValorDepreciavel();

  $oTipoDepreciacao = $oBem->getTipoDepreciacao();

  $oDadosBem->cod_depreciacao    = ($oTipoDepreciacao ? $oTipoDepreciacao->getCodigo() : '');
  $oDadosBem->descr              = urlencode(($oTipoDepreciacao ? $oTipoDepreciacao->getDescricao() : ''));
  $oDadosBem->vidaUtil           = $oBem->getVidaUtil();
  $oDadosBem->t67_sequencial     = $oBem->getMedida();
  $oDadosBem->t66_sequencial     = $oBem->getModelo();
  $oDadosBem->t65_sequencial     = $oBem->getMarca();
  $oDadosBem->obser              = urlencode($oBem->getObservacao());
  $oDadosBem->iParametroPlaca    = $iParametroPlaca;

  $oDadosBem->t54_itbql         = "";
  $oDadosBem->observacoesimovel = "";
  $oDadosBem->bemComCalculo      = false;
  if ($oBem->getQuantidadeMesesDepreciados() > 0) {
    $oDadosBem->bemComCalculo = true;
  }
  if ($oBem->getDadosImovel() != "") {

  	$oDadosBem->t54_itbql         = $oBem->getDadosImovel()->getIdBql();
    $oDadosBem->observacoesimovel = urlencode($oBem->getDadosImovel()->getObservacao());
  }

  if ($oBem->getDadosCompra() != "") {

  	$oDadosBem->cod_notafiscal    = $oBem->getDadosCompra()->getNotaFiscal();
  	$oDadosBem->emp_sistema       = 'n';
  	if ($oBem->getDadosCompra()->isEmpenhoSistema()) {
  		$oDadosBem->emp_sistema = 's';
  	}
  	$oDadosBem->t53_empen         = $oBem->getDadosCompra()->getEmpenho();
  	$oDadosBem->cod_ordemdecompra = $oBem->getDadosCompra()->getOrdemCompra();
  	$oDadosBem->garantia          = $oBem->getDadosCompra()->getDataGarantia();
  	$oDadosBem->z01_nome_empenho  = urlencode($oBem->getDadosCompra()->getCredor());

  }
  return $oDadosBem;

}
echo $oJson->encode($oRetorno);

?>