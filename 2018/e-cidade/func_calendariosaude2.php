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
 
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_utils.php"));
include(modification("classes/db_undmedhorario_ext_classe.php"));
include(modification("dbforms/db_funcoes.php"));

parse_str( $_SERVER['QUERY_STRING'] ); // ta com o globals desativado no php -- Crestani

class calendario{

  var $sem;//Array com os dias da semana como índice 
  var $mes;//Array com os meses do ano 
  var $nome_objeto_data;
  var $shutdown_function = "";
  var $centralagenda="N";

  function inicializa() {//Atribui valores para $sem e $mes.

    $this->sem=array('Sun'=>1,'Mon'=>2,'Tue'=>3,'Wed'=>4,'Thu'=>5,'Fri'=>6,'Sat'=>7);
    $this->mes=array('1'=>'JANEIRO',
                     '2'=>'FEVEREIRO',
                     '3'=>'MARÇO',
                     '4'=>'ABRIL',
                     '5'=>'MAIO',
                     '6'=>'JUNHO',
                     '7'=>'JULHO',
                     '8'=>'AGOSTO',
                     '9'=>'SETEMBRO',
                     '10'=>'OUTUBRO',
                     '11'=>'NOVEMBRO',
                     '12'=>'DEZEMBRO'
                    );
  } 

  function aux($i) {//Complementa a tabela com espaços em branco

    $retval = ""; 
    for ($k = 0; $k < $i; $k++) { 
      $retval .= "<td width=\"20\">&nbsp;</td>"; 
    } 
    return $retval; 
  }

  function arr_search( $array, $valor ) {

    for ($x = 0; $x < count($array); $x++) {

      if( $array[$x] == $valor ){
        return true;
      }
    }
    return false;
  }

