<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

$sess = 0;
if(!session_is_registered("DB_modulo"))
  $sess = 1;
if(!session_is_registered("DB_nome_modulo"))
  $sess = 1;
if(!session_is_registered("DB_anousu"))
  $sess = 1;
if(!session_is_registered("DB_instit"))
  $sess = 1;
if(!session_is_registered("DB_uol_hora"))
  $sess = 1;
if($sess == 1) {
  session_destroy();\ECidade\V3\Extension\Registry::get('app.response')->redirect('db_erros.php?fechar=true&db_erro='.urlencode("Sessï¿½o invï¿½lida."));
  echo "Sessão Inválida!(14)<br>Feche seu navegador e faça login novamente.<Br>\n";
  exit;
}
