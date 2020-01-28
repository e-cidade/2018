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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_mer_cardapiodia_classe.php");
require_once("classes/db_mer_cardapiodata_classe.php");
require_once('libs/db_stdlibwebseller.php');
require_once('libs/db_utils.php');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clrotulo           = new rotulocampo;
$clmer_cardapiodia  = new cl_mer_cardapiodia;
$clmer_cardapiodata = new cl_mer_cardapiodata;
$escola             = db_getsession("DB_coddepto");
$descrdepto         = db_getsession("DB_nomedepto");
$iUsuarioNutri      = VerNutricionista(db_getsession('DB_id_usuario'));

if ($opcao == 1) {
	
  if ($periodo == 1) { // Semana
  	
    $ano    = date('Y', db_getsession('DB_datausu'));
    $mes    = date('m', db_getsession('DB_datausu'));
    $dia    = date('d', db_getsession('DB_datausu'));
    $weeke  = date('w', mktime(0, 0, 0, $mes, $dia, $ano));
    $inicio = date('Y-m-d', mktime(0, 0, 0, $mes, $dia + (2 - ($weeke + 1)), $ano));
    $fim    = date('Y-m-d', mktime(0, 0, 0, $mes, $dia + (6 - ($weeke + 1)), $ano));
    
  } else { // Mês
  	
    $ano    = date('Y', db_getsession('DB_datausu'));
    $mes    = date('m', db_getsession('DB_datausu'));
    $inicio = $ano.'-'.$mes.'-01';
    $fim    = date('Y-m-t', mktime(0, 0, 0, $mes, 1, $ano));
    
  }

}

$sCampos  = ' me27_c_nome, me01_i_percapita, me12_i_codigo, me03_c_tipo, me01_i_codigo, ';
$sCampos .= ' me01_c_nome, me01_f_versao, me12_d_data, ed18_i_codigo, ed18_c_nome, ';
$sCampos .= ' (select mer_cardapiodata.me13_d_data from mer_cardapiodata ';
$sCampos .= ' where me13_i_cardapiodiaescola = me37_i_codigo) as me13_d_data';
$sOrderBy = ' ed18_c_nome, me12_d_data, me03_i_orden';
$sWhere   = " me12_d_data between '$inicio' and '$fim' ";
if ($iUsuarioNutri != '') {
  
  // Obtenho todas as escolas atendidas pelo usuário nutricionista
  $oDaoMerNutricionistaEscola = db_utils::getdao('mer_nutricionistaescola');
  $sSql                       = $oDaoMerNutricionistaEscola->sql_query_nutricionistausuario(null, 'me31_i_escola', '',
                                                                                            'db_usuarios.id_usuario = '.
                                                                                            $iUsuarioNutri
                                                                                           );
  $rs                         = $oDaoMerNutricionistaEscola->sql_record($sSql);
  if ($oDaoMerNutricionistaEscola->numrows > 0) {
    
    $sCodEscolas = '';
    $sSepEscolas = '';
    for ($iCont = 0; $iCont < $oDaoMerNutricionistaEscola->numrows; $iCont++) {
      
      $sCodEscolas .= $sSepEscolas.db_utils::fieldsmemory($rs, $iCont)->me31_i_escola;
      $sSepEscolas  = ', ';

    }
    $sWhere .= " and me32_i_escola in ($sCodEscolas)";

  } else {
    $sWhere .= " and me32_i_escola = $escola";
  }

} else {
  $sWhere .= " and me32_i_escola = $escola";	
}

$sSql = $clmer_cardapiodia->sql_query_cardapiodiaescola(null, $sCampos, $sOrderBy, $sWhere);
$rs   = $clmer_cardapiodia->sql_record($sSql);

if ($clmer_cardapiodia->numrows == 0) {?>

  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhum registro encontrado.<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;
  
}

function novoCabecalho($oPdf) {

  $oPdf->setfillcolor(220);
  $oPdf->setfont('arial', 'b', 9);
  $iTam = 4;
  $lCor = true;

  $oPdf->cell(20, $iTam, 'Código ', 1, 0, 'C', $lCor);
  $oPdf->cell(70, $iTam, 'Refeição', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Versão', 1, 0, 'C', $lCor);
  $oPdf->cell(40, $iTam, 'Tipo de Refeição', 1, 0, 'C', $lCor);
  $oPdf->cell(40, $iTam, 'Cardápio', 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, 'Per Capita', 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, 'Data Consumo', 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, 'Data Baixa', 1, 1, 'C', $lCor);

}


function novaLinha($oPdf, $me01_i_codigo, $me01_c_nome, $me01_f_versao, $me03_c_tipo, $me27_c_nome, 
                   $me01_i_percapita, $me12_d_data, $me13_d_data) {
	
  $oPdf->setfont('arial', '', 8);
  $iTam = 4;
  $lCor = false;

  $oPdf->cell(20, $iTam, $me01_i_codigo, 1, 0, 'C', $lCor);
  $oPdf->cell(70, $iTam, $me01_c_nome, 1, 0, 'L', $lCor);
  $oPdf->cell(20, $iTam, $me01_f_versao, 1, 0, 'C', $lCor);
  $oPdf->cell(40, $iTam, $me03_c_tipo, 1, 0, 'L', $lCor);
  $oPdf->cell(40, $iTam, $me27_c_nome, 1, 0, 'L', $lCor);
  $oPdf->cell(30, $iTam, $me01_i_percapita, 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, $me12_d_data, 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, $me13_d_data, 1, 1, "C", $lCor);

}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$head1   = 'RELATÓRIO DE HISTÓRICO DE REFEIÇÕES';
$head3   = 'Período: '.db_formatar($inicio, 'd').' à '.db_formatar($fim, 'd');

$iEscola = -1;
for ($iCont = 0; $iCont < $clmer_cardapiodia->numrows; $iCont++) {
	
  $oDados = db_utils::fieldsmemory($rs, $iCont);


  if ($iEscola != $oDados->ed18_i_codigo || $oPdf->gety() > $oPdf->h - 30) {

    $head5   = 'Escola: '.$oDados->ed18_c_nome;
    $iEscola = $oDados->ed18_i_codigo;
    $oPdf->addpage('L');
    novoCabecalho($oPdf);

  }

  novaLinha($oPdf, $oDados->me01_i_codigo, trim($oDados->me01_c_nome), $oDados->me01_f_versao, 
            trim($oDados->me03_c_tipo), trim($oDados->me27_c_nome), $oDados->me01_i_percapita, 
            db_formatar($oDados->me12_d_data, 'd'), db_formatar($oDados->me13_d_data, 'd')
           );

}

$oPdf->Output();
?>