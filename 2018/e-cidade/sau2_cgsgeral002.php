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

require_once('fpdf151/pdf.php');
require_once('libs/db_utils.php');

$oDaocgs_cartaosus = db_utils::getdao('cgs_cartaosus');
$oDaocgs_und       = db_utils::getdao('cgs_und');

/* Pega o cartao sus do paciente */
function getCns($iCgs) {
  
  global $oDaocgs_cartaosus;
  
  $sSql = $oDaocgs_cartaosus->sql_query(null, ' s115_c_cartaosus ', ' s115_c_tipo asc ',
                                        ' s115_i_cgs = '.$iCgs);
  $rsCgs_cartaosus = $oDaocgs_cartaosus->sql_record($sSql);
  if($oDaocgs_cartaosus->numrows != 0) { // se o paciente tem um cartao sus

    $oDadosCgs_cartaosus = db_utils::fieldsmemory($rsCgs_cartaosus, 0);
    $sCartaoSus = $oDadosCgs_cartaosus->s115_c_cartaosus;

  }  else {
    $sCartaoSus = '';
  }
  
  return $sCartaoSus;

}

function novoCabecalho($oPdf) {

  $lCor = false;
  $oPdf->setfont('arial','B',10);
  $iTam = 5;
  
  $oPdf->cell(15, $iTam, 'CGS', 1, 0, 'L', $lCor);
  $oPdf->cell(88, $iTam, 'Nome', 1, 0, 'L', $lCor);
  $oPdf->cell(27, $iTam, 'Cartão SUS', 1, 0, 'L', $lCor);
  $oPdf->cell(20, $iTam, 'RG', 1, 0, 'L', $lCor);
  $oPdf->cell(20, $iTam, 'CPF', 1, 0, 'L', $lCor);
  $oPdf->cell(25, $iTam, 'Nascimento', 1, 1, 'L', $lCor);

}

function novoCgs($oPdf, $iCgs, $sNome, $sCns, $sRg, $sCpf, $dNasc) {

  $lCor = false;
  $oPdf->setfont('arial', '', 8);
  $iTam = 5;
  
  $oPdf->cell(15, $iTam, $iCgs, 1, 0, 'L', $lCor);
  $oPdf->cell(88, $iTam, $sNome, 1, 0, 'L', $lCor);
  $oPdf->cell(27, $iTam, $sCns, 1, 0, 'L', $lCor);
  $oPdf->cell(20, $iTam, substr(trim($sRg), 0, 10), 1, 0, 'L', $lCor);
  $oPdf->cell(20, $iTam, substr(trim($sCpf), 0, 11), 1, 0, 'L', $lCor);
  $oPdf->cell(25, $iTam, $dNasc, 1, 1, 'L', $lCor);

}

function novoTotal($oPdf, $iTotal) {

  $lCor = false;
  $oPdf->setfont('arial','B',10);

  $oPdf->cell(190, 5, 'TOTAL DE REGISTROS: '.$iTotal.' registros' , 0, 1, 'L', $lCor);

}

function novoCabecalhoTxt($oFd) {

  global $sNovaLinha;

  $sLinha = str_pad('  CGS', 10);
  $sLinha .= str_pad('             NOME', 45);
  $sLinha .= str_pad('  Cartão SUS', 20);
  $sLinha .= str_pad('   RG',  14);
  $sLinha .= str_pad('   CPF', 16);
  $sLinha .= str_pad('  NASCIMENTO', 14);
  $sLinha .=  $sNovaLinha;
  fwrite($oFd, $sLinha);

}

function novoTotalTxt($oFd, $iTotal) {

  $sLinha = 'TOTAL DE REGISTROS: '.$iTotal.' registros';
  fwrite($oFd, $sLinha);

}

function novoCgsTxt($oFd, $iCgs, $sNome, $sCns, $sRg, $sCpf, $dNasc) {

  global $sNovaLinha;

  $sLinha = str_pad("$iCgs,", 10);
  $sLinha .= str_pad("$sNome,", 45);
  $sLinha .= str_pad("$sCns,", 20);
  $sLinha .= str_pad(substr(trim($sRg), 0, 10).',',  14);
  $sLinha .= str_pad(substr(trim($sCpf), 0, 10).',', 16);
  $sLinha .= str_pad("$dNasc,", 14);
  $sLinha .=  $sNovaLinha;
  fwrite($oFd, $sLinha);

}

function formataData($dData, $iTipo = 1) {

  if(empty($dData)) {
    return '';
  }

  if($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];
 return $dData;

}


if($tipoOrdem == 1) {
  $sTipoOrdem = ' asc ';
} else {
  $sTipoOrdem = ' desc ';
}

switch($ordem) {

  case 1:
   
    $sOrderBy = ' z01_v_nome ';
    break;
  
  case 2:

    $sOrderBy = ' z01_i_cgsund ';
    break;

  default:

    $sOrderBy = ' z01_d_nasc ';

}

