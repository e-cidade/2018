<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//echo "aqui"; exit();

if (!isset($arqinclude)){
  
  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_utils.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_liborcamento.php");
  include("classes/db_orcparamrel_classe.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_orcparamrelopcre_classe.php");
  
  $classinatura = new cl_assinatura;
  $orcparamrel  = new cl_orcparamrel;
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
}

include_once("classes/db_conrelinfo_classe.php");
include_once("classes/db_conrelvalor_classe.php");
include_once("classes/db_orcparamrelopcre_classe.php");
include_once("classes/db_orcparamelemento_classe.php");
include_once("libs/db_utils.php");
$head2          = "RELATORIO DE EXAMES CONFIRMADOS";
$sOrder         = "";
$sWhere         = "";
$sCampoVerifica = "s110_i_codigo"; 
if (isset($classificacao) && $classificacao == 1) {
  $sOrder = "z01_nome, s130_c_descricao, s108_c_exame,z01_v_nome";
}else if(isset($classificacao) && $classificacao == 2) {
  $sOrder = "z01_nome, s108_c_exame,z01_v_nome";
} else if(isset($classificacao) && $classificacao == 3) {
  
  $sOrder         = "s130_c_descricao, z01_nome,s108_c_exame,z01_v_nome";
  $sCampoVerifica = "s130_i_codigo";
  
}

if (isset($perini) && trim($perini) != "" && isset($perfim) && trim($perfim) != ""){
  
  if ($sWhere != "") {
    $sWhere .= " and ";
  }
  $sWhere .= " s113_d_exame between '".implode("-", array_reverse(explode("/",$perini)))."'";
  $sWhere .= " and '".implode("-", array_reverse(explode("/",$perfim)))."'";
}
//Protocolados
if (isset($protocolados) && $protocolados == 1) {

  if ($sWhere != "") {
    $sWhere .= " and ";
  }
  $sWhere .= " s133_i_codigo is not null"; 
} else if (isset($protocolados) && $protocolados == 2) {
  
  if ($sWhere != "") {
    $sWhere .= " and ";
  }
  $sWhere .= " s133_i_codigo is null";
	
}
//Prestadora 
if (isset($prestadora) && trim($prestadora) != "") {

  if ($sWhere != "") {
    $sWhere .= " and ";
  }
  $sWhere .= "s110_i_codigo = {$prestadora}";	
}
//Exame
if(isset($exame) && trim($exame) != ""){
	
   if ($sWhere != "") {
    $sWhere .= " and ";
  }
  $sWhere .= "s108_i_codigo = {$exame}";
  
}
if(isset($grupo) && trim($grupo) != ""){
	
   if ($sWhere != "") {
    $sWhere .= " and ";
  }
  $sWhere .= "s108_i_grupoexame = {$grupo}";
  
}

