<?php
/**
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
require_once(modification('fpdf151/pdf.php'));
require_once(modification('libs/db_utils.php'));

$oDaoLabControleFisFin = new cl_lab_controlefisicofinanceiro();

/* Cabeçalho dos passageiros normais*/
function novoCabecalho($oPdf, $oDados, $fTotal, $fLimite) {

  $oPdf->setfont('arial', 'B', 8);
  $iTam = 3.5;
  $lCor = true;
  
  if ($fTotal != -1) {

    $oPdf->cell(150, $iTam, "Total ", 1, 0, 'R', $lCor);
    $oPdf->cell(20, $iTam, number_format($fLimite, 2, ',', '.'), 1, 0, 'R', $lCor);
    $oPdf->cell(20, $iTam, number_format($fTotal, 2, ',', '.'), 1, 1, 'R', $lCor);
    $oPdf->cell(190, $iTam, "", 0, 1, 'L', false);
  }
  
  if ($oDados->itpcontrole > 0 && $oDados->itpcontrole < 4 || $oDados->itpcontrole == 9) {
    $oPdf->cell(190, $iTam, $oDados->idepartamento." - ".$oDados->sdepartamento, 1, 1, 'L', $lCor);
  } elseif ($oDados->itpcontrole > 3 && $oDados->itpcontrole < 7) {
    $oPdf->cell(190,  $iTam, $oDados->slaboratorio, 1, 1, 'L', $lCor);
  }

  $oPdf->cell(10,  $iTam, 'Cod.', 1, 0, 'C', $lCor);
  if ($oDados->itpcontrole == 1) {
    $oPdf->cell(75,  $iTam, 'Departamento', 1, 0, 'C', $lCor);
  } elseif ($oDados->itpcontrole == 9 || $oDados->itpcontrole == 4) {
    $oPdf->cell(75,  $iTam, 'Laboratorio', 1, 0, 'C', $lCor);
  } elseif ($oDados->itpcontrole == 3 || $oDados->itpcontrole == 6 || $oDados->itpcontrole == 7) {

    $oPdf->cell(25,  $iTam, 'Grupo', 1, 0, 'C', $lCor);
    $oPdf->cell(25,  $iTam, 'Sub.Grupo', 1, 0, 'C', $lCor);
    $oPdf->cell(25,  $iTam, 'For.Organiz.', 1, 0, 'C', $lCor);
  } elseif ($oDados->itpcontrole == 2 || $oDados->itpcontrole == 5 || $oDados->itpcontrole == 8) {
    $oPdf->cell(75,  $iTam, 'Exame', 1, 0, 'C', $lCor);
  }

  $oPdf->cell(15,  $iTam, 'Inicio', 1, 0, 'C', $lCor);
  $oPdf->cell(15,  $iTam, 'Fim', 1, 0, 'C', $lCor);
  $oPdf->cell(15,  $iTam, 'Periodo', 1, 0, 'C', $lCor);
  $oPdf->cell(20,  $iTam, 'Teto', 1, 0, 'C', $lCor);
  $oPdf->cell(20,  $iTam, 'Limite', 1, 0, 'C', $lCor);
  $oPdf->cell(20,  $iTam, 'Utilizado', 1, 1, 'C', $lCor);
}

