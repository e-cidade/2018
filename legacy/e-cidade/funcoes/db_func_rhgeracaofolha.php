<?php
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
$campos  = "DISTINCT rhgeracaofolha.rh102_sequencial,                 " . PHP_EOL;
$campos .= "         rhgeracaofolha.rh102_descricao,                  " . PHP_EOL;
$campos .= "         rhgeracaofolha.rh102_usuario,                    " . PHP_EOL;
$campos .= "         rhgeracaofolha.rh102_dtproc,                     " . PHP_EOL;
$campos .= "         rhgeracaofolha.rh102_ativo,                      " . PHP_EOL;
$campos .= "         rhgeracaofolha.rh102_mesusu,                     " . PHP_EOL;
$campos .= "         rhgeracaofolha.rh102_instit,                     " . PHP_EOL;
$campos .= "         rhgeracaofolha.rh102_anousu as db_rh102_anousu,  " . PHP_EOL;
$campos .= "         CAST (CASE rh103_tipofolha                       " . PHP_EOL;
$campos .= "                 WHEN 0 THEN 'Salário'                    " . PHP_EOL;
$campos .= "                 WHEN 1 THEN 'Adiantamento'               " . PHP_EOL;
$campos .= "                 WHEN 2 THEN 'Férias'                     " . PHP_EOL;
$campos .= "                 WHEN 3 THEN 'Rescisão'                   " . PHP_EOL;
$campos .= "                 WHEN 4 THEN '13º Salário'                " . PHP_EOL;
$campos .= "                 WHEN 5 THEN 'Complementar'               " . PHP_EOL;
$campos .= "                 WHEN 6 THEN 'Suplementar'                " . PHP_EOL;
$campos .= "                 ELSE 'S/N'                               " . PHP_EOL;
$campos .= "               END AS VARCHAR(20)                         " . PHP_EOL;
$campos .= "              ) AS rh103_tipofolha                        " . PHP_EOL;
