<?php

/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_libdicionario.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clrhpessoalmov    = new cl_rhpessoalmov;
$clrhtipoapos      = new cl_rhtipoapos;
$clrhpesrescisao   = new cl_rhpesrescisao;
$clrescisao        = new cl_rescisao;
$clrhpesbanco      = new cl_rhpesbanco;
$clrhpespadrao     = new cl_rhpespadrao;
$clrhpessoal       = new cl_rhpessoal;
$clinssirf         = new cl_inssirf;
$clrhpescargo      = new cl_rhpescargo;
$clrhregime        = new cl_rhregime;
$clrhpesorigem     = new cl_rhpesorigem;
$clrhpeslocaltrab  = new cl_rhpeslocaltrab;
$clrhlocaltrab     = new cl_rhlocaltrab;
$clpontofs         = new cl_pontofs;
$clpontofx         = new cl_pontofx;
$clrhpesprop       = new cl_rhpesprop;
$clrhfuncao        = new cl_rhfuncao;
$clpensao          = new cl_pensao;
$cltpcontra        = new cl_tpcontra;
$cltipodeficiencia = new cl_tipodeficiencia;
$oDaoPontoCom      = new cl_pontocom();

$db_opcao  = 22;
$db_botao  = false;
$sDisabled = "";

/**
 * Realiza validação para o tipo de reajuste
 * para Aposentados e pensionistas.
 */

$lErro   = false;
$sqlerro = false;

