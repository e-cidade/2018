<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("libs/db_liborcamento.php");
include("fpdf151/assinatura.php");
include("classes/db_orcparamrel_classe.php");
include("libs/db_libcontabilidade.php");
include("libs/db_libtxt.php");
include("dbforms/db_funcoes.php");
include("classes/db_conrelinfo_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$classinatura = new cl_assinatura;
$orcparamrel = new cl_orcparamrel;
$clconrelinfo = new cl_conrelinfo;

$anousu = db_getsession("DB_anousu");
$dt = datas_bimestre($bimestre,$anousu); // no dbforms/db_funcoes.php
$dt_ini= $dt[0]; // data inicial do período
$dt_fin= $dt[1]; // data final do período

$receitas              = $orcparamrel->sql_parametro('12','0'); // grupo das receitas, não usado inicialmente
$desp_ef               = $orcparamrel->sql_parametro('12','1'); // despesas com ensino funcamental
$desp_pgt              = $orcparamrel->sql_parametro('12','4'); // pgto de profissionais do magistério
$desp_pgto             = $orcparamrel->sql_parametro('12','5'); // Demais de profissionais do magistério
$desp_social           = $orcparamrel->sql_parametro('12','6'); 
$desp_credito          = $orcparamrel->sql_parametro('12','7');
$desp_edu              = $orcparamrel->sql_parametro('12','8'); 
$desp_parcela          = $orcparamrel->sql_parametro('12','9'); //
$desp_fundamental      = $orcparamrel->sql_parametro('12','10'); //
$desp_infantil         = $orcparamrel->sql_parametro('12','11'); //
$desp_superavit        = $orcparamrel->sql_parametro('12','12'); //
$rp_mde                = $orcparamrel->sql_parametro('12','13'); // rp despesas com mde
$rp_fundef             = $orcparamrel->sql_parametro('12','14'); //
$rp_conpensacao        = $orcparamrel->sql_parametro('12','15'); // titulo não usada
$rp_manutenc           = $orcparamrel->sql_parametro('12','16'); // manutenção e desenvolvimento do ensino
$rp_ensino             = $orcparamrel->sql_parametro('12','17'); // ensino fundamental
$saldo_fundef          = $orcparamrel->sql_parametro('12','18'); // ensino fundamental
$sem_uso               = $orcparamrel->sql_parametro('12','19'); // ensino fundamental
// receitas
$rec_impostos          = $orcparamrel->sql_parametro('12','20'); // 
$rec_ativa             = $orcparamrel->sql_parametro('12','21'); // 
$rec_multas            = $orcparamrel->sql_parametro('12','22'); // 
$rec_cota_fpm          = $orcparamrel->sql_parametro('12','23'); // 
$rec_cota_icms         = $orcparamrel->sql_parametro('12','24'); // 
$rec_parte_icms        = $orcparamrel->sql_parametro('12','25'); // 
$rec_parte_ipi         = $orcparamrel->sql_parametro('12','26'); // 
$rec_parcela           = $orcparamrel->sql_parametro('12','27'); // 
$rec_itr               = $orcparamrel->sql_parametro('12','28'); // 
$rec_ouro              = $orcparamrel->sql_parametro('12','29'); // 
$rec_ipva              = $orcparamrel->sql_parametro('12','30'); //
$rec_transf_fundef     = $orcparamrel->sql_parametro('12','31'); // 
$rec_transf_recurso    = $orcparamrel->sql_parametro('12','32'); // transferencias de recursos do FUNDEF
$rec_complementacao    = $orcparamrel->sql_parametro('12','33'); //
$rec_salario           = $orcparamrel->sql_parametro('12','34'); // cota-parte contribuição social do salário-Educação
$rec_fnde              = $orcparamrel->sql_parametro('12','35'); //
$rec_programa_educacao = $orcparamrel->sql_parametro('12','36'); //
$rec_credito           = $orcparamrel->sql_parametro('12','37'); //
$rec_outras            = $orcparamrel->sql_parametro('12','38'); //

// variavaveis
$GANHO_COMPLEM_FUNDEF  = 0;
$DESP_ENS_FUNDAMENTAL  = 0; // Despesa com ensino fundamental
$DESP_ENS_INFANTIL     = 0;
$DESP_VINC_SUPERAVIT   = 0;
$COMPENSACAO_RP_MDE    = 0; // conpensação rp do mde
$COMPENSACAO_RP_FUNDEF = 0; // compensação rp ensino fundamental
$RP_MDE_MINIMA  = 0;
$RP_MDE_APURADA = 0;
$RP_MDE_INSCRITO= 0;
$RP_FUNDEF_MINIMA  = 0;
$RP_FUNDEF_APURADA = 0;
$RP_FUNDEF_INSCRITO= 0; 
$res = $clconrelinfo->sql_record(
       $clconrelinfo->sql_query_valores(12,str_replace('-',',',$db_selinstit)));
       
if ($clconrelinfo->numrows > 0 ){
  for ($x=0;$x < $clconrelinfo->numrows;$x++){
     db_fieldsmemory($res,$x);
     if ($c83_codigo ==1 ){
        $GANHO_COMPLEM_FUNDEF  = $c83_informacao;
     } else if ($c83_codigo ==2 ){
        $DESP_ENS_FUNDAMENTAL  = $c83_informacao;
     } else if ($c83_codigo ==3 ){
        $DESP_ENS_INFANTIL  = $c83_informacao;
     } else if ($c83_codigo ==4 ){
        $DESP_VINC_SUPERAVIT  = $c83_informacao;
     } else if ($c83_codigo ==5 ){
        $COMPENSACAO_RP_MDE  = $c83_informacao;
     } else if ($c83_codigo ==6 ){
        $COMPENSACAO_RP_FUNDEF  = $c83_informacao;
     } else if ($c83_codigo ==251 ){
        $RP_MDE_MINIMA   = $c83_informacao;   
     } else if ($c83_codigo ==252 ){
        $RP_MDE_APURADA  = $c83_informacao;
     } else if ($c83_codigo ==253 ){
        $RP_MDE_INSCRITO  = $c83_informacao;
     } else if ($c83_codigo ==254 ){
        $RP_FUNDEF_MINIMA  = $c83_informacao;   
     } else if ($c83_codigo ==255 ){
        $RP_FUNDEF_APURADA  = $c83_informacao;
     } else if ($c83_codigo ==256 ){
        $RP_FUNDEF_INSCRITO = $c83_informacao;
     }      
  }
}  
//-------------------------------------------------------------------------------------------------
// totalizadores dos relatorios
$somador_I_inicial = 0;
$somador_I_atualizada  = 0;
$somador_I_nobimestre  = 0;
$somador_I_atebimestre = 0;
$somador_II_inicial = 0;
$somador_II_atualizada  = 0;
$somador_II_nobimestre  = 0;
$somador_II_atebimestre = 0;
$somador_III_inicial = 0;
$somador_III_atualizada  = 0;
$somador_III_nobimestre  = 0;
$somador_III_atebimestre = 0;
$somador_IV_inicial = 0;
$somador_IV_atualizada  = 0;
$somador_IV_nobimestre  = 0;
$somador_IV_atebimestre = 0;
$somador_V_inicial = 0;
$somador_V_atualizada  = 0;
$somador_V_nobimestre  = 0;
$somador_V_atebimestre = 0;
$somador_VI_inicial = 0;    // total das receitas
$somador_VI_atualizada  = 0;
$somador_VI_nobimestre  = 0;
$somador_VI_atebimestre = 0;
//--------------------------------------------------------------------------------------------------
// RecordSets

$db_filtro = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
$result = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);

