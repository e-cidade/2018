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
define( 'MENSAGENS_SAU1_UNDMEDHORARIOS006', 'saude.ambulatorial.sau1_undmedhorario006.' );

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$dHoje                    = date("Y-m-d", db_getsession("DB_datausu"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clmedicos                = new cl_medicos;
$clunidademedicos         = new cl_unidademedicos;
$clsau_tipoficha          = new cl_sau_tipoficha;
$cldiasemana              = new cl_diasemana;
$clundmedhorario          = new cl_undmedhorario_ext;
$oDaoAgendamentos         = new cl_agendamentos();

$db_botao      = true;
$db_opcao      = 1;
$db_opcao2     = 1;
$lAgendamentos = true;

$datausu  = date("Y",db_getsession( "DB_datausu" ) )."/".
            date("m",db_getsession( "DB_datausu" ) )."/".
            date("d",db_getsession( "DB_datausu" ) );

if (isset($incluir) || isset($alterar) && $lAgendamentos) {

  if( isset( $sd30_d_valinicial ) && empty( $sd30_d_valinicial ) ) {

    db_msgbox( _M( MENSAGENS_SAU1_UNDMEDHORARIOS006 . 'data_inicial_nao_informada' ) );

    $sNome    = addslashes( $z01_nome );
    $sVinculo = addslashes( $rh70_descr );
    $sUnidade = addslashes( $descrdepto );

    $sParametros  = "?sd04_i_medico={$sd04_i_medico}&z01_nome={$sNome}&sd30_i_undmed={$sd30_i_undmed}";
    $sParametros .= "&rh70_descr={$sVinculo}&sd04_i_unidade={$sd04_i_unidade}&descrdepto={$sUnidade}";
    db_redireciona( "sau1_undmedhorario006.php{$sParametros}" );
  }

	$iDia  = isset($chk_seg) ? $chk_seg . ", " : "";
	$iDia .= isset($chk_ter) ? $chk_ter . ", " : "";
	$iDia .= isset($chk_qua) ? $chk_qua . ", " : "";
	$iDia .= isset($chk_qui) ? $chk_qui . ", " : "";
	$iDia .= isset($chk_sex) ? $chk_sex . ", " : "";
	$iDia .= isset($chk_sab) ? $chk_sab . ", " : "";
	$iDia .= isset($chk_dom) ? $chk_dom . ", " : "";
	$iDia  = substr( $iDia, 0, strlen($iDia) - 2 );

  $oDataInicial = new DBDate( $sd30_d_valinicial );

  $aWhereValidaConflito   = array();
  $aWhereValidaConflito[] = " sd04_i_medico = {$sd04_i_medico} ";
  $aWhereValidaConflito[] = " (sd30_c_horaini::time, sd30_c_horafim::time) overlaps ('{$sd30_c_horaini}'::time, '{$sd30_c_horafim}'::time ) ";
  $sWhereValidaConflito   = implode(" and ", $aWhereValidaConflito );
  $sWhereValidaConflito  .= " and  sd30_i_diasemana in ({$iDia})";

  $sCamposValidaConflito  = " sd30_i_codigo, sd30_d_valinicial, sd30_d_valfinal, ";
  $sCamposValidaConflito .= " (select 1 from agendamentos where sd23_i_undmedhor = sd30_i_codigo limit 1)::boolean as possue_agenda";
  $sSqlValidaConflitoDia  = $clundmedhorario->sql_query(null, $sCamposValidaConflito, null, $sWhereValidaConflito);
  $rsValidaConflitoDia    = db_query($sSqlValidaConflitoDia);

  try {

    if ($rsValidaConflitoDia && pg_num_rows($rsValidaConflitoDia) > 0 && $rad_periodo == 1 ) {

      $iTotalConflitos = pg_num_rows($rsValidaConflitoDia);

      for( $iContador = 0; $iContador < $iTotalConflitos; $iContador++ ) {

        $oDadosConflito  = db_utils::fieldsMemory( $rsValidaConflitoDia, $iContador );

        $lPossuiAgenda = $oDadosConflito->possue_agenda == 't';
        if ($lPossuiAgenda &&  !empty($oDadosConflito->sd30_d_valfinal) && empty($sd30_d_valfinal) && isset( $alterar )
            && $oDadosConflito->sd30_i_codigo == $sd30_i_codigo) {

          throw new Exception( _M(MENSAGENS_SAU1_UNDMEDHORARIOS006 . "agenda_encerrada" ) );
        }
        
        if (  $lPossuiAgenda &&  empty($oDadosConflito->sd30_d_valfinal) &&
              !empty($sd30_d_valfinal) && $oDadosConflito->sd30_i_codigo != $sd30_i_codigo ) {

          throw new Exception( _M(MENSAGENS_SAU1_UNDMEDHORARIOS006 . "agenda_dentro_periodo" )  );
        }

        $oDataInicial         = new DBDate( $sd30_d_valinicial );
        $oDataInicialConflito = new DBDate( $oDadosConflito->sd30_d_valinicial );

        /**
         * valida os dados informado, conflitam com uma agenda já inclusa
         * quando alteração valida se estamos tratando da mesma agenda
         */
        if(  !empty( $oDadosConflito->sd30_d_valfinal ) && (!empty($sd30_i_codigo) && $oDadosConflito->sd30_i_codigo != $sd30_i_codigo) ) {

          $oDataFinalConflito = new DBDate( $oDadosConflito->sd30_d_valfinal );
          if( DBDate::dataEstaNoIntervalo( $oDataInicial, $oDataInicialConflito, $oDataFinalConflito ) ) {
            throw new Exception( _M(MENSAGENS_SAU1_UNDMEDHORARIOS006 . "agenda_dentro_periodo") );
          }
        }

        /**
         * Valida a tentativa de incluir uma agenda e o médico possue uma agenda encerrada para o dia;
         * Neste caso validamos se a data inicial da nova agenda esta conflitando com o período da agenda fechada
         */
        if ( !empty( $oDadosConflito->sd30_d_valfinal ) && empty($sd30_i_codigo) ) {

          $oDataFinalConflito = new DBDate( $oDadosConflito->sd30_d_valfinal );
          if( DBDate::dataEstaNoIntervalo( $oDataInicial, $oDataInicialConflito, $oDataFinalConflito ) ) {
            throw new Exception( _M(MENSAGENS_SAU1_UNDMEDHORARIOS006 . "agenda_dentro_periodo") );
          }

          if (empty($sd30_d_valfinal) && $oDataInicial->getTimeStamp() <= $oDataInicialConflito->getTimeStamp() ) {

            $oMsgErro              = new stdClass();
            $oMsgErro->sDataInicio = $oDataInicial->convertTo(DBDate::DATA_PTBR);
            throw new Exception( _M( MENSAGENS_SAU1_UNDMEDHORARIOS006 . "impossivel_criar_agenda_possui_agenda_valida", $oMsgErro) );
          }

          if ( !empty($sd30_d_valfinal) ) {

            $oDataFinal = new DBDate($sd30_d_valfinal);
            if (DBDate::overlaps($oDataInicialConflito, $oDataFinalConflito, $oDataInicial, $oDataFinal)) {
              throw new Exception( _M( MENSAGENS_SAU1_UNDMEDHORARIOS006 . "periodo_conflita_agenda") );
            }
          }
        }

        /**
         * Valida a tentativa de incluir uma agenda e o médico possue uma agenda sem data final para o dia da semana
         */
        if ( empty( $oDadosConflito->sd30_d_valfinal )  && empty($sd30_i_codigo) ) {
          throw new Exception( _M(MENSAGENS_SAU1_UNDMEDHORARIOS006 . "agenda_dentro_periodo"));
        }

        if(    empty( $oDadosConflito->sd30_d_valfinal ) && empty($sd30_d_valfinal)
            && (!empty($sd30_i_codigo) && $oDadosConflito->sd30_i_codigo != $sd30_i_codigo) ) {
          throw new Exception( _M(MENSAGENS_SAU1_UNDMEDHORARIOS006 . "agenda_dentro_periodo"));
        }
      }
    }

    $datavalidade = "";
    if ( $sd30_d_valinicial_ano != "" ) {

      $dtInicial    = $sd30_d_valinicial_ano . "-" . $sd30_d_valinicial_mes . "-" . $sd30_d_valinicial_dia;
      $datavalidade = " and sd30_d_valinicial is not null and sd30_d_valinicial >= '{$dtInicial}' ";
    } else if ( $sd30_d_valfinal_ano != "" || $sd30_d_valinicial_ano != "" ) {

      $dtFinal       = date("Y", db_getsession("DB_datausu")) . "-" . date("m", db_getsession("DB_datausu"));
      $dtFinal      .= "-" . date("d", db_getsession("DB_datausu"));
      $datavalidade  = " and sd30_d_valinicial is not null and sd30_d_valinicial >= '{$dtFinal}'";
    }

    if ( $sd30_d_valfinal_ano != "" ) {

      $dtFinal       = $sd30_d_valfinal_ano."-".$sd30_d_valfinal_mes."-".$sd30_d_valfinal_dia;
      $datavalidade .= " and (
                     ( sd30_d_valfinal is not null and sd30_d_valfinal <= '{$dtFinal}' ) )";
    } else if ( $sd30_d_valfinal_ano != "" || $sd30_d_valinicial_ano != "" ) {

      $dtFinal       = date("Y", db_getsession("DB_datausu")) . "-" . date("m", db_getsession("DB_datausu"));
      $dtFinal      .= "-" . date("d", db_getsession("DB_datausu"));
      $datavalidade .= " and sd30_d_valfinal is not null and sd30_d_valfinal > '{$dtFinal}'";
    }

    $sCondicaoAlterar = isset($alterar) || @$opcao == "alterar" ? " and sd30_i_codigo <> {$sd30_i_codigo}" : "";
    $sWhere           = "     sd04_i_medico = {$sd04_i_medico} {$sCondicaoAlterar} and sd30_i_diasemana in ( {$iDia} )";
    $sWhere          .= " and ( '{$sd30_c_horaini}' between sd30_c_horaini and sd30_c_horafim";
    $sWhere          .= "       or '{$sd30_c_horafim}' between sd30_c_horaini and sd30_c_horafim";
    $sWhere          .= "       or sd30_c_horaini between  '{$sd30_c_horaini}' and '{$sd30_c_horafim}'";
    $sWhere          .= "       or sd30_c_horafim between  '{$sd30_c_horaini}' and '{$sd30_c_horafim}' )";
    $sWhere          .= " $datavalidade ";

    $str_query2  = "select * ";
    $str_query2 .= "  from agendamentos ";
    $str_query2 .= " where sd23_i_undmedhor = {$sd30_i_codigo} ";
    $str_query2 .= "   and sd23_d_consulta >= '{$sd30_d_valfinal_ano} - {$sd30_d_valfinal_mes} - {$sd30_d_valfinal_dia}' ";
    $str_query2 .= "   and not exists ( select * from agendaconsultaanula where s114_i_agendaconsulta = sd23_i_codigo ) ";

    $sMsg         = "<br><br><center>Erro: <font color=\"red\">Ao selecionar a grade de horarios do profissional</font>";
    $sMsg        .= " <br>Contate o administador do sistema!</center>";
    $res_agenda   = @db_query( $str_query2 );
    $db_opcao     = isset($alterar) ?  2 : $db_opcao;
    $db_opcao2    = isset($alterar) ? 22 : $db_opcao;

    if ( $sd30_d_valinicial_ano != "" && $sd30_d_valfinal_ano != "") {

      $aVet   = explode( "/", $sd30_d_valinicial );
      $dData1 = $aVet[2] . $aVet[1] . $aVet[0];
      $aVet   = explode( "/", $sd30_d_valfinal );
      $dData2 = $aVet[2] . $aVet[1] . $aVet[0];
    }

    if( $sd30_d_valinicial_ano != "" && @pg_num_rows($res_agenda) > 0 ) {
      throw new Exception( _M( MENSAGENS_SAU1_UNDMEDHORARIOS006 . 'profissional_possui_horario' ) );
    } else if (( $sd30_d_valinicial_ano != "" && $sd30_d_valfinal_ano != "") && ($dData1 > $dData2)) {
      throw new Exception( _M( MENSAGENS_SAU1_UNDMEDHORARIOS006 . 'data_inicial_maior_final' ) );
    } else {

      if (isset($incluir)) {

        db_inicio_transacao();
        if ($rad_periodo == 1) {

          $aDia = explode(",", $iDia);
          for( $iCont = 0; $iCont < sizeof($aDia); $iCont++ ) {

            $clundmedhorario->sd30_i_diasemana = $aDia[$iCont];
            $clundmedhorario->incluir(null);
          }
        } else {

          //montando array dia semana
          $aDia           = explode(",", $iDia);
          $dias_da_semana = array();

          for ( $iCont = 0; $iCont < sizeof($aDia); $iCont++ ) {
            $dias_da_semana[$aDia[$iCont]] = 0;
          }

          //Verificando se é Quinzenal1(1) ou Mensal(3)
          if ($rad_periodo == 2) {
            $escape = 1;
          } else {

            //                 0          1          2          3          4           5         6           7
            $escape = array($semanames, $semanames, $semanames, $semanames, $semanames, $semanames, $semanames, $semanames);
          }



          $vet               = explode( "/", $sd30_d_valinicial );
          $sd30_d_valinicial = $vet[2] . "-" . $vet[1] . "-".$vet[0];
          $vet               = explode( "/", $sd30_d_valfinal );
          $sd30_d_valfinal   = $vet[2] . "-" . $vet[1] . "-" . $vet[0];
          $d2                = strtotime( $sd30_d_valfinal );

          $lErro = false;

          //For percorre o periodo das datas de validades
          for ($d1 = strtotime($sd30_d_valinicial); $d1 <= $d2; $d1 = $d1 + 86400) {

            foreach ($dias_da_semana as $chave => $valor) {

              $dtPeriodo            = date( "Y-m-d", $d1 );
              $sWherePeriodicidade  = implode(" and ", $aWhereValidaConflito);
              $sWherePeriodicidade .= " and sd30_i_diasemana = {$chave} ";
              $sWherePeriodicidade .= " AND ( (      sd30_d_valfinal IS NOT NULL ";
              $sWherePeriodicidade .= "        and (sd30_d_valinicial, sd30_d_valfinal) overlaps ('{$dtPeriodo}'::date, '{$dtPeriodo}'::date ) ";
              $sWherePeriodicidade .= "       ) or ( sd30_d_valfinal IS NULL ) ";
              $sWherePeriodicidade .= "     ) ";

              if ($rad_periodo == 2) {

                //escape Quinzenal
                $iDiaChave  = ( date( "w", $d1 ) + 1 );
                $iDiaSemana = $dias_da_semana[$chave];

                if (($iDiaChave == $chave) && ($iDiaSemana == 0)) {

                  $sSqlPeriodicidade = $clundmedhorario->sql_query_ext( "", "*", null, $sWherePeriodicidade );
                  $rsPeriodicidade   = @db_query( $sSqlPeriodicidade ) or die($sMsg);

                  if( pg_num_rows( $rsPeriodicidade ) > 0) {
                    throw new Exception( _M( MENSAGENS_SAU1_UNDMEDHORARIOS006 . 'profissional_possui_horario' ) );
                  }

                  $clundmedhorario->sd30_i_diasemana  = (int)trim($chave);
                  $clundmedhorario->sd30_d_valinicial = date( "Y-m-d", $d1 );
                  $clundmedhorario->sd30_d_valfinal   = date( "Y-m-d", $d1 );
                  $clundmedhorario->incluir(null);
                  $dias_da_semana[$chave] = $escape;
                } else {

                  if ( ( date( "w", $d1 ) + 1 ) == $chave ) {
                    $dias_da_semana[$chave] = $dias_da_semana[$chave] - 1;
                  }
                }
              }

              //escape mensal
              if ($rad_periodo == 3) {

                $iDiaChave  = ( date( "w", $d1 ) + 1 );
                $iDiaSemana = $dias_da_semana[$chave];

                if (($iDiaChave == $chave) && (date("m",$d1) != $iDiaSemana)) {

                  if ($escape[trim($chave)] == 0) { // @ para evitar o erro desconhecido

                    $sSqlPeriodicidade = $clundmedhorario->sql_query_ext( "", "*", null, $sWherePeriodicidade );
                    $rsPeriodicidade   = @db_query( $sSqlPeriodicidade ) or die($sMsg);

                    if( pg_num_rows( $rsPeriodicidade ) > 0) {
                      throw new Exception( _M( MENSAGENS_SAU1_UNDMEDHORARIOS006 . 'profissional_possui_horario' ) );
                    }

                    $clundmedhorario->sd30_i_diasemana  = (int) trim($chave);
                    $clundmedhorario->sd30_d_valinicial = date( "Y-m-d", $d1 );
                    $clundmedhorario->sd30_d_valfinal   = date( "Y-m-d", $d1 );
                    $clundmedhorario->incluir(null);

                    $dias_da_semana[$chave] = date( "m", $d1 );
                    $escape[trim($chave)]   = $semanames;
                  } else {

                    if ( ( ( date( "w", $d1 ) + 1 ) == $chave ) && ( date( "m", $d1 ) != $dias_da_semana[$chave] ) ) {
                      $escape[trim($chave)] = $escape[trim($chave)] - 1;
                    }
                  }
                }
              }
            }
          }
        }

        db_fim_transacao();
      }

      if( isset( $alterar ) ) {

        //Verifica se foi alterado Intervalo/Fichas com agendamentos posteriores
        $sCampos  = "sd23_d_consulta as data, count(*) as icountagenda, max(sd23_d_consulta) as ultima_data, sd30_i_diasemana";
        $sSql     = "select {$sCampos}";
        $sSql    .= "	 from agendamentos ";
        $sSql    .= "	      inner join undmedhorario on sd30_i_codigo = sd23_i_undmedhor";
        $sSql    .= " where sd23_i_undmedhor = {$sd30_i_codigo} ";
        $sSql    .= "   and not exists ( select 1 from agendaconsultaanula where s114_i_agendaconsulta = sd23_i_codigo ) ";
        $sSql    .= " group by sd23_d_consulta, sd30_i_diasemana";
        $sSql    .= " order by ultima_data desc ";

        $rsSql = db_query( $sSql ) or die( "ERRO: $sSql");

        if ( pg_num_rows( $rsSql ) > 0 ) {

          $oCountagenda    = db_utils::fieldsMemory($rsSql, 0);

          if ($oCountagenda->sd30_i_diasemana != $iDia) {
            throw new Exception( _M( MENSAGENS_SAU1_UNDMEDHORARIOS006 . "dia_semana_nao_pode_ser_alterado" ) );
          }

          $oMaiorDataAgenda = new DBDate( $oCountagenda->ultima_data );

          if (!empty ($sd30_d_valfinal)) {

            $oDataFinal = new DBDate($sd30_d_valfinal);
            if ( $oDataFinal->getTimeStamp() < $oMaiorDataAgenda->getTimeStamp() ) {

              $oMensagem              = new stdClass();
              $oMensagem->ultima_data = $oMaiorDataAgenda->convertTo(DBDate::DATA_PTBR);
              throw new Exception( _M( MENSAGENS_SAU1_UNDMEDHORARIOS006 . "data_final_menor_ultimo_agendamento", $oMensagem ) );
            }
          }

          $sSql            = $clundmedhorario->sql_query_ext($sd30_i_codigo);
          $rsUndmedhorario = $clundmedhorario->sql_record($sSql);
          $oUndmedhorario  = db_utils::fieldsMemory($rsUndmedhorario,0);

          if( empty($sd30_d_valfinal) && $sd30_i_fichas < $oCountagenda->icountagenda ) {

            $oMensagem                 = new stdClass();
            $oMensagem->iDia           = db_formatar( $oCountagenda->data, "d" );
            $oMensagem->iiAgendamentos = $oCountagenda->icountagenda;
            throw new Exception( _M( MENSAGENS_SAU1_UNDMEDHORARIOS006 . 'existe_agendamento_fichas', $oMensagem ) );
          } else if( $oUndmedhorario->sd30_i_diasemana <> $iDia ) {
            throw new Exception( _M( MENSAGENS_SAU1_UNDMEDHORARIOS006 . 'existe_agendamento_dia' ) );
          }
        }

        if ($rad_periodo == 1) {
          $clundmedhorario->sd30_i_diasemana = $iDia;
        }

        db_inicio_transacao();
        $clundmedhorario->alterar($sd30_i_codigo);
        db_fim_transacao();
      }
    }
  } catch ( Exception $oError ) {

    $clundmedhorario->erro_status = "0";
    $clundmedhorario->erro_msg    = $oError->getMessage();
  }
}

if (isset($excluir) && $lAgendamentos) {

	$str_query = "select *
	        				from agendamentos
	         			 where sd23_i_undmedhor = $sd30_i_codigo  ";
	$result = db_query( $str_query );

	if( pg_numrows( $result ) > 0 ) {

		$clundmedhorario->erro_status = "0";
		$clundmedhorario->erro_msg    = _M( MENSAGENS_SAU1_UNDMEDHORARIOS006 . 'agendamentos_posteriores_profissional' );
	} else {

		db_inicio_transacao();
		$clundmedhorario->excluir($sd30_i_codigo);
		db_fim_transacao();
	}
}
$sd30_d_valinicial     = date("d/m/Y", db_getsession('DB_datausu'));
$sd30_d_valinicial_dia = date("d",     db_getsession('DB_datausu'));
$sd30_d_valinicial_mes = date("m",     db_getsession('DB_datausu'));
$sd30_d_valinicial_ano = date("Y",     db_getsession('DB_datausu'));

//Botões Alterar/Excluir
if (isset($opcao)) {

	$db_botao1 = true;
	$db_opcao  = $opcao == "alterar" ?  2 : 3;
	$db_opcao2 = $opcao == "alterar" ? 22 : 3;

  $sCampos  = "*";
  $sCampos .= " , (select 1 from agendamentos where sd23_i_undmedhor = sd30_i_codigo limit 1)::boolean as possui_agenda";

	$result = $clundmedhorario->sql_record($clundmedhorario->sql_query_ext($sd30_i_codigo, $sCampos));
	if( $clundmedhorario->numrows > 0 ){
		db_fieldsmemory($result,0);
	}
}

/**
 * Validações para redirecionamento da página
 */
if( isset( $incluir ) || isset( $alterar ) ) {

  if( $clundmedhorario->erro_status == "0" ) {

    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if( $clundmedhorario->erro_campo != "" ) {

      echo "<script> document.form1.".$clundmedhorario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clundmedhorario->erro_campo.".focus();</script>";
    }
  }

  $clundmedhorario->erro(true, false);
  db_redireciona("sau1_undmedhorario006.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome&sd30_i_undmed=$sd30_i_undmed");
}

if( isset( $excluir ) ) {

  $clundmedhorario->erro( true, false );
  db_redireciona("sau1_undmedhorario006.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome");
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
<script language="JavaScript" type="text/javascript" src="scripts/dates.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBInputHora.widget.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
      <?php
      require_once(modification("forms/db_frmundmedhorario006.php"));
      ?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd30_i_undmed",true,1,"sd30_i_undmed", true);
</script>
<?php

if( isset($sd30_i_undmed) ) {
  echo "<script>js_pesquisasd30_i_undmed(false);</script>";
}