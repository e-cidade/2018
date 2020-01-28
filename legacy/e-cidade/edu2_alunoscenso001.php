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

include(modification("libs/db_stdlibwebseller.php"));
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
$escola = db_getsession("DB_coddepto");
if(!isset($ed52_i_ano)){
 $ano_censo = date("Y");
 for($x=1;$x<=31;$x++){
  if(date("w",mktime(0,0,0,5,$x,$ano_censo)) == 3){
   $data_censo_dia = strlen($x)==1?"0".$x:$x;
   $data_censo_mes = "05";
   $data_censo_ano = $ano_censo;
  }
 }
 $data_censo = $data_censo_dia."/".$data_censo_mes."/".$data_censo_ano;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<script>
 team = new Array(
 <?
 $sql = "SELECT DISTINCT ed10_i_codigo,ed10_c_descr,ed10_c_abrev
         FROM ensino
          inner join serie on ed11_i_ensino  = ed10_i_codigo
          inner join matriculaserie on ed221_i_serie = ed11_i_codigo
          inner join matricula on ed60_i_codigo = ed221_i_matricula
          inner join turma on ed57_i_codigo = ed60_i_turma
         WHERE ed57_i_escola = $escola
         ORDER BY ed10_c_abrev
        ";
 $sql_result = db_query($sql);
 $num = pg_num_rows($sql_result);
 $conta = "";
 while ($row=pg_fetch_array($sql_result)){
  $conta = $conta+1;
  $cod_curso = $row["ed10_i_codigo"];
  echo "new Array(\n";
  $sub_sql = "SELECT DISTINCT ed11_i_codigo,ed11_c_descr,ed11_i_sequencia
              FROM serie
               inner join matriculaserie on ed221_i_serie = ed11_i_codigo
               inner join matricula on ed60_i_codigo = ed221_i_matricula
               inner join turma on ed57_i_codigo = ed60_i_turma
              WHERE ed57_i_escola = $escola
              AND ed11_i_ensino = '$cod_curso'
              ORDER BY ed11_i_sequencia
             ";
  $sub_result = db_query($sub_sql);
  $num_sub = pg_num_rows($sub_result);
  if ($num_sub>=1){
   echo "new Array(\"\", ''),\n";
   $conta_sub = "";
   while ($rowx=pg_fetch_array($sub_result)){
    $codigo_base=$rowx["ed11_i_codigo"];
    $base_nome=$rowx["ed11_c_descr"];
    $conta_sub=$conta_sub+1;
    if ($conta_sub==$num_sub){
     echo "new Array(\"$base_nome\", $codigo_base)\n";
     $conta_sub = "";
    }else{
     echo "new Array(\"$base_nome\", $codigo_base),\n";
    }
   }
  }else{
   echo "new Array(\"Ensino sem etapas cadastradas\", '')\n";
  }
  if ($num>$conta){
   echo "),\n";
  }
}
echo ")\n";
echo ");\n";
?>
//Inicio da função JS
function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem){
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
 }else{
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
  document.form1.subgrupo.disabled = false;
 }
}
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
<fieldset style="width:95%"><legend><b>Listagem de Alunos para o Censo Escolar</b></legend>
<table width="80%" border="0" align="left">
 <tr>
  <td colspan="3">
   <table border="0" align="left">
    <tr>
     <td>
      <b>Data do Censo:</b>
     </td>
     <td>
      <?db_inputdata('data_censo',@$data_censo_dia,@$data_censo_mes,@$data_censo_ano,true,'text',1," onchange=\"js_ano();\"","","","parent.js_ano();")?>
      <b>Ano:</b>
      <?db_input('ano_censo',4,@$ano_censo,true,'text',3,"")?>
     </td>
     <td></td>
     <td></td>
    </tr>
    <tr>
     <td colspan="4">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Opcionais:
     </td>
    </tr>
    <tr>
     <td>
      <b>Filtrar por Ensino:</b>
     </td>
     <td>
      <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:200px;height:18px;">
      <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $sql = "SELECT DISTINCT ed10_i_codigo,ed10_c_descr,ed10_c_abrev
               FROM ensino
                inner join serie on ed11_i_ensino  = ed10_i_codigo
                inner join matriculaserie on ed221_i_serie = ed11_i_codigo
                inner join matricula on ed60_i_codigo = ed221_i_matricula
                inner join turma on ed57_i_codigo = ed60_i_turma
               WHERE ed57_i_escola = $escola
               ORDER BY ed10_c_abrev";
       $sql_result = db_query($sql);
       while($row=pg_fetch_array($sql_result)){
        $cod_curso=$row["ed10_i_codigo"];
        $desc_curso=$row["ed10_c_descr"];
        ?>
        <option value="<?=$cod_curso;?>" ><?=$desc_curso;?></option>
        <?
       }
       #Popula o segundo combo de acordo com a escolha no primeiro
       ?>
      </select>
     </td>
     <td>
      &nbsp;&nbsp;<input type="checkbox" name="tt_ensino" value=""> <b>Totalizador por Ensino</b>
     </td>
     <td>
      &nbsp;&nbsp;<input type="checkbox" name="titulo_ensino" value=""> <b>Mostrar títulos por ensino</b>
     </td>
    </tr>
    <tr>
     <td>
      <b>Filtrar por Etapa:</b>
     </td>
     <td>
      <select name="subgrupo" style="font-size:9px;width:200px;height:18px;" disabled>
       <option value=""></option>
      </select>
     </td>
     <td>
      &nbsp;&nbsp;<input type="checkbox" name="tt_serie" value=""> <b>Totalizador por Etapa</b>
     </td>
     <td>
      &nbsp;&nbsp;<input type="checkbox" name="titulo_serie" value=""> <b>Mostrar títulos por etapa</b>
     </td>
    </tr>
   </table>
  </td>
 </tr>
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
     <td rowspan="12" valign="top" width="30" style="border-left:1px solid #000000">
     &nbsp;
     </td>
     <td rowspan="12" valign="top">
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
         <select name="camposordenados" id="camposordenados" size="13" style="width:200px">
         </select>
        </td>
        <td valign="top">
         <br>
          <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
         <br/>
          <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
         <br>
        </td>
       </tr>

       <tr>
        <td colspan="2">
         <!--
         <b>Ordenação dos dados:</b><br>
         <select name="ordenacao" style="width:200px">
          <option value="turma.ed57_c_descr">NOME DA TURMA</option>
          <option value="to_ascii(ed47_v_nome)">NOME DO ALUNO</option>
          <option value="ed47_i_codigo">CÒDIGO DO ALUNO</option>
          <option value="ed47_d_nasc">NASCIMENTO</option>
         </select><br>
         -->
         <b>Tamanho da Fonte:</b><br>
         <select name="tamfonte" style="width:200px">
          <option value="6">6</option>
          <option value="7" selected>7</option>
          <option value="8">8</option>
          <option value="9">9</option>
         </select><br>
        </td>
       </tr>
      </table>
     </td>
    </tr>
    <tr>
     <td valign="top">
      <input type="checkbox" name="campos" value="ed60_d_datamatricula" onclick="VerificaTamanho(0);" checked> Data Matrícula
      <input type="hidden" name="largura" id="largura" value="15">
      <input type="hidden" name="cabecalho" value="Dt. Matric"><br>
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
      <input type="checkbox" name="campos" value="ed60_d_datasaida" onclick="VerificaTamanho(1);" checked> Data Saída
      <input type="hidden" name="largura" id="largura" value="15">
      <input type="hidden" name="cabecalho" value="Dt. Saída"><br>
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
      <input type="checkbox" name="campos" value="trim(turma.ed57_c_descr)" onclick="VerificaTamanho(2);" checked> Turma
      <input type="hidden" name="largura" id="largura" value="20">
      <input type="hidden" name="cabecalho" value="Turma"><br>
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
      <input type="checkbox" name="campos" value="ed47_v_sexo" onclick="VerificaTamanho(3);" checked> Sexo
      <input type="hidden" name="largura" id="largura" value="5">
      <input type="hidden" name="cabecalho" value="Sx"><br>
     </td>
     <td>
      5
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
      <input type="checkbox" name="campos" value="ed47_v_nome" onclick="VerificaTamanho(4);" checked disabled> Nome do Aluno
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
      <input type="checkbox" name="campos" value="ed47_d_nasc" onclick="VerificaTamanho(5);" checked> Data Nascimento
      <input type="hidden" name="largura" id="largura" value="15">
      <input type="hidden" name="cabecalho" value="Nascimento"><br>
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
      <input type="checkbox" name="campos" value="case when ed47_i_transpublico = 1 then 'SIM' else null end as transporte" onclick="VerificaTamanho(6);"> Transporte Escolar
      <input type="hidden" name="largura" id="largura" value="5">
      <input type="hidden" name="cabecalho" value="T"><br>
     </td>
     <td>
      5
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
      <input type="checkbox" name="campos" value="case when ed47_c_zona = 'RURAL' then 'R' else 'U' end as zona " onclick="VerificaTamanho(7);"> Zona Localização
      <input type="hidden" name="largura" id="largura" value="5">
      <input type="hidden" name="cabecalho" value="Z"><br>
     </td>
     <td>
      5
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
      <input type="checkbox" name="campos" value="(select array_to_string( array(select ed214_i_necessidade from alunonecessidade where ed214_i_aluno = ed47_i_codigo), ',') ) as necessidade" onclick="VerificaTamanho(8);"> Necessidades Especiais
      <input type="hidden" name="largura" id="largura" value="10">
      <input type="hidden" name="cabecalho" value="NE"><br>
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
      <input type="checkbox" name="campos" value="ed47_i_codigo" onclick="VerificaTamanho(9);"> Código Aluno
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
      <input type="checkbox" name="campos" value="fc_edurfanterior(ed60_i_codigo)" onclick="VerificaTamanho(10);"> Rendimento
      <input type="hidden" name="largura" id="largura" value="5">
      <input type="hidden" name="cabecalho" value="R"><br>
     </td>
     <td>
      5
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
      <input type="checkbox" name="campos" value="substr(ed60_c_situacao,1,5) as situacao" onclick="VerificaTamanho(11);"> Situação
      <input type="hidden" name="largura" id="largura" value="10">
      <input type="hidden" name="cabecalho" value="St"><br>
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
   <table>
  </td>
 <tr>
  <td align="center" colspan="3">
   <input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa(document.form1.subgrupo.value);">
   <br><br>
  </td>
 </tr>
