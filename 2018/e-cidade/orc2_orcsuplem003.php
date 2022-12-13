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
include("libs/db_utils.php");
include("libs/db_app.utils.php");
include("classes/db_orcsuplem_classe.php");
include("libs/db_liborcamento.php");
db_app::import("orcamento.suplementacao.*");
$auxiliar = new cl_orcsuplem;
$anousu = db_getsession("DB_anousu");
$instit = db_getsession("DB_instit");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

// str_replace('-',', ',$db_selinstit);

$imprimecabec = false;
$imprimiualgo = false;
     $abrerelatorio = true;

for ($tiporel = 0; $tiporel <= 1; $tiporel++) {

 $wheretipo = ($tiporel == 0?"( o39_usalimite is true or o139_orcprojeto is not null)":" (o39_usalimite is false and o139_orcprojeto is null)") . " and ";

  if(isset($processados) && ($processados=='1')){
    
    $sql=" select distinct 
    o39_codproj,
    o39_data,
    o39_numero,
    o39_descr,
    o49_data as data_proc,
    o45_numlei,
    o45_dataini
    from orcprojeto
    inner join orclei on o45_codlei = o39_codlei
    left join orcprojlan on o51_codproj = o39_codproj
    left  join orcprojetoorcprojetolei on o39_codproj = o139_orcprojeto
    inner join orcsuplem on o46_codlei = o39_codproj
    inner join orcsuplemlan on o49_codsup = o46_codsup
    where $wheretipo
    o49_data between '$dt_ini' and '$dt_fim'
    
    ";
    if (isset($codlei) && ($codlei!="")) 
    $sql.=" and o45_codlei = $codlei   ";
    
    $sql .=" order by data_proc ";
    
  }elseif(isset($processados) && ($processados=='3')){
    $sql = "select distinct o39_codproj,
    o39_data,
    o39_numero,
    o39_descr,
    o51_data as data_proc,
    o45_numlei,
    o45_dataini
    from orcprojeto
    inner join orclei on o45_codlei = o39_codlei
    left join orcprojlan on o51_codproj = o39_codproj
    left  join orcprojetoorcprojetolei on o39_codproj = o139_orcprojeto
    where $wheretipo o51_data between '$dt_ini' and '$dt_fim'
    
    ";
    if (isset($codlei) && ($codlei!="")) 
    $sql.=" and o45_codlei = $codlei   ";
    $sql .=" order by data_proc ";
  }else{
    $sql = "select distinct o39_codproj,
    o39_data,
    o39_numero,
    o39_descr,
    o51_data as data_proc,
    o45_numlei,
    o45_dataini
    from orcprojeto
    left  join orcprojetoorcprojetolei on o39_codproj = o139_orcprojeto
    inner join orclei on o45_codlei = o39_codlei
    left join orcprojlan on o51_codproj = o39_codproj            
    where $wheretipo o51_codproj is null and o51_data between '$dt_ini' and '$dt_fim'
    
    ";
    if (isset($codlei) && ($codlei!="")) 
    $sql.=" and o45_codlei = $codlei   ";
    
    $sql .=" order by data_proc ";
  }
  $res = $auxiliar->sql_record($sql); 
  if ($auxiliar->numrows ==0){
    continue;
  }
  //////////////////////////////////

  //////////////////////////////////
  $head4 = "Relatorio de Projetos";
  $perini= split("-",$dt_ini);
  $perfim= split("-",$dt_fim);
  $head5 = "PERIODO : $perini[2]/$perini[1]/$perini[0]  à  $perfim[2]/$perfim[1]$perfim[0]";

  $xinstit = split("-",$db_selinstit);
  $resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
  $descr_inst = '';
  $xvirg = '';
  $consolidado = false;
  for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    if ($xvirg==','){
      $consolidado = true;
    }  
    $descr_inst .= $xvirg.$nomeinst ;
    $xvirg = ',';
  }
  if ($abrerelatorio == true) {
     $abrerelatorio = false;
    $imprimiualgo = true;
    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->SetFillColor(235);
    $pdf->SetFont('Arial','',9);
    $pdf->setY(40);
    $pagina=1;
  }

  $codigo="";

  $tg_total_suplem    = 0;
  $tg_total_reduz     = 0;
  $tg_total_arrecad   = 0;
  $tg_total_superavit = 0;

  for ($x=0; $x< $auxiliar->numrows ; $x++) { // loop nos projetos
    db_fieldsmemory($res,$x);
    
    if ($pdf->gety() > $pdf->h - 30 or $pagina == 1 or $imprimecabec == true){
      $pagina=0;
      if ($imprimecabec == false) {
        $pdf->addpage("L");
      }
      $imprimecabec = false;

      $pdf->Setfillcolor(235);
      if ($tiporel == 0) {
        $pdf->Cell(285,4,"CRÉDITOS ADICIONAIS",'1',1,"L",'1');
      } else {
        $pdf->Cell(285,4,"REMANEJAMENTOS AUTORIZADOS PELO LOA",'1',1,"L",'1');
      }
      $pdf->Setfillcolor(0);

      $pdf->setfont('arial','',9);
      $pdf->Ln();
      $pdf->setX(10);
      $pdf->Cell(10,4,"PROJ",'1',0,"L",'0');    
      $pdf->Cell(20,4,"EMISSÃO",'1',0,"L",'0');     
      $pdf->Cell(20,4,"LANÇADO",'1',0,"L",'0');     
      $pdf->Cell(40,4,"DECRETO",'1',0,"L",'0');  
      $pdf->Cell(15,4,"LEI",'1',0,"L",'0');  
      $pdf->Cell(20,4,"DT LEI",'1',0,"L",'0');  
      $pdf->Cell(60,4,"DECRIÇÂO",'1',0,"L",'0');  
      $pdf->Cell(25,4,"SUPL",'1',0,"L",'0');  
      $pdf->Cell(25,4,"REDUZ",'1',0,"L",'0');  
      $pdf->Cell(25,4,"ARRECAD",'1',0,"L",'0');  
      $pdf->Cell(25,4,"SUPERAVIT",'1',1,"L",'0');  
      $pdf->Ln();
    }
    
    $pdf->setX(10);
    $pdf->Cell(10,4,"$o39_codproj",'B',0,"R",'0');    
    $pdf->Cell(20,4,db_formatar($o39_data, "d"),'B',0,"C",'0');    
    $pdf->Cell(20,4,db_formatar($data_proc, "d"),'B',0,"C",'0');    
    $pdf->Cell(40,4,$o39_numero . " / " .db_formatar($o39_data, "d"),'B',0,"L",'0');
    $pdf->Cell(15,4,substr($o45_numlei,0,8),'B',0,"L",'0');
    $pdf->Cell(20,4,db_formatar($o45_dataini, "d"),'B',0,"L",'0');
    $pdf->Cell(60,4,substr($o39_descr,0,30),'B',0,"L",'0');
    
    /////// ----- 
    $total_suplem       = 0;  
    $total_reduz        = 0;  
    $total_arrecad      = 0;
    $total_superavit    = 0; 
    $sSqlSuplementacoes = "select o46_codsup  
                             from orcsuplem
                                   inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup and o48_coddocsup > 0
                                   inner join orcsuplemlan on o46_codsup = o49_codsup
                             where o49_data = '$data_proc' 
                               and orcsuplem.o46_codlei = $o39_codproj";  
    $rsSuplementacoes     = db_query($sSqlSuplementacoes);
    $aSuplementacao       = db_utils::getColectionByRecord($rsSuplementacoes);
    $valorutilizado       = 0;
    foreach ($aSuplementacao as $oSuplem) {
        
      $oSuplementacao = new Suplementacao($oSuplem->o46_codsup);
      $total_suplem  += $oSuplementacao->getvalorSuplementacao();  
      $total_reduz   += $oSuplementacao->getValorReducao();  
      $total_arrecad += $oSuplementacao->getValorReceita();  
    }
    unset($oSuplementacao);
    $sql = "select sum(o47_valor) as total_superavit 
    from orcsuplemval
    inner join orcdotacao on o58_coddot = o47_coddot and o58_anousu=o47_anousu and o58_instit in (". str_replace('-',', ',$db_selinstit).") 
    
    inner join orcsuplem on o46_codsup =o47_codsup and
    orcsuplem.o46_codlei = $o39_codproj
    inner join orcsuplemtipo on o48_tiposup = orcsuplem.o46_tiposup and     
    o48_superavit ='t'
    inner join orcsuplemlan on o46_codsup = o49_codsup
    where  o49_data = '$data_proc' and 
    o47_valor > 0   
    ";  
    $result = pg_exec($sql);
    if (pg_numrows($result) > 0 ){
      db_fieldsmemory($result,0,true);
    } 
    ///////
    
    $pdf->Cell(25,4,db_formatar($total_suplem, "f"),'B',0,"R",'0');  
    $pdf->Cell(25,4,db_formatar($total_reduz, "f"),'B',0,"R",'0');  
    $pdf->Cell(25,4,db_formatar($total_arrecad, "f"),'B',0,"R",'0');  
    $pdf->Cell(25,4,db_formatar($total_superavit, "f"),'B',1,"R",'0');  
    
    // totalizadores finais
    $tg_total_suplem    += $total_suplem;
    $tg_total_reduz     += $total_reduz;
    $tg_total_arrecad   += $total_arrecad;
    $tg_total_superavit += $total_superavit;
    
  }

  $pdf->setX(155);
  $pdf->Cell(40,4,"TOTAL",'B',0,"R",'0');  
  $pdf->Cell(25,4,db_formatar($tg_total_suplem,'f'),'B',0,"R",'0');  
  $pdf->Cell(25,4,db_formatar($tg_total_reduz,'f')   ,'B',0,"R",'0');  
  $pdf->Cell(25,4,db_formatar($tg_total_arrecad,'f') ,'B',0,"R",'0');  
  $pdf->Cell(25,4,db_formatar($tg_total_superavit,'f') ,'B',1,"R",'0');  

  $pdf->ln(3);

  $imprimecabec = true;
  
}

if ($imprimiualgo == false) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado ');       
} else {
  $pdf->Output();
}

?>