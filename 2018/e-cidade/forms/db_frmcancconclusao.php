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
?>
<form name="form1" method="post" action="">
  <table border="0" align="left">
    <tr>
      <td colspan="3">
        <table border="0" align="left">
          <tr>
            <td>
              <label class="bold">Selecione o Curso:</label>
              <?php
              $sCampos   = "ed29_i_codigo, ed29_c_descr";
              $sSqlCurso = $clcurso->sql_query_cursoescola( "", $sCampos, "ed10_ordem", "ed71_i_escola = {$escola}" );
              $result    = $clcurso->sql_record( $sSqlCurso );
              ?>
              <select name="curso" style="font-size:9px;width:200px;height:18px;" onchange="js_botao(this.value)">
                <option value=""></option>
                <?php
                for( $i = 0; $i < $clcurso->numrows; $i++ ) {

                  db_fieldsmemory( $result, $i );
                  $selected = isset( $curso ) && $ed29_i_codigo == $curso ? "selected" : "";
                  echo "<option value='{$ed29_i_codigo}' {$selected}>{$ed29_c_descr}</option>\n";
                }
                ?>
              </select>
            </td>
            <td valign='bottom'>
              <input type="button" name="procurar" value="Procurar" onclick="js_procurar(document.form1.curso.value)" disabled>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <?php
    if( isset( $curso ) ) {
    ?>
    <tr>
      <td valign="top">
        <?php

        $sql    = "select ed56_i_codigo, ";
        $sql   .= "       ed47_i_codigo, ";
        $sql   .= "       ed47_v_nome ";
        $sql   .= "  from (  select hist.ed61_i_aluno, max(ed10_ordem)  as ordem ";
        $sql   .= "            from historico as hist  ";
        $sql   .= "                 inner join cursoedu  on ed29_i_codigo = hist.ed61_i_curso ";
        $sql   .= "                 INNER JOIN ensino    ON ed10_i_codigo = ed29_i_ensino ";
        $sql   .= "           where ed29_i_codigo = {$curso}";
        $sql   .= "            group by hist.ed61_i_aluno ";
        $sql   .= "            ) as x ";
        $sql   .= "        join alunocurso on ed56_i_aluno = x.ed61_i_aluno  ";
        $sql   .= "        INNER JOIN aluno     ON ed47_i_codigo = ed56_i_aluno ";
        $sql   .= "        INNER JOIN base      ON ed31_i_codigo = ed56_i_baseant ";
        $sql   .= "        INNER JOIN cursoedu  ON ed29_i_codigo = ed31_i_curso ";
        $sql   .= "        INNER JOIN ensino    ON ed10_i_codigo = ed29_i_ensino ";
        $sql   .= "  WHERE ed29_i_codigo   = {$curso} ";
        $sql   .= "    AND ed56_i_escola   = {$escola} ";
        $sql   .= "    and ed10_ordem      = ordem ";
        $sql   .= "    AND ed56_c_situacao = 'CONCLUÍDO' ";
        $sql   .= "  ORDER BY ed47_v_nome ";
        $result = db_query( $sql );
        $linhas = pg_num_rows( $result );
        ?>
        <label class="bold">Alunos:</label>
        <br>
        <select name="alunosdiario"
                id="alunosdiario"
                size="10"
                onclick="js_desabinc()"
                style="font-size:9px;width:330px;height:120px" multiple>
          <?php
          for( $i = 0; $i < $linhas; $i++ ) {

            db_fieldsmemory( $result, $i );
            echo "<option value='{$ed56_i_codigo}#{$ed47_i_codigo}'>{$ed47_v_nome} - {$ed47_i_codigo}</option>\n";
          }
          ?>
        </select>
      </td>
      <td align="center">
        <table border="0">
          <tr>
            <td>
              <input name="incluirum"
                     title="Incluir"
                     type="button"
                     value=">"
                     onclick="js_incluir();"
                     style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;"
                     disabled="disabled">
            </td>
          </tr>
          <tr>
            <td height="1"></td>
          </tr>
          <tr>
            <td>
              <input name="incluirtodos"
                     title="Incluir Todos"
                     type="button"
                     value=">>"
                     onclick="js_incluirtodos();"
                     style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;">
            </td>
          </tr>
          <tr>
            <td height="3"></td>
          </tr>
          <tr>
            <td>
              <hr>
            </td>
          </tr>
          <tr>
            <td height="3"></td>
          </tr>
          <tr>
            <td>
              <input name="excluirum"
                     title="Excluir"
                     type="button"
                     value="<"
                     onclick="js_excluir();"
                     style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;"
                     disabled="disabled">
            </td>
          </tr>
          <tr>
            <td height="1"></td>
          </tr>
          <tr>
            <td>
              <input name="excluirtodos"
                     title="Excluir Todos"
                     type="button"
                     value="<<"
                     onclick="js_excluirtodos();"
                     style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;"
                     disabled="disabled">
            </td>
          </tr>
        </table>
      </td>
      <td valign="top">
        <table>
          <tr>
            <td valign="top">
              <label class="bold">Alunos para cancelar conclusão de curso:</label>
              <br>
              <select name="alunos[]"
                      id="alunos"
                      size="10"
                      onclick="js_desabexc()"
                      style="font-size:9px;width:330px;height:120px"
                      multiple="multiple">
              </select>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" colspan="3">
        <input type="submit" name="cancelar" value="Confirmar" disabled onClick="js_selecionar();">
        <input type="hidden" name="cursoedu" value="<?=$curso?>">
      </td>
    </tr>
    <?php
    }
    ?>
  </table>
