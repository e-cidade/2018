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

# criar o esquema com os dados para gerar arquivo para o dbportal2
# php5 -q geraBaseDBpref.php schema xml/schema.dbpref.xml dbpref_off

# Carrega Configuracoes de acesso a base de dados
[ -r ./lib/db_config.ini ] || exit 0
. ./lib/db_config.ini

export PGPASSWORD=${ConfigConexaoPrefeitura_password}

tDataHora=`date +%Y%m%d_%H%M%S`
sPathTmpDir="dump/tmp/"
sPathCopiadosDir="dump/enviados/"
sPathCopiandoDir="dump/enviando/"
sPathRecebendoDi="dump/recebendo/"
sPathRecebidos="dump/recebidos/"

# Variaveis de acesso ao banco de dados
sDbHost=${ConfigConexaoPrefeitura_host}
sBaseName=${ConfigConexaoPrefeitura_dbname}
sDbUser=${ConfigConexaoPrefeitura_user}
sPorta=${ConfigConexaoPrefeitura_port}

# Variaveis Para geracao do schema dos dados
sArqXML="xml/schema.dbportal.xml"
sSchemaName="dbportal_${tDataHora}"
sFileDumpName="dbportal_${tDataHora}.sql.bz2"
sCmdpg_dump='pg_dump'
sNameArq="log/log_erro_${tDataHora}.log"

# Gerar schema
echo -n "Gerando schema[${sSchemaName}]. " >> ${sNameArq}
echo -n "Gerando schema[${sSchemaName}]. "
php -q geraBaseDBpref.php schema $sArqXML $sSchemaName 2>> $sNameArq
testaerro

# Gerar dump do schema para mandar para o dbpref(datacenter)
echo -n "Gerando dump do schema[${sSchemaName}]. " >> ${sNameArq}
echo -n "Gerando dump do schema[${sSchemaName}]. "
$sCmdpg_dump -p ${sPorta} -U ${sDbUser} -h ${sDbHost} ${sBaseName} -n ${sSchemaName} | bzip2 > ${sPathTmpDir}${sFileDumpName} 2>> ${sNameArq}
testaerro

if [ "${erro}" = "1" ] 
then 
  echo "Erro ao gerar dump dos dados do schema ${sSchemaName}. " >> ${sNameArq}
  rm -f ${sPathTmpDir}${sFileDumpName} 2> /dev/null
  exit
fi


#psql -p ${sPorta} -U ${sDbUser} -h ${sDbHost} ${sBaseName} -c "ALTER TABLE db_reciboweb disable trigger all;" 2>> ${sNameArq}
#psql -p ${sPorta} -U ${sDbUser} -h ${sDbHost} ${sBaseName} -c "ALTER TABLE recibopaga   disable trigger all;" 2>> ${sNameArq}
#psql -p ${sPorta} -U ${sDbUser} -h ${sDbHost} ${sBaseName} -c "ALTER TABLE recibocodbar disable trigger all;" 2>> ${sNameArq}

psql -p ${sPorta} -U ${sDbUser} -h ${sDbHost} ${sBaseName} -c "SELECT fc_startsession(); TRUNCATE db_reciboweb, recibopaga, recibocodbar, recibopagahist;" 2>> ${sNameArq}

#psql -p ${sPorta} -U ${sDbUser} -h ${sDbHost} ${sBaseName} -c "ALTER TABLE db_reciboweb enable trigger all;" 2>> ${sNameArq}
#psql -p ${sPorta} -U ${sDbUser} -h ${sDbHost} ${sBaseName} -c "ALTER TABLE recibopaga   enable trigger all;" 2>> ${sNameArq}
#psql -p ${sPorta} -U ${sDbUser} -h ${sDbHost} ${sBaseName} -c "ALTER TABLE recibocodbar enable trigger all;" 2>> ${sNameArq}


cd ${sPathTmpDir}

md5sum ${sFileDumpName} > "${sFileDumpName}.md5"
testaerro

mv ${sFileDumpName} ../../${sPathCopiandoDir}
mv "${sFileDumpName}.md5" ../../${sPathCopiandoDir}
cd ..