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
$cliptucalcpadrao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j01_numcgm");
if($db_opcao==1){
  $db_action="cad1_iptucalcpadrao004.php";
}else if($db_opcao==2||$db_opcao==22){
  $db_action="cad1_iptucalcpadrao005.php";
}else if($db_opcao==3||$db_opcao==33){
  $db_action="cad1_iptucalcpadrao006.php";
}  
     
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  
  <tr>
    <td > </td>
    <td> 
<?
db_input('chavepesquisa',10,"",true,'hidden',3,"");
db_input('forma',10,"",true,'hidden',3,"");
db_input('j10_anousu',10,$Ij10_anousu,true,'hidden',3,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap >
        <b>Matricula:</b>
    </td>
    <td> 
<?
db_input('j10_matric',10,"",true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj10_vlrter?>">
       <b>Valor venal territorial:</b>
    </td>
    <td> 
<?
db_input('j10_vlrter',10,$Ij10_vlrter,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj10_aliq?>">
       <?=@$Lj10_aliq?>
    </td>
    <td> 
<?
db_input('j10_aliq',10,$Ij10_aliq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj10_perccorre?>">
       <?=@$Lj10_perccorre?>
    </td>
    <td> 
		<?
		db_input('j10_perccorre',10,$Ij10_perccorre,true,'text',3,"")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj10_perccorre?>">
       <b>Ano de origem:</b>
    </td>
    <td> 
		<?
		db_input('j23_anousu',10,"",true,'text',3,"")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj23_vlrter?>">
       <b>Valor de origem:</b>
    </td>
    <td> 
		<?
		db_input('j23_vlrter',10,"",true,'text',3,"")
		?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisaj10_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_iptucalcpadrao','db_iframe_iptubase','func_iptubasealtpadrao.php?funcao_js=parent.js_mostraiptubase1|j01_matric|j01_numcgm','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.j10_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_iptucalcpadrao','db_iframe_iptubase','func_iptubasealtpadrao.php?pesquisa_chave='+document.form1.j10_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.j01_numcgm.value = ''; 
     }
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.j10_matric.focus(); 
    document.form1.j10_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j10_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe_iptubase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_iptucalcpadrao','db_iframe_iptucalcpadrao','func_iptucalcpadrao.php?funcao_js=parent.js_preenchepesquisa|j10_sequencial','Pesquisa',true,'0','1','775','390');
}
function js_preenchepesquisa(chave){
  db_iframe_iptucalcpadrao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

</script>