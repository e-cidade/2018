<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

set_time_limit(0);

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("classes/db_propri_classe.php"));
require_once(modification("classes/db_certid_classe.php"));
require_once(modification("classes/db_cfiptu_classe.php"));
require_once(modification("fpdf151/pdf3.php"));
require_once(modification("model/cda.model.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if( empty($ordenarpor) ){
  $ordenarpor = '';
}

if( empty($endaimp) ){
  $endaimp = '';
}

if( empty($totexe) ){
  $totexe = '';
}

/**
 * Rotina  em processo de refatoração
 */
try{

  $oGeradorCda = new GeradorCDA();
  if( !empty($sNomeArquivo) ){

    $oGeradorCda->gerar($tipo, $certid, $certid1, $reemissao, $ordenarpor, $totexe, $endaimp);
    return $oGeradorCda->escreverArquivo($sNomeArquivo);
  }else{

    $oGeradorCda->gerar($tipo, $certid, $certid1, $reemissao, $ordenarpor, $totexe, $endaimp);
    $oGeradorCda->exibirArquivo();
  }

} catch (Exception $eErro) {
  db_redireciona("db_erros.php?fechar=true&db_erro={$eErro->getMessage()}");
}

exit();

/**
 * @deprecated
 */
if ( !isset($certid) || $certid == '' ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Certidão Não Encontrada.');
  exit;
}

$oDaoParDiv   = db_utils::getDao("pardiv");
$sSqlPardiv   = $oDaoParDiv->sql_query_file(db_getsession("DB_instit"));
$rsPardiv     = $oDaoParDiv->sql_record($sSqlPardiv);
$lImpFolha    = false;

/*
 * Variavel tipo é retornada pelo $_GET
 *
 * $tipo
 *
 * 1 - Parcelamento
 * 2 - Divida
 */

$iCodigoDocumentoOrdena = 2033;
if ($tipo == 1) {

  $iCodigoDocumentoOrdena = 2034;
  $endaimp    = 'o';
  $ordenarpor = 'v14_certid';
  $totexe     = "f";
}
if ($oDaoParDiv->numrows > 0) {

  $oPardiv   = db_utils::fieldsMemory($rsPardiv, 0);
  $lImpFolha = $oPardiv->v04_implivrofolha=="t"?true:false;
}

$clcfiptu      = new cl_cfiptu;

$oDocumento    = new libdocumento(1008);
$oDocumento->getParagrafos();
$oDocumento->nro_parcelamento = 5;
$oDocumentoAgrupador = new libdocumento($iCodigoDocumentoOrdena);
$oDocumentoAgrupador->getParagrafos();
$oDocumentoAgrupador->nro_parcelamento = 5;
if ($oDocumento->lErro) {

  db_redireciona("db_erros.php?fechar=true&db_erro={$oDocumento->sMsgErro}.");
  exit;
}
if (count($oDocumento->aParagrafos) == 0) {

  db_redireciona("db_erros.php?fechar=true&db_erro=Configure o Documento 1008.");
  exit;
}

if (count($oDocumentoAgrupador->aParagrafos) == 0) {

  db_redireciona("db_erros.php?fechar=true&db_erro=Configure o Documento 2033.");
  exit;
}

$classinatura = new cl_assinatura;
$clpropri     = new cl_propri;

if (!isset($valormaximo) || $valormaximo == ""){
  $valormaximo = 99999999999;
}
if (!isset($valormonimo) || $valorminimo == ""){
  $valorminimo = 0;
}

/**
 * Buscamos o documento da que agrupa a CDA
 */
$exercicio = db_getsession("DB_anousu");
$borda     = 1;
$bordat    = 1;
$preenc    = 0;

$TPagina = 57;
$numero = ($certid1 - $certid) + 1;
$count_certid = 0;

class pdfcertidao extends pdf3 {

  function Header() {

    $sql = "select nomeinst,
                   bairro,
                   cgc,
                   trim(ender)||','||trim(cast(numero as text)) as ender,
                   upper(munic) as munic,
                   uf,
                   telef,
                   email,
                   url,
                   logo,
                   db12_extenso
              from db_config
						       inner join db_uf on db12_uf = uf
					   where codigo = ".db_getsession("DB_instit");

    $result = db_query($sql);

    global $nomeinst;
    global $ender;
    global $munic;
    global $cgc;
    global $bairro;
    global $uf;
    global $db12_extenso;
    global $logo;
    global $lImpFolha;

    db_fieldsmemory($result,0);
    $db12_extenso = pg_result($result,0,"db12_extenso");

    /// seta a margem esquerda que veio do relatorio
    $S      = $this->lMargin;
    $this->SetLeftMargin(10);
    $Letra  = 'Times';
    $posini = 20;

    $this->Image('imagens/files/'.$logo,$posini,8,24);
    $this->Ln(5);
    $this->SetFont($Letra,'',10);
    $this->MultiCell(0,4,$db12_extenso,0,"C",0);
    $this->SetFont($Letra,'B',13);
    $this->MultiCell(0,6,$nomeinst,0,"C",0);
    $this->SetFont($Letra,'B',12);
    $this->MultiCell(0,4,@$GLOBALS["head1"],0,"C",0);
    $this->SetLeftMargin($S);
    $this->Ln(1);

    $comprim = ($this->w - $this->rMargin - $this->lMargin);
    $Espaco  = $this->w - 80 ;

    if ($lImpFolha){

      $this->SetFont('Arial','',7);
      $this->SetFillColor(240);
      $this->RoundedRect(160,10, 40,20,2,"FD",'1234');
      $this->setfont('arial','',8);
      $this->Text(163,15,"Livro");
      $this->Text(172,15,":".@$GLOBALS["head2"]);
      $this->Text(163,18,"Folha" );
      $this->Text(172,18,":". @$GLOBALS["head3"]);
      $this->Text(163,21,"Data do Livro");
      $this->Text(180,21,":".db_formatar(@$GLOBALS["head4"],"d"));
    }

		$this->SetY(35);
		$this->setfont('arial','b',11);
	  $this->multicell(0,4,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$GLOBALS["head5"],0,"C",0,0);
	  $this->setfont('arial','',11);
	  $this->ln(3);
  }

}
///////////////////////////////////////////////////////////////////////
$pdf = new pdfcertidao(); // abre a classe
$pdf->open();             // abre o relatorio
$pdf->aliasnbpages();     // gera alias para as paginas
$pdf->SetAutoPageBreak('on',15);

$head5 = "";

for ($numcertid = 0; $numcertid < $numero; $numcertid++) {

  if ($tipo == 2) {

    $sql="select v14_certid,v13_dtemis
           from  certdiv
                 inner join divida 		on v01_coddiv = v14_coddiv
                                     and v01_instit = ".db_getsession('DB_instit')."
                 inner join certid    on v13_certid = v14_certid
                 left  join cgm       on v01_numcgm = z01_numcgm
           where v14_certid BETWEEN {$certid} AND {$certid1}
             and v14_certid not in ( {$count_certid} )
           order by {$ordenarpor} limit 1";
  } else {

    $sql="select v14_certid,v13_dtemis,v07_parcel,v07_numpre
           from  certter
                 inner join termo    on v07_parcel = v14_parcel
                                   and v07_instit = ".db_getsession('DB_instit')."
           inner join certid     on v13_certid = v14_certid
          where v14_certid BETWEEN {$certid} AND {$certid1}
            and v14_certid not in ( {$count_certid} )
          order by v14_certid,v07_numpre limit 1";
  }

  $rsCertidao    = db_query($sql);
  if (pg_num_rows($rsCertidao) == 0) {
    continue;
  }
  $oCertid       = db_utils::fieldsMemory($rsCertidao, 0);
  $oCDA          = new cda($oCertid->v14_certid);
  $aProcedencias = $oCDA->getProcedencias();

  $count_certid .= ",".$oCertid->v14_certid;
  $head2 = $oCDA->getLivro();
  $head3 = $oCDA->getFolha();
  $head4 = $oCDA->getDataLivro();
  $head5 = $oCertid->v14_certid."/".$oCDA->getAno();

  $sqlparag = "select db02_texto
                 from db_documento
                      inner join db_docparag on db03_docum = db04_docum
                      inner join db_tipodoc on db08_codigo  = db03_tipodoc
                      inner join db_paragrafo on db04_idparag = db02_idparag
                      where db03_tipodoc = 1017 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";

  $resparag = db_query($sqlparag);

  if (pg_numrows($resparag) == 0) {

    $head1 = 'SECRETARIA DE FINANÇAS';
  } else {

    db_fieldsmemory( $resparag, 0 );
    $head1 = $db02_texto;
  }

  $sSqlDadosInicial  = " select v51_inicial as numeroinicial,                                                                      ";
  $sSqlDadosInicial .= "        v70_codforo as processoforo                                                                        ";
  $sSqlDadosInicial .= "   from inicialcert                                                                                        ";
  $sSqlDadosInicial .= "        left join processoforoinicial on processoforoinicial.v71_inicial = inicialcert.v51_inicial              ";
  $sSqlDadosInicial .= "        left join processoforo        on processoforo.v70_sequencial     = processoforoinicial.v71_processoforo ";
  $sSqlDadosInicial .= "  where inicialcert.v51_certidao = {$oCertid->v14_certid}";
  $rsDadosInicial = db_query($sSqlDadosInicial);

  if (pg_numrows($rsDadosInicial) > 0) {

    $oDadosInicial = db_utils::fieldsMemory($rsDadosInicial, 0);
    $oDocumento->processoforo  = $oDadosInicial->processoforo;
    $oDocumento->numeroinicial = $oDadosInicial->numeroinicial;
  }

  $pdf->addPage(); // adiciona uma pagina
  $pdf->settextcolor(0,0,0);
  $pdf->setfillcolor(220);
  $pdf->setfont('arial','',11);

  try {

    foreach ($oDocumentoAgrupador->aParagrafos as $oParagrafo) {

      switch (trim(strtolower($oParagrafo->db02_descr))) {

        case "dados_devedor" :

          drawDevedores($pdf, $oCDA, $endaimp, $oCertid);
          break;

        case "dados_origem" :

          drawOrigens($pdf, $oCDA, $endaimp, $oCertid);
          break;

       case "certifico" :

         drawCertifico($pdf, $oCDA, @$oDocumento->aParagrafos[1]);
         break;

       case "dados_parcelamento" :

         drawDadosParcelamento($pdf, $oCDA, @$oParagrafo);
         break;

       case "fundamentacao_legal" :

         drawFundamentoLegal($pdf, $oCDA, $aProcedencias);
         break;

       case "metodologia" :

         drawMetodologia($pdf, $oCDA, $aProcedencias);
         break;

       case "quadro_debito_origem" :

         drawDebitos($pdf, $oCDA, $oPardiv,$totexe=="t"?true:false, $reemissao =="t"?true:false);
         break;

       case "quadro_debito_origem_corrigido" :

         drawDebitosOrigemCorrigido($pdf, $oCDA, $oPardiv,$totexe=="t"?true:false, $reemissao =="t"?true:false,true);
         break;

			 case "quadro_debito_origem_corrigido_data" :

         drawDebitosOrigemCorrigidoData($pdf, $oCDA, $oPardiv,$totexe=="t"?true:false, $reemissao =="t"?true:false,true);
         break;

       case "quadro_debito_origem_composicao" :

         drawDebitosComposicao($pdf, $oCDA, $oPardiv,$totexe=="t"?true:false, $reemissao =="t"?true:false,false);
         break;

       case "quadro_debito_origem_composicao_corrigido" :

         drawDebitosComposicao($pdf, $oCDA, $oPardiv,$totexe=="t"?true:false, $reemissao =="t"?true:false,true);
         break;

       case "quadro_debito_origem_composicao_anterior_primeiro_nivel" :

         drawDebitosOrigemComposicaoAnteriorPrimeiroNivel($pdf, $oCDA, $oPardiv, $oCertid, ($reemissao =="t"?true:false));
         break;

       case "total_debito_origem" :

         drawTotalizacaoDebitos($pdf, $oCDA, $oPardiv,$totexe=="t"?true:false, $reemissao =="t"?true:false);
         break;

       case "total_debito_origem_composicao" :

         drawTotalizacaoDebitos($pdf, $oCDA, $oPardiv,$totexe=="t"?true:false, $reemissao =="t"?true:false,true);
         break;

       case "total_debito_origem_composicao_corrigido" :

         drawTotalizacaoDebitos($pdf, $oCDA, $oPardiv,$totexe=="t"?true:false, $reemissao =="t"?true:false,true,true);
         break;

       case "totalizacao_demonstrativo" :

         drawTotalizacaoDemonstrativos($pdf, $oCDA, $oPardiv,$totexe=="t"?true:false, $reemissao =="t"?true:false,$oCertid,false,"vertical", $oCertid->v13_dtemis);
         break;

       case "totalizacao_demonstrativo_corrigido" :

         drawTotalizacaoDemonstrativos($pdf, $oCDA, $oPardiv,$totexe=="t"?true:false, $reemissao =="t"?true:false,$oCertid,true,"vertical",$oCertid->v13_dtemis);
         break;

       case "totalizacao_demonstrativo_corrigido_horizontal" :

         drawTotalizacaoDemonstrativos($pdf, $oCDA, $oPardiv,$totexe=="t"?true:false, $reemissao =="t"?true:false,$oCertid,true,"horizontal",$oCertid->v13_dtemis);
       break;

       case "quadro_debito_parcelamento" :

         if ($tipo == 1) {
           drawParcelamentos($pdf, $oCDA, $oPardiv, $oCertid, $reemissao =="t"?true:false,false);
         }
         break;

       case "quadro_debito_parcelamento_pagamentos" :

         if ($tipo == 1) {
           drawParcelamentosPago($pdf, $oCDA, $oPardiv, $oCertid, $reemissao =="t"?true:false,false);
         }
       	 break;

       case "quadro_debito_parcelamento_composicao" :

         if ($tipo == 1) {
           drawParcelamentos($pdf, $oCDA, $oPardiv, $oCertid, $reemissao =="t"?true:false,true);
         }
         break;

       case "texto_padrao" :

         drawTextoPadrao($pdf, $oCDA, @$oDocumento->aParagrafos[3]->db02_texto);
         break;

       case "data" :

         drawData($pdf, $oCDA,$oCertid->v13_dtemis, $reemissao =="t"?true:false);
         break;

       case "assinatura" :

         drawAssinaturas($pdf, $oCDA, $oDocumento->aParagrafos);
         break;

       case "cda_valor_inflator" :

       	drawDebitosInflator($pdf, $oCDA, $oPardiv, $oParagrafo->db02_texto, $totexe=="t"?true:false, $reemissao =="t"?true:false);
        break;

      }
    }
  } catch (Exception $eException) {
    db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
  }
}

$pdf->Output();

/**
 * Desenha o quadro dos devedores
 *
 * @param pdf3 $pdf
 * @param cda $oCertidao
 */
function drawDevedores($pdf, cda $oCertidao, $lTipoOrdem, $dadosCertidao) {

   $pdf->setfont('arial','B',10);

   $pdf->cell(190,5  ,'DEVEDOR(ES)',0   ,1,"C",0);
   $pdf->cell(190,0.7,''           ,"TB",1,"L",0);
   $pdf->Ln(5);

   $pdf->setfont('arial','B',10);

   $pdf->cell(30 ,5,'TIPO'     ,"TB",0,"L",0);
   $pdf->cell(110,5,'NOME'     ,1   ,0,"L",0);
   $pdf->cell(20 ,5,'CGM '     ,1   ,0,"L",0);
   $pdf->cell(30 ,5,'CPF/CNPJ',"TB" ,1,"L",0);

   $pdf->setfont('arial','',10);

   $aCgcCpf       = array();
   $aDadosDevedor = $oCertidao->getDevedoresEnvolvidos($lTipoOrdem);

   foreach ($aDadosDevedor->aDevedores as $oDevedor) {

     $aCgcCpf[] = $oDevedor->cgcCpf;

     $pdf->setfont('arial','',8);
     $pdf->cell(30 ,3,substr($oDevedor->tipo,0,15),0,0,"L",0);
     $pdf->Cell(110,3,$oDevedor->nome             ,0,0,"L",0);
     $pdf->Cell(20 ,3,$oDevedor->numcgm           ,0,0,"L",0);
     $pdf->Cell(30 ,3,$oDevedor->cgcCpf           ,0,1,"L",0);

     $pdf->setfont('arial','',8);
     $pdf->MultiCell(190,3,$oDevedor->endereco." Fone: ".$oDevedor->telefone,"B","L",0);
     $pdf->setfont('arial','',10);
   }
}

function drawOrigens ($pdf, cda $oCertidao, $lTipoOrdem, $dadosCertidao) {

  global $tipo, $lImpFolha;
  $aImoveis = $oCertidao->getDevedoresEnvolvidos($lTipoOrdem);

  if (count($aImoveis->aImoveis) > 0) {

    $pdf->setfont('','',10);
    $pdf->Ln(3);
    $pdf->setfont('arial','B',10);
    $pdf->cell(190,5,'DADOS DO IMÓVEL'  ,0,1,"C",0);
    $pdf->cell(190,0.7,''            ,"TB",1,"L",0);

  }

  $clcfiptu = new cl_cfiptu;
  $rsCfiptu = $clcfiptu->sql_record($clcfiptu->sql_query_file("","j18_utilizaloc","","j18_anousu = ".db_getsession("DB_anousu")));
  if ( $clcfiptu->numrows > 0 ) {
    $oCfiptu = db_utils::fieldsMemory($rsCfiptu,0);
  } else {
    $oCfiptu->j18_utilizaloc = 'f';
  }

  foreach ($aImoveis->aImoveis as $oOrigem) {

    /*if ($pdf->gety()>$pdf->h -45){

      $pdf->addPage();
      $pdf->SetFont('ARIAL','B',11);
      $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$oCertidao->getCodigo()."/{$oCertidao->getAno()}",0,"C",0,0);
      $pdf->ln(8);
      $pdf->setfont('','B',9);
    }*/
    $pdf->Ln(3);
    if ($tipo ==1) {

      $pdf->setfont('arial', '', 8);
      $pdf->cell(120, 3, 'ENDEREÇO: '.$oOrigem->endereco,    0, 0, "l", 0);
      $pdf->cell(40,  3, 'BAIRRO : '.$oOrigem->bairro,       0, 1, "l", 0);
      $pdf->cell(40,  3, 'SETOR  : '.$oOrigem->setor,        0, 0, "l", 0);
      $pdf->cell(40,  3, 'QUADRA : '.$oOrigem->quadra,       0, 0, "l", 0);
      $pdf->cell(40,  3, 'LOTE : '.$oOrigem->lote,           0, 0, "l", 0);
      $pdf->cell(40,  3, 'MATRÍCULA : '.$oOrigem->matricula, 0, 1, "l", 0);

      if ( $oCfiptu->j18_utilizaloc == 't' ) {
        $pdf->cell(60,5,'DADOS DE LOCALIZACAO: SETOR  : ' . $oOrigem->setorloc . '-' . $oOrigem->descrsetorloc . ' QUADRA : '.$oOrigem->quadraloc . ' - LOTE : '.$oOrigem->loteloc,0,0,"l",0);
        $pdf->ln();
      }

      $pdf->Ln(3);
      $pdf->Ln(3);
      $pdf->cell(190,0.7,'',"TB",1,"L",0);
      $pdf->Ln(3);

    } else if ($tipo == 2) {

      $pdf->setfont('arial', '', 8);
      $pdf->cell(120,3,'ENDEREÇO: '.$oOrigem->endereco,0,0,"l",0);
      $pdf->cell(40,3,'BAIRRO : '.$oOrigem->bairro,0,1,"l",0);
      $pdf->cell(110,3,'CIDADE : '.$oOrigem->cidade,0,0,"l",0);
      $pdf->cell(40,3,'CEP : '.$oOrigem->cep,0,1,"l",0);
      $pdf->cell(40,3,'SETOR  : '.$oOrigem->setor,0,0,"l",0);
      $pdf->cell(40,3,'QUADRA : '.$oOrigem->quadra,0,0,"l",0);
      $pdf->cell(40,3,'LOTE : '.$oOrigem->lote,0,0,"l",0);
      $pdf->cell(40,3,'MATRÍCULA : '.$oOrigem->matricula,0,1,"l",0);

      if ( $oCfiptu->j18_utilizaloc == 't' ) {
        $pdf->cell(60,5,'DADOS DE LOCALIZACAO: SETOR  : ' . $oOrigem->setorloc . '-' . $oOrigem->descrsetorloc . ' QUADRA : '.$oOrigem->quadraloc . ' - LOTE : '.$oOrigem->loteloc,0,0,"l",0);
        $pdf->ln();
      }

      $pdf->Ln(3);
      $pdf->cell(190,0.7,'',"TB",1,"L",0);
      $pdf->Ln(3);

    }

  }

  if (count($aImoveis->aEmpresas) > 0 ) {

    $pdf->Ln(3);
    $pdf->setfont('arial','B',10);
    $pdf->cell(190,7,'DADOS DA INSCRIÇÃO',0,1,"C",0);
    $pdf->setfont('arial','',10);
    $pdf->cell(190,0.7,'',"TB",1,"L",0);
    $pdf->Ln(3);
    foreach ($aImoveis->aEmpresas as $oOrigem) {

      if ($pdf->gety()>$pdf->h -68){

        $pdf->addPage();
        $pdf->SetFont('ARIAL','B',11);
        $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$oCertidao->getCodigo()."/{$oCertidao->getAno()}",0,"C",0,0);
        $pdf->setfont('','B',9);
        $pdf->ln(8);

      }
      $pdf->cell(35,5,'INSCRIÇÃO: ',0,0,"L",0);
      $pdf->cell(100,5,$oOrigem->inscricao,0,0,"L",0);
      if ($tipo ==1) {

        //$pdf->cell(40,5,'Parcelamento: ',0,0,"l",0);
        //$pdf->cell(10,5,$dadosCertidao->v07_parcel,0, 0,"l",0);

      }
      $pdf->ln();
      $pdf->cell(35,5,'REF. AO ALVARÁ : ',0,0,"L",0);
      $pdf->cell(100,5,$oOrigem->endereco,0,1,"L",0);
      $pdf->cell(35,5,'BAIRRO : ',0,0,"l",0);
      $pdf->cell(100,5,$oOrigem->bairro,0,1,"l",0);
      $pdf->cell(35,5,'CIDADE : ',0,0,"l",0);
      $pdf->cell(100,5,$oOrigem->cidade,0,0,"l",0);
      $pdf->cell(15,5,'CEP : ',0,0,"l",0);
      $pdf->cell(100,5,$oOrigem->cep,0,1,"l",0);
      $pdf->cell(190,0.7,'',"TB",1,"L",0);
      $pdf->Ln(3);
    }
  }
}

