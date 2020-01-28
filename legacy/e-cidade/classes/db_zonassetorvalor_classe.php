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
//CLASSE DA ENTIDADE zonassetorvalor
class cl_zonassetorvalor {
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
   var $j141_sequencial = 0;
   var $j141_anousu = 0;
   var $j141_zonas = 0;
   var $j141_setor = null;
   var $j141_valorminimo = 0;
   var $j141_valorm2 = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 j141_sequencial = int4 = Sequencial da tabela zonasetorvalor
                 j141_anousu = int4 = Ano de execício
                 j141_zonas = int8 = Código da zona
                 j141_setor = varchar(4) = Código do setor
                 j141_valorminimo = float8 = Valor mínimo
                 j141_valorm2 = float8 = Valor do metro quadrado
                 ";
   //funcao construtor da classe
   function cl_zonassetorvalor() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("zonassetorvalor");
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
       $this->j141_sequencial = ($this->j141_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j141_sequencial"]:$this->j141_sequencial);
       $this->j141_anousu = ($this->j141_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j141_anousu"]:$this->j141_anousu);
       $this->j141_zonas = ($this->j141_zonas == ""?@$GLOBALS["HTTP_POST_VARS"]["j141_zonas"]:$this->j141_zonas);
       $this->j141_setor = ($this->j141_setor == ""?@$GLOBALS["HTTP_POST_VARS"]["j141_setor"]:$this->j141_setor);
       $this->j141_valorminimo = ($this->j141_valorminimo == ""?@$GLOBALS["HTTP_POST_VARS"]["j141_valorminimo"]:$this->j141_valorminimo);
       $this->j141_valorm2 = ($this->j141_valorm2 == ""?@$GLOBALS["HTTP_POST_VARS"]["j141_valorm2"]:$this->j141_valorm2);
     }else{
       $this->j141_anousu = ($this->j141_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j141_anousu"]:$this->j141_anousu);
       $this->j141_zonas = ($this->j141_zonas == ""?@$GLOBALS["HTTP_POST_VARS"]["j141_zonas"]:$this->j141_zonas);
       $this->j141_setor = ($this->j141_setor == ""?@$GLOBALS["HTTP_POST_VARS"]["j141_setor"]:$this->j141_setor);
     }
   }
   // funcao para inclusao
   function incluir ($j141_anousu,$j141_zonas,$j141_setor){
      $this->atualizacampos();
     if($this->j141_sequencial == null ){
       $this->erro_sql = " Campo Sequencial da tabela zonasetorvalor não informado.";
       $this->erro_campo = "j141_sequencial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j141_valorminimo == null ){
       $this->erro_sql = " Campo Valor mínimo não informado.";
       $this->erro_campo = "j141_valorminimo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j141_valorm2 == null ){
       $this->erro_sql = " Campo Valor do metro quadrado não informado.";
       $this->erro_campo = "j141_valorm2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j141_sequencial == "" || $j141_sequencial == null ){
       $result = db_query("select nextval('zonassetorvalor_j141_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: zonassetorvalor_j141_sequencial_seq do campo: j141_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->j141_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from zonassetorvalor_j141_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j141_sequencial)){
         $this->erro_sql = " Campo j141_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j141_sequencial = $j141_sequencial;
       }
     }
     if(($this->j141_anousu == null) || ($this->j141_anousu == "") ){
       $this->erro_sql = " Campo j141_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j141_zonas == null) || ($this->j141_zonas == "") ){
       $this->erro_sql = " Campo j141_zonas nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j141_setor == null) || ($this->j141_setor == "") ){
       $this->erro_sql = " Campo j141_setor nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into zonassetorvalor(
                                       j141_sequencial
                                      ,j141_anousu
                                      ,j141_zonas
                                      ,j141_setor
                                      ,j141_valorminimo
                                      ,j141_valorm2
                       )
                values (
                                $this->j141_sequencial
                               ,$this->j141_anousu
                               ,$this->j141_zonas
                               ,'$this->j141_setor'
                               ,$this->j141_valorminimo
                               ,$this->j141_valorm2
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores por zona, setor e ano ($this->j141_anousu."-".$this->j141_zonas."-".$this->j141_setor) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores por zona, setor e ano já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores por zona, setor e ano ($this->j141_anousu."-".$this->j141_zonas."-".$this->j141_setor) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j141_anousu."-".$this->j141_zonas."-".$this->j141_setor;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j141_anousu,$this->j141_zonas,$this->j141_setor  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20997,'$this->j141_anousu','I')");
         $resac = db_query("insert into db_acountkey values($acount,20998,'$this->j141_zonas','I')");
         $resac = db_query("insert into db_acountkey values($acount,20999,'$this->j141_setor','I')");
         $resac = db_query("insert into db_acount values($acount,3783,20996,'','".AddSlashes(pg_result($resaco,0,'j141_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3783,20997,'','".AddSlashes(pg_result($resaco,0,'j141_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3783,20998,'','".AddSlashes(pg_result($resaco,0,'j141_zonas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3783,20999,'','".AddSlashes(pg_result($resaco,0,'j141_setor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3783,21000,'','".AddSlashes(pg_result($resaco,0,'j141_valorminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3783,21001,'','".AddSlashes(pg_result($resaco,0,'j141_valorm2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($j141_anousu=null,$j141_zonas=null,$j141_setor=null) {
      $this->atualizacampos();
     $sql = " update zonassetorvalor set ";
     $virgula = "";
     if(trim($this->j141_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j141_sequencial"])){
       $sql  .= $virgula." j141_sequencial = $this->j141_sequencial ";
       $virgula = ",";
       if(trim($this->j141_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial da tabela zonasetorvalor não informado.";
         $this->erro_campo = "j141_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j141_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j141_anousu"])){
       $sql  .= $virgula." j141_anousu = $this->j141_anousu ";
       $virgula = ",";
       if(trim($this->j141_anousu) == null ){
         $this->erro_sql = " Campo Ano de execício não informado.";
         $this->erro_campo = "j141_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j141_zonas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j141_zonas"])){
       $sql  .= $virgula." j141_zonas = $this->j141_zonas ";
       $virgula = ",";
       if(trim($this->j141_zonas) == null ){
         $this->erro_sql = " Campo Código da zona não informado.";
         $this->erro_campo = "j141_zonas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j141_setor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j141_setor"])){
       $sql  .= $virgula." j141_setor = '$this->j141_setor' ";
       $virgula = ",";
       if(trim($this->j141_setor) == null ){
         $this->erro_sql = " Campo Código do setor não informado.";
         $this->erro_campo = "j141_setor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j141_valorminimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j141_valorminimo"])){
       $sql  .= $virgula." j141_valorminimo = $this->j141_valorminimo ";
       $virgula = ",";
       if(trim($this->j141_valorminimo) == null ){
         $this->erro_sql = " Campo Valor mínimo não informado.";
         $this->erro_campo = "j141_valorminimo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j141_valorm2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j141_valorm2"])){
       $sql  .= $virgula." j141_valorm2 = $this->j141_valorm2 ";
       $virgula = ",";
       if(trim($this->j141_valorm2) == null ){
         $this->erro_sql = " Campo Valor do metro quadrado não informado.";
         $this->erro_campo = "j141_valorm2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j141_anousu!=null){
       $sql .= " j141_anousu = $this->j141_anousu";
     }
     if($j141_zonas!=null){
       $sql .= " and  j141_zonas = $this->j141_zonas";
     }
     if($j141_setor!=null){
       $sql .= " and  j141_setor = '$this->j141_setor'";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j141_anousu,$this->j141_zonas,$this->j141_setor));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20997,'$this->j141_anousu','A')");
           $resac = db_query("insert into db_acountkey values($acount,20998,'$this->j141_zonas','A')");
           $resac = db_query("insert into db_acountkey values($acount,20999,'$this->j141_setor','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j141_sequencial"]) || $this->j141_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3783,20996,'".AddSlashes(pg_result($resaco,$conresaco,'j141_sequencial'))."','$this->j141_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j141_anousu"]) || $this->j141_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3783,20997,'".AddSlashes(pg_result($resaco,$conresaco,'j141_anousu'))."','$this->j141_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j141_zonas"]) || $this->j141_zonas != "")
             $resac = db_query("insert into db_acount values($acount,3783,20998,'".AddSlashes(pg_result($resaco,$conresaco,'j141_zonas'))."','$this->j141_zonas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j141_setor"]) || $this->j141_setor != "")
             $resac = db_query("insert into db_acount values($acount,3783,20999,'".AddSlashes(pg_result($resaco,$conresaco,'j141_setor'))."','$this->j141_setor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j141_valorminimo"]) || $this->j141_valorminimo != "")
             $resac = db_query("insert into db_acount values($acount,3783,21000,'".AddSlashes(pg_result($resaco,$conresaco,'j141_valorminimo'))."','$this->j141_valorminimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j141_valorm2"]) || $this->j141_valorm2 != "")
             $resac = db_query("insert into db_acount values($acount,3783,21001,'".AddSlashes(pg_result($resaco,$conresaco,'j141_valorm2'))."','$this->j141_valorm2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores por zona, setor e ano nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j141_anousu."-".$this->j141_zonas."-".$this->j141_setor;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Valores por zona, setor e ano nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j141_anousu."-".$this->j141_zonas."-".$this->j141_setor;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j141_anousu."-".$this->j141_zonas."-".$this->j141_setor;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($j141_anousu=null,$j141_zonas=null,$j141_setor=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($j141_anousu,$j141_zonas,$j141_setor));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20997,'$j141_anousu','E')");
           $resac  = db_query("insert into db_acountkey values($acount,20998,'$j141_zonas','E')");
           $resac  = db_query("insert into db_acountkey values($acount,20999,'$j141_setor','E')");
           $resac  = db_query("insert into db_acount values($acount,3783,20996,'','".AddSlashes(pg_result($resaco,$iresaco,'j141_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3783,20997,'','".AddSlashes(pg_result($resaco,$iresaco,'j141_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3783,20998,'','".AddSlashes(pg_result($resaco,$iresaco,'j141_zonas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3783,20999,'','".AddSlashes(pg_result($resaco,$iresaco,'j141_setor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3783,21000,'','".AddSlashes(pg_result($resaco,$iresaco,'j141_valorminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3783,21001,'','".AddSlashes(pg_result($resaco,$iresaco,'j141_valorm2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from zonassetorvalor
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($j141_anousu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " j141_anousu = $j141_anousu ";
        }
        if (!empty($j141_zonas)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " j141_zonas = $j141_zonas ";
        }
        if (!empty($j141_setor)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " j141_setor = '$j141_setor' ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores por zona, setor e ano nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j141_anousu."-".$j141_zonas."-".$j141_setor;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Valores por zona, setor e ano nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j141_anousu."-".$j141_zonas."-".$j141_setor;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j141_anousu."-".$j141_zonas."-".$j141_setor;
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
        $this->erro_sql   = "Record Vazio na Tabela:zonassetorvalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($j141_anousu = null,$j141_zonas = null,$j141_setor = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from zonassetorvalor ";
     $sql .= "      inner join setor  on  setor.j30_codi = zonassetorvalor.j141_setor";
     $sql .= "      inner join zonas  on  zonas.j50_zona = zonassetorvalor.j141_zonas";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j141_anousu)) {
         $sql2 .= " where zonassetorvalor.j141_anousu = $j141_anousu ";
       }
       if (!empty($j141_zonas)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " zonassetorvalor.j141_zonas = $j141_zonas ";
       }
       if (!empty($j141_setor)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " zonassetorvalor.j141_setor = '$j141_setor' ";
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
   public function sql_query_file ($j141_anousu = null,$j141_zonas = null,$j141_setor = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from zonassetorvalor ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j141_anousu)){
         $sql2 .= " where zonassetorvalor.j141_anousu = $j141_anousu ";
       }
       if (!empty($j141_zonas)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " zonassetorvalor.j141_zonas = $j141_zonas ";
       }
       if (!empty($j141_setor)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " zonassetorvalor.j141_setor = '$j141_setor' ";
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
