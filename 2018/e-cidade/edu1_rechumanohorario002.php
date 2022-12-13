<?
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$clregenciahorario   = new cl_regenciahorario;
$clperiodoescola     = new cl_periodoescola;
$cldiasemana         = new cl_diasemana;
$clrechumanohoradisp = new cl_rechumanohoradisp;
$clrechumano         = new cl_rechumano;
$clturmaachorario    = new cl_turmaachorario;

$escola = db_getsession("DB_coddepto");

$sCamposPeriodoEscola = "min(ed17_h_inicio) as menorhorario, max(ed17_h_fim) as maiorhorario";
$sSqlPeriodoEscola    = $clperiodoescola->sql_query( "", $sCamposPeriodoEscola );
$result_per           = $clperiodoescola->sql_record( $sSqlPeriodoEscola );

db_fieldsmemory( $result_per, 0 );

$hora1         = (int) substr( $menorhorario, 0, 2 );
$hora2         = (int) substr( $maiorhorario, 0, 2 ) + 1;
$horainicial   = $hora1 * 100;
$horafinal     = $hora2 * 100;
$tempo_ini     = mktime( $hora1, 0, 0, date("m"), date("d"), date("Y") );
$tempo_fim     = mktime( $hora2, 0, 0, date("m"), date("d"), date("Y") );
$difer_minutos = ( $tempo_fim - $tempo_ini ) / 60;
$alt_tab_hora  = $difer_minutos / 2;
$qtd_hora      = $difer_minutos / 60;
$larg_tabela   = @$larg_obj;
$larg_coluna1  = 40;
$larg_coluna2  = 40;
$tabela1_top   = 20;
$tabela1_left  = 2;

$oPost = db_utils::postMemory($_POST);

