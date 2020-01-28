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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_tpcardapioturma_classe.php");
include("classes/db_mer_cardapiodia_classe.php");
include("classes/db_mer_cardapiodata_classe.php");
include("classes/db_mer_cardapio_classe.php");
include("classes/db_mer_tprefeicao_classe.php");
include("classes/db_mer_tipocardapio_classe.php");
include("classes/db_mer_cardapiotipo_classe.php");
include("classes/db_mer_cardapioitem_classe.php");
include("classes/db_mer_alimentomatmater_classe.php");
include("classes/db_diasemana_classe.php");
include("classes/db_feriado_classe.php");
include("classes/db_calendario_classe.php");
include("dbforms/db_funcoes.php");
$clmer_tpcardapioturma = new cl_mer_tpcardapioturma;
$clmer_cardapiodia     = new cl_mer_cardapiodia;
$clmer_cardapiodata    = new cl_mer_cardapiodata;
$clmer_cardapio        = new cl_mer_cardapio;
$clmer_tprefeicao      = new cl_mer_tprefeicao;
$clmer_tipocardapio    = new cl_mer_tipocardapio;
$clmer_cardapiotipo    = new cl_mer_cardapiotipo;
$clmer_cardapioitem    = new cl_mer_cardapioitem;
$clmer_alimentomatmater    = new cl_mer_alimentomatmater;
$cldiasemana           = new cl_diasemana;
$clferiado             = new cl_feriado;
$clcalendario          = new cl_calendario;
$escola                = db_getsession("DB_coddepto");
$hoje                  = date("Y-m-d",db_getsession("DB_datausu"));
$ed52_i_ano = date('Y');

function montasemana($dData, $iSemana = null, $iAno = null) {

  /*
  Se for passada somente $dData, ou seja, se $iSemana for null (valor default),
  retorna todos os dias da semana da data passada. A semana sempre começa no domingo.
  Por exemplo: se passada a data 12/01/2011, vai retornar um vetor com as datas começando
  no dia 09/01/2011 (domingo) e indo até dia 15/01/2011 (sábado).
  */
  if ($iSemana == null) {

    $dData      = explode('/', $dData);
    // Pego o número do dia da semana. (0 => Domingo, 6 => Sábado)
    $iDiaSemana = date('w', mktime(0, 0, 0, $dData[1], $dData[0], $dData[2]));
    for ($iCont = 0; $iCont < 7; $iCont++) {

      $aSemana[$iCont] = date('d/m/Y', mktime(0, 0, 0, $dData[1], $dData[0] + ($iCont - $iDiaSemana), $dData[2]));

    }

    return $aSemana;

  } else { // Retorna um arrays com os dias da semana  $iSemana do ano $iAno, começando no domingo e indo até sábado

    if ($iAno == null) {
      $iAno = date('Y', db_getsession('DB_datausu'));
    }

    if ($iSemana < 1) {
      $iAno--;
    }

    $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $iAno));
    $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $iAno));
    $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;

    /* Obtenho o número total de semanas do ano em questão e verifico se a semana solicitada é menor ou igual
       a este número. Se não for, vou para o ano seguinte, até, que o número da semana seja menor ou igual
       ao número total de semanas do ano solicitado
    */
    while ($iSemana > $iTotalSemanasAno) {

      $iSemana -= $iTotalSemanasAno;
      $iAno++; // Vou para o próximo ano
      $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $iAno));
      $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $iAno));
      $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;

    }

     /* Obtenho o número total de semanas do ano anterior ($iAno--) e somo ao número da semana solicitada
        enquanto o  número da semana for menor que 1
    */
    while ($iSemana < 1) {

      $iSemana += $iTotalSemanasAno;
      if ($iSemana > 0) {
        break;
      }
      $iAno--; // Vou para o ano anterior
      $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $iAno));
      $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $iAno));
      $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;

    }


    /* Considerando que cada mês tenha 6 semanas (máximo), começo a busca da semana desejada
       a partir de um mês próximo ao que contém a semana desejada
    */
    $iMes       = ceil($iSemana / 6); // Se algum dia der problema, dá pra dividir por um número maior (7, 8)
    $iDia       = -6;
    $iSemanaTmp = 0;
    while ($iSemanaTmp != $iSemana) { // Percorro cada semana na busca da semana desejada

      $iDia      += 7; // Vou  para a próxima semana
      $iSemanaTmp = date('W', mktime(0, 0, 0, $iMes, $iDia, $iAno));

    }
    /* Se o dia da semana for 0 (domingo), tenho que diminuir pelo menos um 1 dia, pois para a função date,
       quando informado o parâmetro W, a semana é contada começando em segunda, e na rotina, como começando em
       domingo, então, se eu passar um domingo, para a função, este será o primeiro dia, enquando que na verdade
       nem faria parte da semana solicitada, pois seria o primeiro dia após o término da semana (que termina
       em sábado)
    */
    if (date('w', mktime(0, 0, 0, $iMes, $iDia, $iAno)) == 0) {
      $iDia -= 1; // Sábado, último dia da semana desejada
    }
    /* Já encontrei um dia da semana desejada, então basta obter os demais dias da semana, usando a mesma função,
       passando somente o primeiro argumento */
    return montasemana(date('d/m/Y', mktime(0, 0, 0, $iMes, $iDia, $iAno)));

  }

}