/* Linha Normal */
function novaLinha($oPdf, $oDados, $dDataIniDB, $dDataFimDB) {

  $oPdf->setfont('arial', '', 7);
  $iTam = 3.5;
  $lCor = false;

  $oPdf->cell(10,  $iTam, $oDados->icodigo, 1, 0, 'C', $lCor);
  
  if ($oDados->itpcontrole == 1) {
    $oPdf->cell(75, $iTam, $oDados->idepartamento." - ".$oDados->sdepartamento, 1, 0, 'L', $lCor);
  } elseif ($oDados->itpcontrole == 9  || $oDados->itpcontrole == 4) {
    $oPdf->cell(75, $iTam, substr($oDados->slaboratorio,0,45), 1, 0, 'L', $lCor);
  } elseif ($oDados->itpcontrole == 3 || $oDados->itpcontrole == 6 || $oDados->itpcontrole == 7) {

    $oPdf->cell(25, $iTam, $oDados->igrupo, 1, 0, 'C', $lCor);
    $oPdf->cell(25, $iTam, $oDados->isubgrupo, 1, 0, 'C', $lCor);
    $oPdf->cell(25, $iTam, $oDados->formorg, 1, 0, 'C', $lCor);
  } elseif ($oDados->itpcontrole == 2 || $oDados->itpcontrole == 5 || $oDados->itpcontrole == 8) {
    $oPdf->cell(75, $iTam, $oDados->sexame, 1, 0, 'L', $lCor);
  }

  $oPdf->cell(15, $iTam, $oDados->dtinicio, 1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, $oDados->dtfim, 1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, $oDados->speriodo, 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $oDados->steto, 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, number_format($oDados->flimite, 2, ',', '.'), 1, 0, 'R', $lCor);
  
  //Select Descobre o valor utilizado
  if ($oDados->iteto == 1) {
    $sSql = 'select count(*) as valor from lab_autoriza as lat ';
  } else {
    $sSql = 'select sum((prc.sd63_f_sh+prc.sd63_f_sa+prc.sd63_f_sp+la53_n_acrescimo)* lri.la21_i_quantidade) as valor from lab_autoriza as lat ';
  }
  $sJoin  = ' left join lab_requisicao   as lrq on lrq.la22_i_codigo     = lat.la48_i_requisicao';
  $sJoin .= ' left join lab_requiitem    as lri on lri.la21_i_requisicao = lrq.la22_i_codigo';
  $sJoin .= ' left join lab_setorexame   as lse on lse.la09_i_codigo     = lri.la21_i_setorexame';
  $sJoin .= ' left join lab_labsetor     as lls on lls.la24_i_codigo     = lse.la09_i_labsetor';
  $sJoin .= ' left join lab_exame        as lex on lex.la08_i_codigo     = lse.la09_i_exame';
  $sJoin .= ' left join lab_exameproced  as lep on lep.la53_i_exame      = lex.la08_i_codigo and la53_i_ativo = 1 ';
  $sJoin .= ' left join sau_procedimento as prc on prc.sd63_i_codigo     = lep.la53_i_procedimento';
  $sJoin .= ' left join (select distinct on (sd60_c_grupo) * from sau_grupo) as sgp ';
  $sJoin .= ' on sgp.sd60_c_grupo = substr(prc.sd63_c_procedimento, 1, 2) ';
  $sJoin .= ' left join (select distinct on (sd61_c_subgrupo) * from sau_subgrupo) as ssg ';
  $sJoin .= ' on ssg.sd61_c_subgrupo = substr(prc.sd63_c_procedimento,3,2) ';

  $sWhere  = ' where ';
  $sWhere .= " la22_d_data between '".$dDataIniDB."' and '".$dDataFimDB."' ";
  $sWhere .= " and lri.la21_d_data between '".$dDataIniDB."' and '".$dDataFimDB."' ";

  if ($oDados->itpcontrole == 0) {

    $sWhere .= ' and lrq.la22_i_departamento = '.$oDados->idepartamento;
    $sWhere .= ' and lrq.la22_i_departamento = '.$oDados->ilaboratorio;
  } elseif ($oDados->itpcontrole == 1) {
    $sWhere .= ' and lrq.la22_i_departamento = '.$oDados->idepartamento;
  } elseif ($oDados->itpcontrole == 2) {

    $sWhere .= ' and lrq.la22_i_departamento = '.$oDados->idepartamento;
    $sWhere .= ' and lex.la08_i_codigo   = '.$oDados->iexame;
  } elseif ($oDados->itpcontrole == 3) {

    $sWhere .= ' and lrq.la22_i_departamento = '.$oDados->idepartamento;
    $sWhere .= ' and substr(prc.sd63_c_procedimento,1,2) = '.$oDados->igrupo;
    $sWhere .= ' and substr(prc.sd63_c_procedimento,3,2) = '.$oDados->isubgrupo;
    $sWhere .= ' and substr(prc.sd63_c_procedimento,5,2) = '.$oDados->formorg;
  } elseif ($oDados->itpcontrole == 4) {
    $sWhere .= ' and lls.la24_i_laboratorio = '.$oDados->ilaboratorio;
  } elseif ($oDados->itpcontrole == 5) {

    $sWhere .= ' and lls.la24_i_laboratorio = '.$oDados->ilaboratorio;
    $sWhere .= ' and lex.la08_i_codigo  = '.$oDados->iexame;
  } elseif ($oDados->itpcontrole == 6) {

    $sWhere .= ' and lls.la24_i_laboratorio = '.$oDados->ilaboratorio;
    $sWhere .= ' and substr(prc.sd63_c_procedimento,1,2) = '.$oDados->igrupo;
    $sWhere .= ' and substr(prc.sd63_c_procedimento,3,2) = '.$oDados->isubgrupo;
    $sWhere .= ' and substr(prc.sd63_c_procedimento,5,2) = '.$oDados->formorg;
  } elseif ($oDados->itpcontrole == 7) {

    $sWhere .= ' and substr(prc.sd63_c_procedimento,1,2) = '.$oDados->igrupo;
    $sWhere .= ' and substr(prc.sd63_c_procedimento,3,2) = '.$oDados->isubgrupo;
    $sWhere .= ' and substr(prc.sd63_c_procedimento,5,2) = '.$oDados->formorg;
  } elseif ($oDados->itpcontrole == 8) {
    $sWhere .= ' and lex.la08_i_codigo  = '.$oDados->iexame;
  } elseif ($oDados->itpcontrole == 9) {

    $sWhere .= ' and lrq.la22_i_departamento = '.$oDados->idepartamento;
    $sWhere .= ' and lls.la24_i_laboratorio = '.$oDados->ilaboratorio;
  }

  $rs = db_query($sSql.$sJoin.$sWhere);

  if(!$rs) {
    throw new DBException("Erro ao buscar o valor utilizado.");
  }

  if(pg_num_rows($rs) == 0) {
    throw new BusinessException("Nenhum procedimento encontrado.");
  }

  $oUtilizado = db_utils::fieldsmemory($rs, 0);

  $oPdf->cell(20, $iTam, number_format($oUtilizado->valor, 2, ',', '.'), 1, 1, 'R', $lCor);

  if ($oUtilizado->valor == null || $oUtilizado->valor == "") {
    return 0;
  } else {
    return $oUtilizado->valor;
  }
}

