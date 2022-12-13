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
include("classes/db_cgm_classe.php");
include("classes/db_db_config_classe.php");
include("classes/db_rhvisavale_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhvisavalecad_classe.php");
include("libs/db_libgertxtfolha.php");
$clcgm = new cl_cgm;
$cldb_config  = new cl_db_config;
$clrhvisavale = new cl_rhvisavale;
$clrhpessoal = new cl_rhpessoal;
$clrhvisavalecad = new cl_rhvisavalecad;
$cllayoutVISA = new cl_layout_VISA;

$clcgm->rotulo->label();
$cldb_config->rotulo->label();
$clrhvisavale->rotulo->label();
$clrhpessoal->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('r14_valor');

db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_POST_VARS,2);exit;
$db_opcao = 1;
$db_botao = true;
if(isset($gerarq)){
	$sqlerro = false;

  $result_dadosvisavale = $clrhvisavale->sql_record($clrhvisavale->sql_query_valcgm(db_getsession("DB_instit"),"rh47_rubric as rubrica,rh47_contrato as contrato,rh47_tipovale as visavale,rh48_numcgm as inter","rh48_ordem limit 10"));
  for($i=0; $i<$clrhvisavale->numrows; $i++){
  	db_fieldsmemory($result_dadosvisavale, $i);
  	$interloc  = "inter".($i+1);
  	$$interloc = $inter;
  }

	$result_instituicao = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"),"*"));
	if($cldb_config->numrows > 0){
		db_fieldsmemory($result_instituicao, 0);

    $nomearquivo_impressao = "VisaVale_".db_formatar($pedido_dia,"s","0",2,"e",0).
					                               db_formatar($pedido_mes,"s","0",2,"e",0).
					                               db_formatar($pedido_ano,"s","0",4,"e",0).
					                               "_".
					                               db_formatar($efetiv_dia,"s","0",2,"e",0).
					                               db_formatar($efetiv_mes,"s","0",2,"e",0).
					                               db_formatar($efetiv_ano,"s","0",4,"e",0).".pdf";

    $nomearquivo = "VisaVale_".db_formatar($pedido_dia,"s","0",2,"e",0).
                               db_formatar($pedido_mes,"s","0",2,"e",0).
                               db_formatar($pedido_ano,"s","0",4,"e",0).
                               "_".
                               db_formatar($efetiv_dia,"s","0",2,"e",0).
                               db_formatar($efetiv_mes,"s","0",2,"e",0).
                               db_formatar($efetiv_ano,"s","0",4,"e",0).".txt";
    $cllayoutVISA->nomearq  = "tmp/$nomearquivo";
    if(!is_writable("tmp/")){
      $sqlerro= true;
      $erro_msg = 'Sem permissão de gravar o arquivo. Contate suporte.';
    }

	  /*
	    CABEÇALHO ARQUIVO - HEADER ARQUIVO
	  */
	  $DATA_PEDIDO = db_formatar($pedido_ano,"s","0",4,"e",0)."-".db_formatar($pedido_mes,"s","0",2,"e",0)."-".db_formatar($pedido_dia,"s","0",2,"e",0);
	  $DATA_EFETIV = db_formatar($efetiv_ano,"s","0",4,"e",0)."-".db_formatar($efetiv_mes,"s","0",2,"e",0)."-".db_formatar($efetiv_dia,"s","0",2,"e",0);
	  $ANME_COMPET = db_formatar(db_mesfolha(),"s","0",2,"e",0)."/".db_formatar(db_anofolha(),"s","0",4,"e",0);
	  $cllayoutVISA->VVheaderA_002_009 = $DATA_PEDIDO;
	  $cllayoutVISA->VVheaderA_014_048 = $nomeinst;
	  $cllayoutVISA->VVheaderA_049_062 = $cgc;
	  $cllayoutVISA->VVheaderA_074_084 = $contrato;  // Pegar
	  $cllayoutVISA->VVheaderA_091_098 = $DATA_EFETIV;
	  $cllayoutVISA->VVheaderA_099_099 = $visavale;
	  $cllayoutVISA->VVheaderA_100_100 = $pedido;
	  $cllayoutVISA->VVheaderA_101_106 = $ANME_COMPET;
	  $cllayoutVISA->VVheaderA_125_127 = null;
	  $cllayoutVISA->VVheaderA_128_394 = null;
	  $cllayoutVISA->VVheaderA_395_400 = null;
	  $cllayoutVISA->VVheaderA_401_450 = null;
	  $cllayoutVISA->geraHEADERArqVV();
	  /*
	    GERA CABEÇALHO DE ARQUIVO
	  */

    $dddinter  = "";
    $nominter1 = "";
    $endinter1 = "";
    $foneiter1 = "";
    $nominter2 = "";
    $endinter2 = "";
    $foneiter2 = "";
    $nominter3 = "";
    $endinter3 = "";
    $foneiter3 = "";

	  /*
	    INICIA IMPRESSÃO DO RELATÓRIO
	  */

    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->setfillcolor(235);
    $alt = 4;

    $head2 = "ARQUIVO VALE ALIMENTAÇÃO (".$visavale." - ".($visavale==1?"Alimentação":"Refeição")." - Contrato: ".$contrato.")";
    $head3 = "INSTITUIÇÃO :  ".db_getsession("DB_instit")." - ".$nomeinst;
    $head5 = "DATA DO PEDIDO :  ".db_formatar($DATA_PEDIDO,"d");
    $head6 = "DATA EFETIVAÇÃO :  ".db_formatar($DATA_EFETIV,"d");
    $head7 = "ANO / MÊS COMPETÊNCIA:  ".$ANME_COMPET;

    $pdf->addpage("L");

    $pdf->setfont('arial','b',7);
    $pdf->cell(0,$alt,"Interlocutores",1,1,"C",1);
    $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
    $pdf->cell(80,$alt,$RLz01_nome,1,0,"C",1);
    $pdf->cell(15,$alt,$RLz01_telef,1,0,"C",1);
    $pdf->cell(0,$alt,$RLz01_ender,1,1,"C",1);

    $pdf->setfont('arial','',7);
    if(isset($inter1) && trim($inter1) != ""){
    	$result_inter1 = $clcgm->sql_record($clcgm->sql_query_file($inter1,"substr(z01_telef,1,2) as dddinter1, z01_nome as nominter1, z01_ender as endinter1, substr(z01_telef,3,10) as foninter1"));
    	if($clcgm->numrows > 0){
    		db_fieldsmemory($result_inter1, 0);
		    $pdf->cell(15,$alt,$inter1,1,0,"C",0);
		    $pdf->cell(80,$alt,$nominter1,1,0,"L",0);
		    $pdf->cell(15,$alt,$dddinter1.$foninter1,1,0,"C",0);
		    $pdf->cell(0,$alt,$endinter1,1,1,"L",0);
    	}
    }
    if(isset($inter2) && trim($inter2) != ""){
    	$result_inter2 = $clcgm->sql_record($clcgm->sql_query_file($inter2,"substr(z01_telef,1,2) as dddinter2, z01_nome as nominter2, z01_ender as endinter2, substr(z01_telef,3,10) as foninter2"));
    	if($clcgm->numrows > 0){
    		db_fieldsmemory($result_inter2, 0);
		    $pdf->cell(15,$alt,$inter2,1,0,"C",0);
		    $pdf->cell(80,$alt,$nominter2,1,0,"L",0);
		    $pdf->cell(15,$alt,$dddinter2.$foninter2,1,0,"C",0);
		    $pdf->cell(0,$alt,$endinter2,1,1,"L",0);
    	}
    }
    if(isset($inter3) && trim($inter3) != ""){
    	$result_inter3 = $clcgm->sql_record($clcgm->sql_query_file($inter3,"substr(z01_telef,1,2) as dddinter3, z01_nome as nominter3, z01_ender as endinter3, substr(z01_telef,3,10) as foninter3"));
    	if($clcgm->numrows > 0){
    		db_fieldsmemory($result_inter3, 0);
		    $pdf->cell(15,$alt,$inter3,1,0,"C",0);
		    $pdf->cell(80,$alt,$nominter3,1,0,"L",0);
		    $pdf->cell(15,$alt,$dddinter3.$foninter3,1,0,"C",0);
		    $pdf->cell(0,$alt,$endinter3,1,1,"L",0);
    	}
    }

    if(isset($dddinter1) && trim($dddinter1) != ""){
    	$dddinter = $dddinter1;
    }else if(isset($dddinter2) && trim($dddinter2) != ""){
    	$dddinter = $dddinter2;
    }else if(isset($dddinter3) && trim($dddinter3) != ""){
    	$dddinter = $dddinter3;
    }

		/*
		    REGISTRO FILIAL OU POSTO DE PESSOA JURÍDICA
		*/
    $cllayoutVISA->VVregistFL_002_009 = $cgc;
    $cllayoutVISA->VVregistFL_016_025 = null;
    $cllayoutVISA->VVregistFL_026_060 = $nomeinst;
    $cllayoutVISA->VVregistFL_061_064 = $dddinter;
    $cllayoutVISA->VVregistFL_065_099 = $nominter1;
    $cllayoutVISA->VVregistFL_100_139 = $endinter1;
    $cllayoutVISA->VVregistFL_140_151 = $foneiter1;
    $cllayoutVISA->VVregistFL_158_192 = $nominter2;
    $cllayoutVISA->VVregistFL_193_232 = $endinter2;
    $cllayoutVISA->VVregistFL_233_244 = $foneiter2;
    $cllayoutVISA->VVregistFL_251_285 = $nominter3;
    $cllayoutVISA->VVregistFL_286_325 = $endinter3;
    $cllayoutVISA->VVregistFL_326_337 = $foneiter3;
    $cllayoutVISA->VVregistFL_344_363 = null;
    $cllayoutVISA->geraRegistVV();
    /*
        GERA REGISTRO
    */

    $qtdtotalreg = 0; // Quantidade de registros do 'tipo 5'
    $valtotalreg = 0; // Valor total, soma do valor de todos os registros do 'tipo 5'
    $seqtotalreg = 3; // Sequencial da linha do registro. Header de arquivo = 1 e 'Header de lote' = 2

//                                                die(
//                                                    $clrhvisavalecad->sql_query_documentos(
//                                                                                           null,
//                                                                                           "
//                                                                                            distinct rh49_valor as r14_valor, 
//                                                                                                     z01_ident,
//                                                                                                     z01_cgccpf,
//                                                                                                     z01_ender,
//                                                                                                     z01_compl,
//                                                                                                     z01_numero,
//                                                                                                     z01_cep,
//                                                                                                     z01_munic,
//                                                                                                     z01_bairro,
//                                                                                                     z01_uf,
//                                                                                                     z01_mae,
//                                                                                                     z01_telef,
//                                                                                                     z01_numcgm,
//                                                                                                     z01_nome,
//                                                                                                     rhpessoal.*,
//                                                                                                     rhpesdoc.*
//                                                                                           ",
//                                                                                           "z01_nome",
//                                                                                           "rh49_anousu = ".db_anofolha()." and rh49_mesusu = ".db_mesfolha()
//                                                                                          )
//                                                   );

    $result_dadosarq = $clrhvisavalecad->sql_record(
                                                    $clrhvisavalecad->sql_query_documentos(
                                                                                           null,
                                                                                           "
                                                                                            distinct rh49_valormes as r14_valor, 
                                                                                                     z01_ident,
                                                                                                     z01_cgccpf,
                                                                                                     z01_ender,
                                                                                                     z01_compl,
                                                                                                     z01_numero,
                                                                                                     z01_cep,
                                                                                                     z01_munic,
                                                                                                     z01_bairro,
                                                                                                     z01_uf,
                                                                                                     z01_mae,
                                                                                                     z01_telef,
                                                                                                     z01_numcgm,
                                                                                                     z01_nome,
                                                                                                     rhpessoal.*,
                                                                                                     rhpesdoc.*
                                                                                           ",
                                                                                           "z01_nome",
                                                                                           "rh49_anousu = ".db_anofolha()." and rh49_mesusu = ".db_mesfolha(). "and rh49_valor $lstzerado 0 "
                                                                                          )
                                                   );
    //$result_dadosarq = $clcgm->sql_record($clgeradorsql->gerador_sql("r14",db_anofolha(),2,"",$rubrica,"z01_nome","rh05_recis is null"));
    /*
    $result_dadosarq = $clcgm->sql_record( "select distinct 0 as r14_valor, z01_ident, z01_cgccpf, z01_ender,
                                                            z01_compl, z01_numero, z01_cep, z01_munic, z01_bairro, z01_uf, z01_mae, z01_telef,
                                                            z01_numcgm, z01_nome
					from rhpessoalmov
					     inner join cgm       on cgm.z01_numcgm        = rhpessoal.rh01_numcgm
					     inner join rhpessoal on rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
					     inner join rhpesdoc  on rhpesdoc.rh16_regist  = rhpessoal.rh01_regist
					     left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
					where rhpessoalmov.rh02_anousu = 2006
					  and rhpessoalmov.rh02_mesusu = 03
					  and trim(rh01_clas1) not in ('6','12','5','10','13','11')
					  and rh05_recis is null order by z01_nome");
    */
    if($clrhvisavalecad->numrows > 0){
    	for($i=0; $i<$clrhvisavalecad->numrows; $i++){
    		db_fieldsmemory($result_dadosarq, $i);

    		if($rh01_estciv > 4){
    			$rh01_estciv = 5;
    		}
    		if($rh01_instru <= 5){
    			$rh01_instru = 1;
    		}else if($rh01_instru <= 7){
    			$rh01_instru = 2;
    		}else if($rh01_instru <= 9){
    			$rh01_instru = 3;
    		}else{
    			$rh01_instru = 4;
    		}
    		/*
    		    REGISTROS DE FUNCIONÁRIOS
    		*/
		    $cllayoutVISA->VVregistFC_002_012 = $r14_valor;
		    $cllayoutVISA->VVregistFC_014_026 = $z01_numcgm;
		    $cllayoutVISA->VVregistFC_081_088 = $rh01_nasc;
		    $cllayoutVISA->VVregistFC_089_099 = $z01_cgccpf;
		    $cllayoutVISA->VVregistFC_100_100 = "1";
		    $cllayoutVISA->VVregistFC_101_113 = $z01_ident;
		    $cllayoutVISA->VVregistFC_140_154 = $rh16_pis;
		    $cllayoutVISA->VVregistFC_155_155 = $rh01_sexo;
		    $cllayoutVISA->VVregistFC_156_156 = $rh01_estciv;
		    $cllayoutVISA->VVregistFC_157_191 = $z01_ender;
		    $cllayoutVISA->VVregistFC_192_201 = $z01_compl;
		    $cllayoutVISA->VVregistFC_202_206 = $z01_numero;
		    $cllayoutVISA->VVregistFC_207_214 = $z01_cep;
		    $cllayoutVISA->VVregistFC_215_242 = $z01_munic;
		    $cllayoutVISA->VVregistFC_243_272 = $z01_bairro;
		    $cllayoutVISA->VVregistFC_273_274 = $z01_uf;
		    $cllayoutVISA->VVregistFC_275_309 = $z01_mae;
		    $cllayoutVISA->VVregistFC_310_310 = "R";
		    $cllayoutVISA->VVregistFC_339_339 = $rh01_instru;
		    $cllayoutVISA->VVregistFC_340_347 = $rh01_admiss;
		    $cllayoutVISA->VVregistFC_349_388 = $z01_nome;
		    $cllayoutVISA->VVregistFC_395_400 = $seqtotalreg;
		    $cllayoutVISA->geraREGISTROSVV();
		    /*
		        GERA REGISTROS
		    */

        if($pdf->gety() > $pdf->h - 30 || $i == 0){
        	if($pdf->gety() > ($pdf->h - 30)){
			      $pdf->addpage("L");
        	}else{
        		$pdf->ln();
        	}
        	$pdf->setfont('arial','b',7);
			    $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
			    $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
			    $pdf->cell(80,$alt,$RLz01_nome,1,0,"C",1);
			    $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
			    $pdf->cell(20,$alt,$RLrh01_nasc,1,0,"C",1);
			    $pdf->cell(20,$alt,$RLrh01_admiss,1,0,"C",1);
			    $pdf->cell(80,$alt,$RLz01_ender,1,0,"C",1);
			    $pdf->cell(0,$alt,$RLr14_valor,1,1,"C",1);
        }

        $pdf->setfont('arial','',7);
		    $pdf->cell(15,$alt,$rh01_regist,1,0,"C",0);
		    $pdf->cell(15,$alt,$z01_numcgm,1,0,"C",0);
		    $pdf->cell(80,$alt,$z01_nome,1,0,"L",0);
		    $pdf->cell(20,$alt,db_formatar($z01_cgccpf,"cpf"),1,0,"C",0);
		    $pdf->cell(20,$alt,db_formatar($rh01_nasc,"d"),1,0,"C",0);
		    $pdf->cell(20,$alt,db_formatar($rh01_admiss,"d"),1,0,"C",0);
		    $pdf->cell(80,$alt,$z01_ender.", ".$z01_numero,1,0,"L",0);
		    $pdf->cell(0,$alt,db_formatar($r14_valor,"f"),1,1,"R",0);

		    $qtdtotalreg ++;
		    $valtotalreg += $r14_valor;
		    $seqtotalreg ++;
    	}

      /*
          INÍCIO TRAILLER DE ARQUIVO
      */
	    $cllayoutVISA->VVtraillerArq_002_007 = $qtdtotalreg;
	    $cllayoutVISA->VVtraillerArq_008_022 = $valtotalreg;
	    $cllayoutVISA->VVtraillerArq_395_400 = $seqtotalreg;
	    $cllayoutVISA->geraTRAILLERArq();
      /*
          GERA TRAILLER
      */

      $pdf->setfont('arial','b',7);
	    $pdf->cell(220,$alt,"TOTAL DE REGISTROS","LTB",0,"R",1);
	    $pdf->cell(30,$alt,$qtdtotalreg,"TB",0,"R",1);
	    $pdf->cell(0,$alt,db_formatar($valtotalreg,"f"),"RTB",1,"R",1);

      /*
          FECHA ARQUIVO
      */
	    $cllayoutVISA->gera();
	    /*
	        FIM
	    */

      $pdf->Output("/tmp/".$nomearquivo_impressao,false,true);
    }else{
    	$sqlerro = true;
    	$erro_msg = "Nenhum registro encontrado.";
    }

	}else{
		$sqlerro = true;
		$erro_msg = "Instituição não encontrada.";
	}

$sql= "

select z01_nome||';'||abrev||';'||rh01_numcgm||';'||max(rh02_lota)||';'||z01_cgccpf||';'||rh01_nasc||';'||translate(to_char(sum(val_atual),'99999999.99'),'.',',') as linhatxt
from
(
select z01_nome::char(40),
       substr(z01_nome,1,19)::char(19) as abrev,
       rh01_numcgm ,
       rh02_lota,
       z01_cgccpf,
       to_char(rh01_nasc,'dd/mm/YYYY') as rh01_nasc,
       rh49_valormes as val_atual
from (select rh01_regist,
             z01_nome,
             rh01_numcgm ,
             rh02_lota,
             z01_cgccpf,
             rh01_nasc,
             rh49_valormes,
             rh49_instit 
      from rhvisavalecad
           inner join rhpessoalmov on rh02_anousu = rh49_anousu
                                  and rh02_mesusu = rh49_mesusu
                                  and rh02_regist = rh49_regist
                                  and rh02_instit = 1
           inner join rhlota       on r70_codigo  = rh02_lota
                                  and r70_instit  = rh02_instit
           inner join rhpessoal    on rh01_regist = rh49_regist
           inner join cgm          on z01_numcgm = rh01_numcgm
      where rh49_anousu = ".db_anofolha()." and rh49_mesusu = ".db_mesfolha()." and rh49_valormes $lstzerado 0
     ) as x
order by z01_nome
) as y
group by z01_nome, abrev, rh01_numcgm,  z01_cgccpf, rh01_nasc
order by z01_nome 
";
$result=pg_query($sql);
$linhas=pg_num_rows($result);

$nomearquivo_csv = "VisaVale_".db_formatar($pedido_dia,"s","0",2,"e",0).
                               db_formatar($pedido_mes,"s","0",2,"e",0).
                               db_formatar($pedido_ano,"s","0",4,"e",0).
                               "_".
                               db_formatar($efetiv_dia,"s","0",2,"e",0).
                               db_formatar($efetiv_mes,"s","0",2,"e",0).
                               db_formatar($efetiv_ano,"s","0",4,"e",0).".csv";




$fd=fopen("tmp/$nomearquivo_csv","wr");

for ($j=0;$j<$linhas;$j++){
$res=pg_result($result,$j,0);

fputs($fd,$res."\n");

}
fclose($fd);



}
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
<center>
<?
include("forms/db_frmarqvisa.php");
?>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($gerarq)){
	if($sqlerro == true){
		db_msgbox($erro_msg);
	}else{
    echo "
    <script>
/*
      function js_emitir(){
        js_arquivo_abrir('tmp/$nomearquivo');
        //js_OpenJanelaIframe('top.corpo','db_iframe_download','db_download.php?arquivo=tmp/$nomearquivo','Download de arquivos',false);
      }   
      js_emitir();
*/


    function js_detectaarquivo(arquivo,pdf,arquivocsv){
		  listagem = pdf+'#Download relatório|';
      listagem+= arquivocsv+'#Download arquivo CSV ';

		  js_montarlista(listagem,'form1');
    }
    js_detectaarquivo('tmp/".$nomearquivo."','/tmp/".$nomearquivo_impressao."','tmp/".$nomearquivo_csv."');
    </script>
    ";
	}
}
?>