function drawCertifico($pdf, $oCertidao, $sTexto) {

  global $lImpFolha, $oDocumento;
  $pdf->setfont('','B',10);
  $pdf->MultiCell(0,5,$oDocumento->replaceText(@$sTexto->db02_texto),0,"L",0);
  $pdf->setfont('arial','',11);

}


function drawFundamentoLegal($pdf, $oCertidao, $aProcedencias) {

  global $tipo, $lImpFolha,$processo_protocolo_parcelamento,$observacoes_numpre_parcelamento;

  $lGerarFundamentacao = true;

  if ( count($aProcedencias) > 0  ) {

	  $sSqlFundamentacao  = "select distinct ";
	  $sSqlFundamentacao .= "       db02_texto  ";
	  $sSqlFundamentacao .= "  from db_documento  ";
	  $sSqlFundamentacao .= "       inner join procedparag on procedparag.v80_docum = db_documento.db03_docum ";
	  $sSqlFundamentacao .= "       inner join db_docparag  on db03_docum   = db04_docum ";
	  $sSqlFundamentacao .= "       inner join db_tipodoc   on db08_codigo  = db03_tipodoc ";
	  $sSqlFundamentacao .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
	  $sSqlFundamentacao .= " where ";
	  if ($tipo == 2) {

	    $sSqlFundamentacao .= "  v80_proced in ";
	    $sSqlFundamentacao .= " (".implode(",", $aProcedencias).") ";

	  } else if ($tipo == 1) {
	    $sSqlFundamentacao .= "  v80_proced in (".implode(",", $aProcedencias).")  ";
	  }
	  if ($lGerarFundamentacao) {

	    $rsFundamentacao     = db_query($sSqlFundamentacao);
	    $iTotalFundamentacao = pg_num_rows($rsFundamentacao);

	    if ( $iTotalFundamentacao > 0) {

	    	$processo_protocolo_parcelamento = $oCertidao->getProcessoParcelamento();
	    	$observacoes_numpre_parcelamento = $oCertidao->getObsNumpreParcelamento();

	      for ( $i = 0;$i < $iTotalFundamentacao; $i++) {

	        $oFundamentacao = db_utils::fieldsmemory($rsFundamentacao, $i);
	        $pdf->Ln(2);
	        $pdf->MultiCell(0,5,db_geratexto($oFundamentacao->db02_texto),0,"L",0);
	      }
	    }
	  }
  }
}


function drawMetodologia($pdf, cda $oCertidao, $aProcedencias) {

  global $tipo, $lImpFolha;
  $lGerarMetodologia = true;
  $sMetCalculo  = "select distinct v80_docmetcalculo, ";
  $sMetCalculo .= "       db02_texto  ";
  $sMetCalculo .= "  from db_documento  ";
  $sMetCalculo .= "       inner join procedparag on procedparag.v80_docmetcalculo = db_documento.db03_docum ";
  $sMetCalculo .= "       inner join db_docparag  on db03_docum   = db04_docum ";
  $sMetCalculo .= "       inner join db_tipodoc   on db08_codigo  = db03_tipodoc ";
  $sMetCalculo .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
  $sMetCalculo .= " where db03_tipodoc = 1050  ";

  if ($tipo == 2) {
    $sMetCalculo .= " and v80_proced in (".implode(",", $aProcedencias).") ";
  } else if ($tipo == 1) {
    $sMetCalculo .= " and v80_proced in (".implode(",", $aProcedencias).") ";
  }
  if ($lGerarMetodologia) {

    $resMetCalculo = db_query($sMetCalculo);
    if ($resMetCalculo) {

      $iNumRows = pg_num_rows($resMetCalculo);
      for ($v = 0; $v < $iNumRows; $v++) {

        $oMetodologia = db_utils::fieldsmemory($resMetCalculo, $v);
        $pdf->MultiCell(0,5,db_geratexto($oMetodologia->db02_texto),0,"L",0);
      }
    }
  }
}

/**
 * Escreve o quadro de Divida
 *
 * @param pdf3 $pdf
 * @param cda $oCertidao
 */

function drawDebitos(pdf3 $pdf, cda $oCertidao, $oPardiv, $lTotaliza=false, $lReemissao) {

  global $tipo, $lImpFolha;
  $aDebitos         = $oCertidao->getDebitos($lReemissao);
  if ($tipo == 1) {


    if ( count($aDebitos) > 0 ) {

      $lEscreveHeader = true;
      $iY = 0;
      foreach ($aDebitos as $oProcedencias) {



        /*if ($pdf->gety() > $pdf->h -30) {

          $pdf->addPage();
          $pdf->SetFont('ARIAL','B',11);
          $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$oCertidao->getCodigo()."/{$oCertidao->getAno()}",0,"C",0,0);
          $pdf->ln(8);
          $pdf->setfont('','B',9);
          $lEscreveHeader = true;
        }*/

        if ($lEscreveHeader) {

          $pdf->SetFont('','',7);
          $pdf->Ln(3);
          $pdf->MultiCell(0,5,'P R O C E D Ê N C I A ',0,"C",0);
          $pdf->Ln(3);
          $pdf->SetFont('','B',7);
          $pdf->Cell(15,5,"DÍVIDA",1,0,"C",1);
          $pdf->Cell(15,5,"T.PROCED",1,0,"C",1);
          $pdf->Cell(18,5,"CÓD. PROCED",1,0,"C",1);
          $pdf->Cell(50,5,"PROCEDÊNCIA",1,0,"C",1);
          $pdf->Cell(30,5,"DATA DE INSCRIÇÃO",1,0,"C",1);
          $pdf->Cell(15,5,"ORIGEM",1,0,"C",1);
          $pdf->Cell(15,5,"LIVRO",1,0,"C",1);
          $pdf->Cell(15,5,"FOLHA",1,0,"C",1);
          $pdf->Cell(15,5,"EXERCÍCIO",1,1,"C",1);
          $lEscreveHeader = false;
        }

        $sSqlTipoProcedencia  = "select case when v03_tributaria = 1 then 'TRIB'";
        $sSqlTipoProcedencia .= "            when v03_tributaria = 2 then 'N.TRIB' ";
        $sSqlTipoProcedencia .= "            when v03_tributaria = 3 then 'TCE' end as tipoproced ";
        $sSqlTipoProcedencia .= "  from proced ";
        $sSqlTipoProcedencia .= " where v03_codigo = {$oProcedencias->codigoprocedencia} limit 1";
        $rsProcedencia        = db_query($sSqlTipoProcedencia);
        $sProcedencia         = db_utils::fieldsMemory($rsProcedencia, 0)->tipoproced;
        $pdf->SetFont('','',7);
        $pdf->Cell(15,5,$oProcedencias->codigodivida,1,0,"C",0);
        $pdf->Cell(15,5, $sProcedencia, 1,0,"C",0);
        $pdf->Cell(18,5,$oProcedencias->codigoprocedencia,1,0,"C",0);
        $pdf->Cell(50,5,$oProcedencias->procedencia,1,0,"C",0);
        $pdf->Cell(30,5,db_formatar($oProcedencias->datainscricao,'d'),1,0,"C",0);
        $pdf->Cell(15,5,$oProcedencias->origem,1,0,"C",0);
        $pdf->Cell(15,5,$oProcedencias->livro,1,0,"C",0);
        $pdf->Cell(15,5,$oProcedencias->folha,1,0,"C",0);
        $pdf->Cell(15,5,$oProcedencias->exercicio,1,1,"C",0);
        $iY++;
        if ($oPardiv->v04_imphistcda == "t" && isset($oProcedencias->v01_obs)) {

          $pdf->SetFont('','I',5);
          $pdf->setX(10);
          $pdf->Cell(188,4,"Observação: $oProcedencias->observacao",1,1,"L",0);
          $pdf->SetFont('','',7);

        }
      }
    }

  } else if ($tipo == 2) {

    $aDebitosOrdenado = array();
    $aTotaisAno       = array();
    $oTotalGeral      = array();

    foreach ($aDebitos as $oDebito) {

      $aDebitosOrdenado[$oDebito->procedenciatributaria][] = $oDebito;
      if (!isset($aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio])) {

        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrhis = $oDebito->valorhistorico;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrcor = $oDebito->valorcorrigido;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrmul = $oDebito->valormulta;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrjur = $oDebito->valorjuros;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot = $oDebito->valortotal;
        if ($oDebito->certidmassa != 0) {
          $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot = $oDebito->valorcorrigido;
        }

      } else {

        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrhis += $oDebito->valorhistorico;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrcor += $oDebito->valorcorrigido;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrmul += $oDebito->valormulta;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrjur += $oDebito->valorjuros;

        if ($oDebito->certidmassa != 0) {
          $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot += $oDebito->valorcorrigido;
        } else {
          $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot += $oDebito->valortotal;
        }
      }
      if (!isset($oTotalGeral[$oDebito->procedenciatributaria])) {

        $oTotalGeral[$oDebito->procedenciatributaria]->valorhistorico = $oDebito->valorhistorico;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorcorrigido = $oDebito->valorcorrigido;
        $oTotalGeral[$oDebito->procedenciatributaria]->valormulta     = $oDebito->valormulta;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorjuros     = $oDebito->valorjuros;
        $oTotalGeral[$oDebito->procedenciatributaria]->valortotal     = $oDebito->valortotal;
        if ($oDebito->certidmassa != 0) {
          $oTotalGeral[$oDebito->procedenciatributaria]->valortotal     = $oDebito->valorcorrigido;
        }
      } else {

        $oTotalGeral[$oDebito->procedenciatributaria]->valorhistorico += $oDebito->valorhistorico;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorcorrigido += $oDebito->valorcorrigido;
        $oTotalGeral[$oDebito->procedenciatributaria]->valormulta     += $oDebito->valormulta;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorjuros     += $oDebito->valorjuros;
        if ($oDebito->certidmassa != 0) {
          $oTotalGeral[$oDebito->procedenciatributaria]->valortotal += $oDebito->valorcorrigido;
        } else {
          $oTotalGeral[$oDebito->procedenciatributaria]->valortotal += $oDebito->valortotal;

        }
      }
    }

    /**
     * Escrevemos o quadro dos creditos ;
     */
    foreach ($aDebitosOrdenado as $iTipo => $aTipo) {

      /*if ($pdf->gety() > $pdf->h -35) {

        $pdf->addPage();
        $pdf->SetFont('ARIAL','B',11);
        $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$oCertidao->getCodigo()."/{$oCertidao->getAno()}",0,"C",0,0);
        $pdf->ln(8);
        $pdf->setfont('','B',9);

      }*/
      $pdf->ln(3);
      if ($iTipo == 1) {

        $pdf->MultiCell(0,5,'C R É D I T O    T R I B U T Á R I O ',0,"C",0);
      } else {

        $pdf->setfont('','B',9);
        $pdf->MultiCell(0,5,'C R É D I T O  N Ã O  T R I B U T Á R I O ',0,"C",0);
      }
      $pdf->SetFont('','B',6);
      $pdf->Cell(10,5,"1 EXERC.",1,0,"C",1);
      $pdf->Cell(10,5,"PARC",1,0,"C",1);
      $pdf->Cell(10,5,"LIV/FOL",1,0,"C",1);
      $pdf->Cell(15,5,"ORIG.",1,0,"C",1);
      $pdf->Cell(30,5,"PROCEDÊNCIA",1,0,"C",1);
      $pdf->Cell(18,5,"ORIGEM DÉBITO",1,0,"C",1);
      $pdf->Cell(18,5,"DATA INSCR.",1,0,"C",1);
      $pdf->Cell(18,5,"DATA VENC.",1,0,"C",1);
      $pdf->Cell(15,5,"VLR HIST.",1,0,"C",1);
      $pdf->Cell(15,5,"CORRIGIDO",1,0,"C",1);
      $pdf->Cell(10,5,"MULTA",1,0,"C",1);
      $pdf->Cell(10,5,"JUROS",1,0,"C",1);
      $pdf->Cell(15,5,"TOTAL",1,1,"C",1);
      $lEscreveTotal      = false;
      $iExercicioAnterior = null;
      $pagina             = 0;
      $iY = 0;

      foreach ($aTipo as $oDebito) {

        if ( $oDebito->exercicio != $iExercicioAnterior && $lEscreveTotal && $lTotaliza) {

          $pdf->SetFont('','B',6);
          $pdf->Cell(129,5,"TOTAL EXERCICIO - {$iExercicioAnterior}",1,0,"C",0);
          $pdf->Cell(15,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrhis,'f'),1,0,"R",0);
          $pdf->Cell(15,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrcor,'f'),1,0,"R",0);
          $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrmul,'f'),1,0,"R",0);
          $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrjur,'f'),1,0,"R",0);
          $pdf->Cell(15,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrtot,'f'),1,1,"R",0);
          $pdf->setfont('','B',9);

        }
        $lEscreveTotal = true;
        if ($iY > 272){

          $pdf->AddPage();
          /*$pdf->SetFont('ARIAL','B',11);
          $pdf->multicell(0,5,"CERTIDÃO DE DÍVIDA ATIVA N".CHR(176)." ".$oCertidao->getCodigo()."/{$oCertidao->getAno()}",0,"C",0,0);
          $pdf->ln(8);*/
          $pdf->SetFont('','B',6);
          $pdf->Cell(10,5,"2 EXERC.",1,0,"C",1);
          $pdf->Cell(10,5,"PARC",1,0,"C",1);
          $pdf->Cell(10,5,"LIV/FOL",1,0,"C",1);
          $pdf->Cell(15,5,"ORIG.",1,0,"C",1);
          $pdf->Cell(30,5,"PROCEDÊNCIA",1,0,"C",1);
          $pdf->Cell(18,5,"ORIGEM DÉBITO",1,0,"C",1);
          $pdf->Cell(18,5,"DATA INSCR.",1,0,"C",1);
          $pdf->Cell(18,5,"DATA VENC.",1,0,"C",1);
          $pdf->Cell(15,5,"VLR HIST.",1,0,"C",1);
          $pdf->Cell(15,5,"CORRIGIDO",1,0,"C",1);
          $pdf->Cell(10,5,"MULTA",1,0,"C",1);
          $pdf->Cell(10,5,"JUROS",1,0,"C",1);
          $pdf->Cell(15,5,"TOTAL",1,1,"C",1);
          $pagina = $pdf->PageNo();

        }

        $pdf->SetFont('','',6);
        $pdf->Cell(10,5,$oDebito->exercicio,1,0,"C",0);
        $pdf->Cell(10,5,$oDebito->numpar,1,0,"C",0);
        $pdf->Cell(10,5,$oDebito->livro."/".$oDebito->folha,1,0,"C",0);
        $pdf->Cell(15,5,ucfirst($oDebito->origem)."/{$oDebito->codigoorigem}",1,0,"C",0);
        $pdf->Cell(30,5,$oDebito->procedencia,1,0,"L",0);
        $pdf->Cell(18,5,$oDebito->origemdebito,1,0,"C",0);
        $pdf->Cell(18,5,db_formatar($oDebito->datainscricao,'d'),1,0,"C",0);
        $pdf->Cell(18,5,db_formatar($oDebito->datavencimento,'d'),1,0,"C",0);
        $pdf->Cell(15,5,db_formatar($oDebito->valorhistorico,'f')    ,1,0,"R",0);
        $pdf->Cell(15,5,db_formatar($oDebito->valorcorrigido,'f')    ,1,0,"R",0);
        if ($oDebito->certidmassa == 0) {

          $pdf->Cell(10,5,db_formatar($oDebito->valormulta,'f'),1,0,"R",0);
          $pdf->Cell(10,5,db_formatar($oDebito->valorjuros,'f'),1,0,"R",0);
          $pdf->Cell(15,5,db_formatar($oDebito->valortotal,'f')   ,1,1,"R",0);

        } else {

          $pdf->Cell(10,5,db_formatar(0,'f')      ,1,0,"R",0);
          $pdf->Cell(10,5,db_formatar(0,'f')      ,1,0,"R",0);
          $pdf->Cell(15,5,db_formatar($oDebito->valorcorrigido,'f'),1,1,"R",0);

        }
        if ( $oPardiv->v04_imphistcda == "t" && isset($oDebito->observacao)) {

          $pdf->SetFont('','I',5);
          $pdf->setX(10);

          $pdf->SetAligns(array('J'));
          $pdf->SetWidths(array(194));
          $pdf->Row_multicell(array("Observação: {$oDebito->observacao}"),4,true,4,0,true,true,3,3);

          $pdf->SetFont('','',6);

        }

        $iExercicioAnterior = $oDebito->exercicio;
        $iY = $pdf->GetY();

      }

      /**
       * Escreve o total do ultimo ano
       */
      if (($lEscreveTotal && $lTotaliza)) {

         $pdf->SetFont('','B',6);
         $pdf->Cell(129,5,"TOTAL EXERCICIO - {$iExercicioAnterior}",1,0,"C",0);
         $pdf->Cell(15,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrhis,'f'),1,0,"R",0);
         $pdf->Cell(15,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrcor,'f'),1,0,"R",0);
         $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrmul,'f'),1,0,"R",0);
         $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrjur,'f'),1,0,"R",0);
         $pdf->Cell(15,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrtot,'f'),1,1,"R",0);
         $pdf->setfont('','B',9);

      }
      $pdf->SetFont('','B',6);
      $pdf->Cell(129,5,"TOTAL",1,0,"C",0);
      $pdf->Cell(15,5,db_formatar($oTotalGeral[$iTipo]->valorhistorico,'f'),1,0,"R",0);
      $pdf->Cell(15,5,db_formatar($oTotalGeral[$iTipo]->valorcorrigido,'f'),1,0,"R",0);
      $pdf->Cell(10,5,db_formatar($oTotalGeral[$iTipo]->valormulta ,'f'),1,0,"R",0);
      $pdf->Cell(10,5,db_formatar($oTotalGeral[$iTipo]->valorjuros,'f'),1,0,"R",0);
      $pdf->Cell(15,5,db_formatar($oTotalGeral[$iTipo]->valortotal,'f'),1,1,"R",0);
      $pdf->setfont('','B',9);

      $pdf->Ln(5);
    }
  }
}

