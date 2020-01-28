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

//MODULO: educação
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_serie_classe.php");
include("classes/db_historico_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clserie = new cl_serie;
$clhistorico = new cl_historico;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td align="center" valign="top">
   <?
   $result = $clhistorico->sql_record($clhistorico->sql_query("","ed61_i_curso,ed61_i_aluno,ed47_v_nome,ed29_c_descr",""," ed61_i_codigo = $historico"));
   db_fieldsmemory($result,0);
   echo "<b>Etapas disponíveis para o aluno ".@$z01_nome." no curso $ed29_c_descr:</b>";
   if(!isset($pesquisa_chave)){
    if(isset($campos)==false){
     if(file_exists("funcoes/db_func_serie.php")==true){
      include("funcoes/db_func_serie.php");
     }else{
      $campos = "serie.*";
     }
    }
    $sql1 = "SELECT ed11_i_codigo
               FROM historicomps
                    inner join serie on ed11_i_codigo = ed62_i_serie
              WHERE ed62_i_historico = {$historico}
                AND ed62_c_resultadofinal != 'R'
             UNION
             SELECT ed11_i_codigo
               FROM historicompsfora
                    inner join serie on ed11_i_codigo = ed99_i_serie
              WHERE ed99_i_historico = {$historico}
                AND ed99_c_resultadofinal != 'R'
                AND ( ed99_c_situacao = 'CONCLUÍDO' OR ed99_c_situacao = 'RECLASSIFICADO' )
            ";
    $query1  = db_query($sql1);
    $linhas1 = pg_num_rows($query1);
    
    if ( $linhas1 > 0 ) {

      $ser_jatem = "";
      $sep       = "";
      
      for ( $x = 0; $x < $linhas1; $x++ ) {
      
       db_fieldsmemory( $query1, $x );
       $ser_jatem .= $sep.$ed11_i_codigo;
       $sep        = ",";
      }
    } else {
      $ser_jatem = 0;
    }
    
    $sql = "SELECT $campos
              FROM serie
                   inner join ensino   on ed10_i_codigo = ed11_i_ensino
                   inner join cursoedu on ed29_i_ensino = ed10_i_codigo
             WHERE ed29_i_codigo = {$ed61_i_curso}
               AND ed11_i_codigo not in ( {$ser_jatem} )
             ORDER BY ed11_i_sequencia
           ";
    db_lovrot($sql,15,"()","",$funcao_js);
   }
  ?>
  </td>
 </tr>
</table>
</body>
</html>