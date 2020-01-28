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
$escola              = db_getsession("DB_coddepto");
$sCampos             = "min(ed17_h_inicio) as menorhorario,max(ed17_h_fim) as maiorhorario";
$result_per          = $clperiodoescola->sql_record($clperiodoescola->sql_query( "", $sCampos ) );

db_fieldsmemory( $result_per, 0 );

$hora1         = (int) substr( $menorhorario, 0, 2 );
$hora2         = (int) substr( $maiorhorario, 0, 2 ) + 1;
$horainicial   = $hora1 * 100;
$horafinal     = $hora2 * 100;
$tempo_ini     = mktime( $hora1, 0, 0, date("m"), date("d"), date("Y") );
$tempo_fim     = mktime( $hora2, 0, 0, date("m"), date("d"), date("Y") );
$difer_minutos = ($tempo_fim - $tempo_ini) / 60;
$alt_tab_hora  = $difer_minutos / 2;
$qtd_hora      = $difer_minutos / 60;
$larg_tabela   = @$larg_obj;
$larg_coluna1  = 40;
$larg_coluna2  = 40;
$tabela1_top   = 20;
$tabela1_left  = 2;

$oPost = db_utils::postMemory($_POST);

if ($oPost->sAction == 'MontaGrade') {
	
  if (isset($oPost->cod_matricula)) {
    $where = " ed20_i_codigo = {$oPost->cod_matricula}";
  } else {
    $where = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end = {$oPost->chavepesquisa}";
  }

  $sWhere  = " {$where} AND ed01_c_regencia = 'S' AND ed75_i_escola = {$escola}";
  $result0 = $clrechumano->sql_record($clrechumano->sql_query_escola( "", "ed20_i_codigo as pranada", "", $sWhere ) );

  if ($oPost->esc_horario != "") {
    $condicao = " AND ed17_i_escola = {$oPost->esc_horario}";
  } else {
    $condicao = "";
  }

  $sHtml    = '<tr><td>';
  $ini_left = $tabela1_left+$larg_coluna1+$larg_coluna2;
  $result   = $cldiasemana->sql_record($cldiasemana->sql_query_rh("",
                                                                  "ed32_i_codigo,ed32_c_abrev,ed32_c_descr",
                                                                  "ed32_i_codigo",
                                                                  " ed04_i_escola = $escola AND ed04_c_letivo = 'S'"
                                                                 ));
  $larg_dia = floor( ( $larg_tabela - $larg_coluna1 - $larg_coluna2 ) / $cldiasemana->numrows );
  
  for ($x = 0; $x < $cldiasemana->numrows; $x++) {
  	
    $ini_top = $tabela1_top+25;
    db_fieldsmemory($result,$x);  
    $sCampos  = " ed20_i_codigo,case when ed20_i_tiposervidor = 1  then cgmrh.z01_nome  else";
    $sCampos .= " cgmcgm.z01_nome end as nomeprof,case when ed20_i_tiposervidor = 1 then";
    $sCampos .= " 'Matrícula: '||rechumanopessoal.ed284_i_rhpessoal else 'CGM: '||rechumanocgm.ed285_i_cgm end";
    $sCampos .= " as identificacao,ed232_c_abrev,ed58_i_codigo,ed08_c_descr,ed18_c_nome,ed15_c_nome,ed17_h_inicio,";
    $sCampos .= " ed17_h_fim,ed57_i_escola,ed57_c_descr,ed232_c_descr,ed11_c_descr,ed10_c_abrev";
    $sOrder   = " ed58_i_diasemana,ed17_h_inicio asc,ed17_h_fim asc";
    $sWhere   = " $where AND ed58_i_diasemana = $ed32_i_codigo AND ed52_i_ano = {$oPost->ano} $condicao and ed58_ativo is true  ";
    $result1  = $clregenciahorario->sql_record($clregenciahorario->sql_query( "", $sCampos, $sOrder, $sWhere ) );
                                              
    if ($clrechumano->numrows > 0 && $oPost->ano == date("Y")) {
    	
      $notexists  = " AND not exists ";
      $notexists .= "            (select * from regenciahorario ";
      $notexists .= "                           inner join regencia on ed59_i_codigo = ed58_i_regencia ";
      $notexists .= "                           inner join periodoescola as pe on pe.ed17_i_codigo = ed58_i_periodo ";
	    $notexists .= " 	                        inner join turma on ed57_i_codigo = ed59_i_turma ";
	    $notexists .= " 	                        inner join calendario on ed52_i_codigo = ed57_i_calendario ";
	    $notexists .= " 	              where ed58_i_rechumano = ed75_i_rechumano and ed58_ativo is true and ed33_ativo is true ";
	    $notexists .= " 	                and ed58_i_diasemana = ed33_i_diasemana ";
	    $notexists .= " 	                and ed52_i_ano = {$oPost->ano} ";
	    $notexists .= " 	                and ( ";
	    $notexists .= " 	                     ((pe.ed17_h_inicio > periodoescola.ed17_h_inicio AND";
	    $notexixts .= "                           pe.ed17_h_inicio < periodoescola.ed17_h_fim) ";
	    $notexists .= " 	                       OR (pe.ed17_h_fim  > periodoescola.ed17_h_inicio AND";
	    $notexists .= "                              pe.ed17_h_fim < periodoescola.ed17_h_fim) ";
	    $notexists .= " 	                     ) ";
	    $notexists .= " 	                     OR (pe.ed17_h_inicio <= periodoescola.ed17_h_inicio AND ";
	    $notexists .= "                            pe.ed17_h_fim >= periodoescola.ed17_h_fim) ";
	    $notexists .= " 	                     OR (pe.ed17_h_inicio >= periodoescola.ed17_h_inicio AND";
	    $notexists .= "                            pe.ed17_h_fim <= periodoescola.ed17_h_fim) ";
	    $notexists .= " 	                     OR (pe.ed17_h_inicio = periodoescola.ed17_h_inicio AND";
	    $notexists .= "                             pe.ed17_h_fim = periodoescola.ed17_h_fim) ";
	    $notexists .= " 	                    ) ";
      $notexists .= "                       ) ";
    } else {
      $notexists = "";
    }

    $sCampos   = "case when ed20_i_tiposervidor = 1 then 'Matrícula: '||rechumanopessoal.ed284_i_rhpessoal else";
    $sCampos  .= " 'CGM: '||rechumanocgm.ed285_i_cgm end as identificacao,case when ed20_i_tiposervidor = 1  then";
    $sCampos  .= " cgmrh.z01_nome  else cgmcgm.z01_nome end as nomeprof,ed33_i_codigo,ed08_c_descr,ed18_c_nome,";
    $sCampos  .= "ed15_c_nome,ed17_h_inicio,ed17_h_fim,ed18_i_codigo as ed17_i_escola,ed20_i_codigo";
    $sOrder    = "ed33_i_diasemana,ed17_h_inicio asc,ed17_h_fim asc";
    $sWhere    =  " $where AND ed33_i_diasemana = $ed32_i_codigo $notexists $condicao";
    $result2   = $clrechumanohoradisp->sql_record($clrechumanohoradisp->sql_query_disponibilidade("",
                                                                                                  $sCampos,
                                                                                                  $sOrder,
                                                                                                  $sWhere
                                                                                                 ));
                                                 
    $sCampos   = "case when ed20_i_tiposervidor = 1 then 'Matrícula: '||rechumanopessoal.ed284_i_rhpessoal else";
    $sCampos  .= " 'CGM: '||rechumanocgm.ed285_i_cgm end as identificacao,case when ed20_i_tiposervidor = 1  then";
    $sCampos  .= " cgmrh.z01_nome  else cgmcgm.z01_nome end as nomeprof,ed270_i_codigo,ed268_i_escola,ed08_c_descr,";
    $sCampos  .= "ed18_c_nome,turno.ed15_c_nome,ed17_h_inicio,ed17_h_fim,ed18_i_codigo as ed17_i_escola,ed20_i_codigo,";
    $sCampos  .= "ed268_i_codigo,ed268_c_descr";
    $sOrder    = "ed270_i_diasemana,ed17_h_inicio asc,ed17_h_fim asc";
    $sWhere    = " $where AND ed270_i_diasemana = $ed32_i_codigo and ed52_i_ano = {$oPost->ano}";                                             
    $result111 = $clturmaachorario->sql_record($clturmaachorario->sql_query( "", $sCampos, $sOrder, $sWhere ) );

    $tt = 0;
    for ($t = $horainicial; $t <= $horafinal; $t+= 1) {
    	
      $hora = strlen($t) == 3 ? "0".$t : $t;
      $hora = substr( $hora, 0, 2 ).":".substr( $hora, 2, 2 );
      if ($clregenciahorario->numrows > 0) {
      	
        for( $y = 0; $y < $clregenciahorario->numrows; $y++ ) {
        	
         db_fieldsmemory( $result1, $y );
          if (trim($hora) == trim($ed17_h_inicio)) {
          	
            $tempo_ini = mktime(substr($ed17_h_inicio, 0, 2), substr( $ed17_h_inicio, 3, 2 ), 0, 1, 1, 1999 );
            $tempo_fim = mktime(substr($ed17_h_fim, 0, 2), substr( $ed17_h_fim, 3, 2 ), 0, 1, 1, 1999 );
            $difermin  = ($tempo_fim - $tempo_ini) / 60;
            $difer     = ceil($difermin / 2);
            $conta     = $y;
            $proximo   = true;
            $array     = array();
            
            while ($proximo == true) {
            	
              $conta++;
              if ($clregenciahorario->numrows > $conta) {
              	
                $oDados = db_utils::fieldsmemory($result1,$conta);               
                if ($ed17_h_inicio == $oDados->ed17_h_inicio) {
                	
                  $array[ ] = $conta;
                  $proximo  = true;
                  $y++;                  
                                    
                }else{
                  $proximo=false;
                }
              }else{
                $proximo=false;
              }
            }

            if (count($array) >0) {
            	  	
           	  $lista = "";
           	  $sep   = "";
              for ($e = 0; $e < count($array); $e++) {
                $lista = $array[$e]; 
                $sep   =",";
              }             
            }

      
            if (count($array) >0) {

      	      $iHoraInicio     = $ed17_h_inicio;
      	      $iHoraFim        = $ed17_h_fim;
      	      $iCodigoEscola   = $ed57_i_escola;
              $sNomeEscola     = $ed18_c_nome;
              $sPeriodoDescr   = $ed08_c_descr;
              $sNomeTurno      = $ed15_c_nome;
              $sNomeTurma      = $ed57_c_descr;
              $sNomeDisciplina = $ed232_c_descr;
              $sNomeSerie      = $ed11_c_descr;
              $sNomeEnsino     = $ed10_c_abrev;
              $iIdent          = $identificacao;
              $sProf           = $nomeprof;
              $iCodigo         = $ed58_i_codigo;
              
              for ($a = 0; $a < count($array); $a++) {
              	
                $oDados           = db_utils::fieldsmemory($result1,$array[$a]);                        
                $iHoraInicio     .= ",".$oDados->ed17_h_inicio;
                $iHoraFim        .= ",".$oDados->ed17_h_fim;
                $iCodigoEscola   .= ",".$oDados->ed57_i_escola;
                $sNomeEscola     .= ",".$oDados->ed18_c_nome;
                $sPeriodoDescr   .= ",".$oDados->ed08_c_descr;
                $sNomeTurno      .= ",".$oDados->ed15_c_nome;
                $sNomeTurma      .= ",".$oDados->ed57_c_descr;
                $sNomeDisciplina .= ",".$oDados->ed232_c_descr;
                $sNomeSerie      .= ",".$oDados->ed11_c_descr;
                $sNomeEnsino     .= ",".$oDados->ed10_c_abrev;
                $iIdent          .= ",".$oDados->identificacao;
                $sProf           .= ",".$oDados->nomeprof;
                
              }
              
              $sHtml .= '<table id="tab'.$ed58_i_codigo.'" width="'.$larg_dia.'" border="0" bgcolor="#CCCCCC"';
              $sHtml .= ' height="'.$difer.'" style="background:'.$_SESSION["sess_corhorario"][$ed57_i_escola];
              $sHtml .= ';border:1px outset #000000;position:absolute;top:'.$ini_top.'px;left:'.$ini_left.'px;"';
              $sHtml .= ' cellspacing="0" cellpadding="0">';
              $sHtml .= '<tr>';   
              $sHtml .= '<div id="teste11" style="position:absolute;">';   
              $sHtml .= '<td id="teste1" style="font-size:8px;" align="center"'; 
              $sHtml .= 'onclick ="js_testesimultaneo(\''.$iHoraInicio.'\',\''.$iHoraFim.'\',\''.$iCodigoEscola;
              $sHtml .= '\',\''.$sNomeEscola.'\',\''.$sPeriodoDescr.'\',\''.$sNomeTurno.'\',\'';
              $sHtml .= $sNomeTurma.'\',\''.$sNomeDisciplina.'\',\''.$sNomeSerie.'\',\''.$sNomeEnsino;
              $sHtml .= '\',\''.$_SESSION["sess_corhorario"][$ed57_i_escola].'\',\''.$iIdent.'\',\''.$sProf;
              $sHtml .= '\',event);"  onmouseover="js_Mover(\'tab'.$ed58_i_codigo.'\',\''.$ed17_h_inicio;
              $sHtml .= '\',\''.$ed17_h_fim.'\',\''.$ed57_i_escola.'\',\''.$ed18_c_nome.'\',\''.$ed08_c_descr;
              $sHtml .= '\',\''.$ed15_c_nome.'\',\''.$ed57_c_descr.'\',\''.$ed232_c_descr.'\',\''.$ed11_c_descr.'\',\'';
              $sHtml .= $ed10_c_abrev.'\',\''.$_SESSION["sess_corhorario"][$ed57_i_escola].'\',\'';
              $sHtml .= $identificacao.'\',\''.$nomeprof.'\')" onmouseout="js_Mout(\'tab'.$ed58_i_codigo.'\',\'';
              $sHtml .= $ed17_h_inicio.'\',\''.$ed17_h_fim.'\')">';
              $sHtml .= 'Atende Simultâneo';
              $sHtml .= '</td>';       
              $sHtml .= '</div>';
              $sHtml .= '</tr>';
              $sHtml .= '</table>';
            } else {
      
              $sHtml .= '<table id="tab'.$ed58_i_codigo.'" width="'.$larg_dia.'" border="0" bgcolor="#CCCCCC" ';
              $sHtml .= 'height="'.$difer.'" style="background:'.$_SESSION["sess_corhorario"][$ed57_i_escola];
              $sHtml .= ';border:1px outset #000000;position:absolute;top:'.$ini_top.'px;left:'.$ini_left.'px;"';
              $sHtml .= ' cellspacing="0" cellpadding="0">';
              $sHtml .= '<tr>';      
              $sHtml .= '<td id="teste1" style="font-size:8px;" align="center" ';
              $sHtml .= 'onclick ="js_testesimultaneo(\''.$ed17_h_inicio.'\',\'';
              $sHtml .= $ed17_h_fim.'\',\''.$ed57_i_escola.'\',\''.$ed18_c_nome.'\',\''.$ed08_c_descr.'\',\'';
              $sHtml .= $ed15_c_nome.'\',\''.$ed57_c_descr.'\',\''.$ed232_c_descr.'\',\''.$ed11_c_descr.'\',\'';
              $sHtml .= $ed10_c_abrev.'\',\''.$_SESSION["sess_corhorario"][$ed57_i_escola].'\',\''.$identificacao;
              $sHtml .= '\',\''.$nomeprof.'\',event);"';
              $sHtml .= 'onmouseover="js_Mover(\'tab'.$ed58_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim;
              $sHtml .= '\',\''.$ed57_i_escola.'\',\''.$ed18_c_nome.'\',\''.$ed08_c_descr.'\',\''.$ed15_c_nome;
              $sHtml .= '\',\''.$ed57_c_descr.'\',\''.$ed232_c_descr.'\',\''.$ed11_c_descr.'\',\'';
              $sHtml .= $ed10_c_abrev.'\',\''.$_SESSION["sess_corhorario"][$ed57_i_escola].'\',\'';
              $sHtml .= $identificacao.'\',\''.$nomeprof.'\')" onmouseout="js_Mout(\'tab'.$ed58_i_codigo.'\',\'';
              $sHtml .= $ed17_h_inicio.'\',\''.$ed17_h_fim.'\')">';
              $sHtml .= 'Escola: '.$ed57_i_escola.' Turma: ';
              $sHtml .= substr($ed57_c_descr,0,10).'<br>'.substr($ed232_c_descr,0,20);
              $sHtml .= '</td>';    
              $sHtml .= '</div>';  
              $sHtml .= '</tr>';
              $sHtml .= '</table>';       
            }
          }
        }
      }
      
      if ($clrechumanohoradisp->numrows > 0 && $clrechumano->numrows > 0 && $oPost->ano == date("Y")) {
      	
        for ($y = 0; $y < $clrechumanohoradisp->numrows; $y++) {
        	
          db_fieldsmemory($result2,$y);
          if (trim($hora) == trim($ed17_h_inicio)) {
          	
            $tempo_ini = mktime(substr($ed17_h_inicio,0,2),substr($ed17_h_inicio,3,2),0,1,1,1999);
            $tempo_fim = mktime(substr($ed17_h_fim,0,2),substr($ed17_h_fim,3,2),0,1,1,1999);
            $difermin  = ($tempo_fim-$tempo_ini)/60;
            $difer     = ceil($difermin/2);
            $sHtml    .= '<table id="tabb'.$ed33_i_codigo.'" width="'.$larg_dia.'" border="0" height="'.$difer.'" ';
            $sHtml    .= ' style="background:'.(isset($_SESSION["sess_corhorario"][$ed17_i_escola])?$_SESSION["sess_corhorario"][$ed17_i_escola]:$_SESSION["sess_cordisp"][$ed17_i_escola]).';border:1px outset #000000;position:absolute;top:'.$ini_top.'px;left:'.$ini_left.'px;" cellspacing="0" cellpadding="0">';
            $sHtml    .= '<tr>';
            $sHtml    .= '<td style="font-size:8px;" align="center" onmouseover="js_Mover2(\'tabb'.$ed33_i_codigo;
            $sHtml    .= '\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim.'\',\''.$ed17_i_escola.'\',\''.$ed18_c_nome.'\',\'';
            $sHtml    .= $ed08_c_descr.'\',\''.$ed15_c_nome.'\',\'';
            $sHtml    .= (isset($_SESSION["sess_corhorario"][$ed17_i_escola])?$_SESSION["sess_corhorario"][$ed17_i_escola]:$_SESSION["sess_cordisp"][$ed17_i_escola]).'\',\''.$identificacao.'\')" onmouseout="js_Mout2(\'tabb'.$ed33_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim.'\')">';
            $sHtml    .= '</td>';
            $sHtml    .= '</tr>';
            $sHtml    .= '</table>';
          }
        }
      }

      if ($clturmaachorario->numrows > 0 && $oPost->ano == date("Y")) {
      	
        for ($r = 0; $r < $clturmaachorario->numrows; $r++) {
        	
          db_fieldsmemory($result111,$r);
          if (trim($hora) == trim($ed17_h_inicio)) {
          	
            $tempo_ini = mktime(substr($ed17_h_inicio,0,2),substr($ed17_h_inicio,3,2),0,1,1,1999);
            $tempo_fim = mktime(substr($ed17_h_fim,0,2),substr($ed17_h_fim,3,2),0,1,1,1999);
            $difermin  = ($tempo_fim-$tempo_ini)/60;
            $difer     = ceil($difermin/2);
            $sHtml    .= '<table id="tab'.$ed270_i_codigo.'" width="'.$larg_dia.'" border="0" bgcolor="#CCCCCC" ';
            $sHtml    .= 'height="'.$difer.'" style="background:'.@$_SESSION["sess_corhorario"][$ed17_i_escola].';';
            $sHtml    .= 'border:1px outset #000000;position:absolute;top:'.$ini_top.'px;left:'.$ini_left.'px;"';
            $sHtml    .= ' cellspacing="0" cellpadding="0">';
            $sHtml    .= '<tr>';
            $sHtml    .= '<td style="font-size:8px;" align="center" onmouseover="js_Mover33(\'tab'.$ed270_i_codigo.'\',';
            $sHtml    .= '\''.$ed17_h_inicio.'\',\''.$ed17_h_fim.'\',\''.$ed17_i_escola.'\',\''.$ed18_c_nome.'\',\'';
            $sHtml    .= $ed08_c_descr.'\',\''.$ed15_c_nome.'\',\''.$ed268_c_descr.'\',\''.$identificacao.'\',\'';
            $sHtml    .= $_SESSION["sess_corhorario"][$ed17_i_escola].'\')" onmouseout="js_Mout(\'tab';
            $sHtml    .= $ed270_i_codigo.'\',\''.$ed17_h_inicio.'\',\''.$ed17_h_fim.'\')">';
            $sHtml    .= 'Escola: '.$ed268_i_escola.' Turma: '.substr($ed268_c_descr,0,10);
            $sHtml    .= '</td>';
            $sHtml    .= '</tr>';
            $sHtml    .= '</table>';
          }
        }
      }
      
      $tt += 1;
      if ($tt == 60) {
      	
        $t += 40;
        $tt = 0;
      }
      
      $ini_top += 0.5;
    }
    $ini_left += $larg_dia;
  }

  $sHtml .= '</td></tr>';
  $oJson  = new services_json();
  echo $oJson->encode(urlencode($sHtml));
}

