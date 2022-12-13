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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_matricula_classe.php");
include("classes/db_matriculamov_classe.php");
include("classes/db_alunonecessidade_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");
db_postmemory($HTTP_POST_VARS);
$clmatricula        = new cl_matricula;
$clmatriculamov     = new cl_matriculamov;
$clalunonecessidade = new cl_alunonecessidade;
$clmatricula->rotulo->label();
$db_opcao = 1;
define("MENSAGEM_MATRICULA004", "educacao.escola.edu1_matricula004." );

if (isset($alterar)) {

  db_inicio_transacao();
  $sCampos  = " ed60_t_obs as confereobs, ed60_d_datamatricula as conferedata,ed60_c_parecer as confereparec,";
  $sCampos .= " ed60_i_turma,turma.ed57_c_descr,calendario.ed52_c_descr,ed60_d_datamodif as datamodif,";
  $sCampos .= " ed60_d_datamodifant as datamodifant,ed60_d_datasaida as datasaida";
  $result1 = $clmatricula->sql_record($clmatricula->sql_query("",
                                                              $sCampos,
                                                              "",
                                                              " ed60_i_codigo = $matricula"
                                                             )
                                     );
  db_fieldsmemory($result1,0);

  /**
   * Verifica se o aluno está utilizando proporcionalidade
   */
  $lUtilizaProporcionalidade = false;
  $oMatricula                = new Matricula( $matricula );

  db_inicio_transacao();
  $oDiario = $oMatricula->getDiarioDeClasse();
  db_fim_transacao();

  $aDiarioAvaliacaoDisciplina = $oDiario->getDisciplinas();
  
  if ( !empty( $aDiarioAvaliacaoDisciplina ) ) {
    foreach ($aDiarioAvaliacaoDisciplina as $oDiarioAvaliacaoDisciplina ) {
      
      if ( count($oDiarioAvaliacaoDisciplina->getOrdemPeriodosAplicaProporcionalidade()) > 0 ) {

        $lUtilizaProporcionalidade = true;
        break;
      }
    }
  }

  /**
   * Busca a data inicial do primeiro periodo de avaliação
   */
  $aPeriodosCalendario          = $oMatricula->getTurma()->getCalendario()->getPeriodos();
  $oDataInicio                  = $aPeriodosCalendario[0]->getDataInicio();
  $oDataTermino                 = $aPeriodosCalendario[(count($aPeriodosCalendario)-1)]->getDataTermino();
  $lMatriculadoAntesDataInicial = true;
  $oDataAlterada                = new DBDate($ed60_d_datamatricula);

  /**
   * Valida se a data alterada esta dentro do periodo do calendario
   */
  if ( $oDataAlterada->getTimeStamp() < $oDataInicio->getTimeStamp() || 
        $oDataAlterada->getTimeStamp() > $oDataTermino->getTimeStamp() ) {

        $oErro = new stdClass();
        $oErro->dtInicio = $oDataInicio->convertTo(DBDATE::DATA_PTBR);
        $oErro->dtFim    = $oDataTermino->convertTo(DBDATE::DATA_PTBR);
        db_msgbox( _M( MENSAGEM_MATRICULA004 . "data_matricula_fora_calendario", $oErro ) );
        echo "<script> ";
        echo "  parent.db_iframe_observacoes.hide(); ";
        echo "</script>";
        return;
  }

  /**
   * Utiliza a validação da data inicial com a data alterada para alunos com proporcionalidade
   */
  if ( DBDate::calculaIntervaloEntreDatas( $oDataAlterada, $oDataInicio, 'd' ) > 0 ) {
    $lMatriculadoAntesDataInicial = false;
  }
  
  /**
   * Valida se o aluno utiliza proporcionalidade e se a data de matricula alterada está posterior a data de início do 
   * primeiro período de avaliação, caso contrario não deixa alterar.
   */
  if ( $lUtilizaProporcionalidade && $lMatriculadoAntesDataInicial ) {

    $oErro = new stdClass();
    $oErro->sErro = $oDataInicio->convertTo(DBDATE::DATA_PTBR);
    db_msgbox( _M( MENSAGEM_MATRICULA004 . "data_matricula_proporcionalidade", $oErro ) );
    echo "<script> ";
    echo "  parent.db_iframe_observacoes.hide(); ";
    echo "</script>";
    return;
  }

  $oAluno         = $oMatricula->getAluno();
  $aMatriculas    = MatriculaRepository::getTodasMatriculasAluno($oAluno, false, null);
  
  if ( !empty($aMatriculas ) && isset($aMatriculas[1] ) ) {

    if ( in_array($aMatriculas[1]->getSituacao(), array('TRANSFERIDO REDE','TRANSFERIDO FORA') ) ) {
      
      $oDataSaidaMatriculaAnterior = $aMatriculas[1]->getDataEncerramento();
      $lAplicaValidacao            = true;

      if (    $aMatriculas[1]->getSituacao() ==  "TRANSFERIDO FORA"
           && $oDataAlterada->getAno() != $oDataSaidaMatriculaAnterior->getAno() ) {
        $lAplicaValidacao = false;
      }

      if (    $lAplicaValidacao
           && DBDate::calculaIntervaloEntreDatas( $oDataAlterada, $oDataSaidaMatriculaAnterior, 'd' ) < 0 ) {

        db_msgbox( _M( MENSAGEM_MATRICULA004 . "data_matricula_inferior") );
        echo "<script> ";
        echo "  parent.db_iframe_observacoes.hide(); ";
        echo "</script>";
        return;
      }
    }
  }
  
  $clmatricula->ed60_d_datamodifant = $datamodifant;
  $clmatricula->ed60_d_datasaida    = ($datasaida==""?"null":$datasaida);   
  $clmatricula->ed60_d_datamodif    = date("Y-m-d",db_getsession("DB_datausu"));
  $clmatricula->alterar($ed60_i_codigo);
  if (db_formatar($conferedata,'d') != $ed60_d_datamatricula || trim($confereobs) != trim($ed60_t_obs)) {
  	
    $descricao = "";
    $sep       = "";
    
    if (db_formatar($conferedata,'d') != $ed60_d_datamatricula) {
    	
      $descricao .= "DATA DA MATRÍCULA MODIFICADA DE ".db_formatar($conferedata,'d')." PARA ".$ed60_d_datamatricula;
      $sep        = " | ";
      $sWhere     = " (ed229_c_procedimento = 'REMATRICULAR ALUNO' or";
      $sWhere    .= "  ed229_c_procedimento = 'MATRICULAR ALUNOS TRANSFERIDOS' or";
      $sWhere    .= "  ed229_c_procedimento = 'MATRICULAR ALUNO') and ed229_i_matricula = $ed60_i_codigo";
      $result11   = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                           "ed229_i_codigo as codaltera",
                                                                           "",
                                                                           $sWhere
                                                                          )
                                               );
                                               
      if ($clmatriculamov->numrows > 0) {
      	
        db_fieldsmemory($result11,0);
        $dataevento                         = substr($ed60_d_datamatricula,6,4);
        $dataevento                        .= "-".substr($ed60_d_datamatricula,3,2)."-".substr($ed60_d_datamatricula,0,2);
        $clmatriculamov->ed229_d_dataevento = $dataevento;
        $clmatriculamov->ed229_i_codigo     = $codaltera;
        $clmatriculamov->alterar($codaltera);
        
      }
    }
    
    if (trim($confereobs) != trim($ed60_t_obs)) {
    	
      $descricao .= $sep."OBSERVAÇÕES ALTERADAS:  ".($ed60_t_obs==""?"TODO CONTEÚDO APAGADO":$ed60_t_obs);
      
    }
    
    $ed229_i_codigo                       = "";
    $clmatriculamov->ed229_i_matricula    = $ed60_i_codigo;
    $clmatriculamov->ed229_i_usuario      = db_getsession("DB_id_usuario");
    $clmatriculamov->ed229_c_procedimento = "ALTERAÇÃO DE DATA DA MATRÍCULA E/OU OBSERVAÇÕES";
    $clmatriculamov->ed229_t_descr        = $descricao;
    $clmatriculamov->ed229_d_dataevento   = date("Y-m-d",db_getsession("DB_datausu"));
    $clmatriculamov->ed229_c_horaevento   = db_hora();
    $clmatriculamov->ed229_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
    $clmatriculamov->incluir($ed229_i_codigo);
  }
  
  if (isset($ed60_c_parecer) && trim($confereparec) != trim(@$ed60_c_parecer)) {
    LimpaResultadoFinal($ed60_i_codigo);   
  }
  
  db_fim_transacao();
  $clmatricula->erro(true,false);
  ?>
   <script>
    parent.db_iframe_observacoes.hide();
    parent.location.href = "edu1_alunoturma001.php?ed60_i_turma=<?=$ed60_i_turma?>&ed57_c_descr=<?=$ed57_c_descr?>"+
                           "&ed52_c_descr=<?=$ed52_c_descr?>";
   </script>
  <?
  exit;
}