//Verifica qual layout utilizar
if(isset($producao) && $producao == 1){
	
  
  $sSql   = "select s113_i_codigo,";
  $sSql  .= "        s133_c_protocolo,";
  $sSql  .= "        z01_v_nome, ";
  $sSql  .= "        s110_i_codigo,"; 
  $sSql  .= "        z01_nome, ";
  $sSql  .= "        z01_ender, ";
  $sSql  .= "        z01_bairro, ";
  $sSql  .= "        z01_munic, ";
  $sSql  .= "        z01_i_cgsund, ";
  $sSql  .= "        s108_i_codigo,";
  $sSql  .= "        s108_c_exame, ";
  $sSql  .= "        s108_i_grupoexame, ";
  $sSql  .= "        s113_c_encaminhamento,";
  $sSql  .= "        s113_d_exame, ";
  $sSql  .= "        s130_i_codigo, ";
  $sSql  .= "        s130_c_descricao, ";
  $sSql  .= "        s113_c_hora,   ";
  $sSql  .= "        z01_d_nasc,  ";
  $sSql  .= "        z01_v_cgccpf,s133_i_codigo, ";
  $sSql  .= "        s133_c_observacoes";
  $sSql  .= "   from sau_agendaexames";
  $sSql  .= "        inner join sau_prestadorhorarios on s112_i_codigo = s113_i_prestadorhorarios";
  $sSql  .= "        inner join cgs_und on z01_i_cgsund = s113_i_numcgs  ";
  $sSql  .= "        left join sau_agendaexameconfirma on s113_i_codigo = s133_i_agendaexames ";
  $sSql  .= "        inner join sau_prestadorvinculos on s111_i_codigo = s112_i_prestadorvinc ";
  $sSql  .= "        inner join sau_prestadores on s110_i_codigo = s111_i_prestador ";
  $sSql  .= "        inner join cgm on s110_i_numcgm = z01_numcgm "; 
  $sSql  .= "        inner join sau_exames on s111_i_exame = s108_i_codigo ";
  $sSql  .= "        left join sau_grupoexames on s130_i_codigo = s108_i_grupoexame";
  $sSql  .= "  where {$sWhere}";
  $sSql  .= "  order by {$sOrder}";
  $rsRelatorio = db_query($sSql);
  $aRegistros  = db_utils::getColectionByRecord($rsRelatorio);
  if (pg_num_rows($rsRelatorio) == 0) {
    db_redireciona("db_erros.php?fechar=true&db_erro=Não há dados a serem impressos");
  }
  $pdf = new PDF("L", "mm", "A4"); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->SetAutoPageBreak(false);
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt            = 4;
  $pagina         = 1;
  $pdf->addpage();
  $sValorVerificarAnterior = 0;  
  foreach ($aRegistros as $oLinhaRelatório) {
        
    if ($classificacao  == 1) {
        $sValorVerificar = "{$oLinhaRelatório->s110_i_codigo}{$oLinhaRelatório->s108_i_grupoexame}{$oLinhaRelatório->s108_i_codigo}";
      } else if ($classificacao  == 2) {
        $sValorVerificar = "{$oLinhaRelatório->s110_i_codigo}{$oLinhaRelatório->s108_i_codigo}";
      } else if ($classificacao  == 3) {
       $sValorVerificar = "{$oLinhaRelatório->s108_i_grupoexame}{$oLinhaRelatório->s110_i_codigo}{$oLinhaRelatório->s108_i_codigo}";
    }
    if ($sValorVerificarAnterior != $sValorVerificar) {    
      
      if ($pdf->GetY() > $pdf->h -25) {
        $pdf->AddPage();
      }
      
      $pdf->setfont('arial','',10);
      $pdf->cell(165,$alt,'Relatório por Prestadoras','',0,"L",0);
      $pdf->cell(25,$alt,'','',1,"R",0);
      $pdf->cell(25,$alt,'Prestadora :',0,0,"L",0);
      $pdf->cell(110,$alt,"{$oLinhaRelatório->s110_i_codigo} - {$oLinhaRelatório->z01_nome}",0,0,"L",0);
      $pdf->cell(25,$alt,'Endereço   :',0,0,"L",0);
      $pdf->cell(110,$alt,$oLinhaRelatório->z01_ender,0,1,"L",0);
      $pdf->cell(25,$alt,'','',0,"L",0);
      $pdf->cell(110,$alt,'','',0,"L",0);
      $pdf->cell(25,$alt,'Cidade       :','',0,"L",0);
      $pdf->cell(30,$alt,$oLinhaRelatório->z01_munic,'',0,"L",0);
      $pdf->cell(25,$alt,'Bairro :','',0,"L",0);
      $pdf->cell(30,$alt, $oLinhaRelatório->z01_bairro,'',1,"L",0);
      $pdf->cell(25,$alt,'Exame      :','',0,"L",0);
      $pdf->cell(110,$alt,"{$oLinhaRelatório->s108_i_codigo} - {$oLinhaRelatório->s108_c_exame}",'',0,"L",0);
      $pdf->cell(25,$alt,'Grupo       :','',0,"L",0);
      $pdf->cell(110,$alt,"{$oLinhaRelatório->s130_i_codigo} - {$oLinhaRelatório->s130_c_descricao}",'',1,"L",0);
      $pdf->cell(25,$alt,'','',0,"L",0);
      $pdf->cell(110,$alt,'','',0,"L",0);
      $pdf->cell(25,$alt,'Período  :','',0,"L",0);
      $pdf->cell(110,$alt,"{$perini} à {$perfim}",'',1,"L",0);
      $pdf->cell(90,$alt,'Pacientes','RT',0,"C",1);
      $pdf->cell(30,$alt,'Dia / Hora','RT',0,"C",1);
      $pdf->cell(25,$alt,'Protocolo','RT',0,"C",1);
      $pdf->cell(35,$alt,'Encaminhamento','RT',0,"C",1);
      $pdf->cell(90,$alt,'Profissional','T',1,"C",1);
      
    }
    
    
  	$pdf->cell(90,$alt,$oLinhaRelatório->z01_v_nome,'RTB',0,"L",0);
  	$pdf->cell(30,$alt,db_formatar($oLinhaRelatório->s113_d_exame,"d")." ".$oLinhaRelatório->s113_c_hora,'RTB',0,"C",0);
  	$pdf->cell(25,$alt,$oLinhaRelatório->s133_c_protocolo,'RTB',0,"C",0);
  	$pdf->cell(35,$alt,$oLinhaRelatório->s113_c_encaminhamento,'RTB',0,"C",0);
  	$pdf->cell(90,$alt,'','TB',1,"L",0);
    if ($classificacao  == 1) {
      $sValorVerificarAnterior = "{$oLinhaRelatório->s110_i_codigo}{$oLinhaRelatório->s108_i_grupoexame}{$oLinhaRelatório->s108_i_codigo}";
    } else if ($classificacao  == 2) {
      $sValorVerificarAnterior = "{$oLinhaRelatório->s110_i_codigo}{$oLinhaRelatório->s108_i_codigo}";
    } else if ($classificacao  == 3) {
      $sValorVerificarAnterior = "{$oLinhaRelatório->s108_i_grupoexame}{$oLinhaRelatório->s110_i_codigo}{$oLinhaRelatório->s108_i_codigo}";
    }
  }
}else if (isset($producao) && $producao == 2){
  
  
  $sSql   = "select distinct";
  $sSql  .= "        s108_i_codigo,";
  $sSql  .= "        s108_c_exame, ";
  $sSql  .= "        s113_d_exame, ";
  $sSql  .= "        s130_i_codigo, ";
  $sSql  .= "        s130_c_descricao ";
  $sSql  .= "   from sau_agendaexames";
  $sSql  .= "        inner join sau_prestadorhorarios on s112_i_codigo = s113_i_prestadorhorarios";
  $sSql  .= "        inner join cgs_und on z01_i_cgsund = s113_i_numcgs  ";
  $sSql  .= "        left  join sau_agendaexameconfirma on s113_i_codigo = s133_i_agendaexames ";
  $sSql  .= "        inner join sau_prestadorvinculos on s111_i_codigo = s112_i_prestadorvinc ";
  $sSql  .= "        inner join sau_prestadores on s110_i_codigo = s111_i_prestador ";
  $sSql  .= "        inner join cgm on s110_i_numcgm = z01_numcgm "; 
  $sSql  .= "        inner join sau_exames on s111_i_exame = s108_i_codigo ";
  $sSql  .= "        left join sau_grupoexames on s130_i_codigo = s108_i_grupoexame";
  $sSql  .= "  where {$sWhere}";
  $sSql  .= "  order by s108_i_codigo";
  $rsRelatorio = db_query($sSql);
  $aRegistros  = db_utils::getColectionByRecord($rsRelatorio);
  if (pg_num_rows($rsRelatorio) == 0) {
   db_redireciona("db_erros.php?fechar=true&db_erro=Não há dados a serem impressos");
  }
  
  
  $pdf = new PDF("P", "mm", "A4"); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt            = 4;
  $pagina         = 1;
  $pdf->addpage();
  $pdf->setfont('arial','',10);
  $pdf->cell(165,$alt,'Relatório por Prestadoras','',0,"L",0);
  $pdf->cell(25,$alt,'','',1,"R",0);
  foreach ($aRegistros as $oLinhaRelatório) {
    
    $pdf->setfont('arial','',10);
    $pdf->cell(15,$alt,'Exame      :','',0,"L",0);
    $pdf->cell(110,$alt,"{$oLinhaRelatório->s108_i_codigo} - {$oLinhaRelatório->s108_c_exame}",'',0,"L",0);
    $pdf->cell(15,$alt,'Grupo      :','',0,"L",0);
    $pdf->cell(110,$alt,"{$oLinhaRelatório->s130_i_codigo} - {$oLinhaRelatório->s130_c_descricao}",'',1,"L",0);
    $pdf->cell(15,$alt,'Período  :','',0,"L",0);
    $pdf->cell(110,$alt,"{$perini} à {$perfim}",'',1,"L",0);
    $pdf->Ln();
    $pdf->cell(160,$alt,'Prestadora','RT',0,"C" ,1);
    $pdf->cell(30,$alt,'Quantidade','T',1,"C", 1);
    $sSql   = "select  z01_nome,";
    $sSql  .= "        count(*) as total";
    $sSql  .= " from sau_agendaexames";
    $sSql  .= "        inner join sau_prestadorhorarios on s112_i_codigo = s113_i_prestadorhorarios";
    $sSql  .= "        inner join cgs_und on z01_i_cgsund = s113_i_numcgs  ";
    $sSql  .= "        left  join sau_agendaexameconfirma on s113_i_codigo = s133_i_agendaexames ";
    $sSql  .= "        inner join sau_prestadorvinculos on s111_i_codigo = s112_i_prestadorvinc ";
    $sSql  .= "        inner join sau_prestadores on s110_i_codigo = s111_i_prestador ";
    $sSql  .= "        inner join cgm on s110_i_numcgm = z01_numcgm "; 
    $sSql  .= "        inner join sau_exames on s111_i_exame = s108_i_codigo ";
    $sSql  .= "        left join sau_grupoexames on s130_i_codigo = s108_i_grupoexame";
    $sSql  .= "  where {$sWhere}";
    $sSql  .= "    AND s111_i_exame = {$oLinhaRelatório->s108_i_codigo}";
    $sSql  .= "  group by  z01_nome order by z01_nome";
    $rsTotais = db_query($sSql);
    $aTotais  = db_utils::getColectionByRecord($rsTotais); 
    $pdf->setfont('arial','',8);
  	foreach ($aTotais as $oTotal) {
      
  	  $pdf->cell(160,$alt,$oTotal->z01_nome,'RBT',0,"L",0);
  	  $pdf->cell(30,$alt,$oTotal->total,'TB',1,"R",0);
  	  
     }
     $pdf->ln();
  }
}

    
  $pdf->ln();
// ----------------------------------------------------------------
//notasExplicativas(&$pdf, 63, $periodo,190); 
//  $pdf->Ln(5);
//  
//  // assinaturas
//  $pdf->setfont('arial','',5);
//  $pdf->ln(20);
//  
//  assinaturas(&$pdf,&$classinatura,'GF');
  
  $pdf->Output();

?>