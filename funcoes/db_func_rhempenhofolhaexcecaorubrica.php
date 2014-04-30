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

$campos = "rhempenhofolhaexcecaorubrica.rh74_sequencial,             ";
$campos.= "rhempenhofolhaexcecaorubrica.rh74_rubric    || ' - ' || rhrubricas.rh27_descr as rh74_rubric,    ";
$campos.= "rhempenhofolhaexcecaorubrica.rh74_unidade   || ' - ' || orcunidade.o41_descr  as rh74_unidade,   ";
$campos.= "rhempenhofolhaexcecaorubrica.rh74_orgao     || ' - ' || orcorgao.o40_descr    as rh74_orgao,     ";
$campos.= "rhempenhofolhaexcecaorubrica.rh74_projativ  || ' - ' || orcprojativ.o55_descr as rh74_projativ,  ";
$campos.= "rhempenhofolhaexcecaorubrica.rh74_recurso   || ' - ' || orctiporec.o15_descr  as rh74_recurso,   ";
$campos.= "rhempenhofolhaexcecaorubrica.rh74_instit,                 ";
$campos.= "rhempenhofolhaexcecaorubrica.rh74_anousu                  ";