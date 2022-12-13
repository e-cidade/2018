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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

//phpinfo();
//echo $_SERVER['PHP_SELF'];
$exercicio = db_getsession("DB_anousu");
$borda = 1; 
$bordat = 1;
$preenc = 0;
$TPagina = 57;

  ///////////////////////////////////////////////////////////////////////

//$exercicio = 2003;
$xmod = '';
if (isset($xarquivo)) {
   $xmod = " where c.codarq = $xarquivo ";
}

if (isset($xmodulo)) {
   $xmod = " where a.codmod = $xmodulo ";
}

  $sql=" 
	select trim(nomemod) as modulo,
               c.codarq, 
               trim(c.nomearq) as arquivo,
               trim(c.descricao) as descricao,
               d.seqarq,
               trim(e.nomecam) as campo,
               trim(e.rotulo) as rotulo,
               trim(e.conteudo) as conteudo,
               e.tamanho,
               e.nulo,
               e.maiusculo,
               e.aceitatipo,
               f.sequen as seq_prikey,
               g.sequen as seq_forkey,
               trim(h.nomearq) as nome_arqreferen,
               trim(j.nomecam) as campopai
	from db_sysmodulo a 
	     inner join db_sysarqmod           b on a.codmod=b.codmod 
	     inner join db_sysarquivo          c on c.codarq=b.codarq
	     inner join db_sysarqcamp          d on d.codarq=c.codarq
	     inner join db_syscampo   	       e on e.codcam=d.codcam
	     left outer join db_sysprikey      f on f.codarq=c.codarq and f.codcam=e.codcam
	     left outer join db_sysforkey      g on g.codarq=c.codarq and g.codcam=e.codcam
	     left outer join db_sysarquivo     h on h.codarq=g.referen
	     left outer join db_syscampodep    i on i.codcam=e.codcam
	     left outer join db_syscampo       j on j.codcam=i.codcampai
$xmod	order by modulo,arquivo,d.seqarq
";

$result=pg_exec($sql);
if ( pg_numrows($result) == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro= Problema na estrutura, não retornou nenhum registro na seleção.');
}

$head4 = "RELATÓRIO DA ESTRUTURA DO SISTEMA";
$pdf = new PDF(); // abre a classe
$pdf->Open('L'); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',6);

$bordat = 1;
$preenc = 0;
$xxarq = 0;
$xmod = pg_result($result,0,"modulo");
$xarq = pg_result($result,0,"arquivo");
$xdescr = pg_result($result,0,"descricao");

$pdf->SetFont('Arial','B',8);
$pdf->multicell(0,4,"Módulo  : ".strtoupper($xmod),0,"L",$preenc);
$pdf->ln(3);
$pdf->SetFont('Arial','B',8);
$pdf->multicell(0,4,"Arquivo  : ".strtoupper($xarq)." - ".$xdescr,0,"L",$preenc);
$pdf->ln(1);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(6,4,"SEQ",$bordat,0,"C",1);
$pdf->Cell(25,4,"CAMPO",$bordat,0,"C",1);
$pdf->Cell(50,4,"ROTULO",$bordat,0,"C",1);
$pdf->Cell(20,4,"TIPO",$bordat,0,"C",1);
$pdf->Cell(6,4,"TAM",$bordat,0,"C",1);
$pdf->Cell(6,4,"NULO",$bordat,0,"C",1);
$pdf->Cell(6,4,"MAI",$bordat,0,"C",1);
$pdf->Cell(6,4,"SCR",$bordat,0,"C",1);
$pdf->Cell(6,4,"PK",$bordat,0,"C",1);
$pdf->Cell(6,4,"FK",$bordat,0,"C",1);
$pdf->Cell(25,4,"ARQ.REF",$bordat,0,"C",1);
$pdf->Cell(25,4,"CAMPO PAI",$bordat,1,"C",1);
$pdf->ln(3);