$sql_dotacao = db_dotacaosaldo(8,1,4,true,' o58_instit in ('.str_replace('-',', ',$db_selinstit).') ',$anousu,$dt_ini,$dt_fin,'8','0',true);
$sql = " select 
             (o58_elemento||'00') as o58_elemento,
	         o56_descr,
	         dot_ini,
	         atual,
	         suplementado_acumulado,
	         reduzido_acumulado,
	      	 liquidado,
	      	 liquidado_acumulado,
	      	 conplano.*,
	      	 o58_codigo as recurso,
	      	 o58_codigo,
	      	 o58_funcao,
	      	 o58_subfuncao,
	      	 o53_descr
         from ($sql_dotacao) as x
	    inner join conplano on c60_anousu = $anousu and substr(conplano.c60_estrut,1,13)=x.o58_elemento
	";
 $result_desp = pg_query($sql);
 
 $result_subfunc = db_dotacaosaldo(4,3,2,true,"o58_codigo=20 and o58_instit in (".str_replace('-',', ',$db_selinstit)." ) ",$anousu,$dt_ini,$dt_fin);

 
 $result_bal = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,' c61_instit in ('.str_replace('-',', ',$db_selinstit)   .' ) ');
 //db_criatabela($result_bal);
 //exit;
 // nao e usada 

 ////////
 // recorset usados para controle de rp inscritos em exec.anteriores vinculados a educação
 // o unico valor usado é o valor cancelado em exercicio atual
 $db_filtro = ' in ('.str_replace('-',', ',$db_selinstit).')';
 $result_rp_mde = db_rpsaldo($anousu,
                             $db_filtro,
			     $anousu.'-01-01',
			     $dt_fin,
                             " o58_codigo=20 and vlranu > 0 "); 
 $result_rp_fundef = db_rpsaldo($anousu,
                                $db_filtro,
				$anousu.'-01-01',
				$dt_fin,
				" o58_codigo=20 and o58_subfuncao = 361 and vlranu > 0 ");

 // db_criatabela($result_rp_mde);
 // db_criatabela($result_rp_fundef);
 // exit;
$INTERFERENCIA_MDE = 0;
$INTERFERENCIA_FUNDEF= 0;
$INTERFERENCIA_FUNDEF_DEMAIS = 0;
@pg_exec("drop table work_pl");
$result_bal_mde = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,' c61_instit in ('.str_replace('-',', ',$db_selinstit)   .' ) ');
for($i=0;$i<pg_numrows($result_bal_mde);$i++){
  db_fieldsmemory($result_bal_mde,$i);  
  if (in_array($estrutural,$desp_ef)){     
     $INTERFERENCIA_MDE += $saldo_final ;
  }
  if (in_array($estrutural,$desp_pgt)){ 
  	 $INTERFERENCIA_FUNDEF += $saldo_final ;     
  }
  if (in_array($estrutural,$desp_pgto)){ 
  	 $INTERFERENCIA_FUNDEF_DEMAIS += $saldo_final ;     
  } 
}
@pg_exec("drop table work_pl");
$data_inicial = $anousu."-01-01";
$result_bal_acumulado = db_planocontassaldo_matriz($anousu,$data_inicial,$dt_fin,false,' c61_instit in ('.str_replace('-',', ',$db_selinstit)   .' ) ');
$INTERFERENCIA_MDE_AC = 0;
$INTERFERENCIA_FUNDEF_AC= 0;
$INTERFERENCIA_FUNDEF_DEMAIS_AC =0;
for($i=0;$i<pg_numrows($result_bal_acumulado);$i++){
  db_fieldsmemory($result_bal_acumulado,$i);  
  if (in_array($estrutural,$desp_ef)){     
     $INTERFERENCIA_MDE_AC += $saldo_final ;
  }
  if (in_array($estrutural,$desp_pgt)){ 
     $INTERFERENCIA_FUNDEF_AC += $saldo_final ;     
  } 
  if (in_array($estrutural,$desp_pgto)){ 
  	 $INTERFERENCIA_FUNDEF_DEMAIS_AC += $saldo_final;     
  } 
}


//--------------------------------------------------------------------------------------------------

$tipo_mesini = 1;
$tipo_mesfim = 1;
$perini = $dt_ini;
$perfin = $dt_fin;

$total_saldo_inicial             =0;
$total_saldo_prevadic_acum       =0;
$total_saldo_arrecadado          =0;
$total_saldo_arrecadado_acumulado=0;
$total_saldo_a_arrecadar        = 0;

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    if (strlen(trim($nomeinstabrev)) > 0){
         $descr_inst .= $xvirg.$nomeinstabrev;
         $flag_abrev  = true;
    }else{
         $descr_inst .= $xvirg.$nomeinst;
    }

    $xvirg = ', ';
}

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head1 = $descr_inst;
$head2 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$head3 = "DEMONSTRATIVO DE RECEITAS E DESPESAS COM DESENVOLVIMENTO E MANUTENÇÃO DO ENSINO -MDE";
$head4 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$txt = strtoupper(db_mes('01'));
$dt  = split("-",$dt_fin);
$txt.= " À ".strtoupper(db_mes($dt[1]))." $anousu/BIMESTRE ";;
$dt  = split("-",$dt_ini);
$txt.= strtoupper(db_mes($dt[1]))."-";
$dt  = split("-",$dt_fin);
$txt.= strtoupper(db_mes($dt[1]));
$head5 = "$txt";

///////////////////////////////////////
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$troca = 1;
$alt = 4;

$pagina = 1;
$tottotal = 0;
$pagina = 0;
$n1 =5;
$n2=10;

$pdf->addpage();
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,"Lei 9.394/96, Atr. 72 - Anexo X",0,0,"L",0);
$pdf->cell(100,$alt,"R$",0,1,"R",0);


$pdf->setfont('arial','',6);
$pdf->cell(90,($alt*2),"RECEITAS",'TBR',0,"L",0);
$pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
$pdf->cell(20,($alt*2),"ATUALIZADA(a)",1,0,"C",0);
$pdf->cell(60,($alt),"RECEITAS ATUALIZADAS",'TB',1,"C",0);  //br
$pdf->setX(140); 
$pdf->cell(20,$alt,"No Bimestre",1,0,"C",0);
$pdf->cell(20,$alt,"Até o Bimestre(b)",1,0,"C",0);
$pdf->cell(20,$alt,"% (b/a)",'TB',0,"C",0);
$pdf->ln();


//-----------------
 $pos_rec_impostos = $pdf->getY(); // guarda posição
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,"RECEITA RESULTANTE DE IMPOSTOS(I)",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'',0,0,"R",0);
$pdf->Ln();
//-----------------
  $pos_atu = $pdf->getY(); // guarda posição
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n1)."Receita de Impostos",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'',0,0,"R",0);
$pdf->Ln();
//-----------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_impostos)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Impostos",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_I_inicial     += $tot_rec_inicial;
  $somador_I_atualizada  += $tot_rec_atual;
  $somador_I_nobimestre  += $tot_rec_bim;
  $somador_I_atebimestre += $tot_rec_atebim;
//--------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_ativa)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Divida Ativa dos Impostos",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_I_inicial     += $tot_rec_inicial;
  $somador_I_atualizada  += $tot_rec_atual;
  $somador_I_nobimestre  += $tot_rec_bim;
  $somador_I_atebimestre += $tot_rec_atebim;

//--------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_multas)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Multas,Juros de Mora e outros Encargos de Impostos e da Dívida Ativa de Impostos",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_I_inicial     += $tot_rec_inicial;
  $somador_I_atualizada  += $tot_rec_atual;
  $somador_I_nobimestre  += $tot_rec_bim;
  $somador_I_atebimestre += $tot_rec_atebim;

  //--------------------
  $pos = $pdf->getY();
  $pdf->setY($pos_atu); // posição guardada lá em cima
  $pdf->setX(100);
  $pdf->cell(20,$alt,db_formatar($somador_I_inicial,'f'),'0',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador_I_atualizada,'f'),'0',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador_I_nobimestre,'f'),'0',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador_I_atebimestre,'f'),'0',0,"R",0);
  @$pdf->cell(20,$alt,db_formatar(($somador_I_atebimestre*100)/$somador_I_atualizada,'f'),'0',0,"R",0);

$pdf->setY($pos);



