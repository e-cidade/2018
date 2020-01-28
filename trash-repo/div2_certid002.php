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
include("classes/db_certid_classe.php");
include("classes/db_certdiv_classe.php");
include("classes/db_certter_classe.php");

$clcertid = new cl_certid;
$clcertdiv = new cl_certdiv;
$clcertter = new cl_certter;

$clrotulo = new rotulocampo;
$clrotulo->label('v13_certid');
$clrotulo->label('v13-dtemis');
$clrotulo->label('v14_certid');
$clrotulo->label('v07_parcel');
$clrotulo->label('v07_dtlanc');
$clrotulo->label('v07_totpar');
$clrotulo->label('z01_nome');
$clrotulo->label('v01_coddiv');
$clrotulo->label('v01_numpre');
$clrotulo->label('v03_descr');
$clrotulo->label('z01_nome');
$clrotulo->label('v01_dtinsc');
$clrotulo->label('v51_certidao');


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

$dd    = 0;
$pp    = 0;
$where = "where 1=1 and v13_instit =".db_getsession('DB_instit')." ";
if (($data!="--")&&($data1!="--")) {
  $where .= "and v13_dtemis between '$data' and '$data1'";
  $head4 = "Período: " . db_formatar($data,"d") . " a " . db_formatar($data1,"d");
} else if ($data!="--") {
  $where .= "and v13_dtemis >= '$data'";
  $head4 = "Período: a partir de " . db_formatar($data,"d");
} else if ($data1!="--") {
  $where .= " and v13_dtemis <= '$data1'";
  $head4 = "Período: até " . db_formatar($data1,"d");
}


$head5="";

if($iniciais=="1") {
  $where .= " and v51_certidao is not null ";
  $head5 = " (Com Inicial Emitida)";
} else if ($iniciais=="2") {
  $where .= " and v51_certidao is null ";
  $head5 = " (Sem Inicial Emitida)";
}

$desc_ordem = "Numérica";
$order_by = "v13_certid";


$head3 = "RELATÓRIO DE CERTIDÕES EMITIDAS" . ($rela=="c"?" (Completo)":" (Resumido)");


