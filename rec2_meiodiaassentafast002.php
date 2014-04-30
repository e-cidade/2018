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


function meio_dia_assent_afast($matric,$datafim,$ordem,$tipo){
    global $tipoasse,$cgm,$pessoal,$head3,$head5;
    
   db_selectmax("pessoal","select * from pessoal" .bb_condicaosubpes("r01_")." and r01_regist = " .db_sqlformat($matric));
   db_selectmax("cgm","select z01_numcgm,z01_nome,z01_cgccpf,z01_ident from cgm where z01_numcgm = ".db_sqlformat(db_str($pessoal[0]["r01_numcgm"],6)));
    $chave = true;
    $chave1 = true;
    $arquivo = "arq1";
    $m_nome  = array();
    $m_tipo  = array();
    $tamanho = array();
    $decimal = array();
    
    $m_nome[1] = "datini";
    $m_nome[2] = "data";
    $m_nome[3] = "c_tipo";
    $m_nome[4] = "descr";
    $m_nome[5] = "histor";
    $m_nome[6] = "hist2";

    $m_tipo[1] = "d";
    $m_tipo[2] = "d";
    $m_tipo[3] = "c";
    $m_tipo[4] = "c";
    $m_tipo[5] = "c";
    $m_tipo[6] = "c";

    $tamanho[1] = 8;
    $tamanho[2] = 8;
    $tamanho[3] = 5;
    $tamanho[4] = 40;
    $tamanho[5] = 240;
    $tamanho[6] = 240;

    $decimal[1] = 0;
    $decimal[2] = 0;
    $decimal[3] = 0;
    $decimal[4] = 0;
    $decimal[5] = 0;
    $decimal[6] = 0;

    db_criatemp($arquivo,$m_nome,$m_tipo,$tamanho,$decimal);
    
    global $assmeio;
    if(db_selectmax("assmeio","select * from assmeio where h22_regist = " . db_sqlformat(db_str($matric,6)). " and h22_dtconc <= ". db_sqlformat($datafim))){
       for($Iassmeio=0;$Iassmeio< count($assmeio);$Iassmeio++){
          if(db_selectmax("tipoasse","select * from tipoasse where h12_codigo = " . db_sqlformat($assmeio[$Iassmeio]["h22_assent"]))){
             if( strtolower($qual_tipo) != "g"){
                if( strtolower($qual_tipo) == "a"){
                   if( strtolower($tipoasse[0]["h12_tipo"]) == "s"){
                      continue;
                   }
                }else{
                   if( strtolower($tipoasse[0]["h12_tipo"]) == "a"){
                      continue;
                   }
                }
             }
             $chave = true;
             
	     $mar1 = array();
	     $mar2 = array();
	     
             $mar1[1] = "datini";
             $mar1[2] = "data";
             $mar1[3] = "c_tipo";
             $mar1[4] = "descr";
             $mar1[5] = "histor";
             $mar1[6] = "hist2";
             
             $mar2[1] = $assmeio[$Iassmeio]["h22_dtconc"];
             $mar2[2] = $assmeio[$Iassmeio]["h22_data"]  ;
             $mar2[3] = $assmeio[$Iassmeio]["h22_assent"];
             $mar2[4] = $tipoasse[0]["h12_descr"];
             $mar2[5] = $assmeio[$Iassmeio]["h22_histor"];
             $mar2[6] = $assmeio[$Iassmeio]["h22_hist2"] ;
             db_insert($arquivo,$mar1,$mar2);
          }
       }
    }
    if( strtolower($ordem) == "d"){
       $ord_sql = " order by datini ";
    }else{
       $ord_sql = " order by c_tipo,datini";
    }
    global $work;
    if(!db_selectmax("work","select * from " . $arquivo . $ord_sql)){
      $head3 = "Assent/Afastamentos de Meio Dia por Funcionario ";
      $head5 = "Funcionario : ".$cgm[0]["z01_nome"];
      
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $total = 0;
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',8);
      $alt = 4;
      $troca = true; 
      $regenc = false;
      for($Iwork=0;$Iwork< count($work);$Iwork++){
         if($pdf->gety() > $pdf->h - 30 || $troca  ){
            $pdf->addpage();
            $pdf->setfont('arial','b',8);
            $pdf->cell(15,$alt,"Data",0,0,"C",1);
            $pdf->cell(15,$alt,"Dt.Assent",0,0,"C",1);
            $pdf->cell(15,$alt,"Regencia",0,0,"C",1);
            $pdf->cell(60,$alt,"Historico",0,1,"C",1);
	    $troca = false;
	 }
	 global $tipoasse;
	 db_selectmax("tipoasse","select * from tipoasse where h12_codigo = " . db_sqlformat($work[$Iwork]["c_tipo"]));
	 if(!db_boolean($tipoasse[0]["h12_regenc"])){

	    $pdf->cell(15,$alt,$work[$Iwork]["datini"],0,0,"L",1);
	    $pdf->cell(15,$alt,$work[$Iwork]["data"],0,0,"L",1);
	    $pdf->cell(15,$alt,$tipoasse[0]["h12_assent"]." ".db_substr($tipoasse[0]["h12_descr"],1,30),0,1,"L",1);
	    $pdf->multicell(0,$alt,db_substr($work[$Iwork]["histor"],1,60),0,0,"L",1);
	 }else{
	    $regenc = true;
	 }
      }
      $head3 = "Regencia - Meio Dia ";
      $head5 = "Funcionario : $matric ".$cgm[0]["z01_nome"];
      if($regenc){
	 for($Iwork=0;$Iwork< count($work);$Iwork++){
            if($pdf->gety() > $pdf->h - 30 || $regenc ){
               $pdf->addpage();
               $pdf->setfont('arial','b',8);
               $pdf->cell(15,$alt,"Data",0,0,"C",1);
               $pdf->cell(15,$alt,"Dt.Assent",0,0,"C",1);
               $pdf->cell(15,$alt,"Regencia",0,0,"C",1);
               $pdf->cell(60,$alt,"Historico",0,1,"C",1);
	    $regenc = false;
	    }
	    db_selectmax("tipoasse","select * from tipoasse where h12_codigo = " . db_sqlformat($work[$Iwork]["c_tipo"]));
	    if(db_boolean($tipoasse[0]["h12_regenc"])){
	       $pdf->cell(15,$alt,$work[$Iwork]["datini"],0,0,"L",1);
	       $pdf->cell(15,$alt,$work[$Iwork]["data"],0,0,"L",1);
	       $pdf->cell(15,$alt,$tipoasse[0]["h12_assent"]." ".db_substr($tipoasse[0]["h12_descr"],1,30),0,1,"L",1);
	       $pdf->multicell(0,$alt,db_substr($work[$Iwork]["histor"],1,60),0,0,"C",1);
	    }
	 }
      }
      if(!db_empty($pessoal[0]["r01_admiss"])){
	 $pdf->cell(0,$alt, "Data Admissao :".db_dtoc($pessoal[0]["r01_admiss"]),0,1,"L",1);
	 $pdf->cell(0,$alt, "Regime : ".db_str($pessoal[0]["r01_regime"],1)."-".regime_452($pessoal[0]["r01_regime"]),0,1,"L",1);
      }
      
      $pdf->Output();
   }
}


function regime_452($regime){
  
if(      $regime = 1){
   $retorno = "Estatutario";
}else if($regime == 2){
   $retorno = "Celetista";
}else if($regime == 3){
   $retorno = "Extra Quadro";
}

return $retorno;

}

global $cfpess,$subpes,$db21_codcli,$matric ;

include("fpdf151/pdf.php");
include("libs/db_libpessoal.php");
include("libs/db_sql.php");

db_postmemory($HTTP_GET_VARS);

$subpes = db_anofolha().'/'.db_mesfolha();

meio_dia_assent_afast($matric,$datafim,$ordem,$tipo);

//exit;

db_redireciona("rec2_meiodiaassentafast001.php");

?>
