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

//MODULO: saude
$clprontproced->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd27_i_codigo");
$clrotulo->label("sd09_c_descr");
$clrotulo->label("sd20_i_codigo");
$clrotulo->label("nome");
$clrotulo->label("sd24_i_codigo");
$clrotulo->label("sd16_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd29_i_codigo?>">
       <?=@$Lsd29_i_codigo?>
    </td>
    <td> 
<?
db_input('sd29_i_codigo',10,$Isd29_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd29_i_prontuario?>">
       <?
       db_ancora(@$Lsd29_i_prontuario,"js_pesquisasd29_i_prontuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd29_i_prontuario',10,$Isd29_i_prontuario,true,'text',$db_opcao," onchange='js_pesquisasd29_i_prontuario(false);'")
?>
       <?
db_input('sd24_i_codigo',10,$Isd24_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd29_i_procedimento?>">
       <?
       db_ancora(@$Lsd29_i_procedimento,"js_pesquisasd29_i_procedimento(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd29_i_procedimento',10,$Isd29_i_procedimento,true,'text',$db_opcao," onchange='js_pesquisasd29_i_procedimento(false);'")
?>
       <?
db_input('sd09_c_descr',100,$Isd09_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd29_i_especmed?>">
       <?
       db_ancora(@$Lsd29_i_especmed,"js_pesquisasd29_i_especmed(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd29_i_especmed',10,$Isd29_i_especmed,true,'text',$db_opcao," onchange='js_pesquisasd29_i_especmed(false);'")
?>
       <?
db_input('sd27_i_codigo',5,$Isd27_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd29_i_proctipoatend?>">
       <?
       db_ancora(@$Lsd29_i_proctipoatend,"js_pesquisasd29_i_proctipoatend(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd29_i_proctipoatend',10,$Isd29_i_proctipoatend,true,'text',$db_opcao," onchange='js_pesquisasd29_i_proctipoatend(false);'")
?>
       <?
db_input('sd20_i_codigo',10,$Isd20_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd29_i_procafaixaetaria?>">
       <?
       db_ancora(@$Lsd29_i_procafaixaetaria,"js_pesquisasd29_i_procafaixaetaria(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd29_i_procafaixaetaria',10,$Isd29_i_procafaixaetaria,true,'text',$db_opcao," onchange='js_pesquisasd29_i_procafaixaetaria(false);'")
?>
       <?
db_input('sd16_i_codigo',10,$Isd16_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd29_d_data?>">
       <?=@$Lsd29_d_data?>
    </td>
    <td> 
<?
db_inputdata('sd29_d_data',@$sd29_d_data_dia,@$sd29_d_data_mes,@$sd29_d_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd29_c_hora?>">
       <?=@$Lsd29_c_hora?>
    </td>
    <td> 
<?
db_input('sd29_c_hora',5,$Isd29_c_hora,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd29_t_tratamento?>">
       <?=@$Lsd29_t_tratamento?>
    </td>
    <td> 
<?
db_textarea('sd29_t_tratamento',0,0,$Isd29_t_tratamento,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd29_i_usuario?>">
       <?
       db_ancora(@$Lsd29_i_usuario,"js_pesquisasd29_i_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd29_i_usuario',10,$Isd29_i_usuario,true,'text',$db_opcao," onchange='js_pesquisasd29_i_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd29_d_cadastro?>">
       <?=@$Lsd29_d_cadastro?>
    </td>
    <td> 
<?
db_inputdata('sd29_d_cadastro',@$sd29_d_cadastro_dia,@$sd29_d_cadastro_mes,@$sd29_d_cadastro_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd29_c_cadastro?>">
       <?=@$Lsd29_c_cadastro?>
    </td>
    <td> 
<?
db_input('sd29_c_cadastro',20,$Isd29_c_cadastro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd29_i_especmed(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_especmedico','func_especmedico.php?funcao_js=parent.js_mostraespecmedico1|sd27_i_codigo|sd27_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd29_i_especmed.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_especmedico','func_especmedico.php?pesquisa_chave='+document.form1.sd29_i_especmed.value+'&funcao_js=parent.js_mostraespecmedico','Pesquisa',false);
     }else{
       document.form1.sd27_i_codigo.value = ''; 
     }
  }
}
function js_mostraespecmedico(chave,erro){
  document.form1.sd27_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd29_i_especmed.focus(); 
    document.form1.sd29_i_especmed.value = ''; 
  }
}
function js_mostraespecmedico1(chave1,chave2){
  document.form1.sd29_i_especmed.value = chave1;
  document.form1.sd27_i_codigo.value = chave2;
  db_iframe_especmedico.hide();
}
function js_pesquisasd29_i_procedimento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procedimentos','func_procedimentos.php?funcao_js=parent.js_mostraprocedimentos1|sd09_i_codigo|sd09_c_descr','Pesquisa',true);
  }else{
     if(document.form1.sd29_i_procedimento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procedimentos','func_procedimentos.php?pesquisa_chave='+document.form1.sd29_i_procedimento.value+'&funcao_js=parent.js_mostraprocedimentos','Pesquisa',false);
     }else{
       document.form1.sd09_c_descr.value = ''; 
     }
  }
}
function js_mostraprocedimentos(chave,erro){
  document.form1.sd09_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.sd29_i_procedimento.focus(); 
    document.form1.sd29_i_procedimento.value = ''; 
  }
}
function js_mostraprocedimentos1(chave1,chave2){
  document.form1.sd29_i_procedimento.value = chave1;
  document.form1.sd09_c_descr.value = chave2;
  db_iframe_procedimentos.hide();
}
function js_pesquisasd29_i_proctipoatend(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_proctipoatend','func_proctipoatend.php?funcao_js=parent.js_mostraproctipoatend1|sd20_i_codigo|sd20_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd29_i_proctipoatend.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_proctipoatend','func_proctipoatend.php?pesquisa_chave='+document.form1.sd29_i_proctipoatend.value+'&funcao_js=parent.js_mostraproctipoatend','Pesquisa',false);
     }else{
       document.form1.sd20_i_codigo.value = ''; 
     }
  }
}
function js_mostraproctipoatend(chave,erro){
  document.form1.sd20_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd29_i_proctipoatend.focus(); 
    document.form1.sd29_i_proctipoatend.value = ''; 
  }
}
function js_mostraproctipoatend1(chave1,chave2){
  document.form1.sd29_i_proctipoatend.value = chave1;
  document.form1.sd20_i_codigo.value = chave2;
  db_iframe_proctipoatend.hide();
}
function js_pesquisasd29_i_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.sd29_i_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.sd29_i_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.sd29_i_usuario.focus(); 
    document.form1.sd29_i_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.sd29_i_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisasd29_i_prontuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_prontuarios','func_prontuarios.php?funcao_js=parent.js_mostraprontuarios1|sd24_i_codigo|sd24_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd29_i_prontuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_prontuarios','func_prontuarios.php?pesquisa_chave='+document.form1.sd29_i_prontuario.value+'&funcao_js=parent.js_mostraprontuarios','Pesquisa',false);
     }else{
       document.form1.sd24_i_codigo.value = ''; 
     }
  }
}
function js_mostraprontuarios(chave,erro){
  document.form1.sd24_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd29_i_prontuario.focus(); 
    document.form1.sd29_i_prontuario.value = ''; 
  }
}
function js_mostraprontuarios1(chave1,chave2){
  document.form1.sd29_i_prontuario.value = chave1;
  document.form1.sd24_i_codigo.value = chave2;
  db_iframe_prontuarios.hide();
}
function js_pesquisasd29_i_procafaixaetaria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procfaixaetaria','func_procfaixaetaria.php?funcao_js=parent.js_mostraprocfaixaetaria1|sd16_i_codigo|sd16_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd29_i_procafaixaetaria.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procfaixaetaria','func_procfaixaetaria.php?pesquisa_chave='+document.form1.sd29_i_procafaixaetaria.value+'&funcao_js=parent.js_mostraprocfaixaetaria','Pesquisa',false);
     }else{
       document.form1.sd16_i_codigo.value = ''; 
     }
  }
}
function js_mostraprocfaixaetaria(chave,erro){
  document.form1.sd16_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd29_i_procafaixaetaria.focus(); 
    document.form1.sd29_i_procafaixaetaria.value = ''; 
  }
}
function js_mostraprocfaixaetaria1(chave1,chave2){
  document.form1.sd29_i_procafaixaetaria.value = chave1;
  document.form1.sd16_i_codigo.value = chave2;
  db_iframe_procfaixaetaria.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_prontproced','func_prontproced.php?funcao_js=parent.js_preenchepesquisa|sd29_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_prontproced.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>