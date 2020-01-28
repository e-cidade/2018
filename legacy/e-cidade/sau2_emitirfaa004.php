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
require_once('libs/db_utils.php');

parse_str( $_SERVER["QUERY_STRING"]);
db_postmemory( $_POST );

set_time_limit(0);

$clprontuarios        = new cl_prontuarios_ext;
$clprontproced        = new cl_prontproced_ext;
$oDaoProntAgendamento = new cl_prontagendamento();
$clcgs_und            = new cl_cgs_und;
$oDaoSauConfig        = new cl_sau_config_ext();

/* BUSCAR PARÂMETROS DE CONFIGURAÇÃO */
$sSql     = $oDaoSauConfig->sql_query_ext();
$rsConfig = $oDaoSauConfig->sql_record($sSql);

if ($oDaoSauConfig->numrows > 0) {
  $oDadosConfig = db_utils::fieldsmemory( $rsConfig, 0 );
}

//Quebra e verifica se a chave do prontuario for multipla para imprimir varios
$aChaveProntuarios = explode( ",", $chave_sd29_i_prontuario );
$iTam              = count($aChaveProntuarios);
$result            = array();
$linhas            = array();

for( $iX = 0; $iX < $iTam; $iX++ ) {

  $sSql               = $clprontproced->sql_query_ext( null, "*", null, "sd29_i_prontuario = {$aChaveProntuarios[$iX]}" );
  $result_proc[$iX]   = $clprontproced->sql_record($sSql);

  $sSubAgenda         = 'select sd04_i_medico ';
  $sSubAgenda        .= '  from prontproced ';
  $sSubAgenda        .= '       inner join prontuarios    on prontuarios.sd24_i_codigo    = prontproced.sd29_i_prontuario ';
  $sSubAgenda        .= '       inner join especmedico    on especmedico.sd27_i_codigo    = prontproced.sd29_i_profissional ';
  $sSubAgenda        .= '       inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed ';
  $sSubAgenda        .= '  where sd29_i_prontuario = ' . $aChaveProntuarios[$iX];
  $sWhereAgenda       = 's102_i_prontuario = ' . $aChaveProntuarios[$iX] . ' and sd03_i_codigo not in (' . $sSubAgenda . ')';
  $sSql               = $oDaoProntAgendamento->sql_query_profissional_agendamento(null, 'rh70_estrutural, z01_nome', '', $sWhereAgenda );
  $rsAgenda[$iX]      = $oDaoProntAgendamento->sql_record($sSql);
  $iLinhasAgenda[$iX] = $oDaoProntAgendamento->numrows;
  
  $linhas_proc[$iX]   = $clprontproced->numrows;
  $sSql               = $clprontuarios->sql_query_ext($aChaveProntuarios[$iX], '*, cgm2.z01_nome as proftriagemavulsa');
  $result[$iX]        = $clprontuarios->sql_record($sSql);
  $linhas[$iX]        = $clprontuarios->numrows;
}

if( in_array( 0, $linhas ) ) {

  echo "<table width='100%'>
          <tr>
            <td align='center'>
              <font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br>
              <input type='button' value='Fechar' onclick='window.close()'></b></font>
            </td>
          </tr>
        </table>";
  exit;
}

$pdf = new SCPDF();
$pdf->setMargins(0, 0, 0);
$pdf->Open();
$pdf->AliasNbPages();