//--------------------
$pos_ant = $pdf->getY(); //guarda posição
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n1)."Receita de Transferências Constitucionais e Legais",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'',0,0,"R",0);
$pdf->Ln();
//-----------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_cota_fpm)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Cota-Parte FPM (85%)",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar(($tot_rec_inicial*0.85),'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_rec_atual*0.85),'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_rec_bim*0.85),'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_rec_atebim*0.85),'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim*0.85)*100/($tot_rec_atual*0.85)),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_I_inicial     += $tot_rec_inicial*0.85;
  $somador_I_atualizada  += $tot_rec_atual*0.85;
  $somador_I_nobimestre  += $tot_rec_bim*0.85;
  $somador_I_atebimestre += $tot_rec_atebim*0.85;
  $v_I_inicial     = $tot_rec_inicial*.85;
  $v_I_atualizada  = $tot_rec_atual*.85;
  $v_I_nobimestre  = $tot_rec_bim*.85;
  $v_I_atebimestre = $tot_rec_atebim*.85;

 
//--------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_cota_icms)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Transferencia Financeira ICMS-Desoneração - LC n 87/96 (85%)",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial*0.85,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual*0.85,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim*0.85,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim*0.85,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim*0.85)*100/($tot_rec_atual*0.85)),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_I_inicial     += ($tot_rec_inicial*0.85);
  $somador_I_atualizada  += ($tot_rec_atual*0.85);
  $somador_I_nobimestre  += ($tot_rec_bim*0.85);
  $somador_I_atebimestre += ($tot_rec_atebim*0.85);
  $v_I_inicial     += $tot_rec_inicial*.85;
  $v_I_atualizada  += $tot_rec_atual*.85;
  $v_I_nobimestre  += $tot_rec_bim*.85;
  $v_I_atebimestre += $tot_rec_atebim*.85;


//--------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_parte_icms)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Cota-Parte ICMS (85%)",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial*0.85,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual*0.85,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim*0.85,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim*0.85,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim*0.85)*100/($tot_rec_atual*0.85)),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_I_inicial     += $tot_rec_inicial*0.85;
  $somador_I_atualizada  += $tot_rec_atual*0.85;
  $somador_I_nobimestre  += $tot_rec_bim*0.85;
  $somador_I_atebimestre += $tot_rec_atebim*0.85;
  $v_I_inicial     += $tot_rec_inicial*.85;
  $v_I_atualizada  += $tot_rec_atual*.85;
  $v_I_nobimestre  += $tot_rec_bim*.85;
  $v_I_atebimestre += $tot_rec_atebim*.85;


//-------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_parte_ipi)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Cota-Parte IPI-Exportação (85%)",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial*0.85,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual*0.85,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim*0.85,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim*0.85,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim*0.85)*100/($tot_rec_atual*0.85)),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_I_inicial     += $tot_rec_inicial*0.85;
  $somador_I_atualizada  += $tot_rec_atual*0.85;
  $somador_I_nobimestre  += $tot_rec_bim*0.85;
  $somador_I_atebimestre += $tot_rec_atebim*0.85;
  $v_I_inicial     += $tot_rec_inicial*.85;
  $v_I_atualizada  += $tot_rec_atual*.85;
  $v_I_nobimestre  += $tot_rec_bim*.85;
  $v_I_atebimestre += $tot_rec_atebim*.85;


//-------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_parcela)){ 
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Parcela das Transferências destinada à Formação do FUNDEF(II)",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_I_inicial            += $tot_rec_inicial;
  $somador_I_atualizada    += $tot_rec_atual;
  $somador_I_nobimestre  += $tot_rec_bim;
  $somador_I_atebimestre += $tot_rec_atebim;

  $somador_II_inicial           += $tot_rec_inicial;
  $somador_II_atualizada    += $tot_rec_atual;
  $somador_II_nobimestre  += $tot_rec_bim;
  $somador_II_atebimestre += $tot_rec_atebim;

  $v_I_inicial            += $tot_rec_inicial;
  $v_I_atualizada    += $tot_rec_atual;
  $v_I_nobimestre  += $tot_rec_bim;
  $v_I_atebimestre += $tot_rec_atebim;


//-------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_itr)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Cota-Parte ITR (100%)",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_I_inicial     += $tot_rec_inicial;
  $somador_I_atualizada  += $tot_rec_atual;
  $somador_I_nobimestre  += $tot_rec_bim;
  $somador_I_atebimestre += $tot_rec_atebim;

  $v_I_inicial     += $tot_rec_inicial;
  $v_I_atualizada  += $tot_rec_atual;
  $v_I_nobimestre  += $tot_rec_bim;
  $v_I_atebimestre += $tot_rec_atebim;


//-------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_ouro)){ 
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Cota-Parte IOF-Ouro (100%)",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_I_inicial     += $tot_rec_inicial;
  $somador_I_atualizada  += $tot_rec_atual;
  $somador_I_nobimestre  += $tot_rec_bim;
  $somador_I_atebimestre += $tot_rec_atebim;
  
  $v_I_inicial     += $tot_rec_inicial;
  $v_I_atualizada  += $tot_rec_atual;
  $v_I_nobimestre  += $tot_rec_bim;
  $v_I_atebimestre += $tot_rec_atebim;

//-------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_ipva)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Cota-Parte IPVA (100%)",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_I_inicial     += $tot_rec_inicial;
  $somador_I_atualizada  += $tot_rec_atual;
  $somador_I_nobimestre  += $tot_rec_bim;
  $somador_I_atebimestre += $tot_rec_atebim;

  $v_I_inicial     += $tot_rec_inicial;
  $v_I_atualizada  += $tot_rec_atual;
  $v_I_nobimestre  += $tot_rec_bim;
  $v_I_atebimestre += $tot_rec_atebim;


////// escreve total das transferencias lá em cima
 $pos = $pdf->getY();
 $pdf->setY($pos_ant);
 $pdf->setX(100);
 $pdf->cell(20,$alt,db_formatar($v_I_inicial,'f'),'0',0,"R",0);
 $pdf->cell(20,$alt,db_formatar($v_I_atualizada,'f'),'0',0,"R",0);
 $pdf->cell(20,$alt,db_formatar($v_I_nobimestre,'f'),'0',0,"R",0);
 $pdf->cell(20,$alt,db_formatar($v_I_atebimestre,'f'),'0',0,"R",0);
 @$pdf->cell(20,$alt,db_formatar(($v_I_atebimestre*100)/$v_I_atualizada,'f'),'0',0,"R",0);

 $pdf->setY($pos);

//-------------------
  $pos_rec_vinculado = $pdf->getY(); // guarda posição
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,"RECEITAS VINCULADAS AO ENSINO(III)",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'',0,0,"R",0);
$pdf->Ln();
//-------------------
// é soma das duas abaixo
 $pos_ant = $pdf->getY(); //guarda posição

$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n1)."Trasferencias Multigovernamentais do FUNDEF (IV)",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
@$pdf->cell(20,$alt,'',0,0,"R",0);
$pdf->Ln();
//

//-------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_transf_recurso)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Trasferencias de Recursos do FUNDEF (V)",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_III_inicial     += $tot_rec_inicial;
  $somador_III_atualizada  += $tot_rec_atual;
  $somador_III_nobimestre  += $tot_rec_bim;
  $somador_III_atebimestre += $tot_rec_atebim;
  //
  $somador_IV_inicial     += $tot_rec_inicial;
  $somador_IV_atualizada  += $tot_rec_atual;
  $somador_IV_nobimestre  += $tot_rec_bim;
  $somador_IV_atebimestre += $tot_rec_atebim;
  //
  $somador_V_inicial     += $tot_rec_inicial;
  $somador_V_atualizada  += $tot_rec_atual;
  $somador_V_nobimestre  += $tot_rec_bim;
  $somador_V_atebimestre += $tot_rec_atebim;


