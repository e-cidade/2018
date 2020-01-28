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

//MODULO: veiculos
$clveicbaixa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve01_placa");
$clrotulo->label("ve12_descr");
if (!isset($ve04_data)||@$ve04_data==""){
	$ve04_data_dia=date("d",db_getsession("DB_datausu"));	
	$ve04_data_mes=date("m",db_getsession("DB_datausu"));
	$ve04_data_ano=date("Y",db_getsession("DB_datausu"));	
}
if (!isset($ve04_hora)||@$ve04_hora==""){
	$ve04_hora=db_hora();
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?//=@$Tve04_codigo?>">
       <?//=@$Lve04_codigo?>
    </td>
    <td> 
<?
db_input('ve04_codigo',10,$Ive04_codigo,true,'hidden',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve04_veiculo?>">
       <?
       db_ancora(@$Lve04_veiculo,"js_pesquisave04_veiculo(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('ve04_veiculo',10,$Ive04_veiculo,true,'text',3," onchange='js_pesquisave04_veiculo(false);'")
?>
       <?
db_input('ve01_placa',10,$Ive01_placa,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve04_data?>">
       <?=@$Lve04_data?>
    </td>
    <td> 
<?
db_inputdata('ve04_data',@$ve04_data_dia,@$ve04_data_mes,@$ve04_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve04_hora?>">
       <?=@$Lve04_hora?>
    </td>
    <td> 
<?
db_input('ve04_hora',5,$Ive04_hora,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve04_veiccadtipobaixa?>">
       <?
       db_ancora(@$Lve04_veiccadtipobaixa,"js_pesquisave04_veiccadtipobaixa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ve04_veiccadtipobaixa',10,$Ive04_veiccadtipobaixa,true,'text',$db_opcao," onchange='js_pesquisave04_veiccadtipobaixa(false);'")
?>
       <?
db_input('ve12_descr',40,0,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve04_motivo?>">
       <?=@$Lve04_motivo?>
    </td>
    <td> 
<?
db_textarea('ve04_motivo',0,50,$Ive04_motivo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Incluir":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisave04_veiccadtipobaixa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccadtipobaixa','func_veiccadtipobaixa.php?funcao_js=parent.js_mostraveiccadtipobaixa1|ve12_sequencial|ve12_descr','Pesquisa',true);
  }else{
     if(document.form1.ve04_veiccadtipobaixa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccadtipobaixa','func_veiccadtipobaixa.php?pesquisa_chave='+document.form1.ve04_veiccadtipobaixa.value+'&funcao_js=parent.js_mostraveiccadtipobaixa','Pesquisa',false);
     }else{
       document.form1.ve12_descr.value = ''; 
     }
  }
}
function js_mostraveiccadtipobaixa(chave,erro){
  document.form1.ve12_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve04_veiccadtipobaixa.focus(); 
    document.form1.ve04_veiccadtipobaixa.value = ''; 
  }
}
function js_mostraveiccadtipobaixa1(chave1,chave2){
  document.form1.ve04_veiccadtipobaixa.value = chave1;
  document.form1.ve12_descr.value            = chave2;
  db_iframe_veiccadtipobaixa.hide();
}
function js_pesquisave04_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.ve04_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.ve04_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.ve04_usuario.focus(); 
    document.form1.ve04_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.ve04_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisave04_veiculo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculos.php?funcao_js=parent.js_mostraveiculos1|ve01_codigo|ve01_codigo','Pesquisa',true);
  }else{
     if(document.form1.ve04_veiculo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculos.php?pesquisa_chave='+document.form1.ve04_veiculo.value+'&funcao_js=parent.js_mostraveiculos','Pesquisa',false);
     }else{
       document.form1.ve01_codigo.value = ''; 
     }
  }
}
function js_mostraveiculos(chave,erro){
  document.form1.ve01_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ve04_veiculo.focus(); 
    document.form1.ve04_veiculo.value = ''; 
  }
}
function js_mostraveiculos1(chave1,chave2){
  document.form1.ve04_veiculo.value = chave1;
  document.form1.ve01_codigo.value = chave2;
  db_iframe_veiculos.hide();
}
function js_pesquisa(){
<?
  if($db_opcao==22||$db_opcao==2){
?>
	baixa="1";
<?
  }else{
?>
	baixa="0";
<?
  }
?>
  js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculosalt.php?&baixa='+baixa+'&funcao_js=parent.js_preenchepesquisa|ve01_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_veiculos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>