if($oPost->sAction == 'MontaGrade') {

  $sWhereRecHumano = "ed01_c_regencia = 'S' AND ed20_i_codigo = {$oPost->rechumano} AND ed75_i_escola = {$escola}";
  $sSqlRecHumano   = $clrechumano->sql_query_escola( "", "ed20_i_codigo as pranada", "", $sWhereRecHumano );
  $result0         = $clrechumano->sql_record( $sSqlRecHumano );

  if( $oPost->esc_horario != "" ) {
    $condicao = " AND ed17_i_escola = {$oPost->esc_horario}";
  } else {
    $condicao = "";
  }

  $sHtml    = '<tr><td>';
  $ini_left = $tabela1_left+$larg_coluna1+$larg_coluna2;

  $sCamposDiaSemana = "ed32_i_codigo, ed32_c_abrev, ed32_c_descr";
  $sWhereDiaSemana  = "ed04_i_escola = {$escola} AND ed04_c_letivo = 'S'";
  $sSqlDiaSemana    = $cldiasemana->sql_query_rh( "", $sCamposDiaSemana, "ed32_i_codigo", $sWhereDiaSemana );
  $result           = $cldiasemana->sql_record( $sSqlDiaSemana );
  $larg_dia         = floor( ( $larg_tabela - $larg_coluna1 - $larg_coluna2 ) / $cldiasemana->numrows );

  for( $x = 0; $x < $cldiasemana->numrows; $x++ ) {

    $ini_top = $tabela1_top + 25;
    db_fieldsmemory( $result, $x );

    $sCamposRegenciaHorario     = "case ";
    $sCamposRegenciaHorario    .= "     when ed20_i_tiposervidor = 1 ";
    $sCamposRegenciaHorario    .= "     then 'Matrícula: '||rechumanopessoal.ed284_i_rhpessoal ";
    $sCamposRegenciaHorario    .= "     else 'CGM: '||rechumanocgm.ed285_i_cgm ";
    $sCamposRegenciaHorario    .= " end as identificacao, ";
    $sCamposRegenciaHorario    .= "ed20_i_codigo, ed232_c_abrev, ed58_i_codigo, ed08_c_descr, ed18_c_nome, ed15_c_nome ";
    $sCamposRegenciaHorario    .= ", ed17_h_inicio, ed17_h_fim, ed17_i_escola, ed57_c_descr, ed232_c_descr ";
    $sCamposRegenciaHorario    .= ", ed59_i_ordenacao, ed11_c_descr, ed10_c_abrev";
    $sOrdenacaoRegenciaHorario  = "ed58_i_diasemana,ed17_h_inicio asc,ed17_h_fim asc";
    $sWhereRegenciaHorario      = "ed58_i_rechumano = {$oPost->rechumano} AND ed58_i_diasemana = {$ed32_i_codigo}";
    $sWhereRegenciaHorario     .= " and ed58_ativo is true  AND ed52_i_ano = {$oPost->ano} {$condicao}";

    $sSqlRegenciaHorario = $clregenciahorario->sql_query(
                                                          "",
                                                          $sCamposRegenciaHorario,
                                                          $sOrdenacaoRegenciaHorario,
                                                          $sWhereRegenciaHorario
                                                        );
    $result1             = $clregenciahorario->sql_record( $sSqlRegenciaHorario );

    if( $clrechumano->numrows > 0 && $oPost->ano == date("Y") ) {

      $notexists = "AND not exists
                    (select * from regenciahorario
                              inner join regencia            on ed59_i_codigo    = ed58_i_regencia
                              inner join periodoescola as pe on pe.ed17_i_codigo = ed58_i_periodo
                              inner join turma               on ed57_i_codigo    = ed59_i_turma
                              inner join calendario          on ed52_i_codigo    = ed57_i_calendario
                        where ed58_i_rechumano = ed75_i_rechumano
                          and ed58_ativo is true
                          and ed33_ativo is true
                          and ed58_i_diasemana = ed33_i_diasemana
                          and ed52_i_ano = {$oPost->ano}
        and (
            ( (pe.ed17_h_inicio > periodoescola.ed17_h_inicio AND pe.ed17_h_inicio < periodoescola.ed17_h_fim)
               OR (pe.ed17_h_fim  > periodoescola.ed17_h_inicio AND pe.ed17_h_fim < periodoescola.ed17_h_fim)
            )
            OR (pe.ed17_h_inicio <= periodoescola.ed17_h_inicio AND pe.ed17_h_fim >= periodoescola.ed17_h_fim)
            OR (pe.ed17_h_inicio >= periodoescola.ed17_h_inicio AND pe.ed17_h_fim <= periodoescola.ed17_h_fim)
            OR (pe.ed17_h_inicio = periodoescola.ed17_h_inicio AND pe.ed17_h_fim = periodoescola.ed17_h_fim)
            )
                    )
                   ";
    } else {
      $notexists = "";
    }

    $sCamposRecHumanoHoraDisp     = "case ";
    $sCamposRecHumanoHoraDisp    .= "     when ed20_i_tiposervidor = 1 ";
    $sCamposRecHumanoHoraDisp    .= "     then 'Matrícula: '||rechumanopessoal.ed284_i_rhpessoal ";
    $sCamposRecHumanoHoraDisp    .= "     else 'CGM: '||rechumanocgm.ed285_i_cgm ";
    $sCamposRecHumanoHoraDisp    .= " end as identificacao";
    $sCamposRecHumanoHoraDisp    .= ", ed33_i_codigo, ed08_c_descr, ed18_c_nome, ed15_c_nome, ed17_h_inicio, ed17_h_fim";
    $sCamposRecHumanoHoraDisp    .= ", ed17_i_escola, ed20_i_codigo";
    $sOrdenacaoRecHumanoHoraDisp  = "ed33_i_diasemana, ed17_h_inicio asc, ed17_h_fim asc";
    $sWhereRecHumanoHoraDisp      = "ed75_i_rechumano = {$oPost->rechumano} AND ed33_ativo is true";
    $sWhereRecHumanoHoraDisp     .= " AND ed33_i_diasemana = {$ed32_i_codigo} {$notexists} {$condicao}";

    $sSqlRecHumanoHoraDisp = $clrechumanohoradisp->sql_query_disponibilidade(
                                                                              "",
                                                                              $sCamposRecHumanoHoraDisp,
                                                                              $sOrdenacaoRecHumanoHoraDisp,
                                                                              $sWhereRecHumanoHoraDisp
                                                                            );
    $result2 = $clrechumanohoradisp->sql_record( $sSqlRecHumanoHoraDisp );

    $sCamposTurmaAcHorario     = "case ";
    $sCamposTurmaAcHorario    .= "     when ed20_i_tiposervidor = 1 ";
    $sCamposTurmaAcHorario    .= "     then 'Matrícula: '||rechumanopessoal.ed284_i_rhpessoal ";
    $sCamposTurmaAcHorario    .= "     else 'CGM: '||rechumanocgm.ed285_i_cgm ";
    $sCamposTurmaAcHorario    .= " end as identificacao ";
    $sCamposTurmaAcHorario    .= ", ed270_i_codigo, ed08_c_descr, ed18_c_nome, turno.ed15_c_nome, ed17_h_inicio ";
    $sCamposTurmaAcHorario    .= ", ed17_h_fim, ed17_i_escola, ed20_i_codigo, ed268_c_descr";
    $sOrdenacaoTurmaAcHorario  = "ed270_i_diasemana, ed17_h_inicio asc, ed17_h_fim asc";
    $sWhereTurmaAcHorario      = "ed270_i_rechumano = {$oPost->rechumano} AND ed270_i_diasemana = {$ed32_i_codigo}";
    $sWhereTurmaAcHorario     .= " and ed52_i_ano = {$oPost->ano}";

    $sSqlTurmaAcHorario = $clturmaachorario->sql_query(
                                                        "",
                                                        $sCamposTurmaAcHorario,
                                                        $sOrdenacaoTurmaAcHorario,
                                                        $sWhereTurmaAcHorario
                                                      );
    $resultturma = $clturmaachorario->sql_record( $sSqlTurmaAcHorario );
    $tt          = 0;

    for( $t = $horainicial; $t <= $horafinal; $t++ ) {

      $hora = strlen($t) == 3 ? "0".$t : $t;
      $hora = substr( $hora, 0, 2 ).":".substr( $hora, 2, 2 );

      if( $clregenciahorario->numrows > 0 ) {

        for( $y = 0; $y < $clregenciahorario->numrows; $y++ ) {

          db_fieldsmemory( $result1, $y );

          if( trim( $hora ) == trim( $ed17_h_inicio ) ) {

            $tempo_ini = mktime( substr( $ed17_h_inicio, 0, 2 ), substr( $ed17_h_inicio, 3, 2 ), 0, 1, 1, 1999 );
            $tempo_fim = mktime( substr( $ed17_h_fim, 0, 2 ), substr( $ed17_h_fim, 3, 2 ), 0, 1, 1, 1999 );
            $difermin  = ( $tempo_fim - $tempo_ini ) / 60;
            $difer     = ceil( $difermin / 2 );

            $sHtml .= '<table id="tab'.$ed58_i_codigo.'"';
            $sHtml .= '       width="'.$larg_dia.'" ';
            $sHtml .= '       border="0" ';
            $sHtml .= '       height="'.$difer.'"';
            $sHtml .= '       style="background:'.$_SESSION["sess_corhorario"][$ed17_i_escola].';border:1px outset #000000;position:absolute;top:'.$ini_top.'px;left:'.$ini_left.'px;"';
            $sHtml .= '       cellspacing="0"';
            $sHtml .= '       cellpadding="0">';
            $sHtml .= '<tr>';
            $sHtml .= '<td style="font-size:8px;"';
            $sHtml .= '    align="center"';
            $sHtml .= '    onmouseover="js_Mover(\'tab'.$ed58_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim.'\',\''.$ed17_i_escola.'\',\''.$ed18_c_nome.'\',\''.$ed08_c_descr.'\',\''.$ed15_c_nome.'\',\''.$ed57_c_descr.'\',\''.$ed232_c_descr.'\',\''.$ed11_c_descr.'\',\''.$ed10_c_abrev.'\',\''.$_SESSION["sess_corhorario"][$ed17_i_escola].'\',\''.$identificacao.'\')"';
            $sHtml .= '    onmouseout="js_Mout(\'tab'.$ed58_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim.'\')">';
            $sHtml .= 'Escola: '.$ed17_i_escola.' Turma: '.substr($ed57_c_descr,0,10).'<br>'.substr($ed232_c_descr,0,20);
            $sHtml .= '</td>';
            $sHtml .= '</tr>';
            $sHtml .= '</table>';
          }
        }
      }

      if( $clrechumanohoradisp->numrows > 0 && $clrechumano->numrows > 0 && $oPost->ano == date("Y") ) {

        for( $y = 0; $y < $clrechumanohoradisp->numrows; $y++ ) {

          db_fieldsmemory( $result2, $y );

          if( trim( $hora ) == trim( $ed17_h_inicio ) ) {

           $tempo_ini = mktime( substr( $ed17_h_inicio, 0, 2 ), substr( $ed17_h_inicio, 3, 2 ), 0, 1, 1, 1999 );
           $tempo_fim = mktime( substr( $ed17_h_fim, 0, 2 ), substr( $ed17_h_fim, 3, 2 ), 0, 1, 1, 1999 );
           $difermin  = ( $tempo_fim - $tempo_ini ) / 60;
           $difer     = ceil( $difermin / 2 );

           $sHtml .= '<table id="tabb'.$ed33_i_codigo.'"';
           $sHtml .= '       width="'.$larg_dia.'"';
           $sHtml .= '       border="0"';
           $sHtml .= '       height="'.$difer.'"';
           $sHtml .= '       style="background:'.(isset($_SESSION["sess_corhorario"][$ed17_i_escola])?$_SESSION["sess_corhorario"][$ed17_i_escola]:$_SESSION["sess_cordisp"][$ed17_i_escola]).';border:1px outset #000000;position:absolute;top:'.$ini_top.'px;left:'.$ini_left.'px;"';
           $sHtml .= '       cellspacing="0"';
           $sHtml .= '       cellpadding="0">';
           $sHtml .= '<tr>';
           $sHtml .= '<td style="font-size:8px;"';
           $sHtml .= '    align="center" ';
           $sHtml .= '    onmouseover="js_Mover2(\'tabb'.$ed33_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim.'\',\''.$ed17_i_escola.'\',\''.$ed18_c_nome.'\',\''.$ed08_c_descr.'\',\''.$ed15_c_nome.'\',\''.(isset($_SESSION["sess_corhorario"][$ed17_i_escola])?$_SESSION["sess_corhorario"][$ed17_i_escola]:$_SESSION["sess_cordisp"][$ed17_i_escola]).'\',\''.$identificacao.'\')"';
           $sHtml .= '    onmouseout="js_Mout2(\'tabb'.$ed33_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim.'\')">';
           $sHtml .= '</td>';
           $sHtml .= '</tr>';
           $sHtml .= '</table>';
          }
        }
      }

      if( $clturmaachorario->numrows > 0 ) {

        for( $p = 0; $p < $clturmaachorario->numrows; $p++ ) {

          db_fieldsmemory( $resultturma, $p );

          if( trim( $hora ) == trim( $ed17_h_inicio ) ) {

            $tempo_ini = mktime( substr( $ed17_h_inicio, 0, 2 ), substr( $ed17_h_inicio, 3, 2 ), 0, 1, 1, 1999 );
            $tempo_fim = mktime( substr( $ed17_h_fim, 0, 2 ), substr( $ed17_h_fim, 3, 2 ), 0, 1, 1, 1999 );
            $difermin  = ( $tempo_fim - $tempo_ini ) / 60;
            $difer     = ceil( $difermin / 2 );

            $sHtml .= '<table id="tab'.$ed270_i_codigo.'"';
            $sHtml .= '       width="'.$larg_dia.'" ';
            $sHtml .= '       border="0" ';
            $sHtml .= '       height="'.$difer.'"';
            $sHtml .= '       style="background:'.$_SESSION["sess_corhorario"][$ed17_i_escola].';border:1px outset #000000;position:absolute;top:'.$ini_top.'px;left:'.$ini_left.'px;"';
            $sHtml .= '       cellspacing="0" ';
            $sHtml .= '       cellpadding="0">';
            $sHtml .= '<tr>';
            $sHtml .= '<td style="font-size:8px;"';
            $sHtml .= '    align="center"';
            $sHtml .= '    onmouseover="js_Mover33(\'tab'.$ed270_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim.'\',\''.$ed17_i_escola.'\',\''.$ed18_c_nome.'\',\''.$ed08_c_descr.'\',\''.$ed268_c_descr.'\',\''.$ed15_c_nome.'\',\''.$ed20_i_codigo.'\',\''.$_SESSION["sess_corhorario"][$ed17_i_escola].'\',\''.$identificacao.'\')"';
            $sHtml .= '    onmouseout="js_Mout(\'tab'.$ed270_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim.'\')">';
            $sHtml .= 'Escola: '.$ed17_i_escola.' Turma: '.substr($ed268_c_descr,0,10);
            $sHtml .= '</td>';
            $sHtml .= '</tr>';
            $sHtml .= '</table>';
          }
        }
      }

      $tt++;
      if( $tt == 60 ) {

        $t  += 40;
        $tt  = 0;
      }

      $ini_top += 0.5;
    }

    $ini_left += $larg_dia;
  }

  $sHtml .= '</td></tr>';
  $oJson  = new services_json();
  echo $oJson->encode(urlencode($sHtml));
}