function drawTotalizacaoDebitos(pdf3 $pdf, cda $oCertidao, $oPardiv, $lTotaliza=false, $lReemissao,$lComposicao=false,$lCorrigido=false) {

  global $tipo, $lImpFolha;

  $nTotalGeral         = 0;
  $nTotalGeralJuros    = 0;
  $nTotalGeralMulta    = 0;
  $nTotalGeralCorrecao = 0;
  $nTotalGeralHist     = 0;
  $lTributario         = false;
  $lNaoTributario      = false;


  if ( $tipo == 2 || !$lComposicao ) {

    $aDebitos = $oCertidao->getDebitos($lReemissao);
  } else {

    $oCertidao->setComposicao(true);
    $aDebitos = $oCertidao->getDebitos($lReemissao);
    $oCertidao->setComposicao(false);
  }

  foreach ($aDebitos as $oDebito) {

    if ($oDebito->procedenciatributaria == 1) {
      $lTributario    = true;  // Tributário
    } else {
      $lNaoTributario = true;  // Não Tributário
    }

    if ($lCorrigido) {
      $nValorCorr = $oDebito->valorcorrigido;
    } else {
      $nValorCorr = $oDebito->valorcorrecao;
    }

    if ($oDebito->certidmassa != 0) {
      $nTotalGeral       += $nValorCorr;
    } else {
      $nTotalGeral       += $oDebito->valortotal;
    }

    $nTotalGeralJuros    += $oDebito->valorjuros;
    $nTotalGeralMulta    += $oDebito->valormulta;
    $nTotalGeralCorrecao += $nValorCorr;
    $nTotalGeralHist     += $oDebito->valorhistorico;
  }

  if ($lTributario && $lNaoTributario) {

    $pdf->SetFont('','B',6);
    $pdf->Cell(141,5,"TOTAL GERAL",1,0,"C",0);
    $pdf->SetFont('','B',5);

    $pdf->Cell(10,5,db_formatar($nTotalGeralHist    ,'f'),1,0,"R",0);
    $pdf->Cell(11,5,db_formatar($nTotalGeralCorrecao,'f'),1,0,"R",0);
    $pdf->Cell(10,5,db_formatar($nTotalGeralMulta   ,'f'),1,0,"R",0);
    $pdf->Cell(10,5,db_formatar($nTotalGeralJuros   ,'f'),1,0,"R",0);
    $pdf->Cell(12,5,db_formatar($nTotalGeral        ,'f'),1,1,"R",0);

    $pdf->setfont('','B',9);
    $pdf->Ln(5);
  }
}

/**
 * Escreve o quadro de Origem Corrigida
 *
 * @param pdf3 $pdf
 * @param cda $oCertidao
 */

function drawDebitosOrigemCorrigido(pdf3 $pdf, cda $oCertidao, $oPardiv, $lTotaliza=false, $lReemissao, $lCorrigido) {

  global $tipo, $lImpFolha;

  $oCertidao->setComposicao(false);

  if ($tipo == 2 ) {

  	$aDebitos = $oCertidao->getDebitos($lReemissao);
  } else {

	  $aDebitos = $oCertidao->getDebitos($lReemissao);
  }

    $aDebitosOrdenado = array();
    $aTotaisAno       = array();
    $oTotalGeral      = array();

    foreach ($aDebitos as $oDebito) {

      if ($lCorrigido) {
        $nValorCorr = $oDebito->valorcorrigido;
      } else {
        $nValorCorr = $oDebito->valorcorrecao;
      }

      $aDebitosOrdenado[$oDebito->procedenciatributaria][] = $oDebito;
      if (!isset($aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio])) {

        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrhis = $oDebito->valorhistorico;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrcor = $nValorCorr;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrmul = $oDebito->valormulta;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrjur = $oDebito->valorjuros;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot = $oDebito->valortotal;

        if ($oDebito->certidmassa != 0) {
          $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot = $nValorCorr;
        }

      } else {

        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrhis += $oDebito->valorhistorico;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrcor += $nValorCorr;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrmul += $oDebito->valormulta;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrjur += $oDebito->valorjuros;

        if ($oDebito->certidmassa != 0) {
          $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot += $nValorCorr;
        } else {
          $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot += $oDebito->valortotal;
        }
      }
      if (!isset($oTotalGeral[$oDebito->procedenciatributaria])) {

        $oTotalGeral[$oDebito->procedenciatributaria]->valorhistorico = $oDebito->valorhistorico;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorcorrecao  = $nValorCorr;
        $oTotalGeral[$oDebito->procedenciatributaria]->valormulta     = $oDebito->valormulta;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorjuros     = $oDebito->valorjuros;
        $oTotalGeral[$oDebito->procedenciatributaria]->valortotal     = $oDebito->valortotal;
        if ($oDebito->certidmassa != 0) {
          $oTotalGeral[$oDebito->procedenciatributaria]->valortotal   = $oDebito->valorcorrigido;
        }
      } else {

        $oTotalGeral[$oDebito->procedenciatributaria]->valorhistorico += $oDebito->valorhistorico;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorcorrecao  += $nValorCorr;
        $oTotalGeral[$oDebito->procedenciatributaria]->valormulta     += $oDebito->valormulta;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorjuros     += $oDebito->valorjuros;

        if ($oDebito->certidmassa != 0) {
          $oTotalGeral[$oDebito->procedenciatributaria]->valortotal += $nValorCorr;
        } else {
          $oTotalGeral[$oDebito->procedenciatributaria]->valortotal += $oDebito->valortotal;

        }
      }
    }

    /**
     * Escrevemos o quadro dos creditos ;
     */
    foreach ($aDebitosOrdenado as $iTipo => $aTipo) {

      $pdf->ln(8);

      if ($iTipo == 1) {

        $pdf->MultiCell(0,5,'C R É D I T O    T R I B U T Á R I O ',0,"C",0);
      } else {

        $pdf->setfont('','B',9);
        $pdf->MultiCell(0,5,'C R É D I T O  N Ã O  T R I B U T Á R I O ',0,"C",0);
      }

      $lImprimeCab        = true;
      $lEscreveTotal      = false;
      $iExercicioAnterior = null;
      $pagina             = 0;
      $iY                 = 0;

      foreach ($aTipo as $oDebito) {

        if ( $oDebito->exercicio != $iExercicioAnterior && $lEscreveTotal && $lTotaliza) {

          $pdf->SetFont('','B',6);
          $pdf->Cell(141,5,"TOTAL EXERCICIO - {$iExercicioAnterior}",1,0,"C",0);
          $pdf->SetFont('','B',5);
          $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrhis,'f'),1,0,"R",0);
          $pdf->Cell(11,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrcor,'f'),1,0,"R",0);
          $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrmul,'f'),1,0,"R",0);
          $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrjur,'f'),1,0,"R",0);
          $pdf->Cell(12,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrtot,'f'),1,1,"R",0);
          $pdf->setfont('','B',9);
        }

        $lEscreveTotal = true;

        if ($iY > 272 || $lImprimeCab) {

        	if ( !$lImprimeCab ) {
	          $pdf->AddPage();
        	}

		      $pdf->SetFont('','B',5);
		      $pdf->Cell(13,5,"DÍVIDA"       ,1,0,"C",1);
		      $pdf->Cell(10,5,"NUMPRE"       ,1,0,"C",1);
		      $pdf->Cell(13,5,"DT LANC"      ,1,0,"C",1);
		      $pdf->Cell( 9,5,"EXERC"        ,1,0,"C",1);
		      $pdf->Cell( 6,5,"PARC"         ,1,0,"C",1);
		      $pdf->Cell(10,5,"LIV/FOL"      ,1,0,"C",1);
		      $pdf->Cell(12,5,"ORIG"         ,1,0,"C",1);
		      $pdf->Cell(27,5,"PROCEDÊNCIA"  ,1,0,"C",1);
		      $pdf->Cell(17,5,"ORIGEM DÉBITO",1,0,"C",1);
		      $pdf->Cell(12,5,"DT INSCR"     ,1,0,"C",1);
		      $pdf->Cell(12,5,"DT VENC"      ,1,0,"C",1);
		      $pdf->Cell(10,5,"VLR HIST"     ,1,0,"C",1);

		      if ( $lCorrigido ) {
		        $pdf->Cell(11,5,"CORRIGIDO"  ,1,0,"C",1);
		      } else {
		      	$pdf->Cell(11,5,"CORREÇÃO"   ,1,0,"C",1);
		      }

		      $pdf->Cell(10,5,"MULTA"        ,1,0,"C",1);
		      $pdf->Cell(10,5,"JUROS"        ,1,0,"C",1);
		      $pdf->Cell(12,5,"TOTAL"        ,1,1,"C",1);

          $pagina = $pdf->PageNo();
          $lImprimeCab = false;
        }

        if ( $lCorrigido ) {
        	$nVlrCorr = $oDebito->valorcorrigido;
        } else {
        	$nVlrCorr = $oDebito->valorcorrecao;
        }

        $pdf->SetFont('','',5);
        $pdf->Cell(13,5,$oDebito->codigodivida                   ,1,0,"C",0);
        $pdf->Cell(10,5,$oDebito->numpre                         ,1,0,"C",0);
        $pdf->Cell(13,5,db_formatar($oDebito->dataoperacao,'d')  ,1,0,"C",0);
        $pdf->Cell( 9,5,$oDebito->exercicio                      ,1,0,"C",0);
        $pdf->Cell( 6,5,$oDebito->numpar                         ,1,0,"C",0);
        $pdf->Cell(10,5,$oDebito->livro."/".$oDebito->folha      ,1,0,"C",0);
        $pdf->Cell(12,5,ucfirst($oDebito->origem)."/{$oDebito->codigoorigem}",1,0,"C",0);
        $pdf->Cell(27,5,$oDebito->procedencia                    ,1,0,"L",0);
        $pdf->Cell(17,5,$oDebito->origemdebito                   ,1,0,"C",0);
        $pdf->Cell(12,5,db_formatar($oDebito->datainscricao,'d') ,1,0,"C",0);
        $pdf->Cell(12,5,db_formatar($oDebito->datavencimento,'d'),1,0,"C",0);
        $pdf->Cell(10,5,db_formatar($oDebito->valorhistorico,'f'),1,0,"R",0);
        $pdf->Cell(11,5,db_formatar($nVlrCorr,'f')               ,1,0,"R",0);

        if ($oDebito->certidmassa == 0) {

          $pdf->Cell(10,5,db_formatar($oDebito->valormulta,'f')  ,1,0,"R",0);
          $pdf->Cell(10,5,db_formatar($oDebito->valorjuros,'f')  ,1,0,"R",0);
          $pdf->Cell(12,5,db_formatar($oDebito->valortotal,'f')  ,1,1,"R",0);
        } else {

          $pdf->Cell(10,5,db_formatar(0,'f')      ,1,0,"R",0);
          $pdf->Cell(10,5,db_formatar(0,'f')      ,1,0,"R",0);
          $pdf->Cell(12,5,db_formatar($nVlrCorr,'f'),1,1,"R",0);
        }

        if ( $oPardiv->v04_imphistcda == "t" && isset($oDebito->observacao)) {

          $pdf->SetFont('','I',5);
          $pdf->setX(10);

          $pdf->SetAligns(array('J'));
          $pdf->SetWidths(array(194));
          $pdf->Row_multicell(array("Observação: {$oDebito->observacao}"),4,true,4,0,true,true,3,3);

          $pdf->SetFont('','',6);
        }

        $iExercicioAnterior = $oDebito->exercicio;
        $iY = $pdf->GetY();
      }

      /**
       * Escreve o total do ultimo ano
       */
      if (($lEscreveTotal && $lTotaliza)) {

         $pdf->SetFont('','B',6);
         $pdf->Cell(141,5,"TOTAL EXERCICIO - {$iExercicioAnterior}"                       ,1,0,"C",0);
         $pdf->SetFont('','B',5);
         $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrhis,'f'),1,0,"R",0);
         $pdf->Cell(11,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrcor,'f'),1,0,"R",0);
         $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrmul,'f'),1,0,"R",0);
         $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrjur,'f'),1,0,"R",0);
         $pdf->Cell(12,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrtot,'f'),1,1,"R",0);
         $pdf->setfont('','B',9);
      }

      $pdf->SetFont('','B',6);
      $pdf->Cell(141,5,"TOTAL",1,0,"C",0);
      $pdf->SetFont('','B',5);
      $pdf->Cell(10,5,db_formatar($oTotalGeral[$iTipo]->valorhistorico,'f'),1,0,"R",0);
      $pdf->Cell(11,5,db_formatar($oTotalGeral[$iTipo]->valorcorrecao,'f') ,1,0,"R",0);
      $pdf->Cell(10,5,db_formatar($oTotalGeral[$iTipo]->valormulta ,'f')   ,1,0,"R",0);
      $pdf->Cell(10,5,db_formatar($oTotalGeral[$iTipo]->valorjuros,'f')    ,1,0,"R",0);
      $pdf->Cell(12,5,db_formatar($oTotalGeral[$iTipo]->valortotal,'f')    ,1,1,"R",0);
      $pdf->setfont('','B',9);
      $pdf->Ln(5);
    }
}

