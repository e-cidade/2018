<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
$coddepto = db_getsession("DB_coddepto");
$instit   = db_getsession("DB_instit");

include_once("classes/db_db_almox_classe.php");
include_once("classes/db_db_almoxdepto_classe.php");
$cldb_almox = new cl_db_almox;
$cldb_almoxdepto = new cl_db_almoxdepto;
if(isset($m51_codordem) && $m51_codordem!=''){
     
     $sql = "select m51_codordem,
                    m51_data,
		            m51_depto,
		            m51_depto  as coddepto,
		            m51_numcgm,
		            m51_obs,
		            z01_nome,
		            descrdepto,
                    m51_prazoent,
					m52_numemp
	           from matordem 
	          inner join cgm          on z01_numcgm   = m51_numcgm 
		      inner join db_depart    on coddepto     = m51_depto 
			   left join matordemitem on m52_codordem = m51_codordem
              where m51_codordem = $m51_codordem ";

     $result = pg_exec($sql); 
     if (pg_numrows($result)==0){
     
     }
     db_fieldsmemory($result,0);
	 
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
<form name="form1" method="post" action="" onsubmit="js_buscavalores();" >
<center>
<table border='0'>
  <tr align="left">
    <td align="left">
			<fieldset>
      <table border="0">
        <tr>
          <td nowrap align="right" title="<?=@$Te60_numcgm?>">
						<?=@$Le60_numcgm?>
					</td>
          <td> 
            <?
              db_input('m51_numcgm',15,$Im51_numcgm,true,'text',3)
            ?>
          </td>
          <td nowrap align="right" title="<?=@$z01_nome?>">
						<?=@$Lz01_nome?>
					</td>
          <td>
            <?
              db_input('z01_nome',40,$Iz01_nome,true,'text',3)
            ?>
          </td>
				</tr>
        <tr>
          <td nowrap align="right" title="<?=@$Tm51_codordem?>">
						<b>Ordem de Compra:</b>
					</td>
          <td>
						<?
              db_input('m51_codordem',15,$Im51_codordem,true,'text',3)
						?>
					</td>
					<td nowrap align="right" title="<?=@$Tm51_prazoent?>">
						<?=@$Lm51_prazoent?>
					</td>
				  <td>
						<?
							db_input('m51_prazoent',6,$Im51_prazoent,true,'text',1)
						?>
					</td>
        </tr>
        <tr>
					<td nowrap align="right" title="<?=@$Tm51_data?>">
						<b>Data da emiss&atilde;o:</b>
					</td>
          <td> 
						<?
              $ano = substr($m51_data,0,4);
						  $mes = substr($m51_data,5,2);
						  $dia = substr($m51_data,8,2);
						  db_inputdata('dataemis',"$dia","$mes","$ano",true,'text',3);
						?>
          </td>
						<?
							$result_entrada=$clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query_file(null,
							                                     null,"*",
							                                     null,
							                                     "m73_codmatordemitem  in
							                                      (select m52_codlanc from matordemitem where m52_codordem=$m51_codordem)
							                                      and m73_cancelado is false;"));
							if ($clmatestoqueitemoc->numrows!=0){
						?>
							
							<td nowrap align="right" title="<?=@$descrdepto?>">
								<?=@$Lcoddepto?>
							<td> 
								<?
									db_input('coddepto',6,$Im51_depto,true,'text',3);
									db_input('descrdepto',36,$Idescrdepto,true,'text',3);
									$depart="false";
									db_input('depart',35,$Idescrdepto,true,'hidden',3);
								?>
							</td>
						</tr>
						<tr> 
							<td align='right'>
								<b>Obs:</b>
							</td>
							<td colspan='3' align='left'>
								<? 
									db_textarea("m51_obs","","90",$Im51_obs,true,'text',3);
								?>
							</td>
						
						<?
							}else{
						  $result_matparam=$clmatparam->sql_record($clmatparam->sql_query_file());	
                         if ($clmatparam->numrows>0){
                          db_fieldsmemory($result_matparam,0);
                          if($m90_tipocontrol=='F'){
                        
                            echo "<td nowrap align='right' title='Almox'><b>Almoxarifado :</b></td>";

                        		if ($m90_almoxordemcompra == "2") {
                        			if (!isset($e60_numemp)) {
                        			  $e60_numemp = $m52_numemp;	
                        			}
                        			$sSqlOrigemEmpenho = "select * from fc_origem_empenho(".$e60_numemp.")";
									//die($sSqlOrigemEmpenho);
									$rsOrigemEmpenho   = pg_query($sSqlOrigemEmpenho);
                        			
                        			for ($i = 0; $i < pg_num_rows($rsOrigemEmpenho); $i++) {
                        			  $oOrigemEmpenho = db_utils::fieldsMemory($rsOrigemEmpenho,$i);
                        			  $aDeptoEmp[]	  = $oOrigemEmpenho->ridepto; 		
                        			}
                        			
                        			$rsAlmox = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query(null,null,"distinct m91_depto,a.descrdepto",null," m92_depto in (".implode(",",array_unique($aDeptoEmp)).") and a.instit = $instit")); 
                        
                        			if ($cldb_almoxdepto->numrows > 1){			
                        				$rsAlmox    = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query(null,null,"'0' as m91_depto, 'Nenhum' as descrdepto union all select distinct m91_depto,a.descrdepto",null," m92_depto in (".implode(",",array_unique($aDeptoEmp)).") and a.instit = $instit")); 
                        			}
                        			
                        			$iLinhasAlmox = $cldb_almoxdepto->numrows;
                        		
                        		}else{
                        			
                        			$rsAlmox	  = $cldb_almox->sql_record($cldb_almox->sql_query(null,"m91_depto,descrdepto",null,"db_depart.instit = $instit"));
                        			$iLinhasAlmox = $cldb_almox->numrows;
                        		
                        		}
                        		
                            if ($iLinhasAlmox == 0){
                              db_msgbox("Sem Almoxarifados cadastrados!!");
                              echo "<script>location.href='emp1_ordemcompraaltera001.php';</script>";
                            }
                            
                        		echo "<td>";
                        			db_selectrecord("coddepto",$rsAlmox,true,1);
                            echo "</td>";
                        
                          }else{
                            ?>
                              <td nowrap align="right" title="<?=@$descrdepto?>"><?db_ancora(@$Lcoddepto,"js_coddepto(true);",1);?></td>
                              <td><?db_input('coddepto',6,$Icoddepto,true,'text',1," onchange='js_coddepto(false);'");
                            db_input('descrdepto',35,$Idescrdepto,true,'text',3,'');?>
                              </td>
                              <?
                          }
                        }else{
                          ?>
                            <td nowrap align="right" title="<?=@$descrdepto?>"><?db_ancora(@$Lcoddepto,"js_coddepto(true);",1);?></td>
                            <td><?db_input('coddepto',6,$Icoddepto,true,'text',1," onchange='js_coddepto(false);'");
                          db_input('descrdepto',35,$Idescrdepto,true,'text',3,'');?>
                            </td>
                            <?}?>
                            <tr> 
							<td align='right'>
								<b>Obs:</b>
							</td>
							<td colspan='3' align='left'>
								<?
									$obs = $m51_obs ;
									db_textarea("obs","","90",$Im51_obs,true,'text',1);
								?>
							</td>
					 <?	
						 } 
					 ?> 
				</tr>  
        <tr>
          <td colspan='4' align='center'>
					  <?
						  if ($m51_codordem!=""){
					  ?>
					  <input name="altera" type="submit"  value="Alterar">
						<?}else{?>
						<input name="altera" type="submit" disabled  value="Alterar">
						<?}?>
						<input name="voltar" type="button" value="Voltar" onclick="location.href='emp1_ordemcompraaltera001.php';" >
					</td>
        </tr>
      </table>
			</fieldset>
		</td>
  </tr>
  <tr>
   <td align='center' valign='top' colspan='1' align='center'>
		 <?
			 if(isset($m51_codordem)){
		 ?>  
     <table>
       <tr>
         <td>
           <iframe name="itens" id="itens" src="forms/db_frmmatordemitemaltera.php?m51_codordem=<?=$m51_codordem?>" width="760" height="280" marginwidth="0" marginheight="0" frameborder="0"></iframe>
         </td>
       </tr>
     </table>
		 <?}?>  
   </td>
  </tr>
