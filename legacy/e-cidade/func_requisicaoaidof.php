<?php
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


require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('dbforms/db_funcoes.php');

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

//die($iNumCgm ." = ". $iInscricao);

$clrequisicaoaidof = new cl_requisicaoaidof;
$clcadescrito      = new cl_cadescrito();
?>
<html>

  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body border="0" cellspacing="0" bgcolor="#CCCCCC" >
     <table align="center">
      <tr> 
        <td align="center" valign="top"> 
          <?php 
           
            
            if (isset($campos) == false) {
    
              if (file_exists("funcoes/db_func_requisicaoaidof.php") == true) {
                
                require_once("funcoes/db_func_requisicaoaidof.php");
              } else {
                
                $sCampos  = " y116_id, q09_descr, y116_datalancamento,       ";
                $sCampos .= " q02_inscr, cgm.z01_numcgm, cgm.z01_nome, cgm.z01_cgccpf, ";
                $sCampos .= " CASE WHEN y116_status = 'C' THEN 'Cancelada'
                                   WHEN y116_status = 'B' THEN 'Bloqueada'
                                   WHEN y116_status = 'L' THEN 'Liberada'
                                   ELSE 'Pendente'
                              END as y116_status";
                
                $sOrderBy = 'y116_inscricaomunicipal, y116_status desc, y116_id desc';
                
              }
            }
            
            
            if ((trim($iInscricao) != "") && (trim($iNumCgm) == "")) {
              
              $sSql = $clrequisicaoaidof->sql_query  (null,
                                                   $sCampos,
                                                   $sOrderBy,
                                                   "y116_inscricaomunicipal = {$iInscricao}");
              
            } else if ((trim($iNumCgm) != "") && (trim($iInscricao) == "")) {
              
              $sSql = $clrequisicaoaidof->sql_query_RequisicoesPorEscritorio($iNumCgm,null,
                                                                            $sCampos,
                                                                            $sOrderBy);
              
              
            } else if ((trim($iNumCgm) != "") && (trim($iInscricao) != "")){  
              
              $sSql = $clrequisicaoaidof->sql_query_RequisicoesPorEscritorio($iNumCgm, $iInscricao, $sCampos, $sOrderBy);
              
            } else {
              
              $sSql = $clrequisicaoaidof->sql_query(null, $sCampos, $sOrderBy, "y116_status = 'P'");
              
              
            }
            
            $repassa = array();
            
            db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
            
          ?>
        </td>
      </tr>
    </table>
</body>
</html>

<script>
js_tabulacaoforms("form2","chave_y116_id",true,1,"chave_y116_id",true);
</script>