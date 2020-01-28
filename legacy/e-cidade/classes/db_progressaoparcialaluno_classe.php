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
//CLASSE DA ENTIDADE progressaoparcialaluno
class cl_progressaoparcialaluno {
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
   var $ed114_sequencial = 0;
   var $ed114_disciplina = 0;
   var $ed114_aluno = 0;
   var $ed114_serie = 0;
   var $ed114_tipoconclusao = 0;
   var $ed114_ano = 0;
   var $ed114_escola = 0;
   var $ed114_situacaoeducacao = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed114_sequencial = int4 = Código
                 ed114_disciplina = int4 = Disciplina
                 ed114_aluno = int4 = Aluno
                 ed114_serie = int4 = Série
                 ed114_tipoconclusao = int4 = Tipo de Conclusão
                 ed114_ano = int4 = Ano
                 ed114_escola = int4 = Escola
                 ed114_situacaoeducacao = int4 = Situação da progressão
                 ";
   //funcao construtor da classe
   function cl_progressaoparcialaluno() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("progressaoparcialaluno");
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
       $this->ed114_sequencial = ($this->ed114_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed114_sequencial"]:$this->ed114_sequencial);
       $this->ed114_disciplina = ($this->ed114_disciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed114_disciplina"]:$this->ed114_disciplina);
       $this->ed114_aluno = ($this->ed114_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed114_aluno"]:$this->ed114_aluno);
       $this->ed114_serie = ($this->ed114_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed114_serie"]:$this->ed114_serie);
       $this->ed114_tipoconclusao = ($this->ed114_tipoconclusao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed114_tipoconclusao"]:$this->ed114_tipoconclusao);
       $this->ed114_ano = ($this->ed114_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed114_ano"]:$this->ed114_ano);
       $this->ed114_escola = ($this->ed114_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed114_escola"]:$this->ed114_escola);
       $this->ed114_situacaoeducacao = ($this->ed114_situacaoeducacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed114_situacaoeducacao"]:$this->ed114_situacaoeducacao);
     }else{
       $this->ed114_sequencial = ($this->ed114_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed114_sequencial"]:$this->ed114_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed114_sequencial){
      $this->atualizacampos();
     if($this->ed114_disciplina == null ){
       $this->erro_sql = " Campo Disciplina não informado.";
       $this->erro_campo = "ed114_disciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed114_aluno == null ){
       $this->erro_sql = " Campo Aluno não informado.";
       $this->erro_campo = "ed114_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed114_serie == null ){
       $this->erro_sql = " Campo Série não informado.";
       $this->erro_campo = "ed114_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed114_tipoconclusao == null ){
       $this->ed114_tipoconclusao = "NULL";
     }
     if($this->ed114_ano == null ){
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "ed114_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed114_escola == null ){
       $this->erro_sql = " Campo Escola não informado.";
       $this->erro_campo = "ed114_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed114_situacaoeducacao == null ){
       $this->erro_sql = " Campo Situação da progressão não informado.";
       $this->erro_campo = "ed114_situacaoeducacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed114_sequencial == "" || $ed114_sequencial == null ){
       $result = db_query("select nextval('progressaoparcialaluno_ed114_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: progressaoparcialaluno_ed114_sequencial_seq do campo: ed114_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed114_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from progressaoparcialaluno_ed114_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed114_sequencial)){
         $this->erro_sql = " Campo ed114_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed114_sequencial = $ed114_sequencial;
       }
     }
     if(($this->ed114_sequencial == null) || ($this->ed114_sequencial == "") ){
       $this->erro_sql = " Campo ed114_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into progressaoparcialaluno(
                                       ed114_sequencial
                                      ,ed114_disciplina
                                      ,ed114_aluno
                                      ,ed114_serie
                                      ,ed114_tipoconclusao
                                      ,ed114_ano
                                      ,ed114_escola
                                      ,ed114_situacaoeducacao
                       )
                values (
                                $this->ed114_sequencial
                               ,$this->ed114_disciplina
                               ,$this->ed114_aluno
                               ,$this->ed114_serie
                               ,$this->ed114_tipoconclusao
                               ,$this->ed114_ano
                               ,$this->ed114_escola
                               ,$this->ed114_situacaoeducacao
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Progressão parcial do Aluno ($this->ed114_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Progressão parcial do Aluno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Progressão parcial do Aluno ($this->ed114_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed114_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed114_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19537,'$this->ed114_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3472,19537,'','".AddSlashes(pg_result($resaco,0,'ed114_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3472,19539,'','".AddSlashes(pg_result($resaco,0,'ed114_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3472,19540,'','".AddSlashes(pg_result($resaco,0,'ed114_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3472,19541,'','".AddSlashes(pg_result($resaco,0,'ed114_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3472,19681,'','".AddSlashes(pg_result($resaco,0,'ed114_tipoconclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3472,20386,'','".AddSlashes(pg_result($resaco,0,'ed114_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3472,20390,'','".AddSlashes(pg_result($resaco,0,'ed114_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3472,20413,'','".AddSlashes(pg_result($resaco,0,'ed114_situacaoeducacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed114_sequencial=null) {
      $this->atualizacampos();
     $sql = " update progressaoparcialaluno set ";
     $virgula = "";
     if(trim($this->ed114_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed114_sequencial"])){
       $sql  .= $virgula." ed114_sequencial = $this->ed114_sequencial ";
       $virgula = ",";
       if(trim($this->ed114_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed114_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed114_disciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed114_disciplina"])){
       $sql  .= $virgula." ed114_disciplina = $this->ed114_disciplina ";
       $virgula = ",";
       if(trim($this->ed114_disciplina) == null ){
         $this->erro_sql = " Campo Disciplina não informado.";
         $this->erro_campo = "ed114_disciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed114_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed114_aluno"])){
       $sql  .= $virgula." ed114_aluno = $this->ed114_aluno ";
       $virgula = ",";
       if(trim($this->ed114_aluno) == null ){
         $this->erro_sql = " Campo Aluno não informado.";
         $this->erro_campo = "ed114_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed114_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed114_serie"])){
       $sql  .= $virgula." ed114_serie = $this->ed114_serie ";
       $virgula = ",";
       if(trim($this->ed114_serie) == null ){
         $this->erro_sql = " Campo Série não informado.";
         $this->erro_campo = "ed114_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed114_tipoconclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed114_tipoconclusao"])){
        if(trim($this->ed114_tipoconclusao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed114_tipoconclusao"])){
           $this->ed114_tipoconclusao = "0" ;
        }
       $sql  .= $virgula." ed114_tipoconclusao = $this->ed114_tipoconclusao ";
       $virgula = ",";
     }
     if(trim($this->ed114_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed114_ano"])){
       $sql  .= $virgula." ed114_ano = $this->ed114_ano ";
       $virgula = ",";
       if(trim($this->ed114_ano) == null ){
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "ed114_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed114_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed114_escola"])){
       $sql  .= $virgula." ed114_escola = $this->ed114_escola ";
       $virgula = ",";
       if(trim($this->ed114_escola) == null ){
         $this->erro_sql = " Campo Escola não informado.";
         $this->erro_campo = "ed114_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed114_situacaoeducacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed114_situacaoeducacao"])){
       $sql  .= $virgula." ed114_situacaoeducacao = $this->ed114_situacaoeducacao ";
       $virgula = ",";
       if(trim($this->ed114_situacaoeducacao) == null ){
         $this->erro_sql = " Campo Situação da progressão não informado.";
         $this->erro_campo = "ed114_situacaoeducacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed114_sequencial!=null){
       $sql .= " ed114_sequencial = $this->ed114_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed114_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19537,'$this->ed114_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed114_sequencial"]) || $this->ed114_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3472,19537,'".AddSlashes(pg_result($resaco,$conresaco,'ed114_sequencial'))."','$this->ed114_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed114_disciplina"]) || $this->ed114_disciplina != "")
             $resac = db_query("insert into db_acount values($acount,3472,19539,'".AddSlashes(pg_result($resaco,$conresaco,'ed114_disciplina'))."','$this->ed114_disciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed114_aluno"]) || $this->ed114_aluno != "")
             $resac = db_query("insert into db_acount values($acount,3472,19540,'".AddSlashes(pg_result($resaco,$conresaco,'ed114_aluno'))."','$this->ed114_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed114_serie"]) || $this->ed114_serie != "")
             $resac = db_query("insert into db_acount values($acount,3472,19541,'".AddSlashes(pg_result($resaco,$conresaco,'ed114_serie'))."','$this->ed114_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed114_tipoconclusao"]) || $this->ed114_tipoconclusao != "")
             $resac = db_query("insert into db_acount values($acount,3472,19681,'".AddSlashes(pg_result($resaco,$conresaco,'ed114_tipoconclusao'))."','$this->ed114_tipoconclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed114_ano"]) || $this->ed114_ano != "")
             $resac = db_query("insert into db_acount values($acount,3472,20386,'".AddSlashes(pg_result($resaco,$conresaco,'ed114_ano'))."','$this->ed114_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed114_escola"]) || $this->ed114_escola != "")
             $resac = db_query("insert into db_acount values($acount,3472,20390,'".AddSlashes(pg_result($resaco,$conresaco,'ed114_escola'))."','$this->ed114_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed114_situacaoeducacao"]) || $this->ed114_situacaoeducacao != "")
             $resac = db_query("insert into db_acount values($acount,3472,20413,'".AddSlashes(pg_result($resaco,$conresaco,'ed114_situacaoeducacao'))."','$this->ed114_situacaoeducacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Progressão parcial do Aluno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed114_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Progressão parcial do Aluno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed114_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed114_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19537,'$ed114_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3472,19537,'','".AddSlashes(pg_result($resaco,$iresaco,'ed114_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3472,19539,'','".AddSlashes(pg_result($resaco,$iresaco,'ed114_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3472,19540,'','".AddSlashes(pg_result($resaco,$iresaco,'ed114_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3472,19541,'','".AddSlashes(pg_result($resaco,$iresaco,'ed114_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3472,19681,'','".AddSlashes(pg_result($resaco,$iresaco,'ed114_tipoconclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3472,20386,'','".AddSlashes(pg_result($resaco,$iresaco,'ed114_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3472,20390,'','".AddSlashes(pg_result($resaco,$iresaco,'ed114_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3472,20413,'','".AddSlashes(pg_result($resaco,$iresaco,'ed114_situacaoeducacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from progressaoparcialaluno
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed114_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed114_sequencial = $ed114_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Progressão parcial do Aluno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed114_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Progressão parcial do Aluno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:progressaoparcialaluno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed114_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from progressaoparcialaluno ";
     $sql .= "      inner join situacaoeducacao  on  situacaoeducacao.ed109_sequencial = progressaoparcialaluno.ed114_situacaoeducacao";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = progressaoparcialaluno.ed114_escola";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = progressaoparcialaluno.ed114_disciplina";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = progressaoparcialaluno.ed114_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = progressaoparcialaluno.ed114_aluno";
     $sql .= "      inner join tiposituacaoeducacao  on  tiposituacaoeducacao.ed108_sequencial = situacaoeducacao.ed109_tiposituacaoeducacao";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql .= "      inner join caddisciplina  on  caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql .= "      inner join ensino  as a on   a.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql .= "      left  join censouf  as b on   b.ed260_i_codigo = aluno.ed47_i_censoufnat and   b.ed260_i_codigo = aluno.ed47_i_censoufident and   b.ed260_i_codigo = aluno.ed47_i_censoufcert and   b.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic  as c on   c.ed261_i_codigo = aluno.ed47_i_censomunicnat and   c.ed261_i_codigo = aluno.ed47_i_censomunicend and   c.ed261_i_codigo = aluno.ed47_i_censomuniccert";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      left  join aluno  as d on   d.ed47_i_codigo = aluno.ed47_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed114_sequencial!=null ){
         $sql2 .= " where progressaoparcialaluno.ed114_sequencial = $ed114_sequencial ";
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
   // funcao do sql
   function sql_query_file ( $ed114_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from progressaoparcialaluno ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed114_sequencial!=null ){
         $sql2 .= " where progressaoparcialaluno.ed114_sequencial = $ed114_sequencial ";
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
   function sql_query_aluno_escola ($ed114_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {

      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from progressaoparcialaluno ";
    $sql .= "      inner join serie          on serie.ed11_i_codigo       = progressaoparcialaluno.ed114_serie";
    $sql .= "      inner join aluno          on aluno.ed47_i_codigo       = progressaoparcialaluno.ed114_aluno";
    $sql .= "      left join progressaoparcialalunodiariofinalorigem on ed107_progressaoparcialaluno          = ed114_sequencial ";
    $sql .= "      left join diariofinal    on diariofinal.ed74_i_codigo = ed107_diariofinal";
    $sql .= "      left join diario         on diario.ed95_i_codigo      = diariofinal.ed74_i_diario";
    $sql .= "      left join regencia       on regencia.ed59_i_codigo    = diario.ed95_i_regencia ";
    $sql .= "      left join turma          on turma.ed57_i_codigo       = regencia.ed59_i_turma ";
    $sql .= "      left join escola         on escola.ed18_i_codigo      = diario.ed95_i_escola";
    $sql .= "      left join calendario     on calendario.ed52_i_codigo  = diario.ed95_i_calendario";
    $sql2 = "";
    if ($dbwhere == "") {

      if ($ed114_sequencial != null) {
        $sql2 .= " where progressaoparcialaluno.ed114_sequencial = $ed114_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
}
   /**
   * Retorna todos alunos que estão em progressão, sendo da rede ou não
   * @param string $iCodigo
   * @param string $sCampos
   * @param string $sOrdem
   * @param string $sWhere
   * @return string
   */
  function sql_query_aluno_em_progressao($iCodigo = null, $sCampos = "*", $sOrdem = null, $sWhere = "") {

    $sql = "select ";
    if ($sCampos != "*") {

      $sCampos_sql = split("#", $sCampos);
      $virgula = "";
      for ($i = 0; $i < sizeof($sCampos_sql); $i++) {

        $sql .= $virgula . $sCampos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $sCampos;
    }
    $sql .= "   from progressaoparcialaluno ";
    $sql .= "   left join progressaoparcialalunodiariofinalorigem on ed107_progressaoparcialaluno = ed114_sequencial ";
    $sql .= "  inner join serie          on serie.ed11_i_codigo          = progressaoparcialaluno.ed114_serie";
    $sql .= "  inner join aluno          on aluno.ed47_i_codigo          = progressaoparcialaluno.ed114_aluno";
    $sql .= "  inner join disciplina     on disciplina.ed12_i_codigo     = progressaoparcialaluno.ed114_disciplina";
    $sql .= "  inner join caddisciplina  on caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina";
    $sql2 = "";
    if ($sWhere == "") {

      if ($iCodigo != null) {
        $sql2 .= " where progressaoparcialaluno.ed114_sequencial = $iCodigo ";
      }
    } else if ($sWhere != "") {
      $sql2 = " where $sWhere";
    }
    $sql .= $sql2;
    if ($sOrdem != null) {

      $sql .= " order by ";
      $sCampos_sql = split("#", $sOrdem);
      $virgula = "";
      for ($i = 0; $i < sizeof($sCampos_sql); $i++) {

        $sql .= $virgula . $sCampos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_matricula($ed114_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {

      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from progressaoparcialaluno ";
    $sql .= "      left join progressaoparcialalunomatricula      on ed114_sequencial = ed150_progressaoparcialaluno";
    $sql .= "      left join progressaoparcialalunoresultadofinal on ed150_sequencial = ed121_progressaoparcialalunomatricula";
    $sql2 = "";
    if ($dbwhere == "") {

      if ($ed114_sequencial != null) {
        $sql2 .= " where progressaoparcialaluno.ed114_sequencial = $ed114_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_alunos_turma ($ed114_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {

      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from progressaoparcialaluno ";
    $sql .= "      inner join disciplina                          on ed114_disciplina = ed12_i_codigo";
    $sql .= "      inner join aluno                               on ed47_i_codigo    = ed114_aluno";
    $sql .= "       left join progressaoparcialalunomatricula     on ed114_sequencial = ed150_progressaoparcialaluno";
    $sql .= "       left join progressaoparcialalunoturmaregencia on ed115_progressaoparcialalunomatricula = ed150_sequencial";
    $sql2 = "";
    if ($dbwhere == "") {

      if ($ed114_sequencial != null) {
        $sql2 .= " where progressaoparcialaluno.ed114_sequencial = $ed114_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_resultado_final($ed114_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {

      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from progressaoparcialaluno ";
    $sql .= "      inner join progressaoparcialalunomatricula      on ed114_sequencial = ed150_progressaoparcialaluno";
    $sql .= "      inner join progressaoparcialalunoresultadofinal on ed150_sequencial = ed121_progressaoparcialalunomatricula";
    $sql2 = "";
    if ($dbwhere == "") {

      if ($ed114_sequencial != null) {
        $sql2 .= " where progressaoparcialaluno.ed114_sequencial = $ed114_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_resultado_regencia($ed114_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {

      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    
    $sql .= " from progressaoparcialaluno ";
    $sql .= "      inner join progressaoparcialalunomatricula      on ed114_sequencial = ed150_progressaoparcialaluno";
    $sql .= "      inner join progressaoparcialalunoturmaregencia  on ed115_progressaoparcialalunomatricula = ed150_sequencial";
    $sql .= "      inner join progressaoparcialalunoresultadofinal on ed150_sequencial = ed121_progressaoparcialalunomatricula";
    $sql2 = "";
    
    if ($dbwhere == "") {

      if ($ed114_sequencial != null) {
        $sql2 .= " where progressaoparcialaluno.ed114_sequencial = $ed114_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  
  function sql_query_regencia_turma($ed114_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {

      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    
    $sql .= " from progressaoparcialaluno ";
    $sql .= "      inner join progressaoparcialalunomatricula      on ed114_sequencial = ed150_progressaoparcialaluno";
    $sql .= "      inner join progressaoparcialalunoturmaregencia  on ed115_progressaoparcialalunomatricula = ed150_sequencial";
    $sql .= "      inner join regencia on ed59_i_codigo = ed115_regencia";
    $sql2 = "";
    
    if ($dbwhere == "") {

      if ($ed114_sequencial != null) {
        $sql2 .= " where progressaoparcialaluno.ed114_sequencial = $ed114_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}