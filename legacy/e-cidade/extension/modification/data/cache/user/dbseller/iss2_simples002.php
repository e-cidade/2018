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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$sqlinst = "select munic as municipio from db_config where codigo = ".db_getsession("DB_instit");
$resultinst = pg_exec($sqlinst);
db_fieldsmemory($resultinst, 0);
$municipio = strtoupper($municipio);
if($municipio =='BAGE' and 1==2){
  if(!($conn2 = @pg_connect("host=192.168.78.245 dbname=daeb port=5433 user=postgres"))) {
//  if(!($conn2 = @pg_connect("host= 192.168.0.44 dbname= auto_dae_20070619 port=5432 user=postgres"))) {
    echo "Contate com Administrador do Sistema! (Conexão Inválida.)   <br>Sessão terminada, feche seu navegador!\n ";
    exit;
  }
}
include("classes/db_numpref_classe.php");
$clnumpref = new cl_numpref;
$resnumpref = $clnumpref->sql_record($clnumpref->sql_query_file(db_getsession("DB_anousu"),db_getsession("DB_instit"),"k03_certissvar"));
if($resnumpref==false || $clnumpref->numrows==0){
  throw new \ECidade\V3\Extension\Exceptions\ResponseException("Tabela de parâmetro (numpref) não configurada! Verifique com administrador");	
  db_redireciona("corpo.php");
  exit;
}else{
  db_fieldsmemory($resnumpref,0);
}
//format = se quando é escrito no txt é formatado ou não:
//         se 0 não formata se 1 formata
db_criatermometro('termometro','Concluido...','blue',1);
flush();

/*
 echo $DB_BASE . " - 13 segundos... se quiser cancelar CTRL+C\n";
 sleep(13);
 */
//echo "<BR><BR>";

//$variavel_somente_vencido = true;

$arquivo_invalidos = fopen("/tmp/cnpj_simples_invalidos.txt", "w");
//system("echo > /tmp/cnpj_simples_invalidos.txt");

$arquivo_validos = fopen("/tmp/cnpj_simples.txt", "w");
//system("echo > /tmp/cnpj_simples.txt");
//testa o tipo do arquivo 1 - apto, 2 nao apto
if ($tipo == 1){

  $sJoin   = " inner ";
  $sWhere  = "";
  $sWhere2 = " where tipocert <> 'positiva' and q02_dtbaix is null";
  $head5   = "CNPJ Aptos";

}else if ($tipo == 2){
  
  $sJoin        = " left ";
  $sWhere       = " and q02_numcgm is null";
  $sWhere2      = " where (q02_inscr is not null and q02_dtbaix is not null) or (q02_inscr is not null and q02_dtbaix is null and tipocert = 'positiva') or q02_inscr is null ";
  $cons_debvenc = "s";
  $head5        = "CNPJ Não Aptos";
}

$datausu = $data;
//$datausu = date("Y-m-d", db_getsession("DB_datausu")); 
#$whereissvar = ($k03_certissvar=='t'?"' k00_valor <> 0 '":"''");
$whereissvar = "''";
$sql = "
select distinct on (z01_cgccpf) z01_cgccpf, 
z01_numcgm as cgm, 
z01_munic as munic, 
q02_dtbaix as dtbaix, 
q02_inscr as inscr, 
z01_nome, 
tipocert
from (select distinct z01_cgccpf, z01_numcgm, z01_munic, q02_dtbaix, q02_inscr, z01_nome,
fc_tipocertidao(z01_numcgm, 'c', '$datausu', $whereissvar) as tipocert
	from cgm 
	$sJoin join issbase on q02_numcgm = z01_numcgm 
 where length(trim(z01_cgccpf))     = 14 
   AND z01_munic                    = '$municipio'
  ) as x   
 $sWhere2 
order by x.z01_cgccpf;
";
//die($sql);
$result = pg_exec($conn, $sql) or die($sql);
$linhas = pg_num_rows($result);
// GERA PDF
     if($cons_debvenc=="s"){
        $apto = " Sim.";
     }else{
        $apto = " Não.";
     }
$head2 = "Lista de Empresas para o Super Simples";
$head4 = "Considerar CNPJ com débito não vencido como apto:".$apto;
$head6 = "Data: ".db_formatar($data,"d");


$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$alt = 5;
$pag = 1;

$totregistros=0;

//***********************
$cnpj="";

