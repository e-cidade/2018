<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("fpdf151/pdf.php");

$clrotulo        = new rotulocampo;
$clvistlocal     = new cl_vistlocal;
$clvistexec      = new cl_vistexec;
$clvistinscr     = new cl_vistinscr;
$clvistmatric    = new cl_vistmatric;
$clvistcgm       = new cl_vistcgm;
$clvistusuario   = new cl_vistusuario;
$clvistsanitario = new cl_vistsanitario;
$clvistoriarec   = new cl_vistoriarec;
$cltipovistorias = new cl_tipovistorias;
$clcgm           = new cl_cgm;
$clvistorias     = new cl_vistorias;

$clrotulo->label("q03_descr");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$clrotulo->label("nome");
$clrotulo->label("y75_obs");
$clrotulo->label("y76_receita");
$clrotulo->label("y76_valor");
$clrotulo->label("y76_descr");
$clrotulo->label("y77_descricao");
$clcgm->rotulo->label();
$clvistorias->rotulo->label();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$dbdeptoatual=db_getsession("DB_coddepto");
$and = "and";
$where = " and y70_coddepto = $dbdeptoatual";

$head3 = "TIPOS DE VISTORIAS:";

if ($listatipo!=""){

  $vistdescr = $cltipovistorias->sql_record($cltipovistorias->sql_query_file("","y77_descricao",""," y77_codtipo in ($listatipo) and y77_instit = ".db_getsession('DB_instit') ));
  $v =  "";
}else{
  $head3="TIPOS DE VISTORIAS:Todos";
}

for($a=0;$a<$cltipovistorias->numrows;$a++){

  db_fieldsmemory($vistdescr,$a);
  $head3 .= $v.$y77_descricao;
  $v = ",";

}
if( @$consulta!=1){

  if ($vertipo == "com" && $listatipo!=""){
    $where .= " and y77_codtipo in ($listatipo)";
    $and = "and";
  } elseif ($vertipo == "sem" && $listatipo!=""){
    $where .= " and y77_codtipo not in ($listatipo)";
    $and = "and";
  }
  if ($verrua== "com" && $listarua!=""){
    $where .= " and y10_codigo in ($listarua)";
    $and = "and";
  } elseif ($verrua == "sem"&& $listarua!=""){
    $where .= " and y10_codigo not in ($listarua)";
    $and = "and";
  }
  if ($verbairro == "com" && $listabairro!=""){
    $where .= " and y10_codi in ($listabairro)";
    $and = "and";
  } elseif ($verbairro == "sem" && $listabairro!=""){
    $where .= " and y10_codi not in ($listabairro)";
    $and = "and";
  }
  if($datai == "--" && $dataf == "--"){

  } elseif($datai !== "--" && $dataf !== "--"){
    $where .=" and y70_data between '$datai' and '$dataf'";
    $info2 = "PERIODO:".db_formatar($datai,'d'). " A " .db_formatar($dataf,'d');
  } elseif($datai !== "--" && $dataf == "--"){
    $where .=" and y70_data >= '$datai'";
    $info2 = "Apartir de :".db_formatar($datai,'d');
  } elseif($datai == "--" && $dataf !== "--"){
    $where .=" and y70_data <= '$datai'";
    $info2 = "Ate :".db_formatar($datai,'d');
  }
  $order_by = "";
  if (isset($ordem)&&$ordem=='v'){
    $order_by = " y70_codvist ";
    $info = "ORDENADO POR VISTORIA";
  }else if (isset($ordem)&&$ordem=='r'){
    $order_by = " ruas.j14_nome ";
    $info = "ORDENADO POR RUA";
  }else if (isset($ordem)&&$ordem=='b'){
    $order_by = " bairro.j13_descr ";
    $info = "ORDENADO POR BAIRRO";
  }else if (isset($ordem)&&$ordem=='n'){
    $order_by = " z01_nome ";
    $info = "ORDENADO POR NOME";
  }else if (isset($ordem)&&$ordem=='t'){
    $order_by = " y70_tipovist ";
    $info = "ORDENADO POR TIPO";
  }
  $head5 = @$info;
}else{
  $where = " and y70_codvist = $y70_codvist ";
  $order_by = "";
  $tiporel="ana";
}

$result = $clvistorias->sql_record($clvistorias->sql_query_info(null,"*",$order_by," 1=1 ".$where." and y70_instit = ".db_getsession('DB_instit') ));

$linhas = $clvistorias->numrows;
if($linhas == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum dado encontrado para o filtro escolhido!');
}

