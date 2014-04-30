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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$iDepartamento = db_getsession( "DB_coddepto" );
$oGet          = db_utils::postMemory( $_GET );
$oDaoBaseMps   = new cl_basemps();
$oRotuloCampo  = new rotulocampo();
$oRotuloCampo->label( "ed232_c_descr" );

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <form action="" method="post">
    <table class="container">
      <tr>
        <td>
          <label class="bold">Disciplina:</label>
          <?php
            db_input( 'ed232_c_descr', 30, $Ied232_c_descr, true, "text", 1, "", "chave_ed232_c_descr" ); 
          ?>
        </td>
      </tr>
      <tr> 
        <td colspan="3" align="center"> 
          <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar"> 
          <input name="Fechar"    type="button" id="fechar"    value="Fechar" onClick="parent.db_iframe_disciplinasetapa.hide();">
         </td>
      </tr>
      <tr> 
        <td align="center" valign="top"> 
          <?
          
            $aWhere     = array();
            $sWhere     = "";
            $sCampos    = "distinct ed12_i_codigo, ed232_c_descr, ed29_c_descr";
            $sOrdenacao = "ed12_i_codigo";
            
            $aWhere[] = "ed77_i_escola = {$iDepartamento}";
            
            /**
             * Caso seja passado por parâmetro o código da etapa, busca as disciplinas vinculadas ao mesmo ensino
             * da etapa informada
             */
            if ( isset( $oGet->iEtapa ) && !empty( $oGet->iEtapa ) ) {
            
              $oEtapa   = EtapaRepository::getEtapaByCodigo( $oGet->iEtapa );
              $iEnsino  = $oEtapa->getEnsino()->getCodigo();
              $aWhere[] = "ed11_i_ensino = {$iEnsino}";
            }
            
            if ( !isset( $pesquisa_chave ) ) {
            
              if ( isset( $chave_ed232_c_descr ) && !empty( $chave_ed232_c_descr ) ) {
                $aWhere[] = "ed232_c_descr ilike '{$chave_ed232_c_descr}%'";
              }
              
              $repassa = array();
      
              $sWhere = implode( " and ",  $aWhere);
              $sSql   = $oDaoBaseMps->sql_query_basemps_escola( null, $sCampos, $sOrdenacao, $sWhere );
              
              db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
            } else if ( !empty( $pesquisa_chave ) ) {
              
              $sSql      = $oDaoBaseMps->sql_query_basemps_escola( null, $sCampos, $sOrdenacao, $sWhere );
              $rsBaseMps = $oDaoBaseMps->sql_record( $sSql );
              
              if ( $oDaoBaseMps->numrows > 0 ) {
                 
                db_fieldsmemory($result, 0);
                echo "<script>".$funcao_js."(false, '{$ed12_i_codigo}', '{$ed232_c_descr}');</script>";
              } else {
                echo "<script>".$funcao_js."(true, 'Chave (".$pesquisa_chave.") não Encontrado');</script>";
              }
            }
          ?>
         </td>
       </tr>
    </table>
  </form>
</body>
</html>