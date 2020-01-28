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

// select k00_numpre,k00_numpar,k00_receit from arrecad where k00_numpre = 11111454;
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
//include("libs/db_sql.php");

//db_postmemory($HTTP_POST_VARS,2);
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style>
.fonte {
font-family:Arial, Helvetica, sans-serif;
font-size:12px;
}
td {
font-family:Arial, Helvetica, sans-serif;
font-size:12px;

}
th {
font-family:Arial, Helvetica, sans-serif;
font-size:12px;

}
</style>
<script language="JavaScript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<center>
<table width="100%" border="0" cellpadding="0" cellspacing="1">
   <? 
 if (isset($caixa)){
    if ($caixa > 0){
       $xcaixa  = ' and k12_idautent = '.$caixa;
       $xcaixa1 = ' and c.k12_id = '.$caixa;
    }else{
       $xcaixa = '';
       $xcaixa1= ''; 
    }
    
   $sql="
select k13_conta,
       k13_descr,
       c60_codsis,
       sum(soma) as totcaixa
from
      (select k12_empen,
              k13_conta,
              k13_descr,
              case when k12_empen is null then k12_valor else 0 end as soma,
              case when k12_empen is not null then k12_valor else 0 end as menos
       from corrente c
            inner join saltes  on k13_conta = c.k12_conta
            left join coremp p on p.k12_id=c.k12_id
                              and p.k12_data = c.k12_data
                              and p.k12_autent = c.k12_autent
       where c.k12_data = '$data' $xcaixa1 ) as x
       inner join conplanoreduz on c61_reduz = k13_conta and c61_anousu = ".db_getsession("DB_anousu")."
       inner join conplano      on c60_codcon = c61_codcon and c60_anousu = c61_anousu
		    
group by k13_conta,k13_descr,c60_codsis
	 ";
   $sql1="
      select k12_empen,
              k13_conta,
	      k12_hora,
              k13_descr,
              k12_valor as valor,
              z01_nome,
	      e60_codemp,
	      c60_codsis,
	      k12_codord
       from corrente c
            inner join saltes  on k13_conta = c.k12_conta
            inner join coremp p on p.k12_id=c.k12_id
                              and p.k12_data = c.k12_data
                              and p.k12_autent = c.k12_autent
	    inner join empempenho on e60_numemp = k12_empen
	    inner join cgm on z01_numcgm = e60_numcgm
            inner join conplanoreduz on c61_reduz = k13_conta and c61_anousu = ".db_getsession("DB_anousu")."
            inner join conplano      on c60_codcon = c61_codcon and c60_anousu = c61_anousu
       where c.k12_data = '$data' $xcaixa1  and k12_empen is not null and c60_codsis = 5
	  
	 ";
    $result1 = pg_exec($sql);
    $result2 = pg_exec($sql1);
    $num2    = pg_numrows($result2);
//    db_criatabela($result2);
//    db_fieldsmemory(pg_exec($sql),0);
    $sql = "
      select k12_idautent,
             k12_tipomov,
             case k12_tipomov
                  when '0' then 'Débito'
                  when '1' then 'Crédito'
             end as tipo,
             k12_horamov,
             k12_valormov,
             k12_obsmov
      from correntemov
      where k12_dtmov = '$data'
            $xcaixa;
      ";
//echo $sql;exit;										      
   $result = pg_exec($sql);
   ?>
  <tr bgcolor="#666666">
    <th width="8%" >Caixa</th>
    <th width="8%" >Tipo</th>
    <th width="8%" >Hora</th>
    <th width="15%">Valor</th>
    <th width="50" >Observação</th>
  </tr>
   <?
       $cor="#EFE029";
       $total = 0; 
       for ($i = 0;$i < pg_numrows($result);$i++){
	 db_fieldsmemory($result,$i);
         if($cor=="#EFE029")
            $cor="#E4F471";
         else if($cor=="#E4F471")
            $cor="#EFE029";
      
   ?>
         <tr>
           <td align="center"  nowrap bgcolor="<?=$cor?>"><?=$k12_idautent?>&nbsp;</td>
           <td align="center"  nowrap bgcolor="<?=$cor?>"><?=$tipo?>&nbsp;</td>
           <td align="center"  nowrap bgcolor="<?=$cor?>">&nbsp;<?=$k12_horamov?></td>
           <td align="right"  nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($k12_valormov,'f')?></td>
           <td align="left"  nowrap bgcolor="<?=$cor?>">&nbsp;<?=$k12_obsmov?></td>
         <tr>	
   <?
         if ($k12_tipomov == '0'){
            $total -= $k12_valormov;
	 }else{
            $total += $k12_valormov;
	 }
       }
         if($cor=="#EFE029")
            $cor="#E4F471";
         else if($cor=="#E4F471")
            $cor="#EFE029";
   ?>
         <tr>
           <td colspan="3" align="right" style="font-size:12px" nowrap bgcolor="#ffcc66"><strong>Total das Movimentações&nbsp;</td>
           <td align="right" style="font-size:12px" nowrap bgcolor="#ffcc66">&nbsp;<?=db_formatar($total,'f')?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="#ffcc66">&nbsp;</td>
         <tr>
   <?
       $totalcontas = 0;
       for ($i = 0;$i < pg_numrows($result1);$i++){
	 db_fieldsmemory($result1,$i);
         if($cor=="#EFE029")
            $cor="#E4F471";
         else if($cor=="#E4F471")
            $cor="#EFE029";

   ?>
         <tr>
           <td colspan="3" align="right" style="font-size:12px" nowrap bgcolor="#ffcc66"><strong><?=$k13_descr?></strong>&nbsp;</td>
           <td align="right" style="font-size:12px" nowrap bgcolor="#ffcc66">&nbsp;<?=db_formatar($totcaixa,'f')?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="#ffcc66">&nbsp;</td>
         <tr>
   <?
       if($c60_codsis == 5) 
          $totalcontas += $totcaixa;
        
      }
   ?>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" align="center"><strong>EMPENHOS</strong>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  
  <tr bgcolor="#666666">
    <th width="8%" >Empenho</th>
    <th width="8%" >Ordem</th>
    <th width="8%" >Hora</th>
    <th width="15%">Valor</th>
    <th width="50" >Credor</th>
  </tr>
  <?
       $cor="#EFE029";
       $vlremp = 0;
       for ($i = 0;$i < $num2;$i++){
	 db_fieldsmemory($result2,$i);
         if($cor=="#EFE029")
            $cor="#E4F471";
         else if($cor=="#E4F471")
            $cor="#EFE029";
      
   ?>
         <tr>
           <td align="center"  nowrap bgcolor="<?=$cor?>"><?=$e60_codemp?>&nbsp;</td>
           <td align="center"  nowrap bgcolor="<?=$cor?>"><?=$k12_codord?>&nbsp;</td>
           <td align="center"  nowrap bgcolor="<?=$cor?>"><?=$k12_hora?>&nbsp;</td>
           <td align="right"  nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($valor,'f')?></td>
           <td align="left"  nowrap bgcolor="<?=$cor?>">&nbsp;<?=$z01_nome?></td>
         <tr>
    <?
         $vlremp += $valor;
       }
    ?>
         <tr>
           <td colspan="3" align="right" style="font-size:12px" nowrap bgcolor="#ffcc66"><strong>Total dos Empenhos</strong>&nbsp;</td>
           <td align="right" style="font-size:12px" nowrap bgcolor="#ffcc66">&nbsp;<?=db_formatar($vlremp,'f')?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="#ffcc66">&nbsp;</td>
         <tr>
         <tr>
           <td colspan="3" align="right" style="font-size:12px;color:red" nowrap bgcolor="#ffcc66"><strong>Saldo do Caixa</strong>&nbsp;</td>
           <td align="right" style="font-size:12px;color:red" nowrap bgcolor="#ffcc66"><strong>&nbsp;<?=db_formatar($totalcontas+$total-$vlremp,'f')?></strong></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="#ffcc66">&nbsp;</td>
         <tr>

 <?
 }
 ?>
  </table>
</center>
</body>
</html>