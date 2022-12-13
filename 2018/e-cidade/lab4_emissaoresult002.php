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

$cllab_requisicao          = new cl_lab_requisicao;
$cllab_requiitem           = new cl_lab_requiitem;
$cllab_emissao             = new cl_lab_emissao;
$cllab_atributo_componente = new cl_lab_atributo_componente;
$oDaoLabResp               = new cl_lab_labresp();

$usuario      = DB_getsession("DB_id_usuario");
$sOrgaoClasse = "";
$sAssinatura  = "";

/**
 * Busca o Orgão Classe do usuário logado no sistema
 */
$sCamposLabResp = "la06_c_orgaoclasse as orgao_classe, la24_o_assinatura as assinatura";
$sSqlLabResp    = $oDaoLabResp->sql_query_setor( null, $sCamposLabResp, null, "id_usuario = {$usuario}" );
$rsLabResp      = db_query( $sSqlLabResp );

if ( pg_num_rows( $rsLabResp ) > 0 ) {

  $oDadosRetorno = db_utils::fieldsMemory( $rsLabResp, 0 );
  $sOrgaoClasse  = $oDadosRetorno->orgao_classe;
  $sAssinatura   = $oDadosRetorno->assinatura;
}

$aAtributosSelecionaveis  = array();
$oDaoAtributoSelecionavel = new cl_lab_valorreferenciasel();
$sSqlAtributos            = $oDaoAtributoSelecionavel->sql_query_file();
$rsAtributosSelecionaveis = $oDaoAtributoSelecionavel->sql_record($sSqlAtributos);

for ($iAtributo = 0; $iAtributo < $oDaoAtributoSelecionavel->numrows; $iAtributo++) {

  $oDadosAtributoSelecionavel = db_utils::fieldsMemory($rsAtributosSelecionaveis, $iAtributo);
  $aAtributosSelecionaveis[$oDadosAtributoSelecionavel->la28_i_codigo] = $oDadosAtributoSelecionavel->la28_c_descr;
}

