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
require_once(modification("libs/db_utils.php"));

$oGet = db_utils::postMemory($_GET);

$aWhere = array();
$aInfo  = array();

if ($oGet->baix == "b") {

	$aWhere[] = "q02_dtbaix is not null";
	$aInfo[]  = "Somente Inscrições Baixadas";

} else if ($oGet->baix == "c"){

	$aWhere[] = "q02_dtbaix is null";
	$aInfo[]  = "Somente Inscrições não Baixadas";
}

if ( trim($oGet->sListaRuas) != "" ){

  if ( $oGet->ver == "com" ){
    $aWhere[] = "issruas.j14_codigo in  ({$oGet->sListaRuas})";
  } else {
    $aWhere[] = "issruas.j14_codigo not in ({$oGet->sListaRuas})";
  }
}

if ( $oGet->tipo == 't' ||  $oGet->tipo == 'f' ){

	$aWhere[] = "q07_perman = '{$oGet->tipo}'";

  if ( $oGet->tipo == 't' ) {
  	$aInfo[]  = "Somente Atividades Permanente";
  } else {
	  $aInfo[]  = "Somente Atividades Provisórias";
  }
}

if ( $oGet->tipoAtividade == 'a') {

	$aWhere[] = "q07_databx is null";
	$aInfo[]  = "Somente Atividades Ativas";

} else if ( $oGet->tipoAtividade == 'b') {

	$aWhere[] = "q07_databx is not null";
	$aInfo[]  = "Somente Atividades Baixadas";
}

if ( count($aWhere) > 0 ) {
  $sWhere = " where ".implode(" and ",$aWhere);
} else {
	$sWhere = "";
}

$head2 = "RELATÓRIO DE INSCRIÇÕES POR LOGRADOURO";

foreach ($aInfo as $iInd => $sDescricao ) {
	${"head".($iInd+4)} = $sDescricao;
}

$sSqlInscrLograd  = "   select issruas.j14_codigo,                                              ";
$sSqlInscrLograd .= "          ruas.j14_nome,                                                   ";
$sSqlInscrLograd .= "          issbase.q02_inscr,                                               ";
$sSqlInscrLograd .= "          q02_numcgm,                                                      ";
$sSqlInscrLograd .= "          z01_nome,                                                        ";
$sSqlInscrLograd .= "          q03_descr,                                                       ";
$sSqlInscrLograd .= "          q07_datain,                                                      ";
$sSqlInscrLograd .= "          q07_databx,                                                      ";
$sSqlInscrLograd .= "          q02_numero,                                                      ";
$sSqlInscrLograd .= "          q02_compl,                                                       ";
$sSqlInscrLograd .= "          q02_dtbaix,                                                      ";
$sSqlInscrLograd .= "          q07_perman                                                       ";
$sSqlInscrLograd .= "     from issbase                                                          ";
$sSqlInscrLograd .= " 	       inner join tabativ  on  tabativ.q07_inscr  =  issbase.q02_inscr  ";
$sSqlInscrLograd .= "          inner join ativid   on  q07_ativ           =  q03_ativ           ";
$sSqlInscrLograd .= "   			 inner join cgm      on  q02_numcgm         =  z01_numcgm         ";
$sSqlInscrLograd .= "    			 inner join issruas  on  issruas.q02_inscr  =  issbase.q02_inscr  ";
$sSqlInscrLograd .= " 	  		 inner join ruas     on  ruas.j14_codigo    =  issruas.j14_codigo ";
$sSqlInscrLograd .= "   		   {$sWhere}                                                        ";
$sSqlInscrLograd .= " order by issruas.j14_codigo,                                              ";
$sSqlInscrLograd .= " 	       q02_numero,                                                      ";
$sSqlInscrLograd .= " 	       z01_nome                                                         ";

$result = db_query($sSqlInscrLograd);

