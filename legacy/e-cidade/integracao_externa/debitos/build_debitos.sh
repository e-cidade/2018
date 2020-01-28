#!/bin/bash

# Verifica se o arquivo de configura��o existe
if [[ ! -f "lib/debitos.conf" ]]; then
  echo "[ERRO] [4] Diret�rio ou arquivo n�o existe: lib/debitos.conf"
  exit 4
else
  # Se existe, carrega as configura��es
  source lib/debitos.conf
fi

DATA_ATUAL="$(date +%Y-%m-%d)"
DIA_DA_SEMANA="$(date +%u)"
HOJE="$(date +%d)"
AMANHA="$(date +%d -d '1 day')"

function _debitos() {
  for iInstit in $DEBITOS_INSTITUICOES; do
    echo "[INFO] [ ] Gerando debitos para data ${DATA_ATUAL}, instituicao ${iInstit}"
    /usr/bin/php -q build_debitos_thread.php ${DATA_ATUAL} ${iInstit} "1=1" ${LIMITAR_CORES_PROCESSADOR:-2} > log/build_debitos_thread.log 2>&1
  done
}

# Verifica se a gera��o da tabela d�bitos foi habilitada
if [[ "${DEBITOS_PERIODO}" != "-1" ]]; then
  # Verifica se � o �ltimo dia do m�s
  if [ $AMANHA -lt $HOJE ]; then
    # Sempre executa no �ltimo dia do m�s
    _debitos
  elif [[ "${DEBITOS_PERIODO}" = "0" ]]; then
    # Executa todos os dias da semana
    _debitos
  elif [[ "${DEBITOS_PERIODO}" = "${DIA_DA_SEMANA}" ]]; then
    # Executa no dia da semana definido
    _debitos
  else
    echo "[INFO] [ ] Rotina de gera��o da d�bitos desabilitada ou n�o foi configurada corretamente."
  fi
else
  echo "[INFO] [ ] Rotina de gera��o da d�bitos desabilitada ou n�o foi configurada corretamente."
fi
