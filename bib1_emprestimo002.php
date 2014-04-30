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

require("libs/db_stdlib.php");
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
$Y = $ano;// Ano
$G = ($Y % 19) + 1; // Numero Áureo
$C = intval(($Y/100) + 1); // Seculo
$X=  intval(((3*$C)/4) - 12); // Primeira Correção
$Z = intval((((8*$C)+5)/25) -5);//Epacta
$E = ((11*$G) + 20 + $Z - $X) % 30;

if((($E == 25) AND ($G > 11)) OR ($E == 24)){
 $E+=1;
}
$N=44-$E; // Lua Cheia
if($N < 21){
 $N+=30;
}
$D=intval(((5*$Y)/4)) -($X + 10);//Domingo
$N=($N+7)-($D+$N)%7;
if($N > 31){
 $diapascoa=$N-31;
 $diames=4;
}else{
 $diapascoa=$N;
 $diames=3;
}

$feriado = array(date("Y-m-d",mktime(0,0,0,$diames,$diapascoa-47,$Y))//CARNAVAL
                ,date("Y-m-d",mktime(0,0,0,$diames,$diapascoa-2,$Y))//PAIXÃO DE CRISTO
                ,date("Y-m-d",mktime(0,0,0,$diames,$diapascoa,$Y))//PÁSCOA
                ,date("Y-m-d",mktime(0,0,0,$diames,$diapascoa+60,$Y))//CORPUS CHRISTI
                ,$ano."-01-01"//CONFRATERNIZAÇÂO UNIVERSAL
                ,$ano."-04-21"//TIRADENTES
                ,$ano."-05-01"//DIA DO TRABALHO
                ,$ano."-09-07"//INDEPENDÊNCIA DO BRASIL
                ,$ano."-10-12"//NOSSA SENHORA APARECIDA
                ,$ano."-11-02"//FINADOS
                ,$ano."-11-15"//PROCLAMAÇÃO DA REPÚBLICA
                ,$ano."-12-25"//NATAL
                );
sort($feriado);
function VerificaFeriado($data_original,$feriado,$msg){
 //verifica se data original é um feriado nacional,
 if($data_original==$feriado){
  //caso data seja um feriado, avança um dia;
  if($msg==false){
   db_msgbox("Data de devolução (".db_formatar($data_original,'d').") é um feriado!\\nO sistema avançou para uma data válida seguinte ao feriado.");
   global $msg;
   $msg = true;
  }
  $nova_data = date("Y-m-d",mktime (0,0,0,substr($data_original,5,2),substr($data_original,8,2)+1,substr($data_original,0,4)));
 }
 if(!isset($nova_data)){
  $nova_data = $data_original;
 }
 return $nova_data;
}

function VerificaSabDom($data_original,$msg){
 if(date("w",mktime (0,0,0,substr($data_original,5,2),substr($data_original,8,2),substr($data_original,0,4))) == 6){
  //Se data for sábado, avança dois dias, para cair na segunda.
  if($msg==false){
   db_msgbox("Data de devolução (".db_formatar($data_original,'d').") é um sábado!\\nO sistema avançou para uma data válida seguinte ao sábado.");
   global $msg;
   $msg = true;
  }
  $nova_data = date("Y-m-d",mktime (0,0,0,substr($data_original,5,2),substr($data_original,8,2)+2,substr($data_original,0,4)));
 }elseif(date("w",mktime (0,0,0,substr($data_original,5,2),substr($data_original,8,2),substr($data_original,0,4))) == 0){
  //Se data for domingo, avança um dia, para cair na segunda.
  if($msg==false){
   db_msgbox("Data de devolução (".db_formatar($data_original,'d').") é um domingo!\\nO sistema avançou para uma data válida seguinte ao domingo.");
   global $msg;
   $msg = true;
  }
  $nova_data = date("Y-m-d",mktime (0,0,0,substr($data_original,5,2),substr($data_original,8,2)+1,substr($data_original,0,4)));
 }else{
  $nova_data = $data_original;
 }
 return $nova_data;
}
$msg = false;
$data_original = $ano."-".$mes."-".$dia;
for($x=0;$x<count($feriado);$x++){
 $data_original = VerificaSabDom($data_original,$msg);
 $data_original = VerificaFeriado($data_original,$feriado[$x],$msg);
}
?>
<script>
parent.document.form1.bi18_devolucao_ano.value = "<?=substr($data_original,0,4)?>";
parent.document.form1.bi18_devolucao_mes.value = "<?=substr($data_original,5,2)?>";
parent.document.form1.bi18_devolucao_dia.value = "<?=substr($data_original,8,2)?>";
parent.document.form1.bi18_devolucao.value = "<?=substr($data_original,8,2)?>/<?=substr($data_original,5,2)?>/<?=substr($data_original,0,4)?>";
parent.document.form1.bi18_devolucao.value = "<?=substr($data_original,8,2)?>/<?=substr($data_original,5,2)?>/<?=substr($data_original,0,4)?>";
d1 = "<?=substr($data_original,8,2)?>";
m1 = "<?=substr($data_original,5,2)?>";
a1 = "<?=substr($data_original,0,4)?>";
data = new Date(a1,m1-1,d1);
diasemana = data.getDay();
if(diasemana==0) diasemana = "DOMINGO";
if(diasemana==1) diasemana = "SEGUNDA";
if(diasemana==2) diasemana = "TERÇA";
if(diasemana==3) diasemana = "QUARTA";
if(diasemana==4) diasemana = "QUINTA";
if(diasemana==5) diasemana = "SEXTA";
if(diasemana==6) diasemana = "SABADO";
parent.document.form1.diasemana.value = diasemana;

</script>