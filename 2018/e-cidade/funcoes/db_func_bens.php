<?
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

$campos  = "bens.t52_bem,                                                              ";
$campos .= "bens.t52_codcla,                                                           ";
$campos .= "bens.t52_numcgm,                                                           ";
$campos .= "bens.t52_valaqu,                                                           ";
$campos .= "bens.t52_dtaqu,                                                            ";
$campos .= "bens.t52_ident,                                                            ";
$campos .= "bens.t52_descr,                                                            ";
$campos .= "bens.t52_obs,                                                              ";
$campos .= "bens.t52_depart,                                                           ";
$campos .= "bens.t52_instit,                                                           ";
$campos .= "bens.t52_bensmarca,                                                        ";
$campos .= "bens.t52_bensmedida,                                                       ";
$campos .= "bens.t52_bensmodelo,                                                       ";
$campos .= "case                                                                       ";
$campos .= "  when exists (select 1 from bensbaix where bensbaix.t55_codbem = t52_bem) ";
$campos .= "    then 'Baixado'::varchar                                                    ";
$campos .= "  else                                                                     ";
$campos .= "    'Ativo'::varchar                                                         ";
$campos .= "end as dl_Situaчуo                                                         ";
