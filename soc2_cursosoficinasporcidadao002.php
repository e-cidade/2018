<?php
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

require_once ("fpdf151/pdf.php");
require_once ("std/DBDate.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/exceptions/ParameterException.php");

$oParam      = db_utils::postMemory($_GET);
$oCidadao    = CidadaoRepository::getCidadaoByCodigo($oParam->iCidadao);
$aCursos     = array();
$sDataInicio = '';
$sDataFinal  = '';

/**
 * Montamos o where de acordo com os parametros enviados via $_GET
 */
$aWhereData = array();
$sWhereData = '';

if (!empty($oParam->sDataInicial)) {
  
  $sDataInicio  = $oParam->sDataInicial;
  $oDataInicio  = new DBDate($oParam->sDataInicial);
  $aWhereData[] = "as19_inicio >= '{$oDataInicio->getDate(DBDate::DATA_EN)}'";
}

if (!empty($oParam->sDataFinal)) {

  $sDataFinal   = $oParam->sDataFinal;
  $oDataFinal   = new DBDate($oParam->sDataFinal);
  $aWhereData[] = "as19_fim <= '{$oDataFinal->getDate(DBDate::DATA_EN)}'";
}

$sWhereData    = implode(" and ", $aWhereData);
$sWhereCidadao = "as22_cidadao = {$oCidadao->getCodigo()} and as22_cidadao_seq = {$oCidadao->getSequencialInterno()}";
$aWhere[]      = $sWhereCidadao;

if (!empty($sWhereData)) {
  $aWhere[] = $sWhereData;
}

/**
 * Buscamos os cursos os quais o cidadao esta vinculado de acordo com os filtros selecionados
 */
$oDaoCursoSocialCidadao   = new cl_cursosocialcidadao();
$sWhereCursoSocialCidadao = implode(" and ", $aWhere);
$sSqlCursoSocialCidadao   = $oDaoCursoSocialCidadao->sql_query_cursocidadao(
                                                                             null, 
                                                                             "as22_cursosocial", 
                                                                             null, 
                                                                             $sWhereCursoSocialCidadao
                                                                           );
$rsCursoSocialCidadao      = $oDaoCursoSocialCidadao->sql_record($sSqlCursoSocialCidadao);
$iLinhasCursoSocialCidadao = $oDaoCursoSocialCidadao->numrows;

if ($iLinhasCursoSocialCidadao > 0) {
  
  for ($iContador = 0; $iContador < $iLinhasCursoSocialCidadao; $iContador++) {
    
    $iCursoSocial            = db_utils::fieldsMemory($rsCursoSocialCidadao, $iContador)->as22_cursosocial;
    $oCursoSocial            = CursoSocialRepository::getCursoSocialByCodigo($iCursoSocial);
    $oDadosCurso             = new stdClass();
    $oDadosCurso->sDescricao = $oCursoSocial->getNome();
    $oDadosCurso->sTipoCurso = $oCursoSocial->getCategoria()->getDescricao();
    $oDadosCurso->dtInicio   = $oCursoSocial->getDataInicio()->getDate(DBDate::DATA_PTBR);
    $oDadosCurso->dtFim      = $oCursoSocial->getDataFim()->getDate(DBDate::DATA_PTBR);
    $aCursos[]               = $oDadosCurso;
  }
} else {
  
  $sMsgErro = "ERRO: Não foram encontrados cursos aos quais o cidadão informado esteja vinculado.<br>";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
}

$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->SetFillColor(225);

/**
 * Cabecalho do relatorio
 */
$head1 = "Cursos/Oficinas por Cidadão";
$head2 = "Filtros";
$head3 = "- Data de Início: {$sDataInicio}";
$head4 = "- Data de Fim: {$sDataFinal}";

$oPdf->AddPage();
$oPdf->SetFont('arial', 'b', 7);

$oPdf->Cell(192, 4, $oCidadao->getNome(), 1, 1, 'L', 1);

/**
 * Percorremos os cursos vinculados ao cidadao
 */
foreach ($aCursos as $oCurso) {

  if (($oPdf->GetY() > $oPdf->h - 15)) {
    $oPdf->AddPage();
  }
  
  $oPdf->SetFont('arial', '', 6);
  $oPdf->Cell(82, 4, $oCurso->sDescricao, 1, 0, 'L');
  $oPdf->Cell(70,  4, $oCurso->sTipoCurso, 1, 0, 'L');
  
  $sPeriodo = "Período: {$oCurso->dtInicio} até {$oCurso->dtFim}";
  $oPdf->Cell(40,  4, $sPeriodo, 1, 1, 'L');
}

/**
 * Imprime o total de cursos realizados
 */
$oPdf->SetFont('arial', 'b', 7);
$oPdf->Cell(192, 4, "Total de Cursos Realizados: {$iLinhasCursoSocialCidadao}", 1, 1, 'R', 1);

$oPdf->Output();
?>