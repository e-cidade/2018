<?php

use Classes\PostgresMigration;

class M9756FormularioRubricas extends PostgresMigration
{
    public function change()
    {
        $this->execute(<<<SQL
update avaliacaogrupopergunta set db102_identificadorcampo = 'dadosRubrica' where db102_sequencial = 3000091;
update avaliacaopergunta set db103_identificadorcampo = 'codRubr' where db103_sequencial = 3000420;
update avaliacaopergunta set db103_identificadorcampo = 'ideTabRubr' where db103_sequencial = 3000421;
update avaliacaopergunta set db103_identificadorcampo = 'iniValid' where db103_sequencial = 3000422;
update avaliacaopergunta set db103_identificadorcampo = 'fimValid' where db103_sequencial = 3000423;
update avaliacaopergunta set db103_identificadorcampo = 'dscRubr' where db103_sequencial = 3000424;
update avaliacaopergunta set db103_identificadorcampo = 'natRubr' where db103_sequencial = 3000425;
update avaliacaopergunta set db103_identificadorcampo = 'tpRubr' where db103_sequencial = 3000426;
update avaliacaopergunta set db103_identificadorcampo = 'codIncCP' where db103_sequencial = 3000427;
update avaliacaopergunta set db103_identificadorcampo = 'codIncIRRF' where db103_sequencial = 3000428;
update avaliacaopergunta set db103_identificadorcampo = 'codIncFGTS' where db103_sequencial = 3000429;
update avaliacaopergunta set db103_identificadorcampo = 'codIncSIND' where db103_sequencial = 3000430;
update avaliacaopergunta set db103_identificadorcampo = 'repDSR' where db103_sequencial = 3000431;
update avaliacaopergunta set db103_identificadorcampo = 'rep13' where db103_sequencial = 3000432;
update avaliacaopergunta set db103_identificadorcampo = 'repFerias' where db103_sequencial = 3000433;
update avaliacaopergunta set db103_identificadorcampo = 'repAviso' where db103_sequencial = 3000435;
update avaliacaopergunta set db103_identificadorcampo = 'observacao' where db103_sequencial = 3000436;
update avaliacaogrupopergunta set db102_identificadorcampo = 'ideProcessoCP' where db102_sequencial = 3000093;
update avaliacaopergunta set db103_identificadorcampo = 'tpProc' where db103_sequencial = 3000437;
update avaliacaopergunta set db103_identificadorcampo = 'nrProc' where db103_sequencial = 3000438;
update avaliacaopergunta set db103_identificadorcampo = 'extDecisao' where db103_sequencial = 3000439;
update avaliacaopergunta set db103_identificadorcampo = 'codSusp' where db103_sequencial = 3000440;
update avaliacaogrupopergunta set db102_identificadorcampo = 'ideProcessoIRRF' where db102_sequencial = 3000094;
update avaliacaopergunta set db103_identificadorcampo = 'nrProc' where db103_sequencial = 3000441;
update avaliacaopergunta set db103_identificadorcampo = 'codSusp' where db103_sequencial = 3000442;
update avaliacaogrupopergunta set db102_identificadorcampo = 'ideProcessoFGTS' where db102_sequencial = 3000095;
update avaliacaopergunta set db103_identificadorcampo = 'nrProc' where db103_sequencial = 3000443;
update avaliacaopergunta set db103_identificadorcampo = 'codSusp' where db103_sequencial = 3000444;
update avaliacaogrupopergunta set db102_identificadorcampo = 'ideProcessoSIND' where db102_sequencial = 3000096;
update avaliacaopergunta set db103_identificadorcampo = 'nrProc' where db103_sequencial = 3000445;
update avaliacaopergunta set db103_identificadorcampo = 'codSusp' where db103_sequencial = 3000446;
SQL
        );
    }

    public function down()
    {
        // dow M9756EnvioEsocialApi remove coluna alterada
    }
}
