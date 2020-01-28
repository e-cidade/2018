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
require_once("libs/db_utils.php");
require_once("classes/db_tfd_bpamagnetico_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$oDaoTfdBpaMagnetico = new cl_tfd_bpamagnetico;
$iLinhas             = $linhas;
$sLogin              = DB_getsession('DB_login');
$iCodigoBpa          = '';
$sArquivo            = '';

$sSql                = $oDaoTfdBpaMagnetico->sql_query('', '*', ' tf33_i_codigo desc', 
                                                       "tf32_i_mescompetencia = $tf32_i_mescompetencia ".
                                                       "and tf32_i_anocompetencia = $tf32_i_anocompetencia"
                                                      );
$rs                  = $oDaoTfdBpaMagnetico->sql_record($sSql);
if ($oDaoTfdBpaMagnetico->numrows > 0) {

  $oDados     = db_utils::fieldsmemory($rs, 0);
  $iLogin     = $oDados->tf33_i_login;
  $iCodigoBpa = $oDados->tf33_i_codigo;
  $sArquivo   = $oDados->tf33_c_nomearquivo;
  $aData      = explode('-', $oDados->tf33_d_datasistema);
  $dData      = $aData[2].'/'.$aData[1].'/'.$aData[0];

}	  

if ($oDaoTfdBpaMagnetico->numrows == 0) {
?>
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

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$head2 = "Recibo da Emissão do BPA";
$oPdf->ln(5);
$oPdf->addpage('P');

$oPdf->rect($oPdf->getX() + 55, $oPdf->getY() + 10, 100, 45, 'D');
$oPdf->setfont('arial', '', 10);
$oPdf->text($oPdf->getX() + 75, $oPdf->getY() + 15, 'Recibo da Emissão do BPA');
$oPdf->setfont('arial','',8);
$oPdf->text($oPdf->getX() + 59, $oPdf->getY() + 20, 'Arquivo: '. $iCodigoBpa.' - '.$sArquivo);
$oPdf->text($oPdf->getX() + 59, $oPdf->getY() + 25, 'Nome do Usuário: '.$iLogin.' - '.$sLogin);
$oPdf->text($oPdf->getX() + 59, $oPdf->getY() + 30, 'Data do Arquivo: '.$dData);
$oPdf->text($oPdf->getX() + 59, $oPdf->getY() + 35, 'Competência: '.$tf32_i_mescompetencia.'/'.$tf32_i_anocompetencia);
$oPdf->text($oPdf->getX() + 59, $oPdf->getY() + 40, 'Total de Registros: ' .$iLinhas. ' Registros');
$oPdf->text($oPdf->getX() + 59, $oPdf->getY() + 45, '___________________________________________');
$oPdf->text($oPdf->getX() + 59, $oPdf->getY() + 50, 'Responsável do Arquivo');
$oPdf->Output();

?>