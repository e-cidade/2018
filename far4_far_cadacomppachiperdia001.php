<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$oIframeAE                   = new cl_iframe_alterar_excluir();
$oDaoFarCadAcompPacHiperdia  = db_utils::getdao('far_cadacomppachiperdia');
$oDaoSauTriagemAvulsa        = db_utils::getdao('sau_triagemavulsa');
$oDaoFarCbos                 = db_utils::getdao('far_cbos');
$oDaoFarCbosProfissional     = db_utils::getdao('far_cbosprofissional');
$oDaoFarComplicacoes         = db_utils::getdao('far_complicacoes');
$oDaoFarMedicamentoHiperdia  = db_utils::getdao('far_medicamentohiperdia');
$oDaoFarExames               = db_utils::getdao('far_exames');
$oDaoCgsUnd                  = db_utils::getdao('cgs_und');
$oDaoCgsFatorDeRisco         = db_utils::getdao('cgsfatorderisco');
$oDaoFarRetiradaCadAcomp     = db_utils::getdao('far_retiradacadacomp');
$oDaoFarMedicamentoCadAcomp  = db_utils::getdao('far_medicamentocadacomp');
$oDaoFarComplicacoesCadAcomp = db_utils::getdao('far_complicacoescadacomp');
$oDaoFarExamesAcomp          = db_utils::getdao('far_examesacomp');
$oDaoDbUsuaCgm               = db_utils::getdao('db_usuacgm');
$oDaoMedicos                 = db_utils::getdao('medicos');

$db_opcao                    = 1;
$db_botao                    = true;

// variável de alteração / exclusão
if (isset($opcao)) {
  
  $db_opcao2 = 3;
  if ($opcao == 'alterar') {
    $db_opcao = 2;
  } else {
    $db_opcao = 3;
  }

}

// Carrega campos para a memória para as rotinas de alteração / exclusão
if (isset($chavepesquisa) && !isset($incluir) && !isset($alterar) && !isset($excluir)) {

  $aComplicAltExc = array();
  $aExamesAltExc  = array();

  // Busco as informações da triagem e do cadastro / acompanhamento
  $sCampos        = 'sau_triagemavulsa.*, far_cadacomppachiperdia.*, sd03_i_codigo, ';
  $sCampos       .= 'z01_nome, sd04_i_unidade, sd04_i_codigo, descrdepto, fa53_i_codigo ';
           
	$sSql           = $oDaoFarCadAcompPacHiperdia->sql_query2(null, $sCampos, ' s152_i_codigo desc ',
                                                            ' fa50_i_codigo = '.$chavepesquisa
                                                           );
  $rs             = $oDaoFarCadAcompPacHiperdia->sql_record($sSql);

  db_fieldsmemory($rs, 0);

  // Busco as complicacoes
	$sSql = $oDaoFarComplicacoesCadAcomp->sql_query_file(null, 'fa52_i_complicacao', '',
                                                       ' fa52_i_cadacomp = '.$fa50_i_codigo
                                                      );
  $rs   = $oDaoFarComplicacoesCadAcomp->sql_record($sSql);
  for ($iCont = 0; $iCont < $oDaoFarComplicacoesCadAcomp->numrows; $iCont++) {
    
    $oDados                 = db_utils::fieldsmemory($rs, $iCont);
    $aComplicAltExc[$iCont] = $oDados->fa52_i_complicacao;

  }

  // Busco os exames
	$sSql = $oDaoFarExamesAcomp->sql_query_file(null, 'fa48_i_exame', '',
                                              ' fa48_i_acompanhamento = '.$fa50_i_codigo
                                             );
  $rs   = $oDaoFarExamesAcomp->sql_record($sSql);
  for ($iCont = 0; $iCont < $oDaoFarExamesAcomp->numrows; $iCont++) {
    
    $oDados                = db_utils::fieldsmemory($rs, $iCont);
    $aExamesAltExc[$iCont] = $oDados->fa48_i_exame;

  }

}

