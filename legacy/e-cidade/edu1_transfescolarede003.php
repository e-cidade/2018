<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_transfescolarede_classe.php");
require_once("classes/db_matricula_classe.php");
require_once("classes/db_logmatricula_classe.php");
require_once("classes/db_diario_classe.php");
require_once("classes/db_turma_classe.php");
require_once("classes/db_regencia_classe.php");
require_once("libs/db_utils.php");
$iEscola              = db_getsession("DB_coddepto");
$oDaoTransfEscolaRede = db_utils::getdao('transfescolarede');
$oDaoMatricula        = db_utils::getdao('matricula');
$oDaoLogMatricula     = db_utils::getdao('logmatricula');
$oDaoRegencia         = db_utils::getdao('regencia');
$oDaoDiario           = db_utils::getdao('diario');
$oDaoTurma            = db_utils::getdao('turma');
$oDaoAlunoCurso       = db_utils::getdao('alunocurso');
$oDaoAtestVaga        = db_utils::getdao('atestvaga');
$oDaoHistorico        = db_utils::getdao('historico');
$oDaoMatriculaMov     = db_utils::getdao('matriculamov');
$oDaoTurma->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed18_c_nome");
$clrotulo->label("ed15_c_nome");
$clrotulo->label("ed31_c_descr");
$clrotulo->label("ed31_i_codigo");
$clrotulo->label("ed52_i_codigo");
$clrotulo->label("ed15_i_codigo");
$clrotulo->label("ed11_i_codigo");
$clrotulo->label("ed29_i_codigo");
$clrotulo->label("ed60_i_codigo");
$clrotulo->label("ed60_c_situacao");
$clrotulo->label("ed60_c_concluida");
$clrotulo->label("ed11_c_descr");
$clrotulo->label("ed57_c_descr");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed52_c_descr");
$clrotulo->label("ed15_c_descr");

