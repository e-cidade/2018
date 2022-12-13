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

//MODULO: Habitacao
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo                 = new rotulocampo;

$clhabittipogrupoprograma->rotulo->label();
$clhabittipogrupoprogramaformaavaliacao->rotulo->label();
$clhabitformaavaliacao->rotulo->label();

if (isset($oPost->db_opcaoal)) {
  
  $db_opcao = 33;
  $db_botao = false;
} else if (isset($oPost->opcao) && $oPost->opcao == "alterar") {
  
  $db_botao = true;
  $db_opcao = 2;
} else if (isset($oPost->opcao) && $oPost->opcao == "excluir") {
  
  $db_opcao = 3;
  $db_botao = true;
} else {
    
  $db_opcao = 1;
  $db_botao = true;
  if (isset($oPost->novo)    || 
      isset($oPost->alterar) || 
      isset($oPost->excluir) || (isset($oPost->incluir) && $sqlerro == false )) {
    
    $ht06_sequencial = "";
    $ht07_sequencial = "";
    $ht07_descricao  = "";
  }
}
?>
<form name="form1" method="post" action="">
  <fieldset>
    <table border="0" align="left" width="100%">
      <tr>
        <td nowrap title="<?=@$Tht06_sequencial?>">
          <b>Código:</b>
        </td>
        <td> 
          <?
            db_input('ht06_sequencial',10,$Iht06_sequencial,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tht06_habittipogrupoprograma?>">
          <?=@$Lht06_habittipogrupoprograma?>
        </td>
        <td> 
          <?
            db_input('ht06_habittipogrupoprograma',10,$Iht06_habittipogrupoprograma,true,'text',3,"");
            db_input('ht02_descricao',50,$Iht02_descricao,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tht07_sequencial?>">
          <?
            db_ancora("<b>Forma Avaliação:</b>","js_pesquisaht07_sequencial(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('ht07_sequencial',10,$Iht07_sequencial,true,'text',$db_opcao," onchange='js_pesquisaht07_sequencial(false);'");
            db_input('ht07_descricao',50,$Iht07_descricao,true,'text',3,'');
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <table align="center">
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
               type="submit" id="db_opcao" onclick="return js_validar();"
               value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
               <?=($db_botao==false?"disabled":"")?>  >
      </td>
      <td>
        <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" 
               <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
      </td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
  <table>
    <tr>
      <td valign="top"  align="center">  
        <?
          $chavepri= array("ht06_sequencial"=>@$ht06_sequencial);
          
          $sWhere                              = "ht06_habittipogrupoprograma = ".@$ht06_habittipogrupoprograma;
          $sCampos                             = "ht06_sequencial,ht06_habitformaavaliacao,ht07_descricao";
          $sSqlTipoGrupoProgramaFormaAvaliacao = $clhabittipogrupoprogramaformaavaliacao->sql_query(null, "*", 
                                                                                                    "ht06_sequencial", 
                                                                                                    $sWhere);
          
          $cliframe_alterar_excluir->chavepri      = $chavepri;
          $cliframe_alterar_excluir->sql           = $sSqlTipoGrupoProgramaFormaAvaliacao;
          $cliframe_alterar_excluir->campos        = $sCampos;
          $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
          $cliframe_alterar_excluir->iframe_height = "160";
          $cliframe_alterar_excluir->iframe_width  = "600";
          $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
          
        ?>
      </td>
    </tr>
  </table>
</form>
<script>
function js_validar() {

  var iCodTipoGrupoPrograma = $('ht06_habittipogrupoprograma').value;
  var iCodFormaAvaliacao    = $('ht07_sequencial').value;
  
  if (iCodTipoGrupoPrograma == '') {
    
    var sMsg  = "Usuário: \n";
        sMsg += "Informe um grupo válido!";
          
    alert(sMsg);
    return false;
  }
  
  if (iCodFormaAvaliacao == '') {
  
    var sMsg  = "Usuário: \n";
        sMsg += "Informe uma forma de avaliação válida!";
        
    alert(sMsg);
    return false;
  }
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisaht07_sequencial(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_habitformaavaliacao.php?funcao_js=parent.js_mostraformaavaliacao1|ht07_sequencial|ht07_descricao';
    js_OpenJanelaIframe('','db_iframe_formaavaliacao',sUrl,'Pesquisa',true,'0');
  } else {
    if(document.form1.ht07_sequencial.value != '') {
      
      var ht07_sequencial = document.form1.ht07_sequencial.value; 
      var sUrl         = 'func_habitformaavaliacao.php?pesquisa_chave='+ht07_sequencial+'&funcao_js=parent.js_mostraformaavaliacao';
      js_OpenJanelaIframe('','db_iframe_formaavaliacao',sUrl,'Pesquisa',false);
    } else {
      document.form1.ht07_sequencial.value = ''; 
      document.form1.ht07_descricao.value  = '';
    }
  }
}

function js_mostraformaavaliacao(chave,erro) {

  document.form1.ht07_descricao.value = chave; 
  if (erro == true) {
   
    document.form1.ht07_sequencial.focus(); 
    document.form1.ht07_sequencial.value = ''; 
  }
}

function js_mostraformaavaliacao1(chave1,chave2) {

  document.form1.ht07_sequencial.value = chave1;
  document.form1.ht07_descricao.value    = chave2;
  db_iframe_formaavaliacao.hide();
}
</script>