<?
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

/**
* Este arquivo pertence a rotina de geração do livro de controlados.
* Neste é encontrado o Formulário HTML e todas as funcionalidade deste.
* 
* @access 	  public
* @author     Adriano Quilião de Oliveira <adriano.oliveira@dbseller.com.br> 
* @copyright  GNU - LICENÇA PÚBLICA GERAL GNU - Versão 2, junho de 1991. 
* @version	  2.0
* @magic	  phpdoc.de compatibility
* @todo		  phpdoc.de compatibility
* @package	  Saude
* @subpackage Farmacia
*/
require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$oDaoModeloLivro = db_utils::getdao('far_modelolivro');
$oDaoFechaLivro  = db_utils::getdao('far_fechalivro');
$oDaoFechaLivro->rotulo->label();
$fa26_i_login = DB_getsession("DB_id_usuario");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" >
    <center>
      <table width="100%"  bgcolor="#CCCCCC">
        <tr>
          <td align="center" valign="top">
            <fieldset style="width:50; margin-top: 40px; margin-bottom: 8px;">
              <legend><b>Livro dos Controlados</b></legend>
              <table width="100%" style="margin: 6px;">
                <form name="form1" method="post" action="" >
                  <tr>
                    <td nowrap title="<?=@$Tfa26_i_codigo?>">
                      <?=@$Lfa26_i_codigo?>
                    </td>
                    <td> 
                      <?db_input('fa26_i_codigo', 10, @$Ifa26_i_codigo, true, 'text', 3, "")?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>Período:</b>
                    </td>
                    <td>
                      <?
                      db_inputdata('fa26_d_dataini', @$fa26_d_dataini_dia, @$fa26_d_dataini_mes, @$fa26_d_dataini_ano,
                                   true, 'text', "", ""
                                  );
                      ?>
                      &nbsp;&nbsp;
                      <b>Até</b>
                      &nbsp;&nbsp; 
                      <?
                      db_inputdata('fa26_d_datafim', @$fa26_d_datafim_dia, @$fa26_d_datafim_mes, @$fa26_d_datafim_ano,
                                   true, 'text', "", ""
                                  );
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>Livro:</b>
                    </td>
                    <td> 
                      <?             
                      $sSql      = $oDaoModeloLivro->sql_query("", "fa16_i_codigo, fa16_c_livro", "fa16_c_livro");
                      $rsModelos = $oDaoModeloLivro->sql_record($sSql);
                      db_selectrecord("fa16_i_codigo", $rsModelos, "", "", "", "", "", "  ", "", 1);
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>Ordem:</b>
                    </td>
                    <td>
                      <?db_select('ordem', array('R' => 'REMÉDIO', 'D' => 'DATA'), true, 2, "");?>
                    </td>
                  </tr>
                </table>
              </fieldset>
              <table>
                <tr>
                  <td align="center" colspan="3">           
                    <input name="emitir" type="button" id="emitir" value="Emitir Livro"   onClick= "js_emitir();">
                    <input name="livro" type="button" id="livro" value="Mostrar Livros" onClick="js_pesquisa();">
                    <input name="termo" type="button" id="termo" value="Termo de Abertura"  onClick="js_termo();" disabled="true">
                  </td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
      </table>
    </center>
  </body>
</html>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
/**
 * Função que abre a janela de pesquisa para que o usuário escolha o livro que reseja reemitir.
 *
 * @param {}
 * @return {}
 */
function js_pesquisa(){

  js_OpenJanelaIframe('', 'db_iframe_far_fechalivro', 
		              'func_far_fechalivro.php?fa16_i_codigo=' +
		                 document.form1.fa16_i_codigo.value +
		                 '&funcao_js=parent.js_mostraFechaLivro|fa26_i_codigo|fa26_d_dataini|fa26_d_datafim|fa16_i_codigo',
		              'Pesquisa', true
		             );

}

function js_mostraFechaLivro(iCodigo, dDataInicial, dDataFinal, iCodigoLivro) {

  var aDataIni = dDataInicial.split('-');
  var aDataFim = dDataFinal.split('-');
  $('fa26_i_codigo').value  = iCodigo;
  $('fa26_d_dataini').value = aDataIni[2] + '/' + aDataIni[1] + '/' + aDataIni[0];
  $('fa26_d_datafim').value  = aDataFim[2] + '/' + aDataFim[1] + '/' + aDataFim[0];
  $('fa16_i_codigo').value  = iCodigoLivro;
  db_iframe_far_fechalivro.hide();

}

/**
 * Responsável por gerar o relatório do livro de controlados.
 *
 * @param {}
 * @return {}
 */
function js_emitir() {  

  if ( !valida_dados() ) {

    alert("Preencha os campos obrigatórios: Período e Livro");
    return false;
  }

    var sLink           = 'far4_livrocontrolado002.php?';
    sLink              += 'fa26_d_dataini=' + $F('fa26_d_dataini');
    sLink              += '&fa26_d_datafim=' + $F('fa26_d_datafim') + '&livro=' + $F('fa16_i_codigo'); 
    sLink              += '&ordem=' + $('ordem').value;
    $('termo').disabled = false;
    var oJan            = window.open(sLink, '', 'width=' + (screen.availWidth-5) + 
	 	                                       ',height=' + (screen.availHeight-40) + 
	 	                                       ',scrollbars=1,location=0 '
	 	                           );       
    oJan.moveTo(0,0);   
  

}

 /**
  * Responsável por gerar o termo de abertura do livro de controlados.
  *
  * @param {}
  * @return {}
  */
function js_termo() {
	
  var sLink = 'far2_termoabertura002.php?livro=' + $F('fa16_i_codigo') + '&fa26_i_codigo=' + $F('fa26_i_codigo');
  var oJan  = window.open(sLink, '', 'width=' + (screen.availWidth-5) + 
		                             ',height=' + (screen.availHeight-40) + 
		                             ',scrollbars=1,location=0 '
		                 );    
  jan.moveTo(0,0);  
  location.href = 'far4_livrocontrolado001.php'; 
 
}

/**
 * Valida se o período inicial e final foram preenchidos e se algum livro foi selecionado
 * @return {boolean}
 */
function valida_dados() {

  if ( $F('fa26_d_dataini') == '') {
    return false;  
  }

  if ( $F('fa26_d_datafim') == '' ) {
    return false;  
  }

  if ( $F('fa16_i_codigo') == ' ' ) {
    return false;
  }

  return true;
}

</script>