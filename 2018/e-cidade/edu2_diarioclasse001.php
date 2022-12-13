<?
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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

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
    <?
   # Seleciona todos os calendários
   $sql = " SELECT ed52_i_codigo,ed52_c_descr ";
   $sql .= "  FROM calendario ";
   $sql .= "       inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
   $sql .= " WHERE ed38_i_escola = {$escola} ";
   $sql .= "   AND ed52_c_passivo = 'N' ";
   $sql .= " ORDER BY ed52_i_ano DESC";

   $sql_result = db_query($sql);
   $num        = pg_num_rows($sql_result);
   $conta      = "";

   while ($row = pg_fetch_array($sql_result)) {

     $conta = $conta + 1;
     $cod_curso = $row["ed52_i_codigo"];
     echo "new Array(\n";
     $sub_sql = " SELECT DISTINCT ed220_i_codigo,ed57_c_descr,ed11_c_descr ";
     $sub_sql .= "  FROM turma ";
     $sub_sql .= "       inner join matricula           on ed60_i_turma      = ed57_i_codigo ";
     $sub_sql .= "       inner join turmaserieregimemat on ed220_i_turma     = ed57_i_codigo ";
     $sub_sql .= "       inner join serieregimemat      on ed223_i_codigo    = ed220_i_serieregimemat ";
     $sub_sql .= "       inner join serie               on ed11_i_codigo     = ed223_i_serie ";
     $sub_sql .= "       inner join matriculaserie      on ed221_i_matricula = ed60_i_codigo ";
     $sub_sql .= "                                     and ed221_i_serie     = ed223_i_serie ";
     $sub_sql .= " WHERE ed57_i_calendario   = '{$cod_curso}' ";
     $sub_sql .= "   AND ed57_i_escola       = {$escola} ";
     $sub_sql .= "   AND ed221_c_origem      = 'S' ";
     $sub_sql .= "   ORDER BY ed57_c_descr, ed11_c_descr ";

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
         $conta_sub   = $conta_sub + 1;

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
        for (i = 0; i < itemArray.length; i++){

          selectCtrl.options[j] = new Option(itemArray[i][0]);
          if (itemArray[i][1] != null){
            selectCtrl.options[j].value = itemArray[i][1];
          }
          j++;
        }

        selectCtrl.options[0].selected = true;
        document.form1.subgrupo.disabled = false;
      }

      document.form1.procurar.disabled = true;
      <?if (isset($turma)) { ?>

      qtd = document.form1.alunosdiario.length;
      for (i = 0; i < qtd; i++) {
        document.form1.alunosdiario.options[0] = null;
      }
      qtd = document.form1.alunos.length;
      for (i = 0; i < qtd; i++) {
        document.form1.alunos.options[0] = null;
      }
      <?} ?>
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
      for (i = 0; i < itemArray.length; i++){

        selectCtrl.options[j] = new Option(itemArray[i][0]);
        if (itemArray[i][1] != null){
          selectCtrl.options[j].value = itemArray[i][1];
        }
        <?if (isset($turma)) { ?>
        if (<?=trim($turma) ?> == itemArray[i][1]) {
          indice = i;
        }
        <?} ?>
        j++;
      }

      <?if (isset($turma)) { ?>
      selectCtrl.options[indice].selected = true;
      document.form1.procurar.disabled = false;
      <?} else { ?>
      selectCtrl.options[0].selected = true;
      <?} ?>
      document.form1.subgrupo.disabled = false;
    }
  }
  //End -->
