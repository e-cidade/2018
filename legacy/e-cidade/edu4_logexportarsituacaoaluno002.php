<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("fpdf151/pdf.php"));

$oGet = db_utils::postMemory( $_GET );

/**
 * Cria a instância de Escola para preenchimento do cabeçalho
 */
$oEscola = EscolaRepository::getEscolaByCodigo( db_getsession( "DB_coddepto" ) );
$oJson   = new Services_JSON();

/**
 * Lê o contéudo do arquivo de log gerado
 */
$sArquivoLog  = file_get_contents( "{$oGet->sCaminhoArquivo}" );
$oJsonArquivo = $oJson->decode( $sArquivoLog );

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

  $sMensagem = "Não foram encontrados dados com os filtros informados para geração do arquivo de log.";
  db_redireciona( "db_erros.php?fechar=true&db_erro={$sMensagem}" );
}

/**
 * Dados do cabeçalho
 */
$head1 = "EXPORTAÇÃO SITUAÇÃO DO ALUNO DO CENSO";
$head3 = "ESCOLA: {$oEscola->getCodigo()} - {$oEscola->getNome()}";
$head4 = "ANO: {$oGet->iAno}";
$head6 = "Registros com erros";

/**
 * Cria a instância de PDF e inicializa os métodos padrões
 */
$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true, 20);

$oPdf->SetFont( "arial", "", 7 );
$oPdf->SetFillColor( 225, 225, 225 );

$iContador       = 0;
$iTotalRegistros = count($oJsonArquivo->aLogs);

$iIdentificador = null;

$aRegistros = array(
  89 => "Registro 89 - Dados da Escola",
  90 => "Registro 90 - Alunos admitidos antes da data base do censo",
  91 => "Registro 91 - Alunos admitidos após a data base do censo",
);

foreach($oJsonArquivo->aLogs as $oErro) {

  if ( empty($iIdentificador) || $iIdentificador != $oErro->iIdentificador ) {

    $oPdf->addPage();
    $iIdentificador = $oErro->iIdentificador;
    $oPdf->SetFont( "arial", "B", 7 );
    $oPdf->Cell(192, 4, $aRegistros[$iIdentificador], 0, 1);
    $oPdf->SetFont( "arial", "", 7 );
  }
  $iPreenchimento = 0;

  if ( $iContador % 2 != 0 ) {
    $iPreenchimento = 1;
  }

  $oPdf->MultiCell($iLargura, $iAltura, utf8_decode($oErro->sErro), 0, 'L', $iPreenchimento);
  $iContador++;
}

$oPdf->Output();