  function cria($dia, $mes, $ano, $marca=0, $str_where, $result_unidademedico ,$fechar=false, $iUpsSolicitante, $iUpsPrestadora ) {

    $this->inicializa();
    $last  = date("d", mktime (0,0,0,$mes+1,0,$ano));
    if($last<$dia) {
      $dia = $last;
    }
    $verf   = date ("d/n/Y", mktime (0,0,0,$mes,$dia,$ano));/*Corrige qualquer data invalida*/
    $pieces = explode("/",$verf);
    $dia    = $pieces[0];
    $mes    = $pieces[1];
    $ano    = $pieces[2];
    $diasem = date ("D", mktime (0,0,0,$mes,1,$ano));/*String com dia da semana em inglês*/
    $str    = "";
    if ($this->sem[$diasem] != 1) {

      $valor=$this->sem[$diasem]-1;
      $str="<tr align=center >".$this->aux($valor);
    }

    //Pega os dias da semana
    for( $x=0; $x < pg_num_rows( $result_unidademedico ); $x++){

      $obj_result   = db_utils::fieldsMemory($result_unidademedico,$x);
      $arr_diasem[] = $obj_result->sd30_i_diasemana;
    }

    //Verifica se é para aparecer agendamentos anteriores
    $clsau_config  = db_utils::getDao("sau_config_ext");
    $resSau_Config = $clsau_config->sql_record( $clsau_config->sql_query_ext() );
    $objSau_Config = db_utils::fieldsMemory($resSau_Config,0);

    for ($i = 1; $i < ($last+1); $i++){       //; pega todos os dias do mes informado....

      $diasem=date ("D", mktime (0,0,0,$mes,$i,$ano));
      if($this->sem[$diasem] == 1) {

        $str .= "<tr align=\"center\" >";
        $s    = "$i";
      } else {
        $s="$i";
      }

      $data_script  = "$ano-$mes-$s";
      $str         .= "<td     ";

      if ($marca != 0) {  // marca o dia atual em laranja
        $str .= "onmouseover=\"js_mostra('parecerr".$i."',event); \"onmouseout=\"js_oculta('parecerr".$i."',event);\" ";
      }
      $str .=" width=\"25\" ";

      if (( $ano.str_pad($mes,2,'0',STR_PAD_LEFT).str_pad($i,2,'0',STR_PAD_LEFT) >= date("Ymd")
           || $objSau_Config->s103_c_cancelafa == 'S' ) &&
          ( isset($arr_diasem) && $this->arr_search( $arr_diasem, $this->sem[$diasem] ) )
         ) {

        //Verifica se unidade é da central
        $booMostradiv           = false;
        $sMotivosAusenciasHoras = array();
        $sWhereCotas            = "";

        /**
         * Quando a unidade selecionada(prestadora) é diferente a da unidade logada(solicitante), valida se há cotas
         * lançadas que permitam o agendamento
         */
        if( $iUpsSolicitante != $iUpsPrestadora ) {

          $sWhereCotas  = " AND exists(select 1";
          $sWhereCotas .= "              from sau_cotasagendamentoprofissional";
          $sWhereCotas .= "                   inner join sau_cotasagendamento    on s163_i_codigo = s164_cotaagendamento";
          $sWhereCotas .= "                   inner join especmedico especm      on especm.sd27_i_codigo = s164_especmedico";
          $sWhereCotas .= "                   inner join unidademedicos unidadem on unidadem.sd04_i_codigo = especm.sd27_i_undmed";
          $sWhereCotas .= "             where s164_especmedico = especm.sd27_i_codigo";
          $sWhereCotas .= "               and unidadem.sd04_i_unidade = {$iUpsPrestadora}";
          $sWhereCotas .= "               and s163_i_mescomp          = {$mes}";
          $sWhereCotas .= "               and s163_i_anocomp          = {$ano})";
        }

        if ($this->centralagenda == "S") {

          $str_query            = cl_undmedhorario_ext::sql_calendario2($ano,
                                                                        $mes,
                                                                        $s,
                                                                        $str_where,
                                                                        $this->sem[$diasem],
                                                                        "");
          $result_undmedhorario = db_query( $str_query ) or die( "ERRO: <p> $str_query ");
          $iTam                 = pg_num_rows($result_undmedhorario);
        } else {

          $str_query = "select sd30_i_turno,
                               sd30_i_fichas,
                               sd30_i_reservas,
                               sd30_c_horaini,
                               sd30_c_horafim,
                               sd04_i_codigo,
                               sd101_c_descr,
                               sd30_i_codigo,
                               ausencias.sd06_c_horainicio,
                               ausencias.sd06_c_horafim,
                               case when sd06_i_undmedhorario=sd30_i_codigo
                                    then sd06_i_undmedhorario
                                    else null
                                end as sd06_i_undmedhorario,
                               case when (    sd06_i_undmedhorario = undmedhorario.sd30_i_codigo
                                           or ausencias.sd06_i_undmedhorario is null)
                                    then sau_motivo_ausencia.s139_c_descr
                                    else null
                                end as sd06_c_tipo,
                               ( select count(sd23_d_consulta)
                                   from agendamentos
                                   where sd23_d_consulta = '$ano/$mes/$s'
                                    and not exists ( select *
                                                       from agendaconsultaanula
                                                      where s114_i_agendaconsulta = sd23_i_codigo )
                                    and sd23_i_undmedhor= sd30_i_codigo
                                  group by sd23_d_consulta
                               )::integer as total_agendado
                          from undmedhorario
                               inner join especmedico    on sd27_i_codigo  = sd30_i_undmed
                               inner join unidademedicos on sd04_i_codigo  = sd27_i_undmed
                               inner join medicos        on sd03_i_codigo  = sd04_i_medico
                               left  join sau_tipoficha  on sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha
                               left  join ausencias      on '$ano/$mes/$s' between ausencias.sd06_d_inicio and ausencias.sd06_d_fim
                                                        and ( ( (ausencias.sd06_i_especmed = especmedico.sd27_i_codigo)
                                                                 and (ausencias.sd06_i_undmedhorario is null)
                                                                )
                                                              or
                                                              (ausencias.sd06_i_undmedhorario = undmedhorario.sd30_i_codigo)
                                                            )
                               left  join sau_motivo_ausencia on ausencias.sd06_i_tipo = sau_motivo_ausencia.s139_i_codigo
                          where {$str_where}
                            {$sWhereCotas}
                            and sd30_i_diasemana = {$this->sem[$diasem]}
                            and (    sd30_d_valfinal is null
                                  or ( sd30_d_valfinal is not null and sd30_d_valfinal >= '$ano/$mes/$s' ) )
                           and (    sd30_d_valinicial is null
                                 or ( sd30_d_valinicial is not null and sd30_d_valinicial <= '$ano/$mes/$s' ) )";
          $result_undmedhorario = db_query( $str_query ) or die( "ERRO: <p> $str_query ");
          $iTam                 = pg_num_rows($result_undmedhorario);

          if ($iTam > 0) {

            $sMotivosAusencias      = "";
            $sSep                   = "";
            $iDisponivel            = 0;
            for ($iY = 0; $iY < $iTam; $iY++) {

              $obj_undmedhorario  = db_utils::fieldsMemory($result_undmedhorario,$iY);
              if (    $obj_undmedhorario->sd06_c_tipo != ''
                   && (    $obj_undmedhorario->sd06_c_horainicio == ''
                        || $obj_undmedhorario->sd06_i_undmedhorario != '' )
                   || (    $obj_undmedhorario->sd06_c_horainicio <=  $obj_undmedhorario->sd30_c_horaini
                        && $obj_undmedhorario->sd06_c_horafim >=  $obj_undmedhorario->sd30_c_horafim )
                 ) {

                  $sMotivosAusencias .= $sSep.$obj_undmedhorario->sd06_c_tipo;
                  $sSep               = ", ";
              } else {

                $iDisponivel = 1;
                $sMotivosAusenciasHoras[$obj_undmedhorario->sd06_c_horainicio] = ' as '.
                                                                                 $obj_undmedhorario->sd06_c_horafim.
                                                                                 ' - motivo: '.
                                                                                 $obj_undmedhorario->sd06_c_tipo;
              }
            }

            if ($sMotivosAusencias != "") {

              if ($iDisponivel == 0) {

                $str         .= " bgcolor=white > ";
                $str         .= "<a href=\"\" onclick=\"return janela($s,$mes,$ano,$fechar);\"><font size='4'>$s</a>";
                $str_msg      = "Profissional não esta atendendo.<br> Motivo: $sMotivosAusencias ";
                $booMostradiv = true;
              } else {

                $clsau_upsparalisada  = db_utils::getDao("sau_upsparalisada_ext");
                $sRet                 = $clsau_upsparalisada->UpsParalisada(db_getsession("DB_coddepto"),
                                                                           "'$ano/$mes/$s'",
                                                                           false
                                                                          );
                if ($sRet != "") {

                  $str         .= " bgcolor=white > ";  // marcar o dia atual
                  $str         .= "<a href=\"\" onclick=\"return janela($s,$mes,$ano,$fechar);\"><font size='4'>$s</a>";
                  $str_msg      = "Unidade paralisada.<br> Motivo: {$sRet} ";
                  $booMostradiv = true;
                }
              }
            } else {

              $clsau_upsparalisada  = db_utils::getDao("sau_upsparalisada_ext");
              $sRet                 = $clsau_upsparalisada->UpsParalisada(db_getsession("DB_coddepto"),
                                                                          "'$ano/$mes/$s'",
                                                                          false
                                                                         );
              if ($sRet != "") {

                $str         .= " bgcolor=white > ";  // marcar o dia atual
                $str         .=" <a href=\"\" onclick=\"return janela($s,$mes,$ano,$fechar);\"><font size='4'>$s</a>";
                $str_msg      = "Unidade paralisada.<br> Motivo: {$sRet} ";
                $booMostradiv = true;
              }
            }
          }
        }

        if ($booMostradiv == false && isset( $iTam ) && $iTam > 0) {

          //Pega mais de uma hora para o mesmo dia
          $str_msg            = "";
          $int_sd30_i_fichas  = 0;
          $int_total_agendado = 0;
          $str_br             = "";
          $iHorario           = -1; // Controla o último horário pego, para não pegar de novo

          for ($xHora = 0; $xHora < $iTam; $xHora++) {

            $obj_undmedhorario   = db_utils::fieldsMemory($result_undmedhorario,$xHora);

            if ($obj_undmedhorario->sd30_i_codigo != $iHorario) {

              $iHorario            = $obj_undmedhorario->sd30_i_codigo;
              $int_total_agendado += $obj_undmedhorario->total_agendado;
              $int_sd30_i_fichas  += $obj_undmedhorario->sd30_i_fichas + $obj_undmedhorario->sd30_i_reservas;
              $str_msg            .= $str_br."{$obj_undmedhorario->sd101_c_descr} {$obj_undmedhorario->sd30_c_horaini} ";
              $str_msg            .=  "as {$obj_undmedhorario->sd30_c_horafim} ";

              if (   ( !isset( $obj_undmedhorario->sd06_c_tipo ) || $obj_undmedhorario->sd06_c_tipo == "")
                  || (     ( isset( $obj_undmedhorario->sd06_c_horainicio ) && $obj_undmedhorario->sd06_c_horainicio != '')
                        && ($obj_undmedhorario->sd06_i_undmedhorario == '') )
                 ) {
           
                $str_msg .=  "- Saldo: ";
                $str_msg .=  ($obj_undmedhorario->sd30_i_fichas+$obj_undmedhorario->sd30_i_reservas - $obj_undmedhorario->total_agendado );
              } else {
                $str_msg            .=  "- Ausencia :".$obj_undmedhorario->sd06_c_tipo;
              }

              $str_br              = "<br>";
            }
          }

          foreach($sMotivosAusenciasHoras  as $key => $value) {

            if ($key != "") {
              $str_msg            .=  $str_br."AUSENCIA   ".$key.$value;
            }
          }

          $str_cor = " bgcolor='' > ";
          if( $this->arr_search( $arr_diasem, $this->sem[$diasem] ) && $int_sd30_i_fichas > 0 ) {

            if( ( $int_sd30_i_fichas - $int_total_agendado ) <= 0 ) {
              $str_cor = " bgcolor=red > ";  // marcar o dia atual
            } else if( $int_total_agendado > 0 ) {
              $str_cor = " bgcolor=yellow > ";  // marcar o dia atual
            } else {
              $str_cor = " bgcolor=#00FF00 > ";  // marcar o dia atual
            }
          }

          $str          .= $str_cor;
          $str          .= " <a href=\"\" ";
          $str          .= " onclick=\"return janela($s,$mes,$ano,$fechar,";
          $str          .= ($obj_undmedhorario->sd30_i_fichas - $obj_undmedhorario->total_agendado ).");\">";
          $str          .= " <font size='4'> $s</a> ";
          $booMostradiv  = true;
        }

        if ($booMostradiv) {

          //*Div
          $str .= "  <div  onmouseover=js_oculta('parecerr$i',event)  
                           name='parecerr$i' 
                           id='parecerr$i' 
                           style='position:absolute;visibility:hidden; left: 183px; top: 0px;  ' >
                      <table align='left' height='100%' width='100%' border='1' cellspacing='1' cellpadding='1'>
                       <tr>
                        <td  bgcolor='#f3f3f3'   align='justify'>
                          <FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>
                          <center>Data: $s/$mes/$ano  &nbsp;&nbsp;&nbsp; <a title='Fechar' href='' onclick=js_oculta('parecerr$i',event)>F</a> </center> <hr>
                          $str_msg
                          </FONT>
                         </td>
                       </tr>
                      </table>
                     </div>";
                 //Fim Div
        } else {
          $str .= "><font size='4'> $s";
        }
      } else {
        $str .= "><font size='4'> $s";
      }

      $str .= "</font> </td>";

      if ($this->sem[$diasem] == 7) {
        $str.= "</tr>";
      }
    }

    $diasem = date ("D", mktime (0,0,0,$mes,$last,$ano));

    if ($this->sem[$diasem] != 7) {

      $valor = 7-$this->sem[$diasem];
      $str   = $str.$this->aux($valor)."</tr>";
    }

    $str = "
        <table border=\"1\"  cellspacing=\"0\" cellpadding=\"0\" width=\"180\" height=\"100%\">
          <tr>
            <td colspan='4'>
              <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" height=\"100%\" >
                <tr align=\"center\">
                  <td width=\"97%\" colspan=\"7\" nowrap>
                    <FONT SIZE='1' FACE='Verdana' COLOR='black'>
                      <a href=\"func_calendariosaude2.php?
                               ".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"").
                               "nome_objeto_data=".$this->nome_objeto_data.
                               "&mes_solicitado=".($mes).
                               "&ano_solicitado=".($ano-1).
                               "&".$str_where.
                               "&upssolicitante=" . $iUpsSolicitante.
                               "&upsprestadora=" . $iUpsPrestadora.
                               "&sd02_c_centralagenda={$this->centralagenda}&fechar=true \"> << </a>
                        	   $ano
                      <a href=\"func_calendariosaude2.php?
                               ".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"").
                               "nome_objeto_data=".$this->nome_objeto_data.
                               "&mes_solicitado=".($mes).
                               "&ano_solicitado=".($ano+1).
                               "&".$str_where.
                               "&upssolicitante=" . $iUpsSolicitante.
                               "&upsprestadora=" . $iUpsPrestadora.
                               "&sd02_c_centralagenda={$this->centralagenda}
                               &fechar=true \"> >> </a>
                    </font>
                </td>
              </tr>
              <tr align=\"center\">
                <td width=\"97%\" colspan=\"7\" nowrap>
                  <FONT SIZE='1' FACE='Verdana' COLOR='black'>
                    <a href=\"func_calendariosaude2.php?
                              ".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"").
                              "nome_objeto_data=".$this->nome_objeto_data.
                              "&ano_solicitado=".($ano).
                              "&mes_solicitado=".($mes-1).
                              "&".$str_where.
                              "&upssolicitante=" . $iUpsSolicitante.
                              "&upsprestadora=" . $iUpsPrestadora.
                              "&sd02_c_centralagenda={$this->centralagenda}
                              &fechar=true \"> << </a>
                    ".$this->mes[$mes]."
                    <a href=\"func_calendariosaude2.php?
                             ".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"").
                             "nome_objeto_data=".$this->nome_objeto_data.
                             "&ano_solicitado=".($ano).
                             "&mes_solicitado=".($mes+1).
                             "&".$str_where.
                             "&upssolicitante=" . $iUpsSolicitante.
                             "&upsprestadora=" . $iUpsPrestadora.
                             "&sd02_c_centralagenda={$this->centralagenda}
                             &fechar=true \"> >> </a>
                  </FONT>
                </td>
              </tr>
              <tr align=\"center\">
                <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>D </font></td>
                <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
                <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>T </font></td>
                <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>Q </font></td>
                <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>Q </font></td>
                <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
                <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
              </tr>
              ".$str."
            </table>
          </td>
        </tr>
        <tr>";
    $str .= " <td bgcolor='white'><font size=1>Ausente</td> ";
    $str .= " <td bgcolor='#00FF00'><font size=1>Liberado</td>
              <td bgcolor='yellow'><font size=1>Marcado</td>
              <td bgcolor='red'><font size=1>Lotado</td>
           </tr>
          </table>";
    echo $str; 
  } //fim function 
} 

