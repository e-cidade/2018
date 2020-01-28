<?
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

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_sql.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_escrito_classe.php"));
include(modification("classes/db_issquant_classe.php"));
$clescrito = new cl_escrito;
$clissquant = new cl_issquant;

db_postmemory($HTTP_POST_VARS);

$sql="select issbase.*,
cg.z01_nome,
cg.z01_numero,
cg.z01_email,
cg.z01_telef,
cg.z01_cep,
cg.z01_telcel,
cg.z01_ident,
cg.z01_bairro,
cg.z01_munic,
cg.z01_compl,
             cg.z01_numcgm,
	           cg.z01_ender,
        		 cg.z01_incest,
	           c.z01_nome as escritorio,
	           cg.z01_nomefanta,
             j14_nome,
					   j13_descr ,
					   q02_numero,
					   q02_compl,
					   q05_matric,
					   q14_proces,
					   cg.z01_cgccpf
      from issbase
             inner join cgm cg on cg.z01_numcgm = q02_numcgm
             left outer join issruas on issbase.q02_inscr = issruas.q02_inscr
             left outer join ruas on ruas.j14_codigo = issruas.j14_codigo
             left outer join issbairro on issbase.q02_inscr = q13_inscr
             left outer join bairro on j13_codi = q13_bairro
             left outer join escrito on issbase.q02_inscr = q10_inscr
             left outer join cgm c on c.z01_numcgm = q10_numcgm
             left outer join issmatric on issbase.q02_inscr = q05_inscr
             left outer join issprocesso on issbase.q02_inscr = q14_inscr
      where issbase.q02_inscr = $inscr";
//die($sql);
$result  = db_query($sql) or die($sql);
$numrows = pg_num_rows($result);

if($numrows>0){
  db_fieldsmemory($result,0,true);
}
if($numrows==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.");
}
//die($sql);exit;
//_criatabela($result);
//it;
$head4 = "BIC Alvara";
$head5 = "Inscriçao: {$inscr}";
$head6 = "CGM: {$z01_numcgm}";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$alt = 4;
$pri = true;

for ($i = 0;$i < $numrows;$i++){
 db_fieldsmemory($result,$i);

 if (($pdf->gety() > $pdf->h -30)  || $pri==true ){
     $pdf->addpage("");
     $pdf->setfillcolor(235);
     $titulo = 9;
     $texto = 8;

// novo dados cadastrais do CGM

     $pdf->setX(5);
     $pdf->SetFont('Arial','B',$titulo);
     $pdf->Cell(200,4,"Dados Cadastrais do CGM","LRBT",1,"C",0);
     $pdf->setX(5);
     $pdf->Cell(200,4,"","",1,"C",0);

     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Nome:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_nome","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"CNPJ/CPF:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_cgccpf","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Endereço:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_ender, N° $z01_numero","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Complemento:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_compl","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Bairro:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_bairro","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Fone:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_telef/$z01_telcel","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Cidade:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_munic","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"E-mail:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_email","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Cep:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_cep","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->Cell(60,10,"","",1,"L",0);

// fim
}

 if (($pdf->gety() > $pdf->h -30)  || $pri==true ){
     //$pdf->addpage("");
     $pdf->setfillcolor(235);
     $titulo = 9;
     $texto = 8;

     //lado esquerdo da tela
     $pdf->setX(5);
     $pdf->SetFont('Arial','B',$titulo);
     $pdf->Cell(200,4,"Dados Cadastrais do Alvará","LRBT",1,"C",0);
     $pdf->setX(5);
     $pdf->Cell(200,4,"","",1,"C",0);

     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Inscrição Municipal:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$inscr","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);
     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"CNPJ/CPF:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_cgccpf","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Nome:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_nome","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);
     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Endereço:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_ender","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Nome Fantasia:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_nomefanta","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);
     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Registro na junta:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$q02_regjuc","",1,"L",0);
     //
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     //
     $pdf->Cell(30,4,"Referência Anterior:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$q02_inscmu","",0,"L",0);
     //
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Data da Junta:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,db_formatar($q02_dtjunta,"d"),"",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     //
     $pdf->Cell(30,4,"Inscrição Estadual:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_incest","",0,"L",0);
     //
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Data de Baixa:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,db_formatar($q02_dtbaix,"d"),"",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);


     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Data Inicial:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,db_formatar($q02_dtinic,"d"),"",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Numero:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$q02_numero","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);


     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Rua:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$j14_nome","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Complemento:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$q02_compl","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);


     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Bairro:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$j13_descr","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Processo:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$q14_proces","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);


     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Matricula:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$q05_matric","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     $result_escritorio = $clescrito->sql_record($clescrito->sql_query(null,"q10_numcgm as cgm_esc,a.z01_nome as nome_esc",null,"q10_inscr = $inscr"));
     if($clescrito->numrows>0){
     	db_fieldsmemory($result_escritorio,0);
     	$escri = @$cgm_esc." - ".@$nome_esc;
     }

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Escritório:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,@$escri,"",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

     $result_area=$clissquant->sql_record($clissquant->sql_query_file(null,$q02_inscr,"q30_area,q30_quant",null," q30_inscr = $q02_inscr and q30_anousu = ".db_getsession('DB_anousu')));
     if ($clissquant->numrows>0){
     	db_fieldsmemory($result_area,0);
     }

     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Área:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,@$q30_area,"",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Identidade:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_ident","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"E-mail:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_email","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Cep:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_cep","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);


     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Fone:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$z01_telef/$z01_telcel","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);


     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Empregados:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,@$q30_quant,"",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

     $sqlzona    = "select * from isszona inner join zonas on j50_zona = q35_zona where q35_inscr = $q02_inscr";
     $resultzona = db_query($sqlzona);
     $linhaszona = pg_num_rows($resultzona);
     if($linhaszona>0){
       db_fieldsmemory($resultzona,0);
     }
     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Zona Fiscal:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,@$q35_zona."-".@$j50_descr,"",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);


     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Data do cadastro:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,db_formatar($q02_dtcada,"d"),"",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);
  }
}

