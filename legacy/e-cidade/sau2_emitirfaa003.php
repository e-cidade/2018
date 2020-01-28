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

require_once("fpdf151/scpdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_stdlibwebseller.php");

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

set_time_limit(0);

$clprontuarios = new cl_prontuarios;
$clprontproced = new cl_prontproced;
$clcgs_und     = new cl_cgs_und;

//Quebra e verifica se a chave do prontuario for multipla para imprimir varios
$aChaveProntuarios = explode(",",$chave_sd29_i_prontuario);
$iTam              = count($aChaveProntuarios);
$result            = array();
$linhas            = array();

for( $iX = 0; $iX < $iTam; $iX++ ) {

  $sSql             = $clprontproced->sql_query( null, "*", null, "sd29_i_prontuario = {$aChaveProntuarios[$iX]}" );
  $result_proc[$iX] = $clprontproced->sql_record($sSql);
  $linhas_proc[$iX] = $clprontproced->numrows;

  $sSql        = $clprontuarios->sql_query($aChaveProntuarios[$iX]);
  $result[$iX] = $clprontuarios->sql_record($sSql);
  $linhas[$iX] = $clprontuarios->numrows;
}

if( in_array( 0, $linhas ) ) {

  echo "<table width='100%'>
         <tr>
          <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
         </tr>
        </table>";
  exit;
}

$pdf = new SCPDF();
$pdf->Open();
$pdf->AliasNbPages();