</table>
</center>
<?
	db_input("valores",100,0,true,"hidden",3);
	db_input("val",100,0,true,"hidden",3);
?>
</form>
<script>
function js_buscavalores(){
  obj= itens.document.form1;
  valor="";
  valoritem='';
  
  for (i=0;i<obj.elements.length;i++){
    if (obj.elements[i].name.substr(0,6)=="quant_"){
      var objvalor=new Number(obj.elements[i].value);
      valor+=obj.elements[i].name+"_"+obj.elements[i].value;
    }
    if (obj.elements[i].name.substr(0,6)=="valor_"){
      objvaloritem=new Number(obj.elements[i].value);
      valoritem+=obj.elements[i].name+"_"+obj.elements[i].value;
    }
  }
  document.form1.valores.value=valor;
  document.form1.val.value=valoritem;
}
function js_coddepto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostracoddepto1|coddepto|descrdepto','Pesquisa',true);
  }else{
    coddepto = document.form1.coddepto.value;
  if(coddepto!=""){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+coddepto+'&funcao_js=parent.js_mostracoddepto','Pesquisa',false);
  }else{ 	
    document.form1.descrdepto.value='';
  } 	
 }
}
function js_mostracoddepto1(chave1,chave2){
  document.form1.coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_mostracoddepto(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.coddepto.focus(); 
    document.form1.coddepto.value = ''; 
  }
}
</script>
</body>
</html>