if (pg_numrows($result) == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',8);

$alt   = 4;
$total = 0;
$p     = 1;
$rua   = "X";
$inscr = "X";
$total = 0;

   for($x = 0; $x <pg_numrows($result);$x++){

   	 db_fieldsmemory($result,$x);

	   if ($oPdf->gety() > $oPdf->h - 30 || $total == 0){

	     $oPdf->addpage("L");

		   if($rua == "X"){
		     $oPdf->cell(20,$alt,"Logradouro","TLB",0,"C",1);
		     $oPdf->cell(260,$alt,$j14_codigo." ".$j14_nome,"TBR",1,"L",1);
		     $rua = $j14_codigo;
		   }
	      //cabeçalho
	     $oPdf->setfont('arial','b',8);
	     $oPdf->cell(15,$alt,"Inscrição"    ,1,0,"C",1);
	     $oPdf->cell(13,$alt,"CGM"          ,1,0,"C",1);
	     $oPdf->cell(72,$alt,"Nome"         ,1,0,"C",1);
	     $oPdf->cell(80,$alt,"Atividade"    ,1,0,"C",1);
	     $oPdf->cell(10,$alt,"Tipo"         ,1,0,"C",1);
	     $oPdf->cell(20,$alt,"Inicio"       ,1,0,"C",1);
	     $oPdf->cell(20,$alt,"Final"        ,1,0,"C",1);
	     $oPdf->cell(10,$alt,"N°"           ,1,0,"C",1);

	     if ( $oGet->baix == "c" ) {
 	       $oPdf->cell(40,$alt,"Compl."     ,1,1,"C",1);
	     } else {
	       $oPdf->cell(20,$alt,"Compl."     ,1,0,"C",1);
	       $oPdf->cell(20,$alt,"Data Baixa" ,1,1,"C",1);
	     }

	     $oPdf->setfont('arial','',8);
	   }

	   if($rua != $j14_codigo){

	     $oPdf->cell(20,$alt,"Logradouro","TLB",0,"C",1);
	     $oPdf->cell(260,$alt,$j14_codigo." ".$j14_nome,"TBR",1,"L",1);
	   }

     if ($inscr != $q02_inscr){

	     $total++;

	   	 if($q07_perman =='t'){
	   		 $tp = "Perm.";
	   	 } else if ($q07_perman =='f'){
	   		 $tp = "Prov.";
	   	 }

	    //mostra os registros da inscrição
	     $oPdf->setfont('arial','',8);
	     $oPdf->cell(15,$alt,$q02_inscr,0,0,"C",$p);
	     $oPdf->cell(13,$alt,$q02_numcgm,0,0,"C",$p);
	     $oPdf->cell(72,$alt,trim($z01_nome),0,0,"L",$p);
       $oPdf->cell(80,$alt,substr(trim($q03_descr), 0, 43),0,0,"L",$p);
	     $oPdf->cell(10,$alt,$tp,0,0,"L",$p);
	     $oPdf->cell(20,$alt,db_formatar($q07_datain,'d'),0,0,"C",$p);
	     $oPdf->cell(20,$alt,db_formatar($q07_databx,'d'),0,0,"C",$p);
	     $oPdf->cell(10,$alt,$q02_numero,0,0,"L",$p);

       if ( $oGet->baix == "c" ) {
         $oPdf->cell(40,$alt,trim($q02_compl),0,1,"L",$p);
       } else {

         $oPdf->cell(20,$alt,trim($q02_compl),0,0,"L",$p);
         $oPdf->cell(20,$alt,db_formatar($q02_dtbaix,'d'),0,1,"L",$p);
       }

       $inscr = $q02_inscr;

     } else {

		   // mostra as atividades
		   $oPdf->cell(15,$alt,"",0,0,"C",$p);
		   $oPdf->cell(13,$alt,"",0,0,"C",$p);
		   $oPdf->cell(72,$alt,"",0,0,"L",$p);
		   $oPdf->cell(80,$alt,substr(trim($q03_descr), 0, 43),0,0,"L",$p);
		   $oPdf->cell(10,$alt,$tp,0,0,"L",$p);
       $oPdf->cell(20,$alt,db_formatar($q07_datain,'d'),0,0,"C",$p);
       $oPdf->cell(20,$alt,db_formatar($q07_databx,'d'),0,0,"C",$p);
		   $oPdf->cell(10,$alt,"",0,0,"L",$p);

       if ( $oGet->baix == "c" ) {
         $oPdf->cell(40,$alt,trim($q02_compl),0,1,"L",$p);
       } else {
         $oPdf->cell(20,$alt,trim($q02_compl),0,0,"L",$p);
         $oPdf->cell(20,$alt,db_formatar($q02_dtbaix,'d'),0,1,"L",$p);
       }

		 }

	   if( $p == 1 ){
	     $p = 0;
	   } else {
	     $p = 1;
	   }

 	   // variaveis acumuladoras
	   $inscr = $q02_inscr;
	   $rua   = $j14_codigo;
  }

//mostra o total
$oPdf->setfont('arial','b',8);
$oPdf->cell(280,$alt,'TOTAL DE ALVARÁS  :  '.$total,"T",0,"L",0);

$oPdf->Output();