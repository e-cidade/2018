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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clrhpessoal = new cl_rhpessoal;
$clpessoal   = new cl_pessoal;
$clpontofx   = new cl_pontofx;
$clpontofs   = new cl_pontofs;
$clpontofa   = new cl_pontofa;
$clpontofe   = new cl_pontofe;
$clpontofr   = new cl_pontofr;
$clpontof13  = new cl_pontof13;
$clpontocom  = new cl_pontocom;
$clrhrubricas= new cl_rhrubricas;
$cllotacao   = new cl_lotacao;

$db_opcao    = 1;
$iDbOpcao    = 1;
$db_botao    = true;
$oPost       = db_utils::postMemory($_POST);
$oGet        = db_utils::postMemory($_GET);

$rh183_datainicio_ano = '';
$rh183_datainicio_mes = '';
$rh183_datainicio_dia = '';

$rh183_datafim_ano = '';
$rh183_datafim_mes = '';
$rh183_datafim_dia = '';

$lBloqueioDatasPeriodo  = true;
$rh27_periodolancamento = 'f';
// se a rotina for acessada pela consulta financeira
// tratamos eventos como: bloquear a ancora de matricula e reescrevemos o fechar da lookup para recarregar a consulta

if (isset($oGet->lConsulta)) {

  $ponto      = $oGet->sPonto;
  $r90_regist = $oGet->iMatricula;
  $iDbOpcao   = 3;

  $sFechar = "
            <script>
               var oBTnFechar = parent.$('fechardb_iframe_ponto');
                   oBTnFechar.onclick = function js_FecharJan(iMgsSs,JanElAaX) {
                    // alert('reescrito');
                     parent.db_iframe_ponto.hide();

                     parent.js_chama_link2('{$oGet->sChama}');
                     parent.js_MudaLink('{$oGet->sMuda}');
                   }               
             </script> ";
}

// Se variáveis anouso e mesusu não existem, ele pegará as variáveis atuais da folha
if(!isset($r90_anousu)){
  $r90_anousu = db_anofolha();
}

if(!isset($r90_mesusu)){
  $r90_mesusu = db_mesfolha();
}
////////////
if(isset($incluir) || isset($confirmado) || isset($pontonovo)){



    $pontoteste = 0;
    if((($ponto == "fx" || $ponto == "Rfx") && !isset($pontonovo)) || (isset($pontonovo) && trim($pontonovo)=="fx")){

      // Rotina que verifica se já existe, na tabela PONTOFX, algum registro com o mesmo anousu, mesusu e rubrica
      $result_pontofixo = $clpontofx->sql_record($clpontofx->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,"r90_valor as valoranterior,r90_quant as quantidadeanterior"));
    if($clpontofx->numrows > 0){
      db_fieldsmemory($result_pontofixo,0);
      $pontoteste = 1;
      }
      //////////

    }else if((($ponto == "fs" || $ponto == "Rfs") && !isset($pontonovo)) || (isset($pontonovo) && trim($pontonovo)=="fs")){

      // Rotina que verifica se já existe, na tabela PONTOFS, algum registro com o mesmo anousu, mesusu e rubrica
    $result_pontosalario = $clpontofs->sql_record($clpontofs->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,"r10_valor as valoranterior,r10_quant as quantidadeanterior"));
    if($clpontofs->numrows > 0){
      db_fieldsmemory($result_pontosalario,0);
      $pontoteste = 1;
      }
      //////////

    }else if($ponto == "fa" || $ponto == "Rfa"){

      // Rotina que verifica se já existe, na tabela PONTOFA, algum registro com o mesmo anousu, mesusu e rubrica
    $result_pontoadiantamento = $clpontofa->sql_record($clpontofa->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,"r21_valor as valoranterior,r21_quant as quantidadeanterior"));
    if($clpontofa->numrows > 0){
      db_fieldsmemory($result_pontoadiantamento,0);
      $pontoteste = 1;
      }
      //////////

    }else if($ponto == "fe" || $ponto == "Rfe"){

      // Rotina que verifica se já existe, na tabela PONTOFE, algum registro com o mesmo anousu, mesusu, rubrica e tipo
    $result_pontoferias = $clpontofe->sql_record($clpontofe->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,$r29_tpp,"r29_valor as valoranterior,r29_quant as quantidadeanterior"));
    if($clpontofe->numrows > 0){
      db_fieldsmemory($result_pontoferias,0);
      $pontoteste = 1;
      }
      //////////

    }else if($ponto == "fr" || $ponto == "Rfr"){

      // Rotina que verifica se já existe, na tabela PONTOFR, algum registro com o mesmo anousu, mesusu, rubrica e tipo
      $result_pontorescisao= $clpontofr->sql_record($clpontofr->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,$r29_tpp,"r19_valor as valoranterior,r19_quant as quantidadeanterior"));
    if($clpontofr->numrows > 0){
      db_fieldsmemory($result_pontorescisao,0);
      $pontoteste = 1;
      }
      //////////

    }else if($ponto == "f13" || $ponto == "Rf13"){

      // Rotina que verifica se já existe, na tabela PONTOF13, algum registro com o mesmo anousu, mesusu e rubrica
    $result_pontodecimo = $clpontof13->sql_record($clpontof13->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,"r34_valor as valoranterior,r34_quant as quantidadeanterior"));
    if($clpontof13->numrows > 0){
      db_fieldsmemory($result_pontodecimo,0);
      $pontoteste = 1;
      }
      //////////

    }else if($ponto == "com" || $ponto == "Rcom"){

      // Rotina que verifica se já existe, na tabela PONTOCOM, algum registro com o mesmo anousu, mesusu e rubrica
      $result_pontocomplementar = $clpontocom->sql_record($clpontocom->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,"r47_valor as valoranterior,r47_quant as quantidadeanterior"));

      if($clpontocom->numrows > 0){

        db_fieldsmemory($result_pontocomplementar,0);
        $pontoteste = 1;
      }
    }

    $ok = false;

    if($pontoteste > 0){
      if(!isset($confirmado) && !isset($pontonovo)){
      $alertconfirma = true;
      $db_opcao = 22;
    }else if(isset($confirmado) && $confirmado == "true" && !isset($pontonovo)){
      $ok = true;
      $r90_valor += $valoranterior;
      $r90_quant += $quantidadeanterior;
      $alterar = "alterar";
      unset($incluir);
    }else{
      $alterar = "alterar";
      unset($incluir);
      }
    }
}

/**
 * --------------------------------------------------------------------
 * Incluir
 * --------------------------------------------------------------------
 */
