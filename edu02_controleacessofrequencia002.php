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

require_once("fpdf151/pdfwebseller.php");
require_once("libs/db_utils.php");
require_once("libs/db_stdlib.php");
require_once("dbforms/db_funcoes.php");
require_once("std/DBLargeObject.php");


$oDaoRegenciaHorario = db_utils::getDao("regenciahorario");
$oGet     = db_utils::postMemory($_GET);
$sDataDia = implode("-", array_reverse(explode("/", $oGet->data)));

$iEscola  = db_getsession("DB_coddepto");
$iAno     = db_getsession("DB_anousu");
$sCampos  = "ed47_v_nome  as aluno, ";
$sCampos .= "ed47_c_nomeresp as resplegal, ";
$sCampos .= "ed47_i_codigo, ";
$sCampos .= "ed57_i_codigo, ";
$sCampos .= "ed57_c_descr, ";
$sCampos .= "ed47_v_mae as mae, ";
$sCampos .= "ed47_v_pai as pai, ";
$sCampos .= "ed47_o_oid as foto, ";
$sCampos .= "ed47_v_telcel as celular, ";
$sCampos .= "ed47_v_telef as telefoneresidencial, ";
$sCampos .= "array_to_string(array_accum(ed58_i_codigo),',') as horarios";

$sGroupBy  = " group by ed47_v_nome, ";
$sGroupBy .= "          ed47_c_nomeresp, ";
$sGroupBy .= "          ed47_i_codigo, ";
$sGroupBy .= "          ed47_v_mae, ";
$sGroupBy .= "          ed47_v_pai, ";
$sGroupBy .= "          ed47_v_telcel, ";
$sGroupBy .= "          ed47_v_telef,";
$sGroupBy .= "          ed57_i_codigo,";
$sGroupBy .= "          ed47_o_oid,";
$sGroupBy .= "          ed57_c_descr";

$sWhere  = "ed58_i_diasemana  = extract (dow from cast('{$sDataDia}' as date))+1 ";
$sWhere .= "and ed57_i_escola = {$iEscola} and ed52_i_ano = {$iAno} and ed58_ativo is true  ";
if (isset($oGet->sListaTurmas)  && $oGet->sListaTurmas != "") {

  $sListaTurma  = $oGet->sListaTurmas; 
  $sWhere      .= " and ed57_i_codigo in ({$sListaTurma})";
}
$sSqlAlunos  = $oDaoRegenciaHorario->sql_query_diario_classe_matricula (null, 
                                                                        $sCampos, 
                                                                        "ed57_c_descr, ed47_v_nome", 
                                                                        $sWhere.$sGroupBy
                                                                        );

