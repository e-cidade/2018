<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_agendaassentamento_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

db_postmemory($HTTP_POST_VARS);

$oDaoAgendaassentamento = new cl_agendaassentamento();

$db_opcao    = 1;
$db_botao    = true;
$sPosScripts = "";

const MENSAGEM = 'recursoshumanos.rh.rec1_agendaassentamento.';

if(isset($h82_sequencial)) {
  $h82_sequencial = (int)$h82_sequencial;
}

if(isset($chavepesquisa)) {

  if(isset($opcao) && $opcao == 'alterar' ) {
    $db_opcao = 2;
    $db_botao = true;
  }

  if(isset($opcao) && $opcao == 'excluir' ) {
    $db_opcao = 3;
    $db_botao = true;
  }
  
  $sCamposItemAgendaAssentamento = "h82_sequencial, 
                                    h12_assent as h82_tipoassentamento, 
                                    h82_formulacondicao, 
                                    h82_formulainicio, 
                                    h82_formulafim, 
                                    h82_formulafaltasperiodo,
                                    h82_formulaprorrogafim, 
                                    h82_selecao, 
                                    h12_descr, 
                                    r44_descr, 
                                    formulainicio.db148_nome as db148_nome_inicio, 
                                    formulafim.db148_nome as db148_nome_fim, 
                                    formulafaltasperiodo.db148_nome as db148_nome_faltasperiodo,
                                    formulaprorroga.db148_nome as db148_nome_prorrogafim,
                                    formulacondicao.db148_nome";

  $result   = $oDaoAgendaassentamento->sql_record( $oDaoAgendaassentamento->sql_query($chavepesquisa, $sCamposItemAgendaAssentamento) );
  db_fieldsmemory($result, 0);

} elseif ( isset($incluir) || isset($alterar) || isset($excluir) ) {

  db_inicio_transacao();

  try{
    
    if(empty($h82_tipoassentamento)) {
      throw new BusinessException(_M(MENSAGEM."campo_assentamento_obrigadorio"));
    }

    if(empty($h82_selecao)) {
      throw new BusinessException(_M(MENSAGEM."campo_selecao_obrigadorio"));
    }

    if(empty($h82_formulacondicao)) {
      throw new BusinessException(_M(MENSAGEM."campo_formulacondicao_obrigadorio"));
    }

    if(empty($h82_formulainicio)) {
      throw new BusinessException(_M(MENSAGEM."campo_formulainicio_obrigadorio"));
    }



    if ( isset($incluir) || isset($alterar) ) {

      if ( isset($alterar) ) {
        $db_opcao  = 2;
      }

      $oDaoTipoassentamento = new cl_tipoasse;
      $sSqlTipoassentamento = $oDaoTipoassentamento->sql_query_file(null, 'h12_codigo', null, " h12_assent = '{$h82_tipoassentamento}'");
      $rsTipoassentamento   = db_query($sSqlTipoassentamento);

      if(!$rsTipoassentamento) {
        throw new BusinessException(_M(MENSAGEM ."erro_buscar_tipoassentamento"));
      }

      if(pg_num_rows($rsTipoassentamento) == 0) {
        throw new BusinessException(_M(MENSAGEM ."tipoassentamento_nao_encontrado"));
      }

      $iTipoAssentamento = db_utils::fieldsMemory($rsTipoassentamento, 0)->h12_codigo;

      $sWhereVerificaDuplicidadeAgenda  = "     h82_tipoassentamento = {$iTipoAssentamento}";
      $sWhereVerificaDuplicidadeAgenda .= " and h82_selecao          = {$h82_selecao}";
      $sSqlVerificaDuplicidadeAgenda    = $oDaoAgendaassentamento->sql_query_file(null, "*", null, $sWhereVerificaDuplicidadeAgenda);
      $rsVerificaDuplicidadeAgenda      = db_query($sSqlVerificaDuplicidadeAgenda);

      if(is_resource($rsVerificaDuplicidadeAgenda) && pg_num_rows($rsVerificaDuplicidadeAgenda) > 0) {

        if(isset($incluir) || (isset($alterar) && db_utils::fieldsMemory($rsVerificaDuplicidadeAgenda, 0)->h82_sequencial != $h82_sequencial)) {
          throw new BusinessException(_M(MENSAGEM.'erro_duplicidade_agenda'));
        }
      }

      $oDaoAgendaassentamento->h82_instit               = db_getsession('DB_instit');
      $oDaoAgendaassentamento->h82_tipoassentamento     = $iTipoAssentamento;
      $oDaoAgendaassentamento->h82_selecao              = $h82_selecao;
      $oDaoAgendaassentamento->h82_formulacondicao      = $h82_formulacondicao;
      $oDaoAgendaassentamento->h82_formulainicio        = $h82_formulainicio;
      $oDaoAgendaassentamento->h82_formulafim           = $h82_formulafim;
      $oDaoAgendaassentamento->h82_formulafaltasperiodo = $h82_formulafaltasperiodo;
      $oDaoAgendaassentamento->h82_formulaprorrogafim   = $h82_formulaprorrogafim;
    }

    if (isset($incluir)) {

      $oDaoAgendaassentamento->incluir(null);

      if ($oDaoAgendaassentamento->erro_status == '0') {

        $db_botao = true;
        $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

        if ($oDaoAgendaassentamento->erro_campo != "") {
          $sPosScripts .= "document.form1.{$oDaoAgendaassentamento->erro_campo}.classList.add('form-error');\n";
          $sPosScripts .= "document.form1.{$oDaoAgendaassentamento->erro_campo}.focus();\n";
        }

        throw new BusinessException($oDaoAgendaassentamento->erro_msg);
      }
    }

    if (isset($alterar)) {

      $db_opcao  = 22;
      $db_botao  = false;
      $oDaoAgendaassentamento->h82_sequencial = $h82_sequencial;
      $oDaoAgendaassentamento->alterar($h82_sequencial);

      if ($oDaoAgendaassentamento->erro_status == "0") {

        $db_botao = true;
        $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

        if ($oDaoAgendaassentamento->erro_campo != "") {
          $sPosScripts .= "document.form1.{$oDaoAgendaassentamento->erro_campo}.classList.add('form-error');";
          $sPosScripts .= "document.form1.{$oDaoAgendaassentamento->erro_campo}.focus();";
        }

        throw new BusinessException($oDaoAgendaassentamento->erro_msg);
      }
    }

    if (isset($excluir)) {

      $db_opcao  = 33;
      $db_botao  = false;
      $oDaoAgendaassentamento->excluir($h82_sequencial);

      if ($oDaoAgendaassentamento->erro_status == "0") {

        $db_botao = true;
        throw new BusinessException($oDaoAgendaassentamento->erro_msg);
      }
    }

    db_fim_transacao(!(bool)$oDaoAgendaassentamento->erro_status);

  } catch (Exception $oErro) {

    db_fim_transacao(1);
    db_msgbox($oErro->getMessage());
  }

  if(isset($oDaoAgendaassentamento->erro_status) && $oDaoAgendaassentamento->erro_status == "1" ) {
    db_msgbox($oDaoAgendaassentamento->erro_msg);
    $sPosScripts = "document.form1.novo.click();";
  }
}

// $sPosScripts .=  'js_tabulacaoforms("form1", "h82_tipoassentamento", true, 1, "h82_tipoassentamento", true);';

include(modification("forms/db_frmagendaassentamento.php"));
?>
