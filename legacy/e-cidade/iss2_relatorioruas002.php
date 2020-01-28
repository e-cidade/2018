<?
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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");

$oGet  = db_utils::postMemory($_GET);

$sWhere               = "";
$sAnd                 = "";
$sOrderBy             = "";

$sHeaderLogradouro    = "Logradouro: Todos";
$sHeaderTipoAtividade = "Tipo de Atividade: Todas";
$sHeaderTipoDebito    = "Tipo de Débito: Todos";
$sHeaderInscricao     = "Incrições: Todas";    
$sHeaderDataDebitos   = "Data: Todas";

/**
 * Verifica os logradouros selecionados no formulario.
 */
if (trim($oGet->logradouro) != "") {
  
  $sHeaderLogradouro = "Logradouro: ( {$oGet->logradouro} )";
  $sWhere           .= "{$sAnd} ruas.j14_codigo in($oGet->logradouro) ";
  $sAnd              = " and ";
}

/**
 * Verifica o tipo de atividade selecionados no formulario.
 */
if (trim($oGet->tipoatividade) != "") {
  
  $sHeaderTipoAtividade = "Tipo de Atividade: ( {$oGet->tipoatividade} )";
  $sWhere              .= "{$sAnd} ativid.q03_ativ in($oGet->tipoatividade) ";
  $sAnd                 = " and ";
}

/**
 * Verifica o tipo de débito selecionados no formulario.
 */
if (trim($oGet->tipodebito) != "") {
  
  $sHeaderTipoDebito = "Tipo de Débito: ( {$oGet->tipodebito} )";
  $sWhere           .= "{$sAnd} debitos.k22_tipo in($oGet->tipodebito) ";
  $sAnd              = " and ";
}

/**
 * Verifica incricoes selecionadas no formulario.
 */
if (trim($oGet->inscricao) != "") {
  
	if (trim($oGet->inscricao) == 'BA') {
		
	  $sHeaderInscricao  = "Incrições: Baixadas";
	  $sWhere           .= "{$sAnd} issbase.q02_dtbaix is not null ";
	  $sAnd              = " and ";
	} else if (trim($oGet->inscricao) == 'NBA') {
		
    $sHeaderInscricao  = "Incrições: Não Baixadas";
    $sWhere           .= "{$sAnd} issbase.q02_dtbaix is null ";
    $sAnd              = " and ";
	}
}

/**
 * Verifica a data na tabela debitos
 */
if (trim($oGet->datadebitos) != "") {
  
  $sDataDebitos        = db_formatar($oGet->datadebitos, 'd');
  $sHeaderDataDebitos  = "Data: {$sDataDebitos} ";
  $sWhere             .= "{$sAnd} debitos.k22_data = '{$oGet->datadebitos}' ";
  $sAnd                = " and ";
}

/**
 * Verifica ordem selecionada para o order by do sql.
 */
if (trim($oGet->ordenar) != "") {
  
  switch (trim($oGet->ordenar)) {

    case 'A':
      $sOrderBy = "ativid.q03_descr";
      break;

    case 'I':     
      $sOrderBy = "issbase.q02_inscr";
      break;

    case 'L':
      $sOrderBy = "ruas.j14_nome";
      break;

    case 'N':
      $sOrderBy = "ruas.j14_nome asc, issruas.q02_numero asc";
      break;
  }

}
	
if (isset($sWhere) && !empty($sWhere)) {
	$sWhere = "where {$sWhere} ";
}

if (isset($sOrderBy) && !empty($sOrderBy)) {
  $sOrderBy = "order by {$sOrderBy} ";
}

$head2 = "RELATÓRIO COM DÉBITO POR LOGRADOURO";
$head4 = $sHeaderLogradouro;
$head5 = $sHeaderTipoAtividade;
$head6 = $sHeaderTipoDebito;
$head7 = $sHeaderInscricao;
$head8 = $sHeaderDataDebitos;

