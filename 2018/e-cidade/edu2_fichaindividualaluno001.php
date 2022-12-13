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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));

$iEscola            = db_getsession("DB_coddepto");
$clmatricula        = new cl_matricula;
$clObsFichaIndAluno = new cl_obsfichaindaluno;
$clprocresultado    = new cl_procresultado;
$clprocavaliacao    = new cl_procavaliacao;
$clregencia         = new cl_regencia;
$clturma            = new cl_turma;
$resultedu          = eduparametros(db_getsession("DB_coddepto"));

$oRotulo = new rotulocampo();
$oRotulo->label("ed20_i_codigo");
$oRotulo->label("z01_numcgm");
$oRotulo->label("z01_nome");

if (isset($incluirobs)) {

  $resultobs = $clObsFichaIndAluno->sql_record($clObsFichaIndAluno->sql_query("","ed286_i_codigo",""," ed286_i_escola = $iEscola"));
  if ($clObsFichaIndAluno->numrows > 0) {

    db_fieldsmemory($resultobs,0);
    db_inicio_transacao();
    $clObsFichaIndAluno->ed286_i_escola   = $iEscola;
    $clObsFichaIndAluno->ed286_t_obs = $sObs;
    $clObsFichaIndAluno->ed286_i_codigo   = $ed286_i_codigo;
    $clObsFichaIndAluno->alterar($ed286_i_codigo);
    db_fim_transacao();

  } else {

    if ($sObs != "") {

      db_inicio_transacao();
      $clObsFichaIndAluno->ed286_i_escola   = $iEscola;
      $clObsFichaIndAluno->ed286_t_obs = $sObs;
      $clObsFichaIndAluno->incluir(null);
      db_fim_transacao();

    }
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, prototype.js, strings.js, arrays.js, windowAux.widget.js, datagrid.widget.js, webseller.js, dbcomboBox.widget.js");
  db_app::load("estilos.css, grid.style.css, DBFormularios.css");
?>
</head>
<SCRIPT>
 team = new Array(
 <?
 # Seleciona todos os calendários
  $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
  $sql       .= "       FROM calendario ";
  $sql       .= "        inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
  $sql       .= "       WHERE ed38_i_escola = $iEscola ";
  $sql       .= "       AND ed52_c_passivo = 'N' ";
  $sql       .= "       ORDER BY ed52_i_ano DESC ";
  $sql_result = db_query($sql);
  $num        = pg_num_rows($sql_result);
  $conta      = "";
  while ($row = pg_fetch_array($sql_result)) {

    $conta     = $conta+1;
    $cod_curso = $row["ed52_i_codigo"];
    echo "new Array(\n";
    $sub_sql    = " SELECT DISTINCT ed220_i_codigo,ed57_c_descr,ed11_c_descr ";
    $sub_sql   .= "         FROM turma ";
    $sub_sql   .= "          inner join matricula on ed60_i_turma = ed57_i_codigo ";
    $sub_sql   .= "          inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ";
    $sub_sql   .= "          inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ";
    $sub_sql   .= "          inner join serie on ed11_i_codigo = ed223_i_serie ";
    $sub_sql   .= "          inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
    $sub_sql   .= "                                    and ed221_i_serie = ed223_i_serie ";
    $sub_sql   .= "         WHERE ed57_i_calendario = '$cod_curso' ";
    $sub_sql   .= "         AND ed57_i_escola = $iEscola ";
    $sub_sql   .= "         AND ed221_c_origem = 'S' ";
    $sub_sql   .= "         ORDER BY ed57_c_descr,ed11_c_descr ";
    $sub_result = db_query($sub_sql);
    $num_sub    = pg_num_rows($sub_result);
    if ($num_sub >= 1) {

      # Se achar alguma base para o curso, marca a palavra Todas
      echo "new Array(\"\", ''),\n";
      $conta_sub = "";
      while ($rowx = pg_fetch_array($sub_result)) {

        $codigo_base = $rowx["ed220_i_codigo"];
        $base_nome   = $rowx["ed57_c_descr"];
        $serie_nome  = $rowx["ed11_c_descr"];
        $conta_sub   = $conta_sub+1;

        if ($conta_sub == $num_sub) {

          echo "new Array(\"$base_nome - $serie_nome\", $codigo_base)\n";
          $conta_sub = "";

        } else {
          echo "new Array(\"$base_nome - $serie_nome\", $codigo_base),\n";
        }

      }
    } else {

      #Se nao achar base para o curso selecionado...
      echo "new Array(\"Calendário sem turmas cadastradas\", '')\n";

    }

    if ($num > $conta) {
      echo "),\n";
    }
}
echo ")\n";
echo ");\n";
?>
//Inicio da função JS
function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
  var i, j;
  var prompt;
  // empty existing items
  for (i = selectCtrl.options.length; i >= 0; i--) {
    selectCtrl.options[i] = null;
  }
  prompt = (itemArray != null) ? goodPrompt : badPrompt;
  if (prompt == null) {
    document.form1.subgrupo.disabled = true;
    j = 0;
  } else {
    selectCtrl.options[0] = new Option(prompt);
    j = 1;
  }
  if (itemArray != null) {
   // add new items
    for (i = 0; i < itemArray.length; i++) {
      selectCtrl.options[j] = new Option(itemArray[i][0]);
      if (itemArray[i][1] != null) {
        selectCtrl.options[j].value = itemArray[i][1];
      }
      j++;
    }
    selectCtrl.options[0].selected   = true;
    document.form1.subgrupo.disabled = false;
  }
  document.form1.procurar.disabled = true;
 <?if (isset($turma)) {?>
     qtd = document.form1.alunosdiario.length;
     for (i = 0; i < qtd; i++) {
       document.form1.alunosdiario.options[0] = null;
     }
     qtd = document.form1.alunos.length;
     for (i = 0; i < qtd; i++) {
       document.form1.alunos.options[0] = null;
     }
 <?}?>
}
function fillSelectFromArray2(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
  var i, j;
  var prompt;
  // empty existing items
  for (i = selectCtrl.options.length; i >= 0; i--) {
    selectCtrl.options[i] = null;
  }
  prompt = (itemArray != null) ? goodPrompt : badPrompt;
  if (prompt == null) {
    document.form1.subgrupo.disabled = true;
    j = 0;
  } else {
    selectCtrl.options[0] = new Option(prompt);
    j = 1;
  }
  if (itemArray != null) {
  // add new items
    for (i = 0; i < itemArray.length; i++) {
      selectCtrl.options[j] = new Option(itemArray[i][0]);
      if (itemArray[i][1] != null) {
        selectCtrl.options[j].value = itemArray[i][1];
      }
    <?if (isset($turma)) {?>
        if (<?=trim($turma)?> == itemArray[i][1]) {
          indice = i;
        }
    <?}?>
      j++;
  }
  <?if (isset($turma)) {?>
      selectCtrl.options[indice].selected = true;
      document.form1.procurar.disabled    = false;
  <?} else {?>
      selectCtrl.options[0].selected = true;
  <?}?>
    document.form1.subgrupo.disabled = false;
  }
}
//End -->
</script>
<body bgcolor="#CCCCCC" onLoad="a=1;" >
<form name="form1" method="post" action="" class="container">
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<br>
<fieldset><legend><b>Relatório Ficha Individual do Aluno</b></legend>

<table class="form-container">
 <tr>
  <td>
    <b>Selecione o Calendário:</b>
  </td>
  <td>
    <select name="grupo"
            onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));"
            style="font-size:9px;width:200px;height:18px;">
     <option></option>
     <?
     #Seleciona todos os grupos para setar os valores no combo
     $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
     $sql       .= "       FROM calendario ";
     $sql       .= "        inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
     $sql       .= "       WHERE ed38_i_escola = $iEscola ";
     $sql       .= "      AND ed52_c_passivo = 'N' ";
     $sql       .= "       ORDER BY ed52_i_ano DESC ";
     $sql_result = db_query($sql);
     while($row = pg_fetch_array($sql_result)) {
       $cod_curso  = $row["ed52_i_codigo"];
       $desc_curso = $row["ed52_c_descr"];
       ?>
        <option value="<?=$cod_curso;?>" <?=$cod_curso==@$calendario?"selected":""?>><?=$desc_curso;?></option>
       <?
     }
     #Popula o segundo combo de acordo com a escolha no primeiro
     ?>
    </select>
  </td>
  <td>
    <b>Selecione a Turma:</b>
  </td>
  <td>
    <select name="subgrupo" style="font-size:9px;width:200px;height:18px;" disabled onchange="js_botao(this.value);">
     <option value=""></option>
    </select>

    <input type="button" name="procurar" value="Procurar"
           onclick="js_procurar(document.form1.grupo.value,document.form1.subgrupo.value)" disabled>
  </td>



 </tr>
 <?if (isset($turma)) {
  ?>
  <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
  <tr>
  <td colspan="4">

  <table class="subtable">
    <tr>
      <td>
         <?
         $result_proced = $clturma->sql_record($clturma->sql_query_turmaserie("",
                                                                              "ed220_i_procedimento as procedimento",
                                                                              "",
                                                                              " ed220_i_codigo = $turma"
                                                                             )
                                              );
         db_fieldsmemory($result_proced,0);
           //$where = " AND ed60_c_situacao = 'MATRICULADO'";
         $sql    = " SELECT ed47_i_codigo,ed47_v_nome,ed60_i_codigo ";
         $sql   .= "       FROM matricula ";
         $sql   .= "        inner join aluno on ed47_i_codigo = ed60_i_aluno ";
         $sql   .= "        inner join turma on ed57_i_codigo = ed60_i_turma ";
         $sql   .= "        inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ";
         $sql   .= "       inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ";
         $sql   .= "        inner join serie on ed11_i_codigo = ed223_i_serie ";
         $sql   .= "        inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
         $sql   .= "       WHERE ed220_i_codigo = $turma ";
         $sql   .= "       AND ed60_c_ativa = 'S' ";
         $sql   .= "      AND ed221_c_origem = 'S' ";
         $sql   .= "       AND ed221_i_serie = ed223_i_serie ";
         //$sql   .= "       $where ";
         $sql   .= "       ORDER BY ed60_i_numaluno,to_ascii(ed47_v_nome) ";
         $result = db_query($sql);
         $linhas = pg_num_rows($result);
         ?>
         <b>Alunos:</b><br>
         <select name="alunosdiario" id="alunosdiario" size="10" onclick="js_desabinc()"
                 style="font-size:9px;width:340px;height:120px" multiple>
          <?
          for ($i = 0; $i < $linhas; $i++) {

            db_fieldsmemory($result,$i);
            echo "<option value='$ed60_i_codigo'>$ed47_i_codigo - $ed47_v_nome</option>\n";

          }
          ?>
         </select>
       </td>

     <td>

      <input name="incluirum" title="Incluir" type="button" value=">"
             onclick="js_incluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>

      <br>
      <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();"
             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" <?=$linhas==0?"disabled":""?>>

      <hr style="width:30px">


      <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();"
             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
      <br>
      <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();"
             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
    </td>

    <td>

     <b>Alunos para gerar Ficha Individual:</b><br>
     <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()"
             style="font-size:9px;width:350px;height:120px" multiple>
     </select>
     </td>
    </tr>
  </table>

  </td>
  </tr>

  <tr>
    <td colspan="4">
      <label>
        <input type="checkbox" value="S" name="lExibeAssinaturaProfessor" id="lExibeAssinaturaProfessor" />
        <strong>Exibe Assinatura do Professor</strong>
      </label>
    </td>
  </tr>

 <?
 $sCampos     = "ed37_c_tipo,ed37_i_menorvalor,ed37_i_maiorvalor,ed37_c_minimoaprov";
 $result_proc = $clprocresultado->sql_record($clprocresultado->sql_query("",
                                                                         $sCampos,
                                                                         "",
                                                                         " ed43_i_procedimento = $procedimento and ed43_c_geraresultado='S' "
                                                                        )
                                            );
 if ($clprocresultado->numrows > 0) {
   db_fieldsmemory($result_proc,0);
 } else {

   $sCampos      = "ed37_c_tipo,ed37_i_menorvalor,ed37_i_maiorvalor,ed37_c_minimoaprov";
   $result_proc1 = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("",
                                                                            $sCampos,
                                                                            "ed41_i_sequencia desc",
                                                                            " ed41_i_procedimento = $procedimento"
                                                                           )
                                               );
   db_fieldsmemory($result_proc1,0);

 }
 ?>
 <?
 if (trim($ed37_c_tipo) == "PARECER") {
  ?>
  <tr>
   <td>
    <b>Disciplinas:</b>
    <?
    $sql2     = " SELECT ed59_i_codigo,ed232_c_descr ";
    $sql2    .= "           FROM regencia ";
    $sql2    .= "        inner join disciplina on disciplina.ed12_i_codigo = regencia.ed59_i_disciplina ";
    $sql2    .= "        inner join caddisciplina on  ed232_i_codigo = ed12_i_caddisciplina ";
    $sql2    .= "        inner join turma on turma.ed57_i_codigo = regencia.ed59_i_turma ";
    $sql2    .= "        inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ";
    $sql2    .= "        inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ";
    $sql2    .= "        inner join serie on ed11_i_codigo = ed223_i_serie ";
    $sql2    .= "       WHERE ed220_i_codigo = $turma ";
    $sql2    .= "       AND ed59_c_freqglob != 'F' ";
    $sql2    .= "       AND ed59_c_condicao = 'OB' ";
    $sql2    .= "       AND ed223_i_serie = ed59_i_serie ";
    $sql2    .= "       ORDER BY ed59_i_ordenacao ";
    $result_r = $clregencia->sql_record($sql2);
    ?>
    </td>
    <td colspan="3">
    <select name="disciplinas" id="disciplinas" style="width:605px;">
     <?
     if ($clregencia->numrows > 1) {

       echo "<option value='PU'>PARECER ÚNICO</option>";
       echo"<option value='T'>TODAS</option>";

     }

     for($y = 0; $y < $clregencia->numrows; $y++) {

       db_fieldsmemory($result_r,$y);
       echo "<option value='$ed59_i_codigo'>$ed232_c_descr</option>";

     }
     ?>
    </select>
   </td>
  </tr>
  <?
 }
 ?>

 <tr>
  <td>
    <?
      db_ancora("<b>Assinatura Adicional: </b>", "js_pesquisaRecHumano(true);", 1);
    ?>
  </td>
  <td colspan="3">
    <?
      db_input("ed20_i_codigo", 6, $Ied20_i_codigo, true, "text", 1, "onChange='js_pesquisaRecHumano(false);'");
      db_input("z01_numcgm", 10, $Iz01_numcgm, true, "hidden", 3);
      db_input("z01_nome", 73, $Iz01_nome, true, "text", 3);
    ?>
    </td>
  </tr>
  <tr>
    <td><b>Atividades:</b></td>
    <td id='ctnAtividades' colspan="3"></td>
  </tr>

 <tr>
  <td colspan="4">
   <?

   $resultobs = $clObsFichaIndAluno->sql_record($clObsFichaIndAluno->sql_query("","ed286_t_obs",""," ed286_i_escola = $iEscola"));
   if ($clObsFichaIndAluno->numrows > 0) {

     db_fieldsmemory($resultobs,0);
     $sObs = $ed286_t_obs;

   }
   ?>
   <fieldset>
     <legend><b>Mensagem da Ficha Individual:</b></legend>
     <?db_textarea('sObs',3,95,@$obs,true,'text',@$db_opcao,"")?><br>
   </fieldset>
  </td>
 </tr>
 <tr>
  <td colspan="4">
    <div class="center">
     <?if (trim($ed37_c_tipo) == "PARECER") {?>

         <input name="pesquisar" type="button" id="pesquisar" value="Imprimir"
                onclick="js_pesquisa2(document.form1.subgrupo.value);" disabled>

     <?} else {?>

         <input name="pesquisar" type="button" id="pesquisar" value="Imprimir"
                onclick="js_pesquisa(document.form1.subgrupo.value);" disabled>

     <?}?>
     <br><br>
     <fieldset>
       Para selecionar mais de um aluno<br>mantenha pressionada a tecla CTRL <br>e clique sobre o nome dos alunos.
     </fieldset>
     <input type="hidden" name="base" value="<?= isset($base) ? $base : ''; ?>">
     <input type="hidden" name="curso" value="<?= isset($curso) ? $curso : ''; ?>">
   </div>
  </td>
 </tr>
 <?}?>