if (isset($incluir) && !$lErro) {
  db_inicio_transacao();

  $sqlerro = false;

  $clrhpessoalmov->rh02_diasgozoferias = $rh02_diasgozoferias;
  $clrhpessoalmov->rh02_funcao         = $rh02_funcao;
  $clrhpessoalmov->rh02_instit         = db_getsession("DB_instit");
  $clrhpessoalmov->rh02_equip          = "false";
  $_POST["rh02_cnpjcedencia"]          = str_replace(array(".","_", "-", "/"), "", $_POST["rh02_cnpjcedencia"]);

  if (!empty($rh02_datacedencia)) {

    $oDataCedencia = new DBDate($rh02_datacedencia);
    $_POST["rh02_datacedencia"] = $oDataCedencia->getDate();
  }

  if($rh02_diasgozoferias >= 30) {

    $clrhpessoalmov->incluir(null,db_getsession("DB_instit"));
    $rh02_seqpes    = $clrhpessoalmov->rh02_seqpes;
    $erro_msg       = $clrhpessoalmov->erro_msg;

    if($clrhpessoalmov->erro_status==0){
      $sqlerro=true;
    }

  } else {
    $sqlerro  = true;
    $erro_msg = 'Informe no mínimo 30 dias de férias padrão para o funcionário.';
  }

  if ($sqlerro == false) {

    $clrhpessoal->rh01_funcao = $rh02_funcao;
    $clrhpessoal->rh01_regist = $oPost->rh02_regist;
    $clrhpessoal->alterar($oPost->rh02_regist);
    if ($clrhpessoal->erro_status == 0) {
      $sqlerro = true;
    }
  }

  if($sqlerro == false){
    if(trim($rh21_regpri)!=""){
      $clrhpesorigem->incluir($rh02_regist);
      if($clrhpesorigem->erro_status==0){
        $erro_msg = $clrhpesorigem->erro_msg;
        $sqlerro=true;
      }
    }
  }

  if($sqlerro == false){

    if(trim($rh05_recis_dia)!="" && trim($rh05_recis_mes)!="" && trim($rh05_recis_ano)!=""){
      $clrhpesrescisao->rh05_seqpes = $rh02_seqpes;
      $clrhpesrescisao->incluir($rh02_regist);

      if($clrhpesrescisao->erro_status==0){
        $erro_msg = $clrhpesrescisao->erro_msg;
        $sqlerro=true;
      } else {

        $clpontofs->excluir(db_anofolha(),db_mesfolha(),$rh02_regist,null);

        if($clpontofs->erro_status==0){
          $erro_msg = $clpontofs->erro_msg;
          $sqlerro=true;
        }else{

          $clpontofx->excluir(db_anofolha(),db_mesfolha(),$rh02_regist,null);

          if($clpontofx->erro_status==0){
            $erro_msg = $clpontofx->erro_msg;
            $sqlerro=true;
          }
        }
      }
    }

  }

  if($sqlerro == false){
    if(trim($rh03_padrao) != ""){
      $clrhpespadrao->rh03_anousu     = $rh02_anousu;
      $clrhpespadrao->rh03_mesusu     = $rh02_mesusu;
      $clrhpespadrao->rh03_padrao     = $rh03_padrao;
      $clrhpespadrao->rh03_padraoprev = $rh03_padraoprev;
      $clrhpespadrao->rh03_regime     = $rh30_regime;
      $clrhpespadrao->incluir($rh02_seqpes);
      if($clrhpespadrao->erro_status==0){
        $erro_msg = $clrhpespadrao->erro_msg;
        $sqlerro=true;
      }
    }
  }

  if($sqlerro == false){
    if(trim($rh20_cargo) != ""){
      $clrhpescargo->rh20_instit = db_getsession("DB_instit");
      $clrhpescargo->rh20_cargo = $rh20_cargo;
      $clrhpescargo->incluir($rh02_seqpes);
      if($clrhpescargo->erro_status==0){
        $erro_msg = $clrhpescargo->erro_msg;
        $sqlerro=true;
      }
    }
  }

  if($sqlerro == false){
    if(trim($rh19_propi) != ""){
      $clrhpesprop->rh19_propi = $rh19_propi;
      $clrhpesprop->incluir($rh02_regist);
      if($clrhpesprop->erro_status==0){
        $erro_msg = $clrhpesprop->erro_msg;
        $sqlerro=true;
      }
    }
  }

  if($sqlerro == false){

    $oServidor      = ServidorRepository::getInstanciaByCodigo($rh02_regist, $rh02_anousu, $rh02_mesusu);

    if(trim($inputCodigoBanco) != ""){
      try {
        $oContaBancaria = $oServidor->getContaBancaria();
        if ( $inputSequencialConta != "" ) {
          $oContaBancaria->setSequencialContaBancaria($inputSequencialConta);
        }
        $oContaBancaria->setCodigoBanco($inputCodigoBanco);
        $oContaBancaria->setNumeroAgencia($inputNumeroAgencia);
        $oContaBancaria->setDVAgencia($inputDvAgencia);
        $oContaBancaria->setNumeroConta($inputNumeroConta);
        $oContaBancaria->setDVConta($inputDvConta);
        $oContaBancaria->setIdentificador('0');
        $oContaBancaria->setCodigoOperacao($inputOperacao);
        $oContaBancaria->setTipoConta($cboTipoConta);
        $oContaBancaria->salvar();

      } catch ( Exception $oException ) {

        $erro_msg = "Erro ao Cadastrar dados bancários do Servidor";
        $sqlerro  = true;
      }

      $oServidor->setContaBancaria($oContaBancaria);
    }

    $oRetorno  = ServidorRepository::persistServidor($oServidor);


    if($oRetorno->erro_status == 0){
      $erro_msg = $oRetorno->erro_msg;
      $sqlerro  = true;
    }
  }

  // incluir rubricas cargo/funcao
  if ($sqlerro == false) {

    try {

      alterarCargoFuncao(
        $rh20_cargo, $rh02_funcao, $rh02_regist, DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()
      );

    } catch(Exception $oErro) {
      $sqlerro = true;
      $erro_msg = $oErro->getMessage();
    }
  }

  db_fim_transacao($sqlerro);

} else if(isset($alterar) && !$lErro) {

  db_inicio_transacao();

  $_POST["rh02_cnpjcedencia"] = str_replace(array(".","_", "-", "/"), "", $rh02_cnpjcedencia);

  if (!empty($rh02_datacedencia)) {

    $oDataCedencia = new DBDate($rh02_datacedencia);
    $_POST["rh02_datacedencia"] = $oDataCedencia->getDate();
  }

  $sqlerro = false;

  // alterar rubricas cargo/funcao
  try {

    $lAlteracaoCargoFuncao = alterarCargoFuncao(
      $rh20_cargo, $rh02_funcao, $rh02_regist, DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()
    );

  } catch(Exception $oErro) {
    $sqlerro = true;
    $erro_msg = $oErro->getMessage();
  }

  if (!$sqlerro) {

    $clrhpessoalmov->rh02_diasgozoferias = $rh02_diasgozoferias;
    $clrhpessoalmov->rh02_instit         = db_getsession('DB_instit');
    $clrhpessoalmov->rh02_cnpjcedencia   = str_replace(array(".","_", "-", "/"), "", $_POST["rh02_cnpjcedencia"]);

    if($rh02_diasgozoferias >= 30) {

      $oRetorno = ServidorRepository::persistServidor(ServidorRepository::getInstanciaByCodigo($rh02_regist,
                                                                                      DBPessoal::getAnoFolha(),
                                                                                      DBPessoal::getMesFolha()));

      $clrhpessoalmov->erro_status = $oRetorno->erro_status;
      $clrhpessoalmov->erro_msg    = $oRetorno->erro_msg;
      $erro_msg = $clrhpessoalmov->erro_msg;
      $clrhpessoalmov->alterar($rh02_seqpes, db_getsession('DB_instit'));
      if($clrhpessoalmov->erro_status==0) {
        $sqlerro=true;
      }

    } else {
      $sqlerro  = true;
      $erro_msg = 'Informe no mínimo 30 dias de férias padrão para o funcionário.';
    }
  }

  if ($sqlerro == false) {

    $clrhpessoal->rh01_funcao = $rh02_funcao;
    $clrhpessoal->rh01_regist = $oPost->rh02_regist;
    $clrhpessoal->alterar($oPost->rh02_regist);
    if ($clrhpessoal->erro_status == 0) {
      $sqlerro = true;
    }
  }

  if($sqlerro == false){
    if(trim($rh21_regpri)!=""){
      $result_origem = $clrhpesorigem->sql_record($clrhpesorigem->sql_query_file($rh02_regist));
      if($clrhpesorigem->numrows > 0){
        $clrhpesorigem->rh21_regist = $rh02_regist;
        $clrhpesorigem->rh21_regpri = $rh21_regpri;
        $clrhpesorigem->alterar($rh02_regist);
      }else{
        $clrhpesorigem->incluir($rh02_regist);
      }
    }else{
      $clrhpesorigem->excluir($rh02_regist);
    }
    if($clrhpesorigem->erro_status==0){
      $erro_msg = $clrhpesorigem->erro_msg;
      $sqlerro=true;
    }
  }

  if($sqlerro == false){
    $clrhpescargo->excluir($rh02_seqpes);
    if($clrhpescargo->erro_status==0){
      $erro_msg = $clrhpescargo->erro_msg;
      $sqlerro=true;
    }
  }

  if($sqlerro == false){
    if(trim($rh20_cargo) != ""){
      $clrhpescargo->rh20_instit = db_getsession('DB_instit');
      $clrhpescargo->rh20_cargo = $rh20_cargo;
      $clrhpescargo->incluir($rh02_seqpes);
      if($clrhpescargo->erro_status==0){
        $erro_msg = $clrhpescargo->erro_msg;
        $sqlerro=true;
      }
    }
  }

  if($sqlerro==false){

    if(trim($inputCodigoBanco) != ""){

      try {

        $oServidor      = ServidorRepository::getInstanciaByCodigo($rh02_regist, $rh02_anousu, $rh02_mesusu);
        $oContaBancaria = $oServidor->getContaBancaria();

        if ( $inputSequencialConta != "" ) {
          $oContaBancaria->setSequencialContaBancaria($inputSequencialConta);
        }


        $oContaBancaria->setCodigoBanco($inputCodigoBanco);
        $oContaBancaria->setNumeroAgencia($inputNumeroAgencia);
        $oContaBancaria->setDVAgencia($inputDvAgencia);
        $oContaBancaria->setNumeroConta($inputNumeroConta);
        $oContaBancaria->setDVConta($inputDvConta);
        $oContaBancaria->setIdentificador('0');
        $oContaBancaria->setCodigoOperacao($inputOperacao);
        $oContaBancaria->setTipoConta($cboTipoConta);

        $oServidor->setContaBancaria($oContaBancaria);
        $oServidor->salvar();

      } catch ( Exception $oException ) {

        $erro_msg = "Erro ao Cadastrar dados bancários do Servidor" . $oException->getMessage();
        $sqlerro  = true;
      }
    }

    if (trim($inputCodigoBanco) == "") {

        $oDaoRhPessoalMovContaBancaria = db_utils::getDao('rhpessoalmovcontabancaria');
        $sSqlRhPessoalMovContaBancaria = $oDaoRhPessoalMovContaBancaria->sql_query(null, 'rh138_sequencial', null, "rh02_regist = {$rh02_regist}");
        $rsRhPessoalMovContaBancaria   = db_query($sSqlRhPessoalMovContaBancaria);

        if (pg_num_rows($rsRhPessoalMovContaBancaria) > 0){

          $oRhPessoalMovContaBancaria = db_utils::fieldsMemory($rsRhPessoalMovContaBancaria,0);
          $oDaoRhPessoalMovContaBancaria->excluir($oRhPessoalMovContaBancaria->rh138_sequencial);
          $db83_sequencial     = null;
          $db83_tipoconta      = null;
          $db83_codigooperacao = null;
        }


    }
  }
  $excluiponto = false;
  if($sqlerro == false){
    if(trim($rh05_recis_dia)!="" && trim($rh05_recis_mes)!="" && trim($rh05_recis_ano)!=""){

      $sCamposPensao = "distinct(r52_regist+r52_numcgm), r52_regist, r52_numcgm";
      $sWherePensao  = " r52_anousu = " . db_anofolha() . " and r52_mesusu = " . db_mesfolha();
      $sWherePensao .= " and rh05_recis is null and r52_regist = {$rh02_regist}";

      $sSqlPensao = $clpensao->sql_query_pensao_rescisao(null, null, null, null, $sCamposPensao, "r52_regist", $sWherePensao);
      $rsPensao   = $clpensao->sql_record( $sSqlPensao );

      if ($clpensao->numrows > 0) {
        $aPensoes = db_utils::getCollectionByRecord($rsPensao);

        foreach ($aPensoes as $oPensao) {

          $clpensao->r52_anousu = db_anofolha();
          $clpensao->r52_mesusu = db_mesfolha();
          $clpensao->r52_regist = $rh02_regist;
          $clpensao->r52_numcgm = $oPensao->r52_numcgm;
          $clpensao->r52_valor  = '0';
          $clpensao->r52_valcom = '0';
          $clpensao->r52_val13  = '0';
          $clpensao->r52_valfer = '0';

          $clpensao->alterar(db_anofolha(), db_mesfolha(), $rh02_regist, $oPensao->r52_numcgm);

          if ($clpensao->erro_status == 0) {
            $erro_msg = $clpensao->erro_msg;
            $sqlerro  = true;
          }
        }
      }

      $excluiponto = true;
      $result_rescisao = $clrhpesrescisao->sql_record($clrhpesrescisao->sql_query_file($rh02_seqpes));

      if($clrhpesrescisao->numrows > 0){
        $clrhpesrescisao->rh05_seqpes = $rh02_seqpes;
        $clrhpesrescisao->alterar($rh02_seqpes);
      }else{
        $clrhpesrescisao->rh05_seqpes = $rh02_seqpes;
        $clrhpesrescisao->incluir($rh02_seqpes);
      }
    }else{
      $clrhpesrescisao->excluir($rh02_seqpes);
    }

    if($clrhpesrescisao->erro_status==0){
      $erro_msg = $clrhpesrescisao->erro_msg;
      $sqlerro=true;
    } else if($excluiponto == true){

      $clpontofs->excluir(db_anofolha(),db_mesfolha(),$rh02_regist,null);
      if($clpontofs->erro_status==0){
        $erro_msg = $clpontofs->erro_msg;
        $sqlerro=true;
      }else{

        $clpontofx->excluir(db_anofolha(),db_mesfolha(),$rh02_regist,null);
        if($clpontofx->erro_status==0){
          $erro_msg = $clpontofx->erro_msg;
          $sqlerro=true;
        }
      }

      /**
       * Caso a folha complementar e suplementar estiverem aberta,
       * os eventos financeiros do ponto e do histórico ponto serão excluídos.
       */
      if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

        $oCompetencia        = DBPessoal::getCompetenciaFolha();
        $oServidor           = ServidorRepository::getInstanciaByCodigo($rh02_regist, $oCompetencia->getAno(), $oCompetencia->getMes());
        $oFolhaComplementar = FolhaPagamentoComplementar::getUltimaFolha();
        $oFolhaSuplementar  = FolhaPagamentoSuplementar::getUltimaFolha();

        /**
         * Tratamento da folha complementar.
         */
        if ($oFolhaComplementar->getSequencial() && $oFolhaComplementar->isAberto()) {

          /**
           * Excluí os eventos financeiros do histórico ponto.
           */
          $aEventosFinanceiros = $oFolhaComplementar->getHistoricoRegistrosPonto($oServidor);
          foreach ($aEventosFinanceiros as $oEventoFinanceiro) {
            $oFolhaComplementar->excluirRubricaHistoricoPonto($oEventoFinanceiro->getServidor()->getMatricula(),
                                                              $oEventoFinanceiro->getRubrica()->getCodigo());
          }

          /**
           * Excluí os eventos financeiros do ponto.
           */
          $oPonto = $oServidor->getPonto($oFolhaComplementar->getTabelaPonto());
          $oPonto->limpar();
        }

        /**
         * Tratamento da folha suplementar
         */
        if ($oFolhaSuplementar->getSequencial() && $oFolhaSuplementar->isAberto()) {

          /**
           * Excluí os eventos financeiros do histórico ponto.
           */
          $aEventosFinanceiros = $oFolhaSuplementar->getHistoricoRegistrosPonto($oServidor);

          foreach ($aEventosFinanceiros as $oEventoFinanceiro) {
            $oFolhaSuplementar->excluirRubricaHistoricoPonto($oEventoFinanceiro->getServidor()->getMatricula(),
                                                             $oEventoFinanceiro->getRubrica()->getCodigo());
          }

          /**
           * Excluí os eventos financeiros do ponto.
           */
          $oPonto = $oServidor->getPonto($oFolhaSuplementar->getTabelaPonto());
          $oPonto->limpar();
        }

      }

    }
  }

  if($sqlerro == false){
    if(trim($rh03_padrao) != ""){
      $result_testa = $clrhpespadrao->sql_record($clrhpespadrao->sql_query_file($rh02_seqpes));
      if($clrhpespadrao->numrows == 0){
        $clrhpespadrao->rh03_anousu     = $rh02_anousu;
        $clrhpespadrao->rh03_mesusu     = $rh02_mesusu;
        $clrhpespadrao->rh03_padrao     = $rh03_padrao;
        $clrhpespadrao->rh03_padraoprev = $rh03_padraoprev;
        $clrhpespadrao->rh03_regime     = $rh30_regime;
        $clrhpespadrao->incluir($rh02_seqpes);
      }else{
        $clrhpespadrao->rh03_seqpes     = $rh02_seqpes;
        $clrhpespadrao->rh03_anousu     = $rh02_anousu;
        $clrhpespadrao->rh03_mesusu     = $rh02_mesusu;
        $clrhpespadrao->rh03_padrao     = $rh03_padrao;
        $clrhpespadrao->rh03_padraoprev = $rh03_padraoprev;
        $clrhpespadrao->rh03_regime     = $rh30_regime;
        $clrhpespadrao->alterar($rh02_seqpes);
      }
    }else{
      $clrhpespadrao->excluir($rh02_seqpes);
    }
    if($clrhpespadrao->erro_status==0){
      $erro_msg = $clrhpespadrao->erro_msg;
      $sqlerro=true;
    }
  }

  if($sqlerro == false){
    if(trim($rh19_propi) != ""){
      $result_propi = $clrhpesprop->sql_record($clrhpesprop->sql_query_file($rh02_regist));
      $clrhpesprop->rh19_regist = $rh02_regist;
      $clrhpesprop->rh19_propi = $rh19_propi;
      if($clrhpesprop->numrows > 0){
        $clrhpesprop->alterar($rh02_regist);
      }else{
        $clrhpesprop->incluir($rh02_regist);
      }
    }else{
      $clrhpesprop->rh19_propi = $rh19_propi;
      $clrhpesprop->excluir($rh02_regist);
    }
    if($clrhpesprop->erro_status==0){
      $erro_msg = $clrhpesprop->erro_msg;
      $sqlerro=true;
    }
  }

  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();

    $clrhpescargo->excluir($rh02_seqpes);
    if($clrhpescargo->erro_status==0){
      $erro_msg = $clrhpescargo->erro_msg;
      $sqlerro=true;
    }

    if($sqlerro==false){
      $clrhpeslocaltrab->excluir($rh02_seqpes);
      if($clrhpeslocaltrab->erro_status==0){
        $erro_msg = $clrhpeslocaltrab->erro_msg;
        $sqlerro=true;
      }
    }

    if($sqlerro==false){
      $clrhpespadrao->excluir(null,"rh56_seqpes = ".$rh02_seqpes);
      if($clrhpespadrao->erro_status==0){
        $erro_msg = $clrhpespadrao->erro_msg;
        $sqlerro=true;
      }
    }

    if($sqlerro==false){
      $clrhpesbanco->excluir($rh02_seqpes);
      if($clrhpesbanco->erro_status==0){
        $erro_msg = $clrhpesbanco->erro_msg;
        $sqlerro=true;
      }
    }

    if($sqlerro==false){
      $clrhpesrescisao->excluir($rh02_seqpes);
      if($clrhpesrescisao->erro_status==0){
        $erro_msg = $clrhpesrescisao->erro_msg;
        $sqlerro=true;
      }
    }

    if($sqlerro==false){
      $clrhpesorigem->excluir($rh02_regist);
      if($clrhpesorigem->erro_status==0){
        $erro_msg = $clrhpesorigem->erro_msg;
        $sqlerro=true;
      }
    }

    if($sqlerro==false){
      $clrhpesprop->excluir($rh02_regist);
      if($clrhpesprop->erro_status==0){
        $erro_msg = $clrhpesprop->erro_msg;
        $sqlerro=true;
      }
    }

    if($sqlerro==false){
      $clrhpessoalmov->excluir($rh02_seqpes);
      $erro_msg = $clrhpessoalmov->erro_msg;
      if($clrhpessoalmov->erro_status==0){
        $sqlerro=true;
      }
    }
    db_fim_transacao($sqlerro);
  }
}

