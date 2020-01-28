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

//MODULO: fiscal
$cltiafprazoproc->rotulo->label();
$cltiafprazo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y96_codigo");
$clrotulo->label("p58_codproc");
$clrotulo->label("y90_codtiaf");
?>
<form name="form1" method="post" action="">
<center>
<?
		$where = "";
	    if(isset($y96_codigo) && $y96_codigo != ""){  
	      $where = " and y96_codigo = $y96_codigo ";
	    }
	    if(isset($y90_codtiaf) && $y90_codtiaf != ""){  
		    $rsResult = $cltiafprazo->sql_record($cltiafprazo->sql_query("","*","","y96_codtiaf = $y90_codtiaf ".$where));
		    if ($cltiafdoc->numrows > 0){
		    	db_fieldsmemory($rsResult,0);
		    	list($y96_prazo_ano,$y96_prazo_mes,$y96_prazo_dia) = split ("-", $y96_prazo);
		    }  
	    }
	    else{
	    	echo "<script>document.form1.novo.click;</script>";
	    }
	    if (isset($tipobotao) && $tipobotao == "Incluir"){
			$y90_tiafdoc   = "";
			$y96_prazo_dia = "";
			$y96_prazo_mes = "";
			$y96_prazo_ano = "";
        }
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
	 </tr>
	 <?if (isset($opcao) && $opcao!="incluir"){?>
	  <tr>	
	  <td nowrap title="<?=@$Ty96_codigo?>"> 
	    <?
	      db_ancora(@$Ly96_codigo,"",3);
	    ?>
	 </td>
	 <td>
	    <?
	     db_input('y96_codigo',10,$Iy96_codigo,true,'text',3,"");
	    ?>
	 </td>
      <?}?>
     
  
  <tr>
    <td nowrap title="<?=@$Ty97_codproc?>">
       <?
	       db_ancora(@$Ly97_codproc,"js_pesquisay97_codproc(true);",$db_opcao);
       ?>
    </td>
    <td> 
		<?
			db_input('y97_codproc',10,$Iy97_codproc,true,'text',$db_opcao," onchange='js_pesquisay97_codproc(false);'")
		?>
       <?
			db_input('p58_codproc',10,$Ip58_codproc,true,'text',3,'')
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
  
  </table>
  <?
       //echo($cltiafprazo->sql_query("","*","","y96_codtiaf = $y90_codtiaf"));
       $chavepri= array("y90_codtiaf"=>$y90_codtiaf,"y96_codigo"=>$y96_codigo);
       $cliframe_alterar_excluir->chavepri = $chavepri;
       $cliframe_alterar_excluir->sql     = $cltiafprazo->sql_query("","*","","y96_codtiaf = $y90_codtiaf");
       $cliframe_alterar_excluir->campos  = "y96_codtiaf, y97_codproc, y90_data, y96_prazo";
       $cliframe_alterar_excluir->legenda="PRAZOS PRORROGADOS";
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
       $cliframe_alterar_excluir->iframe_alterar_excluir(1);//$db_opcao;
//     js_mostratiaf1(<?=$y90_codtiaf?>);
   ?>
  </center>
  <input name="incluir" type="Submit" value="<?=$tipobotao?>" onclick="js_validacampos();">
  <input name="pesquisar" type="button" value="Pesquisar" onclick="">
  <input name="novo" type="button" value="Novo registro" onclick="js_limpacampos1(<?=$y90_codtiaf?>);">
</form>
<script>
function js_pesquisay97_codprazo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tiafprazo','func_tiafprazo.php?funcao_js=parent.js_mostratiafprazo1|y96_codigo|y96_codigo','Pesquisa',true);
  }else{
     if(document.form1.y97_codprazo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tiafprazo','func_tiafprazo.php?pesquisa_chave='+document.form1.y97_codprazo.value+'&funcao_js=parent.js_mostratiafprazo','Pesquisa',false);
     }else{
       document.form1.y96_codigo.value = ''; 
     }
  }
}
function js_mostratiafprazo(chave,erro){
  document.form1.y96_codigo.value = chave; 
  if(erro==true){ 
    document.form1.y97_codprazo.focus(); 
    document.form1.y97_codprazo.value = ''; 
  }
}
function js_mostratiafprazo1(chave1,chave2){
  document.form1.y97_codprazo.value = chave1;
  document.form1.y96_codigo.value = chave2;
  db_iframe_tiafprazo.hide();
}
function js_pesquisay97_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_codproc','Pesquisa',true);
  }else{
     if(document.form1.y97_codproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.y97_codproc.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
     }else{
       document.form1.p58_codproc.value = ''; 
     }
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.p58_codproc.value = chave; 
  if(erro==true){ 
    document.form1.y97_codproc.focus(); 
    document.form1.y97_codproc.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.y97_codproc.value = chave1;
  document.form1.p58_codproc.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tiafprazoproc','func_tiafprazoproc.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tiafprazoproc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
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
  location.href="fis1_tiafprazoproc001.php?y90_codtiaf="+chave;
/*  if (passa == "s"){
     location.href="fis1_tiafaba002.php?y90_codtiaf="+chave;
  }else if (passa == "n"){
     location.href="fis1_tiafaba003.php?y90_codtiaf="+chave;
  } else if (passa == "e"){
     location.href="fis1_tiafaba004.php?y90_codtiaf="+chave;
  }*/  
}
function js_limpacampos(chave){
  /*for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'text'){
      document.form1.elements[i].value = '';
    }
    if(document.form1.elements[i].type == 'textarea'){
      document.form1.elements[i].value = '';
    }
  }*/
  location.href="fis1_tiafprazoproc001.php?y90_codtiaf="+chave;  
}


</script>