</table>
</fieldset>
</form>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
function js_padrao() {

  if (document.form1.padrao.checked == true) {
    document.getElementById("optpadrao").style.visibility = "visible";
  } else {
    document.getElementById("optpadrao").style.visibility = "hidden";
  }

}

function js_incluir() {
  var Tam = document.form1.alunosdiario.length;
  var F   = document.form1;
  for(var x = 0; x < Tam; x++) {

    if (F.alunosdiario.options[x].selected == true) {

      F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[x].text,F.alunosdiario.options[x].value)
      F.alunosdiario.options[x] = null;
      Tam--;
      x--;

    }

  }
  if (document.form1.alunosdiario.length > 0) {
    document.form1.alunosdiario.options[0].selected = true;
  } else {

    document.form1.incluirum.disabled    = true;
    document.form1.incluirtodos.disabled = true;

  }

  document.form1.pesquisar.disabled    = false;
  document.form1.excluirtodos.disabled = false;
  document.form1.alunosdiario.focus();
}

function js_incluirtodos() {

  var Tam = document.form1.alunosdiario.length;
  var F   = document.form1;
  for(var i=0;i<Tam;i++){

    F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[0].text,F.alunosdiario.options[0].value)
    F.alunosdiario.options[0] = null;

  }
  document.form1.incluirum.disabled    = true;
  document.form1.incluirtodos.disabled = true;
  document.form1.excluirtodos.disabled = false;
  document.form1.pesquisar.disabled    = false;
  document.form1.alunos.focus();
}

