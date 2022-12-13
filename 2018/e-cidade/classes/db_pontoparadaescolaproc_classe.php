<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBseller Servicos de Informatica
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
//MODULO: transporteescolar
//CLASSE DA ENTIDADE pontoparadaescolaproc
class cl_pontoparadaescolaproc { 
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
   var $tre13_sequencial = 0; 
   var $tre13_pontoparada = 0; 
   var $tre13_escolaproc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tre13_sequencial = int4 = Código Sequencial 
                 tre13_pontoparada = int4 = Ponto de Parada 
                 tre13_escolaproc = int4 = Escola de Procedência 
                 ";
   //funcao construtor da classe 
   function cl_pontoparadaescolaproc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pontoparadaescolaproc"); 
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
       $this->tre13_sequencial = ($this->tre13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tre13_sequencial"]:$this->tre13_sequencial);
       $this->tre13_pontoparada = ($this->tre13_pontoparada == ""?@$GLOBALS["HTTP_POST_VARS"]["tre13_pontoparada"]:$this->tre13_pontoparada);
       $this->tre13_escolaproc = ($this->tre13_escolaproc == ""?@$GLOBALS["HTTP_POST_VARS"]["tre13_escolaproc"]:$this->tre13_escolaproc);
     }else{
       $this->tre13_sequencial = ($this->tre13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tre13_sequencial"]:$this->tre13_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($tre13_sequencial){ 
      $this->atualizacampos();
     if($this->tre13_pontoparada == null ){ 
       $this->erro_sql = " Campo Ponto de Parada não informado.";
       $this->erro_campo = "tre13_pontoparada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tre13_escolaproc == null ){ 
       $this->erro_sql = " Campo Escola de Procedência não informado.";
       $this->erro_campo = "tre13_escolaproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tre13_sequencial == "" || $tre13_sequencial == null ){
       $result = db_query("select nextval('pontoparadaescolaproc_tre13_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pontoparadaescolaproc_tre13_sequencial_seq do campo: tre13_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tre13_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pontoparadaescolaproc_tre13_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $tre13_sequencial)){
         $this->erro_sql = " Campo tre13_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tre13_sequencial = $tre13_sequencial; 
       }
     }
     if(($this->tre13_sequencial == null) || ($this->tre13_sequencial == "") ){ 
       $this->erro_sql = " Campo tre13_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pontoparadaescolaproc(
                                       tre13_sequencial 
                                      ,tre13_pontoparada 
                                      ,tre13_escolaproc 
                       )
                values (
                                $this->tre13_sequencial 
                               ,$this->tre13_pontoparada 
                               ,$this->tre13_escolaproc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vínculo do Ponto de Parada e Escola de Procedência ($this->tre13_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vínculo do Ponto de Parada e Escola de Procedência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vínculo do Ponto de Parada e Escola de Procedência ($this->tre13_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tre13_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tre13_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21373,'$this->tre13_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3846,21373,'','".AddSlashes(pg_result($resaco,0,'tre13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3846,21374,'','".AddSlashes(pg_result($resaco,0,'tre13_pontoparada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3846,21375,'','".AddSlashes(pg_result($resaco,0,'tre13_escolaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($tre13_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pontoparadaescolaproc set ";
     $virgula = "";
     if(trim($this->tre13_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre13_sequencial"])){ 
       $sql  .= $virgula." tre13_sequencial = $this->tre13_sequencial ";
       $virgula = ",";
       if(trim($this->tre13_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "tre13_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre13_pontoparada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre13_pontoparada"])){ 
       $sql  .= $virgula." tre13_pontoparada = $this->tre13_pontoparada ";
       $virgula = ",";
       if(trim($this->tre13_pontoparada) == null ){ 
         $this->erro_sql = " Campo Ponto de Parada não informado.";
         $this->erro_campo = "tre13_pontoparada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre13_escolaproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre13_escolaproc"])){ 
       $sql  .= $virgula." tre13_escolaproc = $this->tre13_escolaproc ";
       $virgula = ",";
       if(trim($this->tre13_escolaproc) == null ){ 
         $this->erro_sql = " Campo Escola de Procedência não informado.";
         $this->erro_campo = "tre13_escolaproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tre13_sequencial!=null){
       $sql .= " tre13_sequencial = $this->tre13_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tre13_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21373,'$this->tre13_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tre13_sequencial"]) || $this->tre13_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3846,21373,'".AddSlashes(pg_result($resaco,$conresaco,'tre13_sequencial'))."','$this->tre13_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tre13_pontoparada"]) || $this->tre13_pontoparada != "")
             $resac = db_query("insert into db_acount values($acount,3846,21374,'".AddSlashes(pg_result($resaco,$conresaco,'tre13_pontoparada'))."','$this->tre13_pontoparada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tre13_escolaproc"]) || $this->tre13_escolaproc != "")
             $resac = db_query("insert into db_acount values($acount,3846,21375,'".AddSlashes(pg_result($resaco,$conresaco,'tre13_escolaproc'))."','$this->tre13_escolaproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vínculo do Ponto de Parada e Escola de Procedência não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tre13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vínculo do Ponto de Parada e Escola de Procedência não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tre13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tre13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($tre13_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($tre13_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21373,'$tre13_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3846,21373,'','".AddSlashes(pg_result($resaco,$iresaco,'tre13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3846,21374,'','".AddSlashes(pg_result($resaco,$iresaco,'tre13_pontoparada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3846,21375,'','".AddSlashes(pg_result($resaco,$iresaco,'tre13_escolaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pontoparadaescolaproc
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($tre13_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " tre13_sequencial = $tre13_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vínculo do Ponto de Parada e Escola de Procedência não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tre13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vínculo do Ponto de Parada e Escola de Procedência não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tre13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tre13_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pontoparadaescolaproc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($tre13_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from pontoparadaescolaproc ";
     $sql .= "      inner join pontoparada  on  pontoparada.tre04_sequencial = pontoparadaescolaproc.tre13_pontoparada";
     $sql .= "      inner join escolaproc  on  escolaproc.ed82_i_codigo = pontoparadaescolaproc.tre13_escolaproc";
     $sql .= "      inner join cadenderbairrocadenderrua  on  cadenderbairrocadenderrua.db87_sequencial = pontoparada.tre04_cadenderbairrocadenderrua";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = escolaproc.ed82_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = escolaproc.ed82_i_censouf";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = escolaproc.ed82_i_censomunic";
     $sql .= "      left  join censodistrito  on  censodistrito.ed262_i_codigo = escolaproc.ed82_i_censodistrito";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($tre13_sequencial)) {
         $sql2 .= " where pontoparadaescolaproc.tre13_sequencial = $tre13_sequencial "; 
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
   public function sql_query_file ($tre13_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from pontoparadaescolaproc ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($tre13_sequencial)){
         $sql2 .= " where pontoparadaescolaproc.tre13_sequencial = $tre13_sequencial "; 
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
