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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libsys.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
include("dbagata/classes/core/AgataAPI.class");
include("classes/db_db_relatorio_classe.php");
include("model/dbColunaRelatorio.php");
include("model/dbFiltroRelatorio.php");
include("model/dbVariaveisRelatorio.php");
include("model/dbGeradorRelatorio.model.php");
include("model/dbOrdemRelatorio.model.php");
include("model/dbPropriedadeRelatorio.php");

$oGet   		   		 = db_utils::postMemory($_GET);
$cldb_relatorio    = new cl_db_relatorio();

$rsRelatorio = $cldb_relatorio->sql_record($cldb_relatorio->sql_query_file($oGet->iCodRelatorio,"db63_nomerelatorio"));
$oRelatorio  = db_utils::fieldsMemory($rsRelatorio,0);

$sHtml  = " <html> ";
$sHtml .= " <head> ";
$sHtml .= " <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title> ";
$sHtml .= " <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"> ";
$sHtml .= " <meta http-equiv=\"Expires\" CONTENT=\"0\"> ";
$sHtml .= " <script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/scripts.js\"></script> ";
$sHtml .= " <script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/geradorrelatorios.js\"></script> ";
$sHtml .= " <script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/prototype.js\"></script> ";
$sHtml .= " <script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/strings.js\"></script> ";
$sHtml .= " <script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/json2.js\"></script>";
$sHtml .= " <link href=\"estilos.css\" rel=\"stylesheet\" type=\"text/css\"> ";
$sHtml .= " </head> ";
$sHtml .= " <body bgcolor=#CCCCCC leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" onLoad=\"a=1\" > ";

if (!isset($lEsconderMenus) || !$lEsconderMenus) {
  $sHtml .= " <table width=\"790\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#5786B2\">  ";
  $sHtml .= "   <tr> ";
  $sHtml .= "     <td width=\"360\" height=\"18\">&nbsp;</td> ";
  $sHtml .= "     <td width=\"263\">&nbsp;</td> ";
  $sHtml .= "     <td width=\"25\">&nbsp;</td>  ";
  $sHtml .= "     <td width=\"140\">&nbsp;</td> ";
  $sHtml .= "   </tr>  ";
  $sHtml .= " </table> ";
}
$sHtml .= " <table width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> ";
$sHtml .= "   <tr> ";
$sHtml .= "     <td height=\"100%\" align=\"center\" valign=\"top\" bgcolor=\"#CCCCCC\"> ";
$sHtml .= "     <center> ";
$sHtml .= "       <form name=\"frmFiltros\" method=\"post\" action=\"\"> ";
$sHtml .= "       <center> ";
$sHtml .= "       <table width=\"790\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" > ";
$sHtml .= "       <tr> ";
$sHtml .= "         <td width=\"360\" height=\"18\">&nbsp;</td> ";
$sHtml .= "         <td width=\"263\">&nbsp;</td> ";
$sHtml .= "         <td width=\"25\">&nbsp;</td> ";
$sHtml .= "         <td width=\"140\">&nbsp;</td> ";
$sHtml .= "       </tr> ";
$sHtml .= "       </table> ";
$sHtml .= "       <table border=0 align=\"center\"> ";
$sHtml .= "   <tr> ";
$sHtml .= "   <td> ";
$sHtml .= "   <fieldset> ";
$sHtml .= "       <legend><b>{$oRelatorio->db63_nomerelatorio}</b></legend> ";
$sHtml .= "   <div> ";
$sHtml .= "   <table width=100% border=0> ";

$oGeradorRelatorio = new dbGeradorRelatorio($oGet->iCodRelatorio);

foreach ($oGeradorRelatorio->aVariaveis as $oVariavel) {

  $sHtml .= "  <tr> ";
  $sHtml .= "     <td title=\"".$oVariavel->getLabel()."\" align=\"left\"> ";
  $sHtml .= "       <b> ".$oVariavel->getLabel()." : </b> ";
  $sHtml .= "     </td> ";
  $sHtml .= "     <td> ";

  ob_start();
  switch ($oVariavel->getTipoDado()) {

    case 'date':
      db_inputdata(str_replace('$','',$oVariavel->getNome()),"","","",true,'text',1,"");
      break;
    case 'int4':
      db_input(str_replace('$','',$oVariavel->getNome()),10,"1",true,"text",1,"");
      break;
    case 'float8':
      db_input(str_replace('$','',$oVariavel->getNome()),10,"4",true,"text",1,"");
      break;
    case 'varchar':
      db_input(str_replace('$','',$oVariavel->getNome()),50,"0",true,"text",1,"");
      break;
    case 'bool':
      $aTipos = array("t" => "Sim",
                      "f" => "Não" );
      db_select(str_replace('$','',$oVariavel->getNome()),$aTipos,true,1,"");
      break;

  }

  $sHtml .= ob_get_contents();
  ob_end_clean();

  $sHtml .= "     </td> ";
  $sHtml .= "   </tr> ";

}

$sHtml .= "         </table> ";
$sHtml .= "        </fieldset> ";
$sHtml .= "         </td> ";
$sHtml .= "         </tr> ";
$sHtml .= "         </table> ";
$sHtml .= "         <input name=\"processar\" type=\"button\" id=\"processar\" value=\"Processar\" onclick=\"js_processar();\" >";
$sHtml .= "         </center> ";
$sHtml .= "       </form> ";
$sHtml .= "     </center> ";
$sHtml .= "   </td> ";
$sHtml .= "   </tr> ";
$sHtml .= " </table> ";

echo $sHtml;

if (!isset($lEsconderMenus) || !$lEsconderMenus) {
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}

$sHtml  = " <script> ";
$sHtml .= "  function js_processar(){  ";
$sHtml .= "    var aParametros   = new Array(); ";
$sHtml .= "    var oFormElements = document.frmFiltros; ";
$sHtml .= "    for(var i=0; i < oFormElements.length; i++) { ";
$sHtml .= "      var obj = oFormElements.elements[i]; ";
$sHtml .= "      if ( obj.type == 'text' || obj.type == 'select-one' ) { ";
$sHtml .= "         var sVariavel   = obj.value ; ";
$sHtml .= "         var sDescricao  = '$'+obj.name; ";
$sHtml .= "         var objVariavel = new js_criaObjetoVariavel(sDescricao,sVariavel);";
$sHtml .= "         aParametros.push( objVariavel ); ";
$sHtml .= "      } ";
$sHtml .= "    } ";
$sHtml .= "    js_imprimeRelatorio({$oGet->iCodRelatorio},js_downloadArquivo,Object.toJSON(aParametros)); ";
$sHtml .= "  } ";
$sHtml .= " </script> ";
$sHtml .= " </body> ";
$sHtml .= " </html> ";

echo $sHtml;