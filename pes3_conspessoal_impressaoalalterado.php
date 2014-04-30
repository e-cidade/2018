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
include("classes/db_cadferia_classe.php");
include("classes/db_pessoal_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhfuncao_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_depend_classe.php");
include("classes/db_afasta_classe.php");

$clpessoal = new cl_pessoal;
$clrhpessoal = new cl_rhpessoal;
$clrhfuncao = new cl_rhfuncao;
$clpessoal->rotulo->label();
$clrhpessoal->rotulo->label();
$clrhfuncao->rotulo->label();

$clcadferia = new cl_cadferia;
$clcadferia->rotulo->label();

$clcgm = new cl_cgm;
$clcgm->rotulo->label();

$cldepend = new cl_depend;
$cldepend->rotulo->label();

$clafasta = new cl_afasta;
$clafasta->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('z01_ender');
$clrotulo->label('z01_munic');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_cgccpf');
$clrotulo->label('r13_descr');
$clrotulo->label('r37_descr');
$clrotulo->label('rh02_lota');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$head3 = "CADASTRO DE CÓDIGOS";
$head5 = "PERÍODO : ".$mes." / ".$ano;

$sql = $clrhpessoal->sql_query_pesquisa(
                                        null,
                                        "
                                         *,
                                         case rh30_regime
                                              when 1 then 'Estatutário'
                                              when 2 then 'Celetista'
                                              when 3 then 'Extra-Quadro'
                                         end as descr_regime,
                                         case rh30_vinculo
                                              when 'A' then 'Ativo'
                                              when 'I' then 'Inativo'
                                              when 'P' then 'Pensionista'
                                         end as descr_vinculo,
                                         rh37_cbo as cbo,
                                         case rh02_vincrais
                                              when 10 then 'CLT'
                                              when 30 then 'Servidor Público'
                                              when 35 then 'Servidor Público Não Efetivo'
                                              when 40 then 'Trabalhador Avulso'
                                              when 90 then 'Contrato'
                                         end as descr_vinculorais,
                                         case rh02_tpcont
                                              when '12' then 'Agente Público'
                                              when '01' then 'CLT'
                                              when '04' then 'Empregado Contratado por Prazo Determinado'
                                         end as descr_contrato
                                        ",
                                        "",
                                        "
                                             rh02_anousu = $ano
                                         and rh02_mesusu = $mes
                                         and rh01_regist = $regist
                                        ",
                                         $ano,
                                         $mes
                                       );
$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0 || $xxnum==false){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);
}
db_fieldsmemory($result,0);
$alt="5";
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->AddPage("P");
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',12);
$pdf->cell(180,$alt,"DADOS CADASTRAIS DO FUNCIONÁRIO",0,0,"C",1);
$pdf->ln();
$fonte01="arial";
$tam01="9";
$b01="b";
$fonte02="times";
$tam02="9";
$b02="";

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLrh01_regist.":",0,0,"R",0);
$pdf->setfont($fonte02,'',$tam02);
$pdf->cell(60,$alt,$rh01_regist,0,0,"L",0);

$pdf->cell(1,$alt,"  ",0,0,"C",0);

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLz01_ender.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$z01_ender.', '.$z01_numero.' '.$z01_compl,0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLz01_numcgm.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$z01_numcgm,0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLz01_munic.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$z01_munic,0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLz01_nome.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLrh01_nasc.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,db_formatar($rh01_nasc,'d'),0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLrh01_admiss.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,db_formatar($rh01_admiss,'d'),0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLrh02_salari.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$rh02_salari,0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLrh02_lota.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$r70_estrut.'-'.substr($r70_descr,0,25),0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLrh30_regime.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$rh30_regime,0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLrh02_tbprev.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,"$rh02_tbprev-$r33_nome",0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_tpvinc.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,"$rh30_vinculo-$descr_vinculo",0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLrh02_vincrais.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,"$rh02_vincrais-$descr_vinculorais",0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLrh37_cbo.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$cbo,0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_funcao.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,"$r01_funcao-$r37_descr",0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_padrao.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$r01_padrao,0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_hrssem.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$r01_hrssem,0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam02);
$pdf->cell(35,$alt,$RLr01_hrsmen.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$r01_hrsmen,0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_banco.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,"$r01_banco/$r01_agenc",0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_contac.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$r01_contac.":",0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_admiss.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,db_formatar($r01_admiss,'d'),0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_salari.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$r01_salari,0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_tpcont.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,"$r01_tpcont-$descr_contrato",0,0,"L",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_recis.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,db_formatar($r01_recis,'d').'-'.$r01_caub,0,0,"L",0);

