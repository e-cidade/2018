<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//MODULO: fiscal
//CLASSE DA ENTIDADE parfiscal
class cl_parfiscal {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $y32_instit = 0;
   var $y32_tipo = 0;
   var $y32_hist = 0;
   var $y32_impdatas = 'f';
   var $y32_impcodativ = 'f';
   var $y32_impobs = 'f';
   var $y32_impobslanc = 'f';
   var $y32_modalvara = 0;
   var $y32_modaidof = 0;
   var $y32_receit = 0;
   var $y32_receitexp = 0;
   var $y32_proced = 0;
   var $y32_procedexp = 0;
   var $y32_formvist = 0;
   var $y32_sanidepto = 0;
   var $y32_sanbaixadiv = 0;
   var $y32_tipoprocpadrao = 0;
   var $y32_calcvistanosanteriores = 'f';
   var $y32_procprotbaixaauto = 0;
   var $y32_utilizacalculoporteatividade = 'f';
   var $y32_templatealvarasanitariopermanente = 0;
   var $y32_templatealvarasanitarioprovisorio = 0;
   var $y32_templateautoinfracao = 0;
   var $y32_calculavistoriamei = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 y32_instit = int4 = Código da Instituição
                 y32_tipo = int4 = Tipo de Débito
                 y32_hist = int4 = Histórico do Cálculo
                 y32_impdatas = bool = Imprime Datas do Alvará Sanitário
                 y32_impcodativ = bool = Código Atividade do Alvará Sanitário
                 y32_impobs = bool = Imprime Obs. das Atividades do Alvará Sanitário
                 y32_impobslanc = bool = Observação do Lançamento
                 y32_modalvara = int4 = Modelo Alvará
                 y32_modaidof = int4 = Modelo AIDOF
                 y32_receit = int4 = Receita
                 y32_receitexp = int4 = Receita do Lançamento Espontâneo
                 y32_proced = int4 = Procedência
                 y32_procedexp = int4 = Procedência do Lançamento Espontâneo
                 y32_formvist = int4 = Fórmula de Cálculo das Vistorias
                 y32_sanidepto = int4 = Controla Sanitário por Departamento
                 y32_sanbaixadiv = int4 = Permite Baixa de Sanitário com Dívida
                 y32_tipoprocpadrao = int4 = Tipo de Processo
                 y32_calcvistanosanteriores = bool = Calcula Vistorias para Anos Anteriores
                 y32_procprotbaixaauto = int4 = Processo Baixa de Auto de Infração
                 y32_utilizacalculoporteatividade = bool = Utiliza Cálculo por Porte/Atividade
                 y32_templatealvarasanitariopermanente = int4 = Template Padrão Alvará Sanitário Permanente
                 y32_templatealvarasanitarioprovisorio = int4 = Template Padrão Alvará Sanitário Provisório
                 y32_templateautoinfracao = int4 = Modelo do Auto
                 y32_calculavistoriamei = bool = Calcula vistorias para MEI
                 ";
   //funcao construtor da classe
   function cl_parfiscal() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("parfiscal");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->y32_instit = ($this->y32_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_instit"]:$this->y32_instit);
       $this->y32_tipo = ($this->y32_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_tipo"]:$this->y32_tipo);
       $this->y32_hist = ($this->y32_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_hist"]:$this->y32_hist);
       $this->y32_impdatas = ($this->y32_impdatas == "f"?@$GLOBALS["HTTP_POST_VARS"]["y32_impdatas"]:$this->y32_impdatas);
       $this->y32_impcodativ = ($this->y32_impcodativ == "f"?@$GLOBALS["HTTP_POST_VARS"]["y32_impcodativ"]:$this->y32_impcodativ);
       $this->y32_impobs = ($this->y32_impobs == "f"?@$GLOBALS["HTTP_POST_VARS"]["y32_impobs"]:$this->y32_impobs);
       $this->y32_impobslanc = ($this->y32_impobslanc == "f"?@$GLOBALS["HTTP_POST_VARS"]["y32_impobslanc"]:$this->y32_impobslanc);
       $this->y32_modalvara = ($this->y32_modalvara == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_modalvara"]:$this->y32_modalvara);
       $this->y32_modaidof = ($this->y32_modaidof == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_modaidof"]:$this->y32_modaidof);
       $this->y32_receit = ($this->y32_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_receit"]:$this->y32_receit);
       $this->y32_receitexp = ($this->y32_receitexp == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_receitexp"]:$this->y32_receitexp);
       $this->y32_proced = ($this->y32_proced == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_proced"]:$this->y32_proced);
       $this->y32_procedexp = ($this->y32_procedexp == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_procedexp"]:$this->y32_procedexp);
       $this->y32_formvist = ($this->y32_formvist == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_formvist"]:$this->y32_formvist);
       $this->y32_sanidepto = ($this->y32_sanidepto == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_sanidepto"]:$this->y32_sanidepto);
       $this->y32_sanbaixadiv = ($this->y32_sanbaixadiv == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_sanbaixadiv"]:$this->y32_sanbaixadiv);
       $this->y32_tipoprocpadrao = ($this->y32_tipoprocpadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_tipoprocpadrao"]:$this->y32_tipoprocpadrao);
       $this->y32_calcvistanosanteriores = ($this->y32_calcvistanosanteriores == "f"?@$GLOBALS["HTTP_POST_VARS"]["y32_calcvistanosanteriores"]:$this->y32_calcvistanosanteriores);
       $this->y32_procprotbaixaauto = ($this->y32_procprotbaixaauto == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_procprotbaixaauto"]:$this->y32_procprotbaixaauto);
       $this->y32_utilizacalculoporteatividade = ($this->y32_utilizacalculoporteatividade == "f"?@$GLOBALS["HTTP_POST_VARS"]["y32_utilizacalculoporteatividade"]:$this->y32_utilizacalculoporteatividade);
       $this->y32_templatealvarasanitariopermanente = ($this->y32_templatealvarasanitariopermanente == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_templatealvarasanitariopermanente"]:$this->y32_templatealvarasanitariopermanente);
       $this->y32_templatealvarasanitarioprovisorio = ($this->y32_templatealvarasanitarioprovisorio == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_templatealvarasanitarioprovisorio"]:$this->y32_templatealvarasanitarioprovisorio);
       $this->y32_templateautoinfracao = ($this->y32_templateautoinfracao == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_templateautoinfracao"]:$this->y32_templateautoinfracao);
       $this->y32_calculavistoriamei = ($this->y32_calculavistoriamei == "f"?@$GLOBALS["HTTP_POST_VARS"]["y32_calculavistoriamei"]:$this->y32_calculavistoriamei);
     }else{
       $this->y32_instit = ($this->y32_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["y32_instit"]:$this->y32_instit);
     }
   }
   // funcao para Inclusão
   function incluir ($y32_instit){
      $this->atualizacampos();
     if($this->y32_tipo == null ){
       $this->erro_sql = " Campo Tipo de Débito não informado.";
       $this->erro_campo = "y32_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_hist == null ){
       $this->erro_sql = " Campo Histórico do Cálculo não informado.";
       $this->erro_campo = "y32_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_impdatas == null ){
       $this->erro_sql = " Campo Imprime Datas do Alvará Sanitário não informado.";
       $this->erro_campo = "y32_impdatas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_impcodativ == null ){
       $this->erro_sql = " Campo Código Atividade do Alvará Sanitário não informado.";
       $this->erro_campo = "y32_impcodativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_impobs == null ){
       $this->erro_sql = " Campo Imprime Obs. das Atividades do Alvará Sanitário não informado.";
       $this->erro_campo = "y32_impobs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_impobslanc == null ){
       $this->erro_sql = " Campo Observação do Lançamento não informado.";
       $this->erro_campo = "y32_impobslanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_modalvara == null ){
       $this->erro_sql = " Campo Modelo Alvará não informado.";
       $this->erro_campo = "y32_modalvara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_modaidof == null ){
       $this->erro_sql = " Campo Modelo AIDOF não informado.";
       $this->erro_campo = "y32_modaidof";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_receit == null ){
       $this->erro_sql = " Campo Receita não informado.";
       $this->erro_campo = "y32_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_receitexp == null ){
       $this->erro_sql = " Campo Receita do Lançamento Espontâneo não informado.";
       $this->erro_campo = "y32_receitexp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_proced == null ){
       $this->erro_sql = " Campo Procedência não informado.";
       $this->erro_campo = "y32_proced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_procedexp == null ){
       $this->erro_sql = " Campo Procedência do Lançamento Espontâneo não informado.";
       $this->erro_campo = "y32_procedexp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_formvist == null ){
       $this->erro_sql = " Campo Fórmula de Cálculo das Vistorias não informado.";
       $this->erro_campo = "y32_formvist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_sanidepto == null ){
       $this->erro_sql = " Campo Controla Sanitário por Departamento não informado.";
       $this->erro_campo = "y32_sanidepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_sanbaixadiv == null ){
       $this->erro_sql = " Campo Permite Baixa de Sanitário com Dívida não informado.";
       $this->erro_campo = "y32_sanbaixadiv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_tipoprocpadrao == null ){
       $this->erro_sql = " Campo Tipo de Processo não informado.";
       $this->erro_campo = "y32_tipoprocpadrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_calcvistanosanteriores == null ){
       $this->erro_sql = " Campo Calcula Vistorias para Anos Anteriores não informado.";
       $this->erro_campo = "y32_calcvistanosanteriores";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_procprotbaixaauto == null ){
       $this->erro_sql = " Campo Processo Baixa de Auto de Infração não informado.";
       $this->erro_campo = "y32_procprotbaixaauto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_utilizacalculoporteatividade == null ){
       $this->erro_sql = " Campo Utiliza Cálculo por Porte/Atividade não informado.";
       $this->erro_campo = "y32_utilizacalculoporteatividade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y32_templatealvarasanitariopermanente == null ){
       $this->y32_templatealvarasanitariopermanente = "null";
     }
     if($this->y32_templatealvarasanitarioprovisorio == null ){
       $this->y32_templatealvarasanitarioprovisorio = "null";
     }
     if($this->y32_templateautoinfracao == null ){
       $this->y32_templateautoinfracao = "null";
     }
     if($this->y32_calculavistoriamei == null ){
       $this->y32_calculavistoriamei = "false";
     }
       $this->y32_instit = $y32_instit;
     if(($this->y32_instit == null) || ($this->y32_instit == "") ){
       $this->erro_sql = " Campo y32_instit não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into parfiscal(
                                       y32_instit
                                      ,y32_tipo
                                      ,y32_hist
                                      ,y32_impdatas
                                      ,y32_impcodativ
                                      ,y32_impobs
                                      ,y32_impobslanc
                                      ,y32_modalvara
                                      ,y32_modaidof
                                      ,y32_receit
                                      ,y32_receitexp
                                      ,y32_proced
                                      ,y32_procedexp
                                      ,y32_formvist
                                      ,y32_sanidepto
                                      ,y32_sanbaixadiv
                                      ,y32_tipoprocpadrao
                                      ,y32_calcvistanosanteriores
                                      ,y32_procprotbaixaauto
                                      ,y32_utilizacalculoporteatividade
                                      ,y32_templatealvarasanitariopermanente
                                      ,y32_templatealvarasanitarioprovisorio
                                      ,y32_templateautoinfracao
                                      ,y32_calculavistoriamei
                       )
                values (
                                $this->y32_instit
                               ,$this->y32_tipo
                               ,$this->y32_hist
                               ,'$this->y32_impdatas'
                               ,'$this->y32_impcodativ'
                               ,'$this->y32_impobs'
                               ,'$this->y32_impobslanc'
                               ,$this->y32_modalvara
                               ,$this->y32_modaidof
                               ,$this->y32_receit
                               ,$this->y32_receitexp
                               ,$this->y32_proced
                               ,$this->y32_procedexp
                               ,$this->y32_formvist
                               ,$this->y32_sanidepto
                               ,$this->y32_sanbaixadiv
                               ,$this->y32_tipoprocpadrao
                               ,'$this->y32_calcvistanosanteriores'
                               ,$this->y32_procprotbaixaauto
                               ,'$this->y32_utilizacalculoporteatividade'
                               ,$this->y32_templatealvarasanitariopermanente
                               ,$this->y32_templatealvarasanitarioprovisorio
                               ,$this->y32_templateautoinfracao
                               ,'$this->y32_calculavistoriamei'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros do modulo fiscal ($this->y32_instit) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros do modulo fiscal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros do modulo fiscal ($this->y32_instit) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y32_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->y32_instit  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10661,'$this->y32_instit','I')");
         $resac = db_query("insert into db_acount values($acount,1103,10661,'','".AddSlashes(pg_result($resaco,0,'y32_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,6762,'','".AddSlashes(pg_result($resaco,0,'y32_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,6763,'','".AddSlashes(pg_result($resaco,0,'y32_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,7482,'','".AddSlashes(pg_result($resaco,0,'y32_impdatas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,7483,'','".AddSlashes(pg_result($resaco,0,'y32_impcodativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,7484,'','".AddSlashes(pg_result($resaco,0,'y32_impobs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,7566,'','".AddSlashes(pg_result($resaco,0,'y32_impobslanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,7565,'','".AddSlashes(pg_result($resaco,0,'y32_modalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,14589,'','".AddSlashes(pg_result($resaco,0,'y32_modaidof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,7767,'','".AddSlashes(pg_result($resaco,0,'y32_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,7768,'','".AddSlashes(pg_result($resaco,0,'y32_receitexp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,7769,'','".AddSlashes(pg_result($resaco,0,'y32_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,7770,'','".AddSlashes(pg_result($resaco,0,'y32_procedexp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,8741,'','".AddSlashes(pg_result($resaco,0,'y32_formvist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,9450,'','".AddSlashes(pg_result($resaco,0,'y32_sanidepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,10145,'','".AddSlashes(pg_result($resaco,0,'y32_sanbaixadiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,12056,'','".AddSlashes(pg_result($resaco,0,'y32_tipoprocpadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,12291,'','".AddSlashes(pg_result($resaco,0,'y32_calcvistanosanteriores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,16039,'','".AddSlashes(pg_result($resaco,0,'y32_procprotbaixaauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,20479,'','".AddSlashes(pg_result($resaco,0,'y32_utilizacalculoporteatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,20669,'','".AddSlashes(pg_result($resaco,0,'y32_templatealvarasanitariopermanente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,20668,'','".AddSlashes(pg_result($resaco,0,'y32_templatealvarasanitarioprovisorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,20824,'','".AddSlashes(pg_result($resaco,0,'y32_templateautoinfracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1103,21641,'','".AddSlashes(pg_result($resaco,0,'y32_calculavistoriamei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($y32_instit=null) {
      $this->atualizacampos();
     $sql = " update parfiscal set ";
     $virgula = "";
     if(trim($this->y32_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_instit"])){
       $sql  .= $virgula." y32_instit = $this->y32_instit ";
       $virgula = ",";
       if(trim($this->y32_instit) == null ){
         $this->erro_sql = " Campo Código da Instituição não informado.";
         $this->erro_campo = "y32_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_tipo"])){
       $sql  .= $virgula." y32_tipo = $this->y32_tipo ";
       $virgula = ",";
       if(trim($this->y32_tipo) == null ){
         $this->erro_sql = " Campo Tipo de Débito não informado.";
         $this->erro_campo = "y32_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_hist"])){
       $sql  .= $virgula." y32_hist = $this->y32_hist ";
       $virgula = ",";
       if(trim($this->y32_hist) == null ){
         $this->erro_sql = " Campo Histórico do Cálculo não informado.";
         $this->erro_campo = "y32_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_impdatas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_impdatas"])){
       $sql  .= $virgula." y32_impdatas = '$this->y32_impdatas' ";
       $virgula = ",";
       if(trim($this->y32_impdatas) == null ){
         $this->erro_sql = " Campo Imprime Datas do Alvará Sanitário não informado.";
         $this->erro_campo = "y32_impdatas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_impcodativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_impcodativ"])){
       $sql  .= $virgula." y32_impcodativ = '$this->y32_impcodativ' ";
       $virgula = ",";
       if(trim($this->y32_impcodativ) == null ){
         $this->erro_sql = " Campo Código Atividade do Alvará Sanitário não informado.";
         $this->erro_campo = "y32_impcodativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_impobs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_impobs"])){
       $sql  .= $virgula." y32_impobs = '$this->y32_impobs' ";
       $virgula = ",";
       if(trim($this->y32_impobs) == null ){
         $this->erro_sql = " Campo Imprime Obs. das Atividades do Alvará Sanitário não informado.";
         $this->erro_campo = "y32_impobs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_impobslanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_impobslanc"])){
       $sql  .= $virgula." y32_impobslanc = '$this->y32_impobslanc' ";
       $virgula = ",";
       if(trim($this->y32_impobslanc) == null ){
         $this->erro_sql = " Campo Observação do Lançamento não informado.";
         $this->erro_campo = "y32_impobslanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_modalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_modalvara"])){
       $sql  .= $virgula." y32_modalvara = $this->y32_modalvara ";
       $virgula = ",";
       if(trim($this->y32_modalvara) == null ){
         $this->erro_sql = " Campo Modelo Alvará não informado.";
         $this->erro_campo = "y32_modalvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_modaidof)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_modaidof"])){
       $sql  .= $virgula." y32_modaidof = $this->y32_modaidof ";
       $virgula = ",";
       if(trim($this->y32_modaidof) == null ){
         $this->erro_sql = " Campo Modelo AIDOF não informado.";
         $this->erro_campo = "y32_modaidof";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_receit"])){
       $sql  .= $virgula." y32_receit = $this->y32_receit ";
       $virgula = ",";
       if(trim($this->y32_receit) == null ){
         $this->erro_sql = " Campo Receita não informado.";
         $this->erro_campo = "y32_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_receitexp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_receitexp"])){
       $sql  .= $virgula." y32_receitexp = $this->y32_receitexp ";
       $virgula = ",";
       if(trim($this->y32_receitexp) == null ){
         $this->erro_sql = " Campo Receita do Lançamento Espontâneo não informado.";
         $this->erro_campo = "y32_receitexp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_proced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_proced"])){
       $sql  .= $virgula." y32_proced = $this->y32_proced ";
       $virgula = ",";
       if(trim($this->y32_proced) == null ){
         $this->erro_sql = " Campo Procedência não informado.";
         $this->erro_campo = "y32_proced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_procedexp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_procedexp"])){
       $sql  .= $virgula." y32_procedexp = $this->y32_procedexp ";
       $virgula = ",";
       if(trim($this->y32_procedexp) == null ){
         $this->erro_sql = " Campo Procedência do Lançamento Espontâneo não informado.";
         $this->erro_campo = "y32_procedexp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_formvist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_formvist"])){
       $sql  .= $virgula." y32_formvist = $this->y32_formvist ";
       $virgula = ",";
       if(trim($this->y32_formvist) == null ){
         $this->erro_sql = " Campo Fórmula de Cálculo das Vistorias não informado.";
         $this->erro_campo = "y32_formvist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_sanidepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_sanidepto"])){
       $sql  .= $virgula." y32_sanidepto = $this->y32_sanidepto ";
       $virgula = ",";
       if(trim($this->y32_sanidepto) == null ){
         $this->erro_sql = " Campo Controla Sanitário por Departamento não informado.";
         $this->erro_campo = "y32_sanidepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_sanbaixadiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_sanbaixadiv"])){
       $sql  .= $virgula." y32_sanbaixadiv = $this->y32_sanbaixadiv ";
       $virgula = ",";
       if(trim($this->y32_sanbaixadiv) == null ){
         $this->erro_sql = " Campo Permite Baixa de Sanitário com Dívida não informado.";
         $this->erro_campo = "y32_sanbaixadiv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_tipoprocpadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_tipoprocpadrao"])){
       $sql  .= $virgula." y32_tipoprocpadrao = $this->y32_tipoprocpadrao ";
       $virgula = ",";
       if(trim($this->y32_tipoprocpadrao) == null ){
         $this->erro_sql = " Campo Tipo de Processo não informado.";
         $this->erro_campo = "y32_tipoprocpadrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_calcvistanosanteriores)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_calcvistanosanteriores"])){
       $sql  .= $virgula." y32_calcvistanosanteriores = '$this->y32_calcvistanosanteriores' ";
       $virgula = ",";
       if(trim($this->y32_calcvistanosanteriores) == null ){
         $this->erro_sql = " Campo Calcula Vistorias para Anos Anteriores não informado.";
         $this->erro_campo = "y32_calcvistanosanteriores";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_procprotbaixaauto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_procprotbaixaauto"])){
       $sql  .= $virgula." y32_procprotbaixaauto = $this->y32_procprotbaixaauto ";
       $virgula = ",";
       if(trim($this->y32_procprotbaixaauto) == null ){
         $this->erro_sql = " Campo Processo Baixa de Auto de Infração não informado.";
         $this->erro_campo = "y32_procprotbaixaauto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_utilizacalculoporteatividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_utilizacalculoporteatividade"])){
       $sql  .= $virgula." y32_utilizacalculoporteatividade = '$this->y32_utilizacalculoporteatividade' ";
       $virgula = ",";
       if(trim($this->y32_utilizacalculoporteatividade) == null ){
         $this->erro_sql = " Campo Utiliza Cálculo por Porte/Atividade não informado.";
         $this->erro_campo = "y32_utilizacalculoporteatividade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y32_templatealvarasanitariopermanente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_templatealvarasanitariopermanente"])){
        if(trim($this->y32_templatealvarasanitariopermanente)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y32_templatealvarasanitariopermanente"])){
           $this->y32_templatealvarasanitariopermanente = "null" ;
        }
       $sql  .= $virgula." y32_templatealvarasanitariopermanente = $this->y32_templatealvarasanitariopermanente ";
       $virgula = ",";
     }
     if(trim($this->y32_templatealvarasanitarioprovisorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_templatealvarasanitarioprovisorio"])){
        if(trim($this->y32_templatealvarasanitarioprovisorio)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y32_templatealvarasanitarioprovisorio"])){
           $this->y32_templatealvarasanitarioprovisorio = "null" ;
        }
       $sql  .= $virgula." y32_templatealvarasanitarioprovisorio = $this->y32_templatealvarasanitarioprovisorio ";
       $virgula = ",";
     }
     if(trim($this->y32_templateautoinfracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_templateautoinfracao"])){
        if(trim($this->y32_templateautoinfracao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y32_templateautoinfracao"])){
           $this->y32_templateautoinfracao = "null" ;
        }
       $sql  .= $virgula." y32_templateautoinfracao = $this->y32_templateautoinfracao ";
       $virgula = ",";
     }
     if(trim($this->y32_calculavistoriamei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y32_calculavistoriamei"])){
        if(trim($this->y32_calculavistoriamei)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y32_calculavistoriamei"])){
           $this->y32_calculavistoriamei = "0" ;
        }
       $sql  .= $virgula." y32_calculavistoriamei = $this->y32_calculavistoriamei ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($y32_instit!=null){
       $sql .= " y32_instit = $this->y32_instit";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->y32_instit));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,10661,'$this->y32_instit','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_instit"]) || $this->y32_instit != "")
             $resac = db_query("insert into db_acount values($acount,1103,10661,'".AddSlashes(pg_result($resaco,$conresaco,'y32_instit'))."','$this->y32_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_tipo"]) || $this->y32_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1103,6762,'".AddSlashes(pg_result($resaco,$conresaco,'y32_tipo'))."','$this->y32_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_hist"]) || $this->y32_hist != "")
             $resac = db_query("insert into db_acount values($acount,1103,6763,'".AddSlashes(pg_result($resaco,$conresaco,'y32_hist'))."','$this->y32_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_impdatas"]) || $this->y32_impdatas != "")
             $resac = db_query("insert into db_acount values($acount,1103,7482,'".AddSlashes(pg_result($resaco,$conresaco,'y32_impdatas'))."','$this->y32_impdatas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_impcodativ"]) || $this->y32_impcodativ != "")
             $resac = db_query("insert into db_acount values($acount,1103,7483,'".AddSlashes(pg_result($resaco,$conresaco,'y32_impcodativ'))."','$this->y32_impcodativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_impobs"]) || $this->y32_impobs != "")
             $resac = db_query("insert into db_acount values($acount,1103,7484,'".AddSlashes(pg_result($resaco,$conresaco,'y32_impobs'))."','$this->y32_impobs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_impobslanc"]) || $this->y32_impobslanc != "")
             $resac = db_query("insert into db_acount values($acount,1103,7566,'".AddSlashes(pg_result($resaco,$conresaco,'y32_impobslanc'))."','$this->y32_impobslanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_modalvara"]) || $this->y32_modalvara != "")
             $resac = db_query("insert into db_acount values($acount,1103,7565,'".AddSlashes(pg_result($resaco,$conresaco,'y32_modalvara'))."','$this->y32_modalvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_modaidof"]) || $this->y32_modaidof != "")
             $resac = db_query("insert into db_acount values($acount,1103,14589,'".AddSlashes(pg_result($resaco,$conresaco,'y32_modaidof'))."','$this->y32_modaidof',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_receit"]) || $this->y32_receit != "")
             $resac = db_query("insert into db_acount values($acount,1103,7767,'".AddSlashes(pg_result($resaco,$conresaco,'y32_receit'))."','$this->y32_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_receitexp"]) || $this->y32_receitexp != "")
             $resac = db_query("insert into db_acount values($acount,1103,7768,'".AddSlashes(pg_result($resaco,$conresaco,'y32_receitexp'))."','$this->y32_receitexp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_proced"]) || $this->y32_proced != "")
             $resac = db_query("insert into db_acount values($acount,1103,7769,'".AddSlashes(pg_result($resaco,$conresaco,'y32_proced'))."','$this->y32_proced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_procedexp"]) || $this->y32_procedexp != "")
             $resac = db_query("insert into db_acount values($acount,1103,7770,'".AddSlashes(pg_result($resaco,$conresaco,'y32_procedexp'))."','$this->y32_procedexp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_formvist"]) || $this->y32_formvist != "")
             $resac = db_query("insert into db_acount values($acount,1103,8741,'".AddSlashes(pg_result($resaco,$conresaco,'y32_formvist'))."','$this->y32_formvist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_sanidepto"]) || $this->y32_sanidepto != "")
             $resac = db_query("insert into db_acount values($acount,1103,9450,'".AddSlashes(pg_result($resaco,$conresaco,'y32_sanidepto'))."','$this->y32_sanidepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_sanbaixadiv"]) || $this->y32_sanbaixadiv != "")
             $resac = db_query("insert into db_acount values($acount,1103,10145,'".AddSlashes(pg_result($resaco,$conresaco,'y32_sanbaixadiv'))."','$this->y32_sanbaixadiv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_tipoprocpadrao"]) || $this->y32_tipoprocpadrao != "")
             $resac = db_query("insert into db_acount values($acount,1103,12056,'".AddSlashes(pg_result($resaco,$conresaco,'y32_tipoprocpadrao'))."','$this->y32_tipoprocpadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_calcvistanosanteriores"]) || $this->y32_calcvistanosanteriores != "")
             $resac = db_query("insert into db_acount values($acount,1103,12291,'".AddSlashes(pg_result($resaco,$conresaco,'y32_calcvistanosanteriores'))."','$this->y32_calcvistanosanteriores',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_procprotbaixaauto"]) || $this->y32_procprotbaixaauto != "")
             $resac = db_query("insert into db_acount values($acount,1103,16039,'".AddSlashes(pg_result($resaco,$conresaco,'y32_procprotbaixaauto'))."','$this->y32_procprotbaixaauto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_utilizacalculoporteatividade"]) || $this->y32_utilizacalculoporteatividade != "")
             $resac = db_query("insert into db_acount values($acount,1103,20479,'".AddSlashes(pg_result($resaco,$conresaco,'y32_utilizacalculoporteatividade'))."','$this->y32_utilizacalculoporteatividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_templatealvarasanitariopermanente"]) || $this->y32_templatealvarasanitariopermanente != "")
             $resac = db_query("insert into db_acount values($acount,1103,20669,'".AddSlashes(pg_result($resaco,$conresaco,'y32_templatealvarasanitariopermanente'))."','$this->y32_templatealvarasanitariopermanente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_templatealvarasanitarioprovisorio"]) || $this->y32_templatealvarasanitarioprovisorio != "")
             $resac = db_query("insert into db_acount values($acount,1103,20668,'".AddSlashes(pg_result($resaco,$conresaco,'y32_templatealvarasanitarioprovisorio'))."','$this->y32_templatealvarasanitarioprovisorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_templateautoinfracao"]) || $this->y32_templateautoinfracao != "")
             $resac = db_query("insert into db_acount values($acount,1103,20824,'".AddSlashes(pg_result($resaco,$conresaco,'y32_templateautoinfracao'))."','$this->y32_templateautoinfracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["y32_calculavistoriamei"]) || $this->y32_calculavistoriamei != "")
             $resac = db_query("insert into db_acount values($acount,1103,21641,'".AddSlashes(pg_result($resaco,$conresaco,'y32_calculavistoriamei'))."','$this->y32_calculavistoriamei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros do modulo fiscal não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y32_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parametros do modulo fiscal não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y32_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y32_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($y32_instit=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($y32_instit));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,10661,'$y32_instit','E')");
           $resac  = db_query("insert into db_acount values($acount,1103,10661,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,6762,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,6763,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,7482,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_impdatas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,7483,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_impcodativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,7484,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_impobs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,7566,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_impobslanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,7565,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_modalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,14589,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_modaidof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,7767,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,7768,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_receitexp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,7769,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,7770,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_procedexp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,8741,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_formvist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,9450,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_sanidepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,10145,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_sanbaixadiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,12056,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_tipoprocpadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,12291,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_calcvistanosanteriores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,16039,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_procprotbaixaauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,20479,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_utilizacalculoporteatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,20669,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_templatealvarasanitariopermanente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,20668,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_templatealvarasanitarioprovisorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,20824,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_templateautoinfracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1103,21641,'','".AddSlashes(pg_result($resaco,$iresaco,'y32_calculavistoriamei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from parfiscal
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($y32_instit)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " y32_instit = $y32_instit ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros do modulo fiscal não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y32_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parametros do modulo fiscal não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y32_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y32_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:parfiscal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $y32_instit=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= "  from parfiscal ";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = parfiscal.y32_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = parfiscal.y32_receit";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = parfiscal.y32_tipo";
     $sql .= "      inner join db_config  on  db_config.codigo = parfiscal.y32_instit";
     $sql .= "      inner join proced  on  proced.v03_codigo = parfiscal.y32_proced";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = parfiscal.y32_tipoprocpadrao";
     $sql .= "      left  join db_documentotemplate  on  db_documentotemplate.db82_sequencial = parfiscal.y32_templateautoinfracao";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join db_config  as a on   a.codigo = arretipo.k00_instit";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join histcalc histcalc2  on  histcalc2.k01_codigo = proced.k00_hist";
     $sql .= "      inner join tabrec  as b on   b.k02_codigo = proced.v03_receit";
     $sql .= "      inner join db_config  as c on   c.codigo = proced.v03_instit";
     $sql .= "      inner join db_config  as d on   d.codigo = tipoproc.p51_instit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($y32_instit)) {
         $sql2 .= " where parfiscal.y32_instit = $y32_instit ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_param ( $y32_instit=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from parfiscal ";
     $sql .= "      left join histcalc  on  histcalc.k01_codigo = parfiscal.y32_hist";
     $sql .= "      left join tabrec  on  tabrec.k02_codigo = parfiscal.y32_receit";
     $sql .= "      left join arretipo  on  arretipo.k00_tipo = parfiscal.y32_tipo";
     $sql .= "      left join db_config  on  db_config.codigo = parfiscal.y32_instit";
     $sql .= "      left join proced  on  proced.v03_codigo = parfiscal.y32_proced";
     $sql .= "      left join tipoproc  on  tipoproc.p51_codigo = parfiscal.y32_tipoprocpadrao";
     $sql .= "      left join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      left join db_config  as a on   a.codigo = arretipo.k00_instit";
     $sql .= "      left join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      left join histcalc histcalc2  on  histcalc2.k01_codigo = proced.k00_hist";
     $sql .= "      left join tabrec  as b on   b.k02_codigo = proced.v03_receit";
     $sql .= "      left join db_config  as c on   c.codigo = proced.v03_instit";
     $sql .= "      left join db_config  as d on   d.codigo = tipoproc.p51_instit";
     $sql .= "      left join db_documentotemplate as doctemplateProvisorio on doctemplateProvisorio.db82_sequencial = parfiscal.y32_templatealvarasanitarioprovisorio";
     $sql2 = "";
   // funcao do sql
     if($dbwhere==""){
       if($y32_instit!=null ){
         $sql2 .= " where parfiscal.y32_instit = $y32_instit ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

   function sql_query_file ( $y32_instit=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= "  from parfiscal ";
     $sql2 = "";
     if($dbwhere==""){
       if($y32_instit!=null ){
         $sql2 .= " where parfiscal.y32_instit = $y32_instit ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

}
