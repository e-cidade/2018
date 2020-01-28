<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

$campos = "turma.ed57_i_codigo,
           turma.ed57_c_descr,
           case
            when ed57_c_regime = 'S�RIE'
             then serie.ed11_c_descr else
                  'MPD'
           end as ed57_i_serie,
           calendario.ed52_c_descr as ed57_i_calendario,
           cursoedu.ed29_c_descr as ed31_i_curso,
           turno.ed15_c_nome as ed57_i_turno,
           sala.ed16_c_descr as ed57_i_sala,
           turma.ed57_i_numvagas,
           turma.ed57_i_nummatr,
           formaavaliacao.ed37_c_descr as dl_Avalia��o
          ";
?>