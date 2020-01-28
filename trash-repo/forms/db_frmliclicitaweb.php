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

//MODULO: licitação
$clliclicitaweb->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("l20_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tl29_sequencial?>">
       <?=@$Ll29_sequencial?>
    </td>
    <td> 
<?
db_input('l29_sequencial',10,$Il29_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl29_liclicita?>">
    <b>
       <?
       db_ancora("Licitação:","js_pesquisal29_liclicita(true);",$db_opcao);
       ?>
       </b>
    </td>
    <td> 
<?
db_input('l29_liclicita',10,$Il29_liclicita,true,'text',3," onchange='js_pesquisal29_liclicita(false);'")
?>
       <?
db_input('l20_codigo',10,$Il20_codigo,true,'hidden',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl29_datapublic?>">
       <?=@$Ll29_datapublic?>
    </td>
    <td> 
<?
db_inputdata('l29_datapublic',@$l29_datapublic_dia,@$l29_datapublic_mes,@$l29_datapublic_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl29_contato?>">
       <?=@$Ll29_contato?>
    </td>
    <td> 
<?
db_input('l29_contato',60,$Il29_contato,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl29_email?>">
       <?=@$Ll29_email?>
    </td>
    <td> 
<?
db_input('l29_email',100,$Il29_email,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl29_telefone?>">
       <?=@$Ll29_telefone?>
    </td>
    <td> 
<?
db_input('l29_telefone',15,$Il29_telefone,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl29_obs?>">
       <?=@$Ll29_obs?>
    </td>
    <td> 
<?
db_textarea('l29_obs',0,60,$Il29_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl29_liberaedital?>">
       <?//=@$Ll29_liberaedital?>
       <b>Libera Edital :</b>
    </td>
    <td> 
<?
$x = array('1'=>'Sem Cadastro','2'=>'Com Cadastro');
db_select('l29_liberaedital',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisal29_liclicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicita.php?funcao_js=parent.js_mostraliclicita1|l20_codigo|l20_codigo','Pesquisa',true);
  }else{
     if(document.form1.l29_liclicita.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicita.php?pesquisa_chave='+document.form1.l29_liclicita.value+'&funcao_js=parent.js_mostraliclicita','Pesquisa',false);
     }else{
       document.form1.l20_codigo.value = ''; 
     }
  }
}
function js_mostraliclicita(chave,erro){
  document.form1.l20_codigo.value = chave; 
  if(erro==true){ 
    document.form1.l29_liclicita.focus(); 
    document.form1.l29_liclicita.value = ''; 
  }
}
function js_mostraliclicita1(chave1,chave2){
  document.form1.l29_liclicita.value = chave1;
  document.form1.l20_codigo.value = chave2;
  db_iframe_liclicita.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_liclicitaweb','func_liclicitaweb.php?funcao_js=parent.js_preenchepesquisa|l29_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_liclicitaweb.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>