//-------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_complementacao)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n2)."Complementação da União so FUNDEF",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar(($tot_rec_atebim*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_III_inicial     += $tot_rec_inicial;
  $somador_III_atualizada  += $tot_rec_atual;
  $somador_III_nobimestre  += $tot_rec_bim;
  $somador_III_atebimestre += $tot_rec_atebim;
  //
  $somador_IV_inicial     += $tot_rec_inicial;
  $somador_IV_atualizada  += $tot_rec_atual;
  $somador_IV_nobimestre  += $tot_rec_bim;
  $somador_IV_atebimestre += $tot_rec_atebim;


//// imprime total acima
 $pos = $pdf->getY();
 $pdf->setY($pos_ant);
 $pdf->setX(100);
 $pdf->cell(20,$alt,db_formatar($somador_IV_inicial,'f'),'0',0,"R",0);
 $pdf->cell(20,$alt,db_formatar($somador_IV_atualizada,'f'),'0',0,"R",0);
 $pdf->cell(20,$alt,db_formatar($somador_IV_nobimestre,'f'),'0',0,"R",0);
 $pdf->cell(20,$alt,db_formatar($somador_IV_atebimestre,'f'),'0',0,"R",0);
 @$pdf->cell(20,$alt,db_formatar(($somador_IV_atebimestre*100)/$somador_IV_atualizada,'f'),'0',0,"R",0);

 $pdf->setY($pos);


//-------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_salario)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n1)."Cota-Parte Contribuição Social do Salário-Educação",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_rec_atebim),'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_III_inicial     += $tot_rec_inicial;
  $somador_III_atualizada  += $tot_rec_atual;
  $somador_III_nobimestre  += $tot_rec_bim;
  $somador_III_atebimestre += ($tot_rec_atebim);

//-------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_fnde)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n1)."Transferências do FNDE",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_rec_atebim),'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_III_inicial     += $tot_rec_inicial;
  $somador_III_atualizada  += $tot_rec_atual;
  $somador_III_nobimestre  += $tot_rec_bim;
  $somador_III_atebimestre += ($tot_rec_atebim);

//-------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_programa_educacao)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n1)."Transferências de Convênios destinadas a Programas de Educação",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_rec_atebim),'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_III_inicial     += $tot_rec_inicial;
  $somador_III_atualizada  += $tot_rec_atual;
  $somador_III_nobimestre  += $tot_rec_bim;
  $somador_III_atebimestre += ($tot_rec_atebim);

//-------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_credito)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n1)."Receitas de Operações de Crédito destinada à Educação",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar(($tot_rec_atebim),'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_III_inicial     += $tot_rec_inicial;
  $somador_III_atualizada  += $tot_rec_atual;
  $somador_III_nobimestre  += $tot_rec_bim;
  $somador_III_atebimestre += $tot_rec_atebim;

//-------------------
$tot_rec_inicial=0;
$tot_rec_atual=0;
$tot_rec_bim =0;
$tot_rec_atebim =0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$rec_outras)){ // despesas com ensino fundamental
    $tot_rec_inicial += $saldo_inicial;
    $tot_rec_atual   += $saldo_inicial_prevadic; //$saldo_prevadic_acum;
    $tot_rec_bim     += $saldo_arrecadado ;
    $tot_rec_atebim  += $saldo_arrecadado_acumulado;   
  }
}  
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,espaco($n1)."Outras Receitas Vinculadas à Educação",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atual,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_rec_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_rec_atebim)*100/$tot_rec_atual),'f'),0,0,"R",0);
$pdf->Ln();
  $somador_III_inicial     += $tot_rec_inicial;
  $somador_III_atualizada  += $tot_rec_atual;
  $somador_III_nobimestre  += $tot_rec_bim;
  $somador_III_atebimestre += $tot_rec_atebim;
//--- // total --//--//
$somador_VI_inicial     = ($somador_I_inicial+$somador_III_inicial-$somador_II_inicial);
$somador_VI_atualizada  = ($somador_I_atualizada+$somador_III_atualizada-$somador_II_atualizada);
$somador_VI_nobimestre  = ($somador_I_nobimestre+$somador_III_nobimestre-$somador_II_nobimestre);
$somador_VI_atebimestre = ($somador_I_atebimestre+$somador_III_atebimestre-$somador_II_atebimestre);

$pdf->setfont('arial','',6);
$pdf->cell(90,$alt,"TOTAL DAS RECEITAS (VI) = (I+III-II)",'TBR',0,"L",0);
$pdf->cell(20,$alt,db_formatar($somador_VI_inicial,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_VI_atualizada,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_VI_nobimestre,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_VI_atebimestre,'f'),'TBR',0,"R",0);
@$pdf->cell(20,$alt,db_formatar(($somador_VI_atebimestre*100)/$somador_VI_atualizada,'f'),'TB',0,"R",0);
$pdf->Ln();

/// imprime lá em cima o total das receitas resultante de impostos e das receitas vinculadas
$pos = $pdf->getY();

$pdf->setY($pos_rec_impostos);
$pdf->setX(100);
$pdf->cell(20,$alt,db_formatar($somador_I_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_I_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_I_nobimestre,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_I_atebimestre,'f'),'0',0,"R",0);
@$pdf->cell(20,$alt,db_formatar(($somador_I_atebimestre*100)/$somador_I_atualizada,'f'),'0',0,"R",0);

$pdf->setY($pos_rec_vinculado);
$pdf->setX(100);
$pdf->cell(20,$alt,db_formatar($somador_III_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_III_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_III_nobimestre,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_III_atebimestre,'f'),'0',0,"R",0);
@$pdf->cell(20,$alt,db_formatar(($somador_III_atebimestre*100)/$somador_III_atualizada,'f'),'0',0,"R",0);

$pdf->setY($pos);

///////////////////////////////////////////////
$somador_VI_inicial    = 0; //
$somador_VI_atualizada = 0;
$somador_VI_nobimestre  = 0;
$somador_VI_atebimestre = 0;
$somador_VII_inicial    = 0; //
$somador_VII_atualizada = 0;
$somador_VII_nobimestre  = 0;
$somador_VII_atebimestre = 0;
$somador_VIII_inicial    = 0; //
$somador_VIII_atualizada = 0;
$somador_VIII_nobimestre  = 0;
$somador_VIII_atebimestre = 0;
$somador_IX_inicial     = 0; //
$somador_IX_atualizada  = 0;
$somador_IX_nobimestre  = 0;
$somador_IX_atebimestre = 0;
$somador_X_inicial     = 0; //
$somador_X_atualizada  = 0;
$somador_X_nobimestre  = 0;
$somador_X_atebimestre = 0;
$somador_XI_inicial     = 0; //
$somador_XI_atualizada  = 0;
$somador_XI_nobimestre  = 0;
$somador_XI_atebimestre = 0;

//////////////////

// header das despesas
$pdf->Ln(3);
$pdf->setfont('arial','',6);
$pdf->cell(90,($alt*2),"DESPESAS COM ENSINO POR VINCULAÇÃO",'TBR',0,"L",0);
$pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
$pdf->cell(20,($alt*2),"ATUALIZADA(c)",1,0,"C",0);
$pdf->cell(60,($alt),"DESPESAS LIQUIDADAS",'TB',1,"C",0);  //br
$pdf->setX(140); 
$pdf->cell(20,$alt,"No Bimestre",1,0,"C",0);
$pdf->cell(20,$alt,"Até o Bimestre(d)",1,0,"C",0);
$pdf->cell(20,$alt,"% (d/c)",'TB',0,"C",0);
$pdf->ln();