$aAlunos            = array();                                                                                     
$rsAlunosNoDia      = $oDaoRegenciaHorario->sql_record($sSqlAlunos);
$iTotalLinhas       = $oDaoRegenciaHorario->numrows;
$oDaoControleAcesso = db_utils::getDao("controleacessoalunoregistrovalido");
for ($iAluno = 0; $iAluno < $iTotalLinhas; $iAluno++) {

  $oAluno        = db_utils::fieldsMemory($rsAlunosNoDia, $iAluno);
  $oAluno->comLeiuturaFalta    = "0";
  $oAluno->semLeiuturaPresente = "0";
  $oAluno->chamadafechada      = false;
  $oAluno->horarios            = urldecode($oAluno->horarios);
  $sWhereFaltas  =  "     ed101_dataleitura = cast('{$sDataDia}' as date) ";
  $sWhereFaltas .=  " and ed60_i_turma = {$oAluno->ed57_i_codigo} "; 
  $sWhereFaltas .= "  and ed60_i_aluno = {$oAluno->ed47_i_codigo} ";      
  $sWhereFaltas .=  " and exists (select 1 ";
  $sWhereFaltas .=  "              from diarioclassealunofalta ";
  $sWhereFaltas .=  "                   inner join diarioclasseregenciahorario on ed301_diarioclasseregenciahorario  = ed302_sequencial ";
  $sWhereFaltas .=  "                   inner join diarioclasse on ed302_diarioclasse = ed300_sequencial ";
  $sWhereFaltas .=  "             where ed300_datalancamento  = cast('{$sDataDia}' as date) ";
  $sWhereFaltas .=  "               and ed302_regenciahorario in({$oAluno->horarios}) ";
  $sWhereFaltas .=  "               and ed301_aluno = ed303_aluno)";
                   
  $sSqlTotalLeituraeFalta = $oDaoControleAcesso->sql_query_controle_acesso(null, 
                                                                           "count(*) as total", 
                                                                           null, 
                                                                           $sWhereFaltas);
                                                                           
  $rsTotalLeituraeFalta   = $oDaoControleAcesso->sql_record($sSqlTotalLeituraeFalta);
  if ($oDaoControleAcesso->numrows > 0) {
    $oAluno->comLeiuturaFalta  = db_utils::fieldsMemory($rsTotalLeituraeFalta, 0)->total;
  }
  
  /**
   * Calculamos o total de alunos que estao presentes na turma
   */
  $sWherePresentes  = " ed58_i_codigo in({$oAluno->horarios})";
  $sWherePresentes .= " and ed60_i_aluno = {$oAluno->ed47_i_codigo}";      
  $sWherePresentes .= " and  not exists (select 1  ";
  $sWherePresentes .= "                   from  controleacessoalunoregistrovalido ";
  $sWherePresentes .= "                      inner join controleacessoalunoregistro on ed303_controleacessoalunoregistro = ed101_sequencial ";
  $sWherePresentes .= "                where ed101_dataleitura = cast('{$sDataDia}' as date)  ";
  $sWherePresentes .= "                  and ed303_aluno = ed60_i_aluno)";
  
  $sWherePresentes .= "and not exists "; 
  $sWherePresentes .= "   (select 1 ";
  $sWherePresentes .= "      from diarioclassealunofalta ";
  $sWherePresentes .= "           inner join diarioclasseregenciahorario on ed301_diarioclasseregenciahorario  = ed302_sequencial ";
  $sWherePresentes .= "           inner join diarioclasse on ed302_diarioclasse = ed300_sequencial ";
  $sWherePresentes .= "     where ed300_datalancamento  = cast('{$sDataDia}' as date) and ed58_ativo is true  ";
  $sWherePresentes .= "       and ed302_regenciahorario in({$oAluno->horarios}) ";
  $sWherePresentes .= "       and ed301_aluno           = ed60_i_aluno) ";
  $sSqlQueryPresentesSemLeitura = $oDaoRegenciaHorario->sql_query_regencia_horario_matricula(null, 
                                                                                             "count(distinct ed60_matricula) as total", 
                                                                                             null, 
                                                                                             $sWherePresentes
                                                                                             );
  $rsTotalPresentesSemLeitura = $oDaoRegenciaHorario->sql_record($sSqlQueryPresentesSemLeitura);                                                                                                 
  if ($oDaoRegenciaHorario->numrows > 0) {
    $oAluno->semLeiuturaPresente = db_utils::fieldsMemory($rsTotalPresentesSemLeitura, 0)->total;
  }
  
  /**
   * Verificamos se a chamada está fechada
   */
  $oDaoDiarioclasseHorario = db_utils::getDao("diarioclasseregenciahorario");
  $sWhereChamada           = " ed300_datalancamento = cast('{$sDataDia}' as date) ";
  $sWhereChamada          .= " and ed302_regenciahorario in({$oAluno->horarios}) ";
  $sSqlChamadaFechada      = $oDaoDiarioclasseHorario->sql_query_diario_classe(null, "1", null, $sWhereChamada);
  $rsChamadaFechada        = $oDaoDiarioclasseHorario->sql_record($sSqlChamadaFechada);
  if ($oDaoDiarioclasseHorario->numrows > 0) {
    $oAluno->chamadafechada = true;
  }
  if (!$oAluno->chamadafechada) {
    $oAluno->semLeiuturaPresente = '0';
  }
  
  unset($oAluno->horarios);
  if ($oGet->iFiltroAluno == 2 && $oAluno->comLeiuturaFalta == 0) {
    continue;
  }
  if ($oGet->iFiltroAluno == 3 && $oAluno->semLeiuturaPresente == 0) {
    continue;
  } else if ($oAluno->semLeiuturaPresente == 0 && $oAluno->comLeiuturaFalta == 0) {
    continue;   
  }
  
  if ($oAluno->comLeiuturaFalta > 0) {
    $sTipoAluno = 2;
    
  } else if ($oAluno->semLeiuturaPresente > 0) {
    $sTipoAluno = 3;
  }
  $aAlunos[$sTipoAluno][]  = $oAluno;
}

$oPdf   = new PDF();
$oPdf->Open();
$sFonte = 'arial';
$head1  = 'Controle Acesso/Frenquencia Alunos';
$head2  = "Data:{$oGet->data}";