/**
 * Escreve o quadro de Origem Corrigida
 *
 * @param pdf3 $pdf
 * @param cda $oCertidao
 */

function drawDebitosOrigemCorrigidoData(pdf3 $pdf, cda $oCertidao, $oPardiv, $lTotaliza=false, $lReemissao, $lCorrigido) {

	global $tipo, $lImpFolha;

	$oCertidao->setComposicao(false);

	if ($tipo == 2 ) {

		$aDebitos = $oCertidao->getDebitos($lReemissao);
	} else {

		$aDebitos = $oCertidao->getDebitos($lReemissao);
	}

	$aDebitosOrdenado = array();
	$aTotaisAno       = array();
	$oTotalGeral      = array();

	foreach ($aDebitos as $oDebito) {

		if ($lCorrigido) {
			$nValorCorr = $oDebito->valorcorrigido;
		} else {
			$nValorCorr = $oDebito->valorcorrecao;
		}

		$aDebitosOrdenado[$oDebito->procedenciatributaria][] = $oDebito;
		if (!isset($aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio])) {

			$aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrhis = $oDebito->valorhistorico;
			$aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrcor = $nValorCorr;
			$aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrmul = $oDebito->valormulta;
			$aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrjur = $oDebito->valorjuros;
			$aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot = $oDebito->valortotal;

			if ($oDebito->certidmassa != 0) {
				$aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot = $nValorCorr;
			}

		} else {

			$aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrhis += $oDebito->valorhistorico;
			$aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrcor += $nValorCorr;
			$aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrmul += $oDebito->valormulta;
			$aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrjur += $oDebito->valorjuros;

			if ($oDebito->certidmassa != 0) {
				$aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot += $nValorCorr;
			} else {
				$aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot += $oDebito->valortotal;
			}
		}
		if (!isset($oTotalGeral[$oDebito->procedenciatributaria])) {

			$oTotalGeral[$oDebito->procedenciatributaria]->valorhistorico = $oDebito->valorhistorico;
			$oTotalGeral[$oDebito->procedenciatributaria]->valorcorrecao  = $nValorCorr;
			$oTotalGeral[$oDebito->procedenciatributaria]->valormulta     = $oDebito->valormulta;
			$oTotalGeral[$oDebito->procedenciatributaria]->valorjuros     = $oDebito->valorjuros;
			$oTotalGeral[$oDebito->procedenciatributaria]->valortotal     = $oDebito->valortotal;
			if ($oDebito->certidmassa != 0) {
				$oTotalGeral[$oDebito->procedenciatributaria]->valortotal   = $oDebito->valorcorrigido;
			}
		} else {

			$oTotalGeral[$oDebito->procedenciatributaria]->valorhistorico += $oDebito->valorhistorico;
			$oTotalGeral[$oDebito->procedenciatributaria]->valorcorrecao  += $nValorCorr;
			$oTotalGeral[$oDebito->procedenciatributaria]->valormulta     += $oDebito->valormulta;
			$oTotalGeral[$oDebito->procedenciatributaria]->valorjuros     += $oDebito->valorjuros;

			if ($oDebito->certidmassa != 0) {
				$oTotalGeral[$oDebito->procedenciatributaria]->valortotal += $nValorCorr;
			} else {
				$oTotalGeral[$oDebito->procedenciatributaria]->valortotal += $oDebito->valortotal;

			}
		}
	}

	/**
	 * Escrevemos o quadro dos creditos ;
	 */
	foreach ($aDebitosOrdenado as $iTipo => $aTipo) {

		$pdf->ln(8);

		if ($iTipo == 1) {

			$pdf->MultiCell(0,5,'C R É D I T O    T R I B U T Á R I O ',0,"C",0);
		} else {

			$pdf->setfont('','B',9);
			$pdf->MultiCell(0,5,'C R É D I T O  N Ã O  T R I B U T Á R I O ',0,"C",0);
		}

		$lImprimeCab        = true;
		$lEscreveTotal      = false;
		$iExercicioAnterior = null;
		$pagina             = 0;
		$iY                 = 0;

		foreach ($aTipo as $oDebito) {

			if ( $oDebito->exercicio != $iExercicioAnterior && $lEscreveTotal && $lTotaliza) {

				$pdf->SetFont('','B',6);
				$pdf->Cell(141,5,"TOTAL EXERCICIO - {$iExercicioAnterior}",1,0,"C",0);
				$pdf->SetFont('','B',5);
				$pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrhis,'f'),1,0,"R",0);
				$pdf->Cell(11,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrcor,'f'),1,0,"R",0);
				$pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrmul,'f'),1,0,"R",0);
				$pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrjur,'f'),1,0,"R",0);
				$pdf->Cell(12,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrtot,'f'),1,1,"R",0);
				$pdf->setfont('','B',9);
			}

			$lEscreveTotal = true;

			if ($iY > 272 || $lImprimeCab) {

				if ( !$lImprimeCab ) {
					$pdf->AddPage();
				}

				/*
				 * DIVIDA, NUMPRE, PARCELA, DATA DE LANÇAMENTO, DATA DE INSCRIÇÃO, DATA DE OPERAÇÃO,
				 * DATA DE VENCIMENTO, EXERCÍCIO, LIVRO/FOLHA, ORIGEM*, ORIGEM DO DÉBITO, PROCEDÊNCIA,
				 * VALOR HISTÓRICO, VALOR CORRIGIDO, JUROS, MULTA E TOTAL
				 *
				 * */

				$pdf->SetFont('','B',5);
				$pdf->Cell(13,5,"DÍVIDA"       ,1,0,"C",1);
				$pdf->Cell(10,5,"NUMPRE"       ,1,0,"C",1);
				$pdf->Cell( 6,5,"PARC"         ,1,0,"C",1);
				$pdf->Cell(13,5,"DT LANC"      ,1,0,"C",1);
				$pdf->Cell(13,5,"DT INSCR"     ,1,0,"C",1);
				$pdf->Cell(13,5,"DT OPER"      ,1,0,"C",1);
				$pdf->Cell(12,5,"DT VENC"      ,1,0,"C",1);
				$pdf->Cell( 7,5,"EXERC"        ,1,0,"C",1);
				$pdf->Cell(10,5,"LIV/FOL"      ,1,0,"C",1);
				$pdf->Cell(17,5,"ORIGEM DÉBITO",1,0,"C",1);
				$pdf->Cell(27,5,"PROCEDÊNCIA"  ,1,0,"C",1);
				$pdf->Cell(10,5,"VLR HIST"     ,1,0,"C",1);

				if ( $lCorrigido ) {
					$pdf->Cell(11,5,"CORRIGIDO"  ,1,0,"C",1);
				} else {
					$pdf->Cell(11,5,"CORREÇÃO"   ,1,0,"C",1);
				}

				$pdf->Cell(10,5,"JUROS"        ,1,0,"C",1);
				$pdf->Cell(10,5,"MULTA"        ,1,0,"C",1);
				$pdf->Cell(12,5,"TOTAL"        ,1,1,"C",1);

				$pagina = $pdf->PageNo();
				$lImprimeCab = false;
			}

			if ( $lCorrigido ) {
				$nVlrCorr = $oDebito->valorcorrigido;
			} else {
				$nVlrCorr = $oDebito->valorcorrecao;
			}

			$pdf->SetFont('','',5);
			$pdf->Cell(13,5,$oDebito->codigodivida                    ,1,0,"C",0);
			$pdf->Cell(10,5,$oDebito->numpre                          ,1,0,"C",0);
			$pdf->Cell( 6,5,$oDebito->numpar                          ,1,0,"C",0);
			$pdf->Cell(13,5,db_formatar($oDebito->datalancamento, 'd'),1,0,"C",0);
			$pdf->Cell(13,5,db_formatar($oDebito->datainscricao,'d')  ,1,0,"C",0);
			$pdf->Cell(13,5,db_formatar($oDebito->dataoperacao,'d')   ,1,0,"C",0);
			$pdf->Cell(12,5,db_formatar($oDebito->datavencimento,'d') ,1,0,"C",0);
			$pdf->Cell( 7,5,$oDebito->exercicio                       ,1,0,"C",0);
			$pdf->Cell(10,5,$oDebito->livro."/".$oDebito->folha       ,1,0,"C",0);
			$pdf->Cell(17,5,$oDebito->origemdebito                    ,1,0,"C",0);
			$pdf->Cell(27,5,$oDebito->procedencia                     ,1,0,"L",0);
			$pdf->Cell(10,5,db_formatar($oDebito->valorhistorico,'f') ,1,0,"R",0);
			$pdf->Cell(11,5,db_formatar($nVlrCorr,'f')                ,1,0,"R",0);

			if ($oDebito->certidmassa == 0) {

				$pdf->Cell(10,5,db_formatar($oDebito->valorjuros,'f')  ,1,0,"R",0);
				$pdf->Cell(10,5,db_formatar($oDebito->valormulta,'f')  ,1,0,"R",0);
				$pdf->Cell(12,5,db_formatar($oDebito->valortotal,'f')  ,1,1,"R",0);
			} else {

				$pdf->Cell(10,5,db_formatar(0,'f')      ,1,0,"R",0);
				$pdf->Cell(10,5,db_formatar(0,'f')      ,1,0,"R",0);
				$pdf->Cell(12,5,db_formatar($nVlrCorr,'f'),1,1,"R",0);
			}

			if ( $oPardiv->v04_imphistcda == "t" && isset($oDebito->observacao)) {

				$pdf->SetFont('','I',5);
				$pdf->setX(10);

				$pdf->SetAligns(array('J'));
				$pdf->SetWidths(array(194));
				$pdf->Row_multicell(array("Observação: {$oDebito->observacao}"),4,true,4,0,true,true,3,3);

				$pdf->SetFont('','',6);
			}

			$iExercicioAnterior = $oDebito->exercicio;
			$iY = $pdf->GetY();
		}

		/**
		 * Escreve o total do ultimo ano
		 */
		if (($lEscreveTotal && $lTotaliza)) {

			$pdf->SetFont('','B',6);
			$pdf->Cell(141,5,"TOTAL EXERCICIO - {$iExercicioAnterior}"                       ,1,0,"C",0);
			$pdf->SetFont('','B',5);
			$pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrhis,'f'),1,0,"R",0);
			$pdf->Cell(11,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrcor,'f'),1,0,"R",0);
			$pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrmul,'f'),1,0,"R",0);
			$pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrjur,'f'),1,0,"R",0);
			$pdf->Cell(12,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrtot,'f'),1,1,"R",0);
			$pdf->setfont('','B',9);
		}

		$pdf->SetFont('','B',6);
		$pdf->Cell(141,5,"TOTAL",1,0,"C",0);
		$pdf->SetFont('','B',5);
		$pdf->Cell(10,5,db_formatar($oTotalGeral[$iTipo]->valorhistorico,'f'),1,0,"R",0);
		$pdf->Cell(11,5,db_formatar($oTotalGeral[$iTipo]->valorcorrecao,'f') ,1,0,"R",0);
		$pdf->Cell(10,5,db_formatar($oTotalGeral[$iTipo]->valormulta ,'f')   ,1,0,"R",0);
		$pdf->Cell(10,5,db_formatar($oTotalGeral[$iTipo]->valorjuros,'f')    ,1,0,"R",0);
		$pdf->Cell(12,5,db_formatar($oTotalGeral[$iTipo]->valortotal,'f')    ,1,1,"R",0);
		$pdf->setfont('','B',9);
		$pdf->Ln(5);
	}
}

/**
 * Escreve o quadro de Composição da Divida
 *
 * @param pdf3 $pdf
 * @param cda $oCertidao
 */

