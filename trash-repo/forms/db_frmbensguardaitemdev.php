<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: patrim
include("dbforms/db_classesgenericas.php");
 
$cliframe_seleciona = new cl_iframe_seleciona;
$clbensguardaitemdev->rotulo->label();
$clbensguardaitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("t21_codigo");
$clrotulo->label("t52_descr");
$clrotulo->label("nome");
?>
<script>
function js_submit_form() {  
  js_gera_chaves();
  return true;
}
</script>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Devolução de Itens do Termo de Guarda</legend>
    <table class="form-container">
      <tr>
        <td title="<?=@$Tt22_bensguarda?>">
          <?
            db_ancora(@$Lt22_bensguarda,"js_pesquisat22_bensguarda(true);",3);
          ?>
        </td>
        <td> 
          <?
            db_input('t22_bensguarda', 10, $It22_bensguarda, true, 'text', 3, " onchange='js_pesquisat22_bensguarda(false);'");
            db_input('t21_codigo', 8, $It21_codigo, true, 'hidden', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tt23_data?>">
          <?=@$Lt23_data?>
        </td>
        <td> 
          <?
            if (!isset($t23_data)){
            	$t23_data_ano = date('Y',db_getsession("DB_datausu"));
            	$t23_data_mes = date('m',db_getsession("DB_datausu"));
            	$t23_data_dia = date('d',db_getsession("DB_datausu"));
            }
            db_inputdata('t23_data',@$t23_data_dia,@$t23_data_mes,@$t23_data_ano,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tt23_situacao?>">
          <?=@$Lt23_situacao?>
        </td>
        <td> 
          <?
            $result_sit=$clsituabens->sql_record($clsituabens->sql_query_file());
            db_selectrecord('t23_situacao',$result_sit,true,'text',$db_opcao,"");
            if(isset($t23_situacao)&&$t23_situacao!=""){
            	echo "<script>document.form1.t23_situacao.value=$t23_situacao;</script>";
            }
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tt23_obs?>" colspan="2">
          <fieldset class="separator">
            <legend><?=@$Lt23_obs?></legend>
            <?
              db_textarea('t23_obs',0,50,$It23_obs,true,'text',$db_opcao,"")
            ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"incluir":"excluir"))?>" 
         type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Devolver":"Excluir"))?>" 
         <?=($db_botao==false?"disabled":"")?> Onclick='return js_submit_form();' /> 
  <input type="button" value="Imprimir" onClick="js_imprimir();" />
  <table>
    <tr>
      <td valign="top"  align="center">  
        <?
  	      $cliframe_seleciona->campos = "t22_bensguarda,t22_codigo,t22_bem,t52_descr,t22_dtini,t22_dtfim,t22_obs";
  	      $cliframe_seleciona->legenda = "Bens";
  	      if (isset($t21_codigo)&&$t21_codigo != "") { 
  	        $cliframe_seleciona->sql = $clbensguardaitem->sql_query_dev(null,"*",null,"t22_bensguarda=".@$t21_codigo." and t23_guardaitem is null");
  	      }
  	      //$cliframe_seleciona->sql_marca = @ $sql_marca;
  	      $cliframe_seleciona->iframe_height ="200";
  	      $cliframe_seleciona->iframe_width = "680";
  	      $cliframe_seleciona->iframe_nome = "itens_teste";
  	      $cliframe_seleciona->chaves = "t22_codigo";
  	      $cliframe_seleciona->iframe_seleciona(1);	 
        ?>
      </td>
    </tr>
  </table>
</form>
<script type="text/javascript">
/**
 * Imprime 
 */
function js_imprimir() {

  var sUrl  = 'pat2_reltermoguarda001.php?';
      sUrl += 'iTermo='+$F('t22_bensguarda');
      sUrl += '&devolucao=true';

  js_OpenJanelaIframe('', 'db_iframe_imprime_termo', sUrl, 'Imprime Termo', true);
}

function js_pesquisa() {
  js_OpenJanelaIframe('top.corpo','db_iframe_bensguarda','func_bensguardadev.php?funcao_js=parent.js_preenchepesquisa|t21_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bensguarda.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("t22_bensguarda").addClassName("field-size2");
$("t23_data").addClassName("field-size2");
$("t23_situacao").setAttribute("rel","ignore-css");
$("t23_situacao").addClassName("field-size2");
$("t23_situacaodescr").setAttribute("rel","ignore-css");
$("t23_situacaodescr").addClassName("field-size7");

</script>