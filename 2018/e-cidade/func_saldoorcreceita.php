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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcreceita_classe.php");
$clrotulo     = new rotulocampo;
$clorcreceita = new cl_orcreceita;
$clrotulo->label("o70_codrec");
$clrotulo->label("DBtxtmes");
$clrotulo->label("DBtxtmesacumulado");
$clrotulo->label("DBtxtperiodoini");
$clrotulo->label("DBtxtperiodofim");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//-------------------------------------------------------------
if (isset($anousu) and $anousu!=""){
    $anousu = $anousu;
} else {
    $anousu = db_getsession("DB_anousu");
}
//-------------------------------------------------------------
if (isset($estrut) && ($estrut != "")){
    $res= $clorcreceita->sql_record($clorcreceita->sql_query($anousu,null,"o70_codrec",null,"o57_fonte=$estrut"));    
    if ($clorcreceita->numrows > 0){
       db_fieldsmemory($res,0);  
    }
}
//-------------------------------------------------------------
if(isset($o70_codrec) && ($o70_codrec!="")){
    $codrec = $o70_codrec;
}
//-------------------------------------------------------------
if (!isset($tipo_saldo)){
    $tipo_saldo=2;
}
//-------------------------------------------------------------
 $res= $clorcreceita->sql_record("select min(o70_codrec) as minimo, max(o70_codrec) as maximo  from orcreceita");
 if ($clorcreceita->numrows > 0){
    db_fieldsmemory($res,0);  
 }
//-------------------------------------------------------------
    $dt_ini="";
    $dt_fim="";
    if ($tipo_saldo =="2"){
       @$dt_ini = db_getsession("DB_anousu")."-$di_mes-"."01";
       @$dt_fim = db_getsession("DB_anousu")."-$df_mes-"."01";
    } else {  
       @$dt_ini = db_getsession("DB_anousu")."-$di_mes-$di_dia";
       @$dt_fim = db_getsession("DB_anousu")."-$df_mes-$df_dia";
    }  
    if (  (strlen($dt_ini) < 9) || (strlen($dt_fim) < 9 )) {
       unset($dt_ini);
       unset($dt_fim);
    } 
//-------------------------------------------------------------

if(isset($dt_ini)){
   //  $DBtxtperiodoini = $anousu."-".$DBtxtmes."-01";
   //  $DBtxtperiodofim = $anousu."-".$DBtxtmes."-01";
  $result = db_receitasaldo(11,1,$tipo_saldo,true," o70_codrec= $codrec ",$anousu,$dt_ini,$dt_fim);
}else{				
  $result = db_receitasaldo(11,1,$tipo_saldo,true," o70_codrec= $codrec ", $anousu,date("Y-m-d",db_getsession("DB_datausu")),date("Y-m-d",db_getsession("DB_datausu")));
}
if(pg_numrows($result)>0){
  db_fieldsmemory($result,1);
}else{
    // quando não encontra receita
    if (($codrec != $minimo) && ($codrec != $maximo)){
         if (isset($cursor) && ($cursor=="proximo")){
             $codrec +=1;
             echo "<script> location.href='func_saldoorcreceita.php?cursor=proximo&codrec=$codrec';  </script>";
         };
	 if (isset($cursor) && ($cursor=="anterior")){
             $codrec -=1;
             echo "<script> location.href='func_saldoorcreceita.php?cursor=anterior&codrec=$codrec';  </script>";
         };
    } else {
        echo "<center> Receita Não Encontrada </center>";
    }  
}
//-------------------------------------------------------------
//-------------------------------------------------------------
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.descricao {
   height : 40px
}
</style>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_verifica(){
    obj = document.form1;
    var data1 = new Date(obj.di_ano.value,obj.di_mes.value,obj.di_dia.value,0,0,0);
    var data2 = new Date(obj.df_ano.value,obj.df_mes.value,obj.df_dia.value,0,0,0);
    if(data1.valueOf() > data2.valueOf()){
        alert('Data inicial maior que data final. Verifique!');
        return false;
    } else {
       document.form1.submit();
       return true;
    }        
       
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post">
<table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
<tr>
<td valign="top">
  <table border="1"  align="center" cellspacing="0" >

  <tr class="descricao" > 
  <td align="center" colspan="3">Descrição</td>
  </tr>
 
  <?
   for($i=0;$i<pg_numrows($result);$i++){
       db_fieldsmemory($result,$i);
       ?>
       <tr> 
         <td><?=$o57_fonte?></td>
         <td><?=$o57_descr?></td>
       </tr>
       <?
   }
   ?>
      
  <tr> 
  <td>Reduzido</td>
  <td>
    <?
    $o70_codrec=$codrec; //carlos teste
    db_input("o70_codrec",5,$Io70_codrec,true,'text',2," onchange='js_verifica();'");
    ?>
    <? if ($minimo !=$codrec){ ?>
        <input name="anterior" value="Anterior" type="button" onclick="location.href='func_saldoorcreceita.php?cursor=anterior&codrec=<?=($codrec-1<1?$codrec:$codrec-1)?>'">
    <? }  ?>
    <? if ($maximo !=$codrec){ ?>
        <input name="Proximo" value="Próximo" type="button" onclick="location.href='func_saldoorcreceita.php?cursor=proximo&codrec=<?=$codrec+1?>'"></td>
    <? }  ?>
  </tr>

  <tr> 
  <td>Período</td>
  <td>
     <?
       db_inputdata('di',@$di_dia,@$df_mes,@$df_ano,true,'text',1);  
       echo " à ";
       db_inputdata('df',@$df_dia,@$df_mes,@$df_ano,true,'text',1);  
     ?>
  </tr>

  <tr>
  <td> Tipo: </td>
  <td colspan="2" >
     <?
     $x=array("2"=>"Saldo Orçamento (Mensal)",
              "3"=>"Saldo da Contabilidade");
     db_select("tipo_saldo",$x,true,2,"");	 
     ?>
     <input name="pesquisa" value="Pesquisa" type="button" onclick="js_verifica()" >
  </td>
  </tr>


  
</table>
</td>
<td>
</td>

<td valign="top">

<table border="1"  align="center" cellspacing="0" >

<tr class="descricao">
<td align="center" colspan="2" > Financeiro</td>
</tr>



<tr>
<td> Previsão inicial:</td>
<td align="right"><?=db_formatar($saldo_inicial,'f')?> </td>
</tr>
<tr>
<td> Previsão Adicional:</td>
<td align="right"><?=db_formatar($saldo_prevadic_acum,'f')?> </td>
</tr>
<tr>
<td> Total Previsto:</td>
<td align="right"><?=db_formatar($saldo_inicial+$saldo_prevadic_acum,'f')?> </td>
</tr>

<tr>
<td> Arrecadado:</td>
<td align="right"> <?=db_formatar($saldo_arrecadado,'f')?></td>
</tr>

<tr>
<td> Diferença:</td>
<td align="right"> <?=db_formatar($saldo_inicial-($saldo_anterior+$saldo_arrecadado),'f')?></td>
</tr>

<tr>
<td> Arrecadado Anterior:</td>
<td align="right"> <?=db_formatar($saldo_anterior,'f')?></td>
</tr>



<tr>
<td> Adicional Anterior:</td>
<td align="right"> <?=@db_formatar(@$saldo_prev_anterior,'f')?></d>
</tr>


</table>

</td>
</tr>
</table>
</form>
</body>
</html>