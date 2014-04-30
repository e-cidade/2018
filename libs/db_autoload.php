<?php
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


function __autoload($sClassName) {

  //echo "$sClassName\n";
   $aIncludeDirs = array();

   $aIncludeDirs[] = "model/";
   $aIncludeDirs[] = "model/ambulatorial/";
   $aIncludeDirs[] = "model/arrecadacao/";
   $aIncludeDirs[] = "model/arrecadacao/abatimento/";
   $aIncludeDirs[] = "model/cadastro/";
   $aIncludeDirs[] = "model/caixa/";
   $aIncludeDirs[] = "model/caixa/arquivos/";
   $aIncludeDirs[] = "model/caixa/slip/";
   $aIncludeDirs[] = "model/compras/";
   $aIncludeDirs[] = "model/configuracao/";
   $aIncludeDirs[] = "model/configuracao/avaliacao/";
   $aIncludeDirs[] = "model/configuracao/endereco/";
   $aIncludeDirs[] = "model/configuracao/inconsistencia/";
   $aIncludeDirs[] = "model/configuracao/inconsistencia/educacao/";
   $aIncludeDirs[] = "model/configuracao/mensagem/";
   $aIncludeDirs[] = "model/configuracao/notificacao/";
   $aIncludeDirs[] = "model/contabilidade/";
   $aIncludeDirs[] = "model/contabilidade/arquivos/";
   $aIncludeDirs[] = "model/contabilidade/arquivos/sigfis/";
   $aIncludeDirs[] = "model/contabilidade/contacorrente/";
   $aIncludeDirs[] = "model/contabilidade/lancamento/";
   $aIncludeDirs[] = "model/contabilidade/planoconta/";
   $aIncludeDirs[] = "model/contabilidade/relatorios/";
   $aIncludeDirs[] = "model/contabilidade/relatorios/sigfis/";
   $aIncludeDirs[] = "model/contrato/";
   $aIncludeDirs[] = "model/diversos/";
   $aIncludeDirs[] = "model/divida/";
   $aIncludeDirs[] = "model/educacao/";
   $aIncludeDirs[] = "model/educacao/avaliacao/";
   $aIncludeDirs[] = "model/educacao/censo/";
   $aIncludeDirs[] = "model/educacao/classificacao/";
   $aIncludeDirs[] = "model/educacao/recursohumano/";
   $aIncludeDirs[] = "model/educacao/ocorrencia/";
   $aIncludeDirs[] = "model/educacao/progressaoparcial/";
   $aIncludeDirs[] = "model/educacao/relatorio/";
   $aIncludeDirs[] = "model/empenho/";
   $aIncludeDirs[] = "model/estoque/";
   $aIncludeDirs[] = "model/farmacia/";
   $aIncludeDirs[] = "model/financeiro/";
   $aIncludeDirs[] = "model/fiscal/";
   $aIncludeDirs[] = "model/fiscal/webservice/";
   $aIncludeDirs[] = "model/habitacao/";
   $aIncludeDirs[] = "model/issqn/";
   $aIncludeDirs[] = "model/juridico/";
   $aIncludeDirs[] = "model/orcamento/";
   $aIncludeDirs[] = "model/orcamento/programa/";
   $aIncludeDirs[] = "model/orcamento/suplementacao/";
   $aIncludeDirs[] = "model/patrimonio/";
   $aIncludeDirs[] = "model/patrimonio/depreciacao/";
   $aIncludeDirs[] = "model/pessoal/";
   $aIncludeDirs[] = "model/pessoal/calculofinanceiro/";
   $aIncludeDirs[] = "model/pessoal/arquivos/";
   $aIncludeDirs[] = "model/pessoal/arquivos/dirf/";
   $aIncludeDirs[] = "model/pessoal/arquivos/siprev/";
   $aIncludeDirs[] = "model/pessoal/ferias/";
   $aIncludeDirs[] = "model/pessoal/relatorios/";
   $aIncludeDirs[] = "model/pessoal/std/";
   $aIncludeDirs[] = "model/protocolo/";
   $aIncludeDirs[] = "model/recursosHumanos/";
   $aIncludeDirs[] = "model/social/";
   $aIncludeDirs[] = "model/social/cadastrounico/";
   $aIncludeDirs[] = "model/tfd/";
   $aIncludeDirs[] = "model/transporteescolar/";
   $aIncludeDirs[] = "model/veiculos/";
   $aIncludeDirs[] = "model/viradaIPTU/";
   $aIncludeDirs[] = "model/webservices/";

   /**
    * Opcoes alternativas aos diretorios padroes
    */
   $aExceptions[]  = "std/";
   $aExceptions[]  = "libs/";
   $aExceptions[]  = "libs/exceptions/";

   foreach($aExceptions as $sDiretorioExcecao) {

     $sArquivoExcecao = $sDiretorioExcecao . $sClassName . '.php';

     if (file_exists($sArquivoExcecao)) {

       require_once($sArquivoExcecao);
       return true;
     }

   }

   if (substr($sClassName, 0, 3) == 'cl_') {

     $sClassNameDao = str_replace("cl_", "db_", $sClassName);
     require_once "classes/{$sClassNameDao}_classe.php";
     return true;

   } else {

     foreach($aIncludeDirs as $sDirectory) {

       $sFile = "{$sDirectory}{$sClassName}.model.php";

       if (file_exists($sFile)) {

         require_once($sFile);
         break;
       }
     }
   }

   return true;
}