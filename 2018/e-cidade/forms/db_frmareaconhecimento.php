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


//MODULO: secretariadeeducacao
require_once("dbforms/db_classesgenericas.php");
$clAlterarExcluir = new cl_iframe_alterar_excluir;

$oDaoAreaConhecimento->rotulo->label();

?>
<br /><br />
<fieldset><legend><b>
  <?=($db_opcao == 1 ? "Cadastro" : ($db_opcao == 2 ? "Alteração" : "Exclusão"))?>
  de Área de Conhecimento</b></legend>
<form name="form1" method="post" action="">
  <center>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Ted293_sequencial?>">
          <?=@$Led293_sequencial?>
        </td>
        <td> 
          <?db_input('ed293_sequencial', 10, $Ied293_sequencial, true, 'text', "3", "")?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted293_descr?>">
          <?=@$Led293_descr?>
        </td>
        <td> 
          <?db_input('ed293_descr', 100, $Ied293_descr, true, 'text', $db_opcao, "")?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted293_ativo?>">
          <?=@$Led293_ativo?>
        </td>
        <td> 
          <?
            $aOpcoes = array("2" => "NÃO", "1" => "SIM");
            db_select('ed293_ativo', $aOpcoes, true, $db_opcao, '');
          ?>
        </td>
      </tr>
    </table>
  </center>
  
  <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" 
         type="submit" id="db_opcao" value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? 
                                     "Alterar" : "Excluir"))?>" <?=($db_botao == false ? "disabled" : "")?> 
         onclick="js_valida();">
  
  <input name="cancelar" type="button" value="Cancelar" onclick="js_cancelar();" 
         <?=($db_opcao == 1 ? "disabled" : "")?> >
</form>

<fieldset><legend><b>Registros</b></legend>

  <center>
    <div id="areasConhecimento"></div>
  </center>

</fieldset>

</fieldset>

<script>

oDBGridAreas = js_criaGridAreasConhecimento();

function js_criaGridAreasConhecimento() {

  oDBGrid                = new DBGrid('areasConhecimento');
  oDBGrid.nameInstance   = "oDBGridAreas";
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('10%', '50%', '30%', '10%'));
  oDBGrid.setHeight(150);
  oDBGrid.allowSelectColumns(true);

  var aHeader = new Array();
  aHeader[0]  = 'Sequencial';
  aHeader[1]  = 'Descrição';
  aHeader[2]  = 'Ativo';
  aHeader[3]  = 'Opções';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0]  = 'left';
  aAligns[1]  = 'center';
  aAligns[2]  = 'center';
  aAligns[3]  = 'center';
  oDBGrid.setCellAlign(aAligns);
  
  oDBGrid.show($('areasConhecimento'));
  oDBGrid.clearAll(true);

  return oDBGrid;

}

function js_buscaAreasConhecimento() {

  var oParam  = new Object();

  oParam.exec = "getAreasConhecimento";
  sUrl        = "edu4_escola.RPC.php";

  js_webajax(oParam, 'js_retornoBuscaArea', sUrl);

}

function js_retornoBuscaArea(oRetorno) {

  var oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 0) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    var iResultado = oRetorno.iResultado;
    
    if (parseInt(iResultado, 10) > 0) {

      var aLinha = new Array();
      oDBGridAreas.clearAll(true);
      oDBGridAreas.renderRows();

      for (var iCont = 0; iCont < iResultado; iCont++) {
        
        sOpcoes   = "<span onclick='js_alterarArea("+oRetorno.aResultado[iCont].ed293_sequencial+");'> <u>A</u> </span>";
        sOpcoes  += "<span onclick='js_excluirArea("+oRetorno.aResultado[iCont].ed293_sequencial+");'> <u>E</u> </span>";

        aLinha[0] = oRetorno.aResultado[iCont].ed293_sequencial;
        aLinha[1] = oRetorno.aResultado[iCont].ed293_descr.urlDecode();
        aLinha[2] = oRetorno.aResultado[iCont].ed293_ativo.urlDecode();
        aLinha[3] = sOpcoes;

        oDBGridAreas.addRow(aLinha);

      }

      oDBGridAreas.renderRows();

    } else {
      oDBGridAreas.clearAll(true);
      oDBGridAreas.renderRows();
    }

  }

}

function js_alterarArea(iAreaConhecimento) {
  location.href = "edu1_areaconhecimento002.php?chavepesquisa="+iAreaConhecimento;
}

function js_excluirArea(iAreaConhecimento) {
  location.href = "edu1_areaconhecimento003.php?chavepesquisa="+iAreaConhecimento;
}

function js_cancelar() {
  location.href = "edu1_areaconhecimento001.php";
}

function js_valida() {

  var sDBOpcao = $('db_opcao').value;

  if (sDBOpcao == "Excluir") {
    
    return true;
  
  } else if (sDBOpcao == "Incluir" || sDBOpcao == "Alterar") {

    if ($('ed293_descr').value.trim() == "") {
      
      alert("Digite a descrição da Área de Conhecimento antes de incluir.");
      return false;

    }

  }

  return true;

}

js_buscaAreasConhecimento();

</script>