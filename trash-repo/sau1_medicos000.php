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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oCriaAbas = new cl_criaabas;
if ($db_opcao==1) {
  $sArquivo = "sau1_medicos001.php";
} elseif ($db_opcao==22) {
  $sArquivo = "sau1_medicos002.php";
} elseif ($db_opcao==33) {
  $sArquivo = "sau1_medicos003.php";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<form name="formaba">
<table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="100%">&nbsp;</td>
  </tr>
</table>
<table marginwidth="0" width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
      <?
      $oCriaAbas->identifica    = array('a1' => 'Dados Pessoais',
                                        'a2' => 'Outros Dados',
                                        'a3' => 'Vínculo',
                                        'a4' => 'Horários',
                                        'a5' => 'Ausências',
                                        'a6' => 'Procedimentos'
                                       );
      $oCriaAbas->src           = array('a1' => $sArquivo,
                                        'a2' => 'pro1_cgmdoc001.php',
                                        'a3' => 'sau1_unidademedicos001.php',
                                        'a4' => '',
                                        'a5' => '',
                                        'a6' => ''
                                       );
      $oCriaAbas->sizecampo     = array('a1' => 20, 'a2' => 20, 'a3' => 20, 'a5' => 20, 'a5' => 20, 'a6' => 20);
      $oCriaAbas->disabled      = array('a2' => 'true', 'a3' => 'true', 'a4' => true, 'a5' => true, 'a6' => true);
      $oCriaAbas->scrolling     = 'no';
      $oCriaAbas->iframe_height = '600';
      $oCriaAbas->iframe_width  = '100%';
      $oCriaAbas->cria_abas();
      ?>
    </td>
  </tr>
</table>
</form>
<?
db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), 
        db_getsession('DB_anousu'),db_getsession('DB_instit')
       );
?>
</body>
</html>