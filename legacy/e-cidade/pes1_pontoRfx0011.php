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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_rhpessoal_classe.php"));
require_once(modification("classes/db_pessoal_classe.php"));
require_once(modification("classes/db_pontofx_classe.php"));
require_once(modification("classes/db_pontofs_classe.php"));
require_once(modification("classes/db_pontofa_classe.php"));
require_once(modification("classes/db_pontofe_classe.php"));
require_once(modification("classes/db_pontofr_classe.php"));
require_once(modification("classes/db_pontof13_classe.php"));
require_once(modification("classes/db_pontocom_classe.php"));
require_once(modification("classes/db_rhrubricas_classe.php"));
require_once(modification("classes/db_lotacao_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

require_once modification("libs/db_utils.php");
require_once modification("model/pessoal/ServidorRepository.model.php");

db_postmemory($_POST);
db_postmemory($_GET);

$clrhpessoal  = new cl_rhpessoal;
$clpessoal    = new cl_pessoal;
$clpontofx    = new cl_pontofx;
$clpontofs    = new cl_pontofs;
$clpontofa    = new cl_pontofa;
$clpontofe    = new cl_pontofe;
$clpontofr    = new cl_pontofr;
$clpontof13   = new cl_pontof13;
$clpontocom   = new cl_pontocom;
$clrhrubricas = new cl_rhrubricas;
$cllotacao    = new cl_lotacao;
$db_opcao     = 1;
$db_botao     = true;

if(isset($ponto)){
  $ponto = strtolower($ponto);
}

// Se variáveis anouso e mesusu não existem, ele pegará as variáveis atuais da folha
if(!isset($r90_anousu)){
  $r90_anousu = db_anofolha();
}
if(!isset($r90_mesusu)){
  $r90_mesusu = db_mesfolha();
}
////////////

if(isset($incluir)) {

  // dump($_POST, $_GET);

  db_inicio_transacao();
  $sqlerro = false;

  $repassar_rubricas_com_replacer = str_replace("chk_","",$repassar_rubricas);

  $arr_rubricas_que_serao_incluid = split(",",$rubricas_selecionadas_enviar);
  $arr_rubricas_que_serao_repassa = split(",",$repassar_rubricas_com_replacer);
  $arr_rubricas_qtd_serao_incluid = split(",",$quantidade_rubricas_selecionadas_enviar);
  $arr_rubricas_val_serao_incluid = split(",",$valores_rubricas_selecionadas_enviar);
  $arr_rubricas_dat_serao_incluid = split(",",$datlim_rubricas_selecionadas_enviar);
  $arr_rubricas_tpp_serao_incluid = split(",",$tpp_rubricas_selecionadas_enviar);
  $arr_rubricas_opc_serao_incluid = split(",",$opcoes_rubricas);
  $lotacao_matricula_sera_incluid = $lotacao_matricula;

  $q = count($arr_rubricas_que_serao_incluid);

  for ($i=0; $i<$q; $i++) {

    $rubrica_cod_corrente = $arr_rubricas_que_serao_incluid[$i];
    $rubrica_qtd_corrente = $arr_rubricas_qtd_serao_incluid[$i];
    $rubrica_val_corrente = $arr_rubricas_val_serao_incluid[$i];
    $rubrica_opc_corrente = $arr_rubricas_opc_serao_incluid[$i];

    $quant_em_branco = false;
    $valor_em_branco = false;
    if(trim($rubrica_qtd_corrente) == ""){
      $quant_em_branco = true;
      $rubrica_qtd_corrente = 0;
    }
    if(trim($rubrica_val_corrente) == ""){
      $valor_em_branco = false;
      $rubrica_val_corrente = 0;
    }

    if(isset($arr_rubricas_dat_serao_incluid[$i])){
      $rubrica_dat_corrente = $arr_rubricas_dat_serao_incluid[$i];
    }
    if(isset($arr_rubricas_tpp_serao_incluid[$i])){
      $rubrica_tpp_corrente = $arr_rubricas_tpp_serao_incluid[$i];
    }

    if(trim($rubrica_dat_corrente) == "#"){
      $rubrica_dat_corrente = "";
    }


    // Rotina que verifica se a rubrica será repassada ou não
    $repassa = false;
    if(in_array($rubrica_cod_corrente,$arr_rubricas_que_serao_repassa)){
      $repassa = true;
    }

    $sSql = $clrhrubricas->sql_query_file($rubrica_cod_corrente, db_getsession('DB_instit'), "rh27_form,rh27_limdat as limdata_testa, rh27_propq as proporcionalizar");
    $result_verifica_rubrica_com_formula = db_query($sSql);

    if ( !$result_verifica_rubrica_com_formula ) {

      $sqlerro = true;
      $erro_msg  = "Erro ao buscar dados da rubrica: ".$rubrica_cod_corrente;
    }

    if (pg_num_rows($result_verifica_rubrica_com_formula) > 0) {

      db_fieldsmemory($result_verifica_rubrica_com_formula, 0);
      if ($limdata_testa == "t") {

        if (isset($r90_datlim) && $r90_datlim == "") {

          $sqlerro   = true;
          $erro_msg  = "Rubrica ".$rubrica_cod_corrente.": \nAno/Mês não informado";
          $campofoco = "datlim_".$rubrica_cod_corrente;
          break;
        }
      } else if($limdata_testa == "f") {
        $rubrica_dat_corrente = "";
      } else if(trim($rh27_form) != "") {

        if($rubrica_val_corrente != 0 && $rubrica_qtd_corrente == 0){
          $sqlerro = true;
          $erro_msg = "Rubrica ".$rubrica_cod_corrente.": \nQuantidade não informada";
          $campofoco = "quant_".$rubrica_cod_corrente;
          break;
        }
      }else{
        if($rubrica_qtd_corrente != 0 && $rubrica_val_corrente == 0){
          $sqlerro = true;
          $erro_msg = "Rubrica ".$rubrica_cod_corrente.": \nValor não informado";
          $campofoco = "valor_".$rubrica_cod_corrente;
          break;
        }
      }
    }

    $pontoteste = 0;
    if($ponto == "fx" || $ponto == "Rfx" || $ponto == "fs" || $ponto == "Rfs"){

      if($ponto == "fx" || $ponto == "Rfx" || $repassa == true){

        $opcaovalor = 0;
        $opcaoquant = 0;
        // Rotina que verifica se já existe, na tabela PONTOFX, algum registro com o mesmo anousu, mesusu e rubrica
        $result_pontofixo = $clpontofx->sql_record($clpontofx->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente,"r90_valor as opcaovalor,r90_quant as opcaoquant"));
        if($clpontofx->numrows > 0){
          db_fieldsmemory($result_pontofixo,0);
          $clpontofx->excluir($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente);
          if($clpontofx->erro_status==0){
            $erro_msg = $clpontofx->erro_msg;
            $sqlerro=true;
            break;
          }
        }
        //////////

        // Rotina que incluirá na tabela PONTOFX caso a quantidade e o valor seja diferente de zero
        if ( $rubrica_qtd_corrente != 0 || $rubrica_val_corrente != 0 ) {
          $clpontofx->r90_anousu = $r90_anousu;
          $clpontofx->r90_mesusu = $r90_mesusu;
          $clpontofx->r90_regist = $r90_regist;
          $clpontofx->r90_rubric = $rubrica_cod_corrente;
          $val_corrente          = $rubrica_val_corrente;
          $qtd_corrente          = $rubrica_qtd_corrente;
          if($rubrica_opc_corrente == "so"){
            $val_corrente = ($opcaovalor + $rubrica_val_corrente);
            $qtd_corrente = ($opcaoquant + $rubrica_qtd_corrente);
          }else if($rubrica_opc_corrente == "su"){
            $val_corrente = ($opcaovalor - $rubrica_val_corrente);
            $qtd_corrente = ($opcaoquant - $rubrica_qtd_corrente);
          }
          if($val_corrente > 0 || $qtd_corrente > 0){
            $clpontofx->r90_valor  = "round($val_corrente,2)";
            $clpontofx->r90_quant  = "$qtd_corrente";
            $clpontofx->r90_datlim = $rubrica_dat_corrente;
            $clpontofx->r90_lotac  = $lotacao_matricula_sera_incluid;
            $clpontofx->r90_instit = db_getsession("DB_instit");
            $clpontofx->incluir($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente);
            $erro_msg = $clpontofx->erro_msg;
            if($clpontofx->erro_status==0){
              $sqlerro=true;
              break;
            }
          }
        }
      }

      if($ponto == "fs" || $ponto == "Rfs" || $repassa == true){

        if($ponto == "fx" || $ponto == "Rfx"){
          $arr_ano_mes_usu = split("-",$admissa_matricula);
          $ano_da_admissao = $arr_ano_mes_usu[0];
          $mes_da_admissao = $arr_ano_mes_usu[1];
          $dia_da_admissao = $arr_ano_mes_usu[2] - 1;
          if($mes_da_admissao == db_mesfolha() && $ano_da_admissao == db_anofolha() && $proporcionalizar == 't'){
            // Só proporcionalizar na admissao se a rubrica estiver assim definida
            $dia_recebe_mens = 30;
            $dias_a_pagar    = $dia_recebe_mens - $dia_da_admissao;

            if($quant_em_branco == false){
              $rubrica_qtd_corrente = ($rubrica_qtd_corrente / 30) * $dias_a_pagar;
            }
            if($valor_em_branco == false){
              $rubrica_val_corrente = ($rubrica_val_corrente / 30) * $dias_a_pagar;
            }
          }
        }

        $opcaovalor = 0;
        $opcaoquant = 0;
        // Rotina que verifica se já existe, na tabela PONTOFS, algum registro com o mesmo anousu, mesusu e rubrica
        $result_pontosalario = $clpontofs->sql_record($clpontofs->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente,"r10_valor as opcaovalor,r10_quant as opcaoquant"));
        if ($clpontofs->numrows > 0) {

          db_fieldsmemory($result_pontosalario,0);
          $clpontofs->excluir($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente);
          if($clpontofs->erro_status==0){
            $erro_msg = $clpontofs->erro_msg;
            $sqlerro=true;
            break;
          }
        }

        // Rotina que incluirá na tabela PONTOFS caso a quantidade e o valor seja diferente de zero
        if($rubrica_qtd_corrente != 0 || $rubrica_val_corrente != 0){
          $clpontofs->r10_anousu = $r90_anousu;
          $clpontofs->r10_mesusu = $r90_mesusu;
          $clpontofs->r10_regist = $r90_regist;
          $clpontofs->r10_rubric = $rubrica_cod_corrente;
          $val_corrente = $rubrica_val_corrente;
          $qtd_corrente = $rubrica_qtd_corrente;
          if ($rubrica_opc_corrente == "so") {

            $val_corrente = ($opcaovalor + $rubrica_val_corrente);
            $qtd_corrente = ($opcaoquant + $rubrica_qtd_corrente);
          } else if($rubrica_opc_corrente == "su") {
            $val_corrente = ($opcaovalor - $rubrica_val_corrente);
            $qtd_corrente = ($opcaoquant - $rubrica_qtd_corrente);
          }
          if($val_corrente > 0 || $qtd_corrente > 0){
            $clpontofs->r10_valor  = "round($val_corrente,2)";
            $clpontofs->r10_quant  = "$qtd_corrente";
            $clpontofs->r10_datlim = $rubrica_dat_corrente;
            $clpontofs->r10_lotac  = $lotacao_matricula_sera_incluid;
            $clpontofs->r10_instit = db_getsession("DB_instit");
            $clpontofs->incluir($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente);
            $erro_msg = $clpontofs->erro_msg;
            if($clpontofs->erro_status==0){
              $sqlerro=true;
              break;
            }
          }
        }
        //////////

      }

    }else if($ponto == "fa" || $ponto == "Rfa"){

      $opcaovalor = 0;
      $opcaoquant = 0;
      // Rotina que verifica se já existe, na tabela PONTOFA, algum registro com o mesmo anousu, mesusu e rubrica
      $result_pontoadiantamento = $clpontofa->sql_record($clpontofa->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente,"r21_valor as opcaovalor,r21_quant as opcaoquant"));
      if($clpontofa->numrows > 0){
	db_fieldsmemory($result_pontoadiantamento, 0);
        if($rubrica_opc_corrente == "so"){
          $rubrica_val_corrente = ($opcaovalor + $rubrica_val_corrente);
          $rubrica_qtd_corrente = ($opcaoquant + $rubrica_qtd_corrente);
        }else if($rubrica_opc_corrente == "su"){
          $rubrica_val_corrente = ($opcaovalor - $rubrica_val_corrente);
          $rubrica_qtd_corrente = ($opcaoquant - $rubrica_qtd_corrente);
        }
        $clpontofa->excluir($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente);
        if($clpontofa->erro_status==0){
          $erro_msg = $clpontofa->erro_msg;
          $sqlerro=true;
          break;
        }
      }
      //////////

      // Rotina que incluirá na tabela PONTOFS caso a quantidade e o valor seja diferente de zero
      if($rubrica_qtd_corrente != 0 || $rubrica_val_corrente != 0){
        $clpontofa->r21_anousu = $r90_anousu;
        $clpontofa->r21_mesusu = $r90_mesusu;
        $clpontofa->r21_regist = $r90_regist;
        $clpontofa->r21_rubric = $rubrica_cod_corrente;
        $clpontofa->r21_valor  = "round($rubrica_val_corrente,2)";
        $clpontofa->r21_quant  = "$rubrica_qtd_corrente";
        $clpontofa->r21_lotac  = $lotacao_matricula_sera_incluid;
        $clpontofa->r21_instit = db_getsession("DB_instit");
        $clpontofa->incluir($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente);
        $erro_msg = $clpontofa->erro_msg;
        if($clpontofa->erro_status==0){
          $sqlerro=true;
          break;
        }
      }
      //////////

    }else if($ponto == "f13" || $ponto == "Rf13"){

      $opcaovalor = 0;
      $opcaoquant = 0;
      // Rotina que verifica se já existe, na tabela PONTOF13, algum registro com o mesmo anousu, mesusu e rubrica
      $result_pontodecimo = $clpontof13->sql_record($clpontof13->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente,"r34_valor as opcaovalor,r34_quant as opcaoquant"));
      if($clpontof13->numrows > 0){
        db_fieldsmemory($result_pontodecimo, 0);
        if($rubrica_opc_corrente == "so"){
          $rubrica_val_corrente = ($opcaovalor + $rubrica_val_corrente);
          $rubrica_qtd_corrente = ($opcaoquant + $rubrica_qtd_corrente);
        }else if($rubrica_opc_corrente == "su"){
          $rubrica_val_corrente = ($opcaovalor - $rubrica_val_corrente);
          $rubrica_qtd_corrente = ($opcaoquant - $rubrica_qtd_corrente);
        }
        $clpontof13->excluir($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente);
        if($clpontof13->erro_status==0){
          $erro_msg = $clpontof13->erro_msg;
          $sqlerro=true;
          break;
        }
      }
      //////////

      // Rotina que incluirá na tabela PONTOFS caso a quantidade e o valor seja diferente de zero
      if($rubrica_qtd_corrente != 0 || $rubrica_val_corrente != 0){
        $clpontof13->r34_anousu = $r90_anousu;
        $clpontof13->r34_mesusu = $r90_mesusu;
        $clpontof13->r34_regist = $r90_regist;
        $clpontof13->r34_rubric = $rubrica_cod_corrente;
        $clpontof13->r34_valor  = "round($rubrica_val_corrente,2)";
        $clpontof13->r34_quant  = "$rubrica_qtd_corrente";
        $clpontof13->r34_lotac  = $lotacao_matricula_sera_incluid;
        $clpontof13->r34_instit = db_getsession("DB_instit");
        $clpontof13->r34_media  = "0";
        $clpontof13->r34_calc   = "0";
        $clpontof13->incluir($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente);
        $erro_msg = $clpontof13->erro_msg;
        if($clpontof13->erro_status==0){
          $sqlerro=true;
          break;
        }
      }
      //////////

    }else if($ponto == "com" || $ponto == "Rcom"){

      $opcaovalor = 0;
      $opcaoquant = 0;

      $result_pontocomplementar = $clpontocom->sql_record($clpontocom->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente,"r47_valor as opcaovalor,r47_quant as opcaoquant"));

      if ($clpontocom->numrows > 0) {

        db_fieldsmemory($result_pontocomplementar,0);

        if($rubrica_opc_corrente == "so"){

          $rubrica_val_corrente = ($opcaovalor + $rubrica_val_corrente);
          $rubrica_qtd_corrente = ($opcaoquant + $rubrica_qtd_corrente);

        }else if($rubrica_opc_corrente == "su"){

          $rubrica_val_corrente = ($opcaovalor - $rubrica_val_corrente);
          $rubrica_qtd_corrente = ($opcaoquant - $rubrica_qtd_corrente);
        }
      }

      /**
       * Rotina que incluirá na tabela PONTOCOM caso a quantidade e o valor seja diferente de zero
       */
      if ($rubrica_qtd_corrente != 0 || $rubrica_val_corrente != 0) {

        try {

          /**
           * Cria servidor
           */
          $oServidor = ServidorRepository::getInstanciaByCodigo( $r90_regist, $r90_anousu, $r90_mesusu );
          $oPontoComplementar = $oServidor->getPonto(Ponto::COMPLEMENTAR);
          $oPontoComplementar->carregarRegistros();

          $oRubrica = RubricaRepository::getInstanciaByCodigo($rubrica_cod_corrente);

          $oRegistro = new RegistroPonto();
          $oRegistro->setServidor($oServidor);
          $oRegistro->setRubrica($oRubrica);
          $oRegistro->setQuantidade("$rubrica_qtd_corrente");
          $oRegistro->setValor( round($rubrica_val_corrente, 2) );
          $oPontoComplementar->adicionarRegistro($oRegistro);
          $oPontoComplementar->gerar();

          $erro_msg = 'Alteração efetuada com sucesso.';

        } catch ( Exception $oErro ) {

          $erro_msg = $oErro->getMessage();
          $sqlerro  = true;
          break;
        }
      }
      //////////


    // ESTE PROGRAMA NÃO USA FR E NEM FE, MAS DEIXEI O CÓDIGO PREVENDO UM DIA SER NECESSÁRIO
    }else if($ponto == "fe" || $ponto == "Rfe"){

      $opcaovalor = 0;
      $opcaoquant = 0;
      // Rotina que verifica se já existe, na tabela PONTOFE, algum registro com o mesmo anousu, mesusu, rubrica e tipo
      $result_pontoferias = $clpontofe->sql_record($clpontofe->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente,$rubrica_tpp_corrente,"r29_valor as opcaovalor,r29_quant as opcaoquant"));
      if($clpontofe->numrows > 0){
        db_fieldsmemory($result_pontoferias, 0);
        if($rubrica_opc_corrente == "so"){
          $rubrica_val_corrente = ($opcaovalor + $rubrica_val_corrente);
          $rubrica_qtd_corrente = ($opcaoquant + $rubrica_qtd_corrente);
        }else if($rubrica_opc_corrente == "su"){
          $rubrica_val_corrente = ($opcaovalor - $rubrica_val_corrente);
          $rubrica_qtd_corrente = ($opcaoquant - $rubrica_qtd_corrente);
        }
        $clpontofe->excluir($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente,$rubrica_tpp_corrente);
        if($clpontofa->erro_status==0){
          $erro_msg = $clpontofe->erro_msg;
          $sqlerro=true;
          break;
        }
      }
      //////////

      // Rotina que incluirá na tabela PONTOFS caso a quantidade e o valor seja diferente de zero
      if($rubrica_qtd_corrente != 0 || $rubrica_val_corrente != 0){
        $clpontofe->r29_anousu = $r90_anousu;
        $clpontofe->r29_mesusu = $r90_mesusu;
        $clpontofe->r29_regist = $r90_regist;
        $clpontofe->r29_rubric = $rubrica_cod_corrente;
        $clpontofe->r29_tpp    = $rubrica_tpp_corrente;
        $clpontofe->r29_valor  = "round($rubrica_val_corrente,2)";
        $clpontofe->r29_quant  = "$rubrica_qtd_corrente";
        $clpontofe->r29_lotac  = $lotacao_matricula_sera_incluid;
        $clpontofe->r29_instit = db_getsession("DB_instit");
        $clpontofe->r29_media  = "0";
        $clpontofe->r29_calc   = "0";
        $clpontofe->incluir($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente,$rubrica_tpp_corrente);
        $erro_msg = $clpontofe->erro_msg;
        if($clpontofe->erro_status==0){
          $sqlerro=true;
          break;
        }
      }
      //////////

    }else if($ponto == "fr" || $ponto == "Rfr"){

      $opcaovalor = 0;
      $opcaoquant = 0;
      // Rotina que verifica se já existe, na tabela PONTOFR, algum registro com o mesmo anousu, mesusu, rubrica e tipo
      $result_pontorescisao= $clpontofr->sql_record($clpontofr->sql_query_seleciona($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente,$rubrica_tpp_corrente,"r19_valor as opcaovalor,r19_quant as opcaoquant"));
      if($clpontofr->numrows > 0){
	db_fieldsmemory($result_pontorescisao, 0);
        if($rubrica_opc_corrente == "so"){
          $rubrica_val_corrente = ($opcaovalor + $rubrica_val_corrente);
          $rubrica_qtd_corrente = ($opcaoquant + $rubrica_qtd_corrente);
        }else if($rubrica_opc_corrente == "su"){
          $rubrica_val_corrente = ($opcaovalor - $rubrica_val_corrente);
          $rubrica_qtd_corrente = ($opcaoquant - $rubrica_qtd_corrente);
        }
        $clpontofr->excluir($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente,$rubrica_tpp_corrente);
        if($clpontofr->erro_status==0){
          $erro_msg = $clpontofr->erro_msg;
          $sqlerro=true;
          break;
        }
      }
      //////////

      // Rotina que incluirá na tabela PONTOFS caso a quantidade e o valor seja diferente de zero
      if($rubrica_qtd_corrente != 0 || $rubrica_val_corrente != 0){
        $clpontofr->r19_anousu = $r90_anousu;
        $clpontofr->r19_mesusu = $r90_mesusu;
        $clpontofr->r19_regist = $r90_regist;
        $clpontofr->r19_tpp    = $rubrica_tpp_corrente;
        $clpontofr->r19_rubric = $rubrica_cod_corrente;
        $clpontofr->r19_valor  = "round($rubrica_val_corrente,2)";
        $clpontofr->r19_quant  = "$rubrica_qtd_corrente";
        $clpontofr->r19_lotac  = $lotacao_matricula_sera_incluid;
        $clpontofr->r19_instit = db_getsession("DB_instit");
        $clpontofr->incluir($r90_anousu,$r90_mesusu,$r90_regist,$rubrica_cod_corrente,$rubrica_tpp_corrente);
        $erro_msg = $clpontofr->erro_msg;
        if($clpontofr->erro_status==0){
          $sqlerro=true;
          break;
        }
      }
      //////////
    }
  }

  if($sqlerro == false){
    unset($r90_regist,$z01_nome);
  }

  $sMsgPos = "";
  if (!$sqlerro){
    $sMsgPos = 'Inclusão efetuada com sucesso.';
  }

  db_fim_transacao($sqlerro);

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="javascript" type="text/javascript" src="scripts/strings.js"></script>
<script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.r90_regist.focus();" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<?php

if (!empty($sMsgPos)){
  db_msgbox($sMsgPos);
}

include(modification("forms/db_frmpontoRfx0011.php"));

?>

<?php db_menu(); ?>
</body>
</html>
<script type="text/javascript">

<?php

if ( isset($incluir) ) {



  if ( isset($sqlerro) && $sqlerro == true) {

    $erro_msg = str_replace("\n","\\n",$erro_msg);

    echo "alert('$erro_msg');";

    if ( isset($campofoco) ) {

      echo "function js_seleciona_campo_confirma() {                      ";
      echo "  rubricas_dados_enviar.document.form1.$campofoco.focus();    ";
      echo "  clearInterval(time);                                        ";
      echo "}                                                             ";
      echo "time = setInterval(js_seleciona_campo_confirma,10);           ";
    }

  }
}
?>
</script>