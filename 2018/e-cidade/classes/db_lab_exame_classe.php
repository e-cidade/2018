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
//MODULO: laboratorio
//CLASSE DA ENTIDADE lab_exame
class cl_lab_exame {
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
   var $la08_i_codigo = 0;
   var $la08_c_sigla = null;
   var $la08_c_descr = null;
   var $la08_i_idademax = 0;
   var $la08_i_idademin = 0;
   var $la08_i_sexo = 0;
   var $la08_d_inicio_dia = null;
   var $la08_d_inicio_mes = null;
   var $la08_d_inicio_ano = null;
   var $la08_d_inicio = null;
   var $la08_d_fim_dia = null;
   var $la08_d_fim_mes = null;
   var $la08_d_fim_ano = null;
   var $la08_d_fim = null;
   var $la08_i_dias = 0;
   var $la08_t_interferencia = null;
   var $la08_i_gerar = 0;
   var $la08_i_undidadeini = 0;
   var $la08_i_undidadefim = 0;
   var $la08_i_ativo = 0;
   var $la08_observacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 la08_i_codigo = int4 = Código
                 la08_c_sigla = char(5) = Sigla
                 la08_c_descr = char(50) = Descrição
                 la08_i_idademax = int4 = Idade Máxima
                 la08_i_idademin = int4 = Idade Mínima
                 la08_i_sexo = int4 = Sexo
                 la08_d_inicio = date = Início
                 la08_d_fim = date = Fim
                 la08_i_dias = int4 = Dias para entregar
                 la08_t_interferencia = text = Interferência
                 la08_i_gerar = int4 = Gerar
                 la08_i_undidadeini = int4 = Unidade
                 la08_i_undidadefim = int4 = Unidade
                 la08_i_ativo = int4 = Situação
                 la08_observacao = text = Observação
                 ";
   //funcao construtor da classe
   function cl_lab_exame() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_exame");
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
       $this->la08_i_codigo = ($this->la08_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_i_codigo"]:$this->la08_i_codigo);
       $this->la08_c_sigla = ($this->la08_c_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_c_sigla"]:$this->la08_c_sigla);
       $this->la08_c_descr = ($this->la08_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_c_descr"]:$this->la08_c_descr);
       $this->la08_i_idademax = ($this->la08_i_idademax == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_i_idademax"]:$this->la08_i_idademax);
       $this->la08_i_idademin = ($this->la08_i_idademin == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_i_idademin"]:$this->la08_i_idademin);
       $this->la08_i_sexo = ($this->la08_i_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_i_sexo"]:$this->la08_i_sexo);
       if($this->la08_d_inicio == ""){
         $this->la08_d_inicio_dia = ($this->la08_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_d_inicio_dia"]:$this->la08_d_inicio_dia);
         $this->la08_d_inicio_mes = ($this->la08_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_d_inicio_mes"]:$this->la08_d_inicio_mes);
         $this->la08_d_inicio_ano = ($this->la08_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_d_inicio_ano"]:$this->la08_d_inicio_ano);
         if($this->la08_d_inicio_dia != ""){
            $this->la08_d_inicio = $this->la08_d_inicio_ano."-".$this->la08_d_inicio_mes."-".$this->la08_d_inicio_dia;
         }
       }
       if($this->la08_d_fim == ""){
         $this->la08_d_fim_dia = ($this->la08_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_d_fim_dia"]:$this->la08_d_fim_dia);
         $this->la08_d_fim_mes = ($this->la08_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_d_fim_mes"]:$this->la08_d_fim_mes);
         $this->la08_d_fim_ano = ($this->la08_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_d_fim_ano"]:$this->la08_d_fim_ano);
         if($this->la08_d_fim_dia != ""){
            $this->la08_d_fim = $this->la08_d_fim_ano."-".$this->la08_d_fim_mes."-".$this->la08_d_fim_dia;
         }
       }
       $this->la08_i_dias = ($this->la08_i_dias == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_i_dias"]:$this->la08_i_dias);
       $this->la08_t_interferencia = ($this->la08_t_interferencia == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_t_interferencia"]:$this->la08_t_interferencia);
       $this->la08_i_gerar = ($this->la08_i_gerar == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_i_gerar"]:$this->la08_i_gerar);
       $this->la08_i_undidadeini = ($this->la08_i_undidadeini == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_i_undidadeini"]:$this->la08_i_undidadeini);
       $this->la08_i_undidadefim = ($this->la08_i_undidadefim == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_i_undidadefim"]:$this->la08_i_undidadefim);
       $this->la08_i_ativo = ($this->la08_i_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_i_ativo"]:$this->la08_i_ativo);
       $this->la08_observacao = ($this->la08_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_observacao"]:$this->la08_observacao);
     }else{
       $this->la08_i_codigo = ($this->la08_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la08_i_codigo"]:$this->la08_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($la08_i_codigo){
      $this->atualizacampos();
     if($this->la08_c_sigla == null ){
       $this->erro_sql = " Campo Sigla não informado.";
       $this->erro_campo = "la08_c_sigla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la08_c_descr == null ){
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "la08_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la08_i_idademax == null ){
       $this->erro_sql = " Campo Idade Máxima não informado.";
       $this->erro_campo = "la08_i_idademax";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la08_i_idademin == null ){
       $this->erro_sql = " Campo Idade Mínima não informado.";
       $this->erro_campo = "la08_i_idademin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la08_i_sexo == null ){
       $this->erro_sql = " Campo Sexo não informado.";
       $this->erro_campo = "la08_i_sexo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la08_d_inicio == null ){
       $this->la08_d_inicio = "null";
     }
     if($this->la08_d_fim == null ){
       $this->la08_d_fim = "null";
     }
     if($this->la08_i_dias == null ){
       $this->erro_sql = " Campo Dias para entregar não informado.";
       $this->erro_campo = "la08_i_dias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la08_i_gerar == null ){
       $this->erro_sql = " Campo Gerar não informado.";
       $this->erro_campo = "la08_i_gerar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la08_i_undidadeini == null ){
       $this->erro_sql = " Campo Unidade não informado.";
       $this->erro_campo = "la08_i_undidadeini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la08_i_undidadefim == null ){
       $this->erro_sql = " Campo Unidade não informado.";
       $this->erro_campo = "la08_i_undidadefim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la08_i_ativo == null ){
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "la08_i_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la08_i_codigo == "" || $la08_i_codigo == null ){
       $result = db_query("select nextval('lab_exame_la08_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_exame_la08_i_codigo_seq do campo: la08_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->la08_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from lab_exame_la08_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la08_i_codigo)){
         $this->erro_sql = " Campo la08_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la08_i_codigo = $la08_i_codigo;
       }
     }
     if(($this->la08_i_codigo == null) || ($this->la08_i_codigo == "") ){
       $this->erro_sql = " Campo la08_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_exame(
                                       la08_i_codigo
                                      ,la08_c_sigla
                                      ,la08_c_descr
                                      ,la08_i_idademax
                                      ,la08_i_idademin
                                      ,la08_i_sexo
                                      ,la08_d_inicio
                                      ,la08_d_fim
                                      ,la08_i_dias
                                      ,la08_t_interferencia
                                      ,la08_i_gerar
                                      ,la08_i_undidadeini
                                      ,la08_i_undidadefim
                                      ,la08_i_ativo
                                      ,la08_observacao
                       )
                values (
                                $this->la08_i_codigo
                               ,'$this->la08_c_sigla'
                               ,'$this->la08_c_descr'
                               ,$this->la08_i_idademax
                               ,$this->la08_i_idademin
                               ,$this->la08_i_sexo
                               ,".($this->la08_d_inicio == "null" || $this->la08_d_inicio == ""?"null":"'".$this->la08_d_inicio."'")."
                               ,".($this->la08_d_fim == "null" || $this->la08_d_fim == ""?"null":"'".$this->la08_d_fim."'")."
                               ,$this->la08_i_dias
                               ,'$this->la08_t_interferencia'
                               ,$this->la08_i_gerar
                               ,$this->la08_i_undidadeini
                               ,$this->la08_i_undidadefim
                               ,$this->la08_i_ativo
                               ,'$this->la08_observacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_exame ($this->la08_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_exame já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_exame ($this->la08_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la08_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la08_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15737,'$this->la08_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2758,15737,'','".AddSlashes(pg_result($resaco,0,'la08_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,15738,'','".AddSlashes(pg_result($resaco,0,'la08_c_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,15739,'','".AddSlashes(pg_result($resaco,0,'la08_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,15740,'','".AddSlashes(pg_result($resaco,0,'la08_i_idademax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,15741,'','".AddSlashes(pg_result($resaco,0,'la08_i_idademin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,15742,'','".AddSlashes(pg_result($resaco,0,'la08_i_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,15743,'','".AddSlashes(pg_result($resaco,0,'la08_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,15744,'','".AddSlashes(pg_result($resaco,0,'la08_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,15745,'','".AddSlashes(pg_result($resaco,0,'la08_i_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,15746,'','".AddSlashes(pg_result($resaco,0,'la08_t_interferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,15748,'','".AddSlashes(pg_result($resaco,0,'la08_i_gerar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,16080,'','".AddSlashes(pg_result($resaco,0,'la08_i_undidadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,16081,'','".AddSlashes(pg_result($resaco,0,'la08_i_undidadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,17967,'','".AddSlashes(pg_result($resaco,0,'la08_i_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2758,21617,'','".AddSlashes(pg_result($resaco,0,'la08_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($la08_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update lab_exame set ";
     $virgula = "";
     if(trim($this->la08_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_i_codigo"])){
       $sql  .= $virgula." la08_i_codigo = $this->la08_i_codigo ";
       $virgula = ",";
       if(trim($this->la08_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "la08_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la08_c_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_c_sigla"])){
       $sql  .= $virgula." la08_c_sigla = '$this->la08_c_sigla' ";
       $virgula = ",";
       if(trim($this->la08_c_sigla) == null ){
         $this->erro_sql = " Campo Sigla não informado.";
         $this->erro_campo = "la08_c_sigla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la08_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_c_descr"])){
       $sql  .= $virgula." la08_c_descr = '$this->la08_c_descr' ";
       $virgula = ",";
       if(trim($this->la08_c_descr) == null ){
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "la08_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la08_i_idademax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_i_idademax"])){
       $sql  .= $virgula." la08_i_idademax = $this->la08_i_idademax ";
       $virgula = ",";
       if(trim($this->la08_i_idademax) == null ){
         $this->erro_sql = " Campo Idade Máxima não informado.";
         $this->erro_campo = "la08_i_idademax";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la08_i_idademin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_i_idademin"])){
       $sql  .= $virgula." la08_i_idademin = $this->la08_i_idademin ";
       $virgula = ",";
       if(trim($this->la08_i_idademin) == null ){
         $this->erro_sql = " Campo Idade Mínima não informado.";
         $this->erro_campo = "la08_i_idademin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la08_i_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_i_sexo"])){
       $sql  .= $virgula." la08_i_sexo = $this->la08_i_sexo ";
       $virgula = ",";
       if(trim($this->la08_i_sexo) == null ){
         $this->erro_sql = " Campo Sexo não informado.";
         $this->erro_campo = "la08_i_sexo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la08_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la08_d_inicio_dia"] !="") ){
       $sql  .= $virgula." la08_d_inicio = '$this->la08_d_inicio' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["la08_d_inicio_dia"])){
         $sql  .= $virgula." la08_d_inicio = null ";
         $virgula = ",";
       }
     }
     if(trim($this->la08_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la08_d_fim_dia"] !="") ){
       $sql  .= $virgula." la08_d_fim = '$this->la08_d_fim' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["la08_d_fim_dia"])){
         $sql  .= $virgula." la08_d_fim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->la08_i_dias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_i_dias"])){
       $sql  .= $virgula." la08_i_dias = $this->la08_i_dias ";
       $virgula = ",";
       if(trim($this->la08_i_dias) == null ){
         $this->erro_sql = " Campo Dias para entregar não informado.";
         $this->erro_campo = "la08_i_dias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la08_t_interferencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_t_interferencia"])){
       $sql  .= $virgula." la08_t_interferencia = '$this->la08_t_interferencia' ";
       $virgula = ",";
     }
     if(trim($this->la08_i_gerar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_i_gerar"])){
       $sql  .= $virgula." la08_i_gerar = $this->la08_i_gerar ";
       $virgula = ",";
       if(trim($this->la08_i_gerar) == null ){
         $this->erro_sql = " Campo Gerar não informado.";
         $this->erro_campo = "la08_i_gerar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la08_i_undidadeini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_i_undidadeini"])){
       $sql  .= $virgula." la08_i_undidadeini = $this->la08_i_undidadeini ";
       $virgula = ",";
       if(trim($this->la08_i_undidadeini) == null ){
         $this->erro_sql = " Campo Unidade não informado.";
         $this->erro_campo = "la08_i_undidadeini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la08_i_undidadefim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_i_undidadefim"])){
       $sql  .= $virgula." la08_i_undidadefim = $this->la08_i_undidadefim ";
       $virgula = ",";
       if(trim($this->la08_i_undidadefim) == null ){
         $this->erro_sql = " Campo Unidade não informado.";
         $this->erro_campo = "la08_i_undidadefim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la08_i_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_i_ativo"])){
       $sql  .= $virgula." la08_i_ativo = $this->la08_i_ativo ";
       $virgula = ",";
       if(trim($this->la08_i_ativo) == null ){
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "la08_i_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la08_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la08_observacao"])){
       $sql  .= $virgula." la08_observacao = '$this->la08_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($la08_i_codigo!=null){
       $sql .= " la08_i_codigo = $this->la08_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la08_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,15737,'$this->la08_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_i_codigo"]) || $this->la08_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2758,15737,'".AddSlashes(pg_result($resaco,$conresaco,'la08_i_codigo'))."','$this->la08_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_c_sigla"]) || $this->la08_c_sigla != "")
             $resac = db_query("insert into db_acount values($acount,2758,15738,'".AddSlashes(pg_result($resaco,$conresaco,'la08_c_sigla'))."','$this->la08_c_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_c_descr"]) || $this->la08_c_descr != "")
             $resac = db_query("insert into db_acount values($acount,2758,15739,'".AddSlashes(pg_result($resaco,$conresaco,'la08_c_descr'))."','$this->la08_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_i_idademax"]) || $this->la08_i_idademax != "")
             $resac = db_query("insert into db_acount values($acount,2758,15740,'".AddSlashes(pg_result($resaco,$conresaco,'la08_i_idademax'))."','$this->la08_i_idademax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_i_idademin"]) || $this->la08_i_idademin != "")
             $resac = db_query("insert into db_acount values($acount,2758,15741,'".AddSlashes(pg_result($resaco,$conresaco,'la08_i_idademin'))."','$this->la08_i_idademin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_i_sexo"]) || $this->la08_i_sexo != "")
             $resac = db_query("insert into db_acount values($acount,2758,15742,'".AddSlashes(pg_result($resaco,$conresaco,'la08_i_sexo'))."','$this->la08_i_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_d_inicio"]) || $this->la08_d_inicio != "")
             $resac = db_query("insert into db_acount values($acount,2758,15743,'".AddSlashes(pg_result($resaco,$conresaco,'la08_d_inicio'))."','$this->la08_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_d_fim"]) || $this->la08_d_fim != "")
             $resac = db_query("insert into db_acount values($acount,2758,15744,'".AddSlashes(pg_result($resaco,$conresaco,'la08_d_fim'))."','$this->la08_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_i_dias"]) || $this->la08_i_dias != "")
             $resac = db_query("insert into db_acount values($acount,2758,15745,'".AddSlashes(pg_result($resaco,$conresaco,'la08_i_dias'))."','$this->la08_i_dias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_t_interferencia"]) || $this->la08_t_interferencia != "")
             $resac = db_query("insert into db_acount values($acount,2758,15746,'".AddSlashes(pg_result($resaco,$conresaco,'la08_t_interferencia'))."','$this->la08_t_interferencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_i_gerar"]) || $this->la08_i_gerar != "")
             $resac = db_query("insert into db_acount values($acount,2758,15748,'".AddSlashes(pg_result($resaco,$conresaco,'la08_i_gerar'))."','$this->la08_i_gerar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_i_undidadeini"]) || $this->la08_i_undidadeini != "")
             $resac = db_query("insert into db_acount values($acount,2758,16080,'".AddSlashes(pg_result($resaco,$conresaco,'la08_i_undidadeini'))."','$this->la08_i_undidadeini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_i_undidadefim"]) || $this->la08_i_undidadefim != "")
             $resac = db_query("insert into db_acount values($acount,2758,16081,'".AddSlashes(pg_result($resaco,$conresaco,'la08_i_undidadefim'))."','$this->la08_i_undidadefim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_i_ativo"]) || $this->la08_i_ativo != "")
             $resac = db_query("insert into db_acount values($acount,2758,17967,'".AddSlashes(pg_result($resaco,$conresaco,'la08_i_ativo'))."','$this->la08_i_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la08_observacao"]) || $this->la08_observacao != "")
             $resac = db_query("insert into db_acount values($acount,2758,21617,'".AddSlashes(pg_result($resaco,$conresaco,'la08_observacao'))."','$this->la08_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_exame não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la08_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "lab_exame não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($la08_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($la08_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,15737,'$la08_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2758,15737,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,15738,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_c_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,15739,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,15740,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_i_idademax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,15741,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_i_idademin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,15742,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_i_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,15743,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,15744,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,15745,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_i_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,15746,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_t_interferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,15748,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_i_gerar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,16080,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_i_undidadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,16081,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_i_undidadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,17967,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_i_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2758,21617,'','".AddSlashes(pg_result($resaco,$iresaco,'la08_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from lab_exame
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($la08_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " la08_i_codigo = $la08_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_exame não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la08_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "lab_exame não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la08_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_exame";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($la08_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from lab_exame ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la08_i_codigo)) {
         $sql2 .= " where lab_exame.la08_i_codigo = $la08_i_codigo ";
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
   public function sql_query_file ($la08_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from lab_exame ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la08_i_codigo)){
         $sql2 .= " where lab_exame.la08_i_codigo = $la08_i_codigo ";
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

   function sql_query_procedimento ($la08_i_codigo=null, $campos="*", $ordem=null, $dbwhere="") {
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
     $sql .= " from lab_exame ";
     $sql .= "  inner join lab_exameproced on la08_i_codigo = la53_i_exame ";
     $sql .= "  inner join sau_procedimento on la53_i_procedimento = sd63_i_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($la08_i_codigo!=null ){
         $sql2 .= " where lab_exame.la08_i_codigo = $la08_i_codigo ";
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
  function sql_query_exame_atributo($la08_i_codigo=null, $campos="*", $ordem=null, $dbwhere="")  {

    $sql  = "select {$campos}";
    $sql .= "  from lab_exame ";
    $sql .= "       left join lab_exameatributo ON lab_exameatributo.la42_i_exame =  lab_exame.la08_i_codigo";
    $sql2 = "";
    if (empty($dbwhere)) {

      if (!empty($la08_i_codigo)) {
        $sql2 .= " where lab_exame.la08_i_codigo = $la08_i_codigo ";
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

  function sql_query_exame_laboratorio($la08_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql  = "select {$campos} ";
    $sql .= "  from lab_exame ";
    $sql .= "  join lab_setorexame  on lab_setorexame.la09_i_exame   = lab_exame.la08_i_codigo";
    $sql .= "  join lab_labsetor    on lab_labsetor.la24_i_codigo    = lab_setorexame.la09_i_labsetor ";
    $sql .= "  join lab_laboratorio on lab_laboratorio.la02_i_codigo = lab_labsetor.la24_i_laboratorio";

    if (empty($dbwhere)) {
       if (!empty($la08_i_codigo)){
         $sql2 .= " where lab_exame.la08_i_codigo = $la08_i_codigo ";
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
}