for( $iX = 0; $iX < $iTam; $iX++ ) {

  db_fieldsmemory( $result[$iX], 0 );

  $result_cgs = $clcgs_und->sql_record( $clcgs_und->sql_query( $sd24_i_numcgs ) );
  db_fieldsmemory( $result_cgs, 0 );

  $sSqlCgsUnd  = "select unidades.*, cgm.z01_nome as estabelecimento,";
  $sSqlCgsUnd .= "       cgm.z01_ender as est_ender,";
  $sSqlCgsUnd .= "       cgm.z01_bairro as est_bairro,";
  $sSqlCgsUnd .= "       cgm.z01_munic as est_munic,";
  $sSqlCgsUnd .= "       cgm.z01_uf as est_uf";
  $sSqlCgsUnd .= "  from unidades";
  $sSqlCgsUnd .= "       inner join db_depart on db_depart.coddepto = unidades.sd02_i_codigo";
  $sSqlCgsUnd .= "       inner join cgm       on cgm.z01_numcgm     = unidades.sd02_i_numcgm";
  $sSqlCgsUnd .= " where unidades.sd02_i_codigo = {$sd24_i_unidade}";
  $result_und = $clcgs_und->sql_record( $sSqlCgsUnd );

  $pdf->setfillcolor(243);
  $pdf->addpage('P');
  $pdf->ln(0);
  $pdf->SetLineWidth(0.5);

  //Posições
  $alt = $pdf->getY();
  $lar = $pdf->getX();

  $setY = 0;

  for( $y=1; $y<=2; $y++ ) {

    $alt = $pdf->setY($setY);
    $lar = $pdf->setX(0);

    //Linha 1 - 1Âª Retangulo SIA/SUA
    $pdf->rect( $pdf->getX(),     $pdf->getY(),     56, 16, "D" );
    $pdf->rect( $pdf->getX() + 6, $pdf->getY() + 1,  5, 14, "D" );
    $pdf->rect( $pdf->getX() + 1, $pdf->getY() + 6, 15,  5, "D" );

    $pdf->setfont( 'arial', 'b', 8 );
    $pdf->text( $pdf->getX() + 17, $pdf->getY() + 3, "SIAA/SUS-RS" );//nome biblioteca

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 17, $pdf->getY() +  8, "SISTEMA DE INFORMAÇÕES" );//nome biblioteca
    $pdf->text( $pdf->getX() + 17, $pdf->getY() + 11, "AMBULATORIAIS" );//nome biblioteca
    $pdf->text( $pdf->getX() + 17, $pdf->getY() + 14, "DO SISTEMA ÙNICO DE SAÚDE" );//nome biblioteca

    //Linha 1 - 2Âº Retangulo
    $pdf->setfont( 'arial', 'b', 12 );
    $pdf->rect( $pdf->getX() + 58, $pdf->getY(), 56, 16, "D" );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->text( $pdf->getX() + 60,$pdf->getY() + 3, "NÚMERO DO ATENDIMENTO" );

    $pdf->setfont( 'arial', 'b', 8 );
    $pdf->text( $pdf->getX() + 60, $pdf->getY() + 7, str_pad( $sd24_i_codigo, 40, " ", STR_PAD_LEFT ) );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->text( $pdf->getX() + 60, $pdf->getY() + 11, "FAMÍLIA PSF:" . $sd33_v_descricao );
    $pdf->text( $pdf->getX() + 60, $pdf->getY() + 14, "MICROÁREA:" . $sd34_v_descricao );

    //Linha 1 - 3Â° Retangulo
    $pdf->rect( $pdf->getX() + 116, $pdf->getY(), 94, 7, "D" );

    $pdf->setfont( 'arial', 'b', 10 );
    $pdf->text( $pdf->getX() + 116 + 10, $pdf->getY() + 5, "FICHA DE ATENDIMENTO AMBULATORIAL" );
    $pdf->rect( $pdf->getX() + 116,      $pdf->getY() + 9, 94, 7, "D");

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 116 + 1, $pdf->getY() + 11, "MOTIVO DO ATENDIMENTO" );
    $pdf->text( $pdf->getX() + 116 + 1, $pdf->getY() + 14, $s144_c_descr );

    //Linha 2 - 1Âª Retangulo Unidade prestadora
    db_fieldsmemory( $result_und, 0 );
    $pdf->rect( $pdf->getX(), $pdf->getY() + 18, 56, 48, "D" );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 2, $pdf->getY() + 20, "UNIDADE PRESTADORA DE ATENDIMENTO" );
    $alt = $pdf->getY() + 5;
    $pdf->setfont( 'arial', 'b', 7 );

    $pdf->text( $pdf->getX() + 2, $alt + 23, "NOME DA UNIDADE: ".substr( $descrdepto, 0, 17 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 26, substr( $descrdepto, 17, 40 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 29, "ENDEREÇO: ".substr( $est_ender, 0, 40 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 32, substr( $est_ender, 41, 40 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 35, "MUNICÍPIO: ".substr( $est_munic, 0, 40 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 38, substr( $est_munic, 41, 40 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 41, "UF:" . $est_uf );
    $pdf->text( $pdf->getX() + 2, $alt + 46, "CÓDIGO SIA/SUS:" . $sd02_c_siasus );

    //Linha 2 - 2Âª Retangulo CBO
    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->rect( $pdf->getX() + 58, $pdf->getY() + 18, 19, 99, "D" );
    $pdf->text( $pdf->getX() + 60, $pdf->getY() + 20, "CBO" );
    $pdf->line( $pdf->getX() + 58, $pdf->getY() + 24, $pdf->getX() + 58 + 19, $pdf->getY() + 24 );

    //Linha 2 - 3Âª Retangulo Procedimentos
    $pdf->rect( $pdf->getX() + 79, $pdf->getY() + 18, 35, 99, "D" );
    $pdf->text( $pdf->getX() + 80, $pdf->getY() + 20, "CÓDIGO TABELA DE" );
    $pdf->text( $pdf->getX() + 80, $pdf->getY() + 23, "PROCEDIMENTO SIA/SUS" );
    $pdf->line( $pdf->getX() + 79, $pdf->getY() + 24, $pdf->getX() + 74 + 40, $pdf->getY() + 24 );

    //Linha 2 - 4Âª Retangulo Procedimentos
    $pdf->rect( $pdf->getX() + 116, $pdf->getY() + 18, 92, 99, "D" );
    $pdf->text( $pdf->getX() + 116, $pdf->getY() + 21, "TRATAMENTO/DESCRIÇÃO" );
    $pdf->text( $pdf->getX() + 116, $pdf->getY() + 23, "DOS PROCEDIMENTOS" );
    $pdf->line( $pdf->getX() + 116, $pdf->getY() + 24, $pdf->getX() + 116 + 92, $pdf->getY() + 24 );

    //Linha 2 - 5Âª Retangulo Procedimentos
    $pdf->rect( $pdf->getX() + 170, $pdf->getY() + 18, 15, 99, "D" );
    $pdf->text( $pdf->getX() + 170, $pdf->getY() + 20, "DATA DO" );
    $pdf->text( $pdf->getX() + 170, $pdf->getY() + 23, "ATENDIMENTO" );
    $pdf->text( $pdf->getX() + 186, $pdf->getY() + 20, "ASSIN.DO PACIENTE/" );
    $pdf->text( $pdf->getX() + 186, $pdf->getY() + 23, "RESPONSÁVEL" );

    $pdf->line( $pdf->getX() + 58,  $pdf->getY() + 27, $pdf->getX() + 58 + 19,  $pdf->getY() + 27 );
    $pdf->line( $pdf->getX() + 79,  $pdf->getY() + 27, $pdf->getX() + 74 + 40,  $pdf->getY() + 27 );
    $pdf->line( $pdf->getX() + 116, $pdf->getY() + 27, $pdf->getX() + 116 + 92, $pdf->getY() + 27 );

    //Prontuário / Procedimentos
    $alt = $pdf->getY();
    $lar = $pdf->getX();
    $pdf->setY( $alt + 28 );

    for( $x = 0; $x < $linhas_proc[$iX]; $x++ ) {

      $pdf->setX( $lar + 58 );
      db_fieldsmemory( $result_proc[$iX], $x );

      $sSqlCgsUnd  = "select cgm.z01_nome as profissional";
      $sSqlCgsUnd .= "  from cgm";
      $sSqlCgsUnd .= "       inner join medicos        on medicos.sd03_i_cgm           = cgm.z01_numcgm";
      $sSqlCgsUnd .= "       inner join unidademedicos on unidademedicos.sd04_i_medico = medicos.sd03_i_codigo";
      $sSqlCgsUnd .= "       inner join especmedico    on especmedico.sd27_i_undmed    = unidademedicos.sd04_i_codigo";
      $sSqlCgsUnd .= " where especmedico.sd27_i_codigo = {$sd29_i_profissional}";
      $result_prof = $clcgs_und->sql_record( $sSqlCgsUnd );
      db_fieldsmemory( $result_prof, 0 );

      $pdf->setfont( 'arial', '', 7 );
      $pdf->SetWidths( array( 19, 38, 55, 53, 24 ) );
      $pdf->SetAligns( array( "C", "C", "L", "J", "L" ) );

      $nbx = "";
      $sd24_d_cadastro2 = substr( $sd24_d_cadastro, 8, 2) . "/" . substr( $sd24_d_cadastro, 5, 2 ) . "/" . substr( $sd24_d_cadastro, 0, 4 );
      $pdf->Row( array( $rh70_estrutural, $sd63_c_procedimento, $sd63_c_nome, $sd24_d_cadastro2, $nbx ), 3, false, 3 );
      $pdf->line( $lar + 58,  $pdf->getY(), $lar +58 + 19,  $pdf->getY() );
      $pdf->line( $lar + 79,  $pdf->getY(), $lar +74 + 40,  $pdf->getY() );
      $pdf->line( $lar + 116, $pdf->getY(), $lar +116 + 92, $pdf->getY() );
    }

    $pdf->setY($alt);
    $pdf->setX($lar);

    //Linha 3 - 1Âª Retangulo Unidade prestadora
    db_fieldsmemory( $result_cgs, 0 );

    $sexo  = array( "F" => "Feminino", "M" => "Masculino" );
    $dia   = substr( $z01_d_nasc, 8, 2 );
    $mes   = substr( $z01_d_nasc, 5, 2 );
    $ano   = substr( $z01_d_nasc, 0, 4 );
    $idade = calcage( $dia, $mes, $ano, date("d"), date("m"), date("Y") );

    $pdf->rect( $pdf->getX(), $pdf->getY() + 68, 56, 61, "D" );
    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 2, $pdf->getY() + 70, "IDENTIFICAÇÃO DO PACIENTE" );

    $pdf->setfont( 'arial', 'b', 7 );
    $alt = $pdf->getY()+50+5;

    $alt = $pdf->getY();
    $lar = $pdf->getX();

    $pdf->setY( $alt + 73 );
    $pdf->setX( $lar + 1 );
    $pdf->MultiCell( 55, 4, "NOME: " . $z01_i_numcgs . "-" . $z01_v_nome, 0, "L" );
    $pdf->setY($alt );
    $pdf->setX($lar );

    $alt = $pdf->getY() + 50 + 5;

    $pdf->text( $pdf->getX() + 2, $alt + 29, "ENDEREÇO: " . substr( $z01_v_ender . ", " . $z01_i_numero . ", " . $z01_v_compl, 0, 21 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 32, substr( $z01_v_ender . ", " . $z01_i_numero . ", " . $z01_v_compl, 21, 40 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 35, "BAIRRO: " . $z01_v_bairro );
    $pdf->text( $pdf->getX() + 2, $alt + 41, "MUNICÍPIO:" . substr( $z01_v_munic, 0, 40 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 44, substr( $z01_v_munic, 40, 40 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 47, "UF:" . $est_uf . " IDADE:" . $idade );
    $pdf->text( $pdf->getX() + 2, $alt + 53, "SEXO: " . $sexo[$z01_v_sexo] );
    $pdf->text( $pdf->getX() + 2, $alt + 59, "DATA NASC:" . $dia . "/" . $mes . "/" . $ano );

    //Linha 3 - 2º Retangulo Diagnostico
    //Linha 4 - 2º Retangulo HORA
    $pdf->rect( $pdf->getX() + 58, $pdf->getY() + 119, 34, 10, "D" );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 60, $pdf->getY() + 123, "CÓDIGO DA" );
    $pdf->text( $pdf->getX() + 60, $pdf->getY() + 126, "ESPECIFICAÇÃO DA" );
    $pdf->text( $pdf->getX() + 60, $pdf->getY() + 128, " ATIVIDADE PROFISSIONAL:" );
    $pdf->setfont( 'arial', 'b', 7 );

    //Linha 4 - 2º Retangulo GRUPO TABELA
    $pdf->rect( $pdf->getX() + 58, $pdf->getY() + 130, 34, 10, "D" );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 60, $pdf->getY() + 133, "GRUPO(TABELA3):" );
    $pdf->setfont( 'arial', 'b', 7 );

    //Linha 3 - 2º Retangulo polegar1
    $pdf->rect( $pdf->getX() + 65 + 28, $pdf->getY() + 119, 21, 22, "D"
    );
    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 66 + 28, $pdf->getY() + 121, "POLEGAR DIREITO:" );

    //Linha 3 - 2º Retangulo polegar2
    $pdf->rect( $pdf->getX() + 65 + 52, $pdf->getY() + 119, 21, 22, "D" );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 66 + 52, $pdf->getY() + 121, "POLEGAR DIREITO:" );

    //Linha 3 - 2ª Retangulo polegar3
    $pdf->rect( $pdf->getX() + 65 + 76, $pdf->getY() + 119, 21, 22, "D" );
    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 66 + 76, $pdf->getY() + 121, "POLEGAR DIREITO:" );

    //Linha 3 - 2Âª Retangulo polegar4
    $pdf->rect( $pdf->getX() + 65 + 100, $pdf->getY() + 119, 21, 22, "D" );
    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 66 + 100, $pdf->getY() + 121, "POLEGAR DIREITO:" );

    //Linha 3 - 2ª Retangulo polegar5
    $pdf->rect( $pdf->getX() + 65 + 124, $pdf->getY() + 119, 21, 22, "D" );
    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 66 + 124, $pdf->getY() + 121, "POLEGAR DIREITO:" );

    //Linha 4 - 1ª Retangulo data do atendimento
    $pdf->rect( $pdf->getX(), $pdf->getY() + 131, 56, 10, "D" );
    $pdf->text( $pdf->getX(), $pdf->getY() + 133, "ASSINATURA DO PACIENTE OU RESPONSÁVEL:" );

    $fTamFolha = 210.0; //tamanho da folha
    $sRodape = "Usuário: $login - $nome    Data: ".db_formatar($sd24_d_cadastro, 'd').
               '    Hora: '.substr($sd24_c_cadastro,0,8).'    Base: '.db_base_ativa();

    $fTamString = $pdf->getStringWidth($sRodape);
    $fXRodape   = ($fTamFolha - $fTamString) / 2.0;
    $pdf->text($fXRodape, $pdf->getY()+143.6,$sRodape);

    $setY = 150;
  }
}

$pdf->Output();