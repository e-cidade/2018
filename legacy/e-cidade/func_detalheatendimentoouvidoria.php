<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("classes/db_ouvidoriaatendimento_classe.php");

$oGet = db_utils::postMemory($_GET);

$clouvidoriaatendimento = new cl_ouvidoriaatendimento;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table align="center" valign="top">
  <tr> 
    <td> 
      <?
      
	      $sWhere  = "    ov01_instit       = ".db_getsession('DB_instit');
	      $sWhere .= "and ov09_protprocesso = {$oGet->iCodProcesso}";
	      
	      $sCampos  = "distinct fc_numeroouvidoria(ov01_sequencial) as ov01_numero,";
	      $sCampos .= "ov01_dataatend,";
	      $sCampos .= "ov01_horaatend,";
	      $sCampos .= "ov01_requerente,";
	      $sCampos .= "ov01_solicitacao,";
	      $sCampos .= "ov01_executado";
        
        $sql = $clouvidoriaatendimento->sql_query_proc("",$sCampos,"ov01_numero",$sWhere);
        db_lovrot($sql,15);

      ?>
     </td>
   </tr>
</table>
</body>
</html>