if ($ordem=="t") {
  $head5 = "TODAS CERTIDÕES".$head5;
  $sql = "select distinct
                 v13_dtemis,
                 v13_certid,
                 certter.v14_certid as parcelada,
                 certdiv.v14_certid as divati,
                 inicialcert.v51_certidao as inic,
                 inicialcert.v51_inicial
            from certid
                 left join inicialcert on v51_certidao       = v13_certid
                 left join certdiv     on certdiv.v14_certid = v13_certid
                 left join certter     on certter.v14_certid = v13_certid
           $where
        order by v13_certid";
  $result = pg_query($sql);
  if (pg_numrows($result) == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem certidões.');
  }
  
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',8);
  $troca = 1;
  $alt = 4;
  $total = 0;
  
  for ($x = 0; $x < pg_numrows($result); $x++) {
    db_fieldsmemory($result,$x);
    
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLv13_certid,1,0,"C",1);
      $pdf->cell(30,$alt,"Data Emissão",1,0,"C",1);
      $pdf->cell(90,$alt,'Tipo',1,0,"C",1);
      $pdf->cell(40,$alt,'Inicial Emitida',1,1,"C",1);
      if ($rela=="c") {
        $pdf->cell(20,$alt,'Codigo',1,0,"C",1);
        $pdf->cell(30,$alt,'Data de Lançamento',1,0,"C",1);
        $pdf->cell(90,$alt,'Nome/Razão Social',1,0,"C",1);
        $pdf->cell(40,$alt,'Numpre',1,1,"C",1);
      }
      $troca = 0;
    }
    
    if (empty($divati)) {
      $pp++;
      $tipo="Parcelamento";
    } else if (empty($parcelada)) {
      $tipo="Dívida Ativa";
      $dd++;
    }
    if (empty($inic)) {
      $ini="Não";
    } else {
      $ini="Sim";
      $ini=str_pad($v51_inicial,8, "0", STR_PAD_LEFT);
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(20,$alt,$v13_certid,0,0,"C",0);
    $pdf->cell(30,$alt,db_formatar($v13_dtemis,"d"),0,0,"C",0);
    $pdf->cell(90,$alt,$tipo,0,0,"L",0);
    $pdf->cell(40,$alt,$ini,0,1,"C",0);
    $total++;
    
    if ($rela=="c") {
      
      if ($tipo=="Parcelamento") {
        $result1 = pg_query("select certter.v14_certid,
                                    v07_parcel,
                                    v07_dtlanc,
                                    v07_totpar,
                                    v07_numpre,
                                  	case 
																		  when a.j01_numcgm is not null
														            then (select z01_nome from cgm where z01_numcgm = a.j01_numcgm)
															        when q02_numcgm is not null
																		    then (select z01_nome from cgm where z01_numcgm = q02_numcgm)
																			else 
																		    (select z01_nome from cgm where z01_numcgm = v07_numcgm)
																		end as z01_nome
                               from certter
                                    inner join certid on certid.v13_certid = certter.v14_certid
																		                 and certid.v13_instit = ".db_getsession('DB_instit')."  
                                    inner join termo  on v14_parcel        = v07_parcel
																		                 and v07_instit        = ".db_getsession('DB_instit')."
																		left join arrematric  on arrematric.k00_numpre = termo.v07_numpre
															      left join arreinscr   on arreinscr.k00_numpre  = termo.v07_numpre
															      left join iptubase a  on arrematric.k00_matric = a.j01_matric
														 	      left join issbase     on arreinscr.k00_inscr   = issbase.q02_inscr
                                    inner join cgm on z01_numcgm           = v07_numcgm
                              where certter.v14_certid=$v13_certid");
        
        for ($x1 = 0; $x1 < pg_numrows($result1); $x1++) {
          db_fieldsmemory($result1,$x1);
          $pdf->setfont('arial','',7);
          $pdf->cell(20,$alt,$v07_parcel,0,0,"C",0);
          $pdf->cell(30,$alt,db_formatar($v07_dtlanc,"d"),0,0,"C",0);
          $pdf->cell(90,$alt,$z01_nome,0,0,"L",0);
          $pdf->cell(40,$alt,$v07_numpre,0,1,"C",0);
        }
        $pdf->cell(180,$alt,"","B",1,"C",0);
      }
      
      if ($tipo=="Dívida Ativa") {
        $result2 = pg_query("select distinct
                                    v14_certid,
                                    v01_coddiv,
                                    v01_dtinsc,
                                    v01_numpre,
                                    v03_descr,
																		case 
																		  when a.j01_numcgm is not null
														            then (select z01_nome from cgm where z01_numcgm = a.j01_numcgm)
															        when q02_numcgm is not null
																		    then (select z01_nome from cgm where z01_numcgm = q02_numcgm)
																			else 
																		    (select z01_nome from cgm where z01_numcgm = v01_numcgm)
																		end as z01_nome
                               from certdiv
                                    inner join certid on certid.v13_certid = v14_certid
																		                 and certid.v13_instit = ".db_getsession('DB_instit')." 
                                    inner join divida on v01_coddiv        = v14_coddiv
																		                 and v01_instit        = ".db_getsession('DB_instit')."
																		left join arrematric  on arrematric.k00_numpre = divida.v01_numpre
															      left join arreinscr   on arreinscr.k00_numpre  = divida.v01_numpre
															      left join iptubase a  on arrematric.k00_matric = a.j01_matric
														 	      left join issbase     on arreinscr.k00_inscr   = issbase.q02_inscr
                                    inner join proced on v01_proced = v03_codigo																		          
                                    inner join cgm on z01_numcgm = v01_numcgm
                              where v14_certid = $v13_certid  ");
        
        for ($x2 = 0; $x2 < pg_numrows($result2); $x2++) {
          db_fieldsmemory($result2,$x2);
          if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
            $pdf->addpage();
            $pdf->setfont('arial','b',8);
            $pdf->cell(20,$alt,$RLv13_certid,1,0,"C",1);
            $pdf->cell(30,$alt,"Data Emissão",1,0,"C",1);
            $pdf->cell(90,$alt,'Tipo',1,0,"C",1);
            $pdf->cell(40,$alt,'Inicial Emitida',1,1,"C",1);
            if ($rela=="c") {
              $pdf->cell(20,$alt,'Codigo',1,0,"C",1);
              $pdf->cell(30,$alt,'Data de Lançamento',1,0,"C",1);
              $pdf->cell(90,$alt,'Nome/Razão Social',1,0,"C",1);
              $pdf->cell(40,$alt,'Numpre',1,1,"C",1);
            }
            $troca = 0;
          }
          
          $pdf->setfont('arial','',7);
          $pdf->cell(20,$alt,$v01_coddiv,0,0,"C",0);
          $pdf->cell(30,$alt,db_formatar($v01_dtinsc,"d"),0,0,"C",0);
          $pdf->cell(90,$alt,$z01_nome,0,0,"L",0);
          $pdf->cell(40,$alt,$v01_numpre,0,1,"C",0);
        }
        $pdf->cell(180,$alt,"","B",1,"C",0);
      }
    }
  }
}

