# e-Cidade e e-CidadeOnline
## Sobre o repositório

Última versão, disponibilizada no portal [Software Público](https://softwarepublico.gov.br/social/e-cidade/) em 2018.
## Instalação versão 2018

O arquivo disponível no Portal do Software Público Brasileiro para download, fornecido pelo atual mantenedor [DB Seller](https://dbseller.com), contém arquivos SQL muito grandes que não são aconselháveis de serem versionados em um único repositório. Sendo assim, para executar o container PostgreSQL com os dados iniciais, é necessário o download adicional dos arquivos abaixo para serem incluídos no diretório _/2018/sql_.

1. [Schema](https://github.com/e-cidade/e-cidade-database/raw/main/e-cidade-2018-2-schema.sql)
2. [Demo Data](https://github.com/e-cidade/e-cidade-database/raw/main/e-cidade-2018-2-demo.sql)

Já na pasta raiz do projeto, para salvar os arquivos SQL diretamente, use os comandos abaixo:

1. Schema
    ```bash
    wget -O 2018/sql/e-cidade-2018-2-schema.sql https://github.com/e-cidade/e-cidade-database/raw/main/e-cidade-2018-2-schema.sql
    ```
2. Demo Data
    ```bash
    wget -O 2018/sql/e-cidade-2018-2-schema.sql https://github.com/e-cidade/e-cidade-database/raw/main/e-cidade-2018-2-demo.sql
    ```