</script>
<body bgcolor="#CCCCCC">
<form class="container" name="form1" method="post" action="">
<?MsgAviso(db_getsession("DB_coddepto"), "escola"); ?>
<fieldset>
<legend>Relatório Diário de Classe</legend>
<table class="form-container">
<tr>
  <td colspan="3">
    <table border="0" align="left">
      <tr>
        <td>
          <label>Selecione o Calendário:</label><br>
          <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" >
            <option></option>
            <?
            #Seleciona todos os grupos para setar os valores no combo
            $sql = " SELECT ed52_i_codigo,ed52_c_descr ";
            $sql .= "  FROM calendario ";
            $sql .= "       inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
            $sql .= " WHERE ed38_i_escola  = {$escola} ";
            $sql .= "   AND ed52_c_passivo = 'N' ";
            $sql .= " ORDER BY ed52_i_ano DESC";

            $sql_result = db_query($sql);
            while ($row = pg_fetch_array($sql_result)) {

              $cod_curso  = $row["ed52_i_codigo"];
              $desc_curso = $row["ed52_c_descr"];
              ?>
              <option value="<?=$cod_curso; ?>" <?=$cod_curso == @$calendario ? "selected" : "" ?>><?=$desc_curso; ?></option>
            <?
            }
            #Popula o segundo combo de acordo com a escolha no primeiro
            ?>
          </select>
        </td>
        <td>
          <label>Selecione a Turma:</label><br>
          <select name="subgrupo" disabled onchange="js_botao(this.value);">
            <option value=""></option>
          </select>
        </td>
        <td valign='bottom'>
          <input type="button" name="procurar" value="Procurar"
                 onclick="js_procurar(document.form1.grupo.value,document.form1.subgrupo.value)" disabled>
        </td>
      </tr>
    </table>
  </td>
