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

//MODULO: material
$clmatparam->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db77_descr");
?>
<form name="form1" method="post" action="" onsubmit="return js_validaConfiguracaoEstrutural();">
  <center>
    <fieldset>
      <legend align="center">  
        <b>Configuração de Parâmetros</b>  
      </legend>  
      <table border="0">
      <tr>
      <td>
      <fieldset style='border: 0px;border-top: 2px groove white;' class='interno'>
      <legend><b>Gerais</b></legend>
      <table>
       <tr>
        <td nowrap title="<?=@$Tm90_tipocontrol?>">
          <input name="oid" type="hidden" value="<?=@$oid?>">
          <?=@$Lm90_tipocontrol?>
        </td>
        <td> 
          <?
          $aX = array('D' => 'Por departamento', 'S' => 'Por secretaria', 'G' => 'Por almoxarifado/departamento',
                      'F' => 'Por almoxarifado/deposito');
          db_select('m90_tipocontrol', $aX, true, $db_opcao, "style='width:250px;'");
          ?>
        </td>
      </tr>
      <tr style='display:none'>
        <td nowrap title="<?=@$Tm90_modrelsaidamat?>">
          <?=@$Lm90_modrelsaidamat?>
        </td>
        <td> 
          <?
          $aX = array('18' => 'Modelo 18 - Observação Resumida', '181' => 'Modelo 181-  Observação Completa');
          db_select('m90_modrelsaidamat', $aX, true, $db_opcao, "style='width:250px;'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tm90_almoxordemcompra?>">
          <?=@$Lm90_almoxordemcompra?>
        </td>
        <td> 
          <?
          $aX = array('1' => 'Departamento(s) do(s) Usuário(s)', '2' => 'Departamento(s) de Origem');
          db_select('m90_almoxordemcompra', $aX, true, $db_opcao, "style='width:250px;'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tm90_reqsemest?>">
           <?=@$Lm90_reqsemest?>
        </td>
        <td> 
          <?
          $aX = array("f" => "Não", "t" => "Sim");
          db_select('m90_reqsemest', $aX, true, $db_opcao, "style='width:250px;'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tm90_versaldoitemreq?>">
           <?=@$Lm90_versaldoitemreq?>
        </td>
        <td> 
          <?
          $aVerSaldoItem = array("f" => "Não", "t" => "Sim");
          db_select('m90_versaldoitemreq', $aVerSaldoItem, true, $db_opcao, "style='width:250px;'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tm90_deptalmox?>">
           <?=@$Lm90_deptalmox?>
        </td>
        <td> 
          <?
          $aX = array('t' => 'Sim', 'f' => 'Não');
          db_select('m90_deptalmox', $aX, true, $db_opcao, "style='width:250px;'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tm90_liqentoc?>">
           <?=@$Lm90_liqentoc?>
        </td>
        <td> 
          <?
          $aX = array('f' => 'Não', 't' => 'Sim');
          db_select('m90_liqentoc', $aX, true, $db_opcao, "style='width:250px;'");
          ?>
        </td>
      </tr>
      <tr style='display:none'>
        <td nowrap title="<?=@$Tm90_entratrans?>">
          <?=@$Lm90_entratrans?>
        </td>
        <td> 
          <?
          $aX = array('t' => 'Sim', 'f' => 'Não');
          db_select('m90_entratrans', $aX, true, $db_opcao, "style='width:250px;'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tm90_dtimplan?>">
           <?=@$Lm90_dtimplan?>
        </td>
        <td> 
          <?
          db_inputdata('m90_dtimplan', @$m90_dtimplan_dia, @$m90_dtimplan_mes, @$m90_dtimplan_ano, 
                       true, 'text', $db_opcao, ""
                      );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tm90_prazovenc?>">
           <?=@$Lm90_prazovenc?>
        </td>
        <td> 
          <?
          db_input('m90_prazovenc', 10, @$Im90_prazovenc, true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tm90_corfundorequisicao?>">
           <?=@$Lm90_corfundorequisicao?>
        </td>
        <td> 
          <?
          $aX = array("1" => "Cinza", "2" => "Branco");
          db_select('m90_corfundorequisicao', $aX, true, $db_opcao, "style='width:250px;'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tm90_db_estrutura?>">
         <?
           db_ancora(@$Lm90_db_estrutura,"js_pesquisam90_db_estrutura(true);",$db_opcao);
         ?>
        </td>
        <td> 
           <?
             db_input('m90_db_estrutura',10,$Im90_db_estrutura,true,'text',$db_opcao,
              " onchange='js_pesquisam90_db_estrutura(false);'");
             db_input('db77_descr',30,$Idb77_descr,true,'text',3,'');
             $m90_db_estrutura_anterior = $m90_db_estrutura;
             db_input("m90_db_estrutura_anterior", 10, $Im90_db_estrutura, true, 'hidden', 3);
           ?>
        </td>
      </tr>
      </table>
      </fieldset>
      </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset style='border: 0px;border-top: 2px groove white;' class="interno">
            <legend><b>Solicitação de Transferência</b></legend>
            <table>
              <tr>
                <td nowrap title="<?=@$Tm90_mostrarsaldosolictransf?>">
                  <?=@$Lm90_mostrarsaldosolictransf?>
                </td>
                <td> 
                  <?
                  $aX = array('1' => 'Sim', '2' => 'Não');
                  db_select('m90_mostrarsaldosolictransf', $aX, true, $db_opcao, "style='width:250px;'");
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Tm90_validarsaldosolictransf?>">
                  <?=@$Lm90_validarsaldosolictransf?>
                </td>
                <td> 
                  <?
                  $aX = array('1' => 'Sim', '2' => 'Não');
                  db_select('m90_validarsaldosolictransf', $aX, true, $db_opcao, "style='width:250px;'");
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>
    </fieldset>
  </center>
<input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" 
  type="submit" id="db_opcao" 
  value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
  <?=($db_botao == false ? "disabled" : "")?>>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa() {

  js_OpenJanelaIframe('top.corpo', 'db_iframe_matparam', 'func_matparam.php?'+
                      'funcao_js=parent.js_preenchepesquisa|0', 'Pesquisa', true
                     );
}
function js_preenchepesquisa(chave) {

  db_iframe_matparam.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>

}

function js_validaConfiguracaoEstrutural() {

  if ($F('m90_db_estrutura') != $F('m90_db_estrutura_anterior')) {

    var sMsgConfirm  = "Você alterou a Estrutura de Grupos dos materiais. Os grupos e subgrupos serão alterados ";
    sMsgConfirm     += "para utilizar o estrutural cadastrado na conta escolhida.\n\n";
    sMsgConfirm     += "Importante: Será necessário reconfigurar o estrutural dos grupos e subgrupos.\n\n";
    sMsgConfirm     += "Confirma esta operação?";

    if (!confirm(sMsgConfirm)) {
      return false;
    }
  }
}



function js_pesquisam90_db_estrutura(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_estrutura',
                        'func_db_estrutura.php?funcao_js=parent.js_mostradb_estrutura1|db77_codestrut|db77_descr',
                        'Pesquisa',true);
  }else{
     if(document.form1.m90_db_estrutura.value != ''){ 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_db_estrutura',
                            'func_db_estrutura.php?pesquisa_chave='
                             +document.form1.m90_db_estrutura.value+'&funcao_js=parent.js_mostradb_estrutura',
                             'Pesquisa',false);
     }else{
       document.form1.db77_descr.value = ''; 
     }
  }
}
function js_mostradb_estrutura(chave,erro){
  document.form1.db77_descr.value = chave; 
  if(erro==true){ 
    document.form1.m90_db_estrutura.focus(); 
    document.form1.m90_db_estrutura.value = ''; 
  }
}
function js_mostradb_estrutura1(chave1,chave2){
  document.form1.m90_db_estrutura.value = chave1;
  document.form1.db77_descr.value = chave2;
  db_iframe_db_estrutura.hide();
}
</script>