function js_excluir() {

  var F = document.getElementById("alunos");
  Tam   = F.length;
  for(var x = 0; x < Tam; x++) {

    if (F.options[x].selected == true) {

      document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[x].text,F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;

    }
  }
  if (document.form1.alunos.length>0){
    document.form1.alunos.options[0].selected = true;
  }

  if (F.length == 0) {

    document.form1.pesquisar.disabled    = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;

  }
  document.form1.alunos.focus();
}

function js_excluirtodos() {

  var Tam = document.form1.alunos.length;
  var F = document.getElementById("alunos");
  for (var i = 0; i < Tam; i++) {

    document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[0].text,F.options[0].value);
    F.options[0] = null;

  }
  if (F.length == 0) {

    document.form1.pesquisar.disabled    = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;

  }
  document.form1.alunosdiario.focus();
}

function js_desabinc() {

  for(var i = 0; i < document.form1.alunosdiario.length; i++) {

    if (document.form1.alunosdiario.length>0 && document.form1.alunosdiario.options[i].selected) {

      if (document.form1.alunos.length>0){
        document.form1.alunavaliacaoos.options[0].selected = false;
      }

      document.form1.incluirum.disabled = false;
      document.form1.excluirum.disabled = true;
    }

  }
}

function js_desabexc() {

  for(var i = 0; i < document.form1.alunos.length; i++) {

    if (document.form1.alunos.length>0 && document.form1.alunos.options[i].selected) {

      if (document.form1.alunosdiario.length>0) {
        document.form1.alunosdiario.options[0].selected = false;
      }
      document.form1.incluirum.disabled = true;
      document.form1.excluirum.disabled = false;
    }

  }
}

