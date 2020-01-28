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

//MODULO: dividaativa
$cltipoparc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("i01_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ttipoparc?>">
       <?=@$Ltipoparc?>
    </td>
    <td> 
<?
db_input('tipoparc',4,$Itipoparc,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdescr?>">
       <?=@$Ldescr?>
    </td>
    <td> 
<?
db_input('descr',40,$Idescr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdtini?>">
       <?=@$Ldtini?>
    </td>
    <td> 
<?
db_inputdata('dtini',@$dtini_dia,@$dtini_mes,@$dtini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdtfim?>">
       <?=@$Ldtfim?>
    </td>
    <td> 
<?
db_inputdata('dtfim',@$dtfim_dia,@$dtfim_mes,@$dtfim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tmaxparc?>">
       <?=@$Lmaxparc?>
    </td>
    <td> 
<?
db_input('maxparc',4,$Imaxparc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvlrmin?>">
       <?=@$Lvlrmin?>
    </td>
    <td> 
<?
db_input('vlrmin',15,$Ivlrmin,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdtvlr?>">
       <?=@$Ldtvlr?>
    </td>
    <td> 
<?
db_inputdata('dtvlr',@$dtvlr_dia,@$dtvlr_mes,@$dtvlr_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tinflat?>">
       <?
       db_ancora(@$Linflat,"js_pesquisainflat(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('inflat',5,$Iinflat,true,'text',$db_opcao," onchange='js_pesquisainflat(false);'")
?>
       <?
db_input('i01_descr',40,$Ii01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdescmul?>">
       <?=@$Ldescmul?>
    </td>
    <td> 
<?
db_input('descmul',15,$Idescmul,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdescjur?>">
       <?=@$Ldescjur?>
    </td>
    <td> 
<?
db_input('descjur',15,$Idescjur,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisainflat(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_inflan','func_inflan.php?funcao_js=parent.js_mostrainflan1|i01_codigo|i01_descr','Pesquisa',true);
  }else{
     if(document.form1.inflat.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_inflan','func_inflan.php?pesquisa_chave='+document.form1.inflat.value+'&funcao_js=parent.js_mostrainflan','Pesquisa',false);
     }else{
       document.form1.i01_descr.value = ''; 
     }
  }
}
function js_mostrainflan(chave,erro){
  document.form1.i01_descr.value = chave; 
  if(erro==true){ 
    document.form1.inflat.focus(); 
    document.form1.inflat.value = ''; 
  }
}
function js_mostrainflan1(chave1,chave2){
  document.form1.inflat.value = chave1;
  document.form1.i01_descr.value = chave2;
  db_iframe_inflan.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tipoparc','func_tipoparc.php?funcao_js=parent.js_preenchepesquisa|tipoparc','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tipoparc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>