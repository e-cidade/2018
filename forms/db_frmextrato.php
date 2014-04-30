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

//MODULO: caixa
$clextrato->rotulo->label();
      if($db_opcao==1){
 	   $db_action="cai1_extrato004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="cai1_extrato005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="cai1_extrato006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk85_sequencial?>">
       <?=@$Lk85_sequencial?>
    </td>
    <td> 
<?
db_input('k85_sequencial',10,$Ik85_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk85_codbco?>">
       <?=@$Lk85_codbco?>
    </td>
    <td> 
<?
db_input('k85_codbco',3,$Ik85_codbco,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk85_dtproc?>">
       <?=@$Lk85_dtproc?>
    </td>
    <td> 
<?
db_inputdata('k85_dtproc',@$k85_dtproc_dia,@$k85_dtproc_mes,@$k85_dtproc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk85_dtarq?>">
       <?=@$Lk85_dtarq?>
    </td>
    <td> 
<?
db_inputdata('k85_dtarq',@$k85_dtarq_dia,@$k85_dtarq_mes,@$k85_dtarq_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk85_convenio?>">
       <?=@$Lk85_convenio?>
    </td>
    <td> 
<?
db_input('k85_convenio',20,$Ik85_convenio,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk85_seqarq?>">
       <?=@$Lk85_seqarq?>
    </td>
    <td> 
<?
db_input('k85_seqarq',10,$Ik85_seqarq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk85_nomearq?>">
       <?=@$Lk85_nomearq?>
    </td>
    <td> 
<?
db_input('k85_nomearq',255,$Ik85_nomearq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk85_tipoinclusao?>">
       <?=@$Lk85_tipoinclusao?>
    </td>
    <td> 
<?
$x = array('1'=>'Automatica','2'=>'Manual');
db_select('k85_tipoinclusao',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk85_conteudo?>">
       <?=@$Lk85_conteudo?>
    </td>
    <td> 
<?
db_textarea('k85_conteudo',0,0,$Ik85_conteudo,true,'text',$db_opcao,"")
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
  js_OpenJanelaIframe('top.corpo.iframe_extrato','db_iframe_extrato','func_extrato.php?funcao_js=parent.js_preenchepesquisa|k85_sequencial','Pesquisa',true,'0','1','775','390');
}
function js_preenchepesquisa(chave){
  db_iframe_extrato.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>