if ($ordem=="p") {
  $head5 = "CERTIDÕES DE PARCELAMENTO".$head5;
  $sql = "select v13_dtemis,
                 v14_certid, 
                 v51_certidao as inic,
                 v51_inicial
            from certid
                 inner join certter     on certter.v14_certid = v13_certid
                 left  join inicialcert on v51_certidao       = v13_certid
          $where and  certter.v14_certid is not null and v13_instit = ".db_getsession('DB_instit')." 
        order by certter.v14_certid";
  $result = pg_query($sql);
  if (pg_numrows($result) == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem certidões.');
  }
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',8);
  $troca = 1;
  $alt = 4;
  $total = 0;
  
  for ($x = 0; $x < pg_numrows($result); $x++) {
    db_fieldsmemory($result,$x);
    
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLv13_certid,1,0,"C",1);
      $pdf->cell(30,$alt,"Data Emissão",1,0,"C",1);
      $pdf->cell(90,$alt,'Tipo',1,0,"C",1);
      $pdf->cell(40,$alt,'Inicial Emitida',1,1,"C",1);
      if ($rela=="c") {
        $pdf->cell(20,$alt,'Codigo',1,0,"C",1);
        $pdf->cell(30,$alt,'Data de Lançamento',1,0,"C",1);
        $pdf->cell(90,$alt,'Nome/Razão Social',1,0,"C",1);
        $pdf->cell(40,$alt,'Numpre',1,1,"C",1);
      }
      $troca = 0;
    }
    
    if (empty($inic)) {
      //$ini="Não";
      $ini="-";
    } else {
      //$ini="Sim";
      $ini=str_pad($v51_inicial,8, "0", STR_PAD_LEFT);
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(20,$alt,$v14_certid,0,0,"C",0);
    $pdf->cell(30,$alt,db_formatar($v13_dtemis,"d"),0,0,"C",0);
    $pdf->cell(90,$alt,"Parcelamento",0,0,"L",0);
    $pdf->cell(40,$alt,$ini,0,1,"C",0);
    $total++;
    
    if ($rela=="c") {
      $result1 = pg_query("select distinct
                                  certter.v14_certid,
                                  v07_parcel,
                                  v07_dtlanc,
                                  v07_numpre,
                                  z01_nome
                             from certter
                                  inner join certid on certid.v13_certid = certter.v14_certid
																	                 and certid.v13_instit = ".db_getsession('DB_instit')." 
                                  inner join termo  on v14_parcel        = v07_parcel
																	                 and v07_instit        = ".db_getsession('DB_instit')." 
                                  inner join cgm    on z01_numcgm        = v07_numcgm
                            where certter.v14_certid=$v14_certid");
      
      for ($x1 = 0; $x1 < pg_numrows($result1); $x1++) {
        db_fieldsmemory($result1,$x1);
        if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
          $pdf->addpage();
          $pdf->setfont('arial','b',8);
          $pdf->cell(20,$alt,$RLv13_certid,1,0,"C",1);
          $pdf->cell(30,$alt,"Data Emissão",1,0,"C",1);
          $pdf->cell(90,$alt,'Tipo',1,0,"C",1);
          $pdf->cell(40,$alt,'Inicial Emitida',1,1,"C",1);
          if ($rela=="c") {
            $pdf->cell(20,$alt,'Codigo',1,0,"C",1);
            $pdf->cell(30,$alt,'Data de Lançamento',1,0,"C",1);
            $pdf->cell(90,$alt,'Nome/Razão Social',1,0,"C",1);
            $pdf->cell(40,$alt,'Numpre',1,1,"C",1);
          }
          $troca = 0;
        }
        
        
        $pdf->setfont('arial','',7);
        $pdf->cell(20,$alt,$v07_parcel,0,0,"C",0);
        $pdf->cell(30,$alt,db_formatar($v07_dtlanc,"d"),0,0,"C",0);
        $pdf->cell(90,$alt,$z01_nome,0,0,"L",0);
        $pdf->cell(40,$alt,$v07_numpre,0,1,"C",0);
        $pdf->cell(180,$alt,"","B",1,"C",0);
        
      }
    }
  }
}

