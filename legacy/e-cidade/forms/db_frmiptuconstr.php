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

//MODULO: cadastro
$cliptuconstr->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j01_numcgm");
$clrotulo->label("j14_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj39_matric?>">
       <?
       db_ancora(@$Lj39_matric,"js_pesquisaj39_matric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j39_matric',6,$Ij39_matric,true,'text',$db_opcao," onchange='js_pesquisaj39_matric(false);'")
?>
       <?
db_input('j01_numcgm',4,$Ij01_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj39_idcons?>">
       <?=@$Lj39_idcons?>
    </td>
    <td> 
<?
db_input('j39_idcons',4,$Ij39_idcons,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj39_ano?>">
       <?=@$Lj39_ano?>
    </td>
    <td> 
<?
db_input('j39_ano',4,$Ij39_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj39_area?>">
       <?=@$Lj39_area?>
    </td>
    <td> 
<?
db_input('j39_area',15,$Ij39_area,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj39_areap?>">
       <?=@$Lj39_areap?>
    </td>
    <td> 
<?
db_input('j39_areap',15,$Ij39_areap,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj39_dtlan?>">
       <?=@$Lj39_dtlan?>
    </td>
    <td> 
<?
db_inputdata('j39_dtlan',@$j39_dtlan_dia,@$j39_dtlan_mes,@$j39_dtlan_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj39_codigo?>">
       <?
       db_ancora(@$Lj39_codigo,"js_pesquisaj39_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j39_codigo',4,$Ij39_codigo,true,'text',$db_opcao," onchange='js_pesquisaj39_codigo(false);'")
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj39_numero?>">
       <?=@$Lj39_numero?>
    </td>
    <td> 
<?
db_input('j39_numero',6,$Ij39_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj39_compl?>">
       <?=@$Lj39_compl?>
    </td>
    <td> 
<?
db_input('j39_compl',20,$Ij39_compl,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj39_dtdemo?>">
       <?=@$Lj39_dtdemo?>
    </td>
    <td> 
<?
db_inputdata('j39_dtdemo',@$j39_dtdemo_dia,@$j39_dtdemo_mes,@$j39_dtdemo_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj39_idaument?>">
       <?=@$Lj39_idaument?>
    </td>
    <td> 
<?
db_input('j39_idaument',6,$Ij39_idaument,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj39_idprinc?>">
       <?=@$Lj39_idprinc?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('j39_idprinc',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj39_matric(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_mostraiptubase1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.j39_matric.value+'&funcao_js=parent.js_mostraiptubase';
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.j39_matric.focus(); 
    document.form1.j39_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j39_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe.hide();
}
function js_pesquisaj39_codigo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_mostraruas1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form1.j39_codigo.value+'&funcao_js=parent.js_mostraruas';
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.j39_codigo.focus(); 
    document.form1.j39_codigo.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j39_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_iptuconstr.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
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