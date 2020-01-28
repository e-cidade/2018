<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: cadastro
//CLASSE DA ENTIDADE caractercaracter
class cl_caractercaracter {
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
   var $j138_sequencial = 0;
   var $j138_caracterorigem = 0;
   var $j138_caracterdestino = 0;
   var $j138_pontuacao = 0;
   var $j138_aliquota = 0;
   var $j138_valor = 0;
   var $j138_anousu = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 j138_sequencial = int4 = Sequencial
                 j138_caracterorigem = int4 = Caracteristica de Origem
                 j138_caracterdestino = int4 = Caracteristica de Destino
                 j138_pontuacao = float4 = Pontuação
                 j138_aliquota = float4 = Alíquota
                 j138_valor = float4 = Valor
                 j138_anousu = int4 = Anousu
                 ";
   //funcao construtor da classe
   function cl_caractercaracter() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("caractercaracter");
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
       $this->j138_sequencial = ($this->j138_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j138_sequencial"]:$this->j138_sequencial);
       $this->j138_caracterorigem = ($this->j138_caracterorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["j138_caracterorigem"]:$this->j138_caracterorigem);
       $this->j138_caracterdestino = ($this->j138_caracterdestino == ""?@$GLOBALS["HTTP_POST_VARS"]["j138_caracterdestino"]:$this->j138_caracterdestino);
       $this->j138_pontuacao = ($this->j138_pontuacao == ""?@$GLOBALS["HTTP_POST_VARS"]["j138_pontuacao"]:$this->j138_pontuacao);
       $this->j138_aliquota = ($this->j138_aliquota == ""?@$GLOBALS["HTTP_POST_VARS"]["j138_aliquota"]:$this->j138_aliquota);
       $this->j138_valor = ($this->j138_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["j138_valor"]:$this->j138_valor);
       $this->j138_anousu = ($this->j138_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j138_anousu"]:$this->j138_anousu);
     }else{
       $this->j138_sequencial = ($this->j138_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j138_sequencial"]:$this->j138_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j138_sequencial){
      $this->atualizacampos();
     if($this->j138_caracterorigem == null ){
       $this->erro_sql = " Campo Caracteristica de Origem não informado.";
       $this->erro_campo = "j138_caracterorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j138_caracterdestino == null ){
       $this->erro_sql = " Campo Caracteristica de Destino não informado.";
       $this->erro_campo = "j138_caracterdestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j138_pontuacao == null ){
       $this->erro_sql = " Campo Pontuação não informado.";
       $this->erro_campo = "j138_pontuacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j138_aliquota == null ){
       $this->erro_sql = " Campo Alíquota não informado.";
       $this->erro_campo = "j138_aliquota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j138_valor == null ){
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "j138_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j138_anousu == null ){
       $this->erro_sql = " Campo Anousu não informado.";
       $this->erro_campo = "j138_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j138_sequencial == "" || $j138_sequencial == null ){
       $result = db_query("select nextval('caractercaracter_j138_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: caractercaracter_j138_sequencial_seq do campo: j138_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->j138_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from caractercaracter_j138_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j138_sequencial)){
         $this->erro_sql = " Campo j138_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j138_sequencial = $j138_sequencial;
       }
     }
     if(($this->j138_sequencial == null) || ($this->j138_sequencial == "") ){
       $this->erro_sql = " Campo j138_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into caractercaracter(
                                       j138_sequencial
                                      ,j138_caracterorigem
                                      ,j138_caracterdestino
                                      ,j138_pontuacao
                                      ,j138_aliquota
                                      ,j138_valor
                                      ,j138_anousu
                       )
                values (
                                $this->j138_sequencial
                               ,$this->j138_caracterorigem
                               ,$this->j138_caracterdestino
                               ,$this->j138_pontuacao
                               ,$this->j138_aliquota
                               ,$this->j138_valor
                               ,$this->j138_anousu
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Caracter Caracter ($this->j138_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Caracter Caracter já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Caracter Caracter ($this->j138_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j138_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j138_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20548,'$this->j138_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3698,20548,'','".AddSlashes(pg_result($resaco,0,'j138_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3698,20549,'','".AddSlashes(pg_result($resaco,0,'j138_caracterorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3698,20550,'','".AddSlashes(pg_result($resaco,0,'j138_caracterdestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3698,20551,'','".AddSlashes(pg_result($resaco,0,'j138_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3698,20552,'','".AddSlashes(pg_result($resaco,0,'j138_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3698,20553,'','".AddSlashes(pg_result($resaco,0,'j138_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3698,20554,'','".AddSlashes(pg_result($resaco,0,'j138_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($j138_sequencial=null) {
      $this->atualizacampos();
     $sql = " update caractercaracter set ";
     $virgula = "";
     if(trim($this->j138_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j138_sequencial"])){
       $sql  .= $virgula." j138_sequencial = $this->j138_sequencial ";
       $virgula = ",";
       if(trim($this->j138_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "j138_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j138_caracterorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j138_caracterorigem"])){
       $sql  .= $virgula." j138_caracterorigem = $this->j138_caracterorigem ";
       $virgula = ",";
       if(trim($this->j138_caracterorigem) == null ){
         $this->erro_sql = " Campo Caracteristica de Origem não informado.";
         $this->erro_campo = "j138_caracterorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j138_caracterdestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j138_caracterdestino"])){
       $sql  .= $virgula." j138_caracterdestino = $this->j138_caracterdestino ";
       $virgula = ",";
       if(trim($this->j138_caracterdestino) == null ){
         $this->erro_sql = " Campo Caracteristica de Destino não informado.";
         $this->erro_campo = "j138_caracterdestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j138_pontuacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j138_pontuacao"])){
       $sql  .= $virgula." j138_pontuacao = $this->j138_pontuacao ";
       $virgula = ",";
       if(trim($this->j138_pontuacao) == null ){
         $this->erro_sql = " Campo Pontuação não informado.";
         $this->erro_campo = "j138_pontuacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j138_aliquota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j138_aliquota"])){
       $sql  .= $virgula." j138_aliquota = $this->j138_aliquota ";
       $virgula = ",";
       if(trim($this->j138_aliquota) == null ){
         $this->erro_sql = " Campo Alíquota não informado.";
         $this->erro_campo = "j138_aliquota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j138_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j138_valor"])){
       $sql  .= $virgula." j138_valor = $this->j138_valor ";
       $virgula = ",";
       if(trim($this->j138_valor) == null ){
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "j138_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j138_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j138_anousu"])){
       $sql  .= $virgula." j138_anousu = $this->j138_anousu ";
       $virgula = ",";
       if(trim($this->j138_anousu) == null ){
         $this->erro_sql = " Campo Anousu não informado.";
         $this->erro_campo = "j138_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j138_sequencial!=null){
       $sql .= " j138_sequencial = $this->j138_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j138_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20548,'$this->j138_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j138_sequencial"]) || $this->j138_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3698,20548,'".AddSlashes(pg_result($resaco,$conresaco,'j138_sequencial'))."','$this->j138_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j138_caracterorigem"]) || $this->j138_caracterorigem != "")
             $resac = db_query("insert into db_acount values($acount,3698,20549,'".AddSlashes(pg_result($resaco,$conresaco,'j138_caracterorigem'))."','$this->j138_caracterorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j138_caracterdestino"]) || $this->j138_caracterdestino != "")
             $resac = db_query("insert into db_acount values($acount,3698,20550,'".AddSlashes(pg_result($resaco,$conresaco,'j138_caracterdestino'))."','$this->j138_caracterdestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j138_pontuacao"]) || $this->j138_pontuacao != "")
             $resac = db_query("insert into db_acount values($acount,3698,20551,'".AddSlashes(pg_result($resaco,$conresaco,'j138_pontuacao'))."','$this->j138_pontuacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j138_aliquota"]) || $this->j138_aliquota != "")
             $resac = db_query("insert into db_acount values($acount,3698,20552,'".AddSlashes(pg_result($resaco,$conresaco,'j138_aliquota'))."','$this->j138_aliquota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j138_valor"]) || $this->j138_valor != "")
             $resac = db_query("insert into db_acount values($acount,3698,20553,'".AddSlashes(pg_result($resaco,$conresaco,'j138_valor'))."','$this->j138_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j138_anousu"]) || $this->j138_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3698,20554,'".AddSlashes(pg_result($resaco,$conresaco,'j138_anousu'))."','$this->j138_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Caracter Caracter nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j138_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Caracter Caracter nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($j138_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($j138_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20548,'$j138_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3698,20548,'','".AddSlashes(pg_result($resaco,$iresaco,'j138_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3698,20549,'','".AddSlashes(pg_result($resaco,$iresaco,'j138_caracterorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3698,20550,'','".AddSlashes(pg_result($resaco,$iresaco,'j138_caracterdestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3698,20551,'','".AddSlashes(pg_result($resaco,$iresaco,'j138_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3698,20552,'','".AddSlashes(pg_result($resaco,$iresaco,'j138_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3698,20553,'','".AddSlashes(pg_result($resaco,$iresaco,'j138_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3698,20554,'','".AddSlashes(pg_result($resaco,$iresaco,'j138_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from caractercaracter
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($j138_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " j138_sequencial = $j138_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Caracter Caracter nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j138_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Caracter Caracter nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j138_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:caractercaracter";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($j138_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from caractercaracter ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = caractercaracter.j138_caracterorigem and  caracter.j31_codigo = caractercaracter.j138_caracterdestino";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j138_sequencial)) {
         $sql2 .= " where caractercaracter.j138_sequencial = $j138_sequencial ";
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
   public function sql_query_file ($j138_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from caractercaracter ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j138_sequencial)){
         $sql2 .= " where caractercaracter.j138_sequencial = $j138_sequencial ";
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