//db_criatabela($result);exit;
for ($x=0; $x < $linhas; $x++) {
  db_fieldsmemory($result, $x);
  db_atutermometro($x,$linhas,'termometro');
  flush();

  $vaitxt=true;

  if($municipio =='BAGE' and $z01_cgccpf!=""){
    // procura na base daeb
    $sql_daeb = " select z01_numcgm 
                from cgm
                inner join arrenumcgm on z01_numcgm = arrenumcgm.k00_numcgm
                inner join arrecad    on arrenumcgm.k00_numpre = arrecad.k00_numpre
                where z01_cgccpf = '$z01_cgccpf'";
    $result_daeb = pg_exec($conn2,$sql_daeb) or die($sql_daeb);
    if (pg_numrows($result_daeb) > 0 and $tipo == 1) {
      $vaitxt=false;
    }

  }

  if ($vaitxt == true) {
     if($cons_debvenc=="s"){
        if($tipocert=="positiva" and $tipo == 1){
          $z01_cgccpf="";
        }
     }else{
        if((($tipocert=="positiva")||($tipocert=="regular")) and $tipo == 1){
          $z01_cgccpf="";
        }
     }
    
    if($z01_cgccpf!=""){
        
      // gera txt
      if($z01_cgccpf != $cnpj){
        $cnpj= $z01_cgccpf;
        validaCNPJ($z01_cgccpf,1,$arquivo_invalidos,$arquivo_validos);
      }
     
      // gera pdf
      if (($pdf->gety() > $pdf->h - 30) || $pag == 1 ){
        $pdf->addpage();
        $pag = 0;
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(35,$alt,"CNPJ","BT",0,"L",1);
        $pdf->Cell(20,$alt,"CGM","BT",0,"L",1);
        $pdf->Cell(115,$alt,"NOME DA EMPRESA","BT",0,"L",1);
        $pdf->Cell(20,$alt,"INSCRIÇÃO","BT",0,"L",1);
        $pdf->ln();

      }
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(35,$alt,db_formatar($z01_cgccpf,"cnpj"),0,0,"L",0);
      $pdf->Cell(20,$alt,$cgm,0,0,"L",0);
      $pdf->Cell(115,$alt,$z01_nome,0,0,"L",0);
      $pdf->Cell(20,$alt,$inscr,0,0,"L",0);
      $totregistros++;
      $pdf->ln();

    }

  }

}
$pdf->ln(1);
$pdf->Cell(35,$alt,"  TOTAL DE REGISTROS: " . $totregistros,0,0,"L",0);

//$pdf->Output();
$pdf->Output("/tmp/simples.pdf",false,true);

fclose($arquivo_invalidos);
fclose($arquivo_validos);

$nomedoarquivo = "/tmp/cnpj_simples.txt";
$arqnomes = $nomedoarquivo."# Download do Arquivo - ".$nomedoarquivo."|";
$nomedoarquivo2 = "/tmp/cnpj_simples_invalidos.txt";
$arqnomes .= $nomedoarquivo2."# Download do Arquivo - ".$nomedoarquivo2."|";
$nomedoarquivo3 = "/tmp/simples.pdf";
$arqnomes .= $nomedoarquivo3."# Download do Arquivo - ".$nomedoarquivo3."|";

echo "<script>";
echo "  parent.db_iframe_relatorio.hide();";
echo "  listagem = '$arqnomes';";
echo "  parent.js_montarlista(listagem,'form1');";
echo "</script>";

function validaCNPJ($cnpj,$format,$arquivo_invalidos,$arquivo_validos){

  if($format = 1){
     $cnpj1 =db_formatar($cnpj,"cnpj");
    //$cnpj1 = substr($cnpj,0,2).'.'.substr($cnpj,3,3).'.'.substr($cnpj,5,3).'/'.substr($cnpj,8,4).'-'.substr($cnpj,12,2).'\;';
  }else{
    $cnpj1 = $cnpj;
  }

  if (strlen($cnpj) <> 14){
    //echo " - Inválido <br>";
//    system("echo $cnpj1 >> /tmp/cnpj_simples_invalidos.txt");
    fputs($arquivo_invalidos,$cnpj1 . "\n");
  }else{
    $soma = 0;
    $soma += ($cnpj[0] * 5);
    $soma += ($cnpj[1] * 4);
    $soma += ($cnpj[2] * 3);
    $soma += ($cnpj[3] * 2);
    $soma += ($cnpj[4] * 9);
    $soma += ($cnpj[5] * 8);
    $soma += ($cnpj[6] * 7);
    $soma += ($cnpj[7] * 6);
    $soma += ($cnpj[8] * 5);
    $soma += ($cnpj[9] * 4);
    $soma += ($cnpj[10] * 3);
    $soma += ($cnpj[11] * 2);
    $d1 = $soma % 11;
    $d1 = $d1 < 2 ? 0 : 11 - $d1;
    $soma = 0;
    $soma += ($cnpj[0] * 6);
    $soma += ($cnpj[1] * 5);
    $soma += ($cnpj[2] * 4);
    $soma += ($cnpj[3] * 3);
    $soma += ($cnpj[4] * 2);
    $soma += ($cnpj[5] * 9);
    $soma += ($cnpj[6] * 8);
    $soma += ($cnpj[7] * 7);
    $soma += ($cnpj[8] * 6);
    $soma += ($cnpj[9] * 5);
    $soma += ($cnpj[10] * 4);
    $soma += ($cnpj[11] * 3);
    $soma += ($cnpj[12] * 2);
    $d2 = $soma % 11;
    $d2 = $d2 < 2 ? 0 : 11 - $d2;
    if ($cnpj[12] == $d1 && $cnpj[13] == $d2) {
      // echo " - Válido <br>";
//      system("echo $cnpj1 >> /tmp/cnpj_simples.txt");
      fputs($arquivo_validos,$cnpj1 . "\n");
    } else {
      // echo " - Inválido <br>";
//      system("echo $cnpj1 >> /tmp/cnpj_simples_invalidos.txt");
      fputs($arquivo_invalidos,$cnpj1 . "\n");
    }
  }
}
?>