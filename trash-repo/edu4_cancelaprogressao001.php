<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("classes/db_trocaserie_classe.php");
include("classes/db_matricula_classe.php");
include("classes/db_turmaserieregimemat_classe.php");
include("classes/db_matriculaserie_classe.php");
include("classes/db_amparo_classe.php");
include("classes/db_diariofinal_classe.php");
include("classes/db_parecerresult_classe.php");
include("classes/db_diarioresultado_classe.php");
include("classes/db_pareceraval_classe.php");
include("classes/db_abonofalta_classe.php");
include("classes/db_diarioavaliacao_classe.php");
include("classes/db_aprovconselho_classe.php");
include("classes/db_diario_classe.php");
include("classes/db_matriculamov_classe.php");
include("classes/db_alunotransfturma_classe.php");
include("classes/db_transfescolarede_classe.php");
include("classes/db_transfescolafora_classe.php");
include("classes/db_logmatricula_classe.php");
$escola = db_getsession("DB_coddepto");
$cltrocaserie       = new cl_trocaserie;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$clmatricula        = new cl_matricula;
$clmatriculaserie   = new cl_matriculaserie;
$clamparo           = new cl_amparo;
$cldiariofinal      = new cl_diariofinal;
$clparecerresult    = new cl_parecerresult;
$cldiarioresultado  = new cl_diarioresultado;
$clpareceraval      = new cl_pareceraval;
$clabonofalta       = new cl_abonofalta;
$cldiarioavaliacao  = new cl_diarioavaliacao;
$claprovconselho    = new cl_aprovconselho;
$cldiario           = new cl_diario;
$clmatriculamov     = new cl_matriculamov;
$clalunotransfturma = new cl_alunotransfturma;
$cltransfescolarede = new cl_transfescolarede;
$cltransfescolafora = new cl_transfescolafora;
$cllogmatricula     = new cl_logmatricula;
if(isset($incluir)){
 //pg_query("begin");
 db_inicio_transacao();
 $sql_exc = "SELECT DISTINCT ed95_i_codigo as coddiario
             FROM diarioavaliacao
              inner join diario on ed95_i_codigo = ed72_i_diario
             WHERE ed95_i_aluno = $codigoaluno
             AND ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $codturmadest AND ed59_i_serie = $codseriedest)
            ";
 $result_exc = pg_query($sql_exc);
 $linhas_exc = pg_num_rows($result_exc);
 for($z=0;$z<$linhas_exc;$z++){
  db_fieldsmemory($result_exc,$z);
  $clamparo->excluir(""," ed81_i_diario = $coddiario");
  $cldiariofinal->excluir(""," ed74_i_diario = $coddiario");
  $result5 = pg_query("select ed73_i_codigo from diarioresultado where ed73_i_diario = $coddiario");
  $clparecerresult->excluir(""," ed63_i_diarioresultado in (select ed73_i_codigo from diarioresultado where ed73_i_diario = $coddiario)");
  $cldiarioresultado->excluir(""," ed73_i_diario = $coddiario");
  $clpareceraval->excluir(""," ed93_i_diarioavaliacao in (select ed72_i_codigo from diarioavaliacao where ed72_i_diario = $coddiario)");
  $clabonofalta->excluir(""," ed80_i_diarioavaliacao in (select ed72_i_codigo from diarioavaliacao where ed72_i_diario = $coddiario)");
  $cldiarioavaliacao->excluir(""," ed72_i_diario = $coddiario");
  $claprovconselho->excluir(""," ed253_i_diario = $coddiario");
  $cldiario->excluir(""," ed95_i_codigo = $coddiario");
 }
 $clmatriculamov->excluir(""," ed229_i_matricula = $matriculadest ");
 $clalunotransfturma->excluir("","ed69_i_matricula  = $matriculadest ");
 $cltransfescolarede->excluir("","ed103_i_matricula  = $matriculadest ");
 $cltransfescolafora->excluir("","ed104_i_matricula  = $matriculadest ");
 $clmatriculaserie->excluir("","ed221_i_matricula  = $matriculadest ");
 $clmatricula->excluir($matriculadest);
 $result_seqant = $clmatricula->sql_record($clmatricula->sql_query("","ed221_i_serie as serieant,ed11_i_sequencia as seqant",""," ed60_i_codigo = $matriculaorig"));
 db_fieldsmemory($result_seqant,0);
 $sql1 = "SELECT ed56_i_codigo FROM alunocurso
          WHERE ed56_i_aluno = $codigoaluno
         ";
 $query1 = pg_query($sql1);
 $linhas1 = pg_num_rows($query1);
 if($linhas1>0){
  db_fieldsmemory($query1,0);
  $sql1 = "UPDATE alunocurso SET
            ed56_i_escola = $codescolaorig,
            ed56_i_base = $codbaseorig,
            ed56_i_calendario = $codcalendarioorig,
            ed56_c_situacao = 'MATRICULADO',
            ed56_i_baseant = null,
            ed56_i_calendarioant = null,
            ed56_c_situacaoant = ''
           WHERE ed56_i_codigo = $ed56_i_codigo
          ";
  $result1 = pg_query($sql1);
  $sql1 = "UPDATE alunopossib SET
            ed79_i_serie = $serieant,
            ed79_i_turno = $codturnoorig,
            ed79_i_turmaant = null,
            ed79_c_resulant = '',
            ed79_c_situacao = 'A'
           WHERE ed79_i_alunocurso = $ed56_i_codigo
          ";
  $result1 = pg_query($sql1);
 }
 $result_qtd = $clmatricula->sql_record($clmatricula->sql_query_file(""," count(*) as qtdmatricula",""," ed60_i_turma = $codturmaorig AND ed60_c_situacao = 'MATRICULADO'"));
 db_fieldsmemory($result_qtd,0);
 $qtdmatricula = $qtdmatricula==""?0:$qtdmatricula;
 $sql1 = "UPDATE turma SET
           ed57_i_nummatr = $qtdmatricula
          WHERE ed57_i_codigo = $codturmaorig
          ";
 $query1 = pg_query($sql1);
 $result_qtd = $clmatricula->sql_record($clmatricula->sql_query_file(""," count(*) as qtdmatricula",""," ed60_i_turma = $codturmadest AND ed60_c_situacao = 'MATRICULADO'"));
 db_fieldsmemory($result_qtd,0);
 $qtdmatricula = $qtdmatricula==""?0:$qtdmatricula;
 $sql1 = "UPDATE turma SET
           ed57_i_nummatr = $qtdmatricula
          WHERE ed57_i_codigo = $codturmadest
          ";
 $query1 = pg_query($sql1);
 $result_modif = $clmatricula->sql_record($clmatricula->sql_query("","ed60_d_datamodifant as datamodifant,turma.ed57_i_tipoturma",""," ed60_i_codigo = $matriculaorig"));
 db_fieldsmemory($result_modif,0);
 $sql1 = "UPDATE matricula SET
           ed60_c_situacao = 'MATRICULADO',
           ed60_c_concluida = 'N',
           ed60_t_obs = '',
           ed60_d_datamodif = '$datamodifant',
           ed60_d_datamodifant = null,
           ed60_d_datasaida = null
          WHERE ed60_i_codigo = $matriculaorig
          ";
 $query1 = pg_query($sql1);
 $sql1 = "DELETE FROM matriculamov
          WHERE ed229_i_matricula = $matriculaorig
          AND ed229_c_procedimento like 'PROGRESS%'
          ";
 $query1 = pg_query($sql1);
 $sql1 = "DELETE FROM trocaserie
          WHERE ed101_i_codigo = $codigoprogressao
          ";
 $query1 = pg_query($sql1);
 $sql1 = "UPDATE diario SET
           ed95_c_encerrado = 'N'
          WHERE ed95_i_aluno = $codigoaluno
          AND ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $codturmaorig AND ed59_i_serie = $codserieorig)
         ";
 $result1 = pg_query($sql1);
 $sql1 = "UPDATE historico SET
           ed61_t_obs = ''
          WHERE ed61_i_aluno = $codigoaluno
          AND ed61_i_curso = $codcursoorig
         ";
 $result1 = pg_query($sql1);
 if($ed57_i_tipoturma==2){
  $condicao = " AND ed11_i_sequencia >= $seqant";
 }else{
  $condicao = " AND ed11_i_sequencia = $seqant";
 }
 $result_etpant = $clturmaserieregimemat->sql_record($clturmaserieregimemat->sql_query("","ed223_i_serie","ed223_i_ordenacao"," ed220_i_turma = $codturmaorig $condicao"));
 for($r=0;$r<$clturmaserieregimemat->numrows;$r++){
  db_fieldsmemory($result_etpant,$r);
  $sql1 = "DELETE FROM histmpsdisc
           WHERE ed65_i_codigo in (select ed65_i_codigo
                                   from histmpsdisc
                                    inner join historicomps on ed62_i_codigo = ed65_i_historicomps
                                    inner join historico on ed61_i_codigo = ed62_i_historico
                                   where ed62_i_serie = $ed223_i_serie
                                   and ed62_i_escola = $codescolaorig
                                   and ed61_i_aluno = $codigoaluno
                                   and ed61_i_curso = $codcursoorig)";
  $result1 = pg_query($sql1);
  $sql1 = "DELETE FROM historicomps
           WHERE ed62_i_codigo in (select ed62_i_codigo
                                   from historicomps
                                    inner join historico on ed61_i_codigo = ed62_i_historico
                                   where ed62_i_serie = $ed223_i_serie
                                   and ed62_i_escola = $codescolaorig
                                   and ed61_i_aluno = $codigoaluno
                                   and ed61_i_curso = $codcursoorig)";
  $result1 = pg_query($sql1);
 }
 $descr_origem = "Matrícula n°: $matriculadest\nTurma: $descrturmadest\nEscola: ".db_getsession("DB_nomedepto")."\nCalendário: $descrcalendariodest";
 $cllogmatricula->ed248_i_usuario = db_getsession("DB_id_usuario");
 $cllogmatricula->ed248_i_motivo  = null;
 $cllogmatricula->ed248_i_aluno   = $codigoaluno;
 $cllogmatricula->ed248_t_origem  = $descr_origem;
 $cllogmatricula->ed248_t_obs     = "Cancelamento de Progressão ( Aluno retornado da turma $descrturmadest para a turma $descrturmaorig )";
 $cllogmatricula->ed248_d_data    = date("Y-m-d",db_getsession("DB_datausu"));
 $cllogmatricula->ed248_c_hora    = date("H:i");
 $cllogmatricula->ed248_c_tipo    = "E";
 $cllogmatricula->incluir(null);
 //pg_query("rollback");
 db_fim_transacao();
 db_msgbox("Cancelamento efetuado com sucesso!");
 db_redireciona("edu4_cancelaprogressao001.php");
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
   <fieldset style="width:95%"><legend><b>Cancelar Progressão de Aluno</b></legend>
   <table border="0" align="left">
    </tr>
     <td>
      <?
      $result = $cltrocaserie->sql_record($cltrocaserie->sql_query("","ed101_i_codigo,ed101_d_data,ed101_c_tipo,ed47_i_codigo,ed47_v_nome",""," turmaorig.ed57_i_escola = $escola"));
      ?>
      <b>Alunos Progredidos:</b>
      <select name="aluno" style="font-size:9px;" onchange="js_pesquisa(this.value);">
       <?
       if($cltrocaserie->numrows==0){
        echo "<option value=''>Nenhum registro de progressão</option>";
       }else{
        echo "<option value=''></option>";
        for($x=0;$x<$cltrocaserie->numrows;$x++){
         db_fieldsmemory($result,$x);
         echo "<option value='$ed101_i_codigo' ".($ed101_i_codigo==@$aluno?"selected":"").">$ed47_i_codigo - $ed47_v_nome ( ".($ed101_c_tipo=="A"?"AVANÇADO":"CLASSIFICADO")." em ".db_formatar($ed101_d_data,'d')." )</option>";
        }
       }
       ?>
      </select>
     </td>
    </tr>
    <?if(isset($aluno)){
    $campos = "turmaorig.ed57_i_codigo as codturmaorig,
               turmaorig.ed57_c_descr as descrturmaorig,
               baseorig.ed31_i_codigo as codbaseorig,
               calendarioorig.ed52_i_codigo as codcalendarioorig,
               escolaorig.ed18_i_codigo as codescolaorig,
               turnoorig.ed15_i_codigo as codturnoorig,
               aluno.ed47_i_codigo as codigoaluno,
               turmadest.ed57_i_codigo as codturmadest,
               turmadest.ed57_c_descr as descrturmadest,
               basedest.ed31_i_codigo as codbasedest,
               calendariodest.ed52_i_codigo as codcalendariodest,
               calendariodest.ed52_c_descr as descrcalendariodest,
               escoladest.ed18_i_codigo as codescoladest,
               turnodest.ed15_i_codigo as codturnodest,
               trocaserie.ed101_d_data,
               trocaserie.ed101_c_tipo,
               trocaserie.ed101_t_obs,
               trocaserie.ed101_i_codigo
              ";
    $result1 = $cltrocaserie->sql_record($cltrocaserie->sql_query("",$campos,""," ed101_i_codigo = $aluno"));
    db_fieldsmemory($result1,0);
    $result2 = $clmatricula->sql_record($clmatricula->sql_query("","ed60_i_codigo as matriculaorig,ed60_c_situacao as situacaoorig,ed60_c_concluida as conclusaoorig,cursoedu.ed29_i_codigo as codcursoorig,ed11_c_descr as descrserieorig,ed221_i_serie as codserieorig",""," ed60_i_aluno = $codigoaluno AND ed60_i_turma = $codturmaorig AND (ed60_c_situacao = 'AVANÇADO' OR ed60_c_situacao = 'CLASSIFICADO')"));
    if($clmatricula->numrows>0){
     db_fieldsmemory($result2,0);
     $conclusaoorig = $conclusaoorig=="S"?"SIM":"NAO";
    }
    $result3 = $clmatricula->sql_record($clmatricula->sql_query("","ed60_i_codigo as matriculadest,ed60_c_situacao as situacaodest,ed60_c_concluida as conclusaodest,ed11_c_descr as descrseriedest,ed221_i_serie as codseriedest",""," ed60_i_aluno = $codigoaluno AND ed60_i_turma = $codturmadest AND ed60_c_situacao = 'MATRICULADO'"));
    if($clmatricula->numrows>0){
     db_fieldsmemory($result3,0);
     $conclusaodest = $conclusaodest=="S"?"SIM":"NAO";
    }
    ?>
    <tr>
     <td>
       <fieldset style="width:95%;"><legend><b>Dados de Origem</b></legend>
        <table>
         <tr>
          <td>
           <b>Matrícula:</b>
          </td>
          <td>
           <?db_input('codescolaorig',15,@$codescolaorig,true,'hidden',3,'')?>
           <?db_input('codbaseorig',15,@$codbaseorig,true,'hidden',3,'')?>
           <?db_input('codcalendarioorig',15,@$codcalendarioorig,true,'hidden',3,'')?>
           <?db_input('codturnoorig',15,@$codturnoorig,true,'hidden',3,'')?>
           <?db_input('codserieorig',15,@$codserieorig,true,'hidden',3,'')?>
           <?db_input('codcursoorig',15,@$codcursoorig,true,'hidden',3,'')?>
           <?db_input('matriculaorig',15,@$matriculaorig,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Situação:</b>
          </td>
          <td>
           <?db_input('situacaoorig',40,@$situacaoorig,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Concluida:</b>
          </td>
          <td>
           <?db_input('conclusaoorig',40,@$conclusaorig,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Etapa:</b>
          </td>
          <td>
           <?db_input('codserieorig',15,@$codserieorig,true,'hidden',3,'')?>
           <?db_input('descrserieorig',40,@$descrserieorig,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Turma:</b>
          </td>
          <td>
           <?db_input('codturmaorig',15,@$codturmaorig,true,'hidden',3,'')?>
           <?db_input('descrturmaorig',40,@$descrturmaorig,true,'text',3,'')?>
          </td>
         </tr>
        </table>
       </fieldset>
      </td>
      <td>
       <fieldset style="width:95%;"><legend><b>Dados de Destino</b></legend>
        <table>
         <tr>
          <td>
           <b>Matrícula:</b>
          </td>
          <td>
           <?db_input('codescoladest',15,@$codescoladest,true,'hidden',3,'')?>
           <?db_input('codbasedest',15,@$codbasedest,true,'hidden',3,'')?>
           <?db_input('codcalendariodest',15,@$codcalendariodest,true,'hidden',3,'')?>
           <?db_input('descrcalendariodest',20,@$descrcalendariodest,true,'hidden',3,'')?>
           <?db_input('codturnodest',15,@$codturnodest,true,'hidden',3,'')?>
           <?db_input('codseriedest',15,@$codseriedest,true,'hidden',3,'')?>
           <?db_input('matriculadest',15,@$matriculadest,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Situação:</b>
          </td>
          <td>
           <?db_input('situacaodest',40,@$situacaodest,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Concluida:</b>
          </td>
          <td>
           <?db_input('conclusaodest',40,@$conclusadest,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Etapa:</b>
          </td>
          <td>
           <?db_input('codseriedest',15,@$codseriedest,true,'hidden',3,'')?>
           <?db_input('descrseriedest',40,@$descrseriedest,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Turma:</b>
          </td>
          <td>
           <?db_input('codturmadest',15,@$codturmadest,true,'hidden',3,'')?>
           <?db_input('descrturmadest',40,@$descrturmadest,true,'text',3,'')?>
          </td>
         </tr>
        </table>
       </fieldset>
     </td>
    </tr>
    <tr>
     <td colspan="2">
      <b>
       <?=$ed101_c_tipo=="A"?"AVANÇADO":"CLASSIFICADO"?> em: <?=db_formatar($ed101_d_data,'d')?>
       Obs.: <?=$ed101_t_obs?>
      </b>
     </td>
    </tr>
    <tr>
     <td>
      <input type="hidden" name="codigoprogressao" value="<?=$ed101_i_codigo?>">
      <input type="hidden" name="codigoaluno" value="<?=$codigoaluno?>">
      <input type="submit" name="incluir" value="Confirmar Cancelamento" onclick="return js_confirma();">
      <script>
       sitorig = document.form1.situacaoorig.value.substr(0,4);
       sitdest = document.form1.situacaodest.value;
       if(sitdest!='MATRICULADO'){
        if(sitdest==""){
         alert("Aluno não está mais matriculado na turma de destino. Cancelamento de Progressão não permitido!");
        }else{
         alert("Aluno não está mais na situação de MATRICULADO na turma <?=$descrturmadest?>. Cancelamento de Progressão não permitido!");
        }
        document.form1.incluir.disabled = true;
       }else if(sitorig!='CLAS' && sitorig!='AVAN'){
        if(sitorig==""){
         alert("Aluno não está mais matriculado na turma de origem. Cancelamento de Progressão não permitido!");
        }else{
         alert("Aluno não está mais na situação de <?=$ed101_c_tipo=="A"?"AVANÇADO":"CLASSIFICADO"?> na turma <?=$descrturmaorig?>. Cancelamento de Progressão não permitido!");
        }
        //document.form1.incluir.disabled = true;
       }else if(document.form1.conclusaodest.value=='SIM'){
        alert("Aluno já possui matrícula concluída na turma <?=$descrturmadest?>. Cancelamento de Progressão não permitido!");
       }
      </script>
     </td>
    </tr>
    <?}?>
   </table>
   </fieldset>
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_pesquisa(){
 if(document.form1.aluno.value==""){
  location.href = 'edu4_cancelaprogressao001.php';
 }else{
  location.href = 'edu4_cancelaprogressao001.php?aluno='+document.form1.aluno.value;
 }
}
function js_confirma(){
 if(confirm('Confirmar cancelamento de progressão para este aluno?')){
  document.form1.incluir.style.visibility = "hidden";
  return true;
 }else{
  return false;
 }
}
</script>