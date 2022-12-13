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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oGet      = db_utils::postMemory( $_GET );
$oDaoAluno = new cl_aluno;
$oRotulo   = new rotulocampo;

$oRotulo->label("ed47_i_codigo");
$oRotulo->label("ed47_v_nome");
$oRotulo->label("ed223_i_serie");

$aWhere   = array();
$aWhere[] = " ed47_i_transpublico = 1 "; // todos alunos devem estar informado como Utiliza Transporte Público

/**
 * Busca tods alunos que possuem matricula ativa
 */
if ( !empty($oGet->lEscolaRede) && $oGet->lEscolaRede == "true" && !empty($oGet->iEscola)) {

  $iAnoSessao = date('Y',db_getsession('DB_datausu'));

  $sAlunoMatriculaEscola  = " exists ( select 1 from matricula  ";
  $sAlunoMatriculaEscola .= "            inner join turma      on ed57_i_codigo = ed60_i_turma ";
  $sAlunoMatriculaEscola .= "            inner join calendario on ed52_i_codigo = ed57_i_calendario ";
  $sAlunoMatriculaEscola .= "            where ed60_i_aluno    = ed47_i_codigo ";
  $sAlunoMatriculaEscola .= "              and ed60_c_situacao = 'MATRICULADO' ";
  $sAlunoMatriculaEscola .= "              and ed60_c_ativa    = 'S' ";
  $sAlunoMatriculaEscola .= "              and ed57_i_escola   = {$oGet->iEscola} ";
  $sAlunoMatriculaEscola .= "              and ed52_i_ano      = {$iAnoSessao} ";
  $sAlunoMatriculaEscola .= "         )";

  $aWhere[] = " trim(ed47_c_transporte) = '2' "; // Transporte público da esfera municipal
  $aWhere[] = $sAlunoMatriculaEscola;
}

/**
 * Filtra alunos de escola de procedencia que não são da rede
 */
if( !empty($oGet->lEscolaRede) && $oGet->lEscolaRede != "true" && !empty($oGet->iEscola) ) {

  $sAlunosFora  = " exists ( select 1 from alunoprimat ";
  $sAlunosFora .= "           where ed76_i_aluno = ed47_i_codigo and ed76_i_escola = {$oGet->iEscola} ";
  $sAlunosFora .= "             and ed76_c_tipo = 'F' ) ";
  $aWhere[]     = " not exists ( select 1 from matricula where ed60_i_aluno    = ed47_i_codigo ) ";
  $aWhere[]     = $sAlunosFora;
}

?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td><label for="chave_ed47_i_codigo"><?=$Led47_i_codigo?></label></td>
          <td><? db_input("ed47_i_codigo",10,$Ied47_i_codigo,true,"text",4,"","chave_ed47_i_codigo"); ?></td>
        </tr>
        <tr>
          <td><label for="chave_ed47_v_nome"><?=$Led47_v_nome?></label></td>
          <td><? db_input("ed47_v_nome",30,$Ied47_v_nome,true,"text",4,"","chave_ed47_v_nome");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_aluno.hide();">
  </form>
      <?php
      $sCampos = " ed47_i_codigo, ed47_v_nome ";

      if (!isset($pesquisa_chave)) {

        if(isset($chave_ed47_i_codigo) && (trim($chave_ed47_i_codigo)!="") ){
          $aWhere[] = " ed47_i_codigo = {$chave_ed47_i_codigo} ";
        }
        if(isset($chave_ed47_v_nome) && (trim($chave_ed47_v_nome)!="") ) {
          $aWhere[] = " ed47_v_nome ilike '{$chave_ed47_v_nome}%' ";
        }

        $sWhere  = implode(" and ", $aWhere);
        $sSql    = $oDaoAluno->sql_query_file("", $sCampos, "ed47_v_nome", $sWhere);
        $repassa = array();

        if(isset($chave_ed47_v_nome)){
          $repassa = array("chave_ed47_i_codigo"=>$chave_ed47_i_codigo,"chave_ed47_v_nome"=>$chave_ed47_v_nome);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {

        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          $aWhere[] = " ed47_i_codigo = {$pesquisa_chave} ";
          $sWhere   = implode(" and ", $aWhere);
          $sSql     = $oDaoAluno->sql_query_file("",$sCampos, "ed47_v_nome", $sWhere);
          $result   = $oDaoAluno->sql_record($sSql);
          if ($oDaoAluno->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ed47_v_nome',false);</script>";
          }else{
           echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
         echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
</body>
</html>

<script>
js_tabulacaoforms("form2","chave_ed47_v_nome",true,1,"chave_ed47_v_nome",true);
</script>
