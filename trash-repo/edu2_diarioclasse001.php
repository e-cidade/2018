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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matricula_classe.php");
include("classes/db_procavaliacao_classe.php");
include("classes/db_turma_classe.php");
include("dbforms/db_funcoes.php");
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
 # Seleciona todos os calend�rios
 $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
 $sql       .= "        FROM calendario ";
 $sql       .= "   inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
 $sql       .= "   WHERE ed38_i_escola = $escola ";
 $sql       .= "         AND ed52_c_passivo = 'N' ";
 $sql       .= "         ORDER BY ed52_i_ano DESC";
 $sql_result = db_query($sql);
 $num        = pg_num_rows($sql_result);
 $conta      = "";
 while ($row = pg_fetch_array($sql_result)) {

   $conta     = $conta+1;
   $cod_curso = $row["ed52_i_codigo"];
   echo "new Array(\n";
   $sub_sql    = " SELECT DISTINCT ed220_i_codigo,ed57_c_descr,ed11_c_descr ";
   $sub_sql   .= "   FROM turma ";
   $sub_sql   .= "     inner join matricula on ed60_i_turma = ed57_i_codigo ";
   $sub_sql   .= "     inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ";
   $sub_sql   .= "     inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ";
   $sub_sql   .= "     inner join serie on ed11_i_codigo = ed223_i_serie ";
   $sub_sql   .= "     inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
   $sub_sql   .= "                               and ed221_i_serie = ed223_i_serie ";
   $sub_sql   .= "   WHERE ed57_i_calendario = '$cod_curso' ";
   $sub_sql   .= "   AND ed57_i_escola       = $escola ";
   $sub_sql   .= "   AND ed221_c_origem      = 'S' ";
   $sub_sql   .= "   ORDER BY ed57_c_descr,ed11_c_descr ";
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
     echo "new Array(\"Calend�rio sem turmas cadastradas\", '')\n";

   }
   if ($num>$conta){
     echo "),\n";
   }
}
echo ")\n";
echo ");\n";
?>
//Inicio da fun��o JS
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
    for (i = 0; i < itemArray.length; i++){

      selectCtrl.options[j] = new Option(itemArray[i][0]);
      if (itemArray[i][1] != null){
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
       document.form1.procurar.disabled = false;
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
<fieldset style="width:95%"><legend><b>Relat�rio Di�rio de Classe</b></legend>
<table border="0" align="left">
 <tr>
  <td colspan="3">
   <table border="0" align="left">
    </tr>
     <td>
      <b>Selecione o Calend�rio:</b><br>
      <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));"
              style="font-size:9px;width:200px;height:18px;">
       <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
       $sql       .= "   FROM calendario ";
       $sql       .= "        inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
       $sql       .= "   WHERE ed38_i_escola = $escola ";
       $sql       .= "         AND ed52_c_passivo = 'N' ";
       $sql       .= "   ORDER BY ed52_i_ano DESC";
       $sql_result = db_query($sql);
       while ($row = pg_fetch_array($sql_result)) {

         $cod_curso=$row["ed52_i_codigo"];
         $desc_curso=$row["ed52_c_descr"];
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
      <select name="subgrupo" style="font-size:9px;width:200px;height:18px;" disabled onchange="js_botao(this.value);">
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
 <?if(isset($turma)){
  $arr_tipo = array("2"=>"EJA","3"=>"MULTIETAPA");
  $result_tipo = $clturma->sql_record($clturma->sql_query_turmaserie("",
                                                                     "ed57_i_codigo,ed57_i_tipoturma",
                                                                     "",
                                                                     "ed220_i_codigo = $turma"
                                                                    )
                                     );
  db_fieldsmemory($result_tipo,0);
  ?>
  <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
  <tr>
   <td valign="top">
   <b>Modelo:</b>
   <select name="modelo" id="modelo" style="font-size:9px;" onchange="js_modelo(this.value)">
     <option value='1'>MODELO 1</option>
     <option value='2'>MODELO 2</option>
     <option value='3'>MODELO 3</option>
     <option value='4'>MODELO 4</option>
     <?if ($ed57_i_tipoturma == 2 || $ed57_i_tipoturma == 3) {?>
         <option value='5'>MODELO 5</option>
     <?}?>
   </select>
   </td>
  </tr>
  <tr>
    <td colspan="3" id="teste">
      <fieldset><legend><b>Configura��o do Relat�rio</b></legend>
        <input id = "avaliacao" type = "checkbox" name = "avaliacao" value = "" checked> Avalia��es
        <input id = "falta"     type = "checkbox" name = "falta"     value = "" checked> Total de Faltas
      </fieldset>
    </td>
  </tr>
  <tr>
   <td colspan="3" id="teste1" style="display: none">
    <fieldset><legend><b>Configura��o do Relat�rio</b></legend>
      <input id = "sexo"      type = "checkbox" name = "sexo"      value= "" checked>Sexo
      <input id = "idade"     type = "checkbox" name = "idade"     value= "" checked>Idade
      <input id = "abono"     type = "checkbox" name = "abono"     value= "" checked>Faltas Abonadas
      <input id = "codigo"    type = "checkbox" name = "codigo"    value= "" checked>C�digo
      <input id = "nasc"      type = "checkbox" name = "nasc"      value= "" checked>Nascimento
      <input id = "resultant" type = "checkbox" name = "resultant" value= "" checked>Resultado Anterior
      <input id = "totalfal"  type = "checkbox" name = "totalfal"  value= "" checked>Total de faltas
      <input id = "parecer"   type = "checkbox" name = "parecer"   value= "" checked>Parecer
    </fieldset>
   </td>
  </tr>
  <tr>
  	<td colspan="3" id="configuracaoModelo4" style="display: none">
    <fieldset><legend><b>Configura��o do Relat�rio</b></legend>
      <input id = "faltaMod4"  type = "checkbox" name = "falta" value = "" checked><span>Total de Faltas</span>
      <input id = "pontos" type = "checkbox" name = "falta" value = "" checked><span>Exibir Pontos</span>
    </fieldset>
   </td>
  </tr>
  <tbody id="div_regencia">
  <tr>
   <td valign="top">
   <?
      $sql    = " SELECT ed59_i_codigo,ed232_c_descr,ed59_i_ordenacao ";
      $sql   .= "   FROM regencia ";
      $sql   .= "     inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
      $sql   .= "     inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
      $sql   .= "     inner join turma on turma.ed57_i_codigo = regencia.ed59_i_turma ";
      $sql   .= "     inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ";
      $sql   .= "     inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ";
      $sql   .= "     inner join serie on ed11_i_codigo = ed223_i_serie ";
      $sql   .= "   WHERE ed220_i_codigo = $turma ";
      $sql   .= "         AND ed223_i_serie = ed59_i_serie ";
      $sql   .= "         ORDER BY ed59_i_ordenacao  ";
      $result = db_query($sql);
      $linhas = pg_num_rows($result);
   ?>
   <b>Disciplinas:</b><br>
   <select name="alunosdiario" id="alunosdiario" size="10" onclick="js_desabinc()"
           style="font-size:9px;width:330px;height:120px" multiple>
    <?
    for ($i = 0; $i < $linhas; $i++) {
      db_fieldsmemory($result,$i);
      echo "<option value='$ed59_i_codigo'>$ed232_c_descr</option>\n";
    }
    ?>
   </select>
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
     <tr><td height="1"></td></tr>
     <tr>
      <td>
       <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();"
              style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                     font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;">
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
              style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                     font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
       </td>
     </tr>
     <tr><td height="1"></td></tr>
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
    <b>Disciplinas para gerar di�rio de classe:</b><br>
    <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()"
            style="font-size:9px;width:330px;height:120px" multiple>
    </select>
   </td>
  </tr>
  </tbody>
  <tr>
   <td colspan="3">
   <b>Per�odo de Avalia��o:</b>
   <?
   $result_t = $clturma->sql_record($clturma->sql_query_turmaserie("",
                                                                   "ed220_i_procedimento",
                                                                   "",
                                                                   " ed220_i_codigo = $turma"
                                                                  )
                                   );
   db_fieldsmemory($result_t,0);
   $result_d = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("",
                                                                        "ed41_i_codigo,ed09_c_descr",
                                                                        "ed41_i_sequencia",
                                                                        " ed41_i_procedimento = $ed220_i_procedimento
                                                                          AND ed09_c_somach = 'S'"
                                                                       )
                                           );
   ?>
   <select name="periodo" id="periodo" style="font-size:9px;">
    <?
    for ($y = 0; $y < $clprocavaliacao->numrows; $y++) {

      db_fieldsmemory($result_d,$y);
      echo "<option value='$ed41_i_codigo'>$ed09_c_descr</option>";

    }
    ?>
   </select>
   &nbsp;&nbsp;
   <b>Informar Dias Letivos:</b>
   <select name="informadiasletivos" onChange="js_DiasLetivos(this.value);" style="font-size:9px;">
     <option value='S'>SIM</option>
     <option value='N'>N�O</option>
   </select>
   <span id="colunas" style="visibility:hidden;">
    &nbsp;&nbsp;
    <b>Quantidade de Colunas (Presen�as):</b>
    <select name="qtdecolunas" style="font-size:9px;">
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
   <b>Mostrar somente alunos ativos (Matriculados)</b>:
   <select name="active">
    <option value="SIM" selected>SIM</option>
    <option value="NAO">N�O</option>
   </select>
   <br>
   </td>
  </tr>
  <tr>
    <td>
      <b>Exibir Trocas de Turma:</b>
      <select id='trocaTurma' name='trocaTurma' style="font-size:9px;width:200px;height:18px;">
        <option value="1" selected="selected">N�o</option>
        <option value="2">Sim</option>
      </select>
    </td>
  </tr>
  <tr>
   <td align="center" colspan="3">
    <input name="pesquisar" type="button" id="pesquisar" value="Processar"
           onclick="js_pesquisa(document.form1.subgrupo.value,<?=$ed57_i_codigo?>);" disabled>
    <br><br>
    <fieldset style="align:left">
     <table>
      <tr>
       <td>
        MODELO 1 -> Uma disciplina por p�gina (�rea)
       </td>
      </tr>
      <tr>
       <td>
        MODELO 2 -> Todas disciplinas em uma p�gina (Curr�culo)
       </td>
      </tr>
      <tr>
       <td>
        MODELO 3 -> Duas p�ginas por disciplina (P�gina 1 - Presen�as / P�gina 2 - Avalia��es)
       </td>
      </tr>
      <tr>
       <td>
        MODELO 4 -> Uma disciplina por p�gina (Logotipo prefeitura e total de faltas)
       </td>
      </tr>
      <?if($ed57_i_tipoturma==2||$ed57_i_tipoturma==3){?>
      <tr>
       <td>
        MODELO 5 -> Turma <?=$arr_tipo[$ed57_i_tipoturma]?> (P�gina de Presen�as com alunos de todas as etapas)
       </td>
      </tr>
      <?}?>

     </table>
    </fieldset>
    <br>
    <fieldset style="align:center">
     Para selecionar mais de uma disciplina<br>mantenha pressionada a tecla CTRL <br>e clique sobre o nome da disciplina.
    </fieldset>
    <input type="hidden" name="base" value="<?=$base?>">
    <input type="hidden" name="curso" value="<?=$curso?>">
   </td>
  </tr>
 <?}?>
