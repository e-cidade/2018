<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
      $oDaoGrupoContaDuplicado->incluir(null);
      
      if ($oDaoGrupoContaDuplicado->erro_status == "0") {
        
        $erro_msg = "Não foi possível virar o grupo de contas do material. Contate o suporte.";
        $sqlerro  = true;
      }
    }
  }
}
?>