$rh02_anousu = db_anofolha();
$rh02_mesusu  = db_mesfolha();
$limparrecis = false;
$limparbanco = false;
if(isset($rh02_regist)){
  $instit = db_getsession("DB_instit");
  $result = $clrhpessoalmov->sql_record($clrhpessoalmov->sql_query(null,null,"*","","rh02_regist=$rh02_regist and rh02_anousu=$rh02_anousu and rh02_mesusu=$rh02_mesusu and rh02_instit = $instit "));
  if($clrhpessoalmov->numrows>0){
    db_fieldsmemory($result,0);
    $opcao = "alterar";
    $result_banco = $clrhpesbanco->sql_record($clrhpesbanco->sql_query($rh02_seqpes));
    if($clrhpesbanco->numrows>0){
      db_fieldsmemory($result_banco,0);
    }else{
      $limparbanco = true;
    }
    if($rh30_vinculo == "P"){
      $result_rhpesorigem = $clrhpesorigem->sql_record($clrhpesorigem->sql_query_file($rh02_regist));
      if($clrhpesorigem->numrows > 0){
        db_fieldsmemory($result_rhpesorigem,0);
        $result_nomeorigem = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($rh21_regpri,"z01_nome as z01_nomeorigem"));
        if($clrhpessoal->numrows > 0){
          db_fieldsmemory($result_nomeorigem, 0);
        }
      }
    }
    $result_rescisao = $clrhpesrescisao->sql_record($clrhpesrescisao->sql_query_file($rh02_seqpes));
    if($clrhpesrescisao->numrows > 0){
      db_fieldsmemory($result_rescisao,0);
      if(trim($rh30_regime) != ""){
        $result_descricoes = $clrescisao->sql_record($clrescisao->sql_query_file($rh02_anousu,$rh02_mesusu,$rh30_regime,$rh05_causa,$rh05_caub,null,null,"r59_descr,r59_descr1"));
        if($clrescisao->numrows > 0){
          db_fieldsmemory($result_descricoes,0);
        }else{
          $limparrecis = true;
        }
      }else{
        $limparrecis = true;
      }
    }else{
      $limparrecis = true;
    }
    // echo "<BR><BR>".($clrhpespadrao->sql_query_padroes($rh02_seqpes,"rh03_padrao,r02_descr"));
    //
    $result_rhpessoal = $clrhpessoal->sql_record($clrhpessoal->sql_query_file($rh02_regist,"rh01_reajusteparidade"));

    if($clrhpessoal->numrows > 0){
      db_fieldsmemory($result_rhpessoal,0);
    }

    $result_pespadrao = $clrhpespadrao->sql_record($clrhpespadrao->sql_query_padroes($rh02_seqpes,"rh03_padrao,r02_descr"));
    if($clrhpespadrao->numrows > 0){
      db_fieldsmemory($result_pespadrao,0);
    }

    $result_pespadraoprev = $clrhpespadrao->sql_record($clrhpespadrao->sql_query_padrao_previdencia($rh02_seqpes,"rh03_padraoprev,r02_descr as r02_descrprev"));
    if($clrhpespadrao->numrows > 0){
      db_fieldsmemory($result_pespadraoprev,0);
    }

    $result_cargo = $clrhpescargo->sql_record($clrhpescargo->sql_query_descr($rh02_seqpes,"rh20_cargo,rh04_descr"));
    if($clrhpescargo->numrows > 0){
      db_fieldsmemory($result_cargo, 0);
    }

    $result_rhpeslocaltrab = $clrhpeslocaltrab->sql_record($clrhpeslocaltrab->sql_query_descrlocal($rh02_seqpes));
    if($clrhpeslocaltrab->numrows > 0){
      db_fieldsmemory($result_rhpeslocaltrab, 0);
    }

    $result_propi = $clrhpesprop->sql_record($clrhpesprop->sql_query_file($rh02_regist));
    if($clrhpesprop->numrows > 0){
      db_fieldsmemory($result_propi,0);
    }

    $result_contrato = $cltpcontra->sql_record($cltpcontra->sql_query_file($rh02_tpcont));

    if ($cltpcontra->numrows > 0) {
      db_fieldsmemory($result_contrato,0);
    }

    $sSqlRhFuncao = $clrhfuncao->sql_query($rh02_funcao,$instit,"rh37_funcao,rh37_descr",null,"");
    $rsRhFuncao   = $clrhfuncao->sql_record($sSqlRhFuncao);
    if ($clrhfuncao->numrows > 0) {
      db_fieldsmemory($rsRhFuncao,0);
    }
  }
}

