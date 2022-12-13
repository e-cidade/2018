<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

if (!$sqlerro) {
  
  $oDaoMaterialEstoqueGrupoConta = db_utils::getDao("materialestoquegrupoconta");
  $sWhere                        = " m66_anousu = {$anoorigem} ";
  $sSqlMaterialGrupoConta        = $oDaoMaterialEstoqueGrupoConta->sql_query_file(null,"*",null, $sWhere);
  $rsMaterialGrupoConta          = $oDaoMaterialEstoqueGrupoConta->sql_record($sSqlMaterialGrupoConta);
  
  if($oDaoMaterialEstoqueGrupoConta->numrows > 0) {
    
    for($iMaterialGrupoConta = 0; $iMaterialGrupoConta < $oDaoMaterialEstoqueGrupoConta->numrows; $iMaterialGrupoConta++ ) {
    
      $oGrupoConta             = db_utils::fieldsMemory($rsMaterialGrupoConta, $iMaterialGrupoConta);
      
      $oDaoGrupoContaDuplicado = db_utils::getDao("materialestoquegrupoconta");
      
      $oDaoGrupoContaDuplicado->m66_materialestoquegrupo  = $oGrupoConta->m66_materialestoquegrupo;
      $oDaoGrupoContaDuplicado->m66_codcon                = $oGrupoConta->m66_codcon;              
      $oDaoGrupoContaDuplicado->m66_anousu                = $anodestino;      
      $oDaoGrupoContaDuplicado->m66_codconvpd             = $oGrupoConta->m66_codconvpd;
      $oDaoGrupoContaDuplicado->incluir(null);
      
      if ($oDaoGrupoContaDuplicado->erro_status == "0") {
        
        $erro_msg = "N�o foi poss�vel virar o grupo de contas do material. Contate o suporte.";
        $sqlerro  = true;
      }
    }
  }
}
?>