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
$clmoblevantamento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j95_pda");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj97_sequen?>">
       <?=@$Lj97_sequen?>
    </td>
    <td> 
<?
db_input('j97_sequen',8,$Ij97_sequen,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj97_codimporta?>">
       <?
       db_ancora(@$Lj97_codimporta,"js_pesquisaj97_codimporta(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j97_codimporta',8,$Ij97_codimporta,true,'text',$db_opcao," onchange='js_pesquisaj97_codimporta(false);'")
?>
       <?
db_input('j95_pda',3,$Ij95_pda,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj97_matric?>">
       <?=@$Lj97_matric?>
    </td>
    <td> 
<?
db_input('j97_matric',8,$Ij97_matric,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj97_endcor?>">
       <?=@$Lj97_endcor?>
    </td>
    <td> 
<?
db_input('j97_endcor',50,$Ij97_endcor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj97_cidade?>">
       <?=@$Lj97_cidade?>
    </td>
    <td> 
<?
db_input('j97_cidade',50,$Ij97_cidade,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj97_profun?>">
       <?=@$Lj97_profun?>
    </td>
    <td> 
<?
db_input('j97_profun',15,$Ij97_profun,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj97_sitterreno?>">
       <?=@$Lj97_sitterreno?>
    </td>
    <td> 
<?
$x = array('4'=>'Esquina','5'=>'Meio de Quadra','6'=>'Vila/Servidão/Encravado');
db_select('j97_sitterreno',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj97_pedol?>">
       <?=@$Lj97_pedol?>
    </td>
    <td> 
<?
$x = array('7'=>'Alagado','8'=>'Inundável','9'=>'Normal');
db_select('j97_pedol',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj97_topog?>">
       <?=@$Lj97_topog?>
    </td>
    <td> 
<?
$x = array('10'=>'Em Nível','11'=>'Acima do Nível','12'=>'Abaixo do Nível','13'=>'Topografia Irregular');
db_select('j97_topog',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj97_vistoria?>">
       <?=@$Lj97_vistoria?>
    </td>
    <td> 
<?
$x = array('1'=>'Vistoriado','2'=>'Não Autorizado','3'=>'Fechado');
db_select('j97_vistoria',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj97_muro?>">
       <?=@$Lj97_muro?>
    </td>
    <td> 
<?
db_input('j97_muro',3,$Ij97_muro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj97_calcada?>">
       <?=@$Lj97_calcada?>
    </td>
    <td> 
<?
db_input('j97_calcada',3,$Ij97_calcada,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj97_codimporta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_mobimportacao','func_mobimportacao.php?funcao_js=parent.js_mostramobimportacao1|j95_codimporta|j95_pda','Pesquisa',true);
  }else{
     if(document.form1.j97_codimporta.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_mobimportacao','func_mobimportacao.php?pesquisa_chave='+document.form1.j97_codimporta.value+'&funcao_js=parent.js_mostramobimportacao','Pesquisa',false);
     }else{
       document.form1.j95_pda.value = ''; 
     }
  }
}
function js_mostramobimportacao(chave,erro){
  document.form1.j95_pda.value = chave; 
  if(erro==true){ 
    document.form1.j97_codimporta.focus(); 
    document.form1.j97_codimporta.value = ''; 
  }
}
function js_mostramobimportacao1(chave1,chave2){
  document.form1.j97_codimporta.value = chave1;
  document.form1.j95_pda.value = chave2;
  db_iframe_mobimportacao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_moblevantamento','func_moblevantamento.php?funcao_js=parent.js_preenchepesquisa|j97_sequen','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_moblevantamento.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>