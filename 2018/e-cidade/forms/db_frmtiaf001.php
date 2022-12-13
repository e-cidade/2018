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

		
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
     <tr>
         <td nowrap title="<?=@$Ty90_codtiaf?>"> 
	    <?
	      db_ancora(@$Ly90_codtiaf,"js_pesquisay90_codtiaf(true);",$tipoanctiaf);
	    ?>
	 </td>
	 <td>
	    <?
	     db_input('y90_codtiaf',10,$Iy90_codtiaf,true,'text',3," onchange='js_pesquisay90_codtiaf(false);'");
	    ?>
	 </td>
	 </tr>
   <tr>   
      <td>
      <?
       	db_ancora($Lz01_numcgm,' js_cgm(true); ',$tipoancgeral);
      ?>
       </td>
       <td> 
      <?
	       db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',$db_opcao,"onchange='js_cgm(false)'");
	       db_input('z01_nome',30,0,true,'text',3,"","z01_nomecgm");
      ?>
       </td>
     </tr>
      <tr>   
       <td>
      <?
       db_ancora($Lq02_inscr,' js_inscr(true); ',$tipoancgeral);
      ?>
       </td>
       <td> 
      <?
       db_input('q02_inscr',5,$Iq02_inscr,true,'text',$db_opcao,"onchange='js_inscr(false)'");
       db_input('z01_nome',30,0,true,'text',3,"","z01_nomeinscr");
      ?>
       </td>
     </tr>  
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty90_data?>">
       <?=@$Ly90_data?>
    </td>
    <td> 
	<?
		db_inputdata('y90_data',@$y90_data_dia,@$y90_data_mes,@$y90_data_ano,true,'text',$db_opcao,"")
	?>
    </td>
  </tr>
   <tr>
    <td nowrap title="<?=@$Ty96_prazo?>">
       <?=@$Ly96_prazo?>
    </td>
    <td> 
	<?
		db_inputdata('y96_prazo',@$y96_prazo_dia,@$y96_prazo_mes,@$y96_prazo_ano,true,'text',$db_opcao,"")
	?>
    </td>
  </tr>
  
  <tr>
   </tr>
  </table>
<input name="incluir" type="Submit" id="pesquisar" value="<?=$tipobotao?>" onclick="js_validacampos();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</center>  
</form>
<script>

function js_limpacampos(){
	//alert ("limpacampos");
	document.form1.y96_prazo_dia.value = "";
	document.form1.y96_prazo_mes.value = "";
	document.form1.y96_prazo_ano.value = "";
	document.form1.y90_data_dia.value  = "";
	document.form1.y90_data_mes.value  = "";
	document.form1.y90_data_ano.value  = "";
	document.form1.z01_nomecgm.value   = "";
	document.form1.z01_numcgm.value    = "";
	document.form1.z01_nomeinscr.value = "";
	document.form1.q02_inscr.value     = "";
	document.form1.y90_codtiaf.value   = "";
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tiaf','func_tiaf.php?funcao_js=parent.js_preenchepesquisa|y90_codtiaf','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_tiaf.hide();
  <?
	  if($db_opcao!=1){
	    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
	  }
  ?>
}

function js_cgm(mostra){
  var cgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}

function js_mostracgm(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe2.hide();
}

function js_inscr(mostra){
  inscr=document.form1.q02_inscr.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
  }
}

function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  db_iframe.hide();
}

function js_mostrainscr1(chave,erro){
  document.form1.z01_nomeinscr.value = chave; 
  if(erro==true){ 
    document.form1.q02_inscr.focus(); 
    document.form1.q02_inscr.value = ''; 
  }
}

function js_validacampos(){
	if (document.form1.q02_inscr.value =="" && document.form1.z01_numcgm.value ==""){
		alert ("Preencha a inscri??o ou cgm ! ");
		return false;
	}else if (document.form1.q02_inscr.value !="" && document.form1.z01_numcgm.value !=""){
		alert ("Preencha somente a inscri??o ou cgm ! ");
		return false;
	}else if (document.form1.y90_data_dia.value =="" || document.form1.y90_data_mes.value =="" || document.form1.y90_data_ano.value ==""){
		alert ("Preencha a corretamente a data ! ");
		return false;
	}else if (document.form1.y96_prazo_dia.value =="" || document.form1.y96_prazo_mes.value =="" || document.form1.y96_prazo_ano.value ==""){
		alert ("Preencha a corretamente o prazo ! ");
		return false;
	}else{
	    return true;
	}
}

function js_pesquisay90_codtiaf(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tiaf','func_tiaf.php?funcao_js=parent.js_mostratiaf1|y90_codtiaf','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tiaf','func_tiaf.php?pesquisa_chave='+document.form1.y90_codtiaf.value+'&funcao_js=parent.js_mostratiaf','Pesquisa',false);
  }
}

function js_mostratiaf1(chave){
  document.form1.y90_codtiaf.value = chave;
  //document.form1.y98_descr.value = chave2;
  db_iframe_tiaf.hide();
  if (passa == "s"){
     location.href="fis1_tiafaba002.php?y90_codtiaf="+chave;
  }else if (passa == "n"){
     location.href="fis1_tiafaba003.php?y90_codtiaf="+chave;
  } else if (passa == "e"){
     location.href="fis1_tiafaba004.php?y90_codtiaf="+chave;
  }  
}
</script>