if (!isset($chavepesquisa) && !isset($incluir) && !isset($alterar) && !isset($excluir)) {

  $sSql = $oDaoDbUsuaCgm->sql_query(null, 'nome, cgmlogin', '', 
                                    ' db_usuacgm.id_usuario = '.db_getsession('DB_id_usuario')
                                   );
  $rs   = $oDaoDbUsuaCgm->sql_record($sSql);
 
  if ($oDaoDbUsuaCgm->numrows > 0) {
 
    $oDados   = db_utils::fieldsmemory($rs, 0);

    $sCampos  = 'sd03_i_codigo, sd04_i_codigo, (select fa54_i_cbos from far_cbosprofissional ';
    $sCampos .= 'where far_cbosprofissional.fa54_i_unidademedico = sd04_i_codigo limit 1) as fa54_i_cbos ';
    $sSql     = $oDaoMedicos->sql_query_file(null, $sCampos, '', 
                                             ' sd03_i_cgm = '.$oDados->cgmlogin.
                                             'and sd04_i_unidade = '.db_getsession('DB_coddepto')
                                            );
    $rs       = $oDaoMedicos->sql_record($sSql);
   
    if ($oDaoMedicos->numrows > 0) {

      $z01_nome       = $oDados->nome;
      $oDados         = db_utils::fieldsmemory($rs, 0);
      $sd03_i_codigo  = $oDados->sd03_i_codigo;
      $sd04_i_codigo  = $oDados->sd04_i_codigo;
      $fa53_i_codigo  = empty($oDados->fa54_i_cbos) ? '' : $oDados->fa54_i_cbos;
      $sd04_i_unidade = db_getsession('DB_coddepto');
      $descrdepto     = db_getsession('DB_nomedepto');
                         
    }

  }

}

if (!isset($lBuscaCgs) || isset($iCgs)) {

  //Indica se o paciente já foi ou não cadastrado (possui registros na tabela far_cadacomppachiperdia)
  $lCadastrado = false; 
  $sSql        = $oDaoFarCadAcompPacHiperdia->sql_query_file(null, 'fa50_i_codigo', '', ' fa50_i_cgsund = '.$iCgs);
  $rs          = $oDaoFarCadAcompPacHiperdia->sql_record($sSql);
  if ($oDaoFarCadAcompPacHiperdia->numrows > 0) {
    $lCadastrado     = true; 
  }

  if ($lCadastrado && (!isset($fa50_i_tipo) || $fa50_i_tipo == 2)) {
    $sTituloFieldset = 'Acompanhamento de Paciente do Hiperdia';
  } else {
    $sTituloFieldset = 'Cadastro de Paciente do Hiperdia'; 
  }

  $oDaoFarCadAcompPacHiperdia->erro_status = null; // Retira o status de erro de um possível record vazio

  // Trago o nome do paciente
  $sSql = $oDaoCgsUnd->sql_query_file(null, 'z01_v_nome', '', ' z01_i_cgsund = '.$iCgs);
  $rs   = $oDaoCgsUnd->sql_record($sSql);
  if ($oDaoCgsUnd->numrows == 0) {
    die("<center><big><b>Nenhum paciente informado para realizar o acompanhamento.</b></big></center>"); 
  }
  db_fieldsmemory($rs, 0);
  $fa50_i_cgsund   = $iCgs;
  $fa55_i_retirada = @$iRetirada;

} else {

  $lCadastrado = false; 
  $sTituloFieldset = 'Cadastro de Paciente do Hiperdia';

}