function drawDebitosComposicao(pdf3 $pdf, cda $oCertidao, $oPardiv, $lTotaliza=false, $lReemissao,$lCorrigido) {

  global $tipo, $lImpFolha;


  if ($tipo == 2 ) {

  	$aDebitos = $oCertidao->getDebitos($lReemissao);
  } else {

	  //$oCertidao->setComposicao(true);
	  $aDebitos = $oCertidao->getDebitos($lReemissao);
	  //$oCertidao->setComposicao(false);
  }

    $aDebitosOrdenado = array();
    $aTotaisAno       = array();
    $oTotalGeral      = array();

    foreach ($aDebitos as $oDebito) {

      if ($lCorrigido) {
        $nValorCorr = $oDebito->valorcorrigido;
      } else {
        $nValorCorr = $oDebito->valorcorrecao;
      }

      $aDebitosOrdenado[$oDebito->procedenciatributaria][] = $oDebito;
      if (!isset($aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio])) {

        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrhis = $oDebito->valorhistorico;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrcor = $nValorCorr;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrmul = $oDebito->valormulta;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrjur = $oDebito->valorjuros;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot = $oDebito->valortotal;

        if ($oDebito->certidmassa != 0) {
          $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot = $nValorCorr;
        }

      } else {

        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrhis += $oDebito->valorhistorico;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrcor += $nValorCorr;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrmul += $oDebito->valormulta;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrjur += $oDebito->valorjuros;

        if ($oDebito->certidmassa != 0) {
          $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot += $nValorCorr;
        } else {
          $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot += $oDebito->valortotal;
        }
      }
      if (!isset($oTotalGeral[$oDebito->procedenciatributaria])) {

        $oTotalGeral[$oDebito->procedenciatributaria]->valorhistorico = $oDebito->valorhistorico;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorcorrecao  = $nValorCorr;
        $oTotalGeral[$oDebito->procedenciatributaria]->valormulta     = $oDebito->valormulta;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorjuros     = $oDebito->valorjuros;
        $oTotalGeral[$oDebito->procedenciatributaria]->valortotal     = $oDebito->valortotal;
        if ($oDebito->certidmassa != 0) {
          $oTotalGeral[$oDebito->procedenciatributaria]->valortotal   = $oDebito->valorcorrigido;
        }
      } else {

        $oTotalGeral[$oDebito->procedenciatributaria]->valorhistorico += $oDebito->valorhistorico;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorcorrecao  += $nValorCorr;
        $oTotalGeral[$oDebito->procedenciatributaria]->valormulta     += $oDebito->valormulta;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorjuros     += $oDebito->valorjuros;

        if ($oDebito->certidmassa != 0) {
          $oTotalGeral[$oDebito->procedenciatributaria]->valortotal += $nValorCorr;
        } else {
          $oTotalGeral[$oDebito->procedenciatributaria]->valortotal += $oDebito->valortotal;

        }
      }
    }

    /**
     * Escrevemos o quadro dos creditos ;
     */
    foreach ($aDebitosOrdenado as $iTipo => $aTipo) {

      $pdf->ln(8);

      if ($iTipo == 1) {

        $pdf->MultiCell(0,5,'C R É D I T O    T R I B U T Á R I O ',0,"C",0);
      } else {

        $pdf->setfont('','B',9);
        $pdf->MultiCell(0,5,'C R É D I T O  N Ã O  T R I B U T Á R I O ',0,"C",0);
      }

      $lImprimeCab        = true;
      $lEscreveTotal      = false;
      $iExercicioAnterior = null;
      $pagina             = 0;
      $iY                 = 0;

      foreach ($aTipo as $oDebito) {

        if ( $oDebito->exercicio != $iExercicioAnterior && $lEscreveTotal && $lTotaliza) {

          $pdf->SetFont('','B',6);
          $pdf->Cell(141,5,"TOTAL EXERCICIO - {$iExercicioAnterior}",1,0,"C",0);
          $pdf->SetFont('','B',5);
          $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrhis,'f'),1,0,"R",0);
          $pdf->Cell(11,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrcor,'f'),1,0,"R",0);
          $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrmul,'f'),1,0,"R",0);
          $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrjur,'f'),1,0,"R",0);
          $pdf->Cell(12,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrtot,'f'),1,1,"R",0);
          $pdf->setfont('','B',9);
        }

        $lEscreveTotal = true;

        if ($iY > 272 || $lImprimeCab) {

        	if ( !$lImprimeCab ) {
	          $pdf->AddPage();
        	}

		      $pdf->SetFont('','B',5);
		      $pdf->Cell(13,5,"DÍVIDA"       ,1,0,"C",1);
		      $pdf->Cell(10,5,"NUMPRE"       ,1,0,"C",1);
		      $pdf->Cell(13,5,"DT LANC"      ,1,0,"C",1);
		      $pdf->Cell( 9,5,"EXERC"        ,1,0,"C",1);
		      $pdf->Cell( 6,5,"PARC"         ,1,0,"C",1);
		      $pdf->Cell(10,5,"LIV/FOL"      ,1,0,"C",1);
		      $pdf->Cell(12,5,"ORIG"         ,1,0,"C",1);
		      $pdf->Cell(27,5,"PROCEDÊNCIA"  ,1,0,"C",1);
		      $pdf->Cell(17,5,"ORIGEM DÉBITO",1,0,"C",1);
		      $pdf->Cell(12,5,"DT INSCR"     ,1,0,"C",1);
		      $pdf->Cell(12,5,"DT VENC"      ,1,0,"C",1);
		      $pdf->Cell(10,5,"VLR HIST"     ,1,0,"C",1);

		      if ( $lCorrigido ) {
		        $pdf->Cell(11,5,"CORRIGIDO"  ,1,0,"C",1);
		      } else {
		      	$pdf->Cell(11,5,"CORREÇÃO"   ,1,0,"C",1);
		      }

		      $pdf->Cell(10,5,"MULTA"        ,1,0,"C",1);
		      $pdf->Cell(10,5,"JUROS"        ,1,0,"C",1);
		      $pdf->Cell(12,5,"TOTAL"        ,1,1,"C",1);

          $pagina = $pdf->PageNo();
          $lImprimeCab = false;
        }

        if ( $lCorrigido ) {
        	$nVlrCorr = $oDebito->valorcorrigido;
        } else {
        	$nVlrCorr = $oDebito->valorcorrecao;
        }

        $pdf->SetFont('','',5);
        $pdf->Cell(13,5,$oDebito->codigodivida                   ,1,0,"C",0);
        $pdf->Cell(10,5,$oDebito->numpre                         ,1,0,"C",0);
        $pdf->Cell(13,5,db_formatar($oDebito->dataoperacao,'d')  ,1,0,"C",0);
        $pdf->Cell( 9,5,$oDebito->exercicio                      ,1,0,"C",0);
        $pdf->Cell( 6,5,$oDebito->numpar                         ,1,0,"C",0);
        $pdf->Cell(10,5,$oDebito->livro."/".$oDebito->folha      ,1,0,"C",0);
        $pdf->Cell(12,5,ucfirst($oDebito->origem)."/{$oDebito->codigoorigem}",1,0,"C",0);
        $pdf->Cell(27,5,$oDebito->procedencia                    ,1,0,"L",0);
        $pdf->Cell(17,5,$oDebito->origemdebito                   ,1,0,"C",0);
        $pdf->Cell(12,5,db_formatar($oDebito->datainscricao,'d') ,1,0,"C",0);
        $pdf->Cell(12,5,db_formatar($oDebito->datavencimento,'d'),1,0,"C",0);
        $pdf->Cell(10,5,db_formatar($oDebito->valorhistorico,'f'),1,0,"R",0);
        $pdf->Cell(11,5,db_formatar($nVlrCorr,'f')               ,1,0,"R",0);

        if ($oDebito->certidmassa == 0) {

          $pdf->Cell(10,5,db_formatar($oDebito->valormulta,'f')  ,1,0,"R",0);
          $pdf->Cell(10,5,db_formatar($oDebito->valorjuros,'f')  ,1,0,"R",0);
          $pdf->Cell(12,5,db_formatar($oDebito->valortotal,'f')  ,1,1,"R",0);
        } else {

          $pdf->Cell(10,5,db_formatar(0,'f')      ,1,0,"R",0);
          $pdf->Cell(10,5,db_formatar(0,'f')      ,1,0,"R",0);
          $pdf->Cell(12,5,db_formatar($nVlrCorr,'f'),1,1,"R",0);
        }

        if ( $oPardiv->v04_imphistcda == "t" && isset($oDebito->observacao)) {

          $pdf->SetFont('','I',5);
          $pdf->setX(10);

          $pdf->SetAligns(array('J'));
          $pdf->SetWidths(array(194));
          $pdf->Row_multicell(array("Observação: {$oDebito->observacao}"),4,true,4,0,true,true,3,3);

          $pdf->SetFont('','',6);
        }

        $iExercicioAnterior = $oDebito->exercicio;
        $iY = $pdf->GetY();
      }

      /**
       * Escreve o total do ultimo ano
       */
      if (($lEscreveTotal && $lTotaliza)) {

         $pdf->SetFont('','B',6);
         $pdf->Cell(141,5,"TOTAL EXERCICIO - {$iExercicioAnterior}"                       ,1,0,"C",0);
         $pdf->SetFont('','B',5);
         $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrhis,'f'),1,0,"R",0);
         $pdf->Cell(11,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrcor,'f'),1,0,"R",0);
         $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrmul,'f'),1,0,"R",0);
         $pdf->Cell(10,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrjur,'f'),1,0,"R",0);
         $pdf->Cell(12,5,db_formatar($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrtot,'f'),1,1,"R",0);
         $pdf->setfont('','B',9);
      }

      $pdf->SetFont('','B',6);
      $pdf->Cell(141,5,"TOTAL",1,0,"C",0);
      $pdf->SetFont('','B',5);
      $pdf->Cell(10,5,db_formatar($oTotalGeral[$iTipo]->valorhistorico,'f'),1,0,"R",0);
      $pdf->Cell(11,5,db_formatar($oTotalGeral[$iTipo]->valorcorrecao,'f') ,1,0,"R",0);
      $pdf->Cell(10,5,db_formatar($oTotalGeral[$iTipo]->valormulta ,'f')   ,1,0,"R",0);
      $pdf->Cell(10,5,db_formatar($oTotalGeral[$iTipo]->valorjuros,'f')    ,1,0,"R",0);
      $pdf->Cell(12,5,db_formatar($oTotalGeral[$iTipo]->valortotal,'f')    ,1,1,"R",0);
      $pdf->setfont('','B',9);
      $pdf->Ln(5);
    }
}


