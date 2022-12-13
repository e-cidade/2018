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
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory( $HTTP_POST_VARS );
parse_str( $HTTP_SERVER_VARS["QUERY_STRING"] );

$clperiodoavaliacao  = new cl_periodoavaliacao;
$clperiodocalendario = new cl_periodocalendario;
$clprocavaliacao     = new cl_procavaliacao;

$clperiodoavaliacao->rotulo->label("ed09_i_codigo");
$clperiodoavaliacao->rotulo->label("ed09_c_descr");
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
            <table width="35%" border="0" align="center" cellspacing="0">
              <tr>
                <td width="4%" align="right" nowrap title="<?=$Ted09_i_codigo?>">
                  <?=$Led09_i_codigo?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?db_input( "ed09_i_codigo", 10, $Ied09_i_codigo, true, "text", 4, "", "chave_ed09_i_codigo" );?>
                </td>
              </tr>
              <tr>
                <td width="4%" align="right" nowrap title="<?=$Ted09_c_descr?>">
                  <?=$Led09_c_descr?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?db_input( "ed09_c_descr", 40, $Ied09_c_descr, true, "text", 4, "", "chave_ed09_c_descr" );?>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input name="calendario" type="hidden" value="<?=isset( $calendario ) ? $calendario : ""?>">
                  <input name="pesquisar"  type="submit" id="pesquisar2" value="Pesquisar">
                  <input name="limpar"     type="reset"  id="limpar" value="Limpar" >
                  <input name="Fechar"     type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_periodoavaliacao.hide();">
                </td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top">
         <?
         $max = '0';
         if ( isset( $calendario ) && $calendario != "" ) {

           $sCamposPeriodoCalendario = "coalesce(max(ed09_i_sequencia), 0) as max";
           $sql1                     = $clperiodocalendario->sql_query( "", $sCamposPeriodoCalendario, "", " ed53_i_calendario = {$calendario}" );
           $result1                  = $clperiodoavaliacao->sql_record($sql1);
           db_fieldsmemory( $result1, 0 );
         }
         
         if ( isset( $periodos ) ) {
        
           $where = " AND ed09_i_codigo not in ({$periodos})";
           $max   = 0;
         } else {
           $where = "";
         }
         
         if ( !isset( $pesquisa_chave ) ) {

           if ( isset( $campos ) == false ) {

             if ( file_exists( "funcoes/db_func_periodoavaliacao.php" ) == true ) {
               include( "funcoes/db_func_periodoavaliacao.php" );
             }else{
               $campos = "periodoavaliacao.*";
             }
           }
           
           if ( isset( $chave_ed09_i_codigo ) && ( trim( $chave_ed09_i_codigo ) != "" ) ) {

            $sWhere = " ed09_i_codigo = {$chave_ed09_i_codigo} and ed09_i_sequencia > {$max} {$where}";
            $sql    = $clperiodoavaliacao->sql_query( "", $campos, "ed09_i_sequencia", $sWhere );
           } else if( isset( $chave_ed09_c_descr ) && ( trim( $chave_ed09_c_descr ) != "" ) ) {

             $sWhere = " ed09_c_descr like '{$chave_ed09_c_descr}%' and ed09_i_sequencia > {$max} {$where}";
             $sql    = $clperiodoavaliacao->sql_query( "", $campos, "ed09_i_sequencia", $sWhere );
           } else {
             $sql = $clperiodoavaliacao->sql_query( "", $campos, "ed09_i_sequencia", " ed09_i_sequencia > {$max} {$where}" );
           }
           
           db_lovrot( $sql, 15, "()", "", $funcao_js );
         } else {

           if ( $pesquisa_chave != null && $pesquisa_chave != "" ) {

             $sWhere               = " ed09_i_codigo = {$pesquisa_chave} and ed09_i_sequencia > {$max} {$where}";
             $sSqlPeriodoAvaliacao = $clperiodoavaliacao->sql_query( "", "*", "", $sWhere );
             $result               = $clperiodoavaliacao->sql_record( $sSqlPeriodoAvaliacao );
             
             if ( $clperiodoavaliacao->numrows != 0 ) {

               db_fieldsmemory( $result, 0 );
               echo "<script>".$funcao_js."('$ed09_c_descr',false);</script>";
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