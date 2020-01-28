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
include("classes/db_cgm_classe.php");

$clcgm = new cl_cgm;
$clcgm->rotulo->label();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);




//--------------------------------------------------------------------------------------------------------------------------
//Calcula CPF
function CalculaCPF($CampoNumero){
  global $zerados;
  $RecebeCPF=$CampoNumero;
  //Retirar todos os caracteres que nao sejam 0-9
  $s="";
  for ($x=1; $x<=strlen($RecebeCPF); $x=$x+1){
    $ch=substr($RecebeCPF,$x-1,1);
    if (ord($ch)>=48 && ord($ch)<=57){
      $s=$s.$ch;
    }
  }
  
  $RecebeCPF=$s;
  if ((int) $RecebeCPF==0 and $RecebeCPF != ""){
    if ($zerados=='s'){
      return false;
    }else{
      return true;
    }
  }else if (strlen($RecebeCPF)!=11){
    return false;     
  }else{
    $Numero[1]=intval(substr($RecebeCPF,1-1,1));
    $Numero[2]=intval(substr($RecebeCPF,2-1,1));
    $Numero[3]=intval(substr($RecebeCPF,3-1,1));
    $Numero[4]=intval(substr($RecebeCPF,4-1,1));
    $Numero[5]=intval(substr($RecebeCPF,5-1,1));
    $Numero[6]=intval(substr($RecebeCPF,6-1,1));
    $Numero[7]=intval(substr($RecebeCPF,7-1,1));
    $Numero[8]=intval(substr($RecebeCPF,8-1,1));
    $Numero[9]=intval(substr($RecebeCPF,9-1,1));
    $Numero[10]=intval(substr($RecebeCPF,10-1,1));
    $Numero[11]=intval(substr($RecebeCPF,11-1,1));

    $soma=10*$Numero[1]+9*$Numero[2]+8*$Numero[3]+7*$Numero[4]+6*$Numero[5]+5*$Numero[6]+4*$Numero[7]+3*$Numero[8]+2*$Numero[9];
    $soma=$soma-(11*(intval($soma/11)));

    if ($soma==0 || $soma==1){
      $resultado1=0;
    }else{
      $resultado1=11-$soma;
    }

    if ($resultado1==$Numero[10]){
      $soma=$Numero[1]*11+$Numero[2]*10+$Numero[3]*9+$Numero[4]*8+$Numero[5]*7+$Numero[6]*6+$Numero[7]*5+
      $Numero[8]*4+$Numero[9]*3+$Numero[10]*2;
      $soma=$soma-(11*(intval($soma/11)));
      if ($soma==0 || $soma==1){
	$resultado2=0;
      }else{
	$resultado2=11-$soma;
      }
      if ($resultado2==$Numero[11]){
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }
}
// Fim do Calcula CPF

//---------------------------------------------------------------------------

//Função que calcula CNPJ
function CalculaCNPJ($CampoNumero){
  global $zerados;
  $RecebeCNPJ=${"CampoNumero"};
  $s="";
  for ($x=1; $x<=strlen($RecebeCNPJ); $x=$x+1){
    $ch=substr($RecebeCNPJ,$x-1,1);
    if (ord($ch)>=48 && ord($ch)<=57){
      $s=$s.$ch;
    }
  }
  $RecebeCNPJ=$s;
  if ((int) $RecebeCNPJ==0 and $RecebeCNPJ != ""){
    if ($zerados=='s'){
     return false;
    }else{
      return true;
    }
  }else if (strlen($RecebeCNPJ)!=14){
    return false;
  }else{
    $Numero[1]=intval(substr($RecebeCNPJ,1-1,1));
    $Numero[2]=intval(substr($RecebeCNPJ,2-1,1));
    $Numero[3]=intval(substr($RecebeCNPJ,3-1,1));
    $Numero[4]=intval(substr($RecebeCNPJ,4-1,1));
    $Numero[5]=intval(substr($RecebeCNPJ,5-1,1));
    $Numero[6]=intval(substr($RecebeCNPJ,6-1,1));
    $Numero[7]=intval(substr($RecebeCNPJ,7-1,1));
    $Numero[8]=intval(substr($RecebeCNPJ,8-1,1));
    $Numero[9]=intval(substr($RecebeCNPJ,9-1,1));
    $Numero[10]=intval(substr($RecebeCNPJ,10-1,1));
    $Numero[11]=intval(substr($RecebeCNPJ,11-1,1));
    $Numero[12]=intval(substr($RecebeCNPJ,12-1,1));
    $Numero[13]=intval(substr($RecebeCNPJ,13-1,1));
    $Numero[14]=intval(substr($RecebeCNPJ,14-1,1));

    $soma=$Numero[1]*5+$Numero[2]*4+$Numero[3]*3+$Numero[4]*2+$Numero[5]*9+$Numero[6]*8+$Numero[7]*7+$Numero[8]*6+$Numero[9]*5+$Numero[10]*4+$Numero[11]*3+$Numero[12]*2;
    $soma=$soma-(11*(intval($soma/11)));

    if ($soma==0 || $soma==1){
      $resultado1=0;
    }else{
      $resultado1=11-$soma;
    }
    if ($resultado1==$Numero[13]){
      
      $soma=$Numero[1]*6+$Numero[2]*5+$Numero[3]*4+$Numero[4]*3+$Numero[5]*2+$Numero[6]*9+$Numero[7]*8+$Numero[8]*7+$Numero[9]*6+$Numero[10]*5+$Numero[11]*4+$Numero[12]*3+$Numero[13]*2;
      $soma=$soma-(11*(intval($soma/11)));
      
      if ($soma==0 || $soma==1){
	 $resultado2=0;
      }else{
	 $resultado2=11-$soma;
      }
      if ($resultado2==$Numero[14]){
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }
}
//Fim do Calcula CNPJ
//--------------------------------------------------------------------------------------------------------------------------


$head3 = "RELATÓRIOS DE CPF/CNPJ ";
$head5 = "INVÁLIDOS";

$result=$clcgm->sql_record($clcgm->sql_query_file(null,"z01_numcgm,z01_nome,z01_cgccpf","z01_numcgm",""));

if ($clcgm->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');

}
      
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$p=0;  

for($x = 0; $x < $clcgm->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(50,$alt,$RLz01_numcgm,1,0,"C",1);
      $pdf->cell(80,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(50,$alt,$RLz01_cgccpf,1,1,"C",1);
      $p=0;  
      $troca = 0;
   }
    
  
  
   if ($z01_cgccpf==""&&$branco=='n'){
     continue;
   }else{
     $tam_cgccpf=strlen($z01_cgccpf);
     if ($tam_cgccpf==14){
       $imprime = CalculaCNPJ($z01_cgccpf);
     }else{
       $imprime = CalculaCPF($z01_cgccpf);
     }
     if($imprime==false){
       $pdf->setfont('arial','',7);
       $pdf->cell(50,$alt,$z01_numcgm,0,0,"C",$p);
       $pdf->cell(80,$alt,$z01_nome,0,0,"L",$p);
       $pdf->cell(50,$alt,$z01_cgccpf,0,1,"C",$p);
       if ($p==0){
	 $p=1;
       }else $p=0;
       $total++;
     }
   }
 }

$pdf->setfont('arial','b',8);
$pdf->cell(180,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>