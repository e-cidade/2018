<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordo_classe.php");
require_once("classes/db_acordomovimentacao_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clacordo             = new cl_acordo;
$clacordomovimentacao = new cl_acordomovimentacao;
$db_opcao             = 1;

$clacordo->rotulo->label();
$clacordomovimentacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
$clrotulo->label("ac10_obs");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php   
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js");
  db_app::load("estilos.css, grid.style.css");
  
  $oTemplates      = new cl_db_documentotemplate();
  $sCamposTemplate = "db82_sequencial, db82_descricao";
  $sWhereTemplate  = "db82_templatetipo = 45"; 
  $sSqlTemplates   = $oTemplates->sql_query( null, $sCamposTemplate , null, $sWhereTemplate);
  $rsModelo        = $oTemplates->sql_record($sSqlTemplates);
  
?>
<style>
td {
  white-space: nowrap;
}

fieldset table td:first-child {
  width: 80px;
  white-space: nowrap;
}

#iModelo {

  width: 93px;
}
#iModelodescr {
  width: 300px;;
}

</style>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <form >
  <center>
      <fieldset style="margin-top: 30px; width: 400px; ">
        <legend><strong>Relatório de Extrato do Acordo</strong></legend>
        
	      <table align="left" border="0">
	      
	        <tr>
	          <td title="<?=@$Tac16_sequencial?>" align="left">
	            <?php db_ancora($Lac16_sequencial, "js_pesquisaac16_sequencial(true);",$db_opcao); ?>
	          </td>
	          <td align="left">
	            <?php
                db_input('ac16_sequencial',10,$Iac16_sequencial,true,'text',
                         $db_opcao," onchange='js_pesquisaac16_sequencial(false);'");

                db_input('ac16_resumoobjeto',40,$Iac16_resumoobjeto,true,'text',3);
              ?>
	          </td>
	        </tr>
	        
	        <tr>
	          <td title="<?=@$Tac16_sequencial?>" align="left">
	            <strong>Modelo:</strong>
	          </td>
	          <td align="left">
	            <?php
	              db_selectrecord('iModelo', $rsModelo, true, 1);
              ?>
	          </td>
	        </tr>	        
	        
	        
	      </table>
	      
      </fieldset>
      
      <div style="margin-top: 10px;">
        <input id="gerar" name="gerar" type="button" value="Gerar Relatório" onclick="js_emitir();">
      </div>
            
  </center>
  </form>
      
<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

</body>
<script>

var   sUrl = 'con4_contratosmovimento.RPC.php';
const CAMINHO_MENSAGEM = 'patrimonial.contratos.con2_extratoacordos002.';


function js_emitir(){

  var iAcordo           = $F('ac16_sequencial');
  var iModelo           = $F('iModelo');
  var sArquivoRelatorio = "con2_extratoacordos003.php";
  
  if ( iAcordo == '' ) {
    alert( _M(CAMINHO_MENSAGEM + 'contrato_branco') ); return false;
  };

  if ( iModelo == '' ) {
    aler( _M(CAMINHO_MENSAGEM + 'sem_modelo_selecionado') );
  };

  var sQuery  = '?iAcordo=' + iAcordo;
      sQuery += '&iModelo=' + iModelo;

  jan = window.open(sArquivoRelatorio + sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
  
  
  
}



/**
 * Pesquisa acordos
 */
function js_pesquisaac16_sequencial(lMostrar) {

  if (lMostrar == true) {
    
    var sUrl = 'func_acordo.php?funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_resumoobjeto&iTipoFiltro=4';
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_acordo', 
                        sUrl,
                        'Pesquisar Acordo',
                        true);
  } else {
  
    if ($('ac16_sequencial').value != '') { 
    
      var sUrl = 'func_acordo.php?descricao=true&iTipoFiltro=4&pesquisa_chave='+$('ac16_sequencial').value+
                 '&funcao_js=parent.js_mostraacordo';
                 
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_acordo',
                          sUrl,
                          'Pesquisar Acordo',
                          false);
     } else {
       $('ac16_sequencial').value = ''; 
     }
  }
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraacordo(chave1,chave2,erro) {
 
  if (erro == true) {
   
    $('ac16_sequencial').value   = ''; 
    $('ac16_resumoobjeto').value = chave1;
    $('ac16_sequencial').focus(); 
  } else {
  
    $('ac16_sequencial').value   = chave1;
    $('ac16_resumoobjeto').value = chave2;
  }
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraacordo1(chave1,chave2) {

  $('ac16_sequencial').value    = chave1;
  $('ac16_resumoobjeto').value  = chave2;
  db_iframe_acordo.hide();
}



</script>
</html>