for($i = 0;$i < pg_numrows($result);$i++){
   db_fieldsmemory($result,$i);
   if ($xmod != $modulo){
	$pdf->ln(3);
        $pdf->SetFont('Arial','B',8);
	$pdf->multicell(0,4,"Módulo  : ".strtoupper($modulo),0,"L",$preenc);
        $pdf->ln(3);
   }
        
   if ($xarq != $arquivo){
        $sqlind = "select a.*,b.*,c.nomecam
		from db_sysindices a
			inner join db_syscadind b on a.codind = b.codind
			inner join db_syscampo  c on c.codcam = b.codcam
		where codarq = $xxarq
                order by nomeind,sequen";
        $resindice = pg_exec($sqlind);
        $pdf->SetFont('Arial','B',8);
        if ( pg_numrows($resindice) != 0 ) {
           $prinome = '';
           $espaco = '';
           $xnome = ''; 
           for ($iind = 0;$iind < pg_numrows($resindice);$iind++){
               db_fieldsmemory($resindice,$iind);
               if ($prinome != $nomeind){
                   $xnome .= $espaco.$nomeind."   -   Campos :  ";
                   $espaco = '#';
                   $virgula = '';
 	       }
               $xnome .= $virgula.$nomecam;
               $virgula = ', '; 
               $prinome = $nomeind;
           }
           $matrizind = split('#',$xnome);
           $pdf->multicell(0,4,'Índices do arquivo : ',0,"L",$preenc);

           for( $xind = 0 ;$xind < sizeof($matrizind); $xind++ ){
                $pdf->multicell(0,4,$matrizind[$xind],0,"L",$preenc);
           }
        }else{
	   $pdf->multicell(0,4,"Arquivo  sem índice cadastrado",0,"L",$preenc);
        }
	$pdf->ln(3);
        $pdf->SetFont('Arial','B',8);
	$pdf->multicell(0,4,"Arquivo  : ".strtoupper($arquivo)." - ".$descricao,0,"L",$preenc);
        $pdf->ln(1);
        $pdf->SetFont('Arial','B',6);
	$pdf->Cell(6,4,"SEQ",$bordat,0,"C",1);
	$pdf->Cell(25,4,"CAMPO",$bordat,0,"C",1);
	$pdf->Cell(50,4,"ROTULO",$bordat,0,"C",1);
	$pdf->Cell(20,4,"TIPO",$bordat,0,"C",1);
	$pdf->Cell(6,4,"TAM",$bordat,0,"C",1);
	$pdf->Cell(6,4,"NULO",$bordat,0,"C",1);
	$pdf->Cell(6,4,"MAI",$bordat,0,"C",1);
	$pdf->Cell(6,4,"SCR",$bordat,0,"C",1);
	$pdf->Cell(6,4,"PK",$bordat,0,"C",1);
	$pdf->Cell(6,4,"FK",$bordat,0,"C",1);
	$pdf->Cell(25,4,"ARQ.REF",$bordat,0,"C",1);
	$pdf->Cell(25,4,"CAMPO PAI",$bordat,1,"C",1);
   }

   if ( $pdf->gety() > $pdf->h - 30 ){
        $pdf->addpage();
	$pdf->ln(3);
        $pdf->SetFont('Arial','B',8);
	$pdf->multicell(0,4,"Módulo  : ".strtoupper($modulo),0,"L",$preenc);
        $pdf->ln(3);
	$pdf->ln(3);
        $pdf->SetFont('Arial','B',8);
	$pdf->multicell(0,4,"Arquivo  : ".strtoupper($arquivo)." - ".$descricao,0,"L",$preenc);
        $pdf->ln(1);
        $pdf->SetFont('Arial','B',6);
	$pdf->Cell(6,4,"SEQ",$bordat,0,"C",1);
	$pdf->Cell(25,4,"CAMPO",$bordat,0,"C",1);
	$pdf->Cell(50,4,"ROTULO",$bordat,0,"C",1);
	$pdf->Cell(20,4,"TIPO",$bordat,0,"C",1);
	$pdf->Cell(6,4,"TAM",$bordat,0,"C",1);
	$pdf->Cell(6,4,"NULO",$bordat,0,"C",1);
	$pdf->Cell(6,4,"MAI",$bordat,0,"C",1);
	$pdf->Cell(6,4,"SCR",$bordat,0,"C",1);
	$pdf->Cell(6,4,"PK",$bordat,0,"C",1);
	$pdf->Cell(6,4,"FK",$bordat,0,"C",1);
	$pdf->Cell(25,4,"ARQ.REF",$bordat,0,"C",1);
	$pdf->Cell(25,4,"CAMPO PAI",$bordat,1,"C",1);
   }
   $pdf->SetFont('Arial','',6);
   $pdf->Cell(6,4,$seqarq,$borda,0,"C",0);
   $pdf->Cell(25,4,$campo,$borda,0,"L",0);
   $pdf->Cell(50,4,$rotulo,$borda,0,"L",0);
   $pdf->Cell(20,4,$conteudo,$borda,0,"L",0);
   $pdf->Cell(6,4,$tamanho,$borda,0,"C",0);
   $pdf->Cell(6,4,$nulo,$borda,0,"C",0);
   $pdf->Cell(6,4,$maiusculo,$borda,0,"C",0);
   $pdf->Cell(6,4,$aceitatipo,$borda,0,"C",0);
   $pdf->Cell(6,4,$seq_prikey,$borda,0,"C",0);
   $pdf->Cell(6,4,$seq_forkey,$borda,0,"C",0);
   $pdf->Cell(25,4,$nome_arqreferen,$borda,0,"L",0);
   $pdf->Cell(25,4,$campopai,$borda,1,"L",0);

   $xmod = $modulo;
   $xarq = $arquivo;
   $xxarq = $codarq;

}
$pdf->Output();
?>