$pdf->Cell(60,5,"","",1,"L",0);
if((isset($q02_obs) && $q02_obs != "") || (isset($q02_memo) && $q02_memo != "")){
	  $pdf->MultiCell(200,5,"Observações - ".@$q02_obs.". ".@$q02_memo,"","J",0,0);
}

$sql = "select q07_ativ,
               q03_descr,
							 q07_datain,
							 q07_datafi,
							 q07_databx,
		           q07_quant,
							 tabativbaixa.*,
							 case when q88_inscr is null then 'S'::char(1) else 'P'::char(1) end as q88_tipo,
										 q11_processo,
							 case when q11_oficio = 'true' then 'NORMAL'
													when q11_oficio = 'false' then 'OFICIO'
							else '' end as q11_oficio
					from tabativ
							inner join ativid on q07_ativ = q03_ativ
							left join ativprinc on ativprinc.q88_inscr = tabativ.q07_inscr and ativprinc.q88_seq = tabativ.q07_seq
							left join tabativbaixa on tabativ.q07_inscr = tabativbaixa.q11_inscr and tabativ.q07_seq = tabativbaixa.q11_seq
	  		where q07_inscr = $inscr
        order by case when q88_inscr is null then 2 else 1 end, q07_datain, q07_datafi
        ";
//die ($sql);
$result  = db_query($sql);
$numrows = pg_num_rows($result);
$pdf->Cell(180,3,"","",1,"L",0);
$pdf->Cell(200,4,"","",1,"C",0);
$pdf->setX(5);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(200,4,"Atividades","LRBT",1,"C",0);
$pdf->setX(5);
$pdf->Cell(200,4,"","",1,"C",0);

if($numrows <> 0){
   $pdf->setX(10);
   $pdf->SetFont('Arial','',$titulo);
   $pdf->cell(15,4,"Cod.",0,0,"C",1);
   $pdf->cell(100,4,"Atividade",0,0,"C",1);
   $pdf->cell(6,4,"Tipo",0,0,"C",1);
   $pdf->cell(25,4,"Data Inicio",0,0,"C",1);
   $pdf->cell(25,4,"Data Fim",0,0,"C",1);
   $pdf->cell(25,4,"Data Baixa",0,1,"C",1);

   for ($i = 0;$i < $numrows;$i++){
      db_fieldsmemory($result,$i);
      $pdf->setX(10);
      $pdf->SetFont('Arial','',$texto);
      $pdf->cell(15,4,"$q07_ativ",0,0,"C",0);
      $iPosX = $pdf->GetX();
      $iPosY = $pdf->GetY();
      $pdf->multicell(100,4,"$q03_descr",0,"L","L",0);
      $iPosYAtual = $pdf->getY();
      $iAltura = $iPosYAtual - $iPosY;
      $pdf->SetXY($iPosX + 100,$iPosY);
      $pdf->cell(6,$iAltura,$q88_tipo,0,0,"L",0);
      $pdf->cell(25,$iAltura,db_formatar($q07_datain,"d"),0,0,"C",0);
      $pdf->cell(25,$iAltura,db_formatar($q07_datafi,"d"),0,0,"C",0);
      $pdf->cell(25,$iAltura,db_formatar($q07_databx,"d"),0,1,"C",0);
			if(isset($q11_obs) && $q11_obs != ""){
          $pdf->multicell(190,4,"Observações da baixa - $q11_obs  ",0,"L","L",0);
      }
   }
}else{
  $pdf->cell(190,4,"NÃO POSSUI ATIVIDADE",0,1,"C",0);
}

