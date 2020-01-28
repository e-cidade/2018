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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE sau_cgserrado
class cl_sau_cgserrado { 
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
   var $s128_i_codigo = 0; 
   var $s128_i_numcgs = 0; 
   var $s128_v_nome = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s128_i_codigo = int4 = Código 
                 s128_i_numcgs = int4 = CGS 
                 s128_v_nome = varchar(255) = Nome 
                 ";
   //funcao construtor da classe 
   function cl_sau_cgserrado() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_cgserrado"); 
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
       $this->s128_i_codigo = ($this->s128_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s128_i_codigo"]:$this->s128_i_codigo);
       $this->s128_i_numcgs = ($this->s128_i_numcgs == ""?@$GLOBALS["HTTP_POST_VARS"]["s128_i_numcgs"]:$this->s128_i_numcgs);
       $this->s128_v_nome = ($this->s128_v_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["s128_v_nome"]:$this->s128_v_nome);
     }else{
       $this->s128_i_codigo = ($this->s128_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s128_i_codigo"]:$this->s128_i_codigo);
       $this->s128_i_numcgs = ($this->s128_i_numcgs == ""?@$GLOBALS["HTTP_POST_VARS"]["s128_i_numcgs"]:$this->s128_i_numcgs);
     }
   }
   // funcao para Inclusão
   function incluir ($s128_i_codigo,$s128_i_numcgs){ 
      $this->atualizacampos();
     if($this->s128_v_nome == null ){ 
       $this->erro_sql = " Campo Nome não informado.";
       $this->erro_campo = "s128_v_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->s128_i_codigo = $s128_i_codigo; 
       $this->s128_i_numcgs = $s128_i_numcgs; 
     if(($this->s128_i_codigo == null) || ($this->s128_i_codigo == "") ){ 
       $this->erro_sql = " Campo s128_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->s128_i_numcgs == null) || ($this->s128_i_numcgs == "") ){ 
       $this->erro_sql = " Campo s128_i_numcgs não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_cgserrado(
                                       s128_i_codigo 
                                      ,s128_i_numcgs 
                                      ,s128_v_nome 
                       )
                values (
                                $this->s128_i_codigo 
                               ,$this->s128_i_numcgs 
                               ,'$this->s128_v_nome' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cgserrado ($this->s128_i_codigo."-".$this->s128_i_numcgs) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cgserrado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cgserrado ($this->s128_i_codigo."-".$this->s128_i_numcgs) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s128_i_codigo."-".$this->s128_i_numcgs;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s128_i_codigo,$this->s128_i_numcgs  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15473,'$this->s128_i_codigo','I')");
         $resac = db_query("insert into db_acountkey values($acount,15474,'$this->s128_i_numcgs','I')");
         $resac = db_query("insert into db_acount values($acount,2713,15473,'','".AddSlashes(pg_result($resaco,0,'s128_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2713,15474,'','".AddSlashes(pg_result($resaco,0,'s128_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2713,15475,'','".AddSlashes(pg_result($resaco,0,'s128_v_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($s128_i_codigo=null,$s128_i_numcgs=null) { 
      $this->atualizacampos();
     $sql = " update sau_cgserrado set ";
     $virgula = "";
     if(trim($this->s128_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s128_i_codigo"])){ 
       $sql  .= $virgula." s128_i_codigo = $this->s128_i_codigo ";
       $virgula = ",";
       if(trim($this->s128_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "s128_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s128_i_numcgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s128_i_numcgs"])){ 
       $sql  .= $virgula." s128_i_numcgs = $this->s128_i_numcgs ";
       $virgula = ",";
       if(trim($this->s128_i_numcgs) == null ){ 
         $this->erro_sql = " Campo CGS não informado.";
         $this->erro_campo = "s128_i_numcgs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s128_v_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s128_v_nome"])){ 
       $sql  .= $virgula." s128_v_nome = '$this->s128_v_nome' ";
       $virgula = ",";
       if(trim($this->s128_v_nome) == null ){ 
         $this->erro_sql = " Campo Nome não informado.";
         $this->erro_campo = "s128_v_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s128_i_codigo!=null){
       $sql .= " s128_i_codigo = $this->s128_i_codigo";
     }
     if($s128_i_numcgs!=null){
       $sql .= " and  s128_i_numcgs = $this->s128_i_numcgs";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s128_i_codigo,$this->s128_i_numcgs));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,15473,'$this->s128_i_codigo','A')");
           $resac = db_query("insert into db_acountkey values($acount,15474,'$this->s128_i_numcgs','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s128_i_codigo"]) || $this->s128_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2713,15473,'".AddSlashes(pg_result($resaco,$conresaco,'s128_i_codigo'))."','$this->s128_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s128_i_numcgs"]) || $this->s128_i_numcgs != "")
             $resac = db_query("insert into db_acount values($acount,2713,15474,'".AddSlashes(pg_result($resaco,$conresaco,'s128_i_numcgs'))."','$this->s128_i_numcgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s128_v_nome"]) || $this->s128_v_nome != "")
             $resac = db_query("insert into db_acount values($acount,2713,15475,'".AddSlashes(pg_result($resaco,$conresaco,'s128_v_nome'))."','$this->s128_v_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cgserrado não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s128_i_codigo."-".$this->s128_i_numcgs;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cgserrado não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s128_i_codigo."-".$this->s128_i_numcgs;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s128_i_codigo."-".$this->s128_i_numcgs;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($s128_i_codigo=null,$s128_i_numcgs=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($s128_i_codigo,$s128_i_numcgs));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,15473,'$s128_i_codigo','E')");
           $resac  = db_query("insert into db_acountkey values($acount,15474,'$s128_i_numcgs','E')");
           $resac  = db_query("insert into db_acount values($acount,2713,15473,'','".AddSlashes(pg_result($resaco,$iresaco,'s128_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2713,15474,'','".AddSlashes(pg_result($resaco,$iresaco,'s128_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2713,15475,'','".AddSlashes(pg_result($resaco,$iresaco,'s128_v_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from sau_cgserrado
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($s128_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " s128_i_codigo = $s128_i_codigo ";
        }
        if (!empty($s128_i_numcgs)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " s128_i_numcgs = $s128_i_numcgs ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cgserrado não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s128_i_codigo."-".$s128_i_numcgs;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cgserrado não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s128_i_codigo."-".$s128_i_numcgs;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s128_i_codigo."-".$s128_i_numcgs;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_cgserrado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($s128_i_codigo = null,$s128_i_numcgs = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from sau_cgserrado ";
     $sql .= "      inner join sau_cgscorreto on sau_cgscorreto.s127_i_codigo = sau_cgserrado.s128_i_codigo ";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_cgserrado.s128_i_numcgs";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($s128_i_codigo)) {
         $sql2 .= " where sau_cgserrado.s128_i_codigo = $s128_i_codigo "; 
       } 
       if (!empty($s128_i_numcgs)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " sau_cgserrado.s128_i_numcgs = $s128_i_numcgs "; 
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
   public function sql_query_file ($s128_i_codigo = null,$s128_i_numcgs = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from sau_cgserrado ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($s128_i_codigo)){
         $sql2 .= " where sau_cgserrado.s128_i_codigo = $s128_i_codigo "; 
       } 
       if (!empty($s128_i_numcgs)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " sau_cgserrado.s128_i_numcgs = $s128_i_numcgs "; 
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
