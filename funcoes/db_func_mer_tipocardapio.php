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

$campos  =  " mer_tipocardapio.me27_i_codigo,
              mer_tipocardapio.me27_c_nome,
              mer_tipocardapio.me27_c_ativo,
              round(mer_tipocardapio.me27_f_versao,2) as me27_f_versao,
              (select count(*) from matricula
               inner join turma on ed57_i_codigo = ed60_i_turma
               inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
                                        and ed221_c_origem = 'S'  
               inner join calendario on ed52_i_codigo = ed57_i_calendario
               inner join mer_cardapioescola on me32_i_escola = ed57_i_escola
                                            and me32_i_tipocardapio = me27_i_codigo
                inner join mer_tpcardapioturma on me28_i_cardapioescola = me32_i_codigo
                                              and me28_i_serie = ed221_i_serie
                                           
               where ed60_c_situacao = 'MATRICULADO'
               and me27_i_ano = ed52_i_ano) as dl_alunos
              ";
?>