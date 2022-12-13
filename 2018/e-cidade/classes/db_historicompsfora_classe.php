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
//CLASSE DA ENTIDADE historicompsfora
class cl_historicompsfora { 
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
   var $ed99_i_codigo = 0; 
   var $ed99_i_historico = 0; 
   var $ed99_i_escolaproc = 0; 
   var $ed99_i_serie = 0; 
   var $ed99_i_justificativa = null;
   var $ed99_c_turma = null; 
   var $ed99_i_anoref = 0; 
   var $ed99_i_periodoref = 0; 
   var $ed99_c_resultadofinal = null; 
   var $ed99_c_situacao = null; 
   var $ed99_i_qtdch = 0; 
   var $ed99_i_diasletivos = 0; 
   var $ed99_c_minimo = null; 
   var $ed99_c_termofinal = null; 
   var $ed99_observacao = null; 
   var $ed99_percentualfrequencia = null;
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed99_i_codigo = int8 = Código 
                 ed99_i_historico = int8 = Hístórico 
                 ed99_i_escolaproc = int8 = Escola 
                 ed99_i_serie = int8 = Etapa 
                 ed99_i_justificativa = int8 = Justificativa 
                 ed99_c_turma = char(80) = Turma 
                 ed99_i_anoref = int4 = Ano 
                 ed99_i_periodoref = int4 = Período 
                 ed99_c_resultadofinal = char(1) = Resultado Final 
                 ed99_c_situacao = char(20) = Situação 
                 ed99_i_qtdch = numeric(10) = Carga Horária 
                 ed99_i_diasletivos = int4 = Dias Letivos 
                 ed99_c_minimo = char(20) = Mínimo Aprovação 
                 ed99_c_termofinal = varchar(4) = Termo Final 
                 ed99_observacao = text = Observação 
                 ed99_percentualfrequencia = float8 = Percentual de Frequência 
                 ";
   //funcao construtor da classe 
   function cl_historicompsfora() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("historicompsfora"); 
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
       $this->ed99_i_codigo = ($this->ed99_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_i_codigo"]:$this->ed99_i_codigo);
       $this->ed99_i_historico = ($this->ed99_i_historico == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_i_historico"]:$this->ed99_i_historico);
       $this->ed99_i_escolaproc = ($this->ed99_i_escolaproc == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_i_escolaproc"]:$this->ed99_i_escolaproc);
       $this->ed99_i_serie = ($this->ed99_i_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_i_serie"]:$this->ed99_i_serie);
       $this->ed99_i_justificativa = ($this->ed99_i_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_i_justificativa"]:$this->ed99_i_justificativa);
       $this->ed99_c_turma = ($this->ed99_c_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_c_turma"]:$this->ed99_c_turma);
       $this->ed99_i_anoref = ($this->ed99_i_anoref == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_i_anoref"]:$this->ed99_i_anoref);
       $this->ed99_i_periodoref = ($this->ed99_i_periodoref == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_i_periodoref"]:$this->ed99_i_periodoref);
       $this->ed99_c_resultadofinal = ($this->ed99_c_resultadofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_c_resultadofinal"]:$this->ed99_c_resultadofinal);
       $this->ed99_c_situacao = ($this->ed99_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_c_situacao"]:$this->ed99_c_situacao);
       $this->ed99_i_qtdch = ($this->ed99_i_qtdch == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_i_qtdch"]:$this->ed99_i_qtdch);
       $this->ed99_i_diasletivos = ($this->ed99_i_diasletivos == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_i_diasletivos"]:$this->ed99_i_diasletivos);
       $this->ed99_c_minimo = ($this->ed99_c_minimo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_c_minimo"]:$this->ed99_c_minimo);
       $this->ed99_c_termofinal = ($this->ed99_c_termofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_c_termofinal"]:$this->ed99_c_termofinal);
       $this->ed99_observacao = ($this->ed99_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_observacao"]:$this->ed99_observacao);
       $this->ed99_percentualfrequencia = ($this->ed99_percentualfrequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_percentualfrequencia"]:$this->ed99_percentualfrequencia);
     }else{
       $this->ed99_i_codigo = ($this->ed99_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed99_i_codigo"]:$this->ed99_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed99_i_codigo){ 
      $this->atualizacampos();
     if($this->ed99_i_historico == null ){ 
       $this->erro_sql = " Campo Hístórico não informado.";
       $this->erro_campo = "ed99_i_historico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed99_i_escolaproc == null ){ 
       $this->erro_sql = " Campo Escola não informado.";
       $this->erro_campo = "ed99_i_escolaproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed99_i_serie == null ){ 
       $this->erro_sql = " Campo Etapa não informado.";
       $this->erro_campo = "ed99_i_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed99_i_justificativa == null ){ 
       $this->ed99_i_justificativa = "null";
     }
     if($this->ed99_i_anoref == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "ed99_i_anoref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed99_i_periodoref == null ){ 
       $this->ed99_i_periodoref = "0";
     }
     if($this->ed99_c_resultadofinal == null ){ 
       $this->erro_sql = " Campo Resultado Final não informado.";
       $this->erro_campo = "ed99_c_resultadofinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed99_c_situacao == null ){ 
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "ed99_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed99_i_qtdch == null ){ 
       $this->ed99_i_qtdch = "null";
     }
     if($this->ed99_i_diasletivos == null ){ 
       $this->ed99_i_diasletivos = "null";
     }
     if($this->ed99_percentualfrequencia == null ){ 
       $this->ed99_percentualfrequencia = "null";
     }
     if($ed99_i_codigo == "" || $ed99_i_codigo == null ){
       $result = db_query("select nextval('historicompsfora_ed99_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: historicompsfora_ed99_i_codigo_seq do campo: ed99_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed99_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from historicompsfora_ed99_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed99_i_codigo)){
         $this->erro_sql = " Campo ed99_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed99_i_codigo = $ed99_i_codigo; 
       }
     }
     if(($this->ed99_i_codigo == null) || ($this->ed99_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed99_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into historicompsfora(
                                       ed99_i_codigo 
                                      ,ed99_i_historico 
                                      ,ed99_i_escolaproc 
                                      ,ed99_i_serie 
                                      ,ed99_i_justificativa 
                                      ,ed99_c_turma 
                                      ,ed99_i_anoref 
                                      ,ed99_i_periodoref 
                                      ,ed99_c_resultadofinal 
                                      ,ed99_c_situacao 
                                      ,ed99_i_qtdch 
                                      ,ed99_i_diasletivos 
                                      ,ed99_c_minimo 
                                      ,ed99_c_termofinal 
                                      ,ed99_observacao 
                                      ,ed99_percentualfrequencia 
                       )
                values (
                                $this->ed99_i_codigo 
                               ,$this->ed99_i_historico 
                               ,$this->ed99_i_escolaproc 
                               ,$this->ed99_i_serie 
                               ,$this->ed99_i_justificativa 
                               ,'$this->ed99_c_turma' 
                               ,$this->ed99_i_anoref 
                               ,$this->ed99_i_periodoref 
                               ,'$this->ed99_c_resultadofinal' 
                               ,'$this->ed99_c_situacao' 
                               ,$this->ed99_i_qtdch 
                               ,$this->ed99_i_diasletivos 
                               ,'$this->ed99_c_minimo' 
                               ,'$this->ed99_c_termofinal' 
                               ,'$this->ed99_observacao' 
                               ,$this->ed99_percentualfrequencia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de séries do histórico ($this->ed99_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de séries do histórico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de séries do histórico ($this->ed99_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed99_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed99_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009008,'$this->ed99_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010157,1009008,'','".AddSlashes(pg_result($resaco,0,'ed99_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,1009009,'','".AddSlashes(pg_result($resaco,0,'ed99_i_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,1009010,'','".AddSlashes(pg_result($resaco,0,'ed99_i_escolaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,1009011,'','".AddSlashes(pg_result($resaco,0,'ed99_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,1009012,'','".AddSlashes(pg_result($resaco,0,'ed99_i_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,1009013,'','".AddSlashes(pg_result($resaco,0,'ed99_c_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,1009014,'','".AddSlashes(pg_result($resaco,0,'ed99_i_anoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,1009015,'','".AddSlashes(pg_result($resaco,0,'ed99_i_periodoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,1009016,'','".AddSlashes(pg_result($resaco,0,'ed99_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,1009017,'','".AddSlashes(pg_result($resaco,0,'ed99_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,1009018,'','".AddSlashes(pg_result($resaco,0,'ed99_i_qtdch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,1009019,'','".AddSlashes(pg_result($resaco,0,'ed99_i_diasletivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,14633,'','".AddSlashes(pg_result($resaco,0,'ed99_c_minimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,19693,'','".AddSlashes(pg_result($resaco,0,'ed99_c_termofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,20370,'','".AddSlashes(pg_result($resaco,0,'ed99_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010157,20819,'','".AddSlashes(pg_result($resaco,0,'ed99_percentualfrequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed99_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update historicompsfora set ";
     $virgula = "";
     if(trim($this->ed99_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_codigo"])){ 
       $sql  .= $virgula." ed99_i_codigo = $this->ed99_i_codigo ";
       $virgula = ",";
       if(trim($this->ed99_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed99_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed99_i_historico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_historico"])){ 
       $sql  .= $virgula." ed99_i_historico = $this->ed99_i_historico ";
       $virgula = ",";
       if(trim($this->ed99_i_historico) == null ){ 
         $this->erro_sql = " Campo Hístórico não informado.";
         $this->erro_campo = "ed99_i_historico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed99_i_escolaproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_escolaproc"])){ 
       $sql  .= $virgula." ed99_i_escolaproc = $this->ed99_i_escolaproc ";
       $virgula = ",";
       if(trim($this->ed99_i_escolaproc) == null ){ 
         $this->erro_sql = " Campo Escola não informado.";
         $this->erro_campo = "ed99_i_escolaproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed99_i_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_serie"])){ 
       $sql  .= $virgula." ed99_i_serie = $this->ed99_i_serie ";
       $virgula = ",";
       if(trim($this->ed99_i_serie) == null ){ 
         $this->erro_sql = " Campo Etapa não informado.";
         $this->erro_campo = "ed99_i_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed99_i_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_justificativa"])){ 
        if(trim($this->ed99_i_justificativa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_justificativa"])){ 
           $this->ed99_i_justificativa = "null" ;
        } 
       $sql  .= $virgula." ed99_i_justificativa = $this->ed99_i_justificativa ";
       $virgula = ",";
     }
     if(trim($this->ed99_c_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_c_turma"])){ 
       $sql  .= $virgula." ed99_c_turma = '$this->ed99_c_turma' ";
       $virgula = ",";
     }
     if(trim($this->ed99_i_anoref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_anoref"])){ 
       $sql  .= $virgula." ed99_i_anoref = $this->ed99_i_anoref ";
       $virgula = ",";
       if(trim($this->ed99_i_anoref) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "ed99_i_anoref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed99_i_periodoref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_periodoref"])){ 
        if(trim($this->ed99_i_periodoref)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_periodoref"])){ 
           $this->ed99_i_periodoref = "0" ; 
        } 
       $sql  .= $virgula." ed99_i_periodoref = $this->ed99_i_periodoref ";
       $virgula = ",";
     }
     if(trim($this->ed99_c_resultadofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_c_resultadofinal"])){ 
       $sql  .= $virgula." ed99_c_resultadofinal = '$this->ed99_c_resultadofinal' ";
       $virgula = ",";
       if(trim($this->ed99_c_resultadofinal) == null ){ 
         $this->erro_sql = " Campo Resultado Final não informado.";
         $this->erro_campo = "ed99_c_resultadofinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed99_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_c_situacao"])){ 
       $sql  .= $virgula." ed99_c_situacao = '$this->ed99_c_situacao' ";
       $virgula = ",";
       if(trim($this->ed99_c_situacao) == null ){ 
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "ed99_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed99_i_qtdch)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_qtdch"])){ 

        if ( trim($this->ed99_i_qtdch) == "" ) {
          $this->ed99_i_qtdch = "null";
        }
       $sql  .= $virgula." ed99_i_qtdch = $this->ed99_i_qtdch ";
       $virgula = ",";
     }

     if(trim($this->ed99_i_diasletivos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_diasletivos"])){ 
        if(trim($this->ed99_i_diasletivos)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_diasletivos"])){ 
           $this->ed99_i_diasletivos = "null" ; 
        } 
       $sql  .= $virgula." ed99_i_diasletivos = $this->ed99_i_diasletivos ";
       $virgula = ",";
     }
     if(trim($this->ed99_c_minimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_c_minimo"])){ 
       $sql  .= $virgula." ed99_c_minimo = '$this->ed99_c_minimo' ";
       $virgula = ",";
     }
     if(trim($this->ed99_c_termofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_c_termofinal"])){ 
       $sql  .= $virgula." ed99_c_termofinal = '$this->ed99_c_termofinal' ";
       $virgula = ",";
     }
     if(trim($this->ed99_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed99_observacao"])){ 
       $sql  .= $virgula." ed99_observacao = '$this->ed99_observacao' ";
       $virgula = ",";
     }

     if( isset($GLOBALS["HTTP_POST_VARS"]["ed99_percentualfrequencia"]) ){

       if($this->ed99_percentualfrequencia == null ){
         $this->ed99_percentualfrequencia = "null";
       }
       $sql  .= $virgula." ed99_percentualfrequencia = $this->ed99_percentualfrequencia ";
       $virgula = ",";
     }

     $sql .= " where ";
     if($ed99_i_codigo!=null){
       $sql .= " ed99_i_codigo = $this->ed99_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed99_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1009008,'$this->ed99_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_codigo"]) || $this->ed99_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010157,1009008,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_i_codigo'))."','$this->ed99_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_historico"]) || $this->ed99_i_historico != "")
             $resac = db_query("insert into db_acount values($acount,1010157,1009009,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_i_historico'))."','$this->ed99_i_historico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_escolaproc"]) || $this->ed99_i_escolaproc != "")
             $resac = db_query("insert into db_acount values($acount,1010157,1009010,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_i_escolaproc'))."','$this->ed99_i_escolaproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_serie"]) || $this->ed99_i_serie != "")
             $resac = db_query("insert into db_acount values($acount,1010157,1009011,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_i_serie'))."','$this->ed99_i_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_justificativa"]) || $this->ed99_i_justificativa != "")
             $resac = db_query("insert into db_acount values($acount,1010157,1009012,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_i_justificativa'))."','$this->ed99_i_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_c_turma"]) || $this->ed99_c_turma != "")
             $resac = db_query("insert into db_acount values($acount,1010157,1009013,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_c_turma'))."','$this->ed99_c_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_anoref"]) || $this->ed99_i_anoref != "")
             $resac = db_query("insert into db_acount values($acount,1010157,1009014,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_i_anoref'))."','$this->ed99_i_anoref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_periodoref"]) || $this->ed99_i_periodoref != "")
             $resac = db_query("insert into db_acount values($acount,1010157,1009015,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_i_periodoref'))."','$this->ed99_i_periodoref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_c_resultadofinal"]) || $this->ed99_c_resultadofinal != "")
             $resac = db_query("insert into db_acount values($acount,1010157,1009016,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_c_resultadofinal'))."','$this->ed99_c_resultadofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_c_situacao"]) || $this->ed99_c_situacao != "")
             $resac = db_query("insert into db_acount values($acount,1010157,1009017,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_c_situacao'))."','$this->ed99_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_qtdch"]) || $this->ed99_i_qtdch != "")
             $resac = db_query("insert into db_acount values($acount,1010157,1009018,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_i_qtdch'))."','$this->ed99_i_qtdch',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_i_diasletivos"]) || $this->ed99_i_diasletivos != "")
             $resac = db_query("insert into db_acount values($acount,1010157,1009019,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_i_diasletivos'))."','$this->ed99_i_diasletivos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_c_minimo"]) || $this->ed99_c_minimo != "")
             $resac = db_query("insert into db_acount values($acount,1010157,14633,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_c_minimo'))."','$this->ed99_c_minimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_c_termofinal"]) || $this->ed99_c_termofinal != "")
             $resac = db_query("insert into db_acount values($acount,1010157,19693,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_c_termofinal'))."','$this->ed99_c_termofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_observacao"]) || $this->ed99_observacao != "")
             $resac = db_query("insert into db_acount values($acount,1010157,20370,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_observacao'))."','$this->ed99_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed99_percentualfrequencia"]) || $this->ed99_percentualfrequencia != "")
             $resac = db_query("insert into db_acount values($acount,1010157,20819,'".AddSlashes(pg_result($resaco,$conresaco,'ed99_percentualfrequencia'))."','$this->ed99_percentualfrequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de séries do histórico nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed99_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de séries do histórico nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed99_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed99_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed99_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed99_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1009008,'$ed99_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010157,1009008,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,1009009,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_i_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,1009010,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_i_escolaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,1009011,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,1009012,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_i_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,1009013,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_c_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,1009014,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_i_anoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,1009015,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_i_periodoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,1009016,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,1009017,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,1009018,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_i_qtdch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,1009019,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_i_diasletivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,14633,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_c_minimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,19693,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_c_termofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,20370,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010157,20819,'','".AddSlashes(pg_result($resaco,$iresaco,'ed99_percentualfrequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from historicompsfora
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed99_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed99_i_codigo = $ed99_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de séries do histórico nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed99_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de séries do histórico nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed99_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed99_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:historicompsfora";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed99_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from historicompsfora ";
     $sql .= "      left join justificativa  on  justificativa.ed06_i_codigo = historicompsfora.ed99_i_justificativa";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = historicompsfora.ed99_i_serie";
     $sql .= "      inner join historico  on  historico.ed61_i_codigo = historicompsfora.ed99_i_historico";
     $sql .= "      inner join escolaproc  on  escolaproc.ed82_i_codigo = historicompsfora.ed99_i_escolaproc";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = historico.ed61_i_escola";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = historico.ed61_i_curso";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = historico.ed61_i_aluno";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed99_i_codigo)) {
         $sql2 .= " where historicompsfora.ed99_i_codigo = $ed99_i_codigo "; 
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
   public function sql_query_file ($ed99_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from historicompsfora ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed99_i_codigo)){
         $sql2 .= " where historicompsfora.ed99_i_codigo = $ed99_i_codigo "; 
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
  
  	$sSql .= " FROM historicompsfora ";
  	$sSql .= "   inner join serie on ed11_i_codigo = ed99_i_serie ";
  	$sSql .= "   inner join escolaproc  on  ed82_i_codigo = ed99_i_escolaproc ";
  	$sSql .= "   left join censouf  on  censouf.ed260_i_codigo = escolaproc.ed82_i_censouf ";
  	$sSql .= "   left join censomunic  on  censomunic.ed261_i_codigo = escolaproc.ed82_i_censomunic ";
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
}
