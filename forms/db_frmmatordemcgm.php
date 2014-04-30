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
include("classes/db_db_almox_classe.php");
$cldb_almox = new cl_db_almox;
$clempempenho->rotulo->label();
$clcgm->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");
$clrotulo->label("m51_obs");
$clrotulo->label("m51_prazoent");

$where=" 1=1 ";
$where1="";
$where2="";
$pesqemp='false';

$coddepto = db_getsession("DB_coddepto");

$resultdepto = pg_query("select descrdepto from db_depart where coddepto = $coddepto");
db_fieldsmemory($resultdepto,0);

$m51_prazoent = 3;

if (isset($e60_numcgm) && $e60_numcgm!=''){
  $where= "e60_numcgm = $e60_numcgm ";
}
if (isset($e60_numemp) && $e60_numemp!='' ){
  $where1= " and e60_numemp = $e60_numemp ";
  $pesqemp='true';
}

if (isset($e60codemp) && $e60_codemp){
  $where2= " and e60_codemp = $e60_codemp ";
  $pesqemp='true';
}

if((isset($e60_numcgm) && $e60_numcgm!='')||(isset($e60_numemp) && $e60_numemp!='' )||(isset($e60_codemp) && $e60_codemp)){
  
   //rotina que traz os dados do empenho
     $result = $clempempenho->sql_record($clempempenho->sql_query_empnome(null,"*","","$where $where1 $where2")); 
     db_fieldsmemory($result,0,true);
   //fim  
   
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
<form name="form1" method="post" action="" onsubmit="js_buscavalores();">
<center>
<table border='0'>
  <tr>
    <td>
      <table border="0">
        <tr>
          <td nowrap align="right" title="<?=@$Te60_numcgm?>"><?=@$Le60_numcgm?></td>
          <td><?db_input('e60_numcgm',20,$Ie60_numcgm,true,'text',3)?></td>
          <td nowrap align="right" title="<?=@$z01_nome?>"><?=@$Lz01_nome?></td>
          <td><?db_input('z01_nome',45,$Iz01_nome,true,'text',3)?></td>
	</tr>
        <tr>
          <td nowrap align="right" title="<?=@$Tz01_cgccpf?>"><?=@$Lz01_cgccpf?></td>
          <td><?db_input('z01_cgccpf',20,$Iz01_cgccpf,true,'text',3)?></td>
            <td nowrap align="right" title="<?=@$z01_email?>"><?=@$Lz01_email?></td>
          <td nowrap><?db_input('z01_email',45,$Iz01_email,true,'text',3)?>
          <input name="Alterar CGM" type="button" id="alterarcgm" value="Alterar CGM" onclick="js_AlteraCGM(document.form1.e60_numcgm.value);" >
          </td>                    
        </tr>
				<?
         $result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit")));
         if ($clpcparam->numrows > 0) {
	           db_fieldsmemory($result_pcparam, 0);
						 if ($pc30_emiteemail == 't'){
							  $sSql = "select usuext 
								           from db_usuarios u
													      inner join db_usuacgm c on u.id_usuario = c.id_usuario
													where cgmlogin = $z01_numcgm";
							$rs = pg_query($sSql);						
							if (pg_num_rows($rs) > 0){
  							db_fieldsmemory($rs,0);
								if ($usuext == 1){

            ?>

               <tr>
                <td nowrap></td>
                 <td nowrap></td>
                 <td align="right"><input id='manda_email' name="manda_mail" type="checkbox" value="X"></td>
                 <td nowrap><label for='manda_email'><b>Mandar e-mail para o fornecedor.</b></label></td>         
              </tr>
           <?//end if parametro
								}
							}
					 }
				 }
				?>		 
       <!-- <td nowrap></td>
          <td nowrap></td>
        <td align="right"><input name="manda_mail" type="radio" value="X"></td>
          <td nowrap><b>Mandar e-mail para o fornecedor.</b></td>         
        </tr>-->
        <tr>
          <td nowrap align="right" title="<?=@$z01_ender?>"><?=@$Lz01_ender?></td>
          <td><?db_input('z01_ender',30,"$Iz01_ender",true,'text',3);if (@$z01_numero!=0){db_input('z01_numero',4,@$Iz01_numero,true,'text',3);}?></td>
          <td nowrap align="right"   title="<?=@$Tz01_compl?>"><?=@$Lz01_compl?></td>
          <td><?db_input('z01_compl',20,$Iz01_compl,true,'text',3)?></td>
        </tr>
        <tr>
          <td nowrap align="right" title="<?=@$Tz01_munic?>"><?=@$Lz01_munic?></td>
          <td><?db_input('z01_munic',30,$Iz01_munic,true,'text',3)?></td>
          <td nowrap align="right"   title="<?=@$Tz01_cep?>"><?=@$Lz01_cep?></td>
          <td><?db_input('z01_cep',20,$Iz01_cep,true,'text',3)?></td>
        </tr>
        <tr>
          <td nowrap align="right" title="<?=@$Tz01_telef?>"><?=@$Lz01_telef?></td>
          <td><?db_input('z01_telef',20,$Iz01_telef,true,'text',3)?></td>
          <td nowrap align="right" title="<?=@$Tm51_prazoent?>"><?=@$Lm51_prazoent?></td>
          <td><?db_input('m51_prazoent',6,$Im51_prazoent,true,'text',1)?></td>
        </tr>
        <tr>
          <td nowrap align="right" title="<?=@$Tm51_data?>"><b>Data:</b></td>
          <td><?if(empty($m51_data_dia)){
	        $m51_data_dia =  date("d",db_getsession("DB_datausu"));
                $m51_data_mes =  date("m",db_getsession("DB_datausu"));
                $m51_data_ano =  date("Y",db_getsession("DB_datausu"));
              }
              db_inputdata('m51_data',@$m51_data_dia,@$m51_data_mes,@$m51_data_ano,true,'text',3);?>
          </td>
          <?
          $result_matparam=$clmatparam->sql_record($clmatparam->sql_query_file());
          if ($clmatparam->numrows>0){
          	db_fieldsmemory($result_matparam,0);
          	if($m90_tipocontrol=='F'){
          		
          		echo "<td nowrap align='right' title='Almox'><b>Almoxarifado :</b></td>";
          		$Result_almox=$cldb_almox->sql_record($cldb_almox->sql_query(null,"m91_depto,descrdepto"));
          		if ($cldb_almox->numrows==0){
          			db_msgbox("Sem Almoxarifados cadastrados!!");
          			echo "<script>location.href='emp1_ordemcompra001.php';</script>";
          		}
          		echo "<td>";
          		db_selectrecord("coddepto",$Result_almox,true,1);
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
        </tr>
        <tr> 
	<td align='right'><b>Obs:</b></td>
          <td colspan='3' align='left'>
	 <? 
	 db_textarea("m51_obs","","110",$Im51_obs,true,'text',1);
	 
	 ?>
	  </td>
        
	</tr>  
        <tr> 
          <td colspan='4' align='center'></td>
        </tr>  
        <tr>
          <td colspan='4' align='center'>
	  <?if ($e60_numcgm!=""){
	      $result=pg_exec("select * from empempenho inner join empempitem on e62_numemp = e60_numemp inner join pcmater on pc01_codmater = e62_item where e60_numcgm=$e60_numcgm");
	      if (pg_numrows($result)>0){?>
              <input name="incluir" type="submit"  value="Incluir">
	      <input name="voltar" type="button" value="Voltar" onclick="location.href='emp1_ordemcompra001.php';" >
	  <?}else{?>
              <input name="incluir" type="submit" disabled  value="Incluir">
	      <input name="voltar" type="button" value="Voltar" onclick="location.href='emp1_ordemcompra001.php';" >
	  <?}
	    }else{?>
              <input name="incluir" type="submit" disabled  value="Incluir">
	      <input name="voltar" type="button" value="Voltar" onclick="location.href='emp1_ordemcompra001.php';" ><?}?>
	  </td>
        </tr>
      </table>
     </td>
    </tr>
    <tr>
     <td align='center' valign='top' colspan='1'>
     <?
     if(isset($e60_numcgm) && $pesqemp!='true'){
     ?>  
      <table>
        <tr>
         <td>
           <iframe name="itens" id="itens" src="forms/db_frmmatordemitem.php?e60_numcgm=<?=$e60_numcgm?>&erro=false" width="720" height="220" marginwidth="0" marginheight="0" frameborder="0"></iframe>
         </td>
        </tr>
      </table>
     <?
     }
     ?>  
    </td>
  </tr>
    <tr>
     <td align='center' valign='top' colspan='1'>
     <?
     if($pesqemp=='true'){
     ?>  
      <table>
        <tr>
         <td>
           <iframe name="itens" id="itens" src="forms/db_frmmatordemitemnota.php?e60_numcgm=<?=$e60_numcgm?>&erro=false&e60_codemp=<?=@$e60_codemp?>&e60_numemp=<?=@$e60_numemp?>" width="720" height="220" marginwidth="0" marginheight="0" frameborder="0"></iframe>
         </td>
        </tr>
      </table>
     <?
     }
     ?>  
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
  function js_AlteraCGM(cgm) {
  	js_OpenJanelaIframe('','db_iframe_altcgm','prot1_cadcgm002.php?chavepesquisa='+cgm+'&testanome=true&autoc=true','Altera Cgm',true);
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
  function js_buscavalores(){
   obj= itens.document.form1;
   valor="";
   valoritem='';
   for (i=0;i<obj.elements.length;i++){
     if (obj.elements[i].name.substr(0,6)=="quant_"){
       cheke=obj.elements[i].name.split("_");
       if (eval("obj.CHECK_"+cheke[1]+"_"+cheke[2]+".checked")==true){
	 var objvalor=new Number(obj.elements[i].value);
	 if (objvalor!=0){
	   valor+=obj.elements[i].name+"_"+obj.elements[i].value;
	 } 
       }else{
	 continue;
       }
     }
     if (obj.elements[i].name.substr(0,6)=="valor_"){
       cheke=obj.elements[i].name.split("_");
       if (eval("obj.CHECK_"+cheke[2]+"_"+cheke[3]+".checked")==true){
         objvaloritem=new Number(obj.elements[i].value);
         if (objvaloritem!=0){
	   valoritem+=obj.elements[i].name+"_"+obj.elements[i].value;
         } 
       }
     }
   }
   document.form1.valores.value=valor;
   document.form1.val.value=valoritem;
  }
</script>