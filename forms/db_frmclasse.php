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

//MODULO: issqn
$clclasse->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<fieldset style="margin-top: 20px;">
<legend><b>Cadastro de Classes</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq12_classe?>">
       <?=@$Lq12_classe?>
    </td>
    <td> 
    <?
      db_input('q12_classe',10,$Iq12_classe,true,'text',3,"")
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq12_descr?>">
       <?=@$Lq12_descr?>
    </td>
    <td> 
    <?
      db_input('q12_descr',40,$Iq12_descr,true,'text',$db_opcao,"")
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq12_fisica?>">
       <?=@$Lq12_fisica?>
    </td>
    <td> 
      <?
        $x = array('f'=>'Física','t'=>'Juridica');
        db_select('q12_fisica',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq12_calciss?>">
       <?=@$Lq12_calciss?>
    </td>
    <td> 
      <?
        $x = array("f"=>"NAO","t"=>"SIM");
        db_select('q12_calciss',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq12_integrasani?>">
       <?=@$Lq12_integrasani?>
    </td>
    <td> 
      <?
        $x = array("f"=>"NAO","t"=>"SIM");
        db_select('q12_integrasani',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq12_alvaraautomatico?>">
       <b>Gera Alvará de Localização Automático: </b>
    </td>
    <td> 
      <?
        $x = array("t"=>"SIM","f"=>"NAO");
        db_select('q12_alvaraautomatico',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>  
  </table>
</fieldset>  
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_classe','func_classe.php?funcao_js=parent.js_preenchepesquisa|q12_classe','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_classe.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>