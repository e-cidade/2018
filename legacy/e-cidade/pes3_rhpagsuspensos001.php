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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");
require_once ("classes/db_rhsuspensaopag_classe.php");
require_once ("classes/db_rhpessoal_classe.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
$lblRelatorio = "Processar";

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load ( "scripts.js, strings.js, prototype.js,datagrid.widget.js, estilos.css, grid.style.css" );
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
db_menu ( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) );
?>
<center>
<table style="top: 60px; position: relative;">
	<tr>
		<td valign="middle" align="center"><strong>Tipo de Folha: </strong>
<?
$aOpcoes = array( 
                  ''            => 'Selecione',
                  'gerfsal'     => 'Salário',
                  'gerfcom'     => 'Complementar',
                  'gerfres'     => 'Rescisão',
                  'gerfs13'     => '13º Salario',
                  'gerfadi'     => 'Adiantamento',
                  'gerfprovfer' => 'Provisão de Férias',
                  'gerfprovs13' => 'Provisão 13º Salário'
                );

db_select("sTipoFolha", $aOpcoes, "",1);
echo "<BR><BR>"; 
db_input ( "lblRelatorio", "20", "", "", "submit", 1, "onClick='return js_abrejanela();'" );
?> 
</td>
	</tr>
</table>
</center>
</body>
</html>
<script>
function js_abrejanela(){
 
  if($('sTipoFolha').getValue() == ""){
  
    alert("Tipo de Folha não selecionado");
    $('sTipoFolha').focus();
  }
  else{
     oJanela = window.open("pes3_rhpagsuspensos002.php?sTipoFolha="+$('sTipoFolha').getValue(),"","width="+(screen.availWidth-5)+",height="+(screen.availHeight-40)+",scrollbars=1,location=0 ");
     oJanela.moveTo(0,0);
     return true;
  }
}
</script>