if (isset($incluir) || isset($alterar)) {

  unset($GLOBALS['opcao']);
  $db_opcao = 1;

  db_inicio_transacao();

  /* BLOCO DE VERIFICAÇÃO / INCLUSÃO DO CBOS DO PROFISSIONAL */
  $sSql = $oDaoFarCbosProfissional->sql_query_file(null, 'fa54_i_codigo', '', 
                                                   "fa54_i_unidademedico = $sd04_i_codigo ".
                                                   "and fa54_i_cbos = $fa53_i_codigo"
                                                  );
  $rs   = $oDaoFarCbosProfissional->sql_record($sSql);

  if ($oDaoFarCbosProfissional->numrows > 0) { // o profissional ja possui o código CBOS na unidade

    $oDados                                        = db_utils::fieldsmemory($rs, 0);
    $oDaoSauTriagemAvulsa->s152_i_cbosprofissional = $oDados->fa54_i_codigo;

  } else { // tenho que incluir o código CBOS para o profissional na unidade

    $oDaoFarCbosProfissional->fa54_i_unidademedico = $sd04_i_codigo;
    $oDaoFarCbosProfissional->fa54_i_cbos          = $fa53_i_codigo;
    $oDaoFarCbosProfissional->incluir(null);
    if ($oDaoFarCbosProfissional->erro_status == '0') {

      $oDaoFarCadAcompPacHiperdia->erro_status = '0';
      $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoFarCbosProfissional->erro_msg;

    } else {
      $oDaoSauTriagemAvulsa->s152_i_cbosprofissional = $oDaoFarCbosProfissional->fa54_i_codigo;
    }

  }
  
  /* BLOCO DE INCLUSÃO / ALTERAÇÃO DA TRIAGEM AVULSA */
  if ($oDaoFarCadAcompPacHiperdia->erro_status != '0') {

    if (empty($s152_i_glicemia) || $s152_i_glicemia <= 0) {

       $oDaoSauTriagemAvulsa->s152_i_glicemia                 = '0';
       $oDaoSauTriagemAvulsa->s152_i_alimentacaoexameglicemia = '0';

    }

    if (isset($incluir)) { // INCLUIR TRIAGEM AVULSA

      $oDaoSauTriagemAvulsa->s152_i_cgsund      = $fa50_i_cgsund;
      $oDaoSauTriagemAvulsa->s152_d_datasistema = date('Y-m-d', db_getsession('DB_datausu'));
      $oDaoSauTriagemAvulsa->s152_c_horasistema = date('H:i');
      $oDaoSauTriagemAvulsa->s152_i_login       = db_getsession('DB_id_usuario');
      $oDaoSauTriagemAvulsa->incluir($s152_i_codigo);

    } else { // ALTERAR TRIAGEM AVULSA

      $oDaoSauTriagemAvulsa->s152_i_codigo = $s152_i_codigo;
      $oDaoSauTriagemAvulsa->alterar($s152_i_codigo);

    }

    if ($oDaoSauTriagemAvulsa->erro_status == '0') { // SE OCORRER ERRO NA INSERÇÃO / ATUALIZAÇÃO DA TRIAGEM

      $oDaoFarCadAcompPacHiperdia->erro_status = '0';
      $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoSauTriagemAvulsa->erro_msg;

    }

  }

  /* BLOCO DE INCLUSÃO / ALTERAÇÃO DO CADASTRO / ACOMPANHAMENTO */
  if ($oDaoFarCadAcompPacHiperdia->erro_status != '0') {
   
    $oDaoFarCadAcompPacHiperdia->fa50_i_outrosmedicamentos = isset($ckOutrosMedicamentos) ? 1 : 2;
    $oDaoFarCadAcompPacHiperdia->fa50_i_naomedicamentoso   = isset($ckNaoMedicamentoso) ? 1 : 2;
    $oDaoFarCadAcompPacHiperdia->fa50_i_diabetesacomp      = isset($statusDiabetes) ? 1 : 2;
    $oDaoFarCadAcompPacHiperdia->fa50_i_hipertensaoacomp   = isset($statusHipertensao) ? 1 : 2;

    if (isset($incluir)) { // INCLUSÃO DE CADASTRO / ACOMPANHAMENTO DE PACIENTE DO HIPERDIA

      $oDaoFarCadAcompPacHiperdia->fa50_i_tipo               = $lCadastrado ? 2 : 1;
      $oDaoFarCadAcompPacHiperdia->fa50_i_triagem            = $oDaoSauTriagemAvulsa->s152_i_codigo;
      $oDaoFarCadAcompPacHiperdia->fa50_i_exportado          = 2;
      $oDaoFarCadAcompPacHiperdia->incluir(null);

    } else { // ALTERAÇÃO DE CADASTRO / ACOMPANHAMENTO DE PACIENTE DO HIPERDIA

      $oDaoFarCadAcompPacHiperdia->fa50_i_codigo = $fa50_i_codigo;
      $oDaoFarCadAcompPacHiperdia->alterar($fa50_i_codigo);

    }

  }

  /* BLOCO DE INCLUSÃO DA LIGAÇÃO DO CADASTRO / ACOMPANHAMENTO COM A RETIRADA DE MEDICAMENTOS 
     *Obs: Não tem alteração, e a ligação é 1:1 */
  if ($oDaoFarCadAcompPacHiperdia->erro_status != '0' && isset($incluir) && $fa55_i_retirada != "") {

    $oDaoFarRetiradaCadAcomp->fa55_i_retirada = $fa55_i_retirada;
    $oDaoFarRetiradaCadAcomp->fa55_i_cadacomp = $oDaoFarCadAcompPacHiperdia->fa50_i_codigo;
    $oDaoFarRetiradaCadAcomp->incluir(null);
    if ($oDaoFarRetiradaCadAcomp->erro_status == '0') {

      $oDaoFarCadAcompPacHiperdia->erro_status = '0';
      $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoFarRetiradaCadAcomp->erro_msg;

    }

  }

  /* BLOCO DE INCLUSÃO / ALTERAÇÃO DOS MEDICAMENTOS DO CADASTRO / ACOMPANHAMENTO */
  if ($oDaoFarCadAcompPacHiperdia->erro_status != '0') {

    if (isset($alterar)) { // tenho que excluir os medicamentos que estavam relacionados, para incluir novamente

      $sSql    = $oDaoFarMedicamentoCadAcomp->sql_query_file(null, ' fa49_i_codigo ', null,
                                                             ' fa49_i_cadacomp = '.
                                                             $oDaoFarCadAcompPacHiperdia->fa50_i_codigo
                                                            );
      $rs      = $oDaoFarMedicamentoCadAcomp->sql_record($sSql);
      $iLinhas = $oDaoFarMedicamentoCadAcomp->numrows;
      /*
      * Laco que exclui todos os medicamentos relacionados ao cadastro / acompanhamento
      */
      for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
       
        $oDados = db_utils::fieldsmemory($rs, $iCont);
        $oDaoFarMedicamentoCadAcomp->excluir($oDados->fa49_i_codigo);
        if ($oDaoFarMedicamentoCadAcomp->erro_status == '0') {

          $oDaoFarCadAcompPacHiperdia->erro_status = '0';
          $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoFarMedicamentoCadAcomp->erro_msg;
          break;

        }
     
      } // fim do for

    }


    if (!empty($aMedicamentos) && $oDaoFarCadAcompPacHiperdia->erro_status != '0') {
      /*
      * Laço que inclui todos os medicamentos
      */
      $iTam = count($aMedicamentos);
      for ($iCont = 0; $iCont < $iTam; $iCont++) {

        /* Os dados dos medicamentos vem no seguinte formato: id_medicamento ## quantidade */
        $aDados = explode(' ## ',$aMedicamentos[$iCont]);
        $oDaoFarMedicamentoCadAcomp->fa49_i_medicamento = $aDados[0];
        $oDaoFarMedicamentoCadAcomp->fa49_n_quantidade  = $aDados[1];
        $oDaoFarMedicamentoCadAcomp->fa49_i_cadacomp    = $oDaoFarCadAcompPacHiperdia->fa50_i_codigo;
        $oDaoFarMedicamentoCadAcomp->incluir(null);
        if ($oDaoFarMedicamentoCadAcomp->erro_status == '0') {

          $oDaoFarCadAcompPacHiperdia->erro_status = '0';
          $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoFarMedicamentoCadAcomp->erro_msg;
          break;
          
        }

      } // fim do for

    }

  }

  /* BLOCO DE INCLUSÃO / ALTERAÇÃO DAS COMPLICAÇÕES DO CADASTRO / ACOMPANHAMENTO */
  if ($oDaoFarCadAcompPacHiperdia->erro_status != '0') {

    if (isset($alterar)) { // tenho que excluir as complicações que estavam relacionados, para incluir novamente

      $sSql    = $oDaoFarComplicacoesCadAcomp->sql_query_file(null, ' fa52_i_codigo ', null,
                                                              ' fa52_i_cadacomp = '.
                                                              $oDaoFarCadAcompPacHiperdia->fa50_i_codigo
                                                             );
      $rs      = $oDaoFarComplicacoesCadAcomp->sql_record($sSql);
      $iLinhas = $oDaoFarComplicacoesCadAcomp->numrows;
      /*
      * Laco que exclui todas as complicações relacionadas ao cadastro / acompanhamento
      */
      for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
       
        $oDados = db_utils::fieldsmemory($rs, $iCont);
        $oDaoFarComplicacoesCadAcomp->excluir($oDados->fa52_i_codigo);
        if ($oDaoFarComplicacoesCadAcomp->erro_status == '0') {

          $oDaoFarCadAcompPacHiperdia->erro_status = '0';
          $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoFarComplicacoesCadAcomp->erro_msg;
          break;

        }
     
      } // fim do for

    }


    if (!empty($aComplicacoes) && $oDaoFarCadAcompPacHiperdia->erro_status != '0') {
      /*
      * Laço que inclui todas as complicações
      */
      $iTam = count($aComplicacoes);
      for ($iCont = 0; $iCont < $iTam; $iCont++) {

        /* Os dados das complicacoes vem no seguinte formato: id_complicacao ## cod_estrutural */
        $aDados = explode(' ## ',$aComplicacoes[$iCont]);
        $oDaoFarComplicacoesCadAcomp->fa52_i_complicacao = $aDados[0];
        $oDaoFarComplicacoesCadAcomp->fa52_i_cadacomp    = $oDaoFarCadAcompPacHiperdia->fa50_i_codigo;
        $oDaoFarComplicacoesCadAcomp->incluir(null);
        if ($oDaoFarComplicacoesCadAcomp->erro_status == '0') {

          $oDaoFarCadAcompPacHiperdia->erro_status = '0';
          $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoFarComplicacoesCadAcomp->erro_msg;
          break;
          
        }

      } // fim do for

    }

  }

  /* BLOCO DE INCLUSÃO / ALTERAÇÃO DOS EXAMES DO ACOMPANHAMENTO (somente acompanhamentos possuem ligação com exames */
  if ($oDaoFarCadAcompPacHiperdia->erro_status != '0' && $oDaoFarCadAcompPacHiperdia->fa50_i_tipo == 2) {

    if (isset($alterar)) { // tenho que excluir os exames que estavam relacionados, para incluir novamente

      $sSql    = $oDaoFarExamesAcomp->sql_query_file(null, ' fa48_i_codigo ', null,
                                                     ' fa48_i_acompanhamento = '.
                                                     $oDaoFarCadAcompPacHiperdia->fa50_i_codigo
                                                    );
      $rs      = $oDaoFarExamesAcomp->sql_record($sSql);
      $iLinhas = $oDaoFarExamesAcomp->numrows;
      /*
      * Laco que exclui todos os exames relacionadas ao cadastro / acompanhamento
      */
      for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
       
        $oDados = db_utils::fieldsmemory($rs, $iCont);
        $oDaoFarExamesAcomp->excluir($oDados->fa48_i_codigo);
        if ($oDaoFarExamesAcomp->erro_status == '0') {

          $oDaoFarCadAcompPacHiperdia->erro_status = '0';
          $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoFarExamesAcomp->erro_msg;
          break;

        }
     
      } // fim do for

    }

    if (!empty($aExames) && $oDaoFarCadAcompPacHiperdia->erro_status != '0') {
      /*
      * Laço que inclui todos os exames
      */
      $iTam = count($aExames);
      for ($iCont = 0; $iCont < $iTam; $iCont++) {

        $oDaoFarExamesAcomp->fa48_i_exame          = $aExames[$iCont];
        $oDaoFarExamesAcomp->fa48_i_acompanhamento = $oDaoFarCadAcompPacHiperdia->fa50_i_codigo;
        $oDaoFarExamesAcomp->incluir(null);
        if ($oDaoFarExamesAcomp->erro_status == '0') {

          $oDaoFarCadAcompPacHiperdia->erro_status = '0';
          $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoFarExamesAcomp->erro_msg;
          break;
          
        }

      } // fim do for

    }

  }

  db_fim_transacao($oDaoFarCadAcompPacHiperdia->erro_status == '0' ? true : false);

}

