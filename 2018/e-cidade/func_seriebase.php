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
//CLASSE DA ENTIDADE diario

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clserie       = new cl_serie;
$clbaseserie   = new cl_baseserie;
$clalunopossib = new cl_alunopossib;

$clserie->rotulo->label("ed11_i_codigo");
$clserie->rotulo->label("ed11_c_descr");
?>
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
      <tr>
        <td height="63" align="center" valign="top">
          <form name="form2" method="post" action="" >
            <table width="55%" border="0" align="center" cellspacing="0">
              <tr>
                <td width="4%" align="right" nowrap title="<?=$Ted11_i_codigo?>">
                  <?=$Led11_i_codigo?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?db_input( "ed11_i_codigo", 10, $Ied11_i_codigo, true, "text", 4, "", "chave_ed11_i_codigo" );?>
                </td>
              </tr>
              <tr>
                <td width="4%" align="right" nowrap title="<?=$Ted11_c_descr?>">
                  <?=$Led11_c_descr?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?db_input( "ed11_c_descr", 30, $Ied11_c_descr, true, "text", 4, "", "chave_ed11_c_descr" );?>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input name="curso" type="hidden" value="<?=isset( $curso ) && !empty( $curso ) ? $curso : ""?>">
                  <?if(isset($inicial)){?>
                   <input name="inicial" type="hidden" value="<?=$inicial?>">
                  <?}?>
                  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                  <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
                  <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_serie.hide();">
                </td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top">
        <?php
          $sql_hist     = "SELECT ed62_c_resultadofinal, ed62_i_anoref, ed11_i_sequencia ";   
          $sql_hist    .= "  FROM historicomps ";
          $sql_hist    .= "       inner join historico on ed61_i_codigo = ed62_i_historico ";
          $sql_hist    .= "       inner join serie     on ed11_i_codigo = ed62_i_serie ";
          $sql_hist    .= " WHERE ed61_i_aluno = {$aluno} ";
          $sql_hist    .= " UNION ";
          $sql_hist    .= "SELECT ed99_c_resultadofinal, ed99_i_anoref, ed11_i_sequencia ";
          $sql_hist    .= "  FROM historicompsfora ";
          $sql_hist    .= "       inner join historico on ed61_i_codigo = ed99_i_historico ";
          $sql_hist    .= "       inner join serie     on ed11_i_codigo = ed99_i_serie ";
          $sql_hist    .= " WHERE ed61_i_aluno = {$aluno} ";
          $sql_hist    .= " ORDER BY ed62_i_anoref desc,ed11_i_sequencia desc,ed62_c_resultadofinal asc ";
          $sql_hist    .= " LIMIT 1 ";
          $result_hist  = db_query( $sql_hist );
          
          $sWhereHist = "";
          $sMsgUser   = "";
          
          if ( pg_num_rows( $result_hist ) > 0 ) {
          	
            $ed62_c_resultadofinal = trim( pg_result( $result_hist, 0, 'ed62_c_resultadofinal' ) );
            
            if ( $ed62_c_resultadofinal == "P" ) {
          
              $sCamposAlunoPossib = "ed79_i_serie, ed11_c_descr as descr_etapa, ed10_c_descr as descr_ensino, ed10_c_abrev as descr_abrev";
              $sSqlAlunoPossib    = $clalunopossib->sql_query( "", $sCamposAlunoPossib, "", " ed56_i_aluno = {$aluno}" );
              $result_etapa       = $clalunopossib->sql_record( $sSqlAlunoPossib );
              
              if ( $clalunopossib->numrows > 0 ) {
              
              	 db_fieldsmemory( $result_etapa, 0 );
            	   $sWhereHist .= " AND ed11_i_codigo = {$ed79_i_serie}";
            	   $sMsgUser   .= " <b>Aluno possui último registro no histórico <br> como APROVAÇÃO PARCIAL na etapa ";
            	   $sMsgUser   .= "{$descr_etapa} - {$descr_ensino} / {$descr_abrev}</b>";
              }
            } 
          }
          
          $sCamposBaseSerie = "si.ed11_i_sequencia as inicial, sf.ed11_i_sequencia as final, si.ed11_i_ensino as ensino";
          $sSqlBaseSerie    = $clbaseserie->sql_query( "", $sCamposBaseSerie, "", " ed87_i_codigo = {$base}" );
          $result           = $clbaseserie->sql_record( $sSqlBaseSerie );
          db_fieldsmemory( $result, 0 );
          
          $sWhereSerie  = "     ed34_i_base = {$base} AND ed11_i_ensino = {$ensino} AND ed11_i_sequencia between {$inicial}";
          $sWhereSerie .= " AND {$final} {$sWhereHist}";
          
          if ( !isset( $pesquisa_chave ) ) {
          
            if ( isset( $campos ) == false ) {
          
              if ( file_exists( "funcoes/db_func_serie.php" ) == true ) {
                include( "funcoes/db_func_serie.php" );
              } else {
                $campos = "serie.*";
              }
            }
            
            $sGroupBy = " GROUP BY ed11_i_codigo, ed11_c_descr, ed11_c_abrev, ed11_i_sequencia, serie.ed11_i_codcenso "; 
            
            if ( isset( $chave_ed11_i_codigo ) && ( trim( $chave_ed11_i_codigo ) != "" ) ) {
              
              $sWhereSerie .= " AND ed11_i_codigo = {$chave_ed11_i_codigo} {$sGroupBy}";
              $sql          = $clserie->sql_query_turma( "", $campos, "ed11_i_sequencia", $sWhereSerie );
            } else if( isset( $chave_ed11_c_descr ) && ( trim( $chave_ed11_c_descr ) != "" ) ) {
          
              $sWhereSerie  = " AND ed11_c_descr like '{$chave_ed11_c_descr}%' {$sGroupBy}";
              $sql          = $clserie->sql_query_turma( "", $campos, "ed11_i_sequencia", $sWhereSerie );
            } else {
              $sql = $clserie->sql_query_turma( "", $campos, "ed11_i_sequencia", $sWhereSerie.$sGroupBy );
            }
            
            db_lovrot( $sql, 15, "()", "", $funcao_js );
            echo $sMsgUser;
          } else {
          
            if ( $pesquisa_chave != null && $pesquisa_chave != "" ) {
          
              $sWhereSerie .= " AND ed11_i_codigo = {$pesquisa_chave} ";
              $sSqlSerie    = $clserie->sql_query_turma( "", "*", "ed11_i_sequencia", $sWhereSerie );
              $result       = $clserie->sql_record( $sSqlSerie );
              
              if ( $clserie->numrows != 0 ) {
          
                db_fieldsmemory( $result, 0 );
                echo "<script>".$funcao_js."('$ed11_c_descr',false);</script>";
              } else {
                echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
              }
            } else {
              echo "<script>".$funcao_js."('',false);</script>";
            }
          }
        ?>
        </td>
      </tr>
    </table>
  </body>
</html>