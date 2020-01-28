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
$clprontobs->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd24_c_atendimento");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd30_i_prontuario?>">
       <?
       db_ancora(@$Lsd30_i_prontuario,"js_pesquisasd30_i_prontuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd30_i_prontuario',10,$Isd30_i_prontuario,true,'text',$db_opcao," onchange='js_pesquisasd30_i_prontuario(false);'")
?>
       <?
db_input('sd24_c_atendimento',11,$Isd24_c_atendimento,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd30_t_tratamento?>">
       <?=@$Lsd30_t_tratamento?>
    </td>
    <td> 
<?
db_textarea('sd30_t_tratamento',0,0,$Isd30_t_tratamento,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd30_i_prontuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_prontuarios','func_prontuarios.php?funcao_js=parent.js_mostraprontuarios1|sd24_i_id|sd24_c_atendimento','Pesquisa',true);
  }else{
     if(document.form1.sd30_i_prontuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_prontuarios','func_prontuarios.php?pesquisa_chave='+document.form1.sd30_i_prontuario.value+'&funcao_js=parent.js_mostraprontuarios','Pesquisa',false);
     }else{
       document.form1.sd24_c_atendimento.value = ''; 
     }
  }
}
function js_mostraprontuarios(chave,erro){
  document.form1.sd24_c_atendimento.value = chave; 
  if(erro==true){ 
    document.form1.sd30_i_prontuario.focus(); 
    document.form1.sd30_i_prontuario.value = ''; 
  }
}
function js_mostraprontuarios1(chave1,chave2){
  document.form1.sd30_i_prontuario.value = chave1;
  document.form1.sd24_c_atendimento.value = chave2;
  db_iframe_prontuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_prontobs','func_prontobs.php?funcao_js=parent.js_preenchepesquisa|sd30_i_prontuario','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_prontobs.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>