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
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$e_linux = strpos(strtolower($HTTP_USER_AGENT),'linux') ;

if($e_linux > 0){
  $troca_linha = "\n";
}else{
  $troca_linha = "\r\n";
}


global $cfpess,$subpes;

$subpes = db_anofolha().'/'.db_mesfolha();
//$fopag_geracao = db_ctod($fopag_geracao);
db_selectmax("cfpess"," select * from cfpess ".bb_condicaosubpes("r11_")); 

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br><br>
<center>
</center>
</body>
<?

global $db_config;
db_selectmax("db_config","select cgc,email, db21_codcli , cgc from db_config where codigo = ".db_getsession("DB_instit"));

global $d08_cgc,$d08_email; 
global $fopag_geracao,
       $fopag_convenio,
       $fopag_sequen,
       $subpes;

$d08_cgc    = $db_config[0]["cgc"];
$d08_email  = $db_config[0]["email"];


$db_erro = false;
$sqlerro = false;
$nomearquivotxt = "/tmp/fpsf950.txt"; 
$nomearquivopdf = "/tmp/fpsf950.pdf"; 
gera_pasep_FPSF950($nomearquivotxt,$nomearquivopdf,$rh27_rubric);

//exit;
if($sqlerro == false){
  echo "
  <script>
    parent.js_detectaarquivo('$nomearquivotxt','$nomearquivopdf');
  </script>
  ";
}else{
  echo "
  <script>
    parent.js_erro('$erro_msg');
  </script>
  ";
}
//echo "<BR> antes do fim db_fim_transacao()";
//flush();
db_fim_transacao();
//flush();
db_redireciona("pes4_rhfopag95001.php");

function gera_pasep_FPSF950($nomearquivotxt,$nomearquivopdf,$rh27_rubric){

global $d08_cgc,$d08_email; 
global $transacao,$rhfopag;
global $fopag_geracao,
       $fopag_convenio,
       $fopag_sequen,$head2, $head4,
       $troca_linha,
       $subpes;
       
       $arquivo = fopen($nomearquivotxt,"w");


      //  ********************************
      //  * PARTICIPANTE
      //  ********************************
      $total_registros = 0;
      $Ipes = 0; 
      $sql =  "select rhfopag.*,
                                      rh02_regist,
                                      z01_nome
                               from rhfopag inner join rhpessoalmov on rh02_anousu = ".db_anofolha()."
                                                                   and rh02_mesusu = ".db_mesfolha()."
                                                                   and rh02_instit = ".db_getsession("DB_instit")."
                                                                   and rh02_regist = rh66_regist
                                            inner join rhpessoal on rh01_regist = rh02_regist
                                            inner join cgm       on z01_numcgm  = rh01_numcgm
                               where rh66_valor > 0
                               order by rh66_pis";
      db_selectmax("rhfopag", $sql);
//      db_criatabela(pg_query($sql));exit;

      // **********************************
      // * HEADER
      // **********************************
      //echo $fopag_geracao."   ".  ;exit;
      $lin  =  "1";                                                  // FIXO
      $lin .=  "FPSF950";                                            // FIXO
      $lin .=  db_strtran( $fopag_geracao , "-", "" );     // DATA DA GERACAO
      $lin .=  $d08_cgc;			                                       //- CGC DA ENTIDADE
      $lin .=  db_str($fopag_convenio,6,0,"0");
      $lin .=  db_str($fopag_sequen,6,0,"0");                        //- SEQUENCIA DO ARQUIVO
      fputs($arquivo,$lin.$troca_linha);

      // Gera o Relatório 

      $head2 = 'RELATÓRIO FPSF950.TXT';
      $head4 = 'LISTA DOS NÃO PAGOS';
      $pdf = new PDF(); 
      $pdf->Open(); 
      $pdf->AliasNbPages(); 

      $total = 0;
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',8);
      $troca = 1;
      $alt = 4;
      $total = 0;
      $func  = 0;

      db_criatermometro('calculo_folha','Concluido...','blue',1,'Lendo os não Pagos (PASEP)');
      for($Ipes=0;$Ipes < count($rhfopag);$Ipes++){
          db_atutermometro($Ipes,count($rhfopag),'calculo_folha',1);
          if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
             $pdf->addpage();
             $pdf->setfont('arial','b',8);
             $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
             $pdf->cell(70,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
             $pdf->cell(20,$alt,'PASEP',1,0,"C",1);
             $pdf->cell(25,$alt,'VALOR',1,1,"C",1);
             $troca = 0;
          }
              $condicaoaux  = " and r14_rubric = ".db_sqlformat($rh27_rubric)." and r14_regist = ".db_sqlformat( $rhfopag[$Ipes]["rh66_regist"] );
              $condicaoaux1 = " and r48_rubric = ".db_sqlformat($rh27_rubric)." and r48_regist = ".db_sqlformat( $rhfopag[$Ipes]["rh66_regist"] );
              $condicaoaux2 = " and r20_rubric = ".db_sqlformat($rh27_rubric)." and r20_regist = ".db_sqlformat( $rhfopag[$Ipes]["rh66_regist"] );
              if(  db_selectmax( "transacao", "select r14_regist from gerfsal ".bb_condicaosubpes("r14_"). $condicaoaux ) ){
                   continue;
              }elseif( db_selectmax( "transacao", "select r48_regist from gerfcom ".bb_condicaosubpes("r48_"). $condicaoaux1)){
                   continue;
              }elseif( db_selectmax( "transacao", "select r20_regist from gerfres ".bb_condicaosubpes("r20_"). $condicaoaux2)){
                   continue;
              }
              $total_registros += 1;
              $lin  =  "2";
              $lin .=  str_pad($rhfopag[$Ipes]["rh66_pis"],11);
              $lin .=  db_str($rhfopag[$Ipes]["rh66_regist"],15,0,"0");
              $lin .=  bb_space(15);
              fputs($arquivo,$lin.$troca_linha);

              $pdf->setfont('arial','',7);
              $pdf->cell(15,$alt,$rhfopag[$Ipes]["rh02_regist"],0,0,"C",0);
              $pdf->cell(70,$alt,$rhfopag[$Ipes]["z01_nome"],0,0,"L",0);
              $pdf->cell(20,$alt,$rhfopag[$Ipes]["rh66_pis"],0,0,"L",0);
              $pdf->cell(25,$alt,db_formatar($rhfopag[$Ipes]["rh66_valor"],'f'),0,1,"R",0);
              $total += $rhfopag[$Ipes]["rh66_valor"];
              $func  += 1;

      }
      $pdf->setfont('arial','b',8);
      $pdf->cell(105,$alt,'TOTAL NÃO PAGOS  :  '.$func.'  FUNCIONÁRIOS',"T",0,"C",0);
      $pdf->cell(25,$alt,db_formatar($total,'f'),"T",1,"R",0);
      $pdf->Output($nomearquivopdf,false,true);

      $lin  = "9";
      $lin .=  db_str($total_registros,6,0,"0");
      $lin .=  bb_space(35);
      fputs($arquivo,$lin.$troca_linha);
      fclose($arquivo);
      
}

?>