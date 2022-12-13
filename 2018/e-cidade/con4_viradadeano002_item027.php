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
  
  $oDaoClabensConplano = db_utils::getDao("clabensconplano");
  $sWhere              = " t86_anousudepreciacao = {$anoorigem} ";
  $sSqlClaBensConplano = $oDaoClabensConplano->sql_query_file(null,"*",null,$sWhere);
  $rsClabensConplano   = $oDaoClabensConplano->sql_record($sSqlClaBensConplano);
  
  if($oDaoClabensConplano->numrows > 0) {
    
    for($iClabem = 0; $iClabem < $oDaoClabensConplano->numrows; $iClabem++ ) {
    
      $oClabem             = db_utils::fieldsMemory($rsClabensConplano, $iClabem);
      
      $oDaoClabensConplanoDuplicado = db_utils::getDao("clabensconplano");
      
      $oDaoClabensConplanoDuplicado->t86_clabens             = $oClabem->t86_clabens;
      $oDaoClabensConplanoDuplicado->t86_conplano            = $oClabem->t86_conplano;              
      $oDaoClabensConplanoDuplicado->t86_anousu              = $anodestino;
      $oDaoClabensConplanoDuplicado->t86_conplanodepreciacao = $oClabem->t86_conplanodepreciacao;
      $oDaoClabensConplanoDuplicado->t86_anousudepreciacao   = $anodestino;
      
      $oDaoClabensConplanoDuplicado->incluir(null);
      
      if ($oDaoClabensConplanoDuplicado->erro_status == "0") {
      
        $erro_msg = "Não foi possível virar as contas da classificação dos materiais. Contate o suporte.";
        $sqlerro  = true;
      }
    }
  }
}
?>