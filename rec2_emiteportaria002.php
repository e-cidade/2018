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

include("fpdf151/scpdf.php");
include("libs/db_sql.php");
include ("fpdf151/assinatura.php");

$classinatura = new cl_assinatura;
$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

//$head3 = "CADASTRO DE CÓDIGOS";
//$head5 = "PERÍODO : ".$mes." / ".$ano;

$sql = "

select rh01_regist,
      z01_nome,
      h31_dtportaria,
      h31_numero,
      h31_anousu,
      rh01_numcgm , 
      z01_ident, 
      z01_ender, 
      z01_numero, 
      z01_compl, 
      z01_bairro,
      z01_cep, 
      z01_munic, 
      rh01_admiss,
      case rh30_regime when 1 then 'estatutario'
                       when 2 then 'clt'
		       when 3 then 'cc'
      end as regime,
      rh37_descr,
      rh55_descr,
      r02_descr,
      h12_descr,
      trim(h31_amparolegal) as h31_amparolegal ,
      h16_histor,
      h16_hist2,
      h40_descr,
      h42_descr,
      h16_dtconc,
      h31_portariatipo
from portaria 
     inner join portariaassenta     on h31_sequencial   = h33_portaria 
     inner join portariatipo        on h30_sequencial   = h31_portariatipo
     inner join portariaenvolv      on h42_sequencial   = h30_portariaenvolv
     inner join portariaproced      on h40_sequencial   = h30_portariaproced
     inner join assenta             on h33_assenta      = h16_codigo
     inner join tipoasse            on h16_assent       = h12_codigo
     inner join rhpessoal           on h16_regist       = rh01_regist 
     inner join cgm                 on rh01_numcgm      = z01_numcgm 
     left join rhpessoalmov         on rh02_regist      = rh01_regist
                                   and rh02_anousu      = ".db_anofolha()."
				   and rh02_mesusu      = ".db_mesfolha()."
     inner join rhfuncao            on rh37_funcao      = rh01_funcao 
                                    and rh37_instit     = rh02_instit
     left  join rhregime            on rh30_codreg      = rh02_codreg
                                   and rh30_instit      = rh02_instit
     left  join rhpeslocaltrab      on rh56_seqpes      = rh02_seqpes
                                   and rh56_princ       = 't'
     left join rhlocaltrab          on rh55_codigo      = rh56_localtrab
     left join rhpespadrao          on rh03_seqpes      = rh02_seqpes
     left join padroes              on r02_anousu       = rh02_anousu
                                   and r02_mesusu       = rh02_mesusu
				   and r02_regime       = rh30_regime
				   and trim(r02_codigo) = trim(rh03_padrao)
				   and r02_instit       = rh02_instit

where h31_sequencial = $port;
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
//db_criatabela($result);exit;
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);

}

