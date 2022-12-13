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
require_once ("classes/db_cursoedu_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clalunocurso = new cl_alunocurso;
$clcurso      = new cl_curso;

$clcurso->rotulo->label("ed29_i_codigo");
$clcurso->rotulo->label("ed29_c_descr");
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
                <td width="4%" align="right" nowrap title="<?=$Ted29_i_codigo?>">
                  <?=$Led29_i_codigo?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?db_input( "ed29_i_codigo", 10, $Ied29_i_codigo, true, "text", 4, "", "chave_ed29_i_codigo" );?>
                </td>
              </tr>
              <tr>
                <td width="4%" align="right" nowrap title="<?=$Ted29_c_descr?>">
                 <?=$Led29_c_descr?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?
                  db_input( "ed29_c_descr", 30, @$Ied29_c_descr, true, "text", 4, "", "chave_ed29_c_descr" );
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                  <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
                  <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_curso.hide();">
                </td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top">
        <?php
          $escola = db_getsession("DB_coddepto");
          
          if ( !isset( $pesquisa_chave ) ) {
            
            if ( isset( $campos ) == false ) {
              
              if ( file_exists( "funcoes/db_func_alunocurso.php" ) == true ) {
                include( "funcoes/db_func_alunocurso.php" );
              } else {
                $campos = "alunocurso.*";
              }
            }
            
            if ( isset( $chave_ed29_i_codigo ) && ( trim( $chave_ed29_i_codigo ) != "" ) ) {
              $where = " AND ed29_i_codigo = {$chave_ed29_i_codigo}";
            } else if ( isset( $chave_ed29_c_descr ) && ( trim( $chave_ed29_c_descr ) != "" ) ) {
              $where = " AND ed29_c_descr like '{$chave_ed29_c_descr}%'";
            } else {
              $where = "";
            }
            
            $sql  = "SELECT {$campos} ";
            $sql .= "  FROM cursoedu ";
            $sql .= "       inner join ensino      on ensino.ed10_i_codigo     = cursoedu.ed29_i_ensino ";
            $sql .= "       inner join cursoescola on cursoescola.ed71_i_curso = cursoedu.ed29_i_codigo ";
            $sql .= "       inner join base        on base.ed31_i_curso        = cursoedu.ed29_i_codigo ";
            $sql .= "       inner join cursoturno  on cursoturno.ed85_i_curso  = cursoedu.ed29_i_codigo ";
            $sql .= " WHERE cursoescola.ed71_i_escola   = {$escola} ";
            $sql .= "   AND cursoescola.ed71_c_situacao = 'S' ";
            $sql .= " {$where}";
            $sql .= "EXCEPT ";
            $sql .= "SELECT {$campos} ";
            $sql .= "  FROM alunocurso ";
            $sql .= "       inner join base     on base.ed31_i_codigo     = alunocurso.ed56_i_base ";
            $sql .= "       inner join cursoedu on cursoedu.ed29_i_codigo = base.ed31_i_curso ";
            $sql .= "       inner join ensino   on ensino.ed10_i_codigo   = cursoedu.ed29_i_ensino ";
            $sql .= " WHERE alunocurso.ed56_i_aluno = {$aluno}";
            
            db_lovrot( $sql, 15, "()", "", $funcao_js );
          } else {

            if ( file_exists( "funcoes/db_func_alunocurso.php" ) == true ) {
              include("funcoes/db_func_alunocurso.php");
            } else {
              $campos = "alunocurso.*";
            }
            
            if ( $pesquisa_chave != null && $pesquisa_chave != "" ) {

              $sql  = "SELECT {$campos} ";
              $sql .= "  FROM cursoedu ";
              $sql .= "       inner join ensino      on ensino.ed10_i_codigo     = cursoedu.ed29_i_ensino ";
              $sql .= "       inner join cursoescola on cursoescola.ed71_i_curso = cursoedu.ed29_i_codigo ";
              $sql .= "       inner join base        on base.ed31_i_curso        = cursoedu.ed29_i_codigo ";
              $sql .= " WHERE cursoescola.ed71_i_escola = {$escola} ";
              $sql .= "   AND cursoedu.ed29_i_codigo    = {$pesquisa_chave} ";
              $sql .= "EXCEPT ";
              $sql .= "SELECT {$campos} ";
              $sql .= "  FROM alunocurso ";
              $sql .= "       inner join base     on base.ed31_i_codigo     = alunocurso.ed56_i_base ";
              $sql .= "       inner join cursoedu on cursoedu.ed29_i_codigo = base.ed31_i_curso ";
              $sql .= " WHERE alunocurso.ed56_i_aluno = {$aluno} ";
              $sql .= " ORDER BY ed29_c_descr ";
              
              $result = db_query( $sql );
              $linhas = pg_num_rows( $result );
              
              if ( $linhas != 0 ) {

                db_fieldsmemory( $result, 0 );
                echo "<script>".$funcao_js."('$ed29_c_descr',false);</script>";
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