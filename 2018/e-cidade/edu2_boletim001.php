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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$escola          = db_getsession("DB_coddepto");
$clmatricula     = new cl_matricula;
$clobsboletim    = new cl_obsboletim;
$clprocresultado = new cl_procresultado;
$clprocavaliacao = new cl_procavaliacao;
$clregencia      = new cl_regencia;
$clturma         = new cl_turma;
$resultedu       = eduparametros(db_getsession("DB_coddepto"));

db_postmemory($_GET);

/*

if (isset($incluirobs)) {

  $resultobs = $clobsboletim->sql_record($clobsboletim->sql_query("","ed252_i_codigo",""," ed252_i_escola = $escola"));
  if ($clobsboletim->numrows > 0) {

    db_fieldsmemory($resultobs,0);
    db_inicio_transacao();
    $clobsboletim->ed252_i_escola   = $escola;
    $clobsboletim->ed252_t_mensagem = $obs;
    $clobsboletim->ed252_i_codigo   = $ed252_i_codigo;
    //$clobsboletim->alterar($ed252_i_codigo);
    db_fim_transacao();
  } else {

    if ($obs != "") {

      db_inicio_transacao();
      $clobsboletim->ed252_i_escola   = $escola;
      $clobsboletim->ed252_t_mensagem = $obs;
    //  $clobsboletim->incluir(null);
      db_fim_transacao();
    }
  }
}
  */

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/DBFormCache.js"></script>
<script type="text/javascript" src="scripts/DBFormSelectCache.js"></script>
<script type="text/javascript" src="scripts/AjaxRequest.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<SCRIPT LANGUAGE="JavaScript">
  team = new Array(
  <?php
  # Seleciona todos os calendários
  $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
  $sql       .= "   FROM calendario ";
  $sql       .= "        inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
  $sql       .= "  WHERE ed38_i_escola  = $escola ";
  $sql       .= "    AND ed52_c_passivo = 'N' ";
  $sql       .= "  ORDER BY ed52_i_ano DESC ";
  $sql_result = db_query($sql);
  $num        = pg_num_rows($sql_result);
  $conta      = "";

  while ($row = pg_fetch_array($sql_result)) {

    $conta     = $conta+1;
    $cod_curso = $row["ed52_i_codigo"];
    echo "new Array(\n";
    $sub_sql    = " SELECT DISTINCT ed220_i_codigo,ed57_c_descr,ed11_c_descr ";
    $sub_sql   .= "   FROM turma ";
    $sub_sql   .= "        inner join matricula           on ed60_i_turma      = ed57_i_codigo ";
    $sub_sql   .= "        inner join turmaserieregimemat on ed220_i_turma     = ed57_i_codigo ";
    $sub_sql   .= "        inner join serieregimemat      on ed223_i_codigo    = ed220_i_serieregimemat ";
    $sub_sql   .= "        inner join serie               on ed11_i_codigo     = ed223_i_serie ";
    $sub_sql   .= "        inner join matriculaserie      on ed221_i_matricula = ed60_i_codigo ";
    $sub_sql   .= "                                      and ed221_i_serie     = ed223_i_serie ";
    $sub_sql   .= "  WHERE ed57_i_calendario = '$cod_curso' ";
    $sub_sql   .= "    AND ed57_i_escola     = $escola ";
    $sub_sql   .= "    AND ed221_c_origem    = 'S' ";
    $sub_sql   .= "  ORDER BY ed57_c_descr,ed11_c_descr ";
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;" >
  <table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
  <div class="center">
    <form name="form1" method="post" action="">
      <?php
      MsgAviso(db_getsession("DB_coddepto"),"escola");
      ?>
      <br>
      <fieldset style="width:95%">
        <legend><b>Relatório Boletim de Desempenho</b></legend>
        <table border="0" align="left">
          <tr>
            <td colspan="3">
              <table border="0" align="left">
                <tr>
                  <td>
                    <b>Selecione o Calendário:</b><br>
                    <select name="grupo"
                            onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));"
                            style="font-size:9px;width:200px;height:18px;">
                      <option></option>
                      <?php
                      #Seleciona todos os grupos para setar os valores no combo
                      $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
                      $sql       .= "   FROM calendario ";
                      $sql       .= "        inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
                      $sql       .= "  WHERE ed38_i_escola  = $escola ";
                      $sql       .= "    AND ed52_c_passivo = 'N' ";
                      $sql       .= "  ORDER BY ed52_i_ano DESC ";
                      $sql_result = db_query($sql);

                      while( $row = pg_fetch_array($sql_result) ) {

                        $cod_curso  = $row["ed52_i_codigo"];
                        $desc_curso = $row["ed52_c_descr"];
                        ?>
                        <option value="<?=$cod_curso;?>" <?=isset( $calendario ) && $cod_curso == $calendario ? "selected" : ""?>><?=$desc_curso;?></option>
                        <?php
                      }
                      #Popula o segundo combo de acordo com a escolha no primeiro
                      ?>
                    </select>
                  </td>
                  <td>
                    <b>Selecione a Turma:</b><br>
                    <select name="subgrupo"
                            style="font-size:9px;width:200px;height:18px;"
                            disabled
                            onchange="js_botao(this.value);">
                      <option value=""></option>
                    </select>
                  </td>
                  <td>
                    <b>Exibir Trocas de Turma:</b><br/>
                    <select id='trocaTurma' name='trocaTurma' style="font-size:9px;width:200px;height:18px;">
                      <option value="1" selected="selected">Não</option>
                      <option value="2">Sim</option>
                    </select>
                  </td>
                  <td valign='bottom'>
                    <input type="checkbox"
                           name="inativos"
                           value="" <?=isset( $inativos ) && $inativos == "yes" ? "checked" : ""?> > Somente alunos inativos
                    <input type="button" name="procurar" value="Procurar"
                           onclick="js_procurar(document.form1.grupo.value,document.form1.subgrupo.value)" disabled>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <?php
          if( isset( $turma ) ) {
            ?>
            <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
            <tr>
              <td valign="top">
                <?php
                $sSqlTurma = $clturma->sql_query_turmaserie(
                                                             "",
                                                             "ed220_i_procedimento as procedimento",
                                                             "",
                                                             " ed220_i_codigo = $turma"
                                                           );
                $result_proced = $clturma->sql_record( $sSqlTurma );
                db_fieldsmemory($result_proced,0);

                $where = '';
                if ($inativos == "yes") {
                  $where = " AND ed60_c_situacao != 'MATRICULADO'";
                } else {
                  $where = " AND ed60_c_situacao = 'MATRICULADO'";
                }

                if ( isset( $trocaTurma ) && $trocaTurma == 1) {

                  if (empty($where)) {
                    $where = " AND ed60_c_situacao != 'TROCA DE TURMA'";
                  } else {
                    $where .= " AND ed60_c_situacao != 'TROCA DE TURMA'";
                  }
                }

                $sql    = " SELECT ed47_i_codigo,ed47_v_nome,ed60_i_codigo ";
                $sql   .= "   FROM matricula ";
                $sql   .= "        inner join aluno               on ed47_i_codigo     = ed60_i_aluno ";
                $sql   .= "        inner join turma               on ed57_i_codigo     = ed60_i_turma ";
                $sql   .= "        inner join turmaserieregimemat on ed220_i_turma     = ed57_i_codigo ";
                $sql   .= "        inner join serieregimemat      on ed223_i_codigo    = ed220_i_serieregimemat ";
                $sql   .= "        inner join serie               on ed11_i_codigo     = ed223_i_serie ";
                $sql   .= "        inner join matriculaserie      on ed221_i_matricula = ed60_i_codigo ";
                $sql   .= "  WHERE ed220_i_codigo = {$turma} ";
                $sql   .= "    AND ed60_c_ativa   = 'S' ";
                $sql   .= "    AND ed221_c_origem = 'S' ";
                $sql   .= "    AND ed221_i_serie  = ed223_i_serie ";
                $sql   .= "    {$where} ";
                $sql   .= "  ORDER BY ed60_i_numaluno,to_ascii(ed47_v_nome) ";

                $result = db_query($sql);
                $linhas = pg_num_rows($result);
                ?>
                <b>Alunos:</b><br>
                <select name="alunosdiario" id="alunosdiario" size="10" onclick="js_desabinc()"
                        style="font-size:9px;width:330px;height:120px" multiple>
                <?php
                for($i = 0; $i < $linhas; $i++) {

                  db_fieldsmemory($result,$i);
                  echo "<option value='$ed60_i_codigo'>$ed47_i_codigo - $ed47_v_nome</option>\n";
                }
                ?>
              </select>
            </td>
            <td align="center">
              <br>
              <table border="0">
                <tr>
                  <td>
                    <input name="incluirum" title="Incluir" type="button" value=">"
                           onclick="js_incluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;
                           background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
                  </td>
                </tr>
                <tr>
                  <td height="1"></td>
                </tr>
                <tr>
                  <td>
                    <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();"
                           style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                           font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" <?=$linhas==0?"disabled":""?>>
                  </td>
                </tr>
                <tr>
                  <td height="3"></td>
                </tr>
                <tr>
                  <td><hr></td>
                </tr>
                <tr>
                  <td height="3"></td>
                </tr>
                <tr>
                  <td>
                    <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();"
                           style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;
                           background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
                  </td>
                </tr>
                <tr>
                  <td height="1"></td>
                </tr>
                <tr>
                  <td>
                    <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();"
                           style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;
                           background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
                  </td>
                </tr>
              </table>
            </td>
            <td valign="top">
              <b>Alunos para gerar boletim:</b><br>
              <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()"
                      style="font-size:9px;width:330px;height:120px" multiple>
              </select>
            </td>
          </tr>
          <?php
          $sCampos     = "ed37_c_tipo,ed37_i_menorvalor,ed37_i_maiorvalor,ed37_c_minimoaprov";
          $sWhere      = " ed43_i_procedimento = {$procedimento} and ed43_c_geraresultado='S' ";
          $sSql        = $clprocresultado->sql_query( "", $sCampos, "", $sWhere );
          $result_proc = $clprocresultado->sql_record($sSql);

          if ($clprocresultado->numrows > 0) {
            db_fieldsmemory($result_proc,0);
          } else {

            $sCampos           = "ed37_c_tipo,ed37_i_menorvalor,ed37_i_maiorvalor,ed37_c_minimoaprov";
            $sWhere            = " ed41_i_procedimento = {$procedimento}";
            $sSqlProcAvaliacao = $clprocavaliacao->sql_query("", $sCampos, "ed41_i_sequencia desc", $sWhere );
            $result_proc1      = $clprocavaliacao->sql_record( $sSqlProcAvaliacao );

            db_fieldsmemory($result_proc1,0);
          }
          ?>
          <tr>
            <td colspan="3">
              <b>Forma de Avaliação do Resultado Final:</b>
              <?php
              if ($clprocresultado->numrows > 0) {

                if (trim($ed37_c_tipo) == "PARECER") {

                  echo $ed37_c_tipo;
                  ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;<b>BOLETIM POR PARECER DESCRITIVO</b>

                  <?php
                } else if (trim($ed37_c_tipo) == "NIVEL") {

                  echo $ed37_c_tipo;
                  ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;<b>BOLETIM DE DESEMPENHO</b>
                  <?php
                } else {

                  echo $ed37_c_tipo;
                  ?>
                  ( <?php echo $resultedu == "S" ? number_format($ed37_i_menorvalor, 2, ",", ".") : number_format($ed37_i_menorvalor, 0);?>
                  a <?php echo $resultedu == "S" ? number_format($ed37_i_maiorvalor, 2, ",", ".") : number_format($ed37_i_maiorvalor, 0);?>
                  - Mínimo para Aprovação:
                  <?php echo $resultedu == "S" ? number_format($ed37_c_minimoaprov, 2, ",", ".") : number_format($ed37_c_minimoaprov, 0);?> )
                  &nbsp;&nbsp;&nbsp;&nbsp; <b>BOLETIM DE DESEMPENHO</b>
                  <?php
                }
              } else {
                echo "Nenhum resultado cadastrado no procedimento de avaliação.";
              }
              ?>
            </td>
          </tr>
          <?php
          if (trim($ed37_c_tipo) == "PARECER") {

            ?>
            <tr>
              <td colspan="3">
                <b>Período de Avaliação:</b>
                <?php
                $sql2    = " SELECT ed41_i_codigo, ";
                $sql2   .= "        ed09_c_descr, ";
                $sql2   .= "        ed41_i_sequencia, ";
                $sql2   .= "        case ";
                $sql2   .= "             when ed41_i_codigo > 0";
                $sql2   .= "             then 'A' ";
                $sql2   .= "         end as tipo ";
                $sql2   .= "   FROM procavaliacao ";
                $sql2   .= "        inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao ";
                $sql2   .= "        inner join formaavaliacao   on formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao ";
                $sql2   .= "  WHERE ed41_i_procedimento = $procedimento ";
                $sql2   .= "  UNION ";
                $sql2   .= " SELECT ed43_i_codigo, ";
                $sql2   .= "        ed42_c_descr, ";
                $sql2   .= "        ed43_i_sequencia, ";
                $sql2   .= "        case ";
                $sql2   .= "             when ed43_i_codigo > 0";
                $sql2   .= "             then 'R'";
                $sql2   .= "         end as tipo ";
                $sql2   .= "   FROM procresultado ";
                $sql2   .= "        inner join resultado      on resultado.ed42_i_codigo      = procresultado.ed43_i_resultado ";
                $sql2   .= "        inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procresultado.ed43_i_formaavaliacao ";
                $sql2   .= "  WHERE ed43_i_procedimento = {$procedimento} ";
                $sql2   .= "  ORDER BY ed41_i_sequencia ";
                $result2 = db_query($sql2);
                $linhas2 = pg_num_rows($result2);
                ?>
                <select name="periodo" id="periodo" style="font-size:9px;width:180px;">
                  <?php
                  for ($y = 0; $y < $linhas2; $y++) {

                    db_fieldsmemory($result2,$y);
                    echo "<option value='$tipo|$ed41_i_codigo'>$ed09_c_descr</option>";
                  }
                  ?>
                </select>
                <b>Orientação:</b>
                <select name="modelo" id="modelo" style="font-size:9px;" Onchange="js_remove();">
                  <option value='M1'>MODELO 1 - 2 por página</option>
                  <option value='M2'>MODELO 2 - 1 por página</option>
                  <option value='M3'>MODELO 3 - Cumulativo</option>
                  <option value='M4'>MODELO 4</option>
                </select>
                <b>Disciplinas:</b>
                <?php
                $sql2     = " SELECT ed59_i_codigo,ed232_c_descr ";
                $sql2    .= "   FROM regencia ";
                $sql2    .= "        inner join disciplina          on disciplina.ed12_i_codigo = regencia.ed59_i_disciplina ";
                $sql2    .= "        inner join caddisciplina       on  ed232_i_codigo          = ed12_i_caddisciplina ";
                $sql2    .= "        inner join turma               on turma.ed57_i_codigo      = regencia.ed59_i_turma ";
                $sql2    .= "        inner join turmaserieregimemat on ed220_i_turma            = ed57_i_codigo ";
                $sql2    .= "        inner join serieregimemat      on ed223_i_codigo           = ed220_i_serieregimemat ";
                $sql2    .= "        inner join serie               on ed11_i_codigo            = ed223_i_serie ";
                $sql2    .= "  WHERE ed220_i_codigo   = {$turma} ";
                $sql2    .= "    AND ed59_c_freqglob != 'F' ";
                $sql2    .= "    AND ed59_c_condicao  = 'OB' ";
                $sql2    .= "    AND ed223_i_serie    = ed59_i_serie ";
                $sql2    .= "  ORDER BY ed59_i_ordenacao ";
                $result_r = $clregencia->sql_record($sql2);
                ?>
                <select name="disciplinas" id="disciplinas" style="font-size:9px;width:180px;">
                  <?php
                  if ($clregencia->numrows > 1) {

                    echo "<option value='PU'>PARECER ÚNICO</option>";
                    echo "<option value='T'>TODAS</option>";
                  }

                  for($y = 0; $y < $clregencia->numrows; $y++) {

                    db_fieldsmemory($result_r,$y);
                    echo "<option value='$ed59_i_codigo'>$ed232_c_descr</option>";
                  }
                  ?>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan='3'>
                <b>Pareceres Padronizados:</b>
                <select name="padraotipo" style="font-size:9px;height:16px;">
                  <option value="C">Concatenar pareceres lado a lado</option>
                  <option value="L">Listar pareceres um abaixo do outro</option>
                </select>
                <input type="checkbox"
                       id="assinaturaregente"
                       name="assinaturaregente"
                       value=""
                       checked /><b>Imprimir assinatura do professor</b><br>
              </td>
            </tr>
            <?php
          } else {

            ?>
            <tr>
              <td>
                <b>Período de Avaliação:</b>
                <?
                $sql2    = " SELECT ed41_i_codigo, ";
                $sql2   .= "                ed09_c_descr, ";
                $sql2   .= "                ed41_i_sequencia, ";
                $sql2   .= "                case ";
                $sql2   .= "                 when ed41_i_codigo>0 then 'A' end as tipo, ";
                $sql2   .= "                ed37_c_tipo ";
                $sql2   .= "         FROM procavaliacao ";
                $sql2   .= "          inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao ";
                $sql2   .= "          inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao ";
                $sql2   .= "         WHERE ed41_i_procedimento = $procedimento ";
                $sql2   .= "         ORDER BY ed41_i_sequencia ";
                $result2 = db_query($sql2);
                $linhas2 = pg_num_rows($result2);
                ?>
                <select name="periodo" id="periodo" style="font-size:9px;width:180px;">
                  <?php
                  $tem_nivel = false;
                  for ($y = 0; $y < $linhas2; $y++) {

                    db_fieldsmemory($result2,$y);
                    echo "<option value='$ed41_i_codigo'>$ed09_c_descr</option>";
                  }

                  for ($y = 0; $y < $linhas2; $y++) {

                    db_fieldsmemory($result2,$y);
                    if ($ed37_c_tipo == "NIVEL") {
                      $tem_nivel = true;
                      break;
                    }
                  }
                  ?>
                </select>
              </td>
              <td></td>
              <td valign="top">
                <b>Orientação:</b>
                <select name="modelo" id="modelo" style="font-size:9px;">
                  <option value='1'>MODELO 1 - RETRATO</option>
                  <option value='2'>MODELO 2 - PAISAGEM</option>
                  <option value='3'>MODELO 3</option>
                </select>
                <input type="checkbox" id='assinaturaregente' name="assinaturaregente" value="" checked> <b>Imprimir assinatura do professor conselheiro</b><br>
              </td>
            </tr>
            <tr>
              <td colspan="3">
                <b>Layout:</b><br>
                <input type="checkbox" name="grade" value="" checked> <b>Mostrar grade com aproveitamento do aluno</b><br>
                <input type="checkbox" name="padrao" value="" checked onclick="js_padrao();"> <b>Mostrar pareceres padronizados</b>
                <span id="optpadrao">
                  <select name="padraotipo" style="font-size:9px;height:16px;">
                    <option value="C">Concatenar pareceres lado a lado</option>
                    <option value="L">Listar pareceres um abaixo do outro</option>
                  </select>
                </span>
                <br>
                <input type="checkbox" name="descritivo" value="" checked> <b>Mostrar parecer descritivo</b><br>
                <?php
                if( $tem_nivel == true ) {

                  ?>
                  <input type="checkbox" name="niveis" value="" checked> <b>Mostrar descrição dos níveis</b><br>
                <?php
                } else {
                  ?>
                  <input type="checkbox" name="niveis" value="" style="visibility:hidden;">
                <?php
                }
                ?>
              </td>
            </tr>
          <?php
          }
          ?>
          <tr>
            <td valign="top" colspan="3">
              <?php
              $sSqlObsBoletim = $clobsboletim->sql_query( "", "ed252_t_mensagem", "", "ed252_i_escola = {$escola}" );
              $resultobs      = $clobsboletim->sql_record( $sSqlObsBoletim );

              if( $clobsboletim->numrows > 0 ) {

                db_fieldsmemory($resultobs,0);
                $obs = $ed252_t_mensagem;
              }
              ?>
              <b>Mensagem do Boletim:</b>
              <br>
              <?php
              db_textarea( 'obs', 2, 110, 0, true, 'text', 1, "", "", "", 240);
              ?>
              <br>
            </td>
          </tr>
          <tr>
            <td align="center" colspan="3">
              <?php
              if (trim($ed37_c_tipo) == "PARECER") {

                ?>
                <input name="pesquisar"
                       type="button"
                       id="pesquisar"
                       value="Processar"
                       onclick="js_salvaObs(document.form1.subgrupo.value, 2);" disabled>
              <?php
              } else {

                ?>
                <input name="pesquisar"
                       type="button"
                       id="pesquisar"
                       value="Processar"
                       onclick="js_salvaObs(document.form1.subgrupo.value, 1);" disabled>
                       
                       
              <?php
              }
              ?>
              <br><br>
              <?php
              if( trim( $ed37_c_tipo ) != "PARECER" ) {

                ?>
                <fieldset style="align:left">
                  <table>
                    <tr>
                      <td>
                        MODELO 1 -> Retrato (Dois boletins por página)
                      </td>
                   </tr>
                   <tr>
                     <td>
                       MODELO 2 -> Paisagem (Um boletim por página)
                     </td>
                   </tr>
                   <tr>
                     <td>
                       MODELO 3 -> Retrato (Dois boletins por página)
                     </td>
                  </tr>
                 </table>
                </fieldset>
              <?php
              }
              ?>
              <br>
              <fieldset style="align:center">
                Para selecionar mais de um aluno<br>mantenha pressionada a tecla CTRL <br>e clique sobre o nome dos alunos.
              </fieldset>
              <input type="hidden" name="base"  value="<?=isset( $base ) ? $base : ""?>">
              <input type="hidden" name="curso" value="<?=isset( $curso ) ? $curso : ""?>">
            </td>
          </tr>
          <?php
          }
          ?>
        </table>
      </fieldset>
    </form>
  </div>
  <?php
  db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
  ?>