if ( (isset($incluir) && !isset($alertconfirma)) || (isset($pontonovo) && !isset($alterar))) {



  db_inicio_transacao();
  $sqlerro = false;

  $valor_em_branco = false;
  $quant_em_branco = false;

  if(trim($r90_quant) == ""){
    $r90_quant = "0";
    $quant_em_branco = true;
  }
  if(trim($r90_valor) == ""){
    $r90_valor = "0";
    $valor_em_branco = true;
  }

  $result_verifica_rubrica_com_formula = $clrhrubricas->sql_record($clrhrubricas->sql_query_file(null,null,"rh27_form as formula_testa,rh27_limdat as limdata_testa, rh27_propq as proporcionalizar, rh27_periodolancamento","","rh27_instit = ".db_getsession("DB_instit")." and rh27_rubric = '".$r90_rubric."'"));
  if($clrhrubricas->numrows > 0){

    db_fieldsmemory($result_verifica_rubrica_com_formula,0);
    if($limdata_testa == "t"){
      if(isset($r90_datlim) && $r90_datlim == ""){
        $sqlerro = true;
        $erro_msg = "Ano/Mês não informado";
        $campofoco = "r90_datlim";
      }
    }else if($limdata_testa == "f"){
      $r90_datlim = "";
    }else if(trim($formula_testa) != ""){
      if($r90_quant == 0){
        $sqlerro = true;
        $erro_msg = "Quantidade não informada";
        $campofoco = "r90_quant";
      }
    } else {

      if($r90_valor == 0){

        $sqlerro   = true;
        $erro_msg  = "Valor não informado";
        $campofoco = "r90_valor";
      }
    }
  } else {

    $sqlerro = true;
    $erro_msg = "Rubrica ".$r90_rubric." não encontrada. Verifique.";
    $r90_rubric = "";
    $rh27_descr = "";

  }

  $lIncluirRubricaNoFixoOuSalario = true;
  $oDataInicioControle            = null;
  $oDataFimControle               = null;
  $lVerificarProporcaoPeriodo     = false;
  if ($sqlerro == false && $rh27_periodolancamento == 't') {

    $oServidor                 = ServidorRepository::getInstanciaByCodigo($r90_regist);
    $oRubrica                  = RubricaRepository::getInstanciaByCodigo($r90_rubric);
    $oRubricaPeriodoLancamento = PontoRubricaPeriodoRepository::getPontoRubricaPorServidorRubrica($oServidor, $oRubrica);

    $iInstituicao   = db_getsession("DB_instit");

    if (isset($pontonovo) && trim($pontonovo) == "fs" && !empty($oRubricaPeriodoLancamento)) {

      $oDataInicioControle = $oRubricaPeriodoLancamento->getDataInicio();
      $lVerificarProporcaoPeriodo = true;

      if ($oDataInicioControle->getCompetencia()->comparar(DBPessoal::getCompetenciaFolha(), DBCompetencia::COMPARACAO_MAIOR)) {
        $lIncluirRubricaNoFixoOuSalario = false;
      }

      if ($oRubricaPeriodoLancamento->getDataFim()->getCompetencia()->comparar(DBPessoal::getCompetenciaFolha())) {
        $oDataFimControle = $oRubricaPeriodoLancamento->getDataFim();
      }
    }

    if (!empty($rh183_datainicio) && !empty($rh183_datafim)) {

      if (empty($oRubricaPeriodoLancamento)) {
        $oRubricaPeriodoLancamento = new PontoRubricaPeriodo();
      }
      /**
       *Incluimos a rubrica no controle da periodo
       */
      if (!$sqlerro) {

        try {

          $oRubricaPeriodoLancamento->setRubrica($oRubrica);
          $oRubricaPeriodoLancamento->setServidor($oServidor);
          $oRubricaPeriodoLancamento->setDataInicio(new DBDate($rh183_datainicio));
          $oRubricaPeriodoLancamento->setDataFim(new DBDate($rh183_datafim));
          $oRubricaPeriodoLancamento->setQuantidade($r90_quant);
          $oRubricaPeriodoLancamento->setValor($r90_valor);
          $oRubricaPeriodoLancamento->setInstituicao(InstituicaoRepository::getInstituicaoSessao());
          PontoRubricaPeriodoRepository::persist($oRubricaPeriodoLancamento);

          $oDataInicioControle        = new DBDate($rh183_datainicio);
          $lVerificarProporcaoPeriodo = true;

          if ($oDataInicioControle->getCompetencia()->comparar(DBPessoal::getCompetenciaFolha(), DBCompetencia::COMPARACAO_MAIOR)) {
            $lIncluirRubricaNoFixoOuSalario = false;
          }
        } catch (Exception $oErro) {

          $sqlerro = true;
          $erro_msg = $oErro->getMessage();
        }
      }
    }
  }

  /**
   * PONTO FIXO
   */
  if ($sqlerro == false && ((($ponto == "fx" || $ponto == "Rfx") && !isset($pontonovo)) || (isset($pontonovo) && trim($pontonovo)=="fx")) && $lIncluirRubricaNoFixoOuSalario)  {

    $clpontofx->r90_anousu = $r90_anousu;
    $clpontofx->r90_mesusu = $r90_mesusu;
    $clpontofx->r90_regist = $r90_regist;
    $clpontofx->r90_rubric = $r90_rubric;
    $clpontofx->r90_valor  = "round($r90_valor,2)";
    $clpontofx->r90_quant  = "$r90_quant";
    $clpontofx->r90_lotac  = $r90_lotac;
    $clpontofx->r90_datlim = $r90_datlim;
    $clpontofx->r90_instit = db_getsession("DB_instit");
    $clpontofx->incluir($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric);

    $erro_msg = $clpontofx->erro_msg;
    $repassa  = true;

    if($clpontofx->erro_status==0){

      $sqlerro=true;
      unset($repassa);
    }

  }

  /**
   * PONTO DE SALARIO
   */
  elseif ($sqlerro == false && ((($ponto == "fs" || $ponto == "Rfs") && !isset($pontonovo)) || (isset($pontonovo) && trim($pontonovo)=="fs")) && $lIncluirRubricaNoFixoOuSalario) {

    if ((isset($pontonovo) && trim($pontonovo)=="fs" && $proporcionalizar == 't') || $lVerificarProporcaoPeriodo) {

      $sDataValidar = $data_de_admissao;
      if (!empty($oDataInicioControle)) {
        $sDataValidar = $oDataInicioControle->getDate(DBDate::DATA_EN);
      }
      $arr_ano_mes_usu = explode("-", $sDataValidar);
      $ano_da_admissao = $arr_ano_mes_usu[0];
      $mes_da_admissao = $arr_ano_mes_usu[1];
      $dia_da_admissao = $arr_ano_mes_usu[2] - 1;
      if($mes_da_admissao == db_mesfolha() && $ano_da_admissao == db_anofolha()){
        // Só proporcionalizar na admissao se a rubrica estiver assim definida
        $dia_recebe_mens = 30;
        if (!empty($oDataFimControle)) {
          $dia_recebe_mens = $oDataFimControle->getDia();
        }
        if ($dia_recebe_mens > 30) {
          $dia_recebe_mens = 30;
        }
        $dias_a_pagar    = $dia_recebe_mens - $dia_da_admissao;

        if($quant_em_branco == false){
          $r90_quant = ($r90_quant / 30) * $dias_a_pagar;
        }
        if($valor_em_branco == false){
          $r90_valor = ($r90_valor / 30) * $dias_a_pagar;
        }
      }
    }

    $clpontofs->r10_anousu = $r90_anousu;
    $clpontofs->r10_mesusu = $r90_mesusu;
    $clpontofs->r10_regist = $r90_regist;
    $clpontofs->r10_rubric = $r90_rubric;
    $clpontofs->r10_valor  = "round($r90_valor,2)";
    $clpontofs->r10_quant  = "round($r90_quant, 2)";
    $clpontofs->r10_lotac  = $r90_lotac;
    $clpontofs->r10_datlim = $r90_datlim;
    $clpontofs->r10_instit = db_getsession("DB_instit");
    $clpontofs->incluir($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric);

    $erro_msg = $clpontofs->erro_msg;
    $repassa  = true;

    if($clpontofs->erro_status==0){

      $sqlerro=true;
      unset($repassa);
    }
  }


  /**
   * PONTO DE ADIANTAMENTO
   */
  elseif ( $sqlerro == false && ($ponto == "fa" || $ponto == "Rfa") ) {

    $clpontofa->r21_anousu = $r90_anousu;
    $clpontofa->r21_mesusu = $r90_mesusu;
    $clpontofa->r21_regist = $r90_regist;
    $clpontofa->r21_rubric = $r90_rubric;
    $clpontofa->r21_valor  = "round($r90_valor,2)";
    $clpontofa->r21_quant  = "$r90_quant";
    $clpontofa->r21_lotac  = $r90_lotac;
    $clpontofa->r21_instit = db_getsession("DB_instit");
    $clpontofa->incluir($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric);

    $erro_msg = $clpontofa->erro_msg;

    if($clpontofa->erro_status==0){
      $sqlerro=true;
    }
  }

  /**
   * PONTO DE FERIAS
   */
  else if($sqlerro == false && ($ponto == "fe" || $ponto == "Rfe")) {

    $clpontofe->r29_anousu = $r90_anousu;
    $clpontofe->r29_mesusu = $r90_mesusu;
    $clpontofe->r29_regist = $r90_regist;
    $clpontofe->r29_rubric = $r90_rubric;
    $clpontofe->r29_valor  = "round($r90_valor,2)";
    $clpontofe->r29_quant  = "$r90_quant";
    $clpontofe->r29_lotac  = $r90_lotac;
    $clpontofe->r29_media  = "0";
    $clpontofe->r29_calc   = "0";
    $clpontofe->r29_tpp    = $r29_tpp;
    $clpontofe->r29_instit = db_getsession("DB_instit");
    $clpontofe->incluir($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,$r29_tpp);

    $erro_msg = $clpontofe->erro_msg;

    if($clpontofe->erro_status==0){
      $sqlerro=true;
    }
  }

  /**
   * PONTO DE RESCISAO
   * - incluir
   */
  elseif ($sqlerro == false && ($ponto == "fr" || $ponto == "Rfr")) {

    $clpontofr->r19_anousu = $r90_anousu;
    $clpontofr->r19_mesusu = $r90_mesusu;
    $clpontofr->r19_regist = $r90_regist;
    $clpontofr->r19_rubric = $r90_rubric;
    $clpontofr->r19_valor  = "round($r90_valor,2)";
    $clpontofr->r19_quant  = "$r90_quant";
    $clpontofr->r19_lotac  = $r90_lotac;
    $clpontofr->r19_tpp    = $r29_tpp;
    $clpontofr->r19_instit = db_getsession("DB_instit");
    $clpontofr->incluir($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,$r29_tpp);

    $erro_msg = $clpontofr->erro_msg;

    if($clpontofr->erro_status==0){
      $sqlerro=true;
    }
  }

  /**
   * PONTO DE 13o SALARIO
   * - Incluir
   */
  elseif( $sqlerro == false && ($ponto == "f13" || $ponto == "Rf13") ) {

    $clpontof13->r34_anousu = $r90_anousu;
    $clpontof13->r34_mesusu = $r90_mesusu;
    $clpontof13->r34_regist = $r90_regist;
    $clpontof13->r34_rubric = $r90_rubric;
    $clpontof13->r34_valor  = "round($r90_valor,2)";
    $clpontof13->r34_quant  = "$r90_quant";
    $clpontof13->r34_lotac  = $r90_lotac;
    $clpontof13->r34_media  = "0";
    $clpontof13->r34_calc   = "0";
    $clpontof13->r34_instit = db_getsession("DB_instit");
    $clpontof13->incluir($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric);
    $erro_msg = $clpontof13->erro_msg;
    if($clpontof13->erro_status==0){
      $sqlerro=true;
    }
  }

  /**
   * COMPLEMENTAR
   * - Inclui
   */
  else if($sqlerro == false && ($ponto == "com" || $ponto == "Rcom")) {

    try {

      /**
       * Servidor
       */
      $oServidor = new Servidor($r90_regist, $r90_anousu, $r90_mesusu);

      /**
       * Ponto complementar
       */
      $oPontoComplementar = $oServidor->getPonto(Ponto::COMPLEMENTAR);
      $oPontoComplementar->carregarRegistros();

      /**
       * Rubrica
       */
      $oRubrica = RubricaRepository::getInstanciaByCodigo($r90_rubric);

      /**
       * Registro do ponto
       */
      $oRegistro = new RegistroPonto();
      $oRegistro->setServidor($oServidor);
      $oRegistro->setRubrica($oRubrica);
      $oRegistro->setQuantidade($r90_quant);
      $oRegistro->setValor(round($r90_valor, 2) );

      /**
       * Adiciona o registro ao ponto complementar
       */
      $oPontoComplementar->adicionarRegistro($oRegistro);

      /**
       * Salva alteracoes
       */
      $oPontoComplementar->gerar();

      db_msgbox('Inclusão efetuada com sucesso.');

    } catch (Exception $oErro) {

      $erro_msg = $oErro->getMessage();
      $sqlerro  = true;
    }
  }

  db_fim_transacao($sqlerro);
}

