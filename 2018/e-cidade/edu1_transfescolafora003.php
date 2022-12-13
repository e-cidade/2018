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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_matricula_classe.php");
include("classes/db_logmatricula_classe.php");
require_once("libs/db_utils.php");
$iEscola              = db_getsession("DB_coddepto");
$oDaoMatricula        = db_utils::getdao('matricula');
$oDaoLogMatricula     = db_utils::getdao('logmatricula');
$oDaoTransfEscolaFora = db_utils::getdao('transfescolafora');
$oDaoMatriculaMov     = db_utils::getdao('matriculamov');
$oDaoRegencia         = db_utils::getdao('regencia');
$oDaoAlunoCurso       = db_utils::getdao('alunocurso');
$oDaoAlunoCurso->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed57_c_descr");
$clrotulo->label("ed11_i_codigo");
$clrotulo->label("ed11_c_descr");
$clrotulo->label("ed60_c_situacao");
$clrotulo->label("ed60_c_concluida");
$clrotulo->label("ed18_c_nome");
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed31_i_codigo");
$clrotulo->label("ed52_i_codigo");
$clrotulo->label("ed15_i_codigo");
$clrotulo->label("ed10_i_codigo");
$clrotulo->label("ed29_i_codigo");
$clrotulo->label("ed60_i_codigo");
$clrotulo->label("ed104_d_data");
$clrotulo->label("ed104_t_obs");
if (isset($incluir)) {

  db_inicio_transacao();

  $sCampos     = "ed60_i_turma,ed60_d_datamodif as datamodif,ed60_d_datamodifant as datamodifant,";
  $sCampos    .= "ed60_d_datasaida as datasaida,ed60_c_concluida as concluida,turma.ed57_i_turno as turnoturma,";
  $sCampos    .= "ed221_i_serie as etapaoriginal";
  $sSql        = $oDaoMatricula->sql_query("", $sCampos, "", " ed60_i_codigo = $matriculaorig");
  $rsMatricula = $oDaoMatricula->sql_record($sSql);
  db_fieldsmemory($rsMatricula,0);

  if ($concluida == "N") {

  	$sWhereRegencia  = " ed59_i_turma = $codturmaorig AND ed59_i_serie = $etapaoriginal";
  	$sSqlRegencia    = $oDaoRegencia->sql_query_file("", "ed59_i_codigo as regturma", "", $sWhereRegencia);
    $rsRegencia      = $oDaoRegencia->sql_record($sSqlRegencia);
    $iLinhasRegencia = $oDaoRegencia->numrows;

    for ($f = 0; $f < $iLinhasRegencia; $f++) {

      db_fieldsmemory($rsRegencia, $f);
      $sql12  = " UPDATE diario SET ";
      $sql12 .= "               ed95_c_encerrado = 'N' ";
      $sql12 .= " WHERE ed95_i_aluno = $codigoaluno ";
      $sql12 .= "       AND ed95_i_regencia = $regturma ";
      $sql12 .= "       AND ed95_i_serie = $etapaoriginal ";
      $result12 = db_query($sql12);

   }

 }

 $sCamposAlunoCurso = "ed56_i_codigo,ed56_c_situacaoant as sitanterior";
 $sWhereAlunoCurso  = "ed56_i_aluno = $codigoaluno AND ed56_i_escola = $iEscola AND ed56_c_situacao = 'TRANSFERIDO FORA'";
 $sSqlAluno         = $oDaoAlunoCurso->sql_query_file("", $sCamposAlunoCurso, "", $sWhereAlunoCurso);
 $rsAluno           = $oDaoAlunoCurso->sql_record($sSqlAluno);
 db_fieldsmemory($rsAluno,0);
 $sitanterior = empty($sitanterior) || $sitanterior == "TRANSFERIDO FORA" ? "MATRICULADO" : $sitanterior;

 if ($concluida == "N") {

   $sSqlUpdate = " UPDATE matricula SET ";
   $sSqlUpdate .= " ed60_c_situacao = '$sitanterior', ";
   $sSqlUpdate .= " ed60_d_datamodif = '".(empty($datamodifant) ? $datamodif : $datamodifant)."', ";
   $sSqlUpdate .= " ed60_d_datamodifant = null, ";
   $sSqlUpdate .= " ed60_d_datasaida = null ";
   $sSqlUpdate .= " WHERE ed60_i_codigo = $matriculaorig ";
   $rsUpdate = db_query($sSqlUpdate);

  //atualiza qtd de matriculas turma de origem
  $sExcluir = "ed229_i_matricula = $matriculaorig AND ed229_c_procedimento = 'TRANSFERÊNCIA PARA OUTRA ESCOLA'";
  $oDaoMatriculaMov->excluir(null, $sExcluir);

 }

 if ($concluida == "S") {

   if (trim($sitanterior) == "MATRICULADO") {

     $resfinal = ResultadoFinal($matriculaorig,$codigoaluno,$codturmaorig,$sitanterior,$concluida);
     db_msgbox($resfinal);
     $situacaoatual = $resfinal=="REPROVADO"?"REPETENTE":"APROVADO";

   } else {
     $situacaoatual = $sitanterior;
   }

 } else {
   $situacaoatual = $sitanterior;
 }
 $sSqlAlunoCurso  = " UPDATE alunocurso SET ";
 $sSqlAlunoCurso .= "                   ed56_i_escola   = $codescolaorig, ";
 $sSqlAlunoCurso .= "                   ed56_c_situacao = '$situacaoatual', ";
 $sSqlAlunoCurso .= "                   ed56_c_situacaoant = '$sitanterior' ";
 $sSqlAlunoCurso .= " WHERE ed56_i_codigo = $ed56_i_codigo ";
 $rsAlunoCurso    = db_query($sSqlAlunoCurso);

 $oDaoTransfEscolaFora->excluir($codigotransf);

 $sSqlUpHistorico  = " UPDATE historico SET ";
 $sSqlUpHistorico .= " ed61_i_escola = $codescolaorig ";
 $sSqlUpHistorico .= " WHERE ed61_i_aluno = $codigoaluno";
 $rsUpHistorico    = db_query($sSqlUpHistorico);

 $sSqlUpTransf  = " UPDATE transfescolafora SET ";
 $sSqlUpTransf .= "                         ed104_c_situacao = 'A' ";
 $sSqlUpTransf .= " WHERE ed104_i_codigo = (select ed104_i_codigo from ";
 $sSqlUpTransf .= "                                         transfescolafora where ed104_i_matricula = $matriculaorig)";
 $rsTransf      = db_query($sSqlUpTransf);

 $GLOBALS['HTTP_POST_VARS']['ed248_i_motivo'] = '';
 $sDescrOrigem                      = "Matrícula n°: $matriculaorig\nTurma: $descrturmaorig\nEscola: $descrescolaorig";
 $sObs                              = "Cancelamento de TRANSFERÊNCIA FORA( Escola Origem: ".$descrescolaorig;
 $sObs                             .= "Escola Destino: ".$descrescoladest.")";
 $oDaoLogMatricula->ed248_i_usuario = db_getsession("DB_id_usuario");
 $oDaoLogMatricula->ed248_i_motivo  = null;
 $oDaoLogMatricula->ed248_i_aluno   = $codigoaluno;
 $oDaoLogMatricula->ed248_t_origem  = $sDescrOrigem;
 $oDaoLogMatricula->ed248_t_obs     = $sObs;
 $oDaoLogMatricula->ed248_d_data    = date("Y-m-d",db_getsession("DB_datausu"));
 $oDaoLogMatricula->ed248_c_hora    = date("H:i");
 $oDaoLogMatricula->ed248_c_tipo    = "T";
 $oDaoLogMatricula->incluir(null);

 //db_query("rollback");
 db_fim_transacao();
 db_msgbox("Cancelamento efetuado com sucesso!");
 db_redireciona("edu1_transfescolafora003.php");
 exit;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post">
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td valign="top">
   <br>
   <fieldset style="width:95%"><legend><b>Cancelar Transferência para Outras Escolas</b></legend>
   <table border="0" align="left">
    </tr>
     <td colspan="2">
      <?
        $sCampos                  = " ed104_i_codigo,ed47_i_codigo,ed47_v_nome,ed104_d_data,ed82_c_nome as escoladestino";
        $sWhere                   = " ed104_i_escolaorigem = $iEscola AND ed56_i_escola = $iEscola ";
        $sWhere                  .= " AND ed56_c_situacao = 'TRANSFERIDO FORA' and ed104_c_situacao= 'A'";
        $sWhere                  .= " and exists(select 1 ";
        $sWhere                  .= "              from regencia ";
        $sWhere                  .= "             where ed59_i_turma     = ed60_i_turma ";
        $sWhere                  .= "               and ed59_c_encerrada = 'N'";
        $sWhere                  .= ")";
        $sSqlTransfEscolaFora     = $oDaoTransfEscolaFora->sql_query_alunotransf("", $sCampos, "ed47_v_nome", $sWhere);
        $rsResultTransfEscolaFora = $oDaoTransfEscolaFora->sql_record($sSqlTransfEscolaFora);
        $iLinhasTransfFora        = $oDaoTransfEscolaFora->numrows;
      ?>
      <b>Alunos Transferidos:</b>
      <select name="aluno" style="font-size:9px;" onchange="js_pesquisa(this.value);">
       <?
       if ($iLinhasTransfFora == 0) {
         echo "<option value=''>Nenhum registro de transferência em aberto.</option>";
       } else {

         echo "<option value=''></option>";
         for ($iCont = 0; $iCont < $iLinhasTransfFora; $iCont++) {

           db_fieldsmemory($rsResultTransfEscolaFora, $iCont);
           echo "<option value='$ed104_i_codigo' ".($ed104_i_codigo==@$aluno?"selected":"").
                 ">".db_formatar($ed104_d_data,'d').
                 " ->".$ed47_i_codigo." - ".$ed47_v_nome."( Destino: ".
                 $escoladestino.")</option>";

         }//fecha o for

       }//fecha o else
       ?>
      </select>
     </td>
    </tr>
    <?
      if (isset($aluno)) {

      	$sCamposTransfFora        = " transfescolafora.ed104_d_data,transfescolafora.ed104_t_obs,";
      	$sCamposTransfFora       .= " transfescolafora.ed104_i_codigo,aluno.ed47_i_codigo as codigoaluno, ";
        $sCamposTransfFora       .= " escolaproc.ed82_c_nome as descrescoladest,matricula.ed60_i_turma as cod_turma ";
        $sWhere                   = "ed104_i_codigo = $aluno";
        $sSqlTransfEscolaFora     = $oDaoTransfEscolaFora->sql_query("", $sCamposTransfFora, "", $sWhere);
        $rsResultTransfEscolaFora = $oDaoTransfEscolaFora->sql_record($sSqlTransfEscolaFora);

        db_fieldsmemory($rsResultTransfEscolaFora, 0);

        $sCamposMat  = " turma.ed57_i_codigo as codturmaorig,turma.ed57_c_descr as descrturmaorig,";
        $sCamposMat .= " serie.ed11_i_codigo as codserieorig,base.ed31_i_codigo as codbaseorig, ";
        $sCamposMat .= " serie.ed11_c_descr ||' - '||ensino.ed10_c_abrev as descrserieorig,";
        $sCamposMat .= " calendario.ed52_i_codigo as codcalendarioorig,escola.ed18_i_codigo as codescolaorig, ";
        $sCamposMat .= " escola.ed18_c_nome as descrescolaorig,turno.ed15_i_codigo as codturnoorig,";
        $sCamposMat .= " ed60_i_codigo as matriculaorig,ed60_c_situacao as situacaoorig, ";
        $sCamposMat .= " ed60_c_concluida as conclusaoorig,cursoedu.ed29_i_codigo as codcursoorig";
        $sWhereMat   = " ed60_i_aluno = $codigoaluno ";
        $sWhereMat  .= " AND matricula.ed60_i_turma = $cod_turma AND ed60_c_ativa = 'S'";
        $sSqlMat     = $oDaoMatricula->sql_query("", $sCamposMat, "ed60_i_codigo desc", $sWhereMat);
        $rsResultMat = $oDaoMatricula->sql_record($sSqlMat);

        if ($oDaoMatricula->numrows > 0) {

          db_fieldsmemory($rsResultMat, 0);
          $conclusaoorig = $conclusaoorig == "S" ? "SIM" : "NAO";
        }

       ?>
    <tr>
     <td valign="top">
       <fieldset style="width:95%;"><legend><b>Dados de Origem</b></legend>
        <table>
         <tr>
          <td>
           <b>Escola:</b>
          </td>
          <td>
           <?db_input('descrescolaorig', 40, $Ied18_c_nome, true, 'text', 3, '')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Matrícula:</b>
          </td>
          <td>
           <?db_input('codescolaorig', 15, $Ied18_i_codigo, true, 'hidden', 3, '')?>
           <?db_input('codbaseorig', 15, $Ied31_i_codigo, true, 'hidden', 3, '')?>
           <?db_input('codcalendarioorig', 15, $Ied52_i_codigo, true, 'hidden', 3, '')?>
           <?db_input('codturnoorig', 15, $Ied15_i_codigo, true, 'hidden', 3, '')?>
           <?db_input('codserieorig', 15, $Ied11_i_codigo, true, 'hidden', 3, '')?>
           <?db_input('codcursoorig', 15, $Ied29_i_codigo, true, 'hidden', 3, '')?>
           <?db_input('matriculaorig', 15, $Ied60_i_codigo, true, 'text', 3, '')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Situação:</b>
          </td>
          <td>
           <?db_input('situacaoorig', 40, $Ied60_c_situacao, true, 'text', 3, '')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Concluida:</b>
          </td>
          <td>
           <?db_input('conclusaoorig', 40, $Ied60_c_concluida, true, 'text', 3, '')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Etapa:</b>
          </td>
          <td>
           <?db_input('codserieorig', 15, $Ied11_i_codigo, true, 'hidden', 3, '')?>
           <?db_input('descrserieorig', 40, $Ied11_c_descr, true, 'text', 3, '')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Turma:</b>
          </td>
          <td>
           <?db_input('codturmaorig', 15, $Ied57_i_codigo, true, 'hidden', 3, '')?>
           <?db_input('descrturmaorig', 40, $Ied57_c_descr, true, 'text', 3, '')?>
          </td>
         </tr>
        </table>
       </fieldset>
      </td>
      <td valign="top">
       <fieldset style="width:95%;"><legend><b>Dados de Destino</b></legend>
        <table>
         <tr>
          <td>
           <b>Escola:</b>
          </td>
          <td>
           <?db_input('descrescoladest', 40, $Ied18_c_nome, true, 'text', 3, '')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Transferido em:</b>
          </td>
          <td>
           <?db_inputdata('ed104_d_data', @$ed104_d_data_dia,
                          @$ed104_d_data_mes, @$ed104_d_data_ano, true, 'text', 3, ""
                         )
           ?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Observações:</b>
          </td>
          <td>
           <?db_textarea('ed104_t_obs', 4, 40, $Ied104_t_obs, true, 'text' , 3, "")?>
          </td>
         </tr>
        </table>
       </fieldset>
     </td>
    </tr>
    <tr>
     <td colspan="2">
      <?
      $negado = false;
      $sWhere             = "ed56_i_aluno = ".$codigoaluno;
      $sWhere            .= " AND ed56_i_escola = ".$iEscola."AND ed56_c_situacao = 'TRANSFERIDO FORA'";
      $sSqlAlunoCurso     = $oDaoAlunoCurso->sql_query_alunotransf("", "ed56_i_codigo as nada", "", $sWhere);
      $rsResultAlunoCurso = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);

      if ($oDaoAlunoCurso->numrows == 0 ) {

        $negado           = true;
        $sCampos          = "ed18_c_nome,ed56_c_situacao,ed11_c_descr";
        $sWhereAlunoCurso = "ed56_i_aluno = ".$codigoaluno;
        $sSqlAluno        = $oDaoAlunoCurso->sql_query_alunotransf("", $sCampos, "", $sWhereAlunoCurso);
        $rsResultAluno    = $oDaoAlunoCurso->sql_record($sSqlAluno);

        //if ($oDaoAlunoCurso->numrows > 0) {
          $oDadosAluno = db_utils::fieldsmemory($rsResultAluno, 0);
        //}

        echo "ATENÇÃO! Transferência já foi concretizada no destino. Cancelamento da transferência não permitido.<br>
              Situação atual do aluno:<br>
              Escola: $oDadosAluno->ed18_c_nome<br>
              Situação: $oDadosAluno->ed56_c_situacao<br>
              Etapa: $oDadosAluno->ed11_c_descr<br>
             ";

      }

      if ($conclusaoorig == "SIM" && $situacaoorig == "TRANSFERIDO FORA") {

       $negado = true;
       $sMsg   = " <b>ATENÇÃO! Matrícula com situação de TRANSFERIDO FORA já está concluída na turma de origem. ";
       $sMsg  .= " Cancelamento da transferência não permitido.</b><br><br>";
       echo $sMsg;

      }
      ?>
      <input type="hidden" name="codigotransf" value="<?=$ed104_i_codigo?>">
      <input type="hidden" name="codigoaluno" value="<?=$codigoaluno?>">
      <input type="submit" name="incluir" value="Confirmar Cancelamento"
             onclick="return js_confirma();" <?=$negado==true?"disabled":""?>>
     </td>
    </tr>
    <?}?>
   </table>
   </fieldset>
  </td>
 </tr>
</table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
function js_pesquisa() {

  if (document.form1.aluno.value == "") {

    location.href = 'edu1_transfescolafora003.php';
  } else {
    location.href = 'edu1_transfescolafora003.php?aluno='+document.form1.aluno.value;
  }

}

function js_confirma() {

  if (confirm('Confirmar cancelamento de transferência para este aluno?')) {

    document.form1.incluir.style.visibility = "hidden";
    return true;

  } else {
    return false;
  }

}
</script>