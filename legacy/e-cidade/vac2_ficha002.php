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

require_once(modification('fpdf151/pdf.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('ext/php/adodb-time.inc.php'));
require_once(modification('libs/db_stdlibwebseller.php'));

$oDaoCgsUnd        = db_utils::getdao('cgs_und');
$oDaoVacVacinaDose = db_utils::getdao('vac_vacinadose');
$oDaoVacCalendario = db_utils::getdao('vac_calendario');

function novoCabecalho($oPdf, $iCgs, $sNome, $dNasc, $sPai, $sMae, $sEndereco, $sNumCompl, $sMunicipio, $sUf) {

  $lCor      = false;
  $iTam      = 4;
  $iTamFonte = 8.0;
  $iX        = 30;

  $oPdf->setfont('arial', 'B', $iTamFonte);
  $oPdf->cell(208, $iTam, 'Ficha de Vacinação', 0, 1, 'C', $lCor);

  $oPdf->setX($iX);
  $oPdf->setfont('arial', '', $iTamFonte);
  $oPdf->cell(15, $iTam, 'CGS:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', $iTamFonte);
  $oPdf->cell(170, $iTam, $iCgs, 0, 1, 'L', $lCor);

  $oPdf->setX($iX);
  $oPdf->setfont('arial', '', $iTamFonte);
  $oPdf->cell(15, $iTam, 'Nome:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', $iTamFonte);
  $oPdf->cell(80, $iTam, $sNome, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', $iTamFonte);
  $oPdf->cell(15, $iTam, 'Nasc:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', $iTamFonte);
  $oPdf->cell(70, $iTam, $dNasc, 0, 1, 'L', $lCor);

  $oPdf->setX($iX);
  $oPdf->setfont('arial', '', $iTamFonte);
  $oPdf->cell(15, $iTam, 'Pai:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', $iTamFonte);
  $oPdf->cell(80, $iTam, $sPai, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', $iTamFonte);
  $oPdf->cell(15, $iTam, 'Mãe:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', $iTamFonte);
  $oPdf->cell(70, $iTam, $sMae, 0, 1, 'L', $lCor);

  $oPdf->setX($iX);
  $oPdf->setfont('arial', '', $iTamFonte);
  $oPdf->cell(15, $iTam, 'End:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', $iTamFonte);
  $oPdf->cell(80, $iTam, $sEndereco, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', $iTamFonte);
  $oPdf->cell(15, $iTam, 'Nº/Compl:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', $iTamFonte);
  $oPdf->cell(70, $iTam, $sNumCompl, 0, 1, 'L', $lCor);

  $oPdf->setX($iX);
  $oPdf->setfont('arial', '', $iTamFonte);
  $oPdf->cell(15, $iTam, 'Município:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', $iTamFonte);
  $oPdf->cell(80, $iTam, $sMunicipio, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', $iTamFonte);
  $oPdf->cell(15, $iTam, 'UF:',  0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', $iTamFonte);
  $oPdf->cell(70, $iTam, $sUf, 0, 1, 'L', $lCor);

}

function novoCalendario($oPdf, $sNome, &$iX, &$iY) {

  $oPdf->setfont('arial', 'B', 8);
  $oPdf->setXY($iX, $iY);
  $lCor = false;
  $iTam = 4;
  $iY  += $iTam;
     
  $oPdf->cell(45, $iTam, $sNome, 0, 0, 'C', $lCor);

}

function novaVacina($oPdf, $sNome, &$iX, &$iY) {
  
  $oPdf->setfont('arial', 'B', 8);
  $oPdf->setXY($iX, $iY);
  $lCor = false;
  $iTam = 3.5;
  $iY  += $iTam;

  $oPdf->cell(46, $iTam, $sNome, 'B', 1, 'L', $lCor);

  $oPdf->setXY($iX, $iY);
  $iY += $iTam;

  $oPdf->cell(13, $iTam, '', 'L', 0, 'L', $lCor);
  $oPdf->cell(17, $iTam, 'Vencimento/', 0, 0, 'C', $lCor);
  $oPdf->cell(16, $iTam, 'Aplicada', 'R', 0, 'C', $lCor);

}

function novaDose($oPdf, $sNome, $dVencimento, $dAplicada, &$iX, &$iY) {

  $oPdf->setfont('arial', 'B', 6);
  $oPdf->setXY($iX, $iY);
  $lCor = false;
  $iTam = 4;
  $iY  += $iTam;

  $oPdf->cell(16, $iTam, $sNome, 'L', 0, 'L', $lCor);

  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(15, $iTam, $dVencimento, 1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, $dAplicada, 1, 0, 'C', $lCor);

}

function fechaVacina($oPdf, $iX, &$iY) {
  
  $lCor = false;
  $iTam = 1;
  $oPdf->setXY($iX, $iY);
  $iY  += $iTam + 0.2;

  $oPdf->cell(46, $iTam, '', 'LBR', 0, 'C', $lCor);

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

/* Bloco para obtenção das informações do CGS */
$sSql  = $oDaoCgsUnd->sql_query_file($iCgs);
$rsCgs = $oDaoCgsUnd->sql_record($sSql);

/* Bloco para obtenção do número de calendários existentes */
$sSql         = $oDaoVacCalendario->sql_query_file(null, ' count(*) as numcalend ');
$rsCalendario = $oDaoVacCalendario->sql_record($sSql);

/* Bloco para obtenção das informações das vacinas/doses */
$sCampos          = ' vc07_i_codigo,';
$sCampos          = ' vc06_i_codigo,';
$sCampos         .= ' vc06_c_descr,';
$sCampos         .= ' vc05_i_codigo,';
$sCampos         .= ' vc05_c_descr,';
$sCampos         .= ' vc03_c_descr,';
$sCampos         .= ' vc07_i_faixainidias,';
$sCampos         .= ' vc07_i_faixainimes,';
$sCampos         .= ' vc07_i_faixainiano,';
$sCampos         .= ' vc07_i_faixafimdias,';
$sCampos         .= ' vc07_i_faixafimmes,';
$sCampos         .= ' vc07_i_faixafimano,';
$sCampos         .= ' vc07_i_diasatraso,';
$sCampos         .= ' vc07_i_diasantecipacao,';
$sCampos         .= ' (select vc16_d_dataaplicada';
$sCampos         .= '    from vac_aplica';
$sCampos         .= '      left join vac_aplicaanula on vac_aplicaanula.vc18_i_aplica = vac_aplica.vc16_i_codigo';
$sCampos         .= '      left join vac_aplicalote on vac_aplicalote.vc17_i_aplica = vac_aplica.vc16_i_codigo';
$sCampos         .= '      inner join db_usuarios on db_usuarios.id_usuario = vac_aplica.vc16_i_usuario';
$sCampos         .= "        where vc16_i_cgs = $iCgs";
$sCampos         .= '          and vc16_i_dosevacina = vc07_i_codigo';
$sCampos         .= '          and vc18_i_codigo is null';
$sCampos         .= '            order by vc16_i_codigo desc';
$sCampos         .= '              limit 1)';
$sCampos         .= ' as vc16_d_dataaplicada';

$sOrderBy        = 'vc05_i_idadeini, vc05_i_codigo, vc06_c_descr, vc06_i_codigo, vc03_i_ordem';

$sSql            = $oDaoVacVacinaDose->sql_query2(null, $sCampos, $sOrderBy);
$rsVacVacinaDose = $oDaoVacVacinaDose->sql_record($sSql);

$iLinhas         = $oDaoVacVacinaDose->numrows;

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

$oPdf = new PDF();
$oPdf->Open();

$oDadosCgs       = db_utils::fieldsmemory($rsCgs, 0);
$oCalendario     = db_utils::fieldsmemory($rsCalendario, 0);

$head1 = 'Ficha de vacinação';

$oPdf->AliasNbPages();
$oPdf->setMargins(1, 2, 1);
$iNumCalendarios = $oCalendario->numcalend;

$oPdf->Addpage('P');

$oPdf->setfillcolor(223);


novoCabecalho($oPdf, $oDadosCgs->z01_i_cgsund, $oDadosCgs->z01_v_nome, formataData($oDadosCgs->z01_d_nasc, 2), 
              $oDadosCgs->z01_v_pai, $oDadosCgs->z01_v_mae, $oDadosCgs->z01_v_ender,
              $oDadosCgs->z01_i_numero.' '.$oDadosCgs->z01_v_compl, $oDadosCgs->z01_v_munic,
              $oDadosCgs->z01_v_uf
             );

$aNasc           = explode('-', $oDadosCgs->z01_d_nasc);
//calculo o x inicial para posicionar os calendarios
$iX              = (210 - ($iNumCalendarios * 45)) / 2;
$iY = $iYini     = $oPdf->getY() + 1;
$oDados          = db_utils::fieldsmemory($rsVacVacinaDose, 0);
$iCalendario     = $oDados->vc05_i_codigo;
$iVacina         = $oDados->vc06_i_codigo;
$lFlagCalendario = false;
novoCalendario($oPdf, $oDados->vc05_c_descr, $iX, $iY);
novaVacina($oPdf, $oDados->vc06_c_descr, $iX, $iY);
for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
  
  $oDados = db_utils::fieldsmemory($rsVacVacinaDose, $iCont);
  if ($iCalendario != $oDados->vc05_i_codigo) {
   
    fechaVacina($oPdf, $iX, $iY);
    $iX         += 50;
    $iY          = $iYini;
    $iCalendario = $oDados->vc05_i_codigo;
    $iVacina     = -1;
    novoCalendario($oPdf, $oDados->vc05_c_descr, $iX, $iY);
    $lFlagCalendario = true;

  }

  if ($iVacina != $oDados->vc06_i_codigo) {
 
    if (!$lFlagCalendario) {
      fechaVacina($oPdf, $iX, $iY);
    }
    $iVacina = $oDados->vc06_i_codigo;
    novaVacina($oPdf, $oDados->vc06_c_descr, $iX, $iY);
    $lFlagCalendario = false;

  }
  
  $dVencimento = somaDataDiaMesAno($aNasc[2], $aNasc[1], $aNasc[0], 
                                   $oDados->vc07_i_faixainidias + $oDados->vc07_i_diasatraso, 
                                   $oDados->vc07_i_faixainimes, $oDados->vc07_i_faixainiano
                                  );
  novaDose($oPdf, $oDados->vc03_c_descr, $dVencimento, formataData($oDados->vc16_d_dataaplicada, 2), $iX, $iY);

}
fechaVacina($oPdf, $iX, $iY);

$oPdf->Output();
  
?>