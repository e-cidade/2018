<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("libs/db_utils.php");

$oGet         = db_utils::postMemory($_GET);

$oDAOAguabase = db_utils::getDao('aguabase');

$sCampos      = "x01_matric, 
                (select trim(z01_nome) from proprietario_nome where j01_matric = x01_matric limit 1) as z01_nome,
                j14_nome, 
                x01_numero,
                (select x11_complemento from aguaconstr where x11_matric = x01_matric order by x11_codconstr limit 1) as x11_complemento ";

$rDAOAguabase = $oDAOAguabase->sql_record($oDAOAguabase->sql_query($oGet->matricula, $sCampos));

if($oDAOAguabase->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

$oAguabase    = db_utils::fieldsMemory($rDAOAguabase, 0, true);



$iAlt   = 8;
$iFonte = 10;

$oPDF = new PDF(); 
$oPDF->Open(); 
$oPDF->AliasNbPages();
$oPDF->setfillcolor(235);
$oPDF->setfont('arial', 'b', $iFonte);

$oPDF->addpage();

$oPDF->cell(24, $iAlt, 'MATRÍCULA: ', 0, 0, "L", 0);
$oPDF->setfont('arial', '', $iFonte);
$oPDF->cell(24, $iAlt, $oAguabase->x01_matric, 0, 1, "L", 0);

$oPDF->setfont('arial', 'b', $iFonte);
$oPDF->cell(24, $iAlt, 'RUA:  ' , 0, 0, "L", 0);
$oPDF->setfont('arial', '', $iFonte);
$oPDF->cell(72, $iAlt, $oAguabase->j14_nome       , 0, 0, "L", 0);
$oPDF->setfont('arial', 'b', $iFonte);
$oPDF->cell(12, $iAlt, 'NRO:', 0, 0, "L", 0);
$oPDF->setfont('arial', '', $iFonte);
$oPDF->cell(24, $iAlt, $oAguabase->x01_numero     , 0, 0, "L", 0);
$oPDF->setfont('arial', 'b', $iFonte);
$oPDF->cell(12, $iAlt, 'COMP: ' , 0, 0, "L", 0);
$oPDF->setfont('arial', '', $iFonte);
$oPDF->cell(48, $iAlt, $oAguabase->x11_complemento, 0, 1, "L", 0);

$oPDF->setfont('arial', 'b', $iFonte);
$oPDF->cell(24, $iAlt, 'NOME: ', 0, 0, "L", 0);
$oPDF->setfont('arial', '', $iFonte);
$oPDF->cell(168, $iAlt, $oAguabase->z01_nome, 0, 1, "L", 0);

$oPDF->setfont('arial', 'b', $iFonte);
$oPDF->cell(96, $iAlt, 'Nº HIDROMETRO: ', 0, 0, "L", 0);
$oPDF->cell(96, $iAlt, 'DATA: '         , 0, 1, "L", 0);

$oPDF->cell(48, $iAlt, 'LEITURA: ', 0, 0, "L", 0);
$oPDF->cell(48, $iAlt, 'MARCA:   ', 0, 0, "L", 0);
$oPDF->cell(48, $iAlt, 'POLEGADA:', 0, 0, "L", 0);
$oPDF->cell(48, $iAlt, 'VIRADA:  ', 0, 1, "L", 0);

$oPDF->Output();