for( $iX = 0; $iX < $iTam; $iX++ ) {

  db_fieldsmemory( $result[$iX], 0 );

  $result_cgs = $clcgs_und->sql_record( $clcgs_und->sql_query( $sd24_i_numcgs ) );
  db_fieldsmemory( $result_cgs, 0 );

  $sSqlCgsUnd  = "select unidades.*, cgm.z01_nome as estabelecimento, ";
  $sSqlCgsUnd .= "       cgm.z01_ender as est_ender, ";
  $sSqlCgsUnd .= "       cgm.z01_bairro as est_bairro, ";
  $sSqlCgsUnd .= "       cgm.z01_munic as est_munic, ";
  $sSqlCgsUnd .= "       cgm.z01_uf as est_uf ";
  $sSqlCgsUnd .= "  from unidades ";
  $sSqlCgsUnd .= "       inner join db_depart on db_depart.coddepto = unidades.sd02_i_codigo ";
  $sSqlCgsUnd .= "       inner join cgm       on cgm.z01_numcgm     = unidades.sd02_i_numcgm ";
  $sSqlCgsUnd .= " where unidades.sd02_i_codigo = {$sd24_i_unidade}";
  $result_und = $clcgs_und->sql_record( $sSqlCgsUnd );

  $pdf->setfillcolor(243);
  $pdf->addpage('P');
  $pdf->ln(0);
  $pdf->SetLineWidth(0.5);

  //Posições
  $alt  = $pdf->getY();
  $lar  = $pdf->getX();
  $setY = 0;

  for( $y = 1; $y <= 1; $y++ ) {
  
    $pdf->setY($setY);
    $pdf->setX(0);
  
    //Linha 1 - 1ª Retangulo SIA/SUA
    $pdf->rect( $pdf->getX(),     $pdf->getY(),     56, 16, "D" );
    $pdf->rect( $pdf->getX() + 6, $pdf->getY() + 1,  5, 14, "D" );
    $pdf->rect( $pdf->getX() + 1, $pdf->getY() + 6, 15,  5, "D" );

    $pdf->setfont( 'arial', 'b', 8 );
    $pdf->text( $pdf->getX() + 17,$pdf->getY() + 3, "SIAA/SUS-RS" );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 17, $pdf->getY() + 8, "SISTEMA DE INFORMAÇÕES" );
    $pdf->text( $pdf->getX() + 17, $pdf->getY() + 11,"AMBULATORIAIS" );
    $pdf->text( $pdf->getX() + 17, $pdf->getY() + 14,"DO SISTEMA ÚNICO DE SAÚDE" );

    //Linha 1 - 2º Retangulo
    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->rect( $pdf->getX() + 58, $pdf->getY(), 56, 16, "D" );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->text( $pdf->getX() + 70, $pdf->getY() + 5, "ATENDIMENTO Nro :   " . $sd24_i_codigo );
    $t1 = str_pad( $sd24_i_codigo, 10, 0, STR_PAD_LEFT ); //numero codigo barras

    $pdf->setfont( 'arial', 'b', 8 );
    $pdf->SetFillColor(000); //fundo codbarras
    $pdf->int25( $pdf->getX() + 72,$pdf->getY() + 7, $t1, 5, 0.341 ); //codbarras
    $pdf->setfont( 'arial', 'b', 7 );

    //Linha 1 - 3° Retangulo
    $pdf->rect( $pdf->getX() + 116, $pdf->getY(), 94, 7, "D" );

    $pdf->setfont( 'arial', 'b', 10 );
    $pdf->text( $pdf->getX() + 116 + 10, $pdf->getY() + 5, "FICHA DE ATENDIMENTO AMBULATORIAL" );
    $pdf->rect( $pdf->getX() + 116,      $pdf->getY() + 9, 94, 7, "D" );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 116 + 1, $pdf->getY() + 12, "MOTIVO DO ATENDIMENTO" );

    $sMotivo = empty($s144_c_descr) ? $sd24_v_motivo : $s144_c_descr;
    $pdf->text( $pdf->getX() + 116 + 1, $pdf->getY() + 14, $sMotivo );

    //Linha 2 - 1ª Retangulo Unidade prestadora
    db_fieldsmemory( $result_und, 0 );
    $pdf->rect( $pdf->getX(), $pdf->getY() + 18, 56, 48, "D" );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 2, $pdf->getY() + 21, "UNIDADE PRESTADORA DE ATENDIMENTO" );
    $alt = $pdf->getY() + 5;
    $pdf->setfont( 'arial', 'b', 7 );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->text( $pdf->getX() + 2, $alt + 24, "NOME DA UNIDADE: " );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 2, $alt + 27, substr( $descrdepto, 0, 40 ) );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->text( $pdf->getX() + 2, $alt + 34, "ENDEREÇO: " );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 2, $alt + 37, substr( $est_ender, 0, 40 ) );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->text( $pdf->getX() + 2, $alt + 42, "MUNICÍPIO: " );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 2, $alt + 44, substr( $est_munic, 0, 40 ) );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 2, $alt + 50, "UF:" . $est_uf );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 2, $alt + 55, "CÓDIGO SIA/SUS:" . $sd02_c_siasus );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 2, $alt + 60, "LOGIN:" . $login );

    //Linha 2 - 2ª Retangulo CBO
    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->rect( $pdf->getX() + 58, $pdf->getY() + 18, 19, 99, "D" );
    $pdf->text( $pdf->getX() + 60, $pdf->getY() + 21, "CBO" );
    $pdf->line( $pdf->getX() + 58, $pdf->getY() + 24, $pdf->getX() + 58 + 19, $pdf->getY() + 24 );

    //Linha 2 - 3ª Retangulo Procedimentos
    $pdf->rect( $pdf->getX() + 79, $pdf->getY() + 18, 35, 99, "D") ;
    $pdf->text( $pdf->getX() + 80, $pdf->getY() + 21, "CÓDIGO TABELA DE" );
    $pdf->text( $pdf->getX() + 80, $pdf->getY() + 23, "PROCEDIMENTO SIA/SUS" );
    $pdf->line( $pdf->getX() + 79, $pdf->getY() + 24, $pdf->getX() + 74 + 40, $pdf->getY() + 24 );

    //Linha 2 - 4ª Retangulo Procedimentos
    $pdf->rect( $pdf->getX() + 116, $pdf->getY() + 18, 94, 99, "D" );
    $pdf->text( $pdf->getX() + 117, $pdf->getY() + 21, "PROFISSIONAL" );
    $pdf->text( $pdf->getX() + 117, $pdf->getY() + 23, "SETOR" );
    $pdf->line( $pdf->getX() + 116, $pdf->getY() + 24, $pdf->getX() + 116 + 94, $pdf->getY() + 24 );

    //Linha 2 - 5ª Retangulo Procedimentos
    $pdf->rect( $pdf->getX() + 134, $pdf->getY() + 18, 53, 99, "D" );
    $pdf->text( $pdf->getX() + 135, $pdf->getY() + 21, "TRATAMENTO DESCRIÇÃO" );
    $pdf->text( $pdf->getX() + 135, $pdf->getY() + 23, "DOS PROCEDIMENTOS" );
    $pdf->text( $pdf->getX() + 188, $pdf->getY() + 21, "ASSIN. E CARIMBO" );
    $pdf->text( $pdf->getX() + 188, $pdf->getY() + 23, "DO PROFISSIONAL" );

    $pdf->setfont( 'arial', 'b', 8 );

    if( $sd24_v_pressao == 0 ) {
      $sd24_v_pressao = "";
    }

    if( $sd24_f_temperatura == 0 ) {
      $sd24_f_temperatura = "";
    }

    if( $sd24_f_peso == 0 ) {
      $sd24_f_peso = "";
    }

    if( isset( $s152_i_pressaosistolica ) && !empty( $s152_i_pressaosistolica ) ) {
      $sPressao = $s152_i_pressaosistolica . '/' . $s152_i_pressaodiastolica;
    } else {
      $sPressao = $sd24_v_pressao;
    }

    if( isset( $s152_n_temperatura ) && !empty( $s152_n_temperatura ) ) {

      $aTmp         = explode( '.', $s152_n_temperatura );
      $sTemperatura = $aTmp[0] . ',' . $aTmp[1][0];
    } else {
      $sTemperatura = $sd24_f_temperatura;
    }

    if( isset( $s152_n_peso ) && !empty( $s152_n_peso ) ) {

      $aTmp  = explode( '.', $s152_n_peso );
      $sPeso = $aTmp[0] . ',' . $aTmp[1][0];
    } else {
      $sPeso = $sd24_f_peso;
    }

    $aImc  = calculaIMC( @$s152_n_peso, @$s152_i_altura );

    if( empty( $aImc ) ) {
      $sImc = '';
    } else {

      $aIm2  = explode( '.', $aImc[0] );

      if( count( $aIm2 ) == 2 ) {
        $aImc[0] = $aIm2[0] . ',' . substr( $aIm2[1], 0, 2 );
      }

      $sImc = $aImc[0].' - '.$aImc[1];
    }

    $iXTmp = $pdf->getX();
    $iYTmp = $pdf->getY();

    $pdf->setX( $iXTmp + 58 );
    $pdf->setY( $iYTmp + 24.6 );

    $pdf->SetWidths( array( 58, 19, 38, 20, 53, 24 ) );
    $pdf->SetAligns( array( "C", "C", "C", "L", "L", "L" ) );

    $pdf->Row(array('', '', '', $proftriagemavulsa, 
                    "PRESSÃO: $sPressao   TEMP.: $sTemperatura   PESO: $sPeso   IMC: $sImc", ''
                   ), 3, false, 3 );
    $pdf->setX( $iXTmp );
    $pdf->line( $pdf->getX() + 58,  $pdf->getY(), $pdf->getX() + 58 + 19,  $pdf->getY() );
    $pdf->line( $pdf->getX() + 79,  $pdf->getY(), $pdf->getX() + 74 + 40,  $pdf->getY() );
    $pdf->line( $pdf->getX() + 116, $pdf->getY(), $pdf->getX() + 116 + 94, $pdf->getY() );

    //Prontuário / Procedimento
    $alt = $pdf->getY();
    $lar = $pdf->getX();
    $pdf->setY( $alt );

    for( $iCont = 0; $iCont < $iLinhasAgenda[$iX]; $iCont++ ) {

      $pdf->setX($lar + 58);
      $oDados = db_utils::fieldsmemory( $rsAgenda[$iX], $iCont );

      $pdf->setfont( 'arial', '', 7 );
      $pdf->SetWidths( array( 19, 38, 20, 53, 24 ) );
      $pdf->SetAligns( array( 'C', 'C', 'L', 'J', 'L' ) );

      $pdf->Row( array( $oDados->rh70_estrutural, '', $oDados->z01_nome, '', '' ), 3, false, 3 );
      $pdf->line( $lar + 58,  $pdf->getY(), $lar + 77,  $pdf->getY() );
      $pdf->line( $lar + 79,  $pdf->getY(), $lar + 114, $pdf->getY() );
      $pdf->line( $lar + 116, $pdf->getY(), $lar + 210, $pdf->getY() );
    }

    for( $x = 0; $x < $linhas_proc[$iX]; $x++ ) {

      $pdf->setX( $lar + 58 );
      db_fieldsmemory( $result_proc[$iX], $x );

      $sSqlCgsUnd   = "select cgm.z01_nome as profissional ";
      $sSqlCgsUnd  .= "  from cgm ";
      $sSqlCgsUnd  .= "       inner join medicos        on medicos.sd03_i_cgm           = cgm.z01_numcgm ";
      $sSqlCgsUnd  .= "       inner join unidademedicos on unidademedicos.sd04_i_medico = medicos.sd03_i_codigo ";
      $sSqlCgsUnd  .= "       inner join especmedico    on especmedico.sd27_i_undmed    = unidademedicos.sd04_i_codigo ";
      $sSqlCgsUnd  .= " where especmedico.sd27_i_codigo = {$sd29_i_profissional}";
      $result_prof  = $clcgs_und->sql_record( $sSqlCgsUnd );
      db_fieldsmemory( $result_prof, 0 );

      $pdf->setfont( 'arial', '', 7 );
      $pdf->SetWidths( array( 19, 38, 20, 53, 24 ) );
      $pdf->SetAligns( array( "C", "C", "L", "J", "L" ) );

      $nbx = "";
      $pdf->Row( array( $rh70_estrutural, $sd63_c_procedimento, $profissional, $sd63_c_nome, $nbx ), 3, false, 3 );
      $pdf->line( $lar + 58,  $pdf->getY(), $lar + 58 + 19,  $pdf->getY() );
      $pdf->line( $lar + 79,  $pdf->getY(), $lar + 74 + 40,  $pdf->getY() );
      $pdf->line( $lar + 116, $pdf->getY(), $lar + 116 + 94, $pdf->getY() );
    }

    $pdf->setY($iYTmp);
    $pdf->setX($lar);
  
    //Linha 3 - 1ª Retangulo Unidade prestadora
    db_fieldsmemory( $result_cgs, 0 );

    $sexo  = array( '' => '', "F" => "Feminino", "M" => "Masculino" );
    $dia   = substr( $z01_d_nasc, 8, 2 );
    $mes   = substr( $z01_d_nasc, 5, 2 );
    $ano   = substr( $z01_d_nasc, 0, 4 );
    $idade = calcage( $dia, $mes, $ano, date("d"), date("m"), date("Y") );

    $pdf->rect( $pdf->getX(), $pdf->getY() + 68, 56, 61, "D" );
    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 2, $pdf->getY() + 71, "IDENTIFICAÇÃO DO PACIENTE" );
    $pdf->setfont( 'arial', 'b', 7 );
    $alt = $pdf->getY() + 50 + 5;

    $alt = $pdf->getY();
    $lar = $pdf->getX();

    $pdf->setY( $alt + 73 );
    $pdf->setX( $lar + 1 );
    $pdf->MultiCell( 55, 4, "NOME: " . $z01_i_numcgs . "-" . $z01_v_nome, 0, "L" );
    $pdf->setY( $alt );
    $pdf->setX( $lar );

    $alt = $pdf->getY() + 50 + 5;

    $pdf->text( $pdf->getX() + 2, $alt + 29, "ENDEREÇO: " . substr( $z01_v_ender . ", " . $z01_i_numero . ", " . $z01_v_compl, 0, 21 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 32, substr( $z01_v_ender . ", " . $z01_i_numero . ", " . $z01_v_compl, 21, 40 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 35, "BAIRRO: " . $z01_v_bairro );
    $pdf->text( $pdf->getX() + 2, $alt + 41, "MUNICÍPIO:" . substr( $z01_v_munic, 0, 40 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 44, substr( $z01_v_munic, 40, 40 ) );
    $pdf->text( $pdf->getX() + 2, $alt + 47, "UF: " . $est_uf . " IDADE: " . $idade );
    $pdf->text( $pdf->getX() + 2, $alt + 53, "SEXO: " . $sexo[$z01_v_sexo] );
    $pdf->text( $pdf->getX() + 2, $alt + 56, "DATA NASC: " . $dia . "/" . $mes . "/" . $ano );
    $pdf->text( $pdf->getX() + 2, $alt + 59, "TELEFONE: " . $z01_v_telef );
    $pdf->text( $pdf->getX() + 2, $alt + 62, "CELULAR: " . $z01_v_telcel );
    $pdf->text( $pdf->getX() + 2 ,$alt + 65, "FAMÍLIA PSF:" . $sd33_v_descricao );
    $pdf->text( $pdf->getX() + 2 ,$alt + 71, "MICROÁREA:" . $sd34_v_descricao );
  
    //Linha 3 - 2ª Retangulo Diagnóstico
    $pdf->rect( $pdf->getX() + 58, $pdf->getY() + 119, 129, 10, "D" );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 59, $pdf->getY() + 122, "DIAGNÓSTICO:" );
    $pdf->text( $pdf->getX() + 59, $pdf->getY() + 128, "CID: " );
    $pdf->setfont( 'arial', 'b', 7 );

    $alt                 = $pdf->getY();
    $lar                 = $pdf->getX();
    $sd24_t_diagnostico1 = substr( $sd24_t_diagnostico, 0,  46 );
    $sd24_t_diagnostico2 = substr( $sd24_t_diagnostico, 46, 46 );
    $sd24_t_diagnostico3 = substr( $sd24_t_diagnostico, 92, 46 );

    $pdf->setY( $alt + 119 );
    $pdf->setX( $lar + 76 );
    $pdf->text( $lar + 78, $alt + 122, $sd24_t_diagnostico1 );
    $pdf->text( $lar + 78, $alt + 124, $sd24_t_diagnostico2 );
    $pdf->text( $lar + 78, $alt + 126, $sd24_t_diagnostico3 );

    $pdf->setY( $alt );
    $pdf->setX( $lar );
    $pdf->text( $pdf->getX() + 65, $pdf->getY() + 128, $sd70_c_cid . "   " . $sd70_c_nome );

    //Linha 3 - 2ª Retangulo polegar
    $pdf->rect( $pdf->getX() + 65 + 124, $pdf->getY() + 119, 21, 22, "D" );
    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 66 + 124, $pdf->getY() + 122, "POLEGAR DIREITO:" );

    //Linha 4 - 1ª Retangulo data do atendimento
    $pdf->rect( $pdf->getX(), $pdf->getY() + 131, 27, 10, "D" );
    $pdf->text( $pdf->getX(), $pdf->getY() + 134, "DATA DO ATENDIMENTO:" );
    $pdf->setfont( 'arial', 'b', 7 );
    $sd24_d_cadastro2 = substr( $sd24_d_cadastro, 8, 2 ) ."/" . substr( $sd24_d_cadastro, 5, 2 ) ."/" . substr( $sd24_d_cadastro, 0, 4 );

    /* DATA E HORA DA EMISSÃO */
    if ($oDadosConfig->s103_i_datahorafaa == 2) {

      $sd24_d_cadastro2 = date( 'd/m/Y', db_getsession('DB_datausu') );
      $sd24_c_cadastro  = date( 'H:i' );
    }

    $pdf->text( $pdf->getX() + 5, $pdf->getY() + 137, $sd24_d_cadastro2 );

    //Linha 4 - 2ª Retangulo HORA
    $pdf->rect( $pdf->getX() + 29, $pdf->getY() + 131, 27, 10, "D" );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 29, $pdf->getY() + 134, "HORA DO ATENDIMENTO:" );

    $pdf->setfont( 'arial', 'b', 7 );
    $pdf->text( $pdf->getX() + 29 + 5, $pdf->getY() + 137, $sd24_c_cadastro );

    //Linha 4 - 3ª Retangulo assinatura
    $pdf->rect( $pdf->getX() + 58, $pdf->getY() + 131, 129, 10, "D" );

    $pdf->setfont( 'arial', 'b', 6 );
    $pdf->text( $pdf->getX() + 59, $pdf->getY() + 134, "ASSINATURA DO PACIENTE OU RESPONSÁVEL:" );

    $setY = 150;
    $pdf->setfont( 'arial', '', 5.8 );
    $fTamFolha = 210.0; //tamanho da folha
    $sRodape = "Usuário: $login - $nome    Data: ".db_formatar($sd24_d_cadastro, 'd').
               '    Hora: '.substr($sd24_c_cadastro,0,8).'    Base: '.db_base_ativa();
    $fTamString = $pdf->getStringWidth($sRodape);
    $fXRodape   = ($fTamFolha - $fTamString) / 2.0;
    $pdf->text( $fXRodape, $pdf->getY() + 143.6,$sRodape );
  }
}

$pdf->Output();