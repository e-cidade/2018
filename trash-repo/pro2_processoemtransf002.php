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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);//db_postmemory($HTTP_SERVER_VARS,2);exit;

//echo $HTTP_SERVER_VARS['QUERY_STRING'];

$desc_ordem = "Alfab�tica";
$order_by = "nome";


$head3 = "LISTA DE TRANSFER�NCIAS EM ABERTO";

$dbwhere = " ";
$orderby = " ";

if (isset($listar)) {
  if ($listar==2) {
    if($depto!= ""){
      $dbwhere =  " and e.coddepto = ".$depto;
    }
    if($id_usuario!= ""){
      $dbwhere =  " and ru.id_usuario = ".$id_usuario;
    }
    $orderby = " e.coddepto  ";
    $head5 = "ORDEM DE DEPARTAMENTO QUE ENVIOU";
  } else {
    if($depto!= ""){
      $dbwhere =  " and r.coddepto = ".$depto;
    }
    if($id_usuario!= ""){
      $dbwhere =  " and r.id_usuario = ".$id_usuario;
    }
    $orderby = " r.coddepto ";
    $head5 = "ORDEM DE DEPARTAMENTO QUE RECEBER�";
  }
}

$sql = "select p62_codtran,
               p62_dttran,
               p62_hora,
               e.coddepto as e_coddepto,
               e.descrdepto as e_descricao ,
               eu.login as e_login, 
               r.coddepto as r_coddepto,
               r.descrdepto as r_descricao,
               ru.login as r_login,
               p58_instit,
               p58_codigo,
               array_to_string(array_accum(p58_numero||'/'||p58_ano),',') as processos
        from proctransferproc
             inner join proctransfer on p62_codtran = p63_codtran
             inner join protprocesso on p63_codproc = p58_codproc
             inner join db_depart e on e.coddepto = p62_coddepto
             inner join db_usuarios as eu on eu.id_usuario = p62_id_usuario
             inner join db_depart as r on r.coddepto = p62_coddeptorec
             inner join db_usuarios as ru on ru.id_usuario = p62_id_usorec
             left join proctransand on p64_codtran = p62_codtran
             left join arqproc on p68_codproc = p63_codproc
        where ( p62_id_usorec = 1 or p62_id_usorec = 0 )
          and p64_codtran is null
          and p68_codproc is null
          $dbwhere
          and p58_instit = ".db_getsession("DB_instit")."
        group by p62_codtran, p62_dttran, p62_hora, e.coddepto, e.descrdepto, eu.login , r.coddepto, r.descrdepto, ru.login, p58_instit, p58_codigo
        order by $orderby , p62_dttran 
";

$result2 = $cldepart->sql_record($sql);

if ($cldepart->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=N�o foram encontrados registros para esse(s) filtro(s).');
}

//db_criatabela($result2);exit;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;

$codepto = 0;

for ($x2 = 0; $x2 < $cldepart->numrows; $x2++) {
  db_fieldsmemory($result2,$x2);
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $coddepto = 0;
  }
  if ( $coddepto == 0 || ( $listar == 1 && $coddepto != $r_coddepto ) || ( $listar == 2 && $coddepto != $e_coddepto) ) {

    if ($listar==1 ) {
       $coddepto = $r_coddepto;
       $pdf->cell(180,$alt,"Departamento que Receber�: ".$r_coddepto."-".$r_descricao,1,1,"L",0);
    } else {
       $coddepto = $e_coddepto;
       $pdf->cell(180,$alt,"Departamento que Enviou: ".$e_coddepto."-".$e_descricao,1,1,"L",0);
    }
    $pdf->cell(25,$alt,"Transf.",1,0,"L",0);
    $pdf->cell(20,$alt,"Data",1,0,"C",0);
    $pdf->cell(15,$alt,"Hora",1,0,"C",0);
    if ($listar==1 ) {
       $pdf->cell(100,$alt,"Departamento que Enviou ",1,0,"L",0);
    } else {
       $pdf->cell(100,$alt,"Departamento que Receber� ",1,0,"L",0);
    }
    $pdf->cell(20,$alt,"Cod. Processo",1,1,"C",0);
    $total = 0;
    $troca = 0;
  }


  $pdf->cell(25,$alt,$p62_codtran,0,0,"L",0);
  $pdf->cell(20,$alt,$p62_dttran,0,0,"C",0);
  $pdf->cell(15,$alt,$p62_hora,0,0,"C",0);
  if ($listar==1 ) {
     $pdf->cell(100,$alt,$e_coddepto."-".$e_descricao,0,0,"L",0);
  } else {
     $pdf->cell(100,$alt,$r_coddepto."-".$r_descricao,0,0,"L",0);
  }
  $pdf->cell(20,$alt,$p58_codigo,0,1,"C",0);
  //$pdf->cell(200,$alt,$processos,0,1,"L",0);

}

$pdf->Output();
   
?>