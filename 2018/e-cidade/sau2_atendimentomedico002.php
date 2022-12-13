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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_prontproced_ext_classe.php"));
require_once(modification("classes/db_cgs_und_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

try {

  db_inicio_transacao();

  $clprontproced = new cl_prontproced_ext;
  $clcgs_und     = new cl_cgs_und;

  $clprontproced->rotulo->label();
  $clcgs_und->rotulo->label();

  $str_where = "1=1";

  if($listaprof != ""){

    if( $verprof == "com" ){
      $str_where .= " and sd03_i_codigo in ($listaprof)";
    }else if($listaprof != "" ){
      $str_where .= " and sd03_i_codigo not in ($listaprof)";
    }
  }

  if($listaups != ""){
    $str_where .= " and sd02_i_codigo in ($listaups)";
  }

  $hoje = date("Y-m-d",db_getsession("DB_datausu"));

  if($data1 != "//" && $data2 != "//"){

     $str_where .= " and sd29_d_data between '$data1' and '$data2' ";
     $head5 = "Período: {$data1} à {$data2}";
  }else if($data1 != "//" && $data2 == "//"){

     $str_where .= " and sd29_d_data between '$data1' and '$hoje' ";
     $head5 = "Período: {$data1} à ".date("d/m/Y",db_getsession("DB_datausu"));
  }else if($data1 == "//" && $data2 != "//"){

     $str_where .= " and sd29_d_data <= '$data2'";
  }else if($data1 == "//" && $data2 == "//"){

     $str_where .= " and sd29_d_data <= '$hoje'";
  }

  $str_ordem  = ($lQuebraUPS == "true" ? "sd02_i_codigo, " : "");
  $str_ordem .= " z01_nome ";

  $sql = $clprontproced->sql_query_ext("","
                sd02_i_codigo,
                descrdepto,
                sd03_i_codigo,
                sd03_i_cgm,
                z01_nome,
                rh70_estrutural,
                rh70_descr,
                sd24_i_codigo,
                z01_i_cgsund,
                z01_v_nome,
                sd63_c_procedimento,
                sd63_c_nome
                  ", $str_ordem, $str_where
              );

  $res_prontproced = db_query($sql);

  if ( !$res_prontproced ) {
    throw new DBException('Falha ao buscar os dados dos atendimentos.');
  }

  if( pg_num_rows( $res_prontproced ) == 0 ){
    throw new Exception("Nenhum registro para o relatório.");
  }

  $aProfissionais = array();

  for ($i = 0; $i < pg_num_rows($res_prontproced); $i++) {

    db_fieldsmemory($res_prontproced, $i);

    $sHash = "{$sd03_i_cgm}#{$rh70_estrutural}";
    // Verifica se o profissional já está na lista
    if ( !array_key_exists($sHash, $aProfissionais) ) {

      $aProfissionais[$sHash] = array(
        'codigo'       => $sd03_i_codigo,
        'nome'         => $z01_nome,
        'cbo'          => "{$rh70_estrutural} - {$rh70_descr}",
        'matriculas'   => array(),
        'ups'          => array(),
      );

      $oDaoRhPessoal = db_utils::getdao('rhpessoal');
      $sSql          = $oDaoRhPessoal->sql_query_func_rhpessoal("", "rh01_regist,rh05_recis", "", "rh01_numcgm = {$sd03_i_cgm}");
      $rsMatriculas  = db_query($sSql);

      if ( !$rsMatriculas ) {
        throw new DBException('Falha ao buscar as matrículas do profissional.');
      }

      // Busca as matrículas do profissional
      for ( $iX = 0; $iX < pg_num_rows($rsMatriculas); $iX++ ) {

        $oMatricula = db_utils::fieldsmemory($rsMatriculas, $iX);
        $sMatricula = $oMatricula->rh01_regist;

        if($oMatricula->rh05_recis == null){
          $sMatricula .= " (ativa)";
        }

        $aProfissionais[$sHash]['matriculas'][] = $sMatricula;
      }
    }

    // Adiciona a UPS ao profissional
    if ( !array_key_exists($sd02_i_codigo, $aProfissionais[$sHash]['ups']) ) {

      $aProfissionais[$sHash]['ups'][$sd02_i_codigo] = array(
        'codigo'       => $sd02_i_codigo,
        'nome'         => $descrdepto,
        'atendimentos' => array(),
      );
    }

    // Adiciona os atendimentos em cada UPS
    if ( !array_key_exists($sd24_i_codigo, $aProfissionais[$sHash]['ups'][$sd02_i_codigo]['atendimentos']) ) {

      $aProfissionais[$sHash]['ups'][$sd02_i_codigo]['atendimentos'][$sd24_i_codigo] = array(
        'faa'           => $sd24_i_codigo,
        'cgs'           => $z01_i_cgsund,
        'nome'          => $z01_v_nome,
        'procedimentos' => array(),
      );
    }

    // Adiciona os procedimentos em cada atendimento
    if ( !array_key_exists($sd63_c_procedimento, $aProfissionais[$sHash]['ups'][$sd02_i_codigo]['atendimentos'][$sd24_i_codigo]['procedimentos']) ) {
      $aProfissionais[$sHash]['ups'][$sd02_i_codigo]['atendimentos'][$sd24_i_codigo]['procedimentos'][$sd63_c_procedimento] = $sd63_c_nome;
    }
  }

  /**
   * Monta cabeçalho com os dados do profissional
   * @param array $aProfissional Array com os dados do profissional
   * @param PDF $pdf
   */
  function montaDadosProfissional($aProfissional, PDF $pdf) {

    $pdf->setfont('times','b',8);
    $pdf->cell(20,5,"Profissional:",0,0,"L",1);
    $pdf->setfont('times','',8);
    $pdf->cell(80,5, "{$aProfissional['codigo']} - {$aProfissional['nome']}",0,0,"L",1);
    $pdf->setfont('times','b',8);
    $pdf->cell(10,5,"CBO:",0,0,"L",1);
    $pdf->setfont('times','',8);
    $pdf->cell(80,5, substr(trim($aProfissional['cbo']), 0, 50) ,0,1,"L",1);
    $pdf->setfont('times','b',8);
    $pdf->cell(20,5,"Matrícula(s):",0,0,"L",1);
    $pdf->setfont('times','',8);
    $pdf->cell(80,5,  implode(", ", $aProfissional['matriculas']),0,0,"L",1);
    $pdf->setfont('times','b',8);
    $pdf->cell(10,5,"",0,0,"L",1);
    $pdf->setfont('times','',8);
    $pdf->cell(80,5, "",0,1,"L",1);
  }

  /**
   * Monta o cabeçalho da lista de atendimentos
   * @param type $lListaProcedimentos Se true, monta também a coluna de procedimento
   * @param PDF $pdf
   */
  function montaCabecalhoAtendimentos($lListaProcedimentos, PDF $pdf) {

    $pdf->setfont('times','b',8);
    $pdf->cell(10,5,"FAA","B",0,"L",0);
    $pdf->cell(15,5,"CGS","B",0,"L",0);

    if ( $lListaProcedimentos ) {

      $pdf->cell(55,5,"Paciente","B",0,"L",0);
      $pdf->cell(110,5,"Procedimento","B",1,"L",0);
    } else {
      $pdf->cell(165,5,"Paciente","B",1,"L",0);
    }

    $pdf->setfont('times','',8);
  }

  /**
   * Monta a linha com os dados de cada atendimento, retornando o total de procedimentos
   * @param array $aAtendimento Array com os dados do atendimento
   * @param boolean $lListaProcedimentos Se true, lista os procedimentos
   * @param PDF $pdf
   */
  function montaLinhaAtendimento($aAtendimento, $lListaProcedimentos, PDF $pdf) {

    $iTotalProcedimentos = 0;

    $pdf->setfont('times','',7);

    if ( $lListaProcedimentos ) {

      foreach ( $aAtendimento['procedimentos'] as $iCodigoProcedimento => $nomeProcedimento ) {

        $pdf->cell(10,4,$aAtendimento['faa'],0,0,"L",0);
        $pdf->cell(15,4,$aAtendimento['cgs'],0,0,"L",0);
        $pdf->cell(55,4,substr(trim($aAtendimento['nome']),0,33),0,0,"L",0);
        $pdf->cell(100,4, substr("{$iCodigoProcedimento} - {$nomeProcedimento}", 0, 77),0,1,"L",0);

        $iTotalProcedimentos++;
      }

    } else {

      $pdf->cell(10,4,$aAtendimento['faa'],0,0,"L",0);
      $pdf->cell(15,4,$aAtendimento['cgs'],0,0,"L",0);
      $pdf->cell(165,4,substr(trim($aAtendimento['nome']),0,104),0,1,"L",0);
    }

    return $iTotalProcedimentos;
  }

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $head3 = "Relatório de Atendimentos";

  $pdf->addpage();
  $pdf->setfillcolor(200);

  $lQuebraUPS               = $lQuebraUPS == 'true';
  $lListaProcedimentos      = $lListaProcedimentos == 'true';
  $iTotalGeralATendimentos  = 0;
  $iTotalGeralProcedimentos = 0;

  foreach ( $aProfissionais as $iCGMProfissional => $aProfissional) {

    $lQuebraPagina = ($pdf->GetY() > $pdf->h - 50);

    if ( $lQuebraPagina ) {
      $pdf->addpage();
    }

    // Dados do profissional
    montaDadosProfissional($aProfissional, $pdf)
;    foreach ( $aProfissional['ups'] as $iCodigoUPS => $aUPS ) {

      $pdf->setfont('times','b',8);
      $pdf->cell(10,5,"UPS:",0,0,"L",0);
      $pdf->setfont('times','',8);
      $pdf->cell(105,5, "{$aUPS['codigo']} - {$aUPS['nome']}",0,1,"L",0);

      montaCabecalhoAtendimentos($lListaProcedimentos, $pdf);

      ksort($aUPS['atendimentos']);
      $iTotalProcedimentos = 0;

      foreach ( $aUPS['atendimentos'] as $aAtendimento ) {
        $iTotalProcedimentos += montaLinhaAtendimento($aAtendimento, $lListaProcedimentos, $pdf);
      }

      $pdf->setfont('times','b',7);
      $pdf->cell(50,5,"TOTAL DE ATENDIMENTOS:","T",0,"L",0);
      $pdf->setfont('times','',7);
      $pdf->cell(140,5,count($aUPS['atendimentos']),"T",1,"L",0);

      $iTotalGeralATendimentos += count($aUPS['atendimentos']);

      if ( $lListaProcedimentos ) {

        $pdf->setfont('times','b',7);
        $pdf->cell(50,5,"TOTAL DE PROCEDIMENTOS:",0,0,"L",0);
        $pdf->setfont('times','',7);
        $pdf->cell(140,5, $iTotalProcedimentos ,0,1,"L",0);

        $iTotalGeralProcedimentos += $iTotalProcedimentos;
      }

      $pdf->cell(0,5,"",0,1,"L",0);

      // Quebra a página por UPS, verificando se esta montando o último profissional ou a última UPS
      if ( $lQuebraUPS && (end(array_keys($aProfissionais)) != $iCGMProfissional || end(array_keys($aProfissional['ups'])) != $iCodigoUPS) ) {

        $pdf->addpage();

        // Não imprime o cabeçalho do profissional se for a última UPS
        if ( end(array_keys($aProfissional['ups'])) != $iCodigoUPS ) {
          montaDadosProfissional($aProfissional, $pdf);
        }
      }
    }
  }

  // Mostra os totalizadores gerais em uma página separada, se quebrar por UPS
  if ( $lQuebraUPS ) {
    $pdf->addpage();
  }

  $pdf->setfont('times','b',7);
  $pdf->cell(50,5,"TOTAL GERAL DE ATENDIMENTOS:","T",0,"L",0);
  $pdf->setfont('times','',7);
  $pdf->cell(140,5,$iTotalGeralATendimentos,"T",1,"L",0);

  if ( $lListaProcedimentos ) {

    $pdf->setfont('times','b',7);
    $pdf->cell(50,5,"TOTAL GERAL DE PROCEDIMENTOS:",0,0,"L",0);
    $pdf->setfont('times','',7);
    $pdf->cell(140,5, $iTotalGeralProcedimentos ,0,1,"L",0);
  }

  $pdf->Output();

} catch ( Exception $oErro ) {

  db_fim_transacao( true );
  db_redireciona('db_erros.php?fechar=true&db_erro='.$oErro->getMessage());
}