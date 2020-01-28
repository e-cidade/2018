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

//MODULO: Juridico
$cldbusuarios->rotulo->label();
$clprocessoforo->rotulo->label();
$clprocessoforomov->rotulo->label();
$clprocessoforomovsituacao->rotulo->label();
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Procedimentos - Situação do Processo do Foro</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tv73_sequencial?>">
          <?=@$Lv73_sequencial?>
        </td>
        <td> 
          <?
            db_input('v73_sequencial', 10, $Iv73_sequencial, true, 'text', 3);
            //  jur1_consultaprocessoforo002.php?v70_sequencial=8
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tv70_sequencial?>">
          <?
            db_ancora("Processo:", "js_pesquisav70_sequencial(true);", $db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('v70_sequencial', 10, $Iv70_sequencial, true, 
                     'text', 3, " onchange='js_pesquisav70_sequencial(false);'");
          ?>
          <input name="consultaprocesso" type="button" value="Consulta Processo" onclick="js_consulta_processo(document.form1.v70_sequencial.value)" >
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tv74_sequencial?>">
          <?
            db_ancora("Situação:", "js_pesquisav74_sequencial(true);", $db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('v74_sequencial', 10, $Iv74_sequencial, true, 
                     'text', $db_opcao, " onchange='js_pesquisav74_sequencial(false);'");
            db_input('v74_descricao', 60, $Iv74_descricao, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tv73_data?>">
          <?=@$Lv73_data?>
        </td>
        <td>
          <?
            if( $db_opcao == 1){
              $v73_data_dia = date("d");
              $v73_data_mes = date("m");
              $v73_data_ano = date("Y");
            }
            
            db_inputdata('v73_data',@$v73_data_dia, @$v73_data_mes, @$v73_data_ano, true, 'text', $db_opcao)
          ?>
          <?=@$Lv73_hora?>
          <?
            db_input('v73_hora', 10, $Iv73_hora, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Nome do Contribuinte">
          Contribuinte:
        </td>
        <td> 
          <?
            db_input('dl_nome', 40, $Inome, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset class="separator">
            <legend><?=@$Lv73_obs?></legend>
                <?
                  db_textarea('v73_obs', 8, 60, $Iv73_obs, true, 'text', $db_opcao);
                ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
         type="submit" id="db_opcao" onclick="return js_validar();"
         value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
         <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_validar() {

  var iCodigoProcesso = $('v70_sequencial').value;
  var iCodigoSituacao = $('v74_sequencial').value;
  if (iCodigoProcesso == '') {
  
    alert(_M('tributario.juridico.db_frmprocessoforomov.informe_processo'));
    return false;
  }
  
  if (iCodigoSituacao == '') {
    
    alert(_M('tributario.juridico.db_frmprocessoforomov.informe_situacao'));
    return false;
  }
}

function js_pesquisav70_sequencial(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_processoforo.php?lAnuladas=false&funcao_js=parent.js_mostarprocesso1|v70_sequencial|dl_nome';
    js_OpenJanelaIframe('', 'db_iframe_processoforo', sUrl, 'Pesquisa', true);
  } else {
  
    if ($('v70_sequencial').value != '') {
      var sUrl = 'func_processoforo.php?pesquisa_chave='+$('v70_sequencial').value
                                                        +'&funcao_js=parent.js_mostarprocesso'
                                                        +'&lAnuladas=false'; 
      js_OpenJanelaIframe('', 'db_iframe_processoforo', sUrl, 'Pesquisa', false);
    } else {
      $('v70_sequencial').value = ''; 
    }
  }
}

function js_mostarprocesso(chave,erro) {

  $('v70_sequencial').value = chave; 
  if (erro == true) {
   
    $('v70_sequencial').value = ''; 
    $('v70_sequencial').focus(); 
  }
}

function js_mostarprocesso1(chave1,chave2) {

  $('v70_sequencial').value = chave1;
  $('dl_nome').value = chave2;
  db_iframe_processoforo.hide();
}

function js_pesquisav74_sequencial(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_processoforomovsituacao.php?lAnuladas=false'
              +'&funcao_js=parent.js_mostarsituacaoprocesso1|v74_sequencial|v74_descricao';
    js_OpenJanelaIframe('', 'db_iframe_processoforomovsituacao', sUrl, 'Pesquisa', true);
  } else {
  
    if ($('v74_sequencial').value != '') {
      var sUrl = 'func_processoforomovsituacao.php?pesquisa_chave='+$('v74_sequencial').value
                                                        +'&funcao_js=parent.js_mostarsituacaoprocesso'
      js_OpenJanelaIframe('', 'db_iframe_processoforomovsituacao', sUrl, 'Pesquisa', false);
    } else {
    
      $('v74_sequencial').value = ''; 
      $('v74_descricao').value  = '';
    }
  }
}

function js_mostarsituacaoprocesso(chave2, erro) {

  $('v74_descricao').value  = chave2;
  if (erro == true) {
   
    $('v74_sequencial').value = '';
    $('v74_descricao').value  = chave1;
    $('v74_sequencial').focus(); 

  }
}

function js_mostarsituacaoprocesso1(chave1, chave2) {

  $('v74_sequencial').value = chave1;
  $('v74_descricao').value  = chave2;
  db_iframe_processoforomovsituacao.hide();
}

function js_pesquisa() {

  var sUrl = 'func_processoforomov.php?funcao_js=parent.js_preenchepesquisa|v73_sequencial';
  js_OpenJanelaIframe('', 'db_iframe_processoforomov', sUrl, 'Pesquisa', true);
}

function js_preenchepesquisa(chave) {

  db_iframe_processoforomov.hide();
  <?
    if ($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}

function js_consulta_processo(codigo){
  
  if ( codigo =="" ){
    alert(_M('tributario.juridico.db_frmprocessoforomov.selecione_processo'));
  }else{ 
    var sUrl = 'jur1_consultaprocessoforo002.php?v70_sequencial='+codigo;
    js_OpenJanelaIframe('', 'db_iframe_processoconsulta', sUrl, 'Pesquisa', true);
  }
}

<?
if( $db_opcao == 1 ){
  ?>
  js_pesquisav70_sequencial(true);
  <?
}
?>
</script>

<script>

$("v73_sequencial").addClassName("field-size2");
$("v70_sequencial").addClassName("field-size2");
$("v74_sequencial").addClassName("field-size2");
$("v74_descricao").addClassName("field-size7");
$("v73_data").addClassName("field-size2");
$("v73_hora").addClassName("field-size2");
$("dl_nome").addClassName("field-size9");

</script>