function semanasigla($data,$comp = 0,$num = 7) {

  if ($num==7) {

    $data  = explode("/", $data);
    $fator = date("w", mktime(0,0,0,$data[1],$data[0],$data[2]));

  } else {
    $fator=$num;
  }
  $sigla="N/A";
  switch ($fator) {

   case 0:

   if ($comp==0) {
   	 $sigla="D";
   } else {
   	 $sigla="Domigo";
   }
   break;

  case 1:

   if ($comp==0) {
   	 $sigla="S";
   } else {
   	 $sigla="Segunda";
   }
   break;

  case 2:

   if ($comp==0) {
   	 $sigla="T";
   } else {
   	 $sigla="Terça";
   }
   break;

  case 3:

   if ($comp==0) {
   	 $sigla="Q";
   } else {
   	 $sigla="Quarta";
   }
   break;

  case 4:

   if ($comp==0) {
   	 $sigla="Q";
   } else {
   	 $sigla="Quinta";
   }
   break;

  case 5:

   if ($comp==0) {
   	 $sigla="S";
   } else {
   	 $sigla="Sexta";
   }
   break;

  case 6:

   if ($comp==0) {
   	 $sigla="S";
   } else {
   	 $sigla="Sabado";
   }
   break;

 }
 return $sigla;
}

function numerosemana($data,$ano = "0") {

  if ($ano=="0") {

    $data      = explode("/",$data);
    $timestamp = mktime(0, 0, 0, $data[1], $data[0], $data[2]);

  } else {

    $data      = date("t/m/Y", mktime(0, 0, 0, 2, 1, $ano));
    $data      = explode("/",$data);
    $timestamp = mktime(0, 0, 0, $data[1], $data[0], $data[2]);

  }
  return date("W", $timestamp);
}

function quantsemana($mes,$ano = "0") {

  if ($ano=="0") {
  	$ano = date("Y",db_getsession("DB_datausu"));
  }
  $fator=0;
  $semana[7]="01/$mes/$ano";
  do {

    $fator++;
    $weeke = date("w", mktime(0,0,0,substr($semana[7],3,2),substr($semana[7],0,2),substr($semana[7],6,4)));
    for ($s=0;$s<8;$s++) {

      $semana[$s] = date("d/m/Y", mktime(0,0,0,
                                         substr($semana[7],3,2),
                                         (substr($semana[7],0,2))+($s+2-($weeke+1)),
                                          substr($semana[7],6,4)
                                      ));

    }
  } while (substr($semana[7],3,2)==$mes);
  return $fator;
}

function somardata($data, $dias= 0, $meses = 0, $ano = 0) {

  $data     = explode("/", $data);
  $novadata = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,   $data[0] + $dias, $data[2] + $ano) );
  return $novadata;

}