$dDataAtual = date('Y-m-d', db_getsession('DB_datausu'));
$where      = " la22_i_codigo = {$requisicao} and la21_i_codigo = {$requiitem}";
$sCampos    = "*, fc_idade(z01_d_nasc, '$dDataAtual') as idade, la24_c_nomearq as imagem";
$sSql       = $cllab_requiitem->sql_query_nova( "", $sCampos, "", $where );
$result     = $cllab_requiitem->sql_record( $sSql );
if ($cllab_requiitem->numrows==0) {

  db_msgbox("Nenhum registro encontrado!");

}else{

  $oRequisicao       = new RequisicaoExame($requiitem);
  $oExame            = $oRequisicao->getExame();
  $oResultadoExame   = $oRequisicao->getResultado();
  $aAtributos        = $oExame->getAtributos();
  $aAtributosDoExame = array();
  foreach ($aAtributos as $oAtributo) {

    $oAtributoDoExame                  = new stdClass();
    $oAtributoDoExame->nome            = $oAtributo->getNome();
    $oAtributoDoExame->nivel           = $oAtributo->getNivel();
    $oAtributoDoExame->valorabsoluto   = '';
    $oAtributoDoExame->valorpercentual = '';
    $oAtributoDoExame->unidade         = '';
    $oAtributoDoExame->referencia      = '';
    $oAtributoDoExame->tipo            = $oAtributo->getTipo();
    $oAtributoDoExame->tiporeferencia  = $oAtributo->getTipoReferencia();
    $oResultadoAtributo                = $oResultadoExame->getValorDoAtributo($oAtributo);

    if ($oAtributo->getUnidadeMedida() != "") {
      $oAtributoDoExame->unidade = $oAtributo->getUnidadeMedida()->getNome();
    }
    if (!empty($oResultadoAtributo)) {

      $oAtributoDoExame->valorabsoluto   = $oResultadoAtributo->getValorAbsoluto();
      $oAtributoDoExame->valorpercentual = $oResultadoAtributo->getValorPercentual();

      switch ($oAtributo->getTipoReferencia() ) {

        case AtributoExame::REFERENCIA_NUMERICA:
        $oReferenciaAtributo  = $oResultadoAtributo->getFaixaUtilizada();

        if ($oReferenciaAtributo != '') {

          $sStringReferencia  = "({$oReferenciaAtributo->getValorMinimo()} - {$oReferenciaAtributo->getValorMaximo()})";
          $sStringReferencia .= " {$oAtributoDoExame->unidade}";
          $oAtributoDoExame->referencia  = $sStringReferencia;
        }
        break;

        case AtributoExame::REFERENCIA_SELECIONAVEL:

          $oAtributoDoExame->referencia    = $oAtributoDoExame->unidade;
          if (isset($aAtributosSelecionaveis[$oResultadoAtributo->getValorAbsoluto()])) {
            $oAtributoDoExame->valorabsoluto = $aAtributosSelecionaveis[$oResultadoAtributo->getValorAbsoluto()];
          }
          break;

        case AtributoExame::REFERENCIA_FIXA:

          $oAtributoDoExame->referencia    = $oAtributoDoExame->unidade;
          $oAtributoDoExame->valorabsoluto = $oResultadoAtributo->getValorAbsoluto();
          break;
      }
    }

    $aAtributosDoExame[] = $oAtributoDoExame;
  }
  $sFonte = "courier";
  db_fieldsmemory($result,0);
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->settopmargin(1);
  $pdf->SetAutoPageBreak('on',0);
  $pdf->setfillcolor(243);
  $head2 = "Emissão de Resultado";
  $head3 = "Exame : $la08_c_descr"; 
  $pdf->addpage('P');
  $pdf->ln(0);
  $alt = $pdf->getY();
  $larg= $pdf->getX();

  $pdf->setfont($sFonte, 'b', 10);
  $pdf->setY(35);
  $pdf->cell( 170, 5, "Requisição : {$la22_i_codigo}",            0, 1, "L", 0 );
  $pdf->cell( 100, 5, "Paciente : {$la22_i_cgs} - {$z01_v_nome}", 0, 1, "L", 0 );
  $pdf->cell( 100, 5, "Idade : {$idade}",                         0, 0, "L", 0 );

  if ( $z01_v_sexo == 'F' ) {
    $sexo = "FEMININO";
  } else {
    $sexo = "MASCULINO";
  }

  $pdf->cell(100, 5, "Exame  : {$la08_c_descr}",  0, 1, "L", 0 );
  $pdf->cell(100, 5, "Médico : {$la22_c_medico}", 0, 0, "L", 0 );
  $pdf->cell(100, 5, "Sexo : {$sexo}",            0, 1, "L", 0 );

  $pdf->setfont($sFonte, 'b', 8);
  $alt = $pdf->getY();
  $larg= $pdf->getX();
  $pdf->setfont($sFonte,'b',8);
  $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
  $pdf->setY(65);

  foreach ($aAtributosDoExame as $oAtributo) {

    switch ($oAtributo->tipo) {

      case AtributoExame::SINTETICO:

        escreverLinhaSintetica($pdf, $sFonte, $oAtributo);
        break;

      case AtributoExame::ANALITICO:

        escreverLinhaAnalitica($pdf, $sFonte, $oAtributo);
        break;
    }
  }

  $pdf->rect( 8, 250, $larg + 185, $alt - 30, 3, '', '1234' );
  $pdf->cell( 50, 5, "", 0, 1, "L", 0 );

  if (trim($oResultadoExame->getConsideracao()) != "") {

    $pdf->setfont( $sFonte, 'b', 8 );
    $pdf->cell( 10, 5, "Considerações:", 0, 1, "L", 0 );
    $pdf->setfont( $sFonte, '', 8 );
    $pdf->multicell( 0, 3, $oResultadoExame->getConsideracao(), 0, 1, "J", 0 );
  }
  if ($sAssinatura) {
  	
   $arquivo = "tmp/".$la24_c_nomearq;
   db_query("begin");
   pg_loexport( $sAssinatura, $arquivo );
   db_query("end");
  } else {
    $arquivo = "";
  }

  $oUsuarioSistema = new UsuarioSistema( $usuario );
  $pdf->setY(260);
  $pdf->Image( $arquivo, 90, $alt + 198, 25 );
  $pdf->cell( 110, 6, " Profissional : {$oUsuarioSistema->getIdUsuario()} - {$oUsuarioSistema->getNome()}", 0, 1, "L", 0 );
  $pdf->cell( 160, 6, " Órgão Classe : {$sOrgaoClasse} ",       0, 1, "L", 0 );

  $sNome = "Resultado({$la22_i_cgs})".$la22_i_codigo."_".date("d-m-Y",db_getsession("DB_datausu")).".pdf";
  $pdf->Output( "tmp/{$sNome}", false, true );

  db_inicio_transacao();
  $oidgrava = db_geraArquivoOidfarmacia("tmp/$sNome","",1,$conn); 
  $cllab_emissao->la34_o_laudo      = $oidgrava;
  $cllab_emissao->la34_c_nomearq    = "tmp/$sNome";
  $cllab_emissao->la34_d_data       = date("Y-m-d",db_getsession("DB_datausu"));
  $cllab_emissao->la34_c_hora       = db_hora();
  $cllab_emissao->la34_i_requiitem  = $requiitem;
  $cllab_emissao->la34_i_usuario    = $usuario; 
  $cllab_emissao->la34_i_forma      = 1;
  $cllab_emissao->incluir(null);
  db_fim_transacao() ;
  ?>
  <script>
  jan = window.open('tmp/<?=$sNome?>',
                    '',
                    'width='+(screen.availWidth-5)+
                    ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  </script>
  <?
}

function escreverLinhaSintetica(FPDF $oPdf, $sFonte, $oLinha) {

  $oPdf->SetFont($sFonte, "b", 10);
  $oPdf->Cell(150, 5, str_repeat(" ", $oLinha->nivel).$oLinha->nome, 0, 0, "L");
  if ($oLinha->nivel == 1) {
    $oPdf->Cell(40, 5, "Valores de Referência", 0, 0, "L");
  }
  $oPdf->ln();
  $oPdf->SetFont($sFonte, "", 8);
}

function escreverLinhaAnalitica(FPDF $oPdf, $sFonte, $oLinha) {

  $oPdf->SetFont($sFonte, "", 10);
  $oPdf->Cell(70, 4, str_repeat(" ", $oLinha->nivel).$oLinha->nome, 0, 0, "L", '', '', ".");
  $oPdf->Cell(5, 4, '', 0, 0, "L", '', '');
  $oPdf->Cell(20, 4, $oLinha->valorpercentual, 0, 0, "L");
  $oPdf->Cell(40, 4, $oLinha->valorabsoluto, 0, 0, "L");
  $oPdf->Cell(40, 4, $oLinha->referencia, 0, 1, "L");
}
?>