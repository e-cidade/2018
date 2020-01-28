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

$campos = "distinct  

           rhpessoal.rh01_regist,
           rhpessoal.rh01_numcgm,
           cgm.z01_nome,
	   rh01_admiss,
	   rh05_recis,
	   rh37_descr
          ";
//$campos = "rhpessoal.rh01_regist,rhpessoal.rh01_numcgm,rhpessoal.rh01_funcao,rhpessoalmov.rh02_lota,rhpessoal.rh01_admiss,rhpessoal.rh01_nasc,rhpessoal.rh01_nacion,rhpessoal.rh01_anoche,rhpessoal.rh01_instru,rhpessoal.rh01_sexo,rhpessoal.rh01_estciv,rhpessoal.rh01_tipadm,rhpessoal.rh01_natura,rhpessoal.rh01_tpcont,rhpessoal.rh01_raca,rhpessoal.rh01_clas1,rhpessoal.rh01_clas2,rhpessoal.rh01_trienio,rhpessoal.rh01_progres";
?>