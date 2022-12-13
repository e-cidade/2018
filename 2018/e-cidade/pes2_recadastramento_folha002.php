<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

include(modification("fpdf151/pdf1.php"));
include(modification("libs/db_sql.php"));
include(modification("classes/db_cadferia_classe.php"));
include(modification("classes/db_pessoal_classe.php"));
include(modification("classes/db_cgm_classe.php"));
include(modification("classes/db_depend_classe.php"));
include(modification("classes/db_afasta_classe.php"));

//$ano    = 2009;
//$mes    = 1;
//$regist = 60012;
//$ordem = 'order by z01_nome ';

$head1 = "SECRETARIA MUNICIPAL DE ADMINISTRAÇÃO\n E RECURSOS HUMANOS";

$clpessoal = new cl_pessoal;
$clpessoal->rotulo->label();

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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ordem == 'a'){
   $orderby = " order by z01_nome ";
}else{
   $orderby = " order by rh01_regist ";
}

$db_where = '';
if($regist != ''){
   $db_where = " and rh01_regist = $regist ";
}

$sql = "
	select *,
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
		 end as descr_contrato,
		 rh01_regist as regist
		 
	from rhpessoal
	  inner join rhpessoalmov   on rh02_anousu = $ano
		                         and rh02_mesusu = $mes
		              	         and rh02_regist = rh01_regist
										 			   and rh02_instit = ".db_getsession("DB_instit")."
    inner join rhregime       on rh30_codreg = rh02_codreg
                             and rh30_instit = rh02_instit
	  left  join rhpeslocaltrab on rh56_seqpes = rh02_seqpes
		left  join rhlocaltrab    on rh55_codigo = rh56_localtrab 
		                         and rh56_princ  = 't'
 		inner join cgm            on z01_numcgm  = rh01_numcgm
   	left join rhfuncao        on rh37_funcao = rh01_funcao
                             and rh37_instit = rh02_instit 
	  left join rhlota          on r70_codigo  = rh02_lota
                             and r70_instit  = rh02_instit
		left join rhpescargo      on rh20_seqpes = rh02_seqpes
    left join rhcargo         on rh20_cargo  = rh04_codigo
                             and rh04_instit = rh02_instit
    left join rhraca          on rh01_raca   = rh18_raca
    left join rhestcivil      on rh01_estciv = rh08_estciv
    left join rhnacionalidade on rh01_nacion = rh06_nacionalidade
    left join rhinstrucao     on rh21_instru = rh01_instru
    left join rhpesbanco      on rh44_seqpes = rh02_seqpes
    left join rhpespadrao     on rh02_seqpes = rh03_seqpes
    left join rhpesfgts       on rh01_regist = rh15_regist
    left join rhpesdoc        on rh01_regist = rh16_regist
    left join rhpesrescisao   on rh02_seqpes = rh05_seqpes
		left outer join (select distinct r33_codtab,r33_nome 
		                 from inssirf 
				             where r33_anousu = $ano
				               and r33_mesusu = $mes 
					             and r33_instit = ".db_getsession("DB_instit")."
                    ) as x on r33_codtab = rh02_tbprev+2
where rh05_seqpes is null
$db_where
$orderby
       ";
