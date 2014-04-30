<?php
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

$clrotulo = new rotulocampo;
$clrotulo->label("m80_coddepto");
$clrotulo->label("descrdepto");
$clrotulo->label("m60_descr");
$clrotulo->label("m81_descr");
$clrotulo->label("m80_codtipo");
$clrotulo->label("m70_codmatmater");
$clrotulo->label("m71_codmatestoque");
$clrotulo->label("m80_matestoqueitem");
$clrotulo->label("cc08_sequencial");
$clrotulo->label("m80_obs");
$clrotulo->label("cc08_sequencial");
?>
<form name='form1' method="post">
<table>
  <tr>
    <td>
      <fieldset>
        <legend>
          <b>Alterar Centro de Custo</b>
        </legend>
        <table>
          <tr>
            <td>
              <b>Material :</b>
            </td> 
            <td>
              <?
               db_input('cc12_sequencial', 10, $Im70_codmatmater, true, "hidden", 3);
               db_input('m70_codmatmater', 10, $Im70_codmatmater, true, "text", 3);
               db_input('m60_descr', 40, $Im60_descr, true, "text", 3);
              ?> 
            </td>
          </tr>
          <tr>
            <td>
              <b>Tipo Saída :</b>
            </td> 
            <td>
              <?
               db_input('m80_codtipo', 10, $Im70_codmatmater, true, "text", 3);
               db_input('m81_descr', 40, $Im60_descr, true, "text", 3);
              ?> 
            </td>
          </tr>
          <tr>
            <td>
              <b>Departamento :</b>
            </td> 
            <td>
              <?
               db_input('m80_coddepto', 10, $Im70_codmatmater, true, "text", 3);
               db_input('descrdepto', 40, $Im60_descr, true, "text", 3);
              ?> 
            </td>
          </tr>
          <tr>
            <td>
              <?
                db_ancora("<b>Centro de de Custo:</b>",'js_adicionaCentroCusto()', 1,"","centrocusto");
              ?>  
            </td>
            <td>
              <?
                db_input('cc08_sequencial',10,$Icc08_sequencial,true,"text", 3);
                db_input('cc08_descricao',40,$Im60_descr,true,"text",3);
              ?>  
            </td>
          </tr>
          <tr>
            <td>
              <b>Quantidade:</b>  
            </td>
            <td>
              <?
                db_input('cc12_qtd',10,$Icc08_sequencial,true,"text", 3);
              ?>  
            </td>
          </tr>
          <tr>
            <td>
              <b>Valor:</b>  
            </td>
            <td>
              <?
                db_input('cc12_valor',10,$Icc08_sequencial,true,"text", 3);
              ?>  
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>    
  <tr>
    <td style="text-align: center;">
      <input name="btnalterar"   type="submit" id="btnalterar"    value="Alterar" disabled 
             onclick='return js_validaAlteracao();'>
             
      <input name="btnpesquisar" type="button" id="btnpesquisar" value="pesquisar" 
             onclick="js_pesquisa()" >
    </td>
  </tr>  
</table>
</form>
<script>
function js_adicionaCentroCusto() {
 
  var iOrigem  = 2;
  var sUrl     = 'iOrigem='+iOrigem+'&iCodItem='+$F('m70_codmatmater')+'&iCodigoDaLinha='+$F('m70_codmatmater');
  sUrl        += '&iCodigoDepto='+$F('m80_coddepto');
  if ($F('m70_codmatmater')) {
    
    js_OpenJanelaIframe('',
                        'db_iframe_centroCusto',
                        'cus4_escolhercentroCusto.php?'+sUrl,
                        'Centro de Custos',
                        true,
                        '25',
                        '1',
                        (document.body.scrollWidth-10),
                        (document.body.scrollHeight-25)
                       );
  }
  
   
}

function js_completaCustos(iCodigo, iCriterio, iDescr) {
  
  $('cc08_sequencial').value = iCriterio;
  $('cc08_descricao').value  = iDescr;
  db_iframe_centroCusto.hide();

}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_custoapropria',
                      'func_custoapropria.php?funcao_js=parent.js_preenchepesquisa|cc12_sequencial',
                      'Apropriações de Custo Realizadas',
                      true,
                      '25',
                      '1',
                      (document.body.scrollWidth-10),
                       (document.body.scrollHeight-25)
                      );
}
function js_preenchepesquisa(chave){
  db_iframe_custoapropria.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
}
<?
if (isset($cc12_sequencial) && $cc12_sequencial != "") {
  
  echo "\n$('btnalterar').disabled = false;\n";
}

?>

function js_validaAlteracao() {

  if ($F('cc08_sequencial') == "") {
  
    alert('O prenchimento do centro de custo é obrigatório!');
    return false;
    
  }
  return true;
}
</script>