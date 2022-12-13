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


//MODULO: escola
$oDaoAnexoAtoLegal->rotulo->label();
$oDaoRotulo = new rotulocampo;
$oDaoRotulo->label("ed05_i_codigo");

?>
<br /><br />
<form name="form1" method="post" action="" enctype="multipart/form-data" >
  <center>
    <fieldset style="width=95%;"><legend><b>
      <?=($db_opcao == 1 ? "Inclusão" : ($db_opcao == 2 || $db_opcao == 22 ? "Alteração" : "Exclusão"))?>
      de Arquivo</b></legend>
      <table border="0">
        <br />
        <tr>
          <td nowrap title="<?=@$Ted292_arquivo?>">
            <?=@$Led292_arquivo?>
          </td>
          <td> 
            <?
              db_input('ed05_i_codigo',50,'',true,'hidden','');
              db_input('ed292_sequencial',50,'',true,'hidden','');

              if (isset($ed292_nomearquivo) && $ed292_nomearquivo != "") {
                echo("<b>Arquivo atual: tmp/$ed292_nomearquivo</b>");
              }

              db_input('ed292_nomearquivo',60,$Ied292_nomearquivo,true,'hidden','');
              db_input('ed292_arquivo',80,'',true,'file',$db_opcao,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ted292_obs?>">
            <?=@$Led292_obs?>
          </td>
          <td> 
            <?
              db_textarea('ed292_obs',3,100,$Ied292_obs,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td> 
            <?
              db_input('ed292_ordem',10,$Ied292_ordem,true,'hidden','')
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
  </center>
  
  <br />

  <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" 
         type="submit" id="db_opcao" 
         value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>" 
         onclick="return js_valida();" <?=($db_botao == false ? "disabled" : "")?> />
  <input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_cancelaAcao();"
         <?=($db_opcao == 1 ? "disabled" : "")?> />
  <input name="ordenar" type="button" id="ordenar" value="Ordenar Arquivos" onclick="js_ordenarCampos();"
         <?=($db_opcao == 1 ? "" : "disabled")?> />
  <input name="novoRegistro" type="button" id="novoRegistro" value="Novo Registro" onclick="js_novoRegistro();" />
</form>

<fieldset width="width=95%;"><legend><b>Arquivos Incluidos</b></legend>
  
  <center>
  <div id="arquivosIncluidos"></div>
  </center>

</fieldset>

<script>

oDBGridArquivos = js_criaGridArquivosIncluidos();

function js_criaGridArquivosIncluidos() {

  oDBGrid                = new DBGrid('arquivosIncluidos');
  oDBGrid.nameInstance   = 'oDBGridArquivos';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('10%', '10%', '50%', '20%', '10%'));
  oDBGrid.setHeight(150);
  oDBGrid.allowSelectColumns(true);

  var aHeader = new Array();
  aHeader[0]  = 'Ordem';
  aHeader[1]  = 'Código';
  aHeader[2]  = 'Nome Arquivo';
  aHeader[3]  = 'Observações';
  aHeader[4]  = 'Opções';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0]  = 'left';
  aAligns[1]  = 'left';
  aAligns[2]  = 'left';
  aAligns[3]  = 'left';
  aAligns[4]  = 'center';
  
  oDBGrid.setCellAlign(aAligns);
  oDBGrid.show($('arquivosIncluidos'));
  oDBGrid.clearAll(true);
  
  oDBGrid.showColumn(false, 1);
  return oDBGrid;

}

function js_buscaArquivos() {

  var iAtoLegal = $('ed05_i_codigo').value;

  if (iAtoLegal.trim() != '') {

    var oParam       = new Object();

    oParam.exec      = 'getAnexosAtoLegal';
    oParam.iAtoLegal = iAtoLegal;

    sUrl             = 'edu4_escola.RPC.php';

    js_webajax(oParam, "js_retornoBuscaArquivos", sUrl);

  } else {
    alert('Nenhum Ato Legal selecionado!');
    return false;
  }

}