if (isset($excluir)) {

  unset($GLOBALS['opcao']);
  $db_opcao = 1;

  db_inicio_transacao();

  $sSql    = $oDaoFarComplicacoesCadAcomp->sql_query_file(null, ' fa52_i_codigo ', null,
                                                          ' fa52_i_cadacomp = '.$fa50_i_codigo
                                                         );
  $rs      = $oDaoFarComplicacoesCadAcomp->sql_record($sSql);
  $iLinhas = $oDaoFarComplicacoesCadAcomp->numrows;
  /*
  * Laco que exclui todas as complicações relacionadas ao cadastro / acompanhamento
  */
  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
   
    $oDados = db_utils::fieldsmemory($rs, $iCont);
    $oDaoFarComplicacoesCadAcomp->excluir($oDados->fa52_i_codigo);
    if ($oDaoFarComplicacoesCadAcomp->erro_status == '0') {

      $oDaoFarCadAcompPacHiperdia->erro_status = '0';
      $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoFarComplicacoesCadAcomp->erro_msg;
      break;

    }
 
  } // fim do for

  if ($oDaoFarCadAcompPacHiperdia->erro_status != '0') {

    // Excluo os medicamentos do cadastro / acompanhamento
    $sSql    = $oDaoFarMedicamentoCadAcomp->sql_query_file(null, ' fa49_i_codigo ', null,
                                                           ' fa49_i_cadacomp = '.$fa50_i_codigo
                                                          );
    $rs      = $oDaoFarMedicamentoCadAcomp->sql_record($sSql);
    $iLinhas = $oDaoFarMedicamentoCadAcomp->numrows;
    /*
    * Laco que exclui todos os medicamentos relacionados ao cadastro / acompanhamento
    */
    for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
     
      $oDados = db_utils::fieldsmemory($rs, $iCont);
      $oDaoFarMedicamentoCadAcomp->excluir($oDados->fa49_i_codigo);
      if ($oDaoFarMedicamentoCadAcomp->erro_status == '0') {
  
        $oDaoFarCadAcompPacHiperdia->erro_status = '0';
        $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoFarMedicamentoCadAcomp->erro_msg;
        break;
  
      }
    
    }

  }

  if ($oDaoFarCadAcompPacHiperdia->erro_status != '0') {

    // Excluo os exames do cadastro / acompanhamento
    $sSql    = $oDaoFarExamesAcomp->sql_query_file(null, ' fa48_i_codigo ', null,
                                                   ' fa48_i_acompanhamento = '.$fa50_i_codigo
                                                  );
    $rs      = $oDaoFarExamesAcomp->sql_record($sSql);
    $iLinhas = $oDaoFarExamesAcomp->numrows;
    /*
    * Laco que exclui todos os exames relacionadas ao cadastro / acompanhamento
    */
    for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
     
      $oDados = db_utils::fieldsmemory($rs, $iCont);
      $oDaoFarExamesAcomp->excluir($oDados->fa48_i_codigo);
      if ($oDaoFarExamesAcomp->erro_status == '0') {
    
        $oDaoFarCadAcompPacHiperdia->erro_status = '0';
        $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoFarExamesAcomp->erro_msg;
        break;
    
      }
    
    } // fim do for

  }

  if ($oDaoFarCadAcompPacHiperdia->erro_status != '0') {

    // Excluo a ligacao do cadastro / acompanhamento com a retirada
    $sSql    = $oDaoFarRetiradaCadAcomp->sql_query_file(null, ' fa55_i_codigo ', '',
                                                        ' fa55_i_cadacomp = '.$fa50_i_codigo
                                                       );
    $rs      = $oDaoFarRetiradaCadAcomp->sql_record($sSql);
    $iLinhas = $oDaoFarRetiradaCadAcomp->numrows;
    if ($iLinhas > 0) {
  
      $oDados = db_utils::fieldsmemory($rs, 0);
      $oDaoFarRetiradaCadAcomp->excluir($oDados->fa55_i_codigo);
      if ($oDaoFarRetiradaCadAcomp->erro_status == '0') {
  
        $oDaoFarCadAcompPacHiperdia->erro_status = '0';
        $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoFarRetiradaCadAcomp->erro_msg;
  
      }
  
    }

  }

  
  if ($oDaoFarCadAcompPacHiperdia->erro_status != '0') {

    // Excluo o cadastro / acompanhamento
    $oDaoFarCadAcompPacHiperdia->excluir($fa50_i_codigo);

  }

  if ($oDaoFarCadAcompPacHiperdia->erro_status != '0') {

    // Excluo a triagem avulsa relacionada ao cadastro / acompanhamento
    $oDaoSauTriagemAvulsa->excluir($s152_i_codigo);
    if ($oDaoSauTriagemAvulsa->erro_status == '0') {
  
      $oDaoFarCadAcompPacHiperdia->erro_status = '0';
      $oDaoFarCadAcompPacHiperdia->erro_msg    = $oDaoSauTriagemAvulsa->erro_msg;
      break;
  
    }

  }

  db_fim_transacao($oDaoFarCadAcompPacHiperdia->erro_status == '0' ? true : false);

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
.estiloLinkAltExc {

  color: blue;
  text-decoration: underline;
  cursor: pointer;

}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <?
          if (isset($lBuscaCgs)) {
            echo "<br><br>";
          }
        ?>
        <fieldset style='width: 100%;'> <legend><b><?=$sTituloFieldset?></b></legend>
          <?
          require_once("forms/db_frmfar_cadacomppachiperdia.php");
          ?>
      </center>
    </fieldset>
  </td>
  </tr>
</table>
</center>
<?
if ($iModulo == 2) {

db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
        db_getsession("DB_anousu"), db_getsession("DB_instit")
       );

}
?>

</body>
</html>
<script>
js_tabulacaoforms("form1", "fa50_i_triagem", true, 1, "fa50_i_triagem", true);
</script>
<?
if (isset($incluir) || isset($alterar) || isset($excluir)) {

  if ($oDaoFarCadAcompPacHiperdia->erro_status=="0") {

    $oDaoFarCadAcompPacHiperdia->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

  } else {

    $oDaoFarCadAcompPacHiperdia->erro(true, false);
    $sGet  = "?iCgs=$fa50_i_cgsund";
    $sGet .= "&lDesabilita=true";
    if ($iModulo == 2) {
      $sGet .= "&lBuscaCgs=true";
    }
    db_redireciona("far4_far_cadacomppachiperdia001.php".$sGet);
  }

}
?>