</tr>
<?if (isset($turma)) {

  $oTurma          = TurmaRepository::getTurmaByCodigoTurmaSerieRegimeMat( $turma );
  $lEnsinoInfantil = $oTurma->getTurno()->isIntegral() ? true : false;
  $arr_tipo        = array( "2" => "EJA", "3" => "MULTIETAPA" );

  $sSqlTurma   = $clturma->sql_query_turmaserie("", "ed57_i_codigo, ed57_i_tipoturma", "", "ed220_i_codigo = {$turma}");
  $result_tipo = $clturma->sql_record( $sSqlTurma );
  db_fieldsmemory($result_tipo, 0);
  ?>
  <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
  <tr>
    <td valign="top" colspan="2">
      <br>
      <label>Modelo:</label>
      <select name="modelo" id="modelo" onchange="js_modelo(this.value)">
        <option value='1'>MODELO 1</option>
        <option value='2'>MODELO 2</option>
        <option value='3'>MODELO 3</option>
        <option value='4'>MODELO 4</option>
        <?if ($ed57_i_tipoturma == 2 || $ed57_i_tipoturma == 3) { ?>
          <option value='5'>MODELO 5</option>
        <?} ?>
      </select>
    </td>
  </tr>

  <?php
    $sStyle = "none;";

    if ( $lEnsinoInfantil ) {
      $sStyle = "";
    }
  ?>
  <tr style = "display: <?=$sStyle?>">
    <td>
      <label>Turno:</label>
      <select id = "turno" name = "turno">
        <option value = "0" selected="selected">TODOS</option>
        <option value = "1">MANHÃ</option>
        <option value = "2">TARDE</option>
        <option value = "3">NOITE</option>
      </select>
    </td>
  </tr>
  <tr>
    <td colspan="3" id="teste">
      <fieldset class="separator">
        <legend>Configuração do Relatório</legend>
        <input id = "avaliacao" type = "checkbox" name = "avaliacao" value = "" checked> Avaliações
        <input id = "falta"     type = "checkbox" name = "falta"     value = "" checked> Total de Faltas
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="3" id="teste1" style="display: none">
      <fieldset class="separator">
        <legend>Configuração do Relatório</legend>
        <input id = "sexo"      type = "checkbox" name = "sexo"      value= "" checked>Sexo
        <input id = "idade"     type = "checkbox" name = "idade"     value= "" checked>Idade
        <input id = "abono"     type = "checkbox" name = "abono"     value= "" checked>Faltas Abonadas
        <input id = "codigo"    type = "checkbox" name = "codigo"    value= "" checked>Código
        <input id = "nasc"      type = "checkbox" name = "nasc"      value= "" checked>Nascimento
        <input id = "resultant" type = "checkbox" name = "resultant" value= "" checked>Resultado Anterior
        <input id = "totalfal"  type = "checkbox" name = "totalfal"  value= "" checked>Total de faltas
        <input id = "parecer"   type = "checkbox" name = "parecer"   value= "" checked>Parecer
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="3" id="configuracaoModelo4" style="display: none">
      <fieldset class="separator">
        <legend>Configuração do Relatório</legend>
        <input id = "faltaMod4"  type = "checkbox" name = "falta" value = "" checked><span id="spanFaltaMod4">Total de Faltas</span>
        <input id = "pontos" type = "checkbox" name = "falta" value = "" checked><span>Exibir Pontos</span>
      </fieldset>
    </td>
  </tr>
  <tbody id="div_regencia">
  <tr>
    <td valign="top">
      <?
      $sql = " SELECT ed59_i_codigo,ed232_c_descr,ed59_i_ordenacao ";
      $sql .= "  FROM regencia ";
      $sql .= "       inner join disciplina          on ed12_i_codigo       = ed59_i_disciplina ";
      $sql .= "       inner join caddisciplina       on ed232_i_codigo      = ed12_i_caddisciplina ";
      $sql .= "       inner join turma               on turma.ed57_i_codigo = regencia.ed59_i_turma ";
      $sql .= "       inner join turmaserieregimemat on ed220_i_turma       = ed57_i_codigo ";
      $sql .= "       inner join serieregimemat      on ed223_i_codigo      = ed220_i_serieregimemat ";
      $sql .= "       inner join serie               on ed11_i_codigo       = ed223_i_serie ";
      $sql .= " WHERE ed220_i_codigo = {$turma} ";
      $sql .= "   AND ed223_i_serie  = ed59_i_serie ";
      $sql .= " ORDER BY ed59_i_ordenacao  ";

      $result = db_query($sql);
      $linhas = pg_num_rows($result);
      ?>
      <fieldset class="separator">
        <legend>Disciplinas:</legend>
        <select name="alunosdiario" id="alunosdiario" size="10" onclick="js_desabinc()"
                style="font-size:9px;width:330px;height:155px" multiple>
          <?
          for ($i = 0; $i < $linhas; $i++) {
            db_fieldsmemory($result, $i);
            echo "<option value='$ed59_i_codigo'>$ed232_c_descr</option>\n";
          }
          ?>
        </select>
      </fieldset>
    </td>
    <td align="center">
      <br>
      <table border="0">
        <tr>
          <td>
            <input name="incluirum" title="Incluir" type="button" value=">" onclick="js_incluir();"
                   style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                                font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
          </td>
        </tr>
        <tr>
          <td height="1"></td>
        </tr>
        <tr>
          <td>
            <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();"
                   style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                                font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;">
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
            <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();"
                   style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                                font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
          </td>
        </tr>
        <tr>
          <td height="1"></td>
        </tr>
        <tr>
          <td>
            <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();"
                   style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                                font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
          </td>
        </tr>
      </table>
    </td>
    <td valign="top">
      <fieldset class="separator">
        <legend>Disciplinas para gerar diário de classe:</legend>
        <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()"
                style="font-size:9px;width:330px;height:155px" multiple>
        </select>
      </fieldset>
    </td>
  </tr>
  </tbody>
  <tr>
    <td colspan="3">
      <label>Período de Avaliação:</label>
      <?
      $sSqlTurma = $clturma->sql_query_turmaserie("", "ed220_i_procedimento", "", " ed220_i_codigo = {$turma}");
      $result_t  = $clturma->sql_record( $sSqlTurma );
      db_fieldsmemory($result_t, 0);

      $sWhereProcAvaliacao = "ed41_i_procedimento = {$ed220_i_procedimento} AND ed09_c_somach = 'S'";
      $sSqlProcAvaliacao = $clprocavaliacao->sql_query(
                                                        "",
                                                        "ed41_i_codigo, ed09_c_descr",
                                                        "ed41_i_sequencia",
                                                        $sWhereProcAvaliacao
                                                      );
      $result_d          = $clprocavaliacao->sql_record( $sSqlProcAvaliacao );
      ?>
      <select name="periodo" id="periodo" >
        <?
        for ($y = 0; $y < $clprocavaliacao->numrows; $y++) {

          db_fieldsmemory($result_d, $y);
          echo "<option value='$ed41_i_codigo'>$ed09_c_descr</option>";

        }
        ?>
      </select>
      &nbsp;&nbsp;
      <label>Informar Dias Letivos:</label>
      <select id="informadiasletivos" name="informadiasletivos" onChange="js_DiasLetivos(this.value);" >
        <option value='S'>SIM</option>
        <option value='N'>NÃO</option>
      </select>
          <span id="colunas" style="display: none;">
            &nbsp;&nbsp;
            <b>Quantidade de Colunas (Presenças):</b>
            <select id="qtdecolunas" name="qtdecolunas">
              <?
              for ($y = 30; $y <= 70; $y++) {
                echo "<option value='$y'>$y</option>";
              }
              ?>
            </select>
          </span>
    </td>
  </tr>
  <tr>
    <td colspan="3">
      <label>Mostrar somente alunos ativos (Matriculados):</label>
      <select id="active" name="active">
        <option value="SIM" selected>SIM</option>
        <option value="NAO">NÃO</option>
      </select>
      <br>
    </td>
  </tr>
  <tr>
    <td colspan="3">
      <label>Exibir Trocas de Turma:</label>
      <select id='trocaTurma' name='trocaTurma'>
        <option value="1" selected="selected">Não</option>
        <option value="2">Sim</option>
      </select>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="3">
      <input name="pesquisar" type="button" id="pesquisar" value="Processar"
             onclick="js_pesquisa(document.form1.subgrupo.value,<?=$ed57_i_codigo ?>);" disabled>
      <br><br>
      <fieldset style="align:left">
        <table>
          <tr>
            <td>
              MODELO 1 -> Uma disciplina por página (Área)
            </td>
          </tr>
          <tr>
            <td>
              MODELO 2 -> Todas disciplinas em uma página (Currículo)
            </td>
          </tr>
          <tr>
            <td>
              MODELO 3 -> Duas páginas por disciplina (Página 1 - Presenças / Página 2 - Avaliações)
            </td>
          </tr>
          <tr>
            <td>
              MODELO 4 -> Uma disciplina por página (Logotipo prefeitura e total de faltas)
            </td>
          </tr>
          <?if ($ed57_i_tipoturma == 2 || $ed57_i_tipoturma == 3) { ?>
            <tr>
              <td>
                MODELO 5 -> Turma <?=$arr_tipo[$ed57_i_tipoturma] ?> (Página de Presenças com alunos de todas as etapas)
              </td>
            </tr>
          <?} ?>
        </table>
      </fieldset>
      <br>
      <fieldset style="align:center">
        Para selecionar mais de uma disciplina mantenha pressionada a tecla CTRL <br>e clique sobre o nome da disciplina.
      </fieldset>
      <input type="hidden" name="base" value="<?=isset( $base ) ? $base : "" ?>">
      <input type="hidden" name="curso" value="<?=isset( $curso ) ? $curso : "" ?>">
    </td>
  </tr>
<?} ?>
</table>
</fieldset>
</form>
<?db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"),
          db_getsession("DB_instit"));