$pdf->ln();


########################################################################$######################################
$sql = "
	select *,
	         case r01_folha
	              when 'M' then 'Mensal'
		      when 'Q' then 'Quinzenal'
		      when 'S' then 'Semanal'
		 end as descr_folha,
	         case r01_tipsal
	              when 'M' then 'Mensal'
		      when 'Q' then 'Quinzenal'
		      when 'S' then 'Semanal'
		      when 'D' then 'Diário'
		      when 'H' then 'Horista'
		      when 'E' then 'Extra'
		 end as descr_tipsal,
	         case r01_instru
	              when 1 then 'Analfabeto'
		      when 2 then 'Até 4a. Série Incompleta'
		      when 3 then '4a. Série Completa'
		      when 4 then 'Até 8a. Série Incompleta'
		      when 5 then 'Primeiro Grau Completo'
		      when 6 then 'Segundo Grau Incompleto'
		      when 7 then 'Segundo Grau Completo'
		      when 8 then 'Superior Incompleto'
		      when 9 then 'Superior Completo'
		 end as descr_instru,
	         case r01_estciv
	              when 1 then 'Solteiro'
		      when 2 then 'Casado'
		      when 3 then 'Viúvo'
		      when 4 then 'Separado Consensual'
		      when 5 then 'Divorciado'
		      when 6 then 'Outros'
		 end as descr_estciv,
	         case r01_sexo
	              when 'M' then 'Masculino'
		      else 'Feminino'
		 end as descr_sexo,
	         case r01_nacion
	              when 10 then 'Brasileiro'
		      when 20 then 'Naturalizado'
		      when 21 then 'Argentino'
		      else 'Outros'
		 end as descr_nacion
																			     
	from pessoal
     		inner join cgm on cgm.z01_numcgm = pessoal.r01_numcgm
          	inner join funcao on funcao.r37_anousu = pessoal.r01_anousu
			and funcao.r37_mesusu = pessoal.r01_mesusu
			and funcao.r37_funcao = pessoal.r01_funcao
		inner join lotacao on lotacao.r13_anousu = pessoal.r01_anousu
			and lotacao.r13_mesusu = pessoal.r01_mesusu
			and lotacao.r13_codigo = pessoal.r01_lotac
		left join cargo on cargo.r65_anousu = pessoal.r01_anousu
			and cargo.r65_mesusu = pessoal.r01_mesusu
			and cargo.r65_cargo = pessoal.r01_cargo
		where pessoal.r01_anousu = $ano
			and pessoal.r01_mesusu = $mes
			and pessoal.r01_regist = $regist ;
	 ";																			    

  $result = pg_exec($sql);
  db_fieldsmemory($result,0);

  $pdf->ln();
  $pdf->setfont('arial','b',12);
  $pdf->cell(180,$alt,"OUTROS DADOS",0,0,"C",1);

  $pdf->ln();
  $pdf->setfont('arial','b',9);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(35,$alt,$RLr01_estciv.":",0,0,"R",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,"$r01_estciv-".@$descr_estciv,0,0,"L",0);
  $pdf->cell(1,$alt,"  ",0,0,"C",0);
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(35,$alt,$RLr01_nacion.":",0,0,"R",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,$r01_nacion."-".@$descr_nacion,0,0,"L",0);

  $pdf->ln();

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(35,$alt,$RLr01_tipsal.":",0,0,"R",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,$r01_tipsal."-".@$descr_tipsal,0,0,"L",0);
  $pdf->cell(1,$alt,"  ",0,0,"C",0);
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(35,$alt,$RLr01_ponto.":",0,0,"R",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,$r01_ponto,0,0,"L",0);

  $pdf->ln();

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(35,$alt,$RLr01_folha.":",0,0,"R",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,"$r01_folha-$descr_folha",0,0,"L",0);
  $pdf->cell(1,$alt,"  ",0,0,"C",0);
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(35,$alt,$RLr01_instru.":",0,0,"R",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,"$r01_instru-$descr_instru",0,0,"L",0);

  $pdf->ln();

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(35,$alt,$RLr01_natura.":",0,0,"R",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,$r01_natura,0,0,"L",0);
  $pdf->cell(1,$alt,"  ",0,0,"C",0);
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(35,$alt,$RLr01_sexo.":",0,0,"R",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,@$descr_sexo,0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_fgts.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,db_formatar($r01_fgts,'d'),0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_ccfgts.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$r01_ccfgts,0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_anter.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,db_formatar($r01_anter,'d'),0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_trien.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,db_formatar($r01_trien,'d'),0,0,"L",0);

