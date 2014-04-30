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

//MODULO: licitação
$clliccomissao->rotulo->label();
      if($db_opcao==1){
 	   $db_action="lic1_liccomissao004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="lic1_liccomissao005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="lic1_liccomissao006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tl30_codigo?>">
       <?=@$Ll30_codigo?>
    </td>
    <td> 
<?
db_input('l30_codigo',10,$Il30_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl30_data?>">
       <?=@$Ll30_data?>
    </td>
    <td> 
<?
db_inputdata('l30_data',@$l30_data_dia,@$l30_data_mes,@$l30_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl30_portaria?>">
       <?=@$Ll30_portaria?>
    </td>
    <td> 
<?
db_input('l30_portaria',20,$Il30_portaria,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl30_datavalid?>">
       <?=@$Ll30_datavalid?>
    </td>
    <td> 
<?
db_inputdata('l30_datavalid',@$l30_datavalid_dia,@$l30_datavalid_mes,@$l30_datavalid_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl30_tipo?>">
       <?=@$Ll30_tipo?>
    </td>
    <td> 
<?
$x = array('1'=>'Permanente','2'=>'Especial');
db_select('l30_tipo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_liccomissao','db_iframe_liccomissao','func_liccomissao.php?funcao_js=parent.js_preenchepesquisa|l30_codigo','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
  db_iframe_liccomissao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>