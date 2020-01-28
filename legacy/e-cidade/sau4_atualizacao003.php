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
require_once('libs/db_sql.php');

$lFlagErro = false;
$sMsgErro  = '';

$pFile = @fopen("tmp/conferencia_$mes$ano", 'r');
if(!is_resource($pFile)) {
  
  $lFlagErro = true;
  $sMsgErro  = 'Arquivo de conferência não gerado.';

} else {

  $aFile = file("tmp/conferencia_$mes$ano");
	$aVer  = explode('|', $aFile[count($aFile) - 1]);
  if (trim(strtolower($aVer[1])) == 'cancelado') {

    $lFlagErro = true;
    $sMsgErro  = 'Nada foi importado. Ocorreu um erro na importação do arquivo '.$aVer[0].'.';

  }

}

if ($lFlagErro) {
?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>
          <?=$sMsgErro?><br>
          <input type='button' value='Fechar' onclick='window.close()'>
          </b>
        </font>
      </td>
    </tr>
  </table>
<?
	exit;
}

$oPdf  = new PDF();

$oPdf->Open();
$oPdf->AliasNbPages();

$head2 = "Conferência Atualização SIA/SUS";
$head3 = "Competência: $mes/$ano";

$oPdf->addpage();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', 'B', 7);
$oPdf->cell(90, 4, 'Arquivo', 1, 0, 'C', 1);
$oPdf->cell(30, 4, 'Procedimento', 1, 0, 'C', 1);
$oPdf->cell(30, 4, 'Registros Inseridos', 1, 1, 'C', 1);

$iTam = count($aFile);
for ($iCont = 0; $iCont < $iTam; $iCont++){
	
	$aConf = explode('|', $aFile[$iCont]);
	
	$oPdf->setfont('arial','',7);
	$oPdf->cell(90, 4, $aConf[0], 1, 0, 'L', 0);
	$oPdf->cell(30, 4, $aConf[1], 1, 0, 'L', 0);
  if (trim(strtolower($aConf[1])) == 'cancelado') {
    $aConf[2] = 0;
  }
	$oPdf->cell(30, 4, $aConf[2].' registros', 1, 1, 'L', 0);

}
$oPdf->Output();
?>