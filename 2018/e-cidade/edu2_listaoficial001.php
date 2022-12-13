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
$clturma         = new cl_turma;
$clprocavaliacao = new cl_procavaliacao;
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/DBFormCache.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/DBFormSelectCache.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<SCRIPT LANGUAGE="JavaScript">
team = new Array(
<?php
  $iCampoCount = 0;
  # Seleciona todos os calendario
  $sql = "SELECT ed52_i_codigo, ed52_c_descr
            FROM calendario
                 inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
           WHERE ed38_i_escola  = {$escola}
             AND ed52_c_passivo = 'N'
           ORDER BY ed52_i_ano DESC";

  $sql_result = db_query($sql);
  $num        = pg_num_rows($sql_result);
  $conta      = "";

  while ($row = pg_fetch_array($sql_result)) {

    $conta     = $conta+1;
    $cod_curso = $row["ed52_i_codigo"];
    echo "new Array(\n";
    $sub_sql = " SELECT DISTINCT ed29_i_codigo,ed29_c_descr
                   FROM cursoedu
                        inner join base      on ed31_i_curso = ed29_i_codigo
                        inner join turma     on ed57_i_base  = ed31_i_codigo
                        inner join matricula on ed60_i_turma = ed57_i_codigo
                  WHERE ed57_i_escola     = {$escola}
                    AND ed57_i_calendario = {$cod_curso}
                  ORDER BY ed29_i_codigo ASC";

    $sub_result = db_query($sub_sql);
    $num_sub    = pg_num_rows($sub_result);

    if ($num_sub >= 1){

      # Se achar alguma base para o curso, marca a palavra Todas
      echo "new Array(\"\", ''),\n";
      echo "new Array(\"TODOS\", 'T'),\n";
      $conta_sub = "";

      while ($rowx = pg_fetch_array($sub_result)) {

        $codigo_serie = $rowx["ed29_i_codigo"];
        $serie_nome   = $rowx["ed29_c_descr"];
        $conta_sub    = $conta_sub+1;

        if ($conta_sub == $num_sub) {

          echo "new Array(\"$serie_nome\", $codigo_serie)\n";
          $conta_sub = "";
        } else {
          echo "new Array(\"$serie_nome\", $codigo_serie),\n";
        }
      }
    } else {

      #Se nao achar serie para o curso selecionado...
      echo "new Array(\"Nenhuma turma neste calendário\", '')\n";
    }

    if ($num>$conta) {
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

  <?if (isset($curso)) {?>

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

      <?if (isset($curso)) {?>
        if ('<?=trim($curso)?>'==itemArray[i][1]) {
         indice = i;
        }
      <?}?>
      j++;
    }

    <?if (isset($curso)) {?>

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
<body class="body-default">
<form class="container" name="form1" method="post" action="">
  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <fieldset>
    <legend>Lista das Turmas (Editável)</legend>
    <table class="form-container">
      <tr>
        <td colspan="3">
          <table border="0" align="left">
            <tr>
              <td>
                <strong>Selecione o Calendário:</strong><br/>
                <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));">
                  <option></option>
                  <?
                    #Seleciona todos os grupos para setar os valores no combo
                    $sql = "SELECT ed52_i_codigo, ed52_c_descr
                              FROM calendario
                                   inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
                             WHERE ed38_i_escola  = {$escola}
                               AND ed52_c_passivo = 'N'
                             ORDER BY ed52_i_ano DESC";

                    $sql_result = db_query($sql);

                    while ($row = pg_fetch_array($sql_result)) {

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
                <strong>Selecione o Curso:</strong><br/>
                <select name="subgrupo" disabled onchange="js_botao(this.value);">
                  <option value=""></option>
                </select>
              </td>
              <td valign='bottom'>
                <input type="button" name="procurar" value="Procurar" onclick="js_procurar(document.form1.grupo.value,document.form1.subgrupo.value)" disabled>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <?
        if (isset($curso)) {
          if ($curso == "T") {
            $where = " AND ed57_i_calendario = $calendario";
          } else {
            $where = " AND ed57_i_calendario = $calendario AND ed31_i_curso = $curso";
          }
      ?>
      <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
      <tr>
        <td>
          <?
            $sql = "SELECT DISTINCT ed220_i_codigo, ed57_c_descr, ed31_i_curso, ed11_c_descr
                      FROM turma
                           inner join matricula           on ed60_i_turma      = ed57_i_codigo
                           inner join turmaserieregimemat on ed220_i_turma     = ed57_i_codigo
                           inner join serieregimemat      on ed223_i_codigo    = ed220_i_serieregimemat
                           inner join serie               on ed11_i_codigo     = ed223_i_serie
                           inner join matriculaserie      on ed221_i_matricula = ed60_i_codigo
                                                         and ed221_i_serie     = ed223_i_serie
                           inner join base                on ed31_i_codigo     = ed57_i_base
                     WHERE ed57_i_escola  = {$escola}
                       AND ed221_c_origem = 'S'
                       $where
                     ORDER BY ed57_c_descr,ed11_c_descr";
            $result = db_query($sql);
            $linhas = pg_num_rows($result);
          ?>
          <fieldset class="separator">
            <legend>Turmas:</legend>
            <select name="alunosdiario" id="alunosdiario" size="10" onclick="js_desabinc()" style="font-size:9px;width:330px;height:155px" multiple>
              <?
                for($i=0;$i<$linhas;$i++) {
                  db_fieldsmemory($result,$i);
                  echo "<option value='$ed220_i_codigo'>$ed57_c_descr - $ed11_c_descr</option>\n";
                }
              ?>
            </select>
          </fieldset>
        </td>
        <td align="center">
          <br/>
          <table border="0">
           <tr>
             <td>
               <input name="incluirum" title="Incluir" type="button" value=">" onclick="js_incluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
             </td>
           </tr>
           <tr>
             <td height="1"></td>
           </tr>
           <tr>
             <td>
               <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;">
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
               <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
             </td>
           </tr>
           <tr>
             <td height="1"></td>
           </tr>
            <tr>
              <td>
                <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
              </td>
            </tr>
          </table>
        </td>
        <td>
          <fieldset class="separator">
            <legend>Turmas para gerar lista oficial:</legend>
            <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()" style="font-size:9px;width:330px;height:155px" multiple>
            </select>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td align="center" colspan="3" height="15">
          Selecione os campos para aparecerem no relatório. Largura da página não deve ultrapassar <strong><span id="limite">195</span></strong> pixels.
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <table width="100%" cellspacing="0" cellpading="0">
            <tr>
              <td valign="top"></td>
              <td></td>
              <td></td>
              <td rowspan="31" valign="top" width="70" style="border-left:1px solid #000000">
                &nbsp;
              </td>
              <td rowspan="31" valign="top">
                <table cellspacing="0" cellpading="0">
                  <tr>
                    <td colspan="2">
                      <strong>Orientação:</strong><br/>
                      <select name="orientacao" onchange="js_limite(this.value);">
                        <option value="P" selected>RETRATO -> 195 pixels</option>
                        <option value="L">PAISAGEM -> 280 pixels</option>
                      </select><br/><br/>
                      <strong>Já Marcados:</strong>
                      <input type="text" size="3" name="marcados" readonly/> pixels
                      <br/><br/>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <strong>Ordem dos campos no relatório:</strong><br/>
                      <select name="camposordenados" id="camposordenados" size="16">
                      </select>
                    </td>
                    <td valign="top">
                      <br/>
                       <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
                       <br/>
                       <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
                      <br/>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <strong>Ordenação dos dados:</strong><br/>
                      <select name="ordenacao">
                        <option value="ed60_i_numaluno,to_ascii(ed47_v_nome)">N° CHAMADA</option>
                        <option value="to_ascii(ed47_v_nome)">ALFABÉTICA</option>
                        <option value="ed60_i_codigo">N° MATRÍCULA</option>
                        <option value="ed47_d_nasc">DATA NASCIMENTO</option>
                        <option value="ed47_i_codigo">CÓDIGO ALUNO</option>
                      </select><br/>
                      <strong>Tamanho da Fonte:</strong><br/>
                      <select name="tamfonte">
                        <option value="6">6</option>
                        <option value="7" selected>7</option>
                        <option value="8">8</option>
                      </select><br/>
                      <strong>Título do Relatório:</strong><br/>
                      <input id="titulorel" type="text" maxlength="50" name="titulorel" value=""><br/>
                      <strong>Mostrar somente alunos ativos (Matriculados)</strong>:<br/>
                      <select name="active">
                        <option value="SIM">SIM</option>
                        <option value="NAO" selected>NÃO</option>
                      </select><br/>
                      <input type="checkbox" name="nomeregente" value="" checked> <strong>Imprimir nome do regente</strong><br/>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <strong>Exibir Trocas de Turma:</strong><br/>
                      <select id='trocaTurma' name='trocaTurma'>
                        <option value="1" selected="selected">Não</option>
                        <option value="2">Sim</option>
                      </select>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <div style="height: 570px; overflow: auto;">
                  <table>
                    <tr>
                      <td><strong>Campos</strong></td>
                      <td><strong>Largura</strong></td>
                      <td><strong>Alinhamento</strong></td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_v_nome" onclick="VerificaTamanho(0);" checked disabled> Nome do Aluno
                        <input type="hidden" name="largura" id="largura" value="70">
                        <input type="hidden" name="cabecalho" value="Nome do Aluno"><br/>
                      </td>
                      <td>
                        70
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed60_i_numaluno" onclick="VerificaTamanho(1);"> N° Chamada
                        <input type="hidden" name="largura" id="largura" value="5">
                        <input type="hidden" name="cabecalho" value="N°"><br/>
                      </td>
                      <td>
                       05
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="trim(ed47_v_ender)||' '||ed47_c_numero||' / '||trim(ed47_v_bairro)" onclick="VerificaTamanho(2);"> Endereço/Bairro
                        <input type="hidden" name="largura" id="largura" value="90">
                        <input type="hidden" name="cabecalho" value="Endereço/Bairro"><br/>
                      </td>
                      <td>
                        90
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_v_cep" onclick="VerificaTamanho(3);"> CEP
                        <input type="hidden" name="largura" id="largura" value="15">
                        <input type="hidden" name="cabecalho" value="CEP"><br/>
                      </td>
                      <td>
                        15
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="case when ed47_v_telef = '' then ed47_v_telcel else case when ed47_v_telcel != '' then ed47_v_telef||' / '||ed47_v_telcel else ed47_v_telef end end" onclick="VerificaTamanho(4);"> Telefones
                        <input type="hidden" name="largura" id="largura" value="40">
                        <input type="hidden" name="cabecalho" value="Telefones"><br/>
                      </td>
                      <td>
                        40
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_d_nasc" onclick="VerificaTamanho(5);"> Data Nascimento
                        <input type="hidden" name="largura" id="largura" value="25">
                        <input type="hidden" name="cabecalho" value="Nascimento"><br/>
                      </td>
                      <td>
                        25
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed60_matricula" onclick="VerificaTamanho(6);"> N° Matrícula
                        <input type="hidden" name="largura" id="largura" value="20">
                        <input type="hidden" name="cabecalho" value="Matrícula"><br/>
                      </td>
                      <td>
                        20
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed60_c_situacao" onclick="VerificaTamanho(7);"> Situação Matrícula
                        <input type="hidden" name="largura" id="largura" value="30">
                        <input type="hidden" name="cabecalho" value="Situação"><br/>
                      </td>
                      <td>
                        30
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_v_sexo" onclick="VerificaTamanho(8);"> Sexo
                        <input type="hidden" name="largura" id="largura" value="10">
                        <input type="hidden" name="cabecalho" value="Sexo"><br/>
                      </td>
                      <td>
                        10
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="trim(ed261_c_nome)" onclick="VerificaTamanho(9);"> Naturalidade
                        <input type="hidden" name="largura" id="largura" value="40">
                        <input type="hidden" name="cabecalho" value="Naturalidade"><br/>
                      </td>
                      <td>
                        40
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="case when ed47_c_transporte!='' then trim(ed47_c_transporte)||'-'||trim(ed47_c_zona) else 'NÃO' end" onclick="VerificaTamanho(10);"> Transporte Escolar
                        <input type="hidden" name="largura" id="largura" value="30">
                        <input type="hidden" name="cabecalho" value="Transporte Escolar"><br/>
                      </td>
                      <td>
                        30
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="case when ed47_c_bolsafamilia='S' then 'SIM - '||trim(ed47_c_nis) else 'NÃO' end" onclick="VerificaTamanho(11);"> Bolsa Família
                        <input type="hidden" name="largura" id="largura" value="30">
                        <input type="hidden" name="cabecalho" value="Bolsa Família"><br/>
                      </td>
                      <td>
                        30
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_v_email" onclick="VerificaTamanho(12);"> Email
                        <input type="hidden" name="largura" id="largura" value="40">
                        <input type="hidden" name="cabecalho" value="Email"><br/>
                      </td>
                      <td>
                        40
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="case when fc_edurfanterior(ed60_i_codigo) = 'R' then '*' else null end" onclick="VerificaTamanho(13);"> Reprovado
                        <input type="hidden" name="largura" id="largura" value="10">
                        <input type="hidden" name="cabecalho" value="Rep"><br/>
                      </td>
                      <td>
                        10
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_v_pai" onclick="VerificaTamanho(14);"> Nome do Pai
                        <input type="hidden" name="largura" id="largura" value="60">
                        <input type="hidden" name="cabecalho" value="Nome do Pai"><br/>
                      </td>
                      <td>
                        60
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_v_mae" onclick="VerificaTamanho(15);"> Nome da Mãe
                        <input type="hidden" name="largura" id="largura" value="60">
                        <input type="hidden" name="cabecalho" value="Nome da Mãe"><br/>
                      </td>
                      <td>
                        60
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_i_codigo" onclick="VerificaTamanho(16);"> Código Aluno
                        <input type="hidden" name="largura" id="largura" value="15">
                        <input type="hidden" name="cabecalho" value="Código"><br/>
                      </td>
                      <td>
                        15
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed60_d_datamatricula" onclick="VerificaTamanho(17);"> Data Matrícula
                        <input type="hidden" name="largura" id="largura" value="15">
                        <input type="hidden" name="cabecalho" value="Dt. Matric"><br/>
                      </td>
                      <td>
                        15
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed60_d_datasaida" onclick="VerificaTamanho(18);"> Data Saída
                        <input type="hidden" name="largura" id="largura" value="15">
                        <input type="hidden" name="cabecalho" value="Dt. Saída"><br/>
                      </td>
                      <td>
                        15
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_certidaomatricula" onclick="VerificaTamanho(19);"> Matrícula da Certidão
                        <input type="hidden" name="largura" id="largura" value="55">
                        <input type="hidden" name="cabecalho" value="Matrícula da Certidão"><br/>
                      </td>
                      <td>
                        55
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="trim(ed47_c_certidaonum)" onclick="VerificaTamanho(20);"> Termo Certidão
                        <input type="hidden" name="largura" id="largura" value="15">
                        <input type="hidden" name="cabecalho" value="Certidão"><br/>
                      </td>
                      <td>
                        15
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="case when ed76_c_tipo = 'M' then substr(escolaprimat.ed18_c_nome,0,35) else substr(escolaproc.ed82_c_nome,0,35) end as nomeescola" onclick="VerificaTamanho(21);"> Local de Procedência
                        <input type="hidden" name="largura" id="largura" value="60">
                        <input type="hidden" name="cabecalho" value="Local de Procedência"><br/>
                      </td>
                      <td>
                       60
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed76_d_data" onclick="VerificaTamanho(22);"> Data de Procedência
                        <input type="hidden" name="largura" id="largura" value="25">
                        <input type="hidden" name="cabecalho" value="Dt. Procedência"><br/>
                      </td>
                      <td>
                        25
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L" selected>ESQUERDO</option>
                          <option value="C">CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="'' as assinatura1" onclick="VerificaTamanho(23);"> Assinatura 1
                        <input type="hidden" name="largura" id="largura" value="40">
                        <input type="hidden" name="cabecalho" value="Assinatura 1"><br/>
                      </td>
                      <td>
                        40
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="'' as assinatura2" onclick="VerificaTamanho(24);"> Assinatura 2
                        <input type="hidden" name="largura" id="largura" value="40">
                        <input type="hidden" name="cabecalho" value="Assinatura 2"><br/>
                      </td>
                      <td>
                        40
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="'' as assinatura3" onclick="VerificaTamanho(25);"> Assinatura 3
                        <input type="hidden" name="largura" id="largura" value="60">
                        <input type="hidden" name="cabecalho" value="Assinatura 3"><br/>
                      </td>
                      <td>
                        60
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="'' as meses" onclick="VerificaTamanho(26);"> Meses do Ano
                        <input type="hidden" name="largura" id="largura" value="120">
                        <input type="hidden" name="cabecalho" value="Meses"><br/>
                      </td>
                      <td>
                        120
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_v_ident" onclick="VerificaTamanho(27);"> RG
                        <input type="hidden" name="largura" id="largura" value="20">
                        <input type="hidden" name="cabecalho" value="RG"><br/>
                      </td>
                      <td>
                        20
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_v_cpf" onclick="VerificaTamanho(28);"> CPF
                        <input type="hidden" name="largura" id="largura" value="20">
                        <input type="hidden" name="cabecalho" value="CPF"><br/>
                      </td>
                      <td>
                        20
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_v_cnh" onclick="VerificaTamanho(29);"> CNH
                        <input type="hidden" name="largura" id="largura" value="20">
                        <input type="hidden" name="cabecalho" value="CNH"><br/>
                      </td>
                      <td>
                        20
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="fc_idade()" onclick="VerificaTamanho(30);"> Idade
                        <input type="hidden" name="largura" id="largura" value="10">
                        <input type="hidden" name="cabecalho" value="Idade"><br/>
                      </td>
                      <td>
                        10
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="fc_idade_mes() as anomes" onclick="VerificaTamanho(31);"> Meses da Idade
                        <input type="hidden" name="largura" id="largura" value="20">
                        <input type="hidden" name="cabecalho" value="Meses da Idade"><br/>
                      </td>
                      <td>
                        20
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="fc_idade_dia() as idadedia" onclick="VerificaTamanho(32);"> Dias da Idade
                        <input type="hidden" name="largura" id="largura" value="20">
                        <input type="hidden" name="cabecalho" value="Dias da Idade"><br/>
                      </td>
                      <td>
                        20
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_c_codigoinep" onclick="VerificaTamanho(33);"> Código INEP / ID Aluno
                        <input type="hidden" name="largura" id="largura" value="33">
                        <input type="hidden" name="cabecalho" value="Código INEP / ID Aluno"><br/>
                      </td>
                      <td>
                        33
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_c_nis" onclick="VerificaTamanho(34);"> NIS
                        <input type="hidden" name="largura" id="largura" value="20">
                        <input type="hidden" name="cabecalho" value="NIS"><br/>
                      </td>
                      <td>
                        20
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="case when ed47_o_oid > 0 then 'SIM' else 'NÃO' end"
                               onclick="VerificaTamanho(35);"> Foto
                        <input type="hidden" name="largura" id="largura" value="15">
                        <input type="hidden" name="cabecalho" value="Foto"><br/>
                      </td>
                      <td>
                        15
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_cartaosus"
                               onclick="VerificaTamanho(36);"> Cartão SUS
                        <input type="hidden" name="largura" id="largura" value="25">
                        <input type="hidden" name="cabecalho" value="Cartão SUS"><br/>
                      </td>
                      <td>
                        25
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <input type="checkbox" id="campos<?php echo $iCampoCount++ ?>" name="campos" value="ed47_tiposanguineo"
                               onclick="VerificaTamanho(37);"> Tipo Sanguíneo
                        <input type="hidden" name="largura" id="largura" value="22">
                        <input type="hidden" name="cabecalho" value="Tipo Sanguíneo"><br/>
                      </td>
                      <td>
                        22
                      </td>
                      <td>
                        <select name="alinhamento">
                          <option value="L">ESQUERDO</option>
                          <option value="C" selected>CENTRALIZADO</option>
                          <option value="R">DIREITO</option>
                        </select>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center" colspan="3" style="text-align:center !important;">
          <br/>
          <input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa(document.form1.subgrupo.value);" disabled>
          <input type="hidden" name="base" value="<?=isset( $base ) ? $base : ""?>">
          <input type="hidden" name="curso" value="<?=isset( $curso ) ? $curso : ""?>">
        </td>
      </tr>
      <?}?>
    </table>
  </fieldset>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script type="text/javascript">
var oDBFormCache = new DBFormCache('oDBFormCache', 'edu2_listaoficial001.php');
oDBFormCache.setElements(new Array($('trocaTurma')));
oDBFormCache.load();

function js_incluir() {

  var Tam = document.form1.alunosdiario.length;
  var F   = document.form1;

  for (x=0; x < Tam; x++) {

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

  for (i=0; i < Tam; i++) {

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

  for (x=0; x < Tam; x++) {

    if(F.options[x].selected==true){
      document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[x].text,F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;
    }
  }

  if (document.form1.alunos.length > 0) {
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
  var F   = document.getElementById("alunos");

  for (i=0; i < Tam; i++) {

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

function js_desabinc(){

  for (i=0; i < document.form1.alunosdiario.length; i++) {

    if (document.form1.alunosdiario.length > 0 && document.form1.alunosdiario.options[i].selected) {

      if (document.form1.alunos.length > 0) {
        document.form1.alunos.options[0].selected = false;
      }

      document.form1.incluirum.disabled = false;
      document.form1.excluirum.disabled = true;
    }
  }
}

function js_desabexc() {

  for (i=0; i < document.form1.alunos.length; i++) {

    if (document.form1.alunos.length > 0 && document.form1.alunos.options[i].selected) {

      if (document.form1.alunosdiario.length > 0) {
        document.form1.alunosdiario.options[0].selected = false;
      }

      document.form1.incluirum.disabled = true;
      document.form1.excluirum.disabled = false;
    }
  }
}

function js_botao(valor){

  if (valor != "") {
    document.form1.procurar.disabled = false;
  }else{
    document.form1.procurar.disabled = true;
  }

  <?if (isset($curso)) {?>

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

function js_procurar(calendario,curso) {
  location.href = "edu2_listaoficial001.php?calendario="+calendario+"&curso="+curso;
}

function VerificaTamanho(atual){

  F          = document.form1;
  jamarcados = 0;

  for (i=0; i < document.form1.campos.length; i++) {

    if (document.form1.campos[i].checked == true) {
      jamarcados += parseInt(document.form1.largura[i].value);
    }
  }

  limite = parseInt(document.getElementById("limite").innerHTML);

  if (jamarcados > limite) {

   alert(_M('educacao.escola.edu2_listaoficial001.limite_pixels_ultrapassado', {"iLimite" : limite}));
   document.form1.campos[atual].checked = false;
  } else {

    document.form1.marcados.value = jamarcados;

    if (document.form1.campos[atual].checked == true) {
      F.elements['camposordenados'].options[F.elements['camposordenados'].options.length] = new Option(F.cabecalho[atual].value,F.campos[atual].value);
    } else {
      for (i=0; i < document.form1.camposordenados.length; i++) {

        if (document.form1.camposordenados.options[i].value == document.form1.campos[atual].value) {
          document.form1.camposordenados.options[i] = null;
        }
      }
    }
  }
}

<?if (isset($curso)) {?>
  VerificaTamanho(0);
<?}?>

function js_pesquisa(turma) {

  F      = document.form1.alunos;
  turmas = "";
  sep    = "";

  for (i=0; i < F.length; i++) {

   turmas += sep+F.options[i].value;
   sep     = ",";
  }

  campos      = "";
  cabecalho   = "";
  colunas     = "";
  alinhamento = "";
  sep         = "";
  sep1        = "";
  contador    = 0;

  for (i=0; i < document.form1.camposordenados.length; i++) {

    for (t=0; t < document.form1.campos.length; t++) {

      if (document.form1.camposordenados.options[i].value == document.form1.campos[t].value) {

        campos      += sep+document.form1.campos[t].value;
        cabecalho   += sep1+document.form1.cabecalho[t].value;
        colunas     += sep1+document.form1.largura[t].value;
        alinhamento += sep1+document.form1.alinhamento[t].value;
        sep          = "__";
        sep1         = "|";
        contador++;
      }
    }
  }

  if (contador == 0) {

   alert(_M('educacao.escola.edu2_listaoficial001.selecione_campo_para_processar'));
   return false;
  }

  if (document.form1.grupo.value == "") {

   alert(_M('educacao.escola.edu2_listaoficial001.selecione_calendario'));
   return false;
  }

  if (document.form1.nomeregente.checked == true) {
   regente = "S";
  } else {
   regente = "N";
  }

  oDBFormCache.save();

  var sUrl  = 'edu2_listaoficial002.php?codcalendario='+document.form1.grupo.value;
      sUrl += '&titulorel='+document.form1.titulorel.value+'&turmas='+turmas+'&nomeregente='+regente;
      sUrl += '&ordenacao='+document.form1.ordenacao.value+'&orientacao='+document.form1.orientacao.value;
      sUrl += '&alinhamento='+alinhamento+'&campos='+btoa(campos)+'&cabecalho='+btoa(unescape(encodeURIComponent(cabecalho)))+'&colunas='+colunas;
      sUrl += '&tamfonte='+document.form1.tamfonte.value+'&active='+document.form1.active.value;
      sUrl += '&trocaTurma='+$F('trocaTurma');
  jan = window.open(sUrl,
                    '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_limite(valor){

  if (valor == "P") {
    document.getElementById("limite").innerHTML = 195;
  } else {
    document.getElementById("limite").innerHTML = 280;
  }

  if (parseInt(document.getElementById("limite").innerHTML) < document.form1.marcados.value) {

    alert(_M('educacao.escola.edu2_listaoficial001.desmarque_alguns_campos'));
    document.getElementById("limite").innerHTML   = 280;
    document.form1.orientacao.options[1].selected = true;
  }
}

function js_sobe() {
  var F = document.getElementById("camposordenados");

  if (F.selectedIndex != -1 && F.selectedIndex > 0) {

    var SI                 = F.selectedIndex - 1;
    var auxText            = F.options[SI].text;
    var auxValue           = F.options[SI].value;
    F.options[SI]          = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
    F.options[SI + 1]      = new Option(auxText,auxValue);
    F.options[SI].selected = true;
  }
}

function js_desce() {

  var F = document.getElementById("camposordenados");

  if (F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {

   var SI                 = F.selectedIndex + 1;
   var auxText            = F.options[SI].text;
   var auxValue           = F.options[SI].value;
   F.options[SI]          = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
   F.options[SI - 1]      = new Option(auxText,auxValue);
   F.options[SI].selected = true;
  }
}

<?if (!isset($curso) && pg_num_rows($sql_result) > 0) {?>
  fillSelectFromArray2(document.form1.subgrupo,team[0]);
  document.form1.grupo.options[1].selected = true;
<?}?>
</script>
<script>

if ( $("titulorel") ) {
	  $("titulorel").addClassName("field-size-max");
}
</script>