if ($oPost->sAction == 'MontaEscola') {
	
  if (isset($oPost->cod_matricula)) {
    $where = " ed20_i_codigo = {$oPost->cod_matricula}";
  } else {
    $where  = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else";
    $where .= " cgmcgm.z01_numcgm end = {$oPost->chavepesquisa}";
  }
  
  $resultano = " select distinct ed18_i_codigo,ed18_c_nome from regenciahorario"; 
  $resultano .= " inner join regencia  on  regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia";
  $resultano .= " inner join rechumano  on  rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano";
  $resultano .= " inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
  $resultano .= " inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
  $resultano .= " inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario"; 
  $resultano .= " where $where and ed52_i_ano={$oPost->ano} and ed58_ativo is true  "; 
  $resultano .= " union ";
  $resultano .= " select distinct ed18_i_codigo, ed18_c_nome from turmaachorario ";
  $resultano .= " inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorario.ed270_i_turmaac";
  $resultano .= " inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
  $resultano .= " inner join escola  on  escola.ed18_i_codigo = turmaac.ed268_i_escola";
  $resultano .= " where $where and ed52_i_ano={$oPost->ano} order by ed18_c_nome";
  $aResult    = db_utils::getCollectionByRecord($resultano, false, false, true);
  $oJson      = new services_json();
  echo $oJson->encode($aResult);
}