$sSql  = "    select distinct issbase.q02_inscr,                                                              ";
$sSql .= "           cgm.z01_cgccpf,                                                                          ";
$sSql .= "           issbase.q02_numcgm,                                                                      ";
$sSql .= "           cgm.z01_nome,                                                                            ";
$sSql .= "           ruas.j14_nome,                                                                           ";
$sSql .= "           issruas.q02_numero,                                                                      ";
$sSql .= "           issruas.q02_compl,                                                                       ";
$sSql .= "           ativid.q03_descr,                                                                        ";
$sSql .= "           debitos.k22_vlrhis,                                                                      ";
$sSql .= "           debitos.k22_vlrcor,                                                                      ";
$sSql .= "           debitos.k22_juros,                                                                       ";
$sSql .= "           debitos.k22_multa,                                                                       ";
$sSql .= "           round(sum(debitos.k22_vlrcor + debitos.k22_juros + debitos.k22_multa),2) as total_debito ";
$sSql .= "     from  issqn.issbase                                                                            ";
$sSql .= "           inner join protocolo.cgm      on issbase.q02_numcgm  = cgm.z01_numcgm                    ";
$sSql .= "           inner join issqn.tabativ      on issbase.q02_inscr   = tabativ.q07_inscr                 ";
$sSql .= "           inner join issqn.ativprinc    on ativprinc.q88_inscr = tabativ.q07_inscr                 ";
$sSql .= "                                        and ativprinc.q88_seq   = tabativ.q07_seq                   ";
$sSql .= "           inner join issqn.ativid       on ativid.q03_ativ     = tabativ.q07_ativ                  ";
$sSql .= "           inner join caixa.arreinscr    on issbase.q02_inscr   = arreinscr.k00_inscr               ";
$sSql .= "           inner join caixa.debitos      on debitos.k22_numpre  = arreinscr.k00_numpre              ";
$sSql .= "           inner join caixa.arretipo     on debitos.k22_tipo    = arretipo.k00_tipo                 ";
$sSql .= "           inner join issqn.issruas      on issruas.q02_inscr   = issbase.q02_inscr                 ";
$sSql .= "           inner join cadastro.ruas      on issruas.j14_codigo  = ruas.j14_codigo                   ";
$sSql .= "  {$sWhere}                                                                                         ";
$sSql .= "  group by issbase.q02_inscr,                                                                       ";
$sSql .= "           cgm.z01_cgccpf,                                                                          ";
$sSql .= "           issbase.q02_numcgm,                                                                      ";
$sSql .= "           cgm.z01_nome,                                                                            ";
$sSql .= "           ruas.j14_nome,                                                                           ";
$sSql .= "           issruas.q02_numero,                                                                      ";
$sSql .= "           issruas.q02_compl,                                                                       ";
$sSql .= "           ativid.q03_descr,                                                                        ";
$sSql .= "           debitos.k22_vlrhis,                                                                      ";
$sSql .= "           debitos.k22_vlrcor,                                                                      ";
$sSql .= "           debitos.k22_juros,                                                                       ";
$sSql .= "           debitos.k22_multa                                                                        ";
$sSql .= " {$sOrderBy}                                                                                        ";

