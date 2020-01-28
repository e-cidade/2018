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

//MODULO: atendimento
$clclientes->rotulo->label();
      if($db_opcao==1){
 	   $db_action="ate1_clientes004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="ate1_clientes005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="ate1_clientes006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat01_codcli?>">
       <?=@$Lat01_codcli?>
    </td>
    <td> 
<?
db_input('at01_codcli',4,$Iat01_codcli,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat01_nomecli?>">
       <?=@$Lat01_nomecli?>
    </td>
    <td> 
<?
db_input('at01_nomecli',40,$Iat01_nomecli,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat01_site?>">
       <?=@$Lat01_site?>
    </td>
    <td> 
<?
db_input('at01_site',40,$Iat01_site,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat01_status?>">
       <?=@$Lat01_status?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('at01_status',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat01_cidade?>">
       <?=@$Lat01_cidade?>
    </td>
    <td> 
<?
db_input('at01_cidade',40,$Iat01_cidade,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat01_ender?>">
       <?=@$Lat01_ender?>
    </td>
    <td> 
<?
db_input('at01_ender',40,$Iat01_ender,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat01_cep?>">
       <?=@$Lat01_cep?>
    </td>
    <td> 
<?
db_input('at01_cep',10,$Iat01_cep,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat01_codver?>">
       <?=@$Lat01_codver?>
    </td>
    <td> 
<?
  
  $sql = " select db30_codver , '2.'||db30_codversao ||'.'|| db30_codrelease as versao from db_versao order by db30_codver";
  db_selectrecord("at01_codver",pg_exec($sql),true,2);

//db_input('at01_codver',6,$Iat01_codver,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat01_sigla?>">
       <?=@$Lat01_sigla?>
    </td>
    <td> 
<?
db_input('at01_sigla',10,$Iat01_sigla,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat01_ativo?>">
       <?=@$Lat01_ativo?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('at01_ativo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat01_base?>">
       <?=@$Lat01_base?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('at01_base',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat01_obs?>">
       <?=@$Lat01_obs?>
    </td>
    <td> 
<?
db_textarea('at01_obs',4,70,$Iat01_obs,true,'text',$db_opcao,"")
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
  js_OpenJanelaIframe('top.corpo.iframe_clientes','db_iframe_clientes','func_clientes.php?funcao_js=parent.js_preenchepesquisa|at01_codcli','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_clientes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>