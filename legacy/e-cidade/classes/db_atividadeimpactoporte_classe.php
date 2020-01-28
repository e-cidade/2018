<?
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

//MODULO: meioambiente
//CLASSE DA ENTIDADE atividadeimpactoporte
class cl_atividadeimpactoporte { 
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
   var $am04_sequencial = 0; 
   var $am04_atividadeimpacto = 0; 
   var $am04_porteatividadeimpacto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 am04_sequencial = int4 = Porte 
                 am04_atividadeimpacto = int4 = Atividade 
                 am04_porteatividadeimpacto = int4 = Porte 
                 ";
   //funcao construtor da classe 
   function cl_atividadeimpactoporte() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atividadeimpactoporte"); 
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
       $this->am04_sequencial = ($this->am04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am04_sequencial"]:$this->am04_sequencial);
       $this->am04_atividadeimpacto = ($this->am04_atividadeimpacto == ""?@$GLOBALS["HTTP_POST_VARS"]["am04_atividadeimpacto"]:$this->am04_atividadeimpacto);
       $this->am04_porteatividadeimpacto = ($this->am04_porteatividadeimpacto == ""?@$GLOBALS["HTTP_POST_VARS"]["am04_porteatividadeimpacto"]:$this->am04_porteatividadeimpacto);
     }else{
       $this->am04_sequencial = ($this->am04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am04_sequencial"]:$this->am04_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($am04_sequencial){ 
      $this->atualizacampos();
     if($this->am04_atividadeimpacto == null ){ 
       $this->erro_sql = " Campo Atividade não informado.";
       $this->erro_campo = "am04_atividadeimpacto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am04_porteatividadeimpacto == null ){ 
       $this->erro_sql = " Campo Porte não informado.";
       $this->erro_campo = "am04_porteatividadeimpacto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am04_sequencial == "" || $am04_sequencial == null ){
       $result = db_query("select nextval('atividadeimpactoporte_am04_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atividadeimpactoporte_am04_sequencial_seq do campo: am04_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->am04_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from atividadeimpactoporte_am04_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am04_sequencial)){
         $this->erro_sql = " Campo am04_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am04_sequencial = $am04_sequencial; 
       }
     }
     if(($this->am04_sequencial == null) || ($this->am04_sequencial == "") ){ 
       $this->erro_sql = " Campo am04_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atividadeimpactoporte(
                                       am04_sequencial 
                                      ,am04_atividadeimpacto 
                                      ,am04_porteatividadeimpacto 
                       )
                values (
                                $this->am04_sequencial 
                               ,$this->am04_atividadeimpacto 
                               ,$this->am04_porteatividadeimpacto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Relação Atividade e Porte ($this->am04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Relação Atividade e Porte já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Relação Atividade e Porte ($this->am04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am04_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am04_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20778,'$this->am04_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3740,20778,'','".AddSlashes(pg_result($resaco,0,'am04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3740,20779,'','".AddSlashes(pg_result($resaco,0,'am04_atividadeimpacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3740,20780,'','".AddSlashes(pg_result($resaco,0,'am04_porteatividadeimpacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($am04_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update atividadeimpactoporte set ";
     $virgula = "";
     if(trim($this->am04_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am04_sequencial"])){ 
       $sql  .= $virgula." am04_sequencial = $this->am04_sequencial ";
       $virgula = ",";
       if(trim($this->am04_sequencial) == null ){ 
         $this->erro_sql = " Campo Porte não informado.";
         $this->erro_campo = "am04_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am04_atividadeimpacto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am04_atividadeimpacto"])){ 
       $sql  .= $virgula." am04_atividadeimpacto = $this->am04_atividadeimpacto ";
       $virgula = ",";
       if(trim($this->am04_atividadeimpacto) == null ){ 
         $this->erro_sql = " Campo Atividade não informado.";
         $this->erro_campo = "am04_atividadeimpacto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am04_porteatividadeimpacto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am04_porteatividadeimpacto"])){ 
       $sql  .= $virgula." am04_porteatividadeimpacto = $this->am04_porteatividadeimpacto ";
       $virgula = ",";
       if(trim($this->am04_porteatividadeimpacto) == null ){ 
         $this->erro_sql = " Campo Porte não informado.";
         $this->erro_campo = "am04_porteatividadeimpacto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am04_sequencial!=null){
       $sql .= " am04_sequencial = $this->am04_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am04_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20778,'$this->am04_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am04_sequencial"]) || $this->am04_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3740,20778,'".AddSlashes(pg_result($resaco,$conresaco,'am04_sequencial'))."','$this->am04_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am04_atividadeimpacto"]) || $this->am04_atividadeimpacto != "")
             $resac = db_query("insert into db_acount values($acount,3740,20779,'".AddSlashes(pg_result($resaco,$conresaco,'am04_atividadeimpacto'))."','$this->am04_atividadeimpacto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am04_porteatividadeimpacto"]) || $this->am04_porteatividadeimpacto != "")
             $resac = db_query("insert into db_acount values($acount,3740,20780,'".AddSlashes(pg_result($resaco,$conresaco,'am04_porteatividadeimpacto'))."','$this->am04_porteatividadeimpacto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Relação Atividade e Porte nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Relação Atividade e Porte nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($am04_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am04_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20778,'$am04_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3740,20778,'','".AddSlashes(pg_result($resaco,$iresaco,'am04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3740,20779,'','".AddSlashes(pg_result($resaco,$iresaco,'am04_atividadeimpacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3740,20780,'','".AddSlashes(pg_result($resaco,$iresaco,'am04_porteatividadeimpacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from atividadeimpactoporte
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am04_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am04_sequencial = $am04_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Relação Atividade e Porte nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Relação Atividade e Porte nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am04_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:atividadeimpactoporte";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($am04_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from atividadeimpactoporte ";
     $sql .= "      inner join porteatividadeimpacto  on  porteatividadeimpacto.am02_sequencial = atividadeimpactoporte.am04_porteatividadeimpacto";
     $sql .= "      inner join atividadeimpacto  on  atividadeimpacto.am03_sequencial = atividadeimpactoporte.am04_atividadeimpacto";
     $sql .= "      inner join criterioatividadeimpacto  on  criterioatividadeimpacto.am01_sequencial = atividadeimpacto.am03_criterioatividadeimpacto";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am04_sequencial)) {
         $sql2 .= " where atividadeimpactoporte.am04_sequencial = $am04_sequencial "; 
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
   public function sql_query_file ($am04_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from atividadeimpactoporte ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am04_sequencial)){
         $sql2 .= " where atividadeimpactoporte.am04_sequencial = $am04_sequencial "; 
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
