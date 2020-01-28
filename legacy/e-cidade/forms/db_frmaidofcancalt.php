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

//MODULO: fiscal
$claidofcanc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Procedimentos - Libera AIDOF</legend>
	<table class="form-container">
  	  <tr>
    	<td nowrap title="<?=@$Ty03_codigo?>">
          <?//=@$Ly03_codigo?>
    	</td>
    	<td> 
		  <?
		    db_input('y03_codigo',8,$Iy03_codigo,true,'hidden',$db_opcao,"")
		  ?>
    	</td>
  	  </tr>
  	  <tr>
    	<td nowrap title="<?=@$Ty03_aidof?>">
          <?
            db_ancora(@$Ly03_aidof,"js_pesquisay03_aidof(true);",$db_opcao);
          ?>
    	</td>
    	<td> 
		  <?
		    db_input('y03_aidof',6,$Iy03_aidof,true,'text',$db_opcao," onchange='js_pesquisay03_aidof(false);'")
		  ?>
          <?
			db_input('z01_nome',60,$Iz01_nome,true,'text',3,'')
          ?>
    	</td>
  	  </tr>
      <!--
      <tr>
        <td nowrap title="<?=@$Ty03_data?>">
          <?=@$Ly03_data?>
        </td>
        <td> 
		  <?
		    db_inputdata('y03_data',@$y03_data_dia,@$y03_data_mes,@$y03_data_ano,true,'text',$db_opcao,"")
		  ?>
    	</td>
  	  </tr>
  	  <tr>
    	<td nowrap title="<?=@$Ty03_usuario?>">
          <?
            db_ancora(@$Ly03_usuario,"js_pesquisay03_usuario(true);",$db_opcao);
          ?>
    	</td>
    	<td> 
		  <?
		    db_input('y03_usuario',10,$Iy03_usuario,true,'text',$db_opcao," onchange='js_pesquisay03_usuario(false);'")
		  ?>
          <?
			db_input('nome',40,$Inome,true,'text',3,'')
       	  ?>
    	</td>
  	  </tr>
  	  -->
  	  <tr>
    	<td nowrap title="<?=@$Ty03_obs?>" colspan="2">
          <fieldset class="separator">
            <legend><?=@$Ly03_obs?></legend> 
		  	<?
		      db_textarea('y03_obs',0,66,$Iy03_obs,true,'text',$db_opcao,"")
		  	?>
		  </fieldset>
    	</td>
	  </tr>
	  <!--
	  <tr>
	    <td nowrap title="<?=@$Ty03_tipocanc?>">
	      <?=@$Ly03_tipocanc?>
	    </td>
	    <td> 
		  <?
		    $x = array("f"=>"NAO","t"=>"SIM");
		    db_select('y03_tipocanc',$x,true,$db_opcao,"");
		  ?>
    	</td>
  	  </tr>
  	  -->
  	</table>
  </fieldset>
  <input name="incluir" type="submit" id="db_opcao" value="Cancelar" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisay03_aidof(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_aidof','func_aidofalt.php?funcao_js=parent.js_mostraaidof1|y08_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.y03_aidof.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_aidof','func_aidofalt.php?pesquisa_chave='+document.form1.y03_aidof.value+'&funcao_js=parent.js_mostraaidof','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostraaidof(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.y03_aidof.focus(); 
    document.form1.y03_aidof.value = ''; 
  }
}
function js_mostraaidof1(chave1,chave2){
  document.form1.y03_aidof.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_aidof.hide();
}
function js_pesquisay03_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.y03_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.y03_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.y03_usuario.focus(); 
    document.form1.y03_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.y03_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_aidofcanc','func_aidofcanc.php?funcao_js=parent.js_preenchepesquisa|y03_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_aidofcanc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("y03_aidof").addClassName("field-size2");
$("z01_nome").addClassName("field-size9");

</script>