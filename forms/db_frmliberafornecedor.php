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

//MODULO: Compras
$clliberafornecedor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("z01_nome");
if($db_opcao==1){
  $db_action="com1_liberafornecedor004.php";
}else if($db_opcao==2||$db_opcao==22){
  $db_action="com1_liberafornecedor005.php";
}else if($db_opcao==3||$db_opcao==33){
  $db_action="com1_liberafornecedor006.php";
}  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table style="margin-top: 15px;" align="center">
  <tr>
    <td nowrap title="<?=@$Tpc82_numcgm?>">
    &nbsp;
    <?
    db_ancora("<b>Fornecedor</b>","js_pesquisapc82_numcgm(true);",$db_opcao);
    ?>
    </td>
    <td> 
    <?
    db_input('pc82_numcgm',10,$Ipc82_numcgm,true,'text',$db_opcao," onchange='js_pesquisapc82_numcgm(false);'")
    ?>
    <?
    db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
    ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
    <fieldset><legend><b>Dados Liberação</b></legend>
    
		<table border="0">
		  <tr>
		    <td nowrap title="<?//=@$Tpc82_sequencial?>">
		       <?//=@$Lpc82_sequencial?>
		    </td>
		    <td> 
					<?
					db_input('pc82_sequencial',10,$Ipc82_sequencial,true,'hidden',3,"")
					?>
		    </td>
		  </tr>
		  <tr>
		    <td colspan="2">
		       <b>Período Liberação:</b>&nbsp;
		       <?
           db_inputdata('pc82_dataini',@$pc82_dataini_dia,@$pc82_dataini_mes,@$pc82_dataini_ano,true,'text',$db_opcao,"");
           ?>
		     
		      &nbsp;<b>à</b>&nbsp;
				  <?
          db_inputdata('pc82_datafim',@$pc82_datafim_dia,@$pc82_datafim_mes,@$pc82_datafim_ano,true,'text',$db_opcao,"")
          ?>
		    </td>
		  </tr>
		  <tr>
        
        <td nowrap title="<?=@$Tpc82_liberasol?>" align="left" colspan="2"> 
        <?
        $selecionado = "";
        if(isset($pc82_liberasol) && $pc82_liberasol == 't') $selecionado = "checked";
        db_input("pc82_liberasol",20,0,true,"checkbox", $db_opcao," $selecionado onChange='js_onChangeCheckBox(this)'");
        ?>
        &nbsp;&nbsp;
        <?=@$Lpc82_liberasol?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tpc82_liberaproc?>" align="left" colspan="2"> 
        <?
        $selecionado = "";
        if(isset($pc82_liberaproc) && $pc82_liberaproc == 't') $selecionado = "checked";
        db_input("pc82_liberaproc",20,0,true,"checkbox", $db_opcao," $selecionado onChange='js_onChangeCheckBox(this)'");
        ?>
        &nbsp;&nbsp;   
        <?=@$Lpc82_liberaproc?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tpc82_liberaaut?>" align="left" colspan="2"> 
        <?
        $selecionado = "";
        if(isset($pc82_liberaaut) && $pc82_liberaaut == 't') $selecionado = "checked";
        db_input("pc82_liberaaut",20,0,true,"checkbox", $db_opcao," $selecionado onChange='js_onChangeCheckBox(this)'");
        ?>
        &nbsp;&nbsp;
        <?=@$Lpc82_liberaaut?>
        </td>
      </tr>
		  <tr> 
		    <td nowrap title="<?=@$Tpc82_obs?>" colspan="2">
		    <br>
        <?=@$Lpc82_obs?>       
        </td>
		  </tr>
		  <tr> 
        <td colspan="2">
               
        <?
        db_textarea('pc82_obs',5,60,$Ipc82_obs,true,'text',$db_opcao,"");
        ?>
        </td>
      </tr>
		</table>
		
		</fieldset>
    </td>
  </tr>
</table>
</center>
<input onclick='return js_valida();' name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Cancelar"))?>" <?=($db_botao==false?"disabled":"")?> >
<?
if($db_opcao != 1){
	?> 
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  <?
}
?>
</form>
<script>
function js_valida(){

  var dtIni = document.getElementById('pc82_dataini').value;
  var dtFim = document.getElementById('pc82_datafim').value;
  
  
  if(dtIni != "" || dtFim != ""){
  
	  if (dtIni == "" || dtIni == null){
	    alert("usuário:\n\nPeríodo inicial não informado!!!");
	    document.getElementById('pc82_dataini').focus();
	    return false;
	  }
	  
	  if (dtFim == "" || dtFim == null){
	    alert("usuário:\n\nPeríodo Fim não informado!!!");
	    document.getElementById('pc82_datafim').focus();
	    return false;
	  }
	  
	}
  
}

function js_onChangeCheckBox(idCheckBox){
  
  var objCheckBox = idCheckBox;
  if(objCheckBox.checked){
    objCheckBox.value = 't';
  }else{
    objCheckBox.value = 'f';
  }
  
}

function js_pesquisapc82_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_liberafornecedor','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,'0','1');
  }else{
     if(document.form1.pc82_id_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_liberafornecedor','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.pc82_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false,'0','1');
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.pc82_id_usuario.focus(); 
    document.form1.pc82_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.pc82_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisapc82_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_liberafornecedor','func_nome','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,'0','1');
  }else{
     if(document.form1.pc82_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_liberafornecedor','func_nome','func_cgm.php?pesquisa_chave='+document.form1.pc82_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false,'0','1');
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = erro; 
  if(erro==true){ 
    document.form1.pc82_numcgm.focus(); 
    document.form1.pc82_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.pc82_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  func_nome.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_liberafornecedor','db_iframe_liberafornecedor','func_liberafornecedor.php?funcao_js=parent.js_preenchepesquisa|pc82_sequencial&pc82_ativo=true','Pesquisa',true,'0','1');
}
function js_preenchepesquisa(chave){
  db_iframe_liberafornecedor.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>