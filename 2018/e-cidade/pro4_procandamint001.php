<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_protprocesso_classe.php"));
require_once(modification("classes/db_procvar_classe.php"));
require_once(modification("classes/db_proctipovar_classe.php"));
require_once(modification("classes/db_db_syscampo_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
$clprotprocesso = new cl_protprocesso;
$rotulo = new rotulocampo();
$rotulo->label("p58_codproc");
$rotulo->label("p58_numero");
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
  <script>
    function js_processa(){
      if (document.form1.p58_numero.value!=""){
            
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_despint','func_procdespint.php?grupo=1&pesquisa_chave='+document.form1.p58_numero.value+'&funcao_js=parent.js_mudapagina','Pesquisa',true);
      }else{
        alert("Informe o Cod. do Processo!!");
        document.form1.p58_numero.focus();
      }
    }

    function js_mudapagina(sNumeroProcesso, iNumCgm, sNome, lErro){

      var iCodigoProcesso = document.form1.p58_codproc.value;

      /**
       * Erro na consulta 
       */
      if ( lErro ) {

        alert("Digite um codigo de processo valido!!");
        document.form1.p58_numero.value="";
        document.form1.p58_numero.focus();
        return false;
      }

      location.href="pro4_procandamintabas001.php?p58_codproc=" + iCodigoProcesso
    }
  </script>
</head>
<body style="background-color: #CCCCCC; margin-top: 30px;" >
<div id="ctnInclusaoDespacho" align="center">
  <form name="form1">
    <fieldset style="width: 600px;">
    <legend><b>Incluir Despacho</b></legend>
      <table>
      	<tr>
          <td title="<?=$Tp58_codproc?>">
            <? db_ancora("Processo:","js_pesquisa(true);",1); ?>
          </td>
          <td>
            <?php db_input("p58_numero", 15, $Ip58_numero, true, "text", 2, "onchange='js_pesquisa(false)'"); ?>
            <?php db_input("p58_codproc", 30, 0, true, "hidden", 1); ?>
          </td>
          <td> 
            <?db_input("p58_requer",40,$Ip58_codproc,true,"text",3);?>
          </td>
        </tr>
      </table>
    </fieldset>
    <p align="center">
      <input type="button" value="Processar" id="btnProcessar">
    </p>
  </form>
</div>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>

$("btnProcessar").disabled = true;

function js_pesquisa(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_despint','func_procdespint.php?grupo=1&funcao_js=parent.js_mostra1|dl_cod_processo|dl_processo|dl_nome_ou_Razão_social','Pesquisa',true);
  }else{
     if(document.form1.p58_numero.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_despint',
          'func_procdespint.php?grupo=1&pesquisa_chave='+document.form1.p58_numero.value+'&funcao_js=parent.js_mostra',
          'Pesquisa',false);
     }else{
     }
  }
}
//

$("btnProcessar").observe("click", function() {

  if ($F("p58_numero") == "") {
    alert("Selecione um processo."); return false;
  }
  location.href="pro4_procandamintabas001.php?p58_codproc="+$F("p58_codproc");
});


function getCodigoProcesso() {

  js_divCarregando("Aguarde, carregando dados do processo...", "msgBox");

  var oParam             = new Object();
  oParam.exec            = "getDadosProcessoProtocolo";
  oParam.sNumeroProcesso = $F("p58_numero");

  new Ajax.Request("prot4_processoprotocolo004.RPC.php",
                   {method: 'post', 
                    parameters: 'json='+Object.toJSON(oParam),
                    async: false,
                    onComplete: function (oAjax) {

                      js_removeObj("msgBox");
                      var oRetorno = eval("("+oAjax.responseText+")");
                      if (!oRetorno.lErro) {
                        
                        $("p58_codproc").value = oRetorno.iSequencialProcesso;
                        //$("p58_requer").value = oRetorno.sRequerenteProcesso.urlDecode();
                      } else {

                        alert(oRetorno.sMensagem.urlDecode());
                        $("p58_numero").value  = '';
                        $("p58_codproc").value = '';
                        $("p58_requer").value  = '';
                      }
                   }});
};


function js_mostra(iCodigoProcesso, iNumCgm, sNome, lErro) {
 
  document.form1.p58_requer.value  = sNome;

  if ( lErro ) { 

    document.form1.p58_numero.focus(); 
    document.form1.p58_numero.value = '';     
    return false;
  }

  getCodigoProcesso();

  $("btnProcessar").disabled = false;
  document.form1.p58_requer.value  = sNome;
  return true;
}

function js_mostra1(iCodigoProcesso, iNumeroProecesso, sNome) {
  
  document.form1.p58_codproc.value = iCodigoProcesso;
  document.form1.p58_numero.value = iNumeroProecesso;
  document.form1.p58_requer.value = sNome;
  $("btnProcessar").disabled = false;
  db_iframe_despint.hide();
}
</script>