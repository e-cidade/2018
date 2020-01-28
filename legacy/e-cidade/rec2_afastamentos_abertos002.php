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

function afastamentos_abertos($tipos,$regime,$datai){

   global $work,$ordem;

   $nom = array();
   $tip = array();
   $tam = array();
   $dec = array();
   
   $nom[1] = "w_REGIST";
   $nom[2] = "w_NOME";
   $nom[3] = "w_LOTAC";
   $nom[4] = "w_ASSENT";
   $nom[5] = "w_DTCONC";
   $nom[6] = "w_DTTERM";
   
   $tam[1] = 6;
   $tam[2] = 40;
   $tam[3] = 4;
   $tam[4] = 5;
   $tam[5] = 8;
   $tam[6] = 8;
   
   $dec = array_fill(1,6,0);
   $tip = array_fill(1,6,"c");
   $tip[5] = "d";
   $tip[6] = "d";
   
   $arquivo = "arq1";
   db_criatemp($arquivo, $nom, $tip, $tam, $dec);
   global $tipoasse;
   db_selectmax("tipoasse","select * from tipoasse where h12_codigo in (".$tipos.")");

   $contador = 1;
   $quantidade = count($tipoasse);
   
   for($Itipoasse=0;$Itipoasse< count($tipoasse);$Itipoasse++){
      global $assenta;
      $sWhereVerificaLotacao = " select distinct rh02_regist";
      $sWhereVerificaLotacao .= "  from rhpessoalmov                             ";
      $sWhereVerificaLotacao .= " where rh02_anousu = ".DBPessoal::getAnoFolha()  ;
      $sWhereVerificaLotacao .= "   and rh02_mesusu = ".DBPessoal::getMesFolha()  ;
      $sWhereVerificaLotacao .= "   and rh02_lota in (select rh157_lotacao       ";
      $sWhereVerificaLotacao .= "                       from db_usuariosrhlota   ";
      $sWhereVerificaLotacao .= "                      where rh157_usuario = ".db_getsession("DB_instit").")";
      if( db_selectmax("assenta","select * from assenta inner join assentamentofuncional on rh193_assentamento_funcional = h16_codigo where h16_regist in (".$sWhereVerificaLotacao.") and h16_assent = ".db_sqlformat($tipoasse[$Itipoasse]["h12_codigo"]))){

         $mat_campos  = array();
	 $mat_valores = array();
         $mat_campos[1] = "w_regist";
         $mat_campos[2] = "w_nome";
         $mat_campos[3] = "w_lotac";
         $mat_campos[4] = "w_assent";
         $mat_campos[5] = "w_dtconc";
         $mat_campos[6] = "w_dtterm";

         for($Iassenta=0;$Iassenta< count($assenta);$Iassenta++){
            if( db_empty($assenta[$Iassenta]["h16_dtterm"]) || db_mktime($assenta[$Iassenta]["h16_dtterm"]) >= db_mktime($datai)){
               $condicaoaux = " and r01_regist = ".db_sqlformat($assenta[$Iassenta]["h16_regist"]);
	       global $pessoal;
               db_selectmax("pessoal","select * from pessoal ".bb_condicaosubpes("r01_").$condicaoaux );
               if( db_val($regime) != 0){
                 if( $pessoal[0]["r01_regime"] != db_val($regime)){
                     continue;
                  }
               }
	       global $cgm;
               db_selectmax("cgm","select * from cgm where z01_numcgm = ".db_sqlformat($pessoal[0]["r01_numcgm"]));
               
               $mat_valores[1] = db_str($assenta[$Iassenta]["h16_regist"],6);
               $mat_valores[2] = $cgm[0]["z01_nome"];
               $mat_valores[3] = $pessoal[0]["r01_lotac"];
               $mat_valores[4] = $assenta[$Iassenta]["h16_assent"];
               $mat_valores[5] = db_nulldata($assenta[$Iassenta]["h16_dtconc"]);
               $mat_valores[6] = db_nulldata($assenta[$Iassenta]["h16_dtterm"]);
               db_insert($arquivo, $mat_campos, $mat_valores);
            }
         }
      }
   }
   global $work;
   if( $ordem == "a"){
      db_selectmax("work","select * from ".$arquivo." order by w_nome,w_dtconc") ;
   }else{
      db_selectmax("work","select * from ".$arquivo." order by w_lotac,w_nome,w_dtconc") ;
   }
   
   $pdf = new PDF();
   $pdf->Open();
   $pdf->AliasNbPages();
   $pdf->setfillcolor(235);
   $pdf->setfont('arial','b',8);
   $alt = 4;

   if(count($work) > 0){
       $contador = 0;
       $lotac = $work[0]["w_lotac"];
       $troca = true;
       for($Iwork=0;$Iwork< count($work);$Iwork++){
          if ($pdf->gety() > $pdf->h - 30 || $troca ){
             $pdf->addpage();
             $pdf->setfont('arial','b',8);
	     if( !db_empty($tipoasse[0]["h12_assent"])){
		if( strtolower($tipoasse[0]["h12_descr"]) == "s"){
                   $pdf->cell(0,$alt,"Assentamento: ".$tipoasse[0]["h12_assent"]." - ".$tipoasse[0]["h12_descr"],0,0,"L",1);
		}else{
                   $pdf->cell(0,$alt,"Afastamento : ".$tipoasse[0]["h12_assent"]." - ".$tipoasse[0]["h12_descr"],0,0,"L",1);
		}
                $pdf->ln($alt);
	     }
             $pdf->cell(15,$alt,"Registro",0,0,"L",1);
             $pdf->cell(10,$alt,"Lota",0,0,"L",1);
             $pdf->cell(45,$alt,"Nome do funcionario",0,0,"L",1);
             $pdf->cell(15,$alt,"Codigo",0,0,"L",1);
             $pdf->cell(65,$alt,"Descricao do afastamento",0,0,"L",1);
             $pdf->cell(20,$alt,"Dt Inicio",0,0,"L",1);
             $pdf->cell(20,$alt,"Data Final ",0,1,"L",1);
	     $troca = false;
	  }
	  global $tipoasse;
	  db_selectmax("tipoasse","select * from tipoasse where h12_codigo = ".db_sqlformat($work[$Iwork]["w_assent"]));
          $pdf->cell(15,$alt, $work[$Iwork]["w_regist"],0,0,"R",1);
          $pdf->cell(10,$alt, $work[$Iwork]["w_lotac"],0,0,"L",1);
          $pdf->cell(45,$alt, $work[$Iwork]["w_nome"],0,0,"L",1);
          $pdf->cell(15,$alt, $tipoasse[0]["h12_assent"],0,0,"L",1);
          $pdf->cell(65,$alt, $tipoasse[0]["h12_descr"],0,0,"L",1);
          $pdf->cell(20,$alt, $work[$Iwork]["w_dtconc"],0,0,"L",1);
          $pdf->cell(20,$alt, $work[$Iwork]["w_dtterm"],0,1,"L",1);
	  if( $ordem == "l"){
             $pdf->cell(0,$alt, "Total de Funcionarios Listados : ".count($work),0,1,"C",1);
	  }
       }
       $pdf->ln($alt);
       if( $ordem == "l"){
          $pdf->cell(0,$alt, "Total de Funcionarios Listados : $quantidade  Total Geral : $quantidade",0,0,"C",1);
       }else{
          $pdf->cell(0,$alt, "Total de Funcionarios Listados : $quantidade",0,0,"C",1);
       }
   }
   $pdf->Output();
}

global $cfpess,$subpes,$d08_carnes,$matric ;

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_libpessoal.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_sql.php"));

db_postmemory($HTTP_GET_VARS);

db_inicio_transacao();

$subpes = db_anofolha().'/'.db_mesfolha();

afastamentos_abertos($tipos,$regime,$datai);

db_fim_transacao();
//exit;

db_redireciona("rh02_afastamentos_abertos001.php");
?>