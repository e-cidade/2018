# Sobre o template
O template visa formatar os dados de acordo com o esperado pela API.

O Formatter no e-cidade vai criar um objeto com base na formata��o do template e somente os dados nele contidos.

Sempre a informa��o (index) da esquerda na estrutura do template, deve ser como esta no e-cidade. Por default, � a mesma informa��o enviada para API, mais pode ser alterada pelo par�metro/index *'nome_api'*



## Estrutura padr�o do template

* O primeiro n�vel do array corresponde a um grupo de dados no e-cidade. E deve ser um array;
* Se dentro do array contiver o index **'nome_api'**, seu valor � o nome do grupo criado no caso do exemplo abaixo o grupo de dados ideEstab (no e-cidade) ser� enviado para API como ideEstab_na_API.
Se n�o o nome do grupo enviado para a API � o mesmo presente no e-cidade
* Todas propriedade do grupo deve estar dentro do index **'properties'**. E tamb�m podemos reescrever o nome enviado para api ou informar o tipo do dado. Ver os exemplos abaixo.

```php
array(
    'ideEstab' => array(
        'nome_api' => 'ideEstab_na_API',
        'properties' => array(
            'iniValid', // iniValid � o nome na API
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
Dentro de um grupo de dados podemos informar a presen�a de outro grupo de dados. A estrutura do subgrupo deve ser igual a do grupo (primeiro n�vel)  e estar presente dentro do array **'groups'**

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

## Quando o grupo � um array
Todo grupo de dados � considerado um objeto por default, � possivel declara-lo como um array informando o index **'type' => 'array'**.
Se o tipo do grupo de dados � um array, � preciso informar o index **'items'** e dentro dele devemos ter a mesma estrutura das propriedades de um grupo j� visto acima.


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