$rsSqlDebitos = db_query($sSql);
$iNumRows     = pg_numrows($rsSqlDebitos);
if ($iNumRows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 

$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$iPrenc   = 1;
$iAlt     = 4;
$lMostrar = true;

for ($i = 0; $i < $iNumRows; $i++) {
	
	$oDebitos = db_utils::fieldsMemory($rsSqlDebitos, $i);
	
  imprimeCabecalho($pdf, $iAlt, $lMostrar);
  $lMostrar = false;

  if ( $iPrenc == 0 ) {
    $iPrenc = 1;
  } else {
    $iPrenc = 0;
  }

  $pdf->setfont('arial','',6);
  $pdf->cell(15,$iAlt,$oDebitos->q02_inscr                                                  ,0,0,"C",$iPrenc);
  
  $sCpfCnpj = '';
  if (!empty($oDebitos->z01_cgccpf)) {
  	
	  if (trim(strlen($oDebitos->z01_cgccpf)) == 11) {
	  	$sCpfCnpj = db_formatar($oDebitos->z01_cgccpf, 'cpf');
	  } else {
	  	$sCpfCnpj = db_formatar($oDebitos->z01_cgccpf, 'cnpj');
	  }
  }
  
  $pdf->cell(23,$iAlt,$sCpfCnpj                                                             ,0,0,"L",$iPrenc);
  $pdf->cell(33,$iAlt,substr($oDebitos->z01_nome, 0, 20)                                    ,0,0,"L",$iPrenc);
  $pdf->cell(33,$iAlt,substr($oDebitos->j14_nome, 0, 20)                                    ,0,0,"L",$iPrenc);
  $pdf->cell(15,$iAlt,$oDebitos->q02_numero                                                 ,0,0,"C",$iPrenc);
  $pdf->cell(28,$iAlt,substr($oDebitos->q02_compl, 0, 10)                                   ,0,0,"L",$iPrenc);
  $pdf->cell(28,$iAlt,substr($oDebitos->q03_descr, 0, 18)                                   ,0,0,"L",$iPrenc);   
  $pdf->cell(22,$iAlt,db_formatar($oDebitos->k22_vlrhis, 'f')                               ,0,0,"R",$iPrenc);
  $pdf->cell(22,$iAlt,db_formatar($oDebitos->k22_vlrcor, 'f')                               ,0,0,"R",$iPrenc);
  $pdf->cell(22,$iAlt,db_formatar($oDebitos->k22_juros, 'f')                                ,0,0,"R",$iPrenc);
  $pdf->cell(22,$iAlt,db_formatar($oDebitos->k22_multa, 'f')                                ,0,0,"R",$iPrenc);
  $pdf->cell(20,$iAlt,db_formatar($oDebitos->total_debito, 'f')                             ,0,1,"R",$iPrenc);
}

$pdf->setfont('arial','b',8);
$pdf->cell(283,1,'',"T",1,"L",0);
$pdf->cell(263,$iAlt,'TOTAL DE REGISTROS:'                                                        ,0,0,"R",0);
$pdf->cell(20,$iAlt,$iNumRows                                                                     ,0,0,"R",0);
$pdf->Output();

/*
 * Imprime cabeçalho do relatorio
 */
function imprimeCabecalho($pdf, $iAlt, $lMostrar) {

	if ($pdf->gety() > $pdf->h - 30 || $lMostrar ) {
		
	  $pdf->addpage("L");
	  $pdf->setfont('arial','b',8);
	
	  $pdf->cell(15,$iAlt,'Inscrição'                                                               ,1,0,"C",1);
	  $pdf->cell(23,$iAlt,'CNPJ/CPF'                                                                ,1,0,"C",1);
	  $pdf->cell(33,$iAlt,'Nome'                                                                    ,1,0,"C",1);
	  $pdf->cell(33,$iAlt,'Logradouro'                                                              ,1,0,"C",1);
	  $pdf->cell(15,$iAlt,'Número'                                                                  ,1,0,"C",1);
	  $pdf->cell(28,$iAlt,'Complemento'                                                             ,1,0,"C",1);
	  $pdf->cell(28,$iAlt,'Atividade'                                                               ,1,0,"C",1);
	  
	  $pdf->cell(22,$iAlt,'Vlr. Original'                                                           ,1,0,"C",1);
	  $pdf->cell(22,$iAlt,'Vlr. Corrigido'                                                          ,1,0,"C",1);
	  $pdf->cell(22,$iAlt,'Vlr. Juros'                                                              ,1,0,"C",1);
	  $pdf->cell(22,$iAlt,'Vlr. Multa'                                                              ,1,0,"C",1);
	  $pdf->cell(20,$iAlt,'Vlr. Total'                                                              ,1,1,"C",1);
	}
}
?>