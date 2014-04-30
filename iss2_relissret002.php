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
    include("dbforms/db_funcoes.php");
    include("dbforms/db_classesgenericas.php");

if( isset($HTTP_GET_VARS["ano"]) && isset( $HTTP_GET_VARS["mes"])){
     
    $aTipos = array('1' => 'No financeiro',
                    '2' => 'Anulado',
                    '3' => 'Cancelado',
                    '4' => 'Já Pago',
                    '5' => 'Suspenso',
                    '6' => 'Em Digitação',  
                    );
    db_postmemory($HTTP_POST_VARS);
    db_postmemory($HTTP_GET_VARS);
    $sOrder  = '';
    switch ($ordem) {
      
      case '1':
        
        $sOrder = " order by issqnret.q20_mes, issqnret.q20_ano,z01_nome";
        break;
        
      case '2':
        
        $sOrder = " order by z01_nome, issqnret.q20_mes, issqnret.q20_ano";
        break;   

     case '3':
        
        $sOrder = " order by issqnret.q20_planilha";
        break;    
    }
    $sWhere  = '';
    $aSituacoes = explode(',', $situacoes);
//    foreach ($aSituacoes as $iSituacao) {
//
//      switch ($iSituacao) {
//        
//        case '1':
//          if ($sWhere != "") {
//            $sWhere .= " or ";
//          }
//          $sWhere .= " arrecad.k00_numpre is not null ";
//          break;
//          
//        case '2':
//          if ($sWhere != "") {
//            $sWhere .= " or ";
//          }
//          $sWhere .= " q20_situacao = 5 ";
//          break;  
//          
//        case '3':
//          if ($sWhere != "") {
//            $sWhere .= " or ";
//          }
//          $sWhere .= " k24_cancdebitosreg is not null ";
//          break;  
//          
//        case '4':
//          if ($sWhere != "") {
//            $sWhere .= " or ";
//          }
//          $sWhere .= " (arrecant.k00_numpre is not null  and arresusp.k00_numpre is null and q20_situacao <> 5)";
//          break;
//             
//        case '5':
//          if ($sWhere != "") {
//            $sWhere .= " or ";
//          }
//          $sWhere .= " (arresusp.k00_numpre is not null)";
//          break;   
//          
//        case '6':
//          if ($sWhere != "") {
//            $sWhere .= " or ";
//          }
//          $sWhere .= " (arrecad.k00_numpre is  null and arrecant.k00_numpre is null and arresusp.k00_numpre is null ";
//          $sWhere .= " and k24_cancdebitosreg is null and q20_situacao <> 5)";
//          break;  
//      }
//    }
    $str_sql = "select issqnret.*,
                  case when arrecad.k00_numpre is not null then
                     '1'
                  else
                        case when q20_situacao = 5 then 
                          '2' 
                         when k24_cancdebitosreg is not null then 
                          '3' 
                         when arrecant.k00_numpre is not null then
                             '4'
                        when arresusp.k00_numpre is not null then 
                             '5'
                        else
                            '6'
                        end
                  end as situacao,
                  q20_ano,
                  q20_mes,
                  arrecad.k00_dtvenc
                  from (
                        select q20_ano,q20_mes,
                               z01_numcgm, 
                               z01_nome,q24_inscr,
                               q20_planilha,
                               q20_numpre, 
                               q20_situacao,
                               sum( q21_valor ) as q21_valor
                          from issplan
                         inner join issplanit on q21_planilha = q20_planilha
						 left join issplaninscr on q24_planilha= q20_planilha 
                         inner join cgm on z01_numcgm = q20_numcgm
                         where q20_ano = $ano and q21_status = 1";
    if( $mes != "todos"){
        $str_sql .= " and q20_mes = $mes ";
    }

    $str_sql .= "      group by z01_numcgm, z01_nome,q24_inscr, q20_planilha, q20_numpre,
                                q20_ano,
                                q20_mes,
                                q20_situacao
                      ) as issqnret
                  left join arrecad  on arrecad.k00_numpre = q20_numpre
                  left join arrecant on arrecant.k00_numpre = q20_numpre
                  left join arresusp on arresusp.k00_numpre     = q20_numpre
                  left join cancdebitosreg on k21_numpre        = q20_numpre
                  left join cancdebitosprocreg on k21_sequencia = k24_cancdebitosreg";
     if ($sWhere != "")  {
       $str_sql .= " where {$sWhere}";
     }
     $str_sql .=  "{$sOrder} ";
    //die($str_sql);
    $result = pg_exec($str_sql) or die("FALHA: <br>$str_sql" );
    if(pg_num_rows($result)==0){
      
      $sVirgula   = "";
      $sSituacoes = "";
      foreach ($aSituacoes  as $iSituacao) {
        
        $sSituacoes .= $sVirgula.$aTipos[$iSituacao];
        $sVirgula = ", "; 
      }
      $sErro = "Não foram  encontrada(s) situação(ões) <b>{$sSituacoes}</b> para o relatório. em ({$ano}/{$mes})";
      db_redireciona("db_erros.php?fechar=true&db_erro={$sErro}");
    }
    $head2 = "Lançamentos efetuados no DBPREF";
    $head3 = "Issqn Retido na Fontes";
    $head5 = "Competência: $ano/$mes";
    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(220);
    $pdf->SetFont('Arial','B',7);

    $pag = 1;
    $iTotalLinhas = 0;
    $aTotalizadores = array();
    for ($x = 0 ; $x < pg_numrows($result);$x++){
        db_fieldsmemory($result,$x);
        if (($pdf->gety() > $pdf->h - 30) || $pag == 1 ){
            $pdf->addpage();
            $pdf->SetFont('Arial','B',7);
            $pag = 0;
            $pdf->Cell(1,10,'',0,1,"",0);
            $pdf->Cell(15,5,'CGM', "TBR",0,"C",1);
            $pdf->Cell(70,5,'Nome', "TBRL",0,"C",1);
            $pdf->cell(15,5,'Inscrição',"TBRL",0,"C",1);
            $pdf->cell(15,5,'Planilha',"TBRL",0,"C",1);
            $pdf->cell(15,5,'Mês/Ano', "TBRL",0,"C",1);
            $pdf->cell(20,5,'Valor', "TBRL",0,"C",1);
            $pdf->cell(18,5,'Situação',"TBRL",0,"C",1);
            $pdf->cell(18,5,'Dt. Venc.',"TBL",1,"C",1);
            
        }
        if (!in_array($situacao, $aSituacoes)) {
          continue;
        }
        
        $pdf->SetFont('Arial','',7);
        $pdf->Cell(15,5,$z01_numcgm, "TBR",0,"R",0);
        $pdf->Cell(70,5,$z01_nome, "TBRL",0,"L",0);
        $pdf->Cell(15,5,$q24_inscr, "TBRL",0,"R",0);
        $pdf->Cell(15,5,$q20_planilha, "TBRL",0,"R",0);
        $pdf->Cell(15,5,str_pad($q20_mes, 2,"0",STR_PAD_LEFT)."/{$q20_ano}", "TBRL",0,"C",0);
        $pdf->cell(20,5,db_formatar($q21_valor,'f'), "TBRL",0,"R",0);
        $pdf->Cell(18,5,$aTipos[$situacao], "TBR",0,"L",0);
        $pdf->Cell(18,5, db_formatar($k00_dtvenc,'d'), "TBL",1,"L",0);
        if (isset($aTotalizadores[$situacao])) {
          
          $aTotalizadores[$situacao]->iQuantidade++;
          $aTotalizadores[$situacao]->nValor     += $q21_valor;
          
        } else {
          
          $aTotalizadores[$situacao]->iQuantidade = 1;
          $aTotalizadores[$situacao]->nValor      = $q21_valor;
        }
        $iTotalLinhas ++;
    }
    if ($iTotalLinhas == 0) {
      
      $sVirgula   = "";
      $sSituacoes = "";
      foreach ($aSituacoes  as $iSituacao) {
        
        $sSituacoes .= $sVirgula.$aTipos[$iSituacao];
        $sVirgula = ", "; 
      }
      $sErro = "Não foram  encontrada(s) situação(ões) <b>{$sSituacoes}</b> para o relatório. em ({$ano}/{$mes})";
      db_redireciona("db_erros.php?fechar=true&db_erro={$sErro}");
    }
    $pdf->ln();
    if ($pdf->gety() > $pdf->h - 70) {
      $pdf->AddPage();
    }
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(100,5,'Totalizadores',1,1,"C", 1);
    $pdf->Cell(40,5,'Tipo',1,0,"C",1);
    $pdf->Cell(30,5,'Quantidade',1,0,"C",1);
    $pdf->cell(30,5,'Valor',1,1,"R",1);
    $iQuantidadeGeral = 0;
    $nValorGeral      = 0;
    $pdf->SetFont('Arial','',7);
    foreach ($aTipos as $iTipo => $sDescricao) {
      
      $pdf->Cell(40,5, $sDescricao,1,0,"L");
      $pdf->Cell(30,5, @$aTotalizadores[$iTipo]->iQuantidade,1,0,"R",0);
      $pdf->cell(30,5, db_formatar(@$aTotalizadores[$iTipo]->nValor ,"f"),1,1,"R",0);
      
      $nValorGeral      += @$aTotalizadores[$iTipo]->nValor;
      $iQuantidadeGeral += @$aTotalizadores[$iTipo]->iQuantidade;
    }
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(40,5, 'Total Geral',1,0,"C", 1);
    $pdf->Cell(30,5, $iQuantidadeGeral,1,0,"R",1);
    $pdf->cell(30,5, db_formatar($nValorGeral, 'f'),1,1,"R",1);
    $pdf->Output();

}
?>