$sql="select cgmsocio.z01_numcgm,
             cgmsocio.z01_nome,
	     cgmsocio.z01_ender,
	     cgmsocio.z01_munic,
	     q95_perc
      from issbase
	     inner join socios on q95_cgmpri = q02_numcgm
	     inner join cgm cgmsocio on cgmsocio.z01_numcgm = q95_numcgm
	     inner join cgm cgmempresa on cgmempresa.z01_numcgm = q02_numcgm where q95_tipo = 1 and q02_inscr =$inscr";
$result = db_query($sql);
$numrows = pg_num_rows($result);
$pdf->Cell(200,4,"","",1,"C",0);
$pdf->setY($iPosY+25);
$pdf->setX(5);
$pdf->SetFont('Arial','B',$titulo);
$pdf->Cell(200,4,"Sócios","LRBT",1,"C",0);
$pdf->setX(5);
$pdf->Cell(200,4,"","",1,"C",0);

if($numrows <> 0){
   $pdf->setX(10);
   $pdf->SetFont('Arial','',$titulo);
   $pdf->cell(10,4,"CGM",0,0,"C",1);
   $pdf->cell(68,4,"Nome",0,0,"C",1);
   $pdf->cell(68,4,"Endereço",0,0,"C",1);
   $pdf->cell(30,4,"Municipio",0,0,"C",1);
   $pdf->cell(14,4,"Percentual",0,1,"C",1);


   for ($i = 0;$i < $numrows;$i++){
      db_fieldsmemory($result,$i);
      $pdf->setX(10);
            $pdf->SetFont('Arial','',$texto);
      $pdf->cell(10,4,"$z01_numcgm",0,0,"C",0);
      $pdf->cell(68,4,"$z01_nome",0,0,"L",0);
      $pdf->cell(68,4,"$z01_ender",0,0,"L",0);
      $pdf->cell(30,4,"$z01_munic",0,0,"C",0);
      $pdf->cell(14,4,"$q95_perc",0,1,"C",0);
   }
}
else{
  $pdf->cell(190,4,"NÃO POSSUI SOCIOS",0,1,"C",0);
}

$sql     = "select * from aidof left join aidofproc on y02_aidof = y08_codigo  where y08_inscr=$inscr";
$result  = db_query($sql);
$numrows = pg_num_rows($result);

$pdf->Cell(200,4,"","",1,"C",0);
$pdf->setX(5);
$pdf->SetFont('Arial','B',$titulo);
$pdf->Cell(200,4,"Aidof","LRBT",1,"C",0);
$pdf->setX(5);
$pdf->Cell(200,4,"","",1,"C",0);

if ($numrows <> 0) {
   $pdf->setX(10);
   $pdf->SetFont('Arial','',$titulo);
   $pdf->cell(10,4,"Código",0,0,"C",1);
   $pdf->cell(20,4,"Processo",0,0,"C",1);
   $pdf->cell(30,4,"Data Lançamento",0,0,"C",1);
   $pdf->cell(20,4,"Nota Inicial",0,0,"C",1);
   $pdf->cell(30,4,"Quant. Solicitada",0,0,"C",1);
   $pdf->cell(30,4,"Quant. Liberada",0,0,"C",1);
   $pdf->cell(20,4,"Nota Final",0,0,"C",1);
   $pdf->cell(20,4,"Gráfica",0,0,"C",1);
   $pdf->cell(10,4,"Cancel.",0,1,"C",1);
   for ($i = 0; $i < $numrows;$i++) {
      db_fieldsmemory($result,$i);
      $pdf->setX(10);
      $pdf->SetFont('Arial','',$texto);
      $p=0;
      if ($y08_cancel=="t"){
      	$cancel="Sim";
      }else{
      	$cancel="Não";
      }
      $pdf->cell(10,4,$y08_codigo,0,0,"C",$p);
      $pdf->cell(20,4,$y02_codproc,0,0,"C",$p);
      $pdf->cell(30,4,db_formatar($y08_dtlanc,"d"),0,0,"C",$p);
      $pdf->cell(20,4,$y08_notain,0,0,"C",$p);
      $pdf->cell(30,4,$y08_quantsol,0,0,"C",$p);
      $pdf->cell(30,4,$y08_quantlib,0,0,"C",$p);
      $pdf->cell(20,4,$y08_notafi,0,0,"C",$p);
      $pdf->cell(20,4,$y08_numcgm,0,0,"C",$p);
      $pdf->cell(10,4,$cancel,0,1,"C",$p);
   }
}
else{
  $pdf->cell(190,4,"NÃO POSSUI AIDOF",0,1,"C",0);
}