if( $oPost->sAction == 'MontaEscola') {

  $resultano  = " select distinct ed18_i_codigo,ed18_c_nome";
  $resultano .= "   from regenciahorario";
  $resultano .= "        inner join periodoescola on periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo";
  $resultano .= "        inner join regencia      on regencia.ed59_i_codigo      = regenciahorario.ed58_i_regencia";
  $resultano .= "        inner join rechumano     on rechumano.ed20_i_codigo     = regenciahorario.ed58_i_rechumano";
  $resultano .= "        inner join diasemana     on diasemana.ed32_i_codigo     = regenciahorario.ed58_i_diasemana";
  $resultano .= "        inner join escola        on escola.ed18_i_codigo        = periodoescola.ed17_i_escola";
  $resultano .= "        inner join turma         on turma.ed57_i_codigo         = regencia.ed59_i_turma";
  $resultano .= "        inner join calendario    on calendario.ed52_i_codigo    = turma.ed57_i_calendario";
  $resultano .= "  where ed58_i_rechumano={$oPost->rechumano}";
  $resultano .= "    and ed52_i_ano={$oPost->ano} ";
  $resultano .= "    and ed58_ativo is true ";
  $resultano .= " union ";
  $resultano .= " select distinct ed18_i_codigo, ed18_c_nome";
  $resultano .= "   from turmaachorario ";
  $resultano .= "        inner join turmaac    on turmaac.ed268_i_codigo   = turmaachorario.ed270_i_turmaac";
  $resultano .= "        inner join calendario on calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
  $resultano .= "        inner join escola     on escola.ed18_i_codigo     = turmaac.ed268_i_escola";
  $resultano .= "  where ed270_i_rechumano = {$oPost->rechumano}";
  $resultano .= "    and ed52_i_ano = {$oPost->ano}";
  $resultano .= "  order by ed18_c_nome";

  $result  = db_query($resultano);
  $aResult = db_utils::getCollectionByRecord( $result, false, false, true );
  $oJson   = new services_json();
  echo $oJson->encode( $aResult );
}