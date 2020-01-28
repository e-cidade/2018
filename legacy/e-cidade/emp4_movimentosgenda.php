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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

include("classes/db_empagemov_classe.php");
$clempagemov    = new cl_empagemov;
$clrotulo = new rotulocampo;

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e50_codord");
$db_opcao = 1;
$db_botao = false;


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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr> 
  <td height="380" align="center" valign="middle" bgcolor="#CCCCCC"> 
    <table>
     <tr>
   	  <td align='center'>
       <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cheque.hide();">
	    </td>
     </tr>
     <tr>
	    <td>
       <?
         $dbwhere  = " e80_instit = " . db_getsession("DB_instit");
         $dbwhere .= " and e60_numemp = $e60_numemp and corempagemov.k12_codmov is not null and k105_corgrupotipo in( 1 , 2)";
          //$dbwhere = "e60_numemp = $e60_numemp and e91_codcheque  in (select k12_codmov from corconf)";

         if (isset($e50_codord) && $e50_codord != '') {
           $dbwhere .=  " and  e82_codord = $e50_codord ";
         }

         if (isset($e81_codmov) && !empty($e81_codmov)) {
           $dbwhere .= " and e81_codmov = {$e81_codmov}";
         }

         $sql    = $clempagemov->sql_query_corgrupo(null,
                                                    "e91_codcheque as DB_e91_codcheque,
                                                     substr(k13_descr,0,30),
                                                     e81_codmov,
                                                     substr(z01_nome,1,30),
                                                     e83_conta,e83_descr,
                                                     e91_cheque,
                                                     k105_corgrupotipo,
                                                     (case when e91_valor is null then e81_valor  - fc_valorretencaomov(e81_codmov,true) 
                                                                                  else e91_valor end) as e81_valor,
                                                     corrente.k12_valor",
                                                     "","$dbwhere");

         $result = $clempagemov->sql_record($sql);
         if ($clempagemov->numrows == 0) {
  
             /**
             * Consultamos as retencoes da ordem.
             */
             $dbwhere  = " e80_instit = " . db_getsession("DB_instit");
             $dbwhere .= " and e60_numemp = $e60_numemp and corempagemov.k12_codmov is not null and k105_corgrupotipo = 2";
             $dbwhere .= " and e81_cancelado is null";
            if (isset($e50_codord) && $e50_codord != '') {
              $dbwhere .=  " and  e82_codord = $e50_codord ";
            }

            if (isset($e81_codmov) && !empty($e81_codmov)) {
              $dbwhere .= " and e81_codmov = {$e81_codmov}";
            }
  
            $sql    = $clempagemov->sql_query_corgrupo(null,
                                                       "e91_codcheque as DB_e91_codcheque,
                                                        substr(k13_descr,0,30),
                                                        e81_codmov,
                                                        substr(z01_nome,1,30),
                                                        e83_conta,
                                                        e83_descr,
                                                        e91_cheque,
                                                        k105_corgrupotipo,
                                                        fc_valorretencaomov(e81_codmov,true) as e81_valor,
                                                        fc_valorretencaomov(e81_codmov,true) as k12_valor",
                                                        "",
                                                        "$dbwhere ");
    
            $rsRetencao = $clempagemov->sql_record($sql);
            if ($clempagemov->numrows == 0) {

              $dbwhere = "e60_numemp = $e60_numemp and e81_cancelado is null";

              if (isset($e50_codord) && $e50_codord != '') { 
                $dbwhere .=  " and  e82_codord = $e50_codord ";
              }

              if (isset($e81_codmov) && !empty($e81_codmov)) {
                $dbwhere .= " and e81_codmov = {$e81_codmov}";
              }

              $sql    = $clempagemov->sql_query_emp(null,
                                                    "distinct 0 as DB_e91_codcheque,
                                                     substr(k13_descr,0,30),
                                                     e81_codmov,
                                                     substr(z01_nome,1,30),
                                                     e83_conta,
                                                     e83_descr,
                                                     0,
                                                     e53_vlrpag as k12_valor",
                                                     "",
                                                     "$dbwhere");
            }
         }

db_lovrot($sql, 15,"()","",$js_funcao);

?>
         </td>	    
	      </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
<script>
</script>