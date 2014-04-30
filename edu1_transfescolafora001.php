<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_transfescolafora_classe.php");
require_once("classes/db_alunocurso_classe.php");
require_once("classes/db_matricula_classe.php");
require_once("classes/db_matriculamov_classe.php");
require_once("classes/db_escoladiretor_classe.php");
require_once("classes/db_obstransferencia_classe.php");
require_once("classes/db_regencia_classe.php");
require_once("libs/db_jsplibwebseller.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

$ed104_d_data_dia     = date("d", db_getsession("DB_datausu"));
$ed104_d_data_mes     = date("m", db_getsession("DB_datausu"));
$ed104_d_data_ano     = date("Y", db_getsession("DB_datausu"));
$oDaoTransfEscolaFora = db_utils::getdao('transfescolafora');
$oDaoAlunoCurso       = db_utils::getdao('alunocurso');
$oDaoMatricula        = db_utils::getdao('matricula');
$oDaoMatriculaMov     = db_utils::getdao('matriculamov');
$oDaoEscolaDiretor    = db_utils::getdao('escoladiretor');
$oDaoObsTransferencia = db_utils::getdao('obstransferencia');
$oDaoRegencia         = db_utils::getdao('regencia');
$oDaoDiario           = db_utils::getdao('diario');
$oDaoTurma            = db_utils::getdao('turma');
$db_opcao             = 1;
$db_botao             = true;
$ed104_i_escolaorigem = db_getsession("DB_coddepto");
$ed18_c_nome          = db_getsession("DB_nomedepto");
$iEscola              = db_getsession("DB_coddepto");

if (isset($incluir)) {
  
  try {

    db_inicio_transacao();
    $oDaoTransfEscolaFora->ed104_c_situacao  = "A";
    $oDaoTransfEscolaFora->ed104_i_usuario   = db_getsession("DB_id_usuario");
    $oDaoTransfEscolaFora->ed104_i_matricula = $matricula;
    $oDaoTransfEscolaFora->incluir($ed104_i_codigo);
  
    if ($oDaoTransfEscolaFora->erro_status == '0') {              
      throw new Exception("Erro na alteração na tabela Transfescolafora. Erro da classe: ".
                          $oDaoTransfEscolaFora->erro_msg
                         );                                       
    }//fecha o erro_status
   
    $sCampos       = "ed60_c_situacao,ed60_i_turma,ed60_i_aluno,turma.ed57_i_calendario,turma.ed57_i_escola,";
    $sCampos      .= "turma.ed57_i_base,turma.ed57_i_turno as turnoturma";
    $sSqlMatricula = $oDaoMatricula->sql_query("", $sCampos, "", " ed60_i_codigo = $matricula"); 
    $rsResult      = $oDaoMatricula->sql_record($sSqlMatricula);
  
    if ($oDaoMatricula->numrows > 0) {
      $oDados = db_utils::fieldsmemory($rsResult, 0);
    }
  
    $sWhereRegencia   = "ed59_i_turma =". $oDados->ed60_i_turma;
    $sSqlRegencia     = $oDaoRegencia->sql_query("", "ed59_i_codigo as regturma", "", $sWhereRegencia);    
    $rsResultRegencia = $oDaoRegencia->sql_record($sSqlRegencia);
    $iLinhasReg       = $oDaoRegencia->numrows;
  
    for ($iCont = 0; $iCont < $iLinhasReg; $iCont++) {

      $sWhereDiario   = " ed95_i_aluno = $oDados->ed60_i_aluno ";
      $sWhereDiario  .= " AND ed95_i_regencia = ".db_utils::fieldsmemory($rsResultRegencia,$iCont)->regturma;
      $sSqlDiario     = $oDaoDiario->sql_query("", "ed95_i_codigo", "", $sWhereDiario);
      $rsResultDiario = $oDaoDiario->sql_record($sSqlDiario); 
      $iLinhasDiario = $oDaoDiario->numrows; 
      if ($iLinhasDiario > 0) {
      
        $oDaoDiario->ed95_c_encerrado = 'S';
        $oDaoDiario->ed95_i_codigo    = db_utils::fieldsmemory($rsResultDiario, 0)->ed95_i_codigo;
        $oDaoDiario->alterar($oDaoDiario->ed95_i_codigo);
             
        if ($oDaoDiario->erro_status == '0') {              
          throw new Exception("Erro na alteração na tabela Diario. Erro da classe: ".$oDaoDiario->erro_msg);                                       
        }//fecha o erro_status
      
      }//fecha o if numrows
      
    }//fecha o for
   
    $sWhere             = "ed56_i_escola = $oDados->ed57_i_escola AND ed56_i_aluno = $oDados->ed60_i_aluno ";
    $sSqlAlunoCurso     = $oDaoAlunoCurso->sql_query("", "ed56_i_codigo", "", $sWhere); 
    $rsResultAlunoCurso = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);
  
    if ($oDaoAlunoCurso->numrows > 0) {
         
      $oDaoAlunoCurso->ed56_c_situacao    = "TRANSFERIDO FORA";
      $oDaoAlunoCurso->ed56_c_situacaoant = $oDados->ed60_c_situacao;
      $oDaoAlunoCurso->ed56_i_codigo      = db_utils::fieldsmemory($rsResultAlunoCurso, 0)->ed56_i_codigo;   
      $oDaoAlunoCurso->alterar($oDaoAlunoCurso->ed56_i_codigo);
   
      if ($oDaoAlunoCurso->erro_status == '0') {              
        throw new Exception("Erro na alteração na tabela Alunocurso. Erro da classe: ".$oDaoAlunoCurso->erro_msg);                                       
      }//fecha o erro_status
       
    }//fecha o if $oDaoAlunoCurso->numrows > 0  
    
    if ($concluida == "N") {
    
      $oDaoMatricula->ed60_i_codigo       = $matricula;
      $oDaoMatricula->ed60_c_situacao     = "TRANSFERIDO FORA";
      $oDaoMatricula->ed60_d_datamodif    = substr($ed104_d_data,6,4)."-".substr($ed104_d_data,3,2)."-".
                                            substr($ed104_d_data,0,2);
      $oDaoMatricula->ed60_d_datasaida    = substr($ed104_d_data,6,4)."-".substr($ed104_d_data,3,2)."-".
                                            substr($ed104_d_data,0,2);
      $oDaoMatricula->ed60_d_datamodifant = substr($datamodif,6,4)."-".substr($datamodif,3,2)."-".substr($datamodif,0,2);
      $oDaoMatricula->alterar($matricula);
      
      if ($oDaoMatricula->erro_status == '0') {              
        throw new Exception("Erro na alteração na tabela Matricula. Erro da classe: ".$oDaoMatricula->erro_msg);                                       
      }//fecha o erro_status
    
      $oDaoMatriculaMov->ed229_i_matricula    = $matricula;
      $oDaoMatriculaMov->ed229_i_usuario      = db_getsession("DB_id_usuario");
      $oDaoMatriculaMov->ed229_c_procedimento = "TRANSFERÊNCIA PARA OUTRA ESCOLA";
      $oDaoMatriculaMov->ed229_t_descr        = "ALUNO DA TURMA ".trim($turma)." TRANSFERIDO PARA ESCOLA ".
                                                trim($ed82_c_nome)."";
      $oDaoMatriculaMov->ed229_d_dataevento   = substr($ed104_d_data,6,4)."-".substr($ed104_d_data,3,2)."-".
                                                substr($ed104_d_data,0,2);
      $oDaoMatriculaMov->ed229_c_horaevento   = date("H:i");
      $oDaoMatriculaMov->ed229_d_data         = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoMatriculaMov->incluir(null);  
    
      if ($oDaoMatriculaMov->erro_status == '0') {              
        throw new Exception("Erro na alteração na tabela Matriculamov. Erro da classe: ".$oDaoMatriculaMov->erro_msg);                                       
      }//fecha o erro_status
    
     //atualiza qtd de matriculas turma de origem
      $sWhereMatri  = " ed60_i_turma = $oDados->ed60_i_turma AND ed60_c_situacao = 'MATRICULADO'";
      $sSqlMat      = $oDaoMatricula->sql_query_file("", "count(*) as qtdmatricula", "", $sWhereMatri); 
      $rsResultMat  = $oDaoMatricula->sql_record($sSqlMat);
    
      if ($oDaoMatricula->numrows > 0) {
      
        $qtdmatricula = db_utils::fieldsmemory($rsResultMat,0)->qtdmatricula == "" ? 0 : 
                        db_utils::fieldsmemory($rsResultMat,0)->qtdmatricula;
                        
        $oDaoTurma->ed57_i_nummatr = $qtdmatricula;
        $oDaoTurma->ed57_i_codigo  = $oDados->ed60_i_turma;
        $oDaoTurma->alterar($oDaoTurma->ed57_i_codigo);
      
        if ($oDaoTurma->erro_status == '0') {              
          throw new Exception("Erro na alteração na tabela Turma. Erro da classe: ".$oDaoTurma->erro_msg);                                       
        }//fecha o erro_status   
      
        LimpaResultadofinal($matricula);
    
      }//fecha o if $oDaoMatricula->numrows > 0
    
    }//fecha o $concluida == "N"
    if ($oDaoTransfEscolaFora->erro_status == "0") {
      $erro_transf = true;
    } else {
      $erro_transf = false;
    }
  
    $sSqlObsTransferencia     = $oDaoObsTransferencia->sql_query("", "ed283_i_codigo", "", " ed283_i_escola = $iEscola"); 
    $rsResultObsTransferencia = $oDaoObsTransferencia->sql_record($sSqlObsTransferencia);
                                             
    if ($oDaoObsTransferencia->numrows > 0) {
         
      $oDaoObsTransferencia->ed283_i_escola       = $iEscola;
      $oDaoObsTransferencia->ed283_t_mensagem     = $obs;
      $oDaoObsTransferencia->ed283_c_bolsafamilia = $ed283_c_bolsafamilia;
      $oDaoObsTransferencia->ed283_i_codigo       = db_utils::fieldsmemory($rsResultObsTransferencia,0)->ed283_i_codigo;
      $oDaoObsTransferencia->alterar($oDaoObsTransferencia->ed283_i_codigo);
    
      if ($oDaoObsTransferencia->erro_status == '0') {              
        throw new Exception("Erro na alteração na tabela ObsTransferencia. Erro da classe: ".
                            $oDaoObsTransferencia->erro_msg
                           );                                       
      }//fecha o erro_status   
    
     } else {
        
       if ($obs != "") {
            
         $oDaoObsTransferencia->ed283_i_escola       = $iEscola;
         $oDaoObsTransferencia->ed283_c_bolsafamilia = $ed283_c_bolsafamilia;
         $oDaoObsTransferencia->ed283_t_mensagem     = $obs;
         $oDaoObsTransferencia->incluir(null);     
       
         if ($oDaoObsTransferencia->erro_status == '0') {      
                
           throw new Exception("Erro na alteração na tabela ObsTransferencia. Erro da classe: ".
                               $oDaoObsTransferencia->erro_msg
                              );
                                                                   
         }//fecha o erro_status
          
       }//fecha o if obs!=""
    
    }//fecha o else 
  
    db_fim_transacao();
    //db_msgbox("Transferência efetuada com sucesso!");
    ///db_redireciona("edu1_transfescolafora001.php");
    //exit;
    
  } catch (Exception $oE) {

    db_fim_transacao(true);
    db_msgbox(str_replace("'", "\'", $oE->getMessage()));
    db_redireciona('edu1_transfescolafora001.php');

  }//fecha o catch
  
}//fecha o if incluir
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Transferência para outras escolas</b></legend>
    <?include("forms/db_frmtransfescolafora.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
js_tabulacaoforms("form1", "ed104_i_aluno", true, 1, "ed104_i_aluno", true);
</script>
<?
if (isset($incluir)) {
  
  if ($oDaoTransfEscolaFora->erro_status == "0") {
  
    $oDaoTransfEscolaFora->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    echo "<script> document.form1.db_opcao.style.visibility='visible';</script>  ";
    
    if ($oDaoTransfEscolaFora->erro_campo != "") {
      
      echo "<script> document.form1.".$oDaoTransfEscolaFora->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoTransfEscolaFora->erro_campo.".focus();</script>";
      
    }
  } else {
    
    $oDaoTransfEscolaFora->erro(true, false);
    $alunos = $oDaoTransfEscolaFora->ed104_i_codigo;
   ?>
    <script> 
    
      jan = window.open('edu2_guiatransf002.php?alunos=<?=$alunos?>&tipo=TF&diretor=<?=$diretor?>'+
                      '&bolsafamilia=<?=$ed283_c_bolsafamilia?>&obs=<?=$obs?>','',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0'
                     );
      jan.moveTo(0,0);
    </script>
   <?
    db_redireciona("edu1_transfescolafora001.php");
    
  }
  
}
?>