//Inicializa classe
$clcalendario                   = new calendario;
$clcalendario->nome_objeto_data = $nome_objeto_data;

if( isset( $sd02_c_centralagenda ) ) {
  $clcalendario->centralagenda = $sd02_c_centralagenda != "S" ? "N" : "S";
}

$str_centralagenda = "";

if( $clcalendario->centralagenda == "N" && isset( $sd27_i_codigo ) && !empty( $sd27_i_codigo ) ) {
  $str_where = "sd27_i_codigo={$sd27_i_codigo}";
} else {

  $str_where         = "sd27_i_rhcbo={$sd27_i_rhcbo}";
  $str_centralagenda = "and sd02_c_centralagenda='S'";
}

if (!isset($mes_solicitado)) {
  $mes_solicitado = date("n",db_getsession("DB_datausu"));
} else if ( $mes_solicitado > 12 || $mes_solicitado < 1 ) {

  $ano_solicitado = $mes_solicitado > 12 ? ($ano_solicitado + 1) : ($ano_solicitado - 1);
  $mes_solicitado = $mes_solicitado > 12 ? 1 : 12;
}

if (!isset($ano_solicitado)) {
  $ano_solicitado = date("Y",db_getsession("DB_datausu"));
}

if( isset($shutdown_function) ) {
  $clcalendario->shutdown_function = $shutdown_function;
}

