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

require("libs/db_conecta.php");
include("libs/db_stdlib.php");
require("fpdf151/scpdf.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);



$result = db_query($conn,"select db_itbi.*,to_char(datavencimento,'DD-MM-YYYY') as datavenctochar
                       from db_itbi
    				   where id_itbi = ".$itbi);
if(pg_numrows($result) == 0) {
  db_msgbox2('Recibo nao gerado. Entre em contato com a prefeitura');
      exit;
  //redireciona();
}

db_fieldsmemory($result,0);

$proprietarios = '';
$resultprop = db_query($conn,"select *
                       from propri inner join cgm on j42_numcgm = z01_numcgm
    				   where j42_matric = ".$matricula);
if ( pg_numrows($resultprop) > 0 ){
   $traco = '';
   $proprietarios = "\n".'OUTROS TRANSMITENTES : ';
   for ($p = 0;$p < pg_numrows($resultprop);$p++){
       db_fieldsmemory($resultprop,$p);
       $proprietarios .= $traco.trim($z01_nome);
       $traco = ' - ';
   }
}
//echo $proprietarios;

$result1 = db_query($conn,"select proprietario.*
                       from proprietario
    				   where j01_matric = ".$matricula." limit 1 ");
db_fieldsmemory($result1,0);

$result2 = db_query($conn,"select * 
			  from db_caritbilan a
			       inner join db_caritbi b on a.codcaritbi=b.codcaritbi,
                               (select sum(area)
                                from db_caritbilan a
                                     inner join db_caritbi b on a.codcaritbi=b.codcaritbi
                                where id_itbi = $itbi) as x

			  where id_itbi = $itbi ");
 
if (pg_numrows($result2) == 0 ){
    $xarea = 0 ;
}else{
    $xarea = pg_result($result2,0,"sum");
}

if($numpre==""){

   $receit = 20;

   $numcgm = 15245;

   db_query("begin");
   $sql = "select nextval('numpref_k03_numpre_seq')::integer as numpre";
   $result = db_query($sql);
   $numpre = pg_result($result,0,0);

   $sql = "insert into recibo values($numcgm,
                                     '$datavencimento',
                                     $receit,
                                     707,
                                     $valorpagamento,
                                     '$datavencimento', 
                                     $numpre,
                                     1,
                                     1,
                                     0,
                                     29,
                                     0,
                                     0)";
  $result = db_query($sql);
  $sql = "update db_itbi set numpre = $numpre where id_itbi = $id_itbi";
  $result = db_query($sql);
  db_query("commit");
}



$vlrbar = "0".str_replace('.','',str_pad(number_format($valorpagamento,2,"","."),11,"0",STR_PAD_LEFT));
if($valorpagamento>999)
  $vlrbar = "0".$vlrbar;
if($valorpagamento>9999)
  $vlrbar = "0".$vlrbar;
if($valorpagamento>99999)
  $vlrbar = "0".$vlrbar;

$numbanco = "4268" ;// deve ser tirado do db_config
$numpre = db_numpre_sp($numpre,1); 
$dtvenc = str_replace("-","",$datavencimento);
$resultcod = db_query("select fc_febraban('816'||'$vlrbar'||'".$numbanco."'||'0000'||$dtvenc||'00'||'$numpre')");
$fc_febraban = pg_result($resultcod,0,0);
    			
$codigo_barras   = substr($fc_febraban,0,strpos($fc_febraban,','));
$linha_digitavel = substr($fc_febraban,strpos($fc_febraban,',')+1);

$config = db_query("select 
codigo    ,
nomeinst  ,
ender     ,
munic     ,
uf        ,
telef     ,
email     ,
ident     ,
tx_banc   ,
numbanco  ,
url       ,
logo      ,
figura    ,
dtcont    ,
diario    ,
pref      ,
vicepref  ,
fax       ,
cgc       ,
cep       ,
bairro    ,
tpropri   ,
prefeitura,
tsocios    
from db_config where codigo = ".db_getsession("DB_instit"));
db_fieldsmemory($config,0);
   
       $CAR = db_query($conn,"select c.descricao,i.area 
                    from db_caritbi c,db_caritbilan i
                    where c.codcaritbi = i.codcaritbi
                    and i.area <> 0
                    and i.id_itbi = $itbi");
    for($i = 0;$i < pg_numrows($CAR);$i += 4) {
	  $des1 = trim(@pg_result($CAR,$i,"descricao"));
	  $are1 = trim(@pg_result($CAR,$i,"area"));
      $des2 = trim(@pg_result($CAR,($i+1),"descricao"));
	  $are2 = trim(@pg_result($CAR,($i+1),"area"));
	  
  	  $des3 = trim(@pg_result($CAR,($i+2),"descricao"));
	  $are3 = trim(@pg_result($CAR,($i+2),"area"));
      $des4 = trim(@pg_result($CAR,($i+3),"descricao"));
	  $are4 = trim(@pg_result($CAR,($i+3),"area"));
    }
$matriz= split('\.',$j40_refant);

$pdf = new scpdf();
$pdf->Open();
$pdf->settopmargin(5);
$pdf->AliasNbPages();
$pdf->AddPage();



/*
$pdf->SetTextColor(235);
$pdf->SetFont('Arial','',115);
$pdf->text(20,30,'MODELO');
$pdf->text(20,70,'MODELO');
$pdf->text(20,110,'MODELO');
$pdf->text(20,150,'MODELO');
$pdf->text(20,190,'MODELO');
$pdf->text(20,230,'MODELO');
$pdf->text(20,270,'MODELO');

$pdf->SetFont('Arial','',8);
$pdf->SetTextColor(0);
*/



$pdf->SetFillColor(235);
//echo $pdf->gety();exit;
$altura = 3.5;
for ( $i=1;$i < 3;$i++){
$pdf->SetFillColor(235);
   $y = $pdf->gety() - 2;
   $pdf->Image('../dbportal2/imagens/files/'.$logo,10,$y,14);
   $pdf->SetFont('Arial','B',10);
   $pdf->setx(30);
   $pdf->Cell(100,3,$nomeinst,0,1,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->setx(30);
   $pdf->Cell(100,3,'Imposto Sobre Transmissão de Bens Imóveis (ITBI)',0,0,"L",0);

   $pdf->SetFont('Arial','B',12);
   $pdf->cell(100,3,'Vencimento : '.@$datavenctochar,0,1,"L",0);

   $pdf->SetFont('Arial','',8);
   $pdf->setx(30);
   $pdf->Cell(100,3,'Guia de Recolhimento N'.chr(176).' SMF/'.db_formatar($itbi,'s','0',5).'/'.db_getsession("DB_anousu"),0,0,"L",0);
   $pdf->cell(100,3,'Código de Arrecadação : '.@$numpre,0,1,"L",0);
   $pdf->setx(30);
   $pdf->Cell(100,3,'Tipo de Transmissão : '.$tipotransacao,0,1,"L",0);
   $pdf->Ln(7);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(20,$altura,'',1,0,"C",1);
   $pdf->cell(80,$altura,'Identificação do Transmitente',1,0,"C",1);
   $pdf->cell(80,$altura,'Identificação do Adquirente',1,1,"C",1);
   $pdf->cell(20,$altura,'Nome : ',1,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(80,$altura,$z01_nome,1,0,"L",0);
   $pdf->cell(80,$altura,$nomecomprador,1,1,"L",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(20,$altura,'CNPJ/CPF:',1,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(80,$altura,$z01_cgccpf,1,0,"L",0);
   $pdf->cell(80,$altura,$cgccpfcomprador,1,1,"L",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(20,$altura,'Endereço : ',1,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(80,$altura,$z01_ender.' - '.$z01_bairro ,1,0,"L",0);
   $pdf->cell(80,$altura,$enderecocomprador.','.$numerocomprador.' / '.$complcomprador ,1,1,"L",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(20,$altura,'Município : ',1,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(80,$altura,$z01_munic.'('.$z01_uf.') - CEP: '.$z01_cep ,1,0,"L",0);
   $pdf->cell(80,$altura,$municipiocomprador.'('.$ufcomprador.') - CEP: '.$bairrocomprador ,1,1,"L",0);




//   $pdf->cell(110,$altura,'Bairro : '.$j34_bairro,1,1,"L",0);
//   $pdf->cell(110,$altura,'Logradouro : '.$j14_nome,1,1,"L",0);
   $pdf->Ln(2);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(88,$altura,'Dados do Imóvel',1,0,"C",1);
   $pdf->cell(2,$altura,'',0,0,"C",0);
   $pdf->cell(90,$altura,'Dados da Construção',1,1,"C",1);
   $pdf->SetFont('Arial','',8);
   $y = $pdf->gety();
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(44,$altura,'Matrícula da Prefeitura : ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(44,$altura,$matricula,1,1,"L",0);
   $pdf->SetFont('Arial','B',8);
//   $pdf->cell(22,$altura,'Ref.Ant.: ',1,0,"L",1);
//   $pdf->SetFont('Arial','',8);
//   $pdf->cell(23,$altura,$j40_refant,1,1,"L",0);


   $pdf->SetFont('Arial','B',8);
   $pdf->cell(15,$altura,'Setor : ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(14,$altura,$j34_setor,1,0,"L",0);
   $pdf->SetFont('Arial','B',8);

   $pdf->cell(15,$altura,'Quadra : ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(14,$altura,$j34_quadra,1,0,"L",0);
   $pdf->SetFont('Arial','B',8);

   $pdf->cell(15,$altura,'Lote: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(15,$altura,$matriz[3],1,1,"L",0);

   $pdf->SetFont('Arial','B',8);
   $pdf->cell(22,$altura,'Logradouro: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(66,$altura,$j14_nome,1,1,"L",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(22,$altura,'Situação: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(66,$altura,$situacao,1,1,"L",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(22,$altura,'Frente: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(21,$altura,db_formatar($mfrente,'f'),1,0,"R",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(22,$altura,'Fundos : ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(23,$altura,db_formatar($mfundos,'f'),1,1,"R",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(22,$altura,'Lado Esquerdo: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(21,$altura,db_formatar($mladoesquerdo,'f'),1,0,"R",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(22,$altura,'Lado Direito: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(23,$altura,db_formatar($mladodireito,'f'),1,1,"R",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(22,$altura,'',0,1,"L",0);
   $pdf->cell(22,$altura,'',1,0,"L",1);
   $pdf->cell(33,$altura,'REAL',1,0,"C",1);
   $pdf->cell(33,$altura,'TRANSMITIDA',1,1,"C",1);
   $pdf->cell(22,$altura,'Terreno',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(33,$altura,db_formatar($areareal+0,'f').' m2',1,0,"R",0);
   $pdf->cell(33,$altura,db_formatar($areaterreno+0,'f'),1,1,"R",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(22,$altura,'Prédio',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(33,$altura,db_formatar($xarea,'f').' m2',1,0,"R",0);
   $pdf->cell(33,$altura,db_formatar($areaedificada+0,'f'),1,1,"R",0);

   $pdf->SetXY(100,$y);

   $pdf->SetFont('Arial','B',8);
   $pdf->cell(30,$altura,'Descrição',1,0,"C",1);
   $pdf->cell(33,$altura,'Descrição',1,0,"C",1);
   $pdf->cell(14,$altura,'Área m2',1,0,"C",1);
   $pdf->cell(13,$altura,'Ano Con',1,1,"C",1);
   $pdf->SetFont('Arial','',8);

   $y = $pdf->gety();
   for ($ii = 1;$ii <= 10 ; $ii++){
       $pdf->setx(100);
       $pdf->cell(30,$altura,'',1,0,"L",0);
       $pdf->cell(33,$altura,'',1,0,"L",0);
       $pdf->cell(14,$altura,'',1,0,"L",0);
       $pdf->cell(13,$altura,'',1,1,"L",0);
   }
   $yy = $pdf->gety();
   $pdf->SetXY(100,$y);
   for ($num = 0;$num < pg_numrows($result2) ; $num++){
       db_fieldsmemory($result2,$num);
       $pdf->setx(100);
       $pdf->cell(30,$altura,$descrcar,0,0,"L",0);
       $pdf->cell(33,$altura,$descricao,0,0,"L",0);
       $pdf->cell(14,$altura,db_formatar($area,'f'),0,0,"R",0);
       $pdf->cell(13,$altura,$anoconstr,0,1,"C",0);
   }
   $pdf->sety($yy+2);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(180,$altura,'Observações',1,1,"L",1);
   $pdf->SetFont('Arial','',8);
   $y = $pdf->gety();
   $pdf->cell(180,$altura,'',"TLR",1,"L",0);
   $pdf->cell(180,$altura,'',"LBR",1,"l",0);
   $pdf->cell(180,$altura,'',"LBR",1,"l",0);
   $pdf->cell(180,$altura,'',"LBR",1,"l",0);
   $pdf->cell(180,$altura,'',"BLR",1,"l",0);
   $yy = $pdf->gety();
   $pdf->sety($y);   
   $pdf->multicell(180,$altura,$obsliber.$proprietarios,1,"L",0);
   $pdf->sety($yy);   
   $pdf->cell(60,$altura,'Valor Terreno : '.@db_formatar($valoravterr,'f'),1,0,"L",0);
   $pdf->cell(60,$altura,'Valor Prédio : '.@db_formatar($valoravconst,'f'),1,0,"L",0);
   $pdf->cell(60,$altura,'Valor Avaliação : '.db_formatar($valoravaliacao,'f'),1,1,"L",0);
   $pdf->cell(60,$altura,'Valor Informado : '.db_formatar($valortransacao,'f'),1,0,"L",0);
   $pdf->cell(60,$altura,'Alíquota : '.db_formatar($aliquota,'f'),1,0,"L",0);
   $pdf->SetFont('Arial','B',10);
   $pdf->cell(60,$altura,'Valor a Pagar : '.db_formatar(($valorpagamento + $tx_banc),'f'),1,1,"L",0);
   $pdf->setfont('Arial','B',11); 
   $pdf->ln(3);
   $pdf->multicell(180,4,$munic.', '.date('d').' de '.db_mes(date('m')).' de '.date('Y').'.',0,"R",0);
   $pdf->Ln(4);
   $pdf->setfont('Arial','',11); 
   $pos = $pdf->gety();
   $pdf->setfillcolor(0,0,0); 
   $pdf->text(14,$pos,$linha_digitavel);
   $pdf->int25(10,$pos+1,$codigo_barras,15,0.341);
   $pdf->ln(30);
}   	
//$pdf->Cell(180,$altura,int25($codigo_barras),0,1,"C",0);
   /*
            <?
	           echo $linha_digitavel;
	        ?>
            </strong> </td>
	    </tr>
        <tr> 
		   
          <td> <a href="" style="text-decoration:none;border:none" onClick="print();return false"> 
            &nbsp;<img border="0" src="boleto/int25.php?text=<?=$codigo_barras?>" > 
            </a> </td>
        </tr>
      </table></td>
  </tr>
</table>
<script>
alert("Para imprimir a guia corretamente configure as margens, cabeçalho e rodapé do seu browser.\n\n\n No Internet Explorer:\n Clique no menu Arquivo(file), Configurar página(page setup), retire o cabeçalho e rodapé, e configure as margens para 1.\n\n\n  No Netscape 7:\n Clique no menu Arquivo(file), Configurar Página(page setup), clique na aba Margins & Headers/Footers, configure as margens para 0 e o cabeçalho e rodapé para blank.\n\n\nPara aparecer a caixa de diálogo da impressora, pressione CTRL P, ou clique no logotipo");
</script>

</body>
</html>
*/
$pdf->Output()
?>