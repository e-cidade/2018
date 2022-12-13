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
require_once("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clprocessoforoinicial->rotulo->label();

if (isset($oPost->db_opcaoal)) {
  
  $db_opcao = 33;
  $db_botao = false;
} else if (isset($oPost->opcao) && $oPost->opcao == "excluir") {
  
  $db_opcao = 3;
  $db_botao = true;
} else {
    
  $db_opcao = 1;
  $db_botao = true;
  if (isset($oPost->novo) || isset($oPost->excluir) || (isset($oPost->incluir) && $lSqlErro == false )) {
    
    $v71_sequencial = "";
    $v71_inicial    = "";
  }
}
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Dados Iniciais Processo Foro</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tv71_inicial?>">
          <?
            db_ancora('<b>Número Inicial:</b>',"js_pesquisav71_inicial(true);",$db_opcao);
          ?>
          <?
            db_input('v71_sequencial',10,$Iv71_sequencial,true,'hidden',3);
            db_input('v71_processoforo',10,$Iv71_processoforo,true,'hidden',3);
            db_input('v71_inicial',10,$Iv71_inicial,true,'text',$db_opcao," onchange='js_pesquisav71_inicial(false);'");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
               type="submit" id="db_opcao" onclick="return js_validar();"
               value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
               <?=($db_botao==false?"disabled":"")?>  >
        <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" 
               <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
  <table class="form-container">
    <tr>
      <td valign="top"  align="center">  
        <?
          $chavepri= array("v71_sequencial"=>@$v71_sequencial);
          
          $sWhere                  = "v71_processoforo = ".@$v71_processoforo." and v71_anulado is false   ";
          $sCampos                 = "v71_sequencial, v71_id_usuario, v71_inicial,                         "; 
          $sCampos                .= "v71_processoforo, v71_data, v71_anulado                              ";
          $sSqlProcessoForoInicial = $clprocessoforoinicial->sql_query(null, "*", "v71_sequencial", $sWhere);
          $cliframe_alterar_excluir->chavepri      = $chavepri;
          $cliframe_alterar_excluir->sql           = $sSqlProcessoForoInicial;
          $cliframe_alterar_excluir->campos        = $sCampos;
          $cliframe_alterar_excluir->legenda       = "Iniciais Cadastradas";
          $cliframe_alterar_excluir->iframe_height = "160";
          $cliframe_alterar_excluir->iframe_width  = "600";
          $cliframe_alterar_excluir->opcoes        = 3;
          $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
        ?>
      </td>
    </tr>
  </table>
</form>
<script>
function js_validar() {

  var iNumeroInicial = $('v71_inicial').value;
  if (iNumeroInicial == '') {
    
    alert(_M('tributario.juridico.db_frmprocessoforoinicial.informe_inicial'));
    return false;
  }
} 


function js_cancelar() {

  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisav71_inicial(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_inicialprocessoforo.php?verif_proc=1&funcao_js=parent.js_mostrainicial1|v50_inicial|v50_data';
    js_OpenJanelaIframe('', 'db_iframe_inicial', sUrl, 'Pesquisa', true, 0);
  } else {
  
    if (document.form1.v71_inicial.value != '') { 
      var sUrl = 'func_inicialprocessoforo.php?verif_proc=1&pesquisa_chave='+$('v71_inicial').value
                                                                +'&funcao_js=parent.js_mostrainicial';
      js_OpenJanelaIframe('', 'db_iframe_inicial', sUrl, 'Pesquisa', false, 0);
    } else {
      $('v71_inicial').value = ''; 
    }
  }
}

function js_mostrainicial(chave,erro) {
 
  if (erro == true) {
   
    $('v71_inicial').value = ''; 
    $('v71_inicial').focus(); 
  }
}

function js_mostrainicial1(chave1,chave2) {

  $('v71_inicial').value = chave1;
  db_iframe_inicial.hide();
}
</script>

<script>

$("v71_inicial").addClassName("field-size2");

</script>