$pos_desp_vinculado = $pdf->getY();
$pdf->cell(90,$alt,"VINCULADA ÀS RECEITAS RESULTANTES DE IMPOSTOS",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','0',0,"R",0);
$pdf->Ln();
//------------------------------------------------------------------------------

$RESERVA_MDE=0;
$RESERVA_FUNDEF=0;

$result_free = db_dotacaosaldo(4,3,2,true," o58_codigo=20 and o58_instit in (".str_replace('-',', ',$db_selinstit)." ) ",$anousu,$dt_ini,$dt_fin);
for($i=0;$i<pg_numrows($result_free);$i++){
  db_fieldsmemory($result_free,$i);
  if ($o58_subfuncao==999) {
  	 $RESERVA_MDE= $dot_ini;
  }	  	
}

$result_free = db_dotacaosaldo(4,3,2,true," o58_codigo=30 and o58_instit in (".str_replace('-',', ',$db_selinstit)." ) ",$anousu,$dt_ini,$dt_fin);
for($i=0;$i<pg_numrows($result_free);$i++){
  db_fieldsmemory($result_free,$i);
  if ($o58_subfuncao==999){
  	 $RESERVA_FUNDEF= $dot_ini;
  }
}

// totaliza despesa com ensino fundamental
$tot_ini=0;
$tot_atu =0;
$tot_bim =0;
$tot_atebim =0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  if ($o58_subfuncao == 361 && $o58_codigo ==20){
     $tot_ini    += $dot_ini;
     $tot_atu    += $dot_ini + ($suplementado_acumulado-$reduzido_acumulado);
     $tot_bim    += $liquidado;
     $tot_atebim += $liquidado_acumulado;
  }
} 
$pdf->cell(90,$alt,espaco($n1)."Despesas com Ensino Fundamental (VII)",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_ini+$RESERVA_MDE,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atu +$RESERVA_MDE,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_bim +$INTERFERENCIA_MDE,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atebim+$INTERFERENCIA_MDE_AC,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_atebim+$INTERFERENCIA_MDE_AC)*100/($tot_atu+$RESERVA_MDE)),'f'),'0',0,"R",0);
$pdf->Ln();
   $SOMADOR_SUBFUNCAO_MDE_BIMESTRAL = $tot_bim  +$INTERFERENCIA_MDE ;

   $somador_XI_inicial    += $tot_ini + $RESERVA_MDE; //
   $somador_XI_atualizada += $tot_atu +$RESERVA_MDE;
   $somador_XI_nobimestre += $tot_bim  +$INTERFERENCIA_MDE ;
   $somador_XI_atebimestre+= $tot_atebim+$INTERFERENCIA_MDE_AC ;
   $somador_VII_atebimestre +=$tot_atebim+$INTERFERENCIA_MDE_AC;
   $v_inicial    = $tot_ini + $RESERVA_MDE; //
   $v_atualizada = $tot_atu +$RESERVA_MDE;
   $v_nobimestre = $tot_bim +$INTERFERENCIA_MDE;
   $v_atebimestre= $tot_atebim+$INTERFERENCIA_MDE_AC;


//------------------------------------------------------------------------------
// totaliza despesa com educação infantil
$tot_ini=0;
$tot_atu =0;
$tot_bim =0;
$tot_atebim =0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  if ($o58_subfuncao == 365 && $o58_codigo ==20){
     $tot_ini    += $dot_ini;
     $tot_atu    += $dot_ini + ($suplementado_acumulado-$reduzido_acumulado);
     $tot_bim    += $liquidado;
     $tot_atebim += $liquidado_acumulado;
  }
} 
$pdf->cell(90,$alt,espaco($n1)."Despesas com Educação Infantil em Creches e Pré-Escolas (VIII) ",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_ini,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atu,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar(($tot_atebim*100/$tot_atu),'f'),'0',0,"R",0);
$pdf->Ln();
   $somador_XI_inicial    += $tot_ini; //
   $somador_XI_atualizada += $tot_atu;
   $somador_XI_nobimestre += $tot_bim;
   $somador_XI_atebimestre+= $tot_atebim;
   $somador_VIII_atebimestre +=$tot_atebim;
   $v_inicial           += $tot_ini; //
   $v_atualizada   += $tot_atu;
   $v_nobimestre += $tot_bim;
   $v_atebimestre+= $tot_atebim;

//------------------------------------------------------------------------------
// outras Despeas com Ensino
$tot_ini=0;
$tot_atu =0;
$tot_bim =0;
$tot_atebim =0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  if ( $o58_funcao ==12
       && $o58_subfuncao != 361  
       && $o58_subfuncao != 365 
       && $o58_codigo !=20  
       && $o58_codigo !=30 ){
     $tot_ini    += $dot_ini;
     $tot_atu    += $dot_ini + ($suplementado_acumulado-$reduzido_acumulado);
     $tot_bim    += $liquidado;
     $tot_atebim += $liquidado_acumulado;
  }
} 
$pdf->cell(90,$alt,espaco($n1)."Outras Despesas com Ensino ",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_ini,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atu,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar(($tot_atebim*100/$tot_atu),'f'),'0',0,"R",0);
$pdf->Ln();
   $somador_XI_inicial    += $tot_ini; //
   $somador_XI_atualizada += $tot_atu;
   $somador_XI_nobimestre += $tot_bim;
   $somador_XI_atebimestre+= $tot_atebim;
   $v_inicial    += $tot_ini; //
   $v_atualizada += $tot_atu;
   $v_nobimestre += $tot_bim;
   $v_atebimestre+= $tot_atebim;

//------------------------------------------------------------------------------
$pos = $pdf->getY();
$pdf->setY($pos_desp_vinculado);
$pdf->setX(100);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_nobimestre,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atebimestre,'f'),'0',0,"R",0);
@$pdf->cell(20,$alt,db_formatar(($v_atebimestre*100)/$v_atualizada,'f'),'0',0,"R",0);

$pdf->setY($pos);




//------------------------------------------------------------------------------
$pos_desp_vinculado = $pdf->getY();  //segunda fez que sobrecarrego essa variável
$pdf->cell(90,$alt,"VINCULADA AO FUNDEF, NO ENSINO FUNDAMENTAL (IX)",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','0',0,"R",0);
$pdf->Ln();

// pagamento dos profissionais do magisério
$tot_ini=0;
$tot_atu =0;
$tot_bim =0;
$tot_atebim =0;
for($i=0;$i<pg_numrows($result_desp);$i++){
   db_fieldsmemory($result_desp,$i);
   if ( $o58_funcao   ==12
        && $o58_subfuncao == 361 
        && $o58_codigo == 30
        && substr($o58_elemento,0,6)=='331901'   
      ) {        
     $tot_ini    += $dot_ini;
     $tot_atu    += $dot_ini + ($suplementado_acumulado-$reduzido_acumulado);
     $tot_bim    += $liquidado;
     $tot_atebim += $liquidado_acumulado;
  }
} 
$pdf->cell(90,$alt,espaco($n1)."Pagamento dos profissionais do magistério do ensino fundamental (X)",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_ini+$RESERVA_FUNDEF,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atu+$RESERVA_FUNDEF,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_bim +$INTERFERENCIA_FUNDEF,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atebim+$INTERFERENCIA_FUNDEF_AC,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_atebim+$INTERFERENCIA_FUNDEF_AC)*100/($tot_atu+$RESERVA_FUNDEF)),'f'),'0',0,"R",0);
$pdf->ln();
   $somador_XI_inicial    += $tot_ini+$RESERVA_FUNDEF; //
   $somador_XI_atualizada += $tot_atu+$RESERVA_FUNDEF;
   $somador_XI_nobimestre += $tot_bim +$INTERFERENCIA_FUNDEF;
   $somador_XI_atebimestre+= $tot_atebim+$INTERFERENCIA_FUNDEF_AC;
   $somador_X_atebimestre+= $tot_atebim+$INTERFERENCIA_FUNDEF_AC;
   $v_inicial    = $tot_ini+$RESERVA_FUNDEF; //
   $v_atualizada = $tot_atu+$RESERVA_FUNDEF;
   $v_nobimestre = $tot_bim +$INTERFERENCIA_FUNDEF;
   $v_atebimestre= $tot_atebim+$INTERFERENCIA_FUNDEF_AC;

