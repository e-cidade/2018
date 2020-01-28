<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2015  DBSeller Servicos de Informatica             
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
//MODULO: recursoshumanos
//CLASSE DA ENTIDADE assentadb_cadattdinamicovalorgrupo
class cl_assentadb_cadattdinamicovalorgrupo { 
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
   var $h80_db_cadattdinamicovalorgrupo = 0; 
   var $h80_assenta = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h80_db_cadattdinamicovalorgrupo = int4 = Codigo Grupo 
                 h80_assenta = int4 = Codigo do Assentamento 
                 ";
   //funcao construtor da classe 
   function cl_assentadb_cadattdinamicovalorgrupo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("assentadb_cadattdinamicovalorgrupo"); 
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
       $this->h80_db_cadattdinamicovalorgrupo = ($this->h80_db_cadattdinamicovalorgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["h80_db_cadattdinamicovalorgrupo"]:$this->h80_db_cadattdinamicovalorgrupo);
       $this->h80_assenta = ($this->h80_assenta == ""?@$GLOBALS["HTTP_POST_VARS"]["h80_assenta"]:$this->h80_assenta);
     }else{
       $this->h80_db_cadattdinamicovalorgrupo = ($this->h80_db_cadattdinamicovalorgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["h80_db_cadattdinamicovalorgrupo"]:$this->h80_db_cadattdinamicovalorgrupo);
       $this->h80_assenta = ($this->h80_assenta == ""?@$GLOBALS["HTTP_POST_VARS"]["h80_assenta"]:$this->h80_assenta);
     }
   }
   // funcao para Inclusão
   function incluir ($h80_assenta,$h80_db_cadattdinamicovalorgrupo){ 


      $this->atualizacampos();
       $this->h80_assenta = $h80_assenta; 
       $this->h80_db_cadattdinamicovalorgrupo = $h80_db_cadattdinamicovalorgrupo; 
     if(($this->h80_assenta == null) || ($this->h80_assenta == "") ){ 
       $this->erro_sql = " Campo h80_assenta não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->h80_db_cadattdinamicovalorgrupo == null) || ($this->h80_db_cadattdinamicovalorgrupo == "") ){ 
       $this->erro_sql = " Campo h80_db_cadattdinamicovalorgrupo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into assentadb_cadattdinamicovalorgrupo(
                                       h80_db_cadattdinamicovalorgrupo 
                                      ,h80_assenta 
                       )
                values (
                                $this->h80_db_cadattdinamicovalorgrupo 
                               ,$this->h80_assenta 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "assentadb_cadattdinamicovalorgrupo ($this->h80_assenta."-".$this->h80_db_cadattdinamicovalorgrupo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "assentadb_cadattdinamicovalorgrupo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "assentadb_cadattdinamicovalorgrupo ($this->h80_assenta."-".$this->h80_db_cadattdinamicovalorgrupo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h80_assenta."-".$this->h80_db_cadattdinamicovalorgrupo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h80_assenta,$this->h80_db_cadattdinamicovalorgrupo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21207,'$this->h80_assenta','I')");
         $resac = db_query("insert into db_acountkey values($acount,21206,'$this->h80_db_cadattdinamicovalorgrupo','I')");
         $resac = db_query("insert into db_acount values($acount,3819,21206,'','".AddSlashes(pg_result($resaco,0,'h80_db_cadattdinamicovalorgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3819,21207,'','".AddSlashes(pg_result($resaco,0,'h80_assenta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($h80_assenta=null,$h80_db_cadattdinamicovalorgrupo=null) { 
      $this->atualizacampos();
     $sql = " update assentadb_cadattdinamicovalorgrupo set ";
     $virgula = "";
     if(trim($this->h80_db_cadattdinamicovalorgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h80_db_cadattdinamicovalorgrupo"])){ 
       $sql  .= $virgula." h80_db_cadattdinamicovalorgrupo = $this->h80_db_cadattdinamicovalorgrupo ";
       $virgula = ",";
       if(trim($this->h80_db_cadattdinamicovalorgrupo) == null ){ 
         $this->erro_sql = " Campo Codigo Grupo não informado.";
         $this->erro_campo = "h80_db_cadattdinamicovalorgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h80_assenta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h80_assenta"])){ 
       $sql  .= $virgula." h80_assenta = $this->h80_assenta ";
       $virgula = ",";
       if(trim($this->h80_assenta) == null ){ 
         $this->erro_sql = " Campo Codigo do Assentamento não informado.";
         $this->erro_campo = "h80_assenta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h80_assenta!=null){
       $sql .= " h80_assenta = $this->h80_assenta";
     }
     if($h80_db_cadattdinamicovalorgrupo!=null){
       $sql .= " and  h80_db_cadattdinamicovalorgrupo = $this->h80_db_cadattdinamicovalorgrupo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h80_assenta,$this->h80_db_cadattdinamicovalorgrupo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21207,'$this->h80_assenta','A')");
           $resac = db_query("insert into db_acountkey values($acount,21206,'$this->h80_db_cadattdinamicovalorgrupo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h80_db_cadattdinamicovalorgrupo"]) || $this->h80_db_cadattdinamicovalorgrupo != "")
             $resac = db_query("insert into db_acount values($acount,3819,21206,'".AddSlashes(pg_result($resaco,$conresaco,'h80_db_cadattdinamicovalorgrupo'))."','$this->h80_db_cadattdinamicovalorgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h80_assenta"]) || $this->h80_assenta != "")
             $resac = db_query("insert into db_acount values($acount,3819,21207,'".AddSlashes(pg_result($resaco,$conresaco,'h80_assenta'))."','$this->h80_assenta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "assentadb_cadattdinamicovalorgrupo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h80_assenta."-".$this->h80_db_cadattdinamicovalorgrupo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "assentadb_cadattdinamicovalorgrupo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h80_assenta."-".$this->h80_db_cadattdinamicovalorgrupo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h80_assenta."-".$this->h80_db_cadattdinamicovalorgrupo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($h80_assenta=null,$h80_db_cadattdinamicovalorgrupo=null,$dbwhere=null) { 
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {
         $resaco = $this->sql_record($this->sql_query_file($h80_assenta,$h80_db_cadattdinamicovalorgrupo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21207,'$h80_assenta','E')");
           $resac  = db_query("insert into db_acountkey values($acount,21206,'$h80_db_cadattdinamicovalorgrupo','E')");
           $resac  = db_query("insert into db_acount values($acount,3819,21206,'','".AddSlashes(pg_result($resaco,$iresaco,'h80_db_cadattdinamicovalorgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3819,21207,'','".AddSlashes(pg_result($resaco,$iresaco,'h80_assenta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from assentadb_cadattdinamicovalorgrupo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($h80_assenta)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " h80_assenta = $h80_assenta ";
        }
        if (!empty($h80_db_cadattdinamicovalorgrupo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " h80_db_cadattdinamicovalorgrupo = $h80_db_cadattdinamicovalorgrupo ";
        }
     } else {
       $sql2 = $dbwhere;
     }

     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "assentadb_cadattdinamicovalorgrupo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h80_assenta."-".$h80_db_cadattdinamicovalorgrupo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "assentadb_cadattdinamicovalorgrupo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h80_assenta."-".$h80_db_cadattdinamicovalorgrupo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h80_assenta."-".$h80_db_cadattdinamicovalorgrupo;
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
        $this->erro_sql   = "Record Vazio na Tabela:assentadb_cadattdinamicovalorgrupo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($h80_assenta = null,$h80_db_cadattdinamicovalorgrupo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from assentadb_cadattdinamicovalorgrupo ";
     $sql .= "      inner join assenta  on  assenta.h16_codigo = assentadb_cadattdinamicovalorgrupo.h80_assenta";
     $sql .= "      inner join db_cadattdinamicovalorgrupo  on  db_cadattdinamicovalorgrupo.db120_sequencial = assentadb_cadattdinamicovalorgrupo.h80_db_cadattdinamicovalorgrupo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = assenta.h16_login";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = assenta.h16_assent";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = assenta.h16_regist";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h80_assenta)) {
         $sql2 .= " where assentadb_cadattdinamicovalorgrupo.h80_assenta = $h80_assenta "; 
       } 
       if (!empty($h80_db_cadattdinamicovalorgrupo)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " assentadb_cadattdinamicovalorgrupo.h80_db_cadattdinamicovalorgrupo = $h80_db_cadattdinamicovalorgrupo "; 
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
   public function sql_query_file ($h80_assenta = null,$h80_db_cadattdinamicovalorgrupo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from assentadb_cadattdinamicovalorgrupo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h80_assenta)){
         $sql2 .= " where assentadb_cadattdinamicovalorgrupo.h80_assenta = $h80_assenta "; 
       } 
       if (!empty($h80_db_cadattdinamicovalorgrupo)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " assentadb_cadattdinamicovalorgrupo.h80_db_cadattdinamicovalorgrupo = $h80_db_cadattdinamicovalorgrupo "; 
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