function js_botao(valor) {

  if (valor != "") {
    document.form1.procurar.disabled = false;
  } else {
    document.form1.procurar.disabled = true;
  }
<?if (isset($turma)) {?>

    qtd = document.form1.alunosdiario.length;
    for (var i = 0; i < qtd; i++) {
      document.form1.alunosdiario.options[0] = null;
    }
    qtd = document.form1.alunos.length;
    for (var i = 0; i < qtd; i++) {
      document.form1.alunos.options[0] = null;
    }
<?}?>
}

function js_procurar(calendario,turma) {

  location.href = "edu2_fichaindividualaluno001.php?calendario="+calendario+"&turma="+turma;
}

function js_pesquisa(turma) {
	 F = document.form1.alunos;
	 alunos = "";
	 sep = "";
	 for(i=0;i<F.length;i++){
	  alunos += sep+F.options[i].value;
	  sep = ",";
	 }

  var sParametros  = 'calendario='                 + document.form1.grupo.value;
      sParametros += '&alunos='                    + alunos;
      sParametros += '&sObs='                      + btoa($F('sObs'));
      sParametros += '&iAssinaturaAdicional='      + $F('ed20_i_codigo');
      sParametros += '&lExibeAssinaturaProfessor=' + $F('lExibeAssinaturaProfessor');
      sParametros += '&iAtividade='                + oCboAtividades.getValue();
      sParametros += '&incluirobs';


 jan = window.open('edu2_fichaindividualaluno002.php?' + sParametros,'',
         'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');

 location.href = "edu2_fichaindividualaluno001.php?calendario="+document.form1.grupo.value+
 "&turma="+document.form1.subgrupo.value+"&sObs="+document.form1.sObs.value+"&incluirobs";

}