</table>
</fieldset>
</center>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
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

function js_procurar(calendario,turma) {
  location.href = "edu2_diarioclasse001.php?calendario="+calendario+"&turma="+turma;
}

function js_pesquisa(turma,codturma) {


	oDBFormCache.save();
  if (document.form1.periodo.value == "") {

    alert("Informe o per�odo de avalia��o!");
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

    alert ('Selecione ao menos uma disciplina para emiss�o do relat�rio.');
    return false;
  }

  for (i = 0; i < F.length; i++) {

    disciplinas += sep+F.options[i].value;
    sep = ",";

  }

  var sAquivo = 'edu2_diarioclasse002.php?';
  var sUrl    = 'qtdecolunas='+document.form1.qtdecolunas.value;
      sUrl   += '&informadiasletivos='+document.form1.informadiasletivos.value;
      sUrl   += '&periodo='+document.form1.periodo.value+'&disciplinas='+disciplinas;
      sUrl   += '&turma='+turma+'&active='+document.form1.active.value;
      sUrl   += '&trocaTurma='+$F('trocaTurma');


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
    jan = window.open(sEnvio, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  } else if (document.form1.modelo.value == 2) {

    sAquivo = 'edu2_diarioclasse003.php?';
    sEnvio  = sAquivo + sUrl;

    jan = window.open(sEnvio,'',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

  }  else {

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

    jan = window.open('edu2_diarioclasse004.php?qtdecolunas='+document.form1.qtdecolunas.value+
                      '&informadiasletivos='+document.form1.informadiasletivos.value+
                      '&periodo='+document.form1.periodo.value+'&disciplinas='+disciplinas+'&turma='+turma+
                      '&active='+document.form1.active.value+'&sexo='+sexo+'&idade='+idade+'&abono='+abono+'&codigo='+codigo+
                      '&nasc='+nasc+'&resultant='+resultant+'&totalfal='+totalfal+'&parecer='+parecer+
                      '&trocaTurma='+$F('trocaTurma'),'',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

   }
   jan.moveTo(0,0);
}

function js_DiasLetivos(valor) {
  if (valor == "S") {
    document.getElementById("colunas").style.visibility = "hidden";
  } else {
    document.getElementById("colunas").style.visibility = "visible";
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
     	$('configuracaoModelo4').style.display = 'none';
  	  document.form1.pesquisar.disabled      = false;
      break;
    case '4':

      $('configuracaoModelo4').style.display = '';
      $("teste").style.display               = "none";
      $("teste1").style.display              = "none";
      document.form1.pesquisar.disabled      = false;
      break;

    case '5':

      $("teste1").style.display              = "";
      $('configuracaoModelo4').style.display = 'none';
      $("teste").style.display               = "none";
      $("div_regencia").style.display        = "none";
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
      $("div_regencia").style.display    = "";
      $("div_regencia").style.position   = "static";
      break;
  }


}
<?if (!isset($turma) && pg_num_rows($sql_result) > 0) {?>

    fillSelectFromArray2(document.form1.subgrupo,team[0]);
    document.form1.grupo.options[1].selected = true;

<?}?>
</script>