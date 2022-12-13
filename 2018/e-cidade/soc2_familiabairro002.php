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

/**
 * @author Andrio Costa  andrio.costa@dbseller.com.br
 * @version $Revision: 1.4 $
 */
require_once("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once ("std/DBDate.php");

db_app::import("social.*");
db_app::import("social.cadastrounico.*");
db_app::import("exceptions.*");


$oGet        = db_utils::postMemory($_GET);
$aBairros    = explode(",", $oGet->sBairros);
$sBairros    = "'".implode("','", $aBairros)."'";

/**
 * Buscamos os codigos sequenciais das familias 
 */
$oDaoFamilia = db_utils::getDao('cidadaofamilia');
$sWhere      = " to_ascii(trim(cidadao.ov02_bairro)) in ({$sBairros}) ";
$sWhere     .= " group by  as04_sequencial, cidadao.ov02_bairro " ;
$sOrder      = " to_ascii(trim(cidadao.ov02_bairro)) ";
$sSqlFamilia = $oDaoFamilia->sql_query_completa(null, " as04_sequencial", $sOrder, $sWhere);
$rsFamilia   = $oDaoFamilia->sql_record($sSqlFamilia);
$iLinhas     = $oDaoFamilia->numrows;
$aFamilias   = array();

if ($iLinhas == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhuma família encontrada.");
}

/**
 * Criamos o Objeto da Familia
 */
for ($i = 0; $i < $iLinhas; $i++) {
  
  $iCodigoFamilia = db_utils::fieldsMemory($rsFamilia, $i)->as04_sequencial;
  $aFamilias[]    = new Familia($iCodigoFamilia);
}


$iHeigth        = 4;
$lPrimeiroLaco  = true;
$oPdf           = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFont('arial', '', 7);

$head1 = "Famílias por Bairro";
$head2 = "Famílias: Todas";

$iTotalRegistros = 0;


/**
 * iTipoFamilia é um novo parâmetro passado ao relatório
 * 
 * iTipoFamilia == 1 imprime todas as famílias retornadas na query $sSqlFamilia
 * iTipoFamilia == 2 imprime as famílias que possuem benefícios 
 */

$aSituacoesValidas = array('EM PAGAMENTO', 'LIBERADO', 'CONCEDIDO');
if ($oGet->iTipoFamilia == 2 ) {
  
  $head2 = "Famílias: Somente com Benefícios";
  
  $aArrayAuxiliarFamilia = array();
  
  foreach ($aFamilias as $oFamilia) {
    
    if (count($oFamilia->getListaBeneficios()) > 0) {
      
      $lAdicionar = false;
      foreach ($oFamilia->getListaBeneficios() as $oBeneficio) {
      
        if (in_array($oBeneficio->situacao, $aSituacoesValidas)) {
          $lAdicionar = true;
        }
      }
      if($lAdicionar) {
        $aArrayAuxiliarFamilia[] = $oFamilia;
      }
    }
  }
  
  unset($aFamilias);
  $aFamilias = $aArrayAuxiliarFamilia;
}


/**
 * Percorremos as Familias imprimindo os valores solicidado no relatorio
 */
foreach ($aFamilias as $oFamilia) {

  if ($oFamilia->getResponsavel() == null) {
    continue;
  }
  $sNIS           = $oFamilia->getResponsavel()->getNis();
  $iCodigoFamilia = $oFamilia->getCodigoFamiliarCadastroUnico();
  $sResponsavel   = $oFamilia->getResponsavel()->getNome();
  $oTelefone      = $oFamilia->getResponsavel()->getTelefonePrincipal();
  
  $sTelefone = "";
  if (!empty($oTelefone)) {
    $sTelefone = "(".$oTelefone->getDDD() . ") " . $oTelefone->getNumeroTelefone();
  }
  $sBairro        = $oFamilia->getResponsavel()->getBairro();
  $sEndereco      = $oFamilia->getResponsavel()->getEndereco() . ", ". $oFamilia->getResponsavel()->getNumero();
  if ($oPdf->gety() > $oPdf->h - 15 || $lPrimeiroLaco ) {
  
    setHeader($oPdf, $iHeigth);
    $lPrimeiroLaco = false;
  } 
  
  $oPdf->SetFont('arial', '', 6);
  $oPdf->Cell(25,  $iHeigth, "{$sNIS}",                 "TBR",  0);
  $oPdf->Cell(25,  $iHeigth, "{$iCodigoFamilia}",       "TBRL", 0);
  $oPdf->Cell(80,  $iHeigth, "{$sResponsavel}",         "TBRL", 0);
  $oPdf->Cell(25,  $iHeigth, "{$sTelefone}",            "TBRL", 0);
  $oPdf->Cell(55,  $iHeigth, substr($sBairro, 0, 40) ,  "TBRL", 0);
  $oPdf->Cell(70,  $iHeigth, substr($sEndereco, 0, 60), "LTB",  1);
  
  $iTotalRegistros ++;
}
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(240, $iHeigth, "Total de Registros:", "TBR",  0, "R");
$oPdf->Cell(40,  $iHeigth, $iTotalRegistros,      "LTB",  1);

/**
 * Criamos o cabeçalho do relatorio
 * @param FPDF $oPdf
 * @param integer $iHeigth
 */
function setHeader($oPdf, $iHeigth) {
  
  $oPdf->setfillcolor(235);
  $oPdf->AddPage("L");
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(25,  $iHeigth, "NIS",           1, 0, "C", 1);
  $oPdf->Cell(25,  $iHeigth, "Cód. Familiar", 1, 0, "C", 1);
  $oPdf->Cell(80,  $iHeigth, "Responsável",   1, 0, "C", 1);
  $oPdf->Cell(25,  $iHeigth, "Telefone",      1, 0, "C", 1);
  $oPdf->Cell(55,  $iHeigth, "Bairro",        1, 0, "C", 1);
  $oPdf->Cell(70,  $iHeigth, "Endereço",      1, 1, "C", 1);
  
}
$oPdf->Output();