// -----------------  Outras despesas no ensino fundamental
$tot_ini=0;
$tot_atu =0;
$tot_bim =0;
$tot_atebim =0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
   if ( $o58_funcao   ==12
        && $o58_subfuncao == 361 
        && $o58_codigo == 30
        && substr($o58_elemento,0,6)!='331901'   
      ) {        
     $tot_ini    += $dot_ini;
     $tot_atu    += $dot_ini + ($suplementado_acumulado-$reduzido_acumulado);
     $tot_bim    += $liquidado;
     $tot_atebim += $liquidado_acumulado;
  }
} 
$pdf->cell(90,$alt,espaco($n1)."Outras Despesas no Ensino Fundamental",'R',0,"l",0);
$pdf->cell(20,$alt,db_formatar($tot_ini+$RESERVA_FUNDEF,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atu+$RESERVA_FUNDEF,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_bim +$INTERFERENCIA_FUNDEF_DEMAIS,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atebim+$INTERFERENCIA_FUNDEF_DEMAIS_AC,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar((($tot_atebim+$INTERFERENCIA_FUNDEF_DEMAIS_AC)*100/$tot_atu+$RESERVA_FUNDEF),'f'),'0',0,"R",0);
$pdf->ln();
   $somador_XI_inicial    += $tot_ini+$RESERVA_FUNDEF; //
   $somador_XI_atualizada += $tot_atu+$RESERVA_FUNDEF;
   $somador_XI_nobimestre += $tot_bim +$INTERFERENCIA_FUNDEF_DEMAIS;
   $somador_XI_atebimestre+= $tot_atebim+$INTERFERENCIA_FUNDEF_DEMAIS_AC;
   $v_inicial    += $tot_ini+$RESERVA_FUNDEF; //
   $v_atualizada += $tot_atu+$RESERVA_FUNDEF;
   $v_nobimestre += $tot_bim+$INTERFERENCIA_FUNDEF_DEMAIS;
   $v_atebimestre+= $tot_atebim+$INTERFERENCIA_FUNDEF_DEMAIS_AC;

//------------------------------------------------------------------------------
$pos = $pdf->getY();
$pdf->setY($pos_desp_vinculado);
$pdf->setX(100);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_nobimestre,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atebimestre,'f'),'0',0,"R",0);
@$pdf->cell(20,$alt,db_formatar(($v_atebimestre*100)/$v_atualizada,'f'),'0',0,"R",0);

$somador_IX_atebimestre = $v_atebimestre;

$pdf->setY($pos);


