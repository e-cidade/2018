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

//MODULO: ISSQN
$clcadescrito->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("q86_datalimite");
      if($db_opcao==1){
 	   $db_action="iss1_cadescrito004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="iss1_cadescrito005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="iss1_cadescrito006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>

<table align=center style="margin-top:15px;">
<tr>
<td>

<fieldset>
<legend><strong>Escritório Contábil</strong></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq86_numcgm?>">
       <? db_ancora(@$Lq86_numcgm,"js_pesquisaq86_numcgm(true);",($db_opcao==2)?3:$db_opcao); ?>
    </td>
    <td> 
			<?
			 db_input('q86_numcgm',10,$Iq86_numcgm,true,'text',($db_opcao==2)?3:$db_opcao," onchange='js_pesquisaq86_numcgm(false);'");
			 db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td>
      <?=$Lq86_datalimite?>
    </td>
    <td>
      <? db_inputdata('q86_datalimite', @$q86_datalimite_dia, @$q86_datalimite_mes, @$q86_datalimite_ano, true, 'text',$db_opcao); ?>
    </td>
  </tr>
</table>
  
</fieldset>

</td>
</tr>
</table>  

 </center>
  
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>
<script>
function js_pesquisaq86_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadescrito','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,'0','1');
  }else{       
     if(document.form1.q86_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_cadescrito','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.q86_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false,'0','1');
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.q86_numcgm.focus(); 
    document.form1.q86_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.q86_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_cadescrito','db_iframe_cadescrito','func_cadescrito.php?funcao_js=parent.js_preenchepesquisa|q86_numcgm','Pesquisa',true,'0','1');
}
function js_preenchepesquisa(chave){
  db_iframe_cadescrito.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>