if ($ordem=="d") {
  $head5 = "CERTIDÕES DE DIVIDA ATIVA".$head5;
  $sql = "select distinct * from (
          select v13_dtemis,
                 v14_certid,
                 v51_certidao as inic,
                 v51_inicial
            from certid
                 inner join certdiv     on certdiv.v14_certid = v13_certid
                 left  join inicialcert on v51_certidao       = v13_certid
          $where and certdiv.v14_certid is not null and certid.v13_instit = ".db_getsession('DB_instit')." ) as x
        order by v14_certid   ";
  $result = pg_query($sql);
  if (pg_numrows($result) == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem certidões.');
  }
  
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',8);
  $troca = 1;
  $alt = 4;
  $total = 0;
  
  for ($x = 0; $x < pg_numrows($result); $x++) {
    db_fieldsmemory($result,$x);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLv13_certid,1,0,"C",1);
      $pdf->cell(30,$alt,"Data Emissão",1,0,"C",1);
      $pdf->cell(90,$alt,'Tipo',1,0,"C",1);
      $pdf->cell(40,$alt,'Inicial Emitida',1,1,"C",1);
      if ($rela=="c") {
        $pdf->cell(20,$alt,'Codigo',1,0,"C",1);
        $pdf->cell(30,$alt,'Data de Lançamento',1,0,"C",1);
        $pdf->cell(90,$alt,'Nome/Razão Social',1,0,"C",1);
        $pdf->cell(40,$alt,'Numpre',1,1,"C",1);
      }
      $troca = 0;
    }
    if (empty($inic)) {
      //$ini="Não";
      $ini="-";
    } else {
      //$ini="Sim";
      $ini=str_pad($v51_inicial,8, "0", STR_PAD_LEFT);
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(20,$alt,$v14_certid,0,0,"C",0);
    $pdf->cell(30,$alt,db_formatar($v13_dtemis,"d"),0,0,"C",0);
    $pdf->cell(90,$alt,"Dívida Ativa",0,0,"L",0);
    $pdf->cell(40,$alt,$ini,0,1,"C",0);
    $total++;
    if ($rela=="c") {
      $result2 = pg_query("select v14_certid,
                                  v01_coddiv,
                                  v01_numpre,
                                  v01_dtinsc,
                                  v03_descr,
                                  z01_nome
                             from certdiv
                                  inner join certid on v13_certid = v14_certid
																	                 and v13_instit = ".db_getsession('DB_instit')." 
                                  inner join divida on v01_coddiv = v14_coddiv
																	                 and v01_instit = ".db_getsession('DB_instit')." 
                                  inner join proced on v01_proced = v03_codigo
                                  inner join cgm    on z01_numcgm = v01_numcgm
                            where certdiv.v14_certid = $v14_certid  ");
      
      for ($x2 = 0; $x2 < pg_numrows($result2); $x2++) {
        db_fieldsmemory($result2,$x2);
        if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
          $pdf->addpage();
          $pdf->setfont('arial','b',8);
          $pdf->cell(20,$alt,$RLv13_certid,1,0,"C",1);
          $pdf->cell(30,$alt,"Data Emissão",1,0,"C",1);
          $pdf->cell(90,$alt,'Tipo',1,0,"C",1);
          $pdf->cell(40,$alt,'Inicial Emitida',1,1,"C",1);
          if ($rela=="c") {
            $pdf->cell(20,$alt,'Codigo',1,0,"C",1);
            $pdf->cell(30,$alt,'Data de Lançamento',1,0,"C",1);
            $pdf->cell(90,$alt,'Nome/Razão Social',1,0,"C",1);
            $pdf->cell(40,$alt,'Numpre',1,1,"C",1);
          }
          $troca = 0;
        }
        
        $pdf->setfont('arial','',7);
        $pdf->cell(20,$alt,$v01_coddiv,0,0,"C",0);
        $pdf->cell(30,$alt,db_formatar($v01_dtinsc,"d"),0,0,"C",0);
        $pdf->cell(90,$alt,$z01_nome,0,0,"L",0);
        $pdf->cell(40,$alt,$v01_numpre,0,1,"C",0);
        
      }
      $pdf->cell(180,$alt,"","B",1,"C",0);
    }
  }
}
if ($ordem=="t") {
  $pdf->setfont('arial','b',7);
  $pdf->cell(180,$alt,'TOTAL DE CERTIDÕES DE DÍVIDAS ATIVAS :  '.$dd,0,1,"L",0);
  $pdf->cell(180,$alt,'TOTAL DE CERTIDÕES DE PARCELAMENTO :  '.$pp,0,1,"L",0);
}
$pdf->setfont('arial','b',8);
$pdf->cell(180,$alt,'TOTAL DE CERTIDÕES  :  '.$total,"T",0,"L",0);
$pdf->Output();
?>