$oPdf->SetAutoPageBreak(false);
$oPdf->AliasNbPages();
$isPrimeiraPagina     = true;
$iMaximoAlunoPorLinha = 4;
if ($oGet->iModelo == 2) {
  $iMaximoAlunoPorLinha = 2;  
}
$iMaximoLinhasPorPagina = 5;
$iProximoAluno          = 10;
$iTotalAlunosImpressos  = 0;
$iAlturaAluno           = 40;
$iSalto                 = 2;
$iLinhasImpressas       = 0;
$aListaImagens          = array(); 
foreach ($aAlunos as $iTipoAluno => $aListaAluno) {
   
  /**
   * mostramos apenas os alunos conforme o usuario selecionou: 
   * 1 - Apenas com leitura e Falta
   * 2 - Apenas sem leitura e presente em aula
   * 3 - Ambos
   */
  if ($oGet->iFiltroAluno != 1) {
    
    if ($iTipoAluno != $oGet->iFiltroAluno) {
      continue;
    }
  } else {
    
    $isPrimeiraPagina = true;
    $iLinhasImpressas = 0;
    $iTotalAlunosImpressos = 0;
  }
  if ($iTipoAluno == 2) {
    $head3 = "Alunos com leitura do cartão e com falta";
  } else {
    $head3 = "Alunos presentes sala de aula sem leitura do cartão";
  }
  foreach ($aListaAluno as $oAluno) {

    if ($oPdf->GetY() > $oPdf->h - 30 || $isPrimeiraPagina || $iLinhasImpressas == 5) {
  
      if ($iLinhasImpressas == 5) {
        $iLinhasImpressas = 0;
      }
      $oPdf->AddPage();
      $iAlturaAluno     = $oPdf->GetY();
      if ($isPrimeiraPagina) {
        $iProximoAluno    = 10;
      }
      $isPrimeiraPagina = false;
      $iSalto           = 2;
    }
    if ($iTotalAlunosImpressos % $iMaximoAlunoPorLinha == 0) {
      
      $iAlturaAluno  = $oPdf->getY()+$iSalto;
      $oPdf->SetXY(10, $iAlturaAluno);
      $iProximoAluno = 10;
      $iSalto        = 50;
    }
    $oPdf->SetXY($iProximoAluno, $iAlturaAluno);
    $oPdf->SetFont($sFonte, "b", 8);  
    $oPdf->cell(20, 4, "Turma:".$oAluno->ed57_c_descr, 0, 1);
    $oPdf->SetFont($sFonte, "", 6);
    $oPdf->Rect($iProximoAluno, $oPdf->getY(), 30, 40);
    db_inicio_transacao();
    $sCaminhoImagem = "/tmp/foto_".db_getsession("DB_id_usuario")."_{$oAluno->ed47_i_codigo}.jpg";
    if ($oAluno->foto != "0" && DBLargeObject::leitura($oAluno->foto, $sCaminhoImagem)) {
      
      $aListaImagens[] = $sCaminhoImagem;
      $oPdf->Image($sCaminhoImagem, $iProximoAluno+1, $oPdf->getY()+1, 28, 38);
    }
    db_fim_transacao(true);
    if ($oGet->iModelo == 2) {
      
      $iTamanhoCelulaNomes  = 60;
      $iAlturaCelula        = 4;
      $oPdf->setXY($iProximoAluno + 32, $oPdf->getY());
    } else {
      
      $iAlturaCelula        = 3;
      $iTamanhoCelulaNomes  = 40;
      $oPdf->setXY($iProximoAluno, $oPdf->getY() + 40);
    }
    $oPdf->MultiCell($iTamanhoCelulaNomes, $iAlturaCelula, $oAluno->aluno, 0, 1);
    if ($oGet->iModelo == 2) {
      
      $oPdf->setXY($iProximoAluno + 32, $oPdf->getY());
      $oPdf->MultiCell($iTamanhoCelulaNomes, $iAlturaCelula, "Pai: {$oAluno->pai}", 0, 1);
      $oPdf->setXY($iProximoAluno + 32, $oPdf->getY());
      $oPdf->MultiCell($iTamanhoCelulaNomes, $iAlturaCelula, "Mae: {$oAluno->mae}", 0, 1);
      $oPdf->setXY($iProximoAluno + 32, $oPdf->getY());
      $oPdf->MultiCell($iTamanhoCelulaNomes, $iAlturaCelula, "Resp.: {$oAluno->resplegal}", 0, 1);
      $oPdf->setXY($iProximoAluno + 32, $oPdf->getY());
      $sFones = $oAluno->celular;
      if (trim($oAluno->telefoneresidencial)  != "" && trim($oAluno->celular) != "") {
        $sFones .= " / ";
      }
      $sFones .= $oAluno->telefoneresidencial;
      $oPdf->MultiCell($iTamanhoCelulaNomes, $iAlturaCelula, "Fones: {$sFones}", 0, 1);   
    }
    $iProximoAluno += (200 / $iMaximoAlunoPorLinha);
    $iTotalAlunosImpressos++;
    $oPdf->SetY($iAlturaAluno);  
    if ($iTotalAlunosImpressos % $iMaximoAlunoPorLinha == 0) {
      if ($iTotalAlunosImpressos > 0) {
        $iLinhasImpressas++;
      }
    }
  }
}

$oPdf->Output();
foreach ($aListaImagens as $sImagem) {
  unlink($sImagem);
}
?>