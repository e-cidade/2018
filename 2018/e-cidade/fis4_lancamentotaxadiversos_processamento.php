<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBseller Servicos de Informatica
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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

try {

  if (count($_POST) == 0) {
    throw new \BusinessException('Esta rotina é acessada apenas pela postagem dos dados do Lançamento de Taxas.');
  }

  /**
   * Valida a entrada de dados
   */
  $oPost = validarPost(db_utils::postMemory($_POST));

  db_inicio_transacao();

  /**
   * Tenta salvar os dados no banco com os dados informados no POST
   */
  $iCodigoSalvo = salvarDados(new cl_lancamentotaxadiversos(),$oPost);
  $sBloqueia    = empty($oPost->inscricao) ? 'inscricao' : 'cgm';
  $sParametros  = '?codigo='.$iCodigoSalvo.'&mensagem='.urlencode("Taxa salva com sucesso");
  $sParametros .= '&taxa_tem_calculo='.$oPost->taxa_tem_calculo.'&bloqueia=' . $sBloqueia;

  /**
   * Termina a tranação com "não erro" e redireciona para o formulário.
   */
  db_fim_transacao(false);
  db_redireciona('fis4_lancamentotaxadiversos.php' . $sParametros);
} catch (\Exception $e) {

  $aArgumentos             = $_POST;
  $aArgumentos['mensagem'] = urlencode($e->getMessage());
  db_redireciona('fis4_lancamentotaxadiversos.php?' . http_build_query($aArgumentos));
  db_fim_transacao(true);
}

/**
 * Persiste os dados no banco
 *
 * @param Object   $oDao  cl_lancamentotaxadiversos
 * @param StdClass $oPost Dados do Post
 * @return mixed
 * @throws DBException
 */
function salvarDados($oDao, $oPost) {

  $oDao->y120_cgm          = $oPost->cgm;
  $oDao->y120_issbase      = !empty($oPost->inscricao) ? $oPost->inscricao : null;
  $oDao->y120_taxadiversos = $oPost->taxa;
  $oDao->y120_unidade      = pg_escape_string($oPost->unidade);
  $oDao->y120_periodo      = $oPost->periodo;
  $oDao->y120_datainicio   = empty($oPost->data_inicio) ? 'null' : \DBDate::create($oPost->data_inicio)->getDate();
  $oDao->y120_datafim      = empty($oPost->data_fim) ? 'null' : \DBDate::create($oPost->data_fim)->getDate();

  if(empty($oPost->codigo)) {

    $oDao->incluir(null);

  } else {

    $oDao->y120_sequencial = $oPost->codigo;
    $oDao->alterar($oDao->y120_sequencial);
  }

  if ($oDao->erro_status == "0") {
    throw new \DBException("Erro ao efetuar o lançamento da taxa.\n".$oDao->erro_msg);
  }

  return $oDao->y120_sequencial;
}

/**
 * Valida se o conteudo postado é valido
 *
 * @param  StdClass $oPost  Dados postados
 * @return Stdclass         O próprio post.
 */
function validarPost($oPost) {

  $oDadosTaxa = validarTaxaDiversos($oPost->taxa);

  if(!empty($oPost->inscricao) && empty($oPost->cgm)) {
    $oPost->cgm = buscaCGM($oPost->inscricao);
  }

  validarCGM($oPost->cgm);
  validarUnidade($oPost, $oDadosTaxa);
  validarDatas($oPost->data_fim, $oDadosTaxa);
  return $oPost;
}

/**
 * Valida o cgm informado antes do processamento
 *
 * @param  integer $iNumeroCGM
 * @throws BusinessException
 */
function validarCGM($iNumeroCGM) {

  if (!DBNumber::isInteger($iNumeroCGM)) {
    throw new \BusinessException("Somente números inteiros são aceitos como CGM.");
  }

  try {
    CgmFactory::getInstanceByCgm($iNumeroCGM);
  } catch(\Exception $e) {
    throw new \BusinessException("O CGM informado {$iNumeroCGM} não é um CGM cadastrado.");
  }
}

/**
 * Valida se o código da taxa informada é válido
 *
 * @param  Integer $iCodigoTaxa Código da Natureza
 * @return Stdclass Dados referentes a consulta da taxa.
 */
function validarTaxaDiversos($iCodigoTaxa) {

  $oDao = new cl_taxadiversos();
  $sSql = $oDao->sql_query_file($iCodigoTaxa);

  $rsDados = db_query($sSql);

  if (!$rsDados) {
    throw new \DBException("Não foi possível validar a Natureza da Taxa({$iCodigoTaxa}).");
  }

  if (pg_num_rows($rsDados) == 0) {
    throw new \BusinessException("Natureza da Taxa({$iCodigoTaxa}) é inválida.");
  }
  return db_utils::fieldsMemory($rsDados, 0);
}

/**
 * Valida se Unidade de Medida pode ser salva
 *
 * @param  String $sUnidade Unidade de Medida
 * @return void
 */
function validarUnidade($oPost, $oDadosTaxa) {

  if (!trim($oPost->unidade)) {

    if ($oDadosTaxa->y119_unidade != 0) {
      throw new \BusinessException("Unidade de medida não pode ser vazia.");
    }

    $oPost->unidade = 0;
  }
}

/**
 * Valida se as datas estão aptas a serem salvas.
 *
 * @param  String   $sDataInicial
 * @param  String   $sDataFinal
 * @param  StdClass $oDadosTaxa
 * @return void
 */
function validarDatas($sDataFinal, $oDadosTaxa) {

  if ($oDadosTaxa->y119_tipo_calculo == "U") {

    if ($sDataFinal == '') {
      throw new \BusinessException("Data Final não pode ser vazia.");
    }
    $oDataFinal   = new DBDate($sDataFinal);
  }

  return true;
}

/**
 * Quando selecionada uma Inscrição Municipal, busca o CGM vinculado a esta
 * @param  integer $iInscricao
 * @return mixed
 * @throws BusinessException
 * @throws DBException
 */
function buscaCGM($iInscricao) {

  $oDaoIssbase  = new cl_issbase();
  $sSqlIssabase = $oDaoIssbase->sql_query_file($iInscricao, 'q02_numcgm');
  $rsIssbase    = db_query($sSqlIssabase);

  if(!$rsIssbase) {
    throw new \DBException('Erro ao buscar o CGM vinculado a Inscrição Municipal.');
  }

  if(pg_num_rows($rsIssbase) == 0) {
    throw new \BusinessException('Nenhum CGM vinculado a Inscrição Municipal selecionada foi encontrado.');
  }

  return db_utils::fieldsMemory($rsIssbase, 0)->q02_numcgm;
}