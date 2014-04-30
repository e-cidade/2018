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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_matricula_classe.php");
require_once("classes/db_matriculamov_classe.php");
require_once("classes/db_logmatricula_classe.php");
require_once("classes/db_turma_classe.php");
require_once("classes/db_turmaserieregimemat_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_jsplibwebseller.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$ed60_d_datamodif_dia = date("d",db_getsession("DB_datausu"));
$ed60_d_datamodif_mes = date("m",db_getsession("DB_datausu"));
$ed60_d_datamodif_ano = date("Y",db_getsession("DB_datausu"));
db_postmemory($HTTP_POST_VARS);
$clmatricula = new cl_matricula;
$clmatriculamov = new cl_matriculamov;
$cllogmatricula = new cl_logmatricula;
$clturma = new cl_turma;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$db_opcao = 22;
$db_botao = false;

if (isset($alterar)) {
  
  db_inicio_transacao();
  try {
    
    /**
     * Retorna todas as matriculas do aluno 
     */
    $sCamposMatriculaParaModificar  = "ed60_d_datamodif as datamodif, ed60_d_datamodifant as datamodifant, ";
    $sCamposMatriculaParaModificar .= "ed221_i_serie as etapaorigem, ed60_i_codigo, ed60_matricula";
    $sOrderMatriculaParaModificar   = "ed60_i_codigo desc";
    $sWhereMatriculaParaModificar   = "ed60_matricula = {$ed60_matricula} AND ed60_i_turma = {$ed60_i_turma}";
    $sSqlMatriculaParaModificar     = $clmatricula->sql_query("", 
                                                              $sCamposMatriculaParaModificar,
                                                              $sOrderMatriculaParaModificar,
                                                              $sWhereMatriculaParaModificar
                                                             );
    $rsMatriculaModificar          = $clmatricula->sql_record($sSqlMatriculaParaModificar);
    $oDadosMatricularParaModificar = db_utils::fieldsMemory($rsMatriculaModificar, 0);
    $db_opcao = 2;
    
    /**
     * Verifica a situacao selecionada foi 'MATRICULADO', atualizando as datas desta matricula
     */
    if (trim($ed60_c_situacao) == "MATRICULADO") {
     
      $clmatricula->ed60_d_datasaida    = "null";
      $clmatricula->ed60_d_datamodifant = null;
    
      if (isset($eliminamov)) {
       $clmatricula->ed60_d_datamodif = $oDadosMatricularParaModificar->datamodifant;
      }
      $clmatricula->ed60_d_datamodifant = null;
    
    } else {
     
      $clmatricula->ed60_d_datasaida    = $ed60_d_datamodif_ano."-".$ed60_d_datamodif_mes."-".$ed60_d_datamodif_dia;
      $clmatricula->ed60_d_datamodifant = $oDadosMatricularParaModificar->datamodif;
    } 
    
    $clmatricula->ed60_matricula = $oDadosMatricularParaModificar->ed60_matricula;
    $clmatricula->ed60_i_codigo  = $oDadosMatricularParaModificar->ed60_i_codigo;
    $clmatricula->alterar($oDadosMatricularParaModificar->ed60_i_codigo);
    if ($clmatricula->erro_status == 0) {

      $sMsgErro = "Erro ao salvar Dados da Matrícula do aluno.\\n{$clmatricula->erro_msg}";
      throw new Exception($sMsgErro);
    }
    
    if (trim($ed60_c_situacao) == "MATRICULADO") {
  
      /**
       * reativa a matriculado aluno.
       * devemos pesquisar todas as disciplinas que a turma possui, 
       * e reativamos os diarios de avaliação do aluno, caso ele possua algum.
       */
      $sSqlRegencias        = "SELECT ed59_i_codigo as regturma ";
      $sSqlRegencias       .= "  FROM regencia ";
      $sSqlRegencias       .= " WHERE ed59_i_turma = {$ed60_i_turma} ";
      $sSqlRegencias       .= "   AND ed59_i_serie = {$oDadosMatricularParaModificar->etapaorigem}";
      $rsRegencias          = db_query($sSqlRegencias);
      $iTotalLinhasRegencia = pg_num_rows($rsRegencias);
      for ($iRegencia = 0; $iRegencia < $iTotalLinhasRegencia; $iRegencia++) {
      
        db_fieldsmemory($rsRegencias, $iRegencia);
        $sSqlAtualizaDiarioClasse  = "UPDATE diario "; 
        $sSqlAtualizaDiarioClasse .= "   SET ed95_c_encerrado = 'N' ";
        $sSqlAtualizaDiarioClasse .= "  WHERE ed95_i_aluno    = {$ed60_i_aluno} ";
        $sSqlAtualizaDiarioClasse .= "    AND ed95_i_regencia = {$regturma} ";
        $rsAtualizaDiarioClasse    = db_query($sSqlAtualizaDiarioClasse);
        if (!$rsAtualizaDiarioClasse) {
          throw new Exception("Erro ao alterar situação do diário de avaliação do aluno"); 
        }
      }
      $clmatriculamov->ed229_t_descr  = "REATIVAÇÃO DA MATRÍCULA. SITUAÇÃO DA MATRÍCULA MODIFICADA DE ";
      $clmatriculamov->ed229_t_descr .= trim($ed60_c_situacaoatual)." PARA ".trim($ed60_c_situacao);
    } else if (trim($ed60_c_situacao) == "MATRICULA INDEVIDA") {

      if ($ed57_novaturma != "") {
  
        trocaTurma($oDadosMatricularParaModificar->ed60_i_codigo, $ed57_novaturma, false, $ed60_matricula);
        LimpaResultadofinal($oDadosMatricularParaModificar->ed60_i_codigo);
        $clmatriculamov->ed229_t_descr = "SITUAÇÃO DA MATRÍCULA MODIFICADA DE ".trim($ed60_c_situacaoatual)." PARA ".trim($ed60_c_situacao);
      }
    } else {
     
      $clmatriculamov->ed229_t_descr = "SITUAÇÃO DA MATRÍCULA MODIFICADA DE ".trim($ed60_c_situacaoatual)." PARA ".trim($ed60_c_situacao);
      LimpaResultadofinal($oDadosMatricularParaModificar->ed60_i_codigo);
    }
    
    if (!isset($eliminamov)) {
     
      $ed229_i_codigo = "";
      $clmatriculamov->ed229_i_matricula = $oDadosMatricularParaModificar->ed60_i_codigo;
      $clmatriculamov->ed229_i_usuario = db_getsession("DB_id_usuario");
      $clmatriculamov->ed229_c_procedimento = "ALTERAR SITUAÇÂO DA MATRÍCULA";
      $clmatriculamov->ed229_d_dataevento = $ed60_d_datamodif_ano."-".$ed60_d_datamodif_mes."-".$ed60_d_datamodif_dia;
      $clmatriculamov->ed229_c_horaevento = date("H:i");
      if (trim($clmatriculamov->ed229_t_descr) == "") {
        $clmatriculamov->ed229_t_descr = ' ';
      }
      $clmatriculamov->ed229_d_data = date("Y-m-d",db_getsession("DB_datausu"));
      $clmatriculamov->incluir($ed229_i_codigo);
      if ($clmatriculamov->erro_status == 0) {
        throw new Exception("Erro ao incluir movimentação da matricula\\n{$clmatriculamov->erro_msg}");
      }
    } else {
  
      $sSqlRemoveHistoricoMatricula  = "DELETE FROM matriculamov ";
      $sSqlRemoveHistoricoMatricula .= " WHERE ed229_i_matricula = $oDadosMatricularParaModificar->ed60_i_codigo  ";
      $sSqlRemoveHistoricoMatricula .= "   AND ed229_c_procedimento = 'ALTERAR SITUAÇÂO DA MATRÍCULA'";
      $sSqlRemoveHistoricoMatricula .= "   AND ed229_t_descr like '%PARA ".trim($ed60_c_situacaoatual)."%'";
      $rsRemoveHistoricoMatricula    = db_query($sSqlRemoveHistoricoMatricula);
    
      if (!$rsRemoveHistoricoMatricula) {
        throw new Exception("Erro ao remover movimentações da matrícula");
      	
      }
    	$sSqlUpdateMatMov = " UPDATE matricula SET ed60_d_datasaida = null WHERE ed60_i_codigo = {$oDadosMatricularParaModificar->ed60_i_codigo}";
     	$rsUpdateMatMov   = db_query($sSqlUpdateMatMov);
      if (!$rsUpdateMatMov) {
        throw new Exception("Erro ao retornar matricula do aluno");      	
      }
      $sDescricaoOrigem  = "Matrícula n°: {$ed60_matricula}\nTurma: {$ed57_c_descr}\nEscola: ";
      $sDescricaoOrigem .= db_getsession("DB_nomedepto")."\nCalendário: {$ed52_c_descr}\n RETORNO em ";
      $sDescricaoOrigem .= "{$ed60_d_datamodif_dia}/{$ed60_d_datamodif_mes}/{$ed60_d_datamodif_ano}";
      $cllogmatricula->ed248_i_usuario = db_getsession("DB_id_usuario");
      $cllogmatricula->ed248_i_motivo  = null;
      $cllogmatricula->ed248_i_aluno   = $ed60_i_aluno;
      $cllogmatricula->ed248_t_origem  = $sDescricaoOrigem;
      $cllogmatricula->ed248_t_obs     = "";
      $cllogmatricula->ed248_d_data    = date("Y-m-d",db_getsession("DB_datausu"));
      $cllogmatricula->ed248_c_hora    = date("H:i");
      $cllogmatricula->ed248_c_tipo    = "R";
      $cllogmatricula->incluir(null);
      if ($cllogmatricula->erro_status == 0) {
         throw new Exception("Erro ao incluir dados do log da matrícula.\\n{$cllogmatricula->erro_msg}");         
      }
    }
     
    $sSqAtualizaCursoAluno  = "UPDATE alunocurso SET ";
    $sSqAtualizaCursoAluno .= "       ed56_c_situacao = '{$ed60_c_situacao}' ";
    $sSqAtualizaCursoAluno .= " WHERE ed56_i_aluno    = {$ed60_i_aluno}";
    if ($ed60_c_situacao == "MATRICULA INDEVIDA") {
     
      $sSqAtualizaCursoAluno  = "UPDATE alunocurso SET ";
      $sSqAtualizaCursoAluno .= "       ed56_c_situacao = 'MATRICULADO' ";
      $sSqAtualizaCursoAluno .= " WHERE ed56_i_aluno    = {$ed60_i_aluno}";
     
    }
    $rsAtualizaAlunoCurso     = db_query($sSqAtualizaCursoAluno);
    if (!$rsAtualizaAlunoCurso) {
      throw new Exception("Erro ao atualizar situação do curso do aluno.");
    }
    $sSqlTotalMatriculasTurma = $clmatricula->sql_query_file("", 
                                                            "count(*) as qtdmatricula", 
                                                            "",
                                                            "    ed60_i_turma = {$ed60_i_turma}
                                                             AND ed60_c_situacao = 'MATRICULADO'"
                                                           );
    $result_qtd               = $clmatricula->sql_record($sSqlTotalMatriculasTurma);
    db_fieldsmemory($result_qtd,0);
    $qtdmatricula = $qtdmatricula==""?0:$qtdmatricula;
    $sSqlAtualizaTotalTurma  = "UPDATE turma SET ";
    $sSqlAtualizaTotalTurma .= "       ed57_i_nummatr = {$qtdmatricula} ";
    $sSqlAtualizaTotalTurma .= " WHERE ed57_i_codigo  = {$ed60_i_turma}  ";
    $rsAtualizaTotalTurma    = db_query($sSqlAtualizaTotalTurma);
    if (!$rsAtualizaTotalTurma) {
      throw new Exception("Erro Ao Alterar quantidade de alunos matriculados na turma");
    }
    db_fim_transacao(false);
  } catch (Exception $eErro) {

   db_msgbox($eErro->getMessage());
   db_fim_transacao(true);
  }

  db_redireciona("edu1_matricula002.php?chavepesquisa=$ed60_i_turma");
  exit;
} else if (isset($chavepesquisa)) {
  
  $db_opcao = 2;
  $db_botao = false;
  $camp = "turma.*,
           calendario.*,
           base.*,
           cursoedu.*,
           turno.*,
           fc_nomeetapaturma(ed57_i_codigo) as nometapa,
           fc_codetapaturma(ed57_i_codigo) as codetapa
          ";
  $result = $clturma->sql_record($clturma->sql_query("",$camp,""," ed57_i_codigo = $chavepesquisa"));
  db_fieldsmemory($result,0);
  $ed60_i_turma = $ed57_i_codigo;
  $result1 = $clmatricula->sql_record($clmatricula->sql_query_file(""," count(*) ",""," ed60_i_turma = $ed60_i_turma AND ed60_c_situacao = 'MATRICULADO'"));
  db_fieldsmemory($result1,0);
  $ed57_i_nummatr = $count;
  ?>
  <script>
   parent.document.formaba.a2.disabled    = false;
   parent.document.formaba.a2.style.color = "black";
   top.corpo.iframe_a2.location.href='edu1_alunoturma001.php?ed60_i_turma=<?=$ed57_i_codigo?>&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
  </script>
 <?
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"), "escola");?>
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Alterar Situação da Matrícula</b></legend>
    <?include("forms/db_frmmatricula.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
 if($clmatricula->erro_status=="0"){
  $clmatricula->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clmatricula->erro_campo!=""){
   echo "<script> document.form1.".$clmatricula->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clmatricula->erro_campo.".focus();</script>";
  }
 }
}
if($db_opcao==22){
 echo "<script>js_pesquisaed60_i_turma();</script>";
}
?>