if(isset($limparbanco) && $limparbanco == true){
  unset($inputCodigoBanco,$inputNomeBanco,$inputNumeroAgencia,$inputDvAgencia,$inputNumeroConta,$inputDvConta);
} else {

  try {

    $oServidor           = ServidorRepository::getInstanciaByCodigo($rh02_regist, $rh02_anousu, $rh02_mesusu);
    $oContaBancaria      = $oServidor->getContaBancaria();

    if ($oContaBancaria) {
      $db83_sequencial     = $oContaBancaria->getSequencialContaBancaria();
      $db83_tipoconta      = $oContaBancaria->getTipoConta();
      $db83_codigooperacao = $oContaBancaria->getCodigoOperacao();
    }
  } catch(Exception $e ) {

    $db83_sequencial     = "";
    $db83_tipoconta      = "";
    $db83_codigooperacao = "";
  }
}

if(isset($limparrecis) && $limparrecis == true){
  unset($rh05_recis_dia,$rh05_recis_mes,$rh05_recis_ano,$rh05_causa,$rh05_caub,$r59_descr,$rh05_aviso_dia,$rh05_aviso_mes,$rh05_aviso_ano,$r59_descr1,$rh05_taviso);
}

if ( !isset($rh30_vinculo) ) {
  $rh30_vinculo = "";
}

