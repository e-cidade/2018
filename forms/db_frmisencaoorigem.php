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

//MODULO: tributario
$clisencaocgm->rotulo->label();
$clisencaoinscr->rotulo->label();
$clisencaomatric->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v10_isencaotipo");
$clrotulo->label("z01_nome");
$clrotulo->label("q02_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border=0>
<tr><td>
<fieldset>
<legend><b>Origem da Isenção : </b></legend>
<table border="0">
   <tr>
    <td nowrap title="<?=@$Tv12_numcgm?>">
       <?
       db_ancora(@$Lv12_numcgm,"js_pesquisav12_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v12_numcgm',10,$Iv12_numcgm,true,'text',$db_opcao," onchange='js_pesquisav12_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
	<? if($origemmenu == '2'){?>
  <tr>
    <td nowrap title="<?=@$Tv16_inscr?>">
       <?
       db_ancora(@$Lv16_inscr,"js_pesquisav16_inscr(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v16_inscr',10,$Iv16_inscr,true,'text',$db_opcao," onchange='js_pesquisav16_inscr(false);'")
?>
       <?
db_input('q02_numcgm',40,$Iq02_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
	<?}else{
		   db_input('v16_inscr',10,$Iv16_inscr,true,'hidden',3,"");
    }?>
	<? if($origemmenu == '3'){?>
  <tr>
    <td nowrap title="<?=@$Tv15_matric?>">
       <?
       db_ancora(@$Lv15_matric,"js_pesquisav15_matric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v15_matric',10,$Iv15_matric,true,'text',$db_opcao," onchange='js_pesquisav15_matric(false);'")
?>
       <?
db_input('j01_numcgm',40,$Ij01_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
	<?}else{
		  db_input('v15_matric',10,$Iv15_matric,true,'hidden',3,"");
		}?>
	</fieldset>
	</td></tr>
	<tr><td align='center' colspan=2>
     <input name="continuar" type="button" id="db_opcao" value="Continuar" onClick='js_redireciona();'>
	</td></tr>
	</table>
  </center>
</form>
<script>
function js_redireciona(){
	var cgm    = (document.form1.v12_numcgm.value=='undefined'?'':document.form1.v12_numcgm.value);
  var	inscr  = (document.form1.v16_inscr.value =='undefined'?'':document.form1.v16_inscr.value);
	var matric = (document.form1.v15_matric.value=='undefined'?'':document.form1.v15_matric.value);
	var origem = '';
	var valorigem = '';
	if(cgm != '' && (inscr != '' || matric != '')){
    alert('Preencha apenas um dos campos para origem');   		
		return false;
	}else if(cgm == '' && inscr == '' && matric == ''){
    alert('Preencha um dos campos para origem !');   		
		return false;
	}
	if(cgm != ''){
		origem = 1;	  	
		valorigem = cgm;	  	
	}else if(inscr != ''){
    origem = 2;	  	
		valorigem = inscr;
	}else if(matric){
		origem = 3;	  
		valorigem = matric;
	}
  location.href = 'tri1_isencao001.php?origem='+origem+'&valorigem='+valorigem; 	
}
function js_pesquisav12_isencao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_isencao','func_isencao.php?funcao_js=parent.js_mostraisencao1|v10_sequencial|v10_isencaotipo','Pesquisa',true);
  }else{
     if(document.form1.v12_isencao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_isencao','func_isencao.php?pesquisa_chave='+document.form1.v12_isencao.value+'&funcao_js=parent.js_mostraisencao','Pesquisa',false);
     }else{
       document.form1.v10_isencaotipo.value = ''; 
     }
  }
}
function js_mostraisencao(chave,erro){
  document.form1.v10_isencaotipo.value = chave; 
  if(erro==true){ 
    document.form1.v12_isencao.focus(); 
    document.form1.v12_isencao.value = ''; 
  }
}
function js_mostraisencao1(chave1,chave2){
  document.form1.v12_isencao.value = chave1;
  document.form1.v10_isencaotipo.value = chave2;
  db_iframe_isencao.hide();
}
function js_pesquisav12_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.v12_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.v12_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
//	alert(chave+ '' +erro);
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.v12_numcgm.focus(); 
    document.form1.v12_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.v12_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisav16_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|q02_numcgm','Pesquisa',true);
  }else{
     if(document.form1.v16_inscr.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.v16_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false);
     }else{
       document.form1.q02_numcgm.value = ''; 
     }
  }
}
function js_mostraissbase(chave,erro){
  document.form1.q02_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.v16_inscr.focus(); 
    document.form1.v16_inscr.value = ''; 
  }
}
function js_mostraissbase1(chave1,chave2){
  document.form1.v16_inscr.value = chave1;
  document.form1.q02_numcgm.value = chave2;
  db_iframe_issbase.hide();
}
function js_pesquisav15_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?funcao_js=parent.js_mostraiptubase1|j01_matric|j01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.v15_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?pesquisa_chave='+document.form1.v15_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false);
     }else{
       document.form1.j01_numcgm.value = ''; 
     }
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.v15_matric.focus(); 
    document.form1.v15_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.v15_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe_iptubase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_isencaocgm','func_isencaocgm.php?funcao_js=parent.js_preenchepesquisa|v12_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_isencaocgm.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>