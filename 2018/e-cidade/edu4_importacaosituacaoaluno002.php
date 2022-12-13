<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");
require_once ("fpdf151/pdf.php");

$oGet = db_utils::postMemory( $_GET );

/**
 * Cria a instância de Escola para preenchimento do cabeçalho
 */
$oEscola = EscolaRepository::getEscolaByCodigo( db_getsession( "DB_coddepto" ) );

$oJson = new Services_JSON();

/**
 * Lê o contéudo do arquivo de log gerado
 */
$sArquivoLog  = file_get_contents( $oGet->sCaminhoArquivo );
$oJsonArquivo = $oJson->decode( $sArquivoLog );

/**
 * Array para armazenar as mensagens de erro
 * @param array
 */
$aErros = array();

/**
 * Array para armazenar os alunos importados com sucesso
 * @param array
 */
$aSucessos = array();

/**
 * Define Largura e Altura padrões para a linha do arquivo PDF
 */
$iLargura = 192;
$iAltura  = 4;
 
/**
 * Caso o atributo aLogs não tenha sido setado ou não existam logs gerados, apresenta a mensagem e redireciona para 
 * o formulário de importação
 */
if ( !isset( $oJsonArquivo->aLogs ) || count( $oJsonArquivo->aLogs ) == 0 ) {
  
  db_msgbox( "Não foram encontrados dados com os filtros informados para geração do arquivo de log." );
  db_redireciona( "edu4_importacaosituacaoaluno001.php" );
}

/**
 * Percorre os logs gerados, validando o tipo de erro para armazenar no array
 */
foreach ( $oJsonArquivo->aLogs as $oLog ) {

  if ( trim( $oLog->tipo ) == "ERRO" ) {
    $aErros[] = utf8_decode( $oLog->sMensagem );
  }
  
  if ( trim( $oLog->tipo ) == "INFO" ) {
    $aSucessos[] = utf8_decode( $oLog->sMensagem );
  }
}

/**
 * Dados do cabeçalho
 */
$head1 = "IMPORTAÇÃO SITUAÇÃO DO ALUNO DO CENSO";
$head3 = "ESCOLA: {$oEscola->getCodigo()} - {$oEscola->getNome()}";
$head4 = "ANO: {$oGet->iAno}";

/**
 * Cria a instância de PDF e inicializa os métodos padrões
 */
$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();

/**
 * Percorre primeiramente o array com as mensagens de erro
 */
$iTotalErros = count( $aErros );

if ( $iTotalErros > 0 ) {

  $head6 = "Registros com erros";
  $oPdf->AddPage();
  $oPdf->SetFont( "arial", "", 7 );
  $oPdf->SetFillColor( 225, 225, 225 );
  
  for ( $iContador = 0; $iContador < $iTotalErros; $iContador++ ) {
    
    $iPreenchimento = 0;
    
    if ($iContador % 2 != 0) {
      $iPreenchimento = 1;
    }
    
    $oPdf->MultiCell($iLargura, $iAltura, $aErros[$iContador], 0, 'L', $iPreenchimento);
  }
}

/**
 * Percorre os registros importados com sucesso. Inicia a partir de uma nova página
 */
$iTotalSucesso = count( $aSucessos );

if ( $iTotalSucesso > 0 ) {
  
  $head6 = "Alunos Importados com Sucesso";
  
  $oPdf->AddPage();
  $oPdf->SetFont( "arial", "", 7 );
  $oPdf->SetFillColor( 225, 225, 225 );
  
  for ( $iContador = 0; $iContador < $iTotalSucesso; $iContador++ ) {
    
    $iPreenchimento = 0;
    
    if ( $iContador % 2 != 0 ) {
      $iPreenchimento = 1;
    }
    
    $oPdf->MultiCell($iLargura, $iAltura, $aSucessos[$iContador], 0, 'L', $iPreenchimento);
  }
}

$oPdf->Output();
?>