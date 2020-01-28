<?php
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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$clrhcbo = new cl_rhcbo;
$clrhcbo->rotulo->label("rh70_sequencial");
$clrhcbo->rotulo->label("rh70_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td height="63" align="center" valign="top">
        <form name="form2" method="post" action="" >
          <table width="35%" border="0" align="center" cellspacing="0">
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Trh70_sequencial?>">
                <?=$Lrh70_sequencial?>
              </td>
              <td width="96%" align="left" nowrap>
                <?php
                db_input( "rh70_sequencial", 10, $Irh70_sequencial, true, "text", 4, "", "chave_rh70_sequencial" );
                ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Trh70_descr?>">
                <?=$Lrh70_descr?>
              </td>
              <td width="96%" align="left" nowrap>
                <?php
                db_input( "rh70_descr", 40, $Irh70_descr, true, "text", 4, "", "chave_rh70_descr" );
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="button"  id="limpar" value="Limpar" onClick="js_limpar();">
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhcbo.hide();">
               </td>
            </tr>
          </table>
        </form>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?php
        $sRhcbo = " and exists ( select * from sau_proccbo where sd96_i_cbo = rh70_sequencial ) ";

        if( !isset( $pesquisa_chave ) ) {

          if( isset( $campos ) == false ) {

            if( file_exists( "funcoes/db_func_rhcbo.php" ) == true ) {
              include("funcoes/db_func_rhcbo.php");
            } else {
              $campos = "rhcbo.*";
            }
          }

          if( isset( $chave_rh70_sequencial ) && ( trim( $chave_rh70_sequencial ) != "" ) ) {

            $sWhere = " rhcbo.rh70_sequencial = {$chave_rh70_sequencial} and rh70_tipo = 4 {$sRhcbo} ";
            $sql    = $clrhcbo->sql_query( $chave_rh70_sequencial, $campos, "rh70_sequencial", $sWhere );
          } else if( isset( $chave_rh70_descr ) && ( trim( $chave_rh70_descr ) != "" ) ) {

            $sWhere = " upper(rh70_descr) like '" . strtoupper( $chave_rh70_descr ) . "%' and rh70_tipo = 4 {$sRhcbo} ";
            $sql    = $clrhcbo->sql_query( "", $campos, "rh70_sequencial", $sWhere );
          } else {
            $sql = $clrhcbo->sql_query( "", $campos, "rh70_sequencial", " rh70_tipo = 4 {$sRhcbo}" );
          }

          $repassa = array();
          if( isset( $chave_rh70_sequencial ) ) {
            $repassa = array( "chave_rh70_sequencial" => $chave_rh70_sequencial );
          }

          db_lovrot( $sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
        } else {

          if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

            $sWhere = "rh70_estrutural = '{$pesquisa_chave}' and rh70_tipo = 4 {$sRhcbo} ";
            $sSql   = $clrhcbo->sql_query( null, "rh70_estrutural, rh70_descr, rh70_sequencial", null, $sWhere );
            $result = $clrhcbo->sql_record( $sSql );

            if( $clrhcbo->numrows != 0 ) {

              db_fieldsmemory( $result, 0 );
              echo "<script>{$funcao_js}('{$rh70_estrutural}','{$rh70_descr}','{$rh70_sequencial}',false);</script>";
            } else {
              echo "<script>{$funcao_js}('','Chave({$pesquisa_chave}) não Encontrado','',true);</script>";
            }
          } else {
            echo "<script>{$funcao_js}('',false);</script>";
          }
        }
        ?>
       </td>
     </tr>
  </table>
</body>
</html>
<script>
function js_limpar() {

  document.form2.chave_rh70_sequencial.value = "";
  document.form2.chave_rh70_descr.value      = "";
}
</script>