function js_DiasLetivos(valor) {
  if (valor == "S") {
    document.getElementById("colunas").style.visibility = "hidden";
  } else {
    document.getElementById("colunas").style.visibility = "visible";
  }
}

function js_pesquisa2(turma) {
 F = document.form1.alunos;
 alunos = "";
 sep = "";
 for (var i = 0; i < F.length; i++) {

   alunos += sep+F.options[i].value;
   sep = ",";

 }
 disciplinas = "";
 if (document.form1.disciplinas.value == "T") {
   D = document.form1.disciplinas;
   sep = "";
   for (var i = 2; i < D.length; i++) {

     disciplinas += sep+D.options[i].value;
     sep = ",";

   }
   punico = "no";

 } else if(document.form1.disciplinas.value == "PU") {

   D = document.form1.disciplinas;
   disciplinas = D.options[2].value;
   punico = "yes";

 } else {

  disciplinas = document.form1.disciplinas.value;
  punico = "no";

 }

 var sParametros  = 'punico='                     + punico;
     sParametros += '&disciplinas='               + disciplinas;
     sParametros += '&turma='                     + turma;
     sParametros += '&alunos='                    + alunos;
     sParametros += '&obs1='                      + btoa( $F('sObs') );
     sParametros += '&iAssinaturaAdicional='      + $F('ed20_i_codigo');
     sParametros += '&lExibeAssinaturaProfessor=' + $F('lExibeAssinaturaProfessor');
     sParametros += '&iAtividade='                + oCboAtividades.getValue();
     sParametros += '&incluirobs';

 jan = window.open( 'edu2_fichaindividualaluno003.php?' + sParametros,
                    '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

 location.href = "edu2_fichaindividualaluno001.php?calendario="+document.form1.grupo.value+
 "&turma="+document.form1.subgrupo.value+"&sObs="+document.form1.sObs.value+"&incluirobs";
}
<?if (!isset($turma) && pg_num_rows($sql_result) > 0) {?>
    fillSelectFromArray2(document.form1.subgrupo,team[0]);
    document.form1.grupo.options[1].selected = true;
<?}?>

/**
 * Pesquisamos os recursos humanos vinculados a escola
 */
function js_pesquisaRecHumano(lMostra) {

  if (lMostra) {

    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_rechumano',
                        'func_rechumanoescolanovo.php?funcao_js=parent.js_mostraRecHumano|ed20_i_codigo|z01_nome|z01_numcgm',
                        'Pesquisa Recurso Humano',
                        true
                       );
  } else if ($F('ed20_i_codigo') != '') {

    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_rechumano',
                        'func_rechumanoescolanovo.php?funcao_js=parent.js_mostraRecHumano1&pesquisa_chave='+$F('ed20_i_codigo'),
                        'Pesquisa Recurso Humano',
                        false
                       );
  } else {

    $('ed20_i_codigo').value = '';
    $('z01_nome').value      = '';
    $('z01_numcgm').value    = '';
    oCboAtividades.clearItens();
    oCboAtividades.setDisable(true);
  }
}

