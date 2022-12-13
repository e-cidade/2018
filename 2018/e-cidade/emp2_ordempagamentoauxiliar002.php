<?
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

require(modification("fpdf151/pdf.php"));
include(modification("classes/db_empageordem_classe.php"));
include(modification("classes/db_empagenotasordem_classe.php"));
include(modification("libs/db_utils.php"));

$oGet  = db_utils::postMemory($_GET);
if ($oGet->iAgenda == "" && $oGet->dtAutorizacao == "") {
  db_redireciona('db_erros.php?fechar=true&db_erro=Filtros não Informados');
}
$oDaoOrdemAuxiliar = new cl_empageordem();

$sWhere = "";
$iInstituicao = db_getsession("DB_instit");
if ($oGet->iAgenda != "") {
  $sWhere .= " e42_sequencial = {$oGet->iAgenda}";
}
if (isset($oGet->dtAutorizacao) && $oGet->dtAutorizacao != "") {
  
  $dtAutorizacao = implode("-", array_reverse(explode("/",$oGet->dtAutorizacao)));
  if ($sWhere != "") {
    $sWhere .= " and ";
  }
  $sWhere  .= " e42_dtpagamento = '{$dtAutorizacao}'";
}

$sSqlDadosOrdem    = $oDaoOrdemAuxiliar->sql_query(null,"*","e42_dtpagamento", $sWhere);
$rsDadosOrdem      = $oDaoOrdemAuxiliar->sql_record($sSqlDadosOrdem);
$oDaoOrdem         = new cl_empagenotasordem;
if ($oDaoOrdemAuxiliar->numrows == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum pagamento autorizado para a Agenda {$oGet->iAgenda}");
}
$oPdf  = new PDF("L","mm","A4"); 
$oPdf->Open();
$oPdf->SetAutoPageBreak(0,1);


