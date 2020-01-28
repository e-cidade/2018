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

//MODULO: orcamento
$clorcimpactoger->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To62_codimpger?>">
       <?=@$Lo62_codimpger?>
    </td>
    <td> 
<?
db_input('o62_codimpger',5,$Io62_codimpger,true,'text',3);
db_input('tipo',5,'',true,'hidden',3);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To62_ativo?>">
       <?=@$Lo62_ativo?>
    </td>
    <td> 
<?
db_input('o62_ativo',12,$Io62_ativo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To62_passivo?>">
       <?=@$Lo62_passivo?>
    </td>
    <td> 
<?
db_input('o62_passivo',12,$Io62_passivo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To62_data?>">
       <?=@$Lo62_data?>
    </td>
    <td> 
<?
$o62_data_dia =  date("d",db_getsession("DB_datausu"));
$o62_data_mes =  date("m",db_getsession("DB_datausu"));
$o62_data_ano =  date("Y",db_getsession("DB_datausu"));
db_inputdata('o62_data',@$o62_data_dia,@$o62_data_mes,@$o62_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To62_obs?>">
       <?=@$Lo62_obs?>
    </td>
    <td> 
<?
db_textarea('o62_obs',5,70,$Io62_obs,true,'text',$db_opcao,"")
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
  js_OpenJanelaIframe('top.corpo.iframe_orcimpactoger','db_iframe_orcimpactoger','func_orcimpactoger.php?funcao_js=parent.js_preenchepesquisa|o62_codimpger','Pesquisa',true,'0','1','775','390');
}
function js_preenchepesquisa(chave){
  db_iframe_orcimpactoger.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?tipo=$tipo&chavepesquisa='+chave";
  ?>
}
</script>