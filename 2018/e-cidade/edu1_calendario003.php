<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
db_postmemory($_GET);

$clcalendario        = new cl_calendario;
$clcalendario        = new cl_calendario;
$clturma             = new cl_turma;
$clperiodocalendario = new cl_periodocalendario;
$clferiado           = new cl_feriado;
$clcalendarioescola  = new cl_calendarioescola;
$clregencia          = new cl_regencia;

$clcalendario->rotulo->label();

$db_botao  = false;
$db_opcao  = 33;
$db_opcao1 = 3;

if ( isset( $chavepesquisa ) ) {
  
  $sWhereRegencia = " ed57_i_calendario = {$chavepesquisa} AND ed59_c_encerrada = 'S' AND ed59_c_condicao = 'OB'";
  $sSqlRegencia   = $clregencia->sql_query( "", "ed59_i_codigo", "", $sWhereRegencia );
  $result         = $clregencia->sql_record( $sSqlRegencia );
  
  $db_opcao  = 3;
  $db_opcao1 = 3;
  
  $result = $clcalendario->sql_record( $clcalendario->sql_query( $chavepesquisa ) );
  db_fieldsmemory( $result, 0 );
  
  $db_botao = true;
  
  if ( $clregencia->numrows > 0 ) {
    
    db_msgbox( "Calendário não pode ser mais excluído,\\npois já existem turmas encerradas vinculadas a este calendário!" );
    $db_botao = false;
  }
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
        <br>
        <center>
        <fieldset style="width:95%">
          <legend><b>Exclusão de Calendário</b></legend>
          <?include("forms/db_frmcalendario.php");?>
        </fieldset>
        </center>
      </td>
    </tr>
  </table>
</body>
</html>
<?
if ( $db_opcao == 33 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>