if (isset($rh02_salari)) {
  $rh02_salari = trim(db_formatar($rh02_salari,"p"));
}

/**
 * @param integer $iCargo
 * @param ineteger $iFuncao
 * @param integer $iMatricula
 * @param integer $iAno
 * @param integer $iMes
 * @return boolean
 */
function alterarCargoFuncao($iCargo, $iFuncao, $iMatricula, $iAno, $iMes) {

  $aRubricaCadastrar = array();
  $aRubricaRemover = array();

  $clrhpessoalmov = new cl_rhpessoalmov;
  $clfuncaorhrubricas = new cl_funcaorhrubricas;
  $clcargorhrubricas = new cl_cargorhrubricas;

  $iInstituicao = db_getsession('DB_instit');
  $sCamposFuncao = 'rh177_rubrica as codigo, rh177_quantidade as quantidade, rh177_valor as valor';
  $sCamposCargo = 'rh176_rubrica as codigo, rh176_quantidade as quantidade, rh176_valor as valor';

  $oServidor = ServidorRepository::getInstanciaByCodigo($iMatricula, $iAno, $iMes, $iInstituicao);
  $oPontoFixo = new PontoFixo($oServidor);
  $oPontoFixo->carregarRegistros();
  $aRubricasCadastradas = array_keys($oPontoFixo->getRegistros());

  // dados atuais, cargo e funcao
  $sCampos = "( select rh01_funcao from rhpessoal where rh01_regist = rh02_regist and rh01_instit = rh02_instit";
  $sCampos .= ") as funcao_codigo, ";
  $sCampos .= "( select rh20_cargo from rhpescargo where rh20_seqpes = rh02_seqpes) as cargo_codigo ";
  $sWhere = "rh02_regist = $iMatricula and rh02_anousu = $iAno and rh02_mesusu = $iMes and rh02_instit = $iInstituicao";
  $sSqlDadosAtuais = $clrhpessoalmov->sql_query_file(null, null, $sCampos, null, $sWhere);
  $rsDadosAtuais = db_query($sSqlDadosAtuais);

  if (!$rsDadosAtuais || pg_num_rows($rsDadosAtuais) == 0) {
    throw new DBException('Erro ao buscar dados atuais do servidor');
  }

  $oDadosAtuais = db_utils::fieldsMemory($rsDadosAtuais, 0);

  // rubricas por cargo
  if (!empty($iCargo)) {

    $sRubricaCargo = $clcargorhrubricas->sql_query_file(
      null, $sCamposCargo, null, "rh176_cargo = $iCargo and rh176_instit = $iInstituicao"
    );
    $rsRubricaCargo = db_query($sRubricaCargo);

    if ($rsRubricaCargo && pg_num_rows($rsRubricaCargo) > 0) {

      foreach(db_utils::getCollectionByRecord($rsRubricaCargo) as $oStdDados) {
        $aRubricaCadastrar[$oStdDados->codigo] = $oStdDados;
      }
    }
  }

  // rubricas por funcao
  if (!empty($iFuncao)) {

    $sRubricaFuncao = $clfuncaorhrubricas->sql_query_file(
      null, $sCamposFuncao, null, "rh177_funcao = $iFuncao and rh177_instit = $iInstituicao"
    );
    $rsRubricaFuncao = db_query($sRubricaFuncao);

    if ($rsRubricaFuncao && pg_num_rows($rsRubricaFuncao) > 0) {

      foreach(db_utils::getCollectionByRecord($rsRubricaFuncao) as $oStdDados) {
        $aRubricaCadastrar[$oStdDados->codigo] = $oStdDados;
      }
    }
  }

  // mudou de cargo, pega as rubricas cadastradas
  if (!empty($oDadosAtuais->cargo_codigo) && $oDadosAtuais->cargo_codigo != $iCargo) {

    $sRubricaCargoAtual = $clcargorhrubricas->sql_query_file(
      null, $sCamposCargo, null, "rh176_cargo = {$oDadosAtuais->cargo_codigo} and rh176_instit = $iInstituicao"
    );
    $rsRubricaCargoAtual = db_query($sRubricaCargoAtual);

    if ($rsRubricaCargoAtual && pg_num_rows($rsRubricaCargoAtual) > 0) {

      foreach(db_utils::getCollectionByRecord($rsRubricaCargoAtual) as $oStdDados) {
        $aRubricaRemover[$oStdDados->codigo] = $oStdDados;
      }
    }
  }

  // mudou de funcao, pega as rubricas cadastradas
  if (!empty($oDadosAtuais->funcao_codigo) && $oDadosAtuais->funcao_codigo != $iFuncao) {

    $sRubricaFuncaoAtual = $clfuncaorhrubricas->sql_query_file(
      null, $sCamposFuncao, null, "rh177_funcao = {$oDadosAtuais->funcao_codigo} and rh177_instit = $iInstituicao"
    );
    $rsRubricaFuncaoAtual = db_query($sRubricaFuncaoAtual);

    if ($rsRubricaFuncaoAtual && pg_num_rows($rsRubricaFuncaoAtual) > 0) {

      foreach(db_utils::getCollectionByRecord($rsRubricaFuncaoAtual) as $oStdDados) {
        $aRubricaRemover[$oStdDados->codigo] = $oStdDados;
      }
    }
  }

  // cadastra rubricas
  foreach ($aRubricaCadastrar as $iIndice => $oDadosRubrica) {

    // remove da lista de rubricas para remover
    if (isset($aRubricaRemover[$oDadosRubrica->codigo]))  {
      unset($aRubricaRemover[$oDadosRubrica->codigo]);
    }

    // rubrica ja cadastrada, nao altera
    if (in_array($oDadosRubrica->codigo, $aRubricasCadastradas)) {

      unset($aRubricaCadastrar[$iIndice]);
      continue;
    }

    $oRubrica = RubricaRepository::getInstanciaByCodigo($oDadosRubrica->codigo);
    $oRegistroPonto = new RegistroPonto();
    $oRegistroPonto->setValor($oDadosRubrica->valor);
    $oRegistroPonto->setQuantidade($oDadosRubrica->quantidade);
    $oRegistroPonto->setRubrica($oRubrica);
    $oRegistroPonto->setServidor($oServidor);
    $oPontoFixo->adicionarRegistro($oRegistroPonto, $lSubstituir = true);
  }

  // remove rubricas de cargo/fucnao anterior
  foreach ($aRubricaRemover as $oDadosRubrica) {
    $oPontoFixo->removerRegistro(RubricaRepository::getInstanciaByCodigo($oDadosRubrica->codigo));
  }

  $oPontoFixo->limpar();
  $oPontoFixo->salvar();

  $iRubricasCadastradas = count($aRubricaCadastrar);
  $iRubricasRemovidas = count($aRubricaRemover);

  return $iRubricasRemovidas > 0 || $iRubricasCadastradas > 0;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <?
      db_app::load("scripts.js");
      db_app::load("prototype.js");
      db_app::load("strings.js");
      db_app::load("dbautocomplete.widget.js");
      db_app::load("DBViewContaBancariaServidor.js");
      db_app::load("estilos.css");
      db_app::load("grid.style.css");
      db_app::load("dbtextField.widget.js");
      db_app::load("dbmessageBoard.widget.js");
      db_app::load("dbcomboBox.widget.js");
      db_app::load("prototype.maskedinput.js");
      db_app::load("widgets/Input/DBInput.widget.js");
      db_app::load("widgets/Input/DBInputCNPJ.js");
      db_app::load("widgets/Input/DBInputDate.widget.js");
      db_app::load("DBToogle.widget.js");
    ?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_disabledtipoapos('<?=$rh30_vinculo?>');">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
			<?
			   include(modification("forms/db_frmrhpessoalmov.php"));
			?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>

<?php

if((isset($alterar) || isset($excluir) || isset($incluir)) && !$lErro){

  if (isset($lAlteracaoCargoFuncao) && $lAlteracaoCargoFuncao) {
    db_msgbox("Foram atualizadas as rubricas do ponto fixo deste servidor de acordo com o novo cargo/função.");
  }

  /**
   * Configura WHERE rhpesbanco
   */

  if(isset($inputCodigoBanco)){
    $sWherePesBanco  = "     rh44_codban    = '{$inputCodigoBanco}'   ";
    $sWherePesBanco .= " and rh44_agencia   = '{$inputNumeroAgencia}' ";
    $sWherePesBanco .= " and rh44_dvagencia = '{$inputDvAgencia}'     ";
    $sWherePesBanco .= " and rh44_conta     = '{$inputNumeroConta}'   ";
    $sWherePesBanco .= " and rh44_dvconta   = '{$inputDvConta}'       ";
    $sWherePesBanco .= " and rh02_regist   <> '{$rh02_regist}'        ";
    $sWherePesBanco .= " and rh02_mesusu    = ".db_mesfolha();
    $sWherePesBanco .= " and rh02_anousu    = ".db_anofolha();
    $sWherePesBanco .= " and rhpesrescisao.rh05_seqpes is null";

    $sSqlValidaRhPesBanco = "select distinct
                                    rh02_regist,
                                    z01_nome
                               from rhpesbanco
                                    inner join rhpessoalmov  on rhpessoalmov.rh02_seqpes = rhpesbanco.rh44_seqpes
                                    inner join rhpessoal     on rhpessoal.rh01_regist    = rhpessoalmov.rh02_regist
                                    inner join cgm           on cgm.z01_numcgm           = rhpessoal.rh01_numcgm
  		                               left join rhpesrescisao on rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
  		                        where {$sWherePesBanco}";
    $rsRhPesBanco = $clrhpesbanco->sql_record($sSqlValidaRhPesBanco);
    if ( $clrhpesbanco->numrows > 0 ) {
      $oDadosRhPesBanco    = db_utils::getCollectionByRecord($rsRhPesBanco);
      $sStrDadosServidores = "";
      foreach ($oDadosRhPesBanco as $oDados) {
        $sStrDadosServidores .= $oDados->rh02_regist." - ".$oDados->z01_nome."\\n";
      }
      db_msgbox("AVISO:\\nExistem servidores cadastrados com os mesmos dados de conta informados.\\n\\nServidor(es):\\n {$sStrDadosServidores}");
    }
  }

  db_msgbox($erro_msg);

  if((isset($alterar) || isset($incluir)) && !$sqlerro && !$lErro){
    echo "<script> parent.mo_camada('rhdepend'); </script>";
  }
}

if ($lErro || $sqlerro) {
  db_msgbox($erro_msg);
}

/**
 * Verifica se  o  usuário possui permissao para liberar as abas para o lançamento
 */
if (isset($rh02_seqpes)) {

  echo "<script>
          parent.document.formaba.rhpeslocaltrab.disabled=false;
          (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhpeslocaltrab.location.href='pes1_rhpeslocaltrab001.php?rh56_seqpes=".@$rh02_seqpes."&rh02_regist={$rh02_regist}';
       ";
  if (db_permissaomenu(db_getsession("DB_anousu"), 952,4507) == 'true'||
      db_permissaomenu(db_getsession("DB_anousu"), 952, 4515) == 'true') {

    echo "parent.document.formaba.rhpontofixo.disabled=false;\n";
    echo "(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhpontofixo.location.href='pes1_rhpessoalponto001.php?ponto=fx&r90_regist=".@$rh02_regist."'\n";
  }
  if (db_permissaomenu(db_getsession("DB_anousu"), 952, 4506)  == 'true' ||
      db_permissaomenu(db_getsession("DB_anousu"), 952, 4514)  =='true') {

    echo "parent.document.formaba.rhpontosalario.disabled=false;\n";
    echo "(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhpontosalario.location.href='pes1_rhpessoalponto001.php?ponto=fs&r90_regist=".@$rh02_regist."'\n";
   }

  echo "</script>";
}

?>
