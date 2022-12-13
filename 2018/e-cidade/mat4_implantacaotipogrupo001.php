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
 
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("model/configuracao/UsuarioSistema.model.php");

$oRotuloImplantacao = new rotulo('matimplantacaotipogrupo');
$oRotuloImplantacao->label();
$oUsuarioSistema = new UsuarioSistema(db_getsession("DB_id_usuario"));
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" style="margin-top: 25px;">
  <center>
    <form>
      <fieldset style="width: 500px;">
        <legend><b>Implantação do Tipo de Grupo</b></legend>
        <table width="100%">
          <tr>
            <td width="140px" nowrap="nowrap"><?=$Lm93_dataimplantacao;?></td>
            <td>
              <?php
                $m93_dataimplantacao = date("d/m/Y", db_getsession("DB_datausu"));
                db_input("m93_dataimplantacao", 8, $Im93_dataimplantacao, true, "text", 3);
              ?>
            </td>
          </tr> 
          <tr>
            <td><?=$Lm93_db_usuarios;?></td>
            <td>
              <?php
                $m93_db_usuarios = $oUsuarioSistema->getIdUsuario();
                $nome_usuario    = $oUsuarioSistema->getNome();
                db_input("m93_db_usuarios", 8, $Im93_db_usuarios, true, "text", 3);
                db_input("nome_usuario"   , 35, false, true, "text", 3);
              ?>
            </td>
          </tr> 
        </table>
      </fieldset>
      <br>
      <input type="button" name="btnProcessar" id="btnProcessar" value="Implantar" />
    </form>
  </center>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  var sUrlRPC = "mat4_implantacaotipogrupo.RPC.php";

  $('btnProcessar').observe('click', function() {

    var sMsgConfirm = "Confirma a implantação do tipo de grupo? Este procedimento não poderá ser desfeito.";
    if (!confirm(sMsgConfirm)) {
      return false;
    }

    js_divCarregando("Processando implantação, aguarde...", "msgBox");

    var oParam             = new Object();
    oParam.exec            = "processarImplantacaoTipoGrupo";
    oParam.id_usuario      = $F('m93_db_usuarios');
    oParam.dataimplantacao = $F('m93_dataimplantacao');

    var oAjax = new Ajax.Request(sUrlRPC,
                                   {method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam), 
                                   onComplete:js_concluirImplantacao});
  });

  function js_concluirImplantacao(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    alert(oRetorno.message.urlDecode());
    if (oRetorno.status == 1) {
      $('btnProcessar').disabled = true;
    }
  }
</script>