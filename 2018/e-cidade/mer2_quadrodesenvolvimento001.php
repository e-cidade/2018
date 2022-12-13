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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matricula_classe.php");
include("classes/db_periodocalendario_classe.php");
include("classes/db_turma_classe.php");
include("dbforms/db_funcoes.php");
$escola = db_getsession("DB_coddepto");
$clmatricula = new cl_matricula;
$clturma = new cl_turma;
$clperiodocalendario = new cl_periodocalendario;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<SCRIPT LANGUAGE="JavaScript">
 team = new Array(
 <?
 # Seleciona todos os calendários
  $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
  $sql       .= "       FROM calendario ";
  $sql       .= "        inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
  $sql       .= "       WHERE ed38_i_escola = $escola ";
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
    $sub_sql   .= "         AND ed57_i_escola = $escola ";
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" action="">
<center>
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<br>
<fieldset style="width:95%"><legend><b>Quadro de Desenvolvimento</b></legend>
<table border="0" align="left">
 <tr>
  <td colspan="3">
   <table border="0" align="left">
    </tr>
     <td>
      <b>Selecione o Calendário:</b><br>
      <select name="grupo" id="grupo"
              onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));"
              style="font-size:9px;width:200px;height:18px;">
       <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
       $sql       .= "       FROM calendario ";
       $sql       .= "        inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
       $sql       .= "       WHERE ed38_i_escola = $escola ";
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
      <b>Selecione a Turma:</b><br>
      <select name="subgrupo" id="subgrupo" style="font-size:9px;width:200px;height:18px;" disabled onchange="js_botao(this.value);">
       <option value=""></option>
      </select>
     </td>
     <td>
      <b>Competência:</b><br>
      <select name="tipocompetencia" onchange="js_competencia(this.value);" style="font-size:9px;height:18px;" >
       <option value="" <?=@$tipocompetencia==""?"selected":""?>>Todas</option>
       <option value="M" <?=@$tipocompetencia=="M"?"selected":""?>>Mês</option>
       <option value="P" <?=@$tipocompetencia=="P"?"selected":""?>>Período de Avaliação</option>
      </select>
     </td>
     <td>
      <span id="div_tipocompetencia">
      <?if(isset($tipomes)){?>
       <b>Mês</b><br>
       <select name="tipomes" style="font-size:9px;height:18px;">
        <option value="1" <?=@$tipomes=="1"?"selected":""?>>Janeiro</option>
        <option value="2" <?=@$tipomes=="2"?"selected":""?>>Fevereiro</option>
        <option value="3" <?=@$tipomes=="3"?"selected":""?>>Março</option>
        <option value="4" <?=@$tipomes=="4"?"selected":""?>>Abril</option>
        <option value="5" <?=@$tipomes=="5"?"selected":""?>>Maio</option>
        <option value="6" <?=@$tipomes=="6"?"selected":""?>>Junho</option>
        <option value="7" <?=@$tipomes=="7"?"selected":""?>>Julho</option>
        <option value="8" <?=@$tipomes=="8"?"selected":""?>>Agosto</option>
        <option value="9" <?=@$tipomes=="9"?"selected":""?>>Setembro</option>
        <option value="10" <?=@$tipomes=="10"?"selected":""?>>Outubro</option>
        <option value="11" <?=@$tipomes=="11"?"selected":""?>>Novembro</option>
        <option value="12" <?=@$tipomes=="12"?"selected":""?>>Dezembro</option>
       </select>
      <?}?>
      <?if(isset($tipoperiodo)){
        $result1 = $clperiodocalendario->sql_record(
                    $clperiodocalendario->sql_query("",
                                               "ed09_i_codigo,ed09_c_descr",
                                               "ed09_i_sequencia",
                                               "ed53_i_calendario = $calendario"
                                              )
                                              );
       ?>
       <b>Período:<br></b>
       <select name="tipoperiodo" style="font-size:9px;height:18px;">
        <?
        for($tt=0;$tt<$clperiodocalendario->numrows;$tt++){
         db_fieldsmemory($result1,$tt);
         ?>
         <option value="<?=$ed09_i_codigo?>" <?=@$tipoperiodo==$ed09_i_codigo?"selected":""?>><?=$ed09_c_descr?></option>
         <?
        }
        ?>
       </select>
       <?
       }
      ?>
      </span>
     </td>
     <td>
      <b>Modelo:<br></b>
      <select name="tipomodelo" style="font-size:9px;height:18px;" >
       <option value="1" <?=@$tipomodelo=="1"?"selected":""?>>Modelo 1</option>
       <option value="2" <?=@$tipomodelo=="2"?"selected":""?>>Modelo 2</option>
      </select>
     </td>
     <td valign='bottom'>
      <input type="button" name="procurar" value="Procurar" onclick="js_procurar(document.form1.grupo.value,document.form1.subgrupo.value)" disabled>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <?if(isset($turma)){?>
  <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
  <tr>
  <td valign="top">
   <?
   $sql    = " SELECT distinct ed47_i_codigo,ed47_v_nome,ed60_i_codigo,ed60_i_numaluno ";
   $sql   .= "       FROM matricula ";
   $sql   .= "        inner join aluno on ed47_i_codigo = ed60_i_aluno ";
   $sql   .= "        inner join mer_infaluno on mer_infaluno.me14_i_aluno = aluno.ed47_i_codigo";
   $sql   .= "        inner join turma on ed57_i_codigo = ed60_i_turma ";
   $sql   .= "        inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ";
   $sql   .= "        inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ";
   $sql   .= "        inner join serie on ed11_i_codigo = ed223_i_serie ";
   $sql   .= "        inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
   $sql   .= "       WHERE ed220_i_codigo = $turma ";
   $sql   .= "       AND ed60_c_ativa = 'S' ";
   $sql   .= "       AND ed221_c_origem = 'S' ";
   $sql   .= "       AND ed221_i_serie = ed223_i_serie ";
   $sql   .= "       AND ed60_c_situacao = 'MATRICULADO' ";
   $sql   .= "       ORDER BY ed60_i_numaluno,ed47_v_nome,ed47_i_codigo,ed60_i_codigo";
   $result = db_query($sql);
   $linhas = pg_num_rows($result);
   ?>
   <b>Selecione o Aluno:</b><br>
   <select name="alunosdiario" id="alunosdiario" size="10" onclick="js_desabinc()"
           style="font-size:9px;width:330px;height:120px" multiple>
    <?
    for ($i = 0; $i < $linhas; $i++) {

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
             onclick="js_incluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();"
             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" <?=$linhas==0?"disabled":""?>>
     </td>
    </tr>
    <tr><td height="3"></td></tr>
    <tr>
     <td>
      <hr>
     </td>
    </tr>
    <tr><td height="3"></td></tr>
    <tr>
     <td>
      <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();"
             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();"
             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <b>Emitir quadro de:</b><br>
   <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()"
           style="font-size:9px;width:330px;height:120px" multiple>
   </select>
  </td>
 </tr>
 <?if(isset($tipomodelo) && $tipomodelo=="2"){?>
 <tr>
  <td align="center" colspan="3">
  Selecione os campos para aparecerem no relatório. Largura da página não deve ultrapassar <b><span id="limite">195</span></b> pixels.
  </td>
 </tr>
 <tr>
  <td colspan="3">
   <table width="100%" cellspacing="0" cellpading="0">
    <tr>
     <td valign="top">
      <b>Campos</b>
     </td>
     <td>
      <b>Largura</b>
     </td>
     <td>
      <b>Alinhamento</b>
     </td>
     <td rowspan="31" valign="top" width="30" style="border-left:1px solid #000000">
     &nbsp;
     </td>
     <td rowspan="31" valign="top">
      <table cellspacing="0" cellpading="0">
       <tr>
        <td>
         <b>Orientação:</b><br>
         <select name="orientacao" style="width:200px" onchange="js_limite(this.value);">
          <option value="P" selected>RETRATO -> 195 pixels</option>
          <option value="L">PAISAGEM -> 280 pixels</option>
         </select><br><br>
         Já Marcados:
         <input type="text" size="3" name="marcados" readonly> pixels
         <br><br>
        </td>
       </tr>
       <tr>
        <td>
         <b>Ordem dos campos no relatório:</b><br>
         <select name="camposordenados" id="camposordenados" size="10" style="width:200px">
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
         <b>Ordenação dos dados:</b><br>
         <select name="ordenacao" style="width:200px">
          <option value="to_ascii(ed47_v_nome)">ALFABÉTICA</option>
          <option value="ed47_d_nasc">DATA NASCIMENTO</option>
          <option value="ed47_i_codigo">CÓDIGO ALUNO</option>
         </select><br>
         <b>Tamanho da Fonte:</b><br>
         <select name="tamfonte" style="width:200px">
          <option value="6">6</option>
          <option value="7" selected>7</option>
          <option value="8">8</option>
         </select><br>
         <b>Título do Relatório:</b><br>
         <input type="text" maxlength="50" name="titulorel" value="" style="width:200px;"><br>
         <b>Mostrar somente alunos ativos (Matriculados)</b>:<br>
         <select name="active" style="width:200px">
          <option value="SIM">SIM</option>
          <option value="NAO" selected>NÃO</option>
         </select><br>
         <input type="checkbox" name="nomeregente" value="" checked> <b>Imprimir nome do regente</b><br>
        </td>
       </tr>
      </table>
     </td>
    </tr>
    <tr>
     <td valign="top">
      <input type="checkbox" name="campos" value="ed47_i_codigo" onclick="VerificaTamanho(0);" checked disabled> Código do Aluno
      <input type="hidden" name="largura" id="largura" value="10">
      <input type="hidden" name="cabecalho" value="Código"><br>
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
      <input type="checkbox" name="campos" value="ed47_v_nome" onclick="VerificaTamanho(1);" checked disabled> Nome do Aluno
      <input type="hidden" name="largura" id="largura" value="70">
      <input type="hidden" name="cabecalho" value="Nome do Aluno"><br>
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
      <input type="checkbox" name="campos" value="round(me14_f_peso,2)" onclick="VerificaTamanho(2);" checked disabled> Peso
      <input type="hidden" name="largura" id="largura" value="10">
      <input type="hidden" name="cabecalho" value="Peso"><br>
     </td>
     <td>
      10
     </td>
     <td>
      <select name="alinhamento">
       <option value="L">ESQUERDO</option>
       <option value="C">CENTRALIZADO</option>
       <option value="R" selected>DIREITO</option>
      </select>
     </td>
    </tr>
    <tr>
     <td valign="top">
      <input type="checkbox" name="campos" value="round(me14_f_altura,2)" onclick="VerificaTamanho(3);" checked disabled> Altura
      <input type="hidden" name="largura" id="largura" value="10">
      <input type="hidden" name="cabecalho" value="Altura"><br>
     </td>
     <td>
      10
     </td>
     <td>
      <select name="alinhamento">
       <option value="L">ESQUERDO</option>
       <option value="C">CENTRALIZADO</option>
       <option value="R" selected>DIREITO</option>
      </select>
     </td>
    </tr>
    <tr>
     <td valign="top">
      <input type="checkbox" name="campos" value="round(me14_f_peso/(me14_f_altura*2),2)" onclick="VerificaTamanho(4);" checked disabled> Massa Corpórea
      <input type="hidden" name="largura" id="largura" value="10">
      <input type="hidden" name="cabecalho" value="Massa"><br>
     </td>
     <td>
      10
     </td>
     <td>
      <select name="alinhamento">
       <option value="L">ESQUERDO</option>
       <option value="C">CENTRALIZADO</option>
       <option value="R" selected>DIREITO</option>
      </select>
     </td>
    </tr>
    <tr>
     <td valign="top">
      <input type="checkbox" name="campos" value="to_char(me14_d_data,'DD/MM/YYYY')" onclick="VerificaTamanho(5);" checked disabled> Data do Registro
      <input type="hidden" name="largura" id="largura" value="15">
      <input type="hidden" name="cabecalho" value="Data"><br>
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
      <input type="checkbox" name="campos" value="fc_idade(ed47_d_nasc,current_date)" onclick="VerificaTamanho(6);"> Idade
      <input type="hidden" name="largura" id="largura" value="10">
      <input type="hidden" name="cabecalho" value="Idade"><br>
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
      <input type="checkbox" name="campos" value="ed47_v_sexo" onclick="VerificaTamanho(7);"> Sexo
      <input type="hidden" name="largura" id="largura" value="10">
      <input type="hidden" name="cabecalho" value="Sexo"><br>
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
      <input type="checkbox" name="campos" value="trim(ed47_v_ender)||' '||ed47_c_numero||' / '||trim(ed47_v_bairro)" onclick="VerificaTamanho(8);"> Endereço/Bairro
      <input type="hidden" name="largura" id="largura" value="90">
      <input type="hidden" name="cabecalho" value="Endereço/Bairro"><br>
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
      <input type="checkbox" name="campos" value="case when ed47_c_bolsafamilia='S' then 'SIM - '||trim(ed47_c_nis) else 'NÃO' end" onclick="VerificaTamanho(9);"> Bolsa Família
      <input type="hidden" name="largura" id="largura" value="30">
      <input type="hidden" name="cabecalho" value="Bolsa Família"><br>
     </td>
     <td>
      30
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
      <input type="checkbox" name="campos" value="ed47_c_codigoinep" onclick="VerificaTamanho(10);"> Código INEP
      <input type="hidden" name="largura" id="largura" value="20">
      <input type="hidden" name="cabecalho" value="Código INEP"><br>
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
      <input type="checkbox" name="campos" value="ed47_c_nis" onclick="VerificaTamanho(11);"> NIS
      <input type="hidden" name="largura" id="largura" value="20">
      <input type="hidden" name="cabecalho" value="NIS"><br>
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
   </table>
  </td>
 </tr>
 <?}?>
 <tr>
  <td align="center" colspan="3">
   <input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa(document.form1.subgrupo.value);" disabled>
   <br><br>
  </td>
 </tr>
 <?}?>
</table>
</fieldset>
</center>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_incluir() {
 var Tam = document.form1.alunosdiario.length;
 var F = document.form1;
 for(x=0;x<Tam;x++){
  if(F.alunosdiario.options[x].selected==true){
   F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[x].text,F.alunosdiario.options[x].value)
   F.alunosdiario.options[x] = null;
   Tam--;
   x--;
  }
 }
 if(document.form1.alunosdiario.length>0){
  document.form1.alunosdiario.options[0].selected = true;
 }else{
  document.form1.incluirum.disabled = true;
  document.form1.incluirtodos.disabled = true;
 }
 document.form1.pesquisar.disabled = false;
 document.form1.excluirtodos.disabled = false;
 document.form1.alunosdiario.focus();
}
function js_incluirtodos() {
 var Tam = document.form1.alunosdiario.length;
 var F = document.form1;
 for(i=0;i<Tam;i++){
  F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[0].text,F.alunosdiario.options[0].value)
  F.alunosdiario.options[0] = null;
 }
 document.form1.incluirum.disabled = true;
 document.form1.incluirtodos.disabled = true;
 document.form1.excluirtodos.disabled = false;
 document.form1.pesquisar.disabled = false;
 document.form1.alunos.focus();
}
function js_excluir() {
 var F = document.getElementById("alunos");
 Tam = F.length;
 for(x=0;x<Tam;x++){
  if(F.options[x].selected==true){
   document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[x].text,F.options[x].value);
   F.options[x] = null;
   Tam--;
   x--;
  }
 }
 if(document.form1.alunos.length>0){
  document.form1.alunos.options[0].selected = true;
 }
 if(F.length == 0){
  document.form1.pesquisar.disabled = true;
  document.form1.excluirum.disabled = true;
  document.form1.excluirtodos.disabled = true;
  document.form1.incluirtodos.disabled = false;
 }
 document.form1.alunos.focus();
}
function js_excluirtodos() {
 var Tam = document.form1.alunos.length;
 var F = document.getElementById("alunos");
 for(i=0;i<Tam;i++){
  document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[0].text,F.options[0].value);
  F.options[0] = null;
 }
 if(F.length == 0){
  document.form1.pesquisar.disabled = true;
  document.form1.excluirum.disabled = true;
  document.form1.excluirtodos.disabled = true;
  document.form1.incluirtodos.disabled = false;
 }
 document.form1.alunosdiario.focus();
}
function js_desabinc(){
 for(i=0;i<document.form1.alunosdiario.length;i++){
  if(document.form1.alunosdiario.length>0 && document.form1.alunosdiario.options[i].selected){
   if(document.form1.alunos.length>0){
    document.form1.alunos.options[0].selected = false;
   }
   document.form1.incluirum.disabled = false;
   document.form1.excluirum.disabled = true;
  }
 }
}
function js_desabexc(){
 for(i=0;i<document.form1.alunos.length;i++){
  if(document.form1.alunos.length>0 && document.form1.alunos.options[i].selected){
   if(document.form1.alunosdiario.length>0){
    document.form1.alunosdiario.options[0].selected = false;
   }
   document.form1.incluirum.disabled = true;
   document.form1.excluirum.disabled = false;
  }
 }
}
function js_botao(valor){
 if(valor!=""){
  document.form1.procurar.disabled = false;
 }else{
  document.form1.procurar.disabled = true;
 }
 <?if(isset($turma)){?>
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
function js_procurar(calendario,turma){
 str = "";
 if(document.form1.tipomes){
  str += "&tipomes="+document.form1.tipomes.value;
 }
 if(document.form1.tipoperiodo){
  str += "&tipoperiodo="+document.form1.tipoperiodo.value;
 }
 location.href = "mer2_quadrodesenvolvimento001.php?calendario="+calendario+"&turma="+turma+"&tipomodelo="+document.form1.tipomodelo.value+"&tipocompetencia="+document.form1.tipocompetencia.value+str;
}
function VerificaTamanho(atual){
 F = document.form1;
 jamarcados = 0;
 for(i=0;i<document.form1.campos.length;i++){
  if(document.form1.campos[i].checked==true){
   jamarcados += parseInt(document.form1.largura[i].value);
  }
 }
 limite = parseInt(document.getElementById("limite").innerHTML);
 if(jamarcados>limite){
  alert("Limite máximo de "+limite+" pixels ultrapassado!");
  document.form1.campos[atual].checked = false;
 }else{
  document.form1.marcados.value = jamarcados;
  if(document.form1.campos[atual].checked==true){
   F.elements['camposordenados'].options[F.elements['camposordenados'].options.length] = new Option(F.cabecalho[atual].value,F.campos[atual].value);
  }else{
   for(i=0;i<document.form1.camposordenados.length;i++){
    if(document.form1.camposordenados.options[i].value==document.form1.campos[atual].value){
     document.form1.camposordenados.options[i] = null;
    }
   }
  }
 }
}
<?if(isset($turma)){?>
VerificaTamanho(0);
VerificaTamanho(1);
VerificaTamanho(2);
VerificaTamanho(3);
VerificaTamanho(4);
VerificaTamanho(5);
<?}?>

function js_pesquisa(turma){

 F = document.form1.alunos;
 alunos = "";
 sep = "";
 for(i=0;i<F.length;i++){
  alunos += sep+F.options[i].value;
  sep = ",";
 }
 if(document.form1.nomeregente){
	 campos = "";
	 cabecalho = "";
	 colunas = "";
	 alinhamento = "";
	 sep = "";
	 sep1 = "";
	 contador = 0;
	 for(i=0;i<document.form1.camposordenados.length;i++){
	  for(t=0;t<document.form1.campos.length;t++){
	   if(document.form1.camposordenados.options[i].value==document.form1.campos[t].value){
	    campos += sep+document.form1.campos[t].value;
	    cabecalho += sep1+document.form1.cabecalho[t].value;
	    colunas += sep1+document.form1.largura[t].value;
	    alinhamento += sep1+document.form1.alinhamento[t].value;
	    sep = ",";
	    sep1 = "|";
	    contador++;
	   }
	  }
	 }
	 if(contador==0){
	  alert("Selecione algum campo para processar!");
	  return false;
	 }
	 if(document.form1.nomeregente.checked==true){
	  regente = "S";
	 }else{
	  regente = "N";
	 }
     titulorel = document.form1.titulorel.value;
     ordenacao = document.form1.ordenacao.value;
     orientacao = document.form1.orientacao.value;
     tamfonte = document.form1.tamfonte.value;
     active = document.form1.active.value;
 }else{
	 campos = "";
	 cabecalho = "Código|Nome do Aluno|Peso|Altura|Massa|Data";
	 colunas = "10|70|10|10|10|15";
	 alinhamento = "C|L|R|R|R|C";
	 regente = "N";
     titulorel = "";
     ordenacao = "to_ascii(ed47_v_nome)";
     orientacao = "P";
     tamfonte = "7";
     active = "SIM";
 }
 if(document.form1.grupo.value==""){
  alert("Selecione o calendario!");
  return false;
 }
 str = "";
 if(document.form1.tipomes){
  str += "&tipomes="+document.form1.tipomes.value;
 }
 if(document.form1.tipoperiodo){
  str += "&tipoperiodo="+document.form1.tipoperiodo.value;
 }
 jan = window.open('mer2_quadrodesenvolvimento002.php?codturma='+document.form1.subgrupo.value+'&tipomodelo='+document.form1.tipomodelo.value+'&codcalendario='+document.form1.grupo.value+'&titulorel='+titulorel+'&alunos='+alunos+'&nomeregente='+regente+'&ordenacao='+ordenacao+'&orientacao='+orientacao+'&alinhamento='+alinhamento+'&campos='+campos+'&cabecalho='+cabecalho+'&colunas='+colunas+'&tamfonte='+tamfonte+'&active='+active+'&tipocompetencia='+document.form1.tipocompetencia.value+str,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
function js_limite(valor){
 if(valor=="P"){
  document.getElementById("limite").innerHTML = 195;
 }else{
  document.getElementById("limite").innerHTML = 280;
 }
 if(parseInt(document.getElementById("limite").innerHTML)<document.form1.marcados.value){
  alert("Campos já selecionados ultrapassam o limite de 195 pixels.\nDesmarque alguns campos para usar a orientação RETRATO");
  document.getElementById("limite").innerHTML = 280;
  document.form1.orientacao.options[1].selected = true;
 }
}
function js_sobe() {
 var F = document.getElementById("camposordenados");
 if(F.selectedIndex != -1 && F.selectedIndex > 0) {
  var SI = F.selectedIndex - 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
  F.options[SI + 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_desce() {
 var F = document.getElementById("camposordenados");
 if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
  var SI = F.selectedIndex + 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
  F.options[SI - 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
<?if(!isset($turma) && pg_num_rows($sql_result)>0){?>
 fillSelectFromArray2(document.form1.subgrupo,team[0]);
 document.form1.grupo.options[1].selected = true;
<?}?>
function js_competencia(valor){
 document.getElementById('div_tipocompetencia').innerHTML = "";
 if(valor=="M"){
  sHtml  = '<b>Mês</b><br>';
  sHtml += '<select name="tipomes" style="font-size:9px;height:18px;">';
  sHtml += '<option value="1">Janeiro</option>';
  sHtml += '<option value="2">Fevereiro</option>';
  sHtml += '<option value="3">Março</option>';
  sHtml += '<option value="4">Abril</option>';
  sHtml += '<option value="5">Maio</option>';
  sHtml += '<option value="6">Junho</option>';
  sHtml += '<option value="7">Julho</option>';
  sHtml += '<option value="8">Agosto</option>';
  sHtml += '<option value="9">Setembro</option>';
  sHtml += '<option value="10">Outubro</option>';
  sHtml += '<option value="11">Novembro</option>';
  sHtml += '<option value="12">Dezembro</option>';
  sHtml += '</select>';
  document.getElementById('div_tipocompetencia').innerHTML = sHtml;
 }else if(valor=="P"){
  js_divCarregando("Aguarde, carregando registro(s)","msgBox");
  var sAction = 'PesquisaPeriodo';
  var url     = 'mer2_quadrodesenvolvimentoRPC.php';
  var oAjax = new Ajax.Request(url,
                                  {
                                    method    : 'post',
                                    parameters: 'calendario='+$('grupo').value+'&sAction='+sAction,
                                    onComplete: js_retornoPesquisaPeriodo
                                  }
                               );
 }
}
function js_retornoPesquisaPeriodo(oAjax) {
 js_removeObj("msgBox");
 var oRetorno = eval("("+oAjax.responseText+")");
 sHtml  = '<b>Período:<br></b>';
 sHtml += '<select name="tipoperiodo" style="font-size:9px;height:18px;">';
 for (var i = 0;i < oRetorno.length; i++) {
  with (oRetorno[i]) {
   sHtml += '  <option value="'+ed09_i_codigo+'">'+ed09_c_descr.urlDecode()+'</option>';
  }
 }
 sHtml += '  </select>';
 document.getElementById('div_tipocompetencia').innerHTML = sHtml;
}
</script>