$pdf->ln();
  $pdf->ln();

$pdf->setfont('arial','b',12);
$pdf->cell(180,$alt,"DOCUMENTOS",0,0,"C",1);

$pdf->setfont('arial','b',9);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,"Título/Zona/Seção:",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,"$r01_titele/$r01_zonael/$r01_secaoe",0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_carth.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$r01_carth,0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_anter.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,db_formatar($r01_anter,'d'),0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_trien.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,db_formatar($r01_trien,'d'),0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_ctps.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$r01_ctps,0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLr01_pis.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,@$r01_pis,0,0,"L",0);

$pdf->ln();


$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,"Reservista/Categoria:",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,@$r01_reserv."/".@$r01_catres,0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);
$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLz01_cgccpf.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,@$z01_cgccpf,0,0,"L",0);

$pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(35,$alt,$RLz01_ident.":",0,0,"R",0);
$pdf->setfont($fonte02,$b02,$tam02);
$pdf->cell(60,$alt,$z01_ident,0,0,"L",0);
$pdf->cell(1,$alt,"  ",0,0,"C",0);

#############################################################################################################

$sql = "select * from cadferia where r30_regist=$regist and r30_anousu = $ano and r30_mesusu = $mes order by r30_perai";
$result = pg_query($sql);

  $pdf->ln();
  $pdf->ln();
  $pdf->setfont('arial','b',12);
  $pdf->cell(180,$alt,"FÉRIAS",0,0,"C",1);
  $pdf->ln();

$pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(40,$alt,"Período Aquisitivo",1,0,"C",1);
$pdf->cell(10,$alt,"Faltas",1,0,"C",1);
$pdf->cell(12,$alt,"Direito",1,0,"C",1);
$pdf->cell(36,$alt,"Período de Gozo",1,0,"C",1);
$pdf->cell(9,$alt,"Dias",1,0,"C",1);
$pdf->cell(12,$alt,"Abono",1,0,"C",1);
$pdf->cell(18,$alt,"Pagamento",1,0,"C",1);
$pdf->cell(10,$alt,"Tipo",1,0,"C",1);
$pdf->cell(23,$alt,"Ponto",1,0,"C",1);
  $pdf->ln();
$pdf->setfont($fonte02,$b02,$tam02);

for ($i = 0;$i < pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);

  if ($pdf->gety() > $pdf->h - 30 ){
    $pdf->addpage();
    $pdf->setfont($fonte01,$b01,$tam01);
    $pdf->cell(40,$alt,"Período Aquisitivo",1,0,"C",1);
    $pdf->cell(10,$alt,"Faltas",1,0,"C",1);
    $pdf->cell(12,$alt,"Direito",1,0,"C",1);
    $pdf->cell(36,$alt,"Período de Gozo",1,0,"C",1);
    $pdf->cell(9,$alt,"Dias",1,0,"C",1);
    $pdf->cell(12,$alt,"Abono",1,0,"C",1);
    $pdf->cell(18,$alt,"Pagamento",1,0,"C",1);
    $pdf->cell(10,$alt,"Tipo",1,0,"C",1);
    $pdf->cell(23,$alt,"Ponto",1,0,"C",1);
    $pdf->ln();
    $pdf->setfont($fonte02,$b02,$tam02);
  }  

  $pdf->cell(40,$alt,db_formatar($r30_perai,'d').' - '.db_formatar($r30_peraf,'d'),1,0,"C",0);
  $pdf->cell(10,$alt,$r30_faltas,1,0,"C",0);
  $pdf->cell(12,$alt,$r30_ndias,1,0,"C",0);
  $pdf->cell(36,$alt,db_formatar($r30_per1i,'d').' - '.db_formatar($r30_per1f,'d'),1,0,"C",0);
  $pdf->cell(9,$alt,$r30_dias1,1,0,"C",0);
  $pdf->cell(12,$alt,$r30_abono,1,0,"C",0);
  $pdf->cell(18,$alt,$r30_proc1,1,0,"C",0);
  $pdf->cell(10,$alt,$r30_tip1,1,0,"C",0);
  $pdf->cell(23,$alt,($r30_ponto == "C"?"Complementar":"Salário"),1,0,"C",0);
  $pdf->ln();

}  