/**
 * --------------------------------------------------------------------
 * Alterar
 * --------------------------------------------------------------------
 */
else if (isset($alterar)) {

    db_inicio_transacao();
    $sqlerro = false;

    $valor_em_branco = false;
    $quant_em_branco = false;

    if(trim($r90_quant) == ""){
      $r90_quant = "0";
      $quant_em_branco = true;
    }
    if(trim($r90_valor) == ""){
      $r90_valor = "0";
      $valor_em_branco = true;
    }

    $result_verifica_rubrica_com_formula = $clrhrubricas->sql_record($clrhrubricas->sql_query_file(null,null,"rh27_form as formula_testa,rh27_limdat as limdata_testa, rh27_propq as proporcionalizar, rh27_periodolancamento","","rh27_instit = ".db_getsession("DB_instit")." and rh27_rubric = '".$r90_rubric."'"));
    if($clrhrubricas->numrows > 0){
      db_fieldsmemory($result_verifica_rubrica_com_formula,0);
      if(trim($formula_testa) != ""){
        if($r90_quant == 0){
          $sqlerro = true;
          $erro_msg = "Quantidade não informada";
          $campofoco = "r90_quant";
        }
      }else{
        if($r90_valor == 0){
          $sqlerro = true;
          $erro_msg = "Valor não informado";
          $campofoco = "r90_valor";
        }
      }
    }else{
      $sqlerro = true;
      $erro_msg = "Rubrica ".$r90_rubric." não encontrada. Verifique.";
      $r90_rubric = "";
      $rh27_descr = "";
    }


    $lIncluirRubricaNoFixoOuSalario = true;
    $oDataInicioControle            = null;
    $oDataFimControle               = null;
    $lVerificarProporcaoPeriodo     = false;

    $oServidor                 = ServidorRepository::getInstanciaByCodigo($r90_regist);
    $oRubrica                  = RubricaRepository::getInstanciaByCodigo($r90_rubric);
    $oRubricaPeriodoLancamento = PontoRubricaPeriodoRepository::getPontoRubricaPorServidorRubrica($oServidor, $oRubrica);

    /**
     * Removemos o periodo para ser incluido novamente
     */
    if (!empty($oRubricaPeriodoLancamento) && (!isset($pontonovo) && in_array($ponto, array('fs', 'fx')))) {

      PontoRubricaPeriodoRepository::remove($oRubricaPeriodoLancamento->getCodigo());
      unset($oRubricaPeriodoLancamento);
    }
    if ($sqlerro == false && $rh27_periodolancamento == 't') {


      $iInstituicao   = db_getsession("DB_instit");

      if (!empty($rh183_datainicio) && !empty($rh183_datafim)) {

        if (empty($oRubricaPeriodoLancamento)) {
          $oRubricaPeriodoLancamento = new PontoRubricaPeriodo();
        }
        /**
         *Incluimos a rubrica no controle da periodo
         */
        if (!$sqlerro) {

          try {

            $oRubricaPeriodoLancamento->setRubrica($oRubrica);
            $oRubricaPeriodoLancamento->setServidor($oServidor);
            $oRubricaPeriodoLancamento->setDataInicio(new DBDate($rh183_datainicio));
            $oRubricaPeriodoLancamento->setDataFim(new DBDate($rh183_datafim));
            $oRubricaPeriodoLancamento->setQuantidade($r90_quant);
            $oRubricaPeriodoLancamento->setValor($r90_valor);
            $oRubricaPeriodoLancamento->setInstituicao(InstituicaoRepository::getInstituicaoSessao());
            PontoRubricaPeriodoRepository::persist($oRubricaPeriodoLancamento);

            $oDataInicioControle        = new DBDate($rh183_datainicio);
            $lVerificarProporcaoPeriodo = true;

            if ($oDataInicioControle->getCompetencia()->comparar(DBPessoal::getCompetenciaFolha(), DBCompetencia::COMPARACAO_MAIOR)) {

              PontoRubricaPeriodo::removerRubricaDoPontosDoServidor($oRubrica, $oServidor, DBPessoal::getCompetenciaFolha());
              $lIncluirRubricaNoFixoOuSalario = false;
            }
          } catch (Exception $oErro) {

            $sqlerro = true;
            $erro_msg = $oErro->getMessage();
          }
        }
      }

      if ((isset($pontonovo) && trim($pontonovo) == "fs" || $ponto == 'fs' ) && !empty($oRubricaPeriodoLancamento)) {

        $oDataInicioControle = $oRubricaPeriodoLancamento->getDataInicio();
        $lVerificarProporcaoPeriodo = true;

        if ($oDataInicioControle->getCompetencia()->comparar(DBPessoal::getCompetenciaFolha(), DBCompetencia::COMPARACAO_MAIOR)) {
          $lIncluirRubricaNoFixoOuSalario = false;
        }

        if ($oRubricaPeriodoLancamento->getDataFim()->getCompetencia()->comparar(DBPessoal::getCompetenciaFolha())) {
          $oDataFimControle = $oRubricaPeriodoLancamento->getDataFim();
        }
      }
    }
    // Se for ponto fixo
    if($sqlerro == false && ((($ponto == "fx" || $ponto == "Rfx") && !isset($pontonovo)) || (isset($pontonovo) && trim($pontonovo)=="fx")) && $lIncluirRubricaNoFixoOuSalario) {

      if (isset($pontonovo) && ! empty($oRubricaPeriodoLancamento)) {

        $r90_valor = $oRubricaPeriodoLancamento->getValor();
        $r90_quant = $oRubricaPeriodoLancamento->getQuantidade();
      }
      $clpontofx->r90_anousu = $r90_anousu;
      $clpontofx->r90_mesusu = $r90_mesusu;
      $clpontofx->r90_regist = $r90_regist;
      $clpontofx->r90_rubric = $r90_rubric;
      $clpontofx->r90_valor  = "round($r90_valor,2)";
      $clpontofx->r90_quant  = "$r90_quant";
      $clpontofx->r90_lotac  = $r90_lotac;
      $clpontofx->r90_datlim = $r90_datlim;
      $clpontofx->r90_instit = db_getsession("DB_instit");
      $clpontofx->alterar($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric);
      $erro_msg = $clpontofx->erro_msg;
      $repassa = true;
      if($clpontofx->erro_status==0){
        $sqlerro=true;
        unset($repassa);
      }
    //////////

    // Se for ponto de salário
    } else if($sqlerro == false && ((($ponto == "fs" || $ponto == "Rfs") && !isset($pontonovo)) || (isset($pontonovo) && trim($pontonovo)=="fs")) && $lIncluirRubricaNoFixoOuSalario) {

      if ((isset($pontonovo) && trim($pontonovo)=="fs" && $proporcionalizar == 't')
        || ($lVerificarProporcaoPeriodo)) {

        $sDataValidar = $data_de_admissao;
        if (!empty($oDataInicioControle)) {
          $sDataValidar = $oDataInicioControle->getDate(DBDate::DATA_EN);
        }
        $arr_ano_mes_usu = explode("-", $sDataValidar);
        $ano_da_admissao = $arr_ano_mes_usu[0];
        $mes_da_admissao = $arr_ano_mes_usu[1];
        $dia_da_admissao = $arr_ano_mes_usu[2] - 1;
        if ($mes_da_admissao == db_mesfolha() && $ano_da_admissao == db_anofolha()) {
          // Só proporcionalizar na admissao se a rubrica estiver assim definida
          $dia_recebe_mens = 30;
          if (!empty($oDataFimControle)) {
            $dia_recebe_mens = $oDataFimControle->getDia();
          }
          if ($dia_recebe_mens > 30) {
            $dia_recebe_mens = 30;
          }
          $dias_a_pagar    = $dia_recebe_mens - $dia_da_admissao;

          if ($quant_em_branco == false) {
            $r90_quant = ($r90_quant / 30) * $dias_a_pagar;
          }

          if ($valor_em_branco == false){
            $r90_valor = ($r90_valor / 30) * $dias_a_pagar;
          }
        }
      }

      $clpontofs->r10_anousu = $r90_anousu;
      $clpontofs->r10_mesusu = $r90_mesusu;
      $clpontofs->r10_regist = $r90_regist;
      $clpontofs->r10_rubric = $r90_rubric;
      $clpontofs->r10_valor  = "round($r90_valor,2)";
      $clpontofs->r10_quant  = "$r90_quant";
      $clpontofs->r10_lotac  = $r90_lotac;
      $clpontofs->r10_datlim = $r90_datlim;
      $clpontofs->r10_instit = db_getsession("DB_instit");
      $clpontofs->alterar($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric);
      $erro_msg = $clpontofs->erro_msg;
      $repassa = true;
      if($clpontofs->erro_status==0){
        $sqlerro=true;
        unset($repassa);
      }
    //////////

    // Se for ponto de adiantamento
    }else if($sqlerro == false && ($ponto == "fa" || $ponto == "Rfa")){
      $clpontofa->r21_anousu = $r90_anousu;
      $clpontofa->r21_mesusu = $r90_mesusu;
      $clpontofa->r21_regist = $r90_regist;
      $clpontofa->r21_rubric = $r90_rubric;
      $clpontofa->r21_valor  = "round($r90_valor,2)";
      $clpontofa->r21_quant  = "$r90_quant";
      $clpontofa->r21_lotac  = $r90_lotac;
      $clpontofa->r21_instit = db_getsession("DB_instit");
      $clpontofa->alterar($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric);
      $erro_msg = $clpontofa->erro_msg;
      if($clpontofa->erro_status==0){
        $sqlerro=true;
      }

    // Se for ponto de férias
    }else if($sqlerro == false && ($ponto == "fe" || $ponto == "Rfe")){
      $clpontofe->r29_anousu = $r90_anousu;
      $clpontofe->r29_mesusu = $r90_mesusu;
      $clpontofe->r29_regist = $r90_regist;
      $clpontofe->r29_rubric = $r90_rubric;
      $clpontofe->r29_valor  = "round($r90_valor,2)";
      $clpontofe->r29_quant  = "$r90_quant";
      $clpontofe->r29_lotac  = $r90_lotac;
      $clpontofe->r29_media  = "0";
      $clpontofe->r29_calc   = "0";
      $clpontofe->r29_tpp    = $r29_tpp;
      $clpontofe->r29_instit = db_getsession("DB_instit");
      $clpontofe->alterar($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,$r29_tpp);
      $erro_msg = $clpontofe->erro_msg;
      if($clpontofe->erro_status==0){
        $sqlerro=true;
      }
    //////////

    }else if($sqlerro == false && ($ponto == "fr" || $ponto == "Rfr")){
    // Se for ponto de rescisão
      $clpontofr->r19_anousu = $r90_anousu;
      $clpontofr->r19_mesusu = $r90_mesusu;
      $clpontofr->r19_regist = $r90_regist;
      $clpontofr->r19_rubric = $r90_rubric;
      $clpontofr->r19_valor  = "round($r90_valor,2)";
      $clpontofr->r19_quant  = "$r90_quant";
      $clpontofr->r19_lotac  = $r90_lotac;
      $clpontofr->r19_tpp    = $r29_tpp;
      $clpontofr->r19_instit = db_getsession("DB_instit");
      $clpontofr->alterar($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,$r29_tpp);
      $erro_msg = $clpontofr->erro_msg;
      if($clpontofr->erro_status==0){
        $sqlerro=true;
      }
    //////////

    }else if($sqlerro == false && ($ponto == "f13" || $ponto == "Rf13")){
    // Se for ponto de 13o
      $clpontof13->r34_anousu = $r90_anousu;
      $clpontof13->r34_mesusu = $r90_mesusu;
      $clpontof13->r34_regist = $r90_regist;
      $clpontof13->r34_rubric = $r90_rubric;
      $clpontof13->r34_valor  = "round($r90_valor,2)";
      $clpontof13->r34_quant  = "$r90_quant";
      $clpontof13->r34_lotac  = $r90_lotac;
      $clpontof13->r34_media  = "0";
      $clpontof13->r34_calc   = "0";
      $clpontof13->r34_instit = db_getsession("DB_instit");
      $clpontof13->alterar($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric);
      $erro_msg = $clpontof13->erro_msg;
      if($clpontof13->erro_status==0){
        $sqlerro=true;
      }

    }

    /**
     * COMPLEMENTAR
     * - Alterar
     */
    elseif($sqlerro == false && ($ponto == "com" || $ponto == "Rcom")){

      try {

        /**
         * Servidor
         */
        $oServidor = new Servidor($r90_regist, $r90_anousu, $r90_mesusu);

        /**
         * Ponto complementar
         */
        $oPontoComplementar = $oServidor->getPonto(Ponto::COMPLEMENTAR);
        $oPontoComplementar->carregarRegistros();

        /**
         * Rubrica
         */
        $oRubrica = RubricaRepository::getInstanciaByCodigo($r90_rubric);

        /**
         * Registro do ponto
         */
        $oRegistro = new RegistroPonto();
        $oRegistro->setServidor($oServidor);
        $oRegistro->setRubrica($oRubrica);
        $oRegistro->setQuantidade($r90_quant);
        $oRegistro->setValor( round($r90_valor, 2) );

        /**
         * Remove rubrica do ponto.
         */
        $oPontoComplementar->removerRegistro( $oRubrica );

        /**
         * Adiciona o registro ao ponto complementar.
         */
        $oPontoComplementar->adicionarRegistro($oRegistro);

        /**
         * Salva alteracoes
         */
        $oPontoComplementar->gerar();

        db_msgbox('Alteração efetuada com sucesso');

      } catch (Exception $oErro) {

        $erro_msg = $oErro->getMessage();
        $sqlerro  = true;
      }

    }

    if ( isset($ok) && $ok == true) {

      $r90_valor -= $valoranterior;
      $r90_quant -= $quantidadeanterior;
    }

    db_fim_transacao($sqlerro);

}

