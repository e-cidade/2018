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

//MODULO: pessoal
$clrhvisavale->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
$clrotulo->label("rh27_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("nomefuncao");
$result_instit = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"),"codigo as rh47_instit,nomeinst"));
//echo ($cldb_config->sql_query_file(db_getsession("DB_instit"),"codigo as rh47_instit,nomeinst"));
if($cldb_config->numrows > 0){
	db_fieldsmemory($result_instit, 0);
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <td>
     <fieldset><legend><b>Cadastro de tipo</b></legend>
  <table>

  <tr>
    <td nowrap title="<?=@$Trh47_instit?>" align="right">
       <?
       db_ancora(@$Lrh47_instit,"js_pesquisarh47_instit(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('rh47_instit',4,$Irh47_instit,true,'text',3," onchange='js_pesquisarh47_instit(false);'")
?>
       <?
db_input('nomeinst',50,$Inomeinst,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_numcgm?>" align="right">
      <?
      db_ancora("<b>Interlocutor 1:</b>","js_pesquisaz01_numcgm(true,1);",$db_opcao);
      ?>
    </td>
    <td>
      <?
      db_input("z01_numcgm",8,$Iz01_numcgm,true,"text",$db_opcao,"onchange='js_pesquisaz01_numcgm(false,1);'","inter1")
      ?>
      <?
      db_input("z01_nome",46,$Iz01_nome,true,"text",3,"","deinter1")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_numcgm?>" align="right">
      <?
      db_ancora("<b>Interlocutor 2:</b>","js_pesquisaz01_numcgm(true,2);",$db_opcao);
      ?>
    </td>
    <td>
      <?
      db_input('z01_numcgm',8,$Iz01_numcgm,true,'text',$db_opcao,"onchange='js_pesquisaz01_numcgm(false,2);'","inter2")
      ?>
      <?
      db_input('z01_nome',46,$Iz01_nome,true,'text',3,'',"deinter2")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_numcgm?>" align="right">
      <?
      db_ancora("<b>Interlocutor 3:</b>","js_pesquisaz01_numcgm(true,3);",$db_opcao);
      ?>
    </td>
    <td>
      <?
      db_input('z01_numcgm',8,$Iz01_numcgm,true,'text',$db_opcao,"onchange='js_pesquisaz01_numcgm(false,3);'","inter3")
      ?>
      <?
      db_input('z01_nome',46,$Iz01_nome,true,'text',3,'',"deinter3")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh47_rubric?>" align="right">
       <?
       db_ancora(@$Lrh47_rubric,"js_pesquisarh47_rubric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh47_rubric',4,$Irh47_rubric,true,'text',$db_opcao," onchange='js_pesquisarh47_rubric(false);'")
?>
       <?
db_input('rh27_descr',50,$Irh27_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh47_perc?>" align="right">
       <?=@$Lrh47_perc?>
    </td>
    <td> 
<?
db_input('rh47_perc',5,$Irh47_perc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh47_contrato?>" align="right">
       <?=@$Lrh47_contrato?>
    </td>
    <td> 
<?
db_input('rh47_contrato',14,$Irh47_contrato,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

 <tr>
    <td nowrap title="<?=@$Trh47_db_sysfuncoes?>" align="right">
       <?
       db_ancora(@$Lrh47_db_sysfuncoes,"js_pesquisarh47_db_sysfuncoes(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('rh47_db_sysfuncoes',5,$Irh47_db_sysfuncoes,true,'text',$db_opcao," onchange='js_pesquisarh47_db_sysfuncoes(false);'")
?>
       <?
db_input('nomefuncao',49,$Inomefuncao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>




<tr>
    <td nowrap title="<?=@$Trh47_diasuteis?>" align="right">
        <?=@$Lrh47_diasuteis?>
    </td>
    <td>
        <?
        db_input('rh47_diasuteis',14,$Irh47_diasuteis,true,'text',$db_opcao,"")
        ?>
   </td>
</tr>


<tr>
    <td nowrap title="<?=@$Trh47_tipovale?>" align="right">
       <?=@$Lrh47_tipovale?>
    </td>
    <td> 
<?
$x = array('1'=>'Alimentação','2'=>'Refeição');
db_select('rh47_tipovale',$x,true,$db_opcao,"");
?>
    </td>
  </tr>

</table>
</fieldset>
</td>

</table>
</center>
<?
if($db_opcao == 2){
  echo "<input name='alterar' type='submit' id='db_opcao' value='Alterar' onclick='return js_retornacampos(1);'>&nbsp;&nbsp;";
  echo "<input name='excluir' type='submit' id='db_opcao' value='Excluir'>";
}else{
  echo "<input name='incluir' type='submit' id='db_opcao' value='Incluir' onclick='return js_retornacampos(1);'>";
}
?>
<?
db_input('opcaopesquisa',40,0,true,'hidden',3,'');
?>
</form>
<script>
function js_pesquisaz01_numcgm(mostra,opcao){
	document.form1.opcaopesquisa.value = opcao;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,'20');
  }else{
  	x0 = eval("document.form1.inter"+opcao);
    if(x0.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?testanome=true&pesquisa_chave='+x0.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false,'0');
    }else{
      eval("document.form1.deinter"+opcao+".value = ''");
      document.form1.opcaopesquisa.value = ""; 
    }
  }
}
function js_mostracgm(erro,chave){
	val = document.form1.opcaopesquisa.value;
	x1 = eval("document.form1.inter"+val);
	x2 = eval("document.form1.deinter"+val);
  x2.value = chave;
  if(erro==true){ 
    x1.focus(); 
    x1.value = ''; 
  }
	document.form1.opcaopesquisa.value = "";
}
function js_mostracgm1(chave1,chave2){
	val = document.form1.opcaopesquisa.value;
	x1 = eval("document.form1.inter"+val);
	x2 = eval("document.form1.deinter"+val);
	
	x1.value = chave1;
  x2.value = chave2;
  db_iframe_cgm.hide();
	document.form1.opcaopesquisa.value = "";
}
function js_pesquisarh47_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.rh47_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.rh47_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.rh47_instit.focus(); 
    document.form1.rh47_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.rh47_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisarh47_rubric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarhrubricas1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
     if(document.form1.rh47_rubric.value != ''){
        js_completa_rubricas(document.form1.rh47_rubric);
        js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.rh47_rubric.value+'&funcao_js=parent.js_mostrarhrubricas','Pesquisa',false);
     }else{
       document.form1.rh27_descr.value = ''; 
     }
  }
}
function js_mostrarhrubricas(chave,erro){
  document.form1.rh27_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh47_rubric.focus(); 
    document.form1.rh47_rubric.value = ''; 
  }
}
function js_mostrarhrubricas1(chave1,chave2){
  document.form1.rh47_rubric.value = chave1;
  document.form1.rh27_descr.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhvisavale','func_rhvisavale.php?funcao_js=parent.js_preenchepesquisa|rh47_instit&instit=true','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhvisavale.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_retornacampos(){
	x = document.form1;
	erro = 0;
	if(x.inter1.value != ""){
		erro ++;
	}else if(x.inter2.value != ""){
		x.inter1.value   = x.inter2.value;
		x.inter2.value   = "";
		x.deinter1.value = x.deinter2.value;
		x.deinter2.value = "";
		erro ++;
  }else if(x.inter3.value != ""){
		x.inter1.value   = x.inter3.value;
		x.inter3.value   = "";
		x.deinter1.value = x.deinter3.value;
		x.deinter3.value = "";
		erro ++;
	}

  if(x.inter2.value == "" && x.inter3.value != ""){
		x.inter2.value   = x.inter3.value;
		x.inter3.value   = "";
		x.deinter2.value = x.deinter3.value;
		x.deinter3.value = "";
		erro ++;
  }

	if(erro > 0 && x.rh47_rubric.value != "" && x.rh47_instit.value != "" && x.rh47_contrato.value != ""){
    return true;
	}else{
    if(x.rh47_instit.value == ""){
		  alert("Informe a instituição.");
		  x.rh47_instit.focus();
		}else if(x.rh47_rubric.value == ""){
		  alert("Informe a rubrica.");
		  x.rh47_rubric.focus();
		}else if(erro == 0){
		  alert("Informe no mínimo um dos interlocutores.");
 		  x.inter1.focus();
		}else{
		  alert("Informe o número do contrato.");
 		  x.rh47_contrato.focus();
    }
	}
  return false;
}


function js_pesquisarh47_db_sysfuncoes(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?funcao_js=parent.js_mostradb_sysfuncoes1|codfuncao|nomefuncao','Pesquisa',true);
  }else{
     if(document.form1.j18_db_sysfuncoes.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?pesquisa_chave='+document.form1.rh47_db_sysfuncoes.value+'&funcao_js=parent.js_mostradb_sysfuncoes','Pesquisa',false);
     }else{
       document.form1.nomefuncao.value = ''; 
     }
  }
}
function js_mostradb_sysfuncoes(chave,erro){
  document.form1.nomefuncao.value = chave; 
  if(erro==true){ 
    document.form1.rh47_db_sysfuncoes.focus(); 
    document.form1.rh47_db_sysfuncoes.value = ''; 
  }
}
function js_mostradb_sysfuncoes1(chave1,chave2){
  document.form1.rh47_db_sysfuncoes.value = chave1;
  document.form1.nomefuncao.value = chave2;
  db_iframe_db_sysfuncoes.hide();
}




</script>