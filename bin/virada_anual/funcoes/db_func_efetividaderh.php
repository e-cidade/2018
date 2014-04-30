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

$campos = "efetividaderh.ed98_i_codigo,
           escola.ed18_c_nome as dl_escola,
           case
            when ed98_i_mes = 1
             then 'JANEIRO'
            when ed98_i_mes = 2
             then 'FEVEREIRO'
            when ed98_i_mes = 3
             then 'MARÇO'
            when ed98_i_mes = 4
             then 'ABRIL'
            when ed98_i_mes = 5
             then 'MAIO'
            when ed98_i_mes = 6
             then 'JUNHO'
            when ed98_i_mes = 7
             then 'JULHO'
            when ed98_i_mes = 8
             then 'AGOSTO'
            when ed98_i_mes = 9
             then 'SETEMBRO'
            when ed98_i_mes = 10
             then 'OUTUBRO'
            when ed98_i_mes = 11
             then 'NOVEMBRO'
            else
             'DEZEMBRO'
           end as dl_mes,
           efetividaderh.ed98_i_ano,
           case when ed98_c_tipo = 'F'
            then 'FUNCIONÁRIOS'
            else 'PROFESSORES'
           end as ed98_c_tipo,
           efetividaderh.ed98_i_mes
          ";
?>