$pdf->ln();
$pdf->ln();
########################################################################################################################
  $pdf->setfont('arial','b',12);
  $pdf->cell(180,$alt,"DEPENDENTES",0,0,"C",1);
  $pdf->setfont('arial','b',9);
  $pdf->ln();
  $sql = "
  	select r03_nome,
         	r03_dtnasc,
	 	case r03_gparen
			when 'C' then 'Cônjuge'
		        when 'F' then 'Filho(a)'
		        when 'P' then 'Pai'
		        when 'M' then 'Mãe'
		        when 'A' then 'Avô(ó)'
		        when 'O' then 'Outros'
		end as r03_gparen,
		case r03_depend
		        when 'C' then 'Cálculo'
		        when 'S' then 'Sempre'
		        when 'N' then 'Não'
		end as r03_depend,
		case r03_irf
			when '0' then 'Não Dependente'
		        when '1' then 'Cônjuge/Companheiro(a)'
		        when '2' then 'Filho(a)'
		        when '3' then 'Irmãos,Netos Até 21 anos'
		        when '4' then 'Avós'
		        when '5' then 'Absolutamente Incapaz'
		        when '6' then 'Filhos Maiores Universitários'
		        when '7' then 'Irmãos,Netos Maiores de 21 anos'
		end as r03_irf
  	from depend 
	where r03_regist=$regist 
	  and r03_anousu = $ano 
	  and r03_mesusu = $mes";
  $result = pg_exec($sql);

    $pdf->setfont($fonte01,$b01,$tam01);
$pdf->cell(75,$alt,$RLr03_nome,1,0,"C",1);
$pdf->cell(32,$alt,$RLr03_dtnasc,1,0,"C",1);
$pdf->cell(22,$alt,$RLr03_gparen,1,0,"C",1);
$pdf->cell(24,$alt,$RLr03_depend,1,0,"C",1);
$pdf->cell(39,$alt,$RLr03_irf,1,0,"C",1);
$pdf->ln();
    $pdf->setfont($fonte02,$b02,$tam02);

for ($i = 0;$i < pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  if ($pdf->gety() > $pdf->h - 30 ){
      $pdf->setfont($fonte01,$b01,$tam01);
      $pdf->addpage();
      $pdf->cell(75,$alt,$RLr03_nome,1,0,"C",1);
      $pdf->cell(32,$alt,$RLr03_dtnasc,1,0,"C",1);
      $pdf->cell(22,$alt,$RLr03_gparen,1,0,"C",1);
      $pdf->cell(24,$alt,$RLr03_depend,1,0,"C",1);
      $pdf->cell(39,$alt,$RLr03_irf,1,0,"C",1);
      $pdf->ln();
      $pdf->setfont($fonte02,$b02,$tam02);
  }  
  $pdf->cell(75,$alt,$r03_nome,1,0,"C",0);
  $pdf->cell(32,$alt,db_formatar($r03_dtnasc,'d'),1,0,"C",0);
  $pdf->cell(22,$alt,$r03_gparen,1,0,"C",0);
  $pdf->cell(24,$alt,$r03_depend,1,0,"C",0);
  $pdf->cell(39,$alt,$r03_irf,1,0,"C",0);
  $pdf->ln();
}

$pdf->Output();
?>