$oPost = db_utils::postMemory($_POST);

if ($oPost->sAction == 'VerificaRefeicao') {

  $result_1 = $clmer_cardapioitem->sql_record(
               $clmer_cardapioitem->sql_query_file("",
                                              "*",
                                              "",
                                              " me07_i_cardapio = {$oPost->codrefeicao}
                                              "
                                             )
                                             );
  $result_1 = $clmer_alimentomatmater->sql_record(
               $clmer_alimentomatmater->sql_query_file("",
                                                       "*",
                                                       "",
                                                       " me36_i_alimento in
                                                         (select me07_i_alimento from mer_cardapioitem
                                                          where me07_i_cardapio = {$oPost->codrefeicao}
                                                          and me07_i_alimento = me36_i_alimento
                                                         )
                                                       "
                                             )
                                             );
  $retorno1 = $clmer_alimentomatmater->numrows-$clmer_cardapioitem->numrows;
  $sql_2 = "SELECT *
            FROM mer_cardapiodia
             inner join mer_cardapioaluno on me11_i_cardapiodia = me12_i_codigo
             inner join matricula on ed60_i_codigo  = me11_i_matricula
             inner join turma on ed57_i_codigo = ed60_i_turma
            WHERE me12_i_codigo = {$oPost->codcardapiodia}
            AND ed57_i_escola = $escola
           ";
  $result_2 = pg_query($sql_2);
  $linhas2 = pg_num_rows($result_2);
  $sql_3 = "SELECT *
            FROM mer_cardapiodia
             inner join mer_cardapioturma on me39_i_cardapiodia = me12_i_codigo
             inner join turma on ed57_i_codigo = me39_i_turma
            WHERE me12_i_codigo = {$oPost->codcardapiodia}
            AND ed57_i_escola = $escola
           ";
  $result_3 = pg_query($sql_3);
  $linhas3 = pg_num_rows($result_3);
  $retorno2 = $linhas2+$linhas3;
  $oJson = new services_json();
  echo $oJson->encode(array($retorno1,$retorno2,$oPost->quadro));

}
if ($oPost->sAction == 'MontaGrid') {

  if (isset($oPost->semana)) {

    $ed52_i_ano = date('Y');
    $sHtml = '<table width="100%" height="100%" cellspacing="0" cellpading="0" border="1" bordercolor="#000000">';
    $sHtml .= ' <tr class="cabec">';
    $sHtml .= '  <td><b><center>Tipo Refeição<br>Horário<center></b></td>';

    $semana = montasemana('', $oPost->semana, $ed52_i_ano);

    //print_r($semana);
    $pri_dia = substr($semana[0],6,4)."-".substr($semana[0],3,2)."-".substr($semana[0],0,2);
    $ult_dia = substr($semana[6],6,4)."-".substr($semana[6],3,2)."-".substr($semana[6],0,2);
    if ($oPost->diasemana=='8') {

      $sWhere     = " ed04_i_escola = $escola ";
      $sWhere    .= " AND (ed04_c_letivo = 'S' ";
      $sWhere    .= "  OR (ed04_c_letivo = 'N' ";
      $sWhere    .= "      AND ed32_i_codigo in ";
      $sWhere    .= "      (select extract(day from me12_d_data)";
      $sWhere    .= "       from mer_cardapiodia ";
      $sWhere    .= "        inner join mer_cardapiodiaescola on mer_cardapiodiaescola.me37_i_cardapiodia = mer_cardapiodia.me12_i_codigo ";
      $sWhere    .= "        inner join mer_cardapioescola on mer_cardapioescola.me32_i_codigo = mer_cardapiodiaescola.me37_i_cardapioescola ";
      $sWhere    .= "       where me32_i_escola = $escola ";
      $sWhere    .= "       AND me12_i_cardapio = {$oPost->cardapio} ";
      $sWhere    .= "       AND me12_d_data between '$pri_dia' and '$ult_dia'";
      $sWhere    .= "      )";
      $sWhere    .= "     )";
      $sWhere    .= " ) ";
      $resultdias = $cldiasemana->sql_record(
                                             $cldiasemana->sql_query_rh("",
                                                                        "ed32_i_codigo,ed32_c_descr",
                                                                        "ed32_i_codigo",
                                                                        $sWhere
                                                                       )
                                            );
      $calibra  = @(pg_result($resultdias,0,0)-1);
      $calibra2 = @pg_result($resultdias,(pg_num_rows($resultdias)-1),0);

    } else {

      $calibra = $oPost->diasemana;
      $calibra2 = $oPost->diasemana+1;

    }
    for ($dia=$calibra;$dia<$calibra2;$dia++) {

      $sigla = semanasigla("",1,$dia);
      $d1 = substr($semana[$dia],0,5);
      $sHtml .= '  <td><b><center>'.$sigla.'<br>'.$d1.'</b></center></td>';

    }
    $sHtml .= ' </tr>';
    $campos = " distinct me03_i_codigo,me03_c_tipo,me03_c_inicio,me03_c_fim,me03_i_orden ";
    $result_cardapiotipo = $clmer_cardapiotipo->sql_record(
                            $clmer_cardapiotipo->sql_query("",
                                                           $campos,
                                                           "me03_i_orden",
                                                           " me27_i_codigo = {$oPost->cardapio}"
                                                          )
                                                          );
    $larguradia = 87/($calibra2-$calibra);
    $cont = -1;
    $contcheck = -1;
    for($y=0;$y<$clmer_cardapiotipo->numrows;$y++) {

      db_fieldsmemory($result_cardapiotipo,$y);
      $sHtml .= ' <tr>';
      $sHtml .= '  <td width="13%" height="50" style="background:#f3f3f3">';
      $sHtml .= '   <b><center>'.trim($me03_c_tipo).'<br>'.trim($me03_c_inicio).' - '.trim($me03_c_fim).'</center></b>';
      $sHtml .= '  </td>';
      $d1=$semana[$calibra];
      for($dia=$calibra;$dia<$calibra2;$dia++) {

        $cont++;
        $me12_i_codigo = "";
        $me01_i_codigo = "";
        $d2            = substr($d1,6,4)."-".substr($d1,3,2)."-".substr($d1,0,2);
        $campos        = " me12_i_codigo,me01_i_codigo,me01_c_nome,me01_f_versao,me03_c_fim ";
        $sWhere        = "me12_d_data = '$d2' AND me12_i_tprefeicao = $me03_i_codigo ";
        $sWhere       .= "AND me01_i_tipocardapio = {$oPost->cardapio}";
        $sWhere       .= "AND exists(select * from mer_cardapiodiaescola
                                      inner join mer_cardapioescola on me32_i_codigo = me37_i_cardapioescola
                                     where me37_i_cardapiodia = me12_i_codigo
                                     and me32_i_escola = $escola)";
        $result2       = $clmer_cardapiodia->sql_record(
                                                         $clmer_cardapiodia->sql_query_horario("",
                                                                                                $campos,
                                                                                                "me01_c_nome",
                                                                                                $sWhere
                                                                                              )
                                                       );

        $blokeado = "";
        $block = "";
        $dataatual = date("Ymd",db_getsession("DB_datausu"));
        $horaatual = date("H:i");
        $diagrig   = substr($d1,6,4).substr($d1,3,2).substr($d1,0,2);
        if ($clmer_cardapiodia->numrows==0) {

          if ($diagrig > $dataatual) {

            $blokeado = " disabled ";
            $block = "yes";
            $estilo = "border:2px inset #999999;";

          } else {

            $blokeado = "";
            $block = "";
            $estilo = "border:2px outset #999999;";

          }
          //verificar se o dia é um dia letivo valido ou se é feriado
          $resultferiado = $clferiado->sql_record(
                            $clferiado->sql_query("",
                                                  "*",
                                                  "",
                                                  " ed54_d_data = '$d2'
                                                    AND ed54_c_dialetivo = 'N'"
                                                 )
                                                 );
          if ($clferiado->numrows==0) {

            if (substr($d1,3,2)==$oPost->mes) {

              $nome ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

            } else {

              $nome = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
              $blokeado=" disabled ";
              $block = "yes";
              $estilo = "border:2px inset #999999;";

            }

          } else {

            db_fieldsmemory($resultferiado,0);
            $nome = substr($ed96_c_descr,0,20)."<br>".substr($ed54_c_descr,0,20);
            $blokeado = " disabled ";
            $block = "yes";
            $estilo = "border:2px inset #999999;";

          }
          $sHtml .= '  <td id="td'.$cont.'" width="'.$larguradia.'%"
                        style="font-size:9px;font-weight:bold;'.$estilo.'" valign="top">';
          $sHtml .= '   <table><tr>';
          $sHtml .= '   <td valign="top"><div id="texto'.$cont.'" style="color:'.($blokeado==''?'black':'#777777').'">'.$nome.'</div></td>';
          $sHtml .= '   </tr></table>';
          $sHtml .= '  </td>';


        } else {

          if ($diagrig > $dataatual) {

            $blokeado = " disabled ";
            $block = "yes";
            $estilo = "border:2px inset #999999;";

          } else {

            $blokeado = "";
            $block = "";
            $estilo = "border:2px outset #999999;";

          }
          $sHtml .= '  <td id="td'.$cont.'" width="'.$larguradia.'%"
                        style="font-size:9px;font-weight:bold;'.$estilo.'" valign="top">';
          $sHtml .= '   <table><tr>';
          $sHtml .= '   <td valign="top">';
          for ($g=0;$g<$clmer_cardapiodia->numrows;$g++) {

            db_fieldsmemory($result2,$g);
            //verificar se ja não foi dado baixa no horario
            $sWhere = "me37_i_cardapiodia = $me12_i_codigo and me32_i_escola = $escola";
            $resustdata = $clmer_cardapiodata->sql_record(
                           $clmer_cardapiodata->sql_query_baixa("",
                                                          "*",
                                                          "",
                                                          $sWhere
                                                         )
                                                         );
            if ($clmer_cardapiodata->numrows!=0) {

              $nome = substr(trim($me01_c_nome),0,20)." - Versão: ".trim($me01_f_versao)."<br><font color=red>BAIXADO</font>";
              $blokeado = " disabled ";
              $block = "yes";

            } else {

              $nome = substr(trim($me01_c_nome),0,20)." - Versão: ".trim($me01_f_versao);

            }
            if ($g<$clmer_cardapiodia->numrows-1) {
              $hr = "<hr>";
            } else {
              $hr = "";
            }
            if ($clmer_cardapiodia->numrows>0 && $clmer_cardapiodata->numrows==0 && $block=="") {
              $contcheck++;
              $sHtml .= '  <input onclick="js_verificarefeicao('.$contcheck.','.$me12_i_codigo.','.$me01_i_codigo.')" type="checkbox" name="checkbaixa" id="checkbaixa" value="'.$me12_i_codigo.'">';
            }
            $sHtml .= '   <span id="texto'.$cont.'" style="color:'.($blokeado==''?'black':'#777777').'">'.$nome.'</span>'.$hr;
            //$sHtml .= '   <div onclick="javascript:js_alteraregistro('.$me12_i_codigo.',event,'.($blokeado==''?2:3).')" id="texto'.$cont.'" style="cursor:pointer;color:'.($blokeado==''?'black':'#777777').'">'.$nome.'</div>'.$hr;

          }
          $sHtml .= '   </td></tr></table>';
          $sHtml .= '  </td>';

        }
        $d1=somardata($d1,1);

      }
      $sHtml .= ' </tr>';

    }
    $sHtml .= '</table>';
    $sHtml .= '<center><br><input type="button" name="calcular" id="calcular" value="Calcular" onclick="js_calcular();">';

  }
  $oJson = new services_json();
  echo $oJson->encode(urlencode($sHtml));

}

?>