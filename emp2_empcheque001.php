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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_lote_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_empempenho_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clempempenho       = new cl_empempenho;
$aux                = new cl_arquivo_auxiliar;
$cllote             = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo           = new rotulocampo;

$clempempenho->rotulo->label();
$cllote->rotulo->label();
$clrotulo->label("z01_nome");

$iDia = date('d',db_getsession("DB_datausu"));
$iMes = date('m',db_getsession("DB_datausu"));
$iAno = date('Y',db_getsession("DB_datausu"));
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?   
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
.fieldsetinterno {
  border:0px;
  border-top:2px groove white;
  margin-top:10px;
}

td {
  white-space: nowrap
}

fieldset table td:first-child {
  width: 70px;
  white-space: nowrap
}

#ver, #considerar, #filtro, #totaliza, #quebrarpaginaconta {
  width: 100%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
      <form name="form1" method="post" action="emp2_empcheque002.php">
        <fieldset>
          <legend><b>Cheques Emitidos</b></legend>
          <table border="0" align="center" width="400px">
            <tr> 
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr> 
              <td>
                <strong>Opções:</strong>
              </td>
              <td>
                <?
                  $aVer = array("com" => "Com as contas selecionados",
                                "sem" => "Sem as contas selecionadas");
                  db_select('ver', $aVer, true, 4, "");
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <?
                  $aux->cabecalho      = "<strong>Contas</strong>";
                  $aux->codigo         = "e83_codtipo";
                  $aux->descr          = "e83_descr";
                  $aux->nomeobjeto     = 'lista';
                  $aux->funcao_js      = 'js_mostra';
                  $aux->funcao_js_hide = 'js_mostra1';
                  $aux->sql_exec       = "";
                  $aux->func_arquivo   = "func_empagetipo.php";
                  $aux->nomeiframe     = "db_iframe_cgm";
                  $aux->localjan       = "";
                  $aux->onclick        = "";
                  $aux->db_opcao       = 2;
                  $aux->tipo           = 2;
                  $aux->top            = 0;
                  $aux->linhas         = 10;
                  $aux->vwhidth        = 200;
                  $aux->funcao_gera_formulario();
                ?>    
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset class='fieldsetinterno'>
                  <legend><b>Datas</b></legend>
                  <table border="0">
                    <tr>
                      <td>
                        <b>De:</b>
                      </td>
                      <td>
                        <?
                          db_inputdata("dtini", $iDia, $iMes, $iAno, "true", "text", 2);
                        ?>
                      </td>
                      <td width="80px">&nbsp;&nbsp;
                        <b>Até:</b>
                      </td>
                      <td>
                        <?
                          db_inputdata("dtfim", $iDia, $iMes, $iAno, "true", "text", 2);
                        ?>
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset class='fieldsetinterno'>
                  <legend><b>Filtros</b></legend>
                  <table border="0">
				            <tr>
				              <td>
				                <strong>Considerar:</strong>
				              </td>
				              <td width="100%">
				                <?
				                  $aConsiderar = array("t" => "Todos",
				                                       "s" => "Autenticados",
				                                       "n" => "Não autenticados");
				                  db_select('considerar', $aConsiderar, true, 4, "");
				                ?>
				              </td>
				            </tr>
				            <tr>
				              <td>
				                <strong>Filtro:</strong>
				              </td>
				              <td>
				                <?
				                  $aFiltro = array("t" => "Todos",
				                                   "o" => "Ordem de pagamento",
				                                   "s" => "Slips");
				                  db_select('filtro', $aFiltro, true, 4, "");
				                ?>
				              </td>
				            </tr>
				            <tr>
				              <td>
				                <strong>Totaliza por Cheque:</strong>
				              </td>
				              <td>
				                <?
				                  $aTotaliza = array("t" => "Sim",
				                                     "f" => "Não");
				                  db_select('totaliza', $aTotaliza, true, 4, "");
				                ?>
				              </td>
				            </tr>
                    <tr>
                      <td>
                        <strong>Quebrar Página por Conta:</strong>
                      </td>
                      <td>
                        <?
                          $aQuebraPaginaConta = array("f" => "Não",
                                                      "t" => "Sim");
                          db_select('quebrarpaginaconta', $aQuebraPaginaConta, true, 4, "");
                        ?>
                      </td>
                    </tr>				            
                  </table>
                </fieldset>
              </td>
            </tr> 
          </table>
        </fieldset>
        <table align="center" border="0">
          <tr>      
            <td>
              <input type="button" name="relatorio" id="relatorio" value="Gerar Relatório" onClick="js_seleciona();">
            </td> 
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
var variavel = 1;
function js_seleciona() {

  for (i = 0; i < document.form1.length; i++) {
  
    if (document.form1.elements[i].name == "lista[]") {
    
      for (x = 0; x < document.form1.elements[i].length; x++) {
        document.form1.elements[i].options[x].selected = true;
      }
    }
  }
  
  var jan = window.open('',
                        'relatorio_cheques_emitidos' + variavel,
                        'width='+(screen.availWidth-5)+
                        ',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  
  document.form1.target = 'relatorio_cheques_emitidos' + variavel++;
  setTimeout("document.form1.submit()", 1000);
  return true;
}
</script>
</html>