/**
 * --------------------------------------------------------------------
 * Excluir
 * --------------------------------------------------------------------
 */
elseif ( isset($excluir) || isset ( $pontoexcluir) ) {

    db_inicio_transacao();
    $sqlerro = false;

   $aPontosRemoverControlePeriodo = array("fx", "fs");
   if (in_array($ponto, $aPontosRemoverControlePeriodo) || (isset($pontoexcluir) && in_array($pontoexcluir, $aPontosRemoverControlePeriodo))) {

     $iInstituicao   = db_getsession("DB_instit");
     $aWhereRubricas = array("rh183_matricula = $r90_regist",
       "rh183_rubrica   = '$r90_rubric'",
       "rh183_instituicao = $iInstituicao"
     );
     $sWhereRubricas = implode(" and ", $aWhereRubricas);

     $oDaoPontoRubrica                    = new cl_pontosalariodatalimite();
     $lRubricaTemControlePeriodoLancado   = false;
     $oDaoPontoRubrica->excluir(null, $sWhereRubricas);
     if ($oDaoPontoRubrica->erro_status == 0) {

       $sqlerro = true;
       $erro_msg = $oDaoPontoRubrica->erro_msg;
     }
   }
    // Se for ponto fixo
    if((($ponto == "fx" || $ponto == "Rfx") && !isset($pontoexcluir)) || (isset($pontoexcluir) && $pontoexcluir == "fx")){

      if(!isset($pontoexcluir)){
        $repassa_ponto    = "fs";
        $repassa_exclusao = true;
        $db_opcao = 3;
      }

      $clpontofx->excluir($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric);
      $erro_msg = $clpontofx->erro_msg;
      if($clpontofx->erro_status==0){
        $sqlerro=true;
      }
    //////////

    // Se for ponto de salário
    }else if((($ponto == "fs" || $ponto == "Rfs") && !isset($pontoexcluir)) || (isset($pontoexcluir) && $pontoexcluir == "fs")){

      // Testa se rubrica do ponto de salário é do tipo fixo
      $result_repassa_exclusao = $clrhrubricas->sql_record($clrhrubricas->sql_query_file(null,null,"*","","rh27_instit = ".db_getsession("DB_instit")." and  rh27_rubric = '$r90_rubric' and (rh27_tipo = 1 or rh27_limdat = 't') and rh27_instit=".db_getsession("DB_instit")));
      if($clrhrubricas->numrows > 0 && !isset($pontoexcluir)){
        $repassa_ponto    = "fx";
        $repassa_exclusao = true;
        $db_opcao = 3;
      }

      $clpontofs->excluir($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric);
      $erro_msg = $clpontofs->erro_msg;
      if($clpontofs->erro_status==0){
        $sqlerro=true;
      }
    //////////

    // Se for ponto de adiantamento
    }else if($ponto == "fa" || $ponto == "Rfa"){
      $clpontofa->excluir($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric);
      $erro_msg = $clpontofa->erro_msg;
      if($clpontofa->erro_status==0){
        $sqlerro=true;
      }
    //////////

    // Se for ponto de férias
    }else if($ponto == "fe" || $ponto == "Rfe"){
      $clpontofe->excluir($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,$r29_tpp);
      $erro_msg = $clpontofe->erro_msg;
      if($clpontofe->erro_status==0){
        $sqlerro=true;
      }
    //////////

    // Se for ponto de rescisão
    }else if($ponto == "fr" || $ponto == "Rfr"){
      $clpontofr->excluir($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric,$r29_tpp);
      $erro_msg = $clpontofr->erro_msg;
      if($clpontofr->erro_status==0){
        $sqlerro=true;
      }
    //////////

    // Se for ponto de 13o
    }else if($ponto == "f13" || $ponto == "Rf13"){
      $clpontof13->excluir($r90_anousu,$r90_mesusu,$r90_regist,$r90_rubric);
      $erro_msg = $clpontof13->erro_msg;
      if($clpontof13->erro_status==0){
        $sqlerro=true;
      }
    //////////

    // Se for ponto complementar
    }

    /**
     * COMPLEMENTAR
     * - Excluir
     */
    elseif ($ponto == "com" || $ponto == "Rcom") {

      try {

        /**
         * Servidor
         */
        $oServidor = new Servidor($r90_regist, $r90_anousu, $r90_mesusu);

        /**
         * Ponto complementar
         */
        $oPontoComplementar = $oServidor->getPonto(Ponto::COMPLEMENTAR);
        $oPontoComplementar->carregarRegistros();

        /**
         * Rubrica
         */
        $oRubrica = RubricaRepository::getInstanciaByCodigo($r90_rubric);

        /**
         * Remove rubrica do ponto
         */
        $oPontoComplementar->removerRegistro( $oRubrica );


        /**
         * Remove rubrica da rhhistoricoponto
         */
        if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

          $oHistoricoPontoComplementar = FolhaPagamentoComplementar::getFolhaAberta();
          $oHistoricoPontoComplementar->excluirRubricaHistoricoPonto($r90_regist, $r90_rubric);

        }

        /**
         * Salva alteracoes
         */
        $oPontoComplementar->gerar();

        db_msgbox('Exclusão efetuada com sucesso');

      } catch (Exception $oErro) {

        $erro_msg = $oErro->getMessage();
        $sqlerro  = true;
      }

    }

    if(isset($pontoexcluir)){
      $excluir = "excluir";
    }

    db_fim_transacao($sqlerro);

} elseif ( isset($opcao) ) {

  if($opcao == "alterar"){
    $db_opcao = 2;
  }else if($opcao == "excluir"){
    $db_opcao = 3;
  }

  ///////////////////////////////////////////////////
  // Rotina para buscar os dados do ponto selecionado
  ///////////////////////////////////////////////////

  if($ponto == "fx" || $ponto == "Rfx"){
    $sigla = "r90_";
    $campoextra = ", r90_datlim as r90_datlim ";
    $whereextra = "";
  }else if($ponto == "fs" || $ponto == "Rfs"){
    $sigla = "r10_";
    $campoextra = ", r10_datlim as r90_datlim ";
    $whereextra = "";
  }else if($ponto == "fa" || $ponto == "Rfa"){
    $sigla = "r21_";
    $campoextra = "";
    $whereextra = "";
  }else if($ponto == "fe" || $ponto == "Rfe"){
    $sigla = "r29_";
    $campoextra = ", r29_tpp";
    $whereextra = " and r29_tpp = '$r29_tpp' ";
  }else if($ponto == "fr" || $ponto == "Rfr"){
    $sigla = "r19_";
    $campoextra = ", r19_tpp as r29_tpp";
    $whereextra = " and r19_tpp = '$r29_tpp' ";
  }else if($ponto == "f13" || $ponto == "Rf13"){
    $sigla = "r34_";
    $campoextra = "";
    $whereextra = "";

  }else if($ponto == "com" || $ponto == "Rcom"){

    $sigla = "r47_";
    $campoextra = "";
    $whereextra = "";
  }

  $dbwhere = $sigla."regist = $r90_regist and ".$sigla."anousu = $r90_anousu and ".$sigla."mesusu = $r90_mesusu and ".$sigla."rubric = '$r90_rubric' $whereextra and ".$sigla."instit=".db_getsession("DB_instit");
  $campos  = "rh01_regist as r90_regist,z01_nome,rh27_rubric as r90_rubric,rh27_presta,rh27_form,rh27_descr,rh27_limdat, rh27_periodolancamento ".$campoextra.",r70_codigo as r90_lotac,r70_descr,".$sigla."quant as r90_quant,".$sigla."valor as r90_valor";

  // Se for ponto fixo
  if($ponto == "fx" || $ponto == "Rfx"){
     // echo "<BR><BR>".($clpontofx->sql_query_seleciona(null,null,null,null,$campos,"",$dbwhere));
     $result_pontofixo = $clpontofx->sql_record($clpontofx->sql_query_seleciona(null,null,null,null,$campos,"",$dbwhere));
     if($clpontofx->numrows > 0){
        db_fieldsmemory($result_pontofixo,0);
     }
  //////////

  // Se for ponto de salário
  }else if($ponto == "fs" || $ponto == "Rfs"){
    $result_pontosalario = $clpontofs->sql_record($clpontofs->sql_query_seleciona(null,null,null,null,$campos,"",$dbwhere));
    if($clpontofs->numrows > 0){
      db_fieldsmemory($result_pontosalario,0);
    }
  //////////

  // Se for ponto de adiantamento
  }else if($ponto == "fa" || $ponto == "Rfa"){
    $result_pontoadiantamento = $clpontofa->sql_record($clpontofa->sql_query_seleciona(null,null,null,null,$campos,"",$dbwhere));
    if($clpontofa->numrows > 0){
          db_fieldsmemory($result_pontoadiantamento,0);
    }
  //////////

  // Se for ponto de férias
  }else if($ponto == "fe" || $ponto == "Rfe"){
        $result_pontoferias = $clpontofe->sql_record($clpontofe->sql_query_seleciona(null,null,null,null,null,$campos,"",$dbwhere));
        if($clpontofe->numrows > 0){
          db_fieldsmemory($result_pontoferias,0);
    }
  //////////

  // Se for ponto de rescisão
  }else if($ponto == "fr" || $ponto == "Rfr"){
        $result_pontorecisao = $clpontofr->sql_record($clpontofr->sql_query_seleciona(null,null,null,null,null,$campos,"",$dbwhere));
        if($clpontofr->numrows > 0){
          db_fieldsmemory($result_pontorecisao,0);
    }
  //////////

  // Se for ponto de 13o
  }else if($ponto == "f13" || $ponto == "Rf13"){
        $result_pontodecimo = $clpontof13->sql_record($clpontof13->sql_query_seleciona(null,null,null,null,$campos,"",$dbwhere));
        if($clpontof13->numrows > 0){
          db_fieldsmemory($result_pontodecimo,0);
    }
  //////////

  // Se for ponto complementar
  }else if($ponto == "com" || $ponto == "Rcom"){

    $result_pontocomplementar = $clpontocom->sql_record($clpontocom->sql_query_seleciona(null,null,null,null,$campos,"",$dbwhere));
    if($clpontocom->numrows > 0){
      db_fieldsmemory($result_pontocomplementar,0);
    }

  }
  //////////

  if(isset($rh27_form)){
    if(trim($rh27_form) != ""){
      $rh27_form = "t";
    }else{
      $rh27_form = "f";
    }
  }

  /**
   * pesquisa os dados do controle por periodo da Rubrica
   */
  $aPontosRemoverControlePeriodo = array("fx", "fs");
  if (in_array($ponto, $aPontosRemoverControlePeriodo) && $rh27_periodolancamento == 't') {

    $iInstituicao = db_getsession("DB_instit");
    $aWhereRubricas = array("rh183_matricula = $r90_regist", "rh183_rubrica = '$r90_rubric'", "rh183_instituicao = $iInstituicao");
    $sWhereRubricas = implode(" and ", $aWhereRubricas);

    $oDaoPontoRubrica                  = new cl_pontosalariodatalimite();
    $sSqlRubricaTemControle            = $oDaoPontoRubrica->sql_query_file(null, "*", null, $sWhereRubricas);
    $rsRubricaNoPonto                  = db_query($sSqlRubricaTemControle);
    if (!$rsRubricaNoPonto) {

      $erro_msg = "Não foi possível verificar os dados de controle de período da Rubrica {$r90_rubric}";
      $sqlerro  = true;
    }

    $iTotalLinhas = pg_num_rows($rsRubricaNoPonto);
    if ($iTotalLinhas > 0) {

      $oDadosControlePonto = db_utils::fieldsMemory($rsRubricaNoPonto, 0);
      $rh183_datainicio    = $oDadosControlePonto->rh183_datainicio;
      list($rh183_datainicio_ano, $rh183_datainicio_mes, $rh183_datainicio_dia) = explode("-", $rh183_datainicio);
      $rh183_datafim       = $oDadosControlePonto->rh183_datafim;
      list($rh183_datafim_ano, $rh183_datafim_mes, $rh183_datafim_dia) = explode("-", $rh183_datafim);
    }
  }

  ///////////////////////////////////////////////////
  ///////////////////////////////////////////////////
}else if(isset($r90_regist)){

  // Rotina para buscar os dados da matrícula
  $dbwhere = " rh01_regist = $r90_regist and rh02_anousu = $r90_anousu and rh02_mesusu = $r90_mesusu ";
  $result_registro = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm(null,"rh01_regist as r90_regist,rh01_admiss as data_de_admissao,z01_nome,rh02_lota as r90_lotac,r70_descr","",$dbwhere));
  if($clrhpessoal->numrows > 0){
    db_fieldsmemory($result_registro,0);
  }
  ////////////
}

