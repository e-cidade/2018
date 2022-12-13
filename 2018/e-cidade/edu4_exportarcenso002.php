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

require_once("libs/JSON.php");
require_once("fpdf151/pdfwebseller.php");

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->ln(5);

$oGet  = db_utils::postMemory($_GET);
$oJson = new services_json();

if ( !file_exists( $oGet->arquivo_erro )) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foi possível gerar arquivo de log.');
}

$oArquivoErro = $oJson->decode( file_get_contents($oGet->arquivo_erro) );

foreach( $oArquivoErro as $iIndice => $aMensagens ) {

  $sTitulo = "";

  switch( $iIndice ) {

    case "1" :

      $sTitulo = "Inconsistências encontradas na Escola";
      break;

    case "2" :

      $sTitulo = "Inconsistências encontradas na Turma";
      break;

    case "3" :
      $sTitulo = "Inconsistências encontradas no Docente";
      break;

    case "4" :

      $sTitulo = "Inconsistências encontradas no Aluno";
      break;
  }

  escreveLog($oPdf, $sTitulo, $aMensagens);
}

function escreveLog( FPDF $oPdf, $sTitulo, $aMensagens) {

  escreveTitulo( $oPdf, $sTitulo );

  foreach( $aMensagens as $i => $sMensagem) {

    if ($oPdf->gety() > $oPdf->h - 30) {
      escreveTitulo( $oPdf, $sTitulo );
    }

    $iCor = ($i % 2 == 0) ? 0 : 1;
    $oPdf->Multicell(280, 4, trim( urldecode($sMensagem) ), 1, "J", $iCor, 0);
  }
}

function escreveTitulo(FPDF $oPdf, $sTitulo) {

  $oPdf->addpage('L');
  $oPdf->setfillcolor(220);
  $oPdf->setfont('arial','b',8);
  $oPdf->Cell(280, 4, $sTitulo,  1, 1, "L", 1);
  $oPdf->setfillcolor(240);
  $oPdf->setfont('arial','',7);
}
$oPdf->Output();