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
<?
//MODULO: educação
$clturmas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed10_c_nome");
$clrotulo->label("ed03_c_nome");
$clrotulo->label("ed02_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted05_i_codigo?>">
       <?=@$Led05_i_codigo?>
    </td>
    <td> 
<?
db_input('ed05_i_codigo',5,$Ied05_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted05_i_escola?>">
       <?
       db_ancora(@$Led05_i_escola,"js_pesquisaed05_i_escola(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed05_i_escola',10,$Ied05_i_escola,true,'text',$db_opcao," onchange='js_pesquisaed05_i_escola(false);'")
?>
       <?
db_input('ed02_i_codigo',10,$Ied02_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted05_i_turno?>">
       <?
       db_ancora(@$Led05_i_turno,"js_pesquisaed05_i_turno(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed05_i_turno',10,$Ied05_i_turno,true,'text',$db_opcao," onchange='js_pesquisaed05_i_turno(false);'")
?>
       <?
db_input('ed10_c_nome',20,$Ied10_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted05_i_serie?>">
       <?
       db_ancora(@$Led05_i_serie,"js_pesquisaed05_i_serie(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed05_i_serie',10,$Ied05_i_serie,true,'text',$db_opcao," onchange='js_pesquisaed05_i_serie(false);'")
?>
       <?
db_input('ed03_c_nome',40,$Ied03_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted05_c_nome?>">
       <?=@$Led05_c_nome?>
    </td>
    <td> 
<?
db_input('ed05_c_nome',40,$Ied05_c_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted05_i_criterio?>">
       <?=@$Led05_i_criterio?>
    </td>
    <td> 
<?
$x = array('1'=>'Notas','2'=>'Conceito','3'=>'Parecer');
db_select('ed05_i_criterio',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed05_i_turno(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_turnos','func_turnos.php?funcao_js=parent.js_mostraturnos1|ed10_i_codigo|ed10_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed05_i_turno.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_turnos','func_turnos.php?pesquisa_chave='+document.form1.ed05_i_turno.value+'&funcao_js=parent.js_mostraturnos','Pesquisa',false);
     }else{
       document.form1.ed10_c_nome.value = ''; 
     }
  }
}
function js_mostraturnos(chave,erro){
  document.form1.ed10_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.ed05_i_turno.focus(); 
    document.form1.ed05_i_turno.value = ''; 
  }
}
function js_mostraturnos1(chave1,chave2){
  document.form1.ed05_i_turno.value = chave1;
  document.form1.ed10_c_nome.value = chave2;
  db_iframe_turnos.hide();
}
function js_pesquisaed05_i_serie(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_series','func_series.php?funcao_js=parent.js_mostraseries1|ed03_i_codigo|ed03_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed05_i_serie.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_series','func_series.php?pesquisa_chave='+document.form1.ed05_i_serie.value+'&funcao_js=parent.js_mostraseries','Pesquisa',false);
     }else{
       document.form1.ed03_c_nome.value = ''; 
     }
  }
}
function js_mostraseries(chave,erro){
  document.form1.ed03_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.ed05_i_serie.focus(); 
    document.form1.ed05_i_serie.value = ''; 
  }
}
function js_mostraseries1(chave1,chave2){
  document.form1.ed05_i_serie.value = chave1;
  document.form1.ed03_c_nome.value = chave2;
  db_iframe_series.hide();
}
function js_pesquisaed05_i_escola(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_escolas','func_escolas.php?funcao_js=parent.js_mostraescolas1|ed02_i_codigo|ed02_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed05_i_escola.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_escolas','func_escolas.php?pesquisa_chave='+document.form1.ed05_i_escola.value+'&funcao_js=parent.js_mostraescolas','Pesquisa',false);
     }else{
       document.form1.ed02_i_codigo.value = ''; 
     }
  }
}
function js_mostraescolas(chave,erro){
  document.form1.ed02_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed05_i_escola.focus(); 
    document.form1.ed05_i_escola.value = ''; 
  }
}
function js_mostraescolas1(chave1,chave2){
  document.form1.ed05_i_escola.value = chave1;
  document.form1.ed02_i_codigo.value = chave2;
  db_iframe_escolas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_turmas','func_turmas.php?funcao_js=parent.js_preenchepesquisa|ed05_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_turmas.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>