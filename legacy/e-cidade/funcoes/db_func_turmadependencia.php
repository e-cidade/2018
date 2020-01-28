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

  $sCampos  = " turma.ed57_i_codigo, ";
  $sCampos .= " turma.ed57_i_codigoinep, ";
  $sCampos .= " turma.ed57_c_descr, ";
  $sCampos .= " fc_nomeetapaturma(ed57_i_codigo) AS ed57_i_serie, ";
  $sCampos .= " calendario.ed52_c_descr AS ed57_i_calendario, ";
  $sCampos .= " cursoedu.ed29_c_descr AS ed31_i_curso, ";
  $sCampos .= " turno.ed15_c_nome AS ed57_i_turno, ";
  $sCampos .= " sala.ed16_c_descr AS ed57_i_sala ";
?>