$result = $clmatricula->sql_record($clmatricula->sql_query($matricula));
db_fieldsmemory($result,0);
if ($ed60_c_ativa == "N" || $ed60_c_concluida == "S") {
  $db_opcao = 3;
}

$sCampos = "ed60_d_datamatricula as datamatanterior,ed60_d_datasaida as datasaidaanterior";
$sWhere  = " ed60_i_aluno = $ed60_i_aluno AND ed60_i_codigo not in($matricula)";
$result1 = $clmatricula->sql_record($clmatricula->sql_query_file("",
                                                                 $sCampos,
                                                                 "ed60_d_datamatricula desc",
                                                                 $sWhere
                                                                )
                                   );
if ($clmatricula->numrows > 0) {
  db_fieldsmemory($result1,0);
}
$result_ne = $clalunonecessidade->sql_record($clalunonecessidade->sql_query("",
                                                                            "ed214_i_codigo",
                                                                            "",
                                                                            " ed214_i_aluno = $ed60_i_aluno"
                                                                           )
                                            );
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11px;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
 border: 1px solid #f3f3f3;
}
.cabec1{
 font-size: 11px;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 10px;
 font-weight: bold;
}
.aluno1{
 color: #000000;
 font-family : Tahoma;
 font-weight: bold;
 text-align: center;
 font-size: 10px;
}
.aluno2{
 color: #000000;
 font-family : Verdana;
 font-size: 10px;
 font-weight: bold;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="left" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%;background:#EAEAEA;"><legend><b>Dados da Matricula</b></legend>
   <table border="0" cellspacing="0" width="100%" height="100%" cellpadding="2">
   <tr>
    <td align="center" valign="top">
     <table border='0' width="100%" bgcolor="#EAEAEA" cellspacing="0px">
      <tr>
       <td width="15%"><b>Escola:</b></td>
       <td><?=$ed18_c_nome?></td>
       <td><b>Calendário:</b></td>
       <td><?=$ed52_c_descr?></td>
      </tr>
      <tr>
       <td><b>Curso:</b></td>
       <td><?=$ed29_c_descr?></td>
       <td><b>Base:</b></td>
       <td><?=$ed31_c_descr?></td>
      </tr>
      <tr>
       <td><b>Turma:</b></td>
       <td><?=$ed57_c_descr?></td>
       <td><b>Etapa:</b></td>
       <td><?=$ed11_c_descr?></td>
      </tr>
      <tr>
       <td><b>Situação:</b></td>
       <td><?=Situacao($ed60_c_situacao,$ed60_i_codigo)?></td>
       <td><b>Concluída:</b></td>
       <td><?=$ed60_c_concluida=="S"?"SIM":"NÃO"?></td>
      </tr>
      <tr>
       <td><b>Matriculado em:</b></td>
       <td><?=db_formatar($ed60_d_datamatricula,'d')?></td>
       <td><b>Atualizado em:</b></td>
       <td><?=db_formatar($ed60_d_datamodif,'d')?></td>
      </tr>
     </table>
    </td>
   </tr>
   <tr>
    <td align="center">
     <form name="form1" method="post" action="">
     <br>
     <?=@$Led60_t_obs?><br>
     <?db_textarea('ed60_t_obs',3,120,@$Ied60_t_obs,true,'text',$db_opcao,"")?><br>
     <?=@$Led60_d_datamatricula?>
     <?db_inputdata('ed60_d_datamatricula',@$ed60_d_datamatricula_dia,@$ed60_d_datamatricula_mes,
                    @$ed60_d_datamatricula_ano,true,'text',(($ed60_c_situacao!="MATRICULADO")?3:$db_opcao),"")?>
     <?db_input('datamatanterior',10,@$Idatamatanterior,true,'hidden',3,"")?>
     <?db_input('datasaidaanterior',10,@$Idatasaidaanterior,true,'hidden',3,"")?>
     <?if($clalunonecessidade->numrows>0 || $ed60_c_parecer=="S"){?>
      <?=@$Led60_c_parecer?>
      <?
      $x = array("N"=>"NÃO","S"=>"SIM");
      db_select('ed60_c_parecer',$x,true,$db_opcao,"");
     }
     ?>
     <br><br>
     <input name="alterar" type="submit" value="Alterar" 
            onclick="return js_alerta('<?=$ed60_d_datamatricula?>','<?=$ed52_d_inicio?>','<?=$ed52_d_fim?>')" 
                                       <?=$ed60_c_ativa=="N"||$ed60_c_concluida=="S"?"disabled":""?>>
     <input name="fechar" type="button" value="Fechar" onclick="parent.db_iframe_observacoes.hide();">
     <input name="ed60_i_codigo" type="hidden" value="<?=@$matricula?>">
     </form>
    </td>
   </tr>
   <tr>
    <td>
     <table width="100%" cellspacing="0">
      <tr class="titulo" style="border:1px solid #f3f3f3">
       <td>
        &nbsp;&nbsp;<b>Movimentação da Matrícula N° <?=$iNumeroMatricula?> - <?=$ed47_v_nome?>:</b>
       </td>
       <td align="right">
       <a href="javascript:js_historico(<?=$iNumeroMatricula?>);" class="titulo" >Histórico de Matrículas</a>&nbsp;&nbsp;
       </td>
      </tr>
     </table>
    </td>
   </tr>
   <tr>
    <td colspan="2">
     <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
      <?
      $array_mov = array();
      $sCampos  = " ed229_i_codigo,ed229_d_dataevento,ed18_i_codigo,ed18_c_nome,ed60_i_codigo,";
      $sCampos .= " ed57_c_descr,ed52_i_ano,ed11_c_descr,ed229_c_procedimento,ed229_t_descr,nome";
      $sOrder   = " ed229_d_dataevento,ed229_i_codigo";
      $sWhere   = " ed60_matricula = {$iNumeroMatricula} AND ed229_c_procedimento NOT LIKE  'CANCELAR ENCERRAMENTO%' ";
      $sWhere  .= " AND ed229_c_procedimento NOT LIKE  'ENCERRAR%'";

      $result   = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                         $sCampos,
                                                                         $sOrder,
                                                                         $sWhere
                                                                        )
                                             );
      if ($clmatriculamov->numrows > 0) {

        for ($f = 0; $f < $clmatriculamov->numrows; $f++) {

          db_fieldsmemory($result,$f);
          $array_mov[]  = str_replace("-","",$ed229_d_dataevento).$ed229_i_codigo;
          $iContador = count($array_mov)-1; 
          $array_mov[$iContador] .= "|".db_formatar($ed229_d_dataevento,'d')."#".$ed18_i_codigo." - ".substr($ed18_c_nome,0,30);
          $array_mov[$iContador] .= "#".$ed60_i_codigo."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr;
          $array_mov[$iContador] .= "#".$ed229_c_procedimento."#".$ed229_t_descr."#".$nome;          
        }
      }
      $sCampos  = " ed229_i_codigo,ed229_d_dataevento,ed18_i_codigo,ed18_c_nome,ed60_i_codigo,";
      $sCampos .= " ed57_c_descr,ed52_i_ano,ed11_c_descr,ed229_c_procedimento,ed229_t_descr,nome ";
      $sOrder   = " ed229_d_dataevento DESC,ed229_i_codigo DESC LIMIT 1 ";
      $sWhere   = " ed60_matricula = {$iNumeroMatricula} AND ed229_c_procedimento LIKE  'CANCELAR ENCERRAMENTO%'";

      $result1  = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                         $sCampos,
                                                                         $sOrder,
                                                                         $sWhere
                                                                        )
                                             );
      if ($clmatriculamov->numrows > 0) {

        db_fieldsmemory($result1,0);
        $array_mov[]  = str_replace("-","",$ed229_d_dataevento).$ed229_i_codigo;
        $iContador = count($array_mov)-1; 
        $array_mov[$iContador] .= "|".db_formatar($ed229_d_dataevento,'d')."#".$ed18_i_codigo." - ".substr($ed18_c_nome,0,30);
        $array_mov[$iContador] .= "#".$ed60_i_codigo."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr;
        $array_mov[$iContador] .= "#".$ed229_c_procedimento."#".$ed229_t_descr."#".$nome;
      }
      
      $sCampos  = " ed229_i_codigo,ed229_d_dataevento,ed18_i_codigo,ed18_c_nome,ed60_i_codigo,";
      $sCampos .= " ed57_c_descr,ed52_i_ano,ed11_c_descr,ed229_c_procedimento,ed229_t_descr,nome"; 
      $sOrder   = " ed229_d_dataevento DESC,ed229_i_codigo DESC LIMIT 1";
      $sWhere   = " ed60_matricula = {$iNumeroMatricula} AND ed229_c_procedimento LIKE  'ENCERRAR%'";

      $result2  = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                         $sCampos,
                                                                         $sOrder,
                                                                         $sWhere
                                                                       )
                                            );
      if ($clmatriculamov->numrows > 0) {

        db_fieldsmemory($result2,0);
        $array_mov[]  = str_replace("-","",$ed229_d_dataevento).$ed229_i_codigo;
        $iContador = count($array_mov)-1; 
        $array_mov[$iContador] .= "|".db_formatar($ed229_d_dataevento,'d')."#".$ed18_i_codigo." - ".substr($ed18_c_nome,0,30);
        $array_mov[$iContador] .= "#".$ed60_i_codigo."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr."#".$ed229_c_procedimento;
        $array_mov[$iContador] .= "#".$ed229_t_descr."#".$nome;
      }
      
      array_multisort($array_mov,SORT_ASC);

      if (count($array_mov) > 0) {
      	
        ?>
        <tr class="titulo" align="center">
         <td>Data</td>
         <td>Escola</td>
         <td>Matr.</td>
         <td>Turma</td>
         <td>Ano</td>
         <td>Etapa</td>
         <td>Procedimento</td>
        </tr>
        <?
        for($f=0;$f<count($array_mov);$f++){
        	
          $array_mov1 = explode("|",$array_mov[$f]);
          $array_mov2 = explode("#",$array_mov1[1]);               
          if ($f > 0) {
            ?>
	        <tr><td height="1" bgcolor="black" colspan="7"></td></tr>
            <?
          }
          ?>
          <tr bgcolor="#dbdbdb">
           <td class="aluno2" align="center"><?=$array_mov2[0]?></td>
           <td class="aluno2"><?=$array_mov2[1]?></td>
           <td class="aluno2" align="center"><?=$array_mov2[2]?></td>
           <td class="aluno2" align="center"><?=$array_mov2[3]?></td>
           <td class="aluno2" align="center"><?=$array_mov2[4]?></td>
           <td class="aluno2" align="center"><?=$array_mov2[5]?></td>
           <td class="aluno2"><?=$array_mov2[6]?></td>
	      </tr>
	      <tr>
           <td>&nbsp;</td>
           <td bgcolor="#f3f3f3" colspan="6" class="aluno2">
            <table width="100%">
             <tr>
              <td width="60%">
              <?=$array_mov2[7]?>
             </td>
             <td align="right" valign="top">
              <b>Usuário: </b><?=$array_mov2[8]?>             
             </td>
            </tr>
           </table>
          </td>
         </tr>
        <?
        }
      } else {
        ?>
        <tr>
         <td>
          Nenhum registro.
         </td>
        </tr>
        <?
      }
      ?>
     </table>
    </td>
   </tr>
   </table>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>

