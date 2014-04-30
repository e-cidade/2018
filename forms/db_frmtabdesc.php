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

//MODULO: caixa
$cltabdesc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k02_descr");
$clrotulo->label("i01_descr");
$clrotulo->label("k78_arretipo");
$clrotulo->label("k00_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcodsubrec?>">
       <?=@$Lcodsubrec?>
    </td>
    <td> 
<?
db_input('codsubrec',10,$Icodsubrec,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk07_codigo?>">
       <?
       db_ancora(@$Lk07_codigo,"js_pesquisak07_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k07_codigo',10,$Ik07_codigo,true,'text',$db_opcao," onchange='js_pesquisak07_codigo(false);'")
?>
       <?
db_input('k02_descr',50,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk07_descr?>">
       <?=@$Lk07_descr?>
    </td>
    <td> 
<?
db_input('k07_descr',63,$Ik07_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk07_valorf?>">
       <?=@$Lk07_valorf?>
    </td>
    <td> 
<?
db_input('k07_valorf',10,$Ik07_valorf,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk07_valorv?>">
       <?=@$Lk07_valorv?>
    </td>
    <td> 
<?
db_input('k07_valorv',10,$Ik07_valorv,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk07_quamin?>">
       <?=@$Lk07_quamin?>
    </td>
    <td> 
<?
db_input('k07_quamin',10,$Ik07_quamin,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk07_percde?>">
       <?=@$Lk07_percde?>
    </td>
    <td> 
<?
db_input('k07_percde',10,$Ik07_percde,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk07_data?>">
       <?=@$Lk07_data?>
    </td>
    <td> 
<?
db_inputdata('k07_data',@$k07_data_dia,@$k07_data_mes,@$k07_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk07_codinf?>">
       <?
       db_ancora(@$Lk07_codinf,"js_pesquisak07_codinf(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k07_codinf',10,$Ik07_codinf,true,'text',3," onchange='js_pesquisak07_codinf(false);'")

?>
       <?
db_input('i01_descr',50,$Ii01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk07_dtval?>">
       <?=@$Lk07_dtval?>
    </td>
    <td> 
<?
db_inputdata('k07_dtval',@$k07_dtval_dia,@$k07_dtval_mes,@$k07_dtval_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
	 <tr>
    <td nowrap title="<?=@$Tk78_arretipo?>">
    	 <b>
       <?
       db_ancora("Tipo para Recibo Protocolo","js_pesquisa_tipo(true);",$db_opcao);
       ?>
			 </b>
    </td>
    <td> 
<?
db_input('k78_arretipo',10,$Ik78_arretipo,true,'text',$db_opcao," onchange='js_pesquisa_tipo(false);'")
?>
       <?
db_input('k00_descr',40,$Ik00_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>
<script>
function js_pesquisak07_codigo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_tabrec.php?funcao_js=parent.js_mostratabrec1|1|3';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_tabrec.php?pesquisa_chave='+document.form1.k07_codigo.value+'&funcao_js=parent.js_mostratabrec';
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.k07_codigo.focus(); 
    document.form1.k07_codigo.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.k07_codigo.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisak07_codinf(mostra){
	
  if(mostra==true){
    db_iframe.jan.location.href = 'func_inflan.php?funcao_js=parent.js_mostrainflan1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
		db_iframe.jan.location.href = 'func_inflan.php?pesquisa_chave='+document.form1.k07_codinf.value+'&funcao_js=parent.js_mostrainflan';
	
  }
}
function js_mostrainflan(chave,erro){
  document.form1.i01_descr.value = chave; 
  if(erro==true){ 
    document.form1.k07_codinf.focus(); 
    document.form1.k07_codinf.value = ''; 
  }
}
function js_mostrainflan1(chave1,chave2){
  document.form1.k07_codinf.value = chave1;
  document.form1.i01_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_tabdesc.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}


function js_pesquisa_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr&k03_tipo=14','Pesquisa',true);
  }else{
     if(document.form1.k78_arretipo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.k78_arretipo.value+'&funcao_js=parent.js_mostraarretipo&k03_tipo=14','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.k78_arretipo.focus(); 
    document.form1.k78_arretipo.value = ''; 
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.k78_arretipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>