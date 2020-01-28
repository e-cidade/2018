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

//MODULO: patrim
$clapolice->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("t80_contato");
      if($db_opcao==1){
 	   $db_action="pat1_apolice004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="pat1_apolice005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="pat1_apolice006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tt81_codapo?>">
       <?=@$Lt81_codapo?>
    </td>
    <td> 
<?
$t81_instit = db_getsession("DB_instit");
db_input("t81_instit",10,$It81_instit,true,"hidden",3,"");
db_input('t81_codapo',8,$It81_codapo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt81_codseg?>">
       <?
       db_ancora(@$Lt81_codseg,"js_pesquisat81_codseg(true);",$db_opcao);
       ?>       
    </td>
    <td> 
<?
db_input('t81_codseg',8,$It81_codseg,true,'text',$db_opcao," onchange='js_pesquisat81_codseg(false);'")
?>
<?
db_input('t80_contato',40,$It80_contato,true,'text',3,'')
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt81_apolice?>">
       <?=@$Lt81_apolice?>
    </td>
    <td> 
<?
db_input('t81_apolice',51,$It81_apolice,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt81_venc?>">
       <?=@$Lt81_venc?>
    </td>
    <td> 
<?
db_inputdata('t81_venc',@$t81_venc_dia,@$t81_venc_mes,@$t81_venc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisat81_codseg(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_apolice','db_iframe_seguradoras','func_seguradoras.php?funcao_js=parent.js_mostraseguradoras1|t80_segura|t80_contato','Pesquisa',true);
  }else{
     if(document.form1.t81_codseg.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_apolice','db_iframe_seguradoras','func_seguradoras.php?pesquisa_chave='+document.form1.t81_codseg.value+'&funcao_js=parent.js_mostraseguradoras','Pesquisa',false);
     }else{
       document.form1.t80_contato.value = ''; 
     }
  }
}
function js_mostraseguradoras(chave,erro){
  document.form1.t80_contato.value = chave; 
  if(erro==true){ 
    document.form1.t81_codseg.focus(); 
    document.form1.t81_codseg.value = ''; 
  }
}
function js_mostraseguradoras1(chave1,chave2){
  document.form1.t81_codseg.value = chave1;
  document.form1.t80_contato.value = chave2;
  db_iframe_seguradoras.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_apolice','db_iframe_apolice','func_apolice.php?funcao_js=parent.js_preenchepesquisa|t81_codapo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_apolice.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>