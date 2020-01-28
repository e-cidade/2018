#!/bin/sh

erro=0
testaerro() {
  TPUT=/usr/bin/tput
  RED=`$TPUT setaf 1`
  GREEN=`$TPUT setaf 2`
  NORMAL=`$TPUT op`

  if [ "$?" != "0" ]
  then
    erro=1
    echo "\r\t\t\t\t\t\t\t\t\t\t\t\t\t[${RED}Erro${NORMAL}]"

  else
    echo "\r\t\t\t\t\t\t\t\t\t\t\t\t\t[${GREEN} Ok ${NORMAL}]"
    erro=0
  fi
}

# Carrega Configuracoes de acesso a base de dados
[ -r ./lib/db_config.ini ] || exit 0
. ./lib/db_config.ini

tDataHora=`date +%Y%m%d_%H%M%S`
sPathTmpDir="dump/tmp/"
sPathCopiadosDir="dump/enviados/"
sPathCopiandoDir="dump/enviando/"
sPathRecebendoDir="dump/recebendo/"
sPathRecebidos="dump/recebidos/"
sPathProcessados="dump/processados/"

# Variaveis de acesso ao banco de dados
sDbHost=${ConfigConexaoPrefeitura_host}
sBaseName=${ConfigConexaoPrefeitura_dbname}
sDbUser=${ConfigConexaoPrefeitura_user}
sPorta=${ConfigConexaoPrefeitura_port}

# Variaveis Para geracao do schema dos dados
sArqXML="xml/schema.dbpref.xml"
sNameArq="log/log_erro_${tDataHora}.log"

sDbConnDesativado="../../libs/db_conn_desativado.php"
sDbConn="../../libs/db_conn.php"
sDbConnBKP="../../libs/db_conn.bkp.php"

# verificando arquivo
sArquivo=`ls -t ${sPathRecebidos}*.bz2 | tail -n1`
testaerro

echo ${sArquivo}

sArqSchema=`basename ${sArquivo}`
testaerro

if [ "${sArquivo}x" != "x" ] 
then

  # guardando o db_conn.php original 
  cp ${sDbConn} ${sDbConnBKP}

  # Desativando o dbpref
  cp ${sDbConnDesativado} ${sDbConn}

  cd ${sPathRecebidos}

  sMd5=`md5sum ${sArqSchema}`
  testaerro
  sMd5Novo=`cat ${sArqSchema}.md5`
  testaerro
  if [ "${sMd5}" = "${sMd5Novo}" ]
  then
    bunzip2 -c ${sArqSchema} | psql -U ${sDbUser} -h ${sDbHost} ${sBaseName} 2> ../../${sNameArq}
    testaerro

    cd ../../
    sSchemaName=`echo ${sArqSchema} | cut -d'.' -f1`
     
    php -q geraBaseDBpref.php gerar ${sArqXML} ${sSchemaName}    
    testaerro

    mv ${sPathRecebidos}${sArqSchema} ${sPathProcessados}
    testaerro
    mv "${sPathRecebidos}${sArqSchema}.md5" ${sPathProcessados}
    testaerro

  else
    echo "Arquivo Imcompleto ou Corrompido"
  fi

# Ativando o dbpref  
  cp ${sDbConnBKP} ${sDbConn}

fi