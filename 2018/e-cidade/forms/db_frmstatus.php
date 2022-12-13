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

//MODULO: configuracoes
$cldb_config->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?
db_input('codigo',2,$Icodigo,true,'hidden',3,"")
?>
<?if($db_botao != false){?>
  <tr>
    <td>
      <fieldset>
        <legend><b>Status atual</b></legend>
        <table>
          <tr>
            <td nowrap title="Selecionar o status do sistema">
              <b>Status:</b>
            </td>
            <td> 
              <?
              $arr_ativo = array(1=>"On line",2=>"Não permitir novos logs",3=>"Off line");
              if($db21_ativo == 0){
                $arr_ativo[0] = "Indefinido (Default: ".$arr_ativo[1].")";
              }
              db_select('db21_ativo',$arr_ativo,true,1,"");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="alterar" type="submit" id="db_opcao" value="Lançar status">
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
<?}else{?>
  <tr>
    <td align="center">
      <b>Configure os dados da Instituição Prefeitura.</b>
    </td>
  </tr>
<?}?>
</table>
</center>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_preenchepesquisa|codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_config.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>