</form>
<script>
function js_incluir() {

  var Tam = document.form1.alunosdiario.length;
  var F   = document.form1;

  for( var x = 0; x < Tam; x++ ) {

    if( F.alunosdiario.options[x].selected == true ) {

      F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[x].text,F.alunosdiario.options[x].value)
      F.alunosdiario.options[x] = null;
      Tam--;
      x--;
    }
  }

  if( document.form1.alunosdiario.length > 0 ) {
    document.form1.alunosdiario.options[0].selected = true;
  } else {

    document.form1.incluirum.disabled    = true;
    document.form1.incluirtodos.disabled = true;
  }

  document.form1.cancelar.disabled     = false;
  document.form1.excluirtodos.disabled = false;
  document.form1.alunosdiario.focus();
}

function js_incluirtodos() {

  var Tam = document.form1.alunosdiario.length;
  var F   = document.form1;

  for( var i = 0; i < Tam; i++ ) {

    F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[0].text,F.alunosdiario.options[0].value)
    F.alunosdiario.options[0] = null;
  }

  document.form1.incluirum.disabled    = true;
  document.form1.incluirtodos.disabled = true;
  document.form1.excluirtodos.disabled = false;
  document.form1.cancelar.disabled     = false;
  document.form1.alunos.focus();
}

function js_excluir() {

  var F   = document.getElementById("alunos");
  var Tam = F.length;

  for( var x = 0; x< Tam; x++ ) {

    if( F.options[x].selected == true ) {

      document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[x].text,F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;
    }
  }

  if( document.form1.alunos.length > 0 ) {
    document.form1.alunos.options[0].selected = true;
  }

  if( F.length == 0 ) {

    document.form1.cancelar.disabled     = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;
  }

  document.form1.alunos.focus();
}

function js_excluirtodos() {

  var Tam = document.form1.alunos.length;
  var F   = document.getElementById("alunos");

  for( var i = 0; i < Tam; i++ ) {

    document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[0].text,F.options[0].value);
    F.options[0] = null;
  }

  if( F.length == 0 ) {

    document.form1.cancelar.disabled     = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;
  }

  document.form1.alunosdiario.focus();
}

function js_desabinc() {

  for( var i = 0; i < document.form1.alunosdiario.length; i++ ) {

    if( document.form1.alunosdiario.length > 0 && document.form1.alunosdiario.options[i].selected ) {

      if( document.form1.alunos.length > 0 ) {
        document.form1.alunos.options[0].selected = false;
      }

      document.form1.incluirum.disabled = false;
      document.form1.excluirum.disabled = true;
    }
  }
}

function js_desabexc() {

  for( var i = 0; i < document.form1.alunos.length; i++ ) {

    if( document.form1.alunos.length > 0 && document.form1.alunos.options[i].selected ) {

      if( document.form1.alunosdiario.length > 0 ) {
        document.form1.alunosdiario.options[0].selected = false;
      }

      document.form1.incluirum.disabled = true;
      document.form1.excluirum.disabled = false;
    }
  }
}

function js_selecionar() {

  var F = document.getElementById("alunos").options;
  for( var i = 0;i < F.length;i++ ) {
    F[i].selected = true;
  }

  return true;
}

function js_botao( valor ) {

  if( valor != "" ) {
    document.form1.procurar.disabled = false;
  } else {
    document.form1.procurar.disabled = true;
  }
}

function js_procurar( curso ) {
  location.href = "edu1_cancconclusao001.php?curso="+curso;
}

<?php
if( isset( $curso ) ) {
?>
  if( document.form1.alunosdiario.length == 0 ) {

    <?php
    if( !isset( $cancelar ) ) {
    ?>
      alert("Não existem alunos para cancelar conclusão para este curso!");
      location.href = "edu1_cancconclusao001.php";
    <?php
    }
    ?>
  }
<?php
}
?>
</script>