if( ( isset($sd27_i_rhcbo) && (int) $sd27_i_rhcbo != 0 ) ||( isset($sd27_i_codigo) && (int) $sd27_i_codigo != 0 ) ) {

  //Pega total de dias para agendamentos
  $strData  = date("$ano_solicitado")."/";
  $strData .= date("$mes_solicitado")."/";
  $strData .= date("d",db_getsession("DB_datausu"));

  if (checkdate($mes_solicitado, date("d",db_getsession("DB_datausu")), $ano_solicitado) == false) {
    $strData  = date("$ano_solicitado")."/".date("$mes_solicitado")."/01";
  }

  $str_query = "select distinct sd30_i_diasemana
                  from undmedhorario
                       inner join especmedico    on sd27_i_codigo = sd30_i_undmed
                       inner join unidademedicos on sd04_i_codigo = sd27_i_undmed
                       inner join unidades       on sd02_i_codigo = sd04_i_unidade
                 where $str_where
                       $str_centralagenda
                 order by sd30_i_diasemana";

  $result     = db_query($str_query) or die("ERRO: nos horários do profissional.");

  if( pg_num_rows($result) > 0 ) {

    $obj_result = db_utils::fieldsMemory($result,0);
    $fechar     = isset($fechar)?true:0;
    $clcalendario->cria(date("d",db_getsession("DB_datausu")),
                        date("$mes_solicitado"),
                        date("$ano_solicitado"),
                        1,
                        $str_where,
                        $result,
                        $fechar,
                        $upssolicitante,
                        $upsprestadora);
  } else {
    echo "Nenhuma informação encontrada";
  }
} else {
  echo "Não foi informado Profissional."; 
}
?>

