<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_calendario_classe.php");
include("classes/db_feriado_classe.php");
include("classes/db_periodoavaliacao_classe.php");
include("classes/db_periodocalendario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clferiado = new cl_feriado;
$clperiodoavaliacao = new cl_periodoavaliacao;
$clperiodocalendario = new cl_periodocalendario;
if(isset($data_inicio)){
 if($data_inicio>$data_fim){
  ?>
  <script>
   alert("Data inicial é maior que a data final!");
   parent.document.form1.ed53_d_inicio_dia.style.backgroundColor='#99A9AE';
   parent.document.form1.ed53_d_inicio_mes.style.backgroundColor='#99A9AE';
   parent.document.form1.ed53_d_inicio_ano.style.backgroundColor='#99A9AE';
   parent.document.form1.ed53_d_inicio_dia.focus();
  </script>
  <?
 }else{
  $sql3 = $clperiodoavaliacao->sql_query("","*",""," ed09_i_codigo = $periodo ");
  $result3 = $clperiodoavaliacao->sql_record($sql3);
  db_fieldsmemory($result3,0);
  if($ed09_c_somach=="N"){
   ?>
   <script>
    parent.document.form1.ed53_i_diasletivos.value = 0;
    parent.document.form1.ed53_i_semletivas.value = 0;
   </script>
   <?
  }else{
   $data_in = mktime(0,0,0,substr($data_inicio,5,2),substr($data_inicio,8,2),substr($data_inicio,0,4));
   $data_out = mktime(0,0,0,substr($data_fim,5,2),substr($data_fim,8,2),substr($data_fim,0,4));
   #pega a data de saida em UNIX_TIMESTAMP e diminui da data de entrada UNIX_TIMESTAMP
   $data_entre = $data_out - $data_in;
   #divide a diferenca das datas pelo numero de segundos de um dia e arredonda, para saber o numero de dias inteiro que tem
   $dias = ceil($data_entre/86400);
   $dias2 = $dias;
   $day = 0;
   $nao_util = 0;
   #pega dia, mes e ano da data de entrada
   $d = date('d', $data_in);
   $m = date('m', $data_in);
   $y = date('Y', $data_in);
   #pega mes e ano da data de saida
   $m2 = date('m', $data_out);
   $y2 = date('Y', $data_out);
   #conta o numero de dias do mes de entrada
   $days_month = date("t", $data_in);
   $mi = date('m', $data_in);
   $semanas = 0;
   $primeiro_dia = date("w", mktime (0,0,0,substr($data_inicio,5,2),substr($data_inicio,8,2),substr($data_inicio,0,4)));
   #se o dia da entrada + total de dias for menor que total de dias do mes, ou seja, se não passar do mesmo mês.
   if($dias+$d <= $days_month){
    for ($i = 0; $i < $dias+1; $i++){
     if(date("w", mktime (0,0,0,$m,$d+$i,$y))==1){
      $semanas++;
     }
     $day++;
     #checa o dia da semana para cada dia do mês, se for igual a 0 (domingo) ou 6 (sabado) ele adiciona 1 no dia não útil
     if($sabado=="N"){
      if (date("w", mktime (0,0,0,$m,$d+$i,$y)) == 0 || date("w", mktime (0,0,0,$m,$d+$i,$y)) == 6){
       #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
       $res = pg_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario");
       if(pg_num_rows($res)==0){
        $nao_util++;
       }else{
        if(pg_result($res,0,'ed54_c_dialetivo')=="N"){
         $nao_util++;
        }
       }
      }else{
       #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
       $res = pg_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario AND ed54_c_dialetivo = 'N' ");
       if($row = pg_fetch_assoc($res)){
        $nao_util++;
       }
      }
     }else{
      if (date("w", mktime (0,0,0,$m,$d+$i,$y)) == 0 ){
       #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
       $res = pg_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario");
       if(pg_num_rows($res)==0){
        $nao_util++;
       }else{
        if(pg_result($res,0,'ed54_c_dialetivo')=="N"){
         $nao_util++;
        }
       }
      }else{
       #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
       $res = pg_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario AND ed54_c_dialetivo = 'N' ");
       if($row = pg_fetch_assoc($res)){
        $nao_util++;
       }
      }
     }
    }
   #se o dia da entrada + total de dias for maior que total de dias do mes, ou seja, se passar do mesmo mês.
   }else{
    #enquanto o mês de entrada for diferente do mês de saida ou ano de entrada for diferente do ano de saida.
    while($m != $m2 || $y != $y2){
     #pega total de dias do mes de entrada
     if($m==$mi){
      $days_month = date("t", mktime (0,0,0,$m,$d,$y))-$d+1;
     }else{
      $days_month = date("t", mktime (0,0,0,$m,$d,$y));
     }
     for ($i = 0; $i < $days_month; $i++){
      $day++;
      if(date("w", mktime (0,0,0,$m,$d+$i,$y))==1){
       $semanas++;
      }
      #checa o dia da semana para cada dia do mês, se for igual a 0 (domingo) ou 6 (sabado) ele adiciona 1 no dia não útil
      if($sabado=="N"){
       if (date("w", mktime (0,0,0,$m,$d+$i,$y)) == 0 || date("w", mktime (0,0,0,$m,$d+$i,$y)) == 6){
        #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
        $res = pg_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario");
        if(pg_num_rows($res)==0){
         $nao_util++;
        }else{
         if(pg_result($res,0,'ed54_c_dialetivo')=="N"){
          $nao_util++;
         }
        }
       }else{
        #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
        $res = pg_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario AND ed54_c_dialetivo = 'N' ");
        if($row = pg_fetch_assoc($res)){
         $nao_util++;
        }
       }
      }else{
       if (date("w", mktime (0,0,0,$m,$d+$i,$y)) == 0 ){
        #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
        $res = pg_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario");
        if(pg_num_rows($res)==0){
         $nao_util++;
        }else{
         if(pg_result($res,0,'ed54_c_dialetivo')=="N"){
          $nao_util++;
         }
        }
       }else{
        #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
        $res = pg_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario AND ed54_c_dialetivo = 'N' ");
        if($row = pg_fetch_assoc($res)){
         $nao_util++;
        }
       }
      }
     }
     #se o mes for igual a 12 (dezembro), mes recebe 1 (janeiro) e ano recebe +1 (próximo ano)
     if($m == 12){
      $m = 1;
      $y++;
     #mês recebe mais 1 para fazer o mesmo processo do próximo mês
     }else{
      $m++;
     }
     $d = 1;
     //$dias2 = $dias2 - $day;
     if($m==$m2){
      $d3 = date('d', $data_out);
      $m3 = date('m', $data_out);
      $y3 = date('Y', $data_out);
      for ($i = 0; $i < $d3; $i++){
       $day++;
       if(date("w", mktime (0,0,0,$m3,$d+$i,$y3))==1){
        $semanas++;
       }       
       #checa o dia da semana para cada dia do mês, se for igual a 0 (domingo) ou 6 (sabado) ele adiciona 1 no dia não útil
       if($sabado=="N"){
        if(date("w", mktime (0,0,0,$m3,$d+$i,$y3)) == 0 || date("w", mktime (0,0,0,$m3,$d+$i,$y3)) == 6){
         #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
         $res = pg_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m3 AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario");
         if(pg_num_rows($res)==0){
          $nao_util++;
         }else{
          if(pg_result($res,0,'ed54_c_dialetivo')=="N"){
           $nao_util++;
          }
         }
        }else{
         #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
         $res = pg_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m3 AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario AND ed54_c_dialetivo = 'N' ");
         if($row = pg_fetch_assoc($res)){
          $nao_util++;
         }
        }
       }else{
        if (date("w", mktime (0,0,0,$m3,$d+$i,$y3)) == 0 ){
         #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
         $res = pg_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m3 AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario");
         if(pg_num_rows($res)==0){
          $nao_util++;
         }else{
          if(pg_result($res,0,'ed54_c_dialetivo')=="N"){
           $nao_util++;
          }
         }
        }else{
         #pesquisa no banco os feriados cadastrados se retornar aquele dia ele adiciona 1 no dia não útil
         $res = pg_query("SELECT * FROM feriado WHERE extract(month from ed54_d_data)=$m3 AND extract(day from ed54_d_data)=$d+$i AND ed54_i_calendario=$calendario AND ed54_c_dialetivo = 'N' ");
         if($row = pg_fetch_assoc($res)){
          $nao_util++;
         }
        }
       }
      }
     }
    }
   }
   $diasletivos = $day-$nao_util;
   if($primeiro_dia>1 && $primeiro_dia<6){
   	 $semanas++;
   }
   $semletivas = $semanas;   	
   if($diasletivos<$dias_per){
     $dif_total_dias = $dias_per-$diasletivos;
     $dif_total_semanas = $semanas_per-$semletivas;
     $total_dias = $total_dias-$dif_total_dias;
     $total_semanas = $total_semanas-$dif_total_semanas;
   }elseif($diasletivos>$dias_per){
     $dif_total_dias = $diasletivos-$dias_per;
     $dif_total_semanas = $semletivas-$semanas_per;
     $total_dias = $total_dias+$dif_total_dias;
     $total_semanas = $total_semanas+$dif_total_semanas;
   }
   ?>
   <script>
    parent.document.form1.ed53_i_diasletivos.value = <?=$diasletivos?>;
    parent.document.form1.ed53_i_semletivas.value = <?=$semletivas?>;
    parent.document.form1.dias.value = <?=$total_dias?>;
    parent.document.form1.semanas.value = <?=$total_semanas?>;
   </script>
   <?
  }
 }
}
?>