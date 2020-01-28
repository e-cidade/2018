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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table align="center" valign="top" style="padding-top:25px;">
  <tr> 
    <td> 
      <?
      
        if ( $oGet->sTipoTitular == 'Cidadao' ) {
        	$sWhere  = " and ov10_cidadao = {$oGet->iCodigo} "; 
        } else {
          $sWhere  = " and ov11_cgm     = {$oGet->iCodigo} "; 
        }
        
      
        $sSql = "select distinct p58_codproc,
								        z01_numcgm as DB_p58_numcgm,
								        z01_nome,
								        p58_dtproc,
								        p51_descr,
								        p58_obs,
								        p58_requer as DB_p58_requer
								   from ouvidoriaatendimento
								        inner join processoouvidoria           on ov09_ouvidoriaatendimento = ov01_sequencial
								        inner join protprocesso                on p58_codproc               = ov09_protprocesso
								        inner join cgm                         on z01_numcgm                = p58_numcgm
								        inner join tipoproc                    on p51_codigo                = p58_codigo
								        left  join arqproc                     on p68_codproc               = p58_codproc
								        left  join ouvidoriaatendimentocgm     on ov11_ouvidoriaatendimento = ov01_sequencial
								        left  join ouvidoriaatendimentocidadao on ov10_ouvidoriaatendimento = ov01_sequencial
								   where ov01_instit = ".db_getsession('DB_instit')."
                     and p68_codproc is null
                     {$sWhere}"; 
      
         db_lovrot($sSql,15,"()","",'',"","NoMe");
      ?>
     </td>
   </tr>
   <tr align="center">
     <td>
       <input type="button" name="fechar" Value="Fechar" onClick="parent.db_iframe_listaProc.hide()"/>
     </td>
   </tr>
</table>
</body>
</html>