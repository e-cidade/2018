<?php

use Classes\PostgresMigration;

/**
 * Correção das posições dos campos utilizados no layout 280 (Emissão de Tarifas / módulo Água)
 */
class M8268CorrecaoLayoutEmissaoTarifas extends PostgresMigration
{
    public function up()
    {
      $this->execute("update db_layoutcampos set db52_posicao = 3393 + 5 where db52_codigo = 15597");
      $this->execute("update db_layoutcampos set db52_posicao = 3437 + 5 where db52_codigo = 15598");
      $this->execute("update db_layoutcampos set db52_posicao = 3447 + 5 where db52_codigo = 15599");
      $this->execute("update db_layoutcampos set db52_posicao = 3462 + 5 where db52_codigo = 15600");
      $this->execute("update db_layoutcampos set db52_posicao = 3562 + 5 where db52_codigo = 15601");
      $this->execute("update db_layoutcampos set db52_posicao = 4062 + 5 where db52_codigo = 15602");
      $this->execute("update db_layoutcampos set db52_posicao = 4562 + 5 where db52_codigo = 15603");
      $this->execute("update db_layoutcampos set db52_posicao = 4707 + 5 where db52_codigo = 15604");
      $this->execute("update db_layoutcampos set db52_posicao = 4721 + 5 where db52_codigo = 15605");
      $this->execute("update db_layoutcampos set db52_posicao = 4741 + 5 where db52_codigo = 15606");
      $this->execute("update db_layoutcampos set db52_posicao = 4751 + 5 where db52_codigo = 15607");
      $this->execute("update db_layoutcampos set db52_posicao = 4765 + 5 where db52_codigo = 15608");
    }

    public function down()
    {
      $this->execute("update db_layoutcampos set db52_posicao = 3393 where db52_codigo = 15597");
      $this->execute("update db_layoutcampos set db52_posicao = 3437 where db52_codigo = 15598");
      $this->execute("update db_layoutcampos set db52_posicao = 3447 where db52_codigo = 15599");
      $this->execute("update db_layoutcampos set db52_posicao = 3462 where db52_codigo = 15600");
      $this->execute("update db_layoutcampos set db52_posicao = 3562 where db52_codigo = 15601");
      $this->execute("update db_layoutcampos set db52_posicao = 4062 where db52_codigo = 15602");
      $this->execute("update db_layoutcampos set db52_posicao = 4562 where db52_codigo = 15603");
      $this->execute("update db_layoutcampos set db52_posicao = 4707 where db52_codigo = 15604");
      $this->execute("update db_layoutcampos set db52_posicao = 4721 where db52_codigo = 15605");
      $this->execute("update db_layoutcampos set db52_posicao = 4741 where db52_codigo = 15606");
      $this->execute("update db_layoutcampos set db52_posicao = 4751 where db52_codigo = 15607");
      $this->execute("update db_layoutcampos set db52_posicao = 4765 where db52_codigo = 15608");
    }
}
