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


include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_db_depusu_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_usuarios_classe.php");


$cldepusu = new cl_db_depusu;
$cldepart = new cl_db_depart;
$clusuarios = new cl_db_usuarios;


$clrotulo = new rotulocampo;
$clrotulo->label('id_usuario');
$clrotulo->label('nome');
$clrotulo->label('coddepto');
$clrotulo->label('descrdepto');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

//echo $HTTP_SERVER_VARS['QUERY_STRING'];

$desc_ordem = "Alfabética";
$order_by = "nome";


$head3 = "CADASTRO DE DEPARTAMENTOS E USUÁRIOS";
$head5 = "ORDEM $desc_ordem";


if (isset($listar)) {

  switch ($listar) {
    case 1:
      $dbwhere = "db_usuarios.usuarioativo = '1'";
      $head7 = "Somente Usuários Ativos";

      break;
    case 2:
      $dbwhere = "db_usuarios.usuarioativo = '0'";
      $head7 = "Somente Usuários Inativos";

      break;
    case 3:
      $dbwhere = "db_usuarios.usuarioativo = '2'";
      $head7 = "Somente Usuários Bloqueados";

      break;
    case 4:
      $dbwhere = "db_usuarios.usuarioativo = '3'";
      $head7 = "Somente Usuários Aguardando Ativação";

      break;
    default:
      $dbwhere = "1=1";
      $head7 = "Todos Usuários";
  }
}

if (isset($id_usuario)) {
  $dbwhere .=" and db_depusu.id_usuario=$id_usuario";
  $result = $cldepusu->sql_record($cldepusu->sql_query("","","*",$order_by,$dbwhere));
  
  if ($cldepusu->numrows == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros para esse(s) filtro(s).');
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
  
  for ($x = 0; $x < $cldepusu->numrows; $x++) {
    db_fieldsmemory($result,$x);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(30,$alt,$RLid_usuario,1,0,"C",1);

      if($listar==0) {
        $pdf->cell(30,$alt,"Situacao",1,0,"C",1);
      }

      $pdf->cell(90,$alt,$RLnome,1,1,"C",1);
      
      $troca = 0;
      
      $pdf->setfont('arial','',8);
      $pdf->cell(30,$alt,$id_usuario,0,0,"C",0);
      
      if ($listar==0) {

        switch ($usuarioativo) {
          case 1:
            $sSituacao = "ATIVO";
            break;
          case 2:
            $sSituacao = "BLOQUEADO";
            break;
          case 3:
            $sSituacao = "AGUARDANDO ATIVAÇÃO";
            break;
          default:
            $sSituacao = "INATIVO";
        }

        $pdf->cell(30, $alt, $sSituacao, 0, 0, "C", 0);
      }

      $pdf->cell(90,$alt,$nome,0,1,"L",0);
      $total++;
      
      $pdf->setfont('arial','b',8);
      $pdf->cell(50,$alt,"Departamento(s):",0,1,"L",0);
    }
    
    $pdf->setfont('arial','',7);
    $pdf->cell(25,$alt,$coddepto,0,0,"C",0);
    $pdf->cell(100,$alt,$descrdepto,0,1,"L",0);
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(150,$alt,'TOTAL DE USUÁRIOS  :  '.$total,"T",0,"L",0);
}





if (isset($depto)) {
  $dbwhere .=" and db_depusu.coddepto=$depto";
  $result1 = $cldepusu->sql_record($cldepusu->sql_query("","","*",$order_by,$dbwhere));
  
  if ($cldepusu->numrows == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros para esse(s) filtro(s).');
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
  
  for ($x1 = 0; $x1 < $cldepusu->numrows; $x1++) {
    db_fieldsmemory($result1,$x1);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(60,$alt,$RLcoddepto,1,0,"C",1);
      $pdf->cell(90,$alt,$RLdescrdepto,1,1,"C",1);
      $total = 0;
      $troca = 0;
      
      $pdf->setfont('arial','',8);
      $pdf->cell(60,$alt,$coddepto,0,0,"C",0);
      $pdf->cell(90,$alt,$descrdepto,0,1,"L",0);
      $total++;
      
      $pdf->setfont('arial','b',8);
      $pdf->cell(50,$alt,"Usuário(s):",0,1,"L",0);
    }
    

    $pdf->setfont('arial','',7);
    $pdf->cell(25,$alt,$id_usuario,0,0,"C",0);

    if ($listar == 0) {

      switch ($usuarioativo) {
        case 1:
          $sSituacao = "ATIVO";
          break;
        case 2:
          $sSituacao = "BLOQUEADO";
          break;
        case 3:
          $sSituacao = "AGUARDANDO ATIVAÇÃO";
          break;
        default:
          $sSituacao = "INATIVO";
      }

      $pdf->cell(30, $alt, $sSituacao, 0, 0, "C", 0);
    }

    $pdf->cell(100,$alt,$nome,0,1,"L",0);
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(150,$alt,'TOTAL DE DEPARTAMENTOS  :  '.$total,"T",0,"L",0);
  
}

if (empty($id_usuario) and empty($depto)) {
  
  $result2 = $cldepart->sql_record($cldepusu->sql_query("","","distinct *",$order_by,$dbwhere));
  
  if ($cldepart->numrows == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros para esse(s) filtro(s).');
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
  
  for ($x2 = 0; $x2 < $cldepart->numrows; $x2++) {
    db_fieldsmemory($result2,$x2);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(60,$alt,$RLcoddepto,1,0,"C",1);
      $pdf->cell(90,$alt,$RLdescrdepto,1,1,"C",1);
      $total = 0;
      $troca = 0;
    }
    $pdf->setfont('arial','',8);
    $pdf->cell(60,$alt,$coddepto,0,0,"C",0);
    $pdf->cell(90,$alt,$descrdepto,0,1,"L",0);
    $total++;
    
    $pdf->setfont('arial','b',8);
    $pdf->cell(50,$alt,"Usuário(s):",0,1,"L",0);
    
    $result3 = $cldepusu->sql_record($cldepusu->sql_query("","","*",$order_by,$dbwhere." and db_depusu.coddepto=$coddepto"));
    
    for ($x3 = 0; $x3 < $cldepusu->numrows; $x3++) {
      db_fieldsmemory($result3,$x3);
      
      $pdf->setfont('arial','',7);

      $pdf->cell(25,$alt,$id_usuario,0,0,"C",0);

      if($listar==0) {

        switch ($usuarioativo) {
          case 1:
            $sSituacao = "ATIVO";
            break;
          case 2:
            $sSituacao = "BLOQUEADO";
            break;
          case 3:
            $sSituacao = "AGUARDANDO ATIVAÇÃO";
            break;
          default:
            $sSituacao = "INATIVO";
        }

        $pdf->cell(30, $alt, $sSituacao, 0, 0, "C", 0);
      }

      $pdf->cell(100,$alt,$nome,0,1,"L",0);
    }
    
    
    $pdf->cell(150,$alt,'',"T",1,"L",0);
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(150,$alt,'TOTAL DE DEPARTAMENTOS  :  '.$total,"T",0,"L",0);
  
}



$pdf->Output();
   
?>
