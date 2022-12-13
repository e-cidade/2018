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

//MODULO: educação
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$clescola = new cl_escola;
$clescola->rotulo->label("ed18_i_codigo");
$clescola->rotulo->label("ed18_c_nome");
$oCrusoTurno = new cl_cursoturno();
$iEscola     = db_getsession('DB_coddepto');

/* PLUGIN MATRICULAONLINE - Chamada DAO cl_fase */
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
   <table width="35%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted18_i_codigo?>">
      <?=$Led18_i_codigo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed18_i_codigo",10,$Ied18_i_codigo,true,"text",4,"","chave_ed18_i_codigo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted18_c_nome?>">
      <?=$Led18_c_nome?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed18_c_nome",40,$Ied18_c_nome,true,"text",4,"","chave_ed18_c_nome");?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_escola.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?php
   if( !isset( $pesquisa_chave ) ) {

     if( isset( $campos ) == false ) {

       if( file_exists( "funcoes/db_func_escola.php" ) == true ) {
         include(modification("funcoes/db_func_escola.php"));
       } else {
         $campos = "escola.*";
       }
     }

     if( isset( $chave_ed18_i_codigo ) && ( trim( $chave_ed18_i_codigo ) != "" ) ) {
       $sql = $clescola->sql_query( $chave_ed18_i_codigo, " DISTINCT " . $campos, "ed18_c_nome" );
     } else if( isset( $chave_ed18_c_nome ) && ( trim( $chave_ed18_c_nome ) != "" ) ) {
     $sql = $clescola->sql_query(""," DISTINCT ".$campos,"ed18_c_nome"," ed18_c_nome like '$chave_ed18_c_nome%' ");
    } else if ( isset($sEnsino) && !empty($sEnsino) ) {
      $sql = $oCrusoTurno->sql_query(null, "DISTINCT {$campos}", null, "ed29_i_ensino in ({$sEnsino})");

       /* PLUGIN MATRICULAONLINE - Condição iFase */
     } else {
       $sql = $clescola->sql_query( "", "DISTINCT " . $campos, "ed18_c_nome", "" );
     }

     if ( isset($lRemoverEscolaLogada) ) {
        $sql     = $clescola->sql_query_file(null, "DISTINCT {$campos}", null, "ed18_i_codigo <> {$iEscola}");
     }

     db_lovrot( $sql, 15, "()", "", $funcao_js );
   } else {

     if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

       $sSql = $clescola->sql_query( $pesquisa_chave );

       /* PLUGIN MATRICULAONLINE - Condição iFase com pesquisa chave */

       $result = $clescola->sql_record( $sSql );

       $lRetornarEscolaLogada = true;

       if ( isset($lRemoverEscolaLogada) && $pesquisa_chave == $iEscola ) {
         $lRetornarEscolaLogada = false;
       }

       if( $clescola->numrows != 0 && $lRetornarEscolaLogada ) {

         db_fieldsmemory( $result, 0 );
         echo "<script>".$funcao_js."('$ed18_c_nome',false);</script>";
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
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
