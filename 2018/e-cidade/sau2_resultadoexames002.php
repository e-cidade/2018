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

/**
 *
 * @author I
 * @revision $Author: dbeduardo.sirangelo $
 * @version $Revision: 1.5 $
 */
 include("fpdf151/pdf.php");
 include("libs/db_utils.php");


  $oGet = db_utils::postMemory($_GET);
  $sSql   = "select s113_i_codigo,";
  $sSql  .= "        s133_c_protocolo,";
  $sSql  .= "        z01_v_nome, ";
  $sSql  .= "        s110_i_codigo,";
  $sSql  .= "        z01_nome, ";
  $sSql  .= "        z01_ender, ";
  $sSql  .= "        z01_bairro, ";
  $sSql  .= "        z01_munic, ";
  $sSql  .= "        z01_i_cgsund, ";
  $sSql  .= "        sd63_i_codigo,";
  $sSql  .= "        sd63_c_procedimento,";
  $sSql  .= "        sd63_c_nome,";
  $sSql  .= "        s113_c_encaminhamento,";
  $sSql  .= "        s113_d_exame, ";
  $sSql  .= "        s113_c_hora,   ";
  $sSql  .= "        z01_d_nasc,  ";
  $sSql  .= "        z01_v_cgccpf,s133_i_codigo, ";
  $sSql  .= "        s133_c_observacoes";
  $sSql  .= "   from sau_agendaexames";
  $sSql  .= "        inner join sau_prestadorhorarios on s112_i_codigo = s113_i_prestadorhorarios";
  $sSql  .= "        inner join cgs_und on z01_i_cgsund = s113_i_numcgs  ";
  $sSql  .= "        inner join sau_agendaexameconfirma on s113_i_codigo = s133_i_agendaexames ";
  $sSql  .= "        inner join sau_prestadorvinculos on s111_i_codigo = s112_i_prestadorvinc ";
  $sSql  .= "        inner join sau_prestadores on s110_i_codigo = s111_i_prestador ";
  $sSql  .= "        inner join cgm on s110_i_numcgm = z01_numcgm ";
  $sSql  .= "        inner join sau_procedimento ON sau_procedimento.sd63_i_codigo = sau_prestadorvinculos.s111_procedimento ";
  $sSql  .= "   where s133_i_codigo = {$oGet->iCodigoExame}";
  $rsDados     = db_query($sSql);
  $oRelatorio  = db_utils::fieldsMemory($rsDados, 0);
  $pdf         = new PDF("P", "mm", "A4");

  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->SetAutoPageBreak(false);
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt   = 4;
  $head1 = "Resultado de Exame {$oRelatorio->s113_i_codigo}";
  $pdf->addPage();
  $pdf->cell(190,$alt,"Protocolo: {$oRelatorio->s133_c_protocolo}",0,1,"R");
  $pdf->cell(60,$alt,"Exame de : {$oRelatorio->sd63_c_nome}",0,1,"L");
  $pdf->cell(60,$alt,"Paciente : {$oRelatorio->z01_v_nome}",0,1,"L");
  $pdf->cell(60,$alt,"Data do Exame : ".db_formatar($oRelatorio->s113_d_exame, "d"),0,9,"L");
  $pdf->cell(60,$alt,"Prestadora    : ".$oRelatorio->z01_nome,0,1,"L");
  $pdf->ln();
  $pdf->Line(10,$pdf->GetY(),200, $pdf->GetY());
  $pdf->ln();

  $pdf->cell(70,$alt,"Atributos",1,0,"L",1);
  $pdf->cell(40,$alt,"Medidas",1,0,"L",1);
  $pdf->cell(40,$alt,"Valor",1,1,"L",1);


  $pdf->setfont('arial','',7);
  $oDaoExames        = db_utils::getDao("sau_examesatributos");
  $sWhere            = "s134_i_agendaexameconfirma = {$oGet->iCodigoExame}";
  $sSqlAtributos     = $oDaoExames->sql_query_atributovalores(null,"*","s132_i_codigo", $sWhere, $oGet->iCodigoExame);
  $rsAtributos       = $oDaoExames->sql_record($sSqlAtributos);
  $aValoresAtributos = db_utils::getCollectionByRecord($rsAtributos);
  foreach ($aValoresAtributos as $oValorAtributo) {

    $pdf->cell(70,$alt,$oValorAtributo->s131_c_descricao,1,0,"L");
    $pdf->cell(40,$alt,$oValorAtributo->m61_abrev,1,0,"L");
    $pdf->cell(40,$alt,$oValorAtributo->s134_c_valor,1,1,"R");

  }
  $pdf->Output();


?>