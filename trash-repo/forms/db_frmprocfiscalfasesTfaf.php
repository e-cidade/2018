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

//MODULO: fiscal
$clprocfiscalfases->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y100_coddepto");
$clrotulo->label("z01_nome");
      if($db_opcao==1){
 	   $db_action="fis1_procfiscalfasesTfaf004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="fis1_procfiscalfasesTfaf005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="fis1_procfiscalfasesTfaf006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap >
       <b>TFAF</b>
    </td>
    <td> 
<?
db_input('y108_sequencial',10,$Iy108_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty108_procfiscal?>">
       <?
       db_ancora(@$Ly108_procfiscal,"js_pesquisay108_procfiscal(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y108_procfiscal',10,$Iy108_procfiscal,true,'text',$db_opcao," onchange='js_pesquisay108_procfiscal(false);'")
?>
       <?
db_input('nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty108_dtcriacao?>">
       <?=@$Ly108_dtcriacao?>
    </td>
    <td> 
<?
db_inputdata('y108_dtcriacao',@$y108_dtcriacao_dia,@$y108_dtcriacao_mes,@$y108_dtcriacao_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty108_dtassinatura?>">
       <?=@$Ly108_dtassinatura?>
    </td>
    <td> 
<?
db_inputdata('y108_dtassinatura',@$y108_dtassinatura_dia,@$y108_dtassinatura_mes,@$y108_dtassinatura_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty108_responsavel?>">
       <?
       db_ancora(@$Ly108_responsavel,"js_pesquisay108_responsavel(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y108_responsavel',10,$Iy108_responsavel,true,'text',$db_opcao," onchange='js_pesquisay108_responsavel(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay108_procfiscal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_procfiscalfases','db_iframe_procfiscal','func_procfiscal_tfaf.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.y108_procfiscal.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_procfiscalfases','db_iframe_procfiscal','func_procfiscal_tfaf.php?pesquisa_chave='+document.form1.y108_procfiscal.value+'&funcao_js=parent.js_mostraprocfiscal','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.y100_coddepto.value = ''; 
     }
  }
}
function js_mostraprocfiscal(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.y108_procfiscal.focus(); 
    document.form1.y108_procfiscal.value = ''; 
  }
}
function js_mostraprocfiscal1(chave1,chave2){
  document.form1.y108_procfiscal.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_procfiscal.hide();
}
function js_pesquisay108_responsavel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_procfiscalfases','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.y108_responsavel.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_procfiscalfases','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.y108_responsavel.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.y108_responsavel.focus(); 
    document.form1.y108_responsavel.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.y108_responsavel.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_procfiscalfases','db_iframe_procfiscalfases','func_procfiscalfasesTfaf.php?funcao_js=parent.js_preenchepesquisa|y108_sequencial','Pesquisa',true,'0','1','775','390');
}
function js_preenchepesquisa(chave){
  db_iframe_procfiscalfases.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>