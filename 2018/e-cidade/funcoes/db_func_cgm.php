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

$campos = "cgm.z01_numcgm,
           cgm.z01_nome,
           cgm.z01_ender,
           cgm.z01_numero,
           cgm.z01_compl,
           cgm.z01_bairro,
           cgm.z01_munic,
           cgm.z01_uf,
           cgm.z01_cep,
           cgm.z01_cxpostal as db_z01_cxpostal,
           cgm.z01_cadast as db_z01_cadast,
           cgm.z01_telef as db_z01_telef,
           cgm.z01_ident as db_z01_ident,
           cgm.z01_login as db_z01_login,
           cgm.z01_incest as db_z01_incest,
           cgm.z01_telcel as db_z01_telcel,
           cgm.z01_email as db_z01_email,
           cgm.z01_endcon as db_z01_endcon,
           cgm.z01_numcon as db_z01_numcon,
           cgm.z01_comcon as db_z01_comcon,
           cgm.z01_baicon as db_z01_baicon,
           cgm.z01_muncon as db_z01_muncon,
           cgm.z01_ufcon as db_z01_ufcon,
           cgm.z01_cepcon as db_z01_cepcon,
           cgm.z01_cxposcon as db_z01_cxposcon,
           cgm.z01_telcon as db_z01_telcon,
           cgm.z01_celcon as db_z01_celcon,
           cgm.z01_emailc as db_z01_emailc,
           cgm.z01_nacion as db_z01_nacion,
           cgm.z01_estciv as db_z01_estciv,
           cgm.z01_profis as db_z01_profis,
           cgm.z01_tipcre as db_z01_tipcre,
           cgm.z01_cgccpf as db_z01_cgccpf,
           cgm.z01_mae,
           cgm.z01_pai";
?>