// -----------------  contribuição social
$tot_ini=0;
$tot_atu =0;
$tot_bim =0;
$tot_atebim =0;
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  if ($o58_codigo== 1002 && $o58_subfuncao == 361){  // subfunc- ensino funcamental
     $tot_ini    += $dot_ini;
     $tot_atu    += $dot_ini + ($suplementado_acumulado-$reduzido_acumulado);
     $tot_bim    += $liquidado;
     $tot_atebim += $liquidado_acumulado;
  }
} 
$pdf->cell(90,$alt,"VINCULADAS À CONTRIBUIÇÃO SOCIAL DO SALÁRIO-EDUCAÇÃO",'R',0,"l",0);
$pdf->cell(20,$alt,db_formatar($tot_ini,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atu,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar(($tot_atebim*100/$tot_atu),'f'),'0',0,"R",0);
$pdf->ln();
   $somador_XI_inicial    += $tot_ini; //
   $somador_XI_atualizada += $tot_atu;
   $somador_XI_nobimestre += $tot_bim;
   $somador_XI_atebimestre+= $tot_atebim;

// -----------------  credito
$tot_ini=0;
$tot_atu =0;
$tot_bim =0;
$tot_atebim =0;
// for($i=0;$i<pg_numrows($result_desp);$i++){
//  db_fieldsmemory($result_desp,$i);
//  $estrutural = $c60_estrut;
//  $recurso = $recurso ;
//  if ($recurso == ""){//nada
  // if (in_array($estrutural,$desp_credito)){ // despesas com ensino fundamental
//     $tot_ini    += $dot_ini;
//     $tot_atu    += $dot_ini + ($suplementado_acumulado-$reduzido_acumulado);
//     $tot_bim    += $liquidado;
//     $tot_atebim += $liquidado_acumulado;
//  }
//} 
$pdf->cell(90,$alt,"FINANCIADAS COM RECURSOS DE OPERAÇÕES DE CRÉDITO",'R',0,"l",0);
$pdf->cell(20,$alt,db_formatar($tot_ini,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atu,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar(($tot_atebim*100/$tot_atu),'f'),'0',0,"R",0);
$pdf->ln();
   $somador_XI_inicial    += $tot_ini; //
   $somador_XI_atualizada += $tot_atu;
   $somador_XI_nobimestre += $tot_bim;
   $somador_XI_atebimestre+= $tot_atebim;



// -----------------  vinculados a educação
$tot_ini=0;
$tot_atu =0;
$tot_bim =0;
$tot_atebim =0;


for($i=0;$i<pg_numrows($result_desp);$i++){
   db_fieldsmemory($result_desp,$i);
   if ( 
	$o58_funcao== 12
	&& $o58_codigo > 0 
	&& (
	    $o58_codigo != 20 && 
	    $o58_codigo != 30 &&
	    $o58_codigo != 1002 &&
	    $o58_codigo != 1037

	    )
      ) {       
     $tot_ini    += $dot_ini;
     $tot_atu    += $dot_ini + ($suplementado_acumulado-$reduzido_acumulado);
     $tot_bim    += $liquidado;
     $tot_atebim += $liquidado_acumulado;
  }
} 
$pdf->cell(90,$alt,"FINANCIADAS COM OUTROS RECURSOS VINCULADOS À EDUCAÇÃO",'R',0,"l",0);
$pdf->cell(20,$alt,db_formatar($tot_ini,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atu,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_bim,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(20,$alt,db_formatar(($tot_atebim*100/$tot_atu),'f'),'0',0,"R",0);
$pdf->ln();
   $somador_XI_inicial    += $tot_ini; //
   $somador_XI_atualizada += $tot_atu;
   $somador_XI_nobimestre += $tot_bim;
   $somador_XI_atebimestre+= $tot_atebim;



///////////// total da despesa com ensino por vinculação

$pdf->cell(90,$alt,"TOTAL DAS DESPESAS COM ENSINO (XI) ",'TBR',0,"l",0);
$pdf->cell(20,$alt,db_formatar($somador_XI_inicial,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_XI_atualizada,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_XI_nobimestre,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_XI_atebimestre,'f'),'TBR',0,"R",0);
@$pdf->cell(20,$alt,db_formatar(($somador_XI_atebimestre*100/$somador_XI_atualizada),'f'),'TB',0,"R",0);
$pdf->ln();

//-----------------------------------
$somador_XII_atebimestre = 0;
$ganho_fundef = 0;
if ($somador_II_atebimestre > $somador_IV_atebimestre ){
   $somador_XII_atebimestre =  $somador_II_atebimestre - $somador_IV_atebimestre ;
} else {
   $ganho_fundef =  $somador_IV_atebimestre - $somador_II_atebimestre ;
}  
$pdf->Ln(3);
$pdf->setfont('arial','',6);
$pdf->cell(150,$alt,'PERDA/GANHO NAS TRANSFERÊNCIAS DO FUNDEF','TBR',0,"L",0);
$pdf->cell(40,$alt,'VALOR','TB',1,"R",0);
$pdf->cell(150,$alt,'[se II > IV] = Perda nas transferências do FUNDEF (XII)',0,0,"L",0);
$pdf->cell(40,$alt,db_formatar($somador_XII_atebimestre,'f'),'L',1,"R",0);
$pdf->cell(150,$alt,'[se II < IV] = Ganho nas transferências do FUNDEF','B',0,"L",0);
$pdf->cell(40,$alt,db_formatar($ganho_fundef,'f'),'BL',1,"R",0);

// -----------------------------------------------------------------------
// variaveis
$total_XVI_valor = 0; 

$pdf->Ln(3);
$pdf->setfont('arial','',6);
$pdf->cell(150,$alt,'DEDUÇÕES DA DESPESA','TBR',0,"C",0);
$pdf->cell(40,$alt,'VALOR','TB',1,"R",0);

//////////////
// se houver ganho nas transferencias para o funde, e a variavel estiver zerado, sera usado o ganho
if ($ganho_fundef > 0  && $GANHO_COMPLEM_FUNDEF+0==0) {	
    $GANHO_COMPLEM_FUNDEF = $ganho_fundef;	     
}
if  (( $GANHO_COMPLEM_FUNDEF+0) < 0){
       $GANHO_COMPLEM_FUNDEF = 0;
}
$pdf->cell(150,$alt,'PARCELA DO GANHO/COMPLEMENTAÇÃO DO FUNDEF APLICADA NO EXERCÍCIO (XIII)',0,0,"L",0);
$pdf->cell(40,$alt,db_formatar($GANHO_COMPLEM_FUNDEF,'f'),'L',1,"R",0);
  $euxiii = $GANHO_COMPLEM_FUNDEF;
  $tot_xiii = 0;
  $tot_xiii = $GANHO_COMPLEM_FUNDEF;
  $total_XVI_valor += $GANHO_COMPLEM_FUNDEF;

$pdf->cell(150,$alt,'RESTOR A PAGAR INSCRITOR NO EXERCÍCIO SEM DISPONIBILIDADE FINANCEIRA VINCULADA DE RECURSOS PRÓPRIOS',0,0,"L",0);
$pdf->cell(40,$alt,'','L',1,"R",0);
//---
$pdf->setX(20);
$pdf->cell(140,$alt,'Despesas com Ensino Fundamental (XIV)',0,0,"L",0);
$pdf->cell(40,$alt,db_formatar($DESP_ENS_FUNDAMENTAL,'f'),'L',1,"R",0);
  $euxiv = $DESP_ENS_FUNDAMENTAL;
  $tot_xiv = 0;
  $tot_xiv = $DESP_ENS_FUNDAMENTAL;
  $total_XVI_valor += $DESP_ENS_FUNDAMENTAL;

$pdf->setX(20);
$pdf->cell(140,$alt,'Despesas com Educação infantil em Creches e Pré-Escolas',0,0,"L",0);
$pdf->cell(40,$alt,db_formatar($DESP_ENS_INFANTIL,'f'),'L',1,"R",0);
  $total_XVI_valor += $DESP_ENS_INFANTIL;

$pdf->cell(150,$alt,'DESPESAS VINCULADAS AO SUPERÁVIT FINANCEIRO DO GANHO/COMPLEMENTAÇÃO DO FUNDEF DO EXERCÍCIO ANTERIOR (XV)',0,0,"L",0);
$pdf->cell(40,$alt,db_formatar($DESP_VINC_SUPERAVIT,'f'),'L',1,"R",0);
  $euxv = $DESP_VINC_SUPERAVIT;
  $tot_xv =0;
  $tot_xv = $DESP_VINC_SUPERAVIT;
  $total_XVI_valor += $DESP_VINC_SUPERAVIT;

$pdf->cell(150,$alt,'TOTAL (XVI)','TR',0,"L",0);
$pdf->cell(40,$alt,db_formatar($total_XVI_valor,'f'),'T',1,"R",0);
$pdf->ln();

/////////////////////// quebra página

$pdf->cell(150,$alt,'Continua na página 2',0,0,"L",0);
$pdf->addpage();

$pdf->cell(150,$alt,'Continuação da página 1',0,0,"L",0);
$pdf->Ln();


////////////////////////////////////////////////////////////////////////////////////////////
$pdf->Ln(3);
$pdf->setfont('arial','',6);
$pdf->cell(90,($alt),"CONTROLE DE RP INSCRITOS EM EXERCICIOS ANTERIORES",'TR',0,"C",0);
$pdf->cell(20,($alt*2),"Mínima (e)",1,0,"C",0);
$pdf->cell(20,($alt*2),"Apurada(f)",1,0,"C",0);
$pdf->cell(60,($alt),"RESTOS A PAGAR",'TB',1,"C",0);  //br
$pdf->cell(90,($alt),"VINCULADOS A EDUCAÇÃO",'B',0,"C",0);
$pdf->setX(140);
$pdf->cell(40,$alt,"Inscritos em 31/12/".($anousu-1),1,0,"C",0);
$pdf->cell(20,$alt,"Cancelados em $anousu",'TB',0,"C",0);
$pdf->ln();
// 
$tot_cancelado=0;
for($i=0;$i<pg_numrows($result_rp_mde);$i++){
  db_fieldsmemory($result_rp_mde,$i);
  $tot_cancelado += $vlranu;
} 
$pdf->cell(90,$alt,'RP DE DESPESAS COM MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO','0',0,"L",0);
$pdf->cell(20,$alt,db_formatar($RP_MDE_MINIMA,'f'),'RL',0,"R",0);
$pdf->cell(20,$alt,db_formatar($RP_MDE_APURADA,'f'),'RL',0,"R",0);
$pdf->cell(40,$alt,db_formatar($RP_MDE_INSCRITO,'f'),'RL',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_cancelado,'f'),'L',1,"R",0);
// --
$tot_cancelado=0;
for($i=0;$i<pg_numrows($result_rp_fundef);$i++){
  db_fieldsmemory($result_rp_fundef,$i);
  $tot_cancelado += $vlranu;
} 
$pdf->cell(90,$alt,'RP DE DESPESAS COM ENSINO FUNDAMENTAL',0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($RP_FUNDEF_MINIMA,'f'),'RL',0,"R",0);
$pdf->cell(20,$alt,db_formatar($RP_FUNDEF_APURADA,'f'),'RL',0,"R",0);
$pdf->cell(40,$alt,db_formatar($RP_FUNDEF_INSCRITO,'f'),'RL',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_cancelado,'f'),'L',1,"R",0);
//--------- ////////--





// compensação de RP
$pdf->cell(150,$alt,"COMPENSAÇÃO DE RESTOS A PAGAR CANCELADOS EM ".$anousu,'TR',0,"C",0);
$pdf->cell(20,$alt,'VALOR','TB',0,"L",0);
$pdf->cell(20,$alt,0,'TB',1,"R",0);
// --
$pdf->cell(150,$alt,'MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO (XVII) ','TR',0,"L",0);
@$pdf->cell(40,$alt,db_formatar($COMPENSACAO_RP_MDE,'f'),'0',1,"R",0);
  $somador_XVII_valor = $COMPENSACAO_RP_MDE;

$pdf->cell(150,$alt,'ENSINO FUNCAMENTAL (XVIII)','BR',0,"L",0);
@$pdf->cell(40,$alt,db_formatar($COMPENSACAO_RP_FUNDEF,'f'),'B',1,"R",0);
  $euxviii = $COMPENSACAO_RP_FUNDEF;
  $somador_XVIII_valor = $COMPENSACAO_RP_FUNDEF;

//--------------------------------------------
// total das despesas consideradas para find do limite
// VII+VIII+IXI+XII)-XVI]
$total_XIX_valor = 
         ($somador_VII_atebimestre  
          + $somador_VIII_atebimestre 
          + $somador_IX_atebimestre    
          + $somador_XII_atebimestre ) - $total_XVI_valor ;
$pdf->Ln(3);
$pdf->cell(150,$alt,'TOTAL DAS DESPESAS CONSIDERADAS PARA FINS DE LIMITE CONSTITUCIONAL (XIX) = [(VII+VIII+IX+XII)-XVI]','TBR',0,"L",0);
$pdf->cell(40,$alt,db_formatar($total_XIX_valor,'f'),'TB',1,"R",0);

///
@$total_A = (($total_XIX_valor-$somador_XVII_valor)/$somador_I_atebimestre) * 100;
@$total_B = ((($somador_VII_atebimestre
             +$somador_IX_atebimestre
	     +$somador_XII_atebimestre)
             -($euxiii
	       + $euxiv
	       + $euxv
	       + $euxviii
	      ))/($somador_I_atebimestre *0.25)) * 100;

@$total_C =  ($somador_X_atebimestre / $somador_IV_atebimestre) * 100;

$pdf->Ln(3);
$pdf->cell(170,$alt,'TABELA DE CUMPRIMENTO DOS LIMITES CONSTITUCIONAIS','TBR',0,"L",0);  $pdf->cell(20,$alt,'%','TB',1,"C",0);
$pdf->cell(170,$alt,'MÍNIMO DE 25% DAS RECEITAS RESULTANTES DE IMPOSTOS NA MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO [(XIX-XVII)/I]','R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($total_A,'f'),'0',1,"R",0);
$pdf->cell(170,$alt,'Caput do artigo 212 da CF/88','R',0,"L",0);$pdf->cell(20,$alt,'','0',1,"C",0);

$pdf->cell(170,$alt,'MÍNIMO DE 60% DOS RECURSOS COM MDE NO ENSINO FUNDAMENTAL [(VII+IX+XII)-(XIII+XIV+XV+XVIII)]/(I x 0,25) ','R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($total_B,'f'),'0',1,"R",0);
$pdf->cell(170,$alt,'Caput do artigo 60 do ADCT da CF/88','R',0,"L",0);$pdf->cell(20,$alt,'','0',1,"C",0);

$pdf->cell(170,$alt,'MÍNIMO DE 60% DO FUNDEF NA REMUNERAÇÃO DO MAGISTÉRIO ENSINO FUNDAMENTAL (X/IV) ','R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($total_C,'f'),'0',1,"R",0);
$pdf->cell(170,$alt,'paragrafo 5, do artigo 60 do ADCT da CF/88','BR',0,"L",0);$pdf->cell(20,$alt,'','B',1,"C",0);

/////////////////////////////////////////
//// saldo financeiro do fundef


$pdf->Ln(3);

$tot_valor = 0;
$tot_ant   = 0;

for($i=0;$i<pg_numrows($result_bal);$i++){
  db_fieldsmemory($result_bal,$i);  
  if (in_array($estrutural,$saldo_fundef)){ 
     $tot_valor  += $saldo_final ;
     $tot_ant    += $saldo_anterior ;
  }
  if (in_array($estrutural,$desp_ef)){     
     $INTERFERENCIA_MDE += $saldo_final ;
  }
  if (in_array($estrutural,$desp_pgt)){ 
  	 $INTERFERENCIA_FUNDEF += $saldo_final ;     
  }  
} 
$pdf->cell(90,($alt*2),'SALDO FINANCEIRO DO FUNDEF','TBR',0,"L",0);
$pdf->cell(60,$alt,"Em 31/dez/".($anousu-1),'TBR',0,"C",0);
$pdf->cell(40,$alt,"Até o Bimestre",'TB',1,"C",0);
$pdf->setX(100);
$pdf->cell(60,$alt,db_formatar($tot_ant,'f'),'TBR',0,"R",0);
$pdf->cell(40,$alt,db_formatar($tot_valor,'f'),'TB',1,"R",0);

/// lista despe
$pdf->Ln(3);
$pdf->setfont('arial','',6);
$pdf->cell(90,($alt),"DESPESAS COM MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO",'T',0,"C",0);
$pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
$pdf->cell(20,($alt*2),"ATUALIZADA(h)",1,0,"C",0);
$pdf->cell(60,($alt),"DESPESAS LIQUIDADAS",'TB',1,"C",0);  //br
$pdf->cell(90,($alt),"POR SUBFUNÇÃO",'B',0,"C",0);
$pdf->setX(140); 
$pdf->cell(20,$alt,"No Bimestre",1,0,"C",0);
$pdf->cell(20,$alt,"Até o Bimestre(i)",1,0,"C",0);
$pdf->cell(20,$alt,"% (i/h)",'TB',0,"C",0);
$pdf->ln();
// 
// lista despesas por subfunção
// aqui tem lixo, nunca copie esse codigo inutil
// lista ensino fundamental + reserva de contingencia
// lista a educação infantil
$tot_dot_ini=0;
$tot_dot_atual=0;
$tot_dot_liquidado=0;
$tot_dot_liquidado_acumulado=0;

for($i=0;$i< pg_numrows($result_subfunc);$i++) {
    db_fieldsmemory($result_subfunc,$i);
    $vatual = $dot_ini + ($suplementado_acumulado-$reduzido_acumulado);
    if ($o58_subfuncao==361){   
    
      $atual = $dot_ini + ($suplementado_acumulado-$reduzido_acumulado);
      $pdf->cell(90,$alt,"$o53_descr",'R',0,"L",0);
      $pdf->cell(20,$alt,db_formatar($dot_ini + $RESERVA_MDE,'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($vatual  + $RESERVA_MDE,'f'),'R',0,"R",0);
      // $pdf->cell(20,$alt,db_formatar($liquidado + $INTERFERENCIA_MDE,'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($SOMADOR_SUBFUNCAO_MDE_BIMESTRAL,'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar(($liquidado_acumulado + $INTERFERENCIA_MDE_AC),'f'),'R',0,"R",0);
      @$pdf->cell(20,$alt,db_formatar(((($liquidado_acumulado +$INTERFERENCIA_MDE_AC)*100)/($atual+$RESERVA_MDE)),'f'),'0',0,"R",0);
      $pdf->Ln();
      $tot_dot_ini         += $dot_ini + $RESERVA_MDE;
      $tot_dot_atual       += ($vatual  + $RESERVA_MDE);
      $tot_dot_liquidado   += $SOMADOR_SUBFUNCAO_MDE_BIMESTRAL; // GAMBIARRA, nao siga este mau exemplo ! 
      $tot_dot_liquidado_acumulado += $liquidado_acumulado+ $INTERFERENCIA_MDE_AC;
      continue;
    }       
    if ($o58_subfuncao==365){   
      $pdf->cell(90,$alt,"$o53_descr",'R',0,"L",0);
      $pdf->cell(20,$alt,db_formatar($dot_ini,'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($vatual,'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($liquidado,'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar(($liquidado_acumulado),'f'),'R',0,"R",0);
      @$pdf->cell(20,$alt,db_formatar(((($liquidado_acumulado)*100)/($vatual)),'f'),'0',0,"R",0);
      $tot_dot_ini         += $dot_ini;
      $tot_dot_atual       += $vatual ;
      $tot_dot_liquidado   += $liquidado;
      $tot_dot_liquidado_acumulado += $liquidado_acumulado;

    }
}
$pdf->ln();
$pdf->cell(90,$alt,"TOTAL DAS DESPESAS COM ENSINO",'TBR',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_dot_ini ,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_dot_atual, 'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_dot_liquidado ,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_dot_liquidado_acumulado,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,'-','TB',0,"R",0);
$pdf->Ln();


////////////////////////////////
$pdf->cell(90,$alt,"FONTE: Contabilidade",0,0,"L",0);


$pdf->ln(10);
$pdf->setfont('arial','',5);
$controle  =  "______________________________"."\n"."Controle Interno";
$sec  =  "______________________________"."\n"."Secretaria da Fazenda";
$cont =  "______________________________"."\n"."Contador";
$pref =  "______________________________"."\n"."Prefeito";
$ass_pref = $classinatura->assinatura(1000,$pref);
$ass_sec  = $classinatura->assinatura(1002,$sec);
$ass_cont = $classinatura->assinatura(1005,$cont);
$ass_controle = $classinatura->assinatura(1009,$controle);
//echo $ass_pref;
if( $pdf->gety() > ( $pdf->h - 30 ) )
  $pdf->addpage();
$largura = ( $pdf->w ) / 2;
$pdf->ln(10);
$pos = $pdf->gety();
$pdf->multicell($largura,2,$ass_pref,0,"C",0,0);
$pdf->setxy($largura,$pos);
$pdf->multicell($largura,2,$ass_sec,0,"C",0,0);

$pdf->Ln(10);
$pos = $pdf->gety();
$pdf->multicell($largura,2,$ass_cont,0,"C",0,0);
$pdf->setxy($largura,$pos);
$pdf->multicell($largura,2,$ass_controle,0,"C",0,0);


$pdf->Output();

// pg_exec("commit");

?>