<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
//CLASSE DA ENTIDADE turmacensoetapa
class cl_turmacensoetapa {
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
   var $ed132_codigo = 0;
   var $ed132_turma = 0;
   var $ed132_censoetapa = 0;
   var $ed132_ano = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed132_codigo = int4 = Código
                 ed132_turma = int4 = Turma
                 ed132_censoetapa = int4 = Censo Etapa
                 ed132_ano = int4 = Ano
                 ";
   //funcao construtor da classe
   function cl_turmacensoetapa() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmacensoetapa");
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
       $this->ed132_codigo = ($this->ed132_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed132_codigo"]:$this->ed132_codigo);
       $this->ed132_turma = ($this->ed132_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed132_turma"]:$this->ed132_turma);
       $this->ed132_censoetapa = ($this->ed132_censoetapa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed132_censoetapa"]:$this->ed132_censoetapa);
       $this->ed132_ano = ($this->ed132_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed132_ano"]:$this->ed132_ano);
     }else{
       $this->ed132_codigo = ($this->ed132_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed132_codigo"]:$this->ed132_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed132_codigo){
      $this->atualizacampos();
     if($this->ed132_turma == null ){
       $this->erro_sql = " Campo Turma não informado.";
       $this->erro_campo = "ed132_turma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed132_censoetapa == null ){
       $this->erro_sql = " Campo Censo Etapa não informado.";
       $this->erro_campo = "ed132_censoetapa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed132_ano == null ){
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "ed132_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed132_codigo == "" || $ed132_codigo == null ){
       $result = db_query("select nextval('turmacensoetapa_ed132_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turmacensoetapa_ed132_codigo_seq do campo: ed132_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed132_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from turmacensoetapa_ed132_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed132_codigo)){
         $this->erro_sql = " Campo ed132_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed132_codigo = $ed132_codigo;
       }
     }
     if(($this->ed132_codigo == null) || ($this->ed132_codigo == "") ){
       $this->erro_sql = " Campo ed132_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turmacensoetapa(
                                       ed132_codigo
                                      ,ed132_turma
                                      ,ed132_censoetapa
                                      ,ed132_ano
                       )
                values (
                                $this->ed132_codigo
                               ,$this->ed132_turma
                               ,$this->ed132_censoetapa
                               ,$this->ed132_ano
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Turma censo etapa ($this->ed132_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Turma censo etapa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Turma censo etapa ($this->ed132_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed132_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed132_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21058,'$this->ed132_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3794,21058,'','".AddSlashes(pg_result($resaco,0,'ed132_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3794,21059,'','".AddSlashes(pg_result($resaco,0,'ed132_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3794,21060,'','".AddSlashes(pg_result($resaco,0,'ed132_censoetapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3794,21061,'','".AddSlashes(pg_result($resaco,0,'ed132_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed132_codigo=null) {
      $this->atualizacampos();
     $sql = " update turmacensoetapa set ";
     $virgula = "";
     if(trim($this->ed132_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed132_codigo"])){
       $sql  .= $virgula." ed132_codigo = $this->ed132_codigo ";
       $virgula = ",";
       if(trim($this->ed132_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed132_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed132_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed132_turma"])){
       $sql  .= $virgula." ed132_turma = $this->ed132_turma ";
       $virgula = ",";
       if(trim($this->ed132_turma) == null ){
         $this->erro_sql = " Campo Turma não informado.";
         $this->erro_campo = "ed132_turma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed132_censoetapa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed132_censoetapa"])){
       $sql  .= $virgula." ed132_censoetapa = $this->ed132_censoetapa ";
       $virgula = ",";
       if(trim($this->ed132_censoetapa) == null ){
         $this->erro_sql = " Campo Censo Etapa não informado.";
         $this->erro_campo = "ed132_censoetapa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed132_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed132_ano"])){
       $sql  .= $virgula." ed132_ano = $this->ed132_ano ";
       $virgula = ",";
       if(trim($this->ed132_ano) == null ){
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "ed132_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed132_codigo!=null){
       $sql .= " ed132_codigo = $this->ed132_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed132_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21058,'$this->ed132_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed132_codigo"]) || $this->ed132_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3794,21058,'".AddSlashes(pg_result($resaco,$conresaco,'ed132_codigo'))."','$this->ed132_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed132_turma"]) || $this->ed132_turma != "")
             $resac = db_query("insert into db_acount values($acount,3794,21059,'".AddSlashes(pg_result($resaco,$conresaco,'ed132_turma'))."','$this->ed132_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed132_censoetapa"]) || $this->ed132_censoetapa != "")
             $resac = db_query("insert into db_acount values($acount,3794,21060,'".AddSlashes(pg_result($resaco,$conresaco,'ed132_censoetapa'))."','$this->ed132_censoetapa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed132_ano"]) || $this->ed132_ano != "")
             $resac = db_query("insert into db_acount values($acount,3794,21061,'".AddSlashes(pg_result($resaco,$conresaco,'ed132_ano'))."','$this->ed132_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Turma censo etapa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed132_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Turma censo etapa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed132_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed132_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed132_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed132_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21058,'$ed132_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3794,21058,'','".AddSlashes(pg_result($resaco,$iresaco,'ed132_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3794,21059,'','".AddSlashes(pg_result($resaco,$iresaco,'ed132_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3794,21060,'','".AddSlashes(pg_result($resaco,$iresaco,'ed132_censoetapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3794,21061,'','".AddSlashes(pg_result($resaco,$iresaco,'ed132_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from turmacensoetapa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed132_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed132_codigo = $ed132_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Turma censo etapa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed132_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Turma censo etapa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed132_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed132_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmacensoetapa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed132_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from turmacensoetapa ";
     $sql .= "      inner join censoetapa         on  censoetapa.ed266_i_codigo = turmacensoetapa.ed132_censoetapa ";
     $sql .= "                                   and  censoetapa.ed266_ano = turmacensoetapa.ed132_ano";
     $sql .= "      inner join turma              on  turma.ed57_i_codigo = turmacensoetapa.ed132_turma";
     $sql .= "      left  join censocursoprofiss  on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss";
     $sql .= "      inner join escola             on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join turno              on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala               on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario         on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base               on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      inner join procedimento       on  procedimento.ed40_i_codigo = turma.ed57_i_procedimento";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed132_codigo)) {
         $sql2 .= " where turmacensoetapa.ed132_codigo = $ed132_codigo ";
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
   public function sql_query_file ($ed132_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from turmacensoetapa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed132_codigo)){
         $sql2 .= " where turmacensoetapa.ed132_codigo = $ed132_codigo ";
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
