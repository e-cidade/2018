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
require("libs/db_utils.php");
include("dbforms/db_funcoes.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_issnotaavulsanumpre_classe.php");
include("dbforms/db_classesgenericas.php");

$clissnotaavulsanumpre = new cl_issnotaavulsanumpre();
$get                   = db_utils::postmemory($_GET);
$rsNumpreNota          = $clissnotaavulsanumpre->sql_record(
                                           $clissnotaavulsanumpre->sql_query(null,'*',null,"q52_issnotaavulsa = ".$get->q51_sequencial));
$objNumpreNota         = db_utils::fieldsMemory($rsNumpreNota,0);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<?
//sql para recibos pagos
$sSql = "select k00_dtpaga, 
               arrepaga.k00_valor,
               arrepaga.k00_numpre,
               k01_descr,
              'Recibo pago' as dl_Situação
         from  arrepaga 
                  inner join arrecant on arrepaga.k00_numpre = arrecant.k00_numpre
                                     and arrepaga.k00_numpar = arrecant.k00_numpar
                  inner join histcalc on k01_codigo          =  arrepaga.k00_hist                   
         where  arrepaga.k00_numpre = ".$objNumpreNota->q52_numpre;                          
$rsPago   = pg_query($sSql);       
$iNumRows = pg_num_rows($rsPago);
if ($iNumRows == 0){       

   $sSql = "select k20_data, 
                   arrecant.k00_valor,
                   arrecant.k00_numpre,
                   'Recibo Cancelado' as dl_Situação
             from  arrecant 
                   inner join cancdebitosreg on k00_numpre = k21_numpre
                                            and k00_numpar = k21_numpar
                   inner join cancdebitos    on k20_codigo = k21_codigo                         
            where  k00_numpre = ".$objNumpreNota->q52_numpre;                          
   $rsCanc  = pg_query($sSql);       
   $iNumRows = pg_num_rows($rsCanc);
   if ($iNumRows == 0){       
     $sSql = "select k00_dtvenc, 
                     arrecad.k00_valor,
                     arrecad.k00_numpre,
                     'recibo em Aberto' as dl_Situação
               from  arrecad 
              where  k00_numpre = ".$objNumpreNota->q52_numpre;                          
   }


}
db_lovrot($sSql,15,"","",null,"","NoMe");
?>
<body>
</html>