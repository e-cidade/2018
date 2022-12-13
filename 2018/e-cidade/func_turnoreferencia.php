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
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
db_postmemory($_POST);

$oGet           = db_utils::postMemory($_GET);
$oDaoTurno      = new cl_turno;
$oDaoCursoTurno = new cl_cursoturno;
$oDaoTurno->rotulo->label("ed15_i_codigo");
$oDaoTurno->rotulo->label("ed15_c_nome");
?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <div class="container">
      <form name="form2" method="post" action="" >
        <fieldset>
          <table class="form-container">
            <tr>
              <td>
                <label for="chave_ed15_i_codigo"><?=$Led15_i_codigo?></label>
              </td>
              <td>
                <?db_input("ed15_i_codigo",10,$Ied15_i_codigo,true,"text",4,"","chave_ed15_i_codigo");?>
              </td>
            </tr>
            <tr>
              <td title="<?=$Ted15_c_nome?>">
                <label for="chave_ed15_c_nome"><?=$Led15_c_nome?></label>
              </td>
              <td>
                <?db_input("ed15_c_nome",20,$Ied15_c_nome,true,"text",4,"","chave_ed15_c_nome");?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
        <input name="limpar" type="reset" id="limpar" value="Limpar" >
        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_turno.hide();">
      </form>
  </div>

  <div class="container">
    <?
      $iEscola = db_getsession("DB_coddepto");
      $aWhere  = array();

      $aWhere[] = "exists(select * from periodoescola where ed17_i_escola = {$iEscola} and ed17_i_turno = ed15_i_codigo)";
      $aWhere[] = "ed85_i_escola = {$iEscola} ";

      if (isset($oGet->curso) && !empty($oGet->curso)) {
        $aWhere[] = "ed85_i_curso  = {$oGet->curso} ";
      }

      if(!isset($pesquisa_chave)) {

        $sCampos  = " turno.ed15_i_codigo,              ";
        $sCampos .= " turno.ed15_c_nome,                ";
        $sCampos .= " turno.ed15_i_sequencia,           ";
        $sCampos .= " array_to_string(array_accum (case ";
        $sCampos .= "                 when ed231_i_referencia = 1 ";
        $sCampos .= "                   then 'MANHÃ'              ";
        $sCampos .= "                 when ed231_i_referencia = 2 ";
        $sCampos .= "                   then 'TARDE'              ";
        $sCampos .= "                 else 'NOITE'                ";
        $sCampos .= "               end  ),                       ";
        $sCampos .= "  ', ') as ed231_i_referencia                ";


        $sGroupBy = " group by ed15_i_codigo, ed15_c_nome, ed15_i_sequencia ";

        if ( isset($chave_ed15_i_codigo) && (trim($chave_ed15_i_codigo)!="") ) {
          $aWhere[] = "ed15_i_codigo = {$chave_ed15_i_codigo} ";
        } else if (isset($chave_ed15_c_nome) && (trim($chave_ed15_c_nome)!="") ) {
          $aWhere[] = "ed15_c_nome like '{$chave_ed15_c_nome}%'";
        }

        $sWhere = implode(" and  ", $aWhere);
        $sSql   = $oDaoCursoTurno->sql_query_turno_referente(null, $sCampos, 'ed15_i_sequencia', $sWhere . $sGroupBy);

        db_lovrot($sSql,15,"()","",$funcao_js);

        $resul = db_query($sSql);

        if (pg_num_rows($resul) == 0) {
          echo "<b>Informe os turnos para o curso desta turma.<br>Cadastros / Cursos na Escola / Vincular curso / Aba Turnos</b>";
        }
      } else {

        if ($pesquisa_chave!=null && $pesquisa_chave!=""){

          $aWhere[]     = "ed15_i_codigo = {$pesquisa_chave}";
          $sWhere       = implode(" and ", $aWhere);
          $sSql         = $oDaoCursoTurno->sql_query_turno_referente(null, $sCampos, 'ed15_i_sequencia', $sWhere);
          $rsCursoTurno = db_query($sSql);

          if ($rsCursoTurno && pg_num_rows($rsCursoTurno) > 0) {

            db_fieldsmemory($rsCursoTurno, 0);
            echo "<script>".$funcao_js."('$ed15_c_nome',false, $ed231_i_referencia);</script>";
          } else{
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }

        } else {
          echo "<script>".$funcao_js."('',false);</script>";
        }
      }
     ?>
  </div>
</body>
</html>