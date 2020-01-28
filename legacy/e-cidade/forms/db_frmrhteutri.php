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
$clrhteutri->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("rh68_descr");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table align="center">

  <tr> 
    <td >&nbsp;</td>
  </tr>
  <tr>
  <td>
  <fieldset>
    <Legend align="left">
    <b>Cadastro de Vale Transporte Integrado</b>
    </Legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh67_sequencial?>">
       <?=@$Lrh67_sequencial?>
    </td>
    <td> 
<?
db_input('rh67_sequencial',6,$Irh67_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh67_regist?>">
       <?
       db_ancora(@$Lrh67_regist,"js_pesquisarh67_regist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh67_regist',6,$Irh67_regist,true,'text',$db_opcao," onchange='js_pesquisarh67_regist(false);'")
?>
       <?
db_input('z01_nome',45,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh67_rhtipovale?>">
       <?
       db_ancora(@$Lrh67_rhtipovale,"js_pesquisarh67_rhtipovale(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh67_rhtipovale',6,$Irh67_rhtipovale,true,'text',$db_opcao," onchange='js_pesquisarh67_rhtipovale(false);'")
?>
       <?
db_input('rh68_descr',45,$Irh68_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh67_cartao?>">
       <?=@$Lrh67_cartao?>
    </td>
    <td> 
<?
db_input('rh67_cartao',20,$Irh67_cartao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh67_grupo?>">
       <?=@$Lrh67_grupo?>
    </td>
    <td> 
<?
db_input('rh67_grupo',6,$Irh67_grupo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh67_dias?>">
       <?=@$Lrh67_dias?>
    </td>
    <td> 
<?
db_input('rh67_dias',6,$Irh67_dias,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh67_vales?>">
       <?=@$Lrh67_vales?>
    </td>
    <td> 
<?
db_input('rh67_vales',6,$Irh67_vales,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh67_ativo?>">
       <?=@$Lrh67_ativo?>
    </td>
    <td> 
<?
$x = array("t"=>"SIM","f"=>"NAO");
db_select('rh67_ativo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
<?
$rh67_db_usuarios = db_getsession('DB_id_usuario'); 
db_input('rh67_db_usuarios',10,$Irh67_db_usuarios,true,'hidden',3,'');

$rh67_data = date("Y/m/d",db_getsession('DB_datausu')); // 03/04/2008
db_input('rh67_data',10,$Irh67_data,true,'hidden',3,'');
//db_inputdata('rh67_data',@$rh67_data_dia,@$rh67_data_mes,@$rh67_data_ano,true,'hidden',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </td>
  </tr>
  </fieldset>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh67_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.rh67_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.rh67_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false);
     }else{
       document.form1.rh01_numcgm.value = ''; 
     }
  }
}
function js_mostrarhpessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.rh67_regist.focus(); 
    document.form1.rh67_regist.value = ''; 
  }
}
function js_mostrarhpessoal1(chave1,chave2){
  document.form1.rh67_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
}
function js_pesquisarh67_rhtipovale(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhtipovale','func_rhtipovale.php?funcao_js=parent.js_mostrarhtipovale1|rh68_sequencial|rh68_descr','Pesquisa',true);
  }else{
     if(document.form1.rh67_rhtipovale.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhtipovale','func_rhtipovale.php?pesquisa_chave='+document.form1.rh67_rhtipovale.value+'&funcao_js=parent.js_mostrarhtipovale','Pesquisa',false);
     }else{
       document.form1.rh68_descr.value = ''; 
     }
  }
}
function js_mostrarhtipovale(chave,erro){
  document.form1.rh68_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh67_rhtipovale.focus(); 
    document.form1.rh67_rhtipovale.value = ''; 
  }
}
function js_mostrarhtipovale1(chave1,chave2){
  document.form1.rh67_rhtipovale.value = chave1;
  document.form1.rh68_descr.value = chave2;
  db_iframe_rhtipovale.hide();
}
function js_pesquisarh67_db_usuarios(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.rh67_db_usuarios.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.rh67_db_usuarios.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.rh67_db_usuarios.focus(); 
    document.form1.rh67_db_usuarios.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.rh67_db_usuarios.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhteutri','func_rhteutri.php?funcao_js=parent.js_preenchepesquisa|rh67_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhteutri.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>