try {

  $sCampos  = 'la56_i_tipocontrole          as itpcontrole,';
  $sCampos .= 'la56_i_codigo                as icodigo,';
  $sCampos .= 'la02_i_codigo                as ilaboratorio,';
  $sCampos .= 'la02_c_descr                 as slaboratorio,';
  $sCampos .= 'la08_i_codigo                as iexame,';
  $sCampos .= 'la08_c_descr                 as sexame,';
  $sCampos .= 'sau_grupo.sd60_c_grupo       as igrupo,';
  $sCampos .= 'sau_subgrupo.sd61_c_subgrupo as isubgrupo,';
  $sCampos .= 'sd62_c_formaorganizacao      as formorg,';
  $sCampos .= 'la56_d_ini                   as dtinicio,';
  $sCampos .= 'la56_d_fim                   as dtfim,';
  $sCampos .= 'la56_n_limite                as flimite,';
  $sCampos .= 'coddepto                     as idepartamento,';
  $sCampos .= 'descrdepto                   as sdepartamento,';
  $sCampos .= 'la56_i_teto                  as iteto,';
  $sCampos .= "case when la56_i_periodo = 1 then 'Diario' else 'Mensal' end as speriodo,";
  $sCampos .= "case when la56_i_teto = 1 then 'Fisico' else 'Financeiro' end as steto";

  $dDataIniDB = implode('-',array_reverse(explode('/',$dDataIni)));
  $dDataFimDB = implode('-',array_reverse(explode('/',$dDataFim)));
  $sWhere   = " la56_d_ini >= '".$dDataIniDB."'";
  $sWhere  .= " and la56_d_ini <= '".$dDataFimDB."'";

  if ($iTpcontrole == 1) {

    $sOrder = 'idepartamento,iexame,ilaboratorio';
    if ($iValor1 != -1) {
      $sWhere .= 'and la56_i_depto = '.$iValor1;
    }
  } elseif ($iTpcontrole == 2) {

    $sOrder = 'ilaboratorio,idepartamento,iexame';
    if ($iValor1 != -1) {
      $sWhere .= 'and la56_i_laboratorio = '.$iValor1;
    }
  } elseif ($iTpcontrole == 3) {

    $sOrder = 'igrupo, isubgrupo, formorg, idepartamento, iexame, ilaboratorio';
    if ($iValor1 != -1) {

      $sWhere .= " and sd60_c_grupo = '".$iValor1."'";
      $sWhere .= " and sd61_c_subgrupo = '".$iValor2."'";
      $sWhere .= " and sd62_c_formaorganizacao = '".$iValor3."'";
    }
  } elseif ($iTpcontrole == 4) {

    $sOrder = 'iexame';
    if ($iValor1 != -1) {
      $sWhere .= ' and la08_i_codigo = '.$iValor1;
    }
  }

  $sSql = $oDaoLabControleFisFin->sql_query_controle(null, $sCampos, $sOrder, $sWhere);
  $rs   = db_query($sSql);

  if(!$rs) {
    throw new DBException("Erro ao buscar as informações dos laboratórios.");
  }

  if (pg_num_rows($rs) == 0) {
    throw new BusinessException("Nenhum registro encontrado");
  }

  $oPdf = new PDF();
  $oPdf->Open();
  $oPdf->AliasNbPages();
  $oPdf->setLeftMargin(8.5);

  //Cabeçalho
  $head1 = "RELATÓRIO CONTROLE FINANCEIRO DO LABORATÓRIO";
  $head2 = " ".$sQuebraLabel.": ".(($iValor1==-1)?'Todos':$iValor1);
  $head3 = " Periodo : ".$dDataIni." a ".$dDataFim;

  $oPdf->addpage('P');
  $oPdf->setfillcolor(235);

  $iQuebra     = 0;
  $lQuebra     = true;
  $iTpCtrAtual = 0;
  $fTotal      = -1;
  $fLimite     = 0;
  $iLinhas     = pg_num_rows($rs);

  for ($iInd = 0; $iInd < $iLinhas; $iInd++) {

    $oDados = db_utils::fieldsmemory($rs, $iInd, true);

    if ($oDados->itpcontrole > 0 && $oDados->itpcontrole < 4 || $oDados->itpcontrole == 9) {

      if ($oDados->idepartamento != $iQuebra || $iTpCtrAtual != $oDados->itpcontrole) {

        $iQuebra     = $oDados->idepartamento;
        $lQuebra     = true;
        $iTpCtrAtual = $oDados->itpcontrole;
      }
    } elseif ($oDados->itpcontrole > 3 && $oDados->itpcontrole < 7) {

      if ($oDados->iexame != $iQuebra || $iTpCtrAtual != $oDados->itpcontrole) {

        $iQuebra     = $oDados->iexame;
        $lQuebra     = true;
        $iTpCtrAtual = $oDados->itpcontrole;
      }
    }

    if ( ($oPdf->GetY() > $oPdf->h -25) || $lQuebra == true) {

      novoCabecalho($oPdf, $oDados, $fTotal, $fLimite);
      $lQuebra = false;
      $fTotal  = 0;
      $fLimite = 0;
    }

    $fTotal  += novaLinha($oPdf, $oDados, $dDataIniDB, $dDataFimDB);
    $fLimite += $oDados->flimite;
  }

  if ($fTotal != -1) {

    $oPdf->cell(150, 3.5, " Total ", 1, 0, 'R', true);
    $oPdf->cell(20, 3.5, ''.number_format($fLimite, 2, ',', '.'), 1, 0, 'R', true);
    $oPdf->cell(20, 3.5, ''.number_format($fTotal, 2, ',', '.'), 1, 1, 'R', true);
    $oPdf->cell(190, 3.5, "", 0, 1, 'L', false);
  }
} catch(Exception $oErro) {
  db_redireciona( "db_erros.php?fechar=true&db_erro={$oErro->getMessage()}");
}

$oPdf->Output();