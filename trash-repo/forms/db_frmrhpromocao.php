<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: recursoshumanos
$clrhpromocao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th72_sequencial?>">
       <?=@$Lh72_sequencial?>
    </td>
    <td> 
<?
db_input('h72_sequencial',10,$Ih72_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th72_regist?>">
       <?
       db_ancora(@$Lh72_regist,"js_pesquisah72_regist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h72_regist',10,$Ih72_regist,true,'text',$db_opcao," onchange='js_pesquisah72_regist(false);'")
?>
       <?
db_input('rh01_numcgm',10,$Irh01_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th72_dtinicial?>">
       <?=@$Lh72_dtinicial?>
    </td>
    <td> 
<?
db_inputdata('h72_dtinicial',@$h72_dtinicial_dia,@$h72_dtinicial_mes,@$h72_dtinicial_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th72_dtfinal?>">
       <?=@$Lh72_dtfinal?>
    </td>
    <td> 
<?
db_inputdata('h72_dtfinal',@$h72_dtfinal_dia,@$h72_dtfinal_mes,@$h72_dtfinal_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th72_ativo?>">
       <?=@$Lh72_ativo?>
    </td>
    <td> 
<?
$x = array('0'=>'Ativo','1'=>'Inativo');
db_select('h72_ativo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th72_observacao?>">
       <?=@$Lh72_observacao?>
    </td>
    <td> 
<?
db_textarea('h72_observacao',0,0,$Ih72_observacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisah72_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|rh01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.h72_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.h72_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false);
     }else{
       document.form1.rh01_numcgm.value = ''; 
     }
  }
}
function js_mostrarhpessoal(chave,erro){
  document.form1.rh01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.h72_regist.focus(); 
    document.form1.h72_regist.value = ''; 
  }
}
function js_mostrarhpessoal1(chave1,chave2){
  document.form1.h72_regist.value = chave1;
  document.form1.rh01_numcgm.value = chave2;
  db_iframe_rhpessoal.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhpromocao','func_rhpromocao.php?funcao_js=parent.js_preenchepesquisa|h72_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhpromocao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>