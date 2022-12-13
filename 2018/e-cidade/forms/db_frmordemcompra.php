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
$evtCLick = '';

if (isset($listagem_empenhos) && $listagem_empenhos!='' ){	  
  $pesqemp='true';
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
	    <td nowrap align="right" title="<?=@$Tm51_data?>"><b>Data:</b></td>
	    <td><?if(empty($m51_data_dia)){
		  $m51_data_dia =  date("d",db_getsession("DB_datausu"));
		  $m51_data_mes =  date("m",db_getsession("DB_datausu"));
		  $m51_data_ano =  date("Y",db_getsession("DB_datausu"));
		}
		db_inputdata('m51_data',@$m51_data_dia,@$m51_data_mes,@$m51_data_ano,true,'text',3);?>
	    </td>
	    <td nowrap align="right" title="<?=@$Tm51_prazoent?>"><?=@$Lm51_prazoent?></td>
	    <td><?db_input('m51_prazoent',6,$Im51_prazoent,true,'text',1)?></td>
	  <tr>
	    <td nowrap align="right" title="<?=@$descrdepto?>"><?db_ancora(@$Lcoddepto,"js_coddepto(true);",1);?></td>
	    <td><?db_input('coddepto',6,$Icoddepto,true,'text',1," onchange='js_coddepto(false);'");
		  db_input('descrdepto',35,$Idescrdepto,true,'text',3,'');?>
	    </td>
				<?
         $result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit")));
         if ($clpcparam->numrows > 0) {
	           db_fieldsmemory($result_pcparam, 0);
						 if ($pc30_emiteemail == 't'){
							   echo "<script>\n";
								 echo " function testaEmail(){\n";
								 echo "     if (document.getElementById('manda_email').checked == true){\n";
                 echo "         alert('Os mails somente serão enviados para aqueles fornecedores devidamente\\n inscritos como usuários externos da Prefeitura');";
								 echo "     }\n";
								 echo "}\n";
								 echo "</script>\n";
								 $evtCLick = "testaEmail();";
								 ?>

              <td align="right"><input id='manda_email' name="manda_mail" type="checkbox" value="X"></td>
              <td nowrap><label for='manda_email'><b>Mandar e-mail para o fornecedor.</b></label></td>         
           <?//end if parametro
								}
							}
				?>		 
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
		<input name="incluir" type="submit"  value="Incluir" onclick="<?=$evtCLick;?>">
		<input name="voltar" type="button" value="Voltar" onclick="location.href='emp4_ordemcompra011.php';" >
	    </td>
	  </tr>
	</table>
       </td>
      </tr>
      <tr>
       <td align='center' valign='top' colspan='1'>
       <!--
	<table>
	  <tr>
	   <td>
	     <iframe name="itens" id="itens" src="forms/db_frmordemcompraitem.php?e60_numcgm=<?=$e60_numcgm?>&erro=false" width="720" height="220" marginwidth="0" marginheight="0" frameborder="0"></iframe>
	   </td>
	  </tr>
	</table>
	-->
      </td>
    </tr>
      <tr>
       <td align='center' valign='top' colspan='1'>
       <?     
       //if($pesqemp=='true'){     	
       ?>  
	<table>
	  <tr>
	   <td>
	     <iframe name="itens" id="itens" src="forms/db_frmordemcompraitem.php?listagem_empenhos=<?=@$listagem_empenhos?>&erro=false" width="720" height="220" marginwidth="0" marginheight="0" frameborder="0"></iframe>
         </td>
        </tr>
      </table>
     <?
     //}
     ?>  
    </td>
  </tr>
 </table>
</center>
<?
db_input("valores",100,0,true,"hidden",3);
db_input("val",100,0,true,"hidden",3);
db_input("emitir",10,0,true,"hidden",3);
?>
</form>
<script>
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
       objvaloritem=new Number(obj.elements[i].value);
       if (objvaloritem!=0){
	 valoritem+=obj.elements[i].name+"_"+obj.elements[i].value;
       } 
     }
   }
   document.form1.valores.value=valor;
   document.form1.val.value=valoritem;
  }
</script>