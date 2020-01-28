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

require_once('fpdf151/scpdf.php');
require_once('libs/db_utils.php');
require_once('model/encaminhamentos.model.php');
require_once('libs/db_stdlibwebseller.php');

$oDaoSau_encaminhamentos = db_utils::getdao('sau_encaminhamentos');
$oDaoDb_usuacgm = db_utils::getdao('db_usuacgm');
$oDaoMedicos = db_utils::getdao('medicos');
$oDaoCgmdoc = db_utils::getdao('cgmdoc');
$oDaoUnidades = db_utils::getdao('unidades');
$oDaoProntuarios = db_utils::getdao('prontuarios');
$oDaoCgs_cartaosus = db_utils::getdao('cgs_cartaosus');
$oEncaminhamento = new encaminhamento();

function novoCabecalho($oPDF, $sTitulo) {

  $lCor = true;
  $oPDF->setFont('arial','B',10);
  $oPDF->setTextColor(255, 255, 255);
  $oPDF->setFillColor(0, 0, 0);
  $oPDF->cell(190,5,$sTitulo,1,1,'C',$lCor);
  $oPDF->cell(190,2,'','LR',1,'C',false);

}

/*
* Imprime uma linha para preenchimento
*/
function linhaPreenchimento($oPDF, $nLinhas) {

  $fTamFonteAtual = $oPDF->FontSizePt;
  $sFamiliaFonteAtual = $oPDF->FontFamily;
  $sEstiloFonteAtual = $oPDF->FontStyle;
  $oPDF->setFont('arial','',11);
  for($i = 0; $i < $nLinhas; $i++) {

    $oPDF->cell(190,5,'______________________________________________________________________________________','LR',1,'L',false);

  }
  $oPDF->setFont($sFamiliaFonteAtual, $sEstiloFonteAtual, $fTamFonteAtual);

}

function finalizaBox($oPDF, $lEspaco = false) {
   
  $lCor = false;
  if($lEspaco) {
    $oPDF->cell(190,1,'','LR',1,'C',$lCor);
  }
  $oPDF->cell(190,2,'','T',1,'C',$lCor);

}

function espacoBranco($oPDF, $fW) {
  $oPDF->cell(2,$fW,'',0,0,'C',false);
}

function cantoEsq($oPDF, $fW) {
  $oPDF->cell(2,$fW,'','L',0,'C',false);
}

function cantoDir($oPDF, $fW, $lEspaco = false) {

 $oPDF->cell(2,$fW,'','R',1,'C',false);
 if($lEspaco) {
    $oPDF->cell(190,2,'','LR',1,'C',false);
  }

}

function cabecalhoPrincipal($oPDF) {

  $oPDF->image('./imagens/simbolo_sus.jpeg', 12, 4.5, 26, 12);

  $lCor = false;
  $oPDF->setFont('arial','',9);

  $oPDF->cell(68,14.5,'',1,0,'C',$lCor);

  $oPDF->text(44,7.5,'Sistema');
  $oPDF->text(44,11.5,converteCodificacao('Único de'));
  $oPDF->text(44,15.5,converteCodificacao('Saúde'));

  $oPDF->text(62,7.5,converteCodificacao('Ministério'));
  $oPDF->text(62,11.5,'da');
  $oPDF->text(62,15.5,converteCodificacao('Saúde'));
  
  $oPDF->setFont('arial','B',10);
  espacoBranco($oPDF, 14.5);
  $oPDF->cell(120,7.25,converteCodificacao('LAUDO PARA A SOLICITAÇÃO/AUTORIZAÇÃO DE'),'LTR',1,'C',$lCor);
  $oPDF->cell(70,7.25,'',0,0,'C',$lCor);
  $oPDF->cell(120,7.25,converteCodificacao('PROCEDIMENTO AMBULATORIAL'),'LBR',1,'C',$lCor);
/* 
  $oPDF->line(18, 4, 22, 4); //  _ 
  $oPDF->line(18, 4, 18, 8); // | |
  $oPDF->line(22, 4, 22, 8); //

  $oPDF->line(12, 8, 28, 8);   //   ___________
  $oPDF->line(12, 8, 12, 12);  //  |___________|  
  $oPDF->line(12, 12, 28, 12); //
  $oPDF->line(28, 8, 28, 12);  //

  $oPDF->line(18, 12, 18, 16); //  
  $oPDF->line(18, 16, 22, 16); // |_|
  $oPDF->line(22, 12, 22, 16); //  
  
  $oPDF->line(12, 8, 17, 8.6);
  $oPDF->line(17, 8.6, 21.6, 12);
  $oPDF->line(21.6, 12, 22, 16);

*/
 $oPDF->cell(190,1,'',0,1,'C',$lCor);
 // $oPDF->line(4, 8, 8, 8.5);
}

