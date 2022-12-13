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

require_once(modification("fpdf151/pdfwebseller.php"));
require_once(modification("libs/db_utils.php"));

define( "ARQUIVO_MENSAGEM", "educacao.escola.edu2_rechumano002." );

try{

  $sCampos     = " case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal ";
  $sCampos    .= " else rechumanocgm.ed285_i_cgm end as ed20_i_codigo, ";
  $sCampos    .= " case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,  ";
  $sCampos    .= " case when ed20_i_tiposervidor = 1 then cgmrh.z01_cgccpf else cgmcgm.z01_cgccpf end  as z01_cgccpf,  ";
  $sCampos    .= " ed01_c_descr, ";
  $sCampos    .= " case when ed20_i_tiposervidor = 1 then regimerh.rh30_descr else regimecgm.rh30_descr";
  $sCampos    .= " end as rh30_descr";
  $sWhere      = " ed75_i_escola = $iEscola AND ed75_i_saidaescola is null AND ed01_i_codigo in ($atividades)";

  $oDaoRecHumano = new cl_rechumano;
  $sSqlRec       = $oDaoRecHumano->sql_query_relatorio("", $sCampos, $ordem, $sWhere);
  $rsRecHumano   = db_query($sSqlRec);

  if ( !$rsRecHumano ) {
    throw new DBException(  _M(ARQUIVO_MENSAGEM . "erro_buscar_profissionais") );
  }

  $iLinhasRec = pg_num_rows( $rsRecHumano );

  if ( $iLinhasRec == 0 ) {
    throw new BusinessException( _M(ARQUIVO_MENSAGEM . "nenhum_registro") );
  }

} catch (Exception $e) {

  $sMsg = urlencode($e->getMessage());
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
}


$oPdf = new Pdf();
$oPdf->Open();
$oPdf->AliasNbPages();
$head1 = "RELATÓRIO RECURSOS HUMANOS POR ATIVIDADE";
$oPdf->ln(5);
$lTroca = true;
$lCor   = true;
$iCont  = 0;

for ($iContador = 0; $iContador < $iLinhasRec; $iContador++) {

  $oDadosRh = db_utils::fieldsmemory($rsRecHumano, $iContador);
  if ($oPdf->gety() > $oPdf->h - 30 || $lTroca != 0 ) {

    $oPdf->addpage('P');
    $oPdf->setfillcolor(215);
    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(25, 4, "Matrícula/CGM", 1, 0, "C", 1);
    $oPdf->cell(70, 4, "Nome", 1, 0, "L", 1);
    $oPdf->cell(20, 4, "CPF", 1, 0, "C", 1);
    $oPdf->cell(40, 4, "Atividade", 1, 0, "C", 1);
    $oPdf->cell(35, 4, "Regime", 1, 1, "C", 1);
    $lTroca = false;

  }

  if ($lCor) {
    $lCor = false;
  } else {
    $lCor = true;
  }

  $oPdf->setfillcolor(240);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(25, 4, $oDadosRh->ed20_i_codigo, 0, 0, "C", $lCor);
  $oPdf->cell(70, 4, $oDadosRh->z01_nome, 0, 0, "L", $lCor);
  $oPdf->cell(20, 4, $oDadosRh->z01_cgccpf, 0, 0, "C", $lCor);
  $oPdf->cell(40, 4, $oDadosRh->ed01_c_descr == "" ? "Não informado" : $oDadosRh->ed01_c_descr, 0, 0, "C", $lCor);
  $oPdf->cell(35, 4, $oDadosRh->rh30_descr, 0, 1, "C", $lCor);
  $iCont++;

}
$oPdf->setfont('arial', 'b', 7);
$oPdf->cell(190, 5, "Total de Recursos Humanos: $iCont", 1, 1, "L", 0);
$oPdf->Output();
?>