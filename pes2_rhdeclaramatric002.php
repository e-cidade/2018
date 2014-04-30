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

include("fpdf151/scpdf.php");
include("libs/db_sql.php");
include("classes/db_rhpagatra_classe.php");
$clrhpagatra = new cl_rhpagatra;
db_postmemory($HTTP_POST_VARS);

$dbwhere = " rh57_saldo > 0 ";
if($filtro != "0"){
  if(isset($regisi)){
    if(trim($regisi) != "" && trim($regisf) != ""){
      $dbwhere .= "  and rh01_regist between ".$regisi." and ".$regisf;
    }else if(trim($regisi) != ""){
      $dbwhere .= "  and rh01_regist >= ".$regisi;
    }else if(trim($regisf) != ""){
      $dbwhere .= "  and rh01_regist <= ".$regisf;
    }
  }else{
    if(trim($regiss) != ""){
      $dbwhere .= "  and rh01_regist in (".$regiss.")";
    }
  }
}

$result_dados = $clrhpagatra->sql_record($clrhpagatra->sql_query_tipocgm(null,"rh01_regist, z01_nome, rh60_descr, rh57_ano, rh57_mes, rh57_saldo ","z01_nome, rh01_regist, rh57_ano, rh57_mes",$dbwhere));
$numrows_dados = $clrhpagatra->numrows;

if($numrows_dados == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Verifique os dados informados. Nenhum registro de salário atrasado encontrado.');
}

db_sel_instit();

$pdf = new scpdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$regist_ant = 0;
$total = 0;
$qtdlinhas = 0;
$altura = 0;

$data = date("Y-m-d",db_getsession("DB_datausu"));
for($i=0; $i<$numrows_dados; $i++, $altura+=5){
  db_fieldsmemory($result_dados, $i);

  if($regist_ant != $rh01_regist || $pdf->h - 70 <= $pdf->gety()){
    if($regist_ant != 0){
      if($regist_ant != $rh01_regist){
        $pdf->cell(35,4,"",0,0,"L",0);
        $pdf->cell(15,4,"Total ",0,0,"L",0);
        $pdf->Multicell(30,4,db_formatar($total,"f"),0,"R",0);
  $total = 0;
      }

      $pdf->sety($pdf->h - 65);
      $pdf->cell(100,4,"",0,0,"L",0);
      $pdf->Multicell(70,4,"$munic, ".db_subdata($data,"d")." de ".db_mes(db_subdata($data,"m"))." de ".db_subdata($data,"a"),0,"C",0);
      $pdf->sety($pdf->h - 50);
      $pdf->cell(100,4,"",0,0,"L",0);
      $pdf->Multicell(70,4,"TAIRONE REIS MARTINS",0,"C",0);

      $pdf->sety($pdf->h - 35);
      $pdf->cell(25,4,"",0,0,"L",0);
      $pdf->Multicell(145,4,"Obs.: Declaração para simples conferência, sujeita a alteração, pois, esta diferença não quita qualquer diferença paga.",0,"J",0);
    }

    $pdf->AddPage();
    $altura = 40;

    $pdf->Image("imagens/files/$logo",93,$altura,25);
 
    $pdf->sety($altura + 40);
    $pdf->setfont('Arial','B',15);
    $pdf->Multicell(0,8,$nomeinst,0,"C",0); 
 
    $pdf->sety($altura + 50);
    $pdf->setfont('Arial','B',12);
    $pdf->Multicell(0,8,"DECLARAÇÃO",0,"C",0); 
 
    $pdf->sety($altura + 72);
    $pdf->setfont('Arial','B',10);
    $pdf->Multicell(0,8,"Saldo de salários em atraso",0,"C",0);
 
    $pdf->sety($altura + 92);
    $pdf->setfont('Arial','B',8);
    $pdf->cell(50,4,"Matrícula: ",0,0,"R",0);
    $pdf->Multicell(0,4,$rh01_regist,0,"L",0);
    $pdf->cell(50,4,"Nome: ",0,0,"R",0);
    $pdf->Multicell(0,4,$z01_nome,0,"L",0);

  }
  $regist_ant = $rh01_regist;
 
  $total += $rh57_saldo;
 
  $rh57_mes = db_formatar($rh57_mes,"s","0",2,"e",0);
  $pdf->sety($altura + 106);
  $pdf->cell(35,4,"",0,0,"L",0);
  $pdf->cell(15,4,"$rh57_mes / $rh57_ano",0,0,"L",0);
  $pdf->Multicell(30,4,db_formatar($rh57_saldo,"f"),0,"R",0);
 
}

$pdf->cell(35,4,"",0,0,"L",0);
$pdf->cell(15,4,"Total ",0,0,"L",0);
$pdf->Multicell(30,4,db_formatar($total,"f"),0,"R",0);

$pdf->sety($pdf->h - 65);
$pdf->cell(100,4,"",0,0,"L",0);
$pdf->Multicell(70,4,"$munic, ".db_subdata($data,"d")." de ".db_mes(db_subdata($data,"m"))." de ".db_subdata($data,"a"),0,"C",0);
$pdf->sety($pdf->h - 50);
$pdf->cell(100,4,"",0,0,"L",0);
$pdf->Multicell(70,4,"TAIRONE REIS MARTINS",0,"C",0);

$pdf->sety($pdf->h - 35);
$pdf->cell(25,4,"",0,0,"L",0);
$pdf->Multicell(145,4,"Obs.: Declaração para simples conferência, sujeita a alteração, pois, esta diferença não quita qualquer diferença paga.",0,"J",0);
$pdf->Output();
?>