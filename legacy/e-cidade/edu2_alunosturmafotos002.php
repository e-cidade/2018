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
require_once("libs/db_app.utils.php");
require_once("libs/db_stdlib.php");
require_once("dbforms/db_funcoes.php");
require_once("std/DBLargeObject.php");
require_once("std/DBDate.php");
require_once("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once("model/educacao/censo/DadosCenso.model.php");
require_once("classes/db_cursoedu_classe.php");
require_once("model/CgmFactory.model.php");

db_app::import("exceptions.*");
db_app::import("educacao.avaliacao.*");
db_app::import("educacao.censo.*");
db_app::import("educacao.*");

$oDaoRegenciaHorario = db_utils::getDao("regenciahorario");
$oGet                = db_utils::postMemory($_GET);

$aCodigoTurmas = explode(",", $oGet->aTurmas);

$aTurmaAlunos  = array();

foreach ($aCodigoTurmas as $iCodigoTurma) {
  
  $aTurmaAlunos[$iCodigoTurma] = TurmaRepository::getTurmaByCodigo($iCodigoTurma);
}

$oPdf   = new PDF();
foreach ($aTurmaAlunos as $oTurma) {
  
  $oPdf->Open();
  $sFonte = 'arial';
  $head1  = 'Lista de Alunos Matriculados';
  $head2  = "Turma : ".$oTurma->getDescricao();;
  
  $oPdf->SetAutoPageBreak(false);
  $oPdf->AliasNbPages();
  $isPrimeiraPagina       = true;
  $iMaximoAlunoPorLinha   = 4;
  $iMaximoLinhasPorPagina = 5;
  $iProximoAluno          = 10;
  $iTotalAlunosImpressos  = 0;
  $iAlturaAluno           = 40;
  $iSalto                 = 2;
  $iLinhasImpressas       = 0;
  $aListaImagens          = array();
  $aAlunosMatriculados    = $oTurma->getAlunosMatriculados();
  
    
  foreach ($aAlunosMatriculados as $oMatricula) {
    
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
    $oPdf->SetFont($sFonte, "", 6);
    $oPdf->cell(20, 4, $oMatricula->getSituacao(), 0, 1);
    $oPdf->SetFont($sFonte, "", 6);
    $oPdf->Rect($iProximoAluno, $oPdf->getY(), 30, 40);
    
    db_inicio_transacao();
    $sCaminhoFoto    = $oMatricula->getAluno()->getFoto();
    db_fim_transacao();
    $aListaImagens[] = $sCaminhoFoto;
    $oPdf->Image($sCaminhoFoto, $iProximoAluno+1, $oPdf->getY()+1, 28, 38);

    $iAlturaCelula        = 3;
    $iTamanhoCelulaNomes  = 40;
    
    $oPdf->setXY($iProximoAluno, $oPdf->getY() + 40);
    $oPdf->MultiCell($iTamanhoCelulaNomes, $iAlturaCelula, $oMatricula->getAluno()->getCodigoAluno() . " - " . $oMatricula->getAluno()->getNome(), 0, 1);
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