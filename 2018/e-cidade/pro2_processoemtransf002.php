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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("classes/db_db_depusu_classe.php"));
require_once(modification("classes/db_db_depart_classe.php"));
require_once(modification("classes/db_db_usuarios_classe.php"));

define('LARGURA_PAGINA', 277);

$id_usuario  = '';
$depto       = '';
$cldepusu    = new cl_db_depusu;
$cldepart    = new cl_db_depart;
$clusuarios  = new cl_db_usuarios;


$clrotulo = new rotulocampo;
$clrotulo->label('id_usuario');
$clrotulo->label('nome');
$clrotulo->label('coddepto');
$clrotulo->label('descrdepto');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);//db_postmemory($HTTP_SERVER_VARS,2);exit;

//echo $HTTP_SERVER_VARS['QUERY_STRING'];

$desc_ordem = "Alfabética";
$order_by = "nome";


$head3 = "LISTA DE TRANSFERÊNCIAS EM ABERTO";

$dbwhere = " ";
$orderby = " ";

if (isset($listar)) {
    $dbwhere = "";
  if ($listar==2) {
    if($depto!= ""){
      $dbwhere .=  " and e.coddepto = ".$depto;
    }
    if($id_usuario!= ""){
      $dbwhere .=  " and eu.id_usuario = ".$id_usuario;
    }
    $orderby = " e.coddepto  ";
    $head5 = "ORDEM DE DEPARTAMENTO QUE ENVIOU";
  } else {
    if($depto!= ""){
      $dbwhere .=  " and r.coddepto = ".$depto;
    }
    if($id_usuario!= ""){
      $dbwhere .=  " and ru.id_usuario = ".$id_usuario;
    }
    $orderby = " r.coddepto ";
    $head5 = "ORDEM DE DEPARTAMENTO QUE RECEBERÁ";
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
               p58_numero||'/'||p58_ano as processos,
               p58_requer,
               p51_descr
        from proctransferproc
             inner join proctransfer on p62_codtran = p63_codtran
             inner join protprocesso on p63_codproc = p58_codproc
				     inner join tipoproc     on p58_codigo  = p51_codigo
             inner join db_depart e on e.coddepto = p62_coddepto
             inner join db_usuarios as eu on eu.id_usuario = p62_id_usuario
             inner join db_depart as r on r.coddepto = p62_coddeptorec
             left join db_usuarios as ru on ru.id_usuario = p62_id_usorec
             left join proctransand on p64_codtran = p62_codtran
             left join arqproc on p68_codproc = p63_codproc
        where 
          p64_codtran is null
          and p68_codproc is null
          $dbwhere
          and p58_instit = ".db_getsession("DB_instit")."
        order by $orderby , p62_dttran
";
$result2 = $cldepart->sql_record($sql);

if ($cldepart->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros para esse(s) filtro(s).');
}

//db_criatabela($result2);exit;

$pdf = new PDF('L');
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

  $pdf->setfont('arial','b',8);

  if ( $coddepto == 0 || ( $listar == 1 && $coddepto != $r_coddepto ) || ( $listar == 2 && $coddepto != $e_coddepto) ) {

    if ($listar==1 ) {

      $coddepto = $r_coddepto;
      $pdf->cell(larguraColuna(100),$alt,"Departamento que Receberá: ".$r_coddepto."-".$r_descricao,1,1,"L",0);
    } else {
      $coddepto = $e_coddepto;
      $pdf->cell(larguraColuna(100),$alt,"Departamento que Enviou: ".$e_coddepto."-".$e_descricao,1,1,"L",0);
    }

    $pdf->cell(larguraColuna(7),$alt,"Transf.",1,0,"L",0);
    $pdf->cell(larguraColuna(6),$alt,"Data",1,0,"C",0);
    $pdf->cell(larguraColuna(5),$alt,"Hora",1,0,"C",0);

    if ($listar==1 ) {
      $pdf->cell(larguraColuna(30),$alt,"Departamento que Enviou ",1,0,"L",0);
    } else {
      $pdf->cell(larguraColuna(30),$alt,"Departamento que Receberá ",1,0,"L",0);
    }

    $pdf->cell(larguraColuna(8),$alt,"Cod. Processo",1,0,"C",0);
    $pdf->cell(larguraColuna(26),$alt,"Requerente",1,0,"C",0);
    $pdf->cell(larguraColuna(18),$alt,"Tipo",1,1,"C",0);

    $total = 0;
    $troca = 0;
  }

  $pdf->setfont('arial', '', 8);
  $pdf->cell(larguraColuna(7),$alt,$p62_codtran,0,0,"L",0);
  $pdf->cell(larguraColuna(6),$alt, db_formatar($p62_dttran, 'd'),0,0,"C",0);
  $pdf->cell(larguraColuna(5),$alt,$p62_hora,0,0,"C",0);
  if ($listar==1 ) {
    $pdf->cell(larguraColuna(30),$alt,$e_coddepto."-".$e_descricao,0,0,"L",0);
  } else {
    $pdf->cell(larguraColuna(30),$alt,$r_coddepto."-".$r_descricao,0,0,"L",0);
  }

  $pdf->cell(larguraColuna(8),$alt,$processos,0,0,"L",0);

  $pdf->cell(larguraColuna(26),$alt, limitarTexto($p58_requer, 45), 0,0,"L",0);
  $pdf->cell(larguraColuna(18),$alt, limitarTexto($p51_descr, 25),0,1,"L",0);
}

$pdf->Output();

/**
 * Largura da coluna
 *
 * @param string $sTipo
 * @param float $nPorcentagem - Porcentagem que a coluna ocupara na linha
 * @return float
 */
function larguraColuna($nPorcentagem = 0) {

  if (empty($nPorcentagem)) {
    return LARGURA_PAGINA;
  }

  return round($nPorcentagem / 100 * LARGURA_PAGINA, 2);
}


function limitarTexto($sTexto, $iLimite = 50) {

  $iTamanho = mb_strlen($sTexto);
  if ($iTamanho > $iLimite) {
    $sTexto = substr($sTexto, 0, $iLimite) . '...';
  }
  return $sTexto;
}
