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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
$clrotulo = new rotulocampo;
$clrotulo->label('rh61_regist');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$orderby = " z01_nome ";
$ordenacao = "Alfabética";
if($ordem == "n"){
  $ordenacao = "Numérico";
  $orderby = " rh01_regist ";
}

//// Variáveis do cabeçalho
//// $head1 = linha 1
//// $head2 = linha 2
///  ...
//// $head9 = linha 9

$head2 = "FUNCIONÁRIOS COM CONTA BANCARIA";
$head4  = "GERAL";
$head6 = "ORDEM: $ordenacao   PERIODO: $ano/$mes";

////////////////////////////////////////////////
$xordem = " order by $orderby";
$where = "";
if($tipo == 'l'){
  $head4  = "LOTAÇÕES";
  $xordem = " order by r70_estrut,$orderby";
  if(isset($lti) && trim($lti) != "" && isset($ltf) && trim($ltf) != ""){
    // Se for por intervalos e vier local inicial e final
    $where .= " and r70_estrut between '".$lti."' and '".$ltf."' ";
    $head4.= " DE ".$lti." A ".$ltf;
  }else if(isset($lti) && trim($lti) != ""){
    // Se for por intervalos e vier somente local inicial
    $where .= " and r70_estrut >= '".$lti."' ";
    $head4.= " SUPERIORES A ".$lti;
  }else if(isset($ltf) && trim($ltf) != ""){
    // Se for por intervalos e vier somente local final
    $where .= " and r70_estrut <= '".$ltf."' ";
    $head4.= " INFERIORES A ".$ltf;
  }else if(isset($flt) && trim($flt) != ""){
    // Se for por selecionados
    $where .= " and r70_estrut in ('".str_replace(",","','",$flt)."') ";
    $head4.= " SELECIONADOS";
  }

}elseif($tipo == 'o'){
  $head4 = "SECRETARIAS";
  $xordem = " order by o40_orgao,$orderby";
  if(isset($ori) && trim($ori) != "" && isset($orf) && trim($orf) != ""){
    // Se for por intervalos e vier órgão inicial e final
    $where .= " and o40_orgao between ".$ori." and ".$orf;
    $head4.= " DE ".$ori." A ".$orf;
  }else if(isset($ori) && trim($ori) != ""){
    // Se for por intervalos e vier somente órgão inicial
    $where .= " and o40_orgao >= ".$ori;
    $head4.= " SUPERIORES A ".$ori;
  }else if(isset($orf) && trim($orf) != ""){
    // Se for por intervalos e vier somente órgão final
    $where .= " and o40_orgao <= ".$orf;
    $head4.= " INFERIORES A ".$orf;
  }else if(isset($for) && trim($for) != ""){
    // Se for por selecionados
    $where .= " and o40_orgao in (".$for.") ";
    $head4.= " SELECIONADOS";
  }
}

if($banco != ""){
  $banco = "and rh44_codban = '$banco'";
}
$sql = "select rh01_regist,
               z01_nome,
               z01_cgccpf,
               db90_descr,
	             rh44_codban,
	             rh44_agencia,
	             rh44_dvagencia,
	             rh44_conta,
	             rh44_dvconta,
               o40_orgao,
               o40_descr,
               r70_estrut,
               r70_descr
from rhpessoalmov
     inner join rhpessoal on rh01_regist = rh02_regist
     inner join rhfuncao  on rh01_funcao = rh37_funcao
		                     and rh02_instit = rh37_instit
     left  join rhpesrescisao on rh05_seqpes = rh02_seqpes
     inner join cgm       on rh01_numcgm = z01_numcgm 
     inner join rhpesbanco on rh02_seqpes   = rh44_seqpes
     inner join db_bancos on db90_codban    = rh44_codban
     left  join rhlota    on rh02_lota   = r70_codigo
     left  join rhlotaexe on rh26_codigo = rh02_lota
                         and rh26_anousu = rh02_anousu
     left  join orcorgao  on o40_orgao   = rh26_orgao
                         and o40_anousu  = rh26_anousu
where rh02_anousu = $ano 
  and rh02_mesusu = $mes
	and rh02_instit = ".db_getsession("DB_instit")."
  $banco
  and rh05_recis is null
  $where 
  $xordem "; 
  