$pdf = new SCPDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
db_sel_instit();
$xlin = 10;
$pdf->SetLineWidth(0.4);
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   $dados = pg_exec($conn,"select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));
   $pdf->addpage();
   $pdf->Image('imagens/files/'.pg_result($dados,0,"logo"),20,$xlin -7, 25);
   //$pdf->Image('imagens/files/logo_boleto.png', 20, $xlin -7, 25); //.$this->logo
   $pdf->Setfont('Arial', 'B', 9);
   $pdf->text(43, $xlin, $nomeinst);
   $pdf->Setfont('Arial', '', 7);
   $pdf->text(43, $xlin +4, 'SECRETARIA DE GOVERNO');
   $pdf->text(43, $xlin +8, 'DIVISÃO DE RECURSOS HUMANOS' );
   $pdf->Setfont('Arial', 'B', 15);
   if($h31_portariatipo == 412 && strtoupper($munic) == 'ALEGRETE' ){
     $pdf->text(160, $xlin, '  AVISO');
   }else{
     $pdf->text(160, $xlin, 'PORTARIA');
   }
   $pdf->Setfont('Arial', 'B', 15);
   $pdf->text(162, $xlin +6, $h31_numero.'/'.$h31_anousu);
   $pdf->Setfont('Arial', 'BI',11);
   $pdf->text(110 , $xlin +30, 'O Prefeito Municipal de '.ucfirst(strtolower($munic)));
   $pdf->text(110 , $xlin +34, 'no uso de suas atribuições legais,');
   $pdf->text(110 , $xlin +38, 'resolve:');
   


   
   $pdf->rect(10,$xlin +55,190,20) ;
   $pdf->Setfont('Arial', 'B', 7);
   $pdf->text(10 , $xlin +54, 'IDENTIFICAÇÃO');
   $pdf->Setfont('Arial', '', 5);
   $pdf->text(12  , $xlin +57, 'NOME');
   $pdf->text(120 , $xlin +57, 'C.G.M.');
   $pdf->text(140 , $xlin +57, 'MATRÍCULA');
   $pdf->text(160 , $xlin +57, 'I.E./R.G.');
   $pdf->Setfont('Arial', '', 8);
   $pdf->text(12  , $xlin +60, $z01_nome);
   $pdf->text(120 , $xlin +60, $rh01_numcgm);
   $pdf->text(140 , $xlin +60, $rh01_regist);
   $pdf->text(160 , $xlin +60, $z01_ident);
   
   $pdf->Setfont('Arial', '', 5);
   $pdf->text(12  , $xlin +63, 'ENDEREÇO');
   $pdf->text(120 , $xlin +63, 'CEP');
   $pdf->Setfont('Arial', '', 8);
   $pdf->text(12  , $xlin +66, $z01_ender.', '.$z01_numero.'  '.$z01_compl);
   $pdf->text(120 , $xlin +66, $z01_cep);
   
   $pdf->Setfont('Arial', '', 5);
   $pdf->text(12  , $xlin +69, 'BAIRRO');
   $pdf->text(120 , $xlin +69, 'CIDADE');
   $pdf->Setfont('Arial', '', 8);
   $pdf->text(12  , $xlin +72, $z01_bairro);
   $pdf->text(120 , $xlin +72, $z01_munic);
   
   
   $pdf->rect(10,$xlin +80,190,20);
   $pdf->Setfont('Arial', 'B', 7);
   $pdf->text(10 , $xlin +79, 'LOTAÇÃO');
   $pdf->Setfont('Arial', '', 5);
   $pdf->text(12  , $xlin +82, 'ADMISSÃO');
   $pdf->text(50  , $xlin +82, 'VÍNCULO');
   $pdf->text(100 , $xlin +82, 'REGIME');
   $pdf->Setfont('Arial', '', 8);
   $pdf->text(12  , $xlin +85, db_formatar($rh01_admiss,'d'));
   $pdf->text(50  , $xlin +85, '');
   $pdf->text(100 , $xlin +85, $regime);
   
   $pdf->Setfont('Arial', '', 5);
   $pdf->text(12  , $xlin +88, 'CARGO');
   $pdf->text(100 , $xlin +88, 'ÓRGÃO');
   $pdf->text(150 , $xlin +88, 'NÍVEL');
   $pdf->Setfont('Arial', '', 8);
   $pdf->text(12  , $xlin +91, $rh37_descr);
   $pdf->text(100 , $xlin +91, '');
   $pdf->text(150 , $xlin +91, '');
   
   $pdf->Setfont('Arial', '', 5);
   $pdf->text(12  , $xlin +94, 'FUNÇÃO');
   $pdf->text(100 , $xlin +94, 'SEÇÃO');
   $pdf->text(150 , $xlin +94, 'CLASSE/PADRÃO');
   $pdf->Setfont('Arial', '', 8);
   $pdf->text(12  , $xlin +97, '');
   $pdf->text(100 , $xlin +97, $rh55_descr);
   $pdf->text(150 , $xlin +97, $r02_descr);
  
  
   $pdf->rect(10,$xlin +105,190,20);
   $pdf->Setfont('Arial', 'B', 7);
   $pdf->text(10  , $xlin +104, 'PEDIDO');
   $pdf->Setfont('Arial', '', 5);
   $pdf->text(12  , $xlin +107, 'PROCESSO');
   $pdf->text(50  , $xlin +107, 'DATA DE ENTREGA');
   $pdf->text(100 , $xlin +107, 'TIPO DE PROCESSO');
   $pdf->Setfont('Arial', '', 8);
   $pdf->text(12  , $xlin +111, '');
   $pdf->text(50  , $xlin +111, '');
   $pdf->text(100 , $xlin +111, '');
   
   $pdf->Setfont('Arial', '', 5);
   $pdf->text(12  , $xlin +114, 'PROCEDIMENTO');
   $pdf->text(50  , $xlin +114, 'A CONTAR DE');
   $pdf->text(100 , $xlin +114, 'DESCRIÇÃO');
   $pdf->Setfont('Arial', '', 8);
   $pdf->text(12  , $xlin +117, $h40_descr);
   $pdf->text(50  , $xlin +117, db_formatar($h16_dtconc,'d'));
   $pdf->text(100 , $xlin +117, $h12_descr);

   $pdf->Setfont('Arial', '', 5);
   $pdf->text(12  , $xlin +120, 'ENVOLVIMENTO');
   $pdf->Setfont('Arial', '', 8);
   $pdf->text(12  , $xlin +123, $h42_descr);
   

   $pdf->Setfont('Arial', 'B', 7);
   $pdf->text(10 , $xlin +129, 'AMPARO LEGAL');
   $pdf->rect(10,$xlin +130,190,20);
   $pdf->setxy(12,$xlin+132);
   $pdf->Setfont('Arial', '', 8);
   $pdf->multicell(0,5,strtoupper($h31_amparolegal),0,1,"J",0);
   
   $pdf->Setfont('Arial', 'B', 7);
   $pdf->text(10 , $xlin +154, 'INFORMAÇÕES');
   $pdf->rect(10,$xlin +155,190,40);
   $pdf->setxy(12,$xlin+157);
   $pdf->Setfont('Arial', '', 8);
   $pdf->multicell(0,5,trim($h16_histor).trim($h16_hist2),0,1,"J",0);

   $ass_pref = "_________________________________________________"."\n\n"."Prefeito Municipal";
   $ass_sec  = "_________________________________________________"."\n\n"."Sec. de Administração";
   $ass_dir  = "_________________________________________________"."\n\n"."Diretor de Recusos Humanos";
   $ass_func = $z01_nome;

   $ass1_p_default  = "_____________________________"."\n\n"."Prefeito Municipal";
   $ass2_p_default  = "_____________________________"."\n\n"."Sec. de Governo";
   $ass1_a_default  = "_____________________________"."\n\n"."Diretor Recursos Humanos";

   $ass1_p  = $classinatura->assinatura(12, $ass1_p_default);
   $ass2_p  = $classinatura->assinatura(13, $ass2_p_default);
   $ass1_a  = $classinatura->assinatura(14, $ass1_a_default);
//   $ass_func= $classinatura->assinatura_usuario();

   $largura = ($pdf->w) / 3;

   $pdf->Setfont('Arial', '', 10);
   if(strtoupper($munic) == 'ALEGRETE' ){
     $pdf->text(10 , $xlin +205, 'PALÁCIO RUI RAMOS, em '.$munic.', '.db_formatar($h31_dtportaria,'d') );
     $pdf->text(10 , $xlin +215, 'Registre-se e publique-se:' );
     if($h31_portariatipo == 412 ){
       $pdf->setxy(135, $xlin +225);
       $pdf->multicell($largura, 2, $ass_func, 0, "C", 0, 0);
       $pdf->setxy(10, $xlin +250);
       $pdf->multicell(80, 2, $ass1_a, 0, "C", 0, 0);
     }else{
       $pdf->setxy(135, $xlin +225);
       $pdf->multicell($largura, 2, $ass1_p, 0, "C", 0, 0);
       $pdf->setxy(10, $xlin +250);
       $pdf->multicell(80, 2, $ass2_p, 0, "C", 0, 0);
     }
   }else{
     $pdf->text(10 , $xlin +205, $munic.', '.db_formatar($h31_dtportaria,'d') );
     $pdf->text(10 , $xlin +215, 'Registre-se e publique-se:' );
     $pdf->setxy(135, $xlin +225);
     $pdf->multicell($largura, 2, $ass1_p, 0, "C", 0, 0);
     $pdf->setxy(10, $xlin +250);
     $pdf->multicell(80, 2, $ass2_p, 0, "C", 0, 0);
   }
}
$pdf->Output();
   
?>