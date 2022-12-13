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

include("libs/db_sql.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_liclicitaata_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clliclicitaata = new cl_liclicitaata;

if ( isset($oGet->oid_arq) && $oGet->oid_arq != "" ){
	
   pg_query ($conn, "begin");
   
   $loid = pg_lo_open($conn,$oGet->oid_arq, "r");
   
   header('Accept-Ranges: bytes');
   header('Keep-Alive: timeout=15, max=100');
   
   $sSqlNomeArquivo = $clliclicitaata->sql_query_file(null,"*",null," l39_arquivo = '{$oGet->oid_arq}'");
   $rsNomeArquivo   = $clliclicitaata->sql_record($sSqlNomeArquivo);
   
   $oNomeArquivo = db_utils::fieldsMemory($rsNomeArquivo,0);
   header('Content-Disposition: attachment; filename="'.$oNomeArquivo->l39_arqnome.'"');
   
   pg_lo_read_all($loid);
   pg_lo_close($loid);
   pg_query($conn, "commit"); 
   
   exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</script> 
<style>
.bordas{
  border: 2px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #999999;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.bordas_corp{
  border: 1px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #cccccc;
}
</style> 
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  align="center" width="500px">
    <form name="form1" method="post" action="">
      <tr>
        <td  align="center">
          <fieldset>
            <legend>
              <b>Lista de Atas</b>
            </legend>
	          <table width="100%">
		        	<?
		        	  $sWhereAnexos = "l39_liclicita = {$oGet->l20_codigo}";
		        	  $sSqlAnexos   = $clliclicitaata->sql_query_file(null,"*",null,$sWhereAnexos);
		         	  $rsAnexos     = $clliclicitaata->sql_record($sSqlAnexos);
		        	  $iLinhasAnexo = $clliclicitaata->numrows;
		        	  if ( $iLinhasAnexo > 0 ){
							    echo "<tr class='bordas'>
										      <td class='bordas' align='center'><b>Nome Arquivo</b></td>
							          </tr>";
							  } else {
							  	echo"<b>Nenhum registro encontrado</b>"; 
							  }
			 	
								for ( $iInd=0; $iInd < $iLinhasAnexo; $iInd++ ) {
									$oAnexo = db_utils::fieldsMemory($rsAnexos,$iInd);	
								  echo "
						        <tr>	    
		      				    <td class='bordas_corp' align='center'>
		      				      <img src='imagens/seta.gif'>
		      					    <a class='links' href='lic3_infolicata002.php?oid_arq={$oAnexo->l39_arquivo}'> $oAnexo->l39_arqnome</a>
		      					  </td>     
		    				   	</tr>";
								}
				      ?>
		        </table>
	        </fieldset>
        </td>
      </tr>
    </form>
  </table>
</body>
</html>