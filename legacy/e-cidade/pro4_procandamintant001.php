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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sql.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_procandamint_classe.php");
include("classes/db_protprocesso_classe.php");

$clprocandamint = new cl_procandamint;
$clprotprocesso = new cl_protprocesso;

$clprocandamint->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("login");


db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
</script>  
<style>
.cabec {
text-align: center;
color: darkblue;
background-color:#aacccc;       
border-color: darkblue;
}
.corpo {
color: black;
background-color:#ccddcc;       
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post" target="" action="">
<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
  </tr>
    <td colspan=2>
  <?
       $result_proc=$clprotprocesso->sql_record($clprotprocesso->sql_query_file($p58_codproc,"p58_codandam"));
       if ($clprotprocesso->numrows!=0){
	      db_fieldsmemory($result_proc,0);
       }
       $sSqlAndamentosAnteriores = $clprocandamint->sql_query_sim(null,
                                                                 "p78_codandam,
                                                                  p78_data,
                                                                  p78_hora,
                                                                  p100_descricao as \"dl_Tipo\",
                                                                  p78_usuario,
                                                                  nome,
                                                                  p78_despacho
                                                                  ",
                                                                  null,
                                                                  "p78_codandam=$p58_codandam");
    db_lovrot($sSqlAndamentosAnteriores, 30);


  ?>
  </td>
  </tr>
  </table>
  </form>
</center>
<script>
</script>
</body>
</html>