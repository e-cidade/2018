#!/bin/bash

# Verifica se o arquivo de configuração existe
if [[ ! -f "lib/debitos.conf" ]]; then
  echo "[ERRO] [4] Diretório ou arquivo não existe: lib/debitos.conf"
  exit 4
else
  # Se existe, carrega as configurações
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

# Verifica se a geração da tabela débitos foi habilitada
if [[ "${DEBITOS_PERIODO}" != "-1" ]]; then
  # Verifica se é o último dia do mês
  if [ $AMANHA -lt $HOJE ]; then
    # Sempre executa no último dia do mês
    _debitos
  elif [[ "${DEBITOS_PERIODO}" = "0" ]]; then
    # Executa todos os dias da semana
    _debitos
  elif [[ "${DEBITOS_PERIODO}" = "${DIA_DA_SEMANA}" ]]; then
    # Executa no dia da semana definido
    _debitos
  else
    echo "[INFO] [ ] Rotina de geração da débitos desabilitada ou não foi configurada corretamente."
  fi
else
  echo "[INFO] [ ] Rotina de geração da débitos desabilitada ou não foi configurada corretamente."
fi
