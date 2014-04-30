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

//MODULO: contabilidade
$clcontcearquivorespcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c12_nome");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc13_sequencial?>">
       <?=@$Lc13_sequencial?>
    </td>
    <td> 
<?
db_input('c13_sequencial',10,$Ic13_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc13_contcearquivoresp?>">
       <?
       db_ancora(@$Lc13_contcearquivoresp,"js_pesquisac13_contcearquivoresp(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c13_contcearquivoresp',10,$Ic13_contcearquivoresp,true,'text',$db_opcao," onchange='js_pesquisac13_contcearquivoresp(false);'")
?>
       <?
db_input('c12_nome',30,$Ic12_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc13_numcgm?>">
       <?
       db_ancora(@$Lc13_numcgm,"js_pesquisac13_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c13_numcgm',10,$Ic13_numcgm,true,'text',$db_opcao," onchange='js_pesquisac13_numcgm(false);'")
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
function js_pesquisac13_contcearquivoresp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_contcearquivoresp','func_contcearquivoresp.php?funcao_js=parent.js_mostracontcearquivoresp1|c12_sequencial|c12_nome','Pesquisa',true);
  }else{
     if(document.form1.c13_contcearquivoresp.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_contcearquivoresp','func_contcearquivoresp.php?pesquisa_chave='+document.form1.c13_contcearquivoresp.value+'&funcao_js=parent.js_mostracontcearquivoresp','Pesquisa',false);
     }else{
       document.form1.c12_nome.value = ''; 
     }
  }
}
function js_mostracontcearquivoresp(chave,erro){
  document.form1.c12_nome.value = chave; 
  if(erro==true){ 
    document.form1.c13_contcearquivoresp.focus(); 
    document.form1.c13_contcearquivoresp.value = ''; 
  }
}
function js_mostracontcearquivoresp1(chave1,chave2){
  document.form1.c13_contcearquivoresp.value = chave1;
  document.form1.c12_nome.value = chave2;
  db_iframe_contcearquivoresp.hide();
}
function js_pesquisac13_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.c13_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.c13_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.c13_numcgm.focus(); 
    document.form1.c13_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.c13_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_contcearquivorespcgm','func_contcearquivorespcgm.php?funcao_js=parent.js_preenchepesquisa|c13_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_contcearquivorespcgm.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>