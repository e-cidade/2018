#!/bin/bash

#
# Parametros
#  $1 = Id do ultimo "configuracao.db_auditoria_migracao.sequencial" 
#       apos a implantacao da auditoria
#  $2 = Quantidade de Lotes de Migracao a Processar (default = 1)
#  $3 = Excluir registros da db_acount apos migracao 1=SIM e 0=NAO (default = 0)
#

PID_FILE=/tmp/auditoria_migracao_cursor_old.pid

if [ -f $PID_FILE ]; then
	echo "ERRO: script já rodando com o pid $(cat $PID_FILE)"
	exit 1
fi

trap "rm -f -- '$PID_FILE'" EXIT INT KILL TERM
echo $$ > $PID_FILE

php -q auditoria_migracao_cursor.php "$1" $2 $3