</table>
</fieldset>
</center>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
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
VerificaTamanho(0);
VerificaTamanho(1);
VerificaTamanho(2);
VerificaTamanho(3);
VerificaTamanho(4);
VerificaTamanho(5);
VerificaTamanho(6);
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
function js_ano(){
 datacenso = document.form1.data_censo.value;
 if(datacenso!="" && datacenso.length==10){
  datacenso = datacenso.split("/");
  document.form1.ano_censo.value = datacenso[2];
 }else{
  document.form1.ano_censo.value = "";
 }
}
function js_pesquisa(turma){
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
 if(document.form1.data_censo.value==""){
  alert("Informe a data do censo!");
  return false;
 }
 tt_ensino = document.form1.tt_ensino.checked==true?"yes":"no";
 tt_serie = document.form1.tt_serie.checked==true?"yes":"no";
 titulo_ensino = document.form1.titulo_ensino.checked==true?"yes":"no";
 titulo_serie = document.form1.titulo_serie.checked==true?"yes":"no";

 campos      = btoa(campos);
 cabecalho   = btoa(cabecalho);
 colunas     = btoa(colunas);
 alinhamento = btoa(alinhamento);

 jan = window.open('edu2_alunoscenso002.php?tt_ensino='+tt_ensino+'&tt_serie='+tt_serie+'&titulo_ensino='+titulo_ensino+'&titulo_serie='+titulo_serie+'&data_censo='+document.form1.data_censo.value+'&ano_censo='+document.form1.ano_censo.value+'&ensino='+document.form1.grupo.value+'&serie='+document.form1.subgrupo.value+'&orientacao='+document.form1.orientacao.value+'&alinhamento='+alinhamento+'&campos='+campos+'&cabecalho='+cabecalho+'&colunas='+colunas+'&tamfonte='+document.form1.tamfonte.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
</script>