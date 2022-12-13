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
//CLASSE DA ENTIDADE turma
class cl_turma {
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
   var $ed57_i_codigo = 0;
   var $ed57_i_escola = 0;
   var $ed57_i_calendario = 0;
   var $ed57_c_descr = null;
   var $ed57_i_base = 0;
   var $ed57_i_turno = 0;
   var $ed57_i_sala = 0;
   var $ed57_c_medfreq = null;
   var $ed57_t_obs = null;
   var $ed57_i_codigoinep = 0;
   var $ed57_i_tipoatend = 0;
   var $ed57_i_ativqtd = 0;
   var $ed57_i_censocursoprofiss = 0;
   var $ed57_i_censoetapa = 0;
   var $ed57_i_tipoturma = 0;
   var $ed57_censoprogramamaiseducacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed57_i_codigo = int8 = Código
                 ed57_i_escola = int8 = Escola
                 ed57_i_calendario = int8 = Calendário
                 ed57_c_descr = char(80) = Nome da Turma
                 ed57_i_base = int8 = Base Curricular
                 ed57_i_turno = int8 = Turno
                 ed57_i_sala = int8 = Dependência
                 ed57_c_medfreq = char(15) = Frequência
                 ed57_t_obs = text = Observações
                 ed57_i_codigoinep = int4 = Código INEP
                 ed57_i_tipoatend = int4 = Tipo de Atendimento
                 ed57_i_ativqtd = int4 = Qtde. de vezes da Atividade Complementar
                 ed57_i_censocursoprofiss = int4 = Curso Profissionalizante
                 ed57_i_censoetapa = int8 = Etapa Censo
                 ed57_i_tipoturma = int4 = Tipo
                 ed57_censoprogramamaiseducacao = bool = Programa Mais Educação
                 ";
   //funcao construtor da classe
   function cl_turma() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turma");
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
       $this->ed57_i_codigo = ($this->ed57_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_codigo"]:$this->ed57_i_codigo);
       $this->ed57_i_escola = ($this->ed57_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_escola"]:$this->ed57_i_escola);
       $this->ed57_i_calendario = ($this->ed57_i_calendario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_calendario"]:$this->ed57_i_calendario);
       $this->ed57_c_descr = ($this->ed57_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_c_descr"]:$this->ed57_c_descr);
       $this->ed57_i_base = ($this->ed57_i_base == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_base"]:$this->ed57_i_base);
       $this->ed57_i_turno = ($this->ed57_i_turno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_turno"]:$this->ed57_i_turno);
       $this->ed57_i_sala = ($this->ed57_i_sala == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_sala"]:$this->ed57_i_sala);
       $this->ed57_c_medfreq = ($this->ed57_c_medfreq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_c_medfreq"]:$this->ed57_c_medfreq);
       $this->ed57_t_obs = ($this->ed57_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_t_obs"]:$this->ed57_t_obs);
       $this->ed57_i_codigoinep = ($this->ed57_i_codigoinep == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_codigoinep"]:$this->ed57_i_codigoinep);
       $this->ed57_i_tipoatend = ($this->ed57_i_tipoatend == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_tipoatend"]:$this->ed57_i_tipoatend);
       $this->ed57_i_ativqtd = ($this->ed57_i_ativqtd == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_ativqtd"]:$this->ed57_i_ativqtd);
       $this->ed57_i_censocursoprofiss = ($this->ed57_i_censocursoprofiss == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_censocursoprofiss"]:$this->ed57_i_censocursoprofiss);
       $this->ed57_i_censoetapa = ($this->ed57_i_censoetapa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_censoetapa"]:$this->ed57_i_censoetapa);
       $this->ed57_i_tipoturma = ($this->ed57_i_tipoturma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_tipoturma"]:$this->ed57_i_tipoturma);
       $this->ed57_censoprogramamaiseducacao = ($this->ed57_censoprogramamaiseducacao == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed57_censoprogramamaiseducacao"]:$this->ed57_censoprogramamaiseducacao);
     }else{
       $this->ed57_i_codigo = ($this->ed57_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed57_i_codigo"]:$this->ed57_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ed57_i_codigo){
      $this->atualizacampos();
     if($this->ed57_i_escola == null ){
       $this->erro_sql = " Campo Escola não informado.";
       $this->erro_campo = "ed57_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed57_i_calendario == null ){
       $this->erro_sql = " Campo Calendário não informado.";
       $this->erro_campo = "ed57_i_calendario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed57_c_descr == null ){
       $this->erro_sql = " Campo Nome da Turma não informado.";
       $this->erro_campo = "ed57_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed57_i_base == null ){
       $this->erro_sql = " Campo Base Curricular não informado.";
       $this->erro_campo = "ed57_i_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed57_i_turno == null ){
       $this->erro_sql = " Campo Turno não informado.";
       $this->erro_campo = "ed57_i_turno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed57_i_sala == null ){
       $this->erro_sql = " Campo Dependência não informado.";
       $this->erro_campo = "ed57_i_sala";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed57_c_medfreq == null ){
       $this->erro_sql = " Campo Frequência não informado.";
       $this->erro_campo = "ed57_c_medfreq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed57_i_codigoinep == null ){
       $this->ed57_i_codigoinep = "null";
     }
     if($this->ed57_i_tipoatend == null ){
       $this->erro_sql = " Campo Tipo de Atendimento não informado.";
       $this->erro_campo = "ed57_i_tipoatend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed57_i_ativqtd == null ){
       $this->ed57_i_ativqtd = "null";
     }
     if($this->ed57_i_censocursoprofiss == null ){
       $this->ed57_i_censocursoprofiss = "null";
     }

     $this->ed57_i_censoetapa = 'null';

     if($this->ed57_i_tipoturma == null ){
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "ed57_i_tipoturma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed57_censoprogramamaiseducacao != '' ){
       $this->ed57_censoprogramamaiseducacao = "'{$this->ed57_censoprogramamaiseducacao}'";
     }
     if($this->ed57_censoprogramamaiseducacao == null ){
       $this->ed57_censoprogramamaiseducacao = 'null';
     }
     if($ed57_i_codigo == "" || $ed57_i_codigo == null ){
       $result = db_query("select nextval('turma_ed57_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turma_ed57_i_codigo_seq do campo: ed57_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed57_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from turma_ed57_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed57_i_codigo)){
         $this->erro_sql = " Campo ed57_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed57_i_codigo = $ed57_i_codigo;
       }
     }
     if(($this->ed57_i_codigo == null) || ($this->ed57_i_codigo == "") ){
       $this->erro_sql = " Campo ed57_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turma(
                                       ed57_i_codigo
                                      ,ed57_i_escola
                                      ,ed57_i_calendario
                                      ,ed57_c_descr
                                      ,ed57_i_base
                                      ,ed57_i_turno
                                      ,ed57_i_sala
                                      ,ed57_c_medfreq
                                      ,ed57_t_obs
                                      ,ed57_i_codigoinep
                                      ,ed57_i_tipoatend
                                      ,ed57_i_ativqtd
                                      ,ed57_i_censocursoprofiss
                                      ,ed57_i_censoetapa
                                      ,ed57_i_tipoturma
                                      ,ed57_censoprogramamaiseducacao
                       )
                values (
                                $this->ed57_i_codigo
                               ,$this->ed57_i_escola
                               ,$this->ed57_i_calendario
                               ,'$this->ed57_c_descr'
                               ,$this->ed57_i_base
                               ,$this->ed57_i_turno
                               ,$this->ed57_i_sala
                               ,'$this->ed57_c_medfreq'
                               ,'$this->ed57_t_obs'
                               ,$this->ed57_i_codigoinep
                               ,$this->ed57_i_tipoatend
                               ,$this->ed57_i_ativqtd
                               ,$this->ed57_i_censocursoprofiss
                               ,$this->ed57_i_censoetapa
                               ,$this->ed57_i_tipoturma
                               ,$this->ed57_censoprogramamaiseducacao
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Turmas ($this->ed57_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Turmas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Turmas ($this->ed57_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed57_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed57_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008483,'$this->ed57_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010083,1008483,'','".AddSlashes(pg_result($resaco,0,'ed57_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,1008484,'','".AddSlashes(pg_result($resaco,0,'ed57_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,1008485,'','".AddSlashes(pg_result($resaco,0,'ed57_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,1008491,'','".AddSlashes(pg_result($resaco,0,'ed57_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,1008486,'','".AddSlashes(pg_result($resaco,0,'ed57_i_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,1008488,'','".AddSlashes(pg_result($resaco,0,'ed57_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,1008490,'','".AddSlashes(pg_result($resaco,0,'ed57_i_sala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,1008494,'','".AddSlashes(pg_result($resaco,0,'ed57_c_medfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,1008495,'','".AddSlashes(pg_result($resaco,0,'ed57_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,13787,'','".AddSlashes(pg_result($resaco,0,'ed57_i_codigoinep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,13788,'','".AddSlashes(pg_result($resaco,0,'ed57_i_tipoatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,13789,'','".AddSlashes(pg_result($resaco,0,'ed57_i_ativqtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,13790,'','".AddSlashes(pg_result($resaco,0,'ed57_i_censocursoprofiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,15221,'','".AddSlashes(pg_result($resaco,0,'ed57_i_censoetapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,15220,'','".AddSlashes(pg_result($resaco,0,'ed57_i_tipoturma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010083,18920,'','".AddSlashes(pg_result($resaco,0,'ed57_censoprogramamaiseducacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed57_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update turma set ";
     $virgula = "";
     if(trim($this->ed57_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_codigo"])){
       $sql  .= $virgula." ed57_i_codigo = $this->ed57_i_codigo ";
       $virgula = ",";
       if(trim($this->ed57_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed57_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed57_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_escola"])){
       $sql  .= $virgula." ed57_i_escola = $this->ed57_i_escola ";
       $virgula = ",";
       if(trim($this->ed57_i_escola) == null ){
         $this->erro_sql = " Campo Escola não informado.";
         $this->erro_campo = "ed57_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed57_i_calendario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_calendario"])){
       $sql  .= $virgula." ed57_i_calendario = $this->ed57_i_calendario ";
       $virgula = ",";
       if(trim($this->ed57_i_calendario) == null ){
         $this->erro_sql = " Campo Calendário não informado.";
         $this->erro_campo = "ed57_i_calendario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed57_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_c_descr"])){
       $sql  .= $virgula." ed57_c_descr = '$this->ed57_c_descr' ";
       $virgula = ",";
       if(trim($this->ed57_c_descr) == null ){
         $this->erro_sql = " Campo Nome da Turma não informado.";
         $this->erro_campo = "ed57_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed57_i_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_base"])){
       $sql  .= $virgula." ed57_i_base = $this->ed57_i_base ";
       $virgula = ",";
       if(trim($this->ed57_i_base) == null ){
         $this->erro_sql = " Campo Base Curricular não informado.";
         $this->erro_campo = "ed57_i_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed57_i_turno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_turno"])){
       $sql  .= $virgula." ed57_i_turno = $this->ed57_i_turno ";
       $virgula = ",";
       if(trim($this->ed57_i_turno) == null ){
         $this->erro_sql = " Campo Turno não informado.";
         $this->erro_campo = "ed57_i_turno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed57_i_sala)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_sala"])){
       $sql  .= $virgula." ed57_i_sala = $this->ed57_i_sala ";
       $virgula = ",";
       if(trim($this->ed57_i_sala) == null ){
         $this->erro_sql = " Campo Dependência não informado.";
         $this->erro_campo = "ed57_i_sala";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed57_c_medfreq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_c_medfreq"])){
       $sql  .= $virgula." ed57_c_medfreq = '$this->ed57_c_medfreq' ";
       $virgula = ",";
       if(trim($this->ed57_c_medfreq) == null ){
         $this->erro_sql = " Campo Frequência não informado.";
         $this->erro_campo = "ed57_c_medfreq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed57_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_t_obs"])){
       $sql  .= $virgula." ed57_t_obs = '$this->ed57_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed57_i_codigoinep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_codigoinep"])){
        if(trim($this->ed57_i_codigoinep)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_codigoinep"])){
           $this->ed57_i_codigoinep = "null" ;
        }
       $sql  .= $virgula." ed57_i_codigoinep = $this->ed57_i_codigoinep ";
       $virgula = ",";
     }
     if(trim($this->ed57_i_tipoatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_tipoatend"])){
       $sql  .= $virgula." ed57_i_tipoatend = $this->ed57_i_tipoatend ";
       $virgula = ",";
       if(trim($this->ed57_i_tipoatend) == null ){
         $this->erro_sql = " Campo Tipo de Atendimento não informado.";
         $this->erro_campo = "ed57_i_tipoatend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed57_i_ativqtd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_ativqtd"])){
        if(trim($this->ed57_i_ativqtd)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_ativqtd"])){
           $this->ed57_i_ativqtd = "null" ;
        }
       $sql  .= $virgula." ed57_i_ativqtd = $this->ed57_i_ativqtd ";
       $virgula = ",";
     }
     if(trim($this->ed57_i_censocursoprofiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_censocursoprofiss"])){
        if(trim($this->ed57_i_censocursoprofiss)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_censocursoprofiss"])){
           $this->ed57_i_censocursoprofiss = "null" ;
        }
       $sql  .= $virgula." ed57_i_censocursoprofiss = $this->ed57_i_censocursoprofiss ";
       $virgula = ",";
     }

     if(trim($this->ed57_i_tipoturma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_tipoturma"])){
       $sql  .= $virgula." ed57_i_tipoturma = $this->ed57_i_tipoturma ";
       $virgula = ",";
       if(trim($this->ed57_i_tipoturma) == null ){
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "ed57_i_tipoturma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if(trim($this->ed57_censoprogramamaiseducacao) != "") {

     	 $sql  .= $virgula." ed57_censoprogramamaiseducacao = '$this->ed57_censoprogramamaiseducacao' ";
       $virgula = ",";
     } else {

     	$sql  .= $virgula." ed57_censoprogramamaiseducacao = null ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed57_i_codigo!=null){
       $sql .= " ed57_i_codigo = $this->ed57_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed57_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008483,'$this->ed57_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_codigo"]) || $this->ed57_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010083,1008483,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_i_codigo'))."','$this->ed57_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_escola"]) || $this->ed57_i_escola != "")
             $resac = db_query("insert into db_acount values($acount,1010083,1008484,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_i_escola'))."','$this->ed57_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_calendario"]) || $this->ed57_i_calendario != "")
             $resac = db_query("insert into db_acount values($acount,1010083,1008485,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_i_calendario'))."','$this->ed57_i_calendario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_c_descr"]) || $this->ed57_c_descr != "")
             $resac = db_query("insert into db_acount values($acount,1010083,1008491,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_c_descr'))."','$this->ed57_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_base"]) || $this->ed57_i_base != "")
             $resac = db_query("insert into db_acount values($acount,1010083,1008486,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_i_base'))."','$this->ed57_i_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_turno"]) || $this->ed57_i_turno != "")
             $resac = db_query("insert into db_acount values($acount,1010083,1008488,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_i_turno'))."','$this->ed57_i_turno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_sala"]) || $this->ed57_i_sala != "")
             $resac = db_query("insert into db_acount values($acount,1010083,1008490,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_i_sala'))."','$this->ed57_i_sala',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_c_medfreq"]) || $this->ed57_c_medfreq != "")
             $resac = db_query("insert into db_acount values($acount,1010083,1008494,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_c_medfreq'))."','$this->ed57_c_medfreq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_t_obs"]) || $this->ed57_t_obs != "")
             $resac = db_query("insert into db_acount values($acount,1010083,1008495,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_t_obs'))."','$this->ed57_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_codigoinep"]) || $this->ed57_i_codigoinep != "")
             $resac = db_query("insert into db_acount values($acount,1010083,13787,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_i_codigoinep'))."','$this->ed57_i_codigoinep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_tipoatend"]) || $this->ed57_i_tipoatend != "")
             $resac = db_query("insert into db_acount values($acount,1010083,13788,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_i_tipoatend'))."','$this->ed57_i_tipoatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_ativqtd"]) || $this->ed57_i_ativqtd != "")
             $resac = db_query("insert into db_acount values($acount,1010083,13789,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_i_ativqtd'))."','$this->ed57_i_ativqtd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_censocursoprofiss"]) || $this->ed57_i_censocursoprofiss != "")
             $resac = db_query("insert into db_acount values($acount,1010083,13790,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_i_censocursoprofiss'))."','$this->ed57_i_censocursoprofiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_censoetapa"]) || $this->ed57_i_censoetapa != "")
             $resac = db_query("insert into db_acount values($acount,1010083,15221,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_i_censoetapa'))."','$this->ed57_i_censoetapa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_i_tipoturma"]) || $this->ed57_i_tipoturma != "")
             $resac = db_query("insert into db_acount values($acount,1010083,15220,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_i_tipoturma'))."','$this->ed57_i_tipoturma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed57_censoprogramamaiseducacao"]) || $this->ed57_censoprogramamaiseducacao != "")
             $resac = db_query("insert into db_acount values($acount,1010083,18920,'".AddSlashes(pg_result($resaco,$conresaco,'ed57_censoprogramamaiseducacao'))."','$this->ed57_censoprogramamaiseducacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Turmas não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed57_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Turmas não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed57_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed57_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed57_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed57_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008483,'$ed57_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010083,1008483,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,1008484,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,1008485,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,1008491,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,1008486,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_i_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,1008488,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,1008490,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_i_sala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,1008494,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_c_medfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,1008495,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,13787,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_i_codigoinep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,13788,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_i_tipoatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,13789,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_i_ativqtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,13790,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_i_censocursoprofiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,15221,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_i_censoetapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,15220,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_i_tipoturma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010083,18920,'','".AddSlashes(pg_result($resaco,$iresaco,'ed57_censoprogramamaiseducacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from turma
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed57_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed57_i_codigo = $ed57_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Turmas não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed57_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Turmas não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed57_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed57_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:turma";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed57_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from turma ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      inner join regimemat  on  regimemat.ed218_i_codigo = base.ed31_i_regimemat";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join tiposala  on  tiposala.ed14_i_codigo = sala.ed16_i_tiposala";
     $sql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql .= "      left join censocursoprofiss  on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss";
     $sql .= "      inner join turmacensoetapa  on  turmacensoetapa.ed132_turma = turma.ed57_i_codigo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed57_i_codigo)) {
         $sql2 .= " where turma.ed57_i_codigo = $ed57_i_codigo ";
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
   public function sql_query_file ($ed57_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from turma ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed57_i_codigo)){
         $sql2 .= " where turma.ed57_i_codigo = $ed57_i_codigo ";
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

   function sql_query_turmaserie_regencia($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from turma ";
    $sSql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
    $sSql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
    $sSql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
    $sSql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
    $sSql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
    $sSql .= "      inner join regimemat  on  regimemat.ed218_i_codigo = base.ed31_i_regimemat";
    $sSql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
    $sSql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
    $sSql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
    $sSql .= "      inner join tiposala  on  tiposala.ed14_i_codigo = sala.ed16_i_tiposala";
    $sSql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
    $sSql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
    $sSql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
    $sSql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
    $sSql .= "      inner join turmaserieregimemat  on  turmaserieregimemat.ed220_i_turma = turma.ed57_i_codigo";
    $sSql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turmaserieregimemat.ed220_i_procedimento";
    $sSql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procedimento.ed40_i_formaavaliacao";
    $sSql .= "      inner join serieregimemat  on  serieregimemat.ed223_i_codigo = turmaserieregimemat.ed220_i_serieregimemat";
    $sSql .= "      inner join serie  on  serie.ed11_i_codigo = serieregimemat.ed223_i_serie";
    $sSql .= "      left  join censocursoprofiss  on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss";
    $sSql .= "      inner join turmacensoetapa on turmacensoetapa.ed132_turma = turma.ed57_i_codigo";
    $sSql .= '      inner join regencia on ed59_i_turma = ed57_i_codigo ';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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
   function sql_query_turma($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= ' from turma ';
    $sSql .= '   inner join escola on escola.ed18_i_codigo = turma.ed57_i_escola ';
    $sSql .= '   inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario ';
    $sSql .= '   inner join base on base.ed31_i_codigo = turma.ed57_i_base ';
    $sSql .= '   inner join cursoedu on cursoedu.ed29_i_codigo = base.ed31_i_curso ';
    $sSql .= '   inner join ensino on ensino.ed10_i_codigo = cursoedu.ed29_i_ensino ';
    $sSql .= '   inner join turmaserieregimemat on turmaserieregimemat.ed220_i_turma = turma.ed57_i_codigo  ';
    $sSql .= '   inner join serieregimemat on ';
    $sSql .= '     serieregimemat.ed223_i_codigo = turmaserieregimemat.ed220_i_serieregimemat ';
    $sSql .= '   inner join serie on serie.ed11_i_codigo = serieregimemat.ed223_i_serie ';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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
   function sql_query_censo($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from turma ";
    $sSql .= "      inner join calendario on ed52_i_codigo = ed57_i_calendario ";
    $sSql .= "      inner join escola on ed18_i_codigo = ed57_i_escola ";
    $sSql .= "      inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ";
    $sSql .= "      inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ";
    $sSql .= "      inner join serie on ed11_i_codigo = ed223_i_serie ";
    $sSql .= "      inner join ensino on ed10_i_codigo = ed11_i_ensino ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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
   function sql_query_turmaserie($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from turma ";
    $sSql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
    $sSql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
    $sSql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
    $sSql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
    $sSql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
    $sSql .= "      inner join regimemat  on  regimemat.ed218_i_codigo = base.ed31_i_regimemat";
    $sSql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
    $sSql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
    $sSql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
    $sSql .= "      inner join tiposala  on  tiposala.ed14_i_codigo = sala.ed16_i_tiposala";
    $sSql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
    $sSql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
    $sSql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
    $sSql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
    $sSql .= "      inner join turmaserieregimemat  on  turmaserieregimemat.ed220_i_turma = turma.ed57_i_codigo";
    $sSql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turmaserieregimemat.ed220_i_procedimento";
    $sSql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procedimento.ed40_i_formaavaliacao";
    $sSql .= "      inner join serieregimemat  on  serieregimemat.ed223_i_codigo = turmaserieregimemat.ed220_i_serieregimemat";
    $sSql .= "      inner join serie  on  serie.ed11_i_codigo = serieregimemat.ed223_i_serie";
    $sSql .= "      inner join turmacensoetapa on turmacensoetapa.ed132_turma = turma.ed57_i_codigo";
    $sSql .= "      left  join censocursoprofiss  on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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
   function sql_query_relatorioanual($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

    $sSql .= ' from turma ';
    $sSql .= "  inner join turmaserieregimemat on turmaserieregimemat.ed220_i_turma = turma.ed57_i_codigo ";
    $sSql .= "  inner join serieregimemat on serieregimemat.ed223_i_codigo = turmaserieregimemat.ed220_i_serieregimemat ";
    $sSql .= "  inner join serie on serie.ed11_i_codigo = serieregimemat.ed223_i_serie ";
    $sSql .= "  inner join base on base.ed31_i_codigo = turma.ed57_i_base ";
    $sSql .= "  inner join ensino on ensino.ed10_i_codigo = serie.ed11_i_ensino ";
    $sSql .= "  inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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
   function sql_query_boletimestat($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

    $sSql .= ' from turma ';
    $sSql .= "      inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ";
    $sSql .= "      inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ";
    $sSql .= "      inner join serie on ed11_i_codigo = ed223_i_serie ";
    $sSql .= "      inner join ensino on ed10_i_codigo = ed11_i_ensino ";
    $sSql .= "      inner join turno on ed15_i_codigo = ed57_i_turno ";
    $sSql .= "      inner join calendario on ed52_i_codigo = ed57_i_calendario ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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
   function sql_query_ensino($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

    $sSql .= ' from turma ';
    $sSql .= '      inner join matricula on ed60_i_turma = ed57_i_codigo ';
    $sSql .= '      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo';
    $sSql .= '      inner join serie on ed11_i_codigo = ed221_i_serie';
    $sSql .= '      inner join ensino on ed10_i_codigo = ed11_i_ensino';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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
   function sql_query_periodoavaliacao($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

    $sSql .= ' from turma ';
    $sSql .= '      inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ';
    $sSql .= '      inner join procedimento on ed40_i_codigo = ed220_i_procedimento ';
    $sSql .= '      inner join procavaliacao on ed41_i_procedimento = ed40_i_codigo ';
    $sSql .= '      inner join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao ';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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
   function sql_query_relatorio($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

    $sSql .= ' from turma ';
    $sSql .= ' inner join matricula on ed60_i_turma = ed57_i_codigo ';
    $sSql .= ' inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ';
    $sSql .= ' inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ';
    $sSql .= ' inner join serie on ed11_i_codigo = ed223_i_serie ';
    $sSql .= ' inner join calendario on ed57_i_calendario = ed52_i_codigo';
    $sSql .= ' inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ';
    $sSql .= '                              and ed221_i_serie = ed223_i_serie ';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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
   function sql_query_diarioclasse($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

    $sSql .= ' from turma ';
    $sSql .= '      inner join matricula on ed60_i_turma = ed57_i_codigo ';
    $sSql .= '      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo';
    $sSql .= '      inner join serie on ed11_i_codigo = ed221_i_serie';
    $sSql .= '      inner join base on ed31_i_codigo = ed57_i_base';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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
   function sql_query_turma_progressao_parcial($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

    $sSql .= ' from turma ';
    $sSql .= '      inner join regencia                            on regencia.ed59_i_turma = turma.ed57_i_codigo ';
    $sSql .= '      inner join progressaoparcialalunoturmaregencia on ed115_regencia        = regencia.ed59_i_codigo';
    $sSql .= '      inner join calendario                          on ed52_i_codigo         = ed57_i_calendario';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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
  function sql_query_atafinal($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

    $sSql .= ' from turma ';
    $sSql .= '      inner join matricula on ed60_i_turma = ed57_i_codigo ';
    $sSql .= '      inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ';
    $sSql .= '      inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ';
    $sSql .= '      inner join serie on ed11_i_codigo = ed223_i_serie ';
    $sSql .= '      inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ';
    $sSql .= '                              and ed221_i_serie = ed223_i_serie ';
    $sSql .= '      inner join regencia on ed59_i_turma = ed57_i_codigo ';
    $sSql .= '                               and ed59_i_serie = ed223_i_serie ';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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

  function sql_query_turno( $iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '' ) {

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

    $sSql .= " from turma ";
    $sSql .= "      inner join escola         on escola.ed18_i_codigo         = turma.ed57_i_escola";
    $sSql .= "      inner join turno          on turno.ed15_i_codigo          = turma.ed57_i_turno";
    $sSql .= "      inner join turnoreferente on turnoreferente.ed231_i_turno = turno.ed15_i_codigo";
    $sSql .= "      inner join sala           on sala.ed16_i_codigo           = turma.ed57_i_sala";
    $sSql .= "      inner join calendario     on calendario.ed52_i_codigo     = turma.ed57_i_calendario";

    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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

  function sql_query_rechumano_hora_disponivel( $iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '' ) {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql     .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula  = ",";
      }
    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " from turma ";
    $sSql .= "      inner join escola          on ed18_i_codigo   = ed57_i_escola";
    $sSql .= "      inner join turno           on ed15_i_codigo   = ed57_i_turno";
    $sSql .= "      inner join periodoescola   on ed17_i_turno    = ed15_i_codigo";
    $sSql .= "      inner join periodoaula     on ed08_i_codigo   = ed17_i_periodoaula";
    $sSql .= "      inner join calendario      on ed52_i_codigo   = ed57_i_calendario";
    $sSql .= "      inner join regenciahorario on ed58_i_periodo  = ed17_i_codigo";
    $sSql .= "      inner join rechumano       on ed20_i_codigo   = ed58_i_rechumano";

    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turma.ed57_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
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

   // funcao do sql
   public function sql_query_turma_etapa_censo ($ed57_i_codigo = null, $ed266_ano = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from turma ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      inner join regimemat  on  regimemat.ed218_i_codigo = base.ed31_i_regimemat";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join tiposala  on  tiposala.ed14_i_codigo = sala.ed16_i_tiposala";
     $sql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql .= "      left join censocursoprofiss  on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss";
     $sql .= "      inner join turmacensoetapa  on  turmacensoetapa.ed132_turma = turma.ed57_i_codigo";
     $sql .= "      inner join censoetapa on (censoetapa.ed266_i_codigo = ed132_censoetapa and censoetapa.ed266_ano = ed132_ano)";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed57_i_codigo)) {
         $sql2 .= " where turma.ed57_i_codigo = $ed57_i_codigo ";
       }
       if ( !empty($ed266_ano) ) {
          if ( !empty($sql2) ) {
            $sql2 .= " and censoetapa.ed266_ano = {$ed266_ano}";
        } else {
            $sql2 .= " where censoetapa.ed266_ano = {$ed266_ano} ";
        }
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


  public function sql_query_turma_ensino ($ed10_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from turma ";
    $sql .= "   inner join calendario on ed52_i_codigo = ed57_i_calendario  ";
    $sql .= "   inner join base       on ed31_i_codigo = ed57_i_base ";
    $sql .= "   inner join cursoedu   on ed29_i_codigo = ed31_i_curso ";
    $sql .= "   inner join ensino     on ed10_i_codigo = ed29_i_ensino ";
    $sql2 = "";
    if (empty($dbwhere)) {

      if (!empty($ed10_i_codigo)){
        $sql2 .= " where ensino.ed10_i_codigo = $ed10_i_codigo ";
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

  /*PLUGIN DIARIO PROGRESSAO ALUNO - NÃO APAGAR*/
}