$head2     = "ORDEM AUXILIAR DE PAGAMENTO";
if ($oDaoOrdemAuxiliar->numrows > 0) {
   
  for ($i = 0; $i < $oDaoOrdemAuxiliar->numrows; $i++) {

    $lNovo           = true;   
    $oOrdemAuxiliar  = db_utils::fieldsMemory($rsDadosOrdem, $i);
    
    $j         = 0;
    $head3     = "Autorização nº:  ".$oOrdemAuxiliar->e42_sequencial;
    $head4     = "Pagamentos autorizados para:  ".db_formatar($oOrdemAuxiliar->e42_dtpagamento, 'd');
    $sWhere    = "e43_ordempagamento = {$oOrdemAuxiliar->e42_sequencial}";
    $sWhere   .= " and (slip.k17_instit = {$iInstituicao} or e60_instit = {$iInstituicao})";
    $sSqlOrdem = $oDaoOrdem->sql_query_empenho(null,
                                       "e60_codemp||'/'||e60_anousu as e60_codemp,
                                       e69_numero,
                                       e83_conta,
                                       e83_descr,
                                       e50_codord,
                                       e96_descr,
                                       case when e49_numcgm is null then cgmemp.z01_nome else cgmordem.z01_nome end as z01_nome,
                                       case when e49_numcgm is null then cgmemp.z01_cgccpf else cgmordem.z01_cgccpf end as z01_cgccpf,
                                       e50_data,
                                       fc_valorretencaomov(e81_codmov,false) as vlrretencao,
                                       e43_valor,
                                       e53_valor,
                                       k17_valor,
                                       slip.k17_codigo,
                                       cgmslip.z01_nome as nomeslip,
                                       slipnum.k17_numcgm,
                                       cgmslip.z01_cgccpf as cgccpfslip,
                                       k17_data",
                                       "e50_codord,
                                       slip.k17_codigo",
                                       $sWhere
                                      );
    $rsOrdem = $oDaoOrdem->sql_record($sSqlOrdem);
    $sFonte  = "arial"; 
    if ($oDaoOrdem->numrows > 0) {
  
      $nValorAutorizado = 0;
      $nValorLiquido    = 0;
      $nValorBruto      = 0;
      $nValorRetido     = 0;
      $aMovimentos      = db_utils::getCollectionByRecord($rsOrdem);
      foreach($aMovimentos as $oMovimento) {
    
        if ($oPdf->Gety() > $oPdf->h - 25 || $lNovo) {
          
          $oPdf->AddPage();
          $oPdf->SetFont($sFonte, "B",8);
          $oPdf->SetFillColor("245");
          $oPdf->cell(15,5,"OP/Slip","TBR",0,"C");
          $oPdf->cell(20,5,"Empenho","TBR",0,"C");
          $oPdf->cell(20,5,"Nota Fiscal","TBR",0,"C");
          $oPdf->cell(35,5,"Cta. Pag","TBR",0,"C");
          $oPdf->cell(23,5,"CNPJ/CPF","TBR",0,"C");
          $oPdf->cell(55,5,"Nome do Credor","TBR",0,"C");
          $oPdf->cell(23,5,"Emissão da OP","TBR",0,"C");
          $oPdf->cell(15,5,"Forma","TBR",0,"C");
          $oPdf->cell(18,5,"Valor da OP	","TBR",0,"C");
          $oPdf->cell(18,5,"Retencao","TBR",0,"C");
          $oPdf->cell(18,5,"Vlr Liquido","TBR",0,"C");
          $oPdf->cell(18,5,"Vlr Aut.","TBL",1,"C");
          $oPdf->SetFont($sFonte, "",6);
          $lNovo = false;
          $j = 0;
          
        }
        if ($j % 2 == 0) {
          $iPreencher = 0; 
        } else {
          $iPreencher = 1;
        }
        $sCpfCgc = "";
        if (strlen($oMovimento->z01_cgccpf) == 14) {
          $sCpfCgc = db_formatar($oMovimento->z01_cgccpf,"cnpj");
        } else if (strlen($oMovimento->z01_cgccpf) == 11) {
          $sCpfCgc = db_formatar($oMovimento->z01_cgccpf,"cpf");
        }
        if ($oMovimento->e60_codemp == "") {

          $oMovimento->e53_valor  = $oMovimento->k17_valor;            
          $oMovimento->e50_codord = $oMovimento->k17_codigo;
          $oMovimento->z01_nome   = $oMovimento->nomeslip;
          $oMovimento->e60_codemp = "slip";
          $oMovimento->e50_data   = $oMovimento->k17_data;
                      
        }
        $oPdf->cell(15, 4,$oMovimento->e50_codord  , 0,0,"R", $iPreencher);
        $oPdf->cell(20, 4,$oMovimento->e60_codemp, 0, 0, "C", $iPreencher);
        $oPdf->cell(20, 4,$oMovimento->e69_numero, 0,0,"C",$iPreencher);
        $oPdf->cell(35, 4,substr($oMovimento->e83_conta."-".$oMovimento->e83_descr,0,25), 0,0,"L",$iPreencher);
        $oPdf->cell(23, 4,$sCpfCgc , 0,0,"L",$iPreencher);
        $oPdf->cell(55, 4,substr($oMovimento->z01_nome,0,35)  , 0,0,"L",$iPreencher);
        $oPdf->cell(23, 4,db_formatar($oMovimento->e50_data,"d")   , 0, 0, "C", $iPreencher);
        $oPdf->cell(15, 4,$oMovimento->e96_descr , 0, 0, "C", $iPreencher);
        $oPdf->cell(18, 4,db_formatar($oMovimento->e53_valor,"f"), 0,0,"R",$iPreencher);
        $oPdf->cell(18, 4,db_formatar($oMovimento->vlrretencao,"f"),0,0,"R",$iPreencher);
        $oPdf->cell(18, 4,db_formatar($oMovimento->e43_valor-$oMovimento->vlrretencao,"f"),0,0,"R",$iPreencher);
        $oPdf->cell(18, 4,db_formatar($oMovimento->e43_valor,"f"), 0,1,"R",$iPreencher);
        $nValorAutorizado += $oMovimento->e43_valor;
        $nValorLiquido    += $oMovimento->e43_valor - $oMovimento->vlrretencao;
        $nValorBruto      += $oMovimento->e53_valor;
        $nValorRetido     += $oMovimento->vlrretencao;
        $j++;        
      }
      $oPdf->SetFont($sFonte, "B",8);
      $oPdf->cell(55,4, "Total de Registros: {$oDaoOrdem->numrows}","TBR",0,"L");  
      $oPdf->cell(151, 4,"Totais "  , "TBR",0,"R");
      $oPdf->cell(18 , 4, db_formatar($nValorBruto,"f") , "TBL",0,"R");
      $oPdf->cell(18 , 4, db_formatar($nValorRetido,"f") , "TBL",0,"R");
      $oPdf->cell(18 , 4, db_formatar($nValorLiquido,"f") , "TBL",0,"R");
      $oPdf->cell(18 , 4, db_formatar($nValorAutorizado,"f") , "TBL",0,"R");
    }
  }
}
$oPdf->Output();
?>