function drawTotalizacaoDemonstrativos(pdf3 $pdf, cda $oCertidao, $oPardiv, $lTotaliza=false, $lReemissao,$oDadosCertidao,$lCorrigido, $sPosicao="vertical",$sData) {

  global $tipo, $lImpFolha;

  $oTotalGeral                  = array();
  $oTotalGeral['nVlrHistorico'] = 0;
  $oTotalGeral['nVlrCorrecao']  = 0;
  $oTotalGeral['nVlrMulta']     = 0;
  $oTotalGeral['nVlrJuros']     = 0;
  $oTotalGeral['nVlrTotal']     = 0;

  if ($tipo == 1) {

	  if ($lReemissao) {

	    $v13_dtemis = $oDadosCertidao->v13_dtemis;
	    $dataemis   = mktime(0,0,0,substr($v13_dtemis,5,2),substr($v13_dtemis,8,2),substr($v13_dtemis,0,4));
	    $anoemis    = substr($v13_dtemis,0,4);
	  } else {

	    $dataemis   = db_getsession("DB_datausu");
	    $anoemis    = db_getsession("DB_anousu");
	  }

	  $sSqlNumpres  = "select v07_numpre,v07_parcel  ";
	  $sSqlNumpres .= "  from certter                ";
	  $sSqlNumpres .= "       inner join termo on v07_parcel = v14_parcel ";
	  $sSqlNumpres .= " where v14_certid = {$oDadosCertidao->v14_certid}";
	  $rsNumpres    = db_query($sSqlNumpres);
	  $aNumpres     = db_utils::getCollectionByRecord($rsNumpres);

	  foreach ($aNumpres as $oNumpre) {

	    $sSqlArrecad  = "select *
	                       from arrecad
	                      where k00_numpre = {$oNumpre->v07_numpre} ";

	    $rsArrecad    = db_query($sSqlArrecad);

	    if (pg_num_rows($rsArrecad) > 0) {

	      $rsDebitos      = debitos_numpre($oNumpre->v07_numpre,0,0,$dataemis,$anoemis,0);
	      $iTotalDebitos  = pg_num_rows($rsDebitos);

	    } else {

	      $result_arreold = db_query("select *
	                                   from arreold
	                                  where k00_numpre = {$oNumpre->v07_numpre}");

	      if (pg_numrows($result_arreold) > 0)  {

	        $rsDebitos = debitos_numpre_old($oDadosCertidao->v07_numpre,0,0,$dataemis,$anoemis,0);
	        $iTotalDebitos = pg_numrows($rsDebitos);

	      } else {

	        $sqlprocuraarreforo = "select k00_numpre,
																        k00_numpar,
																        k00_numcgm,
																        k00_dtoper,
																        k00_receit,
																        k00_hist,
																        k00_valor,
																        k00_dtvenc,
																        k00_numtot,
																        k00_numdig,
																        k00_tipo
												          from arreforo
                                 where k00_certidao = {$oCertidao->getCodigo()}";

          $resultprocuraarreforo = db_query($sqlprocuraarreforo) or die($sqlprocuraarreforo);

	        if (pg_num_rows($resultprocuraarreforo) > 0) {

	          $sqlInsertArreold = "insert into arreold ( k00_numpre,k00_numpar,k00_numcgm,k00_dtoper,k00_receit,k00_hist,k00_valor, k00_dtvenc,k00_numtot,k00_numdig,k00_tipo ) $sqlprocuraarreforo ";
	          db_query($sqlInsertArreold) or die($sqlInsertArreold);
	          $rsDebitos = debitos_numpre_old($oDadosCertidao->v07_numpre,0,0,$dataemis,$anoemis,0,'','');

	        }else{

	          $iTotalDebitos = 0;
	          db_redireciona("db_erros.php?fechar=true&db_erro=Os debitos da origem CDA {$oCertidao->getCodigo()} não foram encontrados, provavelmente pagos ou cancelados!!<br>Contate Suporte!!");
	          exit;
	        }
	      }
	    }

	    for ($i = 0; $i < $iTotalDebitos; $i++) {

	      $oDebito = db_utils::fieldsmemory($rsDebitos, $i);

	      if ($lCorrigido) {
	      	$nVlrCorr = $oDebito->vlrcor;
	      } else {
	      	$nVlrCorr = $oDebito->vlrcor - $oDebito->vlrhis;
	      }

		    $oTotalGeral['nVlrHistorico'] += $oDebito->vlrhis;
		    $oTotalGeral['nVlrCorrecao']  += $nVlrCorr;
		    $oTotalGeral['nVlrMulta']     += $oDebito->vlrmulta;
		    $oTotalGeral['nVlrJuros']     += $oDebito->vlrjuros;
		    $oTotalGeral['nVlrTotal']     += $oDebito->total;

	    }

	  }

  } else {

    $aDebitos = $oCertidao->getDebitos($lReemissao);

	  foreach ($aDebitos as $oDebito) {

	    if ($lCorrigido) {
        $nVlrCorr = $oDebito->valorcorrigido;
      } else {
        $nVlrCorr = $oDebito->valorcorrecao;
      }

	    $oTotalGeral['nVlrHistorico'] += $oDebito->valorhistorico;
	    $oTotalGeral['nVlrCorrecao']  += $nVlrCorr;
	    $oTotalGeral['nVlrMulta']     += $oDebito->valormulta;
	    $oTotalGeral['nVlrJuros']     += $oDebito->valorjuros;

	    if ($oDebito->certidmassa != 0) {
	      $oTotalGeral['nVlrTotal'] += $nVlrCorr;
	    } else {
	      $oTotalGeral['nVlrTotal'] += $oDebito->valortotal;
	    }
	  }
  }

  if ($lReemissao) {

    $dataemis = mktime(0,0,0,substr($sData,5,2),
                             substr($sData,8,2),
                             substr($sData,0,4)
                             );
    $anoemis  = substr($sData,0,4);
    $xmes     = substr($sData,5,2);
    $xdia     = substr($sData,8,2);
    $xano     = substr($sData,0,4);

  } else {

    $dataemis = db_getsession("DB_datausu");
    $anoemis  = db_getsession("DB_anousu");
    $xdia = substr(date("Y-m-d",db_getsession("DB_datausu")),8,2);
    $xmes = substr(date("Y-m-d",db_getsession("DB_datausu")),5,2);
    $xano = substr(date("Y-m-d",db_getsession("DB_datausu")),0,4);

  }

	$pdf->setfont('','B',9);
	$sMsg = "DEMONSTRATIVO DA DÍVIDA - em moeda corrente, atualizado até $xdia/$xmes/$xano.";
	$pdf->MultiCell(0,5,$sMsg,0,"C",0);

	$pdf->Ln(3);

	if ($sPosicao == "vertical") {

	  $pdf->SetFont('','B',6);
	  $pdf->Cell(40,5,""          ,1,0,"C",1);
	  $pdf->Cell(25,5,"TOTAL"     ,1,1,"C",1);

	  $pdf->SetFont('','',6);

	  $pdf->Cell(40,5,"Valor Original"                              ,1,0,"L",0);
	  $pdf->Cell(25,5,db_formatar($oTotalGeral['nVlrHistorico'],'f'),1,1,"R",0);

	  if ($lCorrigido) {
      $pdf->Cell(40,5,"Corrigido"                                 ,1,0,"L",0);
	  } else {
	  	$pdf->Cell(40,5,"Correção"                                  ,1,0,"L",0);
	  }

	  $pdf->Cell(25,5,db_formatar($oTotalGeral['nVlrCorrecao'],'f') ,1,1,"R",0);

	  $pdf->Cell(40,5,"Multa"                                       ,1,0,"L",0);
	  $pdf->Cell(25,5,db_formatar($oTotalGeral['nVlrMulta'],'f')    ,1,1,"R",0);

	  $pdf->Cell(40,5,"Juros"                                       ,1,0,"L",0);
	  $pdf->Cell(25,5,db_formatar($oTotalGeral['nVlrJuros'],'f')    ,1,1,"R",0);

	  $pdf->SetFont('','B',6);
	  $pdf->Cell(40,5,"TOTAL GERAL"                                 ,1,0,"L",0);
	  $pdf->Cell(25,5,db_formatar($oTotalGeral['nVlrTotal'],'f')    ,1,1,"R",0);

	  $pdf->Ln(3);

	  $sMsgTotal  = "TOTAL :  ".trim(db_formatar($oTotalGeral['nVlrTotal'],'f'))."  ";
	  $sMsgTotal .= " ( ".ucfirst(trim(db_extenso($oTotalGeral['nVlrTotal'])))." )";
	  $pdf->SetFont('','B',7);
	  $pdf->Cell(0,5,$sMsgTotal,0,1,"L",0);
	  $pdf->Ln(5);

	} else {

		  $pdf->SetFont('','B',6);

      $pdf->Cell(141, 5, ""         , 1, 0, "C", 1);
      $pdf->SetFont('' , 'B'        , 5);
      $pdf->Cell( 10, 5, "VLR HIST" , 1, 0, "C", 1);
      $pdf->Cell( 11, 5, "CORRIGIDO", 1, 0, "C", 1);
      $pdf->Cell( 10, 5, "MULTA"    , 1, 0, "C", 1);
      $pdf->Cell( 10, 5, "JUROS"    , 1, 0, "C", 1);
      $pdf->Cell( 12, 5, "TOTAL"    , 1, 1, "C", 1);

      $pdf->Cell(141,5,"TOTAL GERAL", 1, 0, "R", 0);
      $pdf->SetFont('','B',5);
      $pdf->Cell(10,5,db_formatar($oTotalGeral['nVlrHistorico'], 'f') , 1, 0, "R", 0);
      $pdf->Cell(11,5,db_formatar($oTotalGeral['nVlrCorrecao'], 'f') , 1, 0, "R", 0);
      $pdf->Cell(10,5,db_formatar($oTotalGeral['nVlrMulta'], 'f') , 1, 0, "R", 0);
      $pdf->Cell(10,5,db_formatar($oTotalGeral['nVlrJuros'], 'f') , 1, 0, "R", 0);
      $pdf->Cell(12,5,db_formatar($oTotalGeral['nVlrTotal'], 'f') , 1, 1, "R", 0);
      $pdf->setfont('','B',9);
      $pdf->Ln(5);

	}

}

function drawParcelamentos(pdf3 $pdf, cda $oCertidao, $oPardiv, $oDadosCertidao, $lReemissao,$lComposicao) {

  global $lImpFolha;

  if ( $lReemissao){

    $v13_dtemis = $oDadosCertidao->v13_dtemis;
    $dataemis = mktime(0,0,0,substr($v13_dtemis,5,2),substr($v13_dtemis,8,2),substr($v13_dtemis,0,4));
    $anoemis  = substr($v13_dtemis,0,4);
    $xmes     = substr($v13_dtemis,5,2);
    $xdia     = substr($v13_dtemis,8,2);
    $xano     = substr($v13_dtemis,0,4);

  } else {

    $dataemis = db_getsession("DB_datausu");
    $anoemis  = db_getsession("DB_anousu");
    $xmes = substr(date("Y-m-d",db_getsession("DB_datausu")),8,2);
    $xdia = substr(date("Y-m-d",db_getsession("DB_datausu")),5,2);
    $xano = substr(date("Y-m-d",db_getsession("DB_datausu")),0,4);

  }

  $sSqlNumpres  = "SELECT v07_numpre,v07_parcel  ";
  $sSqlNumpres .= "  From certter ";
  $sSqlNumpres .= "       inner join termo on v07_parcel = v14_parcel ";
  $sSqlNumpres .= " where v14_certid = {$oDadosCertidao->v14_certid}";
  $rsNumpres    = db_query($sSqlNumpres);
  $aNumpres     = db_utils::getCollectionByRecord($rsNumpres);
  foreach ($aNumpres as $oNumpre) {
    $sSqlArrecad  = "select * from arrecad where k00_numpre={$oNumpre->v07_numpre}";
    $rsArrecad = db_query($sSqlArrecad);
    if (pg_num_rows($rsArrecad) > 0) {

      $rsDebitos     = debitos_numpre($oNumpre->v07_numpre,0,0,$dataemis,$anoemis,0);
      $iTotalDebitos = pg_num_rows($rsDebitos);

    }else{

      $result_arreold=db_query("select * from arreold where k00_numpre={$oNumpre->v07_numpre}");
      if(pg_numrows($result_arreold)>0) {

        $rsDebitos = debitos_numpre_old($oNumpre->v07_numpre, 0, 0, $dataemis,$anoemis, 0);
        $iTotalDebitos = pg_numrows($rsDebitos);

      } else {
        $sqlprocuraarreforo    = " select k00_numpre,
																	        k00_numpar,
																	        k00_numcgm,
																	        k00_dtoper,
																	        k00_receit,
																	        k00_hist,
																	        k00_valor,
																	        k00_dtvenc,
																	        k00_numtot,
																	        k00_numdig,
																	        k00_tipo
																	   from arreforo
																	  where k00_certidao = {$oCertidao->getCodigo()}";
        $resultprocuraarreforo = db_query($sqlprocuraarreforo) or die($sqlprocuraarreforo);
        if (pg_num_rows($resultprocuraarreforo) > 0) {

          $sqlInsertArreold = "insert into arreold ( k00_numpre,k00_numpar,k00_numcgm,k00_dtoper,k00_receit,k00_hist,k00_valor, k00_dtvenc,k00_numtot,k00_numdig,k00_tipo ) $sqlprocuraarreforo ";
          db_query($sqlInsertArreold) or die($sqlInsertArreold);
          $rsDebitos = debitos_numpre_old($oDadosCertidao->v07_numpre, 0, 0, $dataemis,$anoemis, 0, '', '');

        }else{

          $iTotalDebitos = 0;
          db_redireciona("db_erros.php?fechar=true&db_erro=Os debitos da origem CDA {$oCertidao->getCodigo()} não foram encontrados, provavelmente pagos ou cancelados!!<br>Contate Suporte!!");
          exit;
        }
      }
    }

    $pagina    = 0;
    $Tvlrhis   = 0;
    $Tvlrcor   = 0;
    $Tvlrmulta = 0;
    $Tvlrjuros = 0;
    $Ttotal    = 0;
    $y         = 0;

    $lImprimeCab = true;



    for($i = 0; $i < $iTotalDebitos; $i++) {

      $oDebito = db_utils::fieldsmemory($rsDebitos, $i);

      if ($y > 272 || $lImprimeCab ) {

      	if ( !$lImprimeCab ) {
          $pdf->AddPage();
      	}

        $pdf->Ln(3);

        $pdf->SetFont('','B',7);
        $pdf->MultiCell(0,5,"P A R C E L A M E N T O:  $oNumpre->v07_parcel",0,"L",0);
        $pdf->SetFont('','B',7);

        $pdf->Cell(15,5,"NUMPRE"   ,1,0,"C",1);
        $pdf->Cell(50,5,"NATUREZA" ,1,0,"C",1);
        $pdf->Cell( 8,5,"PARC"     ,1,0,"C",1);
        $pdf->Cell(15,5,"VENC"     ,1,0,"C",1);
        $pdf->Cell(20,5,"ORIGINAL" ,1,0,"C",1);

        if ( $lComposicao ) {
		      $pdf->Cell(20,5,"CORREÇÃO" ,1,0,"C",1);
		    } else {
		      $pdf->Cell(20,5,"CORRIGIDO",1,0,"C",1);
		    }

		    $pdf->Cell(20,5,"MULTA"    ,1,0,"C",1);
        $pdf->Cell(20,5,"JUROS"    ,1,0,"C",1);
        $pdf->Cell(20,5,"TOTAL"    ,1,1,"C",1);

        $pagina      = $pdf->PageNo();
        $lImprimeCab = false;

      }

      if ( $lComposicao ) {
      	$nVlrCorrecao = $oDebito->vlrcor - $oDebito->vlrhis;
      } else {
      	$nVlrCorrecao = $oDebito->vlrcor;
      }


      $pdf->SetFont('','',7);

      $pdf->Cell(15,5,$oDebito->k00_numpre                           ,1,0,"C",0);
      $pdf->Cell(50,5,substr($oDebito->k02_drecei,0,34)              ,1,0,"L",0);
      $pdf->Cell( 8,5,db_formatar($oDebito->k00_numpar,'s','0',2,'e'),1,0,"C",0);
      $pdf->Cell(15,5,db_formatar($oDebito->k00_dtvenc,'d')          ,1,0,"C",0);
      $pdf->Cell(20,5,db_formatar($oDebito->vlrhis,'f')              ,1,0,"R",0);
      $pdf->Cell(20,5,db_formatar($nVlrCorrecao,'f')                 ,1,0,"R",0);

      if ($oCertidao->getMassafalida() == 0) {

        $pdf->Cell(20,5,db_formatar($oDebito->vlrmulta,'f'),1,0,"R",0);
        $pdf->Cell(20,5,db_formatar($oDebito->vlrjuros,'f'),1,0,"R",0);
        $pdf->Cell(20,5,db_formatar($oDebito->total,'f')   ,1,1,"R",0);
        $Tvlrmulta += $oDebito->vlrmulta;
        $Tvlrjuros += $oDebito->vlrjuros;
        $Ttotal    += $oDebito->total;

      } else {

        $pdf->Cell(20,5,db_formatar(0,'f')               ,1,0,"R",0);
        $pdf->Cell(20,5,db_formatar(0,'f')               ,1,0,"R",0);
        $pdf->Cell(20,5,db_formatar($nVlrCorrecao,'f')   ,1,1,"R",0);
        $Ttotal    += $nVlrCorrecao;
      }
      $y = $pdf->GetY();
      $Tvlrhis   += $oDebito->vlrhis;
      $Tvlrcor   += $nVlrCorrecao;

    }
    $pdf->SetFont('','B',7);
    $pdf->Cell(88,5,"TOTAL",1,0,"C",0);
    $pdf->Cell(20,5,db_formatar($Tvlrhis,'f')  ,1,0,"R",0);
    $pdf->Cell(20,5,db_formatar($Tvlrcor,'f')  ,1,0,"R",0);
    $pdf->Cell(20,5,db_formatar($Tvlrmulta,'f'),1,0,"R",0);
    $pdf->Cell(20,5,db_formatar($Tvlrjuros,'f'),1,0,"R",0);
    $pdf->Cell(20,5,db_formatar($Ttotal,'f')   ,1,1,"R",0);
  }
  $pdf->setfont('','B',10);
  $pdf->Ln(3);

}


function drawDebitosOrigemComposicaoAnteriorPrimeiroNivel(pdf3 $pdf, cda $oCertidao, $oPardiv, $oDadosCertidao, $lReemissao) {

  $iFont = 7;

  $sSqlTermo  = " select v07_parcel                                  ";
  $sSqlTermo .= "   from certter                                     ";
  $sSqlTermo .= "        inner join termo on v07_parcel = v14_parcel ";
  $sSqlTermo .= "  where v14_certid = {$oDadosCertidao->v14_certid}  ";

  $rsTermo    = db_query($sSqlTermo);
  $aTermo     = db_utils::getCollectionByRecord($rsTermo);

  foreach ( $aTermo as $oTermo ) {

    $sSqlTermoReparc = " select distinct termo.*,
                                case
                                  when arrematric.k00_matric is not null then 'M-'||arrematric.k00_matric
                                  when arreinscr.k00_inscr   is not null then 'I-'||arreinscr.k00_inscr
                                  else 'C-'||arrenumcgm.k00_numcgm
                                end as origem
                           from fc_origemparcelamento_agrupa(( select v07_numpre
                                                                 from termo
                                                                where v07_parcel = {$oTermo->v07_parcel} )) as x
                                inner join termo      on termo.v07_parcel      = x.riparcel
                                inner join arrenumcgm on arrenumcgm.k00_numpre = termo.v07_numpre
                                left  join arrematric on arrematric.k00_numpre = termo.v07_numpre
                                left  join arreinscr  on arreinscr.k00_numpre  = termo.v07_numpre
                          where rireparc = {$oTermo->v07_parcel}";

    $rsTermoReparc   = db_query($sSqlTermoReparc);
    $aTermoReparc    = db_utils::getCollectionByRecord($rsTermoReparc);

    $aTotalGeral['nVlrHist']  = 0;
    $aTotalGeral['nVlrCorr']  = 0;
    $aTotalGeral['nVlrJuros'] = 0;
    $aTotalGeral['nVlrMulta'] = 0;
    $aTotalGeral['nVlrTotal'] = 0;

    foreach ( $aTermoReparc as $iInd => $oTermoReparc ) {

    	$sSqlDadosDebito = "  select arreold.k00_numpar,
    	                             arreold.k00_receit,
    	                             arreold.k00_dtoper,
    	                             arreold.k00_dtvenc,
    	                             tabrec.k02_descr,
    	                             arrecadcompos.*,
    	                             (arrecadcompos.k00_vlrhist  +
    	                              arrecadcompos.k00_correcao +
    	                              arrecadcompos.k00_juros    +
    	                              arrecadcompos.k00_multa) as total
    	                        from arreold
    	                             inner join arreckey      on arreold.k00_numpre         = arreckey.k00_numpre
    	                                                     and arreold.k00_numpar         = arreckey.k00_numpar
    	                                                     and arreold.k00_receit         = arreckey.k00_receit
    	                             inner join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
    	                             inner join tabrec        on tabrec.k02_codigo          = arreold.k00_receit
    	                       where arreold.k00_numpre = {$oTermoReparc->v07_numpre}
    	                       order by arreold.k00_numpar,
    	                                arreold.k00_receit";


      $rsDadosDebitos = db_query($sSqlDadosDebito);
      $iLinhasDebitos = pg_num_rows($rsDadosDebitos);


	    if ( $iLinhasDebitos > 0 ) {

		    if ( $pdf->GetY() > 270 ) {
          $pdf->AddPage();
	      }

        $pdf->SetFont('','B',$iFont+3);
        $pdf->Cell(0,5,'PARCELAMENTO : '.$oTermoReparc->v07_parcel.' NUMPRE: '.$oTermoReparc->v07_numpre,0,1,'L',0);
        $pdf->SetFont('','B',$iFont);

        $pdf->Ln(3);

        $pdf->Cell(15,5,'Parcela'      ,1,0,'C',1);
        $pdf->Cell(50,5,'Receita'      ,1,0,'C',1);
        $pdf->Cell(20,5,'Dt Lançamento',1,0,'C',1);
        $pdf->Cell(20,5,'Dt Vencimento',1,0,'C',1);
        $pdf->Cell(19,5,'Vlr Original' ,1,0,'C',1);
        $pdf->Cell(17,5,'Correção'     ,1,0,'C',1);
        $pdf->Cell(17,5,'Juros'        ,1,0,'C',1);
        $pdf->Cell(17,5,'Multa'        ,1,0,'C',1);
        $pdf->Cell(19,5,'Total'        ,1,1,'C',1);

        $pdf->SetFont('','',$iFont);

        $aTotalNumpre['nVlrHist']   = 0;
        $aTotalNumpre['nVlrCorr']   = 0;
        $aTotalNumpre['nVlrJuros']  = 0;
        $aTotalNumpre['nVlrMulta']  = 0;
        $aTotalNumpre['nVlrTotal']  = 0;

        $aTotalParcela['nVlrHist']  = 0;
        $aTotalParcela['nVlrCorr']  = 0;
        $aTotalParcela['nVlrJuros'] = 0;
        $aTotalParcela['nVlrMulta'] = 0;
        $aTotalParcela['nVlrTotal'] = 0;

        $lImprimeTotalParcela = false;
        $iNumparAnt           = 0;

	      for ( $iIndReparc=0; $iIndReparc < $iLinhasDebitos; $iIndReparc++ ) {

	      	$oDadosDebito = db_utils::fieldsMemory($rsDadosDebitos,$iIndReparc);

	        if ( $pdf->GetY() > 270 ) {

	        	$pdf->AddPage();

	          $pdf->Ln(3);

	          $pdf->Cell(15,5,'Parcela'      ,1,0,'C',1);
	          $pdf->Cell(50,5,'Receita'      ,1,0,'C',1);
	          $pdf->Cell(20,5,'Dt Lançamento',1,0,'C',1);
	          $pdf->Cell(20,5,'Dt Vencimento',1,0,'C',1);
	          $pdf->Cell(19,5,'Vlr Original' ,1,0,'C',1);
	          $pdf->Cell(17,5,'Correção'     ,1,0,'C',1);
	          $pdf->Cell(17,5,'Juros'        ,1,0,'C',1);
	          $pdf->Cell(17,5,'Multa'        ,1,0,'C',1);
	          $pdf->Cell(19,5,'Total'        ,1,1,'C',1);

	          $pdf->SetFont('','',$iFont);

	        }


          if ( $iNumparAnt != $oDadosDebito->k00_numpar && $lImprimeTotalParcela ) {

		        $pdf->SetFont('','B',$iFont);
		        $pdf->Cell(105,5,'TOTAL PARCELA'                              ,1,0,'L',0);
		        $pdf->Cell(19 ,5,db_formatar($aTotalParcela['nVlrHist'],'f')  ,1,0,'R',0);
		        $pdf->Cell(17 ,5,db_formatar($aTotalParcela['nVlrCorr'],'f')  ,1,0,'R',0);
		        $pdf->Cell(17 ,5,db_formatar($aTotalParcela['nVlrJuros'],'f') ,1,0,'R',0);
		        $pdf->Cell(17 ,5,db_formatar($aTotalParcela['nVlrMulta'],'f') ,1,0,'R',0);
		        $pdf->Cell(19 ,5,db_formatar($aTotalParcela['nVlrTotal'],'f') ,1,1,'R',0);
		        $pdf->SetFont('','',$iFont);

          	$lImprimeTotalParcela = false;

          }

          if ( $iNumparAnt == $oDadosDebito->k00_numpar) {

            $aTotalParcela['nVlrHist']  += $oDadosDebito->k00_vlrhist;
            $aTotalParcela['nVlrCorr']  += $oDadosDebito->k00_correcao;
            $aTotalParcela['nVlrJuros'] += $oDadosDebito->k00_juros;
            $aTotalParcela['nVlrMulta'] += $oDadosDebito->k00_multa;
            $aTotalParcela['nVlrTotal'] += $oDadosDebito->total;

            $lImprimeTotalParcela = true;

          } else {

            $aTotalParcela['nVlrHist']  = $oDadosDebito->k00_vlrhist;
            $aTotalParcela['nVlrCorr']  = $oDadosDebito->k00_correcao;
            $aTotalParcela['nVlrJuros'] = $oDadosDebito->k00_juros;
            $aTotalParcela['nVlrMulta'] = $oDadosDebito->k00_multa;
            $aTotalParcela['nVlrTotal'] = $oDadosDebito->total;

          }


          $iNumparAnt = $oDadosDebito->k00_numpar;


	        $pdf->Cell(15,5,$oDadosDebito->k00_numpar                             ,1,0,'C',0);
	        $pdf->Cell(50,5,$oDadosDebito->k00_receit."-".$oDadosDebito->k02_descr,1,0,'L',0);
	        $pdf->Cell(20,5,db_formatar($oDadosDebito->k00_dtoper,'d')            ,1,0,'C',0);
	        $pdf->Cell(20,5,db_formatar($oDadosDebito->k00_dtvenc,'d')            ,1,0,'C',0);
	        $pdf->Cell(19,5,db_formatar($oDadosDebito->k00_vlrhist,'f')           ,1,0,'R',0);
	        $pdf->Cell(17,5,db_formatar($oDadosDebito->k00_correcao,'f')          ,1,0,'R',0);
	        $pdf->Cell(17,5,db_formatar($oDadosDebito->k00_juros,'f')             ,1,0,'R',0);
	        $pdf->Cell(17,5,db_formatar($oDadosDebito->k00_multa,'f')             ,1,0,'R',0);
	        $pdf->Cell(19,5,db_formatar($oDadosDebito->total,'f')                 ,1,1,'R',0);

	        $aTotalNumpre['nVlrHist']  += $oDadosDebito->k00_vlrhist;
	        $aTotalNumpre['nVlrCorr']  += $oDadosDebito->k00_correcao;
		      $aTotalNumpre['nVlrJuros'] += $oDadosDebito->k00_juros;
		      $aTotalNumpre['nVlrMulta'] += $oDadosDebito->k00_multa;
		      $aTotalNumpre['nVlrTotal'] += $oDadosDebito->total;

          $aTotalGeral['nVlrHist']   += $oDadosDebito->k00_vlrhist;
          $aTotalGeral['nVlrCorr']   += $oDadosDebito->k00_correcao;
          $aTotalGeral['nVlrJuros']  += $oDadosDebito->k00_juros;
          $aTotalGeral['nVlrMulta']  += $oDadosDebito->k00_multa;
          $aTotalGeral['nVlrTotal']  += $oDadosDebito->total;

		    }


	      if ( $lImprimeTotalParcela ) {

          $pdf->SetFont('','B',$iFont);
          $pdf->Cell(105,5,'TOTAL PARCELA'                              ,1,0,'L',0);
          $pdf->Cell(19 ,5,db_formatar($aTotalParcela['nVlrHist'],'f')  ,1,0,'R',0);
          $pdf->Cell(17 ,5,db_formatar($aTotalParcela['nVlrCorr'],'f')  ,1,0,'R',0);
          $pdf->Cell(17 ,5,db_formatar($aTotalParcela['nVlrJuros'],'f') ,1,0,'R',0);
          $pdf->Cell(17 ,5,db_formatar($aTotalParcela['nVlrMulta'],'f') ,1,0,'R',0);
          $pdf->Cell(19 ,5,db_formatar($aTotalParcela['nVlrTotal'],'f') ,1,1,'R',0);
          $pdf->SetFont('','',$iFont);

        }


		    $pdf->SetFont('','B',$iFont);
	      $pdf->Cell(105,5,'TOTAL PARCELAMENTO'                        ,'T',0,'L',0);
	      $pdf->Cell(19 ,5,db_formatar($aTotalNumpre['nVlrHist'],'f')  ,'T',0,'R',0);
	      $pdf->Cell(17 ,5,db_formatar($aTotalNumpre['nVlrCorr'],'f')  ,'T',0,'R',0);
	      $pdf->Cell(17 ,5,db_formatar($aTotalNumpre['nVlrJuros'],'f') ,'T',0,'R',0);
	      $pdf->Cell(17 ,5,db_formatar($aTotalNumpre['nVlrMulta'],'f') ,'T',0,'R',0);
	      $pdf->Cell(19 ,5,db_formatar($aTotalNumpre['nVlrTotal'],'f') ,'T',1,'R',0);
	      $pdf->SetFont('','',$iFont);

        $pdf->Ln(3);

		  }
	  }

	  if ( count($aTermoReparc) > 0 ) {

      $pdf->SetFont('','B',$iFont);
      $pdf->Cell(105,5,'TOTAL GERAL'                              ,'T',0,'L',0);
      $pdf->Cell(19 ,5,db_formatar($aTotalGeral['nVlrHist'],'f')  ,'T',0,'R',0);
      $pdf->Cell(17 ,5,db_formatar($aTotalGeral['nVlrCorr'],'f')  ,'T',0,'R',0);
      $pdf->Cell(17 ,5,db_formatar($aTotalGeral['nVlrJuros'],'f') ,'T',0,'R',0);
      $pdf->Cell(17 ,5,db_formatar($aTotalGeral['nVlrMulta'],'f') ,'T',0,'R',0);
      $pdf->Cell(19 ,5,db_formatar($aTotalGeral['nVlrTotal'],'f') ,'T',1,'R',0);
      $pdf->SetFont('','',$iFont);
      $pdf->Ln(3);

	  }
	}
}

function drawTextoPadrao($pdf, $oCertidao, $sTexto) {

  global $lImpFolha;
  $pdf->setfont('','B',9);
  $pdf->Ln(5);
  $pdf->MultiCell(0,5,$sTexto,0,"L",0);
}

function drawData ($pdf, $oCertidao, $sData, $lCorrigir) {

  global $lImpFolha;

  $sMunic = db_stdClass::getDadosInstit()->munic;
  if ($lCorrigir) {

    $dataemis = mktime(0,0,0,substr($sData,5,2),
                             substr($sData,8,2),
                             substr($sData,0,4)
                             );
    $anoemis  = substr($sData,0,4);
    $xmes     = substr($sData,5,2);
    $xdia     = substr($sData,8,2);
    $xano     = substr($sData,0,4);

  } else {

    $dataemis = db_getsession("DB_datausu");
    $anoemis  = db_getsession("DB_anousu");
    $xdia = substr(date("Y-m-d",db_getsession("DB_datausu")),8,2);
    $xmes = substr(date("Y-m-d",db_getsession("DB_datausu")),5,2);
    $xano = substr(date("Y-m-d",db_getsession("DB_datausu")),0,4);

  }

  $pdf->MultiCell(0,4,$sMunic.', '.$xdia." de ".db_mes( $xmes )." de ".$xano.'.',0,"R",0);
}

function drawAssinaturas($pdf, $oCertidao, $aAssinaturas) {

  $asssec   = null;
  $asscoord = null;
  global $lImpFolha;

  if ($pdf->gety() > $pdf->h - 66 ){

    $pdf->addPage();
  }

  foreach ($aAssinaturas as $oAssinaturas) {

    if ($oAssinaturas->db02_descr == "ASSINATURAS_CODIGOPHP") {
      $assinaturas_php = trim($oAssinaturas->db02_texto);
    }
    if ($oAssinaturas->db04_ordem == '4'){
      $asssec = $oAssinaturas->db02_texto;
    }
    if ($oAssinaturas->db04_ordem == '5'){
      $asscoord = $oAssinaturas->db02_texto;
    }
  }

  $pdf->setfont('','',1);
  $pdf->MultiCell(0,2,"",0,"R",0);
  $pdf->setfont('','',10);

  if (!empty($asssec)) {
    $sec =  "______________________________"."\n".$asssec;
  } else {
    $sec =  "";
  }
  if (!empty($asscoord)) {
    $coor =  "______________________________"."\n".$asscoord;
  } else {
    $coor =  "";
  }

  $pdf->SetFont('','B',10);

  $largura  = ( $pdf->w ) / 2;
  $posy     = $pdf->gety();
  $alt      = 5;
  $dbinstit = db_getsession('DB_instit');

  if (isset($assinaturas_php) && $assinaturas_php != ""){

    eval($assinaturas_php);
  } else {

    if ($coor != "") {
      $pdf->multicell($largura-20,4,$coor,0,"C",0,0);
    } else {
      $pdf->Cell(1,3,"",0,0,"C",0);
    }

    if ($sec != "") {

      $pdf->Cell($largura-10,3,"",0,0,"C",0);
      $pdf->multicell($largura,4,$sec,0,"C",0,0);
    } else {
      $pdf->Cell(100,3,"",0,0,"C",0);
    }
  }
}

function drawParcelamentosPago(pdf3 $pdf, cda $oCertidao, $oPardiv, $oDadosCertidao, $lReemissao,$lComposicao) {

  global $lImpFolha;

  if ( $lReemissao){

    $v13_dtemis = $oDadosCertidao->v13_dtemis;
    $dataemis = mktime(0,0,0,substr($v13_dtemis,5,2),substr($v13_dtemis,8,2),substr($v13_dtemis,0,4));
    $anoemis  = substr($v13_dtemis,0,4);
    $xmes     = substr($v13_dtemis,5,2);
    $xdia     = substr($v13_dtemis,8,2);
    $xano     = substr($v13_dtemis,0,4);

  } else {

    $dataemis = db_getsession("DB_datausu");
    $anoemis  = db_getsession("DB_anousu");
    $xdia = substr(date("Y-m-d",db_getsession("DB_datausu")),8,2);
    $xmes = substr(date("Y-m-d",db_getsession("DB_datausu")),5,2);
    $xano = substr(date("Y-m-d",db_getsession("DB_datausu")),0,4);
  }

  $sSqlNumpres  = "SELECT v07_numpre,v07_parcel  ";
  $sSqlNumpres .= "  From certter ";
  $sSqlNumpres .= "       inner join termo on v07_parcel = v14_parcel ";
  $sSqlNumpres .= " where v14_certid = {$oDadosCertidao->v14_certid}";
  $rsNumpres    = db_query($sSqlNumpres);
  $aNumpres     = db_utils::getCollectionByRecord($rsNumpres);

  foreach ($aNumpres as $oNumpre) {

    $sSqlArrecad     = "select * from arrecad where k00_numpre={$oNumpre->v07_numpre}";
    $rsArrecad       = db_query($sSqlArrecad);

    if (pg_num_rows($rsArrecad) > 0) {

      $rsDebitos     = debitos_numpre($oNumpre->v07_numpre,0,0,$dataemis,$anoemis,0);
      $iTotalDebitos = pg_num_rows($rsDebitos);

    } else {

      $result_arreold=db_query("select * from arreold where k00_numpre={$oNumpre->v07_numpre}");

      if ( pg_numrows($result_arreold) > 0 ) {

        $rsDebitos = debitos_numpre_old($oDadosCertidao->v07_numpre,0,0,$dataemis,$anoemis,0);
        $iTotalDebitos = pg_numrows($rsDebitos);

      } else {

     	  $sqlprocuraarreforo    = " select k00_numpre,
                                          k00_numpar,
                                          k00_numcgm,
                                          k00_dtoper,
                                          k00_receit,
                                          k00_hist,
                                          k00_valor,
                                          k00_dtvenc,
                                          k00_numtot,
                                          k00_numdig,
                                          k00_tipo
                                     from arreforo
                                    where k00_certidao = {$oCertidao->getCodigo()}";
        $resultprocuraarreforo = db_query($sqlprocuraarreforo) or die($sqlprocuraarreforo);

	      if (pg_num_rows($resultprocuraarreforo) > 0) {

          $sqlInsertArreold = "insert into arreold ( k00_numpre,k00_numpar,k00_numcgm,k00_dtoper,k00_receit,k00_hist,k00_valor, k00_dtvenc,k00_numtot,k00_numdig,k00_tipo ) $sqlprocuraarreforo ";
          db_query($sqlInsertArreold) or die($sqlInsertArreold);
          $rsDebitos = debitos_numpre_old($oDadosCertidao->v07_numpre,0,0,$dataemis,$anoemis,0,'','');

	      } else {

	        $iTotalDebitos = 0;
	        db_redireciona("db_erros.php?fechar=true&db_erro=Os debitos da origem CDA {$oCertidao->getCodigo()} não foram encontrados, provavelmente pagos ou cancelados!!<br>Contate Suporte!!");
	        exit;
        }
      }
    }

    $aDebitosNumpre = db_utils::getCollectionByRecord($rsDebitos);
    $aListaDebitos  = array();

    foreach ( $aDebitosNumpre as $iInd => $oDebitosNumpre ) {
      if ( !isset($aListaDebitos[$oDebitosNumpre->k00_numpre][$oDebitosNumpre->k00_numpar][$oDebitosNumpre->k00_receit]) ) {
      	$aListaDebitos[$oDebitosNumpre->k00_numpre][$oDebitosNumpre->k00_numpar][$oDebitosNumpre->k00_receit] = $oDebitosNumpre;
      }
    }

    $sSqlArrepaga  = " select x.*,                                                                        ";
    $sSqlArrepaga .= "( (x.vlrcor + x.vlrjuros + x.vlrmulta) - abs(x.vlrdesconto) ) as vlrpago            ";
    $sSqlArrepaga .= "from ( select arrepaga.k00_numcgm,                                                  ";
    $sSqlArrepaga .= "              arrepaga.k00_receit,                                                  ";
    $sSqlArrepaga .= "              tabrec.k02_drecei,                                                    ";
    $sSqlArrepaga .= "              arrepaga.k00_hist,                                                    ";
    $sSqlArrepaga .= "              histcalc.k01_descr,                                                   ";
    $sSqlArrepaga .= "              arrecant.k00_tipo,                                                    ";
    $sSqlArrepaga .= "              coalesce( arrecant.k00_tipojm,0) as k00_tipojm,                       ";
    $sSqlArrepaga .= "              arrepaga.k00_numpre,                                                  ";
    $sSqlArrepaga .= "              arrepaga.k00_numpar,                                                  ";
    $sSqlArrepaga .= "              arrepaga.k00_numtot,                                                  ";
    $sSqlArrepaga .= "              arrepaga.k00_numdig,                                                  ";
    $sSqlArrepaga .= "              arrecant.k00_valor as vlrhis,                                         ";
    $sSqlArrepaga .= "              arrepaga.k00_valor as vlrcor,                                         ";
    $sSqlArrepaga .= "              ( select coalesce(sum(k00_valor),0)                                   ";
    $sSqlArrepaga .= "                  from arrepaga arrpg                                               ";
    $sSqlArrepaga .= "                 where arrpg.k00_numpre = arrepaga.k00_numpre                       ";
    $sSqlArrepaga .= "                   and arrpg.k00_numpar = arrepaga.k00_numpar                       ";
    $sSqlArrepaga .= "                   and arrpg.k00_hist   = 400 ) as vlrjuros,                        ";
    $sSqlArrepaga .= "              ( select coalesce(sum(k00_valor),0)                                   ";
    $sSqlArrepaga .= "                  from arrepaga arrpg                                               ";
    $sSqlArrepaga .= "                 where arrpg.k00_numpre = arrepaga.k00_numpre                       ";
    $sSqlArrepaga .= "                   and arrpg.k00_numpar = arrepaga.k00_numpar                       ";
    $sSqlArrepaga .= "                   and arrpg.k00_hist   = 401 ) as vlrmulta,                        ";
    $sSqlArrepaga .= "              ( select coalesce(sum(k00_valor),0)                                   ";
    $sSqlArrepaga .= "                  from arrepaga arrpg                                               ";
    $sSqlArrepaga .= "                 where arrpg.k00_numpre = arrepaga.k00_numpre                       ";
    $sSqlArrepaga .= "                   and arrpg.k00_numpar = arrepaga.k00_numpar                       ";
    $sSqlArrepaga .= "                   and arrpg.k00_hist   = 918 ) as vlrdesconto,                     ";
    $sSqlArrepaga .= "              arrepaga.k00_dtvenc,                                                  ";
    $sSqlArrepaga .= "              arrepaga.k00_dtoper                                                   ";
    $sSqlArrepaga .= "         from arrepaga                                                              ";
    $sSqlArrepaga .= "              inner join arrecant  on arrecant.k00_numpre = arrepaga.k00_numpre     ";
    $sSqlArrepaga .= "                     and arrecant.k00_numpar = arrepaga.k00_numpar                  ";
    $sSqlArrepaga .= "                     and arrecant.k00_receit = arrepaga.k00_receit                  ";
    $sSqlArrepaga .= "              inner join tabrec    on tabrec.k02_codigo   = arrepaga.k00_receit     ";
    $sSqlArrepaga .= "              inner join histcalc  on histcalc.k01_codigo = arrepaga.k00_hist       ";
    $sSqlArrepaga .= "        where arrepaga.k00_numpre = {$oNumpre->v07_numpre}                          ";
    $sSqlArrepaga .= "          and arrepaga.k00_hist not in (918)                                        ";
    $sSqlArrepaga .= "              ) as x                                                                ";
    $sSqlArrepaga .= "        order by x.k00_numpre,                                                      ";
    $sSqlArrepaga .= "                 x.k00_numpar                                                       ";

    $rsDebitos      = db_query($sSqlArrepaga);
    $aDebitosNumpre = db_utils::getCollectionByRecord($rsDebitos);

    foreach ( $aDebitosNumpre as $iInd => $oDebitosNumpre ) {
      if ( !isset($aListaDebitos[$oDebitosNumpre->k00_numpre][$oDebitosNumpre->k00_numpar][$oDebitosNumpre->k00_receit]) ) {
        $aListaDebitos[$oDebitosNumpre->k00_numpre][$oDebitosNumpre->k00_numpar][$oDebitosNumpre->k00_receit] = $oDebitosNumpre;
      }
    }

    $pagina       = 0;
    $Tvlrhis      = 0;
    $Tvlrcor      = 0;
    $Tvlrmulta    = 0;
    $Tvlrjuros    = 0;
    $TtotalPago   = 0;
    $TtotalApagar = 0;
    $y            = 0;

    $lImprimeCab  = true;

    foreach ($aListaDebitos as $iNumpre => $aNumpar ) {
      ksort($aListaDebitos[$iNumpre]);
    }

    foreach ( $aListaDebitos as $iNumpre => $aNumpre ) {
    	foreach ( $aNumpre     as $iNumpar => $aNumpar ) {
    		foreach ( $aNumpar   as $iReceit => $oDebito ) {

          if ($y > 272 || $lImprimeCab ) {

            if ( !$lImprimeCab ) {
              $pdf->AddPage();
            }

            $pdf->Ln(3);

            $pdf->SetFont('','B',7);
            $pdf->MultiCell(0,5,"P A R C E L A M E N T O:  $oNumpre->v07_parcel",0,"L",0);
            $pdf->SetFont('','B',7);

            $pdf->Cell(15,5,"NUMPRE"   ,1,0,"C",1);
            $pdf->Cell(30,5,"NATUREZA" ,1,0,"C",1);//alterado de 50 para 30 a largura
            $pdf->Cell( 8,5,"PARC"     ,1,0,"C",1);
            $pdf->Cell(15,5,"VENC"     ,1,0,"C",1);
            $pdf->Cell(20,5,"ORIGINAL" ,1,0,"C",1);

            if ( $lComposicao ) {
              $pdf->Cell(20,5,"CORREÇÃO" ,1,0,"C",1);
            } else {
              $pdf->Cell(20,5,"CORRIGIDO",1,0,"C",1);
            }

            $pdf->Cell(20,5,"MULTA"         ,1,0,"C",1);
            $pdf->Cell(20,5,"JUROS"         ,1,0,"C",1);
            $pdf->Cell(20,5,"Valor Pago"    ,1,0,"C",1);
            $pdf->Cell(20,5,"Valor à Pagar" ,1,1,"C",1);

            $pagina      = $pdf->PageNo();
            $lImprimeCab = false;
          }

          if ( $lComposicao ) {
            $nVlrCorrecao = $oDebito->vlrcor - $oDebito->vlrhis;
          } else {
            $nVlrCorrecao = $oDebito->vlrcor;
          }

          $pdf->SetFont('','',7);

          $pdf->Cell(15,5,$oDebito->k00_numpre                           ,1,0,"C",0);
          $pdf->Cell(30,5,substr($oDebito->k02_drecei,0,18)              ,1,0,"L",0);//de 50 para 30 a largura da linha
          $pdf->Cell( 8,5,db_formatar($oDebito->k00_numpar,'s','0',2,'e'),1,0,"C",0);
          $pdf->Cell(15,5,db_formatar($oDebito->k00_dtvenc,'d')          ,1,0,"C",0);
          $pdf->Cell(20,5,db_formatar($oDebito->vlrhis,'f')              ,1,0,"R",0);
          $pdf->Cell(20,5,db_formatar($nVlrCorrecao,'f')                 ,1,0,"R",0);

          if ($oCertidao->getMassafalida() == 0) {

            $pdf->Cell(20,5,db_formatar($oDebito->vlrmulta,'f'),1,0,"R",0);
            $pdf->Cell(20,5,db_formatar($oDebito->vlrjuros,'f'),1,0,"R",0);
            if (isset($oDebito->vlrpago)) {
              $pdf->Cell(20,5,db_formatar($oDebito->vlrpago,'f')   ,1,0,"R",0);
              $pdf->Cell(20,5,db_formatar(0,'f')   ,1,1,"R",0);
            } else {
            	$pdf->Cell(20,5,db_formatar(0,'f')   ,1,0,"R",0);
              $pdf->Cell(20,5,db_formatar($oDebito->total,'f')   ,1,1,"R",0);
            }
            $Tvlrmulta    += $oDebito->vlrmulta;
            $Tvlrjuros    += $oDebito->vlrjuros;

            if (isset($oDebito->vlrpago)) {
              $TtotalPago  += $oDebito->vlrpago;
            } else {
              $TtotalApagar += $oDebito->total;
            }

          } else {

            $pdf->Cell(20,5,db_formatar(0,'f')               ,1,0,"R",0);
            $pdf->Cell(20,5,db_formatar(0,'f')               ,1,0,"R",0);
            $pdf->Cell(20,5,db_formatar($nVlrCorrecao,'f')   ,1,1,"R",0);
            $Ttotal    += $nVlrCorrecao;
          }
          $y = $pdf->GetY();
          $Tvlrhis   += $oDebito->vlrhis;
          $Tvlrcor   += $nVlrCorrecao;

        }
      }
    }
  }

  $pdf->SetFont('','B',7);
  $pdf->Cell(68,5,"TOTAL",1,0,"C",0);
  $pdf->Cell(20,5,db_formatar($Tvlrhis,'f')      ,1,0,"R",0);
  $pdf->Cell(20,5,db_formatar($Tvlrcor,'f')      ,1,0,"R",0);
  $pdf->Cell(20,5,db_formatar($Tvlrmulta,'f')    ,1,0,"R",0);
  $pdf->Cell(20,5,db_formatar($Tvlrjuros,'f')    ,1,0,"R",0);
  $pdf->Cell(20,5,db_formatar($TtotalPago,'f')   ,1,0,"R",0);
  $pdf->Cell(20,5,db_formatar($TtotalApagar,'f') ,1,1,"R",0);
  $pdf->setfont('','B',10);
  $pdf->Ln(3);
}

function drawDadosParcelamento($pdf, $oCertidao, $sTexto) {

  global $lImpFolha, $oDocumentoAgrupador;

  $sSqlNumpres  = "select v07_parcel, v07_totpar, v07_dtlanc  ";
  $sSqlNumpres .= "  from certter ";
  $sSqlNumpres .= "       inner join termo on v07_parcel = v14_parcel ";
  $sSqlNumpres .= " where v14_certid = {$oCertidao->getCodigo()}";
  $rsNumpres    = db_query($sSqlNumpres);
  $aNumpres     = db_utils::getCollectionByRecord($rsNumpres);

  $iNumRows     = count($aNumpres);

  $qtd_parcelas     = "";
  $dt_parcelamento  = "";
  $nro_parcelamento = "";
  $virgula          = "";

  foreach ($aNumpres as $oParcelamento) {

  	if ($iNumRows == 1) {

  		$qtd_parcelas     = $oParcelamento->v07_totpar;
  		$dt_parcelamento  = db_formatar($oParcelamento->v07_dtlanc,'d');
  		$nro_parcelamento = $oParcelamento->v07_parcel;
  	} else {

      $nro_parcelamento .= $virgula.$oParcelamento->v07_parcel;
  		$qtd_parcelas     .= $virgula."Parc: ".$oParcelamento->v07_parcel." - P".$oParcelamento->v07_totpar;
      $dt_parcelamento  .= $virgula."Parc: ".$oParcelamento->v07_parcel." - Data: ".db_formatar($oParcelamento->v07_dtlanc,'d');
      $virgula           = ", ";
  	}

  }

  $dt_cda = db_formatar($oCertidao->getDataEmissao(),'d');

  $sString = $nro_parcelamento." ".$qtd_parcelas." ".$dt_parcelamento." ".$dt_cda;
  $pdf->setfont('','B',10);
  $oDocumentoAgrupador->nro_parcelamento = $nro_parcelamento;
  $oDocumentoAgrupador->qtd_parcelas     = $qtd_parcelas;
  $oDocumentoAgrupador->dt_parcelamento  = $dt_parcelamento;
  $oDocumentoAgrupador->dt_cda           = $dt_cda;

  $pdf->MultiCell(0,5, $oDocumentoAgrupador->replaceText(@$sTexto->db02_texto),0,"L",0);
  $pdf->setfont('arial','',11);
}

function drawDebitosInflator($pdf, cda $oCertidao, $oPardiv, $sInflator, $lTotaliza, $lReemissao) {

  global $tipo, $lImpFolha;
  $aDebitos         = $oCertidao->getDebitos($lReemissao);

  $dDataEmissao = date("Y-m-d",db_getsession("DB_datausu"));
  if ($lReemissao) {
  	$sSqlDataEmissao = "select v13_dtemis
  	                      from certid
  	                     where v13_certid = {$oCertidao->getCodigo()}";
    $rsDataEmissao = db_query($sSqlDataEmissao);
    $dDataEmissao  = db_utils::fieldsMemory($rsDataEmissao,0)->v13_dtemis;
  }

  if ($tipo == 1) {

    if ( count($aDebitos) > 0 ) {

      $lEscreveHeader = true;
      $iY = 0;
      foreach ($aDebitos as $oProcedencias) {

        if ($lEscreveHeader) {

          $pdf->Ln(3);
          $pdf->SetFont('','B',7);
		      $pdf->Cell(20,5,"3 EXERC.",1,0,"C",1);
		      $pdf->Cell(25,5,"PARCEL.",1,0,"C",1);
		      $pdf->Cell(25,5,"VLR HIST ({$sInflator}).",1,0,"C",1);
		      $pdf->Cell(25,5,"CORRIGIDO ({$sInflator})",1,0,"C",1);
		      $pdf->Cell(20,5,"MULTA ({$sInflator})",1,0,"C",1);
		      $pdf->Cell(20,5,"JUROS ({$sInflator})",1,0,"C",1);
		      $pdf->Cell(25,5,"TOTAL ({$sInflator})",1,1,"C",1);
          $lEscreveHeader = false;
        }

        $sSqlTipoProcedencia  = "select case when v03_tributaria = 1 then 'TRIB'";
        $sSqlTipoProcedencia .= "            when v03_tributaria = 2 then 'N.TRIB' ";
        $sSqlTipoProcedencia .= "            when v03_tributaria = 3 then 'TCE' end as tipoproced ";
        $sSqlTipoProcedencia .= "  from proced ";
        $sSqlTipoProcedencia .= " where v03_codigo = {$oProcedencias->codigoprocedencia} limit 1";
        $rsProcedencia        = db_query($sSqlTipoProcedencia);
        $sProcedencia         = db_utils::fieldsMemory($rsProcedencia, 0)->tipoproced;
        $pdf->SetFont('','',7);
        $pdf->Cell(20,5,$oProcedencias->exercicio,1,0,"C",0);
        $pdf->Cell(25,5,$oProcedencias->codigodivida,1,0,"C",0);
        $pdf->Cell(25,5,db_formatar($oProcedencias->valorhistorico, "f"), 1,0,"C",0);
        $pdf->Cell(25,5,db_formatar($oProcedencias->valorcorrigido, "f"),1,0,"C",0);
        $pdf->Cell(20,5,db_formatar($oProcedencias->valormulta, "f"),1,0,"C",0);
        $pdf->Cell(20,5,db_formatar($oProcedencias->valorjuros, "f"),1,0,"C",0);
        $pdf->Cell(25,5,db_formatar($oProcedencias->valortotal, "f"),1,1,"C",0);

        $iY++;
        if ($oPardiv->v04_imphistcda == "t" && isset($oProcedencias->v01_obs)) {

          $pdf->SetFont('','I',5);
          $pdf->setX(10);
          $pdf->Cell(188,4,"Observação: $oProcedencias->observacao",1,1,"L",0);
          $pdf->SetFont('','',7);

        }
      }
    }

  } else if ($tipo == 2) {

    $aDebitosOrdenado = array();
    $aTotaisAno       = array();
    $oTotalGeral      = array();

    foreach ($aDebitos as $oDebito) {

      $aDebitosOrdenado[$oDebito->procedenciatributaria][] = $oDebito;
      if (!isset($aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio])) {

        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrhis = $oDebito->valorhistorico;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrcor = $oDebito->valorcorrigido;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrmul = $oDebito->valormulta;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrjur = $oDebito->valorjuros;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot = $oDebito->valortotal;

        if ($oDebito->certidmassa != 0) {
          $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot = $oDebito->valorcorrigido;
        }

      } else {

        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrhis += $oDebito->valorhistorico;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrcor += $oDebito->valorcorrigido;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrmul += $oDebito->valormulta;
        $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrjur += $oDebito->valorjuros;

        if ($oDebito->certidmassa != 0) {
          $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot += $oDebito->valorcorrigido;
        } else {
          $aTotaisAno[$oDebito->procedenciatributaria][$oDebito->exercicio]->vlrtot += $oDebito->valortotal;
        }
      }
      if (!isset($oTotalGeral[$oDebito->procedenciatributaria])) {

        $oTotalGeral[$oDebito->procedenciatributaria]->valorhistorico = $oDebito->valorhistorico;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorcorrigido = $oDebito->valorcorrigido;
        $oTotalGeral[$oDebito->procedenciatributaria]->valormulta     = $oDebito->valormulta;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorjuros     = $oDebito->valorjuros;
        $oTotalGeral[$oDebito->procedenciatributaria]->valortotal     = $oDebito->valortotal;

        if ($oDebito->certidmassa != 0) {
          $oTotalGeral[$oDebito->procedenciatributaria]->valortotal     = $oDebito->valorcorrigido;
        }
      } else {

        $oTotalGeral[$oDebito->procedenciatributaria]->valorhistorico += $oDebito->valorhistorico;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorcorrigido += $oDebito->valorcorrigido;
        $oTotalGeral[$oDebito->procedenciatributaria]->valormulta     += $oDebito->valormulta;
        $oTotalGeral[$oDebito->procedenciatributaria]->valorjuros     += $oDebito->valorjuros;
        if ($oDebito->certidmassa != 0) {
          $oTotalGeral[$oDebito->procedenciatributaria]->valortotal += $oDebito->valorcorrigido;
        } else {
          $oTotalGeral[$oDebito->procedenciatributaria]->valortotal += $oDebito->valortotal;

        }
      }
    }

    /**
     * Escrevemos o quadro dos creditos ;
     */
    foreach ($aDebitosOrdenado as $iTipo => $aTipo) {

      $pdf->ln(8);
      if ($iTipo == 1) {

        $pdf->MultiCell(0,5,'C R É D I T O    T R I B U T Á R I O ',0,"C",0);
      } else {

        $pdf->setfont('','B',9);
        $pdf->MultiCell(0,5,'C R É D I T O  N Ã O  T R I B U T Á R I O ',0,"C",0);
      }

      $pdf->SetFont('','B',6);
      $pdf->Cell(20,5,"EXERC.",1,0,"C",1);
      $pdf->Cell(20,5,"CÓD. DIVIDA",1,0,"C",1);
      $pdf->Cell(20,5,"VLR HIST ({$sInflator})",1,0,"C",1);
      $pdf->Cell(20,5,"CORRIGIDO ({$sInflator})",1,0,"C",1);
      $pdf->Cell(20,5,"MULTA ({$sInflator})",1,0,"C",1);
      $pdf->Cell(20,5,"JUROS ({$sInflator})",1,0,"C",1);
      $pdf->Cell(35,5,"TOTAL ({$sInflator})",1,1,"C",1);
      $lEscreveTotal      = false;
      $iExercicioAnterior = null;
      $pagina             = 0;
      $iY = 0;
      foreach ($aTipo as $oDebito) {

        $sSqlVlrInfla = "select fc_vlinf from fc_vlinf('".strtoupper($sInflator)."','{$oDebito->datainscricao}');";
        $rsVlrInfla   = db_query($sSqlVlrInfla);
        $nVlrInfla    = db_utils::fieldsMemory($rsVlrInfla,0)->fc_vlinf;

        if ( $oDebito->exercicio != $iExercicioAnterior && $lEscreveTotal && $lTotaliza) {

          $pdf->SetFont('','B',6);
          $pdf->Cell(129,5,"TOTAL EXERCICIO - {$iExercicioAnterior}",1,0,"C",0);
          $pdf->Cell(15,5,db_formatar(($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrhis/$nVlrInfla),'f'),1,0,"R",0);
          $pdf->Cell(15,5,db_formatar(($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrcor/$nVlrInfla),'f'),1,0,"R",0);
          $pdf->Cell(10,5,db_formatar(($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrmul/$nVlrInfla),'f'),1,0,"R",0);
          $pdf->Cell(10,5,db_formatar(($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrjur/$nVlrInfla),'f'),1,0,"R",0);
          $pdf->Cell(15,5,db_formatar(($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrtot/$nVlrInfla),'f'),1,1,"R",0);
          $pdf->setfont('','B',9);

        }
        $lEscreveTotal = true;
        if ($iY > 272){

          $pdf->AddPage();
          $pdf->SetFont('','B',6);
          $pdf->Cell(20, 5,"EXERC.",1,0,"C",1);
          $pdf->Cell(20, 5,"CÓD. DIVIDA",1,0,"C",1);
          $pdf->Cell(20, 5,"VLR HIST ({$sInflator}).",1,0,"C",1);
          $pdf->Cell(20, 5,"CORRIGIDO ({$sInflator})",1,0,"C",1);
          $pdf->Cell(20, 5,"MULTA ({$sInflator})",1,0,"C",1);
          $pdf->Cell(20, 5,"JUROS ({$sInflator})",1,0,"C",1);
          $pdf->Cell(35, 5,"TOTAL ({$sInflator})",1,1,"C",1);
          $pagina = $pdf->PageNo();

        }

        $pdf->SetFont('','',6);
        $pdf->Cell(20,5,$oDebito->exercicio,1,0,"C",0);
        $pdf->Cell(20,5,$oDebito->codigodivida,1,0,"C",0);
        $pdf->Cell(20,5,db_formatar(($oDebito->valorhistorico/$nVlrInfla),'f')    ,1,0,"R",0);
        $pdf->Cell(20,5,db_formatar(($oDebito->valorcorrigido/$nVlrInfla),'f')    ,1,0,"R",0);
        if ($oDebito->certidmassa == 0) {

          $pdf->Cell(20,5,db_formatar(($oDebito->valormulta/$nVlrInfla),'f'),1,0,"R",0);
          $pdf->Cell(20,5,db_formatar(($oDebito->valorjuros/$nVlrInfla),'f'),1,0,"R",0);
          $pdf->Cell(35,5,db_formatar(($oDebito->valortotal/$nVlrInfla),'f')   ,1,1,"R",0);

        } else {

          $pdf->Cell(20,5,db_formatar(0,'f')      ,1,0,"R",0);
          $pdf->Cell(20,5,db_formatar(0,'f')      ,1,0,"R",0);
          $pdf->Cell(35,5,db_formatar(($oDebito->valorcorrigido/$nVlrInfla),'f'),1,1,"R",0);

        }

        $iExercicioAnterior = $oDebito->exercicio;
        $iY = $pdf->GetY();

      }

      /**
       * Escreve o total do ultimo ano
       */
      if (($lEscreveTotal && $lTotaliza)) {

         $pdf->SetFont('','B',6);
         $pdf->Cell(40,5,"TOTAL EXERCICIO - {$iExercicioAnterior}",1,0,"C",0);
         $pdf->Cell(20,5,db_formatar(($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrhis/$nVlrInfla),'f'),1,0,"R",0);
         $pdf->Cell(20,5,db_formatar(($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrcor/$nVlrInfla),'f'),1,0,"R",0);
         $pdf->Cell(20,5,db_formatar(($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrmul/$nVlrInfla),'f'),1,0,"R",0);
         $pdf->Cell(20,5,db_formatar(($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrjur/$nVlrInfla),'f'),1,0,"R",0);
         $pdf->Cell(20,5,db_formatar(($aTotaisAno[$iTipo][$iExercicioAnterior]->vlrtot/$nVlrInfla),'f'),1,1,"R",0);
         $pdf->setfont('','B',9);

      }

      $pdf->SetFont('','B',6);
      $pdf->Cell(40,5,"TOTAL",1,0,"C",0);
      $pdf->Cell(20,5,db_formatar(($oTotalGeral[$iTipo]->valorhistorico/$nVlrInfla),'f'),1,0,"R",0);
      $pdf->Cell(20,5,db_formatar(($oTotalGeral[$iTipo]->valorcorrigido/$nVlrInfla),'f'),1,0,"R",0);
      $pdf->Cell(20,5,db_formatar(($oTotalGeral[$iTipo]->valormulta/$nVlrInfla),'f'),1,0,"R",0);
      $pdf->Cell(20,5,db_formatar(($oTotalGeral[$iTipo]->valorjuros/$nVlrInfla),'f'),1,0,"R",0);
      $pdf->Cell(35,5,db_formatar(($oTotalGeral[$iTipo]->valortotal/$nVlrInfla),'f'),1,1,"R",0);
      $pdf->setfont('','B',9);

      $pdf->Ln(5);
    }
  }
}