if(isset($incluir) || isset($alterar) || isset($excluir) || isset($pontonovo)){
   if((isset($sqlerro) && $sqlerro == false && !isset($repassa) && !isset($repassa_exclusao)) || isset($pontonovo)){
      $r90_rubric = "";
      $r90_valor  = "";
      $r90_quant  = "";
      if(isset($r90_datlim)){
        $r90_datlim = "";
      }else if(isset($r29_tpp)){
        $r29_tpp    = "";
      }
      $rh27_descr = "";
      $rh27_limdat= "";
   }else if(isset($repassa)){
      if($ponto == "fs" || $ponto == "Rfs"){

        // Testa se rubrica do ponto de salário é do tipo fixo
        $result_rubricastipo = $clrhrubricas->sql_record($clrhrubricas->sql_query_file(null,null,"*","","rh27_instit = ".db_getsession("DB_instit")." and rh27_rubric = '$r90_rubric' and (rh27_tipo = 1 or rh27_limdat = 't') and rh27_instit=".db_getsession("DB_instit")));
        if($clrhrubricas->numrows > 0){
          $alertrepassa = "fx";
          $descricponto = "PONTO FIXO";
          $db_opcao = 22;
        }

      }else if($ponto == "fx" || $ponto == "Rfx"){
        $alertrepassa = "fs";
        $descricponto = "PONTO DE SALÁRIO";
        $db_opcao = 22;
      }
   }
}