const MENSAGEM_MATRICULA004 = "educacao.escola.edu1_matricula004.";

function js_alerta(data,inicio,fim) {
  
  if (document.form1.ed60_d_datamatricula.value == "") {
	  
    alert( _M( MENSAGEM_MATRICULA004 + "informe_data_matricula" ) );
    document.form1.ed60_d_datamatricula.focus();
    document.form1.ed60_d_datamatricula.style.backgroundColor='#99A9AE';
    return false;
    
  } else {
	  
    datamat  = document.form1.ed60_d_datamatricula_ano.value+"-"+document.form1.ed60_d_datamatricula_mes.value;
    datamat += "-"+document.form1.ed60_d_datamatricula_dia.value;
    dataini  = inicio;
    datafim  = fim;
    check    = js_validata(datamat,dataini,datafim);
    
    if (check == false) {
        
      data_ini = dataini.substr(8,2)+"/"+dataini.substr(5,2)+"/"+dataini.substr(0,4);
      data_fim = datafim.substr(8,2)+"/"+datafim.substr(5,2)+"/"+datafim.substr(0,4);
      alert( _M( MENSAGEM_MATRICULA004 + "data_matricula_fora_calendario", {'dtInicio':data_ini,'dtFim':data_fim} ) )
      document.form1.ed60_d_datamatricula.focus();
      document.form1.ed60_d_datamatricula.style.backgroundColor='#99A9AE';
      return false;      
    }
    
    datamatanterior    = document.form1.datamatanterior.value.substr(0,4);
    datamatanterior   += document.form1.datamatanterior.value.substr(5,2);
    datamatanterior   += document.form1.datamatanterior.value.substr(8,2);
    datasaidaanterior  = document.form1.datasaidaanterior.value.substr(0,4);
    datasaidaanterior += document.form1.datasaidaanterior.value.substr(5,2);
    datasaidaanterior += document.form1.datasaidaanterior.value.substr(8,2);
    datamat            = datamat.substr(0,4)+''+datamat.substr(5,2)+''+datamat.substr(8,2);
        
    if (datamatanterior != "") {
        
      if (parseInt(datamatanterior) > parseInt(datamat)) {
          
        data_mat = datamatanterior.substr(6,2)+"/"+datamatanterior.substr(4,2)+"/"+datamatanterior.substr(0,4);
        alert( _M( MENSAGEM_MATRICULA004 + "data_matricula_menor", {'dtMatricula':data_mat}) )
        document.form1.ed60_d_datamatricula.focus();
        document.form1.ed60_d_datamatricula.style.backgroundColor='#99A9AE';
        return false;
        
      }
    } 
    
    if (datasaidaanterior != "") {
        
      if (parseInt(datasaidaanterior) > parseInt(datamat)) {
        data_sai = datasaidaanterior.substr(6,2)+"/"+datasaidaanterior.substr(4,2)+"/"+datasaidaanterior.substr(0,4);
        alert( _M( MENSAGEM_MATRICULA004 + "data_matricula_menor", {'dtMatricula':data_sai}) )
        document.form1.ed60_d_datamatricula.focus();
        document.form1.ed60_d_datamatricula.style.backgroundColor='#99A9AE';
        return false;
      }
    }
  }
  return true;
}

<?if ($ed60_c_concluida == "S") {?>
    alert( _M( MENSAGEM_MATRICULA004 + "matricula_encerrada" ) )
<?}?>

function js_historico(matricula) {
	
  js_OpenJanelaIframe('','db_iframe_historico','edu1_matricula006.php?matricula='+matricula,'Histórico de Matrículas',
		              true,0,0,screen.availWidth-50,screen.availHeight);
  
}

function js_validata(datamat,dataini,datafim) {
  var oDtAlteracao = new Date(datamat);
  var oDtInicio    = new Date(dataini);
  var oDtFim       = new Date(datafim);

  if ( oDtAlteracao.getTime() < oDtInicio.getTime() || oDtAlteracao.getTime() > oDtFim.getTime() ) {
    return false;
  }
  return true;
}

</script>