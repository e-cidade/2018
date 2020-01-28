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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory( $_POST );
$oDadoEtapa = new cl_censoetapa;
parse_str( $_SERVER["QUERY_STRING"] );
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
        <?php
          if( !isset( $pesquisa_chave ) ) {

            if( isset( $campos ) == false ) {

              if( file_exists( "funcoes/db_func_censoetapa.php" ) == true ) {
                include("funcoes/db_func_censoetapa.php");
              } else {
                $campos = "censoetapa.*";
              }
            }

            /* Busca o codigo do ensino */
            $oCursoEdu = new Curso( $iCursoEdu );
            $iEnsino   = $oCursoEdu->getEnsino()->getCodigo();

            /* Busca o ano valido do censo */
            $oCalendario   = new Calendario( $iCalendario );
            $iAnoCenso     = DadosCenso::getUltimoAnoEtapaCenso();
            $iAnoConsulta  = 2014;
            $aEnsinosCenso = array( 12, 13, 22, 23, 24, 51, 56, 58 );
            
            if ( $oCalendario->getAnoExecucao() > 2014 && $oCalendario->getAnoExecucao() == $iAnoCenso ) {

              $iAnoConsulta  = $iAnoCenso;
              $aEnsinosCenso = array( 3, 12, 13, 22, 23, 24, 56, 72);
            }

            $sCondicao      = " ed266_i_codigo in ( " . implode( ", ", $aEnsinosCenso ) . " )";

            if( trim( $abrevtipoensino ) == "ER" ) {
              $sCondicao .= " AND ed131_regular = 'S'";
            } else if( trim( $abrevtipoensino ) == "ES" ) {
              $sCondicao .= " AND ed131_especial = 'S'";
            } else if( trim( $abrevtipoensino ) == "EJ" ) {
              $sCondicao .= " AND ed131_eja = 'S'";
            } else if ( trim( $abrevtipoensino) == "EP" ) {
              $sCondicao .= " AND ed131_profissional = 'S'";
            }
            
            $sCondicao .= " AND ed131_ano = {$iAnoConsulta}";
            $sCondicao .= " AND ed10_i_codigo = {$iEnsino}";

            $repassa = array();
            $sql     = $oDadoEtapa->sql_query_mediacao( "", "", $campos, "ed266_c_descr", $sCondicao );
            db_lovrot( $sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
          }
        ?>
      </td>
    </tr>
  </table>
</body>
</html>