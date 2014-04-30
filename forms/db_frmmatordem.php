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

//MODULO: empenho
$clempempenho->rotulo->label();
$clcgm->rotulo->label();
$clmatordem->rotulo->label();
$cldbdepart->rotulo->label();
$clmatordemanu->rotulo->label();

if(isset($m51_codordem) && $m51_codordem!=''){
     
     $sql = "select m51_codordem,
                    m51_data,
		    m51_depto,
		    m51_numcgm,
		    z01_nome,
		    descrdepto
		    
	     from matordem 
	            inner join cgm on z01_numcgm = m51_numcgm 
		    inner join db_depart on coddepto = m51_depto 
             where m51_codordem = $m51_codordem ";
     
     $result = pg_exec($sql); 
     if (pg_numrows($result)==0){
     
     }
     db_fieldsmemory($result,0,true);

 
   
}    

  ?>
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
</style>
<form name="form1" method="post" action="emp1_ordemcompra004.php" >
<center>
<table border='0'>
  <tr align = 'left'>
    <td align="left">
      <table border="0">
        <tr>
          <td nowrap align="right" title="<?=@$Te60_numcgm?>"><?=@$Le60_numcgm?></td>
          <td> 
            <?
              db_input('m51_numcgm',20,$Im51_numcgm,true,'text',3)
            ?>
          </td>
          <td nowrap align="right" title="<?=@$z01_nome?>"><?=@$Lz01_nome?></td>
          <td>
            <?
              db_input('z01_nome',45,$Iz01_nome,true,'text',3)
            ?>
          </td>
	</tr>
        <tr>
          <td nowrap align="right" title="<?=@$Tm51_codordem?>"><b>Ordem de Compra:</b></td>
          <td>
	    <?
              db_input('m51_codordem',20,$Im51_codordem,true,'text',3)
	    ?>
	  </td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td nowrap align="right" title="<?=@$Tm53_data?>"><b>Data da anula&ccedil;&atilde;o:</b></td>
          <td> 
            <?  $m53_data_dia =  date("d",db_getsession("DB_datausu"));
	        $m53_data_mes =  date("m",db_getsession("DB_datausu"));
		$m53_data_ano =  date("Y",db_getsession("DB_datausu"));
					    
              db_inputdata('m53_data',@$m53_data_dia,@$m53_data_mes,@$m53_data_ano,true,'text',3);
            ?>
          </td>
          <td nowrap align="right" title="<?=@$descrdepto?>">
             <?=@$Lcoddepto?>
          <td> 
             <?
             db_input('m51_depto',6,$Im51_depto,true,'text',3);
             db_input('descrdepto',36,$Idescrdepto,true,'text',3);
             ?>
          </td>
        </tr>
        <tr> 
	<td align='right'><b>Obs:</b></td>
          <td colspan='3' align='left'>
	 <? 
	 db_textarea("m53_obs","","90",$Im53_obs,true,'text',1);
	 
	 ?>
	  </td>
        
	</tr>  
        <tr>
          <td colspan='4' align='center'>
	  <?if ($m51_codordem!=""){
	  ?>
            <input name="anula" type="submit"  value="Anular">
	    <?}else{?>
	   <input name="anula" type="submit" disabled  value="Anular">
	  <?}?>
	    <input name="voltar" type="button" value="Voltar" onclick="location.href='emp1_ordemcompra003.php';" >
	  </td>
        </tr>
      </table>
    </td>
    </tr>
    <tr>
   <td align='center' valign='top' colspan='1' align='center'>
  <?if(isset($m51_codordem)){?>  
     <table>
       <tr>
         <td>
           <iframe name="itens" id="itens" src="forms/db_frmmatordemitemanu.php?m51_codordem=<?=$m51_codordem?>" width="720" height="220" marginwidth="0" marginheight="0" frameborder="0"></iframe>
         </td>
       </tr>
     </table>
  <?}?>  
   </td>
  </tr>
</table>
</center>
</form>
</body>
</html>