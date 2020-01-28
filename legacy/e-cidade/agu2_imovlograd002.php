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

  require_once "fpdf151/pdf.php";
  require_once "libs/db_sql.php";
  require_once "classes/db_aguabase_classe.php";
  require_once "libs/db_utils.php";

  $oGet = db_utils::postmemory($_GET);

  $sComSem          = (isset($oGet->comsem)           and $oGet->comsem           != '') ? $oGet->comsem           : '';
  $listaLog         = (isset($oGet->listalog)         and $oGet->listalog         != '') ? $oGet->listalog         : '';
  $listaBairro      = (isset($oGet->listabairro)      and $oGet->listabairro      != '') ? $oGet->listabairro      : '';
  $listaZona        = (isset($oGet->listazona)        and $oGet->listazona        != '') ? $oGet->listazona        : '';
  $listaZonaEntrega = (isset($oGet->listazonaentrega) and $oGet->listazonaentrega != '') ? $oGet->listazonaentrega : '';
  $dataInicial      = (isset($oGet->datainicial)      and $oGet->datainicial      != '') ? $oGet->datainicial      : '';
  $dataFinal        = (isset($oGet->datafinal)        and $oGet->datafinal        != '') ? $oGet->datafinal        : '';
  $sOpcao           = $oGet->opcao;

  $oDaoAguaBase     = db_utils::getDao('aguabase');
  
  $oDaoAguaBase->rotulo->label();

  $clrotulo = new rotulocampo;
  $clrotulo->label('x11_complemento');
  $clrotulo->label('x04_nrohidro');
  $clrotulo->label('z01_nome');
  $clrotulo->label('j13_descr');
  $clrotulo->label('j50_descr');
  $clrotulo->label('j85_descr');
  $clrotulo->label('x04_dtinst');

  $sWhere  = "     ( fc_agua_hidrometroativo(aguahidromatric.x04_codhidrometro) is true ";
  $sWhere .= "        or aguahidromatric.x04_codhidrometro is null)                     ";
  $sWhere .= " and (x11_tipo = 'P' or x11_matric is null)                               ";

  $sHead = "";

  if (isset($listaLog) and ($listaLog != '')) {
    
    $sWhere .= " and x01_codrua {$sComSem} in ({$listaLog}) ";
    $sHead  .= "Logradouro\n";  
  }
  
  if (isset($listaBairro) and ($listaBairro != '')) {
    
    $sWhere .= " and x01_codbairro {$sComSem} in ({$listaBairro}) ";
    $sHead  .= "Bairro\n";
  }
  
  if (isset($listaZona) and ($listaZona != '')) {
    
    $sWhere .= " and x01_zona {$sComSem} in ({$listaZona}) ";
    $sHead  .= "Zona Fiscal\n";
  }
  
  if (isset($listaZonaEntrega) and ($listaZonaEntrega != '')) {
    
    $sWhere .= " and x01_entrega {$sComSem} in ({$listaZonaEntrega}) ";
    $sHead  .= "Zona de Entrega\n";
  }
  
  if (($dataInicial != '') && ($dataFinal != '')) {
    
    $sWhere .= " and x04_dtinst between '{$dataInicial}' and '{$dataFinal}' ";
    $sHead  .= "Período: " . db_formatar($dataInicial, 'd') . " até " . db_formatar($dataFinal, 'd') . "\n";
  }
  
  $head2 = "Relatório de Hidrômetros Instalados: ";
  $head3 = $sHead;
  
  $sCampos  = "x01_matric, j14_nome       , x01_numero,   ";
  $sCampos .= " x01_letra, x11_complemento, x04_nrohidro, ";
  $sCampos .= " case when x11_codconstr is not null       ";
  $sCampos .= "       then 'Predial' else 'Territorial'   ";
  $sCampos .= " end as j31_descr,                         ";
  $sCampos .= " j13_descr , j50_descr , j85_descr,        ";
  $sCampos .= " x01_codrua, x04_dtinst                    ";
  
  $sOrderBy = "j14_nome, x01_numero, x01_letra, x11_complemento";
  
  $sSqlAguaBase  = "select {$sCampos}                                                     ";
  $sSqlAguaBase .= "  from aguabase                                                       ";
  $sSqlAguaBase .= "       inner join ruas               on j14_codigo    = x01_codrua    ";
  $sSqlAguaBase .= "       inner join bairro             on j13_codi      = x01_codbairro ";
  $sSqlAguaBase .= "        left join aguaconstr         on x11_matric    = x01_matric    ";
  $sSqlAguaBase .= "                                    and x11_tipo      = 'P'           ";
  $sSqlAguaBase .= "        left join aguaconstrcar      on x12_codconstr = x11_codconstr ";
  $sSqlAguaBase .= "        left join caracter           on x12_codigo    = j31_codigo    ";
  $sSqlAguaBase .= "       inner join aguahidromatric    on x04_matric    = x01_matric    ";
  $sSqlAguaBase .= "        left join zonas              on j50_zona      = x01_zona      ";
  $sSqlAguaBase .= "        left join iptucadzonaentrega on j85_codigo    = x01_entrega   ";
  $sSqlAguaBase .= " where {$sWhere}                                                      ";
  $sSqlAguaBase .= " order by {$sOrderBy}                                                 ";
  
  $rsResultado = $oDaoAguaBase->sql_record($sSqlAguaBase);
  $aResultado  = db_utils::getCollectionByRecord($rsResultado, true);
  
  if (count($aResultado) <= 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
  }
  
  $pdf = new PDF();
  
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  $pdf->setfont('arial', 'b', 7);
  
  $iTotalGeral           = 0;
  $fTroca                = 1;
  $iAltura               = 4;
  $iTotalLogradouro      = 0;
  $iTotalMatriculasGeral = 0;
  $iCodigoRua            = "";
  $fLinha                = 0;
  $aDados                = array();
  
  foreach ($aResultado as $oRegistro) {
    
    $oDados = new stdClass();
    $oDados->iMatricula         = $oRegistro->x01_matric;
    $oDados->sComplemento       = $oRegistro->x11_complemento;
    $oDados->sNumeroHidrometro  = $oRegistro->x04_nrohidro;
    $oDados->sTipoImovel        = substr($oRegistro->j31_descr, 0, 35);
    $oDados->sDescricaoZona     = $oRegistro->j50_descr;
    $oDados->sDescricaoZonaEntr = $oRegistro->j85_descr;
    
    $sNumero = $oRegistro->x01_numero; 
    
    if (!empty($oRegistro->x01_letra)) {
      $sNumero = $oRegistro->x01_numero . '-' . $oRegistro->x01_letra;
    }
    
    $aDados[$oRegistro->j13_descr]
           [$oRegistro->j14_nome]
           [$sNumero]
           [$oRegistro->x04_dtinst] = $oDados;
  }
  
  $sOrientacao = 'P';
  $iLargura    = 190;
  
  if ($sOpcao == 'analitico') {
    
    $sOrientacao = 'L';
    $iLargura    = 280;
  }
  
  foreach ($aDados as $sBairro => $aBairro) {
    
    if ($pdf->gety() > $pdf->h - 30 || $fTroca != 0) {
      $pdf->addpage($sOrientacao);
      $pdf->setrightmargin(0.5);
      $fTroca = 0;
    }
    
    $sLabelBairro = "BAIRRO: {$sBairro}";
    
    $pdf->setfont('arial', 'b', 7);
    
    $pdf->cell($iLargura, $iAltura, $sLabelBairro, 1, 1, "L", 1);
    
    if ($sOpcao == 'analitico') {
      $pdf->ln();
    } else {
      
      $pdf->cell(90, $iAltura, 'Logradouro' , 1, 0, 'C', 1);
      $pdf->cell(30, $iAltura, 'Quantidade' , 1, 1, 'C', 1);
    }
    
    $iTotalMatriculasBairro = 0 ;
    
    foreach ($aBairro as $sLogradouro => $oLogradouro) {
      
      if ($pdf->gety() > $pdf->h - 30 || $fTroca != 0) {
       
        $pdf->addpage($sOrientacao);
        
        $pdf->setrightmargin(0.5);
        $fTroca = 0;
      }
    
      $sLabelLogradouro = "{$oRegistro->x01_codrua} - {$sLogradouro}";
      
      if ($sOpcao == 'analitico') {
        
        $pdf->setfont('arial', 'b', 7);
        $pdf->cell( 0, $iAltura, $RLx01_codrua . ":" . $sLabelLogradouro  , 0, 1, 'L', 0);
        $pdf->cell(15, $iAltura, $RLx01_matric      , 1, 0, 'C', 1);
        $pdf->cell(15, $iAltura, $RLx01_numero      , 1, 0, 'C', 1);
        $pdf->cell(60, $iAltura, $RLx11_complemento , 1, 0, 'C', 1);
        $pdf->cell(30, $iAltura, 'Nº Hidrometro'    , 1, 0, 'C', 1);
        $pdf->cell(20, $iAltura, 'Tipo'             , 1, 0, 'C', 1);
        $pdf->cell(20, $iAltura, $RLj50_descr       , 1, 0, 'C', 1);
        $pdf->cell(50, $iAltura, $RLj85_descr       , 1, 0, 'C', 1);
        $pdf->cell(20, $iAltura, $RLx04_dtinst      , 1, 0, 'C', 1);
        $pdf->cell(50, $iAltura, 'Observação'       , 1, 1, 'C', 1);
      }
      
      $iQuantidadeMatriculasLogradouro = 0;
      
      foreach ($oLogradouro as $sNumero => $oNumero) {
        
        foreach ($oNumero as $dDataInstalacao => $oDadosInstalacao) {
          
          if ($sOpcao == 'analitico') {
            
            $pdf->setfont('arial', '', 6);
            $pdf->cell(15, $iAltura, $oDadosInstalacao->iMatricula         , 0, 0, 'C', $fLinha);
            $pdf->cell(15, $iAltura, $sNumero                              , 0, 0, 'C', $fLinha);
            $pdf->cell(60, $iAltura, $oDadosInstalacao->sComplemento       , 0, 0, 'L', $fLinha);
            $pdf->cell(30, $iAltura, $oDadosInstalacao->sNumeroHidrometro  , 0, 0, 'C', $fLinha);
            $pdf->cell(20, $iAltura, $oDadosInstalacao->sTipoImovel        , 0, 0, 'L', $fLinha);
            $pdf->cell(20, $iAltura, $oDadosInstalacao->sDescricaoZona     , 0, 0, 'C', $fLinha);
            $pdf->cell(50, $iAltura, $oDadosInstalacao->sDescricaoZonaEntr , 0, 0, 'C', $fLinha);
            $pdf->cell(20, $iAltura, $dDataInstalacao                      , 0, 0, 'C', $fLinha);
            $pdf->cell(50, $iAltura, ''                                    , 0, 1, 'L', $fLinha);
          } 
          
          $iQuantidadeMatriculasLogradouro++;
        }
      }
      
      if ($sOpcao != 'analitico') {
      
        $pdf->setfont('arial', '', 6);
        $pdf->cell(90, $iAltura, $sLabelLogradouro                 , 0, 0, 'L', 0);
        $pdf->cell(30, $iAltura, $iQuantidadeMatriculasLogradouro  , 0, 1, 'L', 0);
      }
      
      $iTotalMatriculasBairro = $iTotalMatriculasBairro + $iQuantidadeMatriculasLogradouro;
      
      if ($sOpcao == 'analitico') {
        
        $pdf->setfont('arial', 'b', 7);
        $pdf->cell($iLargura, $iAltura, 'Total de Instalações no Logradouro: ' . $iQuantidadeMatriculasLogradouro, 'T', 1, 'R', 0);
      }
    }
    
    $iTotalMatriculasGeral = $iTotalMatriculasGeral + $iTotalMatriculasBairro;
    
    $pdf->setfont('arial', 'b', 7);
    $pdf->cell($iLargura, $iAltura, 'Total de Instalações no Bairro: ' . $iTotalMatriculasBairro, 'T', 1, 'R', 0);
    $pdf->ln();
  }

  $pdf->setfont('arial', 'b', 7);
  $pdf->cell($iLargura, $iAltura, 'Total de Instalações (Geral): ' . $iTotalMatriculasGeral, 'T', 1, 'R', 0);
  $pdf->ln();
 

  $pdf->Output();
?>