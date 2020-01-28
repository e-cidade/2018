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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhpeslotaprinc
class cl_rhpeslotaprinc { 
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
   var $rh11_anousu = 0; 
   var $rh11_mesusu = 0; 
   var $rh11_regist = 0; 
   var $rh11_lotac = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh11_anousu = int4 = Ano 
                 rh11_mesusu = int4 = Mês 
                 rh11_regist = int4 = Matrícula 
                 rh11_lotac = int4 = Lotação 
                 ";
   //funcao construtor da classe 
   function cl_rhpeslotaprinc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpeslotaprinc"); 
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
       $this->rh11_anousu = ($this->rh11_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh11_anousu"]:$this->rh11_anousu);
       $this->rh11_mesusu = ($this->rh11_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh11_mesusu"]:$this->rh11_mesusu);
       $this->rh11_regist = ($this->rh11_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh11_regist"]:$this->rh11_regist);
       $this->rh11_lotac = ($this->rh11_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["rh11_lotac"]:$this->rh11_lotac);
     }else{
       $this->rh11_anousu = ($this->rh11_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh11_anousu"]:$this->rh11_anousu);
       $this->rh11_mesusu = ($this->rh11_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh11_mesusu"]:$this->rh11_mesusu);
       $this->rh11_regist = ($this->rh11_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh11_regist"]:$this->rh11_regist);
       $this->rh11_lotac = ($this->rh11_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["rh11_lotac"]:$this->rh11_lotac);
     }
   }
   // funcao para inclusao
   function incluir ($rh11_anousu,$rh11_mesusu,$rh11_regist,$rh11_lotac){ 
      $this->atualizacampos();
       $this->rh11_anousu = $rh11_anousu; 
       $this->rh11_mesusu = $rh11_mesusu; 
       $this->rh11_regist = $rh11_regist; 
       $this->rh11_lotac = $rh11_lotac; 
     if(($this->rh11_anousu == null) || ($this->rh11_anousu == "") ){ 
       $this->erro_sql = " Campo rh11_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh11_mesusu == null) || ($this->rh11_mesusu == "") ){ 
       $this->erro_sql = " Campo rh11_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh11_regist == null) || ($this->rh11_regist == "") ){ 
       $this->erro_sql = " Campo rh11_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh11_lotac == null) || ($this->rh11_lotac == "") ){ 
       $this->erro_sql = " Campo rh11_lotac nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpeslotaprinc(
                                       rh11_anousu 
                                      ,rh11_mesusu 
                                      ,rh11_regist 
                                      ,rh11_lotac 
                       )
                values (
                                $this->rh11_anousu 
                               ,$this->rh11_mesusu 
                               ,$this->rh11_regist 
                               ,$this->rh11_lotac 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lotação Principal ($this->rh11_anousu."-".$this->rh11_mesusu."-".$this->rh11_regist."-".$this->rh11_lotac) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lotação Principal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lotação Principal ($this->rh11_anousu."-".$this->rh11_mesusu."-".$this->rh11_regist."-".$this->rh11_lotac) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh11_anousu."-".$this->rh11_mesusu."-".$this->rh11_regist."-".$this->rh11_lotac;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh11_anousu,$this->rh11_mesusu,$this->rh11_regist,$this->rh11_lotac));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6062,'$this->rh11_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,6064,'$this->rh11_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,6065,'$this->rh11_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,6066,'$this->rh11_lotac','I')");
       $resac = db_query("insert into db_acount values($acount,974,6062,'','".AddSlashes(pg_result($resaco,0,'rh11_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,974,6064,'','".AddSlashes(pg_result($resaco,0,'rh11_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,974,6065,'','".AddSlashes(pg_result($resaco,0,'rh11_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,974,6066,'','".AddSlashes(pg_result($resaco,0,'rh11_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh11_anousu=null,$rh11_mesusu=null,$rh11_regist=null,$rh11_lotac=null) { 
      $this->atualizacampos();
     $sql = " update rhpeslotaprinc set ";
     $virgula = "";
     if(trim($this->rh11_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh11_anousu"])){ 
       $sql  .= $virgula." rh11_anousu = $this->rh11_anousu ";
       $virgula = ",";
       if(trim($this->rh11_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "rh11_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh11_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh11_mesusu"])){ 
       $sql  .= $virgula." rh11_mesusu = $this->rh11_mesusu ";
       $virgula = ",";
       if(trim($this->rh11_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "rh11_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh11_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh11_regist"])){ 
       $sql  .= $virgula." rh11_regist = $this->rh11_regist ";
       $virgula = ",";
       if(trim($this->rh11_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "rh11_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh11_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh11_lotac"])){ 
       $sql  .= $virgula." rh11_lotac = $this->rh11_lotac ";
       $virgula = ",";
       if(trim($this->rh11_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "rh11_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh11_anousu!=null){
       $sql .= " rh11_anousu = $this->rh11_anousu";
     }
     if($rh11_mesusu!=null){
       $sql .= " and  rh11_mesusu = $this->rh11_mesusu";
     }
     if($rh11_regist!=null){
       $sql .= " and  rh11_regist = $this->rh11_regist";
     }
     if($rh11_lotac!=null){
       $sql .= " and  rh11_lotac = $this->rh11_lotac";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh11_anousu,$this->rh11_mesusu,$this->rh11_regist,$this->rh11_lotac));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6062,'$this->rh11_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,6064,'$this->rh11_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,6065,'$this->rh11_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,6066,'$this->rh11_lotac','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh11_anousu"]))
           $resac = db_query("insert into db_acount values($acount,974,6062,'".AddSlashes(pg_result($resaco,$conresaco,'rh11_anousu'))."','$this->rh11_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh11_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,974,6064,'".AddSlashes(pg_result($resaco,$conresaco,'rh11_mesusu'))."','$this->rh11_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh11_regist"]))
           $resac = db_query("insert into db_acount values($acount,974,6065,'".AddSlashes(pg_result($resaco,$conresaco,'rh11_regist'))."','$this->rh11_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh11_lotac"]))
           $resac = db_query("insert into db_acount values($acount,974,6066,'".AddSlashes(pg_result($resaco,$conresaco,'rh11_lotac'))."','$this->rh11_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lotação Principal nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh11_anousu."-".$this->rh11_mesusu."-".$this->rh11_regist."-".$this->rh11_lotac;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lotação Principal nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh11_anousu."-".$this->rh11_mesusu."-".$this->rh11_regist."-".$this->rh11_lotac;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh11_anousu."-".$this->rh11_mesusu."-".$this->rh11_regist."-".$this->rh11_lotac;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh11_anousu=null,$rh11_mesusu=null,$rh11_regist=null,$rh11_lotac=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh11_anousu,$rh11_mesusu,$rh11_regist,$rh11_lotac));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6062,'$rh11_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,6064,'$rh11_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,6065,'$rh11_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,6066,'$rh11_lotac','E')");
         $resac = db_query("insert into db_acount values($acount,974,6062,'','".AddSlashes(pg_result($resaco,$iresaco,'rh11_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,974,6064,'','".AddSlashes(pg_result($resaco,$iresaco,'rh11_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,974,6065,'','".AddSlashes(pg_result($resaco,$iresaco,'rh11_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,974,6066,'','".AddSlashes(pg_result($resaco,$iresaco,'rh11_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhpeslotaprinc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh11_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh11_anousu = $rh11_anousu ";
        }
        if($rh11_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh11_mesusu = $rh11_mesusu ";
        }
        if($rh11_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh11_regist = $rh11_regist ";
        }
        if($rh11_lotac != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh11_lotac = $rh11_lotac ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lotação Principal nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh11_anousu."-".$rh11_mesusu."-".$rh11_regist."-".$rh11_lotac;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lotação Principal nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh11_anousu."-".$rh11_mesusu."-".$rh11_regist."-".$rh11_lotac;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh11_anousu."-".$rh11_mesusu."-".$rh11_regist."-".$rh11_lotac;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpeslotaprinc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh11_anousu=null,$rh11_mesusu=null,$rh11_regist=null,$rh11_lotac=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpeslotaprinc ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = rhpeslotaprinc.rh11_anousu 
		                                   and  pessoal.r01_mesusu = rhpeslotaprinc.rh11_mesusu 
																			 and  pessoal.r01_regist = rhpeslotaprinc.rh11_regist
																			 and  pessoal.r01_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhpeslotaprinc.rh11_lotac
		                                  and  rhlota.r70_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu 
		                                  and  funcao.r37_mesusu = pessoal.r01_mesusu 
																			and  funcao.r37_funcao = pessoal.r01_funcao
																			and  funcao.r37_instit = pessoal.r01_instit ";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pessoal.r01_anousu 
		                                   and  lotacao.r13_mesusu = pessoal.r01_mesusu 
																			 and  lotacao.r13_codigo = pessoal.r01_lotac
																			 and  lotacao.r13_instit = pessoal.r01_instit ";
     $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu 
		                                 and  cargo.r65_mesusu = pessoal.r01_mesusu 
																		 and  cargo.r65_cargo = pessoal.r01_cargo ";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = rhlota.r70_codestrut";
     $sql2 = "";
     if($dbwhere==""){
       if($rh11_anousu!=null ){
         $sql2 .= " where rhpeslotaprinc.rh11_anousu = $rh11_anousu "; 
       } 
       if($rh11_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhpeslotaprinc.rh11_mesusu = $rh11_mesusu "; 
       } 
       if($rh11_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhpeslotaprinc.rh11_regist = $rh11_regist "; 
       } 
       if($rh11_lotac!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhpeslotaprinc.rh11_lotac = $rh11_lotac "; 
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
   function sql_query_file ( $rh11_anousu=null,$rh11_mesusu=null,$rh11_regist=null,$rh11_lotac=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpeslotaprinc ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh11_anousu!=null ){
         $sql2 .= " where rhpeslotaprinc.rh11_anousu = $rh11_anousu "; 
       } 
       if($rh11_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhpeslotaprinc.rh11_mesusu = $rh11_mesusu "; 
       } 
       if($rh11_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhpeslotaprinc.rh11_regist = $rh11_regist "; 
       } 
       if($rh11_lotac!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhpeslotaprinc.rh11_lotac = $rh11_lotac "; 
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
}
?>