$head2 = @$info2;
$total = 0;
$troca = 1;
$alt = 4;
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas

if($tiporel=="ana"){
  if(@$consulta!=1){
    $head1 = "RELATÓRIO DE VISTORIAS ANALÍTICO";
  }else{
    $head1 = "VISTORIA: ".$y70_codvist;
  }
  $pdf->AddPage(); // adiciona uma pagina
}
for($r=0;$r<$linhas;$r++){
  db_fieldsmemory($result,$r);
  $resultcgm = $clvistinscr->sql_record($clvistinscr->sql_query($y70_codvist));
  if($clvistinscr->numrows > 0){
    db_fieldsmemory($resultcgm,0);
  }
  $resultcgm = $clvistsanitario->sql_record($clvistsanitario->sql_query($y70_codvist));
  if($clvistsanitario->numrows > 0){
    db_fieldsmemory($resultcgm,0);
  }
  $resultcgm = $clvistmatric->sql_record($clvistmatric->sql_query($y70_codvist));
  if($clvistmatric->numrows > 0){
    db_fieldsmemory($resultcgm,0);
  }
  $resultcgm = $clvistcgm->sql_record($clvistcgm->sql_query($y70_codvist));
  if($clvistcgm->numrows > 0){
    db_fieldsmemory($resultcgm,0);
  }
  $resultlocal = $clvistexec->sql_record($clvistexec->sql_query($y70_codvist,"j14_nome as rexec,j13_descr as bexec,y11_numero,y11_compl"));
  if($clvistexec->numrows > 0){
    db_fieldsmemory($resultlocal,0);
  }
  $resultexec = $clvistlocal->sql_record($clvistlocal->sql_query($y70_codvist,"j14_nome as rlocal,j13_descr as blocal,y10_numero,y10_compl"));
  if($clvistlocal->numrows > 0){
    db_fieldsmemory($resultexec,0);
  }
  $head1 = "RELATÓRIO DE VISTORIAS";
  if($tiporel=="ana"){
    $head1 = "RELATÓRIO DE VISTORIAS";
    if ($pdf->GetY() > 280) {
      $pdf->AddPage();
    }

    $Letra = 'arial';
    $pdf->SetFont($Letra,'',7);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(230);
    $pdf->Ln(4);
    $pdf->SetFillColor(255);
    $pdf->Cell(140,4,$RLz01_nome.': '.@$z01_nome,1,0,"J",1);
    $pdf->Cell(50,4,$RLz01_cgccpf.': '.@$z01_cgccpf,1,"J",1,30);
    $pdf->Ln(4);
    $pdf->Cell(75,4,$RLj14_nome.': '.@$j14_nome,1,0,"J",1);
    $pdf->Cell(35,4,$RLz01_numero.': '.@$z01_numero,1,"J",1,30);
    $pdf->Cell(80,4,$RLz01_compl.': '.substr(@$z01_compl, 0, 100),1,"J",1,30);
    $pdf->Ln(4);
    $pdf->Cell(75,4,$RLz01_munic.': '.@$z01_munic,1,0,"J",1);
    $pdf->Cell(80,4,$RLj13_descr.': '.@$j13_descr,1,0,"J",1);
    $pdf->Cell(35,4,$RLz01_cep.': '.@$z01_cep,1,0,"J",1);
    $pdf->Ln(4);
    $pdf->SetFillColor(230);
    $pdf->Cell(190,4," VISTORIA",1,0,"C",1);
    $pdf->SetFillColor(255);
    $pdf->Ln(4);
    $pdf->Cell(40,4,$RLy70_numbloco.': '.@$y70_numbloco,1,"J",1,30);
    $pdf->Cell(35,4,$RLy70_data.': '.db_formatar(@$y70_data,'d'),1,0,"J",1);
    $pdf->Cell(35,4,$RLy70_hora.': '.@$y70_hora,1,"J",1,30);
    $pdf->Cell(80,4,$RLy70_contato.': '.@$y70_contato,1,0,"J",1);
    $pdf->Ln(4);
    $pdf->Cell(190,4,$RLy77_descricao.': '.@$y77_descricao,1,0,"J",1);
    $pdf->Ln(4);
    $pdf->MultiCell(190,4,$RLy70_obs.': '.$y70_obs,1,"L",0);
    $pdf->SetFillColor(230);
    $pdf->Cell(190,4,"LOCAL DA VISTORIA",1,0,"C",1);
    $pdf->Ln(4);
    $pdf->SetFillColor(255);
    $pdf->Cell(75,4,$RLj14_nome.': '.@$rlocal,1,0,"J",1);
    $pdf->Cell(55,4,$RLj13_descr.': '.@$blocal,1,0,"J",1);
    $pdf->Cell(20,4,$RLz01_numero.': '.@$y10_numero,1,"J",1,30);
    $pdf->Cell(40,4,$RLz01_compl.': '.substr(@$y10_compl, 0,17),1,"J",1,30);
    $pdf->Ln(4);
    $pdf->SetFillColor(230);
    $pdf->Cell(190,4,"LOCAL DA EXECUÇÃO DA VISTORIA",1,0,"C",1);
    $pdf->Ln(4);
    $pdf->SetFillColor(255);
    $pdf->Cell(75,4,$RLj14_nome.': '.@$rexec,1,0,"J",1);
    $pdf->Cell(55,4,$RLj13_descr.': '.@$bexec,1,0,"J",1);
    $pdf->Cell(20,4,$RLz01_numero.': '.@$y11_numero,1,"J",1,30);
    $pdf->Cell(40,4,$RLz01_compl.': '.substr(@$y11_compl, 0,17),1,"J",1,30);
    $pdf->Ln(4);
    $resultfiscal = $clvistusuario->sql_record($clvistusuario->sql_query($y70_codvist,"","db_usuarios.nome as fiscal,y75_obs"));
    if($clvistusuario->numrows > 0){
      $pdf->SetFillColor(230);
      $pdf->Cell(190,4,"FISCAIS",1,0,"C",1);
      $pdf->Ln(4);
      $pdf->SetFillColor(255);
      if($clvistusuario->numrows >= 1){

        $vir="";
        $nome_fiscal = "";
        $obser_fiscal = "";
        for($i=0;$i<$clvistusuario->numrows;$i++){

          db_fieldsmemory($resultfiscal,$i);
          $nome_fiscal .= $vir.$fiscal;
          $obser_fiscal .= $y75_obs.". ";
          $vir=",";
        }
        $pdf->multicell(190,4,$RLnome.": ".@$nome_fiscal.".".$RLy75_obs.": ".$obser_fiscal,1,"L",0);
      }
    }
    $resultrec = $clvistoriarec->sql_record($clvistoriarec->sql_query($y70_codvist));
    if($clvistoriarec->numrows > 0){
      $pdf->SetFillColor(230);
      $pdf->Cell(190,4,"RECEITAS",1,0,"C",1);
      $pdf->Ln(4);
      $pdf->SetFillColor(255);
      if($clvistoriarec->numrows >= 1){
        $pdf->Cell(40,4,$RLy76_receita.'',1,0,"C",1);
        $pdf->Cell(40,4,$RLy76_valor.'',1,0,"C",1);
        $pdf->Cell(110,4,$RLy76_descr.'',1,1,"C",1);
        $pdf->SetAligns(array("C","C","C"));
        $pdf->SetWidths(array(40,40,110));
        for($i=0;$i<$clvistoriarec->numrows;$i++){
          db_fieldsmemory($resultrec,$i);
          $pdf->SetAligns(array("C","C","C"));
          $pdf->Row(array($y76_receita,db_formatar($y76_valor,'f'),$y76_descr),3);
        }
      }
    }
    $pdf->Ln(10);
    if ($pdf->GetY() > 280) {
      $pdf->AddPage();
    }
    $total++;
  }

  //RELATORIO SINTETICO
  if ($tiporel=="sin"){
    $head1 = "RELATÓRIO DE VISTORIAS SINTÉTICO";

    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->SetFillColor(230);
      $pdf->cell(30,$alt,"$RLy70_codvist" ,1,0,"C",1);
      $pdf->cell(80,$alt,"$RLz01_nome" ,1,0,"L",1);
      $pdf->cell(40,$alt,"$RLy77_descricao" ,1,0,"C",1);
      $pdf->cell(40,$alt,"$RLy70_data" ,1,1,"C",1);
      $troca = 0;
    }
    $pdf->setfont('arial','',7);
    $pdf->cell(30,$alt,"$y70_codvist",0,0,"C",0);
    $pdf->cell(80,$alt,"$z01_nome",0,0,"L",0);
    $pdf->cell(40,$alt,"$y77_descricao",0,0,"C",0);
    $pdf->cell(40,$alt,db_formatar($y70_data,"d"),0,1,"C",0);
    $total ++;
  }
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',0,"L",0);
$pdf->output();