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
include("classes/db_periodocalendario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcalendario = new cl_calendario;
$clperiodocalendario = new cl_periodocalendario;
if(isset($sabado)){
 $sql = $clperiodocalendario->sql_query("","*",""," ed09_c_somach = 'S' and ed53_i_calendario = $calendario");
 $result = $clperiodocalendario->sql_record($sql);
 $clperiodocalendario->numrows;
 if($clperiodocalendario->numrows>0){
  for($xx=0;$xx<$clperiodocalendario->numrows;$xx++){
    db_fieldsmemory($result,$xx);
    $data_in = mktime(0,0,0,substr($ed53_d_inicio,5,2),substr($ed53_d_inicio,8,2),substr($ed53_d_inicio,0,4));
    $data_out = mktime(0,0,0,substr($ed53_d_fim,5,2),substr($ed53_d_fim,8,2),substr($ed53_d_fim,0,4));
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
    #se o dia da entrada + total de dias for menor que total de dias do mes, ou seja, se não passar do mesmo mês.
    $semanas = 0;
    $primeiro_dia = date("w", mktime (0,0,0,substr($ed53_d_inicio,5,2),substr($ed53_d_inicio,8,2),substr($ed53_d_inicio,0,4)));
    if($dias+$d <= $days_month){
     for ($i = 0; $i < $dias+1; $i++){
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
         if (date("w", mktime (0,0,0,$m3,$d+$i,$y3)) == 0 || date("w", mktime (0,0,0,$m3,$d+$i,$y3)) == 6){
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
    $sql1 = "UPDATE periodocalendario SET
              ed53_i_diasletivos = $diasletivos,
              ed53_i_semletivas = $semletivas
             WHERE ed53_i_codigo = $ed53_i_codigo
            ";
    $query1 = pg_query($sql1);
  }
  $sql2 = $clperiodocalendario->sql_query("","sum(ed53_i_diasletivos) as dias1,sum(ed53_i_semletivas) as semanas1",""," ed53_i_calendario = $calendario AND ed09_c_somach = 'S'");
  $result2 = $clperiodocalendario->sql_record($sql2);
  db_fieldsmemory($result2,0);
  if($dias1==""){
    $dias1 = 0;
    $semanas1 = 0;
  }
  $sql3 = "UPDATE calendario SET
            ed52_i_diasletivos = $dias1,
            ed52_i_semletivas = $semanas1
           WHERE ed52_i_codigo = $calendario
          ";
  $query3 = pg_query($sql3);
  if(isset($feriado)){
   //echo "oioioi";
   ?>
   <script>
    top.corpo.iframe_a1.location.href='edu1_calendario002.php?chavepesquisa=<?=$calendario?>';
    top.corpo.iframe_a2.location.href='edu1_periodocalendario001.php?ed53_i_calendario=<?=$calendario?>&ed52_c_descr=<?=$ed52_c_descr?>';
   </script>
   <?
  }else{
   ?>
   <script>
    parent.document.form1.ed52_i_diasletivos.value = <?=$dias1?>;
    parent.document.form1.ed52_i_semletivas.value = <?=$semanas1?>;
    top.corpo.iframe_a2.location.href='edu1_periodocalendario001.php?ed53_i_calendario=<?=$calendario?>&ed52_c_descr=<?=$ed52_c_descr?>';
    parent.document.form1.alterar.click();
   </script>
   <?
  }
 }else{
  $sql = $clcalendario->sql_query("","ed52_i_codigo,ed52_c_descr",""," ed52_i_codigo = $calendario");
  $result = $clcalendario->sql_record($sql);
  db_fieldsmemory($result,0);
  ?>
  <script>
   top.corpo.iframe_a2.location.href='edu1_periodocalendario001.php?ed53_i_calendario=<?=$ed52_i_codigo?>&ed52_c_descr=<?=$ed52_c_descr?>';
   <?if(!isset($feriado)){?>
    parent.document.form1.alterar.click();
   <?}?>
  </script>
  <?
 }
}
?>