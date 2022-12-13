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
    <?
	    $where = "";
	    if(isset($y99_coddoc) && $y99_coddoc != ""){  
	      $where = " and y99_coddoc = $y99_coddoc ";
	    }
	    //if(isset($y90_codtiaf) && $y90_codtiaf != ""){
		    $result = $cltiafdoc->sql_record($cltiafdoc->sql_query("","*","","y99_codtiaf = $y90_codtiaf".$where));
		    if ($cltiafdoc->numrows > 0){
		    	db_fieldsmemory($result,0);
		    	list($y99_dataini_ano,$y99_dataini_mes,$y99_dataini_dia) = split ("-", $y99_dtini);
		   		list($y99_datafim_ano,$y99_datafim_mes,$y99_datafim_dia) = split ("-", $y99_dtfim);
		    }else{
		    	echo "<script>document.form1.novo.click;</script>";
		    }
		    if (isset($tipobotao) && $tipobotao == "Incluir"){
		    		$y99_coddoc = "";
					$y98_tiafdoc = "";
					$y98_descr = "";
					$y99_dataini_ano = "";
					$y99_dataini_mes = "";
					$y99_dataini_dia = "";
					$y99_datafim_ano = "";
					$y99_datafim_mes = "";
					$y99_datafim_dia = "";
				    $y99_obs = "";
				    
	         }
	    //}
    ?>
   <table border="0">
      <tr>
         <td nowrap title="<?=@$Ty90_codtiaf?>"> 
	    <?
	      db_ancora(@$Ly90_codtiaf,"js_pesquisay90_codtiaf(true);",$db_opcao);
	    ?>
	 </td>
	 <td>
	    <?
	     db_input('y90_codtiaf',10,$Iy90_codtiaf,true,'text',3," onchange='js_pesquisay90_codtiaf(false);'");
	    ?>
	 </td>
	 </tr>
	 <?if (isset($opcao) && $opcao!="incluir"){?>
	  <tr>	
	  <td nowrap title="<?=@$Ty99_coddoc?>"> 
	    <?
	      db_ancora(@$Ly99_coddoc,"js_pesquisay99_coddoc(true);",3);
	    ?>
	 </td>
	 <td>
	    <?
	     db_input('y99_coddoc',10,$Iy99_coddoc,true,'text',3," onchange='js_pesquisay99_coddoc(false);'");
	    ?>
	 </td>
      <?}?>
     </tr>
      <tr>
         <td nowrap title="<?=@$Ty98_tiafdoc?>"> 
	    <?
	     db_ancora(@$Ly98_tiafdoc,"js_pesquisay98_tiafdoc(true);",$db_opcao);
	    ?>
	 </td>
	 <td>
	    <?
	     db_input('y98_tiafdoc',10,$Iy98_tiafdoc,true,'text',$db_opcao," onchange='js_pesquisay98_tiafdoc(false);'");
	    ?>
	    <?
	     db_input('y98_descr',40,$Iy98_descr,true,'text',3,'');
	    ?>
	 </td>
      </tr>
      
      <tr>
        <td align='center'>
            <b> Data inicial : </b>
        </td>
        <td>
            <? 
              db_inputdata('y99_dataini',@$y99_dataini_dia,@$y99_dataini_mes,@$y99_dataini_ano,true,'text',$db_opcao,"")
            ?>
    	</td>
	  </tr>
	  
	  <tr>
	    <td align='center'>
	       <b> Data final : </b>
	    </td>
	    <td>
	      <? 
	         db_inputdata('y99_datafim',@$y99_datafim_dia,@$y99_datafim_mes,@$y99_datafim_ano,true,'text',$db_opcao,"")       
	      ?>
        </td>
      </tr>
       <tr>
    <td nowrap title="<?=@$Ty99_obs?>">
       <?=@$Ly99_obs?>
    </td>
    <td> 
		<?
			db_textarea('y99_obs',5,50,$Iy99_obs,true,'text',$db_opcao,"")
		?>
    </td>
  </tr>
  <tr>
    <td>
    </td>
  </tr>
    </table>
    <center>
    <? 
       //echo($cltiafdoc->sql_query($y99_coddoc,"y99_coddoc, y98_descr, y99_obs,y90_codtiaf",$y99_coddoc,"y99_codtiaf = $y90_codtiaf"));
       $chavepri= array("y90_codtiaf"=>$y90_codtiaf,"y99_coddoc"=>$y99_coddoc);
       $cliframe_alterar_excluir->chavepri = $chavepri;
       $cliframe_alterar_excluir->sql     = $cltiafdoc->sql_query($y99_coddoc,"y98_tiafdoc, y99_coddoc, y98_descr, y90_codtiaf, y99_obs, y99_dtini, y99_dtfim","y99_coddoc","y99_codtiaf = $y90_codtiaf");
       $cliframe_alterar_excluir->campos  = "y99_coddoc, y98_descr, y99_obs ";
       $cliframe_alterar_excluir->legenda="DOCUMENTOS DO TIAF";
       $cliframe_alterar_excluir->iframe_height ="120";
       $cliframe_alterar_excluir->iframe_width ="712";
       $val = 1;
       $verific = "N";
       if($db_botao==false && $db_opcao==3){
         $val = 3;
	     $verific = "S";
       }
       $cliframe_alterar_excluir->opcoes = 1;
       $cliframe_alterar_excluir->fieldset = false;
       $cliframe_alterar_excluir->iframe_alterar_excluir(1); //$db_opcao;
       
       //js_validacampos(<?=$y90_codtiaf?>);
    ?>
    </center>
    <input name="incluir" type="Submit" value="<?=(isset($tipobotao)&&$tipobotao!=""?$tipobotao:"Incluir")?>" onclick="js_valida(<?=$y90_codtiaf?>);">
    <input name="pesquisar" type="button" value="Pesquisar" onclick="js_pesquisay90_codtiaf(true);">
    <input name="novo" type="button" value="Novo registro" onclick="js_limpacampos1(<?=$y90_codtiaf?>);">
    </center>