<script>

function js_mostra(nomepar,event) {

  PosMouseX = event.layerX;
  PosMouseY = event.layerY;

  if( nomepar != undefined && document.getElementById(nomepar) != undefined) {

    document.getElementById(nomepar).style.top = 100;
	  if( PosMouseY > 80 ) {
	  	document.getElementById(nomepar).style.top = 0;
	  }

	  document.getElementById(nomepar).style.visibility = "visible";
	}
}

function js_oculta(nomepar,event) {

	if( nomepar != undefined && document.getElementById(nomepar) != undefined ) {
		document.getElementById(nomepar).style.visibility = "hidden";
	}
}

function janela(d,m,a,fechar,saldo) {

	data   = new Date(a,m-1,d);
	dia    = data.getDay();
	semana = new Array(6);

	semana[0] = 'Domingo';
	semana[1] = 'Segunda-Feira';
	semana[2] = 'Terça-Feira';
	semana[3] = 'Quarta-Feira';
	semana[4] = 'Quinta-Feira';
	semana[5] = 'Sexta-Feira';
	semana[6] = 'Sábado';

	parent.document.form1.diasemana.value = semana[dia];

  if(parent.document.form1.saldo!=undefined) {
     parent.document.form1.saldo.value = saldo;
  }
  <?php
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = (d<10?'0'+d:d);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = (m<10?'0'+m:m);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = a;\n";
  echo "parent.js_comparaDatas".$nome_objeto_data."((d<10?'0'+d:d),(m<10?'0'+m:m),a);\n";
  
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').setfocus;\n";
  
  if (isset($shutdown_function) && ($shutdown_function!='none')) {
    echo $shutdown_function."\n";
  }
  ?>
  if( fechar == true ) {

  	x = 'parent.iframe_data_<?=$nome_objeto_data?>.hide();\n';
  	eval( x );
  }
}

function janela_zera() {

  <?php
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = '';\n";

  if (isset($shutdown_function) && ($shutdown_function!='none')) {
    echo $shutdown_function."\n";
  }
  ?>
}

</script>
<script type="text/javascript">
(function() {

  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  if( input != null ) {
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  }
})();
</script>