function boxIdentificacaoEstabelecimentoSolicitante($oPDF, $sUnidade, $sCnes) {

  novoCabecalho($oPDF, converteCodificacao('IDENTIFICAÇÃO DO ESTABELECIMENTO DE SAÚDE (SOLICITANTE)'));
  $lCor = false;
  $oPDF->setFont('arial','',6);
  $oPDF->setTextColor(0, 0, 0);

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(162,$iH,converteCodificacao('NOME DO ESTABELECIMENTO DE SAÚDE SOLICITANTE'),0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(22,$iH,converteCodificacao('CNES'),0,0,'C',$lCor);
  cantoDir($oPDF, $iH);

  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(162,$iH,$sUnidade,1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(22,$iH,$sCnes,1,0,'C',$lCor);
  cantoDir($oPDF, $iH);

  finalizaBox($oPDF, true);

}

function boxIdentificacaoPaciente($oPDF, $sNome, $iProntuario, $iCns, $dDataNasc, $sSexo, $sRaca, $sNomeMae, $sTelef,
                                  $sNomeResp, $sEndereco, $sMunicResid, $sUf, $sCep) {
 
  novoCabecalho($oPDF, converteCodificacao('IDENTIFICAÇÃO DO PACIENTE'));
  $lCor = false;
  $oPDF->setFont('arial','',6);
  $oPDF->setTextColor(0, 0, 0);

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(158,$iH,'NOME DO PACIENTE',0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(26,$iH,converteCodificacao('N° DO PRONTUARIO'),0,0,'C',$lCor);
  cantoDir($oPDF, $iH); 
 
  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(158,$iH,$sNome,1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(26,$iH,$iProntuario,1,0,'C',$lCor);
  cantoDir($oPDF, $iH, true);

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(40,$iH,converteCodificacao('CARTÃO NACIONAL DE SAÚDE (CNS)'),0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(35,$iH,'DATA DE NASCIMENTO',0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(35,$iH,'SEXO',0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(70,$iH,converteCodificacao('RAÇA/COR'),0,0,'C',$lCor);
  cantoDir($oPDF, $iH);

  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(40,$iH,$iCns,1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(35,$iH,$dDataNasc,1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(35,$iH,$sSexo,1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(70,$iH,$sRaca,1,0,'C',$lCor);
  cantoDir($oPDF, $iH, true);
  
  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(158,$iH,converteCodificacao('NOME DA MÃE'),0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(26,$iH,'TELEFONE DE CONTATO',0,0,'C',$lCor);
  cantoDir($oPDF, $iH); 
 
  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(158,$iH,$sNomeMae,1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(26,$iH,$sTelef,1,0,'C',$lCor);
  cantoDir($oPDF, $iH, true);

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(158,$iH,converteCodificacao('NOME DO RESPONSÁVEL'),0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(26,$iH,'TELEFONE DE CONTATO',0,0,'C',$lCor);
  cantoDir($oPDF, $iH); 
 
  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(158,$iH,$sNomeResp,1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(26,$iH,'',1,0,'C',$lCor);
  cantoDir($oPDF, $iH, true);

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(186,$iH,converteCodificacao('ENDEREÇO (RUA, N°, BAIRRO)'),0,0,'C',$lCor);
  cantoDir($oPDF, $iH); 
 
  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(186,$iH,$sEndereco,1,0,'C',$lCor);
  cantoDir($oPDF, $iH, true);

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(130,$iH,converteCodificacao('MUNICÍPIO DE RESIDÊNCIA'),0,0,'C',$lCor);
  $oPDF->cell(32,$iH,converteCodificacao('CÓD. IBGE MUNICÍPIO'),0,0,'C',$lCor);
  $oPDF->cell(4,$iH,'UF',0,0,'C',$lCor);
  $oPDF->cell(20,$iH,'CEP',0,0,'C',$lCor);
  cantoDir($oPDF, $iH); 
 
  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(130,$iH,$sMunicResid,1,0,'C',$lCor);
  $oPDF->cell(32,$iH,'',1,0,'C',$lCor); 
  $oPDF->cell(4,$iH,$sUf,1,0,'C',$lCor);
  $oPDF->cell(20,$iH,$sCep,1,0,'C',$lCor);
  cantoDir($oPDF, $iH, true);

  finalizaBox($oPDF);
  
}

function dadosProcedimento($oPDF, $sCod, $sNome, $iQtde = 1, $lPrincipal = false) {
  
  $lCor = false;
  $sTipo = $lPrincipal ? 'PRINCIPAL' : 'SECUNDÁRIO';

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(48,$iH,converteCodificacao("CÓDIGO DO PROCEDIMENTO $sTipo"),0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(128,$iH,converteCodificacao("NOME DO PROCEDIMENTO $sTipo"),0,0,'C',$lCor);
  $oPDF->cell(8,$iH,'QTDE',0,0,'C',$lCor);
  cantoDir($oPDF, $iH);
  
  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(48,$iH,$sCod,1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(128,$iH,substr(urldecode($sNome),0,96),1,0,'C',$lCor); 
  $oPDF->cell(8,$iH,$iQtde,1,0,'C',$lCor);
  cantoDir($oPDF, $iH, true);

}

function boxProcedimentos($oPDF, $oProcedimentos) {
 
  novoCabecalho($oPDF, 'PROCEDIMENTO SOLICITADO'); 
  $lCor = false;
  $oPDF->setFont('arial','',6);
  $oPDF->setTextColor(0, 0, 0);

  $iNumProced = count($oProcedimentos);
  
  dadosProcedimento($oPDF,$oProcedimentos[0]->sProcedimento,$oProcedimentos[0]->sDescr, 1,true);
  
  novoCabecalho($oPDF, converteCodificacao('PROCEDIMENTO(S) SECUNDÁRIOS')); 
  $oPDF->setFont('arial','',6);
  $oPDF->setTextColor(0, 0, 0);

  for($iCont = 1; $iCont < 6; $iCont++) { 
 
    if($iCont < $iNumProced) {
      dadosProcedimento($oPDF,$oProcedimentos[$iCont]->sProcedimento,$oProcedimentos[$iCont]->sDescr);
    } else {
      dadosProcedimento($oPDF, '', '', '');
    }

  }
  finalizaBox($oPDF);

}

function boxJustificativaProcedimentos($oPDF) {

  novoCabecalho($oPDF, 'JUSTIFICATIVA DO(S) PROCEDIMENTOS SOLICITADO(S)'); 
  $lCor = false;
  $oPDF->setFont('arial','',6);
  $oPDF->setTextColor(0, 0, 0);

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(100,$iH,converteCodificacao('DESCRIÇÃO DO DIAGNÓSTICO'),0,0,'C',$lCor);
  $oPDF->cell(25,$iH,'CID10 PRINCIPAL',0,0,'C',$lCor);
  $oPDF->cell(25,$iH,converteCodificacao('CID10 SECUNDÁRIO'),0,0,'C',$lCor);
  $oPDF->cell(36,$iH,'CID10 CAUSAS ASSOCIADAS',0,0,'C',$lCor);
  cantoDir($oPDF, $iH);
  
  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(100,$iH,'',1,0,'C',$lCor);
  $oPDF->cell(25,$iH,'',1,0,'C',$lCor);
  $oPDF->cell(25,$iH,'',1,0,'C',$lCor);
  $oPDF->cell(36,$iH,'',1,0,'C',$lCor);
  cantoDir($oPDF, $iH, true);

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(186,$iH,converteCodificacao('OBSERVAÇÕES'),0,0,'C',$lCor);
  cantoDir($oPDF, $iH);
  
  $iH = 20;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(186,$iH,'',1,0,'C',$lCor);
  cantoDir($oPDF, $iH, true);

  finalizaBox($oPDF);

}

function boxSolicitacao($oPDF, $sSolicitante, $dDataSolic, $iTipoDoc, $iNumDoc) {
 
  $sXcpf = $sXcns = '  ';
  switch($iTipoDoc) {

     case 1: // CNS

       $sXcns = 'X';
       break;

     case 2: // CNS

       $sXcpf = 'X';
       break;
 
  }
  novoCabecalho($oPDF,converteCodificacao('SOLICITAÇÃO'));
  $lCor = false;
  $oPDF->setFont('arial','',6);
  $oPDF->setTextColor(0, 0, 0);

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(100,$iH,'NOME DO PROFISSIONAL SOLICITANTE',0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(26,$iH,converteCodificacao('DATA DA SOLICITAÇÃO'),0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(56,$iH,converteCodificacao('ASSINATURA E CARIMBO (N° REGISTRO DO CONSELHO)'),0,0,'C',$lCor);
  cantoDir($oPDF, $iH);
  
  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(100,$iH,$sSolicitante,1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(26,$iH,$dDataSolic,1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(56,6,'','LTR',0,'C',$lCor);
  cantoDir($oPDF, $iH, true);
 
  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(28,$iH,'DOCUMENTO',0,0,'C',$lCor);
  $oPDF->cell(100,$iH,converteCodificacao('N° DOCUMENTO (CNS/CPF) DO PROFISSIONAL SOLICITANTE'),0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(56,$iH,'','LR',0,'C',$lCor);
  cantoDir($oPDF, $iH);
  
  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(28,$iH,"($sXcns) CNS   ($sXcpf) CPF",1,0,'C',$lCor);
  $oPDF->cell(100,$iH,$iNumDoc,1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(56,$iH,'','LBR',0,'C',$lCor);
  cantoDir($oPDF, $iH, true);

  finalizaBox($oPDF);

}

function boxAutorizacao($oPDF) {
 
  novoCabecalho($oPDF,converteCodificacao('AUTORIZAÇÃO'));
  $lCor = false;
  $oPDF->setFont('arial','',6);
  $oPDF->setTextColor(0, 0, 0);

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(102,$iH,'NOME DO PROFISSIONAL AUTORIZADOR',0,0,'C',$lCor);
  $oPDF->cell(26,$iH,converteCodificacao('CÓD. ORGÃO EMISSOR'),0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(56,$iH,converteCodificacao('N° DA AUTORIZAÇÃO (APAC)'),0,0,'C',$lCor);
  cantoDir($oPDF, $iH);
  
  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(102,$iH,'',1,'C',$lCor);
  $oPDF->cell(26,$iH,'',1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(56,6,'','LTR',0,'C',$lCor);
  cantoDir($oPDF, $iH, true);
 
  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(28,$iH,'DOCUMENTO',0,0,'C',$lCor);
  $oPDF->cell(100,$iH,converteCodificacao('N° DOCUMENTO (CNS/CPF) DO PROFISSIONAL AUTORIZADOR'),0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(56,$iH,'','LR',0,'C',$lCor);
  cantoDir($oPDF, $iH);
  
  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(28,$iH,"(  ) CNS   (  ) CPF",1,0,'C',$lCor);
  $oPDF->cell(100,$iH,'',1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(56,$iH,'','LBR',0,'C',$lCor);
  cantoDir($oPDF, $iH, true);

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(28,$iH,converteCodificacao('DATA DA AUTORIZAÇÃO'),0,0,'C',$lCor);
  $oPDF->cell(100,$iH,converteCodificacao('ASSINATURA E CARIMBO (N° DO REGISTRO DO CONSELHO)'),0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(56,$iH,converteCodificacao('PERÍODO DE VALIDADE DA APAC'),0,0,'C',$lCor);
  cantoDir($oPDF, $iH);
  
  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(28,$iH,'',1,0,'C',$lCor);
  $oPDF->cell(100,$iH,'',1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(56,$iH,'',1,0,'C',$lCor);
  cantoDir($oPDF, $iH, true);

  finalizaBox($oPDF);

}

function boxIdentificacaoEstabelecimentoExecutante($oPDF, $sEstabelecimentoDest, $sCnsDest) {

  novoCabecalho($oPDF, converteCodificacao('IDENTIFICAÇÃO DO ESTABELECIMENTO DE SAÚDE (EXECUTANTE)'));
  $lCor = false;
  $oPDF->setFont('arial','',6);
  $oPDF->setTextColor(0, 0, 0);

  $iH = 2;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(162,$iH,converteCodificacao('NOME FANTASIA DO ESTABELECIMENTO DE SAÚDE EXECUTANTE'),0,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(22,$iH,converteCodificacao('CNES'),0,0,'C',$lCor);
  cantoDir($oPDF, $iH);

  $iH = 4;
  cantoEsq($oPDF, $iH);
  $oPDF->cell(162,$iH,$sEstabelecimentoDest,1,0,'C',$lCor);
  espacoBranco($oPDF, $iH);
  $oPDF->cell(22,$iH,$sCnsDest,1,0,'C',$lCor);
  cantoDir($oPDF, $iH);

  finalizaBox($oPDF, true);

}
function formataData($dData, $iTipo = 1) {

  if($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = $dData[2].'/'.$dData[1].'/'.$dData[0];
 return $dData;

}

$sCampos  = 'cgs_und.z01_v_nome, ';
$sCampos .= 'cgs_und.z01_i_cgsund, ';
$sCampos .= 'cgs_und.z01_d_nasc, ';
$sCampos .= 'cgs_und.z01_v_sexo, ';
$sCampos .= 'cgs_und.z01_c_raca, ';
$sCampos .= 'cgs_und.z01_c_nomeresp, ';
$sCampos .= 'cgs_und.z01_v_cgccpf, ';
$sCampos .= 'cgs_und.z01_v_ident, ';
$sCampos .= 'cgs_und.z01_v_mae, ';
$sCampos .= 'cgs_und.z01_v_ender, ';
$sCampos .= 'cgs_und.z01_v_uf, ';
$sCampos .= 'cgs_und.z01_i_numero, ';
$sCampos .= 'cgs_und.z01_v_bairro, ';
$sCampos .= 'cgs_und.z01_v_munic, ';
$sCampos .= 'cgs_und.z01_v_cep, ';
$sCampos .= 'cgs_und.z01_v_telef, ';
$sCampos .= 'cgs_und.z01_c_naturalidade, ';
$sCampos .= 'sau_encaminhamentos.s142_d_encaminhamento, ';
$sCampos .= 'sau_encaminhamentos.s142_d_validade, ';
$sCampos .= 'sau_encaminhamentos.s142_d_retorno, ';
$sCampos .= 'sau_encaminhamentos.s142_t_dadosclinicos, ';
$sCampos .= 'sau_encaminhamentos.s142_d_validade, ';
$sCampos .= 'sau_encaminhamentos.s142_i_prontuario, ';
$sCampos .= 'sau_encaminhamentos.s142_i_codigo, ';
$sCampos .= 'sau_encaminhamentos.s142_i_login, ';
$sCampos .= 'sau_encaminhamentos.s142_i_profsolicitante, ';
$sCampos .= 'b.z01_nome as sNomeSolicitante, ';
$sCampos .= 'b.z01_cgccpf as iCpfSolicitante, ';
$sCampos .= 'b.z01_numcgm as iCgmSolicitante, ';
$sCampos .= 'case when sau_encaminhamentos.s142_i_tipo = 1 then \'Consulta\' else \'Exame\' end as sTipo, ';
$sCampos .= 'cgm.z01_nome as sPrestadora, ' ;
$sCampos .= 'rhcbo.rh70_estrutural || \' - \' || rhcbo.rh70_descr as sEspecialidade, ';
$sCampos .= 'db_depart.descrdepto as sUnidDest, ';
$sCampos .= 'unidades.sd02_v_cnes as sCnesDest';

$sSql = $oDaoSau_encaminhamentos->sql_query2($iEncaminhamento, $sCampos);
$rsSau_encaminhamentos= $oDaoSau_encaminhamentos->sql_record($sSql);

$iLinhas = $oDaoSau_encaminhamentos->numrows;
if($iLinhas <= 0)
{
?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado.<br>
            <input type='button' value='Fechar' onclick='window.close()'>
          </b>
        </font>
      </td>
    </tr>
  </table>
<?
  exit;
}

$oDados = db_utils::fieldsmemory($rsSau_encaminhamentos,0);

$oPDF = new SCPDF();
$oPDF->Open();
$oPDF->AliasNbPages();
$oPDF->setTopMargin(3);
$oPDF->Addpage('P');
$lCor = false;
$oPDF->setfillcolor(223);
$oPDF->setfont('arial','',10);

/* Pega a unidade de origem (solicitante) atraves da faa informada */
if(!empty($oDados->s142_i_prontuario)) {

  $sSql = $oDaoProntuarios->sql_query($oDados->s142_i_prontuario, ' descrdepto, sd02_v_cnes ');
  $rsProntuarios = $oDaoProntuarios->sql_record($sSql);
  $oDadosProntuarios = db_utils::fieldsmemory($rsProntuarios, 0);
  $sUnidOrig = $oDadosProntuarios->descrdepto;
  $sCnesOrig = $oDadosProntuarios->sd02_v_cnes;

} else { 
  
  // pega a unidade da sessao
  $sSql = $oDaoUnidades->sql_query(db_getsession('DB_coddepto'), ' descrdepto, sd02_v_cnes ');
  $rsUnidades = $oDaoUnidades->sql_record($sSql);
  $oDadosUnidades = db_utils::fieldsmemory($rsUnidades, 0);
  $sUnidOrig = $oDadosUnidades->descrdepto;
  $sCnesOrig = $oDadosUnidades->sd02_v_cnes;

}

/* Pega o cartao sus do paciente */
$sSql = $oDaoCgs_cartaosus->sql_query(null, ' s115_c_cartaosus ', ' s115_c_tipo asc ',
                                      ' s115_i_cgs = '.$oDados->z01_i_cgsund);
$rsCgs_cartaosus = $oDaoCgs_cartaosus->sql_record($sSql);
if($oDaoCgs_cartaosus->numrows != 0) { // se o paciente tem um cartao sus

  $oDadosCgs_cartaosus = db_utils::fieldsmemory($rsCgs_cartaosus, 0);
  $sCartaoSus = $oDadosCgs_cartaosus->s115_c_cartaosus;

}  else {
  $sCartaoSus = '';
}

/* Pega o cartao sus do profissional solicitante */
$sSql = $oDaoCgmdoc->sql_query(null, ' z02_i_cns ', '', ' z02_i_cgm = '.$oDados->icgmsolicitante);
$rsCgmdoc = $oDaoCgmdoc->sql_record($sSql);
if($oDaoCgmdoc->numrows != 0) {

  $oDadosCgmdoc = db_utils::fieldsmemory($rsCgmdoc, 0);
  $iNumDocSolicitante  = $oDadosCgmdoc->z02_i_cns;

} else {
  $iNumDocSolicitante = '';
}

if(empty($iNumDocSolicitante) || $iNumDocSolicitante == 0) {

  if(!empty($oDados->icpfsolicitante) && $oDados->icpfsolicitante != 0)  {

    $iNumDocSolicitante = $oDados->icpfsolicitante;
    $iTipoDocSolicitante = 2;

  } else {
    $iTipoDocSolicitante = 0;
  }

} else {
  $iTipoDocSolicitante = 1;
}

/* Trata dos dados do estabelecimento de destino */
$sCnesDest = '';
if(!empty($oDados->suniddest)) {

  $sEstabelecimentoDest = $oDados->suniddest;
  $sCnesDest = $oDados->scnesdest;

} else {
  $sEstabelecimentoDest = $oDados->sprestadora;
}

/* Pega os procedimentos */
$oProcedimentos = $oEncaminhamento->getProcedimentosEncaminhamento($iEncaminhamento)->oProcedimentos;

/* Evita a impressao de valores 0 na ficha */
if($oDados->z01_v_ender == '0') {
  $oDados0->z01_v_ender = '';
} 
if($oDados->z01_i_numero == 0) {
  $oDados0->z01_i_numero = '';
} 
if($oDados->z01_v_bairro == '0') {
  $oDados0->z01_v_bairro = '';
}
if($oDados->z01_v_cep == '0') {
  $oDados0->z01_v_cep = '';
}
if($oDados->z01_v_munic == '0') {
  $oDados0->z01_v_munic = '';
}
if($oDados->z01_c_nomeresp == '0') {
  $oDados0->z01_c_nomeresp = '';
}

cabecalhoPrincipal($oPDF);
boxIdentificacaoEstabelecimentoSolicitante($oPDF, $sUnidOrig, $sCnesOrig);
boxIdentificacaoPaciente($oPDF, substr($oDados->z01_v_nome,0,56), $oDados->s142_i_prontuario, $sCartaoSus,
                         formataData($oDados->z01_d_nasc, 2),
                         empty($oDados->z01_v_sexo) ? '' : $oDados->z01_v_sexo == 'M' ? 'Masculino' : 'Feminino',
                         $oDados->z01_c_raca, $oDados->z01_v_mae, $oDados->z01_v_telef, $oDados->z01_c_nomeresp,
                         substr($oDados->z01_v_ender.' '.$oDados->z01_i_numero.' '.$oDados->z01_v_bairro,0,100),
                         $oDados->z01_v_munic, $oDados->z01_v_uf, $oDados->z01_v_cep
                        );

boxProcedimentos($oPDF, $oProcedimentos);
boxJustificativaProcedimentos($oPDF);
boxSolicitacao($oPDF, $oDados->snomesolicitante, formataData($oDados->s142_d_encaminhamento, 2),
               $iTipoDocSolicitante, $iNumDocSolicitante);
boxAutorizacao($oPDF);
boxIdentificacaoEstabelecimentoExecutante($oPDF, $sEstabelecimentoDest, $sCnesDest);
//$sSolicitante, $dDataSolic, $iTipoDoc, $iNumDoc) {

$oPDF->Output();
?>