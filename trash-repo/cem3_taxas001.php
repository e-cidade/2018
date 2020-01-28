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
include("classes/db_txossoariojazigo_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cltxossoariojazigo = new cl_txossoariojazigo;

$clrotulo = new rotulocampo;


if(isset($chavepesquisa)){
   $result = $cltxossoariojazigo->sql_record("select cm10_i_numpre
                                             from txossoariojazigo
                                            inner join itenserv      on cm10_i_codigo = cm30_i_itenserv
                                            where cm30_i_ossoariojazigo=$chavepesquisa");
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <br>
    <center>
     <?

     if( $cltxossoariojazigo->numrows != 0 ){
         db_fieldsmemory( $result, 0 );
         $result = debitos_tipos_numpre($cm10_i_numpre);
         if( $result == false ){
            db_msgbox('Sem d�bitos a pagar ou n�o localizado!');
         }else{
              echo "<script>";
              echo " parent.document.formaba.a4.disabled=false; ";
              echo " top.corpo.iframe_a4.location.href='cai3_gerfinanc002.php?numpre=6765503&tipo=".pg_result($result,0,"k00_tipo")."&emrec=".pg_result($result,0,"k00_emrec")."&agnum=".pg_result($result,0,"k00_agnum")."&agpar=".pg_result($result,0,"k00_agpar")."&certidao=&k03_tipo=&k00_tipo=".pg_result($result,0,"k00_tipo")."&db_datausu=".date('Y-m-d',db_getsession("DB_datausu"))."'";
              echo "</script>";
         }
     }else{
         echo('<b>Sem d�bitos a pagar ou n�o localizado!</b><p>');
     }
     ?>
    </center>
     </td>
  </tr>
</table>
</body>
</html>
<?