/*
 * Bloco que testa se a empresa é optante do simples
 */

$sql  = "SELECT isscadsimples.q38_sequencial,                                                                          ";
$sql .= "       isscadsimples.q38_dtinicial,                                                                           ";
$sql .= "       CASE                                                                                                   ";
$sql .= "         WHEN isscadsimples.q38_categoria = 1 THEN 'Micro Empresa'                                            ";
$sql .= "         WHEN isscadsimples.q38_categoria = 2 THEN 'Empresa de pequeno porte'                                 ";
$sql .= "         WHEN isscadsimples.q38_categoria = 3 THEN 'MEI'                                                      ";
$sql .= "       END AS q38_categoria,                                                                                  ";
$sql .= "       isscadsimplesbaixa.q39_dtbaixa,                                                                        ";
$sql .= "       isscadsimplesbaixa.q39_issmotivobaixa,                                                                 ";
$sql .= "       isscadsimplesbaixa.q39_obs,                                                                            ";
$sql .= "       issmotivobaixa.q42_descr                                                                               ";
$sql .= "  FROM isscadsimples                                                                                          ";
$sql .= "       LEFT JOIN isscadsimplesbaixa ON isscadsimples.q38_sequencial  = isscadsimplesbaixa.q39_isscadsimples  ";
$sql .= "       LEFT JOIN issmotivobaixa     ON issmotivobaixa.q42_sequencial = isscadsimplesbaixa.q39_issmotivobaixa ";
$sql .= " WHERE isscadsimples.q38_inscr = {$inscr}                                                                     ";

$result  = db_query($sql);
$numrows = pg_num_rows($result);
$pdf->Cell(200,4,"","",1,"C",0);
$pdf->setX(5);
$pdf->SetFont('Arial','B',$titulo);
$pdf->Cell(200,4,"Optante Simples","LRBT",1,"C",0);
$pdf->setX(5);
$pdf->Cell(200,4,"","",1,"C",0);

if ($numrows <> 0) {

	$pdf->setX(10);
  $pdf->SetFont('Arial','',$titulo);
  $pdf->cell(10,4,"Código",0,0,"C",1);
  $pdf->cell(20,4,"Data Inicial",0,0,"C",1);
  $pdf->cell(30,4,"Categoria",0,0,"C",1);
  $pdf->cell(20,4,"Data da baixa",0,0,"C",1);
  $pdf->cell(40,4,"Motivo da baixa",0,0,"C",1);
  $pdf->cell(70,4,"Observações",0,1,"C",1);
  for ($i = 0; $i < $numrows; $i++) {
  	db_fieldsmemory($result,$i);
    $pdf->setX(10);
    $pdf->SetFont('Arial','',$texto);
    $p=0;
    $pdf->cell(10, 4, $q38_sequencial, 0, 0, "C", $p);
    $pdf->cell(20, 4, db_formatar($q38_dtinicial, 'd'), 0, 0, "C", $p);
    $pdf->cell(30, 4, $q38_categoria, 0, 0, "C", $p);
    $pdf->cell(20, 4, db_formatar($q39_dtbaixa, 'd'), 0, 0, "C", $p);
    $pdf->cell(40, 4, $q42_descr, 0, 0, "C", $p);
    $pdf->cell(70, 4, $q39_obs, 0, 1, "C", $p);
  }
} else {
	$pdf->cell(190,4,"Sem lançamentos",0,1,"C",0);
}

$pdf->Output();

?>
