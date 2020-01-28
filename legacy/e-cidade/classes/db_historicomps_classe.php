<?php
/*
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

//MODULO: escola
//CLASSE DA ENTIDADE historicomps
class cl_historicomps { 
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
   var $ed62_i_codigo = 0; 
   var $ed62_i_historico = 0; 
   var $ed62_i_escola = 0; 
   var $ed62_i_serie = 0; 
   var $ed62_i_turma = null; 
   var $ed62_i_justificativa = null;
   var $ed62_i_anoref = 0; 
   var $ed62_i_periodoref = 0; 
   var $ed62_c_resultadofinal = null; 
   var $ed62_c_situacao = null; 
   var $ed62_i_qtdch = 0; 
   var $ed62_i_diasletivos = 0; 
   var $ed62_c_minimo = null; 
   var $ed62_c_termofinal = null; 
   var $ed62_lancamentoautomatico = 'f'; 
   var $ed62_percentualfrequencia = null;
   var $ed62_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed62_i_codigo = int8 = Código 
                 ed62_i_historico = int8 = Histórico 
                 ed62_i_escola = int8 = Escola 
                 ed62_i_serie = int8 = Etapa 
                 ed62_i_turma = char(80) = Turma 
                 ed62_i_justificativa = int8 = Justificativa 
                 ed62_i_anoref = int4 = Ano 
                 ed62_i_periodoref = int4 = Período 
                 ed62_c_resultadofinal = char(1) = Resultado Final 
                 ed62_c_situacao = char(20) = Situação 
                 ed62_i_qtdch = numeric(10) = Carga Horária 
                 ed62_i_diasletivos = int4 = Dias Letivos 
                 ed62_c_minimo = char(20) = Mínimo Aprovação 
                 ed62_c_termofinal = varchar(4) = Termo Final 
                 ed62_lancamentoautomatico = bool = Lançamento Automático 
                 ed62_percentualfrequencia = float8 = Percentual de Frequência 
                 ed62_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_historicomps() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("historicomps"); 
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
       $this->ed62_i_codigo = ($this->ed62_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_i_codigo"]:$this->ed62_i_codigo);
       $this->ed62_i_historico = ($this->ed62_i_historico == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_i_historico"]:$this->ed62_i_historico);
       $this->ed62_i_escola = ($this->ed62_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_i_escola"]:$this->ed62_i_escola);
       $this->ed62_i_serie = ($this->ed62_i_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_i_serie"]:$this->ed62_i_serie);
       $this->ed62_i_turma = ($this->ed62_i_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_i_turma"]:$this->ed62_i_turma);
       $this->ed62_i_justificativa = ($this->ed62_i_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_i_justificativa"]:$this->ed62_i_justificativa);
       $this->ed62_i_anoref = ($this->ed62_i_anoref == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_i_anoref"]:$this->ed62_i_anoref);
       $this->ed62_i_periodoref = ($this->ed62_i_periodoref == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_i_periodoref"]:$this->ed62_i_periodoref);
       $this->ed62_c_resultadofinal = ($this->ed62_c_resultadofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_c_resultadofinal"]:$this->ed62_c_resultadofinal);
       $this->ed62_c_situacao = ($this->ed62_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_c_situacao"]:$this->ed62_c_situacao);
       $this->ed62_i_qtdch = ($this->ed62_i_qtdch == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_i_qtdch"]:$this->ed62_i_qtdch);
       $this->ed62_i_diasletivos = ($this->ed62_i_diasletivos == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_i_diasletivos"]:$this->ed62_i_diasletivos);
       $this->ed62_c_minimo = ($this->ed62_c_minimo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_c_minimo"]:$this->ed62_c_minimo);
       $this->ed62_c_termofinal = ($this->ed62_c_termofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_c_termofinal"]:$this->ed62_c_termofinal);
       $this->ed62_lancamentoautomatico = ($this->ed62_lancamentoautomatico == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed62_lancamentoautomatico"]:$this->ed62_lancamentoautomatico);
       $this->ed62_percentualfrequencia = ($this->ed62_percentualfrequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_percentualfrequencia"]:$this->ed62_percentualfrequencia);
       $this->ed62_observacao = ($this->ed62_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_observacao"]:$this->ed62_observacao);
     }else{
       $this->ed62_i_codigo = ($this->ed62_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed62_i_codigo"]:$this->ed62_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed62_i_codigo){ 
      $this->atualizacampos();
     if($this->ed62_i_historico == null ){ 
       $this->erro_sql = " Campo Histórico não informado.";
       $this->erro_campo = "ed62_i_historico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed62_i_escola == null ){ 
       $this->erro_sql = " Campo Escola não informado.";
       $this->erro_campo = "ed62_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed62_i_serie == null ){ 
       $this->erro_sql = " Campo Etapa não informado.";
       $this->erro_campo = "ed62_i_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed62_i_justificativa == null ){ 
       $this->ed62_i_justificativa = "null";
     }
     if($this->ed62_i_anoref == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "ed62_i_anoref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed62_i_periodoref == null ){ 
       $this->ed62_i_periodoref = "0";
     }
     if($this->ed62_c_resultadofinal == null ){ 
       $this->erro_sql = " Campo Resultado Final não informado.";
       $this->erro_campo = "ed62_c_resultadofinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed62_c_situacao == null ){ 
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "ed62_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed62_i_qtdch == null ){ 
       $this->ed62_i_qtdch = "null";
     }
     if($this->ed62_i_diasletivos == null ){ 
       $this->ed62_i_diasletivos = "null";
     }
     if($this->ed62_lancamentoautomatico == null ){ 
       $this->erro_sql = " Campo Lançamento Automático não informado.";
       $this->erro_campo = "ed62_lancamentoautomatico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed62_percentualfrequencia == null ){ 
       $this->ed62_percentualfrequencia = "null";
     }
     if($ed62_i_codigo == "" || $ed62_i_codigo == null ){
       $result = db_query("select nextval('historicomps_ed62_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: historicomps_ed62_i_codigo_seq do campo: ed62_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed62_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from historicomps_ed62_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed62_i_codigo)){
         $this->erro_sql = " Campo ed62_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed62_i_codigo = $ed62_i_codigo; 
       }
     }
     if(($this->ed62_i_codigo == null) || ($this->ed62_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed62_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into historicomps(
                                       ed62_i_codigo 
                                      ,ed62_i_historico 
                                      ,ed62_i_escola 
                                      ,ed62_i_serie 
                                      ,ed62_i_turma 
                                      ,ed62_i_justificativa 
                                      ,ed62_i_anoref 
                                      ,ed62_i_periodoref 
                                      ,ed62_c_resultadofinal 
                                      ,ed62_c_situacao 
                                      ,ed62_i_qtdch 
                                      ,ed62_i_diasletivos 
                                      ,ed62_c_minimo 
                                      ,ed62_c_termofinal 
                                      ,ed62_lancamentoautomatico 
                                      ,ed62_percentualfrequencia 
                                      ,ed62_observacao 
                       )
                values (
                                $this->ed62_i_codigo 
                               ,$this->ed62_i_historico 
                               ,$this->ed62_i_escola 
                               ,$this->ed62_i_serie 
                               ,'$this->ed62_i_turma' 
                               ,$this->ed62_i_justificativa 
                               ,$this->ed62_i_anoref 
                               ,$this->ed62_i_periodoref 
                               ,'$this->ed62_c_resultadofinal' 
                               ,'$this->ed62_c_situacao' 
                               ,$this->ed62_i_qtdch 
                               ,$this->ed62_i_diasletivos 
                               ,'$this->ed62_c_minimo' 
                               ,'$this->ed62_c_termofinal' 
                               ,'$this->ed62_lancamentoautomatico' 
                               ,$this->ed62_percentualfrequencia 
                               ,'$this->ed62_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Historico MPS ($this->ed62_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Historico MPS já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Historico MPS ($this->ed62_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed62_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed62_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008767,'$this->ed62_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010132,1008767,'','".AddSlashes(pg_result($resaco,0,'ed62_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,1008768,'','".AddSlashes(pg_result($resaco,0,'ed62_i_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,1008769,'','".AddSlashes(pg_result($resaco,0,'ed62_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,1008770,'','".AddSlashes(pg_result($resaco,0,'ed62_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,1008771,'','".AddSlashes(pg_result($resaco,0,'ed62_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,1008772,'','".AddSlashes(pg_result($resaco,0,'ed62_i_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,1008773,'','".AddSlashes(pg_result($resaco,0,'ed62_i_anoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,1008774,'','".AddSlashes(pg_result($resaco,0,'ed62_i_periodoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,1008775,'','".AddSlashes(pg_result($resaco,0,'ed62_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,1008776,'','".AddSlashes(pg_result($resaco,0,'ed62_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,1008777,'','".AddSlashes(pg_result($resaco,0,'ed62_i_qtdch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,1008778,'','".AddSlashes(pg_result($resaco,0,'ed62_i_diasletivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,14632,'','".AddSlashes(pg_result($resaco,0,'ed62_c_minimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,19692,'','".AddSlashes(pg_result($resaco,0,'ed62_c_termofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,19791,'','".AddSlashes(pg_result($resaco,0,'ed62_lancamentoautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,20294,'','".AddSlashes(pg_result($resaco,0,'ed62_percentualfrequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010132,20368,'','".AddSlashes(pg_result($resaco,0,'ed62_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed62_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update historicomps set ";
     $virgula = "";
     if(trim($this->ed62_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_codigo"])){ 
       $sql  .= $virgula." ed62_i_codigo = $this->ed62_i_codigo ";
       $virgula = ",";
       if(trim($this->ed62_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed62_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed62_i_historico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_historico"])){ 
       $sql  .= $virgula." ed62_i_historico = $this->ed62_i_historico ";
       $virgula = ",";
       if(trim($this->ed62_i_historico) == null ){ 
         $this->erro_sql = " Campo Histórico não informado.";
         $this->erro_campo = "ed62_i_historico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed62_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_escola"])){ 
       $sql  .= $virgula." ed62_i_escola = $this->ed62_i_escola ";
       $virgula = ",";
       if(trim($this->ed62_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola não informado.";
         $this->erro_campo = "ed62_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed62_i_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_serie"])){ 
       $sql  .= $virgula." ed62_i_serie = $this->ed62_i_serie ";
       $virgula = ",";
       if(trim($this->ed62_i_serie) == null ){ 
         $this->erro_sql = " Campo Etapa não informado.";
         $this->erro_campo = "ed62_i_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed62_i_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_turma"])){ 
       $sql  .= $virgula." ed62_i_turma = '$this->ed62_i_turma' ";
       $virgula = ",";
     }
     if(trim($this->ed62_i_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_justificativa"])){ 
        if(trim($this->ed62_i_justificativa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_justificativa"])){ 
           $this->ed62_i_justificativa = "null" ;
        } 
       $sql  .= $virgula." ed62_i_justificativa = $this->ed62_i_justificativa ";
       $virgula = ",";
     }
     if(trim($this->ed62_i_anoref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_anoref"])){ 
       $sql  .= $virgula." ed62_i_anoref = $this->ed62_i_anoref ";
       $virgula = ",";
       if(trim($this->ed62_i_anoref) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "ed62_i_anoref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed62_i_periodoref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_periodoref"])){ 
        if(trim($this->ed62_i_periodoref)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_periodoref"])){ 
           $this->ed62_i_periodoref = "0" ; 
        } 
       $sql  .= $virgula." ed62_i_periodoref = $this->ed62_i_periodoref ";
       $virgula = ",";
     }
     if(trim($this->ed62_c_resultadofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_c_resultadofinal"])){ 
       $sql  .= $virgula." ed62_c_resultadofinal = '$this->ed62_c_resultadofinal' ";
       $virgula = ",";
       if(trim($this->ed62_c_resultadofinal) == null ){ 
         $this->erro_sql = " Campo Resultado Final não informado.";
         $this->erro_campo = "ed62_c_resultadofinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed62_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_c_situacao"])){ 
       $sql  .= $virgula." ed62_c_situacao = '$this->ed62_c_situacao' ";
       $virgula = ",";
       if(trim($this->ed62_c_situacao) == null ){ 
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "ed62_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed62_i_qtdch)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_qtdch"])){ 
       if(trim($this->ed62_i_qtdch)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_qtdch"])){
         $this->ed62_i_qtdch = "null" ;
       }
       $sql  .= $virgula." ed62_i_qtdch = $this->ed62_i_qtdch ";
       $virgula = ",";
     }
     if(trim($this->ed62_i_diasletivos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_diasletivos"])){ 
        if(trim($this->ed62_i_diasletivos)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_diasletivos"])){ 
           $this->ed62_i_diasletivos = "null" ; 
        } 
       $sql  .= $virgula." ed62_i_diasletivos = $this->ed62_i_diasletivos ";
       $virgula = ",";
     }
     if(trim($this->ed62_c_minimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_c_minimo"])){ 
       $sql  .= $virgula." ed62_c_minimo = '$this->ed62_c_minimo' ";
       $virgula = ",";
     }
     if(trim($this->ed62_c_termofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_c_termofinal"])){ 
       $sql  .= $virgula." ed62_c_termofinal = '$this->ed62_c_termofinal' ";
       $virgula = ",";
     }
     if(trim($this->ed62_lancamentoautomatico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_lancamentoautomatico"])){ 
       $sql  .= $virgula." ed62_lancamentoautomatico = '$this->ed62_lancamentoautomatico' ";
       $virgula = ",";
       if(trim($this->ed62_lancamentoautomatico) == null ){ 
         $this->erro_sql = " Campo Lançamento Automático não informado.";
         $this->erro_campo = "ed62_lancamentoautomatico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if( isset($GLOBALS["HTTP_POST_VARS"]["ed62_percentualfrequencia"]) ){

       if($this->ed62_percentualfrequencia == null ){
         $this->ed62_percentualfrequencia = "null";
       }
       $sql  .= $virgula." ed62_percentualfrequencia = $this->ed62_percentualfrequencia ";
       $virgula = ",";
     }
     if(trim($this->ed62_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed62_observacao"])){ 
       $sql  .= $virgula." ed62_observacao = '$this->ed62_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed62_i_codigo!=null){
       $sql .= " ed62_i_codigo = $this->ed62_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed62_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008767,'$this->ed62_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_codigo"]) || $this->ed62_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010132,1008767,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_i_codigo'))."','$this->ed62_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_historico"]) || $this->ed62_i_historico != "")
             $resac = db_query("insert into db_acount values($acount,1010132,1008768,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_i_historico'))."','$this->ed62_i_historico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_escola"]) || $this->ed62_i_escola != "")
             $resac = db_query("insert into db_acount values($acount,1010132,1008769,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_i_escola'))."','$this->ed62_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_serie"]) || $this->ed62_i_serie != "")
             $resac = db_query("insert into db_acount values($acount,1010132,1008770,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_i_serie'))."','$this->ed62_i_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_turma"]) || $this->ed62_i_turma != "")
             $resac = db_query("insert into db_acount values($acount,1010132,1008771,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_i_turma'))."','$this->ed62_i_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_justificativa"]) || $this->ed62_i_justificativa != "")
             $resac = db_query("insert into db_acount values($acount,1010132,1008772,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_i_justificativa'))."','$this->ed62_i_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_anoref"]) || $this->ed62_i_anoref != "")
             $resac = db_query("insert into db_acount values($acount,1010132,1008773,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_i_anoref'))."','$this->ed62_i_anoref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_periodoref"]) || $this->ed62_i_periodoref != "")
             $resac = db_query("insert into db_acount values($acount,1010132,1008774,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_i_periodoref'))."','$this->ed62_i_periodoref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_c_resultadofinal"]) || $this->ed62_c_resultadofinal != "")
             $resac = db_query("insert into db_acount values($acount,1010132,1008775,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_c_resultadofinal'))."','$this->ed62_c_resultadofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_c_situacao"]) || $this->ed62_c_situacao != "")
             $resac = db_query("insert into db_acount values($acount,1010132,1008776,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_c_situacao'))."','$this->ed62_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_qtdch"]) || $this->ed62_i_qtdch != "")
             $resac = db_query("insert into db_acount values($acount,1010132,1008777,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_i_qtdch'))."','$this->ed62_i_qtdch',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_i_diasletivos"]) || $this->ed62_i_diasletivos != "")
             $resac = db_query("insert into db_acount values($acount,1010132,1008778,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_i_diasletivos'))."','$this->ed62_i_diasletivos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_c_minimo"]) || $this->ed62_c_minimo != "")
             $resac = db_query("insert into db_acount values($acount,1010132,14632,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_c_minimo'))."','$this->ed62_c_minimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_c_termofinal"]) || $this->ed62_c_termofinal != "")
             $resac = db_query("insert into db_acount values($acount,1010132,19692,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_c_termofinal'))."','$this->ed62_c_termofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_lancamentoautomatico"]) || $this->ed62_lancamentoautomatico != "")
             $resac = db_query("insert into db_acount values($acount,1010132,19791,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_lancamentoautomatico'))."','$this->ed62_lancamentoautomatico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_percentualfrequencia"]) || $this->ed62_percentualfrequencia != "")
             $resac = db_query("insert into db_acount values($acount,1010132,20294,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_percentualfrequencia'))."','$this->ed62_percentualfrequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed62_observacao"]) || $this->ed62_observacao != "")
             $resac = db_query("insert into db_acount values($acount,1010132,20368,'".AddSlashes(pg_result($resaco,$conresaco,'ed62_observacao'))."','$this->ed62_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Historico MPS nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed62_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Historico MPS nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed62_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed62_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed62_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed62_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008767,'$ed62_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010132,1008767,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,1008768,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_i_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,1008769,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,1008770,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,1008771,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,1008772,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_i_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,1008773,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_i_anoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,1008774,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_i_periodoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,1008775,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,1008776,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,1008777,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_i_qtdch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,1008778,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_i_diasletivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,14632,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_c_minimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,19692,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_c_termofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,19791,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_lancamentoautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,20294,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_percentualfrequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010132,20368,'','".AddSlashes(pg_result($resaco,$iresaco,'ed62_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from historicomps
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed62_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed62_i_codigo = $ed62_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Historico MPS nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed62_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Historico MPS nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed62_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed62_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:historicomps";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed62_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from historicomps ";
     $sql .= "      inner join escola             on  escola.ed18_i_codigo = historicomps.ed62_i_escola";
     $sql .= "      inner join bairro             on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas               on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart          on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      left join ruascep             on ruascep.j29_codigo = ruas.j14_codigo ";
     $sql .= "      left join logradcep           on logradcep.j65_lograd = ruas.j14_codigo ";
     $sql .= "      left join ceplogradouros      on ceplogradouros.cp06_codlogradouro = logradcep.j65_ceplog ";
     $sql .= "      left join ceplocalidades      on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade ";
     $sql .= "      left join justificativa       on  justificativa.ed06_i_codigo = historicomps.ed62_i_justificativa";
     $sql .= "      inner join serie              on  serie.ed11_i_codigo = historicomps.ed62_i_serie";
     $sql .= "      inner join historico          on  historico.ed61_i_codigo = historicomps.ed62_i_historico";
     $sql .= "      inner join ensino             on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join escola as hescola  on  hescola.ed18_i_codigo = historico.ed61_i_escola";
     $sql .= "      inner join cursoedu           on  cursoedu.ed29_i_codigo = historico.ed61_i_curso";
     $sql .= "      inner join aluno              on  aluno.ed47_i_codigo = historico.ed61_i_aluno";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed62_i_codigo)) {
         $sql2 .= " where historicomps.ed62_i_codigo = $ed62_i_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($ed62_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from historicomps ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed62_i_codigo)){
         $sql2 .= " where historicomps.ed62_i_codigo = $ed62_i_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

   function sql_query_historico ( $ed62_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") {                              
                                                                                                                        
   $sql = "select ";                                                                                                    
   if ($campos != "*" ) {                                                                                               
                                                                                                                        
     $campos_sql = split("#",$campos);                                                                                  
     $virgula    = "";                                                                                                  
     for ($i = 0; $i < sizeof($campos_sql); $i++) {                                                                     
                                                                                                                        
       $sql     .= $virgula.$campos_sql[$i];                                                                            
       $virgula  = ",";                                                                                                 
     }                                                                                                                  
   } else {                                                                                                             
     $sql .= $campos;                                                                                                   
   }                                                                                                                    
   $sql .= " from historicomps                                                                                    \n";  
   $sql .= "      inner join escola           on  escola.ed18_i_codigo = historicomps.ed62_i_escola               \n";  
   $sql .= "      left  join justificativa    on  justificativa.ed06_i_codigo = historicomps.ed62_i_justificativa \n";  
   $sql .= "      inner join serie            on  serie.ed11_i_codigo = historicomps.ed62_i_serie                 \n";  
   $sql .= "      inner join historico        on  historico.ed61_i_codigo = historicomps.ed62_i_historico         \n";  
   $sql .= "      inner join bairro           on  bairro.j13_codi = escola.ed18_i_bairro                          \n";  
   $sql .= "      inner join ruas             on  ruas.j14_codigo = escola.ed18_i_rua                             \n";  
   $sql .= "      inner join db_depart        on  db_depart.coddepto = escola.ed18_i_codigo                       \n";  
   $sql .= "      inner join censouf          on  censouf.ed260_i_codigo = escola.ed18_i_censouf                  \n";  
   $sql .= "      inner join censomunic       on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic            \n";  
   $sql .= "      inner join censodistrito    on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito      \n";  
   $sql .= "      left  join censoorgreg      on  censoorgreg.ed263_i_codigo = escola.ed18_i_censoorgreg          \n";  
   $sql .= "      left  join censolinguaindig on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena  \n";  
   $sql .= "      inner join ensino           on  ensino.ed10_i_codigo = serie.ed11_i_ensino                      \n";  
   $sql .= "      inner join cursoedu         on  cursoedu.ed29_i_codigo = historico.ed61_i_curso                 \n";  
   $sql .= "      inner join aluno            on  aluno.ed47_i_codigo = historico.ed61_i_aluno                      ";  
   $sql2 = "";                                                                                                          
                                                                                                                        
   if ($dbwhere == "") {                                                                                                
                                                                                                                        
     if ($ed62_i_codigo != null ) {                                                                                     
       $sql2 .= " where historicomps.ed62_i_codigo = $ed62_i_codigo ";                                                  
     }                                                                                                                  
   } else if ($dbwhere != "") {                                                                                         
     $sql2 = " where $dbwhere";                                                                                         
   }                                                                                                                    
   $sql .= $sql2;                                                                                                       
   if ($ordem != null ) {                                                                                               
                                                                                                                        
     $sql        .= " order by ";                                                                                       
     $campos_sql  = split("#",$ordem);                                                                                  
     $virgula     = "";                                                                                                 
     for ($i = 0; $i < sizeof($campos_sql); $i++) {                                                                     
                                                                                                                        
       $sql     .= $virgula.$campos_sql[$i];                                                                            
       $virgula  = ",";                                                                                                 
     }                                                                                                                  
   }                                                                                                                    
   return $sql;                                                                                                         
 }
   function sql_query_certconclusao($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

         $sSql .= $sVirgula.$sCamposSql[$iCont];
         $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " FROM historicomps ";
    $sSql .= " inner join serie on ed11_i_codigo = ed62_i_serie ";
    $sSql .= " inner join escola  on  escola.ed18_i_codigo = historicomps.ed62_i_escola";
    $sSql .= " inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf ";
    $sSql .= " inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic ";
    $sSql2 = '';
    if ($sDbWhere != '') {
       $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;
    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
   function sql_query_disciplina_etapa ($ed62_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from historicomps                                       ";
    $sql .= "      left join histmpsdisc on ed65_i_historicomps = ed62_i_codigo ";
    $sql2 = "";

    if ($dbwhere == "") {

      if ($ed62_i_codigo != null ) {
        $sql2 .= " where historicomps.ed62_i_codigo = $ed62_i_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }
}