</body>
</html>
<script>

var oDBFormCache = new DBFormCache('oDBFormCache', 'edu2_boletim001.php');
    oDBFormCache.setElements(new Array($('trocaTurma')));
    oDBFormCache.load();

function js_init() {

  if (<?=(isset($calendario) && isset($turma))?'true':'false'?>) {
    js_remove();
  }
}

function js_remove() {

  if (document.form1.disciplinas != undefined) {

    if (document.form1['disciplinas'].options[1].value == 'T') {
      document.form1['disciplinas'].remove(1);
    } else {

      espera=new Option("TODAS","T");
      document.form1.disciplinas.options.add(espera,1);
    }
  }
}

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

  for(x = 0; x < Tam; x++) {

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

  for(i=0;i<Tam;i++){

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

  for(x = 0; x < Tam; x++) {

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

  for (i = 0; i < Tam; i++) {

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

  for(i = 0; i < document.form1.alunosdiario.length; i++) {

    if (document.form1.alunosdiario.length>0 && document.form1.alunosdiario.options[i].selected) {

      if (document.form1.alunos.length>0){
        document.form1.alunos.options[0].selected = false;
      }

      document.form1.incluirum.disabled = false;
      document.form1.excluirum.disabled = true;
    }
  }
}

function js_desabexc() {

  for(i = 0; i < document.form1.alunos.length; i++) {

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

  if ($('pesquisar')) {
    $('pesquisar').setAttribute('disabled', 'disabled');
  }

  if (valor != "") {
    document.form1.procurar.disabled = false;
  } else {
    document.form1.procurar.disabled = true;
  }
  <?php
  if (isset($turma)) {

    ?>

    qtd = document.form1.alunosdiario.length;
    for (i = 0; i < qtd; i++) {
      document.form1.alunosdiario.options[0] = null;
    }

    qtd = document.form1.alunos.length;

    for (i = 0; i < qtd; i++) {
      document.form1.alunos.options[0] = null;
    }
  <?php
  }
  ?>
}

function js_procurar(calendario,turma) {

  oDBFormCache.save();
  if (document.form1.inativos.checked == true) {
    inativos = "yes";
  } else {
    inativos = "no";
  }

  location.href = "edu2_boletim001.php?calendario="+calendario+"&turma="+turma+"&inativos="+inativos+"&trocaTurma="+$F('trocaTurma');
}


function js_salvaObs(iTurma, iPesquisa){

	  var sObs              = document.form1.obs.value;
	  var oParametros       = new Object();
	  oParametros.exec      = 'salvaObs';  
	  oParametros.sObs      = sObs;   
	  oParametros.iTurma    = iTurma;
	  oParametros.iPesquisa = iPesquisa;

	  AjaxRequest.create("edu2_alunosturmafotos.RPC.php",oParametros, js_retornoSalvaObs).execute();

}

function js_retornoSalvaObs(oRetorno){

   if (oRetorno.status != 1) {

	   alert('Erro ao salvar a Mensagem do Boletim.');
	   return false;
   }
	 
   switch(oRetorno.iPesquisa) {

     case 1 :
	   js_pesquisa(oRetorno.iTurma)
     break;    

     case 2 :
  	   js_pesquisa2(oRetorno.iTurma)
     break;    
   }
}



function js_pesquisa(turma) {



  F      = document.form1.alunos;
  alunos = "";
  sep    = "";

  for(i = 0; i < F.length; i++) {

    alunos += sep+F.options[i].value;
    sep     = ",";
  }

  if (document.form1.grade.checked == true) {
    grade = "yes";
  } else {
    grade = "no";
  }

  if (document.form1.padrao.checked == true) {
    padrao = "yes";
  } else {
    padrao = "no";
  }

  if (document.form1.descritivo.checked == true) {
    descritivo = "yes";
  } else {
    descritivo = "no";
  }

  if (document.form1.niveis.checked == true) {
    niveis = "yes";
  } else {
    niveis = "no";
  }

  if (grade == "no" && padrao == "no" && descritivo == "no") {

    alert("Escolha uma das opções de layout!");
    return false;
  }

  if(document.form1.assinaturaregente.checked==true) {
    assinaturaregente = "S";
  } else {
    assinaturaregente = "N";
  }

  var sUrlBoletim = '';
  if (document.form1.modelo.value == 1) {
    sUrlBoletim = 'edu2_boletim003.php';
  } else if (document.form1.modelo.value == 3) {
    sUrlBoletim = 'edu2_boletimunificado002.php';
  } else {
    sUrlBoletim = 'edu2_boletim002.php';
  }

  jan = window.open(sUrlBoletim+'?padraotipo='+document.form1.padraotipo.value+'&grade='+grade+
                    '&padrao='+padrao+'&descritivo='+descritivo+'&niveis='+niveis+
                    '&assinaturaregente='+assinaturaregente+'&alunos='+alunos+
                    '&periodo='+document.form1.periodo.value+'&turma='+turma+'&obs1='+btoa(document.form1.obs.value),'',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');

  if (document.form1.inativos.checked==true){
    inativos = "yes";
  } else {
    inativos = "no";
  }

  location.href = "edu2_boletim001.php?calendario="+document.form1.grupo.value+
                   "&turma="+document.form1.subgrupo.value+
                   "&obs="+document.form1.obs.value+"&inativos="+inativos+"&incluirobs";
}

function js_DiasLetivos(valor) {

  if (valor == "S") {
    document.getElementById("colunas").style.visibility = "hidden";
  } else {
    document.getElementById("colunas").style.visibility = "visible";
  }
}

function js_pesquisa2( turma ) {

  F      = document.form1.alunos;
  alunos = "";
  sep    = "";

  for( i = 0; i < F.length; i++ ) {

    alunos += sep+F.options[i].value;
    sep     = ",";
  }

  disciplinas = "";

  if (document.form1.disciplinas.value == "T") {

    D   = document.form1.disciplinas;
    sep = "";

    for (i = 2; i < D.length; i++) {

      disciplinas += sep+D.options[i].value;
      sep = ",";
    }

    punico = "no";

  } else if( document.form1.disciplinas.value == "PU" && document.form1.modelo.value == 'M2' ) {

    D           = document.form1.disciplinas;
    disciplinas = D.options[1].value;
    punico      = "yes";
  } else if(document.form1.disciplinas.value == "PU" && document.form1.modelo.value != 'M3') {

    D           = document.form1.disciplinas;
    disciplinas = D.options[2].value;
    punico      = "yes";
  } else if(document.form1.disciplinas.value == "PU" && document.form1.modelo.value == 'M3'){

    D           = document.form1.disciplinas;
    disciplinas = D.options[1].value;
    punico      = "yes";
  } else {

    disciplinas = document.form1.disciplinas.value;
    punico      = "no";
  }

  if (document.form1.assinaturaregente.checked == true) {
    assinaturaregente = "S";
  } else {
    assinaturaregente = "N";
  }

  var sUrlBoletim = '';
  if (document.form1.modelo.value == 'M1') {
    sUrlBoletim = 'edu2_pardescritivonota003.php';
  } else if(document.form1.modelo.value == 'M3') {
    sUrlBoletim = 'edu2_boletim004.php';
  } else if (document.form1.modelo.value == 'M4') {
    sUrlBoletim = 'edu2_boletimunificado002.php';
  } else {
    sUrlBoletim = 'edu2_pardescritivonota002.php';
  }

  jan = window.open(sUrlBoletim+'?padraotipo='+document.form1.padraotipo.value+
                    '&punico='+punico+'&periodo='+document.form1.periodo.value+
                    '&disciplinas='+disciplinas+'&turma='+turma+'&assinaturaregente='+assinaturaregente+
                    '&alunos='+alunos+'&obs1='+btoa(document.form1.obs.value),'',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');


  if (document.form1.inativos.checked == true) {
    inativos = "yes";
  } else {
    inativos = "no";
  }

  location.href = "edu2_boletim001.php?calendario="+document.form1.grupo.value+
                  "&turma="+document.form1.subgrupo.value+"&obs="+document.form1.obs.value+
                  "&inativos="+inativos+"&incluirobs";
}

<?php
if (!isset($turma) && pg_num_rows($sql_result) > 0) {

  ?>
  fillSelectFromArray2(document.form1.subgrupo,team[0]);
  document.form1.grupo.options[1].selected = true;
<?php
}
?>
</script>