function js_mostraRecHumano() {

  $('ed20_i_codigo').value = arguments[0];
  $('z01_nome').value      = arguments[1];
  $('z01_numcgm').value    = arguments[2];
  db_iframe_rechumano.hide();
  js_atividadesDocente();
}

function js_mostraRecHumano1() {

  $('z01_nome').value   = arguments[0];
  $('z01_numcgm').value = arguments[1];

  if (arguments[1] == true) {

    $('ed20_i_codigo').value = '';
    $('z01_nome').value      = arguments[0];
    $('z01_numcgm').value    = '';
    oCboAtividades.setDisable(true);
  } else {
    js_atividadesDocente();
  }
}

oCboAtividades = new DBComboBox("cboAtividades", "oCboAtividades", null, "605px");
oCboAtividades.addItem("", "");
oCboAtividades.show($('ctnAtividades'));
oCboAtividades.setDisable(true);

/**
 * Buscamos as atividades do docente na escola
 */
function js_atividadesDocente() {

   var oParametro     = new Object();
   oParametro.exec    = 'buscaAtividadesServidor';
   oParametro.iNumCgm = $F('z01_numcgm');

   js_divCarregando("Aguarde, carregando as atividades do funcionário.", "msgBox");
   var oAjax = new Ajax.Request(
                                'edu_educacaobase.RPC.php',
                                {
                                  method: 'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornaAtividadesDocente
                                }
                               );
}

function js_retornaAtividadesDocente(oResponse) {

  oCboAtividades.setEnable(true);
  oCboAtividades.clearItens();
  oCboAtividades.addItem("", "");
  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.aAtividades.length > 0) {

    oRetorno.aAtividades.each(function(oLinha, iSeq) {

      oCboAtividades.addItem(oLinha.iCodigo, oLinha.sDescricao.urlDecode());
      if (oRetorno.aAtividades.length == 1) {
        oCboAtividades.setValue(oLinha.iCodigo);
      }
    });
  }

}
</script>