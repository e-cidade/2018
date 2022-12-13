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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));

$clmatricula             = new cl_matricula;
$clturma                 = new cl_turma;
$clturmaserieregimemat   = new cl_turmaserieregimemat;
$clregencia              = new cl_regencia;
$clturmaturnoadicional   = new cl_turmaturnoadicional;
$clparecerturma          = new cl_parecerturma;
$clcalendario            = new cl_calendario;
$clturmalog              = new cl_turmalog;
$oDaoTurmaTurnoReferente = new cl_turmaturnoreferente();
$oDaoTurmaCensoEtapa     = new cl_turmacensoetapa();
$oDaoCensoEtapa          = new cl_censoetapa();

$escola  = db_getsession("DB_coddepto");
$usuario = DB_getsession("DB_id_usuario");
$hoje    = date("Y-m-d",db_getsession("DB_datausu"));

if ( isset( $processar ) ) {

  db_inicio_transacao();
  $turmant = "";
  $sep     = "";

  if ( isset( $alunos ) ) {

    for ($r = 0; $r < sizeof($alunos); $r++) {

       $turmant .= $sep.$alunos[$r];
       $sep    = ",";
    }
  }

  $sSqlTurma = $clturma->sql_query( "", "ed57_i_codigo as codigo, turma.*, turmacensoetapa.*", "", "ed57_i_codigo in ({$turmant})" );
  $result    = $clturma->sql_record( $sSqlTurma );
  $linhas    = $clturma->numrows;
  $turmanova = "";
  $sepnovo   = "";

  for ($e = 0; $e < $linhas; $e++) {

	  db_fieldsmemory($result, $e);
    $clturma->ed57_i_escola                  = $ed57_i_escola;
    $clturma->ed57_i_calendario              = $calendario1;
    $clturma->ed57_c_descr                   = $ed57_c_descr;
    $clturma->ed57_i_base                    = $ed57_i_base;
    $clturma->ed57_i_turno                   = $ed57_i_turno;
    $clturma->ed57_i_sala                    = $ed57_i_sala;
    $clturma->ed57_i_nummatr                 = 0;
    $clturma->ed57_c_medfreq                 = $ed57_c_medfreq;
    $clturma->ed57_t_obs                     = $ed57_t_obs;
    $clturma->ed57_i_codigoinep              = "null";
    $clturma->ed57_i_tipoatend               = $ed57_i_tipoatend;
    $clturma->ed57_i_ativqtd                 = $ed57_i_ativqtd;
    $clturma->ed57_i_censocursoprofiss       = $ed57_i_censocursoprofiss;
    $clturma->ed57_i_tipoturma               = $ed57_i_tipoturma;
    $clturma->ed57_censoprogramamaiseducacao = $ed57_censoprogramamaiseducacao == 't' ? 'true' : 'false';
    $clturma->incluir(null);

    $turmanova .= $sepnovo.$clturma->ed57_i_codigo;
    $sepnovo    = ",";

    $sSqlTurmaSerieRegimeMat   = $clturmaserieregimemat->sql_query( "", "*", "", "ed220_i_turma = {$codigo}" );
    $result1                   = $clturmaserieregimemat->sql_record( $sSqlTurmaSerieRegimeMat );
    $turmanov                  = $clturma->ed57_i_codigo;
    $linhasturmaserieregimemat =  $clturmaserieregimemat->numrows;

    for ($f = 0; $f < $linhasturmaserieregimemat; $f++) {

	   db_fieldsmemory($result1, $f);
      $clturmaserieregimemat->ed220_i_turma          = $turmanov;
      $clturmaserieregimemat->ed220_i_serieregimemat = $ed220_i_serieregimemat;
      $clturmaserieregimemat->ed220_c_historico      = $ed220_c_historico;
      $clturmaserieregimemat->ed220_i_procedimento   = $ed220_i_procedimento;
      $clturmaserieregimemat->ed220_c_aprovauto      = $ed220_c_aprovauto;
      $clturmaserieregimemat->incluir(null);
    }

    $result2           = $clregencia->sql_record($clregencia->sql_query( "", "*", "", "ed59_i_turma = {$codigo}" ) );
    $linhasregencia    = $clregencia->numrows;

    for ($i = 0; $i < $linhasregencia; $i++) {

	    db_fieldsmemory($result2, $i);
      $clregencia->ed59_i_turma              = $turmanov;
      $clregencia->ed59_i_disciplina         = $ed59_i_disciplina;
      $clregencia->ed59_i_qtdperiodo         = $ed59_i_qtdperiodo;
      $clregencia->ed59_c_condicao           = $ed59_c_condicao;
      $clregencia->ed59_c_freqglob           = $ed59_c_freqglob;
      $clregencia->ed59_c_ultatualiz         = 'SI';
      $clregencia->ed59_d_dataatualiz        = $hoje;
      $clregencia->ed59_c_encerrada          = 'N';
      $clregencia->ed59_i_ordenacao          = $ed59_i_ordenacao;
      $clregencia->ed59_i_serie              = $ed59_i_serie;
      $clregencia->ed59_lancarhistorico      = $ed59_lancarhistorico      == 't' ? 'true' : 'false';
      $clregencia->ed59_caracterreprobatorio = $ed59_caracterreprobatorio == 't' ? 'true' : 'false';;
      $clregencia->ed59_basecomum            = $ed59_basecomum            == 't' ? 'true' : 'false';
      $clregencia->ed59_procedimento         = $ed59_procedimento;
      $clregencia->incluir(null);
    }

    $sSqlTurmaTurnoAdicional = $clturmaturnoadicional->sql_query( "", "*", "", "ed246_i_turma = {$codigo}");
    $result3                 = $clturmaturnoadicional->sql_record( $sSqlTurmaTurnoAdicional );
    $linhasturno             = $clturmaturnoadicional->numrows;

    for ($y = 0; $y < $linhasturno; $y++) {

	    db_fieldsmemory($result3,$y);
      $clturmaturnoadicional->ed246_i_turma = $turmanov;
      $clturmaturnoadicional->ed246_i_turno = $ed246_i_turno;
      $clturmaturnoadicional->incluir(null);
    }

    $sSqlParecerTurma   = $clparecerturma->sql_query( "", "*", "", "ed105_i_turma = {$codigo}" );
    $result4            = $clparecerturma->sql_record( $sSqlParecerTurma );
    $linhasparecerturma = $clparecerturma->numrows;

    for ($w = 0; $w < $linhasparecerturma; $w++) {

	    db_fieldsmemory($result4, $w);
      $clparecerturma->ed105_i_turma   = $turmanov;
      $clparecerturma->ed105_i_parecer = $ed105_i_parecer;
      $clparecerturma->incluir(null);
    }

    /**
     * Busca os dados da tabela turmaturnoreferente da turma de origem, para salvar um novo registro da turma criada
     */
    $sSqlTurmaTurnoReferente    = $oDaoTurmaTurnoReferente->sql_query_file( null, "*", null, "ed336_turma = {$codigo}" );
    $rsTurmaTurnoReferente      = db_query( $sSqlTurmaTurnoReferente );
    $iLinhasTurmaTurnoReferente = pg_num_rows( $rsTurmaTurnoReferente );

    for ( $iContador = 0; $iContador < $iLinhasTurmaTurnoReferente; $iContador++ ) {

      $oDadosTurmaTurnoReferente                     = db_utils::fieldsMemory( $rsTurmaTurnoReferente, $iContador );
      $oDaoTurmaTurnoReferente->ed336_turma          = $turmanov;
      $oDaoTurmaTurnoReferente->ed336_turnoreferente = $oDadosTurmaTurnoReferente->ed336_turnoreferente;
      $oDaoTurmaTurnoReferente->ed336_vagas          = $oDadosTurmaTurnoReferente->ed336_vagas;
      $oDaoTurmaTurnoReferente->incluir( null );
    }

    $clturmalog->ed287_i_turma      = $turmanov;
    $clturmalog->ed287_i_usuario    = $usuario;
    $clturmalog->ed287_d_data       = $hoje;
    $clturmalog->ed287_c_hora       = db_hora();
    $clturmalog->ed287_i_escola     = $escola;
    $clturmalog->ed287_i_tipoturma  = 1;
    $clturmalog->ed287_i_codigoant  = $codigo;
    $clturmalog->incluir(null);

    $oCalendario  = new Calendario($calendario1);
    $iAnoCenso    = DadosCenso::getUltimoAnoEtapaCenso();
    $iAnoConsulta = $ed132_ano;
    $iCensoEtapa  = $ed132_censoetapa;

    if ( $oCalendario->getAnoExecucao() > 2014 && $oCalendario->getAnoExecucao() == $iAnoCenso ) {
      $iAnoConsulta = $iAnoCenso;
    }

    $oTurma = new Turma($clturma->ed57_i_codigo);

    $aEtapas = $oTurma->getEtapas();

    if ( count($aEtapas) == 1) {

      $oDaoSerieCensoEtapa   = new cl_seriecensoetapa();
      $sWhereSerieCensoEtapa = "ed133_serie = {$aEtapas[0]->getEtapa()->getCodigo()} and ed133_ano = {$iAnoConsulta}";
      $sSqlSerieCensoEtapa   = $oDaoSerieCensoEtapa->sql_query_file( null, "ed133_censoetapa", null, $sWhereSerieCensoEtapa);
      $rsSerieCensoEtapa     = db_query($sSqlSerieCensoEtapa);

      if ( !$rsSerieCensoEtapa ) {
        throw new DBException("Erro ao buscar o vínculo da série com o censo.");
      }

      if ( pg_num_rows($rsSerieCensoEtapa) > 0 )  {
        $iCensoEtapa = db_utils::fieldsMemory( $rsSerieCensoEtapa, 0 )->ed133_censoetapa;
      }
    } else {

      $sWhereCensoEtapa = " ed266_i_codigo = {$iCensoEtapa} and ed266_ano = {$iAnoCenso} ";
      $sSqlCensoEtapa   = $oDaoCensoEtapa->sql_query_file( null, null, "1", null, $sWhereCensoEtapa);
      $rsCensoEtapa     = db_query( $sSqlCensoEtapa );

      if ( !$rsCensoEtapa ) {
        throw new DBException("Erro ao buscar a etapa do censo.");
      }

      if ( pg_num_rows($rsCensoEtapa) == 0) {
        $iAnoCenso = 2014;
      }
    }

    $oDaoTurmaCensoEtapa->ed132_codigo     = null;
    $oDaoTurmaCensoEtapa->ed132_turma      = $clturma->ed57_i_codigo;
    $oDaoTurmaCensoEtapa->ed132_ano        = $iAnoConsulta;
    $oDaoTurmaCensoEtapa->ed132_censoetapa = $iCensoEtapa;
    $oDaoTurmaCensoEtapa->incluir(null);
  }

  db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<SCRIPT LANGUAGE="JavaScript">
 team = new Array(
 <?
 # Seleciona todos os calendários
 $sql_result = $clcalendario->sql_record($clcalendario->sql_query_calescola("",
                                                                            "ed52_i_codigo,ed52_c_descr",
                                                                            "ed52_i_ano DESC",
                                                                            "ed38_i_escola = $escola
                                                                              AND ed52_c_passivo = 'N'"
                                                                           )
                                        );
 $row        = $clcalendario->numrows;
 $num        = pg_num_rows($sql_result);
 $conta      = "";
 while ($row = pg_fetch_array($sql_result)) {
   $conta     = $conta+1;
   $cod_curso = $row["ed52_i_codigo"];
   echo "new Array(\n";
   $sub_sql = $clcalendario->sql_record($clcalendario->sql_query_calescola("",
                                                                           "ed52_i_codigo,ed52_c_descr",
                                                                           "ed52_i_ano DESC",
                                                                           "ed38_i_escola = $escola
                                                                            AND ed52_i_calendant= $cod_curso "
                                                                          )
                                       );
   $num_sub = $clcalendario->numrows;
   if ($num_sub >= 1) {
     # Se achar alguma base para o curso, marca a palavra Todas
     echo "new Array(\"\", ''),\n";
     $conta_sub = "";
     while ($rowx = pg_fetch_array($sub_sql)) {
       $codigo_base = $rowx["ed52_i_codigo"];
       $base_nome   = $rowx["ed52_c_descr"];
       $conta_sub   = $conta_sub+1;
       if ($conta_sub == $num_sub) {
         echo "new Array(\"$base_nome \", $codigo_base)\n";
         $conta_sub = "";
       } else {
         echo "new Array(\"$base_nome \", $codigo_base),\n";
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
<?if (isset($calendario1)) {?>
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
    <?if (isset($calendario1)) {?>
        if (<?=trim($calendario1)?> == itemArray[i][1]) {
          indice = i;
        }
    <?}?>
      j++;
    }
  <?if (isset($calendario1)) {?>
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
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" action="">
<center>
<br>
<fieldset style="width:95%"><legend><b>Remanejamento de Turmas</b></legend>
<table border="0" align="left">
 <tr>
  <td colspan="3">
   <table border="0" align="left">
    </tr>
     <td>
      <b>Calendário Origem:</b><br>
      <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:200px;height:18px;">
       <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $result = $clcalendario->sql_record($clcalendario->sql_query_calescola("",
                                                                              "ed52_i_codigo,ed52_c_descr",
                                                                              "ed52_i_ano DESC",
                                                                              "ed38_i_escola = $escola
                                                                                AND ed52_c_passivo = 'N'"
                                                                             )
                                          );
       $row    = $clcalendario->numrows;
       while($row = pg_fetch_array($result)) {

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
      <b>Calendário Destino:</b><br>
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
 <?if (isset($calendario1)) {?>

     <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
     <tr>
      <td valign="top">
       <?

        $sql    = " SELECT ed57_i_codigo,ed57_c_descr";
        $sql   .= " from turma ";
        $sql   .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
        $sql   .= "      inner join calendarioescola  on  calendarioescola.ed38_i_calendario = calendario.ed52_i_codigo";
        $sql   .= " where ed38_i_escola = $escola and ed52_i_codigo = $calendario ";
        $sql   .= " and ed57_i_codigo not in ";
        $sql   .= " (select ed287_i_codigoant from turmalog ";
        $sql   .= "        inner join turma  on  turma.ed57_i_codigo = turmalog.ed287_i_turma ";
        $sql   .= "        inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario ";
        $sql   .= "        where ed287_i_tipoturma = 1 and ed57_i_calendario = $calendario1)  order by ed57_c_descr";

        $result = db_query($sql);
        $linhas = pg_num_rows($result);
       ?>
        <b>Turmas:</b><br>
       <select name="alunosdiario" id="alunosdiario" onclick="js_desabinc()"
               style="font-size:9px;width:300px;height:120px" multiple>
       <?
        for ($i = 0; $i < $linhas; $i++) {

          db_fieldsmemory($result,$i);
          echo "<option value='$ed57_i_codigo'>$ed57_c_descr</option>\n";

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
                       font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" <?=$linhas==0?"disabled":""?>>
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
        <b>Turmas Remanejadas:</b><br>
        <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()"
                style="font-size:9px;width:300px;height:120px" multiple>
        </select>
       </td>
       <td valign="top">
        <b>Turmas Incluídas:</b><br>
        <?
            $sql23    = " SELECT ed57_i_codigo as codigo1,ed57_c_descr as descr1";
            $sql23   .= " from turma ";
            $sql23   .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
            $sql23   .= "      left join turmalog on turmalog.ed287_i_turma = turma.ed57_i_codigo";
            $sql23   .= " where ed52_i_codigo = $calendario1 order by descr1";
            $result23 = db_query($sql23);
            $linhas23 = pg_num_rows($result23);

         ?>
        <select name="turmasincluidas" id="turmasincluidas" size="10" onclick="js_desabexc()"
                style="font-size:9px;width:300px;height:120px" multiple>
         <?
          for ($i = 0; $i < $linhas23; $i++) {

            db_fieldsmemory($result23,$i);
            echo "<option disabled value='$codigo1'>$descr1</option>\n";

          }
         ?>
        </select>
       </td>
      </tr>
     </tr>
    <tr>
     <td align="center" colspan="3">
      <input name="processar" type="submit" id="processar" value="Processar"
             onClick="return js_selecionar();" disabled>
      <br><br>
      <fieldset style="width:250;align:center">
        Para selecionar mais de uma turma<br>mantenha pressionada a tecla CTRL <br>e clique sobre o nome da turma.
      </fieldset>
      <input type="hidden" name="base" value="<?php echo isset($base) ? $base : ''?>">
      <input type="hidden" name="curso" value="<?php echo isset($curso) ? $curso : ''?>">
     </td>
    </tr>
 <?}?>
</table>
</fieldset>
</center>
</form>
<?db_menu(db_getsession("DB_id_usuario"),
  db_getsession("DB_modulo"),
  db_getsession("DB_anousu"),
  db_getsession("DB_instit"));
?>
</body>
</html>
<script>
<?if (isset($processar)) {?>

    js_OpenJanelaIframe('','db_iframe_remanejarturma','func_remanejarturma.php?turma=<?=$turmanova?>'+
		                   '&calendario=<?=@$calendario1?>','Turmas',true);
    location.href = "#topo";

<?}?>

function js_incluir() {

  var Tam = document.form1.alunosdiario.length;
  var F   = document.form1;
  for (x = 0; x < Tam; x++) {

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

  document.form1.processar.disabled    = false;
  document.form1.excluirtodos.disabled = false;
  document.form1.alunosdiario.focus();
}

function js_incluirtodos() {

  var Tam = document.form1.alunosdiario.length;
  var F   = document.form1;
  //var linhas = document.form1.linhas1.value;
  for (i = 0; i < Tam; i++) {

    F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[0].text,F.alunosdiario.options[0].value);
    F.alunosdiario.options[0] = null;

  }

  document.form1.incluirum.disabled    = true;
  document.form1.incluirtodos.disabled = true;
  document.form1.excluirtodos.disabled = false;
  document.form1.processar.disabled    = false;
  document.form1.alunos.focus();
}

function js_excluir() {


  var F = document.getElementById("alunos");
  Tam   = F.length;
  for (x = 0; x < Tam; x++) {

    if (F.options[x].selected == true) {

      document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[x].text,F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;
    }

  }

  if (F.length == 0) {

    document.form1.processar.disabled    = true;
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

    document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[0].text,F.options[0].value);
    F.options[0] = null;

  }

  if (F.length == 0) {

    document.form1.processar.disabled    = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;

  }
  document.form1.alunosdiario.focus();
}

function js_selecionar() {

  var F = document.getElementById("alunos").options;

  for (var i = 0;i < F.length; i++) {

    F[i].selected = true;
  }
  return true;

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
<?if (isset($calendario1)) {?>

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

function js_procurar(calendario,calendario1) {
  location.href = "edu1_remanejarturma001.php?calendario="+calendario+"&calendario1="+calendario1;
}

function js_pesquisa(turma) {

  F           = document.form1.alunos;
  disciplinas = "";
  sep         = "";
  for (i = 0; i < F.length; i++) {

    disciplinas += sep+F.options[i].value;
    sep          = ",";

  }
}


<?if (!isset($calendario1) && pg_num_rows($sql_result) > 0) {?>

    fillSelectFromArray2(document.form1.subgrupo,team[0]);
    document.form1.grupo.options[1].selected = true;

<?}?>

</script>