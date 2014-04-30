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

/*
 *  funções anexaas do relatorio de recurso orçamentário
 *  na contabilidade 
 * */
function sql_saldo_bancario($anousu="",$data_limite="",$db_selinstit="1",$where_recurso=""){
    $sql = "   select k13_conta, 
								          k13_descr, 
								          c61_codigo,
								          fc_saltessaldo(k13_conta,
								                                 '$data_limite',
								                                 '$data_limite',
								                                  null,
								                                  conplanoreduz.c61_instit)   as valor
								from saltes 
										    inner join conplanoexe on c62_anousu = ".$anousu."
								                                               and c62_reduz = k13_conta
								            inner join conplanoreduz on c62_reduz = c61_reduz and
                       																	 c62_anousu = c61_anousu and 	 
            																			 c61_instit in  (".str_replace('-', ', ', $db_selinstit).")
                                                                                         $where_recurso
										    inner join conplano on c61_codcon = c60_codcon and c61_anousu=c60_anousu
										    inner join orctiporec on o15_codigo = c61_codigo
								            inner join conplanoconta on c63_codcon = conplano.c60_codcon and c63_anousu=c60_anousu
								            inner join db_bancos on trim(db90_codban) = conplanoconta.c63_banco::varchar(10)  
							  where c60_codsis in (5,6)                          
							  order by k13_descr								                      
		              ";
	return $sql;							              
}
?>