?>
</body>
</html>
<script>
var oUrl = js_urlToObject();
if (oUrl["turma"] != null) {

  oDBFormCache = new DBFormCache('oDBFormCache', 'edu2_diarioclasse001.php');
  oDBFormCache.setElements(new Array($('modelo')));
  oDBFormCache.setElements(new Array($('trocaTurma')));
  oDBFormCache.load();

  js_modelo($F('modelo'));
}
function js_incluir() {

  var Tam = document.form1.alunosdiario.length;
  var F   = document.form1;
  for (x = 0; x < Tam; x++) {

    if (F.alunosdiario.options[x].selected == true) {

      F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[x].text,
        F.alunosdiario.options[x].value)
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
  for (i = 0; i < Tam; i++) {
    F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[0].text,
                                                                                       F.alunosdiario.options[0].value)
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

  for (x = 0; x < Tam; x++) {

    if (F.options[x].selected == true) {

      document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[x].text,
                                                                                           F.options[x].value);
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

  for (i = 0; i < Tam; i++) {

    document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[0].text,
                                                                                         F.options[0].value);
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

  for (i = 0; i < document.form1.alunosdiario.length; i++) {

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

  for (i = 0; i < document.form1.alunos.length; i++) {

    if (document.form1.alunos.length > 0 && document.form1.alunos.options[i].selected) {

      if (document.form1.alunosdiario.length > 0) {
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

  <?if (isset($turma)) { ?>
  qtd = document.form1.alunosdiario.length;
  for (i = 0; i < qtd; i++) {
    document.form1.alunosdiario.options[0] = null;
  }

  qtd = document.form1.alunos.length;
  for (i = 0; i < qtd; i++) {
    document.form1.alunos.options[0] = null;
  }
  <?} ?>
}

function js_procurar(calendario,turma) {
  location.href = "edu2_diarioclasse001.php?calendario="+calendario+"&turma="+turma;
}

function js_pesquisa(turma,codturma) {

  oDBFormCache.save();
  if (document.form1.periodo.value == "") {

    alert("Informe o período de avaliação!");
    return false;
  }

  if (document.form1.modelo.value == "5") {

    jan = window.open('edu2_diarioclasse005.php?qtdecolunas='+document.form1.qtdecolunas.value+
                      '&informadiasletivos='+document.form1.informadiasletivos.value+
                      '&periodo='+document.form1.periodo.value+'&turma='+codturma+
                      '&active='+document.form1.active.value+
                      '&trocaTurma='+$F('trocaTurma'),'',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    return false;
  }

  F           = document.form1.alunos;
  disciplinas = "";
  sep         = "";

  if (F.length == 0) {

    alert (_M('educacao.escola.edu2_diarioclasse001.selecione_disciplina'));
    return false;
  }

  for (i = 0; i < F.length; i++) {

    disciplinas += sep+F.options[i].value;
    sep = ",";
  }

  var sTurno = '&iTurno=' + $F('turno');

  var sAquivo = 'edu2_diarioclasse002.php?';
  var sUrl    = 'qtdecolunas='+document.form1.qtdecolunas.value;
      sUrl   += '&informadiasletivos='+document.form1.informadiasletivos.value;
      sUrl   += '&periodo='+document.form1.periodo.value+'&disciplinas='+disciplinas;
      sUrl   += '&turma='+turma+'&active='+document.form1.active.value;
      sUrl   += '&trocaTurma='+$F('trocaTurma');
      sUrl   += sTurno;

  if ($F('modelo') == 1 || $F('modelo') == 4) {

    var lAvalliacao = false;
    if ($('avaliacao').checked && $F('modelo') == 1) {
      lAvalliacao = true;   // ta marcado
    }

    var lFalta = false;
    if ($('falta').checked && $F('modelo') == 1) {
      lFalta = true; //ta marcado
    }

    if ($('faltaMod4').checked && $F('modelo') == 4) {
      lFalta = true; //ta marcado
    }

    var lPontos = false;
    if ($('pontos') && $('pontos').checked) {
      lPontos = true;
    }

    sUrl += '&avaliacao='+lAvalliacao+'&falta='+lFalta;
    sUrl += '&lPontos='+lPontos;
    sUrl += '&iModeloRelatorio='+$F('modelo');

    sEnvio = sAquivo + sUrl;
    jan    = window.open(sEnvio, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  } else if (document.form1.modelo.value == 2) {

    var lPontos = false;
    if ($('pontos') && $('pontos').checked) {
      lPontos = true;
    }

    sUrl += '&lPontos='+lPontos;

    sAquivo = 'edu2_diarioclasse003.php?';
    sEnvio  = sAquivo + sUrl;

    jan = window.open(sEnvio,'',
      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  } else if ( document.form1.modelo.value == "5" ) {

    jan = window.open('edu2_diarioclasse005.php?qtdecolunas='+document.form1.qtdecolunas.value
                                             +'&informadiasletivos='+document.form1.informadiasletivos.value
                                             +'&periodo='+document.form1.periodo.value
                                             +'&turma='+codturma
                                             +'&disciplinas='+disciplinas
                                             +'&active='+document.form1.active.value
                                             +'&trocaTurma='+$F('trocaTurma')
                                             +sTurno,
                      '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    return false;
  } else {

    if (document.form1.sexo.checked == false) {    //nao ta marcado
      sexo = false;
    } else {
      sexo = true;   // ta marcado
    }
    if (document.form1.idade.checked == false) {  //nao ta marcado
      idade = false;
    } else {
      idade = true; //ta marcado
    }
    if (document.form1.abono.checked == false) {    //nao ta marcado
      abono = false;
    } else {
      abono = true;   // ta marcado
    }
    if (document.form1.codigo.checked == false) {  //nao ta marcado
      codigo = false;
    } else {
      codigo = true; //ta marcado
    }
    if (document.form1.nasc.checked == false) {  //nao ta marcado
      nasc = false;
    } else {
      nasc = true; //ta marcado
    }
    if (document.form1.resultant.checked == false) {  //nao ta marcado
      resultant = false;
    } else {
      resultant = true; //ta marcado
    }
    if (document.form1.totalfal.checked == false) {  //nao ta marcado
      totalfal = false;
    } else {
      totalfal = true; //ta marcado
    }
    if (document.form1.parecer.checked == false) {  //nao ta marcado
      parecer = false;
    } else {
      parecer = true; //ta marcado
    }

    jan = window.open('edu2_diarioclasse004.php?qtdecolunas='+document.form1.qtdecolunas.value
                                             +'&informadiasletivos='+document.form1.informadiasletivos.value
                                             +'&periodo='+document.form1.periodo.value
                                             +'&disciplinas='+disciplinas
                                             +'&turma='+turma
                                             +'&active='+document.form1.active.value
                                             +'&sexo='+sexo
                                             +'&idade='+idade
                                             +'&abono='+abono
                                             +'&codigo='+codigo
                                             +'&nasc='+nasc
                                             +'&resultant='+resultant
                                             +'&totalfal='+totalfal
                                             +'&parecer='+parecer
                                             +'&trocaTurma='+$F('trocaTurma')
                                             +sTurno,
                      '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }
  jan.moveTo(0,0);
}

function js_DiasLetivos(valor) {
  if (valor == "S") {
    document.getElementById("colunas").style.display = "none";
  } else {
    document.getElementById("colunas").style.display = "";
  }
}

function js_modelo(valor) {

  switch (valor) {

    case '1':

      $("teste").style.display               = "";
      $("teste1").style.display              = "none";
      $('configuracaoModelo4').style.display = 'none';
      document.form1.pesquisar.disabled      = false;
      break;

    case '2':

      $("teste").style.display               = "none";
      $("teste1").style.display              = "none";
      $('configuracaoModelo4').style.display = '';
      $('faltaMod4').style.display           = "none";
      $('spanFaltaMod4').style.display       = "none";
      document.form1.pesquisar.disabled      = false;
      break;

    case '4':

      $('configuracaoModelo4').style.display = '';
      $('faltaMod4').style.display           = '';
      $('spanFaltaMod4').style.display       = '';
      $("teste").style.display               = "none";
      $("teste1").style.display              = "none";
      document.form1.pesquisar.disabled      = false;
      break;

    case '5':

      $("teste1").style.display              = "";
      $('configuracaoModelo4').style.display = 'none';
      $("teste").style.display               = "none";
      document.form1.pesquisar.disabled      = false;
      break;

    default:

      $("teste1").style.display              = "";
      $('configuracaoModelo4').style.display = 'none';
      $("teste").style.display               = "none";
      document.form1.pesquisar.disabled      = false;

      if (document.getElementById("alunos").length == 0) {
        document.form1.pesquisar.disabled = true;
      }

      break;
  }

}
<?if (!isset($turma) && pg_num_rows($sql_result) > 0) { ?>

fillSelectFromArray2(document.form1.subgrupo,team[0]);
document.form1.grupo.options[1].selected = true;
<?} ?>

if( $("periodo") ){
  $("periodo").setAttribute("rel","ignore-css");
  $("periodo").addClassName("field-size4");
  $("informadiasletivos").setAttribute("rel","ignore-css");
  $("informadiasletivos").addClassName("field-size2");
  $("active").setAttribute("rel","ignore-css");
  $("active").addClassName("field-size2");
  $("trocaTurma").setAttribute("rel","ignore-css");
  $("trocaTurma").addClassName("field-size2");
  $("qtdecolunas").setAttribute("rel","ignore-css");
  $("qtdecolunas").addClassName("field-size2");
  $("periodo").addClassName("field-size2");
  $("periodo").addClassName("field-size2");
}
</script>