$sOrderBy .= $sTipoOrdem;
$sCampos = " cgs_und.z01_i_cgsund,
             cgs_und.z01_v_nome,
             cgs_und.z01_v_cgccpf,
             cgs_und.z01_v_ident,
             cgs_und.z01_d_nasc ";

$sSql = $oDaocgs_und->sql_query_file(null, $sCampos, $sOrderBy);

db_query('begin');
$sSql  = ' declare pCursor cursor for '.$sSql;
db_query($sSql);
$rs = db_query('fetch forward 1000 from pCursor;');
$iLinhas = pg_numrows($rs);

if($iLinhas == 0) {
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

$dDataAtual = date('d/m/Y', db_getsession('DB_datausu'));



/* GERA NO FORMATO PDF */
if($formato == 1) {

  $oPdf = new PDF();
  $oPdf->Open();
  $oPdf->AliasNbPages();

  $head1 = 'Relatório do Cadastro Geral da Saúde (CGS)';
  $head2 = '';
  $head3 = 'Data de emissão: '.$dDataAtual;
  $oPdf->Addpage('P');

  $oPdf->setfillcolor(223);
  
  $iTotal = 0;
  novoCabecalho($oPdf);
  while($iLinhas > 0) {

    for($iCont = 0; $iCont < $iLinhas; $iCont++) {

      $oDados = db_utils::fieldsmemory($rs, $iCont);
  
      if($oPdf->getY() >$oPdf->h - 30) {

        $oPdf->Addpage('P');
        novoCabecalho($oPdf);

      }

      $sCns = getCns($oDados->z01_i_cgsund);
      if($oDados->z01_v_ident == 0) {
        $oDados->z01_v_ident = '';
      }
      if($oDados->z01_v_cgccpf == 0) {
        $oDados->z01_v_cgccpf = '';
      }
      $dNasc = formataData($oDados->z01_d_nasc, 2);
      novoCgs($oPdf, $oDados->z01_i_cgsund, $oDados->z01_v_nome, $sCns, $oDados->z01_v_ident, $oDados->z01_v_cgccpf, $dNasc);
      $iTotal++;

    }

   $rs = db_query('fetch forward 1000 from pCursor;');
   $iLinhas = pg_numrows($rs);

  }

  novoTotal($oPdf, $iTotal);

  db_query('end');

  $oPdf->Output();

} else { /* GERA NO FORMATO TXT */

  $sEndArq = 'tmp/cgsgeral.txt';
  $oFd = fopen($sEndArq, 'wb');
  if($oFd == false) {
?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Não foi possível criar o arquivo <?=$sEndArq?>. Contate o administrador do sistema.<br>
            <input type='button' value='Fechar' onclick='window.close()'>
          </b>
        </font>
      </td>
    </tr>
  </table>
<?
    exit;
  }
  
  $e_linux = strpos(strtolower($HTTP_USER_AGENT), 'linux');
	if ($e_linux > 0) {
		$sNovaLinha = "\n";
	} else {
		$sNovaLinha = "\r\n";
	}

  //$sTitulo = 'Relatório do Cadastro Geral da Saúde (CGS)'."             Data da Emissão: $dDataAtual$sNovaLinha";
  //fwrite($oFd, $sTitulo);

  $iTotal = 0;
  novoCabecalhoTxt($oFd);
  while($iLinhas > 0) {

    for($iCont = 0; $iCont < $iLinhas; $iCont++) {

      $oDados = db_utils::fieldsmemory($rs, $iCont);
      $sCns = getCns($oDados->z01_i_cgsund);
      if($oDados->z01_v_ident == 0) {
        $oDados->z01_v_ident = '';
      }
      if($oDados->z01_v_cgccpf == 0) {
        $oDados->z01_v_cgccpf = '';
      }
      $dNasc = formataData($oDados->z01_d_nasc, 2);

      novoCgsTxt($oFd, $oDados->z01_i_cgsund, $oDados->z01_v_nome, $sCns, $oDados->z01_v_ident, $oDados->z01_v_cgccpf,
                 $dNasc);
      $iTotal++;

    }

    $rs = db_query('fetch forward 1000 from pCursor;');
    $iLinhas = pg_numrows($rs);

  }
  novoTotalTxt($oFd, $iTotal);
  fclose($oFd);
  db_query('end');

  /* Modifico os cabecalhos para oferecer para download o arquivo */
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  header("Content-type: application/x-msdownload");
  header("Content-Disposition: attachment; filename=cgsgeral.txt");

  flush();
  $oFd = fopen($sEndArq, 'r');
  $sArquivo = "";
  while(!feof($oFd)) { 

    $sArquivo .= fgetc($oFd);

  }

  fclose($oFd);
  echo $sArquivo;

}
  
?>