<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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
        
        $erro_msg = "Não foi possível virar o grupo de contas do material. Contate o suporte.";
        $sqlerro  = true;
      }
    }
  }
}
?>