</form>
<script>

function js_pesquisay98_tiafdoc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tiaftipodoc','func_tiaftipodoc.php?funcao_js=parent.js_mostratiafdoc1|y98_tiafdoc|y98_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tiaftipodoc','func_tiaftipodoc.php?pesquisa_chave='+document.form1.y98_tiafdoc.value+'&funcao_js=parent.js_mostratiafdoc','Pesquisa',false);
  }
}

function js_mostratiafdoc(chave,erro){
  document.form1.y98_descr.value = chave;
  if(erro==true){ 
	  document.form1.y98_tiafdoc.focus(); 
	  document.form1.y98_tiafdoc.value = ''; 
  }
}

function js_mostratiafdoc1(chave,chave2){
  document.form1.y98_tiafdoc.value = chave;
  document.form1.y98_descr.value = chave2;
  db_iframe_tiaftipodoc.hide();
  
}

function js_pesquisay90_codtiaf(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tiaf','func_tiaf.php?funcao_js=parent.js_mostratiaf1|y90_codtiaf','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tiaf','func_tiaf.php?pesquisa_chave='+document.form1.y90_codtiaf.value+'&funcao_js=parent.js_mostratiaf','Pesquisa',false);
  }
}

function js_mostratiaf(chave,erro){
  document.form1.y90_codtiaf.value = chave; 
  if(erro==true){ 
    document.form1.y90_codtiaf.focus(); 
    document.form1.y90_codtiaf.value = ''; 
  }
}

function js_mostratiaf1(chave) {
  document.form1.y90_codtiaf.value = chave;
  db_iframe_tiaf.hide();
  location.href="fis1_tiafaba002.php?y90_codtiaf="+chave;
}

function js_limpacampos1(chave){
  alert('js_limpacampos1');
  <?
  	$tipobotao = "Incluir";
  ?>	
  location.href="fis1_tiafaba002.php?y90_codtiaf="+chave;
  document.form1.y99_dataini.value = "";
  document.form1.y99_datafim.value = "";
  document.form1.y99_obs.value = "";
  document.form1.y98_tiafdoc.value = "";  
}

function js_valida(chave){
	alert('js_valida'); 
	location.href="fis1_tiafaba002.php?y90_codtiaf="+chave;
	js_limpacampos1(chave);  
}

function js_limpacampos(chave){
  alert('js_limpacampos');
  /*for(i=0;i<document.form1.length;i++) {
    if(document.form1.elements[i].type == 'text'){
      document.form1.elements[i].value = '';
    }
    if(document.form1.elements[i].type == 'textarea'){
      document.form1.elements[i].value = '';
    }
  }*/
  location.href="fis1_tiafaba002.php?y90_codtiaf="+chave;  
}
</script>