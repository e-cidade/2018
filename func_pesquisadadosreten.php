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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_pagordem_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clpagordem = new cl_pagordem;

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='a=1'>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
      <form name="form2" method="post" action="" >
      </form> 
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $campos  = "e60_numcgm,z01_nome,z01_munic,z01_uf,z01_ender,z01_cep";
      $dbwhere = " e60_instit = ".db_getsession("DB_instit");

      $sql = $clpagordem->sql_query("",$campos," e50_codord limit 1 ","$dbwhere and e50_codord = '$pesquisa_chave' ");

			$rsPagordem = $clpagordem->sql_record($sql);

      if ( $rsPagordem != false && pg_num_rows($rsPagordem) > 0 ) {

        db_fieldsmemory($rsPagordem,0);
        
        $scriptjs = " parent.js_dadosCgm('{$e60_numcgm}','{$z01_nome}','{$z01_munic}','{$z01_cep}','{$z01_uf}','{$z01_ender}',false); ";
       
      } else {

        $scriptjs = " parent.js_dadosCgm('','','','','','',true); ";
        
      }

      ?>
    </td>
  </tr>
</table>
</body>
</html>
<script>
  <?
    echo $scriptjs;
  ?>
</script>