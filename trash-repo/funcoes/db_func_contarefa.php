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

	$campos = "tarefa.at40_sequencial,tarefa.at40_progresso,case tarefa.at40_prioridade when 1 then 'Baixa' when 2 then 'Mщdia' when 3 then 'Alta' end as at40_prioridade,tarefa.at40_descr||'/'||db_proced.at30_descr as dl_Tarefa,tarefa.at40_diaini,tarefa.at40_previsao||'/'||tarefa.at40_tipoprevisao as dl_Duraчуo,tarefa.at40_diafim,(tarefa.at40_diafim::date - '".date("Y-m-d",db_getsession("DB_datausu"))."'::date) as dl_Pendente,tarefaenvol.at45_perc as dl_Envolvimento,clientes.at01_nomecli as nome_cliente,tarefa_lanc.at36_usuario as db_usulanc,tarefa_lanc.at36_tarefa as db_tarefa";
?>