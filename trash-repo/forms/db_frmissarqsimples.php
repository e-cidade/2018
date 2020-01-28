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

//MODULO: issqn
$clissarqsimples->rotulo->label();
      if($db_opcao==1){
 	   $db_action="iss1_issarqsimples004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="iss1_issarqsimples005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="iss1_issarqsimples006.php";
      }  
      $db_opcao = 3;
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq17_sequencial?>">
       <?=@$Lq17_sequencial?>
    </td>
    <td> 
<?
db_input('q17_sequencial',8,$Iq17_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq17_instit?>">
       <?=@$Lq17_instit?>
    </td>
    <td> 
<?
db_input('q17_instit',10,$Iq17_instit,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq17_data?>">
       <?=@$Lq17_data?>
    </td>
    <td> 
<?
db_inputdata('q17_data',@$q17_data_dia,@$q17_data_mes,@$q17_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq17_nroremessa?>">
       <?=@$Lq17_nroremessa?>
    </td>
    <td> 
<?
db_input('q17_nroremessa',6,$Iq17_nroremessa,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq17_versao?>">
       <?=@$Lq17_versao?>
    </td>
    <td> 
<?
db_input('q17_versao',2,$Iq17_versao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq17_qtdreg?>">
       <?=@$Lq17_qtdreg?>
    </td>
    <td> 
<?
db_input('q17_qtdreg',10,$Iq17_qtdreg,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq17_vlrtot?>">
       <?=@$Lq17_vlrtot?>
    </td>
    <td> 
<?
db_input('q17_vlrtot',15,$Iq17_vlrtot,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq17_codbco?>">
       <?=@$Lq17_codbco?>
    </td>
    <td> 
<?
db_input('q17_codbco',3,$Iq17_codbco,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq17_oidarq?>">
       <?=@$Lq17_oidarq?>
    </td>
    <td> 
<?
db_input('q17_oidarq',1,$Iq17_oidarq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq17_nomearq?>">
       <?=@$Lq17_nomearq?>
    </td>
    <td> 
<?
db_input('q17_nomearq',100,$Iq17_nomearq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="alterar" type="submit" id="db_opcao" value="Alterar" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_issarqsimples','db_iframe_issarqsimples','func_issarqsimples.php?semproc=1&funcao_js=parent.js_preenchepesquisa|q17_sequencial','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_issarqsimples.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>