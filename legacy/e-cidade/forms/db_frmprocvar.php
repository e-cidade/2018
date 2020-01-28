<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: protocolo
$clprocvar->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p51_descr");
$clrotulo->label("nomecam");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp54_codigo?>">
       <?
       db_ancora(@$Lp54_codigo,"js_pesquisap54_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p54_codigo',5,$Ip54_codigo,true,'text',$db_opcao," onchange='js_pesquisap54_codigo(false);'")
?>
       <?
db_input('p51_descr',60,$Ip51_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp54_codcam?>">
       <?
       db_ancora(@$Lp54_codcam,"js_pesquisap54_codcam(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p54_codcam',6,$Ip54_codcam,true,'text',$db_opcao," onchange='js_pesquisap54_codcam(false);'")
?>
       <?
db_input('nomecam',40,$Inomecam,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp54_obrigatorio?>">
       <?=@$Lp54_obrigatorio?>
    </td>
    <td> 
<?
$x = array('f'=>'Não','t'=>'Sim');
db_select('p54_obrigatorio',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisap54_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tipoproc','func_tipoproc.php?funcao_js=parent.js_mostratipoproc1|p51_codigo|p51_descr','Pesquisa',true);
  }else{
     if(document.form1.p54_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tipoproc','func_tipoproc.php?pesquisa_chave='+document.form1.p54_codigo.value+'&funcao_js=parent.js_mostratipoproc','Pesquisa',false);
     }else{
       document.form1.p51_descr.value = ''; 
     }
  }
}
function js_mostratipoproc(chave,erro){
  document.form1.p51_descr.value = chave; 
  if(erro==true){ 
    document.form1.p54_codigo.focus(); 
    document.form1.p54_codigo.value = ''; 
  }
}
function js_mostratipoproc1(chave1,chave2){
  document.form1.p54_codigo.value = chave1;
  document.form1.p51_descr.value = chave2;
  db_iframe_tipoproc.hide();
}
function js_pesquisap54_codcam(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_syscampo','func_db_syscampo.php?funcao_js=parent.js_mostradb_syscampo1|codcam|nomecam','Pesquisa',true);
  }else{
     if(document.form1.p54_codcam.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_syscampo','func_db_syscampo.php?pesquisa_chave='+document.form1.p54_codcam.value+'&funcao_js=parent.js_mostradb_syscampo','Pesquisa',false);
     }else{
       document.form1.nomecam.value = ''; 
     }
  }
}
function js_mostradb_syscampo(chave,erro){
  document.form1.nomecam.value = chave; 
  if(erro==true){ 
    document.form1.p54_codcam.focus(); 
    document.form1.p54_codcam.value = ''; 
  }
}
function js_mostradb_syscampo1(chave1,chave2){
  document.form1.p54_codcam.value = chave1;
  document.form1.nomecam.value = chave2;
  db_iframe_db_syscampo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_procvar','func_procvar.php?funcao_js=parent.js_preenchepesquisa|p54_codigo|p54_codcam','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_procvar.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>