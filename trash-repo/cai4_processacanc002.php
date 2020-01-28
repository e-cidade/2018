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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cancdebitos_classe.php");
$clcancdebitos        = new cl_cancdebitos;
db_postmemory($HTTP_POST_VARS);
if($chave!=""){
 $result = $clcancdebitos->sql_record($clcancdebitos->sql_pendentes("k21_sequencia,k21_numpre,k21_numpar,sum(k00_valor) as k00_valor","k21_numpre,k21_numpar"," k20_codigo = $chave and k20_instit = ".db_getsession("DB_instit")." GROUP BY k21_sequencia,k21_numpre,k21_numpar"));
 if($clcancdebitos->numrows > 0){
  db_fieldsmemory($result,0);
  $db_botao = true;
  $db_opcao = 1;
 }
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  <form name="form_reg" method="post">
  <inpu type="hidden" value='<?=$chavepesquisa?>' name="chavepesquisa">
  <table border='1' cellpadding="0" cellspacing='0' width="100%" >
    <tr><td colspan="3" align="center"><strong>Registros</strong></td></tr>
    <tr><td align='center'><b>Numpre</b></td><td align='center'><b>Numpar</b></td><td align='center'><b>Valor</b></td></tr>
     <?
      $total = 0;
      for($x = 0; $x < $clcancdebitos->numrows; $x++) {
          db_fieldsmemory($result,$x);
          echo "<tr><td align='center'>$k21_numpre</td><td align='center'>$k21_numpar</td><td align='center'>$k00_valor</td></tr>";
          $total+=$k00_valor;
      }
     ?>
     <tr><td colspan="3"><strong>Total:</strong><?=$total?></td></tr>
   </table>
  </form>
</body>
</html>