function js_retornoBuscaArquivos(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {
    
    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    var iTamResultado = oRetorno.aResultado.length;
    
    if (parseInt(iTamResultado, 10) > 0) {

      iQuantArquivos = oRetorno.aResultado.length;

      var aLinha = new Array();
      oDBGridArquivos.clearAll(true);
      oDBGridArquivos.renderRows();

      for (var iCont = 0; iCont < oRetorno.aResultado.length; iCont++) {
        
        sOpcoes  = "<span onclick='js_alterarAnexo("+oRetorno.aResultado[iCont].ed292_sequencial+","+
                                                     oRetorno.aResultado[iCont].ed292_atolegal+");'> A </span>";
        sOpcoes += "<span onclick='js_excluirAnexo("+oRetorno.aResultado[iCont].ed292_sequencial+","+
                                                     oRetorno.aResultado[iCont].ed292_atolegal+");'> E </span>";

        sLinkDownload = "<span onclick='js_downloadFile("+oRetorno.aResultado[iCont].ed292_sequencial+");'>"+
                         oRetorno.aResultado[iCont].ed292_nomearquivo.urlDecode()+"</span>";

        aLinha[0] = oRetorno.aResultado[iCont].ed292_ordem;
        aLinha[1] = oRetorno.aResultado[iCont].ed292_sequencial;
        aLinha[2] = sLinkDownload;
        aLinha[3] = oRetorno.aResultado[iCont].ed292_obs.urlDecode().substring(0, 20)+"...";
        aLinha[4] = sOpcoes;

        oDBGridArquivos.addRow(aLinha);

      }

      oDBGridArquivos.renderRows();

    } else {

      oDBGridArquivos.clearAll(true);
      oDBGridArquivos.renderRows();

    }

  }

}

function js_excluirAnexo(iAnexo, iAtoLegal) {

  if (<?=($db_opcao == 3 ? 'false' : 'true')?>) {
    location.href = "edu1_edu_anexoatolegal003.php?iAnexo="+iAnexo+"&iAtoLegal="+iAtoLegal;
  }

}

function js_alterarAnexo(iAnexo, iAtoLegal) {

  if (<?=($db_opcao == 3 ? 'false' : 'true')?>) {
    location.href = "edu1_edu_anexoatolegal002.php?iAnexo="+iAnexo+"&iAtoLegal="+iAtoLegal;
  }

}

function js_valida() {

  var sArquivo = $('ed292_arquivo').value;
  
  if ($('db_opcao').value == "Alterar" || $('db_opcao').value == "Excluir") {
    return true;
  }
  if (sArquivo.trim() == "") {

    alert("Escolha o arquivo do Ato Legal antes de incluir!");
    $('ed292_arquivo').focus();
    return false;

  }

  return true;

}

function js_ordenarCampos() {

  js_OpenJanelaIframe('', 
                      'db_iframe_ordenar', 
                      'func_ordenarAnexosAtoLegal.php?iCodAtoLegal='+$('ed05_i_codigo').value,
                      'Ordenar Arquivos', 
                      true);

}

function js_cancelaAcao() {

  location.href = "edu1_edu_anexoatolegal001.php?chavepesquisa="+$('ed05_i_codigo').value;

}

function js_novoRegistro() {

  parent.document.formaba.a2.disabled = true;
  top.corpo.iframe_a2.location.href   = 'edu1_edu_anexoatolegal001.php';
  top.corpo.iframe_a1.location.href   = 'edu1_atolegal001.php';
  location.href                       = 'edu1_atolegal001.php';
  parent.mo_camada('a1');

}

function js_downloadFile(iAnexo) {

  var oParam    = new Object();

  oParam.exec   = "getDownloadAnexoAtoLegal";
  oParam.iAnexo = iAnexo;

  var sUrl      = "edu4_escola.RPC.php";

  js_webajax(oParam, 'js_retornoDownloadFile', sUrl);

}

function js_retornoDownloadFile(oRetorno) {

  var oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {
                        
    jan = window.open('db_download.php?arquivo='+oRetorno.sArquivo.urlDecode(), '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

  }

}

</script>

<?
  if (isset($chavepesquisa)) {
    echo("<script>\n js_buscaArquivos(); \n</script>");
  }
?>