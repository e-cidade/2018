<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

/**
 *  Configuraчуo do Programa de Conversуo
 */
 $ConfigINI = parse_ini_file("db_config.ini");

/**
 *  Configuraчуo base Prefeitura
 */
 $ConfigConexaoPrefeitura["host"]     = $ConfigINI["ConexaoOrigem_host"];
 $ConfigConexaoPrefeitura["port"]     = $ConfigINI["ConexaoOrigem_port"];
 $ConfigConexaoPrefeitura["dbname"]   = $ConfigINI["ConexaoOrigem_dbname"];
 $ConfigConexaoPrefeitura["user"]     = $ConfigINI["ConexaoOrigem_user"];
 $ConfigConexaoPrefeitura["password"] = $ConfigINI["ConexaoOrigem_password"];

/**
 *  Configuraчуo base Destino
 */
 $ConfigConexaoDestino["host"]        = $ConfigINI["ConexaoDestino_host"];
 $ConfigConexaoDestino["port"]        = $ConfigINI["ConexaoDestino_port"];
 $ConfigConexaoDestino["dbname"]      = $ConfigINI["ConexaoDestino_dbname"];
 $ConfigConexaoDestino["user"]        = $ConfigINI["ConexaoDestino_user"];
 $ConfigConexaoDestino["password"]    = $ConfigINI["ConexaoDestino_password"];

/**
 *  Conexуo com base de destino
 */
 $sDataSourceDestino = "host={$ConfigConexaoDestino["host"]}
 	                     dbname={$ConfigConexaoDestino["dbname"]}
	                     port={$ConfigConexaoDestino["port"]}
	                     user={$ConfigConexaoDestino["user"]}
	                     password={$ConfigConexaoDestino["password"]}";

 $sDataSourceDestinoLog  = " host = {$ConfigConexaoDestino["host"]} ";
 $sDataSourceDestinoLog .= " port = {$ConfigConexaoDestino["port"]} ";
 $sDataSourceDestinoLog .= " dbname = {$ConfigConexaoDestino["dbname"]} ";

 db_log("- Base para Integracao Destino:$sDataSourceDestinoLog", $sArquivoLog,$iParamLog);

 if (! ($connDestino = pg_connect($sDataSourceDestino))) {

   db_log("Erro ao conectar no Destino... ($sDataSourceDestinoLog)", $sArquivoLog,$iParamLog);
   die();
 }

/**
 *  Conexуo com base de dados da Prefeitura
 */
 $sDataSourceOrigem = "host={$ConfigConexaoPrefeitura["host"]}
                       dbname={$ConfigConexaoPrefeitura["dbname"]}
                       port={$ConfigConexaoPrefeitura["port"]}
                       user={$ConfigConexaoPrefeitura["user"]}
                       password={$ConfigConexaoPrefeitura["password"]}";

 $sDataSourceOrigemLog  = "  host = {$ConfigConexaoPrefeitura["host"]} ";
 $sDataSourceOrigemLog .= " port = {$ConfigConexaoPrefeitura["port"]} ";
 $sDataSourceOrigemLog .= " dbname = {$ConfigConexaoPrefeitura["dbname"]} ";

 db_log("- Base para Integracao Origem:$sDataSourceOrigemLog", $sArquivoLog, $iParamLog);

 if (! ($connOrigem = @pg_connect($sDataSourceOrigem))) {

   db_log("Erro ao conectar no DBPortal ($sDataSourceOrigemLog)...", $sArquivoLog, $iParamLog);
   die();
 }

 db_log("", $sArquivoLog, $iParamLog);