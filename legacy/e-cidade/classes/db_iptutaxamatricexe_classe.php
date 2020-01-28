<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE iptutaxamatricexe
class cl_iptutaxamatricexe { 
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
   var $j10_iptutaxamatricexe = 0; 
   var $j10_iptutaxamatric = 0; 
   var $j10_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j10_iptutaxamatricexe = int4 = Codigo 
                 j10_iptutaxamatric = int4 = Codigo 
                 j10_anousu = int4 = Anousu 
                 ";
   //funcao construtor da classe 
   function cl_iptutaxamatricexe() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptutaxamatricexe"); 
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
       $this->j10_iptutaxamatricexe = ($this->j10_iptutaxamatricexe == ""?@$GLOBALS["HTTP_POST_VARS"]["j10_iptutaxamatricexe"]:$this->j10_iptutaxamatricexe);
       $this->j10_iptutaxamatric = ($this->j10_iptutaxamatric == ""?@$GLOBALS["HTTP_POST_VARS"]["j10_iptutaxamatric"]:$this->j10_iptutaxamatric);
       $this->j10_anousu = ($this->j10_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j10_anousu"]:$this->j10_anousu);
     }else{
       $this->j10_iptutaxamatricexe = ($this->j10_iptutaxamatricexe == ""?@$GLOBALS["HTTP_POST_VARS"]["j10_iptutaxamatricexe"]:$this->j10_iptutaxamatricexe);
       $this->j10_iptutaxamatric = ($this->j10_iptutaxamatric == ""?@$GLOBALS["HTTP_POST_VARS"]["j10_iptutaxamatric"]:$this->j10_iptutaxamatric);
     }
   }
   // funcao para inclusao
   function incluir ($j10_iptutaxamatricexe){ 
      $this->atualizacampos();
     if($this->j10_anousu == null ){ 
       $this->erro_sql = " Campo Anousu nao Informado.";
       $this->erro_campo = "j10_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j10_iptutaxamatricexe == "" || $j10_iptutaxamatricexe == null ){
       $result = db_query("select nextval('iptutaxamatricexe_j10_iptutaxamatricexe_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptutaxamatricexe_j10_iptutaxamatricexe_seq do campo: j10_iptutaxamatricexe"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j10_iptutaxamatricexe = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptutaxamatricexe_j10_iptutaxamatricexe_seq");
       if(($result != false) && (pg_result($result,0,0) < $j10_iptutaxamatricexe)){
         $this->erro_sql = " Campo j10_iptutaxamatricexe maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j10_iptutaxamatricexe = $j10_iptutaxamatricexe; 
       }
     }
     if(($this->j10_iptutaxamatricexe == null) || ($this->j10_iptutaxamatricexe == "") ){ 
       $this->erro_sql = " Campo j10_iptutaxamatricexe nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptutaxamatricexe(
                                       j10_iptutaxamatricexe 
                                      ,j10_iptutaxamatric 
                                      ,j10_anousu 
                       )
                values (
                                $this->j10_iptutaxamatricexe 
                               ,$this->j10_iptutaxamatric 
                               ,$this->j10_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "iptutaxamatricexe ($this->j10_iptutaxamatricexe) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "iptutaxamatricexe já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "iptutaxamatricexe ($this->j10_iptutaxamatricexe) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j10_iptutaxamatricexe;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j10_iptutaxamatricexe));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9497,'$this->j10_iptutaxamatricexe','I')");
       $resac = db_query("insert into db_acount values($acount,1631,9497,'','".AddSlashes(pg_result($resaco,0,'j10_iptutaxamatricexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1631,9498,'','".AddSlashes(pg_result($resaco,0,'j10_iptutaxamatric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1631,9499,'','".AddSlashes(pg_result($resaco,0,'j10_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j10_iptutaxamatricexe=null) { 
      $this->atualizacampos();
     $sql = " update iptutaxamatricexe set ";
     $virgula = "";
     if(trim($this->j10_iptutaxamatricexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j10_iptutaxamatricexe"])){ 
       $sql  .= $virgula." j10_iptutaxamatricexe = $this->j10_iptutaxamatricexe ";
       $virgula = ",";
       if(trim($this->j10_iptutaxamatricexe) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "j10_iptutaxamatricexe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j10_iptutaxamatric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j10_iptutaxamatric"])){ 
       $sql  .= $virgula." j10_iptutaxamatric = $this->j10_iptutaxamatric ";
       $virgula = ",";
       if(trim($this->j10_iptutaxamatric) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "j10_iptutaxamatric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j10_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j10_anousu"])){ 
       $sql  .= $virgula." j10_anousu = $this->j10_anousu ";
       $virgula = ",";
       if(trim($this->j10_anousu) == null ){ 
         $this->erro_sql = " Campo Anousu nao Informado.";
         $this->erro_campo = "j10_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j10_iptutaxamatricexe!=null){
       $sql .= " j10_iptutaxamatricexe = $this->j10_iptutaxamatricexe";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j10_iptutaxamatricexe));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9497,'$this->j10_iptutaxamatricexe','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j10_iptutaxamatricexe"]))
           $resac = db_query("insert into db_acount values($acount,1631,9497,'".AddSlashes(pg_result($resaco,$conresaco,'j10_iptutaxamatricexe'))."','$this->j10_iptutaxamatricexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j10_iptutaxamatric"]))
           $resac = db_query("insert into db_acount values($acount,1631,9498,'".AddSlashes(pg_result($resaco,$conresaco,'j10_iptutaxamatric'))."','$this->j10_iptutaxamatric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j10_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1631,9499,'".AddSlashes(pg_result($resaco,$conresaco,'j10_anousu'))."','$this->j10_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "iptutaxamatricexe nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j10_iptutaxamatricexe;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "iptutaxamatricexe nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j10_iptutaxamatricexe;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j10_iptutaxamatricexe;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j10_iptutaxamatricexe=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j10_iptutaxamatricexe));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9497,'$j10_iptutaxamatricexe','E')");
         $resac = db_query("insert into db_acount values($acount,1631,9497,'','".AddSlashes(pg_result($resaco,$iresaco,'j10_iptutaxamatricexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1631,9498,'','".AddSlashes(pg_result($resaco,$iresaco,'j10_iptutaxamatric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1631,9499,'','".AddSlashes(pg_result($resaco,$iresaco,'j10_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptutaxamatricexe
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j10_iptutaxamatricexe != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j10_iptutaxamatricexe = $j10_iptutaxamatricexe ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "iptutaxamatricexe nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j10_iptutaxamatricexe;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "iptutaxamatricexe nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j10_iptutaxamatricexe;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j10_iptutaxamatricexe;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptutaxamatricexe";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>