# Sobre o template
O template visa formatar os dados de acordo com o esperado pela API.

O Formatter no e-cidade vai criar um objeto com base na formatação do template e somente os dados nele contidos.

Sempre a informação (index) da esquerda na estrutura do template, deve ser como esta no e-cidade. Por default, é a mesma informação enviada para API, mais pode ser alterada pelo parâmetro/index *'nome_api'*



## Estrutura padrão do template

* O primeiro nível do array corresponde a um grupo de dados no e-cidade. E deve ser um array;
* Se dentro do array contiver o index **'nome_api'**, seu valor é o nome do grupo criado no caso do exemplo abaixo o grupo de dados ideEstab (no e-cidade) será enviado para API como ideEstab_na_API.
Se não o nome do grupo enviado para a API é o mesmo presente no e-cidade
* Todas propriedade do grupo deve estar dentro do index **'properties'**. E também podemos reescrever o nome enviado para api ou informar o tipo do dado. Ver os exemplos abaixo.

```php
array(
    'ideEstab' => array(
        'nome_api' => 'ideEstab_na_API',
        'properties' => array(
            'iniValid', // iniValid é o nome na API
            'fimValid' => 'fimValid_na_API'
            'tpInsc' => array(
                'nome_api'=> 'tpInsc_na_API',
                'type' => 'int'
            ),
            'nrInsc' => array(
                'type' => 'float'
            ),
        )
    ),
```

## Grupos com Subgrupos
Dentro de um grupo de dados podemos informar a presença de outro grupo de dados. A estrutura do subgrupo deve ser igual a do grupo (primeiro nível)  e estar presente dentro do array **'groups'**

```php
array(
    'dadosEstab' => array(
        'nome_api' => 'dadosEstab',
        'properties' => array(
            'cnaePrep' => 'cnaePrep'
        ),
        'groups' => array (
            'aliqGilrat' => array(
                'nome_api' => 'aliqGilrat',
                'properties' => array(
                    'aliqRat' => array(
                        'nome_api'=>'aliqRat',
                        'type' => 'integer'
                    ),
                ),
                'groups' =>array (
                    'procAdmJudRat' => array (
                        'properties' => array(
                            'tpProc' => array(
                                'nome_api'=> 'tpProc',
                                'type' => 'int'
                            ),
                            'nrProc' => 'nrProc',
                            'codSusp' => 'codSuspo'
                        )
                    ),
                )
            )
        )
    )
),
```

## Quando o grupo é um array
Todo grupo de dados é considerado um objeto por default, é possivel declara-lo como um array informando o index **'type' => 'array'**.
Se o tipo do grupo de dados é um array, é preciso informar o index **'items'** e dentro dele devemos ter a mesma estrutura das propriedades de um grupo já visto acima.


```php
array(
    'infoEntEduc' => array (
        'type' => 'array',
        'nome_api' => 'infoEntEduc',
        'items' => array(
            'properties' => array(
                'nrInsc' => array(
                    'nome_api'=>  'nrInsc',
                    'type' => 'int'

                ),
            )
        )
    ),
)
```