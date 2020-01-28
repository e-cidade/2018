<?php
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
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_layouttxt.php");

$oGet                   = db_utils::postMemory($_GET);

require_once("classes/db_emparquivopit_classe.php");
$clEmpArquivoPit = new cl_emparquivopit();
//$sArquivoPit   = "tmp/arquivo_pit_".date("Ymd",db_getsession("DB_datausu")).".txt";

$sQueryArquivo  = " select e14_corpoarquivo, e14_nomearquivo ";
$sQueryArquivo .= "   from emparquivopit ";
$sQueryArquivo .= "  where e14_sequencial =  ".$oGet->e14_sequencial;

$rsQueryArquivo = $clEmpArquivoPit->sql_record($sQueryArquivo);
$iNumRowsArquivo = pg_num_rows($rsQueryArquivo);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>  
  <body>
  <form name='form1'>
  <div align="center" >
    <b>Reemitindo arquivo PIT</b>
      <?
       $i = 0;
       $lArquivoGerado = false;
       if($iNumRowsArquivo > 0){
       	   $oArquivo = db_utils::fieldsMemory($rsQueryArquivo,0);
       	   $sArquivoPit = "tmp/".$oArquivo->e14_nomearquivo;
           $fp = fopen($sArquivoPit,"w");
           if($fp === false){
           	db_msgbox("usuário:\\n\\n Erro ao reemitir Arquivo PIT");    	
           }else{
             
             fputs($fp,$oArquivo->e14_corpoarquivo);
             fclose($fp);
             $lArquivoGerado = true;   	
           }
       }
      ?>
      </div>
   </form>   
  </body>
</html>
<script>
<?
 if ($lArquivoGerado) {
  
   echo "var sLista = '{$sArquivoPit}#Arquivo pit'\n";
   //echo "alert(sLista);\n";
   echo "js_montarlista(sLista,'form1');\n";
 }
?>  
</script>