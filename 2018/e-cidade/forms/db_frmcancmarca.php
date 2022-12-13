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

//MODULO: marcas
$clcancmarca->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p51_descr");
$clrotulo->label("ma01_i_cgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tma03_i_codigo?>">
       <?=@$Lma03_i_codigo?>
    </td>
    <td> 
          <?
          db_input('ma03_i_codigo',10,$Ima03_i_codigo,true,'text',3,"")
          ?>
     </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tma03_i_marca?>">
       <?
       db_ancora(@$Lma03_i_marca,"js_pesquisama03_i_marca(true);",$db_opcao);
       ?>
    </td>
    <td> 
          <?
          db_input('ma03_i_marca',10,$Ima03_i_marca,true,'text',$db_opcao," onchange='js_pesquisama03_i_marca(false);'");
          db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
          ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tma03_i_codproc?>">
       <?
       db_ancora(@$Lma03_i_codproc,"js_pesquisama03_i_codproc(true);",1);
       ?>
    </td>
    <td> 
<?
db_input('ma03_i_codproc',10,$Ima03_i_codproc,true,'text',1," onchange='js_pesquisama03_i_codproc(false);'")
?>
       <?
db_input('p51_descr',40,$Ip51_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tma03_d_data?>">
       <?=@$Lma03_d_data?>
    </td>
    <td> 
<?
db_inputdata('ma03_d_data',@$ma03_d_data_dia,@$ma03_d_data_mes,@$ma03_d_data_ano,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tma03_t_obs?>">
       <?=@$Lma03_t_obs?>
    </td>
    <td> 
<?
db_textarea('ma03_t_obs',5,50,$Ima03_t_obs,true,'text',1,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"reativar"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Reativar"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($db_opcao==1?"disabled":"")?>>
</form>
<script>
function js_pesquisama03_i_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ma03_i_codproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.ma03_i_codproc.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
     }else{
       document.form1.p51_descr.value = '';
     }
  }
}
function js_mostraprotprocesso(chave1,chave2,erro){

  document.form1.p51_descr.value = chave2;
  if(erro==true){
    document.form1.ma03_i_codproc.focus(); 
    document.form1.ma03_i_codproc.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.ma03_i_codproc.value = chave1;
  document.form1.p51_descr.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisama03_i_marca(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_marca','func_marca.php?funcao_js=parent.js_mostramarca1|ma01_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ma03_i_marca.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_marca','func_marca.php?pesquisa_chave='+document.form1.ma03_i_marca.value+'&funcao_js=parent.js_mostramarca','Pesquisa',false);
     }else{
       document.form1.ma01_i_cgm.value = ''; 
     }
  }
}
function js_mostramarca(chave1,chave2,chave3, erro){
  document.form1.ma03_i_marca.value = chave1;
  document.form1.z01_nome.value = chave3;
  if(erro==true){
    document.form1.ma03_i_marca.focus(); 
    document.form1.ma03_i_marca.value = '';
  }
}
function js_mostramarca1(chave1,chave2){
  document.form1.ma03_i_marca.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_marca.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cancmarca','func_cancmarca.php?funcao_js=parent.js_preenchepesquisa|ma03_i_marca','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cancmarca.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>