$sMatriculas = $r90_regist;

if (isset($r90_regist)) {
  /**
   * Retornamos um array com as matriculas de duplo vinculo, para ser utilizado no cálculo financeiro
   */
  $oDaoRhPessoalMov = new cl_rhpessoalmov();

  $sWhereServidores  = "     rh02_anousu = " . DBPessoal::getAnoFolha();
  $sWhereServidores .= " and rh02_mesusu = " . DBPessoal::getMesFolha();
  $sWhereServidores .= " and rh02_instit = " . db_getsession('DB_instit');
  $sWhereServidores .= " and rh02_regist in ($r90_regist) ";

  $sSqlServidores    = $oDaoRhPessoalMov->sql_query_file(null, null, 'rh02_regist', null, $sWhereServidores);
  $sSqlDuploVinculo  = $oDaoRhPessoalMov->sql_servidores_duplo_vinculo($sSqlServidores, DBPessoal::getAnoFolha(),DBPessoal::getMesFolha());
  $rsSqlDuplaVincula = db_query($sSqlDuploVinculo);

  if (pg_num_rows($rsSqlDuplaVincula) > 0) {

    $aMatriculas = pg_fetch_all_columns($rsSqlDuplaVincula, 0);
    $aMatriculas[] = $r90_regist;
    $sMatriculas = implode(',', $aMatriculas);
    $sMatriculas = empty($sMatriculas) ? $oGet->r90_regist : $sMatriculas;
  }
}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="<?=(isset($r90_regist)?(isset($campofoco)?"document.form1.".$campofoco.".focus();":"document.form1.r90_rubric.focus();"):"document.form1.r90_regist.focus();")?>" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <?
  include(modification("forms/db_frmpontofx.php"));

  if(!isset($oGet->lConsulta)){
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  }
?>
</body>
</html>


