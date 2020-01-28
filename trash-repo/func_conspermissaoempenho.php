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

require_once("libs/db_utils.php");
require_once("libs/db_stdlib.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_classesgenericas.php");
require_once("dbforms/db_funcoes.php");

$oGet  = db_utils::postMemory($_GET);

$clrotulo   = new rotulocampo;
$clrotulo->label("id_usuario");
$clrotulo->label("nome");
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");

$iIdUsuario = db_getsession('DB_id_usuario');
$iAnoUsu    = db_getsession('DB_anousu');
$iDptoUsu   = db_getsession('DB_coddepto');

if (isset($oGet->relpermempenho) && $oGet->relpermempenho = 't') {
	
	$sValueBotao   = "Imprimir";
	$sName         = "imprimir";
} else {
	
	$sValueBotao   = "Pesquisar";
	$sName         = "pesquisar";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="" target="">
<?
  db_input('listafiltros','',3,'','hidden');
  db_input('listainstit','',3,'','hidden');
  db_input('listausuarios','',3,'','hidden');
  db_input('listadptos','',3,'','hidden');
  db_input('anousu','',3,'','hidden');
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
			<fieldset>
			<legend><b>Relatório de Permissões de Empenhos</b></legend>
			  <table border="0" cellspacing="0" cellpadding="0" align="center">  
			    <tr> 
			      <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
			        <table border="0" align="center">
			          <tr> 
			            <td nowrap>
			              <table border="0">
			                <tr>
			                  <td>
			                    <fieldset>
			                      <?
			                        db_selinstit('',300,100);
			                      ?>      
			                    </fieldset>                 
			                  </td>
			                </tr>             
			              </table>      
			            </td>
			          </tr>
			          <tr>
			            <td nowrap>
			              <table border="0">
			                <tr>
			                  <td>
			                    <?
			                    
			                      $clauxiliarusuario = new cl_arquivo_auxiliar;
			                      $clauxiliarusuario->nome_botao              = "db_lanca_usuarios";
			                      $clauxiliarusuario->cabecalho               = "<strong>Usuarios Selecionados</strong>";
			                      $clauxiliarusuario->codigo                  = "id_usuario";
			                      $clauxiliarusuario->descr                   = "nome";
			                      $clauxiliarusuario->nomeobjeto              = 'usuarioSel';
			                      $clauxiliarusuario->funcao_js               = 'js_mostraUsuarioSel';
			                      $clauxiliarusuario->funcao_js_hide          = 'js_mostraUsuarioSel1';
			                      $clauxiliarusuario->sql_exec                = "";
			                      $clauxiliarusuario->func_arquivo            = "func_db_usuarios.php";
			                      $clauxiliarusuario->nomeiframe              = "iframe_usuariosel";
                            $clauxiliarusuario->localjan                = "";
                            $clauxiliarusuario->onclick                 = "";
                            $clauxiliarusuario->tipo                    = 2;
                            $clauxiliarusuario->top                     = 1;
                            $clauxiliarusuario->linhas                  = 10;
			                      $clauxiliarusuario->funcao_gera_formulario();  
			                      
			                    ?>                 
			                  </td>              
			                </tr>      
			              </table>
			            </td>
			            <td nowrap>
			              <table border="0">
			                <tr>
			                  <td>
			                    <?		                    
			                    
			                      $clauxiliardbpto = new cl_arquivo_auxiliar;
			                      $clauxiliardbpto->nome_botao              = "db_lanca_dptos";
			                      $clauxiliardbpto->cabecalho               = "<strong>Departamentos Selecionados</strong>";
			                      $clauxiliardbpto->codigo                  = "coddepto";
			                      $clauxiliardbpto->descr                   = "descrdepto";
			                      $clauxiliardbpto->nomeobjeto              = 'deptoSel';
			                      $clauxiliardbpto->funcao_js               = 'js_mostraDptoSel';
			                      $clauxiliardbpto->funcao_js_hide          = 'js_mostraDptoSel1';
			                      $clauxiliardbpto->sql_exec                = "";
			                      $clauxiliardbpto->func_arquivo            = "func_db_depart.php";
			                      $clauxiliardbpto->nomeiframe              = "iframe_departamentosel";
			                      $clauxiliardbpto->localjan                = "";
			                      $clauxiliardbpto->onclick                 = "";
			                      $clauxiliardbpto->tipo                    = 2;
			                      $clauxiliardbpto->top                     = 1;
			                      $clauxiliardbpto->linhas                  = 10;
			                      $clauxiliardbpto->funcao_gera_formulario(); 
			                      
			                    ?>                
			                  </td>
			                </tr>          
			              </table>    
			            </td>
			          </tr>        
			          <tr>
			            <td nowrap>
			              <b>Exercício:&nbsp;</b>
			              <?
			                $sSqlAnoExercicio  = " select distinct db20_anousu     "; 
			                $sSqlAnoExercicio .= " from db_permemp                 ";
			                $sSqlAnoExercicio .= " order by db20_anousu desc       "; 
			                $rsAnoExercicio    = db_query($sSqlAnoExercicio);
			                $iNumRows          = pg_num_rows($rsAnoExercicio);
			                
			                $aAnoExercicio = array();
			                if ($iNumRows > 0) {
			                  
			                  for ($iInd = 0; $iInd < $iNumRows; $iInd++) {
			                    
			                    $oAnoExercicio        = db_utils::fieldsMemory($rsAnoExercicio,$iInd);
			                    $aAnoExercicio[$oAnoExercicio->db20_anousu] = $oAnoExercicio->db20_anousu;  
			                  }               
			                }
			                
			                db_select('db20_anousu',$aAnoExercicio,true,4,"");
			              ?>          
			            </td>
			          </tr>
			        </table>
			      </td>
			    </tr>
			  </table>
			</fieldset>    
    </td>
  </tr>
  <tr>
    <table border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>
          <input type="button" id="botao" name="<?=$sName?>" value="<?=$sValueBotao?>" onClick="js_emite();">
        </td>
      </tr>
    </table>
  </tr>
</table>
</form>
</body>
<script>
function js_emite(){
   
  var sBotao         = document.form1.botao.value;
  var sListaInstit   = document.form1.db_selinstit.value;
  var sListaFiltros  = parent.iframe_filtros.js_atualiza_variavel_retorno();
  var iExercicio     = document.form1.db20_anousu.value;
  var sListaUsuarios = "";
  var sListaDptos    = "";
  
  var sVirgula = "";
  for (x = 0; x < document.form1.usuarioSel.length; x++) {
  
    sListaUsuarios += sVirgula+document.form1.usuarioSel.options[x].value;
    sVirgula = ",";
  }  
  
  var sVirgula = "";
  for (i = 0; i < document.form1.deptoSel.length; i++) {
  
    sListaDptos += sVirgula+document.form1.deptoSel.options[i].value;
    sVirgula = ",";
  }   
  
  var sQuery  = "?";
      sQuery += "listainstit="+sListaInstit;
      sQuery += "&listafiltros="+sListaFiltros;
      sQuery += "&listausuarios="+sListaUsuarios;
      sQuery += "&listadptos="+sListaDptos;
      sQuery += "&anousu="+iExercicio;   

  if (sBotao == 'Pesquisar') {

    var iTamListaFiltros = new Number(sListaFiltros.length);
    
    if (iTamListaFiltros > 8060) {
    
      var sMsg  = "Usuário: \n\n";
          sMsg += " Filtros selecionados não suportados!\n Filtros selecionados excedeu o tamanho limite.\n\n";
          sMsg += "Administrador: ";
      alert(sMsg);
      return false;
    }

    var sUrl = 'func_origempermissao.php'+sQuery
    js_OpenJanelaIframe('top.corpo.iframe_g1','db_iframe_origempermissao',sUrl,'Pesquisa Origem Permissão',true,'0',1); 
  } else {

    var variavel = 0;
        variavel++;
        
    document.form1.listainstit.value   = sListaInstit;
    document.form1.listafiltros.value  = sListaFiltros;
    document.form1.listausuarios.value = sListaUsuarios;
    document.form1.listadptos.value    = sListaDptos;
    document.form1.anousu.value        = iExercicio;

    var sUrl = 'con2_relpermempenho002.php';    
    var jan  = window.open("",'relempermissao','width='+(screen.availWidth-5)
                                                       +',height='+(screen.availHeight-40)
                                                       +',scrollbars=1,location=0 ');
        jan.moveTo(0,0); 
    
    document.form1.target = "relempermissao";
    document.form1.method = "post";
    document.form1.action = "con2_relpermempenho002.php";
    document.form1.submit();
  }   
}
</script>
</html>