//die($sql);
//// pg_exec - executa $sql no banco e gera um RECORDSET criado na variável $resultado_sql com os dados da execução
//// da variável $sql no banco
$resultado_sql = pg_exec($sql);
//// pg_numrows - verifica quantas linhas vieram no RECORDSET e coloca o resultado na variávei $qtd_linhas_sql
$qtd_linhas_sql = pg_numrows($resultado_sql);
if($qtd_linhas_sql == 0){
  db_redireciona('db_erros.php?fec\har=true&db_erro=Não existem funcionários cadastrados no período.');
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$imprime_cabecalho = true;
$alt = 4;
$lotacao = 0;
$orgao = 0;
$total = 0;
for($x=0; $x<$qtd_linhas_sql; $x++){

  db_fieldsmemory($resultado_sql, $x);
  if ($pdf->gety() > $pdf->h - 30 || $imprime_cabecalho == true){
     $pdf->addpage("L");
     $pdf->setfont('arial','b',8);

     $pdf->cell(12,$alt,'MATRIC',1,0,"C",1);
     $pdf->cell(70,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
     $pdf->cell(15,$alt,'CODIGO',1,0,"C",1);
     $pdf->cell(60,$alt,'BANCO',1,0,"C",1);
     $pdf->cell(20,$alt,'AGENCIA',1,0,"C",1);
     $pdf->cell(05,$alt,'DV',1,0,"C",1);
     $pdf->cell(20,$alt,'CONTA',1,0,"C",1);
     $pdf->cell(05,$alt,'DV',1,0,"C",1);
     $pdf->cell(20,$alt,'CPF',1,1,"C",1);
     $pre = 1;

     $imprime_cabecalho = false;
  }
  if($tipo == 'o'){
     if($orgao != $o40_orgao){
       if($x != 0){
         $pdf->setfont('arial','',8);
         $pdf->cell(227,$alt,'TOTAL DE REGISTROS  : '.$total,"T",0,"L",0);
       }
       $total = 0;
       $pdf->setfont('arial','b',8);
       $pdf->ln(4);
       $pdf->cell(0,$alt,'SECRETÁRIA : '.$o40_orgao.' - '.$o40_descr,0,1,"L",0);
     
       $orgao = $o40_orgao;
     }

  }elseif($tipo == 'l'){
     if($lotacao != $r70_estrut){
       if($x != 0){
         $pdf->setfont('arial','',8);
         $pdf->cell(227,$alt,'TOTAL DE REGISTROS  : '.$total,"T",0,"L",0);
       }
       $total = 0;
       $pdf->setfont('arial','b',8);
       $pdf->ln(4);
       $pdf->cell(0,$alt,'LOTAÇÃO : '.$r70_estrut.' - '.$r70_descr,0,1,"L",0);
     
       $lotacao = $r70_estrut;
     }
  }
  if ($pre == 1)
    $pre = 0;
  else
    $pre = 1;
  $pdf->setfont('arial','',7);
  $pdf->cell(12,$alt,$rh01_regist,0,0,"C",$pre);
  $pdf->cell(70,$alt,$z01_nome,0,0,"L",$pre);
  $pdf->cell(15,$alt,$rh44_codban,0,0,"C",$pre);
  $pdf->cell(60,$alt,$db90_descr,0,0,"L",$pre);
  $pdf->cell(20,$alt,$rh44_agencia,0,0,"C",$pre);
  $pdf->cell(05,$alt,$rh44_dvagencia,0,0,"C",$pre);
  $pdf->cell(20,$alt,$rh44_conta,0,0,"C",$pre);
  $pdf->cell(05,$alt,$rh44_dvconta,0,0,"C",$pre);
  $pdf->cell(20,$alt,$z01_cgccpf,0,1,"C",$pre);
  $total ++;
}
$pdf->setfont('arial','',8);
$pdf->cell(227,$alt,'TOTAL DE REGISTROS  : '.$total,"T",1,"L",0);
$pdf->setfont('arial','b',8);
$pdf->cell(227,$alt,'TOTAL GERAL DE REGISTROS  : '.$qtd_linhas_sql,"T",1,"C",0);
$pdf->Output();
?>