<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if(isset($sqlerro) && $sqlerro == true){
    db_msgbox($erro_msg);
  }
  if(!isset($alertconfirma) && !isset($alertrepassa) && isset($sqlerro) && $sqlerro == false && !isset($repassa_exclusao)){
  echo "
         <script>
           document.form1.r90_rubric.value = '';
           document.form1.r90_valor.value  = '0';
           document.form1.r90_quant.value  = '0';
           if ( document.form1.r90_datlim ) {
             document.form1.r90_datlim.value = '';
           }
           document.form1.rh27_descr.value = '';
           if ( document.form1.rh27_limdat ) {
             document.form1.rh27_limdat.value= '';
           }
         </script>
    ";
  }
};
if((isset($sqlerro) && $sqlerro == false) || !isset($sqlerro)){
  if(isset($alertconfirma)){
  // Pergunta se o usuário quer ou não somar o valor e quantidade informados
  // com os já existentes para a matrícula
  echo "
         <script>
           confirmado = 'false';
           if(confirm('Rubrica $r90_rubric ($rh27_descr) já cadastrada para a matrícula $r90_regist ($z01_nome). Deseja somar a mesma ao valor e quantidade informados? \\n\\nClique em OK para somar ou Cancelar para substituir os dados.')){
             confirmado = 'true';
           }
           obj = document.createElement('input');
           obj.setAttribute('name','confirmado');
           obj.setAttribute('type','hidden');
           obj.setAttribute('value',confirmado);
           document.form1.appendChild(obj);
           document.form1.submit();
         </script>
    ";
  }

  /**
   * Se for cadastro ponto fixo, pergunta se o usuário quer
   * repassar rubrica, valor e quantidade para o ponto de salário...
   *
   * Se for cadastro ponto de salário e a rubrica for do tipo fixo,
   * pergunta se o usuário quer repassar rubrica, valor e quantidade
   * para o ponto fixo.
   */
  if (isset($alertrepassa)) {

    /**
     * Verifica se não é um ponto suplementar.
     */
    if (!FolhaPagamentoSuplementar::hasFolha()) {

      echo "
        <script>
          if (confirm('Deseja repassar para {$descricponto}?')) {

            obj = document.createElement('input');
            obj.setAttribute('name','pontonovo');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value','$alertrepassa');
            document.form1.appendChild(obj);
            document.form1.submit();
          } else {

              document.form1.r90_rubric.value  = '';
              document.form1.r90_valor.value   = '0';
              document.form1.r90_quant.value   = '0';
              document.form1.r90_datlim.value  = '';
              document.form1.rh27_descr.value  = '';
              document.form1.rh27_limdat.value = '';
              document.form1.submit();
          }
        </script>";
    } else {

      echo "
        <script>
          document.form1.r90_rubric.value  = '';
          document.form1.r90_valor.value   = '0';
          document.form1.r90_quant.value   = '0';
          document.form1.r90_datlim.value  = '';
          document.form1.rh27_descr.value  = '';
          document.form1.rh27_limdat.value = '';
          document.form1.submit();
        </script>";
    }
  }

  /**
   * Se for exclusão de ponto de salário e a rubrica for do tipo
   * fixo, pergunta se o usuário quer exluir também do ponto fixo.
   */
  if (isset($excluir) && isset($repassa_exclusao)) {

    $descrponto = "";

    if ($repassa_ponto == "fx") {
      $descrponto = "PONTO FIXO";
    } elseif ($repassa_ponto == "fs") {

      $descrponto = "PONTO DE SALÁRIO";
    }

    /**
     * Verifica se não é um ponto suplementar.
     */
    if (!FolhaPagamentoSuplementar::hasFolha()) {

      echo "
        <script>
          if (confirm('Deseja repassar exclusão para {$descrponto}?')) {

            obj = document.createElement('input');
            obj.setAttribute('name','pontoexcluir');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value','{$repassa_ponto}');
            document.form1.appendChild(obj);
            document.form1.submit();
          } else {

            document.form1.r90_rubric.value = '';
            document.form1.r90_valor.value  = '0';
            document.form1.r90_quant.value  = '0';
            document.form1.r90_datlim.value = '';
            document.form1.rh27_descr.value = '';
            document.form1.rh27_limdat.value= '';
            document.form1.submit();
          }
        </script>";
    } else {

      echo "
        <script>
          document.form1.r90_rubric.value = '';
          document.form1.r90_valor.value  = '0';
          document.form1.r90_quant.value  = '0';
          document.form1.r90_datlim.value = '';
          document.form1.rh27_descr.value = '';
          document.form1.rh27_limdat.value= '';
          document.form1.submit();
        </script>";
    }
  }
}

if (isset($oGet->lConsulta)) {
  echo $sFechar;
}
