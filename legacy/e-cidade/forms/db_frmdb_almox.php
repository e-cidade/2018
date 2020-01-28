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

//MODULO: material
$cldb_almox->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
      if($db_opcao == 1){
 	   $db_action="mat1_db_almox004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="mat1_db_almox005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="mat1_db_almox006.php";
      }  
?>

<form name="form1" method="post" action="<?=$db_action?>">
<center>
<fieldset>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tm91_codigo?>">
       <?=@$Lm91_codigo?>
    </td>
    <td> 
     <?
       db_input('m91_codigo',6,$Im91_codigo,true,'text',3,"")
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm91_depto?>">
       <?
       
       // na alteração desabilita a âncora
       if (isset($db_opcao) and $db_opcao == 22 or $db_opcao == 2) {
         echo "<b>Depart.:</b>"; 
       } else {
         db_ancora(@$Lm91_depto,"js_pesquisam91_depto(true);",$db_opcao);
       }
       ?>
    </td>
    <td> 
       <?
         db_input('m91_depto',5,$Im91_depto,true,'text',$db_opcao," onchange='js_pesquisam91_depto(false);'");
         db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
       ?>
    </td>
  </tr>
  </table>
  </center>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisam91_depto(mostra){
  if(mostra==true){
   //top.corpo.iframe_db_almox
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true,4);
  }else{
     if(document.form1.m91_depto.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.m91_depto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false,4);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.m91_depto.focus(); 
    document.form1.m91_depto.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.m91_depto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_db_almox','func_db_almox.php?funcao_js=parent.js_preenchepesquisa|m91_codigo','Pesquisa',true, 4);
}
function js_preenchepesquisa(chave){
  db_iframe_db_almox.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>