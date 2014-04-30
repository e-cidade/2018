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

//MODULO: agua
$claguacortemat->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x01_numcgm");
$clrotulo->label("x40_dtinc");
$clrotulo->label("x01_codrua");
$clrotulo->label("j14_nome");
$clrotulo->label("x01_codbairro");
$clrotulo->label("j13_descr");
$clrotulo->label("x01_numero");

      if($db_opcao==1){
 	   $db_action="agu1_aguacortemat004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="agu1_aguacortemat005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="agu1_aguacortemat006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx41_codcortemat?>">
       <?=@$Lx41_codcortemat?>
    </td>
    <td> 
<?
db_input('x41_codcortemat',5,$Ix41_codcortemat,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx41_matric?>">
       <?
       db_ancora(@$Lx41_matric,"js_pesquisax41_matric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x41_matric',10,$Ix41_matric,true,'text',$db_opcao," onchange='js_pesquisax41_matric(false);'")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tx01_codrua?>">
       <?=@$Lx01_codrua?>
    </td>
    <td> 
<?
db_input('x01_codrua',10,$Ix01_codrua,true,'text',3," onchange='js_pesquisax01_codrua(false);'")
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tx01_codbairro?>">
       <?=@$Lx01_codbairro?>
    </td>
    <td> 
<?
db_input('x01_codbairro',10,$Ix01_codbairro,true,'text',3," onchange='js_pesquisax01_codbairro(false);'")
?>
       <?
db_input('j13_descr',40,$Ij13_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tx01_numero?>">
       <?=@$Lx01_numero?>
    </td>
    <td> 
<?
db_input('x01_numero',10,$Ix01_numero,true,'text',3,'')
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tx41_codcorte?>">
       <?
       db_ancora(@$Lx41_codcorte,"js_pesquisax41_codcorte(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x41_codcorte',10,$Ix41_codcorte,true,'text',$db_opcao," onchange='js_pesquisax41_codcorte(false);'")
?>
       <?
db_input('x40_dtinc',10,$Ix40_dtinc,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx41_dtprazo?>">
       <?=@$Lx41_dtprazo?>
    </td>
    <td> 
<?
db_inputdata('x41_dtprazo',@$x41_dtprazo_dia,@$x41_dtprazo_mes,@$x41_dtprazo_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisax41_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_aguabase_corte','func_aguabase_corte.php?funcao_js=parent.js_mostraaguabase1|x01_matric|x01_numcgm|x01_codrua|x01_codbairro|x01_numero','Pesquisa',true,20);
  }else{
     if(document.form1.x41_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_aguabase_corte','func_aguabase_corte.php?pesquisa_chave='+document.form1.x41_matric.value+'&funcao_js=parent.js_mostraaguabase','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.x01_numcgm.value = ''; 
     }
  }
}
function js_mostraaguabase(chave,codrua,codbairro,numero,erro){
  //document.form1.x01_numcgm.value = chave; 
  document.form1.x01_codrua.value = codrua;
  js_pesquisax01_codrua(false);
  document.form1.x01_codbairro.value = codbairro;
  js_pesquisax01_codbairro(false);
  document.form1.x01_numero.value = numero;

  if(erro==true){ 
    document.form1.x41_matric.focus(); 
    document.form1.x41_matric.value = ''; 
    document.form1.x01_codrua.value = '';
    document.form1.x01_codbairro.value = '';
    document.form1.x01_numero.value = '';
  }
}
function js_mostraaguabase1(matric,cgm,codrua,codbairro,numero){
  document.form1.x41_matric.value = matric;
  document.form1.x01_numcgm.value = cgm;
  document.form1.x01_codrua.value = codrua;
  document.form1.x01_codbairro.value = codbairro;
  document.form1.x01_numero.value = numero;
  db_iframe_aguabase_corte.hide();
}
function js_pesquisax41_codcorte(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_aguacorte','func_aguacorte.php?funcao_js=parent.js_mostraaguacorte1|x40_codcorte|x40_dtinc','Pesquisa',true,20);
  }else{
     if(document.form1.x41_codcorte.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_aguacorte','func_aguacorte.php?pesquisa_chave='+document.form1.x41_codcorte.value+'&funcao_js=parent.js_mostraaguacorte','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.x40_dtinc.value = ''; 
     }
  }
}
function js_mostraaguacorte(chave,erro){
  document.form1.x40_dtinc.value = chave; 
  if(erro==true){ 
    document.form1.x41_codcorte.focus(); 
    document.form1.x41_codcorte.value = ''; 
  }
}
function js_mostraaguacorte1(chave1,chave2){
  document.form1.x41_codcorte.value = chave1;
  document.form1.x40_dtinc.value = chave2;
  db_iframe_aguacorte.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_aguacortemat','func_aguacortemat.php?funcao_js=parent.js_preenchepesquisa|x41_codcortemat','Pesquisa',true,20);
}
function js_preenchepesquisa(chave){
  db_iframe_aguacortemat.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}


function js_pesquisax01_codrua(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x01_codrua.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.x01_codrua.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.j14_nome.value = ''; 
     }
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.x01_codrua.focus(); 
    document.form1.x01_codrua.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.x01_codrua.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}

function js_pesquisax01_codbairro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr','Pesquisa',true,20);
  }else{
     if(document.form1.x01_codbairro.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacortemat','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.x01_codbairro.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.j13_descr.value = ''; 
     }
  }
}
function js_mostrabairro(chave,erro){
  document.form1.j13_descr.value = chave; 
  if(erro==true){ 
    document.form1.x01_codbairro.focus(); 
    document.form1.x01_codbairro.value = ''; 
  }
}
function js_mostrabairro1(chave1,chave2){
  document.form1.x01_codbairro.value = chave1;
  document.form1.j13_descr.value = chave2;
  db_iframe_bairro.hide();
}

</script>