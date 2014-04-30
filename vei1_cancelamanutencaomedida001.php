<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_veicmanutencaomedida_classe.php");
require_once("dbforms/db_funcoes.php");

$oRotuloCancManutencao = new rotulocampo;
$oRotuloCancManutencao->label("ve67_sequencial");
$oRotuloCancManutencao->label("ve67_veicmanutencaomedida");
$oRotuloCancManutencao->label("ve67_usuario");
$oRotuloCancManutencao->label("ve67_motivo");
$oRotuloCancManutencao->label("ve67_data");
$oRotuloCancManutencao->label("ve67_hora");

$iDataAtual = date('d/m/Y', db_getsession('DB_datausu'));
$iUsuario   = db_getsession('DB_id_usuario');
$sHoraAtual = date('H:i');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" src="scripts/strings.js"  ></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top: 25px;">

<center>
  <form id="form1" name="form1" method="post" action="">
    <fieldset style="width: 400px;">
      <legend><strong>Cancela Manutenção de Medida</strong></legend>
      <?
        db_input("iUsuario", 10, '', true, 'hidden', 3);
        db_input("iVeiculo", 10, '', true, 'hidden', 3);
      ?>
      <table width="100%">
        <tr style="display: none;">
          <td nowrap="nowrap" title="<?=$Tve67_sequencial;?>" width="100">
            <b><?=$Lve67_sequencial;?></b>
          </td>
          <td>
            <?
              db_input("iSequencial", 8, '', true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title="<?=$Tve67_veicmanutencaomedida;?>">
            <b><?=db_ancora($Lve67_veicmanutencaomedida, 'js_pesquisar(true)', 1);?></b>
          </td>
          <td>
            <?
              db_input("iManutencao", 8, '', true, 'text', 1, "onchange='js_pesquisar(false);'");
              db_input("sPlacaVeiculo", 25, '', true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title="<?=$Tve67_data?>">
            <b><?=$Lve67_data?></b>
          </td>
          <td>
            <?
              db_input("sData", 8, '', true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title="<?=$Tve67_hora;?>">
            <b><?=$Lve67_hora;?></b>
          </td>
          <td>
            <?
              db_input("sHora", 8, '', true, 'text', 3);
            ?>
          </td>
        </tr>
          
        <tr>
          <td colspan="2">
            <fieldset>
              <legend><b>Motivo</b></legend>
              
              <?
                db_textarea("sMotivo", 5, 50, '', true, 'text', 1);
              ?>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
    
    <br>
    <input type="button" name="btnSalvar" id="btnSalvar" value="Salvar">&nbsp;
    <input type="button" name="btnPesquisar" id="btnPesquisar" value="Pesquisar">
    
  </form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>


<script>
  $("sData").value    = '<?=$iDataAtual;?>';
  $("sHora").value    = '<?=$sHoraAtual;?>';
  $("iUsuario").value = <?=$iUsuario;?>;

  function js_pesquisar(lMostra) {
    
    if (lMostra) {
      js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veicmanutencaomedida.php?funcao_js=parent.js_preencheManutencao|ve66_sequencial|ve01_placa|ve66_veiculo','Pesquisa',true);
    } else {
      
      if ($("iManutencao").value != "") {
        js_OpenJanelaIframe('top.corpo','db_iframe_veiculos',
                            'func_veicmanutencaomedida.php?pesquisa_chave='+$F("iManutencao")+'&funcao_js=parent.js_completaManutencao',
                            'Pesquisa',false);
      }
    }
  
  }
  
  function js_preencheManutencao(iSequencial, sPlaca, iVeiculo) {
  
    $("iManutencao").value   = iSequencial;
    $("sPlacaVeiculo").value = sPlaca;
    $("iVeiculo").value      = iVeiculo;
    db_iframe_veiculos.hide();
  }
  
  function js_completaManutencao(sPlaca, iCodVeiculo, lErro) {

    if (!lErro) {
    
      $("sPlacaVeiculo").value = sPlaca;
      $("iVeiculo").value      = iCodVeiculo;
    } else {
      
      $("iManutencao").value   = "";
      $("sPlacaVeiculo").value = sPlaca;
    }
  
  }
  
  $("btnPesquisar").observe("click", function() {
    js_pesquisar(true);
  });


  $("btnSalvar").observe("click", function() {

    if ($("iManutencao").value == "") {

      alert("Informe a manutenção.");
      return false;
    }

    if ($("sMotivo").value == "") {
      
      alert("Campo motivo obrigatório!");
      return false;
    }
    
    var oParam         = new Object();
    oParam.exec        = "salvarDadosManutencao";
    oParam.iManutencao = $("iManutencao").value;
    oParam.sMotivo     = $("sMotivo").value;
    oParam.sHora       = $("sHora").value;
    oParam.sData       = $("sData").value;
    oParam.iVeiculo    = $("iVeiculo").value;
    
    js_divCarregando("Aguarde, processando...", "msgBox");
    
    var oAjax     = new Ajax.Request("vei4_manutencaomedida.RPC.php",
                                      {method: 'post',
                                       parameters: 'json='+Object.toJSON(oParam), 
                                       onComplete: function(oAjax) {
                                         
                                         js_removeObj("msgBox");
                                         var aRetorno = eval("("+oAjax.responseText+")");
                                         alert(aRetorno.message.urlDecode());
                                         js_limpaCampos();
                                       }
                                      }) ;
  });


  function js_limpaCampos() {
  
    $("iUsuario").value      = "";
    $("iVeiculo").value      = "";
    $("sMotivo").value       = "";
    $("iManutencao").value   = "";
    $("sPlacaVeiculo").value = "";
  }
</script>


</body>
</html>