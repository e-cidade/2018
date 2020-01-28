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

$campos  = " cursosocial.as19_sequencial, "; 
$campos .= " cursosocial.as19_nome, "; 
$campos .= " cursosocial.as19_detalhamento, ";
$campos .= " cursosocial.as19_tabcurritipo as \"dl_Código Tipo\", ";
$campos .= " tabcurritipo.h02_descr as \"dl_Tipo de Curso\", ";
$campos .= " cursosocial.as19_inicio, ";
$campos .= " cursosocial.as19_fim, ";
$campos .= " cursosocial.as19_horaaulasdia, ";
$campos .= " cursosocial.as19_ministrante, "; 
$campos .= " m.z01_nome as dl_Ministrante, ";
$campos .= " cursosocial.as19_responsavel, ";
$campos .= " r.z01_nome as dl_Responsável";
?>