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

//MODULO: Ambulatorial
include("libs/db_jsplibwebseller.php");

$clsau_turnoatend->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
         <fieldset><legend>Turno de Atendimento</legend>

<table border="0">

  <tr>
    <td nowrap title="<?=@$Tsd43_cod_turnat?>">
       <?=@$Lsd43_cod_turnat?>
    </td>
    <td> 
<?
db_input('sd43_cod_turnat',2,$Isd43_cod_turnat,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd43_v_descricao?>">
       <?=@$Lsd43_v_descricao?>
    </td>
    <td> 
<?
db_input('sd43_v_descricao',60,$Isd43_v_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd43_c_horainicial?>">
       <?=@$Lsd43_c_horainicial?>
    </td>
    <td> 
<?
db_input('sd43_c_horainicial',5,$Isd43_c_horainicial,true,'text',$db_opcao,"onKeyUp=\"mascara_hora(this.value,'sd43_c_horainicial', event)\" ")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd43_c_horafinal?>">
       <?=@$Lsd43_c_horafinal?>
    </td>
    <td> 
<?
db_input('sd43_c_horafinal',5,$Isd43_c_horafinal,true,'text',$db_opcao," onKeyUp=\"mascara_hora(this.value,'sd43_c_horafinal', event)\" ")
?>
    </td>
  </tr>
  </table>
  </fieldset>  
  </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_turnoatend','func_sau_turnoatend.php?funcao_js=parent.js_preenchepesquisa|sd43_cod_turnat','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_turnoatend.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>