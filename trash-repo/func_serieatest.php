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

//MODULO: educa��o
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clbasemps = new cl_basemps;
$clbasemps->rotulo->label("ed34_i_codigo");
$clbasemps->rotulo->label("ed11_c_descr");

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
      <td height="63" align="center" valign="top">
        <form name="form2" method="post" action="" >
          <table width="55%" border="0" align="center" cellspacing="0">
            <tr>
              <td width="4%" align="right" nowrap title="<?=isset( $Ted34_i_serie ) && !empty( $Ted34_i_serie ) ? $Ted34_i_serie : ""?>">
                <b>Serie</b>
              </td>
              <td width="96%" align="left" nowrap>
                <?db_input( "ed34_i_serie", 10, @$Ied34_i_serie, true, "text", 4, "", "chave_ed34_i_serie" );?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="right" nowrap title="<?=isset( $Ted11_c_descr ) && !empty( $Ted11_c_descr ) ? $Ted11_c_descr : ""?>">
                <b>Descricao</b>
              </td>
              <td width="96%" align="left" nowrap>
                <?db_input( "ed11_c_descr", 30, @$Ied11_c_descr, true, "text", 4, "", "chave_ed11_c_descr" );?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="reset" id="limpar" value="Limpar" >
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_serieatest.hide();">
              </td>
            </tr>
          </table>
        </form>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
       <?
       $sql    = "SELECT ARRAY(SELECT ed234_i_serieequiv FROM serieequiv WHERE ed234_i_serie in ({$serie})) as seriesequivalentes";
       $result = db_query( $sql );
       
       db_fieldsmemory( $result, 0 );
       
       $seriesequivalentes = str_replace( "{", "", $seriesequivalentes );
       $seriesequivalentes = str_replace( "}", "", $seriesequivalentes );
       
       if ( $seriesequivalentes == "" ) {
         $seriesequivalentes = "0";
       }
       
       $where1 = " ed34_i_base = {$base} AND ed34_i_serie in ({$serie}, {$seriesequivalentes})";
       
       if ( !isset( $pesquisa_chave ) ) {
      
         if ( isset( $campos ) == false ) {
      
           if ( file_exists( "funcoes/db_func_serieatest.php" ) == true ) {
             include("funcoes/db_func_serieatest.php");
           } else {
             $campos = "basemps.*";
           }
         }
         
         if ( isset( $chave_ed34_i_codigo ) && ( trim( $chave_ed34_i_codigo ) != "" ) ) {
      
           $ssWhere  = "     ed34_i_codigo = {$chave_ed34_i_codigo} AND ed34_i_base = {$base}";
           $ssWhere .= " AND ed34_i_serie in ({$serie}, {$seriesequivalentes})";
           $sql      = $clbasemps->sql_query( "", " distinct ".$campos, "", $ssWhere );
         } else if( isset( $chave_ed11_c_descr ) && ( trim( $chave_ed11_c_descr ) != "" ) ) {
      
           $ssWhere  = "     ed11_c_descr like '{$chave_ed11_c_descr}%' AND ed34_i_base = {$base}";
           $ssWhere .= " AND ed34_i_serie in ({$serie}, {$seriesequivalentes})";
           $sql     = $clbasemps->sql_query( "", " distinct ".$campos, "", $ssWhere );
         } else {
           $sql = $clbasemps->sql_query( "", " distinct ".$campos, "", $where1 );
         }
         
         db_lovrot( $sql, 15, "()", "", $funcao_js );
       } else {
      
         if ( $pesquisa_chave != null && $pesquisa_chave != "" ) {
      
           $result = $clbasemps->sql_record( $clbsemps->sql_query( "", "*", "", " ed34_i_codigo = {$pesquisa_chave}" ) );
           
           if ( $clbasemps->numrows != 0 ) {
      
             db_fieldsmemory( $result, 0 );
             echo "<script>".$funcao_js."('$ed11_c_descr',false);</script>";
           } else {
             echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
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