if (isset($incluir)) {

  try {
  
    db_inicio_transacao();
    
    /* Busco dados da matrícula (de origem) */
    $sCamposMatricula  = " ed60_i_turma,ed60_c_concluida as concluida,ed60_d_datamodifant as datamodifant, ";
    $sCamposMatricula .= " ed60_d_datamodif as datamodif,ed60_d_datasaida as datasaida, ";
    $sCamposMatricula .= " turma.ed57_i_turno as turnoturma,matriculaserie.ed221_i_serie as etapaoriginal";
    $sSqlMatricula     = $oDaoMatricula->sql_query(null, $sCamposMatricula, '', 
                                                   " ed60_i_codigo = $matriculaorig"
                                                  );
    $rsResultMatricula = $oDaoMatricula->sql_record($sSqlMatricula); 
    
    if ($oDaoMatricula->numrows <= 0) {

      throw new Exception('Não foi possível obter informações sobre a matrícula de origem. Mensagem da classe: '.
                          $oDaoMatricula->erro_msg
                         );

    }
      
    $oDadosMatricula = db_utils::fieldsmemory($rsResultMatricula, 0);

    /* Busco a situação do aluno anterior à transferência, para voltar a ela */
    $sCampos            = "ed56_i_codigo, ed56_c_situacaoant as sitanterior";
    $sWhereAlunoCurso   = "ed56_i_aluno = $codigoaluno AND ed56_c_situacao = 'TRANSFERIDO REDE'";  
    $sSqlAlunoCurso     = $oDaoAlunoCurso->sql_query(null, $sCampos, '', $sWhereAlunoCurso);
    $rsResultAlunoCurso = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);
    
    if ($oDaoAlunoCurso->numrows <= 0) {

      throw new Exception('Não foi possível obter informações sobre a situação anterior '.
                          'da matrícula do aluno. Mensagem da classe: '.
                          $oDaoAlunoCurso->erro_msg
                         );

    }
    
    $iCodAlunoCurso    = db_utils::fieldsmemory($rsResultAlunoCurso, 0)->ed56_i_codigo;
    $sSituacaoAnterior = db_utils::fieldsmemory($rsResultAlunoCurso, 0)->sitanterior;
    $sSituacaoAnterior = empty($oDados->sitanterior) ? 'MATRICULADO' : $sSitAnterior;

    /* Se a matrícula não estiver concluída, ativo os diários, atualizo a situação da matrícula e da turma */
    if ($oDadosMatricula->concluida == 'N') { 
    
      $sWhereRegencia   = "ed59_i_turma = $codturmaorig and ed59_i_serie = ".$oDadosMatricula->etapaoriginal;
      $sSqlRegencia     = $oDaoRegencia->sql_query_file(null, "ed59_i_codigo as regturma", '', $sWhereRegencia);
      $rsResultRegencia = $oDaoRegencia->sql_record($sSqlRegencia);
       
       for ($iCont = 0; $iCont < $oDaoRegencia->numrows; $iCont++)  {
         
         $oDadosRegencia = db_utils::fieldsmemory($rsResultRegencia, $iCont);
         $sWhereDiario   = " ed95_i_aluno = $codigoaluno and ed95_i_regencia = ".$oDadosRegencia->regturma;
         $sWhereDiario  .= " and ed95_i_serie = ".$oDadosMatricula->etapaoriginal;
         $sSqlDiario     = $oDaoDiario->sql_query_file(null, 'ed95_i_codigo as codigodiario', '', $sWhereDiario);
         $rsResultDiario = $oDaoDiario->sql_record($sSqlDiario);
         
         if ($oDaoDiario->numrows > 0) {
           
           $oDaoDiario->ed95_c_encerrado = 'N';
           $oDaoDiario->ed95_i_codigo    = db_utils::fieldsmemory($rsResultDiario, 0)->codigodiario;
           $oDaoDiario->alterar($oDaoDiario->ed95_i_codigo);
           if ($oDaoDiario->erro_status == '0') {              
            throw new Exception('Erro na alteração na tabela Diario. Erro da classe: '.$oDaoDiario->erro_msg);                                       
          }

         }
          
       } //fecha o for regencias
      
      /* Atualizo situação da matrícula */
      
      $GLOBALS['HTTP_POST_VARS']['ed60_d_datamodifant_dia'] = '';
      $GLOBALS['HTTP_POST_VARS']['ed60_d_datasaida_dia']    = '';
      // Limpo o status da classe
      
      $oDaoMatricula->erro_status         = null;
      $oDaoMatricula->ed60_c_situacao     = $sSituacaoAnterior;
      $oDaoMatricula->ed60_d_datamodif    = date('Y-m-d') ; // data de hoje
      $oDaoMatricula->ed60_d_datamodifant = (empty($oDadosMatricula->datamodif) ? $oDadosMatricula->datamodifant : $oDadosMatricula->datamodif ); //data modif
      $oDaoMatricula->ed60_d_datasaida    = null;
      $oDaoMatricula->ed60_i_codigo       = $matriculaorig;
      
      $oDaoMatricula->alterar($oDaoMatricula->ed60_i_codigo);
      if ($oDaoMatricula->erro_status == '0') {
        throw new Exception('Erro ao atualizar a situação da matrícula. Erro da classe: '. $oDaoMatricula->erro_msg );
      }
           
      $sExcluir  = " ed229_i_matricula = $matriculaorig ";
      $sExcluir .= " and ed229_c_procedimento = 'TRANSFERÊNCIA ENTRE ESCOLAS DA REDE'";
      $oDaoMatriculaMov->excluir(null, $sExcluir);
      if ($oDaoMatriculaMov->erro_status == '0') {

        throw new Exception('Erro ao excluir a transferência nos movimentos da matrícula. '.
                            'Erro da classe: '.$oDaoMatriculaMov->erro_msg
                           );

      }
        
    } // Fim if matrícula não concluída 
    
    if ($oDadosMatricula->concluida == 'S' 
        && trim($sSituacaoAnterior) == 'MATRICULADO') {
      
        
      $sResultadoFinal = ResultadoFinal($matriculaorig, $codigoaluno, $codturmaorig,
                                        $sSituacaoAnterior, $oDadosMatricula->concluida
                                       );
      $sSituacaoAtual  = $sResultadoFinal == 'REPROVADO' ? 'REPETENTE' : 'APROVADO';
        
      
    } else {
      $sSituacaoAtual = $sSituacaoAnterior;
    }
    
    /* Atualizo a situação do aluno no curso */
    $oDaoAlunoCurso->ed56_i_escola      = $codescolaorig;
    $oDaoAlunoCurso->ed56_c_situacao    = $sSituacaoAtual;
    $oDaoAlunoCurso->ed56_c_situacaoant = $sSituacaoAnterior;
    $oDaoAlunoCurso->ed56_i_codigo      = $iCodAlunoCurso;
    $oDaoAlunoCurso->alterar($oDaoAlunoCurso->ed56_i_codigo);
    if ($oDaoAlunoCurso->erro_status == '0') {

      throw new Exception('Erro na alteração na tabela AlunoCurso. Erro da classe: '.
                          $oDaoAlunoCurso->erro_msg
                         );

    }
    
    /* Busco o código do atestado para excluir */
    $sWhere            = "ed103_i_codigo = $codigotransf";
    $sSqlAtestVaga     = $oDaoTransfEscolaRede->sql_query_file(null, 'ed103_i_atestvaga as cod_atestvaga', 
                                                               '', $sWhere
                                                              );
    $rsResultAtestVaga = $oDaoTransfEscolaRede->sql_record($sSqlAtestVaga);
    
    if ($oDaoTransfEscolaRede->numrows > 0) {
      
      $oDaoTransfEscolaRede->excluir(null, "ed103_i_codigo = $codigotransf");
      if ($oDaoTransfEscolaRede->erro_status == '0') {
                      
        throw new Exception('Erro ao excluir a transferência na rede. Erro da classe: '.
                            $oDaoTransfEscolaRede->erro_msg
                           );
                                                                    
      }
      
      /* Excluo o atestado de transferência */
      $oDaoAtestVaga->excluir(null, 'ed102_i_codigo = '.
                              db_utils::fieldsmemory($rsResultAtestVaga, 0)->cod_atestvaga
                             );
      
      if ($oDaoAtestVaga->erro_status == '0') {

        throw new Exception('Erro ao excluir o atestado de transferência. Erro da classe: '.
                            $oDaoAtestvaga->erro_msg
                           );

      }

    } // Fecha o if $oDaoTransfEscolaRede->numrows > 0
    
    $sSqlHistorico     = $oDaoHistorico->sql_query_file(null, 'ed61_i_codigo', '', 
                                                        "ed61_i_aluno = $codigoaluno"
                                                       );
    $rsResultHistorico = $oDaoHistorico->sql_record($sSqlHistorico);
    
    if ($oDaoHistorico->numrows > 0) {
      
      $oDaoHistorico->ed61_i_escola = $codescolaorig;
      $oDaoHistorico->ed61_i_codigo = db_utils::fieldsmemory($rsResultHistorico, 0 )->ed61_i_codigo;
      $oDaoHistorico->alterar($oDaoHistorico->ed61_i_codigo);
      if ($oDaoHistorico->erro_status == '0') {

        throw new Exception('Erro ao retornar o histórico do aluno para a escola de origem. '.
                            'Erro da classe: '.$oDaoHistorico->erro_msg
                           );

      }
      
    }
    
    $GLOBALS['HTTP_POST_VARS']['ed248_i_motivo'] = '';
    $sObs                              = "Cancelamento de TRANSFERÊNCIA REDE( Escola Origem: ";
    $sObs                             .= $descrescolaorig."Escola Destino: ".$descrescoladest;
    $sObs                             .= " \n Excluído Atestado de vaga n° ";
    $sObs                             .= db_utils::fieldsmemory($rsResultAtestVaga, 0)->cod_atestvaga.")";
    $oDaoLogMatricula->ed248_i_usuario = db_getsession('DB_id_usuario');
    $oDaoLogMatricula->ed248_i_motivo  = 'null';
    $oDaoLogMatricula->ed248_i_aluno   = $codigoaluno;
    $oDaoLogMatricula->ed248_t_origem  = "Matrícula n°: $matriculaorig\nTurma: ";
    $oDaoLogMatricula->ed248_t_origem .= "$descrturmaorig\nEscola: $descrescolaorig";
    $oDaoLogMatricula->ed248_t_obs     = $sObs;
    $oDaoLogMatricula->ed248_d_data    = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoLogMatricula->ed248_c_hora    = date('H:i');
    $oDaoLogMatricula->ed248_c_tipo    = 'T';
    $oDaoLogMatricula->incluir(null); 
    
    if ($oDaoLogMatricula->erro_status == '0') {

      throw new Exception('Erro na alteração na tabela LogMatricula. Erro da classe: '.
                          $oDaoLogMatricula->erro_msg
                         );

    }
    
    db_fim_transacao();
    db_msgbox('Cancelamento efetuado com sucesso!');
    db_redireciona('edu1_transfescolarede003.php');
    exit;

  } catch (Exception $oE) {

    db_fim_transacao(true);
    db_msgbox(str_replace("'", "\'", $oE->getMessage()));
    db_redireciona('edu1_transfescolarede003.php');

  }
  
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
   <fieldset style="width:95%"><legend><b>Cancelar Transferência entre Escolas da Rede</b></legend>
   <table border="0" align="left">
    </tr>
     <td colspan="2">
      <?
      $sCampos                  = " ed103_i_codigo,ed47_i_codigo,ed47_v_nome,ed103_d_data, ";
      $sCampos                 .= " escoladestino.ed18_c_nome as escoladestino";
      $sWhere                   = "     ed103_i_escolaorigem = $iEscola AND ed103_c_situacao = 'A'";
      $sWhere                  .= " and ed60_c_concluida = 'N' ";
      $sSqlTransfEscolaRede     = $oDaoTransfEscolaRede->sql_query("", $sCampos, "ed103_d_data desc", $sWhere);
      $rsResultTransfEscolaRede = $oDaoTransfEscolaRede->sql_record($sSqlTransfEscolaRede);
      ?>
      <b>Alunos Transferidos:</b>
      <select name="aluno" style="font-size:9px;" onchange="js_pesquisa(this.value);">
       <?
       if ($oDaoTransfEscolaRede->numrows == 0) {
         echo "<option value=''>Nenhum registro de transferência em aberto.</option>";
       } else {
                   
         echo "<option value=''></option>";
         for ($iCont = 0; $iCont < $oDaoTransfEscolaRede->numrows; $iCont++) {
           
           $oDados = db_utils::fieldsmemory($rsResultTransfEscolaRede, $iCont);
           echo "<option value='$oDados->ed103_i_codigo' ".($oDados->ed103_i_codigo==@$aluno?"selected":"").">".
                  db_formatar($oDados->ed103_d_data, 'd')." -> ".$oDados->ed47_i_codigo ."-".
                  $oDados->ed47_v_nome."(Destino: ".$oDados->escoladestino.")
                 </option>";
                 
         }
         
       }
       ?>
      </select>
     </td>
    </tr>
    <?
      if (isset($aluno)) {
      
        $sCamposTransfEscolaRede  = " transfescolarede.ed103_d_data,transfescolarede.ed103_c_situacao, "; 
        $sCamposTransfEscolaRede .= " transfescolarede.ed103_t_obs,transfescolarede.ed103_i_codigo, ";
        $sCamposTransfEscolaRede .= " transfescolarede.ed103_i_matricula,aluno.ed47_i_codigo as codigoaluno,";
        $sCamposTransfEscolaRede .= " serie.ed11_c_descr||' - '||ensino.ed10_c_abrev as descrseriedest, ";
        $sCamposTransfEscolaRede .= " base.ed31_c_descr as descrbasedest,calendario.ed52_c_descr as descrcalendariodest,";
        $sCamposTransfEscolaRede .= " escoladestino.ed18_c_nome as descrescoladest,turno.ed15_c_nome as descrturnodest";
        $sWhere                   = " ed103_i_codigo = $aluno";
        $sSqlTransfEscolaRede     = $oDaoTransfEscolaRede->sql_query("", $sCamposTransfEscolaRede, "", $sWhere);
        $rsResultTransfEscolaRede = $oDaoTransfEscolaRede->sql_record($sSqlTransfEscolaRede);
         
        if ($oDaoTransfEscolaRede->numrows > 0 ) {
          
          $oDadosTransf        = db_utils::fieldsmemory($rsResultTransfEscolaRede, 0);  
          $descrescoladest     = $oDadosTransf->descrescoladest;
          $descrcalendariodest = $oDadosTransf->descrcalendariodest;
          $descrbasedest       = $oDadosTransf->descrbasedest;
          $descrseriedest      = $oDadosTransf->descrseriedest;
          $descrturnodest      = $oDadosTransf->descrturnodest;          
          $ed103_d_data        = db_formatar($oDadosTransf->ed103_d_data, 'd');
          
        }
    
        $sCamposMatricula  = " turma.ed57_i_codigo as codturmaorig,turma.ed57_c_descr as descrturmaorig, ";
        $sCamposMatricula .= " serie.ed11_i_codigo as codserieorig,base.ed31_i_codigo as codbaseorig, ";
        $sCamposMatricula .= " serie.ed11_c_descr ||' - '||ensino.ed10_c_abrev as descrserieorig, ";                
        $sCamposMatricula .= " calendario.ed52_i_codigo as codcalendarioorig,escola.ed18_i_codigo as codescolaorig, ";
        $sCamposMatricula .= " escola.ed18_c_nome as descrescolaorig,turno.ed15_i_codigo as codturnoorig, ";
        $sCamposMatricula .= " ed60_i_codigo as matriculaorig,ed60_c_situacao as situacaoorig, ";
        $sCamposMatricula .= " ed60_c_concluida as conclusaoorig, cursoedu.ed29_i_codigo as codcursoorig";
        $sWhereMatricula   = " ed60_i_codigo = ".$oDadosTransf->ed103_i_matricula;           
        $sSqlMatricula     = $oDaoMatricula->sql_query("", $sCamposMatricula, "", $sWhereMatricula);
        $rsResultMatricula = $oDaoMatricula->sql_record($sSqlMatricula);     
    
        if ($oDaoMatricula->numrows > 0) {
          
          $oDadosMat         =  db_utils::fieldsmemory($rsResultMatricula,0);         
          $conclusaoorig     = $oDadosMat->conclusaoorig == "S" ? "SIM" : "NAO";
          $descrescolaorig   = $oDadosMat->descrescolaorig;
          $codescolaorig     = $oDadosMat->codescolaorig;
          $codbaseorig       = $oDadosMat->codbaseorig;
          $codcalendarioorig = $oDadosMat->codcalendarioorig;
          $codturnoorig      = $oDadosMat->codturnoorig;
          $codserieorig      = $oDadosMat->codserieorig;
          $codcursoorig      = $oDadosMat->codcursoorig;
          $matriculaorig     = $oDadosMat->matriculaorig;
          $situacaoorig      = $oDadosMat->situacaoorig;
          $conclusaoorig     = $oDadosMat->conclusaoorig;
          $codserieorig      = $oDadosMat->codserieorig;
          $descrserieorig    = $oDadosMat->descrserieorig;
          $codturmaorig      = $oDadosMat->codturmaorig;
          $descrturmaorig    = $oDadosMat->descrturmaorig;
          
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
           <?db_input('descrescolaorig', 40, $Ied18_c_nome,true, 'text', 3, '');?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Matrícula:</b>
          </td>
          <td>
           <? db_input('codescolaorig', 15, $Ied18_i_codigo,true, 'hidden', 3, '');?>
           <? db_input('codbaseorig', 15, $Ied31_i_codigo,true, 'hidden', 3, '');?>
           <? db_input('codcalendarioorig', 15, $Ied52_i_codigo,true, 'hidden', 3 ,'');?>
           <? db_input('codturnoorig', 15, $Ied15_i_codigo,true, 'hidden', 3, '');?>
           <? db_input('codserieorig', 15, $Ied11_i_codigo,true, 'hidden', 3, '');?>
           <? db_input('codcursoorig', 15, $Ied29_i_codigo,true, 'hidden', 3, '');?>
           <? db_input('matriculaorig', 15, $Ied60_i_codigo,true, 'text', 3, '');?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Situação:</b>
          </td>
          <td>
           <? db_input('situacaoorig', 40, $Ied60_c_situacao,true, 'text', 3, '');?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Concluida:</b>
          </td>
          <td>
           <? db_input('conclusaoorig', 40, $Ied60_c_concluida,true, 'text', 3, '');?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Etapa:</b>
          </td>
          <td>
           <? db_input('codserieorig', 15, $Ied11_i_codigo,true, 'hidden', 3, ''); ?>
           <? db_input('descrserieorig', 40, $Ied11_c_descr,true, 'text', 3, '');?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Turma:</b>
          </td>
          <td>
           <? db_input('codturmaorig', 15, $Ied57_i_codigo,true, 'hidden', 3, '');?>
           <? db_input('descrturmaorig',40,$Ied57_c_descr,true, 'text', 3, '');?>
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
           <b>Calendário:</b>
          </td>
          <td>
           <?db_input('descrcalendariodest', 40, $Ied52_c_descr, true, 'text', 3, '')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Base:</b>
          </td>
          <td>
           <?db_input('descrbasedest', 40, $Ied31_c_descr, true, 'text', 3, '')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Etapa:</b>
          </td>
          <td>
           <?db_input('descrseriedest', 40, $Ied11_c_descr, true, 'text', 3, '')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Turno:</b>
          </td>
          <td>
           <?db_input('descrturnodest', 40, $Ied15_c_nome, true, 'text', 3, '')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Transferido em:</b>
          </td>
          <td>
          <?db_input('ed103_d_data', 10, @$Ied103_d_data, true, 'text', 3, '')?>
          </td>
         </tr>
        </table>
       </fieldset>
     </td>
    </tr>
    <tr>
     <td colspan="2">
      <?
      $lErro              = false;
      $sCampos            = "ed18_c_nome,ed56_c_situacao,ed11_c_descr";
      $sWhere             = "ed56_i_aluno = ".$oDadosTransf->codigoaluno."AND ed56_i_calendario = ";
      $sWhere            .= db_utils::fieldsmemory($rsResultMatricula,0)->codcalendarioorig;
      $sSqlAlunoCurso     = $oDaoAlunoCurso->sql_query_alunotransf("", $sCampos, "", $sWhere);      
      $rsResultAlunoCurso = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);     
      
      if ($oDaoAlunoCurso->numrows == 0) {
        $lErro = true;
      } else {
       
       if (trim(db_utils::fieldsmemory($rsResultAlunoCurso, 0)->ed56_c_situacao) != "TRANSFERIDO REDE") {
         $lErro = true;
       }
       
      }
      if ($lErro) {
        
        $sCampos            = "ed18_c_nome,ed56_c_situacao,ed11_c_descr";
        $sWhere             = "ed56_i_aluno = ".$oDadosTransf->codigoaluno."AND ed56_i_calendario = ";
        $sWhere            .= db_utils::fieldsmemory($rsResultMatricula, 0)->codcalendarioorig;
        $sSqlAlunoCurso     = $oDaoAlunoCurso->sql_query("", $sCampos, "", $sWhere);
        $rsResultAlunoCurso = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);
        
        $sMsg  = " Transferência já foi concretizada no destino. Cancelamento da transferência não permitido. \n";
        $sMsg .= " Situação atual do aluno: \n";
        $sMsg .= " Escola: ".db_utils::fieldsmemory($rsResultAlunoCurso, 0)->ed18_c_nome."\n";
        $sMsg .= " Situação: ".db_utils::fieldsmemory($rsResultAlunoCurso, 0)->ed56_c_situacao."\n";
        $sMsg .= " Etapa: ".db_utils::fieldsmemory($rsResultAlunoCurso, 0)->ed11_c_descr."\n";     
        echo $sMsg;             
                         
      }
      
      if (db_utils::fieldsmemory($rsResultMatricula, 0)->conclusaoorig == "SIM"
          && db_utils::fieldsmemory($rsResultMatricula, 0)->situacaoorig == "TRANSFERIDO REDE") {
            
        $lErro = true;
        $sMsg  = " <b>ATENÇÃO! Matrícula com situação de TRANSFERIDO REDE já está concluída na turma de origem. ";
        $sMsg .= " Cancelamento da transferência não permitido.</b><br><br>";  
        echo  $sMsg;
        
      }
      ?>
      <input type="hidden" name="codigotransf" value="<?=$oDadosTransf->ed103_i_codigo?>">
      <input type="hidden" name="codigoaluno" value="<?=$oDadosTransf->codigoaluno?>">
      <input type="submit" name="incluir" value="Confirmar Cancelamento" 
             onclick="return js_confirma();" <?=$lErro?"disabled":""?>>
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
  db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
function js_pesquisa() {
  
  if (document.form1.aluno.value == "") {
    location.href = 'edu1_transfescolarede003.php';
  } else {
    location.href = 'edu1_transfescolarede003.php?aluno='+document.form1.aluno.value;
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