// die($sql);exit;
$result = db_query($sql);
$xxnum = pg_numrows($result);
if($xxnum == 0 || $xxnum==false){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);
}
$alt="6";
$pdf = new PDF1(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
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

for($index=0; $index<$xxnum; $index ++){

  $pdf->AddPage();
  db_fieldsmemory($result,$index);
  $pdf->setfont('arial','b',12);
  $pdf->cell(0,$alt,'BLOCO 01 : IDENTIFICAÇÃO DO SERVIDOR',0,1,"L",1);
  $pdf->ln();
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Nome',0,0,"L",0);
  $pdf->setfont($fonte02,$b01,10);
  $pdf->cell(100,$alt,': '.$z01_nome,0,1,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Matrícula ',0,0,"L",0);
  $pdf->setfont($fonte02,'',$tam02);
  $pdf->cell(30,$alt,': '.$rh01_regist,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Admissão',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(20,$alt,': '.db_formatar($rh01_admiss,'d'),0,1,"L",0);

//////

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Raça',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': '.$rh18_raca.' - '.$rh18_descr,0,0,"L",0);


  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'G. de Instrução',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,': '.$rh21_instru.' - '.$rh21_descr,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Nec.Especiais',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(40,$alt,': 0 - NENHUMA',0,1,"L",0);

/////////////////

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Nascimento',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': '.db_formatar($rh01_nasc,'d'),0,0,"L",0);


  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Lotação',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(80,$alt,': '.$r70_estrut.' - '.$r70_descr,0,1,"L",0);

/////////////////

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Estado Civil',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': '.$rh08_estciv.' - '.$rh08_descr,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Naturalidade',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,': '.$rh01_natura,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Nacionalidade',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(80,$alt,': '.$rh06_nacionalidade.' - '.$rh06_descr,0,1,"L",0);

/////////////////

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Endereço',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(115,$alt,': '.$z01_ender.($z01_numcon > 0 ?', '.$z01_numcon:'').' '.$z01_compl,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Inscrição IPTU',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(3,$alt,': ',0,0,"L",0);
  $pdf->cell(20,$alt,' ',1,1,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Bairro',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(115,$alt,': '.$z01_bairro,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(15,$alt,'CEP',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(20,$alt,': '.db_formatar($z01_cep,'cep'),0,1,"L",0);


  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Cidade',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,': '.$z01_munic,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(23,$alt,'UF',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(32,$alt,': '.$z01_uf,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(15,$alt,'Telefone',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(20,$alt,': '.$z01_telef,0,1,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Celular',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,': '.$z01_telcel,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(23,$alt,'Email',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(50,$alt,': '.$z01_email,0,1,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(15,$alt,'ENDEREÇO PARA CONTATO',0,1,"L",0);

/////////////////

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Endereço',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(100,$alt,': '.$z01_endcon.($z01_numcon > 0 ?', '.$z01_numcon:'').' '.$z01_comcon,0,1,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Bairro',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(115,$alt,': '.$z01_baicon,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(15,$alt,'CEP',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(20,$alt,': '.$z01_cepcon,0,1,"L",0);


  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Cidade',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,': '.$z01_muncon,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(23,$alt,'UF',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(32,$alt,': '.$z01_ufcon,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(15,$alt,'Telefone',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': '.$z01_telcon,0,1,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Celular',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,': '.$z01_celcon,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(23,$alt,'Email',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(50,$alt,': '.$z01_emailc,0,1,"L",0);

///////

  $pdf->ln();


########################################################################$######################################

  $pdf->setfont('arial','b',12);
  $pdf->cell(0,$alt,'BLOCO 02 : DOCUMENTOS',0,1,"L",1);
  $pdf->ln();
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'CPF',0,0,"L",0);
  $pdf->setfont($fonte02,'',$tam02);
  $pdf->cell(60,$alt,': '.db_formatar($z01_cgccpf,'cpf'),0,1,"L",0);

///////////////

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Identidade',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,': '.$z01_ident,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Órgão Emissor',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': ',0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Emissão',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(20,$alt,': ',0,0,"L",0);
  $pdf->setfont($fonte01,$b01,$tam01);

  $pdf->cell(10,$alt,'UF',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(20,$alt,': ',0,1,"L",0);


/////////////  

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Título',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,': '.$rh16_titele,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Zona',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': '.$rh16_zonael,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Seção',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': '.$rh16_secaoe,0,1,"L",0);

//////////////

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Reservista',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,': '.$rh16_reserv,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Categoria',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': '.$rh16_catres,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'PIS/PASEP',0,0,"L",0);
  $pdf->setfont($fonte02,'',$tam02);
  $pdf->cell(60,$alt,': '.$rh16_pis,0,1,"L",0);

////////////

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'CTPS',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(60,$alt,': '.db_formatar($rh16_ctps_n,'s','0', 7, 'e',0),0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Série - Dídito',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': '.db_formatar($rh16_ctps_s,'s','0', 5, 'e',0).'-'.$rh16_ctps_d,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'UF',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': '.$rh16_secaoe,0,1,"L",0);

////////////////////////

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(40,$alt,'Carteira de Habilitação',0,0,"L",0);
  $pdf->setfont($fonte02,'',$tam02);
  $pdf->cell(40,$alt,': '.$rh16_carth_n,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Categoria',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': '.$r16_carth_cat,0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Validade',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': '.$rh16_carth_val,0,1,"L",0);

////////////////////////

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(40,$alt,'Conselho da Categoria',0,0,"L",0);
  $pdf->setfont($fonte02,'',$tam02);
  $pdf->cell(40,$alt,': _______________________',0,0,"L",0);

  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(25,$alt,'Número',0,0,"L",0);
  $pdf->setfont($fonte02,$b02,$tam02);
  $pdf->cell(30,$alt,': __________________________________',0,1,"L",0);

  $pdf->ln();


########################################################################$######################################

  $pdf->setfont('arial','b',12);
  $pdf->cell(0,$alt,'BLOCO 03 : DEPENDENTES',0,1,"L",1);
  $pdf->ln();
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(110,$alt,'Nome',0,0,"C",0);
  $pdf->cell(25,$alt,'Nascimento',0,0,"C",0);
  $pdf->cell(20,$alt,'Parentesco',0,0,"C",0);
  $pdf->cell(20,$alt,'Sal. Fam.',0,0,"C",0);
  $pdf->cell(20,$alt,'IRRF',0,1,"C",0);
  $pdf->setfont($fonte02,'',$tam02);
  for($i=0;$i<10;$i++){
    $pdf->cell(5,3,'',0,1,'C',0);
    $pdf->cell(110,$alt,'_______________________________________________________________',0,0,"L",0);
    $pdf->cell(25,$alt,'_____/_____/_______',0,0,"C",0);
    $pdf->cell(5,$alt,'',0,0,'C',0);
    $pdf->cell(10,$alt,'',1,0,"C",0);
    $pdf->cell(5,$alt,'',0,0,'C',0);
    $pdf->cell(5,$alt,'',0,0,'C',0);
    $pdf->cell(10,$alt,'',1,0,"C",0);
    $pdf->cell(5,$alt,'',0,0,'C',0);
    $pdf->cell(5,$alt,'',0,0,'C',0);
    $pdf->cell(10,$alt,'',1,0,"C",0);
    $pdf->cell(5,$alt,'',0,1,'C',0);
  }
  $pdf->ln();

###################################################################################

  $pdf->setfont('arial','b',12);
  $pdf->cell(0,$alt,'BLOCO 04 : AFASTAMENTOS / LICENÇAS',0,1,"L",1);
  $pdf->ln();
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(5,3,'',0,1,'C',0);
  $pdf->cell(40,$alt,'Encontra-se afastado?',0,0,"L",0);
  $pdf->setfont($fonte02,'',$tam02);
  $pdf->cell(5,$alt,'',0,0,'C',0);
  $pdf->cell(10,$alt,'',1,0,"C",0);
  $pdf->cell(5,$alt,'',0,0,'C',0);
  $pdf->cell(30,$alt,'1-Sim    2-Não',0,0,'L',0);
  $pdf->cell(5,$alt,'',0,0,'C',0);
  $pdf->cell(10,$alt,'',1,0,"C",0);
  $pdf->cell(5,$alt,'',0,0,'C',0);
  $pdf->cell(30,$alt,'Código',0,1,'L',0);
  $pdf->setfont($fonte02,'',$tam02);
  $pdf->ln();
  $pdf->cell(100,$alt,'Motivo : ___________________________________________________________________   Desde : _______/_______/________',0,1,"L",0);
  $pdf->ln();

###################################################################################

  $pdf->setfont('arial','b',12);
  $pdf->cell(0,$alt,'BLOCO 05 : TEMPOS ANTERIORES',0,1,"L",1);
  $pdf->ln();
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(90,$alt,'Empresa',0,0,"C",0);
  $pdf->cell(50,$alt,'Período',0,0,"C",0);
  $pdf->cell(50,$alt,'Cargo',0,1,"C",0);
  $pdf->setfont($fonte02,'',$tam02);

  $pdf->setfont($fonte01,$b01,$tam01);
  for($x=0;$x<4;$x++){
    $pdf->setfont($fonte02,'',$tam02);
    $pdf->cell(5,3,'',0,1,'C',0);
    $pdf->cell(90,$alt,'______________________________________________________',0,0,"L",0);
    $pdf->cell(50,$alt,'____/____/______ a ____/____/______',0,0,"C",0);
    $pdf->cell(50,$alt,'____________________________',0,1,"C",0);
  }
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(5,3,'',0,1,'C',0);
  $pdf->cell(40,$alt,'Total do tempo de serviço:',0,0,"L",0);
  $pdf->setfont($fonte02,'',$tam02);
  $pdf->cell(40,$alt,'____________ anos, ____________ meses e __________ dias.',0,1,"L",0);
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(30,$alt,'OBSERVAÇÃO : ',0,0,"L",0);
  $pdf->setfont($fonte02,'',$tam02);
  $pdf->cell(50,$alt,'Encontra-se averbado no Município ? ',0,0,"L",0);
  $pdf->cell(5,$alt,'',0,0,'C',0);
  $pdf->cell(10,$alt,'',1,0,"C",0);
  $pdf->cell(5,$alt,'',0,0,'C',0);
  $pdf->cell(30,$alt,'1-Sim    2-Não',0,1,'L',0);

  $pdf->ln();

###################################################################################

  $pdf->setfont('arial','b',12);
  $pdf->cell(0,$alt,'BLOCO 06 : CURSOS DE APERFEIÇOAMENTO',0,1,"L",1);
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(5,3,'',0,1,'C',0);
  $pdf->cell(60,$alt,'Possui cursos de aperfeiçoamento?',0,0,"L",0);
  $pdf->setfont($fonte02,'',$tam02);
  $pdf->cell(5,$alt,'',0,0,'C',0);
  $pdf->cell(10,$alt,'',1,0,"C",0);
  $pdf->cell(5,$alt,'',0,0,'C',0);
  $pdf->cell(30,$alt,'1-Sim    2-Não',0,0,'L',0);
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(20,$alt,'Quantos?',0,0,"L",0);
  $pdf->setfont($fonte02,'',$tam02);
  $pdf->cell(5,$alt,'',0,0,'C',0);
  $pdf->cell(10,$alt,'',1,0,"C",0);
  $pdf->cell(5,$alt,'',0,1,'C',0);
  $pdf->cell(0,$alt,'','B',1,'C',0);
  $pdf->ln();

###################################################################################

///  $pdf->setfont('arial','b',12);
///  $pdf->cell(0,$alt,'BLOCO 07 : DADOS DO CADASTRADOR',0,1,"L",1);
  $pdf->setfont($fonte01,$b01,$tam01);
  $pdf->cell(5,5,'',0,1,'C',0);
  $pdf->cell(40,12,'NOME DO RECADASTRADOR : ___________________________________________________________________________',0,0,"L",0);
  $pdf->ln();
  $pdf->cell(40,12,'DATA DO RECADASTRAMENTO: ________/________/________',0,0,"L",0);
  $pdf->ln();
  $pdf->ln();
  $pdf->cell(60,5,'',0,0,"L",0);
  $pdf->cell(70,5,'_________________________________________________________',0,1,"C",0);
  $pdf->cell(60,2,'',0,0,"L",0);
  $pdf->cell(70,2,$z01_nome,0,0,"C",0);
}

$pdf->Output();
?>