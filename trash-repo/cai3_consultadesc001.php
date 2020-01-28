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
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
</style>
<script>
function js_verifica(){
   var valor= document.form1.parcel.value;
   if(valor == ''){
     alert('Campo parcelamento em branco. Verifique!');
   }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" onSubmit="return js_verifica();" action="" >
   <table align="center" border="0" cellspacing="0" cellpadding="0" >
	<tr>
          <td colspan="2">&nbsp;
	  </td>
	</tr>
    <tr> 
     <td  align="center" valign="top" bgcolor="#CCCCCC">
	<tr>
    	  <td colspan="2" align="center"><b>&nbsp;Consulta Descontos&nbsp;</b>
  	  </td>
	</tr>
	<tr>
          <td colspan="2">&nbsp;
	  </td>
	</tr>
        <tr>
	  <td title="Número do Parcelamento a ser consultado">&nbsp;<strong>Parcelamento</strong>&nbsp;
	  </td>
 	  <td >&nbsp;
	    <?
	      db_input("parcel",10,"",true,"text",4)
	    ?>
	   &nbsp;&nbsp;&nbsp;<input type="submit" name="pesquisar" value="Pesquisar" id="pesquisar" >
	  </td>
	</tr>
	<tr>
          <td colspan="2">&nbsp;
	  </td>
	</tr>
     <table cellspacing=0 border=1 cellpadding=0 align="center">
<?

if(isset($pesquisar) && $parcel != '' || isset($calcular) || isset($emiterecibo)){

pg_exec("begin");
if(isset($calcular)){
   $xinput = array();
   for($ii = 0;$ii < $qtotal;$ii++ ){
    $valor = "val_$ii";
    $ano   = "data_$ii";
    if($$valor!=""){
      $sql1 = "select fc_infla('VRM',".$$valor.",'".$$ano."-01-01',current_date) as funcao "; 
      $result1 = pg_query($sql1);
      $cor = "cor_$ii";
      $$cor = pg_result($result1,0,0);
    }
   }

}
  
$sql = "
select v01_exerc, 
       sum(vlrcor) as vlrcor,
       sum(vlrjuros) as vlrjuros,
       sum(vlrmulta) as vlrmulta,
       sum(total) as total
       from
 (select 	v01_exerc, 
	  k00_numpre,
	  k00_numpar,
	  k00_dtvenc,
	  substr(fc_calculaold,15,13)::float8 as vlrcor,
	  substr(fc_calculaold,28,13)::float8 as vlrmulta,
	  substr(fc_calculaold,41,13)::float8 as vlrjuros,
	  (substr(fc_calculaold,15,13)::float8+
	   substr(fc_calculaold,28,13)::float8+
	   substr(fc_calculaold,41,13)::float8) as total
    from
   (select x.*,  
	   fc_calculaold(x.k00_numpre,k00_numpar,0,current_date,x.k00_dtvenc," . db_getsession("DB_anousu") . ") 
    from (select v01_exerc,
		 arreold.k00_numpre,
		 k00_numpar,
		 k00_dtvenc,
		 sum(k00_valor) 
	  from termodiv 
	       inner join divida      on coddiv = v01_coddiv 
	       inner join arreold     on v01_numpre = arreold.k00_numpre 
				     and v01_numpar = k00_numpar 
	       inner join arrematric  on arrematric.k00_numpre = v01_numpre 
	  where parcel = $parcel 
	  group by v01_exerc, arreold.k00_numpre, k00_numpar, k00_dtvenc) 
	  as x) 
   as y) as z group by v01_exerc" ;

     
pg_exec("rollback");

  $result = pg_query($sql);
  if(pg_numrows($result) == 0){
    ?>
    <script>alert('Parcelamento não encontrado.')</script>
    <?
  }else{
    $cor="#EFE029";
    ?>
        <tr>
          <th class="borda" style="font-size:12px" nowrap>Exercício</th>
          <th class="borda" style="font-size:12px" nowrap>Valor Corrigido</th>
          <th class="borda" style="font-size:12px" nowrap>Valor Juros</th>
          <th class="borda" style="font-size:12px" nowrap>Valor Multa</th>
          <th class="borda" style="font-size:12px" nowrap>Valor Total</th>
          <th class="borda" style="font-size:12px" nowrap>Acerto</th>
          <th class="borda" style="font-size:12px" nowrap>Corrigido</th>
          <th class="borda" style="font-size:12px" nowrap>Total</th>
        </tr>
    <?
    for($i=0;$i<pg_numrows($result);$i++){
      db_fieldsmemory($result,$i,true);
      if($cor=="#EFE029")
         $cor="#E4F471";
      else if($cor=="#E4F471")
         $cor="#EFE029";
       ?>
       <tr>
         <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$v01_exerc?>&nbsp;</td>
         <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$vlrcor?></td>
         <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$vlrmulta?></td>
         <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$vlrjuros?></td>
         <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$total?></td>
         <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
	    <input type="text" name="val_<?=$i?>" value="<?$vv="val_$i";echo $$vv?>" id="val_<?=$i?>">
	 </td>
         <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
	     <input type="hidden" style="background-color:<?=$cor?>" name="cor_<?=$i?>" value="<?$vv="cor_$i";echo $$vv?>" readonly id="cor_<?=$i?>" >
             <input type="hidden" name="data_<?=$i?>" value="<?=$v01_exerc?>" id="data"  >
	     <?=db_formatar($$vv,'f')?>
	 </td>
         <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
	     <?
	     echo db_formatar($total-$$vv,'f');
	     $tgeral += ($total-$$vv);
	     
	     ?>
	 </td>

       </tr>
        <?
   }
   for($x=($v01_exerc+1);$x<db_getsession("DB_anousu")+1;$x++){
      $i ++;
      if($cor=="#EFE029")
         $cor="#E4F471";
      else if($cor=="#E4F471")
         $cor="#EFE029";
 
        ?>
       <tr>
         <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$x?>&nbsp;</td>
         <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=0?></td>
         <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=0?></td>
         <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=0?></td>
         <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=0?></td>
         <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
	    <input type="text" name="val_<?=$i?>" value="<?$vv="val_$i";echo $$vv?>" id="val_<?=$i?>">
	 </td>
         <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
	     <input type="hidden" style="background-color:<?=$cor?>" name="cor_<?=$i?>" value="<?$vv="cor_$i";echo $$vv?>" readonly id="cor_<?=$i?>" >
             <input type="hidden" name="data_<?=$i?>" value="<?=$v01_exerc?>" id="data"  >
	     <?=db_formatar($$vv,'f')?>
	 </td>
         <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
	     <?
	     echo db_formatar($$vv,'f');
	     $tgeral -= $$vv;
	     ?>
	 </td>


       </tr>
        <?
  
   }
  }  
  ?>
  <tr>
    <td align="center" colspan="6">
      <input type="submit" name="calcular" value="Calcular" id="calcular"  >
      <input type="hidden" name="qtotal" value="<?=$i + 1?>" id="qtotal"  >
      <?
      if(isset($calcular) || isset($emiterecibo)){
	?>
        <input type="submit" name="emiterecibo" value="Emite Recibo" >
        <?
      }
      ?>
    </td>
    <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>"> À Pagar </td>
    <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
	<?=db_formatar($tgeral,'f')?>
      <input type="hidden" name="valorapagar" value="<?=$tgeral?>" >
    </td>
  </tr>
  <?
}
?>

	     </table>
	 </td>
	</tr>
	<tr>
	 <td>&nbsp;
	 </td>
	</tr>
	<tr>
	 <td align="center">&nbsp;
	 </td>
	</tr>
  </td>
 </tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($emiterecibo)){
  
  ?>
  <script>
  jan = window.open('cai3_consultadesc002.php?parcel=<?=$parcel."&valortot=".$valorapagar?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
  </script> 
  <?



}
?>