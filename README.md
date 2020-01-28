e-Cidade e e-CidadeOnline
======
Sobre o REPO _(2018/2)_
------
O e-cidade é um projeto baseado em um fork de 2014, atualizado para a última versão disponível no portal [Software Público](https://softwarepublico.gov.br/social/e-cidade/).

Do arquivo disponível para download, disponibilizado pelo atual mantenedor [DB Seller](https://dbseller.com), contém arquivos SQL muito grandes para serem versionados pelo GITHUB. Sendo assim, para executar o container PostgreSQL com os dados iniciais, é necessário o download adicional dos arquivos abaixo para serem incluídos no diretório _/legacy/sql_.

1. [Schema](https://www.dropbox.com/sh/k05kmu07zulddk8/AAA_KY-dAxB3boQeZxvtXgE5a/e-cidade-2018-2-schema.sql?dl=0)
2. [Demo Data](https://www.dropbox.com/sh/k05kmu07zulddk8/AABP7ztrLifetWaHnlyOfgvza/e-cidade-2018-2-demo.sql?dl=0)

Já na pasta raiz do projeto, para salvar os arquivos SQL diretamente, use os comandos abaixo:

1. Schema
    - wget -O legacy/sql/e-cidade-2018-2-schema.sql https://www.dropbox.com/sh/k05kmu07zulddk8/AAA_KY-dAxB3boQeZxvtXgE5a/e-cidade-2018-2-schema.sql?dl=0
2. Demo Data
    - wget -O legacy/sql/e-cidade-2018-2-schema.sql https://www.dropbox.com/sh/k05kmu07zulddk8/AABP7ztrLifetWaHnlyOfgvza/e-cidade-2018-2-demo.sql?dl=0
