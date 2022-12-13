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

$campos = 'distinct contabancaria.db83_sequencial, 
                    contabancaria.db83_descricao, 
                    bancoagencia.db89_codagencia,
                    bancoagencia.db89_digito,
                    contabancaria.db83_conta,
                    contabancaria.db83_dvconta,
                    contabancaria.db83_identificador,
                    contabancaria.db83_codigooperacao,
                    contabancaria.db83_tipoconta,
                    case when contabancaria.db83_tipoconta = 1 then \'Conta Corrente\'
                         when contabancaria.db83_tipoconta = 2 then \'Conta Poupança\'
                         when contabancaria.db83_tipoconta = 3 then \'Conta Aplicação\'
                         when contabancaria.db83_tipoconta = 4 then \'Conta Salário\'
                    end as "dl_Descricao Tipo de Conta"
               ';

?>