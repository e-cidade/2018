<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include("classes/db_cgm_classe.php");
include("fpdf151/pdf.php");
include("classes/db_iptubase_classe.php");
include("classes/db_issbase_classe.php");

db_postmemory($HTTP_SERVER_VARS);
$total  = 0;
$lPrint = 1;

if ( $numcgm == '' && $opcao != 'socios' ){
   $sql = "select * 
             from proprietario 
            where j01_matric = $matricula limit 1";
   $result = pg_exec($sql);
   db_fieldsmemory($result,0);
   $setor   = $j34_setor;
   $quadra  = $j34_quadra;
   $lote    = $j34_lote;
   $refant  = $j40_refant;
   $ender   = $nomepri;
   $bairro  = $j13_descr;
   $tipoimp = $j01_tipoimp;
   if (isset($j01_baixa) && $j01_baixa != ""){
       $baixa   = $j01_baixa; 
   }else{
       $baixa = "";  
   }
   
   
   if ($opcao == "promitente") {
      $xtipo = "Promitentes";
      $sql = "select * 
                from promitente 
   	                 inner join cgm on z01_numcgm = j41_numcgm 
   	           where j41_matric = $matricula";
   } else {
      $xtipo = "Outros Propriet�rios";
      $sql = "select * 
                from propri 
   	                 inner join cgm on z01_numcgm = j42_numcgm 
   	           where j42_matric = $matricula";
   }
   $result = pg_exec($sql);
   
   if (pg_numrows($result) == 0 ) {
      db_redireciona("db_erros.php?fechar=true&db_erro=Propriet�rios/Promitentes nao Encontrados");
      exit;
   }
   db_fieldsmemory($result,0,true);
   
   $head4 = "RELAT�RIO DE ".strtoupper($xtipo);
   $pdf = new PDF(); // abre a classe
   
   $pdf->Open(); // abre o relatorio
   $pdf->AliasNbPages(); // gera alias para as paginas
   $pdf->AddPage('L'); // adiciona uma pagina
   $pdf->SetFillColor(220);
   $pdf->multicell(0,0.5,'',1,"C",0);
   $pdf->SetFont('arial','B',10);
   $pdf->multicell(0,6,'Dados do Im�vel',0,"C",0);
   $pdf->SetFont('arial','',8);
   $pdf->multicell(0,4,'Matr�cula : '.$matricula.'   Setor : '.$setor.'   Quadra : '.$quadra.'   Lote : '.$lote,0,"L",0);
   $pdf->multicell(0,4,'Endere�o : '.$ender.'    Bairro : '.$bairro,0,"L",0);
   $pdf->multicell(0,4,'Refer�ncia Anterior : '.$refant,0,"L",0);
   $pdf->multicell(0,4,'Tipo de Imposto : '.$tipoimp,0,"L",0);
   $pdf->ln(2);
   $pdf->multicell(0,0.5,'',1,"C",0);
   $pdf->ln(5);
   $pdf->SetFont('arial','B',10);
   $pdf->multicell(0,6,'Dados dos '.$xtipo,0,"C",0);
   $pdf->ln(5);
   
   for ($i=0;$i<pg_numrows($result);$i++) {
      db_fieldsmemory($result,$i);
      $pdf->SetFont('arial','',8);
      $pdf->multicell(0,4,'Numcgm : '.$z01_numcgm,0,"L",0);
      $pdf->multicell(0,4,'Nome : '.$z01_nome,0,"L",0);
      $pdf->multicell(0,4,'Endere�o : '.$z01_ender.', '.$z01_numero.' '.$z01_compl.'    Bairro : '.$bairro,0,"L",0);
      $pdf->multicell(0,4,'Municipio : '.$z01_munic,0,"L",0);
      $pdf->ln(5);
      $total += 1; 
   }
   
} else if ( $opcao == 'socios' ) {
	
   $clissbase = new cl_issbase;
   $resultiss = $clissbase->sql_record($clissbase->sql_query($inscricao));
   db_fieldsmemory($resultiss,0);
   
   $head4 = "RELAT�RIO DE S�CIOS";
   $pdf = new PDF(); // abre a classe
   $pdf->Open(); // abre o relatorio
   $pdf->AliasNbPages(); // gera alias para as paginas
   $pdf->AddPage('L'); // adiciona uma pagina
   $pdf->SetFillColor(220);
   $pdf->multicell(0,0.5,'',1,"C",0);
   $pdf->SetFont('arial','B',10);
   $pdf->multicell(0,6,'Dados da Empresa',0,"C",0);
   $pdf->SetFont('arial','',8);
   $pdf->multicell(0,4,'Inscri��o : '.$inscricao,0,"L",0);
   $pdf->multicell(0,4,'Nome/Raz�o Social : '.$z01_nomefanta,0,"L",0);
   $pdf->multicell(0,4,'Endere�o : '.$z01_ender.', '.$z01_numero.' '.$z01_compl.'    Bairro : '.$z01_bairro,0,"L",0);
   $pdf->ln(2);
   $pdf->multicell(0,0.5,'',1,"C",0);
   $pdf->ln(5);
   $pdf->SetFont('arial','B',10);
   $pdf->multicell(0,6,'Dados dos S�cios',0,"C",0);
   $pdf->ln(5);
   
   $result = $clissbase->sql_record($clissbase->sqlinscricoes_socios($inscricao));
   for ($i=0;$i<pg_numrows($result);$i++) {
      db_fieldsmemory($result,$i);
      $pdf->SetFont('arial','',8);
      $pdf->multicell(0,4,'Numcgm : '.$z01_numcgm,0,"L",0);
      $pdf->multicell(0,4,'Nome : '.$z01_nome,0,"L",0);
      $pdf->multicell(0,4,'Endere�o : '.$z01_ender.', '.$z01_numero.' '.$z01_compl.'    Bairro : '.$z01_bairro,0,"L",0);
      $pdf->multicell(0,4,'Munic�pio : '.$z01_munic,0,"L",0);
      $pdf->ln(5);
      $total += 1; 
   }
   
} else if ( $opcao == 'matricula' ) {

   $sql = "select * 
             from cgm 
            where z01_numcgm = $numcgm";
   $result = pg_exec($sql);
   db_fieldsmemory($result,0);
   $head4 = "RELAT�RIO DE MATR�CULAS CADASTRADAS";
   $head5 = $numcgm.' - '.$z01_nome;

   $pdf = new PDF(); // abre a classe
   $pdf->Open(); // abre o relatorio
   $pdf->AliasNbPages(); // gera alias para as paginas
   $pdf->AddPage('L'); // adiciona uma pagina
   $pdf->SetFillColor(220);
   $pdf->multicell(0,0.5,'',1,"C",0);
   $pdf->SetFont('arial','B',10);
   $pdf->multicell(0,6,'Dados do Propriet�rio',0,"C",0);
   $pdf->SetFont('arial','',8);
   $pdf->multicell(0,4,'Numcgm : '.$numcgm,0,"L",0);
   $pdf->multicell(0,4,'Nome : '.$z01_nome,0,"L",0);
   $pdf->multicell(0,4,'Endere�o : '.$z01_ender.', '.$z01_numero.' '.$z01_compl.'    Bairro : '.$z01_bairro,0,"L",0);
   $pdf->multicell(0,4,'Munic�pio : '.$z01_munic.' - '.$z01_uf,0,"L",0);
   $pdf->ln(2);
   $pdf->multicell(0,0.5,'',1,"C",0);
   $pdf->ln(5);


   $clsqlamatriculas = new cl_iptubase;
   include("classes/db_loteloc_classe.php");
   include("classes/db_cfiptu_classe.php");
   $clloteloc = new cl_loteloc;
   $clcfiptu  = new cl_cfiptu;
  
   $utilizaloc = $clcfiptu->sql_record($clcfiptu->sql_query("","j18_utilizaloc","","j18_anousu = ".db_getsession("DB_anousu")));
   $linhas = $clcfiptu->numrows;
   if($linhas > 0){
      db_fieldsmemory($utilizaloc,0);
   }else{
     $j18_utilizaloc = 'f';
   }
  

   $sql = $clsqlamatriculas->sqlmatriculas_nome_numero($numcgm, @$regracgm);
   $result = pg_exec($sql);
   $pdf->SetFont('arial','B',10);
   $pdf->multicell(0,6,'Dados das Matr�culas',0,"C",0);
   $pdf->SetFont('arial','B',8);
   $pdf->ln(5);
   $pdf->cell(20,4,'Matr�cula'   ,1,0,"C",1);
   $pdf->cell(30,4,'Tipo'        ,1,0,"C",1);
   $pdf->cell(70,4,'Rua/Avenida' ,1,0,"L",1);
   $pdf->cell(15,4,'N�mero'      ,1,0,"C",1);
   $pdf->cell(40,4,'Compl'       ,1,0,"L",1);
   $pdf->cell(60,4,'Bairro'      ,1,1,"L",1);
   $pdf->cell(15,4,'ID Lote'     ,1,0,"C",1);
   $pdf->cell(15,4,'Setor'       ,1,0,"C",1);
   $pdf->cell(15,4,'Quadra'      ,1,0,"C",1);
   $pdf->cell(15,4,'Lote'        ,1,0,"C",1);
   $pdf->cell(15,4,'�rea M2'     ,1,0,"C",1);
   if($j18_utilizaloc != 'f'){
    $pdf->cell(17,4,'Setorloc'   ,1,0,"C",1);
    $pdf->cell(17,4,'Quadraloc'  ,1,0,"C",1);   
    $pdf->cell(17,4,'Loteloc'    ,1,0,"C",1);
   }
   $pdf->cell(17,4,'Baixa'       ,1,0,"C",1);
   $pdf->cell(92,4,""            ,1,1,"C",1);
   
   for($i=0;$i<pg_numrows($result);$i++){
      db_fieldsmemory($result,$i);
      
      if ($pdf->gety() > ( $pdf->h - 30 ) ){
	     $pdf->addpage('L');
         $pdf->SetFont('arial','B',8);
         $pdf->cell(20,4,'Matr�cula'   ,1,0,"C",1);
         $pdf->cell(30,4,'Tipo'        ,1,0,"C",1);
         $pdf->cell(70,4,'Rua/Avenida' ,1,0,"L",1);
         $pdf->cell(15,4,'N�mero'      ,1,0,"C",1);
         $pdf->cell(40,4,'Compl'       ,1,0,"L",1);
         $pdf->cell(60,4,'Bairro'      ,1,1,"L",1);
         $pdf->cell(15,4,'ID Lote'     ,1,0,"C",1);
         $pdf->cell(15,4,'Setor'       ,1,0,"C",1);
         $pdf->cell(15,4,'Quadra'      ,1,0,"C",1);
         $pdf->cell(15,4,'Lote'        ,1,0,"C",1);
         $pdf->cell(15,4,'�rea M2'     ,1,0,"C",1);
	     if($j18_utilizaloc != 'f') {
	      $pdf->cell(17,4,'Setorloc'   ,1,0,"C",1);
          $pdf->cell(17,4,'Quadraloc'  ,1,0,"C",1);
          $pdf->cell(17,4,'Loteloc'    ,1,0,"C",1);
         }
         $pdf->cell(17,4,'Baixa'       ,1,0,"C",1);
         $pdf->cell(92,4,""            ,1,1,"C",1);
      }
      
      if($lPrint == 1) {
      	$lPrint = 0;
      } else {
      	$lPrint = 1;
      }
      	 
      $pdf->SetFont('arial','',8);
      $pdf->cell(20,4,$j01_matric      ,"T",0,"C",$lPrint);
      $pdf->cell(30,4,$proprietario    ,"T",0,"C",$lPrint);
      $pdf->cell(70,4,$nomepri         ,"T",0,"L",$lPrint);
      $pdf->cell(15,4,$j39_numero      ,"T",0,"C",$lPrint);
      $pdf->cell(40,4,$j39_compl       ,"T",0,"L",$lPrint);
      $pdf->cell(60,4,$j13_descr       ,"T",1,"L",$lPrint);
      $pdf->cell(15,4,$j01_idbql       ,"B",0,"C",$lPrint);
      $pdf->cell(15,4,$j34_setor       ,"B",0,"C",$lPrint);
      $pdf->cell(15,4,$j34_quadra      ,"B",0,"C",$lPrint);
      $pdf->cell(15,4,$j34_lote        ,"B",0,"C",$lPrint);
      $pdf->cell(15,4,$j34_area        ,"B",0,"C",$lPrint);
      if($j18_utilizaloc != 'f'){
       $resultloc = $clloteloc->sql_record($clloteloc->sql_query($j01_idbql,"j06_setorloc,j06_quadraloc,j06_lote"));
       if($clloteloc->numrows > 0){
        db_fieldsmemory($resultloc,0);
       }
       $pdf->cell(17,4,@$j06_setorloc  ,"B",0,"C",$lPrint);
       $pdf->cell(17,4,@$j06_quadraloc ,"B",0,"C",$lPrint);
       $pdf->cell(17,4,@$j06_lote      ,"B",0,"C",$lPrint);
      }
      $pdf->cell(17,4,$j01_baixa       ,"B",0,"C",$lPrint);
      $pdf->cell(92,4,""               ,"B",1,"C",$lPrint);
      $total += 1;
   }
   
} elseif ( $opcao == 'inscricao' ) {

   $sql = "select * from cgm where z01_numcgm = $numcgm";
   $result = pg_exec($sql);
   db_fieldsmemory($result,0);
   $head4 = "RELAT�RIO DE INSCRI��ES CADASTRADAS";
   $head5 = $numcgm.' - '.$z01_nome;

   $pdf = new PDF(); // abre a classe
   $pdf->Open(); // abre o relatorio
   $pdf->AliasNbPages(); // gera alias para as paginas
   $pdf->AddPage('L'); // adiciona uma pagina
   $pdf->multicell(0,0.5,'',1,"C",0);
   $pdf->SetFont('arial','B',10);
   $pdf->multicell(0,6,'Dados do Propriet�rio',0,"C",0);
   $pdf->SetFont('arial','',8);
   $pdf->multicell(0,4,'Numcgm : '.$numcgm,0,"L",0);
   $pdf->multicell(0,4,'Nome : '.$z01_nome,0,"L",0);
   $pdf->multicell(0,4,'Endere�o : '.$z01_ender.', '.$z01_numero.' '.$z01_compl.'    Bairro : '.$z01_bairro,0,"L",0);
   $pdf->multicell(0,4,'Munic�pio : '.$z01_munic.' - '.$z01_uf,0,"L",0);
   $pdf->ln(2);
   $pdf->multicell(0,0.5,'',1,"C",0);
   $pdf->ln(5);

   $clsqlinscricoes = new cl_issbase;
   $sql = $clsqlinscricoes->sqlinscricoes_nome($numcgm);
   $result = pg_exec($sql);
  
   $pdf->SetFont('arial','B',10);
   $pdf->multicell(0,6,'Dados das Inscri��es',0,"C",0);
   $pdf->SetFont('arial','B',8);
   $pdf->ln(5);
   $pdf->cell(15,4,'Inscri��es',1,0,"C",0);
   $pdf->cell(20,4,'Tipo',1,0,"C",0);
   $pdf->cell(60,4,'Nome Fantasia',1,0,"C",0);
   $pdf->cell(60,4,'Nome/Raz�o Social',1,0,"C",0);
   $pdf->cell(20,4,'Inicio',1,0,"C",0);
   $pdf->cell(20,4,'Baixa',1,1,"C",0);
   
   
   for($i=0;$i<pg_numrows($result);$i++){
      db_fieldsmemory($result,$i,true);
      if ($pdf->gety() > ( $pdf->h - 30 ) ){
	     $pdf->addpage('L');
         $pdf->SetFont('arial','B',8);
         $pdf->cell(15,4,'Inscri��es',1,0,"C",0);
         $pdf->cell(20,4,'Tipo',1,0,"C",0);
         $pdf->cell(60,4,'Nome Fantasia',1,0,"C",0);
         $pdf->cell(60,4,'Nome/Raz�o Social',1,0,"C",0);
         $pdf->cell(20,4,'Inicio',1,0,"C",0);
         $pdf->cell(20,4,'Baixa',1,1,"C",0);
      }	 
      
      $pdf->SetFont('arial','',8);
      $pdf->cell(15,4,$q02_inscr,1,0,"C",0);
      $pdf->cell(20,4,$proprietario,1,0,"C",0);
      $pdf->cell(60,4,$z01_nomefanta,1,0,"L",0);
      $pdf->cell(60,4,$z01_nome,1,0,"L",0);
      $pdf->cell(20,4,$q02_dtinic,1,0,"C",0);

      $pdf->cell(20,4,$q02_dtbaix,1,1,"C",0);
      $total += 1;
   }
}
$pdf->ln(5);
$pdf->cell(50,4,'Total de Registros  : '.$total,0,1,"L",0);
$pdf->Output();
?>